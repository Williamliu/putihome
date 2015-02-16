<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	
	$depArr = depart_items(0);

	$response["data"]["depart_id"]	= $_REQUEST["depart_id"]; 
	$response["data"]["departs"] 	= $depArr; 
	$response["errorCode"] 		    = 0;
	$response["errorMessage"]	    = "";
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

function depart_items($parent) {
    global $CFG;
    global $admin_user;
    $db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
    $query = "SELECT * FROM pt_department WHERE deleted <> 1 AND parent = $parent  ORDER BY sn DESC, created_time ASC";
	$result = $db->query( $query );
    $depArr = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
        $item = array();
        $item["id"]         = $row["id"];
        $item["parent"]     = $row["parent"];
        $item["lang_key"]   = $row["lang_key"];
        $item["title"]      = $admin_user["lang"]=="en"?$row["title_en"]:cTYPE::gstr($row["title_cn"]);
        $item["description"]= $admin_user["lang"]=="en"?$row["desc_en"]:cTYPE::gstr($row["desc_cn"]);
        $item["title_en"]   = cTYPE::gstr($row["title_en"]);
        $item["title_cn"]   = cTYPE::gstr($row["title_cn"]);
        $item["desc_en"]    = cTYPE::gstr($row["desc_en"]);
        $item["desc_cn"]    = cTYPE::gstr($row["desc_cn"]);
        $item["sn"]         = $row["sn"];
        $item["status"]     = $row["status"];
        $item["departs"]    = depart_items($row["id"]);
        $depArr[$cnt++]     = $item;
    }
    return $depArr;
}

?>
