<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="5,38";
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
		var post_member_id = "<?php echo $_REQUEST["member_id"];?>";
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
												sch_idd:	"#sch_idd",
												sch_plate_no:"#sch_plate_no",
												event_id: 	"#event_id"
											},
											headers:[
												{title:	words["sn"], 			col:"rowno",		width:20},
												{title: words["name"], 			col:"first_name",	sq:"ASC"},
												//{title: words["last name"], 	col:"last_name", 	sq:"ASC"},
												//{title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
												//{title: words["m.alias"], 		col:"alias", 		sq:"DESC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC"},
												{title: words["email"], 		col:"email", 		sq:"ASC"},
												{title: words["phone"], 		col:"phone", 		sq:"ASC"},
												//{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												//{title: words["g.site"], 		col:"site", 		sq:"ASC", align:"center"},
												//{title: words["date"], 			col:"created_time",	sq:"DESC"},
												{title: words["short.lang"], 	col:"language",	    sq:"ASC", align:"center"},
												{title: words["status"],		col:"enroll", 		sq:"DESC", align:"center"},
												{title: words["email subscription"], col:"email_flag",	    sq:"ASC", align:"center"},
												{title:"", 						col:""}
											],
											container: 		"#event_enrollment",
											me:				"ctt",

											url:			"ajax/puti_email_list_select.php",
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

											pageRows:		pageHTML
										});
			
			ctt.tabData.condition.member_id = post_member_id;
			ctt.start();
			
			$(".enroll_button_remove").live("click", function(ev) {
				  var rid = $(this).attr("rid");
					  //$("#wait").loadShow();
					  $.ajax({
						  data: {
							  admin_sess: 	$("input#adminSession").val(),
							  admin_menu:	$("input#adminMenu").val(),
							  admin_oper:	"delete",
							  member_id: 	rid
						  },
						  dataType: "json",  
						  error: function(xhr, tStatus, errorTh ) {
							  //$("#wait").loadHide();
							  alert("Error (puti_email_list_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						  },
						  success: function(req, tStatus) {
							  //$("#wait").loadHide();
							  if( req.errorCode > 0 ) { 
								  errObj.set(req.errorCode, req.errorMessage, req.errorField);
								  return false;
							  } else {
								  $("tr[rid='"+ req.data.member_id +"']>.enroll").html('');
								   $("span.shelf", "tr[rid='"+ req.data.member_id +"']").html('');
								  $("td>.group_no[rid='"+ req.data.member_id +"']").val('');
								  tool_tips(words["remove from email list success"]);
							  }
						  },
						  type: "post",
						  url: "ajax/puti_email_list_delete.php"
					  });
			});

			//$(":checkbox[name='email_flag'], .enroll_button_add").live("click", function(ev) {
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
		});
		
		function save_ajax(eid, rid) {
				  //$("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  //onsite: 		$("#onsite").is(":checked")?1:0,
						  email: 			$("input.email[rid='" + rid + "']").val(), 
						  email_flag: 		$("input.email_flag[rid='" + rid + "']").is(":checked")?1:0, 
							
						  member_id: 		rid
				  	  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  //$("#wait").loadHide();
						  alert("Error (puti_email_list_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  //$("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
						  	$("tr[rid='"+ req.data.member_id +"']>.enroll").html('<a class="enroll-status-enroll"></a>');
							tool_tips(words["add to email list success"]);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_email_list_save.php"
				  });
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
				html += '</td>';


				html += '<td>';
				html += pObjs[idx]["first_name"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["gender"];
				html += '</td>';

				html += '<td>';
				html += '<input class="email" style="width:250px;" name="email" rid="'  + pObjs[idx]["id"] + '" value="' + pObjs[idx]["email"] + '">';
				//html += pObjs[idx]["email"];
				html += '</td>';

				html += '<td>';
				html += pObjs[idx]["phone"];
				html += '</td>';

				/*
				html += '<td align="center">';
				html += pObjs[idx]["city"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["site"];
				html += '</td>';
				
				html += '<td>';
				html += pObjs[idx]["created_time"];
				html += '</td>';
				*/

				html += '<td align="center"><span class="language">';
				html += pObjs[idx]["language"];
				html += '</span></td>';

				html += '<td class="enroll" align="center">';
				html += pObjs[idx]["enroll"];
				html += '</td>';

				html += '<td align="center">';
				html += '<input class="email_flag" type="checkbox" name="email_flag" rid="'  + pObjs[idx]["id"] + '" ' +  (pObjs[idx]["email_flag"]!="1"?'':'checked') + ' value="1" />';
				html += '</span></td>';
				
				/*
				*/
				
				html += '<td align="center"  style="white-space:nowrap;">';
			 	html += '<a class="enroll_button_add" 		oper="save" 		right="save" 	rsn="' + idx + '" rid="'  + pObjs[idx]["id"] + '"  title="' + words["add to email list"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-output" 	oper="print" 	right="print" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["print details"] + '"></a>';
				//html += ' <a class="tabQuery-button tabQuery-button-view" 		oper="view" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view details"] + '"></a>';
				html += ' <a class="enroll_button_remove" 	oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["remove from email list"] + '"></a>';
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
		
		
		
		function full_ajax() {
			$("input[name='enroll_event_id']").val($("#event_id").val());
			$("input[name='enroll_onsite']").val( $("#onsite").is(":checked")?1:0  );
			$("input[name='enroll_trial']").val( $("#trial").is(":checked")?1:0 );

			$("input[name='sch_name']").val($("#sch_name").val());
			$("input[name='sch_email']").val($("#sch_email").val());
			$("input[name='sch_phone']").val($("#sch_phone").val());
			$("input[name='sch_plate_no']").val($("#sch_plate_no").val());

			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/puti_registration8.php");
			form_register.submit();
		}

		function quick_ajax() {
			$("input[name='enroll_event_id']").val($("#event_id").val());
			$("input[name='enroll_onsite']").val( $("#onsite").is(":checked")?1:0  );
			$("input[name='enroll_trial']").val( $("#trial").is(":checked")?1:0 );

			$("input[name='sch_name']").val($("#sch_name").val());
			$("input[name='sch_email']").val($("#sch_email").val());
			$("input[name='sch_phone']").val($("#sch_phone").val());
			$("input[name='sch_plate_no']").val($("#sch_plate_no").val());

			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/puti_qform8.php");
			form_register.submit();
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
              <table cellpadding="2" cellspacing="0">
                  <tr>
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                      <td align="right"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                      <td align="right"><?php echo $words["id number"]?>: </td>
                      <td><input style="width:120px;"  oper="search" id="sch_idd" style="width:120px;" value="" /></td>
                  </tr>
                  <tr>
                      <td align="right"><?php echo $words["email"]?>: </td>
                      <td colspan="5"><input oper="search" style="width:200px;" id="sch_email" value="" /></td>
                  </tr>

                  <tr>
                      <td align="right"></td>
                      <td colspan="5">
                        <input type="button" oper="search" style="width:100px;" onclick="search_ajax()" style="width:60px;" value="<?php echo $words["search"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="quick_ajax()"  value="<?php echo $words["quick register"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="full_ajax()"  value="<?php echo $words["full register"]?>" />                 
						<a id="btn_print_empty" class="tabQuery-button tabQuery-button-output" style=" vertical-align:middle; margin-left:20px;" right="print" title="<?php echo $words["print empty signature"]?>"></a>                       
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

	<input type="hidden" name="sch_name" value="" />
	<input type="hidden" name="sch_email" value="" />
	<input type="hidden" name="sch_phone" value="" />
	<input type="hidden" name="sch_plate_no" value="" />
</form>

</body>
</html>