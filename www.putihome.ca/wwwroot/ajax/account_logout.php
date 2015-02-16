<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
		$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
		$db->query("UPDATE puti_members SET sess_exp = 0,sess_id = '' WHERE sess_id = '" . $_REQUEST["publicSession"] . "'");
		$_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] = "";

		$response["errorMessage"]	= "";
		$response["errorCode"] = 0;
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
