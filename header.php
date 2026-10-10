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

<!-- ── TOP ANNOUNCEMENT BANNER (Centered Text, Pinned Close Button, Shared 72rem Container) ── -->
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

<!-- MAIN HEADER (3-Column Grid: Logo | Centered Links | Actions) -->
<header class="fnx-header" id="fnx-header" role="banner">
  <div class="header-inner container">

    <!-- Column 1: Logo -->
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

    <!-- Column 2: Centered Links with Categories Dropdown -->
    <nav class="header-nav" id="primary-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'foundnxt'); ?>">
      <ul class="nav-menu">
        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/articles/')); ?>"><?php _e('Articles', 'foundnxt'); ?></a></li>
        
        <!-- Categories Dropdown (7 Unified Categories) -->
        <li class="menu-item-has-children has-dropdown">
          <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="nav-parent-link" aria-haspopup="true" aria-expanded="false">
            <?php _e('Categories', 'foundnxt'); ?>
            <svg class="nav-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <ul class="sub-menu" role="menu">
            <?php foreach (fnx_get_primary_categories() as $pcat): ?>
              <li role="none">
                <a href="<?php echo esc_url($pcat['url']); ?>" role="menuitem">
                  <span class="cat-menu-dot" style="background:<?php echo esc_attr($pcat['color']); ?>"></span>
                  <span><?php echo esc_html($pcat['name']); ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </li>

        <li><a href="<?php echo esc_url(home_url('/tools/')); ?>"><?php _e('Tools', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('Services', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php _e('About', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact', 'foundnxt'); ?></a></li>
      </ul>
    </nav>

    <!-- Column 3: Actions (Search, Theme Toggle, "Get In Touch" button, and Mobile Hamburger) -->
    <div class="header-actions">
      <!-- Search Trigger (Accessible Overlay with Focus Trap) -->
      <button class="action-btn search-btn" id="search-toggle"
        aria-label="<?php esc_attr_e('Open search', 'foundnxt'); ?>"
        aria-expanded="false" aria-controls="fnx-search-modal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>

      <!-- Theme Toggle -->
      <?php if (get_theme_mod('fnx_dark_mode_toggle', true)): ?>
      <button class="action-btn dark-toggle" id="dark-toggle"
        aria-label="<?php esc_attr_e('Toggle dark mode', 'foundnxt'); ?>">
        <span class="icon-light" aria-hidden="true">☀️</span>
        <span class="icon-dark"  aria-hidden="true">🌙</span>
      </button>
      <?php endif; ?>

      <!-- Desktop Action CTA: "Get In Touch" Button -->
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary header-cta-btn"><?php _e('Get In Touch', 'foundnxt'); ?> →</a>

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
<div class="fnx-mobile-nav" id="mobile-nav" aria-hidden="true">
  <div class="mobile-nav-inner">
    <div class="mobile-nav-header">
      <?php if ($custom_logo_id): ?>
        <a href="<?php echo $site_url; ?>" aria-label="<?php echo $site_name; ?>">
          <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo $site_name; ?>" class="mobile-logo" style="max-height:32px;width:auto;">
        </a>
      <?php else: ?>
        <a href="<?php echo $site_url; ?>" class="brand-logo-wrap" aria-label="<?php echo $site_name; ?>">
          <span class="brand-logo-icon" aria-hidden="true" style="width:28px;height:28px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          </span>
          <span class="brand-logo-main">Found<span class="brand-logo-nxt">NXT</span></span>
        </a>
      <?php endif; ?>
      <button class="mobile-close" id="mobile-close" aria-label="<?php esc_attr_e('Close menu', 'foundnxt'); ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="mobile-nav-body">
      <ul class="mobile-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-menu-link"><?php _e('Home', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/articles/')); ?>" class="mobile-menu-link"><?php _e('Articles', 'foundnxt'); ?></a></li>
        
        <!-- Categories Collapsible Accordion (7 Unified Categories) -->
        <li class="mobile-menu-group">
          <details class="mobile-categories-accordion" id="mobile-categories-accordion">
            <summary class="mobile-menu-link mobile-categories-summary">
              <span><?php _e('Categories', 'foundnxt'); ?></span>
              <svg class="mobile-chevron" width="14" height="14" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </summary>
            <ul class="mobile-submenu-list">
              <?php foreach (fnx_get_primary_categories() as $pcat): ?>
                <li>
                  <a href="<?php echo esc_url($pcat['url']); ?>" class="mobile-sublink">
                    <span class="cat-menu-dot" style="background:<?php echo esc_attr($pcat['color']); ?>"></span>
                    <span><?php echo esc_html($pcat['name']); ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </details>
        </li>

        <li><a href="<?php echo esc_url(home_url('/tools/')); ?>" class="mobile-menu-link"><?php _e('Tools', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="mobile-menu-link"><?php _e('Services', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>" class="mobile-menu-link"><?php _e('About', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>" class="mobile-menu-link"><?php _e('Contact', 'foundnxt'); ?></a></li>
      </ul>
    </div>

    <div class="mobile-nav-footer">
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary mobile-cta-btn">
        <?php _e('Get In Touch', 'foundnxt'); ?> →
      </a>
    </div>
  </div>
</div>
<div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

<main id="main-content" class="fnx-main" role="main" tabindex="-1">
