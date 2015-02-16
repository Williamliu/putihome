<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["dharma_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "dharma_id", 		"name":"Dharma ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "DELETE FROM  puti_dharma WHERE id = '" . $_REQUEST["dharma_id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["dharma_id"] = $_REQUEST["dharma_id"];
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "<br>Dharma Prefix has been deleted.";
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
