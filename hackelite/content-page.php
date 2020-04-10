<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package injob
 */
?>

<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
        wp_link_pages(array(
            'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'injob').'</span>',
            'after' => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
        ));
		?>
	</div><!-- .entry-content -->
	<div class="clearfix"></div>
	<footer class="entry-footer ">
		<?php edit_post_link( esc_html__( 'Edit', 'injob' ), '<span class="edit-link">', '</span>' ,get_the_ID()); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
