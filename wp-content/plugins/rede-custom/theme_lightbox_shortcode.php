<?php
//custom Publix Theme Lightbox 



function get_theme_fields() {
	
	$themeOptions = get_field("theme_pages", 'options');
	
	
	ob_start();
	?>
	<style>
		.lightbox-trigger{
			padding:10px;
			display:inline-block;
			cursor:pointer;
			width:auto;
		}
		.lightbox-trigger p, .lightbox-trigger .theme-icon{
			display:inline-block;
			text-align:right;
			font-size:14px;
			font-family: Roboto, sans-serif;
		}
		.lightbox-trigger .theme-icon{
			height:20px;
			width:20px;
			border-radius:90px;
			margin-right:5px;
			position:relative;
			top:4px;
			background-size:cover;
			background-position:center;
			background-repeat:no-repeat;
		}
		#lightbox-overlay{
			position:fixed;
			top:0;
			left:0;
			min-width:100%;
			min-height:100vh;
			background:rgba(0,0,0,.5);
			z-index:1000;
			display:none;
		}
	    #lightbox-content{
	    	width: 40%;
	    	margin: 0 auto;
	    	top: 0;
	   	 	bottom: 0;
	    	position: relative;
	    	left: 0;
	    	right: 0;
	    	background: white;
	   	 	height: auto;
	    	top: 50%;
	    	transform: translateY(50%);
	    	padding: 20px;
		}
		.lightbox-title-wrap{
		    border-top: solid 1px #eaeaea;
		    border-bottom: none !important;
		    padding: 12px !important;
		    background-color: #eaeaea;
		    margin-bottom: 0px;
		    margin-top: 30px !important;
		}
		.lightbox-title-wrap h2.gsection_title{
			margin-top:0 !important;
		}
		.close-lightbox{
    		background: #D3D3D3;
    		color: #323232;
    		margin-right: 10px;
			transition:200ms;
		}
		.close-lightbox:hover{
			background: #558743;
			transition:200ms;
    		color: #323232;
		}
		.light-box-content-wrap{
			display:none;
		}
		.light-box-content-wrap p{
			margin:40px 0;
		}
		body.no-scroll{
			overflow:hidden;
		}
		.coop-calendar-entry .dates, .coop-calendar-entry .holidays{
			font-size:12px !important;
		}
		#coop-calendar-wrapper .coop-calendar-entry span.dates, #coop-calendar-wrapper .coop-calendar-entry span.holidays{
			font-size:12px !important;
		}
		#coop-calendar-wrapper .coop-calendar-entry span.holidays{
			margin-left:0 !important;
		}
		.lightbox-trigger-wrap{
			margin-top:40px;
		}
		
		/****reater offer form styles**/
		.gfield_repeater_container{
			margin-top:40px !important;
		}
		.gfield_repeater_container legend{
		    font-weight: 400;
		    font-size: 1.25em;
			color: #438938 !important;
			background: #D9E9D4;
			padding:0 10px 0;
			text-transform:uppercase;
		}
		.gfield_repeater_container .gfield_repeater_items{
			background-color:#F2FAEF;
			padding:20px;
		}
		.hastext label{
			display:none !important;
		}
		.gfield_repeater_cell label{
			color:#464646 !important;
			font-weight:700 !important;
			font-family: 'Roboto', 'sans-serif' !important;
		}
		.gfield_repeater_cell .gfield_radio label{
			font-weight: 300 !important;
		    color: #0a0a0a !important;
		}
		.gfield_repeater_cell input, .gfield_repeater_cell select{
			background-color:#F2FAEF !important;
		}
		.gfield_repeater_cell ::-webkit-input-placeholder { /* Edge */
			font-weight: 400 !important;
		    color: #0a0a0a !important;
			font-style: italic;
			font-size:12px;
			font-family: 'Roboto', 'sans-serif' !important;
		}

		.gfield_repeater_cell :-ms-input-placeholder { /* Internet Explorer 10-11 */
			font-weight: 400 !important;
		    color: #0a0a0a !important;
			font-style: italic;
			font-size:12px;
			font-family: 'Roboto', 'sans-serif' !important;
		}

		.gfield_repeater_cell ::placeholder {
			font-weight: 400 !important;
		    color: #0a0a0a !important;
			font-style: italic;
			font-size:12px;
			font-family: 'Roboto', 'sans-serif' !important;
		}
		.gfield_repeater_cell select option { /* Edge */
			font-weight: 400 !important;
		    color: #0a0a0a !important;
			font-style: italic;
			font-size:12px;
			font-family: 'Roboto', 'sans-serif' !important;
		}
		.gfield_repeater_cell.hasselect {
			width: 33%;
			float: right;
			clear: right;
			position: relative;
			top: -58px;
		}
		.hastext input, .gfield_repeater_cell.hasselect select{
			padding:0 10px !important;
			
		}
		.gfield_repeater_cell.hasselect select[multiple=multiple]{
			height:65px !important;
			overflow-y:scroll !important;
			width:100%;
		}
		.offer-left{
			float:left;
			width:75%;
		}
		.offer-right{
			float:left;
			width:25%;
		}
		.gfield_repeater_cell.hastext{
			display:inline-block;
			margin-top: 5px;
			width: 30%;
			margin-right: 10px;
			position:relative;
			top:-20px;
			float: left;
		}
		.gfield_repeater_cell.hastext input{
			width:100% !important;
		}
		.gfield_repeater_cell.hasselect label{
			display:inline-block;
			margin-right:5px;
		}
		.gfield_repeater_cell.hasselect .gfield_description{
			display:inline !important;
			font-size:10px;
		}
		.gfield_repeater_cell.hasradio label, .gfield_repeater_cell.hasradio .ginput_container_radio{
			float:left;
		}
		.gfield_repeater_cell.hasradio .ginput_container_radio{
			margin-top:0 !important;
		}
		.gfield_repeater_cell.hasradio .ginput_container_radio ul li{
			display:inline-block;
			margin-left:10px !important;
		}
		
		.gfield_repeater_cell.hasradio .ginput_container_radio ul li input{
			float:left;
		}
		.gfield_repeater_items .gfield_repeater_buttons{
			padding-top:0;
			/*position:absolute;
			top:0;
			right:40px;*/
		}
		.gfield_repeater_items .gfield_repeater_buttons button{
			margin-top:5px;
		}
		.add_repeater_item::before {
		  	content: "Add Offer";
			position: absolute;
		    font-size: 12px;
		    margin-left: 12px;
		    margin-top: 2px;
			
		}
		.remove_repeater_item::before {
		  	content: "Delete Offer";
		    font-size: 12px;
		    margin-left: 12px;
		    margin-top: 2px;
			position:absolute;
		}
		.gfield_repeater_buttons .add_repeater_item_plus, .gfield_repeater_buttons .remove_repeater_item_minus{
			border:none !important;
		}
		.gfield_repeater_buttons button{
			display:inline-block;
			width:70px !important;
			text-align:left;
		}
		.gfield_repeater_wrapper{
			display:none;
		}
		.gfield_repeater_buttons{
			display:none;
		}
	}
	</style>
	<script>
		(function($){
			$(document).ready(function(){
				$('.gfield_repeater_wrapper').attr('data-max_items', '6');
				$('#lighbox-overlay').prependTo("body");
				$('.light-box-content-wrap').each(function(){
					$(this).appendTo('#lightbox-appender');
				});

				$('.lightbox-trigger').click(function(){
					$('#lightbox-overlay').fadeIn('slow');
					$('#lightbox-content').show();
					var triggerName = $(this).attr('data-service-to-show');
					$('.light-box-content-wrap').each(function(){
						if($(this).hasClass(triggerName)){
							$(this).show();
						}
					});
					$('body').addClass('no-scroll');
				});
				$('.close-lightbox').click(function(){
					$('#lightbox-overlay').fadeOut('slow')
					$('#lightbox-content').hide();
					$('.light-box-content-wrap').hide();
					$('body').removeClass('no-scroll');
				})

				//the following is code for the offering repeater
				var offerInfoSplitter = "<div class='offfer-splitter-wrap cf'><div class='offer-left'></div><div class='offer-right'></div><div class='clearit' style='clear:both'></div></div>";

				$('.gfield_repeater_cell').each(function(){
					if($(this).children('.ginput_container_text').length > 0){
						$(this).addClass('hastext');
					} else if($(this).children('.ginput_container_radio').length > 0){
						$(this).addClass('hasradio');
					} else if($(this).children('.ginput_container_multiselect').length > 0){
						$(this).addClass('hasselect');
					}

				})
				// $('.gfield_repeater_item').each(function(){
				// 	$(this).prepend(offerInfoSplitter);
				// 	$(this).children('.hasradio').appendTo('.offer-left');
				// 	$(this).children('.hastext').appendTo('.offer-left');
				// 	$(this).children('.hasselect').appendTo('.offer-right');
				// })
				var clearDiv = "<div style='clear:both'></div>";
				$('.hasradio').each(function(){
					$(this).append(clearDiv);
				})

				//CODE FOR SAVINGS PLACEMENT SELECTION PROCESS
				$('input:radio[name="input_2380"]').change(function(){
					$('.gfield_repeater_wrapper').show();

			        if (this.checked && this.value == 'Single Offer (select options)|0') {
						$('.gfield_repeater_wrapper').attr('data-max_items', '1');
						$('.gfield_repeater_buttons').hide();

			        } else if (this.checked && this.value == 'Quarter Page  <b>$22,000.00</b>|22000') {
						$('.gfield_repeater_wrapper').attr('data-max_items', '3');
						$('.gfield_repeater_buttons').show();
			        } else if (this.checked && this.value == 'Half Page.  <b>$40,000.00</b>|40000') {
						$('.gfield_repeater_wrapper').attr('data-max_items', '6');
						$('.gfield_repeater_buttons').show();
			        } else if (this.checked && this.value == 'Full Page  <b>$75,000.00</b>|75000') {
						$('.gfield_repeater_wrapper').attr('data-max_items', '12');
						$('.gfield_repeater_buttons').show();

			        }
			    });
			})
		})(jQuery);
	</script>
		<div style="clear:both;"></div>
		<div id="lightbox-overlay">
			<div id="lightbox-content">
				<div id="lightbox-appender"></div>
				<div class="button close-lightbox">Close</div>
			</div>
			
		</div>
	<div class="lightbox-trigger-wrap">
	 <?php 
	foreach($themeOptions as $themeOption){
		$name =  $themeOption['value'];
		$key =  $themeOption['key'];
		$description =  $themeOption['description'];
		$icon_get = $themeOption['image'];
		$iconSize = 'large';
		$icon = wp_get_attachment_image_src( $icon_get, $iconSize );
		
	?>
	
	
		<div class="lightbox-trigger" data-service-to-show="<?= $key; ?>" ><span class="theme-icon" style="background-image:url('<?php echo $icon[0]; ?>')"></span><p><?= $name; ?></p></div>
		
		<div class="light-box-content-wrap <?= $key; ?>">
			<div class="lightbox-title-wrap">
				<h2 class="gsection_title"><?= $name; ?></h2>
			 </div>
			<p><?= $description; ?></p>
			 
		</div>
		
	 <?php }
	 return ob_get_clean();
	 ?>
 </div>
 <?php  
 }

add_shortcode('theme_fields', 'get_theme_fields');