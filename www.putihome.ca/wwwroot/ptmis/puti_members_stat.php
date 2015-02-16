<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="5,10";
include_once("website_admin_auth.php");
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
		<title>Bodhi Meditation Member List</title>

		<?php include("admin_head_link.php"); ?>
	
		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        
        <script language="javascript" type="text/javascript">
		$(function(){
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					stat_ajax();
				}
			});

			stat_ajax();
		});
		
		function stat_ajax() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: $("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"view",

					  sch_name: 	$("#sch_name").val(),
					  sch_phone: 	$("#sch_phone").val(),
					  sch_email: 	$("#sch_email").val(),
					  sch_gender:	$("#sch_gender").val(),
					  sch_status:	$("#sch_status").val(),
					  sch_online:	$("#sch_online").val(),
					  sch_city:		$("#sch_city").val(),
					  sch_site:		$("#sch_site").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (puti_members_stat_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						  $("#stat_content").html(req.data.html);
					  }
				  },
				  type: "post",
				  url: "ajax/puti_members_stat_select.php"
			  });
		}
		

		function print_ajax() {
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"print",
						  
					  sch_name: 	$("#sch_name").val(),
					  sch_phone: 	$("#sch_phone").val(),
					  sch_email: 	$("#sch_email").val(),
					  sch_gender:	$("#sch_gender").val(),
					  sch_status:	$("#sch_status").val(),
					  sch_online:	$("#sch_online").val(),
					  sch_city:		$("#sch_city").val(),
					  sch_site:		$("#sch_site").val()
				  },
				  dataType: "json",  
				  //contentType: "text/html; charset=utf-8",
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (puti_members_stat_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  var w1 = window.open("output.html");
					  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Student Registration</" + "title" + "></" + "head" + "><" + "body>";
					  w1.document.open();
					  w1.document.write(html_str);
					  w1.document.write(req.data.html);
					  w1.document.write('</html>');
					  w1.document.close();
					  w1.print();
				  },
				  type: "post",
				  url: "ajax/puti_members_stat_select.php"
			  });
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <fieldset>
    	<legend><?php echo $words["search filter"]?></legend>
        	<table border="0" cellpadding="0">
            	<tr>	
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["name"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["phone"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["email"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_email" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["city"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_city" value="" /></td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["gender"]?>: </td>
                                  <td>
                                      <select oper="search" style="width:120px;" id="sch_gender">
                                          <option value=""></option>
                                          <option value="Male"><?php echo $words["male"]?></option>
                                          <option value="Female"><?php echo $words["female"]?></option>
                                      </select>
                                      <span style="margin-left:20px;"></span>
                                      <?php echo $words["web"]?>: 
                                      <select oper="search" id="sch_online">
                                          <option value=""></option>
                                          <option value="1"><?php echo $words["yes"]?></option>
                                          <option value="0"><?php echo $words["no"]?></option>
                                      </select>
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["status"]?>: </td>
                                  <td>
                                      <select oper="search" style="width:120px;" id="sch_status">
                                          <option value=""></option>
                                          <option value="0"><?php echo $words["inactive"]?></option>
                                          <option value="1"><?php echo $words["active"]?></option>
                                      </select> 

                                        <span style="margin-left:20px;"></span>
										<?php echo $words["g.site"]?>: 
                                        <select id="sch_site" name="sch_site">
                                              <option value=""></option>
                                              <?php
                                                  $result_site = $db->query("SELECT id, title FROM puti_sites WHERE status = 1 AND id in " . $admin_user["sites"] . " ORDER BY id"); 
                                                  while( $row_site = $db->fetch($result_site) ) {
                                                      echo '<option value="' . $row_site["id"] . '">' . $words[strtolower($row_site["title"])] . '</option>';		
                                                  }
                                              ?>
                                        </select>
                                  </td>
                              </tr>
                          </table>
                    </td>
				</tr>
                <tr>
                    <td colspan="3" valign="middle">
                       <input type="button" right="view"  onclick="stat_ajax()" style="width:100px; vertical-align:middle;" value="<?php echo $words["search"]?>" />                  
                       <input type="button" right="print" onclick="print_ajax()" style="width:100px; margin-left:10px; vertical-align:middle;" value="<?php echo $words["button print"]?>" />                  
                    </td>
                </tr>
        </table>  
    </fieldset>
 	<div id="stat_content" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>

</body>
</html>