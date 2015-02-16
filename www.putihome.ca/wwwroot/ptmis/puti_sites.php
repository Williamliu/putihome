<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="800,32";
include_once("website_admin_auth.php");

$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="copyright" content="Copyright Bodhi Meditation, All Rights Reserved." />
		<meta name="description" content="Bodhi Meditation Vancouver Site" />
		<meta name="keywords" content="Bodhi Meditation Vancouver" />
		<meta name="rating" content="general" />
		<meta name="language" content="english" />
		<meta name="robots" content="index" />
		<meta name="robots" content="follow" />
		<meta name="revisit-after" content="1 days" />
		<meta name="classification" content="" />
		<link rel="icon" type="image/gif" href="../bodhi.gif" />
		<title>Bodhi Meditation Online Agreement</title>
		
		<?php include("admin_head_link.php"); ?>

		<link href="../jquery/min/cleditor/jquery.cleditor.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../jquery/min/cleditor/jquery.cleditor.min.js"></script>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

    	<script type="text/javascript" language="javascript">
		var htmlObj = new LWH.cHTML();
		$(function(){
			$("#group_list, #group_edit").lwhTabber();
			$("#diaglog_message").lwhDiag({
				titleAlign:		"center",
				title:			 words["submit success"],
				
				cnColor:			"#F8F8F8",
				bgColor:			"#EAEAEA",
				ttColor:			"#94C8EF",
				 
				minWW:			400,
				minHH:			250,
				btnMax:			false,
				resizable:		false,
				movable:		false,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});
			
			$("li.group-item").live("click", function(ev) {
				  $("#wait").loadShow();
				  var gid = $(this).attr("gid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"view",
						  
						  site_id: 	gid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
					  	  $("#wait").loadHide();
						  alert("Error (puti_sites_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
					  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							$("li.group-item").removeClass("selected");
							$("li.group-item[gid='" + req.data.site_id + "']").addClass("selected");
							$("input#site_id").val(req.data.site_id);
							$("input#title").val(req.data.title);
							$("input#address").val(req.data.address);
							$("input#tel").val(req.data.tel);
							$("input#email").val(req.data.email);
							$("input#timezone").val(req.data.timezone);
							$("input#cert_prefix").val(req.data.cert_prefix);
							htmlObj.checkbox_clear("branchs");
							htmlObj.checkbox_set("branchs", req.data.branchs);
							$("input#site_name_cn").val(req.data.site_name_cn);
							$("input#site_name_en").val(req.data.site_name_en);
							$("input#phone_cn").val(req.data.phone_cn);
							$("input#phone_en").val(req.data.phone_en);
							
                            $("input#school_cn").val(req.data.school_cn);
							$("input#school_en").val(req.data.school_en);
							
                            $("input#sn").val(req.data.sn);
							$("select#status").val(req.data.status);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_sites_select.php"
				  });
			});

			$("a.branchs").live("click", function(ev) {
				  $("#wait").loadShow();
				  var branch_id = $(this).attr("rid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",
						  
						  site_id: 	$("input#site_id").val(),
						  branch_id: branch_id,
						  branch_title: $(".branch_title[rid='" + branch_id + "']").val(),
						  branchs:  $(".branchs[rid='" + branch_id + "']").is(":checked")?1:0,
						  internal: $(".internal[rid='" + branch_id + "']").is(":checked")?1:0,
						  branch_sn: $(".branch_sn[rid='" + branch_id + "']").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
					  	  $("#wait").loadHide();
						  alert("Error (puti_branchs_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
					  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
						  	$("#branch_html").html(req.data.html);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_branchs_save.php"
				  });
			});

			new_ajax();	
		});
		function save_ajax() {
			  	  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 		$("input#adminSession").val(),
						  admin_menu:		$("input#adminMenu").val(),
						  admin_oper:		"save",

						  site_id: 			$("input#site_id").val(),
						  title: 			$("input#title").val(),
						  address: 			$("input#address").val(),
						  tel: 				$("input#tel").val(),
						  email: 			$("input#email").val(),
						  timezone: 		$("input#timezone").val(),
						  cert_prefix: 		$("input#cert_prefix").val(),
						  branchs: 			htmlObj.checkbox_get("branchs"),

						  site_name_cn: 	$("input#site_name_cn").val(),
						  site_name_en: 	$("input#site_name_en").val(),
						  phone_cn: 		$("input#phone_cn").val(),
						  phone_en: 		$("input#phone_en").val(),

						  school_cn: 	    $("input#school_cn").val(),
						  school_en: 	    $("input#school_en").val(),

						  sn: 				$("input#sn").val(),
						  status: 			$("select#status").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
					  	  $("#wait").loadHide();
						  alert("Error (puti_sites_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
					  	  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							if( req.data.old_id < 0 ) {
								$("ul#group_items").append('<li class="group-item" gid="' + req.data.site_id + '">' + req.data.title  + '</li>');
								$("li.group-item").removeClass("selected");
								$("li.group-item[gid='" + req.data.site_id + "']").addClass("selected");
							} else {
								$("li.group-item[gid='" + req.data.site_id + "']").html(req.data.subject);
							}
							$("input#site_id").val(req.data.site_id);
						  }
					  },
					  type: "post",
					  url: "ajax/puti_sites_save.php"
				  });
		}

		
		function new_ajax() {
			$("li.group-item").removeClass("selected");
			$("input#site_id").val(-1);
			$("input#title").val("");
			$("input#address").val("");
			$("input#tel").val("");
			$("input#email").val("");
			$("input#cert_prefix").val("");
			htmlObj.checkbox_clear("branchs");

			$("input#site_name_cn").val("");
			$("input#site_name_en").val("");
			$("input#phone_cn").val("");
			$("input#phone_en").val("");

			$("input#school_cn").val("");
			$("input#school_en").val("");

			$("input#sn").val("");
			$("select#status").val("");
		}
    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
        	<tr>
            	<td valign="top" width="280px">
                    <div id="group_list" class="lwhTabber lwhTabber-goldenrod" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["menu_site"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:520px;">
                            <div id="groups" style="height:450px; overflow-x:hidden; overflow-y:auto;">
                            	<?php
									ob_start();
									$result = $db->query("SELECT id, title FROM puti_sites ORDER BY sn");
									echo '<ul class="group-items" id="group_items">';
									while( $row = $db->fetch($result) ) {
										echo '<li class="group-item" gid="' . $row["id"] . '">' . $words[strtolower($row["title"])] . '</li>';
									}
									echo '</ul>';
									ob_end_flush();
								?>
                            </div>
                        </div>
                    </div>
                </td>
            	<td valign="top" width="auto">
                    <div id="group_edit" class="lwhTabber lwhTabber-fuzzy" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["menu_site_info"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content" style="height:auto;">
                            <div id="group_item">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                                                <tr>
                                                    <td class="title"><?php echo $words["site name"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input type="hidden" id="site_id" name="site_id" value="-1" />
                                                        <input class="form-input" style="width:90%" id="title" name="title" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["address"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="address" name="address" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["phone"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="tel" name="tel" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["email"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="email" name="email" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["timezone"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="timezone" name="timezone" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["r.teaching"]?>: </td>
                                                    <td><span id="branch_html">
													  <?php 
                                                          	$result_branchs = $db->query("SELECT * FROM puti_branchs Order BY sn");
                                                          	
                                                          	echo '<table class="tabQuery-table">';
															echo '<tr>';
															echo '<td class="tabQuery-table-header">' . $words["sel."] . '</td>';				
															echo '<td class="tabQuery-table-header">' . $words["r.teaching"] . '</td>';				
															echo '<td class="tabQuery-table-header">' . $words["internal group"] . '</td>';				
															echo '<td class="tabQuery-table-header">' . $words["sn"] . '</td>';				
															echo '<td class="tabQuery-table-header"></td>';				
															echo '</tr>';
															while($rows_branchs = $db->fetch($result_branchs)) {
																echo '<tr>';
																echo '<td align="center">' . 
																			'<input type="checkbox" rid="' . $rows_branchs["id"] . '" id="branchs_'  . $rows_branchs["id"] . '" name="branchs"  class="branchs"  value="' . $rows_branchs["id"] . '">'
																	. '</td>';				
																echo '<td><span class="required">*</span> ' . 
																			'Key: <input type="text" rid="' . $rows_branchs["id"] . '" id="branch_title_'  . $rows_branchs["id"] . '" name="branch_title"  class="branch_title"  value="' . $rows_branchs["title"] . '" style="width:200px;" /><br>Display: '
																	 . ($words[strtolower($rows_branchs["title"])]!=""?$words[strtolower($rows_branchs["title"])]:$rows_branchs["title"])
																	 . '</td>';				
																echo '<td align="center">'.
																			'<input type="checkbox" rid="' . $rows_branchs["id"] . '" id="internal_'  . $rows_branchs["id"] . '" ' . ($rows_branchs["internal"]?'checked':'') . ' name="internal"  class="internal"  value="1">'
																	.'</td>';				
																echo '<td align="center"><span class="required">*</span> '.
																			'<input type="text" style="width:30px;" rid="' . $rows_branchs["id"] . '" id="branch_sn_'  . $rows_branchs["id"] . '" name="branch_sn"  class="branch_sn"  value="' . $rows_branchs["sn"] . '">'
																	.'</td>';				
																echo '<td>' . 
																		'<a class="tabQuery-button tabQuery-button-save branchs" oper="save" right="save" rid="' . $rows_branchs["id"] . '" title="保存"></a>'
																	.'</td>';				
																echo '</tr>';
															}

																echo '<tr>';
																echo '<td align="center"></td>';				
																echo '<td><span class="required">*</span> Key: ' . 
																			'<input type="text" style="width:200px;" rid="-1" id="branch_title_-1" name="branch_title"  class="branch_title"  value="">'
																	 . '</td>';				
																echo '<td align="center">'.
																			'<input type="checkbox" rid="-1" id="internal_-1" name="internal"  class="internal"  value="1">'
																	.'</td>';				
																echo '<td align="center"><span class="required">*</span> '.
																			'<input type="text" style="width:30px;" rid="-1" id="branch_sn_-1" name="branch_sn"  class="branch_sn"  value="">'
																	.'</td>';				
																echo '<td>' . 
																		'<a class="tabQuery-button tabQuery-button-save  branchs" oper="save" right="save" rid="-1" title="保存"></a>'
																	.'</td>';				
																echo '</tr>';

                                                          	echo '</table>';
															
                                                          	//$rows_branchs = $db->rows($result_branchs);
															//echo cHTML::checkbox('branchs',$rows_branchs,1);
                                                      		
													  ?>
                                                      </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["cert_no_prefix"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:60px;" id="cert_prefix" name="cert_prefix" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["sn"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <input class="form-input" style="width:60px;" id="sn" name="sn" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["status"]?>: <span class="required">*</span></td>
                                                    <td>
                                                        <select id="status" name="status">
                                                            <option value=""></option>
                                                            <option value="0"><?php echo $words["inactive"]?></option>
                                                            <option value="1"><?php echo $words["active"]?></option>
                                                        </select>
                                                        
                                                    </td>
                                                </tr>
                                                

                                                <tr>
                                                    <td colspan="2" align="center" valign="top"><?php echo cTYPE::gStr($words["site name for certification"])?>: </td>
                                                </tr>

                                                <tr>
                                                    <td class="title line"><?php echo $words["site name cn"]?>: </td>
                                                    <td class="line">
                                                        <input class="form-input" style="width:90%" id="site_name_cn" name="site_name_cn" value="" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["phone cn"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="phone_cn" name="phone_cn" value="" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["site name en"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="site_name_en" name="site_name_en" value="" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["phone en"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="phone_en" name="phone_en" value="" />
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="2" align="center" valign="top"><?php echo cTYPE::gStr($words["site name for dharma"])?>: </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="title line"><?php echo $words["site name cn"]?>: </td>
                                                    <td class="line">
                                                        <input class="form-input" style="width:90%" id="school_cn" name="school_cn" value="" />
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["site name en"]?>: </td>
                                                    <td>
                                                        <input class="form-input" style="width:90%" id="school_en" name="school_en" value="" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
		                                                 <center>
                                                         	<input type="button" right="save" onclick="save_ajax()" value="<?php echo $words["button save"]?>" />
                                                            <input type="button" right="add" id="btn_new" onclick="new_ajax()" value="<?php echo $words["button add"]?>" />
                                                         </center>
                                                    </td>
                                                </tr>

                                            </table> 
                                            <!-- end of group detail -->
                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
                </td>
            </tr>    
        </table>
	</div>
<?php 
include("admin_footer_html.php");
?>

<div id="diaglog_message" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
    	<div id="lwhDiag-msg">
        </div>
	</div>
</div>

</body>
</html>