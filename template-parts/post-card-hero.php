<?php
/**
 * Template Part: Hero Post Card (first post — full width)
 * @package FoundNXT
 */
$cats     = get_the_category();
$cat_name = $cats ? $cats[0]->name : __('Article', 'foundnxt');
$cat_url  = $cats ? get_category_link($cats[0]->term_id) : '#';
$featured = get_post_meta(get_the_ID(), 'fnx_featured', true);
$badge    = get_post_meta(get_the_ID(), 'fnx_post_badge', true);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-card post-card-hero'); ?> itemscope itemtype="https://schema.org/Article">

  <a href="<?php the_permalink(); ?>" class="post-card-thumb-link" tabindex="-1" aria-hidden="true">
    <?php if (has_post_thumbnail()): ?>
      <div class="post-card-thumb"><?php the_post_thumbnail('fnx-hero', ['loading' => 'eager', 'itemprop' => 'image']); ?></div>
    <?php else: ?>
      <div class="post-card-thumb"><div class="post-card-thumb-placeholder" style="font-size:60px">📰</div></div>
    <?php endif; ?>
  </a>

  <div class="post-card-body">
    <div class="post-card-meta">
      <?php if ($featured || $badge): ?>
        <span class="post-badge"><?php echo esc_html($badge ?: '🔥 ' . __('Featured', 'foundnxt')); ?></span>
      <?php endif; ?>
      <a href="<?php echo esc_url($cat_url); ?>" class="post-card-cat"><?php echo esc_html($cat_name); ?></a>
      <time class="post-card-date" datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time>
      <span class="post-card-read"><?php echo esc_html(fnx_read_time()); ?></span>
    </div>

    <a href="<?php the_permalink(); ?>" class="post-card-title" itemprop="headline"><?php the_title(); ?></a>

    <p class="post-card-excerpt" itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 28); ?></p>

    <div class="post-card-footer">
      <div class="post-card-author">
        <?php echo get_avatar(get_the_author_meta('email'), 28, '', '', ['class' => 'avatar']); ?>
        <span itemprop="author"><?php the_author(); ?></span>
      </div>
      <a href="<?php the_permalink(); ?>" class="btn-primary" style="font-size:12px;padding:.5em 1.2em"><?php _e('Read Article →', 'foundnxt'); ?></a>
    </div>
  </div>
</article>
