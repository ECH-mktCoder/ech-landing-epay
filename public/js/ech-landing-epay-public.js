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
				_epay_formNonce = $("#ech_landing_epay_form #epay_form_nonce").val(),
				_epay_duedate = $("#ech_landing_epay_form #epay_duedate").val(),								
				_epay_email_subject = $("#ech_landing_epay_form #epay_email_subject").val(),
				_epay_email_price_content = $("#ech_landing_epay_form #epay_email_price_content").val(),
				_epay_email_sender = $("#ech_landing_epay_form #epay_email_sender").val(),
				_epay_email_replyto = $("#ech_landing_epay_form #epay_email_replyto").val();

			LPepay_requestPayment(_name, _phone, _email, _booking_date, _booking_time, _booking_item, _booking_location, _website_url, _epay_refcode, _epay_amount, _epay_formNonce, _epay_duedate, _epay_email_subject, _epay_email_price_content, _epay_email_sender, _epay_email_replyto);
		}); // on submit
	}); // ready


	function LPepay_requestPayment(_name, _phone, _email, _booking_date, _booking_time, _booking_item, _booking_location, _website_url, _epay_refcode, _epay_amount, _epay_formNonce, _epay_duedate, _epay_email_subject, _epay_email_price_content, _epay_email_sender, _epay_email_replyto) {
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
			'epayFormNonce': _epay_formNonce,
			'epayDueDate': _epay_duedate,
			'epayEmailSubject': _epay_email_subject,
			'epayEmailPriceContent': _epay_email_price_content,
			'epayEmailSender': _epay_email_sender,
			'epayEmailReplyTo': _epay_email_replyto
		}

		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: epayData,
			success: function (msg) {
				console.log(msg);
				if ( msg != '401' ) {
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
				} else {
					alert('401!');
					window.location.replace('/');
				}
			}, 
			error: function (err) {
				console.log("Ajax error: " + err);
			}
		}); // ajax 

	}

})( jQuery );
