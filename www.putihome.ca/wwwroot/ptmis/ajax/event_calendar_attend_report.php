<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] = '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Event Select", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
    
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();

	$result0 = $db->query("SELECT SUM(checkin) as total_num FROM  event_calendar_date WHERE event_id = '" .$_REQUEST["event_id"] . "'");
	$row0 = $db->fetch($result0);
	$total_check_number = intval($row0["total_num"]);
	
	$query0 = "SELECT title, start_date, end_date, status FROM event_calendar
					WHERE id = '" . $_REQUEST["event_id"] . "'";
	$result0 = $db->query($query0);
	$row0 = $db->fetch($result0);
	$evt["title"] 		= $row0["title"];
	$evt["start_date"] 	= $row0["start_date"]>0?date("Y-m-d", $row0["start_date"]):'';
	$evt["end_date"] 	= $row0["end_date"]>0?date("Y-m-d", $row0["end_date"]):'';
	$sss = array();
	$sss[0] = "Inactive";
	$sss[1] = "Active";
	$sss[2] = "Open";
	$sss[9] = "Closed";
	$evt["status"] 		= $sss[$row0["status"]];

	$query2 	= "SELECT round(sum(attend)/sum(if(attend>0,1,0)),2) as attend, count(a.id) as enroll, sum(a.trial) as trial, sum(a.new_flag) as new_flag    
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id)  
					WHERE a.deleted <> 1 AND a.event_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	
	$evt["att_per"] 	= $row2["attend"]==""?"0%":round($row2["attend"]*100,0)."%";
	$evt["enroll"] 		= $row2["enroll"]==""?0:$row2["enroll"];
	$evt["trial"] 		= $row2["trial"]==""?0:$row2["trial"];
	$evt["new_flag"] 	= $row2["new_flag"]==""?0:$row2["new_flag"];


	$query5 = "SELECT count(distinct enroll_id) as attend  
					FROM event_calendar_date a 
					INNER JOIN event_calendar_attend b ON (a.id = b.event_date_id AND b.sn <= a.checkin) 
					INNER JOIN event_calendar_enroll c ON (b.enroll_id = c.id) 
					WHERE a.event_id = '" . $_REQUEST["event_id"] . "' AND
						 (b.status = 2 OR b.status = 8) AND 
						  c.deleted <> 1 AND c.event_id = '" . $_REQUEST["event_id"] . "'"; 
	$result5 	= $db->query($query5);
	$row5 		= $db->fetch($result5);
	$evt["attend"] 		= $row5["attend"]==""?0:$row5["attend"];
	
	// loop from date to date
	$evt["dates"]		= array();
	$query3 = "SELECT id as event_date_id, day_no, title, event_date, checkin  
				FROM event_calendar_date
				WHERE event_id = '" . $_REQUEST["event_id"] . "'
				ORDER BY day_no";
 	$result3 = $db->query($query3);
	$cnt0=0;
	while($row3 = $db->fetch($result3)) {
		$dateArr	= array();
		$dateArr["event_date_id"] 	= $row3["event_date_id"];
		$dateArr["checkin"] 	    = $row3["checkin"];
		$dateArr["day_no"] 			= "Day " . $row3["day_no"];
		$dateArr["event_date"] 		= date("Y-m-d", $row3["event_date"]);
		$dateArr["event_date_desc"] = date("Y-m-d D", $row3["event_date"]);
		$dateArr["title"] 			= cTYPE::gstr($row3["title"]);
		$dateArr["enroll"] 			= $evt["enroll"];
		
		$query5 = "SELECT count(distinct enroll_id) as attend    
						FROM event_calendar_date a 
						INNER JOIN event_calendar_attend b ON (a.id = b.event_date_id AND b.sn <= a.checkin) 
						INNER JOIN event_calendar_enroll c ON (b.enroll_id = c.id) 
						WHERE a.id = '" . $dateArr["event_date_id"] . "' AND
							 (b.status = 2 OR b.status = 8) AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' AND
							  c.deleted <> 1 AND c.event_id = '" . $_REQUEST["event_id"] . "'"; 
		$result5 	= $db->query($query5);
		$row5 		= $db->fetch($result5);
		$dateArr["attend"] 	= $row5["attend"]?$row5["attend"]:0;
	
		// daily trial and new people
		$query6 = "SELECT distinct enroll_id as enroll_id     
						FROM event_calendar_date a 
						INNER JOIN event_calendar_attend b ON (a.id = b.event_date_id AND b.sn <= a.checkin) 
						INNER JOIN event_calendar_enroll c ON (b.enroll_id = c.id) 
						WHERE a.id = '" . $dateArr["event_date_id"] . "' AND
							 (b.status = 2 OR b.status = 8) AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' AND
							  c.deleted <> 1 AND c.event_id = '" . $_REQUEST["event_id"] . "'"; 
	
		$query7 = "SELECT SUM(trial) as trial, SUM(new_flag) as new_flag FROM event_calendar_enroll a INNER JOIN (" . $query6 . ") b ON (a.id = b.enroll_id)";
		$result7 = $db->query($query7);
		$row7 = $db->fetch($result7);
		$dateArr["trial"] 		= $row7["trial"]?$row7["trial"]:"";
		$dateArr["new_flag"] 	= $row7["new_flag"]?$row7["new_flag"]:"";


		// attend percent  by day
		$query5 = "SELECT SUM(IF(b.status = 2 OR b.status = 8,1,0)) as attend  
						FROM event_calendar_date a 
						INNER JOIN event_calendar_attend b ON (a.id = b.event_date_id AND b.sn <= a.checkin) 
						INNER JOIN event_calendar_enroll c ON (b.enroll_id = c.id) 
						WHERE a.id = '" . $dateArr["event_date_id"] . "' AND
							 (b.status = 2 OR b.status = 8) AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' AND 
							  c.deleted <> 1 AND c.event_id = '" . $_REQUEST["event_id"] . "'"; 
		$result5 	= $db->query($query5);
		$row5 		= $db->fetch($result5);
		$att_time 	= $row5["attend"]<=0?0:$row5["attend"];

		$att_demand = $dateArr["checkin"];
		$dateArr["att_per"] = ( round( $att_time/($evt["enroll"] * $att_demand), 2) * 100 ) . "%";

		$evt["dates"][$cnt0]		= $dateArr;
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
