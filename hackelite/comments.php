<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package injob
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
$totalComment = get_comment_count(get_the_ID())['total_comments'];
?>
<div id="comments" class="comments">
    <div class="comments-content">
        
        <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'injob'); ?></p>
        <?php endif; ?>
        <?php if (have_comments()) : ?>
            <div class="commentList">
                <h3 class="comments-title">
                    <?php echo sprintf(_n('<span>%d</span> Comment', '<span>%d</span> Comments', $totalComment,'injob'), $totalComment); ?>
                </h3>
                <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
                    <nav id="comment-nav-above" class="comment-navigation" role="navigation">
                        <div
                            class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'injob')); ?></div>
                        <div
                            class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'injob')); ?></div>
                    </nav><!-- #comment-nav-above -->
                <?php endif; // check for comment navigation ?>
                <ul class="comment_list">
                    <?php
                    wp_list_comments(array(
                        'callback' => 'inwave_comment',
                        'short_ping' => true,
                    ));
                    ?>
                </ul>
                <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
                    <nav id="comment-nav-bellow" class="comment-navigation" role="navigation">
                        <div
                            class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'injob')); ?></div>
                        <div
                            class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'injob')); ?></div>
                    </nav><!-- #comment-nav-below -->
                <?php endif; // check for comment navigation ?>

            </div>
        <?php endif; ?>
        <div class="form-comment">

            <?php
            $req      = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );
            $html_req = ( $req ? " required='required'" : '' );

            $required_text = sprintf( ' ' . esc_html__('Required fields are marked %s','injob'), '<span class="required">*</span>' );
			$fields = array(
                'author' => '<div class="row"><div class="col-md-4 col-sm-12 col-xs-12 commentFormField"><input id="author" '.$aria_req.' '.$html_req.' class="input-text" name="author" placeholder="' . esc_html__('Name *', 'injob') . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" /></div>',
                'email' => '<div class="col-md-4 col-sm-12 col-xs-12 commentFormField"><input id="email" class="input-text" '.$aria_req.' '.$html_req.' name="email" placeholder="' . esc_html__('Email address *', 'injob') . '" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" /></div>',
                'url' => '<div class="col-md-4 col-sm-12 col-xs-12 commentFormField"><input id="url" class="input-text" name="url" placeholder="' . esc_html__('Website', 'injob') . '" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div></div>',
                'comment_field' => '<div class="row"><div class="col-xs-12 commentFormField"><textarea id="comment" aria-required="true" required="required" class="control" placeholder="' . esc_html__('Your comment *', 'injob') . '" name="comment" cols="45" rows="4"></textarea></div></div>',
            );
			
            if (!is_user_logged_in ()){

	            if(version_compare( $GLOBALS['wp_version'], '4.9.6', '>=' )){
		            $consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		            $fields['cookies'] = '<div class="row"><div class="col-xs-12 commentFormField commentFormFieldCookies"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
		                                 '<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time comment.' ) . '</label></div></div>';
	            }

                comment_form(array(
                    'fields' => apply_filters('comment_form_default_fields', $fields),
                    'comment_field' => '',
                    'class_submit' => 'btn-submit button',
                    'comment_notes_before' => $required_text,
                    'comment_notes_after' => '',
                    'format' => 'xhtml'
                ));
            }

            if (is_user_logged_in ()){
                comment_form(array(
                    'comment_field' => '<div class="row"><div class="col-xs-12 commentFormField"><textarea id="comment" class="control" placeholder="' . esc_html_x('Comment*', 'noun','injob') . '" name="comment" cols="45" rows="4" aria-required="true"></textarea></div></div>',
                    'class_submit' => 'btn-submit button',
                ));
            }
			?>
        </div>
    </div>
    <!-- #comments -->
</div><!-- #comments -->
