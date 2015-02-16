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
		$order_str = "ORDER BY c.id ASC";
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

	$sch_group = trim($con["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . $sch_group . "'";
	}


	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_doc_no = trim($con["sch_doc_no"]);
	if($sch_doc_no != "") {
		$criteria .= ($criteria==""?"":" AND ") . "doc_no like '%" . cTYPE::trans($sch_doc_no) . "%'";
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
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}
	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	$query_base = "SELECT a.id, a.first_name, a.last_name, a.dharma_name, a.alias, a.legal_first, a.legal_last, a.gender,
						  c.id as enroll_id, c.member_id, c.group_no, c.doc_no, c.online, c.signin, c.graduate, c.cert, c.attend, c.paid, c.amt, c.paid_date
						FROM puti_members a
						LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
						INNER JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1) c ON ( a.id = c.member_id ) 
						WHERE  a.deleted <> 1 
						$criteria 
						$order_str";
	
	
	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(*) AS CNT FROM ( " . $query_base . " ) res1");
	$row_total = $db->fetch($result_num);
	$recoTotal =  $row_total["CNT"];
	$pageTotal = ceil($recoTotal/$pageSize);
						

	$query 	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	$gpno = array();
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];

		$rows[$cnt]["member_id"] 	= $row["member_id"];
		$rows[$cnt]["enroll_id"] 	= $row["enroll_id"];

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["dharma_name"] 		= $row["dharma_name"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["aname"] 		= cTYPE::gstr(cTYPE::lfname($names));

		$rows[$cnt]["legal_name"] 	= $row["legal_last"] . ($row["legal_last"]!=""?", ":"") . $row["legal_first"];
		
		$rows[$cnt]["gender"] 		= $row["gender"];
		//$rows[$cnt]["email"] 		= $row["email"];
		//$rows[$cnt]["phone"] 		= $row["phone"];
		//$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		//$rows[$cnt]["phone1"] 		= $row["phone"];
		//$rows[$cnt]["cell1"] 		= $row["cell"];
		//$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		//$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);
		//$rows[$cnt]["postal"] 		= $row["postal"];
		$rows[$cnt]["group_no"] 	= $row["group_no"]?$row["group_no"]:"";
		
		$rows[$cnt]["doc_no"] 		= $row["doc_no"]?$row["doc_no"]:"";
		$rows[$cnt]["online"] 		= $row["online"]?"Y":"";
		$rows[$cnt]["signin"] 		= $row["signin"]?"Y":"";
		$rows[$cnt]["graduate"] 	= $row["graduate"]?"Y":"";
		$rows[$cnt]["cert"] 		= $row["cert"]?"Y":"";
		$rows[$cnt]["attend"] 		= ($row["attend"] * 100) . "%";
		$rows[$cnt]["paid"] 		= $row["paid"]?"Y":"";
		$rows[$cnt]["amt"] 			= $row["amt"]>0?"$".$row["amt"]:"";
		$rows[$cnt]["paid_date"] 	= $row["paid_date"]>0?date("Y-m-d",$row["paid_date"]):'';
		
		
		//$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";
		//$gpno[$row["group_no"]]++;
		//$cert_no = $cert_prefix . '-' . $row["group_no"] . (($pageNo-1) * $pageSize + ( $gpno[$row["group_no"]] )); 
		//$rows[$cnt]["cert_no"] 		= $row["cert_no"]?$row["cert_no"]:"";

		//$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";

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
