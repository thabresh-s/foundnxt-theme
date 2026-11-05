<?php
/**
 * Block: FAQ Accordion with Schema.org FAQPage Microdata
 * @package FoundNXT
 */
$heading = $attributes['heading'] ?? __('Frequently Asked Questions', 'foundnxt');
$items   = $attributes['items']   ?? [];

if (empty($items)) {
    $items = [
        [
            'q' => 'What is the standard pre-money valuation benchmark for early-stage B2B startups?',
            'a' => 'Valuations typically range between ₹15Cr to ₹40Cr depending on annual recurring revenue (ARR), founder pedigree, pilot contracts, and gross margin profile.',
        ],
        [
            'q' => 'How can founders prepare for due diligence before term sheet issuance?',
            'a' => 'Ensure your data room includes clean cap tables, verified incorporation documents, audited P&L statements, IP assignment agreements, and customer cohort retention data.',
        ],
    ];
}
?>
<section class="fnx-block fnx-faq-block" itemscope itemtype="https://schema.org/FAQPage">
  <?php if (!empty($heading)): ?>
    <h3 class="fnx-faq-heading"><?php echo esc_html($heading); ?></h3>
  <?php endif; ?>
  <div class="fnx-faq-accordion-list">
    <?php foreach ($items as $idx => $item): 
      $q = $item['q'] ?? '';
      $a = $item['a'] ?? '';
      if (!$q) continue;
    ?>
    <details class="fnx-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"<?php echo $idx === 0 ? ' open' : ''; ?>>
      <summary class="fnx-faq-summary">
        <span class="fnx-faq-question-text" itemprop="name"><?php echo esc_html($q); ?></span>
        <span class="fnx-faq-chevron" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </span>
      </summary>
      <div class="fnx-faq-body" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div class="fnx-faq-answer" itemprop="text">
          <?php echo wp_kses_post(wpautop($a)); ?>
        </div>
      </div>
    </details>
    <?php endforeach; ?>
  </div>
</section>