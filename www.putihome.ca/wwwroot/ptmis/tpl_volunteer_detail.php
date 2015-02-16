<?php include_once($CFG["include_path"] . "/lib/html/html.php"); ?>
<input type="hidden" id="member_id" name="member_id" value="" />
<div id="puti_volunteer_detail" style="background-color:#ffffff;">
	<center>
        <table class="table-td-border" border="0" cellpadding="2" cellspacing="0" width="100%" style="border:0px; background-color:#ffffff;">
            <tr>
                <td valign="top" colspan="2">
                    <fieldset style="border:1px solid #eeeeee;">
                    <legend>
					<span style="font-size:12px;font-weight:bold"><?php echo $words["member.professional"]?></span>
                    </legend>
                    <?php echo iHTML::checkbox($admin_user["lang"], $db, "vw_vol_professional", "professional", "", 99, 0, 1); ?>
                    <span style="margin-left:20px;"><?php echo $words["member.professional_other"]?> : </span>
                    <input class="form-input" style="width:120px;" id="professional_other" name="professional_other" value="" />
                	</fieldset>
                </td>
            </tr>

            <tr>
                <td valign="top" colspan="2">
                    <fieldset style="border:1px solid #eeeeee;">
                    <legend>
					<span style="font-size:12px;font-weight:bold"><?php echo $words["member.health"]?></span>
                    </legend>
                    <?php echo iHTML::checkbox($admin_user["lang"], $db, "vw_vol_health", "health", "", 99, 0, 1); ?>
                    <span style="margin-left:20px;"><?php echo $words["member.health_other"]?> : </span>
                    <input class="form-input" style="width:120px;" id="health_other" name="health_other" value="" />
                	</fieldset>
                </td>
            </tr>

            <tr>
                <td valign="top" colspan="2">
                  	<table width="100%">
                    	<tr>
                        	<td width="50%">
                                <fieldset style="border:1px solid #eeeeee;">
                                <legend>
                                <span style="font-size:12px;font-weight:bold"><?php echo $words["member.resume"]?></span>
                                </legend>
                                    <textarea id="resume" name="resume" style="width:100%; height:80px; border: 1px dotted #aaaaaa;"></textarea>
                                </fieldset>
                            </td>
                            <td width="50%">
                                <fieldset style="border:1px solid #eeeeee;">
                                <legend>
                                <span style="font-size:12px;font-weight:bold"><?php echo $words["member.memo"]?></span>
                                </legend>
                                    <textarea id="memo" name="memo" style="width:100%; height:80px; border: 1px dotted #aaaaaa;"></textarea>
                                </fieldset>
                            </td>
                         </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td valign="top" colspan="2">
                  	<table width="100%">
                    	<tr>
                        	<td width="50%">
                                <fieldset style="border:1px solid #eeeeee;">
                                <legend>
                                <span style="font-size:12px;font-weight:bold"><?php echo $words["member.will_depart"]?> : </span>
                                <a id="btn_vol_depart_will" href="javascript:vol_depart_will();" style="color:blue; text-decoration:underline; cursor:pointer;"><?php echo $words["member.edit"]?></a>
                                </legend>
                                    <input type="hidden" id="vol_depart_will" name="vol_depart_will" value="" />
                                    <div id="department_will" style="font-size:14px; min-height:40px; border: 1px dotted #aaaaaa; padding:10px;"></div>
                                </fieldset>
                            </td>
                            <td width="50%">
                                <fieldset style="border:1px solid #eeeeee;">
                                <legend>
                                <span style="font-size:12px;font-weight:bold"><?php echo $words["member.current_depart"]?> : </span>
                                <a id="btn_vol_depart_current" href="javascript:vol_depart_current();"  style="color:blue; text-decoration:underline; cursor:pointer;"><?php echo $words["member.edit"]?></a>
                                </legend>
                                    <input type="hidden" id="vol_depart_current" name="vol_depart_current" value="" />
                                    <div id="department_current" style="font-size:14px; min-height:40px; border: 1px dotted #aaaaaa; padding:10px;"></div>
                                </fieldset>
                            </td>
                         </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td valign="top" colspan="2">
                    <fieldset style="border:1px solid #eeeeee;">
                    <legend>
					<span style="font-size:12px;font-weight:bold"><?php echo $words["member.select_option"]?> : </span>
                    </legend>
					<table border="0" cellpadding="2" cellspacing="0">
                    	<tr>
                             <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["volunteer.type"]?>: </td>
                             <td>
                                    <?php echo iHTML::select($admin_user["lang"], $db, "vw_vol_type", "vol_type", "", 1); ?>
                             </td>
                            <td valign="top" style="width:60px; white-space:nowrap;" class="title"><?php echo $words["status"]?> <span style="color:red;">*</span> : </td>
                            <td>
                                  <select id="vol_status" style="width:100px;">
                                      <option value=""></option>
                                      <option value="0"><?php echo $words["inactive"]?></option>
                                      <option value="1"><?php echo $words["active"]?></option>
                                  </select>    
                            </td>
                             <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["email subscription"]?>: </td>
                             <td>
                                 <select id="vol_email_flag" name="vol_email_flag">
                                    <option value="0"></option>
                                    <option value="0"><?php echo $words["email.unsubscribe"]?></option>
                                    <option value="1"><?php echo $words["email.subscribe"]?></option>
                                </select>
                             </td>
                        </tr>
                    </table>
                    </fieldset>
                </td>
            </tr>


            <tr>
                <td valign="top" colspan="2">
                    <fieldset style="border:1px solid #eeeeee;">
                        <legend>
        					<span style="font-size:12px;font-weight:bold"><?php echo $words["volunteer.available_time"]?> : </span>
                        </legend>
                  	    <table width="100%">
                    	    <tr>
                        	    <td width="50%">
                                    <div style="width:100%; border:1px solid #cccccc; min-height:180px;">
                                        <table>
                                            <tr>
                                                <td style="width:30px;white-space:nowrap;"><?php echo $words["volunteer.schedule.date"]?>:</td>
                                                <td style="white-space:nowrap;">
                                                    <?php echo $words["from"]?>:
                                                    <input class="form-input" style="width:40px; text-align:center;" id="sdate_yy" name="sdate_yy" maxlength="4" value="<?php echo date("Y")?>" />
                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                    <select style="text-align:center;" id="sdate_mm" name="sdate_mm">
                                                        <option value=""></option>
                                                        <?php
                                                            for($i=1;$i<=12;$i++) {
                                                                echo '<option value="' . $i . '" ' . ($i==date("n")?'selected':'') .'>' . $i . '</option>';
                                                            }
                                                        ?>    
                                                    </select>
                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                    <select style="text-align:center;" id="sdate_dd" name="sdate_dd">
                                                        <option value=""></option>
                                                        <?php
                                                            for($i=1;$i<=31;$i++) {
                                                                echo '<option value="' . $i . '"' . ($i==date("j")?'selected':'') .'>' . $i . '</option>';
                                                            }
                                                        ?>    
                                                    </select>
                                                    <?php echo $words["to"]?>:
                                                    <input class="form-input" style="width:40px; text-align:center;" id="edate_yy" name="edate_yy" maxlength="4" value="<?php echo date("Y")?>" />
                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                    <select style="text-align:center;" id="edate_mm" name="edate_mm">
                                                        <option value=""></option>
                                                        <?php
                                                            for($i=1;$i<=12;$i++) {
                                                                echo '<option value="' . $i . '" ' . ($i==12?'selected':'') .'>' . $i . '</option>';
                                                            }
                                                        ?>    
                                                    </select>
                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                    <select style="text-align:center;" id="edate_dd" name="edate_dd">
                                                        <option value=""></option>
                                                        <?php
                                                            for($i=1;$i<=31;$i++) {
                                                                echo '<option value="' . $i . '"' . ($i==31?'selected':'') .'>' . $i . '</option>';
                                                            }
                                                        ?>    
                                                    </select>
                                                </td>
                                            </tr>



                                            <tr>
                                                <td style="width:30px;white-space:nowrap;"><?php echo $words["volunteer.schedule.time"]?>:</td>
                                                <td style="white-space:nowrap;">
                                                      <?php echo $words["from"]?> 
					                                  <?php 
					  	  	                                echo '<select id="stime_hh" name="stime_hh">';
					  		                                echo '<option value=""></option>';
							                                for($i=0; $i<=23; $i++) {
								                                echo '<option value="' . $i . '" ' . ($i==9?'selected':''). '>' . $i . '</option>';
							                                }
							                                echo '</select>';
                      		                                echo '<b> : </b>';
							                                echo '<select id="stime_mm" name="stime-mm">';
							                                echo '<option value=""></option>';
							                                echo '<option value="00">00</option>';
							                                echo '<option value="15">15</option>';
							                                echo '<option value="30" selected>30</option>';
							                                echo '<option value="45">45</option>';
							                                echo '</select>';
					                                  ?>
                                                      <?php echo $words["to"]?> 
					                                  <?php 
					  	  	                                echo '<select id="etime_hh" name="etime_hh">';
					  		                                echo '<option value=""></option>';
							                                for($i=0; $i<=23; $i++) {
								                                echo '<option value="' . $i . '" ' . ($i==17?'selected':''). '>' . $i . '</option>';
							                                }
							                                echo '</select>';
                      		                                echo '<b> : </b>';
							                                echo '<select id="etime_mm" name="etime-mm">';
							                                echo '<option value=""></option>';
							                                echo '<option value="00">00</option>';
							                                echo '<option value="15">15</option>';
							                                echo '<option value="30" selected>30</option>';
							                                echo '<option value="45">45</option>';
							                                echo '</select>';
					                                  ?>
                                                </td>
                                            </tr>

                    	                    <tr>
                                                 <td class="title" style="width:30px;white-space:nowrap;"><?php echo $words["volunteer.schedule.type"]?><span style="color:red;">*</span>:</td>
                                                 <td style="font-size: 14px;">
                                                        <input type="radio" id="schedule_type_0" name="schedule_type" value="0"><label style="cursor:pointer;" for="schedule_type_0"><?php echo $words["volunteer.schedule.type.daily"]?></label>
                                                        <input type="radio" id="schedule_type_1" name="schedule_type" value="1"><label style="cursor:pointer;" for="schedule_type_1"><?php echo $words["volunteer.schedule.type.weekly"]?></label>
                                                        <input type="radio" id="schedule_type_2" name="schedule_type" value="2"><label style="cursor:pointer;" for="schedule_type_2"><?php echo $words["volunteer.schedule.type.monthly"]?></label>
                                                 </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <div id="div_schedule_weekly" style="display:none;">
                                                        <fieldset>
                                                            <legend><?php echo $words["volunteer.schedule.type.weekly"]?></legend>
                                                            <input type="checkbox" id="weekly_day_1" name="weekly_day" value="1"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.mon"]);?></label>
                                                            <input type="checkbox" id="weekly_day_2" name="weekly_day" value="2"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.tue"]);?></label>
                                                            <input type="checkbox" id="weekly_day_3" name="weekly_day" value="3"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.wed"]);?></label>
                                                            <input type="checkbox" id="weekly_day_4" name="weekly_day" value="4"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.thur"]);?></label>
                                                            <input type="checkbox" id="weekly_day_5" name="weekly_day" value="5"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.fri"]);?></label>
                                                            <input type="checkbox" id="weekly_day_6" name="weekly_day" value="6"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.sat"]);?></label>
                                                            <input type="checkbox" id="weekly_day_7" name="weekly_day" value="7"><label for="weekly_day_1"><?php echo cTYPE::gstr($words["weekday.sun"]);?></label>
                                                        </fieldset>
                                                    </div>
                                                    <div id="div_schedule_monthly" style="display:none;">
                                                        <fieldset>
                                                            <legend><?php echo $words["volunteer.schedule.type.monthly"]?></legend>
                                                                 <table cellpadding="0" cellspacing="0">
                                                                 <?php
                                                                    for($i=1;$i<=31;$i++) {
                                                                        if($i==1) echo "<tr>";
                                                                        if($i==11) echo "</tr><tr>";
                                                                        if($i==21) echo "</tr><tr>";
                                                                        echo '<td style="white-space:nowrap; padding:0px; margin:0px;"><input type="checkbox" id="monthly_day_' . $i . '" name="monthly_day"  value="' . $i . '"><label for="monthly_day_' . $i . '">' . $i . '</label></td>';
                                                                        if($i==31) echo "</tr>";
                                                                    }
                                                                  ?>
                                                                  </table>    
                                                        </fieldset>
                                                    </div>
                                                </td>
                                            </tr>


                                        </table>
                                    </div>
                                </td>
                        	    <td width="32" align="center" valign="middle">
                                    <a href="javascript:volunteer_schedule_save_ajax();" style="cursor:pointer;" title="Add to Schedule"><img src="<?php echo $CFG["http"] . $CFG["web_domain"];?>/theme/blue/image/icon/move_next.png" /></a>
                                </td>
                                <td width="50%">
                                    <div id="volunteer_schedule_list" style="width:100%; overflow:auto; border:1px solid #cccccc; height:180px;">
                                    </div>
                                </td>
                             </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>


            <tr>
                <td colspan="2" align="center">
                     	<input type="button" id="btn_detail_save" right="save" onclick="volunteer_detail_save_ajax();" value="<?php echo $words["button save"]?>" />
                     	<input type="button" id="btn_detail_close" right="view" onclick="volunteer_detail_close();" value="<?php echo $words["button close"]?>" />
                </td>
            </tr>
        </table>
   </center>
