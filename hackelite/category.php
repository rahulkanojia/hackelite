<?php
/**
 * The template for displaying Category pages
 * @package injob
 */

get_header();
$sidebar_position = Inwave_Helper::getThemeOption('sidebar_position');
$sidebar_sticky = Inwave_Helper::getThemeOption('sticky_sidebar');
?>
<div class="page-content blog-list">
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr(inwave_get_classes('container', $sidebar_position, 'large'))?>">
                    <?php if ( have_posts() ) : ?>
                        <?php while (have_posts()) : the_post();
                            get_template_part( 'content', get_post_format() );
                        endwhile; // end of the loop. ?>
                    <?php else :
                        // If no content, include the "No posts found" template.
                        get_template_part( 'content', 'none' );
                    endif;?>

                    <?php if ( isset($GLOBALS['wp_query']->max_num_pages) && $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
                        <div class="clearfix"></div>
                        <?php get_template_part( '/blocks/paging'); ?>
                    <?php endif; ?>
                </div>
                <?php if ($sidebar_position && $sidebar_position != 'none') { ?>
                    <div class="<?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position, 'large'))?> default-sidebar <?php echo ($sidebar_sticky ? 'iwj-sidebar-sticky' : ''); ?>">
                        <?php get_sidebar(); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
