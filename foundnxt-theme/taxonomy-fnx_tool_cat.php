<?php
/**
 * Template for a Tool Category landing page: /tools/<category>/
 *
 * Intro text comes from the category Description (Tools Directory → Tool Categories).
 *
 * @package FoundNXT
 */

get_header();

$term      = get_queried_object();
$all       = function_exists('fnx_get_tools_directory') ? fnx_get_tools_directory() : [];
$cat       = ($term && isset($all[$term->slug])) ? $all[$term->slug] : null;
$tools_url  = esc_url(trailingslashit(home_url('/tools')));
$site_url   = esc_url(home_url('/'));
$term_link  = $term ? get_term_link($term) : '';
$term_link  = is_wp_error($term_link) ? '' : $term_link;
$site_name  = get_bloginfo('name');

$total = 0;
$oss   = 0;
if ($cat) {
  foreach ($cat['tools'] as $t) {
    $total++;
    if (strpos(strtolower($t['tag']), 'open source') !== false) {
      $oss++;
    }
  }
}

/* ── JSON-LD SCHEMA ── */
if ($cat) {
  $list_items = [];
  $pos = 1;
  foreach ($cat['tools'] as $t) {
    $list_items[] = [
      '@type'    => 'ListItem',
      'position' => $pos++,
      'item'     => [
        '@type'               => 'SoftwareApplication',
        'name'                => $t['name'],
        'description'         => $t['desc'],
        'url'                 => $t['url'],
        'applicationCategory' => 'BusinessApplication',
      ],
    ];
  }
  $schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
      [
        '@type'       => 'CollectionPage',
        '@id'         => $term_link . '#collection',
        'url'         => $term_link,
        'name'        => $term->name . ': Recommended Tools',
        'description' => wp_strip_all_tags($term->description),
        'inLanguage'  => 'en',
        'publisher'   => ['@type' => 'Organization', 'name' => $site_name, 'url' => $site_url],
      ],
      [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
          ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => $site_url],
          ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tools', 'item' => $tools_url],
          ['@type' => 'ListItem', 'position' => 3, 'name' => $term->name, 'item' => $term_link],
        ],
      ],
      [
        '@type'           => 'ItemList',
        'name'            => $term->name . ' tools',
        'itemListElement' => $list_items,
      ],
    ],
  ];
  if (!function_exists('fnx_has_active_seo_plugin') || !fnx_has_active_seo_plugin()) {
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
  }
}
?>

<div class="fnx-archive-page fnx-tools-directory-page" style="padding: 40px 0 80px;">
  <div class="container">

    <nav class="fnx-tools-crumbs" aria-label="Breadcrumb">
      <a href="<?php echo $site_url; ?>"><?php _e('Home', 'foundnxt'); ?></a> ›
      <a href="<?php echo $tools_url; ?>"><?php _e('Tools', 'foundnxt'); ?></a> ›
      <span><?php echo esc_html($term ? $term->name : ''); ?></span>
    </nav>

    <header class="tools-dir-hero text-center">
      <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo"><?php echo esc_html($cat ? $cat['icon'] : '🛠️'); ?> <?php _e('Recommended Tools', 'foundnxt'); ?></span>
      <h1 class="tools-dir-title"><span class="text-gradient"><?php echo esc_html($term ? $term->name : ''); ?></span>: <?php _e('Recommended Tools', 'foundnxt'); ?></h1>
      <?php if ($cat) : ?>
        <p class="fnx-cat-stats">
          <?php
          printf(
            /* translators: 1: tool count, 2: open-source count */
            esc_html__('%1$d tools · %2$d open source', 'foundnxt'),
            $total,
            $oss
          );
          ?>
        </p>
      <?php endif; ?>
    </header>

    <?php if ($term && trim($term->description) !== '') : ?>
      <div class="fnx-cat-intro">
        <?php echo wp_kses_post(wpautop($term->description)); ?>
      </div>
    <?php endif; ?>

    <div class="tools-directory-body">
      <?php if ($cat) : ?>
        <section class="tools-cat-block" data-category="<?php echo esc_attr($term->slug); ?>">
          <div class="tools-grid-3">
            <?php foreach ($cat['tools'] as $tool) {
              fnx_tools_render_card($tool);
            } ?>
          </div>
        </section>
      <?php else : ?>
        <p class="text-center"><?php _e('No tools in this category yet.', 'foundnxt'); ?></p>
      <?php endif; ?>
    </div>

    <?php
    $others = [];
    foreach ($all as $slug => $data) {
      if ($term && $slug === $term->slug) {
        continue;
      }
      $link = get_term_link($slug, 'fnx_tool_cat');
      if (is_wp_error($link)) {
        continue;
      }
      $others[] = ['label' => $data['label'], 'icon' => $data['icon'], 'url' => $link];
    }
    if ($others) : ?>
      <h2 class="fnx-cat-others-title"><?php _e('Explore more tool categories', 'foundnxt'); ?></h2>
      <div class="fnx-cat-others">
        <a class="tools-filter-pill" href="<?php echo $tools_url; ?>">✨ <?php _e('All Tools', 'foundnxt'); ?></a>
        <?php foreach ($others as $o) : ?>
          <a class="tools-filter-pill" href="<?php echo esc_url($o['url']); ?>"><?php echo esc_html($o['icon']); ?> <?php echo esc_html($o['label']); ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="tools-cta-box">
      <div class="tools-cta-content">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--violet">💡 <?php _e('Custom Tech Stack Advisory', 'foundnxt'); ?></span>
        <h2><?php _e('Need Help Choosing the Right Tool for Your Stage?', 'foundnxt'); ?></h2>
        <p><?php _e('We review your current stack, audit SaaS spend, and run build-vs-buy evaluations tailored to your company stage.', 'foundnxt'); ?></p>
      </div>
      <div class="tools-cta-action">
        <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), home_url('/contact/'))); ?>" class="btn-primary">
          <?php _e('Request Tech Stack Audit', 'foundnxt'); ?> →
        </a>
      </div>
    </div>

  </div>
</div>

<?php get_footer(); ?>
