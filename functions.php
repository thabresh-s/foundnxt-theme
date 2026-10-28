<?php
/**
 * FoundNXT Theme Functions
 *
 * @package FoundNXT
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('FNX_VERSION', '1.5.0');
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
            return 'FoundNXT | How to Scale Your Company in the AI Era';
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
        ['name' => __('Brand Orange', 'foundnxt'), 'slug' => 'fnx-brand',    'color' => '#FF5A2C'],
        ['name' => __('Brand Dark',   'foundnxt'), 'slug' => 'fnx-brand-dk', 'color' => '#E84A1C'],
        ['name' => __('Ink',          'foundnxt'), 'slug' => 'fnx-ink',      'color' => '#0E1220'],
        ['name' => __('Body',         'foundnxt'), 'slug' => 'fnx-body',     'color' => '#374151'],
        ['name' => __('Muted',        'foundnxt'), 'slug' => 'fnx-muted',    'color' => '#6b7280'],
        ['name' => __('Surface',      'foundnxt'), 'slug' => 'fnx-surface',  'color' => '#f7f8fa'],
        ['name' => __('White',        'foundnxt'), 'slug' => 'fnx-white',    'color' => '#ffffff'],
        ['name' => __('Blue',         'foundnxt'), 'slug' => 'fnx-blue',     'color' => '#2563eb'],
        ['name' => __('Amber',        'foundnxt'), 'slug' => 'fnx-amber',    'color' => '#d97706'],
    ]);

    add_theme_support('editor-gradient-presets', [
        ['name' => __('Brand Fade', 'foundnxt'), 'slug' => 'fnx-brand-fade', 'gradient' => 'linear-gradient(135deg, #FF5A2C 0%, #FF7A45 100%)'],
        ['name' => __('Dark Hero',  'foundnxt'), 'slug' => 'fnx-dark-hero',  'gradient' => 'linear-gradient(135deg, #0E1220 0%, #1B233C 100%)'],
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
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Poppins:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style('foundnxt-style', get_stylesheet_uri(), ['fnx-fonts'], FNX_VERSION);

    // Component stylesheets
    wp_enqueue_style('fnx-components', FNX_URI . '/assets/css/components.css', ['foundnxt-style'], FNX_VERSION);
    wp_enqueue_style('fnx-blocks',     FNX_URI . '/assets/css/blocks.css',     ['foundnxt-style'], FNX_VERSION);

    // Modern Design System Stylesheet (v2.4.1: brand color sync, system dark/light mode, brand logo)
    wp_enqueue_style(
        'fnx-modern-design-system',
        FNX_URI . '/assets/css/modern-design-system.css',
        ['foundnxt-style', 'fnx-components'],
        FNX_VERSION
    );

    // Theme JS
    wp_enqueue_script('fnx-main', FNX_URI . '/assets/js/main.js', [], FNX_VERSION, [
        'strategy'  => 'defer',
        'in_footer' => true,
    ]);

    // Modern Design System JS (Search overlay focus trap, banner dismissal)
    wp_enqueue_script(
        'fnx-modern-design-system',
        FNX_URI . '/assets/js/modern-design-system.js',
        [],
        FNX_VERSION,
        [
            'strategy'  => 'defer',
            'in_footer' => true,
        ]
    );

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
    add_editor_style([
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Poppins:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap',
        'style.css',
        'assets/css/editor.css'
    ]);
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

    // Header Announcement Banner Section
    $wp_customize->add_section('fnx_header', [
        'title'    => __('Header Announcement Banner', 'foundnxt'),
        'panel'    => 'fnx_panel',
        'priority' => 10,
    ]);
    $wp_customize->add_setting('fnx_announcement_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_announcement_enabled', [
        'label'    => __('Enable Announcement Banner', 'foundnxt'),
        'section'  => 'fnx_header',
        'type'     => 'checkbox',
        'priority' => 10,
    ]);
    $wp_customize->add_setting('fnx_announcement_badge', [
        'default'           => "✨ What's New",
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_announcement_badge', [
        'label'    => __('Badge Text', 'foundnxt'),
        'section'  => 'fnx_header',
        'type'     => 'text',
        'priority' => 20,
    ]);
    $wp_customize->add_setting('fnx_announcement_text', [
        'default'           => 'The 2026 Founder AI Valuation & Scaling Framework is live.',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_announcement_text', [
        'label'    => __('Announcement Message', 'foundnxt'),
        'section'  => 'fnx_header',
        'type'     => 'text',
        'priority' => 30,
    ]);
    $wp_customize->add_setting('fnx_announcement_link_text', [
        'default'           => 'Get the Framework',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_announcement_link_text', [
        'label'    => __('Link Text', 'foundnxt'),
        'section'  => 'fnx_header',
        'type'     => 'text',
        'priority' => 40,
    ]);
    $wp_customize->add_setting('fnx_announcement_link_url', [
        'default'           => home_url('/#newsletter'),
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('fnx_announcement_link_url', [
        'label'    => __('Link URL', 'foundnxt'),
        'section'  => 'fnx_header',
        'type'     => 'url',
        'priority' => 50,
    ]);

    // Footer Section
    $wp_customize->add_section('fnx_footer', [
        'title'    => __('Footer Settings', 'foundnxt'),
        'panel'    => 'fnx_panel',
        'priority' => 20,
    ]);
    $wp_customize->add_setting('fnx_footer_tagline', [
        'default'           => 'How to Scale Your Company in the AI Era',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_footer_tagline', [
        'label'    => __('Footer Primary Tagline', 'foundnxt'),
        'section'  => 'fnx_footer',
        'type'     => 'text',
        'priority' => 10,
    ]);
    $wp_customize->add_setting('fnx_footer_tagline_sub', [
        'default'           => 'Business. Markets. Technology. Growth.',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_footer_tagline_sub', [
        'label'    => __('Footer Sub-Tagline', 'foundnxt'),
        'section'  => 'fnx_footer',
        'type'     => 'text',
        'priority' => 20,
    ]);
    $wp_customize->add_setting('fnx_footer_copyright', [
        'default'           => '© ' . date('Y') . ' — FoundNXT. All Rights Reserved.',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('fnx_footer_copyright', [
        'label'    => __('Copyright Text', 'foundnxt'),
        'section'  => 'fnx_footer',
        'type'     => 'text',
        'priority' => 30,
    ]);

    // Brand Section
    $wp_customize->add_section('fnx_brand', [
        'title'    => __('Brand & Colours', 'foundnxt'),
        'panel'    => 'fnx_panel',
        'priority' => 30,
    ]);
    $wp_customize->add_setting('fnx_primary_color', [
        'default'           => '#FF5A2C',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fnx_primary_color', [
        'label'   => __('Primary Accent Color', 'foundnxt'),
        'section' => 'fnx_brand',
    ]));

    // Top Categories Blog Sections
    $wp_customize->add_section('fnx_top_categories_section', [
        'title'       => __('📑 Top Category Blog Sections', 'foundnxt'),
        'description' => __('Configure the top 5 category-wise blog showcases on the homepage.', 'foundnxt'),
        'panel'       => 'fnx_panel',
        'priority'    => 26,
    ]);

    $wp_customize->add_setting('fnx_top_cats_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_top_cats_enabled', [
        'label'    => __('Enable Category Blog Sections', 'foundnxt'),
        'section'  => 'fnx_top_categories_section',
        'type'     => 'checkbox',
        'priority' => 10,
    ]);

    $wp_customize->add_setting('fnx_top_cats_count', [
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fnx_top_cats_count', [
        'label'       => __('Number of Categories to Showcase', 'foundnxt'),
        'description' => __('Default is 5 top categories, displayed one by one.', 'foundnxt'),
        'section'     => 'fnx_top_categories_section',
        'type'        => 'number',
        'input_attrs' => ['min' => 1, 'max' => 8, 'step' => 1],
        'priority'    => 20,
    ]);

    $wp_customize->add_setting('fnx_top_cats_posts_per_cat', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fnx_top_cats_posts_per_cat', [
        'label'       => __('Articles Per Category Section', 'foundnxt'),
        'description' => __('Number of articles to display per category (default: 3).', 'foundnxt'),
        'section'     => 'fnx_top_categories_section',
        'type'        => 'number',
        'input_attrs' => ['min' => 2, 'max' => 6, 'step' => 1],
        'priority'    => 30,
    ]);

    // Newsletter Section
    $wp_customize->add_section('fnx_newsletter', [
        'title'    => __('Newsletter Banner', 'foundnxt'),
        'panel'    => 'fnx_panel',
        'priority' => 40,
    ]);
    $wp_customize->add_setting('fnx_newsletter_heading', [
        'default'           => 'Subscribe to FoundNXT',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_newsletter_heading', [
        'label'    => __('Newsletter Heading', 'foundnxt'),
        'section'  => 'fnx_newsletter',
        'type'     => 'text',
        'priority' => 10,
    ]);
    $wp_customize->add_setting('fnx_newsletter_sub', [
        'default'           => 'Get actionable insights on startup funding, valuation and business growth — straight to your inbox.',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_newsletter_sub', [
        'label'    => __('Newsletter Subtext', 'foundnxt'),
        'section'  => 'fnx_newsletter',
        'type'     => 'textarea',
        'priority' => 20,
    ]);
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
    $color = get_theme_mod('fnx_primary_color', '#FF5A2C');
    if ($color !== '#FF5A2C') {
        echo '<style>:root{--fnx-primary:' . esc_attr($color) . ';--brand:' . esc_attr($color) . '}</style>';
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
    if (is_front_page() || is_home()) {
        $title = 'FoundNXT | How to Scale Your Company in the AI Era';
        $desc  = 'How to Scale Your Company in the AI Era. Actionable frameworks, tech recommendations, and business strategies built for founders and leaders.';
    } else {
        $title = is_singular() ? get_the_title() : get_bloginfo('name');
        $desc  = is_singular() ? get_the_excerpt() : get_bloginfo('description');
    }
    $url   = is_singular() ? get_permalink() : home_url('/');
    $image = is_singular() ? get_the_post_thumbnail_url(null, 'fnx-hero') : '';
    if (!$image) {
        $image = get_theme_file_uri('assets/images/brand/png/foundnxt-logo-dark-bg.png');
    }
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

/**
 * Modern Brand Favicons & Icons Hook
 * Outputs SVG, ICO, Apple Touch Icon, and Web App Manifest in head if not set via Customizer
 */
