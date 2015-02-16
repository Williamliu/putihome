<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$orderBY = $_REQUEST["orderBY"]=="flname"?"last_name":$_REQUEST["orderBY"];
	$orderBY = $orderBY=="legal_name"?"legal_last":$orderBY;
	$orderSQ	= $_REQUEST["orderSQ"]==""?"DESC":$_REQUEST["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	// condition here 
	$criteria = "";
	$con = $_REQUEST; 
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
		$criteria .= ($criteria==""?"":" AND ") . "(replace(replace(phone,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%' OR replace(replace(cell,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%')";
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
		$criteria .= ($criteria==""?"":" AND ") . "status = '" . $sch_status . "'";
	}

	$sch_online = trim($con["sch_online"]);
	if($sch_online != "") {
		$criteria .= ($criteria==""?"":" AND ") . "online = '" . $sch_online . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}

	$sch_idd = trim($con["sch_idd"]);
	if($sch_idd != "") {
		$criteria .= ($criteria==""?"":" AND ") . "idd = '" . $sch_idd . "'";
	}

	$sch_flag = trim($con["sch_email_flag"]);
	if($sch_flag != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email_flag = '" . cTYPE::trans($sch_flag) . "'";
	}

	$sch_lang = trim($con["sch_language"]);
	if($sch_lang != "") {
		$criteria .= ($criteria==""?"":" AND ") . "language = '" . cTYPE::trans($sch_lang) . "'";
	}

	$sch_address = trim($con["sch_address"]);
	if($sch_address != "") {
		$criteria .= ($criteria==""?"":" AND ") . "address like '%" . $sch_address . "%'";
	}

	$sch_plate = trim($con["sch_plate_no"]);
	if($sch_plate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "replace(replace(replace(plate_no,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_plate) . "%'";
	}

	$sch_memid = trim($con["sch_memid"]);
	if($sch_memid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "id = '" . $sch_memid . "'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_state = trim($con["sch_state"]);
	if($sch_state != "") {
		$criteria .= ($criteria==""?"":" AND ") . "state like '%" . $sch_state . "%'";
	}

	$sch_country = trim($con["sch_country"]);
	if($sch_country != "") {
		$criteria .= ($criteria==""?"":" AND ") . "country like '%" . $sch_country . "%'";
	}

	$sch_postal = trim($con["sch_postal"]);
	if($sch_postal != "") {
		$criteria .= ($criteria==""?"":" AND ") . "replace(replace(postal,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""),$sch_postal) . "%'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query_base = "SELECT id  
						FROM puti_members  
						LEFT JOIN puti_members_others b ON ( puti_members.id = b.member_id ) 
            			WHERE  deleted <> 1 AND
						site IN " . $admin_user["sites"] . "
						$criteria 
						$order_str";
	
						

	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();

	$cnt = 0;
	$ecnt = 0;
	$ucnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		if( !$db->exists("SELECT member_id FROM puti_email WHERE admin_id = '" . $admin_user["id"] . "' AND member_id= '" . $row["id"] . "'") ) {
				$fields = array();
				$fields["admin_id"] 	= $admin_user["id"];
				$fields["member_id"] 	= $row["id"];
				$fields["created_time"]	= time();
				$db->insert("puti_email", $fields);
				$ecnt++;
		} else {
				$ucnt++;
		}
		$cnt++;	
	}

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "Total Match Records: $cnt<br><br>$ecnt Emails has been added to Email Pool.<br><br>$ucnt Emails already exists.";

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
