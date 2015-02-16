<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["device_id"] 	= '{"type":"CHAR", 		"length":255, "id": "device_Id", "name":"Device ID", "nullable":0}';
	$type["place"] 		= '{"type":"NUMBER",    "length":11, "id": "place", 	"name":"place", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
    
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$db->query("UPDATE puti_devices SET place = '" . $_REQUEST["place"] . "' WHERE device_id = '" . $_REQUEST["device_id"] . "'");
	
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
