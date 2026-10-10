<?php
/**
 * Template Part: Post Card (typographic — no featured image)
 * @package FoundNXT
 */
$cats      = get_the_category();
$cat_name  = $cats ? $cats[0]->name : __('Article', 'foundnxt');
$cat_url   = $cats ? get_category_link($cats[0]->term_id) : '#';
$read_time = fnx_read_time();

// Assign a deterministic accent color per category
$cat_colors = ['#4f46e5','#2563eb','#7c3aed','#d97706','#e11d48','#0891b2','#059669','#dc2626'];
$color_idx  = $cats ? ($cats[0]->term_id % count($cat_colors)) : 0;
$accent     = $cat_colors[$color_idx];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?> itemscope itemtype="https://schema.org/Article"
         style="--card-accent:<?php echo esc_attr($accent); ?>">

  <div class="post-card-accent-strip"></div>

  <div class="post-card-body">
    <div class="post-card-meta">
      <a href="<?php echo esc_url($cat_url); ?>" class="post-card-cat" itemprop="articleSection"><?php echo esc_html($cat_name); ?></a>
      <time class="post-card-date" datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y'); ?></time>
      <span class="post-card-read"><?php echo esc_html($read_time); ?></span>
    </div>

    <a href="<?php the_permalink(); ?>" class="post-card-title" itemprop="headline"><?php the_title(); ?></a>

    <p class="post-card-excerpt" itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 16); ?></p>

    <div class="post-card-footer">
      <div class="post-card-author">
        <?php echo get_avatar(get_the_author_meta('email'), 20, '', '', ['class' => 'avatar']); ?>
        <span itemprop="author"><?php the_author(); ?></span>
      </div>
      <a href="<?php the_permalink(); ?>" class="post-card-cta" aria-label="Read <?php the_title_attribute(); ?>">
        Read <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>

</article>
