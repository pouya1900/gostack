//<--------- waiting -------//>
(function($){
	"use strict";
$.fn.waiting = function( p_delay ){
	var $_this = this.first();
	var _return = $.Deferred();
	var _handle = null;

	if ( $_this.data('waiting') != undefined ) {
		$_this.data('waiting').rejectWith( $_this );
		$_this.removeData('waiting');
	}
	$_this.data('waiting', _return);

	_handle = setTimeout(function(){
		_return.resolveWith( $_this );
	}, p_delay );

	_return.fail(function(){
		clearTimeout(_handle);
	});

	return _return.promise();
};
})(jQuery);

(function($) {
"use strict";

//** jQuery Scroll to Top Control script- (c) Dynamic Drive DHTML code library: http://www.dynamicdrive.com.
var templatepath = $("#templatedirectory").html();
var scrolltotop={
	setting: {startline:100, scrollto: 0, scrollduration:1000, fadeduration:[700, 500]},
	controlHTML: '<i class="ion-chevron-up up-bottom"></i>', //HTML for control, which is auto wrapped in DIV w/ ID="topcontrol"
	controlattrs: {offsetx:15, offsety:12}, //offset of control relative to right/ bottom of window corner
	anchorkeyword: '#top', //Enter href value of HTML anchors on the page that should also act as "Scroll Up" links

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) //if control is positioned using JavaScript
			this.$control.fadeOut() //hide control immediately after clicking it
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto)
		if (typeof dest=="string" && jQuery('#'+dest).length==1) //check element set by string exists
			dest=jQuery('#'+dest).offset().top
		else
			dest=0
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		var $window=jQuery(window)
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety
		this.$control.css({left:controlx+'px', top:controly+'px'})
	},

	togglecontrol:function(){
		var scrolltop=jQuery(window).scrollTop()
		if (!this.cssfixedsupport)
			this.keepfixed()
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate(this.setting.fadeduration[0]).fadeIn()
			this.state.isvisible=true
		}
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate(this.setting.fadeduration[1]).fadeOut()
			this.state.isvisible=false
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop
			var iebrws=document.all
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body')
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:20, right:10, 'display':'none', 'z-index': 50, cursor:'pointer'})
				.on('click', function(){mainobj.scrollup(); return false})
				.appendTo('body')
			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') //loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}) //IE6- seems to require an explicit width on a DIV containing text
			mainobj.togglecontrol()
			$('a[href="' + mainobj.anchorkeyword +'"]').on('click', function(){
				mainobj.scrollup()
				return false
			})
			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol()
			})
		})
	}
}

scrolltotop.init();

jQuery.fn.reset = function () {
	$(this).each (function() { this.reset(); });
}

function scrollElement(element) {
	var offset = $(element).offset().top;
	$('html, body').animate({scrollTop:offset}, 500);
};

