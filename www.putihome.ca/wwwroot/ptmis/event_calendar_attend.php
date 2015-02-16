<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,120";
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
		var allObj		= null;
		$(function(){
			  $("td.enroll-check[enroll_id]").live("dblclick", function(ev) {
			  	  var rid = $(this).attr("enroll_id");
				  $("input:checkbox.enroll[enroll_id='" + rid + "']").attr("checked",true);
			  });

			  $("td.enroll-uncheck[enroll_id]").live("dblclick", function(ev) {
			  	  var rid = $(this).attr("enroll_id");
				  $("input:checkbox.enroll[enroll_id='" + rid + "']").attr("checked",false);
			  });
		
			  $(":input[oper='search']").live("focus", function(ev) {
				  $(this).select();
			  });

			  $(":input[oper='search']").live("keydown", function(ev) {
				  if( ev.keyCode == 13 ) {
					  allObj.start();
					  $(this).select();
				  }
			  });

				$("input#sch_idd").live("keydown", function(ev) {
					if( ev.keyCode == 13 ) {
			  			allObj.start();
						$(this).select();
					}
				});

			  allObj = new LWH.cTABLE({
										  condition: 	{ 
											  event_id: 	"#event_id",
											  sch_trial:	"#sch_trial",
											  sch_unauth:	"#sch_unauth",
											  sch_sign:		"#sch_sign",
											  sch_grad:		"#sch_grad",
											  sch_cert:		"#sch_cert",
											  sch_name:		"#sch_name",
											  sch_group: 	"#sch_group",
											  sch_rate: 	"#sch_rate",
											  sch_idd:		"#sch_idd"
										  },
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:25},
											  {title: words["enroll"], 		col:"enroll_flag",	width:25},
											  {title: words["time"], 		col:"time"},
											  {title: words["grp"], 		col:"group_no"},
											  {title: words["trial"], 		col:"trial"},
											  {title: words["trial time"], 	col:"trial_date"},
											  {title: words["name"], 		col:"name"},
											  {title: words["gender"], 		col:"gender"},
											  {title: words["phone"], 		col:"phone"},
											  {title: words["city"], 		col:"city"},
											  {title: words["g.site"], 		col:"site"},
											  {title: words["g.site"], 		col:"site"},
											  {title: words["paid"], 		col:"paid"},
											  {title: words["id card"], 	col:"idd"},
											  {title: "", 					col:""}
										  ],
										  container: 	"#event_attend_list",
										  me:			"allObj",
		
										  url:		"ajax/event_calendar_attend_select.php",
										  cache:	false,
										  expire:	3600,
										  
										  admin_sess: 	$("input#adminSession").val(),
										  admin_menu:	$("input#adminMenu").val(),
										  admin_oper:	"view",

										  button:		true,
										  view:			false,
										  output:		false,
										  remove:		true,
										  headRows:		headHTML,
										  pageRows:		pageHTML,
										  pageDONE:		doneHTML										  
							  });
		
			event_select_ajax();		
		});

		function headHTML(obj, others) {
			var css_one = 'style="background-color:#F2E8F9;"';
			var css_two = 'style="background-color:#E3F0FD;"';
			var css_cnt = 0;

			var tmp_html = '<tr>';
			var hcnt = 0;
			for(var idx1 in others) {
				var css = (css_cnt++ % 2)==0?css_one:css_two;
				for(var i=1; i<=others[idx1].checkin; i++) {
					hcnt++;
					tmp_html += '<td class="tabQuery-table-header" ' + css + ' width="20">' + i + '</td>';
				}
			}
			tmp_html += '</tr>';

				
			var html = '';
			html += '<tr>';
			html += '<td colspan="' + (10 + hcnt) + '" align="center"><span style="font-size:12px; font-weight:bold;">' + words["menu_adjust"] + '</span></td>';
			html += '</tr>';
		
			html += '<tr>';
			html += '<td rowspan="2" width="20" class="tabQuery-table-header">' + words["sn"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["group"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["name"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["new people"] + '</td>';

			css_cnt = 0;
			for(var idx1 in others) {
				var css = (css_cnt++ % 2)==0?css_one:css_two;
				html += '<td class="tabQuery-table-header" '+ css +' colspan="' + others[idx1].checkin + '">' + others[idx1].event_md + '<br>' + words["day"] + ' ' + others[idx1].day_no + ' ' + words["day1"] + '</td>';
			}

			html += '<td rowspan="2" class="tabQuery-table-header">' + words["total checkin"] + '</td>';
			//html += '<td rowspan="2" class="tabQuery-table-header">' + words["unauth"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["total attend"] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["total leave"] + '</td>';

			html += '<td rowspan="2" class="tabQuery-table-header">' + words["attd."] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["grad."] + '</td>';
			html += '<td rowspan="2" class="tabQuery-table-header">' + words["cert."] + '</td>';
			html += '</tr>';

			html += tmp_html;
			return html;	
		}
		
		function pageHTML(pgData) {
			var css_one = 'style="background-color:#F2E8F9;"';
			var css_two = 'style="background-color:#E3F0FD;"';
			var css_cnt = 0;

			var html = '';
			for(var idx in pgData.rows) {
				var eObj = pgData.rows[idx];
				html += '<tr>';
	
				html += '<td width="20" align="center" class="enroll-uncheck" enroll_id="' + eObj.enroll_id + '">';
				html += parseInt(idx) + 1;
				html += '</td>';

				html += '<td align="center" class="enroll-check" enroll_id="' + eObj.enroll_id + '"><b>';
				html +=  eObj.group_no>0?eObj.group_no:''; 
				html += '</b></td>';

				html += '<td style="white-space:nowrap;">';
				html +=  eObj.name; 
				html += '</td>';

				html += '<td style="white-space:nowrap;" align="center">';
				html +=  eObj.new_flag; 
				html += '</td>';


				css_cnt = 0;
				for(var idx1 in allObj.others) {
					css = (css_cnt++ % 2)==0?css_one:css_two;
					for(var i=1; i<=allObj.others[idx1].checkin; i++) {
						html += '<td class="tabQuery-table-header" ' + css + ' width="20" title="Day ' + allObj.others[idx1].day_no + '">';
						//html += '<input class="enroll day-attend" type="checkbox" event_id="' +allObj.others[idx1].event_id + '" event_date_id="' + allObj.others[idx1].event_date_id + '" enroll_id="' + eObj.enroll_id + '" value="' + i + '" />';
						html += '<span class="enroll day-attend" event_id="' +allObj.others[idx1].event_id + '" event_date_id="' + allObj.others[idx1].event_date_id + '" enroll_id="' + eObj.enroll_id + '" value="' + i + '"></span>';
						html += '</td>';
					}
				}

				html += '<td class="tabQuery-table-header">';
				html += eObj.total_checkin;
				html += '</td>';

				html += '<td class="tabQuery-table-header">';
				html += eObj.total_attend;
				html += '</td>';

				html += '<td class="tabQuery-table-header">';
				html += eObj.total_leave;
				html += '</td>';
				


				html += '<td class="tabQuery-table-header">';
				html += eObj.attd>0?(Math.round(parseFloat(eObj.attd) * 100)).toString() + '%':' ';
				html += '</td>';

				html += '<td class="tabQuery-table-header">';
				html += '<input class="enroll enroll-grad" type="checkbox" enroll_id="' + eObj.enroll_id + '" value="1" />';
				html += '</td>';

				html += '<td class="tabQuery-table-header">';
				html += '<input class="enroll enroll-cert" type="checkbox" enroll_id="' + eObj.enroll_id + '" value="1" />';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		
		function doneHTML(pgData) {
			for(var idx in pgData.rows) {
				//$("input.enroll-trial[enroll_id='" + pgData.rows[idx].enroll_id + "']").attr("checked",(pgData.rows[idx].trial=="1"?true:false) );
				//$("input.enroll-unauth[enroll_id='" + pgData.rows[idx].enroll_id + "']").attr("checked",(pgData.rows[idx].unauth=="1"?true:false) );
				//$("input.enroll-sign[enroll_id='" + pgData.rows[idx].enroll_id + "']").attr("checked",(pgData.rows[idx].signin=="1"?true:false) );
				$("input.enroll-grad[enroll_id='" + pgData.rows[idx].enroll_id + "']").attr("checked",(pgData.rows[idx].graduate=="1"?true:false) );
				$("input.enroll-cert[enroll_id='" + pgData.rows[idx].enroll_id + "']").attr("checked",(pgData.rows[idx].cert=="1"?true:false) );
				for(var idx1 in pgData.rows[idx].dates) {
					var eid = pgData.rows[idx].dates[idx1].event_id;
					var rid = pgData.rows[idx].dates[idx1].enroll_id;
					var cid = pgData.rows[idx].dates[idx1].event_date_id;
					var sn 	= pgData.rows[idx].dates[idx1].sn;
					var st  = pgData.rows[idx].dates[idx1].status;
					var status = "";
					if(st==0) status = '';
					if(st==2) status = '<span style="color:blue;">Y</span>';
					if(st==4) status = '<span style="color:red;">*</span>';
					if(st==8) status = '<span style="color:red;">M</span>';
					$("span.day-attend[event_id='" + eid + "'][enroll_id='" + rid + "'][event_date_id='" + cid + "'][value='" + sn + "']").html(status);
				}
			}
		}
		
		function event_select_ajax() {
  			if( $("#event_id").val() != "" ) {
				allObj.start();
			} else {
				$("#event_attend_list").empty();
			}
		}
		
		function toJSON() {
			var eObj = [];
			$("input.enroll-grad[enroll_id]").each(function(idx1, el1) {
                var eid = $(this).attr("enroll_id");
				var aObj = {};
				aObj.enroll_id = eid;
				//aObj.signin = $(this).is(":checked")?1:0;
				//aObj.trial = $("input.enroll-trial[enroll_id='" + eid + "']").is(":checked")?1:0;
				//aObj.unauth = $("input.enroll-unauth[enroll_id='" + eid + "']").is(":checked")?1:0;
				aObj.graduate = $("input.enroll-grad[enroll_id='" + eid + "']").is(":checked")?1:0;
				aObj.cert = $("input.enroll-cert[enroll_id='" + eid + "']").is(":checked")?1:0;
				//aObj.attend = $("input.day-attend[enroll_id='" + eid + "']:checked").map(function(){ return $(this).attr("event_date_id") + ":" + $(this).val();}).get().join(",");
            	//if( eid == 3240 || eid == 3220 )  showObj(aObj);
				//if(eObj.length == 200 || eObj.length == 201) alert(showObj(aObj)); 
				eObj[eObj.length] = aObj;
			});
			//alert( eObj.length);
			return eObj;
		}
		
		function save_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						
						  event_id: 	$("select#event_id").val(),
						  attend: 		toJSON()	
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (event_calendar_attend_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  allObj.fresh();
						  }
					  },
					  type: "post",
					  url: "ajax/event_calendar_attend_save.php"
				  });
		}


		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	

						$("input[name='event_id']", "form[name='frm_list_excel']").val( $("#event_id").val() );	
						$("input[name='sch_trial']", "form[name='frm_list_excel']").val(  $("#sch_trial").val() );	
						$("input[name='sch_unauth']", "form[name='frm_list_excel']").val(  $("#sch_unauth").val() );	
						$("input[name='sch_sign']", "form[name='frm_list_excel']").val(  $("#sch_sign").val() );	
						$("input[name='sch_grad']", "form[name='frm_list_excel']").val(  $("#sch_grad").val() );	
						$("input[name='sch_cert']", "form[name='frm_list_excel']").val(  $("#sch_cert").val() );	
						$("input[name='sch_name']", "form[name='frm_list_excel']").val(  $("input#sch_name").val() );	
						$("input[name='sch_group']", "form[name='frm_list_excel']").val( $("input#sch_group").val() );	
						$("input[name='sch_rate']", "form[name='frm_list_excel']").val( $("input#sch_rate").val() );	
						$("input[name='sch_idd']", "form[name='frm_list_excel']").val( $("input#sch_idd").val() );	
						
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/event_calendar_attend_print.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("#event_id").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_trial" value="' + $("#sch_trial").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_unauth" value="' + $("#sch_unauth").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_sign" value="' + $("#sch_sign").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_grad" value="' + $("#sch_grad").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_cert" value="' + $("#sch_cert").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + $("input#sch_name").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_group" value="' + $("input#sch_group").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_rate" value="' + $("input#sch_rate").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_idd" value="' + $("input#sch_idd").val() + '" />');				  
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
                              WHERE a.deleted <> 1 AND a.status = 2 AND
                                    b.deleted <> 1 AND b.status = 1 AND
									a.site IN " . $admin_user["sites"] . " AND
									a.branch IN " . $admin_user["branchs"] . " 
                              ORDER BY a.start_date ASC";
              $first = true;
			  $result = $db->query($query);
              echo '<option value=""></option>';
              while( $row = $db->fetch($result) ) {
                  $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                  if($first) {
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
          <td align="right"><?php echo $words["trial"]?>: </td>
          <td>
              <select oper="search" id="sch_trial">
                  <option value=""></option>
                  <option value="1"><?php echo $words["yes"]?></option>
                  <option value="0"><?php echo $words["no"]?></option>
              </select>
          </td>
          <!--
          <td align="right"><?php echo $words["unauth"]?>: </td>
          <td>
              <select oper="search" id="sch_unauth">
                  <option value=""></option>
                  <option value="1"><?php echo $words["yes"]?></option>
                  <option value="0"><?php echo $words["no"]?></option>
              </select>
          </td>
          -->
          <td align="right"><?php echo $words["sign"]?>: </td>
          <td>
              <select oper="search" id="sch_sign">
                  <option value=""></option>
                  <option value="1"><?php echo $words["yes"]?></option>
                  <option value="0"><?php echo $words["no"]?></option>
              </select>
          </td>
          <td align="right"><?php echo $words["graduate"]?>: </td>
          <td> 
               
              <select oper="search" id="sch_grad">
                  <option value=""></option>
                  <option value="1"><?php echo $words["yes"]?></option>
                  <option value="0"><?php echo $words["no"]?></option>
              </select>
          </td>    
          <td align="right"><?php echo $words["certification"]?>: </td>
          <td>
              <select oper="search" id="sch_cert">
                  <option value=""></option>
                  <option value="1"><?php echo $words["yes"]?></option>
                  <option value="0"><?php echo $words["no"]?></option>
              </select>    
          </td>
          <td style="padding-left:20px;"><?php echo $words["attd."]?>: >= <input oper="search" style="width:30px;text-align:center;" id="sch_rate" value="" /><span style="font-size:16px;font-weight:bold;">%</span></td>
  	</tr>
    <tr>
      <td><?php echo $words["name"]?>: </td>
      <td>
          <input oper="search" style="width:100px;" id="sch_name" value="" />
      </td>
      <td style="padding-left:20px;"><?php echo $words["group"]?>: </td>
      <td>
          <input oper="search" style="width:30px;text-align:center;" id="sch_group" value="" />
      </td>
      <td style="padding-left:20px;"><?php echo $words["scan id card here"]?>: </td>
      <td>
		<input style="width:100px;" id="sch_idd" value="" />
      </td>
      <td style="padding-left:20px;" colspan="4">
          <input type="button" id="btn_search" onclick="event_select_ajax()" value="<?php echo $words["search"]?>" />
          <input type="button" id="btn_search" onclick="print_event()" value="<?php echo $words["output excel"]?>" />
		  <input type="button" right="save" id="btn_save1" style="margin-left:10px;" onclick="save_ajax()" value="<?php echo $words["button save"]?>" />
      </td>
    </tr>
    </table>
 
	<div id="event_attend_list" style="padding:5px; min-height:220px;"></div>
    <center><input type="button" right="save" id="btn_save" onclick="save_ajax()" value="<?php echo $words["button save"]?>" /></center>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>