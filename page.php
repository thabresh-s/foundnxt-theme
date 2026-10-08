<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>
<div class="fnx-page">
  <div class="container-narrow">
    <div class="page-header">
      <?php fnx_breadcrumbs(); ?>
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
    <div class="page-content entry-content">
      <?php the_content(); ?>
    </div>
  </div>
</div>
<?php endwhile; ?>

<style>
.fnx-page{padding:48px 0 72px}
.page-header{margin-bottom:32px;padding-bottom:24px;border-bottom:1px solid var(--fnx-border)}
.page-title{font-size:clamp(26px,4vw,44px);color:var(--fnx-ink);margin:0}
.page-content{font-size:17px;line-height:1.8;color:var(--fnx-body);max-width:760px}
.page-content h2{margin-top:1.8em}.page-content h3{margin-top:1.5em}
.page-content img{border-radius:var(--fnx-radius);margin:1.5em 0}
</style>

<?php get_footer(); ?>
