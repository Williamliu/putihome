<?php
session_start();
ini_set("display_errors", 0);
include_once("../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/admin_menu_struct.php");
$admin_menu="700,40";
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
		<title>Bodhi Meditation Course Report</title>

		<?php include("admin_head_link.php"); ?>
		
   		<script type="text/javascript" 	src="../js/js.lwh.table.js"></script>
        <link 	type="text/css" 		href="../theme/blue/js.lwh.table.css" rel="stylesheet" />
        <script language="javascript" type="text/javascript">
            $(function () {
                $("#start_date, #end_date").datepicker({
                    dateFormat: 'yy-mm-dd',
                    showOn: "button",
                    buttonImage: "../theme/blue/image/icon/calendar.png",
                    buttonImageOnly: true
                });
                list_event();
            });

            function list_event() {
                $("#wait").loadShow();
                $.ajax({
                    data: {
                        admin_sess: $("input#adminSession").val(),
                        admin_menu: $("input#adminMenu").val(),
                        admin_oper: "view",

                        start_date: $("#start_date").val(),
                        end_date: $("#end_date").val()
                    },
                    dataType: "json",
                    error: function (xhr, tStatus, errorTh) {
                        $("#wait").loadHide();
                        alert("Error (event_calendar_report_event.php): " + xhr.responseText + "\nStatus: " + tStatus);
                    },
                    success: function (req, tStatus) {
                        $("#wait").loadHide();
                        if (req.errorCode > 0) {
                            errObj.set(req.errorCode, req.errorMessage, req.errorField);
                            return false;
                        } else {
                            evtToHTML(req.data.evt);
                            //jsonToHTML(req.data.evt);
                        }
                    },
                    type: "post",
                    url: "ajax/event_calendar_report_event.php"
                });
            }

            function report_event() {
                $("#wait").loadShow();
                $.ajax({
                    data: {
                        admin_sess: $("input#adminSession").val(),
                        admin_menu: $("input#adminMenu").val(),
                        admin_oper: "view",

                        event_id: $("#event_id").val()
                    },
                    dataType: "json",
                    error: function (xhr, tStatus, errorTh) {
                        $("#wait").loadHide();
                        alert("Error (event_calendar_report_report.php): " + xhr.responseText + "\nStatus: " + tStatus);
                    },
                    success: function (req, tStatus) {
                        $("#wait").loadHide();
                        if (req.errorCode > 0) {
                            errObj.set(req.errorCode, req.errorMessage, req.errorField);
                            return false;
                        } else {
                            toHTML(req.data);
                            //jsonToHTML(req.data.evt);
                        }
                    },
                    type: "post",
                    url: "ajax/event_calendar_report_report.php"
                });
            }

            function toHTML(data) {
                var tmp_html = '<table class="tabQuery-table" cellspacing="0" cellpadding="1" border="1">';
                tmp_html += headHTML(data.others);
                tmp_html += pageHTML(data);
                tmp_html += '</table>';
                $("#calendar_report").html(tmp_html);
            }

            function headHTML(others) {
                var css_one = 'style="background-color:#F2E8F9;"';
                var css_two = 'style="background-color:#E3F0FD;"';
                var css_cnt = 0;

                var tmp_html = '<tr>';
                var hcnt = 0;
                for (var idx1 in others) {
                    var css = (css_cnt++ % 2) == 0 ? css_one : css_two;
                    for (var i = 1; i <= others[idx1].checkin; i++) {
                        hcnt++;
                        tmp_html += '<td class="tabQuery-table-header" ' + css + ' width="20">' + i + '</td>';
                    }
                }
                tmp_html += '</tr>';


                var html = '';
                html += '<tr>';
                html += '<td colspan="' + (18 + hcnt) + '" align="center"><span style="font-size:12px; font-weight:bold;">' + words["menu_cccrr"] + '</span></td>';
                html += '</tr>';

                html += '<tr>';
                html += '<td rowspan="2" width="20" class="tabQuery-table-header">' + words["sn"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["group"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["name"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["dharma"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["tag.title"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["new people"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["web"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["trial"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["a.sign"] + '</td>';
                
                css_cnt = 0;
                for (var idx1 in others) {
                    var css = (css_cnt++ % 2) == 0 ? css_one : css_two;
                    html += '<td class="tabQuery-table-header" ' + css + ' colspan="' + others[idx1].checkin + '">' + others[idx1].event_md + '<br>' + words["day"] + ' ' + others[idx1].day_no + ' ' + words["day1"] + '</td>';
                }

                html += '<td rowspan="2" class="tabQuery-table-header">' + words["total checkin"] + '</td>';
                //html += '<td rowspan="2" class="tabQuery-table-header">' + words["unauth"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["total attend"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["total leave"] + '</td>';

                html += '<td rowspan="2" class="tabQuery-table-header">' + words["attd."] + '</td>';

                html += '<td rowspan="2" class="tabQuery-table-header">' + words["grad."] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["cert."] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["cert_no"] + '</td>';
                html += '<td rowspan="2" class="tabQuery-table-header">' + words["doc no"] + '</td>';

                html += '<td rowspan="2" class="tabQuery-table-header">' + words["shoes.shelf"] + '</td>';
                html += '</tr>';

                html += tmp_html;
                return html;
            }

            function pageHTML(pgData) {
                var css_one = 'style="background-color:#F2E8F9;"';
                var css_two = 'style="background-color:#E3F0FD;"';
                var css_cnt = 0;

                var html = '';
                for (var idx in pgData.rows) {
                    var eObj = pgData.rows[idx];
                    html += '<tr>';

                    html += '<td width="20" align="center" class="enroll-uncheck" enroll_id="' + eObj.enroll_id + '">';
                    html += parseInt(idx) + 1;
                    html += '</td>';

                    html += '<td align="center" class="enroll-check" enroll_id="' + eObj.enroll_id + '"><b>';
                    html += eObj.group_no > 0 ? eObj.group_no : '';
                    html += '</b></td>';

                    html += '<td style="white-space:nowrap;">';
                    html += eObj.name;
                    html += '</td>';

                    html += '<td style="white-space:nowrap;">';
                    html += eObj.dharma_name;
                    html += '</td>';

                    html += '<td style="white-space:nowrap;">';
                    html += eObj.title;
                    html += '</td>';

                    html += '<td align="center">';
                    html += eObj.new_flag;
                    html += '</td>';

                    html += '<td align="center">';
                    html += eObj.online;
                    html += '</td>';

                    html += '<td align="center">';
                    html += eObj.trial;
                    html += '</td>';

                    html += '<td align="center">';
                    html += eObj.signin;
                    html += '</td>';

                    css_cnt = 0;
                    for (var idx1 in pgData.others) {
                        css = (css_cnt++ % 2) == 0 ? css_one : css_two;
                        for (var i = 1; i <= pgData.others[idx1].checkin; i++) {
                            var status = "";
                            var st = 0;
                            if (eObj.dates[pgData.others[idx1].event_date_id] && eObj.dates[pgData.others[idx1].event_date_id][i]) {
                                st = eObj.dates[pgData.others[idx1].event_date_id][i];
                                if (st == 0) status = '';
                                if (st == 2) status = '<span style="color:blue;">Y</span>';
                                if (st == 4) status = '<span style="color:red;">*</span>';
                                if (st == 8) status = '<span style="color:red;">M</span>';
                            }

                            html += '<td class="tabQuery-table-header" ' + css + ' width="20" title="Day ' + pgData.others[idx1].day_no + '">';
                            html += status;
                            html += '</td>';
                        }
                    }

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.total_checkin;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.total_attend;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.total_leave;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.attd > 0 ? (Math.round(parseFloat(eObj.attd) * 100)).toString() + '%' : ' ';
                    html += '</td>';

                    html += '<td class="tabQuery-table-header" align="center">';
                    html += eObj.graduate;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header" align="center">';
                    html += eObj.cert;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.cert_no;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.doc_no;
                    html += '</td>';

                    html += '<td class="tabQuery-table-header">';
                    html += eObj.shelf;
                    html += '</td>';

                    html += '</tr>';
                }
                return html;
            }


            function print_event() {
                if ($("iframe[name='ifm_list_excel']").length > 0) {
                    $("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());
                    $("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());
                    $("input[name='admin_oper']", "form[name='frm_list_excel']").val("view");

                    $("input[name='event_id']", "form[name='frm_list_excel']").val($("#event_id").val());
                } else {
                    var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild; ;
                    var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild; ;
                    $("form[name='frm_list_excel']").attr({ "action": "ajax/event_calendar_report_print.php", "target": "ifm_list_excel" });
                    $("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');
                    $("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');
                    $("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="view" />');

                    $("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("#event_id").val() + '" />');
                }
                $("form[name='frm_list_excel']").submit();
            }

            function evtToHTML(eObj) {
                var html = '<select id="event_id">';
                html += '<option value=""></option>';
                for (var idx in eObj) {
                    var ttt = eObj[idx].title + "[" + eObj[idx].start_date + "~" + eObj[idx].end_date + "]";
                    html += '<option value="' + eObj[idx].id + '">' + ttt + '</option>';
                }
                html += '</select>';
                $("#event_list").html(html);
            }

            function jsonToHTML(evtObj) {
                var c1 = ' style="background-color:#FFF5D7;"';
                var c2 = ' style="background-color:#EBFAD3;"';

                var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
                html += '<tr>';
                html += '<td colspan="18" align="center"><span style="font-size:12px; font-weight:bold;">' + words["event attendance report"] + '</span></td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td colspan="5" class="tabQuery-table-header" style="text-align:left;">' + words["event title"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["start date"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["end date"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["status"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="网上注册">' + words["web"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="签同意书人数">' + words["sign"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="毕业人数">' + words["grad."] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="拿证书人数">' + words["cert."] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="试听人数">' + words["trial"] + '</td>';
                //html += '<td class="tabQuery-table-header" rowspan="2" title="未報名人数">' + words["unauth"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="报名人数">' + words["enroll"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="出席人数">' + words["att.pp"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="打卡次数">' + words["pun.tm"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="打卡人数">' + words["pun.pp"] + '</td>';
                html += '<td class="tabQuery-table-header" rowspan="2" title="出勤率">' + words["att.rate"] + '</td>';
                html += '</tr>';

                html += '<tr>';
                html += '<td width="20" class="tabQuery-table-header">' + words["sn"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["group"] + '</td>';
                html += '<td class="tabQuery-table-header" colspan="2">' + words["name"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["age"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["member enter date"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["phone"] + '</td>';
                html += '<td class="tabQuery-table-header">' + words["email"] + '</td>';
                html += '</tr>';

                html += '<tr>';
                html += '<td colspan="5" align="left"' + c1 + '><b>';
                html += evtObj.title;
                html += '</b></td>';

                html += '<td' + c1 + '><b>';
                html += evtObj.start_date;
                html += '</b></td>';

                html += '<td' + c1 + '><b>';
                html += evtObj.end_date;
                html += '</b></td>';

                html += '<td align="center"' + c1 + '><b>';
                html += evtObj.status;
                html += '</b></td>';

                html += '<td align="center"' + c1 + '><b>';
                html += evtObj.online;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.signin;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.graduate;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.cert;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.trial;
                html += '</b></td>';

                /*
                html += '<td align="right"' + c1 + '><b>';
                html +=  evtObj.unauth;
                html += '</b></td>';
                */

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.enroll;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.attend;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.punch;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.student;
                html += '</b></td>';

                html += '<td align="right"' + c1 + '><b>';
                html += evtObj.att_per;
                html += '</b></td>';

                html += '</tr>';

                for (var idx in evtObj.list) {
                    var ee = evtObj.list[idx];
                    html += '<td width="20" align="center"' + c2 + '>';
                    html += parseInt(idx) + 1;
                    html += '</td>';

                    html += '<td align="center"' + c2 + '><b>';
                    html += ee.group_no; //+ '{<span style="color:blue;">' + ee.date_range +'</span>}';
                    html += '</b></td>';

                    html += '<td' + c2 + ' colspan="2">';
                    html += ee.name;
                    html += '</td>';

                    html += '<td' + c2 + ' align="center">';
                    html += ee.age;
                    html += '</td>';

                    html += '<td' + c2 + ' align="center">';
                    html += ee.member_date;
                    html += '</td>';

                    html += '<td' + c2 + '>';
                    html += ee.phone;
                    html += '</td>';

                    html += '<td' + c2 + '>';
                    html += ee.email;
                    html += '</td>';

                    html += '<td align="center"' + c2 + '>';
                    html += ee.online;
                    html += '</td>';

                    html += '<td align="center"' + c2 + '>';
                    html += ee.signin;
                    html += '</td>';

                    html += '<td align="center"' + c2 + '>';
                    html += ee.graduate;
                    html += '</td>';

                    html += '<td align="center"' + c2 + '>';
                    html += ee.cert;
                    html += '</td>';

                    html += '<td align="center"' + c2 + '>';
                    html += ee.trial;
                    html += '</td>';
                    /*
                    html += '<td align="center"' + c2 + '>';
                    html +=  ee.unauth;
                    html += '</td>';
                    */
                    html += '<td align="center"' + c2 + '>';
                    html += '</td>';
                    html += '<td align="center"' + c2 + '>';
                    html += '</td>';
                    html += '<td align="center"' + c2 + '>';
                    html += ee.punch;
                    html += '</td>';
                    html += '<td align="center"' + c2 + '>';
                    html += '</td>';

                    html += '<td align="right"' + c2 + '>';
                    html += ee.attend;
                    html += '</td>';

                    html += '</tr>';
                }
                html += '</table>';
                $("#calendar_report").html(html);
            }

        </script>

</head>
<body>
<?php 
include("admin_menu_html.php");
?>
    <br />
    <span style="font-size:12px; font-weight:bold; margin-left:10px;"><?php echo $words["date range"]?>: </span>
    From <input style="width:80px;" id="start_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("j")-30,date("Y")));?>" /> 
    TO <input style="width:80px;" id="end_date" value="<?php echo date("Y-m-d", mktime(0,0,0,date("n"),date("j")+30,date("Y")));?>" />
    <input type="button" id="btn_search" right="view" onclick="list_event()" value="<?php echo $words["search event"]?>" /> 
    <br /> 
    <span style="font-size:12px; font-weight:bold; margin-left:10px;"><?php echo $words["event list"]?>: </span> 
    <span id="event_list">
            <select id="event_id" style="min-width:300px;">
            <?php 
                $fdate 	= mktime(0,0,0, 1 ,1, date("Y"));

                $query = "SELECT a.id, a.title, a.start_date, a.end_date, b.title as site_desc  
								FROM event_calendar a
			 					INNER JOIN puti_sites b ON (a.site = b.id) 
								WHERE a.deleted <> 1 AND start_date >= '" . $fdate . "' AND
									  a.site IN " . $admin_user["sites"]  . " AND
									  a.branch IN ". $admin_user["branchs"] . " 
                                ORDER BY a.start_date ASC";
                $result = $db->query($query);
                echo '<option value=""></option>';
                while( $row = $db->fetch($result) ) {
                    $date_str = date("Y: M-d",$row["start_date"]) . ($row["end_date"]>0?" ~ ".date("M-d",$row["end_date"]):"");
                    echo '<option value="' . $row["id"] . '">' . cTYPE::gstr($words[strtolower($row0["site_desc"])]) . ' - ' . $row["title"] . "[" . $date_str . ']</option>';
                }
            ?>
            </select>
    </span>
    <br />
    <input type="button" id="btn_print" right="view" style="margin-left:50px;" onclick="report_event()"  value="<?php echo $words["g.report"]?>" /> 
    <input type="button" id="btn_print" right="print" onclick="print_event()"  value="<?php echo $words["output excel"]?>" /> 
	<br />
    <br />
    <br />
    <br />
	<div id="calendar_report" style="padding:5px; position:absolute; float:left; left:5px; min-height:420px;">
    </div>
<?php 
include("admin_footer_html.php");
?>
</body>
</html>