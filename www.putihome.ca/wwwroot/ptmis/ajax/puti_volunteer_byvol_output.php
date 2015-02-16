<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=volunteer_hours_report_byvol.xls");

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
						count(a.id) as work_count, count(distinct a.department_id) as total_head  
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 	1 = 1 AND 
							b.deleted <> 1 AND 
   					 	  	b.site IN " . $admin_user["sites"]  . " 
					$ccc $criteria
					GROUP BY b.id, b.cname, b.en_name, b.dharma_name 
					ORDER BY total_hour DESC, b.en_name, b.dharma_name, b.cname";

	//echo $query0;
	$result0 = $db->query($query0);
	$cnt0=0;

	$period = ($_REQUEST["start_date"]>0?$_REQUEST["start_date"]:"long long ago") . " ~ " . ($_REQUEST["end_date"]>0?$_REQUEST["end_date"]:"Today");

	$header_css = 'align="center" style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$h1 = ' style="background-color:#FFE3AE;"';
	$c1 = ' style="background-color:#FFF5D7;"';
	$c2 = ' style="background-color:#FFD7EE;"';
	$c21 = ' style="background-color:#FFD7EE; text-align:left;"';
	$c3 = ' style="background-color:#EBFAD3;"';
	$c31 = ' style="background-color:#EBFAD3;; text-align:right;"';
	$c4 = ' style="background-color:#BFF1F8;"';

	$width_one = '';
	$width_two = '';

	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="8" align="center"><span style="font-size:12px; font-weight:bold;">Volunteer Hours Report By Volunteer<br>' . $period . '</span></td>';
	$html .= '</tr>';

	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Chinese Name</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>English Name</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Dharma Name</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="2" ' . $h1 . '></td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Count</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Total Hour</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Depart.No</td>';
	$html .= '</tr>';
	
	if( $level >= 2) {
		$html .= '<tr>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="4" ' . $c21 . '>Department Title</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $c2 . '>Count</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $c2 . '>Total Hour</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html .= '</tr>';
	}
	if( $level >= 3) {
		$html .= '<tr>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3"></td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="2" ' . $c31 . '>Purpose</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Work Date</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Work Hour</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html .= '</tr>';
	}
	
	while($row0 = $db->fetch($result0)) {
		$pid = $row0["id"];

		$html .= '<tr>';
		$html .= '<td align="left"' . $c1 . '>';
		$html .= ($cnt0 + 1) . '. ' . $row0["cname"];
		$html .= '</td>';

		$html .= '<td align="left"' . $c1 . '>';
		$html .=$row0["en_name"];
		$html .= '</td>';

		$html .= '<td align="left"' . $c1 . '>';
		$html .= $row0["dharma_name"];
		$html .= '</td>';

		$html .= '<td colspan="2" align="right"' . $c1 . '>';
		$html .= 'Total:';
		$html .= '</td>';

		$html .= '<td align="right"' . $c1 . '>';
		$html .= $row0["work_count"]?$row0["work_count"]:"";
		$html .= '</td>';

		$html .= '<td align="right"' . $c1 . '>';
		$html .= $row0["total_hour"]?$row0["total_hour"]:"";
		$html .= '</td>';

		$html .= '<td align="right"' . $c1 . '>';
		$html .= $row0["total_head"]?$row0["total_head"]:"";
		$html .= '</td>';


		$html .= '</tr>';

		if($level >= 2) {
				$query1 = "SELECT b.id, b.title, b.sn, sum(work_hour) as total_hour, count(a.id) as work_count  
								FROM puti_volunteer_hours a INNER JOIN puti_department b ON (a.department_id = b.id) 
								INNER JOIN puti_volunteer c ON (a.volunteer_id = c.id) 
								WHERE 	volunteer_id = '" . $pid . "' AND 
										c.deleted <> 1 AND 
								   		c.site IN " . $admin_user["sites"]  . " 
								 $ccc $criteria
								GROUP BY b.id, b.title, b.sn   
								ORDER BY b.sn DESC, b.title";
				
				$result1	= $db->query($query1);
				$cnt1 = 0;
				while($row1 = $db->fetch($result1)) {

					$html .= '<tr>';
					$html .= '<td></td>';
					
					$html .= '<td colspan="3" align="left"' . $c2 . '>';
					$html .= $row1["title"];
					$html .= '</td>';
					
					$html .= '<td align="right"' . $c2 . '>';
					$html .= 'Total:';
					$html .= '</td>';
					
					$html .= '<td align="right"' . $c2 . '>';
					$html .= $row1["work_count"];
					$html .= '</td>';
					
					$html .= '<td align="right"' . $c2 . '>';
					$html .= $row1["total_hour"];
					$html .= '</td>';

					$html .= '<td></td>';
					
					$html .= '</tr>';
					
					if($level >= 3) {
						  $query2	= "SELECT a.id, purpose, work_date, work_hour, 
											CONCAT( IF( purpose='', '', CONCAT(purpose, '::') ) ,IFNULL(d.job_title, '" . $words["unknown"] . "') )as job_title 						  					
											FROM puti_volunteer_hours a 
											INNER JOIN puti_department b ON (a.department_id = b.id) 
											INNER JOIN puti_volunteer c ON (a.volunteer_id = c.id) 
									  		LEFT JOIN puti_department_job d ON (a.department_id = d.department_id AND a.job_id = d.job_id) 
											WHERE 	c.deleted <> 1 AND 
													a.department_id = '" . $row1["id"] . "' AND 
													a.volunteer_id = '" . $pid . "' AND  
											   		c.site IN " . $admin_user["sites"]  . " 
											 $ccc $criteria ORDER BY work_date";
												  
						  $result2	= $db->query($query2);
						  $cnt2 = 0;
						  while($row2 = $db->fetch($result2)) {
							  $html .= '<tr>';
							  $html .= '<td colspan="3">';
							  $html .= '</td>';

							  $html .= '<td colspan="2" style="text-align:right;"' . $c3 . '>';
							  $html .= $row2["job_title"];
							  $html .= '</td>';
							  
							  $html .= '<td align="right"' . $c3 . '>';
							  $html .= date("Y-m-d",$row2["work_date"]);
							  $html .= '</td>';
							  
							  $html .= '<td align="right"' . $c3 . '>';
							  $html .= $row2["work_hour"];
							  $html .= '</td>';

							  $html .= '<td></td>';

							  $cnt2++;
						  }  // loop for detail
					}
					$cnt1++;
		
				} // loop for person
		} // level > 2
		
		$cnt0++;
		
	} // loop for department


	// summary	
	$query000 = "SELECT sum(work_hour) as total_hour, count(a.id) as work_count, count(distinct a.department_id) as total_head  
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 	1 = 1 AND 
							b.site IN " . $admin_user["sites"]  . "  AND 
							b.deleted <> 1 $ccc $criteria";
	$result000 	= $db->query($query000);
	$row000 		= $db->fetch($result000);
	
	
	$html .= '<tr>';
	$html .= '<td colspan="4" align="left"' . $c4 . '>';
	$html .= '</td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= 'Grand Total:';
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= $row000["work_count"]?$row000["work_count"]:"";
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= $row000["total_hour"]?$row000["total_hour"]:"";
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= $row000["total_head"]?$row000["total_head"]:"";
	$html .= '</b></td>';
	
	$html .= '</tr>';
	
	$html .= '<table>';
	echo $html;
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
