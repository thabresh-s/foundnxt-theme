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

<!-- TOP ANNOUNCEMENT BAR -->
<?php if (get_theme_mod('fnx_topbar_enabled', true)): ?>
<div class="fnx-topbar" id="fnx-topbar" role="region" aria-label="<?php esc_attr_e('Latest articles ticker', 'foundnxt'); ?>">
  <div class="topbar-inner">
    <span class="topbar-label">📰 <?php _e("What's New?", 'foundnxt'); ?></span>
    <div class="topbar-ticker" aria-hidden="true">
      <div class="ticker-track" id="ticker-track">
        <?php
        $ticker_posts = get_posts(['posts_per_page' => 8, 'post_status' => 'publish']);
        $ticker_html  = '';
        foreach ($ticker_posts as $p) {
            $ticker_html .= '<a href="' . esc_url(get_permalink($p->ID)) . '" class="ticker-item" tabindex="-1">'
                          . esc_html(get_the_title($p->ID)) . '</a>';
        }
        echo $ticker_html . $ticker_html;
        wp_reset_postdata();
        ?>
      </div>
    </div>
    <button class="topbar-close" id="topbar-close" aria-label="<?php esc_attr_e('Close announcement bar', 'foundnxt'); ?>">✕</button>
  </div>
</div>
<?php endif; ?>

<!-- MAIN HEADER / NAV -->
<header class="fnx-header" id="fnx-header" role="banner">
  <div class="header-inner container">

    <!-- Logo: Single accessible anchor for crawlers/screen readers -->
    <div class="header-logo">
      <?php
      $light_logo_id = get_theme_mod('custom_logo');
      $dark_logo_id  = get_theme_mod('fnx_dark_logo');
      $site_url      = esc_url(home_url('/'));
      $site_name     = esc_attr(get_bloginfo('name'));

      if ($light_logo_id):
        $light_url = wp_get_attachment_image_url($light_logo_id, 'full');
        $dark_url  = $dark_logo_id ? wp_get_attachment_image_url($dark_logo_id, 'full') : $light_url;
      ?>
        <a href="<?php echo $site_url; ?>" class="logo-link logo-light" aria-label="FoundNXT Home">
          <img src="<?php echo esc_url($light_url); ?>" alt="FoundNXT" width="180" height="52" loading="eager">
        </a>
        <a href="<?php echo $site_url; ?>" class="logo-link logo-dark" aria-hidden="true" tabindex="-1">
          <img src="<?php echo esc_url($dark_url); ?>" alt="" width="180" height="52" loading="eager">
        </a>
      <?php else: ?>
        <a href="<?php echo $site_url; ?>" class="logo-text" aria-label="FoundNXT Home">
          FoundNXT
          <span class="logo-sub"><?php bloginfo('description'); ?></span>
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

        echo '<li><a href="' . esc_url(home_url('/services/')) . '">' . __('Services', 'foundnxt') . '</a></li>';
        echo '<li><a href="' . esc_url(home_url('/about/')) . '">' . __('About', 'foundnxt') . '</a></li>';
        echo '<li><a href="' . esc_url(home_url('/contact/')) . '">' . __('Contact', 'foundnxt') . '</a></li>';
        echo '</ul>';
      }
      ?>
    </nav>

    <!-- Header Actions -->
    <div class="header-actions">

      <!-- Search Toggle -->
      <button class="action-btn search-btn" id="search-toggle"
        aria-label="<?php esc_attr_e('Open search', 'foundnxt'); ?>"
        aria-expanded="false" aria-controls="header-search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>

      <!-- Google Translate Toggle -->
      <div class="fnx-translate-wrap" id="fnx-translate-wrap">
        <button class="action-btn translate-btn" id="translate-toggle"
          aria-label="<?php esc_attr_e('Translate this page', 'foundnxt'); ?>"
          aria-expanded="false" aria-controls="fnx-translate-dropdown">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <path d="M5 8l6 6"/>
            <path d="m4 14 6-6 2-3"/>
            <path d="M2 5h12"/>
            <path d="M7 2h1"/>
            <path d="m22 22-5-10-5 10"/>
            <path d="M14 18h6"/>
          </svg>
        </button>
        <div class="fnx-translate-dropdown" id="fnx-translate-dropdown" aria-hidden="true">
          <!-- Language list injected by JS -->
        </div>
      </div>

      <!-- Dark Mode Toggle -->
      <?php if (get_theme_mod('fnx_dark_mode_toggle', true)): ?>
      <button class="action-btn dark-toggle" id="dark-toggle"
        aria-label="<?php esc_attr_e('Toggle dark mode', 'foundnxt'); ?>">
        <span class="icon-light" aria-hidden="true">☀️</span>
        <span class="icon-dark"  aria-hidden="true">🌙</span>
      </button>
      <?php endif; ?>

      <!-- Header Gradient Subscribe Button -->
      <a href="#newsletter-section" class="btn-primary header-subscribe-btn"><?php _e('Subscribe', 'foundnxt'); ?></a>

      <!-- Mobile Menu Toggle -->
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

  <!-- Search Bar -->
  <div class="header-search" id="header-search" aria-hidden="true" role="search">
    <div class="search-inner container">
      <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
        <label for="search-input" class="sr-only"><?php _e('Search articles', 'foundnxt'); ?></label>
        <input type="search" name="s" id="search-input"
          placeholder="<?php esc_attr_e('Search articles, topics…', 'foundnxt'); ?>"
          value="<?php echo esc_attr(get_search_query()); ?>"
          autocomplete="off">
        <button type="submit" class="btn-primary search-submit">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <?php _e('Search', 'foundnxt'); ?>
        </button>
      </form>
      <button class="search-close" id="search-close" aria-label="<?php esc_attr_e('Close search', 'foundnxt'); ?>">✕</button>
    </div>
  </div>
