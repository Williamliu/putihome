<?php
session_start();
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

if($_REQUEST["loginform_event_id"]=="") {
	header("Location: " . $CFG["http"]. $CFG["web_domain"]);
}
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$query = "SELECT c.template as logform FROM event_calendar a  INNER JOIN puti_class b ON (a.class_id = b.id) INNER JOIN puti_forms c ON(b.logform = c.id) WHERE a.id = '" . $_REQUEST["loginform_event_id"] . "'";
$result = $db->query($query);
$row = $db->fetch($result);
$logform = $row["logform"];

include_once("public_user_auth.php");
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
		//alert("<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>");
		$(function(){

			  $("#diaglog_pwd").lwhDiag({
				  titleAlign:		"center",
				  title:			 words["retrieve password"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			400,
				  minHH:			130,
				  btnMax:			false,
				  resizable:		false,
				  movable:			false,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });
		  
			  $("#login_pwd").bind("keydown", function(ev) {
					  if( ev.keyCode == 13 ) {
						  login_ajax();
					  }
			  });
		});

		function getpwd() {
		    $("#login_email").val( $("#login_name").val() );
			$("#diaglog_pwd").diagShow();		
		}
		
		function savepwd_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  login_email: 	$("#login_email").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (public_login_email_password.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  $("input#login_email").val("");
						  $("#diaglog_pwd").diagHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							tool_tips(words["reset password link has been sent to you"]);
						  }
					  },
					  type: "post",
					  url: "ajax/public_login_email_password.php"
				  });
		}

		function goback() {
			window.location.href = "<?php echo $_REQUEST["prev_url"];?>";
		}

		function login_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  login_name: 	$("input#login_name").val(),
						  login_pwd: 	$("input#login_pwd").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (public_login_session.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
								
								if( req.errorCode == 10 ) {
									$(".lwhDiag-content", "#diaglog_error").html(words["login email password error"]);
									$("#diaglog_error").diagShow(); 
								}

								if( req.errorCode == 11 ) {
									$(".lwhDiag-content", "#diaglog_error").html(words["login wrong password"]);
									$("#diaglog_error").diagShow(); 
								}

								if( req.errorCode == 12 ) {
									$(".lwhDiag-content", "#diaglog_error").html(words["login wrong email"]);
									$("#diaglog_error").diagShow(); 
								}
								
								if( req.errorCode > 12 ) { 
									errObj.set(req.errorCode, req.errorMessage, req.errorField);
									return false;
								} 						
						  } else {
							  $("input[name='publicSession']").val(req.data.sess_id);
							  tool_tips(words["login successful"]);
							  personalform.submit();
						  }
					  },
					  type: "post",
					  url: "ajax/public_login_session.php"
				  });
		}
		
		function create_new() {
			  $("input[name='publicSession']").val(-1);
			  personalform.submit();			  
		}
        </script>

</head>
<body>
<?php 
include("public_menu_html_nohead.php");
?>
 	<a class="goto-event-calendar" href="javascript:goback();" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["go back"]?></a>
 	<a class="goto-event-calendar" href="index.php" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["main menu"]?></a>	<br />
	<br />
	<div style="min-height:400px">
    <center>
    <div class="login-account">
    	<img src="theme/blue/image/icon/login_account.png" style="border-radius:50%;" />
		<br /><br />
        <table border="0">
            <tr>
                <td colspan="2" align="center">
                    <span style="font-size:16px; font-weight:bold;">
                    <?php echo cTYPE::gstr($words["please login with your email"]); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="title" style="color:#666666; font-size:14px; font-weigh:bold;"><?php echo cTYPE::gstr($words["email"])?>: <span class="required">*</span></td>
                <td>
                    <input class="form-input" style="width:200px; font-size:12px; height:25px;" id="login_name" name="login_name" value="" />
                </td>
            </tr>
            <tr>
                <td class="title" style="color:#666666; font-size:14px; font-weigh:bold;"><?php echo cTYPE::gstr($words["password"])?>: <span class="required">*</span></td>
                <td>
                    <input type="password" class="form-input" style="width:200px; font-size:12px; height:25px;" id="login_pwd" name="login_pwd" value="" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="button" onclick="login_ajax()" style="cursor:pointer; width:100%; height:32px; font-size:16px; border:1px solid #3079ED; color:#ffffff; background-color:#4374E0;" value="<?php echo  cTYPE::gstr($words["button login"])?>" />            	
                </td>
            </tr>
            <tr>
                <td class="line" colspan="2" align="left" style="padding-top:20px;">
                	<a href="javascript:getpwd();" style="color:#4374E0; font-size:14px;font-weight:bold; text-decoration:none;"><?php echo cTYPE::gstr($words["forgot password"])?></a>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="left" style="padding-top:20px; padding-bottom:20px;">
                	<a href="javascript:create_new();" style="color:#4374E0; font-size:14px;font-weight:bold; text-decoration:none;"><?php echo cTYPE::gstr($words["create new account"])?></a>
                </td>
            </tr>
        </table>
    </div>
    </center>
	</div>
<?php 
include("public_footer_html_nohead.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<div id="diaglog_pwd" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<table border="0" cellpadding="4" cellspacing="0">
              <tr>
                  <td colspan="2" align="center">
                      <span style="color:blue;"><?php echo $words["reset password info"]?></span>
                  </td>
              </tr>
              <tr>
                  <td class="title"><?php echo $words["email"]?>: <span class="required">*</span></td>
                  <td>
                      <input  style="width:200px;" id="login_email" name="login_email" value="" />
                  </td>
              </tr>
              <tr>
              	  <td></td>
                  <td align="left">
                       <input type="button" onClick="savepwd_ajax()" value="<?php echo $words["send email"]?>" />
                  </td>
              </tr>
        </table>
	</div>
</div>



<form name="personalform" action="<?php echo $CFG["http"] . $CFG["web_domain"] . '/' .$logform ?>" method="get">
	<input type="hidden" id="personalform_event_id" name="personalform_event_id" value="<?php echo $_REQUEST["loginform_event_id"];?>" />
    <input type="hidden" name="publicSession" value="" />
    <input type="hidden" name="prev_url" value="<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>" />
</form>
</body>
</html>