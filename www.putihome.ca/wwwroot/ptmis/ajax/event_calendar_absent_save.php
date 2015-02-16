<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$class_id 	= $db->getVal("event_calendar", "class_id", $_REQUEST["event_id"]);
	$apass 		= $db->getVal("puti_class", 	"attend", 	$class_id);
	$apass		= round(($apass / 100.00), 2);
	
	
	$enrolls = $_REQUEST["attend"];	
	//echo " count: " . count($enrolls);
	//print_r($enrolls[201]);

	foreach($enrolls as $enroll) {
		$db->query("UPDATE event_calendar_attend SET status = 0  WHERE status = 8 AND enroll_id = '" . $enroll["enroll_id"] . "'");

		if( $enroll["attend"] != "") {
			$ddd = explode(",", $enroll["attend"]);
			foreach( $ddd as $dd ) {
				$tmp = explode(":", $dd);

				$fields = array();
				$fields["sn"] 				= $tmp[1];
				$fields["event_date_id"] 	= $tmp[0];
				$fields["enroll_id"]		= $enroll["enroll_id"];
				//echo "sn: $sn  did: $class_date_id   eid: $enroll_id\n";
				if( !$db->hasRow("event_calendar_attend", $fields) ) {
						$fields["status"]		= 8;
						$db->insert("event_calendar_attend", $fields);
				} else {
						$db->update("event_calendar_attend", $fields, array("status"=>8));					
				}
			}
		}


		// update attandance percentage
		$result0 = $db->query("SELECT SUM(checkin) as total_num FROM  event_calendar_date	WHERE event_id = '" . $_REQUEST["event_id"] . "'");
		$row0 = $db->fetch($result0);
		$total_check_number = intval($row0["total_num"]);

		$db->query("UPDATE event_calendar_enroll SET attend = 0 WHERE event_id = '" .  $_REQUEST["event_id"] . "' AND id = '" . $enroll["enroll_id"] . "'");
		
		$query1 = "SELECT a.enroll_id as enroll_id, SUM(IF(a.status=2 OR a.status=8, 1, 0)) as attend_cnt 
						FROM 	event_calendar_attend a 
                        INNER JOIN event_calendar_date c ON (a.event_date_id = c.id AND a.sn <= c.checkin) 
						INNER JOIN event_calendar_enroll b ON (a.enroll_id = b.id) 
						WHERE b.event_id = '" . $_REQUEST["event_id"] . "' AND b.id = '" . $enroll["enroll_id"] . "'"; 
					
		$result1 = $db->query($query1);
		$row1 = $db->fetch($result1);
		$enroll_id 	= $row1["enroll_id"];
		$attend_cnt = intval($row1["attend_cnt"]);
		$percent	= round($attend_cnt / $total_check_number, 2);
		$fields 	= array();
		$fields["trial"]		= $enroll["trial"]?$enroll["trial"]:0;
		$fields["signin"]		= $enroll["signin"]?$enroll["signin"]:0;
		$fields["attend"]		= $percent;
		if( $fields["attend"]>=$apass ) {
				$fields["graduate"] = 1;
				$fields["cert"] 	= 1;
		} else {
				$fields["graduate"] = 0;
				$fields["cert"] 	= 0;
		}
			//echo "eid:" . $enroll_id . "  total:" . $total_check_number . "  ac:" . $attend_cnt . " p:" . $percent .  "\n";
		$db->update("event_calendar_enroll", $enroll["enroll_id"], $fields);
		
				
	}
	$response["errorMessage"]	= "<br>Data has been saved to database.";
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
