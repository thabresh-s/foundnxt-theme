<?php get_header(); ?>

<?php if (is_front_page() && is_home()): ?>
  <!-- ══════════════════════════════════
     HOMEPAGE — FOUNDNXT DRAFT
     Server-side rendered post lists & conversion layout
══════════════════════════════════ -->

  <?php
  function fnx_get_posts_data($args = [])
  {
    $defaults = ['post_status' => 'publish', 'posts_per_page' => 6, 'suppress_filters' => false];
    return get_posts(array_merge($defaults, $args));
  }
  function fnx_get_cats()
  {
    $cats = get_categories(['orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false, 'number' => 12]);
    if (empty($cats)) {
      // Fallback categories for crawlers/visitors
      return [
        (object)['term_id' => 1, 'name' => 'Startups', 'count' => 1, 'slug' => 'startups'],
        (object)['term_id' => 2, 'name' => 'Tech', 'count' => 1, 'slug' => 'technology'],
        (object)['term_id' => 3, 'name' => 'Scaling', 'count' => 1, 'slug' => 'scaling'],
        (object)['term_id' => 4, 'name' => 'Careers', 'count' => 1, 'slug' => 'careers'],
      ];
    }
    return $cats;
  }
  function fnx_fmt_date($date)
  {
    return date_i18n('M j, Y', strtotime($date));
  }
  ?>

  <!-- ── BLOCK 1: INTRO HERO ── -->
  <section class="hp-block hp-intro">

    <div class="hp-hero-bg" aria-hidden="true">
      <span class="hp-hero-blob hp-hero-blob--1"></span>
      <span class="hp-hero-blob hp-hero-blob--2"></span>
      <span class="hp-hero-blob hp-hero-blob--3"></span>
      <svg class="hp-hero-grid" width="100%" height="100%" preserveAspectRatio="none">
        <defs>
          <pattern id="hpDotGrid" width="26" height="26" patternUnits="userSpaceOnUse">
            <circle cx="1.5" cy="1.5" r="1.5" fill="currentColor" />
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#hpDotGrid)" />
      </svg>
    </div>

    <div class="container">
      <div class="hp-hero-split">

        <!-- ══ LEFT: Headline + Target Audience + Inline Capture ══ -->
        <div class="hp-hero-left">
          <span class="hp-eyebrow hp-eyebrow--nxt">
            <span class="hp-eyebrow-dot" aria-hidden="true"></span>
            <span class="hp-eyebrow-shimmer"><?php _e("Where founders find what's next.", 'foundnxt'); ?></span>
          </span>

          <h1 class="hp-h1">
            <span class="hp-h1-line1">
              <span class="hp-word hp-w1">Think</span> <span class="hp-word hp-w2">Smarter.</span>
              <span class="hp-hl hp-hl1">Build</span> <span class="hp-word hp-w3">Bigger.</span>
              <span class="hp-word hp-w4">Grow</span> <span class="hp-hl hp-hl2">Faster.</span>
            </span>
          </h1>

          <p class="hp-h1-sub">
            <?php _e('Actionable playbooks, fundraising guides, and scaling frameworks for early-stage founders —', 'foundnxt'); ?>
            <span class="hp-sub-hl"><?php _e('build faster and scale smarter without the guesswork.', 'foundnxt'); ?></span>
          </p>

          <!-- Inline Newsletter Capture (Hero Area) -->
          <form class="hp-hero-inline-subscribe" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_newsletter_submit">
            <div class="hp-hero-inline-wrap">
              <input type="email" name="fnx_email" placeholder="<?php esc_attr_e('Enter your founder email...', 'foundnxt'); ?>" required>
              <button type="submit" class="btn-primary"><?php _e('Get Access', 'foundnxt'); ?> →</button>
            </div>
            <p class="hp-hero-inline-note">✓ <?php _e('Free weekly growth briefs. No spam.', 'foundnxt'); ?></p>
          </form>

          <!-- Primary CTAs -->
          <div class="hp-hero-cta-row">
            <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-primary hp-hero-cta-main">
              <?php _e('Explore Articles', 'foundnxt'); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <a href="<?php echo esc_url(home_url('/#services')); ?>" class="btn-outline hp-hero-cta-main">
              <?php _e('Founder Services', 'foundnxt'); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
          </div>
        </div><!-- /.hp-hero-left -->

        <!-- ══ RIGHT: Modern Contact Form ══ -->
        <div class="hp-hero-right">
          <div class="hp-contact-card" id="hp-contact-form">

            <div class="hp-contact-card-head">
              <span class="hp-contact-card-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              </span>
              <div>
                <h2 class="hp-contact-card-title"><?php _e("Let's Talk Growth", 'foundnxt'); ?></h2>
                <p class="hp-contact-card-sub"><?php _e("Tell us what you're building — we'll reply within 24–48 hrs.", 'foundnxt'); ?></p>
              </div>
            </div>

            <?php
            if (isset($_GET['fnx_contact'])) {
              if ($_GET['fnx_contact'] === 'success') {
                echo '<div class="hp-contact-alert hp-contact-alert--ok" role="status">' . esc_html__('Thanks! Your message has been sent — we\'ll be in touch soon.', 'foundnxt') . '</div>';
              } elseif ($_GET['fnx_contact'] === 'error') {
                echo '<div class="hp-contact-alert hp-contact-alert--err" role="alert">' . esc_html__('Something went wrong. Please check your details and try again.', 'foundnxt') . '</div>';
              }
            }
            ?>

            <form class="hp-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>#hp-contact-form">
              <input type="hidden" name="action" value="fnx_homepage_contact">
              <?php wp_nonce_field('fnx_homepage_contact', 'fnx_homepage_contact_nonce'); ?>
              
              <!-- Honeypot -->
              <div class="hp-contact-hp" aria-hidden="true">
                <label for="hp-website">Website</label>
                <input type="text" id="hp-website" name="hp_website" tabindex="-1" autocomplete="off">
              </div>

              <div class="hp-contact-field">
                <label for="hp-contact-name"><?php _e('Name', 'foundnxt'); ?></label>
                <input type="text" id="hp-contact-name" name="hp_contact_name" placeholder="<?php esc_attr_e('Jordan Lee', 'foundnxt'); ?>" required>
              </div>

              <div class="hp-contact-field">
                <label for="hp-contact-email"><?php _e('Email', 'foundnxt'); ?></label>
                <input type="email" id="hp-contact-email" name="hp_contact_email" placeholder="<?php esc_attr_e('you@company.com', 'foundnxt'); ?>" required>
              </div>

              <div class="hp-contact-field">
                <label for="hp-contact-website"><?php _e('Website (Optional)', 'foundnxt'); ?></label>
                <input type="url" id="hp-contact-website" name="hp_contact_site" placeholder="<?php esc_attr_e('https://yourcompany.com', 'foundnxt'); ?>">
              </div>

              <div class="hp-contact-field">
                <label for="hp-contact-help"><?php _e('What do you need help with?', 'foundnxt'); ?></label>
                <select id="hp-contact-help" name="hp_contact_help">
                  <option value="Product & Tech Strategy"><?php _e('Product & Tech Strategy', 'foundnxt'); ?></option>
                  <option value="Valuation Guidance"><?php _e('Valuation Guidance', 'foundnxt'); ?></option>
                  <option value="Investor Introductions"><?php _e('Investor Introductions', 'foundnxt'); ?></option>
                  <option value="Content & Partnerships"><?php _e('Content & Partnerships', 'foundnxt'); ?></option>
                  <option value="Other Inquiry"><?php _e('Other Inquiry', 'foundnxt'); ?></option>
                </select>
              </div>

              <div class="hp-contact-field">
                <label for="hp-contact-message"><?php _e('Message', 'foundnxt'); ?></label>
                <textarea id="hp-contact-message" name="hp_contact_message" rows="3" placeholder="<?php esc_attr_e('Describe your startup stage, requirement, or question…', 'foundnxt'); ?>" required></textarea>
              </div>

              <button type="submit" class="btn-primary hp-contact-submit">
                <?php _e('Send Message', 'foundnxt'); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>

              <p class="hp-contact-privacy"><?php _e("We'll only use these details to respond to your message.", 'foundnxt'); ?></p>
            </form>

          </div><!-- /.hp-contact-card -->
        </div><!-- /.hp-hero-right -->

      </div><!-- /.hp-hero-split -->
    </div>
  </section>

  <!-- ── BLOCK 2: FEATURED / POPULAR ARTICLES ── -->
  <section class="hp-block hp-featured">
    <div class="container">
      <div class="hp-sec-header fnx-reveal">
        <span class="hp-sec-v2-tag"><?php _e('Must Read', 'foundnxt'); ?></span>
        <h2><?php _e('Featured', 'foundnxt'); ?> <em><?php _e('Playbooks', 'foundnxt'); ?></em></h2>
        <hr class="hp-rule">
      </div>

      <div class="hp-featured-grid">
        <?php
        $featured_posts = fnx_get_posts_data(['posts_per_page' => 3]);
        if (!empty($featured_posts)):
          foreach ($featured_posts as $fp):
        ?>
            <a class="hp-feat-card fnx-reveal" href="<?php echo esc_url(get_permalink($fp->ID)); ?>">
              <div class="hp-feat-body">
                <span class="hp-feat-badge">🔥 <?php _e('Featured', 'foundnxt'); ?></span>
                <h3 class="hp-feat-title"><?php echo esc_html(get_the_title($fp->ID)); ?></h3>
                <p class="hp-feat-desc"><?php echo esc_html(wp_trim_words(get_the_excerpt($fp->ID), 18)); ?></p>
                <div class="hp-feat-foot">
                  <span><?php echo fnx_fmt_date($fp->post_date); ?></span>
                  <span class="hp-feat-arrow">Read Article →</span>
                </div>
              </div>
            </a>
        <?php
          endforeach;
        else:
        ?>
          <div class="hp-feat-card fnx-reveal">
            <div class="hp-feat-body">
              <span class="hp-feat-badge">🚀 <?php _e('Startups', 'foundnxt'); ?></span>
              <h3 class="hp-feat-title"><?php _e('Building Product-Market Fit in 2026', 'foundnxt'); ?></h3>
              <p class="hp-feat-desc"><?php _e('How modern founders validate ideas, run feedback loops, and scale early user adoption.', 'foundnxt'); ?></p>
              <div class="hp-feat-foot">
                <span><?php echo date('M j, Y'); ?></span>
                <span class="hp-feat-arrow">Read Article →</span>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ── BLOCK 3: WHAT YOU'LL FIND HERE — Category Roadmap (SVG Icons) ── -->
  <section class="hp-block hp-pillars">
    <div class="container">

      <div class="hp-sec-header hp-pillars-header fnx-reveal">
        <h2><?php _e("What You'll", 'foundnxt'); ?> <em><?php _e('Find Here', 'foundnxt'); ?></em></h2>
        <hr class="hp-rule">
      </div>

      <div class="hp-roadmap">
        <?php
        $pillars = [
          [
            'svg'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-3.05 11a22.35 22.35 0 0 1-3.95 2z"/></svg>',
            'title' => __('Startups', 'foundnxt'),
            'desc'  => __('Real playbooks on ideation, fundraising, product-market fit, and the mistakes that kill startups early.', 'foundnxt'),
            'url'   => home_url('/category/startups/'),
            'accent'=> '#4f46e5',
          ],
          [
            'svg'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3"/></svg>',
            'title' => __('Tech', 'foundnxt'),
            'desc'  => __('The shifts in tools, AI, and infrastructure that are quietly reshaping how companies build and compete.', 'foundnxt'),
            'url'   => home_url('/category/technology/'),
            'accent'=> '#2563eb',
          ],
          [
            'svg'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
            'title' => __('Scaling', 'foundnxt'),
            'desc'  => __('What breaks when you go from 10 to 100 to 1,000 — and how the best teams rebuild without losing speed.', 'foundnxt'),
            'url'   => home_url('/category/scaling/'),
            'accent'=> '#0891b2',
          ],
          [
            'svg'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
            'title' => __('Careers & Entrepreneurship', 'foundnxt'),
            'desc'  => __("Career moves, founder journeys, and honest takes on what it actually takes to bet on yourself.", 'foundnxt'),
            'url'   => home_url('/category/careers/'),
            'accent'=> '#d97706',
          ],
        ];
        $total = count($pillars);
        ?>
        <?php foreach ($pillars as $i => $p): ?>
          <div class="hp-roadmap-item" style="--pillar-accent:<?php echo esc_attr($p['accent']); ?>; --i:<?php echo (int) $i; ?>;">
            <div class="hp-roadmap-node-col">
              <a class="hp-roadmap-dot-wrap" href="<?php echo esc_url($p['url']); ?>" aria-label="<?php echo esc_attr($p['title']); ?>">
                <span class="hp-roadmap-dot" aria-hidden="true"><?php echo $p['svg']; ?></span>
                <span class="hp-roadmap-step" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
              </a>
              <?php if ($i < $total - 1): ?>
                <span class="hp-roadmap-track" aria-hidden="true"></span>
              <?php endif; ?>
            </div>
            <div class="hp-roadmap-content">
              <h3 class="hp-roadmap-title">
                <a href="<?php echo esc_url($p['url']); ?>"><?php echo esc_html($p['title']); ?></a>
                <span class="hp-roadmap-arrow" aria-hidden="true">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
              </h3>
              <p class="hp-roadmap-desc"><?php echo esc_html($p['desc']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div><!-- /.hp-roadmap -->

    </div>
  </section>

  <!-- ── BLOCK 4: FOUNDER SERVICES ── -->
  <section class="hp-block hp-services" id="services">
    <div class="container">
      <div class="hp-sec-header fnx-reveal">
        <span class="hp-sec-v2-tag"><?php _e('Tailored Advisory', 'foundnxt'); ?></span>
        <h2><?php _e('Services for', 'foundnxt'); ?> <em><?php _e('Founders', 'foundnxt'); ?></em></h2>
        <p class="hp-services-sub"><?php _e('Hands-on guidance and network access to help you execute faster.', 'foundnxt'); ?></p>
        <hr class="hp-rule">
      </div>

      <div class="hp-services-grid">
        <div class="hp-service-card fnx-reveal">
          <div class="hp-service-icon">💡</div>
          <h3 class="hp-service-title"><?php _e('Product & Tech Strategy', 'foundnxt'); ?></h3>
          <p class="hp-service-desc"><?php _e('Architecture reviews, tech stack selection, and AI workflow integration built for scalable growth.', 'foundnxt'); ?></p>
          <a href="#hp-contact-form" class="btn-outline hp-service-btn"><?php _e('Request Strategy Review', 'foundnxt'); ?> →</a>
        </div>

        <div class="hp-service-card fnx-reveal">
          <div class="hp-service-icon">📊</div>
          <h3 class="hp-service-title"><?php _e('Valuation & Pitch Guidance', 'foundnxt'); ?></h3>
          <p class="hp-service-desc"><?php _e('Pitch deck feedback, financial modeling, and valuation benchmarking before your seed round.', 'foundnxt'); ?></p>
          <a href="#hp-contact-form" class="btn-outline hp-service-btn"><?php _e('Get Valuation Help', 'foundnxt'); ?> →</a>
        </div>

        <div class="hp-service-card fnx-reveal">
          <div class="hp-service-icon">🤝</div>
          <h3 class="hp-service-title"><?php _e('Investor Introductions', 'foundnxt'); ?></h3>
          <p class="hp-service-desc"><?php _e('Warm introductions to active angel investors and seed VCs aligned with your industry.', 'foundnxt'); ?></p>
          <a href="#hp-contact-form" class="btn-outline hp-service-btn"><?php _e('Explore Introductions', 'foundnxt'); ?> →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── BLOCK 5: WHO WE ARE (Editorial Blurb) ── -->
  <section class="hp-block hp-about-blurb">
    <div class="container">
      <div class="hp-about-card fnx-reveal">
        <div class="hp-about-badge">⚡ <?php _e('Who We Are', 'foundnxt'); ?></div>
        <h2 class="hp-about-heading"><?php _e('Built by builders, for builders.', 'foundnxt'); ?></h2>
        <p class="hp-about-text">
          <?php _e('FoundNXT is an independent editorial & advisory platform covering startup fundraising, emerging technology, scaling ops, and founder career paths.', 'foundnxt'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn-outline hp-about-cta"><?php _e('Read Our Story & Team', 'foundnxt'); ?> →</a>
      </div>
    </div>
  </section>

  <!-- ── BLOCK 6: RECENT POSTS BENTO GRID ── -->
  <section class="hp-block hp-recent">
    <div class="container">

      <!-- Section Header -->
      <div class="hp-sec-v2 fnx-reveal">
        <div class="hp-sec-v2-left">
          <span class="hp-sec-v2-tag"><?php _e('Fresh off the press', 'foundnxt'); ?></span>
          <h2 class="hp-sec-v2-title"><?php _e('Recent', 'foundnxt'); ?> <em><?php _e('Articles', 'foundnxt'); ?></em></h2>
        </div>
        <a class="hp-sec-v2-link" href="<?php echo esc_url(home_url('/articles/')); ?>">
          <?php _e('View All', 'foundnxt'); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>

      <?php
      $recent = fnx_get_posts_data(['posts_per_page' => 6]);
      $cat_cache = [];
      foreach (get_categories(['number' => 50, 'hide_empty' => false]) as $c)
        $cat_cache[$c->term_id] = $c->name;
      $rc_accents = ['#4f46e5','#2563eb','#7c3aed','#d97706','#e11d48','#0891b2'];
      ?>

      <!-- Bento grid: 6 cards in magazine layout -->
      <div class="hp-bento">

        <?php foreach ($recent as $idx => $p):
          $pcat = wp_get_post_categories($p->ID);
          $cname = ($pcat && isset($cat_cache[$pcat[0]])) ? $cat_cache[$pcat[0]] : 'Article';
          $accent = $rc_accents[$idx % count($rc_accents)];
          $is_hero = ($idx === 0);
          $card_class = 'hp-bcard' . ($is_hero ? ' hp-bcard--hero' : '') . ' hp-bcard--idx-' . $idx;
        ?>
          <a class="<?php echo $card_class; ?> fnx-reveal" href="<?php echo esc_url(get_permalink($p->ID)); ?>"
             style="--rc-accent:<?php echo $accent; ?>; --i:<?php echo (int) $idx; ?>;">
            <?php if (has_post_thumbnail($p->ID)): ?>
              <div class="hp-bcard-img">
                <?php echo get_the_post_thumbnail($p->ID, $is_hero ? 'fnx-card' : 'fnx-thumb', [
                  'loading' => $idx === 0 ? 'eager' : 'lazy',
                  'alt' => esc_attr(get_the_title($p->ID))
                ]); ?>
                <div class="hp-bcard-img-overlay"></div>
              </div>
            <?php else: ?>
              <div class="hp-bcard-img hp-bcard-img--empty">
                <span class="hp-bcard-emoji">📰</span>
              </div>
            <?php endif; ?>

            <div class="hp-bcard-body">
              <div class="hp-bcard-top">
                <span class="hp-bcard-cat"><?php echo esc_html($cname); ?></span>
                <span class="hp-bcard-num"><?php echo str_pad($idx + 1, 2, '0', STR_PAD_LEFT); ?></span>
              </div>
              <div class="hp-bcard-title"><?php echo esc_html(get_the_title($p->ID)); ?></div>
              <?php if ($is_hero): ?>
                <p class="hp-bcard-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($p->ID), 20)); ?></p>
              <?php endif; ?>
              <div class="hp-bcard-foot">
                <span class="hp-bcard-date"><?php echo fnx_fmt_date($p->post_date); ?></span>
                <span class="hp-bcard-read">Read <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
              </div>
            </div>
            <div class="hp-bcard-accent-bar"></div>
          </a>
        <?php endforeach;
        wp_reset_postdata(); ?>

      </div><!-- /.hp-bento -->

      <!-- All Articles Section -->
      <div class="hp-all-hdr-v2">
        <div class="hp-all-hdr-v2-inner">
          <div class="hp-all-hdr-v2-line"></div>
          <h2 class="hp-all-hdr-v2-title">
            <span class="hp-all-hdr-v2-icon">📰</span>
            <?php _e('All Articles', 'foundnxt'); ?>
          </h2>
          <div class="hp-all-hdr-v2-line"></div>
        </div>
      </div>

      <div class="posts-grid" id="posts-grid">
        <?php $all = fnx_get_posts_data(['posts_per_page' => 12]);
        foreach ($all as $p):
          setup_postdata($GLOBALS['post'] =& $p);
          get_template_part('template-parts/post-card');
        endforeach;
        wp_reset_postdata(); ?>
      </div>

      <?php
      $total_posts = wp_count_posts()->publish;
      $max_pages   = ceil($total_posts / 12);
      ?>
      <nav class="hp-pagination" id="hp-pagination"
           data-max="<?php echo $max_pages; ?>"
           data-cat="0"
           data-total="<?php echo $total_posts; ?>"
           aria-label="Articles pagination">
        <span class="hp-page-info-ssr">Page 1 of <?php echo max(1, $max_pages); ?></span>
      </nav>
      <p class="hp-pagination-info" id="hp-pagination-info"></p>

    </div>
  </section>

  <!-- ── BLOCK 7: COMING SOON — Tools Built for Builders ── -->
  <section class="hp-block hp-tools-teaser">
    <div class="container">
      <div class="hp-tools-teaser-card fnx-reveal">
        <span class="hp-tools-teaser-badge"><?php _e('Coming Soon', 'foundnxt'); ?></span>
        <h2 class="hp-tools-teaser-h2"><?php _e('Tools Built for Builders', 'foundnxt'); ?></h2>
        <p class="hp-tools-teaser-p">
          <?php _e("We're not stopping at content. FoundNXT is building a suite of tools to help you go from idea to execution — startup trackers, growth calculators, career planners, and more. Be the first to try them.", 'foundnxt'); ?>
        </p>
        <a href="#hp-contact-form" class="btn-primary hp-tools-teaser-cta">
          <?php _e('Get early access', 'foundnxt'); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>
    </div>
  </section>

<?php else: /* Archive fallback */ ?>

  <div class="fnx-archive">
    <div class="container">
      <div class="archive-header">
        <div class="archive-title-wrap">
          <h1 class="archive-title"><?php single_cat_title(); ?></h1>
        </div>
      </div>
      <div class="archive-layout">
        <div class="archive-posts">
          <div class="posts-grid" id="posts-grid">
            <?php while (have_posts()): the_post();
              get_template_part('template-parts/post-card');
            endwhile; ?>
          </div>
        </div>
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>

<?php endif; ?>

<?php get_footer(); ?>