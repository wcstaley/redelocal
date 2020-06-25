<?php

/*
Plugin Name: Rede Custom
Description: Custom functionality for marketing approval workflow
Version:     1.0
Author:      Anne Schmidt
Author URI:  https://anneschmidt.co
*/

require_once('rede-custom-fields.php');
require_once('coop-gf_field.php');
require_once('previous-entries_gf_field.php');
require_once('cron.php');
require_once('gravity-addons/calendar-addon.php');
require_once('theme_lightbox_shortcode.php');


register_activation_hook(__FILE__, 'publix_custom_plugin_activation');
function publix_custom_plugin_activation() {
    if(!wp_next_scheduled('check_coop_calendar_notices')){
        wp_schedule_event(time(), 'daily', 'check_coop_calendar_notices');
    }
}

register_deactivation_hook(__FILE__, 'publix_custom_plugin_deactivation');
function publix_custom_plugin_deactivation(){
    wp_clear_scheduled_hook('publix_custom_plugin_deactivation');
}

/* enqueue styles and scripts */
function publix_plugin_enqueue() {
    wp_enqueue_style( 'entry-styles', plugin_dir_url( __FILE__ ) . 'entry-styling.css' );
    wp_enqueue_style( 'form-styles', plugin_dir_url( __FILE__ ) . 'form-styling.css' );
    wp_enqueue_script( 'approval-form', plugin_dir_url( __FILE__ ) . 'approval-form.js', array('jquery'), '1.0.0', false );
	
	wp_enqueue_style('datatables-style', '//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css');
	wp_enqueue_script('datatables', '//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js', array('jquery'));
	
}
add_action( 'wp_enqueue_scripts', 'publix_plugin_enqueue', 22 );

//function to display entry data
function publix_display_gf_entry_data( $gf_entry_id ) {
	
	$entry = GFAPI::get_entry( $gf_entry_id );
	$form = GFAPI::get_form( $entry['form_id'] );
	
	if(is_wp_error($entry)){
		//return;
	}
	
	$output ='';
	
	$output .= '<div class="order-overview-table" style="display:block;">';
	
		foreach($form['fields'] as $field){
			
			//echo $field->type;
			
			if ($field->type == 'section') {
				$output .= '<div style="font-weight: bold;font-size: 1.2em;margin-bottom:5px;padding-top: 30px;clear:both;" class="section-header">'.$field->label.'</div>';
			} else if($field->type == 'repeater'){
				$output .= '<div class="order-entry full-width bottom-margin repeater-entry" style="display: block;">';
					$output .= '<div class="order-label full-width" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $field->label . '</div>';
					$subfield = $entry[$field['id']];
		
					foreach($subfield as $subvalue){
						$output .= '<div class="sub-field-entry">';
						foreach($field->fields as $fieldfield){
						
							$output .= '<div class="order-entry">';
								$output .= '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">'. $fieldfield->label . '</div>';
								$output .= '<div class="order-value" style="float:left;>' . $subvalue[$fieldfield->id] . '</div>';
							$output .= '</div>';
						}
						$output .= '</div>';
					}
				$output .= '</div>';
			} else if ($field->type == 'fileupload') {
				$output .= '<div class="order-entry">';
					$output .= '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">'. $field->label.'</div>';
					$output .='<a style="float:left;" href="'.$entry[$field['id']].'" class="order-value" target="_blank">Download File</a>';
				$output .= '</div>';
				
			} else if ($field->type == 'address') {
				$output .= '<div class="order-entry full-width">';
					$output .= '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $field->label . '</div>';
					$output .='<div class="sub-field-entry">';
						foreach( $field['inputs'] as $sub_field) {
							$output .= '<div class="order-entry">';
								$output .= '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $sub_field['label'] . '</div>';
								$output .= '<div class="order-value">'.$entry[$sub_field['id']].'</div>';
							$output .= '</div>';
						}
						$output .= '<div class="order-value"></div>';
					$output .= '</div>';
				$output .= '</div>';
			} else if ($field->type == 'checkbox') {
				
				$output .= '<div class="order-entry">';
					$output .= '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $field->label . '</div>';
					$output .= '<div class="sub-field-entry"><ul>';
						foreach( $field['inputs'] as $sub_field) {
								$selected = $entry[$sub_field['id']];
								
								if ($selected) {
									$output .= '<li class="order-entry"><div class="order-value">'.$entry[$sub_field['id']].'</div></li>';
								}
						}
					$output .= '</ul></div>';
				$output .= '</div>';		
			}  else if ($field->type == 'buyers_multiselect') {
				$output .= '<div class="order-entry">';
					$output .= '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $field->label . '</div>';
					//echo $entry[$field['id']];
					$str_arr = explode (",", $entry[$field['id']]);  
					$output .= '<div class="sub-field-entry" style="clear:both;"><ul>';
						foreach( $str_arr as $sub_field) {
								$selected = $sub_field;
								
								$user_info = get_userdata($selected);
								$user_name = $user_info->user_firstname . " " . $user_info->user_lastname;
								//print_r($user_info);
								
								if ($selected) {
									$output .= '<li class="order-entry"><div class="order-value">'.$user_name.'</div></li>';
								}
						}
					$output .= '</ul></div>';
				$output .= '</div>';		
			} else {
				$output .= '<div class="order-entry"><div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $field->label . '</div><div class="order-value" style="float:left;">' . $entry[$field['id']] . '</div></div>';
			}
			
		}
		
	$output .= '</div>'; //end overview table
	
	return $output;

}