function fnx_render_favicon_tags() {
    if (!has_site_icon()) {
        $theme_uri = get_template_directory_uri();
        ?>
<!-- FoundNXT Modern Favicons & App Icons -->
<link rel="icon" type="image/svg+xml" href="<?php echo esc_url($theme_uri . '/assets/images/favicon.svg'); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo esc_url($theme_uri . '/assets/images/favicon.ico'); ?>" sizes="32x32">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($theme_uri . '/assets/images/apple-touch-icon.png'); ?>">
<link rel="manifest" href="<?php echo esc_url($theme_uri . '/assets/images/site.webmanifest'); ?>">
<meta name="theme-color" content="#0E1220">
<meta name="msapplication-TileColor" content="#0E1220">
        <?php
    }
}
add_action('wp_head', 'fnx_render_favicon_tags', 1);
add_action('admin_head', 'fnx_render_favicon_tags', 1);
add_action('login_head', 'fnx_render_favicon_tags', 1);

/**
 * Custom Login Page Logo
 */
function fnx_login_logo_customizer() {
    $logo_url = get_theme_file_uri('assets/images/logo-light.svg');
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url('<?php echo esc_url($logo_url); ?>') !important;
            background-size: contain !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            width: 220px !important;
            height: 52px !important;
            margin-bottom: 24px !important;
        }
        body.login {
            background: #0E1220 !important;
        }
        body.login #loginform {
            border-radius: 14px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'fnx_login_logo_customizer');

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
require_once FNX_DIR . '/inc/admin-crm.php';
require_once FNX_DIR . '/inc/resend-service.php';
require_once FNX_DIR . '/inc/related-posts.php';
require_once FNX_DIR . '/inc/featured-posts.php';
require_once FNX_DIR . '/inc/performance.php';

/* ============================================================
   PERFORMANCE
   ============================================================ */
// Emoji scripts handled in inc/performance.php

// Remove jQuery Migrate from front-end to reduce script weight & INP
add_action('wp_default_scripts', function ($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
});

// Remove core wp-embed script on front-end
add_action('wp_footer', function() {
    wp_deregister_script('wp-embed');
});

// Automatically wrap content tables in responsive overflow container
function fnx_wrap_responsive_tables($content) {
    if (is_admin()) return $content;
    return preg_replace('/<table(.*?)<\/table>/is', '<div class="table-responsive-wrapper"><table$1</table></div>', $content);
}
add_filter('the_content', 'fnx_wrap_responsive_tables', 20);

// Preconnect & resource hints handled in inc/performance.php


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

// Fluent Forms and legacy Google Translate widget removed for cleanliness and performance.

/* ============================================================
   HOMEPAGE HERO — CONTACT FORM SUBMISSION HANDLER
   ============================================================ */
add_action('admin_post_fnx_homepage_contact',        'fnx_handle_lead_submission');
add_action('admin_post_nopriv_fnx_homepage_contact', 'fnx_handle_lead_submission');



/* ============================================================
   FOUNDNXT V2 — CATEGORY COLOR & ICON HELPERS
   ============================================================ */
function fnx_get_category_color($category_slug_or_id = 'news-insights') {
    $colors = [
        'startups-funding'        => '#FF6B6B', // Coral
        'startups'                => '#FF6B6B', // Coral
        'startup-stories'         => '#7C3AED', // Electric Violet
        'case-studies-teardowns'  => '#7C3AED', // Electric Violet
        'markets-valuation'       => '#F59E0B', // Amber
        'valuation-finance'       => '#F59E0B', // Amber
        'markets-economy'         => '#F59E0B', // Amber
        'ai-tech'                 => '#14B8A6', // Teal
        'technology-ai'           => '#14B8A6', // Teal
        'ai'                      => '#14B8A6', // Teal
        'marketing-growth'        => '#F43F5E', // Rose / Magenta
        'global-business'         => '#0EA5E9', // Sky Blue
        'cost-cutting-operations' => '#10B981', // Mint / Emerald
        'market-research'         => '#6366F1', // Indigo
        'news-updates'            => '#FB923C', // Tangerine / Orange
        'news-insights'           => '#FB923C', // Tangerine / Orange
        'business-strategy'       => '#FF6B6B', // Coral
        'founder-playbook'        => '#3B82F6', // Blue
        'funding-venture'         => '#10B981', // Emerald
        'india-specific'          => '#F59E0B', // Amber
        'deeptech'                => '#6366F1', // Indigo
        'ipo-markets'             => '#0284C7', // Sky Blue
        'whats-next'              => '#8B5CF6', // Purple
    ];
    if (is_numeric($category_slug_or_id)) {
        $term = get_term($category_slug_or_id, 'category');
        if ($term && !is_wp_error($term)) {
            $slug = $term->slug;
            return isset($colors[$slug]) ? $colors[$slug] : '#FF5A2C';
        }
    }
    return isset($colors[$category_slug_or_id]) ? $colors[$category_slug_or_id] : '#FF5A2C';
}

function fnx_get_category_icon($slug_or_id = '') {
    $icons = [
        'startups-funding'        => '🚀',
        'startups'                => '🚀',
        'startup-stories'         => '🔥',
        'case-studies-teardowns'  => '🔥',
        'markets-valuation'       => '📊',
        'valuation-finance'       => '📊',
        'markets-economy'         => '📊',
        'ai-tech'                 => '🤖',
        'technology-ai'           => '🤖',
        'ai'                      => '🤖',
        'marketing-growth'        => '🎯',
        'global-business'         => '🌍',
        'cost-cutting-operations' => '⚡',
        'market-research'         => '🔍',
        'news-updates'            => '📰',
        'news-insights'           => '📰',
        'founder-playbook'        => '📘',
        'business-strategy'       => '💼',
        'funding-venture'         => '💰',
        'india-specific'          => '🇮🇳',
        'deeptech'                => '🔬',
        'ipo-markets'             => '🏛️',
        'whats-next'              => '✨',
    ];
    if (is_numeric($slug_or_id)) {
        $term = get_term($slug_or_id, 'category');
        if ($term && !is_wp_error($term)) {
            $slug_or_id = $term->slug;
        }
    }
    return $icons[$slug_or_id] ?? '📌';
}


/* ============================================================
   FOUNDNXT V2 — LEAD GENERATION CPT & ADMIN MENU
   ============================================================ */
