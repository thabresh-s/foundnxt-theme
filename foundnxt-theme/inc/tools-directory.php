<?php
/**
 * Tools Directory: lets you manage the /tools/ page from wp-admin.
 *
 * - Custom post type  "Tools Directory" (one entry = one tool card)
 * - Taxonomy          "Tool Categories" (one category = one section + one filter chip)
 * - Meta box          badge text/colour, description, perks, URL, affiliate flag
 * - Seeds the original 15 tools / 6 categories once, on first load.
 *
 * Rendered by page-tools.php via fnx_get_tools_directory().
 *
 * @package FoundNXT
 */

if (!defined('ABSPATH')) {
  exit;
}

/* ------------------------------------------------------------
   0. EMOJI HELPERS
   The database tables use a 3-byte UTF-8 charset, which cannot store
   4-byte emoji (💬 📊 🚀). We store emoji as numeric HTML entities and
   turn them back into real characters when rendering.
------------------------------------------------------------ */
function fnx_tools_emoji_encode($text) {
  $text = (string) $text;
  return function_exists('wp_encode_emoji') ? wp_encode_emoji($text) : $text;
}

function fnx_tools_emoji_decode($text) {
  $text = (string) $text;
  $text = preg_replace_callback('/&#x([0-9a-fA-F]+);/', function ($m) {
    $code = hexdec($m[1]);
    return ($code > 0 && $code <= 0x10FFFF) ? mb_chr($code, 'UTF-8') : $m[0];
  }, $text);
  return preg_replace_callback('/&#(\d+);/', function ($m) {
    $code = (int) $m[1];
    return ($code > 0 && $code <= 0x10FFFF) ? mb_chr($code, 'UTF-8') : $m[0];
  }, $text);
}

/* ------------------------------------------------------------
   1. REGISTER POST TYPE + TAXONOMY
------------------------------------------------------------ */
function fnx_tools_register() {
  register_post_type('fnx_tool', [
    'labels' => [
      'name'               => 'Tools Directory',
      'singular_name'      => 'Tool',
      'menu_name'          => 'Tools Directory',
      'add_new'            => 'Add New Tool',
      'add_new_item'       => 'Add New Tool',
      'edit_item'          => 'Edit Tool',
      'new_item'           => 'New Tool',
      'view_item'          => 'View Tool',
      'search_items'       => 'Search Tools',
      'not_found'          => 'No tools found',
      'not_found_in_trash' => 'No tools found in Trash',
      'all_items'          => 'All Tools',
    ],
    'public'              => false,
    'publicly_queryable'  => false,
    'exclude_from_search' => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_admin_bar'   => false,
    'show_in_rest'        => false,
    'has_archive'         => false,
    'rewrite'             => false,
    'query_var'           => false,
    'menu_position'       => 26,
    'menu_icon'           => 'dashicons-hammer',
    'supports'            => ['title', 'page-attributes'],
    'capability_type'     => 'post',
  ]);

  register_taxonomy('fnx_tool_cat', 'fnx_tool', [
    'labels' => [
      'name'          => 'Tool Categories',
      'singular_name' => 'Tool Category',
      'menu_name'     => 'Tool Categories',
      'all_items'     => 'All Tool Categories',
      'add_new_item'  => 'Add Tool Category',
      'edit_item'     => 'Edit Tool Category',
      'search_items'  => 'Search Tool Categories',
    ],
    'public'            => false,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_rest'      => false,
    'hierarchical'      => true,
    'rewrite'           => false,
    'query_var'         => false,
  ]);
}
add_action('init', 'fnx_tools_register');

/* ------------------------------------------------------------
   2. TOOL META BOX
------------------------------------------------------------ */
function fnx_tools_badge_options() {
  return [
    'badge--oss'  => 'Open Source (green)',
    'badge--saas' => 'SaaS / Freemium (blue)',
    'badge--pro'  => 'Industry Standard (purple)',
  ];
}

