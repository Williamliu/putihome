<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="0,72";
include_once("website_admin_auth.php");
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
		<link rel="icon" type="image/gif" href="bodhi.gif" />
		<title>Bodhi Meditation Attend CheckIn</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.global.timer.js"></script>
 
 		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var gTimer 		= null;
		
		$(function(){
			$("select.device_place").live("change", function(ev) {
				  var dev_id = $(this).attr("rid");
				  var place_id = $(this).val();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  device_id:	dev_id,
						  place:		place_id	
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (idcard_reader_place.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
						  }
					  },
					  type: "post",
					  url: "ajax/idcard_reader_place.php"
				  });
		    });
			
			$(".tabQuery-button-delete").live("click", function(ev){
				var device_id = $(this).attr("rid");  
				$.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",

						  device_id:	device_id
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (idcard_reader_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  $("tr[rid='" + req.data.device_id + "']").remove();
						  }
					  },
					  type: "post",
					  url: "ajax/idcard_reader_delete.php"
				  });
			});
			
			
			gTimer = new LWH.timerClass({
								meObj: 		"gTimer",
								interval:	30 * 1000,
								func: function() {
									  search_device();
								}
							});
			
			gTimer.start();
			search_device();
		});

		function device_html(dObjs) {
			var html = '';
			html += '<table id="mytab_alldevice"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
			html += '<tr rid="header">';
			html += '<td class="tabQuery-table-header" colspan="9">' + words["id reader list"] + '</td>';
			html += '</tr>';
			
			html += '<tr rid="header">';
			html += '<td class="tabQuery-table-header">' + words["sn"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["status"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["site_desc"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["place_desc"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["device_no"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["device_id"] + '</td>';
			html += '<td class="tabQuery-table-header">' + words["ip_address"] + '</td>'; 
			html += '<td class="tabQuery-table-header">' + words["last_updated"] + '</td>'; 
			html += '<td class="tabQuery-table-header">&nbsp;</td>'; 
			html += '</tr>';
		  	
			if(dObjs.length>0) {
				for(var key in dObjs) {
					html += device_body(key, dObjs[key] );
				}
			}
			
			html += '</table>';
			return html;
		}
		
		function device_body(sn, dObj) {
			var html = '';
			html += '<tr rid="' + dObj.device_id + '">';
			html += '<td align="center">';
			html += (parseInt(sn) + 1);
			html += '</td>';
			html += '<td class="status" align="center">';
			html += dObj.status; 
			html += '</td>';
			html += '<td class="site_desc">';
			html += dObj.site_desc;
			html += '</td>';
			html += '<td class="place_desc">';
			html += dObj.place_desc;
			html += '</td>';
			html += '<td class="device_no" align="center">';
			html += dObj.device_no;
			html += '</td>';
			html += '<td class="device_id" align="center">';
			html += dObj.device_id;
			html += '</td>';
			html += '<td class="ip_address">';
			html += dObj.ip_address;
			html += '</td>';
			html += '<td class="last_updated">';
			html += dObj.last_updated;
			html += '</td>';
			html += '<td align="center">';
			html += '<a class="tabQuery-button tabQuery-button-delete" right="delete" rid="' + dObj.device_id + '" title="' +  words["delete"] + '" style="margin-left:3px;"></a>';
			html += '</td>';
			html += '</tr>';
			return html;
		}
		
		
		
		function search_device() {
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"view",

					  sch_site:		$("#sch_site").val(),
					  sch_place:	$("#sch_place").val()					  
				  },
				  dataType: "json",  
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (idcard_reader_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  if( req.errorCode > 0 ) { 
						  errObj.set(req.errorCode, req.errorMessage, req.errorField);
						  return false;
					  } else {
							$("#sch_result").html(device_html(req.data.devices));
					  }
				  },
				  type: "post",
				  url: "ajax/idcard_reader_select.php"
			  });
		}
        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <fieldset>
    	<legend><?php echo $words["search filter"]?></legend>
              <table cellpadding="2" cellspacing="0">
                  <tr>
                      <td align="right"><?php echo $words["site_desc"]?>: </td>
                      <td>
                          <select id="sch_site" style="min-width:100px;" name="sch_site">
                              <?php
                                  $result_site = $db->query("SELECT * FROM puti_sites WHERE id IN " . $admin_user["sites"] . " order by id");
                                  while( $row_site = $db->fetch($result_site) ) {
                                      echo '<option value="' . $row_site["id"] . '" ' . ($row_site["id"]==$admin_user["site"]?'selected':'') . '>' . $words[strtolower($row_site["title"])] . '</option>';
                                  }
                              ?>
                          </select>
                      </td>
                      <td align="right"><?php echo $words["place_desc"]?>: </td>
                      <td>
                          <select id="sch_place" style="min-width:100px;" name="sch_place">
                              <option value=""></option>
                              <?php
                                  $result_place = $db->query("SELECT * FROM puti_places order by id");
                                  while( $row_place = $db->fetch($result_place) ) {
                                      echo '<option value="' . $row_place["id"] . '">' . $words[strtolower($row_place["title"])] . '</option>';
                                  }
                              ?>
                          </select>
                      </td>
                      <td>
                        <input type="button" oper="search" style="width:100px;" onclick="search_device()" style="width:60px;" value="<?php echo $words["search"]?>" />                  
                      </td>
                  </tr>
              </table>
    </fieldset>
    <div style="min-height:300px;">
    	<div id="sch_result" style="padding:5px;"></div>
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>