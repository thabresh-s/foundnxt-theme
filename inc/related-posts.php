<?php
/**
 * In-Content Related Posts Feature
 *
 * Injects a curated 3-post "Related Reading" callout box
 * between paragraphs inside single post content.
 *
 * @package FoundNXT
 * @version 1.4.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fetch 3 related posts: same category first, fallback to recent posts.
 *
 * @param int|null $post_id Current post ID.
 * @param int $limit Number of posts to return (default: 3).
 * @return WP_Post[] Array of post objects.
 */
function fnx_get_incontent_related_posts($post_id = null, $limit = 3) {
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    if (!$post_id) {
        return [];
    }

    $related = [];
    $categories = wp_get_post_categories($post_id);

    // 1. Fetch published posts in the same categories
    if (!empty($categories)) {
        $cat_query = new WP_Query([
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'post__not_in'        => [$post_id],
            'category__in'        => $categories,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        ]);
        if (!empty($cat_query->posts)) {
            $related = $cat_query->posts;
        }
    }

    // 2. If fewer than $limit, fallback/fill with recent published posts
    $needed = $limit - count($related);
    if ($needed > 0) {
        $exclude = array_merge([$post_id], wp_list_pluck($related, 'ID'));
        $recent_query = new WP_Query([
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $needed,
            'post__not_in'        => $exclude,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        ]);
        if (!empty($recent_query->posts)) {
            $related = array_merge($related, $recent_query->posts);
        }
    }

    return array_slice($related, 0, $limit);
}

/**
 * Render the Related Posts Box HTML markup.
 *
 * @param int|null $post_id
 * @param int $limit
 * @return string HTML output
 */
function fnx_render_incontent_related_box($post_id = null, $limit = 3) {
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    if (!$post_id) {
        return '';
    }

    $posts = fnx_get_incontent_related_posts($post_id, $limit);
    if (empty($posts)) {
        return '';
    }

    ob_start();
    ?>
    <aside class="fnx-incontent-related" aria-label="<?php esc_attr_e('Related Reading', 'foundnxt'); ?>">
      <div class="fnx-incontent-related-header">
        <div class="fnx-incontent-related-badge">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
          </svg>
          <span><?php _e('Related Reading', 'foundnxt'); ?></span>
        </div>
        <span class="fnx-incontent-related-hint"><?php printf(_n('%d Curated Article', '%d Curated Articles', count($posts), 'foundnxt'), count($posts)); ?></span>
      </div>
      <div class="fnx-incontent-related-list">
        <?php foreach ($posts as $idx => $p):
          $permalink = get_permalink($p->ID);
          $title     = get_the_title($p->ID);
          $p_cats    = get_the_category($p->ID);
          $cat_name  = !empty($p_cats) ? $p_cats[0]->name : __('Article', 'foundnxt');
          $read_time = function_exists('fnx_read_time') ? fnx_read_time($p->ID) : '5 min read';
          $num_label = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
        ?>
        <a href="<?php echo esc_url($permalink); ?>" class="fnx-incontent-related-item">
          <span class="fnx-incontent-related-num"><?php echo esc_html($num_label); ?></span>
          <div class="fnx-incontent-related-body">
            <div class="fnx-incontent-related-meta">
              <span class="fnx-incontent-related-cat"><?php echo esc_html($cat_name); ?></span>
              <span class="fnx-incontent-related-sep" aria-hidden="true">•</span>
              <span class="fnx-incontent-related-time"><?php echo esc_html($read_time); ?></span>
            </div>
            <h4 class="fnx-incontent-related-title"><?php echo esc_html($title); ?></h4>
          </div>
          <span class="fnx-incontent-related-arrow" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
    </aside>
    <?php
    return ob_get_clean();
}

/**
 * Filter `the_content` to insert the Related Posts box between paragraphs on single posts.
 *
 * @param string $content
 * @return string Modified content
 */
function fnx_inject_incontent_related_box($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    if (is_feed() || (defined('REST_REQUEST') && REST_REQUEST) || is_admin()) {
        return $content;
    }

    $post_id = get_the_ID();
    if (!$post_id) {
        return $content;
    }

    // Allow post authors/admins to disable via custom field if ever needed
    if (get_post_meta($post_id, 'fnx_hide_incontent_related', true)) {
        return $content;
    }

    // Avoid double injection
    static $injected_posts = [];
    if (in_array($post_id, $injected_posts, true)) {
        return $content;
    }

    $box_html = fnx_render_incontent_related_box($post_id, 3);
    if (empty($box_html)) {
        return $content;
    }

    // Explicit manual shortcode or comment placeholder support
    if (strpos($content, '[fnx_related_box]') !== false) {
        $injected_posts[] = $post_id;
        return str_replace('[fnx_related_box]', $box_html, $content);
    }
    if (strpos($content, '<!-- fnx_related_box -->') !== false) {
        $injected_posts[] = $post_id;
        return str_replace('<!-- fnx_related_box -->', $box_html, $content);
    }

    // Split content by closing paragraph tags
    $closing_tag = '</p>';
    $parts = explode($closing_tag, $content);
    $total_parts = count($parts) - 1;

    if ($total_parts < 1) {
        $injected_posts[] = $post_id;
        return $content . "\n" . $box_html . "\n";
    }

    // Count meaningful body paragraphs (not inside figure captions, quotes, or empty)
    $meaningful_indexes = [];
    $accumulated = '';

    foreach ($parts as $idx => $part) {
        if ($idx >= $total_parts) {
            continue;
        }
        $accumulated .= $part . $closing_tag;

        // Skip if currently inside an unclosed figure, blockquote, or table
        $in_figure     = (substr_count($accumulated, '<figure') > substr_count($accumulated, '</figure>'));
        $in_blockquote = (substr_count($accumulated, '<blockquote') > substr_count($accumulated, '</blockquote>'));
        $in_table      = (substr_count($accumulated, '<table') > substr_count($accumulated, '</table>'));

        if ($in_figure || $in_blockquote || $in_table) {
            continue;
        }

        $plain = trim(strip_tags($part));
        if (strlen($plain) >= 30) {
            $meaningful_indexes[] = $idx;
        }
    }

    $num_meaningful = count($meaningful_indexes);
    if ($num_meaningful >= 6) {
        $target_meaningful_num = 3; // After 3rd body paragraph
    } elseif ($num_meaningful >= 3) {
        $target_meaningful_num = 2; // After 2nd body paragraph
    } elseif ($num_meaningful >= 1) {
        $target_meaningful_num = 1; // After 1st body paragraph
    } else {
        $target_meaningful_num = 1;
    }

    $target_part_index = isset($meaningful_indexes[$target_meaningful_num - 1])
        ? $meaningful_indexes[$target_meaningful_num - 1]
        : min(2, $total_parts - 1);

    $output = '';
    foreach ($parts as $idx => $part) {
        $output .= $part;
        if ($idx < $total_parts) {
            $output .= $closing_tag;
        }
        if ($idx === $target_part_index) {
            $output .= "\n" . $box_html . "\n";
            $injected_posts[] = $post_id;
        }
    }

    return $output;
}
add_filter('the_content', 'fnx_inject_incontent_related_box', 15);

// Shortcode support: [fnx_related_box limit="3"]
add_shortcode('fnx_related_box', function($atts) {
    $atts = shortcode_atts(['limit' => 3], $atts, 'fnx_related_box');
    return fnx_render_incontent_related_box(null, (int) $atts['limit']);
});
