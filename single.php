<?php get_header(); ?>

<!-- Reading Progress Bar -->
<div class="fnx-progress-bar" id="reading-progress" role="progressbar" aria-label="Reading progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  <div class="progress-fill" id="progress-fill"></div>
</div>

<?php while (have_posts()): the_post(); ?>

<?php
$cats      = get_the_category();
$badge     = get_post_meta(get_the_ID(), 'fnx_post_badge', true);
$takeaway  = get_post_meta(get_the_ID(), 'fnx_key_takeaway', true);
$featured  = get_post_meta(get_the_ID(), 'fnx_featured', true);
$gradient  = get_post_meta(get_the_ID(), 'fnx_hero_gradient', true) ?: 'linear-gradient(135deg, var(--fnx-primary) 0%, var(--fnx-primary-dark) 100%)';
?>

<!-- ══════════════════════════════════
     POST HERO — box style, no featured image
══════════════════════════════════ -->
<?php
  /* Meta description: RankMath → Yoast → manual excerpt → auto-excerpt */
  $post_id      = get_the_ID();
  $meta_desc    = '';
  if ( defined('RANK_MATH_VERSION') ) {
    $meta_desc = get_post_meta( $post_id, 'rank_math_description', true );
  }
  if ( ! $meta_desc && class_exists('WPSEO_Meta') ) {
    $meta_desc = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true );
  }
  if ( ! $meta_desc ) {
    $meta_desc = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30, '…' );
  }
?>
<div class="fnx-post-hero fnx-post-hero--simple">
  <!-- Decorative orbs -->
  <div class="phb-gradient-bg" aria-hidden="true">
    <div class="phb-gradient-orb phb-gradient-orb--1"></div>
    <div class="phb-gradient-orb phb-gradient-orb--2"></div>
    <div class="phb-gradient-orb phb-gradient-orb--3"></div>
  </div>

  <div class="phb-simple-outer container">
    <?php fnx_breadcrumbs(); ?>

    <!-- Badge row (optional) -->
    <?php if ($badge || $featured): ?>
    <div class="phb-badge-row">
      <?php if ($badge): ?><span class="phb-badge"><?php echo esc_html($badge); ?></span><?php endif; ?>
      <?php if ($featured): ?><span class="phb-badge phb-badge--star">⭐ <?php _e('Featured', 'foundnxt'); ?></span><?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Title -->
    <h1 class="phb-title"><?php the_title(); ?></h1>

    <!-- Meta description -->
    <?php if ($meta_desc): ?>
      <p class="phb-desc"><?php echo esc_html($meta_desc); ?></p>
    <?php endif; ?>

    <!-- Chips: Topic · Published · Read time -->
    <div class="phb-chips">
      <?php if ($cats): ?>
      <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>" class="phb-chip phb-chip--topic">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        <?php echo esc_html($cats[0]->name); ?>
      </a>
      <?php endif; ?>
      <span class="phb-chip">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <?php echo get_the_date('M j, Y'); ?>
      </span>
      <span class="phb-chip">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <?php echo fnx_read_time(); ?>
      </span>
    </div><!-- /.phb-chips -->

  </div><!-- /.phb-simple-outer -->
</div><!-- /.fnx-post-hero--simple -->

<!-- ══════════════════════════════════
     POST BODY
