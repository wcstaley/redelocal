<?php

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
		$firstrow = true;
		$columns = [];

		$currentType = "";
		$currentBanner = "";

		$counter_total = 0;
		$counter_working = 0;
		$missing = [];

		foreach ($objWorksheet->getRowIterator() as $row) {
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(FALSE);

		    $index = 0;
		    $tmpData = array();

		    foreach ($cellIterator as $cell) {

		    	if($firstrow){
		    		array_push($columns, $cell->getValue());
		    	} else {

					// locator_get_entry($id)

		    		if($columns[$index] === "Type" && !empty($cell->getFormattedValue())){
		    			$currentType = trim($cell->getFormattedValue());
		    			$jsonData[$currentType] = array();
		    		} else if($columns[$index] === "Banner" && !empty($cell->getFormattedValue())){
		    			$currentBanner = trim($cell->getFormattedValue());
		    			$jsonData[$currentType][$currentBanner] = array();
		    		} else if($columns[$index] === "||__name" && empty($cell->getFormattedValue())){
		    			continue 2;
		    		} else if(in_array($columns[$index], array("||__num"))) {
		    			$tmpData[$columns[$index]] = intval($cell->getFormattedValue());
		    		} else if($columns[$index] === "||__val") {

						$table_name = $wpdb->prefix . "locator3";
						$where_clause = esc_sql( $tmpData["||__name"] );
						$location = $wpdb->get_row(
							"
							SELECT *
							FROM $table_name
							WHERE chain LIKE '$where_clause'
							"
						);

						// print_r($location);
						// die();
						if(!empty($location)){
							$tmpData[$columns[$index]] = $location->id;
							$counter_working++;
						} else {
							$tmpData[$columns[$index]] = "";
							array_push($missing, $where_clause);
						}
						$counter_total++;

		    		} else if($columns[$index] === "||__name"){
						$tmpData[$columns[$index]] = $cell->getFormattedValue();
					}

		    		// $tmpData[$columns[$index]] = $cell->getFormattedValue();
		    	}
				
		    	$index++;
		    }
		    if(!empty($currentType) && !empty($currentBanner)){
			    array_push($jsonData[$currentType][$currentBanner], $tmpData);
			}

		    $firstrow = false;
		}
	}

	print_r(
		array(
			"worked" => $counter_working,
			"total" => $counter_total,
			"missing" => $missing
		)
	);
	die();

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
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html><?php } 

die();?>