<?php
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");
//phpinfo();
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
		
		<script type="text/javascript" 	src="jquery/min/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" 	src="jquery/min/jquery-ui-1.8.21.custom.min.js"></script>
        <link 	type="text/css" 		href="jquery/theme/light/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

		<script type="text/javascript" 	src="js/js.lwh.common.js"></script>

		<script type="text/javascript" 	src="jquery/min/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.zoom.js"></script>
		<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.zoom.css" rel="stylesheet" />
		
        <script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.upload.js"></script>
        <link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />
    
        <script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.diag.js"></script>
        <link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.diag.css" rel="stylesheet" />
       
        <script language="javascript" type="text/javascript">
		var aj = null;
	
		$(function(){
	  		$(".lwhZoom").lwhZoom();
			
			aj = new LWH.AjaxUpload({
				url:		"ajax/lwhUpload_save.php", 
				btnUpload:	".lwhZoom-button-upload", 
				btnImgCut:	".lwhZoom-button-cut",
				btnImgDel:	".lwhZoom-button-delete",
				imgEL:		"#mmm",
				multiple:	true,
				start: 		function() {
					aj.setID(105);
					//alert($(aj.settings.button).attr("sn") + ":" + aj.settings.ref_id);
					aj.cleanLog();
				},
				uploadDone: function(req) {
					//$("#hello").attr("src", req.data.fileUrl);
					$("#mmm").attr("src",  "ajax/lwhUpload_image.php?ts=" + req.data.ts + "&size=large&img_id=" + req.data.ref_id);
					//alert("code:" + req.errorCode + " url:"  + req.data.uid + ":" + req.data.fileurl);
				},
				imgCutDone: function(req) {
					$("#mmm").attr("src",  "ajax/lwhUpload_image.php?ts=" + req.data.ts + "&size=small&img_id=" + req.data.ref_id);
					$("#mmm").attr("width",120).css({"left":"0px", "top":"0px", "width":"120px"});
				},
				imgDelDone: function(req) {
					$("#mmm").attr("src",  "ajax/lwhUpload_image.php?ts=" + req.data.ts + "&size=small&img_id=" + req.data.ref_id);
					$("#mmm").attr("width",120).css({"left":"-3px", "top":"0px", "width":"120px"});
				},

			});
			
			
			
		});
	    </script>

</head>
<body>
   		
            <div class="lwhZoom">
                    <img id="mmm" src="me_big1.jpg" width="120" maxwidth="2048" />
            </div>
        
        <br />
        <br />
		<br />
        <a id="ddd">Download</a>
</body>
</html>