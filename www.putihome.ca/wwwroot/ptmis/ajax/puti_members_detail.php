<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["member_id"] = '{"type":"NUMBER", 	"length":11, 	"id": "member_id", 		"name":"Member ID", 		"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query = "SELECT * FROM puti_members WHERE deleted <> 1 AND id = '" . $_REQUEST["member_id"] . "'";
	//echo "query: " . $query;
	$result = $db->query( $query );
	$row = $db->fetch($result);
	
	$response["data"]["id"] 			= $row["id"];
	$response["data"]["member_id"] 		= $row["id"];
	$response["data"]["first_name"] 	= cTYPE::gstr($row["first_name"]);
	$response["data"]["last_name"] 		= cTYPE::gstr($row["last_name"]);
	$response["data"]["legal_first"] 	= cTYPE::gstr($row["legal_first"]);
	$response["data"]["legal_last"] 	= cTYPE::gstr($row["legal_last"]);
	$response["data"]["dharma_name"] 	= cTYPE::gstr($row["dharma_name"]);
	$response["data"]["dharma_pinyin"] 	= cTYPE::gstr($row["dharma_pinyin"]);
	$response["data"]["dharma_date"] 	= $row["dharma_date"]>0?date("Y-m-d",$row["dharma_date"]):"";
	$response["data"]["alias"] 			= cTYPE::gstr($row["alias"]);
	$response["data"]["identify_no"] 	= stripslashes($row["identify_no"]);
	$response["data"]["level"] 			= $row["level"];
	$response["data"]["gender"] 		= $row["gender"];
	$response["data"]["age"] 			= $row["age"];
	$response["data"]["birth_yy"] 		= $row["birth_yy"]<=0?"":$row["birth_yy"];
	$response["data"]["birth_mm"] 		= $row["birth_mm"];
	$response["data"]["birth_dd"] 		= $row["birth_dd"];

	$response["data"]["degree"] 		   = $row["degree"];
	$response["data"]["current_position"]  = $row["current_position"];
	$response["data"]["past_position"] 	   = $row["past_position"];
	$response["data"]["religion"] 		   = $row["religion"];

	$response["data"]["dharma_yy"] 		= $row["dharma_yy"]<=0?"":$row["dharma_yy"];
	$response["data"]["dharma_mm"] 		= $row["dharma_mm"];
	$response["data"]["dharma_dd"] 		= $row["dharma_dd"];

	$response["data"]["member_yy"] 		= $row["member_yy"]<=0?"":$row["member_yy"];
	$response["data"]["member_mm"] 		= $row["member_mm"];
	$response["data"]["member_dd"] 		= $row["member_dd"];
	$response["data"]["memo"] 			= $row["memo"];
	
	//$response["data"]["birth_year"]		= $row["birth_date"]?date("Y", $row["birth_date"]):"";
	//$response["data"]["birth_month"]	= $row["birth_date"]?date("n", $row["birth_date"]):"";
	//$response["data"]["birth_day"]		= $row["birth_date"]?date("j", $row["birth_date"]):"";
	$response["data"]["member_lang"] 	= $row["language"];
	$response["data"]["language"] 		= $row["language"];
	$response["data"]["email"] 			= $row["email"];
	$response["data"]["email_flag"] 	= $row["email_flag"];
	$response["data"]["phone"] 			= $row["phone"];
	$response["data"]["cell"] 			= $row["cell"];
	$response["data"]["contact_method"]	= $row["contact_method"];
	$response["data"]["status"] 		= $row["status"];
	$response["data"]["online"] 		= $row["online"]?"Y":"";
	$response["data"]["idd"] 			= $row["idd"];

	$response["data"]["contact_method"]	= $row["contact_method"];
	$response["data"]["address"] 		= $row["address"];
	$response["data"]["city"] 			= cTYPE::gstr($row["city"]);
	$response["data"]["site"] 			= $row["site"];
	$response["data"]["state"] 			= $row["state"];
	$response["data"]["country"] 		= $row["country"];
	$response["data"]["postal"] 		= $row["postal"];
	$response["data"]["photo_url"] 		= "ajax/lwhUpload_image.php?ts=".time()."&size=tiny&img_id=" . $row["id"];
	$response["data"]["original_url"] 	= "ajax/lwhUpload_image.php?ts=".time()."&size=original&img_id=" . $row["id"];
	$response["data"]["large_url"] 		= "ajax/lwhUpload_image.php?ts=".time()."&size=large&img_id=" . $row["id"];
	$response["data"]["medium_url"] 	= "ajax/lwhUpload_image.php?ts=".time()."&size=medium&img_id=" . $row["id"];
	$response["data"]["small_url"] 		= "ajax/lwhUpload_image.php?ts=".time()."&size=small&img_id=" . $row["id"];
	
	$response["data"]["created_time"] 	= cTYPE::inttodate($row["created_time"]);
	$response["data"]["last_updated"] 	= cTYPE::inttodate($row["last_updated"]);
	$response["data"]["last_login"] 	= cTYPE::inttodate($row["last_login"]);
	$response["data"]["hits"] 			= $row["hits"];

	$result_lang = $db->query("SELECT language_id FROM puti_members_lang WHERE member_id = '" . $_REQUEST["member_id"] . "'");	
	$lang_str = "";
    while($row_lang = $db->fetch($result_lang) ) {
		$lang_str .= ($lang_str!=""?",":"") . $row_lang["language_id"]; 
	}
	$response["data"]["languages"] = cTYPE::gstr($lang_str);

	
	$query_other = "SELECT * FROM puti_members_others WHERE member_id = '" . $_REQUEST["member_id"] . "'";
	$result_other = $db->query( $query_other );
	$row_other = $db->fetch($result_other);
	$response["data"]["emergency_name"]	 	= cTYPE::gstr($row_other["emergency_name"]);
	$response["data"]["emergency_phone"] 	= cTYPE::gstr($row_other["emergency_phone"]);
	$response["data"]["emergency_ship"] 	= cTYPE::gstr($row_other["emergency_ship"]);
	$response["data"]["therapy"] 			= $row_other["therapy"];
	$response["data"]["therapy_content"] 	= cTYPE::gstr($row_other["therapy_content"]);
	$response["data"]["medical_concern"] 	= cTYPE::gstr($row_other["medical_concern"]);
	$response["data"]["other_symptom"] 		= cTYPE::gstr($row_other["other_symptom"]);

	$response["data"]["transportation"] 	= $row_other["transportation"];
	$response["data"]["offer_carpool"] 		= $row_other["offer_carpool"];
	$response["data"]["plate_no"] 			= $row_other["plate_no"];
	
    $response["data"]["lang_main"] 			= $row_other["lang_main"];
	$response["data"]["lang_able"] 			= $row_other["lang_able"];
	
	$result_hear = $db->query("SELECT hearfrom_id FROM puti_members_hearfrom WHERE member_id = '" . $_REQUEST["member_id"] . "'");	
	$hear_str = "";
    while($row_hear = $db->fetch($result_hear) ) {
		$hear_str .= ($hear_str!=""?",":"") . $row_hear["hearfrom_id"]; 
	}
	$response["data"]["hear_about"] = cTYPE::gstr($hear_str);

	$result_symptom = $db->query("SELECT symptom_id FROM puti_members_symptom WHERE member_id = '" . $_REQUEST["member_id"] . "'");	
	$symptom_str = "";
    while($row_symptom = $db->fetch($result_symptom) ) {
		$symptom_str .= ($symptom_str!=""?",":"") . $row_symptom["symptom_id"]; 
	}
	$response["data"]["symptom"] = cTYPE::gstr($symptom_str);
	
	
	$response["data"]["records"] = array();
	$query_rec = "SELECT    d.id as branch_id, d.title as branch_title,
                            c.id as class_id, c.title as class_title,
                            COUNT(a.id) as enroll,
                            SUM(a.online) as online,
                            SUM(a.signin) as signin,
                            SUM(a.graduate) as graduate,
                            SUM(a.cert) as cert,
                            SUM(a.paid) as paid,
                            SUM(a.amt) as amt,
                            AVG(IF(a.attend > 0, a.attend, null)) as attend     
						FROM event_calendar_enroll a
						INNER JOIN event_calendar b ON (a.event_id = b.id) 
                        INNER JOIN puti_class c on (b.class_id = c.id)
                        INNER JOIN puti_branchs d on (c.branch = d.id)
				  WHERE member_id = '" . $_REQUEST["member_id"] . "' AND a.deleted <> 1 
                  GROUP BY d.id, c.id
				  ORDER BY d.sn, c.id ASC";
	$result_rec = $db->query($query_rec);
	$cnt_rec=0;
	while( $row_rec = $db->fetch($result_rec) ) {
		$recObj = array();
		$recObj["branch_title"] = cTYPE::gstr($row_rec["branch_title"]);
		$recObj["class_title"] 	= cTYPE::gstr($row_rec["class_title"]);
		$recObj["enroll"] 	    = $row_rec["enroll"]>0?$row_rec["enroll"]:"";
		$recObj["online"] 	    = $row_rec["online"]>0?$row_rec["online"]:"";
		$recObj["signin"] 	    = $row_rec["signin"]>0?$row_rec["signin"]:"";
		$recObj["graduate"] 	= $row_rec["graduate"]>0?$row_rec["graduate"]:"";
		$recObj["cert"] 	    = $row_rec["cert"]>0?$row_rec["cert"]:"";
		$recObj["paid"] 	    = $row_rec["paid"]>0?$row_rec["paid"]:"";
		$recObj["amt"] 	        = $row_rec["amt"]>0?"$".$row_rec["amt"]:"";
		$recObj["attend"] 		= $row_rec["attend"]>0?round($row_rec["attend"] * 100) . "%" :"";
		$response["data"]["records"][$cnt_rec] = $recObj;
		$cnt_rec++;
	}
	

	$response["data"]["cards"] = array();
	$query_idd = "SELECT member_id, idd, created_time 
						FROM puti_idd 
				  WHERE member_id = '" . $_REQUEST["member_id"] . "'
				  ORDER BY created_time DESC";
	$result_idd = $db->query($query_idd);
	$cnt_idd=0;
	while( $row_idd = $db->fetch($result_idd) ) {
		$recObj = array();
		$recObj["member_id"] 	= $row_idd["member_id"];
		$recObj["idd"] 			= $row_idd["idd"];
		$recObj["created_time"] = $row_idd["created_time"]>0?date("Y-m-d",$row_idd["created_time"]):'';
		$response["data"]["cards"][$cnt_idd] = $recObj;
		$cnt_idd++;
	}
	
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
