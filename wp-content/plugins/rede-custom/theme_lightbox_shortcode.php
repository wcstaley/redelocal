<?php
//custom Publix Theme Lightbox 



function get_theme_fields() {
	
	$themeOptions = get_field("theme_pages", 'options');
	
	
	ob_start();
	?>
	<style>
		.lightbox-trigger{
			padding:10px;
			float:left;
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
			background-color:red;
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
	}
	</style>
	<script>
		(function($){
			$(document).ready(function(){
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
			})
		})(jQuery);
	</script>
		<div id="lightbox-overlay">This is the overlay
			<div id="lightbox-content">
				<div id="lightbox-appender"></div>
				<div class="button close-lightbox">Close</div>
			</div>
			
		</div>
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
 }

add_shortcode('theme_fields', 'get_theme_fields');

?>

