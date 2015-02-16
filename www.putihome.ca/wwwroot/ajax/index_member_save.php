 <?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	$type["event_id"]  	= '{"type":"NUMBER", 	"length":11, 	"id": "event_id", 		"name":"Select Event", 		"nullable":0}';
	$type["members"]  	= '{"type":"CHAR", 		"length":1023, 		"id": "sel_members", 	"name":"At lease select one member", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$list = array();
	$cnt0=0;
	$members = explode(",", $_REQUEST["members"]);
	foreach($members as $member) {
		$member_id 	= $member;
		$event_id 	= $_REQUEST["event_id"];
		$query1 = "SELECT id, group_no FROM event_calendar_enroll WHERE  event_id = '" . $event_id . "' AND member_id = '" . $member_id . "'";
		$result1 = $db->query( $query1 );
		if( $db->row_nums($result1) > 0 )  {
			$query = "UPDATE event_calendar_enroll SET group_no = 0, deleted = 0, online = 1 WHERE deleted = 1 AND event_id = '" . $event_id . "' AND member_id = '" . $member_id . "'";
			$db->query( $query );
			$row1 = $db->fetch($result1);
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
		}

		$result0 = $db->query("SELECT first_name, last_name, dharma_name, phone, cell FROM puti_members WHERE id = '" . $member_id . "'");
		$row0	 = $db->fetch($result0);
		
		$list[$cnt0]["first_name"] 	= $row0["first_name"];
		$list[$cnt0]["last_name"] 	= $row0["last_name"];
		$list[$cnt0]["dharma_name"] = $row0["dharma_name"];
		$list[$cnt0]["phone"] 		= $row0["phone"];
		$list[$cnt0]["cell"] 		= $row0["cell"];
		$cnt0++;		
	}
	$response["data"]["list"]	= $list;
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
