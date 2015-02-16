<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <title>JQuery Main Menu Sample</title>
	<script type="text/javascript" src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../min/jquery.zclip/jquery.zclip.min.js"></script>

    
    <script type="text/javascript" language="javascript">
		$(function(){
			    $("#btn_copy").zclip({
    				path: "/js/ZeroClipboard.swf",
    				copy: function(){
        				return $("#copyfrom").val();
        			}
    			});
		});
    </script>
</head>
<body style="padding:1px;">
<br />
<br />
<textarea id="copyfrom" style="width:300px; height:60px;"></textarea><br />
<input type="button" id="btn_copy" value="COPY" /><br />
<textarea id="copyto" style="width:300px; height:60px;"></textarea><br />

</body>
</html>