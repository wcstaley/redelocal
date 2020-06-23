<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once REDE_PLUGIN_PATH . '/libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function rede_invoice_pdf_link($order_id){
	return home_url() . '?createpdf&order-id=' . $order_id;
}

function rede_campaign_csv_link(){
	return home_url() . '?campaigndownload';
}

function rede_add_comment($order_id, $comment){
	$current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_info = get_userdata($user_id);
	$time = current_time('mysql');
	
	$commentdata = array(
		'comment_post_ID' => $order_id, // to which post the comment will show up
		'comment_author' => $user_info->first_name . ' ' . $user_info->last_name, //fixed value - can be dynamic 
		'comment_author_email' => $user_info->user_email, //fixed value - can be dynamic 
		'comment_author_url' => $user_info->user_url, //fixed value - can be dynamic 
		'comment_content' => $comment, //fixed value - can be dynamic 
		'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
		'comment_parent' => 0, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
		'user_id' => $user_id, //passing current user ID or any predefined as per the demand
	);

	//Insert new comment and get the comment ID
	$comment_id = wp_new_comment( $commentdata );
	return $comment_id;
}

function rede_create_invoice_pdf($order_id){
	$config = get_config();
	$order_id = $_GET['order-id'];
    $order_data = get_all_meta($order_id);
    $timestamp = get_the_date(  $config['datetime_format_simple'], $order_id );
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    if(!isset($order_data["tax"]) || empty($order_data["tax"])){
        $order_data["tax"] = 0;
    }
    if($order_data["tax"] > 0){
        $order_data["totalwtax"] = (int)$order_data["total"] + (int)$order_data["tax"];
    } else {
        $order_data["totalwtax"] = $order_data["total"];
    }


	// instantiate and use the dompdf class
	$dompdf = new Dompdf();

	// To avoid any unexpected rendering issues
	// $dompdf->set_option('isHtml5ParserEnabled', true);
	$dompdf->set_option('DOMPDF_ENABLE_CSS_FLOAT ', true);

	// $brand-red: #a72027;
	// $brand-most-light-gray: #fafafa;
	// $brand-more-light-gray: #f9f9f9;
	// $brand-very-light-gray: #e6e6e6;
	// $brand-lighter-gray: #d2d2d2;
	// $brand-light-gray: #bcc2c8;
	// $brand-gray: #76838f;
	// $brand-dark-gray: #37474f;

	$testHtml = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
			<style>
				body {
					color: #7d7a7a;
					font-family: "trebuchet ms", verdana, sans-serif;
				}
				table { 
					border-bottom: 1px solid #E5D9C3; 
					border-collapse: separate;
					width: 80%;
					margin: 20px auto;
				}
				table tr {

				} 
				table tr th {
					background: #fafafa;
					border: 1px solid #e6e6e6; 
				} 
				table tr td {
					border: 1px solid #e6e6e6; 
				} 
				.row {
					max-width: 70rem;
				    margin-top 50px auto;
				}
				.row::before, .row::after {
				    display: table;
				    content: " ";
				    -webkit-flex-basis: 0;
				    -ms-flex-preferred-size: 0;
				    flex-basis: 0;
				    -webkit-order: 1;
				    -ms-flex-order: 1;
				    order: 1;
				}
				.row::after {
				    clear: both;
				}
				.column,
				.columns {
				    width: 100%;
				    float: left;
				    padding-right: 0;
    				padding-left: 0;
				}
				.medium-3 {
				    width: 33%;
				}
				.first-col {
					padding-left: 0;
				}
				.invoice-title {
					position: relative;
					max-width: 80%;
					margin: 20px auto;
				}
				.invoice-title img {
					height: 37px;
					margin-top: 10px;
				}
				.invoice-title span {
					display: block;
					position: absolute;
					top: 20px;
    				left: 40px;
				    font-weight: 500;
				    font-size: 18px;
				    line-height: 22px;
				    color: #76838f;
				}
				.invoice-header {
					max-width: 80%;
					margin: 20px auto;
				}
				.invoice-header ul {
				    padding: 0;
				}
				ul.main-client{
				    padding-left: 0;
				    padding-right: 10px;
				}
				ul.invoice-details{
				    padding-left: 10px;
				    padding-right: 10px;
				}
				ul.send-contact {
				    padding-left: 10px;
				    padding-right: 0;
				}
				.invoice-header li {
				    list-style: none;
				}
				.invoice-header li.list-header {
				    background: #a72027;
				    color: #fff;
				    text-transform: uppercase;
				    padding: 2px 5px;
				    font-weight: 500;
				}
				.align-right {
					text-align: right;
				}
				.align-center {
					text-align: center;
				}
			</style>
		</head>

		<body>
			<div class="invoice-title">
				<img style="" src="wp-content/themes/FoundationPress/dist/assets/images/Logo/rede_100.jpg"/>
				<span>Red/E Marketing</span>
			</div>

            <div class="row invoice-header">   
                <div class="medium-3 first-col columns"> 
                    <ul class="main-client">
                        <li class="list-header">Red/E</li>
                        <li><strong>' . $current_user->first_name . ' ' . $current_user->last_name . '</strong></li>
                        <li>Email: ' . $current_user->user_email . '</li>
                    </ul>
                </div>
                <div class="medium-3 columns"> 
                    <ul class="invoice-details">
                        <li class="list-header">Invoice Details</li>
                        <li><strong>Invoice #</strong> <span>' . $order_id . '</span></li>
                        <li><strong>Invoice Date</strong> <span>' . $timestamp . '</span></li>
                        <li><strong>Due Date:</strong> <span>' . date($config['datetime_format_simple'], strtotime($order_data["_enddate"])) . '</span></li>
                    </ul>
                </div>
                <div class="medium-3 columns detail-container">
                    <ul class="send-contact">
                        <li class="list-header">Please Send Payment To</li>
                        <li><strong>Red/E</strong></li>
                        <li>246 Morehouse Rd, Easton CT 06612</li>
                        <li>(203) 219-8103</li>
                    </ul>
                </div>
            </div>  

			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>PO Number</th>
						<th>Sales Rep</th>
						<th>FOB</th>
						<th>Ship Via</th>
						<th>Terms</th>
						<th>Tax ID</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>'. date($config['datetime_format_simple'], strtotime($order_data["_startdate"])).'</td>
						<td></td>
						<td>R. Barker</td>
						<td>N/A</td>
						<td>N/A</td>
						<td>45 days</td>
						<td>46-3346955</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Summary</th>
                        <th>Quantity / Store</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-center">
                            <strong>' . get_the_title( $order_id ) .'</strong><br/>
                            <span class="clearfix">(' . $order_data["type"] .')</span><br/>
                            <div>Number of Stores: ' . $order_data["storecount"] .'</div>
                        </td>
                        <td class="align-center">' . ((isset($order_data["quantity"]) && !empty($order_data["quantity"])) ? $order_data["quantity"] : 'N/A' ) .'</td>
                        <td>' . $order_data["total"] .'</td>
                    </tr>

                    <tr>
                        <td class="align-center"></td>
                        <td class="align-right">
                            <strong>Subtotal:</strong><br/>
                            <strong>Tax:</strong>
                        </td>
                        <td>
                            <span>' . $order_data["total"] .'</span><br/>
                            <span>' . $order_data["tax"] .'</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-center">
                            <span>Due on '. date($config['datetime_format_simple'], strtotime($order_data["_enddate"])).'</span>
                        </td>
                        <td class="align-right">
                            <strong>Total:</strong>
                        </td>
                        <td>
                            <span>' . $order_data["totalwtax"] .'</span>
                        </td>
                    </tr>
                </tbody>
            </table>
		</body>
	</html>';

	// echo $testHtml;
	// die();


	$dompdf->loadHtml($testHtml);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'landscape');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	$dompdf->stream();
}

