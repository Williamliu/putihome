<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["member_id"] = '{"type":"NUMBER", "length":11, "id": "member_id", "name":"Select member", "nullable":0}';
	cTYPE::validate($type, $_REQUEST["condition"]);

	$type["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	$type["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);


	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"]==""?"start_date":$_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	
	// condition here 
	$criteria = "";
	$con = $_REQUEST["condition"]; 

	$sch_mid = trim($con["member_id"]);
	if($sch_mid != "") {
		$criteria .= ($criteria==""?"":" AND ") . " a.member_id = '" . $sch_mid . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria



	$query_base = "SELECT b.title, b.start_date, b.end_date, a.id, a.member_id, a.doc_no, a.cert_no, a.online, a.signin, a.graduate, a.cert, a.attend, a.paid, a.amt, a.paid_date   
						FROM event_calendar_enroll a
						INNER JOIN event_calendar b ON (a.event_id = b.id) 
				  WHERE a.deleted <> 1 $criteria
				  $order_str";

	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(id) AS CNT FROM ( " . $query_base . " ) res1");
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

	$query 	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	while( $row = $db->fetch($result)) {

		$rows[$cnt]["title"] 		= cTYPE::gstr($row["title"]);
		$rows[$cnt]["start_date"] 	= $row["start_date"]>0?date("Y-m-d",$row["start_date"]):'';
		$rows[$cnt]["start_date"] 	.= ($row["start_date"]>0?"<br>":"") . ($row["end_date"]>0?date("Y-m-d",$row["end_date"]):'');
		$rows[$cnt]["end_date"] 	= $row["end_date"]>0?date("Y-m-d",$row["end_date"]):'';
		$rows[$cnt]["doc_no"] 		= $row["doc_no"]?$row["doc_no"]:"";
		$rows[$cnt]["cert_no"] 		= $row["cert_no"]?$row["cert_no"]:"";
		$rows[$cnt]["online"] 		= $row["online"]?"Y":"";
		$rows[$cnt]["signin"] 		= $row["signin"]?"Y":"";
		$rows[$cnt]["graduate"] 	= $row["graduate"]?"Y":"";
		$rows[$cnt]["cert"] 		= $row["cert"]?"Y":"";
		$rows[$cnt]["attend"] 		= $row["attend"]>0?($row["attend"] * 100) . "%":"";
		$rows[$cnt]["paid"] 		= $row["paid"]?"Y":"";
		$rows[$cnt]["amt"] 			= $row["amt"]>0?"$".$row["amt"]:"";
		$rows[$cnt]["paid_date"] 	= $row["paid_date"]>0?date("Y-m-d",$row["paid_date"]):'';

		$cnt++;	
	}

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
