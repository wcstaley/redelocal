<?php
/* Template Name: PoS Materials */

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
				                            <span class="help-block">Enter a name for the new Point-of-Sale Materials program you wish to create.</span>
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
				                        <div class="padding-bottom-30">
				                            <h4>3. Select a Tactic</h4>
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
				                                    <option value="Necker">Necker</option>
				                                    <option value="IRC">IRC</option>
                                                    <option value="Tear Pad">Tear Pad</option>
<!-- 				                                    <option value="Shelf Talker">Shelf Talker</option> -->
				                                    <!-- <option value="Welchs Natural Necker">Welchs Natural Necker</option> -->
				                                </select>
				                            </div>
				                        </div>
				                        <div class="padding-bottom-30">
				                            <h4>4. Select Your Creative</h4>
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
                                            <h4>5. Destination</h4>
                                            <select data-plugin="selectpicker" id="destination" name="destination">
                                                <option value="Email">Email hi-resolution file via email link</option>                                           <span class="help-block">How many products per store will receive this Point-of-Sale Materials vehicle?</span>
                                                <option value="Shipping">Ship printed material to location</option>
                                            </select>
                                        </div>

                                        <div class="padding-bottom-30 hider-email">
                                            <h4>6. Email hi-resolution file</h4>
                                            <input type="text" class="form-control" id="dest-email" name="dest-email">
                                            <span class="help-block">Enter email address</span>
                                        </div>

                                        <div class="padding-bottom-30 hider-shipping">
                                            <h4>6. Printing</h4>
                                            <input type="number" class="form-control" id="dest-quantity" name="dest-quantity">
                                            <span class="help-block">Enter total quantity</span>
                                            <textarea class="form-control" id="specialinstructions" name="specialinstructions"></textarea>
                                            <span class="help-block">Special instructions (example: Bundle in groups of 10)</span>
                                        </div>

                                        <div class="padding-bottom-30 hider-shipping">
                                            <h4>7. Shipping </h4>
                                            <select multiple class="form-control" id="shippingaddress" name="shippingaddress">
                                                <?php
                                                $shippingaddresss = get_user_shippingaddress();
                                                foreach ($shippingaddresss as $shippingaddress) { 
                                                ?>
                                                <option value="<?php echo $shippingaddress; ?>"><?php echo $shippingaddress; ?></option>
                                                <?php } ?>
                                            </select>
                                             <span class="help-block">Hold down the Ctrl (windows) / Command (Mac) button to select multiple addresses.</span>
                                            <div class="row">
                                                <div class="columns small-10 large-10"><textarea class="form-control" id="addshippingaddress" name="addshippingaddress"></textarea></div>
                                                <div class="columns small-2 large-2"><button class="btnaddaddress button success">Add</button></div>
                                            </div>
                                            <span class="help-block">Add shipping address</span>

                                            <textarea class="form-control" id="shippinginstructions" name="shippinginstructions"></textarea>
                                            <span class="help-block">Special instructions (example: idenfity quanties for each location)</span>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>8. Market Date (Week Of)</h4>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                </span>
                                                <input type="text" class="form-control datepicker" data-plugin="datepicker" id="marketdate" name="marketdate">
                                            </div>
                                            <span class="help-block">The date you wish for the campaign to start - the earliest a campaign can start is 2 weeks out.</span>
                                        </div>

				                       

			                            <div class="padding-bottom-30">
			                                <h4>9. Confirm and Proceed to Checkout</h4>
			                                <div class="row">
			                                    <div class="col-md-9">
			                                        <table class="table table-striped border">
                                                         <tr>
                                                            <td>Total Print Quantity
                                                            </td>
                                                            <td class="summary-print-quantity">0
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Shipping Cost
                                                            </td>
                                                            <td class="summary-total-shipping">0
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Design Cost
                                                            </td>
                                                            <td class="summary-design-cost">$0
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
													<li>Red/E Order costs are all inclusive.</li>
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
			                            <input type="hidden" name="type" id="type" value="Point-of-Sale Materials" />
			                            <input type="hidden" name="ordertype" id="ordertype" value="posmaterials" />
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
