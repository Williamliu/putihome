<?php
//session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
require($CFG["web_path"] . "/source/php_pdf/chinese.php");
include($CFG["web_path"] . "/source/php_pdf/html2fpdf.php");
//include_once("website_admin_ajax_auth.php");

$response = array();
try {

	$pdf=new PDF_Chinese();
	$pdf->AddGBFont();
	//$pdf->AddBig5Font();
	$pdf->AddPage();
	$pdf->SetFont('GB','',20);

	/*
	$pdf = new HTML2FPDF();
	//$pdf->DisplayPreferences('HideWindowUI');
	$pdf->Open();
	$pdf->AddPage();
	*/
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	mysql_query("set names 'utf-8'");

	$query = "SELECT c.title, c.start_date, c.end_date 
					  FROM event_calendar c 
					  WHERE  c.id = '" . $_REQUEST["event_id"] . "'";
	$result = $db->query($query);
	$row 	= $db->fetch($result);	
  	$etitle	= stripslashes($row["title"]);
	$edate	= date("Y, M jS", $row["start_date"]) . " ~ " . date("M jS", $row["end_date"]);

	if( $_REQUEST["aflag"]=="2" ) {
		  $info["etitle"] 		= $etitle;
		  $info["edate"] 		= $edate;
		  $info["name"] 		= "";
		  $info["dharma_name"] 	= "";	
		  $info["group_no"] 	= "";
		  
		  for($k = 0 ; $k < 6; $k++ ) {
				$i = $k % 2;
				$j = floor($k / 2);
				item($i, $j, $info);
		  }
		  $pdf->Output();
		  exit;
	}
	

	$crrr = "";
	$grp_id = trim($_REQUEST["group_id"]);
	if( $grp_id != "" ) {
		$crrr .= ($crrr==""?"":" AND ") . "a.group_no = '" . $grp_id . "'"; 
	}

	$enr_id = trim($_REQUEST["enroll_id"]);
	if( $enr_id != "" ) {
		$crrr .= ($crrr==""?"":" AND ") . "a.id = '" . $enr_id . "'"; 
	}

	$crrr = ($crrr==""?"":" AND ") . $crrr; 
	
	if($_REQUEST["aflag"]=="1") {
		  $query = "SELECT a.id as enroll_id, a.confirm, b.id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
						   c.title, c.start_date, c.end_date 
							  FROM event_calendar_enroll a 
							  INNER JOIN puti_members b ON (a.member_id = b.id)  
							  INNER JOIN event_calendar c ON (a.event_id = c.id) 
							  WHERE  a.deleted <> 1 AND 
							  b.deleted <> 1 AND 
							  c.deleted <> 1 AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' 
							  $crrr  
							  ORDER BY b.first_name, b.last_name";
	} else {
		  $query = "SELECT a.id as enroll_id, a.confirm, b.id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
						   c.title, c.start_date, c.end_date 
							  FROM event_calendar_enroll a 
							  INNER JOIN puti_members b ON (a.member_id = b.id)  
							  INNER JOIN event_calendar c ON (a.event_id = c.id) 
							  WHERE  a.deleted <> 1 AND 
							  b.deleted <> 1 AND 
							  c.deleted <> 1 AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' 
							  $crrr  
							  ORDER BY a.group_no,  b.first_name, b.last_name";
	}
	$result = $db->query($query);


	$cnt = 0;
	while( $row = $db->fetch($result)) {
		$info["etitle"] 		= $etitle;
		$info["edate"] 			= $edate;
		$info["name"] 			= (strlen($row["first_name"])>9?substr($row["first_name"],0,9 ) . "." : $row["first_name"])   . " " . substr($row["last_name"],0,1) . ".";
		$info["dharma_name"] 	= $row["dharma_name"];	
		$info["group_no"] 		= $row["group_no"]>0?$row["group_no"]:"";
		$i = $cnt % 2;
		$j = floor($cnt / 2);
		item($i, $j, $info);

		$cnt++;
		if($cnt >= 6) {
		    $cnt=0;
			$pdf->AddPage();
		}
	}
	if($cnt > 0) {
		for($k = $cnt ; $k < 6; $k++ ) {
			  $info["etitle"] 		= $etitle;
			  $info["edate"] 		= $edate;
			  $info["name"] 		= "";
			  $info["dharma_name"] 	= "";	
			  $info["group_no"] 	= "";
			  $i = $k % 2;
			  $j = floor($k / 2);
			  item($i, $j, $info);
		}
	}
	
	
	
	$pdf->Output();
	exit;

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

function item($i, $j, $info) {
	global $pdf;
	$ww = 100;
	$hh = 90;
	$ww_offset = 5;
	$hh_offset = 10;
	$pdf->Image("http://van.putiyea.com/theme/blue/image/background/scard.jpg", $i * $ww + $ww_offset, $j * $hh + $hh_offset, $ww, $hh);

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',24); 
	$pdf->Text($i*$ww + $ww_offset + 28 , $j*$hh + $hh_offset + 24, "Student ID");

	$pdf->SetTextColor(160, 30, 4);
	$pdf->SetFont('GB','',14); 
	//$pdf->Text($i*$ww + $ww_offset + 5 , $j*$hh + $hh_offset + 30, cTYPE::trans(stripslashes($info["etitle"])) );
	$pdf->Text($i*$ww + $ww_offset + 5 , $j*$hh + $hh_offset + 30, $info["etitle"] );
	
	$pdf->SetTextColor(0, 0, 0);

	$pdf->SetFont('Arial','',10); 
	$pdf->Text($i*$ww + $ww_offset + 4 , $j*$hh + $hh_offset + 46, "Name: ");
	$pdf->SetFont('GB','', 40); 
	//$pdf->Text($i*$ww + $ww_offset + 16 , $j*$hh + $hh_offset + 48, cTYPE::tobig5(stripslashes($info["name"])) );
	$pdf->Text($i*$ww + $ww_offset + 16 , $j*$hh + $hh_offset + 48, $info["name"] );

	$pdf->SetFont('Arial','',10); 
	$pdf->Text($i*$ww + $ww_offset + 4 , $j*$hh + $hh_offset + 60, "Dharma: ");
	$pdf->SetFont('GB','', 18); 
	//$pdf->Text($i*$ww + $ww_offset + 20 , $j*$hh + $hh_offset + 60, cTYPE::tobig5(stripslashes($info["dharma_name"])) );
	$pdf->Text($i*$ww + $ww_offset + 20 , $j*$hh + $hh_offset + 60, $info["dharma_name"] );

	$pdf->SetFont('Arial','',10); 
	$pdf->Text($i*$ww + $ww_offset + 6 , $j*$hh + $hh_offset + 71, "Group: ");
	$pdf->SetFont('Arial','',26); 
	$pdf->Text($i*$ww + $ww_offset + 32 , $j*$hh + $hh_offset + 72, $info["group_no"]);

	$pdf->SetTextColor(160, 30, 4);
	$pdf->SetFont('Arial','',14); 
	$pdf->Text($i*$ww + $ww_offset + 22 , $j*$hh + $hh_offset + 82, $info["edate"]);
}
?>
