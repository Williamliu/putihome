<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="10,40";
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
		<title>Bodhi Meditation Volunteer Hour Input</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var jobs = [];

		$(function(){
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			words["add email success"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			400,
				minHH:			250,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			true
			});
			$("#diaglog_ss").lwhDiag({
				titleAlign:		"center",
				title:			words["volunteer search"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			400,
				minHH:			120,
				zIndex:			6666,
				btnMax:			false,
				resizable:		false,
				movable:		false,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			$("#diaglog_jobs").lwhDiag({
				titleAlign:		"center",
				title:			words["job content"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			320,
				minHH:			250,
				zIndex:			6666,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});


			$("#diaglog_detail").lwhDiag({
				titleAlign:		"center",
				title:			words["volunteer information"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			500,
				minHH:			370,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});

			$("#tabber_detail").lwhTabber();

			$("div#department_volunteer").lwhTabber({
				   button: false
			});
			
			$("#start_date").datepicker({ 
							  dateFormat: 'yy-mm-dd',  
							  showOn: "button",
							  buttonImage: "../theme/blue/image/icon/calendar.png",
							  buttonImageOnly: true  
						  });
			
			$("input.volunteer-del[pid][vid]").live("click", function(ev) {
				  var pid = $(this).attr("pid");
				  var vid = $(this).attr("vid");
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",

						  pid: 			pid,
						  vid:			vid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$("tr[pid='" + req.data.pid + "'][vid='" + req.data.vid + "']").remove();
							lresort(req.data.pid);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_delete.php"
				  });
			});

			$("input.volunteer-sav[pid][vid]").live("click", function(ev) {
				  $("#wait").loadShow();
				  var pid = $(this).attr("pid");
				  var vid = $(this).attr("vid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  pid:			pid,
						  vid:			vid,
						  job_id:		$("select.volunteer-job[pid='" + pid + "'][vid='" + vid + "']").val(),
						  purpose:		$("input.volunteer-work[pid='" + pid + "'][vid='" + vid + "']").val(),
						  work_date:	$("input.volunteer-date[pid='" + pid + "'][vid='" + vid + "']").val(),
						  work_hour:	$("input.volunteer-hour[pid='" + pid + "'][vid='" + vid + "']").val()		
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_qsave.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							clearSingle(req.data.pid, req.data.vid);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_qsave.php"
				  });
			});


			$("input.volunteer-save[pid]").live("click", function(ev) {
  				  $("#wait").loadShow();
				  var pid = $(this).attr("pid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  pid:			pid,
						  hour:			hourJSON(pid)		
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_fsave.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							clearDD(req.data.pid, req.data.vids);
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Saved successful."}); 
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_fsave.php"
				  });
			});

			$("input.volunteer-email[pid]").live("click", function(ev) {
				  $("#wait").loadShow();
				  var pid = $(this).attr("pid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"email",

						  pid:			pid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_email.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Saved successful."}); 
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_email.php"
				  });
			});

			$("input.volunteer-sign[pid]").live("click", function(ev) {
				  var pid = $(this).attr("pid");
				  output_excel(pid);
			});


			$("input.volunteer-add[pid]").live("click", function(ev){
					 var pid = $(this).attr("pid");
					 $("#diaglog_ss").diagShow({
						  diag_open: function() {
							  $("input#hid").val(pid);
							  $("input#search_value").val("");
							  $("input#search_value").focus();
						  },
						  diag_close: function() {
							  //$("input#event_id").val("");
						  }
					 }); 
			
			});
			
			$(".volunteer-mark").live("click", function(ev) {
				  $("#wait").loadShow();
				  var pid = $(this).attr("pid");
				  var vid = $(this).attr("vid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  pid:			pid,
						  vid: 	        vid,
						  status: 		$(this).is(":checked")?1:0
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_status.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							moveItem(req.data);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_status.php"
				  });
			});
			
			$(".btn-job[pid][jid]").live("click", function(ev) {
				  $("#wait").loadShow();
				  var pid = $(this).attr("pid");
				  var jid = $(this).attr("jid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  pid:			pid,
						  jid: 	        jid,
						  job_title:	$(".job-title[pid='" + pid + "'][jid='" + jid + "']").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_jobs_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  	errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  	return false;
						  } else {
								if( req.data.jid == "-1" ) $("#diaglog_jobs").diagHide();
								jobs = req.data.jobs;
								addToListDepart(req.data);
								var tmp_html = job_lists();
								$("#job_list").html(tmp_html);	
																
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_jobs_save.php"
				  });
			});
			
			
			$("#search_value").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					find_ajax();
				}
			});
		
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					depart_select();
				}
			});

		
		
			depart_select();
		// end of function	
		});
		
		function hourJSON(pid) {
			var pObj = [];
			$("input.volunteer-date[pid='" + pid + "']").each(function(idx1, el1) {
                var dObj = {};
				var pid = $(this).attr("pid");
				var vid = $(this).attr("vid");
				dObj.pid = pid;
				dObj.vid = vid;
				dObj.work_date 	= $(this).val();
				dObj.work_hour 	= parseFloat($("input.volunteer-hour[pid='" + pid + "'][vid='" + vid + "']").val());
				dObj.purpose 	= $("input.volunteer-work[pid='" + pid + "'][vid='" + vid + "']").val();
				dObj.job_id 	= $("select.volunteer-job[pid='" + pid + "'][vid='" + vid + "']").val();
				if( dObj.work_date != "" && dObj.work_hour > 0) {
					pObj[pObj.length] = dObj;
				}
			});
			return pObj;
		}

		function clearDD(pid, vids) {
			for(var idx in vids) {
				var vid = vids[idx];
				$("select.volunteer-job[pid='" + pid + "'][vid='" + vid + "']").val("");
				$("input.volunteer-work[pid='" + pid + "'][vid='" + vid + "']").val("");
				$("input.volunteer-date[pid='" + pid + "'][vid='" + vid + "']").val("");
				$("input.volunteer-hour[pid='" + pid + "'][vid='" + vid + "']").val("");
			}
		}

		function clearSingle(pid, vid) {
			$("select.volunteer-job[pid='" + pid + "'][vid='" + vid + "']").val("");
			$("input.volunteer-work[pid='" + pid + "'][vid='" + vid + "']").val("");
			$("input.volunteer-date[pid='" + pid + "'][vid='" + vid + "']").val("");
			$("input.volunteer-hour[pid='" + pid + "'][vid='" + vid + "']").val("");
		}
		
		
		function add_date() {
			$("input.volunteer-date[pid][vid]").each(function(idx, el){
				var curdate = new Date( $("#start_date").val() );
				if( !isNaN(curdate.getFullYear()) ) 
					$(this).val($("#start_date").val());
				else 
					$(this).val("");
			});
		}

		function add_work() {
			$("select.volunteer-job[pid][vid]").each(function(idx, el){
				$(this).val($("#job_id").val());
			});
				
			$("input.volunteer-work[pid][vid]").each(function(idx, el){
				$(this).val($("#work_content").val());
			});
		}
		
		function find_ajax() {
			$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: 	$("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",

					pid : 	$("input#hid").val(),
					member: $("input#search_value").val()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
					$("#wait").loadHide();
					alert("Error (puti_volunteer_hours_find.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
					$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						//errObj.set(req.errorCode, req.errorMessage, req.errorField);
						$("#diaglog_ss").diagHide();
						if( req.errorCode == 1 ) {
							  var pid = $("input#hid").val();
							  $("#diaglog_detail").diagShow({
									diag_open: function() {
										$("input#dharma_name").focus();
										if(req.data && req.data.member) {
											var regExp = /@/gi;
											if(regExp.test(req.data.member)) {
												  $("input#email").val(req.data.member);
											} else {
												  regExp = /^[0-9]/gi;
												  if(regExp.test(req.data.member)) {
												  	$("input#phone").val(req.data.member);
												  } else {
												  	$("input#dharma_name").val(req.data.member);
												  	$("input#cname").val(req.data.member);
												  }
											}
										}
									
									},
									diag_close: function() {
										$("input#hid").val("");
										register_form.reset();
									}
							  });
						} else {
							moveItem(req.data);
							$(".lwhDiag-content", "#diaglog_message").html(req.errorMessage);
							$("#diaglog_message").diagShow({title:"Submit Success"}); 
						}
						return false;
					} else {
						$("#diaglog_ss").diagHide(); 
						addToList(req.data);
					}
				},
				type: "post",
				url: "ajax/puti_volunteer_hours_find.php"
			});
		}
		
		function lresort(pid) {
			$("td.sn","tr[pid='" + pid + "'][vid]").each(function(idx, el){
				$(this).html( parseInt(idx) + 1) ;
			});
		}
	
		function moveItem(obj) {
			if(obj.status == 1) {
				if($("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "'][status='1']", "table.tabQuery-table[pid='" + obj.pid + "']").length<=0) {
					if( $("tr[pid='" + obj.pid + "'][vid][status='1']", "table.tabQuery-table[pid='" + obj.pid + "']").length>0) {
						$("tr[pid='" + obj.pid + "'][vid][status='1']:last", "table.tabQuery-table[pid='" + obj.pid + "']").after(
							$("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "']", "table.tabQuery-table[pid='" + obj.pid + "']")
						);
						$("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "']", "table.tabQuery-table[pid='" + obj.pid + "']").attr("status",1);
						$("input.volunteer-mark[pid='" + obj.pid + "'][vid='" + obj.vid + "']").attr("checked",true);
					} else {
						$("tr.head", "table.tabQuery-table[pid='" + obj.pid + "']").after(
							$("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "']", "table.tabQuery-table[pid='" + obj.pid + "']")
						);
						$("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "']", "table.tabQuery-table[pid='" + obj.pid + "']").attr("status",1);
						$("input.volunteer-mark[pid='" + obj.pid + "'][vid='" + obj.vid + "']").attr("checked",true);
					}
				}
			} else {
					$("tr[pid='" + obj.pid + "'][vid='-1']", "table.tabQuery-table[pid='" + obj.pid + "']").before(
						$("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "']", "table.tabQuery-table[pid='" + obj.pid + "']")
					);
					$("tr[pid='" + obj.pid + "'][vid='" + obj.vid + "']", "table.tabQuery-table[pid='" + obj.pid + "']").attr("status",0);
					$("input.volunteer-mark[pid='" + obj.pid + "'][vid='" + obj.vid + "']").attr("checked",false);
			}
			lresort(obj.pid);
		}
		
		function addToList(obj) {
			var html = '';
			var sn = $("tr[pid='" + obj.pid + "'][vid]").length;
			html += '<tr pid="' + obj.pid + '" vid="' + obj.vid + '"  status="' + obj.status + '">';
			html += '<td class="sn" align="center">' + parseInt(sn) + '</td>';
			html += '<td>' + obj.cname + '</td>';
			html += '<td>' + obj.en_name + '</td>';
			html += '<td>' + obj.dharma_name + '</td>';
			html += '<td><input class="volunteer-mark" pid="' + obj.pid + '" vid="' + obj.vid + '" type="checkbox" value="1" ' + (obj.status==1?'checked="checked"':'') + ' /></td>';
			//html += '<td><input class="volunteer-work" pid="' + obj.pid + '" vid="' + obj.vid + '" style="width:100px;" value="' + $("#work_content").val() + '" /></td>';
		    html += '<td>';
			html += vol_job_lists(obj.pid, obj.vid, $("#job_id").val());
		    html += '-<input class="volunteer-work" pid="' + obj.pid + '" vid="' + obj.vid + '" style="width:100px;" value="' + $("#work_content").val() + '" />';
			html += '</td>';

			html += '<td><input class="volunteer-date" pid="' + obj.pid + '" vid="' + obj.vid + '" style="width:100px;" value="' + $("#start_date").val() + '" /></td>';
			html += '<td><input class="volunteer-hour" pid="' + obj.pid + '" vid="' + obj.vid + '" style="width:40px;text-align:center" value="" /></td>';
			html += '<td>';
			html += '<input class="volunteer-sav"  pid="' + obj.pid + '" vid="' + obj.vid + '"  type="button" value="' + words["button save"]+ '" />';
			html += '<input class="volunteer-del"  pid="' + obj.pid + '" vid="' + obj.vid + '"  type="button" value="' + words["del."]+ '" />';
			html += '</td>';
			html += '</tr>';
			if($("tr[pid='" + obj.pid + "'][vid][status='1']", "table.tabQuery-table[pid='" + obj.pid + "']").length>0) {
				$("tr[pid='" + obj.pid + "'][vid][status='1']:last", "table.tabQuery-table[pid='" + obj.pid + "']").after(html);
			} else {
				$("tr.head", "table.tabQuery-table[pid='" + obj.pid + "']").after(html);
			}
			lresort(obj.pid);
		}
		
		 
		function addToListDepart(obj) {
			var	  html = '<table class="tabQuery-table" pid="' + obj.pid + '">';
				  html += '<tr class="head">';
				  html += '<td class="tabQuery-table-header">' + words["sn"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["c.name"] + '</td>';
				  //html += '<td class="tabQuery-table-header">拼音名</td>';
				  html += '<td class="tabQuery-table-header">' + words["e.name"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["dharma"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["mark"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["work for"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["work date"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["hours"] + '</td>';
				  html += '<td class="tabQuery-table-header"><input class="volunteer-add" right="add"  type="button" pid="' + obj.pid + '" value="' + words["button add"] + '" /></td>';
				  html += '</tr>';
				  for(var idx in obj.vols) {
					  var vObj = obj.vols[idx];
					  html += '<tr pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" status="' + vObj.status + '">';
					  html += '<td class="sn" align="center">' + (parseInt(idx) + 1) + '</td>';
					  html += '<td>' + vObj.cname + '</td>';
					  //html += '<td>' . $row1["pname"] . '</td>';
					  html += '<td>' + vObj.en_name + '</td>';
					  html += '<td>' + vObj.dharma_name + '</td>';
					  html += '<td>';
					  html += '<input class="volunteer-mark" pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" type="checkbox" value="1" ' + (vObj.status==1?'checked="checked"':'') + ' />';
					  html += '</td>';
					  html += '<td>';
					  html += vol_job_lists(vObj.department_id,vObj.volunteer_id, $("#job_id").val());
 					  html += '-<input class="volunteer-work" pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" style="width:100px;" value="' + $("#work_content").val() + '" />';
					  html += '</td>';

					  html += '<td><input class="volunteer-date" pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" style="width:100px;" value="' + $("#start_date").val() + '" /></td>';
					  html += '<td><input class="volunteer-hour" pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" style="width:40px;text-align:center" value="" /></td>';
					  html += '<td>';
					  html += '<input class="volunteer-sav"  pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" right="save" type="button" value="' + words["button save"] + '" />';
					  html += '<input class="volunteer-del"  pid="' + vObj.department_id + '" vid="' + vObj.volunteer_id + '" right="delete" type="button" value="' + words["del."] + '" />';
					  html += '</td>';
					  html += '</tr>';
				  }
				  html += '<tr pid="' + obj.pid + '" vid="-1">';
				  html += '<td colspan="8"></td>';
				  html += '<td align="center"><input class="volunteer-add" right="add"  type="button" pid="' + obj.pid + '" value="' + words["button add"] + '" /></td>';
				  html += '</tr>';
				  html += '<tr pid="' + obj.pid + '">';
				  html += '<td colspan="9" align="center">';
				  html += '<input class="volunteer-save" right="save"  type="button" pid="' + obj.pid + '" value="' + words["button save all"] + '" />';
				  html += '<input class="volunteer-email" right="save"  type="button" pid="' + obj.pid + '" value="' + words["email pool"] + '" />';
				  html += '<input class="volunteer-sign" right="save"  type="button" pid="' + obj.pid + '" value="' + words["output sign"] + '" />';
				  html += '</td>';
				  html += '</tr>';
			  
				  html += '</table>';
				  $("span#depart_title").html(obj.title);
				  $("div#depart-content").html(html);
				
		}

		function  addToJobs(obj) {
			var	  html = '<table class="tabQuery-table" pid="' + obj.pid + '">';
				  html += '<tr class="head">';
				  html += '<td class="tabQuery-table-header">' + words["sn"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["job content"] + '</td>';
				  html += '<td class="tabQuery-table-header"></td>';
				  html += '</tr>';
				  if(obj.jobs.length > 0 ) {
					  for(var idx in obj.jobs) {
						  var vObj = obj.jobs[idx];
						  html += '<tr pid="' + vObj.department_id + '" jid="' + vObj.job_id + '">';
						  html += '<td class="sn" align="center"  	pid="' + vObj.department_id + '" jid="' + vObj.job_id + '">' + vObj.job_id + '</td>';
						  html += '<td><input class="job-title"   	pid="' + vObj.department_id + '" jid="' + vObj.job_id + '" style="width:150px;" value="' + vObj.job_title + '" /></td>';
						  html += '<td><input type="button" class="btn-job"   	pid="' + vObj.department_id + '" jid="' + vObj.job_id + '" value="' + words["button save"] + '" /></td>';
						  html += '</tr>';
					  }
				  }
				  html += '<tr pid="' + obj.pid + '" vid="-1">';
				  html += '<td class="sn" align="center"  pid="' + obj.pid + '" jid="-1">New</td>';
				  html += '<td><input class="job-title"   pid="' + obj.pid + '" jid="-1" style="width:150px;" value="" /></td>';
				  html += '<td><input type="button" class="btn-job"     pid="' + obj.pid + '" jid="-1" value="' + words["button add"] + '" /></td>';
				  html += '</td>';
				  html += '</tr>';
			  
				  html += '</table>';
			$("div#job_content").html(html);
				
		}

		function output_excel(pid) {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	

						$("input[name='pid']", "form[name='frm_list_excel']").val(pid);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_volunteer_hours_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="pid" value="' + pid + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function save_ajax() {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						  
						  pid:			$("input#hid").val(),	
						  cname: 		$("input#cname").val(),
						  pname: 		$("input#pname").val(),
						  en_name: 		$("input#en_name").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  gender:		$("input:radio[name='gender']:checked").val(),
						  email: 		$("input#email").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  city: 		$("input#city").val(),
						  status: 		$("select#status").val(),
						  depart:		$("#department").val() //$("input:checkbox.department:checked").map(function(){ return $(this).val();}).get().join(",")
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_member_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$("#diaglog_detail").diagHide();
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Submit Success"}); 
							addToList(req.data);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_member_save.php"
				  });
		}
		
		function depart_select() {
				  $("#wait").loadShow();
				  var pid = $("#department").val();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  pid:			pid,
						  sch_name:		$("#sch_name").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_depart.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							jobs = req.data.jobs;
							addToListDepart(req.data);
							var tmp_html = job_lists();
							$("#job_list").html(tmp_html);
							//$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							//$("#diaglog_message").diagShow({title:"Saved successful."}); 
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_depart.php"
				  });

		}

		function uncheck_ajax() {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  pid:			$("#department").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_mark.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							depart_select(req.data.pid);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_mark.php"
				  });
		}
		
		function job_lists(jval) {
			var html = '<select id="job_id" name="job_id" style="min-width:120px;">';
			html += '<option value="0"></option>';
			for(var key in jobs) {
				html += '<option ' + (jobs[key]["job_id"]==jval?'checked':'') + ' value="' + jobs[key]["job_id"] + '">' + jobs[key]["job_title"] + '</option>';
			}
			html +='</select>';
			return html;
		}


		function vol_job_lists(pid, vid, jval) {
			var html = '<select class="volunteer-job" pid="' + pid + '" vid="' + vid + '" style="width:100px;">';
			html += '<option value="0"></option>';
			for(var key in jobs) {
				html += '<option ' + (jobs[key]["job_id"]==jval?'selected':'') + ' value="' + jobs[key]["job_id"] + '">' + jobs[key]["job_title"] + '</option>';
			}
			html +='</select>';
			return html;
		}

		function add_job() {
			if( $("#department").val()>0 ) {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",

						  pid: 			$("#department").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_hours_jobs.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
								addToJobs(req.data);
								$("#diaglog_jobs").diagShow();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_hours_jobs.php"
				  });
				
				
				
			}
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="padding:10px;">
    <table>
    	<tr>
        	<td align="right"><?php echo $words["select department"]?>: </td>
            <td><select id="department" style="min-width:200px;" onchange="depart_select()">
		  		<?php
                  ob_start();
				  $depart = "(-1)";
				  if($admin_user["department"] != "") $depart = "(" . $admin_user["department"] . ")";
                  $result = $db->query("SELECT id, title, en_title, description, status FROM puti_department WHERE deleted <> 1 AND status = 1 AND id in $depart ORDER BY sn DESC, title");
                  echo '<option value="-1"></option>';
                  $cnt=0;
                  while( $row = $db->fetch($result) ) {
					  $cnt++;
                      if( $cnt == 1 ) 
					  	echo '<option value="' . $row["id"] . '" selected>' . $cnt . '. ' .  ($admin_user["lang"]!="en"?cTYPE::gstr($row["title"]):cTYPE::gstr($row["en_title"])) . '</option>';
					  else
					  	echo '<option value="' . $row["id"] . '">' . $cnt . '. ' .  ($admin_user["lang"]!="en"?cTYPE::gstr($row["title"]):cTYPE::gstr($row["en_title"])) . '</option>';
                  }
                  ob_end_flush();
              	?>
				</select>

                <span style="margin-left:100px;"></span>
                <input type="button" id="btn_uncheck" onclick="uncheck_ajax()" value="<?php echo $words["remove marks"]?>" />
        	</td>
     	</tr>
        <tr>
        	<td align="right"><?php echo $words["work for"]?>: </td>
            <td><span id="job_list">
            		<select id="job_id" name="job_id" style="min-width:120px;">
                    	<option value=""></option>
                    </select>
            	</span>-
            	<input id="work_content" type="text" style="width:100px;" value="" />
                <input type="button" right="save" id="btn_job" 	name="btn_job" 	onclick="add_job()" style="margin-left:5px;" value="<?php echo $words["button add"]?>" />
    			<input type="button" right="save" id="btn_work" name="btn_work" onclick="add_work()" style="margin-left:5px;" value="<?php echo $words["copy content"]?>" />
    			<span style="margin-left:30px;"></span>
                <?php echo $words["work date"]?>:
                <input id="start_date" type="text" style="width:100px;" value="<?php echo date("Y-m-d");?>" />
                <input type="button" right="save" id="btn_date" name="btn_date" onclick="add_date()" style="margin-left:5px;" value="<?php echo $words["copy work date"]?>" />
            </td>
       </tr>
      <tr>
      <td colspan="2"><br /></td>
      </tr>
        <tr>
          <td align="right"><?php echo $words["name"]?>: </td>
          <td><input oper="search" style="width:120px;" id="sch_name" value="" />
          <input type="button" right="view" id="btn_search" name="btn_search" onclick="depart_select();" style="margin-left:5px;" value="<?php echo $words["search"]?>" />
          <!--t
		  <?php echo $words["phone"]?>: 
          <input oper="search" style="width:120px;" id="sch_phone" value="" />
          -->
          </td>
        </tr>
   </table>
    <!------------------------------------------------------------>
	<br />
	<div id="department_volunteer" class="lwhTabber lwhTabber-sea">
		<div class="lwhTabber-header">
				<a><span id="depart_title"><?php echo $words["selected department"]?></span><s></s></a>
					<div class="line"></div>
		</div>
		<div class="lwhTabber-content">
			<div class="depart"  id="depart-content" style="min-height:320px;">
			</div>
		</div>
    </div>
	<!------------------------------------------------------------>
	</div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_ss" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<span style="color:red;"><?php echo $words["volunteer search"]?></span>
        <br /><br />
        <?php echo $words["name|email|phone|cell"]?>: <input class="form-input" id="search_value" value="" /><br />
        <input type="hidden" id="hid" value="" />
        <br />
        <center><input type="button" right="view" onclick="find_ajax()" value="<?php echo $words["find"]?>" /></center> 
	</div>
</div>

<form name="register_form">
<div id="diaglog_detail" class="lwhDiag" style="z-index:888;">
	<div class="lwhDiag-content lwhDiag-no-border">
          <div id="tabber_detail" class="lwhTabber lwhTabber-mint" style="width:480px;">
              <div class="lwhTabber-header">
                  <a><?php echo $words["personal information"]?><s></s></a>
                  <a><?php echo $words["belong department"]?><s></s></a>
                  <div class="line"></div>    
              </div>
              <div class="lwhTabber-content" style="height:300px; border-width:3px;">
                  <div>
					<!------------------------------------------------------------------>
                            <table cellpadding="2" cellspacing="0" width="100%">
                                <tr>
                                     <td class="title"><?php echo $words["dharma name"]?>: </td>
                                     <td>
                                        <input class="form-input" id="dharma_name" name="dharma_name" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["chinese name"]?>: </td>
                                     <td>
                                       	<input type="hidden" id="hid" name="hid" value="" />
                                        <input class="form-input" id="cname" name="cname" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["pinyin"]?>: </td>
                                     <td>
                                        <input class="form-input" id="pname" name="pname" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["english name"]?>: </td>
                                     <td>
                                        <input class="form-input" id="en_name" name="en_name" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["gender"]?>: </td>
                                     <td>
                                        <input type="radio" id="gender_male" name="gender" value="Male" /><label for="gender_male">Male</label> 
                                        <input type="radio" id="gender_female" name="gender" value="Female" /><label for="gender_female">Female</label>
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title line"><?php echo $words["email"]?>: </td>
                                     <td class="line">
                                        <input class="form-input" id="email" name="email" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["phone"]?>: </td>
                                     <td>
                                        <input class="form-input" id="phone" name="phone" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["cell"]?>: </td>
                                     <td>
                                        <input class="form-input" id="cell" name="cell" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["city"]?>: </td>
                                     <td>
                                        <input class="form-input" id="city" name="city" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["status"]?>: </td>
                                     <td>
                                        <select id="status" name="status">
                                            <option value=""></option>
                                            <option value="0"><?php echo $words["inactive"]?></option>
                                            <option value="1" selected><?php echo $words["active"]?></option>
                                        </select>
                                        <span class="required">*</span>
                                     </td>
                                </tr>
                            </table>                    
					<!------------------------------------------------------------------>
                  </div>
                  <div>
					<!------------------------------------------------------------------>
                    <?php echo $words["belong department"]?>: <br />
                    <div style="border:1px solid #cccccc; padding:5px;">
                        <?php
                            $result = $db->query("SELECT id, title FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
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
                                $html .= '<input type="checkbox" id="depart_' . $row["id"] . '" class="department" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  $row["title"] . '</label>';
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
                  </div>
              </div>
              <center><input type="button" right="save" id="btn_detail_save" onclick="save_ajax()" value="<?php echo $words["button save"]?>" /></center>
          </div> <!-- end of "lwhTabber" -->
	</div>
</div>
</form>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>


<div id="diaglog_jobs" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="job_content">
        </div>
	</div>
</div>


</body>
</html>