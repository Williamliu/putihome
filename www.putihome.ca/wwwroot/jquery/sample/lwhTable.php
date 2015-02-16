<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../code/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.5.custom.min.js"></script>
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.table.js"></script>
	<link type="text/css" 			href="../themes/base/jquery.ui.all.css" rel="stylesheet" />
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.table.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		$(function(){
			$("table").lwhTable();
			$("tr:even").css("background-color", "#eeeeee");
			$("tr:odd").css("background-color", "#777777");
		})
	</script>
</head>
<body>

<table style="margin:10px;">
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

<a class="lwh-btn lwh-btn30 lwh-btn30-white"><s class="left"></s>Click for ME<s class="right"></s></a>
</body>
</html>