<?php
/* Template Name: Coupon */

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
                                            <span class="help-block">Enter a name for the new Coupon program you wish to create.</span>
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
                                            <span class="help-block">Select the brand or business name that will be featured as part of your activation.</span>
                                        </div>
                                        <div class="padding-bottom-30">
                                            <h4>3. Program Objective</h4>
                                            <div class="example">
                                                <select data-plugin="selectpicker" id="campaignobjective" name="campaignobjective">
                                                    <option value="Impressions – get my coupon ad in front of as many people as possible">Impressions – get my coupon ad in front of as many people as possible</option>
                                                    <option value="CTR – get people to click on my coupon ad">CTR – get people to click on my coupon ad</option>
                                                    <option value="Unique Reach – find as many unique people as possible to see my coupon ad">Unique Reach – find as many unique people as possible to see my coupon ad</option>
                                                    <option value="Frequency – get people to see my coupon ad as many times as possible">Frequency – get people to see my coupon ad as many times as possible</option>
                                                </select>
                                            </div>
                                            <span class="help-block">Select the measurable objective that aligns closest with your marketing intent.</span>
                                        </div>
                                        <div class="padding-bottom-30">
                                            <h4>4. Program Purpose</h4>
                                            <div class="example">
                                                <select data-plugin="selectpicker" id="campaignpurpose" name="campaignpurpose">
                                                    <option value="New Product – get people to see my new product / innovation, drive trial">New Product – get people to see my new product / innovation, drive trial</option>
                                                    <option value="Brand Awareness – get people to see my brands value, build my base business">Brand Awareness – get people to see my brands value, build my base business</option>
                                                    <option value="Promote Offer – get people to use my promotion / incentive for an upcoming purchase">Promote Offer – get people to use my promotion / incentive for an upcoming purchase</option>
                                                </select>
                                            </div>
                                            <span class="help-block">Select the measurable objective that aligns closest with your marketing intent.</span>
                                        </div>
                                        <div class="padding-bottom-30">
                                            <h4>5. Program Timing</h4>
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control" id="marketdate" name="marketdate">
                                                <div class="input-group-addon">to</div>
                                                <input type="text" class="form-control" id="enddate" name="enddate">
                                            </div>
                                            <span class="help-block">Identify the start / end dates for your activation. Timing must meet 1 full week requirement.</span>

                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>6. Shopper Target</h4>
                                            <span class="help-block">Select the ideal shoppers target you want to reach for your activation or upload a custom segmentation / profile.</span>
                                            <div class="example">
                                                <h4>Gender</h4>
                                                <select data-plugin="selectpicker" id="profilegender" name="profilegender">
                                                    <option value="Female">Female</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Both">Both</option>
                                                </select>
                                            </div>
                                            <div class="example">
                                                <h4>Age (select one or more)</h4>
                                                <select data-plugin="selectpicker" id="profileage" name="profileage">
                                                    <option value="18-24">18-24</option>
                                                    <option value="25-34">25-34</option>
                                                    <option value="35-44">35-44</option>
                                                    <option value="45-54">45-54</option>
                                                    <option value="55+">55+</option>
                                                </select>
                                            </div>
                                            <div class="example">
                                                <h4>Children In Household Age</h4>
                                                <select data-plugin="selectpicker" id="profilechildren" name="profilechildren">
                                                    <option value="0">0</option>
                                                    <option value="<3"><3</option>
                                                    <option value="4-6">4-6</option>
                                                    <option value="7-9">7-9</option>
                                                    <option value="10-12">10-12</option>
                                                    <option value="13-18">13-18</option>
                                                </select>
                                            </div>
                                            <div class="example">
                                                <h4>Household Income</h4>
                                                <select data-plugin="selectpicker" id="profileincome" name="profileincome">
                                                    <option value="< $19,999">< $19,999</option>
                                                    <option value="$20,000 - $50,000">$20,000 - $50,000</option>
                                                    <option value="$50,000 - $74,999">$50,000 - $74,999</option>
                                                    <option value="$75,000 - $99,999">$75,000 - $99,999</option>
                                                    <option value="$100,000 - $149,000">$100,000 - $149,000</option>
                                                    <option value="$150,000 - $199,999">$150,000 - $199,999</option>
                                                    <option value="$200,000 - $249,000">$200,000 - $249,000</option>
                                                    <option value="$250,000 +">$250,000 +</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>7. Select Retailer Audience </h4>
                                            <span class="help-block">You can choose one of the following or leave it blank and move to item #8.</span>
                                            <div class="nav-tabs-horizontal">
                                                <ul class="tabs" data-tabs id="creative-tabs">
                                                    <li class="tabs-title is-active" role="presentation"><a href="#retTabsOne" aria-selected="true">Retailer</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#topdmas" aria-controls="topdmas" role="tab">DMA</a></li>
                                                    <li class="tabs-title" role="presentation"><a href="#regTabsTwo" aria-controls="regTabsTwo" role="tab">Upload</a></li>
                                                </ul>
                                                <div class="tabs-content" data-tabs-content="creative-tabs">
                                                    <div class="tabs-panel is-active" id="retTabsOne" role="tabpanel">
                                                        <span class="help-block">Select the ideal retailer target you want to reach for your activation.</span>
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
                                                    <div class="tabs-panel" id="topdmas" role="tabpanel">
                                                        <span class="help-block">Select the ideal DMAs you want to reach for your activation. Audience will be targeted within 5 mile radius of store location.</span>
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
                                                    <div class="tabs-panel" id="regTabsTwo" role="tabpanel">
                                                        <div class="padding-30 border bg-grey-100 margin-bottom-30">
                                                            <h4>Upload Your Own Custom List</h4>
                                                            <input type="file" id="fileuploadlist" class="hide">
                                                            <a class="button success" id="uploadlist"><i class="fi-upload small"></i> Upload File</a>
                                                            <a class="button secondary inverted hide" id="preview2"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
                                                            
                                                            <div class="progress margin-vertical-20">
                                                                <div class="progress-bar2" role="progressbar" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="fileinfo"></div>
                                                            <input type="hidden" id="customlistguid" name="customlistguid">
                                                            <input type="hidden" id="customlistname" name="customlistname">
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
                                            <h4>9. Enter a Coupon Destination URL</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="destinationurl" name="destinationurl">
                                                <span class="help-block">Insert URL. Identify the location / website where you would like your audience to visit.</span>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>10. Budget</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="budget" name="budget">
                                                <span class="help-block">Minimum $5,000</span>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <h4>11. Other Considerations</h4>
                                            <div class="example">
                                                <input type="text" class="form-control" id="otherdetails" name="otherdetails">
                                                <span class="help-block">What other details are worth noting as we get ready to launch your activation?</span>
                                            </div>
                                        </div>

                                        <div class="padding-bottom-30">
                                            <div class="example">
                                                <div class="col-md-9">
                                                    <p>CPM and impressions will be calculated and delivered to you via email. From checkout to reporting, Red/E Marketing Order costs are all inclusive. </p>
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
                                        <input type="hidden" name="storecount" id="storecount" value="0"/>
                                        <input type="hidden" name="total" id="total" value="$15,000.00" />
                                        <input type="hidden" name="type" id="type" value="Coupon Booster" />
                                        <input type="hidden" name="ordertype" id="ordertype" value="Coupon Booster" />
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
            $("#marketdate").datepicker({
                startDate: '+2d'
            });

            function addDays(date, days) {
                var result = new Date(date);
                result.setDate(result.getDate() + days);
                return result;
            }

            $("#marketdate").change(function () {

                var _t = addDays($('#marketdate').val(), 7);
                $("#enddate").datepicker('remove');
                $("#enddate").datepicker({
                    startDate: (_t.getMonth() + 1) + '/' + _t.getDate() + '/' + _t.getFullYear()
                });
            });

            $('#budget').inputmask("numeric", {
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true,
                prefix: '$ ', //Space after $, this will not truncate the first character.
                rightAlign: false,
                oncleared: function () { self.Value(''); }
            });
            $(".parent-store-check").click(function () {
                var storenum = 0;
                $("input[name=store]:checked").each(function () {
                    storenum += parseInt($(this).attr("data-num"));
                });

                $("#storecount").val(storenum);
                $(".summary-total-stores").text(formatterstore(storenum));
            });

            function isInt(value) {
                return !isNaN(value) &&
                       parseInt(Number(value)) == value &&
                       !isNaN(parseInt(value, 10));
            }

            $("#budget").change(function () {
                $("#total").val($("#budget").val());
                $(".summary-total-cost").text($("#budget").val());
            });

            $(".btnproceed").click(function () {
                var success = true;
                var errmsg = "Oops, we detected a few errors:\n------------------------------------------\n";

                if ($("#ordername").val() == null || $("#ordername").val() == "") {
                    errmsg += "Please enter an order name\n";
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
                param.type = "Coupon";

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

            //Store upload code

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
                    $("#customlistguid").val(data.fileguid);
                    $("#customlistname").val(data.filename);
                }
            };

            function profileerrorHandler2(event) {
                //console.log("error", event);
            };

            function profileabortHandler2(event) {
                //console.log("abort", event);
            };


            $("#fileupload2").change(function (event) {
                if ($("fileupload2").val() == "")
                    return false;

                var files = $("#fileupload2").get(0).files;
                var formdata = new FormData();
                for (i = 0; i < files.length; i++) {
                    formdata.append("file" + i, files[i]);
                }
                $("#customlistguid").val("");
                $("#customlistname").val("");
                $("#fileupload2").val("");

                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", profileprogressHandler2, false);
                ajax.addEventListener("load", profilecompleteHandler2, false);
                ajax.addEventListener("error", profileerrorHandler2, false);
                ajax.addEventListener("abort", profileabortHandler2, false);
                ajax.open("POST", "/api/order/uploadcreative");
                ajax.send(formdata);
            });
        });

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
    </script>
</asp:Content>
*/