══════════════════════════════════ -->
<div class="fnx-post-body">
  <div class="container">
    <div class="post-layout">

      <!-- Article Content -->
      <article class="post-content-wrap" id="post-content" itemscope itemtype="https://schema.org/Article">
        <meta itemprop="headline"       content="<?php echo esc_attr(get_the_title()); ?>">
        <meta itemprop="datePublished"  content="<?php echo get_the_date('c'); ?>">
        <meta itemprop="dateModified"   content="<?php echo get_the_modified_date('c'); ?>">
        <meta itemprop="author"         content="<?php echo esc_attr(get_the_author()); ?>">

        <!-- Key Takeaway Callout -->
        <?php if ($takeaway): ?>
        <div class="fnx-callout callout-takeaway">
          <div class="callout-icon">💡</div>
          <div>
            <div class="callout-label"><?php _e('Key Takeaway', 'foundnxt'); ?></div>
            <p class="callout-text"><?php echo esc_html($takeaway); ?></p>
          </div>
        </div>
        <?php endif; ?>

        <div class="post-content entry-content" itemprop="articleBody">
          <?php the_content(); ?>
        </div>

        <!-- Tags -->
        <?php $tags = get_the_tags(); if ($tags): ?>
        <div class="post-tags">
          <span class="tags-label"><?php _e('Topics:', 'foundnxt'); ?></span>
          <?php foreach ($tags as $tag): ?>
            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-chip">#<?php echo esc_html($tag->name); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Share Buttons -->
        <div class="post-share">
          <span class="share-label"><?php _e('Share this article:', 'foundnxt'); ?></span>
          <div class="share-buttons">
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="share-btn share-twitter" aria-label="Share on X (Twitter)">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.259 5.631L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-facebook" aria-label="Share on Facebook">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="share-btn share-linkedin" aria-label="Share on LinkedIn">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' — ' . get_permalink()); ?>" target="_blank" rel="noopener" class="share-btn share-whatsapp" aria-label="Share on WhatsApp">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="share-btn share-telegram" aria-label="Share on Telegram">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            </a>
            <button class="share-btn share-copy" data-url="<?php echo esc_url(get_permalink()); ?>" aria-label="Copy link">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </button>
          </div>
        </div>

        <!-- Author Bio Card -->
        <div class="post-author-card">
          <?php echo get_avatar(get_the_author_meta('email'), 80, '', '', ['class' => 'author-card-avatar']); ?>
          <div class="author-card-info">
            <div class="author-card-name"><?php the_author(); ?></div>
            <div class="author-card-role"><?php _e('Author, FoundNXT', 'foundnxt'); ?></div>
            <?php $bio = get_the_author_meta('description');
            if ($bio): ?>
            <p class="author-card-bio"><?php echo esc_html($bio); ?></p>
            <?php else: ?>
            <p class="author-card-bio"><?php _e('Computer science background, MBA in product management. Writes about startups, funding, and valuation for founders and investors.', 'foundnxt'); ?></p>
            <?php endif; ?>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-card-link"><?php _e('More articles →', 'foundnxt'); ?></a>
          </div>
        </div>

        <!-- Suggested Reading (same category) -->
        <?php
        $suggest_cats = get_the_category();
        if ($suggest_cats):
          $suggested = get_posts([
            'cat'            => $suggest_cats[0]->term_id,
            'posts_per_page' => 3,
            'post__not_in'   => [get_the_ID()],
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
          ]);
          if ($suggested):
        ?>
        <div class="post-suggested">
          <div class="post-suggested-header">
            <span class="post-suggested-label"><?php printf(__('More in %s', 'foundnxt'), esc_html($suggest_cats[0]->name)); ?></span>
            <a href="<?php echo esc_url(get_category_link($suggest_cats[0]->term_id)); ?>" class="post-suggested-viewall"><?php _e('View all →', 'foundnxt'); ?></a>
          </div>
          <div class="post-suggested-list">
            <?php foreach ($suggested as $i => $sg):
              $sg_cat = get_the_category($sg->ID);
              $sg_cn  = $sg_cat ? $sg_cat[0]->name : $suggest_cats[0]->name;
            ?>
            <a href="<?php echo esc_url(get_permalink($sg->ID)); ?>" class="post-suggested-item">
              <div class="post-suggested-num"><?php echo str_pad($i+1, 2, '0', STR_PAD_LEFT); ?></div>
              <div class="post-suggested-body">
                <div class="post-suggested-cat"><?php echo esc_html($sg_cn); ?></div>
                <div class="post-suggested-title"><?php echo esc_html(get_the_title($sg->ID)); ?></div>
                <div class="post-suggested-date"><?php echo get_the_date('M j, Y', $sg->ID); ?> · <?php echo fnx_read_time($sg->ID); ?></div>
              </div>
              <?php if (has_post_thumbnail($sg->ID)): ?>
              <div class="post-suggested-thumb"><?php echo get_the_post_thumbnail($sg->ID, 'fnx-thumb', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title($sg->ID))]); ?></div>
              <?php endif; ?>
            </a>
            <?php endforeach; wp_reset_postdata(); ?>
          </div>
        </div>
        <?php endif; endif; ?>

        <!-- Comments -->
        <?php if (comments_open() || get_comments_number()): ?>
        <div class="post-comments-wrap">
          <?php comments_template(); ?>
        </div>
        <?php endif; ?>

      </article><!-- /.post-content-wrap -->

      <!-- Article Sidebar -->
      <!-- SIDEBAR: sticky column — only the article content scrolls past it -->
      <aside class="post-sidebar" id="post-sidebar" role="complementary" aria-label="<?php esc_attr_e('Article sidebar', 'foundnxt'); ?>">
        <div class="sidebar-sticky" id="sidebar-sticky">

          <!-- ── 1. TABLE OF CONTENTS (auto-built by JS) ── -->
          <div class="fnx-sidebar-toc" id="sidebar-toc">
            <div class="toc-sidebar-header" id="toc-sidebar-header">
              <span class="toc-sidebar-label"><?php _e('Table of Contents', 'foundnxt'); ?></span>
              <button class="toc-sidebar-toggle" id="toc-sidebar-toggle" aria-label="<?php esc_attr_e('Toggle table of contents', 'foundnxt'); ?>">&#8964;</button>
            </div>
            <div class="toc-sidebar-body" id="toc-sidebar-body">
              <div class="toc-sidebar-progress">
                <div class="toc-sidebar-progress-fill" id="toc-progress-fill"></div>
              </div>
              <ul class="toc-sidebar-list" id="toc-sidebar-list">
                <li><span class="toc-empty-msg"><?php _e('Loading headings…', 'foundnxt'); ?></span></li>
              </ul>
            </div>
          </div>

          <!-- ── 2. POST INFO CARD ── -->
          <div class="sidebar-card post-info-card">
            <div class="info-item">
              <div class="info-item-icon">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
              <div class="info-item-body"><small><?php _e('Published', 'foundnxt'); ?></small><strong><?php echo get_the_date('M j, Y'); ?></strong></div>
            </div>
            <div class="info-item">
              <div class="info-item-icon">
                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </div>
              <div class="info-item-body"><small><?php _e('Updated', 'foundnxt'); ?></small><strong><?php echo get_the_modified_date('M j, Y'); ?></strong></div>
            </div>
            <div class="info-item">
              <div class="info-item-icon">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div class="info-item-body"><small><?php _e('Read time', 'foundnxt'); ?></small><strong><?php echo fnx_read_time(); ?></strong></div>
            </div>
            <?php if ($cats): ?>
            <div class="info-item">
              <div class="info-item-icon">
                <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              </div>
              <div class="info-item-body"><small><?php _e('Category', 'foundnxt'); ?></small><a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"><strong><?php echo esc_html($cats[0]->name); ?></strong></a></div>
            </div>
            <?php endif; ?>
          </div>

          <!-- ── 3. MORE IN THIS CATEGORY (posts from same category) ── -->
          <?php if ($cats):
            $cat_posts = get_posts([
              'cat'            => $cats[0]->term_id,
              'posts_per_page' => 5,
              'post__not_in'   => [get_the_ID()],
              'post_status'    => 'publish',
            ]);
            if ($cat_posts):
          ?>
          <div class="sidebar-card">
            <div class="sidebar-card-title"><?php printf(__('More in %s', 'foundnxt'), esc_html($cats[0]->name)); ?></div>
            <?php foreach ($cat_posts as $cp): ?>
              <a href="<?php echo esc_url(get_permalink($cp->ID)); ?>" class="sidebar-post-link">
                <div class="sidebar-post-title"><?php echo esc_html(get_the_title($cp->ID)); ?></div>
                <div class="sidebar-post-date"><?php echo get_the_date('M j, Y', $cp->ID); ?></div>
              </a>
            <?php endforeach; wp_reset_postdata(); ?>
          </div>
          <?php endif; endif; ?>

          <!-- ── 4. RECENT POSTS (with thumbnails) ── -->
          <?php
          $sidebar_recent = get_posts([
            'posts_per_page' => 5,
            'post__not_in'   => [get_the_ID()],
            'post_status'    => 'publish',
          ]);
          if ($sidebar_recent):
          ?>
          <div class="sidebar-card">
            <div class="sidebar-card-title">📰 <?php _e('Recent Posts', 'foundnxt'); ?></div>
            <ul class="sidebar-recent-posts">
              <?php foreach ($sidebar_recent as $rp): ?>
              <li>
                <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>" class="sidebar-recent-item">
                  <div class="sidebar-recent-thumb">
                    <?php if (has_post_thumbnail($rp->ID)): ?>
                      <?php echo get_the_post_thumbnail($rp->ID, 'fnx-thumb', ['loading' => 'lazy']); ?>
                    <?php else: ?>
                      <div class="sidebar-recent-thumb-ph">📰</div>
                    <?php endif; ?>
                  </div>
                  <div class="sidebar-recent-text">
                    <div class="sidebar-recent-title"><?php echo esc_html(get_the_title($rp->ID)); ?></div>
                    <div class="sidebar-recent-meta"><?php echo get_the_date('M j, Y', $rp->ID); ?> · <?php echo fnx_read_time($rp->ID); ?></div>
                  </div>
                </a>
              </li>
              <?php endforeach; wp_reset_postdata(); ?>
            </ul>
          </div>
          <?php endif; ?>

          <!-- ── 5. CATEGORY CLOUD ── -->
          <?php
          $sidebar_cats = get_categories([
            'orderby'    => 'count',
            'order'      => 'DESC',
            'hide_empty' => true,
            'number'     => 12,
          ]);
          if ($sidebar_cats):
          ?>
          <div class="sidebar-card">
            <div class="sidebar-card-title">🏷️ <?php _e('Browse Categories', 'foundnxt'); ?></div>
            <div class="sidebar-cat-cloud">
              <?php foreach ($sidebar_cats as $sc): ?>
                <a href="<?php echo esc_url(get_category_link($sc->term_id)); ?>" class="sidebar-cat-chip">
                  <?php echo esc_html($sc->name); ?>
                  <span class="sidebar-cat-count"><?php echo $sc->count; ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- ── 6. REGISTERED SIDEBAR WIDGETS (from WP admin) ── -->
          <?php if (is_active_sidebar('fnx-article-sidebar')): ?>
            <?php dynamic_sidebar('fnx-article-sidebar'); ?>
          <?php endif; ?>

        </div><!-- /.sidebar-sticky -->
      </aside>

    </div><!-- /.post-layout -->
  </div><!-- /.container -->
</div><!-- /.fnx-post-body -->

<!-- ══════════════════════════════════
     RELATED POSTS
══════════════════════════════════ -->
<?php $related = fnx_related_posts(); if ($related): ?>
<section class="fnx-related-posts">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title"><?php _e('You May Also Like', 'foundnxt'); ?></h2>
      <hr class="section-rule">
    </div>
    <div class="related-grid">
      <?php foreach ($related as $rp): ?>
        <a href="<?php echo esc_url(get_permalink($rp->ID)); ?>" class="related-card">
          <?php if (has_post_thumbnail($rp->ID)): ?>
            <div class="related-thumb"><?php echo get_the_post_thumbnail($rp->ID, 'fnx-card', ['loading' => 'lazy', 'decoding' => 'async']); ?></div>
          <?php endif; ?>
          <div class="related-body">
            <?php $rcats = get_the_category($rp->ID); if ($rcats): ?>
              <span class="related-cat"><?php echo esc_html($rcats[0]->name); ?></span>
            <?php endif; ?>
            <h3 class="related-title"><?php echo esc_html(get_the_title($rp->ID)); ?></h3>
            <span class="related-date"><?php echo get_the_date('M j, Y', $rp->ID); ?></span>
          </div>
        </a>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
