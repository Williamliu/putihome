<?php 
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=Event_Report.xls");
    
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = cTYPE::gstr($row_age["title"]);
	}
	$ages[0] = "";

	$evt = array();
	
	$query0 = "SELECT title, start_date, end_date, status FROM event_calendar
					WHERE id = '" . $_REQUEST["event_id"] . "'";
	$result0 = $db->query($query0);
	$row0 = $db->fetch($result0);
	$evt["title"] 		= cTYPE::gstr($row0["title"]);
	$evt["start_date"] 	= $row0["start_date"]>0?date("Y-m-d", $row0["start_date"]):'';
	$evt["end_date"] 	= $row0["end_date"]>0?date("Y-m-d", $row0["end_date"]):'';
	$sss = array();
	$sss[0] = "Inactive";
	$sss[1] = "Active";
	$sss[2] = "Open";
	$sss[9] = "Closed";
	$evt["status"] 		= $sss[$row0["status"]];

	$query2 	= "SELECT sum(a.online) as online, count(a.id) as enroll, sum(a.trial) as trial, sum(a.unauth) as unauth,  sum(a.signin) as signin, sum(a.graduate) as graduate, sum(a.cert) as cert   
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id)  
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
					a.event_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	
	$evt["online"] 		= $row2["online"]==""?0:$row2["online"];
	$evt["enroll"] 		= $row2["enroll"]==""?0:$row2["enroll"];
	$evt["trial"] 		= $row2["trial"]==""?0:$row2["trial"];
	$evt["unauth"] 		= $row2["unauth"]==""?0:$row2["unauth"];
	$evt["signin"] 		= $row2["signin"]==""?0:$row2["signin"];
	$evt["graduate"] 	= $row2["graduate"]==""?0:$row2["graduate"];
	$evt["cert"] 		= $row2["cert"]==""?0:$row2["cert"];
	

	// attend percent
	$query2 	= "SELECT round(sum(attend)/sum(if(attend>0,1,0)),2) as attend, count(a.id) as enroll,  sum(a.signin) as signin, sum(a.graduate) as graduate, sum(a.cert) as cert   
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id)  
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND 
					a.event_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	
	$evt["att_per"] = $row2["attend"]==""?"":round($row2["attend"]*100,0)."%";

	// Attend People
	$query5 = "SELECT count(distinct enroll_id) as attend  
					FROM event_calendar_date a 
					INNER JOIN event_calendar_attend b ON (a.class_date_id = b.class_date_id) 
					INNER JOIN event_calendar_enroll c ON (b.enroll_id = c.id) 
					WHERE a.event_id = '" . $_REQUEST["event_id"] . "' AND
						  c.event_id = '" . $_REQUEST["event_id"] . "'"; 
	$result5 	= $db->query($query5);
	$row5 		= $db->fetch($result5);
	$evt["attend"] 		= $row5["attend"]==""?0:$row5["attend"];
	
	// punch people
	$query2 	= "SELECT count(member_id) as punch, count(distinct member_id) as student 
					FROM puti_attend a 
					WHERE a.ref_id = '" . $_REQUEST["event_id"] . "'";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);

	$evt["punch"] 	= $row2["punch"]==""?0:$row2["punch"];
	$evt["student"] = $row2["student"]==""?0:$row2["student"];
	// end

	
	$evt["list"]		= array();
	
	$period = ($evt["start_date"]>0?$evt["start_date"]:"long long ago") . " ~ " . ($evt["end_date"]>0?$evt["end_date"]:"Today");

	$header_css = 'align="center" style="background-color:#eeeeee; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';

	$c1 = ' style="background-color:#FFF5D7;"';
	$c2 = ' style="background-color:#EBFAD3;"';
	
	$html = '<table border="1" cellpadding="1" cellspacing="0">';
	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="18" align="center"><span style="font-size:14px; font-weight:bold;">Event Report<br>' . $period . '</span></td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="5" align="left">Event Title</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Start Date</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>End Date</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Status</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Online</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Sign.</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Grad.</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Cert.</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Trial</td>';
	//$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Unauth</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Enroll</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Att.PP</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Pun.Tm</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Pun.PP</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Att.Rate</td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' width="50">SN</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Group</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="2">Name</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Age</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Member Date</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Phone</td>';
	$html .= '<td ' . $width_one . ' ' . $header_css . '>Email</td>';
	$html .= '</tr>';

	$html .= '<tr>';
	$html .= '<td colspan="5" align="left"' . $c1 . '><b>';
	$html .= $evt["title"];
	$html .= '</b></td>';

	$html .= '<td' . $c1 . '><b>';
	$html .=  $evt["start_date"]; 
	$html .= '</b></td>';
	
	$html .= '<td' . $c1 . '><b>';
	$html .=  $evt["end_date"];
	$html .= '</b></td>';
	
	$html .= '<td align="center"' . $c1 . '><b>';
	$html .=  $evt["status"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["online"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["signin"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["graduate"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["cert"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["trial"];
	$html .= '</b></td>';
    
    /*
	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["unauth"];
	$html .= '</b></td>';
    */

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["enroll"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["attend"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["punch"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["student"];
	$html .= '</b></td>';

	$html .= '<td align="right"' . $c1 . '><b>';
	$html .=  $evt["att_per"];
	$html .= '</b></td>';

	$html .= '</tr>';



	$query3 = "SELECT b.group_no, a.id as member_id, a.first_name, a.last_name, a.dharma_name, a.alias, a.age, a.birth_yy, a.member_yy, a.member_mm, a.member_dd, a.email, a.phone, a.city, b.group_no, b.online, b.trial, b.unauth, b.signin, b.graduate, b.cert, b.attend 
					FROM puti_members a INNER JOIN event_calendar_enroll b ON (a.id = b.member_id) 
				WHERE a.deleted <> 1 AND b.deleted <> 1 AND  b.event_id = '" . $_REQUEST["event_id"] . "'
				ORDER BY b.group_no, b.graduate desc,  a.first_name, a.last_name";
 	$result3 = $db->query($query3);
	$cnt0=0;
	while($row3 = $db->fetch($result3)) {
		$mArr = array();
		$mArr["group_no"] = $row3["group_no"]?$row3["group_no"]:"";

		$names						= array();
		$names["first_name"] 		= $row3["first_name"];
		$names["last_name"] 		= $row3["last_name"];
		$names["dharma_name"] 		= $row3["dharma_name"];
		$names["alias"] 			= $row3["alias"];
		$mArr["name"]				= cTYPE::cname($names, 10);

		//$birth_yy 			= $row3["birth_yy"]>0?$row3["birth_yy"]:"";
		//$mArr["age"] 		= $ages[$row3["age"]] . ($ages[$row3["age"]]!=""&&$birth_yy!=""?" : ":"") .$birth_yy;
		$birth_yy 			= $row3["birth_yy"]>0? date("Y") - intval($row3["birth_yy"]):"";
		$mArr["age"] 		= $birth_yy>0?$birth_yy:$ages[$row3["age"]];
		$mArr["member_date"] = cTYPE::toDate($row3["member_yy"],$row3["member_mm"],$row3["member_dd"]);
		
		
		$mArr["email"] 		= $row3["email"];
		$mArr["phone"]		= $row3["phone"];
		$mArr["city"]		= $row3["city"];
		$mArr["online"] 	= $row3["online"]?"Y":"";
		$mArr["trial"] 		= $row3["trial"]?"Y":"";
		$mArr["unauth"] 	= $row3["unauth"]?"Y":"";
		$mArr["signin"] 	= $row3["signin"]?"Y":"";
		$mArr["graduate"] 	= $row3["graduate"]?"Y":"";
		$mArr["cert"]	 	= $row3["cert"]?"Y":"";
		$mArr["attend"] 	= $row3["attend"]>0?($row3["attend"]*100)."%":"";

		$query6 	= "SELECT count(id) as punch FROM puti_attend WHERE purpose = 'event' AND ref_id = '" . $_REQUEST["event_id"] . "' AND member_id = '" . $row3["member_id"] . "'";
		$result6 	= $db->query($query6);
		$row6		= $db->fetch($result6);
		$mArr["punch"] = $row6["punch"]<=0?"":$row6["punch"];


		$html .= '<td width="50" align="center"' . $c2 . '>';
		$html .= $cnt0 + 1;
		$html .= '</td>';

		$html .= '<td align="center"' . $c2 . '><b>';
		$html .=  $mArr["group_no"];
		$html .= '</b></td>';
		
		$html .= '<td' . $c2 . ' colspan="2">';
		$html .=  $mArr["name"];
		$html .= '</td>';
		
		$html .= '<td' . $c2 . ' align="center">';
		$html .=  $mArr["age"];
		$html .= '</td>';

		$html .= '<td' . $c2 . ' align="center">';
		$html .=  $mArr["member_date"];
		$html .= '</td>';

		$html .= '<td' . $c2 . '>';
		$html .=  $mArr["phone"];
		$html .= '</td>';

		$html .= '<td' . $c2 . '>';
		$html .=  $mArr["email"];
		$html .= '</td>';

		$html .= '<td align="center"' . $c2 . '>';
		$html .=  $mArr["online"];
		$html .= '</td>';

		$html .= '<td align="center"' . $c2 . '>';
		$html .=  $mArr["signin"];
		$html .= '</td>';

		$html .= '<td align="center"' . $c2 . '>';
		$html .=  $mArr["graduate"];
		$html .= '</td>';

		$html .= '<td align="center"' . $c2 . '>';
		$html .=  $mArr["cert"];
		$html .= '</td>';

		$html .= '<td align="center"' . $c2 . '>';
		$html .=  $mArr["trial"];
		$html .= '</td>';

        /*
		$html .= '<td align="center"' . $c2 . '>';
		$html .=  $mArr["unauth"];
		$html .= '</td>';
        */

		$html .= '<td align="center"' . $c2 . '>';
		$html .= '</td>';
		$html .= '<td align="center"' . $c2 . '>';
		$html .= '</td>';
		$html .= '<td align="right"' . $c2 . '>';
		$html .=  $mArr["punch"];
		$html .= '</td>';
		$html .= '<td align="center"' . $c2 . '>';
		$html .= '</td>';

		$html .= '<td align="right"' . $c2 . '>';
		$html .=  $mArr["attend"];
		$html .= '</td>';

		$html .= '</tr>';


		$cnt0++;
	}
	$html .= '</table>';

	echo $html; 
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
