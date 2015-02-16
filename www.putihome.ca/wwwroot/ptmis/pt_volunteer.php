<?php
session_start();
ini_set("display_errors", 1);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="50,50";
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
		<title>Bodhi Meditation Student Enrollment</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />

		<script type="text/javascript" src="../jquery/myplugin/jquery.lwh.tabber.js"></script>
		<link type="text/css" href="../jquery/myplugin/css/light/jquery.lwh.tabber.css" rel="stylesheet" />

		<script type="text/javascript" 	src="../jquery/min/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.zoom.js"></script>
		<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.zoom.css" rel="stylesheet" />
		
        <script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.upload.js"></script>
        <link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />

    	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.slidebox.js"></script>
        <link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.slidebox.css" rel="stylesheet" />

	 	<script type="text/javascript" 	src="../jquery/myplugin/jquery.lwh.tree.js"></script>
    	<link 	type="text/css" 		href="../jquery/myplugin/css/light/jquery.lwh.tree.css" rel="stylesheet" />

        <script language="javascript" type="text/javascript">
		var ctt = null;
		var aj = null;
		var htmlObj = new LWH.cHTML();
		var current_rid = -1;
        var vol_detail_html = '';
		$(function(){
			  $("#diaglog_detail").lwhDiag({
				  titleAlign:		"center",
				  title:			words["Member Enrollment"],
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			500,
				  minHH:			180,
				  btnMax:			false,
				  resizable:		false,
				  movable:			true,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });


			  
			  
			  ctt = new LWH.cTABLE({
											condition: 	{
												sch_name:	"#sch_name",
												sch_email:	"#sch_email",
												sch_site:	"#sch_site",

												sch_memid:	"#sch_memid",
												sch_idd:	"#sch_idd",
												sch_phone:	"#sch_phone",
												sch_city:	"#sch_city"
											},
											headers:[
												{title: words["sn"], 			col:"rowno",		width:20},
												{title: words["name"], 			col:"flname", 		sq:"ASC"},
												{title: words["legal name"], 	col:"legal_name", 	sq:"ASC"},
												//{title: words["last name"], 	col:"last_name", 	sq:"ASC"},
												{title: words["dharma"], 		col:"dharma_name", 	sq:"ASC"},
												{title: words["m.alias"], 		col:"alias", 		sq:"DESC"},
												{title: words["gender"], 		col:"gender", 		sq:"ASC"},
												//{title: words["email"], 		col:"email", 		sq:"ASC"},
												{title: words["phone"], 		col:"phone", 		sq:"ASC"},
												{title: words["city"], 			col:"city", 		sq:"ASC", align:"center"},
												{title: words["short.lang"], 	col:"language", 	sq:"ASC", align:"center"},
												{title: words["g.site"], 		col:"site", 		sq:"ASC", align:"center"},
												{title: words["member.regdate"], 			col:"created_time",	sq:"DESC"},
											    {title: words["c.id"], 			col:"id", 			sq:"ASC"},
											    {title: words["c.photo"], 		col:"photo", 		align:"center"},
												{title: words["vol."], 			col:"vol_flag", 	sq:"DESC", align:"center"},
												{title:"", 						col:""}
											],
											container: 		"#puti_volunteer_area",
											me:				"ctt",

											url:			"ajax/pt_volunteer_select.php",
											orderBY: 		"created_time",
											orderSQ: 		"DESC",
											cache:			true,
											expire:			3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											button:			true,
											view:			true,
											output:			true,
											remove:			true,

											pageRows:		pageHTML
										});
			
			ctt.start();
			

			$(".enroll_button_add").live("click", function(ev) {
				var rid = $(this).attr("rid");
				if( $("tr.volunteer-detail-area").length <= 0 ) {
				    var html = '<tr class="volunteer-detail-area"><td colspan="15" style="padding:10px;">';
				    html += '<fieldset id="diaglog_volunteer" style="background-color:#ffffff; border-radius: 10px; border: 1px solid #999999; padding:5px; position:relative; display:none;">';
                    html += '<legend style="font-size:14px; font-weight:bold;">' + words["volunteer information"] + '</legend>';
                    html += vol_detail_html;
                    html += '</fieldset>';
				    html += '</td></tr>';
					$("tr.rows[rid='" + rid + "']").after(html);
                    $("tr.rows").removeClass("tr-selected");
					$("tr.rows[rid='" + rid + "']").addClass("tr-selected");

    				$("#diaglog_volunteer").stop().show(1000);

					current_rid = rid;
					$("#member_id").val(rid);
					volunteer_detail_search_ajax(rid);

				} 
				else {
					if( current_rid == rid ) {
						if( $("tr.volunteer-detail-area").is(":visible") ) {
							$("tr.volunteer-detail-area").stop().hide(500);
                            $("tr.rows").removeClass("tr-selected");
						} else {
							$("tr.volunteer-detail-area").stop().show(1000);
                            $("tr.rows").removeClass("tr-selected");
					        $("tr.rows[rid='" + rid + "']").addClass("tr-selected");

							current_rid = rid;
							$("#member_id").val(rid);
							volunteer_detail_search_ajax(rid);
						}
					} else {
						$("tr.rows[rid='" + rid + "']").after($("tr.volunteer-detail-area"));
						$("tr.volunteer-detail-area").stop().show(1000);
                        $("tr.rows").removeClass("tr-selected");
					    $("tr.rows[rid='" + rid + "']").addClass("tr-selected");

						current_rid = rid;
						$("#member_id").val(rid);
						volunteer_detail_search_ajax(rid);
					}
				}

			});

			$(".vol-flag").live("click", function(ev) {
				  var rid = $(this).attr("rid");
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  vol_flag:		$("input.vol-flag[rid='" + rid + "']").is(":checked")?1:0,
						  member_id: 	rid
				  	  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  //$("#wait").loadHide();
						  alert("Error (pt_volunteer_add.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  //$("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							tool_tips(words["save success"]);
						  }
					  },
					  type: "post",
					  url: "ajax/pt_volunteer_add.php"
				  });
			});

			
			
			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					search_ajax();
				}
			});

			$("#btn_print_empty").bind("click", function(ev) {
				var eid = $("#event_id").val();
				print_signature(eid, 0);
			});

			$("input#sch_idd, input#idd").bind("focus", function(ev) {
				$(this).select();
			});

			$("input#sch_idd, input#idd").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					$(this).select();
				}
			});



			
			// output signature form
			$(".tabQuery-button[oper='print']").live("click", function(ev){
				  var eid = $("#event_id").val();
				  var mid = $(this).attr("rid");
				  print_signature(eid,mid);
			});	

			$(".tabQuery-button[oper='view']").live("click", function(ev) {
				  var member_id = $(this).attr("rid");
				  member_detail_search(member_id);
			});


            vol_detail_html = $("#puti_volunteer_detail").html();
            $("#puti_volunteer_detail").empty();
		});
		
		function search_ajax() {
			ctt.start();
		}

		function print_signature(eid, mid) {
			  $.ajax({
				  data: {
					  admin_sess: 	$("input#adminSession").val(),
					  admin_menu:	$("input#adminMenu").val(),
					  admin_oper:	"print",
						  
					  event_id: 	eid,						
					  member_id:	mid
				  },
				  dataType: "json",  
				  //contentType: "text/html; charset=utf-8",
				  error: function(xhr, tStatus, errorTh ) {
					  alert("Error (event_calendar_group_signature.php): " + xhr.responseText + "\nStatus: " + tStatus);
				  },
				  success: function(req, tStatus) {
					  var w1 = window.open("output.html");
					  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Student Registration</" + "title" + "></" + "head" + "><" + "body>";
					  w1.document.open();
					  w1.document.write(html_str);
					  w1.document.write(req.data);
					  w1.document.write('</html>');
					  w1.document.close();
					  w1.print();
				  },
				  type: "post",
				  url: "ajax/event_calendar_group_signature.php"
			  });
		}
		
		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			for(var idx in pObjs) {
				html += '<tr class="rows" rowno="' + idx + '" rid="'  + pObjs[idx]["id"] + '">';
				
				html += '<td align="center">';
				html += parseInt(idx) + 1;
				html += '</td>';


				html += '<td style="white-space:nowrap;"><span class="flname">';
				html += pObjs[idx]["flname"];
				html += '</span></td>';


				html += '<td style="white-space:nowrap;"><span class="legal_name">';
				html += pObjs[idx]["legal_name"];
				html += '</span></td>';

				html += '<td style="white-space:nowrap;"><span class="dharma_name">';
				html += pObjs[idx]["dharma_name"];
				html += '</span></td>';

				html += '<td style="white-space:nowrap;"><span class="alias">';
				html += pObjs[idx]["alias"];
				html += '</span></td>';


				html += '<td align="center"><span class="gender">';
				html += pObjs[idx]["gender"];
				html += '</span></td>';
				

				html += '<td><span class="phone">';
				html += pObjs[idx]["phone"];
				html += '</span></td>';
				

				html += '<td align="center"><span class="city">';
				html += pObjs[idx]["city"];
				html += '</span></td>';

				html += '<td align="center"><span class="language">';
				html += pObjs[idx]["language"];
				html += '</span></td>';
				
				html += '<td align="center"><span class="site">';
				html += pObjs[idx]["site"];
				html += '</span></td>';


				html += '<td>';
				html += pObjs[idx]["created_time"];
				html += '</td>';

				html += '<td align="center">';
				html += pObjs[idx]["id"];
				html += '</td>';

				html += '<td align="center"><span class="photo">';
				html += pObjs[idx]["photo"];
				html += '</span>&nbsp;</td>';


				html += '<td>';
				html += '<input class="vol-flag" type="checkbox" '+ (pObjs[idx]["vol_flag"]=="1"?'checked':'') + ' rid="' + pObjs[idx]["id"] + '" value="1" />';
				html += '</td>';

				html += '<td align="center"  style="white-space:nowrap;">';
			 	html += '<a class="enroll_button_add" 		oper="save" 		right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view volunteer details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-view" 		oper="view" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["view member details"] + '"></a>';
				html += ' <a class="tabQuery-button tabQuery-button-output" 	oper="print" 	right="print" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="' + words["print volunteer form"] + '"></a>';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		
		
		function full_ajax() {
			$("input[name='member_id']").val($("#member_id").val());
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/pt_registration1.php");
			form_register.submit();
		}

		function quick_ajax() {
			$("input[name='member_id']").val($("#member_id").val());
			$("form[name='form_register']").attr("action", "<?php echo $CFG["http"] . $CFG["admin_domain"];?>/pt_qform1.php");
			form_register.submit();
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
                      <td align="right"><?php echo $words["name"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_name" value="" /></td>
                      <td align="right"><?php echo $words["email"]?>: </td>
                      <td><input oper="search" style="width:120px;font-size:12px;" id="sch_email" value="" /></td>
                      <td align="right"><?php echo $words["g.site"]?>: </td>
                      <td>
                            <select id="sch_site" name="sch_site">
                                  <option value=""></option>
                                  <?php
                                      $result_site = $db->query("SELECT id, title FROM puti_sites WHERE status = 1 AND id in " . $admin_user["sites"] . " ORDER BY id"); 
                                      while( $row_site = $db->fetch($result_site) ) {
                                          echo '<option value="' . $row_site["id"] . '">' . $words[strtolower($row_site["title"])] . '</option>';		
                                      }
                                  ?>
                            </select>
                      </td>
                      <td align="right"><?php echo $words["member id"]?>: </td>
                      <td><input type="text" style="width:60px;" oper="search" id="sch_memid" name="sch_memid" value="" /></td>
                  </tr>
                  <tr>
                      <td align="right"><b><?php echo $words["id number"]?>:</b> </td>
                      <td><input style="width:120px;"  oper="search" id="sch_idd" style="width:120px; margin-left:10px;" value="" /></td>
                      <td align="right"><?php echo $words["phone"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_phone" value="" /></td>
                      <td align="right"><?php echo $words["city"]?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_city" value="" /></td>
                  </tr>
                  <tr>
                      <td align="right"></td>
                      <td colspan="7">
                        <input type="button" oper="search" style="width:100px;" onclick="search_ajax()" style="width:60px;" value="<?php echo $words["search"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="quick_ajax()"  value="<?php echo $words["quick register"]?>" />                  
                        <input type="button" oper="search" style="width:100px;" onclick="full_ajax()"  value="<?php echo $words["full register"]?>" />                  
						<a id="btn_print_empty" class="tabQuery-button tabQuery-button-output" style=" vertical-align:middle; margin-left:20px;" right="print" title="<?php echo $words["print empty signature"]?>"></a>                       
                      </td>
                  </tr>
              </table>
    </fieldset>
 	<div id="puti_volunteer_area" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>

<?php include("tpl_member_detail.php"); ?>
<form name="form_register" action="" method="post">
	<input type="hidden" name="lang" value="<?php echo $Glang;?>" />
	<input type="hidden" name="adminSession" value="<?php echo $_SESSION[$_SERVER['HTTP_HOST'] . ".sysSessID"]; ?>" />
	<input type="hidden" name="adminMenu" value="<?php echo $admin_menu; ?>" />
	<!-- <input type="hidden" name="member_id" value="" /> -->
</form>

<?php include("tpl_volunteer_detail.php"); ?>
<?php include("tpl_volunteer_depart.php"); ?>
</body>
</html>