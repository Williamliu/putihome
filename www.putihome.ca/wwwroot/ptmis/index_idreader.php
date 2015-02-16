<?php
//header("Location: http://mis.putihome.ca");
//exit();
ini_set("display_errors", 0);
session_start();
$_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]="";
//session_destroy();
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/config/web_language.php");

$goPage = $CFG["http"] . $CFG["admin_domain"] . "/event_calendar_id_reader.php";
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

		<style>
		  div.main-layout {
			  padding:		0px;
			  margin:		auto;
			  width:		840px;
			  
			  border:		0px solid #666666;
			  box-shadow:	0px 1px 1px #666666;
		  }
		</style>
    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#login_pwd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					login_ajax();
				}
			});

			$("#login_name").focus();
		});

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
include("admin_index_idreader.php");
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
<form name="frm_pass" action="<?php echo $goPage;?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="adminSession" value="" />
</form>
</body>
</html>