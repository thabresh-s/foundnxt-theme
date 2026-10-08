<?php
$term  = $attributes['term']         ?? 'Term';
$pos   = $attributes['partOfSpeech'] ?? 'noun';
$def   = $attributes['definition']   ?? 'Add your definition here.';
?>
<div class="fnx-definition">
  <div class="fnx-def-term"><?php echo esc_html($term); ?> <span class="fnx-def-tag"><?php echo esc_html($pos); ?></span></div>
  <p class="fnx-def-text"><?php echo esc_html($def); ?></p>
</div>