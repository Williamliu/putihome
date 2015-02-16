<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.window.js"></script>
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.loading.js"></script>
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.selection.js"></script>
	
    <link type="text/css" 			href="../theme/light/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
 
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.window.css" rel="stylesheet" />
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.loading.css" rel="stylesheet" />
    
    
    
	<script language="javascript" type="text/javascript">
		var cnt = 0;
		$(function(){
			$("#wait").lwhLoading();
			$("#wait").loadShow();
			setTimeout( '$("#wait").loadHide()',3000);
			$("#alert").lwhWindow({title:"Myplugin Window"});
			$("#good").lwhWindow({title:"Myplugin Window-Good"});
			$(".ok").lwhWindow({title:"Myplugin Window-OK", top:20, maskable: true, miniable:true, maskClick:false, movable:true, initWidth:200, initHeight:null});
			$("#yes").lwhWindow({
					maskable: false,
					minWidth:	400,
					minHeight:  300,
					top: 10,
					left:15,
					offsetTo: "#ddd",
					title:"Myplugin Window-Yes", 
					movable: true,
					miniable: true,
					parkable:true,
					resizable: true,
					win_init: function(){
						$("#ddd").append("window init<br>");
					},
					win_open:function() {
						$("#ddd").append("window open<br>");
					},
					win_close:function(){
						$("#ddd").append("window close<br>");
					},
					win_max: function() {
						$("#ddd").append("window max<br>");
					},
					win_min: function() {
						$("#ddd").append("window min<br>");
					},
					move_start: function() {
						$("#ddd").append("move start<br>");
					},
					move_end: function() {
						$("#ddd").append("move end<br>");
					},
					resize_start: function() {
						$("#ddd").append("resize start<br>");
					},
					resize_end: function(){
						$("#ddd").append("resize end<br>");
					}
			});
		
			$(".ok").WShow();
			$("#yes").WShow();
			
			$("#call_alert").live("click", function(ev){
					$("#alert").WShow();
					ev.stopPropagation();
					return false;
			});
			$(document).disableSelect({except:"#yes"});	
		});
		
		function yes_show() {
			$("#yes").WShow();
		}
		function yes_hide() {
			$("#yes").WHide();
		}
		
		function uddd() {
			$("#ddd").empty();
			cnt = 0;
		}
		</script>
</head>
<body>
<input type="button" onclick="yes_show()" value="Show Yes" style="position:relative; top:1500px; left:200px;" /><br />
<input type="button" onclick="yes_hide()" value="Hide Yes" style="position:relative; top:1500px; left:300px;" /><br />

<div class="lwhWindow ok" style="width:600px; height:300px;">
<div>
<span style="color:red;font-size:12px;">hello world</span><br />
you are better
</div>
<div class="lwhWindow-content lwhWindow-vscroll  lwhWindow-resize" style="height:120px;">
dfjasdkfjls kasdjflksadjl
</div>
<div style="width:200px; height:300px; border:1px solid green; background-color:#30C;"></div>
</div>

<div class="lwhWindow ok">
<div>
<span style="color:red;font-size:12px;">hello world</span><br />
you are better
</div>
<div style="width:200px; height:300px; border:1px solid green; background-color:#30C;"></div>
</div>

<div id="alert" class="lwhWindow">
<div>
djfkalsdj<br />
djfklasdkl<br />
dkjfalksd;<br />
jdsfk lasjdklfjasdklf jalskdjflaksjd flkajsldkfjalsdjflkas kasdjfklasdjflk kasdjfklasdjlk sadkfjklasdjsa jaskdjfklasj askdjfklasd asdfjkdasklfs
</div>
</div>

<div id="good" class="lwhWindow">
djfkalsdj<br />
djfklasdkl<br />
dkjfalksd;<br />
jdsfk lasjdklfjasdklf jalskdjflaksjd flkajsldkfjalsdjflkas kasdjfklasdjflk kasdjfklasdjlk sadkfjklasdjsa jaskdjfklasj askdjfklasd asdfjkdasklfs
</div>

<div id="yes" class="lwhWindow lwhWindow-bgColor1">
<div id="yyy" class="lwhWindow-content lwhWindow-vscroll lwhWindow-resize" style="height:120px;">
djfkalsdj<br />
djfklasdkl<br />
dkjfalksd;<br />
djfklasdkl<br />
dkjfalksd;<br />
djfklasdkl<br />
dkjfalksd;<br />
djfklasdkl<br />
dkjfalksd;<br />
djfklasdkl<br />
dkjfalksd;<br />
djfklasdkl<br />
dkjfalksd;<br />
djfklasdkl<br />
dkjfalksd;<br />
jdsfk lasjdklfjasdklf jalskdjflaksjd flkajsldkfjalsdjflkas<br />
kasdjfklasdjflk kasdjfklasdjlk sadkfjklasdjsa jaskdjfklasj askdjfklasd asdfjkdasklfs
</div>
<div id="yyy11" class="lwhWindow-resize" style="display:block; width:200px; height:150px; background-color:#0C9; border:1px solid black;"></div>
<input id="call_alert" type="button" value="show alert" />
</div>

<div id="ddd" style="background:#cccccc; color:red; position:absolute; border:1px solid yellow; padding:20px; display:block; width:600px; height:400px; position:relative; left:300px; top:20px; overflow:auto;">
</div>
<input type="button" onclick="uddd()" value="clear" />
<div id="wait" class="lwhLoading"></div>
</body>
</html>