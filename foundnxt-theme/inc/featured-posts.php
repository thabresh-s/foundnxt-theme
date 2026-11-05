<?php
/**
 * Featured Articles Feature (Homepage Spotlight)
 *
 * Provides querying, customizer controls, post meta box,
 * and admin list columns for selecting 1 or 2 featured posts on the homepage.
 *
 * @package FoundNXT
 * @version 1.4.2
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get 1 or 2 featured posts for the homepage.
 *
 * Hierarchy:
 * 1. Theme Customizer explicit picks (`fnx_featured_post_1`, `fnx_featured_post_2`)
 * 2. Posts marked with post meta `fnx_featured == '1'`
 * 3. Sticky posts
 * 4. Fallback to latest published posts
 *
 * @return WP_Post[] Array containing 1 or 2 post objects.
 */
function fnx_get_featured_homepage_posts() {
    $selected_ids = [];

    // 1. Check Customizer selections
    $p1 = (int) get_theme_mod('fnx_featured_post_1', 0);
    $p2 = (int) get_theme_mod('fnx_featured_post_2', 0);

    // If Customizer settings are explicitly set:
    if ($p1 > 0 && get_post_status($p1) === 'publish') {
        $selected_ids[] = $p1;
    }
    // If p2 is set and not 0 and not identical to p1
    if ($p2 > 0 && $p2 !== $p1 && get_post_status($p2) === 'publish') {
        $selected_ids[] = $p2;
    }

    // 2. If no valid post was found from Customizer, check post meta 'fnx_featured'
    if (empty($selected_ids)) {
        $meta_posts = get_posts([
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 2,
            'meta_key'            => 'fnx_featured',
            'meta_value'          => '1',
            'orderby'             => 'date',
            'order'               => 'DESC',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        ]);
        foreach ($meta_posts as $mp) {
            $selected_ids[] = $mp->ID;
        }
    }

    // 3. If still empty, check sticky posts
    if (empty($selected_ids)) {
        $sticky = get_option('sticky_posts');
        if (!empty($sticky)) {
            $sticky_posts = get_posts([
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => 2,
                'post__in'            => $sticky,
                'orderby'             => 'date',
                'order'               => 'DESC',
                'no_found_rows'       => true,
                'ignore_sticky_posts' => true,
            ]);
            foreach ($sticky_posts as $sp) {
                $selected_ids[] = $sp->ID;
            }
        }
    }

    // 4. Default fallback: latest 2 published posts
    if (empty($selected_ids)) {
        $fallback = get_posts([
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 2,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        ]);
        foreach ($fallback as $fp) {
            $selected_ids[] = $fp->ID;
        }
    }

    // Load full post objects in exact selected order
    $posts = [];
    foreach (array_unique($selected_ids) as $id) {
        $post = get_post($id);
        if ($post && $post->post_status === 'publish') {
            $posts[] = $post;
        }
        if (count($posts) >= 2) {
            break;
        }
    }

    return $posts;
}

/**
 * Register Featured Articles Section in Theme Customizer.
 */
