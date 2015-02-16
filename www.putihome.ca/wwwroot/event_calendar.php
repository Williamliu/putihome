<?php
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

		<script type="text/javascript" src="jquery/myplugin/jquery.lwh.calendar.js"></script>
		<link type="text/css" href="jquery/myplugin/css/light/jquery.lwh.calendar.css" rel="stylesheet" />
        
		<script type="text/javascript" src="jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
   
  		<script type="text/javascript" 	src="js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="theme/blue/js.lwh.table.css" rel="stylesheet" />
      
        <script language="javascript" type="text/javascript">
		var htmlObj = new LWH.cHTML();
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
							  $("#lang").val($(this).attr("lang"));
							  from_language.submit();
						  });
					  }
			  });

			  $("#diaglog_date").lwhDiag({
				  titleAlign:		"center",
				  title:			words["event detail"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			620,
				  minHH:			320,
				  zIndex:			4444,
				  btnMax:			false,
				  resizable:		false,
				  movable:			false,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				true
			  });

			  cal = new LWH.CALENDAR({
								container:	"#div_calendar",
								site:		$("#sch_site").val(),
								monthChange: function(yy,mm) {
								},
								dateClick: function(obj) {
									$("#diaglog_date").diagShow({
										diag_open: function() {
											$(".lwhDiag-content","#diaglog_date").html(dateDetail(obj));
											$("#event_id").val(obj.event_id);
											$("#register_event_id").val(obj.event_id);
										},
										diag_close: function() {
											$(".lwhDiag-content","#diaglog_date").empty();
										}
									});
								}
							});

			cal.current();
		
		
			$("#btn_registration").live("click", function(ev) {
				var eid = $(this).attr("eid");
				$("#agreementform_event_id").val(eid);
				agreementform.submit();
			});
		
		});
	
		
		function dateDetail(obj) {
			var sss = [];
			sss[0] = '<span style="color:red;">' + words["inactive"] + '</span>';
			sss[1] = '<span style="color:red;">' + words["not ready for enrollment"] + '</span>';
			sss[2] = '<span style="color:red;">' + words["open for enrollment"] + '</span>, <a id="btn_registration" href="javascript:void(0);" eid="' + obj.event_id + '" logform="' + obj.logform + '" style="cursor:pointer; text-decoration:underline; color:blue;">' + words["click here to register"] + '</a>';
			sss[9] = '<span style="color:red;">' + words["closed"] + '</span>';
			var timespan = obj.start_time?obj.start_time + (obj.end_time?'~'+obj.end_time:''):(obj.end_time?obj.end_time:'');
            var datespan = obj.start_date?obj.start_date + (obj.end_date?'~'+obj.end_date:''):(obj.end_date?obj.end_date:'');
			var html = '<table width="600" style="font-size:14px;">';

			html += '<tr><td style="font-style:italic;">' + words["class"] + ': </td>';			
			html += '<td>' + obj.title + '</td></tr>';			

			html += '<tr><td style="font-style:italic;">' + words["date"] + ': </td>';			
			html += '<td>' + obj.event_date + '  ' + cal.day_desc[cal.getWDay(obj.yy,obj.mm,obj.dd)] + ' [ ' +  datespan  + ' ]</td></tr>';			
			html += '<tr><td style="font-style:italic;">' + words["time"] + ': </td>';			
			html += '<td>' + timespan + '</td></tr>';			

			html += '<tr><td style="font-style:italic;">' + words["location"] + ': </td>';			
			html += '<td>' + words[obj.site_desc.toLowerCase()] + ' - ' + words[obj.branch_desc.toLowerCase()];
			html += ' - ' + words["event_place"] + ' : ' + words[obj.place_desc.toLowerCase()];
			html += '</td></tr>';	

			html += '<tr><td style="font-style:italic; width:80px; white-space:nowrap;" valign="top">' + words["description"] + ': </td>';			
			html += '<td valign="top">';
				html += '<table width="100%"><tr><td>';
				html += obj.description.nl2br();
				//html += '<textarea style="width:100%; height:120px; resize:none;">' + obj.description + '</textarea>';
				html += '</td></tr></table>';
			html += '</td></tr>';			
		
			html += '<tr><td style="font-style:italic;">' + words["telephone"] + ': </td>';			
			html += '<td>' + obj.site_tel + '</td></tr>';			
			html += '<tr><td style="font-style:italic;">' + words["email"] + ': </td>';			
			html += '<td>' + obj.site_email + '</td></tr>';			
			html += '<tr><td style="font-style:italic;">' + words["address"] + ': </td>';			
			html += '<td>' + obj.site_address + '</td></tr>';			
			
			html += '<tr><td><br></td><td></td></tr>';
			html += '<tr><td style="font-style:italic;">' + words["status"] + ': </td>';			
			html += '<td>' + sss[obj.event_status] + '</td></tr>';			
			html += '</table>';	
			
			return html;		
		}
		
		function site_change() {
			setCookie("puti_adminSite",$("#sch_site").val());
			$("span.site-address").hide();
			$("span.site-address[site='"+$("#sch_site").val()+"']").show();
			cal.site = $("#sch_site").val();
			cal.fresh();
		}
        </script>

