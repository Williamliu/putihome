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

	$result_lang = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
	$langs =array();
	while($row_lang = $db->fetch($result_lang)) {
		$langs[$row_lang["id"]] =  cTYPE::gstr($words[$row_lang["title"]]);
	}
	$langs[0] = "";


	$eid = $_REQUEST["event_id"];


	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $eid . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];



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
	$html.= '<td colspan="' . (22 + $hcnt) . '" align="center" height="40" style="font-size:12px; border:0px; font-weight:bold;">' . cTYPE::gstr($row0["title"]) . '<br>[ ' . date("M d, Y", $row0["start_date"]) . ($row0["start_date"]>0?' ~ ' .date("M d, Y", $row0["end_date"]):'') .  ' ]</td>';
	$html.= '</tr>';


	$html .= '<tr>';
	$html .= '<td colspan="' . (22 + $hcnt) . '" align="center"><span style="font-size:12px; font-weight:bold;">' . $words["event attendance"] . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td rowspan="2" width="40" ' . $header_css . '>' . $words["sn"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["group"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["name"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["dharma"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["gender"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["phone"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["email"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["short.lang"] . '</td>';

	
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["tag.title"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["new people"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["web"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["trial"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["a.sign"] . '</td>';

	foreach($head as $css_cnt=>$val) {
		$css = ($css_cnt%2)==0?$css_one:$css_two;
		$html .= '<td '. $css . ' colspan="' . $val["checkin"] . '" width="60">' .  $words["day"] . ' ' . $val["day_no"] . ' ' . $words["day1"] . '<br>' . $val["event_md"] . '</td>';
	}

	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["total checkin"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["total attend"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["total leave"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["attd."] . '</td>';

	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["grad."] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["cert."] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["cert_no"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["doc no"] . '</td>';

	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["shoes.shelf"] . '</td>';
	$html .= '</tr>';

	$html .= $tmp_html;



	$query_base = "SELECT   a.id as enroll_id, a.event_id, a.group_no, a.leader, a.volunteer, a.shelf, a.trial, a.new_flag,  
							a.unauth, a.online, a.signin, a.graduate, a.cert, attend, a.cert_no, a.doc_no, 
							b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.language, b.phone, b.cell, b.city 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
					WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND 
						  b.deleted <> 1 
				    ORDER BY a.group_no, a.leader DESC, a.volunteer DESC, b.last_name, b.first_name";



	$query0	= $query_base;
	
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

		$html .= '<td align="center">';
		$html .= cTYPE::gstr($words[strtolower($row0["gender"])]);
		$html .= '</td>';

		$html .= '<td align="left">';
		$html .=  $row0["phone"]?$row0["phone"]:$row0["cell"];
		$html .= '</td>';
		
		$html .= '<td align="left">';
		$html .= $row0["email"];
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $row0["language"]?$langs[$row0["language"]]:"";
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= $row0["leader"]?cTYPE::gstr($words["tag.leader"]):($row0["volunteer"]?cTYPE::gstr($words["tag.volunteer"]):"");
		$html .= '</td>';

 		$html .= '<td align="center">';
		$html .= $row0["new_flag"]?'Y':'';
		$html .= '</td>';
            


		$html .= '<td align="right">';
		$html .= $row0["online"]?"Y":"";
		$html .= '</td>';


		$html .= '<td align="center">';
		$html .= $row0["trial"]?'Y':'';
		$html .= '</td>';


		$html .= '<td align="center">';
		$html .= $row0["signin"]?'Y':'';
		$html .= '</td>';

		foreach($head as $css_cnt=>$val) {
			
			$css = ($css_cnt%2)==0?$css_one:$css_two;
			for($i=1; $i<=$val["checkin"]; $i++) {

				$query1 	= "SELECT    b.enroll_id, b.status 
									FROM event_calendar_enroll a 
									INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                                    INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
									WHERE 	a.deleted <> 1 AND 
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
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                            INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
							WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND a.id = '" . $row0["enroll_id"] . "'";
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

		$html .= '<td align="left">';
		$html .= $row0["cert_no"]?$row0["cert_no"]:"";
		$html .= '</td>';

		$html .= '<td align="left">';
		$html .= $row0["doc_no"]?$row0["doc_no"]:"";
		$html .= '</td>';

		$html .= '<td align="center">';
		$html .= cTYPE::shelfSN($row0["shelf"],$CFG["max_shoes_rack"]);
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