//display shortcode
// example URL: http://localhost:10050/entry-test/?entry=29
add_shortcode( 'gf_entry_data', 'gf_entry_data_shortcode' );
function gf_entry_data_shortcode( $atts, $content ) {
	$atts = shortcode_atts( array(

	), $atts, 'gf_entry_data' );
	
	if(!isset($_GET['entry'])){
		return $content;
	}
	$entry_id = $_GET['entry'];
	$entry = GFAPI::get_entry( $entry_id );
	if(is_wp_error($entry)){
		return $content;
	}
	
	
	$user = wp_get_current_user();
	if(!$user){
		return $content;
	}
	
	$form = GFAPI::get_form( $entry['form_id'] );
	if(!$form){
		return $content;
	}
	
	$entry = GFAPI::get_entry( $entry_id );
	
	//MAKE DYNAMIC
	$status = $entry[2354];
	
	//echo get_field('main_order_form','option');
	
	echo "<div class='order-entry-title'> Order #: " . $entry_id . " [Status: " .$status . "]</div>";
	
	if ($status == 'Pending')  {
	
		echo '<div class="approval-buttons">';
			echo '<div class="vendor-actions">';
				echo '<div class="button" id="approve-order">Approve</div>';
				//the below form is used for back-end functionality only. It is triggered by a jQuery click and is hidden to the user.
				//MAKE DYNAMIC
				echo '<div class="gf-approve-form">' . do_shortcode('[gravityform id="10" title="false" description="false" ajax="false"]') . '</div>';
				echo '<div class="button" id="conditionally-approve">Deny</div>';
			echo '</div>';
			echo '<div class="buyer-actions">';
				echo '<div class="button buyer" id="soft-approve-order">Buyer Approve</div>';
				echo '<div class="button" id="soft-deny">Buyer Deny</div>';
			echo '</div>';
			//MAKE DYNAMIC
			echo '<div class="popup-overlay conditionally-approve"><div class="popup-content conditionally-approve">'.do_shortcode('[gravityform id="8" title="false" description="false" ajax="true"]').'</div></div>';
			//MAKE DYNAMIC
			echo '<div class="popup-overlay soft-approve"><div class="popup-content soft-approve">'.do_shortcode('[gravityform id="14" title="false" description="false" ajax="true"]').'</div></div>';
			//MAKE DYNAMIC
			echo '<div class="popup-overlay soft-deny"><div class="popup-content soft-deny">'.do_shortcode('[gravityform id="15" title="false" description="false" ajax="true"]').'</div></div>';
			//echo '<div class="button" id="request-form-resubmit">Request Form Resubmit</div>';
			//echo '<div class="popup-overlay reject"><div class="popup-content reject">'.do_shortcode('[gravityform id="9" title="false" description="false" ajax="true"]').'</div></div>';
		echo '</div>';
	}
	
	/* Order Overview */
	
	//$first_name = $entry[2];
	//$last_name = $entry[3];
	
	//echo '<div><div class="entry-label">Name</div>'. $first_name .' ' . $last_name .'</div>';
	
	/*echo '<pre>';
	print_r($entry);
	echo '</pre>'; */

	ob_start();
	
	//display upload invoice field if user is a vendor/admin AND status is approved AND invoice has not already been uploaded
	
	//MAKE DYNAMIC
	$final_invoice = $entry[2362];
	
	$user = wp_get_current_user();
	$allowed_roles = array('administrator','rede_vendor');
	if( array_intersect($allowed_roles, $user->roles) && ($status == 'Approved') && (!$final_invoice) ) { 
		//MAKE DYNAMIC
		echo do_shortcode('[gravityform id="11" title="false" description="false" ajax="false"]') . '<hr>';
	}
	
	echo '<div class="order-overview-table '.strtolower($status).'">';
	
	
		$form_id = $form['id'];
		foreach($form['fields'] as $field){
			
			//echo $field->type . ",";
			
			if ($field->type == 'section') {
				echo '<div class="section-header">'.$field->label.'</div>';
				if ($field->label == 'Offers') {
					/*** product fields ***/
						$products = GFCommon::get_product_fields( $form, $entry);

				if ( ! empty( $products['products'] ) ) {
				    //ob_start();
					?>
				
					<tr>
						<td colspan="2" class="entry-view-field-value lastrow">
							<table class="entry-products" cellspacing="0" width="97%">
								<colgroup>
									<col class="entry-products-col1" />
									<col class="entry-products-col2" />
									<col class="entry-products-col3" />
									<col class="entry-products-col4" />
								</colgroup>
								<thead>
								<th scope="col"><?php echo gf_apply_filters( array( 'gform_product', $form_id ), __( 'Product', 'gravityforms' ), $form_id ); ?></th>
								<th scope="col" class="textcenter"><?php echo esc_html( gf_apply_filters( array( 'gform_product_qty', $form_id ), __( 'Qty', 'gravityforms' ), $form_id ) ); ?></th>
								<th scope="col"><?php echo esc_html( gf_apply_filters( array( 'gform_product_unitprice', $form_id ), __( 'Unit Price', 'gravityforms' ), $form_id ) ); ?></th>
								<th scope="col"><?php echo esc_html( gf_apply_filters( array( 'gform_product_price', $form_id ), __( 'Price', 'gravityforms' ), $form_id ) ); ?></th>
								</thead>
								<tbody>
								<?php

								$total = 0;
								foreach ( $products['products'] as $product ) {
									?>
									<tr>
										<td>
											<div class="product_name"><?php echo esc_html( $product['name'] ); ?></div>
											<ul class="product_options">
												<?php
												$price = GFCommon::to_number( $product['price'] );
												if ( is_array( rgar( $product, 'options' ) ) ) {
													$count = sizeof( $product['options'] );
													$index = 1;
													foreach ( $product['options'] as $option ) {
														$price += GFCommon::to_number( $option['price'] );
														$class = $index == $count ? " class='lastitem'" : '';
														$index ++;
														?>
														<li<?php echo $class ?>><?php echo $option['option_label'] ?></li>
														<?php
													}
												}
												$quantity = GFCommon::to_number( $product['quantity'] );

												$subtotal = $quantity * $price;
												$total += $subtotal;
												?>
											</ul>
										</td>
										<td class="textcenter"><?php echo esc_html( $product['quantity'] ); ?></td>
										<td><?php echo GFCommon::to_money( $price ) ?></td>
										<td><?php echo GFCommon::to_money( $subtotal ) ?></td>
									</tr>
									<?php
								}
								$total += floatval( $products['shipping']['price'] );
								?>
								</tbody>
								<tfoot>
								<?php
								if ( ! empty( $products['shipping']['name'] ) ) {
									?>
									<tr>
										<td colspan="2" rowspan="2" class="emptycell">&nbsp;</td>
										<td class="textright shipping"><?php echo esc_html( $products['shipping']['name'] ); ?></td>
										<td class="shipping_amount"><?php echo GFCommon::to_money( $products['shipping']['price'] ) ?>&nbsp;</td>
									</tr>
									<?php
								}
								?>
								<tr>
									<?php
									if ( empty( $products['shipping']['name'] ) ) {
										?>
										<td colspan="2" class="emptycell">&nbsp;</td>
										<?php
									}
									?>
									<td class="textright grandtotal"><?php esc_html_e( 'Total', 'gravityforms' ) ?></td>
									<td class="grandtotal_amount"><?php echo GFCommon::to_money( $total ) ?></td>
								</tr>
								</tfoot>
							</table>
						</td>
					</tr>
<?php }
						
						/*** end product fields ***/
				}
			} else if($field->type == 'publix_coop_calendar'){
				$selected =  $entry[$field['id']];

				
				if( have_rows('coop_cal_entry', 'option') ):
				
					while( have_rows('coop_cal_entry', 'option') ): the_row(); 
						$cycle_id = get_sub_field('unique_id');
						if ($cycle_id == $selected) {
							
							$start_date = get_sub_field('start_date');
							$end_date =  get_sub_field('end_date');
							$popular_holidays =  get_sub_field('popular_holidays');
							$theme_field = get_sub_field_object('theme_pages');
							//$themes = get_sub_field('theme_pages');
							$themes = $theme_field['value'];
							
							echo '<div class="cycle-display order-entry">';
								echo '<div class="left-side">';
									echo '<div class="dates">';
										echo $start_date . " - " . $end_date;
									echo '</div>';
									
									echo '<div class="holidays">';
										echo $popular_holidays;
									echo '</div>';
								echo '</div>';
								
								echo '<div class="themes">';								
									if ($themes) {
										foreach ($themes as $theme) {
											$label = $theme_field['choices'][$theme];
											echo '<div class="selected-theme '.$theme.'"></div>';
										}
									}
								echo '</div>';
								
							echo '</div>';
						}
					endwhile;
				
				endif;
				
			} else if($field->type == 'repeater'){
				echo '<div class="order-entry full-width bottom-margin repeater-entry">';
					echo '<div class="order-label full-width">' . $field->label . '</div>';
					$subfield = $entry[$field['id']];
		
					foreach($subfield as $subvalue){
						echo '<div class="sub-field-entry">';
						foreach($field->fields as $fieldfield){
						
							echo '<div class="order-entry">';
								echo '<div class="order-label">'. $fieldfield->label . '</div>';
								echo '<div class="order-value">' . $subvalue[$fieldfield->id] . '</div>';
							echo '</div>';
						}
						echo '</div>';
					}
				echo '</div>';
			} else if ($field->type == 'fileupload') {
			
				//if($status == "Approved" && $final_invoice){
				if ($entry[$field['id']]) {
					echo '<div class="order-entry">';
						echo '<div class="order-label">'. $field->label.'</div>';
						echo '<a class="order-value" target="_blank" download href="'. $entry[$field['id']] . '">Download File</a>';
					echo '</div>';
				}
			} else if ($field->type == 'product' || $field->type == 'total') {
				
			
						
					//echo '</ul></div>';
				
			}  else if ($field->type == 'option') {
					
					/*echo $field->productField;
				
					echo '<div class="order-entry">';
						echo '<div class="order-label">'. $field->label.'</div>';
						//echo 'Product Field';
						
					echo '</div>';*/
				
			} else if ($field->type == 'address') {
				echo '<div class="order-entry full-width">';
					echo '<div class="order-label">' . $field->label . '</div>';
					echo '<div class="sub-field-entry">';
						foreach( $field['inputs'] as $sub_field) {
							echo '<div class="order-entry">';
								echo '<div class="order-label">' . $sub_field['label'] . '</div>';
								echo '<div class="order-value">'.$entry[$sub_field['id']].'</div>';
							echo '</div>';
						}
						echo '<div class="order-value"></div>';
					echo '</div>';
				echo '</div>';
			} else if ($field->type == 'checkbox') {

				echo '<div class="order-entry">';
					echo '<div class="order-label">' . $field->label . '</div>';
					echo '<div class="sub-field-entry"><ul>';
						foreach( $field['inputs'] as $sub_field) {
								$selected = $entry[$sub_field['id']];
								
								if ($selected) {
									echo '<li class="order-entry"><div class="order-value">'.$entry[$sub_field['id']].'</div></li>';
								}
						}
					echo '</ul></div>';
				echo '</div>';		
			} else if ($field->type == 'buyers_multiselect') {
	
				echo '<div class="order-entry">';
					echo '<div class="order-label" style="font-weight: bold;float:left;margin-right:10px;clear:both;">' . $field->label . '</div>';
					//echo $entry[$field['id']];
					$str_arr = explode (",", $entry[$field['id']]);  
					echo '<div class="sub-field-entry" style="clear:both;"><ul>';
						foreach( $str_arr as $sub_field) {
								$selected = $sub_field;
								
								$user_info = get_userdata($selected);
								$user_name = "";
								if($user_info){
									$user_name = $user_info->user_firstname . " " . $user_info->user_lastname;
								}
								
								if ($selected) {
									echo'<li class="order-entry"><div class="order-value">'.$user_name.'</div></li>';
								}
						}
					echo '</ul></div>';
				echo '</div>';		
			} else {
				
				echo '<div class="order-entry '.strtolower($field->label).'"><div class="order-label">' . $field->label . '</div><div class="order-value">' . $entry[$field['id']] . '</div></div>';
			}
			
		}
		
	echo '</div>'; //end overview table
	return ob_get_clean();
}

