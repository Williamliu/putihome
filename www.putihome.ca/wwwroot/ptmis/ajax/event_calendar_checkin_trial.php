<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["member_id"]			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 		"name":"Member ID", 				"nullable":0}';
	$type["event_id"]			= '{"type":"NUMBER", 	"length":11, 	"id": "event_id", 		"name":"Please select an event", 	"nullable":0}';
	$type["trial"]				= '{"type":"NUMBER", 	"length":11, 	"id": "trail", 			"name":"trial", 					"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "UPDATE event_calendar_enroll SET trial = '" . $_REQUEST["trial"] . "', trial_date = '" . time() . "' WHERE event_id = '" .  $_REQUEST["event_id"] . "' AND member_id = '" . $_REQUEST["member_id"] . "'";
	$result = $db->query( $query );

	$response["data"]["event_id"]	= $_REQUEST["event_id"];
	$response["data"]["member_id"]	= $_REQUEST["member_id"];

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= '';
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
