<?php
/**
 * Template Name: Services Page
 * Template Post Type: page
 *
 * Consulting services page for FoundNXT.
 * Assign this template to your /services/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_url     = esc_url(home_url('/'));
$services_url = esc_url(get_permalink());
$contact_url  = esc_url(home_url('/contact/'));

/* ── SERVICE + BREADCRUMB SCHEMA ─────────────────────────── */
$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Service',
            'serviceType' => 'Business Consulting',
            'provider'    => [
                '@type' => 'Organization',
                'name'  => 'FoundNXT',
                'url'   => $site_url,
            ],
            'areaServed'  => 'Global',
            'description' => 'Consulting for companies on scale and operations, valuation, funding strategy, technology adoption, and marketing & branding.',
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

/* ── SERVICE DEFINITIONS ─────────────────────────── */
$services = [
    [
        'icon'    => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M3 21h18M6 21V10l6-5 6 5v11M10 21v-6h4v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'label'   => __('Scale & Operations', 'foundnxt'),
        'title'   => __('Scale Without Breaking What Works', 'foundnxt'),
        'desc'    => __('We audit your operations, find the bottlenecks that cap growth, and build the SOPs and systems that let you 10x headcount and revenue without chaos.', 'foundnxt'),
        'tag'     => __('For Series A+ teams', 'foundnxt'),
        'accent'  => '#4f46e5',
        'featured'=> true,
    ],
    [
        'icon'    => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'label'   => __('Valuation', 'foundnxt'),
        'title'   => __('Know What You\'re Actually Worth', 'foundnxt'),
        'desc'    => __('Independent, defensible valuations built for fundraising, ESOP pools, M&A, or investor conversations — with the models and assumptions laid out clearly.', 'foundnxt'),
        'tag'     => __('For fundraising & M&A', 'foundnxt'),
        'accent'  => '#0891b2',
        'featured'=> true,
    ],
    [
        'icon'    => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><polyline points="16 7 22 7 22 13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'label'   => __('Funding Strategy', 'foundnxt'),
        'title'   => __('Raise on Better Terms, Faster', 'foundnxt'),
        'desc'    => __('Pitch deck and data room review, investor targeting, term sheet negotiation support, and positioning so your round moves faster with less friction.', 'foundnxt'),
        'tag'     => __('For pre-seed to Series C', 'foundnxt'),
        'accent'  => '#7c3aed',
        'featured'=> false,
    ],
    [
        'icon'    => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 21h8M12 17v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
        'label'   => __('Technology Adoption', 'foundnxt'),
        'title'   => __('The Right Tools, Not Just New Ones', 'foundnxt'),
        'desc'    => __('We map your workflows to the AI tools, automation, and infrastructure that actually move the needle — with a clear ROI case before you commit budget.', 'foundnxt'),
        'tag'     => __('For ops & product leaders', 'foundnxt'),
        'accent'  => '#d97706',
        'featured'=> false,
    ],
    [
        'icon'    => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'label'   => __('Marketing & Branding', 'foundnxt'),
        'title'   => __('Positioning Before Performance', 'foundnxt'),
        'desc'    => __('Brand audits, category positioning, and go-to-market strategy — so your marketing spend compounds instead of chasing a message that isn\'t landing.', 'foundnxt'),
        'tag'     => __('For founders & CMOs', 'foundnxt'),
        'accent'  => '#e11d48',
        'featured'=> false,
    ],
];

/* ── PROCESS STEPS ─────────────────────────── */
$process = [
    ['num' => '01', 'label' => __('Discovery',       'foundnxt'), 'desc' => __('We learn your business, numbers, and constraints before recommending anything.', 'foundnxt')],
    ['num' => '02', 'label' => __('Analysis',         'foundnxt'), 'desc' => __('Data-driven diagnosis of where the real leverage points are — not guesswork.', 'foundnxt')],
    ['num' => '03', 'label' => __('Recommendations',  'foundnxt'), 'desc' => __('A clear, prioritised plan with the reasoning behind every recommendation.', 'foundnxt')],
    ['num' => '04', 'label' => __('Execution Support','foundnxt'), 'desc' => __('We stay close through implementation — not a report that sits in a drawer.', 'foundnxt')],
];
?>

