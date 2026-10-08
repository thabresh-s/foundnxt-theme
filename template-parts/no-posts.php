<div class="no-posts">
  <div class="no-posts-icon">📭</div>
  <h2 class="no-posts-title"><?php _e('No Articles Found', 'foundnxt'); ?></h2>
  <p class="no-posts-desc"><?php _e("We couldn't find any articles matching your request. Try browsing a category or searching for a topic.", 'foundnxt'); ?></p>
  <div class="no-posts-actions">
    <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="btn-primary"><?php _e('Browse All Articles', 'foundnxt'); ?></a>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-outline"><?php _e('Go to Homepage', 'foundnxt'); ?></a>
  </div>
</div>

<style>
.no-posts{text-align:center;padding:56px 20px}
.no-posts-icon{font-size:56px;margin-bottom:16px}
.no-posts-title{font-size:24px;color:var(--fnx-ink);margin-bottom:10px}
.no-posts-desc{font-size:15px;color:var(--fnx-muted);max-width:420px;margin:0 auto 24px;line-height:1.7}
.no-posts-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
</style>
