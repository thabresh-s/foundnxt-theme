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

$total_tools_count = 0;
$oss_tools_count   = 0;
$list_items        = [];
$position          = 1;

foreach ($tool_categories as $cat_data) {
  foreach ($cat_data['tools'] as $tool) {
    $total_tools_count++;
    if (strpos(strtolower($tool['tag']), 'open source') !== false) {
      $oss_tools_count++;
    }
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

/* ── JSON-LD SCHEMA ── */
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
if (!function_exists('fnx_has_active_seo_plugin') || !fnx_has_active_seo_plugin()) {
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
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
    </header>

    <!-- 2-COLUMN DIRECTORY LAYOUT: VERTICAL BAR + CONTENT -->
    <div class="tools-layout-wrapper">

      <!-- VERTICAL CATEGORY BAR (SIDEBAR) -->
      <aside class="tools-sidebar-col" aria-label="<?php esc_attr_e('Tool Categories', 'foundnxt'); ?>">
        <div class="tools-vertical-bar">
          
          <div class="tools-vbar-header">
            <span class="tools-vbar-title"><?php _e('Categories', 'foundnxt'); ?></span>
            <span class="tools-vbar-count"><?php echo esc_html($total_tools_count); ?> <?php _e('Tools', 'foundnxt'); ?></span>
          </div>

          <!-- Quick Search in Vertical Bar -->
          <div class="tools-vbar-search">
            <svg class="vbar-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="tools-search-input" placeholder="<?php esc_attr_e('Search tools...', 'foundnxt'); ?>" aria-label="<?php esc_attr_e('Search tools directory', 'foundnxt'); ?>" autocomplete="off">
            <button type="button" id="tools-search-clear" class="vbar-search-clear" aria-label="<?php esc_attr_e('Clear search', 'foundnxt'); ?>">&times;</button>
          </div>

          <!-- Vertical Category Navigation -->
          <nav class="tools-vbar-nav" id="tools-vbar-nav" role="tablist" aria-label="<?php esc_attr_e('Filter by category', 'foundnxt'); ?>">
            <button class="vbar-item active" data-filter="all" role="tab" aria-selected="true">
              <span class="vbar-icon">✨</span>
              <span class="vbar-text"><?php _e('All Categories', 'foundnxt'); ?></span>
              <span class="vbar-badge"><?php echo esc_html($total_tools_count); ?></span>
            </button>
            <button class="vbar-item" data-filter="oss" role="tab" aria-selected="false">
              <span class="vbar-icon">🔓</span>
              <span class="vbar-text"><?php _e('Open Source Only', 'foundnxt'); ?></span>
              <span class="vbar-badge"><?php echo esc_html($oss_tools_count); ?></span>
            </button>

            <div class="vbar-divider" role="separator"></div>
            <div class="vbar-section-label"><?php _e('By Domain', 'foundnxt'); ?></div>

            <?php foreach ($tool_categories as $cat_key => $cat_data): ?>
              <button class="vbar-item" data-filter="<?php echo esc_attr($cat_key); ?>" data-target="#cat-<?php echo esc_attr($cat_key); ?>" role="tab" aria-selected="false">
                <span class="vbar-icon"><?php echo esc_html($cat_data['icon']); ?></span>
                <span class="vbar-text"><?php echo esc_html($cat_data['label']); ?></span>
                <span class="vbar-badge"><?php echo count($cat_data['tools']); ?></span>
              </button>
            <?php endforeach; ?>
          </nav>

          <!-- Advisory Card -->
          <div class="vbar-advisory-card">
            <div class="vbar-adv-icon">💡</div>
            <div class="vbar-adv-title"><?php _e('Custom Tech Stack Audit', 'foundnxt'); ?></div>
            <p class="vbar-adv-desc"><?php _e('Architecture reviews & cost audits tailored to your startup stage.', 'foundnxt'); ?></p>
            <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), home_url('/contact/'))); ?>" class="vbar-adv-link">
              <?php _e('Request Audit', 'foundnxt'); ?> →
            </a>
          </div>

        </div>
      </aside>

      <!-- MAIN CONTENT: CATEGORY SECTIONS & CARDS -->
      <main class="tools-main-content" id="tools-main-content">
        
        <!-- Empty Search State -->
        <div class="tools-search-empty" id="tools-search-empty">
          <div class="tools-search-empty-icon">🔍</div>
          <h3><?php _e('No matching tools found', 'foundnxt'); ?></h3>
          <p><?php _e('Try searching for different keywords, features, or clear the search filter.', 'foundnxt'); ?></p>
          <button type="button" class="btn-reset-search" id="btn-reset-search"><?php _e('Clear Search', 'foundnxt'); ?></button>
        </div>

        <?php if (empty($tool_categories)): ?>
          <p class="text-center" style="padding: 60px 20px;">
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
          <section class="tools-cat-block fnx-reveal" id="cat-<?php echo esc_attr($cat_key); ?>" data-category="<?php echo esc_attr($cat_key); ?>">
            <div class="cat-block-header">
              <div class="cat-block-title-wrap">
                <span class="cat-icon"><?php echo esc_html($cat_data['icon']); ?></span>
                <h2 class="cat-title"><?php echo esc_html($cat_data['title']); ?></h2>
                <span class="cat-count-pill"><?php echo count($cat_data['tools']); ?></span>
              </div>
              <?php $cat_link = get_term_link($cat_key, 'fnx_tool_cat'); ?>
              <?php if (!is_wp_error($cat_link)): ?>
                <a class="cat-view-all" href="<?php echo esc_url($cat_link); ?>"><?php _e('View category page', 'foundnxt'); ?> →</a>
              <?php endif; ?>
            </div>

            <div class="tools-grid-cards">
              <?php foreach ($cat_data['tools'] as $tool):
                $is_oss = strpos(strtolower($tool['tag']), 'open source') !== false;
                $card_filter_class = $is_oss ? 'is-oss-card' : 'is-saas-card';
                $link_rel = !empty($tool['sponsored']) ? 'sponsored nofollow noopener noreferrer' : 'noopener noreferrer';
              ?>
                <article class="tool-recommend-card <?php echo esc_attr($card_filter_class); ?>"
                  data-name="<?php echo esc_attr(strtolower($tool['name'])); ?>"
                  data-desc="<?php echo esc_attr(strtolower($tool['desc'])); ?>"
                  data-tag="<?php echo esc_attr(strtolower($tool['tag'])); ?>"
                  data-perks="<?php echo esc_attr(strtolower(implode(' ', $tool['perks'] ?? []))); ?>">
                  
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

        <!-- Subtle Affiliate Disclosure (Compliant, neat, non-intrusive) -->
        <p class="fnx-tools-disclosure-subtle">
          <?php _e('Disclosure: Some tools featured above may contain affiliate referral links. FoundNXT may earn a commission at no additional cost to you.', 'foundnxt'); ?>
        </p>

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

      </main>

    </div><!-- /.tools-layout-wrapper -->

  </div><!-- /.container -->
