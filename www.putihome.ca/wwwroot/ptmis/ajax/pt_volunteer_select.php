<?php 
session_start();
ini_set("display_errors", 1);
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
		$_REQUEST["orderBY"] = $_REQUEST["created_time"]=="created_time"?"a.created_time":$_REQUEST["orderBY"];
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	
	// condition here 
	$criteria = "";

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

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_memid = trim($con["sch_memid"]);
	if($sch_memid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "id = '" . $sch_memid . "'";
	}


	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}


	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
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
	} else {
		$criteria .= ($criteria==""?"":" AND ") . "site in " . $admin_user["sites"];
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}

	$result_lang = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
	$langs =array();
	while($row_lang = $db->fetch($result_lang)) {
		$langs[$row_lang["id"]] =  cTYPE::gstr($words[$row_lang["title"]]);
	}
	$langs[0] = "";


	$query_vol = "SELECT  aaa0.member_id, aaa0.deleted FROM pt_volunteer aaa0"; 

	$query_base = "SELECT a.*, IF(ISNULL(aa0.member_id) OR aa0.deleted = 1, 0, 1) as vol_flag 
						FROM puti_members a
						LEFT JOIN ($query_vol) aa0 ON (a.id = aa0.member_id)
						WHERE  a.deleted <> 1   
						$criteria 
						$order_str";
	
	
	if( trim($con["sch_name"])=="" &&
		trim($con["sch_phone"])=="" &&
		trim($con["sch_site"])=="" &&
		trim($con["sch_email"])=="" &&
		trim($con["sch_memid"])=="" &&
		trim($con["sch_city"])=="" &&
		trim($con["sch_idd"])=="" )
	{

			$query_base = "SELECT a.*, IF(ISNULL(aa0.member_id), 0, 1) as vol_flag 
								FROM puti_members a
								INNER JOIN ($query_vol) aa0 ON (a.id = aa0.member_id)
								WHERE  a.deleted <> 1 AND aa0.deleted <> 1 
								$order_str";
		



	} 
	
	
	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(*) AS CNT FROM ( " . $query_base . " ) res1");
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
		$rows[$cnt]["gender"] 		= cTYPE::gstr($words[strtolower($row["gender"])]);

		$rows[$cnt]["language"] 	= $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]);
		$rows[$cnt]["phone"] 		= $row["phone"] . ($row["phone"]!=""?"<br>". $row["cell"]:"");

		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);

		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";

		$rows[$cnt]["vol_flag"] 	= $row["vol_flag"]?$row["vol_flag"]:0;
	 
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
