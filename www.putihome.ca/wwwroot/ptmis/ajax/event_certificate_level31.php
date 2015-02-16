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

	$query_class = "SELECT a.start_date, a.end_date, a.title as event_title, b.title as class_title, b.cert_prefix, b.photo 
							FROM 	event_calendar a 
							INNER JOIN puti_class b ON (a.class_id = b.id)
							WHERE a.id='" . $con["event_id"] . "'";  
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$event_title 	= $row_class["event_title"];
	$class_title 	= $row_class["class_title"];
	$event_date 	= $row_class["end_date"]>=0?date("Y-m-d",$row_class["end_date"]):date("Y-m-d",$row_class["start_date"]);
	$event_year 	= $row_class["start_date"]>=0?date("Y",$row_class["start_date"]):date("Y",$row_class["end_date"]);
	$photo_req		= $row_class["photo"]>0?true:false;
	
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
		$member_id 					= $row["id"];

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		if($_REQUEST["lfname"]=="1") 
			$fname			= trim(cTYPE::gstr(cTYPE::cert_lname($names)));
		else 
			$fname 			= trim(cTYPE::gstr(cTYPE::cert_cname($names)));

		$names						= array();
		$names["dharma_name"] 		= $row["dharma_name"];
		$dname = cTYPE::gstr(cTYPE::cname($names));
		
		if($fname==$dname) $fname = "";
		
		$names						= array();
		$names["legal_first"] 		= $row["legal_first"];
		$names["legal_last"] 		= $row["legal_last"];
		if($_REQUEST["lfname"]=="1") 
			$lname			= trim(cTYPE::gstr(cTYPE::lfname1($names)));
		else 
			$lname 			= trim(cTYPE::gstr(cTYPE::cname($names)));
		
		$rows[$cnt]["site"] 		= cTYPE::gstr($words[strtolower($sites[$row["site"]])]);
		$cert_no = $row["cert_no"]?$row["cert_no"]:"";

		$dharma_pinyin = $row["dharma_pinyin"];

		$bbr = getBrowser();
		$layout1 = "420px";
		$layout2 = "380px";
		$photo1	 = "378px";

		if( preg_match("/chrome/i", $bbr["name"]) ) {
			$layout1 = "390px";
			$layout2 = "360px";
		}
		
		if($dname != "") 
			$dname_html = '<span style="font-size:34px;font-family:隶书;">' . $dname . '</span>';
		else 
			$dname_html = '';
			
		$line_one_name = $lname;
		$line_two_name = $dname_html . '&nbsp;&nbsp;' . $fname; 
		if( $line_one_name == "" ) $line_one_name = $fname;
		if( $line_one_name == $fname ) $line_two_name = $dname_html;
		if( $line_two_name == $dname_html ) $line_two_name = $dname_html . ($dharma_pinyin!=""?" ":"") . $dharma_pinyin;
		
		$maginleft = "0px";
		if( max(mb_strlen($fname,"utf-8"), mb_strlen($lname,"utf-8")) > 13 ) {
			$maginleft = ( -20 * (max(strlen($fname), strlen($lname))-13) ) . "px";
		} else {
			$maginleft = "0px";
		}
		//echo "max:" . strlen($fname) . ":" . strlen($lname) . ":" . max(strlen($fname), strlen($lname)) . "<br>";
				
		
		$fn = $CFG["upload_path"] . "/small/" . $member_id . ".jpg";
		if(file_exists($fn)) 
			$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/lwhUpload_image.php?ts=' . time() . '&size=small&img_id=' . $member_id . '" height="160" style="position:absolute;top:' . $photo1 . ';left:430px;border:0px solid #cccccc;" />';
		else 
			$photo_html = '';
		
		if( $first ) {
			$first = false;
			$html .= '<center><div style="position:relative;display:block;height:10px;"></div>';
		} else {
			$html .= '<center><div style="position:relative;display:block;height:10px;page-break-before:always;"></div>';
		}


		
		$html .= '<div style="position:relative;display:block;width:600px;border:0px solid #cccccc;">';

		
		$html .= '<div align="center" style="position:relative;padding-top:' . $layout1 . ';">';
		if( cTYPE::iifcn($line_one_name) ) 
			$html .= '<div align="center" style="position:relative;padding-top:10px; height:35px; margin-left:' . $maginleft . '; border:0px solid black;"><span style="font-size:34px;font-family:隶书;">' . $line_one_name . '</span></div>';
		else 
			$html .= '<div align="center" style="position:relative;padding-top:10px; height:35px; margin-left:' . $maginleft . '; border:0px solid black;"><span style="font-size:24px;font-weight:bold;font-family:Arial Bold,Arial;">' . $line_one_name . '</span></div>';
		
		if( cTYPE::iifcn($fname) && $dname !="" )
			$html .= '<div align="center" style="position:relative;padding-top:20px; height:35px; margin-left:' . $maginleft . '; border:0px solid black;"><span style="font-size:34px;font-family:隶书;">' . $line_two_name . '</span></div>';
		else 
			$html .= '<div align="center" style="position:relative;padding-top:20px; height:35px; margin-left:' . $maginleft . '; border:0px solid black;"><span style="font-size:24px;font-weight:bold;font-family:Arial Bold,Arial;">' . $line_two_name . '</span></div>';
		
		$html .= '</div>';

		if($photo_req) $html .= $photo_html;
		

		$html .= '<div align="left" style="position:relative;padding-top:' . $layout2 . '; padding-left:60px; padding-right:90px;">';
		
		/*
		$html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$html .= '<tr>';
		$html .= '<td valign="middle" align="left">';
			$html .= '<span style="font-size:22px;font-style:normal;font-family:隶书;">證書編號 </span>';
			$html .= '<span style="font-size:16px;font-style:normal;font-family:\'Comic Sans MS\', cursive;">Certificate No. </span>: ';
			$html .= '<br><span style="font-size:16px;font-style:normal;font-family:\'Comic Sans MS\', cursive;">' . $cert_no .'</span>';
		$html .= '</td>';
		$html .= '<td valign="middle" align="right">';
			$html .= '<span style="font-size:22px;font-style:normal;font-family:隶书;">日期 </span>';
			$html .= '<span style="font-size:16px;font-style:normal;font-family:\'Comic Sans MS\', cursive;">Date </span>: ';
			$html .= '<br><span style="font-size:16px;font-style:normal;font-family:\'Comic Sans MS\', cursive;">' . $event_date .'</span>';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '</table>';
		*/

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


function getBrowser() {
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version= "";

	//First get the platform?
	if (preg_match('/linux/i', $u_agent)) {
		$platform = 'linux';
	}
	elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	}
	elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
	}

	// Next get the name of the useragent yes separately and for good reason.
	if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
	{
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	}
	elseif (preg_match('/Firefox/i',$u_agent))
	{
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	}
	elseif (preg_match('/Chrome/i',$u_agent))
	{
		$bname = 'Google Chrome';
		$ub = "Chrome";
	}
	elseif (preg_match('/Safari/i',$u_agent))
	{
		$bname = 'Apple Safari';
		$ub = "Safari";
	}
	elseif (preg_match('/Opera/i',$u_agent))
	{
		$bname = 'Opera';
		$ub = "Opera";
	}
	elseif (preg_match('/Netscape/i',$u_agent))
	{
		$bname = 'Netscape';
		$ub = "Netscape";
	}

	// Finally get the correct version number.
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .
	')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}

	// See how many we have.
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
			$version= $matches['version'][0];
		}
		else {
			$version= $matches['version'][1];
		}
	}
	else {
		$version= $matches['version'][0];
	}

	// Check if we have a number.
	if ($version==null || $version=="") {$version="?";}

	return array(
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'    => $pattern
	);
}
?>
