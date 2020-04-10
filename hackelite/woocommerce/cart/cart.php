<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 4.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wc_print_notices();

do_action('woocommerce_before_cart'); ?>
<div class="product-cart">
    <div class="cart">
        <form action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="cart-table">
			<div class="iwj-table-overflow-x">
                <div class="cart-table-items">
                    <div class="cart-table-title">
                        <div class="row row-item">

                            <div class="col-md-3 col-sm-3 col-xs-3 item no-border"><div class="title-cart-table"><?php esc_html_e('Product', 'injob'); ?></div></div>
                            <div class="col-md-3 col-sm-3 col-xs-3 item"><div class="title-cart-table"><?php esc_html_e('Price', 'injob'); ?></div></div>
                            <div class="col-md-2 col-sm-2 col-xs-2 item"><div class="title-cart-table"><?php esc_html_e('Quantity', 'injob'); ?></div></div>
                            <div class="col-md-3 col-sm-3 col-xs-3 item"><div class="title-cart-table"><?php esc_html_e('Total', 'injob'); ?></div></div>
                            <div class="col-md-1 col-sm-1 col-xs-1 item"><div class="title-cart-table delete-item"><a href="#"><i class="fa fa-times-circle"></i></a></div></div>
                        </div>
                    </div>

                    <?php do_action('woocommerce_before_cart_contents'); ?>

                    <?php
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            ?>
                            <div
                                class="row row-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                                <div class="item no-border col-md-3 col-sm-3 col-xs-3">
                                    <div class="name-item ">
                                        <?php
                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                        if (!$_product->is_visible())
                                            echo wp_kses_post($thumbnail);
                                        else
                                            printf('<a href="%s">%s</a>', $_product->get_permalink($cart_item), $thumbnail);
                                        ?>
                                        <div class="product-info">
                                            <?php
                                            if (!$_product->is_visible())
                                                echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                                            else
                                                echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', $_product->get_permalink($cart_item), $_product->get_name()), $cart_item, $cart_item_key);

                                            // Meta data
                                            echo wc_get_formatted_cart_item_data( $cart_item );

                                            // Backorder notification
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
                                                echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'injob') . '</p>';
                                            ?>
                                        </div>
                                        <div class="clear_wc"></div>
                                    </div>
                                </div>

                                <div class="item col-md-3 col-sm-3 col-xs-3">
                                    <div class="price-item">
										<span class="cart-price">
											<?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                            ?>
										</span>
                                    </div>
                                </div>

                                <div class="item col-md-2 col-sm-2 col-xs-2">
                                    <div class="qty-item">
                                        <?php
                                        if ($_product->is_sold_individually()) {
                                            $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                        } else {
                                            $product_quantity = woocommerce_quantity_input(array(
                                                'input_name' => "cart[{$cart_item_key}][qty]",
                                                'input_value' => $cart_item['quantity'],
                                                'max_value' => $_product->get_max_purchase_quantity(),
                                                'min_value' => '0',
                                                'product_name'  => $_product->get_name(),
                                            ), $_product, false);
                                        }

                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                        ?>
                                    </div>
                                </div>


                                <div class="item col-md-3 col-sm-3 col-xs-3">
                                    <div class="price-item">
										<span class="cart-price">
										<?php
                                        echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                        ?>
										</span>
                                    </div>
                                </div>

                                <div class="item col-md-1 col-sm-1 col-xs-1">
                                    <div class="delete-item">
                                        <?php
                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                            '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_html__( 'Remove this item', 'injob' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ), $cart_item_key );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }

                    do_action('woocommerce_cart_contents');
                    ?>
                </div>
				
				</div>
				
                <?php if (wc_coupons_enabled()) { ?>
				<div class="woo-cart-coupon">
                    <div class="row-title woo-coupon-row">

                        <div class=""><span><?php esc_html_e('Coupon', 'injob'); ?></span></div>
                    </div>
                    <div class="row-item woo-coupon-row">
                        <div class="item">
                        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                               placeholder="<?php esc_html_e('Coupon code', 'injob'); ?>"/>
                            <input type="submit"
                                   class="button"
                                   name="apply_coupon"
                                   value="<?php esc_html_e('Apply Coupon', 'injob'); ?>"/>
                            <?php do_action('woocommerce_cart_coupon'); ?>

                            <button type="submit" class="button update-cart-button" class="button" name="update_cart" value="<?php esc_html_e('Update Cart', 'injob'); ?>"><i class="fa fa-refresh"></i> <?php esc_html_e('Update Cart', 'injob'); ?></button>
                            <div class="clear"></div>
						</div>

                    </div>
				</div>
                <?php } ?>

            </div>

            <div class="clearfix"></div>
            <?php do_action('woocommerce_cart_actions'); ?>

            <?php wp_nonce_field('woocommerce-cart'); ?>
            <?php do_action('woocommerce_after_cart_contents'); ?>

            <?php do_action('woocommerce_after_cart_table'); ?>

        </form>

        <div class="cart-collaterals">
<!--            <div class="col-md-12">-->
                <?php do_action('woocommerce_cart_collaterals'); ?>
<!--            </div>-->
        </div>

        <?php do_action('woocommerce_after_cart'); ?>
    </div>
</div>