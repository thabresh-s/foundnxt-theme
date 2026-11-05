<?php
$name     = $attributes['name']     ?? 'FoundNXT Editorial Team';
$role     = $attributes['role']     ?? 'Author, FoundNXT';
$bio      = $attributes['bio']      ?? '';
$initials = $attributes['initials'] ?? 'TS';
?>
<div class="fnx-author-bio">
  <div class="fnx-author-avatar"><?php echo esc_html($initials); ?></div>
  <div class="fnx-author-info">
    <div class="fnx-author-name"><?php echo esc_html($name); ?></div>
    <div class="fnx-author-role"><?php echo esc_html($role); ?></div>
    <?php if($bio): ?><p class="fnx-author-desc"><?php echo esc_html($bio); ?></p><?php endif; ?>
  </div>
</div>