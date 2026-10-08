<?php
$eyebrow  = $attributes['eyebrow']  ?? 'Chapter 01';
$heading  = $attributes['heading']  ?? 'Section Heading';
$subtitle = $attributes['subtitle'] ?? '';
?>
<div class="fnx-section-hdr">
  <div class="fnx-section-eyebrow"><?php echo esc_html($eyebrow); ?></div>
  <h2 class="fnx-section-title"><?php echo esc_html($heading); ?></h2>
  <?php if($subtitle): ?><p class="fnx-section-sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
</div>