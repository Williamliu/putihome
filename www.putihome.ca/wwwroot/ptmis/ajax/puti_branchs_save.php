<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["site_id"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "site_id", 	"name":"Site ID", 		"nullable":0}';
	$type["branch_id"] 		= '{"type":"NUMBER", 	"length":11, 	"id": "branch_id", 	"name":"Branch ID", 	"nullable":0}';
	$type["branch_title"]	= '{"type":"CHAR", 		"length":255, 	"id": "branch_title", 	"name":"Branch Title", 	"nullable":0}';
	$type["branchs"]		= '{"type":"NUMBER", 	"length":1, 	"id": "branchs", 	"name":"branchs", 		"nullable":0}';
	$type["internal"]		= '{"type":"NUMBER", 	"length":1, 	"id": "internal", 	"name":"internal", 		"nullable":1}';
	$type["branch_sn"]		= '{"type":"NUMBER", 	"length":11, 	"id": "branch_sn", 	"name":"SN序号", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$site_id = $_REQUEST["site_id"]; 
	$branch_id = $_REQUEST["branch_id"];
	
	if($branch_id < 0) {
			$fields = array();
			$fields["title"] 			= cTYPE::utrans($_REQUEST["branch_title"]);
			$fields["internal"] 		= $_REQUEST["internal"];
			$fields["sn"] 				= $_REQUEST["branch_sn"]?$_REQUEST["branch_sn"]:0;
			$branch_id = $db->insert("puti_branchs", $fields);
	} else {
			$fields = array();
			$fields["title"] 			= cTYPE::utrans($_REQUEST["branch_title"]);
			$fields["internal"] 		= $_REQUEST["internal"];
			$fields["sn"] 				= $_REQUEST["branch_sn"]?$_REQUEST["branch_sn"]:0;
			$db->update("puti_branchs", $branch_id, $fields);
	}
	
	if( $site_id < 0 ) {
	}  else {
		  $db->query("DELETE FROM puti_sites_branchs WHERE branch_id = '" . $branch_id . "' AND site_id = '" . $site_id . "'");
		  if( $_REQUEST["branchs"] == "1") {
			  $fields = array();
			  $fields["site_id"] 		= $site_id;
			  $fields["branch_id"] 		= $branch_id;
			  $db->insert("puti_sites_branchs", $fields);
		  } 
	}
	
	
	$result_branchsss = $db->query("SELECT branch_id from puti_sites_branchs WHERE site_id = '" . $site_id . "'");
	$branch_array = array();
	while( $row_branchsss = $db->fetch($result_branchsss) ) {
		$branch_array[] = $row_branchsss["branch_id"];
	}
	
	$result_branchs = $db->query("SELECT * FROM puti_branchs Order BY sn");
	
	$html =  '<table class="tabQuery-table">';
	$html .= '<tr>';
	$html .= '<td class="tabQuery-table-header">' . $words["sel."] . '</td>';				
	$html .= '<td class="tabQuery-table-header">' . $words["r.teaching"] . '</td>';				
	$html .= '<td class="tabQuery-table-header">' . $words["internal group"] . '</td>';				
	$html .= '<td class="tabQuery-table-header">' . $words["sn"] . '</td>';				
	$html .= '<td class="tabQuery-table-header"></td>';				
	$html .= '</tr>';
	while($rows_branchs = $db->fetch($result_branchs)) {
		$html .= '<tr>';
		$html .= '<td align="center">' . 
					'<input type="checkbox" rid="' . $rows_branchs["id"] . '" id="branchs_'  . $rows_branchs["id"] . '" name="branchs" ' . ( in_array($rows_branchs["id"], $branch_array)?'checked="checked"':'') . ' class="branchs"  value="' . $rows_branchs["id"] . '">'
			. '</td>';				
		$html .= '<td><span class="required">*</span> ' . 
					'Key: <input type="text" rid="' . $rows_branchs["id"] . '" id="branch_title_'  . $rows_branchs["id"] . '" name="branch_title"  class="branch_title"  value="' . $rows_branchs["title"] . '" style="width:200px;" /><br>Display: '
			 . ($words[strtolower($rows_branchs["title"])]!=""?$words[strtolower($rows_branchs["title"])]:$rows_branchs["title"])
			 . '</td>';				
		$html .= '<td align="center">'.
					'<input type="checkbox" rid="' . $rows_branchs["id"] . '" id="internal_'  . $rows_branchs["id"] . '" ' . ($rows_branchs["internal"]?'checked':'') . ' name="internal"  class="internal"  value="1">'
			.'</td>';				
		$html .= '<td align="center"><span class="required">*</span> '.
					'<input type="text" style="width:30px;" rid="' . $rows_branchs["id"] . '" id="branch_sn_'  . $rows_branchs["id"] . '" name="branch_sn"  class="branch_sn"  value="' . $rows_branchs["sn"] . '">'
			.'</td>';				
		$html .= '<td>' . 
				'<a class="tabQuery-button tabQuery-button-save branchs" oper="save" right="save" rid="' . $rows_branchs["id"] . '" title="保存"></a>'
			.'</td>';				
		$html .= '</tr>';
	}

		$html .= '<tr>';
		$html .= '<td align="center"></td>';				
		$html .= '<td><span class="required">*</span> Key: ' . 
					'<input type="text" style="width:200px;" rid="-1" id="branch_title_-1" name="branch_title"  class="branch_title"  value="">'
			 . '</td>';				
		$html .= '<td align="center">'.
					'<input type="checkbox" rid="-1" id="internal_-1" name="internal"  class="internal"  value="1">'
			.'</td>';				
		$html .= '<td align="center"><span class="required">*</span> '.
					'<input type="text" style="width:30px;" rid="-1" id="branch_sn_-1" name="branch_sn"  class="branch_sn"  value="">'
			.'</td>';				
		$html .= '<td>' . 
				'<a class="tabQuery-button tabQuery-button-save  branchs" oper="save" right="save" rid="-1" title="保存"></a>'
			.'</td>';				
		$html .= '</tr>';

	$html .= '</table>';
	
	$response["data"]["branch_id"] 		= $branch_id;
	$response["data"]["site_id"] 		= $site_id;
	$response["data"]["html"] 			= $html;
	
	$response["errorMessage"]	= "<br>Your submit has been saved successfully.";
	$response["errorCode"] 		= 0;

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
