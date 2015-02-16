<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", "length":11, "id": "event_id", "name":"Select Event", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query = "SELECT c.title, c.start_date, c.end_date 
					  FROM event_calendar c 
					  WHERE  c.id = '" . $_REQUEST["event_id"] . "'";
	$result = $db->query($query);
	$row 	= $db->fetch($result);	
  	$etitle	= cTYPE::gstr($row["title"]);
	$edate	= date("Y, M jS", $row["start_date"]);
	$edate .= $row["start_date"]!=$row["end_date"]?" ~ " . date("M jS", $row["end_date"]):"";
	
	if( $_REQUEST["aflag"]=="2" ) {
		  $html = '<br><center><div style="position:relative; display:block; width:650px; border:0px solid black; margin:0px; padding:0px;">';
		  $info["etitle"] 		= $etitle;
		  $info["edate"] 		= $edate;
		  $info["name"] 		= "";
		  $info["dharma_name"] 	= "";	
		  $info["group_no"] 	= "";
		  $info["leader"] 		= 0;
		  $info["volunteer"] 	= 0;
		  $info["photo"]		= "";	
		  
		  for($k = 0 ; $k < 18; $k++ ) {
				$i = $k % 2;
				$j = floor($k / 2);
				$html .= item($i, $j, $info);
		  }
		  $html .= '</div></center>';
		  $response["data"] 			= $html;
		  $response["errorCode"] 		= 0;
		  $response["errorMessage"] 	= "";
		  echo json_encode($response);
		  exit;
	}
	
	$order_str = "ORDER BY a.group_no, a.leader DESC, a.volunteer DESC,  b.last_name, b.first_name";
	/* 
	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"]=="created_time"?"a.created_time":$_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} 
	*/
	
	$crrr = "";
	$grp_id = trim($_REQUEST["group_id"]);
	if( $grp_id != "" ) {
		$crrr .= ($crrr==""?"":" AND ") . "a.group_no = '" . $grp_id . "'"; 
	}

	$enr_id = trim($_REQUEST["enroll_id"]);
	if( $enr_id != "" ) {
		$crrr .= ($crrr==""?"":" AND ") . "a.id = '" . $enr_id . "'"; 
	}

	/*******************************************/
	$sch_name = trim($_REQUEST["sch_name"]);
	if($sch_name != "") {
		$crrr .= ($crrr==""?"":" AND ") . 
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

	$sch_phone = trim($_REQUEST["sch_phone"]);
	if($sch_phone != "") {
		$crrr .= ($crrr==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}

	$sch_email = trim($_REQUEST["sch_email"]);
	if($sch_email != "") {
		$crrr .= ($crrr==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($_REQUEST["sch_gender"]);
	if($sch_gender != "") {
		$crrr .= ($crrr==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_online = trim($_REQUEST["sch_online"]);
	if($sch_online != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.online = '" . $sch_online . "'";
	}

	$sch_attend = trim($_REQUEST["sch_attend"]);
	if($sch_attend != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.attend >= '" . ($sch_attend/100) . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$crrr .= ($criteria==""?"":" AND ") . "b.level = '" . $sch_level . "'";
	}

	$sch_onsite = trim($_REQUEST["sch_onsite"]);
	if($sch_onsite != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.onsite = '" . $sch_onsite . "'";
	}
	
	$sch_trial = trim($_REQUEST["sch_trial"]);
	if($sch_trial != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.trial = '" . $sch_trial . "'";
	}

	$sch_lang = trim($_REQUEST["sch_lang"]);
	if($sch_lang != "") {
		$crrr .= ($crrr==""?"":" AND ") . "b.language = '" . $sch_lang . "'";
	}

	$sch_group = trim($_REQUEST["sch_group"]);
	if($sch_group != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.group_no = '" . cTYPE::trans($sch_group) . "'";
	}

	$sch_date = trim($_REQUEST["sch_date"]);
	if($sch_date != "") {
		$sd = cTYPE::datetoint($sch_date);
		$ed = $sd + ( 3600 * 24 - 1);
		$crrr .= ($crrr==""?"":" AND ") . "a.created_time BETWEEN '" . $sd . "' AND '" . $ed . "'";
	}

	$sch_city = trim($_REQUEST["sch_city"]);
	if($sch_city != "") {
		$crrr .= ($crrr==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}


	$sch_idd = trim($_REQUEST["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$crrr .= ($crrr==""?"":" AND ") . "b.id = '" . $mem_id . "'";
		} else {
				$crrr .= ($crrr==""?"":" AND ") . "b.id = '-1'";
		}
	}
	/*****************************************************************/
	$crrr = ($crrr==""?"":" AND ") . $crrr; 
	

	$query_class = "SELECT a.start_date, a.end_date, a.title as event_title, b.title as class_title, b.cert_prefix, b.photo 
							FROM 	event_calendar a 
							INNER JOIN puti_class b ON (a.class_id = b.id)
							WHERE a.id='" . $_REQUEST["event_id"] . "'";  
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$event_title 	= cTYPE::gstr($row_class["event_title"]);
	$class_title 	= cTYPE::gstr($row_class["class_title"]);
	$event_date 	= $row_class["end_date"]>=0?date("Y-m-d",$row_class["end_date"]):date("Y-m-d",$row_class["start_date"]);
	$event_year 	= $row_class["start_date"]>=0?date("Y",$row_class["start_date"]):date("Y",$row_class["end_date"]);
	$photo_req		= $row_class["photo"]>0?true:false;



	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd  
				FROM puti_idd aaa0 
				INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
				ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	if($_REQUEST["aflag"]=="1") {
		  $query = "SELECT a.id as enroll_id, a.leader, a.volunteer, a.confirm, b.id, b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
						   c.title, c.start_date, c.end_date 
							  FROM event_calendar_enroll a 
							  INNER JOIN puti_members b ON (a.member_id = b.id)  
							  LEFT JOIN ($query_idd) aa0 ON (b.id = aa0.member_id)
							  INNER JOIN event_calendar c ON (a.event_id = c.id) 
							  WHERE  a.deleted <> 1 AND 
							  b.deleted <> 1 AND 
							  c.deleted <> 1 AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' 
							  $crrr  
							  $order_str";
	} else {
		  $query = "SELECT a.id as enroll_id, a.leader, a.volunteer, a.confirm, b.id, b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
						   c.title, c.start_date, c.end_date 
							  FROM event_calendar_enroll a 
							  INNER JOIN puti_members b ON (a.member_id = b.id)  
							  LEFT JOIN ($query_idd) aa0 ON (b.id = aa0.member_id)
							  INNER JOIN event_calendar c ON (a.event_id = c.id) 
							  WHERE  a.deleted <> 1 AND 
							  b.deleted <> 1 AND 
							  c.deleted <> 1 AND 
							  a.event_id = '" . $_REQUEST["event_id"] . "' 
							  $crrr  
							  $order_str";
	}
	$result = $db->query($query);
	$html = '';
	$cnt = 0;
	$first = true;
	while( $row = $db->fetch($result)) {
		if( $first ) {
			$first = false;
			if($cnt==0)  $html .= '<br><center><div style="position:relative; display:block; width:650px; border:0px solid black; margin:0px; padding:0px;">';
		} else {
			if($cnt==0)  $html .= '<br><center><div style="position:relative; display:block; width:650px; border:0px solid black; margin:0px; padding:0px; page-break-before:always;">';
		}

		$info["member_id"] 		= $row["member_id"];	
		$info["etitle"] 		= $etitle;
		$info["edate"] 			= $edate;

		$names					= array();
		$names["first_name"] 	= $row["first_name"];
		$names["last_name"] 	= $row["last_name"];

		if($_REQUEST["lfname"]=="1") 
			$info["name"] 		= trim(cTYPE::gstr(cTYPE::lfname1($names,11)));
		else 
			$info["name"] 		= trim(cTYPE::gstr(cTYPE::cert_cname($names,11)));

		$info["dharma_name"] 	= trim(cTYPE::gstr($row["dharma_name"]));	
		$info["group_no"] 		= $row["group_no"]>0?$row["group_no"]:"";
		$info["leader"] 		= $row["leader"];	
		$info["volunteer"] 		= $row["volunteer"];	

		$info["photo"]			= file_exists($CFG["upload_path"] . "/small/" . $row["member_id"] . ".jpg")?"Y":"";

		$i = $cnt % 2;
		$j = floor($cnt / 2);
	    if( $photo_req ) $html .= item_photo($i, $j, $info); else $html .= item($i, $j, $info);
		$cnt++;
		if($cnt >= 18) {
			$html .= '</div></center><br>';
			$cnt=0;
		}
	}
	if($cnt > 0) {
		for($k = $cnt ; $k < 18; $k++ ) {
			  $info["member_id"] 	= -1;	
			  $info["etitle"] 		= $etitle;
			  $info["edate"] 		= $edate;
			  $info["name"] 		= "";
			  $info["dharma_name"] 	= "";	
			  $info["group_no"] 	= "";
			  $info["leader"] 		= 0;	
			  $info["volunteer"] 	= 0;
			  $info["photo"]		= "";	
			  $i = $k % 2;
			  $j = floor($k / 2);
			  if( $photo_req ) $html .= item_photo($i, $j, $info); else $html .= item($i, $j, $info);
		}
	  	$html .= '</div></center><br>';
	}
	
	
	//$db->close();
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

	$html_ret .= '<div style="position:relative; float:left; border:1px dotted #9E0E94; display:inline-block; margin:1px; text-align:left; width:310px; height:100px; overflow:hidden;">';
	//$html_ret .= '<img src="/theme/blue/image/background/scard.jpg" style="position:absolute; top:0px; left:0px;" width="367" height="320" />';
	if( "N"=="Y") {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/lwhUpload_image.php?ts=' . time() . '&size=small&img_id=' .  $info["member_id"] . '" height="96" style="border:0px solid #cccccc; vertical-align:middle; margin-top:-20px;" />';
	} else {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_dharma1.png" height="80" style="border:0px solid #cccccc; vertical-align:middle; margin-top:-5px;" />';
	}
	
	if( cTYPE::iifcn($info["etitle"]) )
		$html_ret .= '<div style="display:block;width:100%;position:absolute;top:5px;text-align:center;font-family:Arial;font-size:20px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;z-index:999;font-family:隶书;">' . $info["etitle"] . '</div>';
	else 
		$html_ret .= '<div style="display:block;width:100%;position:absolute;top:5px;text-align:center;font-family:Arial;font-size:16px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;z-index:999;font-family:隶书;">' . $info["etitle"] . '</div>';
	
	$html_ret .= '<div style="position:absolute; top:10px; width:100%; height:100px; border:0px solid red;">';
	$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';

	$html_ret .= '<tr>';
	$html_ret .= '<td valign="middle" width="100" align="center">';
	$html_ret .= '<div style="display:block;width:100%;height:100px;"><span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
	$html_ret .= $photo_html;
	$html_ret .= '</div>';
	$html_ret .= '</td>';

	$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';
	if( $info["dharma_name"] != "" ) $name = $info["dharma_name"]; else $name = $info["name"];
	if( cTYPE::iifcn($name) )
		$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $name . '</span>';
	else
		$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $name . '</span>';
	
	$html_ret .= '</td>';
	
	if($info["group_no"]!="") {
		$title 	= '';
		if($info["volunteer"]=="1") $title = cTYPE::gstr($words["tag.volunteer"]);
		if($info["leader"]=="1") 	$title = cTYPE::gstr($words["tag.leader"]);
		if($title != "" && $admin_user["lang"] != "en") {
			$title = '<span style="color:#A01E04;font-size:26px;font-weight:bold;font-family:隶书;">' . $title . '</span>';
		} else {
			$title = '';
		}
		
		$html_ret .= '<td valign="middle" align="center" width="40" style="white-space:nowrap;">';
		if($title != '') $html_ret .= $title;
		$html_ret .= '<div align="center" style="display:block;width:32px;height:32px;border:1px solid #A01E04;border-radius:16px 16px 16px 16px;">';
		//$html_ret .= '<img style="float:left;position:absolute;display:inline-block;" src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_group.png" />';
		$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= '<span style="font-size:24px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
		$html_ret .= '</div>';
		$html_ret .= '</td>';
	}
	$html_ret .= '</tr>';

	$html_ret .= '</table>';
	$html_ret .= '</div>';

	$html_ret .= '</div>';
	return $html_ret;
}

function item_photo($i, $j, $info) {
	global $CFG;
	global $words;
	global $admin_user;

	$html_ret .= '<div style="position:relative; float:left; border:1px dotted #9E0E94; display:inline-block; margin:1px; text-align:left; width:310px; height:100px; overflow:hidden;">';
	//$html_ret .= '<img src="/theme/blue/image/background/scard.jpg" style="position:absolute; top:0px; left:0px;" width="367" height="320" />';
	if( $info["photo"]=="Y") {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/lwhUpload_image.php?ts=' . time() . '&size=small&img_id=' .  $info["member_id"] . '" height="96" style="border:0px solid #cccccc; vertical-align:middle; margin-top:-20px;" />';
	} else {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_dharma1.png" height="80" style="border:0px solid #cccccc; vertical-align:middle; margin-top:-5px;" />';
	}
	
	if( cTYPE::iifcn($info["etitle"]) )
		$html_ret .= '<div style="display:block;width:100%;position:absolute;top:5px;text-align:center;font-family:Arial;font-size:20px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;z-index:999;font-family:隶书;">' . $info["etitle"] . '</div>';
	else 
		$html_ret .= '<div style="display:block;width:100%;position:absolute;top:5px;text-align:center;font-family:Arial;font-size:16px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;z-index:999;font-family:隶书;">' . $info["etitle"] . '</div>';
	
	$html_ret .= '<div style="position:absolute; top:10px; width:100%; height:100px; border:0px solid red;">';
	$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';

	$html_ret .= '<tr>';
	$html_ret .= '<td valign="middle" width="100" align="center">';
	$html_ret .= '<div style="display:block;width:100%;height:100px;"><span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
	$html_ret .= $photo_html;
	$html_ret .= '</div>';
	$html_ret .= '</td>';

	$html_ret .= '<td valign="middle" align="center" style="padding-left:-20px;">';
	if( $info["dharma_name"] != "" ) $name = $info["dharma_name"]; else $name = $info["name"];
	if( cTYPE::iifcn($name) )
		$html_ret .= '<span style="font-size:48px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $name . '</span>';
	else
		$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $name . '</span>';
	
	$html_ret .= '</td>';
	
	if($info["group_no"]!="") {
		$title 	= '';
		if($info["volunteer"]=="1") $title = cTYPE::gstr($words["tag.volunteer"]);
		if($info["leader"]=="1") 	$title = cTYPE::gstr($words["tag.leader"]);
		if($title != "" && $admin_user["lang"] != "en") {
			$title = '<span style="color:#A01E04;font-size:26px;font-weight:bold;font-family:隶书;">' . $title . '</span>';
		} else {
			$title = '';
		}
		
		$html_ret .= '<td valign="middle" align="center" width="40" style="white-space:nowrap;">';
		if($title != '') $html_ret .= $title;
		$html_ret .= '<div align="center" style="display:block;width:32px;height:32px;border:1px solid #A01E04;border-radius:16px 16px 16px 16px;">';
		//$html_ret .= '<img style="float:left;position:absolute;display:inline-block;" src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_group.png" />';
		$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= '<span style="font-size:24px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
		$html_ret .= '</div>';
		$html_ret .= '</td>';
	}
	$html_ret .= '</tr>';

	$html_ret .= '</table>';
	$html_ret .= '</div>';

	$html_ret .= '</div>';
	return $html_ret;
}
?>
