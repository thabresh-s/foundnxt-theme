<?php
$icon    = $attributes['icon']    ?? '📄';
$title   = $attributes['title']   ?? 'Resource Name';
$meta    = $attributes['meta']    ?? 'PDF · Free';
$btnText = $attributes['btnText'] ?? 'Download →';
$btnUrl  = $attributes['btnUrl']  ?? '#';
?>
<div class="fnx-resource-card">
  <div class="fnx-resource-icon"><?php echo esc_html($icon); ?></div>
  <div class="fnx-resource-body">
    <div class="fnx-resource-title"><?php echo esc_html($title); ?></div>
    <div class="fnx-resource-meta"><?php echo esc_html($meta); ?></div>
  </div>
  <a href="<?php echo esc_url($btnUrl); ?>" class="fnx-resource-btn"><?php echo esc_html($btnText); ?></a>
</div>