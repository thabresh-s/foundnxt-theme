<?php
/**
 * Template Name: Articles Page
 * Template Post Type: page
 *
 * Redesigned articles index — FoundNXT.
 *
 * @package FoundNXT
 */

// Capture the articles page URL BEFORE get_header() can corrupt global $post.
// get_queried_object_id() reads directly from $wp_query — safe even after loops.
$_articles_page_id = get_queried_object_id();
$page_url = ($_articles_page_id) ? get_permalink($_articles_page_id) : trailingslashit(home_url(add_query_arg([])));

get_header();

$posts_per_page  = 12;
$paged = max(1, (int) get_query_var('paged') ?: (int) get_query_var('page') ?: (int) ($_GET['paged'] ?? 1));
$active_cat_slug = sanitize_key($_GET['cat'] ?? '');
$active_cat      = $active_cat_slug ? get_category_by_slug($active_cat_slug) : null;
$active_cat_id   = $active_cat ? $active_cat->term_id : 0;
$search_query    = sanitize_text_field($_GET['s'] ?? '');

$args = ['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $posts_per_page, 'paged' => $paged, 'orderby' => 'date', 'order' => 'DESC'];
if ($active_cat_id) $args['cat'] = $active_cat_id;
if ($search_query)  $args['s']   = $search_query;
$articles_query = new WP_Query($args);

$all_cats = get_categories(['hide_empty' => false, 'exclude' => [1], 'orderby' => 'name', 'order' => 'ASC', 'number' => 8]);

$cat_icons = [
    'business-strategy' => '💼',
    'startups-funding'  => '🚀',
    'valuation-finance' => '📈',
    'markets-economy'   => '🌐',
    'technology-ai'     => '🤖',
    'marketing-growth'  => '🎯',
    'global-business'   => '🗺️',
    'news-insights'     => '📰',
];

$schema = ['@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => 'Articles — FoundNXT', 'description' => 'Practical articles on business strategy, startups, valuation, technology, and markets.', 'url' => esc_url(get_permalink()), 'publisher' => ['@type' => 'Organization', 'name' => get_bloginfo('name')]];
echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
$bc = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url('/')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Articles', 'item' => get_permalink()]]];
echo '<script type="application/ld+json">' . wp_json_encode($bc) . '</script>' . "\n";
?>

<div class="fnx-articles-page apv2">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO BAND -->
    <div class="apv2-hero">
      <div class="apv2-hero-text">
        <span class="apv2-eyebrow"><span class="apv2-eyebrow-dot"></span><?php echo $active_cat ? esc_html($active_cat->name) : 'FoundNXT Articles'; ?></span>
        <h1 class="apv2-h1"><?php echo $active_cat ? esc_html($active_cat->name . ' — Articles &amp; Guides') : 'Business, Markets &amp;<br>Technology Playbooks'; ?></h1>
        <p class="apv2-lead"><?php echo $active_cat ? esc_html($active_cat->description ?: 'Practical insights for founders and business leaders.') : 'Practical insights on startups, valuation, AI, marketing, and global business built for founders and leaders.'; ?></p>
        <?php if (!$active_cat && !$search_query): ?>
        <div class="apv2-stats">
          <div class="apv2-stat"><strong><?php echo wp_count_posts()->publish; ?>+</strong><span>Articles</span></div>
          <div class="apv2-stat-div"></div>
          <div class="apv2-stat"><strong><?php echo count($all_cats); ?></strong><span>Categories</span></div>
          <div class="apv2-stat-div"></div>
          <div class="apv2-stat"><strong>Free</strong><span>Always</span></div>
        </div>
        <?php endif; ?>
      </div>
      <div class="apv2-hero-search">
        <form class="apv2-search-form" action="<?php echo esc_url($page_url); ?>" method="get" role="search">
          <div class="apv2-search-wrap">
            <svg class="apv2-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="search" name="s" class="apv2-search-input" placeholder="Search articles, topics, terms…" value="<?php echo esc_attr($search_query); ?>" autocomplete="off">
            <button type="submit" class="apv2-search-btn">Search</button>
          </div>
        </form>
        <?php if (!$active_cat && !$search_query): ?>
        <div class="apv2-search-hints">
          <span>Popular:</span>
          <?php $hint_tags = get_tags(['orderby' => 'count', 'order' => 'DESC', 'number' => 4, 'hide_empty' => true]); foreach ($hint_tags as $ht): ?>
          <a href="<?php echo esc_url(add_query_arg('s', $ht->name, $page_url)); ?>" class="apv2-hint-chip"><?php echo esc_html($ht->name); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- CATEGORY FILTER STRIP (Above Post Grid) -->
    <?php get_template_part('template-parts/category-filter-bar'); ?>

    <!-- MAIN TWO-COLUMN LAYOUT -->
    <div class="apv2-layout">
      <main class="apv2-main" id="main-content">

        <?php if ($search_query): ?>
        <div class="apv2-search-header">
          <h2>Results for <em>"<?php echo esc_html($search_query); ?>"</em></h2>
          <span class="apv2-result-count"><?php echo (int) $articles_query->found_posts; ?> articles found</span>
        </div>
        <?php endif; ?>

        <?php if ($articles_query->have_posts()): ?>

          <!-- FEATURED CARD -->
          <?php if ($paged === 1 && !$active_cat_id && !$search_query): ?>
          <?php $articles_query->the_post(); ?>
          <?php $fc = get_the_category(); $fn = $fc ? $fc[0]->name : 'Article'; $fu = $fc ? get_category_link($fc[0]->term_id) : '#'; ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class('apv2-featured'); ?> itemscope itemtype="https://schema.org/Article">
            <a href="<?php the_permalink(); ?>" class="apv2-featured-img" tabindex="-1" aria-hidden="true">
              <?php if (has_post_thumbnail()): the_post_thumbnail('fnx-hero', ['loading' => 'eager', 'itemprop' => 'image', 'alt' => get_the_title()]); else: ?><div class="apv2-img-placeholder">📰</div><?php endif; ?>
              <div class="apv2-featured-overlay"></div>
              <div class="apv2-featured-overlay-content">
                <span class="apv2-featured-pill"><svg width="8" height="8" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg> Latest Article</span>
                <a href="<?php echo esc_url($fu); ?>" class="apv2-featured-cat-badge"><?php echo esc_html($fn); ?></a>
              </div>
            </a>
            <div class="apv2-featured-body">
              <div class="apv2-featured-meta">
                <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y'); ?></time>
                <span class="apv2-meta-dot">·</span><span><?php echo esc_html(fnx_read_time()); ?></span>
              </div>
              <a href="<?php the_permalink(); ?>" class="apv2-featured-title" itemprop="headline"><?php the_title(); ?></a>
              <p class="apv2-featured-excerpt" itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
              <div class="apv2-featured-footer">
                <div class="apv2-author"><?php echo get_avatar(get_the_author_meta('email'), 32, '', '', ['class' => 'apv2-avatar']); ?><span itemprop="author"><?php the_author(); ?></span></div>
                <a href="<?php the_permalink(); ?>" class="apv2-read-btn">Read Article <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
              </div>
            </div>
          </article>
          <?php endif; ?>

          <!-- POSTS GRID -->
          <div class="apv2-grid">
            <?php
            $cat_color_map = ['funding' => 'green', 'startups' => 'blue', 'business' => 'amber', 'valuation' => 'purple', 'technology' => 'cyan'];
            while ($articles_query->have_posts()): $articles_query->the_post();
            $gc = get_the_category(); $gn = $gc ? $gc[0]->name : 'Article'; $gu = $gc ? get_category_link($gc[0]->term_id) : '#';
            $cat_color = $gc ? ($cat_color_map[$gc[0]->slug] ?? 'green') : 'green'; ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class("apv2-card apv2-card--{$cat_color}"); ?> itemscope itemtype="https://schema.org/Article">
              <a href="<?php the_permalink(); ?>" class="apv2-card-img-wrap" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail()): ?><div class="apv2-card-img"><?php the_post_thumbnail('fnx-card', ['loading' => 'lazy', 'itemprop' => 'image', 'alt' => get_the_title()]); ?></div><?php else: ?><div class="apv2-card-img apv2-card-img--empty"><span>📰</span></div><?php endif; ?>
                <a href="<?php echo esc_url($gu); ?>" class="apv2-card-cat-badge apv2-card-cat-badge--<?php echo $cat_color; ?>" itemprop="articleSection"><?php echo esc_html($gn); ?></a>
              </a>
              <div class="apv2-card-body">
                <div class="apv2-card-meta">
                  <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y'); ?></time>
                  <span class="apv2-meta-dot">·</span><span><?php echo esc_html(fnx_read_time()); ?></span>
                </div>
                <a href="<?php the_permalink(); ?>" class="apv2-card-title" itemprop="headline"><?php the_title(); ?></a>
                <p class="apv2-card-excerpt" itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 16); ?></p>
                <div class="apv2-card-footer">
                  <div class="apv2-author apv2-author--sm"><?php echo get_avatar(get_the_author_meta('email'), 22, '', '', ['class' => 'apv2-avatar']); ?><span itemprop="author"><?php the_author(); ?></span></div>
                  <span class="apv2-card-arrow">Read →</span>
                </div>
              </div>
            </article>
            <?php endwhile; ?>
          </div>

          <!-- PAGINATION -->
          <?php if ($articles_query->max_num_pages > 1): ?>
          <nav class="apv2-pagination" aria-label="Articles pages">
            <?php
            $pag_base = add_query_arg('paged', '%#%', $page_url);
            if ($active_cat_slug) $pag_base = add_query_arg('cat', $active_cat_slug, $pag_base);
            if ($search_query)    $pag_base = add_query_arg('s', $search_query, $pag_base);
            $links = paginate_links(['base' => $pag_base, 'format' => '', 'current' => $paged, 'total' => $articles_query->max_num_pages, 'prev_text' => '← Prev', 'next_text' => 'Next →', 'type' => 'array', 'end_size' => 1, 'mid_size' => 1]);
            if ($links): foreach ($links as $link):
              $link = str_replace(['class="page-numbers current"','class="page-numbers dots"','class="page-numbers"','class="prev page-numbers"','class="next page-numbers"'], ['class="apv2-pag-btn active" aria-current="page"','class="apv2-pag-dots"','class="apv2-pag-btn"','class="apv2-pag-btn apv2-pag-nav"','class="apv2-pag-btn apv2-pag-nav"'], $link);
              echo $link;
            endforeach; endif;
            ?>
          </nav>
          <?php endif; ?>

        <?php else: ?>
          <div class="apv2-no-posts">
            <div class="apv2-no-posts-icon">🔍</div>
            <h3>No articles found</h3>
            <p><?php echo $search_query ? 'No results for "' . esc_html($search_query) . '". Try a different term.' : 'No articles in this category yet.'; ?></p>
            <a href="<?php echo esc_url($page_url); ?>" class="apv2-read-btn">View All Articles</a>
          </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

        <!-- CATEGORY SECTIONS -->
        <?php if ($paged === 1 && !$active_cat_id && !$search_query): ?>
        <?php foreach ($cat_sections as $cs):
          $sec_cat = get_category_by_slug($cs['slug']); if (!$sec_cat) continue;
          $sec_posts = new WP_Query(['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $cs['count'], 'cat' => $sec_cat->term_id, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true]);
          if (!$sec_posts->have_posts()) continue; ?>
        <section class="apv2-cat-section apv2-cat-section--<?php echo esc_attr($cs['color']); ?>">
          <div class="apv2-cat-header">
            <div class="apv2-cat-header-left"><span class="apv2-cat-icon"><?php echo $cs['icon']; ?></span><h2 class="apv2-cat-title"><?php echo esc_html($cs['label']); ?></h2></div>
            <a href="<?php echo esc_url(get_category_link($sec_cat->term_id)); ?>" class="apv2-cat-viewall">View all →</a>
          </div>
          <div class="apv2-list">
            <?php $idx = 0; while ($sec_posts->have_posts()): $sec_posts->the_post(); $idx++; ?>
            <article class="apv2-list-item<?php echo $idx === 1 ? ' apv2-list-item--lead' : ''; ?>" itemscope itemtype="https://schema.org/Article">
              <a href="<?php the_permalink(); ?>" class="apv2-list-thumb" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail()): the_post_thumbnail('fnx-thumb', ['loading' => 'lazy', 'itemprop' => 'image', 'alt' => get_the_title()]); else: ?><div class="apv2-img-placeholder" style="width:100%;height:100%">📰</div><?php endif; ?>
              </a>
              <div class="apv2-list-body">
                <a href="<?php the_permalink(); ?>" class="apv2-list-title" itemprop="headline"><?php the_title(); ?></a>
                <div class="apv2-list-meta"><time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time><span class="apv2-meta-dot">·</span><span><?php echo esc_html(fnx_read_time()); ?></span></div>
              </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </section>
        <?php endforeach; ?>
        <?php endif; ?>

      </main>

      <!-- SIDEBAR -->
      <aside class="apv2-sidebar" aria-label="Articles sidebar">

        <div class="apv2-widget apv2-widget--trending">
          <h3 class="apv2-widget-title"><span class="apv2-widget-icon">🔥</span> Trending Articles</h3>
          <div class="apv2-trending-list">
            <?php foreach ($trending_posts as $i => $tp): setup_postdata($tp); ?>
            <a href="<?php echo esc_url(get_permalink($tp->ID)); ?>" class="apv2-trending-item">
              <span class="apv2-trending-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
              <div class="apv2-trending-body"><span class="apv2-trending-title"><?php echo esc_html(get_the_title($tp->ID)); ?></span><span class="apv2-trending-date"><?php echo get_the_date('M j, Y', $tp->ID); ?></span></div>
            </a>
            <?php endforeach; wp_reset_postdata(); ?>
          </div>
        </div>

        <div class="apv2-widget">
          <h3 class="apv2-widget-title"><span class="apv2-widget-icon">🗂️</span> Browse by Category</h3>
          <ul class="apv2-cat-list">
            <?php $sb_cats = get_categories(['hide_empty' => true, 'parent' => 0, 'orderby' => 'count', 'order' => 'DESC', 'number' => 10]); foreach ($sb_cats as $scat): $ic = $cat_icons[$scat->slug] ?? '📌'; ?>
            <li><a href="<?php echo esc_url(get_category_link($scat->term_id)); ?>" class="apv2-cat-link"><span class="apv2-cat-link-icon"><?php echo $ic; ?></span><?php echo esc_html($scat->name); ?></a><span class="apv2-cat-count"><?php echo (int) $scat->count; ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="apv2-widget">
          <h3 class="apv2-widget-title"><span class="apv2-widget-icon">🏷️</span> Focus Topics</h3>
          <div class="apv2-tags">
            <?php $top_tags = get_tags(['orderby' => 'count', 'order' => 'DESC', 'number' => 12, 'hide_empty' => true]); foreach ($top_tags as $tt): ?>
            <a href="<?php echo esc_url(get_tag_link($tt->term_id)); ?>" class="apv2-tag"><?php echo esc_html($tt->name); ?></a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="apv2-widget apv2-widget--newsletter">
          <div class="apv2-nl-bg"></div>
          <div class="apv2-nl-content">
            <span class="apv2-nl-emoji">📬</span>
            <h3 class="apv2-nl-title">Stay Updated</h3>
            <p class="apv2-nl-desc">Get the latest articles on startup funding and growth. No spam — ever.</p>
            <?php if (function_exists('mc4wp_show_form')): mc4wp_show_form(); else: ?>
            <form class="apv2-nl-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
              <input type="hidden" name="action" value="fnx_newsletter"><?php wp_nonce_field('fnx_newsletter'); ?>
              <input type="email" name="email" placeholder="Your email address" required>
              <button type="submit">Subscribe →</button>
            </form>
            <p class="apv2-nl-note">Join 2,000+ readers. Free forever.</p>
            <?php endif; ?>
          </div>
        </div>

      </aside>
    </div>
  </div>
</div>

<?php get_footer(); ?>
