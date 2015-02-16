<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,145";
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
		<title>Bodhi Meditation Student Enrollment</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
		
		<script type="text/javascript" 	src="../jquery/min/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.zoom.js"></script>
		<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.zoom.css" rel="stylesheet" />
		
        <script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.upload.js"></script>
        <link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var ctt = null;
		var aj = null;
		var htmlObj = new LWH.cHTML();

		$(function(){
			  
			  ctt = new LWH.cTABLE({
											condition: 	{
												sch_name:	"#sch_name",
												sch_phone:	"#sch_phone",
												sch_email:	"#sch_email",
												sch_gender:	"#sch_gender",
												sch_idd:	"#sch_idd",
												sch_doc_no:	"#sch_doc_no",
												sch_city:	"#sch_city",
												sch_group:	"#sch_group",
												event_id: 	"#event_id"
											},
											headers:[
												{title:	words["sn"], 			col:"rowno",		width:20},
												{title: words["group"], 		col:"group_no", 	sq:"ASC"},
												{title: words["name"], 			col:"last_name", 	sq:"ASC"},
												{title: words["legal name"], 	col:"legal_last", 	sq:"ASC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC"},

												{title: 	words["sign?"], 		col:"signin", 		sq:"DESC", 		align:"center"},
												{title: 	words["attd."], 		col:"attend", 		sq:"DESC", 		align:"center"},
												{title:  	words["grad.?"], 		col:"graduate", 	sq:"DESC", 		align:"center"},
												{title: 	words["cert.?"], 		col:"cert", 		sq:"DESC", 		align:"center"},
												{title: 	words["paid"], 			col:"paid", 		align:"center"},
												{title: 	words["amount"], 		col:"amt", 			align:"right"},
												//{title: 	words["p.date"], 		col:"paid_date", 	align:"center"},
												
												//{title: words["email"], 		col:"email", 		sq:"ASC"},
												//{title: words["phone"], 		col:"phone"},
												//{title: words["city"], 			col:"city", align:"center", 	sq:"ASC"},
												//{title: words["g.site"], 		col:"site", align:"center"},
											    //{title: words["c.photo"], 		col:"photo",align:"center"},
												{title: words["doc no"], 		col:"doc_no", 	sq:"ASC"},
												{title:"", 						col:""}
											],
											container: 		"#event_enrollment",
											me:				"ctt",

											url:			"ajax/event_archive_select.php",
											orderBY: 		"group_no",
											orderSQ: 		"ASC",
											cache:			true,
											expire:			3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			true,
											view:			true,
											output:			true,
											remove:			true,

											pageRows:		pageHTML
										});
			
			ctt.start();
			
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					search_ajax();
				}
			});


			$("input#sch_idd, input#idd").bind("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd, input#idd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});

			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});

			$(".tabQuery-button-save").live("click", function(ev) {
				var dobjs = [];
				var dobj = {};
				dobj.member_id 	= $(this).attr("rid");
				dobj.doc_no		= $(".document_no[rid='" + dobj.member_id + "']").val();
				dobjs[0] = dobj;
				save_ajax(dobjs);				
			});

		});
		
		
		
		function search_ajax() {
			ctt.start();
		}

		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			for(var idx in pObjs) {
				html += '<tr rowno="' + idx + '" rid="'  + pObjs[idx]["id"] + '">';
				
				html += '<td align="center">';
				html += parseInt(idx) + 1;
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["group_no"];
				html += '</td>';

				html += '<td><span class="aname">';
				html += pObjs[idx]["aname"];
				html += '</span></td>';

				html += '<td><span class="legal_name">';
				html += pObjs[idx]["legal_name"];
				html += '</span></td>';

				html += '<td align="center"><span class="sex">';
				html += pObjs[idx]["gender"];
				html += '</td>';

				//html += '<td width="120" style="overflow:hidden; width:120px;">';
				//html += pObjs[idx]["email"];
				//html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["signin"];
				html += '</td>';

				html += '<td align="right">';
				html += pObjs[idx]["attend"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["graduate"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["cert"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["paid"];
				html += '</td>';

				html += '<td align="right">';
				html += pObjs[idx]["amt"];
				html += '</td>';
				
				/*
				html += '<td align="right">';
				html += pObjs[idx]["paid_date"];
				html += '</td>';
				*/

				html += '<td>';
				html += '<input class="document_no" type="text" style="width:120px;font-size:10px;" rid="'  + pObjs[idx]["id"] + '" value="' + pObjs[idx]["doc_no"] + '" />';
				html += '</td>';

				html += '<td align="center"  style="white-space:nowrap;">';
			 	//html += '<a class="enroll_button_add" 		oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["enroll"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-save" 		oper="save" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["print details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-view" 		oper="view" 	right="detail" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view details"] + '"></a>';
				//html += ' <a class="enroll_button_remove" 	oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["cancel enroll"] + '"></a>';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		
		function save_all() {
			var dobjs = [];
			
			$(".document_no").each(function(idx, el) {
				var dobj = {};
				dobj.member_id 	= $(el).attr("rid");
				dobj.doc_no		= $(el).val();
				dobjs[idx] = dobj;
            });
						
			save_ajax(dobjs);				
		}
		
		function save_ajax(docObj) {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"save",
					  
					  event_id: 	$("#event_id").val(),
					  docs:			docObj
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_archive_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						  ctt.fresh();
					  }
				  },
				  type: "post",
				  url: "ajax/event_archive_save.php"
			  });
		}

		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	

						$("input[name='event_id']", "form[name='frm_list_excel']").val( $("#event_id").val() );	
						$("input[name='sch_name']", "form[name='frm_list_excel']").val(  $("#sch_name").val() );	
						$("input[name='sch_phone']", "form[name='frm_list_excel']").val(  $("#sch_phone").val() );	
						$("input[name='sch_email']", "form[name='frm_list_excel']").val(  $("#sch_email").val() );	
						$("input[name='sch_gender']", "form[name='frm_list_excel']").val(  $("#sch_gender").val() );	
						$("input[name='sch_idd']", "form[name='frm_list_excel']").val(  $("#sch_idd").val() );	
						$("input[name='sch_doc_no']", "form[name='frm_list_excel']").val(  $("#sch_doc_no").val() );	
						$("input[name='sch_city']", "form[name='frm_list_excel']").val( $("#sch_city").val() );	
						$("input[name='sch_group']", "form[name='frm_list_excel']").val( $("#sch_group").val() );	
						
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/event_archive_print.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("#event_id").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + $("#sch_name").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + $("#sch_phone").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + $("#sch_email").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_gender" value="' + $("#sch_gender").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_idd" value="' + $("#sch_idd").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_doc_no" value="' + $("#sch_doc_no").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" value="' + $("#sch_city").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_group" value="' + $("#sch_group").val() + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
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
    	<span style="font-size:14px; font-weight:bold; margin-left:2px;"><?php echo $words["event list"]?>: </span>
          <select id="event_id" style="min-width:250px;vertical-align:middle;" onchange="search_ajax();">
          <?php 
              $query = "SELECT distinct a.id, a.title, a.start_date, a.end_date, c.title as site_desc
			  				  FROM event_calendar a 
							  INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
                              INNER JOIN puti_sites c ON (a.site = c.id) 
							  WHERE a.deleted <> 1 AND a.status = 2 AND
                                    b.deleted <> 1 AND b.status = 1 AND
									a.site IN " . $admin_user["sites"] . " AND
									a.branch IN " . $admin_user["branchs"] . " 
                              ORDER BY a.start_date ASC";
              $first = true;
			  $result = $db->query($query);
              echo '<option value=""></option>';
              while( $row = $db->fetch($result) ) {
                  $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                  if($first) {
					  $first = false;
					  echo '<option value="' . $row["id"] . '" selected>'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  } else { 
					  echo '<option value="' . $row["id"] . '">'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  }
              }
              
          ?>
          </select>
              <table cellpadding="2" cellspacing="0">
                  <tr>
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                      <td align="right"><?php echo $words["email"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_email" value="" /></td>
                      <td align="right"><?php echo $words["gender"]?>: </td>
                      <td>
                          <select oper="search" id="sch_gender">
                              <option value=""></option>
                              <option value="Male"><?php echo $words["male"]?></option>
                              <option value="Female"><?php echo $words["female"]?></option>
                          </select> 
					    <span style="margin-left:10px;vertical-align:middle;font-size:14px; font-weight:bold;"><?php echo $words["group"]?>: 
                          <input type="text" oper="search" style="width:30px; font-size:14px; font-weight:bold; text-align:center;" id="sch_group" name="sch_group" value="" />
						  </span>                             
                    </td>
                  </tr>
                  <tr>
                      <td align="right"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                      <td align="right"><?php echo $words["city"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_city" value="" /></td>
                      <td align="right"><?php echo $words["id number"]?>: </td>
                      <td><input style="width:120px;"  oper="search" id="sch_idd" style="width:120px; margin-left:10px;" value="" /></td>
                      <td align="right"><?php echo $words["doc no"]?>: </td>
                      <td><input style="width:120px;"  oper="search" id="sch_doc_no" style="width:120px; margin-left:10px;" value="" /></td>
                  </tr>
                  <tr>
                      <td align="right"></td>
                      <td colspan="5">
                        <input type="button" oper="search" 		right="view" style="width:100px;" onclick="search_ajax()" value="<?php echo $words["search"]?>" />                  
				        <input type="button" id="btn_search" 	right="print" onclick="print_event()" value="<?php echo $words["output excel"]?>" />
						<input type="button" oper="save" right="save" style="width:100px;" onclick="save_all()" value="<?php echo $words["button save all"]?>" />                    </td>
                  </tr>
              </table>
    </fieldset>
 	<div id="event_enrollment" style="min-height:400px;"></div>
    <center><input type="button" oper="save" right="save" style="width:100px;" onclick="save_all()" value="<?php echo $words["button save all"]?>" /></center>
<?php 
include("admin_footer_html.php");
?>

<?php include("tpl_member_detail.php"); ?>

</body>
</html>