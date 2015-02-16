<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=event_group_attend.xls");
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = cTYPE::gstr($row_age["title"]);
	}


	$query_date = "SELECT event_date FROM event_calendar_date WHERE event_id = '" . $_REQUEST["event_id"] . "' AND status = 1 AND deleted <> 1 ORDER BY event_date ASC";
	$result_date = $db->query($query_date);
	$date_rows   = $db->rows($result_date);

	$query = "SELECT a.id as enroll_id, b.id, b.first_name, b.last_name, b.dharma_name, b.alias, b.age, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
					 c.title, c.start_date, c.end_date 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
            			INNER JOIN event_calendar c ON (a.event_id = c.id) 
						WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND 
						c.deleted <> 1 AND 
						a.event_id = '" . $_REQUEST["event_id"] . "' AND
						a.group_no = '" . $_REQUEST["group_id"] . "' 
						ORDER BY a.group_no, a.leader DESC, a.volunteer DESC, b.last_name, b.first_name";
	//mysql_query("set names 'utf-8'");
	$result = $db->query($query);

	$cnt = 0;
	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	
	$old_val = '';
	$gcnt = 0;
	while( $row = $db->fetch($result)) {
		if( $old_val != $row["group_no"] ) {
			$old_val = $row["group_no"];
			$gcnt = 0;
			$html.= '<tr>';
			$html.= '<td colspan="' . (count($date_rows) * 2 + 4) . '" align="center" height="30" style="font-size:12px; border:0px; font-weight:bold;">' . cTYPE::gstr($row["title"]) . ' [ ' . date("M d, Y", $row["start_date"]) . ($row["start_date"]>0?' ~ ' .date("M d, Y", $row["end_date"]):'') .  ' ]</td>';
			$html.= '</tr>';

			$html.= '<tr>';
			$html.= '<td colspan="' . (count($date_rows) * 2 + 4) . '" align="left" height="20" style="font-size:12px; font-weight:bold;">' . $words["group"] . ': ' . ($row["group_no"]>0?$row["group_no"]:"TBC"). '</td>';
			$html.= '</tr>';

			$html.= '<tr>';
			$html.= '<td rowspan="2" ' . $width_one . ' ' . $header_css . '>' . $words["sn"] . '</td>';
			$html.= '<td rowspan="2" ' . $width_two . ' ' . $header_css . '>' . $words["name"] . '</td>';
			$html.= '<td rowspan="2" ' . $width_two . ' ' . $header_css . '>' . $words["print name"] . '</td>';
			$html.= '<td rowspan="2" ' . $width_two . ' ' . $header_css . '>' . $words["dharma"] . '</td>';
			foreach( $date_rows as $date_row ) {
				$html.= '<td colspan="2" ' . $width_one . ' ' . $header_css . '>' .date("M j", $date_row["event_date"]) . '</td>';
			}
			$html.= '</tr>';

			$html.= '<tr>';
			foreach( $date_rows as $date_row ) {
				$html.= '<td ' . $width_one . ' ' . $header_css . '>' . $words["sign"] . '</td>';
				$html.= '<td ' . $width_one . ' ' . $header_css . '>' . $words["sign"] . '</td>';
			}
			$html.= '</tr>';

		}
		$gcnt++;
		$cnt++;	
		$html.= '<tr height="25">';
		$html.= '<td ' . $width_one . ' align="center">' . $gcnt . '</td>';

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . '</td>';

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$html.= '<td ' . $width_two . '>' .  trim(cTYPE::gstr(cTYPE::fullfirst($names,13))) . '</td>';

		$names						= array();
		$names["dharma_name"] 		= $row["dharma_name"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::cname($names)) . '</td>';

		foreach( $date_rows as $date_row ) {
			$html.= '<td ' . $width_two . '></td>';
			$html.= '<td ' . $width_two . '></td>';
		}
		
		$html.= '</tr>';
	}
	//$html.= '<tr>';
	//$html.= '<td colspan="9" align="center" style="height:10px;"></td>';
	//$html.= '</tr>';
	$html.= '<tr>';
	$html.= '<td colspan="' . (count($date_rows) * 2 + 4). '" style="font-size:12px; font-weight:bold;">' . $words["total"] . ': ' . $cnt . '</td>';
	$html.= '</tr>';
	
	$html.= '</table>';
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
