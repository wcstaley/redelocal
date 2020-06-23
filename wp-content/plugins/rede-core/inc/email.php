<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function rede_report_email(){
    $emails_query = array(
        'post_type' => 'rede-order',
        'posts_per_page' => '1',
        'date_query' => array(
            array(
                'before' => '3 weeks ago'
            )
        ),
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => 'order_status',
                'value'   => 'Active',
                'compare' => 'LIKE',
            ),
            array(
                'key'     => '_active_email',
                'compare' => 'NOT EXISTS',
            ),
        ),
        'order' => 'ASC'
    );
    $emails_posts = new WP_Query($emails_query);
    while($emails_posts->have_posts()) : $emails_posts->the_post();
        $order_id = $emails_posts->post->ID;
        update_post_meta($order_id, '_active_email', 'sent');
        $config = get_config();
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
        $order_data = get_all_meta($order_id);
        $data = array();
        // print_r($order_data);
        if(isset($order_data['_vendor']) && !empty($order_data['_vendor'])){
            $vendor_info = get_userdata($order_data['_vendor']);
            $templateHTML = get_email_template("_base.php");
            $emaildata = array();
            $emaildata['email_subject'] = "UPLOAD REPORT - Program #" . $order_id;
            $emaildata['email_to'] = $vendor_info->user_email;
            $emaildata['email_to_name'] = $vendor_info->first_name . ' ' . $vendor_info->last_name;
            $emaildata['email_head'] = "THIS IS A REMINDER TO UPLOAD A REPORT WHEN THIS CAMPAIGN IS COMPLETE";
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                            Greetings,<br><br>
                                            This is a reminder to upload a report for program <strong>#' . $order_id . '</strong> once it is complete. When a report is uploaded, the order will automatically be marked as complete.
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
            $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
            $data['VendorEmail'] = $emailresponse;
        }
    endwhile;
    // die();
}

function rede_send_email_report_added($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    $order_author_id = get_post_meta($order_id, '_user', true);
    $user_info = get_userdata($order_author_id);

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");
    $emaildata = array();
    $emaildata['email_subject'] = "Creative Added - Program #" . $order_id;
    $emaildata['email_to'] = $user_info->user_email;
    $emaildata['email_to_name'] = $user_info->first_name . ' ' . $user_info->last_name;
    $emaildata['email_head'] = "NEW CREATIVE HAS BEEN ADDED TO YOUR PROGRAM";
    $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                    Greetings,<br><br>
                                    New creative has been added to program <strong>#'.$order_id.'</strong>. Click the \'View Order\' button above to approve your updated order.
                                </p>';
    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    $emaildata['email_button'] = generateOrderButton($order_id);
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
    $data['UserEmail'] = $emailresponse;
    return $data;
}

function rede_send_email_creative_added($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    $order_author_id = get_post_meta($order_id, '_user', true);
    $user_info = get_userdata($order_author_id);

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");
    $emaildata = array();
    $emaildata['email_subject'] = "Creative Added - Program #" . $order_id;
    $emaildata['email_to'] = $user_info->user_email;
    $emaildata['email_to_name'] = $user_info->first_name . ' ' . $user_info->last_name;
    $emaildata['email_head'] = "NEW CREATIVE HAS BEEN ADDED TO YOUR PROGRAM";
    $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                    Greetings,<br><br>
                                    New creative has been added to program <strong>#'.$order_id.'</strong>. Click the \'View Order\' button above to approve your updated order.
                                </p>';
    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    $emaildata['email_button'] = generateOrderButton($order_id);
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
    $data['UserEmail'] = $emailresponse;
    return $data;
}

function rede_send_email_approved($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    $order_author_id = get_post_meta($order_id, '_user', true);
    $user_info = get_userdata($order_author_id);

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");
    $emaildata = array();
    $emaildata['email_subject'] = "Program Approved - Program #" . $order_id;
    $emaildata['email_to'] = $user_info->user_email;
    $emaildata['email_to_name'] = $user_info->first_name . ' ' . $user_info->last_name;
    $emaildata['email_head'] = "YOUR PROGRAM HAS BEEN APPROVED";
    $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                    Greetings,<br><br>
                                    Your program <strong>#'.$order_id.'</strong> has been approved and we are red/e to implement your program based on the order submitted.
                                </p>';
    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    $emaildata['email_button'] = false;
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
    $data['UserEmail'] = $emailresponse;
    return $data;
}

