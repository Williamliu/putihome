<div id="volunteer_department" class="lwhSlidebox">
	<div id="volunteer_department_nodes" class="lwhSlidebox-content lwhSlidebox-vscroll lwhSlidebox-bgColor2">
    	<div id="volunteer_depart_selector" style="min-width:360px; height:420px; overflow:auto;">
        </div>
	</div>
</div>
<input type="hidden" id="volunteer_department_offset"   value="" />
<input type="hidden" id="volunteer_department_trigger"   value="" />
<input type="hidden" id="volunteer_department_init"   value="" />
<input type="hidden" id="volunteer_department_id_el" value="" />
<input type="hidden" id="volunteer_department_title_el" value="" />
<script language="javascript" type="text/javascript">
    var htmlObj_depart = new LWH.cHTML();
    $(function () {
        depart_ajax();
    })


    function depart_ajax() {
        $("#wait").loadShow();
        $.ajax({
            data: {
                admin_sess: $("input#adminSession").val(),
                admin_menu: $("input#adminMenu").val(),
                admin_oper: "view"
            },
            dataType: "json",
            error: function (xhr, tStatus, errorTh) {
                $("#wait").loadHide();
                alert("Error (pt_department_select.php): " + xhr.responseText + "\nStatus: " + tStatus);
            },
            success: function (req, tStatus) {
                $("#wait").loadHide();
                if (req.errorCode > 0) {
                    errObj.set(req.errorCode, req.errorMessage, req.errorField);
                    return false;
                } else {
                    departHTML(req.data.departs);
                    init_volunteer_depart();
                }
            },
            type: "post",
            url: "ajax/pt_department_select.php"
        });
    }

    function init_volunteer_depart() {
        $("#volunteer_department").lwhSlidebox({ title: words["volunteer department"],
            iconClose: true,
            inBound: true,
            offsetTo: $("#volunteer_department_offset").val(),
            top: "middle",
            left: "center",
            trigger: $("#volunteer_department_trigger").val(), 
            box_init: function () { },
            box_open: function () { htmlObj_depart.checkbox_set1("volunteer_depart", "rid", $("#volunteer_department_init").val() ); },
            box_close: function () { 
                                        $($("#volunteer_department_id_el").val()).val(
                                                    htmlObj_depart.checkbox_get1("volunteer_depart", "rid")
                                                    ); 
                                        $($("#volunteer_department_title_el").val()).html(
                                                    htmlObj_depart.checkbox_title1("volunteer_depart",".volunteer_depart", "rid")
                                                    ); 
                                   }
        });
    }

    function departHTML(departs) {
        $("#volunteer_depart_selector").html("");
        var html = '';
        html += '<a class="puti_organization" style="font-size:14px; font-weight:bold; cursor:pointer; vertical-align:middle;" rid="-1" title="' + words["puti.organization structure"] + '">' + words["puti.organization structure"] + '</a>';
        //html += '<input class="" type="checkbox" style="vertical-align:middle;" rid="0" value="1">';
        html += departNode(departs);
        $("#volunteer_depart_selector").html(html);
        $(".lwhTree").lwhTree();
    }

    function departNode(departs) {
        var html = '';
        html += '<ul class="lwhTree">';
        var cnt0 = 0;
        for (var key0 in departs) {
            cnt0++;
            if (departs[key0].departs && departs[key0].departs.length > 0) {
                var color = departs[key0].status == 1 ? '#000000' : '#CC1A29';
                html += '<li class="nodes nodes-open puti-depart" rid="' + departs[key0].id + '"><s class="node-line"></s><s class="node-img"></s>';
                html += '<a class="volunteer_depart" rid="' + departs[key0].id + '" style="color:' + color + '; font-size:12px; font-weight:bold; vertical-align:middle; cursor:pointer;" title="' + departs[key0].description + '">' + departs[key0].title + '</a>';
                html += departNode(departs[key0].departs);
            } else {
                var color = departs[key0].status == 1 ? '#333333' : '#CC1A29';
                html += '<li class="node puti-depart" rid="' + departs[key0].id + '"><s class="node-line"></s><s class="node-img"></s>';
                html += '<a class="volunteer_depart" rid="' + departs[key0].id + '" style="color:' + color + '; cursor:pointer; vertical-align:middle;" title="' + departs[key0].description + '">' + departs[key0].title + '</a>';
                html += '<input type="checkbox" class="volunteer_depart" name="volunteer_depart" rid="' + departs[key0].id + '" value="1">';
                html += '</li>';
            }
        }
        html += '</ul>';
        return html;
    }
</script>