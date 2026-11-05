<?php
/**
 * Block: Key Takeaway
 * @package FoundNXT
 */
$content = $attributes['content'] ?? '';
$title   = $attributes['title'] ?? __('Key Takeaway', 'foundnxt');
if (empty(trim($content))) {
    $content = __('Summarise the core insight or actionable takeaway from this section.', 'foundnxt');
}
?>
<aside class="fnx-block fnx-takeaway-card" aria-label="<?php echo esc_attr($title); ?>">
  <div class="fnx-takeaway-header">
    <span class="fnx-takeaway-badge">
      <svg class="fnx-takeaway-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path><path d="M9 16a5 5 0 1 1 6 0c0 1.5-.8 2-1.5 2.5V19h-3v-.5c-.7-.5-1.5-1-1.5-2.5z"></path></svg>
      <?php echo esc_html($title); ?>
    </span>
  </div>
  <div class="fnx-takeaway-body">
    <?php echo wp_kses_post(wpautop($content)); ?>
  </div>
</aside>