<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Tree</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />


	<script type="text/javascript" 	src="../myplugin/jquery.lwh.tree.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.tree.css" rel="stylesheet" />
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.tree1.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.tree1.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		$(function(){
				$("#lwhT").lwhTree();
				//alert("ok");
				$("#lwhT").append('<li class="nodes nodes-open"><s class="node-line"></s><s class="node-img"></s>H1H1H1HH1H1H1H1' +
					'<ul class="lwhTree"><li><s class="node-line"></s><s class="node-img"></s>2222222222222222222222</li><li class="node"><s class="node-line"></s><s class="node-img"></s>333333333333333333333</li>' +
        			'</ul>' + '</li>');
					

				$("#lwhT").lwhTree_refresh();
				$("#lwhTT").lwhTree1();
		})
		</script>
</head>
<body>
<br /><br />
<ul id="lwhT" class="lwhTree">
    <li class="nodes nodes-open">
	    	<s class="node-line"></s><s class="node-img"></s>
        	1111111111111111111111111
			<ul class="lwhTree"> 
            	<li>
                	<s class="node-line"></s><s class="node-img"></s>
                    2222222222222222222222
                </li>
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                    333333333333333333333
                </li>
        	</ul>
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
        44444444444444444444
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
       55555555555555
    </li>
	<li class="nodes nodes-last-open">
    	<s class="node-line"></s><s class="node-img"></s>
        666666666666666666666
			<ul class="lwhTree"> 
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                    7777777777777777777
                </li>
            	<li class="nodes nodes-open">
                	<s class="node-line"></s><s class="node-img"></s>
                    888888888888888888
                    <ul class="lwhTree"> 
                        <li class="node">
                            <s class="node-line"></s><s class="node-img"></s>
                            999999999999999999999
                        </li>
                        <li class="node">
                            <s class="node-line"></s><s class="node-img"></s>
                            AAAAAAAAAAAAAAAAAAAAAAA
                        </li>
                    </ul>
                </li>
        	</ul>
    </li>
    <li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
       BBBBBBBBBBBBBBBBBBB
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
        CCCCCCCCCCCCCCCCCCC
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
        DDDDDDDDDDDDDDD
    </li>
	<li class="nodes">
    	<s class="node-line"></s><s class="node-img"></s>
        EEEEEEEEEEEEEEEEEEEE
			<ul class="lwhTree"> 
            	<li>
                	<s class="node-line"></s><s class="node-img"></s>
                    FFFFFFFFFFFFFFFFFFFFFFFFFF
                </li>
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                   GGGGGGGGGGGGGGGGGGGGGGGGGGGG
                </li>
        	</ul>
    </li>
</ul>

<BR />

<ul id="lwhTT" class="lwhTree1">
    <li class="nodes nodes-open">
	    	<s class="node-line"></s><s class="node-img"></s>
        	1111111111111111111111111
			<ul class="lwhTree1"> 
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                    2222222222222222222222
                </li>
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                    333333333333333333333
                </li>
        	</ul>
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
        44444444444444444444
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
       55555555555555
    </li>
	<li class="nodes nodes-open">
    	<s class="node-line"></s><s class="node-img"></s>
        666666666666666666666
			<ul class="lwhTree1"> 
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                    7777777777777777777
                </li>
            	<li class="nodes nodes-open">
                	<s class="node-line"></s><s class="node-img"></s>
                    888888888888888888
                    <ul class="lwhTree1"> 
                        <li class="node">
                            <s class="node-line"></s><s class="node-img"></s>
                            999999999999999999999
                        </li>
                        <li class="node">
                            <s class="node-line"></s><s class="node-img"></s>
                            AAAAAAAAAAAAAAAAAAAAAAA
                        </li>
                    </ul>
                </li>
        	</ul>
    </li>
    <li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
       BBBBBBBBBBBBBBBBBBB
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
        CCCCCCCCCCCCCCCCCCC
    </li>
	<li class="node">
    	<s class="node-line"></s><s class="node-img"></s>
        DDDDDDDDDDDDDDD
    </li>
	<li class="nodes">
    	<s class="node-line"></s><s class="node-img"></s>
        EEEEEEEEEEEEEEEEEEEE
			<ul class="lwhTree1"> 
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                    FFFFFFFFFFFFFFFFFFFFFFFFFF
                </li>
            	<li class="node">
                	<s class="node-line"></s><s class="node-img"></s>
                   GGGGGGGGGGGGGGGGGGGGGGGGGGGG
                </li>
        	</ul>
    </li>
</ul>

</body>
</html>