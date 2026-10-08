<?php
/**
 * FoundNXT Theme Functions
 *
 * @package FoundNXT
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('FNX_VERSION', '1.0.0');
define('FNX_DIR', get_template_directory());
define('FNX_URI', get_template_directory_uri());

/* ============================================================
   THEME SETUP
   ============================================================ */
function fnx_setup() {
    load_theme_textdomain('foundnxt', FNX_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // Custom SEO Title
    add_filter('pre_get_document_title', function($title) {
        if (is_front_page() || is_home()) {
            return 'FoundNXT | Startup, Scaling & Tech Playbooks for Founders';
        }
        return $title;
    }, 15);
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','script','style']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');

    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support('post-formats', ['aside','gallery','link','image','quote','video','audio']);

    // Image sizes
    add_image_size('fnx-hero',    1200, 630, true);
    add_image_size('fnx-card',     600, 340, true);
    add_image_size('fnx-thumb',    400, 225, true);
    add_image_size('fnx-square',   300, 300, true);
    add_image_size('fnx-portrait', 400, 550, true);

    // Menus
    register_nav_menus([
        'primary'    => __('Primary Navigation', 'foundnxt'),
        'secondary'  => __('Secondary / Top Bar', 'foundnxt'),
        'footer-1'   => __('Footer Column 1', 'foundnxt'),
        'footer-2'   => __('Footer Column 2', 'foundnxt'),
        'footer-3'   => __('Footer Column 3', 'foundnxt'),
        'mobile'     => __('Mobile Menu', 'foundnxt'),
    ]);

    // Block editor colour palette
    add_theme_support('editor-color-palette', [
        ['name' => __('Green',        'foundnxt'), 'slug' => 'fnx-green',   'color' => '#4f46e5'],
        ['name' => __('Green Dark',   'foundnxt'), 'slug' => 'fnx-green-dk','color' => '#3730a3'],
        ['name' => __('Ink',          'foundnxt'), 'slug' => 'fnx-ink',     'color' => '#1a1a2e'],
        ['name' => __('Body',         'foundnxt'), 'slug' => 'fnx-body',    'color' => '#374151'],
        ['name' => __('Muted',        'foundnxt'), 'slug' => 'fnx-muted',   'color' => '#6b7280'],
        ['name' => __('Surface',      'foundnxt'), 'slug' => 'fnx-surface', 'color' => '#f7f8fa'],
        ['name' => __('White',        'foundnxt'), 'slug' => 'fnx-white',   'color' => '#ffffff'],
        ['name' => __('Blue',         'foundnxt'), 'slug' => 'fnx-blue',    'color' => '#2563eb'],
        ['name' => __('Amber',        'foundnxt'), 'slug' => 'fnx-amber',   'color' => '#d97706'],
    ]);

    add_theme_support('editor-gradient-presets', [
        ['name' => __('Green Fade', 'foundnxt'), 'slug' => 'fnx-green-fade', 'gradient' => 'linear-gradient(135deg, #4f46e5 0%, #0d6338 100%)'],
        ['name' => __('Dark Hero',  'foundnxt'), 'slug' => 'fnx-dark-hero',  'gradient' => 'linear-gradient(135deg, #1a1a2e 0%, #2d3748 100%)'],
    ]);
}
add_action('after_setup_theme', 'fnx_setup');

/* ============================================================
   ENQUEUE ASSETS
   ============================================================ */
