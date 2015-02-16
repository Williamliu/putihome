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
	$con = $_REQUEST["condition"]; 

	$sch_11 = trim($con["sch_project"]);
	if($sch_11 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "project like '%" . $sch_11 . "%'";
	}

	$sch_22 = trim($con["sch_filter"]);
	if($sch_22 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "filter like '%" . $sch_22 . "%'";
	}

	$sch_33 = trim($con["sch_keyword"]);
	if($sch_33 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "keyword like '%" . $sch_33 . "%'";
	}

	$sch_44 = trim($con["sch_content"]);
	if($sch_44 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(en like '%" . $sch_44 . "%' OR cn like '%" . $sch_44 . "%')";
	}


	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria
	
	
	$query_base = "SELECT * 
						FROM website_language_word   
            			WHERE  deleted <> 1  
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
		$rows[$cnt]["project"] 		= stripslashes($row["project"]);
		$rows[$cnt]["filter"] 		= stripslashes($row["filter"]);
		$rows[$cnt]["keyword"] 		= stripslashes($row["keyword"]);
		$rows[$cnt]["en"] 			= stripslashes($row["en"]);
		$rows[$cnt]["cn"] 			= stripslashes($row["cn"]);
		$rows[$cnt]["tw"] 			= stripslashes($row["tw"]);
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d H:i:s",$row["created_time"]):"";
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
