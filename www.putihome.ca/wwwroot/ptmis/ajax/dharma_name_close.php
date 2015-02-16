<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "UPDATE puti_members a  
					INNER JOIN (SELECT event_id, member_id FROM  event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "')  b ON (a.id = b.member_id)
					INNER JOIN (SELECT id, start_date, end_date FROM  event_calendar WHERE id = '" . $_REQUEST["event_id"] . "') c ON (b.event_id = c.id)
							SET a.dharma_name = a.temp_dharma_name, 
                                a.dharma_pinyin = a.temp_dharma_pinyin, 
                                a.dharma_date = c.end_date,
                                a.dharma_yy = DATE_FORMAT(FROM_UNIXTIME(IF(c.end_date>0,c.end_date, NULL) ), '%Y'),
                                a.dharma_mm = DATE_FORMAT(FROM_UNIXTIME(IF(c.end_date>0,c.end_date, NULL) ), '%m'),
                                a.dharma_dd = DATE_FORMAT(FROM_UNIXTIME(IF(c.end_date>0,c.end_date, NULL) ), '%d')
					WHERE   ( 
                                (( a.temp_dharma_name <> '' OR a.temp_dharma_name is null ) AND a.temp_dharma_name <> a.dharma_name ) 
                                OR
                                (a.temp_dharma_name = a.dharma_name AND a.dharma_pinyin <> a.temp_dharma_pinyin)
                            )
                           AND
                           a.deleted <> 1 AND a.deleted <> 1 AND  b.event_id = '" . $_REQUEST["event_id"] . "' AND c.id = '" . $_REQUEST["event_id"] . "'";
	
	$db->query($query);


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
