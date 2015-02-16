<?php
ini_set("display_errors", 1);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$query 	= "SELECT id FROM puti_members WHERE password_link = '" . $_REQUEST["sessid"] . "' AND password_exp > '" . time() . "'";
if( $db->exists($query) ) {
	$flag_bool = true;
} else {
	$flag_bool = false;
}
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
        
        <script language="javascript" type="text/javascript">
		var htmlObj = new LWH.cHTML();
		function save_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  password: 	$("input#password").val(),
						  rpassword: 	$("input#rpassword").val(),
						  password_link: "<?php echo $_REQUEST["sessid"]?>"
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (reset_password_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  
							  tool_tips(words["password change successful"]);
							  $(".login-account").hide();
						  }
					  },
					  type: "post",
					  url: "ajax/reset_password_save.php"
				  });
		}
        </script>

</head>
<body>
<?php 
include("public_menu_html_nohead.php");
?>
 	<a class="goto-event-calendar" href="index.php" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["main menu"]?></a>	<br />
	<div style="min-height:400px">
    <center>
    <?php 
	if( $flag_bool ) {
	?>
    <div class="login-account">
    	<img src="theme/blue/image/icon/login_account2.png" />
		<br /><br />
        <table border="0">
            <tr>
                <td colspan="2" align="center">
                    <span style="font-size:16px; font-weight:bold;">
                    <?php echo cTYPE::gstr($words["reset password"]); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="title" style="color:#666666; font-size:14px; font-weigh:bold;"><?php echo cTYPE::gstr($words["password"])?>: <span class="required">*</span></td>
                <td>
                    <input type="password" class="form-input" style="width:200px; font-size:12px; height:25px;" id="password" name="password" value="" />
                </td>
            </tr>
            <tr>
                <td class="title" style="color:#666666; font-size:14px; font-weigh:bold;"><?php echo cTYPE::gstr($words["confirm password"])?>: <span class="required">*</span></td>
                <td>
                    <input type="password" class="form-input" style="width:200px; font-size:12px; height:25px;" id="rpassword" name="rpassword" value="" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="button" onclick="save_ajax()" style="cursor:pointer; width:100%; height:32px; font-size:16px; border:1px solid #3079ED; color:#ffffff; background-color:#4374E0;" value="<?php echo  cTYPE::gstr($words["button save"])?>" />            	
                </td>
            </tr>
            <tr>
                <td class="line" colspan="2" align="left">
                <br />
	            </td>
            </tr>
            <tr>
                <td colspan="2" align="left">
				<br />
                </td>
            </tr>
        </table>
    </div>
    <?php 
	} else {
		echo '<span id="text_msg" style="color:red;font-size:14px;font-weight:bold;">' . cTYPE::gstr( $words["reset password link expired"] ) . '</span>';
	}
	?>
	</center>
	</div>
<?php 
include("public_footer_html_nohead.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

</body>
</html>