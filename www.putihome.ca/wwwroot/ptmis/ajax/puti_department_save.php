<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "admin_id", 		"name":"ID", 			"nullable":0}';
	$type["title"] 			= '{"type":"CHAR", 		"length":255, 	"id": "title", 			"name":"Title", 		"nullable":0}';
	$type["en_title"] 		= '{"type":"CHAR", 		"length":255, 	"id": "en_title", 		"name":"English Name", 	"nullable":1}';
	$type["description"]	= '{"type":"CHAR", 		"length":0, 	"id": "depart_desc", 	"name":"Description", 	"nullable":1}';
	$type["status"]			= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 		"nullable":0}';
	$type["sn"]				= '{"type":"NUMBER", 	"length":11, 	"id": "sn", 			"name":"Sort 序号", 			"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	if($_REQUEST["id"] < 0) {
			$fields = array();
			$fields["title"] 		= cTYPE::utrans($_REQUEST["title"]);
			$fields["en_title"] 	= cTYPE::utrans($_REQUEST["en_title"]);
			$fields["description"] 	= cTYPE::utrans($_REQUEST["description"]);
			$fields["status"] 		= $_REQUEST["status"];
			$fields["sn"] 			= $_REQUEST["sn"]?$_REQUEST["sn"]:0;
			$fields["deleted"] 		= 0;
			$fields["created_time"]	= time();
			$hid = $db->insert("puti_department", $fields);
			
			$response["data"]["old_id"] = -1;
			$response["data"]["id"] 	= $hid;
			$response["data"]["title"]  = trim($_REQUEST["title"]);

			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
	} else {
			$fields = array();
			$fields["title"] 		= cTYPE::utrans($_REQUEST["title"]);
			$fields["en_title"] 	= cTYPE::utrans($_REQUEST["en_title"]);
			$fields["description"] 	= cTYPE::utrans($_REQUEST["description"]);
			$fields["status"] 		= $_REQUEST["status"];
			$fields["sn"] 			= $_REQUEST["sn"]?$_REQUEST["sn"]:0;
			$fields["last_updated"]	= time();
			$db->update("puti_department", $_REQUEST["id"], $fields);
	
			$response["data"]["old_id"] 	= $_REQUEST["id"];
			$response["data"]["id"] 		= $_REQUEST["id"];
			$response["data"]["title"] 		= cTYPE::utrans(trim($_REQUEST["title"]));
			$response["data"]["en_title"] 	= cTYPE::utrans(trim($_REQUEST["en_title"]));
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
			$response["errorCode"] 		= 0;
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
