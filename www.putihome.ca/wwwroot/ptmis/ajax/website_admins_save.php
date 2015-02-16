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
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":127, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":31, 	"id": "city", 			"name":"City", 			"nullable":1}';
	$type["user_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "user_name", 		"name":"User Name", 	"nullable":0}';
	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":1}';
	$type["site"]				= '{"type":"NUMBER", 	"length":11, 	"id": "site", 			"name":"Site", 			"nullable":0}';
	$type["branch"]				= '{"type":"NUMBER", 	"length":11, 	"id": "branch", 		"name":"Group", 		"nullable":1}';
	$type["group_id"]			= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Role", 			"nullable":0}';
	$type["status"]				= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 		"nullable":0}';
	$type["depart"]				= '{"type":"CHAR", 		"length":1023, 	"id": "depart", 		"name":"Department", 	"nullable":1}';
	$type["sites"]				= '{"type":"CHAR", 		"length":1023, 	"id": "depart", 		"name":"Sites", 		"nullable":1}';
	$type["branchs"]			= '{"type":"CHAR", 		"length":1023, 	"id": "depart", 		"name":"Groups", 		"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	if($_REQUEST["admin_id"] < 0) {
		if(trim($_REQUEST["email"]) != "" ) {
			$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "' OR email = '" . trim($db->quote($_REQUEST["email"])) . "')";
		} else {
			$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "')";
		}
		$result = $db->query( $query );
		if( $db->row_nums($result) > 0 )  {
		 	$response["errorMessage"]	= "<br>Either user name '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "' or email '" . trim($db->quote($_REQUEST["email"])) . "' has already used, <br>Please specify user name or email for the new adminstrator.";
			$response["errorCode"] 		= 1;
		} else {
			$fields = array();
				
			$fields["first_name"] 		= cTYPE::utrans($_REQUEST["first_name"]);
			$fields["last_name"] 		= cTYPE::utrans($_REQUEST["last_name"]);
			$fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
			$fields["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
			$fields["cell"] 			= cTYPE::phone($_REQUEST["cell"]);
			$fields["city"] 			= cTYPE::utrans($_REQUEST["city"]);
			$fields["user_name"] 		= cTYPE::utrans($_REQUEST["user_name"]);
			$fields["email"] 			= $_REQUEST["email"];
			$fields["site"] 			= $_REQUEST["site"];
			$fields["branch"] 			= $_REQUEST["branch"];
			$fields["sites"] 			= $_REQUEST["sites"];
			$fields["branchs"] 			= $_REQUEST["branchs"];
			$fields["group_id"] 		= $_REQUEST["group_id"];
			$fields["status"] 			= $_REQUEST["status"];
			$fields["department"] 		= $_REQUEST["depart"];
			$fields["deleted"] 			= 0;
			$fields["hits"] 			= 0;
			$fields["created_time"]		= time();
			$admin_id = $db->insert("website_admins", $fields);
			
			$response["data"]["old_id"] 	= -1;
			$response["data"]["admin_id"] 	= $admin_id;
			$response["data"]["first_name"] = cTYPE::utrans(trim($_REQUEST["first_name"]));
			$response["data"]["last_name"] 	= cTYPE::utrans(trim($_REQUEST["last_name"]));
			$response["data"]["user_name"] 	= cTYPE::utrans(trim($_REQUEST["user_name"]));
			$response["data"]["created_time"] = cTYPE::inttodate(time());

			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
		}
	} else {
		if( trim($_REQUEST["email"]) != "" ) {
			$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND id <> '" . $db->quote($_REQUEST["admin_id"]) . "' AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "' OR email = '" . trim($db->quote($_REQUEST["email"])) . "')";
		} else {
			$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND id <> '" . $db->quote($_REQUEST["admin_id"]) . "' AND  user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["user_name"]))) . "'";
		}
		$result = $db->query( $query );
		if( $db->row_nums($result) > 0 )  {
		 	$response["errorMessage"]	= "<br>Either user name '" . trim($_REQUEST["user_name"]) . "' or email '" . cTYPE::utrans(trim($_REQUEST["email"])) . "' has already used, <br>Please specify user name or email for the new adminstrator.";
			$response["errorCode"] 		= 1;
		} else {
			$fields = array();
			$fields["first_name"] 		= cTYPE::utrans($_REQUEST["first_name"]);
			$fields["last_name"] 		= cTYPE::utrans($_REQUEST["last_name"]);
			$fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
			$fields["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
			$fields["cell"] 			= cTYPE::phone($_REQUEST["cell"]);
			$fields["city"] 			= cTYPE::utrans($_REQUEST["city"]);
			$fields["user_name"] 		= cTYPE::utrans($_REQUEST["user_name"]);
			$fields["email"] 			= $_REQUEST["email"];
			$fields["site"] 			= $_REQUEST["site"];
			$fields["branch"] 			= $_REQUEST["branch"];
			$fields["sites"] 			= $_REQUEST["sites"];
			$fields["branchs"] 			= $_REQUEST["branchs"];
			$fields["group_id"] 		= $_REQUEST["group_id"];
			$fields["status"] 			= $_REQUEST["status"];
			$fields["department"] 		= $_REQUEST["depart"];
			$fields["last_updated"]		= time();
			$db->update("website_admins", $_REQUEST["admin_id"], $fields);
	
			$response["data"]["old_id"] 		= $_REQUEST["admin_id"];
			$response["data"]["admin_id"] 		= $_REQUEST["admin_id"];
			$response["data"]["first_name"] 	= cTYPE::gstr(trim($_REQUEST["first_name"]));
			$response["data"]["last_name"] 		= cTYPE::gstr(trim($_REQUEST["last_name"]));
			$response["data"]["user_name"] 		= cTYPE::gstr(trim($_REQUEST["user_name"]));
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
