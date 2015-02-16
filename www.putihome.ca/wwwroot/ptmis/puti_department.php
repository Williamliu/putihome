<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="10,10";
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
		<title>Bodhi Meditation Department</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#group_list, #group_edit").lwhTabber();

			$("li.group-item").live("click", function(ev) {
				  $("#wait").loadShow();
				  var gid = $(this).attr("gid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_department_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							$("li.group-item").removeClass("selected");
							$("li.group-item[gid='" + req.data.id + "']").addClass("selected");
							$("input#hid").val(req.data.id);
							$("input#title").val(req.data.title);
							$("input#en_title").val(req.data.en_title);
							$("textarea#depart_desc").val(req.data.description);
							$("select#status").val(req.data.status);
							$("input#sn").val(req.data.sn);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_department_select.php"
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

						  id: 			$("input#hid").val(),
						  title: 		$("input#title").val(),
						  en_title: 	$("input#en_title").val(),
						  description: 	$("textarea#depart_desc").val(),
						  status: 		$("select#status").val(),
						  sn: 			$("input#sn").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_department_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							if( req.data.old_id < 0 ) {
								$("ul#group_items").append('<li class="group-item" gid="' + req.data.id + '">' + req.data.title + '</li>');
								$("li.group-item").removeClass("selected");
								$("li.group-item[gid='" + req.data.id + "']").addClass("selected");
							} else {
								$("span", "li.group-item[gid='" + req.data.id + "']").html(req.data.title + (req.data.en_title!=""?" - " + req.data.en_title:"") );
							}
							$("input#hid").val(req.data.id);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_department_save.php"
				  });
		}
		
		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#hid").val(-1);
			$("input#title").val("");
			$("input#en_title").val("");
			$("textarea#depart_desc").val("");
			$("select#status").val(1);
			$("input#sn").val("");
		}

		function del_ajax() {
			if( $("input#admin_id").val() < 0 ) return;
			var yes = false;
			if( yes = window.confirm("Are you sure to delete this record?") ) {
					$("#wait").loadShow();
					$.ajax({
						data: {
							admin_sess: $("input#adminSession").val(),
							admin_menu:	$("input#adminMenu").val(),
							admin_oper:	"delete",

							id: $("input#hid").val()
						},
						dataType: "json",  
						error: function(xhr, tStatus, errorTh ) {
							$("#wait").loadHide();
							alert("Error (puti_department_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						},
						success: function(req, tStatus) {
							$("#wait").loadHide();
							if( req.errorCode > 0 ) { 
								errObj.set(req.errorCode, req.errorMessage, req.errorField);
								return false;
							} else {
								$("li.group-item[gid='" + req.data.id + "']").remove();						
								new_ajax();
							}
						},
						type: "post",
						url: "ajax/puti_department_delete.php"
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
                            <a><?php echo $words["department"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:350px;">
                            <div id="groups" style="height:350px; overflow-x:hidden; overflow-y:auto;">
                            	<?php
									ob_start();
									$result = $db->query("SELECT id, title, en_title, description, status FROM puti_department WHERE deleted <> 1 ORDER BY sn DESC, title");
									echo '<ul class="group-items" id="group_items">';
									$cnt=0;
									while( $row = $db->fetch($result) ) {
										$cnt++;
										echo '<li class="group-item" gid="' . $row["id"] . '">' . $cnt . '. <span>' .  cTYPE::gstr($row["title"]) . ($row["en_title"]!=""?" - " . cTYPE::gstr($row["en_title"]):"") . '</span></li>';
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
                            <a><?php echo $words["selected department"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:350px;">
                            <div id="group_item">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                    <td class="title"><?php echo $words["department"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input type="hidden" id="hid" name="hid" value="-1" />
                                                        <input class="form-input" id="title" name="title" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["department en"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="en_title" name="en_title" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["description"]?>: </td>
                                                  <td style="white-space:nowrap; width:250px;"><textarea id="depart_desc" style="width:100%; height:60px; resize:none;"></textarea></td>
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
                                                    <td class="title"><?php echo $words["sn"]?>: <span class="required">*</span></td>
                                                    <td>
	                                                    <input class="form-input" id="sn" name="sn" style="width:30px; text-align:center;" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><br />
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
</body>
</html>