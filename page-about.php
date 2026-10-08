<?php
/**
 * Template Name: About Page (SEO)
 * Template Post Type: page
 *
 * SEO-optimised About page for FoundNXT.
 * Assign this template to your /about/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

/* ── PERSON + WEBSITE SCHEMA ─────────────────────────── */
$logo_url  = esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'));
$site_url  = esc_url(home_url('/'));
$about_url = esc_url(get_permalink());

$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Person',
            '@id'         => $site_url . '#thabresh-syed',
            'name'        => 'FoundNXT Editorial Team',
            'jobTitle'    => 'Business Consultant — Scale, Valuation & Funding',
            'description' => 'Computer science background, MBA in product management, and startup consulting experience. Reads obsessively about startups, funding, valuation, and business growth. Freelancer for tech, helping startups with infrastructure, cloud, operations, valuation, and MVPs. Editorial team at FoundNXT.',
            'url'         => $about_url,
            'sameAs'      => [
                $site_url,
            ],
            'knowsAbout'  => [
                'Business Scale & Operations', 'Startup Valuation', 'Funding Strategy',
                'Technology Adoption', 'Marketing & Branding', 'Venture Capital',
                'Business Growth', 'Go-to-Market Strategy',
            ],
            'worksFor'    => [
                '@type' => 'Organization',
                'name'  => 'FoundNXT',
                'url'   => $site_url,
            ],
        ],
        [
            '@type'       => 'WebSite',
            '@id'         => $site_url . '#website',
            'name'        => 'FoundNXT',
            'url'         => $site_url,
            'description' => 'Practical insights on startup funding, valuation, business growth, and scaling — for founders, investors, and entrepreneurs.',
            'author'      => ['@id' => $site_url . '#thabresh-syed'],
            'publisher'   => [
                '@type' => 'Organization',
                'name'  => 'FoundNXT',
                'logo'  => ['@type' => 'ImageObject', 'url' => $logo_url],
            ],
            'inLanguage'  => 'en-IN',
        ],
        [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => $site_url],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => $about_url],
            ],
        ],
    ],
];
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
?>

