<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,3";
include_once("website_admin_auth.php");

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
		<title>Bodhi Meditation Online Registration</title>
		
		<script type="text/javascript" 	src="../jquery/min/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" 	src="../jquery/min/jquery-ui-1.8.21.custom.min.js"></script>
        <link 	type="text/css" 		href="../jquery/theme/light/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
		
		<script type="text/javascript" 	src="../js/js.lwh.common.js"></script>
		<script type="text/javascript" 	src="../js/js.lwh.admin.auth.js"></script>

        <link 	type="text/css" 		href="../theme/blue/content.css" rel="stylesheet" />
		
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.drop.js"></script>
   		<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.drop.css" rel="stylesheet" />

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.mmenu.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.mmenu.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.diag.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.diag.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
        
	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />
        
		
        <script language="javascript" type="text/javascript">
		var admin_user_right_str = '<?php echo $admin_user_right_str;?>';
		var errObj = new LWH.cERR({ diag: "#diaglog" });

		var cal;
		$(function(){
			$("#menu_lang").lwhDrop({
				init: function(me) {
					var div_el = $("div.lwhDrop-div[dropsn='" + $(me).attr("dropsn") + "']");
					if ($("li.selected", div_el).length > 0 )
						$("span.lwhDrop-span",me).html( $("li.selected", div_el).html() );
					
					$(".lang", div_el).live("click", function(ev) {
						$("span.lwhDrop-span",me).html( $(this).html() );
						$(me).lwhDrop_close();
					});
				}
			});

			$(".lwhMMenu").lwhMMenu();

				if( $(":radio[name='therapy']:checked").val() == "Yes") 
						$("#div_therapy_yes").show();
					else 
						$("#div_therapy_yes").hide();

							
				$(":radio[name='therapy']").bind("click", function(ev) {
					if($(this).val() == "Yes") 
						$("#div_therapy_yes").show();
					else 
						$("#div_therapy_yes").hide();
					 
				});
				
			  $("#diaglog").lwhDiag({
				  titleAlign:		"center",
				  title:			"Error Message",
				  
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
				
			  cal = new LWH.CALENDAR();
			  ///////////////////////////////////////////////////////////////
		
			  list_event();
		});
		
		function list_event() {
			$.ajax({
				data: {
					admin_sess: $("input#adminSession").val(),
					admin_menu:	$("input#adminMenu").val(),
					admin_oper:	"view"
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
					alert("Error (event_calendar_edit_list.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						jsonToHTML(req.data.evt);
					}
				},
				type: "post",
				url: "ajax/event_calendar_edit_list.php"
			});
		}
		
		function jsonToHTML( evtObj ) {
			var html = '';
			html += '<ul id="lwhT" class="lwhTree">';
			for(var key in evtObj) {
				html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
				var evt_str = '<span style="color:#666666; font-size:12px; font-weight:bold;">' + evtObj[key].title + '</span> [ '  + evtObj[key].start_date + (evtObj[key].end_date?' ~ '+evtObj[key].end_date:'') + ' ]'; 
				html += evt_str;
					// dates
					var dObj = evtObj[key]["dates"];
					html += '<ul class="lwhTree">';
					for(var key1 in dObj) {
						var tObj = dObj[key1]["times"];
						if(tObj && tObj.length > 0 ) { 
							html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
							var date_str = '<span style="color:#666666;">' + dObj[key1].title + '</span> [ '  + dObj[key1].event_date + ' ' + cal.day_desc[cal.getWDay(dObj[key1].yy,dObj[key1].mm,dObj[key1].dd)] + ' ]'; 
							html += date_str;
							//times
							html += '<ul class="lwhTree">';
							for(var key2 in tObj) {
								html += '<li class="node"><s class="node-line"></s><s class="node-img"></s>';
								var time_str = '<span style="color:#666666;">' + tObj[key2].title + '</span> [ '  + tObj[key2].start_time + (tObj[key2].end_time?' ~ ' + tObj[key2].end_time:'') + ' ]'; 
								html += time_str;
								html += '</li>';
							}
							html += '</ul>';
							html += '</li>';
						} else {
							html += '<li class="node"><s class="node-line"></s><s class="node-img"></s>';
							var date_str = '<span style="color:#666666;">' + dObj[key1].title + '</span> ['  + dObj[key1].event_date + ' ' + cal.day_desc[cal.getWDay(dObj[key1].yy,dObj[key1].mm,dObj[key1].dd)] + ' ]'; 
							html += date_str;
							html += '</li>';
						}
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
<div class="main-layout">
	<div class="main-header">
            <div style="position:absolute; display:block; width:200px; top:10px; left:100%; margin-left: -120px;">
            <s id="menu_lang" class="lwhDrop lwhDrop-blue">
                <span class="lwhDrop-span">选择语言</span>
                <div class="lwhDrop-div">
                    <div class="lwhDrop-white">
                           <div class="lwhDrop-ban lwhDrop-ban-blue">选择语言</div>
                           <ul class="lwhDrop-items">
                           <li class="lwhDrop-item lang selected">简体中文</li>
                           <li class="lwhDrop-item lang">英语</li>
                           </ul>
                    </div>
                </div>
            </s>
            </div>
			<?php 
                include("admin_menu_html.php");
            ?>
	</div>
    <br />
    <span style="font-size:14px; font-weight:bold;">Bodhi Meditation Event List</span><br />
	<div id="calendar_edit" style="padding:5px;">
    </div>
</div>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<div id="diaglog" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
	</div>
</div>

<input type="hidden" id="adminSession" name="adminSession" value="<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>" />
</body>
</html>