<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	header("Content-Type: application/vnd.ms-excel; name='excel'; charset=utf-8");
	header("Content-disposition:  attachment; filename=event_group_list.xls");
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}

	$query_ev = "SELECT title, start_date, end_date FROM event_calendar WHERE id = '" .  $_REQUEST["event_id"] . "'";
	$result_ev = $db->query($query_ev);
	$row_ev = $db->fetch($result_ev);
	$query = "SELECT a.id as enroll_id, a.confirm, a.leader, a.volunteer, b.id, b.first_name, b.last_name, b.dharma_name, b.dharma_pinyin, b.alias, b.age, b.gender, b.email, b.phone, b.cell, b.city, a.group_no,
					 c.title, c.start_date, c.end_date 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
            			INNER JOIN event_calendar c ON (a.event_id = c.id) 
						WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND  
						c.deleted <> 1 AND 
						a.event_id = '" . $_REQUEST["event_id"] . "' 
						ORDER BY a.group_no, a.leader DESC, a.volunteer DESC, b.last_name, b.first_name";
	$result = $db->query($query);
	$group_arr = array();
	while( $row = $db->fetch($result)) {
		$user = array();
		$user["title"] 			= $row["title"];
		$user["start_date"] 	= $row["start_date"]>0?date("M j, Y", $row["start_date"]):'';
		$user["end_date"] 		= $row["end_date"]>0?date("M j, Y", $row["end_date"]):'';
		$user["first_name"] 	= $row["first_name"];
		$user["last_name"] 		= $row["last_name"];
		$user["dharma_name"] 	= $row["dharma_name"];
		$user["dharma_pinyin"] 	= $row["dharma_pinyin"];
		$user["alias"] 			= $row["alias"];
		$user["age"] 			= $ages[$row["age"]];
		$user["confirm"] 		= $row["confirm"];
		$user["leader"] 		= $row["leader"]?$row["leader"]:0;
		$user["volunteer"] 		= $row["volunteer"]?$row["volunteer"]:0;
		$row["group_no"] 		= ($row["group_no"]>0?$row["group_no"]:$words["tbc"]);
		$group_arr[$row["group_no"]][] = $user;
	}
	
	$header_css = 'align="center" valign="middle" style="background-color:#cccccc; font-weight:bold; vertical-align:middle;"';
	$width_one = '';
	$width_two = '';
	
	$html = '<table border="1" cellpadding="2" style="font-size:24px; width:350px;">';
	
	$htmlRR.= '<tr>';
	$htmlRR.= '<td ' . $width_two . ' ' . $header_css . ' height="40" valign="middle">' . cTYPE::gstr($words["sn"]) . '</td>';
	$max_cnt = 0;
	$cnt = 0;
	foreach( $group_arr as $group_key=>$group ) {
		$htmlRR.= '<td ' . $width_two . ' ' . $header_css . ' height="40" valign="middle">' . cTYPE::gstr($words["group"]) . ': ' . $group_key  . '</td>';
		if( count($group) > $max_cnt ) $max_cnt = count($group);
		$cnt++;
	}
	$htmlRR.= '</tr>';
	
	$htmlTT= '<tr>';
	$htmlTT.= '<td colspan="' . ($cnt * 1 + 1) . '" align="center" valign="middle" height="60" style="font-size:22px;font-weight:bold;vertical-align:middle;">' . cTYPE::gstr($row_ev["title"]) . '<br>[ ' . date("M j, Y",$row_ev["start_date"]) . ($row_ev["end_date"]!=''?' ~ ' .date("M j, Y",$row_ev["end_date"]):'') .  ' ]</td>';
	$htmlTT.= '</tr>';
	
	$html .= $htmlTT . $htmlRR;

	$cnt = 0;
	
	for($i=0;$i<$max_cnt; $i++) {
		$html.= '<tr>';
		$html.='<td align="center" valign="middle" height="35" style="font-size:20px;font-weight:bold;vertical-align:middle;">' . ($i+1) . '</td>';
		foreach( $group_arr as $group_key=>$group ) {
			$html.='<td valign="middle" height="35" style="font-size:20px;font-weight:bold;vertical-align:middle;">';

			$names						= array();
			$names["first_name"] 		= $group[$i]["first_name"];
			$names["last_name"] 		= $group[$i]["last_name"];
			if($admin_user["site"] != "1") $names["dharma_name"] = $group[$i]["dharma_name"];
			//$names["alias"] 			= $group[$i]["alias"];
			$name_str 					= cTYPE::gstr(cTYPE::fullfirst($names,13));
			
			if($admin_user["site"] == "1" && $admin_user["lang"] != "en") if( trim($group[$i]["dharma_name"]) != "" ) $name_str = cTYPE::gstr(trim($group[$i]["dharma_name"])) . (trim($group[$i]["dharma_pinyin"])!=""?"-":"") . trim($group[$i]["dharma_pinyin"]);
			
			if($group[$i]["leader"])	
				$name_str = $words["other.zu"] . ": " . $name_str;
			elseif ($group[$i]["volunteer"])	$name_str = $words["other.yi"] . ": " . $name_str;

			$html .= $name_str;
			
			$html.= '</td>';
		}
		$html.= '</tr>';
	}
	
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