//set as denied
add_action( 'gform_after_submission_8', 'conditionally_approve', 10, 2 );
function conditionally_approve( $entry ) {
	
	if(isset($_GET['entry'])){
		$main_form_id = $_GET['entry'];
		}
	//MAKE DYNAMIC
	$meta_key = 2354;
	$meta_value = 'Denied';
	
	gform_add_meta( $main_form_id, $meta_key, $meta_value, $form_id = null );
	
	//add reason to entry
	//MAKE DYNAMIC
	$meta_key = 2355;
	$meta_value = rgar($entry,3);
	
	gform_add_meta( $main_form_id, $meta_key, $meta_value, $form_id = null );
}
//add soft approval comments from buyer
add_action( 'gform_after_submission_14', 'soft_approve', 10, 2 );
function soft_approve( $entry ) {
	
	if(isset($_GET['entry'])){
		$main_form_id = $_GET['entry'];
		}
	$user_id = $entry['created_by']; 
	//GFCommon::log_debug('entry created by:' . $user_id);
	$user_info = get_userdata($user_id);
			
		if($user_info){
		    
		    $first_name = $user_info->first_name;
		    $last_name = $user_info->last_name;
		    
		    $buyer_name .= $first_name . ' ' . $last_name;
		   }
		
	//add reason to entry
	//MAKE DYNAMIC
	$meta_key = 2389;
	$meta_value = rgar($entry,3) . ' -' . $buyer_name;
	
	$current_meta_value = gform_get_meta( $main_form_id, $meta_key );
	
	$new_meta_value = $current_meta_value . '<br/>' . $meta_value;
	
	gform_update_meta( $main_form_id, $meta_key, $new_meta_value, $form_id = null );
}

