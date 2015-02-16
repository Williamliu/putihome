<?php 
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {

	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["alias"] 				= '{"type":"CHAR", 		"length":255, 	"id": "alias", 			"name":"Alias", 		"nullable":1}';
	$type["gender"]				= '{"type":"CHAR", 		"length":11, 	"id": "gender", 		"name":"Gender", 	 	"nullable":0}';
	$type["age"]				= '{"type":"NUMBER", 	"length":11, 	"id": "age_range", 		"name":"Age Range", 	 "nullable":0}';

	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":0}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$tstamp 	= time();
	$inputtime 	= date("Y-m-d H:i:s", $tstamp);
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$query = "SELECT id FROM puti_members WHERE deleted <> 1 AND email = '" . $_REQUEST["email"] . "'";
	$result = $db->query( $query );
	if( $db->row_nums($result) > 0 )  {
		  $row = $db->fetch($result);
		  $member_id = $row["id"];	

		  $fields = array();
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  //$fields["created_time"]		= time();
		  $fields["last_updated"] 		= time();
		  $fields["last_login"] 		= 0;
	  
		  $fields["first_name"] 		= cTYPE::ufirst($_REQUEST["first_name"]);
		  $fields["last_name"] 			= cTYPE::ufirst($_REQUEST["last_name"]);
		  $fields["dharma_name"] 		= $_REQUEST["dharma_name"];
		  $fields["alias"] 				= $_REQUEST["alias"];
		  $fields["gender"] 			= $_REQUEST["gender"];
		  $fields["age"] 				= $_REQUEST["age"];
		  
		  $fields["email"] 				= $_REQUEST["email"];
		  $fields["phone"] 				= cTYPE::phone($_REQUEST["phone"]);
		  $fields["cell"] 				= cTYPE::phone($_REQUEST["cell"]);
		  
		  $fields["city"] 				= cTYPE::ufirst($_REQUEST["city"]);
	  
		  $result = $db->update("puti_members", $member_id, $fields);
		  
		  $response["errorMessage"]	= "<br>Email '" . $_REQUEST["email"] . "' already exists in our database.<br><br>Your submit has been updated to existing account successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
	} else {
		  $fields = array();
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  $fields["created_time"]		= time();
		  $fields["last_updated"] 		= 0;
		  $fields["last_login"] 		= 0;
	  
		  $fields["first_name"] 		= cTYPE::ufirst($_REQUEST["first_name"]);
		  $fields["last_name"] 			= cTYPE::ufirst($_REQUEST["last_name"]);
		  $fields["dharma_name"] 		= $_REQUEST["dharma_name"];
		  $fields["gender"] 			= $_REQUEST["gender"];
		  $fields["age"] 				= $_REQUEST["age"];
		  
		  $fields["email"] 			= $_REQUEST["email"];
		  $fields["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
		  $fields["cell"] 			= cTYPE::phone($_REQUEST["cell"]);
		  $fields["city"] 			= cTYPE::ufirst($_REQUEST["city"]);
	  
		  $member_id = $result = $db->insert("puti_members", $fields);
	  
		  $response["errorMessage"]	= "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
	}
	$response["errorCode"] 		= 0;

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
