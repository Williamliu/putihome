<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$result = $db->query("SELECT * FROM website_basic WHERE deleted <> 1 ORDER BY sn DESC");	
    $cnt = 0;
    $rows = array();
    while($row = $db->fetch($result)) {
        $rows[$cnt]["id"]           = $row["id"];
        $rows[$cnt]["lang_key"]     = $row["lang_key"];
        $rows[$cnt]["title_en"]     = $row["title_en"];
        $rows[$cnt]["title_cn"]     = cTYPE::gstr($row["title_cn"]);
        $rows[$cnt]["desc_en"]  = cTYPE::gstr($row["desc_en"]);
        $rows[$cnt]["desc_cn"]  = cTYPE::gstr($row["desc_cn"]);
        $rows[$cnt]["table_name"]   = $row["table_name"];
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
