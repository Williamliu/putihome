<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] = '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$ret_data 	= array();
	
	$query0 	= "SELECT id, place, class_id, title, start_date, end_date FROM event_calendar WHERE id = '" . $_REQUEST["event_id"] . "'";
	$result0 	= $db->query($query0);
	$row0 		= $db->fetch($result0);
	
	$ret_data["evt"]["event_id"] 	= $row0["id"];
	$ret_data["evt"]["place"] 		= $row0["place"];
	$ret_data["evt"]["class_id"] 	= $row0["class_id"];
    $date_str 						= date("Y-m-d",$row0["start_date"]) . ($row0["end_date"]>0?" ~ ".date("Y-m-d",$row0["end_date"]):"");
	$ret_data["evt"]["title"] 		= cTYPE::gstr($row0["title"]) . "  [ " . $date_str . " ]";
	$ret_data["evt"]["start_date"] 	= date("Y-m-d",$row0["start_date"]);
	$ret_data["evt"]["end_date"] 	= date("Y-m-d",$row0["end_date"]);
	
	$query1 	= "SELECT * FROM puti_class_checkin WHERE class_id = '" . $row0["class_id"] . "' ORDER BY sn";
	$result1 	= $db->query($query1);
	$timeArr 	= array();
	$cnt 		= 0;
	while( $row1 = $db->fetch($result1) ) {
		$timeArr[$cnt]["sn"] 	= $row1["sn"];
		$timeArr[$cnt]["fhh"] 	= $row1["from_hh"];
		$timeArr[$cnt]["fmm"] 	= $row1["from_mm"];
		$timeArr[$cnt]["thh"] 	= $row1["to_hh"];
		$timeArr[$cnt]["tmm"] 	= $row1["to_mm"];
		$cnt++;		
	}
	$ret_data["evt"]["times"] 	= $timeArr;

	$response["data"] 			= $ret_data;
	$response["errorMessage"]	= "";
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
