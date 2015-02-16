<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["dharma_id"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "dharma_id", 	    "name":"Dharma ID", 		"nullable":0}';
	$type["dharma_prefix"] 	= '{"type":"CHAR", 		"length":12, 	"id": "dharma_prefix", 	"name":"Dharma Prefix", 	"nullable":0}';
	$type["dharma_date"] 	= '{"type":"DATE", 		"length":0, 	"id": "dharma_date", 	"name":"Dharma Date", 		"nullable":1}';
	$type["dharma_site"]	= '{"type":"NUMBER", 	"length":1, 	"id": "dharma_site", 	"name":"Location", 		    "nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["dharma_id"] < 0) {
			$fields = array();
			$fields["dharma_prefix"] 	= cTYPE::utrans($_REQUEST["dharma_prefix"]);
			$fields["dharma_date"] 		= cTYPE::datetoint($_REQUEST["dharma_date"]);
			$fields["dharma_site"] 		= $_REQUEST["dharma_site"];
			$dharma_id = $db->insert("puti_dharma", $fields);

			$response["data"]["old_id"] 		= -1;
			$response["data"]["dharma_id"] 		= $dharma_id;
			$response["data"]["dharma_prefix"]	= cTYPE::gstr($_REQUEST["dharma_prefix"]);
			$response["data"]["dharma_date"] 	= $_REQUEST["dharma_date"];
			$response["data"]["dharma_site"] 	= $_REQUEST["dharma_site"];

			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
	} else {
			$fields = array();
			$fields["dharma_prefix"] 	= cTYPE::utrans($_REQUEST["dharma_prefix"]);
			$fields["dharma_date"] 		= cTYPE::datetoint($_REQUEST["dharma_date"]);
			$fields["dharma_site"] 		= $_REQUEST["dharma_site"];
			$db->update("puti_dharma", $_REQUEST["dharma_id"], $fields);

			$response["data"]["old_id"] 		= $_REQUEST["dharma_id"];
			$response["data"]["dharma_id"] 		= $_REQUEST["dharma_id"];
			$response["data"]["dharma_prefix"]	= cTYPE::gstr($_REQUEST["dharma_prefix"]);
			$response["data"]["dharma_date"] 	= $_REQUEST["dharma_date"];
			$response["data"]["dharma_site"] 	= $_REQUEST["dharma_site"];

			$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
			$response["errorCode"] 		= 0;
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