<div class="fnx-about-page">
  <div class="container">

    <?php fnx_breadcrumbs(); ?>

    <!-- ══════════════════════════════════════════
         SECTION 1 — HERO: Author + Intro
    ══════════════════════════════════════════ -->
    <section class="about-hero" aria-labelledby="about-h1">
      <div class="about-hero-text">
        <span class="about-eyebrow"><?php _e('About FoundNXT', 'foundnxt'); ?></span>
        <h1 class="about-h1" id="about-h1">
          <?php _e('FoundNXT Editorial Team — Consulting & Insights', 'foundnxt'); ?>
        </h1>
        <p class="about-lead">
          <?php _e('Computer science background. Someone who found his language in books — startups, funding, valuation, and business growth. We started FoundNXT to share what we keep learning.', 'foundnxt'); ?>
        </p>
        <div class="about-badges">
          <span class="about-badge about-badge--green"><?php _e('Business Consultant', 'foundnxt'); ?></span>
          <span class="about-badge about-badge--blue"><?php _e('Cloud & Infra', 'foundnxt'); ?></span>
          <span class="about-badge about-badge--amber"><?php _e('Funding & Valuation Writer', 'foundnxt'); ?></span>
          <span class="about-badge about-badge--muted"><?php _e('Startup Focused', 'foundnxt'); ?></span>
        </div>
      </div>
      <div class="about-hero-avatar">
        <?php
        $avatar = get_avatar_url(get_the_author_meta('email', 1), ['size' => 200]);
        ?>
        <div class="about-avatar-wrap">
          <img src="<?php echo esc_url($avatar); ?>"
               alt="<?php esc_attr_e('FoundNXT Editorial Team — Author of FoundNXT', 'foundnxt'); ?>"
               width="200" height="200" loading="eager" itemprop="image">
        </div>
        <div class="about-avatar-name">FoundNXT Editorial Team</div>
        <div class="about-avatar-role"><?php _e('Consulting Team, FoundNXT', 'foundnxt'); ?></div>
      </div>
    </section>


    <!-- ══════════════════════════════════════════
         SECTION 1B — Personal Story
    ══════════════════════════════════════════ -->
    <section class="about-section about-story-section" aria-labelledby="about-story-h2">
      <div class="about-story-inner">
        <div class="about-story-text">
          <span class="about-eyebrow"><?php _e('The Story', 'foundnxt'); ?></span>
          <h2 class="about-section-h2" id="about-story-h2">
            <?php _e('It started with a book. Then another. Then I could not stop.', 'foundnxt'); ?>
          </h2>
          <p><?php _e('I am not someone who learns well in a classroom. I learn by reading — obsessively, at odd hours, across subjects that most people keep separate. Finance. Startups. Product design. Valuation models. Business operations. I kept filling notebooks with things I wished someone had explained earlier.', 'foundnxt'); ?></p>
          <p><?php _e('My background is computer science. I think in systems. When I got into product management and then finance through my MBA, I realised the same mental models connect all of it — how products scale, how companies are valued, how money moves, how operations either compound your growth or quietly kill it.', 'foundnxt'); ?></p>
          <p><?php _e('As an introvert, I do not network loudly. I think quietly, then write. FoundNXT became the place where everything absorbed — from books, from consulting projects, from late-night rabbit holes — gets organised into something useful for founders, professionals, and people who want to understand how business and money actually work.', 'foundnxt'); ?></p>
          <p><?php _e('As a freelancer, I work on the technical side of startups — cloud infrastructure, scalable systems, operations, MVPs. That hands-on work keeps everything I write grounded in what actually happens inside early-stage companies, not theory.', 'foundnxt'); ?></p>
        </div>
        <div class="about-story-timeline">
          <div class="about-tl-item">
            <div class="about-tl-dot about-tl-dot--green"></div>
            <div class="about-tl-body">
              <div class="about-tl-label"><?php _e('Foundation', 'foundnxt'); ?></div>
              <div class="about-tl-title"><?php _e('Computer Science', 'foundnxt'); ?></div>
              <div class="about-tl-desc"><?php _e('Systems thinking, problem solving, and a bias for building over talking.', 'foundnxt'); ?></div>
            </div>
          </div>
          <div class="about-tl-item">
            <div class="about-tl-dot about-tl-dot--blue"></div>
            <div class="about-tl-body">
              <div class="about-tl-label"><?php _e('Graduate', 'foundnxt'); ?></div>
              <div class="about-tl-title"><?php _e('MBA — Product Management', 'foundnxt'); ?></div>
              <div class="about-tl-desc"><?php _e('Where tech instincts met business strategy, product thinking, and market dynamics.', 'foundnxt'); ?></div>
            </div>
          </div>
          <div class="about-tl-item">
            <div class="about-tl-dot about-tl-dot--amber"></div>
            <div class="about-tl-body">
              <div class="about-tl-label"><?php _e('Deep Dive', 'foundnxt'); ?></div>
              <div class="about-tl-title"><?php _e('Startup Funding & Valuation', 'foundnxt'); ?></div>
              <div class="about-tl-desc"><?php _e('Valuation models, funding mechanics, and cap tables — self-taught through books and real deal research.', 'foundnxt'); ?></div>
            </div>
          </div>
          <div class="about-tl-item">
            <div class="about-tl-dot about-tl-dot--green"></div>
            <div class="about-tl-body">
              <div class="about-tl-label"><?php _e('Today', 'foundnxt'); ?></div>
              <div class="about-tl-title"><?php _e('Consultant & Founder of FoundNXT', 'foundnxt'); ?></div>
              <div class="about-tl-desc"><?php _e('Advising founders on scale, valuation, and funding by day, writing what we learn on FoundNXT for founders and investors.', 'foundnxt'); ?></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         SECTION 2 — What We Do (Consulting)
    ══════════════════════════════════════════ -->
    <section class="about-section" aria-labelledby="about-work-h2">
      <div class="about-section-header">
        <h2 class="about-section-h2" id="about-work-h2">
          <?php _e('What We Do as Consultants', 'foundnxt'); ?>
        </h2>
        <p class="about-section-sub">
          <?php _e('Working with founders and operators on the disciplines that actually move a company forward — scale, valuation, funding, technology, and brand.', 'foundnxt'); ?>
        </p>
      </div>

      <div class="about-services-grid">

        <div class="about-service-card">
          <div class="about-service-icon about-service-icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M6 21V10l6-5 6 5v11M10 21v-6h4v6"/></svg>
          </div>
          <h3><?php _e('Scale & Operations', 'foundnxt'); ?></h3>
          <p><?php _e('Auditing operations, finding the bottlenecks that cap growth, and building the SOPs and systems that let you scale headcount and revenue without chaos.', 'foundnxt'); ?></p>
        </div>

        <div class="about-service-card">
          <div class="about-service-icon about-service-icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <h3><?php _e('Valuation', 'foundnxt'); ?></h3>
          <p><?php _e('Independent, defensible valuations for fundraising, ESOP pools, and M&A — with the models and assumptions laid out clearly.', 'foundnxt'); ?></p>
        </div>

        <div class="about-service-card">
          <div class="about-service-icon about-service-icon--amber">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
          </div>
          <h3><?php _e('Funding Strategy', 'foundnxt'); ?></h3>
          <p><?php _e('Pitch deck and data room review, investor targeting, and term sheet support so your round moves faster with less friction.', 'foundnxt'); ?></p>
        </div>

        <div class="about-service-card">
          <div class="about-service-icon about-service-icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          </div>
          <h3><?php _e('Technology Adoption', 'foundnxt'); ?></h3>
          <p><?php _e('Mapping workflows to the AI tools, automation, and infrastructure that actually move the needle — with a clear ROI case before you commit budget.', 'foundnxt'); ?></p>
        </div>

        <div class="about-service-card">
          <div class="about-service-icon about-service-icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </div>
          <h3><?php _e('Marketing & Branding', 'foundnxt'); ?></h3>
          <p><?php _e('Brand audits, category positioning, and go-to-market strategy — so your marketing spend compounds instead of chasing a message that isn\'t landing.', 'foundnxt'); ?></p>
        </div>

        <div class="about-service-card">
          <div class="about-service-icon about-service-icon--amber">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          </div>
          <h3><?php _e('Execution Support', 'foundnxt'); ?></h3>
          <p><?php _e('We stay close through implementation — not a report that sits in a drawer. Structured check-ins until the plan is actually running.', 'foundnxt'); ?></p>
        </div>

      </div>

      <div style="text-align:center;margin-top:32px;">
        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-primary about-cta-btn">
          <?php _e('See Full Services →', 'foundnxt'); ?>
        </a>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         SECTION 3 — About FoundNXT
    ══════════════════════════════════════════ -->
    <section class="about-section about-site-section" aria-labelledby="about-site-h2">
      <div class="about-site-inner">
        <div class="about-site-text">
          <span class="about-eyebrow"><?php _e('The Blog', 'foundnxt'); ?></span>
          <h2 class="about-section-h2" id="about-site-h2">
            <?php _e('What is FoundNXT?', 'foundnxt'); ?>
          </h2>
          <p>
            <?php _e('FoundNXT is a practical knowledge blog built for <strong>founders, startups, and entrepreneurs</strong> who want to grow smarter — not just in funding, but operationally and strategically.', 'foundnxt'); ?>
          </p>
          <p>
            <?php _e('Every article is written from real consulting and startup experience — not recycled theory. The goal is to give you the kind of clarity that usually only comes from working inside a startup or sitting across from an investor.', 'foundnxt'); ?>
          </p>
          <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-primary about-cta-btn">
            <?php _e('Browse All Articles →', 'foundnxt'); ?>
          </a>
        </div>
        <div class="about-topics-grid">
          <?php
          $topics = [
            ['icon' => '🚀', 'label' => __('Startup Scaling',          'foundnxt'), 'desc' => __('Growth frameworks, hiring, and systems for scaling Indian startups.', 'foundnxt'), 'cat' => 'startups'],
            ['icon' => '📣', 'label' => __('Digital Marketing',        'foundnxt'), 'desc' => __('SEO, content, social, and paid growth strategies that work in India.', 'foundnxt'), 'cat' => 'business'],
            ['icon' => '🏷️', 'label' => __('Branding',                 'foundnxt'), 'desc' => __('Building a brand that earns trust and differentiates in crowded markets.', 'foundnxt'), 'cat' => 'business'],
            ['icon' => '⚙️', 'label' => __('Tech Adoption',            'foundnxt'), 'desc' => __('Which tools, platforms, and automations actually move the needle.', 'foundnxt'), 'cat' => 'technology'],
            ['icon' => '💰', 'label' => __('Cost Optimisation',        'foundnxt'), 'desc' => __('Cutting operational costs without cutting corners for lean teams.', 'foundnxt'), 'cat' => 'business'],
            ['icon' => '📊', 'label' => __('Analytics & Data',         'foundnxt'), 'desc' => __('Using data to make better product, marketing, and investment decisions.', 'foundnxt'), 'cat' => 'technology'],
            ['icon' => '📈', 'label' => __('Business Growth',          'foundnxt'), 'desc' => __('Growth strategy, market expansion, and scaling playbooks for founders.', 'foundnxt'), 'cat' => 'growth'],
            ['icon' => '💼', 'label' => __('Funding & Valuation',      'foundnxt'), 'desc' => __('How startup funding works — rounds, terms, valuation, and investor relations.', 'foundnxt'), 'cat' => 'funding'],
          ];
          foreach ($topics as $t):
            $cat_obj = get_category_by_slug($t['cat']);
            $cat_url = $cat_obj ? get_category_link($cat_obj->term_id) : home_url('/articles/');
          ?>
          <a href="<?php echo esc_url($cat_url); ?>" class="about-topic-chip">
            <span class="about-topic-icon"><?php echo $t['icon']; ?></span>
            <div>
              <div class="about-topic-label"><?php echo esc_html($t['label']); ?></div>
              <div class="about-topic-desc"><?php echo esc_html($t['desc']); ?></div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         SECTION 4 — Who This Is For
    ══════════════════════════════════════════ -->
    <section class="about-section about-audience-section" aria-labelledby="about-audience-h2">
      <h2 class="about-section-h2 about-section-h2--center" id="about-audience-h2">
        <?php _e('Who FoundNXT Is For', 'foundnxt'); ?>
      </h2>
      <div class="about-audience-grid">
        <div class="about-audience-card">
          <div class="about-audience-num">01</div>
          <h3><?php _e('Startup Founders', 'foundnxt'); ?></h3>
          <p><?php _e('Early-stage founders navigating fundraising, product decisions, team building, and finding product-market fit in India\'s startup ecosystem.', 'foundnxt'); ?></p>
        </div>
        <div class="about-audience-card">
          <div class="about-audience-num">02</div>
          <h3><?php _e('Entrepreneurs & SMEs', 'foundnxt'); ?></h3>
          <p><?php _e('Business owners looking to scale operations, cut costs, adopt the right technology, and build a sustainable competitive advantage.', 'foundnxt'); ?></p>
        </div>
        <div class="about-audience-card">
          <div class="about-audience-num">03</div>
          <h3><?php _e('Investors & VCs', 'foundnxt'); ?></h3>
          <p><?php _e('Angel investors and VC professionals who want fast, clear coverage of funding rounds, valuations, and the deals shaping the startup ecosystem.', 'foundnxt'); ?></p>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         SECTION 5 — Recent Articles
    ══════════════════════════════════════════ -->
    <section class="about-section" aria-labelledby="about-recent-h2">
      <div class="about-section-header about-section-header--row">
        <h2 class="about-section-h2" id="about-recent-h2">
          <?php _e('Recent Articles', 'foundnxt'); ?>
        </h2>
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="ap-cat-viewall">
          <?php _e('View all →', 'foundnxt'); ?>
        </a>
      </div>
      <div class="about-posts-grid">
        <?php
        $recent = get_posts(['posts_per_page' => 3, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC']);
        foreach ($recent as $rp):
          $rc  = get_the_category($rp->ID);
          $rn  = $rc ? $rc[0]->name : 'Article';
          $ru  = $rc ? get_category_link($rc[0]->term_id) : '#';
        ?>
        <article class="post-card" itemscope itemtype="https://schema.org/Article">
          <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>" class="post-card-thumb-link" tabindex="-1" aria-hidden="true">
            <?php if (has_post_thumbnail($rp->ID)): ?>
              <div class="post-card-thumb"><?php echo get_the_post_thumbnail($rp->ID, 'fnx-card', ['loading' => 'lazy', 'itemprop' => 'image']); ?></div>
            <?php else: ?>
              <div class="post-card-thumb"><div class="post-card-thumb-placeholder">📰</div></div>
            <?php endif; ?>
          </a>
          <div class="post-card-body">
            <div class="post-card-meta">
              <a href="<?php echo esc_url($ru); ?>" class="post-card-cat" itemprop="articleSection"><?php echo esc_html($rn); ?></a>
              <time class="post-card-date" datetime="<?php echo get_the_date('c', $rp->ID); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $rp->ID); ?></time>
            </div>
            <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>" class="post-card-title" itemprop="headline"><?php echo esc_html(get_the_title($rp->ID)); ?></a>
            <p class="post-card-excerpt" itemprop="description"><?php echo wp_trim_words(get_the_excerpt($rp->ID), 18); ?></p>
            <div class="post-card-footer">
              <span class="post-card-author"><span itemprop="author">FoundNXT Editorial Team</span></span>
              <span class="post-card-arrow"><?php _e('Read →', 'foundnxt'); ?></span>
            </div>
          </div>
        </article>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
    </section>

    <!-- ══════════════════════════════════════════
         SECTION 6 — Contact / CTA
    ══════════════════════════════════════════ -->
    <section class="about-cta-section" aria-labelledby="about-cta-h2">
      <div class="about-cta-inner">
        <h2 class="about-cta-h2" id="about-cta-h2">
          <?php _e('Ready to scale, raise, or grow? Let\'s talk.', 'foundnxt'); ?>
        </h2>
        <p class="about-cta-sub">
          <?php _e('Whether you\'re raising a round, hitting operational bottlenecks, or rethinking your brand — available for consulting engagements.', 'foundnxt'); ?>
        </p>
        <div class="about-cta-btns">
          <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-primary">
            <?php _e('Get in Touch →', 'foundnxt'); ?>
          </a>
          <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-outline">
            <?php _e('Read the Blog', 'foundnxt'); ?>
          </a>
        </div>
      </div>
    </section>

  </div><!-- /.container -->
</div><!-- /.fnx-about-page -->

<?php get_footer(); ?>
