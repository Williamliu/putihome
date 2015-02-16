<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=ID_Card_Holder_List.xls");


	$header_css = 'align="center" style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';

	$c1 = ' style="background-color:#FFF5D7;"';
	$c2 = ' style="background-color:#ffffff;"';
	
	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="9" align="center"><span style="font-size:14px; font-weight:bold;">ID Card Holder List</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Vol.</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Name</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Email</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Phone</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Cell</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>City</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Site</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>ID Card</td>';
	$html .= '</tr>';
	
	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}
	
	
	//*************** criteria ***********************************************/
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
		$criteria .= ($criteria==""?"":" AND ") . "(replace(replace(phone,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%' OR replace(replace(cell,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%')";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_status = trim($con["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.status like '%" . $sch_status . "%'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}

	
	$query0 = "SELECT a.member_id, a.idd, a.status, b.first_name, b.last_name, b.dharma_name, b.email, b.phone, b.cell, b.gender, b.city, b.site  
					FROM puti_idd a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE 1 = 1 AND
					b.site IN " . $admin_user["sites"] . "
					$criteria 
					$order_str";

	$result0 = $db->query($query0);
	$cnt0 = 0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["status"] 			= $row0["status"]!="1"?"":"Y";

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["dharma_name"] 		= $row0["dharma_name"];
		$names["alias"] 			= $row0["alias"];
		$evt_arr["name"] 			= cTYPE::gstr(cTYPE::lfname($names,13));
		
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"];
		$evt_arr["cell"] 			= $row0["cell"];
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);
		$evt_arr["site"] 			= cTYPE::gstr($words[strtolower($sites[$row0["site"]])]);
		$cnt0++;

		$html .= '<tr>';
		$html .= '<td width="40" align="center" ' . $c2 . '>';
		$html .=  $cnt0;
		$html .= '</td>';

		$html .= '<td align="center" ' . $c2 . '>';
		$html .=  $evt_arr["status"];
		$html .= '</td>';

		$html .= '<td ' . $c2 . '><b>';
		$html .=  $evt_arr["name"];
		$html .= '</b></td>';
		
		$html .= '<td ' . $c2 . '>';
		$html .=  $evt_arr["email"];
		$html .= '</td>';
		
		$html .= '<td ' . $c2 . '>';
		$html .=  $evt_arr["phone"];
		$html .= '</td>';

		$html .= '<td ' . $c2 . '>';
		$html .= $evt_arr["cell"];
		$html .= '</td>';

		$html .= '<td ' . $c2 . '>';
		$html .=  $evt_arr["city"] ;
		$html .= '</td>';

		$html .= '<td ' . $c2 . '>';
		$html .=  $evt_arr["site"] ;
		$html .= '</td>';

		$html .= '<td align="right" ' . $c2 . '>';
		$html .= $evt_arr["idd"];
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
