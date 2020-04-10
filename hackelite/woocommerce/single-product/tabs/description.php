<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = '';

?>

<?php if ( $heading ): ?>
  <h2><?php echo esc_html__($heading); ?></h2>
<?php endif; ?>

<?php the_content(); ?>
