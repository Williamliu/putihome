<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,130";
include_once("website_admin_auth.php");
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
		<title>Bodhi Meditation ID Card Return</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.global.timer.js"></script>
 		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var allObj = null;
		$(function(){
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			words["add email success"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			400,
				minHH:			250,
				btnMax:			false,
				resizable:		false,
				movable:			false,
				maskable: 		true,
				maskClick:		true,
				pin:				false
			});

			$("input:checkbox.vol_status").live("click", function(ev) {
				  $("#wait").loadShow();				  
				  var idd = $(this).attr("idd");
				  $.ajax({
					  data: {
						  admin_sess: $("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
	  
						  idd:			idd,
						  status:  		$("input:checkbox.vol_status[idd='" + idd + "']").is(":checked")?1:0     
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();				  
						  alert("Error (event_calendar_idcard_check.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();				  
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } 
					  },
					  type: "post",
					  url: "ajax/event_calendar_idcard_check.php"
				  });
			});
				
			$(".tabQuery-button-delete").live("click", function(ev) {
				scan_idd( $(this).attr("rid") );
			});
			
			$("input#sch_idd").live("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd").live("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});

			$("input#sch_idd").live("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					scan_idd( $(this).val() );
				}
			});



			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					allObj.start();
				}
			});




			allObj = new LWH.cTABLE({
										  condition: 	{
											  sch_name:		"#sch_name",
											  sch_phone:	"#sch_phone",
											  sch_email:	"#sch_email",
											  sch_status:	"#sch_status",
											  sch_city:		"#sch_city"
										  },
										  
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:20},
											  {title: words["mark"], 		col:"status", 		sq:"ASC", width:20},
											  {title: words["name"], 		col:"first_name", 	sq:"ASC", width:160},
											  {title: words["email"], 		col:"email", 		sq:"ASC"},
											  {title: words["phone"], 		col:"phone"},
											  {title: words["city"], 		col:"city",  		sq:"ASC", 	align:"center"},
											  {title: words["g.site"], 		col:"site",  		sq:"ASC", 	align:"center"},
											  {title: words["id card"], 	col:"idd", 			sq:"ASC", 	align:"right"},
											  {title: "", 					col:""}
										  ],
										  container: 	"#holder_list",
										  me:			"allObj",
		
										  url:		"ajax/event_calendar_idcard_list.php",
										  orderBY: 	"first_name",
										  orderSQ: 	"ASC",
										  cache:		false,
										  expire:		3600,
										  
										  admin_sess: 	$("input#adminSession").val(),
										  admin_menu:	$("input#adminMenu").val(),
										  admin_oper:	"view",

										  button:		true,
										  view:			false,
										  output:		false,
										  remove:		true,
										  
										  pageRows:		allHTML										  
							  });

			allObj.start();

			$("input#sch_idd").focus();
		});
		

		function scan_idd(idd) {
		  	$("#wait").loadShow();				  
			idd = $.trim(idd);
			if( idd == "" ) return;
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"delete",
					
					sch_idd: idd	
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();				  
					alert("Error (event_calendar_idcard_return.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
				  	$("#wait").loadHide();				  
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						$("#sch_result").html(req.data.msg);
						if(req.data.flag == 1) {
							allObj.fresh();
						}
					}
				},
				type: "post",
				url: "ajax/event_calendar_idcard_return.php"
			});
		}

		function allHTML(oList) {
				  var ugrp = oList.rows;
				  var html = '';
				  for(var idx in ugrp) {
					  html += '<tr idd="' + ugrp[idx]["idd"] + '">';

					  html += '<td width="20" align="center">';
					  html += parseInt(idx) + 1;
					  html += '</td>';
					  html += '<td align="center">';
					  html += '<input type="checkbox" class="vol_status" ' + (ugrp[idx]["status"]!="1"?"":"checked") + ' idd="' + ugrp[idx]["idd"] + '" value="1" />';
					  html += '</td>';
					  html += '<td>';
					  html +=  ugrp[idx]["name"];
					  html += '</td>';
					  html += '<td>';
					  html +=  ugrp[idx]["email"];
					  html += '</td>';
					  html += '<td>';
					  html +=  ugrp[idx]["phone"] + (ugrp[idx]["cell"]!=""?'<br>' +ugrp[idx]["cell"]:'');
					  html += '</td>';
					  html += '<td>';
					  html +=  ugrp[idx]["city"];
					  html += '</td>';
					  html += '<td>';
					  html +=  ugrp[idx]["site"];
					  html += '</td>';
					  html += '<td>';
					  html +=  ugrp[idx]["idd"];
					  html += '</td>';

					  html += '<td align="center">';
					  html += '<a class="tabQuery-button tabQuery-button-delete" oper="delete" right="delete" rid="' +  ugrp[idx]["idd"] + '" title="直接删除"></a>';					 
					  html += '</td>';

					  html += '</tr>';
				  }
				  return html;
		}

		function print_event() {
			  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
					$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
					$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
					$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	
					$("input[name='sch_name']", "form[name='frm_list_excel']").val($("#sch_name").val());	
					$("input[name='sch_phone']", "form[name='frm_list_excel']").val($("#sch_phone").val());	
					$("input[name='sch_email']", "form[name='frm_list_excel']").val($("#sch_email").val());	
					$("input[name='sch_city']", "form[name='frm_list_excel']").val($("#sch_city").val());	
					$("input[name='sch_status']", "form[name='frm_list_excel']").val($("#sch_status").val());	
					$("input[name='orderBY']", "form[name='frm_list_excel']").val(allObj.tabData.condition.orderBY);	
					$("input[name='orderSQ']", "form[name='frm_list_excel']").val(allObj.tabData.condition.orderSQ);	
			  } else {
					var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
					var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
					$("form[name='frm_list_excel']").attr({"action":"ajax/event_calendar_idcard_print.php", "target": "ifm_list_excel" }); 
					$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" 	value="' + $("#sch_name").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" 	value="' + $("#sch_phone").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" 	value="' + $("#sch_email").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" 	value="' + $("#sch_city").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_status" value="' + $("#sch_status").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + allObj.tabData.condition.orderBY + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + allObj.tabData.condition.orderSQ + '" />');				  
			  }
			  $("form[name='frm_list_excel']").submit();			  
		}
	
		function add_email() {
			  	  $("#wait").loadShow();				  
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"email",

						  sch_name:		$("#sch_name").val(),
						  sch_phone:	$("#sch_phone").val(),
						  sch_email:	$("#sch_email").val(),
						  sch_city:		$("#sch_city").val(),
						  sch_status:	$("#sch_status").val(),
						  orderBY:		allObj.tabData.condition.orderBY,
						  orderSQ:		allObj.tabData.condition.orderSQ
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
					  	  $("#wait").loadHide();				  
						  alert("Error (event_calendar_idcard_email.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
					  	  $("#wait").loadHide();				  
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Saved successful."}); 
						  }
					  },
					  type: "post",
					  url: "ajax/event_calendar_idcard_email.php"
				  });
		}
		
		/*
		var gTimer = new LWH.timerClass({
							meObj: "gTimer",
							interval:		3000,
							func: function() {
								$("input#sch_idd").focus();
								$("input#sch_idd").select();
							}
						});
		gTimer.start();
		*/
		
		var gTimer11 = new LWH.timerClass({
							meObj: 			"gTimer11",
							interval:		180 * 1000,
							func: function() {
								allObj.start();
							}
						});
		gTimer11.start();
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <span style="font-size:14px; font-weight:bold; margin-left:20px;"><?php echo $words["scan id card here"]?>： </span>
	<input style="width:100px;" id="sch_idd" right="view" value="" /> <span style="font-size:14px; font-weight:bold;"><?php echo $words["to return the card"]?>.</span>

    <br />
    <div id="sch_result" style="font-size:14px; font-weight:bold; margin-left:10px; margin-top:10px; height:100px;"></div>
	
    <br />
        <fieldset>
          <legend><?php echo $words["search filter"]?></legend>
              <table border="0" cellpadding="0">
                  <tr>	
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>

                      <td align="right" style="padding-left:10px;"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>

                      <td align="right" style="padding-left:10px;"><?php echo $words["email"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_email" value="" /></td>

                      <td align="right" style="padding-left:10px;"><?php echo $words["city"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_city" value="" /></td>

                      <td align="right" style="padding-left:10px;"><?php echo $words["checklist"]?>: </td>
                      <td>
                        <select oper="search" id="sch_status">
                            <option value=""></option>
                            <option value="1"><?php echo $words["a.check"]?></option>
                            <option value="0"><?php echo $words["a.uncheck"]?></option>
                        </select>    
                      </td>
				 </tr>
                  <tr>
                      <td></td>
                      <td colspan="9" valign="middle">
                          <input type="button" right="view"  onclick="allObj.start();" style="width:100px; vertical-align:middle;" value="<?php echo $words["search"]?>" />     
                          <input type="button" right="view" 	onclick="print_event()" id="btn_output"  value="<?php echo $words["output excel"]?>" />
                          <input type="button" right="email" 	onclick="add_email()"  value="<?php echo $words["email pool"]?>" />                  
                      </td>
                  </tr>
          </table>  
    </fieldset>

    <div id="holder_list" style="padding:5px; min-height:420px;">
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