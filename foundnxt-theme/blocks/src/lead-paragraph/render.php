<?php
$content = $attributes['content'] ?? 'Start your article with a strong opening paragraph.';
?>
<p class="fnx-lead-para"><?php echo wp_kses_post($content); ?></p>