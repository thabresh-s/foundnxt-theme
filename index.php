<?php get_header(); ?>

<?php if (is_front_page() && is_home()): ?>
  <!-- ══════════════════════════════════
     HOMEPAGE — mirrors /blog/ content
     Blocks 1–4 rendered server-side
══════════════════════════════════ -->

  <?php
  // Pull REST data helpers
  function fnx_get_posts_data($args = [])
  {
    $defaults = ['post_status' => 'publish', 'posts_per_page' => 6, 'suppress_filters' => false];
    return get_posts(array_merge($defaults, $args));
  }
  function fnx_get_cats()
  {
    return get_categories(['orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true, 'number' => 9]);
  }
  function fnx_fmt_date($date)
  {
    return date_i18n('M j, Y', strtotime($date));
  }
  ?>

  <!-- ── BLOCK 1: INTRO HERO ── -->
  <section class="hp-block hp-intro">

    <!-- Animated decorative background: gradient blobs + dot grid + line-art illustration -->
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
      <svg class="hp-hero-illus-chart" width="220" height="140" viewBox="0 0 220 140" fill="none">
        <path class="hp-hero-illus-line" d="M6 118 C 40 118, 46 70, 76 70 S 110 30, 140 30 S 170 90, 196 44"
          stroke="url(#hpChartGrad)" stroke-width="3" stroke-linecap="round" fill="none" />
        <defs>
          <linearGradient id="hpChartGrad" x1="0" y1="0" x2="220" y2="0" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#4f46e5" />
            <stop offset="100%" stop-color="#0891b2" />
          </linearGradient>
        </defs>
        <circle class="hp-hero-illus-dot" cx="196" cy="44" r="5" fill="#0891b2" />
      </svg>
    </div>

    <div class="container">
      <div class="hp-hero-split">

        <!-- ══ LEFT: Headline + Newsletter ══ -->
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

          <p class="hp-h1-sub"><?php _e('Everything you need to build, scale, and grow —', 'foundnxt'); ?> <span
              class="hp-sub-hl"><?php _e('before everyone else figures it out.', 'foundnxt'); ?></span></p>

          <!-- ── Primary CTAs ── -->
          <div class="hp-hero-cta-row">
            <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-primary hp-hero-cta-main">
              <?php _e('Explore Articles', 'foundnxt'); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" class="btn-outline hp-hero-cta-main">
              <?php _e('Case Studies', 'foundnxt'); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
          </div>
        </div><!-- /.hp-hero-left -->

        <!-- ══ RIGHT: Modern Contact Form ══ -->
        <div class="hp-hero-right">
          <div class="hp-contact-card" id="hp-contact-form">

            <div class="hp-contact-card-head">
              <span class="hp-contact-card-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              </span>
              <div>
                <h2 class="hp-contact-card-title"><?php _e("Let's Talk Growth", 'foundnxt'); ?></h2>
                <p class="hp-contact-card-sub"><?php _e("Tell us what you're building — we'll reply within 24–48 hrs.", 'foundnxt'); ?></p>
              </div>
            </div>

            <?php
            // Show a status banner after redirect from the form handler below.
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
              <!-- Honeypot — hidden from real visitors, bots tend to fill every field -->
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
                <label for="hp-contact-message"><?php _e('Message', 'foundnxt'); ?></label>
                <textarea id="hp-contact-message" name="hp_contact_message" rows="4" placeholder="<?php esc_attr_e('Describe the requirement, problem statement, or message…', 'foundnxt'); ?>" required></textarea>
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

  <!-- ── BLOCK 4: WHAT YOU'LL FIND HERE — Animated Roadmap ── -->
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
            'emoji' => '🚀',
            'title' => __('Startups', 'foundnxt'),
            'desc'  => __('Real playbooks on ideation, fundraising, product-market fit, and the mistakes that kill startups early.', 'foundnxt'),
            'url'   => home_url('/category/startups/'),
            'accent'=> '#4f46e5',
          ],
          [
            'emoji' => '⚙️',
            'title' => __('Tech', 'foundnxt'),
            'desc'  => __('The shifts in tools, AI, and infrastructure that are quietly reshaping how companies build and compete.', 'foundnxt'),
            'url'   => home_url('/category/technology/'),
            'accent'=> '#2563eb',
          ],
          [
            'emoji' => '📈',
            'title' => __('Scaling', 'foundnxt'),
            'desc'  => __('What breaks when you go from 10 to 100 to 1,000 — and how the best teams rebuild without losing speed.', 'foundnxt'),
            'url'   => home_url('/category/scaling/'),
            'accent'=> '#0891b2',
          ],
          [
            'emoji' => '💼',
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
                <span class="hp-roadmap-dot" aria-hidden="true"><?php echo $p['emoji']; ?></span>
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

  <!-- ── BLOCK 5: COMING SOON — Tools Built for Builders ── -->
  <section class="hp-block hp-tools-teaser">
    <div class="container">
      <div class="hp-tools-teaser-card fnx-reveal">
        <span class="hp-tools-teaser-badge"><?php _e('Coming Soon', 'foundnxt'); ?></span>
        <h2 class="hp-tools-teaser-h2"><?php _e('Tools Built for Builders', 'foundnxt'); ?></h2>
        <p class="hp-tools-teaser-p">
          <?php _e("We're not stopping at content.", 'foundnxt'); ?>
          <?php printf(
            /* translators: %s: site name */
            esc_html__('%s is building a suite of tools to help you go from idea to execution — startup trackers, growth calculators, career planners, and more.', 'foundnxt'),
            esc_html(get_bloginfo('name'))
          ); ?>
          <?php _e('Be the first to try them.', 'foundnxt'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/tools/')); ?>" class="btn-primary hp-tools-teaser-cta">
          <?php _e('Get early access', 'foundnxt'); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <div class="hp-tools-teaser-orbit" aria-hidden="true">
          <span class="hp-tools-orbit-dot hp-tools-orbit-dot--1"></span>
          <span class="hp-tools-orbit-dot hp-tools-orbit-dot--2"></span>
          <span class="hp-tools-orbit-dot hp-tools-orbit-dot--3"></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ── BLOCK 2: RECENT POSTS ── -->
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
      foreach (get_categories(['number' => 50]) as $c)
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
        <!-- Rendered by JS -->
      </nav>
      <p class="hp-pagination-info" id="hp-pagination-info"></p>

    </div>
  </section>

  <!-- ── BLOCK 3: BROWSE BY CATEGORY ── -->
  <section class="hp-block hp-cats">
    <div class="container">
      <div class="hp-sec-header fnx-reveal">
        <h2><?php _e('Browse by', 'foundnxt'); ?> <em><?php _e('Category', 'foundnxt'); ?></em></h2>
        <hr class="hp-rule">
      </div>

      <?php $cats = fnx_get_cats(); ?>

      <!-- Overview chips -->
      <div class="hp-cov-grid">
        <?php foreach ($cats as $i => $cat): ?>
          <div class="hp-cov-chip fnx-reveal <?php echo $i === 0 ? 'active' : ''; ?>" data-cid="<?php echo $cat->term_id; ?>" style="--i:<?php echo (int) $i; ?>;">
            <div class="hp-cov-dot"></div>
            <div class="hp-cov-name"><?php echo esc_html($cat->name); ?></div>
            <div class="hp-cov-cnt"><?php echo $cat->count; ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Tab strip -->
      <div class="hp-cat-tabs">
        <?php foreach ($cats as $i => $cat): ?>
          <button class="hp-cat-tab <?php echo $i === 0 ? 'active' : ''; ?>" data-cid="<?php echo $cat->term_id; ?>">
            <?php echo esc_html($cat->name); ?>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Panels -->
      <?php foreach ($cats as $i => $cat):
        $cat_posts = get_posts(['category' => $cat->term_id, 'posts_per_page' => 9, 'post_status' => 'publish']);
        ?>
        <div class="hp-cat-panel <?php echo $i === 0 ? 'active' : ''; ?>" id="hp-cp-<?php echo $cat->term_id; ?>">
          <?php if ($cat_posts): ?>
            <div class="hp-art-grid">
              <?php foreach ($cat_posts as $j => $cp): ?>
                <a class="hp-ac" href="<?php echo esc_url(get_permalink($cp->ID)); ?>">
                  <div class="hp-ac-num"><?php echo str_pad($j + 1, 2, '0', STR_PAD_LEFT); ?></div>
                  <div class="hp-ac-title"><?php echo esc_html(get_the_title($cp->ID)); ?></div>
                  <div class="hp-ac-foot"><span><?php echo fnx_fmt_date($cp->post_date); ?></span><span
                      class="hp-ac-arrow">→</span></div>
                </a>
              <?php endforeach;
              wp_reset_postdata(); ?>
            </div>
          <?php else: ?>
            <p class="hp-empty"><?php _e('No posts yet.', 'foundnxt'); ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

    </div>
  </section>

<?php else: /* Non-front-page archive */ ?>

  <!-- ══════════════════════════════════
     BLOG / ARCHIVE (non-homepage)
══════════════════════════════════ -->
  <div class="fnx-archive">
    <div class="container">
      <div class="archive-header">
        <?php fnx_breadcrumbs(); ?>
        <div class="archive-title-wrap">
          <?php if (is_home()): ?>
            <h1 class="archive-title"><?php _e('Latest Articles', 'foundnxt'); ?></h1>
          <?php elseif (is_category()): ?>
            <span class="archive-label"><?php _e('Category', 'foundnxt'); ?></span>
            <h1 class="archive-title"><?php single_cat_title(); ?></h1>
            <?php if (category_description()): ?>
              <div class="archive-desc"><?php echo category_description(); ?></div><?php endif; ?>
          <?php elseif (is_tag()): ?>
            <h1 class="archive-title">#<?php single_tag_title(); ?></h1>
          <?php else: ?>
            <h1 class="archive-title"><?php the_archive_title(); ?></h1>
          <?php endif; ?>
        </div>
        <?php get_template_part('template-parts/category-filter'); ?>
      </div>
      <div class="archive-layout">
        <div class="archive-posts">
          <?php if (have_posts()): ?>
            <?php if (!is_paged()):
              the_post();
              get_template_part('template-parts/post-card', 'hero');
            endif; ?>
            <div class="posts-grid" id="posts-grid">
              <?php while (have_posts()):
                the_post();
                get_template_part('template-parts/post-card');
              endwhile; ?>
            </div>
            <div class="archive-pagination">
              <?php if (get_next_posts_link()): ?>
                <button class="btn-outline load-more-btn" id="load-more"
                  data-page="<?php echo get_query_var('paged') ?: 1; ?>" data-max="<?php echo $wp_query->max_num_pages; ?>"
                  data-cat="<?php echo is_category() ? get_queried_object_id() : 0; ?>">
                  <?php _e('Load More Articles', 'foundnxt'); ?>
                </button>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <?php get_template_part('template-parts/no-posts'); ?>
          <?php endif; ?>
        </div>
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>

<?php endif; ?>

<?php get_footer(); ?>