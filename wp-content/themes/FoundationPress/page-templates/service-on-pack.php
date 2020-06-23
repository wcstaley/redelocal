<?php
/* Template Name: On-Pack */

get_header(); ?>

<div class="main-container">
	<div class="main-grid">
		<main class="main-content-full-width">
			<?php
			while ( have_posts() ){
				the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header>
					<h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php get_template_part( 'template-parts/form-messages', 'page' ); ?>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				    <div class="bg-grey-100">
				        <div class="container">
				            <div class="row">
				                <div class="col-md-10 col-md-offset-1 padding-vertical-50">
				                    <form class="padding-50 bg-white shadow">
				                        <div class="padding-bottom-30">
				                            <h4>1. Program Name</h4>
				                            <input type="text" class="form-control" id="ordername" name="ordername">
				                            <span class="help-block">Enter a name for the new On-Pack program you wish to create.</span>
				                        </div>
				                        <div class="padding-bottom-30">
                                            <h4>2. Select a Brand</h4>
                                            <div class="example">
                                                <input type="text" list="brands" id="brand" name="brand">
                                                <datalist id="brands">
                                                    <?php 
                                                    $brands = get_user_brands();
                                                    foreach ($brands as $brand) { 
                                                    ?>
                                                    <option value="<?php echo $brand; ?>">
                                                    <?php } ?>
                                                </datalist>
                                            </div>
                                            <span class="help-block">Enter the brand or business name that will be featured as part of your activation.</span>
                                        </div>

				                        <div class="padding-bottom-30 input-container">
                                            <h4>3. Budget</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="budget" name="budget">
                                            </div>
                                        </div>

				                        <div class="padding-bottom-30">
				                            <h4>4. Select a Tactic</h4>
				                            <div class="example">
				                            	<script>
				                            		var rede_tactics = {
				                            			"Hang Tag": [
															"SMH-0007-13HT",
															'D-56E8F410'
														],
				                            			"Tear Pad 1": [
				                            				"AR-002",
				                            				""
				                            			],
				                            			"Tear Pad": [
															"PINN-001-15",
															'D-56E8F40D'
														],
														"Necker": [
															"CD-0009-13NK",
															"D-56E8F40A"
														],
														"IRC": [
															"CD-011-14",
															"D-56E8F409"
														],
														"Shelf Talker": [
															"PF-001-16",
															"D-56E8F40C"
														],
														"Welchs Natural Necker": [
															"WEL-002-17",
															"D-56E8F40B"
														],
													};


				                            	</script>


				                                <select data-plugin="selectpicker" id="tactic" name="tactic">
				                     <!--                <option value="Tear Pad 1">Tear Pad 1</option> -->
				                     				<option value="Hang Tag">Hang Tag</option>
				         <!--                            <option value="Tear Pad">Tear Pad</option> -->
				                                    <option value="Necker">Necker</option>
				                                    <option value="IRC">IRC</option>
<!-- 				                                    <option value="Shelf Talker">Shelf Talker</option> -->
				                                    <!-- <option value="Welchs Natural Necker">Welchs Natural Necker</option> -->
				                                </select>
				                            </div>
				                        </div>
				                        <div class="padding-bottom-30">
				                            <h4>5. Select Your Creative</h4>
                                            <?php
                                            $current_user = wp_get_current_user();
                                            $pageflexOn = rwmb_meta( 'pageflexOn', array( 'object_type' => 'user' ), $current_user->ID );
                                            if($pageflexOn){
                                            ?>
                                            <span class="help-block margin-bottom-20">Please select from available templates below, upload your own, or have Red/E develop your creative.</span>
                                            <?php } else { ?>
                                            <span class="help-block margin-bottom-20">Please upload your creative, or have Red/E develop your creative.</span>
                                            <?php } ?>
                                             <?php rede_field_creative_tabs(); ?>
				                        </div>

				                        <div class="padding-bottom-30">
				                            <h4>6. Per Store Quantity</h4>
				                            <input type="number" class="form-control" id="quantity" name="quantity" value="10">
				                            <span class="help-block">How many products per store will receive this on-pack vehicle?</span>
				                        </div>

				                        <div class="padding-bottom-30">
				                            <h4>7. What Products Will Receive the Tactic?</h4>
				                            <input type="file" id="fileuploadsku" class="hide">
                                            <a class="button success" id="uploadsku"><i class="fi-upload small"></i> Upload File</a>
                                            <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                            
                                            <div class="progress margin-vertical-20">
                                                <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                </div>
                                            </div>
                                            <div class="fileinfo"></div>
                                            <input type="hidden" id="skufileguid" name="skufileguid">
                                            <input type="hidden" id="skufilename" name="skufilename">
				                            <span class="help-block">Upload your product list with SKUs and/or product names and descriptions.</span>
				                        </div>

				                        <div class="padding-bottom-30">
				                            <h4>8. Market Date (Week Of)</h4>
				                            <div class="input-group">
				                                <span class="input-group-addon">
				                                    <i class="icon wb-calendar" aria-hidden="true"></i>
				                                </span>
				                                <input type="text" class="form-control datepicker" data-plugin="datepicker" id="marketdate" name="marketdate">
				                            </div>
				                            <span class="help-block">The date you wish for the campaign to start - the earliest a campaign can start is 3 weeks out.</span>

				                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>9. Select Retailer Audience</h4>
                                            <span class="help-block">Select the ideal retailer target you want to reach for your activation or upload a custom store list.</span>
                                            <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#retTabsOne" aria-selected="true">Retailers</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#regTabsTwo" aria-controls="regTabsTwo" role="tab">Upload your own</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="retTabsOne" role="tabpanel">
                                                        <div class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" id="exampleAccordionDefault">

                                                            <?php
                                                            $stores = get_stores();
                                                            $i = 0;
                                                            foreach ($stores as $storetype=>$storeGroup){
                                                                $i++; ?>
                                                                <div class="accordion-item" data-accordion-item>
                                                                    
                                                                    <a class="panel-title collapsed accordion-title" href="#exampleCollapseDefaultOne<?php echo $i; ?>" data-parent="#exampleAccordionDefault<?php echo $i; ?>">
                                                                        <h4 class="padding-0 margin-0"><?php echo $storetype; ?></h4>
                                                                    </a>
                    
                                                                    <div class="accordion-content" data-tab-content id="exampleCollapseDefaultOne<?php echo $i; ?>">
                                                                        <div class="panel-body">
                                                                        <?php $u = 0;
                                                                        foreach ($storeGroup as $storeList){
                                                                            foreach ($storeList as $store){
                                                                                // print_r($store);
                                                                                // die();
                                                                                if($store['num'] === 0){?>
                                                                                    <div class="checkbox-custom checkbox-primary">
                                                                                        <input type="checkbox" name="store" value="<?php echo $store['val']; ?>" data-num="0" class="parent-store-check" />
                                                                                        <label class="font-size-16 font-weight-500"><?php echo $store['name']; ?></label>
                                                                                    </div>
                                                                                <?php } else { 
                                                                                    $parentNum = explode('-', $store['val']);
                                                                                    $parentNum = $parentNum[0];

                                                                                ?>
                                                                                    <div class="checkbox-custom checkbox-primary">
                                                                                        <input type="checkbox" name="store" value="<?php echo $store['val']; ?>" data-num="<?php echo $store['num']; ?>" class="parent-store-check p<?php echo $parentNum; ?>">
                                                                                        <label for="i1" class="font-size-16 font-weight-300"><?php echo $store['name']; ?> (<?php echo number_format($store['num']); ?>)</label>
                                                                                    </div>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tabs-panel" id="regTabsTwo" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Upload your own custom list</h4>
                                                            <input type="file" id="fileuploadlist" class="hide">
                                                            <a class="button success" id="uploadlist"><i class="fi-upload small"></i> Upload File</a>
                                                            <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            
                                                            <div class="progress margin-vertical-20">
                                                                <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="fileinfo"></div>
                                                            <h4>Store Count</h4>
															<input type="text" class="form-control" id="customstorecount" name="customstorecount">
                                                            <span class="help-block">Total store count in your uploaded file</span>
                                                            <input type="hidden" id="customlistguid" name="customlistguid">
                                                            <input type="hidden" id="customlistname" name="customlistname">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

			                            <div class="padding-bottom-30">
			                                <h4>10. Confirm and Proceed to Checkout</h4>
			                                <div class="row">
			                                    <div class="col-md-9">
			                                        <table class="table table-striped border">
			                                             <tr>
			                                                <td>In Market Date (week of)
			                                                </td>
			                                                <td class="summary-date">
			                                                </td>
			                                            </tr>
			                                            <tr>
			                                                <td>Total Stores
			                                                </td>
			                                                <td class="summary-total-stores">0
			                                                </td>
			                                            </tr>
			                                            <tr>
			                                                <td>Total Cost Per Store
			                                                </td>
			                                                <td class="summary-cost-per-store">$0
			                                                </td>
			                                            </tr>
			                                            <tr>
			                                                <td>Total Order Cost
			                                                </td>
			                                                <td class="summary-total-cost">$0
			                                                </td>
			                                            </tr>
			                                        </table>
			                                        <span class="font-size-12"><ul>
													<li>From checkout to placement on your product or shelf, Red/E Order costs are all inclusive.</li>
													<li>* Confirm In-Market Date & Order Total Cost, Proceed to Design & Final Approvals.</li>
													<li>Please allow a minimum of 3 weeks from order receipt to retail/shelf placement.</li>
													<li>If total selected store count exceeds your budget, we will proportionately reduce your store count to reflect the budgeted amount.</li></ul> </span>
			                                    </div>
			                                </div>
			                            </div>
			                            <?php 
                                        $update_post = false;
                                        if(isset($_GET['order-id'])){ 
                                            $order_status = get_post_meta($_GET['order-id'], 'order_status', true);
                                            if($order_status !== 'Pending Confirmation'){
                                                $update_post = true;
                                            }
                                        }
                                        if($update_post){ ?>
                                        <button class="button secondary update">Update</button>
                                        <button class="button secondary cancel">Cancel</button>
                                        <?php } else { ?>
                                        <button class="btnproceed button success">Proceed</button>
                                        <button class="button secondary save">Save</button>
                                        <button class="button secondary cancel">Cancel</button>
                                        <?php } ?>
			                            <?php 
                                        $post_id = get_the_ID();
                                        $tactic_owner = get_post_meta($post_id, 'tactic_owner', true);
                                        $current_user = wp_get_current_user();
                                        $order_author_id = $current_user->ID; ?>
                                        <input type="hidden" name="_vendor" id="_vendor" value="<?php echo $tactic_owner; ?>"/>
                                        <input type="hidden" name="_user" id="_user" value="<?php echo $order_author_id;?>" />
			                            <input type="hidden" name="storecount" id="storecount" />
			                            <input type="hidden" name="total" id="total" />
			                            <input type="hidden" name="costperstore" id="costperstore" />
			                            <input type="hidden" name="type" id="type" value="On-Pack" />
			                            <input type="hidden" name="ordertype" id="ordertype" value="onpack" />
                                        <input type="hidden" name="revision" id="revision" value="false" />
                                        <input type="hidden" name="serviceURL" id="serviceURL" value="<?php echo get_permalink();?>" />
			                            <?php $ajax_nonce = wp_create_nonce( "order-nonce" ); ?>
			                            <input type="hidden" id="order-nonce" value="<?php echo $ajax_nonce; ?>"> 
			                            <input type="hidden" name="pfid" id="pfid" value="" />
				                    </form>
				                </div>

				            </div>
				        </div>
				    </div>
				</div>
			</article>
			<?php } //end while ?>
		</main>
	</div>
</div>
<?php
get_footer();
