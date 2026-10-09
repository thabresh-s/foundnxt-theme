<?php
/**
 * Template Name: Services Page
 * Template Post Type: page
 *
 * Dedicated Services & Advisory page for FoundNXT.
 * Assign this template to your /services/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_url     = esc_url(home_url('/'));
$services_url = esc_url(get_permalink());
$contact_url  = esc_url(home_url('/contact/'));

/* ── SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'Service',
      'serviceType' => 'Founder Advisory & Business Intelligence',
      'provider'    => [
        '@type' => 'Organization',
        'name'  => 'FoundNXT',
        'url'   => $site_url,
      ],
      'areaServed'  => 'Global',
      'description' => 'Advisory services for business leaders: Market Research Requests, Valuation Guidance, Tech & AI Strategy, Marketing Help, and Partnerships.',
      'url'         => $services_url,
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => $services_url],
      ],
    ],
  ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

/* ── 5 SPECIFIED SERVICES ── */
$services = [
  [
    'id'       => 'market-research',
    'icon'     => '🔍',
    'label'    => __('Market Research Request', 'foundnxt'),
    'title'    => __('Custom Market Research & Industry Intelligence', 'foundnxt'),
    'desc'     => __('In-depth market research, sector analysis, competitive benchmarking, and custom market opportunity sizing for founders and investment teams.', 'foundnxt'),
    'tag'      => __('Market Research', 'foundnxt'),
    'accent'   => '#4F46E5',
    'help_val' => 'Market Research Request',
    'perks'    => ['Competitor Benchmarking', 'TAM / SAM Market Sizing', 'Custom Industry Reports'],
  ],
  [
    'id'       => 'valuation-guidance',
    'icon'     => '📊',
    'label'    => __('Valuation Guidance', 'foundnxt'),
    'title'    => __('Defensible Startup Valuation & Financial Modeling', 'foundnxt'),
    'desc'     => __('Independent financial modeling, cap table health checks, 409A advice, and pre-money valuation benchmarking before fundraising rounds.', 'foundnxt'),
    'tag'      => __('Valuation & Finance', 'foundnxt'),
    'accent'   => '#14B8A6',
    'help_val' => 'Valuation Guidance',
    'perks'    => ['DCF & Multiples Valuation', 'Cap Table Health Check', 'Investor Deck Prep'],
  ],
  [
    'id'       => 'tech-ai-strategy',
    'icon'     => '⚡',
    'label'    => __('Tech & AI Strategy', 'foundnxt'),
    'title'    => __('Enterprise AI Workflows & Software Architecture Audits', 'foundnxt'),
    'desc'     => __('Technical stack reviews, AI workflow automation roadmaps, infrastructure cost reduction, and build vs. buy software decision frameworks.', 'foundnxt'),
    'tag'      => __('Tech & AI', 'foundnxt'),
    'accent'   => '#7C3AED',
    'help_val' => 'Tech & AI Strategy',
    'perks'    => ['Architecture Audits', 'AI Workflow Automation', 'Build vs Buy Analysis'],
  ],
  [
    'id'       => 'marketing-growth',
    'icon'     => '🚀',
    'label'    => __('Marketing & Growth Help', 'foundnxt'),
    'title'    => __('0-to-1 Customer Acquisition & Technical SEO', 'foundnxt'),
    'desc'     => __('Topical SEO cluster audits, product-led growth strategy, B2B cold outreach blueprints, and customer acquisition positioning.', 'foundnxt'),
    'tag'      => __('Marketing & Growth', 'foundnxt'),
    'accent'   => '#EC4899',
    'help_val' => 'Marketing Help',
    'perks'    => ['Topical SEO Audits', 'GTM Positioning', 'Customer Acquisition Playbooks'],
  ],
  [
    'id'       => 'partnerships-sponsored',
    'icon'     => '🤝',
    'label'    => __('Partnerships & Sponsored Content', 'foundnxt'),
    'title'    => __('Editorial Partnerships & Executive Reach', 'foundnxt'),
    'desc'     => __('Connect with thousands of founders, venture investors, and technology leaders through dedicated newsletter sponsorships and editorial features.', 'foundnxt'),
    'tag'      => __('Partnerships', 'foundnxt'),
    'accent'   => '#F59E0B',
    'help_val' => 'Partnerships',
    'perks'    => ['Executive Audience Reach', 'Newsletter Sponsorships', 'Co-Branded Research'],
  ],
];
?>