</div>

<script language="javascript" type="text/javascript">
    var htmlObj_vdetail = new LWH.cHTML();
    $(function () {
        $("input:radio[name='schedule_type']").live("click", function (ev) {
            switch ($(this).val()) {
                case "0":
                    $("#div_schedule_weekly").hide();
                    $("#div_schedule_monthly").hide();
                    break;
                case "1":
                    $("#div_schedule_weekly").show();
                    $("#div_schedule_monthly").hide();
                    break;
                case "2":
                    $("#div_schedule_weekly").hide();
                    $("#div_schedule_monthly").show();
                    break;
                default:
                    $("#div_schedule_weekly").hide();
                    $("#div_schedule_monthly").hide();
                    break;
            }
        });

        $(".tabQuery-button-delete[oper='schedule-delete']").live("click", function (ev) {
            /*
            var yes = false;
            yes = window.confirm("Are you sure delete this record？");
            if (!yes) return;
            */
            volunteer_schedule_delete_ajax($(this).attr("sid"));
        });

        $("#volunteer_department_trigger").val("#btn_vol_depart_current, #btn_vol_depart_will, #btn_vol_depart_search");
        $("#volunteer_department_offset").val("");
    })
    function volunteer_detail_save_ajax(eid, rid) {
        $.ajax({
            data: {
                admin_sess: $("input#adminSession").val(),
                admin_menu: $("input#adminMenu").val(),
                admin_oper: "save",

                member_id: $("#member_id").val(),
                professional: htmlObj.checkbox_get("professional"),
                health: htmlObj.checkbox_get("health"),
                professional_other: $("#professional_other").val(),
                health_other: $("#health_other").val(),
                resume: $("#resume").val(),
                memo: $("#memo").val(),
                vol_type: $("#vol_type").val(),
                vol_depart_will: $("#vol_depart_will").val(),
                vol_depart_current: $("#vol_depart_current").val(),
                status: $("#vol_status").val(),
                email_flag: $("#vol_email_flag").val()
            },
            dataType: "json",
            error: function (xhr, tStatus, errorTh) {
                //$("#wait").loadHide();
                alert("Error (tpl_volunteer_detail_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
            },
            success: function (req, tStatus) {
                //$("#wait").loadHide();
                if (req.errorCode > 0) {
                    errObj.set(req.errorCode, req.errorMessage, req.errorField);
                    return false;
                } else {
                    tool_tips(words["save success"]);
                    $("tr.volunteer-detail-area").stop().hide(500);
                }
            },
            type: "post",
            url: "ajax/tpl_volunteer_detail_save.php"
        });
    }

    function volunteer_detail_close() {
        $("tr.volunteer-detail-area").stop().hide(500);
    }

    var tmp_member_id = 0;
    function volunteer_detail_search_ajax(member_id) {
        if (tmp_member_id != member_id) {
            $("input:radio[name='schedule_type']").attr("checked", false);
            $("input:checkbox[name='weekly_day']").attr("checked", false);
            $("input:checkbox[name='monthly_day']").attr("checked", false);

            $("#div_schedule_weekly").hide();
            $("#div_schedule_monthly").hide();
            tmp_member_id = member_id;
        }
        clear_detail();
        $.ajax({
            data: {
                admin_sess: $("input#adminSession").val(),
                admin_menu: $("input#adminMenu").val(),
                admin_oper: "view",

                member_id: $("#member_id").val()
            },
            dataType: "json",
            error: function (xhr, tStatus, errorTh) {
                //$("#wait").loadHide();
                alert("Error (tpl_volunteer_detail_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
            },
            success: function (req, tStatus) {
                //$("#wait").loadHide();
                if (req.errorCode > 0) {
                    errObj.set(req.errorCode, req.errorMessage, req.errorField);
                    return false;
                } else {
                    fresh_detail(req.data.detail);
                    volunteer_schedule_html(req.data.schedule);
                    //tool_tips(words["save success"]);
                }
            },
            type: "post",
            url: "ajax/tpl_volunteer_detail_select.php"
        });
    }

    function volunteer_schedule_delete_ajax(sid) {
        $.ajax({
            data: {
                admin_sess: $("input#adminSession").val(),
                admin_menu: $("input#adminMenu").val(),
                admin_oper: "delete",

                member_id: $("#member_id").val(),
                schedule_id: sid
            },
            dataType: "json",
            error: function (xhr, tStatus, errorTh) {
                //$("#wait").loadHide();
                alert("Error (tpl_volunteer_schedule_delete.php): " + xhr.responseText + "\nStatus: " + tStatus);
            },
            success: function (req, tStatus) {
                //$("#wait").loadHide();
                if (req.errorCode > 0) {
                    errObj.set(req.errorCode, req.errorMessage, req.errorField);
                    return false;
                } else {
                    tool_tips(words["delete success"]);
                    volunteer_schedule_html(req.data.schedule);
                }
            },
            type: "post",
            url: "ajax/tpl_volunteer_schedule_delete.php"
        });
    }


    function volunteer_schedule_save_ajax() {
        $.ajax({
            data: {
                admin_sess: $("input#adminSession").val(),
                admin_menu: $("input#adminMenu").val(),
                admin_oper: "save",

                member_id: $("#member_id").val(),
                start_date: ($("#sdate_yy").val() + "-" + $("#sdate_mm").val() + "-" + $("#sdate_dd").val()),
                end_date: ($("#edate_yy").val() + "-" + $("#edate_mm").val() + "-" + $("#edate_dd").val()),
                start_time: ($("#stime_hh").val() + ":" + $("#stime_mm").val()),
                end_time: ($("#etime_hh").val() + ":" + $("#etime_mm").val()),
                schedule_type: htmlObj.radio_get("schedule_type"),
                weekly_days: htmlObj.checkbox_get("weekly_day"),
                monthly_days: htmlObj.checkbox_get("monthly_day")
            },
            dataType: "json",
            error: function (xhr, tStatus, errorTh) {
                //$("#wait").loadHide();
                alert("Error (tpl_volunteer_schedule_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
            },
            success: function (req, tStatus) {
                //$("#wait").loadHide();
                if (req.errorCode > 0) {
                    errObj.set(req.errorCode, req.errorMessage, req.errorField);
                    return false;
                } else {
                    tool_tips(words["add success"]);
                    volunteer_schedule_html(req.data.schedule);
                }
            },
            type: "post",
            url: "ajax/tpl_volunteer_schedule_save.php"
        });
    }


    function volunteer_schedule_html(objs) {
        var html = '<table class="tabQuery-table" border="1" cellpadding="0" cellspacing="0">';
        html += '<tr>';
        html += '<td width="20" class="tabQuery-table-header"   style="height:12px; font-size:10px; border:1px solid #cccccc;"></td>';
        html += '<td width="20" class="tabQuery-table-header"   style="height:12px; font-size:10px;border:1px solid #cccccc; ">' + words["sn"] + '</td>';
        html += '<td class="tabQuery-table-header"  style="height:12px; font-size:10px;border:1px solid #cccccc;">' + words["volunteer.schedule.type"] + '</td>';
        html += '<td class="tabQuery-table-header"  style="height:12px; font-size:10px;border:1px solid #cccccc;">' + words["start date"] + '</td>';
        html += '<td class="tabQuery-table-header"  style="height:12px; font-size:10px;border:1px solid #cccccc;">' + words["end date"] + '</td>';
        html += '<td class="tabQuery-table-header"  style="height:12px; font-size:10px;border:1px solid #cccccc;">' + words["start time"] + '</td>';
        html += '<td class="tabQuery-table-header"  style="height:12px; font-size:10px;border:1px solid #cccccc;">' + words["end time"] + '</td>';
        html += '</tr>';
        for (var idx in objs) {
            var obj = objs[idx];
            var rowspan = 1;
            if (obj.days != "") {
                rowspan = 2;
            }
            html += '<tr>';

            html += '<td rowspan="' + rowspan + '" width="20" align="center" style="border:1px solid #cccccc;">';
            html += '<a class="tabQuery-button tabQuery-button-delete" oper="schedule-delete" right="delete" sid="' + obj.id + '" title="Delete Record"></a>';
            html += '</td>'

            html += '<td rowspan="' + rowspan + '" width="20" align="center" style="border:1px solid #cccccc;">';
            html += parseInt(idx) + 1;
            html += '</td>';

            html += '<td rowspan="' + rowspan + '" style="border:1px solid #cccccc;">';
            html += obj.schedule_type;
            html += '</td>'


            html += '<td style="border:1px solid #cccccc;">';
            html += obj.start_date;
            html += '</td>'

            html += '<td style="border:1px solid #cccccc;">';
            html += obj.end_date;
            html += '</td>'

            html += '<td style="border:1px solid #cccccc;">';
            html += obj.start_time;
            html += '</td>'

            html += '<td style="border:1px solid #cccccc;">';
            html += obj.end_time;
            html += '</td>'

            html += '</tr>';

            if (obj.days != "") {
                html += '<tr>';
                html += '<td colspan="4" align="left" style="border:1px solid #cccccc;">';
                html += obj.days;
                html += '</td>'
                html += '</tr>';
            }
        }
        html += '</table>';
        $("#volunteer_schedule_list").html(html);
    }

    function fresh_detail(detail) {
        htmlObj_vdetail.checkbox_set("professional", detail.professional);
        htmlObj_vdetail.checkbox_set("health", detail.health);
        $("#professional_other").val(detail.professional_other);
        $("#health_other").val(detail.health_other);
        $("#resume").val(detail.resume);
        $("#memo").val(detail.memo);
        $("#vol_type").val(detail.vol_type);
        $("#vol_status").val(detail.status);
        $("#vol_email_flag").val(detail.email_flag);
        $("#vol_depart_current").val(detail.depart_current);
        $("#vol_depart_will").val(detail.depart_will)
        $("#department_current").html(detail.depart_current_html);
        $("#department_will").html(detail.depart_will_html)
    }

    function clear_detail() {
        htmlObj_vdetail.checkbox_clear("professional");
        htmlObj_vdetail.checkbox_clear("health");
        $("#professional_other").val("");
        $("#health_other").val("");
        $("#resume").val("");
        $("#memo").val("");
        $("#vol_type").val("")
        $("#vol_status").val("");
        $("#vol_email_flag").val("");
        $("#vol_depart_current").val("");
        $("#vol_depart_will").val("")
        $("#department_current").html("");
        $("#department_will").html("");
    }



    function vol_depart_will() {
        $("#volunteer_department_init").val($("#vol_depart_will").val());
        $("#volunteer_department_id_el").val("#vol_depart_will");
        $("#volunteer_department_title_el").val("#department_will");
    }

    function vol_depart_current() {
        $("#volunteer_department_init").val($("#vol_depart_current").val());
        $("#volunteer_department_id_el").val("#vol_depart_current");
        $("#volunteer_department_title_el").val("#department_current");
    }

    function vol_depart_search() {
        $("#volunteer_department_init").val($("#vol_depart_search").val());
        $("#volunteer_department_id_el").val("#vol_depart_search");
        $("#volunteer_department_title_el").val("#department_search");
    }
	
	
</script>