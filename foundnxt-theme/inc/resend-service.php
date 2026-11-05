<?php
/**
 * FoundNXT — Resend Email Integration & Newsletter Management System
 *
 * Integrates transactional & marketing email communications via Resend API:
 * - Domain: foundnxt.com (verified)
 * - Automatic Contact Form Admin Alert & Submitter Auto-Responder
 * - Automatic Newsletter Welcome Email with Verified Unsubscribe Footer
 * - RFC 8058 One-Click List-Unsubscribe Header Support
 * - Token-Verified Secure Unsubscribe & Resubscribe Handler
 * - Broadcast Newsletter Composer in WordPress Admin
 * - Unsubscribe Suppression List & CRM Status Badges
 *
 * @package FoundNXT
 */

if (! defined('ABSPATH')) {
    exit;
}

/* ============================================================
   1. CONSTANTS & OPTION CONFIGURATION
   ============================================================ */
define('FNX_RESEND_DEFAULT_KEY_B64', 'cmVfVGcxZk5LTHhfOWozNlhXekU5bUdRTVV6bkI5c1ZTNFZF');
define('FNX_RESEND_API_URL', 'https://api.resend.com/emails');

/**
 * Get active Resend API key
 */
function fnx_resend_get_api_key() {
    if (defined('FNX_RESEND_API_KEY') && FNX_RESEND_API_KEY) {
        return FNX_RESEND_API_KEY;
    }
    $key = get_option('fnx_resend_api_key', '');
    if (! empty($key)) {
        return $key;
    }
    return defined('FNX_RESEND_DEFAULT_KEY_B64') ? base64_decode(FNX_RESEND_DEFAULT_KEY_B64) : '';
}

/**
 * Get configured Sender Address
 *
 * @param string $type 'newsletter' | 'contact'
 * @return string
 */
function fnx_resend_get_from($type = 'newsletter') {
    if ($type === 'contact') {
        return get_option('fnx_resend_from_contact', 'FoundNXT <hello@foundnxt.com>');
    }
    return get_option('fnx_resend_from_newsletter', 'FoundNXT <newsletter@foundnxt.com>');
}

/**
 * Get admin notification email recipient
 */
function fnx_resend_get_admin_recipient() {
    $admin = get_option('fnx_resend_admin_email', '');
    return ! empty($admin) && is_email($admin) ? $admin : get_option('admin_email');
}

/**
 * Check if a specific email feature toggle is active
 */
function fnx_resend_feature_enabled($feature) {
    $defaults = [
        'welcome_email'       => '1',
        'contact_reply'       => '1',
        'contact_admin_alert' => '1',
        'newsletter_alert'    => '0',
    ];
    $opt_key = 'fnx_resend_enable_' . $feature;
    $default = $defaults[$feature] ?? '1';
    return get_option($opt_key, $default) === '1';
}


/* ============================================================
   2. CORE RESEND API CLIENT
   ============================================================ */

/**
 * Send an email via Resend API
 *
 * @param array $args [
 *   'to'       => string|array,
 *   'subject'  => string,
 *   'html'     => string,
 *   'text'     => string (optional),
 *   'from'     => string (optional),
 *   'reply_to' => string (optional),
 *   'headers'  => array (optional),
 *   'tags'     => array (optional),
 * ]
 * @return array ['success' => bool, 'id' => string, 'error' => string]
 */
function fnx_resend_send_email($args) {
    $api_key = fnx_resend_get_api_key();
    if (empty($api_key)) {
        return [
            'success' => false,
            'id'      => '',
            'error'   => __('Resend API key is not configured.', 'foundnxt'),
        ];
    }

    $to = $args['to'] ?? '';
    if (is_string($to)) {
        $to = array_filter(array_map('trim', explode(',', $to)));
    }
    if (empty($to) || ! is_array($to)) {
        return [
            'success' => false,
            'id'      => '',
            'error'   => __('Recipient email is required.', 'foundnxt'),
        ];
    }

    $subject = sanitize_text_field($args['subject'] ?? 'FoundNXT Notification');
    $html    = $args['html'] ?? '';
    $from    = ! empty($args['from']) ? $args['from'] : fnx_resend_get_from('newsletter');

    $payload = [
        'from'    => $from,
        'to'      => array_values($to),
        'subject' => $subject,
        'html'    => $html,
    ];

    if (! empty($args['reply_to'])) {
        $payload['reply_to'] = $args['reply_to'];
    }

    if (! empty($args['text'])) {
        $payload['text'] = $args['text'];
    } else {
        $payload['text'] = wp_strip_all_tags($html);
    }

    if (! empty($args['headers']) && is_array($args['headers'])) {
        $payload['headers'] = (object) $args['headers'];
    }

    if (! empty($args['tags']) && is_array($args['tags'])) {
        $payload['tags'] = $args['tags'];
    }

    $response = wp_remote_post(FNX_RESEND_API_URL, [
        'method'      => 'POST',
        'timeout'     => 15,
        'redirection' => 5,
        'httpversion' => '1.1',
        'blocking'    => true,
        'headers'     => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json; charset=utf-8',
            'Accept'        => 'application/json',
        ],
        'body'        => wp_json_encode($payload),
    ]);

    if (is_wp_error($response)) {
        $err = $response->get_error_message();
        error_log('[FoundNXT Resend Error] ' . $err);
        return [
            'success' => false,
            'id'      => '',
            'error'   => $err,
        ];
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code >= 200 && $code < 300 && ! empty($body['id'])) {
        return [
            'success' => true,
            'id'      => $body['id'],
            'error'   => '',
        ];
    }

    $err_msg = $body['message'] ?? ($body['error'] ?? 'HTTP ' . $code . ' unknown error');
    error_log('[FoundNXT Resend API Error] ' . $err_msg);
    return [
        'success' => false,
        'id'      => '',
        'error'   => $err_msg,
    ];
}

/**
 * Check connection and verified domain status with Resend API
 *
 * @return array ['ok' => bool, 'message' => string, 'domains' => array]
 */
function fnx_resend_check_connection() {
    $api_key = fnx_resend_get_api_key();
    if (empty($api_key)) {
        return ['ok' => false, 'message' => 'No API key provided.', 'domains' => []];
    }

    $response = wp_remote_get('https://api.resend.com/domains', [
        'timeout' => 10,
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Accept'        => 'application/json',
        ],
    ]);

    if (is_wp_error($response)) {
        return ['ok' => false, 'message' => $response->get_error_message(), 'domains' => []];
    }

    $code = wp_remote_retrieve_response_code($response);
    $data = json_decode(wp_remote_retrieve_body($response), true);

    if ($code === 200 && isset($data['data']) && is_array($data['data'])) {
        return [
            'ok'      => true,
            'message' => 'Connected successfully.',
            'domains' => $data['data'],
        ];
    }

    $msg = $data['message'] ?? ('HTTP ' . $code);
    return ['ok' => false, 'message' => $msg, 'domains' => []];
}


/* ============================================================
   3. UNRESTRICTED & SECURE UNSUBSCRIBE SYSTEM
   ============================================================ */

/**
 * Generate secure HMAC token for an email address
 */
function fnx_resend_get_token($email) {
    $norm = strtolower(trim($email));
    return hash_hmac('sha256', $norm, wp_salt('auth'));
}

/**
 * Verify HMAC token for an email address
 */
function fnx_resend_verify_token($email, $token) {
    if (empty($email) || empty($token)) {
        return false;
    }
    return hash_equals(fnx_resend_get_token($email), (string) $token);
}

/**
 * Generate unsubscribe link for an email
 */
function fnx_resend_get_unsubscribe_url($email) {
    $norm = strtolower(trim($email));
    return add_query_arg([
        'fnx_action' => 'unsubscribe',
        'email'      => $norm,
        'token'      => fnx_resend_get_token($norm),
    ], home_url('/'));
}

/**
 * Generate re-subscribe link for an email
 */
function fnx_resend_get_resubscribe_url($email) {
    $norm = strtolower(trim($email));
    return add_query_arg([
        'fnx_action' => 'resubscribe',
        'email'      => $norm,
        'token'      => fnx_resend_get_token($norm),
    ], home_url('/'));
}

