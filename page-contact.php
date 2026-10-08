<?php
/**
 * Template Name: Contact Page (SEO)
 * Template Post Type: page
 *
 * Custom SEO-optimised Contact page for FoundNXT.
 * Keeps Fluent Forms for submission, wraps it in rich markup.
 * Assign this template to your /contact/ page in WordPress.
 *
 * SEO outputs:
 *  - ContactPage schema with LocalBusiness + author
 *  - BreadcrumbList schema
 *  - Canonical, OG, Twitter meta
 *  - FAQ schema (common contact questions)
 *  - Rich HTML with semantic headings, address element
 *
 * @package FoundNXT
 */

get_header();

/* ── CONFIG — edit once ─────────────────────────────── */
$site_name    = get_bloginfo('name');
$site_url     = esc_url(home_url('/'));
$contact_url  = esc_url(get_permalink());
$logo_url     = esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'));
$author_name  = 'FoundNXT Editorial Team';
$email        = 'foundnxt.com@gmail.com';         // shown publicly
$location     = 'Chennai, Tamil Nadu, India';
$response_time = 'within 24–48 hours';

/* ── Fluent Form shortcode ID ───────────────────────── */
/* Go to Fluent Forms → your contact form → note the ID  */
$fluent_form_id = "6"; // ← change to your actual form ID

/* ── JSON-LD SCHEMA ─────────────────────────────────── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [

    /* ContactPage */
    [
      '@type'       => 'ContactPage',
      '@id'         => $contact_url . '#contactpage',
      'url'         => $contact_url,
      'name'        => 'Contact FoundNXT — Talk to FoundNXT Editorial Team',
      'description' => 'Reach out to FoundNXT for collaborations, article pitches, press releases, freelance consulting, or general feedback. Response within 24–48 hours.',
      'inLanguage'  => 'en-IN',
      'isPartOf'    => ['@id' => $site_url . '#website'],
      'breadcrumb'  => ['@id' => $contact_url . '#breadcrumb'],
      'publisher'   => [
        '@type' => 'Organization',
        'name'  => $site_name,
        'url'   => $site_url,
        'logo'  => ['@type' => 'ImageObject', 'url' => $logo_url],
      ],
    ],

    /* Person / LocalBusiness */
    [
      '@type'         => ['Person', 'LocalBusiness'],
      '@id'           => $site_url . '#thabresh-syed',
      'name'          => $author_name,
      'jobTitle'      => 'Author',
      'url'           => $site_url,
      'email'         => $email,
      'address'       => [
        '@type'            => 'PostalAddress',
        'addressLocality'  => 'Chennai',
        'addressRegion'    => 'Tamil Nadu',
        'addressCountry'   => 'IN',
      ],
      'areaServed'    => 'India',
      'knowsAbout'    => ['Startup Funding', 'Venture Capital', 'Business Valuation', 'Startups', 'Business Strategy'],
      'worksFor'      => ['@id' => $site_url . '#website'],
    ],

    /* WebSite */
    [
      '@type' => 'WebSite',
      '@id'   => $site_url . '#website',
      'name'  => $site_name,
      'url'   => $site_url,
    ],

    /* BreadcrumbList */
    [
      '@type'           => 'BreadcrumbList',
      '@id'             => $contact_url . '#breadcrumb',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact', 'item' => $contact_url],
      ],
    ],

    /* FAQPage — boosts rich results for contact queries */
    [
      '@type'      => 'FAQPage',
      '@id'        => $contact_url . '#faq',
      'mainEntity' => [
        [
          '@type'          => 'Question',
          'name'           => 'How can I contact FoundNXT?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => 'You can reach FoundNXT by filling the contact form on this page or emailing ' . $email . '. FoundNXT Editorial Team typically responds within 24–48 hours.',
          ],
        ],
        [
          '@type'          => 'Question',
          'name'           => 'Does FoundNXT accept guest posts or collaborations?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => 'Yes. FoundNXT considers guest articles, sponsored content, press releases, and business collaborations relevant to startups, funding, and business growth. Use the contact form to pitch your idea.',
          ],
        ],
        [
          '@type'          => 'Question',
          'name'           => 'Can I hire FoundNXT Editorial Team for freelance consulting?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => 'Yes. The FoundNXT team offers freelance services in startup consulting, infrastructure, cloud operations, valuation, and MVP development. Send project details via the contact form.',
          ],
        ],
        [
          '@type'          => 'Question',
          'name'           => 'How quickly does FoundNXT respond to messages?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => 'Most messages receive a reply within 24–48 hours on business days.',
          ],
        ],
      ],
    ],
  ],
];

