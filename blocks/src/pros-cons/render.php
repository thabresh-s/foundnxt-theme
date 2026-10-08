<?php
$pros = $attributes['prosItems'] ?? ['First advantage','Second benefit','Third point'];
$cons = $attributes['consItems'] ?? ['First drawback','Second limitation','Third challenge'];
?>
<div class="fnx-pros-cons">
  <div class="fnx-pc-grid">
    <div class="fnx-pros">
      <h4 class="fnx-pros-heading">👍 Pros</h4>
      <ul><?php foreach($pros as $p): ?><li><?php echo esc_html($p); ?></li><?php endforeach; ?></ul>
    </div>
    <div class="fnx-cons">
      <h4 class="fnx-cons-heading">👎 Cons</h4>
      <ul><?php foreach($cons as $c): ?><li><?php echo esc_html($c); ?></li><?php endforeach; ?></ul>
    </div>
  </div>
</div>