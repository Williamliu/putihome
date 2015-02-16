<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$order_str = "ORDER BY c.id ASC";
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}

	$site_prefix = $db->getVal("puti_sites", "cert_prefix",$admin_user["site"]);
	$query_class = "SELECT a.start_date, a.end_date, a.title as event_title, b.title as class_title, b.cert_prefix 
							FROM 	event_calendar a 
							INNER JOIN puti_class b ON (a.class_id = b.id)
							WHERE a.id='" . $_REQUEST["event_id"] . "'";  
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$class_prefix 	= $row_class["cert_prefix"];
	$date_prefix 	= $row_class["end_date"]>=0?date("ymd",$row_class["end_date"]):date("ymd",$row_class["start_date"]);
	$cert_prefix 	= $site_prefix . $class_prefix . "-" . $date_prefix; 
	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	$query_base = "SELECT c.id, c.group_no, c.cert_no  
						FROM puti_members a
						LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
						INNER JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "' AND deleted <> 1 AND cert = 1) c ON ( a.id = c.member_id ) 
						WHERE  a.deleted <> 1  
						$criteria 
						$order_str";
	
	
	$query 	= $query_base;
	$result = $db->query( $query );
	$cnt = 0;
	$gpno = array();
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] = $row["id"];
		$gpno[$row["group_no"]]++;
		if( $row["cert_no"] == "" ) {
			$query_max = "SELECT MAX( CONVERT( SUBSTRING_INDEX(cert_no, '-', -1) , UNSIGNED INTEGER)) as max_no   
								FROM puti_members a
								LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
								INNER JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $_REQUEST["event_id"] . "' AND group_no='" . $row["group_no"] . "' AND deleted <> 1 AND cert = 1) c ON ( a.id = c.member_id ) 
								WHERE  a.deleted <> 1  
								$criteria 
								$order_str";
			$result_max = $db->query($query_max);
			$row_max = $db->fetch($result_max);
			if( $row_max["max_no"] != "" ) {
				$cert_no = $cert_prefix . $row["group_no"] . '-' . ($row_max["max_no"] + 1 );
			} else {
				$cert_no = $cert_prefix . $row["group_no"] . '-' . ($gpno[$row["group_no"]]); 
			}

			$fields = array();
			$fields["cert_no"] = $cert_no;
			$db->update("event_calendar_enroll",$row["id"], $fields);
		}
		$cnt++;	
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

function strrstr($h, $n, $before = false) {
    $rpos = strrpos($h, $n);
    if($rpos === false) return false;
    if($before == false) return substr($h, $rpos + 1);
    else return substr($h, 1, $rpos);
}
?>
