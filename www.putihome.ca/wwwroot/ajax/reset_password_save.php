<?php 
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

$response = array();
try {
	$type["password_link"] 		= '{"type":"CHAR", 		"length":63, 	"id": "password_link", 	"name":"Password Link", 		"nullable":0}';
	$type["password"]			= '{"type":"CHAR", 		"length":15, 	"id": "password", 		"name":"Password", 				"nullable":0}';
	$type["rpassword"]			= '{"type":"CHAR", 		"length":15, 	"id": "rpassword", 		"name":"Confirm Password", 		"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$_REQUEST["password"] 	= trim($_REQUEST["password"]);
	$_REQUEST["rpassword"] = trim($_REQUEST["rpassword"]);
	
	if( strlen($_REQUEST["password"]) < 4 || strlen($_REQUEST["rpassword"]) < 4 ) {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= cTYPE::gstr($words["password length tips"]);
	} elseif ( $_REQUEST["password"] != $_REQUEST["rpassword"] ) {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= cTYPE::gstr($words["password not match"]);
	}

	if( $response["errorCode"] != 0 ) {
		echo json_encode($response);
		exit();
	}

		
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query 	= "SELECT id FROM puti_members WHERE password_link = '" . $_REQUEST["password_link"] . "' AND password_exp > '" . time() . "'";
	if( $db->exists($query) ) {
		$query = "UPDATE puti_members SET password_link = '', password_exp = 0, password = '" . $_REQUEST["password"] . "', password_hits = password_hits + 1 WHERE password_link = '" . $_REQUEST["password_link"] . "'";
		$db->query($query);
	} else {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= cTYPE::gstr( $words["reset password link expired"] );
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