function rede_send_email_denied($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    $order_author_id = get_post_meta($order_id, '_user', true);
    $user_info = get_userdata($order_author_id);

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");
    $emaildata = array();
    $emaildata['email_subject'] = "Request Form Denied - Program #" . $order_id;
    $emaildata['email_to'] = $user_info->user_email;
    $emaildata['email_to_name'] = $user_info->first_name . ' ' . $user_info->last_name;
    $emaildata['email_head'] = "YOUR REQUEST FORM HAS BEEN DENIED";
    $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                    Greetings,<br><br>
                                    Your request form has been denied. A Red/E account executive will call to assist you with your programs order requirements. Please forward the best number to reach you or call (203) 219-8103.
                                </p>';
    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    $emaildata['email_button'] = false;
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
    $data['UserEmail'] = $emailresponse;
    return $data;
}

function rede_send_email_conditional($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    $order_author_id = get_post_meta($order_id, '_user', true);
    $user_info = get_userdata($order_author_id);

    // $order_author_id = get_post_meta($order_id, '_user', true);
    // $user_info = get_userdata($order_author_id);

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");
    $emaildata = array();
    $emaildata['email_subject'] = "Program Needs Review - Program #" . $order_id;
    $emaildata['email_to'] = $user_info->user_email;
    $emaildata['email_to_name'] = $user_info->first_name . ' ' . $user_info->last_name;
    $emaildata['email_head'] = "YOUR PROGRAM HAS BEEN UPDATED AND NEEDS REVIEW";
    $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                    Greetings,<br><br>
                                    Your program <strong>#'.$order_id.'</strong> has been conditionally approved pending your review.
                                </p>';
    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    $emaildata['email_button'] = generateOrderButton($order_id);
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
    $data['UserEmail'] = $emailresponse;
    return $data;
}

function rede_send_order_created($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");
    $emaildata = array();
    $emaildata['email_subject'] = "Program Submitted - Program #" . $order_id;
    $emaildata['email_to'] = $current_user->user_email;
    $emaildata['email_to_name'] = $current_user->first_name . ' ' . $current_user->last_name;
    $emaildata['email_head'] = "YOU HAVE CREATED A NEW PROGRAM";
    if($order_data['order_status'] === "Out of Home" && isset($order_data['customlistname']) && !empty($order_data['customlistname'])){
        $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                        Greetings,<br><br>
                                        Your program <strong>#'.$order_id.'</strong> has been submitted. Red/E is currently reviewing your custom audience list and will contact you with confirmation of total billboard count.
                                    </p>';
    } else {
        $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                        Greetings,<br><br>
                                        Your program <strong>#'.$order_id.'</strong> has been submitted.
                                    </p>';
    }
    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    $emaildata['email_button'] = false;
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
    $data['UserEmail'] = $emailresponse;

    return $data;
}

function rede_vendor_email_send($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    if(isset($order_data['_vendor']) && !empty($order_data['_vendor'])){
        $vendor_info = get_userdata($order_data['_vendor']);
        $templateHTML = get_email_template("_base.php");
        $emaildata = array();
        $emaildata['email_subject'] = "New Program - Program #" . $order_id;
        $emaildata['email_to'] = $vendor_info->user_email;
        $emaildata['email_to_name'] = $vendor_info->first_name . ' ' . $vendor_info->last_name;
        $emaildata['email_head'] = "A NEW PROGRAM HAS BEEN CREATED FOR YOUR REVIEW";
        if($order_data['order_status'] === "Out of Home" && isset($order_data['customlistname']) && !empty($order_data['customlistname'])){
        $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                        Greetings,<br><br>
                                        New program <strong>#' . $order_id . '</strong> has been created for you to review. User has submitted custom audience list. Please review and confirm total billboard count during order approval process.
                                    </p>';
        } else {
         $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                        Greetings,<br><br>
                                        New program <strong>#' . $order_id . '</strong> has been created for you to review.
                                    </p>';   
        }
        $emaildata['email_order'] = generateOrderInfoHTML($order_id);
        $emaildata['email_button'] = generateOrderButton($order_id);
        foreach($emaildata as $email_key=>$email_content){
            if(!empty($email_content) && $email_content !== false){
                $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
            } else {
                 $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
            }
        }
        $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $emaildata['email_to'], $emaildata['email_to_name'], $emaildata['email_subject'], $templateHTML, '','Order Approved');
        $data['VendorEmail'] = $emailresponse;
    }
    return $data;
}

