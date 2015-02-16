<?php
date_default_timezone_set('America/Los_Angeles');

$CFG = array();
// domain name   and file path
$CFGCNT = preg_match("/^((?!www)\w*)\.(\w*\.\w*)$/i", $_SERVER["HTTP_HOST"], $CFGMATCH);
if( $CFGCNT ) {
	$CFGSUB = "/" . $CFGMATCH[$CFGCNT];
	$CFG["web_root"] = substr($_SERVER['DOCUMENT_ROOT'], 0 , strrpos($_SERVER['DOCUMENT_ROOT'], $CFGSUB));
	$CFG["web_root"] = substr($CFG["web_root"], 0 , strrpos($CFG["web_root"], "/"));
	$CFG["root_domain"] = $CFGMATCH[2];
} else {
	$CFG["web_root"] = substr($_SERVER['DOCUMENT_ROOT'], 0 , strrpos($_SERVER['DOCUMENT_ROOT'], "/") );
	$CFG["root_domain"] = $_SERVER['HTTP_HOST'];
}

$CFG["web_root"] 		= "d:/www.putihome.ca";

$CFG["http"]			= "http://";
$CFG["web_path"] 		= $_SERVER['DOCUMENT_ROOT'];
$CFG["web_domain"] 		= $_SERVER['HTTP_HOST'];
$CFG["admin_domain"] 	= $_SERVER['HTTP_HOST'] . "/ptmis";


$CFG["include_path"] 	= $CFG["web_root"] . "/include";
$CFG["report_path"] 	= $CFG["web_root"] . "/reports";
$CFG["upload_path"] 	= "D:/www.putihome.ca/uploads";

// Theme and   use the theme folder name for Array key.
$CFG["theme"]["blue"] 	= "Blue";
$CFG["theme_default"] 	= "blue";

//Debug = true,  please specify user id. 
$CFG["debug"] = false;

//shoes rack max capability
$CFG["max_shoes_rack"] = "0350";

//user auth
$CFG["admin_session_timeout"] 	= 3600 * 8; 
$CFG["admin_login_webpage"] 	= $CFG["http"] . $CFG["admin_domain"] . "/index.php"; 
$CFG["admin_welcome_webpage"] 	= $CFG["http"] . $CFG["admin_domain"] . "/website_welcome.php"; 

$CFG["trial_date"] = 2; 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    MySQL Connection Information 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
define("PRODUCTION", "production");
define("BETA", "beta");

define("ENVIR", PRODUCTION);
switch(ENVIR) {
	case BETA:
			$CFG["mysql"]["host"] 		= "192.168.1.10";
			$CFG["mysql"]["database"]  	= "puti_testdb";
			$CFG["mysql"]["user"] 		= "xxxxxxxx";
			$CFG["mysql"]["pwd"] 		= "xxxxxxxx";
			break;

	case PRODUCTION:
			$CFG["mysql"]["host"] 		= "192.168.1.10";
			$CFG["mysql"]["database"]  	= "puti_maindb";
			$CFG["mysql"]["user"] 		= "xxxxxxxxx";
			$CFG["mysql"]["pwd"] 		= "xxxxxxxxx";
			break;
}

$CFG["test_db"] = "puti_testdb";
?>