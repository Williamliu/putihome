<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["hid"] = '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM puti_volunteer WHERE deleted <> 1 AND id = '" . $_REQUEST["hid"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["id"] 			= $row["id"];
	$response["data"]["hid"] 			= $row["id"];
	$response["data"]["dharma_name"] 	= $row["dharma_name"];
	$response["data"]["cname"] 			= $row["cname"];
	$response["data"]["pname"] 			= $row["pname"];
	$response["data"]["en_name"] 		= $row["en_name"];
	$response["data"]["gender"] 		= $row["gender"];
	$response["data"]["email"] 			= $row["email"];
	$response["data"]["phone"] 			= $row["phone"];
	$response["data"]["cell"] 			= $row["cell"];
	$response["data"]["status"] 		= $row["status"];
	$response["data"]["city"] 			= $row["city"];
	$response["data"]["created_time"] 	= cTYPE::inttodate($row["created_time"]);

	$query = "SELECT * FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "'";
	$result = $db->query( $query );
	$depart = "";
	while( $row = $db->fetch($result) ) {
		$depart .= ($depart==""?"":",") .  $row["department_id"];
	}
	$response["data"]["depart"] = $depart;

	$query = "SELECT sum(work_hour) as total_hour, count(id) as work_count FROM puti_volunteer_hours WHERE volunteer_id = '" . $_REQUEST["hid"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	$response["data"]["total_hour"] = $row["total_hour"]>0?$row["total_hour"]:0;
	$response["data"]["work_count"] = $row["work_count"]>0?$row["work_count"]:0;

	$query = "SELECT a.id, b.title, a.purpose, a.work_date, a.work_hour 
					FROM puti_volunteer_hours a INNER JOIN puti_department b ON (a.department_id = b.id)
				    WHERE volunteer_id = '" . $_REQUEST["hid"] . "'
					ORDER BY  a.work_date DESC LIMIT 0, 30";
	$result = $db->query( $query );
	$record = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
		$rObj = array();
		$rObj["id"] 		= $row["id"];
		$rObj["title"] 		= $row["title"];
		$rObj["purpose"] 	= $row["purpose"];
		$rObj["work_date"] 	= $row["work_date"]>0?date("Y-m-d",$row["work_date"]):'';
		$rObj["work_hour"] 	= $row["work_hour"];
		$record[$cnt] = $rObj;
		$cnt++;
	}
	$response["data"]["record"] = $record;


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
