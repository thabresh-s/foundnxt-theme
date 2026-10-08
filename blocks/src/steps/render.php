<?php
$heading = $attributes['heading'] ?? 'Step-by-Step Guide';
$steps   = $attributes['steps']   ?? [];
?>
<div class="fnx-steps">
  <h3><?php echo esc_html($heading); ?></h3>
  <?php foreach($steps as $i => $step): ?>
  <div class="fnx-step">
    <div class="fnx-step-num"><?php echo str_pad($i+1,2,'0',STR_PAD_LEFT); ?></div>
    <div class="fnx-step-content">
      <h4 class="fnx-step-title"><?php echo esc_html($step['title'] ?? ''); ?></h4>
      <p class="fnx-step-desc"><?php echo esc_html($step['desc'] ?? ''); ?></p>
    </div>
  </div>
  <?php endforeach; ?>
</div>