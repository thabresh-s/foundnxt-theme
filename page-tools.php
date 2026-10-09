<?php
/**
 * Template Name: Business & Tech Recommendations
 * Template Post Type: page
 *
 * Dedicated Business Tools & Tech Stack Directory for FoundNXT.
 * Assign this template to your /tools/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_name   = get_bloginfo('name');
$site_url    = esc_url(home_url('/'));
$tools_url   = esc_url(get_permalink());

/* ── JSON-LD SCHEMA ── */
$schema = [
  '@context' => 'https://schema.org',
  '@graph'   => [
    [
      '@type'       => 'CollectionPage',
      '@id'         => $tools_url . '#tools',
      'url'         => $tools_url,
      'name'        => 'Business & Tech Recommendations — Curated Software & Open-Source Stack',
      'description' => 'Curated directory of essential business software, open-source tech stacks, and SaaS recommendations engineered to scale modern companies.',
      'inLanguage'  => 'en',
      'isPartOf'    => ['@id' => $site_url . '#website'],
      'publisher'   => [
        '@type' => 'Organization',
        'name'  => $site_name,
        'url'   => $site_url,
      ],
    ],
    [
      '@type'           => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => $site_url],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tools', 'item' => $tools_url],
      ],
    ],
  ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

/* ── TOOL CATEGORIES DATA MATRIX ── */
$tool_categories = [
  'crm' => [
    'title' => __('CRM & Customer Operations', 'foundnxt'),
    'icon'  => '💬',
    'tools' => [
      [
        'name'    => 'Twenty CRM',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Modern, self-hosted open-source CRM alternative to Salesforce & HubSpot built with modern web architecture.',
        'perks'   => ['Full Data Control', 'Custom Objects & Fields', 'Zero Per-Seat Costs'],
        'url'     => 'https://twenty.com',
        'website' => 'twenty.com',
      ],
      [
        'name'    => 'Chatwoot',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Open-source customer engagement suite with shared inbox, live chat, and multi-channel messaging support.',
        'perks'   => ['Omnichannel Messaging', 'Self-Hosted Option', 'WhatsApp & Email Sync'],
        'url'     => 'https://www.chatwoot.com',
        'website' => 'chatwoot.com',
      ],
      [
        'name'    => 'HubSpot CRM',
        'tag'     => 'Freemium SaaS',
        'badge'   => 'badge--saas',
        'desc'    => 'Industry-standard sales CRM, pipeline tracker, and marketing automation suite for fast-growing GTM teams.',
        'perks'   => ['Generous Free Tier', 'Rich Integration Ecosystem', 'Automated Workflows'],
        'url'     => 'https://www.hubspot.com',
        'website' => 'hubspot.com',
      ],
    ]
  ],
  'automation' => [
    'title' => __('AI, Automation & Workflow', 'foundnxt'),
    'icon'  => '⚡',
    'tools' => [
      [
        'name'    => 'n8n.io',
        'tag'     => 'Open Source / Fair-Code',
        'badge'   => 'badge--oss',
        'desc'    => 'Self-hostable node-based workflow automation tool with AI agent integrations and 400+ native connectors.',
        'perks'   => ['Native AI Nodes', 'Self-Hosted Privacy', 'Unlimited Workflow Runs'],
        'url'     => 'https://n8n.io',
        'website' => 'n8n.io',
      ],
      [
        'name'    => 'Flowise AI',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Drag-and-drop UI to build customized LLM chains, RAG pipelines, and autonomous AI agents.',
        'perks'   => ['Visual LangChain Builder', 'RAG Document QA', 'API Endpoint Deployment'],
        'url'     => 'https://flowiseai.com',
        'website' => 'flowiseai.com',
      ],
      [
        'name'    => 'Make (Integromat)',
        'tag'     => 'Recommended SaaS',
        'badge'   => 'badge--saas',
        'desc'    => 'Visual automation platform to visually design, build, and automate complex cross-application workflows.',
        'perks'   => ['Visual Data Flow', 'Cost-Effective Operations', 'Complex Logic Handling'],
        'url'     => 'https://www.make.com',
        'website' => 'make.com',
      ],
    ]
  ],
  'finance' => [
    'title' => __('Finance, Accounting & ERP', 'foundnxt'),
    'icon'  => '📊',
    'tools' => [
      [
        'name'    => 'ERPNext',
        'tag'     => 'Open Source ERP',
        'badge'   => 'badge--oss',
        'desc'    => '100% open-source enterprise resource planning suite covering accounting, inventory, HR, and payroll.',
        'perks'   => ['Complete Business ERP', 'Double-Entry Accounting', 'Modular Architecture'],
        'url'     => 'https://erpnext.com',
        'website' => 'erpnext.com',
      ],
      [
        'name'    => 'Invoice Ninja',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Open-source platform for invoicing, expense tracking, quotes, and recurring subscription billing.',
        'perks'   => ['Custom Invoice Templates', 'Online Payment Gateways', 'Self-Host Support'],
        'url'     => 'https://www.invoiceninja.com',
        'website' => 'invoiceninja.com',
      ],
      [
        'name'    => 'Stripe',
        'tag'     => 'Industry Standard',
        'badge'   => 'badge--pro',
        'desc'    => 'Financial infrastructure for the internet — accept payments, send payouts, and manage billing globally.',
        'perks'   => ['Global Payment Gateways', 'Subscription Billing', 'Fraud Protection'],
        'url'     => 'https://stripe.com',
        'website' => 'stripe.com',
      ],
    ]
  ],
  'infrastructure' => [
    'title' => __('Cloud, DevOps & Databases', 'foundnxt'),
    'icon'  => '🚀',
    'tools' => [
      [
        'name'    => 'Supabase',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Open-source Firebase alternative providing a dedicated Postgres database, Authentication, Instant APIs, and Storage.',
        'perks'   => ['Full Postgres Database', 'Realtime Subscriptions', 'Auto Generated APIs'],
        'url'     => 'https://supabase.com',
        'website' => 'supabase.com',
      ],
      [
        'name'    => 'Coolify',
        'tag'     => 'Open Source Self-Hosted',
        'badge'   => 'badge--oss',
        'desc'    => 'Self-hosted Heroku and Netlify alternative. Deploy applications, databases, and services on your own servers.',
        'perks'   => ['Own Your Infrastructure', 'Git Push Deployment', 'Zero Vendor Lock-in'],
        'url'     => 'https://coolify.io',
        'website' => 'coolify.io',
      ],
      [
        'name'    => 'Vercel / Cloudflare',
        'tag'     => 'Recommended SaaS',
        'badge'   => 'badge--saas',
        'desc'    => 'Edge network and frontend cloud platform for ultra-fast web deployments and serverless functions.',
        'perks'   => ['Instant Global CDN', 'Zero-Config Deployments', 'Sub-second Latency'],
        'url'     => 'https://vercel.com',
        'website' => 'vercel.com',
      ],
    ]
  ],
  'analytics' => [
    'title' => __('Analytics, Product & Marketing', 'foundnxt'),
    'icon'  => '📈',
    'tools' => [
      [
        'name'    => 'PostHog',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Single platform for session recording, product analytics, feature flags, A/B testing, and user surveys.',
        'perks'   => ['Product Analytics + Session Replay', 'Feature Flags & A/B Tests', 'Self-Host or Cloud'],
        'url'     => 'https://posthog.com',
        'website' => 'posthog.com',
      ],
      [
        'name'    => 'Plausible Analytics',
        'tag'     => 'Open Source / Privacy',
        'badge'   => 'badge--oss',
        'desc'    => 'Lightweight, privacy-friendly Google Analytics alternative that requires no cookies and is GDPR compliant.',
        'perks'   => ['< 1KB Script Weight', 'No Cookies / 100% GDPR', 'Clean Single-Dashboard UI'],
        'url'     => 'https://plausible.io',
        'website' => 'plausible.io',
      ],
      [
        'name'    => 'Mixpanel',
        'tag'     => 'Recommended SaaS',
        'badge'   => 'badge--saas',
        'desc'    => 'Powerful self-serve product analytics to track conversion funnels, retention cohorts, and user flows.',
        'perks'   => ['Deep Funnel Insights', 'Cohort Retention', 'Interactive Dashboards'],
        'url'     => 'https://mixpanel.com',
        'website' => 'mixpanel.com',
      ],
    ]
  ],
  'collaboration' => [
    'title' => __('Project Management & Knowledge', 'foundnxt'),
    'icon'  => '📁',
    'tools' => [
      [
        'name'    => 'AppFlowy',
        'tag'     => 'Open Source',
        'badge'   => 'badge--oss',
        'desc'    => 'Open-source Notion alternative built with Flutter & Rust. Keep total control over your business data.',
        'perks'   => ['Offline-First Support', '100% Data Ownership', 'Kanban & Wiki Boards'],
        'url'     => 'https://www.appflowy.io',
        'website' => 'appflowy.io',
      ],
      [
        'name'    => 'Mattermost',
        'tag'     => 'Open Source Workspace',
        'badge'   => 'badge--oss',
        'desc'    => 'Secure, open-source team collaboration and chat platform designed for high-security enterprise teams.',
        'perks'   => ['Self-Hosted Slack Alternative', 'Granular Security Controls', 'DevOps Toolchain Integration'],
        'url'     => 'https://mattermost.com',
        'website' => 'mattermost.com',
      ],
      [
        'name'    => 'Notion',
        'tag'     => 'Recommended SaaS',
        'badge'   => 'badge--saas',
        'desc'    => 'Connected workspace where better, faster work happens — docs, wikis, project management, and AI notes.',
        'perks'   => ['Flexible Database Views', 'AI Assistant Built-in', 'Centralized Team Knowledge'],
        'url'     => 'https://www.notion.so',
        'website' => 'notion.so',
      ],
    ]
  ]
];
?>

