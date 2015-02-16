<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "SELECT * FROM puti_sites WHERE status = 1 AND id in " . $admin_user["sites"] . " ORDER BY sn";
	$result = $db->query( $query );
	$siteArr = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
		$ddd = array();
		$ddd["site_id"] = $row["id"];
		$ddd["title"] 	= cTYPE::gstr($row["title"]);
		$ddd["branchs"]	= array();
		$query1 = "SELECT * FROM puti_sites_branchs a INNER JOIN puti_branchs b ON (a.branch_id = b.id) WHERE b.id in " . $admin_user["branchs"] . " AND a.site_id = '" . $ddd["site_id"] . "' ORDER BY sn";
		$result1 = $db->query($query1);
		$cnt1=0;
		while( $row1 = $db->fetch($result1) ) {
			$uuu = array();
			$uuu["branch_id"] = $row1["id"];
			$uuu["title"]  = cTYPE::gstr($row1["title"]);
			$uuu["classes"] = array();

			$query2 = "SELECT * FROM puti_class 
						WHERE 	deleted <> 1 AND  
								site = " . $row["id"] . " AND
								branch = " . $row1["id"] . "
                        ORDER BY sn DESC";
			$result2 = $db->query($query2);
			$cnt2=0;
			while( $row2 = $db->fetch($result2) ) {
				$zzz = array();
				$zzz["class_id"] = $row2["id"];
				$zzz["name"] 	 = cTYPE::gstr($row2["title"]);
				$uuu["classes"][$cnt2] = $zzz;
				$cnt2++;
			}
			
			$ddd["branchs"][$cnt1] = $uuu;
			$cnt1++;
		}
		$siteArr[$cnt] = $ddd;
		$cnt++;
	}



	$response["data"]["class_id"]	= $_REQUEST["class_id"]; 
	$response["data"]["sites"] 		= $siteArr; 
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
