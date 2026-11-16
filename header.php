<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="google-adsense-account" content="<?php echo esc_attr(get_theme_mod('fnx_ads_publisher_id', 'ca-pub-9252123074089867') ?: 'ca-pub-9252123074089867'); ?>">
<script>
  (function() {
    try {
      var saved = localStorage.getItem('fnx_theme');
      var isDark = saved ? (saved === 'dark') : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
      var root = document.documentElement;
      var theme = isDark ? 'dark' : 'light';
      root.setAttribute('data-theme', theme);
      root.classList.toggle('dark-mode', isDark);
      root.classList.toggle('light-mode', !isDark);
      root.style.colorScheme = theme;
    } catch(e) {}
  })();
</script>
<link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr(get_bloginfo('name')); ?> RSS Feed" href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>">

<?php wp_head(); ?>

<?php if (function_exists('fnx_has_active_seo_plugin') && !fnx_has_active_seo_plugin()): ?>
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
        "https://x.com/foundnxt",
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
      "description": "<?php echo esc_js(function_exists('fnx_get_seo_description') ? fnx_get_seo_description() : get_the_excerpt()); ?>",
      "datePublished": "<?php echo get_the_date('c'); ?>",
      "dateModified": "<?php echo get_the_modified_date('c'); ?>",
      "mainEntityOfPage": "<?php echo esc_url(get_permalink()); ?>",
      "author": {
        "@type": "Person",
        "name": "<?php echo esc_js(get_the_author()); ?>"
      },
      "publisher": { "@id": "<?php echo esc_url(home_url('/#organization')); ?>" }
      <?php if (has_post_thumbnail()): ?>
      ,"image": "<?php echo esc_url(get_the_post_thumbnail_url(null, 'fnx-hero')); ?>"
      <?php endif; ?>
    }
    <?php endif; ?>
  ]
}
</script>
<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php _e('Skip to content', 'foundnxt'); ?></a>

<!-- ── TOP ANNOUNCEMENT BANNER (Centered Text, Pinned Close Button, Cookie Dismissal) ── -->
<?php
$announcement_enabled   = (bool) get_theme_mod('fnx_announcement_enabled', true);
$announcement_dismissed = isset($_COOKIE['fnx_announcement_dismissed']) && $_COOKIE['fnx_announcement_dismissed'] === '1';
if ($announcement_enabled && !$announcement_dismissed):
  $banner_badge     = get_theme_mod('fnx_announcement_badge', "✨ " . __("What's New", 'foundnxt'));
  $banner_text      = get_theme_mod('fnx_announcement_text', __('The 2026 Founder AI Valuation & Scaling Framework is live.', 'foundnxt'));
  $banner_link_text = get_theme_mod('fnx_announcement_link_text', __('Get the Framework', 'foundnxt'));
  $banner_link_url  = get_theme_mod('fnx_announcement_link_url', home_url('/#newsletter'));
?>
<aside class="fnx-top-banner" id="fnx-top-banner" aria-label="<?php esc_attr_e('Announcement', 'foundnxt'); ?>">
  <div class="top-banner-inner container">
    <div class="top-banner-content">
      <?php if (!empty($banner_badge)): ?>
        <span class="top-banner-badge"><?php echo esc_html($banner_badge); ?></span>
      <?php endif; ?>
      <span class="top-banner-text">
        <?php echo esc_html($banner_text); ?>
        <?php if (!empty($banner_link_text) && !empty($banner_link_url)): ?>
          <a href="<?php echo esc_url($banner_link_url); ?>" class="top-banner-link"><?php echo esc_html($banner_link_text); ?> &rarr;</a>
        <?php endif; ?>
      </span>
    </div>
    <button type="button" class="top-banner-close" id="top-banner-close" aria-label="<?php esc_attr_e('Dismiss announcement', 'foundnxt'); ?>">&times;</button>
  </div>
</aside>
<?php endif; ?>

