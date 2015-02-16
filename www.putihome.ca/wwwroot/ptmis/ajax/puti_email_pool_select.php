<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type0["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	cTYPE::validate($type0, $_REQUEST);
	
	$type1["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type1, $_REQUEST["condition"]);
	
	cTYPE::check();
	$pageNo 	= $_REQUEST["pageNo"]<=0?1:$_REQUEST["pageNo"];

	$pageSize 	= $_REQUEST["condition"]["pageSize"]<=0?24:$_REQUEST["condition"]["pageSize"];
	$orderBY	= $_REQUEST["condition"]["orderBY"]==""?"created_time":$_REQUEST["condition"]["orderBY"];
	$orderSQ	= $_REQUEST["condition"]["orderSQ"]==""?"DESC":$_REQUEST["condition"]["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	// condition here 
	$criteria = "";
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_lang = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
	$langs =array();
	while($row_lang = $db->fetch($result_lang)) {
		$langs[$row_lang["id"]] =  cTYPE::gstr($words[$row_lang["title"]]);
	}
	$langs[0] = "";



	$query_base = "SELECT b.* 
							FROM puti_email a
							INNER JOIN puti_members b ON ( a.member_id = b.id )
							WHERE b.status = 1 AND b.deleted <> 1 AND b.site in " . $admin_user["sites"] . " AND admin_id = '" . $admin_user["id"] . "'   
							$criteria 
							$order_str";
	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(id) AS CNT FROM ( " . $query_base . " ) res1");
	$row_total = $db->fetch($result_num);
	$recoTotal =  $row_total["CNT"];
	$pageTotal = ceil($recoTotal/$pageSize);
						

	$query 	= "SELECT * FROM (" . $query_base . ") res1  LIMIT " . ($pageNo-1) * $pageSize . " , " . $pageSize;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["first_name"] 	= cTYPE::gstr($row["first_name"]);
		$rows[$cnt]["last_name"] 	= cTYPE::gstr($row["last_name"]);
		$rows[$cnt]["dharma_name"] 	= cTYPE::gstr($row["dharma_name"]);
		$rows[$cnt]["alias"] 		= cTYPE::gstr($row["alias"]);
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["language"] 	= $row["language"]?$langs[$row["language"]]:"";
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["email_flag"] 	= $row["email_flag"]?"Yes":"No";
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d H:i:s",$row["created_time"]):'';
		$cnt++;	
	}
	// synchorize to general
	$response["data"]["general"]["recoTotal"] 	= $recoTotal;
	$response["data"]["general"]["pageTotal"] 	= $pageTotal;
	$response["data"]["general"]["pageNo"] 		= $pageNo;
	$response["data"]["general"]["pageSize"] 	= $pageSize;
	// synchorize to tabData.condition
	$response["data"]["condition"]	= $_REQUEST["condition"];
	$response["data"]["rows"] 		= $rows;

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
