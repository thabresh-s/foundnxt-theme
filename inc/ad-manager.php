<?php
/**
 * FoundNXT Google AdSense & Monetization Manager
 * High-RPM ad layouts, automated in-article injection, sticky sidebar units,
 * CLS-prevention containers, and WordPress Customizer integration.
 *
 * @package FoundNXT
 */

defined('ABSPATH') || exit;

/**
 * Register Google AdSense & Ad Placement Widget Areas
 */
function fnx_register_ad_widget_areas() {
    $ad_sidebars = [
        [
            'name'        => __('Ad: Header Leaderboard (728x90)', 'foundnxt'),
            'id'          => 'fnx-ad-header',
            'description' => __('Top leaderboard ad displayed below the navigation bar.', 'foundnxt'),
        ],
        [
            'name'        => __('Ad: In-Article Top', 'foundnxt'),
            'id'          => 'fnx-ad-article-top',
            'description' => __('Highest-earning placement: shown after paragraph 2 of blog posts.', 'foundnxt'),
        ],
        [
            'name'        => __('Ad: In-Article Middle', 'foundnxt'),
            'id'          => 'fnx-ad-article-mid',
            'description' => __('Mid-content ad shown at ~50% read progress on articles.', 'foundnxt'),
        ],
        [
            'name'        => __('Ad: In-Article Bottom', 'foundnxt'),
            'id'          => 'fnx-ad-article-bottom',
            'description' => __('End of article ad shown before author card and comments.', 'foundnxt'),
        ],
        [
            'name'        => __('Ad: Sticky Sidebar (300x250 / 300x600)', 'foundnxt'),
            'id'          => 'fnx-ad-sidebar-sticky',
            'description' => __('High-viewability sticky ad in the article sidebar that follows scrolling.', 'foundnxt'),
        ],
        [
            'name'        => __('Ad: In-Feed Grid Card', 'foundnxt'),
            'id'          => 'fnx-ad-infeed',
            'description' => __('Native-style ad placed between article cards on homepage and archive grids.', 'foundnxt'),
        ],
        [
            'name'        => __('Ad: Above Footer Banner', 'foundnxt'),
            'id'          => 'fnx-ad-footer',
            'description' => __('Full-width banner ad placed right above the site footer.', 'foundnxt'),
        ],
    ];

    foreach ($ad_sidebars as $ad_area) {
        register_sidebar([
            'name'          => $ad_area['name'],
            'id'            => $ad_area['id'],
            'description'   => $ad_area['description'],
            'before_widget' => '<div class="fnx-ad-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<span class="sr-only">',
            'after_title'   => '</span>',
        ]);
    }
}
add_action('widgets_init', 'fnx_register_ad_widget_areas');

/**
 * Register AdSense & Monetization Settings in WordPress Customizer
 */
