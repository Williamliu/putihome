<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	//$db->query("UPDATE puti_department_volunteer SET status = '" . $_REQUEST["status"] . "' WHERE department_id = '" . $_REQUEST["pid"] . "' AND volunteer_id = '" . $_REQUEST["vid"] . "'");
	$result0 = $db->query("SELECT title,en_title FROM puti_department WHERE id = '" . $_REQUEST["pid"] . "'");
	$row0 = $db->fetch($result0);

	// condition here 
	$criteria = "";
	$sch_name = trim($_REQUEST["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . 
						"( 	cname like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							pname like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							en_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							dharma_name like '%" . 	cTYPE::trans_trim($sch_name) . "%'
						)";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of condition
	
	
	$query1 = "SELECT a.department_id, a.volunteer_id, b.cname, b.pname, b.en_name, b.dharma_name, a.status 
					FROM puti_department_volunteer a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE b.deleted <> 1 AND b.status = 1 AND
							b.site IN ". $admin_user["sites"] ." AND
							a.department_id = '" . $_REQUEST["pid"] . "' $criteria 
					ORDER BY a.status DESC, a.last_updated, dharma_name, cname, en_name, pname";

					// later 
					//a.site = '". $admin_user["site"] ."' AND


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
	$response["data"]["title"] 		= $admin_user["lang"]!="en"?($row0["title"]==""?"Department":$row0["title"]):($row0["en_title"]==""?"Department":$row0["en_title"]);
	$response["data"]["count"] 		= $cnt1;
	
	$response["data"]["vols"] 		= $dArr;



	$query2 = "SELECT department_id, job_id, job_title 
					FROM puti_department_job WHERE department_id = '" . $_REQUEST["pid"] . "'";
	$result2 = $db->query($query2);
    $jobArr = array();
	$cnt2 = 0;
	while( $row2 = $db->fetch($result2) ) {
		$jobArr[$cnt2]["job_id"] 	= $row2["job_id"];
		$jobArr[$cnt2]["job_title"] = $row2["job_title"];
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
