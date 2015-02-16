<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <title>JQuery Main Menu Sample</title>
	<script type="text/javascript" src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/light/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="/js/js.lwh.common.js"></script>

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.diag.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.diag.css" rel="stylesheet" />
    
    <script type="text/javascript" language="javascript">
		$(function(){
			$("#d1").lwhDiag({
								maskable:	true,
								movable:	true,
								resizable:  true,
								pin:		true,
								btnMax:		true,
								minWW:		200,
								minHH:		300,
								bgColor:	"#aaf0d1",
								ttColor:	"#fbec5d",
									//resize_start:function(){alert("resize 111");},
									diag_init: function() { alert("darg init 111"); },
									diag_open: function() { alert("open 1111"); },
									diag_close: function() { alert("close 111");}

								
							});
			$("#d2").lwhDiag({
								btnMax:		true,
								minWW:		50,
								minHH:		100,
								offsetTo:	"#hello", 
								top: 		$("#hello").outerHeight()
							});

			$("#d3").lwhDiag({
								btnMax:		true,
								offsetTo:	"#hello",
								minWW:		150,
								minHH:		150,
								left:		100,
								maskable:	false,
								resizable:  true,
								movable:	true, 
								top: 		$("#hello").outerHeight()
							});
			$("#d4").lwhDiag({
								btnMax:		true,
								offsetTo:	"#hello",
								top:		30,
								left:		700,
								maskable:	false,
								movable:	true, 
								top: 		$("#hello").outerHeight()
							});

			$("#d3, #d4").diagShow();
			$("#ddd").live("click", function(ev) {
				show2();
				ev.stopPropagation();
				return false;
			});
		});
		function show1() {
			$("#d1").diagShow({
									/*
									title:"Good Morning", 
									resize_start:function(){alert("resize 000");},
									drag_init: function() { alert("darg init 000"); },
									diag_open: function() { alert("open 0000"); },
									diag_close: function() { alert("close 0000");}
									*/
							});
			
			return false;
		}
		function show2() {
			$("#d2").diagShow({
									title:"Dialog D2 - Good Afternoon"
				});
		}

		function show3() {
			$("#d3").diagShow({
									title:"Dialog D3- Good Day",
									
									//resize_start:function(){alert("resize 333");},
									drag_init: function() { alert("darg init 333"); },
									diag_open: function() { alert("open 333"); },
									diag_close: function() { alert("close 333");}
									
				});
		}
    </script>
</head>
<body style="padding:1px;">
<br />
<span style="font-size:14px;">(001)604-609-7397</span><br />
<span style="font-size:14px;">william.liu@plaympe.com</span>

<br />

<input id="hello" value="Offet Baseline here" /><input type="button" onclick="show1()" value="D1 Show" /><input type="button" onclick="show2()" value="D2 Show" /><input type="button" onclick="show3()" value="D3 Show" />

<div id="d1" class="lwhDiag" style="width:600px;">
    <div class="lwhDiag-head">
    	<div class="lwhDiag-title">标题在这里 Dialog D1</div>
    </div>
	<div class="lwhDiag-content" style="height:300px;">
    	<input id="ddd" type="button"  value="show d2" />
    </div>
</div>

<br />
<br />
<br />
<br />
<div id="d2" class="lwhDiag" style="width:600px;">
    <div class="lwhDiag-head">
    	<div class="lwhDiag-title">Dialog D2</div>
    </div>
	<div class="lwhDiag-content" style="height:300px;">
    	<input type="button" onclick="show1()" value="show d1" />
    </div>
</div>

<div id="d3" class="lwhDiag" style="width:600px;">
    <div class="lwhDiag-head">
    	<div class="lwhDiag-title">Dialog D3</div>
    </div>
	<div class="lwhDiag-content" style="height:200px;">
     Dialog Three
     <div style="background-color:blue; height:300px;"></div>
    </div>
</div>
<div id="d4" class="lwhDiag" style="width:600px;">
    <div class="lwhDiag-head">
    	<div class="lwhDiag-title">Dialog D4</div>
    </div>
	<div class="lwhDiag-content" style="height:300px;">
    </div>
</div>

</body>
</html>