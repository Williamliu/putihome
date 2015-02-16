<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["idd"] 				= '{"type":"CHAR", 		"length":12, 	"id": "idd", 			"name":"ID Card", 		"nullable":1}';
	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["legal_first"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_first", 	"name":"Legal First", 	"nullable":1}';
	$type["legal_last"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_last", 	"name":"Legal Last", 	"nullable":1}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["alias"] 				= '{"type":"CHAR", 		"length":255, 	"id": "alias", 			"name":"Alias", 		"nullable":1}';
	$type["identify_no"] 		= '{"type":"CHAR", 		"length":31, 	"id": "identify_no", 	"name":"ID Number", 	"nullable":1}';
	$type["gender"]				= '{"type":"CHAR", 		"length":11, 	"id": "gender", 		"name":"Gender", 	 	"nullable":0}';
	$type["member_yy"]			= '{"type":"NUMBER", 	"length":4, 	"id": "member_yy", 		"name":"Member Year", 	"nullable":1}';
	$type["member_mm"]			= '{"type":"NUMBER", 	"length":2, 	"id": "member_mm", 		"name":"Member Month", 	"nullable":1}';
	$type["member_dd"]			= '{"type":"NUMBER", 	"length":2, 	"id": "member_dd", 		"name":"Member Day", 	"nullable":1}';
	$type["age"]				= '{"type":"NUMBER", 	"length":11, 	"id": "age_range", 		"name":"Age Range", 	"nullable":1}';
	$type["birth_yy"]			= '{"type":"NUMBER", 	"length":4, 	"id": "birth_yy", 		"name":"Birth Year", 	"nullable":1}';
	$type["birth_mm"]			= '{"type":"NUMBER", 	"length":2, 	"id": "birth_mm", 		"name":"Birth Month", 	"nullable":1}';
	$type["birth_dd"]			= '{"type":"NUMBER", 	"length":2, 	"id": "birth_dd", 		"name":"Birth Day", 	"nullable":1}';
	//$type["birth_date"]			= '{"type":"DATE", 		"length":20, 	"id": "birth_month", 	"name":"Birth Date", 	"nullable":1}';

	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":1}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["contact_method"]		= '{"type":"CHAR", 		"length":15, 	"id": "contact_method", "name":"Preferred method of contact", 	"nullable":1}';
	
	$type["address"]			= '{"type":"CHAR", 		"length":1023, 	"id": "address", 		"name":"Address", 		"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';
	$type["state"]				= '{"type":"CHAR", 		"length":127, 	"id": "state", 			"name":"State", 		"nullable":1}';
	$type["country"]			= '{"type":"CHAR", 		"length":127, 	"id": "country", 		"name":"Country", 		"nullable":1}';
	$type["postal"]				= '{"type":"CHAR", 		"length":15, 	"id": "postal", 		"name":"Postal", 		"nullable":1}';

	$type["emergency_name"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_name", 	"name":"Emergency Contact Person", 		"nullable":1}';
	$type["emergency_phone"]	= '{"type":"CHAR", 		"length":255, 	"id": "emergency_phone",	"name":"Emergency Contact Phone", 		"nullable":1}';
	$type["emergency_ship"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_ship", 	"name":"Emergency Relationship", 		"nullable":1}';

	$type["email_flag"] 		= '{"type":"NUMBER", 	"length":1, 	"id": "email_flag", 	    "name":"Email Subscription Agreement",  "nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	if( $_REQUEST["birth_yy"]!="" && (intval($_REQUEST["birth_yy"]) <= ( date("Y") - 100) || intval($_REQUEST["birth_yy"]) > date("Y") )  )	 {
		$response["errorCode"] 		= 1;
		$response["errorMessage"] 	= "The year of birth date is invalid!";
		echo json_encode($response);
		exit();		
	}

	
	$tstamp 	= time();
	$inputtime 	= date("Y-m-d H:i:s", $tstamp);
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$query = "SELECT id FROM puti_members WHERE deleted <> 1 AND 
				( 	email = '" . $_REQUEST["email"] . "' OR  
					replace(replace(phone,' ',''),'-','') = '" . str_replace(array(" ","-"), array("",""), $_REQUEST["phone"]) . "' OR 
					replace(replace(cell,' ',''),'-','') = '" . str_replace(array(" ","-"), array("",""), $_REQUEST["cell"]) . "' 					
				) AND first_name = '" . cTYPE::utrans($_REQUEST["first_name"]) . "' AND 
				last_name = '" . cTYPE::utrans($_REQUEST["last_name"]) . "'";

	$result = $db->query( $query );
	if( $db->row_nums($result) > 0  && trim($_REQUEST["email"]) != "")  {
		  $row = $db->fetch($result);
		  $member_id = $row["id"];	

		  $fields = array();
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  $fields["last_updated"] 		= time();
		  $fields["last_login"] 		= 0;
	  
		  $fields["first_name"] 		= cTYPE::uword($_REQUEST["first_name"]);
		  $fields["last_name"] 			= cTYPE::uword($_REQUEST["last_name"]);
		  $fields["legal_first"] 		= cTYPE::uword($_REQUEST["legal_first"]);
		  $fields["legal_last"] 		= cTYPE::uword($_REQUEST["legal_last"]);
		  $fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
		  $fields["temp_dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
		  $fields["dharma_pinyin"] 		= cTYPE::uword($_REQUEST["dharma_pinyin"]);
		  $fields["temp_dharma_pinyin"] = cTYPE::uword($_REQUEST["dharma_pinyin"]);

		  $fields["apply_date"] 		= time();
		  $fields["alias"] 				= cTYPE::ufirst($_REQUEST["alias"]);
		  $fields["identify_no"] 		= $_REQUEST["identify_no"];
		  $fields["gender"] 			= $_REQUEST["gender"];

		  $fields["member_yy"] 			= $_REQUEST["member_yy"]<=0?0:$_REQUEST["member_yy"];
		  $fields["member_mm"] 			= $_REQUEST["member_mm"];
		  $fields["member_dd"] 			= $_REQUEST["member_dd"];

		$fields["degree"] 		    = $_REQUEST["degree"];
		$fields["current_position"] = $_REQUEST["current_position"];
		$fields["past_position"] 	= $_REQUEST["past_position"];
		$fields["religion"] 		= $_REQUEST["religion"];


		  $fields["birth_yy"] 			= $_REQUEST["birth_yy"]<=0?0:$_REQUEST["birth_yy"];
		  $fields["birth_mm"] 			= $_REQUEST["birth_mm"];
		  $fields["birth_dd"] 			= $_REQUEST["birth_dd"];
		  $fields["age"] 				= cTYPE::ageRange($_REQUEST["birth_yy"] ,$_REQUEST["age"]);
		  //$fields["birth_date"] 		= cTYPE::datetoint($_REQUEST["birth_date"]);
		  
		  $fields["language"] 			= $_REQUEST["member_lang"]?$_REQUEST["member_lang"]:0;

          $fields["email_flag"] 		= $_REQUEST["email_flag"]?$_REQUEST["email_flag"]:0;
		  $fields["email"] 				= $_REQUEST["email"];
		  $fields["phone"] 				= cTYPE::phone($_REQUEST["phone"]);
		  $fields["cell"] 				= cTYPE::phone($_REQUEST["cell"]);
		  $fields["contact_method"] 	= $_REQUEST["contact_method"];
		  
		  $fields["address"] 			= cTYPE::utrans($_REQUEST["address"]);
		  $fields["city"] 				= cTYPE::utrans($_REQUEST["city"]);
		  $fields["state"] 				= cTYPE::utrans($_REQUEST["state"]);
		  $fields["country"] 			= cTYPE::utrans($_REQUEST["country"]);
		  $fields["postal"] 			= strtoupper($_REQUEST["postal"]);
		  $fields["site"] 				= $admin_user["site"];
		  $fields["online"] 			= 0;
		  $fields["operator"] 			= $admin_user["id"];
	  
		  $result = $db->update("puti_members", $member_id, $fields);

		  $response["errorMessage"]	= "<br>Email '" . $_REQUEST["email"] . "' already exists in our database.<br><br>Your submit has been updated to existing account successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
	} else {
		  $fields = array();
		  
		  $fields["status"] 			= 1;
		  $fields["deleted"] 			= 0;
		  $fields["created_time"]		= time();
		  $fields["last_updated"] 		= 0;
		  $fields["last_login"] 		= 0;
	  
		  $fields["first_name"] 		= cTYPE::uword($_REQUEST["first_name"]);
		  $fields["last_name"] 			= cTYPE::uword($_REQUEST["last_name"]);
		  $fields["legal_first"] 		= cTYPE::uword($_REQUEST["legal_first"]);
		  $fields["legal_last"] 		= cTYPE::uword($_REQUEST["legal_last"]);
		  $fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
		  $fields["temp_dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
		  $fields["dharma_pinyin"] 		= cTYPE::uword($_REQUEST["dharma_pinyin"]);
		  $fields["temp_dharma_pinyin"] = cTYPE::uword($_REQUEST["dharma_pinyin"]);

		  $fields["apply_date"] 		= time();
		  $fields["alias"] 				= cTYPE::ufirst($_REQUEST["alias"]);

		  $fields["identify_no"] 		= $_REQUEST["identify_no"];
		  $fields["gender"] 			= $_REQUEST["gender"];

		  $fields["member_yy"] 			= $_REQUEST["member_yy"]<=0?0:$_REQUEST["member_yy"];
		  $fields["member_mm"] 			= $_REQUEST["member_mm"];
		  $fields["member_dd"] 			= $_REQUEST["member_dd"];

		$fields["degree"] 		    = $_REQUEST["degree"];
		$fields["current_position"] = $_REQUEST["current_position"];
		$fields["past_position"] 	= $_REQUEST["past_position"];
		$fields["religion"] 		= $_REQUEST["religion"];

		  $fields["birth_yy"] 			= $_REQUEST["birth_yy"]<=0?0:$_REQUEST["birth_yy"];
		  $fields["birth_mm"] 			= $_REQUEST["birth_mm"];
		  $fields["birth_dd"] 			= $_REQUEST["birth_dd"];
		  $fields["age"] 				= cTYPE::ageRange($_REQUEST["birth_yy"] ,$_REQUEST["age"]);
		  //$fields["birth_date"] 		= cTYPE::datetoint($_REQUEST["birth_date"]);

		  $fields["language"] 			= $_REQUEST["member_lang"]?$_REQUEST["member_lang"]:0;
  	      $fields["email_flag"] 		= $_REQUEST["email_flag"]?$_REQUEST["email_flag"]:0;
		  
		  $fields["email"] 				= $_REQUEST["email"];
		  $fields["phone"] 				= cTYPE::phone($_REQUEST["phone"]);
		  $fields["cell"] 				= cTYPE::phone($_REQUEST["cell"]);
		  $fields["contact_method"] 	= $_REQUEST["contact_method"];
		  
		  $fields["address"] 			= cTYPE::utrans($_REQUEST["address"]);
		  $fields["city"] 				= cTYPE::utrans($_REQUEST["city"]);
		  $fields["state"] 				= cTYPE::utrans($_REQUEST["state"]);
		  $fields["country"] 			= cTYPE::utrans($_REQUEST["country"]);
		  $fields["postal"] 			= strtoupper($_REQUEST["postal"]);
		  $fields["site"] 				= $admin_user["site"];
		  $fields["online"] 			= 0;
		  $fields["operator"] 			= $admin_user["id"];
	  
		  $member_id = $db->insert("puti_members", $fields);

		  $response["errorMessage"]	= "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
	}
	
	// update ID CARD
	if(trim($_REQUEST["idd"]) != "") {
		$db->query("DELETE FROM puti_idd WHERE idd = '" . trim($_REQUEST["idd"]) . "'");
		$fields = array();
		$fields["member_id"] 			= $member_id;
		$fields["idd"] 				= trim($_REQUEST["idd"]);
		$fields["status"] 			= 0;
		$fields["deleted"] 			= 0;
		$fields["created_time"]		= time();
		$db->insert("puti_idd", $fields);
	}


	$ccc = array();
	$ccc["member_id"] = $member_id;

	$fields = array();
	$fields["emergency_name"] 			= cTYPE::uword($_REQUEST["emergency_name"]);
	$fields["emergency_phone"] 			= cTYPE::phone($_REQUEST["emergency_phone"]);
	$fields["emergency_ship"] 			= cTYPE::utrans($_REQUEST["emergency_ship"]);
	$fields["therapy"] 					= $_REQUEST["therapy"];
	$fields["therapy_content"] 			= cTYPE::utrans($_REQUEST["therapy_content"]);
	$fields["medical_concern"] 			= cTYPE::utrans($_REQUEST["medical_concern"]);
	$fields["other_symptom"] 			= cTYPE::utrans($_REQUEST["other_symptom"]);
	$fields["transportation"] 			= $_REQUEST["transportation"];
	$fields["plate_no"] 				= strtoupper($_REQUEST["plate_no"]);
	$fields["offer_carpool"] 			= $_REQUEST["offer_carpool"];
	$fields["lang_main"] 				= trim($_REQUEST["lang_main"]);
	$fields["lang_able"] 				= trim($_REQUEST["lang_able"]);
	$db->append("puti_members_others", $ccc, $fields);
	
	$db->rupdate("puti_members_lang", "member_id", $member_id, "language_id", $_REQUEST["languages"]);
	$db->rupdate("puti_members_hearfrom", "member_id", $member_id, "hearfrom_id", $_REQUEST["hear_about"]);
	$db->rupdate("puti_members_symptom", "member_id", $member_id, "symptom_id", $_REQUEST["symptom"]);

	// add to class if select event 
	if( $_REQUEST["event_id"] != "" ) {
		  $group_no 	= $_REQUEST["group_no"]?$_REQUEST["group_no"]:0;
		  $onsite 	= $_REQUEST["onsite"]?$_REQUEST["onsite"]:0;
		  $trial 		= $_REQUEST["trial"]?$_REQUEST["trial"]:0;
		  
		  $query = "SELECT id, shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $member_id . "'";
		  $result = $db->query( $query );
		  if( $db->row_nums($result) > 0 )  {
			  // shoes shelf
			  $row = $db->fetch($result);
		      $shelf = intval($row["shelf"]);
		      if( $shelf <= 0 ) {
				  $query_del = "SELECT id, shelf FROM event_calendar_enroll WHERE deleted = 1 AND shelf > 0 AND event_id = '" . $_REQUEST["event_id"] . "' ORDER BY id ASC";
				  if( $db->exists( $query_del ) ) {
					  $result_del = $db->query( $query_del );
					  $row_del = $db->fetch( $result_del );
					  $db->query("UPDATE event_calendar_enroll SET shelf = 0 WHERE id = '" . $row_del["id"] . "'");
					  $shelf = $row_del["shelf"];
				  } else {
					  $query_max = "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "'";
					  $result_max = $db->query( $query_max );
					  $row_max = $db->fetch( $result_max );
					  $shelf = intval($row_max["max_shelf"]) + 1;
				  } 
			  }
			  // end of shoes shelf

				$query_class = "SELECT a.class_id, a.start_date FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
				$result_class = $db->query($query_class);
				$row_class = $db->fetch($result_class);
				$class_id = $row_class["class_id"];
				$evt_start_date = $row_class["start_date"];


	  		  $result_new = $db->query("SELECT count(a.id) as cnt FROM event_calendar_enroll a 
		  											INNER JOIN event_calendar b ON (a.event_id = b.id) 
													WHERE 	b.class_id = '" . $class_id . "' AND 
															a.member_id = '" . $_REQUEST["member_id"] . "' AND
															b.start_date < '" . $evt_start_date . "' AND b.deleted <> 1 AND 
													     	(a.graduate = 1 OR a.cert = 1 )");
			  $row_new = $db->fetch($result_new);
			  if($row_new["cnt"] > 0) 
			  		$new_flag = 0;
		  	  else 
		  		 	$new_flag = 1; 

			  
			  $query = "UPDATE event_calendar_enroll SET group_no = '" . $group_no . "', onsite = '" . $onsite . "', trial = '" . $trial . "', shelf = '" . $shelf . "', new_flag = '" . $new_flag . "' , deleted = 0, online = 0 WHERE event_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $member_id . "'";
			  $result = $db->query( $query );
		  } else {
			  $fields = array();
			  $fields["event_id"] 			= $_REQUEST["event_id"];
			  $fields["member_id"] 			= $member_id;
			  $fields["group_no"] 			= $group_no;
			  $fields["onsite"] 			= $onsite;
			  $fields["trial"] 				= $trial;
			  $fields["trial_date"] 		= time();

			  $query_del = "SELECT id, shelf FROM event_calendar_enroll WHERE deleted = 1 AND shelf > 0 AND event_id = '" . $_REQUEST["event_id"] . "' ORDER BY id ASC";
			  if( $db->exists( $query_del ) ) {
				  $result_del = $db->query( $query_del );
				  $row_del = $db->fetch( $result_del );
				  $db->query("UPDATE event_calendar_enroll SET shelf = 0 WHERE id = '" . $row_del["id"] . "'");
				  $shelf = $row_del["shelf"];
			  } else {
				  $query_max = "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "'";
				  $result_max = $db->query( $query_max );
				  $row_max = $db->fetch( $result_max );
				  $shelf = intval($row_max["max_shelf"]) + 1;
			  } 
			  $fields["shelf"] 			= $shelf;


				$query_class = "SELECT a.class_id, a.start_date FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
				$result_class = $db->query($query_class);
				$row_class = $db->fetch($result_class);
				$class_id = $row_class["class_id"];
				$evt_start_date = $row_class["start_date"];


	  		  $result_new = $db->query("SELECT count(a.id) as cnt FROM event_calendar_enroll a 
		  											INNER JOIN event_calendar b ON (a.event_id = b.id) 
													WHERE 	b.class_id = '" . $class_id . "' AND 
															a.member_id = '" . $_REQUEST["member_id"] . "' AND
															b.start_date < '" . $evt_start_date . "' AND b.deleted <> 1 AND 
													     	(a.graduate = 1 OR a.cert = 1 )");
			  $row_new = $db->fetch($result_new);
			  if($row_new["cnt"] > 0) 
					$fields["new_flag"] = 0;
			  else 
					$fields["new_flag"] = 1; 


			  $fields["status"] 				= 1;
			  $fields["online"] 				= 0;
			  $fields["deleted"] 				= 0;
			  $fields["created_time"] 		= time();
			  $enroll_id = $db->insert("event_calendar_enroll", $fields);
		  }
	}
	
	
	$response["data"]["event_id"] = $_REQUEST["event_id"];
	$response["data"]["member_id"] = $member_id;
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
