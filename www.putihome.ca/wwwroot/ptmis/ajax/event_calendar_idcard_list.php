<?php 
//session_start();
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

	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}


	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	//*************** criteria ***********************************************/
	$criteria = "";
	$criteria .= "b.site IN " . $admin_user["sites"]; 	
	
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

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}


	/////////////////////////////////////////////////////////////////////////
	$query_base = "SELECT a.member_id, a.idd, a.status, b.first_name, b.last_name, b.dharma_name,b.alias, b.email, b.phone, b.cell, b.gender, b.city, b.site 
					FROM puti_idd a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE 1 = 1 
					$criteria 
					$order_str";

	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(*) AS CNT FROM ( " . $query_base . " ) res1");
	$row_total = $db->fetch($result_num);
	$recoTotal =  $row_total["CNT"];
	$pageTotal = ceil($recoTotal/$pageSize);

	// synchorize to general
	$response["data"]["general"]["recoTotal"] 	= $recoTotal;
	$response["data"]["general"]["pageTotal"] 	= $pageTotal;
	$response["data"]["general"]["pageNo"] 		= $pageNo;
	$response["data"]["general"]["pageSize"] 	= $pageSize;
	// synchorize to tabData.condition
	$response["data"]["condition"]	= $_REQUEST["condition"];


	$query0 	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result0 = $db->query($query0);
	$rows = array();
	$cnt = 0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["status"] 			= $row0["status"];
	
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
		$evt_arr["city"] 			= $row0["city"];
		$evt_arr["site"] 			= cTYPE::gstr($words[strtolower($sites[$row0["site"]])]);
		$rows[$cnt]					= $evt_arr;
		$cnt++;
	}

	$response["data"]["rows"] = $rows;
	$response["errorMessage"]	= "";
	$response["errorCode"] 		= 0;

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
