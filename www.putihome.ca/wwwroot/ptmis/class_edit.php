<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,30";
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
		<title></title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

 		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#group_list, #group_edit").lwhTabber();

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
		
			new_ajax();	
			class_ajax();
		});
		function save_class() {
	  		  	  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
							
						  cls: toJSON()	
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
			  		  	  $("#wait").loadHide();
						  alert("Error (class_edit_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
			  		  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
	 						  node_selected(req.data.class_id);
							  tool_tips(words["save success"]);	
						  }
					  },
					  type: "post",
					  url: "ajax/class_edit_save.php"
				  });
		}

		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#hid").val(-1);
			$("input#class_title").val("");
			$("textarea#class_desc").val("");
			$("select#agreement").val("");
			$("input#sn").val("");
			$("select#status").val("");
			$("select#checkin").val("");
			$("input#attend").val("");
			$("input#cert_prefix").val("");
			$("input#cert").attr("checked", false);
			$("input#photo").attr("checked", false);
			$("input#payfree").attr("checked", false);
			$("input#payonce").attr("checked", false);
			$("select#logform").val("");
			
			$("#cal_event_list").empty();
		}

		function del_ajax() {
			if( $("input#hid").val() < 0 ) return;
			var yes = false;
			if( yes = window.confirm( words["are you sure to delete this record?"] ) ) {
		    		$("#wait").loadShow();
					$.ajax({
						data: {
							admin_sess: $("input#adminSession").val(),
							admin_menu:	$("input#adminMenu").val(),
							admin_oper:	"delete",

							id: $("input#hid").val()
						},
						dataType: "json",  
						error: function(xhr, tStatus, errorTh ) {
				    		$("#wait").loadHide();
							alert("Error (class_edit_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
						},
						success: function(req, tStatus) {
				    		$("#wait").loadHide();
							if( req.errorCode > 0 ) { 
								errObj.set(req.errorCode, req.errorMessage, req.errorField);
								return false;
							} else {
								new_ajax();
								$("input[name='fhh']").val("");
								$("input[name='fmm']").val("");
								$("input[name='thh']").val("");
								$("input[name='tmm']").val("");
								
								$("li.puti-class[class_id='" + req.data.class_id + "']").remove();						
								$("#lwhGroups").lwhTree_refresh();
								
							}
						},
						type: "post",
						url: "ajax/class_edit_delete.php"
					});
			}
		}
		
		function jsonHTML(obj) {
			$("input#hid").val(obj.id);
			$("input#class_title").val(obj.title);
			$("textarea#class_desc").val(obj.description);
			$("select#agreement").val(obj.agreement);
			$("input#sn").val(obj.sn);
			$("select#status").val(obj.status);
			$("select#checkin").val(obj.checkin);
			$("input#attend").val(obj.attend);
			$("input#cert_prefix").val(obj.cert_prefix);
			$("input#cert").attr("checked", (obj.cert=="1"?true:false));
			$("input#photo").attr("checked", (obj.photo=="1"?true:false));
			$("input#payfree").attr("checked", (obj.payfree=="1"?true:false));
			$("input#payonce").attr("checked", (obj.payonce=="1"?true:false));
			$("select#logform").val(obj.logform);
			set_check(obj.checkarr);
			$("#cal_event_list").empty();
			for(var key in obj.dates) {
				date_html(obj.dates[key]);				
			}
		}
		
		function set_check(obj) {
			$("input[name='fhh']").val("");
			$("input[name='fmm']").val("");
			$("input[name='thh']").val("");
			$("input[name='tmm']").val("");
			if(obj && obj.length > 0) {
				for(var key in obj) {
					var sn = obj[key].sn;
					var fhh = obj[key].fhh;
					var fmm = obj[key].fmm;
					var thh = obj[key].thh;
					var tmm = obj[key].tmm;
					$("input[name='fhh'][sn='" + sn + "']").val(fhh);
					$("input[name='fmm'][sn='" + sn + "']").val(fmm);
					$("input[name='thh'][sn='" + sn + "']").val(thh);
					$("input[name='tmm'][sn='" + sn + "']").val(tmm);
				}
			}
		}
		
		function date_html(day) {
			
				var html = '';
				html += '<li class="date-area" dd="' + day.id + '" style="margin-left:20px; margin-top:5px; margin-bottom:5px; width:500px;">';
				html += '<table border="0">';
				html += '<tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html += words["day"] + ' <b>' + day.day_no + ' ' + words["day1"] +  '</b>';
				html += '</td>';
				html += '<td align="left" valign="top"  style="padding:0px 5px 0px 5px; font-size:12px;  text-transform:none;">';
				
				html += words["from"] + ': ';
				html += hour_html(day.id, 'cal-start-time', day.start_time); 
				html += ' ' + words["to"] + ': ';
				html += hour_html(day.id, 'cal-end-time', day.end_time); 

				html += '</td>';
				html += '</tr><tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html +=  words["subject"] + ': ';
				html += '</td>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:500px;">';
				html += '<input class="date-title" dd="' + day.id + '" type="text" value="" />';
				html += '</td>';
				html += '</tr><tr>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px; width:60px;">';
				html += words["description"] +': ';
				html += '</td>';
				html += '<td valign="top" style="padding:0px 5px 0px 5px;">';
				html += '<textarea class="date-desc" dd="' + day.id + '"></textarea>';
				html += '</td>';
				html += '</tr><tr>';
				html += '<td></td>';
				html += '<td style="padding:0px 5px 0px 5px; width:60px;">';

				html += '<b>' + words["checkin times"] + ':</b> <select class="date-checkin" dd="' + day.id + '">';
				html += '<option value=""></option>';
				html += '<option value="1">1</option>';
				html += '<option value="2">2</option>';
				html += '<option value="3">3</option>';
				html += '</select>';
				html += '<span style="margin-left:20px; font-weight:bold;">' + words["meal"] + ': </span>';
				html += '<input type="checkbox" name="date_meal" dd="' + day.id + '" value="Breakfast" />' + words["breakfast"];
				html += '<input type="checkbox" name="date_meal" dd="' + day.id + '" style="margin-left:5px;" value="Lunch" />' + words["lunch"];
				html += '<input type="checkbox" name="date_meal" dd="' + day.id + '" style="margin-left:5px;" value="Dinner" />' + words["dinner"];

				html += '</td>';
				html += '</tr>';
				html += '</table>';
				html += '</li>';

				$("#cal_event_list").append(html);
				$("input.date-title[dd='" + day.id + "']").val(day.title);
				$("textarea.date-desc[dd='" + day.id + "']").val(day.description);
				$(".date-checkin[dd='" + day.id + "']").val( day.checkin );
				
				$.map( day.meal.split(","), function(n) {
					$("input:checkbox[name='date_meal'][dd='" + day.id + "'][value='" + n + "']").attr("checked",true);
				});
		}
		
		function hour_html(dd, cid, val) {
			var html = '';
			var hm = []; 
			hm[0] = '';
			hm[1] = '';
			if(val) hm = val.split(":");

			html += '<select dd="' + dd + '" hm="hour" class="cal-time ' + cid + '">';
			html += '<option value=""></option>';
			for(var i=5; i<=23; i++) {
				if( i == parseInt(hm[0]) ) 
					html += '<option value="' + i + '" selected>' + i + '</option>';
				else 
					html += '<option value="' + i + '">' + i + '</option>';
			}
			html += '</select>';
			html += '<b> : </b>';
			html += '<select dd="' + dd + '" hm="min" class="cal-time ' + cid + '">';
			html += '<option value=""></option>';
			html += '<option value="00" ' + (hm[1]=="00"?'selected':'')+ '>00</option>';
			html += '<option value="15" ' + (hm[1]=="15"?'selected':'')+ '>15</option>';
			html += '<option value="30" ' + (hm[1]=="30"?'selected':'')+ '>30</option>';
			html += '<option value="45" ' + (hm[1]=="45"?'selected':'')+ '>45</option>';
			html += '</select>';
			return html;
		}
		
		function hour_val(dd, cid) {
			var thh = $("select." + cid + "[dd='" + dd + "'][hm='hour']").val();
			var tmm = $("select." + cid + "[dd='" + dd + "'][hm='min']").val();
			var hour =  thh + ":" + (tmm!=""?tmm:"00"); 
			var regExp = /\d+:\d{2}/gi;
			return regExp.test(hour)?hour:''; 	
		}
		
		function toJSON() {
			var clsObj = {};
			clsObj.id 			= $("input#hid").val();
			clsObj.title 		= $("input#class_title").val();
			clsObj.description 	= $("textarea#class_desc").val();
			clsObj.agreement  	= $("select#agreement").val();
			clsObj.sn       	= $("input#sn").val();
			clsObj.status  		= $("select#status").val();
			clsObj.cert			= $("input:checkbox#cert").is(":checked")?1:0;
			clsObj.cert_prefix	= $("input#cert_prefix").val();
			clsObj.logform		= $("select#logform").val();
			clsObj.checkin  	= $("select#checkin").val();
			clsObj.attend  		= $("input#attend").val();
			clsObj.photo		= $("input:checkbox#photo").is(":checked")?1:0;
			clsObj.payfree		= $("input:checkbox#payfree").is(":checked")?1:0;
			clsObj.payonce		= $("input:checkbox#payonce").is(":checked")?1:0;
			clsObj.checkarr		= get_checkin();
			clsObj.dates 		= [];
			$("li.date-area[dd]").each(function(idx1, el1) {
				var dd = $(this).attr("dd");
				var dateObj = {};
				dateObj.id = dd;
				dateObj.start_time 	= hour_val(dd,	"cal-start-time"); 
				dateObj.end_time 	= hour_val(dd,	"cal-end-time"); 
				dateObj.title 		= $("input.date-title[dd='" + dd + "']").val();
				dateObj.description = $("textarea.date-desc[dd='" + dd + "']").val();
				dateObj.checkin		= $("select.date-checkin[dd='" + dd + "']").val();
				dateObj.meal 		= $("input:checkbox[name='date_meal'][dd='" + dd + "']:checked").map(function(idx, el) {
                    						return $(this).val();
                						}).get().join(",");
				
				clsObj.dates[clsObj.dates.length] = dateObj;
			});
			return clsObj;
		}

		function get_checkin() {
			var ccnt = parseInt($("select#checkin").val());
			var check_arr = [];
			if( ccnt > 0 ) {
				for(var i = 1; i <= ccnt; i++) {
					var cobj = {};
					cobj.sn = i;
					cobj.fhh = $("input[name='fhh'][sn='" + i + "']").val();
					cobj.fmm = $("input[name='fmm'][sn='" + i + "']").val();
					cobj.thh = $("input[name='thh'][sn='" + i + "']").val();
					cobj.tmm = $("input[name='tmm'][sn='" + i + "']").val();
					check_arr[i] = cobj;
				}
			}
		    //alert(showObj(check_arr));
			return check_arr;
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
                        <div class="lwhTabber-content" style="min-height:400px;">
                            <div id="groups_area" style="min-height:400px; width:250px; overflow-x:hidden; overflow-y:auto;">
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
                        <div class="lwhTabber-content" style="min-height:400px;">
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
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["agreement"]?>: </td>
                                                  <td style="white-space:nowrap; width:500px;">
                                                      <select id="agreement" name="agreement">
                                                      <?php
                                                          $query = "SELECT id, subject FROM puti_agreement WHERE status = 1 AND deleted <> 1 ORDER BY created_time";
                                                          $result = $db->query($query);
                                                          echo '<option value="0"></option>';
                                                          while( $row = $db->fetch($result) ) {
                                                              echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($row["subject"]) . '</option>';
                                                          }
                                                      ?>
                                                      </select>

                                                      <span style="margin-left:20px;">
                                                      <?php echo $words["sn"]?> : 
                                                      <input id="sn" name="sn" style="width:40px;" value="" />
                                                      </span>

                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="white-space:nowrap;" align="right"><?php echo $words["status"]?>: </td>
                                                  <td valign="top">
                                                  	  <select id="status" name="status">
                                                      	<option value=""></option>
                                                        <option value="0"><?php echo $words["inactive"]?></option>
                                                        <option value="1"><?php echo $words["active"]?></option>
                                                      </select>
                                                      <input type="checkbox" id="cert" style="margin-left:20px;" name="cert" value="1" />
                                                      <?php echo $words["certification"]?> 

                                                      <span style="margin-left:5px;"></span> 
                                                      <?php echo $words["cert_no_prefix"]?>: <input type="text" id="cert_prefix" name="cert_prefix" style="width:50px;" value="" />
                                                 </td>
                                              </tr>
                                              <tr>
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["register form way"]?>: </td>
                                                  <td style="white-space:nowrap; width:500px;">
                                                      <select id="logform" name="logform">
                                                      <?php 
                                                          $query = "SELECT id, title FROM puti_forms ORDER BY id";
                                                          $result = $db->query($query);
                                                          echo '<option value=""></option>';
                                                          while( $row = $db->fetch($result) ) {
                                                              echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[$row["title"]]) . '</option>';
                                                          }
                                                      ?>
                                                      </select>

                                                      <span style="margin-left:20px;">
                                                      <input type="checkbox" id="payfree" 	name="payfree" value="1" /><label for="payfree"><?php echo $words["pay free"]?></label>
                                                      </span>
                                                      <span style="margin-left:20px;">
                                                      <input type="checkbox" id="payonce" 	name="payonce" value="1" /><label for="payonce"><?php echo $words["pay once"]?></label>
                                                      </span>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["checkin times"]?>: </td>
                                                  <td style="white-space:nowrap; width:500px;">
                                                  	  <select id="checkin" name="checkin">
                                                      	<option value=""></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                      </select>
                                                      <span style="margin-left:20px;"><?php echo $words["attend percent"]?>: </span>
                                                      <input type="text" style="text-align:center; width:20px;" id="attend" name="attend" value="70" /><span style="font-size:14px; font-weight:bold;">%</span>      
                                                      <span style="margin-left:20px;">
                                                      <input type="checkbox" id="photo" 	name="photo" value="1" /><label for="photo"><?php echo $words["photo checkin"]?></label>
                                                      </span>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="white-space:nowrap; width:50px;" align="right" valign="top"><?php echo $words["checkin times"]?>: </td>
                                                  <td style="white-space:nowrap; width:500px;" valign="top">
                                                     <span style="font-size:14px; font-weight:bold;">1. </span> 
                                                     <input type="text" style="width:20px; text-align:center;" name="fhh" sn="1" value="">
                                                     <span style="font-size:14px; font-weight:bold;">:</span>
                                                     <input type="text" style="width:20px; text-align:center;" name="fmm" sn="1" value="">
                                                       ~
                                                     <input type="text" style="width:20px; text-align:center;" name="thh" sn="1" value="">
                                                     <span style="font-size:14px; font-weight:bold;">:</span>
                                                     <input type="text" style="width:20px; text-align:center;" name="tmm" sn="1" value="">
                                                     <br />
                                                     <span style="font-size:14px; font-weight:bold;">2. </span> 
                                                     <input type="text" style="width:20px; text-align:center;" name="fhh" sn="2" value="">
                                                     <span style="font-size:14px; font-weight:bold;">:</span>
                                                     <input type="text" style="width:20px; text-align:center;" name="fmm" sn="2" value="">
                                                       ~
                                                     <input type="text" style="width:20px; text-align:center;" name="thh" sn="2" value="">
                                                     <span style="font-size:14px; font-weight:bold;">:</span>
                                                     <input type="text" style="width:20px; text-align:center;" name="tmm" sn="2" value="">
                                                     <br />
                                                     <span style="font-size:14px; font-weight:bold;">3. </span> 
                                                     <input type="text" style="width:20px; text-align:center;" name="fhh" sn="3" value="">
                                                     <span style="font-size:14px; font-weight:bold;">:</span>
                                                     <input type="text" style="width:20px; text-align:center;" name="fmm" sn="3" value="">
                                                       ~
                                                     <input type="text" style="width:20px; text-align:center;" name="thh" sn="3" value="">
                                                     <span style="font-size:14px; font-weight:bold;">:</span>
                                                     <input type="text" style="width:20px; text-align:center;" name="tmm" sn="3" value="">
                                                  </td>
                                              </tr>
                                          </table>
                                          <div id="cal_event_list" style="border-top:1px solid black;"></div>
                                          <br />
                                          <center>
                                            <input type="button" id="class_save" right="save" onclick="save_class()" value="<?php echo $words["button save"]?>" />
			                            	<input type="button" right="delete" id="btn_del" onclick="del_ajax()" value="<?php echo $words["button delete"]?>" />
                                          </center>
                                      </div>
                            		  <!------------------------------------------------------------------------->

                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
                </td>
            </tr>    
        </table>
	</div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>