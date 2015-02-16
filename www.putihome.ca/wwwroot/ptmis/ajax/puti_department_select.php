<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["id"] = '{"type":"NUMBER", 	"length":11, 	"id": "department_id", 		"name":"Department ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM puti_department WHERE deleted <> 1 AND id = '" . $_REQUEST["id"] . "' ORDER BY sn DESC";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["id"] 			= $row["id"];
	$response["data"]["title"] 			= cTYPE::gstr($row["title"]);
	$response["data"]["en_title"] 		= cTYPE::gstr($row["en_title"]);
	$response["data"]["description"] 	= cTYPE::gstr($row["description"]);
	$response["data"]["status"] 		= $row["status"];
	$response["data"]["sn"] 			= $row["sn"];
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
