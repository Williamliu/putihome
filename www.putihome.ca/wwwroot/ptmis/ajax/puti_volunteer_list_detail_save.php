<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
if( $_REQUEST["dharma_name"]=="" && $_REQUEST["cname"]=="" && $_REQUEST["pname"]=="" && $_REQUEST["en_name"]=="" ) {
	$response["errorCode"] 	= 1;
	$response["errorMessage"] = "Must input volunteer identity for one of the name.";
	echo json_encode($response);
	exit();
}

try {
	$type["hid"]			= '{"type":"NUMBER", "length":11, 	"id": "hid", 		"name":"Volunteer ID", 	"nullable":0}';
	$type["cname"]			= '{"type":"CHAR", 	"length":255, 	"id": "cname", 		"name":"中文名", 		"nullable":1}';
	$type["pname"]			= '{"type":"CHAR", 	"length":255, 	"id": "pname", 		"name":"拼音名", 		"nullable":1}';
	$type["email"]			= '{"type":"EMAIL", "length":1023, 	"id": "pname", 		"name":"电子邮件", 		"nullable":1}';
	$type["status"]			= '{"type":"NUMBER", "length":1, 	"id": "status", 	"name":"状态", 			"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$fields = array();
	$fields["cname"] 		= cTYPE::utrans($_REQUEST["cname"]);
	$fields["pname"] 		= cTYPE::utrans($_REQUEST["pname"]);
	$fields["en_name"] 		= cTYPE::utrans($_REQUEST["en_name"]);
	$fields["dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
	$fields["gender"] 		= $_REQUEST["gender"];
	$fields["email"] 		= $_REQUEST["email"];
	$fields["phone"] 		= cTYPE::phone($_REQUEST["phone"]);
	$fields["cell"] 		= cTYPE::phone($_REQUEST["cell"]);
	$fields["city"] 		= cTYPE::utrans($_REQUEST["city"]);
	$fields["status"] 		= $_REQUEST["status"];
	$fields["last_updated"] = time();
	$db->update("puti_volunteer", $_REQUEST["hid"], $fields);

	if( $_REQUEST["depart"] == "" ) {
		$db->query("DELETE FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "'");
	} else {
		$db->query("DELETE FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "' AND department_id NOT IN (" . $_REQUEST["depart"] . ")");
		$departs = explode(",",$_REQUEST["depart"]);	
		foreach($departs as $depart) {
			if( !$db->exists("SELECT volunteer_id FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "' AND department_id = '" . $depart . "'") ) {
				$fields = array();
				$fields["site"] 			= $admin_user["site"];
				$fields["department_id"] 	= $depart;
				$fields["volunteer_id"] 	= $_REQUEST["hid"];
				$fields["status"] 			= 0;
				$db->insert("puti_department_volunteer", $fields);
			}
		}
	}
	
	$records = $_REQUEST["record"];
	foreach($records as $record) {
		$fields = array();
		$fields["purpose"] 		= cTYPE::trans($record["purpose"]);
		$fields["work_date"] 	= cTYPE::datetoint($record["work_date"]);
		$fields["work_hour"] 	= $record["work_hour"];
		$db->update("puti_volunteer_hours", $record["id"], $fields);
	}
	
	$response["errorMessage"]	= "<br>Volunteer has been saved to database.";
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
