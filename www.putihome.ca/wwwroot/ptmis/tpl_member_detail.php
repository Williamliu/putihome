<script type="text/javascript" language="javascript">
$(function(){
	$("#member_detail_tabber").lwhTabber();

	$("#diaglog_member_detail").lwhDiag({
		titleAlign:		"center",
		title:			words["member details"],
		
		cnColor:		"#F8F8F8",
		bgColor:		"#EAEAEA",
		ttColor:		"#94C8EF",
		 
		minWW:			700,
		minHH:			480,
		btnMax:			false,
		resizable:		false,
		movable:		true,
		maskable: 		true,
		maskClick:		true,
		pin:			false
	});
	
	$("#btn_member_detail_save").bind("click", function(ev) {
		member_detail_save_ajax();
	});


	//////// ajax upload and image
	$(".lwhZoom").lwhZoom();
	
	aj = new LWH.AjaxUpload({
		url:		"ajax/lwhUpload_save.php", 
		btnUpload:	".lwhZoom-button-upload", 
		btnImgCut:	".lwhZoom-button-cut",
		btnImgDel:	".lwhZoom-button-delete",
		imgEL:		"#member_photo",
		multiple:	true,
		ref_el:		"#member_id",  // important for change ref_id
		start: 		function() {
			//alert($(aj.settings.button).attr("sn") + ":" + aj.settings.ref_id);
			aj.cleanLog();
		},
		uploadDone: function(req) {
			//$("#hello").attr("src", req.data.fileUrl);
			$("#member_photo").attr("src",  "ajax/lwhUpload_image.php?ts=" + req.data.ts + "&size=large&img_id=" + req.data.ref_id);
			$("#diaglog_fileUpload").diagHide();
			//alert("code:" + req.errorCode + " url:"  + req.data.uid + ":" + req.data.fileurl);
		},
		imgCutDone: function(req) {
			$("#member_photo").attr("src",  "ajax/lwhUpload_image.php?ts=" + req.data.ts + "&size=tiny&img_id=" + req.data.ref_id);
			$("#member_photo").attr({"width":110, "height":152}).css({"left":"0px", "top":"0px", "width":"110px", "height":"152px"});
		},
		imgDelDone: function(req) {
			$("#member_photo").attr("src",  "ajax/lwhUpload_image.php?ts=" + req.data.ts + "&size=tiny&img_id=" + req.data.ref_id);
			$("#member_photo").attr({"width":110, "height":152}).css({"left":"0px", "top":"0px", "width":"110px", "height":"152px"});
		}

	});


	detailObj = new LWH.cTABLE({
								  condition: 	{
									  member_id: 	"#member_id"
								  },
								  headers:[
									  {title:	words["sn"], 			col:"rowno",		width:20},
									  {title: 	words["event title"], 	col:"title",		sq:"ASC", 		align:"left"},
									  {title: 	words["start date"], 	col:"start_date", 	sq:"DESC"},
									  {title:	words["web"], 			col:"online", 		sq:"DESC", 		align:"center"},
									  {title: 	words["sign?"], 		col:"signin", 		sq:"DESC", 		align:"center"},
									  {title: 	words["attd."], 		col:"attend", 		sq:"DESC", 		align:"right"},
									  {title:   words["grad.?"], 		col:"graduate", 	sq:"DESC", 		align:"center"},
									  {title: 	words["cert.?"], 		col:"cert", 		sq:"DESC", 		align:"center"},
									  {title: 	words["paid"], 			col:"paid", 		align:"center"},
									  {title: 	words["amount"], 		col:"amt", 			align:"right"},
									  {title: 	words["p.date"], 		col:"paid_date", 	align:"center"},
									  {title: 	words["cert_no"], 		col:"cert_no",		sq:"ASC"},
									  {title: 	words["doc no"], 		col:"doc_no",		sq:"ASC"}
								  ],
								  container: 		"#member_detail_history_records",
								  me:				"detailObj",

								  url:				"ajax/puti_member_detail_history.php",
								  pageSize:			12,
								  orderBY: 			"start_date",
								  orderSQ: 			"DESC",
								  cache:			true,
								  expire:			3600,
								  
								  admin_sess: 		$("input#adminSession").val(),
								  admin_menu:		$("input#adminMenu").val(),
								  admin_oper:		"view",
								  
								  button:			false,
								  view:				true,
								  output:			true,
								  remove:			true

								 // pageRows:			pageHTML
							  });

});