function fnx_register_lead_cpt() {
    register_post_type('fnx_lead', [
        'labels' => [
            'name'          => 'FoundNXT Leads',
            'singular_name' => 'Lead Entry',
            'add_new_item'  => 'Add New Lead',
            'edit_item'     => 'View Lead Entry',
            'search_items'  => 'Search Leads',
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'menu_icon'       => 'dashicons-email-alt',
        'supports'        => ['title', 'editor', 'custom-fields'],
        'capability_type' => 'post',
    ]);
}
add_action('init', 'fnx_register_lead_cpt');


/* ============================================================
   FOUNDNXT V2 — UNIFIED LEAD FORM SUBMISSION HANDLER
   ============================================================ */
function fnx_handle_lead_submission() {
    if (
        ! isset($_POST['fnx_lead_nonce_field']) ||
        ! wp_verify_nonce($_POST['fnx_lead_nonce_field'], 'fnx_lead_nonce')
    ) {
        wp_die(__('Security verification failed. Please go back and try submitting again.', 'foundnxt'));
    }

    // Honeypot check
    if (! empty($_POST['lead_hp_field'])) {
        $referer = wp_get_referer() ?: home_url('/');
        wp_safe_redirect(add_query_arg('fnx_lead', 'success', $referer));
        exit;
    }

    $name          = isset($_POST['lead_name'])    ? sanitize_text_field(wp_unslash($_POST['lead_name'])) : '';
    $email         = isset($_POST['lead_email'])   ? sanitize_email(wp_unslash($_POST['lead_email'])) : '';
    $phone         = isset($_POST['lead_phone'])   ? sanitize_text_field(wp_unslash($_POST['lead_phone'])) : '';
    $company_site  = isset($_POST['lead_company']) ? sanitize_text_field(wp_unslash($_POST['lead_company'])) : '';
    $company_stage = isset($_POST['lead_stage'])   ? sanitize_text_field(wp_unslash($_POST['lead_stage'])) : 'Not Specified';
    $service_help  = isset($_POST['lead_help'])    ? sanitize_text_field(wp_unslash($_POST['lead_help'])) : 'General Enquiry';
    $message       = isset($_POST['lead_message']) ? sanitize_textarea_field(wp_unslash($_POST['lead_message'])) : '';
    $lead_type     = isset($_POST['lead_type'])    ? sanitize_text_field(wp_unslash($_POST['lead_type'])) : 'Lead Submission';
    $target_sheet  = isset($_POST['target_sheet']) ? sanitize_key($_POST['target_sheet']) : '';

    if (empty($name) && ! empty($email) && is_email($email)) {
        $parts = explode('@', $email);
        $name  = ucwords(str_replace(['.', '_', '-'], ' ', sanitize_text_field($parts[0])));
    }

    if (empty($name) || empty($email) || ! is_email($email)) {
        $referer = wp_get_referer() ?: home_url('/');
        wp_safe_redirect(add_query_arg('fnx_lead', 'error', $referer));
        exit;
    }

    // 1. Create Lead Post in WP Dashboard
    $post_id = wp_insert_post([
        'post_type'    => 'fnx_lead',
        'post_title'   => sprintf('%s — %s (%s)', $name, $service_help, date('M j, Y H:i')),
        'post_content' => sprintf(
            "Name: %s\nEmail: %s\nMobile/Phone: %s\nCompany/Website: %s\nCompany Stage: %s\nHelp Required: %s\nLead Type: %s\n\nMessage:\n%s",
            $name, $email, $phone, $company_site, $company_stage, $service_help, $lead_type, $message
        ),
        'post_status'  => 'publish',
    ]);

    if ($post_id && ! is_wp_error($post_id)) {
        update_post_meta($post_id, 'lead_name', $name);
        update_post_meta($post_id, 'lead_email', $email);
        update_post_meta($post_id, 'lead_phone', $phone);
        update_post_meta($post_id, 'lead_company', $company_site);
        update_post_meta($post_id, 'lead_stage', $company_stage);
        update_post_meta($post_id, 'lead_help', $service_help);
        update_post_meta($post_id, 'lead_type', $lead_type);
        update_post_meta($post_id, 'lead_message', $message);
        update_post_meta($post_id, 'lead_source', $referer);
        update_post_meta($post_id, 'lead_target_sheet', $target_sheet);
    }

    // 2. Classify Destination & Dispatch Resend Email Communications
    $is_contact = ($target_sheet === 'contact') || (stripos($lead_type, 'Contact') !== false) || ! empty($message) || ! empty($company_site) || ! empty($phone);
    if (empty($target_sheet)) {
        $target_sheet = $is_contact ? 'contact' : 'newsletter';
    }

    $lead_data = [
        'target_sheet' => $target_sheet,
        'name'         => $name,
        'email'        => $email,
        'lead_type'    => $lead_type,
        'phone'        => $phone,
        'company'      => $company_site,
        'stage'        => $company_stage,
        'help'         => $service_help,
        'message'      => $message,
        'source'       => $referer,
    ];

    if (function_exists('fnx_resend_send_email')) {
        if ($is_contact) {
            // Contact submission: Admin notification & User confirmation auto-reply
            if (fnx_resend_feature_enabled('contact_admin_alert')) {
                fnx_resend_send_contact_admin_alert($lead_data, $post_id);
            }
            if (fnx_resend_feature_enabled('contact_reply')) {
                fnx_resend_send_contact_autoresponder($lead_data);
            }
        } else {
            // Newsletter submission:
            // If they had previously unsubscribed, restore their subscription
            if (function_exists('fnx_resend_is_unsubscribed') && fnx_resend_is_unsubscribed($email)) {
                fnx_resend_mark_resubscribed($email);
            }
            // Send Welcome Email with compliant Unsubscribe footer & RFC 8058 headers
            if (fnx_resend_feature_enabled('welcome_email')) {
                fnx_resend_send_newsletter_welcome($email, $name);
            }
            // Optional admin notification for newsletter signup
            if (fnx_resend_feature_enabled('newsletter_alert')) {
                fnx_resend_send_contact_admin_alert($lead_data, $post_id);
            }
        }
    } else {
        // Fallback plain text admin mail if Resend module is unavailable
        $admin_email = get_option('admin_email');
        $subject     = sprintf('[%s Lead] New %s from %s', get_bloginfo('name'), $lead_type, $name);
        $body        = "New Lead Received on FoundNXT!\n\nName: {$name}\nEmail: {$email}\nType: {$lead_type}";
        @wp_mail($admin_email, $subject, $body);
    }

    // 3. Sync to Google Sheets if Webhook URL is configured
    fnx_sync_lead_to_gsheets($lead_data);

    $referer = wp_get_referer() ?: home_url('/');
    $hash    = $is_contact ? '#contact' : '#newsletter';
    wp_safe_redirect(add_query_arg('fnx_lead', 'success', $referer) . $hash);
    exit;
}
add_action('admin_post_fnx_lead_submit',        'fnx_handle_lead_submission');
add_action('admin_post_nopriv_fnx_lead_submit', 'fnx_handle_lead_submission');


/* ============================================================
   FOUNDNXT — GOOGLE SHEETS SYNC INTEGRATION
   ============================================================ */

/**
 * Dispatch lead data to Google Sheets via Webhook URL.
 * Supports Google Apps Script Web App, Make.com, or Zapier webhooks.
 *
 * @param array $lead_data
 * @return bool|WP_Error
 */
function fnx_sync_lead_to_gsheets($lead_data) {
    $default_webhook = 'https://script.google.com/macros/s/AKfycbyn-p1CB4KOwABIk4gSFnOqL1Tu9eS13xl-xfIoCR9tmqceIBF-JAYOQgWqi-j8QP25/exec';
    $webhook_url = defined('FNX_GSHEET_WEBHOOK_URL') && FNX_GSHEET_WEBHOOK_URL
        ? FNX_GSHEET_WEBHOOK_URL
        : get_option('fnx_gsheet_webhook_url', get_theme_mod('fnx_gsheet_webhook_url', $default_webhook));

    if (empty($webhook_url) || ! filter_var($webhook_url, FILTER_VALIDATE_URL)) {
        $webhook_url = $default_webhook;
    }

    $lead_type = sanitize_text_field($lead_data['lead_type'] ?? '');
    $message   = sanitize_textarea_field($lead_data['message'] ?? '');

    // Classify destination tab: 'contact' or 'newsletter'
    if (isset($lead_data['target_sheet']) && in_array($lead_data['target_sheet'], ['newsletter', 'contact'], true)) {
        $target_sheet = $lead_data['target_sheet'];
    } else {
        $is_contact = stripos($lead_type, 'Contact') !== false || ! empty($message) || ! empty($lead_data['company']);
        $target_sheet = $is_contact ? 'contact' : 'newsletter';
    }

    $payload = [
        'target_sheet' => $target_sheet,
        'timestamp'    => current_time('mysql'),
        'date'         => current_time('M j, Y H:i:s'),
        'name'         => sanitize_text_field($lead_data['name'] ?? ''),
        'email'        => sanitize_email($lead_data['email'] ?? ''),
        'lead_type'    => $lead_type ?: ($target_sheet === 'contact' ? 'Contact Form Submission' : 'Newsletter Signup'),
        'phone'        => sanitize_text_field($lead_data['phone'] ?? ''),
        'company'      => sanitize_text_field($lead_data['company'] ?? ''),
        'stage'        => sanitize_text_field($lead_data['stage'] ?? ''),
        'help'         => sanitize_text_field($lead_data['help'] ?? ''),
        'message'      => $message,
        'source'       => esc_url_raw($lead_data['source'] ?? home_url('/')),
    ];

    $response = wp_remote_post($webhook_url, [
        'method'      => 'POST',
        'timeout'     => 12,
        'redirection' => 5,
        'httpversion' => '1.1',
        'headers'     => [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept'       => 'application/json',
        ],
        'body'        => wp_json_encode($payload),
    ]);

    if (is_wp_error($response)) {
        error_log('[FoundNXT Google Sheets Error] ' . $response->get_error_message());
        return $response;
    }

    return true;
}

/**
 * Register Google Sheets submenu page under FoundNXT Leads CPT
 */
function fnx_add_gsheet_admin_page() {
    add_submenu_page(
        'edit.php?post_type=fnx_lead',
        __('Google Sheets Sync', 'foundnxt'),
        __('Google Sheets Sync', 'foundnxt'),
        'manage_options',
        'fnx-gsheet-sync',
        'fnx_render_gsheet_admin_page'
    );
}
add_action('admin_menu', 'fnx_add_gsheet_admin_page');

/**
 * Render Google Sheets Settings & Setup Page
 */
function fnx_render_gsheet_admin_page() {
    if (! current_user_can('manage_options')) {
        return;
    }

    $notice = '';

    // Handle Form Save
    if (isset($_POST['fnx_save_gsheet_settings']) && check_admin_referer('fnx_gsheet_settings_action', 'fnx_gsheet_nonce')) {
        $saved_url = esc_url_raw(trim($_POST['fnx_gsheet_webhook_url'] ?? ''));
        update_option('fnx_gsheet_webhook_url', $saved_url);
        set_theme_mod('fnx_gsheet_webhook_url', $saved_url);
        $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . __('Settings saved successfully!', 'foundnxt') . '</strong></p></div>';
    }

    // Handle Test Newsletter Connection
    if (isset($_POST['fnx_test_newsletter']) && check_admin_referer('fnx_gsheet_settings_action', 'fnx_gsheet_nonce')) {
        $test_res = fnx_sync_lead_to_gsheets([
            'target_sheet' => 'newsletter',
            'name'         => 'FoundNXT Test Subscriber',
            'email'        => 'subscriber@foundnxt.com',
            'lead_type'    => 'Newsletter Signup Test',
            'source'       => home_url('/#newsletter-section'),
        ]);

        if (is_wp_error($test_res)) {
            $notice = '<div class="notice notice-error is-dismissible"><p><strong>' . sprintf(__('Test failed: %s', 'foundnxt'), esc_html($test_res->get_error_message())) . '</strong></p></div>';
        } elseif ($test_res === true) {
            $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . __('Success! A test row was sent to your "newsletter" tab in Google Sheets. Check your spreadsheet now.', 'foundnxt') . '</strong></p></div>';
        } else {
            $notice = '<div class="notice notice-warning is-dismissible"><p><strong>' . __('Please enter a valid Google Sheets Webhook URL first.', 'foundnxt') . '</strong></p></div>';
        }
    }

    // Handle Test Contact Connection
    if (isset($_POST['fnx_test_contact']) && check_admin_referer('fnx_gsheet_settings_action', 'fnx_gsheet_nonce')) {
        $test_res = fnx_sync_lead_to_gsheets([
            'target_sheet' => 'contact',
            'name'         => 'Jordan Lee (Test Contact)',
            'email'        => 'jordan@company.com',
            'lead_type'    => 'Contact Page Form Submission',
            'phone'        => '+1 (555) 019-2834',
            'company'      => 'Acme Technologies Inc.',
            'stage'        => 'Growth / Series A+',
            'help'         => 'Valuation Guidance',
            'message'      => 'This is a test submission verifying that contact page enquiries cleanly sync into the "contact" tab.',
            'source'       => home_url('/contact/'),
        ]);

        if (is_wp_error($test_res)) {
            $notice = '<div class="notice notice-error is-dismissible"><p><strong>' . sprintf(__('Test failed: %s', 'foundnxt'), esc_html($test_res->get_error_message())) . '</strong></p></div>';
        } elseif ($test_res === true) {
            $notice = '<div class="notice notice-success is-dismissible"><p><strong>' . __('Success! A test row was sent to your "contact" tab in Google Sheets. Check your spreadsheet now.', 'foundnxt') . '</strong></p></div>';
        } else {
            $notice = '<div class="notice notice-warning is-dismissible"><p><strong>' . __('Please enter a valid Google Sheets Webhook URL first.', 'foundnxt') . '</strong></p></div>';
        }
    }

    $current_url = get_option('fnx_gsheet_webhook_url', get_theme_mod('fnx_gsheet_webhook_url', 'https://script.google.com/macros/s/AKfycbyn-p1CB4KOwABIk4gSFnOqL1Tu9eS13xl-xfIoCR9tmqceIBF-JAYOQgWqi-j8QP25/exec'));
    ?>
    <div class="wrap" style="max-width: 980px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <span class="dashicons dashicons-spreadsheet" style="font-size: 32px; width: 32px; height: 32px; color: #10b981;"></span>
            <?php _e('FoundNXT — Google Sheets 2-Page Sync (Newsletter & Contact)', 'foundnxt'); ?>
        </h1>

        <?php echo $notice; ?>

        <!-- Step 1: Webhook URL & Testing -->
        <div style="background: #ffffff; border: 1px solid #ccd0d4; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px;">
            <h2 style="margin-top: 0; font-size: 1.25rem;"><?php _e('1. Webhook Configuration', 'foundnxt'); ?></h2>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 18px;">
                <?php _e('Paste your Google Apps Script Web App URL below. When visitors subscribe to the newsletter, it routes to the <strong>newsletter</strong> tab. When visitors send a contact form enquiry, it routes to the <strong>contact</strong> tab.', 'foundnxt'); ?>
            </p>

            <form method="post" action="">
                <?php wp_nonce_field('fnx_gsheet_settings_action', 'fnx_gsheet_nonce'); ?>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="fnx_gsheet_webhook_url"><?php _e('Google Sheets Webhook URL', 'foundnxt'); ?></label></th>
                            <td>
                                <input type="url" name="fnx_gsheet_webhook_url" id="fnx_gsheet_webhook_url" value="<?php echo esc_attr($current_url); ?>" class="large-text" placeholder="https://script.google.com/macros/s/AKfycbx.../exec" style="min-height: 40px; font-family: monospace;">
                                <p class="description">
                                    <?php _e('Enter the Web App URL generated from your Google Apps Script deployment.', 'foundnxt'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-top: 18px;">
                    <button type="submit" name="fnx_save_gsheet_settings" class="button button-primary button-large">
                        <?php _e('Save Webhook URL', 'foundnxt'); ?>
                    </button>
                    <?php if (! empty($current_url)): ?>
                    <button type="submit" name="fnx_test_newsletter" class="button button-secondary button-large">
                        <span class="dashicons dashicons-email" style="vertical-align: middle; margin-top: -2px;"></span> <?php _e('Test "newsletter" Tab', 'foundnxt'); ?>
                    </button>
                    <button type="submit" name="fnx_test_contact" class="button button-secondary button-large">
                        <span class="dashicons dashicons-id-alt" style="vertical-align: middle; margin-top: -2px;"></span> <?php _e('Test "contact" Tab', 'foundnxt'); ?>
                    </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Step 2: Spreadsheet Structure & Instructions -->
        <div style="background: #ffffff; border: 1px solid #ccd0d4; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px;">
            <h2 style="margin-top: 0; font-size: 1.25rem;"><?php _e('2. Setup Instructions for 2-Tab Sheet', 'foundnxt'); ?></h2>
            <p style="color: #475569; font-size: 0.95rem;">
                Your Google Spreadsheet contains two tabs at the bottom: <code>newsletter</code> and <code>contact</code>.
            </p>
            <ol style="line-height: 1.8; font-size: 0.95rem; color: #1e293b; padding-left: 20px;">
                <li>In your Google Sheet with the <strong>newsletter</strong> and <strong>contact</strong> tabs, click <strong>Extensions &gt; Apps Script</strong>.</li>
                <li>Delete any code in the script editor, and paste the code block below in Section 3.</li>
                <li>In the top right, click <strong>Deploy &gt; New deployment</strong>.</li>
                <li>Click the gear icon (Select type) and choose <strong>Web app</strong>.</li>
                <li>Set <strong>Execute as:</strong> <em>Me (your Google account)</em> and <strong>Who has access:</strong> <em>Anyone</em>.</li>
                <li>Click <strong>Deploy</strong>, grant permission when prompted, and copy the <strong>Web app URL</strong>.</li>
                <li>Paste the URL in Step 1 above, click <strong>Save Webhook URL</strong>, and click the test buttons!</li>
            </ol>

            <div style="background: #f8fafc; border-left: 4px solid #FF5A2C; padding: 12px 16px; margin-top: 14px; border-radius: 4px;">
                <p style="margin: 0; font-size: 0.875rem; color: #334155;">
                    💡 <strong>Smart Column Detection:</strong> If your <code>newsletter</code> tab already has column headers like <code>Name</code> and <code>Email</code>, the script dynamically matches them and appends rows into your exact columns!
                </p>
            </div>
        </div>

        <!-- Step 3: Google Apps Script Code -->
        <div style="background: #ffffff; border: 1px solid #ccd0d4; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="margin: 0; font-size: 1.25rem;"><?php _e('3. Google Apps Script Code (Copy & Paste)', 'foundnxt'); ?></h2>
                <button type="button" class="button button-secondary" id="fnx-copy-code-btn" onclick="fnxCopyAppsScript();">
                    <span class="dashicons dashicons-admin-page" style="vertical-align: middle; margin-top: -2px;"></span> <?php _e('Copy Apps Script Code', 'foundnxt'); ?>
                </button>
            </div>
            <pre id="fnx-apps-script-code" style="background: #0f172a; color: #e2e8f0; padding: 18px; border-radius: 6px; font-size: 13px; line-height: 1.6; overflow-x: auto; font-family: 'JetBrains Mono', Consolas, monospace; user-select: all;">function doPost(e) {
  try {
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    
    // 1. Parse incoming JSON data
    var data = {};
    if (e && e.postData && e.postData.contents) {
      data = JSON.parse(e.postData.contents);
    } else if (e && e.parameter) {
      data = e.parameter;
    }
    
    var timestamp = data.timestamp || new Date().toLocaleString();
    var name      = data.name || "";
    var email     = data.email || "";
    var leadType  = data.lead_type || "";
    var phone     = data.phone || "";
    var company   = data.company || "";
    var stage     = data.stage || "";
    var help      = data.help || "";
    var message   = data.message || "";
    var source    = data.source || "";
    var target    = (data.target_sheet || "").toLowerCase().trim();
    
    // 2. Determine target sheet: 'contact' or 'newsletter'
    var isContact = (target === "contact") ||
                    (leadType.toLowerCase().indexOf("contact") !== -1) ||
                    (message && message.length > 0) ||
                    (phone && phone.length > 0) ||
                    (company && company.length > 0) ||
                    (help && help.length > 0 && help !== "General Enquiry");
    
    var targetSheetName = isContact ? "contact" : "newsletter";
    
    // 3. Find sheet tab (case-insensitive)
    var sheet = ss.getSheetByName(targetSheetName);
    if (!sheet) {
      var allSheets = ss.getSheets();
      for (var i = 0; i < allSheets.length; i++) {
        if (allSheets[i].getName().toLowerCase().trim() === targetSheetName) {
          sheet = allSheets[i];
          break;
        }
      }
    }
    
    // Create sheet if it does not exist
    if (!sheet) {
      sheet = ss.insertSheet(targetSheetName);
    }
    
    // 4. Auto-create headers if sheet is empty
    if (sheet.getLastRow() === 0) {
      if (targetSheetName === "newsletter") {
        sheet.appendRow(["Timestamp", "Name", "Email", "Lead Type", "Source Page"]);
        sheet.getRange(1, 1, 1, 5)
          .setFontWeight("bold")
          .setBackground("#FF5A2C")
          .setFontColor("#ffffff");
        sheet.setFrozenRows(1);
      } else {
        sheet.appendRow([
          "Timestamp",
          "Name",
          "Email",
          "Phone / Mobile",
          "Company / Website",
          "Company Stage",
          "Help Topic",
          "Message",
          "Source Page"
        ]);
        sheet.getRange(1, 1, 1, 9)
          .setFontWeight("bold")
          .setBackground("#00d4aa")
          .setFontColor("#0f172a");
        sheet.setFrozenRows(1);
      }
    }
    
    // 5. Match columns dynamically if headers exist
    var lastCol = sheet.getLastColumn();
    var headers = [];
    if (lastCol > 0 && sheet.getLastRow() >= 1) {
      headers = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
    }
    
    if (headers && headers.length > 0) {
      var row = [];
      for (var c = 0; c < headers.length; c++) {
        var h = String(headers[c]).toLowerCase().trim();
        if (h === "name" || h === "full name" || h === "your name") {
          row.push(name);
        } else if (h === "email" || h === "work email" || h === "email address") {
          row.push(email);
        } else if (h === "timestamp" || h === "date" || h === "time" || h === "date & time") {
          row.push(timestamp);
        } else if (h === "phone" || h === "mobile" || h === "phone number" || h === "phone / mobile") {
          row.push(phone);
        } else if (h === "company" || h === "website" || h === "company / website") {
          row.push(company);
        } else if (h === "stage" || h === "company stage" || h === "size") {
          row.push(stage);
        } else if (h === "help" || h === "topic" || h === "help topic" || h === "service") {
          row.push(help);
        } else if (h === "message" || h === "inquiry" || h === "notes") {
          row.push(message);
        } else if (h === "source" || h === "source page" || h === "page" || h === "url") {
          row.push(source);
        } else if (h === "lead type" || h === "type" || h === "form") {
          row.push(leadType);
        } else {
          row.push("");
        }
      }
      sheet.appendRow(row);
    } else {
      if (targetSheetName === "newsletter") {
        sheet.appendRow([timestamp, name, email, leadType || "Newsletter Signup", source]);
      } else {
        sheet.appendRow([timestamp, name, email, phone, company, stage, help, message, source]);
      }
    }
    
    return ContentService
      .createTextOutput(JSON.stringify({
        status: "success",
        sheet: sheet.getName(),
        message: "Saved to " + sheet.getName() + " tab successfully"
      }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (err) {
    return ContentService
      .createTextOutput(JSON.stringify({ status: "error", message: err.toString() }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}</pre>
        </div>
    </div>
    <script>
    function fnxCopyAppsScript() {
        var code = document.getElementById('fnx-apps-script-code').innerText;
        navigator.clipboard.writeText(code).then(function() {
            var btn = document.getElementById('fnx-copy-code-btn');
            var origText = btn.innerHTML;
            btn.innerHTML = '✔ Copied to Clipboard!';
            btn.style.color = '#10b981';
            setTimeout(function() {
                btn.innerHTML = origText;
                btn.style.color = '';
            }, 3000);
        });
    }
    </script>
    <?php
}





/* ============================================================
   FOUNDNXT V2 — AUTOMATIC BOOTSTRAP INITIALIZER
   Configures categories, draft starter posts, page templates,
   and site options automatically on theme load.
   ============================================================ */
function fnx_auto_setup_v2() {
    if (get_option('fnx_v2_setup_completed_v4')) return;

    // 1. Site Options
    update_option('blogname', 'FoundNXT');
    update_option('blogdescription', 'Business, Markets & Technology: Explained for Founders and Leaders.');
    update_option('permalink_structure', '/%category%/%postname%/');

    // 2. Configure 8 Categories
    $categories_data = [
        [
            'name' => 'Business & Strategy',
            'slug' => 'business-strategy',
            'desc' => 'Actionable business strategy, SaaS models, scaling frameworks, and corporate decision-making for founders and executives.'
        ],
        [
            'name' => 'Startups & Funding',
            'slug' => 'startups-funding',
            'desc' => 'Fundraising tactics, pitch decks, venture capital, angel investing, and early-stage startup growth playbooks.'
        ],
        [
            'name' => 'Valuation & Finance',
            'slug' => 'valuation-finance',
            'desc' => 'Startup valuation methods, financial modeling, cap tables, cash flow management, and M&A guides.'
        ],
        [
            'name' => 'Markets & Economy',
            'slug' => 'markets-economy',
            'desc' => 'Macroeconomic trends, sector growth analysis, interest rate impacts, and global financial market insights.'
        ],
        [
            'name' => 'Technology & AI',
            'slug' => 'technology-ai',
            'desc' => 'Enterprise AI adoption, software architecture, tech stack decisions, build vs. buy, and emerging tech.'
        ],
        [
            'name' => 'Marketing & Growth',
            'slug' => 'marketing-growth',
            'desc' => 'Customer acquisition, SEO, product-led growth, brand positioning, and go-to-market strategies.'
        ],
        [
            'name' => 'Global Business',
            'slug' => 'global-business',
            'desc' => 'International expansion, supply chain logistics, global trade policies, cross-border operations, and foreign markets.'
        ],
        [
            'name' => 'News & Insights',
            'slug' => 'news-insights',
            'desc' => 'Timely business analysis, weekly market roundups, industry news, and founder intelligence.'
        ],
    ];

    $cat_ids = [];
    foreach ($categories_data as $c) {
        $term = get_term_by('slug', $c['slug'], 'category');
        if (! $term) {
            $term_by_name = get_term_by('name', $c['name'], 'category');
            if ($term_by_name) {
                wp_update_term($term_by_name->term_id, 'category', ['slug' => $c['slug'], 'description' => $c['desc']]);
                $cat_ids[$c['slug']] = $term_by_name->term_id;
            } else {
                $new_term = wp_insert_term($c['name'], 'category', ['slug' => $c['slug'], 'description' => $c['desc']]);
                if (! is_wp_error($new_term)) {
                    $cat_ids[$c['slug']] = $new_term['term_id'];
                }
            }
        } else {
            wp_update_term($term->term_id, 'category', ['name' => $c['name'], 'description' => $c['desc']]);
            $cat_ids[$c['slug']] = $term->term_id;
        }
    }

    if (isset($cat_ids['news-insights'])) {
        update_option('default_category', $cat_ids['news-insights']);
    }

    // 3. Pages Setup
    $pages_data = [
        'About' => [
            'slug'     => 'about',
            'template' => 'page-about.php',
            'content'  => 'FoundNXT is an independent business and technology publication helping founders and leaders understand markets, technology, and growth.'
        ],
        'Services' => [
            'slug'     => 'services',
            'template' => 'page-services.php',
            'content'  => 'FoundNXT provides market research requests, valuation guidance, tech & AI strategy, marketing help, and partnerships.'
        ],
        'Contact' => [
            'slug'     => 'contact',
            'template' => 'page-contact.php',
            'content'  => 'Get in touch for story tips, advisory enquiries, press releases, or partnership requests.'
        ],
        'Articles' => [
            'slug'     => 'articles',
            'template' => 'page-articles.php',
            'content'  => 'Browse all FoundNXT articles across business strategy, startups, valuation, markets, technology, marketing, and global business.'
        ],
        'Tools' => [
            'slug'     => 'tools',
            'template' => 'page-tools.php',
            'content'  => 'Curated business software and tech recommendations for scaling startups, featuring top open-source alternatives and enterprise SaaS solutions.'
        ],
        'Privacy Policy' => [
            'slug'     => 'privacy-policy',
            'template' => 'page-privacy-policy.php',
            'content'  => 'FoundNXT Privacy Policy detailing data collection, cookie usage, user rights, and security standards.'
        ],
        'Disclaimer' => [
            'slug'     => 'disclaimer',
            'template' => 'page-disclaimer.php',
            'content'  => 'FoundNXT Editorial, Financial, and Legal Disclaimer for website content and tools.'
        ],
        'Support' => [
            'slug'     => 'support',
            'template' => 'page-support.php',
            'content'  => 'FoundNXT Support & Help Center for subscriber enquiries, advisory support, and FAQs.'
        ],
    ];

    foreach ($pages_data as $title => $data) {
        $page = get_page_by_path($data['slug']);
        if (! $page) {
            $page_id = wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $data['slug'],
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
            if ($page_id && ! is_wp_error($page_id) && ! empty($data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $data['template']);
            }
        } else {
            if (! empty($data['template'])) {
                update_post_meta($page->ID, '_wp_page_template', $data['template']);
            }
        }
    }

    // 4. Starter Draft Content Creation
    $starter_posts = [
        [
            'title'    => 'How Are Startups Valued? A Simple Guide',
            'slug'     => 'how-are-startups-valued-simple-guide',
            'category' => 'valuation-finance',
            'content'  => '<h2>Understanding Startup Valuation in 2026</h2><p>Valuing an early-stage company is part quantitative math and part strategic narrative. Unlike established enterprises with years of audited cash flows, pre-revenue or early-stage startups must rely on forward-looking metrics, market opportunity size, and risk-adjusted frameworks.</p><h3>1. The Scorecard Method</h3><p>The Scorecard Valuation Method compares a target startup against benchmarked pre-revenue companies in the same region and sector. Factors include team strength (30%), market opportunity (25%), product/technology (15%), competitive environment (10%), and marketing/sales channels (10%).</p><h3>2. Comparable Multiples</h3><p>For revenue-generating startups, valuations are typically computed as a multiple of Annual Recurrent Revenue (ARR). Depending on macroeconomic interest rates and growth speed, median SaaS ARR multiples range between 6x and 12x ARR for companies growing faster than 50% year-over-year.</p>blockquote><p>"Valuation is not what your company is worth today; it is a reflection of what investors believe your execution velocity can yield tomorrow."</p></blockquote><h3>3. Discounted Cash Flow (DCF) for Scaleups</h3><p>Once a company achieves predictable growth and margins, investors construct 5-year DCF models applying a weighted average cost of capital (WACC) discount rate (typically 20-35% for growth startups) to calculate Net Present Value (NPV).</p><p>By understanding these valuation methodologies, founders can negotiate term sheets confidently without giving away excessive equity dilution in early rounds.</p>'
        ],
        [
            'title'    => 'Valuation Methods Explained',
            'slug'     => 'valuation-methods-explained',
            'category' => 'valuation-finance',
            'content'  => '<h2>Comprehensive Overview of Business Valuation Methodologies</h2><p>Determining business valuation accurately is essential for fundraising, M&A acquisitions, equity incentive grants, and financial planning. Here is an actionable breakdown of the top valuation methods used by founders and advisors.</p><h3>1. Berkus Method</h3><p>Developed by Dave Berkus, this framework assigns up to $500,000 in value for each of five key risk-reduction milestones: Sound Idea, Prototype, Quality Management Team, Strategic Relationships, and Product Rollout.</p><h3>2. Cost-to-Duplicate</h3><p>This asset-based approach calculates the physical and sweat-equity cost required to recreate the company’s software, infrastructure, and operations from scratch.</p><h3>3. Venture Capital (VC) Method</h3><p>The VC method works backward from an anticipated exit valuation and the investor’s target ROI to determine current round pre-money valuation.</p><p>Selecting the optimal valuation approach depends heavily on your company’s stage and financial maturity.</p>'
        ],
        [
            'title'    => 'Fastest-Growing Sectors to Watch in 2026',
            'slug'     => 'fastest-growing-sectors-to-watch-in-2026',
            'category' => 'markets-economy',
            'content'  => '<h2>High-Growth Industry Sectors Shaping the Market</h2><p>As macroeconomic conditions stabilize, venture capital and private equity are converging on sectors driven by structural technological tailwinds and high margin expansion potential.</p><h3>1. Vertical AI & Workflow Automation</h3><p>Rather than horizontal foundation models, founders building specialized AI workflows for legal, healthcare, accounting, and supply chain logistics are experiencing rapid ARR velocity.</p><h3>2. Energy Transition & Grid Infrastructure</h3><p>With data center power demand surging due to AI compute requirements, clean energy generation, battery storage systems, and smart grid software represent major growth vectors.</p><p>Operating in these high-velocity tailwinds provides strong pricing power and active investor interest.</p>'
        ],
        [
            'title'    => 'How Interest Rates Affect Businesses',
            'slug'     => 'how-interest-rates-affect-businesses',
            'category' => 'markets-economy',
            'content'  => '<h2>The Economic Ripple Effect of Central Bank Interest Rates</h2><p>Interest rate decisions by central banks directly influence corporate borrowing costs, equity asset prices, customer purchasing behavior, and venture capital liquidity.</p><h3>1. Cost of Debt & Working Capital</h3><p>When interest rates rise, bank loans and venture debt become significantly more expensive. Companies must prioritize positive free cash flow over unconstrained top-line growth.</p><h3>2. Impact on Tech Valuation Multiples</h3><p>Higher risk-free benchmark yields increase the discount rate applied to future cash flows. Consequently, high-growth valuation multiples contract, prompting a focus on capital efficiency.</p><p>Prudent business leaders maintain cash reserves equal to 18-24 months of runway to buffer against interest rate volatility.</p>'
        ],
        [
            'title'    => 'How Companies Use AI to Cut Costs',
            'slug'     => 'how-companies-use-ai-to-cut-costs',
            'category' => 'technology-ai',
            'content'  => '<h2>Practical Applications of Artificial Intelligence for Operational Efficiency</h2><p>Artificial Intelligence has shifted from speculative innovation to a primary lever for margin expansion and operating cost reduction across enterprise and SMB organizations.</p><h3>1. Intelligent Customer Support & Ticket Deflection</h3><p>Deploying fine-tuned LLM agents enables companies to resolve 60-70% of tier-1 customer support requests automatically, reducing ticket resolution time from hours to seconds.</p><h3>2. Automated Code Generation & QA Testing</h3><p>Engineering teams utilizing AI coding assistants experience 30-45% faster sprint completion rates while automating regression testing routines.</p><p>By strategic deployment of targeted AI modules, companies boost operating margins while empowering team members for high-value tasks.</p>'
        ],
        [
            'title'    => 'Build vs. Buy: A Founder\'s Guide to Software',
            'slug'     => 'build-vs-buy-founders-guide-to-software',
            'category' => 'technology-ai',
            'content'  => '<h2>Evaluating Core Intellectual Property vs. Commercial Software</h2><p>Every technology leader faces the perpetual dilemma: should we engineer a custom in-house software solution or purchase an existing SaaS platform?</p><h3>1. Identify Core IP vs. Commodity Functions</h3><p>If a software feature represents your primary competitive advantage or proprietary algorithm, build it in-house. If the functionality is commodity infrastructure (e.g. auth, payments, email), buy commercial APIs.</p><h3>2. Total Cost of Ownership (TCO)</h3><p>Building software in-house incurs continuous maintenance, security patching, API updates, and technical debt costs that often exceed initial engineering estimates by 3x-5x over a 3-year horizon.</p><p>Focus engineering talent strictly on core differentiation and leverage best-in-class SaaS platforms for supporting operations.</p>'
        ],
        [
            'title'    => 'SEO Basics for New Businesses',
            'slug'     => 'seo-basics-for-new-businesses',
            'category' => 'marketing-growth',
            'content'  => '<h2>Foundational Search Engine Optimization for Organic Growth</h2><p>Search Engine Optimization (SEO) remains one of the highest-ROI, long-term acquisition channels for digital businesses. Establishing strong SEO technical foundations early accelerates authority building.</p><h3>1. Technical Foundation & Speed</h3><p>Ensure your site loads in under 2 seconds, passes Core Web Vitals, utilizes responsive mobile layouts, and includes structured Schema.org JSON-LD markup for search engines.</p><h3>2. Topical Authority & Keyword Mapping</h3><p>Group content into comprehensive topic clusters around core customer pain points rather than publishing disconnected articles.</p><p>Consistent publication of high-value, well-structured content creates a compounding flywheel of organic customer acquisition over time.</p>'
        ],
        [
            'title'    => 'How to Get Your First 100 Customers',
            'slug'     => 'how-to-get-your-first-100-customers',
            'category' => 'marketing-growth',
            'content'  => '<h2>0-to-1 Customer Acquisition Playbook for Founders</h2><p>Acquiring your initial 100 paying customers requires non-scalable, high-touch founder outreach before transitioning to automated marketing channels.</p><h3>1. Founder-Led Direct Cold Outreach</h3><p>Identify target ICP decision-makers on LinkedIn and send personalized cold emails focused on addressing specific operational friction points rather than pitching product features.</p><h3>2. Niche Community Engagement</h3><p>Actively participate in relevant Slack groups, Subreddits, and industry forums. Provide genuine value and practical advice to establish credibility.</p><p>Once manual acquisition reaches 100 happy customers, leverage customer success case studies to launch scalable paid and organic acquisition campaigns.</p>'
        ],
        [
            'title'    => 'Why Companies Expand to Southeast Asia',
            'slug'     => 'why-companies-expand-to-southeast-asia',
            'category' => 'global-business',
            'content'  => '<h2>Unlocking Strategic Growth in SEA Digital Economies</h2><p>Southeast Asia represents one of the world\'s fastest-growing digital commercial regions, powered by a young, tech-savvy population of over 680 million consumers.</p><h3>1. Rapid Digital Consumer Adoption</h3><p>Countries like Indonesia, Vietnam, the Philippines, and Thailand are experiencing surging digital payment, e-commerce, and fintech adoption rates.</p><h3>2. Strategic Regional Hubs (Singapore & Malaysia)</h3><p>Singapore offers world-class legal frameworks, intellectual property protection, double-taxation treaties, and global venture capital access.</p><p>Adapting product localization, local payment integrations, and regional strategic partnerships is essential for long-term expansion success.</p>'
        ],
        [
            'title'    => 'How Tariffs Affect Global Supply Chains',
            'slug'     => 'how-tariffs-affect-global-supply-chains',
            'category' => 'global-business',
            'content'  => '<h2>Navigating International Trade Policies & Supply Chain Risks</h2><p>Fluctuating international trade tariffs and import duties significantly impact cross-border profit margins, component sourcing cost, and inventory logistics strategies.</p><h3>1. Nearshoring & Multi-Country Sourcing</h3><p>To mitigate single-region tariff exposure, global manufacturing supply chains are diversifying production across secondary hubs in Mexico, Vietnam, India, and Eastern Europe.</p><h3>2. Dynamic Landed-Cost Modeling</h3><p>Companies must evaluate whether tariff cost increases can be passed through to end customers via price adjustments or if product re-engineering is required.</p><p>Proactive supply chain mapping and tariff engineering protect international operations against global trade policy shifts.</p>'
        ],
        [
            'title'    => 'Why Startups Fail and How to Avoid It',
            'slug'     => 'why-startups-fail-and-how-to-avoid-it',
            'category' => 'startups-funding',
            'content'  => '<h2>Analyzing the Top Root Causes of Startup Mortality</h2><p>Studying the post-mortems of hundreds of failed venture-backed and bootstrapped companies reveals recurring structural pitfalls that founders can actively mitigate.</p><h3>1. Premature Scaling Before Product-Market Fit</h3><p>Scaling marketing spend or hiring large sales teams before achieving repeatable sales retention is the single primary cause of early-stage startup failure.</p><h3>2. Running Out of Cash & Mismanaged Runway</h3><p>Failure to maintain strict financial discipline or delaying fundraising efforts until fewer than 6 months of cash runway remain drastically reduces founder leverage.</p><p>By prioritizing unit economics, maintaining lean burn rates, and listening closely to customer feedback, founders significantly increase long-term success probabilities.</p>'
        ],
        [
            'title'    => 'Business Models Explained: SaaS, Marketplace, Subscription',
            'slug'     => 'business-models-explained-saas-marketplace-subscription',
            'category' => 'business-strategy',
            'content'  => '<h2>Deconstructing Modern Monetization & Revenue Frameworks</h2><p>Choosing the appropriate business model dictates your acquisition strategy, pricing structure, gross profit margins, and ultimate enterprise valuation multiples.</p><h3>1. Software-as-a-Service (SaaS)</h3><p>SaaS models deliver software over the internet via recurring monthly or annual subscription fees. Key metrics include ARR, NRR, and LTV:CAC ratios.</p><h3>2. Two-Sided Digital Marketplaces</h3><p>Marketplaces connect buyers and sellers, taking a percentage fee on GMV. Marketplaces benefit from network effects but face initial chicken-and-egg cold-start friction.</p><p>Aligning pricing models with how customers derive tangible value maximizes long-term customer lifetime value and retention.</p>'
        ],
        [
            'title'    => 'This Week in Business: Weekly Roundup (template)',
            'slug'     => 'this-week-in-business-weekly-roundup',
            'category' => 'news-insights',
            'content'  => '<h2>Executive Summary: Top Business, Venture & Tech Stories</h2><p>Welcome to the FoundNXT weekly intelligence digest — bringing you concise, practical analysis of major venture capital investments, economic shifts, and emerging technology breakthroughs.</p><h3>1. Venture Capital & Funding Highlights</h3><p>Early-stage funding rounds surged this week across vertical AI, robotics, and clean energy storage, with Series A investments prioritizing unit economics.</p><h3>2. Macroeconomic Indicators</h3><p>Central bank inflation updates signal stabilizing interest rate trajectories, boosting public market sentiment and encouraging strategic M&A acquisitions.</p><p>Subscribe to the free FoundNXT weekly newsletter to receive this executive intelligence brief directly in your inbox every Monday morning.</p>'
        ],
    ];

    foreach ($starter_posts as $post_data) {
        $existing = get_page_by_path($post_data['slug'], OBJECT, 'post');
        if (! $existing) {
            $cat_slug = $post_data['category'];
            $term_id  = isset($cat_ids[$cat_slug]) ? $cat_ids[$cat_slug] : null;

            $post_id = wp_insert_post([
                'post_title'   => $post_data['title'],
                'post_name'    => $post_data['slug'],
                'post_content' => $post_data['content'],
                'post_excerpt' => wp_trim_words(strip_tags($post_data['content']), 28, '...'),
                'post_status'  => 'draft', // Draft per spec!
                'post_type'    => 'post',
            ]);

            if ($post_id && ! is_wp_error($post_id) && $term_id) {
                wp_set_post_categories($post_id, [$term_id]);
            }
        }
    }

    // 5. Reassign existing article "Building Product-Market Fit in 2026"
    $pmf_post = get_page_by_path('building-product-market-fit-in-2026', OBJECT, 'post');
    if ($pmf_post && isset($cat_ids['startups-funding'])) {
        wp_set_post_categories($pmf_post->ID, [$cat_ids['startups-funding']]);
    }

    update_option('fnx_v2_setup_completed_v4', true);
}
add_action('after_setup_theme', 'fnx_auto_setup_v2', 99);
add_action('admin_init',        'fnx_auto_setup_v2', 99);

/**
 * Unified 7-Pillar Primary Categories Source of Truth
 */
function fnx_get_primary_categories() {
    $pillars = [
        [
            'name'  => __('Startups', 'foundnxt'),
            'slug'  => 'startups-funding',
            'url'   => home_url('/category/startups-funding/'),
            'color' => '#FF6B6B',
            'badge' => '01 / TOPIC',
            'icon'  => '🚀',
            'desc'  => __('Fundraising tactics, pitch decks, venture capital, angel investing, and early-stage startup growth playbooks.', 'foundnxt'),
        ],
        [
            'name'  => __('Startup Stories', 'foundnxt'),
            'slug'  => 'startup-stories',
            'url'   => home_url('/category/startup-stories/'),
            'color' => '#7C3AED',
            'badge' => '02 / TOPIC',
            'icon'  => '🔥',
            'desc'  => __('Inside stories, pivot teardowns, and deep profiles of high-momentum Indian and global startups.', 'foundnxt'),
        ],
        [
            'name'  => __('Markets & Valuation', 'foundnxt'),
            'slug'  => 'markets-valuation',
            'url'   => home_url('/category/markets-valuation/'),
            'color' => '#F59E0B',
            'badge' => '03 / TOPIC',
            'icon'  => '📊',
            'desc'  => __('Public market shifts, macroeconomic indicators, 409A multiples, and defensible company valuation models.', 'foundnxt'),
        ],
        [
            'name'  => __('AI & Tech', 'foundnxt'),
            'slug'  => 'ai-tech',
            'url'   => home_url('/category/ai-tech/'),
            'color' => '#14B8A6',
            'badge' => '04 / TOPIC',
            'icon'  => '🤖',
            'desc'  => __('Enterprise AI adoption, LLM agent workflows, developer infrastructure, and software stack evolution.', 'foundnxt'),
        ],
        [
            'name'  => __('Marketing & Growth', 'foundnxt'),
            'slug'  => 'marketing-growth',
            'url'   => home_url('/category/marketing-growth/'),
            'color' => '#F43F5E',
            'badge' => '05 / TOPIC',
            'icon'  => '🎯',
            'desc'  => __('0-to-1 customer acquisition playbooks, topical SEO strategies, brand positioning, and retention loops.', 'foundnxt'),
        ],
        [
            'name'  => __('Global Business', 'foundnxt'),
            'slug'  => 'global-business',
            'url'   => home_url('/category/global-business/'),
            'color' => '#0EA5E9',
            'badge' => '06 / TOPIC',
            'icon'  => '🌍',
            'desc'  => __('Cross-border scaling, international trade dynamics, supply chain optimization, and global market expansion.', 'foundnxt'),
        ],
        [
            'name'  => __('Cost Cutting & Operations', 'foundnxt'),
            'slug'  => 'cost-cutting-operations',
            'url'   => home_url('/category/cost-cutting-operations/'),
            'color' => '#10B981',
            'badge' => '07 / TOPIC',
            'icon'  => '⚡',
            'desc'  => __('Lean operational systems, cloud infrastructure cost reduction, unit economics, and burn rate management.', 'foundnxt'),
        ],
        [
            'name'  => __('Market Research', 'foundnxt'),
            'slug'  => 'market-research',
            'url'   => home_url('/category/market-research/'),
            'color' => '#6366F1',
            'badge' => '08 / TOPIC',
            'icon'  => '🔍',
            'desc'  => __('Proprietary industry datasets, competitor benchmarking, addressable market sizing, and sector trends.', 'foundnxt'),
        ],
        [
            'name'  => __('News Updates', 'foundnxt'),
            'slug'  => 'news-updates',
            'url'   => home_url('/category/news-updates/'),
            'color' => '#FB923C',
            'badge' => '09 / TOPIC',
            'icon'  => '📰',
            'desc'  => __('Fast-moving venture rounds, regulatory shifts, tech announcements, and macroeconomic headlines.', 'foundnxt'),
        ],
    ];

    // Dynamically attach live counts
    foreach ($pillars as &$p) {
        $term = get_term_by('slug', $p['slug'], 'category');
        if ($term && !is_wp_error($term)) {
            $p['count'] = (int) $term->count;
            $p['term_id'] = (int) $term->term_id;
        } else {
            $p['count'] = 0;
            $p['term_id'] = 0;
        }
    }
    unset($p);

    return $pillars;
}

/**
 * Strategic Realignment v2:
 * 1. Ensures canonical categories exist and have articles assigned.
 * 2. Staggers publication dates for posts bulk-published on 2026-10-24.
 * 3. Sets default Instagram URLs for top articles.
 */
function fnx_strategic_realignment_v2() {
    if (get_option('fnx_strategic_realignment_done_v3')) {
        return;
    }

    // 1. Ensure target categories exist
    $target_terms = [
        'technology-ai'     => 'Tech & AI',
        'markets-economy'   => 'Markets & Economy',
        'valuation-finance' => 'Valuation & Finance',
        'marketing-growth'  => 'Marketing & Growth',
        'startups-funding'  => 'Startups & Funding',
        'business-strategy' => 'Scaling & Strategy',
        'founder-playbook'  => 'Founder Playbooks',
    ];

    $term_ids = [];
    foreach ($target_terms as $slug => $name) {
        $term = get_term_by('slug', $slug, 'category');
        if (!$term || is_wp_error($term)) {
            $created = wp_insert_term($name, 'category', ['slug' => $slug]);
            if (!is_wp_error($created)) {
                $term_ids[$slug] = $created['term_id'];
            }
        } else {
            $term_ids[$slug] = $term->term_id;
        }
    }

    // 2. Realignment:
    // a. Map all posts from 'ai' and 'deeptech' into 'technology-ai'
    $ai_terms = get_terms(['taxonomy' => 'category', 'slug' => ['ai', 'deeptech'], 'fields' => 'ids']);
    if (!empty($ai_terms) && isset($term_ids['technology-ai'])) {
        $ai_posts = get_posts(['category__in' => $ai_terms, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids']);
        foreach ($ai_posts as $pid) {
            wp_set_post_categories($pid, [$term_ids['technology-ai']], true);
        }
    }

    // b. Map all posts from 'ipo-markets' into 'markets-economy'
    $ipo_terms = get_terms(['taxonomy' => 'category', 'slug' => ['ipo-markets'], 'fields' => 'ids']);
    if (!empty($ipo_terms) && isset($term_ids['markets-economy'])) {
        $ipo_posts = get_posts(['category__in' => $ipo_terms, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids']);
        foreach ($ipo_posts as $pid) {
            wp_set_post_categories($pid, [$term_ids['markets-economy']], true);
        }
    }

    // c. Ensure Valuation & Finance has 4-5 posts
    if (isset($term_ids['valuation-finance'])) {
        $val_candidates = get_posts([
            's'              => 'valuation',
            'posts_per_page' => 5,
            'post_status'    => 'publish',
            'fields'         => 'ids',
        ]);
        foreach ($val_candidates as $pid) {
            wp_set_post_categories($pid, [$term_ids['valuation-finance']], true);
        }
    }

    // d. Ensure Marketing & Growth has 4-5 posts
    if (isset($term_ids['marketing-growth'])) {
        $growth_slugs = [
            'vernacular-content-startups-next-consumer-wave',
            'building-for-bharat-500m-new-internet-users',
            'small-town-business-ideas-with-big-city-margins',
            'ai-era-business-ideas-you-can-start-solo',
            '15-side-hustles-that-can-grow-into-companies',
        ];
        foreach ($growth_slugs as $g_slug) {
            $g_post = get_page_by_path($g_slug, OBJECT, 'post');
            if ($g_post) {
                wp_set_post_categories($g_post->ID, [$term_ids['marketing-growth']], true);
            }
        }
    }

    // 3. Stagger posts published in bulk on 2026-10-24
    $oct24_posts = get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'date_query'     => [
            ['year' => 2026, 'month' => 10, 'day' => 24],
        ],
        'orderby'        => 'ID',
        'order'          => 'ASC',
    ]);

    if (count($oct24_posts) > 5) {
        $base_time = strtotime('2026-10-24 11:30:00');
        foreach ($oct24_posts as $i => $post_obj) {
            $stagger_seconds = $i * 2 * 86400; // 2 days apart
            $new_time = $base_time - $stagger_seconds;
            $new_date = date('Y-m-d H:i:s', $new_time);

            wp_update_post([
                'ID'            => $post_obj->ID,
                'post_date'     => $new_date,
                'post_date_gmt' => get_gmt_from_date($new_date),
            ]);
        }
    }

    // 4. Default Instagram URLs for top articles
    $top_posts = get_posts(['posts_per_page' => 10, 'post_status' => 'publish']);
    foreach ($top_posts as $tp) {
        if (!get_post_meta($tp->ID, 'fnx_instagram_url', true)) {
            update_post_meta($tp->ID, 'fnx_instagram_url', 'https://instagram.com/foundnxt');
        }
    }

    if (!empty($term_ids)) {
        wp_update_term_count_now(array_values($term_ids), 'category');
    }

    update_option('fnx_strategic_realignment_done_v3', true);
}
add_action('init', 'fnx_strategic_realignment_v2', 99);

/* ============================================================
   SEO ENGINE: ROBOTS.TXT & SITEMAP HYGIENE
   ============================================================ */

/**
 * 1. Enhanced, Search-Engine Compliant robots.txt
 * - Allows Googlebot/Bingbot to crawl CSS, JS, and uploads (vital for mobile-friendly checks).
 * - Disallows search queries (/?s=, /search/) to eliminate duplicate content & crawl bloat.
 * - Disallows technical & sensitive files.
 * - Explicitly lists the canonical XML sitemaps.
 */
function fnx_seo_robots_txt($output, $public) {
    if (!$public) {
        return $output;
    }

    $site_url = home_url();

    $robots  = "User-agent: *\n";
    $robots .= "Disallow: /wp-admin/\n";
    $robots .= "Allow: /wp-admin/admin-ajax.php\n";
    $robots .= "Allow: /wp-content/uploads/\n";
    $robots .= "Allow: /wp-content/themes/\n";
    $robots .= "Allow: /wp-content/plugins/\n";
    $robots .= "Disallow: /?s=\n";
    $robots .= "Disallow: /search/\n";
    $robots .= "Disallow: /trackback/\n";
    $robots .= "Disallow: /xmlrpc.php\n";
    $robots .= "Disallow: /readme.html\n";
    $robots .= "Disallow: /license.txt\n\n";

    // Fast-crawl rules for major search engines
    $robots .= "User-agent: Googlebot\n";
    $robots .= "Allow: /*.js$\n";
    $robots .= "Allow: /*.css$\n";
    $robots .= "Allow: /wp-content/uploads/\n\n";

    $robots .= "User-agent: Bingbot\n";
    $robots .= "Allow: /*.js$\n";
    $robots .= "Allow: /*.css$\n";
    $robots .= "Allow: /wp-content/uploads/\n\n";

    // Canonical XML Sitemaps
    $robots .= "Sitemap: " . esc_url($site_url . '/sitemaps.xml') . "\n";
    $robots .= "Sitemap: " . esc_url($site_url . '/sitemap.xml') . "\n";

    return $robots;
}
add_filter('robots_txt', 'fnx_seo_robots_txt', 999, 2);

/**
 * 2. XML Sitemap Hygiene: Exclude empty terms & uncategorized from sitemaps
 */
add_filter('wp_sitemaps_taxonomies_query_args', function($args, $taxonomy) {
    $args['hide_empty'] = true;
    return $args;
}, 10, 2);

// SiteSEO / SEOPress taxonomy query filter
add_filter('siteseo_sitemaps_terms_query', function($args) {
    $args['hide_empty'] = true;
    return $args;
});
add_filter('seopress_sitemaps_terms_query', function($args) {
    $args['hide_empty'] = true;
    return $args;
});

// Exclude single empty or uncategorized terms from SiteSEO sitemaps
function fnx_filter_sitemap_terms($url, $term) {
    if (!$url) return false;
    if (isset($term->count) && (int) $term->count === 0) {
        return false;
    }
    if (isset($term->slug) && in_array($term->slug, ['uncategorized', 'global-business'], true)) {
        return false;
    }
    return $url;
}
add_filter('siteseo_sitemaps_single_term', 'fnx_filter_sitemap_terms', 10, 2);
add_filter('seopress_sitemaps_single_term', 'fnx_filter_sitemap_terms', 10, 2);

/**
 * 3. Search Engine Indexing Directives (wp_robots)
 * Automatically sets noindex on empty category archives & internal search
 */
add_filter('wp_robots', function($robots) {
    if (is_search() || is_404()) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    } elseif (is_category()) {
        $cat = get_queried_object();
        if ($cat && isset($cat->count) && (int) $cat->count === 0) {
            $robots['noindex'] = true;
            $robots['follow'] = true;
        }
    }
    return $robots;
});

// 4. Clean up legacy empty term 'global-business'
add_action('init', function() {
    $gb = get_term_by('slug', 'global-business', 'category');
    if ($gb && !is_wp_error($gb) && (int) $gb->count === 0) {
        wp_delete_term($gb->term_id, 'category');
    }
}, 99);


/**
 * Auto-populate V2 Categories with articles & setup Waitlist handler
 */
add_action('init', function() {
    if (!get_option('fnx_v2_categories_populated')) {
        $mappings = [
            'startup-stories'         => ['case-studies-teardowns', 'startups-funding'],
            'markets-valuation'       => ['valuation-finance', 'markets-economy'],
            'ai-tech'                 => ['technology-ai', 'ai', 'deeptech'],
            'news-updates'            => ['news-insights'],
        ];

        foreach ($mappings as $target_slug => $source_slugs) {
            $target_term = get_term_by('slug', $target_slug, 'category');
            if ($target_term && !is_wp_error($target_term)) {
                $target_id = $target_term->term_id;
                foreach ($source_slugs as $src) {
                    $src_term = get_term_by('slug', $src, 'category');
                    if ($src_term && !is_wp_error($src_term)) {
                        $posts = get_posts([
                            'category'    => $src_term->term_id,
                            'numberposts' => -1,
                            'fields'      => 'ids',
                        ]);
                        foreach ($posts as $pid) {
                            wp_set_post_categories($pid, [$target_id], true);
                        }
                    }
                }
            }
        }

        // Assign sample posts to global-business, cost-cutting-operations, market-research
        $extra_slugs = ['global-business', 'cost-cutting-operations', 'market-research'];
        $recent = get_posts(['numberposts' => 18, 'fields' => 'ids']);
        if (!empty($recent)) {
            $chunks = array_chunk($recent, 6);
            foreach ($extra_slugs as $idx => $eslug) {
                if (isset($chunks[$idx])) {
                    $t = get_term_by('slug', $eslug, 'category');
                    if ($t && !is_wp_error($t)) {
                        foreach ($chunks[$idx] as $pid) {
                            wp_set_post_categories($pid, [$t->term_id], true);
                        }
                    }
                }
            }
        }

        update_option('fnx_v2_categories_populated', 1);
    }
});

/**
 * Handle Waitlist Submission (Market Research & Valuation Services)
 */
function fnx_handle_waitlist_submission() {
    if (!isset($_POST['fnx_waitlist_nonce']) || !wp_verify_nonce($_POST['fnx_waitlist_nonce'], 'fnx_waitlist_action')) {
        wp_die(__('Security check failed. Please refresh and try again.', 'foundnxt'));
    }

    $email = isset($_POST['waitlist_email']) ? sanitize_email(wp_unslash($_POST['waitlist_email'])) : '';
    $service = isset($_POST['waitlist_service']) ? sanitize_text_field(wp_unslash($_POST['waitlist_service'])) : 'Market Research & Valuation';

    if (empty($email) || !is_email($email)) {
        wp_safe_redirect(add_query_arg(['waitlist' => 'invalid'], wp_get_referer() ?: home_url('/')));
        exit;
    }

    // Save lead
    $post_id = wp_insert_post([
        'post_type'   => 'fnx_lead',
        'post_title'  => sprintf('Waitlist: %s (%s)', $email, $service),
        'post_status' => 'publish',
    ]);

    if ($post_id && !is_wp_error($post_id)) {
        update_post_meta($post_id, '_fnx_lead_email', $email);
        update_post_meta($post_id, '_fnx_lead_type', 'Service Waitlist');
        update_post_meta($post_id, '_fnx_lead_service', $service);
        update_post_meta($post_id, '_fnx_lead_submitted_at', current_time('mysql'));
    }

    // Send Admin Notification
    $admin_email = get_option('admin_email');
    $subject = sprintf('[FoundNXT] New Waitlist Signup: %s', $service);
    $body = "A new user joined the waitlist for {$service}!

Email: {$email}
Date: " . current_time('mysql');
    @wp_mail($admin_email, $subject, $body);

    wp_safe_redirect(add_query_arg(['waitlist' => 'success'], wp_get_referer() ?: home_url('/')) . '#waitlist');
    exit;
}
add_action('admin_post_fnx_waitlist_submit', 'fnx_handle_waitlist_submission');
add_action('admin_post_nopriv_fnx_waitlist_submit', 'fnx_handle_waitlist_submission');
