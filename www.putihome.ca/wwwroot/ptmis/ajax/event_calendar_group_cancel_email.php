<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

					
	$query_base = "SELECT a.id, a.id as enroll_id, a.group_no, a.online,  
						  b.id as member_id, b.first_name, b.last_name, b.dharma_name,b.alias, b.age, b.gender,b.email, b.phone, b.cell, b.city, b.site, c.title as site_desc 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id) 
						LEFT JOIN puti_sites c ON (b.site = c.id)   
            			WHERE  a.deleted = 1 AND b.deleted <> 1 AND event_id = '" . $_REQUEST["event_id"] . "'";
	$query = $query_base;
	
	$result = $db->query( $query );
	$rows = array();

	$cnt = 0;
	$ecnt = 0;
	$ucnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["first_name"] 	= cTYPE::gstr($row["first_name"]);
		$rows[$cnt]["last_name"] 	= cTYPE::gstr($row["last_name"]);
		$rows[$cnt]["dharma_name"] 	= cTYPE::gstr($row["dharma_name"]);
		$rows[$cnt]["alias"] 		= cTYPE::gstr($row["alias"]);
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);

		if( trim($rows[$cnt]["email"]) != "") {
			if( !$db->exists("SELECT id FROM puti_email WHERE admin_id = '" . $admin_user["id"] . "' AND email='" . trim($rows[$cnt]["email"]) . "'") ) {
				$fields = array();
				$fields["admin_id"] 	= $admin_user["id"];
				$fields["email"] 		= $rows[$cnt]["email"];
				$fields["first_name"] 	= $rows[$cnt]["first_name"];
				$fields["last_name"] 	= $rows[$cnt]["last_name"];
				$fields["dharma_name"] 	= $rows[$cnt]["dharma_name"];
				$fields["alias"] 		= $rows[$cnt]["alias"];
				$fields["gender"] 		= $rows[$cnt]["gender"];
				$fields["phone"] 		= $rows[$cnt]["phone"];
				$fields["cell"] 		= $rows[$cnt]["cell"];
				$fields["city"] 		= $rows[$cnt]["city"];
				$fields["created_time"]	= time();
				$db->insert("puti_email",$fields);
				$ecnt++;
			} else {
				$ucnt++;
			}
		}
		$cnt++;	
	}

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "Total Match Records: $cnt<br><br>$ecnt Emails has been added to Email Pool.<br><br>$ucnt Emails already exists.";

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
