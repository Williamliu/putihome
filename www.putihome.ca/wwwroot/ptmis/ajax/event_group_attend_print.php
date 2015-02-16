<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=Event_Group_Attend_Report.xls");
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);


	// header stuff	
	$eid = $_REQUEST["event_id"];
	$query00 = "SELECT  event_id, id as event_date_id, yy, mm, dd, event_date, checkin, day_no 
						FROM event_calendar_date
						WHERE event_id = '" . $eid . "' 
						ORDER BY day_no";
	$result00 = $db->query($query00);
	$head = array();
	$cnt = 0;
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
		$head[$hObj["day_no"]] = $hObj;
		$cnt++;
	}
	$response["data"]["others"] = $head;
	//end of header stuff	

	$query_base = "SELECT 	a.event_id, a.group_no, COUNT(a.id) as enroll,
							SUM(trial) as trial,  SUM(unauth) as unauth, SUM(signin) as signin, SUM(a.online) as online, SUM(a.new_flag) as new_flag,  
							SUM(graduate) as graduate, SUM(cert) as cert, AVG( IF(attend<=0,null, attend) ) as attend 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
					WHERE a.deleted <> 1 AND
					      b.deleted <> 1 AND  
						  a.event_id = '" . $eid . "'
						  GROUP BY a.event_id, a.group_no
						  ORDER BY a.event_id, a.group_no";

	//echo "query:" . $query_base;
	//exit;

	$query0	= $query_base;
	$result0 = $db->query($query0);
	$cnt0=0;
	$evtArr = array();
		
	while($row0 = $db->fetch($result0)) {
		$eObj = array();
		$eObj["event_id"] 	= $row0["event_id"];
		$group_no 			= $row0["group_no"];

		$eObj["group_no"] 	= $row0["group_no"]>0?$row0["group_no"]:'';

		$eObj["enroll"]		= $row0["enroll"]>0?$row0["enroll"]:'';
		$eObj["trial"]		= $row0["trial"]>0?$row0["trial"]:'';
		$eObj["unauth"]		= $row0["unauth"]>0?$row0["unauth"]:'';
		$eObj["new_flag"]	= $row0["new_flag"]>0?$row0["new_flag"]:'';
		
		$eObj["online"]		= $row0["online"]>0?$row0["online"]:'';
		$eObj["attend"]		= $row0["attend"]>0?round($row0["attend"]*100,0)."%":'';
		$eObj["signin"]		= $row0["signin"]>0?$row0["signin"]:'';
		$eObj["graduate"]	= $row0["graduate"]>0?$row0["graduate"]:'';
		$eObj["cert"]		= $row0["cert"]>0?$row0["cert"]:'';

		
		$query1 	= "SELECT  d.day_no, COUNT(distinct c.enroll_id) as people   
							FROM event_calendar_enroll a 
							INNER JOIN puti_members b ON (a.member_id = b.id )
							INNER JOIN  event_calendar_attend c ON (a.id = c.enroll_id)
							INNER JOIN  event_calendar_date d ON (c.event_date_id = d.id AND c.sn <= d.checkin)
							WHERE a.deleted <> 1 AND 
							      b.deleted <> 1 AND 
								 (c.status = 2 OR c.status = 8) AND 
								  a.event_id = '" . $eid . "' AND
								  a.group_no = '" . $group_no . "'
							GROUP BY d.day_no
							ORDER BY d.day_no";

		$result1	= $db->query($query1);
		$eObj["dates"] = array();
		$cnt1=0;
		while($row1	= $db->fetch($result1)) {
			$aObj = array();
			$aObj["day_no"] 		= $row1["day_no"];
			$day_no					= $row1["day_no"];
			$aObj["people"] 		= $row1["people"]>0?$row1["people"]:'';
			
			$query2 	= "SELECT  c.sn, COUNT(distinct c.enroll_id) as people  
								FROM event_calendar_enroll a 
								INNER JOIN puti_members b ON (a.member_id = b.id )
								INNER JOIN  event_calendar_attend c ON (a.id = c.enroll_id)
    							INNER JOIN  event_calendar_date d ON (c.event_date_id = d.id AND c.sn <= d.checkin)
								WHERE a.deleted <> 1 AND
								      b.deleted <> 1 AND  
									 (c.status = 2 OR c.status = 8) AND 
									  a.event_id = '" . $eid . "' AND
									  a.group_no = '" . $group_no . "' AND
									  d.day_no = '" . $day_no . "' 
								GROUP BY c.sn
								ORDER BY c.sn";

			$result2 = $db->query($query2);
			$checkin = array();
			while($row2	= $db->fetch($result2)) {
				$bObj = array();
				$bObj["sn"] 				= $row2["sn"];
				$bObj["people"] 			= $row2["people"]>0?$row2["people"]:'';
				$aObj["sn"][$bObj["sn"]] 	= $bObj; 
			}
			$eObj["dates"][$aObj["day_no"]] = $aObj;
			$cnt1++;
		}
		$evtArr[$cnt0] = $eObj;
		$cnt0++;
	}


	  //  grand total 
	  $grand = array();

	  $query_base = "SELECT   a.event_id, COUNT(a.id) as enroll, 
							  SUM(trial) as trial,  SUM(unauth) as unauth, SUM(signin) as signin, SUM(a.online) as online,  SUM(a.new_flag) as new_flag, 
							  SUM(graduate) as graduate, SUM(cert) as cert, AVG( IF(attend<=0,null, attend) ) as attend 
						  FROM event_calendar_enroll a 
						  INNER JOIN puti_members b ON (a.member_id = b.id) 
					  WHERE a.deleted <> 1 AND 
					        b.deleted <> 1 AND 
							a.event_id = '" . $eid . "'
							GROUP BY a.event_id
							ORDER BY a.event_id";

	  $query0	= $query_base;
	  $result0 = $db->query($query0);
	  $row0 = $db->fetch($result0);

	  $grand["event_id"] 	= $row0["event_id"];
	  $grand["enroll"]		= $row0["enroll"]>0?$row0["enroll"]:'';
	  $grand["trial"]		= $row0["trial"]>0?$row0["trial"]:'';
	  $grand["unauth"]		= $row0["unauth"]>0?$row0["unauth"]:'';
	  $grand["new_flag"]	= $row0["new_flag"]>0?$row0["new_flag"]:'';
	  $grand["online"]		= $row0["online"]>0?$row0["online"]:'';
	  $grand["attend"]		= $row0["attend"]>0?round($row0["attend"]*100,0)."%":'';
	  $grand["signin"]		= $row0["signin"]>0?$row0["signin"]:'';
	  $grand["graduate"]	= $row0["graduate"]>0?$row0["graduate"]:'';
	  $grand["cert"]		= $row0["cert"]>0?$row0["cert"]:'';


      $grand["dates"] = array();
	  $query1 	= "SELECT  d.day_no, COUNT(distinct c.enroll_id) as people  
						  FROM event_calendar_enroll a 
						  INNER JOIN puti_members b ON (a.member_id = b.id )
						  INNER JOIN  event_calendar_attend c ON (a.id = c.enroll_id)
						  INNER JOIN  event_calendar_date d ON (c.event_date_id = d.id AND c.sn <= d.checkin)
						  WHERE a.deleted <> 1 AND 
						        b.deleted <> 1 AND 
								 (c.status = 2 OR c.status = 8) AND 
								a.event_id = '" . $eid . "' 
						  GROUP BY d.day_no
						  ORDER BY d.day_no";

	  $result1	= $db->query($query1);
	  $cnt1=0;
	  while($row1	= $db->fetch($result1)) {
		  $aObj = array();
		  $aObj["day_no"] 		= $row1["day_no"];
		  $day_no				= $row1["day_no"];
		  $aObj["people"] 		= $row1["people"]>0?$row1["people"]:'';
		  
		  $query2 	= "SELECT  c.sn, COUNT(distinct c.enroll_id) as people  
							  FROM event_calendar_enroll a 
							  INNER JOIN puti_members b ON (a.member_id = b.id )
							  INNER JOIN  event_calendar_attend c ON (a.id = c.enroll_id)
    						  INNER JOIN  event_calendar_date d ON (c.event_date_id = d.id AND c.sn <= d.checkin)
							  WHERE a.deleted <> 1 AND 
							        b.deleted <> 1 AND 
									 (c.status = 2 OR c.status = 8) AND 
									a.event_id = '" . $eid . "' AND
									d.day_no = '" . $day_no . "' 
							  GROUP BY c.sn
							  ORDER BY c.sn";

		  $result2 = $db->query($query2);
		  $checkin = array();
		  while($row2	= $db->fetch($result2)) {
			  $bObj = array();
			  $bObj["sn"] 				= $row2["sn"];
			  $bObj["people"] 			= $row2["people"]>0?$row2["people"]:'';
			  $bObj["prate"] 			= round( ( intval($bObj["people"])/intval($grand["enroll"]) ) * 100, 0 ) . "%";
			  $aObj["sn"][$bObj["sn"]] 	= $bObj; 
		  }
		  $grand["dates"][$aObj["day_no"]] = $aObj;
	  }
	  // end of grand total 



		
	// create excel table
	$query0 	= "SELECT title, start_date, end_date FROM event_calendar WHERE deleted <> 1 AND id = '" . $eid . "'";
	$result0 	= $db->query($query0);
	$row0 		= $db->fetch($result0);
	$title 		= cTYPE::gstr($row0["title"]) . '<br>[ ' . date("M d, Y", $row0["start_date"]) . ($row0["start_date"]>0?' ~ ' .date("M d, Y", $row0["end_date"]):'') .  ' ]';

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

	$html = '<table border="1" cellpadding="2" style="font-size:14px;">';
	$html.= '<tr>';
	$html.= '<td colspan="' . (10 + $hcnt) . '" align="center" height="40" style="font-size:12px; border:0px; font-weight:bold;">' . $title . '</td>';
	$html.= '</tr>';


	$html .= '<tr>';
	$html .= '<td colspan="' . (10 + $hcnt) . '" align="center"><span style="font-size:12px; font-weight:bold;">' . $words["event attendance"] . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td rowspan="2" width="40" ' . $header_css . '>' . $words["sn"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["group"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["enroll"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["web"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["trial"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["a.sign"] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["new people"] . '</td>';

	foreach($head as $css_cnt=>$val) {
		$css = ($css_cnt%2)==0?$css_one:$css_two;
		$html .= '<td '. $css . ' colspan="' . $val["checkin"] . '" width="60">' .  $words["day"] . ' ' . $val["day_no"] . ' ' . $words["day1"] . '<br>' . $val["event_md"] . '</td>';
	}

	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["attd."] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["grad."] . '</td>';
	$html .= '<td rowspan="2" ' . $header_css . '>' . $words["cert."] . '</td>';
	$html .= '</tr>';

	$html .= $tmp_html;

	foreach( $evtArr as $key=>$evtObj ) {
		$html .= '<tr>';
		$html .= '<td width="40" align="center">';
		$html .=  intval($key) + 1 ;
		$html .= '</td>';

		$html .= '<td align="center"><b>';
		$html .=  $evtObj["group_no"]!=""?$words["none.di"] . ' ' . $evtObj["group_no"] . ' ' . $words["none.zu"]:'&nbsp;';
		$html .= '</b></td>';


		$html .= '<td align="right">';
		$html .= $evtObj["enroll"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $evtObj["online"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $evtObj["trial"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $evtObj["signin"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $evtObj["new_flag"];
		$html .= '</td>';


		foreach($head as $css_cnt=>$val) {
			$css = ($css_cnt%2)==0?$css_one:$css_two;
			for($i=1; $i<=$val["checkin"]; $i++) {
				$html .= '<td ' . $css . ' width="20">';
				if( $evtObj["dates"][$css_cnt]["sn"][$i] != "" ) 
					$html .= $evtObj["dates"][$css_cnt]["sn"][$i]["people"];
				else 
					$html .= '&nbsp;';
				
				$html .= '</td>';
			}
		}
		
		$html .= '<td align="right">';
		$html .= $evtObj["attend"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $evtObj["graduate"];
		$html .= '</td>';

		$html .= '<td align="right">';
		$html .= $evtObj["cert"];
		$html .= '</td>';
	}

	// grand total
	$html .= '<tr>';

	$html .= '<td colspan="2" align="right"><b>';
	$html .=  $words["grand total"];
	$html .= '</b></td>';


	$html .= '<td align="right">';
	$html .= $grand["enroll"];
	$html .= '</td>';

	$html .= '<td align="right">';
	$html .= $grand["online"];
	$html .= '</td>';

	$html .= '<td align="right">';
	$html .= $grand["trial"];
	$html .= '</td>';
    
	$html .= '<td align="right">';
	$html .= $grand["signin"];
	$html .= '</td>';

	$html .= '<td align="right">';
	$html .= $grand["new_flag"];
	$html .= '</td>';


	foreach($head as $css_cnt=>$val) {
		$css = ($css_cnt%2)==0?$css_one:$css_two;
		for($i=1; $i<=$val["checkin"]; $i++) {
			$html .= '<td ' . $css . ' width="20">';
			if( $grand["dates"][$css_cnt]["sn"][$i] != "" ) 
				$html .= $grand["dates"][$css_cnt]["sn"][$i]["people"];
			else 
				$html .= '&nbsp;';
			
			$html .= '</td>';
		}
	}
	
	$html .= '<td align="right">';
	$html .= $grand["attend"];
	$html .= '</td>';

	$html .= '<td align="right">';
	$html .= $grand["graduate"];
	$html .= '</td>';

	$html .= '<td align="right">';
	$html .= $grand["cert"];
	$html .= '</td>';

	$html .= '</tr>';
	// end of grand


	// people attend rate
	$html .= '<tr>';

	$html .= '<td colspan="2" align="right"><b>';
	$html .=  $words["people attend rate"];
	$html .= '</b></td>';


	$html .= '<td align="right">';
	$html .= $grand["enroll"];
	$html .= '</td>';

	$html .= '<td align="right">';
	//$html .= $grand["online"];
	$html .= '</td>';

	$html .= '<td align="right">';
	//$html .= $grand["trial"];
	$html .= '</td>';

    /*
	$html .= '<td align="right">';
	//$html .= $grand["unauth"];
	$html .= '</td>';
	*/

	$html .= '<td align="right">';
	//$html .= $grand["signin"];
	$html .= '</td>';

	$html .= '<td align="right">';
	//$html .= $grand["signin"];
	$html .= '</td>';


	foreach($head as $css_cnt=>$val) {
		$css = ($css_cnt%2)==0?$css_one:$css_two;
		for($i=1; $i<=$val["checkin"]; $i++) {
			$html .= '<td ' . $css . ' width="20">';
			if( $grand["dates"][$css_cnt]["sn"][$i] != "" ) 
				$html .= $grand["dates"][$css_cnt]["sn"][$i]["prate"];
			else 
				$html .= '&nbsp;';
			
			$html .= '</td>';
		}
	}
	
	$html .= '<td align="right">';
	//$html .= $grand["attend"];
	$html .= '</td>';

	$html .= '<td align="right">';
	//$html .= $grand["graduate"];
	$html .= '</td>';

	$html .= '<td align="right">';
	//$html .= $grand["cert"];
	$html .= '</td>';

	$html .= '</tr>';
	// end of grand
	
	
	
	
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
