<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=website_lang.xls");

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "";
	}

	
	// condition here 
	$criteria = "";
	$con = $_REQUEST["condition"]; 

	$sch_11 = trim($con["sch_project"]);
	if($sch_11 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "project like '%" . $sch_11 . "%'";
	}

	$sch_22 = trim($con["sch_filter"]);
	if($sch_22 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "filter like '%" . $sch_22 . "%'";
	}

	$sch_33 = trim($con["sch_keyword"]);
	if($sch_33 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "keyword like '%" . $sch_33 . "%'";
	}

	$sch_44 = trim($con["sch_content"]);
	if($sch_44 != "") {
		$criteria .= ($criteria==""?"":" AND ") . "(en like '%" . $sch_44 . "%' OR cn like '%" . $sch_44 . "%')";
	}


	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria
	
	
	$query_base = "SELECT * 
						FROM website_language_word   
            			WHERE  deleted <> 1  
						$criteria 
						$order_str";
	
	//echo "query:" . $query_base;
	$result_num = $db->query("SELECT COUNT(id) AS CNT FROM ( " . $query_base . " ) res1");
	$row_total = $db->fetch($result_num);
	$recoTotal =  $row_total["CNT"];
	$pageTotal = ceil($recoTotal/$pageSize);
						

	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();
	$cnt = 0;
	
	$html = '<table border="1" cellpadding="2" style="font-size:12px; width:350px;">';
	$html.= '<tr>';
	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . $words["sn"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["project"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["filter"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["keyword"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["lang.en"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["lang.cn"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["lang.tw"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>ID</td>';
	$html.= '</tr>';
	
	while( $row = $db->fetch($result)) {
		$rows[$cnt]["id"] 			= $row["id"];
		$rows[$cnt]["project"] 		= stripslashes($row["project"]);
		$rows[$cnt]["filter"] 		= stripslashes($row["filter"]);
		$rows[$cnt]["keyword"] 		= stripslashes($row["keyword"]);
		$rows[$cnt]["en"] 			= stripslashes($row["en"]);
		$rows[$cnt]["cn"] 			= stripslashes($row["cn"]);
		$rows[$cnt]["tw"] 			= stripslashes($row["tw"]);
		$rows[$cnt]["created_time"]	= $row["created_time"]>0?date("Y-m-d H:i:s",$row["created_time"]):"";


		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt . '</td>';
		$html.= '<td ' . $width_two . '>' . stripslashes($row["project"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . stripslashes($row["filter"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . stripslashes($row["keyword"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . stripslashes($row["en"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . stripslashes($row["cn"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . stripslashes($row["tw"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["id"] . '</td>';
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
