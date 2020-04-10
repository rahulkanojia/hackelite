<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     4.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//global $product,$smof_data;
global $product, $inwave_theme_option, $woocommerce_loop;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
$classes=array('product-image-wrapper', 'woo-list-product-grid');
if ($product->is_on_sale()){
    $classes[] = 'sale_product';
}

?>
<div <?php post_class($classes); ?>>
    <div class="product-content">
        <div class="product-image">
            <a href="<?php echo esc_url(get_the_permalink()); ?>">
				<?php echo wp_kses_post($product->get_image('medium')); ?>
			</a>

        </div>
        <div class="info-products">
            <div class="product-name">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

                <div class="product-bottom"></div>
            </div>
            <div class="price-box">
				<div class="price-box-inner">
                <?php echo wp_kses_post($product->get_price_html()); ?>
				</div>
            </div>
			<div class="add-cart">
				<a class="add_to_cart_button product_type_simple" data-product_id="<?php echo esc_attr($product->get_id()) ?>" data-product_sku="<?php echo esc_attr($product->get_sku()) ?>" href="<?php echo esc_url($product->add_to_cart_url())?>" data-quantity="1"><?php echo __('Add cart', 'injob');?></a>
            </div>
            
        </div>
    </div>
    
</div>
