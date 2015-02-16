<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=UTF-8");
	header("Content-disposition:  attachment; filename=puti_volunteer_schedule.xls");
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = cTYPE::gstr($row000["title"]);
	}

    $order_str 	= " ORDER BY last_name, first_name, start_date DESC, schedule_type, start_time , end_date DESC, end_time";
	
    $professionals = $db->getTitles($admin_user["lang"], "vw_vol_professional");
    $healths = $db->getTitles($admin_user["lang"], "vw_vol_health");
    $departs = $db->getTitles($admin_user["lang"], "pt_department");

    $sch_types = array();
    $sch_types[""] = "";
    $sch_types[0] =  cTYPE::gstr($words["volunteer.schedule.type.daily"]);
    $sch_types[1] =  cTYPE::gstr($words["volunteer.schedule.type.weekly"]);
    $sch_types[2] =  cTYPE::gstr($words["volunteer.schedule.type.monthly"]);

    $weekdays = array();
    $weekdays[0] = "";
    $weekdays[1] = cTYPE::gstr($words["weekday.mon"]);
    $weekdays[2] = cTYPE::gstr($words["weekday.tue"]);
    $weekdays[3] = cTYPE::gstr($words["weekday.wed"]);
    $weekdays[4] = cTYPE::gstr($words["weekday.thur"]);
    $weekdays[5] = cTYPE::gstr($words["weekday.fri"]);
    $weekdays[6] = cTYPE::gstr($words["weekday.sat"]);
    $weekdays[7] = cTYPE::gstr($words["weekday.sun"]);


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

	$sch_email = trim($con["sch_email"]);
	if($sch_email != "") {
		$criteria .= ($criteria==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_site = trim($con["sch_site"]);
	if($sch_site != "") {
		$criteria .= ($criteria==""?"":" AND ") . "site = '" . $sch_site . "'";
	}

	$sch_memid = trim($con["sch_memid"]);
	if($sch_memid != "") {
		$criteria .= ($criteria==""?"":" AND ") . "id = '" . $sch_memid . "'";
	}


	$sch_phone = trim($con["sch_phone"]);
	if($sch_phone != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}


	$sch_city = trim($con["sch_city"]);
	if($sch_city != "") {
		$criteria .= ($criteria==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	} 

	$sch_position = trim($con["sch_position"]);
	if($sch_position != "") {
		$criteria .= ($criteria==""?"":" AND ") . "( past_position like '%" . cTYPE::trans($sch_position) . "%' OR  current_position like '%" . cTYPE::trans($sch_position) . "%')";
	} 

	$sch_resume = trim($con["sch_resume"]);
	if($sch_resume != "") {
		$criteria .= ($criteria==""?"":" AND ") . "resume like '%" . cTYPE::trans($sch_resume) . "%'";
	} 

	$sch_memo = trim($con["sch_memo"]);
	if($sch_memo != "") {
		$criteria .= ($criteria==""?"":" AND ") . "memo like '%" . cTYPE::trans($sch_memo) . "%'";
	} 

	$sch_degree = trim($con["sch_degree"]);
	if($sch_degree != "") {
		$criteria .= ($criteria==""?"":" AND ") . "degree = '" . cTYPE::trans($sch_degree) . "'";
	} 


	$sch_religion = trim($con["sch_religion"]);
	if($sch_religion != "") {
		$criteria .= ($criteria==""?"":" AND ") . "religion = '" . cTYPE::trans($sch_religion) . "'";
	} 

	$sch_vol_type = trim($con["sch_vol_type"]);
	if($sch_vol_type != "") {
		$criteria .= ($criteria==""?"":" AND ") . "vol_type = '" . cTYPE::trans($sch_vol_type) . "'";
	} 


	$sch_email_flag = trim($con["sch_email_flag"]);
	if($sch_email_flag != "") {
		$criteria .= ($criteria==""?"":" AND ") . "a.email_flag = '" . cTYPE::trans($sch_email_flag) . "'";
	} 

	$sch_gender = trim($con["sch_gender"]);
	if($sch_gender != "") {
		$criteria .= ($criteria==""?"":" AND ") . "gender = '" . cTYPE::trans($sch_gender) . "'";
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
	} else {
		$criteria .= ($criteria==""?"":" AND ") . "site in " . $admin_user["sites"];
	}
	
	$criteria = ($criteria==""?"":" AND ") . $criteria;
	// end of criteria


    $select = "a.id as member_id, a.*, 
			    b.resume, b.memo as vol_memo, b.vol_type, b.status as vol_status, b.deleted as vol_deleted, b.created_time as vol_date,
                aa2.schedule_id, aa2.schedule_type, aa2.start_date, aa2.end_date, DATE_FORMAT(aa2.start_time, '%H:%i') as start_time, DATE_FORMAT(aa2.end_time, '%H:%i') as end_time";
    $from = "puti_members a	INNER JOIN pt_volunteer b ON (a.id = b.member_id)";
    $where = "a.deleted <> 1  AND b.deleted <> 1 " . $criteria;



	$sch_professional = trim($con["sch_professional"]);
	$sch_depart         = trim($con["sch_depart"]);
	$sch_sdate          = trim($con["sch_sdate"]);
	$sch_schedule_type  = trim($con["sch_schedule_type"]);
	$sch_hh 			= trim($con["sch_hh"]);
	$sch_mm 			= trim($con["sch_mm"]);
	$sch_time			= $sch_hh!=""? $sch_hh . ":" . ($sch_mm!=""?$sch_mm:"00") : "";

	if($sch_professional != "") {
		$query_pro = "SELECT distinct aaa0.member_id FROM pt_volunteer_professional aaa0 WHERE aaa0.professional_id = '" . $sch_professional . "'"; 
        $from_pro = " INNER JOIN ($query_pro) aa0 ON (b.member_id = aa0.member_id) ";
        $from .= $from_pro;    
	}


	if($sch_depart != "") {
		$query_dep = "SELECT distinct aaa1.member_id FROM pt_volunteer_depart_current aaa1 WHERE aaa1.depart_id in (" . $sch_depart . ")"; 
        $from_dep = " INNER JOIN ($query_dep) aa1 ON (b.member_id = aa1.member_id) ";
        $from .= $from_dep;    
	}


	if($sch_sdate != "") {
        if( $sch_schedule_type != "" )
			if($sch_time != "") 
		    	$query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
											WHERE aaa2.schedule_type = '" . $sch_schedule_type . "' AND 
													aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "' AND
													aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
        	else 
		    	$query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
											WHERE 	aaa2.schedule_type = '" . $sch_schedule_type . "' AND 
													aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "'"; 
			
		else 
			if($sch_time != "") 
			    $query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
										WHERE 	aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "' AND 
												aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
			else 
			    $query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
										WHERE aaa2.start_date  <= '" . $sch_sdate . "' AND aaa2.end_date >= '" . $sch_sdate . "'"; 
			
        $from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
        $from .= $from_date;    
	} else {
        if( $sch_schedule_type != "" ) {
    		if($sch_time != "") {
				$query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
											WHERE 	aaa2.schedule_type = '" . $sch_schedule_type . "' AND 
													aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
				$from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			} else {
				$query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
											WHERE	aaa2.schedule_type = '" . $sch_schedule_type . "'"; 
				$from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			}
        } else {
    		if($sch_time != "") {
				$query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2 
											WHERE 	aaa2.start_time <= '" . $sch_time . "' AND aaa2.end_time >= '" . $sch_time . "'"; 
				$from_date = " INNER JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			}  else {
				$query_date = "SELECT aaa2.* FROM pt_volunteer_schedule aaa2"; 
				$from_date .= " LEFT JOIN ($query_date) aa2 ON (b.member_id = aa2.member_id) ";
				$from .= $from_date; 
			}
		}
	}
	
	$query_base = "SELECT $select FROM $from WHERE $where $order_str";
    //echo "query: " . $query_base . "\n<br>";

	$query 	= $query_base;
	$result = $db->query( $query );
	$rows = array();

	$header_css = 'align="center" valign="center" style="background-color:#cccccc; font-weight:bold; height:40px"';
	$width_one = 'valign="top"';
	$width_two = 'valign="top"';
	$html = '<table border="1" cellpadding="2" style="font-size:14px; width:500px;">';
	$cnt = 0;
    //// table header //////
			$html.= '<tr valign="middle">';
			$html.= '<td ' . $width_one . ' ' . $header_css . '>' . cTYPE::gstr($words["sn"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["name"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["dharma name"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["legal name"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member.regdate"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["age"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["birth date"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["gender"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["email"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["email subscription"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["phone"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["cell"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["city"]) . '</td>';
            $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["short.lang"]) . '</td>';
            $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["lang.ability"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["religion"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["g.site"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["c.photo"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["memo notes"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["hear from"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["therapy?"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["therapy kind"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["medical concern"]) . '</td>';
			$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["ailment & symptom"]) . '</td>';

	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member.health"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member.professional"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member.degree"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["past_position"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["current_position"]) . '</td>';

	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["volunteer.regdate"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["volunteer.type"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member.will_depart"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member.current_depart"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["start date"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["end date"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["start time"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["end time"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["volunteer.schedule.type"]) . '</td>';
	        $html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["volunteer.select.date"]) . '</td>';
           
			$html.= '</tr>';
    /// end of table header

	while( $row = $db->fetch($result)) {
		$cnt++;	

		//Other Information
		$query_mother 	= "SELECT * FROM puti_members_others WHERE member_id = '" . $row["member_id"] . "'"; 
		$result_mother 	= $db->query($query_mother);
		$row_mother 	= $db->fetch($result_mother);

        ///// volunteer ///////////////
		$query_vother	= "SELECT * FROM pt_volunteer_others WHERE member_id = '" . $row["member_id"] . "'"; 
		$result_vother 	= $db->query($query_vother);
		$row_vother	= $db->fetch($result_vother);


        /////////// row /////////////////
		$html.= '<tr valign="top">';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt . '</td>';
		
		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . '</td>';

		$names						= array();
		$names["dharma_name"] 		= $row["dharma_name"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . ($row["dharma_pinyin"]!=""?"{". $row["dharma_pinyin"] ."}":"") . '</td>';

		$names						= array();
		$names["first_name"] 		= $row["legal_first"];
		$names["last_name"] 		= $row["legal_last"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . '</td>';
        
		$html.= '<td ' . $width_two . '>' . cTYPE::toDate($row["member_yy"],$row["member_mm"],$row["member_dd"]) . '</td>';

		$age_range 					= $row["age"]>=1?$ages[$row["age"]]:"";
		$birth_yy 					= $row["birth_yy"]>0? date("Y") - intval($row["birth_yy"]):"";
		$real_age 					= $birth_yy>0?$birth_yy:$age_range;
		$html.= '<td ' . $width_two . ' align="center">' . $real_age . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::toDate($row["birth_yy"],$row["birth_mm"],$row["birth_dd"]) . '</td>';
        
		$html.= '<td ' . $width_two . ' align="center">' . $words[strtolower($row["gender"])] . '</td>';

        $html.= '<td ' . $width_two . '>' . $row["email"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . ($row["email_flag"]?"Y":"") . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["phone"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["cell"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["city"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]) . ($row_mother["lang_main"]==""?"":" {" . $row_mother["lang_main"] . "}") . '</td>';

        $pt = array();
        $pt["table"] = "puti_members_lang";
        $pt["keys"] = array("language_id");
        $pt["where"] = array("member_id"=>$row["member_id"]);
        $rt = array();
        $rt["table"] = "vw_vol_language";
        $rt["keys"] = array("id");
        $rt["cols"] = array("title_en", "title_cn");
		$langs_str = $db->astr( $db->rselect($pt, $rt), ($admin_user["lang"]=="en"?"title_en":"title_cn"), "; ");

		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($langs_str) . ($row_mother["lang_able"]==""?"":"{" . $row_mother["lang_able"] . "}") . '</td>';

		$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_religion", $row["religion"]) . '</td>';
    	$html.= '<td ' . $width_two . '>' . $words[strtolower($sites[$row["site"]])] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . (file_exists($CFG["upload_path"] . "/small/" . $row["member_id"] . ".jpg")?"Y":"") . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($row["memo"] . ($row["vol_memo"]?" {Volunteer Memo}:". $row["vol_memo"]:"") ) . '</td>';

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

    
        // other information
		$html.= '<td ' . $width_two . ' align="center">' . ($row_mother["therapy"]?'Yes':'') . '</td>';
		// therapy kind
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_mother["therapy_content"]) . '</td>';
		// medical concern
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_mother["medical_concern"]) . '</td>';
		$other_symptom = $row_mother["other_symptom"];



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
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($symptom_str) . '</td>';

	
        ///// volunteer ///////////////
        $rs_hl = $db->select( "pt_volunteer_health", "health_id", array("member_id"=> $row["member_id"]) );    
    	$row_hl = $db->attrs($rs_hl, "health_id");
        $html.= '<td ' . $width_two . '>' .  	$db->astrs($healths, $row_hl) . ($row_vother["health_other"]==""?"":" {" . $row_vother["health_other"] . "}") . '</td>';


        $rs_pro = $db->select( "pt_volunteer_professional", "professional_id", array("member_id"=> $row["member_id"]) );    
    	$row_pro = $db->attrs($rs_pro, "professional_id");
        $html.= '<td ' . $width_two . '>' .  	$db->astrs($professionals, $row_pro) . ($row_vother["professional_other"]==""?"":" {" . $row_vother["professional_other"] . "}") . '</td>';

    	$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_degree", $row["degree"]) . '</td>';


		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($row["past_position"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($row["current_position"]) . '</td>';

    	$html.= '<td ' . $width_two . '>' .  	cTYPE::inttodate($row["vol_date"]) . '</td>';
    	$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_type", $row["vol_type"]) . '</td>';

        $rs_wi = $db->select( "pt_volunteer_depart_will", "depart_id", array("member_id"=> $row["member_id"]) );    
    	$row_wi = $db->attrs($rs_wi, "depart_id");
        $html.= '<td ' . $width_two . '>' .  	$db->astrs($departs, $row_wi) . '</td>';

        $rs_cu = $db->select( "pt_volunteer_depart_current", "depart_id", array("member_id"=> $row["member_id"]) );    
    	$row_cu = $db->attrs($rs_cu, "depart_id");
        $html.= '<td ' . $width_two . '>' .  	$db->astrs($departs, $row_cu) . '</td>';


        $html.= '<td ' . $width_two . ' align="center">' . $row["start_date"] . '</td>';
        $html.= '<td ' . $width_two . ' align="center">' . $row["end_date"] . '</td>';
        $html.= '<td ' . $width_two . ' align="center">' . $row["start_time"] . '</td>';
        $html.= '<td ' . $width_two . ' align="center">' . $row["end_time"] . '</td>';

        $html.= '<td ' . $width_two . ' align="center">' . $sch_types[$row["schedule_type"]] . '</td>';

        $day_str = "";
        switch($row["schedule_type"]) {
            case "":
                $html.= '<td ' . $width_two . '></td>';
                break;
			case 0:
                $html.= '<td ' . $width_two . '>' . cTYPE::gstr($words["everyday"]) . '</td>';
                break;
            case 1:
                $rs_dd = $db->select( "pt_volunteer_schedule_day", "day", array("schedule_id"=> $row["schedule_id"]) );    
    	        $row_dd = $db->attrs($rs_dd, "day");
                $html.= '<td ' . $width_two . '>' .  	$db->astrs($weekdays, $row_dd) . '</td>';
                break;
            case 2:
                $rs_dd = $db->select("pt_volunteer_schedule_day", "day", array("schedule_id"=> $row["schedule_id"]) );    
    	        $row_dd = $db->attrs($rs_dd, "day");
                $html.= '<td ' . $width_two . '>' .  	$db->astr($row_dd) . '</td>';
                break;
        }
 
        $html.= '</tr>';

        /////////// end of  row /////////////////

	}

  	$html .= '</table>';
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
