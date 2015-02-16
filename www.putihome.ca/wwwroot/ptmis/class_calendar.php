<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,48";
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
		<title>Bodhi Meditation Class - Add to Calendar</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

 		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			 words["submit success"],
				
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

			$("#group_list, #group_edit").lwhTabber();
			
			  $("#start_date").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
							});


			$("#btn_pwd").live("click", function(ev) {
				$("#diaglog_pwd").diagShow();
			});
			
			$("li.puti-class").live("click", function(ev) {
	  		  	  $("#wait").loadShow();
				  var gid = $(this).attr("class_id");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
			  		  	  $("#wait").loadHide();
						  alert("Error (class_edit_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
			  		  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							jsonHTML(req.data);
 							node_selected(req.data.class_id);
						  }
					  },
					  type: "post",
					  url: "ajax/class_edit_select.php"
				  });
			});
	
			class_ajax();		
		});
		function save_class() {
		  	      $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						
						  title: 		$("input#class_title").val(),
						  description: 	$("textarea#class_desc").val(),
						  place: 		$("#place").val(),
						  cls: 			toJSON()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  	      $("#wait").loadHide();
						  alert("Error (class_calendar_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  	      $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
	 						  node_selected(req.data.class_id);
							  tool_tips(words["save success"]);		
							  //$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							  //$("#diaglog_message").diagShow({title:words["submit success"]}); 
							  $("input.input-date").val("");
						  }
					  },
					  type: "post",
					  url: "ajax/class_calendar_save.php"
				  });
		}

		
		function jsonHTML(obj) {
			$("input#hid").val(obj.id);
			$("input#class_title").val(obj.title);
			$("textarea#class_desc").val(obj.description);
			$("#cal_event_list").empty();
			for(var key in obj.dates) {
				date_html(obj.dates[key]);				
			}
		}
		
		function date_html(day) {
			
				var timespan = day.start_time?day.start_time + (day.end_time?'~'+day.end_time:''):(day.end_time?day.end_time:'');
				var html = '';
				html += '<li class="date-area" dd="' + day.id + '" style="margin-left:20px; margin-top:5px; margin-bottom:5px; width:500px;">';
				html += '<table border="0">';
				html += '<tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html += words["day"] + ' <b>' + day.day_no + ' ' + words["day1"] +  '</b>';
				html += '</td>';
				html += '<td align="left" valign="top"  style="padding:0px 5px 0px 5px; font-size:12px;  text-transform:none;">';
				html += '<input class="input-date" dd="' + day.id + '" dayno="' + day.day_no + '" type="text" style="width:100px;" value="" />';
				html += '</td>';
				html += '</tr><tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html += words["time"] + ': ';
				html += '</td>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:500px; font-weight:bold;">';
				html += timespan;
				html += '</td>';
				html += '</tr><tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html +=  words["subject"] + ': ';
				html += '</td>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:500px; font-weight:bold;">';
				html += day.title;
				html += '</td>';
				html += '</tr>';
				html += '</table>';
				html += '</li>';

				$("#cal_event_list").append(html);
				$("input.input-date[dd='" + day.id + "']").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
				});
		}
		
		function toJSON() {
			var clsObj = {};
			clsObj.id 			= $("input#hid").val();

			clsObj.dates 		= [];
			$("li.date-area[dd]").each(function(idx1, el1) {
				var dd = $(this).attr("dd");
				var dateObj = {};
				dateObj.id = dd;
				dateObj.event_date	= $("input.input-date[dd='" + dd + "']").val();
				dateObj.day_no	= $("input.input-date[dd='" + dd + "']").attr("dayno");
				clsObj.dates[clsObj.dates.length] = dateObj;
			});
			return clsObj;
		}
		
		function add_date() {
			$("input.input-date[dd]").each(function(idx, el){
				var curdate = new Date( $("#start_date").val() );
				curdate.setDate(curdate.getDate() + parseInt($(this).attr("dayno")) );
				if( !isNaN(curdate.getFullYear()) ) 
					$(this).val(curdate.getFullYear() + "-" + (curdate.getMonth()+1) + "-" + curdate.getDate());
			});
		}

		function class_ajax(class_id) {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  class_id:		class_id
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (class_edit_class.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  sitesHTML(req.data.sites);
							  //node_selected(req.data.class_id);
						  }
					  },
					  type: "post",
					  url: "ajax/class_edit_class.php"
				  });
		}

		function sitesHTML( sites ) {
			$("#groups_area").html("");
			var html = '';
			html += '<ul id="lwhGroups" class="lwhTree" style="margin-left:0px; padding-left:0px;">';
			var cnt0 = 0;
			for(var key0 in sites) {			
				cnt0++;
				html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
				html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;">' + words[sites[key0].title.toLowerCase()] + ' { <span style="font-weight:normal;">' + sites[key0].branchs.length + ' ' + words["groups"] + ' }</span></span>'; 
				html += '<ul class="lwhTree">';
				
				var groups = sites[key0].branchs;
				var cnt = 0;
				for(var key in groups) {
					cnt++;
					html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
					html += '<span class="click" style="color:#000000; font-size:12px; font-weight:bold;" title="' + words[groups[key].title.toLowerCase()] + '">' + words[groups[key].title.toLowerCase()] + ' { <span style="font-weight:normal;">' + groups[key].classes.length + ' ' + words["classes"] + ' }</span></span>'; 
					html += '<ul class="lwhTree">';
					for(var key1 in groups[key].classes) {
						var classObj = groups[key].classes[key1];
						html += '<li class="node puti-class" class_id="' + classObj.class_id + '"><s class="node-line"></s><s class="node-img"></s>';
						var mem_str = '<span class="title" style="color:#333333; width:200px;" title="Click To View Details">' + classObj.name + '</span>'; 
						html += mem_str;
						html += '</li>';
					}
					html += '</ul>';
					html += '</li>';
				}

				html += '</ul>';
				html += '</li>';
			}
			$("#groups_area").html(html);
			$("#lwhGroups").lwhTree();
		}
	
		function node_selected(cid) {
			  $("li.puti-class[class_id]").removeClass("node-selected");
			  $("li.puti-class[class_id='" + cid + "']").addClass("node-selected");
			  $("li.puti-class[class_id]").parents("li.nodes").removeClass("nodes-open nodes-close").addClass("nodes-close");
			  $("li.puti-class[class_id]").parents("li.nodes-last").removeClass("nodes-last-open nodes-last-close").addClass("nodes-last-close");
			  $("li.puti-class[class_id='" + cid + "']").parents("li.nodes").removeClass("nodes-open nodes-close").addClass("nodes-open");
			  $("li.puti-class[class_id='" + cid + "']").parents("li.nodes-last").removeClass("nodes-last-open nodes-last-close").addClass("nodes-last-open");
		}
    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
        	<tr>
            	<td valign="top" width="280px">
                    <div id="group_list" class="lwhTabber lwhTabber-goldenrod" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["class list"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="min-height:350px;">
                            <div id="groups_area" style="min-height:320px; overflow-x:hidden; overflow-y:auto;">
                            </div>
                        </div>
                    </div>
                </td>
            	<td valign="top" width="auto">
                    <div id="group_edit" class="lwhTabber lwhTabber-fuzzy" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["class details"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="min-height:350px;">
                            <div id="group_item">
                            		  <!------------------------------------------------------------------------->
                                      <div id="calendar_add" style="padding:5px;">
                                          <table cellpadding="2" cellspacing="0" width="500">
                                              <tr>
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["subject"]?>: </td>
                                                  <td style="white-space:nowrap; width:500px;">
                                                  	<input type="hidden" id="hid" name="hid" value="" />
                                                    <input type="text" id="class_title" style="width:100%" value="" />
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["description"]?>: </td>
                                                  <td style="white-space:nowrap; width:500px;"><textarea id="class_desc" style="width:100%; height:60px; resize:none;"></textarea></td>
                                              </tr>
                                              <tr>
                                                    <td align="right"><?php echo $words["event_place"]?>: </td>
                                                    <td>
                                                        <select id="place" style="min-width:100px;" name="place">
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
                                                  <td style="white-space:nowrap;" align="right"><?php echo $words["start date"]?>: </td>
                                                  <td valign="top">
                                        				<input id="start_date" type="text" style="width:100px;" value="" />
                                                        <input type="button" right="save" id="btn_date" name="btn_date" onclick="add_date()" style="margin-left:20px;" value="<?php echo $words["add date"]?>" />
                                                        <input type="button" id="class_save" right="save" onclick="save_class()" style="margin-left:30px;" value="<?php echo $words["button save"]?>" />

                                                 </td>
                                              </tr>
                                          </table>
                                          <div id="cal_event_list" style="border-top:1px solid black;"></div>
                                          <br />
                                          <center><input type="button" id="class_save" right="save" onclick="save_class()" value="<?php echo $words["button save"]?>" /></center>
                                      </div>
                            		  <!------------------------------------------------------------------------->

                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
                </td>
            </tr>    
        </table>
	</div>
</div>
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