function fnx_register_featured_customizer($wp_customize) {
    if (!class_exists('WP_Customize_Control')) {
        return;
    }

    // Section under existing fnx_panel
    $wp_customize->add_section('fnx_featured_section', [
        'title'       => __('⭐ Featured Articles (Homepage)', 'foundnxt'),
        'description' => __('Select 1 or 2 posts to spotlight in the Featured section on the homepage.', 'foundnxt'),
        'panel'       => 'fnx_panel',
        'priority'    => 25,
    ]);

    // Enable / Disable
    $wp_customize->add_setting('fnx_featured_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('fnx_featured_enabled', [
        'label'    => __('Enable Featured Articles Section', 'foundnxt'),
        'section'  => 'fnx_featured_section',
        'type'     => 'checkbox',
        'priority' => 10,
    ]);

    // Eyebrow Tag
    $wp_customize->add_setting('fnx_featured_tag', [
        'default'           => 'Editor’s Picks',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_featured_tag', [
        'label'    => __('Section Eyebrow Tag', 'foundnxt'),
        'section'  => 'fnx_featured_section',
        'type'     => 'text',
        'priority' => 20,
    ]);

    // Title Prefix
    $wp_customize->add_setting('fnx_featured_title_prefix', [
        'default'           => 'Featured',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_featured_title_prefix', [
        'label'    => __('Section Title Prefix', 'foundnxt'),
        'section'  => 'fnx_featured_section',
        'type'     => 'text',
        'priority' => 30,
    ]);

    // Title Accent
    $wp_customize->add_setting('fnx_featured_title_accent', [
        'default'           => 'Articles',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('fnx_featured_title_accent', [
        'label'    => __('Section Title Accent (Gradient)', 'foundnxt'),
        'section'  => 'fnx_featured_section',
        'type'     => 'text',
        'priority' => 40,
    ]);

    // Build post choices for dropdown
    $all_posts = get_posts([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 100,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
    ]);

    $post_choices = [];
    foreach ($all_posts as $p) {
        $post_choices[$p->ID] = esc_html($p->post_title);
    }

    $post_choices_with_none = [0 => __('-- None (Show Only 1 Post) --', 'foundnxt')] + $post_choices;

    // Featured Post 1
    $wp_customize->add_setting('fnx_featured_post_1', [
        'default'           => !empty($all_posts[0]) ? $all_posts[0]->ID : 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fnx_featured_post_1', [
        'label'       => __('Featured Article #1', 'foundnxt'),
        'description' => __('Primary spotlight story.', 'foundnxt'),
        'section'     => 'fnx_featured_section',
        'type'        => 'select',
        'choices'     => $post_choices,
        'priority'    => 50,
    ]);

    // Featured Post 2
    $wp_customize->add_setting('fnx_featured_post_2', [
        'default'           => !empty($all_posts[1]) ? $all_posts[1]->ID : 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fnx_featured_post_2', [
        'label'       => __('Featured Article #2 (Optional)', 'foundnxt'),
        'description' => __('Choose a second story for a 2-card grid, or select "None" to show a single widescreen hero card.', 'foundnxt'),
        'section'     => 'fnx_featured_section',
        'type'        => 'select',
        'choices'     => $post_choices_with_none,
        'priority'    => 60,
    ]);
}
add_action('customize_register', 'fnx_register_featured_customizer', 25);

/**
 * Register Meta Box on Post Edit Screen for quick toggling.
 */
function fnx_add_featured_post_metabox() {
    add_meta_box(
        'fnx_featured_metabox',
        __('⭐ Homepage Featured Spotlight', 'foundnxt'),
        'fnx_render_featured_post_metabox',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'fnx_add_featured_post_metabox');

function fnx_render_featured_post_metabox($post) {
    wp_nonce_field('fnx_featured_metabox_nonce', 'fnx_featured_nonce');
    $is_featured = (bool) get_post_meta($post->ID, 'fnx_featured', true);
    $badge       = get_post_meta($post->ID, 'fnx_post_badge', true);
    ?>
    <p>
        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
            <input type="checkbox" name="fnx_featured" value="1" <?php checked($is_featured, true); ?>>
            <span><?php _e('Feature this article on Homepage', 'foundnxt'); ?></span>
        </label>
    </p>
    <p style="margin-top:12px;">
        <label for="fnx_post_badge" style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">
            <?php _e('Custom Badge Text (Optional):', 'foundnxt'); ?>
        </label>
        <input type="text" id="fnx_post_badge" name="fnx_post_badge" value="<?php echo esc_attr($badge); ?>" placeholder="e.g. Must Read, Deep Dive, 2026 Report" style="width:100%;font-size:13px;">
    </p>
    <p class="description" style="font-size:11px;color:#64748b;margin-top:6px;">
        <?php _e('Featured articles are spotlighted prominently below the homepage hero. You can also override selections in Appearance → Customize → FoundNXT Theme.', 'foundnxt'); ?>
    </p>
    <?php
}

function fnx_save_featured_post_metabox($post_id) {
    if (!isset($_POST['fnx_featured_nonce']) || !wp_verify_nonce($_POST['fnx_featured_nonce'], 'fnx_featured_metabox_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['fnx_featured']) && $_POST['fnx_featured'] === '1') {
        update_post_meta($post_id, 'fnx_featured', 1);
    } else {
        delete_post_meta($post_id, 'fnx_featured');
    }

    if (isset($_POST['fnx_post_badge'])) {
        $badge = sanitize_text_field($_POST['fnx_post_badge']);
        if (!empty($badge)) {
            update_post_meta($post_id, 'fnx_post_badge', $badge);
        } else {
            delete_post_meta($post_id, 'fnx_post_badge');
        }
    }
}
add_action('save_post', 'fnx_save_featured_post_metabox');

/**
 * Add "Featured" column to Admin Posts List (`wp-admin/edit.php`).
 */
function fnx_featured_posts_columns($columns) {
    $new_columns = [];
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'title') {
            $new_columns['fnx_featured_col'] = __('⭐ Featured', 'foundnxt');
        }
    }
    return $new_columns;
}
add_filter('manage_posts_columns', 'fnx_featured_posts_columns');

function fnx_featured_posts_custom_column($column, $post_id) {
    if ($column === 'fnx_featured_col') {
        $is_featured = (bool) get_post_meta($post_id, 'fnx_featured', true);
        if ($is_featured) {
            echo '<span style="color:#FF5A2C;font-weight:700;display:inline-flex;align-items:center;gap:3px;" title="' . esc_attr__('Featured on Homepage', 'foundnxt') . '">⭐ ' . esc_html__('Featured', 'foundnxt') . '</span>';
        } else {
            echo '<span style="color:#94a3b8;font-size:12px;">—</span>';
        }
    }
}
add_action('manage_posts_custom_column', 'fnx_featured_posts_custom_column', 10, 2);

