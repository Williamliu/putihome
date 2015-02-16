<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$order_str = "ORDER BY c.id ASC";
	
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

	$sch_date = trim($con["sch_date"]);
	if($sch_date != "") {
		$sd = cTYPE::datetoint($sch_date);
		$ed = $sd + ( 3600 * 24 - 1);
		$criteria .= ($criteria==""?"":" AND ") . "c.created_time BETWEEN '" . $sd . "' AND '" . $ed . "'";
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

	$query_class = "SELECT a.start_date, a.end_date, a.title as event_title, b.title as class_title, b.cert_prefix, b.photo 
							FROM 	event_calendar a 
							INNER JOIN puti_class b ON (a.class_id = b.id)
							WHERE a.id='" . $con["event_id"] . "'";  
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$event_title 	= $row_class["event_title"];
	$class_title 	= $row_class["class_title"];
	$event_date		= date("Y, M jS", $row_class["start_date"]);
	$event_date    .= $row_class["start_date"]!=$row_class["end_date"]?" ~ " . date("M jS", $row_class["end_date"]):"";
	$photo_req		= $row_class["photo"]>0?true:false;
	
	
	$info 					= array();
	$info["event_title"] 	= trim(cTYPE::gstr($event_title));
	$info["event_date"] 	= trim(cTYPE::gstr($event_date));
	
	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	$query_base = "SELECT a.*,c.group_no, c.cert_no, c.leader, c.volunteer
						FROM puti_members a
						LEFT JOIN ($query_idd) aa0 ON (a.id = aa0.member_id)
						INNER JOIN (SELECT * FROM event_calendar_enroll WHERE event_id = '" . $con["event_id"] . "' AND deleted <> 1) c ON ( a.id = c.member_id ) 
						WHERE  a.deleted <> 1  
						$criteria 
						$order_str";
	
	$query 	= $query_base;
	$result = $db->query( $query );

	$html = '';
	$cnt = 0;
	$first = true;
	while( $row = $db->fetch($result)) {
		if( $first ) {
			$first = false;
			if($cnt==0)  $html .= '<center><div style="position:relative; display:block; width:670px; border:0px solid black; margin:0px; padding:0px;">';
		} else {
			if($cnt==0)  $html .= '<center><div style="position:relative; display:block; width:670px; border:0px solid black; margin:0px; padding:0px; page-break-before:always;">';
		}

		$info["member_id"] 			= $row["id"];	

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];

		if($_REQUEST["lfname"]=="1") 
			$info["name"]			= trim(cTYPE::gstr(cTYPE::lfname1($names,13)));
		else 
			$info["name"] 				= trim(cTYPE::gstr(cTYPE::cert_cname($names,13)));
		
		$info["dharma_name"] 		= trim(cTYPE::gstr($row["dharma_name"]?$row["dharma_name"]:''));
		$info["group_no"] 			= $row["group_no"]>0?$row["group_no"]:'';
		$info["leader"] 			= $row["leader"];	
		$info["volunteer"] 			= $row["volunteer"];	
		$info["photo"]				= file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"";

		
		$i = $cnt % 2;
		$j = floor($cnt / 2);
	    if( $photo_req ) $html .= item_photo($i, $j, $info); else $html .= item($i, $j, $info);
		$cnt++;
		if($cnt >= 8) {
			$html .= '</div></center><br>';
			$cnt=0;
		}
	}

	if($cnt > 0) {
		for($k = $cnt ; $k < 8; $k++ ) {
			  $info["member_id"] 	= -1;
			  $info["name"] 		= "";
			  $info["dharma_name"] 	= "";	
			  $info["group_no"] 	= "";	
			  $info["leader"] 		= "";
			  $info["volunteer"] 	= "";
			  $info["photo"]		= "";			  
			  $i = $k % 2;
			  $j = floor($k / 2);
			  if( $photo_req ) $html .= item_photo($i, $j, $info); else $html .= item($i, $j, $info);
		}
	  	$html .= '</div></center><br>';
	}

	$response["data"] 			= $html;
	$response["errorCode"] 		= 0;
	$response["errorMessage"]	= "";
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

