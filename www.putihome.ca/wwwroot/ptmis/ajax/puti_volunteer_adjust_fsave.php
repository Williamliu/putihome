<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$vids = array();
	if( count($_REQUEST["hour"]) > 0 ) {
		  foreach($_REQUEST["hour"] as $date) {
				if( $date["work_hour"]>0 ) {
					$fields = array();
					$fields["work_hour"] 		= $date["work_hour"];
					$fields["job_id"] 			= $date["job_id"];
					$fields["purpose"] 			= cTYPE::trans($date["purpose"]);
					$db->update("puti_volunteer_hours", $date["hid"], $fields);
				} else {
					$db->delete("puti_volunteer_hours", $date["hid"]);
				}
		  }
		  $response["errorMessage"]	= "<br>Hours has been saved to database.";
		  $response["errorCode"] 	= 0;
	} else {
		  $response["errorMessage"]	= "<br>There is no hour to save.<br><br>No record for this department and this date.";
		  $response["errorCode"] 	= 1;
	}
	$response["data"]["pid"] 	= $_REQUEST["pid"];
	$response["data"]["vids"] 	= $vids;
	

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
