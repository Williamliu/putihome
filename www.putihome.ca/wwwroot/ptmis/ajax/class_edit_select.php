<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["id"] = '{"type":"NUMBER", 	"length":11, 	"id": "class_id", 		"name":"Class ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM puti_class WHERE deleted <> 1 AND id = '" . $_REQUEST["id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$cls = array();
	$cls["id"] 				= $row["id"];
	$cls["class_id"] 		= $row["id"];
	$cls["title"] 			= cTYPE::gstr($row["title"]);
	$cls["description"] 	= cTYPE::gstr($row["description"]);
	$cls["agreement"] 		= $row["agreement"];
	$cls["sn"] 		        = $row["sn"];
	$cls["status"] 			= $row["status"];
	$cls["checkin"] 		= $row["checkin"];
	$cls["attend"] 			= $row["attend"];
	$cls["cert"] 			= $row["cert"];
	$cls["cert_prefix"] 	= $row["cert_prefix"];
	$cls["photo"] 			= $row["photo"];
	$cls["payfree"] 		= $row["payfree"];
	$cls["payonce"] 		= $row["payonce"];
	$cls["logform"] 		= $row["logform"];

	$cls["checkarr"] 		= array();
	$query2 	= "SELECT * FROM puti_class_checkin WHERE class_id = '" . $_REQUEST["id"] . "' ORDER BY class_id, sn";
	$result2 	= $db->query($query2);
	$cnt = 0;
	while( $row2 = $db->fetch($result2) ) {
		$carr = array();
		$carr["id"]				= $row2["id"];
		$carr["class_id"]		= $row2["class_id"];
		$carr["sn"]				= $row2["sn"];
		$carr["fhh"]			= $row2["from_hh"];
		$carr["fmm"]			= $row2["from_mm"];
		$carr["thh"]			= $row2["to_hh"];
		$carr["tmm"]			= $row2["to_mm"];
		$cls["checkarr"][$cnt] = $carr;
		$cnt++;
	}
	
	$cls["dates"] 			= array();
	
	$query1 	= "SELECT * FROM puti_class_date WHERE class_id = '" . $_REQUEST["id"] . "' ORDER BY day_no";
	$result1 	= $db->query($query1);
	$cnt = 0;
	while( $row1 = $db->fetch($result1) ) {
		$date = array();
		$date["id"]			= $row1["id"];
		$date["class_id"]	= $row1["class_id"];
		$date["day_no"]		= $row1["day_no"];
		$date["start_time"]	= $row1["start_time"];
		$date["end_time"]	= $row1["end_time"];
		$date["title"]		= cTYPE::gstr($row1["title"]);
		$date["description"]= cTYPE::gstr($row1["description"]);
		$date["checkin"]	= $row1["checkin"];
		$date["meal"]		= $row1["meal"];
		$cls["dates"][$cnt] = $date;
		$cnt++;
	}
	$response["data"] 		= $cls;


	
	$response["data"]["class_id"]	= $_REQUEST["id"];
	$response["errorCode"] 			= 0;
	$response["errorMessage"]		= "";
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
