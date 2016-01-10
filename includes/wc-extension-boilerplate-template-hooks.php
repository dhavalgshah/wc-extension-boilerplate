<?php
/**
 * WooCommerce Extension Boilerplate template hooks
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wc_extension_boilerplate_before_template', 'wc_extension_boilerplate_before_template' );
add_action( 'wc_extension_boilerplate_after_template', 'wc_extension_boilerplate_after_template' );