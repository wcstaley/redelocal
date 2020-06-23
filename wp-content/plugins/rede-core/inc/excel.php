<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once dirname(__FILE__) . '/../libs/PHPExcel/Classes/PHPExcel.php';

function rede_buildExcel($title, $dataArray, $filename){
	$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
	$cacheSettings = array( 'memoryCacheSize' => '8MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

	$fileDest = EXCEL_UPLOADS . "/$filename";

	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Red/E")
							 ->setLastModifiedBy("Red/E")
							 ->setTitle($title)
							 ->setSubject($title);

    $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A1');

    $objPHPExcel->setActiveSheetIndex(0);

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter->setOffice2003Compatibility(true);
	//$objWriter->setPreCalculateFormulas(false);
	$objWriter->save($fileDest);

	return $fileDest;

}

function rede_create_billboard_excel($order_id){
	global $StoreLocator;
    $order_data = get_all_meta($order_id);
    $storeList = $order_data['store'];
    $stores = explode(',' , $storeList);
    $billboards = $StoreLocator->billboards_get_stores_by_parents($stores);
    
    // $all_stores = array();
    // foreach($stores as $parent_id){
    //     $locations = $StoreLocator->locator_lookup_by_parent($parent_id);
    //     foreach($locations as $location){
    //         array_push($all_stores, $location->id);
    //     }
    // }
    // $billboard_maps = $StoreLocator->billboards_get_stores($all_stores);
    // $all_billboards = array();
    // foreach($billboard_maps as $billboard_map){
    //     array_push($all_billboards, $billboard_map->billboard_id);
    // }
    // $billboards = $StoreLocator->rede_get_billboards($all_billboards);
    $billboards  = json_encode($billboards);
    $billboards = json_decode($billboards, true);

    $top_row = array('id','Unit Num','Panel Num','Tab Id','Name','Media Style','Postal Code','Lat','Lon');
    array_unshift($billboards, $top_row);
    
    $filename = $order_id . '_i_' . md5($storeList) . '.xlsx';
    $title = 'Out-of-Home Digital Billboards - Order #' . $order_id;

    // Update file based on template
    $tmp_name = rede_buildExcel($title, $billboards, $filename);
    $filename = explode('/', $tmp_name);
	$filename = array_pop($filename);
	$fileurl = content_url() . '/uploads/csv/' . $filename;

	update_post_meta( $order_id, 'filenamebillboards', $fileurl);
	update_post_meta( $order_id, 'fileguidbillboards', $tmp_name);

    return $fileurl;
}

function rede_create_dma_billboard_excel($order_id){
    global $StoreLocator;
    $order_data = get_all_meta($order_id);
    $dmaList = $order_data['dma'];
    $dmaList = str_replace(', ', '@', $dmaList);
    $dmaList = str_replace(',', '^', $dmaList);
    $dmaList = str_replace('@', ', ', $dmaList);
    $dmas = explode('^' , $dmaList);
    $all_zips = array();
    // print_r($dmas);
    // die();
    $zips = $StoreLocator->rede_zips_by_dmas($dmas);
    foreach($zips as $zip){
         array_push($all_zips, $zip->zip);
    }
    $billboards = $StoreLocator->billboards_get_by_zips($all_zips);
    $billboards  = json_encode($billboards);
    $billboards = json_decode($billboards, true);

    $top_row = array('id','Unit Num','Panel Num','Tab Id','Name','Media Style','Postal Code','Lat','Lon');
    array_unshift($billboards, $top_row);
    
    $filename = $order_id . '_i_' . md5($dmaList) . '.xlsx';
    $title = 'Out-of-Home Digital Billboards - Order #' . $order_id;

    // Update file based on template
    $tmp_name = rede_buildExcel($title, $billboards, $filename);
    $filename = explode('/', $tmp_name);
    $filename = array_pop($filename);
    $fileurl = content_url() . '/uploads/csv/' . $filename;

    update_post_meta( $order_id, 'filenamebillboards', $fileurl);
    update_post_meta( $order_id, 'fileguidbillboards', $tmp_name);

    return $fileurl;
}

function rede_create_campaigns_excel($args){
    
    $default_args = array(
        "month" =>  date('n'),
        "month" =>  date('Y'),
        "user"  =>  get_current_user_id()
    );

    $args = wp_parse_args( $args, $default_args );

    if(user_check_role('rede_vendor')){
        $author_query = array(
            'post_type' => 'rede-order',
            'posts_per_page' => '-1',
            'meta_query' => array(
                'user_clause' => array(
                    'key'     => '_vendor',
                    'value'   => $args["user"],
                    'compare' => '=',
                ),
                'status_clause' => array(
                    'key'     => 'order_status',
                    'value'   => 'Pending Confirmation',
                    'compare' => '!=',
                )
            )
        );
    } else {
        $author_query = array(
            'post_type' => 'rede-order',
            'posts_per_page' => '-1',
            'meta_query' => array(
                'user_clause' => array(
                    'key'     => '_user',
                    'value'   => $args["user"],
                    'compare' => '=',
                )
            )
        );
    }

    $author_posts = new WP_Query($author_query);
    $vendor_campaigns = array();
    $top_row = array();
    $has_top_row = false;

    $nice_fieldnames = get_nice_fieldnames();
    
    while($author_posts->have_posts()){
        $author_posts->the_post();
        $order_id = $author_posts->post->ID;
        $order_data = get_all_meta($order_id);
        $data_row = array();

        // print_r($order_data);
        // die();

        // Add extra data before
        if(!$has_top_row){
            array_push($top_row, 'ID');
        }
        array_push($data_row, $order_id); //ID

        // Loop through meta fields
        foreach($nice_fieldnames as $nice_fieldname_cat => $nice_fieldname_array){

            foreach($nice_fieldname_array as $field_name => $nice_fieldname){
                $fieldvalue = $order_data[$field_name];
                $trimfields = array('campaignobjective', 'campaignpurpose');
                if(in_array($field_name, $trimfields)){
                    $fieldvalue = explode(' – ', $fieldvalue);
                    $fieldvalue = $fieldvalue[0];
                }
                $numberfields = array('quantity', 'storecount', 'billboardcount', 'dest-quantity');
                if(in_array($field_name, $numberfields)){
                    $fieldvalue = number_format((int)$fieldvalue);
                }
                
                if(isset($fieldvalue)){
                    if($nice_fieldname_cat === 'allowed_files'){
                        $fieldvalue = format_field_value($field_name, $fieldvalue, $order_data['type']);
                        $fieldvalue = convert_html_a($fieldvalue);
                    } else {
                        $fieldvalue = format_field_value($field_name, $fieldvalue, $order_data['type']);
                    }
                    array_push($data_row, $fieldvalue);
                } else {
                    array_push($data_row, "");
                }
                if(!$has_top_row){
                    array_push($top_row, $nice_fieldname);
                }
            }
        }

        // Add extra data after
        if(!$has_top_row){
            array_push($top_row, get_nice_fieldname('pfid'),get_nice_fieldname('_pageflex_order'),'Demographics', 'Stores', 'DMAs');
        }
        $fieldvalue = format_field_value('pfid', $order_data['pfid'],  $order_data['type']);
        array_push($data_row,  convert_html_a($fieldvalue)); //pfid
        $fieldvalue = format_field_value('_pageflex_order', $order_data['_pageflex_order'],  $order_data['type']);
        array_push($data_row, $fieldvalue); //_pageflex_order
        $fieldvalue = format_field_value('demographics', $order_data['demographics'],  $order_data['type']);
        array_push($data_row, convert_html_ul($fieldvalue)); //Demographics
        $fieldvalue = format_field_value('store', $order_data['store'],  $order_data['type']);
        array_push($data_row, convert_html_ul($fieldvalue)); //Stores
        $fieldvalue = format_field_value('dma', $order_data['dma'],  $order_data['type']);
        array_push($data_row, convert_html_ul($fieldvalue)); //DMAs

        // Add row to data array
        array_push($vendor_campaigns, $data_row);
        $has_top_row = true;
    }

    $vendor_campaigns  = json_encode($vendor_campaigns);
    $vendor_campaigns = json_decode($vendor_campaigns, true);

    // $top_row = array('id','Unit Num','Panel Num','Tab Id','Name','Media Style','Postal Code','Lat','Lon');
    array_unshift($vendor_campaigns, $top_row);

    // print_r($vendor_campaigns);
    
    $filename = 'campaigns_' . md5(serialize($args)) . '.xlsx';
    $title = 'Red/E Marketing - Campaigns';

    // Update file based on template
    $tmp_name = rede_buildExcel($title, $vendor_campaigns, $filename);
    $filename = explode('/', $tmp_name);
    $filename = array_pop($filename);
    $fileurl = content_url() . '/uploads/csv/' . $filename;

    //update_post_meta( $order_id, 'filenamevendorcampaigns', $fileurl);

    wp_redirect($fileurl);
    die('test');
    exit;
}