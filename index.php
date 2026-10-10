<?php get_header(); ?>

<?php if (is_front_page() && is_home()): ?>
  <!-- ══════════════════════════════════
     HOMEPAGE — FOUNDNXT REBUILD
     Content-focused business intelligence & lead generation layout
══════════════════════════════════ -->

  <?php
  function fnx_get_hp_posts($args = []) {
    $defaults = ['post_status' => 'publish', 'posts_per_page' => 6, 'suppress_filters' => false];
    return get_posts(array_merge($defaults, $args));
  }
  function fnx_fmt_date($date) {
    return date_i18n('M j, Y', strtotime($date));
  }
  ?>

  <!-- ── 1. HERO SECTION (100dvh, Animated Gradient Mesh, Balanced Typography) ── -->
  <section class="hp-hero-v3">
    <div class="hp-mesh-bg" aria-hidden="true"></div>

    <div class="container">
      <div class="hp-hero-v3-inner">
        <div class="hero-pill-badge">
          <span class="hero-pill-dot" aria-hidden="true"></span>
          <span><?php _e('How to scale company in AI era', 'foundnxt'); ?></span>
        </div>

        <h1 class="hp-h1-v3">
          Think Smarter. Build Bigger. <span class="text-grad"><?php _e('Grow Faster.', 'foundnxt'); ?></span>
        </h1>

        <p class="hp-lead-v3">
          <?php _e('Actionable frameworks on startups, valuation, AI architecture, and growth strategies built for founders and leaders who want to scale without fluff.', 'foundnxt'); ?>
        </p>

        <!-- ONE Primary CTA (Newsletter "Get Access") + ONE Secondary ("Explore Articles") + Text Link ("Founder Services") -->
        <div class="hero-cta-bundle">
          <form class="hero-access-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Hero Newsletter Access">
            <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" aria-hidden="true" autocomplete="off">
            <input type="email" name="lead_email" class="hero-access-input" placeholder="<?php esc_attr_e('Enter your work email…', 'foundnxt'); ?>" required>
            <button type="submit" class="hero-access-btn"><?php _e('Get Access', 'foundnxt'); ?> →</button>
          </form>

          <div class="hero-sub-row">
            <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="hero-btn-secondary">
              <?php _e('Explore Articles', 'foundnxt'); ?> →
            </a>
            <a href="#services" class="hero-link-sub">
              <?php _e('Looking for advisory? Founder Services', 'foundnxt'); ?> →
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 2. FEATURED PLAYBOOKS (Container Queries & Dynamic Solo Layout) ── -->
  <section class="hp-featured-section defer-render">
    <div class="container">
      <div class="section-headline-row">
        <div>
          <span class="section-tag"><?php _e('Curated Intelligence', 'foundnxt'); ?></span>
          <h2><?php _e('Featured', 'foundnxt'); ?> <span class="text-grad"><?php _e('Playbooks', 'foundnxt'); ?></span></h2>
        </div>
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="hero-btn-secondary">
          <?php _e('View All', 'foundnxt'); ?> →
        </a>
      </div>

      <div class="playbooks-grid">
        <?php
        $featured_posts = fnx_get_hp_posts(['posts_per_page' => 3]);
        $is_solo = !empty($featured_posts) && count($featured_posts) < 3;

        if (!empty($featured_posts)):
          foreach ($featured_posts as $f_idx => $fp):
            $f_cats  = wp_get_post_categories($fp->ID);
            $f_cname = $f_cats ? get_cat_name($f_cats[0]) : 'Strategy';
            $f_cslug = $f_cats ? get_category($f_cats[0])->slug : 'business-strategy';
            $f_color = fnx_get_category_color($f_cslug);
            $card_class = $is_solo ? 'playbook-card playbook-card--featured-solo' : 'playbook-card';
        ?>
            <article class="<?php echo esc_attr($card_class); ?>" style="--card-accent: <?php echo esc_attr($f_color); ?>;">
              <?php if (has_post_thumbnail($fp->ID)): ?>
                <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>" class="playbook-card-thumb-wrap">
                  <?php echo get_the_post_thumbnail($fp->ID, 'fnx-card', [
                    'alt'           => esc_attr(get_the_title($fp->ID)),
                    'loading'       => ($f_idx === 0) ? 'eager' : 'lazy',
                    'fetchpriority' => ($f_idx === 0) ? 'high' : 'auto',
                    'decoding'      => 'async'
                  ]); ?>
                </a>
              <?php endif; ?>
              <div class="playbook-card-body">
                <span class="playbook-badge" style="background: <?php echo esc_attr($f_color); ?>18; color: <?php echo esc_attr($f_color); ?>;">
                  <?php echo esc_html($f_cname); ?>
                </span>
                <h3 class="playbook-title">
                  <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>"><?php echo esc_html(get_the_title($fp->ID)); ?></a>
                </h3>
                <p class="playbook-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($fp->ID), 20)); ?></p>
                <div class="playbook-meta">
                  <span><?php echo fnx_fmt_date($fp->post_date); ?></span>
                  <span>•</span>
                  <span><?php echo fnx_read_time($fp->ID); ?></span>
                </div>
              </div>
            </article>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </section>

  <!-- ── 3. "WHAT YOU'LL FIND HERE" (BENTO GRID WITH GRID-TEMPLATE-AREAS) ── -->
  <section class="hp-bento-section defer-render">
    <div class="container">
      <div class="section-headline-row">
        <div>
          <span class="section-tag"><?php _e('Knowledge Pillars', 'foundnxt'); ?></span>
          <h2><?php _e('What You’ll', 'foundnxt'); ?> <span class="text-grad"><?php _e('Find Here', 'foundnxt'); ?></span></h2>
        </div>
      </div>

      <div class="bento-grid">
        <!-- Tile 1: Startups & Funding (Large Feature Tile) -->
        <a href="<?php echo esc_url(home_url('/category/startups-funding/')); ?>" class="bento-tile bento-tile-1">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-3.05 11a22.35 22.35 0 0 1-3.95 2z"/></svg>
            </div>
            <span class="bento-number">01 / PILLAR</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Startups & Funding Playbooks', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('From pitch decks and VC term sheets to cap table models and fundraising mechanics for early-stage and growth founders.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 2: Tech & Architecture -->
        <a href="<?php echo esc_url(home_url('/category/technology-ai/')); ?>" class="bento-tile bento-tile-2">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3"/></svg>
            </div>
            <span class="bento-number">02</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Tech & AI Stack', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('Enterprise AI workflows, LLM agents, and software architecture decisions.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 3: Scaling & Strategy -->
        <a href="<?php echo esc_url(home_url('/category/business-strategy/')); ?>" class="bento-tile bento-tile-3">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <span class="bento-number">03</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Scaling Frameworks', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('SaaS operational metrics, unit economics, and team governance.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 4: Careers & Growth -->
        <a href="<?php echo esc_url(home_url('/category/marketing-growth/')); ?>" class="bento-tile bento-tile-4">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <span class="bento-number">04</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Growth Levers', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('0-to-1 customer acquisition, product-led growth, and executive hiring.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 5: AI Workflows -->
        <a href="<?php echo esc_url(home_url('/category/technology-ai/')); ?>" class="bento-tile bento-tile-5">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 16 4-4-4-4M6 8l-4 4 4 4M14.5 4l-5 16"/></svg>
            </div>
            <span class="bento-number">05</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Applied AI Systems', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('Automating business processes with cutting-edge AI architectures.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 6: Markets & Economy -->
        <a href="<?php echo esc_url(home_url('/category/markets-economy/')); ?>" class="bento-tile bento-tile-6">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <span class="bento-number">06</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Market Macro', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('Interest rates, sector cycles, and venture capital liquidity trends.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 7: Valuation & Finance -->
        <a href="<?php echo esc_url(home_url('/category/valuation-finance/')); ?>" class="bento-tile bento-tile-7">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="bento-number">07</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Valuation Guidance', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('Discounted cash flows, multiples analysis, and 409A standards.', 'foundnxt'); ?></p>
          </div>
        </a>

        <!-- Tile 8: News & Intel (Wide Tile) -->
        <a href="<?php echo esc_url(home_url('/category/news-insights/')); ?>" class="bento-tile bento-tile-8">
          <div class="bento-icon-row">
            <div class="bento-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M18 18h-8M18 10h-8"/></svg>
            </div>
            <span class="bento-number">08 / INTELLIGENCE</span>
          </div>
          <div>
            <h3 class="bento-title"><?php _e('Executive Briefs & Industry Updates', 'foundnxt'); ?></h3>
            <p class="bento-desc"><?php _e('Curated weekly dispatches distilling market signals, venture capital rounds, and scaling playbooks.', 'foundnxt'); ?></p>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- ── 4. SERVICES ("WORK WITH US" — 3 EQUAL CARDS WITH SVG ICONS & GRADIENT BORDERS) ── -->
  <section class="hp-services-section defer-render" id="services">
    <div class="container">
      <div class="section-headline-row text-center" style="justify-content: center; text-align: center; margin-bottom: 36px;">
        <div>
          <span class="section-tag"><?php _e('Advisory & Intelligence', 'foundnxt'); ?></span>
          <h2><?php _e('Work With', 'foundnxt'); ?> <span class="text-grad"><?php _e('Us', 'foundnxt'); ?></span></h2>
          <p style="color: var(--muted); margin-top: 8px;"><?php _e('Specialized founder advisory, bespoke intelligence requests, and growth partnerships.', 'foundnxt'); ?></p>
        </div>
      </div>

      <div class="services-3-grid">
        <!-- Service Card 1 -->
        <div class="service-grad-card" style="--card-accent: var(--brand);">
          <div class="service-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          </div>
          <h3 class="service-card-title"><?php _e('Market Research Request', 'foundnxt'); ?></h3>
          <p class="service-card-desc"><?php _e('Bespoke market intelligence, competitive landscape analysis, and industry trend reports built for founders and investment committees.', 'foundnxt'); ?></p>
          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Market Research Request'), home_url('/contact/'))); ?>" class="service-card-btn">
            <?php _e('Enquire Now', 'foundnxt'); ?> →
          </a>
        </div>

        <!-- Service Card 2 -->
        <div class="service-grad-card" style="--card-accent: var(--accent);">
          <div class="service-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
          </div>
          <h3 class="service-card-title"><?php _e('Valuation Guidance', 'foundnxt'); ?></h3>
          <p class="service-card-desc"><?php _e('Independent financial modeling, cap table health audits, and seed-to-growth valuation benchmarking to scale with confidence.', 'foundnxt'); ?></p>
          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Valuation Guidance'), home_url('/contact/'))); ?>" class="service-card-btn">
            <?php _e('Enquire Now', 'foundnxt'); ?> →
          </a>
        </div>

        <!-- Service Card 3 -->
        <div class="service-grad-card" style="--card-accent: var(--warm);">
          <div class="service-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <h3 class="service-card-title"><?php _e('Tech & AI Strategy', 'foundnxt'); ?></h3>
          <p class="service-card-desc"><?php _e('Architecture reviews, enterprise AI workflow integration, and build vs. buy audits for scaling software infrastructure.', 'foundnxt'); ?></p>
          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), home_url('/contact/'))); ?>" class="service-card-btn">
            <?php _e('Enquire Now', 'foundnxt'); ?> →
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 5. "WHO WE ARE" (TWO-COLUMN SPLIT) ── -->
  <section class="hp-about-split defer-render">
    <div class="container">
      <div class="about-split-grid">
        <div>
          <span class="section-tag"><?php _e('Our Mission', 'foundnxt'); ?></span>
          <h2><?php _e('Independent Intelligence for', 'foundnxt'); ?> <span class="text-grad"><?php _e('Founders & Leaders', 'foundnxt'); ?></span></h2>
          <p style="margin-block: 16px; color: var(--muted); font-size: 1.05rem;">
            <?php _e('FoundNXT is an independent publication dedicated to decoding corporate finance, startup valuation, AI engineering, and market dynamics into clean, practical frameworks.', 'foundnxt'); ?>
          </p>
          <p style="color: var(--muted); margin-bottom: 24px;">
            <?php _e('No jargon walls. No clickbait. Just rigorous frameworks to help modern founders build durable, scalable enterprises.', 'foundnxt'); ?>
          </p>
          <a href="<?php echo esc_url(home_url('/about/')); ?>" class="hero-btn-secondary">
            <?php _e('Read Our Story', 'foundnxt'); ?> →
          </a>
        </div>

        <div class="about-visual-card">
          <div class="about-quote-mark">“</div>
          <p class="about-quote-text">
            <?php _e('Where founders discover what is next in venture, strategy, and technological transformation.', 'foundnxt'); ?>
          </p>
          <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <span class="hero-pill-badge" style="margin-bottom: 0;">✓ 100% Free Intelligence</span>
            <span class="hero-pill-badge" style="margin-bottom: 0;">✓ Zero Fluff</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 6. RECENT ARTICLES (HIDE SECTION WHEN NO POSTS EXIST) ── -->
  <?php
  $recent_posts = fnx_get_hp_posts(['posts_per_page' => 6, 'offset' => 3]);
  if (!empty($recent_posts)):
  ?>
  <section class="hp-featured-section defer-render">
    <div class="container">
      <div class="section-headline-row">
        <div>
          <span class="section-tag"><?php _e('Fresh Insights', 'foundnxt'); ?></span>
          <h2><?php _e('Recent', 'foundnxt'); ?> <span class="text-grad"><?php _e('Articles', 'foundnxt'); ?></span></h2>
        </div>
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="hero-btn-secondary">
          <?php _e('View All Articles', 'foundnxt'); ?> →
        </a>
      </div>

      <div class="playbooks-grid">
        <?php
        foreach ($recent_posts as $lp):
          setup_postdata($GLOBALS['post'] =& $lp);
          get_template_part('template-parts/post-card');
        endforeach;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ── 7. TOOLS "COMING SOON" WITH DEDICATED WAITLIST FIELD ── -->
  <section class="hp-tools-waitlist-section defer-render">
    <div class="container">
      <div class="tools-waitlist-card">
        <span class="tools-coming-soon-pill">🛠️ <?php _e('Coming Soon', 'foundnxt'); ?></span>
        <h2 class="tools-waitlist-title"><?php _e('The Founder Tech & Scaling Stack', 'foundnxt'); ?></h2>
        <p class="tools-waitlist-desc">
          <?php _e('A curated directory of top-tier, free, and open-source business software for scaling companies — from valuation models to AI agents. Join the early-access waitlist.', 'foundnxt'); ?>
        </p>

        <form class="tools-waitlist-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Tools Directory Early Access Waitlist">
          <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" aria-hidden="true" autocomplete="off">
          <input type="email" name="lead_email" class="tools-waitlist-input" placeholder="<?php esc_attr_e('Enter your work email for early access…', 'foundnxt'); ?>" required>
          <button type="submit" class="tools-waitlist-btn"><?php _e('Join Waitlist', 'foundnxt'); ?> →</button>
        </form>
      </div>
    </div>
  </section>

  <!-- ── 8. NEWSLETTER (FULL-WIDTH GRADIENT BAND) ── -->
  <section class="fnx-newsletter-fullwidth defer-render">
    <div class="container">
      <div class="newsletter-fw-inner">
        <div class="newsletter-fw-text">
          <h2 class="newsletter-fw-title"><?php _e('Join 10,000+ Founders & Leaders', 'foundnxt'); ?></h2>
          <p class="newsletter-fw-sub"><?php _e('Receive our weekly briefing on startup valuation, enterprise AI shifts, and scaling frameworks. No spam, ever.', 'foundnxt'); ?></p>
        </div>
        <div class="newsletter-fw-form-wrap">
          <form class="newsletter-fw-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Fullwidth Newsletter Band">
            <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" aria-hidden="true" autocomplete="off">
            <input type="email" name="lead_email" class="newsletter-fw-input" placeholder="<?php esc_attr_e('Your work email…', 'foundnxt'); ?>" required>
            <button type="submit" class="newsletter-fw-btn"><?php _e('Subscribe Free', 'foundnxt'); ?> →</button>
          </form>
        </div>
      </div>
    </div>
  </section>





<?php else: /* Archive fallback */ ?>

  <div class="fnx-archive">
    <div class="container">
      <div class="archive-header">
        <h1 class="archive-title"><?php single_cat_title(); ?></h1>
      </div>
      <div class="posts-grid" id="posts-grid">
        <?php while (have_posts()): the_post();
          get_template_part('template-parts/post-card');
        endwhile; ?>
      </div>
    </div>
  </div>

<?php endif; ?>

<?php get_footer(); ?>