<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../../wp-load.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('If-Modified-Since: 0');

$locations = $StoreLocator->locator_get_grouped();

$stores = array();
foreach($locations as $location){
	$channel = trim($location->channel);
	$chain = trim($location->chain);
	$chain_name = trim($location->chain_name);
	$count = $location->count;
	$id = $location->id;

	if(!isset($stores[$channel])){
		$stores[$channel] = array();
	}

	if(!isset($stores[$channel][$chain])){
		$stores[$channel][$chain] = array();
		$tmpStore = array(
			"name" => $chain,
			"num" => 0,
			"val" => $id,
			"id" => 0
		);
		array_push($stores[$channel][$chain], $tmpStore);
	}

	$parent_id = $stores[$channel][$chain][0]["val"];
	$num_child_stores = count($stores[$channel][$chain]);

	$all_stores = array();
    $children = $StoreLocator->locator_lookup_by_parent($id);
    foreach($children as $child){
        array_push($all_stores, $child->id);
    }

    // $billboards = $StoreLocator->billboards_get_stores($all_stores);

	$tmpStore = array(
		"name" => $chain_name,
		"num" => (int)$count,
		"val" => $parent_id . '-' . $num_child_stores,
		"id" => (int)$id
	);

	array_push($stores[$channel][$chain], $tmpStore);

}

echo json_encode($stores);
die();