//add soft denial comments from buyer
add_action( 'gform_after_submission_15', 'soft_deny', 10, 2 );
function soft_deny( $entry ) {
	
	if(isset($_GET['entry'])){
		$main_form_id = $_GET['entry'];
		}
	$user_id = $entry['created_by']; 
	//GFCommon::log_debug('entry created by:' . $user_id);
	$user_info = get_userdata($user_id);
			
		if($user_info){
		    
		    $first_name = $user_info->first_name;
		    $last_name = $user_info->last_name;
		    
		    $buyer_name .= $first_name . ' ' . $last_name;
		   }
		
	//add reason to entry
	//MAKE DYNAMIC
	$meta_key = 2389;
	$meta_value = rgar($entry,3) . ' -' . $buyer_name;
	
	$current_meta_value = gform_get_meta( $main_form_id, $meta_key );
	
	$new_meta_value = $current_meta_value . '<br/>' . $meta_value;
	
	gform_update_meta( $main_form_id, $meta_key, $new_meta_value, $form_id = null );
}
//set as rejected
add_action( 'gform_after_submission_9', 'reject_request', 10, 2 );
function reject_request( $entry ) {
	
	if(isset($_GET['entry'])){
		$main_form_id = $_GET['entry'];
		}
	//MAKE DYNAMIC
	$meta_key = 2354;
	$meta_value = 'Request Form Resubmit';
	
	gform_add_meta( $main_form_id, $meta_key, $meta_value, $form_id = null );
	
	//add reason to entry
	//MAKE DYNAMIC
	$meta_key = 2355;
	$meta_value = rgar($entry,3);
	
	gform_add_meta( $main_form_id, $meta_key, $meta_value, $form_id = null );
	
	
}

//approve via hidden form
add_action( 'gform_after_submission_10', 'approve_request', 10, 2 );
function approve_request( $entry ) {
	
	if(isset($_GET['entry'])){
		$main_form_id = $_GET['entry'];
		}
	
	//MAKE DYNAMIC
	$meta_key = 2354;
	$meta_value = 'Approved';
	
	gform_add_meta( $main_form_id, $meta_key, $meta_value, $form_id = null );

}

//attach invoice to original entry
add_action( 'gform_after_submission_11', 'attach_invoice', 10, 2 );
function attach_invoice( $entry ) {
	
	if(isset($_GET['entry'])){
		$main_form_id = $_GET['entry'];
		}
	
	//MAKE DYNAMIC
	$meta_key = 2362;
	$meta_value = rgar($entry,6);
	
	gform_add_meta( $main_form_id, $meta_key, $meta_value, $form_id = null );
	
	
}

//add custom merge tag to display program info
add_action( 'gform_admin_pre_render', 'add_merge_tags' );
function add_merge_tags( $form ) {
    ?>
    <script type="text/javascript">
        gform.addFilter('gform_merge_tags', 'add_merge_tags');
        function add_merge_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option){
            mergeTags["custom"].tags.push({ tag: '{program_details}', label: 'Order Details' });
            mergeTags["custom"].tags.push({ tag: '{order_link}', label: 'Order Link' });
            mergeTags["custom"].tags.push({ tag: '{vendor_emails}', label: 'Vendor Emails' });
 
            return mergeTags;
        }
    </script>
    <?php
    //return the form object from the php hook
    return $form;
}


//function for program details merge tag
add_filter( 'gform_replace_merge_tags', 'replace_download_link', 10, 7 );
function replace_download_link( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
 
    $custom_merge_tag = '{program_details}';
    
    //id for the approval form
    if($form['id'] == 10) {
    	$original_entry_id = rgar($entry,2);
    } else if($form['id'] == 6) {
    	$original_entry_id = rgar( $entry, 'id');
    }else {
	    //this id is for the reject/request form. Need to check that this is correct on other forms
	    $original_entry_id = rgar($entry,4);
	   }
 
    if ( strpos( $text, $custom_merge_tag ) === false ) {
        return $text;
    }

    $entry_details = publix_display_gf_entry_data( $original_entry_id);
    $text = str_replace( $custom_merge_tag, $entry_details, $text );
    
    GFCommon::log_debug('custom merge output is:' . $text);
 
    return $text;
}

//replace order link merge tag
add_filter( 'gform_replace_merge_tags', 'replace_order_link', 10, 7 );
function replace_order_link( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
 
    $custom_merge_tag = '{order_link}';
    
    //id for the approval form
    $main_entry_page = 'http://publixmarketin.wpengine.com/entry-test/'; //this probably needs to be dynamically set via wp-admin at some point
 
    if ( strpos( $text, $custom_merge_tag ) === false ) {
        return $text;
    }

	  //id for the approval form
    if($form['id'] == 10) {
    	$original_entry_id = rgar($entry,2);
    } else if ($form['id'] == 6) {
	    //main publix form
	    $original_entry_id = $entry['id'];
	    
	    }else {
	    //this id is for the reject/request form. Need to check that this is correct on other forms
	    $original_entry_id = rgar($entry,4);
	   }
	   
    $order_link = $main_entry_page . '?entry=' . $original_entry_id;
    $text = str_replace( $custom_merge_tag, $order_link, $text );
    
    GFCommon::log_debug('custom merge output is:' . $order_link);
 
    return $text;
}

//replace vendor email merge tag
add_filter( 'gform_replace_merge_tags', 'replace_vendor_emails', 10, 7 );
function replace_vendor_emails( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
 
    $custom_merge_tag = '{vendor_emails}';
    
    if ( strpos( $text, $custom_merge_tag ) === false ) {
        return $text;
    }


	$vendor_email_list = "";
	
	//FOR TESTING PURPOSES THIS IS SET TO BUYER. WHEN IT IS READY TO GO LIVE, NEED TO CHANGE USER ROLE TO 'rede_vendor'
		$args = array(
		    'role'    => 'buyer'
		);
		
		$users = get_users( $args );
		
		foreach ( $users as $user ) {
			$vendor_email_list .= $user->user_email . ',';
			}
		
		//remove trailing comma
		$vendor_email_list = rtrim($vendor_email_list, ",");
			   
    $text = str_replace( $custom_merge_tag, $vendor_email_list, $text );
    
    //GFCommon::log_debug('vendor emails are:' . $vendor_email_list);
 
    return $text;
}