function fnx_tools_add_meta_box() {
  add_meta_box('fnx_tool_details', 'Tool Details', 'fnx_tools_meta_box_render', 'fnx_tool', 'normal', 'high');
}
add_action('add_meta_boxes', 'fnx_tools_add_meta_box');

function fnx_tools_meta_box_render($post) {
  wp_nonce_field('fnx_tool_save', 'fnx_tool_nonce');

  $tag       = fnx_tools_emoji_decode(get_post_meta($post->ID, '_fnx_tool_tag', true));
  $badge     = get_post_meta($post->ID, '_fnx_tool_badge', true);
  $desc      = fnx_tools_emoji_decode(get_post_meta($post->ID, '_fnx_tool_desc', true));
  $perks     = fnx_tools_emoji_decode(get_post_meta($post->ID, '_fnx_tool_perks', true));
  $url       = get_post_meta($post->ID, '_fnx_tool_url', true);
  $website   = get_post_meta($post->ID, '_fnx_tool_website', true);
  $sponsored = get_post_meta($post->ID, '_fnx_tool_sponsored', true);
  ?>
  <style>
    .fnx-tool-fields th { width: 190px; }
    .fnx-tool-fields input[type=text],
    .fnx-tool-fields input[type=url],
    .fnx-tool-fields select,
    .fnx-tool-fields textarea { width: 100%; max-width: 640px; }
  </style>
  <p style="margin-top:0;color:#646970;">
    Tick a <strong>Tool Category</strong> in the right-hand box, set the <strong>Order</strong> number (lower shows first), then publish.
    The card appears on <code>/tools/</code> automatically.
  </p>
  <table class="form-table fnx-tool-fields" role="presentation">
    <tr>
      <th scope="row"><label for="fnx_tool_tag">Badge text</label></th>
      <td>
        <input type="text" id="fnx_tool_tag" name="fnx_tool_tag" value="<?php echo esc_attr($tag); ?>" placeholder="Open Source, Freemium SaaS, Recommended SaaS…">
        <p class="description">Shown as the small pill on the card. If it contains "open source", the card is included in the "Open Source Only" filter.</p>
      </td>
    </tr>
    <tr>
      <th scope="row"><label for="fnx_tool_badge">Badge colour</label></th>
      <td>
        <select id="fnx_tool_badge" name="fnx_tool_badge">
          <?php foreach (fnx_tools_badge_options() as $val => $label) : ?>
            <option value="<?php echo esc_attr($val); ?>" <?php selected($badge ?: 'badge--oss', $val); ?>><?php echo esc_html($label); ?></option>
          <?php endforeach; ?>
        </select>
      </td>
    </tr>
    <tr>
      <th scope="row"><label for="fnx_tool_desc">Short description</label></th>
      <td><textarea id="fnx_tool_desc" name="fnx_tool_desc" rows="3"><?php echo esc_textarea($desc); ?></textarea></td>
    </tr>
    <tr>
      <th scope="row"><label for="fnx_tool_perks">Highlights</label></th>
      <td>
        <textarea id="fnx_tool_perks" name="fnx_tool_perks" rows="4" placeholder="One highlight per line"><?php echo esc_textarea($perks); ?></textarea>
        <p class="description">One per line. 3 works best.</p>
      </td>
    </tr>
    <tr>
      <th scope="row"><label for="fnx_tool_url">Website URL</label></th>
      <td><input type="url" id="fnx_tool_url" name="fnx_tool_url" value="<?php echo esc_attr($url); ?>" placeholder="https://example.com"></td>
    </tr>
    <tr>
      <th scope="row"><label for="fnx_tool_website">Button domain (optional)</label></th>
      <td>
        <input type="text" id="fnx_tool_website" name="fnx_tool_website" value="<?php echo esc_attr($website); ?>" placeholder="example.com">
        <p class="description">Shown as "Visit example.com ↗". Leave blank to auto-detect from the URL.</p>
      </td>
    </tr>
    <tr>
      <th scope="row">Affiliate / sponsored link</th>
      <td>
        <label>
          <input type="checkbox" name="fnx_tool_sponsored" value="1" <?php checked($sponsored, '1'); ?>>
          Mark this link as sponsored (adds rel="sponsored nofollow")
        </label>
      </td>
    </tr>
  </table>
  <?php
}

