<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
function rede_add_ajax_library() {
	$post_id = get_the_ID();

	$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
	$homeurl = home_url();
	$homeurl = explode(':', $homeurl);
	unset($homeurl[0]);
	$homeurl = implode(':', $homeurl);

	$admin_url = admin_url( 'admin-ajax.php' );
	$admin_url = explode(':', $admin_url);
	unset($admin_url[0]);
	$admin_url = implode(':', $admin_url);

	if(stripos($_SERVER['SERVER_PROTOCOL'],'https') === true){
		$homeurl = 'https' . $homeurl;
		$admin_url = 'https' . $admin_url;
	} else {
		$homeurl = 'http' . $homeurl;
		$admin_url = 'http' . $admin_url;
	}
 	
 	// Add ajax endpoint with a few helper variables
    $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '";';
        $html .= 'var baseurl = "' . home_url() . '";';
        $html .= 'var tplurl = "' . get_template_directory_uri() . '";';
    $html .= '</script>';

    // Get the owner of the form or current user if not exists
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	if (isset($_GET['order-id'])) {
		$order_id = $_GET['order-id'];
		$order_author_id = get_post_meta($order_id, '_user', true);
		if(!empty($order_author_id)){
			$user_id = $order_author_id;
		}
	}

	$author_query = array(
        'post_type' => 'rede-order',
        'posts_per_page' => '-1',
        'meta_query' => array(
            'user_clause' => array(
                'key'     => '_user',
                'value'   => $user_id,
                'compare' => '=',
            )
        )
    );

    $orders_total = 0;
	$author_posts = new WP_Query($author_query);
	$serviceByMonth = array();
	while($author_posts->have_posts()){
		$author_posts->the_post();
		$postid = $author_posts->post->ID;

		$order_status = get_post_meta($postid, 'order_status', true);
		if(!in_array($order_status, array("Active", "Awaiting Report", "Completed"))){
			continue;
		}

		$order_total = get_post_meta($postid, 'total', true);
		$order_type = get_post_meta($postid, 'type', true);
		$order_date = get_post_meta($postid, '_startdate', true);
		$order_total = preg_replace('/[\$,]/', '', $order_total);

		$serviceByMonth[] = array(
			'id'	=> $postid,
			'total' => $order_total,
			'type'	=> $order_type,
			'date' 	=> $order_date
		);

		$orders_total += floatval($order_total);
	}
	wp_reset_postdata();

	$budget_cap = get_user_meta($user_id, 'budget_cap', true);

    // Add pricing object to forms for custom pricing
    $form_template = basename( get_page_template() );
    $html .= '<script type="text/javascript">';
    $html .= 'var pricing = {};';
    $html .= 'pricing.user = "'.$user_id.'";';
    $html .= 'pricing.template = "'.$form_template.'";';
    $html .= 'pricing.budget_cap = '. ( !empty($budget_cap) && $budget_cap >= 0 ?  $budget_cap : 'false' ) .';';
    $html .= 'pricing.orders_total = '. ( !empty($orders_total) && $orders_total >= 0 ?  $orders_total : 'false' ) .';';
	$html .= 'pricing.all_orders = '.json_encode($serviceByMonth).';';

    if($form_template === 'service-on-pack.php'){
    	$tactic_pricing = get_post_meta($post_id, 'tactic_pricing', true);
    	$html .= 'pricing.tactics = '.json_encode($tactic_pricing).';';

    	$merch = get_post_meta($post_id, 'pricing_merch', true);
    	$fullfillment = get_post_meta($post_id, 'pricing_fullfillment', true);
    	$shipping = get_post_meta($post_id, 'pricing_shipping', true);
    	$markup = get_post_meta($post_id, 'pricing_markup', true);
    	$tacticcost = get_post_meta($post_id, 'pricing_tacticcost', true);

    	$user_merch = get_user_meta($user_id, 'pricing_merch', true);
    	$user_fullfillment = get_user_meta($user_id, 'pricing_fullfillment', true);
    	$user_shipping = get_user_meta($user_id, 'pricing_shipping', true);
    	$user_markup = get_user_meta($user_id, 'pricing_markup', true);
    	$user_tacticcost = get_user_meta($user_id, 'pricing_tacticcost', true);
    	
		$html .= 'pricing.merch = '. ( !empty($user_merch) && $user_merch >= 0 ?  $user_merch : $merch ) .';';
		$html .= 'pricing.fullfillment = '. ( !empty($user_fullfillment) && $user_fullfillment >= 0 ?  $user_fullfillment : $fullfillment ) .';';
		$html .= 'pricing.shipping = '. ( !empty($user_shipping) && $user_shipping >= 0 ?  $user_shipping : $shipping ) .';';
		$html .= 'pricing.markup = '. ( !empty($user_markup) && $user_markup >= 0 ?  $user_markup : $markup ) .';';
		$html .= 'pricing.tacticcost = '. ( !empty($user_tacticcost) && $user_tacticcost >= 0 ?  $user_tacticcost : $tacticcost ) .';';
		$html .= 'pricing.rede_cost = 750;';

	} else if($form_template === 'service-sas-at-shelf.php'){
		$tactic_pricing = get_post_meta($post_id, 'tactic_pricing', true);
		if(!empty($tactic_pricing) && ((isset($tactic_pricing[0]->tactic) && !empty($tactic_pricing[0]->tactic))  || (isset($tactic_pricing[0]['tactic']) && !empty($tactic_pricing[0]['tactic'])))){
			$html .= 'pricing.tactics = '.json_encode($tactic_pricing).';';
		} else {
			// print_r(array(
			// 	'object' => isset($tactic_pricing[0]->tactic),
			// 	'array' => isset($tactic_pricing[0]['tactic'])
			// ));
			// die();
			$sas_tactics_default = get_sas_tactics_default();
			update_post_meta($post_id, 'tactic_pricing', $sas_tactics_default);
			$html .= 'pricing.tactics = '.json_encode($sas_tactics_default).';';
		}
	
		$sas_shelf_first = get_post_meta($post_id, 'sas_shelf_first', true);
		$sas_shelf_add = get_post_meta($post_id, 'sas_shelf_add', true);
		$sas_shelf_shipping = get_post_meta($post_id, 'sas_shelf_shipping', true);
		$sas_shelf_kitting = get_post_meta($post_id, 'sas_shelf_kitting', true);
		$sas_combo_first = get_post_meta($post_id, 'sas_combo_first', true);
		$sas_combo_add = get_post_meta($post_id, 'sas_combo_add', true);
		$sas_combo_add_blade = get_post_meta($post_id, 'sas_combo_add_blade', true);
		$sas_combo_shipping = get_post_meta($post_id, 'sas_combo_shipping', true);
		$sas_combo_kitting = get_post_meta($post_id, 'sas_combo_kitting', true);
		$markup = get_post_meta($post_id, 'pricing_markup', true);

		$user_sas_shelf_first = get_user_meta($user_id, 'sas_shelf_first', true);
		$user_sas_shelf_add = get_user_meta($user_id, 'sas_shelf_add', true);
		$user_sas_shelf_shipping = get_user_meta($user_id, 'sas_shelf_shipping', true);
		$user_sas_shelf_kitting = get_user_meta($user_id, 'sas_shelf_kitting', true);
		$user_sas_combo_first = get_user_meta($user_id, 'sas_combo_first', true);
		$user_sas_combo_add = get_user_meta($user_id, 'sas_combo_add', true);
		$user_sas_combo_add_blade = get_user_meta($user_id, 'sas_combo_add_blade', true);
		$user_sas_combo_shipping = get_user_meta($user_id, 'sas_combo_shipping', true);
		$user_sas_combo_kitting = get_user_meta($user_id, 'sas_combo_kitting', true);
		$user_markup = get_user_meta($user_id, 'pricing_markup', true);
		
		$html .= 'pricing.sas_shelf_first = '. ( !empty($user_sas_shelf_first) && $user_sas_shelf_first >= 0 ?  $user_sas_shelf_first : $sas_shelf_first ) .';';
		$html .= 'pricing.sas_shelf_add = '. ( !empty($user_sas_shelf_add) && $user_sas_shelf_add >= 0 ?  $user_sas_shelf_add : $sas_shelf_add ) .';';
		$html .= 'pricing.sas_shelf_shipping = '. ( !empty($user_sas_shelf_shipping) && $user_sas_shelf_shipping >= 0 ?  $user_sas_shelf_shipping : $sas_shelf_shipping ) .';';
		$html .= 'pricing.sas_shelf_kitting = '. ( !empty($user_sas_shelf_kitting) && $user_sas_shelf_kitting >= 0 ?  $user_sas_shelf_kitting : $sas_shelf_kitting ) .';';
		$html .= 'pricing.sas_combo_first = '. ( !empty($user_sas_combo_first) && $user_sas_combo_first >= 0 ?  $user_sas_combo_first : $sas_combo_first ) .';';
		$html .= 'pricing.sas_combo_add = '. ( !empty($user_sas_combo_add) && $user_sas_combo_add >= 0 ?  $user_sas_combo_add : $sas_combo_add ) .';';
		$html .= 'pricing.sas_combo_add_blade = '. ( !empty($user_sas_combo_add_blade) && $user_sas_combo_add_blade >= 0 ?  $user_sas_combo_add_blade : $sas_combo_add_blade ) .';';
		$html .= 'pricing.sas_combo_shipping = '. ( !empty($user_sas_combo_shipping) && $user_sas_combo_shipping >= 0 ?  $user_sas_combo_shipping : $sas_combo_shipping ) .';';
		$html .= 'pricing.sas_combo_kitting = '. ( !empty($user_sas_combo_kitting) && $user_sas_combo_kitting >= 0 ?  $user_sas_combo_kitting : $sas_combo_kitting ) .';';
		$html .= 'pricing.markup = '. ( !empty($user_markup) && $user_markup >= 0 ?  $user_markup : $markup ) .';';
		$html .= 'pricing.rede_cost = 750;';
	

	} else if($form_template === 'service-pos-materials.php'){
    	$tactic_pricing = get_post_meta($post_id, 'tactic_pricing', true);
		$html .= 'pricing.tactics = '.json_encode($tactic_pricing).';';
		
		$variable_shipping = get_post_meta($post_id, 'variable_shipping', true);
    	$html .= 'pricing.variable_shipping = '.json_encode($variable_shipping).';';

    	$merch = get_post_meta($post_id, 'pricing_merch', true);
    	$fullfillment = get_post_meta($post_id, 'pricing_fullfillment', true);
    	$shipping = get_post_meta($post_id, 'pricing_shipping', true);
    	$markup = get_post_meta($post_id, 'pricing_markup', true);
    	$tacticcost = get_post_meta($post_id, 'pricing_tacticcost', true);

    	$user_merch = get_user_meta($user_id, 'pricing_merch', true);
    	$user_fullfillment = get_user_meta($user_id, 'pricing_fullfillment', true);
    	$user_shipping = get_user_meta($user_id, 'pricing_shipping', true);
    	$user_markup = get_user_meta($user_id, 'pricing_markup', true);
    	$user_tacticcost = get_user_meta($user_id, 'pricing_tacticcost', true);
    	
		$html .= 'pricing.merch = '. ( !empty($user_merch) && $user_merch >= 0 ?  $user_merch : $merch ) .';';
		$html .= 'pricing.fullfillment = '. ( !empty($user_fullfillment) && $user_fullfillment >= 0 ?  $user_fullfillment : $fullfillment ) .';';
		$html .= 'pricing.shipping = '. ( !empty($user_shipping) && $user_shipping >= 0 ?  $user_shipping : $shipping ) .';';
		$html .= 'pricing.markup = '. ( !empty($user_markup) && $user_markup >= 0 ?  $user_markup : $markup ) .';';
		$html .= 'pricing.tacticcost = '. ( !empty($user_tacticcost) && $user_tacticcost >= 0 ?  $user_tacticcost : $tacticcost ) .';';
		$html .= 'pricing.rede_cost = 750;';

	} else if($form_template === 'service-out-of-home.php'){
		$html .= 'pricing.rede_cost = 1500;';
		
	} else if($form_template === 'service-mobile-media.php'){
		$html .= 'pricing.rede_cost = 1500;';

	} else if($form_template === 'service-coupon.php'){
		$html .= 'pricing.rede_cost = 1500;';
		
	} else if($form_template === 'service-social.php'){
		$html .= 'pricing.rede_cost = 1500;';
		
	} else if($form_template === 'service-shroud.php'){
    	$base = get_post_meta($post_id, 'pricing_base', true);
    	$upgrade1Amount = get_post_meta($post_id, 'pricing_upgrade1Amount', true);
    	$upgrade2Amount = get_post_meta($post_id, 'pricing_upgrade2Amount', true);
    	$upgrade3Amount = get_post_meta($post_id, 'pricing_upgrade3Amount', true);

    	$user_base = get_user_meta($user_id, 'pricing_base', true);
    	$user_upgrade1Amount = get_user_meta($user_id, 'pricing_upgrade1Amount', true);
    	$user_upgrade2Amount = get_user_meta($user_id, 'pricing_upgrade2Amount', true);
    	$user_upgrade3Amount = get_user_meta($user_id, 'pricing_upgrade3Amount', true);

		$html .= 'pricing.base = '. ( !empty($user_base) && $user_base >= 0 ?  $user_base : $base ) .';';
        $html .= 'pricing.upgrade1Amount = '. ( !empty($user_upgrade1Amount) && $user_upgrade1Amount >= 0 ?  $user_upgrade1Amount : $upgrade1Amount ) .';';
        $html .= 'pricing.upgrade2Amount = '. ( !empty($user_upgrade2Amount) && $user_upgrade2Amount >= 0 ?  $user_upgrade2Amount : $upgrade2Amount ) .';';
        $html .= 'pricing.upgrade3Amount = '. ( !empty($user_upgrade3Amount) && $user_upgrade3Amount >= 0 ?  $user_upgrade3Amount : $upgrade3Amount ) .';';
		$html .= 'pricing.rede_cost = 2500;';

	} else if($form_template === 'dashboard.php'){
		// $html .= 'pricing.rede_cost = 1500;';
		$quick_start_setup = get_post_meta($post_id, '_quick_start_setup', true);
		$html .= 'pricing.quick_start_setup = '.json_encode($quick_start_setup).';';
	}

	$html .= '</script>';

	// Prepopulate user data if orderid is present
	if (isset($_GET['order-id']) && strpos($form_template, 'service-') !== false) {
		$html .= '<script type="text/javascript">';
	    $order_id = $_GET['order-id'];
	    $order_data = get_all_meta($order_id);
	    // print_r($order_data);
	    // die();
	    $order_data['ID'] = $order_id;
	    $order_data = json_encode($order_data);
	    $html .= "var orderDetails = " . $order_data . ";";
		$html .= '</script>';
	}
 
    echo $html;
 
}
add_action( 'wp_head', 'rede_add_ajax_library');

