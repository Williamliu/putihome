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
 		<script type="text/javascript" 	src="../js/js.lwh.html5player.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var gTimer 		= null;
		var gTimer11 	= null;
		var gTimer22 	= null;
		var allObj		= null;
		
		var Media = null;
		var audio = new LWH.HTML5Player();
		$(function(){
			if( audio.tSupport("mp3")) {
				 Media = new Audio("<?php echo $CFG["http"]. $CFG["admin_domain"];?>/error.mp3"); 
			} else if( audio.tSupport("wav") ) {
				 Media = new Audio("<?php echo $CFG["http"]. $CFG["admin_domain"];?>/error.wav"); 
			}
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


			$("#diaglog_event").lwhDiag({
				titleAlign:		"center",
				title:			words["please select the event"],
				
				cnColor:		"#F8F8F8",
				bgColor:		"#EAEAEA",
				ttColor:		"#94C8EF",
				 
				minWW:			600,
				minHH:			150,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		false,
				maskClick:		true,
				pin:			false
			});
			
            $("input#tabQuery_pageSize", "#holder_list > div.tabQuery-dbnav-background").live("focusin", function(ev) {
                gTimer.stop();                
            });

            $("input#tabQuery_pageSize", "#holder_list > div.tabQuery-dbnav-background").live("focusout", function(ev) {
		        $("input#sch_idd").focus();
				$("input#sch_idd").select();
                gTimer.start();
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
				var eid = $("input#event_id").val();
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
					

			allObj = new LWH.cTABLE({
										  condition: 	{ 
											  event_id: "#event_id"
										  },
										  headers:[
											  {title: words["sn"], 			col:"rowno",		width:25},
											  {title: words["checkin_state"], 		col:"enroll_flag",	width:25},
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
											  {title: "", 					col:""}
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
			html += '<td class="tabQuery-table-header" width="20">' + words["sn"] + '</td>';
			html += '<td class="tabQuery-table-header" width="25">' + words["checkin_state"] + '</td>';
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
			  html += '</td>';
			  
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
			  html += '</td>';
			  html += '<td align="center">';
			  html +=  obj.group_no;
			  html += '</td>';
			  html += '<td align="center">';
			  if( !(obj.unenroll == 1 || obj.unauth == 1) )  
				  html += '<input class="trial" rid="' + obj.id + '" sn="' + idx + '" mid="' + obj.member_id + '" type="checkbox" ' + (obj.trial=="1"?'checked':'')+ ' value="1" />';
			  //html += obj.trial=="1"?words["trial"]:'';
			  html += '</td>';
			  html += '<td align="right">';
			  html += obj.trial=="1"?obj.trial_length:'';
			  html += '</td>';
			  html += '<td mid="' + obj.member_id + '">';
			  html +=  obj.name2;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.gender;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.phone;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.city;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.site;
			  html += '</td>';
			  html += '<td align="center"><span class="shelf">';
			  html +=  obj.shelf;
			  html += '</span></td>';
			  html += '<td align="center">';
			  html +=  obj.paid;
			  html += '</td>';
			  html += '<td>';
			  html +=  obj.idd;
			  html += '</td>';
			  html += '<td align="left" valign="middle">';
			  html += '<a class="tabQuery-button tabQuery-button-delete" style="vertical-align:middle;"  oper="delete" right="delete" rid="' +  obj.id + '" sn="' + idx + '"  title="直接删除"></a>';					 
			  if( obj.unenroll == 1 || obj.unauth == 1 )  
			  			html += '<a class="student-enroll"  style="vertical-align:middle;" rid="' + obj.id + '" sn="" mid="' + obj.member_id + '" title="' + words["enroll"] + '"><a>';
			  html += '</td>';
			  html += '</tr>';
			}
			return html;
		}


		function scanHTML(scanObj) {
			  if( $("tr.student[rid='" + scanObj.id + "']", "#holder_list").length > 0 ) 
			  {
					$("tr[rid='header']").after($("tr.student[rid='" + scanObj.id + "']", "#holder_list"));	
					return;		  
			  }
			  
			  var pageSize = $("#tabQuery_pageSize", "#holder_list").val();
			  if( $("tr.student", "#holder_list").length >= pageSize ) $("tr.student:last", "#holder_list").remove(); 
			  var html = '';
			  html += '<tr class="student" rid="' +  scanObj.id + '" sn="">';
			  html += '<td class="sn" align="center" width="20">';
			  html +=  1;
			  html += '</td>';
			 
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
			  html += '</td>';
			  html += '<td align="center">';
			  html +=  scanObj.group_no;
			  html += '</td>';
			  html += '<td align="center">';
			  if( !(scanObj.unenroll == 1 || scanObj.unauth == 1) ) 
			  html += '<input class="trial" rid="' + scanObj.id + '" sn="" mid="' + scanObj.member_id + '" type="checkbox" ' + (scanObj.trial=="1"?'checked':'')+ ' value="1" />';
			  //html += scanObj.trial=="1"?words["trial"]:'';
			  html += '</td>';
			  html += '<td align="right">';
			  html += scanObj.trial=="1"?scanObj.trial_length:'';
			  html += '</td>';
			  html += '<td mid="' + scanObj.member_id + '">';
			  html +=  scanObj.name2;
			  html += '</td>';
			  html += '<td>';
			  html +=  scanObj.gender;
			  html += '</td>';
			  html += '<td>';
			  html +=  scanObj.phone;
			  html += '</td>';
			  html += '<td>';
			  html +=  scanObj.city;
			  html += '</td>';
			  html += '<td>';
			  html +=  scanObj.site;
			  html += '</td>';
			  html += '<td align="center"><span class="shelf">';
			  html +=  scanObj.shelf;
			  html += '</span></td>';
			  html += '<td align="center">';
			  html +=  scanObj.paid;
			  html += '</td>';
			  html += '<td>';
			  html +=  scanObj.idd;
			  html += '</td>';
			  html += '<td align="left" valign="middle">';
			  html += '<a class="tabQuery-button tabQuery-button-delete" style="vertical-align:middle;" oper="delete" right="delete" rid="' +  scanObj.id + '" sn="" title="直接删除"></a>';					 
			  if( scanObj.unenroll == 1 || scanObj.unauth == 1 )  
			  			html += '<a class="student-enroll" style="vertical-align:middle;" rid="' + scanObj.id + '" sn="" mid="' + scanObj.member_id + '" title="' + words["enroll"] + '"><a>';
			  html += '</td>';
			  html += '</tr>';
			$("tr[rid='header']").after(html);
			
			$("tr.student", "#holder_list").each(function(idx, el) {
				$(".sn", this).html( idx + 1);
				$(this).attr("sn", idx + 1); 
				$("*[sn]", this).attr("sn", idx+1);
			});
			
		}
		

		function scan_idd(idd) {
			idd = $.trim(idd);
			if( idd == "" ) return;
	  	    $("#wait").loadShow();				  
			
			try {
				if( audio.tSupport("mp3") || audio.tSupport("wav")) Media.pause();
				if( audio.tSupport("mp3") || audio.tSupport("wav")) Media.currentTime = 0;
			} catch( ex ){}
			
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",

					pageNo:		allObj.tabData.pageNo,
					pageSize:	allObj.tabData.condition.pageSize,
					orderBY:	allObj.tabData.condition.orderBY,
					orderSQ:	allObj.tabData.condition.orderSQ,

					event_id: $("input#event_id").val(), 
					sch_idd: idd	
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
						
						if(req.data.scan["member_id"]>0) {
							scanHTML(req.data.scan);
						}
						
						try {
							if( req.data.music_flag == "1") { 
								if( audio.tSupport("mp3") || audio.tSupport("wav")) Media.play();
							} else {
								if( audio.tSupport("mp3") || audio.tSupport("wav")) Media.pause();
								if( audio.tSupport("mp3") || audio.tSupport("wav")) Media.currentTime = 0;
							}
						} catch( ex ){}
							
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
					  event_id:		$("input#event_id").val(),
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
		
		function event_select( eid ) {
			$("input#event_id").val(eid);
			$("#event_desc").html( $("#sch_event>option[value='" + eid + "']").html() );
		}
		
		function event_diag_close() {
			$("#diaglog_event").diagHide();
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
		
		function back_to_main() {
			window.location.href = "<?php echo $CFG["http"] . $CFG["admin_domain"]?>/event_calendar_checkin1.php?attend_event_id=" + $("input#event_id").val();
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <input type="button" style="margin-left:2px;" id="btn_erfresh" onclick="list_refresh()" value="<?php echo $words["button refresh"];?>" />
    <span style="font-size:14px; font-weight:bold; margin-left:5px;"><?php echo $words["scan id card here"]?>: </span>
	<input style="width:100px;" id="sch_idd" value="" /> <span style="font-size:14px; font-weight:bold;"><?php echo $words["to check in"]?>.</span>
    <span style="font-size:16px; margin-left:5px; font-weight:bold;">
                <?php echo $words["event title"]?> : 
    </span>
    <span id="event_desc" style="color:#A01E04; font-size:16px;"></span>
    <br />
    <div id="sch_result" style="font-size:14px; font-weight:bold; margin-left:5px; margin-top:5px; height:260px;">
    </div>
	<div id="holder_list" style="padding:5px; min-height:420px;">
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

<div id="diaglog_event" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<br />
        <b><?php echo $words["please select event"]?>: <b><br /><br />
            <select id="sch_event" onchange="event_select(this.value);" style="width:100%">
            <?php 
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
                    if( $first  && $_REQUEST["attend_event_id"]=="" ) {
						$first = false;
						echo '<option value="' . $row["id"] . '" selected>' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
					} else {
						echo '<option value="' . $row["id"] . '" ' . ($_REQUEST["attend_event_id"]==$row["id"]?'selected':'') . '>' . cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']</option>';
					}
				}
            ?>
            </select>
            <br /><br />
            <center><span id="msg_error" style="font-size:14px; font-weight:bold; color:red;"></span></center>
        	<center><input type="button" onclick="event_diag_close()" value="<?php echo $words["button close"]?>" /></center> 
	</div>
</div>
<input type="hidden" id="event_id" name="event_id" value="" />
<script language="javascript" type="text/javascript">
$(function(){
			$("#diaglog_event").diagShow({
				diag_close: function() {
					if($("#sch_event").val() != "") {
					  $("input#event_id").val($("#sch_event").val());
					  $("#event_desc").html( $("#sch_event>option[value='" + $("#sch_event").val() + "']").html() );
				  	  allObj.start();
					  $("input#sch_idd").focus();

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
					} else {
						$("#msg_error").html(words["error: please select the event from event list"]);
						$("#diaglog_event").diagShow();
					}
				}
			});			

});
</script>
</body>
</html>