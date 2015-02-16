<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
//include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"]	= '{"type":"NUMBER", "length":11, "id": "event_id",	"name":"Event",	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT b.id, b.title, b.description FROM event_calendar a INNER JOIN (SELECT * FROM puti_agreement_lang WHERE lang='" . $_REQUEST["lang"] . "')b on ( a.agreement = b.agreement_id ) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
	if( $db->exists($query) ) {
		$result = $db->query($query);
		$row = $db->fetch($result);
		$response["data"]["found"] 	= true;
		$response["data"]["title"] 	= $row["title"];
		$response["data"]["desc"] 	= $row["description"];
		
	} else {
		$response["data"]["found"] 	= false;
	}
	$response["errorMessage"]	= "";
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
