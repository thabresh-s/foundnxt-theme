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
          <span><?php _e('How to Scale Your Company in the AI Era', 'foundnxt'); ?></span>
        </div>

        <h1 class="hp-h1-v3">
          Think Smarter. Build Bigger. <span class="text-grad"><?php _e('Grow Faster.', 'foundnxt'); ?></span>
        </h1>

        <p class="hp-lead-v3">
          <?php _e('Learn how to scale your startup with proven frameworks on AI, valuation, and growth. No jargon, just what works.', 'foundnxt'); ?>
        </p>

        <!-- Hero Actions: "Explore Articles" (Primary) + "Work With Us" (Outlined) -->
        <div class="hero-sub-actions">
          <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="hero-btn-primary">
            <?php _e('Explore Articles', 'foundnxt'); ?> &rarr;
          </a>
          <a href="<?php echo esc_url(home_url('/services/')); ?>" class="hero-btn-outlined">
            <?php _e('Work With Us', 'foundnxt'); ?> &rarr;
          </a>
        </div>
      </div>
    </div>
  </section>


  <?php
  // Check if Featured Articles section is enabled in Customizer
  $featured_enabled = get_theme_mod('fnx_featured_enabled', true);
  $featured_posts   = $featured_enabled && function_exists('fnx_get_featured_homepage_posts') 
                      ? fnx_get_featured_homepage_posts() 
                      : [];
  $featured_count   = count($featured_posts);

  if ($featured_enabled && $featured_count > 0):
    $featured_tag           = get_theme_mod('fnx_featured_tag', __('Editor’s Picks', 'foundnxt'));
    $featured_title_prefix  = get_theme_mod('fnx_featured_title_prefix', __('Featured', 'foundnxt'));
    $featured_title_accent  = get_theme_mod('fnx_featured_title_accent', __('Articles', 'foundnxt'));
  ?>
  <!-- ── 2. FEATURED ARTICLES SPOTLIGHT (1 or 2 Selected Posts) ── -->
  <section class="hp-spotlight-section defer-render" id="featured-articles">
    <div class="container">
      <div class="section-headline-row">
        <div>
          <span class="section-tag"><?php echo esc_html($featured_tag); ?></span>
          <h2><?php echo esc_html($featured_title_prefix); ?> <span class="text-grad"><?php echo esc_html($featured_title_accent); ?></span></h2>
        </div>
        <div class="hp-spotlight-counter">
          <span class="hp-spotlight-count-badge">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <?php printf(_n('%d Featured Story', '%d Featured Stories', $featured_count, 'foundnxt'), $featured_count); ?>
          </span>
        </div>
      </div>

      <?php if ($featured_count === 1):
        $p = $featured_posts[0];
        $post_cats   = wp_get_post_categories($p->ID, ['fields' => 'all']);
        $primary_cat = null;
        foreach ($post_cats as $c) {
          if ($c->slug !== 'uncategorized') {
            $primary_cat = $c;
            break;
          }
        }
        $cname  = $primary_cat ? $primary_cat->name : 'Business & Strategy';
        $cslug  = $primary_cat ? $primary_cat->slug : 'business-strategy';
        $color  = fnx_get_category_color($cslug);
        $badge  = get_post_meta($p->ID, 'fnx_post_badge', true) ?: __('Featured Story', 'foundnxt');
        $read_t = fnx_read_time($p->ID);
        $date   = fnx_fmt_date($p->post_date);
      ?>
        <!-- Single Post Widescreen Hero Card -->
        <article class="hp-spotlight-single" style="--card-accent: <?php echo esc_attr($color); ?>;">
          <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="hp-spotlight-single-thumb" tabindex="-1" aria-hidden="true">
            <?php if (has_post_thumbnail($p->ID)): ?>
              <?php echo get_the_post_thumbnail($p->ID, 'fnx-hero', [
                'alt'           => esc_attr(get_the_title($p->ID)),
                'loading'       => 'eager',
                'fetchpriority' => 'high',
                'decoding'      => 'async'
              ]); ?>
            <?php else: ?>
              <div class="hp-spotlight-thumb-poster" style="background: linear-gradient(135deg, #0E1220 0%, #1B233C 100%);">
                <div class="hp-spotlight-poster-brand">
                  <span class="hp-spotlight-poster-mark">f</span>
                  <span class="hp-spotlight-poster-name">found<strong>NXT</strong></span>
                </div>
                <div class="hp-spotlight-poster-inner">
                  <span class="hp-spotlight-poster-cat" style="color: <?php echo esc_attr($color); ?>;"><?php echo esc_html($cname); ?></span>
                  <div class="hp-spotlight-poster-title"><?php echo esc_html(get_the_title($p->ID)); ?></div>
                </div>
              </div>
            <?php endif; ?>
            <span class="hp-spotlight-badge-floating" style="background: <?php echo esc_attr($color); ?>;">
              ⭐ <?php echo esc_html($badge); ?>
            </span>
          </a>

          <div class="hp-spotlight-single-body">
            <div class="hp-spotlight-meta-top">
              <span class="playbook-badge" style="background: <?php echo esc_attr($color); ?>18; color: <?php echo esc_attr($color); ?>; border-color: <?php echo esc_attr($color); ?>40;">
                <span class="playbook-badge-dot" style="background: <?php echo esc_attr($color); ?>;"></span>
                <?php echo esc_html($cname); ?>
              </span>
              <span class="hp-spotlight-meta-dot">•</span>
              <span class="hp-spotlight-meta-item"><?php echo esc_html($read_t); ?></span>
              <span class="hp-spotlight-meta-dot">•</span>
              <span class="hp-spotlight-meta-item"><?php echo esc_html($date); ?></span>
            </div>

            <h3 class="hp-spotlight-single-title">
              <a href="<?php echo esc_url(get_permalink($p->ID)); ?>">
                <?php echo esc_html(get_the_title($p->ID)); ?>
              </a>
            </h3>

            <p class="hp-spotlight-single-excerpt">
              <?php echo esc_html(wp_trim_words(get_the_excerpt($p->ID), 36)); ?>
            </p>

            <div class="hp-spotlight-single-footer">
              <div class="hp-spotlight-author-lockup">
                <?php echo get_avatar(get_the_author_meta('email', $p->post_author), 38, '', '', ['class' => 'hp-spotlight-avatar', 'loading' => 'lazy']); ?>
                <div class="hp-spotlight-author-info">
                  <span class="hp-spotlight-author-name"><?php the_author_meta('display_name', $p->post_author); ?></span>
                  <span class="hp-spotlight-author-title"><?php _e('Venture Research', 'foundnxt'); ?></span>
                </div>
              </div>

              <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="hero-btn-primary hp-spotlight-btn">
                <span><?php _e('Read Full Story', 'foundnxt'); ?></span> &rarr;
              </a>
            </div>
          </div>
        </article>

      <?php else: ?>
        <!-- Dual Featured 2-Column Grid -->
        <div class="hp-spotlight-grid hp-spotlight-grid--dual">
          <?php foreach ($featured_posts as $f_idx => $p):
            $post_cats   = wp_get_post_categories($p->ID, ['fields' => 'all']);
            $primary_cat = null;
            foreach ($post_cats as $c) {
              if ($c->slug !== 'uncategorized') {
                $primary_cat = $c;
                break;
              }
            }
            $cname  = $primary_cat ? $primary_cat->name : 'Business & Strategy';
            $cslug  = $primary_cat ? $primary_cat->slug : 'business-strategy';
            $color  = fnx_get_category_color($cslug);
            $badge  = get_post_meta($p->ID, 'fnx_post_badge', true) ?: ($f_idx === 0 ? __('Top Story', 'foundnxt') : __('Deep Dive', 'foundnxt'));
            $read_t = fnx_read_time($p->ID);
            $date   = fnx_fmt_date($p->post_date);
          ?>
            <article class="hp-spotlight-card" style="--card-accent: <?php echo esc_attr($color); ?>;">
              <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="hp-spotlight-card-thumb" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail($p->ID)): ?>
                  <?php echo get_the_post_thumbnail($p->ID, 'fnx-card', [
                    'alt'           => esc_attr(get_the_title($p->ID)),
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                    'decoding'      => 'async'
                  ]); ?>
                <?php else: ?>
                  <div class="hp-spotlight-thumb-poster" style="background: linear-gradient(135deg, #0E1220 0%, #1B233C 100%);">
                    <div class="hp-spotlight-poster-brand">
                      <span class="hp-spotlight-poster-mark">f</span>
                      <span class="hp-spotlight-poster-name">found<strong>NXT</strong></span>
                    </div>
                    <div class="hp-spotlight-poster-inner">
                      <span class="hp-spotlight-poster-cat" style="color: <?php echo esc_attr($color); ?>;"><?php echo esc_html($cname); ?></span>
                      <div class="hp-spotlight-poster-title hp-spotlight-poster-title--dual"><?php echo esc_html(get_the_title($p->ID)); ?></div>
                    </div>
                  </div>
                <?php endif; ?>
                <span class="hp-spotlight-badge-floating" style="background: <?php echo esc_attr($color); ?>;">
                  ⭐ <?php echo esc_html($badge); ?>
                </span>
              </a>

              <div class="hp-spotlight-card-body">
                <div class="hp-spotlight-meta-top">
                  <span class="playbook-badge" style="background: <?php echo esc_attr($color); ?>18; color: <?php echo esc_attr($color); ?>; border-color: <?php echo esc_attr($color); ?>40;">
                    <span class="playbook-badge-dot" style="background: <?php echo esc_attr($color); ?>;"></span>
                    <?php echo esc_html($cname); ?>
                  </span>
                  <span class="hp-spotlight-meta-dot">•</span>
                  <span class="hp-spotlight-meta-item"><?php echo esc_html($read_t); ?></span>
                  <span class="hp-spotlight-meta-dot">•</span>
                  <span class="hp-spotlight-meta-item"><?php echo esc_html($date); ?></span>
                </div>

                <h3 class="hp-spotlight-card-title">
                  <a href="<?php echo esc_url(get_permalink($p->ID)); ?>">
                    <?php echo esc_html(get_the_title($p->ID)); ?>
                  </a>
                </h3>

                <p class="hp-spotlight-card-excerpt">
                  <?php echo esc_html(wp_trim_words(get_the_excerpt($p->ID), 24)); ?>
                </p>

                <div class="hp-spotlight-card-footer">
                  <div class="hp-spotlight-author-lockup">
                    <?php echo get_avatar(get_the_author_meta('email', $p->post_author), 30, '', '', ['class' => 'hp-spotlight-avatar', 'loading' => 'lazy']); ?>
                    <span class="hp-spotlight-author-name"><?php the_author_meta('display_name', $p->post_author); ?></span>
                  </div>

                  <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="hp-spotlight-read-link">
                    <span><?php _e('Read Article', 'foundnxt'); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- ── 3. RECENT ARTICLES (Modern Responsive Grid, Category Filter & Interactive Pagination) ── -->
  <section class="hp-featured-section defer-render" id="recent-articles">
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

      <?php
      // Query recent published posts
      $recent_args = [
          'post_type'      => 'post',
          'post_status'    => 'publish',
          'posts_per_page' => 30,
          'orderby'        => 'date',
          'order'          => 'DESC',
      ];
      // Exclude featured IDs from Recent section only if there are at least 5 posts total
      $featured_ids = !empty($featured_posts) ? wp_list_pluck($featured_posts, 'ID') : [];
      $all_posts_count = wp_count_posts('post')->publish ?? 0;
      if ($all_posts_count >= 5 && !empty($featured_ids)) {
          $recent_args['post__not_in'] = $featured_ids;
      }
      $all_posts = get_posts($recent_args);

      // Calculate post counts per category
      $cat_counts = [];
      foreach ($all_posts as $p) {
          $p_cats = wp_get_post_categories($p->ID, ['fields' => 'slugs']);
          foreach ($p_cats as $s) {
              $cat_counts[$s] = ($cat_counts[$s] ?? 0) + 1;
          }
      }

      // Filter categories from primary categories
      $primary_cats = fnx_get_primary_categories();
      $filter_cats  = [];
      foreach ($primary_cats as $pcat) {
          $count = $cat_counts[$pcat['slug']] ?? 0;
          $filter_cats[] = [
              'name'  => $pcat['name'],
              'slug'  => $pcat['slug'],
              'color' => $pcat['color'],
              'count' => $count,
          ];
      }

      // Pagination size: 2 per page if <= 4 total posts so pagination is immediately active & testable; 6 if larger
      $per_page    = (count($all_posts) <= 4) ? 2 : 6;
      $total_pages = max(1, (int) ceil(count($all_posts) / $per_page));
      ?>

      <!-- ── Category Filter Rail ── -->
      <div class="hp-articles-filter-wrap">
        <div class="hp-filter-rail" role="tablist" aria-label="<?php esc_attr_e('Filter articles by category', 'foundnxt'); ?>">
          <button type="button" class="hp-filter-btn is-active" data-filter="all" role="tab" aria-selected="true">
            <span class="hp-filter-dot hp-filter-dot--all"></span>
            <span><?php _e('All Articles', 'foundnxt'); ?></span>
            <span class="hp-filter-count"><?php echo count($all_posts); ?></span>
          </button>
          <?php foreach ($filter_cats as $fcat): ?>
            <button type="button" class="hp-filter-btn" data-filter="<?php echo esc_attr($fcat['slug']); ?>" style="--filter-color: <?php echo esc_attr($fcat['color']); ?>;" role="tab" aria-selected="false">
              <span class="hp-filter-dot" style="background: <?php echo esc_attr($fcat['color']); ?>;"></span>
              <span><?php echo esc_html($fcat['name']); ?></span>
              <?php if (! empty($fcat['count'])): ?>
                <span class="hp-filter-count"><?php echo (int) $fcat['count']; ?></span>
              <?php endif; ?>
            </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ── Articles Grid ── -->
      <div class="playbooks-grid" id="hp-playbooks-grid">
        <?php
        if (! empty($all_posts)):
          foreach ($all_posts as $f_idx => $fp):
            $post_cats    = wp_get_post_categories($fp->ID, ['fields' => 'all']);
            $cat_slugs    = [];
            $primary_cat  = null;
            foreach ($post_cats as $c) {
                $cat_slugs[] = $c->slug;
                if (! $primary_cat && $c->slug !== 'uncategorized') {
                    $primary_cat = $c;
                }
            }
            $cat_slug_attr = implode(' ', $cat_slugs);
            $f_cname       = $primary_cat ? $primary_cat->name : 'Business & Strategy';
            $f_cslug       = $primary_cat ? $primary_cat->slug : 'business-strategy';
            $f_color       = fnx_get_category_color($f_cslug);
            $init_visible  = ($f_idx < $per_page);
        ?>
            <article class="playbook-card" 
                     data-index="<?php echo $f_idx; ?>" 
                     data-categories="<?php echo esc_attr($cat_slug_attr); ?>" 
                     style="--card-accent: <?php echo esc_attr($f_color); ?>; <?php echo ! $init_visible ? 'display: none;' : ''; ?>">
              
              <!-- Featured Image Position (Top of card, full-width 16:9) -->
              <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>" class="playbook-card-thumb-wrap" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail($fp->ID)): ?>
                  <?php echo get_the_post_thumbnail($fp->ID, 'fnx-card', [
                    'alt'           => esc_attr(get_the_title($fp->ID)),
                    'loading'       => ($f_idx === 0) ? 'eager' : 'lazy',
                    'fetchpriority' => ($f_idx === 0) ? 'high' : 'auto',
                    'decoding'      => 'async'
                  ]); ?>
                <?php else: ?>
                  <div class="playbook-card-thumb-placeholder">
                    <span style="font-weight: 800; color: #fff; background: var(--brand, #f97316); width: 44px; height: 44px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">f</span>
                  </div>
                <?php endif; ?>
              </a>

              <div class="playbook-card-body">
                <span class="playbook-badge" style="background: <?php echo esc_attr($f_color); ?>18; color: <?php echo esc_attr($f_color); ?>; border-color: <?php echo esc_attr($f_color); ?>40;">
                  <span class="playbook-badge-dot" style="background: <?php echo esc_attr($f_color); ?>;"></span>
                  <?php echo esc_html($f_cname); ?>
                </span>

                <h3 class="playbook-title">
                  <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>" title="<?php echo esc_attr(get_the_title($fp->ID)); ?>">
                    <?php echo esc_html(get_the_title($fp->ID)); ?>
                  </a>
                </h3>

                <p class="playbook-excerpt">
                  <?php echo esc_html(wp_trim_words(get_the_excerpt($fp->ID), 20)); ?>
                </p>

                <div class="playbook-meta">
                  <div class="playbook-meta-info">
                    <span><?php echo fnx_fmt_date($fp->post_date); ?></span>
                    <span>•</span>
                    <span><?php echo fnx_read_time($fp->ID); ?></span>
                  </div>
                  <span class="playbook-meta-arrow">
                    <?php _e('Read', 'foundnxt'); ?>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                  </span>
                </div>
              </div>
            </article>
        <?php endforeach; endif; ?>

        <!-- Empty state when category has no matches -->
        <div class="hp-no-articles-card" id="hp-no-articles" style="display: none;">
          <div class="hp-no-articles-icon">📂</div>
          <div class="hp-no-articles-title"><?php _e('No articles found in this category', 'foundnxt'); ?></div>
          <p class="hp-no-articles-desc"><?php _e('We regularly publish fresh frameworks. Check back soon or browse all articles.', 'foundnxt'); ?></p>
          <button type="button" class="hp-no-articles-btn" onclick="fnxResetArticleFilter();">
            <?php _e('View All Articles', 'foundnxt'); ?> &rarr;
          </button>
        </div>
      </div>

      <!-- ── Pagination Bar ── -->
      <?php if ($total_pages > 1): ?>
      <nav class="hp-articles-pagination" id="hp-articles-pagination" aria-label="<?php esc_attr_e('Recent articles pagination', 'foundnxt'); ?>">
        <button type="button" class="hp-pag-btn hp-pag-nav" id="hp-pag-prev" disabled aria-label="<?php esc_attr_e('Previous page', 'foundnxt'); ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          <span><?php _e('Prev', 'foundnxt'); ?></span>
        </button>
        <div class="hp-pag-numbers" id="hp-pag-numbers">
          <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <button type="button" class="hp-pag-btn <?php echo ($p === 1) ? 'is-active' : ''; ?>" data-page="<?php echo $p; ?>" aria-label="<?php printf(esc_attr__('Page %d', 'foundnxt'), $p); ?>">
              <?php echo $p; ?>
            </button>
          <?php endfor; ?>
        </div>
        <button type="button" class="hp-pag-btn hp-pag-nav" id="hp-pag-next" aria-label="<?php esc_attr_e('Next page', 'foundnxt'); ?>">
          <span><?php _e('Next', 'foundnxt'); ?></span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
      </nav>
      <?php endif; ?>

      <script>
      (function() {
        var perPage = <?php echo (int) $per_page; ?>;
        var currentPage = 1;
        var currentCategory = 'all';
        var grid = document.getElementById('hp-playbooks-grid');
        if (!grid) return;

        var allCards = Array.from(grid.querySelectorAll('.playbook-card'));
        var noArticlesCard = document.getElementById('hp-no-articles');
        var prevBtn = document.getElementById('hp-pag-prev');
        var nextBtn = document.getElementById('hp-pag-next');
        var numbersContainer = document.getElementById('hp-pag-numbers');
        var filterBtns = Array.from(document.querySelectorAll('.hp-filter-btn'));

        function getMatchingCards() {
          if (currentCategory === 'all') {
            return allCards;
          }
          return allCards.filter(function(card) {
            var cats = card.getAttribute('data-categories') || '';
            var list = cats.split(' ');
            return list.indexOf(currentCategory) !== -1;
          });
        }

        function updateDisplay(scrollToTop) {
          var matching = getMatchingCards();
          var totalMatching = matching.length;
          var totalPages = Math.ceil(totalMatching / perPage);

          if (currentPage > totalPages) currentPage = 1;
          if (currentPage < 1) currentPage = 1;

          if (noArticlesCard) {
            noArticlesCard.style.display = (totalMatching === 0) ? 'block' : 'none';
          }

          var start = (currentPage - 1) * perPage;
          var end = start + perPage;

          allCards.forEach(function(card) {
            card.style.display = 'none';
          });

          matching.forEach(function(card, idx) {
            if (idx >= start && idx < end) {
              card.style.display = 'flex';
            }
          });

          if (numbersContainer) {
            numbersContainer.innerHTML = '';
            if (totalPages > 1) {
              for (var p = 1; p <= totalPages; p++) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'hp-pag-btn' + (p === currentPage ? ' is-active' : '');
                btn.setAttribute('data-page', p);
                btn.textContent = p;
                btn.addEventListener('click', (function(pageNumber) {
                  return function() {
                    currentPage = pageNumber;
                    updateDisplay(true);
                  };
                })(p));
                numbersContainer.appendChild(btn);
              }
            }
          }

          var pagNav = document.getElementById('hp-articles-pagination');
          if (pagNav) {
            pagNav.style.display = (totalPages > 1) ? 'flex' : 'none';
          }
          if (prevBtn) {
            prevBtn.disabled = (currentPage <= 1);
            prevBtn.classList.toggle('is-disabled', currentPage <= 1);
          }
          if (nextBtn) {
            nextBtn.disabled = (currentPage >= totalPages);
            nextBtn.classList.toggle('is-disabled', currentPage >= totalPages);
          }

          if (scrollToTop) {
            var section = document.getElementById('recent-articles');
            if (section) {
              var topPos = section.getBoundingClientRect().top + window.pageYOffset - 80;
              window.scrollTo({ top: topPos, behavior: 'smooth' });
            }
          }
        }

        filterBtns.forEach(function(btn) {
          btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) {
              b.classList.remove('is-active');
              b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');
            currentCategory = btn.getAttribute('data-filter') || 'all';
            currentPage = 1;
            updateDisplay(false);

            if (typeof btn.scrollIntoView === 'function') {
              btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
          });
        });

        if (prevBtn) {
          prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
              currentPage--;
              updateDisplay(true);
            }
          });
        }
        if (nextBtn) {
          nextBtn.addEventListener('click', function() {
            var totalPages = Math.ceil(getMatchingCards().length / perPage);
            if (currentPage < totalPages) {
              currentPage++;
              updateDisplay(true);
            }
          });
        }

        window.fnxResetArticleFilter = function() {
          var allBtn = document.querySelector('.hp-filter-btn[data-filter="all"]');
          if (allBtn) allBtn.click();
        };

        updateDisplay(false);
      })();
      </script>
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
        <?php 
        $bento_cats = fnx_get_primary_categories();
        $bento_icons = [
          'startups-funding'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-3.05 11a22.35 22.35 0 0 1-3.95 2z"/></svg>',
          'technology-ai'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3"/></svg>',
          'business-strategy' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
          'valuation-finance' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
          'markets-economy'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
          'news-insights'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M18 18h-8M18 10h-8"/></svg>',
          'marketing-growth'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
        ];

        foreach ($bento_cats as $idx => $bcat):
          $tile_num = $idx + 1;
          $tile_icon = $bento_icons[$bcat['slug']] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>';
        ?>
          <a href="<?php echo esc_url($bcat['url']); ?>" class="bento-tile bento-tile-<?php echo $tile_num; ?>">
            <div class="bento-icon-row">
              <div class="bento-icon">
                <?php echo $tile_icon; ?>
              </div>
              <span class="bento-number"><?php echo esc_html($bcat['badge']); ?></span>
            </div>
            <div>
              <h3 class="bento-title"><?php echo esc_html($bcat['name']); ?></h3>
              <p class="bento-desc"><?php echo esc_html($bcat['desc']); ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── 4. SERVICES ("WORK WITH US" — 2 CONSULTING CARDS + DRAFT PRESERVATION) ── -->
  <section class="hp-services-section defer-render" id="services">
    <div class="container">
      <div class="section-headline-row text-center" style="justify-content: center; text-align: center; margin-bottom: 36px;">
        <div>
          <span class="section-tag"><?php _e('Consulting & Strategy', 'foundnxt'); ?></span>
          <h2><?php _e('Work With', 'foundnxt'); ?> <span class="text-grad"><?php _e('Us', 'foundnxt'); ?></span></h2>
          <p style="color: var(--muted); margin-top: 8px;"><?php _e('Hands-on consulting for founders and teams scaling with technology and AI.', 'foundnxt'); ?></p>
        </div>
      </div>

      <div class="services-3-grid services-2-grid">
        <!-- Service Card 1: Tech & AI Strategy -->
        <div class="service-grad-card" style="--card-accent: var(--brand);">
          <div class="service-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <h3 class="service-card-title"><?php _e('Tech & AI Strategy', 'foundnxt'); ?></h3>
          <p class="service-card-desc"><?php _e('Architecture reviews, enterprise AI workflow integration, and build vs. buy audits for scaling software infrastructure.', 'foundnxt'); ?></p>
          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), home_url('/contact/'))); ?>" class="service-card-btn">
            <?php _e('Enquire Now', 'foundnxt'); ?> →
          </a>
        </div>

        <!-- Service Card 2: Business Consulting -->
        <div class="service-grad-card" style="--card-accent: var(--accent);">
          <div class="service-card-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3 class="service-card-title"><?php _e('Business Consulting', 'foundnxt'); ?></h3>
          <p class="service-card-desc"><?php _e('One-to-one consulting for founders and leaders on scaling, operations, and growth decisions.', 'foundnxt'); ?></p>
          <a href="<?php echo esc_url(add_query_arg('help', urlencode('Business Consulting'), home_url('/contact/'))); ?>" class="service-card-btn">
            <?php _e('Enquire Now', 'foundnxt'); ?> →
          </a>
        </div>
      </div>

      <p class="services-coming-soon"><?php _e('More services coming soon.', 'foundnxt'); ?></p>

      <!--
        DRAFT SERVICES — Preserved for future republishing:
        1) Market Research Request:
           Bespoke market intelligence, competitive landscape analysis, and industry trend reports built for founders and investment committees.
           Link: /contact/?help=Market+Research+Request
        2) Valuation Guidance:
           Independent financial modeling, cap table health audits, and seed-to-growth valuation benchmarking to scale with confidence.
           Link: /contact/?help=Valuation+Guidance
      -->
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
            <span class="hero-pill-badge" style="margin-bottom: 0;">✓ <?php _e('Free articles & frameworks. Consulting on request.', 'foundnxt'); ?></span>
            <span class="hero-pill-badge" style="margin-bottom: 0;">✓ Zero Fluff</span>
          </div>
        </div>
      </div>
    </div>
  </section>



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
          <input type="hidden" name="target_sheet" value="newsletter">
          <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" aria-hidden="true" autocomplete="off">
          <input type="email" name="lead_email" class="tools-waitlist-input" placeholder="<?php esc_attr_e('Enter your work email for early access…', 'foundnxt'); ?>" required>
          <button type="submit" class="tools-waitlist-btn"><?php _e('Join Waitlist', 'foundnxt'); ?> →</button>
        </form>
      </div>
    </div>
  </section>

  <!-- ── 8. NEWSLETTER (FULL-WIDTH GRADIENT BAND) ── -->
  <section class="fnx-newsletter-fullwidth defer-render" id="newsletter">
    <div class="container">
      <div class="newsletter-fw-inner">
        <div class="newsletter-fw-text">
          <h2 class="newsletter-fw-title"><?php _e('Get the Weekly Founder Brief', 'foundnxt'); ?></h2>
          <p class="newsletter-fw-sub"><?php _e('Receive our weekly briefing on startup valuation, enterprise AI shifts, and scaling frameworks. No spam, ever.', 'foundnxt'); ?></p>
        </div>
        <div class="newsletter-fw-form-wrap">
          <form class="newsletter-fw-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Fullwidth Newsletter Band">
            <input type="hidden" name="target_sheet" value="newsletter">
            <input type="text" name="lead_hp_field" style="display:none;" tabindex="-1" aria-hidden="true" autocomplete="off">
            <div class="newsletter-fw-fields">
              <input type="text" name="lead_name" class="newsletter-fw-input" placeholder="<?php esc_attr_e('Your full name…', 'foundnxt'); ?>" required>
              <input type="email" name="lead_email" class="newsletter-fw-input" placeholder="<?php esc_attr_e('Your work email…', 'foundnxt'); ?>" required>
            </div>
            <button type="submit" class="newsletter-fw-btn"><?php _e('Subscribe Free', 'foundnxt'); ?> →</button>
          </form>
          <p class="newsletter-privacy" style="margin-top: 10px; font-size: 0.8125rem; opacity: 0.9; color: #ffffff;">🔒 <?php _e('Free weekly brief. Unsubscribe in one click.', 'foundnxt'); ?></p>
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