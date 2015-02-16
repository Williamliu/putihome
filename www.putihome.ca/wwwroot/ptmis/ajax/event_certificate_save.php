<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$eid 	= $_REQUEST["event_id"];
	$docs	= $_REQUEST["docs"];
	
	$ccc = array();
	$ccc["event_id"] = $eid;
	foreach( $docs as $doc ) {
		$ccc["member_id"] = $doc["member_id"];
		$fields = array();
		$fields["cert_no"] = $doc["cert_no"];
		$db->update("event_calendar_enroll", $ccc, $fields);
	}

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
