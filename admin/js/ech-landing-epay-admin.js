(function( $ ) {
	'use strict';


	$(function(){
		/************* GENERAL FORM **************/
		$('#lp_epay_settings_form').on('submit', function(e){
			e.preventDefault();
			$('.statusMsg').removeClass('error');
			$('.statusMsg').removeClass('updated');

			var statusMsg = '';
			var validStatus = false;

			var authToken = $('#ech_lp_epay_auth_token').val();

			// form validation
			if( authToken == '') {
				validStatus = false;
				statusMsg += 'Auth Token is missing <br>';
			} else {
				validStatus = true;
			}

			// set error status msg
			if ( !validStatus ) {
				$('.statusMsg').html(statusMsg);
				$('.statusMsg').addClass('error');
				return;
			} else {
				$('#lp_epay_settings_form').attr('action', 'options.php');
				$('#lp_epay_settings_form')[0].submit();
				// output success msg
				statusMsg += 'Settings updated <br>';
				$('.statusMsg').html(statusMsg);
				$('.statusMsg').addClass('updated');
			}
		});
		/************* (END) GENERAL FORM **************/



		/************* COPY SAMPLE SHORTCODE **************/
		$('#copyShortcode').click(function(){

			var shortcode = $('#sample_shortcode').text();

			navigator.clipboard.writeText(shortcode).then(
				function(){
					$('#copyMsg').html('');
					$('#copyShortcode').html('Copied !'); 
					setTimeout(function(){
						$('#copyShortcode').html('Copy Shortcode'); 
					}, 3000);
				},
				function() {
					$('#copyMsg').html('Unable to copy, try again ...');
				}
			);
		});
		/************* (END)COPY SAMPLE SHORTCODE **************/



	}); // ready

	
})( jQuery );
