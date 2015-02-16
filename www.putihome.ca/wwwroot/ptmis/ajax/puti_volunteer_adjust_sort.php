<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {

	$type["pid"] 				= '{"type":"NUMBER", 	"length":11, 	"id": "department_id", 	"name":"Department", 	"nullable":0}';
	//$type["start_date"] 		= '{"type":"DATE", 		"length":15, 	"id": "work_date", 		"name":"Work Date", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();


	$_REQUEST["orderBY"] = $_REQUEST["orderBY"]==""?"dharma_name":$_REQUEST["orderBY"];
	$_REQUEST["orderSQ"] = $_REQUEST["orderSQ"]==""?"ASC":$_REQUEST["orderSQ"];
	
	if( $_REQUEST["orderBY"]=="work_date" ) {
		$order_str = "ORDER BY " . $_REQUEST["orderBY"] . " " . $_REQUEST["orderSQ"] . ", dharma_name ASC";
	} else {
		$order_str = "ORDER BY " . $_REQUEST["orderBY"] . " " . $_REQUEST["orderSQ"] . ", work_date ASC";
	}
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$pid 		= $_REQUEST["pid"];


	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ed = mktime("23","59","59", date("n", $ed), date("j", $ed), date("Y", $ed) );
		$ccc = "work_date >= '" . $sd . "' AND work_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "work_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ed = mktime("23","59","59", date("n", $ed), date("j", $ed), date("Y", $ed) );
		$ccc = "work_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;

	$result00 = $db->query("SELECT title,en_title FROM puti_department WHERE id = '" . $pid . "'");
	$row00 = $db->fetch($result00);

	$query11 	= "SELECT count(a.id) as work_count, sum(a.work_hour) as total_hour  
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)
					WHERE   a.department_id = '" . $pid . "' AND
							a.site IN " . $admin_user["sites"] . " $ccc";
							
							// later:
							// a.site = '" . $admin_user["site"] . "'
	//echo "query11: " . $query11;
	$result11 	= $db->query($query11);
	$row11 		= $db->fetch($result11);

	$response["data"]["pid"] 		= $_REQUEST["pid"];
	$response["data"]["work_date"] 	= $_REQUEST["work_date"];
	$response["data"]["title"] 		= $admin_user["lang"]!="en"?($row00["title"]==""?"Department":$row00["title"]):($row00["en_title"]==""?"Department":$row00["en_title"]);
	$response["data"]["work_count"] = $row11["work_count"]>0?$row11["work_count"]:'';
	$response["data"]["total_hour"] = $row11["total_hour"]>0?$row11["total_hour"]:'';



	$query1 = "SELECT a.id as hid, a.department_id, a.volunteer_id, a.job_id, a.purpose, a.work_date, a.work_hour, b.cname, b.pname, b.en_name, b.dharma_name  
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE a.department_id = '" . $pid . "' AND 
						  a.site = '" . $admin_user["site"] . "' $ccc  
					$order_str";
	$result1 = $db->query($query1);

	$cnt1=0;
	$dArr = array();
	while($row1 = $db->fetch($result1)) {
		$dObj = array();
		$dObj["hid"] 			= $row1["hid"];
		$dObj["department_id"] 	= $row1["department_id"];
		$dObj["volunteer_id"] 	= $row1["volunteer_id"];
		$dObj["job_id"] 		= $row1["job_id"];
		$dObj["cname"] 			= $row1["cname"];
		$dObj["pname"] 			= $row1["pname"];
		$dObj["en_name"] 		= $row1["en_name"];
		$dObj["dharma_name"] 	= $row1["dharma_name"];
		$dObj["purpose"] 		= $row1["purpose"];
		$dObj["work_date"] 		= $row1["work_date"]>0?date("Y-m-d",$row1["work_date"]):'';
		$dObj["work_hour"] 		= $row1["work_hour"]>0?$row1["work_hour"]:'';
		$dArr[$cnt1] = $dObj;
		$cnt1++;	
	}
	$response["data"]["orderBY"] 	= $_REQUEST["orderBY"];
	$response["data"]["orderSQ"] 	= $_REQUEST["orderSQ"];
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
