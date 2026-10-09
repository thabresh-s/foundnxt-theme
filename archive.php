<?php get_header(); ?>

<?php
/* ══════════════════════════════════════════════════
   CATEGORY PAGE — ItemList + CollectionPage Schema
   Injected in <head> via wp_head hook alternative:
   we output a <script type="application/ld+json">
   just after get_header() so it sits early in <body>
   (Google accepts ld+json anywhere in the document).
══════════════════════════════════════════════════ */
if ( is_category() ) :
  $schema_cat   = get_queried_object();
  $schema_url   = get_category_link( $schema_cat->term_id );
  $schema_desc  = strip_tags( category_description() );
  $schema_name  = $schema_cat->name;
  $schema_site  = get_bloginfo('name');
  $schema_logo  = wp_get_attachment_image_url( get_theme_mod('custom_logo'), 'full' );

  // Fetch up to 100 published posts in this category for ListItem
  $schema_posts = get_posts([
    'post_status'    => 'publish',
    'posts_per_page' => 100,
    'category'       => $schema_cat->term_id,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'fields'         => 'ids',
  ]);

  $list_items = [];
  $position   = 1;
  foreach ( $schema_posts as $pid ) {
    $list_items[] = [
      '@type'    => 'ListItem',
      'position' => $position++,
      'url'      => get_permalink( $pid ),
      'name'     => get_the_title( $pid ),
    ];
  }

  $schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
      [
        '@type'           => 'CollectionPage',
        '@id'             => $schema_url . '#collection',
        'url'             => $schema_url,
        'name'            => $schema_name . ' — ' . $schema_site,
        'description'     => $schema_desc ?: $schema_name . ' articles on ' . $schema_site,
        'inLanguage'      => 'en-IN',
        'isPartOf'        => [ '@id' => home_url('/') . '#website' ],
        'breadcrumb'      => [ '@id' => $schema_url . '#breadcrumb' ],
        'publisher'       => [
          '@type' => 'Organization',
          'name'  => $schema_site,
          'url'   => home_url('/'),
          'logo'  => $schema_logo ? [ '@type' => 'ImageObject', 'url' => $schema_logo ] : null,
        ],
      ],
      [
        '@type'           => 'ItemList',
        '@id'             => $schema_url . '#itemlist',
        'url'             => $schema_url,
        'name'            => $schema_name . ' Articles',
        'description'     => $schema_desc ?: '',
        'numberOfItems'   => count( $list_items ),
        'itemListOrder'   => 'https://schema.org/ItemListOrderDescending',
        'itemListElement' => $list_items,
      ],
      [
        '@type'           => 'BreadcrumbList',
        '@id'             => $schema_url . '#breadcrumb',
        'itemListElement' => [
          [
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Home',
            'item'     => home_url('/'),
          ],
          [
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => 'Articles',
            'item'     => home_url('/articles/'),
          ],
          [
            '@type'    => 'ListItem',
            'position' => 3,
            'name'     => $schema_name,
            'item'     => $schema_url,
          ],
        ],
      ],
    ],
  ];
  // Remove nulls from publisher logo if no logo set
  $schema['@graph'][0]['publisher'] = array_filter( $schema['@graph'][0]['publisher'] );
  echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
endif;
?>

