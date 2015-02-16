<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	// condition here 
	$criteria = "";
	$criteria .= "site in " . $admin_user["sites"];

	$con = $_REQUEST; 
	
	$sch_name = trim($con["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( first_name like '%" . cTYPE::trans($sch_name) . "%' OR last_name like '%" . cTYPE::trans($sch_name) . "%' OR legal_first like '%" . cTYPE::trans($sch_name) . "%' OR legal_last like '%" . cTYPE::trans($sch_name) . "%' OR dharma_name like '%" . cTYPE::trans($sch_name) . "%' OR alias like '%" . cTYPE::trans($sch_name) . "%' )";
	}

	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_status = trim($con["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.status = '" . $sch_status . "'";
	}

	$sch_online = trim($con["sch_online"]);
	if($sch_online != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.online = '" . $sch_online . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}


	$sch_plate = trim($con["sch_plate_no"]);
	if($sch_plate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "replace(replace(replace(plate_no,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_plate) . "%'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}


	// important,   if  scan ID Card,  search in whole list without site restrict  
	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "a.id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "a.id = '-1'";
		}
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	if( $con["event_id"] != "" ) {
		$query_base = "SELECT a.*, aa0.idd as id_card, IFNULL(c.id, 0) as enroll, c.group_no 
							FROM puti_members a
							LEFT JOIN puti_members_others b ON ( a.id = b.member_id ) 
							LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
							LEFT JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1 ) c ON ( a.id = c.member_id ) 
							WHERE  a.deleted <> 1  
							$criteria 
							$order_str";
	
	} else {
		$query_base = "SELECT a.*, aa0.idd as id_card, 0 as enroll, 0 as group_no 
							FROM puti_members a
							LEFT JOIN puti_members_others b ON ( a.id = b.member_id ) 
							LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
							WHERE  a.deleted <> 1  
							$criteria 
							$order_str";
	}
	

	$query_bb 	= $query_base;
	$result_bb = $db->query( $query_bb );
	while( $row_bb = $db->fetch($result_bb)) {

		$query = "SELECT id, group_no FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND member_id = '" . $row_bb["id"] . "'";
		$result = $db->query( $query );
		$row = $db->fetch($result);
		
		if( $db->row_nums($result) > 0 )  {
			$query = "UPDATE event_calendar_enroll SET group_no = '0', trial = '0', trial_date = '" . time() . "', onsite = '0', deleted = 1 WHERE event_id = '" . $con["event_id"] . "' AND member_id = '" . $row_bb["id"] . "'";
			$result = $db->query( $query );
			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= '';
		} 
	}


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
