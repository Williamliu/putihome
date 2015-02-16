<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["agreement_id"] = '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT a.id as agreement_id, a.subject, a.status, 
					 b.id as lang_id, b.title, b.description
							FROM puti_agreement a
						 	LEFT JOIN (SELECT * FROM  puti_agreement_lang WHERE lang = '" . $admin_user["lang"] . "') b ON (a.id = b.agreement_id) 
						 WHERE 	a.deleted <> 1 AND a.id = '" . $_REQUEST["agreement_id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["agreement_id"] 	= $row["agreement_id"];
	$response["data"]["lang_id"] 		= $row["lang_id"]?$row["lang_id"]:-1;
	$response["data"]["subject"] 		= cTYPE::gstr($row["subject"]);
	$response["data"]["title"] 			= cTYPE::gstr($row["title"]);
	$response["data"]["desc"] 			= cTYPE::gstr($row["description"]);
	$response["data"]["status"] 		= $row["status"];
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
