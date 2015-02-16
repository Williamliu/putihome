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
	header("Content-disposition:  attachment; filename=volunteer_hours_report_byJobContent.xls");
 
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


	$header_css = 'align="center" style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';

	$h1 = ' style="background-color:#FFE3AE;"';
	$h11 = ' style="background-color:#FFE3AE; text-align:left;"';
	$c1 = ' style="background-color:#FFF5D7;"';
	$c2 = ' style="background-color:#FFD7EE;"';
	$c21 = ' style="background-color:#FFD7EE; text-align:left;"';
	$c3 = ' style="background-color:#EBFAD3;"';
	$c4 = ' style="background-color:#BFF1F8;"';
	$c41 = ' style="background-color:#BFF1F8; text-align:right;"';
	$c5 = ' style="background-color:#7AC8D8;"';
	
	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="9" align="center"><span style="font-size:12px; font-weight:bold;">Volunteer Hours Report By Department, Job Content<br>' . $period . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html.= '<td colspan="5" ' . $width_one . ' ' . $header_css . ' ' . $h11 . '>Department</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '></td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Count</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Total Hour</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $h1 . '>Head</td>';
	$html .= '</tr>';


	if( $level >= 2) {
		  $html .= '<tr>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		  $html.= '<td colspan="4" ' . $width_one . ' ' . $header_css . ' ' . $c21 . '>Job Content</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c2 . '></td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c2 . '>Count</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c2 . '>Total Hours</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c2 . '>Head</td>';
		  $html .= '</tr>';
	}

	if( $level >= 3) {
		$html .= '<tr>';
		$html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Chinese Name</td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Englis Name</td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Dharma Name</td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '></td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Count</td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c3 . '>Total Hour</td>';
		$html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		$html .= '</tr>';
	}
	
	if( $level >= 4) {
		  $html .= '<tr>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' colspan="4"></td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' colspan="2" ' . $c4 . '>Job Content</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c4 . '>Work Date</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . ' ' . $c4 . '>Work Hour</td>';
		  $html.= '<td ' . $width_one . ' ' . $header_css . '></td>';
		  $html .= '</tr>';
	}
	
	//level 11111 
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
		$html .= '<td colspan="5" align="left"' . $c1 . '>';
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
		
		// level 22222		
		if($level >= 2) {
				$jobsArr = array();
				$query2 	= "SELECT	 a.department_id, a.job_id, IFNULL(b.job_title, '" . $words["unknown"] . "') AS job_title, 
										 COUNT(a.id) as work_count, 
										 SUM(work_hour) as total_hour,
										 COUNT(distinct a.volunteer_id) as total_head  
								FROM puti_volunteer_hours a 
								LEFT JOIN puti_department_job b ON ( a.department_id = b.department_id AND a.job_id = b.job_id )
								WHERE 	a.department_id = '" . $pid . "' AND 
										a.site IN " . $admin_user["sites"] . " $ccc GROUP BY a.department_id, a.job_id";
				
				$result2	= $db->query($query2);
				$jobsArr    = $db->rows($result2);	

				$jlists = array();
				foreach($jobsArr as $val) {
				  $jlists[] = $val["job_id"];
				}
			  
				$res_jlist = $db->query("SELECT * FROM puti_department_job WHERE department_id = '" . $pid . "'");			
				while( $row_jlist = $db->fetch($res_jlist) ) {
				  if( !in_array( $row_jlist["job_id"], $jlists) ) {
					  $ridx = count($jobsArr);
					  $jobsArr[$ridx]["job_id"]		=  $row_jlist["job_id"];
					  $jobsArr[$ridx]["job_title"] 	=  $row_jlist["job_title"];
					  $jobsArr[$ridx]["work_count"] =  "";
					  $jobsArr[$ridx]["total_hour"] =  "";
					  $jobsArr[$ridx]["total_head"] =  "";
				  }
				}

				// level 333
				if($level >= 3) {
					foreach( $jobsArr as $key=>$job ) {
							$query3 	= "SELECT a.job_id, 
													volunteer_id as vid, b.en_name, b.pname, b.dharma_name, b.cname, 
													count(a.id) as work_count, 
													sum(work_hour) as total_hour,
													count(distinct a.volunteer_id) as total_head  
											FROM puti_volunteer_hours a 
											INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
											LEFT  JOIN puti_department_job c ON (a.department_id = c.department_id AND a.job_id = c.job_id) 
											WHERE 	a.job_id = '" . $job["job_id"] . "' AND 
													a.department_id = '" . $pid . "' AND 
													b.deleted <> 1 AND 
													b.site IN " . $admin_user["sites"] . " $ccc GROUP BY a.job_id, volunteer_id, b.en_name, b.pname, b.dharma_name, b.cname ORDER BY a.job_id, total_hour DESC, b.en_name, b.pname, b.dharma_name, b.cname";
							
							$result3	= $db->query($query3);
							$cnt1 = 0;
							while($row1 = $db->fetch($result3)) {
								$volObj = array();
								$volObj["job_id"] 		= $row1["job_id"];
								$volObj["job_title"] 	= $row1["job_title"];
								$volObj["id"] 			= $row1["vid"];
								$volObj["work_count"] 	= $row1["work_count"]>0?$row1["work_count"]:"";
								$volObj["total_hour"] 	= $row1["total_hour"]>0?$row1["total_hour"]:"";
								$volObj["total_head"] 	= $row1["total_head"]>0?$row1["total_head"]:"";
								$volObj["dharma_name"] 	= $row1["dharma_name"];
								$volObj["cname"] 		= $row1["cname"];
								$volObj["pname"] 		= $row1["pname"];
								$volObj["en_name"] 		= $row1["en_name"];
								$volObj["detail"] = array();
								if($level >= 4) {
									  $query2	= "SELECT c.job_id, 
															CONCAT( IF( purpose='', '', CONCAT(purpose, '::') ) ,IFNULL(c.job_title, '" . $words["unknown"] . "') )as job_title,  
															a.id, purpose, work_date, work_hour
												  FROM puti_volunteer_hours  a 
												  INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)  
												  LEFT JOIN puti_department_job c ON (a.department_id = c.department_id AND a.job_id = c.job_id) 
												  WHERE a.job_id = '" . $job["job_id"] . "' AND 
														b.deleted<> 1 AND 
														a.department_id = '" . $pid . "' AND 
														a.volunteer_id = '" . $row1["vid"] . "' AND 
														b.site IN " . $admin_user["sites"] . "
												  $ccc ORDER BY work_date";
															  
									  $result2	= $db->query($query2);
									  $cnt2 = 0;
									  while($row2 = $db->fetch($result2)) {
										  $detailObj = array();
										  $detailObj["job_id"]		= $row2["job_id"];
										  $detailObj["job_title"]	= $row2["job_title"];
										  $detailObj["id"]			= $row2["id"];
										  $detailObj["purpose"] 	= $row2["purpose"];
										  $detailObj["work_date"]	= date("Y-m-d",$row2["work_date"]);
										  $detailObj["work_hour"] 	= $row2["work_hour"];
										  $volObj["detail"][$cnt2] 	= $detailObj;
										  $cnt2++;
									  }  // loop for detail
								}
								$jobsArr[$key]["volunteer"][$cnt1] = $volObj;	
								$cnt1++;
					
							} // loop for person
						}
					} 
					// end of level 333
					$departObj["jobs"] = $jobsArr;
					
					// output to html 					
					foreach( $departObj["jobs"] as $jobs ) {
						  // output level22222
						  if($level >= 2) {	
								$html .= '<tr>';
								$html .= '<td></td>';
								
								$html .= '<td colspan="4" align="left" ' . $c2 . '>';
								$html .= $jobs["job_title"];
								$html .= '</td>';
								
								$html .= '<td align="right"' . $c2 . '>';
								$html .= 'Total:';
								$html .= '</td>';
						
								$html .= '<td align="right"' . $c2 . '>';
								$html .= $jobs["work_count"];
								$html .= '</td>';

								$html .= '<td align="right"' . $c2 . '>';
								$html .= $jobs["total_hour"];
								$html .= '</td>';

								$html .= '<td align="right"' . $c2 . '>';
								$html .= $jobs["total_head"];
								$html .= '</td>';
						
								$html .= '</tr>';
								
								// level 3333
							  	if($level >= 3) {
									foreach( $jobs["volunteer"] as $volunteer ) {
										$html .= '<tr>';
										$html .= '<td colspan="2"></td>';
										
										$html .= '<td ' . $c3 . '>';
										$html .= $volunteer["cname"];
										$html .= '</td>';

										$html .= '<td ' . $c3 . '>';
										$html .= $volunteer["en_name"];
										$html .= '</td>';
										

										$html .= '<td ' . $c3 . '>';
										$html .= $volunteer["dharma_name"];
										$html .= '</td>';

										$html .= '<td align="right"' . $c3 . '>';
										$html .= 'Total:';
										$html .= '</td>';
								
										$html .= '<td align="right"' . $c3 . '>';
										$html .= $volunteer["work_count"];
										$html .= '</td>';
		
										$html .= '<td align="right"' . $c3 . '>';
										$html .= $volunteer["total_hour"];
										$html .= '</td>';
		
										$html .= '<td></td>';
								
										$html .= '</tr>';
									
										// level 4444
										if($level >= 4) {
											foreach( $volunteer["detail"] as $detail ) {
												$html .= '<tr>';
												$html .= '<td colspan="4"></td>';
												
												$html .= '<td colspan="2" ' . $c41 . '>';
												$html .= $detail["job_title"];
												$html .= '</td>';
		
												$html .= '<td ' . $c4 . '>';
												$html .= $detail["work_date"];
												$html .= '</td>';
		
												$html .= '<td align="right"' . $c4 . '>';
												$html .= $detail["work_hour"];
												$html .= '</td>';
				
												$html .= '<td></td>';
										
												$html .= '</tr>';
											}
										}
										// end of level 4444
									
									
									}
								}
								// end of level 3333
	
								
								
						  }
						  // end output level 2222
					}
					// end of output to html 					
					
					
					
					

		} // end of level 22222
		
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
	$html .= '<td colspan="5" align="left"' . $c4 . '>';
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
