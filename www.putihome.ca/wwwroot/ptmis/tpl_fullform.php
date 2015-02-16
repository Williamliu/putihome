<?php 
include_once($CFG["include_path"] . "/lib/html/html.php");
?>
<form name="frm_student">
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="4"><b><?php echo $words["personal information"]?>:</b></td>
                    </tr>
                	<?php if( $admin_user["lang"] != "en" ) { ?>
                	<tr>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:50px;" id="last_name" name="last_name" value="<?php echo $lname;?>" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="<?php echo $fname;?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:50px;" id="dharma_name" name="dharma_name" value="<?php echo $dharma;?>" />
                                <input class="form-input" style="width:100px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                         </td>
                    	 <td class="title"><?php echo $words["alias"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="alias" name="alias" value="" />
                         </td>
                    </tr>
                    <tr>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal last"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:50px;" id="legal_last" name="legal_last" value="" />
                         </td>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["legal first"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="legal_first" name="legal_first" value="" />
                         </td>
                    </tr>
                	<?php } else { ?>
                	<tr>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="<?php echo $fname;?>" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="last_name" name="last_name" value="<?php echo $lname;?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:50px;" id="dharma_name" name="dharma_name" value="<?php echo $dharma;?>" />
                                <input class="form-input" style="width:100px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                         </td>
                    	 <td class="title"><?php echo $words["alias"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="alias" name="alias" value="" />
                         </td>
                    </tr>
					<?php } ?>

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
                                <input class="form-input" style="width:100px;" id="identify_no" name="identify_no" value="" />
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
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo $words["id card"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="idd" name="idd" value="" />
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
                          <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["member enter date"]?>: </td>
                          <td style="white-space:nowrap;">
                                <input class="form-input" style="width:40px; text-align:center;" id="member_yy" name="member_yy" maxlength="4" value="<?php echo date("Y")?>" />
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="member_mm" name="member_mm">
                                    <option value="0"><?php echo $words["month"]?></option>
                                    <?php
                                        for($i=1;$i<=12;$i++) {
                                            echo '<option value="' . $i . '" ' . ($i==date("n")?'selected':'') .'>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="member_dd" name="member_dd">
                                    <option value="0"><?php echo $words["bday"]?></option>
                                    <?php
                                        for($i=1;$i<=31;$i++) {
                                            echo '<option value="' . $i . '"' . ($i==date("j")?'selected':'') .'>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                          </td>
                    </tr>

					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["language ability"]?>:</b></td>
                    </tr>
					<tr>
 	                  	<td class="title"><?php echo $words["preferred language"]?>: </td>
                    	<td colspan="3" align="left">
								<?php 
                                    echo iHTML::radio($admin_user["lang"], $db, "vw_vol_language", "member_lang", "", 99, 0, 0);
                                ?>
                                <input class="form-input" style="width:80px;" id="lang_main" name="lang_main" value="" /> 
                        </td>
                    </tr>
					<tr>
 	                  	<td class="title"><?php echo $words["language ability"]?>: </td>
                    	<td colspan="3" align="left">
								<?php 
                                    echo iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "languages", "", 99, 0, 0);
                                ?>
                                <input class="form-input" style="width:80px;" id="lang_able" name="lang_able" value="" /> 
                        </td>
                    </tr>

					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["contact information"]?>:</b></td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["email"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="email" name="email" value="<?php echo $_REQUEST["sch_email"];?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["phone"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="phone" name="phone" value="<?php echo $_REQUEST["sch_phone"];?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["cell"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="cell" name="cell" value="<?php echo $_REQUEST["sch_phone"];?>" />
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
                         	<input class="form-input" id="city" name="city" value="<?php echo $reg_city;?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["state"]?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="state" name="state" value="<?php echo $reg_state;?>" />
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
                    	<td colspan="2"><b><?php echo $words["member.select_option"]?>:</b></td>
                    </tr>
					<tr>
                    	<td colspan="2">
							<table cellpadding="2" cellspacing="0">
                                <tr>
                                     <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["member.degree"]?>: </td>
                                     <td>
                                        <?php
                                            echo iHTML::select($admin_user["lang"], $db, "vw_vol_degree","degree","",0);
                                        ?>
                                     </td>
                                    <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["past_position"]?>: </td>
                                    <td>
                                        <input class="form-input" style="width:160px;" id="past_position" name="past_position" value="" />                                        
                                    </td>
                                </tr>
                                <tr>
                                     <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["religion"]?>: </td>
                                     <td>
                                        <?php
                                            echo iHTML::select($admin_user["lang"], $db, "vw_vol_religion","religion","",0);
                                        ?>
                                     </td>
                                    <td class="title" style="width:60px; white-space:nowrap;"><?php echo $words["current_position"]?>: </td>
                                    <td>
                                        <input class="form-input" style="width:160px;" id="current_position" name="current_position" value="" />                                        
                                    </td>
                                </tr>
							</table>							
						</td>
                    </tr>

					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["emergency contact name and relationship"]?>:</b></td>
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

					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["how did you hear about us?"]?></b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
								$rows_hearfrom = $db->rows($result_hearfrom);
								echo 
								$admin_user["lang"]=="en"?
								cHTML::checkbox('hear_about',$rows_hearfrom,6):
								cHTML::checkbox('hear_about',$rows_hearfrom,8);
							?>
                        </td>
                    </tr>
					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["ailment & symptom"]?></b></td>
                    </tr>
					<tr>
                    	<td colspan="2" align="left">
                         	<?php 
								$result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
								$rows_symptom = $db->rows($result_symptom);
								echo ($admin_user["lang"]=="en"?cHTML::checkbox('symptom',$rows_symptom,4):cHTML::checkbox('symptom',$rows_symptom,6));
							?><br />
                            <span><?php echo $words["specify"]?>: <input type="text" id="other_symptom" name="other_sympton" style="width:200px;" value="" /></span>
                        </td>
                    </tr>
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
								<?php echo $words["plate no"]?>: <input type="text" id="plate_no" name="plate_no" style="width:80px;" value="<?php echo $_REQUEST["sch_plate_no"];?>" />
                            </span>
                            <span style="margin-left:5px;">
                            	<input type="checkbox" id="offer_carpool" name="offer_carpool" value="1" /><label for="offer_carpool"><?php echo $words["offer carpool"]?></label>
                            </span>}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

<?php 
	/*
	$query_ans = "SELECT b.id, b.title, b.description FROM event_calendar a INNER JOIN (SELECT * FROM puti_agreement_lang WHERE lang = '" . $admin_user["lang"] . "') b ON (a.agreement = b.agreement_id) WHERE a.id = '" . $_REQUEST["enroll_event_id"] . "'";
	$result_ans = $db->query( $query_ans );
	$row_ans	= $db->fetch( $result_ans);

	if( $row_ans["id"]>0 ) {
		  $html.= '<tr>';
			$html.= '<td colspan="2" align="left">';
				$html.= '<center><span style="font-size:16px; font-weight:bold">' . cTYPE::gstr($row_ans["title"]) . '</span></center>';
				$html.= '<div style="font-size:14px; padding:10px; text-align:justify; text-justify:inter-ideograph;">';
				$html.= cTYPE::gstr($row_ans["description"]);
				$html.= '</div>';
				$html.= '<center><input type="checkbox" id="iread" name="iread" checked value="I have read" /><label for="iread"><b>' . $words["i have read"] . '</b></label></center>';
                $html.= '<center>
                	<input type="radio" id="irefuse" name="agreement" value="I do not agree" /><label for="irefuse"><b>'.$words["i dont agree"].'</b></label>
                    <input type="radio" id="iagree" name="agreement" checked value="I agree" style="margin-left:50px;" /><label for="iagree"><b>'.$words["i agree"].'</b></label>
              	</center>';
			
			 $html.= '</td>';
		  $html.= '</tr>';
		  echo $html;
	} else {
		  $html.= '';
		  echo $html;
	}
	*/
?>

        <tr>
            <td colspan="2" class="line"><b><?php echo $words["please write down any other medical concerns or history"]?>: </b></td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <textarea id="medical_concern" name="medical_concern" style="width:98%; height:60px; resize:none;"></textarea>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="line"><b><?php echo $words["email subscription"]?> <span class="required">***</span>: </b></td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <span style="font-size:14px;">
                    <?php echo cTYPE::gstr($words["email subscription agreement"])?> 
                </span><br />
                <center>
                	<input type="radio" id="irefuse" 	name="email_flag" value="0" /><label for="irefuse"><b><?php echo $words["i dont agree"]?></b></label>
                    <input type="radio" id="iagree" 	name="email_flag" value="1" style="margin-left:50px;" /><label for="iagree"><b><?php echo $words["i agree"]?></b></label>
              	</center>
            </td>
        </tr>

        <tr>
        	<td class="line" colspan="2" align="center" style="padding-top:5px; padding-bottom:20px;">
            	<input type="button" right="save" id="btn_submit" name="btn_submit" value="<?php echo $words["submit"]?>" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
</form>

<script language="javascript" type="text/javascript">
$(function(){
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

		  	$("#btn_submit").bind("click", function(ev) {
				  if( $("#iread").length > 0 ) {
						var errCode = 0;
						var errMsg  = "<br>We can not process your submit for below error:<br><br>";
					  
						if( !$("#iread").is(":checked") ) {
								errCode = 1;
								errMsg += "<li class='error'>" + words["read before submit"] + "</li><br>";  			  
						}
	  
						if( !$("#iagree").is(":checked") ) {
								errCode = 1;
								errMsg += "<li class='error'>" + words["you dont agree submit"] + "</li>";  			  
						}
						
						if( errCode > 0 )  {
						  $(".lwhDiag-content", "#diaglog_error").html(errMsg);
						  $("#diaglog_error").diagShow({title:"Error Message"}); 
						  return;
						}
				  }

				  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  admin_sess: 		$("input#adminSession").val(),
						  admin_menu:		$("input#adminMenu").val(),
						  admin_oper:		"save",

						  event_id: 		$("#event_id").val(),
						  group_no: 		$("#group_no").val(),
						  onsite: 			$("#onsite").is(":checked")?1:0,
						  trial: 			$("#trial").is(":checked")?1:0,

						  idd: 				$("input#idd").val(),
						  first_name: 		$("input#first_name").val(),
						  last_name: 		$("input#last_name").val(),
						  legal_first: 		$("input#legal_first").val(),
						  legal_last: 		$("input#legal_last").val(),
						  dharma_name: 		$("input#dharma_name").val(),
						  dharma_pinyin:    $("input#dharma_pinyin").val(),
						  alias: 			$("input#alias").val(),
						  identify_no: 		$("input#identify_no").val(),

						  gender: 			htmlObj.radio_get("gender"),

						  member_yy: 	$("input#member_yy").val(),
						  member_mm: 	$("select#member_mm").val(),
						  member_dd: 	$("select#member_dd").val(),

						  degree: 		    $("#degree").val(),
						  current_position: $("#current_position").val(),
						  past_position:    $("#past_position").val(),
						  religion:         $("#religion").val(),

						  birth_yy: 		$("input#birth_yy").val(),
						  birth_mm: 		$("select#birth_mm").val(),
						  birth_dd: 		$("select#birth_dd").val(),
						  age: 				$("#age_range").val(),
						  //birth_date: 	birthDate,

                          email_flag:   $(":radio[name='email_flag']:checked").val()?$(":radio[name='email_flag']:checked").val():"",    
						  email: 			$("input#email").val(),
						  phone: 			$("input#phone").val(),
						  cell: 			$("input#cell").val(),
						  email: 			$("input#email").val(),

						  member_lang:		htmlObj.radio_get("member_lang"),
						  languages: 		htmlObj.checkbox_get("languages"),
						  lang_main: 		$("#lang_main").val(),
						  lang_able: 		$("#lang_able").val(),
			  

						  contact_method: 	htmlObj.checkbox_get("contact_method"),
						  address: 			$("input#address").val(),
						  city: 			$("input#city").val(),
						  state: 			$("input#state").val(),
						  country: 			$("input#country").val(),
						  postal: 			$("input#postal").val(),

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

						  medical_concern: 		$("textarea#medical_concern").val(),
						  
						  iread:				1,
						  iagree:				1
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  $("#wait").loadHide();
						  alert("Error (puti_registration_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  //$(".lwhDiag-content", "#diaglog_message").html( req.errorMessage.nl2br() );
							  //$("#diaglog_message").diagShow(); 
							  tool_tips(words["save success"]);
							  $("#trial").attr("checked", false);
							  frm_student.reset();
							  $("#first_name").focus();

							  $("input[name='member_id']").val(req.data.member_id);
							  full_ajax();
						  }
					  },
					  type: "post",
					  url: "ajax/puti_registration_save.php"
				  });
		  });
			
});
</script>
