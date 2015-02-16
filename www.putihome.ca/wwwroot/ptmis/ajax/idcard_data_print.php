<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=idcard_data_report.xls");
	
	$type0["sch_site"] = '{"type":"NUMBER", "length":0, "id": "sch_site", "name":"Select Bodhi Center", "nullable":0}';
	$type0["sch_sdate"] = '{"type":"DATE", "length":0, "id": "sch_sdate", "name":"Select Start Date", "nullable":0}';
	cTYPE::validate($type0, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);


	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "ORDER BY a.created_time DESC";
	}

	
	// condition here 
	$criteria = "";

	$con = $_REQUEST; 

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.site = '" . cTYPE::trans($sch_site) . "'";
	}

	$sch_place = trim($con["sch_place"]);
	if($sch_place != "") {
		$criteria .= ($criteria==""?"":" AND ") . "place = '" . $sch_place . "'";
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

	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(b.phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(b.cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "b.city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "b.email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "b.gender = '" . $sch_gender . "'";
	}

	$sch_sdate = trim($con["sch_sdate"]);
	$sch_edate = trim($con["sch_edate"]);
	$sch_shh = str_pad(trim($con["sch_shh"]), 2, "0", STR_PAD_LEFT);
	$sch_smm = str_pad(trim($con["sch_smm"]), 2, "0", STR_PAD_LEFT);
	$sch_ehh = str_pad(trim($con["sch_ehh"]), 2, "0", STR_PAD_LEFT);
	$sch_emm = str_pad(trim($con["sch_emm"]), 2, "0", STR_PAD_LEFT);
	if( $sch_edate == "") $sch_edate = $sch_sdate;
	
	$start_time = cTYPE::datetoint($sch_sdate . " " . $sch_shh . ":" . $sch_smm . ":00");
	$end_time 	= cTYPE::datetoint($sch_edate . " " . $sch_ehh . ":" . $sch_emm . ":00");
	//$toff = date("Z");
	//$start_time += $toff;
	//$end_time += $toff;
	
	$criteria .= ($criteria==""?"":" AND ") . "a.created_time BETWEEN '" . $start_time  . "' AND '" . $end_time . "'";
	

	// important,   if  scan ID Card,  search in whole list without site reserict  
	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "member_id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "member_id = '-1'";
		}
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	//echo "critiea:" . $criteria;
	// end of criteria
	
	$query_base = "SELECT a.id, a.member_id, a.site, a.place, c.title as site_desc, d.title as place_desc, a.idd, a.created_time, 
						  b.first_name, b.last_name, b.dharma_name, b.alias, b.legal_first, b.legal_last, b.phone, b.cell, b.email, b.city, b.gender, e.title as member_site 
						FROM puti_device_record a
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						INNER JOIN puti_sites c ON (a.site = c.id)
						INNER JOIN puti_places d ON (a.place = d.id)
						INNER JOIN puti_sites e ON ( b.site = e.id) 
						WHERE  b.deleted <> 1  
						$criteria 
						$order_str";
	
	
	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(*) AS CNT, COUNT(distinct member_id) as MEMBER_CNT FROM ( " . $query_base . " ) res1");
	$row_total = $db->fetch($result_num);
	$recoTotal =  $row_total["CNT"];
	$membTotal =  $row_total["MEMBER_CNT"];
	$pageTotal = ceil($recoTotal/$pageSize);

	//$site_desc 	= 	strtolower($db->getVal("puti_sites", "title", $_REQUEST["site"] ) );
	//$place_desc = 	strtolower($db->getVal("puti_places", "title", $_REQUEST["place"]) );

	$site_desc 	= 	$_REQUEST["sch_site"]!=""?$db->getVal("puti_sites", "title", $_REQUEST["sch_site"] ):"";
	$place_desc = 	$_REQUEST["sch_place"]!=""?$db->getVal("puti_places", "title", $_REQUEST["sch_place"]):"";


	$site_desc 	= "Bodhi Center" . " : " . $site_desc;
	$place_desc = "Place" . " : " . $place_desc;
	$date_range = "Date Range" . " : " . $sch_sdate . " ~ " . $sch_edate;
	$time_range = "Time Range" . " : " . ( $sch_shh . ":" . $sch_smm . ":00" ) . " ~ " . ( $sch_ehh . ":" . $sch_emm . ":00" );
	
	$all_title = $site_desc . 
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $place_desc .
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $date_range .
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $time_range;


	$count_msg = $words["kaoqin.cishu"] . " : " . $recoTotal . 
				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . 
				$words["kaoqin.renshu"] . " : " . $membTotal;		
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html.= '<tr valign="middle">';
	$html.= '<td colspan="14" align="center" valign="middle" height="25" style="font-size:12px; border:0px; font-weight:bold;">' . $all_title .  '</td>';
	$html.= '</tr>';

	$html.= '<tr valign="middle">';
	$html.= '<td colspan="14" align="center" valign="middle" height="25" style="font-size:12px; border:0px; font-weight:bold;">ID Card Report</td>';
	$html.= '</tr>';

	$html.= '<tr valign="middle">';
	$html.= '<td colspan="14" align="center" valign="middle" height="25" style="font-size:14px;color:#CB393A;font-weight:bold;">' . $count_msg . '</td>';
	$html.= '</tr>';

	$html.= '<tr valign="middle">';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>DateTime</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>ID Number</td>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>Site Reader</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Place</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Legal Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Dharma</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Gender</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Phone</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>City</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Email</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>St.Site</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Photo</td>';
	$html.= '</tr>';

						

	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	$gpno = array();
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["member_id"] 	= $row["member_id"];
		
		$rows[$cnt]["site"] 		= $row["site"];
		$rows[$cnt]["site_desc"] 	= cTYPE::gstr($words[strtolower($row["site_desc"])]);

		$rows[$cnt]["place"] 		= $row["place"];
		$rows[$cnt]["place_desc"] 	= cTYPE::gstr($words[strtolower($row["place_desc"])]);

		$rows[$cnt]["idd"] 			= $row["idd"];
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d H:i:s",$row["created_time"]):"";

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["name"] 		= cTYPE::gstr(cTYPE::cname($names));

		$names						= array();
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];
		$rows[$cnt]["legal_name"] 	= cTYPE::gstr(cTYPE::cname($names));
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"]?cTYPE::gstr($row["dharma_name"]):'';

		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["gender"] 		= $row["gender"];

		$rows[$cnt]["member_site"] 	= cTYPE::gstr($words[strtolower($row["member_site"])]);

		
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["member_id"] . ".jpg")?"Y":"";


		$html.= '<tr height="25" valign="middle">';
		$html.= '<td ' . $width_one . ' align="center">' . ($cnt+1) . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["created_time"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["idd"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["site_desc"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["place_desc"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["legal_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["gender"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["city"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["email"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["member_site"] . '</td>';
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
