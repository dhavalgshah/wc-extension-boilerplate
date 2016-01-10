<?php
/**
 * WooCommerce Extension Boilerplate Admin Main Class
 *
 * Adds a setting tab and product meta.
 *
 * @package		WooCommerce Extension Boilerplate
 * @subpackage	WC_Extension_Boilerplate_Admin_Main
 * @category	Class
 * @author		Kathy Darling
 * @since		0.1.0
 */
class WC_Extension_Boilerplate_Admin {

	/**
	 * Bootstraps the class and hooks required actions & filters.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		if( ! defined( 'DOING_AJAX' ) ) {

			// Settings Link for Plugin page
			add_filter( 'plugin_action_links_wc-extension-boilerplate/wc-extension-boilerplate.php', array( $this, 'add_action_link' ) );

			// Product Meta boxes
			add_filter( 'product_type_options', array( $this, 'product_type_options' ) );
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_to_metabox' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_meta' ), 20, 2 );

			// Admin Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'meta_box_script'), 20 );

			// Admin Settings via settings API
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_settings_page' ) );

		}

	}


	/*-----------------------------------------------------------------------------------*/
	/* Plugins Page */
	/*-----------------------------------------------------------------------------------*/

	/*
	 * 'Settings' link on plugin page
	 *
	 * @param array $links
	 * @return array
	 * @since 1.0
	 */

	public function add_action_link( $links ) {
		$settings_link = '<a href="'.admin_url('admin.php?page=wc-settings&tab=wc-extension-boilerplate').'" title="'.__('Go to the settings page', 'woocommerce-extension-boilerplate').'">'.__( 'Settings', 'woocommerce-extension-boilerplate' ).'</a>';
		return array_merge( (array) $settings_link, $links );

	}

    /*-----------------------------------------------------------------------------------*/
	/* Write Panel / metabox */
	/*-----------------------------------------------------------------------------------*/

	/*
	 * Add checkbox to product data metabox title
	 *
	 * @param array $options
	 * @return array
	 * @since 0.1.0
	 */
	public function product_type_options( $options ){

	  $options['wc_extension_boilerplate'] = array(
	      'id' => '_wc_extension_boilerplate',
	      'wrapper_class' => 'show_if_simple',
	      'label' => __( 'WooCommerce Extension Boilerplate', 'woocommerce-extension-boilerplate'),
	      'description' => __( 'A description of this checkbox', 'woocommerce-extension-boilerplate'),
	      'default' => 'no'
	    );

	  return $options;

	}

	/*
	 * Add text inputs to product metabox
	 *
	 * @return print HTML
	 * @since 0.1.0
	 */
	public function add_to_metabox(){
		global $post;

		echo '<div class="options_group">';

			// Checkbox
			woocommerce_wp_checkbox( array(
						'id' => '_wc_extension_boilerplate_checkbox',
						'wrapper_class' => 'show_if_simple',
						'label' => __( 'Sample Checkbox', 'woocommerce-extension-boilerplate' ),
						'description' => __( 'This is a sample checkbox.', 'woocommerce-extension-boilerplate' ) ) );

			// Number
			woocommerce_wp_text_input( array(
				'id' => '_wc_extension_boilerplate_number',
				'class' => 'show_if_simple',
				'label' => __( 'Sample Number Field', 'woocommerce-extension-boilerplate' ),
				'desc_tip' => 'true',
				'description' => __( 'This is a sample number field', 'woocommerce-extension-boilerplate' ),
				'type'	=> 'decimal'
			) );

			// Text
			woocommerce_wp_text_input( array(
				'id' => '_wc_extension_boilerplate_textbox',
				'class' => 'show_if_simple',
				'label' => __( 'Sample Text Field', 'woocommerce-extension-boilerplate' ),
				'desc_tip' => 'true',
				'description' => __( 'This is a sample text field', 'woocommerce-extension-boilerplate' )
			) );

			do_action( 'wc_extension_boilerplate_product_options' );

		echo '</div>';

	  }


	/*
	 * Save extra meta info
	 *
	 * @param int $post_id
	 * @param object $post
	 * @return void
	 */
	public function save_product_meta( $post_id, $post ) {

	   	$product_type 	= empty( $_POST['product-type'] ) ? 'simple' : sanitize_title( stripslashes( $_POST['product-type'] ) );
	   	$suggested = '';

	   	if ( isset( $_POST['_wc_extension_boilerplate'] ) ) {
			update_post_meta( $post_id, '_wc_extension_boilerplate', 'yes' );
		} else {
			update_post_meta( $post_id, '_wc_extension_boilerplate', 'no' );
		}

		if ( isset( $_POST['_wc_extension_boilerplate_checkbox'] ) && 'yes' == $_POST['_wc_extension_boilerplate_checkbox'] ) {
			update_post_meta( $post_id, '_wc_extension_boilerplate_checkbox', 'yes' );
		} else {
			update_post_meta( $post_id, '_wc_extension_boilerplate_checkbox', 'no' );
		}

		if ( isset( $_POST['_wc_extension_boilerplate_number'] ) ) {
			update_post_meta( $post_id, '_wc_extension_boilerplate_number', floatval( $_POST['_wc_extension_boilerplate_number'] ) );
		}

		if ( isset( $_POST['_wc_extension_boilerplate_textbox'] ) ) {
			update_post_meta( $post_id, '_wc_extension_boilerplate_textbox', sanitize_text_field( $_POST['_wc_extension_boilerplate_textbox'] ) );
		}

	}


	/*
	 * Javascript to handle the metabox options
	 *
	 * @param string $hook
	 * @return void
	 * @since 0.1.0
	 */
    public function meta_box_script( $hook ){

		// check if on Edit-Post page (post.php or new-post.php).
		if( ! in_array( $hook, array( 'post-new.php', 'post.php' ) ) ){
			return;
		}

		// now check to see if the $post type is 'product'
		global $post;
		if ( ! isset( $post ) || 'product' != $post->post_type ){
			return;
		}

		// enqueue and localize
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'wc_extension_boilerplate_admin', WC_Extension_Boilerplate::$url . '/assets/js/wc-extension-boilerplate-admin'. $suffix . '.js', array( 'jquery' ), WC_Extension_Boilerplate::VERSION, true );

		$i18n = array ( 'sample_string' => __( 'Sample String', 'woocommerce-extension-boilerplate' ) );

		wp_localize_script( 'wc_extension_boilerplate_admin', 'wc_extension_boilerplate_admin', $i18n );

	}

	/*-----------------------------------------------------------------------------------*/
	/* Admin Settings */
	/*-----------------------------------------------------------------------------------*/

	/*
	 * Include the settings page class
	 * compatible with WooCommerce 2.1
	 *
	 * @param array $settings ( the included settings pages )
	 * @return array
	 * @since 0.1.0
	 */
	public function add_settings_page( $settings ) {
		$settings[] = include( 'class-wc-extension-boilerplate-admin-settings.php' );
		return $settings;
	}

}
