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

<div class="lwhTooltip" id="tooltips">
    <div class="lwhTooltip_message" style="font-size:16px; font-weight:bold; padding-top:5px;"></div>
</div>


<div id="wait" class="lwhLoading"></div>


<form name="from_language" action="<?php echo $CFG["http"] . $CFG["web_domain"] . $_SERVER["REQUEST_URI"];?>" method="get">
	<input type="hidden" name="lang" id="lang" value="<?php echo $Glang;?>" />
<?php  if($_REQUEST["event_id"]!="") { ?>
   	<input type="hidden" id="footer_event_id" 	name="event_id" value="<?php echo $_REQUEST["event_id"];?>" />
<?php } ?>
<?php  if($_REQUEST["publicSession"]!="") { ?>
   	<input type="hidden" id="footer_publicSession" 	name="publicSession" value="<?php echo $_REQUEST["publicSession"];?>" />
<?php } ?>
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