//dynamically populate the email of person who submitted the form
add_filter('gform_field_value_submitter_email', 'submitter_email');
function submitter_email ($value) {
	
	$original_entry_id = $_GET['entry'];
	
	$email = gform_get_meta( $original_entry_id, 5);
	return $email;
}

//route main form notification to buyer email
//target form id 203, change to your form id
add_filter( 'gform_notification_6', 'set_email_to_address', 10, 3 );
function set_email_to_address( $notification, $form, $entry ) {
    //There is no concept of user notifications anymore, so we will need to target notifications based on other criteria,
    //such as name
    //fif ( $notification['name'] == 'Admin Notification' ) {
	    $buyer_ids = rgar($entry,2395);
	    $str_arr = explode (",", $buyer_ids);
	    GFCommon::log_debug('str_array:' . $str_arr);
	    foreach ($str_arr as $buyer_id) {
	    	$user_info = get_userdata($buyer_id);

			if($user_info){
			    $buyer_email = $user_info->user_email;
		        //get email address value
		        $user_email = $buyer_email; //value for field id 1, change to your field id
	        
		        //GFCommon::log_debug('buyer data is:' . rgar($entry,2357));
				if ($user_email) {
					$notification['to'] = $notification['to'] . "," . $user_email;
				}
			}
		}
     
    //}
    //return altered notification object
    return $notification;
}

//same function as above but for sendgrid
add_filter( 'gform_sendgrid_email', 'change_sendgrid_email', 10, 5 );
function change_sendgrid_email( $sendgrid_email, $email, $message_format, $notification, $entry ){

        //add a CC email address
        if ($entry['form_id'] == 6) {
	        
	        
	        
	        $buyer_id = rgar($entry,2357);
	        GFCommon::log_debug('entry data is:' . print_r($entry, true));
	        
			$user_info = get_userdata($buyer_id);
			
			GFCommon::log_debug('user email is:' . $user_info->user_email);
		
		if($user_info){
		    $buyer_email = $user_info->user_email;
	        //get email address value
	        $user_email = $buyer_email; 
	        
	        GFCommon::log_debug('buyer email is:' . $user_email);

	        if ($user_email) {
				$sendgrid_email['personalizations'][0]['cc'][0]['email'] = $user_email;
			}
			
		}
		}
		return $sendgrid_email;
}

// Block non-administrators from accessing the WordPress back-end
function wpabsolute_block_users_backend() {
	$user = wp_get_current_user();
	$allowed_roles = array('editor', 'administrator', 'author');
	if ( !array_intersect($allowed_roles, $user->roles ) )  {
		wp_redirect( home_url() . '/dashboard' );
		exit;
	}
}
add_action( 'admin_init', 'wpabsolute_block_users_backend' );

//disable front-end admin bar for non-admins
add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

// Add role class to body
function add_role_to_body($classes) {   
    foreach (wp_get_current_user()->roles as $user_role) {
        $classes[] = 'role-'. $user_role;
    }
    return $classes;
};
add_filter('body_class','add_role_to_body');

//shortcode to display user first name
function um_user_shortcode( $atts ) {
	$atts = extract( shortcode_atts( array(
		'user_id' => get_current_user_id(),
		'meta_key' => '',
	), $atts ) );
	
	if ( empty( $meta_key ) ) return;
	
	if( empty( $user_id ) ) $user_id = get_current_user_id(); 
    
    $meta_value = get_user_meta( $user_id, $meta_key, true );
    if( is_serialized( $meta_value ) ){
       $meta_value = unserialize( $meta_value );
    } 
    if( is_array( $meta_value ) ){
         $meta_value = implode(",",$meta_value );
    }  
    return apply_filters("um_user_shortcode_filter__{$meta_key}", $meta_value );
 
}
add_shortcode( 'um_user', 'um_user_shortcode' );