<div class="fnx-archive-page fnx-services-page" style="padding: 40px 0 80px;">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO HEADER V2 -->
    <header class="services-hero-v2 text-center fnx-reveal">
      <div class="services-hero-card">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">⚡ <?php _e('Founder Advisory & Research', 'foundnxt'); ?></span>
        <h1 class="services-hero-title">
          <?php _e('Founder Services &', 'foundnxt'); ?> <span class="text-gradient"><?php _e('Business Advisory', 'foundnxt'); ?></span>
        </h1>
        <p class="services-hero-lead">
          <?php _e('Practical market research, financial guidance, software architecture audits, and growth playbooks built specifically for startup founders and business leaders.', 'foundnxt'); ?>
        </p>

        <!-- QUICK JUMP PILLS -->
        <div class="services-quick-pills">
          <?php foreach ($services as $s): ?>
            <a href="#<?php echo esc_attr($s['id']); ?>" class="services-quick-pill">
              <span><?php echo esc_html($s['icon']); ?></span> <?php echo esc_html($s['label']); ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="services-hero-actions">
          <a href="<?php echo $contact_url; ?>" class="btn-primary services-hero-btn">
            <?php _e('Book an Advisory Session', 'foundnxt'); ?> →
          </a>
          <a href="#services-list" class="btn-outline services-hero-btn-alt">
            <?php _e('Explore All Services', 'foundnxt'); ?> ↓
          </a>
        </div>
      </div>
    </header>

    <!-- SERVICES CARDS GRID V2 -->
    <section class="hp-block services-grid-section" id="services-list" style="padding: 40px 0 60px;">
      <div class="hp-services-grid-v2">
        <?php foreach ($services as $s): ?>
          <article class="hp-service-card-v2 fnx-reveal" id="<?php echo esc_attr($s['id']); ?>" style="--serv-accent: <?php echo esc_attr($s['accent']); ?>;">
            <div class="service-card-header-v2">
              <div class="service-icon-wrap-v2" style="color: <?php echo esc_attr($s['accent']); ?>; background: <?php echo esc_attr($s['accent']); ?>18; border: 1px solid <?php echo esc_attr($s['accent']); ?>30;">
                <?php echo esc_html($s['icon']); ?>
              </div>
              <span class="hp-feat-chip-v2" style="background: <?php echo esc_attr($s['accent']); ?>15; color: <?php echo esc_attr($s['accent']); ?>; border: 1px solid <?php echo esc_attr($s['accent']); ?>30;">
                <?php echo esc_html($s['tag']); ?>
              </span>
            </div>

            <h3 class="service-card-h3"><?php echo esc_html($s['title']); ?></h3>
            <p class="service-card-p"><?php echo esc_html($s['desc']); ?></p>

            <ul class="service-perks-v2">
              <?php foreach ($s['perks'] as $perk): ?>
                <li><span class="service-check-icon" style="color: <?php echo esc_attr($s['accent']); ?>;">✓</span> <?php echo esc_html($perk); ?></li>
              <?php endforeach; ?>
            </ul>

            <div class="service-card-footer-v2">
              <a href="<?php echo esc_url(add_query_arg('help', urlencode($s['help_val']), $contact_url)); ?>" class="btn-service-action-v2" style="--btn-color: <?php echo esc_attr($s['accent']); ?>;">
                <span><?php _e('Enquire About This Service', 'foundnxt'); ?></span>
                <span class="btn-arrow">→</span>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- PROCESS SECTION V2 -->
    <section class="hp-block hp-process-section" style="padding: 40px 0;">
      <div class="hp-sec-header text-center fnx-reveal">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--violet">🤝 <?php _e('Our Engagement Model', 'foundnxt'); ?></span>
        <h2 style="font-size: clamp(1.85rem, 3.5vw, 2.5rem); font-weight: 800; color: var(--fnx-ink); margin-top: 12px;">
          <?php _e('How We Work With', 'foundnxt'); ?> <span class="text-gradient"><?php _e('Founders', 'foundnxt'); ?></span>
        </h2>
      </div>

      <div class="grid-4 services-process-grid" style="margin-top: 36px;">
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">01</div>
          <h4><?php _e('Discovery & Audit', 'foundnxt'); ?></h4>
          <p><?php _e('We diagnose your operational metrics, market position, and tech stack constraints.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">02</div>
          <h4><?php _e('Data-Driven Strategy', 'foundnxt'); ?></h4>
          <p><?php _e('We build a custom action plan with defensible benchmarks and clear ROI timelines.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">03</div>
          <h4><?php _e('Sprint Execution', 'foundnxt'); ?></h4>
          <p><?php _e('We execute in focused 4-to-8 week sprints with clear milestone deliverables.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">04</div>
          <h4><?php _e('Review & Scale', 'foundnxt'); ?></h4>
          <p><?php _e('We hand over SOPs, financial models, and frameworks to keep your team scaling.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- BOTTOM CTA BANNER V2 -->
    <section class="hp-block" style="padding: 40px 0 40px;">
      <div class="services-cta-banner-v2 text-center fnx-reveal">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">💬 <?php _e('Executive Advisory', 'foundnxt'); ?></span>
        <h2 class="services-cta-title"><?php _e('Ready to Work Together?', 'foundnxt'); ?></h2>
        <p class="services-cta-lead"><?php _e('Tell us about your business goals or current bottlenecks — our team replies within 24–48 hours.', 'foundnxt'); ?></p>
        <a href="<?php echo $contact_url; ?>" class="btn-primary services-cta-btn">
          <?php _e('Get in Touch with Our Advisory Team', 'foundnxt'); ?> →
        </a>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