function rede_ajax_media_upload(){
	if(empty($_POST)){
		$output = array('status' => 'error', 'data' => 'Missing post body');
		echo json_encode($output);
		die();
	}

	// Validate nonce
	$test_nonce = check_ajax_referer( 'media-nonce', 'security', false );
	if(!$test_nonce &&  wp_doing_ajax()){
		$output = array('status' => 'error', 'data' => 'Security test failed');
		echo json_encode($output);
		die();
	}

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$fileErrors = array(
		0 => "There is no error, the file uploaded with success",
		1 => "The uploaded file exceeds the upload_max_files in server settings",
		2 => "The uploaded file exceeds the MAX_FILE_SIZE from html form",
		3 => "The uploaded file uploaded only partially",
		4 => "No file was uploaded",
		6 => "Missing a temporary folder",
		7 => "Failed to write file to disk",
		8 => "A PHP extension stoped file to upload" );

	$posted_data =  isset( $_POST ) ? $_POST : array();
	$file_data = isset( $_FILES ) ? $_FILES : array();
	$data = array_merge( $posted_data, $file_data );
	$type = 'default';
	$config = get_config();

	if (isset($_POST['type'])){
		$type = $_POST['type'];
	}

	$uploaded_file  = wp_handle_upload( $data['creative'], array( 'test_form' => false ) );

	//$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );

	if ( is_wp_error( $uploaded_file  ) ) {
		$errors[] = $attachment_id->get_error_message();
		$output = array('status' => 'error', 'data' => $errors);
		echo json_encode($output);
		die();
	} else {
		$data = array(
			'uploaded_file' => $uploaded_file 
		);

		if ($type === 'creative' && isset($_POST['order-id']) && !empty($_POST['order-id'])){
			$data['order-status'] = 'Creative Added';
			update_post_meta($_POST['order-id'], 'rede_creative', $uploaded_file['url']);
			// update_post_meta($_POST['order-id'], 'order_status', 'Creative Added');
			$comment = 'Creative added: <a href="'.$uploaded_file['url'].'" target="_blank">View Creative</a>';
			rede_add_comment($_POST['order-id'], $comment);
		}

		if ($type === 'report' && isset($_POST['order-id']) && !empty($_POST['order-id'])){
			$data['order-status'] = 'Completed';
			update_post_meta($_POST['order-id'], 'vendor_reports', $uploaded_file['url']);
	    	// update_post_meta($_POST['order-id'], 'order_status', 'Completed');
	    	$comment = 'Report added: <a href="'.$uploaded_file['url'].'" target="_blank">View Report</a>';
	    	rede_add_comment($_POST['order-id'], $comment);
	    	$data['user_email'] = rede_send_email_report_added($_POST['order-id']);
		}

		if ($type === 'brief' && isset($_POST['order-id']) && !empty($_POST['order-id'])){
			$data['order-status'] = 'Creative Added';
			update_post_meta($_POST['order-id'], 'vendor_briefs', $uploaded_file['url']);
	    	// update_post_meta($_POST['order-id'], 'order_status', 'Completed');
	    	$comment = 'Brief added: <a href="'.$uploaded_file['url'].'" target="_blank">View Brief</a>';
	    	rede_add_comment($_POST['order-id'], $comment);
	    	$data['user_email'] = rede_send_email_report_added($_POST['order-id']);
		}

		if ($type === 'user_brief' && isset($_POST['order-id']) && !empty($_POST['order-id'])){
			$data['order-status'] = 'Creative Added';
			update_post_meta($_POST['order-id'], 'user_briefs', $uploaded_file['url']);
	    	// update_post_meta($_POST['order-id'], 'order_status', 'Completed');
	    	$comment = 'Brief added: <a href="'.$uploaded_file['url'].'" target="_blank">View Brief</a>';
	    	rede_add_comment($_POST['order-id'], $comment);
	    	$data['user_email'] = rede_send_email_report_added($_POST['order-id']);
		}
	    
	    $data['admin_email'] = rede_admin_email_send($_POST['order-id']);

		$output = array('status' => 'success', 'data' => $data);
		echo json_encode($output);
		die();
	}
}

