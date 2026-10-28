<?php
/**
 * Template Name: Newsletter Page
 * Template Post Type: page
 *
 * Dedicated Newsletter Signup page for FoundNXT.
 * Assign this template to your /newsletter/ page in WordPress.
 *
 * @package FoundNXT
 */

get_header();

$site_url = esc_url(home_url('/'));
$page_url = esc_url(get_permalink());
?>

<div class="fnx-newsletter-page" style="padding: 60px 0 100px;">
  <div class="container">

    <!-- ── HERO SIGNUP CARD ── -->
    <header class="newsletter-hero-card text-center" style="max-width: 780px; margin: 0 auto 70px; background: linear-gradient(135deg, var(--navy-surface) 0%, #1E293B 100%); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 28px; padding: clamp(40px, 6vw, 64px) clamp(24px, 5vw, 48px); color: #FFFFFF; box-shadow: 0 20px 50px -10px rgba(0,0,0,0.45);">
      <span style="display: inline-block; padding: 6px 16px; border-radius: 9999px; font-size: 0.8125rem; font-weight: 800; background: rgba(124, 58, 237, 0.25); color: #C4B5FD; border: 1px solid rgba(124, 58, 237, 0.4); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 20px;">
        ✉️ <?php _e('Free Weekly Executive Brief', 'foundnxt'); ?>
      </span>
      <h1 style="font-size: clamp(2.2rem, 5vw, 3.4rem); font-weight: 800; line-height: 1.15; color: #FFFFFF; margin-bottom: 16px;">
        <?php _e('One sharp business breakdown every week.', 'foundnxt'); ?> <span class="text-highlight"><?php _e('No fluff.', 'foundnxt'); ?></span>
      </h1>
      <p style="font-size: 1.15rem; color: #94A3B8; line-height: 1.6; max-width: 600px; margin: 0 auto 32px;">
        <?php _e('Join 12,000+ founders, operators, and investors getting actionable breakdowns on how to scale in the AI era. Sent every Sunday morning.', 'foundnxt'); ?>
      </p>

      <?php if (isset($_GET['fnx_lead']) && $_GET['fnx_lead'] === 'success'): ?>
        <div style="padding: 16px 24px; border-radius: 12px; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #34D399; font-weight: 700; margin-bottom: 16px;">
          ✓ <?php _e('You are subscribed! Check your inbox for the latest executive breakdown.', 'foundnxt'); ?>
        </div>
      <?php else: ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: flex; gap: 12px; max-width: 480px; margin: 0 auto; flex-wrap: wrap;">
          <input type="hidden" name="action" value="fnx_lead_submit">
          <?php wp_nonce_field('fnx_lead_nonce', 'fnx_lead_nonce_field'); ?>
          <input type="hidden" name="lead_type" value="Dedicated Newsletter Page Signup">
          <input type="hidden" name="target_sheet" value="newsletter">
          
          <input type="email" name="lead_email" placeholder="<?php esc_attr_e('Enter your work email…', 'foundnxt'); ?>" required style="flex: 1 1 260px; padding: 14px 20px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.08); color: #FFF; font-size: 1rem;">
          <button type="submit" class="btn-hero-primary" style="padding: 14px 28px; font-size: 1rem; border: none; cursor: pointer;">
            <?php _e('Subscribe Free', 'foundnxt'); ?> &rarr;
          </button>
        </form>
      <?php endif; ?>

      <div style="display: flex; gap: 20px; justify-content: center; align-items: center; margin-top: 22px; font-size: 0.8125rem; color: #64748B;">
        <span>✓ <?php _e('Free forever', 'foundnxt'); ?></span>
        <span>&bull;</span>
        <span>✓ <?php _e('No spam or sponsored ads', 'foundnxt'); ?></span>
        <span>&bull;</span>
        <span>✓ <?php _e('1-click unsubscribe', 'foundnxt'); ?></span>
      </div>
    </header>

    <!-- ── WHAT READERS GET IN EACH ISSUE ── -->
    <section style="margin-bottom: 80px;">
      <div class="hp-section-header" style="justify-content: center; text-align: center; margin-bottom: 48px;">
        <div>
          <span class="hp-sec-eyebrow"><?php _e('Inside Every Edition', 'foundnxt'); ?></span>
          <h2 class="hp-sec-title"><?php _e('What You’ll Read Every Sunday', 'foundnxt'); ?></h2>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 28px;">
          <div style="font-size: 2rem; margin-bottom: 12px;">🔍</div>
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text); margin-bottom: 8px;"><?php _e('The Forensic Teardown', 'foundnxt'); ?></h3>
          <p style="font-size: 0.875rem; color: var(--muted); line-height: 1.55; margin: 0;">
            <?php _e('Real numbers from MCA filings, unit margin teardowns, and cap table breakdowns of breakout startups.', 'foundnxt'); ?>
          </p>
        </div>

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 28px;">
          <div style="font-size: 2rem; margin-bottom: 12px;">🤖</div>
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text); margin-bottom: 8px;"><?php _e('Applied AI Blueprint', 'foundnxt'); ?></h3>
          <p style="font-size: 0.875rem; color: var(--muted); line-height: 1.55; margin: 0;">
            <?php _e('One concrete engineering blueprint on integrating autonomous LLM agents or reducing inference costs.', 'foundnxt'); ?>
          </p>
        </div>

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 28px;">
          <div style="font-size: 2rem; margin-bottom: 12px;">📊</div>
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text); margin-bottom: 8px;"><?php _e('Valuation Benchmark', 'foundnxt'); ?></h3>
          <p style="font-size: 0.875rem; color: var(--muted); line-height: 1.55; margin: 0;">
            <?php _e('Updated multiples and transaction comps for Indian and global SaaS, fintech, and deeptech rounds.', 'foundnxt'); ?>
          </p>
        </div>

        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 28px;">
          <div style="font-size: 2rem; margin-bottom: 12px;">⚡</div>
          <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text); margin-bottom: 8px;"><?php _e('Curated Signals', 'foundnxt'); ?></h3>
          <p style="font-size: 0.875rem; color: var(--muted); line-height: 1.55; margin: 0;">
            <?php _e('Top 3 charts and regulatory updates every founder must know before entering investor meetings.', 'foundnxt'); ?>
          </p>
        </div>
      </div>
    </section>

  </div>
</div>

<?php get_footer(); ?>
