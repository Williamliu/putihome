<?php
ini_set("display_errors", 0);
session_start();
$_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]="";
//session_destroy();
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/config/web_language.php");

if($_SERVER['HTTP_REFERER']!="") {
	$goPage = $_SERVER['HTTP_REFERER'];
} else {
	$goPage = $CFG["http"] . $CFG["admin_domain"] . "/website_myaccount.php";
}
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
		<title>Bodhi Meditation Administration Website Login</title>

		<?php include("admin_head_link.php"); ?>

    	<script type="text/javascript" language="javascript">
		$(function(){
			if( $("form[name='frm_pass']").attr("action") == "" || $("form[name='frm_pass']").attr("action") == "http://<?php echo $CFG["admin_domain"]?>/admin/" || $("form[name='frm_pass']").attr("action") == '<?php echo $CFG["admin_login_webpage"];?>') {
				$("form[name='frm_pass']").attr("action", 'http://<?php echo $CFG["admin_domain"]?>/website_myaccount.php');
			}
			
			$("#diaglog").lwhDiag({
				titleAlign:		"center",
				title:			 words["error message"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			400,
				minHH:			250,
				btnMax:			false,
				resizable:		false,
				movable:		false,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});
			
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
				movable:		false,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			$("#login_pwd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					login_ajax();
				}
			});

			$("#login_name").focus();
		});


		function save_ajax() {
				  $.ajax({
					  data: {
						  first_name: 	$("input#first_name").val(),
						  last_name: 	$("input#last_name").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  city: 		$("input#city").val(),
						  user_name: 	$("input#user_name").val(),
						  email: 		$("input#email").val(),
						  password: 	$("input#password").val(),
						  repassword: 	$("input#repassword").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (website_login_registration_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							frm_new.reset();
							$(".lwhDiag-content", "#diaglog").html( req.errorMessage.nl2br() );
							$("#diaglog").diagShow({title: words["submit success"]}); 
						  }
					  },
					  type: "post",
					  url: "ajax/website_login_registration_save.php"
				  });
		}

		function getpwd() {
			$("#diaglog_pwd").diagShow();		
		}
		
		function savepwd_ajax() {
				  $.ajax({
					  data: {
						  login_email: 	$("input#login_email").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (website_login_email_password.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$("input#login_email").val("");
							$("#diaglog_pwd").diagHide();
							$(".lwhDiag-content", "#diaglog").html( req.errorMessage.nl2br() );
							$("#diaglog").diagShow({title: words["retrieve password successful."]}); 
						  }
					  },
					  type: "post",
					  url: "ajax/website_login_email_password.php"
				  });
		}

		function login_ajax() {
				  $.ajax({
					  data: {
						  login_name: 	$("input#login_name").val(),
						  login_pwd: 	$("input#login_pwd").val(),
						  platform: 	$("select#platform").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (website_login_session.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  $("input[name='adminSession']").val(req.data.sess_id);
							  frm_pass.submit();
						  }
					  },
					  type: "post",
					  url: "ajax/website_login_session.php"
				  });
		}
    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_index_html.php");
?>
    <div style="display:block; padding:5px;">
    	<table border="0" cellpadding="4" cellspacing="0" width="100%">
        	<tr>
            	<td width="100%" valign="top">
                        <!-- existing accout login -->                      
                        <div class="content-box" style="width:100%;">
                            <div class="content-box-head"><?php echo $words["login system"]?></div>
                            <div class="content-box-content" align="center">
                                  <table border="0" cellpadding="2" cellspacing="0">
                                      <tr>
                                          <td colspan="2" align="left" style="padding-left:30px; color:blue;">
                                              <?php echo $words["login info tips"]?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td class="title"><?php echo $words["user name"]?>: <span class="required">*</span></td>
                                          <td>
                                              <input class="form-input" style="width:150px;" id="login_name" name="login_name" value="" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td valign="top" class="title"><?php echo $words["password"]?>: <span class="required">*</span></td>
                                          <td>
                                              <input type="password" class="form-input" style="width:150px;" id="login_pwd" name="login_pwd" value="" />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td valign="top" class="title"><?php echo $words["platform"]?>: <span class="required">*</span></td>
                                          <td>
                                          	 	<select id="platform" name="platform" style="width:150px;">
                                                	<option value="production" selected><?php echo $words["production"]?></option>
                                                    <option value="beta"><?php echo $words["test"]?></option>
                                                </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="2"><br />
                                               <center><input type="button" onClick="login_ajax()" value="<?php echo $words["button login"]?>" /></center>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="2"><br />
                                               <center style="color:blue;"><?php echo $words["forget password?"]?>:<input type="button" id="btn_pwd" name="btn_pwd" onClick="getpwd()" value="<?php echo $words["retrieve password"]?>" /></center>
                                          </td>
                                      </tr>
                                  </table> 
                            </div>
                        </div>
			            <!-- end of existing accout login -->                      
				</td>
			</tr>
      	</table>                
<?php 
include("admin_footer_html.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<div id="diaglog" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

<div id="diaglog_pwd" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<table border="0" cellpadding="4" cellspacing="0">
              <tr>
                  <td colspan="2" align="center">
                      <span style="color:blue;"><?php echo $words["password sent to you"]?></span>
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
                       <input type="button" onClick="savepwd_ajax()" value="<?php echo $words["retrieve password"]?>" />
                  </td>
              </tr>
        </table>
	</div>
</div>
<form name="frm_pass" action="<?php echo $goPage;?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="adminSession" value="" />
</form>
</body>
</html>