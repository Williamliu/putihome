<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=Event_Attendance_Report.xls");
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);


	$order_str = "ORDER BY group_no, leader DESC, volunteer DESC, b.last_name, b.first_name"; 
	
	// condition here 
	$criteria = "";
	$con = $_REQUEST; 

	$sch_11 = trim($con["event_id"]);
	if($sch_11 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "event_id like '%" . $sch_11 . "%'";
	}

	$sch_trial = trim($con["sch_trial"]);
	if($sch_trial != "") {
		$criteria .= ($criteria==""?"":" AND ") . "trial = '" . $sch_trial . "'";
	}

	$sch_unauth = trim($con["sch_unauth"]);
	if($sch_unauth != "") {
		$criteria .= ($criteria==""?"":" AND ") . "unauth = '" . $sch_unauth . "'";
	}


	$sch_222 = trim($con["sch_sign"]);
	if($sch_222 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "signin = '" . $sch_222 . "'";
	}


	$sch_333 = trim($con["sch_grad"]);
	if($sch_333 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "graduate = '" . $sch_333 . "'";
	}

	$sch_444 = trim($con["sch_cert"]);
	if($sch_444 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "cert = '" . $sch_444 . "'";
	}

	$sch_name = trim($con["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . 
						"( 	first_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							last_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							legal_first like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							legal_last like '%" .	cTYPE::trans_trim($sch_name) . "%' OR 
							dharma_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							alias like '%" . cTYPE::trans_trim($sch_name) . "%' OR
							concat(first_name, last_name) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(last_name,  first_name) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(legal_first, legal_last) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(legal_last, legal_first) like '%" . 	cTYPE::trans_trim($sch_name) . "%'
						)";
	}

	$sch_group = trim($con["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . $sch_group . "'";
	}

	$sch_rate = trim($con["sch_rate"]);
	if($sch_rate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "attend >= '" . ($sch_rate/100) . "'";
	}

	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "b.id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "b.id = '-1'";
		}
	}
		
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria



	// header stuff	
	$eid = $con["event_id"];

	$query0 	= "SELECT title, start_date, end_date FROM event_calendar WHERE deleted <> 1 AND id = '" . $eid . "'";
	$result0 	= $db->query($query0);
	$row0 		= $db->fetch($result0);

	$query00 = "SELECT  event_id, id as event_date_id, yy, mm, dd, event_date, checkin, day_no 
						FROM event_calendar_date
						WHERE event_id = '" . $eid . "' 
						ORDER BY day_no";
	$result00 = $db->query($query00);
	$head = array();
	$cnt = 0;
	$total_checkin = 0;
	while($row00	= $db->fetch($result00)) {
		$hObj = array();
		$hObj["event_id"] 		= $row00["event_id"];
		$hObj["event_date_id"] 	= $row00["event_date_id"];
		$hObj["day_no"] 		= $row00["day_no"];
		$hObj["yy"] 			= $row00["yy"];
		$hObj["mm"] 			= $row00["mm"];
		$hObj["dd"] 			= $row00["dd"];
		$hObj["event_date"] 	= $row00["event_date"]>0?date("Y-m-d",$row00["event_date"]):'';
		$hObj["event_md"] 		= $row00["event_date"]>0?date("M-j",$row00["event_date"]):'';
		$hObj["checkin"] 		= $row00["checkin"];
		$total_checkin			+= $row00["checkin"];
		$head[$cnt] = $hObj;
		$cnt++;
	}
	$response["data"]["others"] = $head;
	//end of header stuff	

	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold; white-space:nowrap;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	$css_one = 'align="center" style="background-color:#F2E8F9; white-space:nowrap;"';
	$css_two = 'align="center" style="background-color:#E3F0FD; white-space:nowrap;"';


	$tmp_html = '<tr>';
	$hcnt = 0;
	foreach($head as $css_cnt=>$val) {
		$css = ($css_cnt%2)==0?$css_one:$css_two;
		for($i=1; $i<=$val["checkin"]; $i++) {
			$hcnt++;
			$tmp_html .= '<td '. $css . ' width="20">' . $i . '</td>';
		}
	}
	$tmp_html .= '</tr>';




	$html = '<table border="1" cellpadding="2" style="font-size:12px;">';
	$html.= '<tr>';
	$html.= '<td colspan="' . (14 + $hcnt) . '" align="center" height="40" style="font-size:12px; border:0px; font-weight:bold;">' . cTYPE::gstr($row0["title"]) . '<br>[ ' . date("M d, Y", $row0["start_date"]) . ($row0["start_date"]>0?' ~ ' .date("M d, Y", $row0["end_date"]):'') .  ' ]</td>';
	$html.= '</tr>';


	$html .= '<tr>';
	$html .= '<td colspan="' . (14 + $hcnt) . '" align="center"><span style="font-size:12px; font-weight:bold;">' . $words["event attendance"] . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td rowspan="2" width="40" ' . $header_css . '>SN</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Group</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Full Name</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Dharma Name</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Shoes Rack</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>New People</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Trial</td>';
	//$html .= '<td rowspan="2" ' . $header_css . '>' . $words["unauth"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Sign</td>';

	foreach($head as $css_cnt=>$val) {
		$css = ($css_cnt%2)==0?$css_one:$css_two;
		$html .= '<td '. $css . ' colspan="' . $val["checkin"] . '" width="60">Day ' . $val["day_no"] .  '<br>' . $val["event_md"] . '</td>';
	}

	$html .= '<td rowspan="2" ' . $header_css . '>Total</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Total Attend</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Total Leave</td>';


	$html .= '<td rowspan="2" ' . $header_css . '>Att.Rate</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Graduate</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>Certification</td>';
	$html .= '</tr>';

	$html .= $tmp_html;



	$query0 = "SELECT   a.id as enroll_id, a.event_id, a.group_no, a.shelf, a.trial, a.unauth, a.online, a.signin, a.graduate, a.new_flag, a.cert, attend,
						b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
					WHERE a.deleted <> 1 AND 
						  b.deleted <> 1 
						  $criteria 
						  $order_str";

	
	$result0 = $db->query($query0);
	$cnt0=0;
	$evtArr = array();
		
	while($row0 = $db->fetch($result0)) {
		$cnt0++;

		$html .= '<tr>';
		$html .= '<td width="40" align="center">';
		$html .=  $cnt0;
		$html .= '</td>';

		$html .= '<td align="center"><b>';
		$html .=  $row0["group_no"]>0?$row0["group_no"]:''; 
		$html .= '</b></td>';

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$html .= '<td>';
		$html .=  cTYPE::gstr(cTYPE::lfname($names));
		$html .= '</td>';

		$names						= array();
		$names["dharma_name"] 		= $row0["dharma_name"];
		$html .= '<td>';
		$html .=  cTYPE::gstr(cTYPE::cname($names));
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $row0["shelf"];
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $row0["new_flag"]?'Y':'';
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $row0["trial"]?'Y':'';
		$html .= '</td>';
        /*
		$html .= '<td align="center">';
		$html .= $row0["unauth"]?'Y':'';
		$html .= '</td>';
        */
		$html .= '<td align="center">';
		$html .= $row0["signin"]?'Y':'';
		$html .= '</td>';

		foreach($head as $css_cnt=>$val) {
			
			$css = ($css_cnt%2)==0?$css_one:$css_two;
			for($i=1; $i<=$val["checkin"]; $i++) {

				$query1 	= "SELECT    b.enroll_id, b.status 
									FROM event_calendar_enroll a 
									INNER JOIN puti_members d ON (a.member_id = d.id) 
									INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                                    INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
									WHERE 	a.deleted <> 1 AND 
											d.deleted <> 1 AND 
											a.event_id = '" . $eid . "' AND 
											a.id = '" . $row0["enroll_id"] . "' AND
											sn = '" . $i . "' AND
											event_date_id = '" . $val["event_date_id"] . "'";
				$result1	= $db->query($query1);
				$row1	= $db->fetch($result1);

                $html_status = ''; 
                if($row1["status"]=="2") $html_status = '<span style="color:blue;">Y</span>';
                if($row1["status"]=="4") $html_status = '<span style="color:red;">*</span>';
                if($row1["status"]=="8") $html_status = '<span style="color:red;">M</span>';

				$html .= '<td ' . $css . ' width="20">';
				$html .= $html_status;
				$html .= '</td>';
			}
		}

		$html .= '<td align="center">';
		$html .= $row0["attend"]>0?$total_checkin:"";
		$html .= '</td>';

		$querya 	= "SELECT   SUM(IF( b.status=2 OR b.status=8, 1, 0)) as total_attend,
								SUM(IF( b.status=4, 1 , 0)) as total_leave 
							FROM event_calendar_enroll a 
							INNER JOIN puti_members d ON (a.member_id = d.id) 
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                            INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
							WHERE a.deleted <> 1 AND d.deleted <> 1 AND a.event_id = '" . $eid . "' AND a.id = '" . $row0["enroll_id"] . "'";
		$resulta	= $db->query($querya);
		$rowa       = $db->fetch($resulta);

		$html .= '<td align="center">';
		$html .= $rowa["total_attend"]?$rowa["total_attend"]:"";
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $rowa["total_leave"]?$rowa["total_leave"]:"";
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $row0["attend"]>0?round($row0["attend"]*100)."%":'';
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $row0["graduate"]?'Y':'';
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $row0["cert"]?'Y':'';
		$html .= '</td>';
		$html .= '</tr>';
	}

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
