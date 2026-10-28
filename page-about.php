<?php
/**
 * Template Name: About Page
 * Template Post Type: page
 *
 * About page for FoundNXT aligned with new editorial positioning.
 * Assign this template to your /about/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_url  = esc_url(home_url('/'));
$about_url = esc_url(get_permalink());

/* ── JSON-LD SCHEMA ── */
$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Organization',
            '@id'         => $site_url . '#organization',
            'name'        => 'FoundNXT',
            'url'         => $site_url,
            'description' => 'How to scale your company in the AI era. Independent business and technology publication for founders, operators, and investors.',
            'founder'     => [
                '@type' => 'Person',
                'name'  => 'FoundNXT Editorial Team',
            ],
            'sameAs'      => [
                'https://instagram.com/foundnxt',
                'https://x.com/foundnxt',
                'https://linkedin.com/company/foundnxt'
            ]
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

<div class="fnx-about-page" style="padding: 50px 0 90px;">
  <div class="container">

    <?php if (function_exists('fnx_breadcrumbs')) { fnx_breadcrumbs(); } ?>

    <!-- ── HERO: STORY & MISSION ── -->
    <header class="about-hero text-center" style="max-width: 820px; margin: 0 auto 70px;">
      <span class="hp-hero-badge" style="background: rgba(124, 58, 237, 0.15); color: #A78BFA; border-color: rgba(124, 58, 237, 0.3);">
        📖 <?php _e('Our Story & Philosophy', 'foundnxt'); ?>
      </span>
      <h1 style="font-size: clamp(2.35rem, 5vw, 3.8rem); font-weight: 800; line-height: 1.12; margin: 16px 0 20px; color: var(--text);">
        <?php _e('How to scale your company in the', 'foundnxt'); ?> <span class="text-highlight"><?php _e('AI era.', 'foundnxt'); ?></span>
      </h1>
      <p style="font-size: 1.2rem; color: var(--muted); line-height: 1.6; margin: 0 auto 32px; max-width: 680px;">
        <?php _e('FoundNXT is an independent editorial publication dissecting high-growth startups, market shifts, and AI systems. Built for founders, operators, and investors navigating the next economic cycle.', 'foundnxt'); ?>
      </p>
    </header>

    <!-- ── WHY FOUNDNXT EXISTS & MISSION ── -->
    <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 36px; margin-bottom: 80px;">
      <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 36px;">
        <span style="font-size: 2rem; display: block; margin-bottom: 14px;">🎯</span>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 12px;"><?php _e('Our Mission', 'foundnxt'); ?></h2>
        <p style="font-size: 1rem; color: var(--muted); line-height: 1.65; margin: 0;">
          <?php _e('To demystify corporate finance, startup valuation, enterprise AI adoption, and macroeconomic shifts into practical, actionable intelligence for founders and executive leaders. We skip fluff, hype, and technical jargon to deliver real-world frameworks, mathematical models, and verifiable market data.', 'foundnxt'); ?>
        </p>
      </div>

      <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 36px;">
        <span style="font-size: 2rem; display: block; margin-bottom: 14px;">💡</span>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text); margin-bottom: 12px;"><?php _e('Why FoundNXT Exists', 'foundnxt'); ?></h2>
        <p style="font-size: 1rem; color: var(--muted); line-height: 1.65; margin: 0;">
          <?php _e('The internet is flooded with generic AI summaries and recycled business advice. FoundNXT was created to provide deep, analytical signal: forensic post-mortems on failed ventures, honest unit-economic models, and technical architectures tested on live production systems.', 'foundnxt'); ?>
        </p>
      </div>
    </section>

    <!-- ── WHAT READERS GET (3-Column Pillar Breakdown) ── -->
    <section style="padding: 70px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); margin-bottom: 80px;">
      <div class="hp-section-header" style="justify-content: center; text-align: center; margin-bottom: 48px;">
        <div>
          <span class="hp-sec-eyebrow"><?php _e('Editorial Standards', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('What Readers Get', 'foundnxt'); ?></h2>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;">
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px;">
          <div style="font-size: 1.75rem; margin-bottom: 12px;">📊</div>
          <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 10px;"><?php _e('Mathematical Rigor', 'foundnxt'); ?></h3>
          <p style="font-size: 0.9375rem; color: var(--muted); line-height: 1.6; margin: 0;">
            <?php _e('We breakdown DCF models, revenue multiples, dilution mathematics, and margin teardowns with verifiable corporate filings and audited numbers.', 'foundnxt'); ?>
          </p>
        </div>

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px;">
          <div style="font-size: 1.75rem; margin-bottom: 12px;">🤖</div>
          <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 10px;"><?php _e('Applied AI Systems', 'foundnxt'); ?></h3>
          <p style="font-size: 0.9375rem; color: var(--muted); line-height: 1.6; margin: 0;">
            <?php _e('No toy demos. We focus on real-world agentic pipelines, evaluation harnesses, latency tradeoffs, and actual enterprise cost economics.', 'foundnxt'); ?>
          </p>
        </div>

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px;">
          <div style="font-size: 1.75rem; margin-bottom: 12px;">⚡</div>
          <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 10px;"><?php _e('Operator Playbooks', 'foundnxt'); ?></h3>
          <p style="font-size: 0.9375rem; color: var(--muted); line-height: 1.6; margin: 0;">
            <?php _e('Step-by-step frameworks that founders can apply immediately to hiring, unit economics, tech stack selection, and capital allocation.', 'foundnxt'); ?>
          </p>
        </div>
      </div>
    </section>

    <!-- ── FOUNDER BIO & PHOTO ── -->
    <section class="about-founder-section" style="max-width: 860px; margin: 0 auto 80px; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 48px;">
      <div style="display: grid; grid-template-columns: 160px 1fr; gap: 36px; align-items: center;">
        
        <!-- Founder Photo / Avatar Placeholder -->
        <div style="width: 160px; height: 160px; border-radius: 24px; overflow: hidden; background: linear-gradient(135deg, #7C3AED 0%, #FF6B6B 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 30px rgba(124, 58, 237, 0.35);">
          <span style="font-size: 3.5rem; font-weight: 800; color: #FFFFFF;">fN</span>
        </div>

        <!-- Bio & Background -->
        <div>
          <span style="font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-violet);">
            <?php _e('Founder & Managing Editor', 'foundnxt'); ?>
          </span>
          <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text); margin: 6px 0 12px;">
            <?php _e('FoundNXT Editorial Practice', 'foundnxt'); ?>
          </h2>
          <p style="font-size: 1rem; color: var(--muted); line-height: 1.65; margin-bottom: 20px;">
            <?php _e('Computer science background with an MBA in product management. Specializing in startup valuation, enterprise software infrastructure, and AI systems. Leads editorial research and executive advisory sprints for Indian and global venture-backed startups.', 'foundnxt'); ?>
          </p>

          <!-- Social Links -->
          <div style="display: flex; gap: 16px; align-items: center;">
            <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" style="font-size: 0.875rem; font-weight: 700; color: #E1306C; display: inline-flex; align-items: center; gap: 6px;">
              <span>Instagram @foundnxt</span> &rarr;
            </a>
            <a href="https://x.com/foundnxt" target="_blank" rel="noopener noreferrer" style="font-size: 0.875rem; font-weight: 700; color: var(--text); display: inline-flex; align-items: center; gap: 6px;">
              <span>X @foundnxt</span> &rarr;
            </a>
            <a href="https://linkedin.com/company/foundnxt" target="_blank" rel="noopener noreferrer" style="font-size: 0.875rem; font-weight: 700; color: #0A66C2; display: inline-flex; align-items: center; gap: 6px;">
              <span>LinkedIn</span> &rarr;
            </a>
          </div>
        </div>

      </div>
    </section>

    <!-- ── BOTTOM CTA ── -->
    <section class="about-cta text-center" style="background: linear-gradient(135deg, #0B1020 0%, #131B31 100%); border: 1px solid var(--border-subtle); border-radius: 24px; padding: 60px 30px; color: #FFFFFF;">
      <h2 style="font-size: 2.25rem; font-weight: 800; color: #FFFFFF; margin-bottom: 12px;"><?php _e('Join Our Community of Founders', 'foundnxt'); ?></h2>
      <p style="font-size: 1.0625rem; color: #94A3B8; max-width: 580px; margin: 0 auto 28px;">
        <?php _e('Read our latest insights, subscribe to the weekly executive brief, or partner with our advisory practice.', 'foundnxt'); ?>
      </p>
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-hero-primary">
          <span><?php _e('Read Latest Insights', 'foundnxt'); ?></span> &rarr;
        </a>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-hero-secondary">
          <span><?php _e('Get in Touch', 'foundnxt'); ?></span> &rarr;
        </a>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
