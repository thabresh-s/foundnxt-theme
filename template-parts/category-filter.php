<?php
$cats = get_categories(['orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true, 'number' => 10]);
if (empty($cats)) return;
?>
<div class="category-filter" role="list" aria-label="<?php esc_attr_e('Filter by category', 'foundnxt'); ?>">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="cat-filter-chip <?php echo !is_category() ? 'active' : ''; ?>" data-cat="0" role="listitem">
    <?php _e('All', 'foundnxt'); ?>
  </a>
  <?php foreach ($cats as $cat): ?>
    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
       class="cat-filter-chip <?php echo is_category($cat->term_id) ? 'active' : ''; ?>"
       data-cat="<?php echo $cat->term_id; ?>"
       role="listitem">
      <?php echo esc_html($cat->name); ?>
      <span><?php echo $cat->count; ?></span>
    </a>
  <?php endforeach; ?>
</div>