function fnx_enqueue() {
    // Google Fonts
    wp_enqueue_style(
        'fnx-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600;8..60,700&family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style('foundnxt-style', get_stylesheet_uri(), ['fnx-fonts'], FNX_VERSION);

    // Component stylesheets
    wp_enqueue_style('fnx-components', FNX_URI . '/assets/css/components.css', ['foundnxt-style'], FNX_VERSION);
    wp_enqueue_style('fnx-blocks',     FNX_URI . '/assets/css/blocks.css',     ['foundnxt-style'], FNX_VERSION);

    // Theme JS
    wp_enqueue_script('fnx-main', FNX_URI . '/assets/js/main.js', [], FNX_VERSION, true);

    // Pass data to JS
    wp_localize_script('fnx-main', 'fnxData', [
        'apiBase'   => esc_url_raw(rest_url('wp/v2')),
        'homeUrl'   => esc_url(home_url()),
        'nonce'     => wp_create_nonce('wp_rest'),
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'themeUri'  => FNX_URI,
    ]);

    // Comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'fnx_enqueue');

// Block editor styles
function fnx_editor_styles() {
    add_editor_style(['https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,700&display=swap', 'style.css', 'assets/css/editor.css']);
}
add_action('after_setup_theme', 'fnx_editor_styles');

/* ============================================================
   WIDGETS / SIDEBARS
   ============================================================ */
function fnx_widgets() {
    $config = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar(array_merge($config, [
        'name'        => __('Blog Sidebar', 'foundnxt'),
        'id'          => 'fnx-sidebar',
        'description' => __('Widgets for the blog sidebar.', 'foundnxt'),
    ]));
    register_sidebar(array_merge($config, [
        'name'        => __('Article Sidebar', 'foundnxt'),
        'id'          => 'fnx-article-sidebar',
        'description' => __('Widgets shown on single post pages.', 'foundnxt'),
    ]));
    register_sidebar(array_merge($config, [
        'name'        => __('Footer Column 1', 'foundnxt'),
        'id'          => 'fnx-footer-1',
    ]));
    register_sidebar(array_merge($config, [
        'name'        => __('Footer Column 2', 'foundnxt'),
        'id'          => 'fnx-footer-2',
    ]));
    register_sidebar(array_merge($config, [
        'name'        => __('Footer Column 3', 'foundnxt'),
        'id'          => 'fnx-footer-3',
    ]));
    register_sidebar(array_merge($config, [
        'name'        => __('Before Footer Banner', 'foundnxt'),
        'id'          => 'fnx-before-footer',
        'description' => __('Full-width area just above footer. Good for newsletter signup.', 'foundnxt'),
    ]));
}
add_action('widgets_init', 'fnx_widgets');

/* ============================================================
   CUSTOM POST META
   ============================================================ */
function fnx_register_meta() {
    $post_meta = [
        'fnx_read_time'     => ['single' => true,  'type' => 'string',  'show_in_rest' => true],
        'fnx_featured'      => ['single' => true,  'type' => 'boolean', 'show_in_rest' => true],
        'fnx_post_badge'    => ['single' => true,  'type' => 'string',  'show_in_rest' => true],
        'fnx_key_takeaway'  => ['single' => true,  'type' => 'string',  'show_in_rest' => true],
        'fnx_toc_enabled'   => ['single' => true,  'type' => 'boolean', 'show_in_rest' => true],
        'fnx_hero_gradient' => ['single' => true,  'type' => 'string',  'show_in_rest' => true],
    ];
    foreach ($post_meta as $key => $args) {
        register_post_meta('post', $key, $args);
    }
}
add_action('init', 'fnx_register_meta');

/* ============================================================
   EXCERPT & READ TIME
   ============================================================ */
function fnx_excerpt_length($len) { return 30; }
add_filter('excerpt_length', 'fnx_excerpt_length');

function fnx_excerpt_more($more) {
    return ' <a class="read-more-link" href="' . get_permalink() . '">' . __('Read More →', 'foundnxt') . '</a>';
}
add_filter('excerpt_more', 'fnx_excerpt_more');

function fnx_read_time($post_id = null) {
    $post_id  = $post_id ?: get_the_ID();
    $cached   = get_post_meta($post_id, 'fnx_read_time', true);
    if ($cached) return $cached;

    $content  = get_post_field('post_content', $post_id);
    $words    = str_word_count(strip_tags($content));
    $minutes  = max(1, (int) ceil($words / 200));
    $time     = $minutes . ' min read';

    update_post_meta($post_id, 'fnx_read_time', $time);
    return $time;
}

/* ============================================================
   BREADCRUMBS
   ============================================================ */
function fnx_breadcrumbs() {
    if (is_front_page()) return;

    echo '<nav class="fnx-breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'foundnxt') . '">';
    echo '<ol class="breadcrumb-list">';
    echo '<li><a href="' . esc_url(home_url()) . '">' . __('Home', 'foundnxt') . '</a></li>';

    if (is_category()) {
        echo '<li><span>' . single_cat_title('', false) . '</span></li>';
    } elseif (is_tag()) {
        echo '<li><span>' . single_tag_title('', false) . '</span></li>';
    } elseif (is_single()) {
        $cats = get_the_category();
        if ($cats) {
            echo '<li><a href="' . esc_url(get_category_link($cats[0]->term_id)) . '">' . esc_html($cats[0]->name) . '</a></li>';
        }
        echo '<li><span>' . get_the_title() . '</span></li>';
    } elseif (is_page()) {
        if (wp_get_post_parent_id(get_the_ID())) {
            echo '<li><a href="' . esc_url(get_permalink(wp_get_post_parent_id(get_the_ID()))) . '">' . get_the_title(wp_get_post_parent_id(get_the_ID())) . '</a></li>';
        }
        echo '<li><span>' . get_the_title() . '</span></li>';
    } elseif (is_search()) {
        echo '<li><span>' . sprintf(__('Search: "%s"', 'foundnxt'), get_search_query()) . '</span></li>';
    } elseif (is_archive()) {
        echo '<li><span>' . get_the_archive_title() . '</span></li>';
    }

    echo '</ol></nav>';
}

/* ============================================================
   TABLE OF CONTENTS
   ============================================================ */
function fnx_toc($content) {
    if (!is_single()) return $content;
    $post_id = get_the_ID();
    if (!get_post_meta($post_id, 'fnx_toc_enabled', true)) return $content;

    preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/si', $content, $matches, PREG_SET_ORDER);
    if (count($matches) < 3) return $content;

    $toc = '<div class="fnx-toc"><div class="toc-header"><span class="toc-icon">📋</span><span>' . __('Table of Contents', 'foundnxt') . '</span><button class="toc-toggle" aria-label="Toggle TOC">−</button></div><ol class="toc-list">';

    foreach ($matches as $i => $match) {
        $level = $match[1];
        $text  = strip_tags($match[2]);
        $slug  = 'toc-' . sanitize_title($text);
        $toc  .= '<li class="toc-level-' . $level . '"><a href="#' . $slug . '">' . esc_html($text) . '</a></li>';
        $content = str_replace($match[0], '<' . "h{$level}" . ' id="' . $slug . '">' . $match[2] . '</h' . $level . '>', $content);
    }

    $toc .= '</ol></div>';
    return $toc . $content;
}
add_filter('the_content', 'fnx_toc');

/* ============================================================
   RELATED POSTS
   ============================================================ */
function fnx_related_posts($post_id = null, $limit = 4) {
    $post_id = $post_id ?: get_the_ID();
    $cats    = wp_get_post_categories($post_id);
    if (empty($cats)) return [];

    return get_posts([
        'post__not_in'    => [$post_id],
        'category__in'    => $cats,
        'posts_per_page'  => $limit,
        'orderby'         => 'relevance',
        'post_status'     => 'publish',
        'suppress_filters'=> false,
    ]);
}

/* ============================================================
   CUSTOMIZER OPTIONS
   ============================================================ */
function fnx_customizer($wp_customize) {

    // ── FoundNXT Panel ──
    $wp_customize->add_panel('fnx_panel', [
        'title'    => __('FoundNXT Theme', 'foundnxt'),
        'priority' => 30,
    ]);

    // Header Section
    $wp_customize->add_section('fnx_header', [
        'title' => __('Header Settings', 'foundnxt'),
        'panel' => 'fnx_panel',
    ]);
    $wp_customize->add_setting('fnx_topbar_text', ['default' => '📰 What\'s New? Latest funding news, startups & growth stories', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('fnx_topbar_text', ['label' => __('Top Bar Text', 'foundnxt'), 'section' => 'fnx_header', 'type' => 'text']);
    $wp_customize->add_setting('fnx_topbar_enabled', ['default' => true, 'sanitize_callback' => 'wp_validate_boolean']);
    $wp_customize->add_control('fnx_topbar_enabled', ['label' => __('Show Scrolling Top Bar', 'foundnxt'), 'section' => 'fnx_header', 'type' => 'checkbox']);
    $wp_customize->add_setting('fnx_dark_mode_toggle', ['default' => true, 'sanitize_callback' => 'wp_validate_boolean']);
    $wp_customize->add_control('fnx_dark_mode_toggle', ['label' => __('Show Dark Mode Toggle', 'foundnxt'), 'section' => 'fnx_header', 'type' => 'checkbox']);

    // Homepage Section removed — hero H1, subtitle, and post count are
    // hardcoded in index.php and not read via get_theme_mod(), so these
    // settings had no effect and created a misleading customizer UI.

    // Footer Section
    $wp_customize->add_section('fnx_footer', [
        'title' => __('Footer Settings', 'foundnxt'),
        'panel' => 'fnx_panel',
    ]);
    $wp_customize->add_setting('fnx_footer_tagline', ['default' => 'FoundNXT: Startups, Funding & a Smarter Future', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('fnx_footer_tagline', ['label' => __('Footer Tagline', 'foundnxt'), 'section' => 'fnx_footer', 'type' => 'text']);
    $wp_customize->add_setting('fnx_footer_copyright', ['default' => '© ' . date('Y') . ' — FoundNXT. All Rights Reserved.', 'sanitize_callback' => 'wp_kses_post']);
    $wp_customize->add_control('fnx_footer_copyright', ['label' => __('Copyright Text', 'foundnxt'), 'section' => 'fnx_footer', 'type' => 'text']);

    // Brand Section
    $wp_customize->add_section('fnx_brand', [
        'title' => __('Brand & Colours', 'foundnxt'),
        'panel' => 'fnx_panel',
    ]);
    $wp_customize->add_setting('fnx_primary_color', ['default' => '#4f46e5', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fnx_primary_color', ['label' => __('Primary Green', 'foundnxt'), 'section' => 'fnx_brand']));

    // Newsletter
    $wp_customize->add_section('fnx_newsletter', [
        'title' => __('Newsletter Banner', 'foundnxt'),
        'panel' => 'fnx_panel',
    ]);
    $wp_customize->add_setting('fnx_newsletter_heading', ['default' => 'Subscribe to FoundNXT', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('fnx_newsletter_heading', ['label' => __('Newsletter Heading', 'foundnxt'), 'section' => 'fnx_newsletter', 'type' => 'text']);
    $wp_customize->add_setting('fnx_newsletter_sub', ['default' => 'Get actionable insights on startup funding, valuation and business growth — straight to your inbox.', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('fnx_newsletter_sub', ['label' => __('Newsletter Subtext', 'foundnxt'), 'section' => 'fnx_newsletter', 'type' => 'textarea']);
    // Hero Fluent Form ID
    $wp_customize->add_setting('fnx_hero_ff_id', ['default' => '1', 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('fnx_hero_ff_id', ['label' => __('Hero Section: Fluent Form ID', 'foundnxt'), 'description' => __('Enter the Fluent Forms form ID to embed in the homepage hero newsletter section.', 'foundnxt'), 'section' => 'fnx_newsletter', 'type' => 'number']);
}
add_action('customize_register', 'fnx_customizer');

/**
 * Dark Mode Logo — separate upload field in Site Identity panel.
 * Fixes the broken dual the_custom_logo() approach in old header.php.
 */
function fnx_customize_dark_logo($wp_customize) {
    $wp_customize->add_setting('fnx_dark_logo', ['sanitize_callback' => 'absint', 'transport' => 'refresh']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'fnx_dark_logo', [
        'label'       => __('Dark Mode Logo', 'foundnxt'),
        'description' => __('Logo for dark backgrounds. Falls back to main logo if empty.', 'foundnxt'),
        'section'     => 'title_tagline',
        'mime_type'   => 'image',
        'priority'    => 9,
    ]));
}
add_action('customize_register', 'fnx_customize_dark_logo');

// Dynamic colour CSS from customizer
function fnx_customizer_css() {
    $color = get_theme_mod('fnx_primary_color', '#4f46e5');
    if ($color !== '#4f46e5') {
        echo '<style>:root{--fnx-primary:' . esc_attr($color) . '}</style>';
    }
}
add_action('wp_head', 'fnx_customizer_css');

/* ============================================================
   SEO & SCHEMA HELPERS
   ============================================================ */
function fnx_schema_article() {
    if (!is_single()) return;

    $post      = get_post();
    $image     = get_the_post_thumbnail_url(null, 'fnx-hero');
    $cats      = get_the_category();
    $cat_name  = $cats ? $cats[0]->name : 'Article';

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'Article',
        'headline'        => get_the_title(),
        'description'     => get_the_excerpt(),
        'datePublished'   => get_the_date('c'),
        'dateModified'    => get_the_modified_date('c'),
        'author'          => ['@type' => 'Person', 'name' => get_the_author()],
        'publisher'       => [
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
            'logo'  => ['@type' => 'ImageObject', 'url' => esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'))],
        ],
        'articleSection'  => $cat_name,
        'url'             => get_permalink(),
    ];
    if ($image) $schema['image'] = $image;

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'fnx_schema_article');

// Open Graph
function fnx_open_graph() {
    if (is_feed() || is_admin()) return;

    $type  = is_single() ? 'article' : 'website';
    $title = is_singular() ? get_the_title() : get_bloginfo('name');
    $desc  = is_singular() ? get_the_excerpt() : get_bloginfo('description');
    $url   = is_singular() ? get_permalink() : home_url('/');
    $image = is_singular() ? get_the_post_thumbnail_url(null, 'fnx-hero') : '';
    ?>
    <meta property="og:type"        content="<?= esc_attr($type) ?>">
    <meta property="og:title"       content="<?= esc_attr($title) ?>">
    <meta property="og:description" content="<?= esc_attr(wp_strip_all_tags($desc)) ?>">
    <meta property="og:url"         content="<?= esc_url($url) ?>">
    <meta property="og:site_name"   content="<?= esc_attr(get_bloginfo('name')) ?>">
    <?php if ($image): ?>
    <meta property="og:image"       content="<?= esc_url($image) ?>">
    <?php endif; ?>
    <meta name="twitter:card"       content="summary_large_image">
    <meta name="twitter:title"      content="<?= esc_attr($title) ?>">
    <meta name="twitter:description" content="<?= esc_attr(wp_strip_all_tags($desc)) ?>">
    <?php if ($image): ?>
    <meta name="twitter:image"      content="<?= esc_url($image) ?>">
    <?php endif; ?>
    <?php
}
add_action('wp_head', 'fnx_open_graph', 5);

/* ============================================================
   AJAX: LOAD MORE POSTS
   ============================================================ */
function fnx_ajax_load_more() {
    check_ajax_referer('wp_rest', 'nonce');

    $page    = absint($_POST['page'] ?? 1);
    $cat     = absint($_POST['category'] ?? 0);
    $per     = absint($_POST['per_page'] ?? 9);
    $search  = sanitize_text_field($_POST['search'] ?? '');

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per,
        'paged'          => $page,
    ];
    if ($cat)    $args['cat']    = $cat;
    if ($search) $args['s']      = $search;

    $q      = new WP_Query($args);
    $posts  = [];
    $nonce  = wp_create_nonce('wp_rest');

    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $cats = get_the_category();
            $posts[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'excerpt'   => get_the_excerpt(),
                'link'      => get_permalink(),
                'date'      => get_the_date('M j, Y'),
                'thumb'     => get_the_post_thumbnail_url(null, 'fnx-card'),
                'category'  => $cats ? $cats[0]->name : '',
                'cat_url'   => $cats ? get_category_link($cats[0]->term_id) : '',
                'read_time' => fnx_read_time(),
                'author'    => get_the_author(),
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success([
        'posts'      => $posts,
        'max_pages'  => $q->max_num_pages,
        'found'      => $q->found_posts,
    ]);
}
add_action('wp_ajax_fnx_load_more',        'fnx_ajax_load_more');
add_action('wp_ajax_nopriv_fnx_load_more', 'fnx_ajax_load_more');

/**
 * Allow path-based pagination (/page/2/) to work on static pages
 * that use a custom WP_Query (e.g. page-articles.php template).
 * Without this, get_query_var('paged') always returns 0 on pages.
 */
add_action('pre_get_posts', function( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) return;

    // Only act on the articles page template
    $page_id = $query->get_queried_object_id();
    if ( ! $page_id ) return;
    $tpl = get_page_template_slug( $page_id );
    if ( $tpl !== 'page-articles.php' ) return;

    // Pass paged value through so get_query_var('paged') works inside the template
    $paged = (int) get_query_var('paged');
    if ( ! $paged ) {
        $paged = (int) get_query_var('page');
    }
    if ( $paged > 1 ) {
        $query->set( 'paged', $paged );
    }
});

/* ============================================================
   INCLUDE PARTIALS
   ============================================================ */
require_once FNX_DIR . '/inc/nav-walker.php';

/* ============================================================
   PERFORMANCE
   ============================================================ */
// Remove emoji scripts for performance
remove_action('wp_head',             'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles',     'print_emoji_styles');
remove_action('admin_print_styles',  'print_emoji_styles');

// Remove WP block library CSS if not using blocks heavily
// Uncomment below to disable default block CSS (use only if customizing fully)
// add_filter('should_load_separate_core_block_assets', '__return_false');

// Preconnect hints
function fnx_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    // Preload the most-used font weights to prevent FOUT on post titles
    echo '<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,700;8..60,900&family=Inter:wght@400;500;600&family=Lora:wght@400;600&display=swap">' . "\n";
}
add_action('wp_head', 'fnx_preconnect', 1);


/* ============================================================
   CUSTOM BLOCKS
   ============================================================ */
require_once FNX_DIR . '/blocks/register.php';


/* ══════════════════════════════════════════════════
   FIX: WordPress theme/plugin deletion errors
   Caused by FS_METHOD defaulting to FTP when file
   ownership doesn't match the PHP process owner.
   Setting direct tells WP to use PHP's filesystem
   functions instead of FTP — works on shared hosts,
   VPS, and managed WordPress (Hostinger, Bluehost etc.)
══════════════════════════════════════════════════ */
if ( ! defined( 'FS_METHOD' ) ) {
    define( 'FS_METHOD', 'direct' );
}

/* ══════════════════════════════════════════════════
   CONTACT PAGE — SEO meta (title + description)
   Hooks into wp_head before OG tags fire.
══════════════════════════════════════════════════ */
function fnx_contact_seo_head() {
    if ( ! is_page_template('page-contact.php') ) return;
    // Override title via wp_title filter (Yoast/RankMath will take over if active)
    add_filter('pre_get_document_title', function() {
        return 'Contact FoundNXT — Get in Touch';
    });
    // Meta description
    echo '<meta name="description" content="'
       . esc_attr('Reach the FoundNXT team for story tips, guest posts, press releases, partnership enquiries, or general questions. Replies within 24–48 hours.')
       . '">' . "\n";
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">' . "\n";
    // OG overrides
    $url = esc_url(get_permalink());
    echo '<meta property="og:type"        content="website">' . "\n";
    echo '<meta property="og:title"       content="Contact FoundNXT — Get in Touch">' . "\n";
    echo '<meta property="og:description" content="Get in touch for story tips, guest posts, press releases, partnerships, or feedback.">' . "\n";
    echo '<meta property="og:url"         content="' . $url . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta name="twitter:card"        content="summary">' . "\n";
    echo '<meta name="twitter:title"       content="Contact FoundNXT — Get in Touch">' . "\n";
    echo '<meta name="twitter:description" content="Reach out for story tips, guest posts, partnerships, or feedback. Replies within 24–48 hours.">' . "\n";
}
add_action('wp_head', 'fnx_contact_seo_head', 1);

// Force Fluent Forms webhooks to fire synchronously (no pending delay)
add_filter('fluentform/webhooks_send_async', '__return_false');

// ─── Google Translate Widget (Custom — No Google Logo/Banner) ─────────────────
function fnx_google_translate_script() { ?>
<!-- Hidden Google Translate element (we drive it manually) -->
<div id="google_translate_element" style="display:none;visibility:hidden;position:absolute;"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: 'en,ta,hi,te,ml,kn,ur',
    autoDisplay: false
  }, 'google_translate_element');
}
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<style>
/* Aggressively hide ALL Google Translate UI chrome */
.goog-te-banner-frame,
.goog-te-balloon-frame,
#goog-gt-tt,
.goog-tooltip,
.goog-tooltip:hover,
.goog-te-balloon-frame,
.VIpgJd-ZVi9od-aZ2wEe-wOHMyf { display: none !important; }
.goog-te-gadget { display: none !important; }
body { top: 0 !important; }
</style>
<?php }
add_action('wp_footer', 'fnx_google_translate_script', 5);

// Custom translate dropdown JS
function fnx_translate_toggle_script() { ?>
<script>
(function(){
  var btn      = document.getElementById('translate-toggle');
  var dropdown = document.getElementById('fnx-translate-dropdown');
  var wrap     = document.getElementById('fnx-translate-wrap');
  if (!btn || !dropdown) return;

  // Language list: code, flag emoji, native name
  var langs = [
    { code: 'en', flag: '🇬🇧', name: 'English' },
    { code: 'ta', flag: '🇮🇳', name: 'தமிழ்' },
    { code: 'hi', flag: '🇮🇳', name: 'हिन्दी' },
    { code: 'te', flag: '🇮🇳', name: 'తెలుగు' },
    { code: 'ml', flag: '🇮🇳', name: 'മലയാളം' },
    { code: 'kn', flag: '🇮🇳', name: 'ಕನ್ನಡ' },
    { code: 'ur', flag: '🇵🇰', name: 'اردو' }
  ];

  var activeLang = localStorage.getItem('fnx_lang') || 'en';

  // Build dropdown items
  var list = document.createElement('ul');
  list.className = 'fnx-lang-list';
  langs.forEach(function(l) {
    var li = document.createElement('li');
    li.className = 'fnx-lang-item' + (l.code === activeLang ? ' is-active' : '');
    li.setAttribute('data-lang', l.code);
    li.innerHTML = '<span class="fnx-lang-flag">' + l.flag + '</span>'
                 + '<span class="fnx-lang-name">' + l.name + '</span>'
                 + (l.code === activeLang ? '<span class="fnx-lang-check">✓</span>' : '');
    li.addEventListener('click', function() {
      var code = this.getAttribute('data-lang');
      switchLang(code);
      closeDropdown();
    });
    list.appendChild(li);
  });
  dropdown.appendChild(list);

  // Open / close
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    var open = dropdown.classList.contains('is-open');
    open ? closeDropdown() : openDropdown();
  });
  document.addEventListener('click', function(e) {
    if (!wrap.contains(e.target)) closeDropdown();
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDropdown();
  });

  function openDropdown() {
    dropdown.classList.add('is-open');
    btn.setAttribute('aria-expanded', 'true');
  }
  function closeDropdown() {
    dropdown.classList.remove('is-open');
    btn.setAttribute('aria-expanded', 'false');
  }

  // Drive Google Translate's hidden <select> to switch language
  function switchLang(code) {
    if (code === 'en') {
      // Restore to original
      var restore = document.querySelector('.goog-te-menu-value');
      if (restore) restore.click();
      // Fallback: reload without translation cookie
      var ck = document.cookie.match(/googtrans=([^;]+)/);
      if (ck) {
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + location.hostname + ';';
        location.reload();
      }
    } else {
      // Set cookie then trigger Google Translate's select
      var val = '/en/' + code;
      document.cookie = 'googtrans=' + val + '; path=/;';
      document.cookie = 'googtrans=' + val + '; path=/; domain=.' + location.hostname + ';';
      var sel = document.querySelector('select.goog-te-combo');
      if (sel) {
        sel.value = code;
        sel.dispatchEvent(new Event('change'));
      } else {
        location.reload();
      }
    }
    // Update active state
    localStorage.setItem('fnx_lang', code);
    activeLang = code;
    document.querySelectorAll('.fnx-lang-item').forEach(function(el) {
      var isActive = el.getAttribute('data-lang') === code;
      el.classList.toggle('is-active', isActive);
      var check = el.querySelector('.fnx-lang-check');
      if (isActive && !check) {
        var sp = document.createElement('span');
        sp.className = 'fnx-lang-check';
        sp.textContent = '✓';
        el.appendChild(sp);
      } else if (!isActive && check) {
        check.remove();
      }
    });
  }

  // Suppress Google's top banner aggressively
  var observer = new MutationObserver(function() {
    var iframe = document.querySelector('.goog-te-banner-frame');
    if (iframe) { iframe.style.cssText = 'display:none!important'; document.body.style.top = '0'; }
    var tt = document.getElementById('goog-gt-tt');
    if (tt) tt.style.cssText = 'display:none!important';
  });
  observer.observe(document.body, { childList: true, subtree: true });
})();
</script>
<?php }
add_action('wp_footer', 'fnx_translate_toggle_script', 99);

/* ============================================================
   HOMEPAGE HERO — CONTACT FORM SUBMISSION HANDLER
   Handles the Name / Email / Message form that replaced the
   animated illustration in the homepage hero (index.php).
   ============================================================ */
function fnx_handle_homepage_contact() {
    $redirect = home_url('/');

    // Nonce check
    if (
        ! isset($_POST['fnx_homepage_contact_nonce']) ||
        ! wp_verify_nonce($_POST['fnx_homepage_contact_nonce'], 'fnx_homepage_contact')
    ) {
        wp_safe_redirect(add_query_arg('fnx_contact', 'error', $redirect) . '#hp-contact-form');
        exit;
    }

    // Honeypot — bots tend to fill hidden fields
    if (! empty($_POST['hp_website'])) {
        wp_safe_redirect(add_query_arg('fnx_contact', 'success', $redirect) . '#hp-contact-form');
        exit;
    }

    $name    = isset($_POST['hp_contact_name'])    ? sanitize_text_field(wp_unslash($_POST['hp_contact_name'])) : '';
    $email   = isset($_POST['hp_contact_email'])   ? sanitize_email(wp_unslash($_POST['hp_contact_email'])) : '';
    $message = isset($_POST['hp_contact_message']) ? sanitize_textarea_field(wp_unslash($_POST['hp_contact_message'])) : '';

    if (empty($name) || empty($message) || ! is_email($email)) {
        wp_safe_redirect(add_query_arg('fnx_contact', 'error', $redirect) . '#hp-contact-form');
        exit;
    }

    $to      = get_option('admin_email');
    $subject = sprintf(__('[%s] New homepage enquiry from %s', 'foundnxt'), get_bloginfo('name'), $name);
    $body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
    $headers = ['Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>'];

    $sent = wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('fnx_contact', $sent ? 'success' : 'error', $redirect) . '#hp-contact-form');
    exit;
}
add_action('admin_post_fnx_homepage_contact', 'fnx_handle_homepage_contact');
add_action('admin_post_nopriv_fnx_homepage_contact', 'fnx_handle_homepage_contact');