//shortcode to display dashboard
add_shortcode('publix_show_dashboard','publix_show_dashboard');
function publix_show_dashboard() {
	
	ob_start();
	
	?>
	<script>
		(function($){
			$(document).ready( function () {
				$('#dashboard-datatable').DataTable({
					paging: false,
					searching: false
				});
			} );
		})(jQuery);
	</script>
	<?php
	
	$output = ob_get_clean();
	
	
	$output .= '<table id="dashboard-datatable" class="publix-dashboard" width="100%">';
	
	//search for all entries (admin) or only entries made by the user (user/vendor)
	$current_user = wp_get_current_user();
	$allowed_roles = array('administrator','rede_vendor');
	$buyer_role = array('buyer');
	if( array_intersect($allowed_roles, $current_user->roles ) ) { 
		$search_criteria = array();
		} else if ( array_intersect($buyer_role, $current_user->roles ) ) {
			//$search_criteria = array();
			$user_id = $current_user->ID;
			
			//search for buyer id within buyer field
			$search_criteria['field_filters'][] = array( 'key' => '2395', 'operator' => 'contains', 'value' => $user_id);
			} else {
		$search_criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $current_user->ID );
		}
	//$search_criteria = array();
	//get entries for form with ID of 6
	$entries = GFAPI::get_entries( 6, $search_criteria );
	
		//echo $current_user->ID;
		//echo rgar($entry,2395);
	
		$output .= '<thead><tr class="row header-row">';
		
			$output .= '<th class=" entry-id" style="width: 15%;"><img src="' . plugin_dir_url( __FILE__ ) . 'img/order.png"><br>ID</th>';
			$output .= '<th class=" status" style="width: 15%;"><img src="' . plugin_dir_url( __FILE__ ) . 'img/status.png"><br>Status</th>';
			// $output .= '<th class=" timeframe" style="width: 30%;"><img src="' . plugin_dir_url( __FILE__ ) . 'img/timeframe.png"><br>Submission Timeframe</th>';
			$output .= '<th class=" run-dates" style="width: 20%;"><img src="' . plugin_dir_url( __FILE__ ) . 'img/marketdate.png"><br>Run Dates</th>';
			$output .= '<th class=" brand" style="width: 20%;"><img src="' . plugin_dir_url( __FILE__ ) . 'img/brand.png"><br>Brand</th>';
			// $output .= '<td class=" buyer-name"><img src="' . plugin_dir_url( __FILE__ ) . 'img/retailer.png">Buyer</td>';
			
		$output .= '</tr></thead><tbody>';
		
			
				foreach ($entries as $entry) {
					$entry_id = rgar($entry,'id');
					//echo '<hr>entry: ' . $entry_id . '<br/>';
					
					//we probably need to add this as an option somewhere but for now I just hardcoded it
					$entry_link = 'http://publixmarketin.wpengine.com/entry-test/';
					$status = rgar($entry,2354);
					
					/*timeframe*/
					$selected =  rgar($entry,2397);
					$brand =  rgar($entry,2401);
					//echo 'selected: ' . $selected . '</br>';
					
					if( have_rows('coop_cal_entry', 'option') ):
					
						while( have_rows('coop_cal_entry', 'option') ): the_row(); 
							$cycle_id = get_sub_field('unique_id');
							//echo 'cycle id: ' . $cycle_id . '<br/>';
							//echo $cycle_id == $selected;
							if ($cycle_id == $selected) {
								$start_date = get_sub_field('start_date');
								$end_date =  get_sub_field('end_date');
								$run_dates = $start_date . ' - ' . $end_date;
								
								$submission_start = get_sub_field('submission_start');
								$submission_end = get_sub_field('submission_end');
								$timeframe = $submission_start . " - " . $submission_end;
								
								$popular_holidays = get_sub_field('popular_holidays');
								
								continue;
							} else {
								//echo 'no match';
								//$timeframe = '';
								//$run_dates = '';
							}
							
							//echo $timeframe;
							
						endwhile;

					endif;
					
					$buyer_name = '';
					$buyer_ids = rgar($entry,2395);
				    $str_arr = explode (",", $buyer_ids);
				    //echo $str_arr;

				    foreach ($str_arr as $buyer_id) {
				    	$user_info = get_userdata($buyer_id);
			
						if($user_info){
						    
						    $first_name = $user_info->first_name;
						    $last_name = $user_info->last_name;
						    
						    $buyer_name .= $first_name . ' ' . $last_name . ', ';
						   }
					}
					
					$buyer_name = rtrim($buyer_name, ', ');
					
					//echo $timeframe;		
					
					$output .= '<tr class="row entry-row">';
						$output .= '<td class=" entry-id" style="width: 15%;"><a href="'.$entry_link.'/?entry='.$entry_id.'">'.$entry_id.'</a></td>';
						$output .= '<td class=" status" style="width: 15%;">'.$status.'</td>';
						// $output .= '<td class=" timeframe" style="width: 30%;">'.$timeframe.'</td>';
						$output .= '<td class=" run-dates" style="width: 20%;">'.$run_dates.'</td>';
						$output .= '<td class=" brand" style="width: 20%;">'.$brand.'</td>';
						// $output .= '<td class=" buyer-name">'.$buyer_name.'</td>';
						
					$output .= '</tr>';
			
					}
	
	$output .= '</tbody></table>';
	return $output;
}

    // define the gform_email_background_color_label callback 
    add_filter( 'gform_email_background_color_label', 'set_email_label_color', 10, 3 );
        function set_email_label_color( $color, $field, $lead ){
            return '#def1dc';
        }
        
//add review page
add_filter( 'gform_review_page_6', 'add_review_page', 10, 3 );
function add_review_page( $review_page, $form, $entry ) {
 
    // Enable the review page
    $review_page['is_enabled'] = true;
 
    if ( $entry ) {
        // Populate the review page.
        //$review_page['content'] = '<div class="please-review"><div>Please review your submission. By submitting this form, you agree to our <a style="color:white !important; text-decoration:underline;" href="http://publixmarketin.wpengine.com/order-submission-terms/" target="_blank">terms & conditions</a>.</div></div>';
        //$review_page['content'] .= '<pre>' . print_r($_POST) . '</pre>';
        $review_page['content'] .= GFCommon::replace_variables( '{all_fields}', $form, $entry );
    }
	
 
    return $review_page;
}

add_filter( 'gform_review_page_6', 'change_review_page_button', 10, 3 );
function change_review_page_button( $review_page, $form, $entry ) {
 
    $review_page['nextButton']['text'] = 'Review & Submit';
 
    return $review_page;
}

//add comment field to review page
//add_filter( 'gform_pre_render_6_1', 'publix_review_comments' );
//add_filter( 'gform_pre_validation_6', 'publix_review_comments' );
//add_filter( 'gform_pre_submission_filter_6', 'populate_checkbox' );
//add_filter( 'gform_admin_pre_render_6', 'populate_checkbox' );
/*function publix_review_comments( $form ) {
	$new_field_id = '999999999999999';
	$properties['type'] = 'textarea';
 
$properties['id'] = $new_field_id;
$properties['label'] = 'Additional Review Comments';
$field = GF_Fields::create( $properties );
$form['fields'][] = $field;
GFAPI::update_form( $form );
	return $form;
}*/

//save review comments to entry after submission
//add_action('gform_after_submission_6','add_review_comments', 10, 2);
function add_review_comments($entry, $form) {
	foreach($form['fields'] as $field_key => $field){
		GFCommon::log_debug('field data is:' . print_r($field,true));
		
		if($field->id == '9999'){
			$entry_key = $field['id'];
			$field_id = 'input_' . $entry_key;
			$entry[$entry_key] = $_POST[$field_id];
			
			GFAPI::update_entry($entry, $entry['id']);
		}
	}
}

//custom buyers field
// If the GF_Field class isn't available, bail.
if ( ! class_exists( 'GFForms' ) ) {
	die();
}

/**
 * Class GF_Field_MultiSelect
 *
 * Allows the creation of multiselect fields.
 *
 * @since Unknown
 *
 * @uses GF_Field
 */
class GF_Field_Buyers_MultiSelect extends GF_Field {

	public $type = 'buyers_multiselect';

