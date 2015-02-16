<?php 
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {

	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["legal_first"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_first", 	"name":"Legal First", 	"nullable":1}';
	$type["legal_last"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_last", 	"name":"Legal Last", 	"nullable":1}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["alias"] 				= '{"type":"CHAR", 		"length":255, 	"id": "alias", 			"name":"Alias", 		"nullable":1}';
	$type["gender"]				= '{"type":"CHAR", 		"length":11, 	"id": "gender", 		"name":"Gender", 	 	"nullable":0}';
	$type["age"]				= '{"type":"NUMBER", 	"length":11, 	"id": "age_range", 		"name":"Age Range", 	"nullable":0}';

	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":0}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":0}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';

	$type["contact_method"]		= '{"type":"CHAR", 		"length":15, 	"id": "contact_method", "name":"Preferred method of contact", 	"nullable":1}';
	//$type["birth_date"]			= '{"type":"DATE", 		"length":20, 	"id": "birth_month", 	"name":"Birth Date", 	"nullable":1}';
	
	$type["address"]			= '{"type":"CHAR", 		"length":1023, 	"id": "address", 		"name":"Address", 		"nullable":1}';
	$type["state"]				= '{"type":"CHAR", 		"length":127, 	"id": "state", 			"name":"State", 		"nullable":1}';
	$type["country"]			= '{"type":"CHAR", 		"length":127, 	"id": "country", 		"name":"Country", 		"nullable":1}';
	$type["postal"]				= '{"type":"CHAR", 		"length":15, 	"id": "postal", 		"name":"Postal", 		"nullable":1}';

	$type["emergency_name"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_name", 	"name":"Emergency Contact Person", 		"nullable":1}';
	$type["emergency_phone"]	= '{"type":"CHAR", 		"length":255, 	"id": "emergency_phone",	"name":"Emergency Contact Phone", 		"nullable":1}';
	$type["emergency_ship"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_ship", 	"name":"Emergency Relationship", 		"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$tstamp 	= time();
	$inputtime 	= date("Y-m-d H:i:s", $tstamp);
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$site = $db->getVal("event_calendar", "site", $_REQUEST["event_id"]); 
	
	$query = "SELECT id FROM puti_members WHERE deleted <> 1 AND 
				( 	email = '" . $_REQUEST["email"] . "' OR  
					replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $_REQUEST["phone"]) . "%' OR 
					replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $_REQUEST["cell"]) . "%' 					
				) AND first_name = '" . cTYPE::utrans($_REQUEST["first_name"]) . "'";

	$result = $db->query( $query );
	if( $db->row_nums($result) > 0 )  {
		  $row = $db->fetch($result);
		  $member_id = $row["id"];	

		  $fields = array();
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  //$fields["created_time"]		= time();
		  $fields["last_updated"] 		= time();
		  $fields["last_login"] 		= 0;
	  	  
	  	  $fields["first_name"] 		= cTYPE::ufirst($_REQUEST["first_name"]);
		  $fields["last_name"] 			= cTYPE::ufirst($_REQUEST["last_name"]);
		  $fields["online"] 			= 1;

		  
		  if(trim($_REQUEST["legal_first"])!="")
		  	  $fields["legal_first"]= cTYPE::ufirst($_REQUEST["legal_first"]);
		  
		  if(trim($_REQUEST["legal_last"])!="")
			  $fields["legal_last"] = cTYPE::ufirst($_REQUEST["legal_last"]);
			
		  if(trim($_REQUEST["dharma_name"])!="")
		  	$fields["dharma_name"] = cTYPE::utrans($_REQUEST["dharma_name"]);

		  if(trim($_REQUEST["alias"])!="")
		  	$fields["alias"] = cTYPE::ufirst($_REQUEST["alias"]);

		  if(trim($_REQUEST["identify_no"])!="")
			  $fields["identify_no"] 		= strtoupper($_REQUEST["identify_no"]);


		  $fields["gender"] 			= $_REQUEST["gender"];
		  $fields["age"] 				= $_REQUEST["age"];
		  
		  //if(trim($_REQUEST["birth_date"])!="")   $fields["birth_date"] = cTYPE::datetoint($_REQUEST["birth_date"]);
		  
		  $fields["email"] 				= $_REQUEST["email"];
		  $fields["phone"] 				= cTYPE::phone($_REQUEST["phone"]);
		 
		  if(trim($_REQUEST["cell"])!="")
			  $fields["cell"] = cTYPE::phone($_REQUEST["cell"]);
		  
		  if(trim($_REQUEST["contact_method"])!="")
			  $fields["contact_method"] = $_REQUEST["contact_method"];
		  
		  if(trim($_REQUEST["address"])!="")
			  $fields["address"] = cTYPE::utrans($_REQUEST["address"]);
		 
		  if(trim($_REQUEST["city"])!="")
		 	 $fields["city"] = cTYPE::utrans($_REQUEST["city"]);

		  if(trim($_REQUEST["state"])!="")
		  	 $fields["state"] = cTYPE::utrans($_REQUEST["state"]);
		  
		  if(trim($_REQUEST["country"])!="")
		 	 $fields["country"] = cTYPE::utrans($_REQUEST["country"]);
		  
		  if(trim($_REQUEST["postal"])!="")
			  $fields["postal"] = strtoupper($_REQUEST["postal"]);
	  
		  $result = $db->update("puti_members", $member_id, $fields);
		  
		  $db->query("DELETE FROM puti_members_others WHERE member_id = '" . $member_id . "'");
		  $fields = array();
		  $fields["member_id"] 					= $member_id;
		  $fields["emergency_name"] 			= cTYPE::ufirst($_REQUEST["emergency_name"]);
		  $fields["emergency_phone"] 			= cTYPE::phone($_REQUEST["emergency_phone"]);
		  $fields["emergency_ship"] 			= cTYPE::utrans($_REQUEST["emergency_ship"]);
		  $fields["therapy"] 					= $_REQUEST["therapy"];
		  $fields["therapy_content"] 			= cTYPE::utrans($_REQUEST["therapy_content"]);
		  $fields["medical_concern"] 			= cTYPE::utrans($_REQUEST["medical_concern"]);
		  $fields["other_symptom"] 				= cTYPE::utrans($_REQUEST["other_symptom"]);
		  $db->insert("puti_members_others", $fields);

		  $db->query("DELETE FROM puti_members_hearfrom WHERE member_id = '" . $member_id . "'");
		  $hear_array = $_REQUEST["hear_about"]!=""?explode(",",$_REQUEST["hear_about"]):array();
		  foreach($hear_array as $hear) {
			  $fields = array();
			  $fields["member_id"] = $member_id;
			  $fields["hearfrom_id"] = $hear;
			  $db->insert("puti_members_hearfrom", $fields);
		  }

		  $db->query("DELETE FROM puti_members_symptom WHERE member_id = '" . $member_id . "'");
		  $hear_array = $_REQUEST["symptom"]!=""?explode(",",$_REQUEST["symptom"]):array();
		  foreach($hear_array as $hear) {
			  $fields = array();
			  $fields["member_id"] = $member_id;
			  $fields["symptom_id"] = $hear;
			  $db->insert("puti_members_symptom", $fields);
		  }
	
		
		  $query = "SELECT id FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $member_id . "'";
		  $result = $db->query( $query );
		  if( $db->row_nums($result) > 0 )  {
			  $query = "UPDATE event_calendar_enroll SET group_no = 0, deleted = 0, online = 1 WHERE event_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $member_id . "'";
			  $result = $db->query( $query );
		  } else {
			  $fields = array();
			  $fields["event_id"] 	= $_REQUEST["event_id"];
			  $fields["member_id"] 	= $member_id;
			  $fields["group_no"] 	= 0;
			  $fields["status"] 			= 1;
			  $fields["online"] 			= 1;
			  $fields["deleted"] 			= 0;
			  $fields["created_time"] 	= time();
			  $enroll_id = $db->insert("event_calendar_enroll", $fields);
		  }
		  $response["errorCode"] 		= 0;
		  $response["errorMessage"]	= "<br>'" . cTYPE::ufirst($_REQUEST["first_name"]) . " " . cTYPE::ufirst($_REQUEST["last_name"]) . "' has registered successful and welcome to our class..";
		  $member[0]["first_name"] 	= cTYPE::ufirst($_REQUEST["first_name"]);
		  $member[0]["last_name"] 	= cTYPE::ufirst($_REQUEST["last_name"]);
		  $member[0]["legal_first"] = cTYPE::ufirst($_REQUEST["legal_first"]);
		  $member[0]["legal_last"] 	= cTYPE::ufirst($_REQUEST["legal_last"]);
		  $member[0]["dharma_name"] = cTYPE::utrans($_REQUEST["dharma_name"]);
		  $member[0]["alias"] 		= cTYPE::ufirst($_REQUEST["alias"]);
		  $member[0]["identify_no"] = strtoupper($_REQUEST["identify_no"]);
		  $member[0]["phone"] 		= cTYPE::phone($_REQUEST["phone"]);
		  $member[0]["cell"] 		= cTYPE::phone($_REQUEST["cell"]);

	} else {
		  $fields = array();
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  $fields["created_time"]		= time();
		  $fields["last_updated"] 		= 0;
		  $fields["last_login"] 		= 0;
	  
		  $fields["first_name"] 		= cTYPE::ufirst($_REQUEST["first_name"]);
		  $fields["last_name"] 			= cTYPE::ufirst($_REQUEST["last_name"]);
		  $fields["online"] 			= 1;
			

		  if(trim($_REQUEST["legal_first"])!="")
		  	$fields["legal_first"] = cTYPE::ufirst($_REQUEST["legal_first"]);

		  if(trim($_REQUEST["legal_last"])!="")
		  	$fields["legal_last"] = cTYPE::ufirst($_REQUEST["legal_last"]);

		  if(trim($_REQUEST["dharma_name"])!="")
		  	$fields["dharma_name"] = cTYPE::utrans($_REQUEST["dharma_name"]);

		  if(trim($_REQUEST["alias"])!="")
		  	$fields["alias"] = cTYPE::ufirst($_REQUEST["alias"]);

		  if(trim($_REQUEST["identify_no"])!="")
		  	$fields["identify_no"] = strtoupper($_REQUEST["identify_no"]);

		  $fields["gender"] 			= $_REQUEST["gender"];
		  $fields["age"] 				= $_REQUEST["age"];
		  
		  //if(trim($_REQUEST["birth_date"])!="")  $fields["birth_date"] = cTYPE::datetoint($_REQUEST["birth_date"]);
		  
		  $fields["email"] 				= $_REQUEST["email"];
		  $fields["phone"] 				= cTYPE::phone($_REQUEST["phone"]);
		 
		  if(trim($_REQUEST["cell"])!="")
			  $fields["cell"] = cTYPE::phone($_REQUEST["cell"]);
		  
		  if(trim($_REQUEST["contact_method"])!="")
			  $fields["contact_method"] = $_REQUEST["contact_method"];
		  
		  if(trim($_REQUEST["address"])!="")
			  $fields["address"] = cTYPE::utrans($_REQUEST["address"]);
		 
		  if(trim($_REQUEST["city"])!="")
		 	 $fields["city"] = cTYPE::utrans($_REQUEST["city"]);

		  if(trim($_REQUEST["state"])!="")
		  	 $fields["state"] = cTYPE::utrans($_REQUEST["state"]);
		  
		  if(trim($_REQUEST["country"])!="")
		 	 $fields["country"] = cTYPE::utrans($_REQUEST["country"]);
		  
		  if(trim($_REQUEST["postal"])!="")
			  $fields["postal"] = strtoupper($_REQUEST["postal"]);
	  
		  $fields["site"] = $site;
		  $member_id = $db->insert("puti_members", $fields);
	  
		  $db->query("DELETE FROM puti_members_others WHERE member_id = '" . $member_id . "'");
		  $fields = array();
		  $fields["member_id"] 					= $member_id;
		  $fields["emergency_name"] 			= cTYPE::ufirst($_REQUEST["emergency_name"]);
		  $fields["emergency_phone"] 			= cTYPE::phone($_REQUEST["emergency_phone"]);
		  $fields["emergency_ship"] 			= cTYPE::utrans($_REQUEST["emergency_ship"]);
		  $fields["therapy"] 					= $_REQUEST["therapy"];
		  $fields["therapy_content"] 			= cTYPE::utrans($_REQUEST["therapy_content"]);
		  $fields["medical_concern"] 			= cTYPE::utrans($_REQUEST["medical_concern"]);
		  $fields["other_symptom"] 				= cTYPE::utrans($_REQUEST["other_symptom"]);
		  $db->insert("puti_members_others", $fields);

		  $db->query("DELETE FROM puti_members_hearfrom WHERE member_id = '" . $member_id . "'");
		  $hear_array = $_REQUEST["hear_about"]!=""?explode(",",$_REQUEST["hear_about"]):array();
		  foreach($hear_array as $hear) {
			  $fields = array();
			  $fields["member_id"] = $member_id;
			  $fields["hearfrom_id"] = $hear;
			  $db->insert("puti_members_hearfrom", $fields);
		  }

		  $db->query("DELETE FROM puti_members_symptom WHERE member_id = '" . $member_id . "'");
		  $hear_array = $_REQUEST["symptom"]!=""?explode(",",$_REQUEST["symptom"]):array();
		  foreach($hear_array as $hear) {
			  $fields = array();
			  $fields["member_id"] = $member_id;
			  $fields["symptom_id"] = $hear;
			  $db->insert("puti_members_symptom", $fields);
		  }

		  $fields = array();
		  $fields["event_id"] 	= $_REQUEST["event_id"];
		  $fields["member_id"] 	= $member_id;
		  $fields["group_no"] 	= 0;
		  $fields["status"] 			= 1;
		  $fields["online"] 			= 1;
		  $fields["deleted"] 			= 0;
		  $fields["created_time"] 	= time();
		  $enroll_id = $db->insert("event_calendar_enroll", $fields);
		  $response["errorCode"] 		= 0;
		  $response["errorMessage"]	= "<br>'" . cTYPE::ufirst($_REQUEST["first_name"]) . " " . cTYPE::ufirst($_REQUEST["last_name"]) . "' has registered successful and welcome to our class..";
		  $member[0]["first_name"] 	= cTYPE::ufirst($_REQUEST["first_name"]);
		  $member[0]["last_name"] 	= cTYPE::ufirst($_REQUEST["last_name"]);
		  $member[0]["legal_first"] = cTYPE::ufirst($_REQUEST["legal_first"]);
		  $member[0]["legal_last"] 	= cTYPE::ufirst($_REQUEST["legal_last"]);
		  $member[0]["dharma_name"] = cTYPE::utrans($_REQUEST["dharma_name"]);
		  $member[0]["alias"] 		= cTYPE::ufirst($_REQUEST["alias"]);
		  $member[0]["identify_no"] = strtoupper($_REQUEST["identify_no"]);
		  $member[0]["phone"] 		= cTYPE::phone($_REQUEST["phone"]);
		  $member[0]["cell"] 		= cTYPE::phone($_REQUEST["cell"]);
	}
	$response["data"]["member"] = $member;
	$response["errorCode"] 		= 0;

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
