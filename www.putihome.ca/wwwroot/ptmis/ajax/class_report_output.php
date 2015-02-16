<?php 
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=class_report.xls");

	$orderBY	= $_REQUEST["orderBY"]==""?"last_name":$_REQUEST["orderBY"];
	$orderSQ	= $_REQUEST["orderSQ"]==""?"ASC":$_REQUEST["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";

	$con = $_REQUEST; 
	
	// condition here 
	$sd = cTYPE::datetoint($con["sch_sdate"]);
	$ed = cTYPE::datetoint($con["sch_edate"]);
	if( $sd != "" && $ed != "" ) {
		$ccc = "start_date >= '" . $sd . "' AND end_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "start_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ccc = "end_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;
	
	
	
	
	$criteria = "";
	$sch_111 = trim($con["sch_class"]);
	if($sch_111 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "class_id = '" . $sch_111 . "'";
	} else {
		$criteria .= ($criteria==""?"":" AND ") . "class_id = '-1'";
	}

	$sch_222 = trim($con["sch_sign"]);
	if($sch_222 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "signin = '" . $sch_222 . "'";
	}

	$sch_333 = trim($con["sch_grad"]);
	if($sch_333 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "graduate = '" . $sch_333 . "'";
	}

	$sch_444 = trim($con["sch_cert"]);
	if($sch_444 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "cert = '" . $sch_444 . "'";
	}

	$sch_rate = trim($con["sch_rate"]);
	if($sch_rate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "attend >= '" . ($sch_rate/100) . "'";
	}

	$sch_555 = trim($con["sch_name"]);
	if($sch_555 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(first_name like '%" . $sch_555 . "%' OR last_name like '%" . $sch_555 . "%' OR dharma_name like '%" . $sch_555 . "%' OR alias like '%" . $sch_555 . "%')";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}

	$class_title = $db->getVal("puti_class", "title", $con["sch_class"]);

	$query_base = "SELECT a.id as event_id, a.title, a.start_date, a.end_date,
						  b.id as enroll_id, b.signin, b.graduate, b.cert, b.attend, b.trial, b.unauth, b.new_flag, 
						  c.first_name, c.last_name, c.dharma_name, c.alias, c.gender, c.email, c.phone, c.cell, c.city ,
						  c.birth_yy, c.age, c.member_yy, c.member_mm, c.member_dd, c.memo, c.member_title 
						FROM event_calendar a 
						INNER JOIN event_calendar_enroll b ON (a.id = b.event_id)  
						INNER JOIN 
						( SELECT aa0.*, bb0.title as member_title FROM puti_members aa0 LEFT JOIN puti_info_title bb0 ON ( aa0.level = bb0.id )  ) c ON (b.member_id = c.id) 
						WHERE  	a.deleted <> 1 AND b.deleted <> 1 AND  
								a.site IN " . $admin_user["sites"] . " AND a.branch IN " . $admin_user["branchs"] . "						  
						$ccc 
						$criteria 
						$order_str";

	$query 	= $query_base; 
	$result = $db->query( $query );
	$rows = array();

	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html.= '<tr>';
	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';

	$period = ($_REQUEST["sch_sdate"]>0?$_REQUEST["sch_sdate"]:"long long ago") . " ~ " . ($_REQUEST["sch_edate"]>0?$_REQUEST["sch_edate"]:"Today");

	$html .= '<tr>';
	$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="24" align="center"><span style="font-size:12px; font-weight:bold;">Class History Detail Report<br>' . $class_title . ' [' . $period . ']</span></td>';
	$html .= '</tr>';

	
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . $words["sn"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["start date"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["end date"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["name"] . '</td>';
	//$html.= '<td ' . $width_two . ' ' . $header_css . '>L.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["dharma"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["alias"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["title"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["age"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["new people"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["member date"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["gender"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["email"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["phone"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["cell"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["city"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["notes"] . '</td>';
	//$html.= '<td ' . $width_two . ' ' . $header_css . '>Unauth?</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["trial"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["sign"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["grad."] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["cert."] . '</td>';

	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["total checkin"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["total attend"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["total leave"] . '</td>';

	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["attd."] . '</td>';
	$html.= '</tr>';
	

	
	
	$cnt = 0;
	while( $row = $db->fetch($result)) {
        $result_ck = $db->query("SELECT SUM(checkin) as total_checkin FROM event_calendar_date WHERE event_id = '" . $row["event_id"] . "'");
        $row_ck = $db->fetch($result_ck);
        $total_checkin = $row_ck["total_checkin"];

		$rows[$cnt]["enroll_id"] 	= $row["enroll_id"];
		$rows[$cnt]["title"] 		= cTYPE::gstr($row["title"]);
		$rows[$cnt]["start_date"] 	= $row["start_date"]>0?date("Y-m-d",$row["start_date"]):'';
		$rows[$cnt]["end_date"] 	= $row["end_date"]>0?date("Y-m-d",$row["end_date"]):'';
		$rows[$cnt]["first_name"] 	= cTYPE::gstr($row["first_name"]);

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$rows[$cnt]["name"]			= cTYPE::gstr(cTYPE::lfname($names));

		$age_range 					= $row["age"]>=1?$ages[$row["age"]]:"";
		$birth_yy 					= $row["birth_yy"]>0? date("Y") - intval($row["birth_yy"]):"";
		$rows[$cnt]["age"] 			= $birth_yy>0?$birth_yy:$age_range;

		$rows[$cnt]["new_flag"] 	= $row["new_flag"]?"Y":"";
		$rows[$cnt]["member_date"] 	= cTYPE::toDate($row["member_yy"],$row["member_mm"],$row["member_dd"]);
	
		//$rows[$cnt]["last_name"] 	= cTYPE::gstr($row["last_name"]);
		$rows[$cnt]["dharma_name"] 	= cTYPE::gstr($row["dharma_name"]);
		$rows[$cnt]["alias"] 		= cTYPE::gstr($row["alias"]);
		$rows[$cnt]["member_title"] = $row["member_title"]?$row["member_title"]:"";
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= cTYPE::gstr($row["city"]);
		$rows[$cnt]["memo"] 		= cTYPE::gstr($row["memo"]);
		$rows[$cnt]["trial"] 		= $row["trial"]?"Y":"";
		//$rows[$cnt]["unauth"] 		= $row["unauth"]?"Y":"";
		$rows[$cnt]["signin"] 		= $row["signin"]?"Y":"";
		$rows[$cnt]["graduate"] 	= $row["graduate"]?"Y":"";
		$rows[$cnt]["cert"] 		= $row["cert"]?"Y":"";

		$rows[$cnt]["attend"] 		= $row["attend"]>0?($row["attend"]*100)."%":"";

		$rows[$cnt]["total_checkin"] = $row["attend"]>0?$total_checkin:"";


		$querya 	= "SELECT   SUM(IF( b.status=2 OR b.status=8, 1, 0)) as total_attend,
								SUM(IF( b.status=4, 1 , 0)) as total_leave 
							FROM event_calendar_enroll a 
							INNER JOIN  event_calendar_attend b ON (a.id = b.enroll_id)
       					    INNER JOIN  event_calendar_date c ON (b.event_date_id = c.id AND b.sn <= c.checkin)
							WHERE a.deleted <> 1 AND a.event_id = '" . $row["event_id"] . "' AND a.id = '" . $row["enroll_id"] . "'";
		$resulta	= $db->query($querya);
		$rowa       = $db->fetch($resulta);

		$rows[$cnt]["total_attend"] = $rowa["total_attend"]?$rowa["total_attend"]:"";
		$rows[$cnt]["total_leave"] = $rowa["total_leave"]?$rowa["total_leave"]:"";

		

		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center">' . ($cnt+1) . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["start_date"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["end_date"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["name"] . '</td>';
		//$html.= '<td ' . $width_two . '>' . $rows[$cnt]["last_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["alias"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["member_title"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["age"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["new_flag"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["member_date"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["gender"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["email"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["cell"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["city"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["memo"] . '</td>';
		//$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["unauth"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["trial"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["signin"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["graduate"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["cert"] . '</td>';

        $html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["total_checkin"] . '</td>';
        $html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["total_attend"] . '</td>';
        $html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["total_leave"] . '</td>';
		
        $html.= '<td ' . $width_two . ' align="right">' . $rows[$cnt]["attend"] . '</td>';
		$html.= '</tr>';

		$cnt++;	
	}
	$html.= '</table>';
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