if(is_admin()){
	add_action('wp_ajax_media_upload', 'rede_ajax_media_upload');
	add_action('wp_ajax_nopriv_media_upload', 'rede_ajax_media_upload');
}
function rede_ajax_change_status(){
	if(empty($_POST)){
		$output = array('status' => 'error', 'data' => 'Missing post body');
		echo json_encode($output);
		die();
	}

	$type = $_POST['type'];
	$order_id = $_POST['order-id'];

	// Validate nonce
	if($type === 'approve'){
		$test_nonce = check_ajax_referer( 'approve-nonce', 'security', false );
	} else if($type === 'conditional'){
		$test_nonce = check_ajax_referer( 'conditional-nonce', 'security', false );
	} else {
		$test_nonce = check_ajax_referer( 'deny-nonce', 'security', false );
	}
	if(!$test_nonce &&  wp_doing_ajax()){
		$output = array('status' => 'error', 'data' => 'Security test failed');
		echo json_encode($output);
		die();
	}

	$comment = "";
	// Update comments and order data
	if(isset($_POST['impressions']) & !empty($_POST['impressions'])){
		$impressions = $_POST['impressions'];
		$comment .= 'Impressions: ' . $impressions . '. ';
		$data['order-impressions'] = $impressions;
		update_post_meta($order_id, 'impressions',  $impressions);
	}
	if(isset($_POST['cpm']) & !empty($_POST['cpm'])){
		$cpm = $_POST['cpm'];
		$comment .= 'CPM: ' . $cpm . '. ';
		$data['order-cpm'] = $cpm;
		update_post_meta($order_id, 'cpm',  $cpm);
	}
	if(isset($_POST['comment']) & !empty($_POST['comment'])){
		$comment .= $_POST['comment'];
		$data['order-comment'] = $comment;
		//update_post_meta($order_id, 'vendor_comment', $comment);
		rede_add_comment($order_id, $comment);
	}

	// Update status
	$old_status = get_post_meta($order_id, 'order_status', true);
	if($type === 'approve'){
		if($old_status === "Creative Added"){
			$status = 'Review Order';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else if($old_status === "Confirm Details"){
			$status = 'Review Order';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else if($old_status === "Needs Creative"){
			$status = 'Awaiting Brief';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_send_email_creative_added($order_id);
		} else if($old_status === "Awaiting Brief"){	
			$status = 'Review Brief';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else if($old_status === "Review Brief"){	
			$status = 'Creative Added';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_send_email_creative_added($order_id);
		} else {
			$status = 'Active';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_send_email_approved($order_id);
		}
		$data['order-status'] = $status;
	}
	if($type === 'conditional'){
		if($old_status === "Creative Added"){
			$status = 'Needs Creative';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else if($old_status === "Confirm Details"){
			$status = 'Review Order';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else if($old_status === "Review Brief"){	
			$status = 'Awaiting Brief';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else {
			$status = 'Confirm Details';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_send_email_conditional($order_id);
		}

		$data['order-status'] = $status;
	}
	if($type === 'deny'){
		if($old_status === "Creative Added"){
			$status = 'Needs Creative';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_vendor_email_send($order_id);
		} else {
			$status = 'Order Denied';
			update_post_meta($order_id, 'order_status', $status);
			$data['order-email'] = rede_send_email_denied($order_id);
		}

		$data['order-status'] = $status;
	}

	rede_admin_email_send($order_id);

	$output = array('status' => 'success', 'data' => $data);
	echo json_encode($output);
	die();

}

if(is_admin()){
	add_action('wp_ajax_change_status', 'rede_ajax_change_status');
	add_action('wp_ajax_nopriv_change_status', 'rede_ajax_change_status');
}

function rede_ajax_create_order(){
	set_time_limit( 60 );

	if(empty($_POST)){
		$output = array('status' => 'error', 'data' => 'Missing post body');
		echo json_encode($output);
		die();
	}

	// Validate nonce
	$test_nonce = check_ajax_referer( 'order-nonce', 'security', false );
	if(!$test_nonce &&  wp_doing_ajax()){
		$output = array('status' => 'error', 'data' => 'Security test failed');
		echo json_encode($output);
		die();
	}

	$revision_id = false;
	if(isset($_POST['revision']) && $_POST['revision'] != false){
		$revision_id = $_POST['revision'];
	}

	$errors = array();
	$output = array();

	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;

	if($revision_id !== false && $order_author_id = get_post_meta($revision_id, '_user', true)){
		$owner_user = get_userdata($order_author_id);
	} else {
		$owner_user = $current_user;
	}

	$config = get_config();

	$allowed_fields = array(
		'ordername',
		'brand',
		'campaignobjective',
		'campaignpurpose',
		'campaigntiming',
		'demographics',
		'enddate',
		'profilegender',
		'profileage',
		'profilechildren',
		'profileincome',
		'destinationurl',
		'optimization',
		'budget',
		'otherdetails',
		'tactic',
		'tacticRadio',
		'tactic-custom',
		'quantity',
		'sku',
		'marketdate',
		'marketdate_out_cycle',
		'dma',
		'upgrade-1',
		'upgrade-2',
		'upgrade-3',
		'upgrade2-custom',
		'upgrade3-custom',
		'store',
		'storecount',
		'customstorecount',
		'billboardcount',
		'total',
		'costperstore',
		'type',
		'ordertype',
		'pfid',
		'otherconsiderations',
		'_vendor',
		'_user',
		'serviceURL',
		'producttype',
		'categorytype',
		'marketdate2',
		'timestart',
		'timeend',
		'productname',
		'productdesc',
		'productunit',
		'productupc',
		'productsampled',
		'productfeatured',
		'productbackup',
		'productdistribution',
		'sellingpoints',
		'preparation',
		'equipment',
		'distributiongoal',
		'productcoupon',
		'productsupplies',
		'productcta',
		'productheadline',
		'productsubhead',
		'productlegal',
		'redecollateral',
		'productcollateral',
		'destination',
        'dest-email',
        'dest-quantity',
        'specialinstructions',
        'shippingaddress',
		'shippinginstructions',
		'sasatshelftactic',
		'sasatshelfquantity',
		'at-shelf-tactic-custom',
		'storedepartment',
		'aislequantity',
		'aisleplacement'
	);

	if($_POST['type'] === 'On-Pack'){
		$needsCreative = true;
		$customStoreCount = true;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'tactic' 		=> 'tactic',
			'marketdate'	=> 'market date',
			'type'			=> 'type'
		);
	} else if($_POST['type'] === 'Point-of-Sale Materials'){
		$needsCreative = true;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'tactic' 		=> 'tactic',
			'type'			=> 'type'
		);
	} else if($_POST['type'] === 'Out of Home'){
		$needsCreative = true;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	} else if($_POST['type'] === 'Mobile Media'){
		$needsCreative = true;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	} else if($_POST['type'] === 'Coupon Booster'){
		$needsCreative = true;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);

	} else if($_POST['type'] === 'Sampling'){
		$needsCreative = false;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	} else if($_POST['type'] === 'Paid Social'){
		$needsCreative = true;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	} else if($_POST['type'] === 'Security Shroud'){
		$needsCreative = true;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'storecount'	=> 'store count',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	} else if($_POST['type'] === 'SAS At-Shelf'){
		$needsCreative = false;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'storecount'	=> 'store count',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	} else {
		$needsCreative = false;
		$customStoreCount = false;
		$required_fields = array(
			'ordername' 	=> 'order name',
			'brand'			=> 'brand',
			'marketdate'	=> 'market date',
			'storecount'	=> 'store count',
			'type'			=> 'type',
			'budget'		=> 'budget'
		);
	}

	$file_data = isset( $_FILES ) ? $_FILES : array();

	foreach($required_fields as $required_name => $required_val){
		if(!isset($_POST[$required_name]) || empty($_POST[$required_name])){
			$errors[$required_name] = "Missing " . $required_val;
		}
	}
	if($needsCreative){
		$hasCreative = false;
		if(isset($file_data['creative']) && !empty($file_data['creative'])){
			$hasCreative = true;
		}
		if(isset($_POST['tactic-custom']) && !empty($_POST['tactic-custom'])){
			$hasCreative = true;
		}
		if(isset($_POST['pfid']) && !empty($_POST['pfid'])){
			$hasCreative = true;
		}
		if($revision_id !== false){
			$testcreative =  get_post_meta($revision_id, 'filename', true);
			if(!empty($testcreative)){
				$hasCreative = true;
			}
		}
		if(!$hasCreative){
			$errors['fileupload'] = "Missing creative";
		}
	}

	if($customStoreCount){
		$hasStoreCount = false;
		if(isset($_POST['storecount']) && !empty($_POST['storecount'])){
			$hasStoreCount = true;
		}
		if(isset($_POST['customstorecount']) && !empty($_POST['customstorecount'])){
			$hasStoreCount = true;
		}
		if(!$hasStoreCount){
			$errors['storecount'] = "Missing store count";
		}
	}

	$hasStoreList = false;
	if(isset($_POST['customstorecount']) && !empty($_POST['customstorecount'])){
		if(isset($file_data['customlist']) && !empty($file_data['customlist'])){
			$hasStoreList = true;
		}
		if($revision_id !== false){
			$testcustomlist =  get_post_meta($revision_id, 'customlistname', true);
			if(!empty($testcustomlist)){
				$hasStoreList = true;
			}
		}
		if(!$hasStoreList){
			$errors['customstorecount'] = "Missing custom store list";
		}
	}

	$realbudget = $_POST['budget'];
	$realbudget = str_replace('$', '', $realbudget);
	$realbudget = str_replace(',', '', $realbudget);
	$realbudget = floatval($realbudget);
	if($_POST['type'] === 'Out of Home' && $realbudget < 15000){
		$errors['storecount'] = "Please enter a minimum budget of $15,000";
	}

	if(!empty($errors)){
		$output = array('status' => 'error', 'data' => $errors);
		$output = array(
			'status' => 'error',
			'file_data' => $file_data,
			'_POST' => $_POST,
			'data' => $errors
		);
		echo json_encode($output);
		die();
	}

	// Proceed with order creation
	$clean_page_title = $_POST['ordername'];

	// if (!get_page_by_title($clean_page_title, OBJECT, 'rede-order')) {

	if(isset($_POST['revision']) && $_POST['revision'] !== "false"){
		$new_order = array(
			'ID'			=> $_POST['revision'],
		    'post_title'    => $clean_page_title,
		    'post_content'  => '',
		    'post_status'   => 'publish',
		    'post_author'   => $owner_user->ID,
		    'post_type'		=> 'rede-order'
		);
		$post_id = wp_update_post( $new_order );
	} else {
		$new_order = array(
		    'post_title'    => $clean_page_title,
		    'post_content'  => '',
		    'post_status'   => 'publish',
		    'post_author'   => $owner_user->ID,
		    'post_type'		=> 'rede-order'
		);
		$post_id = wp_insert_post( $new_order );
	}
	 
	if(!is_wp_error($post_id)){
		// Start our output object
		$data = array(
			'id' => $post_id,
			'new_order' => $new_order
		);

	  	// Change order status
	  	if(isset($_POST['revision']) && $_POST['revision'] !== "false"){
			// update_post_meta( $post_id, 'order_status', 'Pending Confirmation' );
		} else {
			update_post_meta( $post_id, 'order_status', 'Pending Confirmation' );
		}

		update_post_meta( $post_id, '_rede_edit_last', $current_user_id );

		// Sas fix
		if (isset($_POST['sasatshelftactic']) && strpos($_POST['sasatshelftactic'], 'on-pack') === false) {
			$data['sascombo'] = false;
			$arr_key = array_search('tactic', $allowed_fields);
			unset($allowed_fields[$arr_key]);
			$arr_key = array_search('quantity', $allowed_fields);
			unset($allowed_fields[$arr_key]);
			$data['allowed_fields'] = $allowed_fields;
		}

		// Update post meta with all allowed field data
		foreach($allowed_fields as $allowed_field_name){
			if(isset($_POST[$allowed_field_name])){
				update_post_meta( $post_id, $allowed_field_name, $_POST[$allowed_field_name] );
			}
		}

		// Create sql formatted start and end dates based on marketdate and enddate
		$marketdate = (!empty($_POST['marketdate']) ? $_POST['marketdate'] : $_POST['marketdate_out_cycle']);
		$enddate = "";
		$_startdate = "";
		$_enddate = "";
		$_createddate = date($config['datetime_format_sql']);
		if(isset($_POST['enddate']) && !empty($_POST['enddate'])){
			$enddate = $_POST['enddate'];
		}
		if (strpos($marketdate, 'Cycle') !== false) {
			$_dateparts = explode('(', $marketdate);
			$_dateparts = str_replace(')', '', $_dateparts[1]);
			$_dateparts = explode(' - ', $_dateparts);
			$_startdate = date($config['datetime_format_sql'], strtotime($_dateparts[0]));
			$_enddate = date($config['datetime_format_sql'], strtotime($_dateparts[1]));
		} else if (!empty($enddate)){
			$_startdate = date($config['datetime_format_sql'], strtotime($marketdate));
			$_enddate = date($config['datetime_format_sql'], strtotime($enddate));
		} else {
			$_startdate = date($config['datetime_format_sql'], strtotime($marketdate));
			$_enddate = date($config['datetime_format_sql'], strtotime($marketdate) + (2 * 60 * 60 * 24 * 7));
		}
		update_post_meta( $post_id, '_startdate', $_startdate );
		update_post_meta( $post_id, '_enddate', $_enddate );
		update_post_meta( $post_id, '_createddate', $_enddate );
		update_post_meta( $post_id, '_invoicedate', $_enddate );
		$data['_startdate'] = $_startdate;
		$data['_enddate'] = $_enddate;
		$data['_createddate'] = $_createddate;

		if(isset($file_data['creative'])){
			$upload_media = rede_upload_media($file_data['creative'], 'creative');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filename', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguid', $upload_media['data']['file']);
			}
			$data['creative'] = $upload_media;
			// $data['creativedata'] = $upload_media->data;
			// $data['creativestatus'] = $upload_media['status'];
		}

		if(isset($file_data['segment'])){
			$upload_media = rede_upload_media($file_data['segment'], 'segment');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameseg', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidseg', $upload_media['data']['file']);
			}
			$data['segment'] = $upload_media;
		}

		if(isset($file_data['customlist'])){
			$upload_media = rede_upload_media($file_data['customlist'], 'customlist');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'customlistname', $upload_media['data']['url']);
				update_post_meta( $post_id, 'customlistguid', $upload_media['data']['file']);
			}
			$data['customlist'] = $upload_media;
		}

		if(isset($file_data['customshopper'])){
			$upload_media = rede_upload_media($file_data['customshopper'], 'customshopper');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'customshoppername', $upload_media['data']['url']);
				update_post_meta( $post_id, 'customshopperguid', $upload_media['data']['file']);
			}
			$data['customshopper'] = $upload_media;
		}

		if(isset($file_data['upgrade2'])){
			$upload_media = rede_upload_media($file_data['upgrade2'], 'upgrade2');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameupgrade2', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidupgrade2', $upload_media['data']['file']);
			}
			$data['upgrade2'] = $upload_media;
		}

		if(isset($file_data['upgrade3'])){
			$upload_media = rede_upload_media($file_data['upgrade3'], 'upgrade3');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameupgrade3', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidupgrade3', $upload_media['data']['file']);
			}
			$data['upgrade3'] = $upload_media;
		}

		if(isset($file_data['audience'])){
			$upload_media = rede_upload_media($file_data['audience'], 'audience');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameaudience', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidaudience', $upload_media['data']['file']);
			}
			$data['audience'] = $upload_media;
		}

		if(isset($file_data['geography'])){
			$upload_media = rede_upload_media($file_data['geography'], 'geography');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenamegeography', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidgeography', $upload_media['data']['file']);
			}
			$data['geography'] = $upload_media;
		}

		if(isset($file_data['sku'])){
			$upload_media = rede_upload_media($file_data['sku'], 'sku');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenamesku', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidgesku', $upload_media['data']['file']);
			}
			$data['sku'] = $upload_media;
		}

		if(isset($file_data['productbeauty'])){
			$upload_media = rede_upload_media($file_data['productbeauty'], 'productbeauty');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameproductbeauty', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidgeproductbeauty', $upload_media['data']['file']);
			}
			$data['productbeauty'] = $upload_media;
		}

		if(isset($file_data['productshot'])){
			$upload_media = rede_upload_media($file_data['productshot'], 'productshot');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameproductshot', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidgeproductshot', $upload_media['data']['file']);
			}
			$data['productshot'] = $upload_media;
		}

		if(isset($file_data['productlogo'])){
			$upload_media = rede_upload_media($file_data['productlogo'], 'productlogo');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenameproductlogo', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidgeproductlogo', $upload_media['data']['file']);
			}
			$data['productlogo'] = $upload_media;
		}

		if(isset($file_data['atshelfcreative'])){
			$upload_media = rede_upload_media($file_data['atshelfcreative'], 'atshelfcreative');
			if($upload_media['status'] === "success" && isset($upload_media['data']) && isset($upload_media['data']['url'])){
				update_post_meta( $post_id, 'filenamesasatshelf', $upload_media['data']['url']);
				update_post_meta( $post_id, 'fileguidsasatshelf', $upload_media['data']['file']);
			}
			$data['atshelfcreative'] = $upload_media;
		}

		// Update users brands list if manually added
		if(isset($_POST['brand'])){
            $brand = trim($_POST['brand']);
            $brands = get_user_brands();
            if(!in_array($brand, $brands)){
                $brands = get_user_meta($current_user_id, 'brands', true);
                array_push($brands, $brand);
                $data['newbrands'] = $brands;
                update_user_meta($current_user_id, 'brands', $brands);
            }
        }

		// $data['admin_email'] = rede_admin_email_send($post_id);

        // Generate output for the browser
		$output = array('status' => 'success', 'data' => $data);
		echo json_encode($output);
		die();
	} else {
		$errors[] = "Failed to create/update order";
		$output = array('status' => 'error', 'data' => $errors);
		echo json_encode($output);
		die();
	}

	// } else {
	// 	$errors[] = "Order already exists with the same name";
	// 	$output = array('status' => 'error', 'data' => $errors);
	// 	echo json_encode($output);
	// 	die();
	// }
}

