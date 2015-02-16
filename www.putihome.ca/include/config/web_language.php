<?php 
ini_set("display_errors", 0);
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/language/webLang.php");
$db_admin = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

$Glang = "";
if( $Glang == "" ) $Glang = $_REQUEST["lang"]; 
if( $Glang == "" ) $Glang = $_SESSION[$_SERVER['HTTP_HOST'] . ".adminLang"];
if( $Glang == "" ) $Glang = $_COOKIE["puti_adminLang"];

$Gsite = "";
if( $Gsite == "" ) $Gsite = $_COOKIE["puti_adminSite"];

$words = getLang($Glang);

$_SESSION[$_SERVER['HTTP_HOST'] . ".adminLang"] = $Glang;
setcookie("puti_adminLang", $Glang, time() + 365 * 24 * 3600);

setcookie("puti_adminSite", $Gsite, time() + 365 * 24 * 3600);
?>