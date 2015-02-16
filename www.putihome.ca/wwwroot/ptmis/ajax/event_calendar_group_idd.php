<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$idds = $_REQUEST["idds"];
	/*
	echo "<pre>";
	print_r($idds);
	echo "</pre>";
	*/
	$used = array();
	foreach($idds as $key=>$val) {
		if(trim($val["idd"]) != "") {
			$ccc 				= array();
			$ccc["idd"] 		= trim($val["idd"]);
			$ccc["member_id"] 	= $val["member_id"];
			if( !$db->hasRow("puti_idd",$ccc) ) {
				$db->query("DELETE FROM puti_idd WHERE idd = '" . trim($val["idd"]) . "'");
				$fields = array();
				$fields["created_time"] 	= time();
				$fields["deleted"] 			= 0;
				$fields["member_id"] 		= $val["member_id"];
				$fields["idd"] 				= trim($val["idd"]);
				$db->insert("puti_idd", $fields);
			}
		}

		$ccc 				= array();
		$ccc["member_id"] 	= $val["member_id"];
		$ccc["event_id"] 	= $_REQUEST["event_id"];

		$old_trial = $db->getVal("event_calendar_enroll","trial", $ccc);

		$fields = array();
		$fields["leader"] 			= $val["leader"];
		$fields["volunteer"] 		= $val["volunteer"];
		$fields["trial"] 			= $val["trial"];
		if($old_trial != $val["trial"]) $fields["trial_date"] = time();
		$fields["group_no"] 		= $val["group_no"]>0?$val["group_no"]:0;

		$db->update("event_calendar_enroll", $ccc, $fields);
		
		/*	
		if( !$db->hasRow("puti_idd", $ccc) ) {
			$fields = array();
			$fields["created_time"] 	= time();
			$fields["member_id"] 		= $val["member_id"];
			$fields["idd"] 				= $val["idd"];
			$db->insert("puti_idd", $fields);
		} else {
			$mid = $db->getVal("puti_idd", "member_id", $ccc);
			if( $mid != $val["member_id"] ) $used[] = $val["member_id"];
		}
		*/
	}

	$response["data"]["used"] 		= $used;

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
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
