<?php
$content = $attributes['content'] ?? 'This article is for informational purposes only and does not constitute financial or investment advice.';
?>
<div class="fnx-risk-banner">
  <div class="fnx-risk-icon">⚠️</div>
  <p class="fnx-risk-text"><strong>Disclaimer:</strong> <?php echo wp_kses_post($content); ?></p>
</div>