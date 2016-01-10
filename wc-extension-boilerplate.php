<?php
/**
 * Plugin Name: WooCommerce Extension Boilerplate
 * Plugin URI:  <%= opts.projectHome %>
 * Description: <%= opts.description %>
 * Version:     0.1.0
 * Author:      <%= opts.authorName %>
 * Author URI:  <%= opts.authorUrl %>
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: <%= opts.funcPrefix %>
 * Domain Path: /languages
 * Requires at least: 3.8.0
 * Tested up to: 4.4.0
 * WC requires at least: 2.4.0
 * WC tested up to: 2.5.0   
 */

/**
 * Copyright: © 2016 Kathy Darling.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ){
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '', '' );

// Quit right now if WooCommerce is not active
if ( ! is_woocommerce_active() ){
	return;
}


/**
 * The Main WC_Extension_Boilerplate class
 **/
if ( ! class_exists( 'WC_Extension_Boilerplate' ) ) :

class WC_Extension_Boilerplate {

	const VERSION = '0.1.0';
	const PREFIX  = 'WC_Extension_Boilerplate';
	const REQUIRED_WC = '2.1.0';

	/**
	 * @var WC_Extension_Boilerplate - the single instance of the class
	 * @since 0.1.0
	 */
	protected static $instance = null;            

	/**
	 * Plugin Directory
	 *
	 * @since 0.1.0
	 * @var string $dir
	 */
	public static $dir = '';

	/**
	 * Plugin URL
	 *
	 * @since 0.1.0
	 * @var string $url
	 */
	public static $url = '';


	/**
	 * Main WC_Extension_Boilerplate Instance
	 *
	 * Ensures only one instance of WC_Extension_Boilerplate is loaded or can be loaded.
	 *
	 * @static
	 * @see WC_Extension_Boilerplate()
	 * @return WC_Extension_Boilerplate - Main instance
	 * @since 0.1.0
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WC_Extension_Boilerplate ) ) {

			self::$instance = new WC_Extension_Boilerplate();

			self::$dir = plugin_dir_path(__FILE__);

			self::$url = plugin_dir_url(__FILE__);

			/*
			 * Register our autoloader
			 */
			spl_autoload_register( array( self::$instance, 'autoloader' ) );

			// include admin class to handle all backend functions
			if( is_admin() ){
				update_option( 'wc_extenstion_boiler_plate_version', self::VERSION );
				self::$instance->admin = new WC_Extension_Boilerplate_Admin();
			}

			// include the front-end functions
			if ( ! is_admin() ) {
				self::$instance->display = new WC_Extension_Boilerplate_Display();
				self::$instance->cart = new WC_Extension_Boilerplate_Cart();
				self::$instance->order = new WC_Extension_Boilerplate_Order();
			}

			// for compatibility with other extensions
			self::$instance->compat = new WC_Extension_Boilerplate_Compatibility();

		}
		return self::$instance;
	}


	public function __construct(){

		// check we're running the required version of WC
		if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, self::REQUIRED_WC, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			return false;
		}

		// Load translation files
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Include required files
		add_action( 'after_setup_theme', array( $this, 'template_includes' ) );
	}


	/*-----------------------------------------------------------------------------------*/
	/* Required Files */
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Load Classes
	 *
	 * @return      void
	 * @since       0.1.0
	 */
	public function autoloader( $class_name ){
		if ( class_exists( $class_name ) ) {
			return;
		}

		if ( false === strpos( $class_name, self::PREFIX ) ) {
			return;
		}

		$class_name = 'class-' . strtolower( $class_name );
		$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;

		$class_file = str_replace( '_', '-', $class_name ) . '.php';

		if ( file_exists( $classes_dir . $class_file ) ){
			require_once $classes_dir . $class_file;
		}
	}

	/**
	 * Include frontend functions and hooks
	 *
	 * @return void
	 * @since  1.0
	 */
	public static function template_includes(){
		require_once( 'includes/wc-extension-boilerplate-template-functions.php' );
		require_once( 'includes/wc-extension-boilerplate-template-hooks.php' );
	}


	/**
	 * Displays a warning message if version check fails.
	 * @return string
	 * @since  2.1
	 */
	public function admin_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Extension Boilerplate requires at least WooCommerce %s in order to function. Please upgrade WooCommerce.', 'woocommerce-mix-and-match-products', 'woocommerce-extension-boilerplate' ), self::REQUIRED_WC ) . '</p></div>';
	}


	/*-----------------------------------------------------------------------------------*/
	/* Localization */
	/*-----------------------------------------------------------------------------------*/


	/**
	 * Make the plugin translation ready
	 *
	 * @return void
	 * @since  1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc-extension-boilerplate' , false , dirname( plugin_basename( __FILE__ ) ) .  '/languages/' );
	}


} //end class: do not remove or there will be no more guacamole for you

endif; // end class_exists check


/**
 * Returns the main instance of WC_Extension_Boilerplate to prevent the need to use globals.
 *
 * @since  2.0
 * @return WC_Extension_Boilerplate
 */
function WC_Extension_Boilerplate() {
	return WC_Extension_Boilerplate::instance();
}

// Launch the whole plugin
add_action( 'woocommerce_loaded', 'WC_Extension_Boilerplate' );