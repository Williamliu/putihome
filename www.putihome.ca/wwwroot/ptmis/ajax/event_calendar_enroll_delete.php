<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "enroll_id", "name":"Select an Event", "nullable":1}';
	$type["member_id"] 	= '{"type":"NUMBER", "length":11, "id": "enroll_id", "name":"Member ID", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	if(	$_REQUEST["event_id"] != "" ) {
		$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
		$db->query("UPDATE event_calendar_enroll SET deleted = 1, group_no = 0 WHERE deleted <> 1 AND event_id = '" . $_REQUEST["event_id"] . "' AND member_id='" . $_REQUEST["member_id"] . "'");	
	}
	$response["data"]["event_id"]	= $_REQUEST["event_id"];
	$response["data"]["member_id"] 	= $_REQUEST["member_id"];
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
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
