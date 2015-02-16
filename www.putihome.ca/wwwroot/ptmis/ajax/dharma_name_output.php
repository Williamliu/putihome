<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=Dharma_Name_List.xls");

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
							temp_dharma_name like '%" . cTYPE::trans_trim($sch_name) . "%' OR 
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
		$criteria .= ($criteria==""?"":" AND ") . "apply_date BETWEEN '" . $sd . "' AND '" . $ed . "'";
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
	

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}
	
	$result_lang = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 ORDER BY sn DESC");
	$langs =array();
	while($row_lang = $db->fetch($result_lang)) {
		$langs[$row_lang["id"]] =  cTYPE::gstr($words[$row_lang["title"]]);
	}
	$langs[0] = "";
	
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
	$html.= '<td colspan="19" align="center" height="40" style="font-size:12px; border:0px; font-weight:bold;">' . cTYPE::gstr($row0["title"]) . '<br>[ ' . date("M d, Y", $row0["start_date"]) . ($row0["start_date"]>0?' ~ ' .date("M d, Y", $row0["end_date"]):'') .  ' ]</td>';
	$html.= '</tr>';

	$html.= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . cTYPE::gstr($words["sn"]) . '</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . cTYPE::gstr($words["group"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["dharma name"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["dharma pinyin"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["apply date"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["name"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["legal name"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["gender"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["short.lang"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["age"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["birth date"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member enter date"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["email"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["phone"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["cell"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["city"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["g.site"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["c.photo"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["memo notes"]) . '</td>';
	//$html.= '<td ' . $width_two . ' ' . $header_css . '>Photo</td>';
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
		$names["dharma_name"] 		= $row["dharma_name"];
		$rows[$cnt]["name"] 		= cTYPE::gstr(cTYPE::lfname($names));

		$rows[$cnt]["legal_name"] 	= $row["legal_last"] . ($row["legal_last"]!=""?", ":"") . $row["legal_first"];
		
		$rows[$cnt]["temp_dharma_name"] = $row["temp_dharma_name"]?$row["temp_dharma_name"]:'';
		$rows[$cnt]["temp_dharma_pinyin"] = $row["temp_dharma_pinyin"]?$row["temp_dharma_pinyin"]:'';
		$rows[$cnt]["sex"] 			= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
	
		$age_range 					= $row["age"]>=1?$ages[$row["age"]]:"";
		$birth_yy 					= $row["birth_yy"]>0? date("Y") - intval($row["birth_yy"]):"";
		$rows[$cnt]["age"] 			= $birth_yy>0?$birth_yy:$age_range;
		//$rows[$cnt]["age"] 			= $ages[$row["age"]];
		
		$rows[$cnt]["birth_date"] 	= cTYPE::toDate($row["birth_yy"],$row["birth_mm"],$row["birth_dd"]);
		$rows[$cnt]["member_date"] 	= cTYPE::toDate($row["member_yy"],$row["member_mm"],$row["member_dd"]);
		
		$rows[$cnt]["language"] 	= $row["language"]?$langs[$row["language"]]:"";

		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		$rows[$cnt]["phone1"] 		= $row["phone"];
		$rows[$cnt]["cell1"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= $sites[$row["site"]];
		$rows[$cnt]["postal"] 		= $row["postal"];
		$rows[$cnt]["group_no"] 	= $row["group_no"]?$row["group_no"]:"";
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";
		//$rows[$cnt]["photo_url"] 	= $rows[$cnt]["photo"]=="Y"?
									// '<img src="' . $CFG["http"] . $CFG["admin_domain"] . "/ajax/lwhUpload_image.php?size=tiny&img_id=" . $row["id"] . '" height="100" />'
									// :'';
		$rows[$cnt]["memo"] 		= cTYPE::gstr($row["memo"]);

		$rows[$cnt]["apply_date"]	= $row["apply_date"]>0?date("Y-m-d",$row["apply_date"]):"";
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";


		$html.= '<tr height="25" valign="middle">';
		$html.= '<td ' . $width_one . ' align="center" valign="middle">' . ($cnt+1) . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["group_no"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["temp_dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["temp_dharma_pinyin"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["apply_date"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["name"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["legal_name"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["sex"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["language"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["age"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["birth_date"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["member_date"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["email"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["phone1"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["cell1"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["city"] . '</td>';
		$html.= '<td ' . $width_two . ' valign="middle">' . $rows[$cnt]["site"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["photo"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center" valign="middle">' . $rows[$cnt]["memo"] . '</td>';
		//$html.= '<td ' . $width_two . ' align="center" valign="middle" height="100" width="auto">' . $rows[$cnt]["photo_url"] . '</td>';
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