function escapeHtml( unsafe ) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//<-------- * TRIM * ----------->
function trim ( string ) {
	return string.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

//<--------- * Search * ----------------->
$('#buttonSearch, #btnSearch, #_buttonSearch').on('click', function(e) {
		var search    = $('#btnItems').val();
		if(trim( search ).length == 0 || trim( search ).length > 100) {
			return false;
		} else {
			return true;
		}
	});//<---

	$('#btnSearch_2').on('click', function(e){
			var search    = $('#btnItems_2').val();
			if(trim( search ).length == 0 || trim( search ).length > 100 ) {
				return false;
			} else {
				return true;
			}
		});//<---

$(document).ready(function() {

	jQuery(".timeAgo").timeago();
	$(".previewImage").removeClass('d-none');

//================= * Remove focus on click * ===================//
$('.btn, li.dropdown a').on('click', function() {
	$(this).blur();
});

//================= * Input Click * ===================//
$(document).on('click','#avatar_file',function () {
		var _this = $(this);
	    $("#uploadAvatar").trigger('click');
	     _this.blur();
	});

	$('#cover_file').on('click', function () {
		var _this = $(this);
	    $("#uploadCover").trigger('click');
	     _this.blur();
	});

//======== INPUT CLICK ATTACH MESSAGES =====//
$(document).on('click','#upload_image',function () {
		var _this = $(this);
	    $("#uploadImage").trigger('click');
	     _this.blur();
	});

	$(document).on('click','#upload_file',function () {
		var _this = $(this);
	    $("#uploadFile").trigger('click');
	     _this.blur();
	});

	$(document).on('click','#shotPreview',function () {
		var _this = $(this);
	    $("#fileShot").not('.edit_post').trigger('click');
	     _this.blur();
	});

	$(document).on('click','#attachFile',function () {
		var _this = $(this);
	    $("#attach_file").trigger('click');
	     _this.blur();
	});

$(document).on('mouseenter','.deletePhoto, .deleteCover, .deleteBg', function(){

   	 var _this   = $(this);
   	 $(_this).html('<div class="photo-delete"></div>');
 });

 $(document).on('mouseleave','.deletePhoto, .deleteCover, .deleteBg', function(){

   	 var _this   = $(this);
   	 $(_this).html('');
 });


/*---------
 *
 * Credit : http://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded
 * --------
 **/

//<---------- * Avatar * ------------>>
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#upload-avatar').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }//<------ End Function ---->

    //<---- * Avatar * ----->
    $("#file-avatar").change(function(){
        readURL(this);
    });

    //<---------- * Cover * ------------>>
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#upload-cover').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }//<------ End Function ---->

    //<---- * Avatar * ----->
    $("#file-cover").change(function(){
        readURL2(this);
    });

	//<**** - Tooltip
    $('.showTooltip').tooltip();

    $('.delete-attach-image').on('click', function(){
    	$('.imageContainer').fadeOut(100);
    	$('#previewImage').css({ backgroundImage : 'none'});
    	$('.file-name').html('');
    	$('#uploadImage').val('');

    });

    $('.delete-attach-file').on('click', function(){
    	$('.fileContainer').fadeOut(100);
    	$('#previewFile').css({ backgroundImage : 'none'});
    	$('.file-name-file').html('');
    	$('#uploadFile').val('');
    });

    $('.delete-attach-file-2').on('click', function(){
    	$('.fileContainer').fadeOut(100);
    	$('.file-name-file').html('');
    	$('#attach_file').val('');
    });

    $("#saveUpdate").on('click',function(){
    	$(this).css({'display': 'none'})
    });

    $("#paypalPay").on('click',function(){
    	$(this).css({'display': 'none'})
    });


  // Miscellaneous Functions

  /*= Like =*/
	$(".likeButton").on('click',function(e){
	var element     = $(this);
	var id          = element.attr("data-id");
	var like        = element.attr('data-like');
	var like_active = element.attr('data-unlike');
	var data        = 'id=' + id;

	e.preventDefault();

	element.blur();

	if( element.hasClass( 'active' ) ) {
		   	  element.removeClass('active');
		   	  element.find('i').removeClass('bi bi-heart-fill').addClass('bi bi-heart');
		   	  element.find('.textLike').html(like);

		} else {
			element.addClass('active');
		   	  element.find('i').removeClass('bi bi-heart').addClass('bi bi-heart-fill');
		   	  element.find('.textLike').html(like_active);

		}

		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/ajax/like",
		   data: data,
		   success: function( result ){

		   	if( result == '') {
			   	  window.location.reload();
			   	  element.removeClass('likeButton');
			   	  element.removeClass('active');
		   	} else {
		   		$('#countLikes').html(result);
		   	}
		 }//<-- RESULT
	   });//<--- AJAX
});//<----- CLICK



    // ====== FOLLOW HOVER ============
    $(document).on('mouseenter', '.activeFollow' ,function(){

	var following = $(this).attr('data-following');

	// Unfollow
	$(this).html( '<i class="fa fa-times-circle"></i> ' + following);
	 })

	$(document).on('mouseleave', '.activeFollow' ,function() {
		var following = $(this).attr('data-following');
	 	$(this).html( '<i class="glyphicon glyphicon-ok myicon-right"></i> ' + following);
	 });

	 /*========= FOLLOW =============*/
	$(document).on('click',".followBtn",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	var info       = 'id=' + id;

	element.removeClass( 'followBtn' );

	if( element.hasClass( 'follow_active activeFollow' ) ) {
		element.addClass( 'followBtn' );
		   	element.removeClass( 'follow_active activeFollow' );
		   element.html( '<i class="glyphicon glyphicon-plus myicon-right"></i> ' + _follow );
		   element.blur();

		}
		else {

			element.addClass( 'followBtn' );
		   	  element.removeClass( 'follow_active activeFollow' );
		   	    element.addClass( 'followBtn' );
		   	   element.addClass( 'follow_active activeFollow' );
		   	  element.html( '<i class="glyphicon glyphicon-ok myicon-right"></i> ' + _following );
		   	  element.blur();
		}

		 $.ajax({
		   type: "POST",
		   url: URL_BASE+"/ajax/follow",
		   dataType: 'json',
		   data: info,
		   success: function( result ){

		   	if( result.status == false ) {
		   		element.addClass( 'followBtn' );
			   	  element.removeClass( 'follow_active followBtn activeFollow' );
			   	   element.html( '<i class="glyphicon glyphicon-plus myicon-right"></i> ' + _follow );
			   	  window.location.reload();
			   	  element.blur();
		   	}
		 }//<-- RESULT
	   });//<--- AJAX


});//<----- CLICK

