<?php 
ini_set("display_errors", 0);
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/language/webLang.php");
$db_admin = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

$lang_tmp = array();
$lang_tmp["session_id"] = $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"];
$lang_admin_id = $db_admin->getVal("website_session", "admin_id", $lang_tmp);

$Glang = "";
if( $Glang == "" ) $Glang = $_REQUEST["lang"]; 
if( $Glang == "" ) $Glang = $_SESSION[$_SERVER['HTTP_HOST'] . ".adminLang"];
if( $Glang == "" ) $Glang = $db_admin->getVal("website_admins", "lang", $lang_admin_id);
if( $Glang == "" ) $Glang = $_COOKIE["puti_adminLang"];

$words = getLang($Glang);
$_SESSION[$_SERVER['HTTP_HOST'] . ".adminLang"] = $Glang;
setcookie("puti_adminLang", $Glang, time() + 365 * 24 * 3600);
$db_admin = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$db_admin->query("UPDATE website_admins SET lang = '" . $Glang . "' WHERE id = '" . $lang_admin_id . "'");
?>