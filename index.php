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

  <!-- ── 2. HERO SECTION ── -->
  <section class="hp-block hp-hero-v2">
    <div class="hp-hero-bg" aria-hidden="true">
      <span class="hp-hero-blob hp-hero-blob--1"></span>
      <span class="hp-hero-blob hp-hero-blob--2"></span>
      <span class="hp-hero-blob hp-hero-blob--3"></span>
      <svg class="hp-hero-grid" width="100%" height="100%" preserveAspectRatio="none">
        <defs>
          <pattern id="hpDotGrid" width="28" height="28" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="1.5" fill="currentColor" />
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#hpDotGrid)" />
      </svg>
    </div>

    <div class="container">
      <div class="hp-hero-content text-center">
        <!-- Floating Topic Badges -->
        <div class="hp-hero-floating-chips" aria-hidden="true">
          <span class="hp-hero-chip chip--indigo">Business Strategy</span>
          <span class="hp-hero-chip chip--coral">Startups & Funding</span>
          <span class="hp-hero-chip chip--teal">Valuation & Finance</span>
          <span class="hp-hero-chip chip--violet">Technology & AI</span>
          <span class="hp-hero-chip chip--amber">Markets & Economy</span>
        </div>

        <span class="hp-eyebrow hp-eyebrow--nxt">
          <span class="hp-eyebrow-dot" aria-hidden="true"></span>
          <span class="hp-eyebrow-shimmer"><?php _e('Business, Markets & Technology: Explained for Founders and Leaders', 'foundnxt'); ?></span>
        </span>

        <h1 class="hp-h1-v2">
          Business, Markets &amp; Technology, <span class="hp-hl-v2">Explained.</span>
        </h1>

        <p class="hp-h1-sub-v2">
          <?php _e('Practical insights on startups, valuation, AI, marketing, and global business, built for founders and leaders who want to scale smarter.', 'foundnxt'); ?>
        </p>

        <div class="hp-hero-cta-row flex-center">
          <a href="#newsletter-section" class="btn-primary hp-hero-cta-btn">
            <?php _e('Get the Free Weekly Brief', 'foundnxt'); ?> →
          </a>
          <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-outline hp-hero-cta-btn">
            <?php _e('Explore Articles', 'foundnxt'); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>

        <p class="hp-trust-line">
          ✓ <?php _e('Free weekly insights. No spam.', 'foundnxt'); ?>
        </p>
      </div>
    </div>
  </section>

  <!-- ── 3. CATEGORY GRID (8 Colorful Cards) ── -->
  <section class="hp-block hp-categories-v2">
    <div class="container">
      <div class="hp-sec-header text-center fnx-reveal">
        <span class="hp-sec-tag"><?php _e('Explore Topics', 'foundnxt'); ?></span>
        <h2><?php _e('Browse by', 'foundnxt'); ?> <em><?php _e('Category', 'foundnxt'); ?></em></h2>
      </div>

      <div class="hp-cat-grid-v2">
        <?php
        $cats_spec = [
          [
            'name'  => __('Business & Strategy', 'foundnxt'),
            'slug'  => 'business-strategy',
            'color' => '#4F46E5',
            'desc'  => __('SaaS models, operational scaling frameworks, and corporate strategy.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>'
          ],
          [
            'name'  => __('Startups & Funding', 'foundnxt'),
            'slug'  => 'startups-funding',
            'color' => '#FF6B6B',
            'desc'  => __('Fundraising playbooks, pitch deck guides, and VC investor relations.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-3.05 11a22.35 22.35 0 0 1-3.95 2z"/></svg>'
          ],
          [
            'name'  => __('Valuation & Finance', 'foundnxt'),
            'slug'  => 'valuation-finance',
            'color' => '#14B8A6',
            'desc'  => __('Startup valuation methods, financial modeling, and cap tables.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'
          ],
          [
            'name'  => __('Markets & Economy', 'foundnxt'),
            'slug'  => 'markets-economy',
            'color' => '#F59E0B',
            'desc'  => __('Macroeconomic analysis, sector forecasts, and interest rate impacts.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>'
          ],
          [
            'name'  => __('Technology & AI', 'foundnxt'),
            'slug'  => 'technology-ai',
            'color' => '#7C3AED',
            'desc'  => __('Enterprise AI workflows, software architecture, and tech trends.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3"/></svg>'
          ],
          [
            'name'  => __('Marketing & Growth', 'foundnxt'),
            'slug'  => 'marketing-growth',
            'color' => '#EC4899',
            'desc'  => __('0-to-1 customer acquisition, technical SEO, and product-led growth.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>'
          ],
          [
            'name'  => __('Global Business', 'foundnxt'),
            'slug'  => 'global-business',
            'color' => '#0EA5E9',
            'desc'  => __('International expansion, supply chain logistics, and global trade.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>'
          ],
          [
            'name'  => __('News & Insights', 'foundnxt'),
            'slug'  => 'news-insights',
            'color' => '#10B981',
            'desc'  => __('Timely business intelligence digests and weekly executive roundups.', 'foundnxt'),
            'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M18 18h-8M18 10h-8"/></svg>'
          ],
        ];

        foreach ($cats_spec as $c_idx => $c):
          $term = get_term_by('slug', $c['slug'], 'category');
          $c_url = $term ? get_category_link($term->term_id) : home_url('/category/' . $c['slug'] . '/');
          $c_cnt = $term ? $term->count : 0;
        ?>
          <a href="<?php echo esc_url($c_url); ?>" class="hp-cat-card-v2 fnx-reveal" style="--cat-accent: <?php echo esc_attr($c['color']); ?>; --i: <?php echo (int)$c_idx; ?>;">
            <div class="hp-cat-card-icon" style="color: <?php echo esc_attr($c['color']); ?>; background: <?php echo esc_attr($c['color']); ?>15;">
              <?php echo $c['icon']; ?>
            </div>
            <h3 class="hp-cat-card-title"><?php echo esc_html($c['name']); ?></h3>
            <p class="hp-cat-card-desc"><?php echo esc_html($c['desc']); ?></p>
            <div class="hp-cat-card-foot">
              <span class="hp-cat-count"><?php echo esc_html($c_cnt); ?> Articles</span>
              <span class="hp-cat-arrow" style="color: <?php echo esc_attr($c['color']); ?>;">→</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── 4. FEATURED ARTICLES (3 Large Cards) ── -->
  <section class="hp-block hp-featured-v2">
    <div class="container">
      <div class="hp-sec-header fnx-reveal">
        <span class="hp-sec-tag"><?php _e('Top Selection', 'foundnxt'); ?></span>
        <h2><?php _e('Featured', 'foundnxt'); ?> <em><?php _e('Articles', 'foundnxt'); ?></em></h2>
      </div>

      <div class="hp-featured-grid-v2">
        <?php
        $featured_posts = fnx_get_hp_posts(['posts_per_page' => 3]);
        if (!empty($featured_posts)):
          foreach ($featured_posts as $f_idx => $fp):
            $f_cats  = wp_get_post_categories($fp->ID);
            $f_cname = $f_cats ? get_cat_name($f_cats[0]) : 'Featured';
            $f_cslug = $f_cats ? get_category($f_cats[0])->slug : 'news-insights';
            $f_color = fnx_get_category_color($f_cslug);
        ?>
            <article class="hp-feat-card-v2 fnx-reveal" style="--feat-color: <?php echo esc_attr($f_color); ?>;">
              <?php if (has_post_thumbnail($fp->ID)): ?>
                <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>" class="hp-feat-thumb">
                  <?php echo get_the_post_thumbnail($fp->ID, 'fnx-card', ['alt' => esc_attr(get_the_title($fp->ID))]); ?>
                </a>
              <?php endif; ?>
              <div class="hp-feat-content">
                <span class="hp-feat-chip" style="background: <?php echo esc_attr($f_color); ?>18; color: <?php echo esc_attr($f_color); ?>;">
                  <?php echo esc_html($f_cname); ?>
                </span>
                <h3 class="hp-feat-title">
                  <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>"><?php echo esc_html(get_the_title($fp->ID)); ?></a>
                </h3>
                <p class="hp-feat-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($fp->ID), 22)); ?></p>
                <div class="hp-feat-meta">
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

  <!-- ── 5. LATEST ARTICLES (Responsive Grid of 6 Posts) ── -->
  <section class="hp-block hp-latest-v2">
    <div class="container">
      <div class="flex-between hp-latest-header fnx-reveal">
        <div>
          <span class="hp-sec-tag"><?php _e('Fresh Intelligence', 'foundnxt'); ?></span>
          <h2><?php _e('Latest', 'foundnxt'); ?> <em><?php _e('Insights', 'foundnxt'); ?></em></h2>
        </div>
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-outline">
          <?php _e('View All Articles', 'foundnxt'); ?> →
        </a>
      </div>

      <div class="posts-grid hp-latest-grid">
        <?php
        $latest_posts = fnx_get_hp_posts(['posts_per_page' => 6]);
        foreach ($latest_posts as $lp):
          setup_postdata($GLOBALS['post'] =& $lp);
          get_template_part('template-parts/post-card');
        endforeach;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>

  <!-- ── 6. LEAD MAGNET BANNER ── -->
  <section class="hp-block hp-lead-magnet-banner">
    <div class="container">
      <div class="hp-lm-banner-inner fnx-reveal">
        <div class="hp-lm-banner-text">
          <span class="hp-lm-badge">📥 <?php _e('Free Founder Download', 'foundnxt'); ?></span>
          <h2 class="hp-lm-h2"><?php _e("The Founder's Valuation & Scaling Cheat Sheet", 'foundnxt'); ?></h2>
          <p class="hp-lm-p"><?php _e('Get the exact financial models, fundraising pitch deck checklists, and unit economics formulas top founders use to scale.', 'foundnxt'); ?></p>
        </div>
        <div class="hp-lm-banner-form-wrap">
          <form class="hp-lm-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Lead Magnet - Homepage Valuation Cheat Sheet">
            <div class="hp-lm-form-fields">
              <input type="text" name="lead_name" placeholder="<?php esc_attr_e('Your Name', 'foundnxt'); ?>" required>
              <input type="email" name="lead_email" placeholder="<?php esc_attr_e('Your Work Email', 'foundnxt'); ?>" required>
              <button type="submit" class="btn-primary hp-lm-btn"><?php _e('Send Me the Guide', 'foundnxt'); ?> →</button>
            </div>
            <p class="hp-lm-note">🔒 <?php _e('100% free. Sent directly to your inbox.', 'foundnxt'); ?></p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 7. INSIGHTS / MARKET PULSE STRIP ── -->
  <section class="hp-block hp-market-pulse">
    <div class="container">
      <div class="hp-pulse-strip fnx-reveal">
        <div class="hp-pulse-label">
          <span class="pulse-dot"></span>
          <strong><?php _e('Market Pulse', 'foundnxt'); ?></strong>
        </div>
        <div class="hp-pulse-items">
          <div class="hp-pulse-item">
            <?php _e('📈 Early-stage AI startup valuations surge as enterprise adoption hits 45%', 'foundnxt'); ?>
          </div>
          <div class="hp-pulse-item">
            <?php _e('💡 SaaS benchmarks 2026: Median Net Revenue Retention reaches 112%', 'foundnxt'); ?>
          </div>
          <div class="hp-pulse-item">
            <?php _e('🌐 Southeast Asia tech expansion accelerates with $12B in regional capital', 'foundnxt'); ?>
          </div>
          <div class="hp-pulse-item">
            <?php _e('⚡ Interest rate stabilization triggers renewed founder M&A activity', 'foundnxt'); ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 8. SERVICES / "WORK WITH US" (Enquiry-style) ── -->
  <section class="hp-block hp-services-v2" id="services">
    <div class="container">
      <div class="hp-sec-header text-center fnx-reveal">
        <span class="hp-sec-tag"><?php _e('Advisory & Intelligence', 'foundnxt'); ?></span>
        <h2><?php _e('Work With', 'foundnxt'); ?> <em><?php _e('Us', 'foundnxt'); ?></em></h2>
        <p class="hp-sec-sub"><?php _e('Specialized founder advisory, research requests, and growth partnerships.', 'foundnxt'); ?></p>
      </div>

      <div class="hp-services-grid-v2">
        <!-- Service 1 -->
        <div class="hp-service-card-v2 fnx-reveal">
          <div class="service-icon-wrap" style="color: #4F46E5; background: rgba(79,70,229,0.12);">
            🔍
          </div>
          <h3 class="service-card-h3"><?php _e('Market Research Request', 'foundnxt'); ?></h3>
          <p class="service-card-p"><?php _e('Custom market intelligence, competitive analysis, and industry trend reports built for founders and investment teams.', 'foundnxt'); ?></p>
          <a href="#contact" data-service="Market Research Request" class="btn-outline service-enquire-btn"><?php _e('Enquire', 'foundnxt'); ?> →</a>
        </div>

        <!-- Service 2 -->
        <div class="hp-service-card-v2 fnx-reveal">
          <div class="service-icon-wrap" style="color: #14B8A6; background: rgba(20,184,166,0.12);">
            📊
          </div>
          <h3 class="service-card-h3"><?php _e('Valuation Guidance', 'foundnxt'); ?></h3>
          <p class="service-card-p"><?php _e('Independent financial modeling, cap table health checks, and 409A/fundraising valuation guidance.', 'foundnxt'); ?></p>
          <a href="#contact" data-service="Valuation Guidance" class="btn-outline service-enquire-btn"><?php _e('Enquire', 'foundnxt'); ?> →</a>
        </div>

        <!-- Service 3 -->
        <div class="hp-service-card-v2 fnx-reveal">
          <div class="service-icon-wrap" style="color: #7C3AED; background: rgba(124,58,237,0.12);">
            ⚡
          </div>
          <h3 class="service-card-h3"><?php _e('Tech & AI Strategy', 'foundnxt'); ?></h3>
          <p class="service-card-p"><?php _e('Architecture reviews, AI workflow automation roadmap, and technical build vs. buy decision audits.', 'foundnxt'); ?></p>
          <a href="#contact" data-service="Tech & AI Strategy" class="btn-outline service-enquire-btn"><?php _e('Enquire', 'foundnxt'); ?> →</a>
        </div>

        <!-- Service 4 -->
        <div class="hp-service-card-v2 fnx-reveal">
          <div class="service-icon-wrap" style="color: #EC4899; background: rgba(236,72,153,0.12);">
            🚀
          </div>
          <h3 class="service-card-h3"><?php _e('Marketing & Growth Help', 'foundnxt'); ?></h3>
          <p class="service-card-p"><?php _e('Topical SEO cluster audits, 0-to-1 customer acquisition strategies, and GTM positioning for scaling companies.', 'foundnxt'); ?></p>
          <a href="#contact" data-service="Marketing Help" class="btn-outline service-enquire-btn"><?php _e('Enquire', 'foundnxt'); ?> →</a>
        </div>

        <!-- Service 5 -->
        <div class="hp-service-card-v2 fnx-reveal">
          <div class="service-icon-wrap" style="color: #F59E0B; background: rgba(245,158,11,0.12);">
            🤝
          </div>
          <h3 class="service-card-h3"><?php _e('Partnerships & Sponsored Content', 'foundnxt'); ?></h3>
          <p class="service-card-p"><?php _e('Reach thousands of founders and tech leaders through dedicated newsletter sponsorships and editorial partnerships.', 'foundnxt'); ?></p>
          <a href="#contact" data-service="Partnerships" class="btn-outline service-enquire-btn"><?php _e('Enquire', 'foundnxt'); ?> →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 9. ABOUT SNIPPET SECTION ── -->
  <section class="hp-block hp-about-snippet">
    <div class="container">
      <div class="hp-about-snippet-card fnx-reveal">
        <span class="hp-about-badge">⚡ <?php _e('About FoundNXT', 'foundnxt'); ?></span>
        <h2 class="hp-about-h2"><?php _e('Independent Intelligence for Founders & Leaders', 'foundnxt'); ?></h2>
        <p class="hp-about-p">
          <?php _e('FoundNXT is an independent business and technology publication helping founders and leaders understand markets, technology, and growth.', 'foundnxt'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn-outline">
          <?php _e('Learn More About FoundNXT', 'foundnxt'); ?> →
        </a>
      </div>
    </div>
  </section>

  <!-- ── 10. TOOLS COMING SOON ── -->
  <section class="hp-block hp-tools-coming-soon">
    <div class="container">
      <div class="hp-tools-card fnx-reveal">
        <span class="hp-tools-badge">🛠️ <?php _e('Coming Soon', 'foundnxt'); ?></span>
        <h2 class="hp-tools-h2"><?php _e('Founder Tools & Calculators', 'foundnxt'); ?></h2>
        <p class="hp-tools-sub"><?php _e('Interactive decision-making tools built specifically for founders and operators:', 'foundnxt'); ?></p>
        
        <div class="hp-tools-list">
          <div class="hp-tool-item">
            <span class="tool-icon">🧮</span>
            <strong>Startup Valuation Calculator</strong>
          </div>
          <div class="hp-tool-item">
            <span class="tool-icon">🤖</span>
            <strong>AI Cost Savings Calculator</strong>
          </div>
          <div class="hp-tool-item">
            <span class="tool-icon">📊</span>
            <strong>Market Size Estimator</strong>
          </div>
        </div>

        <form class="hp-tools-access-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Tools Early Access Sign Up">
          <div class="tools-input-wrap">
            <input type="email" name="lead_email" placeholder="<?php esc_attr_e('Enter your work email for early access...', 'foundnxt'); ?>" required>
            <button type="submit" class="btn-primary"><?php _e('Get Early Access', 'foundnxt'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- ── 11. NEWSLETTER SECTION ── -->
  <section class="hp-block hp-newsletter-section" id="newsletter-section">
    <div class="container">
      <div class="hp-newsletter-card fnx-reveal">
        <div class="hp-newsletter-content">
          <span class="hp-nl-badge">✉️ <?php _e('Weekly Brief', 'foundnxt'); ?></span>
          <h2 class="hp-nl-h2"><?php _e('Get the sharpest business insights every week', 'foundnxt'); ?></h2>
          <p class="hp-nl-sub"><?php _e('Practical playbooks on business strategy, valuation, AI, marketing, and global expansion delivered every Monday morning.', 'foundnxt'); ?></p>
          
          <form class="hp-nl-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Homepage Newsletter Signup">
            <div class="nl-input-wrap">
              <input type="email" name="lead_email" placeholder="<?php esc_attr_e('Enter your founder email...', 'foundnxt'); ?>" required>
              <button type="submit" class="btn-primary"><?php _e('Subscribe Free', 'foundnxt'); ?> →</button>
            </div>
            <p class="nl-privacy">✓ <?php _e('Free forever. No spam. Unsubscribe in one click.', 'foundnxt'); ?></p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ── 12. UNIFIED CONTACT FORM SECTION (Target for Enquiry Buttons) ── -->
  <section class="hp-block hp-contact-section" id="contact">
    <div class="container container-narrow">
      <div class="hp-contact-box fnx-reveal">
        <div class="hp-contact-head text-center">
          <h2><?php _e('Get in Touch with', 'foundnxt'); ?> <em><?php _e('FoundNXT', 'foundnxt'); ?></em></h2>
          <p><?php _e('Tell us about your requirement — our advisory team replies within 24–48 hours.', 'foundnxt'); ?></p>
        </div>

        <?php
        if (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'success') {
          echo '<div class="hp-contact-alert hp-contact-alert--ok" role="status">✓ ' . esc_html__('Thank you! Your message has been received. We will get back to you shortly.', 'foundnxt') . '</div>';
        }
        ?>

        <form class="hp-lead-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>#contact">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Contact Form Submission">
          
          <!-- Honeypot -->
          <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" autocomplete="off">

          <div class="form-row grid-2">
            <div class="form-group">
              <label for="lead_name"><?php _e('Name *', 'foundnxt'); ?></label>
              <input type="text" id="lead_name" name="lead_name" placeholder="<?php esc_attr_e('Jordan Lee', 'foundnxt'); ?>" required>
            </div>
            <div class="form-group">
              <label for="lead_email"><?php _e('Work Email *', 'foundnxt'); ?></label>
              <input type="email" id="lead_email" name="lead_email" placeholder="<?php esc_attr_e('jordan@company.com', 'foundnxt'); ?>" required>
            </div>
          </div>

          <div class="form-row grid-2">
            <div class="form-group">
              <label for="lead_company"><?php _e('Company / Website (Optional)', 'foundnxt'); ?></label>
              <input type="text" id="lead_company" name="lead_company" placeholder="<?php esc_attr_e('https://yourcompany.com', 'foundnxt'); ?>">
            </div>
            <div class="form-group">
              <label for="lead_stage"><?php _e('Company Stage or Size', 'foundnxt'); ?></label>
              <select id="lead_stage" name="lead_stage">
                <option value="Early Stage / Seed"><?php _e('Early Stage / Seed', 'foundnxt'); ?></option>
                <option value="Growth / Series A+"><?php _e('Growth / Series A+', 'foundnxt'); ?></option>
                <option value="Scaleup"><?php _e('Scaleup', 'foundnxt'); ?></option>
                <option value="Enterprise / Established"><?php _e('Enterprise / Established', 'foundnxt'); ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="lead_help"><?php _e('What do you need help with?', 'foundnxt'); ?></label>
            <select id="lead_help" name="lead_help">
              <option value="Market Research Request"><?php _e('Market Research Request', 'foundnxt'); ?></option>
              <option value="Valuation Guidance"><?php _e('Valuation Guidance', 'foundnxt'); ?></option>
              <option value="Tech & AI Strategy"><?php _e('Tech & AI Strategy', 'foundnxt'); ?></option>
              <option value="Marketing Help"><?php _e('Marketing & Growth Help', 'foundnxt'); ?></option>
              <option value="Partnerships"><?php _e('Partnerships & Sponsored Content', 'foundnxt'); ?></option>
              <option value="Other"><?php _e('Other Inquiry', 'foundnxt'); ?></option>
            </select>
          </div>

          <div class="form-group">
            <label for="lead_message"><?php _e('Message *', 'foundnxt'); ?></label>
            <textarea id="lead_message" name="lead_message" rows="4" placeholder="<?php esc_attr_e('Tell us about your company, requirements, or questions...', 'foundnxt'); ?>" required></textarea>
          </div>

          <div class="form-group form-checkbox-group">
            <label class="checkbox-label">
              <input type="checkbox" name="lead_consent" required checked>
              <span><?php _e('I consent to FoundNXT processing my details to respond to my enquiry.', 'foundnxt'); ?></span>
            </label>
          </div>

          <button type="submit" class="btn-primary form-submit-btn">
            <?php _e('Submit Enquiry', 'foundnxt'); ?> →
          </button>
        </form>
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