function item($i, $j, $info) {
	global $CFG;
	global $words;
	global $admin_user;
	$html_ret .= '<div style="position:relative; float:left; border:1px dotted #666666; display:inline-block; margin:1px; text-align:left; width:320px; height:200px; overflow:hidden;">';
	//$html_ret .= '<img src="/theme/blue/image/background/scard.jpg" style="position:absolute; top:0px; left:0px;" width="367" height="320" />';
	$html_ret .= '<div style="position:absolute; top:5px; width:100%; text-align:center; font-family:Arial;font-size:18px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;">' . $info["event_title"] . '</div>';

	if("N"=="Y") {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/lwhUpload_image.php?ts=' . time() . '&size=small&img_id=' .  $info["member_id"] . '" height="140" style="border:0px solid #cccccc; vertical-align:middle;" />';
	} else {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_dharma.png" width="70" style="border:0px solid #cccccc; vertical-align:middle;" />';
	}


	$html_ret .= '<div style="position:absolute; top:25px; width:100%; height:150px; border:0px solid red;">';
	$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
	$html_ret .= '<tr>';
	$html_ret .= '<td rowspan="3" valign="middle" width="100" align="center">';
	$html_ret .= '<div style="display:block;width:100%;height:150px;margin-left:2px;">';
	$html_ret .= '<span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
	$html_ret .= $photo_html;
	$html_ret .= '</div>';
	$html_ret .= '</td>';
	$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

	if( cTYPE::iifcn($info["name"]) )
		$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$info["name"] . '</span>';
	else
		$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["name"] . '</span>';

	$html_ret .= '</td>';
	$html_ret .= '</tr>';


	$title 	= '';
	if($info["volunteer"]=="1") $title = cTYPE::gstr($words["tag.volunteer"]);
	if($info["leader"]=="1") 	$title = cTYPE::gstr($words["tag.leader"]);
	if($title != "") 			$title = '<span style="color:#A01E04;">' . $title . '</span>';
	
	if( $info["dharma_name"] != "" && $info["dharma_name"]!=$info["name"]) {
		if( $admin_user["lang"] != "en" ) {
			$html_ret .= '<tr>';
			$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';
			
			if( cTYPE::iifcn($info["dharma_name"]) )
				$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_name"] . ' ' . $title . '</span>';
			else
				$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_name"] . ' ' . $title . '</span>';

			$html_ret .= '</td>';
			$html_ret .= '</tr>';
		} else {
			if( $title != "" ) {
				$html_ret .= '<tr>';
				$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

				if( cTYPE::iifcn($title) )
					$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$title . '</span>';
				else
					$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $title . '</span>';

				$html_ret .= '</td>';
				$html_ret .= '</tr>';
			} else {
				$html_ret .= '<tr>';
				$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

				if( cTYPE::iifcn($info["dharma_name"]) )
					$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$info["dharma_name"] . '</span>';
				else
					$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_name"] . '</span>';

				$html_ret .= '</td>';
				$html_ret .= '</tr>';
			}
		}
	} else {
		if( $title != "" ) {
			$html_ret .= '<tr>';
			$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

			if( cTYPE::iifcn($title) )
				$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$title . '</span>';
			else
				$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $title . '</span>';

			$html_ret .= '</td>';
			$html_ret .= '</tr>';
		}
	}

	if( $info["group_no"] != "") {
		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="center">';
		$html_ret .= '<div align="center" style="display:block;width:36px;height:36px;border:1px solid #A01E04;border-radius:18px 18px 18px 18px;">';
		$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= '<span style="font-size:22px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
		$html_ret .= '</div>';
		//$html_ret .= '<span style="font-size:32px;color:#A01E04;font-family:隶书;">' . cTYPE::gstr($words["none.di"]) . ' ' . $info["group_no"] . ' ' .cTYPE::gstr($words["none.zu"]) . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '</tr>';
	}

	$html_ret .= '</table>';
	$html_ret .= '</div>';

	$html_ret .= '<div style="position:absolute; top:180px; width:100%; text-align:center; font-family:Arial; font-size:14px; color:#A01E04;">' . $info["event_date"] . '</div>';

	$html_ret .= '</div>';
	return $html_ret;
}

