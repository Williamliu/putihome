<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,27";
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
		<title>Bodhi Meditation Attendance Adjust</title>

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


		function toHTML(req) {
			var headers = req.others;
			
			var css_one = 'style="background-color:#F2E8F9;"';
			var css_two = 'style="background-color:#E3F0FD;"';
			var css_cnt = 0;

			var tmp_html = '<tr>';
			var hcnt = 0;
			for(var idx1 in headers) {
				var css = (css_cnt++ % 2)==0?css_one:css_two;
				for(var i=1; i<=headers[idx1].checkin; i++) {
					hcnt++;
					tmp_html += '<td class="tabQuery-table-header" ' + css + ' width="20">' + i + '</td>';
				}
			}
			tmp_html += '</tr>';

				
			var html = '<table id="tab_group" class="tabQuery-table" border="1" cellpadding="1" cellspacing="0">';
			html += '<tr>';
			html += '<td colspan="' + (10 + hcnt) + '" align="center"><span style="font-size:12px; font-weight:bold;">' + words["event attendance"] + '</span></td>';
			html += '</tr>';
		
			html += '<tr>';
			html += '<td rowspan="2" width="20" class="tabQuery-table-header">' + words["sn"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["group"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["enroll"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["web"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["trial"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["a.sign"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["new people"] + '</td>';

			css_cnt = 0;
			for(var idx1 in headers) {
				var css = (css_cnt++ % 2)==0?css_one:css_two;
				html += '<td class="tabQuery-table-header" '+ css +' colspan="' + headers[idx1].checkin + '">' + headers[idx1].event_md + '<br>' + words["day"] + ' ' + headers[idx1].day_no + ' ' + words["day1"] + '</td>';
			}
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["attd."] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["grad."] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["cert."] + '</td>';
			html += '</tr>';
			html += tmp_html;
			
			
			// body  
			var rows = req.rows;
			for(var idx in rows) {
				var row = rows[idx];
				html += '<tr>';
	
				html += '<td width="20" align="center">';
				html += parseInt(idx) + 1;
				html += '</td>';

				html += '<td align="center" style="white-space:nowrap;"><b>';
				html +=  row.group_no!=""?words["none.di"] + ' ' + row.group_no + ' ' + words["none.zu"]:'&nbsp;'; 
				html += '</b></td>';

				html += '<td align="right">';
				html +=  row.enroll; 
				html += '</td>';

				html += '<td align="right">';
				html +=  row.online; 
				html += '</td>';

				html += '<td align="right">';
				html +=  row.trial; 
				html += '</td>';

				html += '<td align="right">';
				html +=  row.signin; 
				html += '</td>';

				html += '<td align="right">';
				html +=  row.new_flag; 
				html += '</td>';

				css_cnt = 0;
				for(var idx1 in headers) {
					css = (css_cnt++ % 2)==0?css_one:css_two;
					for(var i=1; i<=headers[idx1].checkin; i++) {
						html += '<td '+ css +' align="right">&nbsp;';
						//html +=  row.dates[idx1]["sn"][i].people; 
						
						if(row.dates) 
							if(row.dates[idx1]) 
								if(row.dates[idx1]["sn"][i])
									if(row.dates[idx1]["sn"][i].people)
										html +=  row.dates[idx1]["sn"][i].people; 
						
						html += '</td>';
					}
				}

				html += '<td align="right">';
				html +=  row.attend; 
				html += '</td>';

				html += '<td align="right">';
				html +=  row.graduate; 
				html += '</td>';

				html += '<td align="right">';
				html +=  row.cert; 
				html += '</td>';

				html += '</tr>';
			}
			// end of body

			// grand total
			var row = req.grand;
			html += '<tr>';

			html += '<td colspan="2" align="right" style="white-space:nowrap;"><b>';
			html +=  words["grand total"]; 
			html += '</b></td>';

			html += '<td align="right">';
			html +=  row.enroll; 
			html += '</td>';

			html += '<td align="right">';
			html +=  row.online; 
			html += '</td>';

			html += '<td align="right">';
			html +=  row.trial; 
			html += '</td>';

			html += '<td align="right">';
			html +=  row.signin; 
			html += '</td>';

			html += '<td align="right">';
			html +=  row.new_flag; 
			html += '</td>';

			css_cnt = 0;
			for(var idx1 in headers) {
				css = (css_cnt++ % 2)==0?css_one:css_two;
				for(var i=1; i<=headers[idx1].checkin; i++) {
					html += '<td '+ css +' align="right">&nbsp;';
					//html +=  row.dates[idx1]["sn"][i].people; 
					
					if(row.dates) 
						if(row.dates[idx1]) 
							if(row.dates[idx1]["sn"][i])
								if(row.dates[idx1]["sn"][i].people)
									html +=  row.dates[idx1]["sn"][i].people; 
					
					html += '</td>';
				}
			}

			html += '<td align="right">';
			html +=  row.attend; 
			html += '</td>';

			html += '<td align="right">';
			html +=  row.graduate; 
			html += '</td>';

			html += '<td align="right">';
			html +=  row.cert; 
			html += '</td>';

			html += '</tr>';
			// grand total 


			// people attend rate
			html += '<tr>';

			html += '<td colspan="2" align="right" style="white-space:nowrap;"><b>';
			html +=  words["people attend rate"]; 
			html += '</b></td>';

			html += '<td align="right">';
			html +=  row.enroll; 
			html += '</td>';

			html += '<td align="right">';
			//html +=  row.online; 
			html += '</td>';

			html += '<td align="right">';
			//html +=  row.trial; 
			html += '</td>';

            /*
			html += '<td align="right">';
			//html +=  row.unauth; 
			html += '</td>';
            */

			html += '<td align="right">';
			//html +=  row.signin; 
			html += '</td>';

			html += '<td align="right">';
			//html +=  row.signin; 
			html += '</td>';

			css_cnt = 0;
			for(var idx1 in headers) {
				css = (css_cnt++ % 2)==0?css_one:css_two;
				for(var i=1; i<=headers[idx1].checkin; i++) {
					html += '<td '+ css +' align="right">&nbsp;';
					//html +=  row.dates[idx1]["sn"][i].people; 
					
					if(row.dates) 
						if(row.dates[idx1]) 
							if(row.dates[idx1]["sn"][i])
								if(row.dates[idx1]["sn"][i].people)
									html +=  row.dates[idx1]["sn"][i].prate; 
					
					html += '</td>';
				}
			}

			html += '<td align="right">';
			//html +=  row.attend; 
			html += '</td>';

			html += '<td align="right">';
			//html +=  row.graduate; 
			html += '</td>';

			html += '<td align="right">';
			//html +=  row.cert; 
			html += '</td>';

			html += '</tr>';
			// grand total 

			
			
			// end of grand

			html += '</table>';
			return html;	
		}

		function event_select_ajax() {
  			if( $("#event_id").val() == "" ) {
				$("#event_attend_list").empty();
				return;
			} 
			
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
					alert("Error (event_group_attend_search.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
		  			$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						$("#event_attend_list").html( toHTML(req.data) );
					}
				},
				type: "post",
				url: "ajax/event_group_attend_select.php"
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
						$("form[name='frm_list_excel']").attr({"action":"ajax/event_group_attend_print.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("#event_id").val() + '" />');				  
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
    <span style="font-size:12px; font-weight:bold; margin-left:10px;"><?php echo $words["date range"]?>: </span>
    From <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("j") - 30, date("Y")));?>" /> 
    TO <input style="width:80px;" id="end_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("j") + 30, date("Y")));?>" />
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
    <table style="margin-left:10px;">
    <tr>
      <td style="padding-left:20px;" colspan="4">
          <input type="button" id="btn_search" onclick="event_select_ajax()" value="<?php echo $words["search"]?>" />
          <input type="button" id="btn_search" onclick="print_event()" value="<?php echo $words["output excel"]?>" />
      </td>
    </tr>
    </table>
 
	<div id="event_attend_list" style="padding:5px; min-height:220px;"></div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>