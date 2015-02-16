<?php
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
		<link rel="icon" type="image/gif" href="bodhi.gif" />
		<title>Bodhi Meditation Online Registration</title>

		<?php include("web_head_link.php"); ?>    
        
		<script type="text/javascript" 	src="js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="theme/blue/js.lwh.table.css" rel="stylesheet" />
</head>
<body>
<?php 
include("public_menu_html_nohead.php");
?>
 		<a class="goto-event-calendar" href="index.php" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["main menu"]?></a>	<br />
		<?php 
			$member_id = $db->getVal("puti_members", "id", array("sess_id"=>$_REQUEST["publicSession"]));
			
			$query = "SELECT a.id, a.confirm, a.group_no, 
										b.title, b.start_date, b.end_date, 	
 										c.first_name, c.last_name, c.dharma_name 
										FROM event_calendar_enroll a 
										INNER JOIN event_calendar b ON (a.event_id = b.id)
										INNER JOIN puti_members c ON (a.member_id = c.id)  
									 	WHERE b.deleted <> 1 AND c.deleted <> 1 AND c.status = 1 AND b.status < 9 
												AND a.event_id = '" . $_REQUEST["event_id"] .  "' 
												AND a.member_id = '" . $member_id . "'";

			$result = $db->query($query);
			
			if( $db->row_nums($result) > 0 ) {
				$row = $db->fetch($result);
				$name = array();
				$name["first_name"] = $row["first_name"];
				$name["last_name"] 	= $row["last_name"];
		?>
  
    <center>
    <div class="login-account">
    	<img src="theme/blue/image/icon/login_account.png" style="border-radius:50%;" />
		<br />
    	<br /><span style='font-size:16px; color:black;'><?php echo $words["welcome to our meditation class."]?></span><br />
        <br /><span style='font-size:14px; color:black;'><?php echo $words["your registration details as below"]?>:</span><br />
        
        <?php		
				echo "<table class='tabQuery-table' border='1'>
					<tr><td>" . $words["name"] . ":</td><td>" . cTYPE::cname($name) . "</td></tr>
					<tr><td>" . $words["dharma name"] . "</td><td>" . $row["dharma_name"] . "</td></tr>
					<tr><td>" . $words["class name"] . ":</td><td>" . $row["title"] . "</td></tr>
					<tr><td style='color:blue;font-weight:bold;'>" . $words["start date"] . ":</td><td><span style='color:blue;font-weight:bold;'>" . ($row["start_date"]>0?date("Y-m-d",$row["start_date"]):"") . "</span></td></tr>
					<tr><td>" . $words["end date"] . ":</td><td>" . ($row["end_date"]>0?date("Y-m-d",$row["end_date"]):"") . "</td></tr>
					<tr><td style='color:blue;font-weight:bold;'>" . $words["group"] . ":</td><td><span style='color:blue;font-weight:bold;'>" . ($row["group_no"]>0?$row["group_no"]:$words["to be confirmed"]) . "</span></td></tr>
					</table><br>
					<span style='color:red;font-weight:bold;'>" . $words["remind"] . ":</span> <span style='color:black;'>" . $words["please remember class start date and your group no."] . "</span><br>
					";	
		?>
     </div>
    </center>
    <br />
    
        
        <?php
			} else {
		?>
    <div style="width:100%;min-height:320px;padding:10px;">
        <br />
        <?php
			   	echo "<span style='color:red; font-size:16px;'>". $words["register failure"] . "</span>";
			}
		?>
    </div>
<?php 
include("public_footer_html_nohead.php");
?>

<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />
</body>
</html>