function user_check_role($role){
	$user = wp_get_current_user();
	// print_r($user->roles);
	// die();
	if ( in_array( $role, (array) $user->roles ) ) {
	    return true;
	} else {
		return false;
	}
}

function format_price($price){
  $format_price = number_format((int)$price, 2);
  return '$' . $format_price;
}

function set_start_status($order_id){
	$order_data = get_all_meta($order_id);
	
	$status = 'Review Order';
	if(isset($order_data['tactic-custom']) && !empty($order_data['tactic-custom'])){
		$status = 'Needs Creative';
	}
	if(isset($order_data['pfid']) && !empty($order_data['pfid'])){
		$status = 'Review Proof';
	}
	if(isset($order_data['filename']) && !empty($order_data['filename'])){
		$status = 'Review Creative';
	}
	
	update_post_meta( $order_id, 'order_status', $status );
}

function get_all_meta($postID){
  global $wpdb;
  $sql = "SELECT meta_key, meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = " . $postID;
  $results = $wpdb->get_results( $sql  , OBJECT );

  $pairs = array();
  foreach($results as $result){
    $pairs[$result->meta_key] = $result->meta_value;
  }
  return $pairs;
}

function get_user_brands(){
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$brands = get_user_meta($user_id, 'brands', true);
	// $brands = maybe_unserialize($brands);
	// print_r($brands);
	// die();
	$pairs = array();
	if(!empty($brands)){
		foreach($brands as $brand){
			if(!in_array($brand, $pairs)){
				$pairs[] = $brand;
			}
		}
	}

	return $pairs;
}

