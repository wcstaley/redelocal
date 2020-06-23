<?php
/* Template Name: SAS At-Shelf */

get_header(); ?>

<div class="main-container">
	<div class="main-grid">
		<main class="main-content-full-width">
			<?php
			while ( have_posts() ){
                the_post();
                $post_id = get_the_ID();
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
				                            <span class="help-block">Enter a name for the new SAS At-Shelf program you wish to create.</span>
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
                                            <h4>4. Select a Retailer</h4>
                                            <span class="help-block">Select the retailer you want to reach for your activation.</span>

                                            <div class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" id="exampleAccordionDefault">
                                                <div class="accordion-item" data-accordion-item>
                                                    
                                                    <a class="panel-title collapsed accordion-title" href="#exampleCollapseDefaultOne" data-parent="#exampleAccordionDefault">
                                                        <h4 class="padding-0 margin-0">Retailer</h4>
                                                    </a>

                                                    <div class="accordion-content" data-tab-content id="exampleCollapseDefaultOne">
                                                        <div class="panel-body">
                                                            <?php
                                                            $stores = get_sas_at_shelf_stores();
                                                            $i = 0;
                                                            foreach ($stores as $store){
                                                                $i++; 
                                                                $u = 0;

                                                                // print_r($store);
                                                                // die();
                                                                $parentNum = explode('-', $store['val']);
                                                                $parentNum = $parentNum[0];
                                                                ?>
                                                                <div class="checkbox-custom checkbox-primary">
                                                                    <input type="checkbox" name="store" value="<?php echo $store['val']; ?>" data-num="<?php echo $store['num']; ?>" class="parent-store-check p<?php echo $parentNum; ?>">
                                                                    <label for="i1" class="font-size-16 font-weight-300"><?php echo $store['name']; ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

				                        <div class="padding-bottom-30">
				                            <h4>5. Select a Tactic</h4>
                                            <span class="help-block">We are able to accommodate custom sizes and die-cut requests. Please provide custom sizes or die cut details in the additional notes section below (Item 13) and we will provide price quote upon receipt of the order.</span>
				                            <div class="example">

				                                <select data-plugin="selectpicker" id="sasatshelftactic" name="sasatshelftactic">
                                                    <?php
                                                    $tactic_pricing = get_post_meta($post_id, 'tactic_list', true);
                                                    foreach($tactic_pricing as $tactic){
                                                        echo '<option value="'.$tactic.'">'.$tactic.'</option>';
                                                    }
                                                    ?>
                                                    <!-- <option value="Shelf Blade 4” x 6”">Shelf Blade 4” x 6”</option>
                                                    <option value="Shelf Blade 4” x 12”">Shelf Blade 4” x 12”</option>
                                                    <option value="Dangler 3” x 5”">Dangler 3” x 5”</option>
                                                    <option value="Dangler 3” x 5” with on-pack">Dangler 3” x 5” with on-pack</option>
                                                    <option value="Shelf Blade 4” x 6” with on-pack">Shelf Blade 4” x 6” with on-pack</option>
                                                    <option value="Shelf Blade 4” x 12” with on-pack">Shelf Blade 4” x 12” with on-pack</option> -->
				                                </select>
				                            </div>
				                        </div>

                                         <div class="padding-bottom-30">
				                            <h4>6. Per Store Quantity</h4>
				                            <input type="number" class="form-control" id="sasatshelfquantity" name="sasatshelfquantity" value="1">
				                            <span class="help-block">How many at-shelf items per store?</span>
				                        </div>

                                        <div class="padding-bottom-30 form-combo">
				                            <h4>6a. Select an on-pack tactic</h4>
				                            <select data-plugin="selectpicker" id="tactic" name="tactic">
                                                <option value="Hang Tag">Hang Tag</option>
                                                <option value="Necker">Necker</option>
                                                <option value="Sticker">Sticker</option>
                                            </select>
				                        </div>

                                        <div class="padding-bottom-30 form-combo">
				                            <h4>6b. Per Store Quantity</h4>
				                            <input type="number" class="form-control" id="quantity" name="quantity" value="10">
				                            <span class="help-block">How many products per store will receive this on-pack vehicle?</span>
				                        </div>

                                         <div class="padding-bottom-30 form-combo">
				                            <h4>6c. What Products Will Receive the Tactic?</h4>
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
				                            <span class="help-block">Upload your product list - SKUs, UPCs, store item #, and product names and descriptions.</span>
				                        </div>
                                        

				                        <div class="padding-bottom-30 form-combo">
				                            <h4>6d. Select Your Creative</h4>
                                            <span class="help-block margin-bottom-20">Upload your own, or have Red/E develop your creative.</span>
                                            <?php rede_field_creative_tabs(); ?>
				                        </div>

				                        <div class="padding-bottom-30">
				                            <h4>7. Select Your At-Shelf Creative</h4>
				                            <span class="help-block">Upload your own, or have Red/E develop your creative.</span>
                                            <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-b-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#exampleBTabsTwo" aria-controls="exampleBTabsTwo" role="tab">Upload your own</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#exampleBTabsThree" aria-controls="exampleBTabsThree" role="tab">Have Red/E develop</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-b-tabs">
                                                    <div class="tabs-panel is-active" id="exampleBTabsTwo" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Upload Your Own Creative</h4>
                                                            <input type="file" id="fileuploadsasatshelf" class="hide" />
                                                            <?php $ajax_nonce = wp_create_nonce( "media-sas-nonce" ); ?>
                                                            <input type="hidden" id="media-nonce" value="<?php echo $ajax_nonce; ?>"> 
                                                            <a class="button success" id="uploadsasatshelf"><span class="icon wb-upload margin-right-5" aria-hidden="true"></span>Upload File</a>
                                                            <a class="button secondary hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            <div class="progress margin-vertical-20">
                                                                <div class="progress-bar" role="progressbar" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="fileinfo"></div>
                                                            <input type="hidden" id="fileguidsasatshelf" name="fileguidsasatshelf" />
                                                            <input type="hidden" id="filenamesasatshelf" name="filenamesasatshelf" />
                                                            <a href="<?php echo home_url('creative-specs'); ?>" target="_blank">Creative Specs</a>
                                                        </div>
                                                    </div>
                                                    <div class="tabs-panel" id="exampleBTabsThree" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Or, Click Below to Get Your Creative Developed by Red/E</h4>
                                                            <div class="checkbox-custom checkbox-primary">
                                                                <input type="checkbox" id="at-shelf-tactic-custom" name="at-shelf-tactic-custom" value="1">
                                                                <label for="at-shelf-tactic-custom" class="font-size-16 font-weight-400">Red/E develop creative</label>
                                                            </div>
                                                            <span class="help-block">The campaign start date is subject to change upon receiving, designing and approving creative elements in a timely manner. RED/E will work with you to accept all existing or newly furnished assets as quickly as possible to meet the requested program start date.</span>
                                                            <div class="reveal" id="redeatshelfcreativemodal" data-reveal>
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
				                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>8. Select Store Department</h4>
                                            <span class="help-block">Check All That Apply (Note: Selecting multiple departments increases merchandising costs.)</span>
            
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="1 Candy & Tobacco" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">1 Candy & Tobacco</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="2 Health & Beauty Aids" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">2 Health & Beauty Aids</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="3 Stationery" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">3 Stationery</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="4 Household Paper" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">4 Household Paper</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="5 Media & Gaming" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">5 Media & Gaming</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="6 Cameras & Supplies" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">6 Cameras & Supplies</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="7 Toys" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">7 Toys</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="8 Pets & Supplies" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">8 Pets & Supplies</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="9 Sporting Goods" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">9 Sporting Goods</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="10 Automotive" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">10 Automotive</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="11 Hardware" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">11 Hardware</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="12 Paint & Accessories" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">12 Paint & Accessories</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="13 Household Chemicals" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">13 Household Chemicals</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="14 Cook & Dine" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">14 Cook & Dine</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="16 Lawn & Garden" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">16 Lawn & Garden</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="17 Home Decor" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">17 Home Decor</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="18 Seasonal" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">18 Seasonal</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="19 Piece Goods & Crafts" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">19 Piece Goods & Crafts</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="20 Bath & Shower" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">20 Bath & Shower</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="21 Books & Magazines" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">21 Books & Magazines</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="22 Bedding" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">22 Bedding</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="28 Hosiery" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">28 Hosiery</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="29 Sleepwear" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">29 Sleepwear</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="38 Pharmacy Rx" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">38 Pharmacy Rx</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="40 OTC Pharmacy" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">40 OTC Pharmacy</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="46 Cosmetics & Skincare" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">46 Cosmetics & Skincare</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="72 Electronics" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">72 Electronics</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="79 Infant Consumables Hardlines" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">79 Infant Consumables Hardlines</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="81 Commercial Bread" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">81 Commercial Bread</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="82 Impulse Merchandise" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">82 Impulse Merchandise</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="87 Wireless" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">87 Wireless</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="90 Dairy" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">90 Dairy</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="91 Frozen" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">91 Frozen</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="92 Grocery" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">92 Grocery</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="93 Meat" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">93 Meat</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="94 Produce" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">94 Produce</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="95 DSD Grocery" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">95 DSD Grocery</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="96 Liquor" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">96 Liquor</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="97 Wall Deli" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">97 Wall Deli</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="98 Bakery" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">98 Bakery</label>
                                            </div>                
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="storedepartment" value="99 Office & Store" class="parent-store-check" />
                                                <label class="font-size-16 font-weight-500">99 Office & Store</label>
                                            </div>     
                                        </div>     

                                        <div class="padding-bottom-30">
				                            <h4>9. Identify how many aisles your at-shelf item will be placed in</h4>
				                            <input type="number" class="form-control" id="aislequantity" name="aislequantity" value="1">
                                            <div class="reveal" id="aislequantitymodal" data-reveal>
                                                <h4>Too Many Aisles</h4>
                                                <p>Your quantity is larger than the number of aisles. Please adjust the per store quantity in step 5 first.</p>
                                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
				                            <h4>10. Tell us where you would like your at-shelf POS placed</h4>
                                            <textarea class="form-control" id="aisleplacement" name="aisleplacement"></textarea>
                                            <span class="help-block">Example: In front of products</span>
                                        </div>

                                        <div class="padding-bottom-30">
				                            <h4>11. Cycle Dates</h4>
                                            <select data-plugin="selectpicker" id="marketdate" name="marketdate">
                                                <optgroup label="2018">
                                                    <option value="Cycle 12 (Dec 23 2018 - Jan 26 2019)">Cycle 12 (Dec 23 - Jan 26)</option>
                                                </optgroup>
                                                <optgroup label="2019">
                                                    <option value="Cycle 1 (Jan 27 2019 - Feb 23 2019)">Cycle 1 (Jan 27 - Feb 23)</option>
                                                    <option value="Cycle 2 (Feb 24 2019 - Mar 30 2019)">Cycle 2 (Feb 24 - Mar 30)</option>
                                                    <option value="Cycle 3 (Mar 31 2019 - Apr 27 2019)">Cycle 3 (Mar 31 - Apr 27)</option>
                                                    <option value="Cycle 4 (Apr 28 2019 - May 25 2019)">Cycle 4 (Apr 28 - May 25)</option>
                                                    <option value="Cycle 5 (May 26 2019 - Jun 29 2019)">Cycle 5 (May 26 - Jun 29)</option>
                                                    <option value="Cycle 6 (Jun 30 2019 - Aug 3 2019)">Cycle 6 (Jun 30 - Aug 3)</option>
                                                    <option value="Cycle 7 (Aug 4 2019 - Aug 31 2019)">Cycle 7 (Aug 4 - Aug 31)</option>
                                                    <option value="Cycle 8 (Sep 1 2019 - Sep 28 2019)">Cycle 8 (Sep 1 - Sep 28)</option>
                                                    <option value="Cycle 9 (Sep 29 2019 - Oct 26 2019)">Cycle 9 (Sep 29 - Oct 26)</option>
                                                    <option value="Cycle 10 (Oct 27 2019 - Nov 23 2019)">Cycle 10 (Oct 27 - Nov 23)</option>
                                                    <option value="Cycle 11 (Nov 24 2019 - Dec 28 2019)">Cycle 11 (Nov 24 - Dec 28)</option>
                                                    <option value="Cycle 12 (Dec 29 2019 - Feb 1 2020)">Cycle 12 (Dec 29 - Feb 1)</option>
                                                </optgroup>
                                            </select>
				                            <span class="help-block">(If looking for out-of-cycle dates, please proceed to item 12)</span>
				                        </div>

                                        <div class="padding-bottom-30">
				                            <h4>12. Market Date (Out of cycle option)</h4>
                                            <div class="date-select">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="text" class="form-control datepicker" data-plugin="datepicker" id="marketdate_out_cycle" name="marketdate_out_cycle">
                                                </div>
                                            </div>
				                            <span class="help-block">The date you wish for the campaign to start - the earliest a campaign can start is 4 weeks out.</span>
				                        </div>

                                        <div class="padding-bottom-30">
				                            <h4>13. Additional Instructions</h4>
                                            <textarea class="form-control" id="specialinstructions" name="specialinstructions"></textarea>
                                            <span class="help-block">Area to provide special instructions or details (i.e. die cut; custom size; special printing, etc.)</span>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>14. Select Stores</h4>
                                            <span class="help-block">You can upload your list now or upon final approval of program.</span>
                                            <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#retTabsOne" aria-selected="true">Store Count</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#regTabsTwo" aria-controls="regTabsTwo" role="tab">Upload your own</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="retTabsOne" role="tabpanel">
                                                    <h4>How Many Stores</h4>
                                                    <input type="text" name="storecount" id="storecount" />
                                                    <span class="help-block">The store count has been adjusted to fit your budget. If you would like to reach additional stores, please return to item 3 and increase your budget.</span>
                                                    </div>
                                                    <div class="tabs-panel" id="regTabsTwo" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Upload your own custom list</h4>
                                                            <input type="file" id="fileuploadlist" class="hide">                                                            <a class="button success" id="uploadlist"><i class="fi-upload small"></i> Upload File</a>
                                                            <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            
                                                            <div class="progress margin-vertical-20">
                                                                <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="fileinfo"></div>
                                                            <h4>Store Count</h4>
															<input type="text" class="form-control" id="customstorecount" name="customstorecount">
                                                            <span class="help-block">Enter the number of stores you would like to reach.</span>
                                                            <input type="hidden" id="customlistguid" name="customlistguid">
                                                            <input type="hidden" id="customlistname" name="customlistname">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

			                            <div class="padding-bottom-30">
			                                <h4>15. Confirm and Proceed to Checkout</h4>
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
			                                                <td>Design Cost
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
													<li>Confirm In-Market Date & Order Total Cost, Proceed to Design & Final Approvals.</li>
													</ul> </span>
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
                                        $tactic_owner = get_post_meta($post_id, 'tactic_owner', true);
                                        $current_user = wp_get_current_user();
                                        $order_author_id = $current_user->ID; ?>
                                        <input type="hidden" name="_vendor" id="_vendor" value="<?php echo $tactic_owner; ?>"/>
                                        <input type="hidden" name="_user" id="_user" value="<?php echo $order_author_id;?>" />
			                            <input type="hidden" name="total" id="total" />
			                            <input type="hidden" name="costperstore" id="costperstore" />
			                            <input type="hidden" name="type" id="type" value="SAS At-Shelf" />
			                            <input type="hidden" name="ordertype" id="ordertype" value="sas-at-shelf" />
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
