<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function create_post_type() {
  register_post_type( 'rede-order',
    array(
      'labels' => array(
        'name' => __( 'Orders' ),
        'singular_name' => __( 'Order' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array('title','author','thumbnail','comments','revisions')
    )
  );
}
add_action( 'init', 'create_post_type' );

function rede_dashboard_qs_mb(){
    if(isset($_GET['post'])){
        $form_template = get_post_meta($_GET['post'], '_wp_page_template', true);
        $form_template = basename( $form_template );
            // print_r($form_template);
            // die();
        if($form_template === 'dashboard.php'){
            add_meta_box(
                'wporg_box_id',
                'Quick Start Mappings',
                'rede_dashboard_qs_mb_html',
                'page'                 
            );
        }
    }
}
add_action('add_meta_boxes', 'rede_dashboard_qs_mb');

function rede_dashboard_qs_mb_html($post){
    $quick_start = get_post_meta($post->ID, '_quick_start_setup', true);
    if(!is_array($quick_start)){
        $quick_start = array();
    }
    
    if(!isset($quick_start[0])){
        $quick_start[0]['objective'] = "";
        $quick_start[0]['timing'] = "";
        $quick_start[0]['budget'] = "";
        $quick_start[0]['service'] = "";
        $quick_start[0]['service'] = "";
    }
    $quick_start_index = 0;
    ?>
    <div class="rwmb-field rwmb-checkbox-wrapper quick-start">
        <?php foreach($quick_start as $quick_start_condition){ ?>
            <div class="quick-start-section" style="position: relative;padding-left: 15px;">
                <a href="javascript:;" class="rwmb-clone-icon ui-sortable-handle" style="margin-top: 5px;"></a>
                <select name="quick_start_setup[<?php echo $quick_start_index;?>][objective]">
                    <option value="">Objective</option>
                    <option value="any" <?php selected($quick_start_condition['objective'], 'any'); ?>>Any</option>
                    <?php $options = get_post_meta($post->ID, 'objective_options', true); 
                    // print_r($options); die(); 
                    foreach($options as $option){ ?>
                        <option value="<?php echo $option; ?>" <?php selected($quick_start_condition['objective'], $option); ?>><?php echo $option; ?></option>
                    <?php } ?>
                    ?>
                </select>
                <select name="quick_start_setup[<?php echo $quick_start_index;?>][timing]">
                    <option value="">Timing</option>
                    <option value="any" <?php selected($quick_start_condition['timing'], 'any'); ?>>Any</option>
                    <?php $options = get_post_meta($post->ID, 'timing_options', true); 
                    // print_r($options); die(); 
                    foreach($options as $option){ ?>
                        <option value="<?php echo $option; ?>" <?php selected($quick_start_condition['timing'], $option); ?>><?php echo $option; ?></option>
                    <?php } ?>
                </select>
                <select name="quick_start_setup[<?php echo $quick_start_index;?>][budget]">
                    <option value="">Budget</option>
                    <option value="any" <?php selected($quick_start_condition['budget'], 'any'); ?>>Any</option>
                    <?php $options = get_post_meta($post->ID, 'budget_options', true); 
                    // print_r($options); die(); 
                    foreach($options as $option){ ?>
                        <option value="<?php echo $option; ?>" <?php selected($quick_start_condition['budget'], $option); ?>><?php echo $option; ?></option>
                    <?php } ?>
                </select>
                ::
                <select name="quick_start_setup[<?php echo $quick_start_index;?>][service]">
                    <option value="">Service</option>
                    <?php 
                    $args = array(
                        'post_type'      => 'page',
                        'posts_per_page' => -1,
                        'post_parent'    => $_GET['post'],
                        'order'          => 'ASC',
                        'orderby'        => 'menu_order'
                    );
                    $parent = new WP_Query( $args );
                    while ( $parent->have_posts() ) : $parent->the_post();?>
                        <option value="<?php echo get_the_title(); ?>---<?php echo get_permalink(); ?>" <?php selected($quick_start_condition['service'], get_the_title() . '---' . get_permalink()); ?>><?php echo get_the_title(); ?></option>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </select>
                <input name="quick_start_setup[<?php echo $quick_start_index;?>][description]" placeholder="Description" value="<?php echo (isset($quick_start_condition['description']) ? $quick_start_condition['description'] : ""); ?>" size="30" type="text" style="position: relative;top: 2px;">
                <a href="#" class="rwmb-button remove-clone"><i class="dashicons dashicons-minus"></i></a>
            </div>
        <?php $quick_start_index++; 
        } ?>
        <a href="#" class="rwmb-button button-primary add-clone">+ Add more</a>
    </div>
    <script>
        $(document).ready(function () {
            $( ".quick-start" ).sortable({
                update: function( event, ui ) {
                    quickStartResetNames();
                }
            });
        });

        $('.quick-start').find('.add-clone').click(function(event){
            var numSections = $('.quick-start-section').length;
            var sectionHTML = $('.quick-start-section').first()[0].outerHTML;

            $sectionHTML = $(sectionHTML);
            $sectionHTML.find('select').each(function() {
                $(this).find('option').removeAttr("selected");
                var sectionName = $(this).attr('name');
                var currentNum = sectionName.split(']')[0].split('[')[1];
                sectionName = sectionName.replace(currentNum, numSections);
                // console.log(sectionName);
                $(this).attr('name', sectionName);
            });
            $('.quick-start-section').last().after($sectionHTML);
        });

        $('.quick-start').on('click', '.remove-clone', function(event){
            event.stopPropagation();
            event.preventDefault();

            var $removedSection = $(event.target).parents('.quick-start-section').remove();

            quickStartResetNames();

            console.log('remove', event);
        });

        function quickStartResetNames(){
            var curIndex = 0;
            $('.quick-start-section').each(function(){
                $section = $(this);

                $section.find('[name]').each(function() {
                    var sectionName = $(this).attr('name');
                    var currentNum = sectionName.split(']')[0].split('[')[1];
                    sectionName = sectionName.replace(currentNum, curIndex);
                    // console.log(sectionName);
                    $(this).attr('name', sectionName);
                });
                curIndex++;
            });
        }

    </script>
    <?php
}

function rede_dashboard_qs_mb_save($post_id){
    if (array_key_exists('quick_start_setup', $_POST)) {
        update_post_meta($post_id,'_quick_start_setup',$_POST['quick_start_setup']);
    }
}
add_action('save_post', 'rede_dashboard_qs_mb_save');


add_filter( 'rwmb_meta_boxes', 'prefix_meta_boxes' );
function prefix_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'  => 'Order Info',
        'post_types' => 'rede-order',
        'fields' => array(
            array(
                'name'            => 'Order Status',
                'id'              => 'order_status',
                'type'            => 'select',
                'options'         => array(
                    'Pending Confirmation'  => 'Pending Confirmation',
                    'Review Order'          => 'Review Order',
                    'Confirm Details'       => 'Confirm Details',
                    // 'Review Campaign'       => 'Review Campaign',
                    'Review Creative'       => 'Review Creative',
                    'Review Brief'          => 'Review Creative',
                    'Review Proof'          => 'Review Proof',
                    'Needs Creative'        => 'Needs Creative',
                    'Creative Added'        => 'Creative Added',
                    'Awaiting Brief'        => 'Awaiting Brief',
                    'Review Brief'          => 'Review Brief',
                    'Order Denied'          => 'Order Denied',
                    // 'Creative Approved'     => 'Creative Approved',
                    // 'Proof Denied'          => 'Proof Denied',
                    // 'Proof Approved'        => 'Proof Approved',
                    'Active'                => 'Active',
                    'Awaiting Report'       => 'Awaiting Report',
                    'Completed'             => 'Completed',
                ),
                'multiple'        => false,
                'select_all_none' => false,
            ),
            array(
                'name'            => 'Order Type',
                'id'              => 'type',
                'type'            => 'select',
                'options'         => array(
                    'Mobile Media'          => 'Mobile Media',
                    'Paid Social'           => 'Paid Social',
                    'On-Pack'               => 'On-Pack',
                    'Out of Home'           => 'Out of Home',
                    'Security Shroud'       => 'Security Shroud',
                    'Sampling'              => 'Sampling',
                    'Coupon Booster'        => 'Coupon Booster'
                ),
                'multiple'        => false,
                'placeholder'     => 'Select a tactic',
                'select_all_none' => false,
            ),
            array(
                'name' => 'Spastic',
                'id'   => 'spastic',
                'type' => 'text',
            ),
            array(
                'name' => 'Brand',
                'id'   => 'brand',
                'type' => 'text',
            ),
            array(
                'name' => 'Quantity',
                'id'   => 'quantity',
                'type' => 'text',
            ),
            array(
                'name' => 'Budget',
                'id'   => 'budget',
                'type' => 'text',
            ),
            array(
                'name'       => 'Market Date',
                'id'         => 'marketdate',
                'type'       => 'date',
                'js_options' => array(
                    'dateFormat'      => 'mm/dd/yy',
                    'showButtonPanel' => false,
                ),
                'inline' => false,
                'timestamp' => false,
            ),
            array(
                'name'       => 'End Date',
                'id'         => 'enddate',
                'type'       => 'date',
                'js_options' => array(
                    'dateFormat'      => 'mm/dd/yy',
                    'showButtonPanel' => false,
                ),
                'inline' => false,
                'timestamp' => false,
            ),
            array(
                'name' => 'Store Count',
                'id'   => 'storecount',
                'type' => 'text',
            ),
            array(
                'name' => 'Store Names',
                'id'   => 'stores',
                'type' => 'text',
            ),
            array(
                'name' => 'Total',
                'id'   => 'total',
                'type' => 'text',
            ),
            array(
                'name' => 'Cost Per Store',
                'id'   => 'costperstore',
                'type' => 'text',
            ),
            array(
                'name' => 'Demographics',
                'id'   => 'demographics',
                'type' => 'text',
            ),
            array(
                'name' => 'Profile Gender',
                'id'   => 'profilegender',
                'type' => 'text',
            ),
            array(
                'name' => 'Profile Age',
                'id'   => 'profileage',
                'type' => 'text',
            ),
            array(
                'name' => 'Profile Children',
                'id'   => 'profilechildren',
                'type' => 'text',
            ),
            array(
                'name' => 'Profile Income',
                'id'   => 'profileincome',
                'type' => 'text',
            ),
            array(
                'name' => 'Destination URL',
                'id'   => 'destinationurl',
                'type' => 'text',
            ),
            array(
                'name' => 'Optimization',
                'id'   => 'optimization',
                'type' => 'text',
            ),
            array(
                'name' => 'DMA',
                'id'   => 'dma',
                'type' => 'text',
            ),
            array(
                'name' => 'Upgrade 1',
                'id'   => 'upgrade-1',
                'type' => 'text',
            ),
            array(
                'name' => 'Upgrade 2',
                'id'   => 'upgrade-2',
                'type' => 'text',
            ),
            array(
                'name' => 'Upgrade 2 Red/E Develop',
                'id'   => 'upgrade2-custom',
                'type' => 'text',
            ),
            array(
                'name' => 'Upgrade 2 File',
                'id'   => 'filenameupgrade2',
                'type' => 'file_input',
            ),
            array(
                'name' => 'Upgrade 3',
                'id'   => 'upgrade-3',
                'type' => 'text',
            ),
            array(
                'name' => 'Upgrade 3 Red/E Develop',
                'id'   => 'upgrade3-custom',
                'type' => 'text',
            ),
            array(
                'name' => 'Upgrade 3 File',
                'id'   => 'filenameupgrade3',
                'type' => 'file_input',
            ),
            array(
                'name' => 'Campaign Objective',
                'id'   => 'campaignobjective',
                'type' => 'text',
                'size' => 80,
            ),
            array(
                'name' => 'Campaign Timing',
                'id'   => 'campaigntiming',
                'type' => 'text',
                'size' => 80,
            ),
            array(
                'name' => 'Campaign Purpose',
                'id'   => 'campaignpurpose',
                'type' => 'text',
                'size' => 80,
            ),
            array(
                'name' => 'Other Details',
                'id'   => 'otherdetails',
                'type' => 'textarea',
            ),
            array(
                'name' => 'Other Considerations',
                'id'   => 'otherconsiderations',
                'type' => 'textarea',
            ),
            array(
                'name' => 'Comments',
                'id'   => 'comments',
                'type' => 'textarea',
            ),
            array(
                'name' => 'Vendor Comments',
                'id'   => 'vendor_comments',
                'type' => 'textarea',
            ),
            // array(
            //     'id'      => 'vendor_comments',
            //     'name'    => 'Vendor Comments',
            //     'type'    => 'text_textarea',

            //     // Options: array of key => Label for text boxes
            //     // Note: key is used as key of array of values stored in the database
            //     'options' => array(
            //         'status'    => 'Status',
            //         'comment' => 'Comment',
            //     ),

            //     // Is field cloneable?
            //     'clone' => true,
            // ),
            // array(
            //     'id'       => 'posts',
            //     'type'     => 'custom_html',
            //     'callback' => 'rede_get_pageflex',
            // ),
            array(
                'name' => 'Pageflex ID',
                'id'   => 'pfid',
                'type' => 'text',
            ),
            array(
                'name' => 'Creative',
                'id'   => 'filename',
                'type' => 'file_input',
            ),
            array(
                'name' => 'Red/E Creative',
                'id'   => 'tactic-custom',
                'type' => 'checkbox',
            ),
            array(
                'name' => 'Red/E Creative',
                'id'   => 'rede_creative',
                'type' => 'file_input',
            ),
            // array(
            //     'name'  => 'Proofs',
            //     'id'    => 'proofs',
            //     'type'  => 'file_input',
            // ),
            array(
                'name'  => 'Vendor Creative',
                'id'    => 'vendor_creative',
                'type'  => 'file_input',
            ),

            array(
                'name'  => 'Vendor Brief',
                'id'    => 'vendor_briefs',
                'type'  => 'file_input',
            ),
            array(
                'name'  => 'User Brief',
                'id'    => 'user_briefs',
                'type'  => 'file_input',
            ),
            array(
                'name' => 'Product List',
                'id'   => 'filenamesku',
                'type' => 'file_input',
            ),
            array(
                'name' => 'Custom Store List Name',
                'id'   => 'customlistname',
                'type' => 'file_input',
            ),
            array(
                'name' => 'Custom Segment',
                'id'   => 'filenameseg',
                'type' => 'file_input',
            ),
            array(
                'name'  => 'Reports',
                'id'    => 'vendor_reports',
                'type'  => 'file_input'
            ),

            array(
                'type' => 'heading',
                'name' => 'Vendor Information',
            ),
            array(
                'name'        => 'Select an owner',
                'id'          => '_vendor',
                'type'        => 'user',
                'field_type'  => 'select_advanced',
                'query_args'  => array(
                    'role' => 'rede_vendor'
                ),
            ),

            array(
                'type' => 'heading',
                'name' => 'Debug',
            ),
            array(
                'id'       => 'posts',
                'type'     => 'custom_html',
                'callback' => 'rede_display_all_meta',
            ),
        ),
    );

    $meta_boxes[] = array(
        'title'  => 'Order Info',
        'post_types' => 'page',
        'fields' => array(
            array(
                'name' => 'Registered Users Only',
                'id'   => 'registered_only',
                'type' => 'checkbox',
                'std'  => 0, // 0 or 1
            ),
            array(
                'name'        => 'Select an owner',
                'id'          => 'tactic_owner',
                'type'        => 'user',
                'field_type'  => 'select_advanced',
                'query_args'  => array(
                    'role' => 'rede_vendor'
                ),
            ),
            array(
                'name'        => 'Image Header Text',
                'id'          => '_head_text',
                'type'        => 'textarea',
            ),
        ),
    );

    $post_id = "";
    if(isset($_GET['post']) && !empty($_GET['post'])){
        $post_id = $_GET['post'];
    }
    if(isset($_POST['post_ID']) && !empty($_POST['post_ID'])){
        $post_id = $_POST['post_ID'];
    }
    if(!empty( $post_id )){
        $form_template = get_post_meta($post_id, '_wp_page_template', true);
        $form_template = basename( $form_template );
        // print_r($form_template);
        // die();
        if($form_template === 'dashboard.php'){
            $meta_boxes[] = array(
                'title'  => 'Quick Start',
                'post_types' => 'page',
                'fields' => array(
                    array(
                        'id'      => 'quickstart_overview',
                        'name'    => 'Quickstart Overview',
                        'type'    => 'textarea'
                    ),
                    array(
                        'id'      => 'objective_options',
                        'name'    => 'Objective Options',
                        'type'    => 'text',
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'id'      => 'timing_options',
                        'name'    => 'Timing Options',
                        'type'    => 'text',
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'id'      => 'budget_options',
                        'name'    => 'Budget Options',
                        'type'    => 'text',
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                ),
            );
        }
        if($form_template === 'service-on-pack.php'){
            $meta_boxes[] = array(
                'title'  => 'Default Pricing',
                'post_types' => 'page',
                'fields' => array(
                    array(
                        'id'      => 'tactic_pricing',
                        'name'    => 'Tactic Prices',
                        'type'    => 'fieldset_text',
                        'options' => array(
                            'tactic'    => 'Tactic',
                            'min'       => 'Min',
                            'max'       => 'Max',
                            'price'     => 'Price'
                        ),
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'name' => 'Merch',
                        'id'   => 'pricing_merch',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Fullfillment',
                        'id'   => 'pricing_fullfillment',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Shipping',
                        'id'   => 'pricing_shipping',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Markup',
                        'id'   => 'pricing_markup',
                        'type' => 'text',
                        'desc' => 'Enter markup as fraction (example: 1.1)',
                    ),
                    array(
                        'name' => 'Tactic Cost',
                        'id'   => 'pricing_tacticcost',
                        'type' => 'text',
                    ),
                ),
            );
        }
        if($form_template === 'service-sas-at-shelf.php'){
            $meta_boxes[] = array(
                'title'  => 'Default Pricing',
                'post_types' => 'page',
                'fields' => array(
                    array(
                        'id'      => 'tactic_list',
                        'name'    => 'Tactic List',
                        'type'    => 'text',
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'id'      => 'tactic_pricing',
                        'name'    => 'Tactic Prices',
                        'type'    => 'fieldset_text',
                        'options' => array(
                            'tactic'    => 'Tactic',
                            'min'       => 'Min',
                            'max'       => 'Max',
                            'price'     => 'Price'
                        ),
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'name' => 'At Shelf First',
                        'id'   => 'sas_shelf_first',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Additional',
                        'id'   => 'sas_shelf_add',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Shipping',
                        'id'   => 'sas_shelf_shipping',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Kitting',
                        'id'   => 'sas_shelf_kitting',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Combo First',
                        'id'   => 'sas_combo_first',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Combo Additional',
                        'id'   => 'sas_combo_add',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Combo Additional Blade',
                        'id'   => 'sas_combo_add_blade',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Combo Shipping',
                        'id'   => 'sas_combo_shipping',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'At Shelf Combo Kitting',
                        'id'   => 'sas_combo_kitting',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Markup',
                        'id'   => 'pricing_markup',
                        'type' => 'text',
                        'desc' => 'Enter markup as fraction (example: 1.1)',
                    ),
                ),
            );
        }
        if($form_template === 'service-pos-materials.php'){
            $meta_boxes[] = array(
                'title'  => 'Default Pricing',
                'post_types' => 'page',
                'fields' => array(
                    array(
                        'id'      => 'tactic_pricing',
                        'name'    => 'Tactic Prices',
                        'type'    => 'fieldset_text',
                        'options' => array(
                            'tactic'    => 'Tactic',
                            'min'       => 'Min',
                            'max'       => 'Max',
                            'price'     => 'Price'
                        ),
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'id'      => 'variable_shipping',
                        'name'    => 'Variable Shipping',
                        'type'    => 'fieldset_text',
                        'options' => array(
                            'min'       => 'Min',
                            'max'       => 'Max',
                            'price'     => 'Price'
                        ),
                        'clone'      => true,
                        'sort_clone' => true
                    ),
                    array(
                        'name' => 'Merch',
                        'id'   => 'pricing_merch',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Fullfillment',
                        'id'   => 'pricing_fullfillment',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Default Shipping',
                        'id'   => 'pricing_shipping',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Markup',
                        'id'   => 'pricing_markup',
                        'type' => 'text',
                        'desc' => 'Enter markup as fraction (example: 1.1)',
                    ),
                    array(
                        'name' => 'Default Tactic Cost',
                        'id'   => 'pricing_tacticcost',
                        'type' => 'text',
                    ),
                ),
            );
        }
        if($form_template === 'service-shroud.php'){
            $meta_boxes[] = array(
                'title'  => 'Default Pricing',
                'post_types' => 'page',
                'fields' => array(
                    array(
                        'name' => 'Base',
                        'id'   => 'pricing_base',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Upgrade 1',
                        'id'   => 'pricing_upgrade1Amount',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Upgrade 2',
                        'id'   => 'pricing_upgrade2Amount',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Upgrade 3',
                        'id'   => 'pricing_upgrade3Amount',
                        'type' => 'text',
                    ),
                ),
            );
        }
    }
     $meta_boxes[] = array(
        'title'  => 'Order Info',
        'type' => 'comment',
        'fields' => array(
            array(
                'name' => 'Order Status',
                'id'   => 'order_status',
                'type' => 'custom_html',
                'callback' => 'rede_get_field_comment_status'
            ),
        ),
    );

    return $meta_boxes;
}

function rede_get_field_comment_status(){
    if ( ! $comment_id = filter_input( INPUT_GET, 'c', FILTER_SANITIZE_NUMBER_INT ) ) {
        return '';
    }
    $comment = get_comment( $comment_id ); 
    $comment_post_id = $comment->comment_post_ID ;

    $order_status = get_post_meta($comment_post_id,'order_status', true);

    // echo $order_status;
    // die();


    $output = '';
    // $output .= '<div class="rwmb-label">';
    // $output .= '<label for="registered_only">Order Status</label>';
    // $output .= '</div><div class="rwmb-input">';
    $output .= '<strong>'.$order_status.'</strong>';
    // $output .= '</div>';
    return $output;
}

function rede_display_all_meta(){
    if ( ! $order_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT ) ) {
        return '';
    }
    $order_data = get_all_meta($order_id);

    $output = '';
    // $output .= '<div class="rwmb-label">';
    // $output .= '<label for="registered_only">Order Status</label>';
    // $output .= '</div><div class="rwmb-input">';
    foreach($order_data as $key => $var){
        $output .= '<strong>'.$key.'</strong>: ' . $var . '<br>';
    }
    // $output .= '</div>';
    return $output;
}

