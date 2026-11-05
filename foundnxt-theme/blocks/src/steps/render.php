<?php
/**
 * Block: Step-by-Step Execution Guide
 * @package FoundNXT
 */
$heading = $attributes['heading'] ?? __('Step-by-Step Guide', 'foundnxt');
$steps   = $attributes['steps']   ?? [];

if (empty($steps)) {
    $steps = [
        ['title' => 'Conduct Market Opportunity Sizing', 'desc' => 'Calculate bottom-up addressable market using validated customer segment numbers.'],
        ['title' => 'Build a Defensible Financial Model', 'desc' => 'Structure unit economics with realistic CAC payback periods and margin expansion curves.'],
        ['title' => 'Pitch Qualified Seed Investors', 'desc' => 'Focus outreach on sector-aligned leads who write active thesis-driven checks.'],
    ];
}
?>
<section class="fnx-block fnx-steps-card">
  <?php if (!empty($heading)): ?>
    <h3 class="fnx-steps-heading"><?php echo esc_html($heading); ?></h3>
  <?php endif; ?>
  <ol class="fnx-steps-list">
    <?php foreach ($steps as $i => $step): 
      $title = $step['title'] ?? '';
      $desc  = $step['desc'] ?? '';
      if (!$title && !$desc) continue;
    ?>
    <li class="fnx-step-item">
      <div class="fnx-step-marker" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
      <div class="fnx-step-body">
        <?php if (!empty($title)): ?>
          <h4 class="fnx-step-title"><?php echo esc_html($title); ?></h4>
        <?php endif; ?>
        <?php if (!empty($desc)): ?>
          <div class="fnx-step-desc"><?php echo wp_kses_post(wpautop($desc)); ?></div>
        <?php endif; ?>
      </div>
    </li>
    <?php endforeach; ?>
  </ol>
</section>