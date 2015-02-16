<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,50";
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
		<title>Bodhi Meditation Course Summary Report</title>

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
					sites:		$("input:checkbox.sites:checked").map(function(){ return $(this).val();}).get().join(","),
					branchs:	$("input:checkbox.branchs:checked").map(function(){ return $(this).val();}).get().join(",")	
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();
					alert("Error (event_calendar_summary_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
				url: "ajax/event_calendar_summary_select.php"
			});
		}
		
		function print_event() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	

						$("input[name='start_date']", "form[name='frm_list_excel']").val( $("input#start_date").val() );	
						$("input[name='end_date']", "form[name='frm_list_excel']").val( $("input#end_date").val() );	
						$("input[name='sites']", "form[name='frm_list_excel']").val( $("input:checkbox.sites:checked").map(function(){ return $(this).val();}).get().join(",") );	
						$("input[name='branchs']", "form[name='frm_list_excel']").val( $("input:checkbox.branchs:checked").map(function(){ return $(this).val();}).get().join(",") );	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/event_calendar_summary_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="start_date" value="' + $("input#start_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="end_date" value="' + $("input#end_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sites" value="' + $("input:checkbox.sites:checked").map(function(){ return $(this).val();}).get().join(",") + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="branchs" value="' + $("input:checkbox.branchs:checked").map(function(){ return $(this).val();}).get().join(",") + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
	}
		
		function jsonToHTML( data ) {
				var evtObj 	= data.evt;
				var grand 	= data.grand;
				var sss = [];
				sss[0] = words["inactive"];
				sss[1] = words["active"];
				sss[2] = words["open"];
				sss[9] = words["closed"];
				
				var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
				html += '<tr>';
				html += '<td colspan="20" align="center"><span style="font-size:12px; font-weight:bold;">Event Summary Report</span></td>';
				html += '</tr>';
				html += '<tr>';
				html += '<td width="20" class="tabQuery-table-header" rowspan="2">' + words["sn"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["event title"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["start date"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["end date"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["status"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["enroll"] + '</td>';
				//html += '<td class="tabQuery-table-header" colspan="3">' + words["unauth"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["trial"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["new people"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["graduate"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["certification"] + '</td>';
				html += '</tr>';

				html += '<tr>';
				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';

                /*
				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';
                */

				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';

				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';

				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';

				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';

				html += '</tr>';
				
				for(var idx in evtObj) {
					html += '<tr>';
		
					html += '<td width="20" align="center">';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td style="white-space:nowrap;">';
					html +=  evtObj[idx].title; //+ '{<span style="color:blue;">' + evtObj[idx].date_range +'</span>}';
					html += '</td>';
					
					html += '<td align="center">';
					html +=  evtObj[idx].start_date;
					html += '</td>';
					
					html += '<td align="center">';
					html +=  evtObj[idx].end_date;
					html += '</td>';

					html += '<td style="white-space:nowrap;">';
					html +=  sss[evtObj[idx].status];
					html += '</td>';

					html += '<td align="right">';
					html +=  evtObj[idx].menro;
					html += '</td>';

					html += '<td align="right">';
					html +=  evtObj[idx].fenro;
					html += '</td>';

					html += '<td align="right">';
					html +=  evtObj[idx].total;
					html += '</td>';

                    /*
					html += '<td align="right">';
					html +=  evtObj[idx].munauth;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].funauth;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].tunauth;
					html += '</td>';
                    */

					html += '<td align="right">';
					html +=  evtObj[idx].mtrial;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].ftrial;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].ttrial;
					html += '</td>';

					html += '<td align="right">';
					html +=  evtObj[idx].mnew;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].fnew;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].tnew;
					html += '</td>';

					html += '<td align="right">';
					html +=  evtObj[idx].mgrad;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].fgrad;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].tgrad;
					html += '</td>';

					html += '<td align="right">';
					html +=  evtObj[idx].mcert;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].fcert;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].tcert;
					html += '</td>';

					html += '</tr>';
				}

				html += '<tr>';
	
				html += '<td colspan="5" align="right"><b>';
				html += words["grand total"];
				html += '<b></td>';

				html += '<td align="right">';
				html +=  grand.menro;
				html += '</td>';

				html += '<td align="right">';
				html +=  grand.fenro;
				html += '</td>';

				html += '<td align="right">';
				html +=  grand.total;
				html += '</td>';

				html += '<td align="right">';
				html +=  grand.mtrial;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.ftrial;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.ttrial;
				html += '</td>';

				html += '<td align="right">';
				html +=  grand.mnew;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.fnew;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.tnew;
				html += '</td>';

				html += '<td align="right">';
				html +=  grand.mgrad;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.fgrad;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.tgrad;
				html += '</td>';

				html += '<td align="right">';
				html +=  grand.mcert;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.fcert;
				html += '</td>';
				html += '<td align="right">';
				html +=  grand.tcert;
				html += '</td>';

				html += '</tr>';

				
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
	<table>
          <tr>  
              <td style="width:60px; white-space:nowrap;" align="right" valign="top"><?php echo $words["r.sites"]?>: </td>
               <td valign="top">
                      <!------------------------------------------------------------------>
                      <div style="border:1px solid #cccccc; padding:5px; width:100%;">
                          <?php
                              if( $admin_user["group_level"] < 9 ) {
                                  $result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 AND id in " . $admin_user["sites"] . " ORDER BY id;");
                              } else {
                                  $result = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 ORDER BY id;");
                              }
                              $col_cnt = 4;
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
                                  $html .= '<input type="checkbox" ' . ($admin_user["site"]==$row["id"]?"checked":"") . ' id="sites_' . $row["id"] . '" class="sites" value="' . $row["id"] . '"><label for="sites_' . $row["id"] . '">' . $cno . '. ' .  $words[strtolower($row["title"])] . '</label>';
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
					  <br /><br />
                      <span style="font-size:14px; font-weight:bold; margin-left:10px;"><?php echo $words["date range"]?>: </span>
                      From <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d", mktime(0,0,0,1,1,date("Y")));?>" /> 
                      TO <input style="width:80px;" id="end_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("t"),date("Y")));?>" />
                      <input type="button" id="btn_search" right="view" onclick="list_event()" value="<?php echo $words["g.report"]?>" /> 
                      <input type="button" id="btn_print" right="print" onclick="print_event()"  value="<?php echo $words["output excel"]?>" /> 



              </td>
              <td style="width:60px; white-space:nowrap; padding-left:20px;" align="right" valign="top"><?php echo $words["rr.groups"]?>: </td>
               <td valign="top">
                      <!------------------------------------------------------------------>
                      <div style="border:1px solid #cccccc; padding:5px; width:100%;">
                          <?php
                              if( $admin_user["group_level"] < 9 ) {
                                  $result = $db->query("SELECT id, title FROM puti_branchs a 
                                                                         INNER JOIN puti_sites_branchs b ON (a.id = b.branch_id) 
                                                                         INNER JOIN puti_sites c ON ( b.site_id = c.id )  
                                                             WHERE  c.status = 1 AND c.id in " . $admin_user["sites"] . " AND a.id > 0 AND  a.id in " . $admin_user["branchs"] . " ORDER BY a.sn;");
                              } else {
                                  $result = $db->query("SELECT id, title FROM puti_branchs WHERE id > 0 ORDER BY id;");
                              }
                              $col_cnt = 1;
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
                                  $html .= '<input type="checkbox" ' . ($admin_user["branch"]==$row["id"]?"checked":"") . ' id="branchs_' . $row["id"] . '" class="branchs" value="' . $row["id"] . '"><label for="branchs_' . $row["id"] . '">' . $cno . '. ' .  $words[strtolower($row["title"])] . '</label>';
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
	<br /> 
 	<div id="calendar_report" style="padding:5px; min-height:420px;">
    </div>

<?php 
include("admin_footer_html.php");
?>

</body>
</html>