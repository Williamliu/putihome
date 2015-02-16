<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["member_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 					"name":"Member ID", 		"nullable":0}';
	$type["start_date"] 		= '{"type":"DATE", 		"length":0, 	"id": "start_date",				    "name":"Start Date", 	    "nullable":0}';
	$type["end_date"] 		    = '{"type":"DATE", 		"length":0, 	"id": "end_date",				    "name":"End Date", 	        "nullable":0}';
	$type["start_time"] 		= '{"type":"TIME", 		"length":0, 	"id": "start_time",				    "name":"Start Time", 	    "nullable":0}';
	$type["end_time"] 		    = '{"type":"TIME", 		"length":0, 	"id": "end_time",				    "name":"End Time", 	        "nullable":0}';
	$type["schedule_type"]		= '{"type":"NUMBER", 	"length":1, 	"id": "schedule_type", 		        "name":"Schedule Type", 	"nullable":0}';
	switch($_REQUEST["schedule_type"]) {
	    case "0":
            break;
        case "1":
    	    $type["weekly_days"]		= '{"type":"CHAR", 	 "length":255,  "id": "weekly_days", 		        "name":"Select WeekDay", "nullable":0}';
            break;
        case "2":
	        $type["monthly_days"]		= '{"type":"CHAR", 	"length":255, 	"id": "monthly_days", 		        "name":"Select Day", 	"nullable":0}';
            break;
	}


	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$member_id = $_REQUEST["member_id"];

	$fields = array();
	$fields["member_id"] 		= $_REQUEST["member_id"];
	$fields["start_date"] 		= $_REQUEST["start_date"];
	$fields["end_date"] 		= $_REQUEST["end_date"];
	$fields["start_time"] 		= $_REQUEST["start_time"];
	$fields["end_time"] 		= $_REQUEST["end_time"];
	$fields["schedule_type"] 	= $_REQUEST["schedule_type"];
	$fields["status"] 	        = 1; 
	$fields["deleted"] 	        = 0; 
	$fields["created_time"] 	= time();

	$schedule_id = $db->insert("pt_volunteer_schedule", $fields);
	switch($_REQUEST["schedule_type"]) {
	    case "0":
            break;
        case "1":
            $days = explode(",", $_REQUEST["weekly_days"]);
            foreach($days as $day) {
            	$fields = array();
	            $fields["schedule_id"] 	= $schedule_id;
            	$fields["day"] 		    = $day;
                $db->insert("pt_volunteer_schedule_day", $fields);    
            }
            break;
        case "2":
            $days = explode(",", $_REQUEST["monthly_days"]);
            foreach($days as $day) {
            	$fields = array();
	            $fields["schedule_id"] 	= $schedule_id;
            	$fields["day"] 		    = $day;
                $db->insert("pt_volunteer_schedule_day", $fields);    
            }
            break;
	}


    /////////////////////// schedule ///////////////////////////////////////////
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
