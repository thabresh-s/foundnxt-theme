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
      'serviceType' => 'Advisory & Growth Services',
      'provider'    => [
        '@type' => 'Organization',
        'name'  => 'FoundNXT',
        'url'   => $site_url,
      ],
      'areaServed'  => 'Global',
      'description' => 'Practical help to scale your company in the AI era, from technology to brand to operations: Tech & AI Strategy, Brand Promotion, and Business Consulting.',
      'url'         => $services_url,
      'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name'  => 'FoundNXT Services',
        'itemListElement' => [
          ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Tech & AI Strategy']],
          ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Brand Promotion']],
          ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Business Consulting']],
        ],
      ],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',        'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Our Services', 'item' => $services_url],
      ],
    ],
  ],
];
if (!function_exists('fnx_has_active_seo_plugin') || !fnx_has_active_seo_plugin()) { echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n"; }

/* ── 3 CORE SERVICES SPECIFICATION ── */
$services = [
  [
    'id'        => 'tech-ai-strategy',
    'num'       => '01',
    'icon'      => '⚡',
    'badge'     => __('Technology & AI', 'foundnxt'),
    'title'     => __('Tech & AI Strategy', 'foundnxt'),
    'tagline'   => __('Build smarter, spend less, move faster.', 'foundnxt'),
    'lead'      => __('We help businesses adopt the right technology without the bloat. No hype, just tools and systems that fit your stage and budget.', 'foundnxt'),
    'accent'    => '#7C3AED',
    'accent_bg' => 'rgba(124, 58, 237, 0.08)',
    'help_val'  => 'Tech & AI Strategy',
    'offerings' => [
      [
        'title' => __('Open Source', 'foundnxt'),
        'desc'  => __('Replace expensive licensed software with proven open-source alternatives. We help you choose, deploy, and maintain them, so you keep control of your stack and your data.', 'foundnxt'),
        'icon'  => '🔓',
      ],
      [
        'title' => __('Integration', 'foundnxt'),
        'desc'  => __('Your tools should talk to each other. We connect your CRM, website, payments, communication, and internal systems into one smooth workflow, with no more copy-pasting between apps.', 'foundnxt'),
        'icon'  => '🔄',
      ],
      [
        'title' => __('AI Tools & Automation', 'foundnxt'),
        'desc'  => __('From AI assistants to automated reporting, lead follow-ups, and content workflows, we identify where AI saves you the most time and put it to work.', 'foundnxt'),
        'icon'  => '🤖',
      ],
      [
        'title' => __('Cost-Efficient Solutions', 'foundnxt'),
        'desc'  => __('We audit your current tech spend and find leaner options: right-sized tools, smarter vendors, and fewer subscriptions you don’t use.', 'foundnxt'),
        'icon'  => '💰',
      ],
      [
        'title' => __('Consulting', 'foundnxt'),
        'desc'  => __('Not sure where to start? Work with us one-on-one to build a clear, practical technology and AI roadmap for your business.', 'foundnxt'),
        'icon'  => '🧭',
      ],
    ],
    'best_for'  => __('startups, SMEs, and growing teams that want enterprise-level capability on a sensible budget.', 'foundnxt'),
  ],
  [
    'id'        => 'brand-promotion',
    'num'       => '02',
    'icon'      => '📣',
    'badge'     => __('Audience & Growth', 'foundnxt'),
    'title'     => __('Brand Promotion', 'foundnxt'),
    'tagline'   => __('Get your story in front of the right audience.', 'foundnxt'),
    'lead'      => __('FoundNXT reaches founders, operators, and business readers interested in markets, startups, and technology. We help you tap into that attention.', 'foundnxt'),
    'accent'    => '#EC4899',
    'accent_bg' => 'rgba(236, 72, 153, 0.08)',
    'help_val'  => 'Brand Promotion',
    'offerings' => [
      [
        'title' => __('Company Promotion Posts', 'foundnxt'),
        'desc'  => __('Well-researched, professionally written features that tell your company’s story, covering your product, milestones, and vision, published to our audience.', 'foundnxt'),
        'icon'  => '📰',
      ],
      [
        'title' => __('Guest Posts', 'foundnxt'),
        'desc'  => __('Share your expertise with our readers. We publish quality guest articles on business, tech, AI, and startups, which build your authority and earn you visibility and backlinks.', 'foundnxt'),
        'icon'  => '✍️',
      ],
      [
        'title' => __('Digital Marketing: Lead Generation', 'foundnxt'),
        'desc'  => __('Targeted campaigns and funnels that bring in qualified prospects, not just clicks.', 'foundnxt'),
        'icon'  => '🎯',
      ],
      [
        'title' => __('Digital Marketing: Affiliate Marketing', 'foundnxt'),
        'desc'  => __('Performance-based partnerships that grow your reach, where you pay for results.', 'foundnxt'),
        'icon'  => '🤝',
      ],
    ],
    'best_for'  => __('startups, SaaS and tech companies, consultants, and brands looking for credible exposure and measurable growth.', 'foundnxt'),
  ],
  [
    'id'        => 'business-consulting',
    'num'       => '03',
    'icon'      => '📈',
    'badge'     => __('Operations & Scaling', 'foundnxt'),
    'title'     => __('Business Consulting', 'foundnxt'),
    'tagline'   => __('Run your business like a system, not a scramble.', 'foundnxt'),
    'lead'      => __('Growth breaks messy operations. We help you build structure that scales.', 'foundnxt'),
    'accent'    => '#14B8A6',
    'accent_bg' => 'rgba(20, 184, 166, 0.08)',
    'help_val'  => 'Business Consulting',
    'offerings' => [
      [
        'title' => __('Operations Management & Automation', 'foundnxt'),
        'desc'  => __('We review how work actually flows in your business, remove bottlenecks, and automate repetitive tasks, so your team spends time on high-value work.', 'foundnxt'),
        'icon'  => '⚙️',
      ],
      [
        'title' => __('SOPs (Standard Operating Procedures)', 'foundnxt'),
        'desc'  => __('Clear, documented processes for every key function: onboarding, sales, support, delivery, and more. Your business runs consistently, new hires ramp up faster, and you stop being the single point of failure.', 'foundnxt'),
        'icon'  => '📋',
      ],
    ],
    'best_for'  => __('founders and teams who are growing fast and need processes that hold up.', 'foundnxt'),
  ],
];
?>

<div class="fnx-archive-page fnx-services-page" style="padding: 40px 0 80px;">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO HEADER -->
    <header class="services-hero-v2 text-center fnx-reveal">
      <div class="services-hero-card">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">⚡ <?php _e('Our Services', 'foundnxt'); ?></span>
        <h1 class="services-hero-title">
          <?php _e('Practical Help to Scale Your Company', 'foundnxt'); ?>
        </h1>
        <p class="services-hero-lead">
          <?php _e('Practical help to scale your company in the AI era, from technology to brand to operations.', 'foundnxt'); ?>
        </p>

        <!-- QUICK JUMP PILLS -->
        <div class="services-quick-pills">
          <?php foreach ($services as $s): ?>
            <a href="#<?php echo esc_attr($s['id']); ?>" class="services-quick-pill">
              <span><?php echo esc_html($s['icon']); ?></span> <?php echo esc_html($s['title']); ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="services-hero-actions">
          <a href="<?php echo $contact_url; ?>" class="btn-primary services-hero-btn">
            <?php _e('Get in Touch', 'foundnxt'); ?> →
          </a>
          <a href="<?php echo esc_url(add_query_arg('help', 'consultation', $contact_url)); ?>" class="btn-outline services-hero-btn-alt">
            <?php _e('Book a Free Consultation', 'foundnxt'); ?>
          </a>
        </div>
      </div>
    </header>

    <!-- DETAILED SERVICES SHOWCASE -->
    <section class="hp-block services-showcase-section" id="services-list" style="padding: 40px 0 60px;">
      <div class="services-showcase-stack">
        <?php foreach ($services as $s): ?>
          <article class="service-showcase-card fnx-reveal" id="<?php echo esc_attr($s['id']); ?>" style="--serv-accent: <?php echo esc_attr($s['accent']); ?>;">
            
            <!-- Service Header -->
            <div class="service-showcase-header">
              <div class="service-showcase-meta">
                <span class="service-num-badge" style="background: <?php echo esc_attr($s['accent_bg']); ?>; color: <?php echo esc_attr($s['accent']); ?>;">
                  <?php echo esc_html($s['num']); ?>
                </span>
                <span class="service-category-pill" style="background: <?php echo esc_attr($s['accent_bg']); ?>; color: <?php echo esc_attr($s['accent']); ?>; border: 1px solid <?php echo esc_attr($s['accent']); ?>30;">
                  <span><?php echo esc_html($s['icon']); ?></span> <?php echo esc_html($s['badge']); ?>
                </span>
              </div>
              <h2 class="service-showcase-title"><?php echo esc_html($s['title']); ?></h2>
              <p class="service-showcase-tagline" style="color: <?php echo esc_attr($s['accent']); ?>;">
                <?php echo esc_html($s['tagline']); ?>
              </p>
              <p class="service-showcase-lead"><?php echo esc_html($s['lead']); ?></p>
            </div>

            <!-- Sub-Offerings Grid -->
            <div class="service-offerings-grid <?php echo count($s['offerings']) <= 2 ? 'service-offerings-grid--2col' : (count($s['offerings']) <= 4 ? 'service-offerings-grid--4col' : 'service-offerings-grid--5col'); ?>">
              <?php foreach ($s['offerings'] as $offering): ?>
                <div class="service-offering-box">
                  <div class="offering-icon-title">
                    <span class="offering-icon"><?php echo esc_html($offering['icon']); ?></span>
                    <h3 class="offering-title"><?php echo esc_html($offering['title']); ?></h3>
                  </div>
                  <p class="offering-desc"><?php echo esc_html($offering['desc']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Best For Bar -->
            <div class="service-best-for-bar">
              <span class="best-for-badge">🎯 <?php _e('Best for:', 'foundnxt'); ?></span>
              <span class="best-for-text"><?php echo esc_html($s['best_for']); ?></span>
            </div>

            <!-- Footer Action Row -->
            <div class="service-showcase-footer">
              <a href="<?php echo esc_url(add_query_arg('help', urlencode($s['help_val']), $contact_url)); ?>" class="service-action-btn service-action-btn--primary">
                <span><?php printf(__('Enquire About %s', 'foundnxt'), esc_html($s['title'])); ?></span>
                <span class="btn-arrow" aria-hidden="true">→</span>
              </a>
              <a href="<?php echo esc_url(add_query_arg('help', 'consultation', $contact_url)); ?>" class="service-action-btn-alt service-action-btn--secondary">
                <span><?php _e('Book a Free Consultation', 'foundnxt'); ?></span>
              </a>
            </div>

          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- PROCESS SECTION ("HOW WE WORK") -->
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
          <p><?php _e('We diagnose your tech stack, workflows, bottleneck areas, or audience reach goals.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">02</div>
          <h4><?php _e('Practical Roadmap', 'foundnxt'); ?></h4>
          <p><?php _e('We build a clear, lean action plan with right-sized tools, documented SOPs, and clear milestones.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">03</div>
          <h4><?php _e('Sprint Execution', 'foundnxt'); ?></h4>
          <p><?php _e('We deploy open-source tools, automate workflows, craft editorial features, or integrate systems.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-process-step fnx-reveal">
          <div class="step-num">04</div>
          <h4><?php _e('Review & Scale', 'foundnxt'); ?></h4>
          <p><?php _e('We hand over complete documentation, SOPs, and monitoring so your team runs independently.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- READY TO SCALE? CTA SECTION -->
    <section class="hp-block" style="padding: 40px 0 40px;">
      <div class="services-cta-banner-v2 text-center fnx-reveal">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">⚡ <?php _e('Next Steps', 'foundnxt'); ?></span>
        <h2 class="services-cta-title"><?php _e('Ready to Scale?', 'foundnxt'); ?></h2>
        <p class="services-cta-lead"><?php _e('Tell us where you’re stuck, and we’ll show you how to move forward.', 'foundnxt'); ?></p>
        <div class="services-cta-button-group">
          <a href="<?php echo $contact_url; ?>" class="btn-primary services-cta-btn">
            <?php _e('Get in Touch', 'foundnxt'); ?> →
          </a>
          <a href="<?php echo esc_url(add_query_arg('help', 'consultation', $contact_url)); ?>" class="btn-outline services-cta-btn-alt">
            <?php _e('Book a Free Consultation', 'foundnxt'); ?>
          </a>
        </div>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
