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
	$query0 = "SELECT a.id as enroll_id, b.id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city 
						FROM event_calendar_enroll a INNER JOIN puti_members b ON (a.member_id = b.id)  
            			WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND  
						a.event_id = '" . $_REQUEST["event_id"] . "' AND a.group_no = 0 
						ORDER BY b.first_name, a.created_time DESC";
	$result0 = $db->query($query0);
	$ungroup = array();
	$cnt0 = 0;
	while( $row0 = $db->fetch($result0) ) {
		$ungroup[$cnt0]["id"] 			= $row0["id"];
		$ungroup[$cnt0]["enroll_id"] 	= $row0["enroll_id"];

		$ungroup[$cnt0]["first_name"] 	= stripslashes($row0["first_name"]);
		$ungroup[$cnt0]["last_name"] 	= stripslashes($row0["last_name"]);
		$ungroup[$cnt0]["dharma_name"] 	= stripslashes($row0["dharma_name"]);


		$ungroup[$cnt0]["gender"] 		= $row0["gender"];
		$ungroup[$cnt0]["email"] 		= $row0["email"];
		$ungroup[$cnt0]["phone"] 		= $row0["phone"];
		$ungroup[$cnt0]["cell"] 		= $row0["cell"];
		$ungroup[$cnt0]["city"] 		= $row0["city"];
		$cnt0++;
	}
	$response["data"]["ungroup"] = $ungroup;


	$query1 = "SELECT a.id as enroll_id, b.id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city, a.group_no	FROM event_calendar_enroll a INNER JOIN puti_members b ON (a.member_id = b.id)  
            			WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND 
						a.event_id = '" . $_REQUEST["event_id"] . "' AND a.group_no > 0 
						ORDER BY a.group_no, b.first_name, b.last_name";
	$result1 = $db->query($query1);
	$group = array();
	$cnt1 = 0;
	while( $row1 = $db->fetch($result1) ) {
		$grp_no 				= $row1["group_no"];
		$grp_arr 				= array();
		$grp_arr["id"] 			= $row1["id"];
		$grp_arr["enroll_id"] 	= $row1["enroll_id"];
		$grp_arr["first_name"] 	= stripslashes($row1["first_name"]);
		$grp_arr["last_name"] 	= stripslashes($row1["last_name"]);
		$grp_arr["dharma_name"] = stripslashes($row1["dharma_name"]);
		$grp_arr["gender"] 		= $row1["gender"];
		$grp_arr["email"] 		= $row1["email"];
		$grp_arr["phone"] 		= $row1["phone"];
		$grp_arr["cell"] 		= $row1["cell"];
		$grp_arr["city"] 		= $row1["city"];
		$group[$grp_no][]		= $grp_arr;
	}
	$response["data"]["group"] = $group;


	$query2 = "SELECT a.id as enroll_id, b.id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city FROM event_calendar_enroll a INNER JOIN puti_members b ON (a.member_id = b.id)  
            			WHERE  a.deleted = 1 AND 
						b.deleted <> 1 AND  
						a.event_id = '" . $_REQUEST["event_id"] . "'  
						ORDER BY b.first_name, a.created_time DESC";
	$result2 = $db->query($query2);
	$deleteGrp = array();
	$cnt2 = 0;
	while( $row2 = $db->fetch($result2) ) {
		$deleteGrp[$cnt2]["id"] 		= $row2["id"];
		$deleteGrp[$cnt2]["enroll_id"] 	= $row2["enroll_id"];
		$deleteGrp[$cnt2]["first_name"] = stripslashes($row2["first_name"]);
		$deleteGrp[$cnt2]["last_name"] 	= stripslashes($row2["last_name"]);
		$deleteGrp[$cnt2]["dharma_name"] = stripslashes($row2["dharma_name"]);
		$deleteGrp[$cnt2]["gender"] 	= $row2["gender"];
		$deleteGrp[$cnt2]["email"] 		= $row2["email"];
		$deleteGrp[$cnt2]["phone"] 		= $row2["phone"];
		$deleteGrp[$cnt2]["cell"] 		= $row2["cell"];
		$deleteGrp[$cnt2]["city"] 		= $row2["city"];
		$cnt2++;
	}
	$response["data"]["del_grp"] = $deleteGrp;

	$query_idd = "SELECT aaa0.member_id, aaa0.idd  
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";
					
	$query3 = "SELECT a.id as enroll_id, b.id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city, a.group_no, a.online, aa0.idd  
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						LEFT JOIN ($query_idd) aa0 ON (b.id = aa0.member_id)  
            			WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND  
						a.event_id = '" . $_REQUEST["event_id"] . "'  
						ORDER BY b.first_name, a.created_time DESC";
	$result3 = $db->query($query3);
	$groupAll = array();
	$cnt3 = 0;
	while( $row3 = $db->fetch($result3) ) {
		$groupAll[$cnt3]["id"] 			= $row3["id"];
		$groupAll[$cnt3]["enroll_id"] 	= $row3["enroll_id"];
		$groupAll[$cnt3]["first_name"] 	= stripslashes($row3["first_name"]);
		$groupAll[$cnt3]["last_name"] 	= stripslashes($row3["last_name"]);
		$groupAll[$cnt3]["dharma_name"] = stripslashes($row3["dharma_name"]);
		$groupAll[$cnt3]["gender"] 		= $row3["gender"];
		$groupAll[$cnt3]["email"] 		= $row3["email"];
		$groupAll[$cnt3]["phone"] 		= $row3["phone"];
		$groupAll[$cnt3]["cell"] 		= $row3["cell"];
		$groupAll[$cnt3]["city"] 		= $row3["city"];
		$groupAll[$cnt3]["group_no"] 	= $row3["group_no"];
		$groupAll[$cnt3]["online"] 		= $row3["online"]?"Y":"";
		$groupAll[$cnt3]["idd"] 		= $row3["idd"]?$row3["idd"]:"";
		
		
		$class_id = $db->getVal("event_calendar","class_id", array("id"=>$_REQUEST["event_id"]));
		$query8 = "SELECT paid, amt, paid_date 
						FROM event_calendar  a
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
						WHERE a.class_id = '" . $class_id . "' AND paid = 1 AND
								b.member_id = '" . $groupAll[$cnt3]["id"] . "' 
						ORDER BY paid_date DESC, amt DESC";
		$result8 	= $db->query($query8);
		$row8 		= $db->fetch($result8); 		
		$groupAll[$cnt3]["paid"] 		= $row8["paid"]?"Y":"";
		$groupAll[$cnt3]["amt"] 		= $row8["online"]>0?$row8["amt"]:"";
		$groupAll[$cnt3]["paid_date"]	= $row8["paid_date"]>0?date("Y-m-d",$row8["paid_date"]):"";
		$cnt3++;
	}
	$response["data"]["all_grp"] = $groupAll;


	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
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
