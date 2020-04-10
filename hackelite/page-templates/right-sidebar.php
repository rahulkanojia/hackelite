<?php
/**
 * Template Name: Right Sidebar
 * This is the template with right sidebar
 */
get_header();

$sidebar_position = 'right';
$sidebar_name     = Inwave_Helper::getPostOption( 'sidebar_name' );
$sidebar_sticky   = Inwave_Helper::getPostOption( 'sidebar_sticky' );
global $post, $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) );
?>
<div class="contents-main" id="contents-main">
	<div class="container">
		<div class="row">
			<?php
			$public_page = get_post_meta( $post->ID, 'inwave_public_page', true );
			if ( ! $public_page || ( $public_page && is_user_logged_in() ) ) { ?>
				<div class="<?php echo esc_attr( inwave_get_classes( 'container', $sidebar_position, 'large' ) ) ?>">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'page' ); ?>
						<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>
					<?php endwhile; // end of the loop. ?>
				</div>
				<?php if ( $sidebar_position && $sidebar_position != 'none' ) { ?>
					<div class="<?php echo esc_attr( inwave_get_classes( 'sidebar', $sidebar_position, '' ) ) ?> <?php echo( $sidebar_sticky ? 'iwj-sidebar-sticky' : '' ); ?> iwj-sidebar-1">
						<?php get_sidebar(); ?>
					</div>
				<?php }
			} else {
				if ( class_exists( 'IWJ_Class' ) ) {
					$login_page_id = get_permalink( iwj_option( 'login_page_id' ) );
				} else {
					$login_page_id = wp_login_url();
				} ?>
				<div class="iwj-candidate-non-permission">
					<?php echo sprintf( __( 'You must be logged in to view this page. <a href="%s">Login here</a>', 'injob' ), add_query_arg( 'redirect_to', $current_url, $login_page_id ) ); ?>
				</div>
				<?php
			} ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
