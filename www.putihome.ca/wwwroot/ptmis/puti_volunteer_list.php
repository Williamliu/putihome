<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="10,30";
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
		<title>Bodhi Meditation Volunteer List</title>
		
		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var ctt = null;
		$(function(){
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
				pin:				false
			});
			
			$("#diaglog_ss").lwhDiag({
				titleAlign:		"center",
				title:			 words["volunteer - merge"],
				
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

			$("#diaglog_detail").lwhDiag({
				titleAlign:		"center",
				title:			words["volunteer information"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			600,
				minHH:			370,
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
											  sch_name:"#sch_name",
											  sch_phone:"#sch_phone",
											  sch_email:"#sch_email",
											  sch_gender:"#sch_gender",
											  sch_status:"#sch_status",
											  sch_city:"#sch_city",
											  sch_depart:"#sch_depart"
										  },
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:20},
											  {title: words["c.id"], 		col:"id", 			sq:"ASC"},
											  {title: words["c.name"], 		col:"cname", 		sq:"ASC"},
											  {title: words["e.name"], 		col:"en_name", 		sq:"ASC"},
											  {title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
											  {title: words["gender"], 		col:"gender", 		sq:"ASC"},
											  {title: words["email"], 		col:"email", 		sq:"ASC"},
											  {title: words["phone"], 		col:"phone", 		sq:"ASC"},
											  {title: words["city"], 		col:"city", 		sq:"ASC", align:"center"},
											  {title: words["status"], 		col:"status", 		sq:"ASC", align:"center"},
											  {title: words["r.date"], 		col:"created_time",	sq:"DESC"},
											  {title:"", 					col:""}
										  ],
										  container: 	"#tabrow",
										  me:			"ctt",

										  url:		"ajax/puti_volunteer_list_select.php",
										  orderBY: 	"cname",
										  orderSQ: 	"ASC",
										  cache:		true,
										  expire:		3600,
										  
										  admin_sess: 	$("input#adminSession").val(),
										  admin_menu:		"<?php echo $admin_menu;?>",
										  admin_oper:		"view",
										  
										  button:		true,
										  view:			true,
										  output:		false,
										  remove:		true
									  });
		  
			ctt.start();
			
			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  $("#wait").loadShow();
				  var hid = $(this).attr("rid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	"<?php echo $admin_menu;?>",
						  admin_oper:	"view",
						  
						  hid: 			hid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_list_detail.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  $("#diaglog_detail").diagShow({
									diag_open: function() {
										  // tabber 1
										  $("input#hid").val(req.data.hid);
										  $("input#dharma_name").val(req.data.dharma_name);
										  $("input#cname").val(req.data.cname);
										  $("input#pname").val(req.data.pname);
										  $("input#en_name").val(req.data.en_name);
										  $("input:radio[name='gender'][value='" + req.data.gender + "']").attr("checked",true);
										  
										  $("input#email").val(req.data.email);
										  $("input#phone").val(req.data.phone);
										  $("input#cell").val(req.data.cell);
										  $("input#city").val(req.data.city);
										  $("select#status").val(req.data.status);
										  // tabber 2
										  $.map( req.data.depart.split(","), function(n) {
												$("input:checkbox.department[value='" + n + "']").attr("checked",true);
										  });
										  $("#total_hour").html(req.data.total_hour);
										  $("#work_count").html(req.data.work_count);
										  recToHTML(req.data.record);

									},
									diag_close: function() {
										clearDetail();
									}
							   });
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_list_detail.php"
				  });
			});

			$(".tabQuery-button[oper='delete']").live("click", function(ev) {
				 var hid = $(this).attr("rid");
				  $("#diaglog_ss").diagShow({
					  	diag_open:	function() {
							$("#merge_id").val("");
							$("#merge_id").focus();
						},
						diag_close: function() {
							var mid = $("#merge_id").val();
							merge_ajax(hid, mid);
						}
				  });				  
				  return;
			});

			$(".tabQuery-button[oper='hour-delete']").live("click", function(ev) {
				  var yes = false;
				  yes = window.confirm("Are you sure delete this record？");
				  if(!yes) return;

				  $("#wait").loadShow();
				  var vid = $(this).attr("vid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	"<?php echo $admin_menu;?>",
						  admin_oper:	"delete",
						  
						  vid: 			vid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_list_hour_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  $("tr.hour-record[vid='" + req.data.vid + "']").remove();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_list_hour_delete.php"
				  });
			});

			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					ctt.start();
				}
			});



		});
		
		function clearDetail() {
				  // tabber 1
				  $("input#hid").val("");
				  $("input#dharma_name").val("");
				  $("input#cname").val("");
				  $("input#pname").val("");
				  $("input#en_name").val("");
				  $("input:radio[name='gender']").attr("checked",false);
				  
				  
				  $("input#email").val("");
				  $("input#phone").val("");
				  $("input#cell").val("");
				  $("input#city").val("");
				  $("select#status").val("");

				  // tabber 2
				  $("input:checkbox.department").attr("checked",false);
				  $("#total_hour").empty();
				  $("#work_count").empty();
				  $("#records").empty();
		}
		
		function merge_close() {
			  $("#diaglog_ss").diagHide();
		}
		
		function merge_ajax(hid, mid) {
 				  var yes = false;
				  yes = window.confirm(words["if merge successful, it will delete this record. are you sure?"]);
				  if(!yes) return;
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	"<?php echo $admin_menu;?>",
						  admin_oper:	"delete",
						  
						  hid: 			hid,
						  mid:			mid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_list_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
					  url: "ajax/puti_volunteer_list_delete.php"
				  });
		}
		
		function save_ajax() {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	"<?php echo $admin_menu;?>",
						  admin_oper:	"save",

						  hid: 			$("input#hid").val(),
						  cname: 		$("input#cname").val(),
						  pname: 		$("input#pname").val(),
						  en_name: 		$("input#en_name").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  gender:		$("input:radio[name='gender']:checked").val(),
						  email: 		$("input#email").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  city: 		$("input#city").val(),
						  status: 		$("select#status").val(),
						  depart:		$("input:checkbox.department:checked").map(function(){ return $(this).val();}).get().join(","),
						  record:		recToJSON()

					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_list_detail_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							ctt.fresh();
							$("#diaglog_detail").diagHide();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_list_detail_save.php"
				  });
		}
		
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
						$("input[name='sch_depart']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_depart);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_volunteer_list_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + ctt.tabData.condition.sch_name + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + ctt.tabData.condition.sch_phone + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + ctt.tabData.condition.sch_email + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_gender" value="' + ctt.tabData.condition.sch_gender + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_status" value="' + ctt.tabData.condition.sch_status + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" 	value="' + ctt.tabData.condition.sch_city + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_depart" value="' + ctt.tabData.condition.sch_depart + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function search_ajax() {
			ctt.start();
		}
		
		function recToHTML(rObj) {
			  var html = '<table class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
				  html += '<tr>';
				  html += '<td width="20" class="tabQuery-table-header">' + words["sn"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["department"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["work for"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["work date"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["hours"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["del."] + '</td>';
				  html += '</tr>';
				  
				  
				  for(var idx in rObj) {
					  html += '<tr class="hour-record" vid="' + rObj[idx].id + '">';

					  html += '<td width="20" align="center">';
					  html += parseInt(idx) + 1;
					  html += '</td>';
					  html += '<td>';
					  html +=  rObj[idx].title;
					  html += '</td>'
					  html += '<td>';
					  html +=  '<input class="record-purpose" vid="' + rObj[idx].id + '" style="width:100px; text-align:left;" value="' + rObj[idx].purpose + '" />';
					  html += '</td>';
					  html += '<td>';
					  html +=  '<input class="record-date" vid="' + rObj[idx].id + '" style="width:80px; text-align:center;" value="' + rObj[idx].work_date + '" />';
					  html += '</td>';
					  html += '<td>';
					  html +=  '<input class="record-hour" vid="' + rObj[idx].id + '" style="width:60px; text-align:right;" value="' + rObj[idx].work_hour + '" />';
					  html += '</td>';
					  html += '<td align="center">';
					  html += '<a class="tabQuery-button tabQuery-button-delete" oper="hour-delete" right="delete" vid="' + rObj[idx].id + '" title="Delete Record"></a>';					 
					  html += '</td>';
					  html += '</tr>';
				  }
				  html += '</table>';

				  $("#records").html(html);

				  $(".record-date").datepicker({ 
									dateFormat: 'yy-mm-dd',  
									showOn: "button",
									buttonImage: "../theme/blue/image/icon/calendar.png",
									buttonImageOnly: true  
								});

		}
		
		function recToJSON() {
			var rObj = [];
			$(".record-purpose").each(function(idx1, el1) {
                var r ={};
				r.id 		= $(this).attr("vid");
				r.purpose 	= $(this).val();
            	r.work_date = $(".record-date[vid='" + r.id + "']").val();
				r.work_hour = $(".record-hour[vid='" + r.id + "']").val();
				rObj[rObj.length] = r;
			});
			return rObj;
		}
  
 		function add_email() {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	"<?php echo $admin_menu;?>",
						  admin_oper:	"email",

						  orderBY: 	ctt.tabData.condition.orderBY,
						  orderSQ: 	ctt.tabData.condition.orderSQ,

						  sch_name: 	ctt.tabData.condition.sch_name,
						  sch_phone: 	ctt.tabData.condition.sch_phone,
						  sch_email: 	ctt.tabData.condition.sch_email,
						  sch_gender:	ctt.tabData.condition.sch_gender,
						  sch_status:	ctt.tabData.condition.sch_status,
						  sch_city:		ctt.tabData.condition.sch_city,
						  sch_depart:	ctt.tabData.condition.sch_depart
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_list_add_email.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
					  url: "ajax/puti_volunteer_list_add_email.php"
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
        	<table border="0" cellpadding="0">
            	<tr>	
                	<td valign="top">
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><?php echo $words["name"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["phone"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><?php echo $words["email"]?>: </td>
                                  <td><input oper="search" style="width:120px;" id="sch_email" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["city"]?>: </td>
                                  <td>
									<input oper="search" style="width:120px;" id="sch_city" style="width:100px;" value="" />                                  
                                  </td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><?php echo $words["gender"]?>: </td>
                                  <td>
                                      <select oper="search" style="width:120px;" id="sch_gender">
                                          <option value=""></option>
                                          <option value="Male"><?php echo $words["male"]?></option>
                                          <option value="Female"><?php echo $words["female"]?></option>
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
                                  </td>
                              </tr>
                          </table>
                    </td>
				</tr>
                <tr>
                	<td colspan="3">
                         <input type="button" right="view" onclick="search_ajax()" style="width:100px;" value="<?php echo $words["search"]?>" />                  
                         <input type="button" right="print" onclick="output_excel()" style="width:100px; margin-left:10px;" value="<?php echo $words["output excel"]?>" />                  
                         <input type="button" right="email" onclick="add_email()" style="width:100px; margin-left:10px;" value="<?php echo $words["email pool"]?>" />                  
                   		 <span style="margin-left:20px;"><?php echo $words["belong department"]?>: </span>
                          <select oper="search" id="sch_depart">
                              <option value=""></option>
                              <?php
                                  $result = $db->query("SELECT id, title, en_title FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
                                  $html = '';
                                  $cnt=0;
                                  while($row = $db->fetch($result)) {
                                      $cnt++;
                                      $html .= '<option value="' . $row["id"] . '">' . $cnt . '. ' . ($admin_user["lang"]!="en"?cTYPE::gstr($row["title"]):cTYPE::gstr($row["en_title"])) . '</option>';
                                  }
                                  echo $html;
                              ?>
                              
                          </select>    
                    </td>
                </tr>
             </table>  
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
          <div id="tabber_detail" class="lwhTabber lwhTabber-mint" style="width:580px;">
              <div class="lwhTabber-header">
                  <a><?php echo $words["personal information"]?><s></s></a>
                  <a><?php echo $words["belong department"]?><s></s></a>
                  <a><?php echo $words["volunteer records"]?><s></s></a>
                  <div class="line"></div>    
              </div>
              <div class="lwhTabber-content" style="height:300px; border-width:3px;">
                  <div>
					<!------------------------------------------------------------------>
                            <table cellpadding="2" cellspacing="0" width="100%">
                                <tr>
                                     <td class="title"><?php echo $words["dharma name"]?>: </td>
                                     <td>
                                        <input class="form-input" id="dharma_name" name="dharma_name" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["chinese name"]?>: </td>
                                     <td>
                                       	<input type="hidden" id="hid" name="hid" value="" />
                                        <input class="form-input" id="cname" name="cname" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["pinyin"]?>: </td>
                                     <td>
                                        <input class="form-input" id="pname" name="pname" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["english name"]?>: </td>
                                     <td>
                                        <input class="form-input" id="en_name" name="en_name" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["gender"]?>: </td>
                                     <td>
                                        <input type="radio" id="gender_male" name="gender" value="Male" /><label for="gender_male">Male</label> 
                                        <input type="radio" id="gender_female" name="gender" value="Female" /><label for="gender_female">Female</label>
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title line"><?php echo $words["email"]?>: </td>
                                     <td class="line">
                                        <input class="form-input" id="email" name="email" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["phone"]?>: </td>
                                     <td>
                                        <input class="form-input" id="phone" name="phone" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["cell"]?>: </td>
                                     <td>
                                        <input class="form-input" id="cell" name="cell" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["city"]?>: </td>
                                     <td>
                                        <input class="form-input" id="city" name="city" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["status"]?>: </td>
                                     <td>
                                        <select id="status" name="status">
                                            <option value=""></option>
                                            <option value="0"><?php echo $words["inactive"]?></option>
                                            <option value="1"><?php echo $words["active"]?></option>
                                        </select>
                                        <span class="required">*</span>
                                     </td>
                                </tr>
                            </table>                    
					<!------------------------------------------------------------------>
                  </div>
                  <div>
					<!------------------------------------------------------------------>
                    <?php echo $words["belong department"]?>: <br />
                    <div style="border:1px solid #cccccc; padding:5px;">
                        <?php
                            $result = $db->query("SELECT id, title, en_title FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
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
                                $html .= '<input type="checkbox" id="depart_' . $row["id"] . '" class="department" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  ($admin_user["lang"]!="en"?cTYPE::gstr($row["title"]):cTYPE::gstr($row["en_title"])) . '</label>';
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
                  </div>

                  <div>
					<!------------------------------------------------------------------>
						<b><?php echo $words["total volunteer hours"]?>: </b><span id="total_hour" style="font-size:14px; font-weight:bold;color:blue;"></span> 
                        <b><?php echo $words["counts"]?>: </b> <span id="work_count" style="font-size:14px; font-weight:bold;color:blue;"></span> 
                        <div id="records" style="width:100%; overflow:auto;">
                        </div>
                    <!------------------------------------------------------------------>
                  </div>
              </div>
              <center><input type="button"  right="save" id="btn_detail_save" onclick="save_ajax()" value="<?php echo $words["button save"]?>" /></center>
          </div> <!-- end of "lwhTabber" -->
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


</body>
</html>