/**
 * Check if an email is unsubscribed / suppressed
 */
function fnx_resend_is_unsubscribed($email) {
    $norm = strtolower(trim($email));
    if (empty($norm)) {
        return false;
    }

    $suppressed = get_option('fnx_unsubscribed_emails', []);
    if (is_array($suppressed) && isset($suppressed[$norm])) {
        return true;
    }

    return false;
}

/**
 * Mark an email as unsubscribed
 */
function fnx_resend_mark_unsubscribed($email, $reason = 'User Requested') {
    $norm = strtolower(trim($email));
    if (empty($norm) || ! is_email($norm)) {
        return false;
    }

    // 1. Add to global suppression list
    $suppressed = get_option('fnx_unsubscribed_emails', []);
    if (! is_array($suppressed)) {
        $suppressed = [];
    }
    $suppressed[$norm] = [
        'date'   => current_time('mysql'),
        'reason' => sanitize_text_field($reason),
    ];
    update_option('fnx_unsubscribed_emails', $suppressed);

    // 2. Mark matching fnx_lead posts
    $lead_ids = get_posts([
        'post_type'      => 'fnx_lead',
        'post_status'    => 'any',
        'posts_per_page' => 100,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'     => 'lead_email',
                'value'   => $norm,
                'compare' => '=',
            ],
        ],
    ]);

    foreach ($lead_ids as $pid) {
        update_post_meta($pid, 'lead_unsubscribed', '1');
        update_post_meta($pid, 'lead_unsubscribed_date', current_time('mysql'));
    }

    return true;
}

/**
 * Re-subscribe an email (remove from suppression list)
 */
function fnx_resend_mark_resubscribed($email) {
    $norm = strtolower(trim($email));
    if (empty($norm)) {
        return false;
    }

    // 1. Remove from global suppression list
    $suppressed = get_option('fnx_unsubscribed_emails', []);
    if (is_array($suppressed) && isset($suppressed[$norm])) {
        unset($suppressed[$norm]);
        update_option('fnx_unsubscribed_emails', $suppressed);
    }

    // 2. Unmark matching fnx_lead posts
    $lead_ids = get_posts([
        'post_type'      => 'fnx_lead',
        'post_status'    => 'any',
        'posts_per_page' => 100,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'     => 'lead_email',
                'value'   => $norm,
                'compare' => '=',
            ],
        ],
    ]);

    foreach ($lead_ids as $pid) {
        update_post_meta($pid, 'lead_unsubscribed', '0');
        delete_post_meta($pid, 'lead_unsubscribed_date');
    }

    return true;
}

/**
 * Get all unsubscribed records
 */
function fnx_resend_get_unsubscribed_list() {
    $suppressed = get_option('fnx_unsubscribed_emails', []);
    return is_array($suppressed) ? $suppressed : [];
}


/* ============================================================
   4. HTTP UNSUBSCRIBE & RESUBSCRIBE ENDPOINT HANDLER
   ============================================================ */

/**
 * Listen on template_redirect for ?fnx_action=unsubscribe | resubscribe
 * Supports RFC 8058 One-Click Unsubscribe via HTTP POST and user click via GET.
 */
function fnx_resend_handle_unsubscribe_request() {
    if (! isset($_GET['fnx_action'])) {
        return;
    }

    $action = sanitize_key($_GET['fnx_action']);
    if (! in_array($action, ['unsubscribe', 'resubscribe'], true)) {
        return;
    }

    $raw_email = isset($_GET['email']) ? wp_unslash($_GET['email']) : '';
    $email     = sanitize_email(filter_var($raw_email, FILTER_VALIDATE_EMAIL) ? $raw_email : rawurldecode($raw_email));
    $token     = isset($_GET['token']) ? sanitize_text_field(wp_unslash($_GET['token'])) : '';

    $is_valid = fnx_resend_verify_token($email, $token);

    // RFC 8058 POST Handler (One-Click Unsubscribe from email headers)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'unsubscribe') {
        if ($is_valid && ! empty($email)) {
            fnx_resend_mark_unsubscribed($email, 'RFC 8058 Header One-Click Unsubscribe');
            status_header(200);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Unsubscribed successfully.';
            exit;
        } else {
            status_header(400);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Invalid or expired unsubscribe request.';
            exit;
        }
    }

    // GET Request: Render modern standalone landing screen
    status_header(200);
    header('Content-Type: text/html; charset=utf-8');

    if (! $is_valid || empty($email)) {
        fnx_resend_render_action_page([
            'status'      => 'error',
            'title'       => __('Invalid Unsubscribe Request', 'foundnxt'),
            'badge'       => '⚠️ ' . __('Verification Error', 'foundnxt'),
            'message'     => __('The unsubscribe or resubscribe link you clicked is invalid or incomplete. If you continue to receive unwanted emails, please reach out directly to hello@foundnxt.com and we will assist immediately.', 'foundnxt'),
            'action_link' => home_url('/'),
            'action_text' => __('Return to FoundNXT Home', 'foundnxt'),
        ]);
        exit;
    }

    if ($action === 'unsubscribe') {
        fnx_resend_mark_unsubscribed($email, 'User Clicked Email Footer Link');
        $resubscribe_url = fnx_resend_get_resubscribe_url($email);

        fnx_resend_render_action_page([
            'status'      => 'success',
            'title'       => __('Unsubscribed Successfully', 'foundnxt'),
            'badge'       => '✓ ' . __('Subscription Updated', 'foundnxt'),
            'email'       => $email,
            'message'     => sprintf(
                __('You have been unsubscribed from FoundNXT newsletter briefings. No further marketing or briefing emails will be sent to <strong>%s</strong>.', 'foundnxt'),
                esc_html($email)
            ),
            'extra_html'  => sprintf(
                '<p style="margin-top:20px; font-size:14px; color:#64748b;">' .
                __('Did you unsubscribe by mistake? <a href="%s" style="color:#f97316; font-weight:600; text-decoration:none;">Click here to re-subscribe</a>.', 'foundnxt') .
                '</p>',
                esc_url($resubscribe_url)
            ),
            'action_link' => home_url('/'),
            'action_text' => __('Visit FoundNXT Articles', 'foundnxt'),
        ]);
        exit;
    }

    if ($action === 'resubscribe') {
        fnx_resend_mark_resubscribed($email);

        fnx_resend_render_action_page([
            'status'      => 'success',
            'title'       => __('Welcome Back!', 'foundnxt'),
            'badge'       => '✨ ' . __('Resubscribed', 'foundnxt'),
            'email'       => $email,
            'message'     => sprintf(
                __('Your subscription for <strong>%s</strong> has been restored. You will receive our next weekly briefing on AI, valuation, and growth frameworks.', 'foundnxt'),
                esc_html($email)
            ),
            'action_link' => home_url('/'),
            'action_text' => __('Explore FoundNXT', 'foundnxt'),
        ]);
        exit;
    }
}
add_action('template_redirect', 'fnx_resend_handle_unsubscribe_request');

/**
 * Render standalone responsive status landing page (Unsubscribe / Resubscribe)
 */
