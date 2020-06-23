<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function get_config(){

	$config = array();
	$config['datetime_format'] = "m/d/Y G:i:s";
	$config['datetime_format_simple'] = "m/d/Y";
	$config['datetime_format_sql'] = "Y-m-d H:i:s";
	$config['SendGridKeyName'] = "e8ZbI7ulR8mIf3CXm0H8ww";
    $config['SendGridAPIKey'] = "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";
    $config['helpemail'] = "support@rede-marketing.com";
    $config['_helpemail'] = "support@rede-marketing.com";
    //$config['_pfemail'] = "jbishop@ampagency.com";
    $config['_pfemail'] = "kevin.forbush@alliedprinting.com";

	return $config;
}

function get_stores(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/stores.json");
	// $string = file_get_contents("json/stores.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_socialmobilebillboard_stores(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/socialmobilebillboard-stores.json");
	// $string = file_get_contents("json/stores.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_sampling_stores(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/sampling_stores.json");
	// $string = file_get_contents("json/stores.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_ooo_stores(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/updated_stores.json");
	// $string = file_get_contents("json/stores.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_dmas(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/dmas.json");
	// $string = file_get_contents("json/dmas.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_storeboards_dmas(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/storeboards_dmas.json");
	// $string = file_get_contents("json/dmas.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_top_dmas(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/top_dmas.json");
	// $string = file_get_contents("json/dmas.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_sas_at_shelf_stores(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/sas-at-shelf-stores.json");
	// $string = file_get_contents("json/dmas.json");
	$json_a = json_decode($string, true);
	return $json_a;
}

function get_harvard_dmas(){
	global $StoreLocator;
	$dmas = $StoreLocator->rede_get_all_dmas();
	$dmas = json_encode($dmas);
	$dmas = json_decode($dmas, true);
	return $dmas;
}
function get_brands(){
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	
	$all_brands = get_all_brands();

	get_user_meta($current_user_id, $key, $single);

	$brands = array();

	foreach($all_brands as $brand){
		$brands[] = array(
	        'id' => $brand,
	        'brand' => $brand
	    );

	}

	return $brands;
}

function get_sas_tactics_default(){
	$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/sas-tactics-default.json");
	// $string = file_get_contents("json/stores.json");
	$json_a = json_decode($string, true);
	return $json_a;
}