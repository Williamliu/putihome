<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$dev_str = $_REQUEST["device_str"];
	$dev_items = explode("|", $dev_str);

	$darr = array();
	foreach( $dev_items as $dev_item ) {
		$tmp_item = explode("=", $dev_item);
		$darr[$tmp_item[0]] = $tmp_item[1];
	}
	
	if( $db->exists("SELECT * FROM puti_devices WHERE device_id = '" . $darr["device_id"] . "'") ) {
		$query = "UPDATE puti_devices SET 
								  device_no = '" . $darr["device_no"] . "', 
								  ip_address = '" . $darr["ip_address"] . "',  
								  site = '" . $admin_user["site"] . "',
								  last_updated = UNIX_TIMESTAMP()  
					  WHERE device_id = '" . $darr["device_id"] . "'";	
		$db->query($query);
	} else {
		$query = "INSERT INTO puti_devices(device_id, device_no, ip_address, site, place, last_updated)
		 		  			values(
									'" . $darr["device_id"] . "',
									'" . $darr["device_no"] . "',
									'" . $darr["ip_address"] . "',
									'" . $admin_user["site"] . "',
									'0', 
									UNIX_TIMESTAMP() )";
		$db->query($query);
	}


	$query_place 	= "SELECT * FROM puti_places ORDER BY id";
	$result_place 	= $db->query($query_place);
	$palces = array();
	while($row_place = $db->fetch($result_place)) {
		$places[ $row_place["id"] ] = $row_place["title"];
	}

	
	$query0 = "SELECT a.*,  b.title as site_desc, c.title as place_desc 
				FROM puti_devices a
				LEFT JOIN puti_sites b ON (a.site = b.id)
				LEFT JOIN puti_places c ON (a.place = c.id)
				WHERE device_id = '" . $darr["device_id"] . "'";
					
					
	$result0 = $db->query($query0); 
	$row0 = $db->fetch($result0);
	$device = array();
	$device["device_id"] 			= $row0["device_id"];
	$device["device_no"] 			= $row0["device_no"];
	$device["ip_address"] 			= $row0["ip_address"];
	$device["status"]				= '<a class="device-status device-status-active"></a>';
	$device["site"] 				= $row0["site"];
	$device["site_desc"]			= cTYPE::gstr($words[strtolower($row0["site_desc"])]);
	$device["place"] 				= $row0["place"];
	//$device["place_desc"]			= html_select($places,$row0["place"],$row0["device_id"]);
	$device["place_desc"]			= cTYPE::gstr($words[strtolower($row0["place_desc"])]);
  
	$device["last_updated"] 		= $row0["last_updated"]>0?date("Y-m-d H:i:s",$row0["last_updated"]):"";
	
	$response["data"]["device"] 			= $device;
	$response["errorMessage"]				= "";
	$response["errorCode"] 					= 0;

	echo json_encode($response);
} catch(cERR $e) {
	echo json_encode($e->detail());
	
} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}

function html_select($arr, $val, $id)  {
	global $words;
	$html = '<select class="device_place" rid="' . $id . '">';
	foreach($arr as $k=>$v) {
		$html .= '<option value="'. $k .'" ' . ($k==$val?'selected':'') . '>' .  cTYPE::gstr($words[strtolower($v)]) . '</option>';
	}
	$html .= '</select>'; 
	return $html;
}
?>
