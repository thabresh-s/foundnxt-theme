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
        'id'      => 'market-research',
        'label'   => __('Market Research Request', 'foundnxt'),
        'title'   => __('Custom Market Research & Industry Intelligence', 'foundnxt'),
        'desc'    => __('In-depth market research, sector analysis, competitive benchmarking, and custom market opportunity sizing for founders and investment teams.', 'foundnxt'),
        'tag'     => __('Market Research', 'foundnxt'),
        'accent'  => '#4F46E5',
        'help_val'=> 'Market Research Request',
    ],
    [
        'id'      => 'valuation-guidance',
        'label'   => __('Valuation Guidance', 'foundnxt'),
        'title'   => __('Defensible Startup Valuation & Financial Modeling', 'foundnxt'),
        'desc'    => __('Independent financial modeling, cap table health checks, 409A advice, and pre-money valuation benchmarking before fundraising rounds.', 'foundnxt'),
        'tag'     => __('Valuation & Finance', 'foundnxt'),
        'accent'  => '#14B8A6',
        'help_val'=> 'Valuation Guidance',
    ],
    [
        'id'      => 'tech-ai-strategy',
        'label'   => __('Tech & AI Strategy', 'foundnxt'),
        'title'   => __('Enterprise AI Workflows & Software Architecture Audits', 'foundnxt'),
        'desc'    => __('Technical stack reviews, AI workflow automation roadmaps, infrastructure cost reduction, and build vs. buy software decision frameworks.', 'foundnxt'),
        'tag'     => __('Tech & AI', 'foundnxt'),
        'accent'  => '#7C3AED',
        'help_val'=> 'Tech & AI Strategy',
    ],
    [
        'id'      => 'marketing-growth',
        'label'   => __('Marketing & Growth Help', 'foundnxt'),
        'title'   => __('0-to-1 Customer Acquisition & Technical SEO', 'foundnxt'),
        'desc'    => __('Topical SEO cluster audits, product-led growth strategy, B2B cold outreach blueprints, and customer acquisition positioning.', 'foundnxt'),
        'tag'     => __('Marketing & Growth', 'foundnxt'),
        'accent'  => '#EC4899',
        'help_val'=> 'Marketing Help',
    ],
    [
        'id'      => 'partnerships-sponsored',
        'label'   => __('Partnerships & Sponsored Content', 'foundnxt'),
        'title'   => __('Editorial Partnerships & Executive Reach', 'foundnxt'),
        'desc'    => __('Connect with thousands of founders, venture investors, and technology leaders through dedicated newsletter sponsorships and editorial features.', 'foundnxt'),
        'tag'     => __('Partnerships', 'foundnxt'),
        'accent'  => '#F59E0B',
        'help_val'=> 'Partnerships',
    ],
];
?>

<div class="fnx-archive-page fnx-services-page">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO -->
    <section class="about-hero text-center" style="margin-bottom: 48px;">
      <span class="hp-sec-tag"><?php _e('Work With Us', 'foundnxt'); ?></span>
      <h1 class="about-h1" style="font-size: clamp(2.2rem, 4vw, 3.4rem); margin-bottom: 16px;">
        <?php _e('Founder Services & Business Advisory', 'foundnxt'); ?>
      </h1>
      <p class="about-lead" style="max-width: 720px; margin: 0 auto 28px;">
        <?php _e('Practical research, financial guidance, technology architecture, and growth playbooks for founders and leaders scaling modern businesses.', 'foundnxt'); ?>
      </p>
      <a href="<?php echo $contact_url; ?>" class="btn-primary"><?php _e('Book an Advisory Session', 'foundnxt'); ?> →</a>
    </section>

    <!-- SERVICES CARDS GRID -->
    <section class="hp-block" style="padding: 0 0 60px;">
      <div class="hp-services-grid-v2">
        <?php foreach ($services as $s): ?>
          <div class="hp-service-card-v2 fnx-reveal" style="--serv-accent: <?php echo esc_attr($s['accent']); ?>;">
            <div class="service-icon-wrap" style="color: <?php echo esc_attr($s['accent']); ?>; background: <?php echo esc_attr($s['accent']); ?>15;">
              ⚡
            </div>
            <span class="hp-feat-chip" style="background: <?php echo esc_attr($s['accent']); ?>18; color: <?php echo esc_attr($s['accent']); ?>; align-self: flex-start; margin-bottom: 12px;">
              <?php echo esc_html($s['tag']); ?>
            </span>
            <h3 class="service-card-h3"><?php echo esc_html($s['title']); ?></h3>
            <p class="service-card-p"><?php echo esc_html($s['desc']); ?></p>
            <a href="<?php echo add_query_arg('help', urlencode($s['help_val']), $contact_url); ?>" class="btn-outline service-enquire-btn" style="margin-top: auto;">
              <?php _e('Enquire About This Service', 'foundnxt'); ?> →
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- PROCESS SECTION -->
    <section class="hp-block hp-process-section" style="padding: 40px 0;">
      <div class="hp-sec-header text-center fnx-reveal">
        <span class="hp-sec-tag"><?php _e('Our Engagement Model', 'foundnxt'); ?></span>
        <h2><?php _e('How We', 'foundnxt'); ?> <em><?php _e('Work', 'foundnxt'); ?></em></h2>
      </div>

      <div class="grid-4" style="margin-top: 32px;">
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

    <!-- CTA BANNER -->
    <section class="hp-block" style="padding: 40px 0 80px;">
      <div class="hp-about-snippet-card text-center fnx-reveal">
        <h2><?php _e('Ready to Work Together?', 'foundnxt'); ?></h2>
        <p><?php _e('Tell us about your business goals or current bottlenecks — we reply within 24–48 hours.', 'foundnxt'); ?></p>
        <a href="<?php echo $contact_url; ?>" class="btn-primary"><?php _e('Get in Touch with Our Advisory Team', 'foundnxt'); ?> →</a>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
