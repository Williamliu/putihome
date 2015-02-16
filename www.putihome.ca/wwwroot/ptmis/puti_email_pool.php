<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
include_once($CFG["include_path"] . "/config/basic_info.php");
$admin_menu="5,40";
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
		<title>Bodhi Meditation Email Notification</title>

		<?php include("admin_head_link.php"); ?>
	
		<script type="text/javascript" src="../jquery/min/cleditor/jquery.cleditor.min.js"></script>
   		<link href="../jquery/min/cleditor/jquery.cleditor.css" rel="stylesheet" type="text/css" />
 	
		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var ctt = null;
		var htmlDesc = null;
		$(function(){
			htmlDesc =  $("#content").cleditor({width:"95%",height:290})[0];
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			 words["add email success"],
				
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
			$("#diaglog_detail").lwhDiag({
				titleAlign:		"center",
				title:			words["send email"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			600,
				minHH:			360,
				btnMax:			false,
				resizable:		false,
				movable:			true,
				maskable: 		true,
				maskClick:		true,
				pin:				false
			});

			ctt = new LWH.cTABLE({
											condition: 	{
											},
											headers:[
												{title: words["sn"], 			col:"rowno",		width:20},
												{title: words["email subscription"], col:"email_flag",	    sq:"ASC", align:"center"},
												{title: words["last name"], 	col:"last_name", 	sq:"ASC"},
												{title: words["first name"], 	col:"first_name", 	sq:"ASC"},
												{title: words["dharma"],		col:"dharma_name", 	sq:"ASC"},
												{title: words["alias"], 		col:"alias", 		sq:"ASC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC", align:"center"},
												{title: words["email"], 		col:"email", 		sq:"ASC"},
												{title: words["short.lang"], 	col:"language",	    sq:"ASC", align:"center"},
												{title: words["phone"], 		col:"phone", 		sq:"ASC"},
												{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												{title: "", 					col:""}
											],
											container: 	"#tabrow",
											me:			"ctt",

											url:		"ajax/puti_email_pool_select.php",
											orderBY: 	"first_name",
											orderSQ: 	"ASC",
											cache:		true,
											expire:		3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			true,
											view:			false,
											output:			false,
											remove:			true
										});
			
			ctt.start();

			  

			$(".tabQuery-button[oper='delete']").live("click", function(ev) {
				  var hid = $(this).attr("rid");
				  $("#wait").diagShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",
						  
						  hid: 			hid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").diagHide();
						  alert("Error (puti_email_pool_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").diagHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  ctt.fresh();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_email_pool_delete.php"
				  });
			});

		});
		
		
		
		function output_excel() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	
						$("input[name='orderBY']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderBY);	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderSQ);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_email_pool_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function search_ajax() {
			var con = {};
			ctt.setCondition(con);
			//alert( showObj(con));
		}
		
		function clear_pool() {
				  var yes = false;
				  yes = window.confirm(words["are you sure to clear email pool"]);
				  if(!yes) return;
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete"
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (puti_email_pool_truncate.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  ctt.fresh();
							  //$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							  //$("#diaglog_message").diagShow({title:words["email pool clear successful"]}); 
						  }
					  },
					  type: "post",
					  url: "ajax/puti_email_pool_truncate.php"
				  });
		}
		
		function send_email() {
			$("#diaglog_detail").diagShow({
				diag_open: function() {
					htmlDesc.refresh();
				}
			});	
		}
		
		function email_ajax() {
			  var yes = false;
			  yes = window.confirm(words["are you sure send email"]);
			  if(!yes) return;
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: $("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"email",

					  subject: 	$("#subject").val(),
					  content: 	$("#content").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
		  			  $("#wait").loadHide();
					  alert("Error (puti_email_pool_sent.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
		  			  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
							$("#diaglog_detail").diagHide();
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Send successful."}); 
					  }
				  },
				  type: "post",
				  url: "ajax/puti_email_pool_sent.php"
			  });
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
       <input type="button" right="view" onclick="search_ajax()" style="width:100px;" value="<?php echo $words["search"]?>" />                  
       <input type="button" right="print" onclick="output_excel()" style="width:100px; margin-left:10px;" value="<?php echo $words["output excel"]?>" />                  
       <input type="button" right="email" onclick="send_email()" style="width:100px; margin-left:10px;" value="<?php echo $words["send email"]?>" />                  
       <input type="button" right="delete" onclick="clear_pool()" style="width:100px; margin-left:10px;" value="<?php echo $words["clear pool"]?>" />                  
    </fieldset>
 	<div id="tabrow" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>
<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

<div id="diaglog_detail" class="lwhDiag" style="z-index:888;">
	<div class="lwhDiag-content lwhDiag-no-border">
        <table cellpadding="2" cellspacing="0" width="100%">
        	<tr>
            	<td style="white-space:nowrap;"><?php echo $words["subject"]?>: </td>
            	<td><input class="form-input" type="text" id="subject" style="width:450px;" value="<?php echo cTYPE::gstr($emailArr[1][$Glang]["subject"]);?>" /></td>
        	</tr>
        	<tr>
            	<td valign="top" style="white-space:nowrap;">><?php echo $words["content"]?>: </td>
            	<td><textarea id="content" style="width:450px; height:200px; resize:none;"><?php echo cTYPE::gstr($emailArr[1][$Glang]["content"]);?></textarea></td>
        	</tr>
        </table>     
        <center><input type="button"  right="email" id="btn_email_save" onclick="email_ajax()" value="<?php echo $words["send email"]?>" /></center>
	</div>
</div>
</body>
</html>