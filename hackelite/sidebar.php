<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package injob
 */

$sidebar = Inwave_Helper::getPostOption('sidebar_name');
if(!$sidebar){
    $sidebar = Inwave_Helper::getThemeOption('sidebar_name');
}

if ( ! is_active_sidebar(  $sidebar) ) {
	return;
}
?>

<div class="widget-area  <?php echo esc_attr($sidebar); ?>" role="complementary">
    <?php dynamic_sidebar($sidebar); ?>
</div><!-- #secondary -->
