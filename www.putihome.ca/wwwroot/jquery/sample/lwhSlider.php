<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<!-- Start slider section -->
	<link rel="stylesheet" type="text/css" href="../plugin/jslider/css/jslider_style.css" />
	<script type="text/javascript" src="../plugin/jslider/jquery.js"></script>
    <script type="text/javascript" src="../plugin/jslider/jslider_class.js"></script>
    <script type="text/javascript" src="../plugin/jslider/jslider_init.js"></script>
    <script language="javascript" type="text/javascript">
		function eff_change(a) {
			$("#myslider").wowSlider({effect:a});
		}
    </script>
	<!-- End slider section -->
</head>
<body style="background-color:#D3D3D3">
	<!-- Start slider content section -->
	<!-- Div class is mandatory  -->
    <div class="jslider" id="myslider">
        <div class="ws_images">
            <ul>
                <li><img src="data/images/1.jpg" alt="" title="Monday" id="1" width="800"/></li>
                <li><img src="data/images/2.jpg" alt="" title="Tuesday" id="2" width="800" /></li>
                <li><img src="data/images/3.jpg" alt="" title="Wednesday" id="3" width="800" /></li>
                <li><img src="data/images/4.jpg" alt="" title="Thursday" id="4" width="800" /></li>
                <li><img src="data/images/5.jpg" alt="" title="Friday" id="5" width="800" /></li>
                <li><img src="data/images/6.jpg" alt="" title="Saturday" id="6" width="800" /></li>
                <li><img src="data/images/7.jpg" alt="" title="Sunday" id="7" width="800" /></li>
            </ul>
        </div>
        <div class="ws_bullets">
            <div>
                <a href="#" title="aaa"><img src="data/tooltips/1.jpg" alt=""/>1</a>
                <a href="#" title="bbb"><img src="data/tooltips/2.jpg" alt=""/>2</a>
                <a href="#" title="ccc"><img src="data/tooltips/3.jpg" alt=""/>3</a>
                <a href="#" title="ddd"><img src="data/tooltips/4.jpg" alt=""/>4</a>
                <a href="#" title="ddd"><img src="data/tooltips/5.jpg" alt=""/>5</a>
                <a href="#" title="ddd"><img src="data/tooltips/6.jpg" alt=""/>6</a>
                <a href="#" title="ddd"><img src="data/tooltips/7.jpg" alt=""/>7</a>
            </div>
        </div>
        <div class="ws_shadow"></div>
    </div>
    <br />
	<!-- End slider content section -->
</body>
</html>

	<!--
    Effect: <select id="eff" onchange="eff_change(this.value);">  basic, basic_linear, blinds, blur, blast, book fade, cube,  domino, fly
    			<option value="basic">Basic</option>
    			<option value="basic_linear">Basic Linear</option>
    			<option value="blinds">Blinds</option>
    			<option value="blur">Blur</option>
    			<option value="blast">Blast</option>
    			<option value="Book">Book</option>
    			<option value="fade" selected>Fade</option>
    			<option value="cube">Cube</option>
    			<option value="domino">Domino</option>
    			<option value="fly">Fly</option>
            </select>
    -->
