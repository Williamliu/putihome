<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

$response = array();
try {

	$type["event_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "event_id", 		"name":"Event ID", 		"nullable":0}';
	$type["member_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 		"name":"Member ID", 	"nullable":0}';
	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["legal_first"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_first", 	"name":"Legal First", 	"nullable":1}';
	$type["legal_last"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_last", 	"name":"Legal Last", 	"nullable":1}';
	$type["gender"]				= '{"type":"CHAR", 		"length":11, 	"id": "gender", 		"name":"Gender", 	 	"nullable":0}';
	
	$type["birth_yy"]			= '{"type":"NUMBER", 	"length":4, 	"id": "birth_yy", 		"name":"Birth Year", 	"nullable":1}';
	$type["birth_mm"]			= '{"type":"NUMBER", 	"length":2, 	"id": "birth_mm", 		"name":"Birth Month", 	"nullable":1}';
	$type["birth_dd"]			= '{"type":"NUMBER", 	"length":2, 	"id": "birth_dd", 		"name":"Birth Day", 	"nullable":1}';
	$type["age"]				= '{"type":"NUMBER", 	"length":11, 	"id": "age_range", 		"name":"Age Range", 	 "nullable":0}';
	//$type["birth_date"]			= '{"type":"DATE", 		"length":20, 	"id": "birth_month", 	"name":"Birth Date", 	"nullable":1}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name", 	"name":"Dharma Name", 	"nullable":1}';
	$type["alias"] 				= '{"type":"CHAR", 		"length":255, 	"id": "alias", 			"name":"Alias", 		"nullable":1}';
	$type["identify_no"] 		= '{"type":"CHAR", 		"length":31, 	"id": "identify_no", 	"name":"ID Number", 	"nullable":1}';

	$type["member_lang"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "member_lang", 	"name":"Preferred Language", "nullable":0}';
	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":0}';
	$type["password"]			= '{"type":"CHAR", 		"length":15, 	"id": "password", 		"name":"Password", 		"nullable":0}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["contact_method"]		= '{"type":"CHAR", 		"length":15, 	"id": "contact_method", "name":"Preferred method of contact", 	"nullable":1}';
	
	$type["address"]			= '{"type":"CHAR", 		"length":1023, 	"id": "address", 		"name":"Address", 		"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';
	$type["state"]				= '{"type":"CHAR", 		"length":127, 	"id": "state", 			"name":"State", 		"nullable":1}';
	$type["country"]			= '{"type":"CHAR", 		"length":127, 	"id": "country", 		"name":"Country", 		"nullable":1}';
	$type["postal"]				= '{"type":"CHAR", 		"length":15, 	"id": "postal", 		"name":"Postal", 		"nullable":1}';

	$type["emergency_name"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_name", 	"name":"Emergency Contact Person", 		"nullable":0}';
	$type["emergency_phone"]	= '{"type":"CHAR", 		"length":255, 	"id": "emergency_phone",	"name":"Emergency Contact Phone", 		"nullable":0}';
	$type["emergency_ship"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_ship", 	"name":"Emergency Relationship", 		"nullable":0}';

	$type["hear_about"]			= '{"type":"CHAR", 		"length":255, 	"id": "hear_about", 		"name":"How did you hear about us", 	"nullable":0}';

	$type["email_flag"] 		= '{"type":"NUMBER", 	"length":1, 	"id": "email_flag", 	    "name":"Email Subscription Agreement",  "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	if( $_REQUEST["birth_yy"]!="" && (intval($_REQUEST["birth_yy"]) <= ( date("Y") - 100) || intval($_REQUEST["birth_yy"]) > date("Y") )  )	 {
		$response["errorCode"] 		= 1;
		$response["errorMessage"] 	= "The year of birth date is invalid!";
		echo json_encode($response);
		exit();		
	}

	if( trim($_REQUEST["phone"])=="" && trim($_REQUEST["cell"])=="") {
		$response["errorCode"] 		= 1;
		$response["errorMessage"] 	= $words["please provide either phone number or cell phone number"];
		echo json_encode($response);
		exit();		
	}
	
	$publicSession  		= $_REQUEST["publicSession"];

	$_REQUEST["password"] 	= trim($_REQUEST["password"]);
	$_REQUEST["cpassword"] 	= trim($_REQUEST["cpassword"]);
	
	if( strlen($_REQUEST["password"]) < 6 || strlen($_REQUEST["cpassword"]) < 6 ) {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= cTYPE::gstr($words["password length tips"]);
	} elseif ( $_REQUEST["password"] != $_REQUEST["cpassword"] ) {
		$response["errorCode"] 		= 1;
	 	$response["errorMessage"]	= cTYPE::gstr($words["password not match"]);
	}

	if( $response["errorCode"] != 0 ) {
		$response["data"]["event_id"] 	= $event_id ;
		$response["data"]["member_id"] 	= $member_id;
		echo json_encode($response);
		exit();
	}

		
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$site = $db->getVal("event_calendar", "site", $_REQUEST["event_id"]); 

	$member_id 	= $_REQUEST["member_id"];
	$event_id 	= $_REQUEST["event_id"];


	$fields = array();
	$fields["status"] 			= 1;
	$fields["deleted"] 			= 0;


	$fields["first_name"] 		= cTYPE::uword($_REQUEST["first_name"]);
	$fields["last_name"] 		= cTYPE::uword($_REQUEST["last_name"]);
	$fields["gender"] 			= $_REQUEST["gender"];
	$fields["age"] 				= cTYPE::ageRange($_REQUEST["birth_yy"] ,$_REQUEST["age"]);
  	$fields["language"] 		= $_REQUEST["member_lang"]?$_REQUEST["member_lang"]:0;
  	$fields["email_flag"] 		= $_REQUEST["email_flag"]?$_REQUEST["email_flag"]:0;
	$fields["email"] 			= trim($_REQUEST["email"]);
	$fields["password"] 		= trim($_REQUEST["password"]);
	$fields["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
	$fields["cell"] 			= cTYPE::phone($_REQUEST["cell"]);
	$fields["online"] 			= 1;

	$query_mem = "SELECT * FROM puti_members WHERE deleted <> 1 AND email = '" . trim($_REQUEST["email"]) ."'";
	if($db->exists($query_mem)) {
		$result_mem 		= $db->query($query_mem);
		$row_mem 			= $db->fetch($result_mem);

		$fields["legal_first"] 		= mval($row_mem["legal_first"],cTYPE::uword($_REQUEST["legal_first"]), "");
		$fields["legal_last"] 		= mval($row_mem["legal_last"],cTYPE::uword($_REQUEST["legal_last"]), ""); 
		$fields["dharma_name"] 		= mval($row_mem["dharma_name"],cTYPE::utrans($_REQUEST["legal_last"]), "");
		$fields["alias"] 			= mval($row_mem["alias"],cTYPE::ufirst($_REQUEST["alias"]), ""); 
		$fields["identify_no"] 		= mval($row_mem["identify_no"],strtoupper($_REQUEST["identify_no"]), "");
		$fields["birth_yy"] 		= mval($row_mem["birth_yy"], ($_REQUEST["birth_yy"]<=0?0:$_REQUEST["birth_yy"]),"");
		$fields["birth_mm"] 		= mval($row_mem["birth_mm"], $_REQUEST["birth_mm"],"");
		$fields["birth_dd"] 		= mval($row_mem["birth_dd"], $_REQUEST["birth_dd"],"");
		$fields["contact_method"] 	= mval($row_mem["contact_method"], $_REQUEST["contact_method"],"");
		$fields["address"] 			= mval($row_mem["address"], cTYPE::utrans($_REQUEST["address"]),"");
		$fields["city"] 			= mval($row_mem["city"], cTYPE::utrans($_REQUEST["city"]),"");
		$fields["state"] 			= mval($row_mem["state"], cTYPE::utrans($_REQUEST["state"]),"");
		$fields["country"] 			= mval($row_mem["country"],cTYPE::utrans($_REQUEST["country"]),"");
		$fields["postal"] 			= mval($row_mem["postal"], strtoupper($_REQUEST["postal"]),"");

	} else {
		$fields["legal_first"] 		= cTYPE::uword($_REQUEST["legal_first"]);
		$fields["legal_last"] 		= cTYPE::uword($_REQUEST["legal_last"]);
		$fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
		$fields["alias"] 			= cTYPE::ufirst($_REQUEST["alias"]);
		$fields["identify_no"] 		= strtoupper($_REQUEST["identify_no"]);
		if($_REQUEST["birth_yy"]!="") $fields["birth_yy"] = $_REQUEST["birth_yy"]<=0?0:$_REQUEST["birth_yy"];
		if($_REQUEST["birth_mm"]!="") $fields["birth_mm"] = $_REQUEST["birth_mm"];
		if($_REQUEST["birth_dd"]!="") $fields["birth_dd"] = $_REQUEST["birth_dd"];
		$fields["contact_method"] 	= $_REQUEST["contact_method"];
		$fields["address"] 			= cTYPE::utrans($_REQUEST["address"]);
		$fields["city"] 			= cTYPE::utrans($_REQUEST["city"]);
		$fields["state"] 			= cTYPE::utrans($_REQUEST["state"]);
		$fields["country"] 			= cTYPE::utrans($_REQUEST["country"]);
		$fields["postal"] 			= strtoupper($_REQUEST["postal"]);
	}


	if( $member_id > 0 )  {

		  $fields["last_updated"] 		= time();
		  $db->update("puti_members", $member_id, $fields);

	} else {
		  $query = "SELECT * FROM puti_members WHERE deleted <> 1 AND email = '" . trim($_REQUEST["email"]) ."'";
		  if($db->exists($query)) {
				$result 		= $db->query($query);
				$row 			= $db->fetch($result);
				$member_id 	= $row["id"];

				$fields["last_updated"] 		= time();
				$db->update("puti_members", $member_id, $fields);
		  } else {
				$fields["site"] 			= $site;
				$fields["created_time"]		= time();
				$fields["last_updated"] 	= 0;

				$fields["member_yy"] 		= date("Y");
				$fields["member_mm"] 		= date("n");
				$fields["member_dd"] 		= date("j");
			
				$member_id = $db->insert("puti_members", $fields);
		  } // end of insert member
	}
	

	$ccc = array();
	$ccc["member_id"] = $member_id;

	$fields = array();
	$fields["emergency_name"] 			= cTYPE::uword($_REQUEST["emergency_name"]);
	$fields["emergency_phone"] 			= cTYPE::phone($_REQUEST["emergency_phone"]);
	$fields["emergency_ship"] 			= cTYPE::utrans($_REQUEST["emergency_ship"]);
	$fields["therapy"] 					= $_REQUEST["therapy"]?$_REQUEST["therapy"]:0;
	$fields["therapy_content"] 			= cTYPE::utrans($_REQUEST["therapy_content"]);
	$fields["medical_concern"] 			= cTYPE::utrans($_REQUEST["medical_concern"]);
	$fields["other_symptom"] 			= cTYPE::utrans($_REQUEST["other_symptom"]);
	$fields["transportation"] 			= $_REQUEST["transportation"]?$_REQUEST["transportation"]:0;
	$fields["plate_no"] 				= strtoupper($_REQUEST["plate_no"]);
	$fields["offer_carpool"] 			= $_REQUEST["offer_carpool"]?$_REQUEST["offer_carpool"]:0;
	$fields["lang_main"] 				= trim($_REQUEST["lang_main"]);
	$fields["lang_able"] 				= trim($_REQUEST["lang_able"]);
	$db->append("puti_members_others", $ccc, $fields);
	
	$db->rupdate("puti_members_lang", "member_id", $member_id, "language_id", $_REQUEST["languages"]);
	$db->rupdate("puti_members_hearfrom", "member_id", $member_id, "hearfrom_id", $_REQUEST["hear_about"]);
	$db->rupdate("puti_members_symptom", "member_id", $member_id, "symptom_id", $_REQUEST["symptom"]);

	
		
	// rename image name
	$o_old = $CFG["upload_path"] . "/original/" . $_REQUEST["image_id"] . ".jpg";
	$o_new = $CFG["upload_path"] . "/original/" . $member_id . ".jpg";
	if( file_exists($o_old) ) rename($o_old, $o_new);

	$o_old = $CFG["upload_path"] . "/large/" . $_REQUEST["image_id"] . ".jpg";
	$o_new = $CFG["upload_path"] . "/large/" . $member_id . ".jpg";
	if( file_exists($o_old) ) rename($o_old, $o_new);

	$o_old = $CFG["upload_path"] . "/medium/" . $_REQUEST["image_id"] . ".jpg";
	$o_new = $CFG["upload_path"] . "/medium/" . $member_id . ".jpg";
	if( file_exists($o_old) ) rename($o_old, $o_new);

	$o_old = $CFG["upload_path"] . "/small/" . $_REQUEST["image_id"] . ".jpg";
	$o_new = $CFG["upload_path"] . "/small/" . $member_id . ".jpg";
	if( file_exists($o_old) ) rename($o_old, $o_new);

	$o_old = $CFG["upload_path"] . "/tiny/" . $_REQUEST["image_id"] . ".jpg";
	$o_new = $CFG["upload_path"] . "/tiny/" . $member_id . ".jpg";
	if( file_exists($o_old) ) rename($o_old, $o_new);

	

	// enroll member to class
	$query = "SELECT id, shelf FROM event_calendar_enroll WHERE event_id = '" . $event_id . "' AND member_id = '" . $member_id . "'";
	$result = $db->query( $query );
	if( $db->row_nums($result) > 0 )  {
		// shoes shelf
		$row = $db->fetch($result);
		$shelf = intval($row["shelf"]);
		if( $shelf <= 0 ) {
			$query_del = "SELECT id, shelf FROM event_calendar_enroll WHERE deleted = 1 AND shelf > 0 AND event_id = '" . $event_id . "' ORDER BY id ASC";
			if( $db->exists( $query_del ) ) {
				$result_del = $db->query( $query_del );
				$row_del = $db->fetch( $result_del );
				$db->query("UPDATE event_calendar_enroll SET shelf = 0 WHERE id = '" . $row_del["id"] . "'");
				$shelf = $row_del["shelf"];
			} else {
				$query_max = "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $event_id . "'";
				$result_max = $db->query( $query_max );
				$row_max = $db->fetch( $result_max );
				$shelf = intval($row_max["max_shelf"]) + 1;
			} 
		}
		// end of shoes shelf

		// new people
		$query_class = "SELECT a.class_id, a.start_date FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $event_id . "'";
		$result_class = $db->query($query_class);
		$row_class = $db->fetch($result_class);
		$class_id = $row_class["class_id"];
		$evt_start_date = $row_class["start_date"];


		$result_new = $db->query("SELECT count(a.id) as cnt FROM event_calendar_enroll a 
												INNER JOIN event_calendar b ON (a.event_id = b.id) 
												WHERE 	b.class_id = '" . $class_id . "' AND 
														a.member_id = '" . $member_id . "' AND
														b.start_date < '" . $evt_start_date . "' AND b.deleted <> 1 AND 
														(a.graduate = 1 OR a.cert = 1 )");
		$row_new = $db->fetch($result_new);
		if($row_new["cnt"] > 0) 
			$new_flag = 0;
		else 
			$new_flag = 1; 



		$query = "UPDATE event_calendar_enroll SET group_no = 0, deleted = 0, online = 1, new_flag = '" . $new_flag . "', shelf = '" . $shelf . "' WHERE event_id = '" . $event_id . "' AND member_id = '" . $member_id . "'";
		$result = $db->query( $query );
	} else {
		$fields = array();
		$fields["event_id"] 		= $event_id;
		$fields["member_id"] 		= $member_id;
		$fields["group_no"] 		= 0;
		$fields["status"] 			= 1;
		$fields["online"] 			= 1;
		$fields["deleted"] 			= 0;

		$query_del = "SELECT id, shelf FROM event_calendar_enroll WHERE deleted = 1 AND shelf > 0 AND event_id = '" . $event_id . "' ORDER BY id ASC";
		if( $db->exists( $query_del ) ) {
			$result_del = $db->query( $query_del );
			$row_del = $db->fetch( $result_del );
			$db->query("UPDATE event_calendar_enroll SET shelf = 0 WHERE id = '" . $row_del["id"] . "'");
			$shelf = $row_del["shelf"];
		} else {
			$query_max = "SELECT MAX(shelf) as max_shelf FROM event_calendar_enroll WHERE event_id = '" . $event_id . "'";
			$result_max = $db->query( $query_max );
			$row_max = $db->fetch( $result_max );
			$shelf = intval($row_max["max_shelf"]) + 1;
		} 
		$fields["shelf"] 			= $shelf;
		
		// new people
		$query_class = "SELECT a.class_id, a.start_date FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $event_id . "'";
		$result_class = $db->query($query_class);
		$row_class = $db->fetch($result_class);
		$class_id = $row_class["class_id"];
		$evt_start_date = $row_class["start_date"];


		$result_new = $db->query("SELECT count(a.id) as cnt FROM event_calendar_enroll a 
												INNER JOIN event_calendar b ON (a.event_id = b.id) 
												WHERE 	b.class_id = '" . $class_id . "' AND 
														a.member_id = '" . $member_id . "' AND
														b.start_date < '" . $evt_start_date . "' AND b.deleted <> 1 AND 
														(a.graduate = 1 OR a.cert = 1 )");
		$row_new = $db->fetch($result_new);
		if($row_new["cnt"] > 0) 
			$new_flag = 0;
		else 
			$new_flag = 1; 
		
		$fields["new_flag"] = $new_flag;

		$fields["created_time"] 	= time();
		$enroll_id = $db->insert("event_calendar_enroll", $fields);
	}



	// member update session
	// login session
	//if( $_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] == "" ) {
		$login_time = time();
		$sess_id  = md5($member_id . $login_time);
		$db->query("UPDATE puti_members SET hits = hits + 1, last_login = '". time() ."', sess_exp = '" . (time() + 3600 * 2) . "', sess_id = '" . $sess_id . "' WHERE id = '" . $member_id . "'");
		
		$_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] = $sess_id;
		$publicSession = $_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"];
	//}



	
  	$response["errorCode"] 				= 0;
	$response["data"]["publicSession"] 	= $publicSession;
	$response["data"]["event_id"] 		= $event_id ;
	$response["data"]["member_id"] 		= $member_id;

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

function mval($fval, $tval, $dval) {
	if($tval=="") $tval = $fval;
	if($tval=="") $tval = $dval;
	return $tval;
}
?>
