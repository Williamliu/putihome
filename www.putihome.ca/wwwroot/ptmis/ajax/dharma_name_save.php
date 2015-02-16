<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$list = $_REQUEST["olist"];
	foreach($list as $val) {
		$fields = array();
		$fields["apply_date"] = cTYPE::datetoint( $val["apply_date"] );
		$fields["temp_dharma_name"] = cTYPE::utrans($val["temp_dharma_name"]);
		$fields["temp_dharma_pinyin"] = cTYPE::uword($val["temp_dharma_pinyin"]);
		$db->update("puti_members", $val["member_id"], $fields);		
	}

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
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
