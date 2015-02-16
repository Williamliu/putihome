<?php 
session_start();
ini_set("display_errors", 1);
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
		$_REQUEST["orderBY"] = $_REQUEST["orderBY"]=="flname"?"last_name":$_REQUEST["orderBY"];
		$_REQUEST["orderBY"] = $_REQUEST["orderBY"]=="legal_name"?"legal_last":$_REQUEST["orderBY"];
		$_REQUEST["orderBY"] = $_REQUEST["orderBY"]=="created_time"?"a.created_time":$_REQUEST["orderBY"];
		$_REQUEST["orderBY"] = $_REQUEST["orderBY"]=="vol_date"?"b.created_time":$_REQUEST["orderBY"];
		$orderBY = $_REQUEST["orderBY"];
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

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_memid = trim($con["sch_memid"]);
	if($sch_memid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "id = '" . $sch_memid . "'";
	}


	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}


	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	} 

	$sch_position = trim($con["sch_position"]);
	if($sch_position != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( past_position like '%" . cTYPE::trans($sch_position) . "%' OR  current_position like '%" . cTYPE::trans($sch_position) . "%')";
	} 

	$sch_resume = trim($con["sch_resume"]);
	if($sch_resume != "") {
		$criteria .= ($criteria==""?"":" AND ") . "resume like '%" . cTYPE::trans($sch_resume) . "%'";
	} 

	$sch_memo = trim($con["sch_memo"]);
	if($sch_memo != "") {
		$criteria .= ($criteria==""?"":" AND ") . "memo like '%" . cTYPE::trans($sch_memo) . "%'";
	} 

	$sch_degree = trim($con["sch_degree"]);
	if($sch_degree != "") {
		$criteria .= ($criteria==""?"":" AND ") . "degree = '" . cTYPE::trans($sch_degree) . "'";
	} 


	$sch_religion = trim($con["sch_religion"]);
	if($sch_religion != "") {
		$criteria .= ($criteria==""?"":" AND ") . "religion = '" . cTYPE::trans($sch_religion) . "'";
	} 

	$sch_vol_type = trim($con["sch_vol_type"]);
	if($sch_vol_type != "") {
		$criteria .= ($criteria==""?"":" AND ") . "vol_type = '" . cTYPE::trans($sch_vol_type) . "'";
	} 


	$sch_email_flag = trim($con["sch_email_flag"]);
	if($sch_email_flag != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.email_flag = '" . cTYPE::trans($sch_email_flag) . "'";
	} 

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . cTYPE::trans($sch_gender) . "'";
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
		$criteria .= ($criteria==""?"":" AND ") . "site in " . $admin_user["sites"];
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}

    $select = "a.id as member_id, a.*, 
			    b.resume, b.memo as vol_memo, b.vol_type, b.status as vol_status, b.deleted as vol_deleted, b.created_time as vol_date";
    $from = "puti_members a	INNER JOIN pt_volunteer b ON (a.id = b.member_id)";
    $where = "a.deleted <> 1  AND b.deleted <> 1 " . $criteria;



	$sch_professional = trim($con["sch_professional"]);
	$sch_depart         = trim($con["sch_depart"]);
	$sch_sdate          = trim($con["sch_sdate"]);
	$sch_schedule_type  = trim($con["sch_schedule_type"]);
	$sch_hh 			= trim($con["sch_hh"]);
	$sch_mm 			= trim($con["sch_mm"]);
	$sch_time			= $sch_hh!=""? $sch_hh . ":" . ($sch_mm!=""?$sch_mm:"00") : "";
	
	if($sch_professional != "") {
		$query_pro = "SELECT distinct aaa0.member_id FROM pt_volunteer_professional aaa0 WHERE aaa0.professional_id = '" . $sch_professional . "'"; 
        $from_pro = " INNER JOIN ($query_pro) aa0 ON (b.member_id = aa0.member_id) ";
        $from .= $from_pro;    
	}


	if($sch_depart != "") {
		$query_dep = "SELECT distinct aaa1.member_id FROM pt_volunteer_depart_current aaa1 WHERE aaa1.depart_id in (" . $sch_depart . ")"; 
        $from_dep = " INNER JOIN ($query_dep) aa1 ON (b.member_id = aa1.member_id) ";
        $from .= $from_dep;    
	}
	
	

	if($sch_sdate != "") {
        if( $sch_schedule_type != "" )
			if($sch_time != "") 
		    	$query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
											WHERE aaa2.schedule_type = '" . $sch_schedule_type . "' AND 
													aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "' AND
													aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
        	else 
		    	$query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
											WHERE 	aaa2.schedule_type = '" . $sch_schedule_type . "' AND 
													aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "'"; 
			
		else 
			if($sch_time != "") 
			    $query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
										WHERE 	aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "' AND 
												aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
			else 
			    $query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
										WHERE aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "'"; 
			
        $from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
        $from .= $from_date;    
	} else {
        if( $sch_schedule_type != "" ) {
    		if($sch_time != "") {
				$query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
											WHERE 	aaa2.schedule_type = '" . $sch_schedule_type . "' AND 
													aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
				$from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			} else {
				$query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
											WHERE	aaa2.schedule_type = '" . $sch_schedule_type . "'"; 
				$from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			}
        } else {
    		if($sch_time != "") {
				$query_date = "SELECT distinct member_id FROM pt_volunteer_schedule aaa2 
											WHERE 	aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
				$from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			} 
		}
	}

	$query_base = "SELECT $select FROM $from WHERE $where $order_str";
	
	
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
		$names["alias"] 		    = $row["alias"];
		$rows[$cnt]["flname"]		=  cTYPE::gstr(cTYPE::lfname($names));

		$rows[$cnt]["legal_name"] 	= $row["legal_last"] . ($row["legal_last"]!=""?", ":"") . $row["legal_first"];

		$rows[$cnt]["dharma_name"] 	= cTYPE::gstr($row["dharma_name"]);
		$rows[$cnt]["gender"] 		= cTYPE::gstr($words[strtolower($row["gender"])]);
        $rows[$cnt]["language"] 	= $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]);

		$rows[$cnt]["phone"] 		= $row["phone"] . ($row["phone"]!=""?"<br>". $row["cell"]:"");

		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);

        $rows[$cnt]["vol_type"] = $db->getTitle($admin_user["lang"], "vw_vol_type", $row["vol_type"]);
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d",$row["created_time"]):"";
		$rows[$cnt]["vol_date"]	    = $row["vol_date"]>0?date("Y-m-d",$row["vol_date"]):"";

		$rows[$cnt]["photo"] 	    = file_exists($CFG["upload_path"] . "/small/" . $row["member_id"] . ".jpg")?"Y":"";
	 
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
