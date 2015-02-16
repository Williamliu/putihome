<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,15";
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
		<title>Bodhi Meditation Class Report</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var ctt = null;
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

			ctt = new LWH.cTABLE({
											condition: 	{
												sch_sdate:"#sch_sdate",
												sch_edate:"#sch_edate",
												sch_class:"#sch_class",
												sch_sign:"#sch_sign",
												sch_grad:"#sch_grad",
												sch_cert:"#sch_cert",
												sch_rate:"#sch_rate",
												sch_name:"#sch_name",
												sch_city:"#sch_city",
												sch_level:"#sch_level"
												
											},
											headers:[
												{title: words["sn"], 			col:"rowno",		width:20},
												{title: words["first name"], 	col:"first_name", 	sq:"ASC"},
												//{title: words["last name"], 	col:"last_name", 	sq:"ASC"},
												{title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
												{title: words["member.title"], 	col:"member_title", sq:"ASC"},
												//{title: words["legal name"], 	col:"legal_first", 	sq:"ASC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC"},
												//{title:"Email", 		col:"email", 		sq:"ASC"},
												//{title:"Phone", 		col:"phone", 		sq:"ASC"},
												{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												//{title: words["start date"], 	col:"start_date",	sq:"ASC"},
												//{title: words["enroll"], 		col:"enroll_total",	sq:"ASC", align:"center"},
												//{title: words["unauth"], 		col:"unauth_total",	sq:"ASC", align:"center"},
												//{title: words["trial"], 		col:"trial_total",	sq:"ASC", align:"center"},
												//{title: words["sign"], 			col:"sign_total",	sq:"ASC", align:"center"},
												{title: words["grad."], 		col:"grad_total",	sq:"ASC", align:"center"},
												{title: words["cert."], 		col:"cert_total",	sq:"ASC", align:"center"},
												{title: words["total checkin"], col:"total_checkin",align:"center"},
												{title: words["total attend"], 	col:"total_attend",	align:"center"},
												{title: words["total leave"],	col:"total_leave",	align:"center"},
												{title: words["attd."],			col:"attr_total", 	sq:"ASC", align:"right"}
											],
											container: 	"#tabrow",
											me:			"ctt",

											url:		"ajax/class_report1_select.php",
											orderBY: 	"first_name",
											orderSQ: 	"ASC",
											cache:		true,
											expire:		3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			false,
											pageRows:		pageHTML
										});
			
			  $("#sch_sdate, #sch_edate").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
			  });


			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					ctt.start();
				}
			});

		});
		
		function output_excel() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	
						$("input[name='orderBY']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderBY);	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderSQ);	

						$("input[name='sch_sdate']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_sdate);	
						$("input[name='sch_edate']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_edate);	
						$("input[name='sch_class']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_class);	
						$("input[name='sch_sign']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_sign);	
						$("input[name='sch_grad']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_grad);	
						$("input[name='sch_cert']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_cert);	
						$("input[name='sch_rate']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_rate);	
						$("input[name='sch_name']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_name);	
						$("input[name='sch_city']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_city);	
						$("input[name='sch_level']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_level);	
						$("input[name='details']", "form[name='frm_list_excel']").val($("#include_details").is(":checked")?1:0);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/class_report2_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_sdate" value="' + ctt.tabData.condition.sch_sdate + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_edate" value="' + ctt.tabData.condition.sch_edate + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_class" value="' + ctt.tabData.condition.sch_class + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_sign" value="' + ctt.tabData.condition.sch_sign + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_grad" value="' + ctt.tabData.condition.sch_grad + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_cert" 	value="' + ctt.tabData.condition.sch_cert + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_rate" 	value="' + ctt.tabData.condition.sch_rate + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" 	value="' + ctt.tabData.condition.sch_name + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" 	value="' + ctt.tabData.condition.sch_city + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_level" 	value="' + ctt.tabData.condition.sch_level + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="details" 	value="' + ($("#include_details").is(":checked")?1:0) + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			
			var c0 = ' style="background-color:#C7DFF2;"';
			var c1 = ' style="background-color:#FFF5D7;"';

			for(var idx in pObjs) {
				html += '<tr rowno="' + idx + '" rid="'  + pObjs[idx]["member_id"] + '">';
				
				html += '<td align="center" ' + c0 + '>';
				html += parseInt(idx) + 1;
				html += '</td>';


				html += '<td ' + c0 + '>';
				html += pObjs[idx]["first_name"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["dharma_name"];
				html += '</td>';

				html += '<td ' + c0 + '>';
				html += pObjs[idx]["member_title"];
				html += '</td>';

                /*
				html += '<td ' + c0 + '>';
				html += pObjs[idx]["legal_first"];
				html += '</td>';
                */

				html += '<td ' + c0 + '>';
				html += pObjs[idx]["gender"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["city"];
				html += '</td>';

                /*
				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["enroll_total"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["unauth_total"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["trial_total"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["sign_total"];
				html += '</td>';
                */

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["grad_total"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["cert_total"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["total_checkin"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["total_attend"];
				html += '</td>';

				html += '<td align="center" ' + c0 + '>';
				html += pObjs[idx]["total_leave"];
				html += '</td>';


				html += '<td align="right" ' + c0 + '>';
				html += pObjs[idx]["attr_total"];
				html += '</td>';

				html += '</tr>';
				if( $("#include_details").is(":checked") ) {
					  for(var idx1 in pObjs[idx].evts) {
						  var eObj = pObjs[idx].evts[idx1];
						  html += '<tr class="tr-sub" rid="'  + pObjs[idx]["member_id"] + '">';
						  html += '<td colspan="3"></td>';
	  
						  html += '<td colspan="3" align="right" ' + c1 + '>';
						  html += parseInt(idx1) + 1 + '. ';
						  html += eObj["event_date"];
						  html += '</td>';
	  
                          /*
						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["enroll"];
						  html += '</td>';
	  
						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["unauth"];
						  html += '</td>';

						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["trial"];
						  html += '</td>';
	  
						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["signin"];
						  html += '</td>';
	                      */
	  
						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["graduate"];
						  html += '</td>';
	  
						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["cert"];
						  html += '</td>';
	  
						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["total_checkin"];
						  html += '</td>';

						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["total_attend"];
						  html += '</td>';

						  html += '<td align="center" ' + c1 + '>';
						  html += eObj["total_leave"];
						  html += '</td>';

						  html += '<td align="right" ' + c1 + '>';
						  html += eObj["attend"];
						  html += '</td>';
						  
						  html += '</tr>';
					  }
				}
				
			}
			return html;
		}

		function search_ajax() {
			ctt.start();
		}
		
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <fieldset>
    	<legend>Search Criteria</legend>
                          <table cellpadding="2" cellspacing="0">
                              <tr>
                                  <td align="right"><span style="color:red; font-size:14px; font-weight:bold;">* </span><?php echo $words["class"]?>: </td>
                                  <td>
									  <select oper="search" id="sch_class" style="min-width:250px;">
									  <?php
                                          ob_start();
                                          $result = $db->query("SELECT a.id, a.title, b.title as site_desc
										  								FROM puti_class a 
										  								INNER JOIN puti_sites b ON (a.site = b.id) 
																		WHERE a.site IN " . $admin_user["sites"]  . " AND
																			  a.branch IN ". $admin_user["branchs"] . " AND
																			  a.deleted <> 1 ORDER BY a.site, a.branch, created_time DESC");
                                          
                                          echo '<option value=""></option>';
                                          while( $row = $db->fetch($result) ) {
                                              echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . '</option>';
                                          }
                                          ob_end_flush();
                                      ?>
									  </select>
                                  </td>
                                  <td align="right"><?php echo $words["sign"]?>: </td>
                                  <td>
                                      <select oper="search" id="sch_sign">
                                          <option value=""></option>
                                          <option value="1"><?php echo $words["yes"]?></option>
                                          <option value="0"><?php echo $words["no"]?></option>
                                      </select> 
                                      <?php echo $words["graduate"]?>: 
                                      <select oper="search" id="sch_grad">
                                          <option value=""></option>
                                          <option value="1"><?php echo $words["yes"]?></option>
                                          <option value="0"><?php echo $words["no"]?></option>
                                      </select>    
									  <?php echo $words["certification"]?>:
                                      <select oper="search" id="sch_cert">
                                          <option value=""></option>
                                          <option value="1"><?php echo $words["yes"]?></option>
                                          <option value="0"><?php echo $words["no"]?></option>
                                      </select>    
                                  </td>
                                  <td><?php echo $words["attd."]?>: >= <input oper="search" style="width:30px;text-align:center;" id="sch_rate" value="" /><span style="font-size:16px;font-weight:bold;">%</span></td>
                              </tr>
                              <tr>
                                  <td align="right"><?php echo $words["date range"]?>: </td>
                                  <td>
                                  From <input style="width:80px;" id="sch_sdate" value="<?php echo date("Y-m-d", mktime(0,0,0,1,1,date("Y")));?>" /> 
								  TO <input style="width:80px;" id="sch_edate" value="" />
                                  </td>
                                  <td align="right"><?php echo $words["name"]?>: </td>
                                  <td>
                                  <input oper="search" style="width:80px;" id="sch_name" value="" />
								  <span style="margin-left:20px;"><?php echo $words["city"]?>: </span>
                                  <input oper="search" style="width:80px;" id="sch_city" value="" />
                                  </td>
                                  <td>
                                  <span style="margin-left:10px;"><?php echo $words["member.title"]?>: </span>
                                  <select id="sch_level" style="text-align:center;" name="level">
                                      <option value=""></option>
                                      <?php
                                          $result_lvl = $db->query("SELECT * FROM puti_info_title order by id");
                                          while( $row_lvl = $db->fetch($result_lvl) ) {
                                              echo '<option value="' . $row_lvl["id"] . '">' . $row_lvl["title"] . '</option>';
                                          }
                                      ?>
                                  </select>
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right" style="white-space:nowrap;"><?php echo $words["include details"]?>: </td>
                                  <td>
                                      <input type="checkbox" id="include_details" name="include_details" checked="checked" value="details"  /><label for="include_details" style="text-decoration:underline;"><?php echo $words["include details"]?></label>
                                  </td>
                              </tr>
                              <tr>
                                  <td align="right"></td>
                                  <td>
                                     <input type="button" right="view" onclick="search_ajax()" style="width:100px;" value="<?php echo $words["search"]?>" />                  
                                     <input type="button" right="print" onclick="output_excel()" style="width:100px; margin-left:10px;" value="<?php echo $words["output excel"]?>" />                  
                                  </td>
                              </tr>
                          </table>
    </fieldset>
 	<div id="tabrow" style="min-height:320px;"></div>
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