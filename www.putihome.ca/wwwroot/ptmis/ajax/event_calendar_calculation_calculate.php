<?php 
//session_start();
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
	$eid 	= $_REQUEST["event_id"];
	$times 	= $_REQUEST["times"]; 

	$class_id 	= $db->getVal("event_calendar", "class_id", $_REQUEST["event_id"]);
	$apass 		= $db->getVal("puti_class", 	"attend", 	$class_id);
	$apass		= round(($apass / 100.00), 2);
	
	// DELETE Attendance Calculate
	$sd = cTYPE::datetoint($_REQUEST["start_date"] . " 00:00:00");
	$ed = cTYPE::datetoint($_REQUEST["end_date"] . " 23:59:59");
	/*
	$query0 = "DELETE a.* FROM event_calendar_attend  a 
				INNER JOIN event_calendar_enroll b ON( a.enroll_id = b.id )
	    		INNER JOIN event_calendar_date c ON(a.class_date_id = c.class_date_id)
				WHERE 	b.event_id = '" . $eid . "' AND
				      	c.event_id = '" . $eid . "' AND
						c.event_date BETWEEN $sd AND $ed";
	*/

	$query0 = "UPDATE event_calendar_attend  a 
				INNER JOIN event_calendar_enroll b ON( a.enroll_id = b.id )
	    		INNER JOIN event_calendar_date c ON(a.event_date_id = c.id)
				SET a.status = 0 
				WHERE 	a.status = 2 AND 
						b.event_id = '" . $eid . "' AND
				      	c.event_id = '" . $eid . "' AND
						c.event_date BETWEEN $sd AND $ed";
	$db->query($query0);
	// end of delete;


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
	// end of criteria



	// attend record
	$query0 = "SELECT a.id, a.member_id, a.idd, a.created_time, b.first_name, b.last_name, b.dharma_name, b.email, b.phone, b.cell, b.gender, b.city 
					FROM puti_attend a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE $ccc AND a.purpose = 'event' AND a.ref_id = '" . $eid . "' 
					ORDER BY a.created_time";
	$result0 = $db->query($query0);
	$mcnt = 0;
	$cnt00 = 0;
	while($row0 = $db->fetch($result0)) {
		$cnt00++;
		$mid = $row0["member_id"];
		
		if( $db->exists("SELECT id FROM event_calendar_enroll WHERE deleted <> 1 AND  member_id = '" . $mid . "' AND event_id = '". $eid . "'") )  {
			$res_eid = $db->query("SELECT id FROM event_calendar_enroll WHERE deleted <> 1 AND  member_id = '" . $mid . "' AND event_id = '". $eid . "'");
			$row_eid = $db->fetch($res_eid);
			$enroll_id = $row_eid["id"];

	        
            ///////////////////////////////////////////////////////////////////////////////////////////////////
            //echo "enroll id: $enroll_id  mid:$mid   ev id:$eid\n";
		    $class_yy = intval(date("Y", $row0["created_time"]));
		    $class_nn = intval(date("n", $row0["created_time"]));
		    $class_dd = intval(date("d", $row0["created_time"]));
		    $class_hh = intval(date("H", $row0["created_time"]));
		    $class_mm = intval(date("i", $row0["created_time"]));
		
		    $xxx = array();
		    $xxx["event_id"] 	= $eid;
		    $xxx["yy"]			= $class_yy;
		    $xxx["mm"]			= $class_nn - 1;
		    $xxx["dd"]			= $class_dd;
		    $event_date_id 		= $db->getVal("event_calendar_date", "id", $xxx);
		    $cal_times 			= $db->getVal("event_calendar_date", "checkin", $event_date_id);
		    $cnt9 = 0;
		    foreach($times as $val) {
			    $cnt9++;
			    if($cnt9 > $cal_times) { 
				    //echo " cnt9: ". $cnt9 . " times:" . $cal_times . "\n"; 
				    break; 
			    }
			
			    $sn  = intval($val["sn"]);
			    $fhh = intval($val["fhh"]);
			    $fmm = intval($val["fmm"]);
			    $thh = intval($val["thh"]);
			    $tmm = intval($val["tmm"]);

			    $stime  = mktime($fhh, $fmm, 0,0,0,0);
			    $etime  = mktime($thh, $tmm, 0,0,0,0);
			    $curtime = mktime($class_hh, $class_mm, 0,0,0,0);
			    //echo "$fhh:$fmm ~ $thh:$tmm = $class_hh:$class_mm \n"; 
			    if($curtime >= $stime && $curtime <= $etime ) {
				    $fields = array();
				    $fields["sn"] 				= $sn;
				    $fields["event_date_id"] 	= $event_date_id;
				    $fields["enroll_id"]		= $enroll_id;
				    //echo "sn: $sn  did: $class_date_id   eid: $enroll_id\n";
				    if( !$db->hasRow("event_calendar_attend", $fields) ) {
						  	$fields["status"]		= 2;
						    $db->insert("event_calendar_attend", $fields);
						    $mcnt++;
				    } else {
							$db->update("event_calendar_attend", $fields, array("status"=>2));					
						    $mcnt++;
					}
			    }
		    }
            ////////////////////////////////////////////////////////////////////////////
        } 
	}
	// end of insert  while
	// end of attend record
	
	
	
	// id card reader record
	$event_site = $db->getVal("event_calendar", "site", $_REQUEST["event_id"]);
	$query0 = "SELECT a.id, a.member_id, a.site, a.place, c.title as site_desc, d.title as place_desc, a.idd, a.created_time, 
						  b.first_name, b.last_name, b.dharma_name, b.alias, b.legal_first, b.legal_last, b.phone, b.cell, b.email, b.city, b.gender, e.title as member_site 
						FROM puti_device_record a
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						INNER JOIN puti_sites c ON (a.site = c.id)
						INNER JOIN puti_places d ON (a.place = d.id)
						INNER JOIN puti_sites e ON ( b.site = e.id) 
						WHERE $ccc AND a.site = '" . $event_site . "' AND a.place = '" . $_REQUEST["place"] . "' AND b.deleted <> 1  ORDER BY a.created_time DESC";
	
	$result0 = $db->query($query0);
	$id_mcnt = 0;
	$id_cnt00 = 0;
	while($row0 = $db->fetch($result0)) {
		$id_cnt00++;
		$mid = $row0["member_id"];
		
		if( $db->exists("SELECT id FROM event_calendar_enroll WHERE deleted <> 1 AND  member_id = '" . $mid . "' AND event_id = '". $eid . "'") )  {
			$res_eid = $db->query("SELECT id FROM event_calendar_enroll WHERE deleted <> 1 AND  member_id = '" . $mid . "' AND event_id = '". $eid . "'");
			$row_eid = $db->fetch($res_eid);
			$enroll_id = $row_eid["id"];
		
	        /////////////////////////////////////////////////////////////////////////////////////////////
            //echo "enroll id: $enroll_id  mid:$mid   ev id:$eid\n";
		    $class_yy = intval(date("Y", $row0["created_time"]));
		    $class_nn = intval(date("n", $row0["created_time"]));
		    $class_dd = intval(date("d", $row0["created_time"]));
		    $class_hh = intval(date("H", $row0["created_time"]));
		    $class_mm = intval(date("i", $row0["created_time"]));
		
		    $xxx = array();
		    $xxx["event_id"] 	= $eid;
		    $xxx["yy"]			= $class_yy;
		    $xxx["mm"]			= $class_nn - 1;
		    $xxx["dd"]			= $class_dd;
		    $event_date_id 		= $db->getVal("event_calendar_date", "id", $xxx);
		    $cal_times 			= $db->getVal("event_calendar_date", "checkin", $event_date_id);
		    $cnt9 = 0;
		    foreach($times as $val) {
			    $cnt9++;
			    if($cnt9 > $cal_times) { 
				    //echo " cnt9: ". $cnt9 . " times:" . $cal_times . "\n"; 
				    break; 
			    }
			
			    $sn  = intval($val["sn"]);
			    $fhh = intval($val["fhh"]);
			    $fmm = intval($val["fmm"]);
			    $thh = intval($val["thh"]);
			    $tmm = intval($val["tmm"]);

			    $stime  = mktime($fhh, $fmm, 0,0,0,0);
			    $etime  = mktime($thh, $tmm, 0,0,0,0);
			    $curtime = mktime($class_hh, $class_mm, 0,0,0,0);
			    //echo "$fhh:$fmm ~ $thh:$tmm = $class_hh:$class_mm \n"; 
			    if($curtime >= $stime && $curtime <= $etime ) {
				    $fields = array();
				    $fields["sn"] 				= $sn;
				    $fields["event_date_id"] 	= $event_date_id;
				    $fields["enroll_id"]		= $enroll_id;
				    //echo "sn: $sn  did: $class_date_id   eid: $enroll_id\n";
				    if( !$db->hasRow("event_calendar_attend", $fields) ) {
						  	$fields["status"]		= 2;
						    $db->insert("event_calendar_attend", $fields);
						    $id_mcnt++;
				    } else {
							$db->update("event_calendar_attend", $fields, array("status"=>2));					
						    $id_mcnt++;
					}
			    }
		    }
            ////////////////////////////////////////////////////////////////////////////////////
        } 
	}
	// end of insert  while
	// end of id card reader record




	// update attandance percentage
	$result0 = $db->query("SELECT SUM(checkin) as total_num FROM  event_calendar_date WHERE event_id = '" . $eid . "'");
	$row0 = $db->fetch($result0);
	$total_check_number = intval($row0["total_num"]);
	
	$db->query("UPDATE event_calendar_enroll SET attend = 0 WHERE event_id = '" . $eid . "'");
	
	$query1 = "SELECT a.enroll_id as enroll_id, SUM(IF(a.status=2 OR a.status=8, 1, 0)) as attend_cnt 
					FROM 	event_calendar_attend a
                    INNER JOIN event_calendar_date c ON (a.event_date_id = c.id AND a.sn <= c.checkin) 
					INNER JOIN event_calendar_enroll b ON (a.enroll_id = b.id) 
					WHERE b.event_id = '" . $eid . "' 
					GROUP BY a.enroll_id";
				
	$result1 = $db->query($query1);
	while( $row1 = $db->fetch($result1) ) {
		$enroll_id 	= $row1["enroll_id"];
		$attend_cnt = intval($row1["attend_cnt"]);
	    $percent	= round($attend_cnt / $total_check_number, 2);
		$fields 	= array();
		$fields["attend"] = $percent;
		if( $fields["attend"]>=$apass ) {
			$fields["graduate"] = 1;
			$fields["cert"] 	= 1;
		} else {
			$fields["graduate"] = 0;
			//$fields["cert"] 	= 0;
		}
		//echo "eid:" . $enroll_id . "  total:" . $total_check_number . "  ac:" . $attend_cnt . " p:" . $percent .  "\n";
		$db->update("event_calendar_enroll", $enroll_id, $fields);
	}
	
	$response["errorMessage"]	= "There are " . $mcnt . " records (Total: " . $cnt00 . ")\nhas been processed to attendance.";
	$response["errorMessage"]	.= "\n\n\nID Card Reader: " . $id_mcnt . " records (Total: " . $id_cnt00 . ")\nhas been processed to attendance.";
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
