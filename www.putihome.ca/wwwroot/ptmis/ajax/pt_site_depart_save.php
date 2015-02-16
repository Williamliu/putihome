<?php 
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	//$type["depart_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "depart_id", 		"name":"Department ID", 		"nullable":0}';
	//$type["site_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "site_id", 		"name":"Site ID", 				"nullable":0}';
	//$type["yes_depart"] 			= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 				"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();


	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	foreach($_REQUEST["departs"] as $depart) {
		if($depart["yes_depart"]=="1")  {
			if(!$db->exists("SELECT * FROM pt_site_department WHERE site_id = '" . $depart["site_id"] . "' AND depart_id = '" . $depart["depart_id"] . "'") ) {
				$fields = array();
				$fields["site_id"] 		= $depart["site_id"];
				$fields["depart_id"] 	= $depart["depart_id"];
				$db->insert("pt_site_department", $fields);
			}
		} else {
			$db->query("DELETE FROM pt_site_department WHERE site_id = '" . $depart["site_id"] . "' AND depart_id = '" . $depart["depart_id"] . "'");
		}
	}
	$response["errorCode"] 				= 0;

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
