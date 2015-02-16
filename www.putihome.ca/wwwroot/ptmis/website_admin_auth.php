<?php
$admin_user = array();
//session_unregister();
//echo "request: " . $_REQUEST["adminSession"] . "<br>"; 
//print_r($_SESSION);
if( $_REQUEST["adminSession"] != "" ) $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"] = $_REQUEST["adminSession"];

if( $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"] == "" ) {
	header("Location: " . $CFG["admin_login_webpage"]);
} else {
	$sess_db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$sess_id = $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"];
	$sess_db->query("UPDATE website_session SET deleted = 1 WHERE deleted <> 1 AND last_updated < '" . (time() - $CFG["admin_session_timeout"]) . "'");
	
	$result_sess = $sess_db->query("SELECT admin_id, session_id, platform FROM website_session WHERE deleted <> 1 AND session_id = '" . $sess_db->quote($sess_id) . "'");
	if( $sess_db->row_nums($result_sess) > 0 )  {
		$row_sess = $sess_db->fetch($result_sess);
		$admin_id = $row_sess["admin_id"];
		$platform = $row_sess["platform"]==""?"production":$row_sess["platform"];
		$sess_db->query("UPDATE website_session SET last_updated = '" . time() . "' WHERE deleted <> 1 AND session_id = '" . $sess_db->quote($sess_id) . "'");
		
		//special user
		if($admin_id == 999999) {
			$admin_user["id"] 			= 999999;
			$admin_user["first_name"] 	= "System";
			$admin_user["last_name"] 	= "Admin";
			$admin_user["user_name"] 	= "sa";
			$admin_user["email"] 		= "sa@sys.com";
			$admin_user["right"]		= $right;
		} 
		else 
		{
			// normal user
			$result_user = $sess_db->query("SELECT * FROM website_admins WHERE deleted <> 1 AND status = 1 AND id = '" . $sess_db->quote($admin_id) . "'");
			if( $sess_db->row_nums($result_user) > 0 )  {
				$row_user = $sess_db->fetch($result_user);
				$admin_user["id"] 			= $row_user["id"];
				$admin_user["first_name"] 	= $row_user["first_name"];
				$admin_user["last_name"] 	= $row_user["last_name"];
				$admin_user["user_name"] 	= $row_user["user_name"];
				$admin_user["email"] 		= $row_user["email"];
				$admin_user["site"] 		= $row_user["site"];
				$admin_user["branch"] 		= $row_user["branch"];
				$admin_user["platform"] 	= $platform;
				$CFG["mysql"]["database"]  	= $platform=="beta"?$CFG["test_db"]:$CFG["mysql"]["database"];

				$sites = $row_user["sites"] . ($row_user["sites"]!=""?",":"") . $row_user["site"];
				$sites = $sites!=""? "(" . $sites . ")":"(-1)";
				$admin_user["sites"] 		= $sites;

				$branchs = $row_user["branchs"] . ($row_user["branchs"]!=""?",":"") . $row_user["branch"];
				$branchs = $branchs!=""? "(" . $branchs . ")":"(-1)";
				$admin_user["branchs"] 		= $branchs;


				$departs = $row_user["department"]!=""? "(" . $row_user["department"] . ")":"(-1)";
				$admin_user["departs"] 		= $departs;
				$admin_user["department"] 	= $row_user["department"];
				
				$admin_user["lang"] 		= $row_user["lang"]==""?"en":$row_user["lang"];
				$Glang						= $admin_user["lang"];

				$admin_user["right"] 		= array();
				$result_grp = $sess_db->query("SELECT group_right, level FROM website_groups WHERE deleted <> 1 AND status = 1 AND id = '" . $row_user["group_id"] . "'");
				$row_grp = $sess_db->fetch($result_grp);
				$admin_user["right"] 		= json_decode($row_grp["group_right"], true);
				$admin_user["group_level"] 	= $row_grp["level"];

				$admin_user["timezone"] 	= $sess_db->getVal("puti_sites", "timezone", $admin_user["site"]);
				date_default_timezone_set($admin_user["timezone"]);
				//echo "admin timezone:" . $admin_user["timezone"];
				//echo "<br>";
				//echo "get timezone:" . date_default_timezone_get();
				//echo "<br>";
				
			} else {
				header("Location: " . $CFG["admin_login_webpage"]);
			}
			// end of normal user
		}
	} else {
		header("Location: " . $CFG["admin_login_webpage"]);
	}
}
$admin_menu_arr = explode(",", $admin_menu);
$admin_user_right = $admin_user["right"];
for($i = 0 ; $i < count($admin_menu_arr); $i++) {
	$admin_user_right = $admin_user_right["right"][$admin_menu_arr[$i]]; 	
}
$admin_user_right_str = json_encode($admin_user_right);

if( $admin_user_right["view"] != 1 ) {
	if( !preg_match("/website_welcome.php/",$_SERVER["REQUEST_URI"]) ) {
		header("Location: " . $CFG["admin_welcome_webpage"]);
	}
}
?>
