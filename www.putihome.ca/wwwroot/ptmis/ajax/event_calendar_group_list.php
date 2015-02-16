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
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query1 = "SELECT a.id as enroll_id, a.leader, a.volunteer, b.id, b.first_name, b.last_name, b.dharma_name, b.alias, b.gender, b.email, b.phone, b.cell, b.city, b.site, c.title as site_desc, a.group_no	
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
            			LEFT JOIN puti_sites c ON (b.site = c.id) 
						WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND 
						a.event_id = '" . $_REQUEST["event_id"] . "' AND a.group_no > 0 
						ORDER BY a.group_no, a.leader DESC, a.volunteer DESC, a.created_time";
	$result1 = $db->query($query1);
	$group = array();
	$cnt1 = 0;
	while( $row1 = $db->fetch($result1) ) {
		$grp_no 				= $row1["group_no"];
		$grp_arr 				= array();
		$grp_arr["id"] 			= $row1["id"];
		$grp_arr["enroll_id"] 	= $row1["enroll_id"];
		$grp_arr["leader"] 		= $row1["leader"];
		$grp_arr["volunteer"] 	= $row1["volunteer"];

		$names					= array();
		$names["first_name"] 	= $row1["first_name"];
		$names["last_name"] 	= $row1["last_name"];
		$names["dharma_name"] 	= $row1["dharma_name"];
		$names["alias"] 		= $row1["alias"];
		$grp_arr["name"]		= cTYPE::gstr(cTYPE::lfname($names, 13));

		$grp_arr["gender"] 		= $row1["gender"];
		$grp_arr["phone"] 		= $row1["phone"];
		$grp_arr["cell"] 		= $row1["cell"];
		$grp_arr["city"] 		= cTYPE::gstr($row1["city"]);
		$grp_arr["site"] 		= $row1["site"];
		$grp_arr["site_desc"] 	= cTYPE::gstr($row1["site_desc"]);
		$group[$grp_no][]		= $grp_arr;
	}
	$response["data"]["group"] = $group;

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
