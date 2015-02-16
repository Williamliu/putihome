<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	$type["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$_REQUEST["orderBY"] = $_REQUEST["orderBY"]=="flname"?"last_name":$_REQUEST["orderBY"];
		$_REQUEST["orderBY"] = $_REQUEST["orderBY"]=="legal_name"?"legal_last":$_REQUEST["orderBY"];
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	
	// condition here 
	$criteria = "";
	$criteria .= "1=1";
	
	$con = $_REQUEST["condition"]; 
	
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

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}

	$sch_plate = trim($con["sch_plate_no"]);
	if($sch_plate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "replace(replace(replace(plate_no,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_plate) . "%'";
	}

	$sch_memid = trim($con["sch_memid"]);
	if($sch_memid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "id = '" . $sch_memid . "'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_flag = trim($con["sch_email_flag"]);
	if($sch_flag != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email_flag = '" . cTYPE::trans($sch_flag) . "'";
	}

	$sch_lang = trim($con["sch_language"]);
	if($sch_lang != "") {
		$criteria .= ($criteria==""?"":" AND ") . "language = '" . cTYPE::trans($sch_lang) . "'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;


	// important,   if  scan ID Card,  search in whole list without site restrict  
	$sch_idd = trim($con["sch_idd"]);
	if($sch_idd != "") {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "id = '-1'";
		}
	} else {
		$criteria .= ($criteria==""?"":" AND ") . "site in " . $admin_user["sites"];
	}


	// end of criteria
	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}

	$query_base = "SELECT *, first_name as name1
						FROM puti_members
						LEFT JOIN puti_members_others b ON ( puti_members.id = b.member_id ) 
            			WHERE deleted <> 1    
						$criteria 
						$order_str";
	
	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(id) AS CNT FROM ( " . $query_base . " ) res1");
	$row_total = $db->fetch($result_num);
	$recoTotal =  $row_total["CNT"];
	$pageTotal = ceil($recoTotal/$pageSize);
						

	$query 	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		
		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$rows[$cnt]["flname"]		=  cTYPE::gstr(cTYPE::lfname($names));

		$rows[$cnt]["legal_name"] 	= $row["legal_last"] . ($row["legal_last"]!=""?", ":"") . $row["legal_first"];

		$rows[$cnt]["dharma_name"] 	= cTYPE::gstr($row["dharma_name"]);
		$rows[$cnt]["alias"] 		= cTYPE::gstr($row["alias"]);
		$rows[$cnt]["gender"] 		= $row["gender"];

		$rows[$cnt]["language"] 	= $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]);

		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"] . ($row["phone"]!=""?"<br>". $row["cell"]:"");
		$rows[$cnt]["phone1"] 		= $row["phone"];
		$rows[$cnt]["cell1"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);
		$rows[$cnt]["postal"] 		= $row["postal"];
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";
		$cnt++;	
	}
	// synchorize to general
	$response["data"]["general"]["recoTotal"] 	= $recoTotal;
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
