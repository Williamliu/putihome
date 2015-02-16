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
	


	$criteria = "";

	$sch_name = trim($_REQUEST["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(cname like '%" . cTYPE::trans($sch_name) . "%' OR pname like '%" . cTYPE::trans($sch_name) . "%' OR dharma_name like '%" . cTYPE::trans($sch_name) . "%' OR en_name like '%" . cTYPE::trans($sch_name) . "%')";
	}

	$sch_phone = trim($_REQUEST["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%')";
	}

	$sch_email = trim($_REQUEST["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($_REQUEST["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_status = trim($_REQUEST["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "status = '" . $sch_status . "'";
	}

	$sch_city = trim($_REQUEST["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;

	
	$query0 = "SELECT b.id, b.cname, b.en_name, b.dharma_name, sum(work_hour) as total_hour, 
					count(a.id) as work_count, count( distinct a.department_id) as total_head  
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 1 = 1 AND b.deleted <> 1 AND 
						  b.site IN " . $admin_user["sites"]  . " 
					$ccc $criteria
					GROUP BY b.id, b.cname, b.en_name, b.dharma_name 
					ORDER BY total_hour DESC, b.en_name, b.dharma_name, b.cname";

	//echo $query0;
	$result0 = $db->query($query0);
	$cnt0=0;
	$departArr = array();
	while($row0 = $db->fetch($result0)) {
		$pid = $row0["id"];
		
		$departObj = array();
		$departObj["id"] 			= $row0["id"];
		$departObj["cname"] 		= $row0["cname"];
		$departObj["en_name"] 		= $row0["en_name"];
		$departObj["dharma_name"] 	= $row0["dharma_name"];
		
		$departObj["work_count"] 	= $row0["work_count"]?$row0["work_count"]:"";
		$departObj["total_hour"] 	= $row0["total_hour"]?$row0["total_hour"]:"";
		$departObj["total_head"] 	= $row0["total_head"]?$row0["total_head"]:"";


		$departObj["volunteer"] = array();
				
		if($level >= 2) {
				$query1 = "SELECT b.id, b.title, b.sn, sum(work_hour) as total_hour, count(a.id) as work_count  
								FROM puti_volunteer_hours a INNER JOIN puti_department b ON (a.department_id = b.id) 
								INNER JOIN puti_volunteer c ON (a.volunteer_id = c.id) 
								WHERE 	volunteer_id = '" . $pid . "'  AND 
										c.deleted <> 1 AND 
								   		c.site IN " . $admin_user["sites"]  . " 
								$ccc $criteria
								GROUP BY b.id, b.title, b.sn   
								ORDER BY b.sn DESC, b.title";
				
				$result1	= $db->query($query1);
				$cnt1 = 0;
				while($row1 = $db->fetch($result1)) {
					$volObj = array();
					$volObj["id"] 			= $row1["id"];
					$volObj["work_count"] 	= $row1["work_count"];
					$volObj["total_hour"] 	= $row1["total_hour"];
					$volObj["title"] 		= $row1["title"];
					$volObj["detail"] = array();
					if($level >= 3) {
						  $query2	= "SELECT a.id, purpose, work_date, work_hour, 
											CONCAT( IF( purpose='', '', CONCAT(purpose, '::') ) ,IFNULL(d.job_title, '" . $words["unknown"] . "') )as job_title 						  					
											FROM puti_volunteer_hours a 
											INNER JOIN puti_department b ON (a.department_id = b.id) 
											INNER JOIN puti_volunteer c ON (a.volunteer_id = c.id) 
									  		LEFT JOIN puti_department_job d ON (a.department_id = d.department_id AND a.job_id = d.job_id) 
											WHERE  	c.deleted <> 1 AND 
													a.department_id = '" . $row1["id"] . "' AND 
													a.volunteer_id = '" . $pid . "' AND  
												   	c.site IN " . $admin_user["sites"]  . " 
											 $ccc $criteria ORDER BY work_date";
												  
						  $result2	= $db->query($query2);
						  $cnt2 = 0;
						  while($row2 = $db->fetch($result2)) {
							  $detailObj = array();
							  $detailObj["id"]			= $row2["id"];
							  $detailObj["job_title"] 	= $row2["job_title"];
							  $detailObj["work_date"] 	= date("Y-m-d",$row2["work_date"]);
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

	/// summary 
	$query00 = "SELECT sum(work_hour) as total_hour, count(a.id) as work_count, count(distinct a.department_id) as total_head   
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 	1 = 1 AND 
							b.deleted <> 1 AND 
							b.site IN " . $admin_user["sites"]  . " $ccc $criteria";
	$result00 	= $db->query($query00);
	$row00 		= $db->fetch($result00);

	
	$response["data"]["work_count"] 	= $row00["work_count"]?$row00["work_count"]:"";
	$response["data"]["total_hour"] 	= $row00["total_hour"]?$row00["total_hour"]:"";
	$response["data"]["total_head"] 	= $row00["total_head"]?$row00["total_head"]:"";



	$response["data"]["type"] 			= $_REQUEST["type"];
	$response["data"]["list"] 			= $departArr;
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
