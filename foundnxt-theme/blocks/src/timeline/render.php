<?php
/**
 * Block: Milestone Timeline
 * @package FoundNXT
 */
$heading = $attributes['heading'] ?? __('Milestone Timeline', 'foundnxt');
$items   = $attributes['items']   ?? [];

if (empty($items)) {
    $items = [
        ['year' => 'Phase 1', 'title' => 'Product Validation & Early Pilots', 'desc' => 'Secured 8 paid enterprise design partners and reached $25k monthly recurring revenue.'],
        ['year' => 'Phase 2', 'title' => 'Seed Round & Talent Scaling', 'desc' => 'Closed $2.5M seed financing led by Tier-1 venture funds to accelerate autonomous engineering.'],
        ['year' => 'Phase 3', 'title' => 'Global Expansion & Multi-Region GTM', 'desc' => 'Expanded into North America and EMEA with 120% net revenue retention.'],
    ];
}
?>
<section class="fnx-block fnx-timeline-card">
  <?php if (!empty($heading)): ?>
    <h3 class="fnx-timeline-heading"><?php echo esc_html($heading); ?></h3>
  <?php endif; ?>
  <div class="fnx-timeline-track">
    <?php foreach ($items as $item): 
      $year  = $item['year'] ?? '';
      $title = $item['title'] ?? '';
      $desc  = $item['desc'] ?? '';
      if (!$year && !$title && !$desc) continue;
    ?>
    <div class="fnx-timeline-node">
      <div class="fnx-timeline-marker">
        <span class="fnx-timeline-pill"><?php echo esc_html($year); ?></span>
        <span class="fnx-timeline-dot" aria-hidden="true"></span>
      </div>
      <div class="fnx-timeline-content">
        <?php if (!empty($title)): ?>
          <h4 class="fnx-timeline-title"><?php echo esc_html($title); ?></h4>
        <?php endif; ?>
        <?php if (!empty($desc)): ?>
          <div class="fnx-timeline-desc"><?php echo wp_kses_post(wpautop($desc)); ?></div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>