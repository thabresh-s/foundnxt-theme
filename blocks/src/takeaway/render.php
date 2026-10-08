<?php
$content = $attributes['content'] ?? 'Summarise the key insight here.';
?>
<div class="fnx-takeaway">
  <h4 class="fnx-takeaway-heading">💡 Key Takeaway</h4>
  <p class="fnx-takeaway-text"><?php echo wp_kses_post($content); ?></p>
</div>