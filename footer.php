</main><!-- /#main-content -->

<!-- ══════════════════════════════════
     MAIN FOOTER — FOUNDNXT REDESIGN
══════════════════════════════════ -->
<footer class="fnx-footer" role="contentinfo">
  <div class="container">

    <!-- Footer Top 4-Column Grid -->
    <div class="footer-top" style="display: grid; grid-template-columns: 1.3fr 1fr 1fr 1.3fr; gap: 40px; padding: 60px 0 40px; border-bottom: 1px solid var(--border-subtle);">

      <!-- Column 1: Brand + Tagline + Book a Free Call + Social (Instagram First) -->
      <div class="footer-brand">
        <?php
        $site_url       = esc_url(home_url('/'));
        $site_name      = esc_attr(get_bloginfo('name', 'display') ?: 'FoundNXT');
        $custom_logo_id = get_theme_mod('custom_logo');
        $dark_logo_id   = get_theme_mod('fnx_dark_logo');
        $footer_light   = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : get_theme_file_uri('assets/images/logo-light.svg');
        $footer_dark    = $dark_logo_id ? wp_get_attachment_image_url($dark_logo_id, 'full') : ($custom_logo_id ? $footer_light : get_theme_file_uri('assets/images/logo-dark.svg'));
        ?>
        <a href="<?php echo $site_url; ?>" class="footer-logo-wrap" aria-label="<?php echo $site_name; ?> Home">
          <img class="fnx-footer-logo fnx-logo-light" src="<?php echo esc_url($footer_light); ?>" alt="<?php echo $site_name; ?>" width="160" height="38" loading="lazy">
          <img class="fnx-footer-logo fnx-logo-dark" src="<?php echo esc_url($footer_dark); ?>" alt="<?php echo $site_name; ?>" width="160" height="38" loading="lazy">
        </a>
        <p class="footer-tagline" style="font-size: 0.9375rem; color: var(--muted); margin: 14px 0 16px; line-height: 1.5; font-weight: 500;">
          <?php echo esc_html(get_theme_mod('fnx_footer_tagline', __('How to scale your company in the AI era.', 'foundnxt'))); ?>
        </p>

        <!-- "Book a free call" CTA Button in Footer -->
        <div style="margin-bottom: 22px;">
          <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="footer-cta-btn" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 9999px; font-weight: 700; font-size: 0.875rem; background: linear-gradient(135deg, #7C3AED 0%, #FF6B6B 100%); color: #FFFFFF; text-decoration: none; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);">
            <span><?php _e('Book a free call', 'foundnxt'); ?></span> &rarr;
          </a>
        </div>

        <!-- Social Links (Instagram FIRST as specified) -->
        <div class="footer-social" style="display: flex; gap: 12px; align-items: center;">
          <a href="https://instagram.com/foundnxt" target="_blank" rel="noopener noreferrer" aria-label="Instagram @foundnxt" class="social-link" style="color: #E1306C;" title="Follow @foundnxt on Instagram">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
          </a>
          <a href="https://x.com/foundnxt" target="_blank" rel="noopener noreferrer" aria-label="X (formerly Twitter)" class="social-link" title="Follow @foundnxt on X">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.743l7.737-8.835L1.254 2.25H8.08l4.259 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="https://linkedin.com/company/foundnxt" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="social-link" title="FoundNXT on LinkedIn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>" target="_blank" rel="noopener noreferrer" aria-label="RSS Feed" class="social-link" title="RSS Feed">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
          </a>
        </div>
      </div>

      <!-- Column 2: Menu Links -->
      <div class="footer-col">
        <h4 class="footer-col-title" style="font-size: 0.9375rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text); margin-bottom: 16px;">
          <?php _e('Navigation', 'foundnxt'); ?>
        </h4>
        <ul class="footer-links" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
          <li><a href="<?php echo esc_url(home_url('/articles/')); ?>"><?php _e('Insights', 'foundnxt'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/category/startup-stories/')); ?>"><?php _e('Startup Stories', 'foundnxt'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/category/markets-valuation/')); ?>"><?php _e('Markets & Valuation', 'foundnxt'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/category/ai-tech/')); ?>"><?php _e('AI & Tech', 'foundnxt'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php _e('Services', 'foundnxt'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php _e('About FoundNXT', 'foundnxt'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact Us', 'foundnxt'); ?></a></li>
        </ul>
      </div>

      <!-- Column 3: Category Topics Links -->
      <div class="footer-col">
        <h4 class="footer-col-title" style="font-size: 0.9375rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text); margin-bottom: 16px;">
          <?php _e('Topics', 'foundnxt'); ?>
        </h4>
        <ul class="footer-links" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
          <?php foreach (fnx_get_primary_categories() as $pcat): ?>
            <li>
              <a href="<?php echo esc_url($pcat['url']); ?>" style="display: flex; align-items: center; gap: 8px;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: <?php echo esc_attr($pcat['color']); ?>;"></span>
                <span><?php echo esc_html($pcat['name']); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Column 4: Newsletter Mini-Form -->
      <div class="footer-col">
        <h4 class="footer-col-title" style="font-size: 0.9375rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text); margin-bottom: 12px;">
          <?php _e('Weekly Executive Brief', 'foundnxt'); ?>
        </h4>
        <p style="font-size: 0.875rem; color: var(--muted); line-height: 1.5; margin-bottom: 16px;">
          <?php _e('One sharp business breakdown every week. No fluff.', 'foundnxt'); ?>
        </p>

        <form class="fnx-footer-nl-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: flex; flex-direction: column; gap: 10px;">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Footer Mini Newsletter Signup">
          <input type="hidden" name="target_sheet" value="newsletter">
          
          <input type="email" name="lead_email" placeholder="<?php esc_attr_e('Enter work email…', 'foundnxt'); ?>" required style="padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 0.875rem;">
          <button type="submit" class="btn-primary" style="padding: 10px 16px; border-radius: 8px; font-weight: 700; font-size: 0.875rem; background: var(--accent-coral); color: #FFF; border: none; cursor: pointer;">
            <?php _e('Subscribe', 'foundnxt'); ?> &rarr;
          </button>
        </form>
        <span style="font-size: 0.75rem; color: var(--muted); margin-top: 8px; display: block;">🔒 <?php _e('Free forever. No spam, ever.', 'foundnxt'); ?></span>
      </div>

    </div><!-- /.footer-top -->

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 0; font-size: 0.875rem; color: var(--muted); flex-wrap: wrap; gap: 12px;">
      <p class="footer-copyright" style="margin: 0;">
        &copy; <?php echo date('Y'); ?> FoundNXT. <?php _e('All Rights Reserved.', 'foundnxt'); ?>
      </p>
      <nav class="footer-bottom-nav" aria-label="<?php esc_attr_e('Footer Legal Navigation', 'foundnxt'); ?>" style="display: flex; gap: 18px;">
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php _e('Privacy Policy', 'foundnxt'); ?></a>
        <a href="<?php echo esc_url(home_url('/disclaimer/')); ?>"><?php _e('Terms & Disclaimer', 'foundnxt'); ?></a>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact', 'foundnxt'); ?></a>
      </nav>
    </div>

  </div><!-- /.container -->
</footer>

<?php wp_footer(); ?>
</body>
</html>
