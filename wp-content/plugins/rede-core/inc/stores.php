<?php
// If this file is called directly, abort.
ini_set('max_execution_time', 600);
ini_set('default_socket_timeout', 600);
set_time_limit(600);

if ( ! defined( 'WPINC' ) ) {
		die;
}

if (!defined('STORE_LOCATOR_URL')) {
		define('STORE_LOCATOR_URL', plugin_dir_url(__FILE__));
}
if (!defined('STORE_LOCATOR_PATH')) {
		define('STORE_LOCATOR_PATH', plugin_dir_path(__FILE__));
}
if (!defined('STORE_LOCATOR_BASENAME')) {
		define('STORE_LOCATOR_BASENAME', plugin_basename(__FILE__));
}
if (!defined('STORE_LOCATOR_ADMIN')) {
		define('STORE_LOCATOR_ADMIN', admin_url('options-general.php?page=locator-management'));
}

class StoreLocator {

	function init(){

		if(is_admin()){
			add_action('wp_ajax_locator_get_all', array(&$this, 'locator_get_all_callback'));
			add_action('wp_ajax_nopriv_locator_get_all', array(&$this, 'locator_get_all_callback'));
			add_action('wp_ajax_search_products', array(&$this, 'get_products_callback'));
			add_action('wp_ajax_nopriv_search_products', array(&$this, 'get_products_callback'));
			add_action('wp_ajax_locator_process_all', array(&$this, 'locator_process_all'));
			add_action('wp_ajax_nopriv_locator_process_all', array(&$this, 'locator_process_all'));
			add_action('init', array(&$this, 'locator_create_table'));
			add_action('admin_menu', array(&$this, 'add_locator_management' ));
		}
	}

