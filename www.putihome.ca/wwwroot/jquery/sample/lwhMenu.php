<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../code/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.5.custom.min.js"></script>
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.menu1.js"></script>
	<link type="text/css" 			href="../themes/base/jquery.ui.all.css" rel="stylesheet" />
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.menu1.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		$(function(){
			$("#mymenu").lwhMenu({
						trigger: "td",
						arrow:	"corner",
						action: { 
									play: function(tel, mel, iel) { $("#msg").append("click Play:" + $(iel).attr("action") + "<br>");  },
								 	download: 100, 
								 	flag:200, 
								 	remove: function() { $("#msg").append("click remove<br>");  }
								},
						win_open: function(tel, mel ){
							$("#msg").append("menu open <br>"); 
						},
						menu_open: {
							"play": 	function() { $("#msg").append("menu item open play<br>"); return "na"},
							"download": function() { return "nv"; },
							"remove": 	100
						},
						menu_close: {
							"play": function(t,m,i) { $("#msg").append("menu item close play<br>");	}
						},
						win_close: function(t, m) {
							$("#msg").append("menu close<br>");
						}
						
						
			});
			
			$("#yrmenu").lwhMenu({trigger:":input", arrow:"middle"});
			
			$(":input").addClass("liu wei hui");
			$(":input").addClass("hello");
			
			$("td").live("click", function() {
				$("#msg").append("td live click<br>");
			});
		})
		
		function clear_msg() {
			$("#msg").empty();
		}
		
		function ttt() {
			$(".input").removeClass("liu hello");
			
			$("#mymenu").menuSetup({
					trigger: ":input",
					arrow:	"",
					action: {	
								play: function(tel, mel, iel) { $("#msg").append("click rebind Play:" + $(tel).val() + "<br>");  }
							}
			});
		}
	</script>
</head>
<body style="background-color:#666666;">
<input type="button" class="input" onclick="ttt()" style="position:relative; top:200px;" value="re-setup" />

<ul id="mymenu" class="lwhMenu">
	<li action="play">Play</li>
	<li action="download">Download</li>
	<li action="playlist" 	class="na">Playlist</li>
	<li action="flag" 		class="na nv">Flagged</li>
	<li action="release_info" sgrp="">Release Information</li>
	<li action="remove" class="separator">Delete | Removal</li>
	<li action="remove_all">Remove All</li>
</ul>    


<ul id="yrmenu" class="lwhMenu">
	<li action="play">Play</li>
	<li action="download">Download</li>
	<li action="release_info">Release Information</li>
	<li class="separator title">Filter:</li>
	<li action="remove">Delete | Removal</li>
	<li action="remove_all">Remove All</li>
</ul>    


<br /><br />
<table border="1" style="position:relative; top:10px;">
    	<tr>
        	<th width="80" class="nowrap">Column One</span></th>
        	<th>Column Two</th>
        	<th class="nowrap fixed" width="30">Column Three</th>
        	<th>Column Four</th>
        	<th>Column Four</th>
    	</tr>
    	<tr>
        	<td class="nowrap">
            	content one onle with nowrap
            </td>
        	<td class="nowrap">
            	content two two, content text can be wrap automaticly.
            </td>
        	<td class="nowrap">693</td>
        	<td class="nowrap">
    			        Description Of Jobs
            </td>
        	<td>
    			 Description Of Jobs dhfjadsh jadshfjkadsh adsjfhjsk ajdshfjkadsh ajdshfdsj asdjhfjdsk jadshfjkjashdhf  dsjhfjdsk
				 jdshfjadskhfks jasdhfjk adsjasdhf 
            </td>
        </tr>
</table>

<div id="uuu" style="position:absolute; left:800px; top:10px; background-color:#eeeeee;">
	<input  id="kkk" type="button" onclick="clear_msg();" value="message clear" /><br />
	<div id="msg" style="color:red; background-color:#eeeeee; border:1px solid yellow; padding:10px; display:block; width:400px; height:600px; overflow:auto;"></div>
</div>

</body>
</html>