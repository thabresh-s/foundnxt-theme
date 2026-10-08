</main><!-- /#main-content -->

<!-- ══════════════════════════════════
     NEWSLETTER BANNER
══════════════════════════════════ -->
<?php if (is_active_sidebar('fnx-before-footer')): ?>
<section class="fnx-before-footer">
  <div class="container"><?php dynamic_sidebar('fnx-before-footer'); ?></div>
</section>
<?php else: ?>
<section class="fnx-newsletter-banner">
  <div class="container">
    <div class="newsletter-inner">
      <div class="newsletter-text">
        <div class="newsletter-eyebrow">✉️ <?php _e('Free Newsletter', 'foundnxt'); ?></div>
        <h2 class="newsletter-heading"><?php echo esc_html(get_theme_mod('fnx_newsletter_heading', __("Get the next big idea before it's big.", 'foundnxt'))); ?></h2>
        <p class="newsletter-sub"><?php echo esc_html(get_theme_mod('fnx_newsletter_sub', __('Join smart founders, builders, and innovators getting the sharpest insights on startups, tech, and growth — straight to their inbox.', 'foundnxt'))); ?></p>
        <p class="newsletter-note"><?php _e('No spam, just signal.', 'foundnxt'); ?></p>
      </div>
      <div class="newsletter-form">
        <div class="newsletter-form-title"><?php _e('Join Smart Readers', 'foundnxt'); ?></div>
        <div class="fnx-fluent-form-wrap">
          <?php if (shortcode_exists('fluentform')): ?>
            <?php echo do_shortcode('[fluentform id="7"]'); ?>
          <?php else: ?>
            <form class="fnx-newsletter-fallback-form" method="post" action="#">
              <input type="email" name="fnx_newsletter_email" placeholder="<?php esc_attr_e('Enter your email', 'foundnxt'); ?>" required>
              <button type="submit"><?php _e('Subscribe', 'foundnxt'); ?></button>
            </form>
          <?php endif; ?>
          <p class="subscribe-privacy"><?php _e('Free forever. No spam. Unsubscribe in one click.', 'foundnxt'); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════
     MAIN FOOTER
══════════════════════════════════ -->
<footer class="fnx-footer" role="contentinfo">
  <div class="container">

    <!-- Footer top: brand + widgets -->
    <div class="footer-top">

      <!-- Brand Column -->
      <div class="footer-brand">
        <?php if (has_custom_logo()): the_custom_logo();
        else: ?>
          <div class="footer-logo-text"><?php bloginfo('name'); ?></div>
        <?php endif; ?>
        <p class="footer-tagline"><?php echo esc_html(get_theme_mod('fnx_footer_tagline', __("Where founders find what's next.", 'foundnxt'))); ?></p>
        <p class="footer-tagline-sub"><?php echo esc_html(get_theme_mod('fnx_footer_tagline_sub', __('Startups. Tech. Scaling. Careers. Built for what\'s coming.', 'foundnxt'))); ?></p>

        <!-- Social Links (rel="noopener noreferrer" for security) -->
        <div class="footer-social">
          <a href="https://twitter.com/foundnxt" target="_blank" rel="noopener noreferrer" aria-label="Twitter" class="social-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.743l7.737-8.835L1.254 2.25H8.08l4.259 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="https://linkedin.com/company/foundnxt" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="social-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="social-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
          </a>
        </div>
      </div>

      <!-- Widget Columns -->
      <?php for ($i = 1; $i <= 3; $i++): ?>
      <div class="footer-col">
        <?php if (is_active_sidebar("fnx-footer-{$i}")): ?>
          <?php dynamic_sidebar("fnx-footer-{$i}"); ?>
        <?php else: ?>
          <?php if ($i === 1): ?>
            <h4 class="footer-col-title"><?php _e('Categories', 'foundnxt'); ?></h4>
            <ul class="footer-links">
              <?php $cats = get_categories(['number' => 8, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false]);
              if (!empty($cats)):
                foreach ($cats as $cat): ?>
                  <li><a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->name); ?> <span>(<?php echo $cat->count; ?>)</span></a></li>
                <?php endforeach;
              else: ?>
                <li><a href="<?php echo esc_url(home_url('/category/startups/')); ?>"><?php _e('Startups', 'foundnxt'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/category/technology/')); ?>"><?php _e('Tech', 'foundnxt'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/category/scaling/')); ?>"><?php _e('Scaling', 'foundnxt'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/category/careers/')); ?>"><?php _e('Careers', 'foundnxt'); ?></a></li>
              <?php endif; ?>
            </ul>
          <?php elseif ($i === 2): ?>
            <h4 class="footer-col-title"><?php _e('Pages', 'foundnxt'); ?></h4>
            <ul class="footer-links">
              <?php wp_nav_menu(['theme_location' => 'footer-2', 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false]); ?>
              <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php _e('About', 'foundnxt'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('/#services')); ?>"><?php _e('Services', 'foundnxt'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact Us', 'foundnxt'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('/articles/')); ?>"><?php _e('Articles', 'foundnxt'); ?></a></li>
            </ul>
          <?php else: ?>
            <h4 class="footer-col-title"><?php _e('Latest Posts', 'foundnxt'); ?></h4>
            <ul class="footer-recent-posts">
              <?php $recent = get_posts(['posts_per_page' => 5, 'post_status' => 'publish']);
              if (!empty($recent)):
                foreach ($recent as $p): ?>
                  <li>
                    <a href="<?php echo esc_url(get_permalink($p->ID)); ?>"><?php echo esc_html(get_the_title($p->ID)); ?></a>
                    <span class="post-date"><?php echo get_the_date('M j, Y', $p->ID); ?></span>
                  </li>
                <?php endforeach; wp_reset_postdata();
              else: ?>
                <li><a href="<?php echo esc_url(home_url('/articles/')); ?>"><?php _e('Explore Articles', 'foundnxt'); ?></a></li>
              <?php endif; ?>
            </ul>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php endfor; ?>

    </div><!-- /.footer-top -->

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom">
      <p class="footer-copyright"><?php echo wp_kses_post(get_theme_mod('fnx_footer_copyright', '&copy; ' . date('Y') . ' &mdash; FoundNXT. All Rights Reserved.')); ?></p>
      <nav class="footer-bottom-nav" aria-label="<?php esc_attr_e('Footer', 'foundnxt'); ?>">
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php _e('Privacy Policy', 'foundnxt'); ?></a>
        <a href="<?php echo esc_url(home_url('/disclaimer/')); ?>"><?php _e('Disclaimer', 'foundnxt'); ?></a>
        <a href="<?php echo esc_url(home_url('/support/')); ?>"><?php _e('Support', 'foundnxt'); ?></a>
      </nav>
    </div>

  </div><!-- /.container -->
</footer>

<!-- Cookie Consent Notice -->
<div id="fnx-cookie-banner" class="fnx-cookie-banner" role="dialog" aria-label="Cookie consent">
  <div class="fnx-cookie-inner container">
    <p><?php _e('We use cookies to enhance your experience and analyze traffic. By using FoundNXT, you agree to our privacy policy.', 'foundnxt'); ?> <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php _e('Learn More', 'foundnxt'); ?></a></p>
    <button id="fnx-accept-cookies" class="btn-primary"><?php _e('Accept', 'foundnxt'); ?></button>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
