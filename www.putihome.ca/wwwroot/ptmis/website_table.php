<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="800,50";
include_once("website_admin_auth.php");
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
		<title>Bodhi Meditation My Account</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		$(function(){
		});
	
		function save_ajax() {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",

						  admin_id: 	$("input#admin_id").val(),
						  first_name: 	$("input#first_name").val(),
						  last_name: 	$("input#last_name").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  city: 		$("input#city").val(),
						  user_name: 	$("input#user_name").val(),
						  email: 		$("input#email").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (website_admins_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$("#admin_last_updated").html(req.data.last_updated);
						  }
					  },
					  type: "post",
					  url: "ajax/website_myaccount_save.php"
				  });
		}

    	</script>
</head>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
		<span><?php echo $words["table name"]?>: </span><input type="text" style="width:200px;" id="table_name" name="table_name" value="" />
        <input type="button" id="btn_output" onclick="output_ajax();" value="<?php echo $words["output excel"]?>" /><br />
        
	</div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>