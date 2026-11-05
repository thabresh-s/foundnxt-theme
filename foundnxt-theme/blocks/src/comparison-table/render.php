<?php
/**
 * Comparison Table Block Render Template
 * @package FoundNXT
 */
$title    = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$columns  = $attributes['columns'] ?? [];
$rows     = $attributes['rows'] ?? [];

if (!is_array($columns)) $columns = [];
if (!is_array($rows)) $rows = [];
?>
<div class="fnx-comparison-wrap">
  <?php if (!empty($title) || !empty($subtitle)): ?>
    <div class="fnx-comparison-header">
      <span class="fnx-comparison-pill">Comparison Matrix</span>
      <?php if (!empty($title)): ?>
        <h3 class="fnx-comparison-title"><?php echo esc_html($title); ?></h3>
      <?php endif; ?>
      <?php if (!empty($subtitle)): ?>
        <p class="fnx-comparison-subtitle"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="fnx-comparison-scroll" tabindex="0" role="region" aria-label="<?php echo esc_attr($title ?: 'Comparison Matrix'); ?>">
    <table class="fnx-comparison-table">
      <thead>
        <tr>
          <th class="fnx-th-feature" scope="col">Capabilities & Features</th>
          <?php foreach ($columns as $col):
            $name      = $col['name'] ?? '';
            $badge     = $col['badge'] ?? '';
            $highlight = !empty($col['highlight']);
          ?>
            <th class="fnx-th-option <?php echo $highlight ? 'fnx-col-highlight' : ''; ?>" scope="col">
              <?php if (!empty($badge)): ?>
                <span class="fnx-option-badge"><?php echo esc_html($badge); ?></span>
              <?php endif; ?>
              <span class="fnx-option-name"><?php echo esc_html($name); ?></span>
            </th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row):
          $feature = $row['feature'] ?? '';
          $values  = $row['values'] ?? [];
          if (!is_array($values)) $values = [];
        ?>
          <tr>
            <th class="fnx-td-feature" scope="row"><?php echo esc_html($feature); ?></th>
            <?php foreach ($columns as $idx => $col):
              $val       = $values[$idx] ?? '';
              $highlight = !empty($col['highlight']);
              $trimmed   = strtolower(trim((string)$val));
            ?>
              <td class="fnx-td-val <?php echo $highlight ? 'fnx-col-highlight' : ''; ?>">
                <?php if ($trimmed === 'yes' || $trimmed === 'true' || $trimmed === 'check' || $trimmed === '✓'): ?>
                  <span class="fnx-matrix-pill fnx-matrix-pill--yes" aria-label="Yes">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Yes</span>
                  </span>
                <?php elseif ($trimmed === 'no' || $trimmed === 'false' || $trimmed === 'cross' || $trimmed === '✕'): ?>
                  <span class="fnx-matrix-pill fnx-matrix-pill--no" aria-label="No">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    <span>No</span>
                  </span>
                <?php else: ?>
                  <span class="fnx-matrix-text"><?php echo esc_html($val); ?></span>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

