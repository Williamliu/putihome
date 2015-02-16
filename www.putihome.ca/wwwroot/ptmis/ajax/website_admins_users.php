<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "SELECT id, name FROM website_groups WHERE deleted <> 1 AND status = 1 AND level <= " . $admin_user["group_level"] . " ORDER BY level DESC, name ASC";
	$result = $db->query( $query );
	$roleArr = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
		$ddd = array();
		$ddd["role_id"] = $row["id"];
		$ddd["title"] 	= $row["name"];
		$ddd["users"]	= array();
		
		$query1 = "SELECT a.* FROM website_admins a
							INNER JOIN website_groups b ON(a.group_id = b.id) 
							WHERE a.deleted <> 1 AND b.deleted <> 1 AND b.level <= '" . $admin_user["group_level"] . "' AND  
								  a.site = " . $admin_user["site"] . " AND 
								  a.group_id = '" . $row["id"] . "'";
								  //branch in " . $admin_user["branchs"] . " AND 
		$result1 = $db->query($query1);
		$cnt1=0;
		while( $row1 = $db->fetch($result1) ) {
			$uuu = array();
			$uuu["user_id"] = $row1["id"];
			$uuu["name"]  = stripslashes($row1["first_name"]);
			$uuu["name"] .= $row1["last_name"]!=""?" ".stripslashes($row1["last_name"]):"";
			$uuu["name"] .= $row1["dharma_name"]!=""?" ".stripslashes($row1["dharma_name"]):"";
			$uuu["name"] .= $row1["user_name"]!=""?" - ".stripslashes($row1["user_name"])."":"";
			$ddd["users"][$cnt1] = $uuu;
			$cnt1++;
		}

		$roleArr[$cnt] = $ddd;
		$cnt++;
	}



	$query = "SELECT * FROM puti_department WHERE deleted <> 1 AND id in " . $admin_user["departs"] . " ORDER BY sn DESC, title";
	$result = $db->query( $query );
	$depArr = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
		$ddd = array();
		$ddd["dep_id"] 	= $row["id"];
		$ddd["title"] 	= $row["title"];
		$ddd["users"]	= array();
		
		$query1 = "SELECT a.* FROM website_admins a
							INNER JOIN website_groups b ON(a.group_id = b.id)
							WHERE a.deleted <> 1 AND b.deleted <> 1 and b.level <= " . $admin_user["group_level"] . " AND 
								  a.site = " . $admin_user["site"] . " AND 
								  (
									a.department like '%," . $row["id"] . ",%' OR
									a.department like '" . $row["id"] . ",%' OR
									a.department like '%," . $row["id"] . "' OR
									a.department like '" . $row["id"] . "')";
		$result1 = $db->query($query1);
		$cnt1=0;
		while( $row1 = $db->fetch($result1) ) {
			$uuu = array();
			$uuu["user_id"] = $row1["id"];
			$uuu["name"]  = stripslashes($row1["first_name"]);
			$uuu["name"] .= $row1["last_name"]!=""?" ".stripslashes($row1["last_name"]):"";
			$uuu["name"] .= $row1["dharma_name"]!=""?" ".stripslashes($row1["dharma_name"]):"";
			$uuu["name"] .= $row1["user_name"]!=""?" - ".stripslashes($row1["user_name"])."":"";
			$ddd["users"][$cnt1] = $uuu;
			$cnt1++;
		}

		$depArr[$cnt] = $ddd;
		$cnt++;
	}



	$query = "SELECT * FROM puti_sites WHERE id > 0 AND id = " . $admin_user["site"] . " ORDER BY sn DESC";
	$result = $db->query( $query );
	$siteArr = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
		$ddd = array();
		$ddd["site_id"] = $row["id"];
		$ddd["title"] 	= $row["title"];
		$ddd["branchs"]	= array();
		$query1 = "SELECT * FROM puti_sites_branchs a INNER JOIN puti_branchs b ON (a.branch_id = b.id) WHERE site_id = '" . $ddd["site_id"] . "' ORDER BY b.id";
		$result1 = $db->query($query1);
		$cnt1=0;
		while( $row1 = $db->fetch($result1) ) {
			$uuu = array();
			$uuu["branch_id"] = $row1["id"];
			$uuu["title"]  = stripslashes($row1["title"]);
			$uuu["users"] = array();
			/*
			$query2 = "SELECT * FROM website_admins 
						WHERE 	deleted <> 1 AND (
									site = '" . $row["id"] . "' OR 
									sites like '%," . $row["id"] . ",%' OR
									sites like '" . $row["id"] . ",%' OR
									sites like '%," . $row["id"] . "' OR
									sites like '%" . $row["id"] . "'																	
								) AND (
									branch = '" . $row1["id"] . "' OR 
									branchs like '%," . $row1["id"] . ",%' OR
									branchs like '" . $row1["id"] . ",%' OR
									branchs like '%," . $row1["id"] . "' OR
									branchs like '%" . $row1["id"] . "'																	
								)";
			*/
			$query2 = "SELECT a.* FROM  	website_admins a
										INNER JOIN website_groups b ON(a.group_id = b.id)
						WHERE 	a.deleted <> 1 AND b.deleted <> 1 AND b.level <= " . $admin_user["group_level"] . " AND 
								a.site = '" . $row["id"] . "' AND
								(
									a.branch = '" . $row1["id"] . "' OR 
									a.branchs like '%," . $row1["id"] . ",%' OR
									a.branchs like '" . $row1["id"] . ",%' OR
									a.branchs like '%," . $row1["id"] . "' OR
									a.branchs like '%" . $row1["id"] . "'																	
								)";
			$result2 = $db->query($query2);
			$cnt2=0;
			while( $row2 = $db->fetch($result2) ) {
				$zzz = array();
				$zzz["user_id"] = $row2["id"];
				$zzz["name"]  = stripslashes($row2["first_name"]);
				$zzz["name"] .= $row2["last_name"]!=""?" ".stripslashes($row2["last_name"]):"";
				$zzz["name"] .= $row2["dharma_name"]!=""?" ".stripslashes($row2["dharma_name"]):"";
				$zzz["name"] .= $row2["user_name"]!=""?" - ".stripslashes($row2["user_name"])."":"";
				$uuu["users"][$cnt2] = $zzz;
				$cnt2++;
			}
			
			$ddd["branchs"][$cnt1] = $uuu;
			$cnt1++;
		}
		$siteArr[$cnt] = $ddd;
		$cnt++;
	}


	$query = "SELECT * FROM puti_sites WHERE id > 0 AND id = " . $admin_user["site"] . " ORDER BY id";
	$result = $db->query( $query );
	$putiArr = array();
	$cnt=0;
	while( $row = $db->fetch($result) ) {
		$ddd = array();
		$ddd["site_id"] = $row["id"];
		$ddd["title"] 	= $row["title"];
		$ddd["users"]	= array();
		
		/*
		$query2 = "SELECT * FROM website_admins 
					WHERE 	deleted <> 1 AND 
							(
								site = '" . $row["id"] . "' OR 
								sites like '%," . $row["id"] . ",%' OR
								sites like '" . $row["id"] . ",%' OR
								sites like '%," . $row["id"] . "' OR
								sites like '%" . $row["id"] . "'																	
							)";
		*/
		
		$query2 = "SELECT a.* FROM website_admins a
							INNER JOIN website_groups b ON(a.group_id = b.id)
					WHERE a.deleted <> 1 AND 
						  b.deleted <> 1 AND b.level <= " . $admin_user["group_level"] . " AND 
						  a.site = '" . $row["id"] . "'";

		$result2 = $db->query($query2);
		$cnt2=0;
		while( $row2 = $db->fetch($result2) ) {
			$zzz = array();
			$zzz["user_id"] = $row2["id"];
			$zzz["name"]  = stripslashes($row2["first_name"]);
			$zzz["name"] .= $row2["last_name"]!=""?" ".stripslashes($row2["last_name"]):"";
			$zzz["name"] .= $row2["dharma_name"]!=""?" ".stripslashes($row2["dharma_name"]):"";
			$zzz["name"] .= $row2["user_name"]!=""?" - ".stripslashes($row2["user_name"])."":"";
			$ddd["users"][$cnt2] = $zzz;
			$cnt2++;
		}
		$putiArr[$cnt] = $ddd;
		$cnt++;
	}



	$response["data"]["admin_id"]	= $_REQUEST["admin_id"]; 
	$response["data"]["roles"] 		= $roleArr; 
	$response["data"]["departments"]= $depArr; 
	$response["data"]["sites"] 		= $siteArr; 
	$response["data"]["puti"] 		= $putiArr; 
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