function member_detail_save_ajax() {
		  $("#wait").loadShow();
		  $.ajax({
			  data: {
				  admin_sess: 	$("input#adminSession").val(),
				  admin_menu:	$("input#adminMenu").val(),
				  admin_oper:	"detail",

				  member_id: 	$("input#member_id").val(),
				  first_name: 	$("input#first_name").val(),
				  last_name: 	$("input#last_name").val(),
				  legal_first: 	$("input#legal_first").val(),
				  legal_last: 	$("input#legal_last").val(),
				  dharma_name: 	$("input#dharma_name").val(),
				  dharma_pinyin: $("input#dharma_pinyin").val(),
				  alias: 		$("input#alias").val(),
				  identify_no: 	$("input#identify_no").val(),
				  level: 		$("select#level").val(),
				  gender: 		htmlObj.radio_get("gender"),

				  birth_yy: 	$("input#birth_yy").val(),
				  birth_mm: 	$("select#birth_mm").val(),
				  birth_dd: 	$("select#birth_dd").val(),
				  age: 			$("#age_range").val(),

				  degree: 		    $("#degree").val(),
				  current_position: $("#current_position").val(),
				  past_position:    $("#past_position").val(),
				  religion:         $("#religion").val(),

				  dharma_yy: 	$("#dharma_yy").val(),
				  dharma_mm: 	$("#dharma_mm").val(),
				  dharma_dd: 	$("#dharma_dd").val(),


				  member_yy: 	$("input#member_yy").val(),
				  member_mm: 	$("select#member_mm").val(),
				  member_dd: 	$("select#member_dd").val(),
				  memo: 		$("input#memo").val(),

				  member_lang:	htmlObj.radio_get("member_lang"),
				  languages: 	htmlObj.checkbox_get("languages"),
                  lang_main:    $("#lang_main").val(),
                  lang_able:    $("#lang_able").val(),

				  email: 		$("input#email").val(),
				  email_flag: 	$("#email_flag").val(),
				  phone: 		$("input#phone").val(),
				  cell: 		$("input#cell").val(),
				  contact_method: htmlObj.checkbox_get("contact_method"),
				  status:		$("select#status").val(),
				  idd:			$("input#idd").val(),
				  address: 		$("input#address").val(),
				  city: 		$("input#city").val(),
				  site: 		$("select#site").val(),
				  state: 		$("input#state").val(),
				  country: 		$("input#country").val(),
				  postal: 		$("input#postal").val(),

				  emergency_name: 		$("input#emergency_name").val(),
				  emergency_phone: 		$("input#emergency_phone").val(),
				  emergency_ship: 		$("input#emergency_ship").val(),


				  hear_about: 			htmlObj.checkbox_get("hear_about"),
				  symptom: 				htmlObj.checkbox_get("symptom"),
				  other_symptom:		$("input#other_symptom").val(),
				  therapy: 				htmlObj.radio_get("therapy")?htmlObj.radio_get("therapy"):0,
				  therapy_content: 		$("textarea#therapy_content").val(),

				  transportation: 		htmlObj.radio_get("transportation")?htmlObj.radio_get("transportation"):0,
				  plate_no: 			$("input#plate_no").val(),
				  offer_carpool: 		$("input#offer_carpool").is(":checked")?1:0,

				  medical_concern: 		$("textarea#medical_concern").val()
			  },
			  dataType: "json",  
			  error: function(xhr, tStatus, errorTh ) {
				  $("#wait").loadHide();
				  alert("Error (puti_members_detail_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
			  },
			  success: function(req, tStatus) {
				  $("#wait").loadHide();
				  if( req.errorCode > 0 ) { 
					  errObj.set(req.errorCode, req.errorMessage, req.errorField);
					  return false;
				  } else {
					$("#diaglog_member_detail").diagHide();
					updateRow(req.data);
				  }
			  },
			  type: "post",
			  url: "ajax/puti_members_detail_save.php"
		  });
}

function updateRow( rowObj ) {
	$(".name", 			"tr[rid='" + rowObj.member_id + "']").html(rowObj.name);
	$(".name1", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.name1);
	$(".aname", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.aname);
	$(".flname", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.flname);

	$(".first_name", 	"tr[rid='" + rowObj.member_id + "']").html(rowObj.first_name);
	$(".last_name", 	"tr[rid='" + rowObj.member_id + "']").html(rowObj.last_name);
	$(".legal_first", 	"tr[rid='" + rowObj.member_id + "']").html(rowObj.legal_first);
	$(".legal_last", 	"tr[rid='" + rowObj.member_id + "']").html(rowObj.legal_last);
	$(".legal_name", 	"tr[rid='" + rowObj.member_id + "']").html(rowObj.legal_name);
	
	$(".dharma_name", 	"tr[rid='" + rowObj.member_id + "']").html(rowObj.dharma_name);
	$(".alias", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.alias);
	$(".age", 			"tr[rid='" + rowObj.member_id + "']").html(rowObj.age);
	$(".birth_yy", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.birth_yy);
	//$(".gender", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.gender);
	$(".sex", 			"tr[rid='" + rowObj.member_id + "']").html(rowObj.sex);
	$(".phone", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.phone);
	$(".language", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.language);
	$(".email", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.email);
	$(".city", 			"tr[rid='" + rowObj.member_id + "']").html(rowObj.city);
	$(".site", 			"tr[rid='" + rowObj.member_id + "']").html(rowObj.site);
	$(".photo", 		"tr[rid='" + rowObj.member_id + "']").html(rowObj.photo);
}

function member_detail_clear() {
		  // tabber 1
		  //$("input#member_id").val("");
		  $("input#first_name").val("");
		  $("input#last_name").val("");
		  $("input#legal_first").val("");
		  $("input#legal_last").val("");
		  $("input#dharma_name").val("");
		  $("input#dharma_pinyin").val("");
		  $("#dharma_date").html("");
		  $("input#alias").val("");
		  $("input#identify_no").val("");
		  $("select#level").val("");
		  htmlObj.radio_clear("gender");
		  //$("input:radio[name='gender']").attr("checked",false);
		  $("#age_range").val("");
		  $("#birth_yy").val("");
		  $("#birth_mm").val(0);
		  $("#birth_dd").val(0);

            $("#degree").val(""),
            $("#current_position").val(""),
            $("#past_position").val(""),
            $("#religion").val(""),
            $("#dharma_yy").val(""),
            $("#dharma_mm").val(0),
            $("#dharma_dd").val(0),

		  $("#member_yy").val("");
		  $("#member_mm").val(0);
		  $("#member_dd").val(0);
		  $("#memo").val("");

		  
		  $("#email_flag").val("");
		  $("input#email").val("");
		  $("input#phone").val("");
		  $("input#cell").val("");
		  htmlObj.checkbox_clear("contact_method");

		  htmlObj.radio_clear("member_lang");
		  htmlObj.checkbox_clear("languages");
		  $("#lang_main").val("");
		  $("#lang_able").val("");
		  
		  //$("input:checkbox[name='contact_method']").attr("checked",false);
		  $("select#status").val("");
		  $("input#idd").val("");

		  // tabber 2
		  $("input#address").val("");
		  $("input#city").val("");
		  $("select#site").val("");
		  $("input#state").val("");
		  $("input#country").val("");
		  $("input#postal").val("");

		  $("#member_photo").attr("src","").attr({"width":110,"height":152}).css({"width":"110px","height":"152px", "left":"0px", "top":"0px"});
		  $("#original_url").attr("href","");
		  $("#large_url").attr("href","");
		  $("#medium_url").attr("href","");
		  $("#small_url").attr("href","");
		  
		  // tabber 3
		  $("input#emergency_name").val("");
		  $("input#emergency_phone").val("");
		  $("input#emergency_ship").val("");
		  $("#created_time").html("");
		  $("#last_updated").html("");
		  $("#last_login").html("");
		  $("#hits").html("");
		  $("#online").html("");

		  // tabber 3
		   htmlObj.checkbox_clear("hear_about");
		  //$("input:checkbox[name='hear_about']").attr("checked",false);
		   htmlObj.checkbox_clear("symptom");
		  $("input#other_symptom").val("");
		   htmlObj.radio_clear("therapy");
		   //$("input:radio[name='therapy']").attr("checked",false);
		  $("textarea#therapy_content").val("");

		   htmlObj.radio_clear("transportation");
		   $("input#plate_no").val("");
		   htmlObj.checkbox_clear("offer_carpool");


		  $("textarea#medical_concern").val("");

		  
		  $("#member_detail_history_records", "#diaglog_member_detail").empty();
		  $("#id_card_list").empty();
}

function idcard_record_html(rObj) {
		var html = '<table class="tabQuery-table" border="1" cellpadding="0" cellspacing="0">';
		html += '<tr>';
		html += '<td width="20" class="tabQuery-table-header" 	style="height:12px; font-size:10px;">' +  words["sn"] + '</td>';
		html += '<td class="tabQuery-table-header" width="80"  	style="height:12px; font-size:10px;">' +  words["issue date"] + '</td>';
		html += '<td class="tabQuery-table-header" width="120"  style="height:12px; font-size:10px;">' +  words["id card"] + '</td>';
		html += '</tr>';
		for(var idx in rObj) {
			html += '<tr>';
			html += '<td width="20" align="center">';
			html += parseInt(idx) + 1;
			html += '</td>';

			html += '<td>';
			html +=  rObj[idx].created_time; 
			html += '</td>'

			html += '<td align="right">';
			html +=  rObj[idx].idd;
			html += '</td>'
			html += '</tr>';
		}
		html += '</table>';
		$("#id_card_list").html(html);
}


function Member_detail_history_summary_html(rObj) {
		var html = '<table class="tabQuery-table" border="1" cellpadding="2" cellspacing="0">';
		html += '<tr>';
		html += '<td width="20" class="tabQuery-table-header">' +  words["sn"] + '</td>';
		//html += '<td class="tabQuery-table-header" width="150">' +  words["r.teaching"] + '</td>';
		html += '<td class="tabQuery-table-header" width="250">' +  words["class name"] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["enroll"] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["web"] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["sign"] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["graduate"] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["cert."] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["attd."] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["paid"] + '</td>';
		html += '<td class="tabQuery-table-header">' +  words["amt."] + '</td>';
		html += '</tr>';
		for(var idx in rObj) {
			html += '<tr>';

			html += '<td width="20" align="center">';
			html += parseInt(idx) + 1;
			html += '</td>';
            /*
			html += '<td>';
			html +=  rObj[idx].branch_title; 
			html += '&nbsp;</td>'
            */
			html += '<td>';
			html +=  rObj[idx].class_title;
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].enroll; 
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].online; 
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].signin; 
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].graduate; 
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].cert; 
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].attend; 
			html += '&nbsp;</td>'

			html += '<td align="center">';
			html +=  rObj[idx].paid; 
			html += '&nbsp;</td>'

			html += '<td align="right">';
			html +=  rObj[idx].amt; 
			html += '&nbsp;</td>'
		}
		html += '</table>';
		$("#member_detail_history_summary", "#diaglog_member_detail").html(html);
}


