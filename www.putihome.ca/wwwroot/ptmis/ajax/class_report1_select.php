<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {
	
	$type1["sch_class"] = '{"type":"NUMBER", "length":11, "id": "class", "name":"Select a Class", "nullable":0}';
	cTYPE::validate($type1, $_REQUEST["condition"]);
	
	cTYPE::check();
	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];

	$pageSize 	= $_REQUEST["condition"]["pageSize"]<=0?24:$_REQUEST["condition"]["pageSize"];
	$orderBY	= $_REQUEST["condition"]["orderBY"]==""?"last_name":$_REQUEST["condition"]["orderBY"];
	$orderSQ	= $_REQUEST["condition"]["orderSQ"]==""?"ASC":$_REQUEST["condition"]["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";

	$con = $_REQUEST["condition"]; 
	
	// condition here 
	$sd = cTYPE::datetoint($con["sch_sdate"]);
	$ed = cTYPE::datetoint($con["sch_edate"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "start_date >= '" . $sd . "' AND end_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "start_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "end_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	
	
	
	$criteria = "";
	$sch_111 = trim($con["sch_class"]);
	if($sch_111 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "class_id = '" . $sch_111 . "'";
	} else {
		$criteria .= ($criteria==""?"":" AND ") . "class_id = '-1'";
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

	$sch_rate = trim($con["sch_rate"]);
	if($sch_rate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "attend >= '" . ($sch_rate/100) . "'";
	}

	$sch_555 = trim($con["sch_name"]);
	if($sch_555 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(first_name like '%" . $sch_555 . "%' OR last_name like '%" . $sch_555 . "%' OR dharma_name like '%" . $sch_555 . "%' OR alias like '%" . $sch_555 . "%')";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
    /*
	$query_base = "SELECT a.title, a.start_date, a.end_date,
						  b.id, b.signin, b.graduate, b.cert, b.attend, 
						  c.first_name, c.last_name, c.dharma_name, c.alias, c.legal_first, c.legal_last, c.gender, c.email, c.phone, c.cell, c.city 
						FROM event_calendar a 
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id)  
						INNER JOIN puti_members c ON (b.member_id = c.id) 
						WHERE  a.deleted <> 1 AND b.deleted <> 1  AND c.deleted <> 1 AND
							   a.site IN " . $admin_user["sites"] . " AND a.branch IN " . $admin_user["branchs"] . "
						$ccc 
						$criteria 
						$order_str";
	*/	

	$query_base = "SELECT a.id as event_id, COUNT(b.id) as enroll_total, SUM(b.unauth) as unauth_total, SUM(trial) as trial_total, SUM(b.signin) as sign_total, SUM(b.graduate) as grad_total, SUM(b.cert) as cert_total, AVG(b.attend) as attr_total, 
						  c.id as member_id, c.first_name, c.last_name, c.dharma_name, c.alias, c.legal_first, c.legal_last, c.gender, c.email, c.phone, c.cell, c.city, c.member_title 
						FROM event_calendar a 
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id)  
						INNER JOIN 
						( SELECT aa0.*, bb0.title as member_title FROM puti_members aa0 LEFT JOIN puti_info_title bb0 ON ( aa0.level = bb0.id )  ) c ON (b.member_id = c.id) 
						WHERE  a.deleted <> 1 AND b.deleted <> 1  AND 
							   a.site IN " . $admin_user["sites"] . " AND a.branch IN " . $admin_user["branchs"] . "
						$ccc 
						$criteria 
						GROUP BY  c.id, c.first_name, c.last_name, c.dharma_name, c.alias, c.legal_first, c.legal_last, c.gender, c.email, c.phone, c.cell, c.city 
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
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["member_id"] 	= $row["member_id"];
		
		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$rows[$cnt]["first_name"]	=  cTYPE::gstr(cTYPE::lfname($names,13));
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"]?cTYPE::gstr($row["dharma_name"]):'';

		$rows[$cnt]["member_title"] = $row["member_title"]?$row["member_title"]:"";

		/*
        $names						= array();
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];
		$rows[$cnt]["legal_first"]	= cTYPE::gstr(cTYPE::cname($names));
        */

		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		//$rows[$cnt]["enroll_total"] = $row["enroll_total"]?$row["enroll_total"]:"";
		//$rows[$cnt]["unauth_total"] = $row["unauth_total"]?$row["unauth_total"]:"";
		//$rows[$cnt]["trial_total"] 	= $row["trial_total"]?$row["trial_total"]:"";
		//$rows[$cnt]["sign_total"] 	= $row["sign_total"]?$row["sign_total"]:"";
		//$rows[$cnt]["sign_total"] 	= $row["sign_total"]?$row["sign_total"]:"";
		$rows[$cnt]["grad_total"] 	= $row["grad_total"]?$row["grad_total"]:"";
		$rows[$cnt]["cert_total"] 	= $row["cert_total"]?$row["cert_total"]:"";
		//$rows[$cnt]["attr_total"] 	= $row["attr_total"]>0?round($row["attr_total"]*100)."%":"";
		
		//if($con["details"]=="1") {
			  $query11 = "SELECT  a.id as event_id, a.title, a.start_date, a.end_date, 
								  b.id as enroll_id, b.signin, b.graduate, b.cert, b.attend, b.unauth, b.trial  
								  FROM event_calendar a 
								  INNER JOIN event_calendar_enroll b ON (a.id = b.event_id)  
								  INNER JOIN puti_members c ON (b.member_id = c.id) 
								  WHERE  a.deleted <> 1 AND b.deleted <> 1  AND 
										 b.member_id = '" . $row["member_id"] . "' AND 
										 a.site IN " . $admin_user["sites"] . " AND a.branch IN " . $admin_user["branchs"] . "
								  $ccc 
								  $criteria 
								  ORDER BY a.start_date DESC";
			  $result11 = $db->query($query11);
			  $cnt1 = 0;
			  $evts = array();
              $member_total_checkin = 0;
              $member_total_attend = 0;
              $member_total_leave = 0;
			  while( $row11 = $db->fetch($result11) ) {
                  
                  $result_ck = $db->query("SELECT SUM(checkin) as total_checkin FROM event_calendar_date WHERE event_id = '" . $row11["event_id"] . "'");
                  $row_ck = $db->fetch($result_ck);
                  $total_checkin = $row_ck["total_checkin"];


				  $evts[$cnt1]["title"] =  cTYPE::gstr($row11["title"]);
				  $period = ($row11["start_date"]>0?date("Y, m-d",$row11["start_date"]):"long long ago") . " ~ " . ($row11["end_date"]>0?date("m-d",$row11["end_date"]):"Today");
				  $evts[$cnt1]["title"] 		.= " [" . $period . "]";
				  $evts[$cnt1]["event_date"] 	= cTYPE::gstr($words["class date"]) . ": " . $period;
				  //$evts[$cnt1]["enroll"] 		= "Y";
				  //$evts[$cnt1]["unauth"] 		= $row11["unauth"]?"Y":"";
				  //$evts[$cnt1]["trial"] 		= $row11["trial"]?"Y":"";
				  //$evts[$cnt1]["signin"] 		= $row11["signin"]?"Y":"";
				  $evts[$cnt1]["graduate"] 	= $row11["graduate"]?"1":"";
				  $evts[$cnt1]["cert"] 		= $row11["cert"]?"1":"";
				  $evts[$cnt1]["attend"] 		= $row11["attend"]>0?round($row11["attend"]*100)."%":"";

				  
		            $querya 	= "SELECT   SUM(IF( b.status=2 OR b.status=8, 1, 0)) as total_attend,
								            SUM(IF( b.status=4, 1 , 0)) as total_leave 
							            FROM event_calendar_enroll a 
							            INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
                    					INNER JOIN  event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin)
							            WHERE a.deleted <> 1 AND a.event_id = '" . $row11["event_id"] . "' AND a.id = '" . $row11["enroll_id"] . "'";
		            $resulta	= $db->query($querya);
		            $rowa       = $db->fetch($resulta);

				    $evts[$cnt1]["total_checkin"] 		= intval($total_checkin);
				    $evts[$cnt1]["total_attend"] 		= $rowa["total_attend"]?$rowa["total_attend"]:"";
				    $evts[$cnt1]["total_leave"] 		= $rowa["total_leave"]?$rowa["total_leave"]:"";

                  
                  $member_total_checkin += intval($total_checkin);
                  $member_total_attend += intval($rowa["total_attend"]);
                  $member_total_leave += intval($rowa["total_leave"]);

                  $cnt1++;
			  }
        	  $rows[$cnt]["total_checkin"] 	= $member_total_checkin?$member_total_checkin:"";
        	  $rows[$cnt]["total_attend"] 	= $member_total_attend?$member_total_attend:"";
        	  $rows[$cnt]["total_leave"] 	= $member_total_leave?$member_total_leave:"";
			  
			  $rows[$cnt]["attr_total"] 	= $member_total_checkin>0 && $member_total_attend>0?round($member_total_attend/$member_total_checkin*100)."%":"";			  
              
			  $rows[$cnt]["evts"] = $evts;
		//}
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