<div class="fnx-about-page fnx-services-page">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- ══════════════════════════════════════════
         HERO
    ══════════════════════════════════════════ -->
    <section class="about-hero" aria-labelledby="services-h1">
      <div class="about-hero-text">
        <span class="about-eyebrow"><?php _e('What We Do', 'foundnxt'); ?></span>
        <h1 class="about-h1" id="services-h1">
          <?php _e('Consulting for Companies Ready to Scale', 'foundnxt'); ?>
        </h1>
        <p class="about-lead">
          <?php _e('We work with founders and operators on the five things that actually move a company forward: scale, valuation, funding, technology, and brand — one consultancy, four disciplines, no fluff.', 'foundnxt'); ?>
        </p>
        <div class="about-badges">
          <span class="about-badge about-badge--green"><?php _e('Scale & Operations', 'foundnxt'); ?></span>
          <span class="about-badge about-badge--blue"><?php _e('Valuation', 'foundnxt'); ?></span>
          <span class="about-badge about-badge--amber"><?php _e('Funding Strategy', 'foundnxt'); ?></span>
          <span class="about-badge about-badge--muted"><?php _e('Tech & Brand', 'foundnxt'); ?></span>
        </div>
        <div class="about-badges" style="margin-top:20px;">
          <a href="<?php echo $contact_url; ?>" class="btn-primary"><?php _e('Book a Consultation', 'foundnxt'); ?></a>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         SERVICE CARDS
    ══════════════════════════════════════════ -->
    <section class="hp-block hp-wwa" aria-labelledby="services-grid-h2">
      <span class="about-eyebrow" id="services-grid-h2"><?php _e('Our Services', 'foundnxt'); ?></span>
      <h2 class="about-section-h2" style="margin-bottom:32px;"><?php _e('Five Ways We Help You Grow', 'foundnxt'); ?></h2>

      <div class="hp-wwa-grid">
        <?php foreach ($services as $s): ?>
        <a class="hp-wwa-card<?php echo $s['featured'] ? ' hp-wwa-card--featured' : ''; ?>"
           href="<?php echo $contact_url; ?>"
           style="--wwa-accent:<?php echo esc_attr($s['accent']); ?>;">

          <div class="hp-wwa-card-top">
            <div class="hp-wwa-card-icon" aria-hidden="true"><?php echo $s['icon']; ?></div>
            <span class="hp-wwa-card-label"><?php echo esc_html($s['label']); ?></span>
          </div>

          <div class="hp-wwa-card-body">
            <h3 class="hp-wwa-card-title"><?php echo esc_html($s['title']); ?></h3>
            <p class="hp-wwa-card-desc"><?php echo esc_html($s['desc']); ?></p>
          </div>

          <div class="hp-wwa-card-foot">
            <span class="hp-wwa-card-count"><?php echo esc_html($s['tag']); ?></span>
            <span class="hp-wwa-card-arrow" aria-hidden="true">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
          <div class="hp-wwa-card-bar" aria-hidden="true"></div>
        </a>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         HOW WE WORK — Process
    ══════════════════════════════════════════ -->
    <section class="about-section about-story-section" aria-labelledby="process-h2">
      <div class="about-story-inner">
        <div class="about-story-text">
          <span class="about-eyebrow"><?php _e('How We Work', 'foundnxt'); ?></span>
          <h2 class="about-section-h2" id="process-h2">
            <?php _e('A Straightforward Process, Every Engagement', 'foundnxt'); ?>
          </h2>
          <p><?php _e('No generic templates. Every engagement starts with understanding your business, not pitching a pre-built framework.', 'foundnxt'); ?></p>
          <p><?php _e('We work in focused sprints — typically 4 to 8 weeks per engagement — with clear deliverables at each stage, not open-ended retainers.', 'foundnxt'); ?></p>
        </div>
        <div class="about-story-timeline">
          <?php
          $dot_colors = ['green', 'blue', 'amber', 'blue'];
          foreach ($process as $i => $step): ?>
          <div class="about-tl-item">
            <div class="about-tl-dot about-tl-dot--<?php echo esc_attr($dot_colors[$i % 4]); ?>"></div>
            <div class="about-tl-body">
              <div class="about-tl-label"><?php echo esc_html($step['num']); ?></div>
              <div class="about-tl-title"><?php echo esc_html($step['label']); ?></div>
              <div class="about-tl-desc"><?php echo esc_html($step['desc']); ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         WHO WE WORK WITH
    ══════════════════════════════════════════ -->
    <section class="about-section" aria-labelledby="audience-h2">
      <span class="about-eyebrow"><?php _e('Who We Work With', 'foundnxt'); ?></span>
      <h2 class="about-section-h2" id="audience-h2" style="margin-bottom:28px;"><?php _e('Built for Founders and Operators at Every Stage', 'foundnxt'); ?></h2>
      <div class="about-audience-grid">
        <div class="about-audience-card">
          <div class="about-audience-num">01</div>
          <h3><?php _e('Early-Stage Founders', 'foundnxt'); ?></h3>
          <p><?php _e('Pre-seed and seed founders who need a fundable valuation story and a clear go-to-market before their next raise.', 'foundnxt'); ?></p>
        </div>
        <div class="about-audience-card">
          <div class="about-audience-num">02</div>
          <h3><?php _e('Scaling Companies', 'foundnxt'); ?></h3>
          <p><?php _e('Series A/B teams hitting operational bottlenecks — where growth is outpacing the systems that support it.', 'foundnxt'); ?></p>
        </div>
        <div class="about-audience-card">
          <div class="about-audience-num">03</div>
          <h3><?php _e('Investors & Boards', 'foundnxt'); ?></h3>
          <p><?php _e('Investors who need an independent valuation read or operational diligence on a portfolio company.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         CTA
    ══════════════════════════════════════════ -->
    <section class="about-section" aria-labelledby="services-cta-h2">
      <div class="about-cta-box">
        <h2 id="services-cta-h2"><?php _e('Tell Us Where You\'re Stuck', 'foundnxt'); ?></h2>
        <p><?php _e('We\'ll tell you honestly if we can help — no obligation, no sales pitch.', 'foundnxt'); ?></p>
        <a href="<?php echo $contact_url; ?>" class="btn-primary"><?php _e('Start the Conversation', 'foundnxt'); ?></a>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
