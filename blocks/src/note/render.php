<?php
/**
 * Block: Editorial Note / Callout
 * @package FoundNXT
 */
$type    = $attributes['type'] ?? 'info';
$title   = $attributes['title'] ?? '';
$content = $attributes['content'] ?? '';

$config = [
    'info' => [
        'default_title' => __('Note', 'foundnxt'),
        'icon'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
    ],
    'tip' => [
        'default_title' => __('Pro Tip', 'foundnxt'),
        'icon'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path><path d="M9 16a5 5 0 1 1 6 0c0 1.5-.8 2-1.5 2.5V19h-3v-.5c-.7-.5-1.5-1-1.5-2.5z"></path></svg>',
    ],
    'success' => [
        'default_title' => __('Pro Tip', 'foundnxt'),
        'icon'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
    ],
    'warning' => [
        'default_title' => __('Warning', 'foundnxt'),
        'icon'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
    ],
    'danger' => [
        'default_title' => __('Important Alert', 'foundnxt'),
        'icon'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>',
    ],
];

$resolved_type = ($type === 'success') ? 'tip' : $type;
$meta = $config[$type] ?? $config['info'];
$display_title = !empty(trim($title)) ? $title : $meta['default_title'];
?>
<aside class="fnx-block fnx-note-card fnx-note--<?php echo esc_attr($resolved_type); ?>" role="note">
  <div class="fnx-note-header">
    <span class="fnx-note-icon"><?php echo $meta['icon']; ?></span>
    <strong class="fnx-note-title"><?php echo esc_html($display_title); ?></strong>
  </div>
  <div class="fnx-note-content">
    <?php echo wp_kses_post(wpautop($content)); ?>
  </div>
</aside>