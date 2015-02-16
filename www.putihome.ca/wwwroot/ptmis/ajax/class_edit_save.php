<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["title"]				= '{"type":"CHAR", 	"length":255, 	"id": "title", 				"name":"Class Title", 		"nullable":0}';
	$type["agreement"]			= '{"type":"NUMBER", "length":11, 	"id": "agreement", 			"name":"Agreement", 		"nullable":0}';
	$type["logform"]			= '{"type":"NUMBER", "length":11, 	"id": "logform", 			"name":"Register Form", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST["cls"]);
	$type1["title"]				= '{"type":"CHAR", 	"length":255, 	"id": "title", 				"name":"At lease adding one day to class", 		"nullable":0}';
	cTYPE::validate($type1, $_REQUEST["cls"]["dates"][0]);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$fields = array();
	$fields["title"] 		= cTYPE::utrans($_REQUEST["cls"]["title"]);
	$fields["description"] 	= cTYPE::utrans($_REQUEST["cls"]["description"]);
	$fields["agreement"] 	= $_REQUEST["cls"]["agreement"];
	$fields["sn"] 	        = $_REQUEST["cls"]["sn"];
	$fields["cert"] 		= $_REQUEST["cls"]["cert"];
	$fields["cert_prefix"] 	= $_REQUEST["cls"]["cert_prefix"];
	$fields["logform"] 		= $_REQUEST["cls"]["logform"];
	$fields["status"] 		= $_REQUEST["cls"]["status"];
	$fields["checkin"] 		= $_REQUEST["cls"]["checkin"]>0?$_REQUEST["cls"]["checkin"]:0;
	$fields["attend"] 		= $_REQUEST["cls"]["attend"];
	$fields["photo"] 		= $_REQUEST["cls"]["photo"];
	$fields["payfree"] 		= $_REQUEST["cls"]["payfree"];
	$fields["payonce"] 		= $_REQUEST["cls"]["payonce"];
	$fields["last_updated"] = time();

	$db->update("puti_class", $_REQUEST["cls"]["id"], $fields);
	$ccc = array();
	$ccc["class_id"] = $_REQUEST["cls"]["id"];
	$db->delete("puti_class_checkin", $ccc);
	$checks = $_REQUEST["cls"]["checkarr"];
	foreach($checks as $ck) {
		if(is_array($ck)) {	
		  $fields = array();
		  $fields["class_id"]		= $_REQUEST["cls"]["id"];
		  $fields["sn"]				= $ck["sn"];
		  $fields["from_hh"]		= $ck["fhh"];
		  $fields["from_mm"]		= $ck["fmm"];
		  $fields["to_hh"]			= $ck["thh"];
		  $fields["to_mm"]			= $ck["tmm"];
		  $db->insert("puti_class_checkin", $fields);
		}
	}

	$dates = $_REQUEST["cls"]["dates"];	
	foreach($dates as $date) {
		$fields = array();
		$fields["start_time"]		= $date["start_time"];
		$fields["end_time"]			= $date["end_time"];
		$fields["title"] 			= cTYPE::utrans($date["title"]);
		$fields["description"] 		= cTYPE::utrans($date["description"]);
		$fields["checkin"] 			= $date["checkin"]>0?$date["checkin"]:0;
		$fields["meal"] 			= $date["meal"];

		$db->update("puti_class_date", $date["id"], $fields);
	}
	$response["data"]["id"] 		= $_REQUEST["cls"]["id"];
	$response["data"]["class_id"] 	= $_REQUEST["cls"]["id"];
	$response["data"]["title"] 		= cTYPE::utrans($_REQUEST["cls"]["title"]);
	$response["errorMessage"]		= "<br>Class has been saved to database.";
	$response["errorCode"] 			= 0;

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
