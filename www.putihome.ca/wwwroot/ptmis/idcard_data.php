<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,73";
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
		<title>Bodhi Meditation Student Enrollment</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
		
		<script type="text/javascript" 	src="../jquery/min/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.zoom.js"></script>
		<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.zoom.css" rel="stylesheet" />
		
        <script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.upload.js"></script>
        <link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var ctt = null;
		var aj = null;
		var htmlObj = new LWH.cHTML();

		$(function(){
			
			$("#sch_sdate, #sch_edate").datepicker({ 
							  dateFormat: 'yy-mm-dd',  
							  showOn: "button",
							  buttonImage: "../theme/blue/image/icon/calendar.png",
							  buttonImageOnly: true  
			});
			  
			  ctt = new LWH.cTABLE({
											condition: 	{
												sch_site:	"#sch_site",
												sch_place:	"#sch_place",
												
												sch_name:	"#sch_name",
												sch_phone:	"#sch_phone",
												sch_city:	"#sch_city",
												sch_email:	"#sch_email",
												sch_gender:	"#sch_gender",

												sch_sdate:	"#sch_sdate",
												sch_edate:	"#sch_edate",
												sch_shh:	"#sch_shh",
												sch_ehh:	"#sch_ehh",
												sch_smm:	"#sch_smm",
												sch_emm:	"#sch_emm",
												
												sch_idd:	"#sch_idd"
											},
											headers:[
												{title:	words["sn"], 			col:"rowno",		width:30},
												{title: words["kaoqin.time"], 	col:"created_time",	sq:"ASC"},
												{title: words["id number"], 	col:"idd",			sq:"ASC"},
												{title: words["kaoqin.site"], 	col:"site",			sq:"ASC"},
												{title: words["kaoqin.place"], 	col:"place",		sq:"ASC"},
												{title: words["name"], 			col:"first_name",	sq:"ASC"},
												//{title: words["legal name"], 	col:"legal_first",	sq:"ASC"},
												{title: words["dharma"], 		col:"dharma_name",	sq:"ASC"},
												{title: words["gender"], 		col:"gender",		sq:"ASC"},
												{title: words["phone"], 		col:"phone"},
												{title: words["city"], 			col:"city", align:"center",	sq:"ASC"},
												//{title: words["email"], 		col:"email", 		sq:"ASC"},
												{title: words["r.site"], 		col:"site", align:"center"},
											    {title: words["c.photo"], 		col:"photo",align:"center"},
												{title:"", 						col:""}
											],
											container: 		"#idreader_data_result",
											me:				"ctt",

											url:			"ajax/idcard_data_select.php",
											orderBY: 		"created_time",
											orderSQ: 		"DESC",
											cache:			true,
											expire:			3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			true,
											view:			true,
											output:			false,
											remove:			false,

											pageRows:		pageHTML,
											ajaxDONE:		ajaxDone
										});
			
			ctt.start();
			
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					search_ajax();
				}
			});


			$("input#sch_idd, input#idd").bind("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd, input#idd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});

			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});

			// output signature form
			$(".tabQuery-button[oper='print']").live("click", function(ev){
				if( $("#cert_temp").val() != "" ) {
					  $("#wait").loadShow();
					  var mid = $(this).attr("rid");
					  $.ajax({
						  data: {
								admin_sess: 	$("input#adminSession").val(),
								admin_menu:		$("input#adminMenu").val(),
								admin_oper:		"print",
								
								event_id: 		$("#event_id").val(),
								member_id:		mid
						  },
						  dataType: "json",  
						  //contentType: "text/html; charset=utf-8",
						  error: function(xhr, tStatus, errorTh ) {
							  $("#wait").loadHide();
							  alert("Error ("+ $("#cert_temp").val() + "): " + xhr.responseText + "\nStatus: " + tStatus);
						  },
						  success: function(req, tStatus) {
							  $("#wait").loadHide();
							  var w1 = window.open("output.html");
							  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Student Registration</" + "title" + "></" + "head" + "><" + "body>";
							  w1.document.open();
							  w1.document.write(html_str);
							  w1.document.write(req.data);
							  w1.document.write('</html>');
							  w1.document.close();
							  w1.print();
						  },
						  type: "post",
						  url: "ajax/" + $("#cert_temp").val()
					  });
				} else {
					  alert(words["please select cert. template"]);
				}
			});	

			  $("#sch_date").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: 	"button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
			  });
		});
		
		
		
		function search_ajax() {
			ctt.start();
		}

		function ajaxDone(req) {
			$("#total_count").html(req.data.general.recoTotal);
			$("#member_count").html(req.data.general.membTotal);
		}
		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			for(var idx in pObjs) {
				html += '<tr rowno="' + idx + '" rid="'  + pObjs[idx]["member_id"] + '">';
				
				html += '<td align="center">';
				html += parseInt(idx) + 1;
				html += '</td>';

				html += '<td>';
				html += pObjs[idx]["created_time"];
				html += '</td>';

				html += '<td>';
				html += pObjs[idx]["idd"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["site_desc"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["place_desc"];
				html += '</td>';

				html += '<td><span class="name">';
				html += pObjs[idx]["name"];
				html += '</span></td>';
				
				/*
				html += '<td><span class="legal_name">';
				html += pObjs[idx]["legal_name"];
				html += '</span></td>';
				*/
				
				html += '<td><span class="dharma_name">';
				html += pObjs[idx]["dharma_name"];
				html += '</span></td>';

				html += '<td align="center"><span class="sex">';
				html += pObjs[idx]["gender"];
				html += '</td>';



				html += '<td><span class="phone">';
				html += pObjs[idx]["phone"];
				html += '</span></td>';

				html += '<td align="center"><span class="city">';
				html += pObjs[idx]["city"];
				html += '</span></td>';
				/*
				html += '<td width="120" style="overflow:hidden; width:120px;">';
				html += pObjs[idx]["email"];
				html += '</td>';
				*/
				html += '<td align="center"><span class="site">';
				html += pObjs[idx]["member_site"];
				html += '</td>';

				html += '<td align="center"><span class="photo">';
				html += pObjs[idx]["photo"];
				html += '</span></td>';

				html += '<td align="center"  style="white-space:nowrap;">';
			 	//html += '<a class="enroll_button_add" 		oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["enroll"] + '"></a>';
				//html += ' <a class="tabQuery-button tabQuery-button-output" 	oper="print" 	right="print" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["print details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-view" 		oper="view" 	right="view" 	rsn="' + idx + '"	rid="' + pObjs[idx]["member_id"] + '" title="' + words["view details"] + '"></a>';
				//html += ' <a class="enroll_button_remove" 	oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["cancel enroll"] + '"></a>';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		
		function print_data() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	
						
						$("input[name='orderBY']", "form[name='frm_list_excel']").val( ctt.tabData.condition.orderBY );	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val( ctt.tabData.condition.orderSQ );	


						$("input[name='sch_site']", "form[name='frm_list_excel']").val(  $("#sch_site").val() );	
						$("input[name='sch_place']", "form[name='frm_list_excel']").val(  $("#sch_place").val() );	

						$("input[name='sch_name']", "form[name='frm_list_excel']").val(  $("#sch_name").val() );	
						$("input[name='sch_phone']", "form[name='frm_list_excel']").val(  $("#sch_phone").val() );	
						$("input[name='sch_city']", "form[name='frm_list_excel']").val( $("#sch_city").val() );	
						$("input[name='sch_email']", "form[name='frm_list_excel']").val(  $("#sch_email").val() );	
						$("input[name='sch_gender']", "form[name='frm_list_excel']").val(  $("#sch_gender").val() );	
						
						$("input[name='sch_sdate']", "form[name='frm_list_excel']").val(  $("#sch_sdate").val() );	
						$("input[name='sch_edate']", "form[name='frm_list_excel']").val(  $("#sch_edate").val() );	
						$("input[name='sch_shh']", "form[name='frm_list_excel']").val(  $("#sch_shh").val() );	
						$("input[name='sch_smm']", "form[name='frm_list_excel']").val(  $("#sch_smm").val() );	
						$("input[name='sch_ehh']", "form[name='frm_list_excel']").val(  $("#sch_ehh").val() );	
						$("input[name='sch_emm']", "form[name='frm_list_excel']").val(  $("#sch_emm").val() );	
						
						$("input[name='sch_idd']", "form[name='frm_list_excel']").val(  $("#sch_idd").val() );	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/idcard_data_print.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_site" value="' + $("#sch_site").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_place" value="' + $("#sch_place").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + $("#sch_name").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + $("#sch_phone").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" value="' + $("#sch_city").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + $("#sch_email").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_gender" value="' + $("#sch_gender").val() + '" />');				  
						
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_sdate" value="' + $("#sch_sdate").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_edate" value="' + $("#sch_edate").val() + '" />');	
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_shh" value="' + $("#sch_shh").val() + '" />');	
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_smm" value="' + $("#sch_smm").val() + '" />');	
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_ehh" value="' + $("#sch_ehh").val() + '" />');	
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_emm" value="' + $("#sch_emm").val() + '" />');	
									  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_idd" value="' + $("#sch_idd").val() + '" />');				  
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
    <fieldset>
    	<legend><?php echo $words["search filter"]?></legend>
          </select>
              <table cellpadding="2" cellspacing="0">
             	<tr>
                      <td align="right"><?php echo $words["site_desc"]?>: </td>
                      <td>
                          <select id="sch_site" style="min-width:100px;" name="sch_site">
                              <?php
                                  $result_site = $db->query("SELECT * FROM puti_sites WHERE id in " . $admin_user["sites"]  ." order by id");
                                  while( $row_site = $db->fetch($result_site) ) {
                                      echo '<option value="' . $row_site["id"] . '" ' . ($admin_user["site"]==$row_site["id"]?"selected":"") . '>' . $words[strtolower($row_site["title"])] . '</option>';
                                  }
                              ?>
                          </select>
                      </td>
                      <td align="right"><?php echo $words["place_desc"]?>: </td>
                      <td>
                          <select id="sch_place" style="min-width:100px;" name="sch_place">
                              <option value=""></option>
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
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:80px;" id="sch_name" value="" /></td>
                      <td align="right"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:80px;" id="sch_phone" value="" /></td>
                      <td align="right"><?php echo $words["city"]?>: </td>
                      <td><input oper="search" style="width:80px;" id="sch_city" value="" /></td>
                      <td align="right"><?php echo $words["email"]?>: </td>
                      <td><input oper="search" style="width:80px;" id="sch_email" value="" /></td>
                      <td align="right"><?php echo $words["gender"]?>: </td>
                      <td>
                          <select oper="search" id="sch_gender">
                              <option value=""></option>
                              <option value="Male"><?php echo $words["male"]?></option>
                              <option value="Female"><?php echo $words["female"]?></option>
                          </select> 
                    </td>
                  </tr>
                  <tr>
                      <td align="right">
                          <span style="font-size:14px; font-weight:bold;">
                          <?php echo $words["date range"]?> : 
                          </span>
                      </td>
                      <td style="white-space:nowrap;">
                      	<b><?php echo $words["from"]?>:</b> 
                     	<input style="width:80px;" id="sch_sdate" value="<?php echo date("Y-m-d")?>" /> 
                      </td>
                      <td align="right">
                      	<b><?php echo $words["to"]?>:</b>
                      </td>
                      <td style="white-space:nowrap;">
                      	<input style="width:80px;" id="sch_edate" value="<?php echo date("Y-m-d")?>" />
                      </td>
                  </tr>
                  <tr>
                      <td align="right">
                          <span style="font-size:14px; font-weight:bold;">
                          <?php echo $words["time range"]?> :
                          </span>
                      </td>
                      <td style="white-space:nowrap;">
                      		<b><?php echo $words["from"]?>:</b> 
                      		<?php echo chour("sch_shh", 0) . " : " . cminu("sch_smm", 0);?>       
	                   </td>
                      <td align="right">
							<b><?php echo $words["to"]?>:</b>
                      </td>
                      <td style="white-space:nowrap;">
                      		<?php echo chour("sch_ehh", 24) . " : " . cminu("sch_emm",0); ?>       
                      </td>
                      <td align="right"><?php echo $words["id number"]?>: </td>
                      <td>
                      	<input style="width:120px;"  oper="search" id="sch_idd" style="width:120px; margin-left:10px;" value="" />
                      </td>
                  </tr>
                  <tr>
                      <td align="right"></td>
                      <td colspan="5">
                        <input type="button" oper="search" right="view" style="width:100px;" onclick="search_ajax()" value="<?php echo $words["search"]?>" />                  
				        <input type="button" id="btn_search" right="print" onclick="print_data()" value="<?php echo $words["output excel"]?>" />
                    </td>
                  </tr>
              </table>
    </fieldset>
 	<div style="background-color:#eeeeee;font-size:14px;color:#CB393A;font-weight:bold;height:20px;">
    	<span style="color:blue;margin-left:100px;"><?php echo $words["kaoqin.cishu"] ?></span> : <span id="total_count"></span>
        <span style="color:blue;margin-left:50px;"><?php echo $words["kaoqin.renshu"] ?></span> : <span id="member_count"></span>
    </div>
 	<div id="idreader_data_result" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>
<?php include("tpl_member_detail.php"); ?>

<?php 
function chour($id, $val) {
	$html = '<select id="' . $id . '" style="text-align:center;">';
	for($i=0;$i<=24;$i++) {
		$html .= '<option value="'. $i .'" ' . ($val==$i?'selected':'') . '>' .  str_pad($i, 2, "0", STR_PAD_LEFT) . '</option>';
	}
	$html .= '</select>';
	return $html;
}
function cminu($id, $val) {
	$html = '<select id="' . $id . '"  style="text-align:center;">';
	$html .= '<option value="0" ' . ($val==0?'selected':'') . '>00</option>';
	$html .= '<option value="15" ' . ($val==15?'selected':'') . '>15</option>';
	$html .= '<option value="30" ' . ($val==30?'selected':'') . '>30</option>';
	$html .= '<option value="45" ' . ($val==45?'selected':'') . '>45</option>';
	$html .= '</select>';
	return $html;
}
?>
</body>
</html>