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
	$upload = new cUPLOAD("uploads");
	$aa = $upload->savedb("website_document", 105);
	$image 	= new cIMAGE("uploads");
	
	$resize = array();
	$resize["large"] 	= array("ww"=>800, 	"hh"=>800);
	$resize["medium"] 	= array("ww"=>300, 	"hh"=>300);;
	$resize["small"] 	= array("ww"=>200, 	"hh"=>200);;
	
	$img_id = $image->savedb("website_images", 105 , $aa["filePath"], $resize);

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