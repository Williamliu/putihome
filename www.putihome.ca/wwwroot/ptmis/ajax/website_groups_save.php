<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["group_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';
	$type["group_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "group_name", 	"name":"Group Name", 	"nullable":0}';
	$type["group_desc"]			= '{"type":"ALL", 		"length":1023, 	"id": "group_desc", 	"name":"Description", 	"nullable":1}';
	$type["group_status"]		= '{"type":"NUMBER", 	"length":4, 	"id": "status", 		"name":"Group Status", 	"nullable":0}';
	$type["level"]				= '{"type":"NUMBER", 	"length":4, 	"id": "level", 			"name":"Right Class", 	"nullable":0}';
	$type["group_right"]		= '{"type":"CHAR", 		"length":0, 	"id": "group_right", 	"name":"Group Right", 	"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["group_id"] < 0) {
		$query = "SELECT id FROM website_groups WHERE deleted <> 1 AND name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["group_name"]))) . "'";
		$result = $db->query( $query );
		if( $db->row_nums($result) > 0 )  {
		 	$response["errorMessage"]	= "<br>New group name '" . cTYPE::utrans(trim($_REQUEST["group_name"])) . "' has already used, <br>Please specify other name for the new group name.";
			$response["errorCode"] 		= 1;
		} else {
			$fields = array();
				
			$fields["name"] 			= cTYPE::utrans($_REQUEST["group_name"]);
			$fields["description"] 		= cTYPE::utrans($_REQUEST["group_desc"]);
			$fields["group_right"] 		= str_replace("\\","",$_REQUEST["group_right"]);
			$fields["status"] 			= $_REQUEST["group_status"];
			$fields["level"] 			= $_REQUEST["level"];
			$fields["deleted"] 			= 0;
			$fields["created_time"]		= time();
			$group_id = $db->insert("website_groups", $fields);
			
			$response["data"]["old_id"] 	= -1;
			$response["data"]["group_id"] 	= $group_id;
			$response["data"]["group_name"] = cTYPE::utrans(trim($_REQUEST["group_name"]));
			$response["data"]["group_title"] = $_REQUEST["level"] . '. ' . cTYPE::utrans(trim($_REQUEST["group_name"]));
			$response["data"]["created_time"] = cTYPE::inttodate(time());
			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
		}
	} else {
		$query = "SELECT id FROM website_groups WHERE deleted <> 1 AND id <> '" . $db->quote($_REQUEST["group_id"]) . "' AND name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["group_name"]))) . "'";
		$result = $db->query( $query );
		if( $db->row_nums($result) > 0 )  {
		 	$response["errorMessage"]	= "<br>Group name '" . cTYPE::utrans(trim($_REQUEST["group_name"])) . "' has already used, Please specify other name for the new group name.";
			$response["errorCode"] 		= 1;
		} else {
			$fields = array();
			$fields["name"] 			= cTYPE::utrans($_REQUEST["group_name"]);
			$fields["description"] 		= cTYPE::utrans($_REQUEST["group_desc"]);
			$fields["group_right"] 		= str_replace("\\","",$_REQUEST["group_right"]);
			$fields["level"] 			= $_REQUEST["level"];
			$fields["status"] 			= $_REQUEST["group_status"];
			$fields["deleted"] 			= 0;
			$fields["last_updated"]		= time();
			$db->update("website_groups", $_REQUEST["group_id"], $fields);
	
			$response["data"]["old_id"] 		= $_REQUEST["group_id"];
			$response["data"]["group_id"] 		= $_REQUEST["group_id"];
			$response["data"]["group_name"] 	= cTYPE::utrans(trim($_REQUEST["group_name"]));
			$response["data"]["group_title"] 	= $_REQUEST["level"] . '. ' . cTYPE::utrans(trim($_REQUEST["group_name"]));
			$response["data"]["last_updated"] 	= cTYPE::inttodate(time());
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
			$response["errorCode"] 		= 0;
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
