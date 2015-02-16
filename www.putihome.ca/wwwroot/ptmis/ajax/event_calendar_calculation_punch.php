<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] = '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	$type["start_date"] = '{"type":"DATE", "length":0, "id": "start_date",  "name":"Start Date",  "nullable":0}';
	$type["end_date"] 	= '{"type":"DATE", "length":0, "id": "end_date",  "name":"End Date",  "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}

	$times 	= $_REQUEST["times"]; 
	
	// All record
	$sd = cTYPE::datetoint($_REQUEST["start_date"] . " 00:00:00");
	$ed = cTYPE::datetoint($_REQUEST["end_date"] . " 23:59:59");
	$ccc = "a.created_time >= '" . $sd . "' AND a.created_time <= '" . $ed . "'";
	$query0 = "SELECT a.id, a.member_id, a.idd, a.created_time, b.first_name, b.last_name, b.dharma_name,b.alias, b.email, b.phone, b.cell, b.gender, b.city, b.site 
					FROM puti_attend a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE $ccc AND a.purpose = 'event' AND a.ref_id = '" . $_REQUEST["event_id"] . "' 
					ORDER BY a.created_time";

	
	$result0 = $db->query($query0);
	$evt = array();
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["id"] 				= $row0["id"];
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["time"] 			= date("H:i - M jS", $row0["created_time"]);

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["dharma_name"] 		= $row0["dharma_name"];
		$names["alias"] 			= $row0["alias"];
		
		$evt_arr["name"] 			= cTYPE::gstr(cTYPE::cname($names));
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"] . ($row0["phone"]!=""?"<br>":"") . $row0["cell"];
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);
		$evt_arr["site"] 			= $words[strtolower($sites[$row0["site"]])];
		$evt[$cnt0]					= $evt_arr;
		$cnt0++;
	}
	$response["data"]["holder"] = $evt;

	$query1 = "SELECT count( distinct a.member_id ) as cnt
					FROM puti_attend a 
					WHERE  $ccc AND a.purpose = 'event' AND a.ref_id = '" . $_REQUEST["event_id"] . "'" ;

	$result1 	= $db->query($query1);
	$row1 		= $db->fetch($result1);
	$response["data"]["total_head"]["student"] 	= $row1["cnt"];
	$response["data"]["total_head"]["punch"] 	= $db->row_nums($result0);

	
	// matched record:  create criteria;
	$ccc = "";
	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	$curdate = $sd;
	while( $curdate <= $ed ) { 
		foreach( $times as $val ) {
			$fhh = intval($val["fhh"]);
			$fmm = intval($val["fmm"]);
			$thh = intval($val["thh"]);
			$tmm = intval($val["tmm"]);
	
			
			$from_time 	= mktime($fhh, $fmm, 0, date("n", $curdate), date("j", $curdate), date("Y", $curdate));
			$to_time 	= mktime($thh, $tmm, 0, date("n", $curdate), date("j", $curdate), date("Y", $curdate));
			
			$ccc .= ( $ccc==""?"(":" OR " ) . "(a.created_time >= '" . $from_time . "' AND a.created_time <= '" . $to_time . "')";
		}
		
		$curdate = mktime(0,0,0, date("n", $curdate), date("j", $curdate) + 1, date("Y", $curdate));
	}
	$ccc .= $ccc==""?"":")";
	// 


	$query0 = "SELECT a.id, a.member_id, a.idd, a.created_time, b.first_name, b.last_name, b.dharma_name,b.alias,b.legal_first,b.legal_last, b.email, b.phone, b.cell, b.gender, b.city, b.site  
					FROM puti_attend a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE $ccc AND a.purpose = 'event' AND a.ref_id = '" . $_REQUEST["event_id"] . "' 
					ORDER BY a.created_time";
	//echo "query: " . $query0;
	$result0 = $db->query($query0);
	$matched = array();
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["id"] 				= $row0["id"];
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["time"] 			= date("H:i - M jS", $row0["created_time"]);
	
		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["dharma_name"] 		= $row0["dharma_name"];
		$names["alias"] 			= $row0["alias"];
		
		$evt_arr["name"] 			= cTYPE::gstr(cTYPE::tname($names));

		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"] . ($row0["phone"]!=""?"<br>":"") . $row0["cell"];
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);
		$evt_arr["site"] 			= $words[strtolower($sites[$row0["site"]])];
		$matched[$cnt0]				= $evt_arr;
		$cnt0++;
	}
	$response["data"]["matched"] = $matched;

	$query1 = "SELECT count( distinct a.member_id ) as cnt
					FROM puti_attend a 
					WHERE  $ccc AND a.purpose = 'event' AND a.ref_id = '" . $_REQUEST["event_id"] . "'" ;

	$result1 	= $db->query($query1);
	$row1 		= $db->fetch($result1);
	$response["data"]["matched_head"]["student"] = $row1["cnt"];
	$response["data"]["matched_head"]["punch"] 	 = $db->row_nums($result0);



	$event_site = $db->getVal("event_calendar", "site", $_REQUEST["event_id"]);
	$query0 = "SELECT a.id, a.member_id, a.site, a.place, c.title as site_desc, d.title as place_desc, a.idd, a.created_time, 
						  b.first_name, b.last_name, b.dharma_name, b.alias, b.legal_first, b.legal_last, b.phone, b.cell, b.email, b.city, b.gender, e.title as member_site 
						FROM puti_device_record a
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						INNER JOIN puti_sites c ON (a.site = c.id)
						INNER JOIN puti_places d ON (a.place = d.id)
						INNER JOIN puti_sites e ON ( b.site = e.id) 
						WHERE $ccc AND a.site = '" . $event_site . "' AND  a.place = '" . $_REQUEST["place"] . "' AND b.deleted <> 1  ORDER BY a.created_time DESC";



	$result0 = $db->query($query0);
	$idreader = array();
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["id"] 				= $row0["id"];
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["time"] 			= date("H:i - M jS", $row0["created_time"]);

		$evt_arr["site_desc"] 		= $words[strtolower($row0["site_desc"])];
		$evt_arr["place_desc"] 		= $words[strtolower($row0["place_desc"])];
	
		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["dharma_name"] 		= $row0["dharma_name"];
		$names["alias"] 			= $row0["alias"];
		
		$evt_arr["name"] 			= cTYPE::gstr(cTYPE::tname($names));

		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"] . ($row0["phone"]!=""?"<br>":"") . $row0["cell"];
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);
		$evt_arr["site"] 			= $words[strtolower($sites[$row0["site"]])];
		$idreader[$cnt0]			= $evt_arr;
		$cnt0++;
	}
	$response["data"]["idreader"] = $idreader;
	
	$query1 = "SELECT count(id) as pcnt, count( distinct member_id ) as mcnt  
					FROM (" . $query0 . ") aa";

	$result1 	= $db->query($query1);
	$row1 		= $db->fetch($result1);
	
	$response["data"]["idreader_head"]["student"] = $row1["mcnt"];
	$response["data"]["idreader_head"]["punch"] 	 = $row1["pcnt"];


	
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
