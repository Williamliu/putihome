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
		  $html = '<center><div style="position:relative; display:block; width:750px; margin:0px; padding:0px;">';
		  $info["etitle"] 		= $etitle;
		  $info["edate"] 		= $edate;
		  $info["name"] 		= "";
		  $info["dharma_name"] 	= "";
		  $info["dharma_pinyin"] 	= "";
		  $info["shelf"] 		= ""; 		  	
		  $info["group_no"] 	= "";
		  $info["leader"] 		= 0;
		  $info["volunteer"] 	= 0;
		  
		  for($k = 0 ; $k < 6; $k++ ) {
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
	$event_title 	= $row_class["event_title"];
	$class_title 	= $row_class["class_title"];
	$event_date 	= $row_class["end_date"]>=0?date("Y-m-d",$row_class["end_date"]):date("Y-m-d",$row_class["start_date"]);
	$event_year 	= $row_class["start_date"]>=0?date("Y",$row_class["start_date"]):date("Y",$row_class["end_date"]);
	$photo_req		= $row_class["photo"]>0?true:false;



	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd  
				FROM puti_idd aaa0 
				INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
				ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	if($_REQUEST["aflag"]=="1") {
		  $query = "SELECT a.id as enroll_id, a.leader, a.volunteer, a.confirm, a.shelf, b.id, b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.dharma_pinyin, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
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
		  $query = "SELECT a.id as enroll_id, a.leader, a.volunteer, a.confirm, a.shelf, b.id, b.id as member_id, b.first_name, b.last_name, b.dharma_name, b.dharma_pinyin, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
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
			if($cnt==0)  $html .= '<center><div style="position:relative; display:block; width:750px; border:0px solid black; margin:0px; padding:0px;">';
		} else {
			if($cnt==0)  $html .= '<center><div style="position:relative; display:block; width:750px; border:0px solid black; margin:0px; padding:0px; page-break-before:always;">';
		}
		$info["member_id"] 		= $row["member_id"];	
		$info["etitle"] 		= $etitle;
		$info["edate"] 			= $edate;

		$names					= array();
		$names["first_name"] 	= $row["first_name"];
		$names["last_name"] 	= $row["last_name"];

		if($_REQUEST["lfname"]=="1") 
			$info["name"]			= trim(cTYPE::gstr(cTYPE::lfname1($names,13)));
		else 
			$info["name"]			= trim(cTYPE::gstr(cTYPE::fullfirst($names,13)));

		$info["dharma_name"] 	= trim(cTYPE::gstr($row["dharma_name"]));	
		$info["dharma_pinyin"] 	= trim(cTYPE::gstr($row["dharma_pinyin"]));	
		$info["group_no"] 		= $row["group_no"]>0?$row["group_no"]:"";
		$info["shelf"] 			= cTYPE::shelfSN($row["shelf"],$CFG["max_shoes_rack"]);
		$info["leader"] 		= $row["leader"];	
		$info["volunteer"] 		= $row["volunteer"];	
		$info["photo"]			= file_exists($CFG["upload_path"] . "/small/" . $row["member_id"] . ".jpg")?"Y":"";

		$i = $cnt % 2;
		$j = floor($cnt / 2);
	    if( $photo_req ) $html .= item_photo($i, $j, $info); else $html .= item($i, $j, $info);
		$cnt++;
		if($cnt >= 6) {
			$html .= '</div></center><br>';
			$cnt=0;
		}
	}
	if($cnt > 0) {
		for($k = $cnt ; $k < 6; $k++ ) {
			  $info["member_id"] 	= -1;	
			  $info["etitle"] 		= $etitle;
			  $info["edate"] 		= $edate;
			  $info["name"] 		= "";
			  $info["dharma_name"] 	= "";	
			  $info["dharma_pinyin"] = "";	
			  $info["group_no"] 	= "";
			  $info["shelf"] 		= ""; 
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
	global $words;
	$html_ret .= '<div style="position:relative; float:left; border:0px solid red; display:inline-block; margin:1px; text-align:left; width:370px; height:320px; overflow:hidden;">';
	$html_ret .= '<img src="/theme/blue/image/background/scard.jpg" style="position:absolute; top:0px; left:0px;" width="367" height="320" />';
	//$html_ret .= '<div style="position:absolute; top:55px; width:100%; text-align:center; font-family:Arial; font-size:26px;">' . cTYPE::tobig5($words["student id"]) . '</div>';
	$html_ret .= '<div style="position:absolute; top:55px; width:100%; text-align:center; font-family:Arial; font-size:22px; color:#A01E04; overflow:hidden; white-space:nowrap;">' . $info["etitle"] . '</div>';

	$html_ret .= '<div style="position:absolute; top:85px; padding:0px; width:100%; height:200px; border:0px solid red; text-align:left; font-family:Arial; overflow:hidden;">';
	$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';

	$html_ret .= '<tr>';
	
	// specail needs
	if($info["dharma_name"]!= "") {
		$info["name"] = $info["dharma_name"] . ($info["dharma_pinyin"]!=""?"<br>":"") . $info["dharma_pinyin"];
		$info["dharma_name"] = "";
	}
	// end of special needs
	if( cTYPE::iifcn($words["name"]) ) {
		$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;"><span style="font-size:24px;font-family:隶书;">' . $words["name"] . '</span></td>';
		$html_ret .= '<td valign="middle" align="center"><span style="font-size:46px; font-weight:bold; margin-left:-30px;font-family:隶书;">' . $info["name"] . '&nbsp;</span></td>';
	} else {
		$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;"><span style="font-size:18px;font-family:隶书;">' . $words["name"] . '</span></td>';
		$html_ret .= '<td valign="middle" align="center"><span style="font-size:46px; font-weight:bold; margin-left:-30px;font-family:隶书;">' . $info["name"] . '&nbsp;</span></td>';
	}

	$html_ret .= '</tr>';

	if( $info["dharma_name"]!= ""  && $info["dharma_name"]!=$info["name"]) {
		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;">&nbsp;</td>';
		if( cTYPE::iifcn($info["dharma_name"]) ) {
			$html_ret .= '<td valign="middle" align="center">';
			$html_ret .= '<span style="font-size:40px; margin-left:-30px;font-family:隶书;">' . $info["dharma_name"] . '</span>&nbsp;';
			if( $info["dharma_pinyin"]!= "")
				$html_ret .= '<span style="font-size:28px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '&nbsp;</span>';
			$html_ret .= '</td>';
		} else {
			$html_ret .= '<td valign="middle" align="center">';
			$html_ret .= '<span style="font-size:40px; margin-left:-30px;font-family:隶书;">' . $info["dharma_name"] . '</span>&nbsp;';
			if( $info["dharma_pinyin"]!= "")
				$html_ret .= '<span style="font-size:28px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '&nbsp;</span>';
			$html_ret .= '</td>';
		}
		$html_ret .= '</tr>';
	}
	
	if($info["volunteer"]=="1") $title = $words["tag.volunteer"];
	if($info["leader"]=="1") 	$title = $words["tag.leader"];
	if( $title != "" ) {
		$html_ret .= '<tr>';
		if( cTYPE::iifcn($words["tag.title"]) ) {
			$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;"><span style="font-size:24px;font-family:隶书;">' . cTYPE::gstr($words["tag.title"]) . '</span></td>';
			$html_ret .= '<td valign="middle" align="center"><span style="font-size:36px; color:#A01E04; margin-left:-30px;font-family:隶书;">' . cTYPE::gstr($title) . '&nbsp;</span></td>';
		} else {
			$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;"><span style="font-size:18px;font-family:隶书;">' . cTYPE::gstr($words["tag.title"]) . '</span></td>';
			$html_ret .= '<td valign="middle" align="center"><span style="font-size:36px; color:#A01E04; margin-left:-30px;font-family:隶书;">' . cTYPE::gstr($title) . '&nbsp;</span></td>';
		}
		$html_ret .= '</tr>';
	}
		
	$html_ret .= '<tr>';
	if(  cTYPE::iifcn($words["group"]) ) {
		$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;"><span style="font-size:24px;font-family:隶书;">' . cTYPE::gstr($words["group"]) . '</span></td>';
	} else {
		$html_ret .= '<td valign="middle" align="right" style="width:60px;white-space:nowrap;"><span style="font-size:18px;font-family:隶书;">' . cTYPE::gstr($words["group"]) . '</span></td>';
	}
	$html_ret .= '<td valign="middle" align="center">';
	$html_ret .= '<div align="center" style="display:inline-block;width:36px;height:36px;border:1px solid #A01E04;border-radius:18px 18px 18px 18px; margin-left:-60px;">';
	$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
	$html_ret .= '<span style="font-size:22px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
	$html_ret .= '</div>';

	if( $_REQUEST["shoes"] == "1" ) 
		$html_ret .= '<span style="margin-left:20px; font-size:20px; font-weight:bold;color:#A01E04;vertical-align:middle;">' . cTYPE::gstr($words["shoes.shelf"]) . ": " . $info["shelf"] . '</span>';

	$html_ret .= '</td>';
	$html_ret .= '</tr>';

	$html_ret .= '</table>';
	$html_ret .= '</div>';

	$html_ret .= '<div style="position:absolute; top:295px; width:100%; text-align:center; font-family:Arial; font-size:16px; color:#A01E04;">' . $info["edate"] . '</div>';

	$html_ret .= '</div>';
	return $html_ret;
}


function item_photo($i, $j, $info) {
	global $CFG;
	global $words;
	$html_ret .= '<div style="position:relative; float:left; border:0px solid red; display:inline-block; margin:1px; text-align:left; width:370px; height:320px; overflow:hidden;">';
	$html_ret .= '<img src="/theme/blue/image/background/scard.jpg" style="position:absolute; top:0px; left:0px;" width="367" height="320" />';
	$html_ret .= '<div style="position:absolute; top:55px; width:100%; text-align:center; font-family:Arial; font-size:22px; color:#A01E04; overflow:hidden; white-space:nowrap;">' . $info["etitle"] . '</div>';

	if( $info["photo"]=="Y") {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/lwhUpload_image.php?ts=' . time() . '&size=small&img_id=' .  $info["member_id"] . '" width="120" height="160" style="border:0px solid #cccccc; vertical-align:middle;" />';
	} else {
		$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_dharma.png" width="100" style="border:0px solid #cccccc; vertical-align:middle;" />';
	}

    if($info["leader"]=="1" || $info["volunteer"]=="1") {
		if($info["volunteer"]=="1") $title = trim($words["tag.volunteer"]);
		if($info["leader"]=="1") $title = trim($words["tag.leader"]);
		
		$html_ret .= '<div style="position:absolute; top:85px; padding-left:10px; width:100%; text-align:left; font-family:Arial; overflow:hidden;">';
		$html_ret .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';

		$html_ret .= '<tr>';
		$html_ret .= '<td rowspan="4" valign="middle" width="120"><div style="display:block;width:100%;height:200px;"><span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= $photo_html;
		$html_ret .= '</div></td>';
		$html_ret .= '<td valign="middle" align="center" style="padding-left:-5px;">';

		// specail needs
		if($info["dharma_name"]!= "") {
			$info["name"] = $info["dharma_name"] . ($info["dharma_pinyin"]!=""?"<br>":"") . $info["dharma_pinyin"];
			$info["dharma_name"] = "";
		}
		// end of special needs

		if( cTYPE::iifcn($info["name"]) ) {
			$html_ret .= '<span style="font-size:50px;font-weight:bold;font-family:隶书;">' . $info["name"] . '&nbsp;</span>';
		} else {
			$html_ret .= '<span style="font-size:44px;font-weight:bold;font-family:隶书;">' . $info["name"] . '&nbsp;</span>';
		}
		
		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		if( $info["dharma_name"] != ""  && $info["dharma_name"]!=$info["name"] ) {
			$html_ret .= '<tr>';
			$html_ret .= '<td valign="middle" align="center" style="padding-left:-5px;">';
			
			if( cTYPE::iifcn($info["dharma_name"]) ) {
				if( $info["dharma_pinyin"]== "") {
					$html_ret .= '<span style="font-size:52px;font-family:隶书;">' . $info["dharma_name"] . '&nbsp;</span>';
				} else { 
					$html_ret .= '<span style="font-size:36px;font-family:隶书;">' . $info["dharma_name"] . '</span>&nbsp;';
					$html_ret .= '<span style="font-size:24px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '</span>';
				}
			} else {
				if( $info["dharma_pinyin"]== "") {
					$html_ret .= '<span style="font-size:44px;font-family:隶书;">' . $info["dharma_name"] . '&nbsp;</span>';
				} else { 
					$html_ret .= '<span style="font-size:36px;font-family:隶书;">' . $info["dharma_name"] . '</span>&nbsp;';
					$html_ret .= '<span style="font-size:24px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '</span>';
				}
			}
			
			$html_ret .= '</td>';
			$html_ret .= '</tr>';
		}

		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="center" style="padding-left:-5px;">';
		if( cTYPE::iifcn($title) ) {
			$html_ret .= '<span style="font-size:40px; color:#A01E04;font-family:隶书;">' . cTYPE::gstr($title) . '&nbsp;</span>';
		} else {
			$html_ret .= '<span style="font-size:36px; color:#A01E04;font-family:隶书;">' . cTYPE::gstr($title) . '&nbsp;</span>';
		}
		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="center">';
		$html_ret .= '<div align="center" style="display:inline-block;width:36px;height:36px;border:1px solid #A01E04;border-radius:18px 18px 18px 18px; margin-left:-20px;">';
		$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= '<span style="font-size:22px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
		$html_ret .= '</div>';

		if( $_REQUEST["shoes"] == "1" ) 
			$html_ret .= '<span style="margin-left:20px; font-size:20px; font-weight:bold;color:#A01E04;vertical-align:middle;">' . cTYPE::gstr($words["shoes.shelf"]) . ": " . $info["shelf"] . '</span>';

		//$html_ret .= '<span style="font-size:30px;">' . cTYPE::gstr($words["none.di"]) . ' ' . ($info["group_no"]>0?$info["group_no"]:"&nbsp;&nbsp;") . ' ' .cTYPE::gstr($words["none.zu"]) . '&nbsp;</span>';
		
		$html_ret .= '</td>';
		$html_ret .= '</tr>';
		$html_ret .= '</table>';
		$html_ret .= '</div>';
		$html_ret .= '<div style="position:absolute; top:295px; width:100%; text-align:center; font-family:Arial; font-size:16px; color:#A01E04;">' . $info["edate"] . '</div>';
	} else {
		$html_ret .= '<div style="position:absolute; top:85px; padding-left:10px; width:100%; text-align:left; font-family:Arial; overflow:hidden;">';
		$html_ret .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';

		$html_ret .= '<tr>';
		$html_ret .= '<td rowspan="3" valign="middle" width="120"><div style="display:block;width:100%;height:200px;"><span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= $photo_html;
		$html_ret .= '</div></td>';
		$html_ret .= '<td valign="middle" align="center" style="padding-left:-5px;">';

		// specail needs
		if($info["dharma_name"]!= "") {
			$info["name"] = $info["dharma_name"] . ($info["dharma_pinyin"]!=""?"<br>":"") . $info["dharma_pinyin"];
			$info["dharma_name"] = "";
		}
		// end of special needs

		if( cTYPE::iifcn($info["name"]) ) {
			$html_ret .= '<span style="font-size:52px;font-family:隶书;">' . $info["name"] . '&nbsp;</span>';
		} else {
			$html_ret .= '<span style="font-size:46px;font-family:隶书;">' . $info["name"] . '&nbsp;</span>';
		}

		$html_ret .= '</td>';
		$html_ret .= '</tr>';
		
		if( $info["dharma_name"] != ""  && $info["dharma_name"]!=$info["name"]) {
			$html_ret .= '<tr>';
			$html_ret .= '<td valign="middle" align="center" style="padding-left:-5px;">';

			if( cTYPE::iifcn($info["dharma_name"]) ) {
				if( $info["dharma_pinyin"]== "") {
					$html_ret .= '<span style="font-size:52px;font-family:隶书;">' . $info["dharma_name"] . '&nbsp;</span>';
				} else { 
					$html_ret .= '<span style="font-size:36px;font-family:隶书;">' . $info["dharma_name"] . '</span>&nbsp;';
					$html_ret .= '<span style="font-size:24px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '</span>';
				}
			} else {
				if( $info["dharma_pinyin"]== "") {
					$html_ret .= '<span style="font-size:44px;font-family:隶书;">' . $info["dharma_name"] . '&nbsp;</span>';
				} else { 
					$html_ret .= '<span style="font-size:36px;font-family:隶书;">' . $info["dharma_name"] . '</span>&nbsp;';
					$html_ret .= '<span style="font-size:24px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '</span>';
				}
			}

			$html_ret .= '</td>';
			$html_ret .= '</tr>';
		}
		
		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="center">';
		$html_ret .= '<div align="center" style="display:inline-block;width:36px;height:36px;border:1px solid #A01E04;border-radius:18px 18px 18px 18px; margin-left:-20px;">';
		$html_ret .= '<span style="display:inline-block;width:1px;height:100%;vertical-align:middle;"></span>';
		$html_ret .= '<span style="font-size:22px;font-weight:bold;color:#A01E04;vertical-align:middle;">' . $info["group_no"] . '</span>';
		$html_ret .= '</div>';

		if( $_REQUEST["shoes"] == "1" ) 
			$html_ret .= '<span style="margin-left:20px; font-size:20px; font-weight:bold;color:#A01E04;vertical-align:middle;">' . cTYPE::gstr($words["shoes.shelf"]) . ": " . $info["shelf"] . '</span>';

		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		$html_ret .= '</table>';
		$html_ret .= '</div>';
		$html_ret .= '<div style="position:absolute; top:295px; width:100%; text-align:center; font-family:Arial; font-size:16px; color:#A01E04;">' . $info["edate"] . '</div>';
	}
	$html_ret .= '</div>';

	return $html_ret;
}
?>
