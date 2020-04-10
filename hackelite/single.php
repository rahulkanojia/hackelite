<?php
/**
 * The Template for displaying all single posts
 * @package injob
 */

get_header();
$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'single_sidebar_position');
$sidebar_sticky = Inwave_Helper::getPostOption('sidebar_sticky', 'single_sticky_sidebar');
?>
    <div class="page-content">
        <div class="main-content">
            <div class="container">
                <div class="row">
                    <div class="<?php echo esc_attr(inwave_get_classes('container',$sidebar_position))?> blog-content single-content">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('content', 'single'); ?>
                        <?php endwhile; // end of the loop. ?>
                    </div>
                    <?php if ($sidebar_position && $sidebar_position != 'none') { ?>
                        <div class="<?php echo esc_attr(inwave_get_classes('sidebar', $sidebar_position))?> <?php echo (($sidebar_sticky && $sidebar_sticky != 'no') ? 'iwj-sidebar-sticky' : ''); ?> default-sidebar">
                            <?php get_sidebar('single'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>