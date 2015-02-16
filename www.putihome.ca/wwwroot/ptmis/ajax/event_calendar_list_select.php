<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
    
	$fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);


	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "event_date >= '" . $sd . "' AND event_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "event_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "event_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	
	if( $_REQUEST["class_id"] != "" ) {
		$ccc .= ($ccc==""?"":" AND "). "class_id = '" . $_REQUEST["class_id"] . "'";
	}

	if( $_REQUEST["sch_status"] != "" ) {
		$ccc .= ($ccc==""?"":" AND "). "a.status = '" . $_REQUEST["sch_status"] . "'";
	}

	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	// sites
	$query0 = "SELECT distinct a0.* 
				FROM puti_sites a0 
			   	INNER JOIN event_calendar a ON (a0.id = a.site) 
				INNER JOIN event_calendar_date c ON (a.id = c.event_id) 
				WHERE a.deleted <> 1 AND 
					  c.deleted <> 1 AND
					  a.site IN " . $admin_user["sites"] . " AND
					  a.branch IN " . $admin_user["branchs"] . " 
					  $ccc   
				ORDER BY a0.sn, a.start_date ASC";

	$result0 = $db->query($query0);
	$sites = array();
	$cnt0 = 0;
	while( $row0 = $db->fetch($result0) ) {
		$cnt0++;
		$site_id 					= $row0["id"];
		$sites[$cnt0]["site_id"]	= $row0["id"];
		$sites[$cnt0]["title"]		= cTYPE::gstr($row0["title"]);
		$sites[$cnt0]["address"]	= cTYPE::gstr($row0["address"]);
		$sites[$cnt0]["tel"]		= $row0["tel"];
		$sites[$cnt0]["email"]		= $row0["email"];
		$sites[$cnt0]["branchs"]  	= array();
		
		// branches
		$query1 = "SELECT distinct a0.* FROM puti_branchs a0 
							INNER JOIN event_calendar a ON (a0.id = a.branch) 
							INNER JOIN event_calendar_date c ON (a.id = c.event_id)
							WHERE a.deleted <> 1 AND c.deleted <> 1 AND 
								  a.branch IN " . $admin_user["branchs"] . " AND a.site = '" . $site_id . "' $ccc ORDER BY a0.sn, a.start_date ASC";
		$result1 = $db->query($query1);
		$branchs = array();
		$cnt1 = 0;
		while( $row1 = $db->fetch($result1) ) {
			$cnt1++;
			$branch_id 						= $row1["id"];
			$branchs[$cnt1]["branch_id"] 	= $row1["id"];
			$branchs[$cnt1]["title"] 		= cTYPE::gstr($row1["title"]);

			// event events
			$query2 = "SELECT distinct a.id, a.site, a.branch, a.title, a.description, a.start_date, a.end_date, a.status,
			                    c.logform   
								FROM event_calendar a 
								INNER JOIN event_calendar_date b ON ( a.id = b.event_id ) 
								INNER JOIN puti_class c ON ( a.class_id = c.id )
								WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
									  a.site = '" . $site_id . "' AND a.branch = '" . $branch_id . "' $ccc 
								ORDER BY a.start_date ASC";
			$result2 = $db->query($query2);
			$events = array();
			$cnt2 = 0;
			while( $row2 = $db->fetch($result2) ) {
				$cnt2++;
				$event_id 						= $row2["id"];
				$events[$cnt2]["event_id"] 		= $row2["id"]; 
				$events[$cnt2]["title"] 		= cTYPE::gstr($row2["title"]); 
				$events[$cnt2]["description"]	= cTYPE::gstr($row2["description"]); 
				$events[$cnt2]["start_date"]	= $row2["start_date"]>0?date("Y-m-d",$row2["start_date"]):""; 
				$events[$cnt2]["end_date"]		= $row2["end_date"]>0?date("Y-m-d",$row2["end_date"]):"";
				$events[$cnt2]["event_date"]	= $row2["start_date"]>0?date("Y, M j",$row2["start_date"]):"";
				$events[$cnt2]["event_date"]   .= $row2["end_date"]>0&&$row2["end_date"]!=$row2["start_date"]?" ~ " . date("M j",$row2["end_date"]):"";
				$events[$cnt2]["status"]		= $row2["status"]; 
				$events[$cnt2]["logform"]		= $row2["logform"]; 
				
				// event dates 
				$query3 = "SELECT * FROM event_calendar_date 
							WHERE 	deleted <> 1 AND
									event_id = '" . $event_id  . "' 
							ORDER BY event_date ASC";		
				$result3 = $db->query($query3);
				$event_dates = array();
				$cnt3 = 0;
				while( $row3 = $db->fetch($result3) ) {
					$cnt3++;
					$date_id = $row3["id"];
					$event_dates[$cnt3]["event_id"] 	= $row3["event_id"];
					$event_dates[$cnt3]["event_date_id"]= $row3["id"];
					$event_dates[$cnt3]["title"]		= cTYPE::gstr($row3["title"]);
					$event_dates[$cnt3]["description"] 	= cTYPE::gstr($row3["description"]);
					$event_dates[$cnt3]["yy"] 			= $row3["yy"];
					$event_dates[$cnt3]["mm"] 			= $row3["mm"];
					$event_dates[$cnt3]["dd"] 			= $row3["dd"];
					$event_dates[$cnt3]["event_date"] 	= $row3["event_date"]>0?date("Y, M j",$row3["event_date"]):"";
					$event_dates[$cnt3]["event_day"] 	= $row3["event_date"]>0?date("D",$row3["event_date"]):"";
					
					$event_dates[$cnt3]["start_time"]	= $row3["start_time"];
					$event_dates[$cnt3]["end_time"] 	= $row3["end_time"];
					$event_dates[$cnt3]["event_time"] 	= $row3["start_time"] . ($row3["end_time"]!=""?" ~ ":"") . $row3["end_time"];
				}
				$events[$cnt2]["event_dates"]			= $event_dates;
				$events[$cnt2]["count"]					= $cnt3;
				
				// end of event dates
			}
			$branchs[$cnt1]["events"]	= $events;
			$branchs[$cnt1]["count"]	= $cnt2;
			// end of events
		}
		$sites[$cnt0]["branchs"] 	= $branchs;
		$sites[$cnt0]["count"] 		= $cnt1; 
		// end of branches
	}
	// end of sites
	
	$response["data"]["sites"] 	= $sites;	
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
