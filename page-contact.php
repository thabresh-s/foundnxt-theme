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
$pre_help    = isset($_GET['help']) ? sanitize_text_field(wp_unslash($_GET['help'])) : 'Business Consulting';

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'ContactPage',
      '@id'         => $contact_url . '#contactpage',
      'url'         => $contact_url,
      'name'        => 'Contact FoundNXT — Business & Advisory Enquiries',
      'description' => 'Get in touch with the FoundNXT advisory team for Business Consulting and Tech Consulting.',
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

<div class="fnx-contact-page" style="padding: 50px 0 90px;">
  <div class="container">

    <?php if (function_exists('fnx_breadcrumbs')) { fnx_breadcrumbs(); } ?>

    <div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 56px; align-items: start; margin-top: 24px;">
      
      <!-- Left Column: Information, Email & Social Links -->
      <div class="contact-info-col">
        <span class="hp-hero-badge" style="background: rgba(124, 58, 237, 0.15); color: #A78BFA; border-color: rgba(124, 58, 237, 0.3);">
          💬 <?php _e('Get in Touch', 'foundnxt'); ?>
        </span>
        <h1 style="font-size: clamp(2.2rem, 4.5vw, 3.4rem); font-weight: 800; line-height: 1.15; margin: 16px 0 20px; color: var(--text);">
          <?php _e('Scaling a business?', 'foundnxt'); ?> <span class="text-highlight"><?php _e('Let’s talk.', 'foundnxt'); ?></span>
        </h1>
        <p style="font-size: 1.125rem; color: var(--muted); line-height: 1.6; margin-bottom: 36px;">
          <?php _e('Whether you need strategic advisory on unit economics, tech guidance on AI adoption, or editorial collaboration, our team replies within 24 business hours.', 'foundnxt'); ?>
        </p>

        <!-- Direct Contact Details -->
        <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 36px;">
          <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(124, 58, 237, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--accent-violet);">
              ✉️
            </div>
            <div>
              <span style="font-size: 0.8125rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; display: block;"><?php _e('Direct Email', 'foundnxt'); ?></span>
              <a href="mailto:hello@foundnxt.com" style="font-size: 1.0625rem; font-weight: 700; color: var(--text);">hello@foundnxt.com</a>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 107, 107, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--accent-coral);">
              ⚡
            </div>
            <div>
              <span style="font-size: 0.8125rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; display: block;"><?php _e('Response Time', 'foundnxt'); ?></span>
              <span style="font-size: 1rem; font-weight: 600; color: var(--text);"><?php _e('Within 24 business hours', 'foundnxt'); ?></span>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(20, 184, 166, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--accent-teal);">
              🔒
            </div>
            <div>
              <span style="font-size: 0.8125rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; display: block;"><?php _e('Confidentiality', 'foundnxt'); ?></span>
              <span style="font-size: 1rem; font-weight: 600; color: var(--text);"><?php _e('Mutual NDA executed upon request', 'foundnxt'); ?></span>
            </div>
          </div>
        </div>

        <!-- Social Profiles (Instagram First) -->
        <div>
          <span style="font-size: 0.8125rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text); display: block; margin-bottom: 12px;">
            <?php _e('Follow & Connect', 'foundnxt'); ?>
          </span>
          <div style="display: flex; gap: 14px; align-items: center;">
            <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.9375rem; font-weight: 700; color: #E1306C;">
              <span>Instagram @foundnxt</span> &rarr;
            </a>
            <span style="color: var(--border);">&bull;</span>
            <a href="https://x.com/foundnxt" target="_blank" rel="noopener noreferrer" style="font-size: 0.9375rem; font-weight: 700; color: var(--text);">
              <span>X @foundnxt</span> &rarr;
            </a>
            <span style="color: var(--border);">&bull;</span>
            <a href="https://linkedin.com/company/foundnxt" target="_blank" rel="noopener noreferrer" style="font-size: 0.9375rem; font-weight: 700; color: #0A66C2;">
              <span>LinkedIn</span> &rarr;
            </a>
          </div>
        </div>

      </div>

      <!-- Right Column: Short Lead Form Card -->
      <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: 0 16px 40px -10px rgba(0,0,0,0.15);">
        
        <?php if (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'success'): ?>
          <div style="padding: 16px 20px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10B981; font-weight: 700; margin-bottom: 24px;">
            ✓ <?php _e('Thank you! Your message has been received. Our advisory team will respond within 24 business hours.', 'foundnxt'); ?>
          </div>
        <?php elseif (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'error'): ?>
          <div style="padding: 16px 20px; border-radius: 12px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444; font-weight: 700; margin-bottom: 24px;">
            ✕ <?php _e('There was an issue submitting your request. Please check required fields and try again.', 'foundnxt'); ?>
          </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: flex; flex-direction: column; gap: 20px;">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Contact Form Lead">
          <input type="hidden" name="target_sheet" value="contact">
          <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" aria-hidden="true" autocomplete="off">

          <!-- Name -->
          <div>
            <label for="lead_name" style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--text); margin-bottom: 6px;">
              <?php _e('Your Name *', 'foundnxt'); ?>
            </label>
            <input type="text" id="lead_name" name="lead_name" placeholder="<?php esc_attr_e('Jordan Lee', 'foundnxt'); ?>" required style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-size: 0.9375rem;">
          </div>

          <!-- Email -->
          <div>
            <label for="lead_email" style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--text); margin-bottom: 6px;">
              <?php _e('Work Email *', 'foundnxt'); ?>
            </label>
            <input type="email" id="lead_email" name="lead_email" placeholder="<?php esc_attr_e('jordan@company.com', 'foundnxt'); ?>" required style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-size: 0.9375rem;">
          </div>

          <!-- Company -->
          <div>
            <label for="lead_company" style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--text); margin-bottom: 6px;">
              <?php _e('Company / Website (Optional)', 'foundnxt'); ?>
            </label>
            <input type="text" id="lead_company" name="lead_company" placeholder="<?php esc_attr_e('company.com', 'foundnxt'); ?>" style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-size: 0.9375rem;">
          </div>

          <!-- What you need -->
          <div>
            <label for="lead_help" style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--text); margin-bottom: 6px;">
              <?php _e('What do you need help with? *', 'foundnxt'); ?>
            </label>
            <select id="lead_help" name="lead_help" required style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-size: 0.9375rem;">
              <option value="Business Consulting" <?php selected($pre_help, 'Business Consulting'); ?>><?php _e('Business Consulting (Strategy, Unit Economics, Scaling)', 'foundnxt'); ?></option>
              <option value="Tech Consulting" <?php selected($pre_help, 'Tech Consulting'); selected($pre_help, 'Tech & AI Strategy'); ?>><?php _e('Tech Consulting (AI Adoption, Tech Stack, Infrastructure)', 'foundnxt'); ?></option>
              <option value="Editorial & Sponsorship"><?php _e('Editorial Inquiry or Partnership', 'foundnxt'); ?></option>
              <option value="Other"><?php _e('General Inquiry', 'foundnxt'); ?></option>
            </select>
          </div>

          <!-- Message -->
          <div>
            <label for="lead_message" style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--text); margin-bottom: 6px;">
              <?php _e('Message *', 'foundnxt'); ?>
            </label>
            <textarea id="lead_message" name="lead_message" rows="4" placeholder="<?php esc_attr_e('Briefly describe your company, current challenge, or questions…', 'foundnxt'); ?>" required style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-size: 0.9375rem; resize: vertical;"></textarea>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn-hero-primary" style="width: 100%; justify-content: center; padding: 14px 24px; font-size: 1rem; cursor: pointer; border: none; margin-top: 8px;">
            <span><?php _e('Submit Inquiry', 'foundnxt'); ?></span> &rarr;
          </button>
        </form>

      </div>

    </div>

  </div>
</div>

<?php get_footer(); ?>
