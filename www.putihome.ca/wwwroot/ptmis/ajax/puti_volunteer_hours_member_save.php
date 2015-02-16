<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
if( $_REQUEST["dharma_name"]=="" && $_REQUEST["cname"]=="" && $_REQUEST["pname"]=="" && $_REQUEST["en_name"]=="" ) {
	$response["errorCode"] 	= 1;
	$response["errorMessage"] = "Must input volunteer identity for one of the name.";
	echo json_encode($response);
	exit();
}


try {
	$type["pid"]			= '{"type":"NUMBER", "length":11, 	"id": "pid", 		"name":"pid", 			"nullable":0}';
	$type["cname"]			= '{"type":"CHAR", 	"length":255, 	"id": "cname", 		"name":"中文名", 		"nullable":1}';
	$type["pname"]			= '{"type":"CHAR", 	"length":255, 	"id": "pname", 		"name":"拼音名", 		"nullable":1}';
	$type["email"]			= '{"type":"EMAIL", "length":1023, 	"id": "pname", 		"name":"电子邮件", 		"nullable":1}';
	$type["status"]			= '{"type":"NUMBER", "length":1, 	"id": "status", 	"name":"状态", 			"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$fields = array();
	$fields["site"] 		= $admin_user["site"];
	$fields["cname"] 		= cTYPE::utrans($_REQUEST["cname"]);
	$fields["pname"] 		= cTYPE::utrans($_REQUEST["pname"]);
	$fields["en_name"] 		= cTYPE::utrans($_REQUEST["en_name"]);
	$fields["dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
	$fields["gender"] 		= $_REQUEST["gender"];
	$fields["email"] 		= $_REQUEST["email"];
	$fields["phone"] 		= cTYPE::phone($_REQUEST["phone"]);
	$fields["cell"] 		= cTYPE::phone($_REQUEST["cell"]);
	$fields["city"] 		= cTYPE::utrans($_REQUEST["city"]);
	
	$fields["status"] 			= 1;
	$fields["deleted"] 			= 0;
	$fields["created_time"] 	= time();
	$vol_id = $db->insert("puti_volunteer", $fields);

	$departs = explode(",",$_REQUEST["depart"]);	
	foreach($departs as $depart) {
		$fields = array();
		$fields["site"] 			= $admin_user["site"];
		$fields["department_id"] 	= $depart;
		$fields["volunteer_id"] 	= $vol_id;
		$fields["status"] 			= $depart==$_REQUEST["pid"]?1:0;
		$fields["last_updated"] 	= time();
		
		$db->insert("puti_department_volunteer", $fields);
	}
	
	$query2 = "SELECT status FROM puti_department_volunteer WHERE site = '" . $admin_user["site"] . "' AND department_id = '" . $_REQUEST["pid"] . "' AND volunteer_id = '" . $vol_id . "'";
	$result2 = $db->query( $query2 );
	$row2 = $db->fetch($result2);
	if( $db->row_nums($result2) <= 0  ) {
		$fields = array();
		$fields["site"] 			= $admin_user["site"];
		$fields["department_id"] 	= $_REQUEST["pid"];
		$fields["volunteer_id"] 	= $vol_id;
		$fields["status"] 			= 1;
		$fields["last_updated"] 	= time();
		$db->insert("puti_department_volunteer", $fields);
	} 
	
	$response["errorMessage"]	= "<br>Volunteer has been saved to database.";
	$response["errorCode"] 		= 0;

	$response["data"]["pid"]			= $_REQUEST["pid"];
	$response["data"]["department_id"]	= $_REQUEST["pid"];
	$response["data"]["vid"]			= $vol_id;
	$response["data"]["volunteer_id"]	= $vol_id;
	$response["data"]["cname"]			= cTYPE::utrans($_REQUEST["cname"]);
	$response["data"]["pname"]			= cTYPE::utrans($_REQUEST["pname"]);
	$response["data"]["en_name"]		= cTYPE::utrans($_REQUEST["en_name"]);
	$response["data"]["dharma_name"]	= cTYPE::utrans($_REQUEST["dharma_name"]);
	$response["data"]["status"]= 1;

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