function fnx_tools_save_meta($post_id) {
  if (!isset($_POST['fnx_tool_nonce'])) {
    return;
  }
  if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['fnx_tool_nonce'])), 'fnx_tool_save')) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  $badge   = isset($_POST['fnx_tool_badge']) ? sanitize_text_field(wp_unslash($_POST['fnx_tool_badge'])) : 'badge--oss';
  $options = fnx_tools_badge_options();
  if (!isset($options[$badge])) {
    $badge = 'badge--oss';
  }

  $fields = [
    '_fnx_tool_tag'       => isset($_POST['fnx_tool_tag']) ? fnx_tools_emoji_encode(sanitize_text_field(wp_unslash($_POST['fnx_tool_tag']))) : '',
    '_fnx_tool_badge'     => $badge,
    '_fnx_tool_desc'      => isset($_POST['fnx_tool_desc']) ? fnx_tools_emoji_encode(sanitize_textarea_field(wp_unslash($_POST['fnx_tool_desc']))) : '',
    '_fnx_tool_perks'     => isset($_POST['fnx_tool_perks']) ? fnx_tools_emoji_encode(sanitize_textarea_field(wp_unslash($_POST['fnx_tool_perks']))) : '',
    '_fnx_tool_url'       => isset($_POST['fnx_tool_url']) ? esc_url_raw(wp_unslash($_POST['fnx_tool_url'])) : '',
    '_fnx_tool_website'   => isset($_POST['fnx_tool_website']) ? sanitize_text_field(wp_unslash($_POST['fnx_tool_website'])) : '',
    '_fnx_tool_sponsored' => !empty($_POST['fnx_tool_sponsored']) ? '1' : '',
  ];

  foreach ($fields as $key => $value) {
    update_post_meta($post_id, $key, $value);
  }
}
add_action('save_post_fnx_tool', 'fnx_tools_save_meta');

/* ------------------------------------------------------------
   3. CATEGORY EXTRA FIELDS (icon, chip label, order)
------------------------------------------------------------ */
function fnx_tool_cat_add_fields() {
  ?>
  <div class="form-field">
    <label for="fnx_cat_icon">Icon (emoji)</label>
    <input type="text" name="fnx_cat_icon" id="fnx_cat_icon" value="" maxlength="8" placeholder="💬">
    <p>Shown next to the section title and on the filter chip.</p>
  </div>
  <div class="form-field">
    <label for="fnx_cat_label">Filter chip label</label>
    <input type="text" name="fnx_cat_label" id="fnx_cat_label" value="" placeholder="CRM &amp; Ops">
    <p>Short name for the filter button. Leave blank to use the category name.</p>
  </div>
  <div class="form-field">
    <label for="fnx_cat_order">Order</label>
    <input type="number" name="fnx_cat_order" id="fnx_cat_order" value="0" step="1">
    <p>Lower numbers appear first on the page.</p>
  </div>
  <?php
}
add_action('fnx_tool_cat_add_form_fields', 'fnx_tool_cat_add_fields');

function fnx_tool_cat_edit_fields($term) {
  $icon  = get_term_meta($term->term_id, 'fnx_icon', true);
  $label = get_term_meta($term->term_id, 'fnx_filter_label', true);
  $order = get_term_meta($term->term_id, 'fnx_order', true);
  ?>
  <tr class="form-field">
    <th scope="row"><label for="fnx_cat_icon">Icon (emoji)</label></th>
    <td><input type="text" name="fnx_cat_icon" id="fnx_cat_icon" value="<?php echo esc_attr(fnx_tools_emoji_decode($icon)); ?>" maxlength="8"></td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="fnx_cat_label">Filter chip label</label></th>
    <td>
      <input type="text" name="fnx_cat_label" id="fnx_cat_label" value="<?php echo esc_attr($label); ?>">
      <p class="description">Leave blank to use the category name.</p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="fnx_cat_order">Order</label></th>
    <td>
      <input type="number" name="fnx_cat_order" id="fnx_cat_order" value="<?php echo esc_attr($order === '' ? 0 : $order); ?>" step="1">
      <p class="description">Lower numbers appear first on the page.</p>
    </td>
  </tr>
  <?php
}
add_action('fnx_tool_cat_edit_form_fields', 'fnx_tool_cat_edit_fields');