</head>
<body>
<div class="main-layout" style="width:1200px;"> <!--  begin of main-layout -->
		<!-- page header -->
        <div class="main-header" style="background:url(theme/blue/image/background/main-header1200.png) 0px 0px no-repeat;">
                <div style="position:absolute; display:block; width:200px; top:10px; left:100%; margin-left: -120px;">
                      <s id="menu_lang" class="lwhDrop lwhDrop-pink">
                          <b><span class="lwhDrop-span"><?php echo $words["language"];?></span></b>
                          <div class="lwhDrop-div">
                              <div class="lwhDrop-white">
                                     <div class="lwhDrop-ban lwhDrop-ban-blue"><?php echo $words["menu_select"];?></div>
                                     <ul class="lwhDrop-items">
                                     <li class="lwhDrop-item lang <?php echo ($Glang=="cn"?"selected":"");?>" lang="cn"><?php echo $words["menu_chinese"];?></li>
                                     <li class="lwhDrop-item lang <?php echo ($Glang=="tw"?"selected":"");?>" lang="tw"><?php echo $words["menu_cntw"];?></li>
                                     <li class="lwhDrop-item lang <?php echo ($Glang!="cn"?"selected":"");?>" lang="en">English</li>
                                     </ul>
                              </div>
                          </div>
                      </s>
                </div>
               
        </div>

<?php 
//include("public_menu_html.php");
?>
    <table>
    	<tr>
        	<td>
    <a class="goto-event-calendar" href="index.php" style="width:100px; height:26px; line-height:26px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["go back"]?></a> 
	<!--<span style="font-size:16px; font-weight:bold;"><?php echo $words["please select location"]?>: </span>-->
    <select id="sch_site" name="sch_site" onchange="site_change();" style="font-size:18px; font-weight:bold; color:blue; vertical-align:middle;">
    	<?php 
			if( $_COOKIE["puti_adminSite"] == "" ) $_COOKIE["puti_adminSite"] = 1;
			$res_site = $db->query("SELECT * FROM puti_sites WHERE status = 1 ORDER BY id");
			$sites = array();
			while( $row_site = $db->fetch( $res_site ) ) {
				$sites[$row_site["id"]]["site_id"] 	= $row_site["id"];
				$sites[$row_site["id"]]["address"] 	= $row_site["address"]; 
				$sites[$row_site["id"]]["tel"] 		= $row_site["tel"]; 
				echo '<option value="' . $row_site["id"] . '"' . ($row_site["id"]==$_COOKIE["puti_adminSite"]?' selected':'') . '>' . $words[strtolower($row_site["title"])] . '</option>';
			}
		?>
    </select>
    		</td>
            <td>
    <?php 
		foreach($sites as $site) {
		 	echo '<span><span class="site-address" style="color:#840F71; display:' . ($site["site_id"]==1?'inline-block':'none'). ';" site="' . $site["site_id"] . '"><span style="font-size:14px; font-weight:normal;">' . $words["address"] . ': ' . $site["address"] . '</span></span></span>';
		}
	?>
    		</td>
         </tr>
     </table>
    <div id="div_calendar"></div>
</div> <!--  end of main-layout -->

<!-- below is out of main-layout components -->
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />
<div id="diaglog_error" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

<div class="lwhTooltip" id="tooltips">
    <div class="lwhTooltip_message" style="font-size:16px; font-weight:bold; padding-top:5px;"></div>
</div>


<div id="wait" class="lwhLoading"></div>


<form name="from_language" action="<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>" method="get">
	<input type="hidden" name="lang" id="lang" value="<?php echo $Glang;?>" />
<?php  if($_REQUEST["event_id"]!="") { ?>
   	<input type="hidden" id="footer_event_id" 	name="event_id" value="<?php echo $_REQUEST["event_id"];?>" />
<?php } ?>
<?php  if($_REQUEST["publicSession"]!="") { ?>
   	<input type="hidden" id="footer_publicSession" 	name="publicSession" value="<?php echo $_REQUEST["publicSession"];?>" />
<?php } ?>
</form>

<script type="text/javascript" language="javascript">
	$(function(){
		$("#diaglog_error").lwhDiag({
			titleAlign:		"center",
			title:			words["error message"],
			
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

		$("#tooltips").lwhTooltip();	

		$("#wait").lwhLoading({loadMsg:"WAITING..."});
	});

	function tool_tips( ss ) {
		$("#tooltips").autoTShow(ss);
	}
</script>

<div id="diaglog_date" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
	</div>
</div>

<form name="agreementform" action="<?php echo $CFG["http"] . $CFG["web_domain"] ?>/agreement_form.php" method="get">
	<input type="hidden" id="agreementform_event_id" name="agreementform_event_id" value="" />
    <input type="hidden" name="prev_url" value="<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>" />
</form>

</body>
</html>