if(is_admin()){
	add_action('wp_ajax_create_order', 'rede_ajax_create_order');
	add_action('wp_ajax_nopriv_create_order', 'rede_ajax_create_order');
}

function rede_ajax_update_account(){
	// Validate nonce
	$test_nonce = check_ajax_referer( 'account-nonce', 'security', false );
	if(!$test_nonce &&  wp_doing_ajax()){
		$output = array('status' => 'error', 'data' => 'Security test failed');
		echo json_encode($output);
		die();
	}

	$errors = array();
	$output = array();
	$data = array();

	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	$type = false;
	$builtin = array('display_name', 'description', 'first_name', 'last_name', 'user_email');
	$passwordFields = array('inputOldPassword','inputNewPassword');
	$customfields = array(
		'brand',
	);

	$fields = $_POST;

	$builtin_updates = array();
	foreach($builtin as $builtin_field){
		if(isset($_POST[$builtin_field]) && $_POST[$builtin_field] !== ""){
			$builtin_updates[$builtin_field] = $_POST[$builtin_field];
		}
	}
	if(!empty($builtin_updates)){
			$builtin_updates['ID'] = $current_user_id;
			$success = wp_update_user( $builtin_updates );
			$data['builtin'] = $builtin_updates;
	}

	foreach($customfields as $customfield){
		if(isset($_POST[$customfield]) && $_POST[$customfield] !== ""){
			$success = update_user_meta($current_user_id, $customfield, $_POST[$customfield]);
			$data['customfields'] = $success;
		}
	}

	$password_updates = array();
	if(isset($_POST[$passwordFields[0]]) && $_POST[$passwordFields[0]] !== "" && isset($_POST[$passwordFields[1]]) && $_POST[$passwordFields[1]] !== "" && wp_check_password( $_POST[$passwordFields[0]], $current_user->user_pass, $current_user->ID)){
		$password_updates['ID'] = $current_user_id;
		$password_updates['user_pass'] = $_POST[$passwordFields[1]];
		$success = wp_update_user( $password_updates );
		$data['password'] = $success;
	} else {
		$data = "Old password not valid";
		$output = array('status' => 'error', 'data' => $data);
	}

	$output = array('status' => 'success', 'data' => $data);
	echo json_encode($output);
	die();
}

