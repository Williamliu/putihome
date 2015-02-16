<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Tabber</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.vtabber.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.vtabber.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		$(function(){
			$("#d1, #d3").lwhVTabber({
				button:  false
			});
			$("#d2, #d4").lwhVTabber({
				button: true,
				closed:	true
			});
			
			$("#d5, #d6, #d7, #d8, #d9, #d10, #d11, #d12," +
			   "#d13, #d14, #d15, #d16, #d17, #d18, #d19," + 
			   "#d20, #d21, #d22, #d23, #d24, #d25, #d26, #d27, #d28").lwhVTabber({closed: true});
		})
		</script>
</head>
<body style="padding-left:100px;">
<div id ="d1" class="lwhVTabber" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>CHINA</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />
<div id ="d2" class="lwhVTabber" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>CHINA</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />

<div id ="d3" class="lwhVTabber lwhVTabber-salmon" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>salmon</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />
<div id ="d4" class="lwhVTabber lwhVTabber-green" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>green</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />

<div id ="d5" class="lwhVTabber lwhVTabber-lime" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>lime</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />

<div id ="d6" class="lwhVTabber lwhVTabber-earth" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>earth</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />
<div id ="d7" class="lwhVTabber lwhVTabber-peridot" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>peridot</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />
<div id ="d8" class="lwhVTabber lwhVTabber-neon" style="width:500px;">
  <ul class="lwhVTabber-header" align="right">
      	<li>neon</li>
      	<li class="selected">CHINA</li>
      	<li>JAPAN</li>
        <div class="line"></div>
  </ul>
  <div class="lwhVTabber-content" style="height:300px; width:100%;">
  	<div>
    	11111111 1111<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  </div>
</div>
<br />


</body>
</html>