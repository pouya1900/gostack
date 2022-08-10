//<--------- Start Payment -------//>
(function($) {
	"use strict";

//<---------------- Add Funds  ----------->>>>
 $(document).on('click','#addFundsBtn',function(s) {

	 s.preventDefault();
	 var element = $(this);
	 var form = $(this).attr('data-form');
	 element.attr({'disabled' : 'true'});
	 var payment = $('input[name=payment_gateway]:checked').val();
	 element.find('i').addClass('spinner-border spinner-border-sm align-middle me-1');

	 (function(){
			$('#formAddFunds').ajaxForm({
			dataType : 'json',
			success:  function(result) {

				// success
				if (result.success && result.instantPayment) {
						window.location.reload();
				}

				if (result.success == true && result.insertBody) {

					$('#bodyContainer').html('');

				 $(result.insertBody).appendTo("#bodyContainer");

				 if (payment != 1 && payment != 2) {
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
				 }

					$('#errorAddFunds').hide();

				} else if (result.success == true && result.status == 'pending') {

					swal({
					 title: thanks,
					 text: result.status_info,
					 type: "success",
					 confirmButtonText: ok
					 });

					 $('#formAddFunds').trigger("reset");
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					 $('#previewImage').html('');
					 $('#handlingFee, #total, #total2').html('0');
					 $('#bankTransferBox').hide();

				} else if(result.success == true && result.url) {
					window.location.href = result.url;
				} else {

					if (result.errors) {

						var error = '';
						var $key = '';

						for ($key in result.errors) {
							error += '<li><i class="far fa-times-circle"></i> ' + result.errors[$key] + '</li>';
						}

						$('#showErrorsFunds').html(error);
						$('#errorAddFunds').show();
						element.removeAttr('disabled');
						element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					}
				}

			 },
			 error: function(responseText, statusText, xhr, $form) {
					 // error
					 element.removeAttr('disabled');
					 element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					 swal({
							 type: 'error',
							 title: error_oops,
							 text: error+' ('+xhr+')',
						 });
			 }
		 }).submit();
	 })(); //<--- FUNCTION %
 });//<<<-------- * END FUNCTION CLICK * ---->>>>
//============ End Payment =================//

})(jQuery);
