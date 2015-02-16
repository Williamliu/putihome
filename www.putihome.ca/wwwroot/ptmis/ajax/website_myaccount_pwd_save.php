<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
$admin_menu="8,0";
$admin_right="save";
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["admin_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "admin_id", 		"name":"Admin ID", 		"nullable":0}';
	$type["password"] 			= '{"type":"CHAR", 		"length":15, 	"id": "password", 		"name":"Password", 		"nullable":0}';
	$type["repassword"] 		= '{"type":"CHAR", 		"length":15, 	"id": "repassword", 	"name":"Confrim Password", 	"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$_REQUEST["password"] 	= trim($_REQUEST["password"]);
	$_REQUEST["repassword"] = trim($_REQUEST["repassword"]);
	
	if( strlen($_REQUEST["password"]) < 4 || strlen($_REQUEST["repassword"]) < 4 ) {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= "<br>密码必须是四位字符或以上,可以是数字或者英文字母.";
	} elseif ( $_REQUEST["password"] != $_REQUEST["repassword"] ) {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= "<br>你所设置的密码和确认密码不相符,请确保密码一致.";
	}

	if(	$response["errorCode"] == 0 ) {
		$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
		$query = "UPDATE website_admins SET password = '" . $_REQUEST["password"] . "', last_updated = '" . time() . "' WHERE deleted <> 1 AND id = '" . $db->quote($_REQUEST["admin_id"]) . "'";
		$db->query($query);
		$response["data"]["last_updated"] = cTYPE::inttodate(time());
		$response["errorCode"] 		= 0;
	 	$response["errorMessage"]	= "<br>密码已成功保存,请妥善保存好你的密码.";
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
