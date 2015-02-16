<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../code/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.5.custom.min.js"></script>
	<link type="text/css" 			href="../themes/base/jquery.ui.all.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.loading.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.loading.css" rel="stylesheet" />

    
	<script language="javascript" type="text/javascript">
		$(function(){
				 $("#wait, #load").lwhLoading();
				 $("#wait").loadShow({loadMsg:"WAITING..."});
				 //$("#load").loadShow({loadMsg:"Loading..."});
				 
				setTimeout("ttt()", 3000);
		})
		
		function ttt() {
				 $("#wait").loadHide();
				 setTimeout("ggg()", 3000);
		}
		
		function ggg() {
				 $("#load").loadShow({loadMsg:"LOADING..."});
				 setTimeout("hhh()", 3000);
		}
		
		function hhh() {
				 $("#load").loadHide();
		}
		</script>
</head>
<body>
<input type="button" onclick="yes_show()" value="Show Yes" style="position:relative; top:1500px; left:200px;" /><br />
<input type="button" onclick="yes_hide()" value="Hide Yes" style="position:relative; top:1500px; left:300px;" /><br />

<div id="wait" class="lwhLoading"></div>
<br />
<div id="load" class="lwhLoading"></div>

<div style="position:absolute; left:800px; top:10px; background-color:#eeeeee;">
	<input type="button" onclick="clear_msg();" value="message clear" /><br />
	<div id="msg" style="color:red; background-color:#eeeeee; border:1px solid yellow; padding:10px; display:block; width:400px; height:600px; overflow:auto;"></div>
</div>

</body>
</html>