<?php
$cards = $attributes['cards'] ?? [];
?>
<div class="fnx-feature-cards">
  <div class="fnx-feat-grid">
    <?php foreach($cards as $card): ?>
    <div class="fnx-feat-card">
      <div class="fnx-feat-icon"><?php echo esc_html($card['icon'] ?? '⭐'); ?></div>
      <h4 class="fnx-feat-title"><?php echo esc_html($card['title'] ?? ''); ?></h4>
      <p class="fnx-feat-desc"><?php echo esc_html($card['desc'] ?? ''); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</div>