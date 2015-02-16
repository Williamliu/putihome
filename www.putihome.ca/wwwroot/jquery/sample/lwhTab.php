<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <title>JQuery Main Menu Sample</title>
	<script type="text/javascript" src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.tab.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.tab.css" rel="stylesheet" />
    
    <script type="text/javascript" language="javascript">
		$(function(){
			$("#d1").lwhTab({height:200});
			$("#d2").lwhTab({trigger:"mouseover", tabsn:3});
		});
    </script>
</head>
<body style="padding:1px;">
<br />
<br />
<div id="d1" class="lwhTab" style="width:600px;">
    <ul>
        <li class="selected">
            HELLO11
        </li>
        <li>
            HELLO22
        </li>
        <li>
            HELLO33
        </li>
        <li>HELLO44</li>
        <li>HELLO55</li>
        <li>HELLO66</li>
    </ul>
    <div class="lwhTab-content">
    1111111111
    </div>
    <div class="lwhTab-content">
    2222222222
    </div>
    <div class="lwhTab-content">
    3333333333
    </div>
    <div class="lwhTab-content">
    4444444444
    </div>
    <div class="lwhTab-content">
    dfjasldfj dskfjdsklajf dsjfkljdsklfjlskdjf dsj dsklfjldskj sdkljfkldsajlkj kdjfkdsjklj klsdjfkldsjkl asdkfjlkdsj
    sdkfjlskadjflkadsjl askldfjkladsjflk klasjdfkljadsfkl sadjklfjaskldfj klas skajflksadjfl adsjfkljas dfkljasdl
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    </div>
    <div class="lwhTab-content">
    6666666666
    </div>
</div>

<br />
<br />
<div id="d2" class="lwhTab" style="width:600px;">
    <ul>
        <li class="selected">
            HELLO11
        </li>
        <li>
            HELLO22
        </li>
        <li>
            HELLO33
        </li>
        <li>HELLO44</li>
        <li>HELLO55</li>
        <li>HELLO66</li>
    </ul>
    <div class="lwhTab-content">
    1111111111
    </div>
    <div class="lwhTab-content">
    2222222222
    </div>
    <div class="lwhTab-content">
    3333333333
    </div>
    <div class="lwhTab-content">
    4444444444
    </div>
    <div class="lwhTab-content">
    dfjasldfj dskfjdsklajf dsjfkljdsklfjlskdjf dsj dsklfjldskj sdkljfkldsajlkj kdjfkdsjklj klsdjfkldsjkl asdkfjlkdsj
    sdkfjlskadjflkadsjl askldfjkladsjflk klasjdfkljadsfkl sadjklfjaskldfj klas skajflksadjfl adsjfkljas dfkljasdl
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    5555555555<br />
    </div>
    <div class="lwhTab-content">
    6666666666
    </div>
</div>

</body>
</html>