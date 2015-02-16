<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}
	$order_str = " ORDER BY a.id DESC, created_time DESC "; 
	
	// condition here 
	$criteria = "";
	$con = $_REQUEST["condition"]; 

	$sch_11 = trim($con["event_id"]);
	if($sch_11 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.ref_id = '" . $sch_11 . "'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}


	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $con["event_id"] . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];


	/////////////////////////////////////////////////////////////////////////
	$query_base = "SELECT 	a.id, a.member_id, a.idd, a.created_time, 
						b.first_name, b.last_name, b.dharma_name, b.alias, b.email, b.phone, b.cell, b.gender, b.city, b.site, b.status, b.deleted as invalid,
					  	c.id as enroll_id, c.group_no, c.unauth, c.shelf, c.trial, c.trial_date, c.paid, c.amt, c.invoice, c.paid_date, c.deleted   
					FROM (	SELECT * FROM puti_attend 
									WHERE purpose = 'event' AND ref_id = '" . $con["event_id"] . "' AND
									created_time BETWEEN '" . mktime(0,0,0,date("n"), date("j"), date("Y")) . "' AND '" . mktime(23,59,59,date("n"), date("j"), date("Y")) . "' 		
						  ) a 
					INNER JOIN puti_members b ON (a.member_id = b.id) 
					LEFT JOIN ( SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "') c ON ( b.id = c.member_id AND a.ref_id = c.event_id )
					WHERE a.created_time BETWEEN '" . mktime(0,0,0,date("n"), date("j"), date("Y")) . "' AND '" . mktime(23,59,59,date("n"), date("j"), date("Y")) . "' 
					AND a.purpose = 'event' 
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


	$query0	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result0 = $db->query($query0);
	$evt = array();
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["id"] 				= $row0["id"];
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["time"] 			= date("H:i - M jS", $row0["created_time"]);

		$names				= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		//$names["dharma_name"] 		= $row0["dharma_name"];
		$names["alias"] 		= $row0["alias"];
		$evt_arr["name"] 		= cTYPE::gstr(cTYPE::lfname($names,13));
		
		$names				= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["dharma_name"] 		= $row0["dharma_name"];
		$names["alias"] 		= $row0["alias"];
		$evt_arr["name2"] 		= cTYPE::gstr(cTYPE::lfname($names,13));

		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"] . ($row0["cell"]!=""?"<br>" . $row0["cell"]:"");
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);
		$evt_arr["site"] 			= cTYPE::gstr($words[strtolower($sites[$row0["site"]])]);
		$evt_arr["group_no"] 		= intval($row0["group_no"]>0)?'<b>' . $row0["group_no"] . '</b>':'';

		$evt_arr["shelf"] 			= cTYPE::shelfSN($row0["shelf"],$CFG["max_shoes_rack"]);

		$evt_arr["invalid"] 		= $row0["status"] <> "1" || $row0["invalid"] == "1"?1:0;

		$evt_arr["trial"] 			= $row0["trial"]<>1?0:1;
		$evt_arr["trial_length"] 	= cTYPE::dhms( time() - $row0["trial_date"] );
		$evt_arr["trial_exp"] 		= ($evt_arr["trial"]==1 && $row0["trial_date"]>0)?( time()<=($row0["trial_date"] + $CFG["trial_date"]*24*3600)?0:1):0;
		$evt_arr["unauth"] 			= $row0["deleted"] 	<> 1?0:1;
		$evt_arr["unenroll"] 		= $row0["enroll_id"]?0:1;
		$evt_arr["state"]			= $evt_arr["trial"] <> 1?0:1;

		
		$evt_arr["paid"] 		= $row0["paid"]?"Y":"";
		$evt_arr["amt"] 		= $row0["amt"]>0?$row0["amt"]:"";
		$evt_arr["invoice"] 	= $row0["invoice"]?$row0["invoice"]:"";
		$evt_arr["paid_date"]	= $row0["paid_date"]>0?date("Y-m-d",$row0["paid_date"]):"";

		if( $payfree == "1") {
			$evt_arr["paid"] = "Free";
			$evt_arr["amt"] 		= "";
			$evt_arr["invoice"] 	= "";
			$evt_arr["paid_date"]	= "";
		} else {
			if( $payonce == "1" ) {
				  $query8 = "SELECT paid, amt, paid_date , invoice 
								  FROM event_calendar  a
								  INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
								  WHERE a.class_id = '" . $class_id . "' AND paid = 1 AND
									    b.member_id = '" . $evt_arr["member_id"] . "' 
								  ORDER BY paid_date DESC, amt DESC";
				  $result8 	= $db->query($query8);
				  $row8 		= $db->fetch($result8); 		
	
				  $evt_arr["paid"] 		= $row8["paid"]?"Y":"";
				  $evt_arr["amt"] 		= $row8["amt"]>0?$row8["amt"]:"";
				  $evt_arr["invoice"] 	= $row8["invoice"]?$row8["invoice"]:"";
				  $evt_arr["paid_date"]	= $row8["paid_date"]>0?date("Y-m-d",$row8["paid_date"]):"";
			}
		}
		$evt_arr["music_flag"]	= max(($evt_arr["paid"]=="Y" || $evt_arr["paid"]=="Free"?0:1), ($evt_arr["trial"]?$evt_arr["trial_exp"]:0), $evt_arr["unenroll"], $evt_arr["unauth"], $evt_arr["invalid"]);
		$evt_arr["state"] 		= $evt_arr["music_flag"]==1?2:$evt_arr["state"];
		
		$evt[$cnt0]					= $evt_arr;
		$cnt0++;
	}

	$query1 = "SELECT count( distinct a.member_id ) as st_cnt, count(a.id) as ph_cnt  
						FROM puti_attend a 
                        LEFT JOIN ( SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "') c ON ( a.member_id = c.member_id AND a.ref_id = c.event_id ) 
						WHERE a.created_time BETWEEN '" . mktime(0,0,0,date("n"), date("j"), date("Y")) . "' AND '" . mktime(23,59,59,date("n"), date("j"), date("Y")) . "' 
						AND a.purpose = 'event' AND a.ref_id = '" . $con["event_id"] . "'" ;

	$result1 	= $db->query($query1);
	$row1 		= $db->fetch($result1);
	
	$response["data"]["others"]["total_student"] 	= $row1["st_cnt"];
	$response["data"]["others"]["total_punch"] 		= $row1["ph_cnt"];
	
	$response["data"]["rows"] = $evt;
	$response["errorMessage"]	= "";
	$response["errorCode"] 		= 0;

	echo json_encode($response);
//} catch(cERR $e) {
//	echo json_encode($e->detail());
	
} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}



?>
