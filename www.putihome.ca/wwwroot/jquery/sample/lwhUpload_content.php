<?php 
ini_set("display_errors", 0);
include("../../../include/config/config.php");
include($CFG["include_path"] . "/lib/database/database.php");
include($CFG["include_path"] . "/lib/file/uploadFile.php");
try {
	$upload = new cUPLOAD();
	$upload->getDocument("website_document", $_REQUEST["doc_id"]);
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