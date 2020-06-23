<?php
/* Template Name: Shroud */

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
                                        <div class="padding-bottom-30 input-container">
                                            <h4>1. Program Name</h4>
                                            <input type="text" class="form-control" id="ordername" name="ordername">
                                            <span class="help-block">Enter a name for the new Security Shroud program you wish to create.</span>
                                        </div>
                                        <div class="padding-bottom-30 input-container">
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
                                            <span class="help-block">Select the brand you wish to create a new order for.</span>
                                        </div>
                                        <div class="padding-bottom-30 input-container">
                                            <h4>3. Program Objective</h4>
                                            <div class="example">
                                                <select data-plugin="selectpicker" id="campaignobjective" name="campaignobjective">
                                                    <option value="">New Product / Trial</option>
                                                    <option value="Brand Building / Awareness Driving">Brand Building / Awareness Driving</option>
                                                    <option value="Competitive Blocking / Defense">Competitive Blocking / Defense</option>
                                                    <option value="Distribution / Retailer Support">Distribution / Retailer Support</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="padding-bottom-30 input-container">
                                            <h4>4. Program Timing</h4>
                                            <div class="date-select">
                                                <select data-plugin="selectpicker" id="marketdate" name="marketdate">
                                                    <optgroup label="2018">
                                                        <option value="Cycle 1 (Jan 8 2018 - Feb 4 2018)">Cycle 1 (Jan 8 - Feb 4)</option>
                                                        <option value="Cycle 2 (Feb 5 2018 - Mar 4 2018)">Cycle 2 (Feb 5 - Mar 4)</option>
                                                        <option value="Cycle 3 (Mar 5 2018 - Apr 1 2018)">Cycle 3 (Mar 5 - Apr 1)</option>
                                                        <option value="Cycle 4 (Apr 2 2018 - Apr 29 2018)">Cycle 4 (Apr 2 - Apr 29)</option>
                                                        <option value="Cycle 5 (Apr 30 2018 - May 27 2018)">Cycle 5 (Apr 30 - May 27)</option>
                                                        <option value="Cycle 6 (May 28 2018 - Jun 24 2018)">Cycle 6 (May 28 - Jun 24)</option>
                                                        <option value="Cycle 7 (Jun 25 2018 - Jul 22 2018)">Cycle 7 (Jun 25 - Jul 22)</option>
                                                        <option value="Cycle 8 (Jul 23 2018 - Aug 19 2018)">Cycle 8 (Jul 23 - Aug 19)</option>
                                                        <option value="Cycle 9 (Aug 20 2018 - Sep 16 2018)">Cycle 9 (Aug 20 - Sep 16)</option>
                                                        <option value="Cycle 10 (Sep 17 2018 - Oct 14 2018)">Cycle 10 (Sep 17 - Oct 14)</option>
                                                        <option value="Cycle 11 (Oct 15 2018 - Nov 11 2018)">Cycle 11 (Oct 15 - Nov 11)</option>
                                                        <option value="Cycle 12 (Nov 12 2018 - Dec 9 2018)">Cycle 12 (Nov 12 - Dec 9)</option>
                                                        <option value="Cycle 13 (Dec 10 2018 - Jan 6 2018)">Cycle 13 (Dec 10 - Jan 6)</option>

                                                    </optgroup>
                                                    <optgroup label="2019">
                                                        <option value="Cycle 1 (Jan 7 2019 - Feb 3 2019)">Cycle 1 (Jan 7 - Feb 3)</option>
                                                        <option value="Cycle 2 (Feb 4 2019 - Mar 3 2019)">Cycle 2 (Feb 4 - Mar 3)</option>
                                                        <option value="Cycle 3 (Mar 4 2019 - Mar 31 2019)">Cycle 3 (Mar 4 - Mar 31)</option>
                                                        <option value="Cycle 4 (Apr 1 2019 - Apr 28 2019)">Cycle 4 (Apr 1 - Apr 28)</option>
                                                        <option value="Cycle 5 (Apr 29 2019 - May 26 2019)">Cycle 5 (Apr 29 - May 26)</option>
                                                        <option value="Cycle 6 (May 27 2019 - Jun 23 2019)">Cycle 6 (May 27 - Jun 23)</option>
                                                        <option value="Cycle 7 (Jun 24 2019 - Jul 21 2019)">Cycle 7 (Jun 24 - Jul 21)</option>
                                                        <option value="Cycle 8 (Jul 22 2019 - Aug 18 2019)">Cycle 8 (Jul 22 - Aug 18)</option>
                                                        <option value="Cycle 9 (Aug 19 2019 - Sep 15 2019)">Cycle 9 (Aug 19 - Sep 15)</option>
                                                        <option value="Cycle 10 (Sep 16 2019 - Oct 13 2019)">Cycle 10 (Sep 16 - Oct 13)</option>
                                                        <option value="Cycle 11 (Oct 14 2019 - Nov 10 2019)">Cycle 11 (Oct 14 - Nov 10)</option>
                                                        <option value="Cycle 12 (Nov 11 2019 - Dec 8 2019)">Cycle 12 (Nov 11 - Dec 8)</option>
                                                        <option value="Cycle 13 (Dec 9 2019 - Jan 5 2019)">Cycle 13 (Dec 9 - Jan 5)</option>

                                                    </optgroup>
                                                     <optgroup label="2020">
                                                        <option value="Cycle 1 (Jan 6 2020 - Feb 2 2020)">Cycle 1 (Jan 6 - Feb 2)</option>
                                                        <option value="Cycle 2 (Feb 3 2020 - Mar 1 2020)">Cycle 2 (Feb 3 - Mar 1)</option>
                                                        <option value="Cycle 3 (Mar 2 2020 - Mar 29 2020)">Cycle 3 (Mar 2 - Mar 29)</option>
                                                        <option value="Cycle 4 (Mar 30 2020 - Apr 29 2020)">Cycle 4 (Mar 30 - Apr 29)</option>
                                                        <option value="Cycle 5 (Apr 29 2020 - May 26 2020)">Cycle 5 (Apr 29 - May 26)</option>
                                                        <option value="Cycle 7 (Jun 22 2020 - Jul 19 2020)">Cycle 7 (Jun 22 - Jul 19)</option>
                                                        <option value="Cycle 8 (Jul 20 2020 - Aug 16 2020)">Cycle 8 (Jul 20 - Aug 16)</option>
                                                        <option value="Cycle 9 (Aug 17 2020 - Sep 13 2020)">Cycle 9 (Aug 17 - Sep 13)</option>
                                                        <option value="Cycle 10 (Sep 14 2020 - Oct 11 2020)">Cycle 10 (Sep 14 - Oct 11)</option>
                                                        <option value="Cycle 11 (Oct 12 2020 - Nov 8 2020)">Cycle 11 (Oct 12 - Nov 8)</option>
                                                        <option value="Cycle 12 (Nov 9 2020 - Dec 6 2020)">Cycle 12 (Nov 9 - Dec 6)</option>
                                                        <option value="Cycle 13 (Dec 7 2020 - Jan 3 2020)">Cycle 13 (Dec 7 - Jan 3)</option>

                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>5. Budget</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="budget" name="budget">
                                                <span class="help-block">Minimum $29,000</span>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>6. Plus Up 1 (Aisle Navigator Sticker Placement)</h4>
                                            <div class="padding-20 border bg-grey-100 margin-bottom-30">
                                                <div class="checkbox-custom checkbox-primary margin-top-20">
                                                    <input class="switch-input" type="checkbox" id="upgrade-1" name="upgrade-1" value="1">
                                                    <label class="switch-paddle" for="upgrade-1">
                                                        <span class="show-for-sr">Plus Up 1</span>
                                                        <span class="switch-active" aria-hidden="true">Yes</span>
                                                        <span class="switch-inactive" aria-hidden="true">No</span>
                                                    </label>
                                                    <label for="upgrade-1" class="font-size-16 font-weight-400">Added cost of $5 per store</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>7. Plus Up 2 (Tear Pad / Coupon Placement)</h4>
                                            <div class="padding-20 border bg-grey-100 margin-bottom-30">
                                                <div class="checkbox-custom checkbox-primary margin-top-20">
                                                    <input class="switch-input" type="checkbox" id="upgrade-2" name="upgrade-2" value="1">
                                                    <label class="switch-paddle" for="upgrade-2">
                                                        <span class="show-for-sr">Plus Up 1</span>
                                                        <span class="switch-active" aria-hidden="true">Yes</span>
                                                        <span class="switch-inactive" aria-hidden="true">No</span>
                                                    </label>
                                                    <label for="upgrade-2" class="font-size-16 font-weight-400">Added cost of $27 per store</label>
                                                </div>
                                                <br />
                                                <div class="nav-tabs-horizontal" style="display: none;">
                                                    <ul class="tabs" data-tabs id="creative-tabs">
                                                        <li class="tabs-title is-active" role="presentation"><a href="#selectUpgrade21" aria-controls="selectUpgrade21" role="tab">Upload your own</a></li>
                                                        <li class="tabs-title" role="presentation"><a href="#selectUpgrade22" aria-controls="selectUpgrade22" role="tab">Have Red/E develop</a></li>
                                                    </ul>
                                                    <div class="tabs-content" data-tabs-content="creative-tabs">
                                                        <div class="tabs-panel is-active" id="selectUpgrade21" role="tabpanel">
                                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                                <h4>Upload Your Own Creative</h4>
                                                                <input type="file" id="fileupload2" class="hide" />
                                                                <?php $ajax_nonce = wp_create_nonce( "media-nonce" ); ?>
                                                                <input type="hidden" id="media-nonce" value="<?php echo $ajax_nonce; ?>"> 
                                                                <a class="button success" id="upload2"><i class="fi-upload small"></i> Upload File</a>
                                                                <a class="button secondary btn-lg inverted hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                                
                                                                <div class="progress margin-vertical-20">
                                                                    <div class="progress-bar" role="progressbar" style="width: 0%;">
                                                                    </div>
                                                                </div>
                                                                <div class="fileinfo"></div>
                                                                <input type="hidden" id="fileguidup2" name="fileguidup2" />
                                                                <input type="hidden" id="filenameup2" name="filenameup2" />
                                                            </div>
                                                        </div>
                                                        <div class="tabs-panel" id="selectUpgrade22" role="tabpanel">
                                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                                <h4>Get Your Creative Developed by Red/E</h4>
                                                                <div class="checkbox-custom checkbox-primary margin-top-20">
                                                                    <input type="checkbox" id="upgrade2-custom" name="upgrade2-custom" value="1">
                                                                    <label for="upgrade2-custom" class="font-size-16 font-weight-400">Red/E develop creative</label>
                                                                </div>
                                                                <span class="help-block">The campaign start date is subject to change upon receiving, designing and approving creative elements in a timely manner. RED/E will work with you to accept all existing or newly furnished assets as quickly as possible to meet the requested program start date.</span>
                                                                <div class="reveal" id="redeupgrade2modal" data-reveal>
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
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>8. Plus Up 3 (Dual Sided Creative Execution)</h4>
                                            <div class="padding-20 border bg-grey-100 margin-bottom-30">
                                                <div class="checkbox-custom checkbox-primary margin-top-20">
                                                    <input class="switch-input" type="checkbox" id="upgrade-3" name="upgrade-3" value="1">
                                                    <label class="switch-paddle" for="upgrade-3">
                                                        <span class="show-for-sr">Plus Up 3</span>
                                                        <span class="switch-active" aria-hidden="true">Yes</span>
                                                        <span class="switch-inactive" aria-hidden="true">No</span>
                                                    </label>
                                                    <label for="upgrade-3" class="font-size-16 font-weight-400">One time $2,500 fee</label>
                                                </div>
                                                <br />
                                                <div class="nav-tabs-horizontal" style="display: none;">
                                                    <ul class="tabs" data-tabs id="creative-tabs">
                                                        <li class="tabs-title is-active" role="presentation"><a href="#selectCreative31" aria-controls="selectCreative31" role="tab">Upload your own</a></li>
                                                        <li class="tabs-title" role="presentation"><a href="#selectCreative32" aria-controls="selectCreative32" role="tab">Have Red/E develop</a></li>
                                                    </ul>
                                                    <div class="tabs-content" data-tabs-content="creative-tabs">
                                                        <div class="tabs-panel is-active" id="selectCreative31" role="tabpanel">
                                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                                <h4>Upload Your Own Creative</h4>
                                                                <input type="file" id="fileupload3" class="hide">
                                                                <a class="button success" id="upload3"><i class="fi-upload small"></i> Upload File</a>
                                                                <a class="button secondary btn-lg inverted hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                                
                                                                <div class="progress margin-vertical-20">
                                                                    <div class="progress-bar" role="progressbar" style="width: 0%;">
                                                                    </div>
                                                                </div>
                                                                <div class="fileinfo"></div>
                                                                <input type="hidden" id="fileguidup3" name="fileguidup3" />
                                                                <input type="hidden" id="filenameup3" name="filenameup3" />
                                                            </div>
                                                        </div>
                                                        <div class="tabs-panel" id="selectCreative32" role="tabpanel">
                                                            <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                                <h4>Get Your Creative Developed by Red/E</h4>
                                                                <div class="checkbox-custom checkbox-primary margin-top-20">
                                                                    <input type="checkbox" id="upgrade3-custom" name="upgrade3-custom" value="1">
                                                                    <label for="upgrade3-custom" class="font-size-16 font-weight-400">Red/E develop creative</label>
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
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>9. Audience</h4>
                                            <span class="help-block">Select the ideal retail target you want to reach for your activation. Audience will be targeted within 5 mile radius of store location.</span>
                                            <div class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true">
                                                <?php
                                                $dmas = get_dmas();

                                                // print_r($dmas);
                                                // die();

                                                $i = 0;
                                                foreach ($dmas as $storename=>$storelist){
                                                    $i++; ?>
                                                    <div class="accordion-item" data-accordion-item>
                            
                                                        <a class="panel-title collapsed accordion-title" data-toggle="collapse" href="#exampleCollapseDefaultOne<?php echo $i; ?>" data-parent="#exampleAccordionDefault<?php echo $i; ?>" aria-expanded="false" aria-controls="exampleCollapseDefaultOne<?php echo $i; ?>">
                                                            <h4 class="padding-0 margin-0"><?php echo $storename; ?></h4>
                                                        </a>

                                                        <div class="accordion-content" data-tab-content id="exampleCollapseDefaultOne<?php echo $i; ?>" aria-labelledby="exampleHeadingDefaultOne<?php echo $i; ?>" role="tabpanel" aria-expanded="false">
                                                            <div class="panel-body">
                                                                <div class="checkbox-custom checkbox-primary">
                                                                    <input type="checkbox" name="store" value="<?php echo $i; ?>" data-parent="<?php echo $i; ?>" data-num="0" class="parent-store-check" />
                                                                    <label class="font-size-16 font-weight-500"><?php echo $storename; ?></label>
                                                                </div>

                                                            <?php
                                                            foreach ($storelist as $store){ ?>
                                                                <div class="checkbox-custom checkbox-primary">
                                                                    <input type="checkbox" name="store" value="<?php echo $storename . ' - ' . $store['location']; ?>" data-num="<?php echo $store['count']; ?>" class="p<?php echo $i; ?>">
                                                                    <label for="i1" class="font-size-16 font-weight-300"><?php echo $store['location']; ?> (<?php echo number_format($store['count']); ?>)</label>
                                                                </div>
                                                            <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>10. DMAs</h4>
                                            <div class="padding-bottom-30" id="topdmas">
                                                <div class="checkbox-custom checkbox-secondary">
                                                    <input class="dma-check" type="checkbox" id="dma-national" name="dma" value="National">
                                                    <label for="i1" class="font-size-16 font-weight-300">National</label>
                                                </div>
                                                <div class="checkbox-custom checkbox-secondary">
                                                    <input class="dma-check" type="checkbox" id="dma-all">
                                                    <label for="i1" class="font-size-16 font-weight-300">Select All Top 50</label>
                                                </div>
                                            <?php
                                            $dmas = get_top_dmas();

                                            // print_r($dmas);
                                            // die();

                                            $i = 0;
                                            foreach ($dmas as $dma){?>
                                                <div class="checkbox-custom checkbox-secondary">
                                                    <input class="dma-check" type="checkbox" data-count="<?php echo $dma['total']; ?>" id="dma-<?php echo $dma['total']; ?>" name="dma" value="<?php echo $dma['dma']; ?>">
                                                    <label for="i1" class="font-size-16 font-weight-300"><?php echo $dma['dma']; ?></label>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30 input-container">
                                            <h4>11. Optimization Preferences</h4>
                                            <select data-plugin="selectpicker" id="optimization" name="optimization">
                                                <option value="National List">National List</option>
                                                <option value="High Volume Stores via Sales">High Volume Stores via Sales</option>
                                                <option value="Low Volume Stores via Sales">Low Volume Stores via Sales</option>
                                                <option value="High CDI Low BDI">High CDI Low BDI</option>
                                                <option value="Weighted w/ Higher Multicultural Stores Index">Weighted w/ Higher Multicultural Stores Index</option>
                                                <option value="Weighted w/ Higher Urban Stores Index">Weighted w/ Higher Urban Stores Index</option>
                                            </select>

                                            
                                        </div>

                       
                                        <div class="padding-bottom-30 input-container">
                                            <h4>12. Select Your Creative</h4>
                                            <span class="help-block margin-bottom-20">Upload your own, or have Red/E develop your creative</span>
                                            <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#selectCreative1" aria-controls="selectCreative1" role="tab">Upload your own</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#selectCreative2" aria-controls="selectCreative2" role="tab">Have Red/E develop</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="selectCreative1" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Upload Your Own Creative</h4>
                                                            <input type="file" id="fileupload" class="hide" />
                                                            <a class="button success" id="upload"><i class="fi-upload small"></i> Upload File</a>
                                                            <a class="button secondary btn-lg inverted hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            
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
                                                    <div class="tabs-panel" id="selectCreative2" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Get Your Creative Developed by Red/E</h4>
                                                            <div class="checkbox-custom checkbox-primary margin-top-20">
                                                                <input type="checkbox" id="tactic-custom" name="tactic-custom" value="1">
                                                                <label for="tactic-custom" class="font-size-16 font-weight-400">Red/E develop creative</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <br />
                                        <div class="padding-bottom-30 input-container">
                                            <h4>13. Other Consideration</h4>
                                            <div class="example">
                                                <textarea class="form-control" id="otherconsiderations" name="otherconsiderations"></textarea>
                                                <span class="help-block">•	Any additional comments or requests</span>
                                            </div>
                                        </div>


                                        <div class="padding-bottom-30 input-container">
                                            <h4>14. Confirm and Proceed to Checkout</h4>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <table class="table table-striped border">
                                                        <tr>
                                                            <td>Per Shroud Cost
                                                            </td>
                                                            <td class="summary-per-shroud">0
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Stores
                                                            </td>
                                                            <td class="summary-total-stores">0
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Budget
                                                            </td>
                                                            <td class="summary-total-cost">$0
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <span class="font-size-12"><ul>
                                                        <li>From checkout to reporting, Red/E Marketing Order costs are all inclusive.</li>
                                                        <li>* Confirm In-Market Date & Order Total Cost.</li>
                                                        <li>* If total selected store count exceeds your budget, we will proportionately reduce your store count to reflect the budgeted amount. Distribution to be determined by your selection under Optimization Preferences.</li></ul></span>
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
                                        // print_r($order_status);
                                        // print_r($update_post);
                                        // die();
                                        if($update_post){ ?>
                                        <button class="button success update">Update</button>
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
                                        <input type="hidden" name="total" id="total" value="$0" />
                                        <input type="hidden" id="storecount" name="storecount" value="0">
                                        <!-- <input type="hidden" name="tactic" id="tactic" value="Security Shroud" /> -->
                                        <!-- <input type="hidden" name="marketdate" id="marketdate" value="Cycle 1 (Jan 8 - Feb 4)" /> -->
                                        <input type="hidden" name="type" id="type" value="Security Shroud" />
                                        <input type="hidden" name="ordertype" id="ordertype" value="securityshroud" />
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
/*
    <script>
        $(document).ready(function () {
            $(".datepicker").datepicker({});

            function isInt(value) {
                return !isNaN(value) &&
                       parseInt(Number(value)) == value &&
                       !isNaN(parseInt(value, 10));
            }

            // $("#budget").change(function () {
            //     $("#total").val($("#budget").val());
            //     $(".summary-total-cost").text($("#budget").val());
            // });

            function getShroudTotal() {
                var base = 120;
                var upgrade1 = 0;
                var upgrade2 = 0;
                var upgrade3 = 0;
                var storecount = $("#storecount").val();

                if ($('#upgrade-1').is(':checked')) {
                    upgrade1 = 5;
                }

                if ($('#upgrade-2').is(':checked')) {
                    upgrade2 = 27;
                }

                if ($('#upgrade-3').is(':checked')) {
                    upgrade3 = 2500;
                }

                var __total = (storecount * base) + (storecount * upgrade1) + (storecount * upgrade2) + (storecount * upgrade3);
                $(".summary-total-cost").text(formatter(__total));
                $("#total").val(formatter(__total));
            }

            $(".dma-check").click(function () {
                var storenum = 0;
                $("input[name=dma]:checked").each(function () {
                    storenum += parseInt($(this).attr("data-count"));
                });

                $("#storecount").val(storenum);
                $(".summary-total-stores").text(storenum);
                getShroudTotal();
            });


            $("#retailer").change(function () {
                // alert($("#retailer").val());
                var param = {};
                param.retailer = $("#retailer").val();
                $.ajax({
                    url: "/api/order/GetDMAString",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(param),
                    dataType: "json",
                    success: function (data) {
                        $("#dma").html(data);
                        $("#dma").selectpicker('refresh');
                        getShroudTotal2();

                    }
                });

            });

            function getShroudTotal2() {
                var param = {};
                param.retailer = $("#retailer").val();
                param.dma = $("#dma").val();
                $.ajax({
                    url: "/api/order/GetDMACount",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(param),
                    dataType: "json",
                    success: function (data) {
                        $("#storecount").val(data);
                        $(".summary-total-stores").text($("#storecount").val());
                        getShroudTotal();
                    }
                });
            }

            $("#dma-bak").change(function () {
                // alert($("#retailer").val());
                var param = {};
                param.retailer = $("#retailer").val();
                param.dma = $("#dma").val();
                $.ajax({
                    url: "/api/order/GetDMACount",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(param),
                    dataType: "json",
                    success: function (data) {
                        $("#storecount").val(data);
                        $(".summary-total-stores").text($("#storecount").val());
                        getShroudTotal();
                    }
                });
            });

            $(".btnproceed").click(function () {
                

                var success = true;
                var errmsg = "Oops, we detected a few errors:\n------------------------------------------\n";

                if ($("#ordername").val() == null || $("#ordername").val() == "") {
                    errmsg += "Please enter an order name\n";
                    success = false;
                }

                //if ($("#marketdate").val() == null || $("#marketdate").val() == "") {
                //    errmsg += "Please enter an market date\n";
                //    success = false;
                //}

                if ($("#budget").val() == null || $("#budget").val() == "") {
                    errmsg += "Please enter a valid budget\n";
                    success = false;
                }else{

               
                var _q = $("#budget").val().replace(" ", "").replace("$", "").replace(",", "");
         
                    if (_q < parseFloat(29000)) {
                        errmsg += "Budget must be at least $29,000\n";
                        success = false;
                    }


                    
                }

                if (success == false) {
                    alert(errmsg);
                    return false;
                }

                var param = {};
                param.jsondata = $("form").serialize();
                param.ordername = $("#ordername").val();
                param.type = "Security Shroud";

                $.ajax({
                    url: "/api/order/create",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(param),
                    dataType: "json",
                    success: function (data) {
                        if (data > 0) {
                            window.location.href = "/rede/confirm/" + data;
                        } else {
                            alert("An error occured in attemptiong to process your order. Please try again.");
                        }
                    }
                });

                return false;
            });

            var formatter = function (n) {
                return "$" + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
            };

            var formatterstore = function (n) {
                n = parseFloat(n);
                return n.toFixed(1).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").replace(".0", "");
            };

            $("#upload").click(function () {
                $("#fileupload").click();
            });

            $("#CampaignTiming").change(function (event) {
                $("#marketdate").val($("#CampaignTiming").val());
            });

            $("#fileupload").change(function (event) {
                if ($("fileupload").val() == "")
                    return false;

                var files = $("#fileupload").get(0).files;
                var formdata = new FormData();
                for (i = 0; i < files.length; i++) {
                    formdata.append("file" + i, files[i]);
                }
                $("#fileguid").val("");
                $("#filename").val("");
                $("#fileupload").val("");

                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", profileprogressHandler, false);
                ajax.addEventListener("load", profilecompleteHandler, false);
                ajax.addEventListener("error", profileerrorHandler, false);
                ajax.addEventListener("abort", profileabortHandler, false);
                ajax.open("POST", "/api/order/uploadcreative");
                ajax.send(formdata);
            });


            function profileprogressHandler(event) {
                var percent = (event.loaded / event.total) * 100;
                $(".progress-bar").width(percent + "%");
            }

            function profilecompleteHandler(event) {
                var data = JSON.parse(event.target.response);

                if (data.status == 0) {
                    $(".fileinfo").html(data.filename);
                    $("#fileguid").val(data.fileguid);
                    $("#filename").val(data.filename);
                }
            };

            function profileerrorHandler(event) {
                //console.log("error", event);
            };

            function profileabortHandler(event) {
                //console.log("abort", event);
            };

            $(".parent-store-check").click(function () {
                var id = $(this).val();
                var isChecked = $(this).is(':checked')
                var checkboxes = $(".p" + id);

                if ($(this).is(':checked')) {
                    checkboxes.prop("checked", true);

                } else {
                    checkboxes.prop("checked", false);
                }

                storenum = 0;
                $("input[name=store]:checked").each(function () {
                    // console.log($(this).attr("value"));
                    storenum += parseInt($(this).attr("data-num"));
                });

                $("#storecount").val(storenum);
                runTotals(storenum);

            });

            $("#upload2").click(function () {
                $("#fileupload2").click();
            });

            function profileprogressHandler2(event) {
                var percent = (event.loaded / event.total) * 100;
                $(".progress-bar2").width(percent + "%");
            }

            function profilecompleteHandler2(event) {
                var data = JSON.parse(event.target.response);

                if (data.status == 0) {
                    $(".fileinfo2").html(data.filename);
                    $("#fileguidup2").val(data.fileguid);
                    $("#filenameup2").val(data.filename);
                }
            };

            function profileerrorHandler2(event) {
                //console.log("error", event);
            };

            function profileabortHandler2(event) {
                //console.log("abort", event);
            };

            $('#budget').inputmask("numeric", {
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true,
                prefix: '$ ', //Space after $, this will not truncate the first character.
                rightAlign: false,
                oncleared: function () { self.Value(''); }
            });

            $("#fileupload2").change(function (event) {
                if ($("fileupload2").val() == "")
                    return false;

                var files = $("#fileupload2").get(0).files;
                var formdata = new FormData();
                for (i = 0; i < files.length; i++) {
                    formdata.append("file" + i, files[i]);
                }
                $("#fileguidup2").val("");
                $("#filenameup2").val("");
                $("#fileupload2").val("");

                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", profileprogressHandler2, false);
                ajax.addEventListener("load", profilecompleteHandler2, false);
                ajax.addEventListener("error", profileerrorHandler2, false);
                ajax.addEventListener("abort", profileabortHandler2, false);
                ajax.open("POST", "/api/order/uploadcreative");
                ajax.send(formdata);
            });

            //up3

            $("#upload3").click(function () {
                $("#fileupload3").click();
            });

            function profileprogressHandler3(event) {
                var percent = (event.loaded / event.total) * 100;
                $(".progress-bar3").width(percent + "%");
            }

            function profilecompleteHandler3(event) {
                var data = JSON.parse(event.target.response);

                if (data.status == 0) {
                    $(".fileinfo3").html(data.filename);
                    $("#fileguidup3").val(data.fileguid);
                    $("#filenameup3").val(data.filename);
                }
            };

            function profileerrorHandler3(event) {
                //console.log("error", event);
            };

            function profileabortHandler3(event) {
                //console.log("abort", event);
            };


            $("#fileupload3").change(function (event) {
                if ($("fileupload3").val() == "")
                    return false;

                var files = $("#fileupload3").get(0).files;
                var formdata = new FormData();
                for (i = 0; i < files.length; i++) {
                    formdata.append("file" + i, files[i]);
                }
                $("#fileguidup3").val("");
                $("#filenameup3").val("");
                $("#fileupload3").val("");

                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", profileprogressHandler3, false);
                ajax.addEventListener("load", profilecompleteHandler3, false);
                ajax.addEventListener("error", profileerrorHandler3, false);
                ajax.addEventListener("abort", profileabortHandler3, false);
                ajax.open("POST", "/api/order/uploadcreative");
                ajax.send(formdata);
            });
        });
    </script>
</asp:Content>
*/
