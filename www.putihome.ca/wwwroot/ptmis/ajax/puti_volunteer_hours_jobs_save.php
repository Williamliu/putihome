<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {
	$type["pid"]			= '{"type":"NUMBER","length":11, 	"id": "id", 			"name":"PID", 				"nullable":0}';
	$type["jid"]			= '{"type":"NUMBER","length":11, 	"id": "id", 			"name":"JID", 				"nullable":0}';
	$type["job_title"]		= '{"type":"CHAR", 	"length":255, 	"id": "job_title", 		"name":"Job Title", 		"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if( $_REQUEST["jid"] == "-1" ) {
		$res = $db->query("SELECT Max(job_id) as max_id FROM puti_department_job WHERE department_id = '" . $_REQUEST["pid"] . "'");
		$row = $db->fetch($res);
		$max_id = $row["max_id"]?$row["max_id"]:0;
		$max_id++;

		$fields = array();
		$fields["department_id"] 	= $_REQUEST["pid"];
		$fields["job_id"]		 	= $max_id;
		$fields["job_title"] 		= cTYPE::utrans($_REQUEST["job_title"]);
		$db->insert("puti_department_job", $fields);
	} else {
		$fields = array();
		$fields["job_title"] = cTYPE::utrans($_REQUEST["job_title"]);
		
		$ccc = array();
		$ccc["department_id"] = $_REQUEST["pid"];
		$ccc["job_id"]		  = $_REQUEST["jid"];
		$db->update("puti_department_job", $ccc, $fields);
	}

	$result0 = $db->query("SELECT title FROM puti_department WHERE id = '" . $_REQUEST["pid"] . "'");
	$row0 = $db->fetch($result0);

	$query1 = "SELECT a.department_id, a.volunteer_id, b.cname, b.pname, b.en_name, b.dharma_name, a.status 
					FROM puti_department_volunteer a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE b.deleted <> 1 AND b.status = 1 AND
							b.site IN ". $admin_user["sites"] ." AND
							a.department_id = '" . $_REQUEST["pid"] . "'
					ORDER BY a.status DESC, a.last_updated, dharma_name, cname, en_name, pname";
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
	$response["data"]["jid"] 		= $_REQUEST["jid"];
	$response["data"]["title"] 		= $row0["title"]==""?"Department":$row0["title"];
	$response["data"]["count"] 		= $cnt1;
	
	$response["data"]["vols"] 		= $dArr;


	
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
