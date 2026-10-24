<?php
/**
 * FoundNXT CRM — Modern Lead Management Table & Dashboard for WordPress Admin
 *
 * Separates Contact Inquiries and Newsletter Subscribers with dedicated views,
 * submenus, tailored table columns, KPI metrics, Quick View drawer, and CSV exports.
 *
 * @package FoundNXT
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * ══════════════════════════════════════════════════════════════
 * 1. DEDICATED ADMIN SUBMENUS FOR SEPARATE VIEWS
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_register_submenus() {
    // 1. Contact Inquiries Submenu
    add_submenu_page(
        'edit.php?post_type=fnx_lead',
        __('Contact Inquiries', 'foundnxt'),
        __('💬 Contact Inquiries', 'foundnxt'),
        'manage_options',
        'edit.php?post_type=fnx_lead&fnx_channel=contact'
    );

    // 2. Newsletter Subscribers Submenu
    add_submenu_page(
        'edit.php?post_type=fnx_lead',
        __('Newsletter Subscribers', 'foundnxt'),
        __('✉️ Newsletter Subscribers', 'foundnxt'),
        'manage_options',
        'edit.php?post_type=fnx_lead&fnx_channel=newsletter'
    );
}
add_action('admin_menu', 'fnx_crm_register_submenus', 9);

/**
 * Highlight the active submenu item in the admin sidebar
 */
add_filter('submenu_file', function($submenu_file) {
    if (isset($_GET['post_type']) && $_GET['post_type'] === 'fnx_lead') {
        $channel = sanitize_key($_GET['fnx_channel'] ?? '');
        if ($channel === 'contact') {
            return 'edit.php?post_type=fnx_lead&fnx_channel=contact';
        } elseif ($channel === 'newsletter') {
            return 'edit.php?post_type=fnx_lead&fnx_channel=newsletter';
        }
    }
    return $submenu_file;
});


/**
 * ══════════════════════════════════════════════════════════════
 * 2. LEAD DATA HELPER
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_get_lead($post_id) {
    $post = get_post($post_id);
    if (! $post) {
        return null;
    }

    $name         = get_post_meta($post_id, 'lead_name', true);
    $email        = get_post_meta($post_id, 'lead_email', true);
    $phone        = get_post_meta($post_id, 'lead_phone', true);
    $company      = get_post_meta($post_id, 'lead_company', true);
    $stage        = get_post_meta($post_id, 'lead_stage', true);
    $help         = get_post_meta($post_id, 'lead_help', true);
    $lead_type    = get_post_meta($post_id, 'lead_type', true);
    $message      = get_post_meta($post_id, 'lead_message', true);
    $source       = get_post_meta($post_id, 'lead_source', true);
    $target_sheet = get_post_meta($post_id, 'lead_target_sheet', true);

    // Fallback extraction from post_content for legacy leads
    if (empty($message) && ! empty($post->post_content)) {
        if (preg_match('/Message:\s*\n(.*)$/s', $post->post_content, $m)) {
            $message = trim($m[1]);
        }
    }
    if (empty($name)) {
        $parts = explode('—', $post->post_title);
        $name  = trim($parts[0]);
    }
    if (empty($help) && ! empty($post->post_title) && strpos($post->post_title, '—') !== false) {
        $parts = explode('—', $post->post_title);
        if (isset($parts[1])) {
            $subparts = explode('(', $parts[1]);
            $help     = trim($subparts[0]);
        }
    }
    if (empty($email) && ! empty($post->post_content)) {
        if (preg_match('/Email:\s*([^\n\r]+)/i', $post->post_content, $m)) {
            $email = trim($m[1]);
        }
    }
    if (empty($phone) && ! empty($post->post_content)) {
        if (preg_match('/Mobile\/Phone:\s*([^\n\r]+)/i', $post->post_content, $m)) {
            $phone = trim($m[1]);
        }
    }
    if (empty($company) && ! empty($post->post_content)) {
        if (preg_match('/Company\/Website:\s*([^\n\r]+)/i', $post->post_content, $m)) {
            $company = trim($m[1]);
        }
    }
    if (empty($stage) && ! empty($post->post_content)) {
        if (preg_match('/Company Stage:\s*([^\n\r]+)/i', $post->post_content, $m)) {
            $stage = trim($m[1]);
        }
    }
    if (empty($lead_type) && ! empty($post->post_content)) {
        if (preg_match('/Lead Type:\s*([^\n\r]+)/i', $post->post_content, $m)) {
            $lead_type = trim($m[1]);
        }
    }

    $is_contact = (stripos($lead_type, 'Contact') !== false) || ! empty($message) || ! empty($phone);
    if (empty($target_sheet)) {
        $target_sheet = $is_contact ? 'contact' : 'newsletter';
    }

    return [
        'id'           => $post_id,
        'name'         => $name ?: __('Anonymous', 'foundnxt'),
        'email'        => $email,
        'phone'        => $phone,
        'company'      => $company,
        'stage'        => $stage ?: __('Not Specified', 'foundnxt'),
        'help'         => $help ?: __('General Enquiry', 'foundnxt'),
        'lead_type'    => $lead_type ?: ($is_contact ? 'Contact Page Enquiry' : 'Newsletter Signup'),
        'is_contact'   => $is_contact,
        'message'      => $message,
        'source'       => $source ?: home_url('/'),
        'target_sheet' => $target_sheet,
        'date_full'    => get_the_date('M j, Y \a\t g:i a', $post_id),
        'date_rel'     => human_time_diff(get_post_time('U', false, $post_id), current_time('timestamp')) . ' ' . __('ago', 'foundnxt'),
    ];
}

/**
 * Generate 2-letter Initials
 */
function fnx_crm_initials($name) {
    $words = preg_split('/\s+/', trim($name));
    $initials = '';
    if (count($words) >= 2) {
        $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
    } elseif (! empty($words[0])) {
        $initials = mb_substr($words[0], 0, 2);
    }
    return strtoupper($initials ?: 'NX');
}

/**
 * Generate aesthetic gradient for user initials avatar
 */
function fnx_crm_avatar_gradient($name) {
    $gradients = [
        'linear-gradient(135deg, #FF5A2C, #FF7A45)',
        'linear-gradient(135deg, #0ea5e9, #00d4aa)',
        'linear-gradient(135deg, #10b981, #059669)',
        'linear-gradient(135deg, #f59e0b, #ff7a45)',
        'linear-gradient(135deg, #ec4899, #f43f5e)',
        'linear-gradient(135deg, #FF5A2C, #FFA834)',
    ];
    $idx = abs(crc32($name)) % count($gradients);
    return $gradients[$idx];
}


/**
 * ══════════════════════════════════════════════════════════════
 * 3. SEPARATED TABLE COLUMNS
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_table_columns($columns) {
    $channel = sanitize_key($_GET['fnx_channel'] ?? '');

    // 1. Newsletter Subscribers View
    if ($channel === 'newsletter') {
        return [
            'cb'                 => '<input type="checkbox" />',
            'fnx_crm_contact'    => __('Subscriber (Name & Email)', 'foundnxt'),
            'fnx_crm_channel'    => __('Signup Form', 'foundnxt'),
            'fnx_crm_source_url' => __('Source Page URL', 'foundnxt'),
            'fnx_crm_sync'       => __('Google Sheets Sync', 'foundnxt'),
            'fnx_crm_date'       => __('Subscribed Date', 'foundnxt'),
            'fnx_crm_actions'    => __('Actions', 'foundnxt'),
        ];
    }

    // 2. Contact Inquiries View
    if ($channel === 'contact') {
        return [
            'cb'                 => '<input type="checkbox" />',
            'fnx_crm_contact'    => __('Contact / Lead', 'foundnxt'),
            'fnx_crm_company'    => __('Company & Stage', 'foundnxt'),
            'fnx_crm_topic'      => __('Requirement / Topic', 'foundnxt'),
            'fnx_crm_message'    => __('Message Preview', 'foundnxt'),
            'fnx_crm_sync'       => __('Google Sheets Sync', 'foundnxt'),
            'fnx_crm_date'       => __('Received Date', 'foundnxt'),
            'fnx_crm_actions'    => __('Actions', 'foundnxt'),
        ];
    }

    // 3. All Inbound Leads (Combined)
    return [
        'cb'                 => '<input type="checkbox" />',
        'fnx_crm_contact'    => __('Contact / Lead', 'foundnxt'),
        'fnx_crm_channel'    => __('Channel / Type', 'foundnxt'),
        'fnx_crm_company'    => __('Company & Stage', 'foundnxt'),
        'fnx_crm_topic'      => __('Requirement / Topic', 'foundnxt'),
        'fnx_crm_message'    => __('Message Preview', 'foundnxt'),
        'fnx_crm_sync'       => __('Sync Destination', 'foundnxt'),
        'fnx_crm_date'       => __('Received', 'foundnxt'),
        'fnx_crm_actions'    => __('Actions', 'foundnxt'),
    ];
}
add_filter('manage_edit-fnx_lead_columns', 'fnx_crm_table_columns');


/**
 * Render Column Data
 */
