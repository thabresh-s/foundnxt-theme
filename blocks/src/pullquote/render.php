<?php
$quote = $attributes['quote'] ?? 'Add your quote here.';
$cite  = $attributes['cite']  ?? '— Source';
?>
<div class="fnx-pullquote">
  <blockquote class="fnx-pullquote-inner">
    <p><?php echo esc_html($quote); ?></p>
    <cite><?php echo esc_html($cite); ?></cite>
  </blockquote>
</div>