echo '<script type="application/ld+json">'
   . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
   . '</script>' . "\n";
?>

<!-- ═══════════════════════════════════════════════════
     CONTACT PAGE MARKUP
══════════════════════════════════════════════════════ -->
<div class="fnx-contact-page">
  <div class="container">

    <!-- Breadcrumb -->
    <nav class="fnx-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'foundnxt'); ?>">
      <a href="<?php echo $site_url; ?>"><?php _e('Home', 'foundnxt'); ?></a>
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M4.5 2.5L8 6L4.5 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <span><?php _e('Contact', 'foundnxt'); ?></span>
    </nav>

    <!-- Hero -->
    <header class="contact-hero">
      <p class="contact-eyebrow">
        <span class="eyebrow-dot" aria-hidden="true"></span>
        <?php _e('Get In Touch', 'foundnxt'); ?>
      </p>
      <h1 class="contact-title"><?php _e('Tell Us Where<br>You\'re <em>Stuck.</em>', 'foundnxt'); ?></h1>
      <p class="contact-subtitle"><?php printf(
        __('We\'ll tell you honestly if we can help. Based in %s — responding %s on business days.', 'foundnxt'),
        '<strong>' . esc_html($location) . '</strong>',
        '<strong>' . esc_html($response_time) . '</strong>'
      ); ?></p>
    </header>

    <!-- Main grid: form + sidebar -->
    <div class="contact-grid">

      <!-- ── LEFT: FORM ── -->
      <div class="contact-form-col">
        <div class="contact-form-card">
          <div class="contact-form-card-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <h2><?php _e('Tell Us About Your Company', 'foundnxt'); ?></h2>
          </div>

          <?php if (shortcode_exists('fluentform')): ?>
          <!-- Fluent Forms shortcode — change ID if needed -->
          <div class="contact-fluent-wrap">
            <?php echo do_shortcode('[fluentform id="' . intval($fluent_form_id) . '"]'); ?>
          </div>
          <?php else: ?>
          <!-- Native lead-qualifying form fallback -->
          <form class="contact-lead-form" method="post" action="">
            <div class="contact-lead-row">
              <div class="contact-lead-field">
                <label for="lead-name"><?php _e('Name', 'foundnxt'); ?></label>
                <input type="text" id="lead-name" name="lead_name" required>
              </div>
              <div class="contact-lead-field">
                <label for="lead-email"><?php _e('Work Email', 'foundnxt'); ?></label>
                <input type="email" id="lead-email" name="lead_email" required>
              </div>
            </div>
            <div class="contact-lead-row">
              <div class="contact-lead-field">
                <label for="lead-company"><?php _e('Company', 'foundnxt'); ?></label>
                <input type="text" id="lead-company" name="lead_company">
              </div>
              <div class="contact-lead-field">
                <label for="lead-stage"><?php _e('Company Stage', 'foundnxt'); ?></label>
                <select id="lead-stage" name="lead_stage">
                  <option value=""><?php _e('Select…', 'foundnxt'); ?></option>
                  <option><?php _e('Pre-seed', 'foundnxt'); ?></option>
                  <option><?php _e('Seed', 'foundnxt'); ?></option>
                  <option><?php _e('Series A', 'foundnxt'); ?></option>
                  <option><?php _e('Series B+', 'foundnxt'); ?></option>
                  <option><?php _e('Established Business', 'foundnxt'); ?></option>
                </select>
              </div>
            </div>
            <div class="contact-lead-row">
              <div class="contact-lead-field contact-lead-field--full">
                <label for="lead-need"><?php _e('What do you need help with?', 'foundnxt'); ?></label>
                <select id="lead-need" name="lead_need">
                  <option value=""><?php _e('Select…', 'foundnxt'); ?></option>
                  <option><?php _e('Scale & Operations', 'foundnxt'); ?></option>
                  <option><?php _e('Valuation', 'foundnxt'); ?></option>
                  <option><?php _e('Funding Strategy', 'foundnxt'); ?></option>
                  <option><?php _e('Technology Adoption', 'foundnxt'); ?></option>
                  <option><?php _e('Marketing & Branding', 'foundnxt'); ?></option>
                  <option><?php _e('Not sure yet', 'foundnxt'); ?></option>
                </select>
              </div>
            </div>
            <div class="contact-lead-row">
              <div class="contact-lead-field">
                <label for="lead-timeline"><?php _e('Timeline', 'foundnxt'); ?></label>
                <select id="lead-timeline" name="lead_timeline">
                  <option value=""><?php _e('Select…', 'foundnxt'); ?></option>
                  <option><?php _e('Immediately', 'foundnxt'); ?></option>
                  <option><?php _e('Within 1 month', 'foundnxt'); ?></option>
                  <option><?php _e('1–3 months', 'foundnxt'); ?></option>
                  <option><?php _e('Just exploring', 'foundnxt'); ?></option>
                </select>
              </div>
              <div class="contact-lead-field">
                <label for="lead-budget"><?php _e('Budget Range', 'foundnxt'); ?></label>
                <select id="lead-budget" name="lead_budget">
                  <option value=""><?php _e('Select…', 'foundnxt'); ?></option>
                  <option><?php _e('Under $2,000', 'foundnxt'); ?></option>
                  <option><?php _e('$2,000–$10,000', 'foundnxt'); ?></option>
                  <option><?php _e('$10,000+', 'foundnxt'); ?></option>
                  <option><?php _e('Not sure yet', 'foundnxt'); ?></option>
                </select>
              </div>
            </div>
            <div class="contact-lead-row">
              <div class="contact-lead-field contact-lead-field--full">
                <label for="lead-message"><?php _e('Tell us where you\'re stuck', 'foundnxt'); ?></label>
                <textarea id="lead-message" name="lead_message" rows="4"></textarea>
              </div>
            </div>
            <button type="submit" class="btn-primary contact-lead-submit"><?php _e('Send Message', 'foundnxt'); ?></button>
          </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- ── RIGHT: SIDEBAR INFO ── -->
      <aside class="contact-info-col" aria-label="<?php esc_attr_e('Contact information', 'foundnxt'); ?>">

        <!-- About card -->
        <div class="contact-card contact-card--about">
          <div class="contact-avatar">
            <?php
            $avatar_id = get_theme_mod('fnx_author_avatar');
            if ($avatar_id) {
              echo wp_get_attachment_image($avatar_id, [64, 64], false, ['class' => 'contact-avatar-img', 'alt' => esc_attr($author_name)]);
            } else {
              echo get_avatar(get_option('admin_email'), 64, '', $author_name, ['class' => 'contact-avatar-img']);
            }
            ?>
            <span class="contact-avatar-badge" aria-label="<?php esc_attr_e('Online', 'foundnxt'); ?>"></span>
          </div>
          <div>
            <p class="contact-card-name"><?php echo esc_html($author_name); ?></p>
            <p class="contact-card-role"><?php _e('· Author · Consultant', 'foundnxt'); ?></p>
          </div>
        </div>

        <!-- Contact details — semantic <address> for schema -->
        <address class="contact-card contact-card--details">
          <h3 class="contact-card-heading"><?php _e('Contact Details', 'foundnxt'); ?></h3>

          <div class="contact-detail-row">
            <span class="contact-detail-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            <div>
              <span class="contact-detail-label"><?php _e('Email', 'foundnxt'); ?></span>
              <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-detail-value"><?php echo esc_html($email); ?></a>
            </div>
          </div>

          <div class="contact-detail-row">
            <span class="contact-detail-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            <div>
              <span class="contact-detail-label"><?php _e('Location', 'foundnxt'); ?></span>
              <span class="contact-detail-value"><?php echo esc_html($location); ?></span>
            </div>
          </div>

          <div class="contact-detail-row">
            <span class="contact-detail-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </span>
            <div>
              <span class="contact-detail-label"><?php _e('Response Time', 'foundnxt'); ?></span>
              <span class="contact-detail-value"><?php echo esc_html(ucfirst($response_time)); ?></span>
            </div>
          </div>
        </address>

        <!-- What we can help with -->
        <div class="contact-card contact-card--topics">
          <h3 class="contact-card-heading"><?php _e('What We Can Help With', 'foundnxt'); ?></h3>
          <ul class="contact-topic-list">
            <?php
            $topics = [
              ['icon' => '🏗️', 'label' => __('Scale & operations consulting', 'foundnxt')],
              ['icon' => '📈', 'label' => __('Startup & business valuation', 'foundnxt')],
              ['icon' => '🚀', 'label' => __('Funding strategy & pitch review', 'foundnxt')],
              ['icon' => '⚙️', 'label' => __('Technology adoption & ROI', 'foundnxt')],
              ['icon' => '🏷️', 'label' => __('Marketing & branding strategy', 'foundnxt')],
              ['icon' => '📰', 'label' => __('Press, guest posts & story tips', 'foundnxt')],
            ];
            foreach ($topics as $t): ?>
            <li class="contact-topic-item">
              <span class="contact-topic-icon" aria-hidden="true"><?php echo $t['icon']; ?></span>
              <span><?php echo esc_html($t['label']); ?></span>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Response time note -->
        <div class="contact-card contact-card--note">
          <svg class="contact-note-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <p><?php _e('All messages are read personally by our editorial team. No bots, no auto-replies beyond confirmation.', 'foundnxt'); ?></p>
        </div>

      </aside>
    </div><!-- .contact-grid -->

    <!-- ── FAQ SECTION (visible text for SEO) ── -->
    <section class="contact-faq" aria-labelledby="contact-faq-heading">
      <h2 id="contact-faq-heading" class="contact-faq-title"><?php _e('Frequently Asked Questions', 'foundnxt'); ?></h2>
      <div class="contact-faq-grid">

        <?php
        $faqs = [
          [
            'q' => __('How can I contact FoundNXT?', 'foundnxt'),
            'a' => sprintf(__('Fill the form above or email %s directly. Our team typically replies within 24–48 hours on business days.', 'foundnxt'), '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>'),
          ],
          [
            'q' => __('Do you accept guest posts or collaborations?', 'foundnxt'),
            'a' => __('Yes — guest articles, sponsored content, press coverage, and brand collaborations relevant to startups and funding are welcome. Pitch your idea via the form.', 'foundnxt'),
          ],
          [
            'q' => __('Can I hire you for a consulting engagement?', 'foundnxt'),
            'a' => __('Yes. We work on scale & operations, valuation, funding strategy, technology adoption, and marketing & branding. Share your company stage and what you need help with in the message.', 'foundnxt'),
          ],
          [
            'q' => __('How quickly will I get a reply?', 'foundnxt'),
            'a' => __('Most messages get a reply within 24–48 hours. Complex enquiries involving proposals or technical reviews may take a little longer.', 'foundnxt'),
          ],
        ];
        foreach ($faqs as $i => $faq): ?>
        <div class="contact-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
          <button class="contact-faq-q" aria-expanded="false" aria-controls="faq-a-<?php echo $i; ?>" itemprop="name">
            <?php echo esc_html($faq['q']); ?>
            <svg class="faq-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="contact-faq-a" id="faq-a-<?php echo $i; ?>" hidden itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            <div itemprop="text"><?php echo wp_kses_post($faq['a']); ?></div>
          </div>
        </div>
        <?php endforeach; ?>

      </div>
    </section>

  </div><!-- .container -->
</div><!-- .fnx-contact-page -->

<script>
/* FAQ accordion — no jQuery needed */
document.querySelectorAll('.contact-faq-q').forEach(btn => {
  btn.addEventListener('click', function() {
    const expanded = this.getAttribute('aria-expanded') === 'true';
    // Close all
    document.querySelectorAll('.contact-faq-q').forEach(b => {
      b.setAttribute('aria-expanded', 'false');
      const ans = document.getElementById(b.getAttribute('aria-controls'));
      if (ans) ans.hidden = true;
    });
    // Open current if it was closed
    if (!expanded) {
      this.setAttribute('aria-expanded', 'true');
      const ans = document.getElementById(this.getAttribute('aria-controls'));
      if (ans) ans.hidden = false;
    }
  });
});
</script>

<?php get_footer(); ?>
