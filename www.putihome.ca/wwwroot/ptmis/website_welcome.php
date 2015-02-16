<?php
session_start();
ini_set("display_errors",0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="99,99";
include_once("website_admin_auth.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="copyright" content="Copyright Bodhi Meditation, All Rights Reserved." />
		<meta name="description" content="Bodhi Meditation Vancouver Site" />
		<meta name="keywords" content="Bodhi Meditation Vancouver" />
		<meta name="rating" content="general" />
		<meta name="language" content="english" />
		<meta name="robots" content="index" />
		<meta name="robots" content="follow" />
		<meta name="revisit-after" content="1 days" />
		<meta name="classification" content="" />
		<link rel="icon" type="image/gif" href="../bodhi.gif" />
		<title>Bodhi Meditation Welcome</title>

		<?php include("admin_head_link.php"); ?>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="padding:10px; min-height:420px;">
		<br />
        <span style="font-size:14px; font-weight:bold;">
			<?php echo $words["welcome page content"]?>
        </span>
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>