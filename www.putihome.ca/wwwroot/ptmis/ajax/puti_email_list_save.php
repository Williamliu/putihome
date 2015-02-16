<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["member_id"]			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 		"name":"Member ID", 	"nullable":0}';
	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":0}';
	
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query_del = "SELECT member_id FROM puti_email WHERE member_id = '" . $_REQUEST["member_id"] . "' AND admin_id = '" . $admin_user["id"] . "'";
	if( !$db->exists( $query_del ) ) {
		$fields 					= array();
		$fields["admin_id"] 		= $admin_user["id"];
		$fields["member_id"] 		= $_REQUEST["member_id"];
		$fields["created_time"] 	= time();
		$db->insert("puti_email", 	$fields);

	} else {
		$ccc = array();
		$ccc["admin_id"] 	=  $admin_user["id"];
		$ccc["member_id"] 	= $_REQUEST["member_id"];
		$fields 					= array();
		$fields["last_updated"] 	= time();
		$db->update("puti_email",$ccc, $fields);
	} 

	$fields 					= array();
	$fields["email"] 			= $_REQUEST["email"];
	$fields["email_flag"] 		=  $_REQUEST["email_flag"];
	$db->update("puti_members", $_REQUEST["member_id"], $fields);

	$response["data"]["member_id"]	= $_REQUEST["member_id"];
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
