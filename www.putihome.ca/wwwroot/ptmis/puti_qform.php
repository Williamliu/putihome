<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="5,30";
include_once("website_admin_auth.php");
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

$reg_city = $db->getVal("puti_sites", "city", $admin_user["site"]);
$reg_state = $db->getVal("puti_sites", "state", $admin_user["site"]);
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

		<?php include("admin_head_link.php"); ?>
		
        <script language="javascript" type="text/javascript">
		var htmlObj = new LWH.cHTML();
		function quick_ajax() {
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
	<br />
    <center><span class="form-header"><?php echo $words["register form"]?></span></center>
    <fieldset style="border:1px solid #cccccc;">
    	<legend style="border:1px solid #cccccc;background-color:orange;"><?php echo $words["event - sign in"];?></legend>
    	<span style="font-size:14px; font-weight:bold; margin-left:2px;"><?php echo $words["select event"]?>: </span>
        <select id="event_id" style="min-width:250px;vertical-align:middle;">
          <?php 
              $query = "SELECT distinct a.id, a.title, a.start_date, a.end_date, c.title as site_desc    
			  				  FROM event_calendar a 
							  INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
                              INNER JOIN puti_sites c ON (a.site = c.id) 
                              WHERE a.deleted <> 1 AND a.status = 2 AND
                                    b.deleted <> 1 AND b.status = 1 AND
									a.site IN " . $admin_user["sites"] . " AND
									a.branch IN " . $admin_user["branchs"] . " 
                              ORDER BY event_date";
              $first = true;
			  $result = $db->query($query);
              echo '<option value=""></option>';
              while( $row = $db->fetch($result) ) {
                  $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                  if($first) {
					  $first = false;
					  echo '<option value="' . $row["id"] . '" selected>'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . " [" . $date_str . ']</option>';
				  } else { 
					  echo '<option value="' . $row["id"] . '">'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . " [" . $date_str . ']</option>';
				  }
              }
              
          ?>
          </select>
          <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><?php echo $words["group"]?>: <input type="text" style="width:30px; font-size:14px; font-weight:bold; text-align:center;" id="group_no" name="group_no" value="" /></span>
          <!-- <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="onsite" name="onsite" value="1" /><label for="onsite"><?php echo $words["onsite registration"]?></label></span> -->
          <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="trial" name="trial" value="1" /><label for="trial"><?php echo $words["trial"]?></label></span>
    </fieldset>

<?php 
include("tpl_quickform.php");
?>

<?php 
include("admin_footer_html.php");
?>

</body>
</html>