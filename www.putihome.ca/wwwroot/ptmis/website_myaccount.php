<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="800,10";
include_once("website_admin_auth.php");

$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$result = $db->query("SELECT * FROM website_admins WHERE deleted <> 1 AND id = '" . $admin_user["id"] . "'");
$row = $db->fetch($result);
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
		<title>Bodhi Meditation My Account</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#group_edit").lwhTabber();

			$("#diaglog_pwd").lwhDiag({
				titleAlign:		"center",
				title:			"更改密码",
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			320,
				minHH:			130,
				btnMax:			false,
				resizable:		false,
				movable:		false,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			$("#btn_pwd").live("click", function(ev) {
				$("#diaglog_pwd").diagShow();
			});
		});
	
		function save_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  admin_id: 	$("input#admin_id").val(),
						  first_name: 	$("input#first_name").val(),
						  last_name: 	$("input#last_name").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  city: 		$("input#city").val(),
						  user_name: 	$("input#user_name").val(),
						  email: 		$("input#email").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_admins_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
                            tool_tips(words["save success"]);
							$("#admin_last_updated").html(req.data.last_updated);
						  }
					  },
					  type: "post",
					  url: "ajax/website_myaccount_save.php"
				  });
		}

		function savepwd_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  admin_id: 	$("input#admin_id").val(),
						  password: 	$("input#password").val(),
						  repassword: 	$("input#repassword").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_admins_pwd_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
                            tool_tips(words["save success"]);
							$("input#password").val("");
							$("input#repassword").val("");
							$("#diaglog_pwd").diagHide();
							$("#admin_last_updated").html(req.data.last_updated);
						  }
					  },
					  type: "post",
					  url: "ajax/website_admins_pwd_save.php"
				  });
		}
    	</script>
</head>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
                    <div id="group_edit" class="lwhTabber lwhTabber-smitten" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["my account"]; ?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content">
                            <div id="group_item">
                                <table cellpadding="2" width="100%">
                                	<tr>
                                    	<td valign="top" width="350">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                    <td class="title"><?php echo $words["first name"]; ?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input type="hidden" id="admin_id" name="admin_id" value="<?php echo $row["id"]; ?>" />
                                                        <input class="form-input" id="first_name" name="first_name" value="<?php echo cTYPE::gstr($row["first_name"]); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["last name"]; ?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input class="form-input" id="last_name" name="last_name" value="<?php echo cTYPE::gstr($row["last_name"]); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["dharma name"]; ?>: </td>
                                                    <td>
                                                        <input class="form-input" id="dharma_name" name="dharma_name" value="<?php echo cTYPE::gstr($row["dharma_name"]); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["phone"]; ?>: </td>
                                                    <td>
                                                        <input class="form-input" id="phone" name="phone" value="<?php echo $row["phone"]; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["cell"]; ?>: </td>
                                                    <td>
                                                        <input class="form-input" id="cell" name="cell" value="<?php echo $row["cell"]; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["city"]; ?>: </td>
                                                    <td>
                                                        <input class="form-input" id="city" name="city" value="<?php echo cTYPE::gstr($row["city"]); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="line" colspan="2" align="center">
                                                    	<span style="color:red;"><?php echo $words["login info tips"]; ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["login name"]; ?>: <span class="required">*</span></td>
                                                    <td><span style="font-size:14px; font-weight:bold">
                                                        <?php echo $row["user_name"]; ?>
                                                        </span>
                                                        <input type="hidden" class="form-input" id="user_name" name="user_name" value="<?php echo $row["user_name"]; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["email"]; ?>: </td>
                                                    <td>
                                                        <input class="form-input" id="email" name="email" value="<?php echo $row["email"]; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="center">
														<input type="button" id="btn_pwd" right="save" name="btn_pwd" value="<?php echo $words["set password"]; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title line"><?php echo $words["role"]; ?>: </td>
                                                    <td class="line">
                                                        <?php
															$result_grp = $db->query("SELECT id, name FROM website_groups WHERE deleted <> 1 AND id = '" . $row["group_id"] . "'");
															$row_grp = $db->fetch($result_grp);
															echo $row_grp["name"];
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo  $words["r.teaching"]; ?>: </td>
                                                    <td>
                                                        <?php
															$result_grp = $db->query("SELECT id, title FROM puti_branchs WHERE id = '" . $admin_user["branch"] . "'");
															$row_grp = $db->fetch($result_grp);
															echo $words[strtolower($row_grp["title"])];
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["status"]; ?>: </td>
                                                    <td>
                                                       <?php
													    	echo $row["status"]==1?$words["active"]:$words["inactive"];
													   ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
		                                                 <center><input type="button" right="save" onclick="save_ajax()" value="<?php echo cTYPE::gstr($words["button save"])?>" /></center>
                                                    </td>
                                                </tr>
                                            </table> 
                                            <!-- end of group detail -->
                                      </td>
                                      <td valign="top" width="50%">
                                      		<table border="0" cellpadding="2" style="margin-left:20px;">
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["created time"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_created_time"><?php echo cTYPE::inttodate($row["created_time"]); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["last updated"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_last_updated"><?php echo cTYPE::inttodate($row["last_updated"]); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["last login"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_last_login"><?php echo cTYPE::inttodate($row["last_login"]); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["login count"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_hits"><?php echo $row["hits"]; ?></span>
                                                    </td>
                                                </tr>
                                            </table>
                                      </td>     
                                  	</tr>
                            	</table>       
                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
	</div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_pwd" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<table border="0" cellpadding="4" cellspacing="0">
              <tr>
                  <td colspan="2" align="center">
                      <span style="color:red;"><?php echo $words["password length tips"]?></span>
                  </td>
              </tr>
              <tr>
                  <td class="title"><?php echo $words["password"]?>: <span class="required">*</span></td>
                  <td>
                      <input type="password" style="width:120px;" id="password" name="password" value="" />
                  </td>
              </tr>
              <tr>
                  <td valign="top" class="title"><?php echo $words["confirm password"]?>: <span class="required">*</span></td>
                  <td>
                      <input type="password" style="width:120px;" id="repassword" name="repassword" value="" />
                  </td>
              </tr>
              <tr>
              	  <td></td>
                  <td align="left">
                       <input type="button" right="save"  onclick="savepwd_ajax()" value="<?php echo $words["button save"]?>" />
                  </td>
              </tr>
        </table>
	</div>
</div>

</body>
</html>