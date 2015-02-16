<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST["condition"]);
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
	$order_str = "ORDER BY group_no, leader DESC, volunteer DESC, b.last_name, b.first_name"; 
	
	// condition here 
	$criteria = "";
	$con = $_REQUEST["condition"]; 

	$sch_11 = trim($con["event_id"]);
	if($sch_11 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "event_id like '%" . $sch_11 . "%'";
	}

	$sch_trial = trim($con["sch_trial"]);
	if($sch_trial != "") {
		$criteria .= ($criteria==""?"":" AND ") . "trial = '" . $sch_trial . "'";
	}

	$sch_unauth = trim($con["sch_unauth"]);
	if($sch_unauth != "") {
		$criteria .= ($criteria==""?"":" AND ") . "unauth = '" . $sch_unauth . "'";
	}

	$sch_222 = trim($con["sch_sign"]);
	if($sch_222 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "signin = '" . $sch_222 . "'";
	}

	$sch_333 = trim($con["sch_grad"]);
	if($sch_333 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "graduate = '" . $sch_333 . "'";
	}

	$sch_444 = trim($con["sch_cert"]);
	if($sch_444 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "cert = '" . $sch_444 . "'";
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

	$sch_group = trim($con["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . $sch_group . "'";
	}

	$sch_rate = trim($con["sch_rate"]);
	if($sch_rate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "attend >= '" . ($sch_rate/100) . "'";
	}

	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "b.id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "b.id = '-1'";
		}
	}
		
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria



	// header stuff	
	$eid = $con["event_id"];
	$query00 = "SELECT  event_id, id as event_date_id, yy, mm, dd, event_date, checkin, day_no 
						FROM event_calendar_date
						WHERE event_id = '" . $eid . "' 
						ORDER BY day_no";
	$result00 = $db->query($query00);
	$head = array();
	$cnt = 0;
	$total_checkin = 0;
	while($row00	= $db->fetch($result00)) {
		$hObj = array();
		$hObj["event_id"] 		= $row00["event_id"];
		$hObj["event_date_id"] 	= $row00["event_date_id"];
		$hObj["day_no"] 		= $row00["day_no"];
		$hObj["yy"] 			= $row00["yy"];
		$hObj["mm"] 			= $row00["mm"];
		$hObj["dd"] 			= $row00["dd"];
		$hObj["event_date"] 	= $row00["event_date"]>0?date("Y-m-d",$row00["event_date"]):'';
		$hObj["event_md"] 		= $row00["event_date"]>0?date("M-j",$row00["event_date"]):'';
		$hObj["checkin"] 		= $row00["checkin"];
		$total_checkin			+= $row00["checkin"];
		$head[$cnt]             = $hObj;
		$cnt++;
	}
	$response["data"]["others"] = $head;
	//end of header stuff	

	$query_base = "SELECT  a.id as enroll_id, a.event_id, a.unauth, a.trial, a.group_no, a.online, a.signin, a.graduate, a.new_flag, a.cert, attend,
						   b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
					WHERE a.deleted <> 1 AND 
						  b.deleted <> 1 
						  $criteria 
						  $order_str";

	//echo "query:" . $query_base;
	//exit;
	
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


	$query0	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result0 = $db->query($query0);
	$cnt0=0;
	$evtArr = array();
		
	while($row0 = $db->fetch($result0)) {
		$eObj = array();
		$eObj["event_id"] 	= $row0["event_id"];
		$eObj["enroll_id"] 	= $row0["enroll_id"];
		$eObj["member_id"] 	= $row0["member_id"];

		$eObj["group_no"] 	= $row0["group_no"];

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["dharma_name"] 		= $row0["dharma_name"];
		$eObj["name"]				= cTYPE::gstr(cTYPE::lfname($names,13));

		$eObj["new_flag"]			= $row0["new_flag"]?"Y":"";

		$eObj["first_name"]	= cTYPE::gstr($row0["first_name"]);
		$eObj["last_name"]	= cTYPE::gstr($row0["last_name"]);
		$eObj["dharma_name"]= cTYPE::gstr($row0["dharma_name"]);

		$eObj["gender"]		= $row0["gender"];
		$eObj["email"]		= $row0["email"];
		$eObj["phone"]		= $row0["phone"];
		$eObj["cell"]		= $row0["cell"];
		$eObj["city"]		= cTYPE::gstr($row0["city"]);

		$eObj["trial"]		= $row0["trial"];
		$eObj["unauth"]		= $row0["unauth"];
		
		$eObj["online"]		= $row0["online"];
		$eObj["attd"]		= $row0["attend"];
		$eObj["signin"]		= $row0["signin"];
		$eObj["graduate"]	= $row0["graduate"];
		$eObj["cert"]		= $row0["cert"];

		$eObj["total_checkin"] 	= $row0["attend"]>0?$total_checkin:"";

		$querya 	= "SELECT   SUM(IF( b.status=2 OR b.status=8, 1, 0)) as total_attend,
								SUM(IF( b.status=4, 1 , 0)) as total_leave 
							FROM event_calendar_enroll a 
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                            INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
							WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND a.id = '" . $row0["enroll_id"] . "'";
		$resulta	= $db->query($querya);
		$rowa       = $db->fetch($resulta);
		$eObj["total_attend"] 	= $rowa["total_attend"]?$rowa["total_attend"]:"";
		$eObj["total_leave"] 	= $rowa["total_leave"]?$rowa["total_leave"]:"";

		
		$query1 	= "SELECT  b.enroll_id, b.event_date_id, b.sn, b.status 
							FROM event_calendar_enroll a 
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                            INNER JOIN event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin) 
							WHERE a.deleted <> 1 AND a.event_id = '" . $eid . "' AND a.id = '" . $row0["enroll_id"] . "'";
		$result1	= $db->query($query1);
		$eObj["dates"] = array();
		$cnt1=0;
		while($row1	= $db->fetch($result1)) {
			$aObj = array();
			$aObj["event_id"] 		= $row0["event_id"];
			$aObj["enroll_id"] 		= $row0["enroll_id"];
			$aObj["event_date_id"] 	= $row1["event_date_id"];
			$aObj["sn"] 			= $row1["sn"];
			$aObj["status"] 		= $row1["status"];
			$eObj["dates"][$cnt1] = $aObj;
			$cnt1++;
		}
		$evtArr[$cnt0] = $eObj;
		$cnt0++;
	}

	$response["data"]["rows"] 	= $evtArr;
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
