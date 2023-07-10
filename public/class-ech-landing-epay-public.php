<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ech_Landing_Epay
 * @subpackage Ech_Landing_Epay/public
 * @author     Toby Wong <tobywong@prohaba.com>
 */
class Ech_Landing_Epay_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ech-landing-epay-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ech-landing-epay-public.js', array( 'jquery' ), $this->version, false );

	}


	public function display_epay_interface($atts) {
		// check if auth token has been set
		if ( get_option('ech_lp_epay_auth_token') == "" && current_user_can( 'manage_options' ) ) {
			return '<div class="code_error">Settings error - Auth token is not specified</div>';
		}

		$attsArr = shortcode_atts(array(
			'amount' => 100,	// payment amount
			'duedate' => null
		), $atts);
		

		if ($attsArr['amount'] == null) {
			return '<div class="code_error">shortcode error - amount is not specified</div>';
		}


		$output = "";


		if (isset($_GET['epay'])) {
			$urldecode_epay = urldecode($_GET['epay']);
			$epayArr = json_decode( stripslashes($urldecode_epay), true );

			$paymentStatusArr = $this->checkPaymentLinkStatus($epayArr['epay_refcode']); 
			//print_r($paymentStatusArr);

			/* $res = $this->getPaymentInfoByTransID($epayArr['epay_refcode']); 
			echo '<br><br><pre>';
			$resArr = json_decode( $res, true );
			print_r($resArr);
			echo '</pre>'; */
			// *********** Check if connected to UAT ePay api ***************/
			if ( get_option('ech_lp_epay_env') == "1" && current_user_can( 'manage_options' ) ) {
				$output .= '<div style="background: #ff6a6a;color: #fff">Please note that UAT ePay is connected</div>';
			}
			// *********** (END) Check if connected to UAT ePay api ***************/


			switch ( $paymentStatusArr['status'] ) {
				case 0: // PaymentLinkRequest_NotFound
					// show form to register epay link
					if( $epayArr['email'] != "" && !empty($epayArr['email']) ) {

						$output .= '				
							<form id="ech_landing_epay_form" action="" method="post" data-ajaxurl="'.get_admin_url(null, 'admin-ajax.php').'">
								<input type="hidden" name="username" id="username" value="'.$epayArr['username'].'">
								<input type="hidden" name="phone" id="phone" value="'.$epayArr['phone'].'">
								<input type="hidden" name="email" id="email" value="'.$epayArr['email'].'">
								<input type="hidden" name="booking_date" id="booking_date" value="'.$epayArr['booking_date'].'">
								<input type="hidden" name="booking_time" id="booking_time" value="'.$epayArr['booking_time'].'">
								<input type="hidden" name="booking_item" id="booking_item" value="'. $epayArr['booking_item'].'">
								<input type="hidden" name="booking_location" id="booking_location" value="'.$epayArr['booking_location'].'">
								<input type="hidden" name="website_url" id="website_url" value="'.$epayArr['website_url'].'">
								<input type="hidden" name="epay_refcode" id="epay_refcode" value="'.$epayArr['epay_refcode'].'">
								<input type="hidden" name="epay_amount" id="epay_amount" value="'.$attsArr['amount'].'">
								<input type="hidden" name="epay_duedate" id="epay_duedate" value="'.$attsArr['duedate'].'">
								<button type="submit" id="epaySubmitBtn" class="epaySubmitBtn">預付</button>
							</form>
						';
					} 
					break; 

				case 1: // OPEN, got payment link
					$paymentLink = $paymentStatusArr['url']; 

					if( $paymentStatusArr['dueDate'] != '' ) {
						$dueDate = date_create($paymentStatusArr['dueDate']);
						$year = date_format($dueDate,"Y");
						$month = date_format($dueDate,"m");
						$day = date_format($dueDate,"d");
						
						$output .= '<div>連結會在'.$year.'年'. $month . '月'. $day . '日到期</div>';
					}
					
					$output .= '<a href="' . $paymentLink . '" class="epaySubmitBtn">預付</a>';
					
					break;

				case 2: // PAID				
					$output .= '<h2>你已付款, 多謝</h2>';
					break;

				default: // 3 - EXPIRED
					$output .= '<h2>付款連結已過期, 多謝</h2>';
					
			} // switch
		} else {
			if ( current_user_can( 'manage_options' ) ) {
				$output .= "Missing epay url parameter. For non-admin users, they will be redirected to home page";
			} else {
				$output .= "<script>window.location.replace('/')</script>";
			}
		} // if isset($_GET['epay'])

		return $output;
	} //display_epay_interface


	


	public function LPepay_requestPayment() {
		global $TRP_LANGUAGE;

		switch($TRP_LANGUAGE) {
			case 'en_GB': 
				$lang = "/en";
				break;
			case 'zh_CN': 
				$lang = "/zh_cn";
				break;
			default: 
				$lang = "";
		}

		$booking_info = "預約項目: " . $_POST['booking_item'] . " | 預約日期: ".$_POST['booking_date']." | 預約時間: " .$_POST['booking_time']. " | 選擇門市: " . $_POST['booking_location']; 
		
		$amount = $_POST['epayAmount'] * 100; // 這裡epay會變兩位小數 (eg. 100 -> ePay $1.00), 需要乘100，不接收小數點

		if ( empty($_POST['epayDueDate']) || $_POST['epayDueDate'] == '') {
			$dueDate = '';
		} else {
			$dueDate = date('Y-m-d', strtotime(' +' . $_POST['epayDueDate'] . ' day'));
		}

		$epayData = array(
			"amount" => $amount, 
			"clientTransactionId" => $_POST['epayRefCode'],
			"currency" => "HKD",
			"customerId" => trim($_POST['phone']),
			"customerName" => $_POST['name'],
			"description" => $booking_info,
			"dueDate" => $dueDate,
			"responseFailURL" => get_site_url(). $lang . "/epay-landing-payment-result/?transid=".$_POST['epayRefCode'],
			"responseSuccessURL"=> get_site_url(). $lang . "/epay-landing-payment-result/?transid=".$_POST['epayRefCode'],
			"useInstallment" => false,
			//"webhookUrl" => "PaymentNotification",
			"additionalInfo" => array( 
									"curLang" => $TRP_LANGUAGE,
									"email" => $_POST['email'],
									"username" => $_POST['name']
								)
		);

		$result = $this->LPepay_POSTcurl('/payment-link-requests', json_encode($epayData));
		echo $result;
		wp_die();
	}


	public function getPaymentInfoByTransID( $transID ) {
		$urlParams = "?clientTransactionId=".$transID;
		$result = $this->LPepay_GETcurl('/payment-link-requests'.$urlParams); 
		
		return $result;
	} // getPaymentInfoByTransID




	/***********************************************************************************************
	 * 在ePay, 每個clientTransactionId只能call一次payment link request, 
	 * 重復call會出現 Error-duplicated clientTransactionId. 
	 * 
	 * checkPaymentLinkStatus Function是先check payment link status, 方便用來判斷執行哪個actions 
	 * $paymentLinkArr['status']: 
	 * 		0 - 還沒call payment link request (payment link不存在)
	 * 		1 - payment link已存在, 不用再call, 拿回payment link
	 * 		2 - 已付錢
	 * 		3 - payment link 過期
	 * 
	 ***********************************************************************************************/
	public function checkPaymentLinkStatus( $transID ) {
		global $TRP_LANGUAGE;

		$paymentLinkArr = array(); 
		$result = $this->getPaymentInfoByTransID($transID);
		$getPaymentData = json_decode($result, true);

		if ( isset($getPaymentData['errors']) && $getPaymentData['errors'][0]['errorCode'] == 'ERR_PaymentLinkRequest_NotFound' ) {
			$paymentLinkArr['status'] = 0;

		} else if ( isset($getPaymentData['status']) && $getPaymentData['status'] == 'OPEN') {		
			switch($TRP_LANGUAGE) {
				case 'en_GB': 
					$url = $getPaymentData['paymentLinkUrlEn'];
					break;
				case 'zh_CN': 
					$url = $getPaymentData['paymentLinkUrlSc'];
					break;
				default: 
					$url = $getPaymentData['paymentLinkUrlTc'];
			}
			$paymentLinkArr['status'] = 1;
			$paymentLinkArr['url'] = $url;
			$paymentLinkArr['dueDate'] = $getPaymentData['dueDate'];

		} else if ( isset($getPaymentData['status']) && $getPaymentData['status'] == 'PAID' ) {
			$paymentLinkArr['status'] = 2;

		} else if ( isset($getPaymentData['status']) && $getPaymentData['status'] == 'EXPIRED' ) {
			$paymentLinkArr['status'] = 3;
		}

		return $paymentLinkArr;
	} // checkPaymentLinkStatus




	private function LPepay_POSTcurl($api_link, $params) {
		if (get_option('ech_lp_epay_env') == "1") {
			// dev
			$api_domain = "https://epayuat.umhgp.com/api"; 
		} else {
			// live
			$api_domain = "https://epay.echealthcare.com/api"; 
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $api_domain . $api_link);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params );

		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // 不驗證證書
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); // 不驗證HOST
		curl_setopt ( $ch, CURLOPT_SSLVERSION, 1 );

		$header = array(
			"Content-Type: text/plain",
			"x-auth-token: " .  get_option('ech_lp_epay_auth_token')
		);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = 'Error:' . curl_error($ch);
            $result .= 'Code:' . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        curl_close($ch);

        return $result;
	}



	private function LPepay_GETcurl($api_link) {
		
		if (get_option('ech_lp_epay_env') == "1") {
			// dev
			$api_domain = "https://epayuat.umhgp.com/api"; 
		} else {
			// live
			$api_domain = "https://epay.echealthcare.com/api"; 
		}


		$ch = curl_init();

		curl_setopt ( $ch, CURLOPT_URL, $api_domain . $api_link);
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // 不驗證證書
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); // 不驗證HOST
		curl_setopt ( $ch, CURLOPT_SSLVERSION, 1 );
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $params );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

		$header = array(
			"Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
			"x-auth-token: " .  get_option('ech_lp_epay_auth_token')
		);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = 'Error:' . curl_error($ch);
            $result .= 'Code:' . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        curl_close($ch);

        return $result;
	}


}
