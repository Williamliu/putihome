<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["dharma_id"] = '{"type":"NUMBER", 	"length":11, 	"id": "dharma_id", 		"name":"Dharma ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT id, dharma_prefix, dharma_date, dharma_site
							FROM puti_dharma 
						 	WHERE 	id = '" . $_REQUEST["dharma_id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["id"] 			= $row["id"];
	$response["data"]["dharma_id"] 		= $row["id"];
	$response["data"]["lang_id"] 		= $row["lang_id"]?$row["lang_id"]:-1;
	$response["data"]["dharma_prefix"] 	= cTYPE::gstr($row["dharma_prefix"]);
	$response["data"]["dharma_date"] 	= $row["dharma_date"]>0?date("Y-m-d",$row["dharma_date"]):'';
	$response["data"]["dharma_site"] 	= $row["dharma_site"];
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
