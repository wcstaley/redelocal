<?php
/* Template Name: Sampling */

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
				                            <h4>1. Campaign Name</h4>
				                            <input type="text" class="form-control" id="ordername" name="ordername">
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
                                        </div>
                                        <div class="padding-bottom-30">
                                            <h4>3. Campaign Objective</h4>
                                            <div class="example">
                                                <select data-plugin="selectpicker" id="campaignobjective" name="campaignobjective">
                                                    <option value="Drive Purchase – Losing share">Drive Purchase – Losing share </option>
                                                    <option value="Drive Trial & Awareness – Need new buyers">Drive Trial & Awareness – Need new buyers</option>
                                                    <option value="Drive Retailer Equity – Retailer programming or Retailer themed event participation">Drive Retailer Equity – Retailer programming or Retailer themed event participation</option>
                                                </select>
                                            </div>                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>4. Product Type</h4>
                                            <div class="example">
                                                <select data-plugin="selectpicker" id="producttype" name="producttype">
                                                    <option value="Current Product in Marketplace">Current Product in Marketplace</option>
                                                    <option value="New Product">New Product</option>
                                                    <option value="Line Extension (Existing Product but New Line)">Line Extension (Existing Product but New Line)</option>
                                                    <option value="New Size">New Size</option>
                                                 </select>
                                            </div>
                                        </div>


                                        <div class="padding-bottom-30">
                                            <h4>5. Category Type</h4>
                                            <div class="example">
                                                <select data-plugin="selectpicker" id="categorytype" name="categorytype">
                                                    <option value="Adult Beverage">Adult Beverage</option>
                                                    <option value="Food or Beverage ">Food or Beverage </option>
                                                    <option value="Consumer Packaged Goods">Consumer Packaged Goods</option>
                                                 </select>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>6. Preferred Event Date</h4>
                                            <p>FIRST preference date</p>
                                            <div class="input-group">
                                                <span class="input-group-label">
                                                    <i class="fi-calendar medium" aria-hidden="true"></i>
                                                </span>
                                                <input type="text" class="form-control datepicker" data-plugin="datepicker" id="marketdate" name="marketdate">
                                            </div>
                                            <p>SECOND preference date</p>
                                            <div class="input-group">
                                                <span class="input-group-label">
                                                    <i class="fi-calendar medium" aria-hidden="true"></i>
                                                </span>
                                                <input type="text" class="form-control datepicker" data-plugin="datepicker" id="marketdate2" name="marketdate2">
                                            </div>
                                            <span class="help-block">The date you wish for the campaign to start - the earliest a campaign can start is 8 weeks out.</span>

                                        </div>


                                        <div class="padding-bottom-30">
                                            <h4>7. Event Hours</h4>
                                            <span class="help-block">Event hours will be automatically populated based on your preferred date.</span>
                                            <div class="input-group">
                                                <select disabled data-plugin="selectpicker" id="timestart" name="timestart">
                                                    <option value="10:30 AM">10:30 AM</option>
                                                    <option value="12:30 PM">12:30 PM</option>
                                                </select>
                                                <span class="input-group-label">
                                                    to
                                                </span>
                                                <select disabled data-plugin="selectpicker" id="timeend" name="timeend">
                                                    <option value="4:30 PM">4:30 PM</option>
                                                    <option value="6:30 PM">6:30 PM</option>
                                                </select>
                                             </div>
                                         </div>

                                         <div class="padding-bottom-30">
                                            <h4>8. Retailers</h4>
                                            <span class="help-block">Select the ideal retailer target you want to reach for your activation or upload a custom store list.</span>
                                            <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#retTabsOne" aria-selected="true">Retailers</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#regTabsTwo" aria-controls="regTabsTwo" role="tab">Upload your own</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="retTabsOne" role="tabpanel">
                                                        <?php
                                                        $stores = get_sampling_stores();

                                                        $i = 0;
                                                        foreach ($stores as $store){ ?>
                                                        <div class="checkbox-custom checkbox-primary">
                                                            <input type="checkbox" name="store" value="<?php echo $store['name']; ?>" data-count="<?php echo $store['count']; ?>"  data-rate="<?php echo $store['rate']; ?>" data-time="<?php echo $store['time']; ?>" />
                                                            <label class="font-size-16 font-weight-500"><?php echo $store['name']; ?></label>
                                                        </div>
                                                        <?php } ?>

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
                                            <h4>9. Total Number # of Stores </h4>
                                            <input disabled type="text" class="form-control" id="customstorecountdisplay" name="customstorecountdisplay">
                                        </div>

                                        <hr>
                                        <p>The below is not required. Fill the rest of this form out to the best of your ability and submit.</p>
                                        
                                        <div class="padding-bottom-30">
                                            <h4>10. Product Overview</h4>
                                            <h4>Product Name</h4>
                                            <input type="text" class="form-control" id="productname" name="productname">
                                            <h4>Description</h4>
                                            <textarea id="productdesc" name="productdesc" rows="10" cols="50"></textarea>
                                            <h4>Flavor/Unit Size/Pack Size</h4>
                                            <input type="text" class="form-control" id="productunit" name="productunit">
                                            <h4>Consumer UPC (Supply all 13 digits)</h4>
                                            <input type="text" class="form-control" id="productupc" name="productupc">
                                            <h4>Sampled (Describe product(s) being sampled during event)</h4>
                                            <input type="text" class="form-control" id="productsampled" name="productsampled">
                                            <h4>Featured (Describe product(s) to be featured at cart) (QUALIFY WHAT A CART MEANS)</h4>
                                            <input type="text" class="form-control" id="productfeatured" name="productfeatured">
                                            <h4>Back-up (If product is not available, what product would you like to be sampled? And featured?)</h4>
                                            <input type="text" class="form-control" id="productbackup" name="productbackup">
                                            <h4>Product Distribution Method (Method the manufacturer uses to get product to the store) – Select 1</h4>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productdistribution" value="DSD (Direct Store Delivery)" />
                                                <label class="font-size-16 font-weight-500">DSD (Direct Store Delivery)</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productdistribution" value="Warehouse" />
                                                <label class="font-size-16 font-weight-500">Warehouse</label>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>11. Event Specialist Training Guidelines</h4>

                                            <h4>Selling Points</h4>
                                            <textarea id="sellingpoints" name="sellingpoints" rows="10" cols="50"></textarea>
                                            <span class="help-block">(Provide 3-5 points that highlight the features or benefits of your product. What do you want shoppers to know about the product?)</span>

                                            <h4>Suggested Preparation Instructions</h4>
                                            <textarea id="preparation" name="preparation" rows="10" cols="50"></textarea>
                                            <span class="help-block">(Provide a preferred preparation/demonstration instructions or recipes. All requests will be reviewed for optimal preparation/demonstration in-store.)</span>

                                            <h4>Equipment needed</h4>
                                            <textarea id="equipment" name="equipment" rows="10" cols="50"></textarea>
                                            <span class="help-block">(i.e. microwave, skillet, blender, coffee maker, etc.)</span>

                                            <h4>Total Sample Distribution Goal per Store Event</h4>
                                            <textarea id="distributiongoal" name="distributiongoal" rows="10" cols="50"></textarea>

                                        </div>



                                        <div class="padding-bottom-30">
                                            <h4>12. Event Day Kit Contents </h4>

                                           <h4> Will you be providing a coupon? [select one] </h4>
                                            <div class="radio-custom radio-primary">
                                                <input type="radio" name="productcoupon" value="Yes" />
                                                <label class="font-size-16 font-weight-500">Yes</label>
                                            </div>
                                            <div class="radio-custom radio-primary">
                                                <input type="radio" name="productcoupon" value="No" />
                                                <label class="font-size-16 font-weight-500">No</label>
                                            </div>
                                            <div class="radio-custom radio-primary">
                                                <input type="radio" name="productcoupon" value="Don’t have one, but would like one" />
                                                <label class="font-size-16 font-weight-500">Don’t have one, but would like one</label>
                                            </div>
                                        
