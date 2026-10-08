<?php /* template-parts/trending.php */ ?>
<?php
$trending = get_posts(['posts_per_page' => 5, 'meta_key' => 'fnx_featured', 'meta_value' => '1', 'post_status' => 'publish']);
if (empty($trending)) $trending = get_posts(['posts_per_page' => 5, 'orderby' => 'comment_count', 'post_status' => 'publish']);
if (empty($trending)) return;
?>
<div class="fnx-trending">
  <div class="container">
    <div class="trending-inner">
      <span class="trending-label">🔥 <?php _e('Trending', 'foundnxt'); ?></span>
      <div class="trending-items">
        <?php foreach ($trending as $i => $p): ?>
          <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="trending-item">
            <span class="trending-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
            <?php echo esc_html(get_the_title($p->ID)); ?>
          </a>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</div>
