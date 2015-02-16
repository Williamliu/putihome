<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");
include_once($CFG["include_path"] . "/lib/email/email.php");

$response = array();
try {
	$type["login_email"] = '{"type":"EMAIL", "length":1023,	"id": "login_email", "name":"Email", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	if(	$response["errorCode"] == 0 ) {
		  $db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	  
		  $query = "SELECT id, first_name, last_name, email, password FROM puti_members WHERE deleted <> 1 AND email = '" . trim($_REQUEST["login_email"]) . "'";
		  $result = $db->query( $query );
		  if( $db->row_nums($result) > 0 )  {
			  $row = $db->fetch($result);
			  $member_id = $row["id"];
			  $login_time = time();
			  $sess_id  = md5($member_id . $login_time);
			  
			  $fields = array();
			  $fields["password_link"] 	= $sess_id;
			  $fields["password_exp"] 	= time() + 3600 * 2;
			  $db->update("puti_members", $member_id , $fields);
			  
			  $a["from"] = "service@putihome.ca";
			  $b[1]	= $row["email"];
			  $e = new cEMAIL($a, $b, "Reset Password");
			  
			  $name 		= $row["first_name"] . ' ' . $row["last_name"];
			  $reset_link 	= $CFG["http"] . $CFG["web_domain"] . '/reset_password.php?sessid=' . $sess_id;
			  
			  $body =  cTYPE::gstr( str_replace(array("{0}", "{1}"), array($name, $reset_link), $words["reset password email body"]) );
			  
			   
			 
			  $e->setBody($body); 
			  $e->send();
			  
			  $response["errorMessage"]	= "";
			  $response["errorCode"] 		= 0;
		  } else {
			  $response["errorMessage"]	= $words["email has not been registered"];
			  $response["errorCode"] 	= 1;
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