<!-- MAIN HEADER (3-Column Grid: Logo | Centered Links | Actions) -->
<header class="fnx-header" id="fnx-header" role="banner">
  <div class="header-inner container">

    <!-- Column 1: Logo (+12% Increased Size) -->
    <div class="header-logo">
      <?php
      $custom_logo_id = get_theme_mod('custom_logo');
      $site_url       = esc_url(home_url('/'));
      $site_name      = esc_attr(get_bloginfo('name', 'display') ?: 'FoundNXT');
      $dark_logo_id   = get_theme_mod('fnx_dark_logo');

      $light_logo_src = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : get_theme_file_uri('assets/images/logo-light.svg');
      $dark_logo_src  = $dark_logo_id ? wp_get_attachment_image_url($dark_logo_id, 'full') : ($custom_logo_id ? $light_logo_src : get_theme_file_uri('assets/images/logo-dark.svg'));
      ?>
      <a href="<?php echo $site_url; ?>" class="brand-logo-wrap" aria-label="<?php echo $site_name; ?> Home">
        <img src="<?php echo esc_url($light_logo_src); ?>" alt="<?php echo $site_name; ?>" class="fnx-logo-img fnx-logo-light" width="185" height="47" loading="eager" fetchpriority="high">
        <img src="<?php echo esc_url($dark_logo_src); ?>" alt="<?php echo $site_name; ?>" class="fnx-logo-img fnx-logo-dark" width="185" height="47" loading="eager" fetchpriority="high">
      </a>
    </div>

    <!-- Column 2: Centered Links / Navigation Menu -->
    <nav class="header-nav" id="primary-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'foundnxt'); ?>">
      <?php
      if (has_nav_menu('primary')) {
          wp_nav_menu([
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'nav-menu',
              'menu_id'        => 'primary-menu',
              'fallback_cb'    => 'fnx_primary_nav_fallback',
              'walker'         => new FNX_Nav_Walker(),
              'depth'          => 3,
          ]);
      } else {
          fnx_primary_nav_fallback();
      }
      ?>
    </nav>

    <!-- Column 3: Actions (Search, "Work With Us" button, and Mobile Hamburger) -->
    <div class="header-actions">
      <!-- Search Trigger (Accessible Overlay with Focus Trap) -->
      <button class="action-btn search-btn" id="search-toggle"
        aria-label="<?php esc_attr_e('Open search', 'foundnxt'); ?>"
        aria-expanded="false" aria-controls="fnx-search-modal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>

      <!-- Theme Toggle (Dark / Light Mode) -->
      <button type="button" class="action-btn theme-toggle-btn" id="theme-toggle"
        aria-label="<?php esc_attr_e('Toggle dark/light mode', 'foundnxt'); ?>"
        title="<?php esc_attr_e('Toggle dark/light mode', 'foundnxt'); ?>">
        <svg class="theme-icon theme-icon--sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="theme-icon theme-icon--moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>

      <!-- Desktop Action CTA: "Work With Us" Button -->
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-primary header-cta-btn"><?php _e('Work With Us', 'foundnxt'); ?> &rarr;</a>

      <!-- Mobile Hamburger (Visible under 900px, 44x44px minimum tap target) -->
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

<!-- Off-Canvas Menu (< 900px Screens, 44px Minimum Tap Targets) -->
<div class="fnx-mobile-nav" id="mobile-nav" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Mobile Navigation', 'foundnxt'); ?>">
  <div class="mobile-nav-inner">
    <div class="mobile-nav-header">
      <a href="<?php echo $site_url; ?>" class="brand-logo-wrap" aria-label="<?php echo $site_name; ?>">
        <img src="<?php echo esc_url($light_logo_src); ?>" alt="<?php echo $site_name; ?>" class="fnx-logo-img fnx-logo-light" width="155" height="36" loading="eager">
        <img src="<?php echo esc_url($dark_logo_src); ?>" alt="<?php echo $site_name; ?>" class="fnx-logo-img fnx-logo-dark" width="155" height="36" loading="eager">
      </a>
      <button type="button" class="mobile-close" id="mobile-close" aria-label="<?php esc_attr_e('Close menu', 'foundnxt'); ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>

    <div class="mobile-nav-body">
      <!-- Mobile Quick Search Trigger -->
      <button type="button" class="mobile-search-trigger" id="mobile-drawer-search-trigger" aria-label="<?php esc_attr_e('Open search', 'foundnxt'); ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <span><?php _e('Search articles, playbooks, tools…', 'foundnxt'); ?></span>
      </button>

      <?php
      if (has_nav_menu('mobile')) {
          wp_nav_menu([
              'theme_location' => 'mobile',
              'container'      => false,
              'menu_class'     => 'mobile-menu mobile-menu-list',
              'menu_id'        => 'mobile-menu',
              'fallback_cb'    => 'fnx_mobile_nav_fallback',
              'walker'         => new FNX_Nav_Walker(),
              'depth'          => 3,
          ]);
      } elseif (has_nav_menu('primary')) {
          wp_nav_menu([
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'mobile-menu mobile-menu-list',
              'menu_id'        => 'mobile-menu',
              'fallback_cb'    => 'fnx_mobile_nav_fallback',
              'walker'         => new FNX_Nav_Walker(),
              'depth'          => 3,
          ]);
      } else {
          fnx_mobile_nav_fallback();
      }
      ?>

      <!-- Mobile Theme Toggle -->
      <button type="button" class="mobile-theme-toggle" id="mobile-theme-toggle" aria-label="<?php esc_attr_e('Toggle dark/light mode', 'foundnxt'); ?>">
        <span class="mobile-theme-icon-wrap" aria-hidden="true">
          <svg class="theme-icon theme-icon--sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
          <svg class="theme-icon theme-icon--moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </span>
        <span class="mobile-theme-label"><?php _e('Theme', 'foundnxt'); ?></span>
        <span class="mobile-theme-state"></span>
      </button>
    </div>

    <div class="mobile-nav-footer">
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-primary mobile-cta-btn">
        <?php _e('Work With Us', 'foundnxt'); ?> &rarr;
      </a>
      <div class="mobile-footer-links">
        <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact', 'foundnxt'); ?></a>
        <span class="mobile-footer-sep">&bull;</span>
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php _e('Privacy', 'foundnxt'); ?></a>
        <span class="mobile-footer-sep">&bull;</span>
        <a href="<?php echo esc_url(home_url('/disclaimer/')); ?>"><?php _e('Disclaimer', 'foundnxt'); ?></a>
      </div>
    </div>
  </div>
</div>
<div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

<main id="main-content" class="fnx-main" role="main" tabindex="-1">

<?php if (function_exists('fnx_has_ad') && fnx_has_ad('header')): ?>
  <div class="fnx-header-ad-outer">
    <div class="container">
      <?php fnx_render_ad('header'); ?>
    </div>
  </div>
<?php endif; ?>
