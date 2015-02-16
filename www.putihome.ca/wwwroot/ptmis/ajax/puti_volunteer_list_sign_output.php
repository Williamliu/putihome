<?php 
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=volunteer_list.xls");

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$result = $db->query("SELECT b.cname, b.pname, b.en_name, b.dharma_name, b.email, b.gender, b.phone, b.cell, b.city FROM puti_department_volunteer a INNER JOIN puti_volunteer b ON (a.volunteer_id = b.id) WHERE b.deleted <> 1 AND a.department_id = '" . $_REQUEST["pid"] . "' AND a.status = 1 ORDER BY a.status DESC, b.en_name, b.pname, b.dharma_name, b.cname");	
	$rows = array();
	$cnt = 0;

	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html.= '<tr>';
	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html.= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>C.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>E.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Dharma</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Phone</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Cell</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>City</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Work Hours</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Signature</td>';
	$html.= '</tr>';
	

	while( $row = $db->fetch($result)) {
		$cnt++;	
		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cname"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["en_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cell"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["city"] . '</td>';
		$html.= '<td ' . $width_two . '></td>';
		$html.= '<td ' . $width_two . '></td>';
		$html.= '</tr>';
	}
	$html.= '<tr>';
	$html.= '<td colspan="9" style="font-size:12px; font-weight:bold;">Total: ' . $cnt . '</td>';
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
