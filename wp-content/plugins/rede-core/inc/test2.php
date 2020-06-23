<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');

$config = get_config();
$current_user = get_userdata( 1 );
$current_user_id = $current_user->ID;
$post_id = 55;

$templateHTML = get_email_template("OrderCreated.php?order-id=" . $post_id);
$subject = "Order Approved - Order #" . $post_id;
$email_to = $current_user->first_name . ' ' . $current_user->last_name;
$emailresponse = rede_email_send("help@rede.com", "Red/E", $config["_helpemail"], $email_to, $subject, $templateHTML, '','Order Approved');
print_r($emailresponse);
die();