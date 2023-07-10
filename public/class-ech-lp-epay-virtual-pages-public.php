<?php

class Ech_LP_Epay_Virtual_Pages_Public {

    public static function lp_epay_payment_result_output() {
		$transID = get_query_var('transid', 'none');
		if ( $transID === 'none' ) { echo '<script>window.location.replace("'.get_site_url().'");</script>'; }

		include('partials/ech-lp-epay-payment-result-public-view.php');
	}  // lp_epay_payment_result_output()


/*     public function lp_epay_fail_output() {
		include('partials/ech-lp-epay-fail-public-view.php');
    } //dr_category_list_output */

} // class