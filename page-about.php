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
    <section class="about-hero text-center" style="margin-bottom: 48px;">
      <span class="hp-sec-tag"><?php _e('About FoundNXT', 'foundnxt'); ?></span>
      <h1 class="about-h1" style="font-size: clamp(2.2rem, 4vw, 3.4rem); margin-bottom: 16px;">
        <?php _e('Business, Markets & Technology, Explained.', 'foundnxt'); ?>
      </h1>
      <p class="about-lead" style="max-width: 780px; margin: 0 auto 28px;">
        <?php _e('FoundNXT is an independent business and technology publication helping founders and leaders understand markets, technology, and growth. Built for leaders who want to scale smarter without jargon walls.', 'foundnxt'); ?>
      </p>
    </section>

    <!-- MISSION & PILLARS -->
    <section class="hp-block" style="padding: 40px 0;">
      <div class="grid-2">
        <div class="hp-about-card-box fnx-reveal" style="background: var(--fnx-card); border: 1px solid var(--fnx-border); border-radius: var(--fnx-radius); padding: 32px;">
          <h3>🎯 <?php _e('Our Mission', 'foundnxt'); ?></h3>
          <p><?php _e('To demystify complex corporate finance, startup valuation, enterprise AI adoption, and macroeconomic shifts into practical, actionable intelligence for founders and executive leaders.', 'foundnxt'); ?></p>
        </div>

        <div class="hp-about-card-box fnx-reveal" style="background: var(--fnx-card); border: 1px solid var(--fnx-border); border-radius: var(--fnx-radius); padding: 32px;">
          <h3>💡 <?php _e('Our Editorial Standard', 'foundnxt'); ?></h3>
          <p><?php _e('Clear, confident, practical, and modern. We skip fluff, hype, and technical jargon to deliver real-world frameworks, mathematical models, and verifiable market data.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- COVERAGE AREAS (8 TOPICS) -->
    <section class="hp-block" style="padding: 40px 0;">
      <div class="hp-sec-header text-center fnx-reveal">
        <span class="hp-sec-tag"><?php _e('What We Cover', 'foundnxt'); ?></span>
        <h2><?php _e('Core Editorial', 'foundnxt'); ?> <em><?php _e('Pillars', 'foundnxt'); ?></em></h2>
      </div>

      <div class="grid-4" style="margin-top: 32px;">
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#4F46E5;">💼</span>
          <h4>Business & Strategy</h4>
          <p>SaaS monetization, unit economics, margin structures, and scaling operations.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#FF6B6B;">🚀</span>
          <h4>Startups & Funding</h4>
          <p>Pitch deck checklists, venture capital dynamics, and early-stage fundraising.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#14B8A6;">📈</span>
          <h4>Valuation & Finance</h4>
          <p>DCF modeling, 409A benchmarking, cap tables, and M&A valuation methods.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#F59E0B;">🌐</span>
          <h4>Markets & Economy</h4>
          <p>Interest rate ripple effects, sector growth forecasts, and macro trends.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#7C3AED;">🤖</span>
          <h4>Technology & AI</h4>
          <p>Enterprise AI cost savings, software architecture, and build vs buy guides.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#EC4899;">🎯</span>
          <h4>Marketing & Growth</h4>
          <p>0-to-100 customer acquisition, technical SEO clusters, and GTM strategy.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#0EA5E9;">🗺️</span>
          <h4>Global Business</h4>
          <p>Cross-border supply chains, tariff impacts, and SEA expansion playbooks.</p>
        </div>
        <div class="pillar-card fnx-reveal">
          <span class="pillar-icon" style="color:#10B981;">📰</span>
          <h4>News & Insights</h4>
          <p>Weekly executive intelligence briefs and timely business roundups.</p>
        </div>
      </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="hp-block" style="padding: 40px 0 80px;">
      <div class="hp-about-snippet-card text-center fnx-reveal">
        <h2><?php _e('Join Thousands of Founders & Leaders', 'foundnxt'); ?></h2>
        <p><?php _e('Subscribe to the free FoundNXT weekly intelligence brief to receive sharp, actionable insights directly in your inbox.', 'foundnxt'); ?></p>
        <a href="<?php echo esc_url(home_url('/#newsletter-section')); ?>" class="btn-primary"><?php _e('Subscribe to Free Weekly Brief', 'foundnxt'); ?> →</a>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
