<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
if( $_REQUEST["dharma_name"]=="" && $_REQUEST["cname"]=="" && $_REQUEST["pname"]=="" && $_REQUEST["en_name"]=="" ) {
	$response["errorCode"] 	= 1;
	$response["errorMessage"] = "Must input volunteer identity for one of the name.";
	echo json_encode($response);
	exit();
}

try {
	$type["cname"]			= '{"type":"CHAR", 	"length":255, 	"id": "cname", 		"name":"中文名", 		"nullable":1}';
	$type["pname"]			= '{"type":"CHAR", 	"length":255, 	"id": "pname", 		"name":"拼音名", 		"nullable":1}';
	$type["email"]			= '{"type":"EMAIL", "length":1023, 	"id": "pname", 		"name":"电子邮件", 		"nullable":1}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["type"] == 2 ) {
		   ///////  create new record 	
		  $fields = array();
		  $fields["site"] 		= $admin_user["site"];
		  $fields["cname"] 		= cTYPE::utrans($_REQUEST["cname"]);
		  $fields["pname"] 		= cTYPE::utrans($_REQUEST["pname"]);
		  $fields["en_name"] 	= cTYPE::utrans($_REQUEST["en_name"]);
		  $fields["dharma_name"]= cTYPE::utrans($_REQUEST["dharma_name"]);
		  $fields["gender"] 	= $_REQUEST["gender"];
		  $fields["email"] 		= $_REQUEST["email"];
		  $fields["phone"] 		= cTYPE::phone($_REQUEST["phone"]);
		  $fields["cell"] 		= cTYPE::phone($_REQUEST["cell"]);
		  $fields["city"] 		= cTYPE::utrans($_REQUEST["city"]);
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  $fields["created_time"] 	= time();
		  $vol_id = $db->insert("puti_volunteer", $fields);
	  
		  $departs = explode(",",$_REQUEST["depart"]);	
		  foreach($departs as $depart) {
			  $fields = array();
			  $fields["site"] 			= $admin_user["site"];
			  $fields["department_id"] 	= $depart;
			  $fields["volunteer_id"] 	= $vol_id;
			  $fields["status"] 			= 0;
			  $db->insert("puti_department_volunteer", $fields);
		  }
		  
		  $response["errorCode"] 		= 0;
		  $response["errorMessage"]	= "Volunteer " . cTYPE::utrans($_REQUEST["dharma_name"]) . " saved successful.";
		  // end of create new record

		
	} elseif($_REQUEST["type"] == 1) {
		  // overwrite record	
		  $fields = array();
		  $fields["site"] 			= $admin_user["site"];
		  $fields["cname"] 			= cTYPE::utrans($_REQUEST["cname"]);
		  $fields["pname"] 			= cTYPE::utrans($_REQUEST["pname"]);
		  $fields["en_name"] 		= cTYPE::utrans($_REQUEST["en_name"]);
		  $fields["dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
		  $fields["gender"] 		= $_REQUEST["gender"];
		  $fields["email"] 			= $_REQUEST["email"];
		  $fields["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
		  $fields["cell"] 			= cTYPE::phone($_REQUEST["cell"]);
		  $fields["city"] 			= cTYPE::utrans($_REQUEST["city"]);
		  $fields["status"] 		= 1;
		  $fields["last_updated"] 	= time();
		  $db->update("puti_volunteer", $_REQUEST["hid"], $fields);
	  
		  if( $_REQUEST["depart"] == "" ) {
			  $db->query("DELETE FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "'");
		  } else {
			  $db->query("DELETE FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "' AND department_id NOT IN (" . $_REQUEST["depart"] . ")");
			  $departs = explode(",",$_REQUEST["depart"]);	
			  foreach($departs as $depart) {
				  if( !$db->exists("SELECT volunteer_id FROM puti_department_volunteer WHERE volunteer_id = '" . $_REQUEST["hid"] . "' AND department_id = '" . $depart . "'") ) {
					  $fields = array();
					  $fields["site"] 			= $admin_user["site"];
					  $fields["department_id"] 	= $depart;
					  $fields["volunteer_id"] 	= $_REQUEST["hid"];
					  $fields["status"] 			= 0;
					  $db->insert("puti_department_volunteer", $fields);
				  }
			  }
		  }
		  $response["errorMessage"]	= "Volunteer " . cTYPE::utrans($_REQUEST["dharma_name"]) . " has been overwritten.";
		  $response["errorCode"] 		= 0;

		  // end of overwrite 
	} else {

		  ///// Search first , not found add new
		  $ccc = "";
		  if( trim($_REQUEST["dharma_name"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "dharma_name = '" . cTYPE::utrans(trim($_REQUEST["dharma_name"])) . "'";
		  }
		  
		  if( trim($_REQUEST["cname"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "cname = '" . cTYPE::utrans(trim($_REQUEST["cname"])) . "'";
		  }
		  
		  if( trim($_REQUEST["pname"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "pname = '" . cTYPE::utrans(trim($_REQUEST["pname"])) . "'";
		  }
		  
		  if( trim($_REQUEST["en_name"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "en_name = '" . cTYPE::utrans(trim($_REQUEST["en_name"])) . "'";
		  }
		  
		  if( trim($_REQUEST["email"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "email = '" . trim($_REQUEST["email"]) . "'";
		  }
		  
		  if( trim($_REQUEST["phone"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "replace(replace(phone,' ',''),'-','') = '" . str_replace( array(" ", "-"),array("",""), trim($_REQUEST["phone"]) ) . "'";
		  }
		  
		  if( trim($_REQUEST["cell"]) != "" ) {
			  $ccc .= ($ccc==""?"":" OR ") . "replace(replace(cell,' ',''),'-','') = '" . str_replace( array(" ", "-"),array("",""), trim($_REQUEST["cell"]) ) . "'";
		  }
		  
		  $ccc = $ccc==""?"AND 1 = 0":" AND (" . $ccc . ")";
		  
		  $query0 = "SELECT id FROM puti_volunteer WHERE deleted <> 1 $ccc";
		  $result0 = $db->query($query0);
		  if( $db->row_nums($result0)>0 ) {
				$row0 = $db->fetch($result0);
		  
				$query = "SELECT * FROM puti_volunteer WHERE deleted <> 1 AND id = '" . $row0["id"] . "'";
				$result = $db->query( $query );
				$row = $db->fetch($result);
				
				$response["data"]["id"] 			= $row["id"];
				$response["data"]["hid"] 			= $row["id"];
				$response["data"]["dharma_name"] 	= $row["dharma_name"];
				$response["data"]["cname"] 			= $row["cname"];
				$response["data"]["pname"] 			= $row["pname"];
				$response["data"]["en_name"] 		= $row["en_name"];
				$response["data"]["gender"] 		= $row["gender"];
				$response["data"]["email"] 			= $row["email"];
				$response["data"]["phone"] 			= $row["phone"];
				$response["data"]["cell"] 			= $row["cell"];
				$response["data"]["status"] 		= $row["status"];
				$response["data"]["city"] 			= $row["city"];
				$response["data"]["site"] 			= $row["site"];
				$response["data"]["created_time"] 	= cTYPE::inttodate($row["created_time"]);
			
				$query = "SELECT * FROM puti_department_volunteer WHERE volunteer_id = '" . $row0["id"] . "'";
				$result = $db->query( $query );
				$depart = "";
				while( $row = $db->fetch($result) ) {
					$depart .= ($depart==""?"":",") .  $row["department_id"];
				}
				$response["data"]["depart"] = $depart;
			
				$query = "SELECT sum(work_hour) as total_hour, count(id) as work_count FROM puti_volunteer_hours WHERE volunteer_id = '" . $row0["id"] . "'";
				$result = $db->query( $query );
				$row = $db->fetch($result);
				$response["data"]["total_hour"] = $row["total_hour"]>0?$row["total_hour"]:0;
				$response["data"]["work_count"] = $row["work_count"]>0?$row["work_count"]:0;
			
				$query = "SELECT a.id, b.title, a.purpose, a.work_date, a.work_hour 
								FROM puti_volunteer_hours a INNER JOIN puti_department b ON (a.department_id = b.id)
								WHERE volunteer_id = '" . $row0["id"] . "'
								ORDER BY  a.work_date DESC LIMIT 0, 30";
				$result = $db->query( $query );
				$record = array();
				$cnt=0;
				while( $row = $db->fetch($result) ) {
					$rObj = array();
					$rObj["id"] 		= $row["id"];
					$rObj["title"] 		= $row["title"];
					$rObj["purpose"] 	= $row["purpose"];
					$rObj["work_date"] 	= $row["work_date"]>0?date("Y-m-d",$row["work_date"]):'';
					$rObj["work_hour"] 	= $row["work_hour"];
					$record[$cnt] = $rObj;
					$cnt++;
				}
		  
				$response["data"]["record"] 	= $record;
				$response["data"]["hid"] 		= $row0["id"];
				$response["errorMessage"]	= "<br>found match.";
				$response["errorCode"] 		= 9;
			
	  
		  } else {
				$fields = array();
				$fields["site"]			= $admin_user["site"];
				$fields["cname"] 		= cTYPE::utrans($_REQUEST["cname"]);
				$fields["pname"] 		= cTYPE::utrans($_REQUEST["pname"]);
				$fields["en_name"] 		= cTYPE::utrans($_REQUEST["en_name"]);
				$fields["dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
				$fields["gender"] 		= $_REQUEST["gender"];
				$fields["email"] 		= $_REQUEST["email"];
				$fields["phone"] 		= cTYPE::phone($_REQUEST["phone"]);
				$fields["cell"] 		= cTYPE::phone($_REQUEST["cell"]);
				$fields["city"] 		= cTYPE::utrans($_REQUEST["city"]);
				
				$fields["status"] 			= 1;
				$fields["deleted"] 			= 0;
				$fields["created_time"] 	= time();
				$vol_id = $db->insert("puti_volunteer", $fields);
			
				$departs = explode(",",$_REQUEST["depart"]);	
				foreach($departs as $depart) {
					$fields = array();
					$fields["site"]				= $admin_user["site"];
					$fields["department_id"] 	= $depart;
					$fields["volunteer_id"] 	= $vol_id;
					$fields["status"] 			= 0;
					$db->insert("puti_department_volunteer", $fields);
				}
				
				$response["errorCode"] 		= 0;
			  	$response["errorMessage"]	= "Volunteer " . $_REQUEST["dharma_name"] . " saved successful.";
		  }
		  ///// end of Search first , not found add new
	}
		  
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