	/**
	 * Returns the field title.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @return string The field title. Escaped.
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'Buyers Multi Select', 'gravityforms' );
	}

	/**
	 * Assign the field button to the Advanced Fields group.
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}
	/**
	 * Returns the class names of the settings which should be available on the field in the form editor.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @return array Settings available within the field editor.
	 */
	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'enable_enhanced_ui_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'size_setting',
			'choices_setting',
			'rules_setting',
			'visibility_setting',
			'description_setting',
			'css_class_setting',
		);
	}

	/**
	 * Indicates this field type can be used when configuring conditional logic rules.
	 *
	 * @return bool
	 */
	public function is_conditional_logic_supported() {
		return true;
	}

	/**
	 * Whether this field expects an array during submission.
	 *
	 * @since 2.4
	 *
	 * @return bool
	 */
	public function is_value_submission_array() {
		return true;
	}

	/**
	 * Returns the field inner markup.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GF_Field_MultiSelect::is_entry_detail()
	 * @uses GF_Field_MultiSelect::is_form_editor()
	 * @uses GF_Field_MultiSelect::get_conditional_logic_event()
	 * @uses GF_Field_MultiSelect::get_tabindex()
	 *
	 * @param array        $form  The Form Object currently being processed.
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param null|array   $entry Null or the Entry Object currently being edited.
	 *
	 * @return string The field input HTML markup.
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$id       = $this->id;
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$size          = $this->size;
		$class_suffix  = $is_entry_detail ? '_admin' : '';
		$class         = $size . $class_suffix;
		$css_class     = trim( esc_attr( $class ) . ' gfield_select' );
		$tabindex      = $this->get_tabindex();
		$disabled_text = $is_form_editor ? 'disabled="disabled"' : '';


		/**
		 * Allow the placeholder used by the enhanced ui to be overridden
		 *
		 * @since 1.9.14 Third parameter containing the field ID was added.
		 * @since Unknown
		 *
		 * @param string  $placeholder The placeholder text.
		 * @param integer $form_id     The ID of the current form.
		 */
		$placeholder = gf_apply_filters( array(
			'gform_multiselect_placeholder',
			$form_id,
			$this->id
		), __( 'Click to select...', 'gravityforms' ), $form_id, $this );
		$placeholder = $this->enableEnhancedUI ? "data-placeholder='" . esc_attr( $placeholder ) . "'" : '';

		$size = $this->multiSelectSize;
		if ( empty( $size ) ) {
			$size = 7;
		}
		
		ob_start();
		?>
		<script>
			(function($){
				$(document).ready(function(){
					$('#<?php echo $field_id; ?> option').mousedown(function(e) {
					    e.preventDefault();
					    $(this).prop('selected', !$(this).prop('selected'));
					    return false;
					});
				})
			})(jQuery);
		</script>
		<?php
		$script = ob_get_clean();
		
		return sprintf( "{$script}<div class='ginput_container ginput_container_multiselect'><select multiple='multiple' {$placeholder} size='{$size}' name='input_%d[]' id='%s' class='%s' $tabindex %s>%s</select></div>", $id, esc_attr( $field_id ), $css_class, $disabled_text, $this->get_choices( $value ) );
	}

	/**
	 * Helper for retrieving the markup for the choices.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GFCommon::get_select_choices()
	 *
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 *
	 * @return string Returns the choices available within the multi-select field.
	 */
	public function get_choices( $value ) {
		
		//get buyer data
		$args = array(
		    'role'    => 'buyer',
		    'orderby' => 'user_nicename',
		    'order'   => 'ASC'
		);
		$buyers = get_users($args);
		$buyer_options =array();
		foreach($buyers as $buyer) : 
			$buyer_options[] = array('value' => $buyer->ID, 'text' => $buyer->data->display_name);
		endforeach; 
		
		$this->choices = $buyer_options;

		//$value = $this->to_array( $value );

		return GFCommon::get_select_choices( $this, $value, false );

	}

	/**
	 * Format the entry value for display on the entries list page.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param string|array $value    The field value.
	 * @param array        $entry    The Entry Object currently being processed.
	 * @param string       $field_id The field or input ID currently being processed.
	 * @param array        $columns  The properties for the columns being displayed on the entry list page.
	 * @param array        $form     The Form Object currently being processed.
	 *
	 * @return string $value The value of the field. Escaped.
	 */
	public function get_value_entry_list( $value, $entry, $field_id, $columns, $form ) {
		// Add space after comma-delimited values.
		$value = implode( ', ', $this->to_array( $value ) );
		return esc_html( $value );
	}

	/**
	 * Format the entry value for display on the entry detail page and for the {all_fields} merge tag.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GFCommon::selection_display()
	 *
	 * @param string|array $value    The field value.
	 * @param string       $currency The entry currency code.
	 * @param bool|false   $use_text When processing choice based fields should the choice text be returned instead of the value.
	 * @param string       $format   The format requested for the location the merge is being used. Possible values: html, text or url.
	 * @param string       $media    The location where the value will be displayed. Possible values: screen or email.
	 *
	 * @return string The list items, stored within an unordered list.
	 */
	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {

		if ( empty( $value ) || ( $format == 'text' && $this->storageType !== 'json' ) ) {
			return $value;
		}

		$items = $this->to_array( $value );

		$return2 = '<ul>';
		$i = 0;
		
		foreach ( $items as $item ) {
			//$item = esc_html( GFCommon::selection_display( $item, $this, $currency, $use_text ) );
			
			$buyer_id = $item;
			
			$user_info = get_userdata($buyer_id);
			
			if($user_info){
				
			    
			    $first_name = $user_info->first_name;
			    $last_name = $user_info->last_name;
			    
			    $buyer_name = ($first_name . ' ' . $last_name);
			    
			    $return2 .= '<li>' . $first_name . ' ' . $last_name . '</li>';
			   
			   }
			
			//$return2 = $i . ' ' . $buyer_name . '<br/>';
//			$return .= $buyer_name . " ";
			//$i++;
		}

		$return2 .= '</ul>';
		
		return $return2;
		
		//return "<ul class='bulleted'><li>" . GFCommon::implode_non_blank( '</li><li>', $items ) . '</li></ul>';
	}

	/**
	 * Format the value before it is saved to the Entry Object.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GF_Field_MultiSelect::sanitize_entry_value()
	 *
	 * @param array|string $value      The value to be saved.
	 * @param array        $form       The Form Object currently being processed.
	 * @param string       $input_name The input name used when accessing the $_POST.
	 * @param int          $lead_id    The ID of the Entry currently being processed.
	 * @param array        $lead       The Entry Object currently being processed.
	 *
	 * @return string $value The field value. Comma separated if an array.
	 */
	public function get_value_save_entry( $value, $form, $input_name, $lead_id, $lead ) {

		if ( is_array( $value ) ) {
			foreach ( $value as &$v ) {
				$v = $this->sanitize_entry_value( $v, $form['id'] );
			}
		} else {
			$value = $this->sanitize_entry_value( $value, $form['id'] );
		}

		return empty( $value ) ? '' : $this->to_string( $value );
	}

	/**
	 * Format the entry value for when the field/input merge tag is processed.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GFCommon::format_post_category()
	 * @uses GFCommon::format_variable_value()
	 * @uses GFCommon::selection_display()
	 * @uses GFCommon::implode_non_blank()
	 *
	 * @param string|array $value      The field value. Depending on the location the merge tag is being used the following functions may have already been applied to the value: esc_html, nl2br, and urlencode.
	 * @param string       $input_id   The field or input ID from the merge tag currently being processed.
	 * @param array        $entry      The Entry Object currently being processed.
	 * @param array        $form       The Form Object currently being processed.
	 * @param string       $modifier   The merge tag modifier. e.g. value
	 * @param string|array $raw_value  The raw field value from before any formatting was applied to $value.
	 * @param bool         $url_encode Indicates if the urlencode function may have been applied to the $value.
	 * @param bool         $esc_html   Indicates if the esc_html function may have been applied to the $value.
	 * @param string       $format     The format requested for the location the merge is being used. Possible values: html, text or url.
	 * @param bool         $nl2br      Indicates if the nl2br function may have been applied to the $value.
	 *
	 * @return string $return The merge tag value.
	 */
	public function get_value_merge_tag( $value, $input_id, $entry, $form, $modifier, $raw_value, $url_encode, $esc_html, $format, $nl2br ) {
		$items = $this->to_array( $raw_value );

		$modifiers = $this->get_modifiers();

		if ( $this->type == 'post_category' ) {
			if ( is_array( $items ) ) {
				$use_id = in_array( 'id', $modifiers );

				foreach ( $items as &$item ) {
					$cat  = GFCommon::format_post_category( $item, $use_id );
					$item = GFCommon::format_variable_value( $cat, $url_encode, $esc_html, $format );
				}
			}
		} elseif ( ! in_array( 'value', $modifiers ) ) {

			foreach ( $items as &$item ) {
				$item = GFCommon::selection_display( $item, $this, rgar( $entry, 'currency' ), true );
				$item = GFCommon::format_variable_value( $item, $url_encode, $esc_html, $format );
			}
		}

		$return = GFCommon::implode_non_blank( ', ', $items );

		if ( $format == 'html' || $esc_html ) {
			$return = esc_html( $return );
		}

		return $return;
	}

	/**
	 * Format the entry value before it is used in entry exports and by framework add-ons using GFAddOn::get_field_value().
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GFCommon::selection_display()
	 * @uses GFCommon::implode_non_blank()
	 *
	 * @param array      $entry    The entry currently being processed.
	 * @param string     $input_id The field or input ID.
	 * @param bool|false $use_text When processing choice based fields should the choice text be returned instead of the value.
	 * @param bool|false $is_csv   Is the value going to be used in the .csv entries export?
	 *
	 * @return string $value The value of a field from an export file.
	 */
	public function get_value_export( $entry, $input_id = '', $use_text = false, $is_csv = false ) {
		if ( empty( $input_id ) ) {
			$input_id = $this->id;
		}

		$value = rgar( $entry, $input_id );

		if ( ! empty( $value ) && ! $is_csv ) {
			$items = $this->to_array( $value );

			foreach ( $items as &$item ) {
				$item = GFCommon::selection_display( $item, $this, rgar( $entry, 'currency' ), $use_text );
			}
			$value = GFCommon::implode_non_blank( ', ', $items );

		} elseif ( $this->storageType === 'json' ) {

			$items = json_decode( $value );
			$value = GFCommon::implode_non_blank( ', ', $items );
		}

		return $value;
	}

	/**
	 * Converts an array to a string.
	 *
	 * @since 2.2.3.7 Changed access to public.
	 * @since 2.2
	 * @access public
	 *
	 * @uses \GF_Field_MultiSelect::$storageType
	 *
	 * @param array $value The array to convert to a string.
	 *
	 * @return string The converted string.
	 */
	public function to_string( $value ) {
		if ( $this->storageType === 'json' ) {
			return json_encode( $value );
		} else {
			return is_array( $value ) ? implode( ',', $value ) : $value;
		}
	}

	/**
	 * Converts a string to an array.
	 *
	 * @since 2.2.3.7 Changed access to public.
	 * @since 2.2
	 * @access public
	 *
	 * @uses \GF_Field_MultiSelect::$storageType
	 *
	 * @param string $value A comma-separated or JSON string to convert.
	 *
	 * @return array The converted array.
	 */
	public function to_array( $value ) {
		if ( empty( $value ) ) {
			return array();
		} elseif ( is_array( $value ) ) {
			return $value;
		} elseif ( $this->storageType !== 'json' || $value[0] !== '[' ) {
			return array_map( 'trim', explode( ',', $value ) );
		} else {
			$json = json_decode( $value, true );

			return $json == null ? array() : $json;
		}
	}

	/**
	 * Forces settings into expected values while saving the form object.
	 *
	 * No escaping should be done at this stage to prevent double escaping on output.
	 *
	 * Currently called only for forms created after version 1.9.6.10.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function sanitize_settings() {
		parent::sanitize_settings();
		$this->enableEnhancedUI = (bool) $this->enableEnhancedUI;

		$this->storageType = empty( $this->storageType ) || $this->storageType === 'json' ? $this->storageType : 'json';

		if ( $this->type === 'post_category' ) {
			$this->displayAllCategories = (bool) $this->displayAllCategories;
		}
	}

	// # FIELD FILTER UI HELPERS ---------------------------------------------------------------------------------------

	/**
	 * Returns the filter operators for the current field.
	 *
	 * @since 2.4
	 *
	 * @return array
	 */
	public function get_filter_operators() {
		return array( 'contains' );
	}

}

// Register the new field type.
GF_Fields::register( new GF_Field_Buyers_MultiSelect() );


// add status to form entry
add_action('gform_after_submission_6', 'publix_set_entry_status', 10, 2);
function publix_set_entry_status($entry, $form){
	gform_update_meta($entry->id, "publix_order_status", "pending");
}

// add a custom field for BUYERS to gravity forms editor
add_action('init', 'publix_add_buyer_user_role');
function publix_add_buyer_user_role(){
	$subscriber = get_role( 'subscriber' );
	if($subscriber){
		add_role('buyer', 'Buyer', $subscriber->capabilities );
	}
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
add_filter( 'acf/load_field/name=main_order_form', 'acf_populate_gf_forms_ids' );

