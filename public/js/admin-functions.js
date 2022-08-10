(function($) {
"use strict";

$(function () {
  $('.showTooltip').tooltip()
});

jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}

function scrollElement(element) {
	var offset = $(element).offset().top;
	$('html, body').animate({scrollTop:offset}, 500);
};

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//<-------- * TRIM * ----------->
function trim (string) {
	return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

$(".toggle-menu, .overlay").on('click', function() {
	$('.overlay').toggleClass('open');
});

$('.isNumber').keypress(function (event) {
			return isNumber(event, this)
});

function isNumber(evt, element) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (
			(charCode != 46 || $(element).val().indexOf('.') != -1) &&
			(charCode < 48 || charCode > 57))
			return false;
			return true;
}

$(document).ready(function() {
  $(".onlyNumber").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
           // Allow: Ctrl+A, Command+A
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
           // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               // let it happen, don't do anything
               return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
  });
});

$('.select option[value="'+timezone+'"]').attr('selected', 'selected');

$(".select").select2({
    theme: "bootstrap-5",
});

// Delete Post, Categories, Members, Languages, etc...
$(".actionDelete").on('click', function(e) {
   	e.preventDefault();

   	var element = $(this);
    var form    = $(element).parents('form');

	element.blur();

	swal(
		{   title: delete_confirm,
		  type: "warning",
		  showLoaderOnConfirm: true,
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		   confirmButtonText: yes_confirm,
		   cancelButtonText: cancel_confirm,
		    closeOnConfirm: false,
		    },
		    function(isConfirm){
		    	 if (isConfirm) {
		    	 	form.submit();
		    	 	}
          });
		 });

   $('.filter').on('change', function() {
 		window.location.href = $(this).val();
 	});
})(jQuery);
