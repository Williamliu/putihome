<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,90";
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
		<title>Bodhi Meditation Volunteer Hours Annual Report - By Volunteer</title>
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

					start_date: $("#start_date").val(),
					end_date: 	$("#end_date").val(),
					sch_name:	$("input#sch_name").val(),
					sch_phone:	$("input#sch_phone").val(),
					sch_email:	$("input#sch_email").val(),
					sch_gender: $("select#sch_gender").val(),
					sch_status:	$("select#sch_status").val(),
					sch_city:	$("input#sch_city").val(),
					type:		$("input:radio[name='report_type']:checked").val()
				    //depart:		$("input:checkbox.department:checked").map(function(){ return $(this).val();}).get().join(",")
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
					$("#wait").loadHide();
					alert("Error (puti_volunteer_volyear_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
				url: "ajax/puti_volunteer_volyear_select.php"
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
						$("input[name='type']", "form[name='frm_list_excel']").val( $("input:radio[name='report_type']:checked").val() );	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_volunteer_volyear_output.php", "target": "ifm_list_excel" }); 
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
						$("form[name='frm_list_excel']").append('<input type="hidden" name="type" 		value="' + $("input:radio[name='report_type']:checked").val() + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
	}
		
		function jsonToHTML( obj ) {
				var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="0" cellspacing="0">';
				html += '<tr>';
				html += '<td colspan="30" align="center"><span style="font-size:12px; font-weight:bold;">义工工时报表按义工统计<br>' + obj.period + '</span></td>';
				
				html += '</tr>';
				html += '<tr>';
				html += '<td rowspan="2" class="tabQuery-table-header">序号</td>';
				html += '<td rowspan="2" class="tabQuery-table-header">中文名</td>';
				html += '<td rowspan="2" class="tabQuery-table-header">英文名</td>';
				html += '<td rowspan="2" class="tabQuery-table-header">法名</td>';
				html += '<td colspan="2" class="tabQuery-table-header">一月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">二月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">三月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">四月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">五月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">六月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">七月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">八月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">九月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">十月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">十一月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">十二月</td>';
				html += '<td colspan="2" class="tabQuery-table-header">总计</td>';
				html += '</tr>';

				html += '<tr>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '<td class="tabQuery-table-header">' + (obj.type=="Time"?"次数":"部门") + '</td>';
				html += '<td class="tabQuery-table-header">时数</td>';
				html += '</tr>';
				
				for(var idx in obj.list) {
					var dObj = obj.list[idx];
					html += '<tr>';
					html += '<td align="center">';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td align="left">';
					html += dObj.cname;
					html += '</td>';

					html += '<td align="left">';
					html += dObj.en_name;
					html += '</td>';

					html += '<td align="left">';
					html += dObj.dharma_name;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm1:dObj.ch1;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm1;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm2:dObj.ch2;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm2;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm3:dObj.ch3;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm3;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm4:dObj.ch4;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm4;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm5:dObj.ch5;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm5;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm6:dObj.ch6;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm6;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm7:dObj.ch7;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm7;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm8:dObj.ch8;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm8;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm9:dObj.ch9;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm9;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm10:dObj.ch10;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm10;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm11:dObj.ch11;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm11;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.cm12:dObj.ch12;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.hm12;
					html += '</td>';

					html += '<td align="right">';
					html += obj.type=="Time"?dObj.tcnt:dObj.thead;
					html += '</td>';
					html += '<td align="right">';
					html += dObj.thour;
					html += '</td>';

					html += '</tr>';
				}
				
				html += '<tr>';

				html += '<td colspan="4" align="right"><b>';
				html += '汇总:';
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm1:obj.grand.ch1;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm1;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm2:obj.grand.ch2;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm2;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm3:obj.grand.ch3;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm3;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm4:obj.grand.ch4;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm4;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm5:obj.grand.ch5;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm5;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm6:obj.grand.ch6;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm6;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm7:obj.grand.ch7;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm7;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm8:obj.grand.ch8;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm8;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm9:obj.grand.ch9;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm9;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm10:obj.grand.ch10;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm10;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm11:obj.grand.ch11;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm11;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.cm12:obj.grand.ch12;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.hm12;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.type=="Time"?obj.grand.tcnt:obj.grand.thead;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.thour;
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
                                          <?php echo $words["to"]?> <input style="width:80px;" id="end_date" value="" />
                                      </td>
                                  </tr>
                                  <tr>
                                      <td align="right" style="white-space:nowrap;"><?php echo $words["report level"]?>: </td>
                                      <td>
                                          <input type="radio" id="type_time" name="report_type" value="Time"  /><label for="type_time" style="text-decoration:underline;"><?php echo $words["by times"]?></label>
                                          <input type="radio" id="type_head" name="report_type" value="Depart" checked="checked" /><label for="type_head" style="text-decoration:underline;"><?php echo $words["by deps"]?></label> 
                                      </td>
                                  </tr>
                                  <tr>
                                      <td align="right"></td>
                                      <td><br />
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
                                                <td align="right"><?php echo $words["name"]?>: </td>
                                                <td><input class="form-input" id="sch_name" value="" /></td>
                                            </tr>
                                            <tr>
                                                <td align="right"><?php echo $words["cell"]?>: </td>
                                                <td><input class="form-input" id="sch_phone" value="" /></td>
                                            </tr>
                                            <tr>
                                                <td align="right"><?php echo $words["email"]?>: </td>
                                                <td><input class="form-input" id="sch_email" value="" /></td>
                                            </tr>
                                        </table>
                                  </td>
                                  <td valign="top">
                                        <table cellpadding="2" cellspacing="0">
                                            <tr>
                                                <td align="right"><?php echo $words["gender"]?>: </td>
                                                <td>
                                                    <select id="sch_gender" style="width:100px;">
                                                        <option value=""></option>
                                                        <option value="Male"><?php echo $words["male"]?></option>
                                                        <option value="Female"><?php echo $words["female"]?></option>
                                                    </select>    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right"><?php echo $words["status"]?>: </td>
                                                <td>
                                                    <select id="sch_status" style="width:100px;">
                                                        <option value=""></option>
                                                        <option value="0"><?php echo $words["inactive"]?></option>
                                                        <option value="1"><?php echo $words["active"]?></option>
                                                    </select>    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right"><?php echo $words["city"]?>: </td>
                                                <td>
                                                  <input class="form-input" id="sch_city" style="width:100px;" value="" />                                  
                                                </td>
                                            </tr>
                                        </table>
                                  </td>
                              </tr>
                              </table>
                        </td>
                    </tr>
                </table>

	    	</div>
    </fieldset>
	<div id="calendar_report" style="position:absolute; float:left; left:5px; padding-bottom:40px;"></div>
    <br /><br /><br /><br />
    <br /><br /><br /><br />
<?php 
include("admin_footer_html.php");
?>
</body>
</html>