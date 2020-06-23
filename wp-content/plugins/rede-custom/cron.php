<?php
function cron_get_all_vendors(){
	$args = array(
		'role' => 'rede_vendor',
		'orderby' => 'user_nicename',
		'order' => 'ASC'
	);
	$emails = array();
	$subscribers = get_users($args);
	if($subscribers){
		foreach($subscribers as $subscriber){
			$emails[] = $subscriber->user_email;
		}
	}
	return $emails;
}

function publix_set_email_content_type(){
    return "text/html";
}
add_filter('wp_mail_content_type','publix_set_email_content_type');

function send_vendor_email($email, $entry){
	$entry_link = get_site_url().'/form-received/?entry='.$entry['id'];
	$user_message = __(
	'
		<table style="width: 100%; background: #eaeaea;">
		<tbody>
		<tr>
		<td style="padding: 10px 20px; background: #eaeaea;" align="center" valign="middle">
		<table style="width: 640px; padding: 20px; background: #ffffff;" align="center">
		<tbody>
		<tr>
		<td>
		<p style="font-size: 24px; text-align: center; font-weight: bold; padding-bottom: 10px; color: #444444;"><img class="aligncenter" src="https://dotthinkdesign.com/wp-content/uploads/2020/05/publix-mark-email-logo.jpg" alt="" /></p>
		<p style="font-size: 18px; text-align: center; font-weight: bold; padding-bottom: 10px; color: #444444;">Today is the final notification approval date. Please finalize approval.</p>
		<a style="display: inline-block; background-color: #419639; margin-top: 10px; padding: 15px 40px; text-align: center; float: none; margin: auto 0; color: #ffffff; text-transform: uppercase; font-weight: bold; font-size: 13px; text-decoration: none;" href="'.$entry_link.'">View the order</a></p>
		<p style="font-size: 24px; text-align: center; font-weight: bold; padding: 0px !important; margin: 0px !important;"><a><img class="aligncenter" src="https://dotthinkdesign.com/wp-content/uploads/2020/05/email-ad.jpg" alt="" /></a></p>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
	'
	);
	return wp_mail($email, 'Order Approval Notification Date Reminder', $user_message);
}

function get_calendar_data($key){
	$calendar_entries = get_field('coop_cal_entry', 'options');
	foreach($calendar_entries as $calendar_entry){
		if($calendar_entry['unique_id'] == $key){
			return $calendar_entry;
		}
	}
	return false;
}

add_action('check_coop_calendar_notices', 'check_coop_calendar_notices_function');
function check_coop_calendar_notices_function(){
	
	$publix_form = GFAPI::get_form(6);
	$calendar_field_id = 0;
	if(!$publix_form){
		error_log("couldnt find form in check_coop_calendar_notices()");
		return;
	}
	foreach($publix_form['fields'] as $field){
		if($field->type == 'publix_coop_calendar'){
			$calendar_field_id = $field->id;
			break;
		}
	}
	
	$all_entries = GFAPI::get_entries(array('6'));
	
	if(!$all_entries){
		error_log("No entries for form ID 6 in cron");
		return;
	}
	foreach($all_entries as $entry){
		$calendar_entry = get_calendar_data($entry[$calendar_field_id]);
		if($calendar_entry){
			$approval_notification_date = $calendar_entry['approval_notification_date'];
			$today = date("mdY");
			$approval = date("mdY", strtotime($approval_notification_date));
			if($today == $approval){
				$vendor_emails = cron_get_all_vendors();
				if(!empty($vendor_emails)){
					foreach($vendor_emails as $vemail){
						send_vendor_email($vemail, $entry);
					}
				}
			}
		}
	}
	
}