function rede_pageflex_email_send($order_id){
    $config = get_config();
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $order_data = get_all_meta($order_id);
    $data = array();

    if(isset($order_data['_vendor']) && !empty($order_data['_vendor'])){
        $vendor_info = get_userdata($order_data['_vendor']);
        $templateHTML = get_email_template("_base.php");
        $emaildata = array();
        $emaildata['email_subject'] = "New Program - Program #" . $order_id;
        $emaildata['email_head'] = "A NEW PROGRAM HAS BEEN CREATED";
        $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                                        Greetings,<br><br>
                                        New program <strong>#' . $order_id . '</strong> has been created for you to review.
                                    </p>';
        $emaildata['email_order'] = generateOrderInfoHTML($order_id);
        $emaildata['email_button'] = "";
        foreach($emaildata as $email_key=>$email_content){
            if(!empty($email_content) && $email_content !== false){
                $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
            } else {
                 $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
            }
        }
        $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $config['_pfemail'], 'Allied Printing', $emaildata['email_subject'], $templateHTML, '','Order Approved');
        $data['PFEmail'] = $emailresponse;
    }
    return $data;
}

function rede_admin_email_send($order_id){
    $config = get_config();
    $order_author_id = get_post_meta($order_id, '_user', true);
    $order_data = get_all_meta($order_id);
    $data = array();

    if ( $last_id = get_post_meta( $order_id, '_edit_last', true) ) {
        $user_info = get_userdata($last_id);
    } else if($order_author_id = get_post_meta($order_id, '_user', true)) {
        $user_info = get_userdata($order_author_id);
    } else {
        $post_author_id = get_post_field( 'post_author', $order_id );
        $user_info = get_userdata($post_author_id);
    }

    // Send order confirm to user
    $templateHTML = get_email_template("_base.php");

    $emaildata = array();
    $emaildata['email_order'] = false;
    $emaildata['email_button'] = false;

    switch($order_data['order_status']){
        case 'Review Order':
        case 'Review Proof':
        case 'Review Creative':
            $emaildata['email_subject'] = "New Program Created - Program #" . $order_id;
            $emaildata['email_head'] = "A NEW PROGRAM HAS BEEN CREATED";
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                Greetings,<br><br>
                Program <strong>#'.$order_id.'</strong> has been created.<br><br>
                Owner: ' . $user_info->first_name . ' ' . $user_info->last_name . '
            </p>';
            break;

        case 'Needs Creative':
            $emaildata['email_subject'] = "New Program Needs Creative - Program #" . $order_id;
            $emaildata['email_head'] = "A NEW PROGRAM HAS BEEN CREATED";
            $emaildata['email_button'] = generateOrderButton($order_id);
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                Greetings,<br><br>
                Program <strong>#'.$order_id.'</strong> has been created and needs creative.<br><br>
                Owner: ' . $user_info->first_name . ' ' . $user_info->last_name . '
            </p>';
            break;

        case 'Active':
            $emaildata['email_subject'] = "Program Is Active - Program #" . $order_id;
            $emaildata['email_head'] = "A PROGRAM IS NOW ACTIVE";
            $emaildata['email_button'] = generateOrderButton($order_id);
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                Greetings,<br><br>
                Program <strong>#'.$order_id.'</strong> has been approved and is set to active.<br><br>
                Owner: ' . $user_info->first_name . ' ' . $user_info->last_name . '
            </p>';
            break;

        case 'Order Denied':
            $emaildata['email_subject'] = "Program Was Denied - Program #" . $order_id;
            $emaildata['email_head'] = "A PROGRAM WAS DENIED";
            $emaildata['email_button'] = generateOrderButton($order_id);
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                Greetings,<br><br>
                Program <strong>#'.$order_id.'</strong> has been denied.<br><br>
                Owner: ' . $user_info->first_name . ' ' . $user_info->last_name . '
            </p>';
            break;

        case 'Completed':
            $emaildata['email_subject'] = "Program Completed - Program #" . $order_id;
            $emaildata['email_head'] = "A PROGRAM HAS BEEN COMPLETED";
            $emaildata['email_button'] = generateOrderButton($order_id);
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                Greetings,<br><br>
                Program <strong>#'.$order_id.'</strong> has been set to completed.<br><br>
                Owner: ' . $user_info->first_name . ' ' . $user_info->last_name . '
            </p>';
            break;

        default:
            $emaildata['email_subject'] = "Program Status Changed - Program #" . $order_id;
            $emaildata['email_head'] = "STATUS CHANGE TO: " . $order_data['order_status'];
            $emaildata['email_button'] = generateOrderButton($order_id);
            $emaildata['email_body'] = '<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
                Greetings,<br><br>
                Program <strong>#'.$order_id.'</strong> status changed to '.$order_data['order_status'].'.<br><br>
                Owner: ' . $user_info->first_name . ' ' . $user_info->last_name . '
            </p>';
            break;

    }

    $emaildata['email_order'] = generateOrderInfoHTML($order_id);
    //$emaildata['email_button'] = generateOrderButton(55);
    foreach($emaildata as $email_key=>$email_content){
        if(!empty($email_content) && $email_content !== false){
            $templateHTML = str_replace('{{ '.$email_key.' }}', $email_content, $templateHTML);
        } else {
             $templateHTML = str_replace('{{ '.$email_key.' }}', "", $templateHTML);
        }
    }
    $emailresponse = rede_email_send("support@rede-marketing.com", "Red/E", $config['_helpemail'], 'Red/E Admin', $emaildata['email_subject'], $templateHTML, '','Order Approved');
    return $emailresponse;
}

