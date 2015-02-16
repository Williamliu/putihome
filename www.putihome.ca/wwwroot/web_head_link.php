<!-- Below Script and CSS is universal -->
<script type="text/javascript" 	src="jquery/min/jquery-1.7.2.min.js"></script>
<script type="text/javascript" 	src="jquery/min/jquery-ui-1.8.21.custom.min.js"></script>
<link 	type="text/css" 		href="jquery/theme/light/jquery-ui-1.8.21.custom.css" rel="stylesheet" />

<script type="text/javascript" 	src="js/js.lwh.common.js"></script>
<link 	type="text/css" 		href="theme/blue/content.css" rel="stylesheet" />

<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.drop.js"></script>
<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.drop.css" rel="stylesheet" />

<!--
<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.mmenu.js"></script>
<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.mmenu.css" rel="stylesheet" />
-->

<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.accordion.js"></script>
<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.accordion.css" rel="stylesheet" />

<script type="text/javascript" src="jquery/myplugin/jquery.lwh.diag.js"></script>
<link type="text/css" href="jquery/myplugin/css/light/jquery.lwh.diag.css" rel="stylesheet" />

<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.loading.js"></script>
<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.loading.css" rel="stylesheet" />

<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.tooltips.js"></script>
<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.tooltips.css" rel="stylesheet" />

<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.button.css" rel="stylesheet" />
<!-- End of Script and CSS is universal -->

<script language="javascript" type="text/javascript">
var errObj = new LWH.cERR({ diag: "#diaglog_error" });

var words			= [];
<?php 
	foreach( $words as $key=>$word ) {
?>
	words["<?php echo $key;?>"] = '<?php echo str_replace("'","%%%single_quote%%%", $word);?>';
<?php
	}
?>
for(var key in words) {
	words[key] = words[key]!=""?words[key].replaceAll("%%%single_quote%%%","'"):"";
}
</script>