</div><!-- /.fnx-tools-directory-page -->

<!-- INTERACTIVE VERTICAL CATEGORY BAR SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const vbarItems   = document.querySelectorAll('.tools-vbar-nav .vbar-item');
  const catBlocks   = document.querySelectorAll('.tools-cat-block');
  const searchInput = document.getElementById('tools-search-input');
  const searchClear = document.getElementById('tools-search-clear');
  const emptyState  = document.getElementById('tools-search-empty');
  const resetBtn    = document.getElementById('btn-reset-search');

  let currentFilter = 'all';

  function applyFilters() {
    const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
    if (searchClear) {
      searchClear.style.display = query.length > 0 ? 'block' : 'none';
    }

    let totalVisible = 0;

    catBlocks.forEach(function(block) {
      const catKey   = block.getAttribute('data-category');
      const catCards = block.querySelectorAll('.tool-recommend-card');
      let visibleInCat = 0;

      // Category check
      const catMatches = (currentFilter === 'all' || currentFilter === 'oss' || currentFilter === catKey);

      if (!catMatches) {
        block.style.display = 'none';
        return;
      }

      catCards.forEach(function(card) {
        const isOss = card.classList.contains('is-oss-card');
        const ossMatches = (currentFilter !== 'oss') || isOss;

        let searchMatches = true;
        if (query.length > 0) {
          const name  = card.getAttribute('data-name') || '';
          const desc  = card.getAttribute('data-desc') || '';
          const tag   = card.getAttribute('data-tag') || '';
          const perks = card.getAttribute('data-perks') || '';
          searchMatches = (name.indexOf(query) !== -1 || desc.indexOf(query) !== -1 || tag.indexOf(query) !== -1 || perks.indexOf(query) !== -1);
        }

        if (ossMatches && searchMatches) {
          card.style.display = 'flex';
          visibleInCat++;
          totalVisible++;
        } else {
          card.style.display = 'none';
        }
      });

      block.style.display = visibleInCat > 0 ? 'block' : 'none';
    });

    if (emptyState) {
      emptyState.style.display = totalVisible === 0 ? 'block' : 'none';
    }
  }

  // Handle category / filter clicks
  vbarItems.forEach(function(item) {
    item.addEventListener('click', function(e) {
      const filter = this.getAttribute('data-filter');
      const targetId = this.getAttribute('data-target');

      vbarItems.forEach(function(i) {
        i.classList.remove('active');
        i.setAttribute('aria-selected', 'false');
      });
      this.classList.add('active');
      this.setAttribute('aria-selected', 'true');

      currentFilter = filter;
      applyFilters();

      // If clicking a specific category in 'all' mode or directly, smoothly scroll to it
      if (targetId && filter !== 'all' && filter !== 'oss') {
        const targetEl = document.querySelector(targetId);
        if (targetEl) {
          targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      } else if (filter === 'all') {
        const mainContent = document.getElementById('tools-main-content');
        if (mainContent && window.scrollY > mainContent.offsetTop) {
          mainContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  });

  // Search input handler
  if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
  }

  if (searchClear) {
    searchClear.addEventListener('click', function() {
      searchInput.value = '';
      applyFilters();
      searchInput.focus();
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      if (searchInput) searchInput.value = '';
      currentFilter = 'all';
      vbarItems.forEach(function(i) {
        const isAll = i.getAttribute('data-filter') === 'all';
        i.classList.toggle('active', isAll);
        i.setAttribute('aria-selected', isAll ? 'true' : 'false');
      });
      applyFilters();
    });
  }

  // Scrollspy: when browsing in 'all' mode, highlight active category in vertical bar
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(function(entries) {
      if (currentFilter !== 'all' || (searchInput && searchInput.value.trim().length > 0)) {
        return;
      }
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const catKey = entry.target.getAttribute('data-category');
          vbarItems.forEach(function(item) {
            const isMatch = item.getAttribute('data-filter') === catKey;
            if (isMatch) {
              vbarItems.forEach(function(i) { i.classList.remove('active'); });
              item.classList.add('active');
            }
          });
        }
      });
    }, {
      rootMargin: '-100px 0px -70% 0px',
      threshold: 0
    });

    catBlocks.forEach(function(block) {
      observer.observe(block);
    });
  }
});
</script>

<?php get_footer(); ?>
