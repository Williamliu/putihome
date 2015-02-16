<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <title>JQuery Main Menu Sample</title>
	<script type="text/javascript" src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.mmenu.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.mmenu.css" rel="stylesheet" />
    
    <script type="text/javascript" language="javascript">
		$(function(){
			$(".lwhMMenu").lwhMMenu();
		});
    </script>
</head>
<body style="padding:1px;">
<br />
<br />
<ul class="lwhMMenu" style="width:1000px; margin:auto;">
	<li class="lwhMMenu-li">
		<a class="lwhMMenu-a">主页</a>
    	<div class="lwhMMenu-div">
        	<div class="lwhMMenu-white">
        	good morning<br />
            1111111111<br />
        	</div>
        </div>
    </li>
	<li class="lwhMMenu-li">
		<a class="lwhMMenu-a">产品介绍</a>
       	<div class="lwhMMenu-div">
        	good morning<br />
            222222222222<br />
        </div>
    </li>
	<li class="lwhMMenu-li">
		<a class="lwhMMenu-a">公司介绍</a>
    	<div class="lwhMMenu-div">
        	good morning<br />
            333333333333<br />
        </div>
    </li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">联系我们</a></li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">活动安排</a></li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">日常事物</a></li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">健康问答</a></li>
</ul>
<br />
<br />
<ul class="lwhMMenu">
	<li class="lwhMMenu-li">
		<a class="lwhMMenu-a">HELLO11</a>
    	<div class="lwhMMenu-div">
        	good morning<br />
            44444<br />
        </div>
    </li>
	<li class="lwhMMenu-li">
		<a class="lwhMMenu-a">HELLO22</a>
       	<div class="lwhMMenu-div">
        	good morning<br />
            555555<br />
        </div>
    </li>
	<li class="lwhMMenu-li">
		<a class="lwhMMenu-a">HELLO33</a>
    	<div class="lwhMMenu-div">
        	good morning<br />
            666666<br />
        </div>
    </li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">HELLO44</a></li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">HELLO55</a></li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">HELLO66</a></li>
	<li class="lwhMMenu-li"><a class="lwhMMenu-a">HELLO77</a></li>
</ul>

</body>
</html>