/*========= FOLLOW BUTTONS SMALL =============*/
	$(document).on('click',".btnFollow",function(){
	var element    = $(this);
	var id         = element.attr("data-id");
	var _follow    = element.attr("data-follow");
	var _following = element.attr("data-following");
	var info       = 'id=' + id;

	element.removeClass( 'btnFollow' );

	if( element.hasClass( 'btnFollowActive' ) ) {
		element.addClass( 'btnFollow' );
		   	element.removeClass( 'btnFollowActive' );
		   element.html( '<i class="bi bi-person-plus me-1"></i> ' + _follow );
			 element.removeClass('btn-custom').addClass('btn-outline-custom');
		   element.blur();

		}
		else {

			element.addClass( 'btnFollow' );
		   	  element.removeClass( 'btnFollowActive' );
		   	    element.addClass( 'btnFollow' );
		   	   element.addClass( 'btnFollowActive' );
		   	  element.html( '<i class="bi bi-person-check me-1"></i> ' + _following );
					element.removeClass('btn-outline-custom').addClass('btn-custom');
		   	  element.blur();
		}

		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/ajax/follow",
		   dataType: 'json',
		   data: info,
		   success: function( result ){

		   	if (result.status == false) {
		   		element.addClass( 'btnFollow' );
			   	  element.removeClass( 'btnFollowActive followBtn' );
			   	   element.html( '<i class="bi bi-person-plus me-1"></i> ' + _follow );
			   	  window.location.reload();
			   	  element.blur();
		   	}
		 }//<-- RESULT
	   });//<--- AJAX
});//<----- CLICK


	$(document).on('click','#button_message',function(s){

				s.preventDefault();

				var element     = $(this);
				var error       = false;
				var _message    = $('#message').val();
				var dataWait    = $('.msgModal').attr('data-wait');
				var dataSuccess = $('.msgModal').attr('data-success');
				var dataSent    = $('.msgModal').attr('data-send');
				var dataError   = $('.msgModal').attr('data-error');

				if( _message == '' && trim( _message ).length  == 0 ) {
					var error = true;
					return false;
				}

				if( error == false ){
					$('#button_message').attr({'disabled' : 'true'}).html(dataWait);

				(function(){
					 $("#send_msg_profile").ajaxForm({
					 dataType : 'json',
					 success:  function(result){
					 //===== SUCCESS =====//
					 if( result.success != false ){
					 		$('#message').val('');
							 $('#button_message').html(dataSent);
							 $('.popout').html(dataSuccess).css('background-color','#258A0F').fadeIn(500).delay(4000).fadeOut();
							 $('#myModal').modal('hide');
							 $('#button_message').removeAttr('disabled');
							 $('#errors').html('').fadeOut();

						}//<-- e
							else {

						var error = '';
						var $key = '';
                        for($key in result.errors){
                        	error += '<li>* ' + result.errors[$key] + '</li>';
                        }

						$('#errors').html('<ul class="margin-zero padding-zero">'+error+'</ul>').fadeIn(500);


							$('#button_message').html(dataSent);
                            $('#button_message').removeAttr('disabled');
							}

							if( result.session_null ) {
								window.location.reload();
							}
						}//<----- SUCCESS
						}).submit();
						})(); //<--- FUNCTION %

				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>


			//<---------------- UPLOAD IMAGE ----------->>>>
			$(document).on('click','#upload',function(s) {

				s.preventDefault();

				var element = $(this);
				var $error = element.attr('data-error');
				var $errorMsg = element.attr('data-msg-error');
				var $processing = element.attr('data-msg-processing');

				element.attr({'disabled' : 'true'});

				$('#progress').show();

				(function() {

					var bar = $('.progress-bar');
					var percent = $('.percent');
					var percentVal = '0%';

					 $("#formUpload").ajaxForm({
					 dataType : 'json',
					 error: function(responseText, statusText, xhr, $form) {

					 	element.removeAttr('disabled');

						if (! xhr) {
							xhr = '- ' + $errorMsg;
						} else {
							xhr = '- ' + xhr;
						}

					 	$('.popout').addClass('popout-error').html($error+' '+xhr+'').fadeIn('500').delay('5000').fadeOut('500');

						$('#progress').hide();
						bar.width(percentVal);
						percent.html(percentVal);
						$('.wrap-loader').hide();

					 },
					 beforeSend: function() {
			        bar.width(percentVal);
			        percent.html(percentVal);
			    },
			    uploadProgress: function(event, position, total, percentComplete) {
			        var percentVal = percentComplete + '%';
			        bar.width(percentVal);
			        percent.html(percentVal);

							if(percentComplete == 100) {
								percent.html($processing);
							}
			    },
					 success:  function(result) {

					 if (result.session_null) {
							window.location.reload();
							return false;
						}

					 //===== SUCCESS =====//
					 if (result.success) {

						 $('#progress').hide();
						 bar.width(percentVal);
						 percent.html(percentVal);

					 	window.location.href = result.target;

						}//<-- e
					else {

						$('#progress').hide();
						bar.width(percentVal);
						percent.html(percentVal);
						$('.wrap-loader').hide();

						var error = '';
						var $key = '';
                        for( $key in result.errors ){
                        	error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
                            //error += '<div class="btn-block"><strong>* ' + result.errors[$key] + '</strong></div>';
                        }

						$('#showErrors').html(error);
						$('#dangerAlert').fadeIn(500)


						element.removeAttr('disabled');

						}

						}//<----- SUCCESS
					}).submit();
				})(); //<--- FUNCTION %

			});//<<<-------- * END FUNCTION CLICK * ---->>>>


			//<---------------- UPDATE SHOT ----------->>>>
			$(document).on('click','#updateShot',function(s){

				s.preventDefault();

				var element     = $(this);
				var dataWait    = element.attr('data-wait');
				var dataSent    = element.attr('data-send');

				$('#updateShot').attr({'disabled' : 'true'}).html(dataWait);

				(function(){
					 $("#form-edit-shot").ajaxForm({
					 dataType : 'json',
					 success:  function(result){
					 //===== SUCCESS =====//
					 if( result.success != false ){

					 	window.location.reload();
						}//<-- e
							else {

						var error = '';
						var $key = '';
                        for( $key in result.errors ){
                        	error += '<li>* ' + result.errors[$key] + '</li>';
                            //error += '<div class="btn-block"><strong>* ' + result.errors[$key] + '</strong></div>';
                        }

						$('#errors_shot').html('<ul class="margin-zero">'+error+'</ul>').fadeIn(500);

                            $('#updateShot').removeAttr('disabled').html(dataSent);
						}

							if( result.session_null ) {
								window.location.reload();
							}
						}//<----- SUCCESS
						}).submit();
						})(); //<--- FUNCTION %

			});//<<<-------- * END FUNCTION CLICK * ---->>>>

			$('#unblock').on('click', function(){
				element = $(this);
				$.post(URL_BASE+"/unblock/user", { user_id: $(this).data('id') }, function(data){
					if(data.success == true ){
						element.remove();
						window.location.reload();
					} else {
						bootbox.alert(data.error);
						window.location.reload();
					}

					if( data.session_null ) {
						window.location.reload();
					}
				},'json');
		});


			//<---------- * Remove Reply * ---------->
	  	 $(document).on('click','.removeMsg',function(){

	  	 	var element   = $(this);
	  	 	var data      = element.attr('data');
	  	 	var deleteMsg = element.attr('data-delete');
	  	 	var query     = 'message_id='+data;

	  	bootbox.confirm(deleteMsg, function(r) {

	  		if( r == true ) {

	  	 	element.parents('li').fadeTo( 200,0.00, function(){
   		             element.parents('li').slideUp( 200, function(){
   		  	           element.parents('li').remove();
   		              });
   		           });

	  	 	$.ajax({
					headers: {
		        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    		},
	  	 		type : 'POST',
	  	 		url  : URL_BASE+'/t/message/delete',
	  	 		dataType: 'json',
	  	 		data : query,

	  	 	}).done(function( data ){

	  	 		if( data.total == 0 ) {
	  	 			var location = URL_BASE+"/t/messages";
   					window.location.href = location;
	  	 		}

	  	 		if( data.status != true ) {
	  	 			bootbox.alert(data.error);
	  	 			return false;
	  	 		}

	  	 		if( data.session_null ) {
					window.location.reload();
				}
	  	 	});//<--- Done
	  	 	}//END IF R TRUE
	  }); //Jconfirm
	 });//<---- * End click * ---->


			//<---------------- ADD AD ----------->>>>
			$(document).on('click','#add_ad',function(s){

				s.preventDefault();

				var element     = $(this);
				var dataWait    = element.attr('data-wait');
				var dataSent    = element.attr('data-send');

				$('#add_ad').attr({'disabled' : 'true'}).html(dataWait);

				(function(){
					 $("#form-ads").ajaxForm({
					 dataType : 'json',
					 success:  function(result){
					 //===== SUCCESS =====//
					 if( result.success != false ){
					 	   $('#removePanel').remove();
					 		window.location.href = result.target;
					 		$('html, body').animate({scrollTop:0}, 500);
						}//<-- e
							else {

						var error = '';
						var $key = '';
                        for( $key in result.errors ){
                        	error += '<li>* ' + result.errors[$key] + '</li>';
                        }

						$('#errors').html('<ul class="margin-zero">'+error+'</ul>').fadeIn(500);

                            $('#add_ad').removeAttr('disabled').html(dataSent);
						}

							if( result.session_null ) {
								window.location.reload();
							}
						}//<----- SUCCESS
						}).submit();
						})(); //<--- FUNCTION %

			});//<<<-------- * END FUNCTION CLICK * ---->>>>

	 	//<---------------- UPDATE AD ----------->>>>
			$(document).on('click','#update_ad',function(s){

				s.preventDefault();

				var element     = $(this);
				var dataWait    = element.attr('data-wait');
				var dataSent    = element.attr('data-send');

				$('#update_ad').attr({'disabled' : 'true'}).html(dataWait);

				(function(){
					 $("#form-ads").ajaxForm({
					 dataType : 'json',
					 success:  function(result){
					 //===== SUCCESS =====//
					 if( result.success != false ){
					 	   $('#removePanel').remove();
					 		$('#success_response').fadeIn();
					 		$('#errors').fadeOut();
					 		$('html, body').animate({scrollTop:0}, 500);
						}//<-- e
							else {

						var error = '';
						var $key = '';
                        for( $key in result.errors ){
                        	error += '<li>* ' + result.errors[$key] + '</li>';
                        }

						$('#errors').html('<ul class="margin-zero">'+error+'</ul>').fadeIn(500);

                            $('#update_ad').removeAttr('disabled').html(dataSent);
						}

							if( result.session_null ) {
								window.location.reload();
							}
						}//<----- SUCCESS
						}).submit();
						})(); //<--- FUNCTION %

			});//<<<-------- * END FUNCTION CLICK * ---->>>>


			//<----------------- Update email valid
			$(document).on('click','#button_update_mail',function(s){

				s.preventDefault();
				var element     = $(this);
				var error       = false;
				var _email    = $('#email').val();

				if( _email == '' && trim( _email ).length  == 0 ) {
					var error = true;
					return false;
				}

				if( error == false ){
					$('#button_update_mail').attr({'disabled' : 'true'});

				(function(){
					 $("#updateEmail").ajaxForm({
					 dataType : 'json',
					 success:  function(result){
					 //===== SUCCESS =====//
					 if( result.success != false ){
							 $('#myModalMail').modal('hide');
							 $('#button_update_mail').removeAttr('disabled');
							 $('#errors').html('').fadeOut();

						}//<-- e
							else {
						var error = '';
						var $key = '';
                        for($key in result.errors){
                        	error += '<li>* ' + result.errors[$key] + '</li>';
                        }
						$('#errors').html('<ul class="margin-zero padding-zero">'+error+'</ul>').fadeIn(500);
                            $('#button_update_mail').removeAttr('disabled');
							}
							if( result.session_null ) {
								window.location.reload();
							}
						}//<----- SUCCESS
						}).submit();
						})(); //<--- FUNCTION %

				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>

		//<------------------- Invite Friends
			$(document).on('click','#invite_friends',function(s){

				s.preventDefault();
				var element     = $(this);
				var error       = false;
				var _email      = $('#email').val();

				if( _email == '' && trim( _email ).length  == 0 ) {
					var error = true;
					return false;
				}

				if( error == false ){
					$('#invite_friends').attr({'disabled' : 'true'});

				(function(){
					 $("#sendInvitation").ajaxForm({
					 dataType : 'json',
					 success:  function(result){
					 //===== SUCCESS =====//
					 if( result.success != false ){
					 	     $("#sendInvitation input").val('');
							 $('#invite_friends').removeAttr('disabled');
							 $('#success_invite').html(result.message).fadeIn();
							 $('#errors').html('').fadeOut();

						}//<-- e
						else if( result.error_custom  ) {
							$('#errors').html(result.error_custom).fadeIn(500);
							$('#invite_friends').removeAttr('disabled');
							$('#success_invite').html('').fadeOut();
						}
							else {

						$('#success_invite').html('').fadeOut();

						var error = '';
						var $key = '';
                        for($key in result.errors){
                        	error += '<li>* ' + result.errors[$key] + '</li>';
                        }
						$('#errors').html('<ul class="margin-zero padding-zero">'+error+'</ul>').fadeIn(500);
                            $('#invite_friends').removeAttr('disabled');
							}
							if( result.session_null ) {
								window.location.reload();
							}
						}//<----- SUCCESS
						}).submit();
						})(); //<--- FUNCTION %

				}//<-- END ERROR == FALSE
			});//<<<-------- * END FUNCTION CLICK * ---->>>>


	 $('#upload').on('click', function(){
	 	$('.wrap-loader').show();
	 });

	  $('.popout').on('click', function(){
	 	$(this).hide();
	 });

}); //*************** End DOM ***************************//

$(document).on('click','#li-search',function(){

	 	$('#btnItems').focus();

	 	$(document).bind('click', function(ev) {
			var $clicked = $(ev.target);
			if ( !$clicked.parents().hasClass("box_Search") ) {
				$(".box_Search").removeClass( 'in' );
			}

    });//<-------- * END CLICK * --------->

    $('body').keydown(function (event) {
	 if( event.which  == 27 ) {
	 	//$('#btnItems').blur()
	 	$(".box_Search").removeClass( 'in' );
	 }
	});//<-------- * END ESC * --------->

});

if ($(window).width() > 768) {
	$('.hovercard').hover(

	   function () {
	      $(this).find('.hover-content').fadeIn();
	   },

	   function () {
	      $(this).find('.hover-content').fadeOut();
	   }
	);
}

	$('.btn-collection').hover(

	   function () {
	      $(this).find('i').removeClass('bi bi-plus-square').addClass('bi bi-plus-square-fill');
	   },

	   function () {
	      $(this).find('i').removeClass('bi bi-plus-square-fill').addClass('bi bi-plus-square');
	   }
	);


 /*= Add collection  =*/
	$("#addCollection").on('click',function(e){
	var element     = $(this);

	e.preventDefault();
	element.blur();

	element.attr({'disabled' : 'true'}).find('i').addClass('spinner-border spinner-border-sm align-middle me-1');

		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/collection/store",
		   dataType: 'json',
		   data: $("#addCollectionForm").serialize(),
		   success: function( result ){

		   	if (result.success){

		   		$( result.data ).hide().appendTo('.collectionsData').slideDown( 1 );

		   		$('.no-collections').remove();
		   		$("#titleCollection").val('');
					$('.note-add').show();

		   		element.removeAttr('disabled').find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');

		   		addImageCollection();

		   	} else {

		   		var error = '';
					var $key = '';
	            for( $key in result.errors ){
	            	error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
	            }

				$('#showErrors').html(error);
				$('#dangerAlert').fadeIn(500)

				element.removeAttr('disabled').find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');

		   	}
		 }//<-- RESULT
	   });//<--- AJAX
});//<----- CLICK

//<--------- Edit Collection
$("#editCollection").on('click',function(e){
	var element  = $(this);

	e.preventDefault();
	element.blur();

	element.attr({'disabled' : 'true'}).find('i').addClass('spinner-border spinner-border-sm align-middle me-1');

		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/collection/edit",
		   dataType: 'json',
		   data: $("#editCollectionForm").serialize(),
		   success: function( result ){

		   	if( result.not_authorized == true ){
		   		$('#dangerAlert').remove()
		   		window.location.reload();
		   	}

		   	if( result.success == true ){
		   		window.location.reload();

		   	} else {

		   		var error = '';
					var $key = '';
	            for( $key in result.errors ){
	            	error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
	            }

				$('#showErrors').html(error);
				$('#dangerAlert').fadeIn(500)

				element.removeAttr('disabled').find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
		   	}
		 }//<-- RESULT
	   });//<--- AJAX
});//<----- CLICK

