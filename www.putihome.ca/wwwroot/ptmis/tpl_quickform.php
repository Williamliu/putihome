<?php 
include_once($CFG["include_path"] . "/lib/html/html.php");
?>
<form name="qform">
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
                         	<input class="form-input" style="width:60px;" id="last_name" name="last_name" value="<?php echo $lname;?>" />
                            <span class="required">*</span>	
                         </td>
                         <td></td><td></td>
                   </tr>
                   <tr>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:120px;" id="first_name" name="first_name" value="<?php echo $fname;?>" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["identify number"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="identify_no" name="identify_no" value="" />
                         </td>
                   </tr>

                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:60px;" id="dharma_name" name="dharma_name" value="<?php echo $dharma;?>" />
                                <input class="form-input" style="width:100px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                         </td>
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
					</tr>
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

<?php } else {?>

                	<tr>
                    	 <td class="title"><?php echo $words["first name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:120px;" id="first_name" name="first_name" value="<?php echo $fname;?>" />
                            <span class="required">*</span>	
                         </td>
                         <td></td><td></td>
                   </tr>
                   <tr>
                    	 <td class="title"><?php echo $words["last name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:120px;" id="last_name" name="last_name" value="<?php echo $lname;?>" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo $words["identify number"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="identify_no" name="identify_no" value="" />
                         </td>
                   </tr>

                	<tr>
                    	 <td class="title"><?php echo $words["dharma name"]?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:60px;" id="dharma_name" name="dharma_name" value="<?php echo $dharma;?>" />
                                <input class="form-input" style="width:100px;" id="dharma_pinyin" name="dharma_pinyin" value="" />
                         </td>
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
					</tr>
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
								echo cHTML::radio("gender", $gender_array, "Female");
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
<?php } ?>

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
                </table>
            </td>
            <td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="2" class="line"><b><?php echo $words["contact information"]?>:</b></td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["email"]?>: </td>
                    	 <td>
                         	<input class="form-input" id="email" name="email" value="<?php echo $_REQUEST["sch_email"];?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["phone"]?>: </td>
                    	 <td>
                         	<input class="form-input" id="phone" name="phone" value="<?php echo $_REQUEST["sch_phone"];?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["cell"]?>: </td>
                    	 <td>
                         	<input class="form-input" id="cell" name="cell" value="<?php echo $_REQUEST["sch_phone"];?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo $words["city"]?>: </td>
                    	 <td>
                         	<input class="form-input" id="city" name="city" value="<?php echo $reg_city;?>" />
                         </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        	<td class="line" colspan="2" align="center" style="padding-top:5px; padding-bottom:20px;">
            	<input type="button" id="btn_submit" name="btn_submit" value="Submit" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
</form>


<script language="javascript" type="text/javascript">
$(function(){
  $("#btn_submit").bind("click", function(ev) {
	  $("#wait").loadShow();
	  $.ajax({
		  data: {
			  admin_sess: 	$("input#adminSession").val(),
			  admin_menu:	$("input#adminMenu").val(),
			  admin_oper:	"save",

			  event_id: 	$("#event_id").val(),
			  group_no: 	$("#group_no").val(),
			  onsite: 		$("#onsite").is(":checked")?1:0,
			  trial: 		$("#trial").is(":checked")?1:0,

			  idd: 			$("input#idd").val(),
			  first_name: 	$("input#first_name").val(),
			  last_name: 	$("input#last_name").val(),
			  legal_first: 	$("input#legal_first").val(),
			  legal_last: 	$("input#legal_last").val(),
			  dharma_name: 	$("input#dharma_name").val(),
			  dharma_pinyin:$("input#dharma_pinyin").val(),
			  alias: 		$("input#alias").val(),
			  identify_no: 	$("input#identify_no").val(),
			  gender: 		htmlObj.radio_get("gender"),

			  member_yy: 	$("input#member_yy").val(),
			  member_mm: 	$("select#member_mm").val(),
			  member_dd: 	$("select#member_dd").val(),

			  birth_yy: 	$("input#birth_yy").val(),
			  birth_mm: 	$("select#birth_mm").val(),
			  birth_dd: 	$("select#birth_dd").val(),
			  age: 			$("#age_range").val(),

			  member_lang:		htmlObj.radio_get("member_lang"),
			  languages: 		htmlObj.checkbox_get("languages"),
			  lang_main: 		$("#lang_main").val(),
			  lang_able: 		$("#lang_able").val(),
			  
			  email: 		$("input#email").val(),
			  phone: 		$("input#phone").val(),
			  cell: 		$("input#cell").val(),
			  email: 		$("input#email").val(),
			  city: 		$("input#city").val(),

			  hear_about: 			htmlObj.checkbox_get("hear_about"),
			  symptom: 				htmlObj.checkbox_get("symptom"),
			  other_symptom:		'',
			  
			  iread:				1,
			  iagree:				1
		  },
		  dataType: "json",  
		  error: function(xhr, tStatus, errorTh ) {
			  $("#wait").loadHide();
			  alert("Error (index_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
		  },
		  success: function(req, tStatus) {
			  $("#wait").loadHide();
			  if( req.errorCode > 0 ) { 
				  errObj.set(req.errorCode, req.errorMessage, req.errorField);
				  return false;
			  } else {
				  $("#trial").attr("checked", false);
				  tool_tips(words["save success"]);
				  qform.reset();
				  $("#first_name").focus();
				  
				  $("input[name='member_id']").val(req.data.member_id);
				  quick_ajax();
			  }
		  },
		  type: "post",
		  url: "ajax/puti_qform_save.php"
	  });
  });
			
});
</script>
