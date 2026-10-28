<?php get_header(); ?>

<!-- Reading Progress Bar -->
<div class="fnx-reading-progress-track fnx-progress-bar" id="reading-progress" role="progressbar" aria-label="Reading progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  <div class="fnx-reading-progress-bar progress-fill" id="progress-fill"></div>
</div>

<?php while (have_posts()): the_post(); ?>

<?php
$cats       = get_the_category();
$primary_c  = !empty($cats) ? $cats[0] : null;
$cat_slug   = $primary_c ? $primary_c->slug : 'startups-funding';
$cat_name   = $primary_c ? $primary_c->name : __('Insights', 'foundnxt');
$cat_color  = fnx_get_category_color($cat_slug);
$badge      = get_post_meta(get_the_ID(), 'fnx_post_badge', true);
$takeaway   = get_post_meta(get_the_ID(), 'fnx_key_takeaway', true);
$read_time  = function_exists('fnx_read_time') ? fnx_read_time() : '5 min read';
$author_id  = get_the_author_meta('ID');
$author_name = get_the_author();
$author_bio = get_the_author_meta('description') ?: __('Writes about startups, technology, valuation, and market shifts for founders and operators.', 'foundnxt');

// Topic-awareness for inline CTA
$tech_slugs = ['ai-tech', 'technology-ai', 'ai', 'deeptech'];
$finance_slugs = ['markets-valuation', 'valuation-finance', 'markets-economy', 'startups-funding', 'ipo-markets'];

$is_tech_topic = in_array($cat_slug, $tech_slugs, true);
$is_finance_topic = in_array($cat_slug, $finance_slugs, true);
?>

<!-- ══════════════════════════════════════════════════
     POST HERO — Hook Headline, Category Tag, Author, Date, Read Time
══════════════════════════════════════════════════ -->
<header class="fnx-post-hero fnx-post-hero--simple" style="padding: 44px 0 24px;">
  <div class="phb-simple-outer container">
    <?php if (function_exists('fnx_breadcrumbs')) { fnx_breadcrumbs(); } ?>

    <!-- Category Tag with Vibrant Accent Color -->
    <div style="margin-bottom: 16px;">
      <a href="<?php echo esc_url(get_category_link($primary_c->term_id)); ?>" class="phb-chip phb-chip--topic" style="background: <?php echo esc_attr($cat_color); ?>18; color: <?php echo esc_attr($cat_color); ?>; border: 1px solid <?php echo esc_attr($cat_color); ?>35; font-weight: 700;">
        <?php echo esc_html($cat_name); ?>
      </a>
      <?php if ($badge): ?>
        <span class="phb-chip" style="background: rgba(255, 255, 255, 0.08);"><?php echo esc_html($badge); ?></span>
      <?php endif; ?>
    </div>

    <!-- Hook Headline -->
    <h1 class="phb-title" style="font-size: clamp(2.1rem, 4.5vw, 3.4rem); font-weight: 800; line-height: 1.15; margin-bottom: 24px; color: var(--text);">
      <?php the_title(); ?>
    </h1>

    <!-- Meta row: Author Avatar + Name, Date, Read Time -->
    <div class="phb-meta-row" style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
      <div style="display: flex; align-items: center; gap: 10px;">
        <?php echo get_avatar($author_id, 36, '', '', ['class' => 'author-mini-avatar', 'style' => 'border-radius: 50%;']); ?>
        <span style="font-weight: 600; font-size: 0.9375rem; color: var(--text);"><?php echo esc_html($author_name); ?></span>
      </div>
      <span style="color: var(--muted);">&bull;</span>
      <span style="font-size: 0.875rem; color: var(--muted);">
        <?php printf(__('Published %s', 'foundnxt'), get_the_date('M j, Y')); ?>
      </span>
      <?php if (get_the_modified_date('U') > get_the_date('U')): ?>
        <span style="color: var(--muted);">&bull;</span>
        <span style="font-size: 0.875rem; color: var(--muted);">
          <?php printf(__('Updated %s', 'foundnxt'), get_the_modified_date('M j, Y')); ?>
        </span>
      <?php endif; ?>
      <span style="color: var(--muted);">&bull;</span>
      <span style="font-size: 0.875rem; color: var(--muted);">
        ⏱️ <?php echo esc_html($read_time); ?>
      </span>
    </div>

  </div><!-- /.phb-simple-outer -->
</header>

<!-- ══════════════════════════════════════════════════
     POST BODY LAYOUT
