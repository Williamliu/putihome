<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,80";
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
		
		<script type="text/javascript" 	src="../js/json2.min.js"></script>
		
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
		var rows = [];
		var htmlObj = new LWH.cHTML();

		$(function(){
			  $("#diaglog_detail").lwhDiag({
				  titleAlign:		"center",
				  title:			words["Member Enrollment"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			500,
				  minHH:			180,
				  btnMax:			false,
				  resizable:		false,
				  movable:			true,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });

			  $("#tabber_detail").lwhTabber();
			  
			  ctt = new LWH.cTABLE({
											condition: 	{
												sch_name:	"#sch_name",
												sch_phone:	"#sch_phone",
												sch_email:	"#sch_email",
												//sch_gender:	"#sch_gender",
												//sch_status:	"#sch_status",
												sch_idd:	"#sch_idd",
												sch_plate_no:"#sch_plate_no",
												sch_level:	"#sch_level",
												sch_city:	"#sch_city",
												event_id: 	"#event_id"
											},
											headers:[
												{title:	words["sn"], 			col:"rowno",		width:30},
												{title: words["name"], 			col:"aname",		sq:"ASC"},
												//{title: words["last name"], 	col:"last_name", 	sq:"ASC"},
												//{title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
												//{title: words["m.alias"], 		col:"alias", 		sq:"DESC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC"},
												{title: words["email"], 		col:"email", 		sq:"ASC"},
												{title: words["phone"], 		col:"phone", 		sq:"ASC"},
												//{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												//{title: words["g.site"], 		col:"site", 		sq:"ASC", align:"center"},
												{title: words["short.lang"], 	col:"language",	    sq:"ASC", align:"center"},
												{title: words["date"], 			col:"created_time",	sq:"DESC"},
												{title: words["shoes.shelf"], 	col:"shelf"},
												{title: words["a.sign"], 		col:"signin"},
												{title: words["trial"], 		col:"trial"},
												{title: words["group"], 		col:"group_no"},
												{title: words["id card"], 		col:"idd"},
											    {title: words["paid"], 			col:"paid", 		align:"center"},
											    {title: words["amount"], 		col:"amt", 			align:"right"},
											    {title: words["c.photo"], 		col:"photo", 		align:"center"},
												{title: words["status"],		col:"enroll", 		sq:"DESC", align:"center"},
												{title:"&nbsp;", 				col:""}
											],
											container: 		"#event_enrollment",
											me:				"ctt",

											url:			"ajax/event_calendar_enroll_select.php",
											orderBY: 		"created_time",
											orderSQ: 		"DESC",
											cache:			true,
											expire:			3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			true,
											view:			true,
											output:			true,
											remove:			true,

											pageRows:		pageHTML,
											ajaxDONE:		ajaxDone
										});
			
			ctt.start();
			
			$(".enroll_button_remove").live("click", function(ev) {
				  var eid = $("#event_id").val();					 
				  var rid = $(this).attr("rid");
				  if(eid != "") {
					  //$("#wait").loadShow();
					  $.ajax({
						  data: {
							  admin_sess: 	$("input#adminSession").val(),
							  admin_menu:	$("input#adminMenu").val(),
							  admin_oper:	"delete",
							  
							  event_id: 	eid, 
							  member_id: 	rid
						  },
						  dataType: "json",  
						  error: function(xhr, tStatus, errorTh ) {
							  //$("#wait").loadHide();
							  alert("Error (event_calendar_enroll_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						  },
						  success: function(req, tStatus) {
							  //$("#wait").loadHide();
							  if( req.errorCode > 0 ) { 
								  errObj.set(req.errorCode, req.errorMessage, req.errorField);
								  return false;
							  } else {
								  $("tr[rid='"+ req.data.member_id +"']>.enroll").html('&nbsp;');
								  $("td>.group_no[rid='"+ req.data.member_id +"']").val('');
								  $("span.shelf", "tr[rid='"+ req.data.member_id +"']").html('');
								  tool_tips(words["cancel success"]);
							  }
						  },
						  type: "post",
						  url: "ajax/event_calendar_enroll_delete.php"
					  });
				  } else {
					  alert( words["please select an event"] );
				  }
			});

			$(".enroll_button_add").live("click", function(ev) {
				  var rid = $(this).attr("rid");
				  var eid = $("#event_id").val();
				  save_ajax(eid, rid);
			});

			
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					search_ajax();
				}
			});

			$("#btn_print_empty").bind("click", function(ev) {
				var eid = $("#event_id").val();
				print_signature(eid, 0);
			});

			$("input#sch_idd, input#idd").bind("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd, input#idd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});

			// output signature form
			$(".tabQuery-button[oper='print']").live("click", function(ev){
				  var eid = $("#event_id").val();
				  var mid = $(this).attr("rid");
				  print_signature(eid,mid);
			});	

			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});

			$(".tabQuery-button[oper='camera']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  var ridx = $(this).attr("rsn");
				  var a = jsonStr( rows[ridx] );
				  window.external.get_member_info(a);
				  //member_detail_search(member_id);
			});
			
		});
		
		function ajaxDone( req ) {
			rows = [];
			rows = req.data.rows;
			/*
			if(rows.length == 1) {
				  var member_id = rows["id"];
				  var ridx = 0;
				  var a = jsonStr( rows[ridx] );
				  window.external.get_member_info(a);
			}
			*/
		}
		
		function  show_member_detail(member_id) {
			//$("span.photo" , "tr[rid='" + member_id + "']").html("Y");
			//$("tr[rid]").removeClass("tr-selected");
			//$("tr[rid='" + member_id + "']").addClass("tr-selected");
			member_detail_search(member_id);
			ctt.fresh();
		}
		
		function save_ajax(eid, rid) {
			if(eid != "") {
				  //$("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  //onsite: 		$("#onsite").is(":checked")?1:0,
						  signin:		$("input.signin[rid='" + rid + "']").is(":checked")?1:0, 		
						  trial:		$("input.trial[rid='" + rid + "']").is(":checked")?1:0, 		
						  member_id: 	rid,
						  event_id:		eid,
						  group_no:		$("input.group_no[rid='" + rid + "']").val(),
						  idd:			$("input.idd_card[rid='" + rid + "']").val()
				  	  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  //$("#wait").loadHide();
						  alert("Error (event_calendar_enroll_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  //$("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
						  	$("tr[rid='"+ req.data.member_id +"']>.enroll").html('<a class="enroll-status-enroll"></a>');
							$("span.shelf", "tr[rid='"+ req.data.member_id +"']").html(req.data.shelf);							
							$("#trial").attr("checked", false);
							tool_tips(words["member enroll success"]);
						  }
					  },
					  type: "post",
					  url: "ajax/event_calendar_enroll_save.php"
				  });
			} else {
				alert( words["please select an event"] );
			}
		}
		
		
		function search_ajax() {
			ctt.start();
		}

		function print_signature(eid, mid) {
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"print",
						  
					  event_id: 	eid,						
					  member_id:	mid
				  },
				  dataType: "json",  
				  //contentType: "text/html; charset=utf-8",
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (event_calendar_group_signature.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  var w1 = window.open("output.html");
					  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Student Registration</" + "title" + "></" + "head" + "><" + "body>";
					  w1.document.open();
					  w1.document.write(html_str);
					  w1.document.write(req.data);
					  w1.document.write('</html>');
					  w1.document.close();
					  w1.print();
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_signature.php"
			  });
		}
		
		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			for(var idx in pObjs) {
				html += '<tr rowno="' + idx + '" rid="'  + pObjs[idx]["id"] + '">';
				
				html += '<td align="center">';
				html += parseInt(idx) + 1;
				html += '&nbsp;</td>';


				html += '<td style="white-space:nowrap;"><span class="aname">';
				html += pObjs[idx]["aname"];
				html += '</span>&nbsp;</td>';

				html += '<td align="center"><span class="sex">';
				html += pObjs[idx]["sex"];
				html += '</span>&nbsp;</td>';

				html += '<td><span class="email">';
				html += pObjs[idx]["email"];
				html += '</span>&nbsp;</td>';

				html += '<td><span class="phone">';
				html += pObjs[idx]["phone"];
				html += '</span>&nbsp;</td>';
				
				/*
				html += '<td align="center"><span class="city">';
				html += pObjs[idx]["city"];
				html += '</span></td>';
				
				
				html += '<td align="center"><span class="site">';
				html += pObjs[idx]["site"];
				html += '</span>&nbsp;</td>';
				*/

				html += '<td align="center"><span class="language">';
				html += pObjs[idx]["language"];
				html += '</span>&nbsp;</td>';

				html += '<td>';
				html += pObjs[idx]["created_time"];
				html += '&nbsp;</td>';

				html += '<td align="center"><span class="shelf">';
				html += pObjs[idx]["shelf"];
				html += '</span>&nbsp;</td>';

				html += '<td>';
				html += '<input class="signin" type="checkbox" '+ (pObjs[idx]["signin"]=="1"?'checked':'') + ' rid="' + pObjs[idx]["id"] + '" value="1" />';
				html += '</td>';

				html += '<td>';
				html += '<input class="trial" type="checkbox" '+ (pObjs[idx]["trial"]=="1"?'checked':'') + ' rid="' + pObjs[idx]["id"] + '" value="1" />';
				html += '</td>';
				
				html += '<td>';
				html += '<input class="group_no" type="text" style="width:30px;font-size:14px;font-weight:bold;text-align:center;" rid="' + pObjs[idx]["id"] + '" value="' + pObjs[idx]["group_no"] + '" />';
				html += '</td>';

				html += '<td>';
				html += '<input class="idd_card" type="text" style="width:80px;" rid="'  + pObjs[idx]["id"] + '" value="' + pObjs[idx]["idd"] + '" />';
				html += '</td>';

				html += '<td align="center"><span class="paid">';
				html += pObjs[idx]["paid"];
				html += '</span>&nbsp;</td>';

				html += '<td align="right"><span class="amt">';
				html += pObjs[idx]["amt"];
				html += '</span>&nbsp;</td>';

				html += '<td align="center"><span class="photo">';
				html += pObjs[idx]["photo"];
				html += '</span>&nbsp;</td>';

				html += '<td class="enroll" align="center">';
				html += pObjs[idx]["enroll"];
				html += '&nbsp;</td>';

				html += '<td align="center"  style="white-space:nowrap;">';
			 	html += '<a class="enroll_button_add" 		oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["enroll"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-output" 	oper="print" 	right="print" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["print details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-view" 		oper="view" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-camera" 	oper="camera" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["camera photo"] + '"></a>';
				html += ' <a class="enroll_button_remove" 	oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["cancel enroll"] + '"></a>';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		
		function level_select() {
			if($("#sch_level").val() != '') {
				$("#area-title").show();
			} else {
				$("#area-title").hide();
			}
			search_ajax();
		}
		
		function enroll_title_ajax() {
					  $("#wait").loadShow();
					  $.ajax({
						  data: {
							  admin_sess: 	$("input#adminSession").val(),
							  admin_menu:	$("input#adminMenu").val(),
							  admin_oper:	"save",
							  
							  event_id: 	$("#event_id").val(),
							  sch_name:		$("#sch_name").val(),
							  sch_phone:	$("#sch_phone").val(),
							  sch_email:	$("#sch_email").val(),
							  //sch_gender:	$("#sch_gender").val(),
							  //sch_status:	$("#sch_status").val(),
							  sch_idd:		$("#sch_idd").val(),
							  sch_plate_no:	$("#sch_plate_no").val(),
							  sch_level:	$("#sch_level").val(),
							  sch_city:		$("#sch_city").val()
						  },
						  dataType: "json",  
						  error: function(xhr, tStatus, errorTh ) {
							  $("#wait").loadHide();
							  alert("Error (event_calendar_enroll_title.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
						  url: "ajax/event_calendar_enroll_title.php"
					  });
		}


		function enroll_title_remove() {
					  $("#wait").loadShow();
					  $.ajax({
						  data: {
							  admin_sess: 	$("input#adminSession").val(),
							  admin_menu:	$("input#adminMenu").val(),
							  admin_oper:	"save",
							  
							  event_id: 	$("#event_id").val(),
							  sch_name:		$("#sch_name").val(),
							  sch_phone:	$("#sch_phone").val(),
							  sch_email:	$("#sch_email").val(),
							  //sch_gender:	$("#sch_gender").val(),
							  //sch_status:	$("#sch_status").val(),
							  sch_idd:		$("#sch_idd").val(),
							  sch_plate_no:	$("#sch_plate_no").val(),
							  sch_level:	$("#sch_level").val(),
							  sch_city:		$("#sch_city").val()
						  },
						  dataType: "json",  
						  error: function(xhr, tStatus, errorTh ) {
							  $("#wait").loadHide();
							  alert("Error (event_calendar_enroll_remove.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
						  url: "ajax/event_calendar_enroll_remove.php"
					  });
		}
		
		
		function full_ajax() {
			$("input[name='enroll_event_id']").val($("#event_id").val());
			$("input[name='enroll_onsite']").val( $("#onsite").is(":checked")?1:0  );
			$("input[name='enroll_trial']").val( $("#trial").is(":checked")?1:0 );
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/puti_registration3.php");

			$("input[name='sch_name']").val($("#sch_name").val());
			$("input[name='sch_email']").val($("#sch_email").val());
			$("input[name='sch_phone']").val($("#sch_phone").val());
			$("input[name='sch_plate_no']").val($("#sch_plate_no").val());

			form_register.submit();
		}

		function quick_ajax() {
			$("input[name='enroll_event_id']").val($("#event_id").val());
			$("input[name='enroll_onsite']").val( $("#onsite").is(":checked")?1:0  );
			$("input[name='enroll_trial']").val( $("#trial").is(":checked")?1:0 );
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/puti_qform3.php");

			$("input[name='sch_name']").val($("#sch_name").val());
			$("input[name='sch_email']").val($("#sch_email").val());
			$("input[name='sch_phone']").val($("#sch_phone").val());
			$("input[name='sch_plate_no']").val($("#sch_plate_no").val());

			form_register.submit();
		}
        </script>

</head>
<body style="background-image:none;background-color:#dddddd;">
<?php 
//include("admin_menu_html.php");
?>
<div>
    <br />
    <fieldset style="border:1px solid #999999;">
    	<legend><?php echo $words["search filter"]?></legend>
    	<span style="font-size:14px; font-weight:bold; margin-left:2px; background-color:orange;"><?php echo $words["select event"]?>: </span>
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
                  if($first && $_REQUEST["enroll_event_id"]=="") {
					  $first = false;
					  echo '<option value="' . $row["id"] . '" selected>' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  } else { 
					  echo '<option value="' . $row["id"] . '" ' . ($_REQUEST["enroll_event_id"]==$row["id"]?'selected':'') . '>' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  }
              }
              
          ?>
          </select>
          <!-- <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="onsite" name="onsite" value="1" <?php echo $_REQUEST["enroll_onsite"]?"checked":"";?> /><label for="onsite"><?php echo $words["onsite registration"]?></label></span> -->
          <!-- <span style="margin-left:10px;vertical-align:middle;font-size:14px;font-weight:bold;"><input type="checkbox" id="trial" name="trial" value="1"  <?php echo $_REQUEST["enroll_trial"]?"checked":"";?> /><label for="trial"><?php echo $words["trial"]?></label></span> -->
              <br /><br />
              <table cellpadding="2" cellspacing="0">
                  <tr>
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                      <td align="right"><?php echo $words["email"]?>: </td>
                      <td><input oper="search" style="width:120px;font-size:12px;" id="sch_email" value="" /></td>
                      <!--
                      <td align="right"><?php echo $words["gender"]?>: </td>
                      <td>
                          <select oper="search" id="sch_gender">
                              <option value=""></option>
                              <option value="Male"><?php echo $words["male"]?></option>
                              <option value="Female"><?php echo $words["female"]?></option>
                          </select>
                      </td>
                      -->   
                     <td align="right"><?php echo $words["member.title"]?>: </td>
                     <td colspan="3">
                          <select id="sch_level" style="text-align:center;" name="sch_level" onchange="level_select();">
                              <option value=""></option>
                              <?php
                                  $result_lvl = $db->query("SELECT * FROM puti_info_title order by id");
                                  while( $row_lvl = $db->fetch($result_lvl) ) {
                                      echo '<option value="' . $row_lvl["id"] . '">' . $row_lvl["title"] . '</option>';
                                  }
                              ?>
                          </select>
                    	  <span id="area-title" style="margin-left:5px; display:none;">
                          <input type="button" id="btn_enroll_title" onclick="enroll_title_ajax()" value="<?php echo $words["enroll all"]?>" />
                          <input type="button" id="btn_enroll_remove" onclick="enroll_title_remove()" value="<?php echo $words["enroll remove"]?>" />
                          </span>                            
                      </td>
                  </tr>
                  <tr>
                      <td align="right"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                      <td align="right"><?php echo $words["city"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_city" value="" /></td>
                      <!--
                      <td align="right"><?php echo $words["status"]?>: </td>
                      <td>
                          <select oper="search" id="sch_status">
                              <option value=""></option>
                              <option value="0"><?php echo $words["inactive"]?></option>
                              <option value="1"><?php echo $words["active"]?></option>
                          </select>  
                      </td>
                      -->
                      <td align="right"><?php echo $words["plate no"]?>: </td>
                      <td colspan="3">
                      	<input type="text" style="width:80px;" oper="search" id="sch_plate_no" name="sch_plate_no" value="" />
                      </td>
                  </tr>
                  <tr>
                      <td align="right"></td>
                      <td colspan="7">
                        <input type="button" oper="search" style="width:100px;" onclick="search_ajax()" style="width:60px;" value="<?php echo $words["search"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="quick_ajax()"  value="<?php echo $words["quick register"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="full_ajax()"  value="<?php echo $words["full register"]?>" />                  
						<a id="btn_print_empty" class="tabQuery-button tabQuery-button-output" style=" vertical-align:middle; margin-left:20px;" right="print" title="<?php echo $words["print empty signature"]?>"></a>                       
                        <span style="font-size:12px; font-weight:bold; margin-left:20px;"><?php echo $words["id number"]?>: </span><input style="width:120px;"  oper="search" id="sch_idd" style="width:120px; margin-left:10px;" value="" />
                      </td>
                  </tr>
              </table>
    </fieldset>
 	<div id="event_enrollment" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>

<?php include("tpl_member_detail.php"); ?>
<form name="form_register" action="" method="post">
	<input type="hidden" name="lang" value="<?php echo $Glang;?>" />
	<input type="hidden" name="adminSession" value="<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>" />
	<input type="hidden" name="adminMenu" value="<?php echo $admin_menu; ?>" />
	<input type="hidden" name="enroll_event_id" value="" />
	<input type="hidden" name="enroll_onsite" value="" />
	<input type="hidden" name="enroll_trial" value="" />
	<input type="hidden" name="enroll_group_no" value="<?php echo $_REQUEST["enroll_group_no"];?>" />

	<input type="hidden" name="sch_name" value="" />
	<input type="hidden" name="sch_email" value="" />
	<input type="hidden" name="sch_phone" value="" />
	<input type="hidden" name="sch_plate_no" value="" />
</form>

</body>
</html>