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
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	
	// condition here 
	$criteria = "";
	//$criteria .= "site in " . $admin_user["sites"];

	$con = $_REQUEST["condition"]; 
	
	$sch_name = trim($con["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . 
						"( 	a.dharma_name = '" . 	cTYPE::trans_trim($sch_name) . "' OR 
							a.alias 		= '" . cTYPE::trans_trim($sch_name) . "' OR
							concat(a.first_name, a.last_name) = '" . 	cTYPE::trans_trim1($sch_name) . "' OR
							concat(a.last_name,  a.first_name) = '" . 	cTYPE::trans_trim1($sch_name) . "' OR
							concat(a.legal_first, a.legal_last) = '" . 	cTYPE::trans_trim1($sch_name) . "' OR
							concat(a.legal_last, a.legal_first) = '" . 	cTYPE::trans_trim1($sch_name) . "'
						)";
	}

	$sch_phone = trim($con["sch_phone"]);
	$sch_phone = str_replace(array(" ","-",".","?","%"), array("","","","",""), $sch_phone);

	if( strlen($sch_phone) < 4 && $sch_phone != "" ) {
		$response["errorCode"] 		= 1;
		$response["errorMessage"]	= "Please provide full phone number or at least last 4 digi numbers";
		echo json_encode($response);
		exit();
	}

	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" OR ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "')";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" OR ") . "a.email = '" . $sch_email . "'";
	}

	// important,   if  scan ID Card,  search in whole list without site restrict  
	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" OR ") . "a.id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" OR ") . "a.id = '-1'";
		}
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria
	/*
	if( $criteria == "" ) {
		$criteria = " AND a.id = '-1'";
	}
	*/
	
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

	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	if( $criteria == "" ) {
		$query_base = "SELECT a.*, aa0.idd as id_card, c.admin_id 
								FROM puti_members a
								INNER JOIN puti_email c ON ( a.id = c.member_id) 
								LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
								WHERE  a.deleted <> 1  AND a.site in " . $admin_user["sites"]. " AND admin_id = '" . $admin_user["id"] . "' 
								$criteria 
								$order_str";
	
	} else {
		$query_base = "SELECT a.*, aa0.idd as id_card, c.admin_id   
								FROM puti_members a
								LEFT JOIN puti_email c ON ( a.id = c.member_id) 
								LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
								WHERE  a.deleted <> 1  AND a.site in " . $admin_user["sites"]. "
								$criteria 
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
		$names["dharma_name"] 		= $row["dharma_name"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["first_name"] 	= cTYPE::gstr(cTYPE::cname($names));
		
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["language"] 	= $row["language"]?$langs[$row["language"]]:"";
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["email_flag"] 	= $row["email_flag"]; //$row["email_flag"]?cTYPE::gstr($words["email.subscribe"]):cTYPE::gstr($words["email.unsubscribe"]);
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		$rows[$cnt]["phone1"] 		= $row["phone"];
		$rows[$cnt]["cell1"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);
		$rows[$cnt]["postal"] 		= $row["postal"];
		$rows[$cnt]["idd"] 			= $row["id_card"]?$row["id_card"]:''; //'<input class="idd_card" type="text" style="width:80px;" rid="' . $row["id"] . '" value="' . $row["id_card"] . '" />';
		$rows[$cnt]["enroll"] 		= $row["admin_id"]>0?'<a class="enroll-status-enroll"></a>':'';
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