//<----*********** addImageCollection ************------>
function addImageCollection() {

	$(".addImageCollection").on('click', function(e) {
		var _element = $(this);
		var imageID  = _element.attr("data-image-id");
		var collectionID  = _element.attr("data-collection-id");

		$.ajax({
			headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "GET",
		   url: URL_BASE+'/collection/'+collectionID+'/i/'+imageID,
		   dataType: 'json',
		   data: null,
		   success: function(response) {
				 $('#collections').modal('hide');
		    $('.popout').addClass('alert-success').html(response.data).fadeIn(500).delay(5000).fadeOut();
		   }

	   });
	});
}//<----*********** Click addImageCollection ************------>

addImageCollection();

$("#commentSend").on('click',function(e){
	var element     = $(this);

	e.preventDefault();
	element.blur();

	element.attr({'disabled' : 'true'});



		 $.ajax({
		 	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
		   type: "POST",
		   url: URL_BASE+"/comment/store",
		   dataType: 'json',
		   data: $("#commentsForm").serialize(),
		   success: function( result ){

		   	if( result.success == true ){

		   		$('#comments').val('');
		   		$('#dangerAlertComments').fadeOut(1);
		   		$( result.data ).hide().prependTo('.gridComments').fadeIn(500);
		   		jQuery(".timeAgo").timeago();
		   		$('.noComments').remove();

		   		$('#totalComments').html(result.total);

		   		element.removeAttr('disabled');

		   	} else {


		   		var error = '';
					var $key = '';
	            for( $key in result.errors ){
	            	error += '<li><i class="fa fa-times-circle"></i> ' + result.errors[$key] + '</li>';
	            }

				$('#showErrorsComments').html(error);
				$('#dangerAlertComments').fadeIn(500);

				element.removeAttr('disabled');
		   	}
		 }//<-- RESULT
	   });//<--- AJAX
});//<----- CLICK

