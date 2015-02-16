<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$class_id 	= $db->getVal("event_calendar", "class_id", $_REQUEST["event_id"]);
	$apass 		= $db->getVal("puti_class", 	"attend", 	$class_id);
	$apass		= round(($apass / 100.00), 2);

	$enrolls = $_REQUEST["attend"];	
	//echo " count: " . count($enrolls);
	//print_r($enrolls[201]);

	foreach($enrolls as $enroll) {
		$fields = array();
//		$fields["trial"]		= $enroll["trial"]?$enroll["trial"]:0;
//		$fields["unauth"]		= $enroll["unauth"]?$enroll["unauth"]:0;
//		$fields["signin"]		= $enroll["signin"]?$enroll["signin"]:0;
		$fields["graduate"]		= $enroll["graduate"]?$enroll["graduate"]:0;
		$fields["cert"] 		= $enroll["cert"]?$enroll["cert"]:0;

		$db->update("event_calendar_enroll", $enroll["enroll_id"], $fields);
	}
	$response["errorMessage"]	= "<br>Data has been saved to database.";
	$response["errorCode"] 		= 0;

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
