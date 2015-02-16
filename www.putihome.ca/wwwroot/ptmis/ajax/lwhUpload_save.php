<?php 
ini_set("display_errors", 0);
ini_set('upload_max_filesize', "30MB");
ini_set('post_max_size', "30MB");
include("../../../include/config/config.php");
include($CFG["include_path"] . "/lib/database/database.php");
include($CFG["include_path"] . "/lib/file/uploadFile.php");
include($CFG["include_path"] . "/lib/image/image.php");

$response = array();
try {	
	$upload = new cUPLOAD();
	$aa = $upload->save("original", $_REQUEST["ref_id"]);
	//$aa = $upload->savedb("website_document", 105);
	$image 	= new cIMAGE();
	
	$resize = array();
	$resize["large"] 	= array("ww"=>2048, "hh"=>2048);
	$resize["medium"] 	= array("ww"=>800, 	"hh"=>800);
	$resize["small"] 	= array("ww"=>240, 	"hh"=>240);
	$resize["tiny"] 	= array("ww"=>240, 	"hh"=>240);
	$image->saveByID($aa["filePath"],$_REQUEST["ref_id"], $resize);
	//$img_id = $image->savedb("website_images", 105 , $aa["filePath"], $resize);

	$response["errorCode"] 			= 0;
	$response["data"]["uid"] 		= $_REQUEST["uid"];
	$response["data"]["ref_id"] 	= $_REQUEST["ref_id"];
	$response["data"]["filename"] 	= urldecode($_REQUEST["ufilename"]);
	$response["data"]["filesize"] 	= $_FILES["qqfile"]?$_FILES["qqfile"]["size"]:$_REQUEST["ufilesize"];
	$response["data"]["filePath"] 	= $aa["filePath"];
	$response["data"]["fileUrl"] 	= $aa["fileUrl"];
	$response["data"]["fileSize"] 	= $aa["fileSize"];
	$response["data"]["status"] 	= $aa["status"];
	$response["data"]["doc_id"] 	= $aa["doc_id"];
	$response["data"]["img_id"] 	= $img_id;
	$response["data"]["ts"] 		= time();
	$response["image"]				= $ia;
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