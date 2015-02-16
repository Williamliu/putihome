<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
if( trim($_REQUEST["member"]) == "" ) {
	$response["errorCode"] = 1;
	$response["data"]["member"]		= trim($_REQUEST["member"]);
	echo json_encode($response);
	exit();	
}

try {
	$type["pid"]  		= '{"type":"NUMBER", 	"length":11, 	"id": "event_id", 		"name":"Select Event", 		"nullable":0}';
	$type["member"]  	= '{"type":"CHAR", 		"length":1023, 	"id": "sigin_member", 	"name":"Email|Phone|Cell", 	"nullable":1}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query0 = "SELECT id, cname, pname, en_name, dharma_name FROM puti_volunteer WHERE deleted <> 1 AND (  
			  	replace(replace(replace(phone,' ',''),'-',''),'.','') = '" . str_replace( array(" ", "-", "."),array("","",""), trim($_REQUEST["member"]) ) . "' OR 
			  	replace(replace(replace(cell,' ',''),'-',''),'.','') = '" . str_replace( array(" ", "-", "."),array("","",""), trim($_REQUEST["member"]) ) . "' OR 
				email = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "' OR 
				cname = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "' OR 
				pname = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "' OR 
				en_name = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "' OR 
				dharma_name = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "')";

	$result0 = $db->query($query0);
	if( $db->row_nums($result0) ) {
		$row0 = $db->fetch($result0);
		
		$vid 	= $row0["id"];
		$pid 	= $_REQUEST["pid"];

		
		$query1 = "SELECT id, cname, pname, en_name, dharma_name, status FROM puti_volunteer WHERE deleted <> 1 AND id = '" . $vid . "'";
		$result1 = $db->query( $query1 );
		$row1 = $db->fetch($result1);
		
		if( $row1["status"] == 0  )  {
			$response["errorCode"] 		= 11;
			$response["errorMessage"]	= "The user is Inactive, please activate this user first.<br><br>
										   Dharma Name: " . $row0["dharma_name"] . "<br>
										   Chinese Name: " . $row0["cname"] . "<br>
										   Pinyin Name: " . $row0["pname"] . "<br>
										   English Name: " . $row0["en_name"] . "<br>
										   Phone: " . $row0["phone"] . "<br>
										   Cell: " . $row0["cell"] . "<br>";
			$db->query("UPDATE puti_department_volunteer SET site = '" . $admin_user["site"] . "', status = 1, last_updated = '" . time() . "' WHERE department_id = '" . $pid . "' AND volunteer_id = '" . $vid . "'");
			$response["data"]["pid"]				= $pid;
			$response["data"]["vid"]				= $vid;
			$response["data"]["department_id"]		= $pid;
			$response["data"]["volunteer_id"]		= $vid;
			$response["data"]["status"]	= 1;
		} else {
			$query2 = "SELECT status FROM puti_department_volunteer WHERE department_id = '" . $pid . "' AND volunteer_id = '" . $vid . "'";
			$result2 = $db->query( $query2 );
			$row2 = $db->fetch($result2);
			if( $db->row_nums($result2) ) {
				  $response["errorCode"] 		= 11;
				  $response["errorMessage"]	= "The user is already in the list.<br><br>
											   Dharma Name: " . $row0["dharma_name"] . "<br>
											   Chinese Name: " . $row0["cname"] . "<br>
											   Pinyin Name: " . $row0["pname"] . "<br>
											   English Name: " . $row0["en_name"] . "<br>
											   Phone: " . $row0["phone"] . "<br>
											   Cell: " . $row0["cell"] . "<br>";
				  $db->query("UPDATE puti_department_volunteer SET site = '". $admin_user["site"] ."', status = 1, last_updated = '" . time() . "' WHERE department_id = '" . $pid . "' AND volunteer_id = '" . $vid . "'");
				  $response["data"]["pid"]				= $pid;
				  $response["data"]["vid"]				= $vid;
				  $response["data"]["department_id"]	= $pid;
				  $response["data"]["volunteer_id"]		= $vid;
				  $response["data"]["status"]	= 1;
			} else {
				  $fields = array();
				  $fields["site"] 				= $admin_user["site"];
				  $fields["department_id"] 		= $pid;
				  $fields["volunteer_id"] 		= $vid;
				  $fields["status"] 			= 1;
				  
				  $fields["last_updated"] 		= time();
				  
				  $did = $db->insert("puti_department_volunteer", $fields);
				  $response["errorCode"] 		= 0;
				  $response["errorMessage"]		= "The volunteer has been added successful.<br><br>
													   Dharma Name: " . $row0["dharma_name"] . "<br>
													   Chinese Name: " . $row0["cname"] . "<br>
													   Pinyin Name: " . $row0["pname"] . "<br>
													   English Name: " . $row0["en_name"] . "<br>
													   Phone: " . $row0["phone"] . "<br>
													   Cell: " . $row0["cell"] . "<br>";
			}
			

		}
		
	    $response["data"]["pid"]			= $pid;
	    $response["data"]["vid"]			= $vid;
	    $response["data"]["department_id"]	= $pid;
	    $response["data"]["volunteer_id"]	= $vid;
	    $response["data"]["cname"]		= $row1["cname"];
	    $response["data"]["pname"]		= $row1["pname"];
	    $response["data"]["en_name"]	= $row1["en_name"];
	    $response["data"]["dharma_name"]= $row1["dharma_name"];
	    $response["data"]["status"]		= $row1["status"];
	} else {


		$query0 = "SELECT id, first_name, last_name, dharma_name, gender, email, phone, cell, city FROM puti_members WHERE deleted <> 1 AND ( 
					replace(replace(replace(phone,' ',''),'-',''),'.','') = '" . str_replace( array(" ", "-", "."),array("","",""), trim($_REQUEST["member"]) ) . "' OR 
					replace(replace(replace(cell,' ',''),'-',''),'.','') = '" . str_replace( array(" ", "-","."),array("","",""), trim($_REQUEST["member"]) ) . "' OR 
					email = '" . trim($_REQUEST["member"]) . "' OR
					dharma_name = '" . trim($_REQUEST["member"]) . "' )";
	
		$result0 = $db->query($query0);
		if( $db->row_nums($result0) ) {
			$row0 = $db->fetch($result0);
			$fields = array();
			$fields["site"]			= $admin_user["site"];
			$fields["cname"] 		= cTYPE::utrans($row0["first_name"] . " " . $row0["last_name"]);
			$fields["pname"] 		= cTYPE::utrans($row0["first_name"] . " " . $row0["last_name"]);
			$fields["en_name"] 		= cTYPE::utrans($row0["first_name"] . " " . $row0["last_name"]);
			$fields["dharma_name"] 	= cTYPE::utrans($row0["dharma_name"]);
			$fields["gender"] 		= $row0["gender"];
			$fields["email"] 		= $row0["email"];
			$fields["phone"] 		= $row0["phone"];
			$fields["cell"] 		= $row0["cell"];
			$fields["city"] 		= $row0["city"];
			$fields["status"] 			= 1;
			$fields["deleted"] 			= 0;
			$fields["created_time"] 	= time();
			$vid = $db->insert("puti_volunteer", $fields);
			
			
			$fields = array();
			$fields["site"] 			= $admin_user["site"];
			$fields["department_id"] 	= $_REQUEST["pid"];
			$fields["volunteer_id"] 	= $vid;
			$fields["status"] 			= 1;
			$fields["last_updated"] 	= time();
			
			$db->insert("puti_department_volunteer", $fields);
			$response["errorCode"] 		= 0;
			$response["errorMessage"]		= "The volunteer has been added successful.<br><br>
												   Dharma Name: " . $row0["dharma_name"] . "<br>
												   Chinese Name: " . $row0["cname"] . "<br>
												   Pinyin Name: " . $row0["pname"] . "<br>
												   English Name: " . $row0["en_name"] . "<br>
												   Phone: " . $row0["phone"] . "<br>
												   Cell: " . $row0["cell"] . "<br>";

			$response["data"]["pid"]			= $_REQUEST["pid"];
			$response["data"]["vid"]			= $vid;
			$response["data"]["department_id"]	= $_REQUEST["pid"];
			$response["data"]["volunteer_id"]	= $vid;
			$response["data"]["cname"]		= "";
			$response["data"]["pname"]		= "";
			$response["data"]["en_name"]	= $row0["first_name"] . " " . $row0["last_name"];
			$response["data"]["dharma_name"]= $row0["dharma_name"];
			
		} else {
			$response["errorMessage"]		= "The user didn't find in our list, please register first.";
			$response["errorCode"] 			= 1;
			$response["data"]["pid"]		= $pid;
			$response["data"]["member"]		= trim($_REQUEST["member"]);
		}
	}
	echo json_encode($response);
} catch(cERR $e) {
	echo json_encode($e->detail());
	
} catch(Exception $e ) {
    $response["data"]["pid"]			= $_REQUEST["pid"];
    $response["data"]["department_id"]	= $_REQUEST["pid"];
    $response["data"]["member"]	= trim($_REQUEST["member"]);
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}



?>
