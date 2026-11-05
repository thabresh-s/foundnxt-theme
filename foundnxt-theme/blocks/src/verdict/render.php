<?php
/**
 * Block: Executive Verdict & Scorecard
 * @package FoundNXT
 */
$label      = $attributes['label']      ?? __('Executive Verdict', 'foundnxt');
$text       = $attributes['text']       ?? __('A standout solution for high-growth tech firms requiring automated multi-tier architecture, defensible margin expansion, and enterprise security compliance.', 'foundnxt');
$score      = $attributes['score']      ?? '9.2 / 10';
$badge      = $attributes['badge']      ?? __('Editor’s Choice', 'foundnxt');
$badgeColor = $attributes['badgeColor'] ?? 'brand';
?>
<div class="fnx-block fnx-verdict-card">
  <div class="fnx-verdict-header">
    <div class="fnx-verdict-title-wrap">
      <span class="fnx-verdict-tag">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <?php echo esc_html($label); ?>
      </span>
      <span class="fnx-verdict-badge fnx-verdict-badge--<?php echo esc_attr($badgeColor); ?>">
        <?php echo esc_html($badge); ?>
      </span>
    </div>
    <div class="fnx-verdict-score-box">
      <span class="fnx-verdict-score-label"><?php _e('Rating Score', 'foundnxt'); ?></span>
      <span class="fnx-verdict-score-value"><?php echo esc_html($score); ?></span>
    </div>
  </div>
  <div class="fnx-verdict-body">
    <p class="fnx-verdict-text"><?php echo wp_kses_post($text); ?></p>
  </div>
</div>