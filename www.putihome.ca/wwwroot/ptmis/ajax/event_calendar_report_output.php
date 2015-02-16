<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=event_attend_report.xls");
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "event_date >= '" . $sd . "' AND event_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "event_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "event_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;

	$query0 = "SELECT distinct a.id FROM event_calendar a INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
					WHERE a.deleted <> 1 AND 
						  b.deleted <> 1   
						  $ccc   
					ORDER BY event_date";

	$result0 = $db->query($query0);
	$cnt0=0;

	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$period = '[ From: ' . $_REQUEST["start_date"] . ($_REQUEST["end_date"]!=""?" To: ". $_REQUEST["end_date"]:"") . ']';
	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html .= '<tr>';
	$html .= '<td colspan="6" align="center" style="font-size:12px; font-weight:bold; height:30px;">Event Report' . $period . '</td>';
	$html .= '</tr>';

	$html.= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Event Title</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Status</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Male</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Female</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Total</td>';
	$html.= '</tr>';

	$sss = array();
	$sss[0] = "Inactive";
	$sss[1] = "Active";
	$sss[2] = "Open";
	$sss[9] = "Closed";
	
	while($row0 = $db->fetch($result0)) {
		$cnt0++;
		$evt_id 	= $row0["id"];
		$query1 	= "SELECT id, title, status FROM event_calendar WHERE deleted <> 1 AND id = '" . $evt_id . "'";
		$result1	= $db->query($query1);
		$row1 		= $db->fetch($result1);

		$query2 	= "SELECT count(a.id) as total,  sum(if(b.gender='Male',1,0)) as male, sum(if(b.gender='Female',1,0)) as female  
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
						WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
						a.event_id = '" . $evt_id . "'";
		
		$result2	= $db->query($query2);
		$row2 		= $db->fetch($result2);

		$html.= '<tr height="25">';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt0 . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($row1["title"]) . '</b></td>';
		$html.= '<td ' . $width_two . '>' . $sss[$row1["status"]] . '</b></td>';
		$html.= '<td ' . $width_two . ' align="right">' . $row2["male"] . '</td>';
		$html.= '<td ' . $width_two . ' align="right">' . $row2["female"] . '</td>';
		$html.= '<td ' . $width_two . ' align="right">' . $row2["total"] . '</td>';
		$html.= '</tr>';
	}
	$html .= '</table>';
	echo $html;
} catch(cERR $e) {
	echo "<pre>";
	print_r($e->detail());
	echo "</pre>";	
} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo "<pre>";
	print_r($response);
	echo "</pre>";	
}
?>
