<?php
/**
 * Block: Editorial Pull Quote
 * @package FoundNXT
 */
$quote  = $attributes['quote'] ?? '';
$cite   = $attributes['cite'] ?? '';
$role   = $attributes['role'] ?? '';

if (empty(trim($quote))) {
    $quote = __('The companies that win this cycle aren’t building slightly faster workflows — they are re-architecting their entire cost and unit economics around autonomous execution.', 'foundnxt');
}
if (empty(trim($cite))) {
    $cite = __('FoundNXT Research & Venture Intelligence', 'foundnxt');
}
?>
<figure class="fnx-block fnx-pullquote-wrap">
  <blockquote class="fnx-pullquote-card">
    <div class="fnx-pullquote-mark" aria-hidden="true">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
    </div>
    <p class="fnx-pullquote-text"><?php echo esc_html($quote); ?></p>
    <figcaption class="fnx-pullquote-cite">
      <strong class="fnx-pullquote-author"><?php echo esc_html($cite); ?></strong>
      <?php if (!empty($role)): ?>
        <span class="fnx-pullquote-role"><?php echo esc_html($role); ?></span>
      <?php endif; ?>
    </figcaption>
  </blockquote>
</figure>