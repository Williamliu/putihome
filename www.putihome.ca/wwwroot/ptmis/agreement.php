<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,10";
include_once("website_admin_auth.php");

$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
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
		<title>Bodhi Meditation Online Agreement</title>
		
		<?php include("admin_head_link.php"); ?>

		<link href="../jquery/min/cleditor/jquery.cleditor.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../jquery/min/cleditor/jquery.cleditor.min.js"></script>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		var htmlDesc = null;
		$(function(){
			htmlDesc =  $("#agree_desc").cleditor({width:"95%",height:320})[0];

			$("#group_list, #group_edit").lwhTabber();
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			 words["submit success"],
				
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
			
			$("li.group-item").live("click", function(ev) {
				  var gid = $(this).attr("gid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  agreement_id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (agreement_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							$("li.group-item").removeClass("selected");
							$("li.group-item[gid='" + req.data.agreement_id + "']").addClass("selected");
							$("input#agreement_id").val(req.data.agreement_id);
							$("input#lang_id").val(req.data.lang_id);
							$("input#subject").val(req.data.subject);
							$("input#title").val(req.data.title);
							$("textarea#agree_desc").val(req.data.desc);
							htmlDesc.refresh();
							$("select#status").val(req.data.status);
						  }
					  },
					  type: "post",
					  url: "ajax/agreement_select.php"
				  });
			});
		
			new_ajax();	
		});
		function save_ajax() {
			  	  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  agreement_id: 	$("input#agreement_id").val(),
						  lang_id: 			$("input#lang_id").val(),
						  subject: 			$("input#subject").val(),
						  title: 			$("input#title").val(),
						  desc: 			$("textarea#agree_desc").val(),
						  status: 			$("select#status").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
					  	  $("#wait").loadHide();
						  alert("Error (agreement_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
					  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							if( req.data.old_id < 0 ) {
								$("ul#group_items").append('<li class="group-item" gid="' + req.data.agreement_id + '">' + req.data.title  + '</li>');
								$("li.group-item").removeClass("selected");
								$("li.group-item[gid='" + req.data.agreement_id + "']").addClass("selected");
							} else {
								$("li.group-item[gid='" + req.data.agreement_id + "']").html(req.data.subject);
							}
							$("input#agreement_id").val(req.data.agreement_id);
						  }
					  },
					  type: "post",
					  url: "ajax/agreement_save.php"
				  });
		}

		
		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#agreement_id").val(-1);
			$("input#lang_id").val(-1);
			$("input#subject").val("");
			$("input#title").val("");
			$("textarea#agree_desc").val("");
			htmlDesc.refresh();
			$("select#status").val("");
		}

		function del_ajax() {
			if( $("input#agreement_id").val() < 0 ) return;
			var yes = false;
			if( yes = window.confirm( words["are you sure to delete this record?"] ) ) {
			  	  	$("#wait").loadShow();
					$.ajax({
						data: {
							admin_sess: $("input#adminSession").val(),
							admin_menu:	$("input#adminMenu").val(),
							admin_oper:	"delete",

							agreement_id: $("input#agreement_id").val()
						},
						dataType: "json",  
						error: function(xhr, tStatus, errorTh ) {
					  	  	$("#wait").loadHide();
							alert("Error (agreement_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						},
						success: function(req, tStatus) {
					  	  	$("#wait").loadHide();
							if( req.errorCode > 0 ) { 
								errObj.set(req.errorCode, req.errorMessage, req.errorField);
								return false;
							} else {
								$("li.group-item[gid='" + req.data.agreement_id + "']").remove();						
								new_ajax();
								//$("#lwhDiag-msg", "#diaglog_message").html(req.errorMessage);
								//$("#diaglog_message").diagShow({title:"Submit Success"}); 
							}
						},
						type: "post",
						url: "ajax/agreement_delete.php"
					});
			}
		}
    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
        	<tr>
            	<td valign="top" width="280px">
                    <div id="group_list" class="lwhTabber lwhTabber-goldenrod" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["agreement details"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:450px;">
                            <div id="groups" style="height:450px; overflow-x:hidden; overflow-y:auto;">
                            	<?php
									ob_start();
									$result = $db->query("SELECT a.id as agreement_id, a.subject, b.id as lang_id, b.title FROM puti_agreement a LEFT JOIN (SELECT * FROM  puti_agreement_lang WHERE lang = '" . $admin_user["lang"] . "') b ON (a.id = b.agreement_id) WHERE a.deleted <> 1 ORDER BY created_time");
									echo '<ul class="group-items" id="group_items">';
									while( $row = $db->fetch($result) ) {
										echo '<li class="group-item" gid="' . $row["agreement_id"] . '">' . $row["subject"] . '</li>';
									}
									echo '</ul>';
									ob_end_flush();
								?>
                            </div>
                        </div>
                    </div>
                </td>
            	<td valign="top" width="auto">
                    <div id="group_edit" class="lwhTabber lwhTabber-fuzzy" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["agreement details"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:450px;">
                            <div id="group_item">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                                                <tr>
                                                    <td class="title"><?php echo $words["purpose"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input type="hidden" id="agreement_id" name="agreement_id" value="-1" />
                                                        <input type="hidden" id="lang_id" name="lang_id" value="-1" />
                                                        <input class="form-input" style="width:95%" id="subject" name="subject" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["title"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input class="form-input" style="width:95%" id="title" name="title" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["description"]?>: </td>
                                                    <td>
                                                         <textarea id="agree_desc"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["status"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <select id="status" name="status">
                                                            <option value=""></option>
                                                            <option value="0"><?php echo $words["inactive"]?></option>
                                                            <option value="1"><?php echo $words["active"]?></option>
                                                        </select>
                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
		                                                 <center>
                                                         	<input type="button" right="save" onclick="save_ajax()" value="<?php echo $words["button save"]?>" />
                                                            <input type="button" right="add" id="btn_new" onclick="new_ajax()" value="<?php echo $words["button add"]?>" />
                                                            <input type="button" right="delete" id="btn_del" onclick="del_ajax()" value="<?php echo $words["button delete"]?>" />
                                                         </center>
                                                    </td>
                                                </tr>
                                            </table> 
                                            <!-- end of group detail -->
                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
                </td>
            </tr>    
        </table>
	</div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

</body>
</html>