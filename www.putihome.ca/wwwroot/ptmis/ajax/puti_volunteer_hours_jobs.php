<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$response["data"]["pid"] 		= $_REQUEST["pid"];
	$query2 = "SELECT department_id, job_id, job_title 
					FROM puti_department_job WHERE department_id = '" . $_REQUEST["pid"] . "'";
	$result2 = $db->query($query2);
    $jobArr = array();
	$cnt2 = 0;
	while( $row2 = $db->fetch($result2) ) {
		$jobArr[$cnt2]["department_id"] = $_REQUEST["pid"];
		$jobArr[$cnt2]["job_id"] 		= $row2["job_id"];
		$jobArr[$cnt2]["job_title"] 	= $row2["job_title"];
		$cnt2++;
	}
	$response["data"]["pid"] 		= $_REQUEST["pid"];
	$response["data"]["jobs"] 		= $jobArr;
	
	//$response["errorMessage"]	= "<br>Hours has been saved to database.";
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
