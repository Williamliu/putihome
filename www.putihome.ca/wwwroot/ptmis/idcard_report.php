<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,74";
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
		<title>Bodhi Meditation Volunteer Hours Annual Report - By Department</title>
		
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
				    sites:		$("input:checkbox.puti_sites:checked").map(function(){ return $(this).val();}).get().join(",")
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
					$("#wait").loadHide();
					alert("Error (idcard_report_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
				url: "ajax/idcard_report_select.php"
			});
		}
		
		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	

						$("input[name='start_date']", "form[name='frm_list_excel']").val( $("input#start_date").val() );	
						$("input[name='end_date']", "form[name='frm_list_excel']").val( $("input#end_date").val() );	
						$("input[name='sites']", 	"form[name='frm_list_excel']").val( $("input:checkbox.puti_sites:checked").map(function(){ return $(this).val();}).get().join(",") );	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/idcard_report_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="start_date" value="' + $("input#start_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="end_date" 	value="' + $("input#end_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sites" 	value="' + $("input:checkbox.puti_sites:checked").map(function(){ return $(this).val();}).get().join(",") + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
	}
		
		function jsonToHTML( obj ) {
				var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="0" cellspacing="0">';
				html += '<tr>';
				html += '<td colspan="29" align="center"><span style="font-size:12px; font-weight:bold;">ID 刷卡 - 统计报表<br>' + obj.period + '</span></td>';
				
				html += '</tr>';
				html += '<tr>';
				html += '<td rowspan="2" class="tabQuery-table-header">序号</td>';
				html += '<td rowspan="2" class="tabQuery-table-header">' + words["site_desc"] + '</td>';
				html += '<td rowspan="2" class="tabQuery-table-header">' + words["place_desc"] + '</td>';
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
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '<td class="tabQuery-table-header">刷卡</td>';
				html += '<td class="tabQuery-table-header">人数</td>';
				html += '</tr>';
				
				// sites
				for(var idx in obj.sites) {
					var bg_css = ' style="background-color:#E8F3D5; white-space:nowrap;"';					
					var dObj = obj.sites[idx];
					html += '<tr>';
					html += '<td align="center"' + bg_css + '>';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td align="left"' + bg_css + '>';
					html += dObj.site_desc;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.place_desc;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm1;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch1;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm2;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch2;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm3;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch3;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm4;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch4;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm5;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch5;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm6;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch6;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm7;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch7;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm8;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch8;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm9;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch9;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm10;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch10;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm11;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch11;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.cm12;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.ch12;
					html += '</td>';

					html += '<td align="right"' + bg_css + '>';
					html += dObj.tcnt;
					html += '</td>';
					html += '<td align="right"' + bg_css + '>';
					html += dObj.thead;
					html += '</td>';

					html += '</tr>';
					
						   // places
						  for(var idx1 in	dObj.places) { 
								var bg_css = ' style="background-color:#eeeeee; white-space:nowrap;"'; 
								var jObj = dObj.places[idx1];
								html += '<tr>';
								html += '<td align="center">';
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.site_desc;
								html += '</td>';

								html += '<td align="right"' + bg_css + '>';
								html += jObj.place_desc;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm1;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch1;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm2;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch2;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm3;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch3;
								html += '</td>';
	  
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm4;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch4;
								html += '</td>';
	  
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm5
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch5;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm6;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch6;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm7;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch7;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm8;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch8;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm9;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch9;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm10;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch10;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm11;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch11;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.cm12;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.ch12;
								html += '</td>';
			
								html += '<td align="right"' + bg_css + '>';
								html += jObj.tcnt;
								html += '</td>';
								html += '<td align="right"' + bg_css + '>';
								html += jObj.thead;
								html += '</td>';
			
								html += '</tr>';
						  }  // end of places		
					
				} // end of sites
				
				html += '<tr>';

				html += '<td colspan="3" align="right"><b>';
				html += '汇总:';
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm1;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch1;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm2;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch2;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm3;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch3;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm4;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch4;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm5;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch5;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm6;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch6;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm7;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch7;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm8;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch8;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm9;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch9;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm10;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch10;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm11;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch11;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.cm12;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.ch12;
				html += '</b></td>';

				html += '<td align="right"><b>';
				html += obj.grand.tcnt;
				html += '</b></td>';
				html += '<td align="right"><b>';
				html += obj.grand.thead;
				html += '</b></td>';


				html += '</tr>';

				html += '</table>';
				$("#calendar_report").html(html);
		}
		
		function selectALL() {
			$("input:checkbox.puti_sites").attr("checked", true);
		}
		function unselALL() {
			$("input:checkbox.puti_sites").attr("checked", false);
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
			<div style="padding:5px;">	    
        	<table border="0" cellpadding="0">
            	<tr>	
                	<td valign="top">
                          <table cellpadding="2" cellspacing="2">
                              <tr>
                                  <td align="right" style="white-space:nowrap;"><?php echo $words["date range"]?>: </td>
                                  <td style="white-space:nowrap;">
                                      <?php echo $words["from"]?> <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d", mktime(0,0,0,1,1,date("Y")));?>" /> 
                                      <?php echo $words["to"]?> <input style="width:80px;" id="end_date" value="" />
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
                	<td valign="top" style="padding-left:10px;">
                            <!------------------------------------------------------------------>
                            <?php echo $words["sites"]?>: <input type="button" right="view" onclick="selectALL()" value="<?php echo $words["select all"]?>" /> <input type="button" right="view" onclick="unselALL()" value="<?php echo $words["unselect all"]?>" /> 
                            <br />
                            <div style="border:1px solid #cccccc; padding:5px; width:100%;">
                                <?php
				       				$depart = "(-1)";
									if($admin_user["site"] != "") $depart = "(" . $admin_user["sites"] . ")";
									$result = $db->query("SELECT id, title FROM puti_sites WHERE id IN ". $admin_user["sites"] ." ORDER BY id");
	                                //$result = $db->query("SELECT id, title FROM puti_department WHERE  deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
                                    $col_cnt = 3;
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
                                        $html .= '<input type="checkbox" id="site_' . $row["id"] . '" ' . ( $row["id"]==$admin_user["site"]?'checked="checked"':'' ) . ' class="puti_sites" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  $words[strtolower($row["title"])] . '</label>';
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
                            <!------------------------------------------------------------------>
                    </td>
				</tr>
             </table>  
	    	</div>
    </fieldset>
	<div id="calendar_report" style="float:left; position:absolute; left:5px; padding-bottom:40px;"></div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>