<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="50,10";
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
         $(function () {
			$("#group_edit").lwhTabber();

             $(".tabQuery-button-save[rid]", "div#basic_table").live("click", function () {
                 var rid = $(this).attr("rid");
                 var dataObj = {};
                 dataObj.admin_sess = $("input#adminSession").val();
                 dataObj.admin_menu = $("input#adminMenu").val();
                 dataObj.admin_oper = "save";
                 
                 dataObj.rid = rid;
                 dataObj.status = $("input.status[rid='" + rid + "']").is(":checked")?1:0;
                 dataObj.sn = $("input.sn[rid='" + rid + "']").val();
                 dataObj.title_en = $("input.title_en[rid='" + rid + "']").val();
                 dataObj.desc_en = $("input.desc_en[rid='" + rid + "']").val();
                 dataObj.title_cn = $("input.title_cn[rid='" + rid + "']").val();
                 dataObj.desc_cn = $("input.desc_cn[rid='" + rid + "']").val();
                 dataObj.table_name = $("#table_name").val();

                 save_ajax(dataObj);
             });


             $(".tabQuery-button-delete[rid]", "div#basic_table").live("click", function () {
                 var rid = $(this).attr("rid");
                 var dataObj = {};
                 dataObj.admin_sess = $("input#adminSession").val();
                 dataObj.admin_menu = $("input#adminMenu").val();
                 dataObj.admin_oper = "delete";
                 dataObj.rid = rid;
                 delete_ajax(dataObj);
             });

             search_ajax();
         });


         function search_ajax() {
             if( $("#table_name").val()=='') {
                 $("#basic_table").empty();
                 return;  
             }

             $("#wait").loadShow();
             $.ajax({
                 data: {
                     admin_sess: $("input#adminSession").val(),
                     admin_menu: $("input#adminMenu").val(),
                     admin_oper: "view",

                     table_name: $("#table_name").val()
                 },
                 dataType: "json",
                 error: function (xhr, tStatus, errorTh) {
                     $("#wait").loadHide();
                     alert("Error (website_basic_table_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
                 },
                 success: function (req, tStatus) {
                     $("#wait").loadHide();
                     if (req.errorCode > 0) {
                         errObj.set(req.errorCode, req.errorMessage, req.errorField);
                         return false;
                     } else {
                         head_html(req.data);
                     }
                 },
                 type: "post",
                 url: "ajax/website_basic_table_select.php"
             });
         }


         function save_ajax(dataJSON) {
             $("#wait").loadShow();
             $.ajax({
                 data: dataJSON,
                 dataType: "json",
                 error: function (xhr, tStatus, errorTh) {
                     $("#wait").loadHide();
                     alert("Error (website_basic_table_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
                 },
                 success: function (req, tStatus) {
                     $("#wait").loadHide();
                     if (req.errorCode > 0) {
                         errObj.set(req.errorCode, req.errorMessage, req.errorField);
                         return false;
                     } else {
                         tool_tips(words["save success"]);
                         search_ajax();
                     }
                 },
                 type: "post",
                 url: "ajax/website_basic_table_save.php"
             });
         }

         function delete_ajax(dataJSON) {
             var yes = false;
             yes = window.confirm(words["are you sure to delete this record?"]);
             if (!yes) return;

             $("#wait").loadShow();
             $.ajax({
                 data: dataJSON,
                 dataType: "json",
                 error: function (xhr, tStatus, errorTh) {
                     $("#wait").loadHide();
                     alert("Error (website_basic_table_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
                 },
                 success: function (req, tStatus) {
                     $("#wait").loadHide();
                     if (req.errorCode > 0) {
                         errObj.set(req.errorCode, req.errorMessage, req.errorField);
                         return false;
                     } else {
                         tool_tips(words["delete success"]);
                         search_ajax();
                     }
                 },
                 type: "post",
                 url: "ajax/website_basic_table_delete.php"
             });
         }

         function head_html(rows) {
             var html = '<table class="tabQuery-table">';
             html += '<tr rid="header">';
             html += '<td class="tabQuery-table-header">' + words["basic.show"] + '</td>';
             html += '<td class="tabQuery-table-header">' + words["sn"] + '</td>';
             html += '<td class="tabQuery-table-header">' + words["basic.subject_en"] + '</td>';
             html += '<td class="tabQuery-table-header">' + words["basic.desc_en"] + '</td>';
             html += '<td class="tabQuery-table-header">' + words["basic.subject_cn"] + '</td>';
             html += '<td class="tabQuery-table-header">' + words["basic.desc_cn"] + '</td>';
             html += '<td class="tabQuery-table-header"></td>';
             html += '</tr>';

             for (var idx in rows) {
                 html += '<tr rid="' + rows[idx].id + '">';

                 html += '<td class="status" rid="' + rows[idx].id + '">';
                 html += '<input type="checkbox" class="status" rid="' + rows[idx].id + '" ' + (rows[idx].status=="1"?'checked':'') + ' value="1">';
                 html += '</td>';

                 html += '<td class="sn" rid="' + rows[idx].id + '">';
                 html += '<input class="sn" rid="' + rows[idx].id + '" style="width:40px; text-align:center;" value="' + rows[idx].sn + '">';
                 html += '</td>';


                 html += '<td class="title_en" rid="' + rows[idx].id + '">';
                 html += '<input class="title_en" rid="' + rows[idx].id + '" style="width:120px;" value="' + rows[idx].title_en + '">';
                 html += '</td>';

                 html += '<td class="desc_en" rid="' + rows[idx].id + '">';
                 html += '<input class="desc_en" rid="' + rows[idx].id + '" style="width:120px;" value="' + rows[idx].desc_en + '">';
                 html += '</td>';

                 html += '<td class="title_cn" rid="' + rows[idx].id + '">';
                 html += '<input class="title_cn" rid="' + rows[idx].id + '" style="width:120px;" value="' + rows[idx].title_cn + '">';
                 html += '</td>';

                 html += '<td class="desc_cn" rid="' + rows[idx].id + '">';
                 html += '<input class="desc_cn" rid="' + rows[idx].id + '" style="width:120px;" value="' + rows[idx].desc_cn + '">';
                 html += '</td>';

                 html += '<td align="left">';
                 html += '<a class="tabQuery-button tabQuery-button-save" oper="save" right="save" rid="' + rows[idx].id + '" title="' + words["save"] + '"></a>';
                 html += '<a class="tabQuery-button tabQuery-button-delete" oper="delete" right="delete" rid="' + rows[idx].id + '" title="' + words["delete"] + '"></a>';
                 html += '</td>';

                 html += '</tr>';
             }

             html += '<tr rid="-1">';

             html += '<td class="status" rid="-1">';
             html += '<input type="checkbox" class="status" checked="checked" rid="-1"  value="1">';
             html += '</td>';

             html += '<td class="sn" rid="-1">';
             html += '<input class="sn" rid="-1" style="width:40px;text-align:center;" value="">';
             html += '</td>';

             html += '<td class="title_en" rid="-1">';
             html += '<input class="title_en" rid="-1" style="width:120px;" value="">';
             html += '</td>';

             html += '<td class="desc_en" rid="-1">';
             html += '<input class="desc_en" rid="-1" style="width:120px;" value="">';
             html += '</td>';

             html += '<td class="title_cn" rid="-1">';
             html += '<input class="title_cn" rid="-1" style="width:120px;" value="">';
             html += '</td>';

             html += '<td class="desc_cn" rid="-1">';
             html += '<input class="desc_cn" rid="-1" style="width:120px;" value="">';
             html += '</td>';

             html += '<td align="left">';
             html += '<a class="tabQuery-button tabQuery-button-save" oper="save" right="save" rid="-1" title="保存"></a>';
             html += '</td>';

             html += '</tr>';
             html += '</table>';

             $("#basic_table").html(html);
         }



    	</script>
</head>
<body style="padding:0px; margin:0px;">
<?php 
include("admin_menu_html.php");
?>
    <br />
    <div style="display:block; padding:5px;">
                    <div id="group_edit" class="lwhTabber" style="width:100%;">
                        <div class="lwhTabber-header">
                            <a><?php echo $words["basic.basic information"]; ?><s></s></a>
                            <div class="line"></div>    
                        </div>
                        <div class="lwhTabber-content">
                            <div id="group_item">
                                <span style="font-size: 24px; vertical-align: middle;"><?php echo $words["basic.basic category"];?>: </span>
                                <select id="table_name" name="table_name" onchange="search_ajax()" style="font-size:20px; min-width: 250px; vertical-align: middle;">
                                <?php
                                    echo '<option value=""></option>';
                                    $query_list = "SELECT * FROM website_basic WHERE deleted <> 1 ORDER BY sn DESC";
                                    $result_list = $db->query($query_list);
                                    while($row_list = $db->fetch($result_list)) {
                                        echo '<option value="' . $row_list["table_name"] . '" title="' . ($admin_user["lang"]=="en"?$row_list["desc_en"]:cTYPE::gstr($row_list["desc_cn"])) . '">' . ($admin_user["lang"]=="en"?$row_list["title_en"]:cTYPE::gstr($row_list["title_cn"])) . '</option>';
                                    }
                                ?>
                                </select>
                                <div id="basic_table" style="min-height:400px;"></div>

                            </div><!-- end of <div id="group_item"> -->
                        </div>
                    </div><!-- end of <div id="group_edit"> -->
	</div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>