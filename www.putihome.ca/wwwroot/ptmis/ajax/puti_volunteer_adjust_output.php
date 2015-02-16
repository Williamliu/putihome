<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");


$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=volunteer_hours_adjust.xls");

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$pid 		= $_REQUEST["pid"];

	$sd = cTYPE::datetoint($_REQUEST["start_date"]);
	$ed = cTYPE::datetoint($_REQUEST["end_date"]);
	if( $sd != "" && $ed != "" ) {
		$ed = mktime("23","59","59", date("n", $ed), date("j", $ed), date("Y", $ed) );
		$ccc = "work_date >= '" . $sd . "' AND work_date <= '" . $ed . "'";
	} elseif($sd != "") {
		$ccc = "work_date >= '" . $sd . "'";
	} elseif($ed != "") {
		$ed = mktime("23","59","59", date("n", $ed), date("j", $ed), date("Y", $ed) );
		$ccc = "work_date <= '" . $ed . "'";
	} else {
		$ccc = "";
	}
	$ccc = ($ccc==""?"":" AND ") . $ccc;


	$_REQUEST["orderBY"] = $_REQUEST["orderBY"]==""?"dharma_name":$_REQUEST["orderBY"];
	$_REQUEST["orderSQ"] = $_REQUEST["orderSQ"]==""?"ASC":$_REQUEST["orderSQ"];
	
	if( $_REQUEST["orderBY"]=="work_date" ) {
		$order_str = "ORDER BY " . $_REQUEST["orderBY"] . " " . $_REQUEST["orderSQ"] . ", dharma_name ASC";
	} else {
		$order_str = "ORDER BY " . $_REQUEST["orderBY"] . " " . $_REQUEST["orderSQ"] . ", work_date ASC";
	}


	$result00 = $db->query("SELECT title FROM puti_department WHERE id = '" . $pid . "'");
	$row00 = $db->fetch($result00);

	$query11 	= "SELECT count(a.id) as work_count, sum(a.work_hour) as total_hour  
					FROM puti_volunteer_hours a  INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id)  
					WHERE b.deleted <> 1 AND a.department_id = '" . $pid . "' AND
					      b.site IN " . $admin_user["sites"] . " $ccc";
	$result11 	= $db->query($query11);
	$row11 		= $db->fetch($result11);

	$response["data"]["pid"] 		= $_REQUEST["pid"];
	$response["data"]["work_date"] 	= $_REQUEST["work_date"];
	$response["data"]["title"] 		= $row00["title"]==""?"Department":$row00["title"];
	$response["data"]["work_count"] = $row11["work_count"]>0?$row11["work_count"]:'';
	$response["data"]["total_hour"] = $row11["total_hour"]>0?$row11["total_hour"]:'';



	$query1 = "SELECT a.id as hid, a.department_id, a.volunteer_id, a.job_id, ifnull(c.job_title, '" . $words["unknown"] . "') as job_title, a.purpose, a.work_date, a.work_hour, b.cname, b.pname, b.en_name, b.dharma_name, b.phone  
					FROM puti_volunteer_hours a 
					INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) 
					LEFT JOIN puti_department_job c ON (a.job_id = c.job_id AND a.department_id = c.department_id)
					WHERE b.deleted <> 1 AND a.department_id = '" . $pid . "' AND 
						  b.site IN " . $admin_user["sites"] . " $ccc  
					$order_str";
	$result1 = $db->query($query1);



	$html = '<table border="1" cellpadding="2" style="font-size:14px; width:350px;">';

	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	$width_one = '';
	$width_two = '';

	$html.= '<td ' . $width_one . ' ' . $header_css . ' colspan="7" style="height:25px; font-size:14px; font-weight:bold;" align="center">' . $response["data"]["title"] . '<br>' . $response["data"]["work_date"] . '</td>';
	$html.= '<tr>';
	
	$html.= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>C.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>E.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Dharma</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Work Date</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . ' width="200">Duty</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Work Hour</td>';
	$html.= '</tr>';


	$cnt1=0;
	$dArr = array();
	while($row1 = $db->fetch($result1)) {
		$dObj = array();
		$dObj["hid"] 			= $row1["hid"];
		$dObj["department_id"] 	= $row1["department_id"];
		$dObj["volunteer_id"] 	= $row1["volunteer_id"];
		$dObj["cname"] 			= $row1["cname"];
		$dObj["pname"] 			= $row1["pname"];
		$dObj["en_name"] 		= $row1["en_name"];
		$dObj["dharma_name"] 	= $row1["dharma_name"];
		$dObj["job_title"] 		= $row1["job_title"];
		$dObj["work_date"] 		= $row1["work_date"]>0?date("Y-m-d",$row1["work_date"]):'';
		$dObj["work_hour"] 		= $row1["work_hour"]>0?$row1["work_hour"]:'';
		$dArr[$cnt1] = $dObj;

		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center" style="height:25px;">' . ($cnt1 + 1) . '</td>';
		$html.= '<td ' . $width_two . '>' . $dObj["cname"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $dObj["en_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $dObj["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $dObj["work_date"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $dObj["job_title"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $dObj["work_hour"] . '</td>';
		$html.= '</tr>';
		$cnt1++;	
	}
	
	$html.= '<tr>';
	$html.= '<td colspan="6" align="right" style="font-size:12px; font-weight:bold;">';
	$html.='Grand Total: ';
	$html.='</td>';
	$html.= '<td style="font-size:12px; font-weight:bold;">' . $response["data"]["total_hour"] . '</td>';
	$html.= '</tr>';
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
