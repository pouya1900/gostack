//<--------- Start Payment -------//>
(function($) {
	"use strict";

	$('input[name=payment_gateway]').on('click', function() {

		$('#payButton').removeAttr('disabled');
  });

//<---------------- Pay ----------->>>>
 $(document).on('click','#payButton',function(s) {

	 s.preventDefault();
	 var element = $(this);
	 var form = $(this).attr('data-form');
	 element.attr({'disabled' : 'true'});
	 var payment = $('input[name=payment_gateway]:checked').val();
	 element.find('i').addClass('spinner-border spinner-border-sm align-middle me-1');

	 (function(){
			$('#formSendBuy').ajaxForm({
			dataType : 'json',
			success:  function(result) {

				if (result.success && result.insertBody) {

					$('#bodyContainer').html('');

				 $(result.insertBody).appendTo("#bodyContainer");

				 if (payment != 1 && payment != 2) {
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
				 }

					$('#errorPurchase').hide();

				} else if (result.success && result.url) {
					window.location.href = result.url;
				} else {

					if (result.errors) {

						var error = '';
						var $key = '';

						for ($key in result.errors) {
							error += '<li><i class="far fa-times-circle me-1"></i> ' + result.errors[$key] + '</li>';
						}

						$('#showErrorsPurchase').html(error);
						$('#errorPurchase').show();
						element.removeAttr('disabled');
						element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					}
				}

			 },
			 error: function(responseText, statusText, xhr, $form) {
					 // error
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					 $('.popout').addClass('popout-error').html(error+' ('+xhr+')').fadeIn('500').delay('8000').fadeOut('500');
			 }
		 }).submit();
	 })(); //<--- FUNCTION %
 });//<<<-------- * END FUNCTION CLICK * ---->>>>
//============ End Payment =================//

$('#checkout').on('hidden.bs.modal', function (e) {
  $('#errorPurchase, #stripeContainer').hide();
	$('#formSendBuy').trigger("reset");
	$('#card-errors').addClass('display-none');
	$('.InputElement').val('');
	$('#card-element').removeClass('StripeElement--invalid');
	$('#payButton').attr({'disabled' : 'true'});
});

})(jQuery);
