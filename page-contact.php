<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * Dedicated Contact & Advisory Enquiry page for FoundNXT.
 * Assign this template to your /contact/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_name   = get_bloginfo('name');
$site_url    = esc_url(home_url('/'));
$contact_url = esc_url(get_permalink());
$pre_help    = isset($_GET['help']) ? sanitize_text_field(wp_unslash($_GET['help'])) : 'Market Research Request';

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'ContactPage',
      '@id'         => $contact_url . '#contactpage',
      'url'         => $contact_url,
      'name'        => 'Contact FoundNXT — Business & Advisory Enquiries',
      'description' => 'Get in touch with the FoundNXT team for Market Research Requests, Valuation Guidance, Tech & AI Strategy, Marketing Help, or Partnerships.',
      'inLanguage'  => 'en',
      'isPartOf'    => ['@id' => $site_url . '#website'],
      'publisher'   => [
        '@type' => 'Organization',
        'name'  => $site_name,
        'url'   => $site_url,
      ],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact', 'item' => $contact_url],
      ],
    ],
  ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
?>

<div class="fnx-archive-page fnx-contact-page">
  <div class="container container-narrow">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO -->
    <section class="about-hero text-center" style="margin-bottom: 40px;">
      <span class="hp-sec-tag"><?php _e('Get in Touch', 'foundnxt'); ?></span>
      <h1 class="about-h1" style="font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: 12px;">
        <?php _e('Contact FoundNXT', 'foundnxt'); ?>
      </h1>
      <p class="about-lead" style="max-width: 680px; margin: 0 auto;">
        <?php _e('Have a question, feedback, story tip, advisory requirement, or partnership enquiry? Fill out the form below — our team replies within 24–48 hours.', 'foundnxt'); ?>
      </p>
    </section>

    <!-- CONTACT FORM CARD -->
    <div class="hp-contact-box fnx-reveal" id="contact" style="background: var(--fnx-card); border: 1.5px solid var(--fnx-border); border-radius: var(--fnx-radius-lg); padding: 40px 36px;">

      <?php
      if (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'success') {
        echo '<div class="hp-contact-alert hp-contact-alert--ok" role="status" style="background: rgba(16,185,129,0.12); border: 1px solid #10B981; color: #10B981; padding: 14px 18px; border-radius: 10px; margin-bottom: 24px; font-weight: 600;">✓ ' . esc_html__('Thank you! Your message has been sent successfully. We will be in touch with you shortly.', 'foundnxt') . '</div>';
      } elseif (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'error') {
        echo '<div class="hp-contact-alert hp-contact-alert--err" role="alert" style="background: rgba(239,68,68,0.12); border: 1px solid #EF4444; color: #EF4444; padding: 14px 18px; border-radius: 10px; margin-bottom: 24px; font-weight: 600;">✕ ' . esc_html__('Something went wrong. Please check your information and try again.', 'foundnxt') . '</div>';
      }
      ?>

      <form class="hp-lead-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="fnx_lead_submit">
        <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
        <input type="hidden" name="lead_type" value="Contact Page Form Submission">
        
        <!-- Honeypot -->
        <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" autocomplete="off">

        <div class="form-row grid-2" style="margin-bottom: 20px;">
          <div class="form-group">
            <label for="lead_name" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.875rem; color:var(--fnx-ink);"><?php _e('Name *', 'foundnxt'); ?></label>
            <input type="text" id="lead_name" name="lead_name" placeholder="<?php esc_attr_e('Jordan Lee', 'foundnxt'); ?>" required style="width:100%; padding:12px 16px; border-radius:8px; border:1px solid var(--fnx-border); background:var(--fnx-surface); color:var(--fnx-ink);">
          </div>
          <div class="form-group">
            <label for="lead_email" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.875rem; color:var(--fnx-ink);"><?php _e('Work Email *', 'foundnxt'); ?></label>
            <input type="email" id="lead_email" name="lead_email" placeholder="<?php esc_attr_e('jordan@company.com', 'foundnxt'); ?>" required style="width:100%; padding:12px 16px; border-radius:8px; border:1px solid var(--fnx-border); background:var(--fnx-surface); color:var(--fnx-ink);">
          </div>
        </div>

        <div class="form-row grid-2" style="margin-bottom: 20px;">
          <div class="form-group">
            <label for="lead_company" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.875rem; color:var(--fnx-ink);"><?php _e('Company / Website (Optional)', 'foundnxt'); ?></label>
            <input type="text" id="lead_company" name="lead_company" placeholder="<?php esc_attr_e('https://yourcompany.com', 'foundnxt'); ?>" style="width:100%; padding:12px 16px; border-radius:8px; border:1px solid var(--fnx-border); background:var(--fnx-surface); color:var(--fnx-ink);">
          </div>
          <div class="form-group">
            <label for="lead_stage" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.875rem; color:var(--fnx-ink);"><?php _e('Company Stage or Size', 'foundnxt'); ?></label>
            <select id="lead_stage" name="lead_stage" style="width:100%; padding:12px 16px; border-radius:8px; border:1px solid var(--fnx-border); background:var(--fnx-surface); color:var(--fnx-ink);">
              <option value="Early Stage / Seed"><?php _e('Early Stage / Seed', 'foundnxt'); ?></option>
              <option value="Growth / Series A+"><?php _e('Growth / Series A+', 'foundnxt'); ?></option>
              <option value="Scaleup"><?php _e('Scaleup', 'foundnxt'); ?></option>
              <option value="Enterprise / Established"><?php _e('Enterprise / Established', 'foundnxt'); ?></option>
            </select>
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
          <label for="lead_help" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.875rem; color:var(--fnx-ink);"><?php _e('What do you need help with?', 'foundnxt'); ?></label>
          <select id="lead_help" name="lead_help" style="width:100%; padding:12px 16px; border-radius:8px; border:1px solid var(--fnx-border); background:var(--fnx-surface); color:var(--fnx-ink);">
            <option value="Market Research Request" <?php selected($pre_help, 'Market Research Request'); ?>><?php _e('Market Research Request', 'foundnxt'); ?></option>
            <option value="Valuation Guidance" <?php selected($pre_help, 'Valuation Guidance'); ?>><?php _e('Valuation Guidance', 'foundnxt'); ?></option>
            <option value="Tech & AI Strategy" <?php selected($pre_help, 'Tech & AI Strategy'); ?>><?php _e('Tech & AI Strategy', 'foundnxt'); ?></option>
            <option value="Marketing Help" <?php selected($pre_help, 'Marketing Help'); ?>><?php _e('Marketing & Growth Help', 'foundnxt'); ?></option>
            <option value="Partnerships" <?php selected($pre_help, 'Partnerships'); ?>><?php _e('Partnerships & Sponsored Content', 'foundnxt'); ?></option>
            <option value="Other" <?php selected($pre_help, 'Other'); ?>><?php _e('Other Inquiry', 'foundnxt'); ?></option>
          </select>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
          <label for="lead_message" style="display:block; font-weight:600; margin-bottom:6px; font-size:0.875rem; color:var(--fnx-ink);"><?php _e('Message *', 'foundnxt'); ?></label>
          <textarea id="lead_message" name="lead_message" rows="5" placeholder="<?php esc_attr_e('Describe your requirement, company background, or questions...', 'foundnxt'); ?>" required style="width:100%; padding:12px 16px; border-radius:8px; border:1px solid var(--fnx-border); background:var(--fnx-surface); color:var(--fnx-ink);"></textarea>
        </div>

        <div class="form-group form-checkbox-group" style="margin-bottom: 24px;">
          <label class="checkbox-label" style="display:flex; align-items:center; gap:8px; font-size:0.84375rem; color:var(--fnx-body);">
            <input type="checkbox" name="lead_consent" required checked>
            <span><?php _e('I consent to FoundNXT storing my information to respond to this enquiry.', 'foundnxt'); ?></span>
          </label>
        </div>

        <button type="submit" class="btn-primary form-submit-btn" style="width:100%; padding:14px; font-size:1rem; font-weight:700;">
          <?php _e('Send Message', 'foundnxt'); ?> →
        </button>
      </form>
    </div>

  </div>
</div>

<?php get_footer(); ?>
