<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,30";
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
		<title>Bodhi Meditation Attendance Report for Class</title>

		<?php include("admin_head_link.php"); ?>
		
   		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		$(function(){
			  ///////////////////////////////////////////////////////////////
			  $("#start_date, #end_date").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
			  });
			  list_event();
		});
		
		function list_event() {
		  	$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					start_date: $("#start_date").val(),
					end_date: 	$("#end_date").val()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();
					alert("Error (event_calendar_report_event.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
				  	$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						evtToHTML(req.data.evt);
						//jsonToHTML(req.data.evt);
					}
				},
				type: "post",
				url: "ajax/event_calendar_report_event.php"
			});
		}

		function report_event() {
		  	$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					event_id: 	$("#event_id").val()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();
					alert("Error (event_calendar_attend_report.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
				  	$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						jsonToHTML(req.data.evt);
					}
				},
				type: "post",
				url: "ajax/event_calendar_attend_report.php"
			});
		}
		
		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	

						$("input[name='event_id']", "form[name='frm_list_excel']").val( $("#event_id").val() );	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/event_calendar_attend_report_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("#event_id").val() + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function evtToHTML(eObj) {
			var html = '<select id="event_id">';
			html += '<option value=""></option>';
			for(var idx in eObj) {
				var ttt = eObj[idx].title + "[" + eObj[idx].start_date + "~" + eObj[idx].end_date +"]";  
				html += '<option value="' + eObj[idx].id +'">' + ttt + '</option>';
			}
			html += '</select>';
			$("#event_list").html(html);
		}
		
		function jsonToHTML( evtObj ) {
				var c1 = ' style="background-color:#FFF5D7;"';
				var c2 = ' style="background-color:#EBFAD3;"';

				var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadd/*ing="2" cellspacing="0">';
				html += '<tr>';
				html += '<td colspan="13" align="center"><span style="font-size:12px; font-weight:bold;">' + words["event attendance report"] + '</span></td>';
				html += '</tr>';
				html += '<tr>';
				html += '<td colspan="3" class="tabQuery-table-header" style="text-align:left;">' + words["event title"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["start date"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["end date"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["status"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2" title="报名人数">' + words["enroll"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2" title="出席人数">' + words["att.pp"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2" title="出勤率">' 	+ words["att.rate"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2" title="新人数">' + words["new people"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2" title="试听人数">' + words["trial"] + '</td>';
				html += '</tr>';

				html += '<tr>';
				html += '<td width="30" class="tabQuery-table-header"></td>';
				html += '<td class="tabQuery-table-header">' + words["day no."] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["date"] + '</td>';
				html += '<td colspan="3" class="tabQuery-table-header">' + words["class subject"] + '</td>';
				html += '</tr>';

				html += '<tr>';
				html += '<td colspan="3" align="left"' + c1 + '><b>';
				html += evtObj.title;
				html += '</b></td>';

				html += '<td' + c1 + '><b>';
				html +=  evtObj.start_date; 
				html += '</b></td>';
				
				html += '<td' + c1 + '><b>';
				html +=  evtObj.end_date;
				html += '</b></td>';
				
				html += '<td align="center"' + c1 + '><b>';
				html +=  evtObj.status;
				html += '</b></td>';

				html += '<td align="right"' + c1 + '><b>';
				html +=  evtObj.enroll;
				html += '</b></td>';

				html += '<td align="right"' + c1 + '><b>';
				html +=  evtObj.attend;
				html += '</b></td>';

				html += '<td align="right"' + c1 + '><b>';
				html +=  evtObj.att_per;
				html += '</b></td>';

				html += '<td align="right"' + c1 + '><b>';
				html +=  evtObj.new_flag;
				html += '</b></td>';

				html += '<td align="right"' + c1 + '><b>';
				html +=  evtObj.trial;
				html += '</b></td>';
                
				
                html += '</tr>';
				
				for(var idx in evtObj.dates) {
					var ee = evtObj.dates[idx];
					html += '<td width="40" align="center">';
					html += '</td>';

					html += '<td align="center"' + c2 + '><b>';
					html +=  ee.day_no; //+ '{<span style="color:blue;">' + ee.date_range +'</span>}';
					html += '</b></td>';
					
					html += '<td' + c2 + '>';
					html +=  ee.event_date_desc;
					html += '</td>';
					
					html += '<td colspan="3" ' + c2 + '>';
					html +=  ee.title;
					html += '</td>';

					html += '<td align="right"' + c2 + '>';
					html +=  ee.enroll;
					html += '</td>';

					html += '<td align="right"' + c2 + '>';
					html +=  ee.attend;
					html += '</td>';

					html += '<td align="right"' + c2 + '>';
					html +=  ee.att_per;
					html += '</td>';

					html += '<td align="right"' + c2 + '>';
					html +=  ee.new_flag;
					html += '</td>';

					html += '<td align="right"' + c2 + '>';
					html +=  ee.trial;
					html += '</td>';

					html += '</tr>';
				}
				html += '</table>';
				$("#calendar_report").html(html);
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <span style="font-size:12px; font-weight:bold; margin-left:10px;"><?php echo $words["date range"]?>: </span>
    From <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("j")-30,date("Y")));?>" /> 
    TO <input style="width:80px;" id="end_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("j")+30,date("Y")));?>" />
    <input type="button" id="btn_search" right="view" onclick="list_event()" value="<?php echo $words["search event"]?>" /> 
    <br /> 
    <span style="font-size:12px; font-weight:bold; margin-left:10px;"><span style="color:red;">* </span><?php echo $words["event list"]?>: </span> 
    <span id="event_list">
            <select id="event_id" style="min-width:300px;">
            <?php 
                $fdate 	= mktime(0,0,0, 1 ,1, date("Y"));

                $query = "SELECT a.id, a.title, a.start_date, a.end_date, b.title as site_desc 
								FROM event_calendar a 
 								INNER JOIN puti_sites b ON (a.site = b.id) 
								WHERE   a.deleted <> 1 AND a.start_date >= '" . $fdate . "' AND
										a.site IN " . $admin_user["sites"]  . " AND
										a.branch IN ". $admin_user["branchs"] . " 
								ORDER BY a.start_date ASC";
                $result = $db->query($query);
                echo '<option value=""></option>';
                while( $row = $db->fetch($result) ) {
                    $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                    echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . $row["title"] . "[" . $date_str . ']</option>';
                }
            ?>
            </select>
    </span>
    <br />
    <input type="button" id="btn_print" right="view" style="margin-left:50px;" onclick="report_event()"  value="<?php echo $words["g.report"]?>" /> 
    <input type="button" id="btn_print" right="print" onclick="print_event()"  value="<?php echo $words["output excel"]?>" /> 
	<br />
	<div id="calendar_report" style="padding:5px; min-height:420px;">
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>