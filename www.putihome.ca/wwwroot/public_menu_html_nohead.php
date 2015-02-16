<script type="text/javascript" language="javascript">
	$(function(){
		$("#menu_lang").lwhDrop({
				init: function(me) {
					var div_el = $("div.lwhDrop-div[dropsn='" + $(me).attr("dropsn") + "']");
					if ($("li.selected", div_el).length > 0 )
						$("span.lwhDrop-span",me).html( $("li.selected", div_el).html() );
					
					$(".lang", div_el).live("click", function(ev) {
						$("span.lwhDrop-span",me).html( $(this).html() );
						$(me).lwhDrop_close();
						$("#lang").val($(this).attr("lang"));
						from_language.submit();
					});
				}
		});
		
		$( "#myaccord" ).accordion({
			  heightStyle: "auto",
			  active: false,
			  collapsible: true		  
		});	

	});
</script>

<?php
$db_menu 		= new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
ob_start();
echo '<a class="goto-event-calendar" href="event_calendar.php">' . $words["event calendar"] . '</a>';

$query_menu_sites 	= "SELECT * FROM puti_sites WHERE status = 1 ORDER BY sn DESC";
$result_menu_sites 	= $db_menu->query($query_menu_sites); 

echo '<div id="myaccord" class="lwhAccordion" style="margin:2px; width:250px; border:0px solid #cccccc;">';
while( $row_menu_sites	= $db_menu->fetch($result_menu_sites) ) {
	echo '<div class="lwhAccordion-title">' .  $words[strtolower($row_menu_sites["title"])] . '</div>';
	
	$query_menu_branchs 	= "SELECT b.* FROM puti_sites_branchs a INNER JOIN puti_branchs b ON (a.branch_id = b.id) WHERE b.internal = 0 AND site_id = '" .  $row_menu_sites["id"] . "' ORDER BY id"; 
	$result_menu_branchs 	= $db_menu->query($query_menu_branchs);

	echo '<div class="lwhAccordion-body" style="overflow:hidden;"><div style="margin-top:-15px;padding-bottom:60px;">';


	echo '<a class="event-item site" siteid="' . $row_menu_sites["id"] . '" branchid="" href="javascript:void(0);">' .  $words[strtolower($row_menu_sites["title"])] . ' - ' . cTYPE::gstr($words["bodhi all class"]) . '</a>';
 
	while( $row_menu_branchs = $db_menu->fetch($result_menu_branchs) ) {
		echo '<a class="event-item"  siteid="' . $row_menu_sites["id"] . '" branchid="' . $row_menu_branchs["id"] . '" href="javascript:void(0);">' .  $words[strtolower($row_menu_branchs["title"])] . '</a>';
	}

	echo '<br><br><table class="address" width="100%"><tr>';
	echo '<td style="color:#EB8F00;font-weight:bold;white-space:nowrap;" align="right" valign="top" width="20">' . $words["address"] . ':</td><td valign="top">' . $row_menu_sites["address"] . '</td>';
	echo '</tr><tr>';
 	echo '<td style="color:#EB8F00;font-weight:bold;white-space:nowrap;" align="right" valign="top" width="20">' . $words["phone"] . ':</td><td valign="top">' .  $row_menu_sites["tel"] . '</td>';
	echo '</tr></table>';

	echo '</div></div>';
}
echo '</div>';
$menu_content = ob_get_contents();
ob_end_clean()
?>
<div class="main-layout"> <!--  begin of main-layout -->
		<!-- page header -->
        <div class="main-header">
                <div style="position:absolute; display:block; width:200px; top:10px; left:100%; margin-left: -120px;">
                      <s id="menu_lang" class="lwhDrop lwhDrop-pink">
                          <b><span class="lwhDrop-span"><?php echo $words["language"];?></span></b>
                          <div class="lwhDrop-div">
                              <div class="lwhDrop-white">
                                     <div class="lwhDrop-ban lwhDrop-ban-blue"><?php echo $words["menu_select"];?></div>
                                     <ul class="lwhDrop-items">
                                     <li class="lwhDrop-item lang <?php echo ($Glang=="cn"?"selected":"");?>" lang="cn"><?php echo $words["menu_chinese"];?></li>
                                     <li class="lwhDrop-item lang <?php echo ($Glang=="tw"?"selected":"");?>" lang="tw"><?php echo $words["menu_cntw"];?></li>
                                     <li class="lwhDrop-item lang <?php echo ($Glang!="cn"?"selected":"");?>" lang="en">English</li>
                                     </ul>
                              </div>
                          </div>
                      </s>
                </div>
				<?php //echo $menu_content; ?>               
        </div>
