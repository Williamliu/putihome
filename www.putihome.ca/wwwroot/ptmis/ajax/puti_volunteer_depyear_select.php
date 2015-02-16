<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();
	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "work_date >= '" . $sd . "' AND work_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "work_date >= '" . $sd . "'";
	} elseif($ed != "") {
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
		$query1 	= "SELECT  
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, volunteer_id, null)) as cm1,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, volunteer_id, null)) as cm2,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, volunteer_id, null)) as cm3,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, volunteer_id, null)) as cm4,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, volunteer_id, null)) as cm5,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, volunteer_id, null)) as cm6,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, volunteer_id, null)) as cm7,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, volunteer_id, null)) as cm8,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, volunteer_id, null)) as cm9,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, volunteer_id, null)) as cm10,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, volunteer_id, null)) as cm11,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, volunteer_id, null)) as cm12,
						
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, volunteer_id, null)) as ch1,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, volunteer_id, null)) as ch2,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, volunteer_id, null)) as ch3,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, volunteer_id, null)) as ch4,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, volunteer_id, null)) as ch5,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, volunteer_id, null)) as ch6,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, volunteer_id, null)) as ch7,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, volunteer_id, null)) as ch8,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, volunteer_id, null)) as ch9,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, volunteer_id, null)) as ch10,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, volunteer_id, null)) as ch11,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, volunteer_id, null)) as ch12,

						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, work_hour, 0)) as hm1,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, work_hour, 0)) as hm2,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, work_hour, 0)) as hm3,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, work_hour, 0)) as hm4,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, work_hour, 0)) as hm5,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, work_hour, 0)) as hm6,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, work_hour, 0)) as hm7,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, work_hour, 0)) as hm8,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, work_hour, 0)) as hm9,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, work_hour, 0)) as hm10,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, work_hour, 0)) as hm11,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, work_hour, 0)) as hm12,
						COUNT(a.id) as tcnt, 
						COUNT(distinct volunteer_id) as thead,  
						SUM(work_hour) as thour			
						FROM puti_volunteer_hours  a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)   
						WHERE department_id = '" . $pid . "'
							   AND b.site IN " . $admin_user["sites"]  . "						
						 $ccc ";
		
		
		
		/*
		$query1 	= "SELECT  
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, 1, 0)) as cm1,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, 1, 0)) as cm2,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, 1, 0)) as cm3,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, 1, 0)) as cm4,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, 1, 0)) as cm5,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, 1, 0)) as cm6,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, 1, 0)) as cm7,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, 1, 0)) as cm8,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, 1, 0)) as cm9,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, 1, 0)) as cm10,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, 1, 0)) as cm11,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, 1, 0)) as cm12,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, work_hour, 0)) as hm1,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, work_hour, 0)) as hm2,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, work_hour, 0)) as hm3,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, work_hour, 0)) as hm4,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, work_hour, 0)) as hm5,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, work_hour, 0)) as hm6,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, work_hour, 0)) as hm7,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, work_hour, 0)) as hm8,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, work_hour, 0)) as hm9,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, work_hour, 0)) as hm10,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, work_hour, 0)) as hm11,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, work_hour, 0)) as hm12,
						COUNT(id) as tcnt, 
						COUNT(distinct volunteer_id) as thead,  
						SUM(work_hour) as thour			
						FROM puti_volunteer_hours  
						WHERE department_id = '" . $pid . "' $ccc ";
		*/
		$result1 	= $db->query($query1);
		$row1 		= $db->fetch($result1);
		
		$departObj = array();
		$departObj["id"] 	= $row0["id"];
		$departObj["title"] = $row0["title"];

		$departObj["cm1"] 	= $row1["cm1"]>0?$row1["cm1"]:"";
		$departObj["cm2"] 	= $row1["cm2"]>0?$row1["cm2"]:"";
		$departObj["cm3"] 	= $row1["cm3"]>0?$row1["cm3"]:"";
		$departObj["cm4"] 	= $row1["cm4"]>0?$row1["cm4"]:"";
		$departObj["cm5"] 	= $row1["cm5"]>0?$row1["cm5"]:"";
		$departObj["cm6"] 	= $row1["cm6"]>0?$row1["cm6"]:"";
		$departObj["cm7"] 	= $row1["cm7"]>0?$row1["cm7"]:"";
		$departObj["cm8"] 	= $row1["cm8"]>0?$row1["cm8"]:"";
		$departObj["cm9"] 	= $row1["cm9"]>0?$row1["cm9"]:"";
		$departObj["cm10"] 	= $row1["cm10"]>0?$row1["cm10"]:"";
		$departObj["cm11"] 	= $row1["cm11"]>0?$row1["cm11"]:"";
		$departObj["cm12"] 	= $row1["cm12"]>0?$row1["cm12"]:"";

		$departObj["ch1"] 	= $row1["ch1"]>0?$row1["ch1"]:"";
		$departObj["ch2"] 	= $row1["ch2"]>0?$row1["ch2"]:"";
		$departObj["ch3"] 	= $row1["ch3"]>0?$row1["ch3"]:"";
		$departObj["ch4"] 	= $row1["ch4"]>0?$row1["ch4"]:"";
		$departObj["ch5"] 	= $row1["ch5"]>0?$row1["ch5"]:"";
		$departObj["ch6"] 	= $row1["ch6"]>0?$row1["ch6"]:"";
		$departObj["ch7"] 	= $row1["ch7"]>0?$row1["ch7"]:"";
		$departObj["ch8"] 	= $row1["ch8"]>0?$row1["ch8"]:"";
		$departObj["ch9"] 	= $row1["ch9"]>0?$row1["ch9"]:"";
		$departObj["ch10"] 	= $row1["ch10"]>0?$row1["ch10"]:"";
		$departObj["ch11"] 	= $row1["ch11"]>0?$row1["ch11"]:"";
		$departObj["ch12"] 	= $row1["ch12"]>0?$row1["ch12"]:"";

		$departObj["hm1"] 	= $row1["hm1"]>0?$row1["hm1"]:"";
		$departObj["hm2"] 	= $row1["hm2"]>0?$row1["hm2"]:"";
		$departObj["hm3"] 	= $row1["hm3"]>0?$row1["hm3"]:"";
		$departObj["hm4"] 	= $row1["hm4"]>0?$row1["hm4"]:"";
		$departObj["hm5"] 	= $row1["hm5"]>0?$row1["hm5"]:"";
		$departObj["hm6"] 	= $row1["hm6"]>0?$row1["hm6"]:"";
		$departObj["hm7"] 	= $row1["hm7"]>0?$row1["hm7"]:"";
		$departObj["hm8"] 	= $row1["hm8"]>0?$row1["hm8"]:"";
		$departObj["hm9"] 	= $row1["hm9"]>0?$row1["hm9"]:"";
		$departObj["hm10"] 	= $row1["hm10"]>0?$row1["hm10"]:"";
		$departObj["hm11"] 	= $row1["hm11"]>0?$row1["hm11"]:"";
		$departObj["hm12"] 	= $row1["hm12"]>0?$row1["hm12"]:"";

		$departObj["thour"] 	= $row1["thour"]>0?$row1["thour"]:"";
		$departObj["tcnt"] 		= $row1["tcnt"]>0?$row1["tcnt"]:"";
		$departObj["thead"] 	= $row1["thead"]>0?$row1["thead"]:"";

		// jobs
		if( $_REQUEST["include_job"] == 1 ) {		
			  $query111 	= "SELECT  
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, volunteer_id, null)) as cm1,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, volunteer_id, null)) as cm2,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, volunteer_id, null)) as cm3,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, volunteer_id, null)) as cm4,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, volunteer_id, null)) as cm5,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, volunteer_id, null)) as cm6,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, volunteer_id, null)) as cm7,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, volunteer_id, null)) as cm8,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, volunteer_id, null)) as cm9,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, volunteer_id, null)) as cm10,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, volunteer_id, null)) as cm11,
							  COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, volunteer_id, null)) as cm12,
							  
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, volunteer_id, null)) as ch1,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, volunteer_id, null)) as ch2,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, volunteer_id, null)) as ch3,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, volunteer_id, null)) as ch4,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, volunteer_id, null)) as ch5,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, volunteer_id, null)) as ch6,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, volunteer_id, null)) as ch7,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, volunteer_id, null)) as ch8,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, volunteer_id, null)) as ch9,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, volunteer_id, null)) as ch10,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, volunteer_id, null)) as ch11,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, volunteer_id, null)) as ch12,
	  
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, work_hour, 0)) as hm1,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, work_hour, 0)) as hm2,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, work_hour, 0)) as hm3,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, work_hour, 0)) as hm4,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, work_hour, 0)) as hm5,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, work_hour, 0)) as hm6,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, work_hour, 0)) as hm7,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, work_hour, 0)) as hm8,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, work_hour, 0)) as hm9,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, work_hour, 0)) as hm10,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, work_hour, 0)) as hm11,
							  SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, work_hour, 0)) as hm12,
							  a.job_id,
							  ifnull(c.job_title, '" . $words["unknown"] . "') as job_title, 
							  COUNT(a.id) as tcnt, 
							  COUNT(distinct volunteer_id) as thead,  
							  SUM(work_hour) as thour			
							  FROM puti_volunteer_hours  a 
							  INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)   
							  LEFT JOIN puti_department_job c ON (a.job_id = c.job_id AND a.department_id = c.department_id)   
							  WHERE  a.department_id = '" . $pid . "'
									 AND b.site IN " . $admin_user["sites"]  . "						
							   $ccc 
							  GROUP BY a.job_id";
		
				
			  $result111 	= $db->query($query111);
			  $row111 	= $db->rows($result111);
			  $jlists = array();
			  foreach($row111 as $val) {
			  	$jlists[] = $val["job_id"];
			  }
			
			  $res_jlist = $db->query("SELECT * FROM puti_department_job WHERE department_id = '" . $pid . "'");			
			  while( $row_jlist = $db->fetch($res_jlist) ) {
			  	if( !in_array( $row_jlist["job_id"], $jlists) ) {
					$ridx = count($row111);
					$row111[$ridx]["job_id"]	=  $row_jlist["job_id"];
					$row111[$ridx]["job_title"] =  $row_jlist["job_title"];
				}
			  }
			  $departObj["jobs"] 	= $row111;
		}
		// end of job

		$departArr[$cnt0] = $departObj;
		$cnt0++;
		
	} // loop for department




	$query2 	= "SELECT 
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, volunteer_id, null)) as cm1,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, volunteer_id, null)) as cm2,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, volunteer_id, null)) as cm3,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, volunteer_id, null)) as cm4,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, volunteer_id, null)) as cm5,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, volunteer_id, null)) as cm6,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, volunteer_id, null)) as cm7,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, volunteer_id, null)) as cm8,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, volunteer_id, null)) as cm9,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, volunteer_id, null)) as cm10,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, volunteer_id, null)) as cm11,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, volunteer_id, null)) as cm12,
						
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, volunteer_id, null)) as ch1,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, volunteer_id, null)) as ch2,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, volunteer_id, null)) as ch3,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, volunteer_id, null)) as ch4,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, volunteer_id, null)) as ch5,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, volunteer_id, null)) as ch6,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, volunteer_id, null)) as ch7,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, volunteer_id, null)) as ch8,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, volunteer_id, null)) as ch9,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, volunteer_id, null)) as ch10,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, volunteer_id, null)) as ch11,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, volunteer_id, null)) as ch12,

						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, work_hour, 0)) as hm1,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, work_hour, 0)) as hm2,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, work_hour, 0)) as hm3,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, work_hour, 0)) as hm4,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, work_hour, 0)) as hm5,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, work_hour, 0)) as hm6,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, work_hour, 0)) as hm7,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, work_hour, 0)) as hm8,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, work_hour, 0)) as hm9,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, work_hour, 0)) as hm10,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, work_hour, 0)) as hm11,
						SUM(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, work_hour, 0)) as hm12,
					
						COUNT(a.id) as tcnt,
						COUNT(distinct volunteer_id) as thead, 
						SUM(work_hour) as thour			
					FROM puti_volunteer_hours  a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE department_id in $pcon
					   AND b.site IN " . $admin_user["sites"]  . "						
					 $ccc ";
	$result2 	= $db->query($query2);
	$row2 		= $db->fetch($result2);

	$grand = array();
	$grand["cm1"] 	= $row2["cm1"]>0?$row2["cm1"]:"";
	$grand["cm2"] 	= $row2["cm2"]>0?$row2["cm2"]:"";
	$grand["cm3"] 	= $row2["cm3"]>0?$row2["cm3"]:"";
	$grand["cm4"] 	= $row2["cm4"]>0?$row2["cm4"]:"";
	$grand["cm5"] 	= $row2["cm5"]>0?$row2["cm5"]:"";
	$grand["cm6"] 	= $row2["cm6"]>0?$row2["cm6"]:"";
	$grand["cm7"] 	= $row2["cm7"]>0?$row2["cm7"]:"";
	$grand["cm8"] 	= $row2["cm8"]>0?$row2["cm8"]:"";
	$grand["cm9"] 	= $row2["cm9"]>0?$row2["cm9"]:"";
	$grand["cm10"] 	= $row2["cm10"]>0?$row2["cm10"]:"";
	$grand["cm11"] 	= $row2["cm11"]>0?$row2["cm11"]:"";
	$grand["cm12"] 	= $row2["cm12"]>0?$row2["cm12"]:"";

	$grand["ch1"] 	= $row2["ch1"]>0?$row2["ch1"]:"";
	$grand["ch2"] 	= $row2["ch2"]>0?$row2["ch2"]:"";
	$grand["ch3"] 	= $row2["ch3"]>0?$row2["ch3"]:"";
	$grand["ch4"] 	= $row2["ch4"]>0?$row2["ch4"]:"";
	$grand["ch5"] 	= $row2["ch5"]>0?$row2["ch5"]:"";
	$grand["ch6"] 	= $row2["ch6"]>0?$row2["ch6"]:"";
	$grand["ch7"] 	= $row2["ch7"]>0?$row2["ch7"]:"";
	$grand["ch8"] 	= $row2["ch8"]>0?$row2["ch8"]:"";
	$grand["ch9"] 	= $row2["ch9"]>0?$row2["ch9"]:"";
	$grand["ch10"] 	= $row2["ch10"]>0?$row2["ch10"]:"";
	$grand["ch11"] 	= $row2["ch11"]>0?$row2["ch11"]:"";
	$grand["ch12"] 	= $row2["ch12"]>0?$row2["ch12"]:"";

	$grand["hm1"] 	= $row2["hm1"]>0?$row2["hm1"]:"";
	$grand["hm2"] 	= $row2["hm2"]>0?$row2["hm2"]:"";
	$grand["hm3"] 	= $row2["hm3"]>0?$row2["hm3"]:"";
	$grand["hm4"] 	= $row2["hm4"]>0?$row2["hm4"]:"";
	$grand["hm5"] 	= $row2["hm5"]>0?$row2["hm5"]:"";
	$grand["hm6"] 	= $row2["hm6"]>0?$row2["hm6"]:"";
	$grand["hm7"] 	= $row2["hm7"]>0?$row2["hm7"]:"";
	$grand["hm8"] 	= $row2["hm8"]>0?$row2["hm8"]:"";
	$grand["hm9"] 	= $row2["hm9"]>0?$row2["hm9"]:"";
	$grand["hm10"] 	= $row2["hm10"]>0?$row2["hm10"]:"";
	$grand["hm11"] 	= $row2["hm11"]>0?$row2["hm11"]:"";
	$grand["hm12"] 	= $row2["hm12"]>0?$row2["hm12"]:"";
		
	$grand["tcnt"] 	= $row2["tcnt"]>0?$row2["tcnt"]:"";
	$grand["thour"] = $row2["thour"]>0?$row2["thour"]:"";
	$grand["thead"] = $row2["thead"]>0?$row2["thead"]:"";

	$response["data"]["type"] 			= $_REQUEST["type"];
	$response["data"]["list"] 			= $departArr;
	$response["data"]["grand"]			= $grand;
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
