<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$type["member_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 	"name":"Member ID", 		"nullable":0}';
	$type["first_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "first_name", 	"name":"First Name", 	"nullable":0}';
	$type["last_name"] 			= '{"type":"CHAR", 		"length":255, 	"id": "last_name", 		"name":"Last Name", 	"nullable":0}';
	$type["legal_first"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_first", 	"name":"Legal First", 	"nullable":1}';
	$type["legal_last"] 		= '{"type":"CHAR", 		"length":255, 	"id": "legal_last", 	"name":"Legal Last", 	"nullable":1}';
	$type["dharma_name"] 		= '{"type":"CHAR", 		"length":255, 	"id": "dharma_name",	"name":"Dharma Name", 	"nullable":1}';
	$type["alias"] 				= '{"type":"CHAR", 		"length":255, 	"id": "alias",			"name":"Alias", 		"nullable":1}';
	$type["identify_no"] 		= '{"type":"CHAR", 		"length":31, 	"id": "identify_no", 	"name":"ID Number", 	"nullable":1}';
	$type["level"] 				= '{"type":"NUMBER", 	"length":11, 	"id": "level", 			"name":"Level", 		"nullable":1}';
	$type["gender"]				= '{"type":"CHAR", 		"length":11, 	"id": "gender", 		"name":"Gender", 	 	"nullable":0}';
	$type["age"]				= '{"type":"NUMBER", 	"length":11, 	"id": "age_range", 		"name":"Age Range", 	"nullable":1}';
	$type["birth_yy"]			= '{"type":"NUMBER", 	"length":4, 	"id": "birth_yy", 		"name":"Birth Year", 	"nullable":1}';
	$type["birth_mm"]			= '{"type":"NUMBER", 	"length":2, 	"id": "birth_mm", 		"name":"Birth Month", 	"nullable":1}';
	$type["birth_dd"]			= '{"type":"NUMBER", 	"length":2, 	"id": "birth_dd", 		"name":"Birth Day", 	"nullable":1}';

	$type["member_yy"]			= '{"type":"NUMBER", 	"length":4, 	"id": "member_yy", 		"name":"Member Year", 	"nullable":1}';
	$type["member_mm"]			= '{"type":"NUMBER", 	"length":2, 	"id": "member_mm", 		"name":"Member Month", 	"nullable":1}';
	$type["member_dd"]			= '{"type":"NUMBER", 	"length":2, 	"id": "member_dd", 		"name":"Member Day", 	"nullable":1}';
	$type["memo"]				= '{"type":"CHAR", 		"length":255, 	"id": "memo", 			"name":"Notes", 		"nullable":1}';

	$type["email_flag"]			= '{"type":"NUMBER", 	"length":1, 	"id": "email_falg", 	"name":"Email Subscription", "nullable":1}';
	$type["email"]				= '{"type":"EMAIL", 	"length":1023, 	"id": "email", 			"name":"Email", 		"nullable":1}';
	$type["phone"]				= '{"type":"CHAR", 		"length":31, 	"id": "phone", 			"name":"Phone", 		"nullable":1}';
	$type["cell"]				= '{"type":"CHAR", 		"length":31, 	"id": "cell", 			"name":"Cell", 			"nullable":1}';
	$type["contact_method"]		= '{"type":"CHAR", 		"length":15, 	"id": "contact_method", "name":"Preferred method of contact", 	"nullable":1}';
	$type["status"]				= '{"type":"NUMBER", 	"length":1, 	"id": "status", 		"name":"Status", 		"nullable":0}';
	$type["idd"]				= '{"type":"CHAR", 		"length":31, 	"id": "idd", 			"name":"ID Number", 	"nullable":1}';
	
	$type["address"]			= '{"type":"CHAR", 		"length":1023, 	"id": "address", 		"name":"Address", 		"nullable":1}';
	$type["city"]				= '{"type":"CHAR", 		"length":127, 	"id": "city", 			"name":"City", 			"nullable":1}';
	$type["site"]				= '{"type":"NUMBER", 	"length":11, 	"id": "site", 			"name":"Site", 			"nullable":0}';
	$type["state"]				= '{"type":"CHAR", 		"length":127, 	"id": "state", 			"name":"State", 		"nullable":1}';
	$type["country"]			= '{"type":"CHAR", 		"length":127, 	"id": "country", 		"name":"Country", 		"nullable":1}';
	$type["postal"]				= '{"type":"CHAR", 		"length":15, 	"id": "postal", 		"name":"Postal", 		"nullable":1}';

	$type["emergency_name"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_name", 	"name":"Emergency Contact Person", 		"nullable":1}';
	$type["emergency_phone"]	= '{"type":"CHAR", 		"length":255, 	"id": "emergency_phone",	"name":"Emergency Contact Phone", 		"nullable":1}';
	$type["emergency_ship"]		= '{"type":"CHAR", 		"length":255, 	"id": "emergency_ship", 	"name":"Emergency Relationship", 		"nullable":1}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	if( $_REQUEST["birth_yy"]!="" && (intval($_REQUEST["birth_yy"]) <= ( date("Y") - 100) || intval($_REQUEST["birth_yy"]) > date("Y") )  )	 {
		$response["errorCode"] 		= 1;
		$response["errorMessage"] 	= "The year of birth date is invalid!";
		echo json_encode($response);
		exit();		
	}

	if( $_REQUEST["member_yy"]!="" && (intval($_REQUEST["member_yy"]) <= ( date("Y") - 100) || intval($_REQUEST["member_yy"]) > date("Y") )  )	 {
		$response["errorCode"] 		= 1;
		$response["errorMessage"] 	= "The year of member registeration date is invalid!";
		echo json_encode($response);
		exit();		
	}
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}
	$ages[0] = "";


	$member_id = $_REQUEST["member_id"];
	
	$fields = array();
	
	$fields["status"] 			= $_REQUEST["status"];
	$fields["last_updated"] 	= time();
	
	$fields["first_name"] 		= cTYPE::uword($_REQUEST["first_name"]);
	$fields["last_name"]		= cTYPE::uword($_REQUEST["last_name"]);
	$fields["legal_first"] 		= cTYPE::uword($_REQUEST["legal_first"]);
	$fields["legal_last"] 		= cTYPE::uword($_REQUEST["legal_last"]);
	$fields["dharma_name"] 		= cTYPE::utrans($_REQUEST["dharma_name"]);
	$fields["dharma_pinyin"] 	= cTYPE::uword($_REQUEST["dharma_pinyin"]);
	$fields["alias"]			= cTYPE::ufirst($_REQUEST["alias"]);
	$fields["identify_no"] 		= $_REQUEST["identify_no"];
	$fields["level"] 			= $_REQUEST["level"];
	$fields["gender"] 			= $_REQUEST["gender"];
	$fields["birth_yy"] 		= $_REQUEST["birth_yy"]<=0?0:$_REQUEST["birth_yy"];
	$fields["birth_mm"] 		= $_REQUEST["birth_mm"];
	$fields["birth_dd"] 		= $_REQUEST["birth_dd"];
	$fields["age"] 				= cTYPE::ageRange($_REQUEST["birth_yy"] ,$_REQUEST["age"]);
	//$fields["birth_date"] 		= cTYPE::datetoint($_REQUEST["birth_date"]);

	$fields["member_yy"] 		= $_REQUEST["member_yy"]<=0?0:$_REQUEST["member_yy"];
	$fields["member_mm"] 		= $_REQUEST["member_mm"];
	$fields["member_dd"] 		= $_REQUEST["member_dd"];
	$fields["memo"] 			= $_REQUEST["memo"];

	$fields["degree"] 		    = $_REQUEST["degree"];
	$fields["current_position"] = $_REQUEST["current_position"];
	$fields["past_position"] 	= $_REQUEST["past_position"];
	$fields["religion"] 		= $_REQUEST["religion"];


	$fields["dharma_yy"] 		= $_REQUEST["dharma_yy"]<=0?0:$_REQUEST["dharma_yy"];
	$fields["dharma_mm"] 		= $_REQUEST["dharma_mm"];
	$fields["dharma_dd"] 		= $_REQUEST["dharma_dd"];


	$fields["language"] 		= $_REQUEST["member_lang"]?$_REQUEST["member_lang"]:0;
	$fields["email"] 			= $_REQUEST["email"];
	$fields["email_flag"] 		= $_REQUEST["email_flag"]?$_REQUEST["email_flag"]:0;
	$fields["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
	$fields["cell"] 			= cTYPE::phone($_REQUEST["cell"]);
	$fields["contact_method"] 	= $_REQUEST["contact_method"];
	
	$fields["address"] 			= cTYPE::utrans($_REQUEST["address"]);
	$fields["city"] 			= cTYPE::utrans($_REQUEST["city"]);
	$fields["site"] 			= $_REQUEST["site"];
	$fields["state"] 			= cTYPE::utrans($_REQUEST["state"]);
	$fields["country"] 			= cTYPE::utrans($_REQUEST["country"]);
	$fields["postal"] 			= strtoupper($_REQUEST["postal"]);
	$fields["operator"] 		= $admin_user["id"];
	$result = $db->update("puti_members", $_REQUEST["member_id"], $fields);


	if(trim($_REQUEST["idd"]) != "") {
		$db->query("DELETE FROM puti_idd WHERE idd = '" . trim($_REQUEST["idd"]) . "'");
		$fields = array();
		$fields["member_id"] 		= $_REQUEST["member_id"];
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

	$response["data"]["member_id"] 		= $_REQUEST["member_id"];

	$names								= array();
	$names["first_name"] 				= cTYPE::uword($_REQUEST["first_name"]);
	$names["last_name"] 				= cTYPE::uword($_REQUEST["last_name"]);
	$response["data"]["name"] 			= cTYPE::lfname($names);

	$names								= array();
	$names["first_name"] 				= cTYPE::uword($_REQUEST["first_name"]);
	$names["last_name"] 				= cTYPE::uword($_REQUEST["last_name"]);
	$names["dharma_name"] 				= $_REQUEST["dharma_name"];
	$names["alias"] 					= $_REQUEST["alias"];
	$response["data"]["aname"] 			= cTYPE::lfname($names);

	$names								= array();
	$names["first_name"] 				= cTYPE::uword($_REQUEST["first_name"]);
	$names["last_name"] 				= cTYPE::uword($_REQUEST["last_name"]);
	//$names["legal_first"] 				= cTYPE::uword($_REQUEST["legal_first"]);
	//$names["legal_last"] 				= cTYPE::uword($_REQUEST["legal_last"]);
	$response["data"]["flname"] 		= cTYPE::lfname($names);


	$response["data"]["first_name"] 	= cTYPE::uword($_REQUEST["first_name"]);
	$response["data"]["last_name"] 		= cTYPE::uword($_REQUEST["last_name"]);
	$response["data"]["legal_first"] 	= cTYPE::uword($_REQUEST["legal_first"]);
	$response["data"]["legal_last"] 	= cTYPE::uword($_REQUEST["legal_last"]);

	$response["data"]["legal_name"] 	= cTYPE::uword($_REQUEST["legal_last"]) . (cTYPE::uword($_REQUEST["legal_last"])!=""?", ":"") . cTYPE::uword($_REQUEST["legal_first"]);

	$response["data"]["dharma_name"] 	= cTYPE::utrans($_REQUEST["dharma_name"]);
	$response["data"]["alias"] 			= cTYPE::ufirst($_REQUEST["alias"]);
	$response["data"]["identify_no"]	= $_REQUEST["identify_no"];
	$response["data"]["level"]			= $_REQUEST["level"];
	$response["data"]["language"] 		= $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]);
	$response["data"]["gender"] 		= $_REQUEST["gender"];
	$response["data"]["sex"] 			= $_REQUEST["gender"];
	$response["data"]["age"] 			= $ages[$_REQUEST["age"]];
	$response["data"]["birth_yy"] 		= $_REQUEST["birth_yy"]>0?$_REQUEST["birth_yy"]:"";
	$response["data"]["birth_mm"] 		= $_REQUEST["birth_mm"];
	$response["data"]["birth_dd"] 		= $_REQUEST["birth_dd"];
	$response["data"]["language"] 		= $db->getVal("website_basic_table",($admin_user["lang"]=="en"?"title_en":"title_cn"), ($_REQUEST["member_lang"]?$_REQUEST["member_lang"]:0) );
	$response["data"]["email"] 			= $_REQUEST["email"];
	$response["data"]["phone"] 			= cTYPE::phone($_REQUEST["phone"]);
	$response["data"]["phone"] 			.= ($_REQUEST["cell"]!=""?"<br>":"") . cTYPE::phone($_REQUEST["cell"]);
	$response["data"]["city"] 			= $_REQUEST["city"];
	$response["data"]["site"] 			= $words[strtolower($sites[$_REQUEST["site"]])];
	$response["data"]["photo"] 			= file_exists($CFG["upload_path"] . "/small/" . $_REQUEST["member_id"] . ".jpg")?"Y":"";

	$response["errorMessage"]	= "<br>学员信息已经成功保存.";
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
