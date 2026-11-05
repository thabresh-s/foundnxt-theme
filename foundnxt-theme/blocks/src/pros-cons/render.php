<?php
/**
 * Block: Pros & Cons Comparison Card
 * @package FoundNXT
 */
$pros_raw = $attributes['prosItems'] ?? ['First key advantage', 'Strong unit economics', 'Fast market adoption'];
$cons_raw = $attributes['consItems'] ?? ['Higher initial customer acquisition cost', 'Requires dedicated implementation', 'Competitive sector'];

$pros_title = $attributes['prosTitle'] ?? __('Advantages / Pros', 'foundnxt');
$cons_title = $attributes['consTitle'] ?? __('Drawbacks / Cons', 'foundnxt');

// Support both array and multi-line string (for seamless copy-pasting!)
$pros = is_array($pros_raw) ? $pros_raw : array_filter(array_map('trim', explode("\n", (string)$pros_raw)));
$cons = is_array($cons_raw) ? $cons_raw : array_filter(array_map('trim', explode("\n", (string)$cons_raw)));
?>
<div class="fnx-block fnx-pros-cons-card">
  <div class="fnx-pc-grid">
    <div class="fnx-pc-col fnx-pc-pros">
      <div class="fnx-pc-header">
        <span class="fnx-pc-badge fnx-pc-badge--pro">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <?php echo esc_html($pros_title); ?>
        </span>
      </div>
      <ul class="fnx-pc-list">
        <?php foreach ($pros as $p): if (!trim($p)) continue; ?>
          <li class="fnx-pc-item">
            <span class="fnx-pc-icon fnx-pc-icon--pro" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </span>
            <span class="fnx-pc-text"><?php echo wp_kses_post($p); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="fnx-pc-col fnx-pc-cons">
      <div class="fnx-pc-header">
        <span class="fnx-pc-badge fnx-pc-badge--con">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          <?php echo esc_html($cons_title); ?>
        </span>
      </div>
      <ul class="fnx-pc-list">
        <?php foreach ($cons as $c): if (!trim($c)) continue; ?>
          <li class="fnx-pc-item">
            <span class="fnx-pc-icon fnx-pc-icon--con" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </span>
            <span class="fnx-pc-text"><?php echo wp_kses_post($c); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>