<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,20";
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
		<title>Bodhi Meditation Class - Add</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
		
        <script language="javascript" type="text/javascript">
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
			  ////////////////////////////////////////////////////////////////////
			  $("#cal_date_add").unbind("click.calendar").bind("click.calendar", function(ev) {
					var day_len = parseInt( $("#date_length").val() );
					for(var i=1; i <= day_len; i++) { 
						date_html(i);
					}
			  });
		  
			  $("#cal_date_clear").unbind("click.calendar").bind("click.calendar", function(ev) {
				  $("#cal_event_list").empty();
			  });
			  
			  $("input.date-btn-clear").die("click.calendar").live("click.calendar", function(ev) {
				  $("li.date-area[dd='" + $(this).attr("dd") + "']").remove();
			  });
			  
			  $("input.cal-time-btn-add").die("click.calendar").live("click.calendar", function(ev) {
				  var stime='', etime='';
				  var shh = $(".cal-time-start-hh[dd='" + $(this).attr("dd") + "']").val();
				  var smi = $(".cal-time-start-mm[dd='" + $(this).attr("dd") + "']").val();
				  var ehh = $(".cal-time-end-hh[dd='" + $(this).attr("dd") + "']").val();
				  var emi = $(".cal-time-end-mm[dd='" + $(this).attr("dd") + "']").val();
				  if( shh == "" && ehh == "" ) {
					  return;
				  } else if(shh == "") {
					  stime = ehh + ":" + (emi==""?"00":emi);
				  } else if(ehh == "" ) {
					  stime = shh + ":" + (smi==""?"00":smi);
				  } else {
					  stime = shh + ":" + (smi==""?"00":smi);
					  etime = ehh + ":" + (emi==""?"00":emi);
				  }
				  time_html($(this).attr("dd"), stime, etime);
				  //$("li.date-area[yy='" + $(this).attr("yy") + "'][mm='" + $(this).attr("mm") + "'][dd='" + $(this).attr("dd") + "']").remove();
			  });
			  
			  ////////////////////////////////////////////////////////////////////
		});
		
		function save_event() {
		  	$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: 	$("input#adminSession").val(),
					admin_menu:		$("input#adminMenu").val(),
					admin_oper:		"save",
					
					cls:			toJSON(),
					carr:			get_checkin()	
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();
					alert("Error (class_add_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
					  $("#agreement").val("");
					  $("#date_length").val("");
					  $("#checkin").val("");
					  $("#photo").attr("checked",false);
					  $("#payfree").attr("checked",false);
					  $("#payonce").attr("checked",false);
					  $("#cert").attr("checked",false);
					  $("input:checkbox[name='meal']").attr("checked",false);

					  tool_tips(words["save success"]);	
					  //$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
					  //$("#diaglog_message").diagShow({title:words["submit success"]}); 
					}
				},
				type: "post",
				url: "ajax/class_add_save.php"
			});
		}
		
		function date_html(dd) {
			if( $("li[dd='" + dd  + "']", "#cal_event_list").length <= 0 ) {
				var html = '';
				html += '<li class="date-area" dd="' + dd + '" style="margin-left:20px; width:500px;">';
				html += '<table border="0">';
				html += '<tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html += words["day"] + ' <b>' + dd + ' ' + words["day1"] +  '</b>';
				html += '</td>';
				html += '<td align="left" valign="top"  style="padding:0px 5px 0px 5px; font-size:12px;  text-transform:none;">';
				
				html += words["from"] + ': ';
				html += hour_html(dd, 'cal-start-time', hour_val("main", "main-start-time")); 
				html += ' ' + words["to"] + ': ';
				html += hour_html(dd, 'cal-end-time', hour_val("main", "main-end-time")); 
				html += '<input type="button" right="save" class="date-btn-clear" dd="' + dd + '" style="float:right;" value="' + words["button delete"] + '" />';

				html += '</td>';
				html += '</tr><tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html +=  words["subject"] + ': ';
				html += '</td>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:500px;">';
				html += '<input class="date-title" dd="' + dd + '" type="text" value="" />';
				html += '</td>';
				html += '</tr><tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html += words["description"] +': ';
				html += '</td>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px;">';
				html += '<textarea class="date-desc" dd="' + dd + '"></textarea>';
				html += '</td>';
				html += '</tr><tr>';
				html += '<td></td>';
				html += '<td style="padding:0px 5px 0px 5px; width:60px;">';

				html += '<b>' + words["checkin times"] + ':</b> <select class="date-checkin" dd="' + dd + '">';
				html += '<option value=""></option>';
				html += '<option value="1">1</option>';
				html += '<option value="2">2</option>';
				html += '<option value="3">3</option>';
				html += '</select>';
				html += '<span style="margin-left:20px; font-weight:bold;">' + words["meal"] + ':</span> <input type="checkbox" name="date_meal" dd="' + dd + '" value="Breakfast" />' + words["breakfast"];
				html += '<input type="checkbox" name="date_meal" dd="' + dd + '" style="margin-left:5px;" value="Lunch" />' + words["lunch"];
				html += '<input type="checkbox" name="date_meal" dd="' + dd + '" style="margin-left:5px;" value="Dinner" />' + words["dinner"];;
				html += '</td>';
				html += '</tr>';
				html += '</table>';
				html += '</li>';

				$("#cal_event_list").append(html);
				$("input.date-title[dd='" + dd + "']").val($("#cal_event_subject").val());
				$("textarea.date-desc[dd='" + dd + "']").val($("#cal_event_desc").val());
				$(".date-checkin[dd='" + dd + "']").val( $("#checkin").val() );
				$("input:checkbox[name='meal']:checked").each(function(idx, el) {
                    $("input:checkbox[name='date_meal'][dd='" + dd + "'][value='" + $(el).val() + "']").attr("checked", true);
                });
			}
		}
		
		
		
		function hour_html(dd, cid, val) {
			var html = '';
			var hm = []; 
			hm[0] = '';
			hm[1] = '';
			if(val) hm = val.split(":");

			html += '<select dd="' + dd + '" hm="hour" class="cal-time ' + cid + '">';
			html += '<option value=""></option>';
			for(var i=5; i<=23; i++) {
				if( i == parseInt(hm[0]) ) 
					html += '<option value="' + i + '" selected>' + i + '</option>';
				else 
					html += '<option value="' + i + '">' + i + '</option>';
			}
			html += '</select>';
			html += '<b> : </b>';
			html += '<select dd="' + dd + '" hm="min" class="cal-time ' + cid + '">';
			html += '<option value=""></option>';
			html += '<option value="00" ' + (hm[1]=="00"?'selected':'')+ '>00</option>';
			html += '<option value="15" ' + (hm[1]=="15"?'selected':'')+ '>15</option>';
			html += '<option value="30" ' + (hm[1]=="30"?'selected':'')+ '>30</option>';
			html += '<option value="45" ' + (hm[1]=="45"?'selected':'')+ '>45</option>';
			html += '</select>';
			return html;
		}
		
		function hour_val(dd, cid) {
			var thh = $("select." + cid + "[dd='" + dd + "'][hm='hour']").val();
			var tmm = $("select." + cid + "[dd='" + dd + "'][hm='min']").val();
			var hour =  thh + ":" + (tmm!=""?tmm:"00"); 
			var regExp = /\d+:\d{2}/gi;
			return regExp.test(hour)?hour:''; 	
		}
		
		function toJSON() {
			var clsObj = {};
			clsObj.title 		= $("input#cal_event_subject").val();
			clsObj.description 	= $("textarea#cal_event_desc").val();
			clsObj.agreement  	= $("select#agreement").val();
			clsObj.date_length 	= $("select#date_length").val();
			clsObj.checkin 		= $("select#checkin").val();
			clsObj.attend 		= $("input#attend").val();
			clsObj.cert			= $("input:checkbox#cert").is(":checked")?1:0;
			clsObj.cert_prefix	= $("input#cert_prefix").val();
			clsObj.photo		= $("input:checkbox#photo").is(":checked")?1:0;
			clsObj.payfree		= $("input:checkbox#payfree").is(":checked")?1:0;
			clsObj.payonce		= $("input:checkbox#payonce").is(":checked")?1:0;
			
			clsObj.meal			= $("input:checkbox[name='meal']:checked").map( function(idx, el) {
								  		return $(this).val();
								  }).get().join(",");
			clsObj.logform		= $("select#logform").val();
			clsObj.dates 		= [];

			$("li.date-area[dd]").each(function(idx1, el1) {
				var dd = $(this).attr("dd");
				var dateObj = {};
				dateObj.day_no = dd;
				dateObj.start_time 	= hour_val(dd,	"cal-start-time"); 
				dateObj.end_time 	= hour_val(dd,	"cal-end-time"); 
				dateObj.title 		= $("input.date-title[dd='" + dd + "']").val();
				dateObj.description = $("textarea.date-desc[dd='" + dd + "']").val();
				dateObj.checkin		= $("select.date-checkin[dd='" + dd + "']").val();
				dateObj.meal 		= $("input:checkbox[name='date_meal'][dd='" + dd + "']:checked").map(function(idx, el) {
                    						return $(this).val();
                						}).get().join(",");
				
				clsObj.dates[clsObj.dates.length] = dateObj;
			});
			return clsObj;
		}
		
		function get_checkin() {
			var ccnt = parseInt($("select#checkin").val());
			var check_arr = [];
			if( ccnt > 0 ) {
				for(var i = 1; i <= ccnt; i++) {
					var cobj = {};
					cobj.sn = i;
					cobj.fhh = $("input[name='fhh'][sn='" + i + "']").val();
					cobj.fmm = $("input[name='fmm'][sn='" + i + "']").val();
					cobj.thh = $("input[name='thh'][sn='" + i + "']").val();
					cobj.tmm = $("input[name='tmm'][sn='" + i + "']").val();
					check_arr[i] = cobj;
				}
			}
		    //alert(showObj(check_arr));
			return check_arr;
		}
		
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
	<div id="calendar_add" style="padding:5px;">
		<table cellpadding="2" cellspacing="0" width="500">
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["subject"]?>: </td>
                <td style="white-space:nowrap; width:500px;"><input type="text" id="cal_event_subject" style="width:100%" value="" /></td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["description"]?>: </td>
                <td style="white-space:nowrap; width:500px;"><textarea id="cal_event_desc" style="width:100%; height:60px; resize:none;"></textarea></td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["agreement"]?>: </td>
                <td style="white-space:nowrap; width:500px;">
                	<select id="agreement" name="agreement">
                	<?php
						$query = "SELECT id, subject FROM puti_agreement WHERE status = 1 AND deleted <> 1 ORDER BY created_time";
						$result = $db->query($query);
						echo '<option value="0"></option>';
						while( $row = $db->fetch($result) ) {
							echo '<option value="' . $row["id"] . '">' . $row["subject"] . '</option>';
						}
					?>
                    </select>
               	</td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap;" align="right"><?php echo $words["date length"]?>: </td>
                <td valign="top">
                	<select id="date_length" name="date_length">
                    	<option value=""></option>
						<?php 
							for($i=1; $i<=20; $i++) {
								echo '<option value="' . $i . '">' . $i . '</option>';
							}
						?>
                    </select>
                   	  <span style="margin-left:10px; font-weight:bold;"><?php echo $words["time"]?>: </span> 
                      <?php echo $words["from"]?> 
					  <?php 
					  	  	echo '<select dd="main" hm="hour" class="cal-time main-start-time">';
					  		echo '<option value=""></option>';
							for($i=5; $i<=23; $i++) {
								echo '<option value="' . $i . '">' . $i . '</option>';
							}
							echo '</select>';
                      		echo '<b> : </b>';
							echo '<select dd="main" hm="min" class="cal-time main-start-time">';
							echo '<option value=""></option>';
							echo '<option value="00">00</option>';
							echo '<option value="15">15</option>';
							echo '<option value="30">30</option>';
							echo '<option value="45">45</option>';
							echo '</select>';
					  ?>
                      <?php echo $words["to"]?> 
					  <?php 
					  	  	echo '<select dd="main" hm="hour" class="cal-time main-end-time">';
					  		echo '<option value=""></option>';
							for($i=5; $i<=23; $i++) {
								echo '<option value="' . $i . '">' . $i . '</option>';
							}
							echo '</select>';
                      		echo '<b> : </b>';
							echo '<select dd="main" hm="min" class="cal-time main-end-time">';
							echo '<option value=""></option>';
							echo '<option value="00">00</option>';
							echo '<option value="15">15</option>';
							echo '<option value="30">30</option>';
							echo '<option value="45">45</option>';
							echo '</select>';
					  ?>
               </td>
            </tr>
        	<tr>
            	<td align="right"><?php echo $words["meal"]?>: </td>
                <td valign="top">
                	<input type="checkbox" id="meal_breakfast" 	name="meal" value="Breakfast" /><label for="meal_breakfast"><?php echo $words["breakfast"]?></label>
                	<input type="checkbox" id="meal_lunch" 		name="meal" value="Lunch" 	style="margin-left:10px;" /><label for="meal_lunch"><?php echo $words["lunch"]?></label>
                	<input type="checkbox" id="meal_dinner" 	name="meal" value="Dinner"  style="margin-left:10px;" /><label for="meal_dinner"><?php echo $words["dinner"]?></label>
   
                    <span style="margin-left:5px;"></span> 
                    <input type="checkbox" id="cert" name="cert" style="margin-left:10px;" value="1" />
                    <?php echo $words["certification"]?> 

                    <span style="margin-left:5px;"></span> 
                    <?php echo $words["cert_no_prefix"]?>: <input type="text" id="cert_prefix" name="cert_prefix" style="width:50px;" value="" />
               </td>
            </tr>
        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right"  valign="middle"><?php echo $words["register form way"]?>: </td>
                <td style="white-space:nowrap; width:500px;"  valign="middle">
					<select id="logform" name="logform">
                    <?php 
						$query = "SELECT id, title FROM puti_forms ORDER BY id";
						$result = $db->query($query);
                        echo '<option value=""></option>';
						while( $row = $db->fetch($result) ) {
							echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[$row["title"]]) . '</option>';
						}
					?>
                    </select>
          	    	<span style="margin-left:20px;">
                    <input type="checkbox" id="payfree" 	name="payfree" value="1" /><label for="payfree"><?php echo $words["pay free"]?></label>
					</span>

          	    	<span style="margin-left:20px;">
                    <input type="checkbox" id="payonce" 	name="payonce" value="1" /><label for="payonce"><?php echo $words["pay once"]?></label>
					</span>
                </td>
            </tr>

        	<tr>
            	<td style="white-space:nowrap; width:50px;" align="right" valign="middle"><?php echo $words["checkin times"]?>: </td>
                <td style="white-space:nowrap; width:500px;" valign="middle">
                	<select id="checkin" name="checkin">
                    	<option value=""></option>
						<?php 
							for($i=1; $i<=3; $i++) {
								echo '<option value="' . $i . '">' . $i . '</option>';
							}
						?>
                    </select>
              		<span style="margin-left:20px;"><?php echo $words["attend percent"]?>: </span>
                    <input type="text" style="text-align:center; width:20px;" id="attend" name="attend" value="70" /><span style="font-size:14px; font-weight:bold;">%</span>      
          	    	<span style="margin-left:20px;">
                    <input type="checkbox" id="photo" 	name="photo" value="1" /><label for="photo"><?php echo $words["photo checkin"]?></label>
					</span>
                </td>
            </tr>

            <tr>
                <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["check in"]?>: </td>
                <td style="white-space:nowrap; width:500px;" valign="top">
                   <span style="font-size:14px; font-weight:bold;">1. </span> 
                   <input type="text" style="width:20px; text-align:center;" name="fhh" sn="1" value="08">
                   <span style="font-size:14px; font-weight:bold;">:</span>
                   <input type="text" style="width:20px; text-align:center;" name="fmm" sn="1" value="00">
                     ~
                   <input type="text" style="width:20px; text-align:center;" name="thh" sn="1" value="12">
                   <span style="font-size:14px; font-weight:bold;">:</span>
                   <input type="text" style="width:20px; text-align:center;" name="tmm" sn="1" value="30">
                   <br />
                   <span style="font-size:14px; font-weight:bold;">2. </span> 
                   <input type="text" style="width:20px; text-align:center;" name="fhh" sn="2" value="12">
                   <span style="font-size:14px; font-weight:bold;">:</span>
                   <input type="text" style="width:20px; text-align:center;" name="fmm" sn="2" value="30">
                     ~
                   <input type="text" style="width:20px; text-align:center;" name="thh" sn="2" value="19">
                   <span style="font-size:14px; font-weight:bold;">:</span>
                   <input type="text" style="width:20px; text-align:center;" name="tmm" sn="2" value="30">
                   <br />
                   <span style="font-size:14px; font-weight:bold;">3. </span> 
                   <input type="text" style="width:20px; text-align:center;" name="fhh" sn="3" value="19">
                   <span style="font-size:14px; font-weight:bold;">:</span>
                   <input type="text" style="width:20px; text-align:center;" name="fmm" sn="3" value="30">
                     ~
                   <input type="text" style="width:20px; text-align:center;" name="thh" sn="3" value="22">
                   <span style="font-size:14px; font-weight:bold;">:</span>
                   <input type="text" style="width:20px; text-align:center;" name="tmm" sn="3" value="30">
                </td>
            </tr>

        	<tr>
            	<td style="white-space:nowrap; border-bottom:1px solid #666666; padding-bottom:5px; width:50px;" align="right"></td>
                <td style="white-space:nowrap; border-bottom:1px solid #666666; padding-bottom:5px; width:500px;" valign="top">
                    <input type="button" right="save" id="cal_date_add" value="<?php echo $words["button add"]?>" /> 
                    <input type="button" right="save" id="cal_date_clear" value="<?php echo $words["button clear"]?>" /><br />
               </td>
            </tr>
        </table>
        <div id="cal_event_list"></div>
        <br />
		<center><input type="button" id="cal_date_save" right="save" onclick="save_event()" value="<?php echo $words["button save"]?>" /></center>
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