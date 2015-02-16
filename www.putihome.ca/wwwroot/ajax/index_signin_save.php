 <?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	$type["event_id"]  	= '{"type":"NUMBER", 	"length":11, 	"id": "event_id", 		"name":"Select Event", 		"nullable":0}';
	$type["member"]  	= '{"type":"CHAR", 		"length":1023, 	"id": "sigin_member", 	"name":"Email|Phone|Cell", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query0 = "SELECT id, first_name, last_name, dharma_name, email, phone, cell FROM puti_members WHERE deleted <> 1 AND status = 1 AND ( 
			  	replace(replace(replace(phone,' ',''),'-',''),'.','') 	= '" . str_replace( array(" ", "-", "."),array("","",""), trim($_REQUEST["member"]) ) . "' OR 
			  	replace(replace(replace(cell,' ',''),'-',''),'.','')	= '" . str_replace( array(" ", "-", "."),array("","",""), trim($_REQUEST["member"]) ) . "' OR 
				email = '" . trim($_REQUEST["member"]) . "' OR
				dharma_name = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "' OR
				concat(first_name , ' ' , last_name) = '" . cTYPE::utrans(trim($_REQUEST["member"])) . "')";

	$result0 = $db->query($query0);
	if( $db->row_nums($result0) > 1 ) {
		$members = array();
		$cnt0=0;
		while($row0 = $db->fetch($result0)) {
			$members[$cnt0]["id"] 			= $row0["id"];
			$members[$cnt0]["first_name"] 	= $row0["first_name"];
			$members[$cnt0]["last_name"] 	= $row0["last_name"];
			$members[$cnt0]["dharma_name"] 	= $row0["dharma_name"];
			//$members[$cnt0]["email"] 		= $row0["email"];
			//$members[$cnt0]["phone"] 		= $row0["phone"];
			//$members[$cnt0]["cell"] 		= $row0["cell"];
			$cnt0++;
		}
		$response["data"]["members"] = $members;
		$response["errorCode"] = 9;
			
	} else if( $db->row_nums($result0) > 0 ) {
		
		$row0 = $db->fetch($result0);

		$member_id 	= $row0["id"];
		$event_id 	= $_REQUEST["event_id"];
		$query1 = "SELECT id, group_no FROM event_calendar_enroll WHERE  event_id = '" . $event_id . "' AND member_id = '" . $member_id . "'";
		$result1 = $db->query( $query1 );
		if( $db->row_nums($result1) > 0 )  {
			$query = "UPDATE event_calendar_enroll SET group_no = 0, deleted = 0, online = 1 WHERE deleted = 1 AND event_id = '" . $event_id . "' AND member_id = '" . $member_id . "'";
			$db->query( $query );
			$row1 = $db->fetch($result1);

			$response["errorMessage"]	= "You have already signed in successful.<br><br>
										   Name: " . $row0["first_name"] . " " . $row0["last_name"] .  "<br>
										   Group: <b>" . ($row1["group_no"]>0?$row1["group_no"]:"to be confirmed") . "</b><br>";
		} else {
			$fields = array();
			$fields["event_id"] 		= $event_id;
			$fields["member_id"] 		= $member_id;
			$fields["group_no"] 		= 0;
			$fields["status"] 			= 1;
			$fields["online"] 			= 1;
			$fields["deleted"] 			= 0;
			$fields["created_time"] 	= time();
			$enroll_id = $db->insert("event_calendar_enroll", $fields);

			$response["errorMessage"]	= "You have signed in successful.<br><br>
										   Name: " . $row0["first_name"] . " " . $row0["last_name"] .  "<br>
										   Group: <b>to be confirm</b><br>";
		}
	    $response["data"]["event_id"]	= $event_id;
	    $response["data"]["member_id"]	= $member_id;
	    $response["data"]["member"]		= trim($_REQUEST["member"]);

		$response["data"]["list"][0]["first_name"] 	= $row0["first_name"];
		$response["data"]["list"][0]["last_name"] 	= $row0["last_name"];
		$response["data"]["list"][0]["dharma_name"] = $row0["dharma_name"];
		//$response["data"]["list"][0]["phone"] 		= $row0["phone"];
		//$response["data"]["list"][0]["cell"] 		= $row0["cell"];
		
		$response["errorCode"] 			= 0;

	} else {
		$response["errorMessage"]		= "The value '" . trim($_REQUEST["member"]) . "' is not associated with any account in our database.<br><br>
									  		If you don't have account, please go to 'Registration' to registrate first.";
		$response["errorCode"] 			= 1;
	    $response["data"]["event_id"]	= $event_id;
	    $response["data"]["member_id"]	= $member_id;
	    $response["data"]["member"]		= trim($_REQUEST["member"]);
	}
	
	
	echo json_encode($response);

} catch(cERR $e) {
	echo json_encode($e->detail());
	
} catch(Exception $e ) {
    $response["data"]["member"]	= trim($_REQUEST["member"]);
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo json_encode($response);
}



?>
