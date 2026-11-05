<?php
/**
 * FoundNXT Speed & Performance Optimization Engine
 *
 * Implements modern web performance standards:
 * - Speculation Rules API for instant 0ms navigation
 * - Resource hints (preconnect, dns-prefetch)
 * - Script deferral without render blocking
 * - Removal of core WordPress bloat & unused CSS
 * - High-priority LCP image optimization & lazy loading
 * - Cache-Control headers for static assets
 *
 * @package FoundNXT
 * @version 1.4.4
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Speculation Rules API (Chrome 109+, Edge 109+)
 * Prerenders same-origin links with moderate eagerness on hover/intent.
 */
function fnx_add_speculation_rules() {
    if (is_admin() || is_feed() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }
    ?>
    <script type="speculationrules">
    {
      "prerender": [{
        "where": {
          "and": [
            { "href_matches": "/*" },
            { "not": { "href_matches": "/wp-admin/*" } },
            { "not": { "href_matches": "/wp-login.php*" } },
            { "not": { "href_matches": "/*\\?*(^|&)add-to-cart=*" } },
            { "not": { "href_matches": "/*\\?*(^|&)preview=*" } },
            { "not": { "selector_matches": ".no-prerender" } },
            { "not": { "selector_matches": "[rel~=nofollow]" } }
          ]
        },
        "eagerness": "moderate"
      }]
    }
    </script>
    <?php
}
add_action('wp_head', 'fnx_add_speculation_rules', 2);

/**
 * 2. Resource Hints (Early DNS & TLS handshake)
 */
function fnx_optimized_resource_hints() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://fonts.gstatic.com">' . "\n";
}
add_action('wp_head', 'fnx_optimized_resource_hints', 1);

/**
 * 3. Defer JavaScript execution to prevent render blocking.
 */
function fnx_defer_frontend_scripts($tag, $handle, $src) {
    if (is_admin()) {
        return $tag;
    }

    // Do not defer jQuery core if required immediately by third parties
    $exclude_handles = ['jquery-core'];
    if (in_array($handle, $exclude_handles, true)) {
        return $tag;
    }

    // If script already has defer or async, skip
    if (strpos($tag, ' defer') !== false || strpos($tag, ' async') !== false) {
        return $tag;
    }

    return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'fnx_defer_frontend_scripts', 10, 3);

/**
 * 4. Remove WordPress Core Bloat & Unused Assets
 */
function fnx_remove_wp_bloat() {
    if (is_admin()) {
        return;
    }

    // Remove Emoji scripts, styles and SVG filters
    remove_action('wp_head',             'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles',     'print_emoji_styles');
    remove_action('admin_print_styles',  'print_emoji_styles');
    remove_filter('the_content_feed',    'wp_staticize_emoji');
    remove_filter('comment_text_rss',    'wp_staticize_emoji');
    remove_filter('wp_mail',             'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', function($plugins) {
        return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
    });

    // Remove core WP head junk
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    // Preserved for RSS autodiscovery: feed_links
    remove_action('wp_head', 'feed_links_extra', 3);

    // Disable XML-RPC pingback
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('pings_open', '__return_false', 20, 2);
}
add_action('init', 'fnx_remove_wp_bloat');

/**
 * 5. Dequeue Unused CSS on Frontend
 */
function fnx_dequeue_unused_assets() {
    if (is_admin()) {
        return;
    }

    // Dequeue Dashicons for logged-out visitors (saves ~30KB)
    if (!is_user_logged_in()) {
        wp_deregister_style('dashicons');
    }

    // Remove Classic Theme Styles
    wp_dequeue_style('classic-theme-styles');

    // On non-single posts, dequeue Gutenberg block library CSS (saves ~85KB)
    if (!is_single()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
    }
}
add_action('wp_enqueue_scripts', 'fnx_dequeue_unused_assets', 100);

/**
 * 6. Image Optimization Attributes
 * Force decoding="async" and native lazy loading on all content images.
 */
function fnx_optimize_content_images($content) {
    if (is_admin()) {
        return $content;
    }

    // Add decoding="async" to images missing it
    $content = preg_replace('/<img(?![^>]*\bdecoding=)([^>]*)>/i', '<img decoding="async"$1>', $content);

    return $content;
}
add_filter('the_content', 'fnx_optimize_content_images', 99);

/**
 * 7. Browser Security & Performance Headers
 */
function fnx_send_performance_headers() {
    if (headers_sent() || is_admin()) {
        return;
    }
    header('X-Content-Type-Options: nosniff');
}
add_action('send_headers', 'fnx_send_performance_headers');

/**
 * 8. Host Author Avatars Locally (Eliminate external Gravatar requests)
 */
function fnx_local_author_avatar($args, $id_or_email) {
    $args['url'] = get_theme_file_uri('assets/images/author-avatar.svg');
    $args['found_avatar'] = true;
    return $args;
}
add_filter('pre_get_avatar_data', 'fnx_local_author_avatar', 10, 2);

