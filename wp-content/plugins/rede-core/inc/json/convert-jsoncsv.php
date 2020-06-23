<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../../wp-load.php');

function lookupStoreVal($storename, $parent = false){

    $string = file_get_contents(REDE_PLUGIN_PATH . "inc/json/stores.json");
    $json_a = json_decode($string, true);

    foreach ($json_a as $storetype=>$storeGroup){
        $group = $storetype;
    
        foreach ($storeGroup as  $storechain=>$storeList){
            $chain = $storechain;
    
            foreach ($storeList as $store){
                
                if($parent === false && $store['num'] === 0){
                    continue;
                }

                $clean_a = str_replace(' ', '', $store['name']);
                $clean_a = trim(strtolower($clean_a));

                $clean_b = str_replace(' ', '', $storename);
                $clean_b = trim(strtolower($clean_b));

                if($clean_a === $clean_b){
                    return $store['val'];
                }
            }
        }
    }

    return "";
}

if(isset($_POST["submit"])) {
	global $wpdb;

	require_once dirname(__FILE__) . '/../../libs/PHPExcel/Classes/PHPExcel.php';

	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
	$cacheSettings = array( 'memoryCacheSize ' => '8MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
	$file = $_FILES["fileToUpload"];

	if(!isset($file)){
		$jsonData = "Error: file not set<br>";
	} else if (isset($file["error"]) && $file["error"] > 0) {
	    $jsonData = "Error: " . $file["error"] . "<br>";
	} else {
		$objReader = PHPExcel_IOFactory::createReader('CSV');
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($file["tmp_name"]);

		$objWorksheet = $objPHPExcel->getActiveSheet();

		$jsonData = [];

		$currentChannel = "";
        $currentParent = "";
        $currentParentIndex = 0;

		$counter_total = 0;
		$counter_working = 0;
        $missing = [];
        $usedIds = array();

        $iterator = 900;

		foreach ($objWorksheet->getRowIterator() as $row) {
            $iterator++;
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(FALSE);

		    $index = 0;
		    $tmpData = array();

		    foreach ($cellIterator as $cell) {
                $formattedValue = trim($cell->getFormattedValue());

                if($index === 0){
                    $currentChannel = $formattedValue;
                    if(!isset($jsonData[$currentChannel])){
                        $jsonData[$currentChannel] = array();
                    }
                }

                if($index === 1){
                    $currentParent = $formattedValue;
                    if(!isset($jsonData[$currentChannel][$currentParent])){
                        $currentParentIndex = 0;
                        $jsonData[$currentChannel][$currentParent] = array();

                        $tmpVal = lookupStoreVal($currentParent, true);
                        if(empty($tmpVal)){
                            $tmpVal = (string)$iterator;
                        }

                        array_push($jsonData[$currentChannel][$currentParent], array(
                            "name" => $currentParent,
                            "num" => 0,
                            "val" => $tmpVal
                        ));
                    }
                }
                
                if($index === 2){
                    $tmpData["name"] = $formattedValue;
                }

                if($index === 3){
                    $tmpData["num"] = $formattedValue;
                }

		    	$index++;
            }

            $currentParentIndex++;
            //$tmpData['val'] = lookupStoreVal($currentParent);
            if(empty($tmpData['val'])){
                $tmpData['val'] = $jsonData[$currentChannel][$currentParent][0]['val'] . "-" . $currentParentIndex;
            }

		    if(!empty($currentChannel) && !empty($currentParent)){
			    array_push($jsonData[$currentChannel][$currentParent], $tmpData);
			}
		}
	}


	$output = array();
	if(isset($file["name"]) && isset($file["type"]) && isset($file["size"])){
		$output['name'] =  $file["name"];
		$output['type'] =  $file["type"];
		$output['size'] =  ($file["size"] / 1024);
	}
	$output['tmp_name'] =  $file["tmp_name"];
	$filename = explode('/', $file["tmp_name"]);
	$output['filename'] =  array_pop($filename);
	$output['data'] = $jsonData;

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header('If-Modified-Since: 0');

	date_default_timezone_set('America/New_York');
	ignore_user_abort(true);

	ini_set('max_execution_time', 360);
	ini_set('default_socket_timeout', 360);
	ini_set('memory_limit', '2048M');
	set_time_limit(360);

	echo json_encode($jsonData);
} else {
?><!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload CSV" name="submit">
</form>

</body>
</html><?php } 

die();?>