══════════════════════════════════════════════════ -->
<div class="fnx-post-body" style="padding-top: 20px;">
  <div class="container">
    <div class="post-layout" style="display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 48px; align-items: start;">

      <!-- Main Article Column -->
      <article class="post-content-wrap single-article-container" id="post-content" itemscope itemtype="https://schema.org/Article">
        <meta itemprop="headline"      content="<?php echo esc_attr(get_the_title()); ?>">
        <meta itemprop="datePublished" content="<?php echo get_the_date('c'); ?>">
        <meta itemprop="dateModified"  content="<?php echo get_the_modified_date('c'); ?>">
        <meta itemprop="author"        content="<?php echo esc_attr($author_name); ?>">

        <!-- ── TL;DR BOX WITH 3 BULLETS ── -->
        <div class="post-tldr-box" style="background: color-mix(in srgb, <?php echo esc_attr($cat_color); ?> 10%, var(--surface)); border-left: 4px solid <?php echo esc_attr($cat_color); ?>;">
          <div class="post-tldr-label" style="color: <?php echo esc_attr($cat_color); ?>;">
            <span>⚡ <?php _e('TL;DR — Executive Summary', 'foundnxt'); ?></span>
          </div>
          <ul class="post-tldr-bullets">
            <li><strong><?php _e('Core Thesis:', 'foundnxt'); ?></strong> <?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_title(), 22)); ?></li>
            <li><strong><?php _e('Operator Insight:', 'foundnxt'); ?></strong> <?php _e('Strategic implementation blueprints, defensible economics, and execution benchmarks analyzed below.', 'foundnxt'); ?></li>
            <li><strong><?php _e('Key Risk & Action:', 'foundnxt'); ?></strong> <?php _e('Avoid common scaling traps by validating unit margins and tech architecture before capital deployment.', 'foundnxt'); ?></li>
          </ul>
        </div>

        <!-- Mobile Table of Contents -->
        <details class="fnx-mobile-toc" id="fnx-mobile-toc" style="margin: 24px 0;">
          <summary class="fnx-mobile-toc-summary">
            <span>📑 <?php _e('Table of Contents', 'foundnxt'); ?></span>
            <span class="toc-arrow" aria-hidden="true">&darr;</span>
          </summary>
          <div class="fnx-mobile-toc-content">
            <ul class="toc-mobile-list" id="toc-mobile-list">
              <li><span class="toc-empty-msg"><?php _e('Loading headings…', 'foundnxt'); ?></span></li>
            </ul>
          </div>
        </details>

        <!-- Article Content -->
        <div class="post-content entry-content article-body-content" itemprop="articleBody">
          <?php the_content(); ?>
        </div>

        <!-- ── KEY TAKEAWAYS BOX ── -->
        <div class="fnx-callout callout-takeaway" style="margin: 40px 0; padding: 24px 28px; border-radius: 16px; background: color-mix(in srgb, #14B8A6 10%, var(--surface)); border-left: 4px solid #14B8A6;">
          <div style="font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #14B8A6; font-size: 0.8125rem; margin-bottom: 8px;">
            💡 <?php _e('Key Takeaways for Leaders', 'foundnxt'); ?>
          </div>
          <p style="font-size: 1rem; color: var(--text); line-height: 1.6; margin: 0;">
            <?php echo esc_html($takeaway ?: __('Evaluate systems from first principles. Maintain disciplined burn, build defensible data feedback loops, and prioritize sustainable unit economics over vanity metrics.', 'foundnxt')); ?>
          </p>
        </div>

        <!-- ── TOPIC-AWARE INLINE CONSULTING CTA BOX ── -->
        <?php if ($is_tech_topic): ?>
          <div class="post-consulting-cta-box" style="--cta-accent: #7C3AED;">
            <div class="post-consulting-icon">⚡</div>
            <div>
              <h3 class="post-consulting-title"><?php _e('Accelerate Your Tech & AI Architecture', 'foundnxt'); ?></h3>
              <p class="post-consulting-desc">
                <?php _e('Evaluating AI integrations, LLM workflows, or cloud infrastructure constraints? Our technical practice helps teams design scalable, defensible systems.', 'foundnxt'); ?>
              </p>
              <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-post-consulting">
                <span><?php _e('Explore Tech Consulting', 'foundnxt'); ?></span> &rarr;
              </a>
            </div>
          </div>
        <?php elseif ($is_finance_topic): ?>
          <div class="post-consulting-cta-box" style="--cta-accent: #F59E0B;">
            <div class="post-consulting-icon">📈</div>
            <div>
              <h3 class="post-consulting-title"><?php _e('Refine Your Unit Economics & Valuation', 'foundnxt'); ?></h3>
              <p class="post-consulting-desc">
                <?php _e('Preparing for fundraising or scaling growth? Book a confidential 1:1 advisory sprint on cap table modeling, margins, and market positioning.', 'foundnxt'); ?>
              </p>
              <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn-post-consulting" style="background: #F59E0B; color: #0B1020;">
                <span><?php _e('Explore Business Consulting', 'foundnxt'); ?></span> &rarr;
              </a>
            </div>
          </div>
        <?php else: ?>
          <div class="post-consulting-cta-box" style="--cta-accent: #FF6B6B;">
            <div class="post-consulting-icon">🎯</div>
            <div>
              <h3 class="post-consulting-title"><?php _e('Scaling Your Venture in the AI Era?', 'foundnxt'); ?></h3>
              <p class="post-consulting-desc">
                <?php _e('Speak directly with our strategy practice about growth bottlenecks, organizational systems, and execution playbooks.', 'foundnxt'); ?>
              </p>
              <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-post-consulting" style="background: #FF6B6B;">
                <span><?php _e('Book a Free 20-Min Call', 'foundnxt'); ?></span> &rarr;
              </a>
            </div>
          </div>
        <?php endif; ?>

        <!-- Disclaimer for finance/markets posts -->
        <?php if ($is_finance_topic): ?>
          <div style="margin: 28px 0; padding: 18px 22px; border-radius: 12px; background: rgba(245, 158, 11, 0.08); border-left: 3px solid #F59E0B; font-size: 0.8125rem; color: var(--muted); line-height: 1.55;">
            <strong style="color: #D97706;"><?php _e('Editorial Disclaimer:', 'foundnxt'); ?></strong>
            <?php _e('FoundNXT publishes independent business intelligence and teardowns. Content does not constitute financial, investment, accounting, or legal advice. Consult certified professionals before executing financial transactions.', 'foundnxt'); ?>
          </div>
        <?php endif; ?>

        <!-- Social Share Bar -->
        <div class="post-share" style="margin: 32px 0; padding: 20px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
          <span style="font-weight: 700; font-size: 0.9375rem; color: var(--text);"><?php _e('Share this breakdown:', 'foundnxt'); ?></span>
          <div style="display: flex; gap: 10px;">
            <a href="https://x.com/intent/post?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="btn-hero-secondary" style="padding: 8px 14px; font-size: 0.8125rem;">X / Twitter</a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="btn-hero-secondary" style="padding: 8px 14px; font-size: 0.8125rem;">LinkedIn</a>
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="btn-hero-secondary" style="padding: 8px 14px; font-size: 0.8125rem;">WhatsApp</a>
          </div>
        </div>

        <!-- ── AUTHOR BOX ── -->
        <div class="post-author-card" style="display: flex; gap: 20px; align-items: center; padding: 28px; border-radius: 18px; background: var(--surface); border: 1px solid var(--border); margin: 40px 0;">
          <?php echo get_avatar($author_id, 72, '', '', ['class' => 'author-card-avatar', 'style' => 'border-radius: 50%;']); ?>
          <div class="author-card-info">
            <h4 style="font-size: 1.125rem; font-weight: 700; margin: 0 0 4px; color: var(--text);"><?php echo esc_html($author_name); ?></h4>
            <span style="font-size: 0.8125rem; font-weight: 600; color: var(--accent-violet); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px;"><?php _e('Editorial Practice, FoundNXT', 'foundnxt'); ?></span>
            <p style="font-size: 0.875rem; color: var(--muted); margin: 0 0 10px; line-height: 1.5;"><?php echo esc_html($author_bio); ?></p>
            <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" style="font-size: 0.8125rem; font-weight: 700; color: var(--accent-coral);">
              <?php _e('View all articles by this author', 'foundnxt'); ?> &rarr;
            </a>
          </div>
        </div>

        <!-- ── NEWSLETTER BOX AT END OF POST ── -->
        <div style="background: linear-gradient(135deg, var(--navy-surface) 0%, #1E293B 100%); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; padding: 36px 32px; text-align: center; margin: 48px 0; color: #FFFFFF;">
          <span style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; background: rgba(124, 58, 237, 0.25); color: #C4B5FD; margin-bottom: 12px; text-transform: uppercase;">
            ✉️ <?php _e('Weekly Executive Brief', 'foundnxt'); ?>
          </span>
          <h3 style="font-size: 1.45rem; font-weight: 800; margin-bottom: 10px; color: #FFFFFF;">
            <?php _e('One sharp business breakdown every week. No fluff.', 'foundnxt'); ?>
          </h3>
          <p style="font-size: 0.9375rem; color: #94A3B8; max-width: 500px; margin: 0 auto 20px;">
            <?php _e('Join thousands of founders and operators getting practical playbooks on scaling in the AI era.', 'foundnxt'); ?>
          </p>
          <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: flex; gap: 10px; max-width: 440px; margin: 0 auto; flex-wrap: wrap;">
            <input type="hidden" name="action" value="fnx_lead_submit">
            <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
            <input type="hidden" name="lead_type" value="Single Post Footer Newsletter">
            <input type="hidden" name="target_sheet" value="newsletter">
            <input type="email" name="lead_email" placeholder="<?php esc_attr_e('Enter work email…', 'foundnxt'); ?>" required style="flex: 1 1 220px; padding: 12px 16px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.08); color: #FFF; font-size: 0.9375rem;">
            <button type="submit" class="btn-hero-primary" style="padding: 12px 22px; font-size: 0.9375rem; cursor: pointer; border: none;">
              <?php _e('Subscribe', 'foundnxt'); ?> &rarr;
            </button>
          </form>
        </div>

      </article>

      <!-- ── SIDEBAR (Sticky TOC + Post Info + Related) ── -->
      <aside class="post-sidebar" style="position: sticky; top: 90px;">
        
        <!-- Table of Contents Widget -->
        <div class="sidebar-card fnx-sidebar-toc" id="sidebar-toc" style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px; margin-bottom: 24px;">
          <h4 style="font-size: 0.9375rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 14px; color: var(--text);">
            📑 <?php _e('Table of Contents', 'foundnxt'); ?>
          </h4>
          <ul class="toc-sidebar-list" id="toc-sidebar-list" style="list-style: none; padding: 0; margin: 0; font-size: 0.875rem; display: flex; flex-direction: column; gap: 8px;">
            <li><span class="toc-empty-msg" style="color: var(--muted);"><?php _e('Loading headings…', 'foundnxt'); ?></span></li>
          </ul>
        </div>

        <!-- Post Quick Info Card -->
        <div class="sidebar-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px; margin-bottom: 24px;">
          <h4 style="font-size: 0.9375rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 14px; color: var(--text);">
            ℹ️ <?php _e('Article Information', 'foundnxt'); ?>
          </h4>
          <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.875rem; color: var(--muted);">
            <div><strong><?php _e('Published:', 'foundnxt'); ?></strong> <?php echo get_the_date('M j, Y'); ?></div>
            <div><strong><?php _e('Est. Read Time:', 'foundnxt'); ?></strong> <?php echo esc_html($read_time); ?></div>
            <div><strong><?php _e('Category:', 'foundnxt'); ?></strong> <a href="<?php echo esc_url(get_category_link($primary_c->term_id)); ?>" style="color: <?php echo esc_attr($cat_color); ?>; font-weight: 600;"><?php echo esc_html($cat_name); ?></a></div>
          </div>
        </div>

      </aside>

    </div><!-- /.post-layout -->
  </div><!-- /.container -->
