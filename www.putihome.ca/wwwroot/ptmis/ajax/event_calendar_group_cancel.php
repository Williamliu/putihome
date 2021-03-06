<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST["condition"]);

	$type["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	$type["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = cTYPE::gstr($row_age["title"]);
	}

	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"]=="aname"?"last_name":$_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	
	// condition here 
	$criteria = "";
	$con = $_REQUEST["condition"]; 

	$sch_eid = trim($con["event_id"]);
	if($sch_eid != "") {
		$criteria .= ($criteria==""?"":" AND ") . " a.event_id = '" . $sch_eid . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $sch_eid . "'";
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
					
	$query_base = "SELECT a.id, a.id as enroll_id, a.paid, a.amt, a.invoice, a.paid_date, a.new_flag, 
						b.id as member_id, b.first_name, b.last_name, b.dharma_name,b.alias, b.age, b.language, b.gender, b.phone, b.cell, b.city, b.site, c.title as site_desc, a.group_no, a.online, aa0.idd  
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						LEFT JOIN ($query_idd) aa0 ON (b.id = aa0.member_id)
						LEFT JOIN puti_sites c ON (b.site = c.id)  
            			WHERE  a.deleted = 1 AND b.deleted <> 1 
						$criteria 
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
		$rows[$cnt]["member_id"] 		= $row["member_id"];
		$rows[$cnt]["enroll_id"] 		= $row["enroll_id"];

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["aname"]		= cTYPE::gstr(cTYPE::lfname($names, 13));
	
		$rows[$cnt]["dharma_name"]	= cTYPE::gstr($row["dharma_name"]);
    	
		$rows[$cnt]["age"] 			= $row["age"]>=1?$ages[$row["age"]]:"";
		$rows[$cnt]["gender"] 		= $row["gender"];

		$rows[$cnt]["language"] 	= $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]);

		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["phone"] 		.= ($row["phone"]!=""?"<br>":""). $row["cell"];

		$rows[$cnt]["group_no"] 	= $row["group_no"]>0?$row["group_no"]:"";
		$rows[$cnt]["online"] 		= $row["online"]==1?"Y":"";

		$rows[$cnt]["idd"] 			= $row["idd"]!=""?$row["idd"]:"";

		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= $row["site"];
		$rows[$cnt]["site_desc"] 	= cTYPE::gstr($row["site_desc"]);
		
		$rows[$cnt]["paid"] 		= $row["paid"]?"Y":"";
		$rows[$cnt]["amt"] 			= $row["amt"]>0?$row["amt"]:"";
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
								  b.member_id = '" . $rows[$cnt]["member_id"] . "' 
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
	

        $rows[$cnt]["new"]              = $row["new_flag"]?"Y":"";;

		$query9 = "SELECT c.id, c.title, count(b.id) as count 
						FROM event_calendar  a
						INNER JOIN puti_class c ON (a.class_id = c.id) 
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
						WHERE   a.start_date < '" . $evt_start_date . "' AND
                                (b.graduate = 1 OR b.cert = 1 ) AND c.deleted <> 1 AND
								b.member_id = '" . $rows[$cnt]["member_id"] . "' 
						GROUP BY c.id, c.title 
						ORDER BY c.id, c.title ASC";
		$result9 	= $db->query($query9);
		$cnt_records = 0;
		while( $row9	= $db->fetch($result9) ){
			$rows[$cnt]["records"][$cnt_records]["title"] 	= cTYPE::gstr($row9["title"]);
			$rows[$cnt]["records"][$cnt_records]["count"] 	= $row9["count"]>0?$row9["count"]:0;
			$cnt_records++;
		}


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
