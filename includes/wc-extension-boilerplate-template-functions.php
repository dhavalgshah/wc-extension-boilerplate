<?php
/**
 * WooCommerce Extension Boilerplate template functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wc_extension_boilerplate_before_template(){
	echo '<p>' . __( 'Before Template', 'woocommerce-extension-boilerplate' ) . '</p>';
}

function wc_extension_boilerplate_after_template(){
	echo '<p>' . __( 'After Template', 'woocommerce-extension-boilerplate' ) . '</p>';
}