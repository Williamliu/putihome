<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/email/email.php");

$response = array();
try {
	$type["login_email"] = '{"type":"EMAIL", "length":1023,	"id": "login_email", "name":"Email", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	if(	$response["errorCode"] == 0 ) {
		  $db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	  
		  $query = "SELECT id, first_name, last_name, email, password FROM website_admins WHERE deleted <> 1 AND email = '" . trim($db->quote($_REQUEST["login_email"])) . "'";
		  $result = $db->query( $query );
		  if( $db->row_nums($result) > 0 )  {
			  $row = $db->fetch($result);
			  
			  $a["from"] = "admin@van.putiyea.com";
			  $b[0] = "185290926@qq.com";
			  $b[1]	= $row["email"];
			  $e = new cEMAIL($a, $b, "取回网站管理员密码");
			  
			  $body = 'Dear ' . $row["first_name"] . ' ' . $row["last_name"] . '<br><br>'; 
			  $body .= 'Your password is used to login website : <a href="http://van.putiyea.com/admin">http://van.putiyea.com/admin</a><br><br>';
			  $body .= 'Password: ' . $row["password"] . "<br><br>";
			  $body .= 'Please keep this password safe.<br><br>';
			  $body .= 'Thank you and best regards<br><br>';
			  $body .= 'Website Administrator';
			 
			  $e->setBody($body); 
			  $e->send();
			  
			  $response["errorMessage"]	= "<br>密码已经发到你的电子邮箱里,请查收并妥善保管好密码.";
			  $response["errorCode"] 		= 0;
		  } else {
			  $response["errorMessage"]	= "<br>电子邮件'" . trim($_REQUEST["login_email"]) . "'并没有注册成为管理员,请注册申请成为管理员.";
			  $response["errorCode"] 		= 1;
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
