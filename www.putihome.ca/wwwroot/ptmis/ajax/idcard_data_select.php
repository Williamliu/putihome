<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["sch_site"] = '{"type":"NUMBER", "length":0, "id": "sch_site", "name":"Select Bodhi Center", "nullable":0}';
	$type0["sch_sdate"] = '{"type":"DATE", "length":0, "id": "sch_sdate", "name":"Select Start Date", "nullable":0}';
	cTYPE::validate($type0, $_REQUEST["condition"]);

	$type["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	$type["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "ORDER BY a.created_time DESC";
	}

	
	// condition here 
	$criteria = "";

	$con = $_REQUEST["condition"]; 

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
	$sch_shh = trim($con["sch_shh"]);
	$sch_smm = trim($con["sch_smm"]);
	$sch_ehh = trim($con["sch_ehh"]);
	$sch_emm = trim($con["sch_emm"]);
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
						

	$query 	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
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
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["name"] 		= cTYPE::gstr(cTYPE::cname($names));
		/*
		$names						= array();
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];
		$rows[$cnt]["legal_name"] 	= cTYPE::gstr(cTYPE::cname($names));
		*/
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"]?cTYPE::gstr($row["dharma_name"]):'';

		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["gender"] 		= $row["gender"];

		$rows[$cnt]["member_site"] 	= cTYPE::gstr($words[strtolower($row["member_site"])]);

		
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["member_id"] . ".jpg")?"Y":"";

		$cnt++;	
	}
	// synchorize to general
	$response["data"]["general"]["recoTotal"] 	= $recoTotal;
	$response["data"]["general"]["membTotal"] 	= $membTotal;
	$response["data"]["general"]["pageTotal"] 	= $pageTotal;
	$response["data"]["general"]["pageNo"] 		= $pageNo;
	$response["data"]["general"]["pageSize"] 	= $pageSize;
	// synchorize to tabData.condition
	$response["data"]["condition"]	= $_REQUEST["condition"];
	$response["data"]["rows"] 		= $rows;

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
