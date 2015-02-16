<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$event = $_REQUEST["event_content"];
	$fields = array();
	$fields["title"] 			= cTYPE::utrans($event["title"]);
	$fields["description"] 		= cTYPE::utrans($event["description"]);
	$fields["status"] 			= $event["status"];
	$fields["place"] 			= $event["place"];
	$fields["last_updated"] 	= time();
	$db->update("event_calendar", $event["id"], $fields);

	$date = $_REQUEST["event_content"]["date"];	
	$fields = array();
	$fields["title"] 			= cTYPE::utrans($date["title"]);
	$fields["description"] 		= cTYPE::utrans($date["description"]);
	$fields["event_date"] 		= cTYPE::datetoint($date["event_date"]);
	$fields["start_time"] 		= $date["start_time"];
	$fields["end_time"] 		= $date["end_time"];
	$fields["yy"] 				= date("Y", $fields["event_date"]);
	$fields["mm"] 				= date("m", $fields["event_date"]) - 1;
	$fields["dd"] 				= date("d", $fields["event_date"]);
	$fields["status"] 			= $date["status"];
	$db->update("event_calendar_date", $date["id"],  $fields);

	$query = "SELECT min(event_date) as start_date, max(event_date) as end_date FROM event_calendar_date WHERE event_id = '" . $event["id"] . "'";
	$result = $db->query($query);
	$row = $db->fetch($result);

	$fields = array();
	$fields["start_date"] 		= $row["start_date"];
	$fields["end_date"] 		= $row["end_date"];
	$fields["last_updated"] 	= time();
	$db->update("event_calendar", $event["id"], $fields);
	
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
