<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query0 = "SELECT a.member_id, a.idd, b.first_name, b.last_name, b.dharma_name, b.alias, b.email, b.phone, b.cell, b.city 
					FROM puti_idd a 
					LEFT JOIN puti_members b ON (a.member_id = b.id) 
					WHERE 1 = 1 AND
					a.idd = '" . $_REQUEST["sch_idd"] . "'";
					//b.site IN " . $admin_user["sites"] . " AND
	
	if( $db->exists($query0) ) {
		$ccc["idd"] = $_REQUEST["sch_idd"];
		$result0 = $db->query($query0);
		$row0 = $db->fetch($result0);

		$names					= array();
		$names["first_name"] 	= $row0["first_name"];
		$names["last_name"] 	= $row0["last_name"];
		$names["dharma_name"]	= $row0["dharma_name"];
		$names["alias"] 		= $row0["alias"];
		
		$msg = "<span style='font-size:24px; font-weight:bold; color:black;'>" . $words["holder"] . ": <span style='color:blue; font-size:48px;'>" . cTYPE::gstr(cTYPE::cname($names, 13)) . "</span>
				<br>" . $words["id card"] . ": <span style='color:blue; font-size:36px;'>" . $_REQUEST["sch_idd"] . "</span> 
				<span style='font-size:24px;'>" . $words["return success see you next time"] . " !</span></span>"; 
		$db->delete("puti_idd", $ccc);		
		$response["data"]["flag"] = 1;
	} else {
		$msg = "<span style='font-size:30px; font-weight:bold; color:red;'>" . $words["id card"] . ": <span style='color:blue; font-size:36px;'>" . $_REQUEST["sch_idd"] . "</span> " . $words["not registered yet please try again"] . ".<span>"; 
		$response["data"]["flag"] = 2;
	}
	$response["data"]["msg"] = $msg;
	$response["data"]["idd"] = $_REQUEST["sch_idd"];
	$response["errorMessage"]	= "";
	$response["errorCode"] 		= 0;

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
