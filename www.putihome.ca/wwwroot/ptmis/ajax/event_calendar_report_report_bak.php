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

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = cTYPE::gstr($row_age["title"]);
	}
	$ages[0] = "";

	$evt = array();
	
	$query0 = "SELECT title, start_date, end_date, status FROM event_calendar
					WHERE id = '" . $_REQUEST["event_id"] . "'";
	$result0 = $db->query($query0);
	$row0 = $db->fetch($result0);
	$evt["title"] 		= cTYPE::gstr($row0["title"]);
	$evt["start_date"] 	= $row0["start_date"]>0?date("Y-m-d", $row0["start_date"]):'';
	$evt["end_date"] 	= $row0["end_date"]>0?date("Y-m-d", $row0["end_date"]):'';
	$sss = array();
	$sss[0] = "Inactive";
	$sss[1] = "Active";
	$sss[2] = "Open";
	$sss[9] = "Closed";
	$evt["status"] 		= $sss[$row0["status"]];

	$query2 	= "SELECT sum(a.online) as online, count(a.id) as enroll, sum(a.trial) as trial, sum(a.unauth) as unauth, sum(a.signin) as signin, sum(a.graduate) as graduate, sum(a.cert) as cert   
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id)  
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
					a.event_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	
	$evt["online"] 		= $row2["online"]==""?0:$row2["online"];
	$evt["enroll"] 		= $row2["enroll"]==""?0:$row2["enroll"];
	$evt["unauth"] 		= $row2["unauth"]==""?0:$row2["unauth"];
	$evt["trial"] 		= $row2["trial"]==""?0:$row2["trial"];
	$evt["signin"] 		= $row2["signin"]==""?0:$row2["signin"];
	$evt["graduate"] 	= $row2["graduate"]==""?0:$row2["graduate"];
	$evt["cert"] 		= $row2["cert"]==""?0:$row2["cert"];
	
	// attend percent
	$query2 	= "SELECT round(sum(attend)/sum(if(attend>0,1,0)),2) as attend, count(a.id) as enroll,  sum(a.signin) as signin, sum(a.graduate) as graduate, sum(a.cert) as cert   
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id)  
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
					a.event_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	
	$evt["att_per"] = $row2["attend"]==""?"":round($row2["attend"]*100,0)."%";

	// Attend People
	$query5 = "SELECT count(distinct enroll_id) as attend  
					FROM event_calendar_date a 
					INNER JOIN event_calendar_attend b ON (a.class_date_id = b.class_date_id) 
					INNER JOIN event_calendar_enroll c ON (b.enroll_id = c.id) 
					WHERE a.event_id = '" . $_REQUEST["event_id"] . "' AND
						  c.event_id = '" . $_REQUEST["event_id"] . "'"; 
	$result5 	= $db->query($query5);
	$row5 		= $db->fetch($result5);
	$evt["attend"] 		= $row5["attend"]==""?0:$row5["attend"];
	
	// punch people
	$query2 	= "SELECT count(member_id) as punch, count(distinct member_id) as student 
					FROM puti_attend a 
					WHERE a.ref_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);

	$evt["punch"] 	= $row2["punch"]==""?0:$row2["punch"];
	$evt["student"] = $row2["student"]==""?0:$row2["student"];
	// end
	
	$evt["list"]		= array();
	
	$query3 = "SELECT b.group_no, a.id as member_id, a.first_name, a.last_name, a.dharma_name, a.alias, a.age, a.birth_yy, a.member_yy, a.member_mm, a.member_dd, a.email, a.phone, a.city, b.group_no, b.online, b.trial, b.unauth, b.signin, b.graduate, b.cert, b.attend 
					FROM puti_members a INNER JOIN event_calendar_enroll b ON (a.id = b.member_id) 
				WHERE a.deleted <> 1 AND b.deleted <> 1 AND  b.event_id = '" . $_REQUEST["event_id"] . "'
				ORDER BY b.group_no, b.graduate desc,  a.first_name, a.last_name";
 	$result3 = $db->query($query3);
	$cnt0=0;
	while($row3 = $db->fetch($result3)) {
		$mArr = array();
		$mArr["group_no"] = $row3["group_no"]?$row3["group_no"]:"";
		
		$names						= array();
		$names["first_name"] 		= $row3["first_name"];
		$names["last_name"] 		= $row3["last_name"];
		$names["dharma_name"] 		= $row3["dharma_name"];
		$names["alias"] 			= $row3["alias"];
		$mArr["name"]				= cTYPE::gstr(cTYPE::cname($names, 10));
		
		$birth_yy 			= $row3["birth_yy"]>0? date("Y") - intval($row3["birth_yy"]):"";
		$mArr["age"] 		= $birth_yy>0?$birth_yy:$ages[$row3["age"]];
		$mArr["member_date"] = cTYPE::toDate($row3["member_yy"],$row3["member_mm"],$row3["member_dd"]);
	
		$mArr["email"] 		= $row3["email"];
		$mArr["phone"] 		= $row3["phone"];
		$mArr["city"] 		= cTYPE::gstr($row3["city"]);
		$mArr["online"] 	= $row3["online"]?"Y":"";
		$mArr["trial"] 		= $row3["trial"]?"Y":"";
		$mArr["unauth"] 	= $row3["unauth"]?"Y":"";
		$mArr["signin"] 	= $row3["signin"]?"Y":"";
		$mArr["graduate"] 	= $row3["graduate"]?"Y":"";
		$mArr["cert"] 		= $row3["cert"]?"Y":"";
		$mArr["attend"] 	= $row3["attend"]>0?($row3["attend"]*100)."%":"";

		$query6 	= "SELECT count(id) as punch FROM puti_attend WHERE purpose = 'event' AND ref_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $row3["member_id"] . "'";
		$result6 	= $db->query($query6);
		$row6		= $db->fetch($result6);
		$mArr["punch"] = $row6["punch"]<=0?"":$row6["punch"];


		$evt["list"][$cnt0]	= $mArr;
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
