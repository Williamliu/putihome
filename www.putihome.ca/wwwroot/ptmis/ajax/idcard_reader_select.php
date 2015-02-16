<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	// condition here 
	$criteria = "";

	$con = $_REQUEST; 

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_place = trim($con["sch_place"]);
	if($sch_place != "") {
		$criteria .= ($criteria==""?"":" AND ") . "place = '" . $sch_place . "'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;	
	// end of criteria
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query_place 	= "SELECT * FROM puti_places ORDER BY id";
	$result_place 	= $db->query($query_place);
	$palces = array();
	while($row_place = $db->fetch($result_place)) {
		$places[ $row_place["id"] ] = $row_place["title"];
	}

	$query0 = "SELECT a.*,  b.title as site_desc, c.title as place_desc, IF(last_updated + 60 * 3 > UNIX_TIMESTAMP(), 1, 0) as active 
				FROM puti_devices a
				LEFT JOIN puti_sites b ON (a.site = b.id)
				LEFT JOIN puti_places c ON (a.place = c.id) 
				WHERE 1 = 1 $criteria 
				ORDER BY a.site, a.place, a.device_no";
					
					
	$result0 = $db->query($query0); 
	$devices = array();
	$cnt = 0;
	while($row0 = $db->fetch($result0)) {
		$device = array();
		$device["device_id"] 			= $row0["device_id"];
		$device["device_no"] 			= $row0["device_no"];
		$device["ip_address"] 			= $row0["ip_address"];
		$device["status"]				= $row0["active"]==1?'<a class="device-status device-status-active"></a>':'<a class="device-status device-status-inactive"></a>';
		$device["site"] 				= $row0["site"];
		$device["site_desc"]			= cTYPE::gstr($words[strtolower($row0["site_desc"])]);
		$device["place"] 				= $row0["place"];
		$device["place_desc"]			= html_select($places,$row0["place"],$row0["device_id"]);
	  
		$device["last_updated"] 		= $row0["last_updated"]>0?date("Y-m-d H:i:s",$row0["last_updated"]):"";
		
		$devices[$cnt++] = $device;
	}
	$response["data"]["devices"] 			= $devices;
	
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
