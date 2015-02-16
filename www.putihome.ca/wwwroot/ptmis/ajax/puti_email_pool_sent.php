<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/email/email.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$a["from"] 		= "service@van.putiyea.com";
	$a["reply"] 	= "service@van.putiyea.com";
	$e = new cEMAIL($a);
	$e->setSubject(trim($_REQUEST["subject"]));
	
	$query = "SELECT b.* 
							FROM puti_email a
							INNER JOIN puti_members b ON ( a.member_id = b.id )
							WHERE b.status = 1 AND b.deleted <> 1 AND b.site in " . $admin_user["sites"] . " AND admin_id = '" . $admin_user["id"] . "'   
							$criteria 
							$order_str";

	$result = $db->query($query);
	$cnt = 0;
	while( $row = $db->fetch($result) ) {
		$email_body = str_replace(  array("{first_name}", "{last_name}","{dharma_name}","{email}", "{phone}", "{cell}","{city}"), 
									array($row["first_name"], $row["last_name"],$row["dharma_name"],$row["email"],$row["phone"], $row["cell"], $row["city"]), 
									$_REQUEST["content"] );
		$e->setBody(cTYPE::gstr($email_body));
		$e->setSend($row["email"]);
		$e->send();
		$cnt++;
	}

	$response["data"]["cnt"] 	= $cnt;
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "$cnt Emails have been sent successful.";
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
