<?php
$title = $attributes['title'] ?? 'Checklist';
$items = $attributes['items'] ?? [];
?>
<div class="fnx-checklist">
  <h4 class="fnx-checklist-title">✅ <?php echo esc_html($title); ?></h4>
  <ul class="fnx-check-list">
    <?php foreach($items as $item): ?>
    <li class="fnx-check-item"><span class="fnx-check-icon">✓</span><span><?php echo esc_html($item); ?></span></li>
    <?php endforeach; ?>
  </ul>
</div>