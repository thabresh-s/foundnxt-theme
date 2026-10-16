<?php
$label = $attributes['label'] ?? 'Related Guides & Deep Dives';
$rawLinks = $attributes['links'] ?? [];

$links = [];
if (is_array($rawLinks)) {
    foreach ($rawLinks as $item) {
        if (is_array($item)) {
            $links[] = [
                'text' => trim($item['text'] ?? ''),
                'url'  => trim($item['url'] ?? '#'),
            ];
        } elseif (is_string($item)) {
            $parts = explode('|', $item, 2);
            $links[] = [
                'text' => trim($parts[0]),
                'url'  => isset($parts[1]) ? trim($parts[1]) : '#',
            ];
        }
    }
} elseif (is_string($rawLinks)) {
    $lines = preg_split('/\r\n|\r|\n/', $rawLinks);
    foreach ($lines as $line) {
        $line = trim($line, " \t\n\r\0\x0B-*•");
        if ($line === '') continue;
        $parts = explode('|', $line, 2);
        $links[] = [
            'text' => trim($parts[0]),
            'url'  => isset($parts[1]) ? trim($parts[1]) : '#',
        ];
    }
}
?>
<div class="fnx-related-inline">
  <div class="fnx-related-header">
    <div class="fnx-related-badge">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
      <span><?php echo esc_html($label); ?></span>
    </div>
  </div>
  <ul class="fnx-related-list">
    <?php foreach ($links as $link): 
      if (empty($link['text'])) continue;
    ?>
    <li>
      <a href="<?php echo esc_url($link['url']); ?>" class="fnx-related-link">
        <span class="fnx-related-title"><?php echo esc_html($link['text']); ?></span>
        <svg class="fnx-related-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</div>