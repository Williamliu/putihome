<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["member_id"] 	    = '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 					"name":"Member ID", 		"nullable":0}';
	$type["schedule_id"] 	= '{"type":"NUMBER", 	"length":11, 	"id": "schedule_id", 					"name":"Schedule ID", 		"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
    $db->query("DELETE FROM pt_volunteer_schedule_day WHERE schedule_id = '" . $_REQUEST["schedule_id"] . "'");
    $db->query("DELETE FROM pt_volunteer_schedule WHERE schedule_id = '" . $_REQUEST["schedule_id"] . "'");


	$member_id = $_REQUEST["member_id"];
	$ccc = array();
	$ccc["member_id"] = $member_id;


    $weekdays = array();
    $weekdays[0] = "";
    $weekdays[1] = cTYPE::gstr($words["weekday.mon"]);
    $weekdays[2] = cTYPE::gstr($words["weekday.tue"]);
    $weekdays[3] = cTYPE::gstr($words["weekday.wed"]);
    $weekdays[4] = cTYPE::gstr($words["weekday.thur"]);
    $weekdays[5] = cTYPE::gstr($words["weekday.fri"]);
    $weekdays[6] = cTYPE::gstr($words["weekday.sat"]);
    $weekdays[7] = cTYPE::gstr($words["weekday.sun"]);

    $sss_types      = array();
    $sss_types[0]   =  cTYPE::gstr($words["volunteer.schedule.type.daily"]);
    $sss_types[1]   =  cTYPE::gstr($words["volunteer.schedule.type.weekly"]);
    $sss_types[2]   =  cTYPE::gstr($words["volunteer.schedule.type.monthly"]);


	$schedule = array();
    $cnt_sss  = 0;
	$result_sss = $db->query("SELECT member_id, schedule_id, schedule_type, start_date, end_date, DATE_FORMAT(start_time, '%H:%i') as start_time, DATE_FORMAT(end_time, '%H:%i') as end_time  FROM pt_volunteer_schedule WHERE member_id = '" . $member_id . "' ORDER BY start_date DESC, schedule_type ASC, start_time ASC");
	while( $row_sss = $db->fetch($result_sss) ) {
	    $schedule[$cnt_sss]["id"] 		        = $row_sss["schedule_id"];
	    $schedule[$cnt_sss]["schedule_id"] 		= $row_sss["schedule_id"];
    	$schedule[$cnt_sss]["schedule_type"]    = $sss_types[$row_sss["schedule_type"]];
    	$schedule[$cnt_sss]["start_date"] 		= $row_sss["start_date"];
    	$schedule[$cnt_sss]["end_date"] 		= $row_sss["end_date"];
    	$schedule[$cnt_sss]["start_time"] 		= $row_sss["start_time"];
    	$schedule[$cnt_sss]["end_time"] 		= $row_sss["end_time"];
        switch($row_sss["schedule_type"]) {
            case "0":
                $schedule[$cnt_sss]["days"] = "";
                break;
            case "1":
                $schedule[$cnt_sss]["days"] = "";
                $result_days = $db->query("SELECT * FROM pt_volunteer_schedule_day WHERE schedule_id = '" . $row_sss["schedule_id"] . "'");
                while( $row_days = $db->fetch($result_days) ) {
                   $schedule[$cnt_sss]["days"] .= ($schedule[$cnt_sss]["days"]==""?"":"; ") . $weekdays[$row_days["day"]];
                }
                break;
            case "2":
                $schedule[$cnt_sss]["days"] = "";
                $result_days = $db->query("SELECT * FROM pt_volunteer_schedule_day WHERE schedule_id = '" . $row_sss["schedule_id"] . "'");
                while( $row_days = $db->fetch($result_days) ) {
                   $schedule[$cnt_sss]["days"] .= ($schedule[$cnt_sss]["days"]==""?"":"; ") . $row_days["day"];
                }
                break;
            default:
                $schedule[$cnt_sss]["days"] = "";
                break;
        }
        $cnt_sss++;
	}


	$response["data"]["schedule"]	= $schedule;
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
