<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,155";
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
		$(function(){
			$("#group_list, #group_edit").lwhTabber();
			
			$("#dharma_date").datepicker({ 
							  dateFormat: 'yy-mm-dd',  
							  showOn: "button",
							  buttonImage: "../theme/blue/image/icon/calendar.png",
							  buttonImageOnly: true  
			});
			
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
						  
						  dharma_id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (puti_dharma_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							$("li.group-item").removeClass("selected");
							$("li.group-item[gid='" + req.data.dharma_id + "']").addClass("selected");
							$("input#dharma_id").val(req.data.dharma_id);
							$("input#dharma_prefix").val(req.data.dharma_prefix);
							$("input#dharma_date").val(req.data.dharma_date);
							$("select#dharma_site").val(req.data.dharma_site);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_dharma_select.php"
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

						  dharma_id: 		$("input#dharma_id").val(),
						  dharma_prefix: 	$("input#dharma_prefix").val(),
						  dharma_date: 		$("input#dharma_date").val(),
						  dharma_site: 		$("select#dharma_site").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
					  	  $("#wait").loadHide();
						  alert("Error (puti_dharma_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
					  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							if( req.data.old_id < 0 ) {
								$("ul#group_items").append('<li class="group-item" gid="' + req.data.dharma_id + '">' + req.data.dharma_prefix  + '</li>');
								$("li.group-item").removeClass("selected");
								$("li.group-item[gid='" + req.data.dharma_id + "']").addClass("selected");
							} else {
								$("li.group-item[gid='" + req.data.dharma_id + "']").html(req.data.dharma_prefix);
							}
							$("input#dharma_id").val(req.data.dharma_id);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_dharma_save.php"
				  });
		}

		
		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#dharma_id").val(-1);
			$("input#dharma_prefix").val("");
			$("input#dharma_date").val("");
			$("select#dharma_site").val(0);
		}

		function del_ajax() {
			if( $("input#dharma_id").val() < 0 ) return;
			var yes = false;
			if( yes = window.confirm( words["are you sure to delete this record?"] ) ) {
			  	  	$("#wait").loadShow();
					$.ajax({
						data: {
							admin_sess: $("input#adminSession").val(),
							admin_menu:	$("input#adminMenu").val(),
							admin_oper:	"delete",

							dharma_id: $("input#dharma_id").val()
						},
						dataType: "json",  
						error: function(xhr, tStatus, errorTh ) {
					  	  	$("#wait").loadHide();
							alert("Error (puti_dharma_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						},
						success: function(req, tStatus) {
					  	  	$("#wait").loadHide();
							if( req.errorCode > 0 ) { 
								errObj.set(req.errorCode, req.errorMessage, req.errorField);
								return false;
							} else {
								$("li.group-item[gid='" + req.data.dharma_id + "']").remove();						
								new_ajax();
								//$("#lwhDiag-msg", "#diaglog_message").html(req.errorMessage);
								//$("#diaglog_message").diagShow({title:"Submit Success"}); 
							}
						},
						type: "post",
						url: "ajax/puti_dharma_delete.php"
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
                            <a><?php echo $words["dharma prefix"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:450px;">
                            <div id="groups" style="height:450px; overflow-x:hidden; overflow-y:auto;">
                            	<?php
									ob_start();
									$result = $db->query("SELECT id, dharma_prefix FROM puti_dharma ORDER BY dharma_date");
									echo '<ul class="group-items" id="group_items">';
									while( $row = $db->fetch($result) ) {
										echo '<li class="group-item" gid="' . $row["id"] . '">' . cTYPE::gstr($row["dharma_prefix"]) . '</li>';
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
                            <a><?php echo $words["dharma prefix"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:450px;">
                            <div id="group_item">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                                                <tr>
                                                    <td class="title"><?php echo $words["dharma prefix"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input type="hidden" id="dharma_id" name="dharma_id" value="-1" />
                                                        <input class="form-input" style="width:60px" id="dharma_prefix" name="dharma_prefix" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["dharma date"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:100px;" id="dharma_date" name="dharma_date" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["baishi.site"]?>: </td>
                                                    <td>
                                                        <select id="dharma_site" name="dharma_site">
                                                        <?php
															$result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 ORDER BY id");
															echo '<option value="0"></option>';
															while($row = $db->fetch($result)) {
																echo '<option value="' . $row["id"] . '">' . $words[strtolower($row["title"])] . '</option>';
															}
														?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="line">
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