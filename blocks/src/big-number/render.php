<?php
$figure  = $attributes['figure']  ?? '₹1Cr';
$context = $attributes['context'] ?? 'Add context for this number.';
?>
<div class="fnx-big-number">
  <div class="fnx-bnum-figure"><?php echo esc_html($figure); ?></div>
  <p class="fnx-bnum-context"><?php echo esc_html($context); ?></p>
</div>