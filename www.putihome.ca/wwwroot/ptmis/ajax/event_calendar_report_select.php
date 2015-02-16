<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
    
	$fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$evt = array();
	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "event_date >= '" . $sd . "' AND event_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "event_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "event_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	$query0 = "SELECT distinct a.id FROM event_calendar a INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
					WHERE a.deleted <> 1 AND 
						  b.deleted <> 1   
						  $ccc   
					ORDER BY event_date";

	$result0 = $db->query($query0);
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		
		$evt_id 	= $row0["id"];

		$query1 	= "SELECT id, title, status, start_date, end_date FROM event_calendar WHERE deleted <> 1 AND id = '" . $evt_id . "'";
		
		$result1	= $db->query($query1);
		$row1 		= $db->fetch($result1);

		$evt_arr["id"] 				= $row1["id"];
		$evt_arr["title"] 			= cTYPE::gstr($row1["title"]);
		$evt_arr["start_date"] 		= $row1["start_date"]>0?date("M j, Y", $row1["start_date"]):'';
		$evt_arr["end_date"] 		= $row1["end_date"]>0?date("M j, Y", $row1["end_date"]):'';
		$evt_arr["date_range"] 		= $evt_arr["start_date"] . ($evt_arr["end_date"]!=''?' ~ ' . $evt_arr["end_date"]:'');
		$evt_arr["status"] 			= $row1["status"];

		$query2 	= "SELECT count(a.id) as total,  sum(if(b.gender='Male',1,0)) as male, sum(if(b.gender='Female',1,0)) as female  
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
						WHERE a.deleted <> 1 AND 
						a.event_id = '" . $evt_id . "'";
		
		$result2	= $db->query($query2);
		$row2 		= $db->fetch($result2);
		
		$evt_arr["total"] 			= $row2["total"]==""?0:$row2["total"];
		$evt_arr["male"] 			= $row2["male"]==""?0:$row2["male"];
		$evt_arr["female"] 			= $row2["female"]==""?0:$row2["female"];
		$evt[$cnt0]					= $evt_arr;

		$cnt0++;
	}

	$response["data"]["evt"] = $evt;
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
