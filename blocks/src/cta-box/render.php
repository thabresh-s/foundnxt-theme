<?php
/**
 * Block: In-Article Newsletter & Advisory CTA
 * @package FoundNXT
 */
$heading = $attributes['heading'] ?? __('Get Actionable Startup & Valuation Insights', 'foundnxt');
$text    = $attributes['text']    ?? __('Join 10,000+ founders and venture operators receiving our weekly breakdown on unit economics, pitch checklists, and market intelligence.', 'foundnxt');
$btnText = $attributes['btnText'] ?? __('Subscribe Free →', 'foundnxt');
$btnUrl  = $attributes['btnUrl']  ?? '#newsletter-section';
$tag     = $attributes['tag']     ?? __('Executive Intelligence', 'foundnxt');
?>
<section class="fnx-block fnx-cta-box-card">
  <div class="fnx-cta-box-inner">
    <?php if (!empty($tag)): ?>
      <span class="fnx-cta-box-tag"><?php echo esc_html($tag); ?></span>
    <?php endif; ?>
    <h3 class="fnx-cta-box-heading"><?php echo esc_html($heading); ?></h3>
    <p class="fnx-cta-box-text"><?php echo esc_html($text); ?></p>
    <div class="fnx-cta-box-action">
      <a href="<?php echo esc_url($btnUrl); ?>" class="fnx-cta-box-btn">
        <span><?php echo esc_html($btnText); ?></span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
      </a>
    </div>
  </div>
</section>