<?php
/**
 * Template Name: Support
 * Template Post Type: page
 *
 * Dedicated Support & Help Center page for FoundNXT.
 * Assign this template to your /support/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_name   = get_bloginfo('name');
$site_url    = esc_url(home_url('/'));
$support_url = esc_url(get_permalink());
$contact_url = esc_url(home_url('/contact/'));

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'WebPage',
      '@id'         => $support_url . '#webpage',
      'url'         => $support_url,
      'name'        => 'Support & Help Center — FoundNXT',
      'description' => 'Get support for FoundNXT publications, newsletter subscriptions, custom advisory requests, and tech recommendations.',
      'inLanguage'  => 'en',
      'isPartOf'    => ['@id' => $site_url . '#website'],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Support', 'item' => $support_url],
      ],
    ],
  ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

$faqs = [
  [
    'q' => __('When is the Weekly Executive Brief delivered?', 'foundnxt'),
    'a' => __('Our newsletter is delivered every Monday morning at 7:00 AM EST. If you do not see it in your primary inbox, please check your updates or promotional folder and mark FoundNXT as a trusted sender.', 'foundnxt'),
  ],
  [
    'q' => __('What is the typical response time for custom advisory requests?', 'foundnxt'),
    'a' => __('Our senior strategy team reviews and responds to all advisory inquiries within 24–48 hours on business days.', 'foundnxt'),
  ],
  [
    'q' => __('Is my company data kept confidential during valuation and strategy audits?', 'foundnxt'),
    'a' => __('Yes, 100%. We operate under strict confidentiality protocols. Any cap table data, revenue metrics, or proprietary tech details shared with FoundNXT remain completely private.', 'foundnxt'),
  ],
  [
    'q' => __('How can I submit a software tool for inclusion in the Tech Stack Directory?', 'foundnxt'),
    'a' => __('Founders and software creators can submit open-source projects or enterprise SaaS tools via our contact form under the "Partnerships & Sponsored Content" topic.', 'foundnxt'),
  ],
  [
    'q' => __('How do I change my subscription email or unsubscribe?', 'foundnxt'),
    'a' => __('Every email sent by FoundNXT includes a one-click unsubscribe link in the footer. To update your email address, simply subscribe with your new address or reach out to our team.', 'foundnxt'),
  ],
];
?>

<div class="fnx-archive-page fnx-support-page" style="padding: 40px 0 80px;">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO HEADER -->
    <header class="support-hero text-center fnx-reveal" style="margin-bottom: 48px;">
      <span class="hp-sec-tag-pill hp-sec-tag-pill--violet">🤝 <?php _e('Help & Support Center', 'foundnxt'); ?></span>
      <h1 class="support-title" style="font-size: clamp(2.2rem, 4.2vw, 3.25rem); font-weight: 800; color: var(--fnx-ink); margin: 14px 0;">
        <?php _e('How Can We', 'foundnxt'); ?> <span class="text-gradient"><?php _e('Help You Today?', 'foundnxt'); ?></span>
      </h1>
      <p class="support-lead" style="color: var(--fnx-muted); font-size: 1.125rem; max-width: 680px; margin: 0 auto;">
        <?php _e('Browse common questions below, access subscriber resources, or get in touch directly with our support team.', 'foundnxt'); ?>
      </p>
    </header>

    <!-- SUPPORT CATEGORIES GRID -->
    <div class="support-categories-grid fnx-reveal" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 56px;">
      
      <div class="support-cat-card" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 18px; padding: 28px 22px; text-align: center;">
        <div class="support-icon" style="font-size: 2rem; margin-bottom: 12px;">✉️</div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--fnx-ink); margin-bottom: 8px;"><?php _e('Newsletter Support', 'foundnxt'); ?></h3>
        <p style="font-size: 0.875rem; color: var(--fnx-muted); margin: 0; line-height: 1.5;"><?php _e('Delivery updates, email preferences, and subscriber management.', 'foundnxt'); ?></p>
      </div>

      <div class="support-cat-card" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 18px; padding: 28px 22px; text-align: center;">
        <div class="support-icon" style="font-size: 2rem; margin-bottom: 12px;">📊</div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--fnx-ink); margin-bottom: 8px;"><?php _e('Advisory Enquiries', 'foundnxt'); ?></h3>
        <p style="font-size: 0.875rem; color: var(--fnx-muted); margin: 0; line-height: 1.5;"><?php _e('Market research requests, valuation audits, and tech strategy.', 'foundnxt'); ?></p>
      </div>

      <div class="support-cat-card" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 18px; padding: 28px 22px; text-align: center;">
        <div class="support-icon" style="font-size: 2rem; margin-bottom: 12px;">🛠️</div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--fnx-ink); margin-bottom: 8px;"><?php _e('Tech Directory', 'foundnxt'); ?></h3>
        <p style="font-size: 0.875rem; color: var(--fnx-muted); margin: 0; line-height: 1.5;"><?php _e('Submitting open-source software or recommending SaaS stack tools.', 'foundnxt'); ?></p>
      </div>

      <div class="support-cat-card" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 18px; padding: 28px 22px; text-align: center;">
        <div class="support-icon" style="font-size: 2rem; margin-bottom: 12px;">🤝</div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--fnx-ink); margin-bottom: 8px;"><?php _e('Partnerships', 'foundnxt'); ?></h3>
        <p style="font-size: 0.875rem; color: var(--fnx-muted); margin: 0; line-height: 1.5;"><?php _e('Sponsorship kits, press releases, and editorial opportunities.', 'foundnxt'); ?></p>
      </div>

    </div>

    <!-- FAQ SECTION -->
    <section class="support-faq-section fnx-reveal" style="max-width: 860px; margin: 0 auto 56px;">
      <div class="text-center" style="margin-bottom: 32px;">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">❓ <?php _e('Frequently Asked Questions', 'foundnxt'); ?></span>
        <h2 style="font-size: clamp(1.75rem, 3.5vw, 2.4rem); font-weight: 800; color: var(--fnx-ink); margin-top: 10px;">
          <?php _e('Common Questions & Answers', 'foundnxt'); ?>
        </h2>
      </div>

      <div class="faq-accordion-list" style="display: flex; flex-direction: column; gap: 16px;">
        <?php foreach ($faqs as $faq): ?>
          <details class="faq-item" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 14px; padding: 18px 22px; cursor: pointer;">
            <summary style="font-size: 1.0625rem; font-weight: 700; color: var(--fnx-ink); list-style: none; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
              <span><?php echo esc_html($faq['q']); ?></span>
              <span class="faq-arrow" style="color: #4f46e5; font-size: 1.2rem; transition: transform 0.2s;">+</span>
            </summary>
            <div class="faq-answer" style="margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--fnx-border, rgba(0,0,0,0.06)); font-size: 0.9375rem; color: var(--fnx-muted); line-height: 1.6;">
              <?php echo esc_html($faq['a']); ?>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- CONTACT SUPPORT CTA -->
    <div class="support-cta-box fnx-reveal" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-radius: 24px; padding: 48px 40px; color: #ffffff; text-align: center; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(15,23,42,0.3);">
      <h2 style="font-size: 1.85rem; font-weight: 800; color: #ffffff; margin-bottom: 12px;"><?php _e('Still Need Assistance?', 'foundnxt'); ?></h2>
      <p style="color: #cbd5e1; font-size: 1.0625rem; max-width: 580px; margin: 0 auto 28px; line-height: 1.6;"><?php _e('Our advisory and support team is here to help. Send us a message and we will respond within 24–48 hours.', 'foundnxt'); ?></p>
      <a href="<?php echo $contact_url; ?>" class="btn-primary" style="padding: 14px 32px; font-size: 1rem; font-weight: 700; border-radius: 12px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
        <?php _e('Contact Support Team', 'foundnxt'); ?> →
      </a>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const details = document.querySelectorAll('.faq-item');
  details.forEach(detail => {
    detail.addEventListener('toggle', function() {
      const arrow = this.querySelector('.faq-arrow');
      if (arrow) {
        arrow.textContent = this.open ? '−' : '+';
      }
    });
  });
});
</script>

<?php get_footer(); ?>

