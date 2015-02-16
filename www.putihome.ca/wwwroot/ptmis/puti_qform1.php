<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,80";
include_once("website_admin_auth.php");
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

$reg_city 	= $db->getVal("puti_sites", "city", $admin_user["site"]);
$reg_state 	= $db->getVal("puti_sites", "state", $admin_user["site"]);

$fname 		= '';
$lname		= '';
$dharma		= '';
$sch_name = cTYPE::trans(trim($_REQUEST["sch_name"]));

$result_gen = $db->query("SELECT dharma_prefix FROM puti_dharma ORDER BY dharma_date");
$dharma_gen = array();
while( $row_gen = $db->fetch($result_gen) ) {
	$dharma_gen[] = $row_gen["dharma_prefix"];
}

if( cTYPE::iifcn($sch_name) ) {
	if( mb_strlen($sch_name,"utf-8") == 2 ) {
		$found = false;
		foreach( $dharma_gen as $gen ) {
			if( preg_match("/^". $gen . "*/i", $sch_name) ) {
				$found = true;
				break;
			} else {
				continue;
			}
		}
		if( $found ) {
			$fname  = $sch_name;
			$lname  = $sch_name;
			$dharma	= $sch_name;		
		} else {
			$lname	= mb_substr($sch_name, 0, 1, 'utf-8');
			$fname	= mb_substr($sch_name, 1, mb_strlen($sch_name,"utf-8") , 'utf-8');
		} // end of if( $found )
	} else {
		if( mb_strlen($sch_name,"utf-8") >= 4 ) {
			$lname	= mb_substr($sch_name, 0, 2, 'utf-8');
			$fname	= mb_substr($sch_name, 2, mb_strlen($sch_name,"utf-8") , 'utf-8');
		} else {
			$lname	= mb_substr($sch_name, 0, 1, 'utf-8');
			$fname	= mb_substr($sch_name, 1, mb_strlen($sch_name,"utf-8") , 'utf-8');
		}
	} // if( mb_strlen($sch_name,"utf-8") == 2 )
	
} else {
		if(preg_match("/\w+(\s+)\w+/i", $sch_name) ) {
			$sch_name = preg_replace("/(\s+)/"," ", $sch_name);
			$tmp_name = explode(" ", $sch_name);
			$fname = $tmp_name[0];
			$lname = $tmp_name[1];
		} else if(preg_match("/\w+(\s*(,)\s*)\w+/i", $sch_name) ) {
			$sch_name = preg_replace("/(\s*(,)\s*)/"," ", $sch_name);
			$tmp_name = explode(" ", $sch_name);
			$lname = $tmp_name[0];
			$fname = $tmp_name[1];
		} else {
			$fname = $sch_name;
		}
}

$fname 		= cTYPE::gstr($fname);
$lname		= cTYPE::gstr($lname);
$dharma		= cTYPE::gstr($dharma);
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
			$("input[name='enroll_event_id']").val($("#event_id").val());
			$("input[name='enroll_onsite']").val( $("#onsite").is(":checked")?1:0  );
			$("input[name='enroll_trial']").val( $("#trial").is(":checked")?1:0 );
			$("input[name='enroll_group_no']").val( $("#group_no").val() );
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/event_calendar_enroll.php");
			form_register.submit();
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
	<br />
	<input type="button" id="btn_back" onclick="quick_ajax()" style="float:left; font-size:14px; font-weight:bold; margin-left:10px;" value="<?php echo $words["go back"]?>" />    <center><span class="form-header"><?php echo $words["register form"]?></span></center>
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
                  if($first && $_REQUEST["enroll_event_id"]=="") {
					  $first = false;
					  echo '<option value="' . $row["id"] . '" selected>'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . " [" . $date_str . ']</option>';
				  } else { 
					  echo '<option value="' . $row["id"] . '" ' . ($_REQUEST["enroll_event_id"]==$row["id"]?'selected':'') . '>'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . " [" . $date_str . ']</option>';
				  }
              }
              
          ?>
          </select>
          <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><?php echo $words["group"]?>: <input type="text" style="width:30px; font-size:14px; font-weight:bold; text-align:center;" id="group_no" name="group_no" value="<?php echo $_REQUEST["enroll_group_no"];?>" /></span>
          <!-- <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="onsite" name="onsite" value="1" <?php echo $_REQUEST["enroll_onsite"]?"checked":"";?> /><label for="onsite"><?php echo $words["onsite registration"]?></label></span> -->
          <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="trial" name="trial" value="1" <?php echo $_REQUEST["enroll_trial"]?"checked":"";?> /><label for="trial"><?php echo $words["trial"]?></label></span>
    </fieldset>

<?php 
include("tpl_quickform.php");
?>

<?php 
include("admin_footer_html.php");
?>

<form name="form_register" action="" method="post">
	<input type="hidden" name="lang" value="<?php echo $Glang;?>" />
	<input type="hidden" name="adminSession" value="<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>" />
	<input type="hidden" name="adminMenu" value="<?php echo $admin_menu; ?>" />
	<input type="hidden" name="enroll_event_id" value="" />
	<input type="hidden" name="enroll_onsite" value="" />
	<input type="hidden" name="enroll_trial" value="" />
	<input type="hidden" name="enroll_group_no" value="" />
</form>

</body>
</html>