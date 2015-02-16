<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["id"]			= '{"type":"NUMBER","length":11, 	"id": "id", 			"name":"ID", 				"nullable":0}';
	$type["project"]	= '{"type":"CHAR", 	"length":127, 	"id": "member_id", 		"name":"Project", 			"nullable":1}';
	$type["filter"]		= '{"type":"CHAR", 	"length":127, 	"id": "filter", 		"name":"Filter", 			"nullable":1}';
	$type["keyword"]	= '{"type":"CHAR", 	"length":127, 	"id": "keyword", 		"name":"Keyword", 			"nullable":0}';
	$type["en"]			= '{"type":"ALL", 	"length":0, 	"id": "en", 			"name":"English", 			"nullable":0}';
	$type["cn"]			= '{"type":"ALL", 	"length":0, 	"id": "cn", 			"name":"English", 			"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$id 		= $_REQUEST["id"];
	$rid 		= $id;
	$project 	= strtolower(trim($_REQUEST["project"]));
	$filter 	= strtolower(trim($_REQUEST["filter"]));
	$keyword 	= strtolower(trim($_REQUEST["keyword"]));
	$en 		= trim($_REQUEST["en"]);
	$cn 		= trim($_REQUEST["cn"]);
	$tw 		= trim($_REQUEST["tw"]);
	
	if( $db->exists("SELECT id FROM website_language_word WHERE deleted <> 1 AND id <> '" . $id . "' AND keyword = '" . $keyword . "'") ) {
		  $response["errorCode"] 		= 1;
		  $response["errorMessage"] 	= "Keyword exists in our language database, please choose other one.";
		  
	} else {
		if( $id > 0 ) {
			$fields = array();
			$fields["project"] 	= $project;
			$fields["filter"] 	= $filter;
			$fields["keyword"] 	= $keyword;
			$fields["en"] 		= $en;
			$fields["cn"] 		= $cn;
			$fields["tw"] 		= $tw;
			$fields["created_time"] = time();
			$db->update("website_language_word", $id, $fields);
			$response["flag"] = 1;
		} else {
			$fields = array();
			$fields["project"] 	= $project;
			$fields["filter"] 	= $filter;
			$fields["keyword"] 	= $keyword;
			$fields["en"] 		= $en;
			$fields["cn"] 		= $cn;
			$fields["tw"] 		= $tw;
			$fields["deleted"] 	= 0;
			$fields["created_time"] = time();
			$rid = $db->insert("website_language_word", $fields);
			$response["flag"] = 2;
		}
		$response["rid"] 			= $rid;
		$response["errorCode"] 		= 0;
		$response["errorMessage"] 	= "OK";
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