function item_photo($i, $j, $info) {
	global $CFG;
	global $words;
	global $admin_user;
	$html_ret .= '<div style="position:relative; float:left; border:1px dotted #666666; display:inline-block; margin:1px; text-align:left; width:320px; height:200px; overflow:hidden;">';
	//$html_ret .= '<img src="/theme/blue/image/background/scard.jpg" style="position:absolute; top:0px; left:0px;" width="367" height="320" />';
	$html_ret .= '<div style="position:absolute; top:5px; width:100%; text-align:center; font-family:Arial;font-size:18px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;">' . $info["event_title"] . '</div>';

	if($info["photo"]=="Y") {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/lwhUpload_image.php?ts=' . time() . '&size=small&img_id=' .  $info["member_id"] . '" height="140" style="border:0px solid #cccccc; vertical-align:middle;" />';
	} else {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_dharma.png" width="70" style="border:0px solid #cccccc; vertical-align:middle;" />';
	}


	$html_ret .= '<div style="position:absolute; top:25px; width:100%; height:150px; border:0px solid red;">';
	$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
	$html_ret .= '<tr>';
	$html_ret .= '<td rowspan="3" valign="middle" width="100" align="center">';
	$html_ret .= '<div style="display:block;width:100%;height:150px;margin-left:2px;">';
	$html_ret .= '<span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
	$html_ret .= $photo_html;
	$html_ret .= '</div>';
	$html_ret .= '</td>';
	$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

	if( cTYPE::iifcn($info["name"]) )
		$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$info["name"] . '</span>';
	else
		$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["name"] . '</span>';

	$html_ret .= '</td>';
	$html_ret .= '</tr>';


	$title 	= '';
	if($info["volunteer"]=="1") $title = cTYPE::gstr($words["tag.volunteer"]);
	if($info["leader"]=="1") 	$title = cTYPE::gstr($words["tag.leader"]);
	if($title != "") 			$title = '<span style="color:#A01E04;">' . $title . '</span>';
	
	if( $info["dharma_name"] != "" && $info["dharma_name"]!=$info["name"]) {
		if( $admin_user["lang"] != "en" ) {
			$html_ret .= '<tr>';
			$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';
			
			if( cTYPE::iifcn($info["dharma_name"]) )
				$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_name"] . ' ' . $title . '</span>';
			else
				$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_name"] . ' ' . $title . '</span>';

			$html_ret .= '</td>';
			$html_ret .= '</tr>';
		} else {
			if( $title != "" ) {
				$html_ret .= '<tr>';
				$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

				if( cTYPE::iifcn($title) )
					$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$title . '</span>';
				else
					$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $title . '</span>';

				$html_ret .= '</td>';
				$html_ret .= '</tr>';
			} else {
				$html_ret .= '<tr>';
				$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

				if( cTYPE::iifcn($info["dharma_name"]) )
					$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$info["dharma_name"] . '</span>';
				else
					$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_name"] . '</span>';

				$html_ret .= '</td>';
				$html_ret .= '</tr>';
			}
		}
	} else {
		if( $title != "" ) {
			$html_ret .= '<tr>';
			$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';

			if( cTYPE::iifcn($title) )
				$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' .$title . '</span>';
			else
				$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $title . '</span>';

			$html_ret .= '</td>';
			$html_ret .= '</tr>';
		}
	}

	if( $info["group_no"] != "") {
		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';
		$html_ret .= '<div align="center" style="display:block;width:36px;height:36px;border:1px solid #A01E04;border-radius:18px 18px 18px 18px;">';
		$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= '<span style="font-size:22px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
		$html_ret .= '</div>';
		//$html_ret .= '<span style="font-size:32px;color:#A01E04;font-family:隶书;">' . cTYPE::gstr($words["none.di"]) . ' ' . $info["group_no"] . ' ' .cTYPE::gstr($words["none.zu"]) . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '</tr>';
	}

	$html_ret .= '</table>';
	$html_ret .= '</div>';

	$html_ret .= '<div style="position:absolute; top:180px; width:100%; text-align:center; font-family:Arial; font-size:14px; color:#A01E04;">' . $info["event_date"] . '</div>';

	$html_ret .= '</div>';
	return $html_ret;
}
?>
