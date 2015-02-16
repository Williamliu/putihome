<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["from_id"] 			= '{"type":"NUMBER", 	"length":11, 	"id": "from_id", 	"name":"Member ID", 					"nullable":0}';
	$type["to_id"] 			    = '{"type":"NUMBER", 	"length":11, 	"id": "to_id", 		"name":"Please provide merge ID", 	"nullable":0}';

	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$_REQUEST["from_id"]= trim($_REQUEST["from_id"]);
	$_REQUEST["to_id"] 	= trim($_REQUEST["to_id"]);
	
	if($_REQUEST["to_id"] == $_REQUEST["from_id"]) {
		$response["errorCode"] 		= 1;
		$response["errorMessage"]	= "<br>The member ID: " . $_REQUEST["from_id"] . " can not be merge to the same ID: " . $_REQUEST["to_id"] . ".";
		echo json_encode($response);
		exit();			
	}
	
	if( $_REQUEST["to_id"] <= 0 ) {
		
		if( $db->exists("SELECT * FROM event_calendar_enroll WHERE deleted <> 1 AND member_id = '" . $_REQUEST["from_id"] . "'") ||
			$db->exists("SELECT * FROM pt_volunteer WHERE deleted <> 1 AND member_id = '" . $_REQUEST["from_id"] . "'")
		 ) {
			$response["errorCode"] 		= 1;
			$response["errorMessage"]	= "<br>The member ID: " . $_REQUEST["from_id"] . " has historical record  can't be deleted,<br><br>please merge this member to other member.";
			echo json_encode($response);
			exit();			
		} else {
			$query = "UPDATE puti_members SET deleted = 1, last_updated = '" . time() . "' WHERE deleted <> 1 AND id = '" . $_REQUEST["from_id"] . "'";
			$db->query( $query );

			$response["data"]["from_id"]= $_REQUEST["from_id"];
			$response["data"]["to_id"] 	= $_REQUEST["to_id"];

			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>The member has been deleted successful.";
			echo json_encode($response);
			exit();
		}
		
	} else {
		
		if( $db->exists("SELECT * FROM puti_members WHERE deleted <> 1 AND id = '" . $_REQUEST["to_id"] . "'") ) {
			$resultf 	= $db->query("SELECT * FROM puti_members WHERE deleted <> 1 AND id = '" . $_REQUEST["from_id"] . "'");
			$rowf 		= $db->fetch($resultf);

			$resultt 	= $db->query("SELECT * FROM puti_members WHERE deleted <> 1 AND id = '" . $_REQUEST["to_id"] . "'");
			$rowt 		= $db->fetch($resultt);
			
			$fields 				= array();
			$fields["first_name"] 	= mval($rowf["first_name"], $rowt["first_name"]); 
			$fields["last_name"] 	= mval($rowf["last_name"], $rowt["last_name"]); 
			$fields["legal_first"] 	= mval($rowf["legal_first"], $rowt["legal_first"]); 
			$fields["legal_last"] 	= mval($rowf["legal_last"], $rowt["legal_last"]); 
			$fields["dharma_name"] 	= mval($rowf["dharma_name"], $rowt["dharma_name"]); 
			$fields["alias"] 		= mval($rowf["alias"], $rowt["alias"]); 
			$fields["gender"] 		= mval($rowf["gender"], $rowt["gender"]); 
			
			$fields["birth_yy"] 	= tval($rowf["birth_yy"], $rowt["birth_yy"], 0); 
			$fields["birth_mm"] 	= tval($rowf["birth_mm"], $rowt["birth_mm"], 0); 
			$fields["birth_dd"] 	= tval($rowf["birth_dd"], $rowt["birth_dd"], 0); 
			$fields["age"] 			= tval($rowf["age"], $rowt["age"], 0); 

			$fields["level"] 		= tval($rowf["level"], $rowt["level"], 0); 
			$fields["online"] 		= tval($rowf["online"], $rowt["online"], 0); 
			$fields["site"] 		= tval($rowf["site"], $rowt["site"], 0); 
			$fields["status"] 		= tval($rowf["status"], $rowt["status"], 0); 
			
			$fields["email"] 		= mval($rowf["email"], $rowt["email"]); 
			$fields["password"] 	= mval($rowf["password"], $rowt["password"]); 
			$fields["email_flag"] 	= tval($rowf["email_flag"], $rowt["email_flag"], 0); 

			$fields["language"] 	= tval($rowf["language"], $rowt["language"], 0); 
			
			$fields["phone"] 		= mval($rowf["phone"], $rowt["phone"]); 
			$fields["cell"] 		= mval($rowf["cell"], $rowt["cell"]); 
			$fields["contact_method"] = mval($rowf["contact_method"], $rowt["contact_method"]); 
			$fields["address"] 		= mval($rowf["address"], $rowt["address"]); 
			$fields["city"] 		= mval($rowf["city"], $rowt["city"]); 
			$fields["state"] 		= mval($rowf["state"], $rowt["state"]); 
			$fields["country"] 		= mval($rowf["country"], $rowt["country"]); 
			$fields["identify_no"] 	= mval($rowf["identify_no"], $rowt["identify_no"]); 
			$fields["postal"] 		= mval($rowf["postal"], $rowt["postal"]); 
			$fields["last_updated"] = time();
			$db->update("puti_members", $_REQUEST["to_id"], $fields);
			

			$resultf 	= $db->query("SELECT * FROM puti_members_others WHERE member_id = '" . $_REQUEST["from_id"] . "'");
			$rowf 		= $db->fetch($resultf);

			$resultt 	= $db->query("SELECT * FROM puti_members_others WHERE member_id = '" . $_REQUEST["to_id"] . "'");
			$rowt 		= $db->fetch($resultt);

			$fields 					= array();
			$fields["emergency_name"] 	= mval($rowf["emergency_name"], $rowt["emergency_name"]); 
			$fields["emergency_phone"] 	= mval($rowf["emergency_phone"], $rowt["emergency_phone"]); 
			$fields["emergency_ship"] 	= mval($rowf["emergency_ship"], $rowt["emergency_ship"]); 
			$fields["therapy"] 			= mval($rowf["therapy"], $rowt["therapy"],0); 
			$fields["therapy_content"] 	= mval($rowf["therapy_content"], $rowt["therapy_content"]); 
			$fields["medical_concern"] 	= mval($rowf["medical_concern"], $rowt["medical_concern"]); 
			$fields["other_symptom"] 	= mval($rowf["other_symptom"], $rowt["other_symptom"]); 
			$fields["transportation"] 	= mval($rowf["transportation"], $rowt["transportation"], 0); 
			$fields["plate_no"] 		= mval($rowf["plate_no"], $rowt["plate_no"]); 
			$fields["offer_carpool"] 	= mval($rowf["offer_carpool"], $rowt["offer_carpool"], 0); 
			
			if( $db->exists("SELECT * FROM puti_members_others WHERE member_id = '" . $_REQUEST["to_id"] . "'") ) {
				$db->update("puti_members_others", array("member_id"=>$_REQUEST["to_id"]),$fields);
			} else {
				$fields["member_id"] = $_REQUEST["to_id"];
				$db->insert("puti_members_others",$fields);
			}

			$resultf 	= $db->query("SELECT * FROM event_calendar_enroll WHERE deleted <> 1 AND member_id = '" . $_REQUEST["from_id"] . "'");
			while($rowf = $db->fetch($resultf)) {
				if( $db->exists("SELECT event_id, member_id FROM event_calendar_enroll WHERE event_id = '" . $rowf["event_id"] . "' AND member_id = '" . $_REQUEST["to_id"] . "'") ) {
					
					$resultt = $db->query("SELECT * FROM event_calendar_enroll WHERE event_id = '" . $rowf["event_id"] . "' AND member_id = '" . $_REQUEST["to_id"] . "'");
					$rowt	= $db->fetch($resultt);
				
					$ccc = array();
					$ccc["event_id"] 	= $rowf["event_id"];
					$ccc["member_id"] 	= $_REQUEST["to_id"];
					
					$fields = array();
					$fields["group_no"] 	= tval($rowf["group_no"], $rowt["group_no"], 0);
					$fields["leader"] 		= maxval($rowf["leader"], $rowt["leader"], 0);
					$fields["volunteer"] 	= maxval($rowf["volunteer"], $rowt["volunteer"], 0);
					$fields["status"] 		= maxval($rowf["status"], $rowt["status"], 0);
					$fields["online"] 		= maxval($rowf["online"], $rowt["online"], 0);
					$fields["signin"] 		= maxval($rowf["signin"], $rowt["signin"], 0);
					$fields["graduate"] 	= maxval($rowf["graduate"], $rowt["graduate"], 0);
					$fields["cert"] 		= maxval($rowf["cert"], $rowt["cert"], 0);
					$fields["attend"] 		= maxval($rowf["attend"], $rowt["attend"], 0);
					$fields["paid"] 		= maxval($rowf["paid"], $rowt["paid"], 0);
					$fields["amt"] 			= maxval($rowf["amt"], $rowt["amt"], 0);
					$fields["invoice"] 		= maxval($rowf["invoice"], $rowt["invoice"],'');
					$fields["paid_date"] 	= maxval($rowf["paid_date"], $rowf["paid_date"], 0);
					$fields["shelf"] 		= maxval($rowf["shelf"], $rowt["shelf"], 0);
					$fields["trial"] 		= tval($rowf["trial"], $rowt["trial"], 0);
					$fields["trial_date"] 	= tval($rowf["trial_date"], $rowt["trial_date"], 0);
					$fields["onsite"] 		= maxval($rowf["onsite"], $rowt["onsite"], 0);
					$fields["cert_no"] 		= mval($rowf["cert_no"], $rowt["cert_no"], '');
					$fields["link"] 		= mval($rowf["link"], $rowt["link"], '');
					$fields["sess"] 		= mval($rowf["sess"], $rowt["sess"], '');
					$fields["confirm"] 		= maxval($rowf["confirm"], $rowt["confirm"], '');
					$fields["deleted"] 		= min($rowf["deleted"], $rowt["deleted"]);
					$db->update("event_calendar_enroll", $ccc, $fields);
					
					//$db->query("UPDATE event_calendar_enroll SET deleted = 0 WHERE event_id = '" . $rowf["event_id"] . "' AND member_id = '" . $_REQUEST["to_id"] . "'");
					continue;
				} else {
					$fields = array();
					$fields["event_id"] 	= $rowf["event_id"];
					$fields["member_id"] 	= $_REQUEST["to_id"];
					$fields["group_no"] 	= $rowf["group_no"]?$rowf["group_no"]:0;
					$fields["leader"] 		= $rowf["leader"]?$rowf["leader"]:0;
					$fields["volunteer"] 	= $rowf["volunteer"]?$rowf["volunteer"]:0;
					$fields["status"] 		= $rowf["status"]?$rowf["status"]:0;
					$fields["online"] 		= $rowf["online"]?$rowf["online"]:0;
					$fields["signin"] 		= $rowf["signin"]?$rowf["signin"]:0;
					$fields["graduate"] 	= $rowf["graduate"]?$rowf["graduate"]:0;
					$fields["cert"] 		= $rowf["cert"]?$rowf["cert"]:0;
					$fields["attend"] 		= $rowf["attend"]?$rowf["attend"]:0;
					$fields["paid"] 		= $rowf["paid"]?$rowf["paid"]:0;
					$fields["amt"] 			= $rowf["amt"]?$rowf["amt"]:0;
					$fields["invoice"] 		= $rowf["invoice"]?$rowf["invoice"]:'';
					$fields["paid_date"] 	= $rowf["paid_date"]?$rowf["paid_date"]:0;
					$fields["shelf"] 		= $rowf["shelf"]?$rowf["shelf"]:0;
					$fields["trial"] 		= $rowf["trial"]?$rowf["trial"]:0;
					$fields["trial_date"] 	= $rowf["trial_date"]?$rowf["trial_date"]:0;
					$fields["onsite"] 		= $rowf["onsite"]?$rowf["onsite"]:0;
					$fields["cert_no"] 		= $rowf["cert_no"]?$rowf["cert_no"]:'';
					$fields["link"] 		= $rowf["link"]?$rowf["link"]:'';
					$fields["sess"] 		= $rowf["sess"]?$rowf["sess"]:'';
					$fields["confirm"] 		= $rowf["confirm"]?$rowf["confirm"]:'';
					$fields["deleted"] 		= $rowf["deleted"]?$rowf["deleted"]:0;
					$db->insert("event_calendar_enroll", $fields);
				}
			}


			$resultf 	= $db->query("SELECT * FROM puti_members_lang WHERE  member_id = '" . $_REQUEST["from_id"] . "'");
			while($rowf = $db->fetch($resultf)) {
				if( $db->exists("SELECT language_id, member_id FROM puti_members_lang WHERE  language_id = '" . $rowf["language_id"] . "' AND member_id = '" . $_REQUEST["to_id"] . "'") ) {
					continue;
				} else {
					$fields = array();
					$fields["language_id"] 	= $rowf["language_id"];
					$fields["member_id"] 	= $_REQUEST["to_id"];
					$db->insert("puti_members_lang", $fields);
				}
			}


			$resultf 	= $db->query("SELECT * FROM puti_members_hearfrom WHERE  member_id = '" . $_REQUEST["from_id"] . "'");
			while($rowf = $db->fetch($resultf)) {
				if( $db->exists("SELECT hearfrom_id, member_id FROM puti_members_hearfrom WHERE  hearfrom_id = '" . $rowf["hearfrom_id"] . "' AND member_id = '" . $_REQUEST["to_id"] . "'") ) {
					continue;
				} else {
					$fields = array();
					$fields["hearfrom_id"] 	= $rowf["hearfrom_id"];
					$fields["member_id"] 	= $_REQUEST["to_id"];
					$db->insert("puti_members_hearfrom", $fields);
				}
			}

			$resultf 	= $db->query("SELECT * FROM puti_members_symptom WHERE  member_id = '" . $_REQUEST["from_id"] . "'");
			while($rowf = $db->fetch($resultf)) {
				if( $db->exists("SELECT symptom_id, member_id FROM puti_members_symptom WHERE symptom_id = '" . $rowf["symptom_id"] . "' AND member_id = '" . $_REQUEST["to_id"] . "'") ) {
					continue;
				} else {
					$fields = array();
					$fields["symptom_id"] 	= $rowf["symptom_id"];
					$fields["member_id"] 	= $_REQUEST["to_id"];
					$db->insert("puti_members_symptom", $fields);
				}
			}

			$from_file 	= $CFG["upload_path"] . "/original/" . $_REQUEST["from_id"]  . ".jpg";				
			$to_file	= $CFG["upload_path"] . "/original/" . $_REQUEST["to_id"]  . ".jpg";
			if(!file_exists($to_file)) if(file_exists($from_file)) copy($from_file, $to_file); 

			$from_file 	= $CFG["upload_path"] . "/large/" . $_REQUEST["from_id"]  . ".jpg";				
			$to_file	= $CFG["upload_path"] . "/large/" . $_REQUEST["to_id"]  . ".jpg";
			if(!file_exists($to_file)) if(file_exists($from_file)) copy($from_file, $to_file); 

			$from_file 	= $CFG["upload_path"] . "/medium/" . $_REQUEST["from_id"]  . ".jpg";				
			$to_file	= $CFG["upload_path"] . "/medium/" . $_REQUEST["to_id"]  . ".jpg";
			if(!file_exists($to_file)) if(file_exists($from_file)) copy($from_file, $to_file); 

			$from_file 	= $CFG["upload_path"] . "/small/" . $_REQUEST["from_id"]  . ".jpg";				
			$to_file	= $CFG["upload_path"] . "/small/" . $_REQUEST["to_id"]  . ".jpg";
			if(!file_exists($to_file)) if(file_exists($from_file)) copy($from_file, $to_file); 

			$from_file 	= $CFG["upload_path"] . "/tiny/" . $_REQUEST["from_id"]  . ".jpg";				
			$to_file	= $CFG["upload_path"] . "/tiny/" . $_REQUEST["to_id"]  . ".jpg";
			if(!file_exists($to_file)) if(file_exists($from_file)) copy($from_file, $to_file); 

			$db->query("UPDATE puti_idd SET member_id = '" . $_REQUEST["to_id"] . "', created_time = '" . time() . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");
			
			$query = "UPDATE puti_members SET deleted = 1, last_updated = '" . time() . "' WHERE deleted <> 1 AND id = '" . $_REQUEST["from_id"] . "'";
			$db->query( $query );

			if( !$db->exists("SELECT * FROM pt_volunteer WHERE deleted <> 1 AND member_id = '" . $_REQUEST["to_id"] . "'") ) {
				$db->query("UPDATE pt_volunteer SET member_id = '" . $_REQUEST["to_id"] . "', last_updated = '" . time() . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
				$db->query("UPDATE pt_volunteer_depart_current SET member_id = '" . $_REQUEST["to_id"] . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
				$db->query("UPDATE pt_volunteer_depart_will SET member_id = '" . $_REQUEST["to_id"] . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
				$db->query("UPDATE pt_volunteer_health SET member_id = '" . $_REQUEST["to_id"] . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
				$db->query("UPDATE pt_volunteer_others SET member_id = '" . $_REQUEST["to_id"] . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
				$db->query("UPDATE pt_volunteer_professional SET member_id = '" . $_REQUEST["to_id"] . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
				$db->query("UPDATE pt_volunteer_schedule SET member_id = '" . $_REQUEST["to_id"] . "' WHERE member_id = '" . $_REQUEST["from_id"] . "'");			
			} 
			
			$response["data"]["from_id"]= $_REQUEST["from_id"];
			$response["data"]["to_id"] 	= $_REQUEST["to_id"];
			
			$response["errorCode"] 		= 0;
			$response["errorMessage"]	= "<br>The member has been merged successful.";
			echo json_encode($response);
			exit();			
			
		} else {
			$response["errorCode"] 		= 1;
			$response["errorMessage"]	= "<br>The destinated member ID: " . $_REQUEST["to_id"] . " doesn't exist in our database.";
			echo json_encode($response);
			exit();			
		}
	
	}

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

function tval($fval, $tval, $dval) {
	if($tval==0) $tval = $fval;
	if($tval==0) $tval = $dval;
	return $tval;
}

function maxval($fval, $tval, $dval) {
	$ret_val = max($fval, $tval);
	if($ret_val <=0) $ret_val = $dval; 
	return $ret_val;
}
?>
