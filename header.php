<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
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
        // Duplicate for seamless CSS loop
        echo $ticker_html . $ticker_html;
        wp_reset_postdata();
        ?>
      </div>
    </div>
    <button class="topbar-close" aria-label="<?php esc_attr_e('Close announcement bar', 'foundnxt'); ?>">✕</button>
  </div>
</div>
<?php endif; ?>

<!-- MAIN HEADER / NAV -->
<header class="fnx-header" id="fnx-header" role="banner">
  <div class="header-inner container">

    <!-- Logo: FIX — separate img tags for light/dark, no duplicate logo output -->
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
        <a href="<?php echo $site_url; ?>" class="logo-link logo-light" aria-label="<?php echo $site_name; ?> – <?php _e('Home', 'foundnxt'); ?>">
          <img src="<?php echo esc_url($light_url); ?>" alt="<?php echo $site_name; ?>">
        </a>
        <a href="<?php echo $site_url; ?>" class="logo-link logo-dark" aria-label="<?php echo $site_name; ?> – <?php _e('Home', 'foundnxt'); ?>">
          <img src="<?php echo esc_url($dark_url); ?>" alt="<?php echo $site_name; ?>">
        </a>
      <?php else: ?>
        <a href="<?php echo $site_url; ?>" class="logo-text">
          <?php bloginfo('name'); ?>
          <span class="logo-sub"><?php bloginfo('description'); ?></span>
        </a>
      <?php endif; ?>
    </div>

    <!-- Primary Nav -->
    <nav class="header-nav" id="primary-nav" role="navigation" aria-label="<?php esc_attr_e('Primary', 'foundnxt'); ?>">
      <?php wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav-menu',
        'fallback_cb'    => false,
        'walker'         => new FNX_Nav_Walker(),
      ]); ?>
    </nav>

    <!-- Header Actions -->
    <div class="header-actions">

      <!-- Search Toggle — FIX: SVG icon instead of emoji for crisp rendering -->
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
