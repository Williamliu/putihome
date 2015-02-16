<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["year"]	= '{"type":"NUMBER", 	"length":4, 	"id": "year",	"name":"Year", 		"nullable":0}';
	$type["month"]	= '{"type":"NUMBER",	"length":2, 	"id": "month", 	"name":"Month", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$year 	= $_REQUEST["year"];
	$month 	= $_REQUEST["month"];
	$fday 	= date("w", mktime(0,0,0, $month+1, 1, $year) );
    $fdate 	= mktime(0,0,0, $month+1 ,1- $fday, $year);

	$lday 	= date("w", mktime(0,0,0, $month+2, 0, $year) );
	$ldate 	= mktime(0,0,0, $month+1, date("j", mktime(0,0,0, $month+2, 0, $year)) + 6 - $lday , $year);	

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query_place 	= "SELECT * FROM puti_places ORDER BY id";
	$result_place 	= $db->query($query_place);
	$palces = array();
	while($row_place = $db->fetch($result_place)) {
		$places[ $row_place["id"] ] = $row_place["title"];
	}


	$evt = array();
	$query0 = "SELECT b.id, b.event_id, b.title, b.description, b.yy, b.mm, b.dd, b.event_date, b.start_time, b.end_time, b.status,
					  a.site, a.place, c.title as site_desc, c.address as site_address, c.tel as site_tel, c.email as site_email, 
					  a.branch, d.title as branch_desc, 	
					  a.title as event_title, a.description as event_description, a.status as event_status,
					  a.start_date as start_date, a.end_date as end_date,
                      SUBSTRING_INDEX(b.start_time,':',1) * 60 + SUBSTRING_INDEX(b.start_time,':',-1) as start_seconds,  
                      SUBSTRING_INDEX(b.end_time,':',1) * 60 + SUBSTRING_INDEX(b.end_time,':',-1) as end_seconds
					FROM event_calendar a 
					INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
					INNER JOIN puti_sites c on (a.site = c.id ) 
					INNER JOIN puti_branchs d on (a.branch = d.id )  
					WHERE a.deleted <> 1 AND 
						  b.deleted <> 1 AND  
						  a.site = '" . $_REQUEST["site"] . "' AND
						  a.branch IN " . $admin_user["branchs"] . " AND
						  event_date >= '" . $fdate . "' AND event_date <= '" . $ldate . "' 
					ORDER BY event_date, start_seconds, end_seconds";
					
	$result0 = $db->query($query0);
	$cnt=0;
	while($row0 = $db->fetch($result0)) {
		$evt[$cnt]["site"] 				= $row0["site"];
		$evt[$cnt]["site_desc"]			= cTYPE::gstr($row0["site_desc"]);

		$evt[$cnt]["place"] 			= $row0["place"];
		$evt[$cnt]["place_desc_select"]	= html_select($places,$row0["place"],$row0["event_id"]);
		$evt[$cnt]["place_desc"]		= cTYPE::gstr($words[strtolower($places[$row0["place"]])]);
		
		
		$evt[$cnt]["site_address"]		= cTYPE::gstr($row0["site_address"]);
		$evt[$cnt]["site_tel"]			= $row0["site_tel"];
		$evt[$cnt]["site_email"]		= $row0["site_email"];
		
		$evt[$cnt]["branch"] 			= $row0["branch"];
		$evt[$cnt]["branch_desc"]		= cTYPE::gstr($row0["branch_desc"]);

		$evt[$cnt]["event_id"] 			= $row0["event_id"];
		$evt[$cnt]["event_title"] 		= cTYPE::gstr($row0["event_title"]);
		$evt[$cnt]["event_description"] = cTYPE::gstr($row0["event_description"]);
		$evt[$cnt]["event_status"] 		= $row0["event_status"];
		$evt[$cnt]["active"] 			= $row0["event_status"];
		$evt[$cnt]["event_id"] 			= $row0["event_id"];
		$evt[$cnt]["start_date"] 		= $row0["start_date"]>0?date("M d, Y",$row0["start_date"]):"";
		$evt[$cnt]["end_date"] 			= $row0["end_date"]>0?date("M d, Y",$row0["end_date"]):"";

		$evt[$cnt]["date_id"] 		= $row0["id"];
		$evt[$cnt]["title"]			= cTYPE::gstr($row0["title"]);
		$evt[$cnt]["description"] 	= cTYPE::gstr($row0["description"]);
		$evt[$cnt]["status"] 		= $row0["status"];
		$evt[$cnt]["active"] 		= $row0["status"] && $row0["event_status"];

		$evt[$cnt]["yy"] 			= $row0["yy"];
		$evt[$cnt]["mm"] 			= $row0["mm"];
		$evt[$cnt]["dd"] 			= $row0["dd"];
		$evt[$cnt]["event_date"] 	= $row0["event_date"]>0?date("Y-m-d",$row0["event_date"]):"";
		$evt[$cnt]["start_time"] 	= $row0["start_time"];
		$evt[$cnt]["end_time"] 		= $row0["end_time"];

		$cnt++;
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


function html_select($arr, $val, $id)  {
	global $words;
	$html = '<select class="device_place" rid="' . $id . '">';
	foreach($arr as $k=>$v) {
		$html .= '<option value="'. $k .'" ' . ($k==$val?'selected':'') . '>' .  cTYPE::gstr($words[strtolower($v)]) . '</option>';
	}
	$html .= '</select>'; 
	return $html;
}
?>
