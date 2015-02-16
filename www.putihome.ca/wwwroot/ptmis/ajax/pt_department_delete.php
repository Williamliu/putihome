<?php 
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["depart_id"]  = '{"type":"NUMBER", "length":11, 	"id": "depart_id", "name":"Department ID", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if( $db->exists("SELECT * FROM pt_department WHERE deleted <> 1 AND parent = '" . $_REQUEST["depart_id"] . "'"))	 {
		$response["errorCode"] 		= 1;
		$response["errorMessage"] 	= $words["the record can not be deleted for children"];
		echo json_encode($response);
		exit();		
	}
	
    $depart_id = $_REQUEST["depart_id"];
    $db->detach("pt_department", $depart_id);
	
	$response["data"]["depart_id"]  = $depart_id;
	$response["data"]["id"]    		= $depart_id;
	$response["errorCode"] 			= 0;

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
