<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <title>PLAY MPE® DIRECT TO WEB</title>
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.manu.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.manu.css" rel="stylesheet" />
    <script type="text/javascript" language="javascript">
		$(function(){
			$(".lwhPMenu").lwhManu();
		});
    </script>
</head>
<body scroll="no" style="padding:1px;">
<div style="width:800px;">
<ul class="lwhPMenu">
	<a mid="m1" pmid="0" class="mmenu">MENU ONE</a>
	<a mid="m2" pmid="0" class="mmenu">MENU TWO</a>
	<a mid="m3" pmid="0" class="mmenu">MENU THREE</a>
	<a mid="m3" pmid="0">帮助信息</a>
</ul>

<ul pmid="m1" class="lwhSMenu">
    <li mid="m11">MENU ONE</li>
    <li mid="m12">MENU TWO</li>
    <li mid="m13" class="smenu">MENU THREE</li>
    <li mid="m14" class="smenu">MENU FOUR</li>
    <li mid="m15">MENU FIVE</li>
    <s></s>
</ul>

<ul pmid="m13" class="lwhSMenu">
    <li mid="m111">MENU APPLE</li>
    <li mid="m112">MENU ORANGE</li>
    <li mid="m113">MENU BANANA</li>
    <li mid="m114">MENU GRAPE</li>
    <li mid="m115">MENU WATER</li>
    <s></s>
</ul>

<ul pmid="m14" class="lwhSMenu">
    <li mid="m141">MENU APPLE</li>
    <li mid="m142">MENU ORANGE</li>
    <li mid="m143">MENU BANANA</li>
    <li mid="m144">MENU GRAPE</li>
    <li mid="m145" class="smenu">MENU WATER</li>
    <s></s>
</ul>

<ul pmid="m15" class="lwhSMenu">
    <li mid="m151">MENU white</li>
    <li mid="m152">MENU black</li>
    <li mid="m153">MENU blue</li>
    <li mid="m154">MENU pink</li>
    <li mid="m155">MENU neon</li>
    <s></s>
</ul>

<ul pmid="m145" class="lwhSMenu">
    <li mid="m1451">MENU white 111</li>
    <li mid="m1452">MENU black 222</li>
    <li mid="m1453">MENU blue  333</li>
    <li mid="m1454">MENU pink  4444</li>
    <li mid="m1455"  class="smenu">MENU neon  5555</li>
    <s></s>
</ul>

<ul pmid="m1455" class="lwhSMenu">
    <li mid="m14551">MENU Monkey</li>
    <li mid="m14552">MENU SNAKE</li>
    <li mid="m14553">MENU COW</li>
    <li mid="m14554">MENU CHICKEN</li>
    <li mid="m14555">MENU GOAT</li>
    <s></s>
</ul>


<ul pmid="m2" class="lwhSMenu">
    <li mid="m21">MENU 222ONE</li>
    <li mid="m22">MENU TWO 222</li>
    <li mid="m23">MENU THREE 222</li>
    <li mid="m24">MENU FOUR2 2</li>
    <li mid="m25">MENU FIVE 222</li>
    <s></s>
</ul>

<ul pmid="m3" class="lwhSMenu">
    <li mid="m31">MENU 222ONE</li>
    <li mid="m32">MENU TWO 222</li>
    <li mid="m33">MENU THREE 222</li>
    <li mid="m34">MENU FOUR2 2</li>
    <li mid="m35">MENU FIVE 222</li>
    <s></s>
</ul>
</div>



<div id="ddd" style="display:block; width:300px; height:500px; border:1px solid blue; overflow:auto; float:right; top:50px;"></div>
</body>
</html>