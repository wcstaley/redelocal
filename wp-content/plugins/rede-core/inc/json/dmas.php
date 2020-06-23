<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../../wp-load.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('If-Modified-Since: 0');

$locations = $StoreLocator->rede_get_all_top_dmas();

// print_r($locations);
// die();

$counter = 0;
$stores = array();
foreach($locations as $location){
	if($location->board_name === "All Other"){
    	$rank = 51;
    } else {
    	$counter++;
    	$rank = $counter;
    }
    $tmpStore = array(
	    "rank" => $rank,
	    "dma" => $location->board_name,
	    "total" => $location->count
	);

	array_push($stores, $tmpStore);

}

echo json_encode($stores);
die();