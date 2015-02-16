<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["site_id"] = '{"type":"NUMBER", 	"length":11, 	"id": "site_id", 		"name":"Site ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM puti_sites WHERE id = '" . $_REQUEST["site_id"] . "' ORDER BY sn DESC";
	$result = $db->query( $query );
	$row = $db->fetch($result);
	

	$result_site = $db->query("SELECT * FROM puti_sites_branchs WHERE site_id = '" . $_REQUEST["site_id"] . "'");	
	$site_str = "";
    while($row_site = $db->fetch($result_site) ) {
		$site_str .= ($site_str!=""?",":"") . $row_site["branch_id"]; 
	}
	$response["data"]["branchs"] 	= $site_str;
	
	
	$response["data"]["site_id"] 	= $row["id"];
	$response["data"]["title"] 		= cTYPE::gstr($row["title"]);
	$response["data"]["address"] 	= cTYPE::gstr($row["address"]);
	$response["data"]["tel"] 		= cTYPE::gstr($row["tel"]);
	$response["data"]["email"] 		= cTYPE::gstr($row["email"]);
	$response["data"]["timezone"] 	= cTYPE::gstr($row["timezone"]);
	$response["data"]["cert_prefix"]= cTYPE::gstr($row["cert_prefix"]);

	$response["data"]["site_name_cn"]	= cTYPE::gstr($row["site_name_cn"]);
	$response["data"]["site_name_en"] 	= cTYPE::gstr($row["site_name_en"]);
	$response["data"]["phone_cn"] 		= cTYPE::gstr($row["phone_cn"]);
	$response["data"]["phone_en"] 		= cTYPE::gstr($row["phone_en"]);

	$response["data"]["school_cn"]	= cTYPE::gstr($row["school_cn"]);
	$response["data"]["school_en"] 	= cTYPE::gstr($row["school_en"]);

	$response["data"]["sn"] 		= $row["sn"];
	$response["data"]["status"] 	= $row["status"];
	$response["errorCode"] 			= 0;
	$response["errorMessage"]		= "";
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
