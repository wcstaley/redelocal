<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../../wp-load.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('If-Modified-Since: 0');

$string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/stores.json");
$json_a = json_decode($string, true);

$title = "Converted";
$dataArray = array();
$filename = "convert.csv";

$group = "";
$chain = "";
$tmpArray = array();
foreach ($json_a as $storetype=>$storeGroup){
    $group = $storetype;

    foreach ($storeGroup as  $storechain=>$storeList){
        $chain = $storechain;

        foreach ($storeList as $store){
            if(!empty($store['num'])){
                $tmpArray = array(
                    'group' => $group,
                    'chain' => $chain,
                    'name' => $store['name'],
                    'num' => $store['num'],
                    'val' => $store['val'],
                );
                array_push($dataArray, $tmpArray);
            }
        }
    }
}

// print_r($dataArray);
// die();

$path = rede_buildExcel($title, $dataArray, $filename);
echo $path;