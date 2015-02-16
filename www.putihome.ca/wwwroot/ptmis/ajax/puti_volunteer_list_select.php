<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	cTYPE::validate($type0, $_REQUEST);
	
	$type1["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type1, $_REQUEST["condition"]);
	
	cTYPE::check();
	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];

	$pageSize 	= $_REQUEST["condition"]["pageSize"]<=0?24:$_REQUEST["condition"]["pageSize"];
	$orderBY	= $_REQUEST["condition"]["orderBY"]==""?"created_time":$_REQUEST["condition"]["orderBY"];
	$orderSQ	= $_REQUEST["condition"]["orderSQ"]==""?"DESC":$_REQUEST["condition"]["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	// condition here 
	$criteria = "";
	$con = $_REQUEST["condition"]; 
	$sch_name = trim($con["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(cname like '%" . cTYPE::trans($sch_name) . "%' OR pname like '%" . cTYPE::trans($sch_name) . "%' OR dharma_name like '%" . cTYPE::trans($sch_name) . "%' OR en_name like '%" . cTYPE::trans($sch_name) . "%')";
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

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_depart = trim($con["sch_depart"]);
	if($sch_depart != "") {
		$criteria .= ($criteria==""?"":" AND ") . "department_id = '" . $sch_depart . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$sch_depart = trim($con["sch_depart"]);
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
							a.site IN " . $admin_user["sites"] . "
							$criteria 
							$order_str";
	}
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
		$rows[$cnt]["cname"] 		= $row["cname"];
		$rows[$cnt]["pname"] 		= $row["pname"];
		$rows[$cnt]["en_name"] 		= $row["en_name"];
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"];
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= $row["city"];
		$rows[$cnt]["status"] 		= $row["status"]==1?"Acitve":"Inactive";
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):'';
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
