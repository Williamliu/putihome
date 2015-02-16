<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$orderBY	= $_REQUEST["orderBY"]==""?"created_time":$_REQUEST["orderBY"];
	$orderSQ	= $_REQUEST["orderSQ"]==""?"DESC":$_REQUEST["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	// condition here 
	$criteria = "";
	$con = $_REQUEST; 
	$sch_name = trim($con["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(cname like '%" . cTYPE::utrans($sch_name) . "%' OR pname like '%" . cTYPE::utrans($sch_name) . "%' OR dharma_name like '%" . cTYPE::utrans($sch_name) . "%' OR en_name like '%" . cTYPE::utrans($sch_name) . "%')";
	}

	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(replace(replace(phone,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%' OR replace(replace(cell,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%')";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_status = trim($con["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "status = '" . $sch_status . "'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_depart = trim($con["sch_depart"]);
	if($sch_depart != "") {
		$criteria .= ($criteria==""?"":" AND ") . "department_id = '" . $sch_depart . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$sch_depart = trim($con["sch_depart"]);
	if($sch_depart == "") {
		$query_base = "SELECT * 
							FROM puti_volunteer  
							WHERE  deleted <> 1  AND
							site IN " . $admin_user["sites"] . "
							$criteria 
							$order_str";
	} else {
		$query_base = "SELECT distinct a.* 
							FROM puti_volunteer a INNER JOIN puti_department_volunteer b ON (a.id = b.volunteer_id)  
							WHERE  a.deleted <> 1 AND 
							site IN " . $admin_user["sites"] . "
							$criteria 
							$order_str";
	}
	//echo "query:" . $query_base;
						

	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();
	
	$cnt = 0;
	$ecnt = 0;
	$ucnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["cname"] 		= $row["cname"];
		$rows[$cnt]["pname"] 		= $row["pname"];
		$rows[$cnt]["en_name"] 		= $row["en_name"];
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"];
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= $row["city"];

		if( trim($rows[$cnt]["email"]) != "") {
			if( !$db->exists("SELECT id FROM puti_email WHERE admin_id = '" . $admin_user["id"] . "' AND email='" . trim($rows[$cnt]["email"]) . "'") ) {
				$fields = array();
				$nnn = explode(" ", $rows[$cnt]["en_name"]);
				$fields["admin_id"]     = $admin_user["id"];							
				$fields["email"] 		= trim($rows[$cnt]["email"]);
				$fields["first_name"] 	= trim($nnn[0]);
				$fields["last_name"] 	= trim($nnn[1]);
				$fields["dharma_name"] 	= trim($rows[$cnt]["dharma_name"]);
				$fields["gender"] 		= trim($rows[$cnt]["gender"]);
				$fields["phone"] 		= trim($rows[$cnt]["phone"]);
				$fields["cell"] 		= trim($rows[$cnt]["cell"]);
				$fields["city"] 		= trim($rows[$cnt]["city"]);
				$fields["created_time"]	= time();
				$db->insert("puti_email",$fields);
				$ecnt++;
			} else {
				$ucnt++;
			}
		}
		

		$cnt++;	
	}

	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "Total Match Records: $cnt<br><br>$ecnt Emails has been added to Email Pool.<br><br>$ucnt Emails already exists.";
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
