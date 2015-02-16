<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["agreement_id"] 	= '{"type":"NUMBER", 	"length":11, 	"id": "agreement_id", 	"name":"Agree ID", 		"nullable":0}';
	$type["lang_id"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "lang_id", 		"name":"Lang ID", 		"nullable":0}';
	$type["subject"] 		= '{"type":"CHAR", 		"length":255, 	"id": "title", 			"name":"Title", 		"nullable":0}';
	$type["title"] 			= '{"type":"CHAR", 		"length":255, 	"id": "title", 			"name":"Title", 		"nullable":0}';
	$type["desc"]			= '{"type":"ALL", 		"length":0, 	"id": "agree_desc", 	"name":"Description", 	"nullable":0}';
	$type["status"]			= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["agreement_id"] < 0) {
			$fields = array();
			$fields["subject"] 			= cTYPE::utrans($_REQUEST["subject"]);
			$fields["status"] 			= $_REQUEST["status"];
			$fields["deleted"] 			= 0;
			$fields["created_time"]		= time();
			$agreement_id = $db->insert("puti_agreement", $fields);

			
			$fields = array();
			$fields["agreement_id"] 	= $agreement_id;
			$fields["lang"] 			= $admin_user["lang"];
			$fields["title"] 			= cTYPE::utrans($_REQUEST["title"]);
			$fields["description"]		= cTYPE::utrans($_REQUEST["desc"]);
			$lang_id = $db->insert("puti_agreement_lang", $fields);
			
			$response["data"]["old_id"] 	= -1;
			$response["data"]["agreement_id"] 	= $agreement_id;
			$response["data"]["lang_id"] 		= $lang_id;
			$response["data"]["subject"]		= cTYPE::utrans($_REQUEST["subject"]);
			$response["data"]["title"] 			= cTYPE::utrans($_REQUEST["title"]);
			$response["data"]["desc"] 			= cTYPE::utrans($_REQUEST["desc"]);

			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
	} else {
			$fields = array();
			$fields["subject"] 			= cTYPE::utrans($_REQUEST["subject"]);
			$fields["status"] 			= $_REQUEST["status"];
			$fields["last_updated"]		= time();
			$db->update("puti_agreement", $_REQUEST["agreement_id"], $fields);

			
			if($_REQUEST["lang_id"] < 0) {
				$fields = array();
				$fields["agreement_id"] 	= $_REQUEST["agreement_id"];
				$fields["lang"] 			= $admin_user["lang"];
				$fields["title"] 			= cTYPE::utrans(trim($_REQUEST["title"]));
				$fields["description"]		= cTYPE::utrans($_REQUEST["desc"]);
				$lang_id = $db->insert("puti_agreement_lang", $fields);
			} else {
				$fields = array();
				$fields["title"] 			= cTYPE::utrans(trim($_REQUEST["title"]));
				$fields["description"] 		= cTYPE::utrans($_REQUEST["desc"]);
				$db->update("puti_agreement_lang", $_REQUEST["lang_id"], $fields);
				$lang_id = $_REQUEST["lang_id"];
			}
	
			$response["data"]["old_id"] 		= $_REQUEST["agreement_id"];
			$response["data"]["agreement_id"] 	= $_REQUEST["agreement_id"];
			$response["data"]["lang_id"] 		= $lang_id;
			$response["data"]["subject"] 		= cTYPE::utrans(trim($_REQUEST["subject"]));
			$response["data"]["title"] 			= cTYPE::utrans(trim($_REQUEST["title"]));
			$response["data"]["desc"] 			= cTYPE::utrans($_REQUEST["desc"]);
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
