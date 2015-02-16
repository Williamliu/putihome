<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	if( $_REQUEST["status"]==1 ) {
		$db->query("UPDATE puti_department_volunteer SET status = '" . $_REQUEST["status"] . "', last_updated = '" . time() . "' WHERE department_id = '" . $_REQUEST["pid"] . "' AND volunteer_id = '" . $_REQUEST["vid"] . "'");
	} else {
		$db->query("UPDATE puti_department_volunteer SET status = '" . $_REQUEST["status"] . "', last_updated = '0' WHERE department_id = '" . $_REQUEST["pid"] . "' AND volunteer_id = '" . $_REQUEST["vid"] . "'");
	}
	
	$query1 = "SELECT a.department_id, a.volunteer_id, b.cname, b.pname, b.en_name, b.dharma_name, a.status 
					FROM puti_department_volunteer a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE b.deleted <> 1 AND b.status = 1 AND a.department_id = '" . $_REQUEST["pid"] . "'
					ORDER BY a.status DESC, a.last_updated, en_name, pname,  dharma_name, cname";
	$result1 = $db->query($query1);

	$cnt1=0;
	$dArr = array();
	while($row1 = $db->fetch($result1)) {
		$dObj = array();
		$dObj["department_id"] 	= $row1["department_id"];
		$dObj["volunteer_id"] 	= $row1["volunteer_id"];
		$dObj["cname"] 			= $row1["cname"];
		$dObj["pname"] 			= $row1["pname"];
		$dObj["en_name"] 		= $row1["en_name"];
		$dObj["dharma_name"] 	= $row1["dharma_name"];
		$dObj["status"] 		= $row1["status"];
		$dArr[$cnt1] = $dObj;
		$cnt1++;	
	}
	
	$response["data"]["pid"] 		= $_REQUEST["pid"];
	$response["data"]["vid"] 		= $_REQUEST["vid"];
	$response["data"]["status"] 	= $_REQUEST["status"];
	
	//$response["data"]["vols"] 		= $dArr;
	
	$response["errorMessage"]	= "<br>Hours has been saved to database.";
	$response["errorCode"] 	= 0;

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
