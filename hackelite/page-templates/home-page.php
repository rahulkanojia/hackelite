<?php

/**
 * Template Name: Home Page
 * This is the template that is used for the Home page:  no Sidebar, no Page heading and no Breadcrums
 */

get_header();
global $post, $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) );
?>
<div class="contents-main" id="contents-main">
	<?php
	$public_page = get_post_meta( $post->ID, 'inwave_public_page', true );
	if ( ! $public_page || ( $public_page && is_user_logged_in() ) ) {
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'page' );
		endwhile;
	} else {
		if ( class_exists( 'IWJ_Class' ) ) {
			$login_page_id = get_permalink( iwj_option( 'login_page_id' ) );
		} else {
			$login_page_id = wp_login_url();
		}
		?>
		<div class="container">
			<div class="row">
				<div class="iwj-candidate-non-permission">
					<?php echo sprintf( __( 'You must be logged in to view this page. <a href="%s">Login here</a>', 'injob' ), add_query_arg( 'redirect_to', $current_url, $login_page_id ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}
	?>

</div>
<?php get_footer(); ?>
