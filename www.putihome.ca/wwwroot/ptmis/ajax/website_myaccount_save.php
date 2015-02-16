<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["admin_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "admin_id", 		"name":"Admin ID", 		"nullable":0}';
	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';
	$type["user_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "user_name", 		"name":"User Name", 	"nullable":0}';
	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if(trim($_REQUEST["email"])!="") {
		$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND id <> '" . $_REQUEST["admin_id"] . "' AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "' OR email = '" . trim($db->quote($_REQUEST["email"])) . "')";
	} else {
		$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND id <> '" . $db->quote($_REQUEST["admin_id"]) . "' AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "')";
	}
	$result = $db->query( $query );
	if( $db->row_nums($result) > 0 )  {
		$response["errorMessage"]	= "<br>Either user name '" . cTYPE::utrans(trim($_REQUEST["user_name"])) . "' or email '" . trim($_REQUEST["email"]) . "' has already used, <br>Please specify user name or email for the new adminstrator.";
		$response["errorCode"] 		= 1;
	} else {
		$fields = array();
		$fields["first_name"] 		= cTYPE::utrans($_REQUEST["first_name"]);
		$fields["last_name"] 		= cTYPE::utrans($_REQUEST["last_name"]);
		$fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
		$fields["phone"] 			= $_REQUEST["phone"];
		$fields["cell"] 			= $_REQUEST["cell"];
		$fields["city"] 			= $_REQUEST["city"];
		$fields["user_name"] 		= cTYPE::utrans($_REQUEST["user_name"]);
		$fields["email"] 			= $_REQUEST["email"];
		$fields["last_updated"]		= time();
		$db->update("website_admins", $_REQUEST["admin_id"], $fields);

		$response["data"]["admin_id"] 		= $_REQUEST["admin_id"];
		$response["data"]["first_name"] 	= cTYPE::utrans(trim($_REQUEST["first_name"]));
		$response["data"]["last_name"] 		= cTYPE::utrans(trim($_REQUEST["last_name"]));
		$response["data"]["user_name"] 		= cTYPE::utrans(trim($_REQUEST["user_name"]));
		$response["data"]["last_updated"] 	= cTYPE::inttodate(time());
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
