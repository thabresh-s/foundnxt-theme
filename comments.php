<?php if (post_password_required()) return; ?>

<div id="comments" class="fnx-comments">

  <?php if (have_comments()): ?>
  <h3 class="fnx-comments-title">
    <?php printf(
      _n('%d Comment', '%d Comments', get_comments_number(), 'foundnxt'),
      get_comments_number()
    ); ?>
  </h3>

  <ol class="fnx-comment-list">
    <?php wp_list_comments([
      'style'       => 'ol',
      'short_ping'  => true,
      'avatar_size' => 44,
      'callback'    => 'fnx_comment_cb',
    ]); ?>
  </ol>

  <?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
  <div class="fnx-comment-pagination"><?php paginate_comments_links(); ?></div>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')): ?>
  <p class="fnx-comments-closed"><?php _e('Comments are closed.', 'foundnxt'); ?></p>
  <?php endif; ?>

  <?php
  comment_form([
    'title_reply'          => __('Leave a Comment', 'foundnxt'),
    'title_reply_before'   => '<h3 class="fnx-comment-form-title">',
    'title_reply_after'    => '</h3>',
    'comment_notes_before' => '',
    'comment_notes_after'  => '',
    'label_submit'         => __('Post Comment →', 'foundnxt'),
    'class_submit'         => 'btn-primary fnx-comment-submit',
    'class_form'           => 'fnx-comment-form',
    'fields'               => [
      'author' => '<div class="fnx-comment-fields-row"><p class="comment-form-author"><label for="author">' . __('Name', 'foundnxt') . ' <span class="required">*</span></label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author'] ?? '') . '" required autocomplete="name" placeholder="' . esc_attr__('Your name', 'foundnxt') . '"></p>',
      'email'  => '<p class="comment-form-email"><label for="email">' . __('Email', 'foundnxt') . ' <span class="required">*</span></label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email'] ?? '') . '" required autocomplete="email" placeholder="' . esc_attr__('Your email (not published)', 'foundnxt') . '"></p></div>',
      'url'    => '',
      'cookies'=> '',
    ],
    'comment_field' => '<p class="comment-form-comment"><label for="comment">' . __('Comment', 'foundnxt') . ' <span class="required">*</span></label><textarea id="comment" name="comment" rows="5" required placeholder="' . esc_attr__('Share your thoughts...', 'foundnxt') . '"></textarea></p>',
  ]);
  ?>
</div>

<?php
function fnx_comment_cb($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  ?>
  <li <?php comment_class('fnx-comment-item'); ?> id="comment-<?php comment_ID(); ?>">
    <div class="fnx-comment">
      <div class="fnx-comment-avatar">
        <?php echo get_avatar($comment, 44, '', '', ['class' => 'fnx-avatar-img']); ?>
      </div>
      <div class="fnx-comment-content">
        <div class="fnx-comment-header">
          <span class="fnx-comment-author"><?php comment_author(); ?></span>
          <time class="fnx-comment-date" datetime="<?php comment_time('c'); ?>"><?php comment_date('M j, Y'); ?></time>
        </div>
        <?php if ($comment->comment_approved == '0'): ?>
        <p class="fnx-comment-pending"><?php _e('Your comment is awaiting moderation.', 'foundnxt'); ?></p>
        <?php endif; ?>
        <div class="fnx-comment-body"><?php comment_text(); ?></div>
        <?php comment_reply_link(array_merge($args, [
          'depth'      => $depth,
          'max_depth'  => $args['max_depth'],
          'before'     => '<div class="fnx-comment-reply">',
          'after'      => '</div>',
          'reply_text' => __('Reply', 'foundnxt'),
        ])); ?>
      </div>
    </div>
  <?php
}
?>
