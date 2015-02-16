<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="10,50";
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
		<title>Bodhi Meditation Volunteer Hours Adjust</title>
		
		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var jobs = [];

		$(function(){
			 ///////////////////////////////////////////////////////////////
			$("#start_date, #end_date").datepicker({ 
							  dateFormat: 'yy-mm-dd',  
							  showOn: "button",
							  buttonImage: "../theme/blue/image/icon/calendar.png",
							  buttonImageOnly: true  
						  });

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
				movable:			false,
				maskable: 		true,
				maskClick:		true,
				pin:				false
			});
		
			$("div#department_volunteer").lwhTabber({
				   button: false
			});
			
			
			$("#work_date").datepicker({ 
							  dateFormat: 'yy-mm-dd',  
							  showOn: "button",
							  buttonImage: "../theme/blue/image/icon/calendar.png",
							  buttonImageOnly: true  
						  });
			$("input.volunteer-del[hid]").live("click", function(ev) {
				  $("#wait").loadShow();
				  var hid = $(this).attr("hid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",

						  hid: 			hid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_adjust_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							search_ajax();
							//$("tr[hid='" + req.data.hid + "']").remove();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_adjust_delete.php"
				  });
			});

			$("input.volunteer-sav[hid]").live("click", function(ev) {
				  $("#wait").loadShow();
				  var hid = $(this).attr("hid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						  
						  hid:			hid,
						  job_id:		$("select.volunteer-job[hid='" + hid + "']").val(),
						  purpose:		$("input.volunteer-work[hid='" + hid + "']").val(),
						  work_hour:	$("input.volunteer-hour[hid='" + hid + "']").val()		
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_adjust_qsave.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  search_ajax();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_adjust_qsave.php"
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
						  hour:			hourJSON()		
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_adjust_fsave.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							search_ajax();
							$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							$("#diaglog_message").diagShow({title:"Saved successful."}); 
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_adjust_fsave.php"
				  });
			});
			
			
			$("a.tabQuery-sort").live("click", function(ev) {
				$("#wait").loadShow();
				var order_by = $(this).attr("orderby");
				var def_sq = $(this).attr("defsq");
				var cur_by = $(".tabQuery-table").attr("orderby");
				var cur_sq = $(".tabQuery-table").attr("ordersq");
				var new_by = order_by;
				var new_sq = "";
				if(cur_by == order_by) {
					if(cur_sq=="ASC") {
						new_sq = "DESC";						
					} else {
						new_sq = "ASC";						
					}
				} else {
					new_sq = def_sq;
				}
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  orderBY:		new_by,
						  orderSQ:		new_sq,
						  pid:			$("#department").val(),
						  start_date:   $("#start_date").val(),
						  end_date:   	$("#end_date").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_adjust_sort.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							jobs = req.data.jobs;
							addToListDepart(req.data);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_adjust_sort.php"
				  });
				
			});
		
		
			search_ajax();
			// end of function	
		});
		
		function hourJSON() {
			var pObj = [];
			$("input.volunteer-hour[hid]").each(function(idx1, el1) {
                var dObj = {};
				var hid = $(this).attr("hid");
				dObj.hid = hid;
				dObj.work_hour 	= parseFloat($(this).val());
				dObj.purpose 	= $("input.volunteer-work[hid='" + hid + "']").val();
				dObj.job_id 	= $("select.volunteer-job[hid='" + hid + "']").val();
				pObj[pObj.length] = dObj;
			});
			return pObj;
		}

		 
		function addToListDepart(obj) {
			var	  html = '<table class="tabQuery-table" pid="' + obj.pid + '" orderby="' + obj.orderBY + '" ordersq="' + obj.orderSQ + '">';
				  html += '<tr class="head">';
				  html += '<td class="tabQuery-table-header">' + words["sn"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["c.name"];
				  html += '<a class="tabQuery-sort' + (obj.orderBY=="cname"?' tabQuery-sort-' + obj.orderSQ.toLowerCase():'') + '" orderby="cname" defsq="ASC"></a>';
				  html += '</td>';
				  //html += '<td class="tabQuery-table-header">' + words["pinyin"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["e.name"] + '';
				  html += '<a class="tabQuery-sort' + (obj.orderBY=="en_name"?' tabQuery-sort-' + obj.orderSQ.toLowerCase():'') + '" orderby="en_name" defsq="ASC"></a>';
				  html += '</td>';
				  html += '<td class="tabQuery-table-header">' + words["dharma"] + '';
				  html += '<a class="tabQuery-sort' + (obj.orderBY=="dharma_name"?' tabQuery-sort-' + obj.orderSQ.toLowerCase():'') + '" orderby="dharma_name" defsq="ASC"></a>';
				  html += '</td>';

				  html += '<td class="tabQuery-table-header">';
				  html += words["work date"];
				  html += '<a class="tabQuery-sort' + (obj.orderBY=="work_date"?' tabQuery-sort-' + obj.orderSQ.toLowerCase():'') + '" orderby="work_date" defsq="ASC"></a>';
				  html += '</td>';
				 
				  html += '<td class="tabQuery-table-header">' + words["work for"] + '</td>';
				  html += '<td class="tabQuery-table-header">' + words["hours"] + '';
				  html += '<a class="tabQuery-sort' + (obj.orderBY=="work_hour"?' tabQuery-sort-' + obj.orderSQ.toLowerCase():'') + '" orderby="work_hour" defsq="ASC"></a>';
				  html += '</td>';
				  html += '<td class="tabQuery-table-header">' + words["action"] + '</td>';
				  html += '</tr>';
				  for(var idx in obj.vols) {
					  var vObj = obj.vols[idx];
					  html += '<tr hid="' + vObj.hid + '">';
					  html += '<td class="sn" align="center">' + (parseInt(idx) + 1) + '</td>';
					  html += '<td>' + vObj.cname + '</td>';
					  //html += '<td>' . $row1["pname"] . '</td>';
					  html += '<td>' + vObj.en_name + '</td>';
					  html += '<td>' + vObj.dharma_name + '</td>';
					  html += '<td align="center">' + vObj.work_date + '</td>';
					  html += '<td>';
					  html += vol_job_lists(vObj.hid, vObj.job_id);
					  html += '-<input class="volunteer-work" hid="' + vObj.hid + '" style="width:100px;" value="' + vObj.purpose + '" />';
					  html += '</td>';
					  html += '<td><input class="volunteer-hour" hid="' + vObj.hid + '" style="width:40px;text-align:right" value="' + vObj.work_hour + '" /></td>';
					  html += '<td>';
					  html += '<input class="volunteer-sav"  hid="' + vObj.hid + '" right="save" type="button" value="' + words["button save"] + '" />';
					  html += '<input class="volunteer-del"  hid="' + vObj.hid + '" right="delete" type="button" value="' + words["del."] + '" />';
					  html += '</td>';
					  html += '</tr>';
				  }
				  html += '<tr pid="' + obj.pid + '">';
				  html += '<td colspan="6" align="right"><b>' + words["total"] + ': </b>';
				  html += '</td>';
				  html += '<td align="right"><b>';
				  html += obj.total_hour;
				  html += '</b></td><td></td>';
				  html += '</tr>';

				  html += '<tr pid="' + obj.pid + '">';
				  html += '<td colspan="8" align="center">';
				  html += '<input class="volunteer-save" right="save"  type="button" pid="' + obj.pid + '" value="' + words["button save"] + '" />';
				  html += '</td>';
				  html += '</tr>';
			  
				  html += '</table>';
				  $("span#depart_title").html(obj.title);
				  $("div#depart-content").html(html);
				
		}

		function output_excel(pid) {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	

						$("input[name='pid']", "form[name='frm_list_excel']").val( $("#department").val() );	
						$("input[name='start_date']", "form[name='frm_list_excel']").val( $("#start_date").val() );	
						$("input[name='end_date']", "form[name='frm_list_excel']").val( $("#end_date").val() );	
						$("input[name='orderBY']", "form[name='frm_list_excel']").val( $(".tabQuery-table").attr("orderby") );	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val( $(".tabQuery-table").attr("ordersq") );	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/puti_volunteer_adjust_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() +'" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="pid" value="' + $("#department").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="start_date" value="' + $("#start_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="end_date" value="' + $("#end_date").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + $(".tabQuery-table").attr("orderby") + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + $(".tabQuery-table").attr("ordersq") + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}
		
		function search_ajax() {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  orderBY:		$(".tabQuery-table").attr("orderby"),
						  orderSQ:		$(".tabQuery-table").attr("ordersq"),
						  pid:			$("#department").val(),
						  start_date:   $("#start_date").val(),
						  end_date:   	$("#end_date").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_adjust_sort.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							jobs = req.data.jobs;
							addToListDepart(req.data);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_adjust_sort.php"
				  });
		}

		function vol_job_lists(hid, jval) {
			var html = '<select class="volunteer-job" hid="' + hid + '" style="width:100px;">';
			html += '<option value="0"></option>';
			for(var key in jobs) {
				html += '<option ' + (jobs[key]["job_id"]==jval?'selected':'') + ' value="' + jobs[key]["job_id"] + '">' + jobs[key]["job_title"] + '</option>';
			}
			html += '</select>';
			return html;
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
            <td><select id="department" style="min-width:200px;">
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

    			<span style="margin-left:20px;"></span>
				<?php echo $words["date range"]?>: 
				<?php echo $words["from"]?> <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-01");?>" /> 
                <?php echo $words["to"]?> <input style="width:80px;" id="end_date" value="<?php echo date("Y-m-d");?>" />
        	</td>
     	</tr>

        <tr>
        	<td></td>
			<td>
                <input type="button" right="save" id="btn_getData" name="btn_getData" onclick="search_ajax()" value="<?php echo $words["get history"]?>" />
                <input type="button" right="print" id="btn_output" name="btn_output" onclick="output_excel()" value="<?php echo $words["output excel"]?>" />
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
<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

</body>
</html>