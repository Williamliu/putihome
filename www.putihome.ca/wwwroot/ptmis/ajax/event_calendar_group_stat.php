<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	//  event information
	$query0 = "SELECT title, start_date, end_date, status FROM event_calendar
					WHERE id = '" . $_REQUEST["event_id"] . "'";
	$result0 = $db->query($query0);
	$row0 = $db->fetch($result0);
	$evt["title"] 		= $row0["title"];
	$evt["start_date"] 	= $row0["start_date"]>0?date("Y-m-d", $row0["start_date"]):'';
	$evt["end_date"] 	= $row0["end_date"]>0?date("Y-m-d", $row0["end_date"]):'';
	$evt_start_date     = $row0["start_date"];
    // end of event information

	$header_css = 'align="center" 	style="background-color:#eeeeee; border-bottom:1px solid #999999; border-right:1px solid #999999;"';
	$body_css 	= 'align="right" 	style="background-color:#EDF8FA; border-bottom:1px solid #999999; border-right:1px solid #999999;"';
	$body_css1 	= 'align="left" 	style="background-color:#EDF8FA; border-bottom:1px solid #999999; border-right:1px solid #999999;"';
	//$header_css = 'align="center" style="background-color:#eeeeee;"';
	//$body_css 	= 'align="right" style="background-color:#EDF8FA;"';
	//$body_css1 	= 'align="left" style="background-color:#EDF8FA;"';
	$width_one 	= '';
	$width_two 	= '';