if(is_admin()){
	add_action('wp_ajax_update_account', 'rede_ajax_update_account');
	add_action('wp_ajax_nopriv_update_account', 'rede_ajax_update_account');
}

function rede_ajax_get_billboard_count(){
	// Validate nonce
	global $StoreLocator;

	$test_nonce = check_ajax_referer( 'billboards-nonce', 'security', false );
	if(!$test_nonce &&  wp_doing_ajax()){
		$output = array('status' => 'error', 'data' => 'Security test failed');
		echo json_encode($output);
		die();
	}

	$errors = array();
	$output = array();
	$data = array();

	$transient_key = 'rede7_' . md5($_POST['stores']);
	if ( false === ( $billboardcount =  get_transient( $transient_key ) ) ) {
	    $all_stores = array();
	    $stores = explode(',' , $_POST['stores']);
	    $billboards = $StoreLocator->billboards_get_stores_by_parents($stores);
		$billboardcount = count($billboards);
		set_transient( $transient_key, $billboardcount, 2 * MONTH_IN_SECONDS );

	}
	$output = array('status' => 'success', 'data' => $billboardcount);
	echo json_encode($output);
	die();
}

if(is_admin()){
	add_action('wp_ajax_billboard_count', 'rede_ajax_get_billboard_count');
	add_action('wp_ajax_nopriv_billboard_count', 'rede_ajax_get_billboard_count');
}


