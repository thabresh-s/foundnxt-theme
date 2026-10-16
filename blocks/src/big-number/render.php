<?php
$figure  = $attributes['figure']  ?? '₹1Cr';
$context = $attributes['context'] ?? '';
$label   = $attributes['label']   ?? '';
$badge   = $attributes['badge']   ?? '';
$source  = $attributes['source']  ?? '';
?>
<div class="fnx-big-number">
  <?php if (!empty($label) || !empty($badge)): ?>
    <div class="fnx-bnum-header">
      <?php if (!empty($label)): ?>
        <span class="fnx-bnum-label"><?php echo esc_html($label); ?></span>
      <?php endif; ?>
      <?php if (!empty($badge)): ?>
        <span class="fnx-bnum-badge"><?php echo esc_html($badge); ?></span>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="fnx-bnum-figure"><?php echo esc_html($figure); ?></div>
  <?php if (!empty($context)): ?>
    <p class="fnx-bnum-context"><?php echo esc_html($context); ?></p>
  <?php endif; ?>
  <?php if (!empty($source)): ?>
    <div class="fnx-bnum-source">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
      <span><?php echo esc_html($source); ?></span>
    </div>
  <?php endif; ?>
</div>