$html = '<center><table border="0" cellpadding="0" cellspacing="0">';
$html .= '<tr>';
$html .= '<td colspan="2" ' . $width_one . ' ' . $header_css . '><b>' . $evt["title"] . '</b><br>' 
		 . $evt["start_date"] . " ~ " . $evt["end_date"] .
		 '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td valign="top" style="padding:5px;">';
	
	// age range 
	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	$ages[0] = $words["unknown"];
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = cTYPE::gstr($row_age["title"]);
	}

	$query	 = "SELECT 	IFNULL(c.age, 0) as age,   
						COUNT( IFNULL(c.age,0) ) as age_total
						FROM puti_members c  
						INNER JOIN event_calendar_enroll b ON (b.member_id = c.id) 
            			WHERE  b.deleted <> 1 AND c.deleted <> 1 AND b.event_id = '" . $_REQUEST["event_id"] . "'
						GROUP BY IFNULL(c.age,0)";

	$result = $db->query( $query );

	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["age range"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["count"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["count"] . '</td>';
	$html .= '</tr>';
	
	$age_val = array();
	$age_grand_total = 0;
	while( $row = $db->fetch($result)) {
		$age_val[$row["age"]] = $row["age_total"];
		$age_grand_total += intval($row["age_total"]);
	}

	foreach( $ages as $key=>$age) {
		$html .= '<tr>';
		$html .= '<td ' . $body_css1 . '>';
		$html .= $age;
		$html .= '</td>';
		$html .= '<td ' . $body_css . '>';
		$html .=  $age_val[$key];
		$html .= '</td>';
		$html .= '<td ' . $body_css . '>';
		$html .=  $age_val[$key]/$age_grand_total>0?round($age_val[$key]/$age_grand_total * 100) . "%":"";
		$html .= '</td>';
		$html .= '</tr>';
	}
	$html .= '</table>';
	// end of range
	
	// hearfrom
	$result_hear = $db->query("SELECT * FROM puti_info_hearfrom order by id");
	$hears =array();
	while($row_hear = $db->fetch($result_hear)) {
		$hears[$row_hear["id"]] = cTYPE::gstr($row_hear["title"]);
	}


	$html .= '<br>';
	$query	 = "SELECT 	hearfrom_id, COUNT(hearfrom_id) as hearfrom_count
						FROM event_calendar_enroll a
						INNER JOIN puti_members_hearfrom b ON ( a.member_id = b.member_id )  
						INNER JOIN puti_members c ON (a.member_id = c.id) 
            			WHERE  a.deleted <> 1 AND c.deleted <> 1 AND a.event_id = '" . $_REQUEST["event_id"] . "'
						GROUP BY b.hearfrom_id ORDER BY b.hearfrom_id";

	$result = $db->query( $query );

	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["hear from"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["count"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["count"] . '</td>';
	$html .= '</tr>';
	
	$hearfrom_val = array();
	$hear_grand_total = 0;
	while( $row = $db->fetch($result)) {
		$hearfrom_val[$row["hearfrom_id"]] = $row["hearfrom_count"];
		$hear_grand_total += intval($row["hearfrom_count"]);
	}

	foreach( $hears as $key=>$hear) {
		$html .= '<tr>';
		$html .= '<td ' . $body_css1 . '>';
		$html .= cTYPE::gstr($words[strtolower($hear)]);
		$html .= '</td>';
		$html .= '<td ' . $body_css . '>';
		$html .= $hearfrom_val[$key];
		$html .= '</td>';
		$html .= '<td ' . $body_css . '>';
		$html .=  $hearfrom_val[$key]/$hear_grand_total>0?round($hearfrom_val[$key]/$hear_grand_total * 100) . "%":"";
		$html .= '</td>';
		$html .= '</tr>';
	}
	$html .= '</table>';
	// end of hear


	// attend times
	$attimes = array(); 
	$class_id = $db->getVal("event_calendar", "class_id", $_REQUEST["event_id"]);
	$query_mem = "SELECT distinct  a.member_id FROM event_calendar_enroll a 
									INNER JOIN puti_members b ON (a.member_id = b.id) 
									WHERE 	a.deleted <> 1 AND  b.deleted <> 1 AND 
											a.event_id = '" . $_REQUEST["event_id"] . "'";
	 
	$result_mem = $db->query($query_mem);
	$mem_times = array();
	while( $row_mem = $db->fetch($result_mem) ) {
		  $query_b1 = "SELECT IF(count(b.id)< 5,count(b.id), 5)  as attime 
						  	FROM  event_calendar_enroll a 
						  	INNER JOIN  event_calendar  b ON (  a.event_id = b.id ) 
						  	WHERE   b.start_date < '" . $evt_start_date . "' AND 
                                    ( a.graduate = 1 OR a.cert = 1 ) AND 
									b.deleted <> 1 AND a.deleted <> 1 AND 
									b.class_id = '" . $class_id . "' AND
								  	a.member_id = '" . $row_mem["member_id"] . "'";
		 $result_b1 = $db->query($query_b1);
		 $row_b1 = $db->fetch($result_b1);
		 $mem_times[$row_mem["member_id"]] = $row_b1["attime"]?$row_b1["attime"]:0;
		 //echo "here : ". $row_mem["member_id"] . ": " . $row_mem["attime"] . "\n";
	}
	$mem_grand_total = 0;
    foreach( $mem_times as $mem_time ) {
		$attimes[$mem_time]++;
		$mem_grand_total++;
	}
	ksort($attimes);

	$html .= '<br>';	
	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["participate"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["head count"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["head count"] . '</td>';
	$html .= '</tr>';

	foreach( $attimes as $key=>$attime) {
		$html .= '<tr>';
		$html .= '<td ' . $body_css1 . '>';
		if( $key >= 5 )  
			$html .= $key . '+';
		else 
			$html .= $key;
		$html .= '</td>';
		$html .= '<td ' . $body_css . '>';
		$html .= $attime;
		$html .= '</td>';
		$html .= '<td ' . $body_css . '>';
		$html .= $attime/$mem_grand_total>0?round($attime/$mem_grand_total * 100) . "%":"";
		$html .= '</td>';
		$html .= '</tr>';
	}
	$html .= '</table>';
	// end of attend times


	



$html .= '</td>';
$html .= '<td valign="top" style="padding:5px;">';

	//  enroll, male , female , online, trial
	$query	 = "SELECT 	count(a.id) as total,  
						sum(if(b.gender='Male',1,0)) as male_total, 
						sum(if(b.gender='Female' || b.gender='',1,0)) as female_total,
						sum(a.online) as online_total,
						sum(a.new_flag) as new_total,
						sum(a.leader) as leader_total,
						sum(a.volunteer) as vol_total,
						sum(a.trial) as trial_total,
						sum(if(c.transportation=40,1,0)) 	as i_drive,
						sum(if(c.offer_carpool=1,1,0)) 		as offer_carpool,
						sum(if(c.transportation=30,1,0)) 	as need_carpool
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						LEFT JOIN puti_members_others c ON (b.id = c.member_id) 
            			WHERE  a.deleted <> 1 AND b.deleted <> 1 AND a.event_id = '" . $_REQUEST["event_id"] . "'";

	$result = $db->query( $query );

	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '></td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["enroll"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["male"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["female"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["web"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["leader"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["tag.volunteer"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["new people"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["trial"] . '</td>';
	$html .= '</tr>';


	while( $row = $db->fetch($result)) {
		$html .= '<tr>';

		$html .= '<td ' . $body_css1 . '>';
		$html .= cTYPE::gstr($words["student number"]);
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["male_total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["female_total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["online_total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["leader_total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["vol_total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["new_total"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["trial_total"];
		$html .= '</td>';

		$html .= '</tr>';
	}
	$html .= '</table>';
	// end of enroll, male, female, enroll

	$html .= '<br>';


	//  enroll, male , female , online, trial
	$query	 = "SELECT 	count(a.id) as total,  
						sum(if(b.gender='Male',1,0)) as male_total, 
						sum(if(b.gender='Female' || b.gender='',1,0)) as female_total,
						sum(a.online) as online_total,
						sum(a.leader) as leader_total,
						sum(a.volunteer) as vol_total,
						sum(a.trial) as trial_total,
						sum(if(c.transportation=40,1,0)) 	as i_drive,
						sum(if(c.offer_carpool=1,1,0)) 		as offer_carpool,
						sum(if(c.transportation=30,1,0)) 	as need_carpool
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						LEFT JOIN puti_members_others c ON (b.id = c.member_id) 
            			WHERE  a.deleted <> 1 AND b.deleted <> 1 AND a.event_id = '" . $_REQUEST["event_id"] . "'";

	$result = $db->query( $query );

	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '></td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["i_drive"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["offer_carpool"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["need_carpool"] . '</td>';
	$html .= '</tr>';


	while( $row = $db->fetch($result)) {
		$html .= '<tr>';

		$html .= '<td ' . $body_css1 . '>';
		$html .= cTYPE::gstr($words["student number"]);
		$html .= '</td>';
		
		$html .= '<td ' . $body_css . '>';
		$html .=  $row["i_drive"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["offer_carpool"];
		$html .= '</td>';

		$html .= '<td ' . $body_css . '>';
		$html .=  $row["need_carpool"];
		$html .= '</td>';

		$html .= '</tr>';
	}
	$html .= '</table>';
	// end of enroll, male, female, enroll

	$html .= '<br>';


	// symptom
	$result_symptom = $db->query("SELECT * FROM puti_info_symptom order by id");
	$symptoms =array();
	while($row_symptom = $db->fetch($result_symptom)) {
		$symptoms[$row_symptom["id"]] = $row_symptom["title"];
	}

	$query	 = "SELECT 	symptom_id, COUNT(symptom_id) as symptom_count
						FROM event_calendar_enroll a
						INNER JOIN puti_members_symptom b ON ( a.member_id = b.member_id )  
						INNER JOIN puti_members c ON (a.member_id = c.id) 
            			WHERE  a.deleted <> 1 AND c.deleted <> 1 AND a.event_id = '" . $_REQUEST["event_id"] . "'
						GROUP BY b.symptom_id ORDER BY b.symptom_id";

	$result = $db->query( $query );

	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '  style="white-space:nowrap;">' . $words["ailment & symptom"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["count"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["count"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>';
	$html .= '<span style="color:#CD6868; font-weight:bold;">' . $words["other"] . '</span>';
	$html .= '</td>';
	$html .= '</tr>';
	
	$symptom_val = array();
	$symptom_grand_total = 0;
	while( $row = $db->fetch($result)) {
		$symptom_val[$row["symptom_id"]] = $row["symptom_count"];
		$symptom_grand_total += intval($row["symptom_count"]);
	}
	$rcnt = 0;
	foreach( $symptoms as $key=>$symptom) {
		$rcnt++;
		if($rcnt == 1) {
			$html .= '<tr>';
			$html .= '<td ' . $body_css1 . ' style="white-space:nowrap;">';
			$html .= cTYPE::gstr($words[strtolower($symptom)]);
			$html .= '</td>';
			$html .= '<td ' . $body_css . '>';
			$html .= $symptom_val[$key];
			$html .= '</td>';
			$html .= '<td ' . $body_css . '>';
			$html .= $symptom_val[$key]/$symptom_grand_total>0?round($symptom_val[$key]/$symptom_grand_total*100) . "%":"";
			$html .= '</td>';

			$html .= '<td valign="top" width="300" rowspan="' . count($symptoms) . '" ' . $body_css1 . '>';

			$query_other = "SELECT distinct b.other_symptom FROM 	event_calendar_enroll a  
									INNER JOIN puti_members c ON (a.member_id = c.id) 
									INNER JOIN 	puti_members_others b ON (a.member_id = b.member_id) 
									WHERE c.deleted <> 1 AND a.deleted<>1 AND a.event_id = '" . $_REQUEST["event_id"] . "'";
			$result_other = $db->query($query_other);
			$other_symptom = '';
			while($row_other = $db->fetch($result_other)) {
				$other_symptom .= ($other_symptom!=''?'; ':'') . $row_other["other_symptom"];
			}
			
			$html .= '<span style="color:blue; font-size:12px; font-weight:normal;">' . $other_symptom . '</span>';
			$html .= '</td>';
			
			$html .= '</tr>';
		} else {
			$html .= '<tr>';
			$html .= '<td ' . $body_css1 . ' style="white-space:nowrap;">';
			$html .= cTYPE::gstr($words[strtolower($symptom)]);
			$html .= '</td>';
			$html .= '<td ' . $body_css . '>';
			$html .= $symptom_val[$key];
			$html .= '</td>';
			$html .= '<td ' . $body_css . '>';
			$html .= $symptom_val[$key]/$symptom_grand_total>0?round($symptom_val[$key]/$symptom_grand_total*100) . "%":"";
			$html .= '</td>';
			$html .= '</tr>';
		}
	}
	
	/*
	$html .= '<tr>';
	$html .= '<td  rowspan="' . $rcnt . '" ' . $body_css1 . '>';
	$html .= '<span style="color:#CD6868; font-weight:bold;">' . $words["other"] . '</span>';
	$html .= "<br>";
	
	$query_other = "SELECT distinct b.other_symptom FROM 	event_calendar_enroll a  
							INNER JOIN 	puti_members_others b ON (a.member_id = b.member_id) 
							WHERE a.event_id = '" . $_REQUEST["event_id"] . "'";
	$result_other = $db->query($query_other);
	$other_symptom = '';
	while($row_other = $db->fetch($result_other)) {
		$other_symptom .= ($other_symptom!=''?'; ':'') . $row_other["other_symptom"];
	}
	
	$html .= '<span style="color:blue; font-size:12px; font-weight:normal;">' . $other_symptom . '</span>';
	$html .= '</td>';
	$html .= '</tr>';
	*/
	
	$html .= '</table>';
	// end of symptom


$html .= '</td>';
$html .= '</tr>';
$html .= '</table></center>';
	


	$response["data"]["html"] = $html;

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
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
