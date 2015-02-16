<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query_place 	= "SELECT * FROM puti_places ORDER BY id";
	$result_place 	= $db->query($query_place);
	$palces = array();
	while($row_place = $db->fetch($result_place)) {
		$places[ $row_place["id"] ] = $row_place["title"];
	}
	
	$device = array();
	$query0 = "SELECT a.*,  b.title as site_desc, c.title as place_desc 
				FROM puti_devices a
				LEFT JOIN puti_sites b ON (a.site = b.id)
				LEFT JOIN puti_places c ON (a.place = c.id)
				WHERE a.site = '" . $admin_user["site"] . "' ORDER BY device_no";
					
					
	$result0 = $db->query($query0); 
	$cnt=0;
	while($row0 = $db->fetch($result0)) {
		$device[$cnt]["device_id"] 			= $row0["device_id"];
		$device[$cnt]["device_no"] 			= $row0["device_no"];
		$device[$cnt]["ip_address"] 		= $row0["ip_address"];
		
		$device[$cnt]["site"] 				= $row0["site"];
		$device[$cnt]["site_desc"]			= cTYPE::gstr($words[strtolower($row0["site_desc"])]);
		$device[$cnt]["place"] 				= $row0["place"];
		$device[$cnt]["place_desc"]			= html_select($places,$row0["place"],$row0["device_id"]);

		$device[$cnt]["last_updated"] 		= $row0["last_updated"]>0?date("Y-m-d H:i:s",$row0["last_updated"]):"";
		$cnt++;
	}
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
