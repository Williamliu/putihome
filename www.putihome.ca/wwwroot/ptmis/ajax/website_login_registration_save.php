<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';
	$type["user_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "user_name", 		"name":"User Name", 	"nullable":0}';
	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":0}';
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
	  
		  $query = "SELECT id FROM website_admins WHERE deleted <> 1 AND ( user_name = '" . trim($db->quote($_REQUEST["user_name"])) . "' OR email = '" . trim($db->quote($_REQUEST["email"])) . "')";
		  $result = $db->query( $query );
		  if( $db->row_nums($result) > 0 )  {
			  $response["errorMessage"]	= "<br>Either user name '" . trim($_REQUEST["user_name"]) . "' or email '" . trim($_REQUEST["email"]) . "' <br>has already registrated in our system.<br><br>If you forgot your password, please use forget password link <br>to retrieve your password.";
			  $response["errorCode"] 		= 1;
		  } else {
			  $fields = array();
			  $fields["first_name"] 	= $_REQUEST["first_name"];
			  $fields["last_name"] 		= $_REQUEST["last_name"];
			  $fields["dharma_name"] 	= $_REQUEST["dharma_name"];
			  $fields["phone"] 			= $_REQUEST["phone"];
			  $fields["cell"] 			= $_REQUEST["cell"];
			  $fields["city"] 			= $_REQUEST["city"];
			  $fields["user_name"] 		= $_REQUEST["user_name"];
			  $fields["email"] 			= $_REQUEST["email"];
			  $fields["status"] 		= 0;
			  $fields["deleted"] 		= 0;
			  $fields["created_time"]	= time();
			  $admin_id = $db->insert("website_admins", $fields);
	  
			  $response["data"]["admin_id"] 		= $admin_id;
			  $response["data"]["first_name"] 		= trim($_REQUEST["first_name"]);
			  $response["data"]["last_name"] 		= trim($_REQUEST["last_name"]);
			  $response["data"]["user_name"] 		= trim($_REQUEST["user_name"]);
			  $response["data"]["created_time"] 	= cTYPE::inttodate(time());
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
