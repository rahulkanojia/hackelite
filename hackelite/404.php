<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package injob
 */
get_header(); ?>
<div class="page-content page-content-404">
    <div class="container">
        <div class="error-404 not-found">
            <div class="text_404"><?php esc_html_e('404','injob'); ?></div>
            <div class="text_label_404"><?php esc_html_e("We're sorry, but the page you were looking for doesn't exist.", "injob"); ?></div>
            <div class="home_link">
                <span><?php esc_html_e('You could go back to', 'injob'); ?></span> <a href="<?php echo esc_url(home_url('/')); ?>" ><?php esc_html_e('Home Page', 'injob'); ?></a>
            </div>
        </div>
        <!-- .error-404 -->
    </div>
</div><!-- .page-content -->
<?php get_footer(); ?>
