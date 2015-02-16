<?php 
//session_start();
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=volunteer_hours_report_bydep.xls");
 
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
	
	$period = ($_REQUEST["start_date"]>0?$_REQUEST["start_date"]:"long long ago") . " ~ " . ($_REQUEST["end_date"]>0?$_REQUEST["end_date"]:"Today");


	$header_css = ' style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';

	$h1 = ' style="background-color:#FFE3AE;"';
	$c1 = ' style="background-color:#FFF5D7;"';
	$c2 = ' style="background-color:#FFD7EE;"';
	$c3 = ' style="background-color:#EBFAD3;"';
	$c4 = ' style="background-color:#BFF1F8;"';
	
	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="8" align="center"><span style="font-size:12px; font-weight:bold;">Volunteer Hours Report By Department<br>' . $period . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html.= '<td align="left" ' . $width_one . ' ' . $header_css . ' colspan="5" '. $h1 . '>Department</td>';
	$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $h1 . '>Count</td>';
	$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $h1 . '>Total Hour</td>';
	$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $h1 . '>Head</td>';
	$html .= '</tr>';

	if( $level >= 2) {
		$html .= '<tr>';
		$html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $c2 . '>Chinese Name</td>';
		$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $c2 . '>Englis Name</td>';
		$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $c2 . '>Dharma Name</td>';
		$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $c2 . '></td>';
		$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $c2 . '>Count</td>';
		$html.= '<td align="center" ' . $width_one . ' ' . $header_css . ' '. $c2 . '>Total Hour</td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html .= '</tr>';
	}
	
	if( $level >= 3) {
		  $html .= '<tr>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' colspan="3"></td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' colspan="2" align="right" '. $c3 . '>Purpose</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' '. $c3 . '>Work Date</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' '. $c3 . '>Work Hour</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		  $html .= '</tr>';
	}
	$departArr = array();
	while($row0 = $db->fetch($result0)) {
		$pid = $row0["id"];
		$query11 	= "SELECT count(a.id) as work_count, sum(work_hour) as total_hour , count(distinct volunteer_id) as total_head  
						FROM puti_volunteer_hours  a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
						WHERE  	a.department_id = '" . $pid . "' AND 
								b.deleted <> 1 AND 
							   	b.site IN " . $admin_user["sites"]  . " 
						       $ccc ";
		$result11 	= $db->query($query11);
		$row11 		= $db->fetch($result11);


		$html .= '<tr>';
		$html .= '<td colspan="4" align="left"' . $c1 . '>';
		$html .= ($cnt0 + 1) . '. ' . $row0["title"];
		$html .= '</td>';
		
		$html .= '<td align="right"' . $c1 . '>';
		$html .= 'Total:';
		$html .= '</td>';

		$html .= '<td align="right"' . $c1 . '>';
		$html .= $row11["work_count"]?$row11["work_count"]:"";
		$html .= '</td>';

		$html .= '<td align="right"' . $c1 . '>';
		$html .= $row11["total_hour"]?$row11["total_hour"]:"";
		$html .= '</td>';

		$html .= '<td align="right"' . $c1 . '>';
		$html .= $row11["total_head"]?$row11["total_head"]:"";
		$html .= '</td>';

		$html .= '</tr>';
				
		if($level >= 2) {
				$query1 	= "SELECT volunteer_id as vid, b.en_name, b.pname, b.dharma_name, b.cname, count(a.id) as work_count, sum(work_hour) as total_hour 
								FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
								WHERE 	b.deleted <> 1 AND 
										department_id = '" . $pid . "' $ccc GROUP BY volunteer_id, b.en_name, b.pname, b.dharma_name, b.cname ORDER BY total_hour DESC, b.en_name, b.pname, b.dharma_name, b.cname";
				
				$result1	= $db->query($query1);
				$cnt1 = 0;
				while($row1 = $db->fetch($result1)) {
						$html .= '<tr>';
						$html .= '<td></td>';
						
						$html .= '<td align="left"' . $c2 . '>';
						$html .= $row1["cname"];
						$html .= '</td>';
						
						$html .= '<td align="left"' . $c2 . '>';
						$html .= $row1["en_name"];
						$html .= '</td>';
						
						$html .= '<td align="left"' . $c2 . '>';
						$html .= $row1["dharma_name"];
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
						  $query2	= "SELECT a.id, purpose, work_date, work_hour, CONCAT( IF( purpose='', '', CONCAT(purpose, '::') ) ,IFNULL(c.job_title, '" . $words["unknown"] . "') )as job_title 
									  FROM puti_volunteer_hours  a 
									  INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)   
									  LEFT JOIN puti_department_job c ON (a.department_id = c.department_id AND a.job_id = c.job_id) 
									  WHERE b.deleted <> 1 AND 
									  		a.department_id = '" . $pid . "' AND 
											a.volunteer_id = '" . $row1["vid"] . "' AND 
											b.site IN " . $admin_user["sites"] . "
									  $ccc ORDER BY work_date";
												  
						  $result2	= $db->query($query2);
						  $cnt2 = 0;
						  while($row2 = $db->fetch($result2)) {
							  $html .= '<tr>';
							  $html .= '<td colspan="3">';
							  $html .= '</td>';

							  $html .= '<td colspan="2" align="right"' . $c3 . '>';
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
	$query22 	= "SELECT count(a.id) as work_count, sum(work_hour) as total_hour, count(distinct volunteer_id) as total_head 
					FROM puti_volunteer_hours  a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 	b.deleted <> 1 AND 
							department_id in $pcon  AND 
							b.site IN " . $admin_user["sites"] . " $ccc ";
	$result22 	= $db->query($query22);
	$row22 		= $db->fetch($result22);


	$html .= '<tr>';
	$html .= '<td colspan="4" align="left"' . $c4 . '>';
	$html .= '</td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= 'Grand Total:';
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= $row22["work_count"]?$row22["work_count"]:"";
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= $row22["total_hour"]?$row22["total_hour"]:"";
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c4 . '><b>';
	$html .= $row22["total_head"]?$row22["total_head"]:"";
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
