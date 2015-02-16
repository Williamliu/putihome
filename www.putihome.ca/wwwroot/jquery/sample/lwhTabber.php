<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Tabber</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.tabber.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		$(function(){
			$("#d1, #d3").lwhTabber({
				button:  false
			});
			$("#d2, #d4").lwhTabber({
				button: true
			});
			
			$("#d5, #d6, #d7, #d8, #d9, #d10, #d11, #d12," +
			   "#d13, #d14, #d15, #d16, #d17, #d18, #d19," + 
			   "#d20, #d21, #d22, #d23, #d24, #d25, #d26, #d27, #d28").lwhTabber({
				   closed: false,
				   linkTo:	"#d3, #d4, #d5, #d6"
			});
		})
		</script>
</head>
<body>
<div id="d1" class="lwhTabber" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Single Tab<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content">
    	<div>
        	1111111111111111111111111111111111111111111<br />
        </div>
    </div>
</div>
<br />
<div id="d2" class="lwhTabber" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Single Button<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content">
    	<div>
        	1111111111111111111111111111111111111111111<br />
        </div>
    </div>
</div>
<br />
<div id="d3" class="lwhTabber" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Skyblue<s></s></a>
		<a>Monday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content">
    	<div>
        	1111111111111111111111111111111111111111111<br />
        </div>
    	<div>
        	222222222222222222222222222222222222222222<br />
        </div>
    </div>
</div>
<br />
<div id="d4" class="lwhTabber" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Skyblue Button<s></s></a>
		<a>Monday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content">
    	<div>
        	1111111111111111111111111111111111111111111<br />
        </div>
    	<div>
        	222222222222222222222222222222222222222222<br />
        </div>
    </div>
</div>
<br />
<div id="d5" class="lwhTabber lwhTabber-salmon" style="width:500px;">
    <div class="lwhTabber-header">
		<a>salmon<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d6" class="lwhTabber lwhTabber-green" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Green<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d7" class="lwhTabber lwhTabber-red" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Red<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d8" class="lwhTabber lwhTabber-fuzzy" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Fuzzy<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d9" class="lwhTabber lwhTabber-iris" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Iris<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d10" class="lwhTabber lwhTabber-purple" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Purple<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d11" class="lwhTabber lwhTabber-goldenrod" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Goldenrod<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d12" class="lwhTabber lwhTabber-mint" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Mint<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d13" class="lwhTabber lwhTabber-smitten" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Smitten<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d14" class="lwhTabber lwhTabber-yellow" style="width:500px;">
    <div class="lwhTabber-header">
		<a>yellow<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div>
        	1111111111111111111111111111111111111111111<br />
            dfjaskldjfkldsa<br />
            kdjfklasdjfsdak<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
        	dfjaskldjfkldsa<br />
            kdjfklasdjfsdak<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
        </div>
    	<div>
        	22222222222222222222222222222222222222222222222<br />
        	dfjaskldjfkldsa<br />
            kdjfklasdjfsdak<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
        </div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        	dfjaskldjfkldsa<br />
            kdjfklasdjfsdak<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        	dfjaskldjfkldsa<br />
            kdjfklasdjfsdak<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
            dkfaksjdflsdajf<br />
        </div>
    </div>
</div>
<br />
<div id="d15" class="lwhTabber lwhTabber-black" style="width:500px;">
    <div class="lwhTabber-header">
		<a>black<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d16" class="lwhTabber lwhTabber-grey" style="width:500px;">
    <div class="lwhTabber-header">
		<a>grey<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d17" class="lwhTabber lwhTabber-orange" style="width:500px;">
    <div class="lwhTabber-header">
		<a>orange<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br /><div id="d18" class="lwhTabber lwhTabber-awesome" style="width:500px;">
    <div class="lwhTabber-header">
		<a>awesome<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d19" class="lwhTabber lwhTabber-blue" style="width:500px;">
    <div class="lwhTabber-header">
		<a>blue<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d20" class="lwhTabber lwhTabber-neon" style="width:500px;">
    <div class="lwhTabber-header">
		<a>neon<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d21" class="lwhTabber lwhTabber-phlox" style="width:500px;">
    <div class="lwhTabber-header">
		<a>phlox<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d22" class="lwhTabber lwhTabber-brown" style="width:500px;">
    <div class="lwhTabber-header">
		<a>brown<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d23" class="lwhTabber lwhTabber-portland" style="width:500px;">
    <div class="lwhTabber-header">
		<a>portland<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d24" class="lwhTabber lwhTabber-sea" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Sea<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d25" class="lwhTabber lwhTabber-lime" style="width:500px;">
    <div class="lwhTabber-header">
		<a>Lime<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d26" class="lwhTabber lwhTabber-earth" style="width:500px;">
    <div class="lwhTabber-header">
		<a>earth<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d27" class="lwhTabber lwhTabber-peridot" style="width:500px;">
    <div class="lwhTabber-header">
		<a>peridot<s></s></a>
		<a>Tuesday<s></s></a>
		<a>Wednesday<s></s></a>
		<a>Thursday<s></s></a>
		<a>Friday<s></s></a>
        <div class="line"></div>    
    </div>
    <div class="lwhTabber-content" style="height:120px;">
    	<div></div>
    	<div></div>
    	<div>
        	333333333333333333333333333333333333333333333333<br />
        </div>
    	<div>
        	4444444444444444444444444444444444444444444444444<br />
        </div>
    </div>
</div>
<br />
<div id="d28" class="lwhTabber lwhTabber-red" style="width:500px;">
  <div class="lwhTabber-header">
      	<a>Red Country<s></s></a>
      	<a class="selected">USA<s></s></a>
      	<a>Canada<s></s></a>
      	<a>China<s></s></a>
      	<a>Japan<s></s></a>
      	<a>French<s></s></a>
       	<div class="line"></div>
  </div>
  <div class="lwhTabber-content">
  	<div>
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    	11111111 1111<br />
        kdsfjkalsdjf;laksdjf <br /> 
        dkjfl;aksdjf klasdjfklasjdlkfjas dkjasdklfjasdlkjflkjklsdjf askjklsad fklasj kasjdfklasdjfl asdkfjasdlkjf kajfdklasjd ljasdklfjasdlkf jasd<br />
    </div>
  	<div>
    	22222 2222
    </div>
  	<div>
    	33333
    </div>
  	<div>
    	4444444
    </div>
  	<div>
    	555555
    </div>
  	<div>
    	666666
    </div>
  	<div>
    	777777
    </div>
  	<div>
    	8888
    </div>
  	<div>
    	9999
    </div>
  </div>
</div>
<br />
<br />


</body>
</html>