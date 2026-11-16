<?php
/**
 * Template Name: Business & Tech Recommendations
 * Template Post Type: page
 *
 * Dedicated Business Tools & Tech Stack Directory for FoundNXT.
 * Assign this template to your /tools/ page in WordPress.
 *
 * Tools and categories are managed in wp-admin under "Tools Directory"
 * (see inc/tools-directory.php).
 *
 * @package FoundNXT
 */

get_header();

$site_name   = get_bloginfo('name');
$site_url    = esc_url(home_url('/'));
$tools_url   = esc_url(get_permalink());

/* ── TOOL DATA (managed from wp-admin → Tools Directory) ── */
$tool_categories = function_exists('fnx_get_tools_directory') ? fnx_get_tools_directory() : [];

/* ── JSON-LD SCHEMA ── */
$list_items = [];
$position   = 1;
foreach ($tool_categories as $cat_data) {
  foreach ($cat_data['tools'] as $tool) {
    $list_items[] = [
      '@type'    => 'ListItem',
      'position' => $position++,
      'item'     => [
        '@type'               => 'SoftwareApplication',
        'name'                => $tool['name'],
        'description'         => $tool['desc'],
        'url'                 => $tool['url'],
        'applicationCategory' => 'BusinessApplication',
      ],
    ];
  }
}

