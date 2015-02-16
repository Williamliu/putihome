<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/email/email.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	$type["subject"] = '{"type":"ALL", "length":0, 	"id": "subject", "name":"Subject", "nullable":0}';
	$type["content"] = '{"type":"ALL", "length":0, 	"id": "content", "name":"Content", "nullable":0}';
	$type["identity"] = '{"type":"ALL", "length":0, "id": "identity", "name":"Identity", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$a["from"] 		= "service@van.putiyea.com";
	$a["reply"] 	= "service@van.putiyea.com";
	$e = new cEMAIL($a);

	$resulte	= $db->query("SELECT title, description, start_date, end_date FROM event_calendar WHERE deleted <> 1 AND id = '" .$_REQUEST["event_id"] . "'");
	$rowe 		= $db->fetch($resulte);
	$event_title = $rowe["title"] . " [" . ($rowe["start_date"]>0?date("Y-m-d",$rowe["start_date"]):"") . " ~ " . ($rowe["end_date"]>0?date("Y-m-d",$rowe["end_date"]):"").  "]";
	$event_desc  = $rowe["description"];
	$sdate 		 = $rowe["start_date"]>0?date("Y-m-d",$rowe["start_date"]):"";
	$edate 		 = $rowe["end_date"]>0?date("Y-m-d",$rowe["end_date"]):"";
	 
	$e->setSubject(str_replace(array("{event_title}"), array($event_title), $_REQUEST["subject"]));
	
	$query = "SELECT a.event_id, a.id as enroll_id, a.group_no, a.member_id, a.link, a.sess, 
					b.first_name, b.last_name, b.dharma_name, b.alias, b.gender, b.email, b.phone, b.cell, b.city 
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id) 
            	WHERE  a.deleted <> 1 AND 
					   b.deleted <> 1 AND  
					   a.group_no > 0 AND 
					   a.event_id = '" . $_REQUEST["event_id"] . "'  
				ORDER BY a.group_no, b.first_name, b.last_name, b.dharma_name DESC";
	$result = $db->query($query);
	$cnt = 0;
	$ecnt=0;
	while( $row = $db->fetch($result) ) {
		if( trim($row["email"]) != "") {
			  if( trim($row["link"]) =="" ) {
				  $sess = md5( $row["enroll_id"] . time() );
				  
				  $link = $CFG["http"] . $CFG["web_domain"] . "/confirm.php?sess=" . $sess; 
				  $tquery = "UPDATE event_calendar_enroll SET link = '" . $link . "', sess = '" . $sess . "' WHERE id = '" . $row["enroll_id"] . "'";
				  //echo "query:" . $tquery . "}";
				  $db->query($tquery);
			  } else {
				  $link = $row["link"];
			  }
			  $row["group_no"] = $row["group_no"]>0?$row["group_no"]: $words["to be confirmed"];
			  $email_link = $link . "&id=" . strtoupper($_REQUEST["identity"]); 
			  $email_body = str_replace(  array(	"{first_name}", 
			  										"{last_name}",
													"{dharma_name}",
													"{alias}",
													"{email}", 
													"{phone}", 
													"{cell}", 
													"{group}", 
													"{link}", 
													"{event_title}", 
													"{event_desc}",
													"{start_date}",
													"{end_date}"
													), 
										  array(	cTYPE::gstr($row["first_name"]), 
										  			cTYPE::gstr($row["last_name"]),
													cTYPE::gstr($row["dharma_name"]),
													cTYPE::gstr($row["alias"]),
													$row["email"],
													$row["phone"], 
													$row["cell"], 
													$row["group_no"], 
													$email_link,  
													$event_title, 
													$event_desc,
													$sdate,
													$edate
													), 
										  cTYPE::gstr($_REQUEST["content"]) );
			  
			  $e->setBody(stripslashes($email_body));
			  $e->setSend($row["email"]);
			  $e->send();
			  $ecnt++;
		}
	  	$cnt++;
	}

	$response["data"]["cnt"] 	= $cnt;
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "Total Students: $cnt<br><br>$ecnt Emails have been sent successful.";
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
