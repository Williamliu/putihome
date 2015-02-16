 <?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/web_language.php");

$response = array();
try {
    $fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$ccc = '';
	if( $_REQUEST["site"] != "" ) {
		$ccc .= ($ccc!=""?" AND ":" ") . "b.site = '" . $_REQUEST["site"] . "'";

		$timezone = $db->getVal("puti_sites", "timezone", $_REQUEST["site"]);
		date_default_timezone_set($timezone);
	}


	if( $_REQUEST["branch"] != "" ) {
		$ccc .= ($ccc!=""?" AND ":" ") . "b.branch = '" . $_REQUEST["branch"] . "'";
	}
	$ccc = ($ccc!=""?" AND ":"") . $ccc;
	
	//echo " ccc: " . $ccc;
	// sites
	if( $_REQUEST["site"]=="" && $_REQUEST["branch"]=="") {
		  $query0 = "SELECT distinct a.* 
					  FROM puti_sites a 
					  INNER JOIN event_calendar b ON (a.id = b.site) 
					  INNER JOIN event_calendar_date c ON (b.id = c.event_id) 
					  INNER JOIN puti_sites_branchs e ON ( a.id = e.site_id)
					  INNER JOIN puti_branchs f ON (e.branch_id = f.id) 
					  WHERE f.internal = 0 AND a.status = 1 AND b.deleted <> 1 AND b.status = 2 AND c.deleted <> 1 AND c.status = 1 $ccc 
					  ORDER BY a.sn, f.sn";
	} else {
		  $query0 = "SELECT * FROM puti_sites  
					  WHERE status = 1 AND id = '" . $_REQUEST["site"] . "'  
					  ORDER BY sn";

	}
	$result0 = $db->query($query0);
	$sites = array();
	$cnt0 = 0;
	while( $row0 = $db->fetch($result0) ) {
		$cnt0++;
		$site_id 						= $row0["id"];
		$sites[$cnt0]["site_id"]		= $row0["id"];
		$sites[$cnt0]["title"]			= cTYPE::gstr($row0["title"]);
		$sites[$cnt0]["address"]		= cTYPE::gstr($row0["address"]);
		$sites[$cnt0]["tel"]			= cTYPE::gstr($row0["tel"]);
		$sites[$cnt0]["email"]			= $row0["email"];
		$sites[$cnt0]["branchs"]  		= array();
		
		// branches
		if($_REQUEST["site"]=="" && $_REQUEST["branch"]=="") {
			$query1 = "SELECT distinct a.* FROM puti_branchs a 
								INNER JOIN event_calendar b ON (a.id = b.branch) 
								INNER JOIN event_calendar_date c ON (b.id = c.event_id)
								INNER JOIN puti_sites_branchs e ON ( a.id = e.branch_id )
								WHERE a.internal = 0 AND b.deleted <> 1 AND b.status = 2 AND c.deleted <> 1 AND c.status = 1 AND
									  e.site_id = '" . $site_id . "' AND b.site = '" . $site_id . "'  $ccc ORDER BY a.sn";
		} elseif($_REQUEST["branch"]=="") {
			$query1 = "SELECT distinct a.* FROM puti_branchs a INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) WHERE a.internal = 0 AND b.site_id = '" . $site_id . "' ORDER BY a.sn";
		} else {
			$query1 = "SELECT distinct a.* FROM puti_branchs a INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) WHERE a.internal = 0 AND b.site_id = '" . $site_id . "' AND a.id = '" . $_REQUEST["branch"] . "' ORDER BY a.sn";
		}
		
		$result1 = $db->query($query1);
		$branchs = array();
		$cnt1 = 0;
		while( $row1 = $db->fetch($result1) ) {
			$cnt1++;
			$branch_id 						= $row1["id"];
			$branchs[$cnt1]["branch_id"]	= $row1["id"];
			$branchs[$cnt1]["title"] 		= cTYPE::gstr($row1["title"]);

			// event events
			$query2 = "SELECT distinct b.id, b.site, b.branch, b.title, b.description, b.start_date, b.end_date, b.status,
			                    c.logform   
								FROM event_calendar b 
								INNER JOIN event_calendar_date a ON ( b.id = a.event_id ) 
								INNER JOIN puti_class c ON ( b.class_id = c.id )
								WHERE b.deleted <> 1 AND b.status = 2 AND a.deleted <> 1 AND a.status = 1 AND 
									  b.site = '" . $site_id . "' AND b.branch = '" . $branch_id . "' $ccc  
								ORDER BY a.event_date ASC";
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
							WHERE 	deleted <> 1 AND status = 1 AND
									event_id = '" . $event_id  . "' 
							ORDER BY event_date ASC";		
				$result3 = $db->query($query3);
				$event_dates = array();
				$cnt3 = 0;
				while( $row3 = $db->fetch($result3) ) {
					$cnt3++;
					$date_id = $row3["id"];
					$event_dates[$cnt3]["event_id"] 		= $row3["event_id"];
					$event_dates[$cnt3]["event_date_id"] 	= $row3["id"];
					$event_dates[$cnt3]["title"]			= cTYPE::gstr($row3["title"]);
					$event_dates[$cnt3]["description"] 		= cTYPE::gstr($row3["description"]);
					$event_dates[$cnt3]["yy"] 				= $row3["yy"];
					$event_dates[$cnt3]["mm"] 				= $row3["mm"];
					$event_dates[$cnt3]["dd"] 				= $row3["dd"];
					$event_dates[$cnt3]["event_date"] 		= $row3["event_date"]>0?date("Y, M j",$row3["event_date"]):"";
					$event_dates[$cnt3]["event_day"] 		= $row3["event_date"]>0?date("D",$row3["event_date"]):"";
					
					$event_dates[$cnt3]["start_time"]		= $row3["start_time"];
					$event_dates[$cnt3]["end_time"] 		= $row3["end_time"];
					$event_dates[$cnt3]["event_time"] 		= $row3["start_time"] . ($row3["end_time"]!=""?" ~ ":"") . $row3["end_time"];
				}
				$events[$cnt2]["event_dates"]	= $event_dates;
				$events[$cnt2]["count"]			= $cnt3;
				
				// end of event dates
			}
			$branchs[$cnt1]["events"]			= $events;
			$branchs[$cnt1]["count"]			= $cnt2;
			// end of events
		}
		$sites[$cnt0]["branchs"]			 	= $branchs;
		$sites[$cnt0]["count"] 					= $cnt1; 
		// end of branches
	}
	// end of sites
	
	$response["data"]["site"]  	= $_REQUEST["site"];
	$response["data"]["branch"] = $_REQUEST["branch"];
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
