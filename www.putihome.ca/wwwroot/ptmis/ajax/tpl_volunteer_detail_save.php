<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["member_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 					"name":"Member ID", 		"nullable":0}';
	$type["professional"] 		= '{"type":"CHAR", 		"length":0, 	"id": "professional",				"name":"Professional", 	"nullable":1}';
	$type["professional_other"] = '{"type":"CHAR", 		"length":255, 	"id": "professional_other",			"name":"Professional Other", 	"nullable":1}';
	$type["health"] 			= '{"type":"CHAR", 		"length":0, 	"id": "professional",				"name":"Health", 				"nullable":1}';
	$type["health_other"]		= '{"type":"CHAR", 		"length":255, 	"id": "professional_other",			"name":"Health Other", 			"nullable":1}';

	$type["resume"] 			= '{"type":"ALL", 		"length":0, 	"id": "resume", 		"name":"Resume", 		"nullable":1}';
	$type["memo"]				= '{"type":"ALL", 		"length":0, 	"id": "memo", 			"name":"Notes", 		"nullable":1}';

	$type["status"]				= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 		"nullable":0}';
	$type["email_flag"]			= '{"type":"NUMBER", 	"length":1, 	"id": "email_falg", 	"name":"Email Subscription", "nullable":1}';

	$type["emergency_name"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_name", 	"name":"Emergency Contact Person", 		"nullable":1}';
	$type["emergency_phone"]	= '{"type":"CHAR", 		"length":255, 	"id": "emergency_phone",	"name":"Emergency Contact Phone", 		"nullable":1}';
	$type["emergency_ship"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_ship", 	"name":"Emergency Relationship", 		"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$member_id = $_REQUEST["member_id"];
	$ccc = array();
	$ccc["member_id"] = $member_id;

	$fields = array();
	$fields["resume"] 			= cTYPE::trans($_REQUEST["resume"]);
	$fields["memo"] 			= cTYPE::trans($_REQUEST["memo"]);
	$fields["status"] 			= $_REQUEST["status"];
	$fields["email_flag"] 		= $_REQUEST["email_flag"];
	$fields["vol_type"] 		= $_REQUEST["vol_type"];
    if( $db->exists("SELECT member_id FROM pt_volunteer WHERE member_id = '" . $member_id . "'") ) {
		$fields["last_updated"] 	= time();
		$db->update("pt_volunteer", $ccc, $fields);
	} else {
    	$fields["member_id"] 		=  $member_id;
		$fields["created_time"] 	= time();
		$db->insert("pt_volunteer", $fields);
	}
	
	$db->rupdate("pt_volunteer_professional", $ccc, "professional_id", $_REQUEST["professional"]);
	$db->rupdate("pt_volunteer_health", $ccc, "health_id", $_REQUEST["health"]);

	$db->rupdate("pt_volunteer_depart_current", $ccc, "depart_id", $_REQUEST["vol_depart_current"]);
	$db->rupdate("pt_volunteer_depart_will", $ccc, "depart_id", $_REQUEST["vol_depart_will"]);
	
	$fields = array();
	$fields["professional_other"] 	= $_REQUEST["professional_other"];
	$fields["health_other"] 		= $_REQUEST["health_other"];
	$db->append("pt_volunteer_others", $ccc, $fields);

	$fields = array();
	$fields["email_flag"] 	= $_REQUEST["email_flag"];
	$db->update("puti_members", array("id"=>$_REQUEST["member_id"]), $fields);

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
