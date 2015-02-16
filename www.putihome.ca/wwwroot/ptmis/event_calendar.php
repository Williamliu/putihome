<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,60";
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
		<title>Bodhi Meditation Calendar - Event List</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
		
        <script language="javascript" type="text/javascript">
		var cal;
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
			  $("#diaglog_evt").lwhDiag({
				  titleAlign:		"center",
				  title:			words["event detail"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			640,
				  minHH:			420,
				  btnMax:			false,
				  resizable:		false,
				  movable:			true,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				true
			  });
				
			  $("#btn_submit").bind("click", function(ev) {
			  });

			  cal = new LWH.CALENDAR({
								container:	"#div_calendar",
								admin_sess: $("input#adminSession").val(),
								admin_menu:	$("input#adminMenu").val(),
								admin_oper:	"view",

								site:		 $("#sch_site").val(),
								monthChange: function(yy,mm) {
								},
								dateClick: function(obj) {
									$("#diaglog_evt").diagShow({
										diag_open: function() {
											$("#event_detail","#diaglog_evt").html(dateDetail(obj));
											$("input#event_date").datepicker({ 
												  dateFormat: 'yy-mm-dd',  
												  showOn: "button",
												  buttonImage: "../theme/blue/image/icon/calendar.png",
												  buttonImageOnly: true  
											  });

										},
										diag_close: function() {
											$("#event_detail","#diaglog_evt").empty();
										}
									});
								}
							});
			
			cal.current();
			
			$("#btn_evt_save").bind("click", function(ev) {
				//alert( showObj(getJSON())); return;
			  	$("#wait").loadShow();
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"save",
						
						event_content: 	getJSON()
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
					  	$("#wait").loadHide();
						alert("Error (event_calendar_edit.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
					  	$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							cal.fresh();							
							$("#diaglog_evt").diagHide();
							//$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							//$("#diaglog_message").diagShow({title:words["submit success"]}); 
						}
					},
					type: "post",
					url: "ajax/event_calendar_edit.php"
				});
			}); // end of btn_evt_save 
			
		    $("input.btn-event-delete").live("click", function(ev) {
				var yes = false;
				yes = window.confirm(words["are you sure delete this event?"]);
				if(!yes) return;

				$("#wait").loadShow();
				var eid = $(this).attr("evid");
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"delete",

						event_id: 	eid
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
					  	$("#wait").loadHide();
						alert("Error (event_calendar_edit.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
					  	$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							cal.fresh();							
							$("#diaglog_evt").diagHide();
						}
					},
					type: "post",
					url: "ajax/event_calendar_delete.php"
				});
			});
		}); // end of $(function(){})
		
		function dateDetail(obj) {
			var html = '<table>';
			html += '<tr><td style="font-style:italic;">' + words["site"] + ': </td>';			
			html += '<td><span style="color:blue; font-size:14px; font-weight:bold;">' + words[obj.site_desc.toLowerCase()];
			html += ' - ' + words["event_place"] + ' : ' + obj.place_desc_select;
			html += ' - ' + words[obj.branch_desc.toLowerCase()] + '</span>';
			html += '</td></tr>';			
			
			html += '<tr><td style="font-style:italic;">' + words["date"] + ': </td>';			
			html += '<td>' + obj.start_date + (obj.end_date?' ~ ' + obj.end_date:'') + '</td></tr>';			
			html += '<tr><td style="font-style:italic;">' + words["subject"] + ': </td>';			
			html += '<td><input id="event_title" style="width:100%;" evid="' + obj.event_id +  '" value="' + obj.event_title + '" /></td></tr>';			
			html += '<tr><td style="font-style:italic; width:80px; white-space:nowrap;" valign="top">' + words["description"] + ': </td>';			
			html += '<td valign="top"><textarea  id="event_desc" evid="' + obj.event_id +  '" style="width:100%; height:60px; resize:none;">' + obj.event_description + '</textarea></td></tr>';			
			html += '<tr><td style="font-style:italic; width:80px; white-space:nowrap;" valign="top">' + words["status"] + ': </td>';			
			html += '<td valign="top"><select id="event_status" evid="' + obj.event_id +  '">';
			html += hStatus(obj.event_status);
			html += '</select> <input type="button" class="btn-event-delete" style="float:right;" value="' + words["button delete"] + '"  evid="' + obj.event_id +  '" /></td></tr>';			
			html += '</table>';	
			html += '<hr style="width:90%; height:1px;">';	
			html += '<table width="450">';
			html += '<tr><td style="font-style:italic;">' + words["date"] + ': </td>';			
			html += '<td><input id="event_date" style="width:100px;" value="' + obj.event_date + '" /></td></tr>';			
			html += '<tr><td style="font-style:italic;">' + words["time"] + ': </td>';			
			html += '<td>';

			html += '' + words["from"] + ': ';
			html += cal.hour_html(99, 99, 99, "cal-start-time", obj.start_time);
			html += ' ' + words["to"] + ': ';
			html += cal.hour_html(99, 99, 99, "cal-end-time", obj.end_time);
			html += '</td></tr>';			

			html += '<tr><td style="font-style:italic;">' + words["subject"] + ': </td>';			
			html += '<td><input id="date_title" ddid="' + obj.date_id + '" style="width:100%;" value="' + obj.title + '" /></td></tr>';			
			html += '<tr><td style="font-style:italic; width:80px; white-space:nowrap;" valign="top">' + words["description"] + ': </td>';			
			html += '<td valign="top"><textarea id="date_desc" ddid="' + obj.date_id + '" style="width:100%; height:60px; resize:none;">' + obj.description + '</textarea></td></tr>';			
			html += '<tr><td style="font-style:italic; width:80px; white-space:nowrap;" valign="top">' + words["status"] + ': </td>';			
			html += '<td valign="top"><select id="date_status" ddid="' + obj.date_id + '">';
			html += '<option value="0" ' + (obj.status=="0"?"selected":"") + '>' + words["inactive"] + '</option><option value="1" ' + (obj.status=="1"?"selected":"") + '>' + words["active"] + '</option>';
			html += '</select></td></tr>';			
			html += '</table>';	

			return html;		
		}
		
		function hStatus(s) {
			var html = '';
			html += '<option value="0" ' + (s=="0"?"selected":"") + '>' + words["inactive"] + '</option>';
			html += '<option value="1" ' + (s=="1"?"selected":"") + '>' + words["active"] + '</option>';
			html += '<option value="2" ' + (s=="2"?"selected":"") + '>' + words["open"] + '</option>';
			html += '<option value="9" ' + (s=="9"?"selected":"") + '>' + words["closed"] + '</option>';
			return html;
		}
		
		function getJSON() {
			var jobj = {};
			jobj.id 			= $("input#event_title").attr("evid");
			jobj.title 			= $("input#event_title").val();
			jobj.description 	= $("textarea#event_desc").val();
			jobj.status 		= $("select#event_status").val();
			jobj.place			= $("select.device_place").val();
			
			jobj.date				= {};
			jobj.date.id			= $("input#date_title").attr("ddid");
			jobj.date.event_date	= $("input#event_date").val();
			jobj.date.start_time	= cal.hour_val(99, 99, 99, "cal-start-time");
			jobj.date.end_time		= cal.hour_val(99, 99, 99, "cal-end-time");
			jobj.date.title			= $("input#date_title").val();
			jobj.date.description 	= $("textarea#date_desc").val();
			jobj.date.status 		= $("select#date_status").val();

			return jobj;			
		}

 		function site_change() {
			cal.site = $("#sch_site").val();
			cal.fresh();
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
	<span style="font-size:16px; font-weight:bold;  margin-left:20px;"><span style="color:red;">*** </span>Please Select Location: </span>
    <select id="sch_site" name="sch_site" onchange="site_change();" style="font-size:18px; font-weight:bold; min-width:160px; color:blue;">
    	<?php 
			$res_site = $db->query("SELECT id, title FROM puti_sites WHERE id IN " . $admin_user["sites"] . " ORDER BY id");
			while( $row_site = $db->fetch( $res_site ) ) {
				echo '<option value="' . $row_site["id"] . '"' . ($row_site["id"]==$admin_user["site"]?' selected':'') . '>' . $row_site["title"] . '</option>';
			}
		?>
    </select>
	<div id="div_calendar"></div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_evt" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="event_detail" style="height:380px; overflow:auto;">
        </div>
        <center><input type="button" id="btn_evt_save" right="save" style="margin-top:10px;" value="<?php echo $words["button save"]?>" /></center>
	</div>
</div>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

</body>
</html>