function get_user_shippingaddress(){
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$shippingaddresss = get_user_meta($user_id, 'shippingaddress', true);
	// $shippingaddresss = maybe_unserialize($shippingaddresss);
	// print_r($shippingaddresss);
	// die();
	$pairs = array();
	if(!empty($shippingaddresss)){
		foreach($shippingaddresss as $shippingaddress){
			if(!in_array($shippingaddress, $pairs)){
				$pairs[] = $shippingaddress;
			}
		}
	}

	return $pairs;
}

function get_all_brands(){
	global $wpdb;
	$sql = "SELECT meta_key, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'brands'";
	$results = $wpdb->get_results( $sql  , OBJECT );

	$pairs = array();
	if(!empty($results)){
		foreach($results as $result){
			$brands = maybe_unserialize($result->meta_value);
			if(!empty($brands)){
				foreach($brands as $brand){
					if(!in_array($brand, $pairs)){
						$pairs[] = $brand;
					}
				}
			}
		}
	}
	return $pairs;
}

function get_order_deta_comments($order_id){
	// Return blank if no comments
	$args = array(
		'post_id' => $order_id,
		'count' => true
	);
	$comment_count = get_comments($args);
	if($comment_count <= 0){
		return "";
	}			

	$config = get_config();
	$html = "";
	$args = array(
		'offset' => 0,
		'post_id' => $order_id,
	);
	$comments = get_comments($args);
	$html .= '<tr>';
    $html .= '    <td colspan="2" style="font-size: 14px;font-weight: bold; padding-right: 10px;padding-top:20px;">Order Comments</td>';
    $html .= '</tr>';
	foreach($comments as $comment) :
		$comment_date = date($config['datetime_format'], strtotime($comment->comment_date));
		$html .= '<tr>';
        $html .= '    <td style="vertical-align: top; padding-right: 10px;color:#B62B27;padding-top:5px;padding-bottom:5px;border-bottom: 1px solid #eee;">' . $comment_date . ": <span style='font-weight:bold;'>" . $comment->comment_author . '</span></td>';
        $html .= '    <td style="vertical-align: top;padding-top:5px;padding-bottom:5px;border-bottom: 1px solid #eee;" class="summary-total-stores">' . $comment->comment_content . '</td>';
        $html .= '</tr>';
	endforeach;
	$html .= '<tr>';
    $html .= '    <td colspan="2" style="font-size: 14px;font-weight: bold; padding-right: 10px;padding-top:20px;">Order Details</td>';
    $html .= '</tr>';

	return $html;
}

