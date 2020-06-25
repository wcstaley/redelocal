jQuery(document).ready(function($) {

	//appends an "active" class to .popup and .popup-content when the "Open" button is clicked
	$("#conditionally-approve").on("click", function() {
	  $(".conditionally-approve.popup-overlay, .conditionally-approve.popup-content").addClass("active");
	});
	
	$("#request-form-resubmit").on("click", function() {
	  $(".reject.popup-overlay, .reject.popup-content").addClass("active");
	});
	
	$("#soft-approve-order").on("click", function() {
	  $(".soft-approve.popup-overlay, .soft-approve.popup-content").addClass("active");
	});
	
	$("#soft-deny").on("click", function() {
	  $(".soft-deny.popup-overlay, .soft-deny.popup-content").addClass("active");
	});
	
	//trigger submission of approval form
	$("#approve-order").click(function(){
		$("#gform_submit_button_10").click();
	})
	
	
	
});

//add close buttons to rejection gravity forms
jQuery(document).on('gform_post_render', function(event, form_id, current_page){
 
        jQuery("#gform_wrapper_8 #gform_submit_button_8").before("<div class='button close-popup'>Close</div>");
        jQuery("#gform_wrapper_9 #gform_submit_button_9").before("<div class='button close-popup'>Close</div>");
        jQuery("#gform_wrapper_14 #gform_submit_button_14, #gform_wrapper_15 #gform_submit_button_15").before("<div class='button close-popup'>Close</div>");
           
        //trigger modal close. 
        jQuery(".popup-overlay.conditionally-approve .button.close-popup").on("click", function() {
		  jQuery(".popup-overlay.conditionally-approve, .popup-content.conditionally-approve").removeClass("active");
		});
		
		 jQuery(".popup-overlay.reject .button.close-popup").on("click", function() {
		  jQuery(".popup-overlay.reject, .popup-content.reject").removeClass("active");
		});
		
		jQuery(".popup-overlay.soft-approve .button.close-popup").on("click", function() {
		  jQuery(".popup-overlay.soft-approve, .popup-content.soft-approve").removeClass("active");
		});
		
		jQuery(".popup-overlay.soft-deny .button.close-popup").on("click", function() {
		  jQuery(".popup-overlay.soft-deny, .popup-content.soft-deny").removeClass("active");
		});
		
		//move additional comment box on review page
		//jQuery("#gform_fields_6_2 li:last-child").appendTo("#gform_fields_6_2 > li .please-review");
		
		//add body class if we are on the review page
			if( jQuery("#gform_page_6_2").css("display") == "none") {
				//console.log('not on review page');
			} else {
				//console.log('we are on the review page');
				jQuery("body").addClass('review_active');
				jQuery("#gform_wrapper_6").prepend('<div class="please-review"><div>Please review your submission below. If everything is correct, please click Submit at the bottom of this page. By submitting this you are liable for the costs of the program as identified here. Cancellation or modification of order will result in any costs incurred to date. By submitting this form, you agree to our <a style="text-decoration:underline;" href="http://publixmarketin.wpengine.com/order-submission-terms/" target="_blank">terms & conditions</a>.</div></div>');
			}
    });
    