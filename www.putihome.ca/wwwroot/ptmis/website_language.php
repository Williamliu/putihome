<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="800,40";
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
		<title>Website Language</title>

		<?php include("admin_head_link.php"); ?>

		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
		var ctt = null;
		$(function(){
			  ctt = new LWH.cTABLE({
											condition: 	{
												sch_keyword:"#sch_keyword",
												sch_content:"#sch_content",
												sch_project:"#sch_project",
												sch_filter:"#sch_filter"
											},
											headers:[
												{title:"SN", 			col:"rowno",		width:20},
												{title:"Project", 		col:"project", 		width:100,	sq:"ASC"},
												{title:"Filter", 		col:"filter", 		width:100,	sq:"ASC"},
												{title:"Keyword", 		col:"keyword", 		width:150,	sq:"ASC"},
												{title:"English", 		col:"en",			width:200,	sq:"DESC"},
												{title:"CHS.Sim", 		col:"cn",			width:200,	sq:"ASC"},
												{title:"CHS.TW", 		col:"tw",			width:200,	sq:"ASC"}
											],
											container: 		"#lang_area",
											me:				"ctt",

											url:			"ajax/website_language_select.php",
											orderBY: 		"created_time",
											orderSQ: 		"DESC",
											cache:			false,
											expire:			3600,
											
											admin_sess: 	$("input#adminSession").val(),
											admin_menu:		$("input#adminMenu").val(),
						  					admin_oper:		"view",
											
											headRows:		headHTML,
											pageRows:		pageHTML
										});
			
			ctt.start();
			
			$(".tabQuery-button[oper='add']").live("click", function(ev) {
				newHTML();			
			});

			$(".tabQuery-button[oper='save']").live("click", function(ev) {
				save_ajax( $(this).attr("rid") );			
			});

			$(".tabQuery-button[oper='delete']").live("click", function(ev) {
				delete_ajax( $(this).attr("rid") );			
			});
			
			$(".tabQuery-button[oper='cancel']").live("click", function(ev) {
					$("tr[rid='" + $(this).attr("rid") + "']").remove();
					nidx = -1;
			});
			
			

			$(":input[oper='search']").bind("keydown", function(ev) {
				if( ev.keyCode == 13 ) {
					search_ajax();
				}
			});


		});
		
		function save_ajax( rid ) {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"save",

						  id: 			rid,
						  project:		$(".project[rid='" + rid + "']").val(),
						  filter:		$(".filter[rid='" + rid + "']").val(),
						  keyword:		$(".keyword[rid='" + rid + "']").val(),
						  en:			$(".en[rid='" + rid + "']").val(),
						  cn:			$(".cn[rid='" + rid + "']").val(),
						  tw:			$(".tw[rid='" + rid + "']").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (website_language_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  if(req.flag != 1) {
								  ctt.tabData.condition.orderBY = "";
								  ctt.sortBy("created_time", "DESC");
							  }
							}
					  },
					  type: "post",
					  url: "ajax/website_language_save.php"
				  });
		}

		function delete_ajax( rid ) {
				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 	$("input#adminSession").val(),
						  admin_menu:	$("input#adminMenu").val(),
						  admin_oper:	"delete",

						  id: 			rid
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (website_language_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  ctt.tabData.condition.orderBY = "";
							  ctt.sortBy("created_time", "DESC");
						  }
					  },
					  type: "post",
					  url: "ajax/website_language_delete.php"
				  });
		}

		
		function search_ajax() {
			ctt.start();
		}

		function headHTML( hObjs ) {
			var html = '';
			html += '<tr class="tabQuery-headers" rid="header">';
			for(var key in hObjs) {
					var ff = hObjs[key];
					if(ff.width && ff.width>0) 
						html += '<td class="tabQuery-table-header" width="' + ff.width + '">';
					else 
						html += '<td class="tabQuery-table-header">';
	
					html += ff.title;
					if(ff.sq && ff.sq!="") {
						var order_css = '';
						if(ff.col == ctt.tabData.condition.orderBY) {
							order_css = ' tabQuery-sort-' + ctt.tabData.condition.orderSQ.toLowerCase();
						}
						html += ' <a class="tabQuery-sort' + order_css + '" orderby="' + ff.col + '" defsq="' + (ff.sq!=""?ff.sq:"ASC") + '"></a>';
					}
					html += '</td>';
			}
			html += '<td class="tabQuery-table-header"  style="white-space:nowrap;">';
			html += '<a class="tabQuery-button tabQuery-button-add" oper="add" right="save"></a>';
			html += '</td>';
			html += '</tr>';
		
			return html;
		}
		

		function newHTML() {
				var nidx = -1;
				if( $("tr[rowno='-1'][rid='-1']").length > 0 ) return; 
				var html = '<tr rowno="' + nidx + '"  rid="' + nidx + '">';
				html += '<td align="center">';
				html += "New";
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<input class="project" rid="' + nidx + '" style="width:98%;" value="van.puti.ca" />';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<input class="filter" 	rid="' + nidx + '" style="width:98%;" value="common" />';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<input class="keyword" rid="' + nidx + '" style="width:98%;" value="" />';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<textarea class="en" rid="' + nidx + '" style="width:98%; height:20px;"></textarea>';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<textarea class="cn" rid="' + nidx + '" style="width:98%; height:20px;"></textarea>';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<textarea class="tw" rid="' + nidx + '" style="width:98%; height:20px;"></textarea>';
				html += '</td>';

				html += '<td align="center"  style="white-space:nowrap;">';
			 	html += '<a class="tabQuery-button tabQuery-button-save" 	oper="save" 	right="save" 	rsn="' + nidx + '"	rid="' + nidx + '" title="保存"></a>';
				html += '<a class="tabQuery-button tabQuery-button-delete" 	oper="cancel" 	right="view" 	rsn="' + nidx + '"	rid="' + nidx + '" title="删除"></a>';
				html += '</td>';

				html += '</tr>';
				
				$("tr[rid='header']").after(html);
		}
		
		function pageHTML( pRows ) {
			var html = '';
			var pObjs = pRows.rows;
			for(var idx in pObjs) {
				html += '<tr rowno="' + idx + '">';
				
				html += '<td align="center">';
				html += parseInt(idx) + 1;
				html += '</td>';


				html += '<td align="center" valign="top">';
				html += '<input class="project" rid="' + pObjs[idx]["id"] + '" style="width:98%;" value="' + pObjs[idx].project + '" />';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<input class="filter" 	rid="' + pObjs[idx]["id"] + '" style="width:98%;" value="' + pObjs[idx].filter + '" />';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<input class="keyword" rid="' + pObjs[idx]["id"] + '" style="width:98%;" value="' + pObjs[idx].keyword + '" />';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<textarea class="en" rid="' + pObjs[idx]["id"] + '" style="width:98%; height:20px;">' + pObjs[idx].en + '</textarea>';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<textarea class="cn" rid="' + pObjs[idx]["id"] + '" style="width:98%; height:20px;">' + pObjs[idx].cn + '</textarea>';
				html += '</td>';

				html += '<td align="center" valign="top">';
				html += '<textarea class="tw" rid="' + pObjs[idx]["id"] + '" style="width:98%; height:20px;">' + pObjs[idx].tw + '</textarea>';
				html += '</td>';

				html += '<td align="center"  style="white-space:nowrap;">';
			 	html += '<a class="tabQuery-button tabQuery-button-save" 	oper="save" 	right="save" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="保存"></a>';
				html += '<a class="tabQuery-button tabQuery-button-delete" 	oper="delete" 	right="delete" 	rsn="' + idx + '"	rid="' + pObjs[idx]["id"] + '" title="删除"></a>';
				html += '</td>';

				html += '</tr>';
			}
			return html;
		}
		
		function output_excel() {
				  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
						$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
						$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
						$("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");	
						$("input[name='orderBY']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderBY);	
						$("input[name='orderSQ']", "form[name='frm_list_excel']").val(ctt.tabData.condition.orderSQ);	

						$("input[name='sch_keyword']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_keyword);	
						$("input[name='sch_content']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_content);	
						$("input[name='sch_project']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_project);	
						$("input[name='sch_filter']", "form[name='frm_list_excel']").val(ctt.tabData.condition.sch_filter);	
				  } else {
						var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none; width:1000px;"></iframe>')[0].lastChild;;
						var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
						$("form[name='frm_list_excel']").attr({"action":"ajax/website_language_output.php", "target": "ifm_list_excel" }); 
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderBY" value="' + ctt.tabData.condition.orderBY + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="orderSQ" value="' + ctt.tabData.condition.orderSQ + '" />');				  

						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_keyword" value="' + ctt.tabData.condition.sch_keyword + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_content" value="' + ctt.tabData.condition.sch_content + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_project" value="' + ctt.tabData.condition.sch_project + '" />');				  
						$("form[name='frm_list_excel']").append('<input type="hidden" name="sch_filter" value="' + ctt.tabData.condition.sch_filter + '" />');				  
				  }
				  $("form[name='frm_list_excel']").submit();			  
		}

        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <fieldset>
    	<legend>Search Criteria</legend>
              <table cellpadding="2" cellspacing="2">
                  <tr>
                      <td align="right"><?php echo $words["keyword"];?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_keyword" value="" /></td>
                      <td align="right"><?php echo $words["content"];?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_content" value="" /></td>
                      <td align="right"><?php echo $words["project"];?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_project" value="" /></td>
                      <td align="right"><?php echo $words["filter"];?>: </td>
                      <td><input oper="search" style="width:120px;" id="sch_filter" value="" /></td>
                  </tr>
                  <tr>
                      <td align="right"></td>
                      <td>
                         <input type="button" oper="search" style="width:120px;" onclick="search_ajax()" style="width:100px;" value="Search" />                  
                      </td>
                      <td align="right"></td>
                      <td>
	                     <input type="button" right="print" onclick="output_excel()" style="width:100px;" value="Output Excel" /> 
                      </td>
                  </tr>
              </table>
    </fieldset>
 	<div id="lang_area" style="min-height:400px;"></div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>