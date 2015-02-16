<?php 
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=volunteer_list.xls");
	
	$orderBY	= $_REQUEST["orderBY"]==""?"created_time":$_REQUEST["orderBY"];
	$orderSQ	= $_REQUEST["orderSQ"]==""?"DESC":$_REQUEST["orderSQ"];
	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	$criteria = "";
	$sch_name = trim($_REQUEST["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(cname like '%" . cTYPE::trans($sch_name) . "%' OR pname like '%" . cTYPE::trans($sch_name) . "%' OR dharma_name like '%" . cTYPE::trans($sch_name) . "%' OR en_name like '%" . cTYPE::trans($sch_name) . "%')";
	}

	$sch_phone = trim($_REQUEST["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%')";
	}

	$sch_email = trim($_REQUEST["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($_REQUEST["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_status = trim($_REQUEST["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "status = '" . $sch_status . "'";
	}

	$sch_city = trim($_REQUEST["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_depart = trim($_REQUEST["sch_depart"]);
	if($sch_depart != "") {
		$criteria .= ($criteria==""?"":" AND ") . "department_id = '" . $sch_depart . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$sch_depart = trim($_REQUEST["sch_depart"]);
	if($sch_depart == "") {
		$query_base = "SELECT * 
							FROM puti_volunteer  
							WHERE  deleted <> 1  AND
							site IN " . $admin_user["sites"] . "
							$criteria 
							$order_str";
	} else {
		$query_base = "SELECT distinct a.* 
							FROM puti_volunteer a INNER JOIN puti_department_volunteer b ON (a.id = b.volunteer_id)  
							WHERE  a.deleted <> 1 AND 
							site IN " . $admin_user["sites"] . "
							$criteria 
							$order_str";
	}
	//echo "query:" . $query_base;

	
	$result = $db->query( $query_base );
	$rows = array();
	$cnt = 0;

	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html.= '<tr>';
	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . $words["sn"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["c.name"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["pinyin"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["e.name"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["dharma"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["gender"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["email"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["phone"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["cell"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["city"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["status"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["department"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["Dep.No"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["created time"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>ID</td>';
	$html.= '</tr>';
	

	while( $row = $db->fetch($result)) {
		$cnt++;	
		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cname"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["pname"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["en_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $row["gender"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["email"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cell"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["city"] . '</td>';
		$html.= '<td ' . $width_two . '>' . ($row["status"]==1?"Active":"Inactive") . '</td>';

		$query_em 	= "SELECT c.title 
							FROM puti_volunteer a 
							INNER JOIN puti_department_volunteer b ON (a.id = b.volunteer_id) 
							INNER JOIN puti_department c ON (b.department_id = c.id) 
					   WHERE a.deleted <> 1 AND a.id = '" . $row["id"] . "' ORDER BY c.sn"; 
		$result_em 	= $db->query($query_em);
		$depart = '';
		$departCNT = 0;
		while( $row_em 	= $db->fetch($result_em) ) {
			$depart .= ($depart==""?"":",") . $row_em["title"];
			$departCNT++;
		}

		$html.= '<td ' . $width_two . '>' . $depart . '</td>';
		$html.= '<td ' . $width_two . '>' . $departCNT . '</td>';

		//emergency

		$html.= '<td ' . $width_two . '>' . ($row["created_time"]>0?date("Y-M-d",$row["created_time"]):"") . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["id"] . '</td>';
		$html.= '</tr>';
	}
	$html.= '<tr>';
	$html.= '<td colspan="15" style="font-size:12px; font-weight:bold;">Total: ' . $cnt . '</td>';
	$html.= '</tr>';
	
	$html.= '</table>';
	echo $html;

} catch(cERR $e) {
	echo "<pre>";
	print_r($e->detail());
	echo "</pre>";	
} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo "<pre>";
	print_r($response);
	echo "</pre>";	
}
?>
