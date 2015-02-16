<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type0, $_REQUEST["condition"]);

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
	$ages[0] = "";

	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];
	$pageSize 	= $_REQUEST["pageSize"]<=0?24:$_REQUEST["pageSize"];

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		if( $_REQUEST["orderBY"]=="age" ) {
			$orderSQ = $_REQUEST["orderSQ"];
			$order_str 	= " ORDER BY ( YEAR(CURDATE()) - if(birth_yy>0, birth_yy, YEAR(CURDATE()) ) )$orderSQ, age $orderSQ";
		} else {
			$orderBY = $_REQUEST["orderBY"]=="aname"?"last_name":$_REQUEST["orderBY"];
			$orderSQ = $_REQUEST["orderSQ"];
			$order_str 	= " ORDER BY $orderBY $orderSQ";
		}
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
		$criteria .= ($criteria==""?"":" AND ") . "b.email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_online = trim($con["sch_online"]);
	if($sch_online != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.online = '" . $sch_online . "'";
	}

	$sch_attend = trim($con["sch_attend"]);
	if($sch_attend != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.attend >= '" . ($sch_attend/100) . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "b.level = '" . $sch_level . "'";
	}

	$sch_onsite = trim($con["sch_onsite"]);
	if($sch_onsite != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.onsite = '" . $sch_onsite . "'";
	}

	$sch_trial = trim($con["sch_trial"]);
	if($sch_trial != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.trial = '" . $sch_trial . "'";
	}

	$sch_new_flag = trim($con["sch_new_flag"]);
	if($sch_new_flag != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.new_flag = '" . $sch_new_flag . "'";
	}


	$sch_lang = trim($con["sch_lang"]);
	if($sch_lang != "") {
		$criteria .= ($criteria==""?"":" AND ") . "b.language = '" . $sch_lang . "'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	$sch_group = trim($con["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . cTYPE::trans($sch_group) . "'";
	}

	$sch_date = trim($con["sch_date"]);
	if($sch_date != "") {
		$sd = cTYPE::datetoint($sch_date);
		$ed = $sd + ( 3600 * 24 - 1);
		$criteria .= ($criteria==""?"":" AND ") . "a.created_time BETWEEN '" . $sd . "' AND '" . $ed . "'";
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

	$sch_eid = trim($con["event_id"]);
	
	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $sch_eid . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];

	/*
	$query_idd = "SELECT aaa0.member_id, aaa0.idd  
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";
	*/

	$query_idd = "SELECT aaa0.member_id, aaa0.idd  
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(idd) as idd FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.idd = aaa1.idd)";

					
	$query_base = "SELECT 	a.id, a.id as enroll_id, a.leader, a.volunteer, a.group_no, a.trial, a.online, a.new_flag, a.paid, a.amt, a.invoice, a.paid_date, a.created_time, 
							b.id as member_id, b.first_name, b.last_name, b.dharma_name,b.alias, b.language, b.age, b.gender, b.phone, b.cell, b.birth_yy, b.city, b.site, 
							aa0.idd, c.title as site_desc 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						LEFT JOIN ($query_idd) aa0 ON (b.id = aa0.member_id)
						LEFT JOIN puti_sites c ON (b.site = c.id)
            			WHERE  a.deleted <> 1 AND b.deleted <> 1 AND a.event_id = '" . $sch_eid . "'  
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
		$rows[$cnt]["leader"] 			= $row["leader"];
		$rows[$cnt]["volunteer"] 		= $row["volunteer"];
		$rows[$cnt]["trial"] 			= $row["trial"];

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["aname"]		= cTYPE::gstr(cTYPE::lfname($names, 13));

		$rows[$cnt]["dharma_name"]	= cTYPE::gstr($row["dharma_name"]);
		
		//$rows[$cnt]["age"] 			= $row["age"]>=1?$ages[$row["age"]]:"";
		//$rows[$cnt]["birth_yy"] 	= $row["birth_yy"]>0?$row["birth_yy"]:"";
		

		$age_range 					= $row["age"]>=1?$ages[$row["age"]]:"";
		$birth_yy 					= $row["birth_yy"]>0? date("Y") - intval($row["birth_yy"]):"";
		$rows[$cnt]["age"] 			= $birth_yy>0?$birth_yy:$age_range;
		

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
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";

        $rows[$cnt]["new"]              = $row["new_flag"]?"Y":"";

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

        
		//$rows[$cnt]["new"]              = "Y";

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
            //if( $row9["id"]==$class_id && $row9["count"]>0 ) $rows[$cnt]["new"] = "";		
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
