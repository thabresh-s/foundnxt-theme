<?php
/**
 * Template Name: About Page
 * Template Post Type: page
 *
 * About page for FoundNXT aligned with new positioning.
 * Assign this template to your /about/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_url  = esc_url(home_url('/'));
$about_url = esc_url(get_permalink());

/* ── SCHEMA ── */
$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Organization',
            '@id'         => $site_url . '#organization',
            'name'        => 'FoundNXT',
            'url'         => $site_url,
            'description' => 'FoundNXT is an independent business and technology publication helping founders and leaders understand markets, technology, and growth.',
            'knowsAbout'  => [
                'Business Strategy', 'Startups & Funding', 'Valuation & Finance',
                'Markets & Economy', 'Technology & AI', 'Marketing & Growth', 'Global Business'
            ],
        ],
        [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => $site_url],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => $about_url],
            ],
        ],
    ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
?>

<div class="fnx-archive-page fnx-about-page">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO -->
    <section class="about-hero text-center fnx-reveal">
      <span class="hp-sec-tag"><?php _e('About FoundNXT', 'foundnxt'); ?></span>
      <h1 class="about-h1">
        <?php _e('Business, Markets & Technology, Explained.', 'foundnxt'); ?>
      </h1>
      <p class="about-lead">
        <?php _e('FoundNXT is an independent business and technology publication helping founders and leaders understand markets, technology, and growth. Built for leaders who want to scale smarter without jargon walls.', 'foundnxt'); ?>
      </p>
    </section>

    <!-- MISSION & STANDARDS -->
    <section class="about-mission-section">
      <div class="about-mission-grid">
        <div class="about-mission-card hp-about-card-box fnx-reveal">
          <h3>🎯 <?php _e('Our Mission', 'foundnxt'); ?></h3>
          <p><?php _e('To demystify complex corporate finance, startup valuation, enterprise AI adoption, and macroeconomic shifts into practical, actionable intelligence for founders and executive leaders.', 'foundnxt'); ?></p>
        </div>

        <div class="about-mission-card hp-about-card-box fnx-reveal">
          <h3>💡 <?php _e('Our Editorial Standard', 'foundnxt'); ?></h3>
          <p><?php _e('Clear, confident, practical, and modern. We skip fluff, hype, and technical jargon to deliver real-world frameworks, mathematical models, and verifiable market data.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- COVERAGE AREAS (8 TOPICS) -->
    <section class="about-pillars-section">
      <div class="hp-sec-header text-center fnx-reveal">
        <span class="hp-sec-tag"><?php _e('What We Cover', 'foundnxt'); ?></span>
        <h2><?php _e('Core Editorial', 'foundnxt'); ?> <em><?php _e('Pillars', 'foundnxt'); ?></em></h2>
      </div>

      <div class="about-pillars-grid">
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--cat-business, #4F46E5);">💼</span>
          <h4><?php _e('Business & Strategy', 'foundnxt'); ?></h4>
          <p><?php _e('SaaS monetization, unit economics, margin structures, and scaling operations.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--warm, #FF6B6B);">🚀</span>
          <h4><?php _e('Startups & Funding', 'foundnxt'); ?></h4>
          <p><?php _e('Pitch deck checklists, venture capital dynamics, and early-stage fundraising.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--accent, #14B8A6);">📈</span>
          <h4><?php _e('Valuation & Finance', 'foundnxt'); ?></h4>
          <p><?php _e('DCF modeling, 409A benchmarking, cap tables, and M&A valuation methods.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--cat-markets, #F59E0B);">🌐</span>
          <h4><?php _e('Markets & Economy', 'foundnxt'); ?></h4>
          <p><?php _e('Interest rate ripple effects, sector growth forecasts, and macro trends.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--cat-ai, #7C3AED);">🤖</span>
          <h4><?php _e('Technology & AI', 'foundnxt'); ?></h4>
          <p><?php _e('Enterprise AI cost savings, software architecture, and build vs buy guides.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--cat-careers, #EC4899);">🎯</span>
          <h4><?php _e('Marketing & Growth', 'foundnxt'); ?></h4>
          <p><?php _e('0-to-100 customer acquisition, technical SEO clusters, and GTM strategy.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: #0EA5E9;">🗺️</span>
          <h4><?php _e('Global Business', 'foundnxt'); ?></h4>
          <p><?php _e('Cross-border supply chains, tariff impacts, and SEA expansion playbooks.', 'foundnxt'); ?></p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color: var(--cat-news, #10B981);">📰</span>
          <h4><?php _e('News & Insights', 'foundnxt'); ?></h4>
          <p><?php _e('Weekly executive intelligence briefs and timely business roundups.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="about-cta-section">
      <div class="about-cta-card hp-about-snippet-card text-center fnx-reveal">
        <h2><?php _e('Join Thousands of Founders & Leaders', 'foundnxt'); ?></h2>
        <p><?php _e('Subscribe to the free FoundNXT weekly intelligence brief to receive sharp, actionable insights directly in your inbox.', 'foundnxt'); ?></p>
        <a href="<?php echo esc_url(home_url('/#newsletter-section')); ?>" class="btn-primary"><?php _e('Subscribe to Free Weekly Brief', 'foundnxt'); ?> →</a>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
