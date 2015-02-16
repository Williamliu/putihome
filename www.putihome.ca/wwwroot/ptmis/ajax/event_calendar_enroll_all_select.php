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
		$orderBY = $_REQUEST["orderBY"]=="aname"?"last_name":$_REQUEST["orderBY"];
		$orderBY = $orderBY=="created_time"?"a.created_time":$orderBY;
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
		$criteria .= ($criteria==""?"":" AND ") . "a.status = '" . $sch_status . "'";
	}

	$sch_online = trim($con["sch_online"]);
	if($sch_online != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.online = '" . $sch_online . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}


	$sch_plate = trim($con["sch_plate_no"]);
	if($sch_plate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "replace(replace(replace(plate_no,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_plate) . "%'";
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
		// for cross sites access and enroll
		//$criteria .= ($criteria==""?"":" AND ") . "site in " . $admin_user["sites"];
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}

	$con["event_id"] = $con["event_id"]!=""?$con["event_id"]:-1;
	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $con["event_id"] . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];

	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(idd) as idd FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.idd = aaa1.idd)";

	if( $con["event_id"] != "" ) {
		$query_base = "SELECT 	a.*, aa0.idd as id_card, 
								IFNULL(c.id, 0) as enroll, c.group_no, c.signin, c.trial, c.shelf, 
								c.paid, c.amt, c.invoice, c.paid_date  
							FROM puti_members a
							LEFT JOIN puti_members_others b ON ( a.id = b.member_id ) 
							LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
							LEFT JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1 ) c ON ( a.id = c.member_id ) 
							WHERE  a.deleted <> 1  
							$criteria 
							$order_str";
	
	} else {
		$query_base = "SELECT a.*, aa0.idd as id_card,
							 0 as enroll, 0 as group_no, 0 as signin, 0 as trial, 0 as paid, 0 as amt, '' as invoice, 0 as paid_date, '' as shelf   
							FROM puti_members a
							LEFT JOIN puti_members_others b ON ( a.id = b.member_id ) 
							LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
							WHERE  a.deleted <> 1  
							$criteria 
							$order_str";
	}
	
	if( trim($con["sch_name"])=="" &&
		trim($con["sch_phone"])=="" &&
		trim($con["sch_email"])=="" &&
		trim($con["sch_gender"])=="" &&
		trim($con["sch_status"])=="" &&
		trim($con["sch_online"])=="" &&
		trim($con["sch_level"])=="" &&
		trim($con["sch_plate_no"])=="" &&
		trim($con["sch_city"])=="" &&
		trim($con["sch_idd"])=="" )
	{

		if( $con["event_id"] != "" ) {
			$query_base = "SELECT 	a.*, aa0.idd as id_card, 
									IFNULL(c.id, 0) as enroll, c.group_no, c.signin, c.trial, c.shelf, 
									c.paid, c.amt, c.invoice, c.paid_date  
								FROM puti_members a
								LEFT JOIN puti_members_others b ON ( a.id = b.member_id ) 
								LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
								LEFT JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1 ) c ON ( a.id = c.member_id ) 
								WHERE  a.deleted <> 1 AND
								c.status = 1 AND c.deleted <> 1 
								$order_str";
		
		} else {
			$query_base = "SELECT a.*, aa0.idd as id_card,
								 0 as enroll, 0 as group_no, 0 as signin, 0 as trial, 0 as paid, 0 as amt, '' as invoice, 0 as paid_date, '' as shelf    
								FROM puti_members a
								LEFT JOIN puti_members_others b ON ( a.id = b.member_id ) 
								LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
								WHERE  a.deleted <> 1 AND
								1 = 0
								$order_str";
		}



		/*
		// synchorize to general
		$response["data"]["general"]["recoTotal"] 	= $recoTotal;
		$response["data"]["general"]["pageTotal"] 	= $pageTotal;
		$response["data"]["general"]["pageNo"] 		= $pageNo;
		$response["data"]["general"]["pageSize"] 	= $pageSize;
		// synchorize to tabData.condition
		$response["data"]["condition"]	= $_REQUEST["condition"];
		$response["data"]["rows"] 		= array();
	
		$response["errorCode"] 		= 0;
		//$response["errorMessage"]	= ""//$words["empty search error"];
		$response["errorMessage"]	= "";
		echo json_encode($response); 
		exit();
		*/
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
		$rows[$cnt]["aname"] 		= cTYPE::gstr(cTYPE::lfname($names));
		
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["sex"] 			= $row["gender"];
		$rows[$cnt]["language"] 	= $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]);
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 	   .= ($row["phone"]!=""?"<br>":"") . $row["cell"];
		$rows[$cnt]["phone1"] 		= $row["phone"];
		$rows[$cnt]["cell1"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);
		$rows[$cnt]["postal"] 		= $row["postal"];
		$rows[$cnt]["shelf"] 		= cTYPE::shelfSN($row["shelf"],$CFG["max_shoes_rack"]);
		$rows[$cnt]["signin"] 		= $row["signin"];
		$rows[$cnt]["trial"] 		= $row["trial"];
		$group_no = $row["group_no"]?$row["group_no"]:"";
		$rows[$cnt]["group_no"] 	= $group_no; //'<input class="group_no" type="text" style="width:30px;font-size:14px;font-weight:bold;text-align:center;" rid="' . $row["id"] . '" value="' . $group_no . '" />';
		$rows[$cnt]["idd"] 			= $row["id_card"]?$row["id_card"]:''; //'<input class="idd_card" type="text" style="width:80px;" rid="' . $row["id"] . '" value="' . $row["id_card"] . '" />';
		$rows[$cnt]["enroll"] 		= $row["enroll"]>0?'<a class="enroll-status-enroll"></a>':'';
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";
		$rows[$cnt]["photo"] 		= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";
		$rows[$cnt]["photo_url"] 	= $CFG["http"] . $CFG["web_domain"]."/ajax/lwhUpload_image.php?ts=".time()."&size=small&img_id=" . $row["id"];
	 
		// payment 
		$rows[$cnt]["paid"] 		= $row["paid"]?"Y":"";
		$rows[$cnt]["amt"] 			= $row["amt"]>0?"$".$row["amt"]:"";
		$rows[$cnt]["invoice"] 		= $row["invoice"]?$row["invoice"]:"";
		$rows[$cnt]["paid_date"]	= $row["paid_date"]>0?date("Y-m-d",$row["paid_date"]):"";

		
		if($payfree == "1") {
			  $rows[$cnt]["paid"] 		= "Free";
			  $rows[$cnt]["amt"] 		= "";
			  $rows[$cnt]["invoice"] 	= "";
			  $rows[$cnt]["paid_date"]	= "";
		} else {
			if( $payonce == "1" ) {
				$query8 = "SELECT paid, amt, paid_date , invoice 
							  FROM event_calendar  a
							  INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
							  WHERE a.class_id = '" . $class_id . "' AND paid = 1 AND
								  b.member_id = '" . $rows[$cnt]["id"] . "' 
							  ORDER BY paid_date DESC, amt DESC";
				$result8 	= $db->query($query8);
			  	$row8 		= $db->fetch($result8); 		

			  $rows[$cnt]["paid"] 		= $row8["paid"]?"Y":"";
			  $rows[$cnt]["amt"] 		= $row8["amt"]>0?"$".$row8["amt"]:"";
			  $rows[$cnt]["invoice"] 	= $row8["invoice"]?$row8["invoice"]:"";
			  $rows[$cnt]["paid_date"]	= $row8["paid_date"]>0?date("Y-m-d",$row8["paid_date"]):"";
			}
		}
		//end of payment
			
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