$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip()
})

$(document).on('click','#button-reply-msg',function(s){

 s.preventDefault();

 var element     = $(this);
 var error       = false;
 var _message    = $('#message').val();
 var dataWait    = element.attr('data-wait');
 var dataSent    = element.attr('data-send');

 if( _message == '' && trim( _message ).length  == 0 ) {
	 var error = true;
	 return false;
 }

 if( error == false ){
	 $('#button-reply-msg').attr({'disabled' : 'true'}).html(dataWait);

 (function(){
		$("#form_reply_post").ajaxForm({
		dataType : 'json',
		success:  function(result){
		//===== SUCCESS =====//
		if( result.success != false ){
			 $('#message').val('');
				$('#errors').html('').fadeOut();
				$('#button-reply-msg').removeAttr('disabled').html(dataSent);


		 }//<-- e
		 else if( result.error_custom ) {
			 $('#button-reply-msg').removeAttr('disabled').html(dataSent);
			 $('#errors').html(result.error_custom).fadeIn(500);
		 }
			 else {
		 var error = '';
		 var $key = '';
								 for($key in result.errors){
									 error += '<li>* ' + result.errors[$key] + '</li>';
								 }

	 $('#errors').html('<ul class="margin-zero padding-zero">'+error+'</ul>').fadeIn(500);
								 $('#button-reply-msg').removeAttr('disabled').html(dataSent);
		 }

		 if( result.session_null ) {
			 window.location.reload();
		 }
	 }//<----- SUCCESS
	 }).submit();
	 })(); //<--- FUNCTION %

 }//<-- END ERROR == FALSE
});//<<<-------- * END FUNCTION CLICK * ---->>>>

