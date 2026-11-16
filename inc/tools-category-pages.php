<?php
/**
 * Tools Directory: SEO category pages (v3).
 *
 * - Makes each Tool Category a public landing page at /tools/<category-slug>/
 * - Renames the original category slugs to descriptive, keyword-friendly ones (one time)
 * - Seeds a unique intro for every category (editable: Tools Directory → Tool Categories → Description)
 * - Provides the shared tool-card renderer and a little CSS for the tools pages
 *
 * Template: taxonomy-fnx_tool_cat.php
 *
 * @package FoundNXT
 */

if (!defined('ABSPATH')) {
  exit;
}

/* ------------------------------------------------------------
   1. MAKE TOOL CATEGORIES PUBLIC: /tools/<category>/
------------------------------------------------------------ */
function fnx_tools_public_taxonomy_args($args, $taxonomy) {
  if ($taxonomy === 'fnx_tool_cat') {
    $args['public']             = true;
    $args['publicly_queryable'] = true;
    $args['show_in_nav_menus']  = false;
    $args['show_tagcloud']      = false;
    $args['query_var']          = 'fnx_tool_cat';
    $args['rewrite']            = [
      'slug'         => 'tools',
      'with_front'   => false,
      'hierarchical' => false,
    ];
  }
  return $args;
}
add_filter('register_taxonomy_args', 'fnx_tools_public_taxonomy_args', 10, 2);

/* Flush rewrite rules once so /tools/<category>/ starts working. */
function fnx_tools_flush_rewrite_once() {
  if (get_option('fnx_tools_rewrite_v3') !== '1') {
    flush_rewrite_rules(false);
    update_option('fnx_tools_rewrite_v3', '1', false);
  }
}
add_action('init', 'fnx_tools_flush_rewrite_once', 99);

/* ------------------------------------------------------------
   2. ONE-TIME: SEO-FRIENDLY SLUGS + CATEGORY INTROS
------------------------------------------------------------ */
function fnx_tools_seed_seo_v3() {
  if (!add_option('fnx_tools_seeded_v3', time(), '', 'no')) {
    return;
  }
  if (!taxonomy_exists('fnx_tool_cat')) {
    delete_option('fnx_tools_seeded_v3');
    return;
  }

  /* old slug => [new slug, intro] */
  $map = [
    'crm' => ['crm-software',
      "A CRM is usually the first system a growing company outgrows. Per-seat pricing, locked-in data and rigid fields start to hurt around the time you add a second sales rep and a support inbox. The tools below cover both routes: self-hosted open-source CRMs and help desks you control, and SaaS options that are quick to roll out.\n\nWhen comparing, check how easily you can export contacts and deal history, whether custom fields and pipelines are flexible, and what the price looks like at 5, 25 and 100 users rather than at a single seat."],
    'automation' => ['ai-automation-tools',
      "Automation and AI tooling is where small teams get the most leverage, replacing manual copy-paste work and repetitive support or reporting tasks. Open-source options such as n8n, Activepieces and Dify can be self-hosted, which keeps data in your hands and avoids per-run pricing, while SaaS platforms trade that control for speed of setup.\n\nStart with one painful, repeatable process, automate it end to end, and measure the time saved before adding more. For anything that touches customer data, decide up front where models run and what gets logged."],
    'finance' => ['finance-accounting-erp',
      "Finance and operations software decides how quickly you can invoice, collect and close the books. Open-source options such as ERPNext, Odoo, Akaunting and Invoice Ninja give you control and lower licence costs, while payment and accounting SaaS tools reduce setup effort and often handle local compliance for you.\n\nFor Indian businesses, GST-ready invoicing and local payment methods like UPI should weigh heavily in the decision. Whatever you choose, make sure your accountant is comfortable with it and that you can export a clean ledger."],
    'infrastructure' => ['cloud-devops-databases',
      "Infrastructure choices are mostly cost decisions in disguise. Managed platforms such as Vercel and Cloudflare get you live quickly, while self-hosted deployment tools like Coolify and Dokploy on a low-cost server can reduce hosting bills once traffic and projects grow.\n\nThe trade-off is time. Self-hosting means you own backups, updates and uptime. A sensible middle path is to start on a managed service, keep your data portable with standard Postgres, and move workloads to your own servers only when the savings justify the effort."],
    'analytics' => ['analytics-tools',
      "You can't improve what you can't measure, but analytics stacks get expensive and complicated fast. Privacy-friendly tools like Plausible and Umami give you clean traffic numbers without cookie banners, product analytics tools like PostHog and Mixpanel show how people actually use your product, and BI tools such as Metabase and Superset let the whole team query company data.\n\nPick the smallest tool that answers your current questions. Most early teams need traffic, a conversion funnel and one shared dashboard, not a full data warehouse."],
    'collaboration' => ['project-management-knowledge',
      "As a team grows, work gets lost in chat threads and scattered documents. A simple system for tasks, a shared knowledge base and easy scheduling removes most of that friction. Open-source tools such as Plane, Outline, AppFlowy and Cal.com give you data ownership, while Linear and Notion offer polished SaaS experiences.\n\nThe best tool is the one your team actually uses. Keep the setup light, write down decisions where everyone can find them, and avoid adopting several overlapping tools at once."],
    'growth' => ['sales-marketing-growth',
      "Growth tooling should help you reach the right people and measure what works, without locking your audience into one vendor. Self-hosted tools like Mautic, Listmonk and Postiz keep your subscriber data and costs under control, while platforms such as Brevo and Apollo.io get outbound and email running quickly.\n\nOwn your email list and customer data wherever you can, and respect consent and unsubscribe rules in every market you send to. Track a small set of numbers, such as leads, conversion rate and cost per lead, before adding more channels."],
    'hr' => ['hr-payroll-people-ops',
      "People operations get complicated the moment you hire your second or tenth person. You need reliable leave and attendance tracking, clear employee records and payroll that is correct and compliant. Open-source tools like Frappe HR and OrangeHRM suit teams that want control, while Deel helps when you hire contractors or employees across borders.\n\nPayroll and labour rules differ by country and state, so confirm local compliance with a qualified professional before relying on any tool for statutory filings."],
    'security' => ['security-monitoring',
      "Security and reliability work rarely feels urgent until something breaks. A password manager, single sign-on and basic monitoring remove the most common risks cheaply. Open-source tools such as Bitwarden, Keycloak, Grafana and Uptime Kuma cover credentials, identity, dashboards and uptime, while Sentry catches application errors before customers report them.\n\nStart with the basics: shared credential vaults, multi-factor authentication, uptime alerts and tested backups. Add deeper tooling as your team and customer data grow."],
    'devtools' => ['internal-tools-no-code',
      "Internal tools, admin panels and approval workflows are often built slowly by engineers who would rather work on the product. Low-code platforms such as Appsmith, Budibase and NocoDB let operations teams build these on top of existing databases and APIs, while GitHub keeps code, reviews and automation in one place.\n\nUse these tools for processes that change often and don't need custom engineering. Keep access controls tight, because internal apps often expose sensitive company data."],
  ];

  foreach ($map as $old_slug => $row) {
    $new_slug = $row[0];
    $intro    = $row[1];

    $term = get_term_by('slug', $old_slug, 'fnx_tool_cat');
    if (!$term || is_wp_error($term)) {
      $term = get_term_by('slug', $new_slug, 'fnx_tool_cat');
    }
    if (!$term || is_wp_error($term)) {
      continue;
    }

    $args = [];
    if ($term->slug !== $new_slug) {
      $args['slug'] = $new_slug;
    }
    if (trim((string) $term->description) === '') {
      $args['description'] = $intro;
    }
    if ($args) {
      wp_update_term($term->term_id, 'fnx_tool_cat', $args);
    }
  }
}
add_action('init', 'fnx_tools_seed_seo_v3', 40);