<div class="fnx-archive">
  <div class="container">

    <?php if (is_category()):
      $cat        = get_queried_object();
      $slug       = $cat->slug;
      $cat_color  = fnx_get_category_color($slug);
      $icon_map   = [
        'business-strategy' => '💼',
        'startups-funding'  => '🚀',
        'valuation-finance' => '📈',
        'markets-economy'   => '🌐',
        'technology-ai'     => '🤖',
        'marketing-growth'  => '🎯',
        'global-business'   => '🗺️',
        'news-insights'     => '📰',
      ];
      $cat_icon   = $icon_map[$slug] ?? '📰';
      $post_count = $cat->count;
    ?>

    <!-- CATEGORY HERO BANNER — Styled with category color -->
    <div class="archive-banner cat-banner" role="banner" style="--cat-accent: <?php echo esc_attr($cat_color); ?>; border-top: 4px solid <?php echo esc_attr($cat_color); ?>;">
      <!-- Decorative blobs -->
      <span class="cat-banner-blob cat-banner-blob--1" aria-hidden="true" style="background: <?php echo esc_attr($cat_color); ?>22;"></span>
      <span class="cat-banner-blob cat-banner-blob--2" aria-hidden="true"></span>

      <div class="cat-banner-body">
        <!-- Breadcrumb -->
        <nav class="cat-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'foundnxt'); ?>">
          <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'foundnxt'); ?></a>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M4.5 2.5L8 6L4.5 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <a href="<?php echo esc_url(home_url('/articles/')); ?>"><?php _e('Categories', 'foundnxt'); ?></a>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M4.5 2.5L8 6L4.5 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span><?php single_cat_title(); ?></span>
        </nav>

        <!-- Icon + Title -->
        <div class="cat-banner-title-row">
          <span class="cat-banner-icon" aria-hidden="true"><?php echo $cat_icon; ?></span>
          <h1 class="cat-banner-title" itemprop="name"><?php single_cat_title(); ?></h1>
        </div>

        <?php if (category_description()): ?>
          <p class="cat-banner-desc" itemprop="description"><?php echo category_description(); ?></p>
        <?php endif; ?>

        <!-- Stats row -->
        <div class="cat-banner-stats">
          <span class="cat-stat-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <?php printf( _n('%d article', '%d articles', $post_count, 'foundnxt'), $post_count ); ?>
          </span>
          <span class="cat-stat-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <?php _e('Updated regularly', 'foundnxt'); ?>
          </span>
        </div>
      </div><!-- .cat-banner-body -->
    </div><!-- .archive-banner -->

    <?php else: ?>
    <div class="archive-header">
      <?php fnx_breadcrumbs(); ?>
      <div class="archive-title-wrap">
        <span class="archive-label">
          <?php if (is_tag()) _e('Tag', 'foundnxt');
          elseif (is_author()) _e('Author', 'foundnxt');
          elseif (is_year()) _e('Year', 'foundnxt');
          elseif (is_month()) _e('Month', 'foundnxt'); ?>
        </span>
        <h1 class="archive-title"><?php the_archive_title(); ?></h1>
        <?php the_archive_description('<div class="archive-desc">', '</div>'); ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="archive-layout">

      <!-- MAIN POSTS -->
      <div class="archive-posts">
        <?php if (have_posts()): ?>

          <?php if (!is_paged()):
            the_post();
            $fc = get_the_category();
            $fn = $fc ? $fc[0]->name : 'Article';
            $fu = $fc ? get_category_link($fc[0]->term_id) : '#';
          ?>
          <!-- Featured first post -->
          <article id="post-<?php the_ID(); ?>" <?php post_class('arc-featured'); ?> itemscope itemtype="https://schema.org/Article">
            <div class="arc-featured-inner">
              <div class="arc-featured-meta">
                <a href="<?php echo esc_url($fu); ?>" class="post-card-cat" itemprop="articleSection"><?php echo esc_html($fn); ?></a>
                <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished" class="post-card-date"><?php echo get_the_date('M j, Y'); ?></time>
                <span class="post-card-read"><?php echo esc_html(fnx_read_time()); ?></span>
              </div>
              <a href="<?php the_permalink(); ?>" class="arc-featured-title" itemprop="headline"><?php the_title(); ?></a>
              <p class="arc-featured-excerpt" itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 28); ?></p>
              <div class="arc-featured-footer">
                <div class="post-card-author">
                  <?php echo get_avatar(get_the_author_meta('email'), 24, '', '', ['class' => 'avatar']); ?>
                  <span itemprop="author"><?php the_author(); ?></span>
                </div>
                <a href="<?php the_permalink(); ?>" class="btn-primary arc-read-btn"><?php _e('Read Article →', 'foundnxt'); ?></a>
              </div>
            </div>
            <?php if (has_post_thumbnail()): ?>
            <a href="<?php the_permalink(); ?>" class="arc-featured-thumb" tabindex="-1" aria-hidden="true">
              <?php the_post_thumbnail('fnx-hero', ['loading' => 'eager', 'itemprop' => 'image', 'alt' => get_the_title()]); ?>
            </a>
            <?php endif; ?>
          </article>
          <?php endif; ?>

          <!-- Posts grid -->
          <div class="posts-grid arc-grid">
            <?php while (have_posts()): the_post();
              get_template_part('template-parts/post-card');
            endwhile; ?>
          </div>

          <!-- Pagination -->
          <div class="archive-pagination">
            <?php
            $big = 999999;
            echo paginate_links([
              'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
              'format'    => '?paged=%#%',
              'current'   => max(1, get_query_var('paged')),
              'total'     => $wp_query->max_num_pages,
              'prev_text' => '← ' . __('Prev', 'foundnxt'),
              'next_text' => __('Next', 'foundnxt') . ' →',
              'type'      => 'list',
            ]);
            ?>
          </div>

        <?php else: ?>
          <?php get_template_part('template-parts/no-posts'); ?>
        <?php endif; ?>
      </div>

      <!-- SIDEBAR -->
      <?php get_sidebar(); ?>

    </div>
  </div>
</div>

<?php get_footer(); ?>
