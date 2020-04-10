<?php
/**
 * Created by PhpStorm.
 * User: TruongDX
 * Date: 11/10/2015
 * Time: 11:44 AM
 */
$show_footer  = Inwave_Helper::getPostOption( 'show_footer', 'show_footer' );
?>
<?php if ($show_footer && $show_footer != 'no') { ?>
    <footer class="iw-footer iw-footer-default">
        <?php if(is_active_sidebar('footer-widget-1') || is_active_sidebar('footer-widget-2') || is_active_sidebar('footer-widget-3') || is_active_sidebar('footer-widget-4')){ ?>
        <div class="iw-footer-middle">
            <div class="container">
                <div class="row">
                    <?php
                    switch(Inwave_Helper::getThemeOption('footer_number_widget'))
                    {
                        case '1':
                            dynamic_sidebar('footer-widget-1');
                            break;
                        case '2':
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">';
                            dynamic_sidebar('footer-widget-1');
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12 last">';
                            dynamic_sidebar('footer-widget-2');
                            echo '</div>';
                            break;
                        case '3':
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-sx-12">';
                            dynamic_sidebar('footer-widget-1');
                            echo '</div>';
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-sx-12">';
                            dynamic_sidebar('footer-widget-2');
                            echo '</div>';
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 col-sx-12 last">';
                            dynamic_sidebar('footer-widget-3');
                            echo '</div>';
                            break;
                        case '4':
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">';
                            echo '<div class="footer-left">';
                            echo '<div class="row">';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-1');
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-2');
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">';
                            echo '<div class="footer-right">';
                            echo '<div class="row">';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-3');
                            echo '</div>';
                            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-sx-12">';
                            dynamic_sidebar('footer-widget-4');
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if(Inwave_Helper::getThemeOption('footer-copyright')){ ?>
        <div class="iw-copy-right">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <p><?php echo wp_kses_post(Inwave_Helper::getThemeOption('footer-copyright')) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </footer>
<?php } ?>
