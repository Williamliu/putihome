<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["admin_id"] = '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM website_admins WHERE deleted <> 1 AND id = '" . $db->quote($_REQUEST["admin_id"]) . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["admin_id"] 		= $row["id"];
	$response["data"]["first_name"] 	= $row["first_name"];
	$response["data"]["last_name"] 		= $row["last_name"];
	$response["data"]["dharma_name"] 	= $row["dharma_name"];
	$response["data"]["phone"] 			= $row["phone"];
	$response["data"]["cell"] 			= $row["cell"];
	$response["data"]["city"] 			= $row["city"];
	$response["data"]["user_name"] 		= $row["user_name"];
	$response["data"]["email"] 			= $row["email"];
	$response["data"]["status"] 		= $row["status"];
	$response["data"]["site"] 			= $row["site"];
	$response["data"]["branch"] 		= $row["branch"];
	$response["data"]["sites"] 			= $row["sites"];
	$response["data"]["branchs"] 		= $row["branchs"];
	$response["data"]["department"] 	= $row["department"];
	$response["data"]["group_id"] 		= $row["group_id"];
	$response["data"]["created_time"] 	= cTYPE::inttodate($row["created_time"]);
	$response["data"]["last_updated"] 	= cTYPE::inttodate($row["last_updated"]);
	$response["data"]["last_login"] 	= cTYPE::inttodate($row["last_login"]);
	$response["data"]["hits"] 			= $row["hits"];
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
