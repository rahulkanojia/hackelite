<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

    <div id="tabs" class="product-collateral">
		<ul id="woo-tab-buttons">
			<?php foreach ( $tabs as $key => $tab ) : ?>

				<li class="<?php echo esc_attr($key) ?>_tab">
					<a href="#tab-<?php echo esc_attr($key) ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
				</li>

			<?php endforeach; ?>
		</ul>
		
		<div id="woo-tab-contents">
		<?php foreach ( $tabs as $key => $tab ) : ?>

			<div class="box-collateral" id="tab-<?php echo esc_attr($key) ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div>

		<?php endforeach; ?>
		</div>
	</div>

<?php endif; ?>


