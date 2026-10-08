<?php /* sidebar.php */ ?>
<aside class="fnx-sidebar arc-sidebar" role="complementary" aria-label="<?php esc_attr_e('Blog Sidebar', 'foundnxt'); ?>">

  <!-- Search -->
  <div class="widget arc-widget-search">
    <h3 class="widget-title"><?php _e('Search', 'foundnxt'); ?></h3>
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="arc-search-form" role="search">
      <input type="search" name="s" placeholder="<?php esc_attr_e('Search articles…', 'foundnxt'); ?>"
             value="<?php echo esc_attr(get_search_query()); ?>" aria-label="<?php esc_attr_e('Search', 'foundnxt'); ?>">
      <button type="submit" aria-label="<?php esc_attr_e('Submit search', 'foundnxt'); ?>">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
    </form>
  </div>

  <!-- Categories -->
  <div class="widget">
    <h3 class="widget-title"><?php _e('Browse Categories', 'foundnxt'); ?></h3>
    <ul class="arc-cat-list">
      <?php
      $cats = get_categories(['hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 10]);
      foreach ($cats as $cat): ?>
      <li>
        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
          <?php echo esc_html($cat->name); ?>
        </a>
        <span class="arc-cat-count"><?php echo (int) $cat->count; ?></span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <!-- Featured Posts — no image -->
  <div class="widget">
    <h3 class="widget-title"><?php _e('Featured Articles', 'foundnxt'); ?></h3>
    <div class="arc-featured-list">
      <?php
      $featured = get_posts(['posts_per_page' => 5, 'post_status' => 'publish', 'meta_key' => 'fnx_featured', 'meta_value' => '1']);
      if (empty($featured)) {
        $featured = get_posts(['posts_per_page' => 5, 'post_status' => 'publish', 'orderby' => 'comment_count']);
      }
      foreach ($featured as $i => $fp):
        $fc = get_the_category($fp->ID);
        $fn = $fc ? $fc[0]->name : '';
      ?>
      <a href="<?php echo esc_url(get_permalink($fp->ID)); ?>" class="arc-fl-item">
        <span class="arc-fl-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
        <div class="arc-fl-body">
          <?php if ($fn): ?><span class="arc-fl-cat"><?php echo esc_html($fn); ?></span><?php endif; ?>
          <div class="arc-fl-title"><?php echo esc_html(get_the_title($fp->ID)); ?></div>
          <div class="arc-fl-date"><?php echo get_the_date('M j, Y', $fp->ID); ?></div>
        </div>
      </a>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
  </div>

  <!-- Popular Topics -->
  <div class="widget">
    <h3 class="widget-title"><?php _e('Popular Topics', 'foundnxt'); ?></h3>
    <div class="ap-tag-cloud">
      <?php
      $tags = get_tags(['orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hide_empty' => true]);
      foreach ($tags as $tag): ?>
      <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="ap-tag"><?php echo esc_html($tag->name); ?></a>
      <?php endforeach; ?>
    </div>
  </div>

</aside>
