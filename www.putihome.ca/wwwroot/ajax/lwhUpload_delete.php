<?php 
ini_set("display_errors", 0);
include("../../include/config/config.php");
include($CFG["include_path"] . "/lib/database/database.php");
include($CFG["include_path"] . "/lib/image/image.php");

$response = array();
try {	
	$image 	= new cIMAGE();
	$image->image_del($_REQUEST["ref_id"]);

	$response["errorCode"] 			= 0;
	$response["data"]["ref_id"] 	= $_REQUEST["ref_id"];
	$response["data"]["ts"] 		= time();
	echo json_encode($response);

} catch(cERR $e) {
	$response						= $e->detail();
	$response["data"]["uid"] 		= $_REQUEST["uid"];
	$response["data"]["ref_id"] 	= $_REQUEST["ref_id"];
	$response["data"]["filename"] 	= urldecode($_REQUEST["ufilename"]);
	$response["data"]["filesize"] 	= $_FILES["qqfile"]?$_FILES["qqfile"]["size"]:$_REQUEST["ufilesize"];
	echo json_encode($response);
} catch(Exception $e ) {
	$response["data"]["uid"] 	= $_REQUEST["uid"];
	$response["data"]["ref_id"] = $_REQUEST["ref_id"];
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}
exit();
?>