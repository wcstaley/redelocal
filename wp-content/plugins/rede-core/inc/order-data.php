<?php
/**
 * Get all fields with user facing names
 *
 * @return array
 */
function get_nice_fieldnames(){

    $allowed_fields = array(
        'type' => 'Program Type',
        'ordername' => 'Program Name',
        'brand' => 'Brand',
        'spastic' => 'Spastic',
        'marketdate' => 'Market Date',
        'marketdate_out_cycle' => 'Out of Cycle Market Date',
        'enddate' => 'End Date',
        'campaignobjective' => 'Program Objective',
        'campaignpurpose' => 'Program Purpose',
        'campaigntiming' => 'Program Timing',
        'profilegender' => 'Profile Gender',
        'profileage' => 'Profile Age',
        'profilechildren' => 'Profile Children',
        'profileincome' => 'Profile Income',
        'destinationurl' => 'Destination URL',
        'optimization' => 'Optimization Preferences',
        'quantity' => 'Quantity',
        'sku' => 'SKU',
        'storecount' => 'Store Count',
        'billboardcount' => 'Billboard Count',
        'impressions' => 'Impressions',
        'cpm' => 'CPM',
        'producttype' => 'Product Type',
		'categorytype' => 'Category Type',
		'marketdate2' => 'Second Preference Date',
		'timestart' => 'Time Start',
		'timeend' => 'Time End',
		'productname' => 'Product Name',
		'productdesc' => 'Product Description',
		'productunit' => 'Flavor/Unit Size/Pack Size',
		'productupc' => 'Consumer UPC',
		'productsampled' => 'Product Sampled',
		'productfeatured' => 'Product Featured',
		'productbackup' => 'Product Back-up',
		'productdistribution' => 'Product Distribution Method',
		'sellingpoints' => 'Selling Points',
		'preparation' => 'Preparation',
		'equipment' => 'Equipment',
		'distributiongoal' => 'Distribution Goal',
		'productcoupon' => 'Product Coupon',
		'productsupplies' => 'Product Supplies',
		'productcta' => 'Product CTA',
		'productheadline' => 'Product Headline',
		'productsubhead' => 'Product Subhead',
		'productlegal' => 'Product Legal',
		'redecollateral' => 'RedE Collateral',
		'productcollateral' => 'Product Collateral',
		'destination' => 'Destination',
        'dest-email' => 'Destination Email',
        'dest-quantity' => 'Destination Quantity',
        'specialinstructions' => 'Special Instructions',
        'shippingaddress' => 'Shipping Address',
        'shippinginstructions' => 'Shipping Instructions',
        'sasatshelftactic' => 'SAS At Shelf Tactic',
        'sasatshelfquantity' => 'SAS At Shelf Quantity',
        'storedepartment' => 'Store Department',
        'aislequantity' => 'Multiple aisle placement',
        'aisleplacement' => 'Placement Instructions'
    );

    $pricing = array(
        'budget' => 'Budget',
        'costperstore' => 'Cost Per Store',
        'total' => 'Total',
    );

     $allowed_checkboxes = array(
        'upgrade-1' => 'Plus Up 1',
        'upgrade-2' => 'Plus Up 2',
        'upgrade-3' => 'Plus Up 3',
        'upgrade2-custom' => 'Plus Up 2 Red/E Creative',
		'upgrade3-custom' => 'Plus Up 3 Red/E Creative',
        'tactic-custom' => 'Red/E Creative',
        'at-shelf-tactic-custom' => 'At Shelf Tactic Custom'
    );

    $allowed_files = array(
        'filename' => 'Creative',
        'rede_creative' => 'Creative',
        'filenameaudience' => 'Custom Audience',
        'filenamegeography' => 'Custom Geography',
        'filenameseg' => 'Custom Segment',
        'customlistname' => 'Custom List',
        'customshoppername' => 'Custom Shopper Target',
        'filenameupgrade2' => 'Plus Up 2 File',
        'filenameupgrade3' => 'Plus Up 3 File',
        'filenamebillboards' => 'Billboards',
        'filenamesku' => 'Products List',
        'vendor_reports' => 'Report',
        'filenameproductbeauty' => 'Beauty Shot',
        'filenameproductshot' => 'Product Shot',
        'filenameproductlogo' => 'Product Logo',
    );

    $allowed_comments = array(
        'order_status' => 'Order Status',
        'comments' => 'Customer Comments',
        'vendor_comment' => 'Service Provider Comments',
        'otherdetails' => 'Other Details'
    );

    $fields = array(
    	"allowed_fields" => $allowed_fields,
    	"pricing" => $pricing,
    	"allowed_checkboxes" => $allowed_checkboxes,
    	"allowed_files" => $allowed_files,
		"allowed_comments" => $allowed_comments
    );

	return $fields;
}