$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'CollectionPage',
      '@id'         => $tools_url . '#tools',
      'url'         => $tools_url,
      'name'        => 'Business & Tech Recommendations — Curated Software & Open-Source Stack',
      'description' => 'Curated directory of essential business software, open-source tech stacks, and SaaS recommendations engineered to scale modern companies.',
      'inLanguage'  => 'en',
      'isPartOf'    => ['@id' => $site_url . '#website'],
      'publisher'   => [
        '@type' => 'Organization',
        'name'  => $site_name,
        'url'   => $site_url,
      ],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tools', 'item' => $tools_url],
      ],
    ],
  ],
];
if (!empty($list_items)) {
  $schema['@graph'][] = [
    '@type'           => 'ItemList',
    'name'            => 'Recommended business & tech tools',
    'itemListElement' => $list_items,
  ];
}
if (!function_exists('fnx_has_active_seo_plugin') || !fnx_has_active_seo_plugin()) { echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n"; }
?>

<div class="fnx-archive-page fnx-tools-directory-page" style="padding: 40px 0 80px;">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO HEADER -->
    <header class="tools-dir-hero text-center fnx-reveal">
      <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">🛠️ <?php _e('Software & Tech Recommendations', 'foundnxt'); ?></span>
      <h1 class="tools-dir-title">
        <?php _e('Founder Tools &', 'foundnxt'); ?> <span class="text-gradient"><?php _e('Software Recommendations', 'foundnxt'); ?></span>
      </h1>
      <p class="tools-dir-lead">
        <?php _e('Discover essential software, open-source alternatives, and recommended SaaS products curated to help founders and operators scale faster while controlling costs.', 'foundnxt'); ?>
      </p>

      <!-- FILTER TABS (built from Tool Categories) -->
      <div class="tools-filter-nav" id="tools-filter-nav">
        <button class="tools-filter-pill active" data-filter="all">✨ <?php _e('All Categories', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="oss">🔓 <?php _e('Open Source Only', 'foundnxt'); ?></button>
        <?php foreach ($tool_categories as $cat_key => $cat_data): ?>
          <button class="tools-filter-pill" data-filter="<?php echo esc_attr($cat_key); ?>"><?php echo esc_html($cat_data['icon']); ?> <?php echo esc_html($cat_data['label']); ?></button>
        <?php endforeach; ?>
      </div>
    </header>

    <!-- HOW WE CHOOSE + DISCLOSURE -->
    <div class="fnx-tools-intro">
      <h2><?php _e('How we choose these tools', 'foundnxt'); ?></h2>
      <p><?php _e('Each tool here has to pass a few practical tests: it solves a real business problem, it stays affordable as your team grows, and it lets you take your data with you if you ever leave. Where a credible open-source option exists, we list it next to the best-known SaaS product so you can compare cost, control and convenience side by side.', 'foundnxt'); ?></p>
      <p><?php _e('Use the filters to narrow by category, or open a category page for a closer look. Prices, features and licences change often, so check the vendor website before you commit.', 'foundnxt'); ?></p>
      <p class="fnx-tools-disclosure"><?php _e('Disclosure: some links on this page may be affiliate links. If you buy through them, FoundNXT may earn a commission at no extra cost to you.', 'foundnxt'); ?></p>
    </div>

    <!-- CATEGORY MATRIX SECTIONS -->
    <div class="tools-directory-body">
      <?php if (empty($tool_categories)): ?>
        <p class="text-center">
          <?php
          if (current_user_can('edit_posts')) {
            echo wp_kses_post(sprintf(
              /* translators: %s: link to add a tool */
              __('No tools published yet. <a href="%s">Add your first tool</a> in wp-admin.', 'foundnxt'),
              esc_url(admin_url('post-new.php?post_type=fnx_tool'))
            ));
          } else {
            _e('Tool recommendations are coming soon.', 'foundnxt');
          }
          ?>
        </p>
      <?php endif; ?>

      <?php foreach ($tool_categories as $cat_key => $cat_data): ?>
        <section class="tools-cat-block fnx-reveal" data-category="<?php echo esc_attr($cat_key); ?>">
          <div class="cat-block-header">
            <span class="cat-icon"><?php echo esc_html($cat_data['icon']); ?></span>
            <h2 class="cat-title"><?php echo esc_html($cat_data['title']); ?></h2>
            <?php $cat_link = get_term_link($cat_key, 'fnx_tool_cat'); ?>
            <?php if (!is_wp_error($cat_link)): ?>
              <a class="cat-view-all" href="<?php echo esc_url($cat_link); ?>"><?php _e('View all', 'foundnxt'); ?> →</a>
            <?php endif; ?>
          </div>

          <div class="tools-grid-3">
            <?php foreach ($cat_data['tools'] as $tool):
              $is_oss = strpos(strtolower($tool['tag']), 'open source') !== false;
              $card_filter_class = $is_oss ? 'is-oss-card' : 'is-saas-card';
              $link_rel = !empty($tool['sponsored']) ? 'sponsored nofollow noopener noreferrer' : 'noopener noreferrer';
            ?>
              <article class="tool-recommend-card <?php echo esc_attr($card_filter_class); ?>">
                <div class="tool-card-top">
                  <?php if ($tool['tag'] !== ''): ?>
                    <span class="tool-tag-pill <?php echo esc_attr($tool['badge']); ?>">
                      <?php echo esc_html($tool['tag']); ?>
                    </span>
                  <?php endif; ?>
                </div>

                <h3 class="tool-name"><?php echo esc_html($tool['name']); ?></h3>
                <p class="tool-desc"><?php echo esc_html($tool['desc']); ?></p>

                <?php if (!empty($tool['perks'])): ?>
                  <ul class="tool-perks-list">
                    <?php foreach ($tool['perks'] as $perk): ?>
                      <li><span class="perk-check">✓</span> <?php echo esc_html($perk); ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>

                <div class="tool-card-footer">
                  <a href="<?php echo esc_url($tool['url']); ?>" target="_blank" rel="<?php echo esc_attr($link_rel); ?>" class="tool-visit-link">
                    <?php _e('Visit', 'foundnxt'); ?> <?php echo esc_html($tool['website']); ?> ↗
                  </a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endforeach; ?>
    </div>

    <!-- ADVISORY & CUSTOM STACK CTA -->
    <div class="tools-cta-box fnx-reveal">
      <div class="tools-cta-content">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--violet">💡 <?php _e('Custom Tech Stack Advisory', 'foundnxt'); ?></span>
        <h2><?php _e('Need Help Selecting the Right Stack for Your Business?', 'foundnxt'); ?></h2>
        <p><?php _e('Our advisory team performs technical architecture reviews, SaaS cost audits, and custom build-vs-buy evaluations tailored to your company stage.', 'foundnxt'); ?></p>
      </div>
      <div class="tools-cta-action">
        <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), home_url('/contact/'))); ?>" class="btn-primary">
          <?php _e('Request Tech Stack Audit', 'foundnxt'); ?> →
        </a>
      </div>
    </div>

  </div>
</div>

<!-- INLINE INTERACTIVE FILTER SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const filterPills = document.querySelectorAll('#tools-filter-nav .tools-filter-pill');
  const catBlocks   = document.querySelectorAll('.tools-cat-block');

  filterPills.forEach(pill => {
    pill.addEventListener('click', function() {
      filterPills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');

      catBlocks.forEach(block => {
        const catKey = block.getAttribute('data-category');
        const cards  = block.querySelectorAll('.tool-recommend-card');

        if (filter === 'all') {
          block.style.display = 'block';
          cards.forEach(card => card.style.display = 'flex');
        } else if (filter === 'oss') {
          let visibleCount = 0;
          cards.forEach(card => {
            if (card.classList.contains('is-oss-card')) {
              card.style.display = 'flex';
              visibleCount++;
            } else {
              card.style.display = 'none';
            }
          });
          block.style.display = visibleCount > 0 ? 'block' : 'none';
        } else {
          if (catKey === filter) {
            block.style.display = 'block';
            cards.forEach(card => card.style.display = 'flex');
          } else {
            block.style.display = 'none';
          }
        }
      });
    });
  });
});
</script>

<?php get_footer(); ?>
