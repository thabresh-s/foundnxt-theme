<?php get_header(); ?>

<?php
$query   = get_search_query();
$found   = $wp_query->found_posts;
$has     = have_posts();
?>

<!-- ── Search Hero Banner ── -->
<div class="srp-hero">
  <div class="container">
    <?php fnx_breadcrumbs(); ?>
    <div class="srp-hero-inner">
      <!-- Left: heading + meta -->
      <div class="srp-hero-text">
        <?php if ($has): ?>
          <div class="srp-eyebrow">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <?php _e('Search Results', 'foundnxt'); ?>
          </div>
          <h1 class="srp-heading">
            <?php _e('Results for', 'foundnxt'); ?>
            <mark class="srp-query"><?php echo esc_html($query); ?></mark>
          </h1>
          <p class="srp-count">
            <?php printf(
              _n('<strong>%d</strong> article found', '<strong>%d</strong> articles found', $found, 'foundnxt'),
              $found
            ); ?>
          </p>
        <?php else: ?>
          <div class="srp-eyebrow srp-eyebrow--empty">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?php _e('No Results', 'foundnxt'); ?>
          </div>
          <h1 class="srp-heading">
            <?php _e('Nothing found for', 'foundnxt'); ?>
            <mark class="srp-query"><?php echo esc_html($query); ?></mark>
          </h1>
          <p class="srp-count srp-count--empty"><?php _e('Try different keywords or browse by category below.', 'foundnxt'); ?></p>
        <?php endif; ?>
      </div>

      <!-- Right: refined search bar -->
      <div class="srp-search-wrap">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="srp-search-form">
          <label class="srp-search-label" for="srp-s"><?php _e('Refine your search', 'foundnxt'); ?></label>
          <div class="srp-search-bar">
            <svg class="srp-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input
              type="search"
              id="srp-s"
              name="s"
              class="srp-search-input"
              value="<?php echo esc_attr($query); ?>"
              placeholder="<?php esc_attr_e('Try another keyword…', 'foundnxt'); ?>"
              autocomplete="off"
            >
            <button type="submit" class="srp-search-btn">
              <?php _e('Search', 'foundnxt'); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
          </div>

          <!-- Quick topic chips inside form -->
          <?php
          $popular_cats = get_categories(['orderby' => 'count', 'order' => 'DESC', 'number' => 5, 'hide_empty' => true]);
          if ($popular_cats):
          ?>
          <div class="srp-quick-topics">
            <span class="srp-quick-label"><?php _e('Popular:', 'foundnxt'); ?></span>
            <?php foreach ($popular_cats as $cat): ?>
              <a href="<?php echo esc_url(home_url('/?s=' . urlencode($cat->name))); ?>" class="srp-quick-chip">
                <?php echo esc_html($cat->name); ?>
              </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ── Results Body ── -->
<div class="srp-body">
  <div class="container">
    <div class="srp-layout">

      <!-- Posts -->
      <div class="srp-posts">
        <?php if ($has): ?>
          <div class="posts-grid">
            <?php while (have_posts()): the_post();
              get_template_part('template-parts/post-card');
            endwhile; ?>
          </div>
          <div class="archive-pagination">
            <?php the_posts_pagination([
              'mid_size'  => 2,
              'prev_text' => '← ' . __('Previous', 'foundnxt'),
              'next_text' => __('Next', 'foundnxt') . ' →',
            ]); ?>
          </div>
        <?php else: ?>
          <!-- No results — category browse -->
          <div class="srp-no-results">
            <div class="srp-no-icon">🔍</div>
            <h3 class="srp-no-title"><?php printf(__('No articles match "%s"', 'foundnxt'), esc_html($query)); ?></h3>
            <p class="srp-no-desc"><?php _e('Try shorter keywords, check spelling, or explore these categories:', 'foundnxt'); ?></p>
            <div class="srp-cat-chips">
              <?php
              $all_cats = get_categories(['orderby' => 'count', 'order' => 'DESC', 'number' => 12, 'hide_empty' => true]);
              foreach ($all_cats as $cat):
              ?>
                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="cat-filter-chip">
                  <?php echo esc_html($cat->name); ?>
                  <span class="srp-cat-count"><?php echo $cat->count; ?></span>
                </a>
              <?php endforeach; ?>
            </div>
            <div class="srp-no-actions">
              <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary"><?php _e('← Back to Home', 'foundnxt'); ?></a>
              <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-outline"><?php _e('Browse All Articles', 'foundnxt'); ?></a>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <?php get_sidebar(); ?>
    </div>
  </div>
</div>

