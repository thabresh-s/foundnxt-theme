<?php
$heading = $attributes['heading'] ?? 'Timeline';
$items   = $attributes['items']   ?? [];
?>
<div class="fnx-timeline">
  <h3><?php echo esc_html($heading); ?></h3>
  <?php foreach($items as $item): ?>
  <div class="fnx-tline-item">
    <div class="fnx-tline-year"><?php echo esc_html($item['year'] ?? ''); ?></div>
    <div class="fnx-tline-body">
      <div class="fnx-tline-title"><?php echo esc_html($item['title'] ?? ''); ?></div>
      <div class="fnx-tline-desc"><?php echo esc_html($item['desc'] ?? ''); ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>