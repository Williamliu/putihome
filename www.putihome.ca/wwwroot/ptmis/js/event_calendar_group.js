$(function() {
		// group item  label print			
		$("a[oper='group_item_pdf']").live("click", function(ev) {
			  $("#nametag_group").val("");
			  $("#nametag_enroll").val( $(this).attr("enroll_id") );
			  $("#nametag_aflag").val("");
			  $("#diaglog_nametag").diagShow();
		});
		
		$("#btn_groups_matrix").live("click", function(ev) {
			  if( $("iframe[name='frm_group_excel']").length > 0 ) {
					$("input[name='admin_sess']", "form[name='frm_group_excel']").val($("input#adminSession").val());	
					$("input[name='admin_menu']", "form[name='frm_group_excel']").val($("input#adminMenu").val());	
					$("input[name='admin_oper']", "form[name='frm_group_excel']").val("print");	
		
					$("input[name='event_id']", "form[name='frm_group_excel']").val($("select#event_id").val());	
			  } else {
					var ifm = $("body").append('<iframe name="ifm_group_excel" style="display:none;"></iframe>')[0].lastChild;;
					var frm = $("body").append('<form name="frm_group_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
					$("form[name='frm_group_excel']").attr({"action":"ajax/event_calendar_group_output.php", "target": "ifm_group_excel" }); 
					$("form[name='frm_group_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
					$("form[name='frm_group_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
					$("form[name='frm_group_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  
		
					$("form[name='frm_group_excel']").append('<input type="hidden" name="event_id" value="' + $("select#event_id").val() + '" />');				  
			  }
			  $("form[name='frm_group_excel']").submit();			  
		});
		
		$("#btn_groups_list").live("click", function(ev) {
			  if( $("iframe[name='ifm_list_excel']").length > 0 ) {
					$("input[name='admin_sess']", "form[name='frm_list_excel']").val($("input#adminSession").val());	
					$("input[name='admin_menu']", "form[name='frm_list_excel']").val($("input#adminMenu").val());	
					$("input[name='admin_oper']", "form[name='frm_list_excel']").val("print");	
		
					$("input[name='event_id']", "form[name='frm_list_excel']").val($("select#event_id").val());	
			  } else {
					var ifm = $("body").append('<iframe name="ifm_list_excel" style="display:none;"></iframe>')[0].lastChild;;
					var frm = $("body").append('<form name="frm_list_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
					$("form[name='frm_list_excel']").attr({"action":"ajax/event_calendar_group_member_output.php", "target": "ifm_list_excel" }); 
					$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
					$("form[name='frm_list_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  
		
					$("form[name='frm_list_excel']").append('<input type="hidden" name="event_id" value="' + $("select#event_id").val() + '" />');				  
			  }
			  $("form[name='frm_list_excel']").submit();			  
		});


		// groups  labels print 
		$(".group-labels").live("click", function(ev) {
			  $("#nametag_group").val($(this).attr("gid"));
			  $("#nametag_enroll").val("");
			  $("#nametag_aflag").val("");
			  $("#diaglog_nametag").diagShow();
		});
		
		// groups member information confirmation
		$("a.group-check").live("click", function(ev) {
			  var eid = $("select#event_id").val();
			  var gid = $(this).attr("gid");
			  if( $("iframe[name='ifm_check_excel']").length > 0 ) {
					$("input[name='admin_sess']", "form[name='frm_check_excel']").val($("input#adminSession").val());	
					$("input[name='admin_menu']", "form[name='frm_check_excel']").val($("input#adminMenu").val());	
					$("input[name='admin_oper']", "form[name='frm_check_excel']").val("print");	
		
					$("input[name='event_id']", "form[name='frm_check_excel']").val(eid);	
					$("input[name='group_id']", "form[name='frm_check_excel']").val(gid);	
			  } else {
					var ifm = $("body").append('<iframe name="ifm_check_excel" style="display:none;"></iframe>')[0].lastChild;;
					var frm = $("body").append('<form name="frm_check_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
					$("form[name='frm_check_excel']").attr({"action":"ajax/event_calendar_group_check_output.php", "target": "ifm_check_excel" }); 
					$("form[name='frm_check_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
					$("form[name='frm_check_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
					$("form[name='frm_check_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  
		
					$("form[name='frm_check_excel']").append('<input type="hidden" name="event_id" value="' + eid + '" />');				  
					$("form[name='frm_check_excel']").append('<input type="hidden" name="group_id" value="' + gid + '" />');				  
			  }
			  $("form[name='frm_check_excel']").submit();			  
		});
		
		// groups  attendance  excel
		$("a.group-attend").live("click", function(ev) {
			  var eid = $("select#event_id").val();
			  var gid = $(this).attr("gid");
			  if( $("iframe[name='frm_attend_excel']").length > 0 ) {
					$("input[name='admin_sess']", "form[name='frm_attend_excel']").val($("input#adminSession").val());	
					$("input[name='admin_menu']", "form[name='frm_attend_excel']").val($("input#adminMenu").val());	
					$("input[name='admin_oper']", "form[name='frm_attend_excel']").val("print");	
		
					$("input[name='event_id']", "form[name='frm_attend_excel']").val(eid);	
					$("input[name='group_id']", "form[name='frm_attend_excel']").val(gid);	
			  } else {
					var ifm = $("body").append('<iframe name="ifm_attend_excel" style="display:none;"></iframe>')[0].lastChild;;
					var frm = $("body").append('<form name="frm_attend_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
					$("form[name='frm_attend_excel']").attr({"action":"ajax/event_calendar_group_attend_output.php", "target": "ifm_attend_excel" }); 
					$("form[name='frm_attend_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
					$("form[name='frm_attend_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
					$("form[name='frm_attend_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  
		
					$("form[name='frm_attend_excel']").append('<input type="hidden" name="event_id" value="' + eid + '" />');				  
					$("form[name='frm_attend_excel']").append('<input type="hidden" name="group_id" value="' + gid + '" />');				  
			  }
			  $("form[name='frm_attend_excel']").submit();			  
		});
		
		// print all student label 		
		$("#btn_all_label").live("click", function(ev) {
			  $("#nametag_group").val("");
			  $("#nametag_enroll").val("");
			  $("#nametag_aflag").val("1");
			  $("#diaglog_nametag").diagShow();
		});

		// print one page blank label
		$("#btn_blank_label").live("click", function(ev) {
			  $("#nametag_group").val("");
			  $("#nametag_enroll").val("");
			  $("#nametag_aflag").val("2");
			  $("#diaglog_nametag").diagShow();
		});
		
		$("#btn_student_sign").live("click", function(ev) {
			  var eid = $("select#event_id").val();
			  group_signature(eid);
		});

}); // end of $(function())



function label_print1(eid, gid, rid, aflag) {
	  if( $("iframe[name='ifm_pdf_excel']").length > 0 ) {
			$("input[name='admin_sess']", "form[name='frm_pdf_excel']").val($("input#adminSession").val());	
			$("input[name='admin_menu']", "form[name='frm_pdf_excel']").val($("input#adminMenu").val());	
			$("input[name='admin_oper']", "form[name='frm_pdf_excel']").val("print");	

			$("input[name='event_id']", "form[name='frm_pdf_excel']").val(eid);	
			$("input[name='group_id']", "form[name='frm_pdf_excel']").val(gid);	
			$("input[name='enroll_id']", "form[name='frm_pdf_excel']").val(rid);	
			$("input[name='aflag']", "form[name='frm_pdf_excel']").val(aflag);	
			$("input[name='sch_name']", "form[name='frm_pdf_excel']").val($("#sch_name").val());	
			$("input[name='sch_phone']", "form[name='frm_pdf_excel']").val($("#sch_phone").val());	
			$("input[name='sch_email']", "form[name='frm_pdf_excel']").val($("#sch_email").val());	
			$("input[name='sch_city']", "form[name='frm_pdf_excel']").val($("#sch_city").val());	
			$("input[name='sch_gender']", "form[name='frm_pdf_excel']").val($("#sch_gender").val());	
			$("input[name='sch_group']", "form[name='frm_pdf_excel']").val($("#sch_group").val());	
			$("input[name='sch_online']", "form[name='frm_pdf_excel']").val($("#sch_online").val());	
			$("input[name='sch_attend']", "form[name='frm_pdf_excel']").val($("#sch_attend").val());	
			$("input[name='sch_level']", "form[name='frm_pdf_excel']").val($("#sch_level").val());	
			$("input[name='sch_lang']", "form[name='frm_pdf_excel']").val($("#sch_lang").val());	
			$("input[name='sch_idd']", "form[name='frm_pdf_excel']").val($("#sch_idd").val());	
	  } else {
			var ifm = $("body").append('<iframe name="ifm_pdf_excel" style="display:none;"></iframe>')[0].lastChild;;
			var frm = $("body").append('<form name="frm_pdf_excel"  style="display:none;"  enctype="multipart/form-data"></form>')[0].lastChild;;
			$("form[name='frm_pdf_excel']").attr({"action":"ajax/event_calendar_group_label_print.php", "target": "ifm_pdf_excel" }); 
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="admin_sess" value="' + $("input#adminSession").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="admin_menu" value="' + $("input#adminMenu").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="admin_oper" value="print" />');				  

			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="event_id" value="' + eid + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="group_id" value="' + gid + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="enroll_id" value="' + rid + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="aflag" value="' + aflag + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_name" value="' + $("#sch_name").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_phone" value="' + $("#sch_phone").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_email" value="' + $("#sch_email").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_city" value="' + $("#sch_city").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_gender" value="' + $("#sch_gender").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_group" value="' + $("#sch_group").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_online" value="' + $("#sch_online").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_attend" value="' + $("#sch_attend").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_level" value="' + $("#sch_level").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_lang" value="' + $("#sch_lang").val() + '" />');				  
			$("form[name='frm_pdf_excel']").append('<input type="hidden" name="sch_idd" value="' + $("#sch_idd").val() + '" />');				  
	  }
	  $("form[name='frm_pdf_excel']").submit();			  
}

function label_print() {
	  $.ajax({
		  data: {
			  admin_sess: $("input#adminSession").val(),
			  admin_menu:	$("input#adminMenu").val(),
			  admin_oper:	"print",
			  
			  event_id: 	$("select#event_id").val(),						
			  group_id:		$("#nametag_group").val(),
			  enroll_id:	$("#nametag_enroll").val(),
			  aflag:		$("#nametag_aflag").val(),
			  shoes:		$("#nametag_shoes").is(":checked")?1:0,
			  lfname:		$("#nametag_last").is(":checked")?1:0,
			  title:		$("#nametag_title").val(),
			  sch_name:		$("#sch_name").val(),
			  sch_phone:	$("#sch_phone").val(),
			  sch_email:	$("#sch_email").val(),
			  sch_city:		$("#sch_city").val(),
			  sch_gender:	$("#sch_gender").val(),
			  sch_online:	$("#sch_online").val(),
			  sch_attend:	$("#sch_attend").val(),
			  sch_level:	$("#sch_level").val(),
			  sch_onsite:	$("#sch_onsite").val(),
			  sch_trial:	$("#sch_trial").val(),
			  sch_lang:		$("#sch_lang").val(),
			  sch_group:	$("#sch_group").val(),
			  sch_idd:		$("#sch_idd").val(),
			  sch_date:		$("#sch_date").val(),
			  orderBY:		allObj.tabData.condition.orderBY,
			  orderSQ:		allObj.tabData.condition.orderSQ
		  },
		  dataType: "json",  
		  //contentType: "text/html; charset=utf-8",
		  error: function(xhr, tStatus, errorTh ) {
			  alert("Error (" +　$("#nametag_temp").val() +　"): " + xhr.responseText + "\nStatus: " + tStatus);
		  },
		  success: function(req, tStatus) {
			  var w1 = window.open("output.html");
			  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Student NameTag</" + "title" + "></" + "head" + "><" + "body>";
			  w1.document.open();
			  w1.document.write(html_str);
			  w1.document.write(req.data);
			  w1.document.write('</html>');
			  w1.document.close();
			  w1.print();
		  },
		  type: "post",
		  url: "ajax/" + $("#nametag_temp").val()
	  });
}

function group_signature(eid) {
	  $.ajax({
		  data: {
			  admin_sess: $("input#adminSession").val(),
			  admin_menu:	$("input#adminMenu").val(),
			  admin_oper:	"print",
			  
			  event_id: 	eid,						
			  sch_name:		$("#sch_name").val(),
			  sch_phone:	$("#sch_phone").val(),
			  sch_email:	$("#sch_email").val(),
			  sch_city:		$("#sch_city").val(),
			  sch_gender:	$("#sch_gender").val(),
			  sch_new_flag:	$("#sch_new_flag").val(),
			  sch_online:	$("#sch_online").val(),
			  sch_attend:	$("#sch_attend").val(),
			  sch_level:	$("#sch_level").val(),
			  sch_onsite:	$("#sch_onsite").val(),
			  sch_trial:	$("#sch_trial").val(),
			  sch_lang:		$("#sch_lang").val(),
			  sch_group:	$("#sch_group").val(),
			  sch_idd:		$("#sch_idd").val(),
			  sch_date:		$("#sch_date").val(),
			  orderBY:		allObj.tabData.condition.orderBY,
			  orderSQ:		allObj.tabData.condition.orderSQ
		  },
		  dataType: "json",  
		  //contentType: "text/html; charset=utf-8",
		  error: function(xhr, tStatus, errorTh ) {
			  alert("Error (event_calendar_group_signature_grp.php): " + xhr.responseText + "\nStatus: " + tStatus);
		  },
		  success: function(req, tStatus) {
			  var w1 = window.open("output.html");
			  var html_str = "<" + "html" + "><" + "head" + "><" + "title" + ">Puti Meditation Student Registration Form</" + "title" + "></" + "head" + "><" + "body>";
			  w1.document.open();
			  w1.document.write(html_str);
			  w1.document.write(req.data);
			  w1.document.write('</html>');
			  w1.document.close();
			  w1.print();
		  },
		  type: "post",
		  url: "ajax/event_calendar_group_signature_grp.php"
	  });
}



function print_signature(eid, mid) {
	  $.ajax({
		  data: {
			  admin_sess: $("input#adminSession").val(),
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
