<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package injob
 */
$sidebar = Inwave_Helper::getPostOption('sidebar_name', 'single_sidebar_name');
if(!$sidebar){
    $sidebar = Inwave_Helper::getThemeOption('single_sidebar_name');
}
if ( ! is_active_sidebar(  $sidebar) ) {
	return;
}
?>

<div class="widget-area" role="complementary">
    <?php dynamic_sidebar($sidebar); ?>
</div><!-- #secondary -->
