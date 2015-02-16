<?php
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");
if($_REQUEST["event_id"]=="") {
	header("Location: http://" . $CFG["web_domain"]);
}
$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$query = "SELECT b.id, b.title, b.description, c.city, c.state FROM event_calendar a INNER JOIN puti_sites c ON (a.site = c.id) INNER JOIN (SELECT * FROM puti_agreement_lang WHERE lang = '" . $Glang . "') b ON (a.agreement = b.agreement_id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
//echo $query;
$result = $db->query($query);
$row = $db->fetch($result);
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

		<?php include("web_head_link.php"); ?>    
        
        <script language="javascript" type="text/javascript">
			var htmlObj = new LWH.cHTML();
			$(function(){
				/*
				if( $(":radio[name='therapy']:checked").val() == "1") 
						$("#div_therapy_yes").show();
					else 
						$("#div_therapy_yes").hide();

							
				$(":radio[name='therapy']").bind("click", function(ev) {
					if($(this).val() == "1") 
						$("#div_therapy_yes").show();
					else 
						$("#div_therapy_yes").hide();
					 
				});
				*/
				
			  $("#diaglog").lwhDiag({
				  titleAlign:		"center",
				  title:			words["error message"],
				  
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
				  var errMsg  = '<br>' + words["submit error"] + ':<br><br>';
				  
				  /*
				  var birthDate = "";
				  if( $("#birth_year").val() != "" || $("#birth_month").val() != "" || $("#birth_day").val() != "") {
					  if( $("#birth_year").val() != ""  && $("#birth_month").val() != "" && $("#birth_day").val() != "" ) {
						  birthDate = $("#birth_year").val() + "-" + $("#birth_month").val() + "-" + $("#birth_day").val();
					  } else {
						  errCode = 1;
						  errMsg += "<li class='error'>Birth Date: Please complete 'Birth Date' input.</li><br>";  			  
					  }
				  }
				  */
				  
				  if( !$("#iread").is(":checked") ) {
						  errCode = 1;
						  errMsg += "<li class='error'>" + words["read agreement before submit"] + "</li><br>";  			  
				  }

				  if( !$("#iagree").is(":checked") ) {
						  errCode = 1;
						  errMsg += "<li class='error'>" + words["you dont read agreement"] + "</li>";  			  
				  }
				  
				  if( errCode > 0 )  {
				 	$(".lwhDiag-content", "#diaglog").html(errMsg);
					$("#diaglog").diagShow(); 
					return;
				  }
				  
				  $.ajax({
					  data: {
						  event_id:		$("#event_id").val(),
						  first_name: 	$("input#first_name").val(),
						  last_name: 	$("input#last_name").val(),
						  legal_first: 	$("input#legal_first").val(),
						  legal_last: 	$("input#legal_last").val(),
						  gender: 		htmlObj.radio_get("gender"),
						  age:			$("#age_range").val(),
						  //birth_date: 	birthDate,
						  dharma_name: 	$("input#dharma_name").val(),
						  alias: 		$("input#alias").val(),
						  identify_no: 	$("input#identify_no").val(),

						  email: 		$("input#email").val(),
						  phone: 		$("input#phone").val(),
						  cell: 		$("input#cell").val(),
						  email: 		$("input#email").val(),
						  contact_method: htmlObj.checkbox_get("contact_method"),
						  address: 		$("input#address").val(),
						  city: 		$("input#city").val(),
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

						  medical_concern: 		$("textarea#medical_concern").val(),

						  transportation: 		htmlObj.radio_get("transportation")?htmlObj.radio_get("transportation"):0,
						  plate_no: 			$("input#plate_no").val(),
						  offer_carpool: 		$("input#offer_carpool").is(":checked")?1:0,
						  
						  iread:				$("input:checkbox[name='iread']:checked").val(),
						  iagree:				$("input:radio[name='agreement']:checked").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (fform_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( req.errorCode > 0 ) { 
							  $(".lwhDiag-content", "#diaglog").html(req.errorMessage.nl2br() );
							  $("#diaglog").diagShow(); 
							  return false;
						  } else {
							$("#welcome_event_id").val(req.data.event_id);
							$("#welcome_member_id").val(req.data.member_id);
							welcomeform.submit();
						  }
					  },
					  type: "post",
					  url: "ajax/fform_save.php"
				  });
			  });
			
			});
			
        </script>

</head>
<body>
<?php 
include("public_menu_html.php");
?>
	<br />
    <center><span class="form-header"><?php echo $words["register form"]?></span></center>
    <form name="fform">
   	<input type="hidden" id="event_id" name="event_id" value="<?php echo $_REQUEST["event_id"];?>" />
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0">
					<tr>
                    	<td colspan="4"><b><?php echo $words["personal information"]?>:</b></td>
                    </tr>
<?php if($Glang!="en") { ?>
                	<tr>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:60px;" id="last_name" name="last_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="" />
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
                         	<input class="form-input" style="width:100px;" id="legal_first" name="legal_first" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td>
							<input class="form-input" style="width:60px;" id="dharma_name" name="dharma_name" value="" />
                         </td>
                    	 <td class="title"><?php echo $words["alias"]?>: </td>
                    	 <td>
							<input class="form-input" style="width:100px;" id="alias" name="alias" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["gender"]?>: </td>
                    	 <td style="white-space:nowrap;">
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
                    	 <td class="title"><?php echo $words["identify number"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="identify_no" name="identify_no" value="" />
                         </td>
                    </tr>

                	<tr>
                         <td class="title"><?php echo $words["age range"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<select id="age_range" style="text-align:center;" name="age_range">
                            	<option value=""></option>
								<?php
									$result_age = $db->query("SELECT * FROM puti_members_age order by id");
									while( $row_age = $db->fetch($result_age) ) {
										echo '<option value="' . $row_age["id"] . '">' . $row_age["title"] . '</option>';
									}
								?>
                            </select> <?php echo $words["years old"]?>
                        	<span class="required">*</span>
                         </td>
                    	 <td class="title"></td>
                    	 <td  style="white-space:nowrap;"></td>
                    </tr>
<?php } else { ?>

                	<tr>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" id="first_name" name="first_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" id="last_name" name="last_name" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["gender"]?>: </td>
                    	 <td style="white-space:nowrap;">
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
                    </tr>

                	<tr>
                         <td class="title"><?php echo $words["age range"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<select id="age_range" style="text-align:center;" name="age_range">
                            	<option value=""></option>
								<?php
									$result_age = $db->query("SELECT * FROM puti_members_age order by id");
									while( $row_age = $db->fetch($result_age) ) {
										echo '<option value="' . $row_age["id"] . '">' . $row_age["title"] . '</option>';
									}
								?>
                            </select> <?php echo $words["years old"]?>
                        	<span class="required">*</span>
                         </td>
                    </tr>

<?php } ?>

					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["contact information"]?>:</b></td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["email"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="email" name="email" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["phone"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="phone" name="phone" value="" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["cell"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="cell" name="cell" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td colspan="4" align="left">
                         	<table>
                            	<tr>
                                	<td>
                         				<?php echo $words["preferred method of contact"]?>: 
                            		</td>
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
                                </tr>
                            </table>
                         </td>
                    </tr>
					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["address information"]?>:</b></td>
                    </tr>
                    <tr>
                    	 <td class="title"><?php echo $words["address"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="address" name="address" value="" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["city"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="city" name="city" value="<?php echo $row["city"];?>" />
                         </td>
                    </tr>
                	<tr>

                    	 <td class="title"><?php echo $words["state"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="state" name="state" value="<?php echo $row["state"];?>" />
                         </td>
                    </tr>
                	<!--
                    <tr>
                    	 <td class="title"><?php echo $words["country"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="country" name="country" value="Canada" />
                         </td>
                    </tr>
                    -->
                	<tr>
                    	 <td class="title"><?php echo $words["postal code"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="postal" name="postal" value="" />
                         </td>
                    </tr>
                </table>
            </td>
            <td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="2"><b><?php echo $words["emergency contact name and relationship"]?>:</b></td>
                    </tr>
					<tr>
                    	<td><?php echo $words["contact name"]?>: </td>
                        <td>
                        	<input class="form-input" id="emergency_name" name="emergency_name" value="" />
                            <span class="required">*</span>
                        </td>
                    </tr>
					<tr>
                    	<td><?php echo $words["contact phone"]?>: </td>
                        <td>
                        	<input class="form-input" id="emergency_phone" name="emergency_phone" value="" />
                            <span class="required">*</span>
                        </td>
                    </tr>
					<tr>
                    	<td><?php echo $words["relationship"]?>: </td>
                        <td>
                        	<input class="form-input" id="emergency_ship" name="emergency_ship" value="" />
                        	<span class="required">*</span>
                        </td>
                    </tr>

					<tr>
                    	<td colspan="2"><br /></td>
                    </tr>


					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["how did you hear about us?"]?> <span class="required">*</span></b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
								$rows_hearfrom = $db->rows($result_hearfrom);
								echo ($Glang=="en"?cHTML::checkbox('hear_about',$rows_hearfrom,6):cHTML::checkbox('hear_about',$rows_hearfrom,8));
							?>
                        </td>
                    </tr>

					<tr>
                    	<td colspan="2"><br /></td>
                    </tr>

					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["ailment & symptom"]?></b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
								$rows_symptom = $db->rows($result_symptom);
								echo ($Glang=="en"?cHTML::checkbox('symptom',$rows_symptom,4):cHTML::checkbox('symptom',$rows_symptom,7));
								//echo cHTML::checkbox('symptom',$rows_symptom,20);
							?><br />
                            <span><?php echo $words["specify"]?>: <input type="text" id="other_symptom" name="other_sympton" style="width:200px;" value="" /></span>
                        </td>
                    </tr>
<!--
					<tr>
                    	<td colspan="2" class="line">
	                      	<b><?php echo $words["are you currently receiving therapy of some kind?"]?></b>
                           	<?php
								$therapy_array = array();
								$therapy_array[0]["id"] 	= "0";
								$therapy_array[0]["title"] 	= "No";
								$therapy_array[1]["id"] 	= "1";
								$therapy_array[1]["title"] 	= "Yes";
								echo cHTML::radio("therapy", $therapy_array);
							?>
                        	<div id="div_therapy_yes" style="display:none;">
	                      	<?php echo $words["if yes, please provide details regarding the nature of the therapy/treatment"]?> : 
                            <textarea id="therapy_content" name="therapy_content" style="width:98%; height:40px; resize:none;"></textarea>
							</div>
                        </td>
                    </tr>
-->
<?php if($Glang!="en" && false) { ?>
					<tr>
                    	<td colspan="2"><br /></td>
                    </tr>
 
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
<?php } ?>

                </table>
            </td>
        </tr>
<?php 
if( $row["id"] ) {
?>
        <tr>
        	<td colspan="2" align="center">
            	<span style="font-size:16px; font-weight:bold;">
                <?php echo stripslashes($row["title"]); ?>
                </span>
            </td>
        </tr>
        <tr>
        	<td colspan="2" align="left">
            	<div style="font-size:12px; padding:10px; text-align:justify; text-justify:inter-ideograph;">
					<?php echo stripslashes($row["description"]); ?>
                </div>
                <center>
                	<input type="checkbox" id="iread" name="iread" value="I have read" /><label for="iread"><b><?php echo $words["i have read"]?></b></label>
                </center>
                <br />
                <center>
                	<input type="radio" id="irefuse" name="agreement" value="I do not agree" /><label for="irefuse"><b><?php echo $words["i dont agree"]?></b></label>
                    <input type="radio" id="iagree" name="agreement" value="I agree" style="margin-left:50px;" /><label for="iagree"><b><?php echo $words["i agree"]?></b></label>
              	</center>
            </td>
        </tr>
<?php
} else {
?>
<input type="checkbox" id="iread" name="iread" style="display:none;" value="I have read" />
<input type="checkbox" id="iagree" name="agreement" style="display:none;" value="I agree" />
<?php
}
?>
        <tr>
        	<td class="line" colspan="2" align="center" style="padding-top:20px; padding-bottom:20px;">
            	<input type="button" id="btn_submit" name="btn_submit" value="<?php echo $words["submit"]?>" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
    </form>
<?php 
include("public_footer_html.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<div id="diaglog" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
	</div>
</div>

<form name="welcomeform" action="https://<?php echo $CFG["web_domain"] ?>/welcome.php" method="post">
	<input type="hidden" id="welcome_event_id" 	name="event_id" value="" />
	<input type="hidden" id="welcome_member_id" name="member_id" value="" />
</form>

</body>
</html>