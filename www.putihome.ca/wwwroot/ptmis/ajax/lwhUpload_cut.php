<?php 
ini_set("display_errors", 0);
include("../../../include/config/config.php");
include($CFG["include_path"] . "/lib/database/database.php");
include($CFG["include_path"] . "/lib/image/image.php");

$response = array();
try {
	$image 	= new cIMAGE();
	$resize = array();
	//$resize["medium"] 	= array("ww"=>120, 	"hh"=>160);;
	$resize["small"] 	= array("ww"=>110, 	"hh"=>152);;
	$image->image_cut($_REQUEST["ref_id"],$_REQUEST["img_ww"],$_REQUEST["img_hh"],$_REQUEST["img_left"],$_REQUEST["img_top"], $resize );
	
	$smm = $CFG["upload_path"] . "/small/" . $_REQUEST["ref_id"] . ".jpg";
	$tmm = $CFG["upload_path"] . "/tiny/" . $_REQUEST["ref_id"] . ".jpg";
	
	$image->image_resize($smm, $tmm, 190, 260);
	
	//$img_id = $image->savedb("website_images", 105 , $aa["filePath"], $resize);

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