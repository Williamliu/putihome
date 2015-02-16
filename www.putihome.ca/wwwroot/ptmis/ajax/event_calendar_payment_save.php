<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Event Select", "nullable":0}';
	$type["enroll_id"] 	= '{"type":"NUMBER", "length":11, "id": "enroll_id", "name":"Member", "nullable":0}';
	$type["amount"] 	= '{"type":"NUMBER", "length":11, "id": "amount",	"name":"Amount", "nullable":0}';
	$type["invoice"] 	= '{"type":"CHAR", 	"length":31, "id": "invoice",	"name":"Invoice", "nullable":1}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
    
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();
	
	$pamt = trim($_REQUEST["amount"]);
	$fields = array();
	$fields["paid"] 		= 1;
	$fields["paid_date"] 	= time();
	$fields["amt"] 			= $pamt;
	$fields["invoice"] 		= trim($_REQUEST["invoice"]);
	
	$db->update("event_calendar_enroll",$_REQUEST["enroll_id"], $fields);
	$query9 = "UPDATE event_calendar_enroll 
						SET paid = 0, paid_date = 0, amt = 0 , invoice = '' 
						WHERE id = '" . $_REQUEST["enroll_id"] . "' AND amt <= 0"; 
	$db->query($query9);
	
	$query0 = "SELECT b.event_id, b.group_no, b.id as enroll_id, a.id as member_id, a.first_name, a.last_name, a.dharma_name, a.gender, a.email, a.phone, a.city,
					  b.paid,  b.paid_date, b.amt, b.invoice 
					FROM puti_members a 
					INNER JOIN event_calendar_enroll b ON (a.id = b.member_id) 
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
						 b.event_id = '" . $_REQUEST["event_id"] . "' AND
						 b.id = '" . $_REQUEST["enroll_id"]  . "'   
					ORDER BY a.first_name, a.last_name";

	$result0 = $db->query($query0);
	$row0 = $db->fetch($result0);
	$evt_arr = array();
	$evt_arr["enroll_id"] 	= $row0["enroll_id"];
	$evt_arr["member_id"] 	= $row0["member_id"];

	$evt_arr["paid"] 		= $row0["paid"]?"Y":"";
	$evt_arr["paid_date"] 	= $row0["paid_date"]>0?date("Y-m-d",$row0["paid_date"]):"";
	$evt_arr["amt"] 		= $row0["amt"]>0?"$".round($row0["amt"],2):"";
	$evt_arr["invoice"] 	= $row0["invoice"]?$row0["invoice"]:"";

	
	if( $evt_arr["paid"]=="" ) {	
		$class_id = $db->getVal("event_calendar","class_id", array("id"=>$_REQUEST["event_id"]));
		$query8 = "SELECT paid, amt, paid_date 
						FROM event_calendar  a
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id) 
						WHERE a.class_id = '" . $class_id . "' AND paid = 1 AND 
							  b.member_id = '" . $evt_arr["member_id"] . "' 
						ORDER BY paid_date DESC, amt DESC";
		$result8 	= $db->query($query8);
		$row8 		= $db->fetch($result8); 		
		$evt_arr["paid"] 		= $row8["paid"]?"Y":"";
		$evt_arr["paid_date"] 	= $row8["paid_date"]>0?date("Y-m-d",$row8["paid_date"]):"";
		$evt_arr["amt"] 		= $row8["amt"]>0?"$".round($row8["amt"],2):"";
		$evt_arr["invoice"] 	= $row8["invoice"]?$row8["invoice"]:"";
	}
	$response["data"]["evt"] = $evt_arr;
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
