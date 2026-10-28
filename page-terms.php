<?php
/**
 * Template Name: Terms Page
 * Template Post Type: page
 *
 * Terms of Service & Editorial Terms page for FoundNXT.
 * Assign this template to your /terms/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();
?>

<div class="fnx-legal-page" style="padding: 50px 0 90px;">
  <div class="container" style="max-width: 800px;">

    <?php if (function_exists('fnx_breadcrumbs')) { fnx_breadcrumbs(); } ?>

    <article style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: clamp(32px, 5vw, 48px); margin-top: 24px;">
      <h1 style="font-size: clamp(2rem, 4vw, 2.75rem); font-weight: 800; color: var(--text); margin-bottom: 8px;">
        <?php _e('Terms of Service', 'foundnxt'); ?>
      </h1>
      <p style="font-size: 0.875rem; color: var(--muted); margin-bottom: 32px;">
        <?php printf(__('Last updated: %s', 'foundnxt'), date('F j, Y')); ?>
      </p>

      <div class="entry-content" style="font-size: 1rem; line-height: 1.7; color: var(--text);">
        <h2><?php _e('1. Introduction', 'foundnxt'); ?></h2>
        <p><?php _e('Welcome to FoundNXT. By accessing or using our website located at foundnxt.com, reading our newsletter, or booking advisory services, you agree to comply with and be bound by these Terms of Service.', 'foundnxt'); ?></p>

        <h2><?php _e('2. Editorial Content & Disclaimer', 'foundnxt'); ?></h2>
        <p><?php _e('All articles, frameworks, case studies, and models published on FoundNXT are for informational and educational purposes only. Nothing on this website constitutes financial, investment, legal, accounting, or tax advice. You should always consult qualified professionals before making corporate financial decisions.', 'foundnxt'); ?></p>

        <h2><?php _e('3. Advisory & Consulting Services', 'foundnxt'); ?></h2>
        <p><?php _e('Advisory engagements (Business Consulting and Tech Consulting) are governed by individual engagement agreements executed between FoundNXT and the client, including mutual non-disclosure agreements.', 'foundnxt'); ?></p>

        <h2><?php _e('4. Intellectual Property', 'foundnxt'); ?></h2>
        <p><?php _e('All content, trademarks, logos, graphics, and models published on FoundNXT are the intellectual property of FoundNXT unless otherwise stated. Reproduction without written consent is strictly prohibited.', 'foundnxt'); ?></p>

        <h2><?php _e('5. Contact', 'foundnxt'); ?></h2>
        <p><?php printf(__('If you have questions regarding these Terms, contact us at %s.', 'foundnxt'), '<a href="mailto:hello@foundnxt.com">hello@foundnxt.com</a>'); ?></p>
      </div>
    </article>

  </div>
</div>

<?php get_footer(); ?>
