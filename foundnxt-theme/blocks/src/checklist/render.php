<?php
/**
 * Block: Action Checklist
 * @package FoundNXT
 */
$title     = $attributes['title'] ?? __('Action Checklist', 'foundnxt');
$items_raw = $attributes['items'] ?? [];

$items = is_array($items_raw) ? $items_raw : array_filter(array_map('trim', explode("\n", (string)$items_raw)));

if (empty($items)) {
    $items = [
        'Complete competitive landscape benchmarking matrix',
        'Verify customer cohort retention and gross margin payback',
        'Audit cap table and intellectual property assignment agreements',
        'Finalize 12-month forward runway and hiring forecasts',
    ];
}
?>
<div class="fnx-block fnx-checklist-card">
  <?php if (!empty($title)): ?>
    <div class="fnx-checklist-header">
      <span class="fnx-checklist-badge">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <?php echo esc_html($title); ?>
      </span>
    </div>
  <?php endif; ?>
  <ul class="fnx-checklist-items">
    <?php foreach ($items as $item): if (!trim($item)) continue; ?>
      <li class="fnx-checklist-item">
        <span class="fnx-checklist-checkbox" aria-hidden="true">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </span>
        <span class="fnx-checklist-label"><?php echo wp_kses_post($item); ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
</div>