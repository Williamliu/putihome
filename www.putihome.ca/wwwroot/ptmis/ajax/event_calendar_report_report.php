<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	



	// header stuff	
	$eid = $_REQUEST["event_id"];
	
	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $eid . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];
    
    
	$query00 = "SELECT  event_id, id as event_date_id, yy, mm, dd, event_date, checkin, day_no 
						FROM event_calendar_date
						WHERE event_id = '" . $eid . "' 
						ORDER BY day_no";

	$result00 = $db->query($query00);
	$head = array();
	$cnt = 0;
	$total_checkin = 0;
	while($row00	= $db->fetch($result00)) {
		$hObj = array();
		$hObj["event_id"] 		= $row00["event_id"];
		$hObj["event_date_id"] 	= $row00["event_date_id"];
		$hObj["day_no"] 		= $row00["day_no"];
		$hObj["yy"] 			= $row00["yy"];
		$hObj["mm"] 			= $row00["mm"];
		$hObj["dd"] 			= $row00["dd"];
		$hObj["event_date"] 	= $row00["event_date"]>0?date("Y-m-d",$row00["event_date"]):'';
		$hObj["event_md"] 		= $row00["event_date"]>0?date("M-j",$row00["event_date"]):'';
		$hObj["checkin"] 		= $row00["checkin"];
		$total_checkin			+= $row00["checkin"];
		$head[$cnt] = $hObj;
		$cnt++;
	}
	$response["data"]["others"] = $head;
	//end of header stuff	

	$query_base = "SELECT  a.id as enroll_id, a.event_id, a.leader, a.volunteer, a.shelf, a.unauth, a.trial, a.group_no, a.new_flag, 
						   a.online, a.signin, a.graduate, a.cert, attend, a.cert_no, a.doc_no, 
						   b.id as member_id, b.first_name, b.last_name, b.alias, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
					WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND 
						  b.deleted <> 1
                    ORDER BY a.group_no, a.leader DESC, a.volunteer DESC, b.last_name, b.first_name";

	//echo "query:" . $query_base;
	//exit;

	$query0	= $query_base;
	$result0 = $db->query($query0);
	$cnt0=0;
	$evtArr = array();
		
	while($row0 = $db->fetch($result0)) {
		$eObj = array();
		$eObj["event_id"] 	= $row0["event_id"];
		$eObj["enroll_id"] 	= $row0["enroll_id"];
		$eObj["member_id"] 	= $row0["member_id"];

		$eObj["group_no"] 	= $row0["group_no"];

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["alias"] 		    = $row0["alias"];
		$eObj["name"]				= cTYPE::gstr(cTYPE::lfname($names));

		$eObj["dharma_name"]	= cTYPE::gstr($row0["dharma_name"]);
		$eObj["title"]	        = $row0["leader"]?cTYPE::gstr($words["tag.leader"]):($row0["volunteer"]?cTYPE::gstr($words["tag.volunteer"]):"");

		$eObj["first_name"]	= cTYPE::gstr($row0["first_name"]);
		$eObj["last_name"]	= cTYPE::gstr($row0["last_name"]);
		$eObj["dharma_name"]= cTYPE::gstr($row0["dharma_name"]);

		$eObj["gender"]		= $row0["gender"];
		$eObj["email"]		= $row0["email"];
		$eObj["phone"]		= $row0["phone"];
		$eObj["cell"]		= $row0["cell"];
		$eObj["city"]		= cTYPE::gstr($row0["city"]);


		$eObj["new_flag"]	= $row0["new_flag"]?"Y":"";
		$eObj["trial"]		= $row0["trial"]?"Y":"";
		$eObj["unauth"]		= $row0["unauth"]?"Y":"";
		
		$eObj["online"]		= $row0["online"]?"Y":"";
		$eObj["attd"]		= $row0["attend"];
		$eObj["signin"]		= $row0["signin"]?"Y":"";
		$eObj["graduate"]	= $row0["graduate"]?"Y":"";
		$eObj["cert"]		= $row0["cert"]?"Y":"";
		$eObj["cert_no"]	= $row0["cert_no"]?$row0["cert_no"]:"";
		$eObj["doc_no"]		= $row0["doc_no"]?$row0["doc_no"]:"";


		$eObj["shelf"] 		= cTYPE::shelfSN($row0["shelf"],$CFG["max_shoes_rack"]);

		$eObj["total_checkin"] 	= $row0["attend"]>0?$total_checkin:"";

		$querya 	= "SELECT   SUM(IF( b.status=2 OR b.status=8, 1, 0)) as total_attend,
								SUM(IF( b.status=4, 1 , 0)) as total_leave 
							FROM event_calendar_enroll a 
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                            INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
							WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND a.id = '" . $row0["enroll_id"] . "'";
		$resulta	= $db->query($querya);
		$rowa       = $db->fetch($resulta);
		$eObj["total_attend"] 	= $rowa["total_attend"]?$rowa["total_attend"]:"";
		$eObj["total_leave"] 	= $rowa["total_leave"]?$rowa["total_leave"]:"";
		

		
		$query1 	= "SELECT  b.enroll_id, b.event_date_id, b.sn, b.status
							FROM event_calendar_enroll a 
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                            INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
							WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND a.id = '" . $row0["enroll_id"] . "'";
		$result1	= $db->query($query1);
		$eObj["dates"] = array();
		$cnt1=0;
		while($row1	= $db->fetch($result1)) {
			$aObj = array();
			$aObj["event_id"] 		= $row0["event_id"];
			$aObj["enroll_id"] 		= $row0["enroll_id"];
			$aObj["event_date_id"] 	= $row1["event_date_id"];
			$aObj["sn"] 			= $row1["sn"];
			$aObj["status"] 		= $row1["status"];
			
			$eObj["dates"][$aObj["event_date_id"]][$aObj["sn"]] = $row1["status"];
			$cnt1++;
		}
		$evtArr[$cnt0] = $eObj;
		$cnt0++;
	}

	$response["data"]["rows"] 	= $evtArr;
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
