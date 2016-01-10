<?php
/**
 * Functions related to front-end display
 *
 * @class 	WC_Extension_Boilerplate_Display
 * @version 0.1.0
 * @since   0.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Extension_Boilerplate_Display {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Single Product Display
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 20 );
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_template' ), 10 );

	}



	/*-----------------------------------------------------------------------------------*/
	/* Single Product Display Functions */
	/*-----------------------------------------------------------------------------------*/


	/**
	 * Register the script
	 *
	 * @return void
	 */
	function register_scripts() {

		wp_enqueue_style( 'woocommerce-extension-boilerplate', WC_Extension_Boilerplate::$url . '/assets/css/wc-extension-boilerplate-frontend.css', false, WC_Extension_Boilerplate::VERSION );

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_script( 'woocommerce-extension-boilerplate', WC_Extension_Boilerplate::$url . '/assets/js/wc-extension-boilerplate'. $suffix . '.js', array( 'jquery' ), WC_Extension_Boilerplate::VERSION, true );
	}


	/**
	 * Load the scripts
	 *
	 * @return void
	 */
	function load_scripts() {

		wp_enqueue_script( 'woocommerce-extension-boilerplate' );

		$params = array(
			'sample-string'  => __( 'A Localized String', 'wc-boilerplate-extension', 'woocommerce-extension-boilerplate' ),
		);

		wp_localize_script( 'woocommerce-extension-boilerplate', 'wc_extension_boilerplate_params', $params );

	}


	/**
	 * Add a Template
	 *
	 * @return  void
	 * @since 0.1.0
	 */
	public function display_template(){

		global $product;

		// load up the scripts
		$this->load_scripts();

		// display the template
		wc_get_template(
			'single-product/sample-template.php',
			array( 'product_id' => $product->id ),
			FALSE,
			WC_Extension_Boilerplate::$dir . '/templates/' );

	}

} //end class
