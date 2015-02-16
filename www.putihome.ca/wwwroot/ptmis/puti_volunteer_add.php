<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="10,20";
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
		<title>Bodhi Meditation Add Volunteer</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

 		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />
 
    	<script type="text/javascript" language="javascript">
		$(function(){
			$("#group_edit").lwhTabber();
			$("#diaglog_detail").lwhDiag({
				titleAlign:		"center",
				title:			"Found Matched Volunteer",
				
				cnColor:		"#F8F8F8",
				bgColor:		"#EAEAEA",
				ttColor:		"#94C8EF",
				 
				minWW:			500,
				minHH:			370,
				btnMax:			false,
				resizable:		false,
				movable:		true,
				maskable: 		true,
				maskClick:		true,
				pin:			false
			});
		   
		   $("#tabber_detail").lwhTabber();

			$("#btn_detail_write").live("click", function(ev){
				save_ajax(1);
			});

			$("#btn_detail_create").live("click", function(ev){
				save_ajax(2);
			});

			$("#btn_detail_cancel").live("click", function(ev){
				cancel_ajax();
				newRecord();
			});
		});


		function save_ajax(tt) {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	"<?php echo $admin_menu;?>",
						  admin_oper:	"save",
						  
						  hid:			$("input#hid").val(),	
						  type:			tt,
						  cname: 		$("input#cname").val(),
						  pname: 		$("input#pname").val(),
						  en_name: 		$("input#en_name").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  gender:		$("input:radio[name='gender']:checked").val(),
						  email: 		$("input#email").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  city: 		$("input#city").val(),
						  depart:		$("input:checkbox.department:checked").map(function(){ return $(this).val();}).get().join(",")
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_volunteer_add_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode == 9 ) { 
							  //alert("match");
							  $("#diaglog_detail").diagShow({
									diag_open: function() {
										  // tabber 1
										  $("input#hid").val(req.data.hid);
										  $("input#dharma_name1").val(req.data.dharma_name);
										  $("input#cname1").val(req.data.cname);
										  $("input#pname1").val(req.data.pname);
										  $("input#en_name1").val(req.data.en_name);
										  $("input:radio[name='gender1'][value='" + req.data.gender + "']").attr("checked",true);
										  
										  $("input#email1").val(req.data.email);
										  $("input#phone1").val(req.data.phone);
										  $("input#cell1").val(req.data.cell);
										  $("input#city1").val(req.data.city);
										  $("select#status1").val(req.data.status);
										  // tabber 2
										  $.map( req.data.depart.split(","), function(n) {
												$("input:checkbox.department1[value='" + n + "']").attr("checked",true);
										  });
										  $("#total_hour1").html(req.data.total_hour);
										  $("#work_count1").html(req.data.work_count);
										  recToHTML(req.data.record);

									},
									diag_close: function() {
										clearDetail();
									}
							   });
							  //return false;
						  } else if(req.errorCode > 0) {
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							$("#diaglog_detail").diagHide();
							$("span#save-status").html(req.errorMessage.nl2br());
						  	newRecord();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_volunteer_add_save.php"
				  });
		}

		function clearDetail() {
				  // tabber 1
				  $("input#hid").val(-1);
				  $("input#dharma_name1").val("");
				  $("input#cname1").val("");
				  $("input#pname1").val("");
				  $("input#en_name1").val("");
				  $("input:radio[name='gender1']").attr("checked",false);
				  
				  
				  $("input#email1").val("");
				  $("input#phone1").val("");
				  $("input#cell1").val("");
				  $("input#city1").val("");
				  $("select#status1").val("");

				  // tabber 2
				  $("input:checkbox.department1").attr("checked",false);
				  $("#total_hour1").empty();
				  $("#work_count1").empty();
				  $("#records1").empty();
		}
		
		function newRecord() {
			$("input#cname").val("");
			$("input#pname").val("");
			$("input#en_name").val("");
			$("input#dharma_name").val("");
			$("input:radio[name='gender']").attr("checked",false);
			$("input#email").val("");
			$("input#phone").val("");
			$("input#cell").val("");
			$("input#city").val("");	
			//$("input:checkbox.department").attr("checked",false);					
		}

		function recToHTML(rObj) {
			  var html = '<table class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
				  html += '<tr>';
				  html += '<td width="20" class="tabQuery-table-header">SN</td>';
				  html += '<td class="tabQuery-table-header">Department</td>';
				  html += '<td class="tabQuery-table-header">Work For</td>';
				  html += '<td class="tabQuery-table-header">Work Date</td>';
				  html += '<td class="tabQuery-table-header">Work Hour</td>';
				  html += '</tr>';
				  
				  
				  for(var idx in rObj) {
					  html += '<tr class="hour-record" vid="' + rObj[idx].id + '">';

					  html += '<td width="20" align="center">';
					  html += parseInt(idx) + 1;
					  html += '</td>';
					  html += '<td>';
					  html +=  rObj[idx].title;
					  html += '</td>'
					  html += '<td>';
					  html +=  '<input class="record-purpose" vid="' + rObj[idx].id + '" style="width:100px; text-align:left;" value="' + rObj[idx].purpose + '" />';
					  html += '</td>';
					  html += '<td>';
					  html +=  '<input class="record-date" vid="' + rObj[idx].id + '" style="width:100px; text-align:center;" value="' + rObj[idx].work_date + '" />';
					  html += '</td>';
					  html += '<td>';
					  html +=  '<input class="record-hour" vid="' + rObj[idx].id + '" style="width:60px; text-align:right;" value="' + rObj[idx].work_hour + '" />';
					  html += '</td>';
					  html += '</tr>';
				  }
				  html += '</table>';

				  $("#records").html(html);

		}
		
		function cancel_ajax() {
			$("#diaglog_detail").diagHide();
		}

    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
                    <div id="group_edit" class="lwhTabber lwhTabber-smitten" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["new volunteer"]?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content">
                            <div id="group_item">
                                <table cellpadding="2">
                                	<tr>
                                    	<td valign="top" width="300">
                                        	<!-- group detail -->
                                            <table border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["dharma name"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="dharma_name" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["chinese name"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="cname" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="title"><?php echo $words["pinyin"]?>: </td>
                                                    <td> 
                                                        <input class="form-input" id="pname" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["english name"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="en_name"  value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                     <td class="title"><?php echo $words["gender"]?>: </td>
                                                     <td>
                                                        <input type="radio" id="gender_male" name="gender" value="Male" /><label for="gender_male"><?php echo $words["male"]?></label> 
                                                        <input type="radio" id="gender_female" name="gender" value="Female" /><label for="gender_female"><?php echo $words["female"]?></label>
                                                     </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title line"><?php echo $words["email"]?>: </td>
                                                    <td class="line">
                                                        <input class="form-input" id="email"  value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["phone"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="phone" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["cell"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="cell" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" class="title"><?php echo $words["city"]?>: </td>
                                                    <td>
                                                        <input class="form-input" id="city" value="" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
		                                                 <center><input type="button" right="save" onclick="save_ajax(0)" value="<?php echo $words["button save"]?>" /><br />
                                                         <span id="save-status" style="color:red;"></span>
                                                         </center>
                                                    		
                                                    </td>
                                                </tr>
                                            </table> 
                                            <!-- end of group detail -->
                                      </td>
                                      <td valign="top" align="left">
                                            <table width="100%">   
                                                <tr>
                                                    <td>
	                                                    <?php echo $words["belong department"]?>: <br />
                                                    	<div style="border:1px solid #cccccc; padding:5px; width:100%;">
                                                        	<?php
																$result = $db->query("SELECT * FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
																$col_cnt = 3;
																$html = '<table width="100%">';
																$cnt=0;
																$cno=0;
																while($row = $db->fetch($result)) {
																	$cno++;
																	if($cnt <= 0) {
																		$html .= '<tr>';
																	}
																	$cnt++;
																	$html .= '<td>';
																	$html .= '<input type="checkbox" id="depart_' . $row["id"] . '" class="department" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  ($admin_user["lang"]!="en"?cTYPE::gstr($row["title"]):cTYPE::gstr($row["en_title"])) . '</label>';
																	$html .= '</td>';
									
																	if($cnt >= $col_cnt) {
																		$cnt = 0;
																		$html .= '</tr>';
																	}
																}
																if($cnt > 0 && $cnt < $col_cnt) $html .= '</tr>';
																$html .= '</table>';
																echo $html;
															?>
                                                        </div>
                                                    </td>
                                                </tr>
                                           </table>
                                      </td>     
                                  	</tr>
                            	</table>       
                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
	</div>
<?php 
include("admin_footer_html.php");
?>

<br />

<div id="diaglog_detail" class="lwhDiag" style="z-index:888;">
	<div class="lwhDiag-content lwhDiag-no-border">
          <div id="tabber_detail" class="lwhTabber lwhTabber-mint" style="width:480px;">
              <div class="lwhTabber-header">
                  <a><?php echo $words["personal information"]?><s></s></a>
                  <a><?php echo $words["belong department"]?><s></s></a>
                  <a><?php echo $words["volunteer records"]?><s></s></a>
                  <div class="line"></div>    
              </div>
              <div class="lwhTabber-content" style="height:300px; border-width:3px;">
                  <div>
					<!------------------------------------------------------------------>
                            <table cellpadding="2" cellspacing="0" width="100%">
                                <tr>
                                     <td class="title"><?php echo $words["dharma name"]?>: </td>
                                     <td>
                                       	<input type="hidden" id="hid" name="hid" value="" />
                                        <input class="form-input" id="dharma_name1" name="dharma_name1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["chinese name"]?>: </td>
                                     <td>
                                        <input class="form-input" id="cname1" name="cname1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["pinyin"]?>: </td>
                                     <td>
                                        <input class="form-input" id="pname1" name="pname1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["english name"]?>: </td>
                                     <td>
                                        <input class="form-input" id="en_name1" name="en_name1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["gender"]?>: </td>
                                     <td>
                                        <input type="radio" id="gender_male1" name="gender1" value="Male" /><label for="gender_male1"><?php echo $words["male"]?></label> 
                                        <input type="radio" id="gender_female1" name="gender1" value="Female" /><label for="gender_female1"><?php echo $words["female"]?></label>
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title line"><?php echo $words["email"]?>: </td>
                                     <td class="line">
                                        <input class="form-input" id="email1" name="email1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["phone"]?>: </td>
                                     <td>
                                        <input class="form-input" id="phone1" name="phone1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["cell"]?>: </td>
                                     <td>
                                        <input class="form-input" id="cell1" name="cell1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["city"]?>: </td>
                                     <td>
                                        <input class="form-input" id="city1" name="city1" value="" />
                                     </td>
                                </tr>
                                <tr>
                                     <td class="title"><?php echo $words["status"]?>: </td>
                                     <td>
                                        <select id="status1" name="status1">
                                            <option value=""></option>
                                            <option value="0"><?php echo $words["inactive"]?></option>
                                            <option value="1"><?php echo $words["active"]?></option>
                                        </select>
                                        <span class="required">*</span>
                                     </td>
                                </tr>
                            </table>                    
					<!------------------------------------------------------------------>
                  </div>
                  <div>
					<!------------------------------------------------------------------>
                    <?php echo $words["belong department"]?>: <br />
                    <div style="border:1px solid #cccccc; padding:5px;">
                        <?php
                            $result = $db->query("SELECT id, title FROM puti_department WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, title;");
                            $col_cnt = 3;
                            $html = '<table width="100%">';
                            $cnt=0;
                            $cno=0;
                            while($row = $db->fetch($result)) {
                                $cno++;
                                if($cnt <= 0) {
                                    $html .= '<tr>';
                                }
                                $cnt++;
                                $html .= '<td>';
                                $html .= '<input type="checkbox" id="depart_' . $row["id"] . '" class="department1" value="' . $row["id"] . '"><label for="depart_' . $row["id"] . '">' . $cno . '. ' .  $row["title"] . '</label>';
                                $html .= '</td>';
    
                                if($cnt >= $col_cnt) {
                                    $cnt = 0;
                                    $html .= '</tr>';
                                }
                            }
                            if($cnt > 0 && $cnt < $col_cnt) $html .= '</tr>';
                            $html .= '</table>';
                            echo $html;
                        ?>
                    </div>
					<!------------------------------------------------------------------>
                  </div>

                  <div>
					<!------------------------------------------------------------------>
						<b><?php echo $words["total volunteer hours"]?>: </b><span id="total_hour1" style="font-size:14px; font-weight:bold;color:blue;"></span> 
                        <b><?php echo $words["counts"]?>: </b> <span id="work_count1" style="font-size:14px; font-weight:bold;color:blue;"></span> 
                        <div id="records" style="width:100%; overflow:auto;">
                        </div>
                    <!------------------------------------------------------------------>
                  </div>
              </div>
              <center>
              	<input type="button"  right="save" id="btn_detail_write"  value="<?php echo $words["overwrite"]?>" />
              	<input type="button"  right="save" id="btn_detail_create" value="<?php echo $words["create new"]?>" />
              	<input type="button"  right="save" id="btn_detail_cancel" value="<?php echo $words["button cancel"]?>" />
              </center>
          </div> <!-- end of "lwhTabber" -->
	</div>
</div>

</body>
</html>