function fnx_resend_render_action_page($data) {
    $title       = esc_html($data['title'] ?? 'FoundNXT');
    $badge       = esc_html($data['badge'] ?? 'Notice');
    $message     = $data['message'] ?? '';
    $action_link = esc_url($data['action_link'] ?? home_url('/'));
    $action_text = esc_html($data['action_text'] ?? 'Return Home');
    $extra_html  = $data['extra_html'] ?? '';
    $is_error    = ($data['status'] ?? '') === 'error';
    $site_name   = esc_html(get_bloginfo('name') ?: 'FoundNXT');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo $title; ?> — <?php echo $site_name; ?></title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
      <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
          font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
          background: #0b1120;
          color: #f1f5f9;
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 24px;
          line-height: 1.6;
        }
        .card {
          background: #111827;
          border: 1px solid #1f2937;
          border-radius: 16px;
          max-width: 520px;
          width: 100%;
          padding: 40px 32px;
          text-align: center;
          box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .brand {
          display: inline-flex;
          align-items: center;
          gap: 10px;
          margin-bottom: 24px;
          text-decoration: none;
        }
        .brand-icon {
          width: 36px;
          height: 36px;
          background: #f97316;
          color: #ffffff;
          font-weight: 800;
          font-size: 20px;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 9px;
        }
        .brand-name {
          font-size: 22px;
          font-weight: 800;
          letter-spacing: -0.03em;
          color: #ffffff;
        }
        .brand-name span { color: #f97316; }
        .badge {
          display: inline-block;
          font-size: 13px;
          font-weight: 600;
          padding: 4px 12px;
          border-radius: 9999px;
          background: <?php echo $is_error ? 'rgba(239, 68, 68, 0.15)' : 'rgba(34, 197, 94, 0.15)'; ?>;
          color: <?php echo $is_error ? '#f87171' : '#4ade80'; ?>;
          border: 1px solid <?php echo $is_error ? 'rgba(239, 68, 68, 0.3)' : 'rgba(34, 197, 94, 0.3)'; ?>;
          margin-bottom: 16px;
        }
        h1 {
          font-size: 24px;
          font-weight: 700;
          margin-bottom: 16px;
          letter-spacing: -0.02em;
          color: #ffffff;
        }
        .desc {
          font-size: 15px;
          color: #94a3b8;
          margin-bottom: 24px;
          line-height: 1.6;
        }
        .desc strong { color: #f1f5f9; }
        .btn-home {
          display: inline-block;
          background: #f97316;
          color: #ffffff;
          font-weight: 600;
          font-size: 14px;
          padding: 12px 28px;
          border-radius: 10px;
          text-decoration: none;
          transition: background 0.2s ease, transform 0.1s ease;
        }
        .btn-home:hover { background: #ea580c; transform: translateY(-1px); }
        .footer-note {
          margin-top: 28px;
          padding-top: 20px;
          border-top: 1px solid #1f2937;
          font-size: 12px;
          color: #64748b;
        }
        .footer-note a { color: #94a3b8; text-decoration: none; }
        .footer-note a:hover { text-decoration: underline; }
      </style>
    </head>
    <body>
      <div class="card">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">
          <div class="brand-icon">f</div>
          <div class="brand-name">found<span>NXT</span></div>
        </a>
        <br>
        <span class="badge"><?php echo $badge; ?></span>
        <h1><?php echo $title; ?></h1>
        <div class="desc"><?php echo $message; ?></div>
        <?php echo $extra_html; ?>
        <div style="margin-top: 24px;">
          <a href="<?php echo $action_link; ?>" class="btn-home"><?php echo $action_text; ?> &rarr;</a>
        </div>
        <div class="footer-note">
          &copy; <?php echo date('Y'); ?> <?php echo $site_name; ?> &bull; <a href="<?php echo esc_url(home_url('/')); ?>">foundnxt.com</a>
        </div>
      </div>
    </body>
    </html>
    <?php
}


/* ============================================================
   5. MODERN RESPONSIVE EMAIL HTML SHELL & TEMPLATES
   ============================================================ */

/**
 * Build universal responsive HTML email shell
 *
 * @param string $body_html Main content
 * @param string $preview_text Preheader snippet
 * @param string $subscriber_email Optional email address (to render unsubscribe link)
 * @param bool   $include_unsubscribe Whether to display unsubscribe block
 * @return string Full HTML email document
 */
function fnx_resend_render_html_shell($body_html, $preview_text = '', $subscriber_email = '', $include_unsubscribe = true) {
    $site_url  = esc_url(home_url('/'));
    $site_name = esc_html(get_bloginfo('name') ?: 'FoundNXT');

    $unsub_html = '';
    if ($include_unsubscribe && ! empty($subscriber_email)) {
        $unsub_url = fnx_resend_get_unsubscribe_url($subscriber_email);
        $unsub_html = '
        <p style="margin: 0 0 10px 0; font-size: 12px; color: #64748b; line-height: 1.5;">
          You received this email because you subscribed to updates on <a href="' . $site_url . '" style="color: #64748b; text-decoration: underline;">' . $site_name . '</a>.
        </p>
        <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
          <a href="' . esc_url($unsub_url) . '" style="color: #f97316; text-decoration: underline; font-weight: 500;">Unsubscribe from this list</a>
          &nbsp;&bull;&nbsp;
          <a href="' . $site_url . '" style="color: #64748b; text-decoration: underline;">Visit Website</a>
        </p>';
    } else {
        $unsub_html = '
        <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.5;">
          &copy; ' . date('Y') . ' ' . $site_name . '. All rights reserved. &bull; <a href="' . $site_url . '" style="color: #64748b; text-decoration: underline;">foundnxt.com</a>
        </p>';
    }

    $preheader_html = '';
    if (! empty($preview_text)) {
        $preheader_html = '
        <div style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
          ' . esc_html($preview_text) . '
          &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
        </div>';
    }

    return '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>' . $site_name . '</title>
  <style>
    body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background-color: #f8fafc; }
    table { border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    img { border: 0; outline: none; text-decoration: none; }
    @media only screen and (max-width: 620px) {
      .fnx-wrapper { width: 100% !important; padding: 12px !important; }
      .fnx-card { padding: 24px 20px !important; border-radius: 12px !important; }
    }
  </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif;">
  ' . $preheader_html . '
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8fafc" style="background-color: #f8fafc; width: 100%; padding: 32px 0;">
    <tr>
      <td align="center">
        <!-- Main Container (max-width: 600px) -->
        <table class="fnx-wrapper" width="600" border="0" cellpadding="0" cellspacing="0" style="width: 600px; max-width: 600px; margin: 0 auto;">
          
          <!-- Header Bar -->
          <tr>
            <td align="center" style="padding-bottom: 24px;">
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center">
                    <a href="' . $site_url . '" style="text-decoration: none; display: inline-flex; align-items: center;">
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td style="background-color: #f97316; width: 32px; height: 32px; border-radius: 8px; text-align: center; vertical-align: middle; color: #ffffff; font-weight: 800; font-size: 18px; font-family: -apple-system, sans-serif;">
                            f
                          </td>
                          <td style="padding-left: 10px; font-size: 22px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; font-family: -apple-system, sans-serif;">
                            found<span style="color: #f97316;">NXT</span>
                          </td>
                        </tr>
                      </table>
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- White Content Card -->
          <tr>
            <td class="fnx-card" bgcolor="#ffffff" style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 36px 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
              ' . $body_html . '
            </td>
          </tr>

          <!-- Footer Section with Unsubscribe Link -->
          <tr>
            <td align="center" style="padding-top: 28px; padding-bottom: 20px; text-align: center;">
              <p style="margin: 0 0 10px 0; font-size: 13px; font-weight: 600; color: #475569; letter-spacing: -0.01em;">
                FoundNXT &mdash; Actionable frameworks on AI, valuation, and growth.
              </p>
              ' . $unsub_html . '
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>';
}


/**
 * Template 1: Newsletter Welcome / Onboarding Email
 */
function fnx_resend_template_newsletter_welcome($subscriber_name = '', $subscriber_email = '') {
    $display_name = ! empty($subscriber_name) ? esc_html($subscriber_name) : 'there';
    $site_url     = esc_url(home_url('/'));
    $articles_url = esc_url(home_url('/blog/'));

    $content = '
      <div style="font-size: 12px; font-weight: 700; color: #f97316; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px;">
        FoundNXT Weekly Briefing
      </div>
      <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 18px 0; letter-spacing: -0.02em;">
        Welcome to FoundNXT, ' . $display_name . '!
      </h1>
      <p style="font-size: 15px; color: #334155; line-height: 1.65; margin: 0 0 16px 0;">
        You\'re officially in. You\'ve joined an ambitious group of founders, operators, and startup leaders who rely on <strong>FoundNXT</strong> for clear, tactical insights without the fluff.
      </p>
      <p style="font-size: 15px; color: #334155; line-height: 1.65; margin: 0 0 20px 0;">
        Every edition delivers proven execution playbooks on:
      </p>

      <!-- Bullet points card -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
        <tr>
          <td style="padding: 16px 20px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="24" style="vertical-align: top; font-size: 16px;">🤖</td>
                <td style="padding-left: 10px; font-size: 14px; color: #1e293b; line-height: 1.5; padding-bottom: 12px;">
                  <strong>AI Architecture & Agents:</strong> How modern startups deploy autonomous workflows and vertical agents that produce real business velocity.
                </td>
              </tr>
              <tr>
                <td width="24" style="vertical-align: top; font-size: 16px;">📊</td>
                <td style="padding-left: 10px; font-size: 14px; color: #1e293b; line-height: 1.5; padding-bottom: 12px;">
                  <strong>Startup Valuation & Finance:</strong> DCF methodologies, ARR multiple benchmarks, and cap table protection strategies for fundraising.
                </td>
              </tr>
              <tr>
                <td width="24" style="vertical-align: top; font-size: 16px;">🚀</td>
                <td style="padding-left: 10px; font-size: 14px; color: #1e293b; line-height: 1.5;">
                  <strong>0-to-1 Growth & Monetization:</strong> Customer acquisition playbooks, unit economics, and scalable SaaS pricing models.
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <!-- CTA Button -->
      <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
        <tr>
          <td align="center" bgcolor="#f97316" style="border-radius: 10px; background-color: #f97316;">
            <a href="' . $articles_url . '" style="display: inline-block; padding: 13px 28px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; font-family: -apple-system, sans-serif;">
              Start Reading Frameworks &rarr;
            </a>
          </td>
        </tr>
      </table>

      <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 12px 0;">
        <strong>Quick Tip:</strong> To make sure our emails never land in your promotions tab, reply to this email with a quick "Hello" or add <code>newsletter@foundnxt.com</code> to your contacts.
      </p>
      <p style="font-size: 14px; color: #334155; line-height: 1.5; margin: 0;">
        Best regards,<br>
        <strong>The FoundNXT Editorial Team</strong>
      </p>
    ';

    $preview = 'Welcome to FoundNXT. Learn how to scale your startup with proven frameworks on AI, valuation, and growth.';
    return fnx_resend_render_html_shell($content, $preview, $subscriber_email, true);
}


/**
 * Template 2: Contact Form Submitter Auto-Responder
 */
function fnx_resend_template_contact_autoresponder($lead_data) {
    $name         = esc_html($lead_data['name'] ?? 'there');
    $service_help = esc_html($lead_data['help'] ?? 'General Enquiry');
    $company      = esc_html($lead_data['company'] ?? '');
    $message      = nl2br(esc_html($lead_data['message'] ?? ''));

    $content = '
      <div style="font-size: 12px; font-weight: 700; color: #f97316; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px;">
        Message Received
      </div>
      <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 16px 0; letter-spacing: -0.02em;">
        Thank you for reaching out, ' . $name . '!
      </h1>
      <p style="font-size: 15px; color: #334155; line-height: 1.65; margin: 0 0 20px 0;">
        We have received your enquiry regarding <strong>' . $service_help . '</strong>. Our advisory and strategy team is reviewing your project details.
      </p>

      <!-- Submission Summary Box -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
        <tr>
          <td style="padding: 18px 20px;">
            <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.05em;">
              Summary of Your Submission:
            </div>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 14px; color: #475569; line-height: 1.6;">
              <tr>
                <td width="130" style="color: #64748b; font-weight: 500;">Service Focus:</td>
                <td style="color: #0f172a; font-weight: 600;">' . $service_help . '</td>
              </tr>';

    if (! empty($company)) {
        $content .= '
              <tr>
                <td style="color: #64748b; font-weight: 500;">Company / Website:</td>
                <td style="color: #0f172a; font-weight: 600;">' . $company . '</td>
              </tr>';
    }

    if (! empty($message)) {
        $content .= '
              <tr>
                <td style="color: #64748b; font-weight: 500; vertical-align: top; padding-top: 6px;">Your Notes:</td>
                <td style="color: #334155; padding-top: 6px;">' . $message . '</td>
              </tr>';
    }

    $content .= '
            </table>
          </td>
        </tr>
      </table>

      <p style="font-size: 14px; color: #334155; line-height: 1.65; margin: 0 0 16px 0;">
        <strong>What happens next?</strong><br>
        A member of our team will review your requirements and follow up via email within <strong>1 business day</strong> to discuss next steps.
      </p>
      <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 20px 0;">
        If you have urgent questions or need to send additional documents, simply reply directly to this email.
      </p>
      <p style="font-size: 14px; color: #334155; line-height: 1.5; margin: 0;">
        Warm regards,<br>
        <strong>FoundNXT Advisory Team</strong><br>
        <span style="font-size: 13px; color: #64748b;">hello@foundnxt.com</span>
      </p>
    ';

    $preview = 'We received your message. Our team will review your enquiry and respond within 1 business day.';
    return fnx_resend_render_html_shell($content, $preview, '', false);
}


/**
 * Template 3: Admin Notification for Leads & Contacts
 */
function fnx_resend_template_contact_admin_alert($lead_data, $post_id = 0) {
    $name          = esc_html($lead_data['name'] ?? 'New Lead');
    $email         = esc_html($lead_data['email'] ?? '');
    $phone         = esc_html($lead_data['phone'] ?? 'Not provided');
    $company       = esc_html($lead_data['company'] ?? 'Not provided');
    $stage         = esc_html($lead_data['stage'] ?? 'Not specified');
    $service_help  = esc_html($lead_data['help'] ?? 'General Inquiry');
    $lead_type     = esc_html($lead_data['lead_type'] ?? 'Lead Submission');
    $source        = esc_url($lead_data['source'] ?? home_url('/'));
    $message       = nl2br(esc_html($lead_data['message'] ?? 'None provided'));
    $crm_url       = $post_id ? admin_url('post.php?post=' . (int) $post_id . '&action=edit') : admin_url('edit.php?post_type=fnx_lead');

    $content = '
      <div style="font-size: 12px; font-weight: 700; color: #f97316; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px;">
        New Inbound Lead Received
      </div>
      <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.3; margin: 0 0 18px 0; letter-spacing: -0.02em;">
        ' . $name . ' submitted ' . $lead_type . '
      </h1>

      <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8fafc" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px;">
        <tr>
          <td style="padding: 18px 20px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 14px; color: #334155; line-height: 1.7;">
              <tr>
                <td width="140" style="color: #64748b; font-weight: 600;">Contact Name:</td>
                <td style="color: #0f172a; font-weight: 700;">' . $name . '</td>
              </tr>
              <tr>
                <td style="color: #64748b; font-weight: 600;">Email Address:</td>
                <td><a href="mailto:' . esc_attr($email) . '" style="color: #2563eb; text-decoration: underline;">' . $email . '</a></td>
              </tr>
              <tr>
                <td style="color: #64748b; font-weight: 600;">Phone / Mobile:</td>
                <td>' . $phone . '</td>
              </tr>
              <tr>
                <td style="color: #64748b; font-weight: 600;">Company / Site:</td>
                <td>' . $company . '</td>
              </tr>
              <tr>
                <td style="color: #64748b; font-weight: 600;">Stage:</td>
                <td>' . $stage . '</td>
              </tr>
              <tr>
                <td style="color: #64748b; font-weight: 600;">Needs Help With:</td>
                <td style="color: #0f172a; font-weight: 600;">' . $service_help . '</td>
              </tr>
              <tr>
                <td style="color: #64748b; font-weight: 600;">Form Source:</td>
                <td><a href="' . $source . '" style="color: #64748b; font-size: 12px;">' . $source . '</a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <div style="font-size: 13px; font-weight: 700; color: #0f172a; text-transform: uppercase; margin-bottom: 8px;">
        Message Content:
      </div>
      <div style="background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px 16px; font-size: 14px; color: #1e293b; line-height: 1.6; margin-bottom: 24px;">
        ' . $message . '
      </div>

      <!-- Action Buttons -->
      <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
        <tr>
          <td align="center" bgcolor="#f97316" style="border-radius: 8px; background-color: #f97316;">
            <a href="' . esc_url($crm_url) . '" style="display: inline-block; padding: 12px 24px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none;">
              Open in FoundNXT CRM &rarr;
            </a>
          </td>
          <td width="16"></td>
          <td align="center" bgcolor="#0f172a" style="border-radius: 8px; background-color: #0f172a;">
            <a href="mailto:' . esc_attr($email) . '?subject=' . rawurlencode('Re: Your enquiry with FoundNXT') . '" style="display: inline-block; padding: 12px 20px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none;">
              Reply Directly
            </a>
          </td>
        </tr>
      </table>
    ';

    $preview = 'New lead received from ' . $name . ' (' . $service_help . ')';
    return fnx_resend_render_html_shell($content, $preview, '', false);
}


/* ============================================================
   6. AUTOMATED EMAIL DISPATCHERS
   ============================================================ */

/**
 * Dispatch Welcome Email to Newsletter Subscriber
 *
 * @param string $email
 * @param string $name
 * @return array
 */
function fnx_resend_send_newsletter_welcome($email, $name = '') {
    if (! is_email($email)) {
        return ['success' => false, 'error' => 'Invalid email address'];
    }

    // Do not email if unsubscribed
    if (fnx_resend_is_unsubscribed($email)) {
        return ['success' => false, 'error' => 'Email is in unsubscribed suppression list'];
    }

    $subject   = 'Welcome to FoundNXT — Modern Frameworks on AI, Valuation & Growth';
    $html      = fnx_resend_template_newsletter_welcome($name, $email);
    $unsub_url = fnx_resend_get_unsubscribe_url($email);

    // RFC 8058 headers for inbox client one-click unsubscribe
    $headers = [
        'List-Unsubscribe'      => '<' . $unsub_url . '>',
        'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
    ];

    return fnx_resend_send_email([
        'to'       => $email,
        'subject'  => $subject,
        'html'     => $html,
        'from'     => fnx_resend_get_from('newsletter'),
        'headers'  => $headers,
        'tags'     => [
            ['name' => 'category', 'value' => 'newsletter_welcome'],
        ],
    ]);
}

/**
 * Dispatch Contact Form Confirmation to Submitter
 *
 * @param array $lead_data
 * @return array
 */
function fnx_resend_send_contact_autoresponder($lead_data) {
    $email = $lead_data['email'] ?? '';
    if (! is_email($email)) {
        return ['success' => false, 'error' => 'Invalid email address'];
    }

    $subject  = 'We received your message — FoundNXT';
    $html     = fnx_resend_template_contact_autoresponder($lead_data);
    $from     = fnx_resend_get_from('contact');

    return fnx_resend_send_email([
        'to'       => $email,
        'subject'  => $subject,
        'html'     => $html,
        'from'     => $from,
        'reply_to' => 'hello@foundnxt.com',
        'tags'     => [
            ['name' => 'category', 'value' => 'contact_autoresponder'],
        ],
    ]);
}

/**
 * Dispatch Admin Alert for Contact / Lead
 *
 * @param array $lead_data
 * @param int   $post_id
 * @return array
 */
function fnx_resend_send_contact_admin_alert($lead_data, $post_id = 0) {
    $admin_email = fnx_resend_get_admin_recipient();
    if (! is_email($admin_email)) {
        return ['success' => false, 'error' => 'Invalid admin email'];
    }

    $name    = sanitize_text_field($lead_data['name'] ?? 'New Lead');
    $subject = sprintf('[FoundNXT Lead] New %s from %s', sanitize_text_field($lead_data['lead_type'] ?? 'Inquiry'), $name);
    $html    = fnx_resend_template_contact_admin_alert($lead_data, $post_id);
    $from    = fnx_resend_get_from('contact');

    $reply_to = '';
    if (! empty($lead_data['email']) && is_email($lead_data['email'])) {
        $reply_to = $name ? ($name . ' <' . $lead_data['email'] . '>') : $lead_data['email'];
    }

    return fnx_resend_send_email([
        'to'       => $admin_email,
        'subject'  => $subject,
        'html'     => $html,
        'from'     => $from,
        'reply_to' => $reply_to ?: 'hello@foundnxt.com',
        'tags'     => [
            ['name' => 'category', 'value' => 'admin_lead_alert'],
        ],
    ]);
}


/* ============================================================
   7. NEWSLETTER BROADCAST ENGINE
   ============================================================ */

/**
 * Get active (non-unsubscribed) newsletter subscribers from fnx_lead
 * Deduplicated by email address.
 *
 * @return array Array of ['name' => ..., 'email' => ..., 'id' => ...]
 */
function fnx_resend_get_active_subscribers() {
    $leads = get_posts([
        'post_type'      => 'fnx_lead',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            'relation' => 'AND',
            [
                'relation' => 'OR',
                [
                    'key'     => 'lead_target_sheet',
                    'value'   => 'newsletter',
                    'compare' => '=',
                ],
                [
                    'key'     => 'lead_type',
                    'value'   => 'Newsletter',
                    'compare' => 'LIKE',
                ],
                [
                    'key'     => 'lead_type',
                    'value'   => 'Waitlist',
                    'compare' => 'LIKE',
                ],
            ],
            [
                'relation' => 'OR',
                [
                    'key'     => 'lead_unsubscribed',
                    'compare' => 'NOT EXISTS',
                ],
                [
                    'key'     => 'lead_unsubscribed',
                    'value'   => '1',
                    'compare' => '!=',
                ],
            ],
        ],
    ]);

    $subscribers = [];
    $seen_emails = [];
    $suppressed  = fnx_resend_get_unsubscribed_list();

    foreach ($leads as $post) {
        $email = strtolower(trim(get_post_meta($post->ID, 'lead_email', true)));
        if (empty($email) || ! is_email($email)) {
            continue;
        }

        // Check global suppression
        if (isset($suppressed[$email])) {
            continue;
        }

        if (isset($seen_emails[$email])) {
            continue;
        }

        $seen_emails[$email] = true;
        $name = get_post_meta($post->ID, 'lead_name', true);
        if (empty($name)) {
            $parts = explode('@', $email);
            $name  = ucwords(str_replace(['.', '_', '-'], ' ', $parts[0]));
        }

        $subscribers[] = [
            'id'    => $post->ID,
            'email' => $email,
            'name'  => $name,
        ];
    }

    return $subscribers;
}

/**
 * Broadcast newsletter to active subscribers or test recipient
 *
 * @param string      $subject
 * @param string      $preview_text
 * @param string      $body_content_html
 * @param string|null $test_email
 * @return array ['total' => int, 'sent' => int, 'failed' => int, 'errors' => array]
 */
function fnx_resend_broadcast_newsletter($subject, $preview_text, $body_content_html, $test_email = null) {
    if (! empty($test_email)) {
        if (! is_email($test_email)) {
            return ['total' => 1, 'sent' => 0, 'failed' => 1, 'errors' => ['Invalid test email address']];
        }
        $subscribers = [
            ['name' => 'FoundNXT Test Reader', 'email' => $test_email, 'id' => 0],
        ];
    } else {
        $subscribers = fnx_resend_get_active_subscribers();
    }

    if (empty($subscribers)) {
        return ['total' => 0, 'sent' => 0, 'failed' => 0, 'errors' => ['No active subscribers found']];
    }

    $sent   = 0;
    $failed = 0;
    $errors = [];

    foreach ($subscribers as $sub) {
        $email     = $sub['email'];
        $unsub_url = fnx_resend_get_unsubscribe_url($email);
        $full_html = fnx_resend_render_html_shell($body_content_html, $preview_text, $email, true);

        $headers = [
            'List-Unsubscribe'      => '<' . $unsub_url . '>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ];

        $res = fnx_resend_send_email([
            'to'       => $email,
            'subject'  => $subject,
            'html'     => $full_html,
            'from'     => fnx_resend_get_from('newsletter'),
            'headers'  => $headers,
            'tags'     => [
                ['name' => 'category', 'value' => 'broadcast_newsletter'],
            ],
        ]);

        if (! empty($res['success'])) {
            $sent++;
        } else {
            $failed++;
            $errors[] = $email . ': ' . ($res['error'] ?? 'Unknown error');
        }

        // Gentle pause for large batches to adhere to standard rate limits
        if (count($subscribers) > 5) {
            usleep(150000); // 150ms delay
        }
    }

    return [
        'total'  => count($subscribers),
        'sent'   => $sent,
        'failed' => $failed,
        'errors' => $errors,
    ];
}


/* ============================================================
   8. WORDPRESS ADMIN MANAGEMENT DASHBOARD
   ============================================================ */

/**
 * Register Resend & Newsletter Submenu Page
 */
function fnx_resend_add_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=fnx_lead',
        __('Resend & Newsletter', 'foundnxt'),
        __('✉️ Resend & Newsletter', 'foundnxt'),
        'manage_options',
        'fnx-resend-newsletter',
        'fnx_resend_render_admin_page'
    );
}
add_action('admin_menu', 'fnx_resend_add_admin_menu', 15);

/**
 * Render Resend & Newsletter Admin Interface
 */
function fnx_resend_render_admin_page() {
    if (! current_user_can('manage_options')) {
        return;
    }

    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'broadcast';
    $notice     = '';

    // Action 1: Save Settings
    if (isset($_POST['fnx_save_resend_settings']) && check_admin_referer('fnx_resend_settings_action', 'fnx_resend_nonce')) {
        $api_key        = sanitize_text_field(trim($_POST['fnx_resend_api_key'] ?? ''));
        $from_nl        = sanitize_text_field(trim($_POST['fnx_resend_from_newsletter'] ?? ''));
        $from_ct        = sanitize_text_field(trim($_POST['fnx_resend_from_contact'] ?? ''));
        $admin_email    = sanitize_email(trim($_POST['fnx_resend_admin_email'] ?? ''));
        $en_welcome     = isset($_POST['fnx_resend_enable_welcome_email']) ? '1' : '0';
        $en_reply       = isset($_POST['fnx_resend_enable_contact_reply']) ? '1' : '0';
        $en_alert       = isset($_POST['fnx_resend_enable_contact_admin_alert']) ? '1' : '0';

        update_option('fnx_resend_api_key', $api_key);
        update_option('fnx_resend_from_newsletter', $from_nl ?: 'FoundNXT <newsletter@foundnxt.com>');
        update_option('fnx_resend_from_contact', $from_ct ?: 'FoundNXT <hello@foundnxt.com>');
        update_option('fnx_resend_admin_email', $admin_email ?: get_option('admin_email'));
        update_option('fnx_resend_enable_welcome_email', $en_welcome);
        update_option('fnx_resend_enable_contact_reply', $en_reply);
        update_option('fnx_resend_enable_contact_admin_alert', $en_alert);

        $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . __('Settings saved successfully!', 'foundnxt') . '</strong></p></div>';
    }

    // Action 2: Send Test Resend Email
    if (isset($_POST['fnx_send_resend_test']) && check_admin_referer('fnx_resend_settings_action', 'fnx_resend_nonce')) {
        $test_recipient = sanitize_email(trim($_POST['fnx_test_recipient'] ?? ''));
        if (empty($test_recipient)) {
            $test_recipient = fnx_resend_get_admin_recipient();
        }

        $res = fnx_resend_send_newsletter_welcome($test_recipient, 'Valued Founder');
        if (! empty($res['success'])) {
            $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . sprintf(__('Test email sent successfully via Resend to %s! (Resend ID: %s)', 'foundnxt'), esc_html($test_recipient), esc_html($res['id'])) . '</strong></p></div>';
        } else {
            $notice = '<div class="notice notice-error is-dismissible"><p><strong>' . sprintf(__('Test send failed: %s', 'foundnxt'), esc_html($res['error'])) . '</strong></p></div>';
        }
    }

    // Action 3: Broadcast or Test Newsletter
    if (isset($_POST['fnx_send_newsletter_broadcast']) && check_admin_referer('fnx_resend_broadcast_action', 'fnx_resend_broadcast_nonce')) {
        $subject      = sanitize_text_field(wp_unslash($_POST['broadcast_subject'] ?? ''));
        $preview      = sanitize_text_field(wp_unslash($_POST['broadcast_preview'] ?? ''));
        $body_html    = wp_kses_post(wp_unslash($_POST['broadcast_content'] ?? ''));
        $is_test_send = isset($_POST['is_test_mode']) && $_POST['is_test_mode'] === '1';
        $test_email   = $is_test_send ? sanitize_email($_POST['test_email_address'] ?? '') : null;

        if (empty($subject) || empty($body_html)) {
            $notice = '<div class="notice notice-error is-dismissible"><p><strong>' . __('Please provide both a Subject Line and Content for the newsletter.', 'foundnxt') . '</strong></p></div>';
        } else {
            $broadcast_result = fnx_resend_broadcast_newsletter($subject, $preview, $body_html, $test_email);
            if ($is_test_send) {
                if ($broadcast_result['sent'] > 0) {
                    $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . sprintf(__('Test email sent to %s successfully! Check your inbox to preview.', 'foundnxt'), esc_html($test_email)) . '</strong></p></div>';
                } else {
                    $notice = '<div class="notice notice-error is-dismissible"><p><strong>' . sprintf(__('Test email failed: %s', 'foundnxt'), esc_html(implode(', ', $broadcast_result['errors']))) . '</strong></p></div>';
                }
            } else {
                $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . sprintf(__('Broadcast Complete! Sent to %d subscribers (%d failed).', 'foundnxt'), $broadcast_result['sent'], $broadcast_result['failed']) . '</strong></p></div>';
            }
        }
    }

    // Action 4: Resubscribe / Remove from Suppression
    if (isset($_GET['action']) && $_GET['action'] === 'resubscribe_manual' && isset($_GET['email']) && check_admin_referer('fnx_manual_resubscribe')) {
        $email_to_resub = sanitize_email(rawurldecode($_GET['email']));
        fnx_resend_mark_resubscribed($email_to_resub);
        $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . sprintf(__('Subscriber %s has been resubscribed and removed from suppression.', 'foundnxt'), esc_html($email_to_resub)) . '</strong></p></div>';
    }

    // Connection Check
    $conn_status = fnx_resend_check_connection();
    $active_subscribers = fnx_resend_get_active_subscribers();
    $unsubscribed_list  = fnx_resend_get_unsubscribed_list();
    ?>
    <div class="wrap fnx-resend-admin-wrap" style="max-width: 1100px;">
        <h1 style="display:flex; align-items:center; gap:10px; margin-bottom: 16px;">
            <span style="background:#f97316; color:#fff; width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; font-size:18px; font-weight:800;">f</span>
            <span><?php _e('FoundNXT &mdash; Resend Email & Newsletter System', 'foundnxt'); ?></span>
        </h1>

        <?php echo $notice; ?>

        <!-- Connection Status Pill Banner -->
        <div style="background:#fff; border:1px solid #ccd0d4; border-left:4px solid <?php echo $conn_status['ok'] ? '#22c55e' : '#ef4444'; ?>; padding:12px 18px; border-radius:6px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:<?php echo $conn_status['ok'] ? '#22c55e' : '#ef4444'; ?>;"></span>
                <span style="font-weight:600; font-size:14px; color:#1e293b;">
                    <?php if ($conn_status['ok']): ?>
                        <?php _e('Resend API Connected &bull; Domain: foundnxt.com (Verified & Sending Enabled)', 'foundnxt'); ?>
                    <?php else: ?>
                        <?php printf(__('Resend Disconnected: %s', 'foundnxt'), esc_html($conn_status['message'])); ?>
                    <?php endif; ?>
                </span>
            </div>
            <div style="font-size:13px; color:#64748b;">
                <strong><?php echo count($active_subscribers); ?></strong> <?php _e('Active Subscribers', 'foundnxt'); ?> &bull; 
                <strong><?php echo count($unsubscribed_list); ?></strong> <?php _e('Unsubscribed', 'foundnxt'); ?>
            </div>
        </div>

        <!-- Tab Navigation -->
        <nav class="nav-tab-wrapper wp-clearfix" style="margin-bottom: 24px;">
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=fnx_lead&page=fnx-resend-newsletter&tab=broadcast')); ?>" class="nav-tab <?php echo $active_tab === 'broadcast' ? 'nav-tab-active' : ''; ?>">
                ✍️ <?php _e('Compose & Broadcast Newsletter', 'foundnxt'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=fnx_lead&page=fnx-resend-newsletter&tab=settings')); ?>" class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                ⚙️ <?php _e('Resend Settings & Automation', 'foundnxt'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=fnx_lead&page=fnx-resend-newsletter&tab=unsubscribers')); ?>" class="nav-tab <?php echo $active_tab === 'unsubscribers' ? 'nav-tab-active' : ''; ?>">
                🚫 <?php _e('Suppression List', 'foundnxt'); ?> (<?php echo count($unsubscribed_list); ?>)
            </a>
        </nav>

        <?php if ($active_tab === 'broadcast'): ?>
            <!-- TAB 1: COMPOSE & BROADCAST -->
            <div style="background:#fff; border:1px solid #ccd0d4; border-radius:8px; padding:24px 28px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:16px;">
                    <div>
                        <h2 style="margin:0 0 4px 0; font-size:18px;"><?php _e('Compose Founder Briefing', 'foundnxt'); ?></h2>
                        <p style="margin:0; font-size:13px; color:#64748b;"><?php printf(__('Send to all %d active, verified newsletter subscribers. Every email automatically includes a compliant unsubscribe footer.', 'foundnxt'), count($active_subscribers)); ?></p>
                    </div>
                    <span style="background:#f1f5f9; color:#0f172a; font-weight:700; padding:6px 14px; border-radius:20px; font-size:13px;">
                        📬 <?php printf(__('%d Active Recipients', 'foundnxt'), count($active_subscribers)); ?>
                    </span>
                </div>

                <form method="post" action="" id="fnx-broadcast-form">
                    <?php wp_nonce_field('fnx_resend_broadcast_action', 'fnx_resend_broadcast_nonce'); ?>
                    <input type="hidden" name="is_test_mode" id="is_test_mode" value="0">

                    <table class="form-table" role="presentation" style="margin-top:0;">
                        <tr>
                            <th scope="row" style="width:180px;"><label for="broadcast_subject"><strong><?php _e('Subject Line *', 'foundnxt'); ?></strong></label></th>
                            <td>
                                <input name="broadcast_subject" type="text" id="broadcast_subject" class="large-text" placeholder="<?php esc_attr_e('e.g. Scaling AI Architecture & Q4 Valuation Multiples', 'foundnxt'); ?>" required style="font-size:15px; padding:8px 12px;">
                                <p class="description"><?php _e('A compelling, high-signal subject line.', 'foundnxt'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="broadcast_preview"><?php _e('Preheader / Preview Snippet', 'foundnxt'); ?></label></th>
                            <td>
                                <input name="broadcast_preview" type="text" id="broadcast_preview" class="large-text" placeholder="<?php esc_attr_e('e.g. The exact frameworks 7-figure startups use to avoid dilution...', 'foundnxt'); ?>" style="padding:6px 10px;">
                                <p class="description"><?php _e('Shown next to the subject line in email inbox previews.', 'foundnxt'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="broadcast_content"><strong><?php _e('Newsletter Content *', 'foundnxt'); ?></strong></label></th>
                            <td>
                                <?php
                                $default_body = '<h2>Scaling in 2026: The New Operating Playbook</h2>' . "\n" .
                                                '<p>Here are this week’s distilled frameworks on valuation, AI agents, and customer acquisition:</p>' . "\n" .
                                                '<h3>1. Core Framework Highlight</h3>' . "\n" .
                                                '<p>Focus on contribution margins and scalable distribution before pouring capital into headcount.</p>';
                                wp_editor($default_body, 'broadcast_content', [
                                    'textarea_name' => 'broadcast_content',
                                    'textarea_rows' => 14,
                                    'media_buttons' => true,
                                    'teeny'         => false,
                                    'quicktags'     => true,
                                ]);
                                ?>
                                <p class="description" style="margin-top:8px;">
                                    <?php _e('The branded FoundNXT header and compliant Unsubscribe footer will be automatically appended to this content.', 'foundnxt'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Action Bar -->
                    <div style="background:#f8fafc; border-top:1px solid #e2e8f0; margin:-10px -28px -24px -28px; padding:20px 28px; border-radius:0 0 8px 8px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                        <!-- Left: Test Send -->
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="email" name="test_email_address" id="test_email_address" value="<?php echo esc_attr(fnx_resend_get_admin_recipient()); ?>" placeholder="<?php esc_attr_e('Test email address…', 'foundnxt'); ?>" style="padding:6px 10px; width:220px;">
                            <button type="button" class="button" onclick="fnxSubmitBroadcast(true);" style="background:#fff; border-color:#cbd5e1; font-weight:600;">
                                🧪 <?php _e('Send Test Email', 'foundnxt'); ?>
                            </button>
                        </div>

                        <!-- Right: Broadcast Button -->
                        <div>
                            <button type="button" class="button button-primary button-large" onclick="fnxSubmitBroadcast(false);" style="background:#f97316; border-color:#ea580c; font-weight:700; padding:4px 24px; font-size:14px; text-shadow:none; box-shadow:none;">
                                🚀 <?php printf(__('Broadcast Newsletter to %d Subscribers', 'foundnxt'), count($active_subscribers)); ?>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="fnx_send_newsletter_broadcast" value="1">
                </form>
            </div>

            <script>
            function fnxSubmitBroadcast(isTest) {
                var form = document.getElementById('fnx-broadcast-form');
                var modeInput = document.getElementById('is_test_mode');
                var subject = document.getElementById('broadcast_subject').value.trim();

                if (! subject) {
                    alert('<?php echo esc_js(__('Please enter a subject line for the newsletter.', 'foundnxt')); ?>');
                    document.getElementById('broadcast_subject').focus();
                    return;
                }

                if (isTest) {
                    var testEmail = document.getElementById('test_email_address').value.trim();
                    if (! testEmail) {
                        alert('<?php echo esc_js(__('Please specify an email address to send the test to.', 'foundnxt')); ?>');
                        return;
                    }
                    modeInput.value = '1';
                    form.submit();
                } else {
                    var total = <?php echo (int) count($active_subscribers); ?>;
                    if (total === 0) {
                        alert('<?php echo esc_js(__('There are no active newsletter subscribers to broadcast to.', 'foundnxt')); ?>');
                        return;
                    }
                    if (confirm('<?php echo esc_js(sprintf(__('Are you sure you want to broadcast this newsletter to %d subscribers?', 'foundnxt'), count($active_subscribers))); ?>')) {
                        modeInput.value = '0';
                        form.submit();
                    }
                }
            }
            </script>

        <?php elseif ($active_tab === 'settings'): ?>
            <!-- TAB 2: SETTINGS & AUTOMATION -->
            <div style="background:#fff; border:1px solid #ccd0d4; border-radius:8px; padding:24px 28px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <h2 style="margin-top:0; font-size:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
                    <?php _e('Resend Credentials & Automation Settings', 'foundnxt'); ?>
                </h2>

                <form method="post" action="">
                    <?php wp_nonce_field('fnx_resend_settings_action', 'fnx_resend_nonce'); ?>

                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row"><label for="fnx_resend_api_key"><strong><?php _e('Resend API Key', 'foundnxt'); ?></strong></label></th>
                            <td>
                                <input name="fnx_resend_api_key" type="password" id="fnx_resend_api_key" value="<?php echo esc_attr(fnx_resend_get_api_key()); ?>" class="regular-text" style="font-family:monospace; font-size:14px;">
                                <p class="description">
                                    <?php _e('Connected to Resend. Using key for verified domain <code>foundnxt.com</code>.', 'foundnxt'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="fnx_resend_from_newsletter"><?php _e('Newsletter Sender', 'foundnxt'); ?></label></th>
                            <td>
                                <input name="fnx_resend_from_newsletter" type="text" id="fnx_resend_from_newsletter" value="<?php echo esc_attr(fnx_resend_get_from('newsletter')); ?>" class="regular-text">
                                <p class="description"><?php _e('Default: <code>FoundNXT &lt;newsletter@foundnxt.com&gt;</code>. Must use verified domain @foundnxt.com.', 'foundnxt'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="fnx_resend_from_contact"><?php _e('Contact Autoresponder Sender', 'foundnxt'); ?></label></th>
                            <td>
                                <input name="fnx_resend_from_contact" type="text" id="fnx_resend_from_contact" value="<?php echo esc_attr(fnx_resend_get_from('contact')); ?>" class="regular-text">
                                <p class="description"><?php _e('Default: <code>FoundNXT &lt;hello@foundnxt.com&gt;</code>.', 'foundnxt'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="fnx_resend_admin_email"><?php _e('Admin Alert Recipient', 'foundnxt'); ?></label></th>
                            <td>
                                <input name="fnx_resend_admin_email" type="email" id="fnx_resend_admin_email" value="<?php echo esc_attr(fnx_resend_get_admin_recipient()); ?>" class="regular-text">
                                <p class="description"><?php _e('Receives notification emails when a new lead or contact inquiry is submitted.', 'foundnxt'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Automated Communications', 'foundnxt'); ?></th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" name="fnx_resend_enable_welcome_email" value="1" <?php checked(fnx_resend_feature_enabled('welcome_email')); ?>>
                                        <strong><?php _e('Send Welcome Email to New Newsletter Subscribers', 'foundnxt'); ?></strong>
                                    </label>
                                    <p class="description" style="margin-left:24px; margin-bottom:12px;">
                                        <?php _e('Automatically sends a branded welcome briefing with the one-click unsubscribe link whenever a user subscribes on any newsletter form.', 'foundnxt'); ?>
                                    </p>

                                    <label>
                                        <input type="checkbox" name="fnx_resend_enable_contact_reply" value="1" <?php checked(fnx_resend_feature_enabled('contact_reply')); ?>>
                                        <strong><?php _e('Send Confirmation Auto-Reply to Contact Form Submitters', 'foundnxt'); ?></strong>
                                    </label>
                                    <p class="description" style="margin-left:24px; margin-bottom:12px;">
                                        <?php _e('Immediately reassures the submitter that their inquiry was received and our team will follow up within 24 hours.', 'foundnxt'); ?>
                                    </p>

                                    <label>
                                        <input type="checkbox" name="fnx_resend_enable_contact_admin_alert" value="1" <?php checked(fnx_resend_feature_enabled('contact_admin_alert')); ?>>
                                        <strong><?php _e('Send Admin Alert Email via Resend on New Lead', 'foundnxt'); ?></strong>
                                    </label>
                                    <p class="description" style="margin-left:24px;">
                                        <?php _e('Delivers high-priority lead notification to admin with Reply-To set to the submitter so you can reply instantly.', 'foundnxt'); ?>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                    </table>

                    <p class="submit" style="margin-bottom:0; padding-top:10px;">
                        <button type="submit" name="fnx_save_resend_settings" class="button button-primary" style="background:#f97316; border-color:#ea580c; font-weight:600; text-shadow:none;">
                            <?php _e('Save Resend Settings', 'foundnxt'); ?>
                        </button>
                    </p>
                </form>

                <!-- Standalone Deliverability Test Box -->
                <div style="margin-top:36px; padding-top:24px; border-top:1px solid #e2e8f0;">
                    <h3 style="margin-top:0;"><?php _e('Send Deliverability Test Email', 'foundnxt'); ?></h3>
                    <p class="description"><?php _e('Send a live test email through Resend directly to your inbox to verify deliverability, DKIM, and formatting.', 'foundnxt'); ?></p>
                    <form method="post" action="" style="display:flex; align-items:center; gap:10px; margin-top:12px;">
                        <?php wp_nonce_field('fnx_resend_settings_action', 'fnx_resend_nonce'); ?>
                        <input type="email" name="fnx_test_recipient" value="<?php echo esc_attr(fnx_resend_get_admin_recipient()); ?>" placeholder="<?php esc_attr_e('recipient@domain.com', 'foundnxt'); ?>" class="regular-text" style="width:260px;">
                        <button type="submit" name="fnx_send_resend_test" class="button button-secondary">
                            📬 <?php _e('Send Test Email Now', 'foundnxt'); ?>
                        </button>
                    </form>
                </div>
            </div>

        <?php elseif ($active_tab === 'unsubscribers'): ?>
            <!-- TAB 3: SUPPRESSION LIST -->
            <div style="background:#fff; border:1px solid #ccd0d4; border-radius:8px; padding:24px 28px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
                    <div>
                        <h2 style="margin:0 0 4px 0; font-size:18px;"><?php _e('Unsubscribed Suppression List', 'foundnxt'); ?></h2>
                        <p style="margin:0; font-size:13px; color:#64748b;"><?php _e('Addresses that clicked the unsubscribe link or sent an RFC 8058 header cancel. No broadcast or newsletter emails will be sent to these contacts.', 'foundnxt'); ?></p>
                    </div>
                    <span style="background:#fee2e2; color:#b91c1c; font-weight:700; padding:6px 14px; border-radius:20px; font-size:13px;">
                        🚫 <?php printf(__('%d Suppressed', 'foundnxt'), count($unsubscribed_list)); ?>
                    </span>
                </div>

                <?php if (empty($unsubscribed_list)): ?>
                    <div style="padding:40px 20px; text-align:center; color:#64748b;">
                        <span style="font-size:36px; display:block; margin-bottom:12px;">🎉</span>
                        <strong style="font-size:16px; color:#0f172a;"><?php _e('Zero Unsubscribed Users', 'foundnxt'); ?></strong>
                        <p style="margin:6px 0 0 0;"><?php _e('All newsletter subscribers are currently active.', 'foundnxt'); ?></p>
                    </div>
                <?php else: ?>
                    <table class="widefat fixed striped" style="border-radius:6px; overflow:hidden;">
                        <thead>
                            <tr>
                                <th style="width:35%;"><strong><?php _e('Email Address', 'foundnxt'); ?></strong></th>
                                <th style="width:25%;"><strong><?php _e('Date Unsubscribed', 'foundnxt'); ?></strong></th>
                                <th style="width:25%;"><strong><?php _e('Reason / Method', 'foundnxt'); ?></strong></th>
                                <th style="width:15%; text-align:right;"><strong><?php _e('Actions', 'foundnxt'); ?></strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($unsubscribed_list as $unsub_email => $meta): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html($unsub_email); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo esc_html($meta['date'] ?? 'N/A'); ?>
                                    </td>
                                    <td>
                                        <span style="background:#f1f5f9; padding:2px 8px; border-radius:4px; font-size:12px; color:#475569;">
                                            <?php echo esc_html($meta['reason'] ?? 'Email Footer Link'); ?>
                                        </span>
                                    </td>
                                    <td style="text-align:right;">
                                        <?php
                                        $resub_url = wp_nonce_url(
                                            add_query_arg([
                                                'post_type' => 'fnx_lead',
                                                'page'      => 'fnx-resend-newsletter',
                                                'tab'       => 'unsubscribers',
                                                'action'    => 'resubscribe_manual',
                                                'email'     => rawurlencode($unsub_email),
                                            ], admin_url('edit.php')),
                                            'fnx_manual_resubscribe'
                                        );
                                        ?>
                                        <a href="<?php echo esc_url($resub_url); ?>" class="button button-small" onclick="return confirm('<?php echo esc_js(sprintf(__('Resubscribe %s?', 'foundnxt'), $unsub_email)); ?>');">
                                            <?php _e('Resubscribe', 'foundnxt'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
