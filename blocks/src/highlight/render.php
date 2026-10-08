<?php
$content = $attributes['content'] ?? '🔑 Add a standout fact or stat here.';
?>
<div class="fnx-highlight-strip">
  <p><?php echo wp_kses_post($content); ?></p>
</div>