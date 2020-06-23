<?php
add_action( 'rwmb_meta_boxes', function( $meta_boxes ) {

    $all_brands = get_all_brands();
    // print_r($all_brands);
    // die();

    $tactics_array = array();
    $my_wp_query = new WP_Query();
    $all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'posts_per_page' => '-1'));
    $dashboard =  get_page_by_path('dashboard');
    $portfolio_children = get_page_children( $dashboard->ID, $all_wp_pages );
    foreach($portfolio_children as $tactic_object){
        $tactics_array[$tactic_object->ID] = $tactic_object->post_title;
    }

            // die();

    $meta_boxes[] = array(
        'title' => 'Account Info',
        'type'  => 'user', // Specifically for user

        'fields' => array(
             array(
                'name'        => 'Shipping Addresses',
                'id'          => 'shippingaddress',
                'type'        => 'textarea',
                'clone'       => true,
                'placeholder' => "Red/E\nc/o Advantage Sales & Marketing LLC\n18100 Von Karman Ave., Suite 1000, Irvine, CA 92612"
            ),
            array(
                'name'        => 'Pageflex',
                'id'          => 'pageflexOn',
                'type'        => 'checkbox',
            ),
            array(
                'name'        => 'Brands',
                'id'          => 'brands',
                'type'        => 'text',
                'clone'       => true,
                'size'        => 30,
                'datalist'    => array(
                    'id'      => 'brands_datalist',
                    'options' => $all_brands,
                ),
            ),
            array(
                'name' => 'Budget Cap',
                'id'   => 'budget_cap',
                'type' => 'text',
            ),
            array(
                'name'    => 'Disable Tactics',
                'id'      => 'disabled_tactics',
                'type'    => 'checkbox_list',
                // Options of checkboxes, in format 'value' => 'Label'
                'options' => $tactics_array,
                // Display options in a single row?
                // 'inline' => true,
                // Display "Select All / None" button?
                'select_all_none' => true,
            ),
        ),
    );

    $meta_boxes[] = array(
        'title'  => 'Custom On Pack Pricing',
        'type' => 'user',
        'fields' => array(
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
            ),
            array(
                'name' => 'Tactic Cost',
                'id'   => 'pricing_tacticcost',
                'type' => 'text',
            ),
        ),
    );

    $meta_boxes[] = array(
        'title'  => 'Custom Shroud Pricing',
        'type' => 'user',
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

    $meta_boxes[] = array(
        'title'  => 'Custom At Shelf Pricing',
        'type' => 'user',
        'fields' => array(
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
        ),
    );


    return $meta_boxes;
} );

function user_active_campaigns(){
    global $current_user;
    wp_get_current_user();
    if(user_check_role('rede_vendor')){
        $author_query = array(
            'post_type' => 'rede-order',
            'posts_per_page' => '-1',
            'meta_query' => array(
                'user_clause' => array(
                    'key'     => '_vendor',
                    'value'   => $current_user->ID,
                    'compare' => '=',
                ),
                'status_clause' => array(
                    'key'     => 'order_status',
                    'value'   => 'Active',
                    'compare' => '=',
                )
            )
        );
    } else {
        $author_query = array(
            'post_type' => 'rede-order',
            'posts_per_page' => '-1',
            'meta_query' => array(
                'user_clause' => array(
                    'key'     => '_user',
                    'value'   => $current_user->ID,
                    'compare' => '=',
                ),
                'status_clause' => array(
                    'key'     => 'order_status',
                    'value'   => 'Active',
                    'compare' => '=',
                )
            )
        );
    }
    // print_r($author_query);
    // die();
    $total = 0;
    $author_posts = new WP_Query($author_query);
    while($author_posts->have_posts()){
        $author_posts->the_post();
        $postid = $author_posts->post->ID;
        $order_status = get_post_meta($postid, 'order_status', true);

        if(!in_array($order_status, array("Active", "Awaiting Report", "Completed"))){
			continue;
		}

        $total++;
    }

    return $total;
}