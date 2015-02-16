<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {
	$type["pid"]		= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group", 		"nullable":0}';
	$type["vid"]		= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group", 		"nullable":0}';
	$type["job_id"]		= '{"type":"NUMBER", 	"length":11, 	"id": "job_id", 		"name":"JOB ID", 		"nullable":0}';
	$type["purpose"]	= '{"type":"CHAR", 		"length":255, 	"id": "Work For", 		"name":"work_for", 		"nullable":1}';
	$type["work_date"]	= '{"type":"DATE", 		"length":31, 	"id": "work_date", 		"name":"Work Date", 	"nullable":0}';
	$type["work_hour"]	= '{"type":"NUMBER", 	"length":11, 	"id": "work_hour", 		"name":"Work Hour", 	"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$fields = array();
	$fields["site"] 			= $admin_user["site"];
	$fields["department_id"] 	= $_REQUEST["pid"];
	$fields["volunteer_id"] 	= $_REQUEST["vid"];
	$fields["job_id"] 			= $_REQUEST["job_id"];
	$fields["work_date"] 		= cTYPE::datetoint($_REQUEST["work_date"]);
	$fields["work_hour"] 		= $_REQUEST["work_hour"];
	$fields["purpose"] 			= cTYPE::trans($_REQUEST["purpose"]);
	$hour_id = $db->insert("puti_volunteer_hours", $fields);

	$response["data"]["pid"] 	= $_REQUEST["pid"];
	$response["data"]["vid"] 	= $_REQUEST["vid"];

	$response["errorMessage"]	= "<br>Hours has been saved to database.";
	$response["errorCode"] 	= 0;


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
