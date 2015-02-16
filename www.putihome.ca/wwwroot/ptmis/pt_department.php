<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="50,20";
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
		<title>Bodhi Meditation Class - Add to Calendar</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

 		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#diaglog_department").lwhDiag({
				titleAlign:		"center",
				title:			 words["puti.department.detail"],
				
				cnColor:		"#F8F8F8",
				bgColor:		"#EAEAEA",
				ttColor:		"#94C8EF",
				 
				minWW:			380,
				minHH:			240,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			$("#group_edit").lwhTabber();
			
			$("li.puti-depart, a.puti_organization").live("click", function(ev) {
	  		  	  $("#wait").loadShow();
				  var gid = $(this).attr("rid");
				  //node_selected(gid);
				  ev.stopPropagation();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  depart_id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
			  		  	  $("#wait").loadHide();
						  alert("Error (pt_department_detail.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
			  		  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
 							node_selected(req.data.depart_id);
							$("#diaglog_department").diagShow({
									diag_open:	function() {
										set_detail(req.data.depart);

										$("#title_en").focus();
									},
									diag_close: function() {
										reset_detail();
									}
							});
						  }
					  },
					  type: "post",
					  url: "ajax/pt_department_detail.php"
				  });
			});
	
			depart_ajax();		
		});
		
		function set_detail( obj ) {
			$("#depart_id").val(obj.depart_id);
			$("#parent_id").val(obj.parent_id);
			$("#current_id").val(obj.current_id);
			$("#title_en").val(obj.title_en);
			$("#desc_en").val(obj.desc_en);
			$("#title_cn").val(obj.title_cn);
			$("#desc_cn").val(obj.desc_cn);
			$("#lang_key").val(obj.lang_key);
			$("#status").val(obj.status);
			$("#sn").val(obj.sn);
			
			if( $("#parent_id").val() < 0 ) {
				$("#btn_sav").attr("disabled", false);
				$("#btn_del").attr("disabled", true);
				$("#btn_add").attr("disabled", true);
			} else {
				$("#btn_sav").attr("disabled", false);
				$("#btn_del").attr("disabled", false);
				$("#btn_add").attr("disabled", false);
			}
		}

		function set_new( obj ) {
			$("#depart_id").val("-1");
			$("#parent_id").val("-1");
			$("#title_en").val("");
			$("#desc_en").val("");
			$("#title_cn").val("");
			$("#desc_cn").val("");
			$("#lang_key").val("");
			$("#status").val(1);
			$("#sn").val("");
		}
		
		function reset_detail() {
			$("#depart_id").val("");
			$("#parent_id").val("");
			$("#current_id").val("");
			$("#title_en").val("");
			$("#desc_en").val("");
			$("#title_cn").val("");
			$("#desc_cn").val("");
			$("#lang_key").val("");
			$("#status").val("");
			$("#sn").val("");
		}
		
		function save_ajax() {
				  //alert($("#depart_id").val() + " : " + $("#parent_id").val() );
		  		  //return;
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						  
						  depart_id:  	$("#depart_id").val(),
						  parent_id:  	$("#parent_id").val(),
						  current_id:  	$("#current_id").val(),
						  
						  title_en:  	$("#title_en").val(),
						  desc_en:  	$("#desc_en").val(),
						  title_cn:  	$("#title_cn").val(),
						  desc_cn:  	$("#desc_cn").val(),
						  lang_key:  	$("#lang_key").val(),
						  status:  		$("#status").val(),
						  sn: 	 		$("#sn").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (pt_department_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  $("#diaglog_department").diagHide();
							  depart_ajax(req.data.depart_id);
						  }
					  },
					  type: "post",
					  url: "ajax/pt_department_save.php"
				  });
		}
		
		function depart_ajax(depart_id) {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  depart_id:  	depart_id
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (pt_department_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  departHTML(req.data.departs);
							  node_selected(req.data.depart_id);
						  }
					  },
					  type: "post",
					  url: "ajax/pt_department_select.php"
				  });
		}

         function delete_ajax(dataJSON) {
             var yes = false;
             yes = window.confirm(words["are you sure to delete this record?"]);
             if (!yes) return;

             $("#wait").loadShow();
             $.ajax({
                 data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"delete",
					  
					  depart_id: $("#depart_id").val()
				 },
                 dataType: "json",
                 error: function (xhr, tStatus, errorTh) {
                     $("#wait").loadHide();
                     alert("Error (pt_department_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
                 },
                 success: function (req, tStatus) {
                     $("#wait").loadHide();
                     if (req.errorCode > 0) {
                         errObj.set(req.errorCode, req.errorMessage, req.errorField);
                         return false;
                     } else {
                         $("#diaglog_department").diagHide();
						 tool_tips(words["delete success"]);
						 depart_ajax(req.data.depart_id);
					     node_selected(req.data.depart_id);
				     }
                 },
                 type: "post",
                 url: "ajax/pt_department_delete.php"
             });
         }

		function departHTML( departs ) {
			$("#groups_area").html("");
			var html = ''; 
			html += '<a class="puti_organization" style="font-size:14px; font-weight:bold; cursor:pointer; vertical-align:middle;" rid="-1" title="' + words["click to add new record"] + '">' + words["puti.organization structure"] + '</a>';
			//html += '<input class="" type="checkbox" style="vertical-align:middle;" rid="0" value="1">';
			html += departNode(departs);
			$("#groups_area").html(html);
			$(".lwhTree").lwhTree();
		}

		function departNode( departs ) {
			var html = '';
			html += '<ul class="lwhTree">';
			var cnt0 = 0;
			for(var key0 in departs) {
				cnt0++;
				if( departs[key0].departs && departs[key0].departs.length > 0 ) { 
				    var color = departs[key0].status==1?'#000000':'#CC1A29';
					html += '<li class="nodes nodes-open puti-depart" rid="' + departs[key0].id + '"><s class="node-line"></s><s class="node-img"></s>';
					html += '<a class="" rid="' + departs[key0].id + '" style="color:' + color + '; font-size:12px; font-weight:bold; vertical-align:middle; cursor:pointer;" title="' + departs[key0].description + '">' + departs[key0].title + '</a>'; 
					html += '<s class="node-selected-img"></s>';
					html += departNode(departs[key0].departs); 
				} else {
				    var color = departs[key0].status==1?'#333333':'#CC1A29';
					html += '<li class="node puti-depart" rid="' + departs[key0].id + '"><s class="node-line"></s><s class="node-img"></s>';
					html += '<a class="" rid="' + departs[key0].id + '" style="color:' + color + '; cursor:pointer; vertical-align:middle;" title="' + departs[key0].description + '">' + departs[key0].title + '</a>'; 
					html += '<s class="node-selected-img"></s>';
					html += '</li>';
				}
			}
			html += '</ul>';
			return html;
		}
	
		function node_selected(rid) {
			  $("li.puti-depart[rid]").removeClass("node-selected");
			  $("li.puti-depart[rid='" + rid + "']").addClass("node-selected");
		}
    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />

    <div id="group_edit" class="lwhTabber lwhTabber-fuzzy" style="margin:5px;"">
        <div class="lwhTabber-header">
            <a><?php echo $words["puti.organization"]?><s></s></a>
            <div class="line"></div>    
        </div>
        <div class="lwhTabber-content" style="min-height:350px; width:100%;">
            <div id="groups_area" style="display: inline-block; width: auto; margin-left: 20px; border: 0px solid red; overflow-y:auto;">
            </div>
        </div>
    </div><!-- end of <div id="group_edit"> -->