function member_detail_search(member_id) {
	$.ajax({
		data: {
			admin_sess: $("input#adminSession").val(),
			admin_menu:	$("input#adminMenu").val(),
			admin_oper:	"detail",

			member_id: 			member_id
		},
		dataType: "json",  
		error: function(xhr, tStatus, errorTh ) {
			alert("Error (puti_members_detail.php): " + xhr.responseText + "\nStatus: " + tStatus);
		},
		success: function(req, tStatus) {
			if( req.errorCode > 0 ) { 
				errObj.set(req.errorCode, req.errorMessage, req.errorField);
				return false;
			} else {
				$("#diaglog_member_detail").diagShow({
					  diag_open: function() {
							// tabber 1
							$("input#member_id").val(req.data.member_id);
							$("input#first_name").val(req.data.first_name);
							$("input#last_name").val(req.data.last_name);
							$("input#legal_first").val(req.data.legal_first);
							$("input#legal_last").val(req.data.legal_last);
							$("input#dharma_name").val(req.data.dharma_name);
							$("input#dharma_pinyin").val(req.data.dharma_pinyin);
							$("#dharma_date").html(req.data.dharma_date);
							$("input#alias").val(req.data.alias);
							$("input#identify_no").val(req.data.identify_no);
							$("select#level").val(req.data.level);
							
							htmlObj.radio_set("gender", req.data.gender);
							$("#age_range").val(req.data.age);
							$("#birth_yy").val(req.data.birth_yy);
							$("#birth_mm").val(req.data.birth_mm);
							$("#birth_dd").val(req.data.birth_dd);

                            $("#degree").val(req.data.degree),
                            $("#current_position").val(req.data.current_position),
                            $("#past_position").val(req.data.past_position),
                            $("#religion").val(req.data.religion),
                            $("#dharma_yy").val(req.data.dharma_yy),
                            $("#dharma_mm").val(req.data.dharma_mm),
                            $("#dharma_dd").val(req.data.dharma_dd),
						
							$("#member_yy").val(req.data.member_yy);
							$("#member_mm").val(req.data.member_mm);
							$("#member_dd").val(req.data.member_dd);
							$("#memo").val(req.data.memo);

							$("#email_flag").val(req.data.email_flag);
							$("input#email").val(req.data.email);
							$("input#phone").val(req.data.phone);
							$("input#cell").val(req.data.cell);
							
							htmlObj.checkbox_set("contact_method", req.data.contact_method);

							htmlObj.radio_set("member_lang", req.data.member_lang);
							htmlObj.checkbox_set("languages", req.data.languages);
							$("#lang_main").val(req.data.lang_main);
							$("#lang_able").val(req.data.lang_able);


							$("select#status").val(req.data.status);
							$("input#idd").val(req.data.idd);
							
							// tabber 2
							$("input#address").val(req.data.address);
							$("input#city").val(req.data.city);
							$("select#site").val(req.data.site);
							$("input#state").val(req.data.state);
							$("input#country").val(req.data.country);
							$("input#postal").val(req.data.postal);

							$("#member_photo").attr("src",req.data.photo_url).attr({"width":110,"height":152}).css({"width":"110px","height":"152px", "left":"0px", "top":"0px"});							
							$("#original_url").attr("href",req.data.original_url);
							$("#large_url").attr("href",req.data.large_url);
							$("#medium_url").attr("href",req.data.medium_url);
							$("#small_url").attr("href",req.data.small_url);

							aj.btnReset();
							// tabber 3
							$("input#emergency_name").val(req.data.emergency_name);
							$("input#emergency_phone").val(req.data.emergency_phone);
							$("input#emergency_ship").val(req.data.emergency_ship);

							$("#created_time").html(req.data.created_time);
							$("#last_updated").html(req.data.last_updated);
							$("#last_login").html(req.data.last_login);
							$("#hits").html(req.data.hits);
							$("#online").html(req.data.online);
							
							// tabber 3
                            Member_detail_history_summary_html(req.data.records)

                            // tabber 4							
							idcard_record_html(req.data.cards);	
							htmlObj.checkbox_set("hear_about", req.data.hear_about);
							htmlObj.checkbox_set("symptom", req.data.symptom);
							$("input#other_symptom").val(req.data.other_symptom);
							
							htmlObj.radio_set("therapy", req.data.therapy);
							//$("input:radio[name='therapy'][value='" + req.data.therapy + "']").attr("checked",true);
							$("textarea#therapy_content").val(req.data.therapy_content);
							
							htmlObj.radio_set("transportation", req.data.transportation);
							$("input#plate_no").val(req.data.plate_no);
							htmlObj.checkbox_set("offer_carpool", req.data.offer_carpool);
							
							$("textarea#medical_concern").val(req.data.medical_concern);
							
							detailObj.start();
					  },
					  diag_close: function() {
						  member_detail_clear();
					  }
				 });
			}
		},
		type: "post",
		url: "ajax/puti_members_detail.php"
	});
}
</script>
<?php 
include_once($CFG["include_path"] . "/lib/html/html.php");
?>
<div id="diaglog_member_detail" class="lwhDiag" style="z-index:888;">
	<div class="lwhDiag-content lwhDiag-no-border">
          <div id="member_detail_tabber" class="lwhTabber lwhTabber-mint" style="width:680px;">
              <div class="lwhTabber-header">
                  <a><?php echo $words["p.information"]?><s></s></a>
                  <a><?php echo $words["emergency"]?><s></s></a>
                  <a><?php echo $words["p.other_info"]?><s></s></a>
                  <a><?php echo $words["q & a"]?><s></s></a>
                  <a><?php echo $words["history summary"]?><s></s></a>
                  <a><?php echo $words["records"]?><s></s></a>
                  <div class="line"></div>    
              </div>
              <div class="lwhTabber-content" style="height:410px; border-width:2px;">
                  <div>
					<!------------------------------------------------------------------>
				    <input type="hidden" id="member_id" name="member_id" value="" />
            	    <table border="0" cellpadding="1" cellspacing="0">
                	    <!-- first part -->
                        <tr>
                    	    <td valign="top">
			            	    <table border="0" cellpadding="1" cellspacing="0">
                                 <?php
							     if( $admin_user["lang"] != "en" ) {
							     ?>   
                                        <tr>
                                             <td class="title"><?php echo $words["last name"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:60px;" id="last_name" name="last_name" value="" />
                                                <span class="required">*</span>	
                                             </td>
                                             <td class="title"><?php echo $words["first name"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:120px;" id="first_name" name="first_name" value="" />
                                                <span class="required">*</span>	
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal last"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:60px;" id="legal_last" name="legal_last" value="" />
                                             </td>
                                             <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal first"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:120px;" id="legal_first" name="legal_first" value="" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="title"><?php echo $words["dharma name"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                    <input class="form-input" style="width:50px;" id="dharma_name" name="dharma_name" value="" />
                                                    <input class="form-input" style="width:85px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                                             </td>
                                             <td class="title"><?php echo $words["alias"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                    <input class="form-input" style="width:120px;" id="alias" name="alias" value="" />
                                             </td>
                                        </tr>
							      <?php
							      } else {
                                  ?>
                                        <tr>
                                             <td class="title"><?php echo $words["first name"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:120px;" id="first_name" name="first_name" value="" />
                                                <span class="required">*</span>	
                                             </td>
                                             <td class="title"><?php echo $words["last name"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:120px;" id="last_name" name="last_name" value="" />
                                                <span class="required">*</span>	
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal first"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:120px;" id="legal_first" name="legal_first" value="" />
                                             </td>
                                             <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal last"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <input class="form-input" style="width:120px;" id="legal_last" name="legal_last" value="" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="title"><?php echo $words["dharma name"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                    <input class="form-input" style="width:50px;" id="dharma_name" name="dharma_name" value="" />
                                                    <input class="form-input" style="width:85px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                                             </td>
                                             <td class="title"><?php echo $words["alias"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                    <input class="form-input" style="width:120px;" id="alias" name="alias" value="" />
                                             </td>
                                        </tr>
							      <?php
							      }
                                  ?>
                                        <tr>
                                             <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["birth date"]?>: </td>
                                             <td style="white-space:nowrap;">
                                         		    <input class="form-input" style="width:40px; text-align:center;" id="birth_yy" name="birth_yy" maxlength="4" value="" />
                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                    <select style="text-align:center;" id="birth_mm" name="birth_mm">
                                                	    <option value="0"><?php echo $words["month"]?></option>
                                                	    <?php
														    for($i=1;$i<=12;$i++) {
															    echo '<option value="' . $i . '">' . $i . '</option>';
														    }
													    ?>    
                                                    </select>
                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                    <select style="text-align:center;" id="birth_dd" name="birth_dd">
                                                	    <option value="0"><?php echo $words["bday"]?></option>
                                                	    <?php
														    for($i=1;$i<=31;$i++) {
															    echo '<option value="' . $i . '">' . $i . '</option>';
														    }
													    ?>    
                                                    </select>
                                                
                                              </td>
                                             <td class="title"><?php echo $words["identify number"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                    <input class="form-input" style="width:120px;" id="identify_no" name="identify_no" value="" />
                                             </td>
                                        </tr>

                                        <tr>
                                             <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["age range"]?>: </td>
                                             <td style="white-space:nowrap;">
                                                <select id="age_range" style="text-align:center;" name="age_range">
                                                    <option value="0"></option>
                                                    <?php
                                                        $result_age = $db->query("SELECT * FROM puti_members_age order by id");
                                                        while( $row_age = $db->fetch($result_age) ) {
                                                            echo '<option value="' . $row_age["id"] . '">' . $row_age["title"] . '</option>';
                                                        }
                                                    ?>
                                                </select> <?php echo $words["years old"]?>
                                              </td>
                                             <td class="title"><?php echo $words["member.title"]?>: </td>
                                             <td>
                                                <select id="level" style="text-align:center;" name="level">
                                                    <option value="0"></option>
                                                    <?php
                                                        $result_lvl = $db->query("SELECT * FROM puti_info_title order by id");
                                                        while( $row_lvl = $db->fetch($result_lvl) ) {
                                                            echo '<option value="' . $row_lvl["id"] . '">' . $row_lvl["title"] . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                             </td>
                                        </tr>
                
                                        <tr>
                                              <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["gender"]?>: </td>
                                              <td  style="white-space:nowrap;">
                                                <?php
                                                    $gender_array = array();
                                                    $gender_array[0]["id"] 		= "Male";
                                                    $gender_array[0]["title"] 	= "Male";
                                                    $gender_array[1]["id"] 		= "Female";
                                                    $gender_array[1]["title"] 	= "Female";
                                                    echo cHTML::radio("gender", $gender_array);
                                                ?>
                                                <span class="required">*</span>
                                              </td>
                                             <td class="title"><?php echo $words["religion"]?>: </td>
                                             <td>
                                                <?php
                                                    echo iHTML::select($admin_user["lang"], $db, "vw_vol_religion","religion","",0);
                                                ?>
                                             </td>
                                        </tr>
                                        <tr>                        	
                                             <td class="title"><?php echo $words["member.degree"]?>: </td>
                                             <td>
                                                <?php
                                                    echo iHTML::select($admin_user["lang"], $db, "vw_vol_degree","degree","",0);
                                                ?>
                                             </td>
                                            <td class="title"><?php echo $words["status"]?>: </td>
                                            <td>
                                                <select id="status" name="status">
                                                    <option value=""></option>
                                                    <option value="0"><?php echo $words["inactive"]?></option>
                                                    <option value="1"><?php echo $words["active"]?></option>
                                                </select>
                                                <span class="required">*</span>
                                            </td>
                                        </tr>
                                        <tr>                        	
                                            <td class="title"><?php echo $words["past_position"]?>: </td>
                                            <td>
                                                <input class="form-input" style="width:160px;" id="past_position" name="past_position" value="" />                                        
                                            </td>
                                            <td class="title"><?php echo $words["g.site"]?>: </td>
                                            <td>
                                                <select id="site" name="site">
                                                    <option value=""></option>
                                                    <?php
                                                        $result_site = $db->query("SELECT id, title FROM puti_sites WHERE id > 0 AND status = 1 AND id in ". $admin_user["sites"] ."  ORDER BY id"); 
                                                        while( $row_site = $db->fetch($result_site) ) {
                                                            echo '<option value="' . $row_site["id"] . '">' . $words[strtolower($row_site["title"])] . '</option>';		
                                                        }
                                                    ?>
                                                </select> <span class="required">*</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="title"><?php echo $words["current_position"]?>: </td>
                                            <td>
                                                <input class="form-input" style="width:160px;" id="current_position" name="current_position" value="" />                                        
                                            </td>
                                        </tr>
                                </table>            
                            </td>
                            <td valign="top" style="padding-left:10px;">
                                <div class="lwhZoom">
                                    <img id="member_photo" src="" width="110" height="152" maxwidth="2048" />
                                </div>
                            </td>
					    </tr>
                  	    <!-- End of first part -->


                        <!-- language part -->
                        <tr><td colspan="2" style="border-top:1px solid #aaaaaa"></td></tr>
                        <tr>
                    	    <td colspan="2">
                        	    <table cellpadding="1" cellspacing="0" width="100%">
                                        <tr>
                                            <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["preferred language"]?>: </td>
                                            <td align="left">
                                                <?php 
                                                    echo iHTML::radio($admin_user["lang"], $db, "vw_vol_language", "member_lang", "", 99, 0, 0);
                                                ?>
                                                <input class="form-input" style="width:80px;" id="lang_main" name="lang_main" value="" /> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["language ability"]?>: </td>
                                            <td align="left">
                                                <?php 
                                                    echo iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "languages", "", 99, 0, 0);
                                                ?>
                                                <input class="form-input" style="width:80px;" id="lang_able" name="lang_able" value="" /> 
                                            </td>
                                        </tr>
                                </table>
                        </tr>
                        <!-- End of language part -->			


                        <!-- Second part -->
                        <tr><td colspan="2" style="border-top:1px solid #aaaaaa"></td></tr>
                        <tr>
                    	    <td colspan="2">
                        	    <table cellpadding="1" cellspacing="0">
                                        <tr>
                                             <td class="title"><?php echo $words["preferred method of contact"]?>: </td>
                                             <td>
                                                            <?php
                                                                $contact_array = array();
                                                                $contact_array[0]["id"] 	= "Phone";
                                                                $contact_array[0]["title"] 	= "Phone";
                                                                $contact_array[1]["id"] 	= "Email";
                                                                $contact_array[1]["title"] 	= "Email";
                                                                echo cHTML::checkbox("contact_method", $contact_array);
                                                            ?>

                                             </td>
                                             <td class="title"><?php echo $words["email subscription"]?>: </td>
                                             <td>
                                                 <select id="email_flag" name="email_flag">
                                                    <option value=""></option>
                                                    <option value="0"><?php echo $words["email.unsubscribe"]?></option>
                                                    <option value="1"><?php echo $words["email.subscribe"]?></option>
                                                </select>
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="title"><?php echo $words["phone"]?>: </td>
                                             <td>
                                                <input class="form-input" style="width:120px;" id="phone" name="phone" value="" />
                                             </td>
                                             <td class="title"><?php echo $words["email"]?>: </td>
                                             <td>
                                                <input class="form-input" id="email" style="width:250px;" name="email" value="" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="title"><?php echo $words["cell"]?>: </td>
                                             <td>
                                                <input class="form-input" style="width:120px;" id="cell" name="cell" value="" />
                                             </td>
                                             <td class="title"><?php echo $words["memo notes"]?>: </td>
                                             <td>
                                                <input class="form-input" style="width:250px;" id="memo" name="memo" value="" />
                                             </td>
                                        </tr>

                                </table>
                        </tr>
                        <!-- End of second part -->			

                    
                        <!-- third part -->			
                        <tr><td colspan="2" style="border-top:1px solid #aaaaaa"></td></tr>
                        <tr>
                    	    <td colspan="2">
                        	    <table cellpadding="1" cellspacing="0">
                            	    <tr>
                                              <td valign="top">
                                                      <table border="0" cellpadding="1" cellspacing="0">
                                                  	      <tr>
                                                              <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["member enter date"]?>: </td>
                                                              <td style="white-space:nowrap;">
                                                                    <input class="form-input" style="width:40px; text-align:center;" id="member_yy" name="member_yy" maxlength="4" value="" />
                                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                                    <select style="text-align:center;" id="member_mm" name="member_mm">
                                                                        <option value="0"><?php echo $words["month"]?></option>
                                                                        <?php
                                                                            for($i=1;$i<=12;$i++) {
                                                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                                                            }
                                                                        ?>    
                                                                    </select>
                                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                                    <select style="text-align:center;" id="member_dd" name="member_dd">
                                                                        <option value="0"><?php echo $words["bday"]?></option>
                                                                        <?php
                                                                            for($i=1;$i<=31;$i++) {
                                                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                                                            }
                                                                        ?>    
                                                                    </select>
                                                                
                                                              </td>
                                                               <td class="title"><?php echo $words["dharma date"]?>: </td>
                                                               <td>
                                                                    <input class="form-input" style="width:40px; text-align:center;" id="dharma_yy" name="dharma_yy" maxlength="4" value="" />
                                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                                    <select style="text-align:center;" id="dharma_mm" name="dharma_mm">
                                                                        <option value="0"><?php echo $words["month"]?></option>
                                                                        <?php
                                                                            for($i=1;$i<=12;$i++) {
                                                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                                                            }
                                                                        ?>    
                                                                    </select>
                                                                    <span style="font-size:16px;font-weight:bold;">-</span>
                                                                    <select style="text-align:center;" id="dharma_dd" name="dharma_dd">
                                                                        <option value="0"><?php echo $words["bday"]?></option>
                                                                        <?php
                                                                            for($i=1;$i<=31;$i++) {
                                                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                                                            }
                                                                        ?>    
                                                                    </select>
                                                                    <!-- <span id="dharma_date"></span> -->
                                                               </td>
                                                          </tr>
                                                      </table>
                                              </td>
                                              <td valign="top" align="left" style="padding-left:10px;">
                                              </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- end of third part -->
                     </table>                    
					<!------------------------------------------------------------------>
                  </div>


                  <div>
					<!--- Emergency ---------------------------------------------------->
                    <table border="0" cellpadding="2" cellspacing="0" width="100%">
                        <tr>
                            <td valign="top">
                                <!-- 11111111111 -->
                                <table cellpadding="2" cellspacing="0">
                                <tr>
                                    <td colspan="2"><b><?php echo $words["emergency contact name and relationship"]?>:</b></td>
                                </tr>
                                <tr>
                                    <td class="title"><?php echo $words["contact name"]?>: </td>
                                    <td>
                                        <input class="form-input" id="emergency_name" name="emergency_name" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title"><?php echo $words["contact phone"]?>: </td>
                                    <td>
                                        <input class="form-input" id="emergency_phone" name="emergency_phone" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="title"><?php echo $words["relationship"]?>: </td>
                                    <td>
                                        <input class="form-input" id="emergency_ship" name="emergency_ship" value="" />
                                    </td>
                                </tr>
                           </table>
                                <!-- 11111111111 -->
                            </td>
                            <td valign="top">
                                <!-- 22222222222 -->
                                <table cellpadding="2" cellspacing="0">
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo $words["created time"]?>: </td>
                                    <td width="auto" align="left">
                                        <span id="created_time"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo $words["last updated"]?>: </td>
                                    <td width="auto" align="left">
                                        <span id="last_updated"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo $words["last login"]?>: </td>
                                    <td width="auto" align="left">
                                        <span id="last_login"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo $words["login count"]?>: </td>
                                    <td width="auto" align="left">
                                        <span id="hits"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo $words["web"]?>: </td>
                                    <td width="auto" align="left">
                                        <span id="online"></span>
                                    </td>
                                </tr>
                           </table>
                                <!-- 22222222222 -->
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="1" cellspacing="0" width="100%">
								<tr>
                                	<td valign="top" class="line">
                                        <table cellpadding="1" cellspacing="0">
                                                <tr>
                                                    <td colspan="2"><b><?php echo $words["address information"]?>:</b></td>
                                                </tr>
                                                <tr>
                                                     <td class="title"><?php echo $words["address"]?>: </td>
                                                     <td>
                                                        <input class="form-input" id="address" name="address" value="" />
                                                     </td>
                                                </tr>
                                                <tr>
                                                     <td class="title"><?php echo $words["city"]?>: </td>
                                                     <td>
                                                        <input class="form-input" id="city" name="city" value="" />
                                                     </td>
                                                </tr>
                                                <tr>
                                                     <td class="title"><?php echo $words["state"]?>: </td>
                                                     <td>
                                                        <input class="form-input" id="state" name="state" value="" />
                                                     </td>
                                                </tr>
                                               	<!--
                                                <tr>
                                                     <td class="title"><?php echo $words["country"]?>: </td>
                                                     <td>
                                                        <input class="form-input" id="country" name="country" value="" />
                                                     </td>
                                                </tr>
                                                -->
                                                <tr>
                                                     <td class="title"><?php echo $words["postal code"]?>: </td>
                                                     <td>
                                                        <input class="form-input" id="postal" name="postal" value="" />
                                                     </td>
                                                </tr>
                                        </table>
									</td>
                                   	<td valign="top"  class="line">
                                    	<span style="font-size:18x; font-weight:bold;"><?php echo $words["photo download"]?>:</span>
                                        <br />
                                        <a id="original_url" href="" style="display:block; font-size:16px; color:blue; text-decoration:underline;">
                                    	<?php echo $words["photo original download"]?>
                                        </a>
                                        <a id="large_url" href="" style="display:block; font-size:16px; color:blue; text-decoration:underline;">
                                    	<?php echo $words["photo large download"]?>
                                        </a>
                                        <a id="medium_url" href="" style="display:block; font-size:16px; color:blue; text-decoration:underline;">
                                    	<?php echo $words["photo medium download"]?>
                                        </a>
                                        <a id="small_url" href="" style="display:block; font-size:16px; color:blue; text-decoration:underline;">
                                    	<?php echo $words["photo small  download"]?>
                                        </a>
                                    </td>
                                 </tr>
                            </table>
                    <table cellpadding="2" cellspacing="0" width="100%">
                                    <tr>
                                        <td colspan="2" class="line"><b><?php echo $words["transportation"]?> : </b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left">
                                            <?php 
                                                $result_carpool = $db->query("SELECT * FROM puti_info_carpool Order BY id");
                                                $rows_carpool = $db->rows($result_carpool);
                                                echo cHTML::radio('transportation',$rows_carpool,0,10);
                                            ?><br />
                                            <?php echo $words["if driving, please help"]?> : 
                                            <span id="span_carpool">{
                                            <span>
                                                <?php echo $words["plate no"]?>: <input type="text" id="plate_no" name="plate_no" style="width:80px;" value="" />
                                            </span>
                                            <span style="margin-left:5px;">
                                                <input type="checkbox" id="offer_carpool" name="offer_carpool" value="1" /><label for="offer_carpool"><?php echo $words["offer carpool"]?></label>
                                            </span>}
                                            </span>
                                        </td>
                                    </tr>

                            </table>                    
					<!--- Emergency ---------------------------------------------------->
                  </div>


                  <div>
					<!----Other Information--------------------------------------------->
                    <table cellpadding="2" cellspacing="0">
						<tr>
                            <td valign="top">
                                <table cellpadding="1" cellspacing="0">
                                    <tr>
                                        <td class="title" valign="top"><?php echo $words["id card"]?>: </td>
                                        <td valign="top">
                                            <input class="form-input" style="width:100px; text-align:left;" id="idd" name="idd" value="" />
                                        </td>
                                        <td class="title" valign="top"><?php echo $words["id card list"]?>: </td>
                                        <td valign="top">
                                            <div id="id_card_list" style="height:100px; width:280px; overflow:auto; border:1px dotted #cccccc;"></div>
                                        </td>
                                    </tr>
                                </table>
							</td>
                            <td valign="top">
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="1" cellspacing="0" width="100%">
						<tr>
                            <td valign="top" class="line">
							</td>
                            <td valign="top"  class="line">
                            </td>
                        </tr>
                    </table>
                    <!------------------------------------------------------------------>
                  </div>


                  <div>
					<!------------------------------------------------------------------>
                            <table cellpadding="2" cellspacing="0" width="100%">
                                <tr>
                                    <td colspan="2" class="line"><b><?php echo $words["how did you hear about us?"]?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left">
										<?php 
                                            $result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
                                            $rows_hearfrom = $db->rows($result_hearfrom);
                                            echo cHTML::checkbox('hear_about',$rows_hearfrom,10);
                                        ?>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td colspan="2" class="line"><br><b><?php echo $words["ailment & symptom"]?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left">
                                        <?php 
                                            $result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
                                            $rows_symptom = $db->rows($result_symptom);
                                            echo ($admin_user["lang"]=="en"?cHTML::checkbox('symptom',$rows_symptom,6):cHTML::checkbox('symptom',$rows_symptom,20));
                                        ?>
                                        <span><?php echo $words["specify"]?>: <input type="text" id="other_symptom" name="other_sympton" style="width:200px;" value="" /></span>
                                    </td>
                                </tr>
                             
                                <tr>
                                    <td colspan="2" class="line">
                                        <br>
                                        <b><?php echo $words["are you currently receiving therapy of some kind?"]?></b>
										<?php
                                            $therapy_array = array();
                                            $therapy_array[0]["id"] 	= "0";
                                            $therapy_array[0]["title"] 	= "hasnt";
                                            $therapy_array[1]["id"] 	= "1";
                                            $therapy_array[1]["title"] 	= "has";
                                            echo cHTML::radio("therapy", $therapy_array);
                                        ?>
										<br />                                       
                                        <?php echo $words["if yes, please provide details regarding the nature of the therapy/treatment"]?> 
                                        <textarea id="therapy_content" name="therapy_content" style="width:98%; height:80px; resize:none;"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="line">
                                        <br>
                                        <b><?php echo $words["please write down any other medical concerns or history"]?>: </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left">
                                        <textarea id="medical_concern" name="medical_concern" style="width:98%; height:80px; resize:none;"></textarea>
                                    </td>
                                </tr>
                            </table>
					<!------------------------------------------------------------------>
                  </div>


                  <div style="width:650px; height:390px;" align="center">
					<!-------- History Summary ------------------------------------------>
						
                        	<div id="member_detail_history_summary">
                            </div>
                      
					<!------------------------------------------------------------------>
                  </div>


                  <div style="width:670px; height:390px;" align="center">
					<!------- History Detail ------------------------------------------->
                        	<div id="member_detail_history_records">
                            </div>
					<!------------------------------------------------------------------>
                  </div>
              </div>
              <center><input type="button" right="save" id="btn_member_detail_save" value="<?php echo $words["button save"]?>" /></center>
          </div> <!-- end of "lwhTabber" -->
	</div>
</div>