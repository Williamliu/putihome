<?php
	ini_set("display_errors",1);
/*
	$path_parts = pathinfo('http://www.uss.com/www/htdocs/inc/lib.inc.php');
	echo "<pre>";
	print_r($path_parts);
	echo "</pre>";
	header('Content-Type: image/jpeg');
	
	$im = imagecreatetruecolor(100, 100);
 	$black = imagecolorallocate($im, 255, 0, 0);
	$font = imageloadfont('./fff.gdf');
	imagestring($im, $font, 100,100, "HELLO WORLD", $black); 
	
	//$a = "upload/lwh.png";
	//echo "file:" . preg_replace("/(.*)[.](.*)/", "$1", $a);
	//echo "file : [" . basename($a) . "]";
	//echo " dir: [" . dirname($a) . "]<br>";
	//$im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . "/mama.png");
	//$dest = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . "/upload/usana.JPG");
	//imagecopyresampled($dest, $im,0,0,0,0,32,100,32,32);

	ob_start();
	//imagejpeg($dest, $_SERVER['DOCUMENT_ROOT'] . "/upload/gooduss.jpg");
	//imagejpeg($dest);
	
	//$imgbinary = ob_get_contents();
	ob_end_flush();
*/
//exit();
	//imagecopymerge($dest, $im, 10, 10, 0, 0, 100, 100, 100);
	//$dest1 = imagerotate($dest, 30, 0);
	//imagepng($dest);
	//exit();
	/*	
	$ia = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/upload/usana.JPG");
	echo "Size:<pre>";
	print_r($ia);
	echo "</pre><br>";
	*/
function getFileName($ff, $f1=true) {
	echo "hello:" . $ff . " true:" . $f1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" 	src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<link type="text/css" 			href="../themes/light/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

	<script type="text/javascript" 	src="/js/js.lwh.common.js"></script>

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.upload.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />

	<script type="text/javascript" 	src="../myplugin/jquery.lwh.diag.js"></script>
    <link 	type="text/css" 		href="../myplugin/css/light/jquery.lwh.diag.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		var aj = null;
		$(function(){
			aj = new LWH.AjaxUpload({
				url:		"lwhUpload_save.php", 
				button:		"#file_upload", 
				info:		"#dshow", 
				multiple:	true,
				start: 		function() {
					aj.setID(105);
					//alert($(aj.settings.button).attr("sn") + ":" + aj.settings.ref_id);
					aj.cleanLog();
				},
				done: 		function(req) {
					
					//$("#hello").attr("src", req.data.fileUrl);
					$("#hello").attr("src",  "lwhUpload_image.php?size=small&img_id=" + req.data.img_id);
					$("#ddd").attr("href", "lwhUpload_content.php?doc_id=" + req.data.doc_id);
					//alert("code:" + req.errorCode + " url:"  + req.data.uid + ":" + req.data.fileurl);
				}
			});
		});
		
		function aa() {
			for(var i = 0 ; i < aj._filelist.length; i++) {
				$("body").append( aj._filelist[i].ufile );
			}
		}
	</script>
</head>
<body>
<br />
<a id="file_upload" sn="299" style="background-color:yellow; text-align:center; width:300px; height:60px; line-height:60px;">Click here to upload file</a>
<br />

<img id="hello" width="300" src="data:image/jpeg;base64,<?php echo base64_encode($imgbinary);?>" />
<br />
<a id="ddd" href="javascript:void(0);">Download file</a>
</body>
</html>