function rede_email_send($from, $fromname, $to, $toname, $subject, $html, $text, $category = 'default'){
	$config = get_config();
	$url = 'https://api.sendgrid.com/';
	$apiKey = $config['SendGridAPIKey'];

	$json_string = array(
	  'category' => $category
	);


	$params = array(
	    // 'api_user'  => $user,
	    // 'api_key'   => $pass,
	    'x-smtpapi' => json_encode($json_string),
	    'to'		=> $to,
	    'subject'   => $subject,
	    'html'      => $html,
	    'text'      => $text,
	    'from'      => $from,
	  );

    $headr = array();
    $headr[] = 'Authorization: Bearer ' . $apiKey;


	$request =  $url.'api/mail.send.json';

	// Generate curl request
	$session = curl_init($request);
    // Add authorization header
    curl_setopt($session, CURLOPT_HTTPHEADER,$headr);
	// Tell curl to use HTTP POST
	curl_setopt($session, CURLOPT_POST, true);
	// Tell curl that this is the body of the POST
	curl_setopt($session, CURLOPT_POSTFIELDS, $params);
	// Tell curl not to return headers, but do return the response
	curl_setopt($session, CURLOPT_HEADER, false);
	// Tell PHP not to use SSLv3 (instead opting for TLS)
	if (!defined('CURL_SSLVERSION_TLSv1_2')) { define('CURL_SSLVERSION_TLSv1_2',6); }
	curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	// obtain response
	$response = curl_exec($session);
	curl_close($session);

    $response = json_decode($response);

	// print everything out
	return $response;
}

function get_email_template($template){
	// ob_start();
	// require(REDE_PLUGIN_PATH . "emails/".$template);
	// $out = ob_get_clean();
	$out = file_get_contents(REDE_PLUGIN_URL . "emails/".$template);
	return $out;
}

function generateOrderInfoHTML($order_id){
    $html = "";

    $html .= "<tr style ='border-left: 1px solid #cccccc;border-right: 1px solid #cccccc'>";
    $html .= "<td style='font-family:Arial,Helvetica,sans-serif;color:#666666;border-collapse:collapse;padding:0px;border-spacing:0;background-color:#fff;width:512px;'>";
    $html .= "<table width='594' style='border-collapse:collapse; border-spacing:0;'>";
    $html .= "<thead width='594' style='font-family: Arial, Helvetica, Sans-serif; padding:0; margin:0;'>";
    $html .= "</thead >";
    $html .= "<tbody>";
    $html .= "<tr>";
    $html .= "<td width='200' valign='top' style='font-family: Arial, Helvetica, Sans-serif; font-size:12px; color:#666666; text-align: left;padding: 15px 20px;border-bottom: solid 1px #ccc;'>";

    $html .= '<table class="table table-striped border">';
    $html .= get_order_deta_comments($order_id);
    $html .= "</table>";

    $html .= '<table class="table table-striped border">';
    $html .= get_orderdata_as_table($order_id, true);
    $html .= "</table>";

    $html .= "</td>";
    $html .= "</tr>";
    $html .= "</tbody>";
    $html .= "</table>";
    $html .= "</td>";
    $html .= "</tr>";

    return $html;
}

function generateOrderButton($order_id){
    $html = '<tr style="border-left: 1px solid #cccccc;border-right: 1px solid #cccccc; background-color:#fff;">';
    $html .= '    <td align="center" style="text-align:center; margin:0; padding:0; width:50%; border-collapse:collapse; border-spacing:0;">';
    $html .= '        <table style="height: 40px; background-color: #2491d7 transparent;" width="200" cellspacing="0" cellpadding="0" align="center">';
    $html .= '            <tbody>';
    $html .= '                <tr>';
    $html .= '                    <td style="border-radius: 3px; text-align: center; background-color: #B62B27; color: #ffffff; font-size: 14px; line-height: 20px;"><a style="text-decoration: none; color: #ffffff;font-family: Arial, Helvetica, sans-serif; " href="' . home_url('/review-center') . '?order-id='. $order_id .'">View Order</a></td>';
    $html .= '                </tr>';
    $html .= '            </tbody>';
    $html .= '        </table>';
    $html .= '    </td>';
    $html .= '</tr>';

    return $html;
}