<?php

/**
 * Fired during plugin activation
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/includes
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Landing_Epay_Activator {

	/**
	 * Short Description. (use period)
	 * Long Description.
	 * @since    1.0.0
	 */
	public static function activate() {
		// create a page when plugin is activated 
		self::createPage( 'ePay Landing Payment Result', 'epay-landing-payment-result', '[LP_epay_payment_result_output]' );

		$getApplyTestEpay = get_option( 'ech_lp_epay_env' );
		if(empty($getApplyTestEpay) || !$getApplyTestEpay ) {
			add_option( 'ech_lp_epay_env', 1 ); // set to DEV Epay environment 
		}
	} //activate


	/*******************************
	 * Create page function
	 *******************************/
	private static function createPage ($pageTitle, $pageSlug, $pageShortcode) {
		if ( current_user_can( 'activate_plugins' ) ) { 
			$v_page = array(
				'post_type' => 'page',
				'post_title' => $pageTitle,
				'post_name' => $pageSlug,
				'post_content' => $pageShortcode,  // shortcode from this plugin
				'post_status' => 'publish',
				'post_author' => get_current_user_id()
			);
			wp_insert_post($v_page, true);
		} else {
			return;
		}
	} //createPage

}
