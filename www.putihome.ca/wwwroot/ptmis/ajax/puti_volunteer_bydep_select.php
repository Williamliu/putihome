<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
    $level = $_REQUEST["level"];
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();
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
	
	$pcon = '(-1)';
	if($_REQUEST["depart"] != "") {
		$pcon = '(' . $_REQUEST["depart"] . ')';
	}
	
	$query0 = "SELECT id, title FROM puti_department 
					WHERE deleted <> 1 AND id in $pcon 
					ORDER BY sn DESC";
	$result0 = $db->query($query0);
	$cnt0=0;
	$departArr = array();
	while($row0 = $db->fetch($result0)) {
		$pid = $row0["id"];
		$query11 	= "SELECT count(a.id) as work_count, sum(a.work_hour) as total_hour, count(distinct a.volunteer_id) as total_head 
						FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
						WHERE  a.department_id = '" . $pid . "' AND b.deleted <> 1 AND 
							   b.site IN " . $admin_user["sites"]  . " 
						      $ccc ";
		$result11 	= $db->query($query11);
		$row11 		= $db->fetch($result11);

		$departObj = array();
		$departObj["id"] 			= $row0["id"];
		$departObj["title"] 		= $row0["title"];
		$departObj["work_count"] 	= $row11["work_count"]?$row11["work_count"]:"";
		$departObj["total_hour"] 	= $row11["total_hour"]?$row11["total_hour"]:"";
		$departObj["total_head"] 	= $row11["total_head"]?$row11["total_head"]:"";


		$departObj["volunteer"] = array();
		
				
		if($level >= 2) {
				$query1 	= "SELECT volunteer_id as vid, b.en_name, b.pname, b.dharma_name, b.cname, count(a.id) as work_count, sum(work_hour) as total_hour 
								FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
								WHERE 	department_id = '" . $pid . "' AND 
										b.deleted <> 1 AND 
										b.site IN " . $admin_user["sites"] . " $ccc GROUP BY volunteer_id, b.en_name, b.pname, b.dharma_name, b.cname ORDER BY total_hour DESC, b.en_name, b.pname, b.dharma_name, b.cname";
				
				$result1	= $db->query($query1);
				$cnt1 = 0;
				while($row1 = $db->fetch($result1)) {
					$volObj = array();
					$volObj["id"] 			= $row1["vid"];
					$volObj["work_count"] 	= $row1["work_count"];
					$volObj["total_hour"] 	= $row1["total_hour"];
					$volObj["dharma_name"] 	= $row1["dharma_name"];
					$volObj["cname"] 		= $row1["cname"];
					$volObj["pname"] 		= $row1["pname"];
					$volObj["en_name"] 		= $row1["en_name"];
					$volObj["detail"] = array();
					if($level >= 3) {
						  $query2	= "SELECT a.id, purpose, work_date, work_hour,  CONCAT( IF( purpose='', '', CONCAT(purpose, '::') ) ,IFNULL(c.job_title, '" . $words["unknown"] . "') )as job_title 
									  FROM puti_volunteer_hours  a 
									  INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)  
									  LEFT JOIN puti_department_job c ON (a.department_id = c.department_id AND a.job_id = c.job_id) 
									  WHERE b.deleted<> 1 AND 
									  		a.department_id = '" . $pid . "' AND 
											a.volunteer_id = '" . $row1["vid"] . "' AND 
											b.site IN " . $admin_user["sites"] . "
									  $ccc ORDER BY work_date";
												  
						  $result2	= $db->query($query2);
						  $cnt2 = 0;
						  while($row2 = $db->fetch($result2)) {
							  $detailObj = array();
							  $detailObj["id"]			= $row2["id"];
							  $detailObj["job_title"] 	= $row2["job_title"];
							  $detailObj["work_date"]	= date("Y-m-d",$row2["work_date"]);
							  $detailObj["work_hour"] 	= $row2["work_hour"];
							  $volObj["detail"][$cnt2] 	= $detailObj;
							  $cnt2++;
						  }  // loop for detail
					}
					$departObj["volunteer"][$cnt1] = $volObj;	
					$cnt1++;
		
				} // loop for person
		} // level > 2
		
		$departArr[$cnt0] = $departObj;
		$cnt0++;
		
	} // loop for department
	$response["data"]["list"] 			= $departArr;

	// summary 
	$query22 	= "SELECT count(a.id) as work_count, sum(work_hour) as total_hour, count(distinct volunteer_id) as total_head 
					FROM puti_volunteer_hours  a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 	b.deleted <> 1 AND 
							department_id in $pcon AND 
							b.site IN " . $admin_user["sites"] . " $ccc ";
	$result22 	= $db->query($query22);
	$row22 		= $db->fetch($result22);

	$response["data"]["work_count"] = $row22["work_count"]?$row22["work_count"]:"";
	$response["data"]["total_hour"] = $row22["total_hour"]?$row22["total_hour"]:"";
	$response["data"]["total_head"] = $row22["total_head"]?$row22["total_head"]:"";


	$response["data"]["period"] = ($_REQUEST["start_date"]>0?$_REQUEST["start_date"]:"long long ago") . " ~ " . ($_REQUEST["end_date"]>0?$_REQUEST["end_date"]:"Today");
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
