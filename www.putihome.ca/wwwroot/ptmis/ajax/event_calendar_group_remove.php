<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "enroll_id", "name":"Select Event", "nullable":0}';
	$type["enroll_id"] 	= '{"type":"NUMBER", "length":11, "id": "enroll_id", "name":"Member ID", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$eid = $_REQUEST["enroll_id"];
	$group_id = $db->getVal("event_calendar_enroll", "group_no", $eid);	
	$db->query("UPDATE event_calendar_enroll SET group_no = 0 WHERE deleted <> 1 AND status = 1 AND id = '" . $eid . "'");	
	
	$response["data"]["enroll_id"]	= $eid;
	$response["data"]["group_id"] 	= $group_id;
	$response["errorCode"] 			= 0;
	$response["errorMessage"]		= "";
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
