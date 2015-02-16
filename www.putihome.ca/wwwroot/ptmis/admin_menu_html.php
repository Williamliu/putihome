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

		$(".lwhMMenu").lwhMMenu();
	});
</script>

<?php
ob_start();
echo '<!--  Main Menu Content -->';
echo '<ul class="lwhMMenu" style="margin-top:80px; position:absolute;">';
foreach($menu["menu"] as $key0=>$val0) {
	// create url for menu
	if($admin_user["right"]["right"][$key0]["view"] == 1) {
		  
		  if($val0["tpl"] != "") 
		  		$menu_url0 = '<a class="lwhMMenu-a" href="' . $val0["tpl"] . '">' . $val0["name"] . '</a>'; 
		  else if($val0["url"] != "") 
		  		$menu_url0 = '<a class="lwhMMenu-a" href="' . $val0["url"] . '" target="_blank">' . $val0["name"] . '</a>'; 
		  else 
		  		$menu_url0 = '<a class="lwhMMenu-a" href="javascript:void(0);">' . $val0["name"] . '</a>'; 
		  
		  
		  
		  echo '<li class="lwhMMenu-li">' . $menu_url0;
		  if( $key0 !=0 ) {
				  if(is_array($val0["menu"])) {
					  echo '<div class="lwhMMenu-div"><div class="lwhMMenu-white"><ul class="lwhMMenu-items">';
					  foreach($val0["menu"] as $key1=>$val1) {
						  // create url for menu-item
						  if($admin_user["right"]["right"][$key0]["right"][$key1]["view"] == 1) {
								
								if($val1["tpl"] != "") 
									$menu_url1 = '<a class="menu-item" href="' . $val1["tpl"] . '">' . $val1["name"] . '</a>'; 
								else if($val1["url"] != "") 
									$menu_url1 = '<a class="menu-item" href="' . $val1["url"] . '" target="_blank">' . $val1["name"] . '</a>'; 
								else 
									$menu_url1 = '<a class="menu-item" href="javascript:void(0);">' . $val1["name"] . '</a>'; 
								
								if(  $val1["title"] == "category" ) {
									echo '<li class="lwhMMenu-item-category">' . $val1["name"] . '</li>';
								} else {
									echo '<li class="lwhMMenu-item">' . $menu_url1 . '</li>';
								}
						  }
					  }
					  echo '</ul></div></div>';
				  }
		
		  } else {
		
				  if(is_array($val0["menu"])) {
					  echo '<div class="lwhMMenu-div" style="width:360px;"><div class="lwhMMenu-white">';

					  echo '<table border="0" width="100%"><tr>';
					  foreach($val0["menu"] as $key1=>$val1) {
						  // create url for menu-item
						  if( $key1 == 0 ) echo '<td valign="top" align="left" width="40%" style="padding-right:50px;"><ul class="lwhMMenu-items">';
						  if( $key1==75 ) echo '<td valign="top" align="left" width="60%"><ul class="lwhMMenu-items">';
						  if($admin_user["right"]["right"][$key0]["right"][$key1]["view"] == 1) {
								
								if($val1["tpl"] != "") 
									$menu_url1 = '<a class="menu-item" href="' . $val1["tpl"] . '">' . $val1["name"] . '</a>'; 
								else if($val1["url"] != "") 
									$menu_url1 = '<a class="menu-item" href="' . $val1["url"] . '" target="_blank">' . $val1["name"] . '</a>'; 
								else 
									$menu_url1 = '<a class="menu-item" href="javascript:void(0);">' . $val1["name"] . '</a>'; 
								
								if(  $val1["title"] == "category" ) {
									 echo '<li class="lwhMMenu-item-category">' . $val1["name"] . '</li>';
								} else {
									echo '<li class="lwhMMenu-item">' . $menu_url1 . '</li>';
								}
						  }
						  if( $key1 == 74 || $key1==160 ) echo '</ul></td>';
					  }
					  echo '</tr"></table>';
					  
					  echo '</div></div>';
				  }
		  }
		  
		  echo '</li>';
	} // end of menu0 view
	
}
echo '</ul>';
echo '<!-- end of main menu -->';

$menu_content = ob_get_contents();
ob_end_clean();
?>
<div class="main-layout"> <!--  begin of main-layout -->
		<!-- page header -->
        <span style="color:red; font-size:24px; position:absolute; top:40px; left:50%; margin-left:-200px; z-index:99999;">
        <?php echo ($admin_user["platform"]=="beta"?$words[$admin_user["platform"]]:"")?>
        </span>
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
                <?php echo $menu_content; ?>
        </div>
		<!-- end of page header -->