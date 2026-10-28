<?php get_header(); ?>

<?php if (is_front_page() || is_home()): ?>
  <!-- ════════════════════════════════════════════════════════════════
       FOUNDNXT — REDESIGNED EDITORIAL & LEAD GENERATION HOMEPAGE
       10 Sections in exact specified hierarchy:
       1. Hero (Headline, Subline, 2 Buttons, Abstract BG, Featured Post Card)
       2. Latest Insights (6 posts grid, tag, read time, date, View all button)
       3. Topics (8 colorful category cards with accent colors & archives)
       4. Featured Series (Startup Post-Mortems, Business Wars, AI for Business)
       5. Instagram Strip (latest @foundnxt carousels + Follow button)
       6. Services (Business Consulting & Tech Consulting + Coming Soon waitlist)
       7. Newsletter ("One sharp business breakdown every week. No fluff.")
       8. About Blurb (2 lines + "Read our story" link)
       9. Final CTA Band ("Scaling a business? Let's talk." + Book a call)
       10. Footer (in footer.php)
  ════════════════════════════════════════════════════════════════ -->

  <?php
  // Deduplication helper to prevent repeating the hero article in Latest Insights
  $rendered_post_ids = [];

  // Helper date format
  function fnx_format_hp_date($date_str) {
      return date_i18n('M j, Y', strtotime($date_str));
  }

  // 1. Fetch Featured Post for Hero
  $hero_posts = get_posts([
      'posts_per_page' => 1,
      'post_status'    => 'publish',
      'meta_key'       => '_thumbnail_id', // prefer post with thumbnail
  ]);
  if (empty($hero_posts)) {
      $hero_posts = get_posts(['posts_per_page' => 1, 'post_status' => 'publish']);
  }
  $hero_post = !empty($hero_posts) ? $hero_posts[0] : null;
  if ($hero_post) {
      $rendered_post_ids[] = $hero_post->ID;
  }
  ?>

  <!-- ── 1. HERO SECTION ── -->
  <section class="hp-hero-redesign" id="hero">
    <div class="container">
      <div class="hp-hero-grid">
        
        <!-- Left Hero Column: Headline, Subline, Action Buttons -->
        <div class="hp-hero-content">
          <div class="hp-hero-badge">
            <span style="font-size: 0.95rem;">⚡</span>
            <span><?php _e('Business · Markets · AI · Growth', 'foundnxt'); ?></span>
          </div>

          <h1 class="hp-hero-h1">
            <?php _e('How to scale your company in the', 'foundnxt'); ?> <span class="text-highlight"><?php _e('AI era.', 'foundnxt'); ?></span>
          </h1>

          <p class="hp-hero-lead">
            <?php _e('Startup stories, market insights and practical playbooks for founders and business leaders.', 'foundnxt'); ?>
          </p>

          <div class="hp-hero-buttons">
            <a href="#latest-insights" class="btn-hero-primary">
              <span><?php _e('Read the latest', 'foundnxt'); ?></span> &darr;
            </a>
            <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-hero-secondary">
              <span><?php _e('Work with us', 'foundnxt'); ?></span> &rarr;
            </a>
          </div>
        </div>

        <!-- Right Hero Column: Featured Post Card -->
        <div class="hp-hero-featured-col">
          <?php if ($hero_post):
            $hcats = get_the_category($hero_post->ID);
            $hcat  = !empty($hcats) ? $hcats[0] : null;
            $hslug = $hcat ? $hcat->slug : 'startups-funding';
            $hname = $hcat ? $hcat->name : __('Featured Story', 'foundnxt');
            $hcol  = fnx_get_category_color($hslug);
            $hread = function_exists('fnx_read_time') ? fnx_read_time($hero_post->ID) : '5 min read';
          ?>
            <article class="hero-featured-card" style="--card-accent: <?php echo esc_attr($hcol); ?>;">
              <a href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>" class="hero-featured-thumb" aria-label="<?php echo esc_attr(get_the_title($hero_post->ID)); ?>">
                <?php if (has_post_thumbnail($hero_post->ID)): ?>
                  <?php echo get_the_post_thumbnail($hero_post->ID, 'fnx-hero', [
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                    'alt'           => esc_attr(get_the_title($hero_post->ID))
                  ]); ?>
                <?php else: ?>
                  <div style="width:100%; height:100%; min-height:240px; background: linear-gradient(135deg, #131B31 0%, #1C2640 100%); display:flex; align-items:center; justify-content:center; color:#A78BFA; font-weight:800; font-size:1.5rem;">
                    foundNXT Featured
                  </div>
                <?php endif; ?>
              </a>
              <div class="hero-featured-body">
                <span class="hero-cat-tag" style="background: <?php echo esc_attr($hcol); ?>20; color: <?php echo esc_attr($hcol); ?>; border: 1px solid <?php echo esc_attr($hcol); ?>40;">
                  <?php echo esc_html($hname); ?>
                </span>
                <h2 class="hero-featured-title">
                  <a href="<?php echo esc_url(get_permalink($hero_post->ID)); ?>">
                    <?php echo esc_html(get_the_title($hero_post->ID)); ?>
                  </a>
                </h2>
                <p class="hero-featured-excerpt">
                  <?php echo esc_html(wp_trim_words(get_the_excerpt($hero_post->ID), 20)); ?>
                </p>
                <div class="hero-featured-meta">
                  <span><?php echo fnx_format_hp_date($hero_post->post_date); ?></span>
                  <span>&bull;</span>
                  <span><?php echo esc_html($hread); ?></span>
                  <span>&bull;</span>
                  <span><?php _e('By FoundNXT Editorial', 'foundnxt'); ?></span>
                </div>
              </div>
            </article>
          <?php endif; ?>
        </div>

      </div><!-- /.hp-hero-grid -->
    </div><!-- /.container -->
  </section>

  <!-- ── 2. LATEST INSIGHTS (6-Post Grid) ── -->
  <section class="hp-insights-section" id="latest-insights">
    <div class="container">
      <div class="hp-section-header">
        <div>
          <span class="hp-sec-eyebrow"><?php _e('Frontline Intelligence', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('Latest Insights', 'foundnxt'); ?></h2>
        </div>
        <div>
          <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-hero-secondary" style="padding: 10px 22px; font-size: 0.875rem;">
            <?php _e('View all insights', 'foundnxt'); ?> &rarr;
          </a>
        </div>
      </div>

      <?php
      $latest_posts = get_posts([
          'posts_per_page' => 6,
          'post_status'    => 'publish',
          'post__not_in'   => $rendered_post_ids,
          'orderby'        => 'date',
          'order'          => 'DESC',
      ]);
      ?>

      <div class="hp-insights-grid">
        <?php foreach ($latest_posts as $p):
          $cats   = get_the_category($p->ID);
          $cat    = !empty($cats) ? $cats[0] : null;
          $cslug  = $cat ? $cat->slug : 'startups-funding';
          $cname  = $cat ? $cat->name : __('Insights', 'foundnxt');
          $ccol   = fnx_get_category_color($cslug);
          $read_t = function_exists('fnx_read_time') ? fnx_read_time($p->ID) : '4 min read';
        ?>
          <article class="insight-card" style="--card-accent: <?php echo esc_attr($ccol); ?>;">
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="insight-card-thumb" aria-label="<?php echo esc_attr(get_the_title($p->ID)); ?>">
              <?php if (has_post_thumbnail($p->ID)): ?>
                <?php echo get_the_post_thumbnail($p->ID, 'fnx-card', [
                  'loading'  => 'lazy',
                  'decoding' => 'async',
                  'alt'      => esc_attr(get_the_title($p->ID))
                ]); ?>
              <?php else: ?>
                <div style="width:100%; height:100%; min-height:180px; background: linear-gradient(135deg, #131B31 0%, #1C2640 100%); display:flex; align-items:center; justify-content:center; color:<?php echo esc_attr($ccol); ?>; font-weight:700;">
                  <?php echo esc_html($cname); ?>
                </div>
              <?php endif; ?>
            </a>
            <div class="insight-card-body">
              <span class="insight-card-tag" style="background: <?php echo esc_attr($ccol); ?>18; color: <?php echo esc_attr($ccol); ?>; border: 1px solid <?php echo esc_attr($ccol); ?>35;">
                <?php echo esc_html($cname); ?>
              </span>
              <h3 class="insight-card-title">
                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>">
                  <?php echo esc_html(get_the_title($p->ID)); ?>
                </a>
              </h3>
              <div class="insight-card-meta">
                <span><?php echo fnx_format_hp_date($p->post_date); ?></span>
                <span><?php echo esc_html($read_t); ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- Mobile Fallback Button -->
      <div style="text-align: center; margin-top: 36px;" class="mobile-only-block">
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-hero-primary" style="width: 100%; justify-content: center;">
          <?php _e('View all insights', 'foundnxt'); ?> &rarr;
        </a>
      </div>
    </div>
  </section>

  <!-- ── 3. TOPICS (8 Colorful Category Cards) ── -->
  <section class="hp-topics-section" id="topics">
    <div class="container">
      <div class="hp-section-header" style="justify-content: center; text-align: center; margin-bottom: 48px;">
        <div>
          <span class="hp-sec-eyebrow"><?php _e('Curated Taxonomies', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('Explore by Topic', 'foundnxt'); ?></h2>
          <p style="color: var(--muted); font-size: 1rem; max-width: 600px; margin: 8px auto 0;">
            <?php _e('Deep playbooks, teardowns, and market datasets organized across our core editorial beats.', 'foundnxt'); ?>
          </p>
        </div>
      </div>

      <div class="hp-topics-grid">
        <?php foreach (fnx_get_primary_categories() as $topic): ?>
          <a href="<?php echo esc_url($topic['url']); ?>" class="topic-card" style="--topic-accent: <?php echo esc_attr($topic['color']); ?>;">
            <div class="topic-card-icon">
              <?php echo esc_html($topic['icon']); ?>
            </div>
            <h3 class="topic-card-title">
              <?php echo esc_html($topic['name']); ?>
            </h3>
            <p class="topic-card-desc">
              <?php echo esc_html($topic['desc']); ?>
            </p>
            <div class="topic-card-footer">
              <span class="topic-card-count">
                <?php printf(_n('%d article', '%d articles', $topic['count'], 'foundnxt'), $topic['count']); ?>
              </span>
              <span class="topic-card-link">
                <span><?php _e('Explore', 'foundnxt'); ?></span> &rarr;
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── 4. FEATURED SERIES (3 Large Distinct Cards) ── -->
  <section class="hp-series-section" id="featured-series">
    <div class="container">
      <div class="hp-section-header">
        <div>
          <span class="hp-sec-eyebrow" style="color: var(--accent-violet);"><?php _e('Special Investigations', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('Featured Editorial Series', 'foundnxt'); ?></h2>
        </div>
      </div>

      <div class="hp-series-grid">
        <!-- Series 1: Startup Post-Mortems -->
        <article class="series-card" style="--series-accent: #FF6B6B;">
          <span class="series-card-badge">01 / Investigation</span>
          <h3 class="series-card-title"><?php _e('Startup Post-Mortems', 'foundnxt'); ?></h3>
          <p class="series-card-desc">
            <?php _e('Forensic teardowns into why ambitious ventures ran out of runway. Analyzing silent cap table collapses, broken unit economics, and post-mortems every founder must study.', 'foundnxt'); ?>
          </p>
          <a href="<?php echo esc_url(home_url('/category/startup-stories/')); ?>" class="series-card-btn">
            <span><?php _e('Explore Post-Mortems', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>

        <!-- Series 2: Business Wars -->
        <article class="series-card" style="--series-accent: #F59E0B;">
          <span class="series-card-badge">02 / Market Battle</span>
          <h3 class="series-card-title"><?php _e('Business Wars', 'foundnxt'); ?></h3>
          <p class="series-card-desc">
            <?php _e('Head-to-head operational battles: Groww vs Zerodha vs Upstox, Swiggy vs Zomato, Razorpay vs Cashfree. Teardowns of moat architecture, margin compression, and customer retention.', 'foundnxt'); ?>
          </p>
          <a href="<?php echo esc_url(home_url('/category/markets-valuation/')); ?>" class="series-card-btn">
            <span><?php _e('Read Business Wars', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>

        <!-- Series 3: AI for Business -->
        <article class="series-card" style="--series-accent: #7C3AED;">
          <span class="series-card-badge">03 / Applied Tech</span>
          <h3 class="series-card-title"><?php _e('AI for Business', 'foundnxt'); ?></h3>
          <p class="series-card-desc">
            <?php _e('Practical blueprints for integrating LLMs and agentic systems into core workflows. Moving past toy wrappers to defensible infrastructure, custom evaluations, and unit-economic ROI.', 'foundnxt'); ?>
          </p>
          <a href="<?php echo esc_url(home_url('/category/ai-tech/')); ?>" class="series-card-btn">
            <span><?php _e('Explore AI Blueprints', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>
      </div>
    </div>
  </section>

  <!-- ── 5. INSTAGRAM STRIP (@foundnxt carousels + Follow button) ── -->
  <section class="hp-insta-section" id="instagram-strip">
    <div class="container">
      <div class="hp-section-header" style="justify-content: space-between; align-items: flex-end;">
        <div>
          <span class="hp-sec-eyebrow" style="color: #E1306C;"><?php _e('Visual Breakdowns', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('Latest on Instagram @foundnxt', 'foundnxt'); ?></h2>
          <p style="color: var(--muted); font-size: 0.9375rem; margin-top: 6px;">
            <?php _e('Short-form visual slides, valuation cheatsheets, and venture models for quick consumption on mobile.', 'foundnxt'); ?>
          </p>
        </div>
        <div>
          <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" class="btn-insta-follow">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
            <span><?php _e('Follow @foundnxt', 'foundnxt'); ?></span>
          </a>
        </div>
      </div>

      <div class="insta-grid">
        <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" class="insta-card">
          <div class="insta-top-row">
            <span class="insta-badge-icon">📸</span>
            <span class="insta-card-slides">10 Slides</span>
          </div>
          <h4 class="insta-card-title"><?php _e('Valuation Multiples: What Indian VCs Are Actually Paying in 2026', 'foundnxt'); ?></h4>
          <span style="font-size: 0.8125rem; color: #E1306C; font-weight: 700;"><?php _e('View on Instagram →', 'foundnxt'); ?></span>
        </a>

        <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" class="insta-card">
          <div class="insta-top-row">
            <span class="insta-badge-icon">🤖</span>
            <span class="insta-card-slides">8 Slides</span>
          </div>
          <h4 class="insta-card-title"><?php _e('The Solo Founder AI Stack: 7 Tools Running a $1M Business', 'foundnxt'); ?></h4>
          <span style="font-size: 0.8125rem; color: #E1306C; font-weight: 700;"><?php _e('View on Instagram →', 'foundnxt'); ?></span>
        </a>

        <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" class="insta-card">
          <div class="insta-top-row">
            <span class="insta-badge-icon">📊</span>
            <span class="insta-card-slides">10 Slides</span>
          </div>
          <h4 class="insta-card-title"><?php _e('Cap Table Mistakes That Cost First-Time Founders 40% Equity', 'foundnxt'); ?></h4>
          <span style="font-size: 0.8125rem; color: #E1306C; font-weight: 700;"><?php _e('View on Instagram →', 'foundnxt'); ?></span>
        </a>

        <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" class="insta-card">
          <div class="insta-top-row">
            <span class="insta-badge-icon">⚔️</span>
            <span class="insta-card-slides">9 Slides</span>
          </div>
          <h4 class="insta-card-title"><?php _e('Business War Breakdown: Groww vs Zerodha vs Upstox in Numbers', 'foundnxt'); ?></h4>
          <span style="font-size: 0.8125rem; color: #E1306C; font-weight: 700;"><?php _e('View on Instagram →', 'foundnxt'); ?></span>
        </a>
      </div>
    </div>
  </section>

  <!-- ── 6. SERVICES (2 Active Cards + Coming Soon Waitlist) ── -->
  <section class="hp-services-section" id="services">
    <div class="container">
      <div class="hp-section-header" style="justify-content: center; text-align: center; margin-bottom: 48px;">
        <div>
          <span class="hp-sec-eyebrow" style="color: var(--accent-teal);"><?php _e('Strategic Advisory', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('Work With FoundNXT', 'foundnxt'); ?></h2>
          <p style="color: var(--muted); font-size: 1rem; max-width: 620px; margin: 8px auto 0;">
            <?php _e('Hands-on consulting for ambitious founders and executive teams scaling in the AI era.', 'foundnxt'); ?>
          </p>
        </div>
      </div>

      <!-- Two Active Services Grid -->
      <div class="services-two-grid">
        <!-- Service 1: Business Consulting -->
        <article class="svc-card" style="--svc-accent: #FF6B6B;">
          <div class="svc-card-icon">📈</div>
          <h3 class="svc-card-title"><?php _e('Business Consulting', 'foundnxt'); ?></h3>
          <p class="svc-card-desc">
            <?php _e('One-to-one strategic advisory for founders and operators. We evaluate your unit economics, resolve operational bottlenecks, and structure scalable growth playbooks.', 'foundnxt'); ?>
          </p>
          <ul class="svc-list">
            <li><span class="svc-check">✓</span> <span><?php _e('Growth strategy & unit economics modeling', 'foundnxt'); ?></span></li>
            <li><span class="svc-check">✓</span> <span><?php _e('Fundraising readiness & cap table health check', 'foundnxt'); ?></span></li>
            <li><span class="svc-check">✓</span> <span><?php _e('Scaling systems & operational bottleneck audits', 'foundnxt'); ?></span></li>
          </ul>
          <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-hero-primary" style="background: #FF6B6B; justify-content: center;">
            <span><?php _e('Learn About Business Consulting', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>

        <!-- Service 2: Tech Consulting -->
        <article class="svc-card" style="--svc-accent: #7C3AED;">
          <div class="svc-card-icon">⚡</div>
          <h3 class="svc-card-title"><?php _e('Tech Consulting', 'foundnxt'); ?></h3>
          <p class="svc-card-desc">
            <?php _e('Architecture advisory and AI acceleration for engineering teams. We help you choose the right LLM tech stack, integrate agentic workflows, and optimize cloud infrastructure.', 'foundnxt'); ?>
          </p>
          <ul class="svc-list">
            <li><span class="svc-check">✓</span> <span><?php _e('AI adoption roadmaps & agentic workflow design', 'foundnxt'); ?></span></li>
            <li><span class="svc-check">✓</span> <span><?php _e('Build vs. buy infrastructure & API evaluations', 'foundnxt'); ?></span></li>
            <li><span class="svc-check">✓</span> <span><?php _e('Latency, cloud spend & inference cost optimization', 'foundnxt'); ?></span></li>
          </ul>
          <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-hero-primary" style="background: #7C3AED; justify-content: center;">
            <span><?php _e('Learn About Tech Consulting', 'foundnxt'); ?></span> &rarr;
          </a>
        </article>
      </div>

      <!-- Coming Soon Waitlist Strip (Market Research & Valuation) -->
      <div class="services-coming-soon-box" id="waitlist">
        <span class="coming-soon-badge"><?php _e('Coming Soon', 'foundnxt'); ?></span>
        <h3 class="coming-soon-title"><?php _e('Market Research & Valuation Services', 'foundnxt'); ?></h3>
        <p class="coming-soon-desc">
          <?php _e('Proprietary sector benchmarks, custom market sizing, and defensible 409A valuation models are launching soon. Join the waitlist for priority access.', 'foundnxt'); ?>
        </p>

        <?php if (isset($_GET['waitlist']) && $_GET['waitlist'] === 'success'): ?>
          <div style="padding: 12px 20px; border-radius: 10px; background: rgba(16, 185, 129, 0.15); color: #10B981; font-weight: 700; margin-bottom: 12px;">
            ✓ <?php _e('You are on the priority waitlist! We will notify you first upon launch.', 'foundnxt'); ?>
          </div>
        <?php else: ?>
          <form class="coming-soon-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_waitlist_submit">
            <?php wp_nonce_field('fnx_waitlist_action', 'fnx_waitlist_nonce'); ?>
            <input type="hidden" name="waitlist_service" value="Market Research & Valuation">
            <input type="email" name="waitlist_email" class="coming-soon-input" placeholder="<?php esc_attr_e('Enter your work email…', 'foundnxt'); ?>" required>
            <button type="submit" class="btn-coming-soon">
              <?php _e('Join the waitlist', 'foundnxt'); ?> &rarr;
            </button>
          </form>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <!-- ── 7. NEWSLETTER SECTION ── -->
  <section class="hp-newsletter-section" id="newsletter">
    <div class="container">
      <div class="hp-nl-inner">
        <span class="hp-nl-badge">✉️ <?php _e('Free Weekly Executive Brief', 'foundnxt'); ?></span>
        <h2 class="hp-nl-title">
          <?php _e('One sharp business breakdown every week. No fluff.', 'foundnxt'); ?>
        </h2>
        <p class="hp-nl-sub">
          <?php _e('Join 12,000+ founders, operators, and investors getting actionable venture intelligence straight to their inbox.', 'foundnxt'); ?>
        </p>

        <form class="hp-nl-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Homepage Newsletter Signup">
          <input type="hidden" name="target_sheet" value="newsletter">
          
          <input type="email" name="lead_email" class="hp-nl-input" placeholder="<?php esc_attr_e('Enter your work email…', 'foundnxt'); ?>" required>
          <button type="submit" class="hp-nl-btn">
            <?php _e('Subscribe Free', 'foundnxt'); ?> &rarr;
          </button>
        </form>
        <span style="font-size: 0.8125rem; color: #64748B; margin-top: 14px; display: block;">🔒 <?php _e('Zero spam. Unsubscribe in one click anytime.', 'foundnxt'); ?></span>
      </div>
    </div>
  </section>

  <!-- ── 8. ABOUT BLURB (2 Lines + Link) ── -->
  <section class="hp-about-blurb-section" id="about-blurb">
    <div class="container">
      <div class="hp-about-blurb-card">
        <p class="hp-about-p">
          <?php _e('FoundNXT is an independent editorial publication dissecting high-growth startups, market shifts, and AI systems. Built for founders, operators, and investors navigating the next economic cycle.', 'foundnxt'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="hp-about-link">
          <span><?php _e('Read our story', 'foundnxt'); ?></span> &rarr;
        </a>
      </div>
    </div>
  </section>

  <!-- ── 9. FINAL CTA BAND ── -->
  <section class="hp-final-cta-section" id="final-cta">
    <div class="container">
      <h2 class="final-cta-title">
        <?php _e('Scaling a business? Let’s talk.', 'foundnxt'); ?>
      </h2>
      <p class="final-cta-sub">
        <?php _e('Book a strategic consultation with our growth and tech advisory practice.', 'foundnxt'); ?>
      </p>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-final-cta">
        <span><?php _e('Book a free 20-min call', 'foundnxt'); ?></span> &rarr;
      </a>
    </div>
  </section>

<?php else: ?>

  <!-- Standard Blog Index Fallback (for /blog/ if assigned) -->
  <div class="fnx-archive" style="padding: 60px 0;">
    <div class="container">
      <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 24px; color: var(--text);">
        <?php _e('All Insights & Articles', 'foundnxt'); ?>
      </h1>
      <?php get_template_part('template-parts/category-filter-bar'); ?>
      
      <div class="hp-insights-grid" style="margin-top: 32px;">
        <?php if (have_posts()): while (have_posts()): the_post();
          $cats  = get_the_category();
          $cat   = !empty($cats) ? $cats[0] : null;
          $cslug = $cat ? $cat->slug : 'startups-funding';
          $cname = $cat ? $cat->name : __('Article', 'foundnxt');
          $ccol  = fnx_get_category_color($cslug);
        ?>
          <article class="insight-card" style="--card-accent: <?php echo esc_attr($ccol); ?>;">
            <?php if (has_post_thumbnail()): ?>
              <a href="<?php the_permalink(); ?>" class="insight-card-thumb">
                <?php the_post_thumbnail('fnx-card', ['loading' => 'lazy']); ?>
              </a>
            <?php endif; ?>
            <div class="insight-card-body">
              <span class="insight-card-tag" style="background: <?php echo esc_attr($ccol); ?>18; color: <?php echo esc_attr($ccol); ?>;">
                <?php echo esc_html($cname); ?>
              </span>
              <h3 class="insight-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>
              <div class="insight-card-meta">
                <span><?php echo get_the_date('M j, Y'); ?></span>
                <span><?php echo fnx_read_time(); ?></span>
              </div>
            </div>
          </article>
        <?php endwhile; endif; ?>
      </div>
    </div>
  </div>

<?php endif; ?>

<!-- ── 10. FOOTER ── -->
<?php get_footer(); ?>
