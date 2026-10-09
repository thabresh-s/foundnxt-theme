<?php
/**
 * Template Name: Disclaimer
 * Template Post Type: page
 *
 * Dedicated Disclaimer & Legal Notice page for FoundNXT.
 * Assign this template to your /disclaimer/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_name      = get_bloginfo('name');
$site_url       = esc_url(home_url('/'));
$disclaimer_url = esc_url(get_permalink());
$contact_url    = esc_url(home_url('/contact/'));

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'WebPage',
      '@id'         => $disclaimer_url . '#webpage',
      'url'         => $disclaimer_url,
      'name'        => 'Disclaimer — FoundNXT',
      'description' => 'Read FoundNXT Editorial, Financial, and Legal Disclaimer regarding market research, valuation guides, and software recommendations.',
      'inLanguage'  => 'en',
      'isPartOf'    => ['@id' => $site_url . '#website'],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',       'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Disclaimer', 'item' => $disclaimer_url],
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
      <span class="hp-sec-tag-pill hp-sec-tag-pill--amber">⚖️ <?php _e('Legal Notice & Editorial Policy', 'foundnxt'); ?></span>
      <h1 class="legal-title" style="font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 800; color: var(--fnx-ink); margin: 14px 0;">
        <?php _e('Disclaimer', 'foundnxt'); ?>
      </h1>
      <p class="legal-subtitle" style="color: var(--fnx-muted); font-size: 0.9375rem;">
        <?php _e('Last Updated:', 'foundnxt'); ?> <strong><?php echo get_the_modified_date('F j, Y'); ?></strong>
      </p>
    </header>

    <!-- CONTENT CARD -->
    <article class="legal-doc-card fnx-reveal" style="background: var(--fnx-surface); border: 1px solid var(--fnx-border, rgba(0,0,0,0.08)); border-radius: 24px; padding: clamp(28px, 4vw, 48px); box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
      
      <div class="legal-intro-box" style="background: rgba(245,158,11,0.05); border-left: 4px solid #f59e0b; padding: 18px 22px; border-radius: 0 12px 12px 0; margin-bottom: 36px;">
        <p style="margin: 0; font-size: 0.96875rem; color: var(--fnx-ink); line-height: 1.6;">
          <?php _e('The information provided on FoundNXT (foundnxt.com) is for general educational, business intelligence, and informational purposes only. By accessing or using this website, you agree to the terms of this Disclaimer.', 'foundnxt'); ?>
        </p>
      </div>

      <div class="legal-section">
        <h2>1. No Professional Financial, Investment, or Legal Advice</h2>
        <p>Content published on FoundNXT — including articles, valuation frameworks, SaaS benchmarks, and tech strategy guides — does not constitute formal financial, investment, accounting, tax, or legal advice. Readers should consult qualified certified professionals before making corporate financial, fundraising, or investment decisions.</p>
      </div>

      <div class="legal-section">
        <h2>2. Valuation & Benchmark Tools Disclaimer</h2>
        <p>Valuation formulas, ARR multiples, and market sizing models referenced across FoundNXT are estimates derived from historical industry benchmarks and third-party data sources. Actual company valuations depend on market conditions, cap table health, term sheet mechanics, and formal independent audit valuations.</p>
      </div>

      <div class="legal-section">
        <h2>3. Accuracy & Editorial Independence</h2>
        <p>While we make every reasonable effort to ensure information accuracy, FoundNXT makes no warranties, express or implied, regarding the completeness, reliability, or accuracy of published materials. Business markets, software pricing, and tech APIs evolve rapidly; information may become outdated over time.</p>
      </div>

      <div class="legal-section">
        <h2>4. Software & Tech Directory Recommendations</h2>
        <p>Software tools, open-source repositories, and SaaS platforms listed in our Business & Tech Recommendations directory are selected independently based on utility and user reputation. Users are responsible for evaluating software security, licensing compliance, and data privacy before deploying third-party tools in production environments.</p>
      </div>

      <div class="legal-section">
        <h2>5. External Links & Affiliate Disclosure</h2>
        <p>FoundNXT may contain links to external third-party websites. We have no control over the content, privacy policies, or security of external sites and assume no responsibility for them. Certain software links may be sponsored or affiliate links, which will be clearly indicated in accordance with FTC guidelines.</p>
      </div>

      <div class="legal-section">
        <h2>6. Limitation of Liability</h2>
        <p>In no event shall FoundNXT, its authors, or advisory team be liable for any direct, indirect, incidental, or consequential damages arising from the use of, or inability to use, the information provided on this platform.</p>
      </div>

      <div class="legal-section">
        <h2>7. Contact & Inquiries</h2>
        <p>If you have questions regarding this Disclaimer or wish to submit an editorial inquiry, please visit our <a href="<?php echo $contact_url; ?>">Contact Page</a>.</p>
      </div>

    </article>

  </div>
</div>

<?php get_footer(); ?>
