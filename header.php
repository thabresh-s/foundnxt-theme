<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if (!has_action('wp_head', 'rel_canonical')): ?>
<link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
<?php endif; ?>
<meta name="description" content="<?php echo esc_attr(get_theme_mod('fnx_seo_description', __('Actionable playbooks, tech insights, and scaling strategies for startup founders. Learn how to build faster, secure funding, and navigate your founder journey.', 'foundnxt'))); ?>">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>

<!-- Schema.org JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "<?php echo esc_url(home_url('/#organization')); ?>",
      "name": "FoundNXT",
      "url": "<?php echo esc_url(home_url('/')); ?>",
      "logo": "<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo.svg'); ?>",
      "sameAs": [
        "https://twitter.com/foundnxt",
        "https://linkedin.com/company/foundnxt"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "<?php echo esc_url(home_url('/#website')); ?>",
      "url": "<?php echo esc_url(home_url('/')); ?>",
      "name": "FoundNXT",
      "description": "Where founders find what's next.",
      "publisher": { "@id": "<?php echo esc_url(home_url('/#organization')); ?>" },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo esc_url(home_url('/?s={search_term_string}')); ?>",
        "query-input": "required name=search_term_string"
      }
    }
    <?php if (is_single()): global $post; ?>
    ,{
      "@type": "Article",
      "@id": "<?php echo esc_url(get_permalink()); ?>#article",
      "isPartOf": { "@id": "<?php echo esc_url(home_url('/#website')); ?>" },
      "headline": "<?php echo esc_js(get_the_title()); ?>",
      "datePublished": "<?php echo get_the_date('c'); ?>",
      "dateModified": "<?php echo get_the_modified_date('c'); ?>",
      "mainEntityOfPage": "<?php echo esc_url(get_permalink()); ?>",
      "author": {
        "@type": "Person",
        "name": "<?php echo esc_js(get_the_author()); ?>"
      },
      "publisher": { "@id": "<?php echo esc_url(home_url('/#organization')); ?>" }
    }
    <?php endif; ?>
  ]
}
</script>

<!-- Prevent dark-mode flash: apply saved theme before first paint -->
<script>
(function(){
  var s = localStorage.getItem('fnx_theme');
  if (!s) { s = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'; }
  document.documentElement.setAttribute('data-theme', s);
  document.documentElement.classList.add(s === 'dark' ? 'dark-mode' : 'light-mode');
})();
</script>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php _e('Skip to content', 'foundnxt'); ?></a>

<!-- ── TOP "WHAT'S NEW?" BANNER (Slim, Dismissible, Height Reserved) ── -->
<aside class="fnx-top-banner" id="fnx-top-banner" aria-label="<?php esc_attr_e('Announcement', 'foundnxt'); ?>">
  <div class="top-banner-inner container">
    <div class="top-banner-content">
      <span class="top-banner-badge">✨ <?php _e("What's New", 'foundnxt'); ?></span>
      <span class="top-banner-text">
        <?php _e('The 2026 Founder AI Valuation & Scaling Framework is live.', 'foundnxt'); ?>
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="top-banner-link"><?php _e('Explore Articles', 'foundnxt'); ?> →</a>
      </span>
    </div>
    <button type="button" class="top-banner-close" id="top-banner-close" aria-label="<?php esc_attr_e('Dismiss announcement', 'foundnxt'); ?>">&times;</button>
  </div>
</aside>

<!-- MAIN HEADER / NAV -->
<header class="fnx-header" id="fnx-header" role="banner">
  <div class="header-inner container">

    <!-- Logo: Single accessible brand element (no duplicate image downloads) -->
    <div class="header-logo">
      <?php
      $custom_logo_id = get_theme_mod('custom_logo');
      $site_url       = esc_url(home_url('/'));
      $site_name      = esc_attr(get_bloginfo('name'));

      if ($custom_logo_id):
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
      ?>
        <a href="<?php echo $site_url; ?>" class="brand-logo-wrap" aria-label="<?php echo $site_name; ?> Home">
          <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo $site_name; ?>" width="160" height="40" loading="eager" fetchpriority="high">
        </a>
      <?php else: ?>
        <a href="<?php echo $site_url; ?>" class="brand-logo-wrap" aria-label="<?php echo $site_name; ?> Home">
          <span class="brand-logo-icon" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          </span>
          <span class="brand-logo-text">
            <span class="brand-logo-main">Found<span class="brand-logo-nxt">NXT</span></span>
            <span class="brand-logo-sub"><?php _e('Scale in AI Era', 'foundnxt'); ?></span>
          </span>
        </a>
      <?php endif; ?>
    </div>

    <!-- Primary Nav -->
    <nav class="header-nav" id="primary-nav" role="navigation" aria-label="<?php esc_attr_e('Primary', 'foundnxt'); ?>">
      <?php
      if (has_nav_menu('primary')) {
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'nav-menu',
          'fallback_cb'    => false,
          'walker'         => new FNX_Nav_Walker(),
        ]);
      } else {
        echo '<ul class="nav-menu">';
        echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'foundnxt') . '</a></li>';
        echo '<li><a href="' . esc_url(home_url('/articles/')) . '">' . __('Articles', 'foundnxt') . '</a></li>';
        
        // Categories Dropdown
        echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/articles/')) . '">' . __('Categories', 'foundnxt') . ' <span class="dropdown-arrow">▾</span></a>';
        echo '<ul class="sub-menu">';
        $hdr_cats = get_categories(['orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false, 'exclude' => [1]]);
        foreach ($hdr_cats as $cat) {
          $c_color = fnx_get_category_color($cat->slug);
          echo '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '"><span class="cat-menu-dot" style="background:' . esc_attr($c_color) . '"></span>' . esc_html($cat->name) . '</a></li>';
        }
        echo '</ul></li>';

        echo '<li><a href="' . esc_url(home_url('/tools/')) . '">' . __('Tools', 'foundnxt') . '</a></li>';
        echo '<li><a href="' . esc_url(home_url('/services/')) . '">' . __('Services', 'foundnxt') . '</a></li>';
        echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . __('About', 'foundnxt') . '</a></li>';
        echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . __('Contact', 'foundnxt') . '</a></li>';
        echo '</ul>';
      }
      ?>
    </nav>

    <!-- Header Actions -->
    <div class="header-actions">

      <!-- Single Search Trigger (Opens Overlay with Focus Trap) -->
      <button class="action-btn search-btn" id="search-toggle"
        aria-label="<?php esc_attr_e('Open search', 'foundnxt'); ?>"
        aria-expanded="false" aria-controls="fnx-search-modal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>

      <!-- Dark Mode Toggle -->
      <?php if (get_theme_mod('fnx_dark_mode_toggle', true)): ?>
      <button class="action-btn dark-toggle" id="dark-toggle"
        aria-label="<?php esc_attr_e('Toggle dark mode', 'foundnxt'); ?>">
        <span class="icon-light" aria-hidden="true">☀️</span>
        <span class="icon-dark"  aria-hidden="true">🌙</span>
      </button>
      <?php endif; ?>

      <!-- Header Modern Action CTA -->
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary header-cta-btn"><?php _e('Get in Touch', 'foundnxt'); ?> →</a>

      <!-- Mobile Menu Toggle (min 44x44px) -->
      <button class="action-btn mobile-toggle" id="mobile-toggle"
        aria-label="<?php esc_attr_e('Open menu', 'foundnxt'); ?>"
        aria-expanded="false" aria-controls="mobile-nav">
        <span class="burger-wrap" aria-hidden="true">
          <span class="burger"></span>
          <span class="burger"></span>
          <span class="burger"></span>
        </span>
      </button>

    </div>
  </div>

  <!-- Category Chip Bar (Startups, Tech, Scaling, Careers, AI, Markets, News) -->
  <nav class="fnx-category-chip-bar" aria-label="<?php esc_attr_e('Topic categories', 'foundnxt'); ?>">
    <div class="category-chip-scroll container">
      <a href="<?php echo esc_url(home_url('/category/startups-funding/')); ?>" class="cat-chip cat-chip--startups"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('Startups', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/technology-ai/')); ?>" class="cat-chip cat-chip--tech"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('Tech', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/business-strategy/')); ?>" class="cat-chip cat-chip--scaling"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('Scaling', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/technology-ai/')); ?>" class="cat-chip cat-chip--ai"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('AI', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/markets-economy/')); ?>" class="cat-chip cat-chip--markets"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('Markets', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/valuation-finance/')); ?>" class="cat-chip cat-chip--valuation"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('Valuation', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/news-insights/')); ?>" class="cat-chip cat-chip--news"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('News', 'foundnxt'); ?></a>
      <a href="<?php echo esc_url(home_url('/category/marketing-growth/')); ?>" class="cat-chip cat-chip--careers"><span class="cat-chip-dot" aria-hidden="true"></span><?php _e('Careers & Growth', 'foundnxt'); ?></a>
    </div>
  </nav>
