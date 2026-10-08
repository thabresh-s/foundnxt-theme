<?php
/**
 * Template Name: Full Width (No Sidebar)
 * Template Post Type: page
 *
 * @package FoundNXT
 */
get_header(); ?>

<?php while (have_posts()): the_post(); ?>
<div class="fnx-page fnx-page-full">
  <?php if (get_the_title()): ?>
  <div class="page-hero">
    <div class="container">
      <?php fnx_breadcrumbs(); ?>
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </div>
  <?php endif; ?>
  <div class="container">
    <div class="page-content entry-content">
      <?php the_content(); ?>
    </div>
  </div>
</div>
<?php endwhile; ?>

<style>
.fnx-page-full { padding-bottom: 72px; }
.page-hero {
  padding: 48px 0 36px;
  border-bottom: 1px solid var(--fnx-border);
  margin-bottom: 40px;
  background: var(--fnx-surface);
}
.fnx-page-full .page-content {
  font-size: 17px; line-height: 1.8; color: var(--fnx-body);
}
.fnx-page-full .page-content img { border-radius: var(--fnx-radius); margin: 1.5em 0; }
</style>

<?php get_footer(); ?>
