<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	if( $admin_user["site"]<="0" || $admin_user["branch"]<="0") {
		$response["errorMessage"]	= "<br>You are not belong to any teaching group.<br><br>You don't have right to create class.";
		$response["errorCode"] 		= 1;
		echo json_encode($response);
		exit();
	} 
	
	$type["title"]				= '{"type":"CHAR", 	"length":255, 	"id": "title", 				"name":"Class Title", 		"nullable":0}';
	$type["date_length"]		= '{"type":"NUMBER", "length":11, 	"id": "date_length", 		"name":"Date Length", 		"nullable":0}';
	$type["agreement"]			= '{"type":"NUMBER", "length":11, 	"id": "agreement", 			"name":"Agreement", 		"nullable":0}';
	$type["logform"]			= '{"type":"NUMBER", "length":11, 	"id": "logform", 			"name":"Register Form", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST["cls"]);
	$type1["title"]				= '{"type":"CHAR", 	"length":255, 	"id": "title", 				"name":"At lease adding one day to class", 		"nullable":0}';
	cTYPE::validate($type1, $_REQUEST["cls"]["dates"][0]);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$fields = array();
	$fields["site"] 		= $admin_user["site"];
	$fields["branch"] 		= $admin_user["branch"];
	$fields["title"] 		= cTYPE::utrans($_REQUEST["cls"]["title"]);
	$fields["description"] 	= cTYPE::utrans($_REQUEST["cls"]["description"]);
	$fields["agreement"] 	= $_REQUEST["cls"]["agreement"];
	$fields["date_length"] 	= $_REQUEST["cls"]["date_length"];
	$fields["checkin"] 		= $_REQUEST["cls"]["checkin"]>0?$_REQUEST["cls"]["checkin"]:0;
	$fields["attend"] 		= $_REQUEST["cls"]["attend"];
	$fields["meal"] 		= $_REQUEST["cls"]["meal"];
	$fields["cert"] 		= $_REQUEST["cls"]["cert"];
	$fields["cert_prefix"] 	= $_REQUEST["cls"]["cert_prefix"];
	$fields["photo"] 		= $_REQUEST["cls"]["photo"];
	$fields["payfree"] 		= $_REQUEST["cls"]["payfree"];
	$fields["payonce"] 		= $_REQUEST["cls"]["payonce"];
	$fields["logform"] 		= $_REQUEST["cls"]["logform"];
	$fields["status"] 			= 1;
	$fields["deleted"] 			= 0;
	$fields["created_time"] 	= time();

	$cls_id = $db->insert("puti_class", $fields);

	$dates = $_REQUEST["cls"]["dates"];	
	foreach($dates as $date) {
		$fields = array();
		$fields["class_id"] 		= $cls_id;
		$fields["day_no"] 			= $date["day_no"];
		$fields["start_time"]		= $date["start_time"];
		$fields["end_time"]			= $date["end_time"];
		$fields["title"] 			= cTYPE::utrans($date["title"]);
		$fields["description"] 		= cTYPE::utrans($date["description"]);
		$fields["checkin"] 			= $date["checkin"]>0?$date["checkin"]:0;
		$fields["meal"] 			= $date["meal"];

		$date_id = $db->insert("puti_class_date", $fields);
	}
	
	$checks = $_REQUEST["carr"];
	foreach($checks as $cc) {
		if(is_array( $cc ) ) {
			$fields = array();
			$fields["class_id"] 	= $cls_id;
			$fields["sn"] 			= $cc["sn"];
			$fields["from_hh"] 		= $cc["fhh"];
			$fields["from_mm"] 		= $cc["fmm"];
			$fields["to_hh"] 		= $cc["thh"];
			$fields["to_mm"] 		= $cc["tmm"];
			$db->insert("puti_class_checkin", $fields);
		}
	}
	$response["errorMessage"]	= "<br>Class has been saved to database.";
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