function fnx_crm_render_column($column, $post_id) {
    $lead = fnx_crm_get_lead($post_id);
    if (! $lead) {
        return;
    }

    switch ($column) {
        case 'fnx_crm_contact':
            $grad     = fnx_crm_avatar_gradient($lead['name']);
            $initials = fnx_crm_initials($lead['name']);
            $json     = esc_attr(wp_json_encode($lead));
            ?>
            <div class="fnx-crm-contact-cell" data-lead="<?php echo $json; ?>">
                <div class="fnx-crm-avatar" style="background: <?php echo esc_attr($grad); ?>;">
                    <?php echo esc_html($initials); ?>
                </div>
                <div class="fnx-crm-contact-meta">
                    <a href="#" onclick="fnxOpenQuickView(this); return false;" data-lead="<?php echo $json; ?>" class="fnx-crm-name-link" title="<?php esc_attr_e('Quick View Lead in Drawer', 'foundnxt'); ?>">
                        <strong><?php echo esc_html($lead['name']); ?></strong>
                    </a>
                    <div class="row-actions">
                        <span class="view"><a href="#" onclick="fnxOpenQuickView(this); return false;" data-lead="<?php echo $json; ?>"><?php _e('Quick View', 'foundnxt'); ?></a> | </span>
                        <span class="edit"><a href="<?php echo esc_url(get_edit_post_link($post_id)); ?>"><?php _e('Edit', 'foundnxt'); ?></a> | </span>
                        <span class="trash"><a href="<?php echo esc_url(get_delete_post_link($post_id)); ?>" class="submitdelete"><?php _e('Trash', 'foundnxt'); ?></a></span>
                    </div>
                    <?php if (! empty($lead['email'])): ?>
                    <div class="fnx-crm-email-row">
                        <a href="mailto:<?php echo esc_attr($lead['email']); ?>" class="fnx-crm-email-link" title="<?php esc_attr_e('Email Contact', 'foundnxt'); ?>">
                            <span class="dashicons dashicons-email-alt"></span>
                            <span><?php echo esc_html($lead['email']); ?></span>
                        </a>
                        <button type="button" class="fnx-copy-btn" onclick="fnxCopyText('<?php echo esc_js($lead['email']); ?>', this);" title="<?php esc_attr_e('Copy Email', 'foundnxt'); ?>">
                            <span class="dashicons dashicons-admin-page"></span>
                        </button>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty($lead['phone'])): ?>
                    <div class="fnx-crm-phone-row">
                        <a href="tel:<?php echo esc_attr($lead['phone']); ?>" class="fnx-crm-phone-link" title="<?php esc_attr_e('Call Contact', 'foundnxt'); ?>">
                            <span class="dashicons dashicons-phone"></span>
                            <span><?php echo esc_html($lead['phone']); ?></span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            break;

        case 'fnx_crm_channel':
            $type = $lead['lead_type'];
            $badge_class = 'fnx-pill-gray';
            $icon = 'dashicons-tag';

            if (stripos($type, 'Contact') !== false) {
                $badge_class = 'fnx-pill-emerald';
                $icon = 'dashicons-testimonial';
            } elseif (stripos($type, 'Newsletter') !== false) {
                $badge_class = 'fnx-pill-purple';
                $icon = 'dashicons-email';
            } elseif (stripos($type, 'Waitlist') !== false || stripos($type, 'Tool') !== false) {
                $badge_class = 'fnx-pill-amber';
                $icon = 'dashicons-hammer';
            } elseif (stripos($type, 'Magnet') !== false) {
                $badge_class = 'fnx-pill-cyan';
                $icon = 'dashicons-book-alt';
            }
            ?>
            <span class="fnx-crm-pill <?php echo esc_attr($badge_class); ?>">
                <span class="dashicons <?php echo esc_attr($icon); ?>"></span>
                <?php echo esc_html($type); ?>
            </span>
            <?php
            if (get_post_meta($post_id, 'lead_unsubscribed', true) === '1' || (function_exists('fnx_resend_is_unsubscribed') && ! empty($lead['email']) && fnx_resend_is_unsubscribed($lead['email']))) {
                echo ' <span class="fnx-crm-pill fnx-pill-rose" style="margin-top:4px; display:inline-flex;" title="' . esc_attr__('Unsubscribed from newsletter', 'foundnxt') . '"><span class="dashicons dashicons-dismiss"></span> ' . esc_html__('Unsubscribed', 'foundnxt') . '</span>';
            }
            ?>
            <?php
            break;

        case 'fnx_crm_source_url':
            $source = $lead['source'];
            if (! empty($source) && filter_var($source, FILTER_VALIDATE_URL)) {
                $path = parse_url($source, PHP_URL_PATH);
                $frag = parse_url($source, PHP_URL_FRAGMENT);
                $label = ($path ?: '/') . ($frag ? '#' . $frag : '');
                ?>
                <a href="<?php echo esc_url($source); ?>" target="_blank" rel="noopener" class="fnx-crm-company-name" title="<?php echo esc_attr($source); ?>">
                    <code><?php echo esc_html($label); ?></code>
                    <span class="dashicons dashicons-external" style="font-size: 13px; width: 13px; height: 13px;"></span>
                </a>
                <?php
            } else {
                echo '<span class="fnx-text-muted">—</span>';
            }
            break;

        case 'fnx_crm_company':
            $company = $lead['company'];
            $stage   = $lead['stage'];
            ?>
            <div class="fnx-crm-company-cell">
                <?php if (! empty($company) && $company !== 'Not Specified'): ?>
                    <?php if (filter_var($company, FILTER_VALIDATE_URL) || strpos($company, 'http') === 0): ?>
                        <a href="<?php echo esc_url($company); ?>" target="_blank" rel="noopener" class="fnx-crm-company-name">
                            <?php echo esc_html(parse_url($company, PHP_URL_HOST) ?: $company); ?>
                            <span class="dashicons dashicons-external" style="font-size: 13px; width: 13px; height: 13px;"></span>
                        </a>
                    <?php else: ?>
                        <div class="fnx-crm-company-name">
                            <span class="dashicons dashicons-building" style="font-size: 14px; width: 14px; height: 14px; opacity: 0.6;"></span>
                            <strong><?php echo esc_html($company); ?></strong>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="fnx-text-muted">—</span>
                <?php endif; ?>

                <?php if (! empty($stage) && $stage !== 'Not Specified'): ?>
                    <span class="fnx-crm-subtag"><?php echo esc_html($stage); ?></span>
                <?php endif; ?>
            </div>
            <?php
            break;

        case 'fnx_crm_topic':
            $help = $lead['help'];
            $topic_class = 'fnx-pill-blue';
            if (stripos($help, 'Valuation') !== false) {
                $topic_class = 'fnx-pill-teal';
            } elseif (stripos($help, 'Tech') !== false || stripos($help, 'AI') !== false) {
                $topic_class = 'fnx-pill-purple';
            } elseif (stripos($help, 'Market') !== false) {
                $topic_class = 'fnx-pill-amber';
            } elseif (stripos($help, 'Marketing') !== false) {
                $topic_class = 'fnx-pill-pink';
            } elseif (stripos($help, 'General') !== false) {
                $topic_class = 'fnx-pill-gray';
            }
            ?>
            <span class="fnx-crm-pill <?php echo esc_attr($topic_class); ?>">
                <?php echo esc_html($help); ?>
            </span>
            <?php
            break;

        case 'fnx_crm_message':
            $msg = $lead['message'];
            if (! empty($msg)):
                $snippet = wp_trim_words($msg, 10, '…');
                $json = esc_attr(wp_json_encode($lead));
                ?>
                <div class="fnx-crm-message-cell">
                    <span class="fnx-crm-snippet" title="<?php echo esc_attr($msg); ?>">“<?php echo esc_html($snippet); ?>”</span>
                    <button type="button" class="fnx-crm-quickview-btn" onclick="fnxOpenQuickView(this);" data-lead="<?php echo $json; ?>" title="<?php esc_attr_e('View full message in drawer', 'foundnxt'); ?>">
                        <span class="dashicons dashicons-visibility"></span> <?php _e('View', 'foundnxt'); ?>
                    </button>
                </div>
            <?php else: ?>
                <span class="fnx-text-muted" style="font-size: 0.8125rem;">
                    <?php _e('No message (Email signup)', 'foundnxt'); ?>
                </span>
            <?php endif;
            break;

        case 'fnx_crm_sync':
            $sheet = $lead['target_sheet'];
            $pill_bg = ($sheet === 'contact') ? '#ecfdf5' : '#fff2ee';
            $pill_color = ($sheet === 'contact') ? '#059669' : '#FF5A2C';
            $pill_border = ($sheet === 'contact') ? '#a7f3d0' : '#ffd5c8';
            ?>
            <span class="fnx-crm-pill" style="background: <?php echo $pill_bg; ?>; color: <?php echo $pill_color; ?>; border-color: <?php echo $pill_border; ?>;">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: currentColor; margin-right: 4px;"></span>
                <?php echo esc_html(sprintf(__('Sheets: %s', 'foundnxt'), $sheet)); ?>
            </span>
            <?php
            break;

        case 'fnx_crm_date':
            ?>
            <div class="fnx-crm-date-cell" title="<?php echo esc_attr($lead['date_full']); ?>">
                <strong><?php echo esc_html($lead['date_rel']); ?></strong>
                <span class="fnx-crm-date-sub"><?php echo esc_html(get_the_date('M j, Y', $post_id)); ?></span>
            </div>
            <?php
            break;

        case 'fnx_crm_actions':
            $json = esc_attr(wp_json_encode($lead));
            ?>
            <div class="fnx-crm-action-buttons">
                <button type="button" class="button button-small fnx-action-btn" onclick="fnxOpenQuickView(this);" data-lead="<?php echo $json; ?>" title="<?php esc_attr_e('Quick View Drawer', 'foundnxt'); ?>">
                    <span class="dashicons dashicons-visibility"></span>
                </button>
                <?php if (! empty($lead['email'])): ?>
                <a href="mailto:<?php echo esc_attr($lead['email']); ?>?subject=<?php echo esc_attr(rawurlencode('FoundNXT: Regarding your ' . $lead['help'] . ' enquiry')); ?>" class="button button-small fnx-action-btn" title="<?php esc_attr_e('Send Direct Email', 'foundnxt'); ?>">
                    <span class="dashicons dashicons-email-alt"></span>
                </a>
                <?php endif; ?>
                <a href="<?php echo esc_url(get_edit_post_link($post_id)); ?>" class="button button-small fnx-action-btn" title="<?php esc_attr_e('Edit Details', 'foundnxt'); ?>">
                    <span class="dashicons dashicons-edit"></span>
                </a>
            </div>
            <?php
            break;
    }
}
add_action('manage_fnx_lead_posts_custom_column', 'fnx_crm_render_column', 10, 2);


