<?php 
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=event_summary_report.xls");

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$evt = array();
	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
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
	
	$query0 = "SELECT id FROM event_calendar  
					WHERE deleted <> 1 AND class_id = '" . $_REQUEST["class_id"] . "' AND
						  site IN " . $admin_user["sites"] ." AND branch IN " . $admin_user["branchs"] . "
						  $ccc   
					ORDER BY start_date DESC";

	$result0 = $db->query($query0);

	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$period = ($_REQUEST["start_date"]>0?$_REQUEST["start_date"]:"long long ago") . " ~ " . ($_REQUEST["end_date"]>0?$_REQUEST["end_date"]:"Today");

	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';

		$html .= '<tr>';
		$html .= '<td colspan="20" align="center"><span style="font-size:12px; font-weight:bold;">Class Summary Report<br>' . $period . '</span></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' width="20" rowspan="2">SN</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Event Title</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Start Date</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">End Date</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' rowspan="2">Status</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3">Enroll</td>';
		//$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3">Unauth</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3">Trial</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3">SignIn</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3">Graduate</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . ' colspan="3">Certify</td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Male</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Female</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Total</td>';
        /*
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Male</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Female</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Total</td>';
        */
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Male</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Female</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Total</td>';

		$html .= '<td ' . $width_one . ' ' . $header_css . '>Male</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Female</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Total</td>';

		$html .= '<td ' . $width_one . ' ' . $header_css . '>Male</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Female</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Total</td>';

		$html .= '<td ' . $width_one . ' ' . $header_css . '>Male</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Female</td>';
		$html .= '<td ' . $width_one . ' ' . $header_css . '>Total</td>';

		$html .= '</tr>';

	$sss = array();
	$sss[0] = "Inactive";
	$sss[1] = "Active";
	$sss[2] = "Open";
	$sss[9] = "Closed";

	$cnt0=0;
	while($row0 = $db->fetch($result0)) {
		$evt_arr = array();
		$evt_id 	= $row0["id"];

		$query1 	= "SELECT id, title, status, start_date, end_date FROM event_calendar 
									WHERE deleted <> 1 AND 
										  site IN " . $admin_user["sites"] ." AND branch IN " . $admin_user["branchs"] . " AND 
										  id = '" . $evt_id . "'";
		
		$result1	= $db->query($query1);
		$row1 		= $db->fetch($result1);

		$evt_arr["id"] 				= $row1["id"];
		$evt_arr["title"] 			= $row1["title"];
		$evt_arr["start_date"] 		= $row1["start_date"]>0?date("Y-m-d", $row1["start_date"]):'';
		$evt_arr["end_date"] 		= $row1["end_date"]>0?date("Y-m-d", $row1["end_date"]):'';
		$evt_arr["date_range"] 		= $evt_arr["start_date"] . ($evt_arr["end_date"]!=''?' ~ ' . $evt_arr["end_date"]:'');
		$evt_arr["status"] 			= $row1["status"];

		$query2 	= "SELECT 
								count(a.id) as total,  
								sum(if(b.gender='Male',1,0)) as menro, 
								sum(if(b.gender='Female' || b.gender='',1,0)) as fenro,
								
								sum(if(b.gender='Male',unauth,0)) as munauth, 
								sum(if(b.gender='Female' || b.gender='',unauth,0)) as funauth, 
								sum(unauth) as tunauth,
	
								sum(if(b.gender='Male',trial,0)) as mtrial, 
								sum(if(b.gender='Female' || b.gender='',trial,0)) as ftrial, 
								sum(trial) as ttrial,
							
								sum(if(b.gender='Male',signin,0)) as msign, 
								sum(if(b.gender='Female' || b.gender='',signin,0)) as fsign, 
								sum(signin) as tsign,
								
								sum(if(b.gender='Male',graduate,0)) as mgrad, 
								sum(if(b.gender='Female' || b.gender='',graduate,0)) as fgrad, 
								sum(graduate) as tgrad,

								sum(if(b.gender='Male',cert,0)) as mcert, 
								sum(if(b.gender='Female' || b.gender='',cert,0)) as fcert,
								sum(cert) as tcert
						
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
						INNER JOIN event_calendar c ON (a.event_id = c.id) 
						WHERE a.deleted <> 1 AND b.deleted <> 1 AND c.deleted <> 1 AND 
							  c.site IN " . $admin_user["sites"] ." AND c.branch IN " . $admin_user["branchs"] . " AND 
							  a.event_id = '" . $evt_id . "'";
		
		$result2	= $db->query($query2);
		$row2 		= $db->fetch($result2);
		
		$evt_arr["total"] 			= $row2["total"]==""?0:$row2["total"];
		$evt_arr["menro"] 			= $row2["menro"]==""?0:$row2["menro"];
		$evt_arr["fenro"] 			= $row2["fenro"]==""?0:$row2["fenro"];
        
		$evt_arr["munauth"] 		= $row2["munauth"]==""?0:$row2["munauth"];
		$evt_arr["funauth"] 		= $row2["funauth"]==""?0:$row2["funauth"];
		$evt_arr["tunauth"] 		= $row2["tunauth"]==""?0:$row2["tunauth"];
	    
		$evt_arr["mtrial"] 			= $row2["mtrial"]==""?0:$row2["mtrial"];
		$evt_arr["ftrial"] 			= $row2["ftrial"]==""?0:$row2["ftrial"];
		$evt_arr["ttrial"] 			= $row2["ttrial"]==""?0:$row2["ttrial"];

		$evt_arr["msign"] 			= $row2["msign"]==""?0:$row2["msign"];
		$evt_arr["fsign"] 			= $row2["fsign"]==""?0:$row2["fsign"];
		$evt_arr["tsign"] 			= $row2["tsign"]==""?0:$row2["tsign"];

		$evt_arr["mgrad"] 			= $row2["mgrad"]==""?0:$row2["mgrad"];
		$evt_arr["fgrad"] 			= $row2["fgrad"]==""?0:$row2["fgrad"];
		$evt_arr["tgrad"] 			= $row2["tgrad"]==""?0:$row2["tgrad"];

		$evt_arr["mcert"] 			= $row2["mcert"]==""?0:$row2["mcert"];
		$evt_arr["fcert"] 			= $row2["fcert"]==""?0:$row2["fcert"];
		$evt_arr["tcert"] 			= $row2["tcert"]==""?0:$row2["tcert"];

		$evt[$cnt0]					= $evt_arr;



					$html .= '<tr>';
		
					$html .= '<td width="20" align="center" valign="middle">';
					$html .= $cnt0 + 1;
					$html .= '</td>';

					$html .= '<td valign="middle">';
					$html .=  $evt_arr["title"]; //+ '{<span style="color:blue;">' + evtObj[idx].date_range +'</span>}';
					$html .= '</td>';
					
					$html .= '<td align="center" valign="middle">';
					$html .=  $evt_arr["start_date"];
					$html .= '</td>';
					
					$html .= '<td align="center" valign="middle">';
					$html .=  $evt_arr["end_date"];
					$html .= '</td>';

					$html .= '<td valign="middle">';
					$html .=  $sss[$evt_arr["status"]];
					$html .= '</td>';

					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["menro"];
					$html .= '</td>';

					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["fenro"];
					$html .= '</td>';

					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["total"];
					$html .= '</td>';
                    /*
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["munauth"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["funauth"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["tunauth"];
					$html .= '</td>';
                    */
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["mtrial"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["ftrial"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["ttrial"];
					$html .= '</td>';


					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["msign"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["fsign"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["tsign"];
					$html .= '</td>';

					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["mgrad"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["fgrad"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["fgrad"];
					$html .= '</td>';

					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["mcert"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["fcert"];
					$html .= '</td>';
					$html .= '<td align="right" valign="middle">';
					$html .=  $evt_arr["tcert"];
					$html .= '</td>';

					$html .= '</tr>';
		$cnt0++;
	}

	$query2 	= "SELECT 
							count(a.id) as total,  
							sum(if(b.gender='Male',1,0)) as menro, 
							sum(if(b.gender='Female' || b.gender='',1,0)) as fenro,
							
							sum(if(b.gender='Male',unauth,0)) as munauth, 
							sum(if(b.gender='Female' || b.gender='',unauth,0)) as funauth, 
							sum(unauth) as tunauth,

							sum(if(b.gender='Male',trial,0)) as mtrial, 
							sum(if(b.gender='Female' || b.gender='',trial,0)) as ftrial, 
							sum(trial) as ttrial,
							
							sum(if(b.gender='Male',signin,0)) as msign, 
							sum(if(b.gender='Female' || b.gender='',signin,0)) as fsign, 
							sum(signin) as tsign,
							
							sum(if(b.gender='Male',graduate,0)) as mgrad, 
							sum(if(b.gender='Female' || b.gender='',graduate,0)) as fgrad, 
							sum(graduate) as tgrad,

							sum(if(b.gender='Male',cert,0)) as mcert, 
							sum(if(b.gender='Female' || b.gender='',cert,0)) as fcert,
							sum(cert) as tcert
					
					FROM event_calendar_enroll a 
					INNER JOIN puti_members b ON (a.member_id = b.id)  
					INNER JOIN event_calendar c ON (a.event_id = c.id) 
					WHERE a.deleted <> 1 AND b.deleted <> 1 AND c.deleted <> 1 AND 
						  c.site IN " . $admin_user["sites"] ." AND c.branch IN " . $admin_user["branchs"] . " AND 
						 c.class_id = '" . $_REQUEST["class_id"] . "' $ccc";
	
	$result2	= $db->query($query2);
	$row2 		= $db->fetch($result2);
	$grand = array();
	$grand["total"] 			= $row2["total"]==""?0:$row2["total"];
	$grand["menro"] 			= $row2["menro"]==""?0:$row2["menro"];
	$grand["fenro"] 			= $row2["fenro"]==""?0:$row2["fenro"];

	$grand["munauth"] 			= $row2["munauth"]==""?0:$row2["munauth"];
	$grand["funauth"] 			= $row2["funauth"]==""?0:$row2["funauth"];
	$grand["tunauth"] 			= $row2["tunauth"]==""?0:$row2["tunauth"];

	$grand["mtrial"] 			= $row2["mtrial"]==""?0:$row2["mtrial"];
	$grand["ftrial"] 			= $row2["ftrial"]==""?0:$row2["ftrial"];
	$grand["ttrial"] 			= $row2["ttrial"]==""?0:$row2["ttrial"];

	$grand["msign"] 			= $row2["msign"]==""?0:$row2["msign"];
	$grand["fsign"] 			= $row2["fsign"]==""?0:$row2["fsign"];
	$grand["tsign"] 			= $row2["tsign"]==""?0:$row2["tsign"];

	$grand["mgrad"] 			= $row2["mgrad"]==""?0:$row2["mgrad"];
	$grand["fgrad"] 			= $row2["fgrad"]==""?0:$row2["fgrad"];
	$grand["tgrad"] 			= $row2["tgrad"]==""?0:$row2["tgrad"];

	$grand["mcert"] 			= $row2["mcert"]==""?0:$row2["mcert"];
	$grand["fcert"] 			= $row2["fcert"]==""?0:$row2["fcert"];
	$grand["tcert"] 			= $row2["tcert"]==""?0:$row2["tcert"];

					$html .= '<tr>';
		
					$html .= '<td colspan="5" align="right"><b>';
					$html .= 'Grand Total:';
					$html .= '</b></td>';

					$html .= '<td align="right">';
					$html .=  $grand["menro"];
					$html .= '</td>';

					$html .= '<td align="right">';
					$html .=  $grand["fenro"];
					$html .= '</td>';

					$html .= '<td align="right">';
					$html .=  $grand["total"];
					$html .= '</td>';
                    /*
					$html .= '<td align="right">';
					$html .=  $grand["munauth"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["funauth"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["tunauth"];
					$html .= '</td>';
                    */
					$html .= '<td align="right">';
					$html .=  $grand["mtrial"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["ftrial"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["ttrial"];
					$html .= '</td>';

					$html .= '<td align="right">';
					$html .=  $grand["msign"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["fsign"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["tsign"];
					$html .= '</td>';

					$html .= '<td align="right">';
					$html .=  $grand["mgrad"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["fgrad"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["fgrad"];
					$html .= '</td>';

					$html .= '<td align="right">';
					$html .=  $grand["mcert"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["fcert"];
					$html .= '</td>';
					$html .= '<td align="right">';
					$html .=  $grand["tcert"];
					$html .= '</td>';

					$html .= '</tr>';

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
