<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="5,10";
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
		<title>Bodhi Meditation Member List</title>

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
		var detailObj = null;
		var aj = null;
		var htmlObj = new LWH.cHTML();
		$(function(){
			if( $(":radio[name='therapy']:checked").val() == "Yes") 
					$("#div_therapy_yes").show();
				else 
					$("#div_therapy_yes").hide();

						
			$(":radio[name='therapy']").bind("click", function(ev) {
				if($(this).val() == "Yes") 
					$("#div_therapy_yes").show();
				else 
					$("#div_therapy_yes").hide();
				 
			});

			$("#diaglog_ss").lwhDiag({
				titleAlign:		"center",
				title:			 words["member - merge"],
				
				cnColor:		"#F8F8F8",
				bgColor:		"#EAEAEA",
				ttColor:		"#94C8EF",
				 
				minWW:			300,
				minHH:			120,
				zIndex:			8888,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			words["add email success"],
				
				cnColor:		"#F8F8F8",
				bgColor:		"#EAEAEA",
				ttColor:		"#94C8EF",
				 
				minWW:			400,
				minHH:			250,
				btnMax:			false,
				resizable:		false,
				movable:		false,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});


			$("#diaglog_detail").lwhDiag({
				titleAlign:		"center",
				title:			words["member details"],
				
				cnColor:		"#F8F8F8",
				bgColor:		"#EAEAEA",
				ttColor:		"#94C8EF",
				 
				minWW:			740,
				minHH:			430,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			  
			ctt = new LWH.cTABLE({
											condition: 	{
												sch_name:	 "#sch_name",
												sch_phone:	 "#sch_phone",
												sch_email:	 "#sch_email",
												sch_gender:	 "#sch_gender",
												sch_status:	 "#sch_status",
												sch_online:	 "#sch_online",
												sch_level:	 "#sch_level",
												sch_plate_no:"#sch_plate_no",
												sch_memid:	 "#sch_memid",
												sch_idd:	 "#sch_idd",
												sch_email_flag: "#sch_email_flag",
												sch_language:   "#sch_language",
                                                sch_city:	 "#sch_city",
												sch_site:	"#sch_site"
											},
											headers:[
												{title: words["sn"], 			col:"rowno",		width:20},
												{title: words["name"], 			col:"flname", 		sq:"ASC"},
												{title: words["legal name"], 	col:"legal_name", 	sq:"ASC"},
												//{title: words["last name"], 	col:"last_name", 	sq:"ASC"},
												{title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
												{title: words["m.alias"], 		col:"alias", 		sq:"DESC"},
												//{title: words["gender"], 		col:"gender", 		sq:"ASC"},
												//{title: words["email"], 		col:"email", 		sq:"ASC"},
												{title: words["phone"], 		col:"phone", 		sq:"ASC"},
												{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												{title: words["short.lang"], 	col:"language", 	sq:"ASC", align:"center"},
												{title: words["g.site"], 		col:"site", 		sq:"ASC", align:"center"},
												{title: words["date"], 			col:"created_time",	sq:"DESC"},
											    {title: words["c.id"], 			col:"id", 			sq:"ASC"},
											    {title: words["c.photo"], 		col:"photo", 		align:"center"},
												{title:"", 						col:""}
											],
											container: 	"#tabcon",
											me:			"ctt",

											url:		"ajax/puti_members_select.php",
											orderBY: 	"created_time",
											orderSQ: 	"DESC",
											cache:		true,
											expire:		3600,
											
											admin_sess: $("input#adminSession").val(),
											admin_menu:	$("input#adminMenu").val(),
						  					admin_oper:	"view"
										});
			
			ctt.start();
			
			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});

			$(".tabQuery-button[oper='delete']").live("click", function(ev) {
				  var from_id = $(this).attr("rid");
				  $("#diaglog_ss").diagShow({
					  	diag_open:	function() {
							$("#merge_id").val("");
							$("#merge_id").focus();
						},
						diag_close: function() {
							var to_id = $("#merge_id").val();
							merge_ajax(from_id, to_id);
						}
				  });				  
				  return;
			});

			// output signature form
			$(".tabQuery-button[oper='print']").live("click", function(ev){
				  var eid = 0;
				  var mid = $(this).attr("rid");
				  print_signature(eid,mid);
			});	

			/*
			$(".tabQuery-button[oper='print']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  if( $("iframe[name='ifm_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_excel']").val("view");	
						$("input[name='member_id']", "form[name='frm_excel']").val(member_id);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_excel']").attr({"action":"ajax/puti_members_detail_output.php", "target": "ifm_excel" }); 
						$("form[name='frm_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
						$("form[name='frm_excel']").append('<input type="hidden" name="member_id" value="' + member_id + '" />');	
				  }
				  $("form[name='frm_excel']").submit();			  
			});
			*/
		
			$("input#sch_idd, :input[oper='search']").bind("keydown", function(ev) {
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

			$("#btn_print_empty").bind("click", function(ev) {
				print_signature(0, 0);
			});
		});
		
		function output_excel() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	
						$("input[name='orderBY']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderBY);	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderSQ);	

						$("input[name='sch_name']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_name);	
						$("input[name='sch_phone']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_phone);	
						$("input[name='sch_email']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_email);	
						$("input[name='sch_gender']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_gender);	
						$("input[name='sch_status']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_status);	
						$("input[name='sch_city']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_city);	
						$("input[name='sch_site']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_site);	
						$("input[name='sch_email_flag']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_email_flag);	
						$("input[name='sch_language']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_language);	
						$("input[name='sch_online']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_online);	
						$("input[name='sch_level']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_level);	
						$("input[name='sch_plate_no']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_plate_no);	
						$("input[name='sch_memid']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_memid);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_members_list_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + ctt.tabData.condition.sch_name + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + ctt.tabData.condition.sch_phone + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + ctt.tabData.condition.sch_email + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_gender" value="' + ctt.tabData.condition.sch_gender + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_status" value="' + ctt.tabData.condition.sch_status + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" value="' + ctt.tabData.condition.sch_city + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_site" value="' + ctt.tabData.condition.sch_site + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email_flag" value="' + ctt.tabData.condition.sch_email_flag + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_language" value="' + ctt.tabData.condition.sch_language + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_online" value="' + ctt.tabData.condition.sch_online + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_level" value="' + ctt.tabData.condition.sch_level + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_plate_no" value="' + ctt.tabData.condition.sch_plate_no + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_memid" value="' + ctt.tabData.condition.sch_memid + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		

		function search_ajax() {
			ctt.start();
		}
		
		function merge_close() {
			  $("#diaglog_ss").diagHide();
		}

		function merge_ajax(fid, tid) {
				  var yes = false;
				  yes = window.confirm( words["are you sure to merge this record?"] );
				  if(!yes) return;
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",
						  from_id: 		fid,
						  to_id:		tid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_members_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
					  url: "ajax/puti_members_delete.php"
				  });
		}
		
		function add_email() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"email",
			  
					  orderBY: 	ctt.tabData.condition.orderBY,
					  orderSQ: 	ctt.tabData.condition.orderSQ,
			  
					  sch_name: 	ctt.tabData.condition.sch_name,
					  sch_phone: 	ctt.tabData.condition.sch_phone,
					  sch_email: 	ctt.tabData.condition.sch_email,
					  sch_gender:	ctt.tabData.condition.sch_gender,
					  sch_status:	ctt.tabData.condition.sch_status,
					  sch_idd:		ctt.tabData.condition.sch_idd,
    				  sch_email_flag: ctt.tabData.condition.sch_email_flag,
    				  sch_language: ctt.tabData.condition.sch_language,
					  sch_city:		ctt.tabData.condition.sch_city,
					  sch_site:		ctt.tabData.condition.sch_site,
					  sch_online:	ctt.tabData.condition.sch_online,
					  sch_level:	ctt.tabData.condition.sch_level,
					  sch_plate_no:	ctt.tabData.condition.sch_plate_no,
					  sch_memid:	ctt.tabData.condition.sch_memid
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (puti_members_add_email.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
						$("#diaglog_message").diagShow(); 
					  }
				  },
				  type: "post",
				  url: "ajax/puti_members_add_email.php"
			  });
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
		
		function fresh_ajax() {
			ctt.fresh();	
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
        	<table border="0" cellpadding="0">
            	<tr>	
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["name"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["phone"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><b><?php echo $words["id number"]?>:</b> </td>
                                  <td><input id="sch_idd" style="width:120px;" value="" /></td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["email"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_email" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["city"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_city" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["email subscription"]?>: </td>
                                  <td>
                                        <select id="sch_email_flag" style="width:120px;" name="sch_email_flag">
                                            <option value=""></option>
                                            <option value="0"><?php echo $words["email.unsubscribe"]?></option>
                                            <option value="1"><?php echo $words["email.subscribe"]?></option>
                                        </select>
                                  </td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["gender"]?>: </td>
                                  <td>
                                      <select oper="search" style="width:120px;" id="sch_gender">
                                          <option value=""></option>
                                          <option value="Male"><?php echo $words["male"]?></option>
                                          <option value="Female"><?php echo $words["female"]?></option>
                                      </select>
                                      <span style="margin-left:20px;"></span>
                                      <?php echo $words["web"]?>: 
                                      <select oper="search" id="sch_online">
                                          <option value=""></option>
                                          <option value="1"><?php echo $words["yes"]?></option>
                                          <option value="0"><?php echo $words["no"]?></option>
                                      </select>
                                      <span style="margin-left:20px;"><?php echo $words["member.title"]?>: </span>
                                      <select id="sch_level" style="text-align:center;" name="sch_level">
                                          <option value=""></option>
                                          <?php
                                              $result_lvl = $db->query("SELECT * FROM puti_info_title order by id");
                                              while( $row_lvl = $db->fetch($result_lvl) ) {
                                                  echo '<option value="' . $row_lvl["id"] . '">' . $row_lvl["title"] . '</option>';
                                              }
                                          ?>
                                      </select>
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["status"]?>: </td>
                                  <td>
                                      <select oper="search" style="width:120px;" id="sch_status">
                                          <option value=""></option>
                                          <option value="0"><?php echo $words["inactive"]?></option>
                                          <option value="1"><?php echo $words["active"]?></option>
                                      </select>    
                                        <span style="margin-left:20px;"></span>
                                      <?php echo $words["plate no"]?>: 
                                      <input type="text" style="width:80px;" oper="search" id="sch_plate_no" name="sch_plate_no" value="" />
                                        <span style="margin-left:20px;"></span>
                                      <?php echo $words["member id"]?>: 
                                      <input type="text" style="width:60px;" oper="search" id="sch_memid" name="sch_memid" value="" />
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["short.lang"]?>: </td>
                                  <td>
                                        <?php
                                              echo iHTML::select1($admin_user["lang"], $db, "vw_vol_language", "sch_language", "");
                                        ?>
                                        <span style="margin-left:20px;"></span>
										<?php echo $words["g.site"]?>: 
                                        <select id="sch_site" name="sch_site">
                                              <option value=""></option>
                                              <?php
                                                  $result_site = $db->query("SELECT id, title FROM puti_sites WHERE status = 1 AND id in " . $admin_user["sites"] . " ORDER BY id"); 
                                                  while( $row_site = $db->fetch($result_site) ) {
                                                      echo '<option value="' . $row_site["id"] . '">' . $words[strtolower($row_site["title"])] . '</option>';		
                                                  }
                                              ?>
                                        </select>

                                  </td>
                              </tr>

                          </table>
                    </td>
				</tr>
                <tr>
                    <td colspan="3" valign="middle">
                       <input type="button" right="view"  onclick="search_ajax()" style="width:100px; vertical-align:middle;" value="<?php echo $words["search"]?>" />                  
                       <input type="button" right="view"  onclick="fresh_ajax()" style="width:100px; vertical-align:middle;" value="<?php echo $words["button refresh"]?>" />                  
                       <input type="button" right="print" onclick="output_excel()" style="width:100px; margin-left:10px; vertical-align:middle;" value="<?php echo $words["output excel"]?>" />                  
                       <input type="button" right="email" onclick="add_email()" style="width:100px; margin-left:10px; vertical-align:middle;" value="<?php echo $words["email pool"]?>" />   
                       <a id="btn_print_empty" class="tabQuery-button tabQuery-button-output" style=" vertical-align:middle; margin-left:20px;" right="print" title="<?php echo $words["print empty signature"]?>"></a>               
                    </td>
                </tr>
        </table>  
    </fieldset>
 	<div id="tabcon" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

<div id="diaglog_ss" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<span style="color:red;"><?php echo $words["please assign this record to other id"]?></span>
        <br /><br />
        <?php echo $words["c.id"]?>: <input class="form-input" id="merge_id" value="" /><br />
        <br />
        <center><input type="button" onclick="merge_close()" value="<?php echo $words["button merge"]?>" /></center> 
	</div>
</div>

<?php include("tpl_member_detail.php"); ?>
</body>
</html>