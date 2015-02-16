<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["hid"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "DELETE FROM  puti_email WHERE admin_id = '" . $admin_user["id"] . "' AND member_id = '" . $_REQUEST["hid"] . "'";
	$db->query( $query );
	
	$response["data"]["hid"] = $_REQUEST["hid"];
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "<br>Email has been deleted successful.";
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
