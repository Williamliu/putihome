<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$order_str = "ORDER BY c.group_no ASC, c.id ASC";

	
	// condition here 
	$criteria = "";

	$con = $_REQUEST; 
	
	$sch_name = trim($con["sch_name"]);
	if($sch_name != "") {
		$criteria .= ($criteria==""?"":" AND ") . 
						"( 	first_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							last_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							legal_first like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							legal_last like '%" .	cTYPE::trans_trim($sch_name) . "%' OR 
							dharma_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							alias like '%" . cTYPE::trans_trim($sch_name) . "%' OR
							concat(first_name, last_name) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(last_name,  first_name) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(legal_first, legal_last) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(legal_last, legal_first) like '%" . 	cTYPE::trans_trim($sch_name) . "%'
						)";
	}

	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_group = trim($con["sch_group"]);
	if($sch_group != "") {
		$criteria .= ($criteria==""?"":" AND ") . "group_no = '" . $sch_group . "'";
	}


	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}

	// important,   if  scan ID Card,  search in whole list without site restrict  
	$sch_idd = trim($con["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$criteria .= ($criteria==""?"":" AND ") . "a.id = '" . $mem_id . "'";
		} else {
				$criteria .= ($criteria==""?"":" AND ") . "a.id = '-1'";
		}
	}


	$sch_mid = trim($con["member_id"]);
	if($sch_mid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.id = '" . $sch_mid . "'";
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}

    $query_sites = "SELECT id, title, site_name_cn, site_name_en, school_cn, school_en FROM puti_sites WHERE id = '" . $admin_user["site"] . "'";
    $result_sites = $db->query($query_sites);
    $row_sites = $db->fetch($result_sites);

    $puti_site_cn = cTYPE::gstr($row_sites["site_name_cn"]);
    $puti_site_en = cTYPE::gstr($row_sites["site_name_en"]);
    if($puti_site_cn == $puti_site_en) $puti_site_en = "";

	$query_class = "SELECT  a.site, a.start_date, a.end_date, a.title as event_title, b.title as class_title, b.cert_prefix 
							FROM 	event_calendar a 
							INNER JOIN puti_class b ON (a.class_id = b.id)
							WHERE a.id='" . $con["event_id"] . "'";  
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$class_site 	= strtolower($sites[$row_class["site"]]);
	$event_date 	= $row_class["end_date"]>=0?date("F jS, Y",$row_class["end_date"]):date("F jS, Y",$row_class["start_date"]);
	$event_year 	= $row_class["start_date"]>=0?date("Y",$row_class["start_date"]):date("Y",$row_class["end_date"]);

	$event_title 	= cTYPE::gstr($row_class["event_title"]);
	$class_title 	= cTYPE::gstr($event_year . '年菩提禅修-' . $row_class["class_title"] . '结业证书');
	$ci_zheng_ming  = cTYPE::gstr("兹证明");
	$yuan_man		= cTYPE::gstr("圆满完成了" . $row_class["event_title"] . "课程,");
	$ban_zheng		= cTYPE::gstr("特颁发此结业证书。");
	$riqi			= cTYPE::gstr("日期");
	$bianhao		= cTYPE::gstr("证书编号");
	$qianzhi		= cTYPE::gstr("签字");
	$puti_famen		= cTYPE::gstr("加拿大菩提法门协会");
	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	$query_base = "SELECT a.*,c.group_no, c.cert_no  
						FROM puti_members a
						LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
						INNER JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1 AND cert = 1) c ON ( a.id = c.member_id ) 
						WHERE  a.deleted <> 1  
						$criteria 
						$order_str";
	
	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();

	$first = true;
	
	while( $row = $db->fetch($result)) {
		

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["dharma_name"] 		= $row["dharma_name"];
		$names["alias"] 			= $row["alias"];
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];
		if($_REQUEST["lfname"]=="1") 
			$fname			= trim(cTYPE::gstr(cTYPE::cert_lname($names)));
		else 
			$fname 			= trim(cTYPE::gstr(cTYPE::cert_cname($names)));

		$names						= array();
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];

		if($_REQUEST["lfname"]=="1") 
			$lname			= trim(cTYPE::gstr(cTYPE::lfname1($names)));
		else 
			$lname 			= trim(cTYPE::gstr(cTYPE::cname($names)));

		//$lname = cTYPE::gstr(cTYPE::cname($names));
		
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);
		$cert_no 					= $row["cert_no"]?$row["cert_no"]:"";

		if( $first ) {
			$first = false;
			$html .= '<center><div style="position:relative;display:block;height:10px;"></div>';
		} else {
			$html .= '<center><div style="position:relative;display:block;height:10px;page-break-before:always;"></div>';
		}

		$html .= '<div style="position:relative;display:block;width:600px;border:0px solid #cccccc;">';

		$html .= '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_watermark.png" width="420" style="position:absolute;top:50%;left:50%;margin-top:-240px;margin-left:-210px;" />';
	
		$html .= '<div align="center" style="position:relative;padding-top:90px;"><span style="font-size:30px;font-weight:bold;font-family:Old English Text MT;">Bodhi Meditation Program Certification</span></div>';
		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:22px;font-family:隶书;">'  . $class_title . '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:20px;"><span style="font-size:30px;font-weight:bold;font-family:Edwardian Script ITC;">This is to certify that</span></div>';
		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:24px;font-family:隶书;">' . $ci_zheng_ming . '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:36px;letter-spacing:1px;font-style:italic;font-family:隶书;">' . $lname . '&nbsp;</span></div>';
		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:36px;letter-spacing:5px;font-family:隶书;">' . $fname . '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:20px;"><span style="font-size:30px;font-weight:bold;font-family:Edwardian Script ITC;">';
		$html .= 'has satisfactorily completed';
		$html .= '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:30px;font-weight:bold;font-family:Edwardian Script ITC;">';
		$html .= 'the Bodhi Bagua Walking Meditation Basic Program.';
		$html .= '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:24px;font-family:隶书;">';
		$html .= $yuan_man;
		$html .= '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:24px;font-family:隶书;">';
		$html .= $ban_zheng;
		$html .= '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:20px;"><span style="font-size:28px;font-weight:bold;font-family:Edwardian Script ITC;">Date </span>';
		$html .= '<span style="font-size:24px;font-style:normal;font-family:隶书;"> ' . $riqi . '： ';
		$html .= '<span style="font-size:28px;font-weight:bold;font-family:Edwardian Script ITC;">' . $event_date . '</span>';
		$html .= '</span></div>';

		$html .= '<div align="center" style="position:relative;padding-top:10px;"><span style="font-size:28px;font-weight:bold;font-family:Edwardian Script ITC;">Certificate No </span>';
		$html .= '<span style="font-size:24px;font-style:normal;font-family:隶书;"> ' . $bianhao . ' </span>: ';
		$html .= '<span style="font-size:24px;font-weight:bold;font-family:Edwardian Script ITC;">' . $cert_no . '</span>';
		$html .= '</span></div>';


		$html .= '<div align="left" style="position:relative;padding-top:60px; padding-left:50px;"><span style="font-size:28px;font-weight:bold;font-family:Edwardian Script ITC;">Signature </span>';
		$html .= '<span style="font-size:24px;font-style:normal;font-family:隶书;">' . $qianzhi . ' <div align="center" style="display:inline-block;width:200px;border-bottom:1px solid black;"><img src="../theme/blue/image/icon/muyu_signature.png" height="75" /></div>' ;
		$html .= '</span></div>';

		$html .= '<div align="left" style="position:relative;padding-top:10px; padding-left:50px;"><span style="font-size:16px;font-weight:bold;font-family:Edwardian Script ITC;">';
		$html .= $puti_site_en; //'The Canada Bodhi Dharma Society' ;
		$html .= '</span></div>';

		$html .= '<div align="left" style="position:relative;padding-top:5px; padding-left:50px;"><span style="font-size:16px;font-style:normal;letter-spacing:5px;font-family:隶书;">';
		$html .=  $puti_site_cn;
		$html .= '</span></div>';

		$html .= '<div align="left" style="position:relative;display:block;height:10px;border:0px solid black;">';
		$html .= '</div>';

		$html .= '</div>';
		$html .= '</center><br>';
	}
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
	$response["data"] = $html;
	echo json_encode($response);

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
