<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["hid"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "volunteer_id", 	"name":"Volunteer", 		"nullable":0}';
	$type["mid"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "merge_id", 		"name":"Merge ID", 			"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$mid = trim($_REQUEST["mid"]);
	$hid = trim($_REQUEST["hid"]);
	if( $mid == $hid ) {
		$response["errorCode"] 		= 1;
		$response["errorMessage"]	= "<br>The volunteer ID: " . $hid . " can not merge to same ID.";
		echo json_encode($response);
		exit();	
	}
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if( !$db->hasRow("puti_volunteer_hours", array("volunteer_id"=>$hid) ) ) {
		$db->query("UPDATE puti_volunteer SET deleted = 1 WHERE id = '" . $hid . "'");
		$response["errorCode"] 		= 0;
		$response["errorMessage"]	= "<br>The volunteer ID: " . $hid . " delete successful, he/she has no working hours.";
		echo json_encode($response);
		exit();	
	}
	
	if( !$db->hasRow("puti_volunteer", $mid) ) {
		$response["errorCode"] 		= 1;
		$response["errorMessage"]	= "<br>Please provide proper merge volunteer ID: " . $mid . ".";
		echo json_encode($response);
		exit();	
	}

	
	$query = "UPDATE puti_volunteer SET deleted = 1, last_updated = '" . time() . "' WHERE deleted <> 1 AND id = '" . $hid . "'";
	$db->query( $query );

	$query = "SELECT * FROM  puti_department_volunteer WHERE volunteer_id = '" . $hid . "'";
	$result = $db->query( $query );
	while( $row = $db->fetch($result) ) {
		$dep_id = $row["department_id"];
		$ccc = array();
		$ccc["department_id"] = $dep_id;
		$ccc["volunteer_id"] =  $_REQUEST["mid"];
		
		if( !$db->hasRow("puti_department_volunteer", $ccc) ) {
			$fields = array();
			$fields["site"]			 = $admin_user["site"];
			$fields["department_id"] = $dep_id;
			$fields["volunteer_id"]  = $mid;
			$fields["status"]  		 = 0;
			$fields["last_updated"]	 = time();
			$db->insert("puti_department_volunteer", $fields);		
		}
	}
	
	$db->query("UPDATE puti_volunteer_hours SET volunteer_id = '" . $mid . "' WHERE volunteer_id = '" . $hid . "'");
	
	$response["data"]["hid"] = $hid;
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "<br>The volunteer has been deleted successful.";
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
