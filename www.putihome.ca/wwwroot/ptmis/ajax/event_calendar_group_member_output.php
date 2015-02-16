<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=event_group_confirm.xls");
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$query000 = "SELECT id, title FROM puti_sites";
	$result000 = $db->query($query000);
	$sites = array();
	while( $row000 = $db->fetch($result000) ) {
		$sites[$row000["id"]] = $row000["title"];
	}

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}


	$query_class 	= "SELECT a.class_id, a.start_date, b.* FROM event_calendar a INNER JOIN puti_class b ON (a.class_id = b.id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
	$result_class 	= $db->query($query_class);
	$row_class 		= $db->fetch($result_class);
	$photo 			= $row_class["photo"];
	$class_id       = $row_class["class_id"];
    $payfree        = $row_class["payfree"];
    $payonce        = $row_class["payonce"];
    $evt_start_date = $row_class["start_date"];


	$query0 	= "SELECT title, start_date, end_date FROM event_calendar WHERE deleted <> 1 AND id = '" . $_REQUEST["event_id"] . "'";
	$result0 	= $db->query($query0);
	$row0 		= $db->fetch($result0);
	
	$query = "SELECT a.id as enroll_id, a.created_time as enroll_date, a.confirm, a.shelf, a.new_flag, a.trial, 
                        b.id as member_id, b.*,
					 IFNULL(e.title,'') as transportation, d.offer_carpool, d.plate_no,
					 c.title, c.start_date, c.end_date, a.leader, a.volunteer 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
            			INNER JOIN event_calendar c ON (a.event_id = c.id) 
						LEFT JOIN puti_members_others d ON (b.id = d.member_id) 
						LEFT JOIN puti_info_carpool e ON (d.transportation = e.id) 
						WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND  
						c.deleted <> 1 AND 
						a.event_id = '" . $_REQUEST["event_id"] . "' 
						ORDER BY  a.group_no, a.leader DESC, a.volunteer DESC, b.last_name, b.first_name";
	$result = $db->query($query);

	
	$header_css = 'align="center" style="background-color:#cccccc; font-weight:bold;"';
	//$width_one = 'width="20"';
	//$width_two = 'width="120"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="2" style="font-size:16px; width:350px;">';
	$html.= '<tr>';
	$html.= '<td colspan="34" align="center" height="40" style="font-size:16px; border:0px; font-weight:bold;">' . cTYPE::gstr($row0["title"]) . '<br>[ ' . date("M d, Y", $row0["start_date"]) . ($row0["start_date"]>0?' ~ ' .date("M d, Y", $row0["end_date"]):'') .  ' ]</td>';
	$html.= '</tr>';

	$html.= '<tr>';
	$html.= '<td ' . $width_one . ' ' . $header_css . '>' . cTYPE::gstr($words["sn"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["group"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["g.leader"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["g.volunteer"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["trial"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["name"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["dharma"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["dharma pinyin"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["legal name"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["print name"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["reg.date"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["member enter date"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["age"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["birth date"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["gender"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["new people"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["email"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["phone"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["cell"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["city"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["c.photo"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["shoes.shelf"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["short.lang"]) . '</td>';

    $html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["religion"] . '</td>';
    $html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["member.degree"] . '</td>';
    $html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["past_position"] . '</td>';
    $html.= '<td ' . $width_two . ' ' . $header_css . '>' . $words["current_position"] . '</td>';

	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["hear from"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["therapy?"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["therapy kind"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["medical concern"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["ailment & symptom"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["g.site"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["transportation"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["offer_carpool"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["plate no"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["memo notes"]) . '</td>';
	$html.= '<td ' . $width_two . ' ' . $header_css . '>' . cTYPE::gstr($words["email confirm"]) . '</td>';
	$html.= '</tr>';
	
	$cnt = 0;
	while( $row = $db->fetch($result)) {
		$cnt++;	
		$html.= '<tr height="25">';
		$html.= '<td ' . $width_one . ' align="center">' . $cnt . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . ($row["group_no"]>0?$row["group_no"]:"") . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . ($row["leader"]?$words["yes"]:"") . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . ($row["volunteer"]?$words["yes"]:"") . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . ($row["trial"]?$words["yes"]:"") . '</td>';
		
		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$names["alias"] 			= $row["alias"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . '</td>';

		$names						= array();
		$names["dharma_name"] 		= $row["dharma_name"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . '</td>';

		$html.= '<td ' . $width_two . '>' . $row["dharma_pinyin"] . '</td>';

		$names						= array();
		$names["first_name"] 		= $row["legal_first"];
		$names["last_name"] 		= $row["legal_last"];
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr(cTYPE::lfname($names)) . '</td>';

		$names						= array();
		$names["first_name"] 		= $row["first_name"];
		$names["last_name"] 		= $row["last_name"];
		$html.= '<td ' . $width_two . '>' . trim(cTYPE::gstr(cTYPE::fullfirst($names,13))) . '</td>';

		$html.= '<td ' . $width_two . '>' . cTYPE::inttodate($row["enroll_date"]) . '</td>';

		$html.= '<td ' . $width_two . '>' . cTYPE::toDate($row["member_yy"],$row["member_mm"],$row["member_dd"]) . '</td>';

		$age_range 					= $row["age"]>=1?$ages[$row["age"]]:"";
		$birth_yy 					= $row["birth_yy"]>0? date("Y") - intval($row["birth_yy"]):"";
		$real_age 					= $birth_yy>0?$birth_yy:$age_range;

		$html.= '<td ' . $width_two . ' align="center">' . $real_age  . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::toDate($row["birth_yy"],$row["birth_mm"],$row["birth_dd"]) . '</td>';
		
		
		$html.= '<td ' . $width_two . ' align="center">' . $words[strtolower($row["gender"])] . '</td>';
		
        /* new people */
        $html.= '<td ' . $width_two . ' align="center">' . ($row["new_flag"]?"Y":"") . '</td>';
        /* end of new people */
         
        
        $html.= '<td ' . $width_two . '>' . $row["email"] . '</td>';
		$html.= '<td ' . $width_two . ' align="left">' . $row["phone"] . '</td>';
		$html.= '<td ' . $width_two . ' align="left">' . $row["cell"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["city"] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . (file_exists($CFG["upload_path"] . "/small/" . $row["id"] . ".jpg")?"Y":"") . '</td>';
		$html.= '<td ' . $width_two . ' align="right">' . $row["shelf"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $db->getTitle($admin_user["lang"], "vw_vol_language",$row["language"]) . '</td>';


		$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_religion", $row["religion"]) . '</td>';
		$html.= '<td ' . $width_two . '>' .  	$db->getTitle($admin_user["lang"], "vw_vol_degree", $row["degree"]) . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["past_position"] . '</td>';
		$html.= '<td ' . $width_two . '>' . $row["current_position"] . '</td>';


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



		//Other Information
		$query_em 	= "SELECT * FROM puti_members_others WHERE member_id = '" . $row["id"] . "'"; 
		$result_em 	= $db->query($query_em);
		$row_em 	= $db->fetch($result_em);

		$html.= '<td ' . $width_two . '>' . ($row_em["therapy"]?'Yes':'') . '</td>';
		// therapy kind
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["therapy_content"]) . '</td>';
		// medical concern
		$html.= '<td ' . $width_two . '>' .  cTYPE::gstr($row_em["medical_concern"]) . '</td>';
		$other_symptom = $row_em["other_symptom"];

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



		$html.= '<td ' . $width_two . '>' . $words[strtolower($sites[$row["site"]])] . '</td>';
		$html.= '<td ' . $width_two . '>' . $words[strtolower($row["transportation"])] . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . ($row["offer_carpool"]?$words["yes"]:"") . '</td>';
		$html.= '<td ' . $width_two . ' align="center">' . $row["plate_no"] . '</td>';
		$html.= '<td ' . $width_two . '>' . cTYPE::gstr($row["memo"]) . '</td>';
		$html.= '<td ' . $width_two . ' style="font-size:14px;font-weight:bold;color:red;text-align:center;">' . $row["confirm"] . '</td>';
		$html.= '</tr>';
	}
	
	//$html.= '<tr>';
	//$html.= '<td colspan="9" align="center" style="height:10px;"></td>';
	//$html.= '</tr>';
	$html.= '<tr>';
	$html.= '<td colspan="34" style="font-size:16px; font-weight:bold;">' . cTYPE::gstr($words["total"]) . ': ' . $cnt . '</td>';
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