function get_orderdata_as_table($order_id, $withstores = false, $showtotal = true, $showcomments = true){

	$order_data = get_all_meta($order_id);

	$nice_fieldnames = get_nice_fieldnames();
    $allowed_fields = $nice_fieldnames["allowed_fields"];
    $pricing = $nice_fieldnames["pricing"];
    $allowed_checkboxes = $nice_fieldnames["allowed_checkboxes"];
    $allowed_files = $nice_fieldnames["allowed_files"];
    $allowed_comments = $nice_fieldnames["allowed_comments"];

    $html = "";

	foreach($allowed_fields as $fieldname=>$fieldlabel){
	    if (isset($order_data[$fieldname]) && !empty($order_data[$fieldname])){
	    	$fieldvalue = $order_data[$fieldname];
	        $trimfields = array('campaignobjective', 'campaignpurpose');
	        if(in_array($fieldname, $trimfields)){
	            $fieldvalue = explode(' – ', $fieldvalue);
	            $fieldvalue = $fieldvalue[0];
	        }
			$numberfields = array('quantity', 'storecount', 'billboardcount', 'dest-quantity');
			if(in_array($fieldname, $numberfields)){
	        	$fieldvalue = number_format((int)$fieldvalue);
	        }

	        $html .= '<tr>';
	        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">' . $fieldlabel . '</td>';
	        $html .= '    <td class="summary-total-stores">' . $fieldvalue . '</td>';
	        $html .= '</tr>';
	    }
	}

	if (isset($order_data['demographics']) && !empty($order_data['demographics'])){
		$demographics = maybe_unserialize($order_data['demographics']);
		if(is_string($demographics)){
			$demographics = str_replace(',9', '#', $demographics);
			$demographics = str_replace(',0', '@', $demographics);
	    	$demographics = str_replace(',', '^', $demographics);
	    	$demographics = str_replace('#',',9', $demographics);
	    	$demographics = str_replace('@', ',0', $demographics);
        	$demographics = explode('^', $demographics);
        }
		$htmldemographics = '<ul>';
		foreach($demographics as $demographic){
			$htmldemographics .= '<li>' . $demographic . '</li>';
		}
		$htmldemographics .= '</ul>';

		$html .= '<tr>';
        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">Demographics</td>';
		$html .= '    <td class="summary-total-stores">' . $htmldemographics . '</td>';
		$html .= '</tr>';
	}

	foreach($allowed_checkboxes as $fieldname=>$fieldlabel){
	    if (isset($order_data[$fieldname]) && !empty($order_data[$fieldname])){

	        $html .= '<tr>';
	        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">' . $fieldlabel . '</td>';
	        $html .= '    <td class="summary-total-stores">Yes</td>';
	        $html .= '</tr>';

	    }
	}

    if (isset($order_data['pfid']) && !empty($order_data['pfid'])){
        $html .= '<tr>';
        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">Creative</td>';
        $html .= '    <td class="summary-total-stores"><a target="_blank" href="http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?UserName=dalim20151&DocID=' . $order_data['pfid'] . '">Download</a></td>';
        $html .= '</tr>';

    }

    if (isset($order_data['_pageflex_order']) && !empty($order_data['_pageflex_order'])){
    	$pf_order = maybe_unserialize($order_data['_pageflex_order']);
        $html .= '<tr>';
        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">Pageflex Order ID</td>';
        $html .= '    <td class="summary-total-stores">'.$pf_order[1].'</td>';
        $html .= '</tr>';
    }
	
	foreach($allowed_files as $fieldname=>$fieldlabel){
	    if (isset($order_data[$fieldname]) && !empty($order_data[$fieldname])){

	        $html .= '<tr>';
	        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">' . $fieldlabel . '</td>';
	        $html .= '    <td class="summary-total-stores"><a target="_blank" href="' . $order_data[$fieldname] . '">Download</a></td>';
	        $html .= '</tr>';

	    }
	}

	if($withstores){
		if (isset($order_data["dma"]) && !empty($order_data["dma"])){
			$dmas = maybe_unserialize($order_data["dma"]);
		    if(is_string($dmas)){
		    	$dmas = str_replace(', ', '@', $dmas);
		    	$dmas = str_replace(',', '^', $dmas);
		    	$dmas = str_replace('@', ', ', $dmas);
		    	$dmas = explode('^', $dmas);
		    }

		    $htmlstores = '<ul>';
			foreach ($dmas as $dma){
				$htmlstores .= '<li>' . $dma . '</li>';
			}
			$htmlstores .= '</ul>';

			$html .= '<tr>';
	        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">DMAs</td>';
	        $html .= '    <td class="summary-total-stores">' . $htmlstores . '</td>';
	        $html .= '</tr>';

		}

		if (isset($order_data["store"]) && !empty($order_data["store"])){
			$stores = maybe_unserialize($order_data["store"]);
	        if(is_string($stores)){
	        	$stores = str_replace(', ', '@', $stores);
		    	$stores = str_replace(',', '^', $stores);
		    	$stores = str_replace('@', ', ', $stores);
		    	$stores = explode('^', $stores);
	        }

	       // die();
	        $htmlstores = '<ul>';
	        if($order_data['type'] === 'Security Shroud'){
	        	$allstores = get_dmas();
	        	foreach ($stores as $storename){
	        		if(!is_numeric($storename)){
						$htmlstores .= '<li>' . $storename . '</li>';
					}
				}

			} else if($order_data['type'] === 'Sampling'){
	        	foreach ($stores as $storename){
	        		if(!is_numeric($storename)){
						$htmlstores .= '<li>' . $storename . '</li>';
					}
				}

	        } else {
		        if($order_data['type'] === 'Out of Home'){
		            $allstores = get_ooo_stores();
		        } else {
		        	$allstores = get_stores();
		        }
		        // print_r($allstores);
		        // die();
		        foreach ($allstores as $storetype=>$storeGroup){
		        	foreach ($storeGroup as $storeList){
		            	foreach ($storeList as $store){
		            		if($order_data['type'] === 'Out of Home'){
								if($store['num'] !== 0 && in_array($store['id'], $stores)){
			            			$htmlstores .= '<li>' . $store['name'] . '</li>';
			            		}
			            	// } else if($order_data['type'] === 'Security Shroud'){
			            	// 	if(!is_numeric($storeList['location']) && in_array($storeList['location'], $stores)){
			            	// 		$htmlstores .= '<li>' . $storeList['location'] . '</li>';
			            	// 	}
		            		} else {
			            		if($store['num'] !== 0 && in_array($store['val'], $stores)){
			            			$htmlstores .= '<li>' . $store['name'] . '</li>';
			            		}
			            	}
		            	}
		            }
		        }
		    }
	        $htmlstores .= '</ul>';

			$html .= '<tr>';
	        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">Stores</td>';
	        $html .= '    <td class="summary-total-stores">' . $htmlstores . '</td>';
	        $html .= '</tr>';
		}
	}

	if($showcomments){
		foreach($allowed_comments as $fieldname=>$fieldlabel){
		    if (isset($order_data[$fieldname]) && !empty(trim($order_data[$fieldname]))){
		        $html .= '<tr>';
		        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">' . $fieldlabel . '</td>';
		        $html .= '    <td class="summary-total-stores">' . $order_data[$fieldname] . '</td>';
		        $html .= '</tr>';

		    }
		}
	}

	if($showtotal){
		foreach($pricing as $fieldname=>$fieldlabel){
		    if (isset($order_data[$fieldname]) && !empty($order_data[$fieldname])){
		    	$order_data[$fieldname] = str_replace(' ', '', $order_data[$fieldname]);
		        $html .= '<tr>';
		        $html .= '    <td style="vertical-align: top; font-weight: bold; padding-right: 10px;">' . $fieldlabel . '</td>';
		        $html .= '    <td class="summary-total-stores">' . $order_data[$fieldname] . '</td>';
		        $html .= '</tr>';

		    }
		}
	}

	return $html;
}