/**
 * ══════════════════════════════════════════════════════════════
 * 4. SORTABLE COLUMNS
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_sortable_columns($columns) {
    $columns['fnx_crm_contact'] = 'title';
    $columns['fnx_crm_channel'] = 'lead_type';
    $columns['fnx_crm_date']    = 'date';
    return $columns;
}
add_filter('manage_edit-fnx_lead_sortable_columns', 'fnx_crm_sortable_columns');


/**
 * ══════════════════════════════════════════════════════════════
 * 5. SMART FILTERS & QUERY ROUTING
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_table_filters($post_type) {
    if ($post_type !== 'fnx_lead') {
        return;
    }

    $channel = sanitize_key($_GET['fnx_channel'] ?? '');

    // Show Topic and Stage filters only on Contact or All views
    if ($channel !== 'newsletter') {
        $selected_topic = isset($_GET['fnx_filter_topic']) ? sanitize_text_field($_GET['fnx_filter_topic']) : '';
        $selected_stage = isset($_GET['fnx_filter_stage']) ? sanitize_text_field($_GET['fnx_filter_stage']) : '';

        // Topic Filter
        echo '<select name="fnx_filter_topic" id="fnx_filter_topic" style="margin-left: 6px;">';
        echo '<option value="">' . __('All Help Topics', 'foundnxt') . '</option>';
        $topics = [
            'Valuation Guidance'     => __('Valuation Guidance', 'foundnxt'),
            'Tech & AI Strategy'     => __('Tech & AI Strategy', 'foundnxt'),
            'Market Research Request'=> __('Market Research', 'foundnxt'),
            'Marketing Help'         => __('Marketing & Growth', 'foundnxt'),
            'Partnerships'           => __('Partnerships', 'foundnxt'),
            'General Enquiry'        => __('General Enquiry', 'foundnxt'),
        ];
        foreach ($topics as $key => $label) {
            printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($selected_topic, $key, false), esc_html($label));
        }
        echo '</select>';

        // Stage Filter
        echo '<select name="fnx_filter_stage" id="fnx_filter_stage" style="margin-left: 6px;">';
        echo '<option value="">' . __('All Company Stages', 'foundnxt') . '</option>';
        $stages = [
            'Early Stage / Seed'      => __('Early Stage / Seed', 'foundnxt'),
            'Growth / Series A+'      => __('Growth / Series A+', 'foundnxt'),
            'Scaleup'                 => __('Scaleup', 'foundnxt'),
            'Enterprise / Established'=> __('Enterprise / Established', 'foundnxt'),
        ];
        foreach ($stages as $key => $label) {
            printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($selected_stage, $key, false), esc_html($label));
        }
        echo '</select>';
    }

    // Channel Form Filter (Newsletter forms or all)
    if ($channel === 'newsletter' || empty($channel)) {
        $selected_form = isset($_GET['fnx_filter_channel']) ? sanitize_text_field($_GET['fnx_filter_channel']) : '';
        echo '<select name="fnx_filter_channel" id="fnx_filter_channel" style="margin-left: 6px;">';
        echo '<option value="">' . __('All Signup Forms', 'foundnxt') . '</option>';
        $forms = [
            'Footer Newsletter Signup'            => __('Footer Form', 'foundnxt'),
            'Hero Newsletter Access'              => __('Hero Access Form', 'foundnxt'),
            'Fullwidth Newsletter Band'           => __('Fullwidth Newsletter Band', 'foundnxt'),
            'Single Article End Newsletter CTA'   => __('Article End CTA', 'foundnxt'),
            'Tools Directory Early Access Waitlist'=> __('Tools Waitlist', 'foundnxt'),
            'Lead Magnet'                         => __('Lead Magnets', 'foundnxt'),
        ];
        foreach ($forms as $key => $label) {
            printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($selected_form, $key, false), esc_html($label));
        }
        echo '</select>';
    }

    // Preserve the active channel in pagination & filter submissions
    if (! empty($channel)) {
        printf('<input type="hidden" name="fnx_channel" value="%s">', esc_attr($channel));
    }
}
add_action('restrict_manage_posts', 'fnx_crm_table_filters');

/**
 * Filter Query by Selected Channel (Contact vs Newsletter)
 */
