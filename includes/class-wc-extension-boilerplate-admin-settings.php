<?php
/**
 * WooCommerce Extension Boilerplate Settings
 *
 * @author 		Kathy Darling
 * @category 	Admin
 * @package 	WC_Extension_Boilerplate/Admin
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Extension_Boilerplate_Admin_Settings' ) ) :

/**
 * WC_Extension_Boilerplate_Admin_Settings
 */
class WC_Extension_Boilerplate_Admin_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'wc-extension-boilerplate';
		$this->label = __( 'WooCommerce Extension Boilerplate', 'woocommerce-extension-boilerplate' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'woocommerce_' . $this->id . '_settings', array(

			array( 
				'id' => 'wc_extension_boilerplate_options_group_a' ,
				'title' => __( 'WooCommerce Extension Boilerplate Settings', 'woocommerce-extension-boilerplate' ), 
				'type' => 'title', 
				'desc' =>  __( 'Modify the text strings used by the Name Your Own Price extension.', 'woocommerce-extension-boilerplate' )
			),

			array(
				'title' => __( 'Sample Text Field', 'woocommerce-extension-boilerplate' ),
				'desc' 		=> __( 'This is a sample text field.', 'woocommerce-extension-boilerplate' ),
				'id' 		=> 'wc_extension_boilerplate_sample_text',
				'type' 		=> 'text',
				'css' 		=> 'min-width:300px;',
				'default'	=> __( 'Default value', 'woocommerce-extension-boilerplate' ),
				'desc_tip'	=>  true,
			),

			array( 
				'type' => 'sectionend', 
				'id' => 'wc_extension_boilerplate_options_group_a' 
			),

			array( 
				'title' => __( 'WooCommerce Extension Boilerplate Additional Settings', 'woocommerce-extension-boilerplate' ), 
				'type' => 'title',
				'id' => 'wc_extension_boilerplate_options_group_b' 
			),

			array(
				'title' => __( 'WooCommerce Extension Boilerplate Sample Checkbox', 'woocommerce-extension-boilerplate' ),
				'id' 		=> 'wc_extension_boilerplate_sample_checkbox',
				'type' 		=> 'checkbox',
				'default'		=> 'no',
			),

			array( 
				'type' => 'sectionend', 
				'id' => 'wc_extension_boilerplate_options_group_b' 
			),

		)); // End pages settings
	}
}

endif;

return new WC_Extension_Boilerplate_Admin_Settings();