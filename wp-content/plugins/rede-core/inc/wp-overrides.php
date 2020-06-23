<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function rede_template_redirect(){	
	$postid = get_the_ID();
	$fullurl = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    global $StoreLocator;

	if(isset($_GET['testemail'])){
        $order_id = $_GET['order-id'];
        $output = array();
		$output['user'] = rede_send_order_created($order_id);
        $output['vendor'] = rede_vendor_email_send($order_id);
        $output['admin'] = rede_admin_email_send($order_id);
        print_r($output);
		die();
	}

	if(isset($_GET['dumptactics'])){
		$order_id = get_the_ID();
		$tactic_pricing = get_post_meta($order_id, 'tactic_pricing', true);
		header('Content-Type: application/json');
		echo json_encode($tactic_pricing);
		die();
	}

    if(isset($_GET['emailtemplate'])){
        $order_id = $_GET['order-id'];
        $templateHTML = get_email_template("_base.php");
        $emaildata = array();
        $emaildata['email_subject'] = "New Program - Program #" . $order_id;
        $emaildata['email_head'] = "A NEW PROGRAM HAS BEEN CREATED FOR YOUR REVIEW";
        $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                        Greetings,<br><br>
                                        New program <strong>#' . $order_id . '</strong> has been created for you to review.
                                    </p>';
        $emaildata['email_order'] = generateOrderInfoHTML($order_id);
        $emaildata['email_button'] = generateOrderButton($order_id);
        foreach($emaildata as $email_key=>$email_content){
            if(!empty($email_content) && $email_content !== false){
                $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
            } else {
                 $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
            }
        }
        echo $templateHTML;
        die();
    }

    if(isset($_GET['teststores'])){
		$all_zips = array();
		$all_stores = array();
		$stores = explode(',' , $_GET['teststores']);
	    $billboards = $StoreLocator->billboards_get_stores_by_parents($stores);
		$billboardcount = count($billboards);
		echo $billboardcount;
		die();
    }

 //    if(isset($_GET['testcsv'])){
 //        $order_id = $_GET['order-id'];
 //        echo rede_create_dma_billboard_excel($order_id);
 //        die();
 //    }

    if(isset($_GET['campaigndownload'])){
        rede_create_campaigns_excel(array());
        die();
	}

	if(isset($_GET['json-gen'])){
		require_once dirname(__FILE__) . '/json/json-gen.php';
	}

	// Generate PDF
	if(isset($_GET['createpdf'])){
		if( !is_user_logged_in() ){
	        wp_redirect( wp_login_url( $fullurl ) );
	        die;
	    }
    	// Redirect to home page if $_GET['order-id'] is not set
    	if(!isset($_GET['order-id'])){
    		wp_redirect( home_url( '/dashboard' ) );
	        die;
    	}
    	$author_id = (int)get_post_field('post_author', $_GET['order-id']);
    	if($author_id !== get_current_user_id() ){
    		//print_r(array($author_id, get_current_user_id()));
	    	wp_redirect( home_url( '/dashboard' ) );
	        die;
	    }
		rede_create_invoice_pdf($_GET['order-id']);
		die();
	}


	// Prevent users not logged in from accessing registered only pages
	$registered_only = get_post_meta($postid, 'registered_only', true);
    if( $registered_only && !is_user_logged_in() ){
        wp_redirect( wp_login_url( $fullurl ) );
        die;
    }

    if(is_page()){
        $current_user = wp_get_current_user();
        $disabled_tactics = rwmb_meta( 'disabled_tactics', array( 'object_type' => 'user' ), $current_user->ID );
        $page_id = get_the_ID();
        foreach($disabled_tactics as $disabled_tactic){
            //echo $disabled_tactic . ' : ' . $page_id . '<br>';
            if((int)$disabled_tactic === $page_id){
                //echo 'Match<br>';
                wp_redirect( home_url( '/no-access' ) );
                die;
            }
            //die;
        }
    }

    // Prevent non admin users from accessing other orders
    if( is_singular('rede-order')){
    	if( !is_user_logged_in() ){
	        wp_redirect( wp_login_url( $fullurl ) );
	        die;
	    }
    	$author_id = (int)get_post_field('post_author', $postid);
    	if($author_id !== get_current_user_id() ){
	    	wp_redirect( home_url( '/dashboard' ) );
	        die;
	    }
    }

    // Prevent non admin users from accessing other invoices
    if(is_page_template( 'page-templates/order-invoice.php' )){
    	if( !is_user_logged_in() ){
	        wp_redirect( wp_login_url( $fullurl ) );
	        die;
	    }
    	// Redirect to home page if $_GET['order-id'] is not set
    	if(!isset($_GET['order-id'])){
    		wp_redirect( home_url( '/dashboard' ) );
	        die;
    	}
    	$author_id = (int)get_post_field('post_author', $_GET['order-id']);
    	if($author_id !== get_current_user_id() ){
    		// print_r(array($author_id, get_current_user_id()));
	    	wp_redirect( home_url( '/dashboard' ) );
	        die;
	    }
    }

    // Prevent non admin users from accessing other order confirmation pages
    if(is_page_template( 'page-templates/order-confirm.php' )){
    	if( !is_user_logged_in() ){
	        wp_redirect( wp_login_url( $fullurl ) );
	        die;
	    }
    	// Redirect to home page if $_GET['order-id'] is not set
    	if(!isset($_GET['order-id'])){
    		wp_redirect( home_url( '/dashboard' ) );
	        die;
    	}
    	// Redirect to home page if user already confirmed
    	$order_status = get_post_meta( $_GET['order-id'], 'order_status', true );
    	if($order_status !== 'Pending Confirmation'){
    		wp_redirect( home_url( '/dashboard' ) );
	        die;
    	}
    	$author_id = (int)get_post_field('post_author', $_GET['order-id']);
    	if(!user_check_role('administrator') && $author_id !== get_current_user_id() ){
	    	wp_redirect( home_url( '/dashboard' ) );
	        die;
	    }
    }

	// Prevent access to users and general public
    if(is_page_template( 'page-templates/message-center.php' )){
    	if( !is_user_logged_in() ){
	        wp_redirect( wp_login_url( $fullurl ) );
	        die;
	    }
        // Redirect if user role and not reviewing Red/E creative
        $order_status = get_post_meta( $_GET['order-id'], 'order_status', true );
        if(user_check_role('rede_user') && !in_array($order_status, array('Creative Added','Confirm Details'))){
            if(isset($_GET['order-id'])){
                wp_redirect( get_permalink($_GET['order-id']) );
                die;
            } else {
                wp_redirect( home_url( '/dashboard' ) );
                die;
            }
        }
	}

	// Handle pageflex response
	$urlpath=strtok($_SERVER["REQUEST_URI"],'?');
	$urlpath=trim($urlpath, '/');
	$urlpath=explode('/', $urlpath);
	$urlpath=end($urlpath);
	if($urlpath === 'pageflex-response'){
		saveFromRedirect();
	}

	// Redirect to pageflex
	if(isset($_GET['pageflexredirect']) && isset($_GET['pfid'])){
		redirectToPageFlex($_GET['pfid']);
	}

	// Process order confirmation page
    if(is_page_template( 'page-templates/order-confirm.php' ) && isset($_POST['confirm-nonce'])){
    	$order_id = $_GET['order-id'];
        $order_data = get_all_meta($order_id);
    	$nonce_action = 'confirm_' . $order_id;
    	check_admin_referer( $nonce_action, 'confirm-nonce' );

    	$config = get_config();
    	$today = date($config['datetime_format']);
    	update_post_meta( $order_id, 'confirm_date', $today );

    	$comments = trim($_POST['comments']);
    	// update_post_meta( $order_id, 'comments', $comments );
        if(!empty($comments)){
            rede_add_comment($order_id, $comments);
        }
    	
    	set_start_status($order_id);

        if(isset($order_data['pfid']) && !empty($order_data['pfid'])){
            rede_pageflex_order($order_data['pfid']);
            rede_pageflex_email_send($order_id);
        }

        rede_send_order_created($order_id);
        rede_vendor_email_send($order_id);
        rede_admin_email_send($order_id);

        if($order_data['type'] === 'Out of Home'){
            if(isset($order_data['store']) && !empty($order_data['store'])){
                rede_create_billboard_excel($order_id);
            } else if(isset($order_data['dma']) && !empty($order_data['dma'])){
                rede_create_dma_billboard_excel($order_id);
            }
        }

    	wp_redirect( home_url( '/order-thanks' ) );

        // wp_redirect( home_url( '/dashboard' ) );
        die;
    }

    rede_report_email();
}
add_action( 'template_redirect', 'rede_template_redirect' );

