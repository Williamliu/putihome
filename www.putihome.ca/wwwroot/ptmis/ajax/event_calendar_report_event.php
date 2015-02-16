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
		$ccc = "start_date >= '" . $sd . "' AND start_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "start_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "start_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	$query0 = "SELECT a.id, a.title, a.start_date, a.end_date, b.title as site_desc 
					FROM event_calendar a
 					INNER JOIN puti_sites b ON (a.site = b.id) 
					WHERE 	deleted <> 1 AND 
							site IN " . $admin_user["sites"]  . " AND
						    branch IN ". $admin_user["branchs"] . " $ccc   
					ORDER BY start_date ASC";

	$result0 = $db->query($query0);
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt[$cnt0]["id"] 				= $row0["id"];
		$evt[$cnt0]["title"] 			= cTYPE::gstr($words[strtolower($row0["site_desc"])]) . ' - ' . cTYPE::gstr($row0["title"]);
		$evt[$cnt0]["start_date"] 		= $row0["start_date"]>0?date("Y-m-d", $row0["start_date"]):'';
		$evt[$cnt0]["end_date"] 		= $row0["end_date"]>0?date("Y-m-d", $row0["end_date"]):'';
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
