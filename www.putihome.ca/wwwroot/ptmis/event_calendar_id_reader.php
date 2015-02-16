<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,100";
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
		<title>Bodhi Meditation Attend CheckIn</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.global.timer.js"></script>
 
 		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var gTimer 		= null;
		var gTimer11 	= null;
		var gTimer22 	= null;
		var allObj		= null;
		
		$(function(){

			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			words["member enrollment"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			250,
				minHH:			120,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false,
				diag_open:	function() {
					gTimer.stop();
					gTimer11.stop();
					$("#enroll_group").focus();
				},
				
				diag_close: function() {
				 	$("#enroll_member_id").val("");
					$("#enroll_trial").attr("checked", false);
					$("#enroll_group").val("");

				    $("input#sch_idd").focus();
					$("input#sch_idd").select();
					
					 gTimer.start();
					 gTimer11.start();
				}
			});

			
			$("select.device_place").live("change", function(ev) {
				  var dev_id = $(this).attr("rid");
				  var place_id = $(this).val();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  device_id:	dev_id,
						  place:		place_id	
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (event_calendar_device_place.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
						  }
					  },
					  type: "post",
					  url: "ajax/event_calendar_device_place.php"
				  });
		    });


			$("input#sch_idd").live("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd").live("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});

			$("input#sch_idd").live("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					scan_idd( $(this).val() );
				}
			});



			$(".tabQuery-button-delete").live("click", function(ev) {
				del_idd( $(this).attr("rid"), $(this).attr("sn") );
			});

			$(".trial[mid]").live("click", function(ev) {
				var eid = $("#event_id").val();
				var mid = $(this).attr("mid");
				var ttt = $(this).is(":checked")?1:0;
				trail_ajax(eid,mid,ttt);
			});

			$("a.student-enroll[mid]").live("click", function(ev) {
				 $("#enroll_member_id").val($(this).attr("mid"));
				 $("#enroll_name").html($("td[mid='" + $(this).attr("mid") + "']").html());
				 $("#diaglog_message").diagShow();
				 return;
			});	
			
			$("#btn_enroll_submit").live("click", function(ev) {
				enroll_save();
			});	


            $("input#tabQuery_pageSize", "#holder_list > div.tabQuery-dbnav-background").live("focusin", function(ev) {
                gTimer.stop();                
            });


            $("input#tabQuery_pageSize", "#holder_list > div.tabQuery-dbnav-background").live("focusout", function(ev) {
		        $("input#sch_idd").focus();
				$("input#sch_idd").select();
                gTimer.start();
            });

            $("#event_id").live("focusin", function(ev) {
                gTimer.stop();                
            });

            $("#event_id").live("focusout", function(ev) {
		        $("input#sch_idd").focus();
				$("input#sch_idd").select();
                gTimer.start();
            });

			gTimer = new LWH.timerClass({
								meObj: 	"gTimer",
								interval:	2000,
								func: function() {
									$("input#sch_idd").focus();
									$("input#sch_idd").select();
								}
							});
			gTimer.start();
			
			
			gTimer11 = new LWH.timerClass({
								meObj: 		"gTimer11",
								interval:	180 * 1000,
								func: function() {
									  timer_fresh();
								}
							});
			
			gTimer11.start();


			allObj = new LWH.cTABLE({
										  condition: 	{ 
											  event_id: "#event_id"
										  },
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:25},
											  {title: words["checkin_state"], col:"enroll_flag",	width:25},
											  {title: words["time"], 		col:"time"},
											  {title: words["grp"], 		col:"group_no"},
											  {title: words["trial"], 		col:"trial"},
											  {title: words["trial time"], 	col:"trial_date"},
											  {title: words["name"], 		col:"name2"},
											  {title: words["gender"], 		col:"gender"},
											  {title: words["phone"], 		col:"phone"},
											  {title: words["city"], 		col:"city"},
											  {title: words["g.site"], 		col:"site"},
											  {title: words["shoes.shelf"], col:"shelf"},
											  {title: words["paid"], 		col:"paid"},
											  {title: words["id card"], 	col:"idd"},
											  {title: "&nbsp;", 					col:""}
										  ],
										  container: 	"#holder_list",
										  me:			"allObj",
		
										  url:		"ajax/event_calendar_checkin_select.php",
										  orderBY: 	"a.created_time",
										  orderSQ: 	"DESC",
										  pageSize:	10,
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
										  ajaxDONE:		pageDone										  
							  });

				$("#div_device").html(device_head());

				try { window.external.set_session("<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>"); } catch( ex ){}
				try { window.external.set_media_url("<?php echo $CFG["http"] . $CFG["admin_domain"] . "/error.mp3";?>"); } catch( ex ){}
				
				search_device();
				allObj.fresh();		
		});
		
		function headHTML(obj, others) {
			var html = '';
			//html += '<tr rid="title">';
			//html += '<td colspan="13" style="text-align:center; font-size:16px; font-weight:bold;" class="tabQuery-table-header">' + words["check in list"] + '</td>';
			//html += '</tr>';
			html += '<tr rid="footer">';
			html += '<td colspan="3" style="text-align:left; background-color:#999999;" class="tabQuery-table-header">' + words["grand total"] + ' </td>';
			html += '<td colspan="4" style="text-align:right;background-color:#999999;" class="tabQuery-table-header">' + words["punch"] + ': </td>';
			html += '<td style="text-align:left; background-color:#999999;" class="tabQuery-table-header"><span id="footer_punch">'  + (others.total_punch?others.total_punch:'') + '</span></td>';
			html += '<td style="text-align:right; background-color:#999999; color:red; font-size:18px;" class="tabQuery-table-header">' + words["student"] + ': </td>';
			html += '<td style="text-align:left; background-color:#999999; color:red; font-size:18px;" class="tabQuery-table-header" colspan="5"><span id="footer_student">'  + (others.total_student?others.total_student:'') + '</span></td>';
			html += '<td style="text-align:left; background-color:#999999;" class="tabQuery-table-header"></td>';
			html += '</tr>';

			html += '<tr rid="header">';
			html += '<td class="tabQuery-table-header" style="white-space:nowrap;" width="30">' + words["sn"] + '</td>';
			html += '<td class="tabQuery-table-header" style="white-space:nowrap;">' + words["checkin_state"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["time"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["grp"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["trial"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["trial time"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["name"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["gender"] + '</td>'; 
			html += '<td class="tabQuery-table-header">' + words["phone"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["city"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["g.site"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["shoes.shelf"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["paid"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["id card"] + '</td>';
			html += '<td class="tabQuery-table-header">&nbsp;</td>';
			html += '</tr>';
			return html;
		}
	
		function pageDone(req) {
			$("span#footer_punch").html(req.data.others.total_punch);
			$("span#footer_student").html(req.data.others.total_student);
		}
		
		function pageHTML(pgData) {
			var html = '';
			for(var idx in pgData.rows) {
			  var obj = pgData.rows[idx];
			  html += '<tr class="student" rid="' +  obj.id + '" sn="' + idx + '">';
			  html += '<td class="sn" align="center" width="20">';
			  html +=  parseInt(idx) + 1;
			  html += '&nbsp;</td>';
			  
			  var verify_txt	= '';
			  if(obj.trial == 1) 
			  		verify_txt = '<span style="color:red;">(' + words["trial"] + ')</span>'; 
			  if( obj.paid != "Y" && obj.paid != "Free") 		
			  		verify_txt = '<span style="color:red;">(' + words["unpaid"] + ')</span>'; 
			  if( obj.trial_exp 	== 1) 	
					verify_txt = '<span style="color:red;">(' + words["trial_exp"] + ')</span>'; 
			  if( obj.unenroll == 1) 	
			  		verify_txt = '<span style="color:red;">(' + words["unenroll"] + ')</span>';  
			  if( obj.unauth == 1) 
			  		verify_txt = '<span style="color:red;">('  + words["unauth"] + ')</span>';  
			  if( obj.invalid == 1) 		
			  		verify_txt = '<span style="color:red;">(' + words["invalid member"] + ')</span>';  
			  
			  html += '<td class="enroll-status" style="white-space:nowrap;">';
			  html += '<a class="enroll-status-' + obj.state + '"></a>';
			  html += verify_txt
			  html += '&nbsp;</td>';

			  html += '<td>';
			  html += obj.time;
			  html += '&nbsp;</td>';
			  html += '<td align="center">';
			  html +=  obj.group_no;
			  html += '&nbsp;</td>';
			  html += '<td align="center">';
			  if( !(obj.unenroll == 1 || obj.unauth == 1) )  
				  html += '<input class="trial" rid="' + obj.id + '" sn="' + idx + '" mid="' + obj.member_id + '" type="checkbox" ' + (obj.trial=="1"?'checked':'')+ ' value="1" />';
			  //html += obj.trial=="1"?words["trial"]:'';
			  html += '&nbsp;</td>';
			  html += '<td align="right">';
			  html += obj.trial=="1"?obj.trial_length:'';
			  html += '&nbsp;</td>';
			  html += '<td mid="' + obj.member_id + '">';
			  html +=  obj.name2;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  obj.gender;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  obj.phone;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  obj.city;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  obj.site;
			  html += '&nbsp;</td>';
			  html += '<td align="center"><span class="shelf">';
			  html +=  obj.shelf;
			  html += '</span>&nbsp;</td>';
			  html += '<td align="center">';
			  html +=  obj.paid;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  obj.idd;
			  html += '&nbsp;</td>';
			  html += '<td align="left" valign="middle">';
			  html += '<a class="tabQuery-button tabQuery-button-delete" style="vertical-align:middle;"  oper="delete" right="delete" rid="' +  obj.id + '" sn="' + idx + '"  title="直接删除"></a>';					 
			  if( obj.unenroll == 1 || obj.unauth == 1 )  
			  			html += '<a class="student-enroll"  style="vertical-align:middle;" rid="' + obj.id + '" sn="" mid="' + obj.member_id + '" title="' + words["enroll"] + '"><a>';
			  html += '&nbsp;</td>';
			  html += '</tr>';
			}
			return html;
		}

		function scanHTML(scanObj) {
			  if( $("tr.student[rid='" + scanObj.id + "']", "#holder_list").length > 0 ) 
			  {
					$("tr[rid='header']", "#holder_list").after($("tr.student[rid='" + scanObj.id + "']", "#holder_list"));	
					return;		  
			  }
			  
			  var pageSize = $("#tabQuery_pageSize", "#holder_list").val();
			  if( $("tr.student", "#holder_list").length >= pageSize ) $("tr.student:last", "#holder_list").remove(); 
			  var html = '';
			  html += '<tr class="student" rid="' +  scanObj.id + '" sn="">';
			  html += '<td class="sn" align="center" width="20">';
			  html +=  1;
			  html += '&nbsp;</td>';
			 
			  var verify_txt	= '';
			  if(scanObj.trial == 1) 
			  		verify_txt = '<span style="color:red;">(' + words["trial"] + ')</span>'; 
			  if( scanObj.paid != "Y" && scanObj.paid != "Free") 		
			  		verify_txt = '<span style="color:red;">(' + words["unpaid"] + ')</span>'; 
			  if( scanObj.trial_exp 	== 1) 	
					verify_txt = '<span style="color:red;">(' + words["trial_exp"] + ')</span>'; 
			  if( scanObj.unenroll == 1) 	
			  		verify_txt = '<span style="color:red;">(' + words["unenroll"] + ')</span>';  
			  if( scanObj.unauth == 1) 
			  		verify_txt = '<span style="color:red;">('  + words["unauth"] + ')</span>';  
			  if( scanObj.invalid == 1) 		
			  		verify_txt = '<span style="color:red;">(' + words["invalid member"] + ')</span>';  
			  
			  html += '<td class="enroll-status" style="white-space:nowrap;">';
			  html += '<a class="enroll-status-' + scanObj.state +  '"></a>';
			  html += verify_txt
			  html += '&nbsp;</td>';

			  html += '<td>';
			  html += scanObj.time;
			  html += '&nbsp;</td>';
			  html += '<td align="center">';
			  html +=  scanObj.group_no;
			  html += '&nbsp;</td>';
			  html += '<td align="center">';
			  if( !(scanObj.unenroll == 1 || scanObj.unauth == 1) ) 
			  html += '<input class="trial" rid="' + scanObj.id + '" sn="" mid="' + scanObj.member_id + '" type="checkbox" ' + (scanObj.trial=="1"?'checked':'')+ ' value="1" />';
			  //html += scanObj.trial=="1"?words["trial"]:'';
			  html += '&nbsp;</td>';
			  html += '<td align="right">';
			  html += scanObj.trial=="1"?scanObj.trial_length:'';
			  html += '&nbsp;</td>';
			  html += '<td mid="' + scanObj.member_id + '">';
			  html +=  scanObj.name2;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  scanObj.gender;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  scanObj.phone;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  scanObj.city;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  scanObj.site;
			  html += '&nbsp;</td>';
			  html += '<td align="center"><span class="shelf">';
			  html +=  scanObj.shelf;
			  html += '</span>&nbsp;</td>';
			  html += '<td align="center">';
			  html +=  scanObj.paid;
			  html += '&nbsp;</td>';
			  html += '<td>';
			  html +=  scanObj.idd;
			  html += '&nbsp;</td>';
			  html += '<td align="left" valign="middle">';
			  html += '<a class="tabQuery-button tabQuery-button-delete" style="vertical-align:middle;" oper="delete" right="delete" rid="' +  scanObj.id + '" sn="" title="直接删除"></a>';					 
			  if( scanObj.unenroll == 1 || scanObj.unauth == 1 )  
			  			html += '<a class="student-enroll" style="vertical-align:middle;" rid="' + scanObj.id + '" sn="" mid="' + scanObj.member_id + '" title="' + words["enroll"] + '"><a>';
			  html += '&nbsp;</td>';
			  html += '</tr>';
			$("tr[rid='header']", "#holder_list").after(html);
			
			$("tr.student", "#holder_list").each(function(idx, el) {
				$(".sn", this).html( idx + 1);
				$(this).attr("sn", idx + 1); 
				$("*[sn]", this).attr("sn", idx+1);
			});
		}

		function device_head() {
			var html = '';
			html += '<table id="mytab_alldevice"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
			html += '<tr rid="header">';
			html += '<td class="tabQuery-table-header">' + words["select"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["status"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["site_desc"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["place_desc"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["device_no"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["device_id"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["ip_address"] + '</td>'; 
			html += '<td class="tabQuery-table-header">' + words["last_updated"] + '</td>'; 
			html += '</tr>';
		  	html += '</table>';
			return html;
		}
		
		function device_html(dObj) {
			var html = '';
			html += '<tr rid="' + dObj.device_id + '">';
			html += '<td align="center">';
			html += '<input type="checkbox" class="device_select" rid="' + dObj.device_id + '"  value="' + dObj.device_id + '" />';
			html += '</td>';
			html += '<td class="status" align="center">';
			html += dObj.status; 
			html += '</td>';
			html += '<td class="site_desc">';
			html += dObj.site_desc;
			html += '</td>';
			html += '<td class="place_desc">';
			html += dObj.place_desc;
			html += '</td>';
			html += '<td class="device_no" align="center">';
			html += dObj.device_no;
			html += '</td>';
			html += '<td class="device_id" align="center">';
			html += dObj.device_id;
			html += '</td>';
			html += '<td class="ip_address">';
			html += dObj.ip_address;
			html += '</td>';
			html += '<td class="last_updated">';
			html += dObj.last_updated;
			html += '</td>';
			html += '</tr>';
			return html;
		}
		
		function replace_device(dObj) {
			var devObj = $("tr[rid='" + dObj.device_id + "']");
			$("td.status", devObj).html(dObj.status);
			$("td.site_desc", devObj).html(dObj.site_desc);
			$("td.place_desc", devObj).html(dObj.place_desc);
			$("td.device_no", devObj).html(dObj.device_no);
			$("td.device_id", devObj).html(dObj.device_id);
			$("td.ip_address", devObj).html(dObj.ip_address);
			$("td.last_updated", devObj).html(dObj.last_updated);
		}
		
		function call_reader(idd, device_id) {
			if( $("input.device_select[rid='" + device_id + "']").is(":checked") ) {
				$("#sch_idd").val(idd);
				scan_idd(idd);
			}
		}

		function scan_idd(idd) {
			idd = $.trim(idd);
			if( idd == "" ) return;
			$("#wait").loadShow();	
			
			try { window.external.stop_media(); } catch( ex ){}
			
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",

					pageNo:		allObj.tabData.pageNo,
					pageSize:	allObj.tabData.condition.pageSize,
					orderBY:	allObj.tabData.condition.orderBY,
					orderSQ:	allObj.tabData.condition.orderSQ,

					event_id: 	$("#event_id").val(), 
					sch_idd:  	idd	
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
			  	    $("#wait").loadHide();				  
					alert("Error (event_calendar_checkin_scan.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
			  	    $("#wait").loadHide();				  
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						$("#sch_result").html(req.data.msg);

						if( req.data.list_flag == "1") {
							$("span#footer_punch").html(req.data.others.total_punch);
							$("span#footer_student").html(req.data.others.total_student);
                            $("span.tabQuery_recoTotal", "#holder_list").html(req.data.others.total_punch);
						}
						
						if(req.data.scan["member_id"]>=0) {
							scanHTML(req.data.scan);
						}
						
						if( req.data.music_flag == "1") { 
							try { window.external.start_media(); } catch( ex ){}
						} else {
							try { window.external.stop_media(); } catch( ex ){}
						}
					}
				},
				type: "post",
				url: "ajax/event_calendar_checkin_scan.php"
			});
		}


		function del_idd(id, sn) {
	  	    $("#wait").loadShow();				  
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"delete",
				 
					id: id,
					sn: sn	
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
			  	    $("#wait").loadHide();				  
					alert("Error (event_calendar_checkin_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
			  	    $("#wait").loadHide();				  
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						$("tr.student[sn='" + req.data.sn + "']","#holder_list").remove();
						allObj.fresh();
					}
				},
				type: "post",
				url: "ajax/event_calendar_checkin_delete.php"
			});
		}

		function trail_ajax(eid, mid, ttt) {
			  $("#wait").loadShow();				  
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"save",
			  
					  event_id:		eid,
					  member_id: 	mid,
					  trial:		ttt
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_calendar_checkin_trial.php): " + xhr.responseText + "\nStatus: " + tStatus);
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
				  url: "ajax/event_calendar_checkin_trial.php"
			  });
		}




		function enroll_save() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"save",
  
					  member_id: 	$("#enroll_member_id").val(),
					  event_id:		$("#event_id").val(),
					  trial:		$("#enroll_trial").is(":checked")?1:0,
					  group_no:		$("#enroll_group").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_calendar_enroll_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						allObj.fresh();
						$("#diaglog_message").diagHide();
						tool_tips(words["member enroll success"]);	
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_enroll_save.php"
			  });
		}




		function search_device() {
			reset_device_status("reset");
			try { window.external.seach_device(); } catch( ex ){}
		}
		
		
		function update_device(dstr) {
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"view",
					  
					  device_str:   dstr
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (event_calendar_device_update.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						  if( $("tr[rid='" + req.data.device.device_id + "']").length <=0 ) {
						  		$("#mytab_alldevice").append(device_html(req.data.device));
						  } else {
							  replace_device(req.data.device);
							  //$("tr[rid='" + req.data.device.device_id + "']").replaceWith( device_html(req.data.device) );
						  }
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_device_update.php"
			  });
		}
		
		function event_select() {
			$("#sch_idd").val("");
			$("#sch_result").empty();
			allObj.fresh();
		}
		
		
		function timer_fresh() {
			$("#sch_idd").val("");
			$("#sch_result").empty();
			//allObj.fresh();		
		}

		function list_refresh() {
			$("#sch_idd").val("");
			$("#sch_result").empty();
			allObj.fresh();		
		}

		
		function reader_span(a, dev_id) {
			$("#div_from_reader").html(a);
		}
		
		function hide_device() {
			$("#btn_show_device").show();
			$(".div_container").hide();
		}
		function show_device() {
			$("#btn_show_device").hide();
			$(".div_container").show();
		}
		function reset_device_status(a) {
			$("a.device-status").removeClass("device-status-active").addClass("device-status-inactive");
		}
		/*
		function reply_reader() {
			if( $("#btn_reply").is(":checked") ) {
				window.external.reply_reader(1);
			} else {
				window.external.reply_reader(0);
			}
		}
		*/
        </script>

</head>
<body style="background-image:none;background-color:#F3E5E0;">
<?php 
//include("admin_menu_html.php");
?>
<div>
    <div class="div_container" style="display:block; position:relative; background-color:#eeeeee; text-align:left; font-size:12px; padding:5px; vertical-align:middle; font-weight:bold;">
        <a href="javascript:hide_device();" style="text-decoration:underline;font-size:14px;margin-left:2px;color:#CB393A;font-weight:bold;"><?php echo $words["button hide"]?></a>
        <a href="javascript:search_device();" style="text-decoration:underline;font-size:14px;margin-left:20px;color:#CB393A;font-weight:bold;"><?php echo $words["search id reader"]?></a>
		<span style="margin-left:100px;"><?php echo $words["id reader list"]?></span>
    </div>
	<div class="div_container" style="background-color:#C69EF5;text-align:left;padding:0px;margin:0px;">
        <div id="div_device"></div>
    </div>
   	<div  style="position:relative; background-color:#eeeeee; height:16px;  padding:5px;">
        <a href="javascript:show_device();" id="btn_show_device" style="display:none; text-decoration:underline;font-size:14px;color:#CB393A;font-weight:bold;vertical-align:middle;float:left;"><?php echo $words["button show"]?></a>
		<!-- <input type="checkbox" id="btn_reply" onclick="reply_reader();" value="1" /><label for="btn_reply" style="font-size:14px;color:#CB393A;font-weight:bold;text-decoration:underline;"><?php echo $words["reply reader"]?></label> -->
        <span id="div_from_reader" style="margin-left:20px;color:#666666;text-align:left; font-size:12px;vertical-align:middle; font-weight:bold;"></span>	
    </div>
	<div style="background-color:#A7CFF4;">
        <input type="button" style="margin-left:2px; vertical-align:middle; text-align:center;" id="btn_erfresh" onclick="list_refresh()" value="<?php echo $words["button refresh"];?>" />
        <span style="font-size:16px; margin-left:10px; font-weight:bold; vertical-align:middle;">
            <?php echo $words["please select event"]?> : 
        </span>
        <select id="event_id" onchange="event_select(this.value);" style="min-width:250px; vertical-align:middle;">
        <?php 
            $query = "SELECT distinct a.id, a.title, a.start_date, a.end_date, a.place, c.title as place_desc 
                            FROM event_calendar a 
							INNER JOIN event_calendar_date b ON (a.id = b.event_id) 
                            INNER JOIN puti_places c ON (a.place = c.id) 
							WHERE a.deleted <> 1 AND a.status = 2 AND
                                  b.deleted <> 1 AND b.status = 1 AND
                                  a.site IN " . $admin_user["sites"] . " AND
                                  a.branch IN " . $admin_user["branchs"] . " 
                            ORDER BY a.start_date ASC";
            $result = $db->query($query);
            $first = true;
            echo '<option value="-1"></option>';
            while( $row = $db->fetch($result) ) {
                $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                if( $first ) {
                    $first = false;
                    echo '<option value="' . $row["id"] . '" selected>' . cTYPE::gstr($row["title"]) . " [" . $date_str . '] - ' . $words["event_place"] . ' : ' .  $words[strtolower($row["place_desc"])] . '</option>';
                } else {
                    echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($row["title"]) . " [" . $date_str . '] - ' . $words["event_place"] . ' : ' .  $words[strtolower($row["place_desc"])] . '</option>';
                }
            }
        ?>
        </select>
        <span style="margin-left:20px; vertical-align:middle;"><?php echo $words["id number"]?> : </span>
        <input style="width:100px; vertical-align:middle; font-size:14px; font-weight:bold; color:red;" id="sch_idd" value="" /> 
    </div>
    <div style="background-color:#F3E5E0; min-height:260px;">
    	<div id="sch_result" style="padding:5px;">
        </div>
    </div>
	<div style="background-color:#A7CFF4;">
    	<div id="holder_list" style="padding:5px; min-height:420px;">
        </div>
    </div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
            <input type="hidden" id="enroll_member_id" name="enroll_member_id" value="" />
	        <span style="margin-left:5px;"><?php echo $words["name"];?> : </span>
	        <span id="enroll_name" style="font-size:14px; font-weight:bold;"></span>
			<br /><br />
        	<span style="font-weight:bold; margin-left:20px;"><?php echo $words["group"];?> :</span>
            <input type="text"  style="width:20px; text-align:center;" id="enroll_group" name="enroll_group" value="" />
        	<span style="font-weight:bold; margin-left:20px;"></span>
            <input type="checkbox" id="enroll_trial" name="enroll_trial" value="1" /><span style="font-weight:bold;"><?php echo $words["trial"];?></span>
            <br /><br />
            <center><input type="button" id="btn_enroll_submit" name="btn_enroll_submit" value="<?php echo $words["enroll"];?>" />
        </div>
	</div>
</div>

</body>
</html>