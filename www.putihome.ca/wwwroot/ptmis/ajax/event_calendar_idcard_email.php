<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	//*************** criteria ***********************************************/
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

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_status = trim($con["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.status like '%" . $sch_status . "%'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria

	$query0 = "SELECT a.member_id, a.idd, a.status, b.first_name, b.last_name, b.dharma_name, b.alias, b.email, b.phone, b.cell, b.gender, b.city 
					FROM puti_idd a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE 1 = 1 AND
					b.site IN " . $admin_user["sites"] . "
					$criteria 
					$order_str";
	
	$result0 = $db->query($query0);

	$cnt = 0;
	$ecnt = 0;
	$ucnt = 0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["member_id"] 		= $row0["member_id"];
		$evt_arr["idd"] 			= $row0["idd"];
		$evt_arr["first_name"] 		= cTYPE::gstr($row0["first_name"]);
		$evt_arr["last_name"] 		= cTYPE::gstr($row0["last_name"]);
		$evt_arr["dharma_name"] 	= cTYPE::gstr($row0["dharma_name"]);
		$evt_arr["alias"] 			= cTYPE::gstr($row0["alias"]);
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"];
		$evt_arr["cell"] 			= $row0["cell"];
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);

		if( trim($evt_arr["email"]) != "") {
			$ccc = array();
			$ccc["admin_id"] 	= $admin_user["id"];
			$ccc["email"] 		= trim($evt_arr["email"]);
			
			if( !$db->hasRow("puti_email", $ccc) ) {
				$fields = array();
				$fields["admin_id"]     = $admin_user["id"];							
				$fields["email"] 		= trim($evt_arr["email"]);
				$fields["first_name"] 	= cTYPE::gstr($evt_arr["first_name"]);
				$fields["last_name"] 	= cTYPE::gstr($evt_arr["last_name"]);
				$fields["dharma_name"] 	= cTYPE::gstr($evt_arr["dharma_name"]);
				$fields["alias"] 		= cTYPE::gstr($evt_arr["alias"]);
				$fields["gender"] 		= $evt_arr["gender"];
				$fields["phone"] 		= $evt_arr["phone"];
				$fields["cell"] 		= $evt_arr["cell"];
				$fields["city"] 		= cTYPE::gstr($evt_arr["city"]);
				$fields["created_time"]	= time();
				$db->insert("puti_email",$fields);
				$ecnt++;
			} else {
				$ucnt++;
			}
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
