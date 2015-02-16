<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"];
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} else {
		$order_str = "ORDER BY apply_date ASC";
	}


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
							temp_dharma_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
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
		$criteria .= ($criteria==""?"":" AND ") . "apply_date BETWEEN '" . $sd . "' AND '" . $ed . "'";
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

    $puti_site_cn = cTYPE::gstr($row_sites["school_cn"]);
    $puti_site_en = cTYPE::gstr($row_sites["school_en"]);
    if($puti_site_cn == $puti_site_en) $puti_site_en = "";

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
	$dharma_time 	= $row_class["start_date"];
	$photo_req		= $row_class["photo"]>0?true:false;
	
	
	$info 					= array();
	$info["event_title"] 	= trim(cTYPE::gstr($event_title));
	$info["event_date"] 	= trim(cTYPE::gstr($event_date));
	
	
	$query_idd = "SELECT aaa0.member_id, aaa0.idd
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(created_time) as mtime FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.created_time = aaa1.mtime)";

	$query_base = "SELECT a.*,c.group_no, c.cert_no  
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
			if($cnt==0)  $html .= '<br><center><div style="position:relative; display:block; width:660px; border:0px solid black; margin:0px; padding:0px;">';
		} else {
			if($cnt==0)  $html .= '<br><center><div style="position:relative; display:block; width:660px; border:0px solid black; margin:0px; padding:0px; page-break-before:always;">';
		}

		$info["member_id"] 			= $row["id"];	

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$info["name"] 				= trim(cTYPE::gstr(cTYPE::cert_cname($names)));
		if( !cTYPE::iifcn($info["name"]) ) {
			$namesss = $row["first_name"] . ' ' . $row["last_name"];
			$namesss = strlen($namesss)>13?$row["first_name"] . '<br>' . $row["last_name"]:$namesss;
			$info["name"] = $namesss;
		}
		  
		$info["dharma_name"] 		= trim(cTYPE::gstr($row["temp_dharma_name"]?$row["temp_dharma_name"]:''));
		$info["dharma_pinyin"] 		= trim(cTYPE::gstr($row["temp_dharma_pinyin"]?$row["temp_dharma_pinyin"]:''));
		$info["dharma_date"] 		= date("Y", $dharma_time) . ' ' . $words["yy"] . ' ' . date("n", $dharma_time) . ' ' . $words["mm"] . ' ' . date("j", $dharma_time) . ' ' . $words["dd"];
		
		$i = $cnt % 2;
		$j = floor($cnt / 2);
	    $html .= item_photo($i, $j, $info);
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
			  $info["dharma_pinyin"] 	= "";	
			  $i = $k % 2;
			  $j = floor($k / 2);
			  $html .= item_photo($i, $j, $info); 
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

function item_photo($i, $j, $info) {
	global $CFG;
	global $words;
	global $admin_user;
	global $sites;
    global $puti_site_cn;
    global $puti_site_en;

	$html_ret .= '<div style="position:relative; float:left; border:1px dotted #666666; display:inline-block; margin:8px; text-align:left; width:305px; height:195px; overflow:hidden;">';
	$photo_html = '<img src="' . $CFG["http"] . $CFG["admin_domain"] . '/ajax/logo/logo_dharma.png" width="70" style="border:0px solid #cccccc; vertical-align:middle;" />';

	$html_ret .= '<div style="position:absolute; padding:0px; width:100%; height:190px; border:0px solid red;">';
	$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
	$html_ret .= '<tr>';

	$html_ret .= '<td rowspan="5" valign="middle" align="center" width="100"><div style="display:block;width:100%;height:170px;"><span style="display:inline-block;width:0px;height:100%;vertical-align:middle;"></span>';
	$html_ret .= $photo_html;
	$html_ret .= '</div></td>';
	
	$html_ret .= '<td valign="middle" align="left" style="padding-left:-10px;padding-top:10px;"><span style="font-size:24px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;letter-spacing:2px;font-family:隶书;">' . $words["jinputi"] . '</span></td>';
	$html_ret .= '</tr>';

	$html_ret .= '<tr>';
	$html_ret .= '<td valign="middle" align="center" style="padding-left:-10px;">';
		$html_ret .= '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';

		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="right" width="20" style="white-space:nowrap;">';
		$html_ret .= '<span style="font-size:14px;color:#A01E04;white-space:nowrap;font-family:隶书;">' . $words["name"] . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '<td valign="middle" align="left" style="padding-left:10px;">';
		$html_ret .= '<span style="font-size:22px;font-weight:bold;color:#A01E04;overflow:hidden;white-space:nowrap;font-family:隶书;letter-spacing:' . (cTYPE::iifcn($info["name"])?'5px;':'1px;') .  '">' . $info["name"] . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="right" width="20" style="white-space:nowrap;">';
		$html_ret .= '<span style="font-size:14px;color:#A01E04;white-space:nowrap;font-family:隶书;">' . $words["dharma"] . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '<td valign="middle" align="left" style="padding-left:10px;">';
		if( $info["dharma_pinyin"] != "" ) {
			$html_ret .= '<span style="font-size:32px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;letter-spacing:5px;font-family:隶书;">' . $info["dharma_name"] . '&nbsp;</span><br>';
			$html_ret .= '<span style="font-size:20px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;letter-spacing:1px;font-family:Arial Unicode MS;">' . $info["dharma_pinyin"] . '&nbsp;</span>';
		} else {
			$html_ret .= '<span style="font-size:36px;font-weight:bold;color:#224279;overflow:hidden;white-space:nowrap;letter-spacing:5px;font-family:隶书;">' . $info["dharma_name"] . '&nbsp;</span>';
		}
		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		$html_ret .= '<tr>';
		$html_ret .= '<td valign="middle" align="right" width="20" style="white-space:nowrap;">';
		$html_ret .= '<span style="font-size:14px;color:#A01E04;white-space:nowrap;font-family:隶书;">' . $words["time"] . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '<td valign="middle" align="left" style="padding-left:10px;">';
		$html_ret .= '<span style="font-size:14px;color:#A01E04;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $info["dharma_date"] . '</span>';
		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		$html_ret .= '<tr>';
		$html_ret .= '<td valign="top" align="right" width="20" style="white-space:nowrap;">';
		$html_ret .= '<span style="font-size:14px;color:#A01E04;white-space:nowrap;font-family:隶书;">' . $words["loc"] . '</span><br>';
		$html_ret .= '</td>';
		$html_ret .= '<td valign="top" align="left" style="padding-left:10px;">';
		
		//$site_loc  = "loc." . strtolower($sites[$admin_user["site"]]);
		//echo "site:" . $site_loc . "<br>";
		$html_ret .= '<span style="font-size:14px;color:#A01E04;overflow:hidden;white-space:nowrap;font-family:隶书;">' . $puti_site_cn . '</span><br>';
		$html_ret .= '</td>';
		$html_ret .= '</tr>';

		$html_ret .= '</table>';
		
	$html_ret .= '</td>';
	$html_ret .= '</tr>';

	$html_ret .= '</table>';
	$html_ret .= '</div>';

	$html_ret .= '</div>';
	return $html_ret;
}
?>
