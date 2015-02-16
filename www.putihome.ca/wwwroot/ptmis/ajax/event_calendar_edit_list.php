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
	$evt = array();

	$query0 = "SELECT distinct a.id FROM event_calendar a INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
					WHERE a.deleted <> 1 AND a.status = 1 AND
						  b.deleted <> 1 AND b.status = 1 AND 
						  event_date >= '" . $fdate . "'  
					ORDER BY event_date";

	$result0 = $db->query($query0);
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		
		$evt_id 	= $row0["id"];
		$query1 	= "SELECT * FROM event_calendar WHERE deleted <> 1 AND id = '" . $evt_id . "'";
		$result1	= $db->query($query1);
		$row1 		= $db->fetch($result1);
		
		$evt_arr["id"] 				= $row1["id"];
		$evt_arr["site"] 			= $db->getVal("puti_sites","title", $row1["site"]);
		$evt_arr["branch"] 			= $db->getVal("puti_branchs","title", $row1["branch"]);
		$evt_arr["title"] 			= cTYPE::gstr($row1["title"]);
		$evt_arr["description"] 	= cTYPE::gstr($row1["description"]);
		$evt_arr["start_date"] 		= $row1["start_date"]>0?date("Y-m-d",$row1["start_date"]):"";
		$evt_arr["end_date"] 		= $row1["end_date"]>0?date("Y-m-d",$row1["end_date"]):"";
		$evt_arr["dates"] 			= array();
		
		$query2 = "SELECT * FROM event_calendar_date WHERE deleted <> 1 AND event_id = '" . $row1["id"] . "'";
		$result2	= $db->query($query2);
		$cnt1 = 0;
		while( $row2 = $db->fetch($result2) ) {
			$date_arr = array();	
			$date_arr["site"] 			= $db->getVal("puti_sites","title", $row1["site"]);
			$date_arr["branch"] 		= $db->getVal("puti_branchs","title", $row1["branch"]);
			$date_arr["date_id"] 		= $row2["id"];
			$date_arr["event_id"] 		= $row2["event_id"];
			$date_arr["title"]			= cTYPE::gstr($row2["title"]);
			$date_arr["description"] 	= cTYPE::gstr($row2["description"]);
			$date_arr["yy"] 			= $row2["yy"];
			$date_arr["mm"] 			= $row2["mm"];
			$date_arr["dd"] 			= $row2["dd"];
			$date_arr["event_date"] 	= $row2["event_date"]>0?date("Y-m-d",$row2["event_date"]):"";
			$date_arr["times"] 			= array();
			
			$query3 = "SELECT * FROM event_calendar_time WHERE deleted <> 1 AND event_date_id = '" . $row2["id"] . "'";
			$result3	= $db->query($query3);
			$cnt2 = 0;
			while( $row3 = $db->fetch($result3) ) {
				//echo "here";
				$time_arr = array();
				$time_arr["event_id"] 		= $row2["event_id"];
				$time_arr["date_id"] 		= $row2["date_id"];
				$time_arr["time_id"] 		= $row3["id"];
				$time_arr["title"] 			= cTYPE::gstr($row3["title"]);
				$time_arr["description"] 	= cTYPE::gstr($row3["description"]);
				$time_arr["start_time"] 	= $row3["start_time"];
				$time_arr["end_time"] 		= $row3["end_time"];
				$date_arr["times"][$cnt2] 	= $time_arr;
				$cnt2++;
			}
			$evt_arr["dates"][$cnt1] = $date_arr;
			$cnt1++;
		}
		$evt[$cnt0]					= $evt_arr;
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
