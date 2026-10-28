<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="color-scheme" content="light dark">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if (!has_action('wp_head', 'rel_canonical')): ?>
<link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
<?php endif; ?>
<meta name="description" content="<?php echo esc_attr(get_theme_mod('fnx_seo_description', __('Actionable playbooks, tech insights, and scaling strategies for startup founders. Learn how to build faster, secure funding, and navigate your founder journey.', 'foundnxt'))); ?>">
<!-- FoundNXT Modern Favicons & App Icons -->
<link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_theme_file_uri('assets/images/favicon.svg')); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_theme_file_uri('assets/images/favicon.ico')); ?>" sizes="32x32">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_theme_file_uri('assets/images/apple-touch-icon.png')); ?>">
<link rel="manifest" href="<?php echo esc_url(get_theme_file_uri('assets/images/site.webmanifest')); ?>">
<meta name="theme-color" content="#0E1220">
<script>
  (function() {
    try {
      localStorage.removeItem('fnx_theme');
      var isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      var root = document.documentElement;
      if (isDark) {
        root.setAttribute('data-theme', 'dark');
        root.classList.add('dark-mode');
        root.classList.remove('light-mode');
        root.style.colorScheme = 'dark';
      } else {
        root.setAttribute('data-theme', 'light');
        root.classList.add('light-mode');
        root.classList.remove('dark-mode');
        root.style.colorScheme = 'light';
      }
    } catch(e) {}
  })();
</script>
<?php
$fnx_og_title = is_single() ? get_the_title() : (get_bloginfo('name') . ' — ' . (get_bloginfo('description') ?: 'Where Founders Find What’s Next'));
$fnx_og_desc  = is_single() ? wp_strip_all_tags(get_the_excerpt() ?: get_the_title()) : get_theme_mod('fnx_seo_description', 'Actionable frameworks, funding teardowns, valuation models, and AI scaling playbooks for Indian founders.');
$fnx_og_url   = is_single() ? get_permalink() : home_url('/');
$fnx_og_img   = (is_single() && has_post_thumbnail()) ? wp_get_attachment_image_url(get_post_thumbnail_id(), 'fnx-hero') : get_theme_file_uri('assets/images/logo.png');
?>
<meta property="og:title" content="<?php echo esc_attr($fnx_og_title); ?>">
<meta property="og:description" content="<?php echo esc_attr($fnx_og_desc); ?>">
<meta property="og:url" content="<?php echo esc_url($fnx_og_url); ?>">
<meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
<meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>">
<meta property="og:image" content="<?php echo esc_url($fnx_og_img); ?>">
<?php if (is_single()): ?>
<meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
<meta property="article:modified_time" content="<?php echo get_the_modified_date('c'); ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@foundnxt">
<meta name="twitter:title" content="<?php echo esc_attr($fnx_og_title); ?>">
<meta name="twitter:description" content="<?php echo esc_attr($fnx_og_desc); ?>">
<meta name="twitter:image" content="<?php echo esc_url($fnx_og_img); ?>">
<link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr(get_bloginfo('name')); ?> RSS Feed" href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>">

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

<!-- ── TOP ANNOUNCEMENT BANNER (Centered Text, Pinned Close Button, Cookie Dismissal) ── -->
<?php
$announcement_enabled   = (bool) get_theme_mod('fnx_announcement_enabled', true);
$announcement_dismissed = isset($_COOKIE['fnx_announcement_dismissed']) && $_COOKIE['fnx_announcement_dismissed'] === '1';
if ($announcement_enabled && !$announcement_dismissed):
  $banner_badge     = get_theme_mod('fnx_announcement_badge', "✨ " . __("What's New", 'foundnxt'));
  $banner_text      = get_theme_mod('fnx_announcement_text', __('How to scale your company in the AI era.', 'foundnxt'));
  $banner_link_text = get_theme_mod('fnx_announcement_link_text', __('Explore Insights', 'foundnxt'));
  $banner_link_url  = get_theme_mod('fnx_announcement_link_url', home_url('/articles/'));
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

    <!-- Column 2: Centered Links with Categories Dropdown (Articles, Categories ▾, Tools, Services, About) -->
    <nav class="header-nav" id="primary-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'foundnxt'); ?>">
      <ul class="nav-menu">
        <li><a href="<?php echo esc_url(home_url('/articles/')); ?>"><?php _e('Insights', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/category/startup-stories/')); ?>"><?php _e('Startup Stories', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/category/markets-valuation/')); ?>"><?php _e('Markets & Valuation', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/category/ai-tech/')); ?>"><?php _e('AI & Tech', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('Services', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php _e('About', 'foundnxt'); ?></a></li>
      </ul>
    </nav>

    <!-- Column 3: Actions (Search, "Work With Us" button, and Mobile Hamburger) -->
    <div class="header-actions">
      <!-- Search Trigger (Accessible Overlay with Focus Trap) -->
      <button class="action-btn search-btn" id="search-toggle"
        aria-label="<?php esc_attr_e('Open search', 'foundnxt'); ?>"
        aria-expanded="false" aria-controls="fnx-search-modal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
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

      <ul class="mobile-menu-list">
        <li><a href="<?php echo esc_url(home_url('/articles/')); ?>" class="mobile-menu-link"><?php _e('Insights', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/category/startup-stories/')); ?>" class="mobile-menu-link"><?php _e('Startup Stories', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/category/markets-valuation/')); ?>" class="mobile-menu-link"><?php _e('Markets & Valuation', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/category/ai-tech/')); ?>" class="mobile-menu-link"><?php _e('AI & Tech', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="mobile-menu-link"><?php _e('Services', 'foundnxt'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>" class="mobile-menu-link"><?php _e('About', 'foundnxt'); ?></a></li>
      </ul>
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
