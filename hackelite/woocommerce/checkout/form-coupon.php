<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
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

if (!wc_coupons_enabled()) {
    return;
}

?>

    <div class="checkout-row col-md-12 col-sm-12 col-xs-12">
		<div class="checkout-box checkout-box-coupon">
			<div class="title"><?php esc_html_e('Coupon', 'injob'); ?> <!--<i class="fa fa-minus-square-o"></i>-->
			</div>
			<div class="box">
				<form method="post">
					<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_html_e('Coupon code', 'injob'); ?>" id="coupon_code" value=""/>
					<button type="submit" class="button" name="apply_coupon" value="<?php esc_html_e('Apply Coupon', 'injob'); ?>">
						<em class="fa-icon">
							<i class="fa fa-check"></i>
						</em><?php esc_html_e('Apply Coupon', 'injob'); ?>
					</button>
				</form>
			</div>
		</div>
    </div>

