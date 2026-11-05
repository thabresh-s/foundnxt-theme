<?php
/**
 * Category Filter Bar
 * Displayed as a filter bar above post grids on Articles and category archive pages.
 *
 * @package FoundNXT
 */

$primary_categories = fnx_get_primary_categories();
$current_slug = '';

if (is_category()) {
    $current_term = get_queried_object();
    $current_slug = ($current_term && isset($current_term->slug)) ? $current_term->slug : '';
} elseif (isset($_GET['cat'])) {
    $current_slug = sanitize_key($_GET['cat']);
}
?>
<nav class="fnx-category-filter-rail" aria-label="<?php esc_attr_e('Filter articles by category', 'foundnxt'); ?>">
  <div class="category-filter-scroll">
    <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="filter-chip <?php echo empty($current_slug) ? 'is-active' : ''; ?>">
      <span class="filter-chip-dot filter-chip-dot--all" aria-hidden="true"></span>
      <span><?php _e('All Articles', 'foundnxt'); ?></span>
    </a>
    <?php foreach ($primary_categories as $pcat):
      $is_active = ($current_slug === $pcat['slug']);
    ?>
      <a href="<?php echo esc_url($pcat['url']); ?>" class="filter-chip <?php echo $is_active ? 'is-active' : ''; ?>" style="--chip-accent: <?php echo esc_attr($pcat['color']); ?>;">
        <span class="filter-chip-dot" style="background: <?php echo esc_attr($pcat['color']); ?>;" aria-hidden="true"></span>
        <span><?php echo esc_html($pcat['name']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</nav>