	////////////////////////////////////////////////////////////////
	// Search by lat/lon
	////////////////////////////////////////////////////////////////
	function store_search($lat_lon, $distance = 5){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		// $lat_lon = $this->getRadians($address);
		$lat =  $lat_lon['lat'];
		$lon =  $lat_lon['lon'];
		// https://developers.google.com/maps/articles/phpsqlsearch_v3
		// SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;
		$locations = $wpdb->get_results(
			"
			 SELECT id, chain, channel, chain_num, chain_name, cus_name, address1, address2, city, state, zip, lat, lon, store_num, ( 3959 * acos( cos( radians( $lat ) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians( $lon ) ) + sin( radians( $lat ) ) * sin( radians( lat ) ) ) ) AS distance
			 FROM $table_name
			 HAVING distance < $distance
			 ORDER BY distance
			 LIMIT 0 , 20;
			"
			);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// Generate locator management page
	////////////////////////////////////////////////////////////////
	function locator_management() {
		global $wpdb;
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<h2>Retailer List Management Page</h2>';
		if(isset($_REQUEST['remove'])){
			$remove_id = $_REQUEST['remove'];
			check_admin_referer( 'delete_locator_entry', '_wpnonce' );
			$this->locator_delete_entry($remove_id);
			echo '<div id="message" class="updated below-h2"><p>Removed ID#: ' . $remove_id . '</p></div>';
		} else if (isset($_REQUEST['process'])) {
			$process_id = $_REQUEST['process'];
			check_admin_referer( 'process_locator_entry', '_wpnonce' );
			$this->updateRadians($process_id);
			echo '<div id="message" class="updated below-h2"><p>Updated ID#: ' . $process_id . '</p></div>';
		}

		$dnonce = wp_create_nonce( 'delete_locator_entry' );
		$pnonce = wp_create_nonce( 'process_locator_entry' );
		//$locations = $this->locator_get(30, 0);
		// $locations = $this->locator_get_grouped();
		$locations = $this->locator_get_all();
	 //  $billboards = $this->billboards_get_all();
	 //  //$billboards = $this->locator_get_all();

	 //  echo '<pre>';
	 //  $mappings = array();
	 //  $counter = 0;
	 //  foreach($billboards as $billboard){
	 //    // print_r($billboard);

	 //    // if($billboard->id < 2034){
	 //    // 	continue;
	 //    // }

	 //    foreach($locations as $location){
	 //      $distance = ( 3959 * acos( cos( deg2rad( $billboard->lat ) ) * cos( deg2rad( $location->lat ) ) * cos( deg2rad( $location->lon ) - deg2rad( $billboard->lon ) ) + sin( deg2rad( $billboard->lat ) ) * sin( deg2rad( $location->lat ) ) ) );

	 //      if($counter > 100){
	 //        break 2;
	 //      }
	 //      if( $distance < 10){
	 //        $counter++;
	 //        $new_mapping = array();
	 //        $new_mapping['id'] = $counter;
	 //        $new_mapping['store_id'] = $location->id;
	 //        $new_mapping['billboard_id'] = $billboard->id;
	 //        $new_mapping['distance'] = $distance;

	 //        // insert into database
	 //        // $wpdb->insert(
	 //        //   $wpdb->prefix . "billboard_map2",
	 //        //   $new_mapping
	 //        // );

	 //        // die();

	 //        array_push($mappings, $new_mapping);
	 //        // print_r($store);
	 //      }
	 //    }
	 //  }
		// echo $counter;
	 //  print_r($mappings);
	 //  die();

		echo '</pre>';

		echo '<strong>Total Locations:</strong> ' . count($locations);

		echo '<p><a href="#" id="processsbutton">Process Lat/Lon</a> <div id="processstatus"></div></p>';
		echo '<table cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;">';
		echo '<tr><th style="padding: 0 5px;">Delete</th>
			<th style="padding: 0 5px;">Update Lat/Lon</th>
			<th style="padding: 0 5px;">chain</th>
			<th style="padding: 0 5px;">channel</th>
			<th style="padding: 0 5px;">chain_name</th>
			<th style="padding: 0 5px;">lat/lon</th>
			<th style="padding: 0 5px;">status</th>
			<th style="padding: 0 5px;">billboards</th>';
		foreach($locations as $location){
			if(isset($location) && !empty($location)){
				echo '<tr id="store-'.$location->id.'">';
				// $location->hometown = unserialize($location->hometown);
				$durl = add_query_arg( array('remove' => $location->id, '_wpnonce' => $dnonce) );
				$purl = add_query_arg( array('process' => $location->id, '_wpnonce' => $pnonce) );
				echo '<td style="padding: 0 5px; text-align:center;">[<a href="'.$durl.'">X</a>]</td>';
				echo '<td style="padding: 0 5px; text-align:center;">[<a href="'.$purl.'">Process</a>]</td>';
				echo '<td style="border-left: 1px solid #ddd;padding: 0 5px;">' . $location->chain . '</td>';
				echo '<td style="border-left: 1px solid #ddd;padding: 0 5px;">' . $location->channel . '</td>';
				echo '<td style="border-left: 1px solid #ddd;padding: 0 5px;">' . $location->chain_name . '</td>';
				echo '<td style="border-left: 1px solid #ddd;padding: 0 5px;">' . $location->lat . ',' .  $location->lon . '</td>';
				echo '<td style="border-left: 1px solid #ddd;padding: 0 5px;"></td>';
				echo '<td style="border-left: 1px solid #ddd;padding: 0 5px;"></td>';
				echo '</tr>';

				// 'chain',
				// 'channel',
				// 'chain_num',
				// 'chain_name',
				// 'cus_name',
				// 'address1',
				// 'address2',
				// 'city',
				// 'state',
				// 'zip',
				// 'lat',
				// 'lon',
				// 'store_num',
			}

		}
		echo '</table>';
		echo '</div>';
		?>
		<script>
		jQuery( document ).ready(function($) {
			var processLocations = function(){
				var $status = $('#processstatus');
				//console.log('start processing');

				$.post(
					ajaxurl,
					{
						action: 'locator_process_all'
					},
					function(response) {
						// if($status.text().trim() === ""){
						// 	var oldcount = 0;
						// } else {
						// 	var oldcount = parseInt($status.text(),10);
						// }
						console.log('response', response);
						response = JSON.parse(response);

						// Update count
						//$status.text(oldcount + parseInt(response.count,10));

						if(response.status){
							processLocations();
						}
					}
				);
			};

			$('#processsbutton').on('click', function(e){
				console.log('processsbutton click');
				e.preventDefault();
				processLocations();
			});

		});
		</script>
		<?php

	}

	////////////////////////////////////////////////////////////////
	// Add locator management page
	////////////////////////////////////////////////////////////////
	function add_locator_management() {
		add_options_page( 'Retailer List', 'Retailer List', 'manage_options', 'locator-management', array(&$this, 'locator_management' ));
	}

	function get_products_callback(){
		$apiurl = 'http://productlocator.iriworldwide.com/productlocator/products?';

		$fields = array(
				'client_id' => '297',
				'brand_id' => 'AEVE',
				'group_id' => 'any',
				'output' => 'json'
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $apiurl . $fields_string);

		//curl_setopt($ch,CURLOPT_HTTPHEADER,array('Referer: http://rap.stage.ampagency.com'));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		$result = json_decode($result);
		$result = $result->products->product;

		if(!isset($_REQUEST['search']) || empty($_REQUEST['search'])){
			$output = array();
			foreach($result as $product) {
				$product->upc_name = str_replace("APPLE & EVE ", "", $product->upc_name);
				array_push($output, $product);
			}
			echo json_encode($result);
			wp_die();
		}

		$searchword = $_REQUEST['search'];
		$lowersearch = strtolower($_REQUEST['search']);
		$matches = array();
		foreach($result as $product) {
				$lowertest = strtolower($product->upc_name);
				if (strpos($lowertest, $lowersearch) !== false) {
					$product->upc_name = str_replace("APPLE & EVE ", "", $product->upc_name);
					array_push($matches, $product);
				}
		}

		echo json_encode($matches);
		wp_die();
	}

	function get_product_families(){
		$apiurl = 'http://productlocator.iriworldwide.com/productlocator/products?';
	
		$fields = array(
				'client_id' => '297',
				'brand_id' => 'AEVE',
				'prod_lvl' => 'group',
				'output' => 'json'
		);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $apiurl . $fields_string);

		//curl_setopt($ch,CURLOPT_HTTPHEADER,array('Referer: http://rap.stage.ampagency.com'));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		return json_decode($result);
	}

