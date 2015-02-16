<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["member_id"] 	= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 					"name":"Member ID", 		"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$member_id = $_REQUEST["member_id"];
	$ccc = array();
	$ccc["member_id"] = $member_id;

	$detail = array();

	$result = $db->query("SELECT * FROM pt_volunteer WHERE member_id = '" . $member_id . "'");
	$row = $db->fetch($result);
	$detail["resume"] 			= $row["resume"];
	$detail["memo"] 			= $row["memo"];
	$detail["status"] 			= $row["status"];
	$detail["vol_type"] 		= $row["vol_type"];

	$result = $db->query("SELECT email_flag FROM puti_members WHERE id = '" . $member_id . "'");
	$row = $db->fetch($result);
	$detail["email_flag"] 		= $row["email_flag"];

	$result = $db->query("SELECT * FROM pt_volunteer_others WHERE member_id = '" . $member_id . "'");
	$row = $db->fetch($result);
	$detail["professional_other"] 	= $row["professional_other"];
	$detail["health_other"] 		= $row["health_other"];
	
	$result = $db->query("SELECT * FROM pt_volunteer_professional WHERE member_id = '" . $member_id . "'");
	$arrs = $db->attrs($result, "professional_id");
    $detail["professional"] = $db->astr($arrs);

	$result = $db->query("SELECT * FROM pt_volunteer_health WHERE member_id = '" . $member_id . "'");
	$arrs = $db->attrs($result, "health_id");
    $detail["health"] = $db->astr($arrs);

	$result = $db->query("SELECT * FROM pt_volunteer_depart_current WHERE member_id = '" . $member_id . "'");
	$arrs = $db->attrs($result, "depart_id");
    $detail["depart_current"] = $db->astr($arrs);

	$result = $db->query("SELECT b.* FROM pt_volunteer_depart_current a INNER JOIN pt_department b on (a.depart_id = b.id) WHERE member_id = '" . $member_id . "'");
	$arrs = $db->attrs($result, ($admin_user["lang"]=="en"?"title_en":"title_cn"));
    $detail["depart_current_html"] = $db->astr($arrs);


	$result = $db->query("SELECT * FROM pt_volunteer_depart_will WHERE member_id = '" . $member_id . "'");
	$arrs = $db->attrs($result, "depart_id");
    $detail["depart_will"] = $db->astr($arrs);

	$result = $db->query("SELECT b.* FROM pt_volunteer_depart_will a INNER JOIN pt_department b on (a.depart_id = b.id) WHERE member_id = '" . $member_id . "'");
	$arrs = $db->attrs($result, ($admin_user["lang"]=="en"?"title_en":"title_cn"));
    $detail["depart_will_html"] = $db->astr($arrs);
	

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
	    $schedule[$cnt_sss]["id"] 		= $row_sss["schedule_id"];
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
                $result_days = $db->query("SELECT * FROM pt_volunteer_schedule_day WHERE schedule_id = '" . $row_sss["schedule_id"] . "' ORDER BY day ASC");
                while( $row_days = $db->fetch($result_days) ) {
                   $schedule[$cnt_sss]["days"] .= ($schedule[$cnt_sss]["days"]==""?"":"; ") . $weekdays[$row_days["day"]];
                }
                break;
            case "2":
                $schedule[$cnt_sss]["days"] = "";
                $result_days = $db->query("SELECT * FROM pt_volunteer_schedule_day WHERE schedule_id = '" . $row_sss["schedule_id"] . "' ORDER BY day ASC");
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
    ////////////////////////////////////////////////////////////////////////////////

	$response["data"]["schedule"]	= $schedule;
	$response["data"]["detail"]	= $detail;
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
