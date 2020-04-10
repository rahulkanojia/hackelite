<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
	return;
}

?>
<div class="checkout-row col-md-12">
	<div class="checkout-box checkout-box-login">
		<div class="title"><?php esc_html_e( 'Login', 'injob' ); ?><!--<i class="fa fa-minus-square-o"></i>--></div>
		<div class="box">
			<form method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<?php if ( $message ) {
					echo wpautop( wptexturize( $message ) );
				} ?>
				<div class="row login-form-input">

					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="text" class="input-text" placeholder="<?php esc_html_e( 'Username or email', 'injob' ); ?>" name="username" id="username" />
					</div>

					<div class="col-md-6 col-sm-6 col-xs-12">
						<input class="input-text" type="password" name="password" id="password" placeholder="<?php esc_html_e( 'Password', 'injob' ); ?>" />
					</div>

				</div>
				<div class="clear"></div>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="login-form-button">
					<?php wp_nonce_field( 'woocommerce-login' ); ?>
					<button type="submit" class="button" name="login" value="<?php esc_html_e( 'Login', 'injob' ); ?>">
						<em class="fa-icon"><i class="fa fa-unlock"></i></em><?php esc_html_e( 'Login', 'injob' ); ?>
					</button>
					<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />
					<span class="iwj-input-checkbox">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<label for="rememberme" class="inline"><?php esc_html_e( 'Remember me', 'injob' ); ?></label>
					</span>
				</div>
				<div class="lost_password">
					<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'injob' ); ?></a>
				</div>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>
		</div>
	</div>
</div>