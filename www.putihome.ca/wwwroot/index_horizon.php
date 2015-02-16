<?php
//phpinfo();
//header("Location: http://www.putihome.ca");
//exit();
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

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
		<title>Bodhi Meditation Online Registration</title>
		<?php include("web_head_link.php"); ?>    

	 	<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

		<script type="text/javascript" src="jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
         
  		<script type="text/javascript" 	src="js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="theme/blue/js.lwh.table.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var htmlObj = new LWH.cHTML();
		$(function(){
			  ///////////////////////////////////////////////////////////////
			  list_event();
			  $("a.event-signin").live("click", function(ev) {
				  	var eid = $(this).attr("eid");
					$("#agreementform_event_id").val(eid);
					agreementform.submit();
			  });
		});
		
		function list_event() {
			$.ajax({
				data: {
					site: 	'<?php echo $_REQUEST["site"];?>',
					branch: '<?php echo $_REQUEST["branch"];?>'
				},
				dataType: "json",  
				error: function(xhr, tStatus, errorTh ) {
					alert("Error (index_event_list.php): " + xhr.responseText + "\nStatus: " + tStatus);
				},
				success: function(req, tStatus) {
					if( req.errorCode > 0 ) { 
						errObj.set(req.errorCode, req.errorMessage, req.errorField);
						return false;
					} else {
						jsonToHTML(req.data.sites);
					}
				},
				type: "post",
				url: "ajax/index_event_list.php"
			});
		}
		
		function jsonToHTML( sitesObj ) {
			var html = '';
			html += '<ul id="lwhT" class="lwhTree">';
			for(var key0 in sitesObj) {
				var osite = sitesObj[key0];
				html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
				html += '<span class="click" style="color:#840F71; font-size:16px; font-weight:bold;">' + words[osite.title.toLowerCase()] + '<span style="font-size:12px; margin-left:10px; font-weight:normal;">[ ' + words["address"] + ': ' + osite.address + ' ]</span><span style="font-size:12px; margin-left:10px; font-weight:normal;">[ ' + words["tel"] + ': ' + osite.tel + ' ]</span></span>'; 
				
				//html += '<span class="click" style="color:#840F71; font-size:16px; font-weight:bold;">' + osite.title + ' <span style="font-weight:normal;">{ ' + osite.count + ' Groups }</span></span>'; 
				html += '<ul class="lwhTree">';
				var cnt0 = 0;
				for(var key1 in osite.branchs) {
					cnt0++;
					var obranch = osite.branchs[key1];
					html += '<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>';
					html += '<span class="click" style="color:#F44C09; font-size:12px; font-weight:bold;">' +  words[obranch.title.toLowerCase()] + ' <span style="font-weight:normal;">{ ' + obranch.count + ' ' + words["classes"] + ' }</span></span>'; 
					html += '<ul class="lwhTree">';
					
					var cnt1 = 0;
					for(var key2 in obranch.events) {
						cnt1++;
						var oevent = obranch.events[key2];
						html += '<li class="nodes nodes-close"><s class="node-line"></s><s class="node-img node-img-group"></s>';
						html += '<span class="click" style="color:#000000;">' + oevent.title + '</span> <span style="color:blue;">' + oevent.event_date + '</span>';
						html += '<a class="lwhBtn H18 H18-salmon event-signin" style="margin-left:20px;"  eid="' + oevent.event_id + '" logform="' + oevent.logform + '">' + words["button sign in"] + '<s></s></a>';
						html += '<ul class="lwhTree">';
						
						var cnt2 = 0;
						for(var key3 in oevent.event_dates) {
							cnt2++;
							var odate = oevent.event_dates[key3];
							html += '<li class="node"><s class="node-line"></s></s>';
							html += '<span style="color:#000000;">' + cnt2 + ') ' + 
									'<span style="color:blue;">' + odate.event_date + '</span> ' +
									'<span style="color:orange;">' + odate.event_day + '</span> - ' +
									'<span style="color:green;">' + odate.event_time + '</span> ' +
									odate.title + '</span>';
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
include("public_menu_html.php");
?>
    
    <br />
    <span style="font-size:14px; font-weight:bold; margin-left:10px; color:#666666;"><?php echo $words["bodhi meditation upcoming classes"]?></span>
    <span style="font-size:12px; font-weight:normal; color:#666666;"> - <?php echo $words["please click sign in"]?></span>
    <br />
	<div id="calendar_edit" style="padding:5px; min-height:420px;"></div>

<?php 
include("public_footer_html.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<form name="agreementform" action="<?php echo $CFG["http"] . $CFG["web_domain"] ?>/agreement_form.php" method="get">
	<input type="hidden" id="agreementform_event_id" name="agreementform_event_id" value="" />
    <input type="hidden" name="prev_url" value="<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>" />
</form>
</body>
</html>