	////////////////////////////////////////////////////////////////
	// Get data from locator table
	////////////////////////////////////////////////////////////////
	function locator_get_all_callback(){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator";
		$response = array();

		if(isset($_REQUEST['locate']) && !empty($_REQUEST['locate'])){
			$search = $_REQUEST['locate'];
			$distance = 5;

			if(isset($_REQUEST['distance']) && !empty($_REQUEST['distance'])){
				 $distance = $_REQUEST['distance'];
			}
			// Add 'US' if zip code
			// if(preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$search)){
			//   $search .= ', US';
			// }
			$locations = array();
			$apilocations = $this->api_search($search, $distance, 20);
			$lat_lon = $this->getRadians($search);
			$response['query'] = $search;
			$response['search'] = $lat_lon;
			$response['apilocations'] = $apilocations;
			//$response['sqllocations'] = $locations;
			$response['locations'] = $this->merge_results($locations, $apilocations);
		} else {
			//$locations = $this->locator_get(30, 0);
			$response['search'] = false;
			$response['locations'] = array();
		}

		echo json_encode($response);
		wp_die();
	}

	////////////////////////////////////////////////////////////////
	// Merge different result sets
	////////////////////////////////////////////////////////////////
	function merge_results($locations, $apilocations){
		$apiNearbyStores = $apilocations->RESULTS->STORES->STORE;

		foreach($apiNearbyStores as $store){
			$locations[] = array(
				"address1" => $store->ADDRESS,
				"address2" => '',
				"city" => $store->CITY,
				"distance" => $store->DISTANCE,
				"id" => $store->STORE_ID,
				"lat" => $store->LATITUDE,
				"lon" => $store->LONGITUDE,
				"name" => $store->NAME,
				"state" => $store->STATE,
				"logo" => $store->STORE_LOGO,
				"status" => '',
				"zip" => $store->ZIP
			);
		}

		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// AJAX callback to update lat/lon
	////////////////////////////////////////////////////////////////
	function locator_process_all(){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$response = array();

		$locations = $wpdb->get_results(
			"
			SELECT *
			FROM $table_name
			WHERE lat LIKE  ''
			OR lat LIKE  '0.000000'
			LIMIT 0 , 10
			"
		);

		if ( !empty($locations) ){
			$billboards = $this->billboards_get_all();
			$response['status'] = true;
			$response['locations'] = array();
			
			// $test = $this->updateRadians($location->id);

			foreach($locations as $location){
				$tmp_location = array();
				$table_name = $wpdb->prefix . "billboards";
				// $address =  $location->address1 . ' ' . $location->address2 . ' ' . $location->city . ' ' . $location->state . ' ' . $location->zip;
				$lat_lon = $this->updateRadians( $location->id);
				if($lat_lon !== false){
					$lat =  $lat_lon['lat'];
					$lon =  $lat_lon['lon'];
					$distance = 10;
					$tmp_location['id'] = $location->id;
					$tmp_location['billboards'] = array();

					$billboards = $wpdb->get_results(
						"
							SELECT id, unit_num, panel_num, tab_id, name, media_style, postal_code, lat, lon, ( 3959 * acos( cos( radians( $lat ) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians( $lon ) ) + sin( radians( $lat ) ) * sin( radians( lat ) ) ) ) AS distance
							FROM $table_name
							HAVING distance < $distance
							ORDER BY distance
							LIMIT 0 , 20;
						"
					);

					foreach($billboards as $billboard){
						$new_mapping = array();
						$new_mapping['store_id'] = $location->id;
						$new_mapping['billboard_id'] = $billboard->id;
						$new_mapping['distance'] = $billboard->distance;
						$wpdb->insert(
							$wpdb->prefix . "billboard_map3",
							$new_mapping
						);
						array_push($tmp_location['billboards'], $new_mapping);
					}

					array_push($response['locations'], $tmp_location);
				}
			}

			echo json_encode($response);
			die();

			if($test != false){
				$counter++;
			}
			
		} else {
			$response['status'] = false;
		}

		echo json_encode($response);
		die();
	}

	////////////////////////////////////////////////////////////////
	// Create locator table
	////////////////////////////////////////////////////////////////
	function locator_create_table(){
		global $wpdb;

			if(get_option( "rede_stores_db_version") === FALSE){
				$table_name = $wpdb->prefix . "locator";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						chain varchar(255) NOT NULL,
						channel varchar(255) NOT NULL,
						chain_num varchar(255) NOT NULL,
						chain_name varchar(255) NOT NULL,
						cus_name varchar(255) NOT NULL,
						address1 varchar(255) NOT NULL,
						address2 varchar(255) NOT NULL,
						city varchar(255) NOT NULL,
						state varchar(255) NOT NULL,
						zip varchar(10) NOT NULL,
						lat FLOAT(9,6) NOT NULL,
						lon FLOAT(9,6) NOT NULL,
						store_num varchar(255) NOT NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_stores_db_version", "1" );
			}

			if(get_option( "rede_stores_db_version2") === FALSE){
				$table_name = $wpdb->prefix . "locator2";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						chain varchar(255) NOT NULL,
						channel varchar(255) NOT NULL,
						chain_num varchar(255),
						chain_name varchar(255) NOT NULL,
						cus_name varchar(255),
						address1 varchar(255) NOT NULL,
						address2 varchar(255),
						city varchar(255) NOT NULL,
						state varchar(255) NOT NULL,
						zip varchar(10) NOT NULL,
						lat FLOAT(9,6),
						lon FLOAT(9,6),
						store_num varchar(255),
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_stores_db_version2", "1" );
			}

			if(get_option( "rede_stores_db_version3") === FALSE){
				$table_name = $wpdb->prefix . "locator3";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						chain varchar(255) NOT NULL,
						channel varchar(255) NOT NULL,
						chain_num varchar(255),
						chain_name varchar(255) NOT NULL,
						cus_name varchar(255),
						address1 varchar(255) NOT NULL,
						address2 varchar(255),
						city varchar(255) NOT NULL,
						state varchar(255) NOT NULL,
						zip varchar(10) NOT NULL,
						lat FLOAT(9,6),
						lon FLOAT(9,6),
						store_num varchar(255),
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_stores_db_version3", "1" );
			}

			if(get_option( "rede_billboards_db_version") === FALSE){
				$table_name = $wpdb->prefix . "billboards";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						unit_num varchar(255) NOT NULL,
						panel_num varchar(255) NOT NULL,
						tab_id varchar(255) NULL,
						name varchar(255) NOT NULL,
						media_style varchar(255) NOT NULL,
						postal_code varchar(255) NULL,
						lat FLOAT(9,6) NOT NULL,
						lon FLOAT(9,6) NOT NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_billboards_db_version", "1" );
			}

			if(get_option( "rede_mappings_db_version") === FALSE){
				$table_name = $wpdb->prefix . "billboard_map";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						store_id bigint(20) NOT NULL,
						billboard_id bigint(20) NOT NULL,
						distance bigint(20) NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_mappings_db_version2", "1" );
			}

			if(get_option( "rede_mappings_db_version2") === FALSE){
				$table_name = $wpdb->prefix . "billboard_map2";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						store_id bigint(20) NOT NULL,
						billboard_id bigint(20) NOT NULL,
						distance bigint(20) NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_mappings_db_version", "1" );
			}

			if(get_option( "rede_mappings_db_version3") === FALSE){
				$table_name = $wpdb->prefix . "billboard_map3";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						store_id bigint(20) NOT NULL,
						billboard_id bigint(20) NOT NULL,
						distance bigint(20) NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_mappings_db_version3", "1" );
			}

			if(get_option( "rede_dmas_db_version") === FALSE){
				$table_name = $wpdb->prefix . "dmas";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						fips bigint(20) NOT NULL,
						county varchar(255) NOT NULL,
						state varchar(255) NOT NULL,
						dma_code varchar(255) NOT NULL,
						dma_name varchar(255) NOT NULL,
						zip varchar(5) NOT NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_dmas_db_version", "1" );
			}

			if(get_option( "rede_dmas_top_db_version") === FALSE){
				$table_name = $wpdb->prefix . "top_dmas";

					$sql = "CREATE TABLE IF NOT EXISTS $table_name (
						id bigint(20) NOT NULL AUTO_INCREMENT,
						fips bigint(20) NOT NULL,
						county varchar(255) NOT NULL,
						state varchar(255) NOT NULL,
						dma_code varchar(255) NOT NULL,
						dma_name varchar(255) NOT NULL,
						zip varchar(5) NOT NULL,
						top varchar(255) NOT NULL,
						board_name varchar(255) NOT NULL,
						count bigint(20) NOT NULL,
						UNIQUE KEY id (id)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );

				add_option( "rede_dmas_top_db_version", "1" );
			}
	}

