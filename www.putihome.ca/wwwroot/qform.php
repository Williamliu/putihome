<?php
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");

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
		<title>Bodhi Meditation Online Registration</title>
		
		<script type="text/javascript" 	src="jquery/min/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" 	src="jquery/min/jquery-ui-1.8.21.custom.min.js"></script>
        <link 	type="text/css" 		href="jquery/theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
		
		<script type="text/javascript" 	src="js/js.lwh.common.js"></script>
        <link 	type="text/css" 		href="theme/blue/content.css" rel="stylesheet" />

		<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.mmenu.js"></script>
    	<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.mmenu.css" rel="stylesheet" />
	
    	
		<script type="text/javascript" src="jquery/myplugin/jquery.lwh.diag.js"></script>
		<link type="text/css" href="jquery/myplugin/css/light/jquery.lwh.diag.css" rel="stylesheet" />
        
        
		
        <script language="javascript" type="text/javascript">
			$(function(){
				$(".lwhMMenu").lwhMMenu();
				
				if( $(":radio[name='therapy']:checked").val() == "Yes") 
						$("#div_therapy_yes").show();
					else 
						$("#div_therapy_yes").hide();

							
				$(":radio[name='therapy']").bind("click", function(ev) {
					if($(this).val() == "Yes") 
						$("#div_therapy_yes").show();
					else 
						$("#div_therapy_yes").hide();
					 
				});
				
			  $("#diaglog").lwhDiag({
				  titleAlign:		"center",
				  title:			"Error Message",
				  
				  cnColor:			"#F8F8F8",
				  bgColor:			"#EAEAEA",
				  ttColor:			"#94C8EF",
				   
				  minWW:			400,
				  minHH:			250,
				  btnMax:			false,
				  resizable:		false,
				  movable:			false,
				  maskable: 		true,
				  maskClick:		true,
				  pin:				false
			  });
				
			  $("#btn_submit").bind("click", function(ev) {
				  var errCode = 0;
				  var errMsg  = "<br>We can not process your submit for below error:<br><br>";
				  var birthDate = "";
				  if( $("#birth_year").val() != "" || $("#birth_month").val() != "" || $("#birth_day").val() != "") {
					  if( $("#birth_year").val() != ""  && $("#birth_month").val() != "" && $("#birth_day").val() != "" ) {
						  birthDate = $("#birth_year").val() + "-" + $("#birth_month").val() + "-" + $("#birth_day").val();
					  } else {
						  errCode = 1;
						  errMsg += "<li class='error'>Birth Date: Please complete 'Birth Date' input.</li><br>";  			  
					  }
				  }

				  if( !$("#iread").is(":checked") ) {
						  errCode = 1;
						  errMsg += "<li class='error'>Please read our 'Individual and Risk Release' before submit.</li><br>";  			  
				  }

				  if( !$("#iagree").is(":checked") ) {
						  errCode = 1;
						  errMsg += "<li class='error'>You don't agree our 'Individual and Risk Release'.</li>";  			  
				  }
				  
				  if( errCode > 0 )  {
				 	$(".lwhDiag-content", "#diaglog").html(errMsg);
					$("#diaglog").diagShow({title:"Error Message"}); 
					return;
				  }
				  
				  $.ajax({
					  data: {
						  first_name: 	$("input#first_name").val(),
						  last_name: 	$("input#last_name").val(),
						  gender: 		$("input:radio[name='gender']:checked").val(),
						  dharma_name: 	$("input#dharma_name").val(),
						  alias: 		$("input#alias").val(),
						  
						  email: 		$("input#email").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  email: 		$("input#email").val(),
						  city: 		$("input#city").val(),
						  
						  iread:				$("input:checkbox[name='iread']:checked").val(),
						  iagree:				$("input:radio[name='agreement']:checked").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (index_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  $(".lwhDiag-content", "#diaglog").html(req.errorMessage.nl2br() );
							  $("#diaglog").diagShow({title:"Error Message"}); 
							  return false;
						  } else {
				 			//var msg = "<br>Your submit has been received successfully, we will contact you as soon as possible.<br><br>Thank you and best wishes to you.";
							
							$(".lwhDiag-content", "#diaglog").html(req.errorMessage);
							$("#diaglog").diagShow({title:"Submit Success"}); 
						  	qform.reset();
						  }
					  },
					  type: "post",
					  url: "ajax/qform_save.php"
				  });
			  });
			
			});
			
        </script>

