<?php

/*
Plugin Name: Advantage Core Plugin
Description: Custom functionality for marketing approval workflow
Version:     1.0
Author:      Anne Schmidt
Author URI:  https://anneschmidt.co
*/


//getting rid of this and switching to ACF options page
//require_once('gravity-addons/simpleaddon.php');

/* enqueue styles and scripts */
function adv_plugin_enqueue() {
    wp_enqueue_style( 'entry-styles', plugin_dir_url( __FILE__ ) . 'css/advantage-core.css' );
    wp_enqueue_script( 'approval-form', plugin_dir_url( __FILE__ ) . 'js/advantage-core.js', array('jquery'), '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'adv_plugin_enqueue' );

//add custom options section to wp-admin
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Advantage Settings',
		'menu_title'	=> 'Advantage Settings',
		'menu_slug' 	=> 'advantage-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Form Assignments',
		'menu_title'	=> 'Form Assignments',
		'parent_slug'	=> 'advantage-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'On Pack Form Settings',
		'menu_title'	=> 'On Pack',
		'parent_slug'	=> 'advantage-settings',
	));
	
}

//add acf options page to select forms and form fields
/**
 * Populate ACF select field options with Gravity Forms forms
 */
function acf_populate_gf_forms_ids( $field ) {
	if ( class_exists( 'GFFormsModel' ) ) {
		$choices = [];

		foreach ( GFFormsModel::get_forms() as $form ) {
			$choices[ $form->id ] = $form->title;
		}

		$field['choices'] = $choices;
	}

	return $field;
}
add_filter( 'acf/load_field/name=onpack_order_form', 'acf_populate_gf_forms_ids' );

//On Pack AJAX price calculations
function onpack_ajax_enqueue() {

	// Enqueue javascript on the frontend.
	wp_enqueue_script(
		'onpack-ajax-script',
		plugin_dir_url( __FILE__ ) . 'js/onpack.js',
		array('jquery')
	);

	// The wp_localize_script allows us to output the ajax_url path for our script to use.
	wp_localize_script(
		'onpack-ajax-script',
		'onpack_ajax_obj',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('ajax-nonce')
		)
	);

}
add_action( 'wp_enqueue_scripts', 'onpack_ajax_enqueue' );

function onpack_ajax_request() {
 
 //do we need a nonce?
 
 //get data on pricing from ACF fields
$merch = get_field('merch','option');
$fulfillment = get_field('fulfillment','option');
$shipping = get_field('shipping','option');
$markup = get_field('markup','option');
$tactic_cost = get_field('tactic_cost','option');




/*TO DO ON FORM ON DEV SITE
add budget_field class
add tactic_field class
*/

    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
     
        $price = $_REQUEST['price'];
        $qty_per_store = $_REQUEST['qtyPerStore'];
        $selected_tactic = $_REQUEST['selectedTactic'];
        
        //sample output for ajax
		$total = 0;
		$store_count = 640; //GET #STORES FROM WILLIAM'S FIELD
		$qty = $qty_per_store * $store_count; //number per store * number of stores 
		
		/* get tactic cost */
		if( have_rows('tactic_prices','option') ):
		
			while( have_rows('tactic_prices','option') ): the_row(); 
			
				$tactic = get_sub_field('tactic');
				$min = get_sub_field('min');
				$max = get_sub_field('max');
				
				if ($tactic == $selected_tactic) {
					if ($qty > $min && $qty < $max) {
						$tactic_cost = get_sub_field('price');
					}
				}			
				
			endwhile;
		
		endif;
        
        $total_cost = ( ($merch * $store_count) + ($fulfillment * $store_count) + ($shipping * $store_count) + ($tactic_cost * $qty) ) * $markup; 
        
        $data['tactic_cost'] = $tactic_cost;
        $data['total_qty'] = $qty;
        $data['price'] = $total_cost;
        $data['per_store'] = $total_cost / $store_count;
        header('Content-Type: application/json');
        $data = json_encode($data);
        echo $data;
         
        // If you're debugging, it might be useful to see what was sent in the $_REQUEST
        // print_r($_REQUEST);
     
      die();
    }
 }
add_action( 'wp_ajax_onpack_ajax_request', 'onpack_ajax_request' );
add_action( 'wp_ajax_nopriv_onpack_ajax_request', 'onpack_ajax_request' );