function rede_ajax_get_dma_billboard_count(){
	// Validate nonce
	global $StoreLocator;

	$test_nonce = check_ajax_referer( 'billboards-nonce', 'security', false );
	if(!$test_nonce &&  wp_doing_ajax()){
		$output = array('status' => 'error', 'data' => 'Security test failed');
		echo json_encode($output);
		die();
	}

	$errors = array();
	$output = array();
	$data = array();

	$transient_key = 'rede1_' . md5($_POST['dmas']);
	if ( false === ( $billboards =  get_transient( $transient_key ) ) ) {
        $all_zips = array();
        $dmas = explode('/' , $_POST['dmas']);
        // print_r($dmas);
        $zips = $StoreLocator->rede_zips_by_dmas($dmas);
        foreach($zips as $zip){
             array_push($all_zips, $zip->zip);
        }
        $billboards = $StoreLocator->billboards_get_by_zips($all_zips);
        set_transient( $transient_key, $billboards, 2 * MONTH_IN_SECONDS );

	}
	$output = array('status' => 'success', 'data' => count($billboards));
	echo json_encode($output);
	die();
}

if(is_admin()){
	add_action('wp_ajax_dma_billboard_count', 'rede_ajax_get_dma_billboard_count');
	add_action('wp_ajax_nopriv_dma_billboard_count', 'rede_ajax_get_dma_billboard_count');
}