/**
 * Convert key name to something we can display to the user
 *
 * @param string $key
 * @return string
 */
function get_nice_fieldname($key){
    $allFields = get_nice_fieldnames();
    foreach ($allFields as $fieldArrayName => $fieldArray) {
        if(isset($fieldArray[$key])){
            return $fieldArray[$key];
        }
    }
    
    if($key === '_pageflex_order'){
        return 'Pageflex Order ID';
    }

    if($key === 'pfid'){
        return 'Creative';
    }

    return ucfirst($key);
}

/**
 * Convert key value to something we can display to the user
 *
 * @param string $key
 * @param string $old_value
 * @param string $order_type
 * @return string|int|array
 */
function format_field_value($key, $old_value, $order_type = "On-Pack"){
    $allFields = get_nice_fieldnames();
    $new_value = $old_value;

    if(isset($allFields["allowed_fields"][$key])){

        $splitfields = array('campaignobjective', 'campaignpurpose');
        if(in_array($key, $splitfields)){
            $new_value = explode(' – ', $old_value);
            $old_value = $old_value[0];
        }

        $numberfields = array('quantity', 'storecount', 'billboardcount', 'dest-quantity');
        if(in_array($key, $numberfields)){
            $new_value = number_format((int)$old_value);
        }
    }

    if(isset($allFields["allowed_checkboxes"][$key])){
        if (isset($old_value) && !empty($old_value)){
            $new_value = "Yes";
        } else {
            $new_value = "No";
        }
    }

    if(isset($allFields["allowed_files"][$key])){
        if (isset($old_value) && !empty($old_value)){
            $new_value = '<a target="_blank" href="' . $old_value . '">Download</a>';
        } 
    }


    if ($key === 'pfid'){
        $new_value = '<a target="_blank" href="http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?UserName=dalim20151&DocID=' . $old_value . '">Download</a>';
    }

    if ($key === '_pageflex_order'){
        $pf_order = maybe_unserialize($old_value);
        $new_value = $pf_order[1];
    }

    if ($key === 'demographics'){
		$demographics = maybe_unserialize($old_value);
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
        
        $new_value = $htmldemographics;
    }
    
    if ($key === 'dma'){
        $dmas = maybe_unserialize($old_value);
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

        $new_value = $htmlstores;

    }

    if ($key === "store"){
        $stores = maybe_unserialize($old_value);
        if(is_string($stores)){
            $stores = str_replace(', ', '@', $stores);
            $stores = str_replace(',', '^', $stores);
            $stores = str_replace('@', ', ', $stores);
            $stores = explode('^', $stores);
        }

       // die();
        $htmlstores = '<ul>';
        if($order_type === 'Security Shroud'){
            $allstores = get_dmas();
            foreach ($stores as $storename){
                if(!is_numeric($storename)){
                    $htmlstores .= '<li>' . $storename . '</li>';
                }
            }

        } else if($order_type === 'Sampling'){
            foreach ($stores as $storename){
                if(!is_numeric($storename)){
                    $htmlstores .= '<li>' . $storename . '</li>';
                }
            }

        } else {
            if($order_type === 'Out of Home'){
                $allstores = get_ooo_stores();
            } else {
                $allstores = get_stores();
            }
            // print_r($allstores);
            // die();
            foreach ($allstores as $storetype=>$storeGroup){
                foreach ($storeGroup as $storeList){
                    foreach ($storeList as $store){
                        if($order_type === 'Out of Home'){
                            if($store['num'] !== 0 && in_array($store['id'], $stores)){
                                $htmlstores .= '<li>' . $store['name'] . '</li>';
                            }
                        // } else if($order_type === 'Security Shroud'){
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

       $new_value = $htmlstores;
    }

    return $new_value;
}