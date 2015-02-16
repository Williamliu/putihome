<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="800,20";
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
		<title>Bodhi Meditation Admin User List</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#group_list, #group_edit").lwhTabber();
			
			$("#diaglog_pwd").lwhDiag({
				titleAlign:		"center",
				title:			words["set password"],
				
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
			
			$("li.admin-user").live("click", function(ev) {
		  		  $("#wait").loadShow();
				  var gid = $(this).attr("user_id");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  admin_id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_admins_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							$("li.admin-user").removeClass("node-selected");
							$("li.admin-user[user_id='" + req.data.admin_id + "']").addClass("node-selected");
							
							$("input#admin_id").val(req.data.admin_id);
							$("input#first_name").val(req.data.first_name);
							$("input#last_name").val(req.data.last_name);
							$("input#dharma_name").val(req.data.dharma_name);
							$("input#phone").val(req.data.phone);
							$("input#cell").val(req.data.cell);
							$("input#city").val(req.data.city);
							$("input#user_name").val(req.data.user_name);
							$("input#email").val(req.data.email);
							$("select#site").val(req.data.site);
							$("select#branch").val(req.data.branch);
							$("select#group_id").val(req.data.group_id);
							$("select#status").val(req.data.status);
							
							$("input:checkbox.sites").attr("checked",false);
							$.map( req.data.sites.split(","), function(n) {
								$("input:checkbox.sites[value='" + n + "']").attr("checked",true);
							});

							$("input:checkbox.branchs").attr("checked",false);
							$.map( req.data.branchs.split(","), function(n) {
								$("input:checkbox.branchs[value='" + n + "']").attr("checked",true);
							});

							$("input:checkbox.department").attr("checked",false);
							$.map( req.data.department.split(","), function(n) {
								$("input:checkbox.department[value='" + n + "']").attr("checked",true);
							});
							
							$("#admin_created_time").html(req.data.created_time);
							$("#admin_last_updated").html(req.data.last_updated);
							$("#admin_last_login").html(req.data.last_login);
							$("#admin_hits").html(req.data.hits);
							$("#btn_pwd").show();
							
							  node_selected(req.data.admin_id);
						  }
					  },
					  type: "post",
					  url: "ajax/website_admins_select.php"
				  });
			});
		
			new_ajax();	
			users_ajax();
		});
		
		function users_ajax( admin_id ) {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  admin_id:		admin_id
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_admins_users.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  rolesHTML(req.data.roles);
							  departmentsHTML(req.data.departments);
							  sitesHTML(req.data.sites);
							  putiHTML(req.data.puti);
							  
							 if(req.data.admin_id>0) node_selected(req.data.admin_id);
						  }
					  },
					  type: "post",
					  url: "ajax/website_admins_users.php"
				  });
		}
		
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
						  email: 		$("input#email").val(),
						  site:			$("select#site").val(),
						  branch:		$("select#branch").val(),
						  group_id:		$("select#group_id").val(),
						  status: 		$("select#status").val(),
						  sites:		$("input:checkbox.sites:checked").map(function(){ return $(this).val();}).get().join(","),
						  branchs:		$("input:checkbox.branchs:checked").map(function(){ return $(this).val();}).get().join(","),
						  depart:		$("input:checkbox.department:checked").map(function(){ return $(this).val();}).get().join(",")

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
							if( req.data.old_id < 0 ) {
								$("#admin_created_time").html(req.data.created_time);
							} else {
								$("#admin_last_updated").html(req.data.last_updated);
							}
							$("input#admin_id").val(req.data.admin_id);
							$("#btn_pwd").show();
							
							users_ajax(req.data.admin_id);
						  }
					  },
					  type: "post",
					  url: "ajax/website_admins_save.php"
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
							//$(".lwhDiag-content", "#diaglog").html( req.errorMessage.nl2br() );
							//$("#diaglog").diagShow({title:"Submit Success"}); 
						  }
					  },
					  type: "post",
					  url: "ajax/website_admins_pwd_save.php"
				  });
		}
		
		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#admin_id").val(-1);
			$("input#first_name").val("");
			$("input#last_name").val("");
			$("input#dharma_name").val("");
			$("input#phone").val("");
			$("input#cell").val("");
			$("input#city").val("");
			$("input#user_name").val("");
			$("input#email").val("");
			$("select#status").val("");
			$("select#site").val("");
			$("select#branch").val("");
			$("select#group_id").val("");
			$("input:checkbox.sites").attr("checked",false);
			$("input:checkbox.branchs").attr("checked",false);
			$("input:checkbox.department").attr("checked",false);
			$("#admin_created_time").html("");
			$("#admin_last_updated").html("");
			$("#admin_last_login").html("");
			$("#admin_hits").html("");
			$("#btn_pwd").hide();
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

							admin_id: $("input#admin_id").val()
						},
						dataType: "json",  
						error: function(xhr, tStatus, errorTh ) {
				  		    $("#wait").loadHide();
							alert("Error (website_admins_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						},
						success: function(req, tStatus) {
  				  		    $("#wait").loadHide();
							if( req.errorCode > 0 ) { 
								errObj.set(req.errorCode, req.errorMessage, req.errorField);
								return false;
							} else {
                                tool_tips(words["delete success"]);
								$("li.admin-user[user_id='" + req.data.admin_id + "']").remove();						
								$("#lwhGroups").lwhTree_refresh();
								$("#lwhRoles").lwhTree_refresh();
								$("#lwhDepartments").lwhTree_refresh();
								$("#lwhPuti").lwhTree_refresh();
								new_ajax();
							}
						},
						type: "post",
						url: "ajax/website_admins_delete.php"
					});
			}
		}

		function sitesHTML( sites ) {
			$("#groups_area").html("");
			var html = '';
			html += '<ul id="lwhGroups" class="lwhTree" style="margin-left:0px; padding-left:0px;">';
			var cnt0 = 0;
			for(var key0 in sites) {			
				cnt0++;
				html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img node-img-group"></s>';
				html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;">' + words[sites[key0].title.toLowerCase()] + ' { <span style="font-weight:normal;">' + sites[key0].branchs.length + ' ' + words["r.groups"] + ' }</span></span>'; 
				html += '<ul class="lwhTree">';
				
				var roles = sites[key0].branchs;
				var cnt = 0;
				for(var key in roles) {
					cnt++;
					html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
					html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;">' +  words[roles[key].title.toLowerCase()] + ' { <span style="font-weight:normal;">' + roles[key].users.length + ' ' + words["users"] + ' }</span></span>'; 
					html += '<ul class="lwhTree">';
					for(var key1 in roles[key].users) {
						var userObj = roles[key].users[key1];
						html += '<li class="node admin-user" user_id="' + userObj.user_id + '"><s class="node-line"></s><s class="node-img node-img-user"></s>';
						var mem_str = '<span class="title" style="color:#333333;width:180px;" title="Click To View Details">' + userObj.name + '</span>'; 
						html += mem_str;
						html += '</li>';
					}
					html += '</ul>';
					html += '</li>';
				}

				html += '</ul>';
				html += '</li>';
			}
			$("#groups_area").html(html);
			$("#lwhGroups").lwhTree({single:true});
		}

    	
		function rolesHTML( roles ) {
			$("#roles_area").html("");
			var html = '';
			html += '<ul id="lwhRoles" class="lwhTree" style="margin-left:0px; padding-left:0px;">';
			var cnt = 0;
			for(var key in roles) {
				cnt++;
				html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
				html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;"><span style="font-weight:normal;">' + cnt + '.</span> ' + roles[key].title + ' { <span style="font-weight:normal;">' + roles[key].users.length + ' ' + words["users"] + ' }</span></span>'; 
				html += '<ul class="lwhTree">';
				for(var key1 in roles[key].users) {
					var userObj = roles[key].users[key1];
					html += '<li class="node admin-user" user_id="' + userObj.user_id + '"><s class="node-line"></s><s class="node-img node-img-user"></s>';
					var mem_str = '<span class="title" style="color:#333333;width:180px;" title="Click To View Details">' + userObj.name + '</span>'; 
					html += mem_str;
					html += '</li>';
				}
				html += '</ul>';
				html += '</li>';
			}
			$("#roles_area").html(html);
			$("#lwhRoles").lwhTree({single:true});
		}

		function departmentsHTML( roles ) {
			$("#departments_area").html("");
			var html = '';
			html += '<ul id="lwhDepartments" class="lwhTree" style="margin-left:0px; padding-left:0px;">';
			var cnt = 0;
			for(var key in roles) {
				cnt++;
				html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
				html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;"><span style="font-weight:normal;">' + cnt + '.</span> ' + roles[key].title + ' { <span style="font-weight:normal;">' + roles[key].users.length + ' ' + words["users"] + ' }</span></span>'; 
				html += '<ul class="lwhTree">';
				for(var key1 in roles[key].users) {
					var userObj = roles[key].users[key1];
					html += '<li class="node admin-user" user_id="' + userObj.user_id + '"><s class="node-line"></s><s class="node-img node-img-user"></s>';
					var mem_str = '<span class="title" style="color:#333333;width:180px;" title="Click To View Details">' + userObj.name + '</span>'; 
					html += mem_str;
					html += '</li>';
				}
				html += '</ul>';
				html += '</li>';
			}
			$("#departments_area").html(html);
			$("#lwhDepartments").lwhTree({single:true});
		}

		function putiHTML( roles ) {
			$("#sites_area").html("");
			var html = '';
			html += '<ul id="lwhPuti" class="lwhTree" style="margin-left:0px; padding-left:0px;">';
			var cnt = 0;
			for(var key in roles) {
				cnt++;
				html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
				html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;">' + words[roles[key].title.toLowerCase()] + ' { <span style="font-weight:normal;">' + roles[key].users.length + ' ' + words["users"] + ' }</span></span>'; 
				html += '<ul class="lwhTree">';
				for(var key1 in roles[key].users) {
					var userObj = roles[key].users[key1];
					html += '<li class="node admin-user" user_id="' + userObj.user_id + '"><s class="node-line"></s><s class="node-img node-img-user"></s>';
					var mem_str = '<span class="title" style="color:#333333;width:180px;" title="Click To View Details">' + userObj.name + '</span>'; 
					html += mem_str;
					html += '</li>';
				}
				html += '</ul>';
				html += '</li>';
			}
			$("#sites_area").html(html);
			$("#lwhPuti").lwhTree({single:true});
		}

		function node_selected(cid) {
			  $("li.admin-user[user_id]").removeClass("node-selected");
			  $("li.admin-user[user_id='" + cid + "']").addClass("node-selected");
			  $("li.admin-user[user_id]").parents("li.nodes").removeClass("nodes-open nodes-close").addClass("nodes-close");
			  $("li.admin-user[user_id]").parents("li.nodes-last").removeClass("nodes-last-open nodes-last-close").addClass("nodes-last-close");
			  $("li.admin-user[user_id='" + cid + "']").parents("li.nodes").removeClass("nodes-open nodes-close").addClass("nodes-open");
			  $("li.admin-user[user_id='" + cid + "']").parents("li.nodes-last").removeClass("nodes-last-open nodes-last-close").addClass("nodes-last-open");
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
                            <a><?php echo $words["roles"]?><s></s></a>
                            <a><?php echo $words["dep.s"]?><s></s></a>
                            <a><?php echo $words["r.groups"]?><s></s></a>
                            <a><?php echo $words["sites"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="min-height:650px;">
                            <div id="roles_area" style="min-height:650px; overflow-x:hidden; overflow-y:auto;">
                            </div>
                            <div id="departments_area" style="min-height:650px; overflow-x:hidden; overflow-y:auto;">
                            </div>
                            <div id="groups_area" style="min-height:650px; overflow-x:hidden; overflow-y:auto;">
                            </div>
                            <div id="sites_area" style="min-height:650px; overflow-x:hidden; overflow-y:auto;">
                            </div>
                        </div>
                    </div>
                </td>
            	<td valign="top" width="auto">
                    <div id="group_edit" class="lwhTabber lwhTabber-fuzzy" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["admin user details"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="min-height:650px;">
                            <div id="group_item">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                	<td colspan="2">
<table>
      <tr>
      	<td>
              <table>
                    <tr>
                        <td class="title"><?php echo $words["first name"]?>: <span class="required">*</span></td>
                        <td>
                            <input type="hidden" id="admin_id" name="admin_id" value="-1" />
                            <input class="form-input" id="first_name" name="first_name" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="title"><?php echo $words["last name"]?>: <span class="required">*</span></td>
                        <td>
                            <input class="form-input" id="last_name" name="last_name" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="title"><?php echo $words["dharma name"]?>: </td>
                        <td>
                            <input class="form-input" id="dharma_name" name="dharma_name" value="" />
                        </td>
                    </tr>
              </table>
        </td>
        <td>
              <table>
                    <tr>
                        <td class="title"><?php echo $words["phone"]?>: </td>
                        <td>
                            <input class="form-input" style="width:120px;" id="phone" name="phone" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="title"><?php echo $words["cell"]?>: </td>
                        <td>
                            <input class="form-input" style="width:120px;" id="cell" name="cell" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="title"><?php echo $words["city"]?>: </td>
                        <td>
                            <input class="form-input" style="width:120px;" id="city" name="city" value="" />
                        </td>
                    </tr>
              </table>

        </td>
	</tr>
</table>
                                                    
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="line" colspan="2" align="center">
                                                    	<span style="color:red;"><?php echo $words["login info tips"]?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["login name"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input class="form-input" id="user_name" name="user_name" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["email"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="email" name="email" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<td></td>
                                                    <td>
														<input type="button" right="save" id="btn_pwd" name="btn_pwd" value="<?php echo $words["set password"]?>" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" class="title line"><?php echo $words["r.site"]?>: <span class="required">*</span></td>
                                                    <td class="line">
                                                        <select id="site" name="site">
                                                        <?php
															if( $admin_user["group_level"] < 9 ) {
																$result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 AND id in " . $admin_user["sites"] . " ORDER BY sn");
															} else {
																$result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 ORDER BY sn");
															}
															echo '<option value="0"></option>';
															while($row = $db->fetch($result)) {
																echo '<option value="' . $row["id"] . '">' . $words[strtolower($row["title"])] . '</option>';
															}
														?>
                                                        </select>
                                                        <span style="margin-left:20px;"></span><?php echo $words["r.teaching"]?>: 
                                                        <select id="branch" name="branch">
                                                        <?php
															if( $admin_user["group_level"] < 9 ) {
																$result = $db->query("SELECT distinct a.id, a.title FROM puti_branchs a INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) INNER JOIN puti_sites c ON (c.id = b.site_id) WHERE a.id > 0 AND a.id in " . $admin_user["branchs"] . " AND c.status = 1 AND c.id in " . $admin_user["sites"] . " ORDER BY a.sn");
															} else {
																$result = $db->query("SELECT distinct a.id, a.title FROM puti_branchs a INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) INNER JOIN puti_sites c ON (c.id = b.site_id) WHERE a.id > 0 AND c.status = 1 AND c.id in " . $admin_user["sites"] . " ORDER BY a.sn");
															}
															echo '<option value="0"></option>';
															while($row = $db->fetch($result)) {
																echo '<option value="' . $row["id"] . '">' . $words[strtolower($row["title"])] . '</option>';
															}
														?>
                                                        </select>
                                                    </td>
                                                </tr>
												<tr>  
                                                    <td style="width:60px; white-space:nowrap;" align="right" valign="top"><?php echo $words["r.sites"]?>: </td>
		                                             <td valign="top">
                                                            <!------------------------------------------------------------------>
                                                            <div style="border:1px solid #cccccc; padding:5px; width:100%;">
                                                                <?php
																	if( $admin_user["group_level"] < 9 ) {
	                                                                    $result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 AND id in " . $admin_user["sites"] . " ORDER BY sn;");
																	} else {
	                                                                    $result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 ORDER BY sn;");
																	}
																	$col_cnt = 4;
                                                                    $html = '<table width="100%">';
                                                                    $cnt=0;
                                                                    $cno=0;
                                                                    while($row = $db->fetch($result)) {
                                                                        $cno++;
                                                                        if($cnt <= 0) {
                                                                            $html .= '<tr>';
                                                                        }
                                                                        $cnt++;
                                                                        $html .= '<td>';
                                                                        $html .= '<input type="checkbox" id="sites_' . $row["id"] . '" class="sites" value="' . $row["id"] . '"><label for="sites_' . $row["id"] . '">' . $cno . '. ' .  $words[strtolower($row["title"])] . '</label>';
                                                                        $html .= '</td>';
                                            
                                                                        if($cnt >= $col_cnt) {
                                                                            $cnt = 0;
                                                                            $html .= '</tr>';
                                                                        }
                                                                    }
                                                                    if($cnt > 0 && $cnt < $col_cnt) $html .= '</tr>';
                                                                    $html .= '</table>';
                                                                    echo $html;
                                                                ?>
                                                            </div>
                                                            <!------------------------------------------------------------------>
                                                    </td>
                                                 </tr>
        										<tr>  
                                                    <td style="width:60px; white-space:nowrap;" align="right" valign="top"><?php echo $words["rr.groups"]?>: </td>
		                                             <td valign="top">
                                                            <!------------------------------------------------------------------>
                                                            <div style="border:1px solid #cccccc; padding:5px; width:100%;">
                                                                <?php
																	if( $admin_user["group_level"] < 9 ) {
	                                                                    $result = $db->query("SELECT distinct a.id, a.title FROM puti_branchs a INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) INNER JOIN puti_sites c ON (c.id = b.site_id) WHERE a.id > 0 AND a.id in " . $admin_user["branchs"] . " AND c.status = 1 AND c.id in " . $admin_user["sites"] . " ORDER BY a.sn;");
																	} else {
	                                                                    $result = $db->query("SELECT distinct a.id, a.title FROM puti_branchs a INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) INNER JOIN puti_sites c ON (c.id = b.site_id) WHERE a.id > 0 AND c.status = 1 AND c.id in " . $admin_user["sites"] . " ORDER BY a.sn;");
																	}
																	$col_cnt = 1;
                                                                    $html = '<table width="100%">';
                                                                    $cnt=0;
                                                                    $cno=0;
                                                                    while($row = $db->fetch($result)) {
                                                                        $cno++;
                                                                        if($cnt <= 0) {
                                                                            $html .= '<tr>';
                                                                        }
                                                                        $cnt++;
                                                                        $html .= '<td>';
                                                                        $html .= '<input type="checkbox" id="branchs_' . $row["id"] . '" class="branchs" value="' . $row["id"] . '"><label for="branchs_' . $row["id"] . '">' . $cno . '. ' .  $words[strtolower($row["title"])] . '</label>';
                                                                        $html .= '</td>';
                                            
                                                                        if($cnt >= $col_cnt) {
                                                                            $cnt = 0;
                                                                            $html .= '</tr>';
                                                                        }
                                                                    }
                                                                    if($cnt > 0 && $cnt < $col_cnt) $html .= '</tr>';
                                                                    $html .= '</table>';
                                                                    echo $html;
                                                                ?>
                                                            </div>
                                                            <!------------------------------------------------------------------>
                                                    </td>
                                                 </tr>
                                                
        										<tr>  
                                                    <td style="width:60px; white-space:nowrap;" align="right" valign="top"><?php echo $words["department"]?>: </td>
		                                             <td valign="top">
                                                            <!------------------------------------------------------------------>
                                                            <div style="border:1px solid #cccccc; padding:5px; width:100%;">
                                                                <?php
																	if( $admin_user["group_level"] < 9 ) {
	                                                                    $result = $db->query("SELECT id, title FROM puti_department WHERE deleted <> 1 AND status = 1 AND id IN " . $admin_user["departs"] . " ORDER BY sn, title;");
																	} else {
	                                                                    $result = $db->query("SELECT id, title FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn, title;");
																	}
																	$col_cnt = 3;
                                                                    $html = '<table width="100%">';
                                                                    $cnt=0;
                                                                    $cno=0;
                                                                    while($row = $db->fetch($result)) {
                                                                        $cno++;
                                                                        if($cnt <= 0) {
                                                                            $html .= '<tr>';
                                                                        }
                                                                        $cnt++;
                                                                        $html .= '<td>';
                                                                        $html .= '<input type="checkbox" id="depart_' . $row["id"] . '" class="department" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  $row["title"] . '</label>';
                                                                        $html .= '</td>';
                                            
                                                                        if($cnt >= $col_cnt) {
                                                                            $cnt = 0;
                                                                            $html .= '</tr>';
                                                                        }
                                                                    }
                                                                    if($cnt > 0 && $cnt < $col_cnt) $html .= '</tr>';
                                                                    $html .= '</table>';
                                                                    echo $html;
                                                                ?>
                                                            </div>
                                                            <!------------------------------------------------------------------>
                                                    </td>
                                                 </tr>

                                                <tr>
                                                    <td valign="top" class="title line"><?php echo $words["role"]?>: <span class="required">*</span></td>
                                                    <td class="line">
                                                        <select id="group_id" name="group_id">
                                                        <?php
															if( $admin_user["group_level"] < 9 ) {
																$result = $db->query("SELECT id, name FROM website_groups WHERE deleted <> 1 AND level <= '" . $admin_user["group_level"] . "' ORDER BY level DESC, name ASC");
															} else {
																$result = $db->query("SELECT id, name FROM website_groups WHERE deleted <> 1 ORDER BY level DESC, name ASC");
															}
															echo '<option value=""></option>';
															while($row = $db->fetch($result)) {
																echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
															}
														?>
                                                        </select>
                                                        <span style="margin-left:20px;"></span><?php echo $words["status"]?>: <span class="required">*</span>
                                                        <select id="status" name="status">
                                                            <option value=""></option>
                                                            <option value="0"><?php echo $words["inactive"]?></option>
                                                            <option value="1"><?php echo $words["active"]?></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["created time"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_created_time"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["last updated"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_last_updated"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["last login"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_last_login"></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:60px; white-space:nowrap;"><?php echo $words["login count"]?>: </td>
                                                    <td width="auto" align="left">
                                                        <span id="admin_hits"></span>
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