//<----- Notifications
function Notifications() {

	 var title = _title;

	 console.time('cache');

	 $.get(URL_BASE+"/ajax/notifications", function(data) {
		if (data) {

			//* Notifications */
			if (data.notifications != 0) {

				var totalNoty = data.notifications;
				$('.noti_notifications').html(data.notifications).fadeIn();
			} else {
				$('.noti_notifications').removeClass('d-block').addClass('display-none').html('').hide();
			}

			//* Error */
			if (data.error == 1) {
				window.location.reload();
			}

			var totalGlobal = parseInt(totalNoty);

			if (data.notifications == 0) {
				$('.notify').removeClass('d-block').addClass('display-none').hide();
				$('title').html(title);
			}

		if (data.notifications != 0) {
		    $('title').html( "("+ totalGlobal + ") " + title );
		  }

		}//<-- DATA

		},'json');

		console.timeEnd('cache');
}//End Function notifications

// Initiator notifications
if (session_status == 'on') {
	setInterval(Notifications, 5000);
}
//End Notifications

// Cookies
$(document).ready(function() {
	if (Cookies.get('cookiePolicy'));
	else {
		$('.showBanner').fadeIn();
			$("#close-banner").on('click', function() {
					$(".showBanner").slideUp(50);
					Cookies.set('cookiePolicy', true, { expires: 365 });
				});
			}
		});

		$(".btnDownload").on('click', function() {
				$("#alertThanks").delay(2000).slideDown();
			});

			$("#closeThanks").on('click', function() {
					$("#alertThanks").slideUp(200);
				});

	$('#filter, .filter').on('change', function() {
		window.location.href = $(this).val();
	});

	// Show/Hide Password
	$(document).ready(function() {
    $("#showHidePassword").on('click', function() {
        if ($('.showHideInput').attr("type") == "text") {
            $('.showHideInput').attr('type', 'password');
            $('#showHidePassword > i').addClass( "fa-eye-slash" );
            $('#showHidePassword > i').removeClass( "fa-eye" );
        } else if ($('.showHideInput').attr("type") == "password") {
            $('.showHideInput').attr('type', 'text');
            $('#showHidePassword > i').removeClass( "fa-eye-slash" );
            $('#showHidePassword > i').addClass( "fa-eye" );
        }
    	});
		});

		// Copy Link
	var clip = new ClipboardJS('.copy-url');

	clip.on("success", function() {
	  $('.popout').removeClass('popout-error').addClass('popout-success').html('<i class="fa fa-check me-1"></i> '+copiedSuccess).slideDown('200').delay('3000').slideUp('50');
	});

	//<<---- PAGINATION AJAX
    $(document).on('click','#linkPagination .pagination a', function(e) {
			e.preventDefault();
			var link = $(this).attr('href');

			$(this).html('<i class="spinner-border spinner-border-sm align-middle"></i>');

			$.ajax({
				headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
				url: link

			}).done(function(data){

				if (data) {

					if (isProfile) {
						scrollElement('#navProfile');
					} else {
						scrollElement('body');
					}


					$('#linkPagination').remove();

					$('.dataResult').html(data);

					$('.hovercard').hover(
		               function () {
		                  $(this).find('.hover-content').fadeIn();
		               },
		               function () {
		                  $(this).find('.hover-content').fadeOut();
		               }
		            );

					$('#imagesFlex').flexImages({ rowHeight: 320 });
					jQuery(".timeAgo").timeago();

					$('[data-toggle="tooltip"]').tooltip();
				} else {
					$('.popout').addClass('popout-error').html(error).fadeIn('500').delay('5000').fadeOut('500');
				}
				//<**** - Tooltip
			}).fail(function(jqXHR, ajaxOptions, thrownError)
 	 	 {
			 $('.popout').addClass('popout-error').html(error + ': '+thrownError).fadeIn('500').delay('5000').fadeOut('500');
		 });
		});//<<---- PAGINATION AJAX

		// Send code TwoFactorAuth
		$('#btn2fa').on('click', function(e) {

			e.preventDefault();
			var $element = $(this);

			$element.attr({'disabled' : 'true'}).find('i').addClass('spinner-border spinner-border-sm align-middle me-1');

			(function() {
				 $("#formVerify2fa").ajaxForm({
				 dataType : 'json',
				 success:  function(response) {
					 if (response.success) {

						 window.location.href = response.redirect;

					 } else {
						 var error = '';
		 				var $key = '';

		 				for ($key in response.errors) {
		 					error += '<li><i class="fa fa-times-circle"></i> ' + response.errors[$key] + '</li>';
		 				}

		 				$('#showErrorsModal2fa').html(error);
		 				$('#errorModal2fa').fadeIn(500);

		 				$element.removeAttr('disabled');
		 				$element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					 }
				 },
				 error: function(responseText, statusText, xhr, $form) {
							// error
							swal({
									type: 'error',
									title: 'Oops...',
									text: ''+error+' ('+xhr+')',
								});
								$element.removeAttr('disabled');
								$element.find('i').removeClass('spinner-border spinner-border-sm align-middle me-1');
					}
				}).submit();
			})(); //<--- FUNCTION %
		});// End

		// Resend code
	  $('.resend_code').on('click',function(e) {

	 	 e.preventDefault();

	 	 var element = $(this);
		 var btnTwoFa = $('#btn2fa');
	 	 element.removeClass('resend_code').addClass('text-decoration-none');

		 btnTwoFa.attr({'disabled' : 'true'});

		 $('#resendCode').addClass('text-muted').html(resending_code);

	 	 $.ajax({
	 		 headers: {
	 				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	 			 },
	 		 type : 'post',
	 		 url : URL_BASE+'/2fa/resend',
	 		 success:function(response) {
	 			 if (response.success) {

					 $('.popout').removeClass('popout-error').addClass('popout-success').html(response.text).slideDown('500').delay('5000').slideUp('500');
	 				 element.addClass('resend_code').removeClass('text-decoration-none');
	 				 btnTwoFa.removeAttr('disabled');
					 $('#resendCode').removeClass('text-muted').html(resend_code);
	 			 }
	 		 }
	 	 }).fail(function(jqXHR, ajaxOptions, thrownError)
	 	 {
	 		 $('.popout').removeClass('popout-success').addClass('popout-error').html(error).slideDown('500').delay('5000').slideUp('500');
			 element.addClass('resend_code');
			 btnTwoFa.removeAttr('disabled');
	 	 });//<--- AJAX
	  });//==== End

		$(".toggle-menu, .overlay, .close-menu-mobile").on('click', function() {
			$('.overlay').toggleClass('open');
		});

		$(".downloadableButton").on('click', function(e) {
			e.preventDefault();
				var form = $(this).parents('form');
				var button = $(this);

				button.attr({'disabled' : 'true'}).html('<i class="spinner-border spinner-border-sm align-middle me-1"></i> '+downloading);
					form.submit();

					setTimeout(function(){
					    button.removeAttr('disabled').html('<i class="bi-cloud-arrow-down me-1"></i> '+download);
					}, 2000);

			});

			// Cookies Announcements
		$(document).ready(function() {
			if (Cookies.get('announcementsAlert'+announcement_cookie));
			else {
				$('.announcements').show();
					$("#closeAnnouncements").on('click', function() {
							$(".announcements").slideUp(50);
							Cookies.set('announcementsAlert'+announcement_cookie, true, { expires: 365 });
						});
					}
				});

})(jQuery);
