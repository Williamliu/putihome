<?php
session_start();
ini_set("display_errors", 1);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="50,60";
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

    	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.slidebox.js"></script>
        <link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.slidebox.css" rel="stylesheet" />

	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var ctt = null;
		var aj = null;
		var htmlObj = new LWH.cHTML();
		var current_rid = -1;
        var vol_detail_html = '';
		$(function(){
			  $("#diaglog_detail").lwhDiag({
				  titleAlign:		"center",
				  title:			words["Member Enrollment"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			500,
				  minHH:			180,
				  btnMax:			false,
				  resizable:		false,
				  movable:			true,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });

              $("#sch_sdate, #sch_edate").datepicker({ 
				    dateFormat: 'yy-mm-dd',  
				    showOn: "button",
				    buttonImage: "../theme/blue/image/icon/calendar.png",
				    buttonImageOnly: true  
			  });

			  
			  
			  ctt = new LWH.cTABLE({
											condition: 	{
												sch_name:	"#sch_name",
												sch_email:	"#sch_email",
												sch_site:	"#sch_site",

												sch_memid:	"#sch_memid",
												sch_idd:	"#sch_idd",
												sch_phone:	"#sch_phone",
												sch_city:	"#sch_city",
												
												sch_position:		"#sch_position",
												sch_resume:			"#sch_resume",
												sch_memo:			"#sch_memo",
												
												sch_degree:			"#sch_degree",
												sch_professional:	"#sch_professional",
												sch_religion:		"#sch_religion",

												sch_vol_type:		"#sch_vol_type",
												sch_email_flag:		"#sch_email_flag",
												sch_gender:			"#sch_gender",
												sch_depart:			"#vol_depart_search",
                                                sch_sdate:          "#sch_sdate",
                                                sch_hh:          	"#sch_hh",
                                                sch_mm:          	"#sch_mm",
                                                sch_schedule_type:  "#sch_schedule_type"
											},
											headers:[
												{title: words["sn"], 			col:"rowno",		width:20},
												{title: words["name"], 			col:"flname", 		sq:"ASC"},
												{title: words["legal name"], 	col:"legal_name", 	sq:"ASC"},
												{title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC"},
												{title: words["short.lang"], 	col:"language", 	sq:"ASC"},
												{title: words["phone"], 		col:"phone", 		sq:"ASC"},
												{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												{title: words["g.site"], 		col:"site", 		sq:"ASC", align:"center"},
                                                {title: words["volunteer.type"], col:"vol_type", 	sq:"ASC"},
												{title: words["volunteer.regdate"],	col:"vol_date",	sq:"DESC"},
												{title: words["member.regdate"], col:"created_time",sq:"ASC", align:"center"},
											    {title: words["c.id"], 			col:"id", 			sq:"ASC"},
											    {title: words["c.photo"], 		col:"photo", 		align:"center"},
												{title:"", 						col:""}
											],
											container: 		"#puti_volunteer_area",
											me:				"ctt",

											url:			"ajax/pt_volunteer1_select.php",
											orderBY: 		"created_time",
											orderSQ: 		"DESC",
											cache:			true,
											expire:			3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			true,
											view:			true,
											output:			true,
											remove:			true,

											pageRows:		pageHTML
										});
			
			ctt.start();
			

			$(".enroll_button_add").live("click", function(ev) {
				var rid = $(this).attr("rid");
				if( $("tr.volunteer-detail-area").length <= 0 ) {
				    var html = '<tr class="volunteer-detail-area"><td colspan="15" style="padding:10px;">';
				    html += '<fieldset id="diaglog_volunteer" style="background-color:#ffffff; border-radius: 10px; border: 1px solid #999999; padding:5px; position:relative; display:none;">';
                    html += '<legend style="font-size:14px; font-weight:bold;">' + words["volunteer information"] + '</legend>';
                    html += vol_detail_html;
                    html += '</fieldset>';
				    html += '</td></tr>';
					$("tr.rows[rid='" + rid + "']").after(html);
                    $("tr.rows").removeClass("tr-selected");
					$("tr.rows[rid='" + rid + "']").addClass("tr-selected");

    				$("#diaglog_volunteer").stop().show(1000);

					current_rid = rid;
					$("#member_id").val(rid);
					volunteer_detail_search_ajax(rid);

				} 
				else {
					if( current_rid == rid ) {
						if( $("tr.volunteer-detail-area").is(":visible") ) {
							$("tr.volunteer-detail-area").stop().hide(500);
                            $("tr.rows").removeClass("tr-selected");
						} else {
							$("tr.volunteer-detail-area").stop().show(1000);
                            $("tr.rows").removeClass("tr-selected");
					        $("tr.rows[rid='" + rid + "']").addClass("tr-selected");

							current_rid = rid;
							$("#member_id").val(rid);
							volunteer_detail_search_ajax(rid);
						}
					} else {
						$("tr.rows[rid='" + rid + "']").after($("tr.volunteer-detail-area"));
						$("tr.volunteer-detail-area").stop().show(1000);
                        $("tr.rows").removeClass("tr-selected");
					    $("tr.rows[rid='" + rid + "']").addClass("tr-selected");

						current_rid = rid;
						$("#member_id").val(rid);
						volunteer_detail_search_ajax(rid);
					}
				}

			});

			$(".vol-flag").live("click", function(ev) {
				  var rid = $(this).attr("rid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  vol_flag:		$("input.vol-flag[rid='" + rid + "']").is(":checked")?1:0,
						  member_id: 	rid
				  	  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  //$("#wait").loadHide();
						  alert("Error (pt_volunteer_add.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  //$("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							tool_tips(words["save success"]);
						  }
					  },
					  type: "post",
					  url: "ajax/pt_volunteer_add.php"
				  });
			});

			
			
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					search_ajax();
				}
			});

			$("#btn_print_empty").bind("click", function(ev) {
				var eid = $("#event_id").val();
				print_signature(eid, 0);
			});

			$("input#sch_idd, input#idd").bind("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd, input#idd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});



			
			// output signature form
			$(".tabQuery-button[oper='print']").live("click", function(ev){
				  var eid = $("#event_id").val();
				  var mid = $(this).attr("rid");
				  print_signature(eid,mid);
			});	

			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});


            vol_detail_html = $("#puti_volunteer_detail").html();
            $("#puti_volunteer_detail").empty();
		});
		
		function search_ajax() {
			ctt.start();
		}

		function print_signature(eid, mid) {
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"print",
						  
					  event_id: 	eid,						
					  member_id:	mid
				  },
				  dataType: "json",  
				  //contentType: "text/html; charset=utf-8",
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (event_calendar_group_signature.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
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
				  url: "ajax/event_calendar_group_signature.php"
			  });
		}
		
		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			for(var idx in pObjs) {
				html += '<tr class="rows" rowno="' + idx + '" rid="'  + pObjs[idx]["id"] + '">';
				
				html += '<td align="center">';
				html += parseInt(idx) + 1;
				html += '</td>';


				html += '<td style="white-space:nowrap;"><span class="flname">';
				html += pObjs[idx]["flname"];
				html += '</span></td>';


				html += '<td style="white-space:nowrap;"><span class="legal_name">';
				html += pObjs[idx]["legal_name"];
				html += '</span></td>';

				html += '<td style="white-space:nowrap;"><span class="dharma_name">';
				html += pObjs[idx]["dharma_name"];
				html += '</span></td>';

				html += '<td align="center"><span class="gender">';
				html += pObjs[idx]["gender"];
				html += '</span></td>';
				
				html += '<td align="center"><span class="gender">';
				html += pObjs[idx]["language"];
				html += '</span></td>';

				html += '<td><span class="phone">';
				html += pObjs[idx]["phone"];
				html += '</span></td>';
				

				html += '<td align="center"><span class="city">';
				html += pObjs[idx]["city"];
				html += '</span></td>';

				html += '<td align="center"><span class="language">';
				html += pObjs[idx]["site"];
				html += '</span></td>';

				html += '<td align="center"><span class="site">';
				html += pObjs[idx]["vol_type"];
				html += '</span></td>';


				html += '<td>';
				html += pObjs[idx]["vol_date"];
				html += '</td>';

				html += '<td>';
				html += pObjs[idx]["created_time"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["id"];
				html += '</td>';

				html += '<td align="center"><span class="photo">';
				html += pObjs[idx]["photo"];
				html += '</span>&nbsp;</td>';

				/*
				html += '<td>';
				html += '<input class="vol-flag" type="checkbox" '+ (pObjs[idx]["vol_flag"]=="1"?'checked':'') + ' rid="' + pObjs[idx]["id"] + '" value="1" />';
				html += '</td>';
				*/
				
				html += '<td align="center"  style="white-space:nowrap;">';
			 	html += '<a class="enroll_button_add" 		oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view volunteer details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-view" 		oper="view" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view member details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-output" 	oper="print" 	right="print" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["print volunteer form"] + '"></a>';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		


		function output_excel() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	
						$("input[name='orderBY']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderBY);	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderSQ);	

						$("input[name='sch_name']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_name);	
						$("input[name='sch_email']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_email);	
						$("input[name='sch_site']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_site);	

						$("input[name='sch_memid']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_memid);	
						$("input[name='sch_idd']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_idd);	
						$("input[name='sch_phone']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_phone);	
						$("input[name='sch_city']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_city);	

						$("input[name='sch_position']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_position);	
						$("input[name='sch_resume']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_resume);	
						$("input[name='sch_memo']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_memo);	


						$("input[name='sch_degree']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_degree);	
						$("input[name='sch_professional']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_professional);	
						$("input[name='sch_religion']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_religion);	

						$("input[name='sch_vol_type']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_vol_type);	
						$("input[name='sch_email_flag']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_email_flag);	
						$("input[name='sch_gender']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_gender);	

						$("input[name='sch_depart']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_depart);	
						$("input[name='sch_sdate']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_sdate);	
						$("input[name='sch_hh']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_hh);	
						$("input[name='sch_mm']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_mm);	
						$("input[name='sch_schedule_type']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_schedule_type);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/pt_volunteer1_print.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_name" value="' + ctt.tabData.condition.sch_name + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email" value="' + ctt.tabData.condition.sch_email + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_site" value="' + ctt.tabData.condition.sch_site + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_memid" value="' + ctt.tabData.condition.sch_memid + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_idd" value="' + ctt.tabData.condition.sch_idd + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_phone" value="' + ctt.tabData.condition.sch_phone + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_city" value="' + ctt.tabData.condition.sch_city + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_position" value="' + ctt.tabData.condition.sch_position + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_resume" value="' + ctt.tabData.condition.sch_resume + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_memo" value="' + ctt.tabData.condition.sch_memo + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_degree" value="' + ctt.tabData.condition.sch_degree + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_professional" value="' + ctt.tabData.condition.sch_professional + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_religion" value="' + ctt.tabData.condition.sch_religion + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_vol_type" value="' + ctt.tabData.condition.sch_vol_type + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_email_flag" value="' + ctt.tabData.condition.sch_email_flag + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_gender" value="' + ctt.tabData.condition.sch_gender + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_depart" value="' + ctt.tabData.condition.sch_depart + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_sdate" value="' + ctt.tabData.condition.sch_sdate + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_hh" value="' + ctt.tabData.condition.sch_hh + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_mm" value="' + ctt.tabData.condition.sch_mm + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_schedule_type" value="' + ctt.tabData.condition.sch_schedule_type + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}

		
		function full_ajax() {
			$("input[name='member_id']").val($("#member_id").val());
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/pt_registration1.php");
			form_register.submit();
		}

		function quick_ajax() {
			$("input[name='member_id']").val($("#member_id").val());
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/pt_qform1.php");
			form_register.submit();
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
              <table cellpadding="2" cellspacing="0">
                  <tr>
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                      <td align="right"><?php echo $words["email"]?>: </td>
                      <td><input oper="search" style="width:120px;font-size:12px;" id="sch_email" value="" /></td>
                      <td align="right"><?php echo $words["g.site"]?>: </td>
                      <td>
                            <select  oper="search" id="sch_site" style="width:80px;" name="sch_site">
                                  <option value=""></option>
                                  <?php
                                      $result_site = $db->query("SELECT id, title FROM puti_sites WHERE status = 1 AND id in " . $admin_user["sites"] . " ORDER BY id"); 
                                      while( $row_site = $db->fetch($result_site) ) {
                                          echo '<option value="' . $row_site["id"] . '">' . $words[strtolower($row_site["title"])] . '</option>';		
                                      }
                                  ?>
                            </select>
                      </td>
                      
                      <td rowspan="4" colspan="2" valign="top" align="left" width="400px;">
                                <fieldset style="border:1px solid #eeeeee; width:100%;">
                                <legend>
                                <span style="font-size:12px;font-weight:bold"><?php echo $words["member.current_depart"]?> : </span>
                                <a id="btn_vol_depart_search" href="javascript:vol_depart_search();"  style="color:blue; text-decoration:underline; cursor:pointer;"><?php echo $words["member.edit"]?></a>
                                </legend>
                                    <input type="hidden" id="vol_depart_search" name="vol_depart_search" value="" />
                                    <div id="department_search" style="font-size:14px; min-height:50px; border: 1px dotted #aaaaaa; padding:10px;"></div>
                                </fieldset>
                      </td>
                  </tr>

                  <tr>
                      <td align="right"><b><?php echo $words["id number"]?>:</b> </td>
                      <td><input style="width:120px;"  oper="search" id="sch_idd" style="width:120px; margin-left:10px;" value="" /></td>
                      <td align="right"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                      <td align="right"><?php echo $words["city"]?>: </td>
                      <td><input oper="search" style="width:80px;" id="sch_city" value="" /></td>
                  </tr>

                  <tr>
                      <td align="right"><?php echo $words["member.position"]?>: </td>
                      <td><input style="width:120px;"  oper="search" id="sch_position" style="width:120px; margin-left:10px;" value="" /></td>
                      <td align="right"><?php echo $words["member.resume"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_resume" value="" /></td>
                      <td align="right"><?php echo $words["member.memo"]?>: </td>
                      <td><input oper="search" style="width:80px;" id="sch_memo" value="" /></td>
                  </tr>

                  <tr>
                      <td align="right"><?php echo $words["member.degree"]?>: </td>
                      <td>
							<?php
                                  echo iHTML::select1($admin_user["lang"], $db, "vw_vol_degree", "sch_degree", "");
                            ?>
                      </td>
                      <td align="right"><?php echo $words["member.professional"]?>: </td>
                      <td>
							<?php
                                  echo iHTML::select1($admin_user["lang"], $db, "vw_vol_professional", "sch_professional", "");
                            ?>
                      </td>
                      <td align="right"><?php echo $words["religion"]?>: </td>
                      <td>
							<?php
                                  echo iHTML::select1($admin_user["lang"], $db, "vw_vol_religion", "sch_religion", "");
                            ?>
                      </td>
                  </tr>

                  <tr>
                     <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["volunteer.type"]?>: </td>
                     <td>
                            <?php echo iHTML::select1($admin_user["lang"], $db, "vw_vol_type", "sch_vol_type", ""); ?>
                     </td>

                     <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["email subscription"]?>: </td>
                     <td>
                         <select oper="search" id="sch_email_flag" name="sch_email_flag">
                            <option value=""></option>
                            <option value="0"><?php echo $words["email.unsubscribe"]?></option>
                            <option value="1"><?php echo $words["email.subscribe"]?></option>
                        </select>
                     </td>

                      <td align="right"><?php echo $words["gender"]?>: </td>
                      <td>
                          <select oper="search" id="sch_gender">
                              <option value=""></option>
                              <option value="Male"><?php echo $words["male"]?></option>
                              <option value="Female"><?php echo $words["female"]?></option>
                          </select>
					   </td>
                       <td align="left" style="width:30px; white-space: nowrap;"><?php echo $words["service date"]?>: </td>
                       <td>
                            <input style="width:80px;" id="sch_sdate" value="" />  
                            <span style="margin-left: 20px;"><?php echo $words["volunteer.schedule.time"]?> :</span>
							  <?php 
                                    echo '<select id="sch_hh" name="sch_hh">';
                                    echo '<option value=""></option>';
                                    for($i=0; $i<=23; $i++) {
                                        echo '<option value="' . $i . '" ' . ($i==9?'':''). '>' . $i . '</option>';
                                    }
                                    echo '</select>';
                                    echo '<b> : </b>';
                                    echo '<select id="sch_mm" name="sch_mm">';
                                    echo '<option value=""></option>';
                                    echo '<option value="00">00</option>';
                                    echo '<option value="15">15</option>';
                                    echo '<option value="30">30</option>';
                                    echo '<option value="45">45</option>';
                                    echo '</select>';
                              ?>

                            <span style="margin-left: 20px;"><?php echo $words["volunteer.schedule.type"]?> :</span>
                            <select oper="search" id="sch_schedule_type" name="sch_schedule_type">
                                <option value=""></option>
                                <option value="0"><?php echo $words["volunteer.schedule.type.daily"]?></option>
                                <option value="1"><?php echo $words["volunteer.schedule.type.weekly"]?></option>
                                <option value="2"><?php echo $words["volunteer.schedule.type.monthly"]?></option>
                            </select>
                       </td>
                  </tr>



                  <tr>
                      <td align="right"></td>
                      <td colspan="7">
                        <input type="button" oper="search" right="view" style="width:100px;" onclick="search_ajax()" style="width:60px;" value="<?php echo $words["search"]?>" />                  
                        <input type="button" oper="search" right="print" style="width:100px;" onclick="output_excel()"  value="<?php echo $words["output excel"]?>" />                  
						<!--
                        <input type="button" oper="search" style="width:100px;" onclick="quick_ajax()"  value="<?php echo $words["quick register"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="full_ajax()"  value="<?php echo $words["full register"]?>" />                  
						-->
                        <a id="btn_print_empty" class="tabQuery-button tabQuery-button-output" style=" vertical-align:middle; margin-left:20px;" right="print" title="<?php echo $words["print empty signature"]?>"></a>                       

	                  	<span style="margin-left:20px;"><?php echo $words["member id"]?>: </span>
                      	<input type="text" style="width:60px;" oper="search" id="sch_memid" name="sch_memid" value="" />

                      </td>
                  </tr>
              </table>
    </fieldset>
 	<div id="puti_volunteer_area" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>

<?php include("tpl_member_detail.php"); ?>
<form name="form_register" action="" method="post">
	<input type="hidden" name="lang" value="<?php echo $Glang;?>" />
	<input type="hidden" name="adminSession" value="<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>" />
	<input type="hidden" name="adminMenu" value="<?php echo $admin_menu; ?>" />
	<!-- <input type="hidden" name="member_id" value="" /> -->
</form>

<?php include("tpl_volunteer_detail.php"); ?>
<?php include("tpl_volunteer_depart.php"); ?>
</body>
</html>