</header>

<!-- Accessible Search Overlay with Focus Trap & Esc to Close (Single Search Element) -->
<div class="fnx-search-modal" id="fnx-search-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Search', 'foundnxt'); ?>" aria-hidden="true">
  <div class="search-modal-card">
    <div class="search-modal-header">
      <span class="search-modal-title"><?php _e('Search FoundNXT', 'foundnxt'); ?></span>
      <button type="button" class="search-modal-close" id="search-modal-close" aria-label="<?php esc_attr_e('Close search', 'foundnxt'); ?>">&times;</button>
    </div>
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-modal-form" role="search">
      <label for="search-modal-input" class="sr-only"><?php _e('Search articles, playbooks, frameworks', 'foundnxt'); ?></label>
      <input type="search" name="s" id="search-modal-input" class="search-modal-input" placeholder="<?php esc_attr_e('Search articles, playbooks, tools, topics…', 'foundnxt'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" autocomplete="off" required>
      <button type="submit" class="search-modal-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <span><?php _e('Search', 'foundnxt'); ?></span>
      </button>
    </form>
  </div>
</div>

<!-- Mobile Nav Drawer -->
<div class="fnx-mobile-nav" id="mobile-nav" aria-hidden="true">
  <div class="mobile-nav-inner">
    <div class="mobile-nav-header">
      <?php if ($custom_logo_id): ?>
        <a href="<?php echo $site_url; ?>" aria-label="<?php echo $site_name; ?>">
          <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo $site_name; ?>" class="mobile-logo" style="max-height:32px;width:auto;">
        </a>
      <?php else: ?>
        <a href="<?php echo $site_url; ?>" class="brand-logo-wrap" aria-label="<?php echo $site_name; ?>">
          <span class="brand-logo-main">Found<span class="brand-logo-nxt">NXT</span></span>
        </a>
      <?php endif; ?>
      <button class="mobile-close" id="mobile-close" aria-label="<?php esc_attr_e('Close menu', 'foundnxt'); ?>">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <?php wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'mobile-menu',
      'fallback_cb'    => false,
    ]); ?>
    <div class="mobile-nav-footer">
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary" style="width:100%;min-height:44px;display:flex;align-items:center;justify-content:center;"><?php _e('Get in Touch', 'foundnxt'); ?> →</a>
    </div>
  </div>
</div>
<div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

<main id="main-content" class="fnx-main" role="main" tabindex="-1">
