<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["site_id"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "site_id", 	"name":"Site ID", 		"nullable":0}';
	$type["title"] 			= '{"type":"CHAR", 		"length":255, 	"id": "title", 			"name":"Title", 		"nullable":0}';
	$type["address"] 		= '{"type":"CHAR", 		"length":1023, 	"id": "address", 		"name":"Address", 		"nullable":1}';
	$type["phone"] 			= '{"type":"CHAR", 		"length":1023, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["email"] 			= '{"type":"EMAIL", 	"length":255, 	"id": "email", 			"name":"Email", 		"nullable":1}';
	$type["timezone"] 		= '{"type":"CHAR", 		"length":255, 	"id": "timezone", 		"name":"Timezone", 		"nullable":0}';
	$type["cert_prefix"]	= '{"type":"CHAR", 		"length":31, 	"id": "cert_prefix", 	"name":"Cert.Prefix", 	"nullable":1}';
	$type["status"]			= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 		"nullable":0}';
	$type["sn"]				= '{"type":"NUMBER", 	"length":11, 	"id": "sn", 			"name":"SN序号", 			"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$site_id = $_REQUEST["site_id"]; 
	if($_REQUEST["site_id"] < 0) {
			$fields = array();
			$fields["title"] 			= cTYPE::utrans($_REQUEST["title"]);
			$fields["address"] 			= trim($_REQUEST["address"]);
			$fields["tel"] 				= trim($_REQUEST["tel"]);
			$fields["email"] 			= trim($_REQUEST["email"]);
			$fields["timezone"] 		= trim($_REQUEST["timezone"]);
			$fields["cert_prefix"] 		= trim($_REQUEST["cert_prefix"]);
			
			$fields["site_name_cn"] 	= cTYPE::utrans($_REQUEST["site_name_cn"]);
			$fields["site_name_en"] 	= trim($_REQUEST["site_name_en"]);
			$fields["phone_cn"] 		= trim($_REQUEST["phone_cn"]);
			$fields["phone_en"] 		= trim($_REQUEST["phone_en"]);

			$fields["school_cn"] 	= cTYPE::utrans($_REQUEST["school_cn"]);
			$fields["school_en"] 	= trim($_REQUEST["school_en"]);

			$fields["sn"] 				= trim($_REQUEST["sn"])?trim($_REQUEST["sn"]):0;
			$fields["status"] 			= $_REQUEST["status"];
			$site_id = $db->insert("puti_sites", $fields);
			
			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
	} else {
			$fields = array();
			$fields["title"] 			= cTYPE::utrans($_REQUEST["title"]);
			$fields["address"] 			= trim($_REQUEST["address"]);
			$fields["tel"] 				= trim($_REQUEST["tel"]);
			$fields["email"] 			= trim($_REQUEST["email"]);
			$fields["timezone"] 		= trim($_REQUEST["timezone"]);
			$fields["cert_prefix"] 		= trim($_REQUEST["cert_prefix"]);

			$fields["site_name_cn"] 	= cTYPE::utrans($_REQUEST["site_name_cn"]);
			$fields["site_name_en"] 	= trim($_REQUEST["site_name_en"]);
			$fields["phone_cn"]			= trim($_REQUEST["phone_cn"]);
			$fields["phone_en"] 		= trim($_REQUEST["phone_en"]);

			$fields["school_cn"] 	= cTYPE::utrans($_REQUEST["school_cn"]);
			$fields["school_en"] 	= trim($_REQUEST["school_en"]);

			
			$fields["sn"] 				= trim($_REQUEST["sn"])?trim($_REQUEST["sn"]):0;
			$fields["status"] 			= $_REQUEST["status"];
			$db->update("puti_sites", $_REQUEST["site_id"], $fields);
	}

	$db->query("DELETE FROM puti_sites_branchs WHERE site_id = '" . $site_id . "'");
	$sites_array = $_REQUEST["branchs"]!=""?explode(",",$_REQUEST["branchs"]):array();
	foreach($sites_array as $site) {
		$fields = array();
		$fields["site_id"] 		= $site_id;
		$fields["branch_id"] 	= $site;
		$db->insert("puti_sites_branchs", $fields);
	}
	
	$response["data"]["old_id"] 		= $_REQUEST["site_id"];
	$response["data"]["site_id"] 		= $site_id;
	$response["data"]["title"] 			= cTYPE::utrans($_REQUEST["title"]);
	$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
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
