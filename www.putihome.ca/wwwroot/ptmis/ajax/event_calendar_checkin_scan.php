<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$trace = false;
$debug = array();

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$scan = array();
	if( $_REQUEST["event_id"]<=0 ) {
		$msg = "";
		$msg = "<br><span style='font-size:48px; font-weight:bold; color:red;'>Please select class from the list<br><br>请选择课程!</span>";

		$response["data"]["list_flag"]	= 0;
		$response["data"]["music_flag"] = 1;
		$response["data"]["flag"]	= 2;
		$response["data"]["msg"] 	= $msg;
        $scan                       = array();
        $scan["member_id"]          = -1;
    	$response["data"]["scan"] 	= $scan;
		
		$response["errorMessage"]	= "";
		$response["errorCode"] 		= 0;
	
		echo json_encode($response);
		exit();
	}

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}
	
	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];
	$apass 			= $row_class["attend"];
	$apass			= round(($apass / 100.00), 2);


	$query_checkin 	= "SELECT * FROM puti_class_checkin WHERE class_id = '" . $class_id . "'";
	$result_checkin = $db->query($query_checkin);
	$checkins		= array();
	while( $row_checkin = $db->fetch($result_checkin) ) {
		$checkins[$row_checkin["sn"]]["start_time"] = mktime($row_checkin["from_hh"],$row_checkin["from_mm"], 0, date("n"), date("j"), date("Y"));
		$checkins[$row_checkin["sn"]]["end_time"] = mktime($row_checkin["to_hh"],$row_checkin["to_mm"], 59 , date("n"), date("j"), date("Y"));
		if($trace) $debug[$row_checkin["sn"]]["start"] = $checkins[$row_checkin["sn"]]["start_time"];
		if($trace) $debug[$row_checkin["sn"]]["end"] = $checkins[$row_checkin["sn"]]["end_time"];
	}
	$ccc = array();
	$ccc["event_id"] 	= $_REQUEST["event_id"];
	$ccc["yy"] 			= date("Y");
	$ccc["mm"] 			= date("n") - 1;  // this is use for Javascript Month, JS month is zero base. 
	$ccc["dd"] 			= date("j");
	if($trace) $debug["YYMMDD"] = $ccc; 

	$event_date_id   		= $db->getVal("event_calendar_date", "id", $ccc);
	$date_checkin_num 		= $db->getVal("event_calendar_date", "checkin", $event_date_id);
	if($trace) $debug["event_date_id"] = $event_date_id; 
	if($trace) $debug["checkin_num"] = $date_checkin_num; 
	
	$member_id      = -1;

	$query0 = "SELECT a.member_id FROM puti_idd a INNER JOIN puti_members b ON (a.member_id = b.id) 
					WHERE a.idd = '" . $_REQUEST["sch_idd"] . "'";

	if( $db->exists($query0) ) {
		$query0 = "SELECT 	a.member_id, a.idd, b.status, b.deleted, b.first_name, b.last_name, b.dharma_name, b.alias, b.gender, b.email, b.phone, b.cell, b.city, b.site
						FROM puti_idd a INNER JOIN puti_members b ON (a.member_id = b.id)
						WHERE a.idd = '" . $_REQUEST["sch_idd"] . "'";

		$result_member 	= $db->query($query0);
		$row_member 	= $db->fetch($result_member);
	
		$member_id 		    = $row_member["member_id"];
		$scan["time"] 		= date("H:i - M jS");
		$scan["member_id"] 	= $row_member["member_id"];
		$scan["idd"] 		= $row_member["idd"];
		$scan["gender"] 	= $row_member["gender"];
		$scan["email"] 		= $row_member["email"];
		$scan["phone"] 		= $row_member["phone"] . ($row_member["phone"]!=""?"<br>":"") . $row_member["cell"];
		$scan["city"] 		= cTYPE::gstr($row_member["city"]);
		$scan["site"] 		= cTYPE::gstr($words[strtolower($sites[$row_member["site"]])]);
		
		$names				= array();
		$names["first_name"] 		= $row_member["first_name"];
		$names["last_name"] 		= $row_member["last_name"];
		//$names["dharma_name"] 	= $row_member["dharma_name"];
		$names["alias"] 		= $row_member["alias"];
		$scan["name"] 			= cTYPE::gstr(cTYPE::cname($names,13));

		$names				= array();
		$names["first_name"] 		= $row_member["first_name"];
		$names["last_name"] 		= $row_member["last_name"];
		$names["dharma_name"] 		= $row_member["dharma_name"];
		$names["alias"] 		= $row_member["alias"];
		$scan["name2"] 			= cTYPE::gstr(cTYPE::cname($names,13));

		$names				= array();
		$names["dharma_name"] 		= $row_member["dharma_name"];
		$scan["name1"] 			= cTYPE::gstr(cTYPE::cname($names));

		$scan["invalid"] 			= $row_member["status"] <> "1" || $row_member["deleted"] == "1"?1:0;


		$query1 = "SELECT id as enroll_id, status, deleted, group_no, shelf, trial, trial_date, paid, amt, invoice, paid_date FROM event_calendar_enroll WHERE member_id = '" . $member_id . "' AND event_id = '" . $_REQUEST["event_id"] . "'";
		if( !$db->exists($query1) ) $scan["unenroll"] = 1;
		$result_enroll 	= $db->query($query1);
		$row_enroll 	= $db->fetch($result_enroll);
		$enroll_id 		= $row_enroll["enroll_id"]?$row_enroll["enroll_id"]:-1;
		if($trace) $debug["enroll_id"] = $enroll_id; 
		
		$scan["group_no"] 	= intval($row_enroll["group_no"]>0)?'<b>' . $row_enroll["group_no"] . '</b>':'';
		$scan["shelf"] 		= cTYPE::shelfSN($row_enroll["shelf"], $CFG["max_shoes_rack"]);

		$scan["paid"] 		= $row_enroll["paid"]?"Y":"";
		$scan["amt"] 		= $row_enroll["amt"]>0?$row_enroll["amt"]:"";
		$scan["invoice"] 	= $row_enroll["invoice"]?$row_enroll["invoice"]:"";
		$scan["paid_date"]	= $row_enroll["paid_date"]>0?date("Y-m-d",$row_enroll["paid_date"]):"";

		if( $payfree == "1") {
			$scan["paid"] 		= "Free";
			$scan["amt"] 		= "";
			$scan["invoice"] 	= "";
			$scan["paid_date"]	= "";
		} else {
			if( $payonce == "1" ) {
				  $query8 = "SELECT paid, amt, paid_date , invoice 
								  FROM event_calendar  a
								  INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
								  WHERE a.class_id = '" . $class_id . "' AND paid = 1 AND
									  b.member_id = '" . $member_id . "' 
								  ORDER BY paid_date DESC, amt DESC";
				  $result8 	= $db->query($query8);
				  $row8 	= $db->fetch($result8); 		
	
				  $scan["paid"] 		= $row8["paid"]?"Y":"";
				  $scan["amt"] 			= $row8["amt"]>0?$row8["amt"]:"";
				  $scan["invoice"] 		= $row8["invoice"]?$row8["invoice"]:"";
				  $scan["paid_date"]	= $row8["paid_date"]>0?date("Y-m-d",$row8["paid_date"]):"";
			}
		}
		$scan["trial"]			= $row_enroll["trial"]<>1?0:1;
		$scan["trial_length"] 	= cTYPE::dhms( time() - $row_enroll["trial_date"] );
		$scan["trial_exp"] 		= ($scan["trial"]==1 && $row_enroll["trial_date"]>0)?( time()<=($row_enroll["trial_date"] + $CFG["trial_date"]*24*3600)?0:1):0;
		$scan["unauth"] 		= $row_enroll["deleted"] <> "1"?0:1;

		$scan["state"]			= $row_enroll["trial"]<>1?0:1;



		$verify_txt	= '';
		if( $scan["trial"] 	== 1) 		$verify_txt = '<span style="color:red;">(' . $words["trial"] . ')</span>'; 
		if( $scan["paid"] != "Y" && $scan["paid"] != "Free") 		$verify_txt = '<span style="color:red;">(' . $words["unpaid"] . ')</span>'; 
		if( $scan["trial_exp"] 	== 1) 	$verify_txt = '<span style="color:red;">(' . $words["trial_exp"] . ')</span>'; 
		if( $scan["unenroll"] == 1) 	$verify_txt = '<span style="color:red;">(' . $words["unenroll"] . ')</span>';  
		if( $scan["unauth"] == 1) 		$verify_txt = '<span style="color:red;">(' . $words["unauth"] . ')</span>';  
		if( $scan["invalid"] == 1) 		$verify_txt = '<span style="color:red;">(' . $words["invalid member"] . ')</span>';  
		
		$scan["music_flag"]	= max(($scan["paid"]=="Y"||$scan["paid"]=="Free"?0:1), ($scan["trial"]?$scan["trial_exp"]:0), $scan["unenroll"], $scan["unauth"], $scan["invalid"]);
		$scan["state"] 		= $scan["music_flag"]==1?2:$scan["state"];

		$response["data"]["music_flag"] 	= $scan["music_flag"];
			
		// display message
		if( $photo == "1" ) {
			$msg = '<table border="0" cellpadding="0" cellspacing="0" style="margin-left:0px;">';
			$msg .= '<tr>';
			$msg .= '<td valign="middle" align="center" style="white-space:nowrap;">';
				$msg .= '<span style="color:black;font-size:32px;">' . $words["welcome"] . '</span>';  
			$msg .= '</td>';
			$msg .= '<td valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<span style="color:blue; font-size:90px;">' . $scan["name"] . '</span>';	
			$msg .= '</td>';
			$msg .= '<td rowspan="3" valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<img src="ajax/lwhUpload_image.php?ts='. time() .'&size=tiny&img_id=' . $member_id . '" style="margin-left:20px;margin-top:0px; border:2px solid #dddddd;" height="250" />';  
			$msg .= '</td>';
			$msg .= '</tr>';
	
			$msg .= '<tr>';
			$msg .= '<td></td>';
			$msg .= '<td valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<span style="color:blue; font-size:72px;">' . $scan["name1"] . '</span>';	
			$msg .= '</td>';
			$msg .= '</tr>';
	
			$msg .= '<tr>';
			$msg .= '<td valign="middle" align="right" style="white-space:nowrap;">';
				$msg .= '<span style="font-size:24px;">' . $words["id card"] . ': </span>';	
			$msg .= '</td>';
			$msg .= '<td valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<span style="color:blue;font-size:30px;">' . $_REQUEST["sch_idd"] . ' ' . $verify_txt . '</span>';	
			$msg .= '</td>';
			$msg .= '</tr>';
			$msg .= '</table>';
		} else {
			$msg = '<table border="0" cellpadding="0" cellspacing="0" style="margin-left:0px;">';
			$msg .= '<tr>';
			$msg .= '<td valign="middle" align="center" style="white-space:nowrap;">';
				$msg .= '<span style="color:black;font-size:32px;">' . $words["welcome"] . '</span>';  
			$msg .= '</td>';
			$msg .= '<td valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<span style="color:blue; font-size:90px;">' . $scan["name"] . '</span>';	
			$msg .= '</td>';
			$msg .= '</tr>';
	
			$msg .= '<tr>';
			$msg .= '<td></td>';
			$msg .= '<td valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<span style="color:blue; font-size:72px;">' . $scan["name1"] . '</span>';	
			$msg .= '</td>';
			$msg .= '</tr>';
	
			$msg .= '<tr>';
			$msg .= '<td valign="middle" align="right" style="white-space:nowrap;">';
				$msg .= '<span style="font-size:24px;">' . $words["id card"] . ': </span>';	
			$msg .= '</td>';
			$msg .= '<td valign="middle" align="left" style="white-space:nowrap;">';
				$msg .= '<span style="color:blue;font-size:30px;">' . $_REQUEST["sch_idd"] . ' ' . $verify_txt . '</span>';	
			$msg .= '</td>';
			$msg .= '</tr>';
			$msg .= '</table>';
		}
		// end of display mesaage


		$id_scan_time = mktime(date("H"), date("i"), 0 , date("n"), date("j"), date("Y"));
		if($trace) $debug["cur_date"] = date("Y") . "-" . date("n") . "-" . date("j") . " " . date("H") . ":" .  date("i") . ":00"; 

		if($trace) $debug["scan_time"] = $id_scan_time; 
		
		$fields = array();
		$fields["member_id"] 	= $member_id;
		$fields["idd"] 			= $_REQUEST["sch_idd"]; 
		$fields["purpose"] 		= "event";
		$fields["ref_id"] 		= $_REQUEST["event_id"];
		$fields["created_time"] = $id_scan_time;

		$scan["id"]						= -1;
		if( !$db->hasRow("puti_attend", $fields) ) {
			$insert_id 					= $db->insert("puti_attend", $fields);		
			$scan["id"] 				= $insert_id;
		} else {
			$scan["id"]					= $db->getVal("puti_attend", "id", $fields);
		}
	

		// update user enroll checkin status
		if( $enroll_id > 0 ) {
			for($i = 1; $i <= $date_checkin_num; $i++) {
				if($trace) $debug["attend"][$i]["sn"] = $i; 
				if( $id_scan_time >= $checkins[$i]["start_time"] && $id_scan_time <= $checkins[$i]["end_time"] ) {
				    $fields = array();
				    $fields["sn"] 				= $i;
				    $fields["event_date_id"] 	= $event_date_id;
				    $fields["enroll_id"]		= $enroll_id;
    				if($trace) $debug["table"][$i]["sn"] = $i; 
    				if($trace) $debug["table"][$i]["event_date_id"] = $event_date_id; 
    				if($trace) $debug["table"][$i]["enroll_id"] = $enroll_id; 

				    if( !$db->hasRow("event_calendar_attend", $fields) ) {
						  	$fields["status"]		= 2;
						    $db->insert("event_calendar_attend", $fields);
				    } else {
							$db->update("event_calendar_attend", $fields, array("status"=>2));					
					}
				}
			}
		}
		// end update
	
	
		// update attandance percentage
		$result0 = $db->query("SELECT SUM(checkin) as total_num FROM  event_calendar_date WHERE event_id = '" . $_REQUEST["event_id"] . "'");
		$row0 = $db->fetch($result0);
		$total_check_number = intval($row0["total_num"]);
		
		$db->query("UPDATE event_calendar_enroll SET attend = 0 WHERE id = '" . $enroll_id . "'");
		
		$query1 = "SELECT SUM(IF(a.status=2 OR a.status=8, 1, 0)) as attend_cnt 
						FROM event_calendar_attend a 
                        INNER JOIN event_calendar_date c ON (a.event_date_id = c.id AND a.sn <= c.checkin) 
						INNER JOIN event_calendar_enroll b ON (a.enroll_id = b.id) 
						WHERE a.enroll_id = '" . $enroll_id . "'"; 
											
		$result1 	= $db->query($query1);
		$row1 		= $db->fetch($result1);
		$attend_cnt = intval($row1["attend_cnt"]);
		$percent	= round($attend_cnt / $total_check_number, 2);
		
		$fields 	= array();
		$fields["attend"] = $percent;
		if( $fields["attend"] >= $apass ) {
				$fields["graduate"] = 1;
				$fields["cert"] 	= 1;
		} else {
				$fields["graduate"] = 0;
				//$fields["cert"] 	= 1;
		}
		$db->update("event_calendar_enroll", $enroll_id, $fields);
		// end update		
		
	
		/* commend by william temporary 		
	
		// grand total people count, time comsumming if record to many	
		$query1 = "SELECT count( distinct a.member_id ) as st_cnt, count(a.id) as ph_cnt  
						FROM puti_attend a 
                        LEFT JOIN ( SELECT * FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "') c ON ( a.member_id = c.member_id AND a.ref_id = c.event_id ) 
						WHERE a.created_time BETWEEN '" . mktime(0,0,0,date("n"), date("j"), date("Y")) . "' AND '" . mktime(23,59,59,date("n"), date("j"), date("Y")) . "' 
						AND a.purpose = 'event' AND a.ref_id = '" . $_REQUEST["event_id"] . "'" ;
	
		$result1 	= $db->query($query1);
		$row1 		= $db->fetch($result1);

		end of commend by william tempoary */


		$response["data"]["debug"]			= $debug;
		$response["data"]["list_flag"]			= 1;
		$response["data"]["others"]["total_student"] 	= $row1["st_cnt"];
		$response["data"]["others"]["total_punch"] 	= $row1["ph_cnt"];
		
	
	} else {
		$msg = "<br><span style='font-size:64px; font-weight:bold; color:red;'>" . $words["read card error"] . "</span>
				<br><span style='font-size:32px;color:blue;'>" . $words["id card"] . ":</span> <span style='color:blue;margin-left:15px; font-size:40px;'>" . $_REQUEST["sch_idd"] . "</span>";
		$response["data"]["music_flag"] = 1;
		$response["data"]["list_flag"]	= 0;

        $scan                       = array();
        $scan["member_id"]          = -1;
    	$response["data"]["scan"] 	= $scan;
	}
	
	
	$response["data"]["msg"] 	= $msg;
	$response["data"]["idd"] 	= $_REQUEST["sch_idd"];
	$response["data"]["scan"] 	= $scan;

	$response["errorMessage"]	= "";
	$response["errorCode"] 		= 0;

	echo json_encode($response);

//} catch(cERR $e) {
//	echo json_encode($e->detail());

} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}
?>
