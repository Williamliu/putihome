<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,140";
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
		<title>Bodhi Meditation Payment Infomation</title>

		<?php include("admin_head_link.php"); ?>

   		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		$(function(){
				$(":input[oper='search']").live("focus", function(ev) {
					$(this).select();
				});

				$(":input[oper='search']").live("keydown", function(ev) {
					if( ev.keyCode == 13 ) {
						search_ajax();
						$(this).select();
					}
				});
	
				$("input#sch_idd").live("keydown", function(ev) {
					if( ev.keyCode == 13 ) {
						$(this).select();
						idd_ajax( $(this).val() );
					}
				});
				
				$(".tabQuery-button-save").live("click", function(ev) {
						var rid = $(this).attr("rid");
						save_ajax(rid, $("input.payamt[rid='" +  rid + "']").val(), $("input.invoice[rid='" +  rid + "']").val() );				
				});
				
				//event_select_ajax();		
		});
		
		function event_select_ajax() {
  			$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					event_id: $("#event_id").val()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
		  			$("#wait").loadHide();
					alert("Error (event_calendar_payment_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
		  			$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						all_html(req.data);
						clear_filter();
					}
				},
				type: "post",
				url: "ajax/event_calendar_payment_select.php"
			});
		}

		function search_ajax() {
  			$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					event_id: 	$("select#event_id").val(),
					sch_name:	$("input#sch_name").val(),
					sch_phone:	$("input#sch_phone").val(),
					sch_email:	$("input#sch_email").val(),
					sch_payment: $("select#sch_payment").val(),
					sch_group:   $("#sch_group").val()				
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
		  			$("#wait").loadHide();
					alert("Error (event_calendar_payment_search.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
		  			$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						all_html(req.data);
						$("input#sch_idd").val("");
					}
				},
				type: "post",
				url: "ajax/event_calendar_payment_search.php"
			});
		}

		function idd_ajax(idd) {
  			$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					event_id: 	$("select#event_id").val(),
					sch_idd:	idd
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
		  			$("#wait").loadHide();
					alert("Error (event_calendar_payment_idd.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
		  			$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						all_html(req.data);
						clear_search();
					}
				},
				type: "post",
				url: "ajax/event_calendar_payment_idd.php"
			});
		}
	
		function clear_filter() {
			$("input#sch_name").val("");
			$("input#sch_phone").val("");
			$("input#sch_email").val("");
			$("select#sch_payment").val("");
			$("input#sch_group").val("");
			$("input#sch_idd").val("");
			$("input#sch_idd").focus();
			$("input#sch_idd").select();
		}

		function clear_search() {
			$("input#sch_name").val("");
			$("input#sch_phone").val("");
			$("input#sch_email").val("");
			$("select#sch_payment").val("");
			$("input#sch_group").val("");
			$("input#sch_idd").focus();
			$("input#sch_idd").select();
		}
		
		function all_html(obj) {
			var ugrp = obj.evt;
			$("#event_attend_list").html("");
			if(ugrp && ugrp.length > 0 ) {	 
				  //var html = '<span id="sch_result" style="font-size:12px; font-weight:bold; margin-left:5px;">. List: Total </span><span id="total_cards" style="font-size:14px; font-weight:bold; color:blue;">' + ugrp.length + '</span><br />';
				  var html = '<table id="mytab_allstudent"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
				  html += '<tr rid="title">';
				  html += '<td colspan="14" style="text-align:center; font-size:16px; font-weight:bold;" class="tabQuery-table-header">' + words["student list"] + '</td>';
				  html += '</tr>';

				  html += '<tr rid="header">';
				  html += '<td class="tabQuery-table-header" width="20">' + words["sn"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["paid"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["paid date"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["amount"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["invoice"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["amount"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["invoice"] + '</td>';
				  html += '<td class="tabQuery-table-header"></td>';

				  html += '<td class="tabQuery-table-header" width="20">' + words["grp"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["name"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["dharma"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["gender"] + '</td>'; 
				  //html += '<td class="tabQuery-table-header">' + words["email"] + '</td>'; 
				  html += '<td class="tabQuery-table-header">' + words["phone"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["g.site"] + '</td>';
				  html += '</tr>';
				  cnt = 0;
				  for(var idx in ugrp) {
					  html += add_html(ugrp[idx]);
				  }
				  html += '</table>';
		
				  $("#event_attend_list").html(html);
			}
		}
		var cnt = 0;
		function add_html(obj) {
			  cnt++;
			  var html = '<tr rid="' + obj.enroll_id + '">';
			  html += '<td width="20" align="center">';
			  html += cnt;
			  html += '</td>';

			  html += '<td class="paid_status" rid="' +  obj.enroll_id + '" align="center">';
			  html +=  obj.paid;
			  html += '</td>';
			  html += '<td class="paid_date" rid="' +  obj.enroll_id + '">';
			  html +=  obj.paid_date;
			  html += '</td>';
			  html += '<td class="paid_amt" rid="' +  obj.enroll_id + '" align="right">';
			  html +=  obj.amt;
			  html += '</td>';
			  html += '<td class="paid_invoice" rid="' +  obj.enroll_id + '" width="80">';
			  html +=  obj.invoice;
			  html += '</td>';
			  html += '<td style="white-space:nowrap;">';
			  html +=  '<b>$</b><input class="payamt" rid="' +  obj.enroll_id + '" type="text" style="width:30px;text-align:center;" value="" />';
			  html += '</td>';
			  html += '<td>';
			  html += '<input class="invoice" rid="' +  obj.enroll_id + '" type="text" style="width:80px;text-align:left;" value="" />';
			  html += '</td>';
			  html += '<td align="center">';
			  html += '<a class="tabQuery-button tabQuery-button-save" oper="save" right="save" rid="' +  obj.enroll_id + '" title="保存"></a>';					 
			  html += '</td>';

			  html += '<td width="20" align="center"><b>';
			  html += obj.group_no;
			  html += '</b></td>';
			  html += '<td>';
			  html +=  obj.name;
			  html += '</td>';
			  html += '<td width="60">';
			  html +=  obj.dharma_name;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.gender;
			  html += '</td>';
			 // html += '<td>';
			 // html +=  obj.email;
			 // html += '</td>';
			  html += '<td>';
			  html +=  obj.phone;
			  html += '</td>';
			  html += '<td style="white-space:nowrap;">';
			  html +=  obj.site;
			  html += '</td>';
			  html += '</tr>';
			  return html;
		}
		
		
		function save_ajax(rid, amt, inv) {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						
						  event_id: 	$("select#event_id").val(),
					  	  enroll_id: 	rid,
						  amount:	    amt,
						  invoice:		inv	
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (event_calendar_payment_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							update_status(req.data.evt);
				  			$("input#sch_idd").focus();
							$("input#sch_idd").select();
						  }
					  },
					  type: "post",
					  url: "ajax/event_calendar_payment_save.php"
				  });
		}
		
		function update_status(obj) {
			$("td.paid_status[rid='" + obj.enroll_id + "']").html(obj.paid);
			$("td.paid_date[rid='" + obj.enroll_id + "']").html(obj.paid_date);
			$("td.paid_amt[rid='" + obj.enroll_id + "']").html(obj.amt);
			$("td.paid_invoice[rid='" + obj.enroll_id + "']").html(obj.invoice);
			$("input.payamt[rid='" + obj.enroll_id + "']").val("");
			$("input.invoice[rid='" + obj.enroll_id + "']").val("");
		}
		
		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	

						$("input[name='event_id']", "form[name='frm_list_excel']").val( $("select#event_id").val() );	
						$("input[name='sch_name']", "form[name='frm_list_excel']").val(  $("input#sch_name").val() );	
						$("input[name='sch_phone']", "form[name='frm_list_excel']").val( $("input#sch_phone").val() );	
						$("input[name='sch_email']", "form[name='frm_list_excel']").val( $("input#sch_email").val() );	
						$("input[name='sch_payment']", "form[name='frm_list_excel']").val( $("select#sch_payment").val() );	
						$("input[name='sch_group']", "form[name='frm_list_excel']").val( $("input#sch_group").val() );	
						
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/event_calendar_payment_print.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("select#event_id").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + $("input#sch_name").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + $("input#sch_phone").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + $("input#sch_email").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_payment" value="' + $("select#sch_payment").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_group" value="' + $("input#sch_group").val() + '" />');				  
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
     <span style="font-size:14px; font-weight:bold; margin-left:10px;"><?php echo $words["event list"]?>: </span>
         <select id="event_id" style="min-width:300px;" onchange="event_select_ajax();">
          <?php 
              $fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));

              $query = "SELECT distinct a.id, a.title, a.start_date, a.end_date, c.title as site_desc  
			  				  FROM event_calendar a 
							  INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
                              INNER JOIN puti_sites c ON (a.site = c.id) 
							  INNER JOIN puti_class d ON (a.class_id = d.id)
							  WHERE a.deleted <> 1 AND a.status = 2 AND
                                    b.deleted <> 1 AND b.status = 1 AND
									d.payfree <> 1 AND
									a.site IN " . $admin_user["sites"] . " AND
									a.branch IN " . $admin_user["branchs"] . "  
                              ORDER BY a.start_date ASC";
              $first = true;
			  $result = $db->query($query);
              echo '<option value=""></option>';
              while( $row = $db->fetch($result) ) {
                  $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                  if( $first ) {
				  		$first = false;
						echo '<option value="' . $row["id"] . '" selected>'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  } else {
				  		echo '<option value="' . $row["id"] . '">'. cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  }
			  }
              
          ?>
          </select>
    <br />
    <table style="margin-left:10px;">
    <tr>
      <td align="right"><b><?php echo $words["name"]?>: </b></td>
      <td>
          <input oper="search" style="width:100px;" id="sch_name" value="" />
      </td>
      <td align="right" style="padding-left:20px;font-weight:bold;"><?php echo $words["phone"]?>: </td>
      <td>
          <input oper="search" style="width:100px;" id="sch_phone" value="" />
      </td>
      <td align="right" style="padding-left:20px;font-weight:bold;"><?php echo $words["email"]?>: </td>
      <td>
          <input oper="search" style="width:100px;" id="sch_email" value="" />
      </td>
      <td align="right" style="padding-left:20px;font-weight:bold;"><?php echo $words["status"]?>: </td>
      <td>
          <select oper="search" id="sch_payment">
          	<option value=""></option>
            <option value="0"><?php echo $words["unpay"]?></option>
            <option value="1"><?php echo $words["paid"]?></option>
          </select>
      </td>
      <td style="padding-left:20px;font-weight:bold;">
          <input type="button" id="btn_search" onclick="search_ajax()" value="<?php echo $words["search"]?>" />
          <input type="button" id="btn_search" onclick="print_event()" value="<?php echo $words["output excel"]?>" />
      </td>
    </tr>
    </table>
    <span style="margin-left:20px;font-weight:bold;"><?php echo $words["group"]?>: </span>
    <input oper="search" style="width:30px;text-align:center;font-size:16px;font-weight:bold;" id="sch_group" value="" />
    
    <span style="font-size:14px; font-weight:bold; margin-left:20px;"><?php echo $words["scan id card here"]?> : </span>
	<input style="width:100px;" id="sch_idd" value="" /> <span style="font-size:14px; font-weight:bold;"></span>
	<br />
    <div id="event_attend_list" style="padding:5px; min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>