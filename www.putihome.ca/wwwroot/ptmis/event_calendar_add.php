<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,50";
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
		<title>Bodhi Meditation Event - New one-time Event</title>
		
		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
		
        <script language="javascript" type="text/javascript">
		var cal;
		$(function(){
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			"Add to Email Pool Successful",
				
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
			  cal = new LWH.CALENDAR();
			  ///////////////////////////////////////////////////////////////
			  $("#cal_start_date, #cal_end_date").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
							});
			  
			  ////////////////////////////////////////////////////////////////////
			  $("#cal_date_add").unbind("click.calendar").bind("click.calendar", function(ev) {
				  var sdate = null;
				  var edate = null;
				  if($("#cal_start_date").val() != "" && $("#cal_end_date").val() != "") {
					  sdate = new Date($("#cal_start_date").val());
					  edate = new Date($("#cal_end_date").val());
				  } else if($("#cal_start_date").val() == "") {
					  sdate = new Date($("#cal_end_date").val());
					  edate = new Date($("#cal_end_date").val());
				  } else if($("#cal_end_date").val() == "") {
					  sdate = new Date($("#cal_start_date").val());
					  edate = new Date($("#cal_start_date").val());
				  } else {
					  return;
				  }
				  sdate.setDate(sdate.getDate() + 1);
				  edate.setDate(edate.getDate() + 1);
				  if($("#cal_date_repeat").is(":checked")) {
					var wd = $("input:checkbox[name='cal_date_wkdate']:checked").map(function(){ return $(this).val();}).get().join(",")
				  	cal.date_add_html(sdate, edate, wd);
				  } else {
				  	cal.date_add_html(sdate, edate);
				  }
			  });
		  
			  $("#cal_date_clear").unbind("click.calendar").bind("click.calendar", function(ev) {
				  $("#cal_event_list").empty();
			  });
			  
			  $("input.date-btn-clear").die("click.calendar").live("click.calendar", function(ev) {
				  $("li.date-area[yy='" + $(this).attr("yy") + "'][mm='" + $(this).attr("mm") + "'][dd='" + $(this).attr("dd") + "']").remove();
			  });
			  ////////////////////////////////////////////////////////////////////
	
			  $("#cal_date_repeat").bind("click.calendar", function(ev){
			  		if($(this).is(":checked")) {
						$("#cal_date_repeat_content").show();
					} else {
						$("#cal_date_repeat_content").hide();
					}
			  });
		});
		
		function save_event() {
		  	$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: 	$("input#adminSession").val(),
					admin_menu:		$("input#adminMenu").val(),
					admin_oper:		"save",
					
					evt:		cal.toJSON()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();
					alert("Error (event_calendar_add_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
				  	$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
					  $("div#cal_event_list").empty();
					  $("#cal_event_subject").val("");
					  $("#cal_event_desc").val("");
					  $("#cal_start_date").val("");
					  $("#cal_end_date").val("");

					  $(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
					  $("#diaglog_message").diagShow({title:"Submit Success"}); 
					}
				},
				type: "post",
				url: "ajax/event_calendar_add_save.php"
			});
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
	<div id="calendar_add" style="padding:5px; min-height:400px;">
		<table cellpadding="2" cellspacing="0" width="350">
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="top">Subject: </td>
                <td style="white-space:nowrap; width:300px;"><input type="text" id="cal_event_subject" style="width:100%" value="" /></td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="top">Description: </td>
                <td style="white-space:nowrap; width:300px;"><textarea id="cal_event_desc" style="width:100%; height:60px; resize:none;"></textarea></td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="top">Agreement: </td>
                <td style="white-space:nowrap; width:300px;">
                	<select id="agreement" name="agreement">
                	<?php
						$query = "SELECT id, title FROM puti_agreement WHERE status = 1 AND deleted <> 1 ORDER BY created_time";
						$result = $db->query($query);
						echo '<option value=""></option>';
						while( $row = $db->fetch($result) ) {
							echo '<option value="' . $row["id"] . '">' . $row["title"] . '</option>';
						}
					?>
                    </select>
               	</td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; border-bottom:1px solid #666666; padding-bottom:5px; width:50px;" align="right">Date Range: </td>
                <td style="white-space:nowrap; border-bottom:1px solid #666666; padding-bottom:5px; width:300px;" valign="top">
                	From: <input id="cal_start_date" type="text" style="width:100px;" style="vertical-align:middle;" value="" /> 
                    TO: <input id="cal_end_date" type="text" style="width:100px; vertical-align:middle;" value="" /> 
                    <input type="button" id="cal_date_add" value="Add" /> 
                    <input type="button" id="cal_date_clear" value="Clear" /><br />
               </td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; border-bottom:1px solid #666666; padding-bottom:5px; width:50px;" align="right">
                    <label for="cal_date_repeat">Work Day</label><input type="checkbox" id="cal_date_repeat" value="1" />: 
                </td>
                <td style="white-space:nowrap; border-bottom:1px solid #666666; padding-bottom:5px; width:300px;" valign="top">
                    <span id="cal_date_repeat_content" style="display:none;">
	                    <input type="checkbox" id="cal_date_sun" name="cal_date_wkdate" value="0" /><label for="cal_date_sun">Sun</label>  
	                    <input type="checkbox" id="cal_date_mon" name="cal_date_wkdate" value="1" /><label for="cal_date_sun">Mon</label>  
	                    <input type="checkbox" id="cal_date_tue" name="cal_date_wkdate" value="2" /><label for="cal_date_sun">Tue</label>  
	                    <input type="checkbox" id="cal_date_wed" name="cal_date_wkdate" value="3" /><label for="cal_date_sun">Wed</label>  
	                    <input type="checkbox" id="cal_date_thu" name="cal_date_wkdate" value="4" /><label for="cal_date_sun">Thu</label>  
	                    <input type="checkbox" id="cal_date_fri" name="cal_date_wkdate" value="5" /><label for="cal_date_sun">Fri</label>  
	                    <input type="checkbox" id="cal_date_sat" name="cal_date_wkdate" value="6" /><label for="cal_date_sun">Sat</label>  
                    </span>
               </td>
            </tr>
        </table>
        <div id="cal_event_list">
        </div>
        <br />
		<center><input type="button" id="cal_date_save" right="save" onclick="save_event()" value="SAVE" /></center>
        
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