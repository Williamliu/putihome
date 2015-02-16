<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
include_once($CFG["include_path"] . "/config/basic_info.php");
$admin_menu="0,90";
include_once("website_admin_auth.php");
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
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
		<link rel="icon" type="image/gif" href="../bodhi.gif" />
		<title>Bodhi Meditation Student Group</title>

		<?php include("admin_head_link.php"); ?>

		<link href="../jquery/min/cleditor/jquery.cleditor.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../jquery/min/cleditor/jquery.cleditor.min.js"></script>
        
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

		<script type="text/javascript" 	src="js/event_calendar_group.js"></script>

		<script type="text/javascript" 	src="../jquery/min/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.zoom.js"></script>
		<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.zoom.css" rel="stylesheet" />
		
        <script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.upload.js"></script>
        <link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />


    	<script type="text/javascript" language="javascript">
		var htmlDesc = null;
		var htmlObj = new LWH.cHTML();
		var ungroupObj = null;
		var allObj = null;
		var cancelObj = null;
		var aj = null;

		$(function(){
			  htmlDesc =  $("#content").cleditor({width:"95%",height:260})[0];
			  $("#event_group_tabber").lwhTabber();
			  $("#diaglog_detail").lwhDiag({
				  titleAlign:		"center",
				  title:			words["send email"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			600,
				  minHH:			360,
				  btnMax:			false,
				  resizable:		false,
				  movable:			true,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });


			  $("#diaglog_nametag").lwhDiag({
				  titleAlign:		"center",
				  title:			words["nametag template select"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			320,
				  minHH:			210,
				  btnMax:			false,
				  resizable:		false,
				  movable:			false,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });
			  
			  $("#nametag_print").bind("click", function(ev) {
				  	$("#diaglog_nametag").diagHide();
					label_print();
			  });
			  
			  $("#diaglog_message").lwhDiag({
				  titleAlign:		"center",
				  title:			words["member enroll success"],
				  
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

			  $("#btn_email").live("click", function(ev) {
					  $("#diaglog_detail").diagShow({
							diag_open: function() {
								htmlDesc.refresh();
							}
					  });	
			   });

			  $("#sch_date").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: 	"button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
			  });
			   

		});
		
		function event_select_ajax() {
				if( $("select#event_id").val() != "" ) {
					group_list_ajax();
					ungroupObj.start();
					allObj.start();
					stat_ajax();
					cancelObj.start();
				} else {
					$("#event_group_list").html("");
					$("#event_student").html("");
					$("#all_student").html("");
					$("#stat_content").html("");
					$("#student_cancel").html("");
				}
		}


		function email_ajax() {
			  var yes = false;
			  yes = window.confirm(words["are you sure send email"]);
			  if(!yes) return;
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: $("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"email",
						
					  event_id: $("select#event_id").val(),
					  subject: 	$("#subject").val(),
					  content: 	$("#content").val(),
					  identity: $("#identity").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
		  			  $("#wait").loadHide();
					  alert("Error (event_calendar_group_email.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
		  			  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
							$("#diaglog_detail").diagHide();
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Send successful."}); 
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_email.php"
			  });
		}


		// list 		
		$(function(){
			ungroupObj = new LWH.cTABLE({
										  condition: {event_id:"#event_id"},
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:20},
											  {title: words["group"], 		col:"group"},
											  {title:"", 					col:""},
											  {title: words["name"], 		col:"aname", 		sq:"ASC"},
											  {title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
											  {title: "", 					col:"gender", 		sq:"ASC"},
											  {title: words["new people"], 	col:"new_flag",		sq:"DESC",    align:"center"},
											  {title: words["age"], 		col:"age", 			sq:"ASC"},
											  {title: words["phone"], 		col:"phone",		sq:"ASC"},
											  {title: words["city"], 		col:"city", 		sq:"ASC", align:"center"},
											  {title: words["short.lang"], 	col:"language", 	sq:"ASC", align:"center"},
											  {title: words["g.site"], 		col:"site", 		sq:"ASC", align:"center"}
										  ],
										  container: 	"#event_student",
										  me:			"ungroupObj",
		
										  url:		"ajax/event_calendar_group_ungroup.php",
										  orderBY: 	"a.created_time",
										  orderSQ: 	"ASC",
										  cache:		false,
										  expire:		3600,
										  
										  admin_sess: 	$("input#adminSession").val(),
										  admin_menu:	$("input#adminMenu").val(),
										  admin_oper:	"view",
										  
										  pageRows:     ungroupHTML
							  });

			allObj = new LWH.cTABLE({
										  condition: 	{
											  event_id:		"#event_id",											  
											  sch_name:		"#sch_name",
											  sch_phone:	"#sch_phone",
											  sch_email:	"#sch_email",
											  sch_gender:	"#sch_gender",
											  sch_online:	"#sch_online",
											  sch_attend:	"#sch_attend",
											  sch_level:	"#sch_level",
											  sch_onsite:	"#sch_onsite",
											  sch_trial:	"#sch_trial",
											  sch_new_flag:	"#sch_new_flag",
											  sch_lang:		"#sch_lang",
											  sch_status:	"#sch_status",
											  sch_group:	"#sch_group",
											  sch_idd:		"#sch_idd",
											  sch_date:		"#sch_date",
											  sch_city:		"#sch_city"
										  },
										  
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:20},
											  {title: words["grp"],			col:"group_no",     sq:"ASC", 	align:"center"},
											  {title: words["g.leader"], 	col:"leader",     	sq:"DESC", 	align:"center"},
											  {title: words["g.volunteer"],	col:"volunteer",    sq:"DESC", 	align:"center"},
											  {title: words["trial"],		col:"trial",    	sq:"ASC", 	align:"center"},
											  {title: words["id card"], 	col:"idd"},
											  {title:"", 					col:""},
											  {title: words["name"], 		col:"aname", 		sq:"ASC"},
											  {title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
											  {title: words["age"], 		col:"age", 			sq:"ASC"},
											  {title: "", 					col:"gender", 		sq:"ASC"},
											  {title: words["new people"], 	col:"new_flag",		sq:"DESC",   align:"center"},
											  //{title: words["phone"], 		col:"phone"},
											  {title: words["city"], 		col:"city",  		sq:"ASC", 	align:"center"},
											  {title: words["short.lang"], 	col:"language", 	sq:"ASC", 	align:"center"},
											  {title: words["g.site"], 		col:"site",  		sq:"ASC", 	align:"center"},
											  {title: words["paid"], 		col:"paid",     	sq:"DESC", 	align:"center"},
											  {title: words["reg.date"], 	col:"created_time", sq:"DESC", 	align:"center"}
										  ],
										  container: 	"#all_student",
										  me:			"allObj",
		
										  url:		"ajax/event_calendar_group_allstu.php",
										  orderBY: 	"a.created_time",
										  orderSQ: 	"ASC",
										  cache:		false,
										  expire:		3600,
										  
										  admin_sess: 	$("input#adminSession").val(),
										  admin_menu:	$("input#adminMenu").val(),
										  admin_oper:	"view",
										  
										  pageRows:     allHTML
							  });


			cancelObj = new LWH.cTABLE({
										 condition: {event_id:"#event_id"},
										 headers:[
											  {title: words["sn"], 			col:"rowno",		width:20},
											  {title: words["name"], 		col:"aname", 		sq:"ASC"},
											  {title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
											  {title: words["age"], 		col:"age", 			sq:"ASC"},
											  {title: words["gender"], 		col:"gender", 		sq:"ASC"},
											  {title: words["new people"], 	col:"new_flag",		sq:"DESC",    align:"center"},
											  {title: words["phone"], 		col:"phone"},
											  {title: words["city"], 		col:"city",  		sq:"ASC", 	align:"center"},
											  {title: words["short.lang"], 	col:"language", 	sq:"ASC", align:"center"},
											  {title: words["g.site"], 		col:"site",  		sq:"ASC", 	align:"center"},
											  {title: words["web"], 		col:"online",     	sq:"DESC", 	align:"center"},
											  {title: words["paid"], 		col:"paid",     	sq:"DESC", 	align:"center"},
											  {title: words["p.date"], 		col:"paid_date",    sq:"DESC", 	align:"center"},
											  {title: words["id card"], 	col:"idd",     		sq:"ASC"},
											  {title: "", 					col:""}
										  ],
										  container: 	"#student_cancel",
										  me:			"cancelObj",
		
										  url:		"ajax/event_calendar_group_cancel.php",
										  orderBY: 	"a.created_time",
										  orderSQ: 	"ASC",
										  cache:		false,
										  expire:		3600,
										  
										  admin_sess: 	$("input#adminSession").val(),
										  admin_menu:	$("input#adminMenu").val(),
										  admin_oper:	"view",
										  
										  pageRows:     cancelHTML
							  });

		
		
			$("input#sch_idd, :input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					allstudent_search_ajax();
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

			// event grouping save function 
			$("#btn_event_group_save").die("click").live("click", function(ev) {
	  			$("#wait").loadShow();
				var earr = [];
				$("input.event-group-no[enroll_id]").each(function(idx0, el0) {
                   	if($(el0).val() != "") {
						earr[idx0] = $(this).attr("enroll_id") + ":" + $(el0).val();
					}
				});
				
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"save",

						event_id: 	$("select#event_id").val(),
						member_grp:	earr
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							grouplistHTML(req.data.group);
							//group_list_ajax();
							ungroupObj.fresh();
							allObj.fresh();
							//stat_ajax();
							//cancelObj.fresh();
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_save.php"
				});
			});
			
			// delete group student and return to ungroup list
			$("a[oper='group_item_delete']", "#event_group_list").die("click").live("click", function(ev) {
	  			$("#wait").loadShow();
				var enroll_id = $(this).attr("enroll_id");
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"delete",

						event_id: 	$("select#event_id").val(),
						enroll_id: 	enroll_id	
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_remove.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							$("li[enroll_id='" + req.data.enroll_id + "']", "#event_group_list").remove();
							$("span.group-number[group_id='" + req.data.group_id + "']").html( parseInt($("span.group-number[group_id='" + req.data.group_id + "']").html()) -1 );
							//group_list_ajax();
							ungroupObj.fresh();
							allObj.fresh();
							//stat_ajax();
							//cancelObj.fresh();
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_remove.php"
				});
			});
			
			
			// delete ungroup student to cancel list
			$("a[oper='delete']", "#event_student").live("click", function(ev) {
	  			$("#wait").loadShow();
				var enroll_id = $(this).attr("enroll_id");
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"delete",

						event_id: 	$("select#event_id").val(),
						enroll_id: 	enroll_id	
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							$("tr[enroll_id='" + req.data.enroll_id + "']", "#event_student").remove();
							//group_list_ajax();
							ungroupObj.fresh();
							allObj.fresh();
							stat_ajax();
							cancelObj.fresh();
							
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_delete.php"
				});
			});
			
			// delete all student to cancel list
			$("a[oper='delete']", "#all_student").live("click", function(ev) {
	  			$("#wait").loadShow();
				var enroll_id = $(this).attr("enroll_id");
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"delete",

						event_id: 	$("select#event_id").val(),
						enroll_id: 	enroll_id	
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							$("tr[enroll_id='" + req.data.enroll_id + "']", "#all_student").remove();
							//group_list_ajax();
							ungroupObj.fresh();
							allObj.fresh();
							stat_ajax();
							cancelObj.fresh();
							
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_delete.php"
				});
			});

			
			
			// add cancelled student back to event ungroup student
			$("a[oper='add']", "#student_cancel").live("click", function(ev) {
	  			$("#wait").loadShow();
				var enroll_id = $(this).attr("enroll_id");
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"delete",

						event_id:  $("select#event_id").val(),
						enroll_id: enroll_id	
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_add.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							$("tr[enroll_id='" + req.data.enroll_id + "']", "#student_cancel").remove();
							//group_list_ajax();
							ungroupObj.fresh();
							allObj.fresh();
							stat_ajax();
							cancelObj.fresh();
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_add.php"
				});
			});

			//  ID Card Number batch save
			$("#btn_idd_save").die("click").live("click", function(ev) {
	  			$("#wait").loadShow();
				var earr = [];
				$("input.student[sid]").each(function(idx, el0) {
						var  sobj = {};
						sobj.member_id 	= $(this).attr("sid"); 
						sobj.idd 		= $.trim($(this).val());
						sobj.leader		= $("input.leader[sid='" + sobj.member_id + "']").is(":checked")?1:0;
						sobj.volunteer	= $("input.volunteer[sid='" + sobj.member_id + "']").is(":checked")?1:0;
						sobj.trial		= $("input.trial[sid='" + sobj.member_id + "']").is(":checked")?1:0;
						sobj.group_no	= $("input.group_no[sid='" + sobj.member_id + "']").val();
						
						earr[earr.length] = sobj;
                });

				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"save",
						
						event_id: 	$("select#event_id").val(),						
						idds:		earr
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_idd.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							group_list_ajax();
							ungroupObj.fresh();
							allObj.fresh();
							stat_ajax();
							//cancelObj.start();
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_idd.php"
				});
			});
		
			// view member detail infomation
			$(".tabQuery-button[oper='view']", "#all_student").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});
			$(".tabQuery-button[oper='view']", "#event_student").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});
			
			// output signature form
			$("a.tabQuery-button-output", "#all_student").live("click", function(ev){
				  var eid = $("select#event_id").val();
				  var mid = $(this).attr("rid");
				  print_signature(eid,mid);
			});	
		
			// ID Card number save to single member
			$(".tabQuery-button-save[rid]", "#all_student").live("click", function(ev) {
	  			$("#wait").loadShow();
				var mid = $(this).attr("rid");
				var idd = $("input.student[sid='" + mid + "']").val();
				var led = $("input.leader[sid='" + mid + "']").is(":checked")?1:0;
				var vol = $("input.volunteer[sid='" + mid + "']").is(":checked")?1:0;
				var trl = $("input.trial[sid='" + mid + "']").is(":checked")?1:0;
				var gno = $("input.group_no[sid='" + mid + "']").val();
				$.ajax({
					data: {
						admin_sess: $("input#adminSession").val(),
						admin_menu:	$("input#adminMenu").val(),
						admin_oper:	"save",
						
						event_id: 	$("select#event_id").val(),						
						idd: 		idd,
						leader:    	led,
						volunteer: 	vol,
						trial:		trl,
						group_no:	gno,
						member_id: 	mid		
					},
					dataType: "json",  
					error: function(xhr, tStatus, errorTh ) {
			  			$("#wait").loadHide();
						alert("Error (event_calendar_group_idd_single.php): " + xhr.responseText + "\nStatus: " + tStatus);
					},
					success: function(req, tStatus) {
			  			$("#wait").loadHide();
						if( req.errorCode > 0 ) { 
							errObj.set(req.errorCode, req.errorMessage, req.errorField);
							return false;
						} else {
							$("input.student[sid='" + req.data.member_id + "']").val(req.data.idd);

							group_list_ajax();
							ungroupObj.fresh();
							//allObj.fresh();
							stat_ajax();
							//cancelObj.fresh();
						}
					},
					type: "post",
					url: "ajax/event_calendar_group_idd_single.php"
				});
			});
			
			// ID Card input box focus 
			$("input.student").live("focus", function(ev) {
				$(this).select();
			});
			$("input.student").live("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					var next_el = parseInt($(this).attr("idx")) + 1;
					$("input.student[idx='" + next_el + "']").focus();
					$("input.student[idx='" + next_el + "']").select();
				}
			});
				
	
			$("#btn_print_empty").bind("click", function(ev) {
				print_signature($("select#event_id").val(), 0);
			});

			event_select_ajax();
		}); // end of $(function())
				

		function group_list_ajax() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: $("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"view",

					  event_id: 	$("#event_id").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_calendar_group_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						  grouplistHTML(req.data.group);
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_list.php"
			  });
		}


		function stat_ajax() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: $("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"view",

					  event_id: 	$("#event_id").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_calendar_group_stat.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						  $("#stat_content").html(req.data.html);
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_stat.php"
			  });
		}

		function grouplistHTML(grp) {
			$("#event_group_list").html("");
			var html = '';
			html += '<ul id="lwhT" class="lwhTree" style="margin-left:0px; padding-left:0px;">';
			var cnt = 0;
			for(var key in grp) {
				cnt++;
				html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
				var grp_str = '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;">' + words["group"] + ': ' + key + ' { <span class="group-number" group_id="' + key + '">' + grp[key].length + '</span> ' + words["people"] + ' }</span><a gid="' + key + '" class="group-attend" title="' +  words["group attandance"] + '"></a><a  gid="' + key + '" class="group-check" title="' +  words["group member confirm"] + '"></a><a  gid="' + key + '" class="group-labels" title="' +  words["group label print"] + '"></a>'; 
				html += grp_str;
				html += '<ul class="lwhTree">';
				var grp_sn = 0;
				for(var key1 in grp[key]) {
					grp_sn++;
					var gender_image = "node-img-user";
					switch(grp[key][key1].gender) {
						case "Male":
							gender_image = "node-img-male";
							break;
						case "Female":
							gender_image = "node-img-female";
							break;
						default:
							gender_image = "node-img-user";
							break;																	
					}
					if(grp[key][key1].volunteer=="1") 	gender_image = "node-img-volunteer1";
					if(grp[key][key1].leader=="1") 		gender_image = "node-img-leader";
					
					html += '<li class="node" enroll_id="' + grp[key][key1].enroll_id + '"><s class="node-line"></s><s class="node-img ' + gender_image + '"></s>';
					var node_title = 	words["name"] +": " +  grp[key][key1].name + "\n" +   
										words["city"] + ": " +  grp[key][key1].city + "\n" +  
										words["g.site"] + ": " +  words[grp[key][key1].site_desc.toLowerCase()] + "\n" +  
										words["phone"] + ": " + grp[key][key1].phone + "\n" +  
										words["cell"] + ":" +  grp[key][key1].cell;
					var mem_str = '<span class="title" style="color:#333333; width:170px;" title="' + node_title + '"><span style="display:inline-block;width:22px;">' + grp_sn + '. </span>' + grp[key][key1].name + '</span>'; 
					mem_str += '<a class="tabQuery-button tabQuery-button-delete-small" oper="group_item_delete"	right="delete" enroll_id="' + grp[key][key1].enroll_id + '" title="' +  words["remove"] + '" style="margin-left:3px;"></a>';
					mem_str += '<a class="tabQuery-button tabQuery-button-label-small" oper="group_item_pdf" 		right="print" enroll_id="' + grp[key][key1].enroll_id + '" title="' +  words["label print"] + '" style="margin-left:3px;"></a>';

					html += mem_str;
					html += '</li>';
				}
				html += '</ul>';
				html += '</li>';
			}
			$("#event_group_list").html(html);
			if(true) { //if(cnt>0) { 
				var btn_html = '<hr><center>';
				btn_html += '<input type="button" right="print" id="btn_groups_matrix"  value="' +  words["group matrix"] + '" />';
				btn_html += '<input type="button" right="print" id="btn_groups_list"  value="' +  words["group list"] + '" />';
				btn_html += '<input type="button" right="email" id="btn_email"  value="' +  words["email"] + '" />';
				btn_html += '</center>';
				$("#event_group_list").append(btn_html);
			}
			$("#lwhT").lwhTree({single:true});
		}
		
		function ungroupHTML(pageObj) {
				var html = '';
				var rowObj = pageObj.rows;
				for(var idx in rowObj) {
					html += '<tr rowno="' + idx + '" enroll_id="' + rowObj[idx]["enroll_id"] + '" rid="' + rowObj[idx]["member_id"] + '">';
					html += '<td align="center">';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td>';
					html +=  '<input class="event-group-no" enroll_id="' + rowObj[idx]["enroll_id"] + '" style="width:30px; text-align:center;" value="" />';
					html += '</td>';

					html += '</td>'
					html += '<td align="center" style="white-space:nowrap;">';
					html += '<a class="tabQuery-button tabQuery-button-view" 			oper="view" right="view" enroll_id="' + rowObj[idx]["enroll_id"] + '" rid="' + rowObj[idx]["member_id"] + '" title="' +  words["view details"] + '"></a>';					 
					html += '<a class="tabQuery-button  tabQuery-button-delete-small" 	oper="delete" 			right="delete" enroll_id="' + rowObj[idx]["enroll_id"] + '" title="' +  words["remove"] + '" style="margin-left:3px;"></a>';
					html += '<a class="tabQuery-button tabQuery-button-label-small" 	oper="group_item_pdf" 	right="print"   enroll_id="' + rowObj[idx]["enroll_id"] + '" title="' +  words["label print"] + '" style="margin-left:3px;"></a>';
					html += '</td>';

					var stitle = '';
					for(var key0 in rowObj[idx]["records"]) {
						stitle += (stitle!=""?"\n":"") + rowObj[idx]["records"][key0]["title"] + " : " + rowObj[idx]["records"][key0]["count"];
					}
					if(stitle == "") stitle = "No Experience";
					html += '<td>';
					html += '<span class="aname" class="ungroup_student_name" style="cursor:pointer;" title="' + stitle + '">';
					html += rowObj[idx]["aname"];
					html += '</span>';
					html += '</td>';

					html += '<td class="dharma_name">';
					html +=  rowObj[idx]["dharma_name"];
					html += '</td>';


					html += '<td class="gender" align="center">';
					var gender_css = ' gender-type-user';
					if(rowObj[idx]["gender"]=="Male") 	gender_css = ' gender-type-male';
					if(rowObj[idx]["gender"]=="Female") gender_css = ' gender-type-female';
					if(rowObj[idx]["volunteer"]=="1")	gender_css = ' gender-type-volunteer1';
					if(rowObj[idx]["leader"]=="1") 		gender_css = ' gender-type-leader';
					html +=  '<a class="gender-type' + gender_css + '"></a>';
					html += '</td>';

					html += '<td align="center">';
					html +=  rowObj[idx]["new"];
					html += '</td>';

	
					html += '<td align="center" class="age">';
					html +=  rowObj[idx]["age"];
					html += '</td>';

					/*
					html += '<td class="birth_yy">';
					html +=  rowObj[idx]["birth_yy"];
					html += '</td>';
					*/
					
					
					html += '<td class="phone">';
					html +=  rowObj[idx]["phone"];
					html += '</td>';
					
					
					html += '<td class="city">';
					html +=  rowObj[idx]["city"];
					html += '</td>';

					html += '<td class="language">';
					html +=  rowObj[idx]["language"];
					html += '</td>';


					html += '<td class="site">';
					html +=  words[rowObj[idx]["site_desc"].toLowerCase()];
					html += '</td>';

					html += '</tr>';
				}
				if(rowObj.length > 0) {
					  var html_button = '<center>';
					  html_button += '<input type="button" oper="save" right="save" id="btn_event_group_save" value="' +  words["button save"] + '" />';
					  html_button += '<input type="button" class="group-labels" oper="view" right="print" gid="0" value="' +  words["ungroup label print"] + '" />';
					  //html_button += '<input type="button" oper="view" right="print" id="btn_blank_label" gid="0" value="' +  words["blank label"] + '" />';
					  html_button += '</center>';
					  $("#event_student").append(html_button);	
				}
				return html;
		}
		
		function allHTML (pageObj) {
			var html = '';
				var rowObj = pageObj.rows;
				for(var idx in rowObj) {
					html += '<tr rowno="' + idx + '" rid="' + rowObj[idx]["member_id"] + '" enroll_id="'+ rowObj[idx]["enroll_id"] +'">';
					html += '<td align="center">';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td align="center">';
					html += '<input class="group_no" sid="' + rowObj[idx]["member_id"] + '" idx="' + idx + '" style="width:30px; font-size:16px; font-weight:bold; text-align:center;" value="' + rowObj[idx]["group_no"] + '" />';
					html += '</td>';

					html += '<td align="center">';
					html += '<input type="checkbox" class="leader" 		sid="' + rowObj[idx]["member_id"] + '" idx="' + idx + '" value="1" ' + (rowObj[idx]["leader"]=='1'?'checked':'') + ' />';
					html += '</td>';

					html += '<td align="center">';
					html += '<input type="checkbox" class="volunteer" 	sid="' + rowObj[idx]["member_id"] + '" idx="' + idx + '" value="1" ' + (rowObj[idx]["volunteer"]=='1'?'checked':'') + ' />';
					html += '</td>';

					html += '<td align="center">';
					html += '<input type="checkbox" class="trial" 		sid="' + rowObj[idx]["member_id"] + '" idx="' + idx + '" value="1" ' + (rowObj[idx]["trial"]=='1'?'checked':'') + ' />';
					html += '</td>';

					html += '<td align="center">';
					html += '<input class="student" sid="' + rowObj[idx]["member_id"] + '" idx="' + idx + '" style="width:80px;" value="' + rowObj[idx]["idd"] + '" />';
					html += '</td>';

					html += '<td align="center" style="white-space:nowrap;">';
					html += '<a class="tabQuery-button tabQuery-button-save" 	oper="save" right="save" 	rid="' +  rowObj[idx]["member_id"] + '" idd="' + rowObj[idx]["idd"] + '" title="' +  words["button save"] + '"></a>';					 
					html += '<a class="tabQuery-button tabQuery-button-output" 	oper="print" right="print" 	rid="' + rowObj[idx]["member_id"] + '" title="' +  words["print signature"] + '"></a>';
					html += '<a class="tabQuery-button tabQuery-button-view" 	oper="view" right="view" 	rid="' +  rowObj[idx]["member_id"] + '" title="' +  words["view details"] + '"></a>';					 
					html += '<a class="tabQuery-button  tabQuery-button-delete-small" oper="delete" right="delete" enroll_id="' + rowObj[idx]["enroll_id"] + '" title="' +  words["cancel enroll"] + '" style="margin-left:3px;"></a>';
					html += '</td>';

					var stitle = '';
					for(var key0 in rowObj[idx]["records"]) {
						stitle += (stitle!=""?"\n":"") + rowObj[idx]["records"][key0]["title"] + " : " + rowObj[idx]["records"][key0]["count"];
					}
					if(stitle == "") stitle = "No Experience";
					
					html += '<td style="white-space:nowrap;">';
					html += '<span class="aname" class="ungroup_student_name" style="cursor:pointer;" title="' + stitle + '">';
					html +=  rowObj[idx]["aname"];
					html += '</span>';
					html += '</td>';

					html += '<td class="dharma_name">';
					html +=  rowObj[idx]["dharma_name"];
					html += '</td>';
	
					html += '<td align="center" class="age">';
					html +=  rowObj[idx]["age"];
					html += '</td>';
					
					/*
					html += '<td class="birth_yy">';
					html +=  rowObj[idx]["birth_yy"];
					html += '</td>';
					*/
					
					html += '<td class="gender" align="center">';
					var gender_css = ' gender-type-user';
					if(rowObj[idx]["gender"]=="Male") 	gender_css = ' gender-type-male';
					if(rowObj[idx]["gender"]=="Female") gender_css = ' gender-type-female';
					html +=  '<a class="gender-type' + gender_css + '"></a>';
					html += '</td>';

					html += '<td align="center">';
					html +=  rowObj[idx]["new"];
					html += '</td>';
					
					/*
					html += '<td class="phone">';
					html +=  rowObj[idx]["phone"];
					html += '</td>';
					*/
					
					html += '<td class="city">';
					html +=  rowObj[idx]["city"];
					html += '</td>';

					html += '<td class="language">';
					html +=  rowObj[idx]["language"];
					html += '</td>';

					html += '<td class="site">';
					html +=  words[rowObj[idx]["site_desc"].toLowerCase()];
					html += '</td>';

					html += '<td align="center">';
					html +=  rowObj[idx]["paid"];
					html += '</td>';

					html += '<td style="white-space:nowrap;">';
					html +=  rowObj[idx]["created_time"];
					html += '</td>';


					html += '</tr>';
				}

				if(rowObj.length > 0) {
					  var html_button = '<center>';
					  html_button += '<input type="button" oper="save" right="save" id="btn_idd_save" value="' + words["button save all"] + '" />';
					  html_button += '<input type="button" oper="view" right="print" id="btn_all_label" value="' +  words["label print"] + '" />';
					  html_button += '<input type="button" oper="view" right="print" id="btn_student_sign" value="' +  words["student signature"] + '" />';
					  html_button += '</center>';
					  $("#all_student").append(html_button);	
				}
				
				return html;
		}
		
		function allstudent_search_ajax() {
			allObj.start();
		}
		
		function cancelHTML (pageObj) {
			var html = '';
				var rowObj = pageObj.rows;
				for(var idx in rowObj) {
					html += '<tr rowno="' + idx + '" enroll_id="' + rowObj[idx]["enroll_id"] + '" rid="' + rowObj[idx]["member_id"] + '">';
					html += '<td align="center">';
					html += parseInt(idx) + 1;
					html += '</td>';

					html += '<td class="aname">';
					html += rowObj[idx]["aname"];
					html += '</td>';

					html += '<td class="dharma_name">';
					html +=  rowObj[idx]["dharma_name"];
					html += '</td>';
	
					html += '<td class="age">';
					html +=  rowObj[idx]["age"];
					html += '</td>';

					html += '<td class="gender" align="center">';
					var gender_css = ' gender-type-user';
					if(rowObj[idx]["gender"]=="Male") 	gender_css = ' gender-type-male';
					if(rowObj[idx]["gender"]=="Female") gender_css = ' gender-type-female';
					html +=  '<a class="gender-type' + gender_css + '"></a>';
					html += '</td>';

                    /*
					html += '<td class="gender">';
					html +=  rowObj[idx]["gender"];
					html += '</td>';
                    */
					
                    html += '<td align="center">';
					html +=  rowObj[idx]["new"];
					html += '</td>';

					html += '<td class="phone">';
					html +=  rowObj[idx]["phone"];
					html += '</td>';

					html += '<td class="city">';
					html +=  rowObj[idx]["city"];
					html += '</td>';

					html += '<td class="language">';
					html +=  rowObj[idx]["language"];
					html += '</td>';

					html += '<td class="site">';
					html +=  words[rowObj[idx]["site_desc"].toLowerCase()];
					html += '</td>';

					html += '<td align="center">';
					html +=  rowObj[idx]["online"];
					html += '</td>';

					html += '<td align="center">';
					html +=  rowObj[idx]["paid"];
					html += '</td>';

					html += '<td>';
					html +=  rowObj[idx]["paid_date"];
					html += '</td>';

					html += '<td align="right">';
					html +=  rowObj[idx]["idd"];
					html += '</td>';

					html += '<td align="center">';
					html += '<a class="tabQuery-button  tabQuery-button-add-small" oper="add" right="save" enroll_id="' + rowObj[idx]["enroll_id"] + '" title="' +  words["enroll"] + '" style="margin-left:3px;"></a>';
					html += '</td>';

					html += '</tr>';
				}

				if(rowObj.length > 0) {
					  var html_button = '<br><center><input type="button" oper="email" right="email" onclick="add_email();" id="btn_add_email" value="' + words["email pool"] + '" /></center>';
					  $("#student_cancel").append(html_button);	
				}

				return html;
		}

		function add_email() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"email",
					  
					  event_id:		$("#event_id").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_calendar_group_cancel_email.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
						$("#diaglog_message").diagShow(); 
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_cancel_email.php"
			  });
		}

		function shoes_shelf_ajax() {
			  $("#wait").loadShow();
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"save",
					  
					  event_id:		$("#event_id").val()
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  $("#wait").loadHide();
					  alert("Error (event_calendar_group_shoes.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  $("#wait").loadHide();
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
						  tool_tips(words["shoes shelf number success"] + " : " + req.data.count);
					  }
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_shoes.php"
			  });
		}
		
		function idd_used( usedList ) {
			$("input.student").css("background-color", "white");
			for(var key in usedList) {
				$("input.student[sid='" + usedList[key] + "']").css("background-color", "yellow");
			}
		}

		function stat_output_ajax() {
			  $.ajax({
				  data: {
					  admin_sess: $("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"print",

					  event_id: 	$("#event_id").val()
				  },
				  dataType: "json",  
				  //contentType: "text/html; charset=utf-8",
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (event_calendar_group_stat.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  var w1 = window.open("output.html");
					  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Class Statistics</" + "title" + "></" + "head" + "><" + "body>";
					  w1.document.open();
					  w1.document.write(html_str);
					  w1.document.write(req.data.html);
					  w1.document.write('</html>');
					  w1.document.close();
					  w1.print();
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_stat.php"
			  });
		}
		
		function event_refresh() {
			group_list_ajax();
			ungroupObj.fresh();
			allObj.fresh();
			stat_ajax();
			cancelObj.fresh();
		}
    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
		 <span style="font-size:16px; font-weight:bold;"><?php echo $words["opened event"]?>: </span>&nbsp;
          <select id="event_id" style="font-size:16px; color:blue; min-width:300px;" onchange="event_select_ajax();">
          <?php 
              $fdate 	= mktime(0,0,0, date("m") ,date("d"), date("Y"));
  
              $query = "SELECT distinct a.id, a.title, a.start_date, a.end_date, c.title as site_desc 
			  				  FROM event_calendar a 
							  INNER JOIN event_calendar_date b ON (a.id = b.event_id)
                              INNER JOIN puti_sites c ON (a.site = c.id) 
                              WHERE a.deleted <> 1 AND a.status = 2 AND
                                    b.deleted <> 1 AND b.status = 1 AND 
									a.site IN " . $admin_user["sites"] . " AND
									a.branch IN " . $admin_user["branchs"] . " 
                              ORDER BY a.start_date ASC";
              $result = $db->query($query);
              $first = true;
			  echo '<option value=""></option>';
              while( $row = $db->fetch($result) ) {
				  $date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
                  if( $first ) {
					  $first = false;
	                  echo '<option value="' . $row["id"] . '" selected>' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  } else {
    	              echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
				  }
              }
          ?>
          </select>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btn_refresh" onclick="event_refresh()" value="<?php echo $words["button refresh"];?>" />
          <br /><br />
 
          <div id="event_group_tabber" class="lwhTabber lwhTabber-fuzzy" style="width:100%;">
              <div class="lwhTabber-header">
                  <a><?php echo $words["event groups"]?><s></s></a>
                  <a><?php echo $words["event students"]?><s></s></a>
                  <a><?php echo $words["statistics"]?><s></s></a>
                  <a><?php echo $words["students - cancel"]?><s></s></a>
                  <div class="line"></div>    
              </div>
              <div class="lwhTabber-content">
                  <div id="group_item" style="min-height:400px; overflow-x:hidden; overflow-y:auto;">
                      <table>
                          <tr>
                              <td valign="top" style="padding-right:10px;border-right:1px dotted #cccccc; min-width:270px;">
                                  <span style="font-size:18px; color:blue;"><?php echo $words["event groups"]?>:</span><br />
                                  <div id="event_group_list"></div>
                              </td>
                              <td valign="top" style="border-left:1px dotted #cccccc; padding-left:10px;">
                                   <div id="event_student"></div>
                              </td>
                          </tr>
                       </table>
                  </div><!-- end of <div id="group_item"> -->
                  <div id="group_all" style="min-height:400px; overflow-x:hidden; overflow-y:auto;">
                        
                        <fieldset>
                            <legend><?php echo $words["search filter"]?></legend>
                                <table border="0" cellpadding="0">
                                    <tr>	
                                        <td valign="top">
                                              <table cellpadding="1" cellspacing="0">
                                                  <tr>
                                                      <td align="right"><?php echo $words["name"]?>: </td>
                                                      <td><input oper="search" style="width:80px;" id="sch_name" value="" /></td>
                                                  </tr>
                                                  <tr>
                                                      <td align="right"><?php echo $words["phone"]?>: </td>
                                                      <td><input oper="search" style="width:80px;" id="sch_phone" value="" /></td>
                                                  </tr>
                                              </table>
                                        </td>
                                        <td valign="top">
                                              <table cellpadding="1" cellspacing="0">
                                                  <tr>
                                                      <td align="right"><?php echo $words["email"]?>: </td>
                                                      <td><input oper="search" style="width:80px;" id="sch_email" value="" /></td>
                                                  </tr>
                                                  <tr>
                                                      <td align="right"><?php echo $words["city"]?>: </td>
                                                      <td><input oper="search" style="width:80px;" id="sch_city" value="" /></td>
                                                  </tr>
                                            </table>
                                        </td>
                                        <td valign="top">
                                              <table cellpadding="1" cellspacing="0">
                                                  <tr>
                                                      <td align="right"><?php echo $words["gender"]?>: </td>
                                                      <td>
                                                          <select oper="search" id="sch_gender">
                                                              <option value=""></option>
                                                              <option value="Male"><?php echo $words["male"]?></option>
                                                              <option value="Female"><?php echo $words["female"]?></option>
                                                          </select> 
                                                      </td> 	
                                                      <td align="right"><?php echo $words["web"]?>: </td>
                                                      <td>
                                                           <select oper="search" id="sch_online">
                                                                <option value=""></option>
                                                                <option value="1"><?php echo $words["yes"]?></option>
                                                                <option value="0"><?php echo $words["no"]?></option>
                                                            </select>    
								 					 </td>
                                                     <td align="right"><?php echo $words["att.rate"]?>: >= </td>
                                                      <td>
															<input oper="search" style="width:30px;font-weight:bold;text-align:center;" id="sch_attend" value="" />                                                         
															<span style="font-size:16px;font-weight:bold;">%</span>
                                                      </td>

                                                       <td align="right"><?php echo $words["member.title"]?>: </td>
                                                       <td>
                                                            <select id="sch_level" style="text-align:center;" name="sch_level">
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
                                                      <td align="right"><?php echo $words["group"]?>: </td>
                                                      <td>
                                                        <input style="width:30px;font-size:14px;font-weight:bold;text-align:center;" oper="search" id="sch_group" value="" />
                                                      </td>
                                                      <!--<td align="right"><?php echo $words["onsite registration"]?>: </td> -->
                                                      <td align="right"><?php echo $words["trial"]?>: </td>
                                                      <td>
                                                           <select oper="search" id="sch_trial">
                                                                <option value=""></option>
                                                                <option value="1"><?php echo $words["yes"]?></option>
                                                                <option value="0"><?php echo $words["no"]?></option>
                                                            </select>    
														</td>
                                                      <td align="right"><?php echo $words["new people"]?>: </td>
                                                      <td>
                                                           <select oper="search" id="sch_new_flag">
                                                                <option value=""></option>
                                                                <option value="1"><?php echo $words["yes"]?></option>
                                                                <option value="0"><?php echo $words["no"]?></option>
                                                            </select>    
														</td>
                                                        <td align="right"><span style="font-size:12px;"><?php echo $words["short.lang"]?>: </span></td>
                                                        <td>											
                                                                  <?php
                                                                      echo iHTML::select1($admin_user["lang"], $db, "vw_vol_language", "sch_lang");
                                                                  ?>
                                                         </td>
                                                     	 <td align="right"><?php echo $words["reg.date"]?>: </td>
                                                      	<td>
                                                             <input oper="search" style="width:80px;" id="sch_date" value="" />
                                                      </td>
                                                  </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" valign="middle" align="left">
                                           <span style="font-size:12px;font-weight:bold;"><?php echo $words["id number"]?>: </span> <input style="width:120px;" id="sch_idd" value="" />   
                                            <span style="margin-left:20px;"></span>
                                           	<input type="button" right="view"  onclick="allstudent_search_ajax()" style="width:100px; vertical-align:middle;" value="<?php echo $words["search"]?>" />
											<input type="button" oper="view" right="print" id="btn_blank_label"  style="width:100px; vertical-align:middle;" gid="0" value="<?php echo $words["blank label"]?>" />                                                
											<input type="button" oper="view" right="save" id="btn_shoes"  onclick="shoes_shelf_ajax()" style="width:100px; vertical-align:middle;" gid="0" value="<?php echo $words["button shoes"]?>" />                                                
					                       <a id="btn_print_empty" class="tabQuery-button tabQuery-button-output" style=" vertical-align:middle; margin-left:20px;" right="print" title="<?php echo $words["print empty signature"]?>"></a> 
                                        </td>
                                    </tr>
                            </table>  
                        </fieldset>
                  		<div id="all_student"></div>

                  </div><!-- end of <div id="group_item"> -->
                  <div id="group_stat" style="min-height:400px; overflow-x:hidden; overflow-y:auto;">
		              	<center><input type="button"  right="view" id="btn_stat_output" onclick="stat_output_ajax()" value="<?php echo $words["button print"]?>" /></center>
                  		<div id="stat_content"></div>
                  </div><!-- end of <div id="group_item"> -->
                  <div id="group_refuse" style="min-height:400px; overflow-x:hidden; overflow-y:auto;">
                  		<div id="student_cancel"></div>
                  </div><!-- end of <div id="group_item"> -->
              </div>
          </div><!-- end of <div id="group_edit"> -->
	</div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_detail" class="lwhDiag" style="z-index:888;">
	<div class="lwhDiag-content lwhDiag-no-border">
        <table cellpadding="2" cellspacing="0" width="100%">
        	<tr>
            	<td  style="white-space:nowrap;"><?php echo $words["subject"]?>: </td>
            	<td><input class="form-input" type="text" id="subject" style="width:480px;" value="<?php echo cTYPE::gstr($emailArr[0][$Glang]["subject"]);?>" /></td>
        	</tr>
        	<tr>
            	<td valign="top"  style="white-space:nowrap;"><?php echo $words["content"]?>: </td>
            	<td><textarea id="content" style="width:480px; height:250px; resize:none;"><?php echo cTYPE::gstr($emailArr[0][$Glang]["content"]);?></textarea></td>
        	</tr>
        	<tr>
            	<td valign="top" style="white-space:nowrap;"><?php echo $words["identity"]?>: </td>
            	<td>
                	<input class="form-input" type="text" id="identity" style="width:40px; text-align:center;" value="" />
                	<?php echo $words["please specify email version. for example: a, b, c"]?> 
                </td>
        	</tr>
        </table>     
              <center><input type="button"  right="email" id="btn_email_save" onclick="email_ajax()" value="<?php echo $words["send email"]?>" /></center>
	</div>
</div>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

<div id="diaglog_nametag" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<input type="hidden" id="nametag_aflag" value="" />
    	<input type="hidden" id="nametag_group" value="" />
    	<input type="hidden" id="nametag_enroll" value="" />
        <br />
        <span style="margin-left:10px;vertical-align:middle;font-size:18px;font-weight:bold;"><?php echo $words["nametag.temp"]?>:<br /> 
            <select id="nametag_temp" name="nametag_temp" style="font-size:18px; width:300px;">
                <option value="event_calendar_group_nametag.php"><?php echo $words["nametag.bigsize"]?></option>
                <option value="event_calendar_group_nametag1.php" selected><?php echo $words["nametag.bigsize1"]?></option>
                <option value="event_calendar_group_nametag11.php"><?php echo $words["nametag.bigsize11"]?></option>
                <option value="event_calendar_group_nametag2.php"><?php echo $words["nametag.bigsize2"]?></option>
                <option value="event_calendar_group_nametag22.php"><?php echo $words["nametag.bigsize22"]?></option>
                <option value="event_calendar_group_nametag_card.php"><?php echo $words["nametag.cardsize"]?></option>
                <option value="event_calendar_group_nametag_card1.php"><?php echo $words["nametag.cardsize1"]?></option>
                <option value="event_calendar_group_nametag_vcard.php"><?php echo $words["nametag.vcardsize"]?></option>
                <option value="event_calendar_group_vipb_card.php"><?php echo $words["nametag.vipbsize"]?></option>
                <option value="event_calendar_group_vipb_card1.php"><?php echo $words["nametag.vipbsize1"]?></option>
                <option value="event_calendar_group_vip_card.php"><?php echo $words["nametag.vipsize"]?></option>
                <option value="event_calendar_group_vip_card1.php"><?php echo $words["nametag.vipsize1"]?></option>
            </select>
        </span> <br /><br />
        <input type="checkbox" id="nametag_shoes" style="vertical-align:middle;" value="1" /> <label style="font-size:16px;" for="nametag_shoes"><?php echo $words["print shoes shelf number"]?></label><br />
        <input type="checkbox" id="nametag_last" style="vertical-align:middle;" value="1" /> <label style="font-size:16px;" for="nametag_last"><?php echo $words["last name first name"]?></label><br /><br />
        <b><?php echo $words["title"]?></b> : <input id="nametag_title" style="vertical-align:middle;width:100px;" value="" /> (Sample: <?php echo $words["staff nametag"]?>)<br /><br />
        <center><input type="button" id="nametag_print" style="font-size:18px;" right="print" value="<?php echo $words["button print"]?>" /></center>                         
	</div>
</div>

<?php include("tpl_member_detail.php"); ?>
</body>
</html>