// Restrict access to the admin bar
add_action('init', 'rede_restrict_admin_bar');
function rede_restrict_admin_bar() {
	if ( ! current_user_can( 'manage_options' ) ){
		add_filter('show_admin_bar', '__return_false');
	}
}

// Restrict access to the admin area but allow ajax calls from the front end
function rede_restrict_admin() {
	$redirect = false;

	// Check for admin user or ajax call before redirecting
	if ( ! current_user_can( 'delete_posts' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		$redirect = true;
	}
	// Check for image uploading before redirecting
	if ( strpos( $_SERVER['REQUEST_URI'] , 'async-upload.php' ) ) {
		$redirect = false;
	}
	// Redirect
	if ( $redirect ) {
		wp_redirect( home_url() );
		exit;
	}
}
add_action( 'admin_init', 'rede_restrict_admin', 1 );

// Filter wp_nav_menu() to add additional links and other output
function new_nav_menu_items($items) {
	if(!is_user_logged_in()){
    	$homelink = '<li class="home"><a href="' . home_url( '/' ) . '">' . __('Home') . '</a></li>';
    } else {
    	$homelink = '';
	}

    if(!is_user_logged_in()){
   		$signinlink = '<li class="sign-in"><a href="' . wp_login_url( home_url( '/dashboard' ) ) . '">' . __('Sign In') . '</a></li>';
	} else {
		$signinlink = '<li class="sign-out"><a href="' . wp_logout_url( home_url( '/' ) ) . '">' . __('Sign Out') . '</a></li>';
	}
    // add the home link to the end of the menu
    $items =  $homelink . $items . $signinlink;
    return $items;
}
add_filter( 'wp_nav_menu_items', 'new_nav_menu_items' );

function my_login_logo() { ?>
    <style type="text/css">
	    body.login {
	    	background: #fff;
	    }
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/dist/assets/images/Logo/rede_100.jpg);
			height:110px;
			width:110px;
			background-size: 110px 110px;
			background-repeat: no-repeat;
        	padding-bottom: 30px;

        }
        body.login .button-primary {
		    background: #a72027;
		    border-color: #a72027;
		    color: #fff;
		    box-shadow: none;
		    text-decoration: none;
		    text-shadow: none;
		}
        body.login .button-primary:hover {
		    background: #c77074;
		    border-color: #a72027;
		    color: #fff;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// Add the custom columns to the rede-order post type:
add_filter( 'manage_rede-order_posts_columns', 'set_custom_edit_rede_order_columns' );
function set_custom_edit_rede_order_columns($columns) {
	$oldColumns = $columns;
	unset($columns['comments']);
	unset($columns['date']);

    $columns['order-id'] = __( 'ID', 'your_text_domain' );

	$columns['order-type'] = __( 'Type', 'your_text_domain' );
    $columns['order-status'] = __( 'Status', 'your_text_domain' );

    $columns['order-author'] = __( 'Client', 'your_text_domain' );
    $columns['order-date'] =__( 'Date', 'your_text_domain' );

    return $columns;
}

function manage_rede_order_column($defaults) {  
    $new = array();
    $new['cb'] = '<input type="checkbox" />';
    $new['order-id'] = 'ID';
    $new['title'] = 'Title';
    $new['author'] = 'Author';
    $new['order-type'] = 'Type';
    $new['order-status'] = 'Status';
    $new['order-author'] = 'Client';
    $new['order-date'] = 'Date';
    return $new;  
} 
add_filter('manage_rede-order_posts_columns', 'manage_rede_order_column');  

// Add the data to the custom columns for the rede-order post type:
add_action( 'manage_rede-order_posts_custom_column' , 'custom_rede_order_column', 10, 2 );
function custom_rede_order_column( $column, $post_id ) {
    switch ( $column ) {

        case 'order-id' :
            echo $post_id; 
            break;

    	case 'order-type' :
            echo get_post_meta( $post_id , 'type' , true ); 
            break;

        case 'order-status' :
            echo get_post_meta( $post_id , 'order_status' , true ); 
            break;

        case 'order-author' :
        	$author_id = get_post_field('post_author', $post_id);
        	$firstname = get_the_author_meta( 'first_name', $author_id);
        	$lastname = get_the_author_meta( 'last_name', $author_id);
        	$fullname = $firstname . ' ' . $lastname;
        	$fullname = '<a href="'.admin_url('user-edit.php').'?user_id='. $author_id .'">' . trim($fullname) . '</a>';
            echo $fullname; 
            break;

        case 'order-date':
        	echo get_the_date();
        	break;

    }
}

add_filter( 'post_row_actions', 'rede_row_actions', 10, 2 );
function rede_row_actions( $actions, WP_Post $post ) {
    if ( $post->post_type != 'rede-order' ) {
        return $actions;
    }
    // print_r($actions);
    // die();
    unset($actions['edit']);
    unset($actions['inline hide-if-no-js']);
    $actions['rede-review'] = '<a href="'. home_url('review-center/') .'?order-id='.$post->ID.'">Review</a>';
    $actions['rede-invoice'] = '<a href="'. home_url('invoice/') .'?order-id='.$post->ID.'">Invoice</a>';
    return $actions;
}

// Allow duplicate comments
add_filter('duplicate_comment_id', '__return_false');