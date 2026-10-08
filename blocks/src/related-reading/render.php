<?php
$label = $attributes['label'] ?? '📚 Related Reading';
$links = $attributes['links'] ?? [];
?>
<div class="fnx-related-inline">
  <div class="fnx-related-label"><?php echo esc_html($label); ?></div>
  <ul class="fnx-related-list">
    <?php foreach($links as $link): ?>
    <li><a href="<?php echo esc_url($link['url'] ?? '#'); ?>"><?php echo esc_html($link['text'] ?? ''); ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>