<?php 
include("admin_footer_html.php");
?>
<div id="diaglog_department" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
            <tr>
                <td valign="top" class="title">
					<?php echo $words["puti.depart.title_en"]?> <span style="color:red;">*</span> : </td>
                <td>
                 	<input type="hidden" id="depart_id" name="depart_id" value="" />
                 	<input type="hidden" id="parent_id" name="parent_id" value="" />
                 	<input type="hidden" id="current_id" name="current_id" value="" />
                    <input class="form-input" id="title_en" value="" />
                </td>
            </tr>
            <tr>
                <td class="title"><?php echo $words["puti.depart.desc_en"]?>: </td>
                <td>
                    <input class="form-input" id="desc_en" value="" />
                </td>
            </tr>
            <tr>
                <td class="title"><?php echo $words["puti.depart.title_cn"]?> <span style="color:red;">*</span> : </td>
                <td>
                    <input class="form-input" id="title_cn" value="" />
                </td>
            </tr>
            <tr>
                <td class="title"><?php echo $words["puti.depart.desc_cn"]?>: </td>
                <td>
                    <input class="form-input" id="desc_cn" value="" />
                </td>
            </tr>
            <tr>
                <td class="title"><?php echo $words["puti.depart.lang_key"]?>: </td>
                <td>
                    <input class="form-input" id="lang_key" value="" />
                </td>
            </tr>
            <tr>
                <td valign="top" class="title"><?php echo $words["status"]?> <span style="color:red;">*</span> : </td>
                <td>
                      <select id="status" style="width:100px;">
                          <option value=""></option>
                          <option value="0"><?php echo $words["inactive"]?></option>
                          <option value="1"><?php echo $words["active"]?></option>
                      </select>    
                </td>
            </tr>
            <tr>
                <td valign="top" class="title"><?php echo $words["sn"]?>: </td>
                <td>
                    <input class="form-input" id="sn" style="width:80px;" value="" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                	<br />
                    <center>
                     	<input type="button" id="btn_sav" right="save" onclick="save_ajax()" value="<?php echo $words["button save"]?>" />
                     	<input type="button" id="btn_del" right="delete" onclick="delete_ajax()" value="<?php echo $words["button delete"]?>" />
                     	<input type="button" id="btn_add" right="add" onclick="set_new()" value="<?php echo $words["button.add.subitem"]?>" />
                        <br />
                    </center>
                        
                </td>
            </tr>
        </table>
	</div>
</div>

</body>
</html>