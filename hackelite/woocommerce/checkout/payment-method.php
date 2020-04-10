<?php
/**
 * Output a single payment method
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="payment-item payment_method_<?php echo esc_attr($gateway->id); ?> iwj-input-radio">
	<input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio custom-input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr($gateway->id); ?>"></label>
    <span><?php echo esc_html__($gateway->get_title()); ?> <?php echo wp_kses_post($gateway->get_icon()); ?></span>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>