function fnx_crm_filter_query($query) {
    global $pagenow;
    if ($pagenow !== 'edit.php' || ! isset($_GET['post_type']) || $_GET['post_type'] !== 'fnx_lead' || ! $query->is_main_query()) {
        return;
    }

    $meta_query = [];
    $channel_tab = sanitize_key($_GET['fnx_channel'] ?? '');

    // 1. Separate by Channel Tab
    if ($channel_tab === 'contact') {
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key'     => 'lead_target_sheet',
                'value'   => 'contact',
                'compare' => '=',
            ],
            [
                'key'     => 'lead_type',
                'value'   => 'Contact',
                'compare' => 'LIKE',
            ],
        ];
    } elseif ($channel_tab === 'newsletter') {
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key'     => 'lead_target_sheet',
                'value'   => 'newsletter',
                'compare' => '=',
            ],
            [
                'key'     => 'lead_type',
                'value'   => 'Contact',
                'compare' => 'NOT LIKE',
            ],
        ];
    }

    // 2. Extra Dropdown Filters
    if (! empty($_GET['fnx_filter_channel'])) {
        $meta_query[] = [
            'key'     => 'lead_type',
            'value'   => sanitize_text_field($_GET['fnx_filter_channel']),
            'compare' => 'LIKE',
        ];
    }

    if (! empty($_GET['fnx_filter_topic'])) {
        $meta_query[] = [
            'key'     => 'lead_help',
            'value'   => sanitize_text_field($_GET['fnx_filter_topic']),
            'compare' => 'LIKE',
        ];
    }

    if (! empty($_GET['fnx_filter_stage'])) {
        $meta_query[] = [
            'key'     => 'lead_stage',
            'value'   => sanitize_text_field($_GET['fnx_filter_stage']),
            'compare' => 'LIKE',
        ];
    }

    if (! empty($meta_query)) {
        $meta_query['relation'] = 'AND';
        $query->set('meta_query', $meta_query);
    }

    if ($query->get('orderby') === 'lead_type') {
        $query->set('meta_key', 'lead_type');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'fnx_crm_filter_query');


