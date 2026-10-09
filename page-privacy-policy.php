<?php
/**
 * Template Name: Privacy Policy
 * Template Post Type: page
 *
 * Dedicated Privacy Policy page for FoundNXT.
 * Assign this template to your /privacy-policy/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_name   = get_bloginfo('name');
$site_url    = esc_url(home_url('/'));
$privacy_url = esc_url(get_permalink());
$contact_url = esc_url(home_url('/contact/'));

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'WebPage',
      '@id'         => $privacy_url . '#webpage',
      'url'         => $privacy_url,
      'name'        => 'Privacy Policy — FoundNXT',
      'description' => 'Read FoundNXT Privacy Policy to learn how we collect, protect, and handle your data when using our publication and advisory services.',
      'inLanguage'  => 'en',
      'isPartOf'    => ['@id' => $site_url . '#website'],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',           'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Privacy Policy', 'item' => $privacy_url],
      ],
    ],
  ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
?>

<div class="fnx-archive-page fnx-legal-page" style="padding: 40px 0 80px;">
  <div class="container container-narrow">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO HEADER -->
    <header class="legal-hero text-center fnx-reveal" style="margin-bottom: 40px;">
      <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">🔒 <?php _e('Legal & Data Protection', 'foundnxt'); ?></span>
      <h1 class="legal-title" style="font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 800; color: var(--fnx-ink); margin: 14px 0;">
        <?php _e('Privacy Policy', 'foundnxt'); ?>
      </h1>
      <p class="legal-subtitle" style="color: var(--fnx-muted); font-size: 0.9375rem;">
        <?php _e('Last Updated:', 'foundnxt'); ?> <strong><?php echo get_the_modified_date('F j, Y'); ?></strong>
      </p>
    </header>

    <!-- CONTENT CARD -->
    <article class="legal-doc-card fnx-reveal" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 24px; padding: clamp(28px, 4vw, 48px); box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
      
      <div class="legal-intro-box" style="background: rgba(79,70,229,0.04); border-left: 4px solid #4f46e5; padding: 18px 22px; border-radius: 0 12px 12px 0; margin-bottom: 36px;">
        <p style="margin: 0; font-size: 0.96875rem; color: var(--fnx-ink); line-height: 1.6;">
          <?php _e('At FoundNXT, we respect your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website, subscribe to our publications, or engage our advisory services.', 'foundnxt'); ?>
        </p>
      </div>

      <div class="legal-section">
        <h2>1. Information We Collect</h2>
        <p>We collect information directly from you when you interact with our platform, as well as automatically through website technologies:</p>
        <ul>
          <li><strong>Voluntary Contact Information:</strong> Name, work email address, phone/mobile number, company name, website URL, and inquiry details provided via contact forms or newsletter subscriptions.</li>
          <li><strong>Advisory & Service Data:</strong> Information shared during custom market research requests, tech stack evaluations, or valuation inquiries.</li>
          <li><strong>Automated Device & Usage Data:</strong> IP address, browser type, operating system, referring URLs, pages visited, and interaction timestamps collected via cookies and privacy-first analytics.</li>
        </ul>
      </div>

      <div class="legal-section">
        <h2>2. How We Use Your Information</h2>
        <p>FoundNXT processes your information solely for legitimate business purposes, including:</p>
        <ul>
          <li>Delivering requested services, custom market research, and advisory responses.</li>
          <li>Sending our Weekly Executive Brief newsletter to confirmed subscribers.</li>
          <li>Improving website performance, content relevance, and technical security.</li>
          <li>Complying with legal obligations and preventing spam or fraudulent activities.</li>
        </ul>
      </div>

      <div class="legal-section">
        <h2>3. Data Sharing & Third-Party Services</h2>
        <p><strong>We do not sell, rent, or trade your personal information to third parties.</strong> We only share data with trusted service providers essential for platform operations:</p>
        <ul>
          <li><strong>Infrastructure Providers:</strong> Secure web hosting, database providers, and SSL encryption services.</li>
          <li><strong>Email Distribution Platforms:</strong> Trusted transactional and newsletter delivery systems operating under strict data processing agreements.</li>
          <li><strong>Legal Requirements:</strong> Disclosures required by law, subpoena, or legal process to protect our rights and user safety.</li>
        </ul>
      </div>

      <div class="legal-section">
        <h2>4. Cookies & Tracking Technologies</h2>
        <p>FoundNXT uses essential cookies to remember user preferences (such as light/dark theme toggles) and privacy-friendly analytics tools. You can control cookie settings through your browser preferences or our cookie consent banner.</p>
      </div>

      <div class="legal-section">
        <h2>5. Data Security & Retention</h2>
        <p>We implement robust technical and organizational security measures, including 256-bit SSL encryption, restricted administrative access, and regular security audits. Personal data is retained only as long as necessary to fulfill the purposes outlined in this policy.</p>
      </div>

      <div class="legal-section">
        <h2>6. Your Rights & Data Choices</h2>
        <p>Depending on your location (including rights under GDPR and CCPA), you have the right to:</p>
        <ul>
          <li>Access, review, or request a copy of your personal data held by FoundNXT.</li>
          <li>Request correction of inaccurate or incomplete information.</li>
          <li>Request deletion of your personal data ("Right to be Forgotten").</li>
          <li>Unsubscribe from newsletter communications at any time via the one-click link at the footer of every email.</li>
        </ul>
      </div>

      <div class="legal-section">
        <h2>7. Contact Us</h2>
        <p>If you have any questions, concerns, or data protection requests regarding this Privacy Policy, please contact our team via our <a href="<?php echo $contact_url; ?>">Contact Page</a> or write to us at support@foundnxt.com.</p>
      </div>

    </article>

  </div>
</div>

<?php get_footer(); ?>
