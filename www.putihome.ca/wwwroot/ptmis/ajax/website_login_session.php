<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	$type["login_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "login_name", 	"name":"User Name", 	"nullable":0}';
	$type["login_pwd"] 			= '{"type":"CHAR", 		"length":15, 	"id": "login_pwd", 		"name":"Password", 		"nullable":0}';
	$type["platform"] 			= '{"type":"CHAR", 		"length":255, 	"id": "platform", 		"name":"Platform", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	if(	$response["errorCode"] == 0 ) {
			$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
				// normal user login
				$query = "SELECT id FROM website_admins WHERE deleted <> 1 AND status = 1 AND ( user_name = '" . trim($db->quote($_REQUEST["login_name"])) . "' OR email = '" . trim($db->quote($_REQUEST["login_name"])) . "') AND password = '" . trim($db->quote($_REQUEST["login_pwd"])) . "'";
				$result = $db->query( $query );
				if( $db->row_nums($result) > 0 )  {
					$row = $db->fetch($result);
					$admin_id = $row["id"];
					$login_time = time();
					$sess_id  = md5($admin_id . $login_time);
					
					$_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"] = $sess_id;
					
					$fields = array();
					$fields["admin_id"] 	= $admin_id;
					$fields["session_id"] 	= $sess_id;
					$fields["platform"] 	= $_REQUEST["platform"];
					$fields["ip_address"] 	= $_SERVER['REMOTE_ADDR']; 
					$fields["login_time"] 	= $login_time;
					$fields["last_updated"] = $login_time;
					$fields["deleted"] 		= 0;
	  
					$sid = $db->insert("website_session", $fields);
					$db->query("UPDATE website_admins SET hits = hits + 1, login_count = 0, last_login = '". time() ."' WHERE deleted <> 1 AND status = 1 AND id = '" . $db->quote($admin_id) . "'");	
					$response["data"]["sess_id"] = $sess_id;
					$response["errorMessage"]	= "<br>Login successful.";
					$response["errorCode"] = 0;
				} else {
					$response["errorMessage"]	= "<br>Login with the user '" . cTYPE::utrans(trim($_REQUEST["login_name"])) . "' is invalid or wrong password.";
					$response["errorCode"] = 1;

					$query = "UPDATE website_admins SET login_count = login_count + 1 WHERE deleted <> 1  AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["login_name"]))) . "' OR email = '" . trim($db->quote($_REQUEST["login_name"])) . "')";
					$db->query( $query );
					
					$query = "SELECT id, login_count FROM website_admins WHERE deleted <> 1 AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["login_name"]))) . "' OR email = '" . trim($db->quote($_REQUEST["login_name"])) . "')";
					$result_count = $db->query( $query );
					$row_count =  $db->fetch($result_count);
					if($row_count["login_count"] >= 3) {
						$query = "UPDATE website_admins SET status = 0 WHERE deleted <> 1 AND status = 1 AND ( user_name = '" . cTYPE::utrans(trim($db->quote($_REQUEST["login_name"]))) . "' OR email = '" . trim($db->quote($_REQUEST["login_name"])) . "')";
						$db->query( $query );
						$response["errorMessage"]	= "<br>The user account '" . cTYPE::utrans(trim($_REQUEST["login_name"])) . "' has tried to login " . $row_count["login_count"] . " times!<br><br>User account has been locked.<br><br>Please contact system administrator.";
						$response["errorCode"] = 1;
					} 
				}
				// end of normal user login
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
