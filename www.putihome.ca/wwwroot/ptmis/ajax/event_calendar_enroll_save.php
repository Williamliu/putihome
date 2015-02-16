<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["member_id"]			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 		"name":"Member ID", 				"nullable":0}';
	$type["event_id"]			= '{"type":"NUMBER", 	"length":11, 	"id": "event_id", 		"name":"Please select an event", 	"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$group_no 	= trim($_REQUEST["group_no"])!=""?$_REQUEST["group_no"]:0;
	$onsite 	= $_REQUEST["onsite"]?$_REQUEST["onsite"]:0;
	$trial 		= $_REQUEST["trial"]?$_REQUEST["trial"]:0;
    $signin     = $_REQUEST["signin"]?$_REQUEST["signin"]:0;
	$idd		= trim($_REQUEST["idd"]);

	$query_class = "SELECT a.class_id, a.start_date FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
	$result_class = $db->query($query_class);
	$row_class = $db->fetch($result_class);
	$class_id = $row_class["class_id"];
	$evt_start_date = $row_class["start_date"];
	
	$query = "SELECT id, deleted, unauth, status, group_no, onsite, trial, shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $_REQUEST["member_id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);

	if( $db->row_nums($result) > 0 )  {
		  $ccc = array();
		  $ccc["event_id"] 	= $_REQUEST["event_id"];
		  $ccc["member_id"] = $_REQUEST["member_id"];
		  $fields = array();
		  $fields["deleted"] 		= 0;
		  $fields["unauth"] 		= 0;
		  $fields["status"] 		= 1;
		  $fields["created_time"] 	= time();

		  if( $_REQUEST["group_no"] != "" ) $fields["group_no"] = $group_no;
		  if( $_REQUEST["onsite"] 	!= "" ) $fields["onsite"] 	= $onsite;
    	  if( $_REQUEST["signin"] 	!= "" ) $fields["signin"] = $signin;

		  if( $_REQUEST["trial"] 	!= $row["trial"] ) {
			   $fields["trial"] 	= $trial;
			   $fields["trial_date"]= time();
		  }
		  
		  $shelf = intval($row["shelf"]);
		  if( $shelf <= 0 ) {
			$query_del = "SELECT id, shelf FROM event_calendar_enroll WHERE deleted = 1 AND shelf > 0 AND event_id = '" . $_REQUEST["event_id"] . "' ORDER BY shelf ASC";
		    if( $db->exists( $query_del ) ) {
				$result_del = $db->query( $query_del );
				$row_del = $db->fetch( $result_del );
				$db->query("UPDATE event_calendar_enroll SET shelf = 0 WHERE id = '" . $row_del["id"] . "'");
				$shelf = $row_del["shelf"];
			} else {
				$query_max = "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "'";
				$result_max = $db->query( $query_max );
				$row_max = $db->fetch( $result_max );
				$shelf = intval($row_max["max_shelf"]) + 1;
			} 
		  }
		  $fields["shelf"] = $shelf;  

		  $result_new = $db->query("SELECT count(a.id) as cnt FROM event_calendar_enroll a 
		  											INNER JOIN event_calendar b ON (a.event_id = b.id) 
													WHERE 	b.class_id = '" . $class_id . "' AND 
															a.member_id = '" . $_REQUEST["member_id"] . "' AND
															b.start_date < '" . $evt_start_date . "' AND b.deleted <> 1 AND 
													     	(a.graduate = 1 OR a.cert = 1 )");
		  $row_new = $db->fetch($result_new);
		  if($row_new["cnt"] > 0) 
		  		$fields["new_flag"] = 0;
		  else 
		  		$fields["new_flag"] = 1; 
		  
		  $db->update("event_calendar_enroll", $ccc, $fields);
	  
		  $query0 	= "SELECT * FROM  event_calendar_enroll WHERE event_id = '" .  $_REQUEST["event_id"] . "' AND member_id = '" . $_REQUEST["member_id"] . "'";
		  $result0 	= $db->query($query0);
		  $row0 	= $db->fetch($result0);
		  
		  $scan 					= array();
		  $scan["trial"] 			= $row0["trial"];
		  $scan["trial_date"] 		= cTYPE::dhms( time() - $row0["trial_date"] );
		  $scan["trial_exp"] 		= $row0["trial_date"]>0?( time()<=($row0["trial_date"] + $CFG["trial_date"]*24*3600)?0:1 ):0;
	  
		  $scan["unauth"] 			= $row0["unauth"];
		  $scan["enroll_flag"] 		= $row0["unauth"]==0?
										  ($row0["trial"]==1?($evt_arr["trial_exp"]==1?'<a class="enroll-status-stop"></a>':'<a class="enroll-status-trial"></a>'):'<span style="color:blue;">Yes</span>')
										  :'<a class="student-enroll" event_id="' . $con["event_id"] . '" member_id="' . $row0["member_id"] . '" title="' . $words["enroll"] . '"><a>';
		  $response["data"]["trial"]		= $scan;
		  
		  $response["data"]["event_id"]		= $_REQUEST["event_id"];
		  $response["data"]["member_id"]	= $_REQUEST["member_id"];
		  $response["data"]["shelf"]		= cTYPE::shelfSN($shelf, $CFG["max_shoes_rack"]);
		  
	} else {
		$fields = array();
		$fields["event_id"] 		= $_REQUEST["event_id"];
		$fields["member_id"] 		= $_REQUEST["member_id"];

		$fields["group_no"] 		= $group_no;
		$fields["onsite"] 			= $onsite;
   	    $fields["signin"] 	        = $signin;
		$fields["trial"] 			= $trial;
		$fields["trial_date"] 		= time();


		$query_del = "SELECT id, shelf FROM event_calendar_enroll WHERE deleted = 1 AND shelf > 0 AND event_id = '" . $_REQUEST["event_id"] . "' ORDER BY shelf ASC";
		if( $db->exists( $query_del ) ) {
			$result_del = $db->query( $query_del );
			$row_del = $db->fetch( $result_del );
			$db->query("UPDATE event_calendar_enroll SET shelf = 0 WHERE id = '" . $row_del["id"] . "'");
			$shelf = $row_del["shelf"];
		} else {
			$query_max = "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "'";
			$result_max = $db->query( $query_max );
			$row_max = $db->fetch( $result_max );
			$shelf = intval($row_max["max_shelf"]) + 1;
		} 
		$fields["shelf"] 			= $shelf;

		  $result_new = $db->query("SELECT count(a.id) as cnt FROM event_calendar_enroll a 
		  											INNER JOIN event_calendar b ON (a.event_id = b.id) 
													WHERE 	b.class_id = '" . $class_id . "' AND 
															a.member_id = '" . $_REQUEST["member_id"] . "' AND
															b.start_date < '" . $evt_start_date . "' AND b.deleted <> 1 AND 
													     	(a.graduate = 1 OR a.cert = 1 )");
		  $row_new = $db->fetch($result_new);
		  if($row_new["cnt"] > 0) 
		  		$fields["new_flag"] = 0;
		  else 
		  		$fields["new_flag"] = 1; 

		$fields["status"] 			= 1;
		$fields["deleted"] 			= 0;
		$fields["created_time"] 	= time();
		$enroll_id = $db->insert("event_calendar_enroll", $fields);

		$response["data"]["enroll_id"]	= $enroll_id;		
		$response["data"]["member_id"]	= $_REQUEST["member_id"];
		$response["data"]["event_id"]	= $_REQUEST["event_id"];
		$response["data"]["shelf"]		= cTYPE::shelfSN($shelf, $CFG["max_shoes_rack"]);
	
	}

	$db->query("UPDATE puti_members SET apply_date = UNIX_TIMESTAMP() WHERE id = '" . $_REQUEST["member_id"] . "'");
	if($idd != "") {
		$ccc = array();
		$ccc["member_id"] 	= $_REQUEST["member_id"];
		$ccc["idd"]			= $idd;
		if( !$db->hasRow("puti_idd", $ccc) ) {
			$db->query("DELETE FROM puti_idd WHERE idd = '" . $idd . "'");
			$fields = array();
			$fields["created_time"] 	= time();
			$fields["deleted"] 			= 0;
			$fields["member_id"] 		= $_REQUEST["member_id"];
			$fields["idd"] 				= $idd;
			$db->insert("puti_idd", $fields);
		}
	}
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
