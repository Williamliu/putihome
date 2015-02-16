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
				$fields = array();
				$fields["site"] 			= $admin_user["site"];
				$fields["department_id"] 	= $date["pid"];
				$fields["volunteer_id"] 	= $date["vid"];
				$fields["job_id"] 			= $date["job_id"];
				$fields["work_date"] 		= cTYPE::datetoint($date["work_date"]);
				$fields["work_hour"] 		= $date["work_hour"];
				$fields["purpose"] 			= cTYPE::trans($date["purpose"]);
				$hour_id = $db->insert("puti_volunteer_hours", $fields);
		  		$vids[] = $date["vid"];
		  }
		  $response["errorMessage"]	= "<br>Hours has been saved to database.";
		  $response["errorCode"] 	= 0;
	} else {
		  $response["errorMessage"]	= "<br>There is no hour to save.<br><br>Please add work day and work hour to list.";
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
