<?php
$heading = $attributes['heading'] ?? 'Want more insights like this?';
$text    = $attributes['text']    ?? 'Get practical articles on startups, funding, and valuation — straight to your inbox.';
$btnText = $attributes['btnText'] ?? 'Subscribe Free →';
$btnUrl  = $attributes['btnUrl']  ?? '/contact/';
?>
<div class="fnx-inline-cta">
  <h4><?php echo esc_html($heading); ?></h4>
  <p><?php echo esc_html($text); ?></p>
  <a href="<?php echo esc_url($btnUrl); ?>" class="fnx-cta-btn"><?php echo esc_html($btnText); ?></a>
</div>