function fnx_tool_cat_save($term_id) {
  if (!current_user_can('manage_categories')) {
    return;
  }
  if (isset($_POST['fnx_cat_icon'])) {
    update_term_meta($term_id, 'fnx_icon', fnx_tools_emoji_encode(sanitize_text_field(wp_unslash($_POST['fnx_cat_icon']))));
  }
  if (isset($_POST['fnx_cat_label'])) {
    update_term_meta($term_id, 'fnx_filter_label', fnx_tools_emoji_encode(sanitize_text_field(wp_unslash($_POST['fnx_cat_label']))));
  }
  if (isset($_POST['fnx_cat_order'])) {
    update_term_meta($term_id, 'fnx_order', (int) $_POST['fnx_cat_order']);
  }
}
add_action('created_fnx_tool_cat', 'fnx_tool_cat_save');
add_action('edited_fnx_tool_cat', 'fnx_tool_cat_save');

/* ------------------------------------------------------------
   4. ADMIN LIST COLUMNS
------------------------------------------------------------ */
function fnx_tools_admin_columns($columns) {
  $new = [];
  foreach ($columns as $key => $label) {
    if ($key === 'date') {
      continue;
    }
    $new[$key] = $label;
    if ($key === 'title') {
      $new['fnx_tag'] = 'Badge';
      $new['fnx_url'] = 'Website';
    }
  }
  $new['fnx_order'] = 'Order';
  return $new;
}
add_filter('manage_fnx_tool_posts_columns', 'fnx_tools_admin_columns');

function fnx_tools_admin_column_content($column, $post_id) {
  if ($column === 'fnx_tag') {
    echo esc_html(get_post_meta($post_id, '_fnx_tool_tag', true));
  } elseif ($column === 'fnx_url') {
    $url = get_post_meta($post_id, '_fnx_tool_url', true);
    if ($url) {
      echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">' . esc_html(preg_replace('#^https?://(www\.)?#', '', untrailingslashit($url))) . '</a>';
    }
  } elseif ($column === 'fnx_order') {
    echo (int) get_post_field('menu_order', $post_id);
  }
}
add_action('manage_fnx_tool_posts_custom_column', 'fnx_tools_admin_column_content', 10, 2);

