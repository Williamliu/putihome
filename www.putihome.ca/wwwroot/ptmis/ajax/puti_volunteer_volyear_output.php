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
	header("Content-disposition:  attachment; filename=Volunteer_Annual_Report_ByVol.xls");

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
///////////////////////////////////////////////////////////////////////////////////////////
	$depart = "(-1)";
	if($admin_user["department"] != "") $depart = "(" . $admin_user["department"] . ")";
	
	$type = $_REQUEST["type"];
	
	$query0 = "SELECT b.id, b.cname, b.pname, b.en_name, b.dharma_name, sum(work_hour) as total_hour, count(a.id) as work_count  
					FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					WHERE 	b.deleted <> 1 AND 
							a.department_id in $depart  AND 
							b.site IN " . $admin_user["sites"]  . " $ccc $criteria
					GROUP BY b.id, b.cname, b.pname, b.en_name, b.dharma_name 
					ORDER BY total_hour DESC, b.en_name, b.pname, b.dharma_name, b.cname";

	$result0 = $db->query($query0);

	$period = ($_REQUEST["start_date"]>0?$_REQUEST["start_date"]:"long long ago") . " ~ " . ($_REQUEST["end_date"]>0?$_REQUEST["end_date"]:"Today");

	$header_css = 'align="center" style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="30" align="center"><span style="font-size:12px; font-weight:bold;">Annual Volunteer Report<br>' . $period . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">序号</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">中文名</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">英文名</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">法名</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">一月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">二月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">三月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">四月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">五月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">六月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">七月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">八月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">九月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">十月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">十一月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">十二月</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '  colspan="2">总计</td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . ($type=="Time"?"次数":"部门") . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>工时</td>';
	$html .= '</tr>';

	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$pid = $row0["id"];
		$query1 	= "SELECT  
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, department_id, null)) as cm1,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, department_id, null)) as cm2,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, department_id, null)) as cm3,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, department_id, null)) as cm4,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, department_id, null)) as cm5,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, department_id, null)) as cm6,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, department_id, null)) as cm7,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, department_id, null)) as cm8,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, department_id, null)) as cm9,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, department_id, null)) as cm10,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, department_id, null)) as cm11,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, department_id, null)) as cm12,
						
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, department_id, null)) as ch1,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, department_id, null)) as ch2,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, department_id, null)) as ch3,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, department_id, null)) as ch4,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, department_id, null)) as ch5,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, department_id, null)) as ch6,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, department_id, null)) as ch7,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, department_id, null)) as ch8,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, department_id, null)) as ch9,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, department_id, null)) as ch10,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, department_id, null)) as ch11,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, department_id, null)) as ch12,

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
						COUNT(distinct a.department_id) as thead, 
						SUM(work_hour) as thour			
						FROM puti_volunteer_hours a INNER JOIN puti_volunteer b ON(a.volunteer_id = b.id)   
						WHERE 	b.deleted <> 1 AND  
								a.department_id in $depart AND 
								volunteer_id = '" . $pid . "' AND 
								b.site IN " . $admin_user["sites"]  . " $ccc $criteria";

		$result1 	= $db->query($query1);
		$row1 		= $db->fetch($result1);

		$departObj = array();
		$departObj["id"] 		= $row0["id"];
		$departObj["cname"] 	= $row0["cname"];
		$departObj["pname"] 	= $row0["pname"];
		$departObj["en_name"] 	= $row0["en_name"];
		$departObj["dharma_name"] = $row0["dharma_name"];
		
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

		$html .= '<tr>';
		$html .= '<td align="center">';
		$html .= $cnt0 + 1;
		$html .= '</td>';
		
		$html .= '<td align="left">';
		$html .= $departObj["cname"];
		$html .= '</td>';

		$html .= '<td align="left">';
		$html .= $departObj["en_name"];
		$html .= '</td>';

		$html .= '<td align="left">';
		$html .= $departObj["dharma_name"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm1"]:$departObj["ch1"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm1"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm2"]:$departObj["ch2"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm2"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm3"]:$departObj["ch3"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm3"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm4"]:$departObj["ch4"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm4"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm5"]:$departObj["ch5"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm5"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm6"]:$departObj["ch6"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm6"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm7"]:$departObj["ch7"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm7"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm8"]:$departObj["ch8"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm8"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm9"]:$departObj["ch9"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm9"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm10"]:$departObj["ch10"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm10"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm11"]:$departObj["ch11"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm11"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["cm12"]:$departObj["ch12"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["hm12"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $type=="Time"?$departObj["tcnt"]:$departObj["thead"];
		$html .= '</td>';
		$html .= '<td align="right">';
		$html .= $departObj["thour"];
		$html .= '</td>';

		$html .= '</tr>';

		$cnt0++;
		
	} // loop for department

	$query2 	= "SELECT 
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, department_id, null)) as cm1,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, department_id, null)) as cm2,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, department_id, null)) as cm3,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, department_id, null)) as cm4,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, department_id, null)) as cm5,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, department_id, null)) as cm6,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, department_id, null)) as cm7,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, department_id, null)) as cm8,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, department_id, null)) as cm9,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, department_id, null)) as cm10,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, department_id, null)) as cm11,
						COUNT(IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, department_id, null)) as cm12,
						
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 1, department_id, null)) as ch1,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 2, department_id, null)) as ch2,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 3, department_id, null)) as ch3,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 4, department_id, null)) as ch4,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 5, department_id, null)) as ch5,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 6, department_id, null)) as ch6,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 7, department_id, null)) as ch7,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 8, department_id, null)) as ch8,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 9, department_id, null)) as ch9,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 10, department_id, null)) as ch10,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 11, department_id, null)) as ch11,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(work_date + 3600)) = 12, department_id, null)) as ch12,

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
						COUNT(distinct a.department_id) as thead, 
						SUM(work_hour) as thour			
					FROM puti_volunteer_hours  a INNER JOIN puti_volunteer b ON(a.volunteer_id = b.id)  
					WHERE 	b.deleted <> 1 AND 
							a.department_id in $depart AND 
							b.site IN " . $admin_user["sites"]  . " $ccc $criteria";
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
	
	$html .= '<tr>';
	$html .= '<td colspan="4" align="right"><b>';
	$html .= 'Grand Total:';
	$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm1"]:$grand["ch1"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm1"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm2"]:$grand["ch2"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm2"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm3"]:$grand["ch3"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm3"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm4"]:$grand["ch4"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm4"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm5"]:$grand["ch5"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm5"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm6"]:$grand["ch6"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm6"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm7"]:$grand["ch7"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm7"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm8"]:$grand["ch8"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm8"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm9"]:$grand["ch9"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm9"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm10"]:$grand["ch10"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm10"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm11"]:$grand["ch11"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm11"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $type=="Time"?$grand["cm12"]:$grand["ch12"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["hm12"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .=  $type=="Time"?$grand["tcnt"]:$grand["thead"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .=  $grand["thour"];
		$html .= '</b></td>';

		$html .= '</tr>';
		$html .= '</table>';
	

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
