<?php
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");
if($_REQUEST["agreementform_event_id"]=="") {
	header("Location: " . $CFG["http"]. $CFG["web_domain"]);
}
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$query = "SELECT b.id, b.title, b.description, c.city, c.state 
						FROM event_calendar a 
						INNER JOIN puti_sites c ON (a.site = c.id) 
						INNER JOIN (SELECT * FROM puti_agreement_lang WHERE lang = '" . $Glang . "') b 
										ON (a.agreement = b.agreement_id) 
						WHERE a.id = '" . $_REQUEST["agreementform_event_id"] . "'";
						
//echo "query: " . $query . "<br>"; 

if( $db->exists($query) ) {
	$result = $db->query($query);
	$row = $db->fetch($result);
} else {
	header("Location: " . $CFG["http"]. $CFG["web_domain"] . "/login_form.php?loginform_event_id=" . $_REQUEST["agreementform_event_id"] . "&prev_url=" . urlencode($_REQUEST["prev_url"]) );
}
//echo $query;
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
        
        <script language="javascript" type="text/javascript">
			var htmlObj = new LWH.cHTML();
			//alert("<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>");
			$(function(){
			 
				  $("#btn_submit").bind("click", function(ev) {
					  var errCode = 0;
					  var errMsg  = '<br>' + words["submit error"] + ':<br><br>';
					  
					  if( !$("#iread").is(":checked") ) {
							  errCode = 1;
							  errMsg += "<li class='error'>" + words["read agreement before submit"] + "</li><br>";  			  
					  }
	
					  if( !$("#iagree").is(":checked") ) {
							  errCode = 1;
							  errMsg += "<li class='error'>" + words["you dont read agreement"] + "</li>";  			  
					  }
					  
					  if( errCode > 0 )  {
						$(".lwhDiag-content", "#diaglog_error").html(errMsg);
						$("#diaglog_error").diagShow(); 
						return;
					  } else {
						  loginform.submit();
					  }
				  });
			  
			});
		
		function goback() {
			window.location.href = "<?php echo $_REQUEST["prev_url"];?>";
		}
        </script>

</head>
<body>
<?php 
include("public_menu_html_nohead.php");
?>
 	<a class="goto-event-calendar" href="javascript:goback();" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["go back"]?></a>
 	<a class="goto-event-calendar" href="index.php" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["main menu"]?></a>	<br />
	<div style="min-height:400px">
    <table border="0" width="100%">
        <tr>
        	<td colspan="2" align="center">
            	<span style="font-size:18px; font-weight:bold;">
                <?php echo cTYPE::gstr($row["title"]); ?>
                </span>
            </td>
        </tr>
        <tr>
        	<td colspan="2" align="left">
            	<div style="font-size:14px; padding:10px; text-align:justify; text-justify:inter-ideograph;">
					<?php echo cTYPE::gstr($row["description"]); ?>
                </div>
                <center>
                	<input type="checkbox" id="iread" name="iread" value="I have read" /><label for="iread"><b><?php echo $words["i have read"]?></b></label>
                </center>
                <br />
                <center>
                	<input type="radio" id="irefuse" 	name="agreement" value="I do not agree" /><label for="irefuse"><b><?php echo $words["i dont agree"]?></b></label>
                    <input type="radio" id="iagree" 	name="agreement" value="I agree" style="margin-left:50px;" /><label for="iagree"><b><?php echo $words["i agree"]?></b></label>
              	</center>
            </td>
        </tr>
        <tr>
        	<td class="line" colspan="2" align="center" style="padding-top:20px; padding-bottom:20px;">
                <input type="button" id="btn_submit" name="btn_submit" value="<?php echo $words["button next"]?>" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
	</div>
<?php 
include("public_footer_html_nohead.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<form name="loginform" action="<?php echo $CFG["http"] . $CFG["web_domain"] ?>/login_form.php" method="get">
	<input type="hidden" id="loginform_event_id" name="loginform_event_id" value="<?php echo $_REQUEST["agreementform_event_id"];?>" />
    <input type="hidden" name="prev_url" value="<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>" />
</form>
</body>
</html>