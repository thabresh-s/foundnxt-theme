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

<div class="fnx-archive-page fnx-contact-page" style="padding: 40px 0 80px;">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <div class="hp-contact-v2-wrapper fnx-reveal" id="contact">
      <div class="hp-contact-v2-grid">
        
        <!-- Left Column: Contact Positioning & Value Prop -->
        <div class="hp-contact-v2-side">
          <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">💬 <?php _e('Executive Advisory', 'foundnxt'); ?></span>
          <h1 class="hp-contact-v2-title">
            <?php _e('Get in Touch with', 'foundnxt'); ?> <span class="text-gradient"><?php _e('FoundNXT', 'foundnxt'); ?></span>
          </h1>
          <p class="hp-contact-v2-lead">
            <?php _e('Have a custom market research request, valuation inquiry, tech & AI strategy audit, or partnership proposal? Fill out the form — our advisory team responds within 24–48 hours.', 'foundnxt'); ?>
          </p>

          <div class="hp-contact-v2-features">
            <div class="hp-contact-feat-item">
              <div class="feat-icon">⚡</div>
              <div>
                <strong><?php _e('24–48 Hour Response Guarantee', 'foundnxt'); ?></strong>
                <p><?php _e('Direct access to our senior research & strategy team.', 'foundnxt'); ?></p>
              </div>
            </div>
            <div class="hp-contact-feat-item">
              <div class="feat-icon">🔒</div>
              <div>
                <strong><?php _e('Strict Confidentiality', 'foundnxt'); ?></strong>
                <p><?php _e('Your company details and advisory data remain 100% private.', 'foundnxt'); ?></p>
              </div>
            </div>
            <div class="hp-contact-feat-item">
              <div class="feat-icon">🎯</div>
              <div>
                <strong><?php _e('Tailored Intelligence', 'foundnxt'); ?></strong>
                <p><?php _e('Actionable insights custom-built for startup founders and tech leaders.', 'foundnxt'); ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Modern Contact Form Card -->
        <div class="hp-contact-v2-form-box">
          <?php
          if (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'success') {
            echo '<div class="hp-contact-alert hp-contact-alert--ok" role="status">✓ ' . esc_html__('Thank you! Your enquiry has been received. Our team will contact you shortly.', 'foundnxt') . '</div>';
          } elseif (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'error') {
            echo '<div class="hp-contact-alert hp-contact-alert--err" role="alert">✕ ' . esc_html__('Something went wrong. Please check your information and try again.', 'foundnxt') . '</div>';
          }
          ?>

          <form class="hp-lead-contact-form-v2" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Contact Page Form Submission">
            
            <!-- Honeypot -->
            <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" autocomplete="off">

            <div class="form-row-v2 grid-2">
              <div class="form-group-v2">
                <label for="lead_name"><?php _e('Name *', 'foundnxt'); ?></label>
                <input type="text" id="lead_name" name="lead_name" placeholder="<?php esc_attr_e('Jordan Lee', 'foundnxt'); ?>" required>
              </div>
              <div class="form-group-v2">
                <label for="lead_email"><?php _e('Work Email *', 'foundnxt'); ?></label>
                <input type="email" id="lead_email" name="lead_email" placeholder="<?php esc_attr_e('jordan@company.com', 'foundnxt'); ?>" required>
              </div>
            </div>

            <div class="form-row-v2 grid-2">
              <div class="form-group-v2">
                <label for="lead_company"><?php _e('Company / Website (Optional)', 'foundnxt'); ?></label>
                <input type="text" id="lead_company" name="lead_company" placeholder="<?php esc_attr_e('https://yourcompany.com', 'foundnxt'); ?>">
              </div>
              <div class="form-group-v2">
                <label for="lead_stage"><?php _e('Company Stage or Size', 'foundnxt'); ?></label>
                <select id="lead_stage" name="lead_stage">
                  <option value="Early Stage / Seed"><?php _e('Early Stage / Seed', 'foundnxt'); ?></option>
                  <option value="Growth / Series A+"><?php _e('Growth / Series A+', 'foundnxt'); ?></option>
                  <option value="Scaleup"><?php _e('Scaleup', 'foundnxt'); ?></option>
                  <option value="Enterprise / Established"><?php _e('Enterprise / Established', 'foundnxt'); ?></option>
                </select>
              </div>
            </div>

            <div class="form-group-v2">
              <label for="lead_help"><?php _e('What do you need help with?', 'foundnxt'); ?></label>
              <select id="lead_help" name="lead_help">
                <option value="Market Research Request" <?php selected($pre_help, 'Market Research Request'); ?>><?php _e('Market Research Request', 'foundnxt'); ?></option>
                <option value="Valuation Guidance" <?php selected($pre_help, 'Valuation Guidance'); ?>><?php _e('Valuation Guidance', 'foundnxt'); ?></option>
                <option value="Tech & AI Strategy" <?php selected($pre_help, 'Tech & AI Strategy'); ?>><?php _e('Tech & AI Strategy', 'foundnxt'); ?></option>
                <option value="Marketing Help" <?php selected($pre_help, 'Marketing Help'); ?>><?php _e('Marketing & Growth Help', 'foundnxt'); ?></option>
                <option value="Partnerships" <?php selected($pre_help, 'Partnerships'); ?>><?php _e('Partnerships & Sponsored Content', 'foundnxt'); ?></option>
                <option value="Other" <?php selected($pre_help, 'Other'); ?>><?php _e('Other Inquiry', 'foundnxt'); ?></option>
              </select>
            </div>

            <div class="form-group-v2">
              <label for="lead_message"><?php _e('Message *', 'foundnxt'); ?></label>
              <textarea id="lead_message" name="lead_message" rows="4" placeholder="<?php esc_attr_e('Describe your requirement, company background, or questions...', 'foundnxt'); ?>" required></textarea>
            </div>

            <div class="form-group-v2 form-checkbox-group-v2">
              <label class="checkbox-label-v2">
                <input type="checkbox" name="lead_consent" required checked>
                <span><?php _e('I consent to FoundNXT storing my details to respond to this enquiry.', 'foundnxt'); ?></span>
              </label>
            </div>

            <button type="submit" class="btn-primary form-submit-btn-v2">
              <?php _e('Submit Advisory Enquiry', 'foundnxt'); ?> →
            </button>
          </form>
        </div>

      </div>
    </div>

  </div>
</div>

<?php get_footer(); ?>
