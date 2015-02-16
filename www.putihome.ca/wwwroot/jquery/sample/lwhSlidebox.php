<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.5.custom.min.js"></script>
	<link type="text/css" 			href="../themes/base/jquery.ui.all.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.slidebox.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.slidebox.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		$(function(){
				 $("#load").lwhSlidebox({title:"QUICK SEARCH", iconClose:true, inBound:false,
										box_init: function(){ $("#msg").append("load init<br>"); },
										box_open: function() { $("#msg").append("load open<br>"); },
										box_close: function() { $("#msg").append("load close<br>");}
										});
				 $("#wait").lwhSlidebox({title:"ADVANCED SEARCH", offsetTo: "#kkk", inBound:true,
										box_init: function(){ $("#msg").append("wait init<br>"); },
										box_open: function() { $("#msg").append("wait open<br>"); },
										box_close: function() { $("#msg").append("wait close<br>"); },
										trigger: "#sss"
										});
				 $("#load").boxShow();
		})
		
		function ttt() {
			 $("#load").boxAutoShow();
		}
		function ggg() {
		}
		
		function hhh() {
				 $("#wait").boxHide();
		}
		
		function clear_msg() {
			$("#msg").empty();
		}
		
        </script>
</head>
<body>
<input type="button" id="sss" onclick="ttt();" value="Show Yes" />
<input type="button" onclick="hhh()" value="Hide Yes" /><br />

<div id="wait" class="lwhSlidebox">
	dkfjkadsjfds<br />
	<div class="lwhSlidebox-content lwhSlidebox-vscroll lwhSlidebox-bgColor2" style="display:block; width:300px; height:200px;">
    sdlfjasdlkkfl;s asldflsad
    <br />
    asdlkfjaksldfjlksd <br />
    sdjfklsdajfklasdjlfsadjfklsdjalfj sadlkjflaskdjlfkasd j kjdfklasjdklfjsda ljaklsjflsadjfkls 
    sdlfjasdlkkfl;s asldflsad
    <br />
    asdlkfjaksldfjlksd <br />
    sdjfklsdajfklasdjlfsadjfklsdjalfj sadlkjflaskdjlfkasd j kjdfklasjdklfjsda ljaklsjflsadjfkls 
    sdlfjasdlkkfl;s asldflsad
    <br />
    asdlkfjaksldfjlksd <br />
    sdjfklsdajfklasdjlfsadjfklsdjalfj sadlkjflaskdjlfkasd j kjdfklasjdklfjsda ljaklsjflsadjfkls 
    sdlfjasdlkkfl;s asldflsad
    <br />
    asdlkfjaksldfjlksd <br />
    sdjfklsdajfklasdjlfsadjfklsdjalfj sadlkjflaskdjlfkasd j kjdfklasjdklfjsda ljaklsjflsadjfkls 
    sdlfjasdlkkfl;s asldflsad
    <br />
    asdlkfjaksldfjlksd <br />
    sdjfklsdajfklasdjlfsadjfklsdjalfj sadlkjflaskdjlfkasd j kjdfklasjdklfjsda ljaklsjflsadjfkls 
	</div>
</div>
<br />
<div id="load" class="lwhSlidebox"  style="width:400px; height:300px;"></div>

<div style="position:absolute; left:800px; top:10px; background-color:#eeeeee;">
	<input  id="kkk" type="button" onclick="clear_msg();" value="message clear" /><br />
	<div id="msg" style="color:red; background-color:#eeeeee; border:1px solid yellow; padding:10px; display:block; width:400px; height:600px; overflow:auto;"></div>
</div>

</body>
</html>