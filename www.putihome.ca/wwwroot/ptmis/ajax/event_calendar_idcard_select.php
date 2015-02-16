<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$ccc = "";
	if($_REQUEST["status"] != "") {
		$ccc .= "a.status = '" . $_REQUEST["status"] . "'";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc; 

	$query0 = "SELECT a.member_id, a.idd, a.status, b.first_name, b.last_name, b.dharma_name, b.email, b.phone, b.cell, b.gender, b.city 
					FROM puti_idd a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE 1 = 1 AND 
					b.site IN " . $admin_user["sites"] . " 
					$ccc 
					ORDER BY a.status, b.first_name, b.last_name, a.created_time DESC";

	$result0 = $db->query($query0);
	$evt = array();
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["status"] 			= $row0["status"];

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$evt_arr["name"] 			= cTYPE::gstr(cTYPE::lfname($names,13));

		//$evt_arr["last_name"] 		= stripslashes($row0["last_name"]);
		//$evt_arr["dharma_name"] 	= stripslashes($row0["dharma_name"]);
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"];
		$evt_arr["cell"] 			= $row0["cell"];
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			=  cTYPE::gstr($row0["city"]);
		$evt[$cnt0]					= $evt_arr;
		$cnt0++;
	}

	$response["data"]["holder"] = $evt;
	$response["errorMessage"]	= "";
	$response["errorCode"] 		= 0;

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



?>
