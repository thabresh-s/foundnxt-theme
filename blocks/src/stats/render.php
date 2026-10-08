<?php
$s1 = $attributes['stat1'] ?? '90%';   $l1 = $attributes['label1'] ?? 'Stat one';
$s2 = $attributes['stat2'] ?? '3x';    $l2 = $attributes['label2'] ?? 'Stat two';
$s3 = $attributes['stat3'] ?? '₹500';  $l3 = $attributes['label3'] ?? 'Stat three';
?>
<div class="fnx-stats-row">
  <div class="fnx-stat-card"><div class="fnx-stat-number"><?php echo esc_html($s1); ?></div><div class="fnx-stat-label"><?php echo esc_html($l1); ?></div></div>
  <div class="fnx-stat-card"><div class="fnx-stat-number"><?php echo esc_html($s2); ?></div><div class="fnx-stat-label"><?php echo esc_html($l2); ?></div></div>
  <div class="fnx-stat-card"><div class="fnx-stat-number"><?php echo esc_html($s3); ?></div><div class="fnx-stat-label"><?php echo esc_html($l3); ?></div></div>
</div>