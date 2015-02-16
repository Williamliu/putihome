<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["group_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM website_groups WHERE deleted <> 1 AND id = '" . $db->quote($_REQUEST["group_id"]) . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["group_id"] 		= $row["id"];
	$response["data"]["group_name"] 	= $row["name"];
	$response["data"]["group_title"] 	= ($row["level"]?$row["level"]:'0') . '. ' . $row["name"];
	$response["data"]["group_desc"] 	= $row["description"];
	$response["data"]["group_status"] 	= $row["status"];
	$response["data"]["level"] 			= $row["level"];
	$response["data"]["group_right"] 	= json_decode($row["group_right"]);
	$response["data"]["created_time"] 	= cTYPE::inttodate($row["created_time"]);
	$response["data"]["last_updated"] 	= cTYPE::inttodate($row["last_updated"]);
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
