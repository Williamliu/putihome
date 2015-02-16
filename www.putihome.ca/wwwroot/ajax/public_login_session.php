<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	$type["login_name"]	= '{"type":"EMAIL", "length":255, "id": "login_name", "name":"Email", 		"nullable":1}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$email 		= trim($_REQUEST["login_name"]);
	$password 	= trim($_REQUEST["login_pwd"]);
	if( $email == ""  ||  $password == "" ) {
		$response["errorCode"] = 10;
		$response["errorMessage"] = "";
	}

	if(	$response["errorCode"] == 0 ) {
		$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
		$query = "SELECT id FROM puti_members WHERE deleted <> 1 AND status = 1 AND email = '" . $email . "' AND password = '" . $password . "'";
		if( $db->exists($query) ) {
			$result = $db->query($query);
			$row = $db->fetch($result);
			$member_id = $row["id"];
			$login_time = time();
			$sess_id  = md5($member_id . $login_time);
			
			$db->query("UPDATE puti_members SET hits = hits + 1, last_login = '". time() ."', sess_exp = '" . (time() + 3600 * 2) . "', sess_id = '" . $sess_id . "' WHERE id = '" . $member_id . "'");

			$_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] = $sess_id;

			$response["data"]["sess_id"] = $sess_id;
			$response["errorMessage"]	= "";
			$response["errorCode"] = 0;
		} else {

			$query = "SELECT id FROM puti_members WHERE deleted <> 1 AND status = 1 AND email = '" . $email . "'";
			if( $db->exists($query) ) {
				$response["errorMessage"]	= "";
				$response["errorCode"] 		= 11;
			} else {
				$response["errorMessage"] 	= "";
				$response["errorCode"] 		= 12;
			}
		}
	}
	
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
