<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

					
	$query = "SELECT id FROM event_calendar_enroll 
							WHERE event_id = '" . $_REQUEST["event_id"] . "' AND
							      ( shelf = 0 OR shelf IS NULL )
							ORDER BY id ASC";
	
	$result = $db->query( $query );
	$cnt = 0;
	while($row = $db->fetch($result)) {
		$fields 	= array();
		$query_max 	= "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "'";
		$result_max = $db->query( $query_max );
		$row_max 	= $db->fetch( $result_max );
		$shelf 		= intval($row_max["max_shelf"]) + 1;
		$fields["shelf"] = $shelf;
		
		$db->update("event_calendar_enroll", $row["id"], $fields);
		$cnt++;	
	}
	$response["data"]["count"]  = $cnt;
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";

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
