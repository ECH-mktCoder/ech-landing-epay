<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/admin
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Landing_Epay_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ech-landing-epay-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ech-landing-epay-admin.js', array( 'jquery' ), $this->version, false );
	}


	/**
	 * ^^^ Add Landing Page ePay Admin menu
	 *
	 * @since    1.0.0
	 */
	public function lp_epay_admin_menu() {
		add_menu_page( 'Landing Page ePay Plugin Settings', 'LP ePay', 'manage_options', 'lp_epay_settings', array($this, 'lp_epay_admin_page'), 'dashicons-buddicons-activity', 110 );
	}

	// return views
	public function lp_epay_admin_page() {
		require_once ('partials/ech-landing-epay-admin-display.php');
	}



	/**
	 * ^^^ Register custom fields for plugin settings
	 *
	 * @since    1.0.0
	 */
	public function reg_lp_epay_settings() {
		// Register all settings for general setting page
		register_setting( 'lp_epay_settings', 'ech_lp_epay_env');
		register_setting( 'lp_epay_settings', 'ech_lp_epay_auth_token');
	}


}
