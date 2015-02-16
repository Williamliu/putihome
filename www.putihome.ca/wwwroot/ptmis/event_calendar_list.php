<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,70";
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
		<title>Bodhi Meditation Event List</title>

		<?php include("admin_head_link.php"); ?>
		
		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
        
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />
        
		
        <script language="javascript" type="text/javascript">
		var cal;
		$(function(){
			  cal = new LWH.CALENDAR();
		  	  ///////////////////////////////////////////////////////////////
			  $("#start_date, #end_date").datepicker({ 
								dateFormat: 'yy-mm-dd',  
								showOn: "button",
								buttonImage: "../theme/blue/image/icon/calendar.png",
								buttonImageOnly: true  
							});

		
			  list_event();
		});
		
		function list_event() {
		  	$("#wait").loadShow();
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view",
					
					start_date: $("#start_date").val(),
					end_date: 	$("#end_date").val(),
					class_id:	$("#class_id").val(),
					sch_status:	$("#sch_status").val()
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
				  	$("#wait").loadHide();
					alert("Error (event_calendar_list_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
				  	$("#wait").loadHide();
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						jsonToHTML(req.data.sites);
					}
				},
				type: "post",
				url: "ajax/event_calendar_list_select.php"
			});
		}
		

		function jsonToHTML( sitesObj ) {
			var sss =[];
			sss[0] = words["inactive"];
			sss[1] = words["active"];
			sss[2] = words["open"];
			sss[9] = words["closed"];

			var html = '';
			html += '<ul id="lwhT" class="lwhTree">';
			for(var key0 in sitesObj) {
				var osite = sitesObj[key0];
				html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
				html += '<span class="click" style="color:#840F71; font-size:16px; font-weight:bold;">' + words[osite.title.toLowerCase()] + ' <span style="font-weight:normal;">{ ' + osite.count + ' ' + words["groups"] + ' }</span></span>'; 
				html += '<ul class="lwhTree">';
				var cnt0 = 0;
				for(var key1 in osite.branchs) {
					cnt0++;
					var obranch = osite.branchs[key1];
					html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
					html += '<span class="click" style="color:#F44C09; font-size:12px; font-weight:bold;">' +   words[obranch.title.toLowerCase()] + ' <span style="font-weight:normal;">{ ' + obranch.count + ' ' + words["classes"] + ' }</span></span>'; 
					html += '<ul class="lwhTree">';
					
					var cnt1 = 0;
					for(var key2 in obranch.events) {
						cnt1++;
						var oevent = obranch.events[key2];
						html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
						
						if(oevent.status==0) ecss = '#BBC101;'; 
						if(oevent.status==1) ecss = '#000000;';
						if(oevent.status==2) ecss = '#F92AC9;'; 
						if(oevent.status==9) ecss = '#888888;'; 
					
						html += '<span style="color:' + ecss + '">' + oevent.title + '</span> <span style="color:blue;">' + oevent.event_date + '</span>';
						html += '<span style="color:green;"> [' + words["status"] + ': ' + '<span style="color:' + ecss + '">' + sss[oevent.status] + '</span>]</span>';
						html += '<ul class="lwhTree">';
						
						var cnt2 = 0;
						for(var key3 in oevent.event_dates) {
							cnt2++;
							var odate = oevent.event_dates[key3];
							html += '<li class="node"><s class="node-line"></s></s>';
							html += '<span style="color:#000000;">' + cnt2 + ') ' + 
									'<span style="color:blue;">' + odate.event_date + '</span> - ' +
									'<span style="color:orange;">' + odate.event_day + '</span> - ' +
									'<span style="color:green;">' + odate.event_time + '</span> ' +
									'<span style="color:' + ecss + '">' + odate.title + '</span></span>';
							html += '</li>';								
						}
						html += '</ul>';
						html += '</li>';
					}
					html += '</ul>';
					html += '</li>';
				}
				html += '</ul>';
				html += '</li>';
			}
			html += '</ul>';
			$("#calendar_edit").html(html);
			$("#lwhT").lwhTree();
		}

        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <span style="font-size:14px; font-weight:bold; margin-left:10px;"><?php echo $words["date range"]?>: </span>
    <?php echo $words["from"]?> <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d");?>" /> 
    <?php echo $words["to"]?> <input style="width:80px;" id="end_date" value="" />
    <span style="font-size:14px; font-weight:bold; margin-left:10px;">
    <?php echo $words["filter"]?>: 
    </span>
    <select id="class_id" name="class_id" style="width:250px;">
    	<?php 
			$query_cls 	= "SELECT a.*, c.title as site_desc  FROM puti_class a 
							INNER JOIN puti_sites c ON (a.site = c.id) 
							WHERE a.deleted <> 1 AND a.site IN " . $admin_user["sites"] . " AND a.branch IN " . $admin_user["branchs"] .
							"ORDER BY a.site, a.branch";	
			$result_cls = $db->query($query_cls);
			echo '<option value=""></option>';
			while( $row_cls = $db->fetch($result_cls) ) {
				echo '<option value="' . $row_cls["id"] . '">' . cTYPE::gstr($words[strtolower($row_cls["site_desc"])]) . ' - ' . cTYPE::gstr($row_cls["title"]) . '</option>';
			}
		?>
    </select>

    <span style="font-size:14px; font-weight:bold; margin-left:10px;">
    <?php echo $words["status"]?>: 
    </span>
    <select id="sch_status" name="sch_status">
		<option value=""></option>
        <option value="0"><?php echo $words["inactive"];?></option>
        <option value="1"><?php echo $words["active"];?></option>
        <option value="2"><?php echo $words["open"];?></option>
        <option value="9"><?php echo $words["closed"];?></option>
    </select>

    <input type="button" id="btn_search" right="view" onclick="list_event()" value="<?php echo $words["search"]?>" /> 
    <br /><br /> 
    <span style="font-size:14px; font-weight:bold; margin-left:10px;"><?php echo $words["bodhi meditation classes"]?></span>
	<div id="calendar_edit" style="padding:5px; min-height:420px;">
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>