	////////////////////////////////////////////////////////////////
	// Search by lat/lon via API
	////////////////////////////////////////////////////////////////
	function api_search($address, $radius = 25, $results = 20)
{    $lat_lon = $this->getRadians($address);
		$lat =  $lat_lon['lat'];
		$lon =  $lat_lon['lon'];
		$apiurl = 'http://productlocator.iriworldwide.com/productlocator/servlet/ProductLocatorEngine?';

		$fields = array(
				'clientid' => '297',
				'productfamilyid' => 'AEVE',
				'producttype' => 'agg',
				'productid' => 'any',
				'searchradius' => $radius,
				'zip' => $address,
				'outputtype' => 'json',
				'storesperpage' => '100'
		);

		if(isset($_REQUEST['searchdata']) && !empty($_REQUEST['searchdata']) && isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
			$fields['producttype'] = 'upc';
			$fields['productid'] = $_REQUEST['searchdata'];
		} else if(isset($_REQUEST['family']) && !empty($_REQUEST['family'])){
			$fields['producttype'] = 'agg';
			$fields['productid'] = $_REQUEST['family'];
		}

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $apiurl . $fields_string);

		//curl_setopt($ch,CURLOPT_HTTPHEADER,array('Referer: http://rap.stage.ampagency.com'));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		return json_decode($result);
	}

	////////////////////////////////////////////////////////////////
	// Search by lat/lon
	////////////////////////////////////////////////////////////////
	function search($address, $distance = 5){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$lat_lon = $this->getRadians($address);
		$lat =  $lat_lon['lat'];
		$lon =  $lat_lon['lon'];
		// https://developers.google.com/maps/articles/phpsqlsearch_v3
		// SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;
		$locations = $wpdb->get_results(
			"
			 SELECT id, name, address1, address2, city, state, zip, lat, lon, status, has_tortillas, has_chips, ( 3959 * acos( cos( radians( $lat ) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians( $lon ) ) + sin( radians( $lat ) ) * sin( radians( lat ) ) ) ) AS distance
			 FROM $table_name
			 HAVING distance < $distance
			 ORDER BY distance
			 LIMIT 0 , 20;
			"
			);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// Delete row
	////////////////////////////////////////////////////////////////
	function locator_delete_entry($id){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$wpdb->delete(
			$table_name,
			array( 'id' => $id )
		);
	}

	////////////////////////////////////////////////////////////////
	// locator get all rows
	////////////////////////////////////////////////////////////////
	function billboards_get_all(){
		global $wpdb;
		$table_name = $wpdb->prefix . "billboards";
		$locations = $wpdb->get_results(
			"
			SELECT id, unit_num, panel_num, tab_id, name, media_style, postal_code, lat, lon
			FROM $table_name
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// Search billboards by store ids
	////////////////////////////////////////////////////////////////
	function rede_get_billboards($billboards){
		global $wpdb;
		$table_name = $wpdb->prefix . "billboards";
		$billboardClause = implode(' OR id = ', $billboards);
		$billboardClause = 'id = '.  $billboardClause;
		$locations = $wpdb->get_results(
			"
			SELECT *
			FROM $table_name
			WHERE $billboardClause
			"
		);
		return $locations;
	}

		////////////////////////////////////////////////////////////////
	// Search billboards by store ids
	////////////////////////////////////////////////////////////////
	function rede_get_zips_from_dmas($dmas){
		global $wpdb;
		$table_name = $wpdb->prefix . "dmas";
		$dmasClause = implode(' OR county = ', $dmas);
		$dmasClause = 'county = '.  $dmasClause;
		$locations = $wpdb->get_results(
			"
			SELECT *
			FROM $table_name
			WHERE $dmasClause
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// Search billboards by store ids
	////////////////////////////////////////////////////////////////
	function billboards_get_stores($stores){
		global $wpdb;
		$table_name = $wpdb->prefix . "billboard_map3";
		$storeClause = implode(' OR store_id = ', $stores);
		$storeClause = 'store_id = '.  $storeClause;
		$locations = $wpdb->get_results(
			"
			SELECT id, store_id, billboard_id, distance
			FROM $table_name
			WHERE $storeClause
			GROUP BY billboard_id
			"
		);
		return $locations;
	}

		////////////////////////////////////////////////////////////////
	// Search billboards by store ids
	////////////////////////////////////////////////////////////////
	function billboards_get_stores_by_parents($parent_ids){
		global $wpdb;
		$table_name = $wpdb->prefix . "billboard_map2";
		$stores = $this->locator_lookup_all_parents($parent_ids);
		// print_r($stores);
		// die();
		$storeClause = implode(' OR store_id = ', $stores);
		$storeClause = 'store_id = '.  $storeClause;
		// $locations = $wpdb->get_results(
		//   "
		//   SELECT id, store_id, billboard_id, distance
		//   FROM $table_name
		//   WHERE $storeClause
		//   AND distance <= 5
		//   GROUP BY billboard_id
		//   "
		// );
		// print_r($storeClause);
		// die();
		$wpdb->show_errors(true);
		$result = mysqli_query( $wpdb->dbh,"
		SELECT id, store_id, billboard_id, distance
		FROM $table_name
		WHERE ($storeClause)
		AND distance <= 5
		GROUP BY billboard_id
		");
		$rows = array();
		if ( $result ) {
			while ( ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) ) {
				$rows[] = $row;
			}
			@mysqli_free_result( $result );
		}
		return $rows;
	}

	////////////////////////////////////////////////////////////////
	// Search billboards by store ids
	////////////////////////////////////////////////////////////////
	function billboards_get_by_zips($zips){
		global $wpdb;
		$table_name = $wpdb->prefix . "billboards";
		$zipsClause = implode(' OR postal_code = ', $zips);
		$zipsClause = 'postal_code = '.  $zipsClause;
		$locations = $wpdb->get_results(
			"
			SELECT *
			FROM $table_name
			WHERE $zipsClause
			GROUP BY id
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// locator get all rows
	////////////////////////////////////////////////////////////////
	function locator_lookup_all_parents($parent_ids){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator2";
		$idsClause = implode(") OR chain_name = (SELECT chain_name FROM $table_name WHERE id = ", $parent_ids);
		$idsClause = "chain_name = (SELECT chain_name FROM $table_name WHERE id = ".  $idsClause . ")";

		$locations = $wpdb->get_col(
			"
			SELECT id
			FROM $table_name 
			WHERE $idsClause
			"
		);
		return $locations;
	}

		////////////////////////////////////////////////////////////////
	// locator get all rows
	////////////////////////////////////////////////////////////////
	function locator_lookup_by_parent($parent_id){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator2";
		$locations = $wpdb->get_results(
			"
			SELECT id
			FROM $table_name 
			WHERE chain_name = (SELECT chain_name FROM $table_name WHERE id = $parent_id)
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// Get zip codes by dmas
	////////////////////////////////////////////////////////////////
	function rede_zips_by_dmas($dmas){
		global $wpdb;
		$dmaClause = implode("' OR board_name = '", $dmas);
		$dmaClause = "board_name = '".  $dmaClause . "'";
		$table_name = $wpdb->prefix . "top_dmas";
		$locations = $wpdb->get_results(
			"
			SELECT zip
			FROM $table_name
			WHERE $dmaClause
			GROUP BY zip
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// locator get all rows
	////////////////////////////////////////////////////////////////
	function locator_get_all(){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$locations = $wpdb->get_results(
			"
			SELECT id, chain, channel, chain_num, chain_name, cus_name, address1, address2, city, state, zip, lat, lon, store_num
			FROM $table_name
			"
		);
		return $locations;
	}

		////////////////////////////////////////////////////////////////
	// locator get all rows
	////////////////////////////////////////////////////////////////
	function locator_get_grouped(){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$locations = $wpdb->get_results(
			"
			SELECT id, chain, channel, chain_num, chain_name, cus_name, address1, address2, city, state, zip, lat, lon, store_num, COUNT(*) AS count
			FROM $table_name
			GROUP BY chain, chain_name
			"
		);
		return $locations;
	}

		////////////////////////////////////////////////////////////////
	// Get all DMAS grouped by county/state
	////////////////////////////////////////////////////////////////
	function rede_get_all_dmas(){
		global $wpdb;
		$table_name = $wpdb->prefix . "dmas";
		$locations = $wpdb->get_results(
			"
			SELECT id, fips, county, state, dma_code, dma_name, zip, COUNT(*) AS count
			FROM $table_name
			GROUP BY dma_name
			"
		);
		return $locations;
	}

			////////////////////////////////////////////////////////////////
	// Get all top DMAS grouped by county/state
	////////////////////////////////////////////////////////////////
	function rede_get_all_top_dmas(){
		global $wpdb;
		$table_name = $wpdb->prefix . "top_dmas";
		$locations = $wpdb->get_results(
			"
			SELECT id, fips, county, state, dma_code, dma_name, zip, top, board_name, count, COUNT(*) AS num
			FROM $table_name
			GROUP BY board_name
			ORDER BY count DESC
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// locator get all rows
	////////////////////////////////////////////////////////////////
	function locator_get($results, $page){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$start = $page * $results;
		$locations = $wpdb->get_results(
			"
			SELECT *
			FROM $table_name
			WHERE lat NOT LIKE  ''
			AND lat NOT LIKE  '0.000000'
			LIMIT $start , $results
			"
		);
		return $locations;
	}

	////////////////////////////////////////////////////////////////
	// get specific entry
	////////////////////////////////////////////////////////////////
	function locator_get_entry($id){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$location = $wpdb->get_row(
			"
			SELECT *
			FROM $table_name
			WHERE id = $id
			"
		);
		return $location;
	}

	////////////////////////////////////////////////////////////////
	// update entry
	////////////////////////////////////////////////////////////////
	function locator_update_entry($id, $data){
		global $wpdb;
		$table_name = $wpdb->prefix . "locator3";
		$success = $wpdb->update(
			$table_name,
			$data,
			array( 'ID' => $id )
		);
		if($success > 0){
			return true;
		} else {
			return false;
		}
	}

	function getRadians($address){
		// Geocode Hometown
		$api_url = sprintf('http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find?text=%s&f=pjson', urlencode($address));
		$geocoded_hometown = wp_remote_get( $api_url, array( 'timeout' => 120) );
		$geocoded_hometown = (array) json_decode($geocoded_hometown['body']);
		if(isset($geocoded_hometown['locations'][0])){
			$lat_lon = (array) $geocoded_hometown['locations'][0]->feature->geometry;
			return array('lat' => $lat_lon['y'], 'lon' => $lat_lon['x']);
		} else {
			return false;
		}
	}

	function updateRadians($id){
		$entry = $this->locator_get_entry($id);
		$address =  $entry->address1 . ' ' . $entry->address2 . ' ' . $entry->city . ' ' . $entry->state . ' ' . $entry->zip;

		$lat_lon = $this->getRadians($address);

		if($lat_lon){
			$this->locator_update_entry($id, $lat_lon);
			return $lat_lon;
		} else {
			return false;
		}
	}
}
global $StoreLocator;
$StoreLocator = new StoreLocator();
$StoreLocator->init();