</header>

<!-- Mobile Nav Drawer -->
<div class="fnx-mobile-nav" id="mobile-nav" aria-hidden="true">
  <div class="mobile-nav-inner">
    <div class="mobile-nav-header">
      <?php
      $light_logo_id = get_theme_mod('custom_logo');
      $dark_logo_id  = get_theme_mod('fnx_dark_logo');
      $site_url      = esc_url(home_url('/'));
      $site_name     = esc_attr(get_bloginfo('name'));
      if ($light_logo_id):
        $light_url = wp_get_attachment_image_url($light_logo_id, 'full');
        $dark_url  = $dark_logo_id ? wp_get_attachment_image_url($dark_logo_id, 'full') : $light_url;
      ?>
        <a href="<?php echo $site_url; ?>" aria-label="<?php echo $site_name; ?>">
          <img src="<?php echo esc_url($light_url); ?>" alt="<?php echo $site_name; ?>" class="mobile-logo mobile-logo-light" style="max-height:32px;width:auto;">
          <img src="<?php echo esc_url($dark_url); ?>"  alt="<?php echo $site_name; ?>" class="mobile-logo mobile-logo-dark"  style="max-height:32px;width:auto;">
        </a>
      <?php else: ?>
        <a href="<?php echo $site_url; ?>" class="mobile-logo-text" style="font-weight:700;font-size:1.1rem;color:var(--fnx-ink);text-decoration:none;"><?php bloginfo('name'); ?></a>
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
      <form method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
        <label for="mobile-search" class="sr-only"><?php _e('Search', 'foundnxt'); ?></label>
        <input type="search" name="s" id="mobile-search"
          placeholder="<?php esc_attr_e('Search…', 'foundnxt'); ?>"
          value="<?php echo esc_attr(get_search_query()); ?>">
      </form>
    </div>
  </div>
</div>
<div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

<main id="main-content" class="fnx-main" role="main" tabindex="-1">
