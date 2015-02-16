<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="800,30";
include_once("website_admin_auth.php");

$str_menu = json_encode($menu);
$str_right = json_encode($right);
$str_user = json_encode($r1);

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
		<title>Bodhi Meditation Admin User Rights</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
 
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />
    	<script type="text/javascript" language="javascript">

		var menuObj = new LWH.CMENU('<?php echo $str_menu; ?>','<?php echo $str_right; ?>', '');
		$(function(){
			$("#group_list, #group_edit").lwhTabber();
			$("#menu_area").html( menuObj.toHTML());
			$("#website_menu_right").lwhTree({single:true});

			$("li.group-item").live("click", function(ev) {
		  		  $("#wait").loadShow();
				  var gid = $(this).attr("gid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",

						  group_id: gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_groups_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$("li.group-item").removeClass("selected");
							$("li.group-item[gid='" + req.data.group_id + "']").addClass("selected");
							$("input#group_id").val(req.data.group_id);
							$("input#group_name").val(req.data.group_name);
							$("textarea#group_desc").val(req.data.group_desc);
							$("select#group_status").val(req.data.group_status);
							$("select#level").val(req.data.level);
							$("#group_created_time").html(req.data.created_time);
							$("#group_last_updated").html(req.data.last_updated);
							menuObj.setRight(req.data.group_right);
							$("#menu_area").html( menuObj.toHTML());
							$("#website_menu_right").lwhTree({single:true});
							
						  }
					  },
					  type: "post",
					  url: "ajax/website_groups_select.php"
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

						  group_id: 	$("input#group_id").val(),
						  group_name: 	$("input#group_name").val(),
						  group_desc: 	$("textarea#group_desc").val(),
						  group_status: $("select#group_status").val(),
						  level: 		$("select#level").val(),
						  group_right:	jsonStr(menuObj.getRight())
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_groups_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
                            tool_tips(words["save success"]);
							if( req.data.old_id < 0 ) {
								$("ul#group_items").append('<li class="group-item" gid="' + req.data.group_id + '">' + req.data.group_title + '</li>');
								$("li.group-item").removeClass("selected");
								$("li.group-item[gid='" + req.data.group_id + "']").addClass("selected");
								$("#group_created_time").html(req.data.created_time);
							} else {
								$("li.group-item[gid='" + req.data.group_id + "']").html(req.data.group_title);
								$("#group_last_updated").html(req.data.last_updated);
							}
							$("input#group_id").val(req.data.group_id);
						  }
					  },
					  type: "post",
					  url: "ajax/website_groups_save.php"
				  });
		}
		
		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#group_id").val("-1");
			$("input#group_name").val("");
			$("textarea#group_desc").val("");
			$("select#group_status").val("");
			$("select#level").val("");
			$("#group_created_time").html("");
			$("#group_last_updated").html("");
			menuObj.setRight("");
			$("#menu_area").html( menuObj.toHTML());
			$("#website_menu_right").lwhTree({single:true});
		}

		function del_ajax() {
			if( $("input#group_id").val() < 0 ) return;
			var yes = false;
			if( yes = window.confirm("Are you sure to delete this record?") ) {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",

						  group_id: $("input#group_id").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_groups_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
                              tool_tips(words["delete success"]);
							  $("li.group-item[gid='" + req.data.group_id + "']").remove();						
							  new_ajax();
						  }
					  },
					  type: "post",
					  url: "ajax/website_groups_delete.php"
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
            	<td valign="top" width="200px">
                    <div id="group_list" class="lwhTabber lwhTabber-smitten" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["roles"]; ?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="min-height:400px;">
                            <div id="groups" style="min-height:400px; overflow-x:hidden; overflow-y:auto;">
                            	<?php
									ob_start();
									$result = $db->query("SELECT id, name, level FROM website_groups WHERE deleted <> 1 ORDER BY level DESC, name ASC");
									echo '<ul class="group-items" id="group_items">';
									$cnt = 0;
									while( $row = $db->fetch($result) ) {
										$cnt++;
										echo '<li class="group-item" gid="' . $row["id"] . '">' . ($row["level"]?$row["level"]:'0') . '. ' . $row["name"] . '</li>';
									}
									echo '</ul>';
									ob_end_flush();
								?>
                            </div>
                        </div>
                    </div>
                </td>
            	<td valign="top" width="auto">
                    <div id="group_edit" class="lwhTabber lwhTabber-mint" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["details"]; ?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="min-height:400px;">
                            <div id="group_item">
                            	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                                	<tr>
                                    	<td valign="top" width="220px">
                                        	<!-- group detail -->
                                            	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                                                	<tr>
                                                    	<td class="title"><?php echo $words["group name"]; ?>:<span class="required">*</span></td>
                                                        <td width="160">
                                                        	<input type="hidden" id="group_id" name="group_id" value="-1" />
                                                            <input class="form-input" id="group_name" name="group_name" style="width:150px;" value="" />
                                                        </td>
                                                    </tr>
                                                	<tr>
                                                    	<td valign="top" class="title"><?php echo $words["description"]; ?>:</td>
                                                        <td width="160">
                                                        	<textarea id="group_desc" name="group_desc" style="width:150px;height:120px;resize:none;"></textarea>
                                                        </td>
                                                    </tr>
                                                	<tr>
                                                    	<td class="title"><?php echo $words["status"]; ?>：<span class="required">*</span></td>
                                                        <td width="160">
                                                        	<select id="group_status" name="group_status" style="width:100px; text-align:center;">
                                                            	<option value=""></option>
                                                            	<option value="0"><?php echo $words["inactive"]; ?></option>
                                                            	<option value="1"><?php echo $words["active"]; ?></option>
                                                            </select>
	                                                    	
                                                        </td>
                                                    </tr>
                                                	<tr>
                                                    	<td class="title"><?php echo $words["right class"]; ?>：<span class="required">*</span></td>
                                                        <td width="160">
                                                        	<select id="level" name="level" style="width:100px; text-align:center;">
                                                            	<option value=""></option>
                                                            	<option value="1">Level 1</option>
                                                            	<option value="2">Level 2</option>
                                                            	<option value="3">Level 3</option>
                                                            	<option value="4">Level 4</option>
                                                            	<option value="5">Level 5</option>
                                                            	<option value="6">Level 6</option>
                                                            	<option value="7">Level 7</option>
                                                            	<option value="8">Level 8</option>
                                                            	<option value="9">Level 9</option>
                                                            </select>
	                                                    	
                                                        </td>
                                                    </tr>
                                                	<tr>
                                                    	<td class="title"><br /><?php echo $words["created time"]; ?>:</td>
                                                        <td width="160"><br />
                                                        	<span id="group_created_time"></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                    	<td class="title"><?php echo $words["last updated"]; ?>:</td>
                                                        <td width="160">
                                                        	<span id="group_last_updated"></span>
                                                        </td>
                                                    </tr>
                                                </table> 
                                                <center>
                                                	<input type="button" right="save" onclick="save_ajax()" style="margin-top:20px;" value="<?php echo $words["button save"]; ?>" />
                                                    <input type="button" right="add" id="btn_new" onclick="new_ajax()" value="<?php echo $words["button add"]; ?>" />
                                                    <input type="button" right="delete" id="btn_del" onclick="del_ajax()" value="<?php echo $words["button delete"]; ?>" />
                                                </center>
                                            <!-- end of group detail -->
                                        </td>
                                    	<td valign="top">
                                              <?php echo $words["group right"]; ?>:<br />
                                        	<div id="menu_area" style="border:1px solid #ffffff;">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                
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