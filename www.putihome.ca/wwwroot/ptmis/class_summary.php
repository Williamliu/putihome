<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,20";
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
		<title>Bodhi Meditation Class Summary Report</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var ctt = null;
		$(function(){
			  $("#sch_sdate, #sch_edate").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
			  });
		});
		
		function output_excel() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	

						$("input[name='class_id']", "form[name='frm_list_excel']").val($("#sch_class").val());	
						$("input[name='start_date']", "form[name='frm_list_excel']").val($("#sch_sdate").val());	
						$("input[name='end_date']", "form[name='frm_list_excel']").val($("#sch_edate").val());	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/class_summary_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="class_id" value="' + $("#sch_class").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="start_date" value="' + $("#sch_sdate").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="end_date" value="' + $("#sch_edate").val() + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function class_summary() {
  		    $("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					class_id: 	$("#sch_class").val(),
					start_date: $("#sch_sdate").val(),
					end_date: 	$("#sch_edate").val()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
 		  		    $("#wait").loadHide();
					alert("Error (class_summary_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
				url: "ajax/class_summary_select.php"
			});
		}

		function jsonToHTML( data ) {
				var evtObj = data.evt;
				var grand  = data.grand;
				var sss = [];
				sss[0] = "Inactive";
				sss[1] = "Active";
				sss[2] = "Open";
				sss[9] = "Closed";
				
				var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
				html += '<tr>';
				html += '<td colspan="23" align="center"><span style="font-size:12px; font-weight:bold;">' + words["event summary report"] + '</span></td>';
				html += '</tr>';
				html += '<tr>';
				html += '<td width="20" class="tabQuery-table-header" rowspan="2">' + words["sn"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["event title"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["start date"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["end date"] + '</td>';
				html += '<td class="tabQuery-table-header" rowspan="2">' + words["status"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["enroll"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["trial"] + '</td>';
				html += '<td class="tabQuery-table-header" colspan="3">' + words["sign"] + '</td>';
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

				html += '<td class="tabQuery-table-header">' + words["c.male"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.female"] + '</td>';
				html += '<td class="tabQuery-table-header">' + words["c.total"] + '</td>';

				html += '</tr>';
				
				for(var idx in evtObj) {
					html += '<tr>';
		
					html += '<td width="20" align="center">';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td>';
					html +=  evtObj[idx].title; //+ '{<span style="color:blue;">' + evtObj[idx].date_range +'</span>}';
					html += '</td>';
					
					html += '<td align="center">';
					html +=  evtObj[idx].start_date;
					html += '</td>';
					
					html += '<td align="center">';
					html +=  evtObj[idx].end_date;
					html += '</td>';

					html += '<td>';
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
					html +=  evtObj[idx].msign;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].fsign;
					html += '</td>';
					html += '<td align="right">';
					html +=  evtObj[idx].tsign;
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
                    /*
					html += '<td align="right">';
					html +=  grand.munauth;
					html += '</td>';
					html += '<td align="right">';
					html +=  grand.funauth;
					html += '</td>';
					html += '<td align="right">';
					html +=  grand.tunauth;
					html += '</td>';
                    */
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
					html +=  grand.msign;
					html += '</td>';
					html += '<td align="right">';
					html +=  grand.fsign;
					html += '</td>';
					html += '<td align="right">';
					html +=  grand.tsign;
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
				$("#class_report").html(html);
		}
		
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <fieldset>
    	<legend><?php echo $words["search criteria"]?></legend>
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><span style="color:red;">* </span><?php echo $words["class"]?>: </td>
                                  <td>
									  <select id="sch_class"  style="min-width:250px;">
									  <?php
                                          ob_start();
                                          $result = $db->query("SELECT a.id, a.title, b.title as site_desc 
										  								FROM puti_class a 
										  								INNER JOIN puti_sites b ON (a.site = b.id) 
										  								WHERE a.site IN " . $admin_user["sites"]  . " AND
																			  a.branch IN ". $admin_user["branchs"] . " AND
																			  a.deleted <> 1 ORDER BY a.site, a.branch, a.created_time DESC");
                                          
                                          echo '<option value=""></option>';
                                          while( $row = $db->fetch($result) ) {
                                              echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . '</option>';
                                          }
                                          ob_end_flush();
                                      ?>
									  </select>
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["date range"]?>: </td>
                                  <td>
                                  <?php echo $words["from"]?> <input style="width:80px;" id="sch_sdate" value="<?php echo date("Y-m-d", mktime(0,0,0,1,1,date("Y")));?>" /> 
								  <?php echo $words["to"]?> <input style="width:80px;" id="sch_edate" value="" />
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"></td>
                                  <td>
                                     <input type="button" right="view" 	onclick="class_summary()" 	style="width:100px;" value="<?php echo $words["search"]?>" />                  
                                     <input type="button" right="print" onclick="output_excel()" 	style="width:100px; margin-left:10px;" value="<?php echo $words["output excel"]?>" />                  
                                  </td>
                              </tr>
                          </table>
    </fieldset>
	<div id="class_report" style="padding:5px; min-height:420px;">
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>