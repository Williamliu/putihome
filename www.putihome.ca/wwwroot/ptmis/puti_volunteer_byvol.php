<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,70";
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
		<title>Bodhi Meditation Volunteer Hours Report - Volunteer Department Detail</title>

		<?php include("admin_head_link.php"); ?>

   		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		$(function(){
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
					level:      $("input:radio:checked").val(),
					start_date: $("#start_date").val(),
					end_date: 	$("#end_date").val(),
					sch_name:	$("input#sch_name").val(),
					sch_phone:	$("input#sch_phone").val(),
					sch_email:	$("input#sch_email").val(),
					sch_gender: $("select#sch_gender").val(),
					sch_status:	$("select#sch_status").val(),
					sch_city:	$("input#sch_city").val()
				    //depart:		$("input:checkbox.department:checked").map(function(){ return $(this).val();}).get().join(",")
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
					$("#wait").loadHide();
					alert("Error (puti_volunteer_byvol_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
					$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						jsonToHTML(req.data);
					}
				},
				type: "post",
				url: "ajax/puti_volunteer_byvol_select.php"
			});
		}
		
		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	

						$("input[name='start_date']", "form[name='frm_list_excel']").val( $("input#start_date").val() );	
						$("input[name='end_date']", "form[name='frm_list_excel']").val( $("input#end_date").val() );	

						$("input[name='level']", "form[name='frm_list_excel']").val( $("input:radio:checked").val() );	
						$("input[name='sch_name']", "form[name='frm_list_excel']").val( $("input#sch_name").val() );	
						$("input[name='sch_phone']", "form[name='frm_list_excel']").val( $("input#sch_phone").val() );	
						$("input[name='sch_email']", "form[name='frm_list_excel']").val( $("input#sch_email").val() );	
						$("input[name='sch_gender']", "form[name='frm_list_excel']").val( $("select#sch_gender").val() );	
						$("input[name='sch_status']", "form[name='frm_list_excel']").val( $("select#sch_status").val() );	
						$("input[name='sch_city']", "form[name='frm_list_excel']").val( $("input#sch_city").val() );	
				
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_volunteer_byvol_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="start_date" value="' + $("input#start_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="end_date" value="' + $("input#end_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="level" value="' + $("input:radio:checked").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + $("input#sch_name").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + $("input#sch_phone").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + $("input#sch_email").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_gender" value="' + $("select#sch_gender").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_status" value="' + $("select#sch_status").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" value="' +  $("input#sch_city").val() + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
	}
		
		function jsonToHTML( obj ) {
				var level = parseInt($("input:radio:checked").val());
				var h1 = ' style="background-color:#FFE3AE;"';
				var c1 = ' style="background-color:#FFF5D7;"';
				var c2 = ' style="background-color:#FFD7EE;"';
				var c21 = ' style="background-color:#FFD7EE; text-align:left;"';
				var c3 = ' style="background-color:#EBFAD3;"';
				var c31 = ' style="background-color:#EBFAD3;; text-align:right;"';
				var c4 = ' style="background-color:#BFF1F8;"';

				//var c1 = ' style="background-color:#FFF5D7;"';
				//var c2 = ' style="background-color:#FFD7EE;"';
				//var c3 = ' style="background-color:#EBFAD3;"';
				//var c4 = ' style="background-color:#BFF1F8;"';
				
				var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="1" cellspacing="0">';
				html += '<tr>';
				html += '<td colspan="8" align="center"><span style="font-size:12px; font-weight:bold;">义工工时报表按义工统计<br>' + obj.period + '</span></td>';
				
				html += '</tr>';
				html += '<tr>';
				html += '<td class="tabQuery-table-header" ' + h1 + '>中文名</td>';
				html += '<td class="tabQuery-table-header" ' + h1 + '>英文名</td>';
				html += '<td class="tabQuery-table-header" ' + h1 + '>法名</td>';
				html += '<td colspan="2" class="tabQuery-table-header" ' + h1 + '></td>';
				html += '<td class="tabQuery-table-header" ' + h1 + '>次数</td>';
				html += '<td class="tabQuery-table-header" ' + h1 + '>总工时</td>';
				html += '<td class="tabQuery-table-header" ' + h1 + '>部门数</td>';
				html += '</tr>';
				if( level >= 2) {
					html += '<tr>';
					html += '<td class="tabQuery-table-header"></td>';
					html += '<td colspan="4" class="tabQuery-table-header" ' + c21 + '>服务部门</td>';
					html += '<td class="tabQuery-table-header" ' + c2 + '>次数</td>';
					html += '<td class="tabQuery-table-header" ' + c2 + '>总工时</td>';
					html += '<td class="tabQuery-table-header"></td>';
					html += '</tr>';
				}
				if( level >= 3) {
					html += '<tr>';
					html += '<td colspan="3" class="tabQuery-table-header"></td>';
					html += '<td colspan="2" class="tabQuery-table-header" ' + c31 + '>服务内容</td>';
					html += '<td class="tabQuery-table-header" ' + c3 + '>服务日期</td>';
					html += '<td class="tabQuery-table-header" ' + c3 + '>工时</td>';
					html += '<td class="tabQuery-table-header"></td>';
					html += '</tr>';
				}
				
				for(var idx in obj.list) {
					var dObj = obj.list[idx];
					html += '<tr>';
					html += '<td align="left"' + c1 + '>';
					html += parseInt(idx) + 1 + '. ' + dObj.cname;
					html += '</td>';

					html += '<td align="left"' + c1 + '>';
					html +=	dObj.en_name;
					html += '</td>';

					html += '<td align="left"' + c1 + '>';
					html +=	dObj.dharma_name;
					html += '</td>';

					html += '<td colspan="2" align="right"' + c1 + '>';
					html += 'Total:';
					html += '</td>';

					html += '<td align="right"' + c1 + '>';
					html += dObj.work_count;
					html += '</td>';

					html += '<td align="right"' + c1 + '>';
					html += dObj.total_hour;
					html += '</td>';

					html += '<td align="right"' + c1 + '>';
					html += dObj.total_head;
					html += '</td>';

					html += '</tr>';
					
					for(var idx1 in dObj.volunteer) {
						  var vObj = dObj.volunteer[idx1];
						  html += '<tr>';
						  html += '<td></td>';

						  html += '<td colspan="3" align="left"' + c2 + '>';
						  html += vObj.title;
						  html += '</td>';

						  html += '<td align="right"' + c2 + '>';
						  html += 'Total:';
						  html += '</td>';

						  html += '<td align="right"' + c2 + '>';
						  html += vObj.work_count;
						  html += '</td>';

						  html += '<td align="right"' + c2 + '>';
						  html += vObj.total_hour;
						  html += '</td>';

						  html += '<td></td>';

						  html += '</tr>';

						  for(var idx2 in vObj.detail) {
						  	var tObj = vObj.detail[idx2];

							html += '<tr>';
							html += '<td colspan="3">';
							html += '</td>';
  
							html += '<td colspan="2" align="right"' + c3 + '>';
							html += tObj.job_title;
							html += '</td>';

							html += '<td align="right"' + c3 + '>';
							html += tObj.work_date;
							html += '</td>';
  
							html += '<td align="right"' + c3 + '>';
							html += tObj.work_hour;
							html += '</td>';
						    
							html += '<td></td>';
						  }
					}
				}
				
				html += '<tr>';
				html += '<td colspan="4" align="left"' + c4 + '>';
				html += '</td>';

				html += '<td align="right"' + c4 + '><b>';
				html += 'Grand Total:';
				html += '</b></td>';

				html += '<td align="right"' + c4 + '><b>';
				html += obj.work_count;
				html += '</b></td>';

				html += '<td align="right"' + c4 + '><b>';
				html += obj.total_hour;
				html += '</b></td>';

				html += '<td align="right"' + c4 + '><b>';
				html += obj.total_head;
				html += '</b></td>';

				html += '</tr>';
				
				html += '</table>';
				$("#calendar_report").html(html);
		}
		
		function selectALL() {
			$("input:checkbox.department").attr("checked", true);
		}
		function unselALL() {
			$("input:checkbox.department").attr("checked", false);
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
			<div style="padding:0px;">	    
        	<table border="0" cellpadding="0">
            	<tr>	
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right"><?php echo $words["date range"]?>: </td>
                                  <td>
                                      <?php echo $words["from"]?> <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d", mktime(0,0,0,1,1,date("Y")));?>" /> 
                                      <?php echo $words["to"]?>  <input style="width:80px;" id="end_date" value="" />
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right" valign="top"><?php echo $words["report level"]?> : </td>
                                  <td>
                                      <input type="radio" name="report_level" id="level_department" checked="checked" value="1" /><label for="level_department"><?php echo $words["summary by vol"]?></label><br /> 
                                      <input type="radio" name="report_level" id="level_volunteer" value="2" /><label for="level_volunteer"><?php echo $words["summary by vol, dep"]?></label><br /> 
                                      <input type="radio" name="report_level" id="level_detail" value="3" /><label for="level_detail"><?php echo $words["summary by vol, dep, det"]?></label>
                                  </td>
                              </tr>
                             
                              <tr>
                                  <td align="right"></td>
                                  <td>
                                      <input type="button" id="btn_search" right="view" onclick="list_event()" value="<?php echo $words["g.report"]?>" /> 
                                      <input type="button" id="btn_print" right="print" onclick="print_event()"  value="<?php echo $words["output excel"]?>" /> 
                                  </td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top" align="left">


        	<table border="0" cellpadding="0">
            	<tr>	
                	<td valign="top">
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><?php echo $words["name"]?> : </td>
                                  <td><input class="form-input" id="sch_name" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["cell"]?> : </td>
                                  <td><input class="form-input" id="sch_phone" value="" /></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["email"]?> : </td>
                                  <td><input class="form-input" id="sch_email" value="" /></td>
                              </tr>
                          </table>
                    </td>
                	<td valign="top">
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><?php echo $words["gender"]?> : </td>
                                  <td>
                                      <select id="sch_gender" style="width:100px;">
                                          <option value=""></option>
                                          <option value="Male"><?php echo $words["male"]?> </option>
                                          <option value="Female"><?php echo $words["female"]?> </option>
                                      </select>    
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["status"]?> : </td>
                                  <td>
                                      <select id="sch_status" style="width:100px;">
                                          <option value=""></option>
                                          <option value="0"><?php echo $words["inactive"]?> </option>
                                          <option value="1"><?php echo $words["active"]?> </option>
                                      </select>    
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["city"]?> : </td>
                                  <td>
									<input class="form-input" id="sch_city" style="width:100px;" value="" />                                  
                                  </td>
                              </tr>
                          </table>
                    </td>
				</tr>
             </table>
                            <!--
                            所属部门: <input type="button" onclick="selectALL()" value="Check ALL" /> <input type="button" onclick="unselALL()" value="Uncheck ALL" /> 
                            <br />
                            <div style="border:1px solid #cccccc; padding:5px;">
                                <?php
                                    $result = $db->query("SELECT id, title FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
                                    $col_cnt = 5;
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
                                        $html .= '<input type="checkbox" id="depart_' . $row["id"] . '" checked="checked" class="department" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  $row["title"] . '</label>';
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
                            -->
                    </td>
				</tr>
             </table>  
	    	</div>
    </fieldset>
	<div id="calendar_report" style="padding:5px; min-height:420px;">
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>