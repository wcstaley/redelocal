<?php
// Adjust your form ID
add_filter( 'gform_form_post_get_meta_6', 'add_offer_repeater_field' );
function add_offer_repeater_field( $form ) {
 
    $repeater = GF_Fields::create( array(
        'type'       => 'repeater',
        'id'         => "3421",
        'formId'     => $form['id'],
        'label'      => 'Offer Repeater',
        'pageNumber' => 1, // Ensure this is correct
    ) );
 
    $another_form = GFAPI::get_form( 17 );
    foreach ( $another_form['fields'] as $field ) {
        $field->id         = $field->id + 3421;
        $field->formId     = $form['id'];
        $field->pageNumber = 1; // Ensure this is correct
 
            if ( is_array( $field->inputs ) ) {
            foreach ( $field->inputs as &$input ) {
                $input['id'] = (string) ( $input['id'] + 3421 );
            }
        }  
    }
 
	$repeater_exists = false;
 	foreach ( $form['fields'] as $field ) {
 		if ( 'repeater' === $field->type && $field->id === 3421 ) {
 			$repeater_exists = true;
 		}
 	}
 	if ( ! $repeater_exists ) {
 	    $repeater->fields = $another_form['fields'];
 	    $form['fields'][] = $repeater;
 	}
 
    return $form;
}
// This is unnecessary and breaks things, but leaving it here because GF says to use it, so....
// Remove the field before the form is saved. Adjust your form ID
// add_filter( 'gform_form_update_meta_6', 'remove_offer_repeater_field', 10, 3 );
// function remove_offer_repeater_field( $form_meta, $form_id, $meta_name ) {
//     if ( $meta_name == 'display_meta' ) {
//         // Remove the Repeater field: ID 3421
//         $form_meta['fields'] = wp_list_filter( $form_meta['fields'], array( 'id' => 3421 ), 'NOT' );
//     }
//     return $form_meta;
// }