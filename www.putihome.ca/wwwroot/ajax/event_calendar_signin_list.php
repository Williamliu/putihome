 <?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
    $fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();

	$query0 = "SELECT distinct a.id FROM event_calendar a INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
					WHERE a.deleted <> 1 AND a.status = 2 AND  
						  b.deleted <> 1 AND b.status = 1   
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
		$evt_arr["title"] 			= $row1["title"];
		$evt_arr["description"] 	= $row1["description"];
		
		$resultlf = $db->query("SELECT logform FROM puti_class WHERE id = '". $row1["class_id"] ."'");
		$rowlf = $db->fetch($resultlf);
		$evt_arr["logform"] 		= $rowlf["logform"];
		
		//$evt_arr["start_date"] 		= $row1["start_date"]>0?date("M j",$row1["start_date"]):"";
		//$evt_arr["end_date"] 		= $row1["end_date"]>0?date("M j",$row1["end_date"]):"";
		$evt_arr["start_date"] 		= $row1["start_date"]>0?date("M j",$row1["start_date"]):"";
		$evt_arr["end_date"] 		= $row1["end_date"]>0?date("M j",$row1["end_date"]):"";
		$evt_arr["dates"] 			= array();
		
		$query2 = "SELECT * FROM event_calendar_date WHERE deleted <> 1 AND event_id = '" . $row1["id"] . "' ORDER BY event_date";
		$result2	= $db->query($query2);
		$cnt1 = 0;
		while( $row2 = $db->fetch($result2) ) {
			$date_arr = array();	
			$date_arr["date_id"] 		= $row2["id"];
			$date_arr["event_id"] 		= $row2["event_id"];
			$date_arr["title"]			= $row2["title"];
			$date_arr["description"] 	= $row2["description"];
			$date_arr["yy"] 			= $row2["yy"];
			$date_arr["mm"] 			= $row2["mm"];
			$date_arr["dd"] 			= $row2["dd"];
			$date_arr["event_date"] 	= $row2["event_date"]>0?date("Y-m-d",$row2["event_date"]):"";
			$date_arr["start_time"]		= $row2["start_time"];
			$date_arr["end_time"] 		= $row2["end_time"];
			
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
