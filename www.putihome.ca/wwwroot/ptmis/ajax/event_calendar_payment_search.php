<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] = '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Event Select", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
    
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}

	$evt = array();

	$criteria = "";
	$sch_name = trim($_REQUEST["sch_name"]);
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

	$sch_phone = trim($_REQUEST["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(replace(replace(phone,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%' OR replace(replace(cell,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%')";
	}

	$sch_email = trim($_REQUEST["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_group = trim($_REQUEST["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . $sch_group . "'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;

	$sch_payment = trim($_REQUEST["sch_payment"]);

	$query0 = "SELECT b.event_id, b.group_no, b.id as enroll_id, a.id as member_id, a.first_name, a.last_name, a.dharma_name, a.alias, a.gender, a.email, a.phone, a.city, a.site,   
					  b.paid,  b.paid_date, b.amt, b.invoice    
					FROM puti_members a 
					INNER JOIN event_calendar_enroll b ON (a.id = b.member_id) 
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND b.event_id = '" . $_REQUEST["event_id"] . "' $criteria 
					ORDER BY a.first_name, a.last_name";

	$result0 = $db->query($query0);
	$evt = array();
	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_arr["group_no"] 		= $row0["group_no"]>0?$row0["group_no"]:"";
		$evt_arr["enroll_id"] 		= $row0["enroll_id"];
		$evt_arr["event_id"] 		= $row0["event_id"];
		$evt_arr["member_id"] 		= $row0["member_id"];

		$names						= array();
		$names["first_name"] 		= $row0["first_name"];
		$names["last_name"] 		= $row0["last_name"];
		$names["alias"] 			= $row0["alias"];
		$evt_arr["name"] 			= cTYPE::gstr(cTYPE::lfname($names,13));

		$evt_arr["dharma_name"] 	= $row0["dharma_name"]?cTYPE::gstr($row0["dharma_name"]):"";

		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["email"] 			= $row0["email"];
		$evt_arr["phone"] 			= $row0["phone"] . ($row0["cell"]!=""?"<br>" . $row0["cell"]:"");
		$evt_arr["gender"] 			= $row0["gender"];
		$evt_arr["city"] 			= cTYPE::gstr($row0["city"]);
		$evt_arr["site"] 			= cTYPE::gstr($words[strtolower($sites[$row0["site"]])]);


		$flag_pay				= $row0["paid"];
		$evt_arr["paid"] 		= $row0["paid"]?"Y":"";
		$evt_arr["amt"] 		= $row0["amt"]>0?$row0["amt"]:"";
		$evt_arr["invoice"] 	= $row0["invoice"]?$row0["invoice"]:"";
		$evt_arr["paid_date"]	= $row0["paid_date"]>0?date("Y-m-d",$row0["paid_date"]):"";

		$class_id 	= $db->getVal("event_calendar","class_id", array("id"=>$_REQUEST["event_id"]));
		$payonce 	= $db->getVal("puti_class", "payonce", $class_id);
		if( $payonce == "1" ) {
			  $query8 = "SELECT paid, amt, paid_date , invoice 
							  FROM event_calendar  a
							  INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
							  WHERE a.class_id = '" . $class_id . "' AND paid = 1 AND
								  b.member_id = '" . $evt_arr["member_id"] . "' 
							  ORDER BY paid_date DESC, amt DESC";
			  $result8 	= $db->query($query8);
			  $row8 	= $db->fetch($result8); 		

			  $flag_pay				= $row8["paid"];
			  $evt_arr["paid"] 		= $row8["paid"]?"Y":"";
			  $evt_arr["amt"] 		= $row8["amt"]>0?$row8["amt"]:"";
			  $evt_arr["invoice"] 	= $row8["invoice"]?$row8["invoice"]:"";
			  $evt_arr["paid_date"]	= $row8["paid_date"]>0?date("Y-m-d",$row8["paid_date"]):"";
		}

		if( $sch_payment != "") {
			 if( intval($sch_payment) == intval($flag_pay) ) {
				$evt[$cnt0]			= $evt_arr;
				$cnt0++;
			 }
		} else {
			$evt[$cnt0]				= $evt_arr;
			$cnt0++;
		}
	}

	$response["data"]["evt"] = $evt;
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
