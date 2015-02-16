<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["title"]	= '{"type":"CHAR", 	"length":255, 	"id": "title", 	"name":"Class Title", 		"nullable":0}';
	cTYPE::validate($type0, $_REQUEST);

	$type["id"]		= '{"type":"NUMBER", "length":11, 	"id": "id",  "name":"class id", "nullable":0}';
	cTYPE::validate($type, $_REQUEST["cls"]);
	
	$type1["id"]		 = '{"type":"NUMBER", "length":11, 	"id": "id",  "name":"class date id", "nullable":0}';
	foreach( $_REQUEST["cls"]["dates"] as $val ) {
		$type1["event_date"] = '{"type":"DATE",   "length":0, "id": "class_date",	"name":"class date for day ' . $val["day_no"] . '",  "nullable":0}';
		cTYPE::validate($type1, $val);
	}
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$query = "INSERT INTO event_calendar(site, branch, class_id, title, description, place, agreement, status, deleted, created_time) 
	          SELECT site, branch, id, '" . $db->quote($_REQUEST["title"]) . "', '" . $db->quote($_REQUEST["description"]) . "', '" . $_REQUEST["place"] . "', agreement, status, deleted, '" . time() . "' FROM puti_class WHERE id = '" . $_REQUEST["cls"]["id"] . "'";
	
	//echo "query: " . $query;
	
	$db->query($query);
	$ev_id = $db->getID();
	
	$dates = $_REQUEST["cls"]["dates"];	
	foreach($dates as $date) {
		$ddd = cTYPE::datetoint($date["event_date"]);
		$query1 = "INSERT INTO event_calendar_date(
                                                    event_id, 
                                                    class_date_id, 
                                                    title, description, 
                                                    day_no,
                                                    yy, mm, dd, 
                                                    start_time, end_time, 
                                                    event_date,
                                                    checkin,
                                                    meal, 
                                                    status, deleted ) 
	          		SELECT  '" . $ev_id . "', 
                            '" . $date["id"] . "', 
                            title, description, 
                            day_no,
                            '" . date("Y", $ddd) . "', '" . (date("m", $ddd)-1) . "', '" . date("d", $ddd) . "', 
                            start_time, end_time, 
                            '" . $ddd . "', 
                            checkin,
                            meal,
                            1, 0 
                    FROM puti_class_date WHERE id = '" . $date["id"] . "'";
        
        $db->query($query1);
	}
	
	$query2 = "SELECT MAX(event_date) as ma, MIN(event_date) as mi FROM event_calendar_date WHERE event_id = '" . $ev_id . "'";
	
	$result2 	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	
	$db->query("UPDATE event_calendar SET start_date = '" . $row2["mi"] . "', end_date='" . $row2["ma"] . "' WHERE id = '" . $ev_id . "'");

	$response["data"]["id"] 		= $_REQUEST["cls"]["id"];
	$response["data"]["class_id"] 	= $_REQUEST["cls"]["id"];
	$response["errorMessage"]	= "<br>Class has been added to calendar.";
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