/* ------------------------------------------------------------
   5. FRONT-END DATA GETTER (used by page-tools.php)
------------------------------------------------------------ */
function fnx_get_tools_directory() {
  $terms = get_terms([
    'taxonomy'   => 'fnx_tool_cat',
    'hide_empty' => true,
  ]);
  if (is_wp_error($terms) || empty($terms)) {
    return [];
  }

  usort($terms, function ($a, $b) {
    $oa = (int) get_term_meta($a->term_id, 'fnx_order', true);
    $ob = (int) get_term_meta($b->term_id, 'fnx_order', true);
    if ($oa === $ob) {
      return strcasecmp($a->name, $b->name);
    }
    return $oa <=> $ob;
  });

  $badge_options = fnx_tools_badge_options();
  $out = [];

  foreach ($terms as $term) {
    $posts = get_posts([
      'post_type'   => 'fnx_tool',
      'post_status' => 'publish',
      'numberposts' => -1,
      'orderby'     => ['menu_order' => 'ASC', 'title' => 'ASC'],
      'tax_query'   => [[
        'taxonomy'         => 'fnx_tool_cat',
        'field'            => 'term_id',
        'terms'            => $term->term_id,
        'include_children' => false,
      ]],
    ]);
    if (empty($posts)) {
      continue;
    }

    $tools = [];
    foreach ($posts as $p) {
      $url = get_post_meta($p->ID, '_fnx_tool_url', true);
      if (!$url) {
        continue;
      }

      $website = get_post_meta($p->ID, '_fnx_tool_website', true);
      if (!$website) {
        $host    = wp_parse_url($url, PHP_URL_HOST);
        $website = $host ? preg_replace('/^www\./', '', $host) : $url;
      }

      $badge = get_post_meta($p->ID, '_fnx_tool_badge', true);
      if (!isset($badge_options[$badge])) {
        $badge = 'badge--oss';
      }

      $perks_raw = fnx_tools_emoji_decode((string) get_post_meta($p->ID, '_fnx_tool_perks', true));
      $perks     = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $perks_raw))));

      $tools[] = [
        'name'      => get_the_title($p),
        'tag'       => fnx_tools_emoji_decode(get_post_meta($p->ID, '_fnx_tool_tag', true)),
        'badge'     => $badge,
        'desc'      => fnx_tools_emoji_decode(get_post_meta($p->ID, '_fnx_tool_desc', true)),
        'perks'     => $perks,
        'url'       => $url,
        'website'   => $website,
        'sponsored' => get_post_meta($p->ID, '_fnx_tool_sponsored', true) === '1',
      ];
    }
    if (empty($tools)) {
      continue;
    }

    $icon  = get_term_meta($term->term_id, 'fnx_icon', true);
    $label = get_term_meta($term->term_id, 'fnx_filter_label', true);

    $out[$term->slug] = [
      'title' => $term->name,
      'label' => $label ? fnx_tools_emoji_decode($label) : $term->name,
      'icon'  => $icon ? fnx_tools_emoji_decode($icon) : '🛠️',
      'tools' => $tools,
    ];
  }

  return $out;
}

