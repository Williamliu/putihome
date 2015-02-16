<?php 
include_once($CFG["include_path"] . "/config/web_language.php");

$menu = array();
$menu["menu"][0] = array("name"=>"EVENT CALENDAR", "tpl"=>"event_calendar.php", "url"=>"", "title"=>"", "desc"=>"");
//$menu["menu"][0]["menu"] 	= array();
//$menu["menu"][0]["menu"][0] = array("name"=>"Event Calendar", 	"tpl"=>"index.php", "url"=>"", "title"=>"", "desc"=>"");
//$menu["menu"][0]["menu"][1] = array("name"=>"Event - Sign In", "tpl"=>"event_calendar_signin.php", "url"=>"", "title"=>"", "desc"=>"");

$db_menu 		= new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$query_menu_sites 	= "SELECT * FROM puti_sites WHERE status = 1 ORDER BY sn DESC";
$result_menu_sites 	= $db_menu->query($query_menu_sites); 
$cnt = 10;
while( $row_menu_sites	= $db_menu->fetch($result_menu_sites) ) {
	$menu["menu"][$cnt] 	= array("name"=>$row_menu_sites["title"], "tpl"=>"index.php?site=" . $row_menu_sites["id"], "url"=>"", "title"=>"", "desc"=>"");
	$query_menu_branchs 	= "SELECT b.* FROM puti_sites_branchs a INNER JOIN puti_branchs b ON (a.branch_id = b.id) WHERE b.internal = 0 AND site_id = '" .  $row_menu_sites["id"] . "' ORDER BY id"; 
	$result_menu_branchs 	= $db_menu->query($query_menu_branchs);
	$cnt1 = 0;
	$menu["menu"][$cnt]["menu"] 	= array();
	while( $row_menu_branchs = $db_menu->fetch($result_menu_branchs) ) {
		$menu["menu"][$cnt]["menu"][$cnt1] = array("name"=>$row_menu_branchs["title"], 	"tpl"=>"index.php?site=" . $row_menu_sites["id"] . "&branch=" . $row_menu_branchs["id"], "url"=>"", "title"=>"", "desc"=>"");
		$cnt1++;
	}
	$cnt++;
}
?>