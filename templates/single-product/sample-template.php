<?php
/**
 * Template
 * 
 * @author 		Kathy Darling
 * @package 	WC_Extension_Boilerplate/Templates
 * @version     0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="woocommerce-extension-boilerplate">

	<?php do_action( 'wc_extension_boilerplate_before_template', $product_id ); ?>

	<p><?php _e( 'WooCommerce Extension Boilerplate Template', 'woocommerce-extension-boilerplate' ); ?><p>

	<?php if ( $option = get_option( 'wc_extension_boilerplate_sample_text' ) ) {
		printf( '<p>' . __( 'Sample Text Option: %s', 'woocommerce-extension-boilerplate') . '</p>', $option );
	}
	?>

	<?php if ( $meta = WC_Extension_Boilerplate_Helpers::get_sample_textbox( $product_id ) ) {
		printf( '<p>' . __( 'Sample Text Meta: %s', 'woocommerce-extension-boilerplate') . '</p>', $meta );
	}
	?>

	<?php do_action( 'wc_extension_boilerplate_after_template', $product_id ); ?>

</div>