<style>
/* ── SEARCH HERO ── */
.srp-hero {
  background: var(--fnx-surface);
  border-bottom: 1px solid var(--fnx-border);
  padding: 36px 0 32px;
}
.srp-hero-inner {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 40px;
  align-items: center;
  margin-top: 16px;
}
@media(max-width:860px) { .srp-hero-inner { grid-template-columns: 1fr; gap: 24px; } }

/* Eyebrow */
.srp-eyebrow {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
  color: var(--fnx-primary);
  background: var(--fnx-primary-light);
  border: 1px solid var(--fnx-primary-border);
  border-radius: 100px; padding: 4px 12px; margin-bottom: 14px;
}
.srp-eyebrow--empty { color: var(--fnx-muted); background: var(--fnx-surface); border-color: var(--fnx-border); }

/* Heading */
.srp-heading {
  font-family: 'Source Serif 4', Georgia, serif;
  font-size: clamp(22px, 2.8vw, 36px);
  font-weight: 800; color: var(--fnx-ink);
  line-height: 1.2; margin: 0 0 10px;
}
.srp-query {
  background: none; color: var(--fnx-primary);
  font-style: normal; padding: 0;
  text-decoration: underline;
  text-decoration-color: var(--fnx-primary-border);
  text-underline-offset: 4px;
}

/* Count */
.srp-count { font-size: 14px; color: var(--fnx-muted); margin: 0; }
.srp-count strong { color: var(--fnx-primary); font-weight: 700; }
.srp-count--empty { font-size: 14px; }

/* ── Search bar ── */
.srp-search-label {
  display: block; font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.2px;
  color: var(--fnx-muted); margin-bottom: 8px;
}
.srp-search-bar {
  display: flex; align-items: center;
  background: var(--fnx-card);
  border: 1.5px solid var(--fnx-border);
  border-radius: 100px;
  padding: 5px 5px 5px 16px;
  gap: 10px;
  transition: border-color .2s, box-shadow .2s;
}
.srp-search-bar:focus-within {
  border-color: var(--fnx-primary);
  box-shadow: 0 0 0 3px rgba(79,70,229,.1);
}
.srp-search-icon {
  width: 16px; height: 16px;
  color: var(--fnx-muted); flex-shrink: 0;
}
.srp-search-input {
  flex: 1; min-width: 0;
  border: none; background: transparent;
  font-size: 14px; font-family: 'DM Sans', sans-serif;
  color: var(--fnx-ink); outline: none; padding: 6px 0;
}
.srp-search-input::placeholder { color: var(--fnx-muted); }
.srp-search-btn {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--fnx-primary); color: #fff;
  border: none; border-radius: 100px;
  padding: 9px 18px; font-size: 13px; font-weight: 700;
  font-family: 'DM Sans', sans-serif; cursor: pointer;
  white-space: nowrap; flex-shrink: 0;
  transition: background .2s, transform .15s;
}
.srp-search-btn:hover { background: var(--fnx-primary-dark, #3730a3); transform: scale(1.02); }

/* Quick topic chips */
.srp-quick-topics {
  display: flex; align-items: center; flex-wrap: wrap;
  gap: 6px; margin-top: 12px;
}
.srp-quick-label { font-size: 11px; color: var(--fnx-muted); font-weight: 600; }
.srp-quick-chip {
  font-size: 11.5px; font-weight: 500;
  color: var(--fnx-body);
  background: var(--fnx-card);
  border: 1px solid var(--fnx-border);
  border-radius: 100px; padding: 3px 10px;
  text-decoration: none; transition: all .15s;
}
.srp-quick-chip:hover {
  background: var(--fnx-primary-light);
  border-color: var(--fnx-primary-border);
  color: var(--fnx-primary);
}

/* ── Results body ── */
.srp-body { padding: 40px 0 60px; }
.srp-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 48px; align-items: start;
}
@media(max-width:1000px) { .srp-layout { grid-template-columns: 1fr; } .post-sidebar { display: none; } }

/* ── No results ── */
.srp-no-results { padding: 20px 0 40px; }
.srp-no-icon { font-size: 48px; margin-bottom: 16px; }
.srp-no-title { font-size: clamp(18px, 2.5vw, 24px); font-weight: 700; color: var(--fnx-ink); margin: 0 0 10px; }
.srp-no-desc  { font-size: 15px; color: var(--fnx-muted); margin: 0 0 20px; line-height: 1.65; }
.srp-cat-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 28px; }
.srp-cat-count { font-size: 10px; opacity: .65; margin-left: 3px; }
.srp-no-actions { display: flex; gap: 10px; flex-wrap: wrap; }

@media(max-width:540px) {
  .srp-hero { padding: 24px 0 20px; }
  .srp-no-actions { flex-direction: column; }
  .srp-no-actions a { justify-content: center; }
}
</style>

<?php get_footer(); ?>
