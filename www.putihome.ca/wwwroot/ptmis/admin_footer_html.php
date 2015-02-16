</div> <!--  end of main-layout -->

<!-- below is out of main-layout components -->
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />
<div id="diaglog_error" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

<div id="wait" class="lwhLoading"></div>

<div class="lwhTooltip" id="tooltips">
    <div class="lwhTooltip_message" style="font-size:16px; font-weight:bold; padding-top:5px;"></div>
</div>


<form name="from_language" action="<?php echo $_SERVER["REQUEST_URI"];?>" method="post">
	<input type="hidden" name="lang" id="lang" value="<?php echo $Glang;?>" />
	<input type="hidden" id="adminSession" 	name="adminSession" value="<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>" />
	<input type="hidden" id="adminMenu"	 	name="adminMenu" value="<?php echo $admin_menu; ?>" />
</form>

<script type="text/javascript" language="javascript">
	$(function(){
		$("#diaglog_error").lwhDiag({
			titleAlign:		"center",
			title:			words["error message"],
			
			cnColor:			"#F8F8F8",
			bgColor:			"#EAEAEA",
			ttColor:			"#94C8EF",
			 
			minWW:			400,
			minHH:			250,
			btnMax:			false,
			resizable:		false,
			movable:			false,
			maskable: 		true,
			maskClick:		true,
			pin:				false
		});

		$("#tooltips").lwhTooltip();	

		$("#wait").lwhLoading({loadMsg:"WAITING..."});
	});
	function tool_tips( ss ) {
		$("#tooltips").autoTShow(ss);
	}
</script>