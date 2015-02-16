<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", 	"name":"Event ID", 		"nullable":0}';
	$type["member_id"] 	= '{"type":"NUMBER", "length":11, "id": "member_id", 	"name":"Member ID", 	"nullable":0}';
	$type["idd"] 		= '{"type":"NUMBER", "length":11, "id": "idd", 			"name":"ID Card Number", "nullable":1}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$idd = trim($_REQUEST["idd"]);
	$mid = $_REQUEST["member_id"];
	$led = $_REQUEST["leader"];
	$vol = $_REQUEST["volunteer"];
	$trl = $_REQUEST["trial"];
	$gno = $_REQUEST["group_no"];

	if($idd != "") {
		$ccc = array();
		$ccc["member_id"] 	= $mid;
		$ccc["idd"]			= $idd;
		if( !$db->hasRow("puti_idd", $ccc) ) {
			$db->query("DELETE FROM puti_idd WHERE idd = '" . $idd . "'");
			$fields = array();
			$fields["created_time"] 	= time();
			$fields["deleted"] 			= 0;
			$fields["member_id"] 		= $mid;
			$fields["idd"] 				= $idd;
			$db->insert("puti_idd", $fields);
		}
	}

	$ccc 					= array();
	$ccc["member_id"] 		= $mid;
	$ccc["event_id"] 		= $_REQUEST["event_id"];
	
	$old_trial = $db->getVal("event_calendar_enroll","trial", $ccc);

	$fields = array();
	$fields["leader"] 		= $led;
	$fields["volunteer"] 	= $vol;
	$fields["trial"] 		= $trl;
	if($old_trial != $trl) 	$fields["trial_date"] = time();
	$fields["group_no"] 	= $gno>0?$gno:0;
	$db->update("event_calendar_enroll", $ccc, $fields);
	
	$result = $db->query("SELECT * FROM puti_idd WHERE member_id = '" . $mid . "' ORDER BY created_time DESC");
	$row 	= $db->fetch($result);
	$response["data"]["idd"] 			= $row["idd"];
	$response["data"]["member_id"] 		= $mid;
	
	echo json_encode($response);

} catch(cERR $e) {
	$response = $e->detail();
	$response["data"]["idd"] 			= $_REQUEST["idd"];
	$response["data"]["member_id"] 		= $_REQUEST["member_id"];
	echo json_encode($response);
	
} catch(Exception $e ) {
	$response["data"]["idd"] 			= $_REQUEST["idd"];
	$response["data"]["member_id"] 		= $_REQUEST["member_id"];

	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}



?>
