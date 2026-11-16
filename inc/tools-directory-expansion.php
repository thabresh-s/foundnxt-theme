<?php
/**
 * Tools Directory: expansion pack (v2).
 *
 * Adds ~40 more tools and 4 new categories to the directory, one time.
 * Everything added here is a normal Tools Directory entry, so you can
 * edit, reorder, or delete any of it from wp-admin afterwards.
 *
 * @package FoundNXT
 */

if (!defined('ABSPATH')) {
  exit;
}

function fnx_tools_seed_expansion_v2() {
  // add_option returns false if it already exists, so this runs exactly once.
  if (!add_option('fnx_tools_seeded_v2', time(), '', 'no')) {
    return;
  }
  if (!taxonomy_exists('fnx_tool_cat') || !post_type_exists('fnx_tool') || !function_exists('fnx_tools_emoji_encode')) {
    delete_option('fnx_tools_seeded_v2');
    return;
  }

  global $wpdb;

  /* New categories: slug => [name, chip label, icon, order] */
  $new_cats = [
    'growth'   => ['Sales, Marketing & Growth', 'Sales & Growth', '📣', 70],
    'hr'       => ['HR, Payroll & People Ops', 'HR & People', '👥', 80],
    'security' => ['Security, Monitoring & Reliability', 'Security & Reliability', '🛡️', 90],
    'devtools' => ['Internal Tools, No-Code & Dev Workflow', 'Build & No-Code', '🧩', 100],
  ];

  $term_ids = [];
  foreach (['crm', 'automation', 'finance', 'infrastructure', 'analytics', 'collaboration'] as $slug) {
    $t = get_term_by('slug', $slug, 'fnx_tool_cat');
    if ($t && !is_wp_error($t)) {
      $term_ids[$slug] = (int) $t->term_id;
    }
  }
  foreach ($new_cats as $slug => $c) {
    $t = get_term_by('slug', $slug, 'fnx_tool_cat');
    if ($t && !is_wp_error($t)) {
      $term_ids[$slug] = (int) $t->term_id;
      continue;
    }
    $created = wp_insert_term($c[0], 'fnx_tool_cat', ['slug' => $slug]);
    if (is_wp_error($created)) {
      continue;
    }
    $term_ids[$slug] = (int) $created['term_id'];
    update_term_meta($term_ids[$slug], 'fnx_icon', fnx_tools_emoji_encode($c[2]));
    update_term_meta($term_ids[$slug], 'fnx_filter_label', $c[1]);
    update_term_meta($term_ids[$slug], 'fnx_order', $c[3]);
  }

  /* [category, name, badge text, badge class, description, [highlights], url, domain] */
  $tools = [
    /* ── CRM & Customer Operations ── */
    ['crm', 'SuiteCRM', 'Open Source', 'badge--oss', 'Mature open-source CRM with sales automation, reporting, and workflows you can self-host and customise.', ['Self-Hosted & Customisable', 'Sales Pipeline & Reporting', 'No Per-Seat Licence Fees'], 'https://suitecrm.com', 'suitecrm.com'],
    ['crm', 'Pipedrive', 'Recommended SaaS', 'badge--saas', 'Pipeline-first sales CRM that keeps small and mid-size teams focused on the deals that are actually moving.', ['Visual Sales Pipeline', 'Workflow Automation', 'Quick Setup'], 'https://www.pipedrive.com', 'pipedrive.com'],
    ['crm', 'Zammad', 'Open Source', 'badge--oss', 'Open-source help desk and ticketing system that brings email, chat, and social channels into one inbox.', ['Multi-Channel Ticketing', 'Self-Hosted Option', 'Built-in Knowledge Base'], 'https://zammad.org', 'zammad.org'],

    /* ── AI, Automation & Workflow ── */
    ['automation', 'Activepieces', 'Open Source', 'badge--oss', 'No-code automation builder and self-hostable alternative to Zapier, with AI-ready building blocks.', ['Self-Hostable', 'Visual Flow Builder', 'Growing App Library'], 'https://www.activepieces.com', 'activepieces.com'],
    ['automation', 'Zapier', 'Recommended SaaS', 'badge--saas', 'The most widely connected no-code automation platform for linking thousands of business apps.', ['Huge App Ecosystem', 'No-Code Setup', 'AI Automation Features'], 'https://zapier.com', 'zapier.com'],
    ['automation', 'Dify', 'Open Source', 'badge--oss', 'Open-source platform for building LLM apps, agents, and RAG workflows with a visual studio and API.', ['Visual Agent Builder', 'RAG Knowledge Bases', 'API-First Deployment'], 'https://dify.ai', 'dify.ai'],
    ['automation', 'Ollama', 'Open Source', 'badge--oss', 'Run open-weight language models on your own machines or servers with a simple CLI and local API.', ['Private Local Inference', 'Simple Model Management', 'No Per-Token Costs'], 'https://ollama.com', 'ollama.com'],
    ['automation', 'Langfuse', 'Open Source', 'badge--oss', 'Open-source LLM engineering platform for tracing, evaluating, and monitoring AI application quality and cost.', ['Request Tracing', 'Prompt Management', 'Cost & Quality Tracking'], 'https://langfuse.com', 'langfuse.com'],

    /* ── Finance, Accounting & ERP ── */
    ['finance', 'Akaunting', 'Open Source', 'badge--oss', 'Free, open-source online accounting for invoicing, expense tracking, and everyday bookkeeping.', ['Invoicing & Expenses', 'Multi-Currency', 'Extendable with Apps'], 'https://akaunting.com', 'akaunting.com'],
    ['finance', 'Lago', 'Open Source', 'badge--oss', 'Open-source billing engine for usage-based pricing, metering, subscriptions, and invoicing.', ['Usage-Based Billing', 'Subscription Plans', 'Self-Host or Cloud'], 'https://www.getlago.com', 'getlago.com'],
    ['finance', 'Razorpay', 'Industry Standard', 'badge--pro', 'Payments and banking stack for Indian businesses: collect payments, run payouts, and manage subscriptions.', ['UPI, Cards & Netbanking', 'Subscription Billing', 'Payouts & Banking Tools'], 'https://razorpay.com', 'razorpay.com'],
    ['finance', 'Zoho Books', 'Recommended SaaS', 'badge--saas', 'Cloud accounting with GST-ready invoicing, expense tracking, and bank reconciliation for growing businesses.', ['GST-Ready Invoicing', 'Bank Reconciliation', 'Fits the Zoho Suite'], 'https://www.zoho.com/books/', 'zoho.com'],
    ['finance', 'Odoo', 'Open Source / Open Core', 'badge--oss', 'Modular business suite covering CRM, accounting, inventory, e-commerce, and manufacturing in one platform.', ['All-in-One Modules', 'Community Edition Available', 'Large App Marketplace'], 'https://www.odoo.com', 'odoo.com'],

    /* ── Cloud, DevOps & Databases ── */
    ['infrastructure', 'Appwrite', 'Open Source', 'badge--oss', 'Open-source backend platform with authentication, databases, storage, and serverless functions.', ['Auth & Databases', 'File Storage', 'Self-Host or Cloud'], 'https://appwrite.io', 'appwrite.io'],
    ['infrastructure', 'Dokploy', 'Open Source Self-Hosted', 'badge--oss', 'Self-hostable deployment platform and alternative to Vercel, Netlify, and Heroku for your own servers.', ['Docker-Based Deploys', 'Built-in Databases', 'Low Hosting Cost'], 'https://dokploy.com', 'dokploy.com'],
    ['infrastructure', 'Hetzner Cloud', 'Recommended SaaS', 'badge--saas', 'Cost-efficient cloud servers and storage popular with startups that want strong price-to-performance.', ['Low-Cost Compute', 'Simple Console & API', 'EU Data Centres'], 'https://www.hetzner.com/cloud', 'hetzner.com'],

    /* ── Analytics, Product & Marketing ── */
    ['analytics', 'Metabase', 'Open Source', 'badge--oss', 'Business intelligence tool that lets non-technical teams explore data and build dashboards with no SQL.', ['Point-and-Click Queries', 'Shareable Dashboards', 'Self-Host or Cloud'], 'https://www.metabase.com', 'metabase.com'],
    ['analytics', 'Apache Superset', 'Open Source', 'badge--oss', 'Powerful open-source data exploration and visualisation platform for teams with serious analytics needs.', ['Rich Chart Library', 'SQL Lab', 'Scales to Large Data'], 'https://superset.apache.org', 'superset.apache.org'],
    ['analytics', 'Umami', 'Open Source / Privacy', 'badge--oss', 'Simple, fast, privacy-focused web analytics you can self-host as a lightweight alternative to Google Analytics.', ['Cookie-Free Tracking', 'Clean Dashboard', 'Self-Hostable'], 'https://umami.is', 'umami.is'],
    ['analytics', 'Looker Studio', 'Free SaaS', 'badge--saas', 'Free dashboarding tool that turns data from Google products, sheets, and databases into shareable reports.', ['Free to Use', 'Many Data Connectors', 'Easy Report Sharing'], 'https://lookerstudio.google.com', 'lookerstudio.google.com'],

    /* ── Project Management & Knowledge ── */
    ['collaboration', 'Plane', 'Open Source', 'badge--oss', 'Open-source project and issue tracking tool positioned as an alternative to Jira and Linear.', ['Issues, Cycles & Modules', 'Self-Hostable', 'Clean Modern Interface'], 'https://plane.so', 'plane.so'],
    ['collaboration', 'Outline', 'Open Source', 'badge--oss', 'Fast, collaborative team wiki and knowledge base with a clean editor and strong search.', ['Markdown-Friendly Editor', 'Powerful Search', 'Slack & SSO Integrations'], 'https://www.getoutline.com', 'getoutline.com'],
    ['collaboration', 'Cal.com', 'Open Source', 'badge--oss', 'Open-source scheduling platform for booking meetings, with routing, team round-robin, and calendar sync.', ['Calendar Integrations', 'Team Scheduling', 'Self-Host Option'], 'https://cal.com', 'cal.com'],
    ['collaboration', 'Linear', 'Recommended SaaS', 'badge--saas', 'Fast, opinionated issue tracker and product planning tool favoured by modern product and engineering teams.', ['Keyboard-First Speed', 'Cycles & Roadmaps', 'GitHub Integration'], 'https://linear.app', 'linear.app'],

    /* ── Sales, Marketing & Growth ── */
    ['growth', 'Mautic', 'Open Source', 'badge--oss', 'Open-source marketing automation platform for email campaigns, lead scoring, and customer journeys.', ['Campaign Automation', 'Lead Scoring', 'Self-Hosted Control'], 'https://www.mautic.org', 'mautic.org'],
    ['growth', 'Listmonk', 'Open Source', 'badge--oss', 'High-performance, self-hosted newsletter and mailing list manager that keeps email costs low.', ['Handles Large Lists', 'Single Lightweight App', 'Own Your Subscriber Data'], 'https://listmonk.app', 'listmonk.app'],
    ['growth', 'Postiz', 'Open Source', 'badge--oss', 'Open-source social media scheduling tool for planning and publishing across your social channels.', ['Multi-Channel Scheduling', 'AI Writing Helpers', 'Self-Hostable'], 'https://postiz.com', 'postiz.com'],
    ['growth', 'Brevo', 'Freemium SaaS', 'badge--saas', 'Email, SMS, and marketing automation platform with a free tier that suits early-stage senders.', ['Email & SMS Campaigns', 'Free Starter Plan', 'Built-in CRM Basics'], 'https://www.brevo.com', 'brevo.com'],
    ['growth', 'Apollo.io', 'Freemium SaaS', 'badge--saas', 'Sales intelligence and outreach platform for finding prospects and running outbound sequences.', ['B2B Contact Database', 'Email Sequences', 'Lead Enrichment'], 'https://www.apollo.io', 'apollo.io'],

    /* ── HR, Payroll & People Ops ── */
    ['hr', 'Frappe HR', 'Open Source', 'badge--oss', 'Open-source HR and payroll software covering leave, attendance, hiring, and payroll processing.', ['Leave & Attendance', 'Payroll Processing', 'Pairs with ERPNext'], 'https://frappe.io/hr', 'frappe.io'],
    ['hr', 'OrangeHRM', 'Open Source', 'badge--oss', 'Long-running open-source HR management system for employee records, leave, and recruitment.', ['Employee Records', 'Leave Management', 'Recruitment Tracking'], 'https://www.orangehrm.com', 'orangehrm.com'],
    ['hr', 'Deel', 'Recommended SaaS', 'badge--saas', 'Global hiring, contractor payments, and compliant payroll for teams working across countries.', ['Contractor Payments', 'Global Payroll', 'Compliance Support'], 'https://www.deel.com', 'deel.com'],

    /* ── Security, Monitoring & Reliability ── */
    ['security', 'Grafana', 'Open Source', 'badge--oss', 'Open-source dashboards and observability for metrics, logs, and traces across your whole stack.', ['Metrics & Log Dashboards', 'Alerting', 'Huge Data Source Support'], 'https://grafana.com', 'grafana.com'],
    ['security', 'Uptime Kuma', 'Open Source', 'badge--oss', 'Easy-to-use self-hosted uptime monitor with alerts and public status pages for your services.', ['Uptime Checks', 'Instant Alerts', 'Public Status Pages'], 'https://uptime.kuma.pet', 'uptime.kuma.pet'],
    ['security', 'Sentry', 'Freemium SaaS', 'badge--saas', 'Application monitoring that captures errors and performance issues so engineers can fix them fast.', ['Error Tracking', 'Performance Tracing', 'Release Health'], 'https://sentry.io', 'sentry.io'],
    ['security', 'Bitwarden', 'Open Source', 'badge--oss', 'Open-source password manager with team vaults, sharing, and admin controls for company credentials.', ['Shared Team Vaults', 'Self-Host Option', 'SSO & Admin Controls'], 'https://bitwarden.com', 'bitwarden.com'],
    ['security', 'Keycloak', 'Open Source', 'badge--oss', 'Open-source identity and access management with single sign-on, user federation, and social login.', ['Single Sign-On', 'OAuth2 & OpenID Connect', 'Role-Based Access'], 'https://www.keycloak.org', 'keycloak.org'],

    /* ── Internal Tools, No-Code & Dev Workflow ── */
    ['devtools', 'Appsmith', 'Open Source', 'badge--oss', 'Open-source low-code platform for building internal tools, admin panels, and dashboards on your own data.', ['Drag-and-Drop UI', 'Connects to Databases & APIs', 'Self-Hostable'], 'https://www.appsmith.com', 'appsmith.com'],
    ['devtools', 'NocoDB', 'Open Source', 'badge--oss', 'Open-source Airtable alternative that turns any SQL database into a spreadsheet-style interface.', ['Spreadsheet Interface', 'Works on Your Database', 'REST API Included'], 'https://nocodb.com', 'nocodb.com'],
    ['devtools', 'Budibase', 'Open Source', 'badge--oss', 'Open-source low-code platform for building forms, approval flows, and internal business apps quickly.', ['Forms & Workflows', 'Role-Based Access', 'Self-Host or Cloud'], 'https://budibase.com', 'budibase.com'],
    ['devtools', 'GitHub', 'Freemium SaaS', 'badge--saas', 'The standard home for code with pull requests, CI/CD through Actions, and project planning built in.', ['Pull Requests & Reviews', 'CI/CD with Actions', 'Free Plan for Teams'], 'https://github.com', 'github.com'],
  ];

  $counters = [];
  foreach ($tools as $t) {
    list($cat, $name, $tag, $badge, $desc, $perks, $url, $site) = $t;

    if (empty($term_ids[$cat])) {
      continue;
    }

    // Skip if a tool with this title already exists.
    $exists = $wpdb->get_var($wpdb->prepare(
      "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'fnx_tool' AND post_status <> 'trash' AND post_title = %s LIMIT 1",
      $name
    ));
    if ($exists) {
      continue;
    }

    $counters[$cat] = isset($counters[$cat]) ? $counters[$cat] + 10 : 110;

    $post_id = wp_insert_post([
      'post_type'   => 'fnx_tool',
      'post_status' => 'publish',
      'post_title'  => $name,
      'menu_order'  => $counters[$cat],
    ]);
    if (is_wp_error($post_id) || !$post_id) {
      continue;
    }

    update_post_meta($post_id, '_fnx_tool_tag', fnx_tools_emoji_encode($tag));
    update_post_meta($post_id, '_fnx_tool_badge', $badge);
    update_post_meta($post_id, '_fnx_tool_desc', fnx_tools_emoji_encode($desc));
    update_post_meta($post_id, '_fnx_tool_perks', fnx_tools_emoji_encode(implode("\n", $perks)));
    update_post_meta($post_id, '_fnx_tool_url', $url);
    update_post_meta($post_id, '_fnx_tool_website', $site);
    wp_set_object_terms($post_id, [$term_ids[$cat]], 'fnx_tool_cat');
  }
}
add_action('init', 'fnx_tools_seed_expansion_v2', 30);
