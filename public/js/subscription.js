//<--------- Start Payment -------//>
(function($) {
	"use strict";
	function toFixed(number, decimals) {
	      var x = Math.pow(10, Number(decimals) + 1);
	      return (Number(number) + (1 / x)).toFixed(decimals);
	    }

	$('#checkout').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var planId = button.data('plan-id') // Extract info from data-* attributes
	  var planName = button.data('plan-name') // Extract info from data-* attributes
	  var price = button.data('price') // Extract info from data-* attributes
	  var priceGross = button.data('price-gross') // Extract info from data-* attributes
	  var priceTotal = button.data('price-total') // Extract info from data-* attributes
	  var priceYear = button.data('price-year') // Extract info from data-* attributes
	  var priceYearGross = button.data('price-year-gross') // Extract info from data-* attributes
	  var priceYearTotal = button.data('price-year-total') // Extract info from data-* attributes


	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  var modal = $(this);
	  modal.find('#summaryPlan').html(planName);
	  modal.find('#planId').val(planId);

	  function taxes(amount) {
	    // Taxes
	    var taxes = $('li.isTaxable').length;
	    var totalTax = 0;

	    // Taxes
	    for (var i = 1; i <= taxes; i++) {
	      var percentage = modal.find('.percentageAppliedTax'+i).attr('data');
	      var value = (amount * percentage / 100);
	      modal.find('.amount'+i).html(toFixed(value, 2));
	      totalTax += value;
	    }
	    return (Math.round(totalTax * 100) / 100).toFixed(2);
	  }

	  if ($('#plan').is(":checked")) {
	    taxes(priceYearGross);
	    modal.find('#subtotal').html(priceYear);
	    modal.find('#total').html(priceYearTotal);
	  } else {
	    taxes(priceGross);
	    modal.find('#subtotal').html(price);
	    modal.find('#total').html(priceTotal);
	  }

	});// show.bs.modal

//<---------------- Subscribe ----------->>>>
 $(document).on('click','#subscribe',function(s) {

	 s.preventDefault();
	 var element = $(this);
	 element.attr({'disabled' : 'true'});
	 var payment = $('input[name=payment_gateway]:checked').val();
	 element.find('i').addClass('spinner-border spinner-border-sm align-middle me-1');

	 (function(){
			$('#formBuySubscription').ajaxForm({
			dataType : 'json',
			success:  function(result) {

				if (result.success && result.insertBody) {

					$('#bodyContainer').html('');

					//==== Insert Content to Body
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
  $('#errorPurchase').hide();
	$('#formBuySubscription').trigger("reset");
});

// Cancel subscription
$(document).on('mouseenter', '.subscriptionActive' ,function(){

$(this).html( '<i class="bi-x-circle me-1"></i> ' + your_subscribed);
$(this).addClass('btn-danger').removeClass('btn-success');
 })

$(document).on('mouseleave', '.subscriptionActive' ,function() {
	$(this).html( '<i class="bi-check2 me-1"></i> ' + your_subscribed);
	$(this).removeClass('btn-danger').addClass('btn-success');
 });

	$(".cancelBtn").on('click', function(e) {
     	e.preventDefault();

     	var element = $(this);
			var alert = element.attr('data-alert');
      element.blur();

  	swal(
  		{   title: delete_confirm,
  		 text: alert,
  		 type: "error",
  		 showLoaderOnConfirm: true,
  		 showCancelButton: true,
  		 confirmButtonColor: "#DD6B55",
  		 confirmButtonText: confirm_delete,
  		 cancelButtonText: cancel_confirm,
  		 closeOnConfirm: false,
     },
     function(isConfirm) {
  		    	 if (isConfirm) {
  		    	 	$('.formCancel').submit();
  		    	 	}
  		    	 });
  		 });// End Cancel subscription

})(jQuery);
