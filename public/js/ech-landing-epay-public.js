(function( $ ) {
	'use strict';

	$(function(){
		$('#ech_landing_epay_form').on("submit", function(e){
			e.preventDefault();
			
			var _name = $("#ech_landing_epay_form #username").val(),
				_phone = $("#ech_landing_epay_form #phone").val(),
				_email = $("#ech_landing_epay_form #email").val(),
				_booking_date = $("#ech_landing_epay_form #booking_date").val(),
				_booking_time = $("#ech_landing_epay_form #booking_time").val(),
				_booking_item = $("#ech_landing_epay_form #booking_item").val(),
				_booking_location = $("#ech_landing_epay_form #booking_location").val(),
				_website_url = $("#ech_landing_epay_form #website_url").val(),
				_epay_refcode = $("#ech_landing_epay_form #epay_refcode").val(),
				_epay_amount = $("#ech_landing_epay_form #epay_amount").val(),
				_epay_duedate = $("#ech_landing_epay_form #epay_duedate").val();

			LPepay_requestPayment(_name, _phone, _email, _booking_date, _booking_time, _booking_item, _booking_location, _website_url, _epay_refcode, _epay_amount, _epay_duedate);
		}); // on submit
	}); // ready


	function LPepay_requestPayment(_name, _phone, _email, _booking_date, _booking_time, _booking_item, _booking_location, _website_url, _epay_refcode, _epay_amount, _epay_duedate) {
		$("#ech_landing_epay_form #epaySubmitBtn").html("提交中...");

		var ajaxurl = $("#ech_landing_epay_form").data("ajaxurl");

		var epayData = {
			'action': 'LPepay_requestPayment',
			'name': _name, 
			'phone': _phone,
			'email': _email,
			'booking_date': _booking_date,
			'booking_time': _booking_time,
			'booking_item': _booking_item,
			'booking_location': _booking_location,
			'website_url': _website_url,
			'epayRefCode': _epay_refcode,
			'epayAmount': _epay_amount,
			'epayDueDate': _epay_duedate
		}

		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: epayData,
			success: function (msg) {
				console.log(msg);

				var paymentLink = "";
				switch (msg.additionalInfo.curLang) {
					case 'en_GB':
						paymentLink = msg.paymentLinkUrlEn; break;						
					case 'zh_CN': 
						paymentLink = msg.paymentLinkUrlSc; break;
					default: 
						paymentLink = msg.paymentLinkUrlTc;
				}
				window.location.replace(paymentLink);
			}, 
			error: function (err) {
				console.log("Ajax error: " + err);
			}
		}); // ajax 

	}

})( jQuery );
