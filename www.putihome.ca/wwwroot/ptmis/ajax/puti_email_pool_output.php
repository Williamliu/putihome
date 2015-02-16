<?php 
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=Email_Pool_list.xls");

	$orderBY	= $_REQUEST["orderBY"]==""?"created_time":$_REQUEST["orderBY"];
	$orderSQ	= $_REQUEST["orderSQ"]==""?"DESC":$_REQUEST["orderSQ"];

	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	// condition here 
	$criteria = "";
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	
	// end of criteria
	
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$query_base = "SELECT b.* 
							FROM puti_email a
							INNER JOIN puti_members b ON ( a.member_id = b.id )
							WHERE 	b.status = 1 AND b.deleted <> 1 AND 
								 	b.email_flag = 1 AND
									b.email	<> '' AND
									b.site in " . $admin_user["sites"] . " AND admin_id = '" . $admin_user["id"] . "'   
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
	
	$html.= '<td ' . $width_one . ' ' . $header_css . '>SN</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Flag</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>F.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>L.Name</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Dharma</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Gender</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Email</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Phone</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Cell</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>City</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Created Time</td>';
	$html.= '</tr>';
	
	
	$cnt = 0;
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["email_flag"] 	= $row["email_flag"]?"Yes":"No";
		$rows[$cnt]["first_name"] 	= $row["first_name"];
		$rows[$cnt]["last_name"] 	= $row["last_name"];
		$rows[$cnt]["dharma_name"] 	= $row["dharma_name"];
		$rows[$cnt]["gender"] 		= $row["gender"];
		$rows[$cnt]["email"] 		= $row["email"];
		$rows[$cnt]["phone"] 		= $row["phone"];
		$rows[$cnt]["cell"] 		= $row["cell"];
		$rows[$cnt]["city"] 		= $row["city"];
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d H:i:s",$row["created_time"]):'';

		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center">' . ($cnt+1) . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["email_flag"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["first_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["last_name"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["dharma_name"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $rows[$cnt]["gender"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["email"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cell"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["city"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $rows[$cnt]["created_time"] . '</td>';
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