/**
 * ══════════════════════════════════════════════════════════════
 * 6. EXECUTIVE KPI DASHBOARD BAR & MODERN SEGMENTED TABS
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_render_kpi_dashboard() {
    $screen = get_current_screen();
    if (! $screen || $screen->id !== 'edit-fnx_lead') {
        return;
    }

    // Total Inbound Leads
    $counts = wp_count_posts('fnx_lead');
    $total_leads = ($counts->publish ?? 0) + ($counts->draft ?? 0);

    // Count Contact Inquiries
    $args_contact = [
        'post_type'   => 'fnx_lead',
        'post_status' => 'any',
        'fields'      => 'ids',
        'meta_query'  => [
            'relation' => 'OR',
            [
                'key'     => 'lead_target_sheet',
                'value'   => 'contact',
                'compare' => '=',
            ],
            [
                'key'     => 'lead_type',
                'value'   => 'Contact',
                'compare' => 'LIKE',
            ],
        ]
    ];
    $contact_count = count(get_posts($args_contact));

    // Count Newsletter Subscribers
    $args_newsletter = [
        'post_type'   => 'fnx_lead',
        'post_status' => 'any',
        'fields'      => 'ids',
        'meta_query'  => [
            'relation' => 'OR',
            [
                'key'     => 'lead_target_sheet',
                'value'   => 'newsletter',
                'compare' => '=',
            ],
            [
                'key'     => 'lead_type',
                'value'   => 'Contact',
                'compare' => 'NOT LIKE',
            ],
        ]
    ];
    $newsletter_count = count(get_posts($args_newsletter));

    // Leads in the last 7 days
    $args_recent = [
        'post_type'   => 'fnx_lead',
        'post_status' => 'any',
        'fields'      => 'ids',
        'date_query'  => [
            [
                'after' => '7 days ago',
            ]
        ]
    ];
    $recent_count = count(get_posts($args_recent));

    $current_channel = sanitize_key($_GET['fnx_channel'] ?? '');
    $contact_url     = admin_url('edit.php?post_type=fnx_lead&fnx_channel=contact');
    $newsletter_url  = admin_url('edit.php?post_type=fnx_lead&fnx_channel=newsletter');
    $all_url         = admin_url('edit.php?post_type=fnx_lead');

    $export_nonce    = wp_create_nonce('fnx_crm_export_nonce');
    $export_url      = admin_url('admin-post.php?action=fnx_export_leads_csv&_wpnonce=' . $export_nonce . ($current_channel ? '&fnx_channel=' . $current_channel : ''));
    $sheets_url      = admin_url('edit.php?post_type=fnx_lead&page=fnx-gsheet-sync');

    // Dynamic Export Button Label
    if ($current_channel === 'contact') {
        $export_label = __('Export Contact Inquiries (CSV)', 'foundnxt');
    } elseif ($current_channel === 'newsletter') {
        $export_label = __('Export Subscribers (CSV)', 'foundnxt');
    } else {
        $export_label = __('Export All Leads (CSV)', 'foundnxt');
    }
    ?>
    <div class="fnx-crm-kpi-wrap">
        <!-- 1. Top Modern Segmented View Switcher -->
        <div class="fnx-crm-nav-tabs">
            <a href="<?php echo esc_url($contact_url); ?>" class="fnx-crm-tab <?php echo ($current_channel === 'contact') ? 'is-active is-contact' : ''; ?>">
                <span class="dashicons dashicons-testimonial"></span>
                <span class="fnx-tab-title"><?php _e('Contact Inquiries', 'foundnxt'); ?></span>
                <span class="fnx-tab-badge fnx-badge-emerald"><?php echo esc_html($contact_count); ?></span>
            </a>
            <a href="<?php echo esc_url($newsletter_url); ?>" class="fnx-crm-tab <?php echo ($current_channel === 'newsletter') ? 'is-active is-newsletter' : ''; ?>">
                <span class="dashicons dashicons-email-alt"></span>
                <span class="fnx-tab-title"><?php _e('Newsletter Subscribers', 'foundnxt'); ?></span>
                <span class="fnx-tab-badge fnx-badge-purple"><?php echo esc_html($newsletter_count); ?></span>
            </a>
            <a href="<?php echo esc_url($all_url); ?>" class="fnx-crm-tab <?php echo empty($current_channel) ? 'is-active is-all' : ''; ?>">
                <span class="dashicons dashicons-groups"></span>
                <span class="fnx-tab-title"><?php _e('All Leads (Combined)', 'foundnxt'); ?></span>
                <span class="fnx-tab-badge fnx-badge-gray"><?php echo esc_html($total_leads); ?></span>
            </a>
        </div>

        <!-- 2. KPI Cards Grid -->
        <div class="fnx-crm-kpi-grid">
            <!-- Card 1: Total Leads -->
            <div class="fnx-crm-kpi-card fnx-kpi-total">
                <div class="fnx-crm-kpi-icon"><span class="dashicons dashicons-groups"></span></div>
                <div class="fnx-crm-kpi-info">
                    <span class="fnx-crm-kpi-label"><?php _e('Total Leads Captured', 'foundnxt'); ?></span>
                    <strong class="fnx-crm-kpi-num"><?php echo esc_html($total_leads); ?></strong>
                    <span class="fnx-crm-kpi-hint"><?php _e('All inbound submissions', 'foundnxt'); ?></span>
                </div>
            </div>

            <!-- Card 2: Contact Form Inquiries -->
            <div class="fnx-crm-kpi-card fnx-kpi-contact <?php echo ($current_channel === 'contact') ? 'is-highlighted' : ''; ?>">
                <div class="fnx-crm-kpi-icon"><span class="dashicons dashicons-testimonial"></span></div>
                <div class="fnx-crm-kpi-info">
                    <span class="fnx-crm-kpi-label"><?php _e('Contact Inquiries', 'foundnxt'); ?></span>
                    <strong class="fnx-crm-kpi-num"><?php echo esc_html($contact_count); ?></strong>
                    <span class="fnx-crm-kpi-hint"><?php _e('High-intent form leads', 'foundnxt'); ?></span>
                </div>
            </div>

            <!-- Card 3: Newsletter Subscribers -->
            <div class="fnx-crm-kpi-card fnx-kpi-newsletter <?php echo ($current_channel === 'newsletter') ? 'is-highlighted' : ''; ?>">
                <div class="fnx-crm-kpi-icon"><span class="dashicons dashicons-email-alt"></span></div>
                <div class="fnx-crm-kpi-info">
                    <span class="fnx-crm-kpi-label"><?php _e('Newsletter Subscribers', 'foundnxt'); ?></span>
                    <strong class="fnx-crm-kpi-num"><?php echo esc_html($newsletter_count); ?></strong>
                    <span class="fnx-crm-kpi-hint"><?php _e('Homepage, footer & articles', 'foundnxt'); ?></span>
                </div>
            </div>

            <!-- Card 4: Recent 7-Day Velocity -->
            <div class="fnx-crm-kpi-card fnx-kpi-velocity">
                <div class="fnx-crm-kpi-icon"><span class="dashicons dashicons-chart-line"></span></div>
                <div class="fnx-crm-kpi-info">
                    <span class="fnx-crm-kpi-label"><?php _e('New This Week', 'foundnxt'); ?></span>
                    <strong class="fnx-crm-kpi-num"><?php echo esc_html($recent_count); ?></strong>
                    <span class="fnx-crm-kpi-hint"><?php _e('Past 7 days velocity', 'foundnxt'); ?></span>
                </div>
            </div>
        </div>

        <!-- 3. CRM Toolbar Actions -->
        <div class="fnx-crm-header-toolbar">
            <div class="fnx-crm-toolbar-left">
                <span class="fnx-crm-brand-pill">
                    <span class="dashicons dashicons-database" style="font-size: 14px; width: 14px; height: 14px;"></span>
                    <?php if ($current_channel === 'contact'): ?>
                        <?php _e('FoundNXT CRM — Contact Inquiries View', 'foundnxt'); ?>
                    <?php elseif ($current_channel === 'newsletter'): ?>
                        <?php _e('FoundNXT CRM — Newsletter Subscribers View', 'foundnxt'); ?>
                    <?php else: ?>
                        <?php _e('FoundNXT CRM — All Inbound Leads', 'foundnxt'); ?>
                    <?php endif; ?>
                </span>
                <span class="fnx-crm-sync-indicator" title="<?php esc_attr_e('Synced with Google Sheets 2-Page Webhook', 'foundnxt'); ?>">
                    <span class="fnx-pulse-dot"></span>
                    <?php _e('Google Sheets 2-Page Sync Active', 'foundnxt'); ?>
                </span>
            </div>
            <div class="fnx-crm-toolbar-right">
                <a href="<?php echo esc_url($export_url); ?>" class="button button-secondary fnx-crm-export-btn">
                    <span class="dashicons dashicons-download" style="vertical-align: middle; margin-top: -2px;"></span>
                    <?php echo esc_html($export_label); ?>
                </a>
                <a href="<?php echo esc_url($sheets_url); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-spreadsheet" style="vertical-align: middle; margin-top: -2px; color: #10b981;"></span>
                    <?php _e('Sheets Settings', 'foundnxt'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}
add_action('all_admin_notices', 'fnx_crm_render_kpi_dashboard');


/**
 * ══════════════════════════════════════════════════════════════
 * 7. SEPARATED CSV EXPORT ENGINE
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_export_leads_csv() {
    if (! current_user_can('manage_options')) {
        wp_die(__('You do not have permission to export leads.', 'foundnxt'));
    }

    check_admin_referer('fnx_crm_export_nonce');

    $channel = sanitize_key($_GET['fnx_channel'] ?? '');

    $args = [
        'post_type'      => 'fnx_lead',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ($channel === 'contact') {
        $args['meta_query'] = [
            'relation' => 'OR',
            [
                'key'     => 'lead_target_sheet',
                'value'   => 'contact',
                'compare' => '=',
            ],
            [
                'key'     => 'lead_type',
                'value'   => 'Contact',
                'compare' => 'LIKE',
            ],
        ];
        $filename = 'foundnxt-contact-inquiries-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Lead ID',
            'Date Received',
            'Name',
            'Email',
            'Phone / Mobile',
            'Company / Website',
            'Company Stage',
            'Help Topic / Need',
            'Channel / Form',
            'Message',
            'Source Page',
            'Target Sheet'
        ];
    } elseif ($channel === 'newsletter') {
        $args['meta_query'] = [
            'relation' => 'OR',
            [
                'key'     => 'lead_target_sheet',
                'value'   => 'newsletter',
                'compare' => '=',
            ],
            [
                'key'     => 'lead_type',
                'value'   => 'Contact',
                'compare' => 'NOT LIKE',
            ],
        ];
        $filename = 'foundnxt-newsletter-subscribers-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Subscriber ID',
            'Subscribed Date',
            'Full Name',
            'Email Address',
            'Signup Form',
            'Source Page URL',
            'Target Sheet'
        ];
    } else {
        $filename = 'foundnxt-all-leads-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Lead ID',
            'Date Received',
            'Name',
            'Email',
            'Phone / Mobile',
            'Company / Website',
            'Company Stage',
            'Help Topic / Need',
            'Channel / Lead Type',
            'Target Sheet',
            'Message',
            'Source Page'
        ];
    }

    $query = new WP_Query($args);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // UTF-8 BOM for Microsoft Excel compatibility
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Write Headers
    fputcsv($output, $headers);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $lead = fnx_crm_get_lead(get_the_ID());
            if (! $lead) {
                continue;
            }

            if ($channel === 'contact') {
                fputcsv($output, [
                    $lead['id'],
                    $lead['date_full'],
                    $lead['name'],
                    $lead['email'],
                    $lead['phone'],
                    $lead['company'],
                    $lead['stage'],
                    $lead['help'],
                    $lead['lead_type'],
                    $lead['message'],
                    $lead['source'],
                    $lead['target_sheet'],
                ]);
            } elseif ($channel === 'newsletter') {
                fputcsv($output, [
                    $lead['id'],
                    $lead['date_full'],
                    $lead['name'],
                    $lead['email'],
                    $lead['lead_type'],
                    $lead['source'],
                    $lead['target_sheet'],
                ]);
            } else {
                fputcsv($output, [
                    $lead['id'],
                    $lead['date_full'],
                    $lead['name'],
                    $lead['email'],
                    $lead['phone'],
                    $lead['company'],
                    $lead['stage'],
                    $lead['help'],
                    $lead['lead_type'],
                    $lead['target_sheet'],
                    $lead['message'],
                    $lead['source'],
                ]);
            }
        }
        wp_reset_postdata();
    }

    fclose($output);
    exit;
}
add_action('admin_post_fnx_export_leads_csv', 'fnx_crm_export_leads_csv');


/**
 * ══════════════════════════════════════════════════════════════
 * 8. IN-PAGE QUICK VIEW SLIDE-OVER DRAWER MODAL
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_render_quick_view_modal() {
    $screen = get_current_screen();
    if (! $screen || $screen->id !== 'edit-fnx_lead') {
        return;
    }
    ?>
    <!-- In-Page Quick View Slide-Over Modal -->
    <div id="fnx-crm-modal-backdrop" class="fnx-crm-backdrop" style="display: none;" onclick="fnxCloseQuickView();"></div>
    <div id="fnx-crm-drawer" class="fnx-crm-drawer" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="fnx-modal-name">
        <div class="fnx-drawer-header">
            <div class="fnx-drawer-header-left">
                <div id="fnx-modal-avatar" class="fnx-crm-avatar large">TS</div>
                <div>
                    <h2 id="fnx-modal-name" class="fnx-drawer-title">Thabresh Syed</h2>
                    <span id="fnx-modal-pill" class="fnx-crm-pill fnx-pill-emerald">Contact Form</span>
                </div>
            </div>
            <button type="button" class="fnx-drawer-close" onclick="fnxCloseQuickView();" aria-label="<?php esc_attr_e('Close drawer', 'foundnxt'); ?>">
                &times;
            </button>
        </div>

        <div class="fnx-drawer-body">
            <!-- Action Bar -->
            <div class="fnx-drawer-actions">
                <a id="fnx-modal-mailto" href="#" class="button button-primary button-large fnx-cta-email">
                    <span class="dashicons dashicons-email-alt" style="margin-top: 3px;"></span>
                    <?php _e('Reply via Email', 'foundnxt'); ?>
                </a>
                <a id="fnx-modal-call" href="#" class="button button-secondary button-large fnx-cta-call" style="display: none;">
                    <span class="dashicons dashicons-phone" style="margin-top: 3px;"></span>
                    <?php _e('Call', 'foundnxt'); ?>
                </a>
                <button type="button" class="button button-secondary button-large" onclick="fnxCopyLeadSummary();">
                    <span class="dashicons dashicons-clipboard" style="margin-top: 3px;"></span>
                    <?php _e('Copy Details', 'foundnxt'); ?>
                </button>
            </div>

            <!-- Contact Information Grid -->
            <div class="fnx-drawer-section">
                <h3 class="fnx-drawer-section-title"><?php _e('Contact Information', 'foundnxt'); ?></h3>
                <div class="fnx-drawer-grid">
                    <div class="fnx-grid-item">
                        <label><?php _e('Email Address', 'foundnxt'); ?></label>
                        <div id="fnx-modal-email" class="fnx-grid-val">—</div>
                    </div>
                    <div class="fnx-grid-item">
                        <label><?php _e('Phone / Mobile', 'foundnxt'); ?></label>
                        <div id="fnx-modal-phone" class="fnx-grid-val">—</div>
                    </div>
                    <div class="fnx-grid-item">
                        <label><?php _e('Company / Website', 'foundnxt'); ?></label>
                        <div id="fnx-modal-company" class="fnx-grid-val">—</div>
                    </div>
                    <div class="fnx-grid-item">
                        <label><?php _e('Company Stage', 'foundnxt'); ?></label>
                        <div id="fnx-modal-stage" class="fnx-grid-val">—</div>
                    </div>
                </div>
            </div>

            <!-- Inquiry Details -->
            <div class="fnx-drawer-section">
                <h3 class="fnx-drawer-section-title"><?php _e('Inquiry & Sync Details', 'foundnxt'); ?></h3>
                <div class="fnx-drawer-grid">
                    <div class="fnx-grid-item">
                        <label><?php _e('Help Required / Topic', 'foundnxt'); ?></label>
                        <div id="fnx-modal-topic" class="fnx-grid-val">—</div>
                    </div>
                    <div class="fnx-grid-item">
                        <label><?php _e('Sync Destination Tab', 'foundnxt'); ?></label>
                        <div id="fnx-modal-sheet" class="fnx-grid-val">—</div>
                    </div>
                    <div class="fnx-grid-item" style="grid-column: span 2;">
                        <label><?php _e('Source Page', 'foundnxt'); ?></label>
                        <div id="fnx-modal-source" class="fnx-grid-val">—</div>
                    </div>
                    <div class="fnx-grid-item" style="grid-column: span 2;">
                        <label><?php _e('Timestamp Received', 'foundnxt'); ?></label>
                        <div id="fnx-modal-date" class="fnx-grid-val">—</div>
                    </div>
                </div>
            </div>

            <!-- Message Card -->
            <div class="fnx-drawer-section">
                <h3 class="fnx-drawer-section-title"><?php _e('Message / Requirement Notes', 'foundnxt'); ?></h3>
                <div id="fnx-modal-message" class="fnx-drawer-message-box">
                    <?php _e('No message provided.', 'foundnxt'); ?>
                </div>
            </div>
        </div>

        <div class="fnx-drawer-footer">
            <a id="fnx-modal-edit-link" href="#" class="button button-secondary">
                <span class="dashicons dashicons-admin-generic" style="vertical-align: middle; margin-top: -2px;"></span>
                <?php _e('Open Full WP Edit Screen', 'foundnxt'); ?>
            </a>
            <button type="button" class="button button-primary" onclick="fnxCloseQuickView();">
                <?php _e('Done', 'foundnxt'); ?>
            </button>
        </div>
    </div>

    <!-- Quick View JS Controller -->
    <script>
    var currentActiveLead = null;

    function fnxOpenQuickView(btn) {
        var raw = btn.getAttribute('data-lead');
        if (! raw) {
            var parent = btn.closest('.fnx-crm-contact-cell');
            if (parent) raw = parent.getAttribute('data-lead');
        }
        if (! raw) return;

        try {
            var lead = JSON.parse(raw);
            currentActiveLead = lead;

            document.getElementById('fnx-modal-name').innerText = lead.name;
            document.getElementById('fnx-modal-avatar').innerText = fnxGetInitials(lead.name);
            document.getElementById('fnx-modal-avatar').style.background = fnxGetGradient(lead.name);

            // Channel Pill
            var pill = document.getElementById('fnx-modal-pill');
            pill.innerText = lead.lead_type;
            pill.className = 'fnx-crm-pill ' + (lead.lead_type.indexOf('Contact') !== -1 ? 'fnx-pill-emerald' : 'fnx-pill-purple');

            // Contact Info
            document.getElementById('fnx-modal-email').innerHTML = lead.email ? '<a href="mailto:' + lead.email + '"><strong>' + lead.email + '</strong></a>' : '<span style="color:#94a3b8;">None</span>';
            document.getElementById('fnx-modal-phone').innerHTML = lead.phone ? '<a href="tel:' + lead.phone + '"><strong>' + lead.phone + '</strong></a>' : '<span style="color:#94a3b8;">None</span>';
            document.getElementById('fnx-modal-company').innerHTML = lead.company ? '<strong>' + lead.company + '</strong>' : '<span style="color:#94a3b8;">None</span>';
            document.getElementById('fnx-modal-stage').innerHTML = '<span class="fnx-crm-subtag">' + (lead.stage || 'Not Specified') + '</span>';

            // Inquiry Info
            document.getElementById('fnx-modal-topic').innerHTML = '<span class="fnx-crm-pill fnx-pill-blue">' + (lead.help || 'General') + '</span>';
            document.getElementById('fnx-modal-sheet').innerHTML = '<code>' + (lead.target_sheet || 'newsletter') + '</code>';
            document.getElementById('fnx-modal-source').innerHTML = '<a href="' + lead.source + '" target="_blank">' + lead.source + '</a>';
            document.getElementById('fnx-modal-date').innerText = lead.date_full + ' (' + lead.date_rel + ')';

            // Message
            var msgBox = document.getElementById('fnx-modal-message');
            if (lead.message && lead.message.trim().length > 0) {
                msgBox.innerText = lead.message;
                msgBox.style.fontStyle = 'normal';
                msgBox.style.color = '#1e293b';
            } else {
                msgBox.innerText = 'No message supplied (Subscriber lead).';
                msgBox.style.fontStyle = 'italic';
                msgBox.style.color = '#94a3b8';
            }

            // CTAs
            var mailto = document.getElementById('fnx-modal-mailto');
            if (lead.email) {
                mailto.href = 'mailto:' + encodeURIComponent(lead.email) + '?subject=' + encodeURIComponent('FoundNXT: Regarding your ' + lead.help + ' enquiry');
                mailto.style.display = 'inline-flex';
            } else {
                mailto.style.display = 'none';
            }

            var callBtn = document.getElementById('fnx-modal-call');
            if (lead.phone) {
                callBtn.href = 'tel:' + encodeURIComponent(lead.phone);
                callBtn.style.display = 'inline-flex';
            } else {
                callBtn.style.display = 'none';
            }

            document.getElementById('fnx-modal-edit-link').href = 'post.php?post=' + lead.id + '&action=edit';

            // Show drawer
            document.getElementById('fnx-crm-modal-backdrop').style.display = 'block';
            var drawer = document.getElementById('fnx-crm-drawer');
            drawer.style.display = 'flex';
            setTimeout(function() {
                drawer.classList.add('is-open');
            }, 10);

        } catch (e) {
            console.error('Error rendering lead details', e);
        }
    }

    function fnxCloseQuickView() {
        var drawer = document.getElementById('fnx-crm-drawer');
        if (drawer) {
            drawer.classList.remove('is-open');
            setTimeout(function() {
                drawer.style.display = 'none';
                document.getElementById('fnx-crm-modal-backdrop').style.display = 'none';
            }, 250);
        }
    }

    // Keyboard ESC to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fnxCloseQuickView();
        }
    });

    function fnxGetInitials(name) {
        if (! name) return 'NX';
        var p = name.trim().split(/\s+/);
        if (p.length >= 2) return (p[0][0] + p[1][0]).toUpperCase();
        return name.substr(0, 2).toUpperCase();
    }

    function fnxGetGradient(name) {
        var grads = [
            'linear-gradient(135deg, #FF5A2C, #FF7A45)',
            'linear-gradient(135deg, #0ea5e9, #00d4aa)',
            'linear-gradient(135deg, #10b981, #059669)',
            'linear-gradient(135deg, #f59e0b, #ff7a45)',
            'linear-gradient(135deg, #ec4899, #f43f5e)',
            'linear-gradient(135deg, #FF5A2C, #FFA834)'
        ];
        var code = 0;
        for (var i = 0; i < name.length; i++) code += name.charCodeAt(i);
        return grads[code % grads.length];
    }

    function fnxCopyText(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            var original = btn.innerHTML;
            btn.innerHTML = '<span class="dashicons dashicons-yes" style="color:#10b981;"></span>';
            setTimeout(function() { btn.innerHTML = original; }, 2000);
        });
    }

    function fnxCopyLeadSummary() {
        if (! currentActiveLead) return;
        var s = "FoundNXT Lead: " + currentActiveLead.name + "\n"
              + "Email: " + currentActiveLead.email + "\n"
              + "Phone: " + currentActiveLead.phone + "\n"
              + "Company: " + currentActiveLead.company + " (" + currentActiveLead.stage + ")\n"
              + "Topic: " + currentActiveLead.help + "\n"
              + "Channel: " + currentActiveLead.lead_type + "\n"
              + "Message: " + currentActiveLead.message;
        navigator.clipboard.writeText(s).then(function() {
            alert('Lead details copied to clipboard!');
        });
    }
    </script>
    <?php
}
add_action('admin_footer', 'fnx_crm_render_quick_view_modal');


/**
 * ══════════════════════════════════════════════════════════════
 * 9. LEAD DETAIL METABOX (FOR post.php?post=...)
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_register_metaboxes() {
    add_meta_box(
        'fnx_lead_crm_profile',
        __('FoundNXT CRM — Lead Profile & Inquiry Overview', 'foundnxt'),
        'fnx_crm_render_lead_metabox',
        'fnx_lead',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'fnx_crm_register_metaboxes');

function fnx_crm_render_lead_metabox($post) {
    $lead = fnx_crm_get_lead($post->ID);
    if (! $lead) {
        echo '<p>' . __('No data available.', 'foundnxt') . '</p>';
        return;
    }
    $initials = fnx_crm_initials($lead['name']);
    $grad     = fnx_crm_avatar_gradient($lead['name']);
    ?>
    <div class="fnx-crm-metabox-card">
        <div style="display: flex; gap: 18px; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
            <div class="fnx-crm-avatar large" style="background: <?php echo esc_attr($grad); ?>;">
                <?php echo esc_html($initials); ?>
            </div>
            <div style="flex: 1;">
                <h2 style="margin: 0 0 6px 0; font-size: 1.5rem; color: #0f172a;"><?php echo esc_html($lead['name']); ?></h2>
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <span class="fnx-crm-pill fnx-pill-purple"><?php echo esc_html($lead['lead_type']); ?></span>
                    <span class="fnx-crm-pill fnx-pill-blue"><?php echo esc_html($lead['help']); ?></span>
                    <span class="fnx-crm-pill fnx-pill-emerald"><?php echo esc_html(sprintf(__('GSheets: %s', 'foundnxt'), $lead['target_sheet'])); ?></span>
                </div>
            </div>
            <div>
                <?php if (! empty($lead['email'])): ?>
                <a href="mailto:<?php echo esc_attr($lead['email']); ?>?subject=<?php echo esc_attr(rawurlencode('FoundNXT: Regarding your ' . $lead['help'] . ' enquiry')); ?>" class="button button-primary button-hero">
                    <span class="dashicons dashicons-email-alt" style="margin-top: 5px;"></span> <?php _e('Reply via Email', 'foundnxt'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <div class="fnx-grid-item" style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;"><?php _e('Email Address', 'foundnxt'); ?></label>
                <div style="font-size: 1.05rem; margin-top: 4px;">
                    <?php if (! empty($lead['email'])): ?>
                        <a href="mailto:<?php echo esc_attr($lead['email']); ?>"><strong><?php echo esc_html($lead['email']); ?></strong></a>
                    <?php else: ?>
                        <span style="color:#94a3b8;">—</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="fnx-grid-item" style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;"><?php _e('Mobile / Phone', 'foundnxt'); ?></label>
                <div style="font-size: 1.05rem; margin-top: 4px;">
                    <?php if (! empty($lead['phone'])): ?>
                        <a href="tel:<?php echo esc_attr($lead['phone']); ?>"><strong><?php echo esc_html($lead['phone']); ?></strong></a>
                    <?php else: ?>
                        <span style="color:#94a3b8;">—</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="fnx-grid-item" style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;"><?php _e('Company / Website', 'foundnxt'); ?></label>
                <div style="font-size: 1.05rem; margin-top: 4px;">
                    <strong><?php echo esc_html($lead['company'] ?: '—'); ?></strong>
                </div>
            </div>

            <div class="fnx-grid-item" style="background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;"><?php _e('Company Stage', 'foundnxt'); ?></label>
                <div style="font-size: 1.05rem; margin-top: 4px;">
                    <span class="fnx-crm-subtag"><?php echo esc_html($lead['stage']); ?></span>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <label style="font-size: 0.8125rem; text-transform: uppercase; color: #475569; font-weight: 700; display: block; margin-bottom: 8px;">
                <?php _e('Inquiry Message / Requirement Details', 'foundnxt'); ?>
            </label>
            <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 18px; font-size: 0.95rem; line-height: 1.7; color: #1e293b; white-space: pre-wrap;">
                <?php echo esc_html($lead['message'] ?: __('No message provided (Newsletter Subscriber)', 'foundnxt')); ?>
            </div>
        </div>

        <div style="display: flex; gap: 20px; font-size: 0.875rem; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 14px;">
            <div><strong><?php _e('Received:', 'foundnxt'); ?></strong> <?php echo esc_html($lead['date_full']); ?> (<?php echo esc_html($lead['date_rel']); ?>)</div>
            <div><strong><?php _e('Source URL:', 'foundnxt'); ?></strong> <a href="<?php echo esc_url($lead['source']); ?>" target="_blank"><?php echo esc_html($lead['source']); ?></a></div>
        </div>
    </div>
    <?php
}


/**
 * ══════════════════════════════════════════════════════════════
 * 10. MODERN CRM STYLES (SCOPED STRICTLY TO fnx_lead SCREENS)
 * ══════════════════════════════════════════════════════════════
 */
