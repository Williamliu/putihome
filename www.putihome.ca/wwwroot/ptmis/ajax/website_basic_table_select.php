<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["table_name"] 		= '{"type":"CHAR", 	"length":255, 	"id": "table_name", 	"name":"Table Name", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$result = $db->query("SELECT * FROM website_basic_table WHERE deleted <> 1 AND filter = '" . $db->quote($_REQUEST["table_name"]) . "' ORDER BY sn DESC, created_time ASC");	
    $cnt = 0;
    $rows = array();
    while($row = $db->fetch($result)) {
        $rows[$cnt]["id"]           = $row["id"];
        $rows[$cnt]["lang_key"]     = $row["lang_key"];
        $rows[$cnt]["title_en"]     = $row["title_en"];
        $rows[$cnt]["title_cn"]     = cTYPE::gstr($row["title_cn"]);
        $rows[$cnt]["desc_en"]      = cTYPE::gstr($row["desc_en"]);
        $rows[$cnt]["desc_cn"]      = cTYPE::gstr($row["desc_cn"]);
        $rows[$cnt]["filter"]       = $row["filter"];
        $rows[$cnt]["status"]       = $row["status"];
        $rows[$cnt]["sn"]           = $row["sn"];
        $cnt++;
    }	
	
	$response["data"] = $rows;
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
