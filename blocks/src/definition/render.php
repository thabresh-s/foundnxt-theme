<?php
/**
 * Block: Term Definition & Glossary Box
 * @package FoundNXT
 */
$term  = $attributes['term']         ?? 'Net Revenue Retention (NRR)';
$pos   = $attributes['partOfSpeech'] ?? 'B2B Metric';
$def   = $attributes['definition']   ?? 'The percentage of recurring revenue retained from existing customers over a given time period, factoring in contract expansion, upsells, downgrades, and churn.';
?>
<div class="fnx-block fnx-definition-card">
  <div class="fnx-def-header">
    <dfn class="fnx-def-term"><?php echo esc_html($term); ?></dfn>
    <?php if (!empty($pos)): ?>
      <span class="fnx-def-badge"><?php echo esc_html($pos); ?></span>
    <?php endif; ?>
  </div>
  <div class="fnx-def-body">
    <p class="fnx-def-text"><?php echo wp_kses_post($def); ?></p>
  </div>
</div>