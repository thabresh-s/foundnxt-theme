<?php
$type    = $attributes['type'] ?? 'info';
$content = $attributes['content'] ?? 'Add your note content here.';
$icons   = ['info'=>'📌','warning'=>'⚠️','success'=>'✅','danger'=>'🚫'];
$labels  = ['info'=>'Note','warning'=>'Warning','success'=>'Tip','danger'=>'Important'];
$icon    = $icons[$type] ?? '📌';
$label   = $labels[$type] ?? 'Note';
?>
<div class="fnx-note fnx-note--<?php echo esc_attr($type); ?>">
  <p><strong><?php echo $icon; ?> <?php echo esc_html($label); ?>:</strong> <?php echo wp_kses_post($content); ?></p>
</div>