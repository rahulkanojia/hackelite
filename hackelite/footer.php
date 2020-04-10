<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package injob
 */
if(!is_404()){
    $footer_layout = Inwave_Helper::getPostOption('footer_option', 'footer_option');
    $footer_layout = $footer_layout ? $footer_layout : 'default';

    get_template_part('footer/footer', $footer_layout);
}
?>
</div> <!--end .content-wrapper -->
<?php wp_footer(); ?>
</body>
</html>
