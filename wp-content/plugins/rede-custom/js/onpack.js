const formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 2
})

jQuery(document).ready(function($) {
 
    // We'll pass this variable to the PHP function onpack_ajax_request
    var price = 0;
    
    $(".budget_field,.tactic_field,.per_store_qty_field").change(function(){
	    runOnpackPricing();
    });
    
    function runOnpackPricing() {
	    var tactic = $( ".tactic_field option:selected" ).val();
	    var qtyPerStore = $(".per_store_qty_field input").val()
	    console.log(qtyPerStore);
	    // This does the ajax request
	    $.ajax({
	        url: onpack_ajax_obj.ajaxurl,
	        data: {
		        'dataType' : 'json',
		        'selectedTactic' : tactic,
		        'qtyPerStore' : qtyPerStore,
	            'action': 'onpack_ajax_request',
	            'price' : price,
	            'nonce' : onpack_ajax_obj.nonce
	        },
	        success:function(data) {
	            // This outputs the result of the ajax request
	            console.log(data);
	            //UPDATE THIS AFTER WILLIAM IS DONE WITH THE RETAILER FIELD
	            //WILL ALSO NEED TO ADD IN MARKET DATE (WEEK) ONCE THAT FIELD IS READY
	            //JSON.parse(data);
	            console.log(data);
	            $(".input-total-stores").text('640');
	            $(".input-total-cost-per-store").text(formatter.format(data.per_store));
	            $(".input-total-order-cost").text(formatter.format(data.price));
	        },
	        error: function(errorThrown){
	            console.log(errorThrown);
	        }
	    }); 
    }
});