<div class="fnx-archive-page fnx-tools-directory-page" style="padding: 40px 0 80px;">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- HERO HEADER -->
    <header class="tools-dir-hero text-center fnx-reveal">
      <span class="hp-sec-tag-pill hp-sec-tag-pill--indigo">🛠️ <?php _e('Software & Tech Recommendations', 'foundnxt'); ?></span>
      <h1 class="tools-dir-title">
        <?php _e('Business & Tech', 'foundnxt'); ?> <span class="text-gradient"><?php _e('Stack Recommendations', 'foundnxt'); ?></span>
      </h1>
      <p class="tools-dir-lead">
        <?php _e('Discover essential software, open-source alternatives, and recommended SaaS products curated to help founders and operators scale faster while controlling costs.', 'foundnxt'); ?>
      </p>

      <!-- FILTER TABS -->
      <div class="tools-filter-nav" id="tools-filter-nav">
        <button class="tools-filter-pill active" data-filter="all">✨ <?php _e('All Categories', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="oss">🔓 <?php _e('Open Source Only', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="crm">💬 <?php _e('CRM & Ops', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="automation">⚡ <?php _e('AI & Automation', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="finance">📊 <?php _e('Finance & ERP', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="infrastructure">🚀 <?php _e('Cloud & Infra', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="analytics">📈 <?php _e('Analytics', 'foundnxt'); ?></button>
        <button class="tools-filter-pill" data-filter="collaboration">📁 <?php _e('Project & Docs', 'foundnxt'); ?></button>
      </div>
    </header>

    <!-- CATEGORY MATRIX SECTIONS -->
    <div class="tools-directory-body">
      <?php foreach ($tool_categories as $cat_key => $cat_data): ?>
        <section class="tools-cat-block fnx-reveal" data-category="<?php echo esc_attr($cat_key); ?>">
          <div class="cat-block-header">
            <span class="cat-icon"><?php echo esc_html($cat_data['icon']); ?></span>
            <h2 class="cat-title"><?php echo esc_html($cat_data['title']); ?></h2>
          </div>

          <div class="tools-grid-3">
            <?php foreach ($cat_data['tools'] as $tool):
              $is_oss = strpos(strtolower($tool['tag']), 'open source') !== false;
              $card_filter_class = $is_oss ? 'is-oss-card' : 'is-saas-card';
            ?>
              <article class="tool-recommend-card <?php echo esc_attr($card_filter_class); ?>">
                <div class="tool-card-top">
                  <span class="tool-tag-pill <?php echo esc_attr($tool['badge']); ?>">
                    <?php echo esc_html($tool['tag']); ?>
                  </span>
                </div>
                
                <h3 class="tool-name"><?php echo esc_html($tool['name']); ?></h3>
                <p class="tool-desc"><?php echo esc_html($tool['desc']); ?></p>

                <ul class="tool-perks-list">
                  <?php foreach ($tool['perks'] as $perk): ?>
                    <li><span class="perk-check">✓</span> <?php echo esc_html($perk); ?></li>
                  <?php endforeach; ?>
                </ul>

                <div class="tool-card-footer">
                  <a href="<?php echo esc_url($tool['url']); ?>" target="_blank" rel="noopener noreferrer" class="tool-visit-link">
                    <?php _e('Visit', 'foundnxt'); ?> <?php echo esc_html($tool['website']); ?> ↗
                  </a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endforeach; ?>
    </div>

    <!-- ADVISORY & CUSTOM STACK CTA -->
    <div class="tools-cta-box fnx-reveal">
      <div class="tools-cta-content">
        <span class="hp-sec-tag-pill hp-sec-tag-pill--violet">💡 <?php _e('Custom Tech Stack Advisory', 'foundnxt'); ?></span>
        <h2><?php _e('Need Help Selecting the Right Stack for Your Business?', 'foundnxt'); ?></h2>
        <p><?php _e('Our advisory team performs technical architecture reviews, SaaS cost audits, and custom build-vs-buy evaluations tailored to your company stage.', 'foundnxt'); ?></p>
      </div>
      <div class="tools-cta-action">
        <a href="<?php echo esc_url(add_query_arg('help', urlencode('Tech & AI Strategy'), home_url('/contact/'))); ?>" class="btn-primary">
          <?php _e('Request Tech Stack Audit', 'foundnxt'); ?> →
        </a>
      </div>
    </div>

  </div>
</div>

<!-- INLINE INTERACTIVE FILTER SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const filterPills = document.querySelectorAll('#tools-filter-nav .tools-filter-pill');
  const catBlocks   = document.querySelectorAll('.tools-cat-block');

  filterPills.forEach(pill => {
    pill.addEventListener('click', function() {
      filterPills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');

      catBlocks.forEach(block => {
        const catKey = block.getAttribute('data-category');
        const cards  = block.querySelectorAll('.tool-recommend-card');

        if (filter === 'all') {
          block.style.display = 'block';
          cards.forEach(card => card.style.display = 'flex');
        } else if (filter === 'oss') {
          let visibleCount = 0;
          cards.forEach(card => {
            if (card.classList.contains('is-oss-card')) {
              card.style.display = 'flex';
              visibleCount++;
            } else {
              card.style.display = 'none';
            }
          });
          block.style.display = visibleCount > 0 ? 'block' : 'none';
        } else {
          if (catKey === filter) {
            block.style.display = 'block';
            cards.forEach(card => card.style.display = 'flex');
          } else {
            block.style.display = 'none';
          }
        }
      });
    });
  });
});
</script>

<?php get_footer(); ?>

