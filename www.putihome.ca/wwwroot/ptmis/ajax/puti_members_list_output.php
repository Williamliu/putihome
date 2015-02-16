<?php 
session_start();
ini_set('default_charset','utf-8');
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	/*
	$type["pageNo"] 	= '{"type":"NUMBER", "length":11, "id": "pageNo", "name":"Page Number", "nullable":0}';
	$type["pageSize"] 	= '{"type":"NUMBER", "length":11, "id": "pageSize", "name":"Page Size", "nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	*/
	//header("Content-Type: application/vnd.ms-excel; name='excel'; charset='utf-8'");
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=UTF-8");
	header("Content-disposition:  attachment; filename=puti_members_list.xls");

	$orderBY = $_REQUEST["orderBY"]=="flname"?"last_name":$_REQUEST["orderBY"];
	$orderBY = $orderBY=="legal_name"?"legal_last":$orderBY;
	$orderSQ	= $_REQUEST["orderSQ"]==""?"DESC":$_REQUEST["orderSQ"];
	$order_str 	= " ORDER BY $orderBY $orderSQ";
	
	// condition here 
	$criteria = "";
	$criteria .= "site in " . $admin_user["sites"];

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
		$criteria .= ($criteria==""?"":" AND ") . "(replace(replace(phone,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%' OR replace(replace(cell,' ',''),'-','') like '%" . str_replace(array(" ","-"), array("",""), $sch_phone) . "%')";
	}

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_status = trim($con["sch_status"]);
	if($sch_status != "") {
		$criteria .= ($criteria==""?"":" AND ") . "status = '" . $sch_status . "'";
	}

	$sch_online = trim($con["sch_online"]);
	if($sch_online != "") {
		$criteria .= ($criteria==""?"":" AND ") . "online = '" . $sch_online . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$criteria .= ($criteria==""?"":" AND ") . "level = '" . $sch_level . "'";
	}

	$sch_plate = trim($con["sch_plate_no"]);
	if($sch_plate != "") {
		$criteria .= ($criteria==""?"":" AND ") . "replace(replace(replace(plate_no,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_plate) . "%'";
	}

	$sch_memid = trim($con["sch_memid"]);
	if($sch_memid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "id = '" . $sch_memid . "'";
	}

	$sch_address = trim($con["sch_address"]);
	if($sch_address != "") {
		$criteria .= ($criteria==""?"":" AND ") . "address like '%" . $sch_address . "%'";
	}

	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . $sch_city . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_state = trim($con["sch_state"]);
	if($sch_state != "") {
		$criteria .= ($criteria==""?"":" AND ") . "state like '%" . $sch_state . "%'";
	}

	$sch_country = trim($con["sch_country"]);
	if($sch_country != "") {
		$criteria .= ($criteria==""?"":" AND ") . "country like '%" . $sch_country . "%'";
	}

	$sch_postal = trim($con["sch_postal"]);
	if($sch_postal != "") {
		$criteria .= ($criteria==""?"":" AND ") . "postal like '%" . $sch_postal . "%'";
	}

	$sch_flag = trim($con["sch_email_flag"]);
	if($sch_flag != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email_flag = '" . cTYPE::trans($sch_flag) . "'";
	}
	
	$sch_lang = trim($con["sch_language"]);
	if($sch_lang != "") {
		$criteria .= ($criteria==""?"":" AND ") . "language = '" . cTYPE::trans($sch_lang) . "'";
	}

	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria

	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}

    $langs = $db->getTitles($admin_user["lang"], "vw_vol_language");
	$langs[0] = "";
	
	$query_base = "SELECT * 
						FROM puti_members  
						LEFT JOIN puti_members_others b ON ( puti_members.id = b.member_id ) 
            			WHERE  deleted <> 1 
						$criteria 
						$order_str";
	
	$result = $db->query( $query_base );
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
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["last name"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["first name"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["legal last"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["legal first"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["dharma name"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["dharma pinyin"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["alias"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["gender"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["short.lang"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["lang.ability"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["email"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["email subscription"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["phone"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["cell"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["age range"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["birth date"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["member enter date"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["contact by"] . '</td>';

	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["religion"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["member.degree"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["past_position"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["current_position"] . '</td>';

	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["address"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["city"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["state"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["country"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["postal"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["memo notes"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["emerg.contact"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["emerg.phone"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["emerg.relative"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["therapy?"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["therapy kind"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["medical concern"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["hear from"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["ailment & symptom"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["c.photo"] . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Status</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>Regist Date</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>ID</td>';
	$html.= '</tr>';
	

	while( $row = $db->fetch($result)) {
		$cnt++;	
		$html.= '<tr>';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["last_name"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["first_name"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["legal_last"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["legal_first"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["dharma_name"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["dharma_pinyin"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row["alias"]) . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $row["gender"] . '</td>';


		$query_em 	= "SELECT * FROM puti_members_others WHERE member_id = '" . $row["id"] . "'"; 
		$result_em 	= $db->query($query_em);
		$row_em 	= $db->fetch($result_em);

		// language ability
		$html.= '<td ' . $width_two . '>' . $langs[$row["language"]] . ($row_em["lang_main"]==""?"":"{" . $row_em["lang_main"] . "}") . '</td>';

        $pt = array();
        $pt["table"] = "puti_members_lang";
        $pt["keys"] = array("language_id");
        $pt["where"] = array("member_id"=>$row["id"]);
        $rt = array();
        $rt["table"] = "vw_vol_language";
        $rt["keys"] = array("id");
        $rt["cols"] = array("title_en", "title_cn");
		$langs_str = $db->astr( $db->rselect($pt, $rt), ($admin_user["lang"]=="en"?"title_en":"title_cn"), "; ");

		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($langs_str) . ($row_em["lang_able"]==""?"":"{" . $row_em["lang_able"] . "}") . '</td>';
		
		
		$html.= '<td ' . $width_two . '>' . $row["email"] . '</td>';
		$html.= '<td ' . $width_two . '>' . ($row["email_flag"]?$words["yes"]:$words["no"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cell"] . '</td>';

		$age_range 					= $row["age"]>=1?$ages[$row["age"]]:"";
		$birth_yy 					= $row["birth_yy"]>0? date("Y") - intval($row["birth_yy"]):"";
		$real_age 					= $birth_yy>0?$birth_yy:$age_range;
		
		$html.= '<td ' . $width_two . '>' . $real_age  . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::toDate($row["birth_yy"],$row["birth_mm"],$row["birth_dd"]) . '</td>';
		
		$html.= '<td ' . $width_two . '>' . cTYPE::toDate($row["member_yy"],$row["member_mm"],$row["member_dd"]) . '</td>';
		
		//$html.= '<td ' . $width_two . '>' . ($row["birth_date"]>0?date("M j,Y",$row["birth_date"]):"") . '</td>';
		$html.= '<td ' . $width_two . '>' . 	cTYPE::gstr($row["contact_method"]) . '</td>';

		$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_religion", $row["religion"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_degree", $row["degree"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	cTYPE::gstr($row["past_position"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	cTYPE::gstr($row["current_position"]) . '</td>';


		$html.= '<td ' . $width_two . '>' .  	cTYPE::gstr($row["address"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	cTYPE::gstr($row["city"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	cTYPE::gstr($row["state"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	cTYPE::gstr($row["country"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["postal"] . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($row["memo"]) . '</td>';
	
	
		//Other Information
		$query_em 	= "SELECT * FROM puti_members_others WHERE member_id = '" . $row["id"] . "'"; 
		$result_em 	= $db->query($query_em);
		$row_em 	= $db->fetch($result_em);

		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["emergency_name"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["emergency_phone"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["emergency_ship"]) . '</td>';
		// therapy?
		$html.= '<td ' . $width_two . '>' . ($row_em["therapy"]?'Yes':'') . '</td>';
		// therapy kind
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["therapy_content"]) . '</td>';
		// medical concern
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["medical_concern"]) . '</td>';

		$other_symptom = $row_em["other_symptom"];

		// heard from
        $query_em 	= "SELECT 	b.title 
								FROM puti_members_hearfrom a 
								INNER JOIN puti_info_hearfrom b ON (a.hearfrom_id = b.id) 
								WHERE member_id = '" . $row["id"] . "'"; 
		$result_em 	= $db->query($query_em);
		$row_ems 	= $db->rows($result_em);
		$hear_str = '';
		foreach($row_ems as $row_em) {
			$hear_str .= ($hear_str==''?'':';') . $words[strtolower($row_em["title"])];
		}
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($hear_str) . '</td>';

		// symptom
		$query_em 	= "SELECT 	b.title 
								FROM puti_members_symptom a 
								INNER JOIN puti_info_symptom b ON (a.symptom_id = b.id) 
								WHERE member_id = '" . $row["id"] . "'"; 
		$result_em 	= $db->query($query_em);
		$row_ems 	= $db->rows($result_em);
		$symptom_str = '';
		foreach($row_ems as $row_em) {
			$symptom_str .= ($symptom_str==''?'':';') . $words[strtolower($row_em["title"])];
		}
		$symptom_str .= ($symptom_str==''?'':'; ') . ($other_symptom==''?'':'Other:'.$other_symptom);
		$html.= '<td ' . $width_two . '>' .cTYPE::gstr($symptom_str) . '</td>';


		$html.= '<td ' . $width_two . ' align="center">' . (file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"") . '</td>';

		$html.= '<td ' . $width_two . '>' . ($row["status"]?"Active":"Inactive") . '</td>';
		$html.= '<td ' . $width_two . '>' . ($row["created_time"]>0?date("Y-M-d H:i:s",$row["created_time"]):"") . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["id"] . '</td>';
		$html.= '</tr>';
	}
	$html.= '<tr>';
	$html.= '<td colspan="41" style="font-size:12px; font-weight:bold;">' . $words["total"] . ': ' . $cnt . '</td>';
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
