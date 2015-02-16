<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "SELECT * FROM pt_department WHERE deleted <> 1 AND id = '" . $_REQUEST["depart_id"] . "'";
	$result = $db->query( $query );
	$item = array();
	$row = $db->fetch($result);
	$item["id"]         = $row["id"];
	$item["parent"]     = $row["parent"];
	$item["lang_key"]   = $row["lang_key"];
	$item["title"]      = $admin_user["lang"]=="en"?$row["title_en"]:cTYPE::gstr($row["title_cn"]);
	$item["description"]= $admin_user["lang"]=="en"?$row["desc_en"]:cTYPE::gstr($row["desc_cn"]);
	$item["title_en"]   = $row["title_en"];
	$item["title_cn"]   = $row["title_cn"];
	$item["desc_en"]    = $row["desc_en"];
	$item["desc_cn"]    = $row["desc_cn"];
	$item["sn"]         = $row["sn"];
	$item["status"]     = $row["status"];

	$item["depart_id"]  = $_REQUEST["depart_id"];
	$item["parent_id"]  = $row["parent"]==NULL?-1:$row["parent"];
	$item["current_id"]  = $row["id"]==NULL?0:$row["id"];


	$response["data"]["depart_id"]	= $_REQUEST["depart_id"]; 
	$response["data"]["depart"] 	= $item; 
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
