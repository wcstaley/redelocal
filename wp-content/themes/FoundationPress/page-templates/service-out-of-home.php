<?php
/* Template Name: Out of Home */

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
                                            <span class="help-block">Enter a name for the new Out of Home Digital Billboards program you wish to create.</span>
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
                                            <h4>3. Client KPI / Goal</h4>
                                            <select data-plugin="selectpicker" id="campaignobjective" name="campaignobjective">
                                                <option value="Impressions">Impressions</option>
                                                <option value="Unique Reach">Unique Reach</option>
                                                <option value="Frequency">Frequency</option>
                                                <option value="Audience profile report">Audience profile report</option>
                                            </select>
                                            <span class="help-block">Please Select a Goal</span>
                                        </div>
<!--                                         <div class="padding-bottom-30">
                                            <h4>5. Demographics</h4>
                                            <select data-plugin="selectpicker" id="demographics" name="demographics" multiple>
                                                <option value="18-34 yrs">18-34 yrs</option>                                             
                                                <option value="21+ yrs">21+ yrs</option>                                             
                                                <option value="21-54 yrs">21-54 yrs</option>                                             
                                                <option value="40+ yrs">40+ yrs</option>                                             
                                                <option value="50+ yrs">50+ yrs</option>                                             
                                                <option value="African-American 18+ yrs">African-American 18+ yrs</option>                                             
                                                <option value="African-American 18-34 yrs">African-American 18-34 yrs</option>                                             
                                                <option value="African-American 21+ yrs">African-American 21+ yrs</option>                                             
                                                <option value="African-American 25-54 yrs">African-American 25-54 yrs</option>                                             
                                                <option value="African-American 30+ yrs">African-American 30+ yrs</option>                                             
                                                <option value="African-American 40+ yrs">African-American 40+ yrs</option>                                             
                                                <option value="African-American 50+ yrs">African-American 50+ yrs</option>                                             
                                                <option value="Asian 18-34 yrs">Asian 18-34 yrs</option>                                             
                                                <option value="Asian 25-54 yrs">Asian 25-54 yrs</option>                                             
                                                <option value="Asian 35+ yrs">Asian 35+ yrs</option>                                             
                                                <option value="Asian 50+ yrs">Asian 50+ yrs</option>                                             
                                                <option value="HHI $100,000-$149,999 aged 18+ yrs">HHI $100,000-$149,999 aged 18+ yrs</option>                                  
                                                <option value="HHI $100-150k aged 25-54 yrs">HHI $100-150k aged 25-54 yrs</option>                                           
                                                <option value="HHI $100,000+ aged 25-54 yrs">HHI $100,000+ aged 25-54 yrs</option>                                           
                                                <option value="HHI $150-200k aged 25-54 yrs">HHI $150-200k aged 25-54 yrs</option>                                           
                                                <option value="HHI $150,000+ aged 25-54 yrs">HHI $150,000+ aged 25-54 yrs</option>                                           
                                                <option value="HHI $200k+ aged 25-54 yrs">HHI $200k+ aged 25-54 yrs</option>                                             
                                                <option value="HHI $25,000-$49,999 aged 18-34 yrs">HHI $25,000-$49,999 aged 18-34 yrs</option>                                  
                                                <option value="HHI $25-50k aged 25-54 yrs">HHI $25-50k aged 25-54 yrs</option>                                           
                                                <option value="HHI $25,000+ aged 18-34 yrs">HHI $25,000+ aged 18-34 yrs</option>                                           
                                                <option value="HHI $25,000+ aged 25-54 yrs">HHI $25,000+ aged 25-54 yrs</option>                                           
                                                <option value="HHI $75,000+ aged 25-54 yrs">HHI $75,000+ aged 25-54 yrs</option>                                           
                                                <option value="Hispanic 21-54 yrs">Hispanic 21-54 yrs</option>                                             
                                                <option value="Hispanic 35+ yrs">Hispanic 35+ yrs</option>                                             
                                                <option value="Hispanic 40+ yrs">Hispanic 40+ yrs</option>                                             
                                                <option value="Hispanic 50+ yrs">Hispanic 50+ yrs</option>                                             
                                                <option value="HHI $100,000+ 18+ yrs">HHI $100,000+ 18+ yrs</option>                                             
                                                <option value="HHI $50,000+ 18+ yrs">HHI $50,000+ 18+ yrs</option>                                             
                                                <option value="HHI $75,000+ 18+ yrs">HHI $75,000+ 18+ yrs</option>                                             
                                                <option value="African-American 18+ yrs">African-American 18+ yrs</option>                                             
                                                <option value="Asian 18+ yrs">Asian 18+ yrs</option>                                             
                                                <option value="Total universe 18+ yrs">Total universe 18+ yrs</option>
                                            </select>
                                            <span class="help-block">Please Select a Demographic (Select all that pertain to your targeting)</span>
                                        </div> -->
                                        <div class="padding-bottom-30">
                                            <h4>4. Program Timing</h4>
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control" id="marketdate" name="marketdate">
                                                <div class="input-group-addon">to</div>
                                                <input type="text" class="form-control" id="enddate" name="enddate">
                                            </div>
                                            <span class="help-block">The date you wish for the campaign to start - the earliest start date is 48 hours from program approval.</span>

                                        </div>
                                        <div class="padding-bottom-30">
                                            <h4>5. Budget</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="budget" name="budget" value="$15,000">
                                            </div>
                                            <span class="help-block">Please enter a budget (minimum $15,000)</span>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>6. Select Retailer Audience</h4>
                                            
                                             <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#segTabsOne" aria-selected="true">Stores</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#segTabsFour" aria-selected="true">DMAs</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#segTabsThree" aria-controls="segTabsThree" role="tab">Upload your own</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="segTabsOne" role="tabpanel">
                                                        <span class="help-block">Select the ideal retailer target you want to reach for your activation.</span>
                                                        <div class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" id="exampleAccordionDefault">

                                                            <?php
                                                            // $stores = get_socialmobilebillboard_stores();
                                                            $stores = get_ooo_stores();
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
                                                                                        <input type="checkbox" name="store" value="<?php echo $store['id']; ?>" data-parent="<?php echo $store['val']; ?>" data-num="0" class="parent-store-check" />
                                                                                        <label class="font-size-16 font-weight-500"><?php echo $store['name']; ?></label>
                                                                                    </div>
                                                                                <?php } else { 
                                                                                    $parentNum = explode('-', $store['val']);
                                                                                    $parentNum = $parentNum[0];

                                                                                ?>
                                                                                    <div class="checkbox-custom checkbox-primary">
                                                                                        <input type="checkbox" name="store" value="<?php echo $store['id']; ?>" data-num="<?php echo $store['num']; ?>" class="parent-store-check p<?php echo $parentNum; ?>">
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

                                                    <div class="tabs-panel" id="segTabsFour" role="tabpanel">
                                                        <span class="help-block">Select the ideal DMAs you want to reach for your activation. Audience will be targeted within 5 mile radius of store location.</span>
                                                        <div class="form-panel" id="topdmas">
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
                                                                <div class="checkbox-custom checkbox-selectall">
                                                                    <input class="dma-check" type="checkbox" data-count="<?php echo $dma['total']; ?>" id="dma-<?php echo $dma['total']; ?>" name="dma" value="<?php echo $dma['dma']; ?>">
                                                                    <label for="i1" class="font-size-16 font-weight-300"><?php echo $dma['dma']; ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>

                                                    <div class="tabs-panel" id="segTabsThree" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Upload Your Own Store List or Geography</h4>
                                                            <input type="file" id="fileuploadlist" class="hide" />
                                                            <?php $ajax_nonce = wp_create_nonce( "media-nonce" ); ?>
                                                            <input type="hidden" id="media-nonce" value="<?php echo $ajax_nonce; ?>"> 
                                                            <a class="button success" id="uploadlist"><i class="fi-upload small"></i> Upload File</a>
                                                            <a class="button secondary inverted hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            
                                                            <div class="progress margin-vertical-20">
                                                                <div class="progress-bar" role="progressbar" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="fileinfo"></div>
                                                            <input type="hidden" id="customlistguid" name="customlistguid" />
                                                            <input type="hidden" id="customlistname" name="customlistname" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>7. Shopper Target</h4>
                                            <span class="help-block">Select the ideal demographic target you want to reach for your program or upload a custom list.</span>
                                             <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#geoTabsTwo" aria-selected="true">Demographics</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#geoTabsThree" aria-controls="geoTabsThree" role="tab">Upload your own</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="geoTabsTwo" role="tabpanel">
                                                        <h4>Select a Demographic</h4>
                                                        <div class="form-panel">
                                                            <span class="help-block">Select all that pertain to your target</span>
                                                            <div class="checkbox-custom checkbox-primary">           
                                                                <input type="checkbox" name="demographics" value="18-34 yrs"><label>18-34 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="21+ yrs"><label>21+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="21-54 yrs"><label>21-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="40+ yrs"><label>40+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="50+ yrs"><label>50+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 18+ yrs"><label>African-American 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 18-34 yrs"><label>African-American 18-34 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 21+ yrs"><label>African-American 21+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 25-54 yrs"><label>African-American 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 30+ yrs"><label>African-American 30+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 40+ yrs"><label>African-American 40+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 50+ yrs"><label>African-American 50+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Asian 18-34 yrs"><label>Asian 18-34 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Asian 25-54 yrs"><label>Asian 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Asian 35+ yrs"><label>Asian 35+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Asian 50+ yrs"><label>Asian 50+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="HHI $100,000-$149,999 aged 18+ yrs"><label>HHI $100,000-$149,999 aged 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                  
                                                                <input type="checkbox" name="demographics" value="HHI $100-150k aged 25-54 yrs"><label>HHI $100-150k aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $100,000+ aged 25-54 yrs"><label>HHI $100,000+ aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $150-200k aged 25-54 yrs"><label>HHI $150-200k aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $150,000+ aged 25-54 yrs"><label>HHI $150,000+ aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $200k+ aged 25-54 yrs"><label>HHI $200k+ aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="HHI $25,000-$49,999 aged 18-34 yrs"><label>HHI $25,000-$49,999 aged 18-34 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                  
                                                                <input type="checkbox" name="demographics" value="HHI $25-50k aged 25-54 yrs"><label>HHI $25-50k aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $25,000+ aged 18-34 yrs"><label>HHI $25,000+ aged 18-34 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $25,000+ aged 25-54 yrs"><label>HHI $25,000+ aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="HHI $75,000+ aged 25-54 yrs"><label>HHI $75,000+ aged 25-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                           
                                                                <input type="checkbox" name="demographics" value="Hispanic 21-54 yrs"><label>Hispanic 21-54 yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Hispanic 35+ yrs"><label>Hispanic 35+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Hispanic 40+ yrs"><label>Hispanic 40+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Hispanic 50+ yrs"><label>Hispanic 50+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="HHI $100,000+ 18+ yrs"><label>HHI $100,000+ 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="HHI $50,000+ 18+ yrs"><label>HHI $50,000+ 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="HHI $75,000+ 18+ yrs"><label>HHI $75,000+ 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="African-American 18+ yrs"><label>African-American 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Asian 18+ yrs"><label>Asian 18+ yrs</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-primary">                                             
                                                                <input type="checkbox" name="demographics" value="Total universe 18+ yrs"><label>Total universe 18+ yrs</label>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="tabs-panel" id="geoTabsThree" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Or, Upload Your Own List</h4>
                                                            <input type="file" id="fileuploadgeography" class="hide" />
                                                            <?php $ajax_nonce = wp_create_nonce( "media-nonce" ); ?>
                                                            <input type="hidden" id="media-nonce" value="<?php echo $ajax_nonce; ?>"> 
                                                            <a class="button success" id="uploadgeography"><i class="fi-upload small"></i> Upload File</a>
                                                            <a class="button secondary inverted hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            
                                                            <div class="progress margin-vertical-20">
                                                                <div class="progress-bar" role="progressbar" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="fileinfo"></div>
                                                            <input type="hidden" id="fileguidgeography" name="fileguidgeography" />
                                                            <input type="hidden" id="filenamegeography" name="filenamegeography" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>8. Select Your Creative</h4>
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
                                            <h4>9. Confirm and Proceed to Checkout</h4>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <table class="table table-striped border">
                                                         <tr>
                                                            <td>In Market Date
                                                            </td>
                                                            <td class="summary-date">
                                                            </td>
                                                        </tr>
<!--                                                         <tr>
                                                            <td>Total Billboards Available
                                                            </td>
                                                            <td class="summary-billboards">
                                                            </td>
                                                        </tr> -->
                                                        <tr>
                                                            <td>Total Order Cost
                                                            </td>
                                                            <td class="summary-total-cost">$15,000
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <span class="font-size-12"><ul>
                                                        <li>From checkout to reporting, Red/E Marketing Order costs are all inclusive.</li>
                                                        <li>* Confirm In-Market Date & Order Total Cost.</li>
                                                        <li>* Total Billboard Count to be provided via email following order submission.</li>
                                                        <li>* Custom audience lists will need to be manually reviewed by vendor before determining Billboard Count.</li></ul>
                                                    </span>
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
                                        <input type="hidden" name="billboardcount" id="billboardcount" />
                                        <input type="hidden" name="total" id="total" value="$15,000" />
                                        <input type="hidden" name="costperstore" id="costperstore" />
                                        <input type="hidden" name="type" id="type" value="Out of Home" />
                                        <input type="hidden" name="ordertype" id="ordertype" value="outofhome" />
                                        <input type="hidden" name="revision" id="revision" value="false" />
                                        <input type="hidden" name="serviceURL" id="serviceURL" value="<?php echo get_permalink();?>" />
                                        <?php $ajax_nonce = wp_create_nonce( "order-nonce" ); ?>
                                        <input type="hidden" id="order-nonce" value="<?php echo $ajax_nonce; ?>"> 
                                        <?php $ajax_nonce = wp_create_nonce( "billboards-nonce" ); ?>
                                        <input type="hidden" id="billboards-nonce" value="<?php echo $ajax_nonce; ?>"> 
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
            $('.datepicker').datepicker({
                startDate: '+21d'
            });

            $(".parent-store-check").click(function () {
                var storenum = 0;
                $("input[name=store]:checked").each(function () {
                    storenum += parseInt($(this).attr("data-num"));
                });

                $("#storecount").val(storenum);
                runTotals(storenum);
            });

            function isInt(value) {
                return !isNaN(value) &&
                       parseInt(Number(value)) == value &&
                       !isNaN(parseInt(value, 10));
            }

            $(".btnproceed").click(function () {
                var success = true;
                var errmsg = "Oops, we detected a few errors:\n------------------------------------------\n";

                if ($("#ordername").val() == null || $("#ordername").val() == "") {
                    errmsg += "Please enter an order name\n";
                    success = false;
                }

                if (isInt($("#quantity").val()) == false) {
                    errmsg += "Please enter a valid quantity amount\n";
                    success = false;
                }

                if ($("#marketdate").val() == null || $("#marketdate").val() == "") {
                    errmsg += "Please enter an market date\n";
                    success = false;
                }

                if (success == false) {
                    alert(errmsg);
                    return false;
                }

                var param = {};
                param.jsondata = $("form").serialize();
                param.ordername = $("#ordername").val();
                param.type = "On-Pack";

                $.ajax({
                    url: "/api/order/create",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(param),
                    dataType: "json",
                    success: function (data) {
                        //if (data > 0) {
                        window.location.href = "/rede/confirm/" + data;
                        //} else {
                        //    alert("An error occured in attemptiong to process your order. Please try again.");
                        //}
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

            function getCost(arg) {
                var cost = 0.025;

                if (arg >= 1 && arg <= 999) {
                    cost = 2.8;
                }


                if (arg >= 1000 && arg <= 2499) {
                    cost = 1.2;
                }


                if (arg >= 2500 && arg <= 4999) {
                    cost = 0.8;
                }


                if (arg >= 5000 && arg <= 9999) {
                    cost = 0.7;
                }


                if (arg >= 10000 && arg <= 24999) {
                    cost = 0.32;
                }


                if (arg >= 25000 && arg <= 29999) {
                    cost = 0.25;
                }


                if (arg >= 30000 && arg <= 49999) {
                    cost = 0.1;
                }

                if (arg >= 50000 && arg <= 99999) {
                    cost = 0.05;
                }


                if (arg >= 100000 && arg <= 199999) {
                    cost = 0.045;
                }


                if (arg >= 200000 && arg <= 399999) {
                    cost = 0.03;
                }


                if (arg >= 400000) {
                    cost = .025;
                }

                return cost;
            };

            function runTotals(arg) {
                var merch = 14;
                var fullfillment = 4;
                var shipping = 2;
                var markup = 1.1;
                var tacticcost = 0.5;
                var quantity = $("#quantity").val();
                tacticcost = getCost(quantity * arg);

                if (quantity == null || quantity == 0) {
                    tacticcost = 0;
                    quantity = 0;
                }

                var total = 0;
                total = merch * arg;
                total = (fullfillment * arg * markup) + total;
                total = (shipping * arg) + total;
                total = (quantity * arg * tacticcost * markup) + total;

                $(".summary-total-stores").text(formatterstore(arg));
                $(".summary-total-cost").text(formatter(total));

                if (arg > 0) {
                    $(".summary-cost-per-store").text(formatter(total / arg));
                } else {
                    $(".summary-cost-per-store").text("$0");
                }

                $("#total").val(formatter(total));
                $("#costperstore").val(formatter(total / arg));
            }

            $("#upload").click(function () {
                $("#fileupload").click();
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

            $("#marketdate").change(function () {
                $(".summary-date").text($("#marketdate").val());
            });
        });
    </script>
</asp:Content>

*/