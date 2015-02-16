<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["title"]				= '{"type":"CHAR", 	"length":255, 	"id": "event_title", 		"name":"Event Title", 		"nullable":0}';
	$type["start_date"]			= '{"type":"DATE", 	"length":0, 	"id": "start_date", 		"name":"Start Date", 		"nullable":0}';
	$type["agreement"]			= '{"type":"NUMBER", "length":11, 	"id": "agreement", 			"name":"Agreement", 		"nullable":0}';
	cTYPE::validate($type, $_REQUEST["evt"]);
	$type1["title"]				= '{"type":"CHAR", 	"length":255, 	"id": "event_title", 		"name":"At lease adding one day to event", 		"nullable":0}';
	cTYPE::validate($type1, $_REQUEST["evt"]["dates"][0]);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$fields = array();
	$fields["site"] 			= $admin_user["site"];
	$fields["branch"] 			= $admin_user["branch"];
	$fields["title"] 			= cTYPE::utrans($_REQUEST["evt"]["title"]);
	$fields["description"] 		= cTYPE::utrans($_REQUEST["evt"]["description"]);
	$fields["agreement"] 		= $_REQUEST["evt"]["agreement"];
	$fields["start_date"] 		= cTYPE::datetoint($_REQUEST["evt"]["start_date"]);
	$fields["end_date"] 		= cTYPE::datetoint($_REQUEST["evt"]["end_date"]);
	$fields["status"] 			= 1;
	$fields["deleted"] 			= 0;
	$fields["created_time"] 	= time();
	$evt_id = $db->insert("event_calendar", $fields);

	$dates = $_REQUEST["evt"]["dates"];	
	foreach($dates as $date) {
		$fields = array();
		$fields["event_id"] 		= $evt_id;
		$fields["title"] 			= cTYPE::utrans($date["title"]);
		$fields["description"] 		= cTYPE::utrans($date["description"]);
		$fields["yy"] 				= $date["yy"];
		$fields["mm"] 				= $date["mm"];
		$fields["dd"] 				= $date["dd"];
		$fields["event_date"] 		= cTYPE::datetoint($date["event_date"]);
		$fields["start_time"] 		= $date["start_time"];
		$fields["end_time"] 		= $date["end_time"];
		$fields["status"] 			= 1;
		$fields["deleted"] 			= 0;
		$date_id = $db->insert("event_calendar_date", $fields);

	}
	
	$response["errorMessage"]	= "<br>活动信息已经成功保存.";
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
