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

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'Service',
      'serviceType' => 'Executive Consulting & Advisory Services',
      'provider'    => [
        '@type' => 'Organization',
        'name'  => 'FoundNXT',
        'url'   => $site_url,
      ],
      'areaServed'  => 'Global',
      'description' => 'Business Consulting and Tech Consulting for founders and executive teams scaling in the AI era.',
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
?>

<div class="fnx-services-page" style="padding: 50px 0 90px;">
  <div class="container">

    <?php if (function_exists('fnx_breadcrumbs')) { fnx_breadcrumbs(); } ?>

    <!-- ── HERO SECTION ── -->
    <header class="services-hero-redesign text-center" style="max-width: 820px; margin: 0 auto 60px;">
      <span class="hp-hero-badge" style="background: rgba(20, 184, 166, 0.15); color: #14B8A6; border-color: rgba(20, 184, 166, 0.3);">
        ⚡ <?php _e('Advisory & Acceleration', 'foundnxt'); ?>
      </span>
      <h1 style="font-size: clamp(2.35rem, 5vw, 3.8rem); font-weight: 800; line-height: 1.1; margin: 16px 0 20px; color: var(--text);">
        <?php _e('Advisory and technical acceleration for', 'foundnxt'); ?> <span class="text-highlight"><?php _e('high-growth ventures.', 'foundnxt'); ?></span>
      </h1>
      <p style="font-size: 1.1875rem; color: var(--muted); line-height: 1.6; margin-bottom: 32px;">
        <?php _e('Hands-on consulting for founders and teams scaling with technology and AI. We combine operator experience with technical rigor to remove friction and accelerate your roadmap.', 'foundnxt'); ?>
      </p>
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="#services-grid" class="btn-hero-primary">
          <span><?php _e('Explore Current Services', 'foundnxt'); ?></span> &darr;
        </a>
        <a href="<?php echo $contact_url; ?>" class="btn-hero-secondary">
          <span><?php _e('Book a Free 20-Min Call', 'foundnxt'); ?></span> &rarr;
        </a>
      </div>
    </header>

    <!-- ── THE TWO ACTIVE SERVICES ── -->
    <section class="services-cards-section" id="services-grid" style="margin-bottom: 80px;">
      <div class="services-two-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 36px;">
        
        <!-- Service 1: Business Consulting -->
        <article class="svc-card" id="business-consulting" style="--svc-accent: #FF6B6B; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: 0 16px 40px -10px rgba(0,0,0,0.15);">
          <div class="svc-card-icon" style="background: rgba(255, 107, 107, 0.12); color: #FF6B6B; border-color: rgba(255, 107, 107, 0.3);">📈</div>
          <span style="font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; color: #FF6B6B; letter-spacing: 0.06em;"><?php _e('Active Practice', 'foundnxt'); ?></span>
          <h2 style="font-size: 1.85rem; font-weight: 800; margin: 8px 0 16px; color: var(--text);"><?php _e('Business Consulting', 'foundnxt'); ?></h2>
          <p style="color: var(--muted); font-size: 1.05rem; line-height: 1.6; margin-bottom: 24px;">
            <?php _e('Direct strategic partnership for founders scaling operations, fixing leaky unit economics, and preparing for competitive funding rounds.', 'foundnxt'); ?>
          </p>

          <!-- Who it's for -->
          <div style="margin-bottom: 24px; padding: 20px; border-radius: 14px; background: var(--bg); border: 1px solid var(--border);">
            <strong style="display: block; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: #FF6B6B; margin-bottom: 6px;">
              🎯 <?php _e('Who It’s For:', 'foundnxt'); ?>
            </strong>
            <p style="font-size: 0.9375rem; color: var(--text); margin: 0; line-height: 1.55;">
              <?php _e('Seed to Series B founders, operating executives, and business builders navigating margin compression, unit economics, or rapid go-to-market scaling.', 'foundnxt'); ?>
            </p>
          </div>

          <!-- What you get -->
          <div style="margin-bottom: 32px; flex-grow: 1;">
            <strong style="display: block; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text); margin-bottom: 12px;">
              📦 <?php _e('What You Get:', 'foundnxt'); ?>
            </strong>
            <ul class="svc-list" style="display: flex; flex-direction: column; gap: 12px;">
              <li><span class="svc-check" style="color: #FF6B6B;">✓</span> <span><?php _e('Comprehensive unit economics & CAC/LTV audit', 'foundnxt'); ?></span></li>
              <li><span class="svc-check" style="color: #FF6B6B;">✓</span> <span><?php _e('Fundraising pitch deck & cap table stress testing', 'foundnxt'); ?></span></li>
              <li><span class="svc-check" style="color: #FF6B6B;">✓</span> <span><?php _e('Operational cadence & executive KPI dashboards', 'foundnxt'); ?></span></li>
              <li><span class="svc-check" style="color: #FF6B6B;">✓</span> <span><?php _e('Weekly 1:1 strategy sprints with our senior practice lead', 'foundnxt'); ?></span></li>
            </ul>
          </div>

          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Business Consulting'), $contact_url)); ?>" class="btn-hero-primary" style="background: #FF6B6B; justify-content: center; width: 100%;">
            <span><?php _e('Enquire About Business Consulting', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>

        <!-- Service 2: Tech Consulting -->
        <article class="svc-card" id="tech-consulting" style="--svc-accent: #7C3AED; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: 0 16px 40px -10px rgba(0,0,0,0.15);">
          <div class="svc-card-icon" style="background: rgba(124, 58, 237, 0.12); color: #7C3AED; border-color: rgba(124, 58, 237, 0.3);">⚡</div>
          <span style="font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; color: #7C3AED; letter-spacing: 0.06em"><?php _e('Active Practice', 'foundnxt'); ?></span>
          <h2 style="font-size: 1.85rem; font-weight: 800; margin: 8px 0 16px; color: var(--text);"><?php _e('Tech Consulting', 'foundnxt'); ?></h2>
          <p style="color: var(--muted); font-size: 1.05rem; line-height: 1.6; margin-bottom: 24px;">
            <?php _e('Modern architecture advisory, AI workflow adoption, and infrastructure efficiency audits for engineering leads and tech-forward founders.', 'foundnxt'); ?>
          </p>

          <!-- Who it's for -->
          <div style="margin-bottom: 24px; padding: 20px; border-radius: 14px; background: var(--bg); border: 1px solid var(--border);">
            <strong style="display: block; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: #7C3AED; margin-bottom: 6px;">
              🎯 <?php _e('Who It’s For:', 'foundnxt'); ?>
            </strong>
            <p style="font-size: 0.9375rem; color: var(--text); margin: 0; line-height: 1.55;">
              <?php _e('Founders, CTOs, and engineering leaders seeking to integrate LLMs, build autonomous workflows, or reduce runaway cloud infrastructure spend.', 'foundnxt'); ?>
            </p>
          </div>

          <!-- What you get -->
          <div style="margin-bottom: 32px; flex-grow: 1;">
            <strong style="display: block; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text); margin-bottom: 12px;">
              📦 <?php _e('What You Get:', 'foundnxt'); ?>
            </strong>
            <ul class="svc-list" style="display: flex; flex-direction: column; gap: 12px;">
              <li><span class="svc-check" style="color: #7C3AED;">✓</span> <span><?php _e('Full stack architecture review & bottleneck diagnosis', 'foundnxt'); ?></span></li>
              <li><span class="svc-check" style="color: #7C3AED;">✓</span> <span><?php _e('Practical AI agent blueprints & evaluation harness setup', 'foundnxt'); ?></span></li>
              <li><span class="svc-check" style="color: #7C3AED;">✓</span> <span><?php _e('Build vs. buy vendor audit (LLMs, vector DBs, tooling)', 'foundnxt'); ?></span></li>
              <li><span class="svc-check" style="color: #7C3AED;">✓</span> <span><?php _e('Inference latency and cloud infrastructure cost reduction plan', 'foundnxt'); ?></span></li>
            </ul>
          </div>

          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), $contact_url)); ?>" class="btn-hero-primary" style="background: #7C3AED; justify-content: center; width: 100%;">
            <span><?php _e('Enquire About Tech Consulting', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>

      </div>
    </section>

    <!-- ── 3-STEP "HOW WE WORK" SECTION ── -->
    <section class="services-process-section" style="padding: 70px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); margin-bottom: 80px;">
      <div class="hp-section-header" style="justify-content: center; text-align: center; margin-bottom: 48px;">
        <div>
          <span class="hp-sec-eyebrow" style="color: var(--accent-violet);"><?php _e('Engagement Model', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('How We Work With Founders', 'foundnxt'); ?></h2>
          <p style="color: var(--muted); font-size: 1rem; max-width: 580px; margin: 8px auto 0;">
            <?php _e('A battle-tested 3-step cadence designed to deliver measurable results in weeks, not months.', 'foundnxt'); ?>
          </p>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;">
        <!-- Step 1 -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px;">
          <div style="font-size: 2.25rem; font-weight: 800; color: var(--accent-coral); margin-bottom: 12px;">01</div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; color: var(--text);"><?php _e('Discovery & Deep-Dive Audit', 'foundnxt'); ?></h3>
          <p style="font-size: 0.9375rem; color: var(--muted); line-height: 1.6; margin: 0;">
            <?php _e('We inspect your data, codebases, unit economics, and team bottlenecks under NDA to establish clear reality over assumptions.', 'foundnxt'); ?>
          </p>
        </div>

        <!-- Step 2 -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px;">
          <div style="font-size: 2.25rem; font-weight: 800; color: var(--accent-violet); margin-bottom: 12px;">02</div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; color: var(--text);"><?php _e('Execution Blueprint', 'foundnxt'); ?></h3>
          <p style="font-size: 0.9375rem; color: var(--muted); line-height: 1.6; margin: 0;">
            <?php _e('We construct prioritized architectural decisions, financial models, and growth sprints with measurable success milestones.', 'foundnxt'); ?>
          </p>
        </div>

        <!-- Step 3 -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px;">
          <div style="font-size: 2.25rem; font-weight: 800; color: var(--accent-teal); margin-bottom: 12px;">03</div>
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; color: var(--text);"><?php _e('Hands-On Scaling & Handover', 'foundnxt'); ?></h3>
          <p style="font-size: 0.9375rem; color: var(--muted); line-height: 1.6; margin: 0;">
            <?php _e('We partner with your team in focused weekly sprints, stress-test outputs, and hand over sustainable SOPs for autonomous operation.', 'foundnxt'); ?>
          </p>
        </div>
      </div>
    </section>

    <!-- ── COMING SOON SERVICES STRIP (Market Research & Valuation) ── -->
    <section class="services-coming-soon-section" style="margin-bottom: 80px;">
      <div class="services-coming-soon-box">
        <span class="coming-soon-badge"><?php _e('In Development · Coming Soon', 'foundnxt'); ?></span>
        <h3 class="coming-soon-title" style="font-size: 1.6rem;"><?php _e('Market Research & Valuation Practice', 'foundnxt'); ?></h3>
        <p class="coming-soon-desc" style="max-width: 600px; margin: 0 auto 24px;">
          <?php _e('We are building proprietary Indian startup valuation benchmarks, sector datasets, and custom TAM sizing models. Join our private waitlist for early access.', 'foundnxt'); ?>
        </p>

        <?php if (isset($_GET['waitlist']) && $_GET['waitlist'] === 'success'): ?>
          <div style="padding: 12px 20px; border-radius: 10px; background: rgba(16, 185, 129, 0.15); color: #10B981; font-weight: 700; margin-bottom: 12px;">
            ✓ <?php _e('You are on the priority waitlist! We will notify you first upon launch.', 'foundnxt'); ?>
          </div>
        <?php else: ?>
          <form class="coming-soon-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_waitlist_submit">
            <?php wp_nonce_field('fnx_waitlist_action', 'fnx_waitlist_nonce'); ?>
            <input type="hidden" name="waitlist_service" value="Market Research & Valuation Services">
            <input type="email" name="waitlist_email" class="coming-soon-input" placeholder="<?php esc_attr_e('Enter your work email…', 'foundnxt'); ?>" required>
            <button type="submit" class="btn-coming-soon">
              <?php _e('Join the waitlist', 'foundnxt'); ?> &rarr;
            </button>
          </form>
        <?php endif; ?>
      </div>
    </section>

    <!-- ── FAQ ACCORDION (Details per modern-web-guidance) ── -->
    <section class="services-faq-section" style="max-width: 800px; margin: 0 auto 80px;">
      <div class="hp-section-header" style="justify-content: center; text-align: center; margin-bottom: 36px;">
        <div>
          <span class="hp-sec-eyebrow"><?php _e('Got Questions?', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('Frequently Asked Questions', 'foundnxt'); ?></h2>
        </div>
      </div>

      <div class="fnx-faq-accordion">
        <details class="fnx-faq-item" name="faq-group" open>
          <summary class="fnx-faq-summary">
            <span><?php _e('How do your consulting sprints work?', 'foundnxt'); ?></span>
            <span class="fnx-faq-chevron">&darr;</span>
          </summary>
          <div class="fnx-faq-body">
            <?php _e('Engagements typically operate in 4-to-8 week structured sprints. We kick off with a thorough diagnostic audit, align on key deliverables (architecture blueprints or financial models), and run weekly working sessions with asynchronous review in between.', 'foundnxt'); ?>
          </div>
        </details>

        <details class="fnx-faq-item" name="faq-group">
          <summary class="fnx-faq-summary">
            <span><?php _e('Who actually conducts the advisory sessions?', 'foundnxt'); ?></span>
            <span class="fnx-faq-chevron">&darr;</span>
          </summary>
          <div class="fnx-faq-body">
            <?php _e('You work directly with senior practitioners who have founded companies, scaled enterprise software, and prepared companies for venture rounds. We never pass you off to junior account managers.', 'foundnxt'); ?>
          </div>
        </details>

        <details class="fnx-faq-item" name="faq-group">
          <summary class="fnx-faq-summary">
            <span><?php _e('Do you sign mutual NDAs before reviewing sensitive company data?', 'foundnxt'); ?></span>
            <span class="fnx-faq-chevron">&darr;</span>
          </summary>
          <div class="fnx-faq-body">
            <?php _e('Yes. All engagements are preceded by a strict mutual non-disclosure agreement (NDA). We treat your financials, code architecture, and cap tables with complete confidentiality.', 'foundnxt'); ?>
          </div>
        </details>

        <details class="fnx-faq-item" name="faq-group">
          <summary class="fnx-faq-summary">
            <span><?php _e('What are the consulting fee structures?', 'foundnxt'); ?></span>
            <span class="fnx-faq-chevron">&darr;</span>
          </summary>
          <div class="fnx-faq-body">
            <?php _e('We offer fixed-scope sprint pricing and monthly advisory retainers depending on company stage and requirements. Contact us with your scope for an upfront, transparent proposal.', 'foundnxt'); ?>
          </div>
        </details>
      </div>
    </section>

    <!-- ── BOTTOM CTA BAND ── -->
    <section class="services-cta-band text-center" style="background: linear-gradient(135deg, #131B31 0%, #1C2640 100%); border: 1px solid var(--border); border-radius: 24px; padding: 50px 30px;">
      <h2 style="font-size: 2.25rem; font-weight: 800; color: #FFFFFF; margin-bottom: 12px;"><?php _e('Ready to scale faster?', 'foundnxt'); ?></h2>
      <p style="font-size: 1.0625rem; color: #94A3B8; max-width: 540px; margin: 0 auto 28px;">
        <?php _e('Schedule a preliminary 20-minute conversation to explore whether our advisory practice is the right fit for your roadmap.', 'foundnxt'); ?>
      </p>
      <a href="<?php echo $contact_url; ?>" class="btn-hero-primary" style="padding: 14px 32px; font-size: 1rem;">
        <span><?php _e('Book a Free 20-Min Call', 'foundnxt'); ?></span> &rarr;
      </a>
    </section>

  </div>
</div>

<?php get_footer(); ?>
