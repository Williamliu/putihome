<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,110";
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
		<title>Bodhi Meditation Attendance Calculation</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.global.timer.js"></script>

 		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		$(function(){
			$("#group_edit").lwhTabber();
			
			$("#diaglog_message").lwhDiag({
				  titleAlign:		"center",
				  title:			words["event calculation"],
				  
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
			  
			////////////////////////////////////////////////////////////////////
			$("#diaglog_event").lwhDiag({
				titleAlign:		"center",
				title:			words["please select the event"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			600,
				minHH:			150,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		false,
				maskClick:		true,
				pin:			false
			});


			$("#sch_sdate, #sch_edate").datepicker({ 
							  dateFormat: 'yy-mm-dd',  
							  showOn: "button",
							  buttonImage: "../theme/blue/image/icon/calendar.png",
							  buttonImageOnly: true  
			});
			
			$(".tabQuery-button-delete").live("click", function(ev) {
				del_idd( $(this).attr("rid") );
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
		});
		
		function list_event() {
  		    $("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					event_id: $("input#event_id").val() 
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
		  		    $("#wait").loadHide();
					alert("Error (event_calendar_calculation_event.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
		  		    $("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						set_event_info(req.data.evt);
					}
				},
				type: "post",
				url: "ajax/event_calendar_calculation_event.php"
			});
		}
		
		function set_event_info( eobj ) {
			$("span#event_title").html(eobj.title);
			$("#sch_place").val( eobj.place );
			var html = '';
			for(var key in eobj.times) {
				var sn 	= eobj.times[key].sn;
				var fhh = eobj.times[key].fhh;
				var fmm = eobj.times[key].fmm;
				var thh = eobj.times[key].thh;
				var tmm = eobj.times[key].tmm;
				html += '<span style="font-size:14px; font-weight:bold;">' + sn + '. </span>';
				html += '<input type="text" style="width:20px; text-align:center;" name="fhh" sn="' + sn + '" value="' + fhh + '">';
				html += '<span style="font-size:14px; font-weight:bold;">:</span>';
				html += '<input type="text" style="width:20px; text-align:center;" name="fmm" sn="' + sn + '" value="' + ("0"+fmm).right(2) + '">';
				html += '~';
				html += '<input type="text" style="width:20px; text-align:center;" name="thh" sn="' + sn + '" value="' + thh + '">';
				html += '<span style="font-size:14px; font-weight:bold;">:</span>';
				html += '<input type="text" style="width:20px; text-align:center;" name="tmm" sn="' + sn + '" value="' + ("0"+tmm).right(2) + '">';
				html += '<span style="margin-left:30px;"></span>';
			}
			$("div#time_range").html(html);
			get_data_ajax();
		}
		
		function get_data_ajax() {
  		    $("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					place:		$("#sch_place").val(),
					event_id: 	$("input#event_id").val(), 
					start_date: $("#sch_sdate").val(),
					end_date: 	$("#sch_edate").val(),
					times:		get_checkin() 		
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
		  		    $("#wait").loadHide();
					alert("Error (event_calendar_calculation_punch.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
		  		    $("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
					  $("#holder_list").empty().html( all_html(req.data.total_head, req.data.holder) );
					  $("#matched_list").empty().html( all_html(req.data.matched_head, req.data.matched) );
					  $("#idreader_list").empty().html( all_html(req.data.idreader_head, req.data.idreader) );
					  
					}
				},
				type: "post",
				url: "ajax/event_calendar_calculation_punch.php"
			});
		}

		function calculate_ajax() {
  		    var confirm = window.confirm(words["attend calculate info"].br2nl() + ":" + $("#sch_sdate").val() + " ~ " + $("#sch_edate").val());
			if(confirm) {
				$("#wait").loadShow();
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"view",
	
						place:		$("#sch_place").val(),
						event_id: 	$("input#event_id").val(), 
						start_date: $("#sch_sdate").val(),
						end_date: 	$("#sch_edate").val(),
						times:		get_checkin() 		
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
						$("#wait").loadHide();
						alert("Error (event_calendar_calculation_calculate.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
						$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow(); 
							//alert(req.errorMessage);
							//all_html(req.data);
						}
					},
					type: "post",
					url: "ajax/event_calendar_calculation_calculate.php"
				});
			}
		}

		function all_html(hd, ugrp) {
			var html = '';
			if(ugrp && ugrp.length > 0 ) {	 
				  //var html = '<span id="sch_result" style="font-size:12px; font-weight:bold; margin-left:5px;">. List: Total </span><span id="total_cards" style="font-size:14px; font-weight:bold; color:blue;">' + ugrp.length + '</span><br />';
				  html += '<table id="mytab_allstudent"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
				  html += '<tr rid="footer">';
				  html += '<td colspan="3" style="text-align:right; background-color:#999999;" class="tabQuery-table-header">' + words["grand total"] + ': </td>';
				  html += '<td colspan="3" style="text-align:right;background-color:#999999;" class="tabQuery-table-header">' + words["punch"] + ': </td>';
				  html += '<td style="text-align:left; background-color:#999999;" class="tabQuery-table-header"><span id="footer_punch">'  + hd.punch + '</span></td>';
				  html += '<td style="text-align:right; background-color:#999999;" class="tabQuery-table-header">' + words["student"] + ': </td>';
				  html += '<td style="text-align:left; background-color:#999999;" class="tabQuery-table-header"><span id="footer_student">'  + hd.student + '</span></td>';
				  //html += '<td style="text-align:left; background-color:#999999;" class="tabQuery-table-header"></td>';
				  html += '</tr>';

				  html += '<tr rid="title">';
				  html += '<td colspan="9" style="text-align:center; font-size:16px; font-weight:bold;" class="tabQuery-table-header">' + words["check in list"] + '</td>';
				  html += '</tr>';

				  html += '<tr rid="header">';
				  html += '<td width="15" class="tabQuery-table-header">SN</td>';
				  html += '<td class="tabQuery-table-header">' + words["time"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["name"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["gender"] + '</td>'; 
				  html += '<td class="tabQuery-table-header">' + words["email"] + '</td>'; 
				  html += '<td class="tabQuery-table-header">' + words["phone"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["city"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["g.site"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["id card"] + '</td>';
				  //html += '<td class="tabQuery-table-header"></td>';
				  html += '</tr>';
				  rowno = 0;			  
				  for(var idx in ugrp) {
					  html += add_html(ugrp[idx]);
				  }
				  html += '</table>';
				  return html;
		
			}
		}
		var rowno = 0;
		function add_html(obj) {
			  rowno++;
			  var html = '<tr rid="' + obj.id + '" mid="' + obj.member_id + '">';
			  html += '<td align="center">';
			  html += rowno;
			  html += '</td>';
			  html += '<td>';
			  html += obj.time;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.name;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.gender;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.email;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.phone;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.city;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.site;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.idd;
			  html += '</td>';
			  //html += '<td align="center">';
			  //html += '<a class="tabQuery-button tabQuery-button-delete" oper="delete" right="delete" rid="' +  obj.id + '" title="直接删除"></a>';					 
			  //html += '</td>';
			  html += '</tr>';
			  return html;
		}
		
		function get_checkin() {
			var check_arr = [];
			$("input[name='fhh'][sn]").each(function(index, element) {
				var i = parseInt($(this).attr("sn")); 
				var cobj = {};
				cobj.sn = i;
				cobj.fhh = $("input[name='fhh'][sn='" + i + "']").val();
				cobj.fmm = $("input[name='fmm'][sn='" + i + "']").val();
				cobj.thh = $("input[name='thh'][sn='" + i + "']").val();
				cobj.tmm = $("input[name='tmm'][sn='" + i + "']").val();
				check_arr[check_arr.length] = cobj;
				               
            });
			return check_arr;
		}
		
		function event_select( eid ) {
			$("input#event_id").val(eid);
		}
	
		function event_diag_close() {
			$("#diaglog_event").diagHide();
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
	<table>
    	<tr>
    		<td align="right" valign="top">
			    <span style="font-size:14px; font-weight:bold;">
                <?php echo $words["event title"]?> : 
                </span>
            </td>
            <td valign="top">
			    <span id="event_title" style="font-size:14px; font-weight:bold; color:blue;"></span><br />
            </td>
        </tr>
        <tr>
            <td align="right">
			    <span style="font-size:14px; font-weight:bold;">
            	<?php echo $words["date range"]?> : 
                </span>
            </td>
            <td>
            <b><?php echo $words["from"]?>:</b> <input style="width:80px;" id="sch_sdate" value="<?php echo date("Y-m-d")?>" /> 
            <span style="margin-left:20px;"></span><b><?php echo $words["to"]?>:</b> <input style="width:80px;" 	id="sch_edate" value="<?php echo date("Y-m-d")?>" />

  
            <span style="font-size:14px; font-weight:bold; margin-left:50px;">
            <?php echo $words["event_place"]?> : 
            </span>
            <select id="sch_place" style="min-width:100px;" name="sch_place">
                <?php
                    $result_place = $db->query("SELECT * FROM puti_places order by id");
                    while( $row_place = $db->fetch($result_place) ) {
                        echo '<option value="' . $row_place["id"] . '">' . $words[strtolower($row_place["title"])] . '</option>';
                    }
                ?>
            </select>
            </td>
        </tr>
		<tr>
    		<td align="right" valign="top">
			    <span style="font-size:14px; font-weight:bold;">
            	<?php echo $words["checkin time range"]?> :
                </span>
            </td>
            <td valign="top">
            		<div id="time_range"></div>
            </td>
        </tr>
        <tr>
            <td align="right">
            </td>
            <td>
				<input type="button" id="btn_getdata" onclick="get_data_ajax()" value="<?php echo $words["get data"]?>" />
                <input type="button" id="btn_calculate" onclick="calculate_ajax()" value="<?php echo $words["calculate attendance"]?>" />
            </td>
        </tr>
     </table>  

    <div id="group_edit" class="lwhTabber lwhTabber-fuzzy" style="width:100%;">
        <div class="lwhTabber-header">
            <a><?php echo $words["all records"]?><s></s></a>
            <a><?php echo $words["matched records"]?><s></s></a>
            <a><?php echo $words["idreader records"]?><s></s></a>
            <div class="line"></div>    
        </div>
        <div class="lwhTabber-content">
            <div id="group_item" style="min-height:370px; overflow-x:hidden; overflow-y:auto;">
				<div id="holder_list" style="padding:5px; min-height:420px;"></div>
            </div><!-- end of <div id="group_item"> -->
            <div id="matched_list" style="min-height:370px; overflow-x:hidden; overflow-y:auto;">
            </div><!-- end of <div id="group_item"> -->
            <div id="idreader_list" style="min-height:370px; overflow-x:hidden; overflow-y:auto;">
            </div><!-- end of <div id="group_item"> -->
        </div>
    </div><!-- end of <div id="group_edit"> -->
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_event" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<br />
        <b><?php echo $words["please select event"]?>: <b><br /><br />
            <select id="sch_event" onchange="event_select(this.value);" style="width:100%">
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
                $result = $db->query($query);
                $first = true;
				echo '<option value=""></option>';
                while( $row = $db->fetch($result) ) {
                    $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                    if( $first ) {
						$first = false;
						echo '<option value="' . $row["id"] . '" selected>' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
					} else {
						echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
					}
	            }
            ?>
            </select>
            <br /><br />
            <center><span id="msg_error" style="font-size:14px; font-weight:bold; color:red;"></span></center>
        	<center><input type="button" onclick="event_diag_close()" value="<?php echo $words["button close"]?>" /></center> 
	</div>
</div>
<input type="hidden" id="event_id" name="event_id" value="" />

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>
<script language="javascript" type="text/javascript">
$(function(){
	$("#diaglog_event").diagShow({
		diag_close: function() {
			if($("#sch_event").val() != "") {
				$("input#event_id").val($("#sch_event").val());
				list_event();
				$("input#sch_idd").focus();
			} else {
				$("#msg_error").html("Error: Please select the event from event list  !");
				$("#diaglog_event").diagShow();
			}
		}
	});			
			
});
</script>
</body>
</html>