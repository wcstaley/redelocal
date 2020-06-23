<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function rede_field_creative_tabs(){ 
	$form_template = basename( get_page_template() );
	$current_user = wp_get_current_user();
	$pageflexOn = rwmb_meta( 'pageflexOn', array( 'object_type' => 'user' ), $current_user->ID );
	// echo $form_template;
	// die();


	// On-pack	Tear Pad	AR-002
	// On-pack	Hang Tag	SMH-0007-13HT
	// On-pack	Necker 1	CD-0009-13NK
	// On-pack	IRC	CD-011-14
	// On-pack	Tear Pad	PINN-001-15
	// On-pack	Shelf Talker	PF-001-16
	// on-pack	Welchs Natural Necker	WEL-002-17
	// mobile	Banner	PF-002-16
	// digital billboard	Billboard	PF-003-16
	$creative_data = array(
		'preview_name'	=> '',
		'preview_bg' 	=> '',
		'preview_width'	=> '',
		'preview_height'=> '',
		'p_top'			=> '',
		'p_left'		=> '',
		'p_width'		=> '',
		'pf_id' 		=> '',
		'pf_docID'		=> ''
	);

	if($form_template === 'service-on-pack.php'){
		$creative_data = array(
			'preview_name'	=> 'IRC',
			'preview_bg' 	=> 'images/Tactics/smh2.jpg',
			'preview_width'	=> '194px',
			'preview_height'=> '400px',
			'p_top'			=> '120px',
			'p_left'		=> '61px',
			'p_width'		=> 'auto',
			'pf_id' 		=> 'SMH-0007-13HT',
			'pf_docID'		=> 'D-56E8F410'
		);
	} else if($form_template === 'service-pos-materials.php'){
		$creative_data = array(
			'preview_name'	=> 'IRC',
			'preview_bg' 	=> 'images/Tactics/smh2.jpg',
			'preview_width'	=> '194px',
			'preview_height'=> '400px',
			'p_top'			=> '120px',
			'p_left'		=> '61px',
			'p_width'		=> 'auto',
			'pf_id' 		=> 'SMH-0007-13HT',
			'pf_docID'		=> 'D-56E8F410'
		);
	} else if($form_template === 'service-out-of-home.php'){
		$creative_data = array(
			'preview_name'	=> 'Billboard',
			'preview_bg' 	=> 'images/Tactics/billboard.png',
			'preview_width'	=> '211px',
			'preview_height'=> '260px',
			'p_top'			=> '13px',
			'p_left'		=> '5px',
			'p_width'		=> '201px',
			'pf_id' 		=> 'PF-003-16',
			'pf_docID'		=> 'D-6DB2F40C'
		);
	} else if($form_template === 'service-mobile-media.php'){
		$creative_data = array(
			'preview_name'	=> 'Web Leader Board',
			'preview_bg' 	=> 'images/Tactics/smh1.png',
			'preview_width'	=> '191px',
			'preview_height'=> '400px',
			'p_top'			=> '136px',
			'p_left'		=> '23px',
			'p_width'		=> '143px',
			'pf_id' 		=> 'PF-002-16',
			'pf_docID'		=> 'D-6DB2F40B'
		);
	} else if($form_template === 'service-coupon.php'){
		$creative_data = array(
			'preview_name'	=> 'Web Leader Board',
			'preview_bg' 	=> 'images/Tactics/smh1.png',
			'preview_width'	=> '191px',
			'preview_height'=> '400px',
			'p_top'			=> '136px',
			'p_left'		=> '23px',
			'p_width'		=> '143px',
			'pf_id' 		=> 'PF-002-16',
			'pf_docID'		=> 'D-6DB2F40B'
		);
	} else if($form_template === 'service-social.php'){
		$creative_data = array(
			'preview_name'	=> 'Web Leader Board',
			'preview_bg' 	=> 'images/Tactics/smh1.png',
			'preview_width'	=> '191px',
			'preview_height'=> '400px',
			'p_top'			=> '286px',
			'p_left'		=> '23px',
			'p_width'		=> '143px',
			'pf_id' 		=> 'SMH-010-15',
			'pf_docID'		=> 'D-86AD7F75'
		);
	} else if($form_template === 'service-shroud.php'){

	} else if($form_template === 'service-sas-at-shelf.php'){
		$pageflexOn = false;
	}


	?>
    <div class="nav-tabs-horizontal">
        <ul class="tabs" data-tabs id="creative-tabs">
        	<?php if($pageflexOn){ ?>
	            <li class="tabs-title is-active" role="presentation"><a href="#exampleTabsOne" aria-selected="true">Templates</a></li>
	            <li class="tabs-title" role="presentation"><a href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Upload your own</a></li>
            <?php } else { ?>
            	<li class="tabs-title is-active" role="presentation"><a href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Upload your own</a></li>
            <?php } ?>
            <li class="tabs-title" role="presentation"><a href="#exampleTabsThree" aria-controls="exampleTabsThree" role="tab">Have Red/E develop</a></li>
        </ul>
        <div class="tabs-content" data-tabs-content="creative-tabs">
        	<?php if($pageflexOn){ ?>
            <div class="tabs-panel is-active" id="exampleTabsOne" role="tabpanel">
                <div class="padding-30 border bg-grey-100 margin-bottom-30">
                    <div class="row">
                		<div class="columns medium-12 text-center">
                			
                        	<div class="pf-preview-container">
                                <a target="_blank" href="http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?DocID=<?php echo $creative_data['pf_docID']; ?>&UserName=dalim20151">
                                	<img id="pf-preview" class="border" src="http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetBitMap.aspx?DocID=<?php echo $creative_data['pf_docID']; ?>&UserName=dalim20151"/>
                        		</a>
                        	</div>
                        	
                        	<a class="button success" type="button" href="<?php echo home_url('/', 'http'); ?>?pfid=<?php echo $creative_data['pf_id'];?>&pageflexredirect" target="_blank">Customize</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="tabs-panel <?php if(!$pageflexOn){ echo 'is-active'; } ?>" id="exampleTabsTwo" role="tabpanel">
                <div class="padding-30 border bg-grey-100 margin-bottom-30">
                	<?php if($pageflexOn){ ?>
                    <h4>Or, You Can Upload Your Own Creative</h4>
                    <?php } else { ?>
                    <h4>Upload Your Own Creative</h4>
                    <?php } ?>
                    <input type="file" id="fileupload" class="hide" />
                    <?php $ajax_nonce = wp_create_nonce( "media-nonce" ); ?>
        			<input type="hidden" id="media-nonce" value="<?php echo $ajax_nonce; ?>"> 
                    <a class="button success" id="upload"><span class="icon wb-upload margin-right-5" aria-hidden="true"></span>Upload File</a>
                    <a class="button secondary hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                    <div class="progress margin-vertical-20">
                        <div class="progress-bar" role="progressbar" style="width: 0%;">
                        </div>
                    </div>
                    <div class="fileinfo"></div>
                    <input type="hidden" id="fileguid" name="fileguid" />
                    <input type="hidden" id="filename" name="filename" />
                    <a href="<?php echo home_url('creative-specs'); ?>" target="_blank">Creative Specs</a>
                </div>
            </div>
            <div class="tabs-panel" id="exampleTabsThree" role="tabpanel">
                <div class="padding-30 border bg-grey-100 margin-bottom-30">
                    <h4>Or, Click Below to Get Your Creative Developed by Red/E</h4>
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" id="tactic-custom" name="tactic-custom" value="1">
                        <label for="tactic-custom" class="font-size-16 font-weight-400">Red/E develop creative</label>
                    </div>
                    <span class="help-block">The campaign start date is subject to change upon receiving, designing and approving creative elements in a timely manner. RED/E will work with you to accept all existing or newly furnished assets as quickly as possible to meet the requested program start date.</span>
                	<div class="reveal" id="redecreativemodal" data-reveal>
						<h4>Custom Creative Notice</h4>
						<p>RED/E will contact you for creative development within 24-48hrs upon receiving campaign order.</p>
						<button class="close-button" data-close aria-label="Close modal" type="button">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>	