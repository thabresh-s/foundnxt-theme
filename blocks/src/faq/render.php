<?php
$heading = $attributes['heading'] ?? 'Frequently Asked Questions';
$items   = $attributes['items']   ?? [];
?>
<div class="fnx-faq" itemscope itemtype="https://schema.org/FAQPage">
  <h3><?php echo esc_html($heading); ?></h3>
  <?php foreach($items as $item): ?>
  <div class="fnx-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <h4 class="fnx-faq-question" itemprop="name"><?php echo esc_html($item['q'] ?? ''); ?></h4>
    <div itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
      <p class="fnx-faq-answer" itemprop="text"><?php echo esc_html($item['a'] ?? ''); ?></p>
    </div>
  </div>
  <?php endforeach; ?>
</div>