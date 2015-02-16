<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT b.cname, b.pname, b.en_name, b.dharma_name, b.email, b.gender, b.phone, b.cell, b.city 
						FROM puti_department_volunteer a 
						INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
						WHERE b.deleted <> 1 AND a.department_id = '" . $_REQUEST["pid"] . "' AND 
							  a.status = 1 AND
							  b.site IN " . $admin_user["sites"] . " 
						ORDER BY a.status DESC, b.en_name, b.pname, b.dharma_name, b.cname";
	$result = $db->query($query);	
	$cnt=0;
	$ecnt=0;
	$ucnt=0;
	while($row = $db->fetch($result)) {
		if( trim($row["email"]) != "") {
			if( !$db->exists("SELECT id FROM puti_email WHERE admin_id = '" . $admin_user["id"] . "' AND email='" . trim($row["email"]) . "'") ) {
				$fields = array();
				$tmp = explode(" ", $row["en_name"]);
				$fields["admin_id"]     = $admin_user["id"];							
				$fields["first_name"] 	= trim($tmp[0]);
				$fields["last_name"] 	= trim($tmp[1]);
				$fields["dharma_name"] 	= $row["dharma_name"];
				$fields["email"] 		= trim($row["email"]);
				$fields["gender"] 		= $row["gender"];
				$fields["phone"] 		= $row["phone"];
				$fields["cell"] 		= $row["cell"];
				$fields["city"] 		= $row["city"];
				$fields["created_time"] = time();
				$db->insert("puti_email", $fields);
				$ecnt++;
			} else {
				$ucnt++;
			}
		}
		$cnt++;
	}
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "Total Match Records: $cnt<br><br>$ecnt Emails has been added to Email Pool.<br><br>$ucnt Emails already exists.";

	$response["data"]["pid"] 	= $_REQUEST["pid"];

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