function rede_get_pageflex() {
    if ( ! $order_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT ) ) {
        return '';
    }

    $pfid = get_post_meta($order_id, 'pfid', true);

    $output = '<img id="pf-preview" class="border" src="http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetBitMap.aspx?DocID='.$pfid.'&UserName=dalim20151" />';


    return $output;
}

add_action( 'init', 'rede_load_text_textarea_type' );

function rede_load_text_textarea_type() {

    if ( class_exists( 'RWMB_Text_Field' ) ) {
        class RWMB_Text_Textarea_Field extends RWMB_Text_Field {
            /**
             * Get field HTML.
             *
             * @param mixed $meta  Meta value.
             * @param array $field Field parameters.
             *
             * @return string
             */
            public static function html( $meta, $field ) {
                $html = array();
                $tpl  = '<label>%s %s</label>';

                $first_field = true;
                foreach ( $field['options'] as $key => $label ) {
                    $value                       = isset( $meta[ $key ] ) ? $meta[ $key ] : '';
                    $field['attributes']['name'] = $field['field_name'] . "[{$key}]";
                    $attributes = self::call( 'get_attributes', $field, $meta );
                    if($first_field){
                        $inputHtml = sprintf( '<input %s>%s', self::render_attributes( $attributes ), self::datalist( $field ) );
                        $first_field = false;
                    } else {
                        $field['attributes']['cols'] = "500";
                        $field['attributes']['rows'] = "5";
                        $inputHtml = sprintf( '<textarea %s>%s</textarea>', self::render_attributes( $attributes ), self::datalist( $field ) );
                    }
                    $html[] = sprintf( $tpl, $label, $inputHtml );
                }



                $out = '<fieldset><legend>' . $field['desc'] . '</legend>' . implode( ' ', $html ) . '</fieldset>';

                return $out;
            }

            /**
             * Do not show field description.
             *
             * @param array $field Field parameters.
             *
             * @return string
             */
            public static function input_description( $field ) {
                return '';
            }

            /**
             * Do not show field description.
             *
             * @param array $field Field parameters.
             *
             * @return string
             */
            public static function label_description( $field ) {
                return '';
            }

            /**
             * Normalize parameters for field.
             *
             * @param array $field Field parameters.
             *
             * @return array
             */
            public static function normalize( $field ) {
                $field                       = parent::normalize( $field );
                $field['multiple']           = false;
                $field['attributes']['id']   = false;
                $field['attributes']['type'] = 'text';
                return $field;
            }

            /**
             * Format value for the helper functions.
             *
             * @param array        $field   Field parameters.
             * @param string|array $value   The field meta value.
             * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
             * @param int|null     $post_id Post ID. null for current post. Optional.
             *
             * @return string
             */
            public static function format_value( $field, $value, $args, $post_id ) {
                $output = '<table><thead><tr>';
                foreach ( $field['options'] as $label ) {
                    $output .= "<th>$label</th>";
                }
                $output .= '</tr></thead></tbody>';

                if ( ! $field['clone'] ) {
                    $output .= self::format_single_value( $field, $value, $args, $post_id );
                } else {
                    foreach ( $value as $subvalue ) {
                        $output .= self::format_single_value( $field, $subvalue, $args, $post_id );
                    }
                }
                $output .= '</tbody></table>';
                return $output;
            }

            /**
             * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
             *
             * @param array    $field   Field parameters.
             * @param array    $value   The value.
             * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
             * @param int|null $post_id Post ID. null for current post. Optional.
             *
             * @return string
             */
            public static function format_single_value( $field, $value, $args, $post_id ) {
                $output = '<tr>';
                foreach ( $value as $subvalue ) {
                    $output .= "<td>$subvalue</td>";
                }
                $output .= '</tr>';
                return $output;
            }
        }
    }
}
