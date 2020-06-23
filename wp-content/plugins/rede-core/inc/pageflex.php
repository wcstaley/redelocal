<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function redirectToPageFlex($pid){
	$current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
	$redirect_url = home_url( '/pageflex-response' );
	$url = "http://digitalprint.alliedprinting.com/ShopperMarketingHub/SSPMCreateEdit.aspx?";
	$url .= "DocumentID=".$pid;
	$url .= "&Action=create";
	$url .= "&AdminPass=dalim2015";
	$url .= "&AdminUser=smh2015";
	$url .= "&UserName=dalim20151";
	$url .= "&ReturnURL=".$redirect_url;
	$url .= "&UF_FirstName=".$current_user->first_name;
	$url .= "&UF_LastName=".$current_user->last_name;
	$url .= "&UF_EmailAddress=".$current_user->user_email;

	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {
		$header = $response['headers']; // array of http header lines
		$body = $response['body']; // use the content
		$uid = explode(':', $body);
		$uid = $uid[1];
		$newUrl = 'http://digitalprint.alliedprinting.com/ShopperMarketingHub/UserPMCreateEdit.aspx?ticket=' . $uid;
		// echo '<!DOCTYPE html>
		// 	<html xmlns="http://www.w3.org/1999/xhtml">
		// 	<head runat="server">
		// 	    <title>Red/E Marketing - Creative Templates</title>
		// 	</head>
		// 	<body>
		// 	    <iframe src="'.$newUrl.'" style="border: 0; position:absolute; top:0; left:0; right:0; bottom:0; width:100%; height:100%"/>
		// 	</body>
		// 	</html>';
		wp_redirect( $newUrl );
		die();
	} else {
		echo "There was an error connecting to the creative database.";
		die();
	}
}

function rede_pageflex_order($pid){
	$current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
	$url = "http://digitalprint.alliedprinting.com/ShopperMarketingHub/SSPMCommitDelete.aspx";
	$url .= "?action=commit";
	$url .= "&AdminPass=dalim2015";
	$url .= "&AdminUser=smh2015";
	$url .= "&UserName=dalim20151";
	$url .= "&DocumentID=" . $pid;
	$url .= "&returnOrderId=y";
	$url .= "&UF_FirstName=".$current_user->first_name;
	$url .= "&UF_LastName=".$current_user->last_name;
	$url .= "&UF_EmailAddress=".$current_user->user_email;
	$response = wp_remote_post( $url );
	if ( is_array( $response ) ) {
		$header = $response['headers']; // array of http header lines
		$body = $response['body']; // use the content
		$body = explode('ORDER_ID:', $body);
		$uid = explode(':', $body[0]);
		$uid = trim($uid[1]);
		$oid = $body[1];
		$oid = trim($oid);
		$orderdata = array($uid, $oid);
		if(isset($_GET['order-id'])){
			update_post_meta($_GET['order-id'], '_pageflex_order', $orderdata);
		}
	} else {
		// echo "There was an error connecting to the creative database.";
		$oid = '';
	}
	return $oid;
}

function saveFromRedirect(){
	$cookie_home_url = home_url();
	$cookie_home_url = explode('://', $cookie_home_url);
	$cookie_home_url = $cookie_home_url[1];
	$cookie_home_url = explode('/', $cookie_home_url);
	$cookie_url = $cookie_home_url[0];
	if(isset($cookie_home_url[1])){
		$cookie_path = $cookie_home_url[1];
	} else {
		$cookie_path = "";
	}

	status_header(200);
	
	$setCookie = setcookie("docid", $_GET['DocID'], time()+3600,$cookie_path, $cookie_url);
	echo '<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head runat="server">
	    <title></title>
	</head>
	<body>
	    <form id="form1" runat="server">
	    <div>
	        <script>
	           parent.window.close();
	        </script>
	    </div>
	    </form>
	</body>
	</html>';
	die();
}



function rede_upload_media($fileinput, $filekey){
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

	// $uploaded_file  = wp_handle_upload( $fileinput, array( 'test_form' => false ) );

	// //$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );

	// if ( $uploaded_file && !isset( $uploaded_file['error'] ) ) {
	// 	return array('status' => 'success', 'data' => $uploaded_file);
	// } else {
	// 	return array('status' => 'error', 'data' => $uploaded_file);
	// }


	$attachment_id = media_handle_upload( $filekey, 0 );
		
	if ( is_wp_error( $attachment_id ) ) { 
		return array('status' => 'error', 'data' => $fileErrors[ $data['ibenic_file_upload']['error'] ]);
	} else {
		$fullsize_path = get_attached_file( $attachment_id );
		$pathinfo = pathinfo( $fullsize_path );
		$type = $pathinfo['extension'];
		if( $type == "jpeg"
		|| $type == "jpg"
		|| $type == "png"
		|| $type == "gif" ) {
			$type = "image/" . $type;
		}
		$url = wp_get_attachment_url( $attachment_id );
		$response = array();
		$response['file'] = $fullsize_path;
		$response['url'] = $url;
		$response['type'] = $type;
		return array('status' => 'success', 'data' => $response);
	}
}