function get_first_store($post_id){
	$stores = get_post_meta($post_id, 'store', true);
	$order_data = get_all_meta($post_id);

	if (isset($stores) && !empty($stores)){
		$stores = maybe_unserialize($stores);
        if(is_string($stores)){
        	$stores = str_replace(', ', '@', $stores);
	    	$stores = str_replace(',', '^', $stores);
	    	$stores = str_replace('@', ', ', $stores);
	    	$stores = explode('^', $stores);
        }

        if(isset($order_data['type']) && $order_data['type'] === 'Security Shroud'){
        	$allstores = get_dmas();
        	foreach ($stores as $storename){
        		if(!is_numeric($storename)){
					return $storename;
				}
			}

		} else if(isset($order_data['type']) && $order_data['type'] === 'Sampling'){
        	foreach ($stores as $storename){
        		if(!is_numeric($storename)){
					return $storename;
				}
			}

        } else {
	        if(isset($order_data['type']) && $order_data['type'] === 'Out of Home'){
	            $allstores = get_ooo_stores();
	        } else {
	        	$allstores = get_stores();
	        }
	        foreach ($allstores as $storetype=>$storeGroup){
	        	foreach ($storeGroup as $storeList){
	            	foreach ($storeList as $store){
	            		if(isset($order_data['type']) && $order_data['type'] === 'Out of Home'){
							if($store['num'] !== 0 && in_array($store['id'], $stores)){
		            			return $store['name'];
		            		}
	            		} else {
		            		if($store['num'] !== 0 && in_array($store['val'], $stores)){
		            			return $store['name'];
		            		}
		            	}
	            	}
	            }
	        }
	    }
	} else {
		return false;
	}
}

function convert_html_ul($output){
	$array = explode("<li>", $output);
	$new_array = array();

	//First element will be empty, so remove it
	unset($array[0]);

	// Now remove "</li>" at end of input
	foreach($array as $expl){
		$ai = explode("</li>",$expl);
		array_push($new_array,$ai[0]);
	}

	return implode(', ' , $new_array);
}

function convert_html_a($output){
	preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $output, $result);
	if (!empty($result)) {
		# Found a link.
		return $result['href'][0];
	} else {
		return "";
	}
}