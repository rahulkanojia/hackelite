<?php

/**
 * Template Name: Two Sidebar Page
 * This is the template that is used for the Home page,  As default template with 2 sidebar
 */

get_header();

$sidebar_name   = Inwave_Helper::getPostOption( 'sidebar_name' );
$sidebar_name_2 = Inwave_Helper::getPostOption( 'sidebar_name_2' );
$sidebar_sticky = Inwave_Helper::getPostOption( 'sidebar_sticky' );
global $post, $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) );
?>
<div class="contents-main" id="contents-main">
	<div class="container">
		<div class="row">
			<?php
			$public_page = get_post_meta( $post->ID, 'inwave_public_page', true );
			if ( ! $public_page || ( $public_page && is_user_logged_in() ) ) { ?>
				<div class="col-md-3 <?php echo( $sidebar_sticky ? 'iwj-sidebar-sticky' : '' ); ?> iwj-sidebar-1">
					<div class="widget-area <?php echo esc_attr( $sidebar_name ); ?>">
						<?php dynamic_sidebar( $sidebar_name ); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="iwj-content">
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
				</div>
				<div class="col-md-3 <?php echo( $sidebar_sticky ? 'iwj-sidebar-sticky' : '' ); ?> iwj-sidebar-2">
					<div class="widget-area <?php echo esc_attr( $sidebar_name_2 ); ?>">
						<?php dynamic_sidebar( $sidebar_name_2 ); ?>
					</div>
				</div>
				<?php
			} else {
				if(class_exists('IWJ_Class')){
					$login_page_id = get_permalink( iwj_option( 'login_page_id' ) );
				}else{
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
