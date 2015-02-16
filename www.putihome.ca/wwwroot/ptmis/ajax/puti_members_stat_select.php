<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	// condition here 
	$criteria = "";
	$criteria .= "site in " . $admin_user["sites"];

	$con = $_REQUEST; 

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

	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_status = trim($con["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "status = '" . $sch_status . "'";
	}

	$sch_online = trim($con["sch_online"]);
	if($sch_online != "") {
		$criteria .= ($criteria==""?"":" AND ") . "online = '" . $sch_online . "'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

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
$html .= '<td colspan="2" ' . $width_one . ' ' . $header_css . '><b>' . $words["all members statistics"] . '</b></td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td valign="top" style="padding:5px;">';
	
	// age range 
	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	$ages[0] = $words["unknown"];
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}

	$query	 = "SELECT 	IFNULL(c.age, 0) as age,   
						COUNT( IFNULL(c.age,0) ) as age_total
						FROM puti_members c  
						WHERE  c.deleted <> 1 $criteria  
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
		$hears[$row_hear["id"]] = $row_hear["title"];
	}


	$html .= '<br>';
	$query	 = "SELECT 	hearfrom_id, COUNT(hearfrom_id) as hearfrom_count
						FROM puti_members_hearfrom b   
						INNER JOIN puti_members c ON (b.member_id = c.id) 
            			WHERE c.deleted <> 1 $criteria 
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

$html .= '</td>';
$html .= '<td valign="top" style="padding:5px;">';

	//  enroll, male , female , online
	$query	 = "SELECT 	count(b.id) as total,  
						sum(if(b.gender='Male',1,0)) as male_total, 
						sum(if(b.gender='Female' || b.gender='',1,0)) as female_total,
						sum(b.online) as online_total
						FROM puti_members b  
            			WHERE b.deleted <> 1 $criteria";

	$result = $db->query( $query );

	$html .= '<table border="0" cellpadding="2" cellspacing="0" style="font-size:14px; border-collapse:collapse;">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '></td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["total"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["male"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["female"] . '</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>' . $words["web"] . '</td>';
	$html .= '</tr>';


	while( $row = $db->fetch($result)) {
		$html .= '<tr>';

		$html .= '<td ' . $body_css1 . '>';
		$html .= $words["student number"];
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
						FROM puti_members_symptom b   
						INNER JOIN puti_members c ON (b.member_id = c.id) 
            			WHERE  c.deleted <> 1 $criteria 
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
