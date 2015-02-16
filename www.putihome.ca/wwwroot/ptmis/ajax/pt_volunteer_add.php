<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["member_id"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 	    "name":"Member ID", 		"nullable":0}';
	$type["vol_flag"]		= '{"type":"NUMBER", 	"length":1, 	"id": "vol_flag", 		"name":"Volunteer Flag", 	"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["vol_flag"] == 1) {
		$fields = array();
		if($db->exists("SELECT member_id FROM pt_volunteer WHERE member_id = '" . $_REQUEST["member_id"] . "'") ) {
			$ccc = array();
			$ccc["member_id"] = $_REQUEST["member_id"];

			$fields["status"] 		= 1;
			$fields["deleted"] 		= 0;
			$fields["last_updated"] = time();
			$db->update("pt_volunteer", $ccc, $fields);
		} else {
			$fields["member_id"] 	= $_REQUEST["member_id"];
			$fields["status"] 		= 1;
			$fields["deleted"] 		= 0;
			$fields["created_time"] = time();
			$db->insert("pt_volunteer", $fields);
		}
	} else {
			$db->query("UPDATE pt_volunteer SET deleted = 1, last_updated = '" . time() . "' WHERE member_id = '" . $_REQUEST["member_id"] . "'");
	}
	
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
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
