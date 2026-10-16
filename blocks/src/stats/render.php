<?php
/**
 * Block: Metrics & KPI Grid
 * @package FoundNXT
 */
$heading = $attributes['heading'] ?? '';
$source  = $attributes['source'] ?? '';

$stats = [];
for ($i = 1; $i <= 4; $i++) {
    $num = $attributes["stat{$i}"] ?? '';
    $lbl = $attributes["label{$i}"] ?? '';
    $chg = $attributes["change{$i}"] ?? '';
    if (!empty(trim($num))) {
        $stats[] = [
            'num'    => $num,
            'lbl'    => $lbl,
            'change' => $chg,
        ];
    }
}

if (empty($stats)) {
    $stats = [
        ['num' => '90%',  'lbl' => __('Startup Failure Rate Within 5 Years', 'foundnxt'), 'change' => 'Industry Benchmark'],
        ['num' => '3.4x', 'lbl' => __('Median Seed to Series A Markup', 'foundnxt'), 'change' => 'Top Quartile'],
        ['num' => '₹50Cr', 'lbl' => __('Average Series A Round Sizing', 'foundnxt'), 'change' => '+18% YoY'],
    ];
}

$count = count($stats);
?>
<div class="fnx-block fnx-stats-card">
  <?php if (!empty($heading)): ?>
    <div class="fnx-stats-card-heading"><?php echo esc_html($heading); ?></div>
  <?php endif; ?>
  <div class="fnx-stats-grid fnx-stats-grid--<?php echo esc_attr($count); ?>">
    <?php foreach ($stats as $s): ?>
      <div class="fnx-stat-item">
        <div class="fnx-stat-number"><?php echo esc_html($s['num']); ?></div>
        <div class="fnx-stat-label"><?php echo esc_html($s['lbl']); ?></div>
        <?php if (!empty($s['change'])): ?>
          <span class="fnx-stat-badge"><?php echo esc_html($s['change']); ?></span>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if (!empty($source)): ?>
    <div class="fnx-stats-source">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
      <?php echo esc_html(sprintf(__('Source: %s', 'foundnxt'), $source)); ?>
    </div>
  <?php endif; ?>
</div>