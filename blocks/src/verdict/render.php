<?php
$label      = $attributes['label']      ?? 'Our Verdict';
$text       = $attributes['text']       ?? 'Write your verdict here.';
$score      = $attributes['score']      ?? '8.5 / 10';
$badge      = $attributes['badge']      ?? 'Recommended';
$badgeColor = $attributes['badgeColor'] ?? 'green';
?>
<div class="fnx-verdict">
  <h4 class="fnx-verdict-label">⚖️ <?php echo esc_html($label); ?></h4>
  <p class="fnx-verdict-text"><?php echo esc_html($text); ?></p>
  <div class="fnx-verdict-rating">
    <span class="fnx-verdict-score"><?php echo esc_html($score); ?></span>
    <span class="fnx-verdict-badge fnx-verdict-badge--<?php echo esc_attr($badgeColor); ?>"><?php echo esc_html($badge); ?></span>
  </div>
</div>