<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=idcard_report.xls");
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();
	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "created_time >= '" . $sd . "' AND created_time <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "created_time >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "created_time <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	$pcon = '(-1)';
	if($_REQUEST["sites"] != "") {
		$pcon = '(' . $_REQUEST["sites"] . ')';
	}
	
	$query0 = "SELECT id, title FROM puti_sites 
					WHERE id in $pcon 
					ORDER BY sn DESC";
	$result0 = $db->query($query0);
	$cnt0=0;
	$departArr = array();

	$period = ($_REQUEST["start_date"]>0?$_REQUEST["start_date"]:"long long ago") . " ~ " . ($_REQUEST["end_date"]>0?$_REQUEST["end_date"]:"Today");

	$header_css = 'align="center" style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="29" align="center"><span style="font-size:12px; font-weight:bold;">ID 刷卡 - 统计报表<br>' . $period . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">序号</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">禅堂</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">殿堂</td>';
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
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>刷卡</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>人数</td>';
	$html .= '</tr>';
	
	
	// loop for sites
	while($row0 = $db->fetch($result0)) {
		$pid = $row0["id"];
		$query1 	= "SELECT  
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 1, member_id, null)) as cm1,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 2, member_id, null)) as cm2,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 3, member_id, null)) as cm3,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 4, member_id, null)) as cm4,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 5, member_id, null)) as cm5,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 6, member_id, null)) as cm6,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 7, member_id, null)) as cm7,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 8, member_id, null)) as cm8,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 9, member_id, null)) as cm9,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 10, member_id, null)) as cm10,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 11, member_id, null)) as cm11,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 12, member_id, null)) as cm12,
						
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 1, member_id, null)) as ch1,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 2, member_id, null)) as ch2,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 3, member_id, null)) as ch3,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 4, member_id, null)) as ch4,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 5, member_id, null)) as ch5,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 6, member_id, null)) as ch6,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 7, member_id, null)) as ch7,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 8, member_id, null)) as ch8,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 9, member_id, null)) as ch9,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 10, member_id, null)) as ch10,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 11, member_id, null)) as ch11,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 12, member_id, null)) as ch12,

						COUNT(member_id) as tcnt, 
						COUNT(distinct member_id) as thead 
						FROM puti_device_record   
						WHERE site = '" . $pid . "'	 $ccc ";
		
		$result1 	= $db->query($query1);
		$row1 		= $db->fetch($result1);
		
		$departObj = array();
		$departObj["id"] 		= $row0["id"];
		$departObj["site_desc"] = $words[strtolower($row0["title"])];
		$departObj["place_desc"]= $words["total"];
		

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

		$departObj["tcnt"] 		= $row1["tcnt"]>0?$row1["tcnt"]:"";
		$departObj["thead"] 	= $row1["thead"]>0?$row1["thead"]:"";


		$bg_css = ' style="background-color:#E8F3D5; white-space:nowrap;"';
		$html .= '<tr>';
		$html .= '<td align="center"'. $bg_css .'>';
		$html .= $cnt0 + 1;
		$html .= '</td>';
		
		$html .= '<td align="left"'. $bg_css .'>';
		$html .= $departObj["site_desc"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $words["total"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm1"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch1"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm2"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch2"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm3"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch3"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm4"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch4"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm5"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch5"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm6"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch6"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm7"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch7"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm8"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch8"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm9"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch9"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm10"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch10"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm11"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch11"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["cm12"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["ch12"];
		$html .= '</td>';

		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["tcnt"];
		$html .= '</td>';
		$html .= '<td align="right"'. $bg_css .'>';
		$html .= $departObj["thead"];
		$html .= '</td>';

		$html .= '</tr>';



		// places
			  $query111 	= "SELECT  
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 1, member_id, null)) as cm1,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 2, member_id, null)) as cm2,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 3, member_id, null)) as cm3,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 4, member_id, null)) as cm4,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 5, member_id, null)) as cm5,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 6, member_id, null)) as cm6,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 7, member_id, null)) as cm7,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 8, member_id, null)) as cm8,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 9, member_id, null)) as cm9,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 10, member_id, null)) as cm10,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 11, member_id, null)) as cm11,
							  COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 12, member_id, null)) as cm12,
							  
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 1, member_id, null)) as ch1,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 2, member_id, null)) as ch2,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 3, member_id, null)) as ch3,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 4, member_id, null)) as ch4,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 5, member_id, null)) as ch5,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 6, member_id, null)) as ch6,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 7, member_id, null)) as ch7,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 8, member_id, null)) as ch8,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 9, member_id, null)) as ch9,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 10, member_id, null)) as ch10,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 11, member_id, null)) as ch11,
							  COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 12, member_id, null)) as ch12,
							  site,
							  '' as site_desc,
							  place,
							  b.title as place_desc, 
							  COUNT(member_id) as tcnt, 
							  COUNT(distinct member_id) as thead 
							  FROM puti_device_record a 
							  INNER JOIN puti_places b ON (a.place = b.id) 
							  WHERE site = $pid $ccc GROUP BY site, site_desc, place, place_desc";
		
				
			  $result111 	= $db->query($query111);
			  $row111 	= $db->rows($result111); 
			  foreach($row111 as $key=>$val) {
			  	$row111[$key]["place_desc"] = $words[strtolower($val["place_desc"])];
				foreach($val as $key1=>$val1) {
					if( is_numeric($val1) && $val1==0) $row111[$key][$key1] = "";
				}
			  }

			  $departObj["places"] 	= $row111;
			  
			  $bg_css = ' style="background-color:#ffffff; white-space:nowrap;"';
              // jobs loop
			  foreach( $row111 as $job) {
					$html .= '<tr>';
					$html .= '<td align="center"'. $bg_css .'>';
					$html .= '</td>';
					
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["site_desc"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["place_desc"];
					$html .= '</td>';
			
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm1"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch1"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm2"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch2"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm3"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch3"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm4"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["chm4"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm5"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch5"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm6"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch6"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm7"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch7"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm8"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch8"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm9"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch9"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm10"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch10"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm11"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch11"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["cm12"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["ch12"];
					$html .= '</td>';

					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["tcnt"];
					$html .= '</td>';
					$html .= '<td align="right"'. $bg_css .'>';
					$html .= $job["thead"];
					$html .= '</td>';
			
					$html .= '</tr>';
			  
			  }
			// end of jobs loop			  
			  
			  
			  
			// end of place

		$departArr[$cnt0] = $departObj;
		$cnt0++;
		
	} // loop for sites




	$query2 	= "SELECT 
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 1, member_id, null)) as cm1,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 2, member_id, null)) as cm2,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 3, member_id, null)) as cm3,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 4, member_id, null)) as cm4,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 5, member_id, null)) as cm5,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 6, member_id, null)) as cm6,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 7, member_id, null)) as cm7,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 8, member_id, null)) as cm8,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 9, member_id, null)) as cm9,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 10, member_id, null)) as cm10,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 11, member_id, null)) as cm11,
						COUNT(IF( MONTH(FROM_UNIXTIME(created_time)) = 12, member_id, null)) as cm12,
						
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 1, member_id, null)) as ch1,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 2, member_id, null)) as ch2,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 3, member_id, null)) as ch3,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 4, member_id, null)) as ch4,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 5, member_id, null)) as ch5,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 6, member_id, null)) as ch6,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 7, member_id, null)) as ch7,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 8, member_id, null)) as ch8,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 9, member_id, null)) as ch9,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 10, member_id, null)) as ch10,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 11, member_id, null)) as ch11,
						COUNT(distinct IF( MONTH(FROM_UNIXTIME(created_time)) = 12, member_id, null)) as ch12,
					
						COUNT(member_id) as tcnt,
						COUNT(distinct member_id) as thead
					FROM puti_device_record 
					WHERE  site in $pcon $ccc";
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
	$html .= '<td colspan="3" align="right"><b>';
	$html .= $words["grand total"];
	$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["cm1"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch1"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm2"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch2"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm3"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch3"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm4"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch4"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm5"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch5"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm6"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch6"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm7"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch7"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm8"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch8"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm9"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch9"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm10"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch10"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm11"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch11"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .= $grand["cm12"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .= $grand["ch12"];
		$html .= '</b></td>';

		$html .= '<td align="right"><b>';
		$html .=  $grand["tcnt"];
		$html .= '</b></td>';
		$html .= '<td align="right"><b>';
		$html .=  $grand["thead"];
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
