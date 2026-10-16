<?php
/**
 * Block: Download & Resource Card
 * @package FoundNXT
 */
$icon    = $attributes['icon']    ?? '📊';
$title   = $attributes['title']   ?? __('SaaS Valuation & Unit Economics Financial Model', 'foundnxt');
$meta    = $attributes['meta']    ?? __('Excel Spreadsheet · Free Resource', 'foundnxt');
$desc    = $attributes['desc']    ?? __('Fully customizable 3-statement model including cohort retention curves and CAC payback tables.', 'foundnxt');
$btnText = $attributes['btnText'] ?? __('Download Template →', 'foundnxt');
$btnUrl  = $attributes['btnUrl']  ?? '#';
?>
<div class="fnx-block fnx-resource-box">
  <div class="fnx-resource-media">
    <span class="fnx-resource-icon-badge" aria-hidden="true"><?php echo esc_html($icon); ?></span>
  </div>
  <div class="fnx-resource-content">
    <div class="fnx-resource-meta-pill"><?php echo esc_html($meta); ?></div>
    <h4 class="fnx-resource-title"><?php echo esc_html($title); ?></h4>
    <?php if (!empty($desc)): ?>
      <p class="fnx-resource-desc"><?php echo esc_html($desc); ?></p>
    <?php endif; ?>
  </div>
  <div class="fnx-resource-cta">
    <a href="<?php echo esc_url($btnUrl); ?>" class="fnx-resource-btn">
      <span><?php echo esc_html($btnText); ?></span>
    </a>
  </div>
</div>