</div><!-- /.fnx-post-body -->

<!-- ══════════════════════════════════════════════════
     RELATED POSTS (3 Articles from same category)
══════════════════════════════════════════════════ -->
<?php
$related_posts = get_posts([
    'category'       => $primary_c->term_id,
    'posts_per_page' => 3,
    'post__not_in'   => [get_the_ID()],
    'post_status'    => 'publish',
]);
if (!empty($related_posts)):
?>
<section class="hp-insights-section" style="border-top: 1px solid var(--border); padding: 60px 0;">
  <div class="container">
    <div class="hp-section-header">
      <div>
        <span class="hp-sec-eyebrow" style="color: <?php echo esc_attr($cat_color); ?>;"><?php _e('Recommended Reading', 'foundnxt'); ?></span>
        <h2 class="hp-sec-title"><?php printf(__('More in %s', 'foundnxt'), esc_html($cat_name)); ?></h2>
      </div>
      <div>
        <a href="<?php echo esc_url(get_category_link($primary_c->term_id)); ?>" class="btn-hero-secondary" style="padding: 8px 18px; font-size: 0.875rem;">
          <?php _e('View Category Archive', 'foundnxt'); ?> &rarr;
        </a>
      </div>
    </div>

    <div class="hp-insights-grid">
      <?php foreach ($related_posts as $rp):
        $rcats  = get_the_category($rp->ID);
        $rcat   = !empty($rcats) ? $rcats[0] : $primary_c;
        $rcolor = fnx_get_category_color($rcat->slug);
      ?>
        <article class="insight-card" style="--card-accent: <?php echo esc_attr($rcolor); ?>;">
          <?php if (has_post_thumbnail($rp->ID)): ?>
            <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>" class="insight-card-thumb">
              <?php echo get_the_post_thumbnail($rp->ID, 'fnx-card', ['loading' => 'lazy']); ?>
            </a>
          <?php endif; ?>
          <div class="insight-card-body">
            <span class="insight-card-tag" style="background: <?php echo esc_attr($rcolor); ?>18; color: <?php echo esc_attr($rcolor); ?>;">
              <?php echo esc_html($rcat->name); ?>
            </span>
            <h3 class="insight-card-title">
              <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>">
                <?php echo esc_html(get_the_title($rp->ID)); ?>
              </a>
            </h3>
            <div class="insight-card-meta">
              <span><?php echo get_the_date('M j, Y', $rp->ID); ?></span>
              <span><?php echo fnx_read_time($rp->ID); ?></span>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