function fnx_crm_admin_styles() {
    $screen = get_current_screen();
    if (! $screen || ! in_array($screen->id, ['edit-fnx_lead', 'fnx_lead'], true)) {
        return;
    }
    ?>
    <style id="fnx-crm-admin-css">
    /* ════════════════════════════════════
       KPI DASHBOARD & SEGMENTED TABS
    ════════════════════════════════════ */
    .fnx-crm-kpi-wrap {
        margin: 16px 0 20px 0;
        max-width: 100%;
    }

    /* Segmented Navigation Tabs */
    .fnx-crm-nav-tabs {
        display: flex;
        gap: 8px;
        align-items: center;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 18px;
        width: fit-content;
        max-width: 100%;
        overflow-x: auto;
    }
    .fnx-crm-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 7px;
        text-decoration: none !important;
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569 !important;
        transition: all 0.15s ease;
        white-space: nowrap;
    }
    .fnx-crm-tab:hover {
        color: #0f172a !important;
        background: rgba(255, 255, 255, 0.6);
    }
    .fnx-crm-tab.is-active {
        background: #ffffff;
        color: #0f172a !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    .fnx-crm-tab .dashicons {
        font-size: 16px;
        width: 16px;
        height: 16px;
    }
    .fnx-crm-tab.is-active.is-contact .dashicons {
        color: #059669;
    }
    .fnx-crm-tab.is-active.is-newsletter .dashicons {
        color: #FF5A2C;
    }
    .fnx-crm-tab.is-active.is-all .dashicons {
        color: #0284c7;
    }
    .fnx-tab-badge {
        padding: 2px 7px;
        border-radius: 9999px;
        font-size: 0.6875rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .fnx-badge-emerald { background: #d1fae5; color: #065f46; }
    .fnx-badge-purple,
    .fnx-badge-orange  { background: #fff2ee; color: #D83B0F; }
    .fnx-badge-gray    { background: #e2e8f0; color: #334155; }

    /* KPI Cards */
    .fnx-crm-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    .fnx-crm-kpi-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .fnx-crm-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
    }
    .fnx-crm-kpi-card.is-highlighted {
        border-color: #FF5A2C;
        box-shadow: 0 0 0 2px rgba(255, 90, 44, 0.15);
    }
    .fnx-crm-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .fnx-crm-kpi-icon .dashicons {
        font-size: 24px;
        width: 24px;
        height: 24px;
    }
    .fnx-kpi-total .fnx-crm-kpi-icon { background: rgba(255, 90, 44, 0.12); color: #FF5A2C; }
    .fnx-kpi-contact .fnx-crm-kpi-icon { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .fnx-kpi-newsletter .fnx-crm-kpi-icon { background: rgba(14, 165, 233, 0.12); color: #0284c7; }
    .fnx-kpi-velocity .fnx-crm-kpi-icon { background: rgba(245, 158, 11, 0.12); color: #d97706; }

    .fnx-crm-kpi-info {
        display: flex;
        flex-direction: column;
    }
    .fnx-crm-kpi-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .fnx-crm-kpi-num {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin: 2px 0;
    }
    .fnx-crm-kpi-hint {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* ════════════════════════════════════
       TOOLBAR BAR
    ════════════════════════════════════ */
    .fnx-crm-header-toolbar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .fnx-crm-toolbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .fnx-crm-brand-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        font-size: 0.8125rem;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .fnx-crm-sync-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        color: #059669;
        font-weight: 600;
    }
    .fnx-pulse-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
    }
    .fnx-crm-toolbar-right {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .fnx-crm-export-btn {
        background: #f8fafc !important;
        font-weight: 600 !important;
    }

    /* ════════════════════════════════════
       CRM POSTS TABLE OVERHAUL
    ════════════════════════════════════ */
    .wp-list-table.fnx_lead,
    .wp-list-table.posts {
        border-radius: 10px !important;
        overflow: hidden !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
        background: #ffffff !important;
    }
    .wp-list-table thead th,
    .wp-list-table tfoot th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 12px 14px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .wp-list-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .wp-list-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
    .wp-list-table td {
        padding: 14px 14px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
    }

    /* Contact Cell */
    .fnx-crm-contact-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .fnx-crm-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        user-select: none;
    }
    .fnx-crm-avatar.large {
        width: 54px;
        height: 54px;
        font-size: 1.15rem;
    }
    .fnx-crm-contact-meta {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .fnx-crm-name-link {
        font-size: 0.9375rem;
        color: #0f172a !important;
        text-decoration: none;
    }
    .fnx-crm-name-link:hover {
        color: #FF5A2C !important;
        text-decoration: underline;
    }
    .fnx-crm-email-row,
    .fnx-crm-phone-row {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.8125rem;
        color: #64748b;
    }
    .fnx-crm-email-row .dashicons,
    .fnx-crm-phone-row .dashicons {
        font-size: 13px;
        width: 13px;
        height: 13px;
        opacity: 0.7;
    }
    .fnx-crm-email-link,
    .fnx-crm-phone-link {
        color: #475569 !important;
        text-decoration: none;
    }
    .fnx-crm-email-link:hover,
    .fnx-crm-phone-link:hover {
        color: #FF5A2C !important;
    }
    .fnx-copy-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .fnx-copy-btn:hover {
        color: #FF5A2C;
    }

    /* Badges & Pills */
    .fnx-crm-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .fnx-crm-pill .dashicons {
        font-size: 13px;
        width: 13px;
        height: 13px;
    }
    .fnx-pill-emerald {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }
    .fnx-pill-purple {
        background: #fff2ee; color: #FF5A2C; border-color: #ffd5c8;
    }
    .fnx-pill-amber {
        background: #fffbeb;
        color: #d97706;
        border-color: #fde68a;
    }
    .fnx-pill-cyan,
    .fnx-pill-teal {
        background: #f0fdfa;
        color: #0d9488;
        border-color: #99f6e4;
    }
    .fnx-pill-blue {
        background: #f0f9ff;
        color: #0284c7;
        border-color: #bae6fd;
    }
    .fnx-pill-pink {
        background: #fdf2f8;
        color: #db2777;
        border-color: #fbcfe8;
    }
    .fnx-pill-gray {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }
    .fnx-pill-rose {
        background: #fff1f2;
        color: #e11d48;
        border-color: #fecdd3;
    }

    /* Company Cell */
    .fnx-crm-company-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .fnx-crm-company-name {
        font-size: 0.875rem;
        color: #1e293b;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .fnx-crm-company-name:hover {
        color: #FF5A2C;
    }
    .fnx-crm-subtag {
        font-size: 0.71875rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        width: fit-content;
    }

    /* Message snippet */
    .fnx-crm-message-cell {
        max-width: 240px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .fnx-crm-snippet {
        font-size: 0.8125rem;
        color: #475569;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .fnx-crm-quickview-btn {
        background: transparent;
        border: none;
        color: #FF5A2C;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .fnx-crm-quickview-btn:hover {
        text-decoration: underline;
    }

    /* Date Cell */
    .fnx-crm-date-cell {
        display: flex;
        flex-direction: column;
        font-size: 0.8125rem;
        color: #1e293b;
    }
    .fnx-crm-date-sub {
        font-size: 0.71875rem;
        color: #94a3b8;
    }

    /* Action Buttons */
    .fnx-crm-action-buttons {
        display: flex;
        gap: 4px;
        align-items: center;
    }
    .fnx-action-btn {
        padding: 0 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 28px !important;
        width: 28px !important;
        color: #475569 !important;
        border-radius: 4px !important;
    }
    .fnx-action-btn:hover {
        color: #FF5A2C !important;
        border-color: #FF5A2C !important;
    }
    .fnx-action-btn .dashicons {
        font-size: 15px;
        width: 15px;
        height: 15px;
    }

    /* ════════════════════════════════════
       QUICK VIEW SLIDE-OVER DRAWER MODAL
    ════════════════════════════════════ */
    .fnx-crm-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        z-index: 100000;
        transition: opacity 0.25s ease;
    }
    .fnx-crm-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        max-width: 540px;
        background: #ffffff;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.15);
        z-index: 100001;
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .fnx-crm-drawer.is-open {
        transform: translateX(0);
    }
    .fnx-drawer-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }
    .fnx-drawer-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .fnx-drawer-title {
        margin: 0 0 4px 0;
        font-size: 1.25rem;
        color: #0f172a;
    }
    .fnx-drawer-close {
        background: transparent;
        border: none;
        font-size: 28px;
        color: #64748b;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    .fnx-drawer-close:hover {
        color: #0f172a;
    }
    .fnx-drawer-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .fnx-drawer-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .fnx-cta-email {
        background: #FF5A2C !important;
        border-color: #FF5A2C !important;
        color: #ffffff !important;
    }
    .fnx-drawer-section-title {
        font-size: 0.8125rem;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
        margin: 0 0 12px 0;
        font-weight: 700;
    }
    .fnx-drawer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
    }
    .fnx-grid-item label {
        font-size: 0.6875rem;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 700;
        display: block;
        margin-bottom: 3px;
    }
    .fnx-grid-val {
        font-size: 0.875rem;
        color: #1e293b;
        word-break: break-word;
    }
    .fnx-drawer-message-box {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 16px;
        font-size: 0.9375rem;
        line-height: 1.6;
        color: #1e293b;
        white-space: pre-wrap;
    }
    .fnx-drawer-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ════════════════════════════════════
       RESPONSIVE ADJUSTMENTS
    ════════════════════════════════════ */
    @media (max-width: 960px) {
        .fnx-crm-kpi-grid {
            grid-template-columns: 1fr 1fr;
        }
        .fnx-crm-drawer {
            max-width: 100%;
        }
    }
    @media (max-width: 600px) {
        .fnx-crm-kpi-grid {
            grid-template-columns: 1fr;
        }
        .fnx-crm-header-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }
        .fnx-crm-nav-tabs {
            flex-direction: column;
            width: 100%;
        }
        .fnx-crm-tab {
            width: 100%;
            justify-content: space-between;
        }
    }
    </style>
    <?php
}
add_action('admin_head', 'fnx_crm_admin_styles');