function fnx_customize_ads_register($wp_customize) {
    // AdSense Section
    $wp_customize->add_section('fnx_adsense_section', [
        'title'       => __('💰 Google AdSense & Monetization', 'foundnxt'),
        'description' => __('Configure Google AdSense auto-ads, high-RPM placements, and in-article ad slots.', 'foundnxt'),
        'panel'       => 'fnx_panel',
        'priority'    => 22,
    ]);

    // 1. Publisher ID (ca-pub-XXXXXXXXXX)
    $wp_customize->add_setting('fnx_ads_publisher_id', [
        'default'           => 'ca-pub-9252123074089867',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_ads_publisher_id', [
        'label'       => __('Google AdSense Publisher ID', 'foundnxt'),
        'description' => __('Enter your AdSense ID (e.g. ca-pub-1234567890123456). Automatically includes the official AdSense script tag in the header.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'text',
        'priority'    => 10,
    ]);

    // 2. Enable Auto Ads
    $wp_customize->add_setting('fnx_ads_auto_ads', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ads_auto_ads', [
        'label'       => __('Enable Google Auto Ads', 'foundnxt'),
        'description' => __('Let Google automatically optimize and place ads across your site in addition to theme slots.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 12,
    ]);

    // 3. Show "ADVERTISEMENT" disclaimer
    $wp_customize->add_setting('fnx_ads_label', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ads_label', [
        'label'       => __('Show "ADVERTISEMENT" Badge', 'foundnxt'),
        'description' => __('Recommended by Google AdSense policy to clearly distinguish editorial content from ads.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 14,
    ]);

    // 4. Show Placeholders when code is empty (preview mode)
    $wp_customize->add_setting('fnx_ads_placeholders', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ads_placeholders', [
        'label'       => __('Show Visual Ad Slot Placeholders', 'foundnxt'),
        'description' => __('Display sample ad boxes on the site so you can visualize positions before pasting real AdSense tags.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 16,
    ]);

    // ── PLACEMENT: Header Leaderboard ──
    $wp_customize->add_setting('fnx_ad_header_enabled', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_header_enabled', [
        'label'       => __('Enable Top Header Leaderboard Ad (728x90)', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 20,
    ]);

    $wp_customize->add_setting('fnx_ad_header_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_header_code', [
        'label'       => __('Header Ad Code / AdSense Snippet', 'foundnxt'),
        'description' => __('Paste your responsive or 728x90 / 320x100 AdSense ad unit code.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 22,
    ]);

    // ── PLACEMENT: In-Article Top (Highest RPM) ──
    $wp_customize->add_setting('fnx_ad_article_top_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_article_top_enabled', [
        'label'       => __('★ Enable In-Article Top Ad (Highest Earning)', 'foundnxt'),
        'description' => __('Automatically injected inside article content after paragraph 2. Consistently delivers highest CTR.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 30,
    ]);

    $wp_customize->add_setting('fnx_ad_article_top_para', [
        'default'           => 2,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fnx_ad_article_top_para', [
        'label'       => __('Insert After Paragraph Number', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'number',
        'input_attrs' => ['min' => 1, 'max' => 6, 'step' => 1],
        'priority'    => 32,
    ]);

    $wp_customize->add_setting('fnx_ad_article_top_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_article_top_code', [
        'label'       => __('In-Article Top Ad Code', 'foundnxt'),
        'description' => __('Recommended: Responsive or In-Article AdSense unit.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 34,
    ]);

    // ── PLACEMENT: In-Article Middle ──
    $wp_customize->add_setting('fnx_ad_article_mid_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_article_mid_enabled', [
        'label'       => __('Enable In-Article Mid-Content Ad', 'foundnxt'),
        'description' => __('Injected at approximately 50% read progress on posts with 5+ paragraphs.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 40,
    ]);

    $wp_customize->add_setting('fnx_ad_article_mid_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_article_mid_code', [
        'label'       => __('In-Article Mid-Content Ad Code', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 42,
    ]);

    // ── PLACEMENT: In-Article Bottom ──
    $wp_customize->add_setting('fnx_ad_article_bottom_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_article_bottom_enabled', [
        'label'       => __('Enable In-Article Bottom Ad', 'foundnxt'),
        'description' => __('Shown directly at the end of article content before the author card.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 50,
    ]);

    $wp_customize->add_setting('fnx_ad_article_bottom_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_article_bottom_code', [
        'label'       => __('In-Article Bottom Ad Code', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 52,
    ]);

    // ── PLACEMENT: Sticky Sidebar Ad ──
    $wp_customize->add_setting('fnx_ad_sidebar_sticky_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_sidebar_sticky_enabled', [
        'label'       => __('★ Enable Sticky Sidebar Ad (300x250 or 300x600)', 'foundnxt'),
        'description' => __('Floats and remains in viewport while the reader scrolls long articles, maximizing Active View viewability.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 60,
    ]);

    $wp_customize->add_setting('fnx_ad_sidebar_sticky_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_sidebar_sticky_code', [
        'label'       => __('Sticky Sidebar Ad Code', 'foundnxt'),
        'description' => __('Recommended: 300x250 Medium Rectangle or 300x600 Half-Page ad unit.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 62,
    ]);

    // ── PLACEMENT: In-Feed Grid Ad ──
    $wp_customize->add_setting('fnx_ad_infeed_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_infeed_enabled', [
        'label'       => __('Enable In-Feed Grid Ad (Homepage & Categories)', 'foundnxt'),
        'description' => __('Blends naturally inside article card grids on blog index and category archive pages.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 70,
    ]);

    $wp_customize->add_setting('fnx_ad_infeed_pos', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fnx_ad_infeed_pos', [
        'label'       => __('In-Feed Position (After Card Number)', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'number',
        'input_attrs' => ['min' => 1, 'max' => 9, 'step' => 1],
        'priority'    => 72,
    ]);

    $wp_customize->add_setting('fnx_ad_infeed_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_infeed_code', [
        'label'       => __('In-Feed Ad Code', 'foundnxt'),
        'description' => __('Recommended: AdSense In-Feed ad unit or responsive display unit.', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 74,
    ]);

    // ── PLACEMENT: Above Footer Banner ──
    $wp_customize->add_setting('fnx_ad_footer_enabled', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_ad_footer_enabled', [
        'label'       => __('Enable Above Footer Banner Ad', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'checkbox',
        'priority'    => 80,
    ]);

    $wp_customize->add_setting('fnx_ad_footer_code', [
        'default'           => '',
        'sanitize_callback' => 'fnx_sanitize_ad_code',
    ]);
    $wp_customize->add_control('fnx_ad_footer_code', [
        'label'       => __('Footer Banner Ad Code', 'foundnxt'),
        'section'     => 'fnx_adsense_section',
        'type'        => 'textarea',
        'priority'    => 82,
    ]);
}
add_action('customize_register', 'fnx_customize_ads_register');

/**
 * Sanitize ad scripts without stripping script and ins tags
 */
function fnx_sanitize_ad_code($input) {
    if (current_user_can('unfiltered_html')) {
        return $input;
    }
    return wp_kses($input, [
        'script' => [
            'async'       => [],
            'src'         => [],
            'crossorigin' => [],
            'type'        => [],
        ],
        'ins'    => [
            'class'                  => [],
            'style'                  => [],
            'data-ad-client'         => [],
            'data-ad-slot'           => [],
            'data-ad-format'         => [],
            'data-full-width-responsive' => [],
            'data-ad-layout'         => [],
            'data-ad-layout-key'     => [],
        ],
        'div'    => [
            'class' => [],
            'id'    => [],
            'style' => [],
        ],
        'span'   => [
            'class' => [],
            'style' => [],
        ],
        'iframe' => [
            'src'         => [],
            'width'       => [],
            'height'      => [],
            'frameborder' => [],
            'scrolling'   => [],
        ],
    ]);
}

/**
 * Inject Google AdSense Tag into <head>
 */
function fnx_adsense_head_script() {
    $pub_id = trim(get_theme_mod('fnx_ads_publisher_id', 'ca-pub-9252123074089867') ?: 'ca-pub-9252123074089867');
    if (empty($pub_id)) {
        return;
    }

    // Standardize client ID
    if (strpos($pub_id, 'ca-pub-') === false) {
        $client_id = 'ca-pub-' . ltrim($pub_id, 'pub-');
    } else {
        $client_id = $pub_id;
    }

    echo "\n<!-- Google AdSense Automated Tag [FoundNXT] -->\n";
    echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . esc_attr($client_id) . '" crossorigin="anonymous"></script>' . "\n";

    if (get_theme_mod('fnx_ads_auto_ads', false)) {
        echo '<script>' . "\n";
        echo '  (adsbygoogle = window.adsbygoogle || []).push({' . "\n";
        echo '    google_ad_client: "' . esc_js($client_id) . '",' . "\n";
        echo '    enable_page_level_ads: true' . "\n";
        echo '  });' . "\n";
        echo '</script>' . "\n";
    }
}
add_action('wp_head', 'fnx_adsense_head_script', 1);

/**
 * Determine if ads should be displayed on the current page
 */
function fnx_can_display_ads() {
    if (is_404() || is_feed()) {
        return false;
    }
    if (is_singular()) {
        $disabled = get_post_meta(get_the_ID(), '_fnx_disable_ads', true);
        if ($disabled) {
            return false;
        }
    }
    return true;
}

/**
 * Check if a specific ad placement is active
 */
function fnx_has_ad($slot) {
    if (!fnx_can_display_ads()) {
        return false;
    }

    $widget_id = 'fnx-ad-' . str_replace('_', '-', $slot);
    if (is_active_sidebar($widget_id)) {
        return true;
    }

    $enabled = get_theme_mod("fnx_ad_{$slot}_enabled", false);
    $code    = trim(get_theme_mod("fnx_ad_{$slot}_code", ''));
    $preview = get_theme_mod('fnx_ads_placeholders', false);

    return ($enabled && (!empty($code) || $preview || is_customize_preview()));
}

/**
 * Return in-feed ad insertion position (after card N)
 */
function fnx_get_ad_infeed_position() {
    return (int) get_theme_mod('fnx_ad_infeed_pos', 3);
}

/**
 * Render an ad block
 */
function fnx_render_ad($slot, $extra_class = '') {
    if (!fnx_can_display_ads()) {
        return;
    }

    $widget_id = 'fnx-ad-' . str_replace('_', '-', $slot);
    $has_widget = is_active_sidebar($widget_id);
    $enabled    = get_theme_mod("fnx_ad_{$slot}_enabled", false);
    $code       = trim(get_theme_mod("fnx_ad_{$slot}_code", ''));
    $preview    = get_theme_mod('fnx_ads_placeholders', false) || is_customize_preview();
    $show_label = get_theme_mod('fnx_ads_label', true);

    if (!$has_widget && !$enabled && !$preview) {
        return;
    }

    if (!$has_widget && empty($code) && !$preview) {
        return;
    }

    $slot_labels = [
        'header'         => __('Leaderboard 728x90', 'foundnxt'),
        'article_top'    => __('In-Article Top (High CTR)', 'foundnxt'),
        'article_mid'    => __('In-Article Mid-Content', 'foundnxt'),
        'article_bottom' => __('In-Article End', 'foundnxt'),
        'sidebar_sticky' => __('Sticky Sidebar 300x250 / 300x600', 'foundnxt'),
        'infeed'         => __('In-Feed Native Ad', 'foundnxt'),
        'footer'         => __('Footer Banner 728x90', 'foundnxt'),
    ];

    $label_desc = $slot_labels[$slot] ?? strtoupper($slot);
    ?>
    <div class="fnx-ad-wrap fnx-ad-<?php echo esc_attr($slot); ?> <?php echo esc_attr($extra_class); ?>">
      <div class="fnx-ad-inner">
        <?php if ($show_label): ?>
          <span class="fnx-ad-disclaimer" aria-hidden="true"><?php _e('ADVERTISEMENT', 'foundnxt'); ?></span>
        <?php endif; ?>

        <div class="fnx-ad-slot-box">
          <?php
          if ($has_widget) {
              dynamic_sidebar($widget_id);
          } elseif (!empty($code)) {
              echo $code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
          } elseif ($preview) {
              ?>
              <div class="fnx-ad-placeholder">
                <div class="fnx-ad-ph-icon">📢</div>
                <div class="fnx-ad-ph-title"><?php echo esc_html($label_desc); ?></div>
                <div class="fnx-ad-ph-sub"><?php _e('Configure ad unit code in Appearance → Customize → Google AdSense & Monetization', 'foundnxt'); ?></div>
              </div>
              <?php
          }
          ?>
        </div>
      </div>
    </div>
    <?php
}

/**
 * Filter the_content to inject In-Article Ads at optimal earning positions
 */
function fnx_inject_in_article_ads($content) {
    if (!is_single() || !is_main_query() || !in_the_loop()) {
        return $content;
    }

    if (!fnx_can_display_ads()) {
        return $content;
    }

    $top_active = fnx_has_ad('article_top');
    $mid_active = fnx_has_ad('article_mid');

    if (!$top_active && !$mid_active) {
        return $content;
    }

    // Split content by closing paragraph tags
    $paragraphs = explode('</p>', $content);
    $total_p    = count($paragraphs) - 1; // last entry is empty or after last </p>

    if ($total_p < 2) {
        return $content;
    }

    $top_pos = (int) get_theme_mod('fnx_ad_article_top_para', 2);
    if ($top_pos < 1) $top_pos = 1;
    if ($top_pos > $total_p) $top_pos = $total_p;

    // Calculate mid position (at 50% of article, only if post has at least 5 paragraphs)
    $mid_pos = ($total_p >= 5) ? (int) floor($total_p / 2) : 0;
    if ($mid_pos === $top_pos) {
        $mid_pos = $top_pos + 2;
    }

    ob_start();
    fnx_render_ad('article_top', 'fnx-in-article-ad fnx-in-article-top');
    $top_ad_html = ob_get_clean();

    ob_start();
    fnx_render_ad('article_mid', 'fnx-in-article-ad fnx-in-article-mid');
    $mid_ad_html = ob_get_clean();

    $new_content = '';
    for ($i = 0; $i < count($paragraphs); $i++) {
        $new_content .= $paragraphs[$i];
        if ($i < $total_p) {
            $new_content .= '</p>';
        }

        // Insert Top Ad
        if ($top_active && ($i + 1) === $top_pos) {
            $new_content .= $top_ad_html;
        }

        // Insert Mid Ad
        if ($mid_active && $mid_pos > 0 && ($i + 1) === $mid_pos) {
            $new_content .= $mid_ad_html;
        }
    }

    return $new_content;
}
add_filter('the_content', 'fnx_inject_in_article_ads', 20);

/**
 * Add Meta Box in Post Editor to disable ads on specific articles if desired
 */
function fnx_add_ads_meta_box() {
    add_meta_box(
        'fnx_ads_control',
        __('Monetization & Ads', 'foundnxt'),
        'fnx_render_ads_meta_box',
        ['post', 'page'],
        'side',
        'low'
    );
}
add_action('add_meta_boxes', 'fnx_add_ads_meta_box');

function fnx_render_ads_meta_box($post) {
    wp_nonce_field('fnx_save_ads_meta', 'fnx_ads_meta_nonce');
    $disabled = get_post_meta($post->ID, '_fnx_disable_ads', true);
    ?>
    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
      <input type="checkbox" name="fnx_disable_ads" value="1" <?php checked($disabled, '1'); ?>>
      <span><?php _e('Disable all ads on this post/page', 'foundnxt'); ?></span>
    </label>
    <p style="font-size:11px;color:#666;margin:6px 0 0;line-height:1.4;">
      <?php _e('Useful for sponsored content, legal terms, or dedicated landing pages.', 'foundnxt'); ?>
    </p>
    <?php
}

function fnx_save_ads_meta_box($post_id) {
    if (!isset($_POST['fnx_ads_meta_nonce']) || !wp_verify_nonce($_POST['fnx_ads_meta_nonce'], 'fnx_save_ads_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!empty($_POST['fnx_disable_ads'])) {
        update_post_meta($post_id, '_fnx_disable_ads', '1');
    } else {
        delete_post_meta($post_id, '_fnx_disable_ads');
    }
}
add_action('save_post', 'fnx_save_ads_meta_box');
