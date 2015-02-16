<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=Event_Certificate_Student_List.xls");

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "ORDER BY c.id ASC";
	}

	// condition here 
	$criteria = "";

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

	$sch_group = trim($con["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . $sch_group . "'";
	}


	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_date = trim($con["sch_date"]);
	if($sch_date != "") {
		$sd = cTYPE::datetoint($sch_date);
		$ed = $sd + ( 3600 * 24 - 1);
		$criteria .= ($criteria==""?"":" AND ") . "c.created_time BETWEEN '" . $sd . "' AND '" . $ed . "'";
	}
	// important,   if  scan ID Card,  search in whole list without site restrict  
	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "a.id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "a.id = '-1'";
		}
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria
	$query0 	= "SELECT title, start_date, end_date FROM event_calendar WHERE deleted <> 1 AND id = '" . $con["event_id"] . "'";
	$result0 	= $db->query($query0);
	$row0 		= $db->fetch($result0);

	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html.= '<tr>';
	$html.= '<td colspan="12" align="center" height="40" style="font-size:12px; border:0px; font-weight:bold;">' . cTYPE::gstr($row0["title"]) . '<br>[ ' . date("M d, Y", $row0["start_date"]) . ($row0["start_date"]>0?' ~ ' .date("M d, Y", $row0["end_date"]):'') .  ' ]</td>';
	$html.= '</tr>';

	$html.= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>Group</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Legal Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Dharma</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Gender</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Email</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Phone</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Cell</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>City</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Site</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Photo</td>';
	$html.= '</tr>';

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}
	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	$query_base = "SELECT a.*,c.group_no, c.cert_no  
						FROM puti_members a
						LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
						INNER JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1) c ON ( a.id = c.member_id ) 
						WHERE  a.deleted <> 1  
						$criteria 
						$order_str";
	
	
	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	$gpno = array();
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$rows[$cnt]["name"] 		= cTYPE::gstr(cTYPE::lfname($names));

		$rows[$cnt]["legal_name"] 	= $row["legal_last"] . ($row["legal_last"]!=""?", ":"") . $row["legal_first"];
		
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"]?$row["dharma_name"]:'';
		$rows[$cnt]["sex"] 			= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		$rows[$cnt]["phone1"] 		= $row["phone"];
		$rows[$cnt]["cell1"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= $sites[$row["site"]];
		$rows[$cnt]["postal"] 		= $row["postal"];
		$rows[$cnt]["group_no"] 	= $row["group_no"]?$row["group_no"]:"";
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";

		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";


		$html.= '<tr height="25">';
		$html.= '<td ' . $width_one . ' align="center">' . ($cnt+1) . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["group_no"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["legal_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["sex"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["email"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["phone1"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["cell1"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["city"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["site"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["photo"] . '</td>';
		$html.= '</tr>';

		$cnt++;	
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
