<?php
$headline = $attributes['headline'] ?? '';
$columns  = intval($attributes['columns'] ?? 3);
if ($columns < 2 || $columns > 4) {
    $columns = 3;
}
$cards = $attributes['cards'] ?? [];
if (!is_array($cards)) {
    $cards = [];
}
?>
<div class="fnx-feature-cards fnx-feat-cols-<?php echo esc_attr($columns); ?>">
  <?php if (!empty($headline)): ?>
    <div class="fnx-feat-heading">
      <span class="fnx-feat-heading-pill">Features</span>
      <h3 class="fnx-feat-heading-title"><?php echo esc_html($headline); ?></h3>
    </div>
  <?php endif; ?>
  <div class="fnx-feat-grid">
    <?php foreach ($cards as $card):
      $icon  = $card['icon'] ?? '⭐';
      $title = $card['title'] ?? '';
      $desc  = $card['desc'] ?? '';
      $tag   = $card['tag'] ?? '';
    ?>
    <div class="fnx-feat-card">
      <div class="fnx-feat-top">
        <div class="fnx-feat-icon" aria-hidden="true"><?php echo esc_html($icon); ?></div>
        <?php if (!empty($tag)): ?>
          <span class="fnx-feat-tag"><?php echo esc_html($tag); ?></span>
        <?php endif; ?>
      </div>
      <?php if (!empty($title)): ?>
        <h4 class="fnx-feat-title"><?php echo esc_html($title); ?></h4>
      <?php endif; ?>
      <?php if (!empty($desc)): ?>
        <p class="fnx-feat-desc"><?php echo esc_html($desc); ?></p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>