/* ------------------------------------------------------------
   3. SHARED TOOL CARD
------------------------------------------------------------ */
function fnx_tools_render_card($tool) {
  $is_oss   = strpos(strtolower($tool['tag']), 'open source') !== false;
  $filter   = $is_oss ? 'is-oss-card' : 'is-saas-card';
  $link_rel = !empty($tool['sponsored']) ? 'sponsored nofollow noopener noreferrer' : 'noopener noreferrer';
  ?>
  <article class="tool-recommend-card <?php echo esc_attr($filter); ?>">
    <div class="tool-card-top">
      <?php if ($tool['tag'] !== '') : ?>
        <span class="tool-tag-pill <?php echo esc_attr($tool['badge']); ?>"><?php echo esc_html($tool['tag']); ?></span>
      <?php endif; ?>
    </div>

    <h3 class="tool-name"><?php echo esc_html($tool['name']); ?></h3>
    <p class="tool-desc"><?php echo esc_html($tool['desc']); ?></p>

    <?php if (!empty($tool['perks'])) : ?>
      <ul class="tool-perks-list">
        <?php foreach ($tool['perks'] as $perk) : ?>
          <li><span class="perk-check">✓</span> <?php echo esc_html($perk); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <div class="tool-card-footer">
      <a href="<?php echo esc_url($tool['url']); ?>" target="_blank" rel="<?php echo esc_attr($link_rel); ?>" class="tool-visit-link">
        <?php _e('Visit', 'foundnxt'); ?> <?php echo esc_html($tool['website']); ?> ↗
      </a>
    </div>
  </article>
  <?php
}

/* ------------------------------------------------------------
   4. SMALL CSS FOR TOOLS PAGES
------------------------------------------------------------ */
function fnx_tools_pages_css() {
  if (!is_tax('fnx_tool_cat') && !is_page_template('page-tools.php')) {
    return;
  }
  ?>
  <style id="fnx-tools-pages-css">
    .fnx-tools-intro{max-width:820px;margin:0 auto 40px;padding:24px 28px;border:1px solid rgba(127,127,127,.25);border-radius:16px}
    .fnx-tools-intro h2{font-size:1.25rem;margin:0 0 10px}
    .fnx-tools-intro p{margin:0 0 10px;line-height:1.7;opacity:.92}
    .fnx-tools-disclosure{font-size:.85rem;opacity:.75;margin:0}
    .cat-view-all{margin-left:auto;font-size:.9rem;font-weight:600;text-decoration:none;white-space:nowrap}
    .cat-view-all:hover{text-decoration:underline}
    .fnx-tools-crumbs{font-size:.9rem;margin-bottom:18px;opacity:.85}
    .fnx-tools-crumbs a{text-decoration:none}
    .fnx-tools-crumbs a:hover{text-decoration:underline}
    .fnx-cat-stats{margin:0 0 18px;font-size:.95rem;opacity:.8}
    .fnx-cat-intro{max-width:760px;margin:0 auto 36px;line-height:1.75;text-align:left}
    .fnx-cat-intro p{margin:0 0 14px}
    .fnx-cat-others{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin:48px 0 8px}
    .fnx-cat-others-title{text-align:center;margin:48px 0 0;font-size:1.15rem}
    .fnx-cat-others a.tools-filter-pill{text-decoration:none;display:inline-block}
  </style>
  <?php
}
add_action('wp_head', 'fnx_tools_pages_css', 30);