/* ------------------------------------------------------------
   6. ONE-TIME SEED OF THE ORIGINAL TOOLS
------------------------------------------------------------ */
function fnx_tools_seed_defaults() {
  // add_option returns false if it already exists, so this runs exactly once.
  if (!add_option('fnx_tools_seeded_v1', time(), '', 'no')) {
    return;
  }
  if (!taxonomy_exists('fnx_tool_cat') || !post_type_exists('fnx_tool')) {
    delete_option('fnx_tools_seeded_v1');
    return;
  }

  $seed = [
    'crm' => [
      'name' => 'CRM & Customer Operations', 'label' => 'CRM & Ops', 'icon' => '💬',
      'tools' => [
        ['Twenty CRM', 'Open Source', 'badge--oss', 'Modern, self-hosted open-source CRM alternative to Salesforce & HubSpot built with modern web architecture.', ['Full Data Control', 'Custom Objects & Fields', 'Zero Per-Seat Costs'], 'https://twenty.com', 'twenty.com'],
        ['Chatwoot', 'Open Source', 'badge--oss', 'Open-source customer engagement suite with shared inbox, live chat, and multi-channel messaging support.', ['Omnichannel Messaging', 'Self-Hosted Option', 'WhatsApp & Email Sync'], 'https://www.chatwoot.com', 'chatwoot.com'],
        ['HubSpot CRM', 'Freemium SaaS', 'badge--saas', 'Industry-standard sales CRM, pipeline tracker, and marketing automation suite for fast-growing GTM teams.', ['Generous Free Tier', 'Rich Integration Ecosystem', 'Automated Workflows'], 'https://www.hubspot.com', 'hubspot.com'],
      ],
    ],
    'automation' => [
      'name' => 'AI, Automation & Workflow', 'label' => 'AI & Automation', 'icon' => '⚡',
      'tools' => [
        ['n8n.io', 'Open Source / Fair-Code', 'badge--oss', 'Self-hostable node-based workflow automation tool with AI agent integrations and 400+ native connectors.', ['Native AI Nodes', 'Self-Hosted Privacy', 'Unlimited Workflow Runs'], 'https://n8n.io', 'n8n.io'],
        ['Flowise AI', 'Open Source', 'badge--oss', 'Drag-and-drop UI to build customized LLM chains, RAG pipelines, and autonomous AI agents.', ['Visual LangChain Builder', 'RAG Document QA', 'API Endpoint Deployment'], 'https://flowiseai.com', 'flowiseai.com'],
        ['Make (Integromat)', 'Recommended SaaS', 'badge--saas', 'Visual automation platform to visually design, build, and automate complex cross-application workflows.', ['Visual Data Flow', 'Cost-Effective Operations', 'Complex Logic Handling'], 'https://www.make.com', 'make.com'],
      ],
    ],
    'finance' => [
      'name' => 'Finance, Accounting & ERP', 'label' => 'Finance & ERP', 'icon' => '📊',
      'tools' => [
        ['ERPNext', 'Open Source ERP', 'badge--oss', '100% open-source enterprise resource planning suite covering accounting, inventory, HR, and payroll.', ['Complete Business ERP', 'Double-Entry Accounting', 'Modular Architecture'], 'https://erpnext.com', 'erpnext.com'],
        ['Invoice Ninja', 'Open Source', 'badge--oss', 'Open-source platform for invoicing, expense tracking, quotes, and recurring subscription billing.', ['Custom Invoice Templates', 'Online Payment Gateways', 'Self-Host Support'], 'https://www.invoiceninja.com', 'invoiceninja.com'],
        ['Stripe', 'Industry Standard', 'badge--pro', 'Financial infrastructure for the internet — accept payments, send payouts, and manage billing globally.', ['Global Payment Gateways', 'Subscription Billing', 'Fraud Protection'], 'https://stripe.com', 'stripe.com'],
      ],
    ],
    'infrastructure' => [
      'name' => 'Cloud, DevOps & Databases', 'label' => 'Cloud & Infra', 'icon' => '🚀',
      'tools' => [
        ['Supabase', 'Open Source', 'badge--oss', 'Open-source Firebase alternative providing a dedicated Postgres database, Authentication, Instant APIs, and Storage.', ['Full Postgres Database', 'Realtime Subscriptions', 'Auto Generated APIs'], 'https://supabase.com', 'supabase.com'],
        ['Coolify', 'Open Source Self-Hosted', 'badge--oss', 'Self-hosted Heroku and Netlify alternative. Deploy applications, databases, and services on your own servers.', ['Own Your Infrastructure', 'Git Push Deployment', 'Zero Vendor Lock-in'], 'https://coolify.io', 'coolify.io'],
        ['Vercel / Cloudflare', 'Recommended SaaS', 'badge--saas', 'Edge network and frontend cloud platform for ultra-fast web deployments and serverless functions.', ['Instant Global CDN', 'Zero-Config Deployments', 'Sub-second Latency'], 'https://vercel.com', 'vercel.com'],
      ],
    ],
    'analytics' => [
      'name' => 'Analytics, Product & Marketing', 'label' => 'Analytics', 'icon' => '📈',
      'tools' => [
        ['PostHog', 'Open Source', 'badge--oss', 'Single platform for session recording, product analytics, feature flags, A/B testing, and user surveys.', ['Product Analytics + Session Replay', 'Feature Flags & A/B Tests', 'Self-Host or Cloud'], 'https://posthog.com', 'posthog.com'],
        ['Plausible Analytics', 'Open Source / Privacy', 'badge--oss', 'Lightweight, privacy-friendly Google Analytics alternative that requires no cookies and is GDPR compliant.', ['< 1KB Script Weight', 'No Cookies / 100% GDPR', 'Clean Single-Dashboard UI'], 'https://plausible.io', 'plausible.io'],
        ['Mixpanel', 'Recommended SaaS', 'badge--saas', 'Powerful self-serve product analytics to track conversion funnels, retention cohorts, and user flows.', ['Deep Funnel Insights', 'Cohort Retention', 'Interactive Dashboards'], 'https://mixpanel.com', 'mixpanel.com'],
      ],
    ],
    'collaboration' => [
      'name' => 'Project Management & Knowledge', 'label' => 'Project & Docs', 'icon' => '📁',
      'tools' => [
        ['AppFlowy', 'Open Source', 'badge--oss', 'Open-source Notion alternative built with Flutter & Rust. Keep total control over your business data.', ['Offline-First Support', '100% Data Ownership', 'Kanban & Wiki Boards'], 'https://www.appflowy.io', 'appflowy.io'],
        ['Mattermost', 'Open Source Workspace', 'badge--oss', 'Secure, open-source team collaboration and chat platform designed for high-security enterprise teams.', ['Self-Hosted Slack Alternative', 'Granular Security Controls', 'DevOps Toolchain Integration'], 'https://mattermost.com', 'mattermost.com'],
        ['Notion', 'Recommended SaaS', 'badge--saas', 'Connected workspace where better, faster work happens — docs, wikis, project management, and AI notes.', ['Flexible Database Views', 'AI Assistant Built-in', 'Centralized Team Knowledge'], 'https://www.notion.so', 'notion.so'],
      ],
    ],
  ];

  $cat_order = 0;
  foreach ($seed as $slug => $cat) {
    $cat_order += 10;

    $existing = term_exists($slug, 'fnx_tool_cat');
    if ($existing) {
      $term_id = is_array($existing) ? (int) $existing['term_id'] : (int) $existing;
    } else {
      $created = wp_insert_term($cat['name'], 'fnx_tool_cat', ['slug' => $slug]);
      if (is_wp_error($created)) {
        continue;
      }
      $term_id = (int) $created['term_id'];
    }

    update_term_meta($term_id, 'fnx_icon', fnx_tools_emoji_encode($cat['icon']));
    update_term_meta($term_id, 'fnx_filter_label', $cat['label']);
    update_term_meta($term_id, 'fnx_order', $cat_order);

    $tool_order = 0;
    foreach ($cat['tools'] as $t) {
      $tool_order += 10;
      $post_id = wp_insert_post([
        'post_type'   => 'fnx_tool',
        'post_status' => 'publish',
        'post_title'  => $t[0],
        'menu_order'  => $tool_order,
      ]);
      if (is_wp_error($post_id) || !$post_id) {
        continue;
      }
      update_post_meta($post_id, '_fnx_tool_tag', $t[1]);
      update_post_meta($post_id, '_fnx_tool_badge', $t[2]);
      update_post_meta($post_id, '_fnx_tool_desc', $t[3]);
      update_post_meta($post_id, '_fnx_tool_perks', implode("\n", $t[4]));
      update_post_meta($post_id, '_fnx_tool_url', $t[5]);
      update_post_meta($post_id, '_fnx_tool_website', $t[6]);
      wp_set_object_terms($post_id, [$term_id], 'fnx_tool_cat');
    }
  }
}
add_action('init', 'fnx_tools_seed_defaults', 20);

/**
 * One-time repair: the first seed run could not store 4-byte emoji, so
 * category icons were saved empty. Restore the default icons once.
 */
function fnx_tools_repair_icons() {
  if (!add_option('fnx_tools_icons_repaired_v2', time(), '', 'no')) {
    return;
  }
  if (!taxonomy_exists('fnx_tool_cat')) {
    delete_option('fnx_tools_icons_repaired_v2');
    return;
  }
  $defaults = [
    'crm'            => '💬',
    'automation'     => '⚡',
    'finance'        => '📊',
    'infrastructure' => '🚀',
    'analytics'      => '📈',
    'collaboration'  => '📁',
  ];
  foreach ($defaults as $slug => $emoji) {
    $term = get_term_by('slug', $slug, 'fnx_tool_cat');
    if ($term && !is_wp_error($term)) {
      update_term_meta($term->term_id, 'fnx_icon', fnx_tools_emoji_encode($emoji));
    }
  }
}
add_action('init', 'fnx_tools_repair_icons', 25);
