<?php
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu=$_REQUEST["admin_menu"];
$admin_oper=$_REQUEST["admin_oper"];
try {
		$err = new cERR();
		$admin_user = array();
		if( $_REQUEST["admin_sess"] != "" ) $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"] = $_REQUEST["admin_sess"];
		
		if( $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"] == "" ) {
				$err->set(9001,"Click 'close' will redirect to login page.", $CFG["admin_login_webpage"]);
				throw $err;
		} else {
				$sess_db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
				$sess_id = $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"];
				$sess_db->query("UPDATE website_session SET deleted = 1 WHERE deleted <> 1 AND platform <> 'ID Collector' AND last_updated < '" . (time() - $CFG["admin_session_timeout"]) . "'");
			
				$result_sess = $sess_db->query("SELECT admin_id, session_id, platform FROM website_session WHERE deleted <> 1 AND session_id = '" . $sess_db->quote($sess_id) . "'");
				if( $sess_db->row_nums($result_sess) > 0 )  {
					$row_sess = $sess_db->fetch($result_sess);
					$admin_id = $row_sess["admin_id"];
					$platform = $row_sess["platform"];
					$sess_db->query("UPDATE website_session SET last_updated = '" . time() . "' WHERE deleted <> 1 AND session_id = '" . $sess_db->quote($sess_id) . "'");
				
					//special user
					if($admin_id == 999999) {
						$admin_user["id"] 			= 999999;
						$admin_user["first_name"] 	= "System";
						$admin_user["last_name"] 	= "Admin";
						$admin_user["user_name"] 	= "sa";
						$admin_user["email"] 		= "sa@sys.com";
						$admin_user["right"]		= $right;
					} else {				
						//normal user
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
							$Glang 						= $admin_user["lang"];
							//print_r($admin_user);
							$admin_user["right"] 		= array();
			
							$result_grp = $sess_db->query("SELECT group_right, level FROM website_groups WHERE deleted <> 1 AND status = 1 AND id = '" . $row_user["group_id"] . "'");
							$row_grp = $sess_db->fetch($result_grp);
							$admin_user["right"] 		= json_decode($row_grp["group_right"], true);
							$admin_user["group_level"] 	= $row_grp["level"];

							$admin_user["timezone"] 	= $sess_db->getVal("puti_sites", "timezone", $admin_user["site"]);
							date_default_timezone_set($admin_user["timezone"]);
						} else {
							$err->set(9001,"Click close will redirect to login page.", $CFG["admin_login_webpage"]);
							throw $err;
						}
						// end of normal user
					}
				
					$admin_menu_arr = explode(",", $admin_menu);
					$admin_user_right = $admin_user["right"];
					for($i = 0 ; $i < count($admin_menu_arr); $i++) {
						$admin_user_right = $admin_user_right["right"][$admin_menu_arr[$i]]; 	
					}
					$adminRight = $admin_user_right[$admin_oper];
					if($adminRight != 1) {
						$err->set(9002,"Click 'close' will redirect to login page.", $CFG["admin_login_webpage"]);
						throw $err;
					}

			} else {
				$err->set(9001,"Click 'close' will redirect to login page.", $CFG["admin_login_webpage"]);
				throw $err;
			}
		}
	
}
catch(cERR $e) {
	echo json_encode($e->detail());
	exit();
}
?>
