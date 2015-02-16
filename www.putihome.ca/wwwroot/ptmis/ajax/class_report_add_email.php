<?php 
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$orderBY	= $_REQUEST["orderBY"]==""?"first_name":$_REQUEST["orderBY"];
	$orderSQ	= $_REQUEST["orderSQ"]==""?"DESC":$_REQUEST["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";

	$con = $_REQUEST; 
	
	// condition here 
	$sd = cTYPE::datetoint($con["sch_sdate"]);
	$ed = cTYPE::datetoint($con["sch_edate"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "start_date >= '" . $sd . "' AND end_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "start_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "end_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	
	
	
	$criteria = "";
	$sch_111 = trim($con["sch_class"]);
	if($sch_111 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "class_id = '" . $sch_111 . "'";
	} else {
		$criteria .= ($criteria==""?"":" AND ") . "class_id = '-1'";
	}

	$sch_222 = trim($con["sch_sign"]);
	if($sch_222 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "signin = '" . $sch_222 . "'";
	}

	$sch_333 = trim($con["sch_grad"]);
	if($sch_333 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "graduate = '" . $sch_333 . "'";
	}

	$sch_444 = trim($con["sch_cert"]);
	if($sch_444 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "cert = '" . $sch_444 . "'";
	}

	$sch_555 = trim($con["sch_name"]);
	if($sch_555 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(first_name like '%" . $sch_555 . "%' OR last_name like '%" . $sch_555 . "%' OR dharma_name like '%" . $sch_555 . "%' OR alias like '%" . $sch_555 . "%')";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query_base = "SELECT a.title, a.start_date, a.end_date,
						  b.id, b.signin, b.graduate, b.cert, b.attend, 
						  c.first_name, c.last_name, c.dharma_name, c.alias, c.gender, c.email, c.phone, c.cell, c.city 
						FROM event_calendar a 
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id)  
						INNER JOIN 
						( SELECT aa0.*, bb0.title as member_title FROM puti_members aa0 LEFT JOIN puti_info_title bb0 ON ( aa0.level = bb0.id )  ) c ON (b.member_id = c.id) 
						WHERE  a.deleted <> 1 AND c.deleted <> 1  
						$ccc 
						$criteria 
						$order_str";

	$query 	= $query_base; 
	$result = $db->query( $query );
	$rows = array();

	

	
	
	$cnt = 0;
	$ecnt = 0;
	$ucnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["title"] 		= $row["title"];
		$rows[$cnt]["start_date"] 	= $row["start_date"]>0?date("Y-m-d",$row["start_date"]):'';
		$rows[$cnt]["end_date"] 	= $row["end_date"]>0?date("Y-m-d",$row["end_date"]):'';
		$rows[$cnt]["first_name"] 	= $row["first_name"];
		$rows[$cnt]["last_name"] 	= $row["last_name"];
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"];
		$rows[$cnt]["alias"] 		= $row["alias"];
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= $row["city"];
		$rows[$cnt]["signin"] 		= $row["signin"]?"Y":"";
		$rows[$cnt]["graduate"] 	= $row["graduate"]?"Y":"";
		$rows[$cnt]["cert"] 		= $row["cert"]?"Y":"";
		$rows[$cnt]["attend"] 		= $row["attend"]>0?($row["attend"]*100)."%":"";
		
		
		if( trim($rows[$cnt]["email"]) != "") {
			if( !$db->exists("SELECT id FROM puti_email WHERE admin_id = '" . $admin_user["id"] . "' AND email='" . trim($rows[$cnt]["email"]) . "'") ) {
				$fields = array();
				$fields["admin_id"]     = $admin_user["id"];							
				$fields["email"] 		= trim($rows[$cnt]["email"]);
				$fields["first_name"] 	= trim($rows[$cnt]["first_name"]);
				$fields["last_name"] 	= trim($rows[$cnt]["last_name"]);
				$fields["dharma_name"] 	= trim($rows[$cnt]["dharma_name"]);
				$fields["alias"] 		= trim($rows[$cnt]["alias"]);
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
