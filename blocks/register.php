<?php
/**
 * FoundNXT Custom Blocks — Registration
 * @package FoundNXT
 */
defined('ABSPATH') || exit;

// Register block category
add_filter('block_categories_all', function($cats) {
    array_unshift($cats, [
        'slug'  => 'foundnxt',
        'title' => 'FoundNXT',
        'icon'  => null,
    ]);
    return $cats;
});

// Register editor script handle BEFORE blocks
add_action('init', function() {
    wp_register_script(
        'fnx-blocks-editor',
        FNX_URI . '/blocks/editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'],
        FNX_VERSION,
        true
    );
    wp_register_style(
        'fnx-blocks-frontend',
        FNX_URI . '/blocks/frontend.css',
        [],
        FNX_VERSION
    );
}, 5);

// Register all blocks
add_action('init', function() {
    $blocks = [
        'note','takeaway','pros-cons','stats','steps','faq','pullquote',
        'checklist','verdict','timeline','feature-cards','definition',
        'lead-paragraph','highlight','risk-banner','section-header',
        'author-bio','big-number','related-reading','cta-box','resource-card',
    ];
    foreach ($blocks as $block) {
        $dir = FNX_DIR . '/blocks/src/' . $block;
        if (file_exists($dir . '/block.json')) {
            register_block_type($dir, [
                'editor_script' => 'fnx-blocks-editor',
                'style'         => 'fnx-blocks-frontend',
            ]);
        }
    }
}, 10);

// Enqueue editor styles
add_action('enqueue_block_editor_assets', function() {
    wp_enqueue_style(
        'fnx-blocks-editor-style',
        FNX_URI . '/assets/css/editor.css',
        ['wp-edit-blocks'],
        FNX_VERSION
    );
});