</head>
<body>
<div class="main-layout">
	<div class="main-header">
			<?php 
                include("public_menu_html.php");
            ?>
    </div>
	<br />
    <center><span class="form-header">Bodhi Meditation Student Registration Form</span></center>
    <form name="qform">
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0">
					<tr>
                    	<td colspan="4"><b>Personal Information:</b></td>
                    </tr>
                	<tr>
                    	 <td class="title">First Name: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="" />
                            <span class="required">*</span>	
                         </td>

                    	 <td class="title"  width="30" style="white-space:nowrap;">Legal First: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="legal_first" name="legal_first" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title">Last Name: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="last_name" name="last_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"  width="30" style="white-space:nowrap;">Legal Last: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="legal_last" name="legal_last" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title">Dharma Name: </td>
                    	 <td>
							<input class="form-input" style="width:100px;" id="dharma_name" name="dharma_name" value="" />
                         </td>
                    	
                         <td class="title">Age Range: </td>
                    	 <td style="white-space:nowrap;">
                         	<select id="age_range" style="text-align:center;" name="age_range">
                            	<option value=""></option>
								<?php
									$result_age = $db->query("SELECT * FROM puti_members_age order by id");
									while( $row_age = $db->fetch($result_age) ) {
										echo '<option value="' . $row_age["id"] . '">' . $row_age["title"] . '</option>';
									}
								?>
                            </select> Years Old
                        	<span class="required">*</span>
                         </td>
                    </tr>

                	<tr>
                    	 <td class="title">Alias: </td>
                    	 <td>
							<input class="form-input" style="width:100px;" id="alias" name="alias" value="" />
                         </td>

                    	 <td class="title">Gender: </td>
                    	 <td style="white-space:nowrap;">
                         	<input type="radio" id="gender_male" name="gender" value="Male" /><label for="gender_male">Male</label> 
                         	<input type="radio" id="gender_female" name="gender" value="Female" /><label for="gender_female">Female</label>
                        	<span class="required">*</span>
                         </td>
                    </tr>
                </table>
            </td>
            <td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="2" class="line"><b>Contact Information:</b></td>
                    </tr>
                	<tr>
                    	 <td class="title">Email: </td>
                    	 <td>
                         	<input class="form-input" id="email" name="email" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title">Phone: </td>
                    	 <td>
                         	<input class="form-input" id="phone" name="phone" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title">Cell: </td>
                    	 <td>
                         	<input class="form-input" id="cell" name="cell" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title">City: </td>
                    	 <td>
                         	<input class="form-input" id="city" name="city" value="" />
                         </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        	<td colspan="2" align="center">
            	<span style="font-size:16px; font-weight:bold;">Individual and Risk Release</span>
            </td>
        </tr>
        <tr>
        	<td colspan="2" align="left">
            	<span style="font-size:12px;">
                I assume all risks of damage and injuries that may occur to me while participating in the Bodhi Meditation course and 
                while on the premises at which the classes are held. I am aware that some courses may involve yoga, mindful stretching
                and mental exercises. I hereby release and discharge the Canada Bodhi Dharma Society and its agents and representatives
                from all claims or injuries resulting from my participation in the program.<br />
                <br />
                I hereby grant permission to the Canada Bodhi Dharma Society, Including its successors and assignees to record and use 
                my name, image and voice, for use in its promotional and informational productions. I further grant the Canada Bodhi Dharma 
                Society permission to edit and modify these recordings in the making of productions as long as no third party's rights are 
                infringeed by their use. Lastly, I release any and all legal claims against the Canada Bodhi Dharma Association for using, 
                distributing or broadcasting any productions.<br />
                <br />
                I have read, understood, and I guarantee that all the information I have provide above is true and correct to the best of 
                my knowledge. I agree to the above release.
                </span>
                <center>
                	<input type="checkbox" id="iread" name="iread" value="I have read" /><label for="iread"><b>I have read above content</b></label>
                </center>
                <br />
                <center>
                	<input type="radio" id="irefuse" name="agreement" value="I do not agree" /><label for="irefuse"><b>I don't agree</b></label>
                    <input type="radio" id="iagree" name="agreement" value="I agree" style="margin-left:50px;" /><label for="iagree"><b>I agree</b></label>
              	</center>
            </td>
        </tr>
        <tr>
        	<td class="line" colspan="2" align="center" style="padding-top:20px; padding-bottom:20px;">
            	<input type="button" id="btn_submit" name="btn_submit" value="Submit" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
    </form>
</div>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<div id="diaglog" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
	</div>
</div>

</body>
</html>