<!--                                             If no skip section
                                            If yes
                                            Offer (i.e. $1 off 1):
                                            Value:
                                            Version: Manufacturer or Store
                                            Expiration Date:
                                            Qty/Kit (Average of 250 coupons per event kit):
                                            {POP-UP}  Coupons must arrive to the fulfillment center 4 weeks prior to the sampling event execution weekend. Detailed shipping instructions will be provided once event order is processed. Should coupons impact standard shipping costs the incremental charge will be communicated to client for approval. 

                                            If don’t have, but would like one (NOT SURE WHAT YOU ARE SAYING HERE- ARE WE SAYING WE WILL PRODUCE FOR CLIENT)
                                            Offer (i.e. $1 off 1):
                                            Value:
                                            Version: Manufacturer or Store
                                            Expiration Date:
                                            Qty/Kit (Average of 250 coupons per event kit):
                                            {POP-UP} Custom quote will be provided after event order completion
                                              -->

                                            <h4>Do you need or will you require basic/generic supplies to execute your event?</h4>
                                            <span class="help-block">(qty per store day) *Some retailers provide generic supplies</span>
<!--                                             Select One
                                            [check box] Basic/Generic supplies are: 
                                            (items are listed below) -->
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Souffle Cups – 200/kit" />
                                                <label class="font-size-16 font-weight-500"> Souffle Cups – 200/kit </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Gloves – 10/kit" />
                                                <label class="font-size-16 font-weight-500">Gloves – 10/kit</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Break Sign – 1/kit" />
                                                <label class="font-size-16 font-weight-500">Break Sign – 1/kit</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Napkins – 1/kit" />
                                                <label class="font-size-16 font-weight-500">Napkins – 1/kit </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Mini Spoons – 200/kit" />
                                                <label class="font-size-16 font-weight-500">Mini Spoons – 200/kit </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Allergen Signs – 1/kit" />
                                                <label class="font-size-16 font-weight-500">Allergen Signs – 1/kit </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productsupplies" value="Brand will supply basic/generic supplies" />
                                                <label class="font-size-16 font-weight-500">Brand will supply basic/generic supplies </label>
                                            </div>
                                             <span class="help-block">Supplies must arrive to the fulfillment center 4 weeks prior to the sampling event execution weekend. Detailed shipping instructions will be provided once event order is processed. Should supplies impact standard shipping costs the incremental charge will be communicated to client for approval.</span>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>13. Custom Branded Signage</h4>
                                            <span class="help-block">Some retailers include custom branded signage within their events. Generic sign will be provided if you do NOT upload creative.</span>
                                            <h4>If opting in please upload:</h4>
                                            
                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                <h4>Beauty Image</h4>
                                                <input type="file" id="fileuploadproductbeauty" class="hide">
                                                <a class="button success" id="uploadproductbeauty"><i class="fi-upload small"></i> Upload File</a>
                                                <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                <div class="progress margin-vertical-20">
                                                    <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                    </div>
                                                </div>
                                                <div class="fileinfo"></div>
                                                <input type="hidden" id="productbeautyguid" name="productbeautyguid">
                                                <input type="hidden" id="productbeautyname" name="productbeautyname">
                                            </div>

                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                <h4>Product Shot</h4>
                                                <input type="file" id="fileuploadproductshot" class="hide">
                                                <a class="button success" id="uploadproductshot"><i class="fi-upload small"></i> Upload File</a>
                                                <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                <div class="progress margin-vertical-20">
                                                    <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                    </div>
                                                </div>
                                                <div class="fileinfo"></div>
                                                <input type="hidden" id="productshotguid" name="productshotguid">
                                                <input type="hidden" id="productshotname" name="productshotname">
                                            </div>

                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                <h4>Logo</h4>
                                                <input type="file" id="fileuploadproductlogo" class="hide">
                                                <a class="button success" id="uploadproductlogo"><i class="fi-upload small"></i> Upload File</a>
                                                <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                <div class="progress margin-vertical-20">
                                                    <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                    </div>
                                                </div>
                                                <div class="fileinfo"></div>
                                                <input type="hidden" id="productlogoguid" name="productlogoguid">
                                                <input type="hidden" id="productlogoname" name="productlogoname">
                                            </div>

                                            <span class="help-block">High Resolution Files Only – Must be 250-350dpi</span>

                                            <h4>Event/Communication Objective</h4>
                                            
                                            <h4>Call to Action Copy</h4>
                                            <textarea id="productcta" name="productcta" rows="10" cols="50"></textarea>

                                            <h4>Headline</h4>
                                            <input type="text" class="form-control" id="productheadline" name="productheadline">
                                            <span class="help-block">3-6 words max, or approx.. 32 characters</span>
                                            
                                            <h4>Subhead</h4>
                                            <input type="text" class="form-control" id="productsubhead" name="productsubhead">
                                            <span class="help-block">(OPTIONAL) 6-8 words max, or approx.. 48 characters</span>

                                            <h4>Legal Lines</h4>
                                            <textarea id="productlegal" name="productlegal" rows="10" cols="50"></textarea>
                                            <span class="help-block">(identify trademark, claim or headline restrictions)</span>
                                            
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>14. Branded Collateral and Fulfillment </h4>
                                            <span class="help-block">Did you know that events with collateral see an average increase of 285% in sales compared to events without collateral?</span>
                                            <!-- Already have collateral? Click here -->
                                            
                                            <h4>Red/E to build Collateral</h4>
                                            <input class="switch-input" type="checkbox" id="redecollateral" name="redecollateral" value="1">
                                            <label class="switch-paddle" for="redecollateral">
                                                <span class="show-for-sr">Red/E to build Collateral</span>
                                                <span class="switch-active" aria-hidden="true">Yes</span>
                                                <span class="switch-inactive" aria-hidden="true">No</span>
                                            </label>

                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productcollateral" value="Easel Back/Countertop Sign" />
                                                <label class="font-size-16 font-weight-500">Easel Back/Countertop Sign</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productcollateral" value="2-Sided Handout " />
                                                <label class="font-size-16 font-weight-500">2-Sided Handout </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productcollateral" value="Recipe Card" />
                                                <label class="font-size-16 font-weight-500">Recipe Card</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productcollateral" value="Trial Size Sample" />
                                                <label class="font-size-16 font-weight-500">Trial Size Sample</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productcollateral" value="Accordian Fold Brochure" />
                                                <label class="font-size-16 font-weight-500">Accordian Fold Brochure</label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="productcollateral" value="Other" />
                                                <label class="font-size-16 font-weight-500">Other</label>
                                            </div>

                                            <span class="help-block">Custom quote will be provided after event order completion</span>
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>15. Budget</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="budget" name="budget">
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>16. Submit for Approval</h4>
                                            <!-- By clicking “Submit for Approval,” our team will review your campaign details and respond within 48 hours to confirm launch.  -->

                                            <!-- Order Summary (WOULD ADD THE DATES OF EXECUTION)
                                            Total Stores
                                            Total Timing
                                            Total In-Store Event Execution Costs
                                            (List all things that are not included in current estimate and require follow-up of custom rates)
                                            From checkout to reporting, Red/E Order costs are all inclusive.


                                            ADD “pop-up” or gray subtext with descriptions for each title / header, statements noted as follows:

                                            Campaign Name – Enter the name of your activation.
                                            Brand Name – Enter the brand or business name that will be featured as part of your activation. 
                                            Campaign Objective – Select the measurable objective that aligns closest with your marketing intent.
                                            Campaign Purpose – Select the measurable objective that aligns closest with your marketing intent.
                                            Campaign Timing – Identify the start / end dates for your activation. Timing must meet 8 weeks out.
                                            Upload Creative – Attach creative to be leveraged as part of your activation.
                                            Campaign Estimate – An initial working budget / estimate based on store selection and timing.


                                            Additional Product Information (will be included as part of the email follow-up once order is received) 
                                            Kroger Case UPCs:
                                            Kroger Category Manager:
                                            Ahold Buyer Name:
                                            Ahold Warehouse Item Number:
                                            Publix Item Codes:
                                            Publix Category Manager Name:
                                            Meijer PID/Item Code:
                                            Walmart Item Code:
                                            Walmart Buyer Name:
                                            Albertson/Safeway CIC/Item Code:
                                            SaveMart/Lucky Item Code:
                                            ShopRite/Wakefern Item Code:
                                            Weis Item Code:
                                            SuperValu (Cub, Farm Fresh, Hornbachers, Shoppers, Shop & Save) Item Code: 
                                            HyVee Item Code:

                                            Additional Fulfillment and Collateral Information Needed if they are Supplying their own (will be included as part of the email follow-up once order is received) 
                                            Description 
                                            Dimensions (inches)
                                            Weight (lbs.)
                                            Bulk/Bundled/Pre-Packages (select one)
                                            Distribution Goal Per Store
                                            Provided By:
                                            If a pre package kit provide the following;
                                            Individual Kit Weight
                                            Individual Kit Dimensions
                                            Total number kits shipped -->
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
			                            <input type="hidden" name="type" id="type" value="Sampling" />
			                            <input type="hidden" name="ordertype" id="ordertype" value="sampling" />
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
