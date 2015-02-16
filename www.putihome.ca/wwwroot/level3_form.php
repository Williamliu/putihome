<?php
session_start();
ini_set("display_errors", 0);
include_once("../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include_once($CFG["include_path"] . "/config/public_menu_struct.php");
if($_REQUEST["personalform_event_id"]=="") {
	header("Location: " . $CFG["http"]. $CFG["web_domain"]);
}

$publicSession = $_REQUEST["publicSession"]!=""?$_REQUEST["publicSession"]:$_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"];

$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
$query = "SELECT a.id, a.title, a.start_date, a.end_date, b.title as site_desc  FROM event_calendar a INNER JOIN puti_sites b ON (a.site = b.id) WHERE a.id = '" . $_REQUEST["personalform_event_id"] . "'";
$result = $db->query($query);
$row = $db->fetch($result);
$date_str = date("Y, M-d",$row["start_date"]) . ($row["end_date"]>0&&$row["start_date"]!=$row["end_date"]?" ~ ".date("M-d",$row["end_date"]):"");
$title_str = cTYPE::gstr($words[strtolower($row["site_desc"])]) . ' - ' . cTYPE::gstr($row["title"]) . " [" . $date_str . ']';

$query 	= "SELECT * FROM puti_members WHERE deleted <> 1 AND status = 1 AND sess_id = '" . $publicSession . "'";
$result = $db->query($query);
$row 	= $db->fetch($result);

$member_id = $row["id"]!=""?$row["id"]:-1;
$image_id = $row["id"]!=""?$row["id"]:$_REQUEST["personalform_event_id"].time();

$query 	= "SELECT * FROM puti_members_lang WHERE member_id = '" . $member_id . "'";
$result_lang = $db->query($query);
$langs = array();
while($row_lang = $db->fetch($result_lang) ) {
	$langs[] = $row_lang["language_id"];
}


$query 	= "SELECT * FROM puti_members_others WHERE member_id = '" . $member_id . "'";
$result_others = $db->query($query);
$row_others = $db->fetch($result_others);

$query 	= "SELECT * FROM puti_members_hearfrom WHERE member_id = '" . $member_id . "'";
$result_hear = $db->query($query);
$hears = array();
while($row_hear = $db->fetch($result_hear) ) {
	$hears[] = $row_hear["hearfrom_id"];
}

$query 	= "SELECT * FROM puti_members_symptom WHERE member_id = '" . $member_id . "'";
$result_symptom = $db->query($query);
$symptoms = array();
while($row_symptom = $db->fetch($result_symptom) ) {
	$symptoms[] = $row_symptom["symptom_id"];
}
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

		<script type="text/javascript" 	src="jquery/min/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.zoom.js"></script>
		<link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.zoom.css" rel="stylesheet" />
		
        <script type="text/javascript" 	src="jquery/myplugin/jquery.lwh.upload.js"></script>
        <link 	type="text/css" 		href="jquery/myplugin/css/light/jquery.lwh.upload.css" rel="stylesheet" />
        
        <script language="javascript" type="text/javascript">
			var aj = null;
			var htmlObj = new LWH.cHTML();
			$(function(){
				
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
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  publicSession: "<?php echo $publicSession?>",
						  event_id:		$("#event_id").val(),
						  member_id:	$("#member_id").val(),
						  image_id:		$("#image_id").val(),
						  password:		$("#password").val(),
						  cpassword:	$("#cpassword").val(),
						  first_name: 	$("input#first_name").val(),
						  last_name: 	$("input#last_name").val(),
						  legal_first: 	$("input#legal_first").val(),
						  legal_last: 	$("input#legal_last").val(),
						  gender: 		htmlObj.radio_get("gender"),
						  
						  birth_yy: 	$("input#birth_yy").val(),
						  birth_mm: 	$("select#birth_mm").val(),
						  birth_dd: 	$("select#birth_dd").val(),
						  age:			$("#age_range").val(),
						  //birth_date: 	birthDate,
						  dharma_name: 	$("input#dharma_name").val(),
						  alias: 		$("input#alias").val(),
						  identify_no: 	$("input#identify_no").val(),

						  member_lang:		htmlObj.radio_get("member_lang"),
						  languages: 		htmlObj.checkbox_get("languages"),
						  lang_main: 		$("#lang_main").val(),
						  lang_able: 		$("#lang_able").val(),
					   
						  email_flag:   $(":radio[name='email_flag']:checked").val()?$(":radio[name='email_flag']:checked").val():"",    
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

						  medical_concern: 		$("textarea#medical_concern").val(),

						  hear_about: 			htmlObj.checkbox_get("hear_about"),
						  symptom: 				htmlObj.checkbox_get("symptom"),
						  other_symptom:		$("input#other_symptom").val()
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (level3_form_save.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  $(".lwhDiag-content", "#diaglog").html(req.errorMessage.nl2br() );
							  $("#diaglog").diagShow(); 
							  return false;
						  } else {
							$("#welcome_event_id").val(req.data.event_id);
							$("*[name=publicSession]").val(req.data.publicSession);
							tool_tips(words["member enroll success"]);
							welcomeform.submit();
						  }
					  },
					  type: "post",
					  url: "ajax/level3_form_save.php"
				  });
			  });

			  $("#btn_cancel").bind("click", function(ev) {
		  		  $("#wait").loadShow();
				  $.ajax({
					  data: {
						  publicSession: "<?php echo $publicSession?>"
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
				  		  $("#wait").loadHide();
						  alert("Error (account_logout.php): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
				  		  $("#wait").loadHide();
						  if( req.errorCode > 0 ) { 
							  $(".lwhDiag-content", "#diaglog").html(req.errorMessage.nl2br() );
							  $("#diaglog").diagShow(); 
							  return false;
						  } else {
							$("*[name=publicSession]").val("");
							window.location.href = "<?php echo $_REQUEST["prev_url"];?>";
						  }
					  },
					  type: "post",
					  url: "ajax/account_logout.php"
				  });
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
				  ref_el:		"#image_id",  // important for change ref_id
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
				  },
		  
			  });
			  
			  
			  $(".lwhZoom-button-delete").hide();
			
			});
		
		function goback() {
			//alert("<?php echo $_REQUEST["prev_url"];?>");
			window.location.href = "<?php echo $_REQUEST["prev_url"];?>";
		}
        </script>

</head>
<body>
<?php 
include("public_menu_html_nohead.php");
?>
 	<a class="goto-event-calendar" href="index.php" style="width:100px; height:24px; line-height:24px; vertical-align:middle; font-size:14px; font-weight:bold;"><?php echo $words["main menu"]?></a>	<br />
	<span style="font-size:16px; font-weight:bold; margin-left:2px; color:#CD6868;"><?php echo cTYPE::gstr($words["select event"]) . " : "?></span>
	<span style="font-size:16px; font-weight:bold; margin-left:2px; color:blue;"><?php echo cTYPE::gstr($title_str)?></span>
    <br /><br />
    <center><span class="form-header"><?php echo cTYPE::gstr($words["register form"])?></span></center>
    <form name="fform">
   	<input type="hidden" id="event_id" name="event_id" value="<?php echo $_REQUEST["personalform_event_id"]?>" />
   	<input type="hidden" id="member_id" name="member_id" value="<?php echo $member_id?>" />
   	<input type="hidden" id="image_id" name="image_id" value="<?php echo $image_id?>" />
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="4"><b><?php echo cTYPE::gstr($words["important account"])?></b></td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["email"])?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="email" name="email" value="<?php echo cTYPE::gstr($row["email"])?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["password"])?>: </td>
                    	 <td colspan="3">
                         	<input type="password" class="form-input" id="password" name="password" value="<?php echo $row["password"]?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["confirm password"])?>: </td>
                    	 <td colspan="3">
                         	<input type="password" class="form-input" id="cpassword" name="cpassword" value="<?php echo $row["password"]?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>

					<tr>
                    	<td colspan="4">
                        <span style="color:red;"><?php echo cTYPE::gstr($words["password length tips"])?></span>
                        </td>
                    </tr>

					<tr>
                    	<td colspan="4" class="line"><b><?php echo cTYPE::gstr($words["personal information"])?>:</b></td>
                    </tr>
<?php if($Glang!="en") { ?>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["last name"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:60px;" id="last_name" name="last_name" value="<?php echo cTYPE::gstr($row["last_name"])?>" />
                            <span class="required">*</span>	
                         </td>
                    	 <td class="title"><?php echo cTYPE::gstr($words["first name"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="first_name" name="first_name" value="<?php echo cTYPE::gstr($row["first_name"])?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                    <tr>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo cTYPE::gstr($words["legal last"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:60px;" id="legal_last" name="legal_last" value="<?php echo cTYPE::gstr($row["legal_last"])?>" />
                         </td>
                    	 <td class="title"  width="30" style="white-space:nowrap;"><?php echo cTYPE::gstr($words["legal first"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" style="width:100px;" id="legal_first" name="legal_first" value="<?php echo cTYPE::gstr($row["legal_first"])?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["dharma name"])?>: </td>
                    	 <td>
							<input class="form-input" style="width:60px;" id="dharma_name" name="dharma_name" value="<?php echo cTYPE::gstr($row["dharma_name"])?>" />
                         </td>
                    	 <td class="title"><?php echo cTYPE::gstr($words["alias"])?>: </td>
                    	 <td>
							<input class="form-input" style="width:100px;" id="alias" name="alias" value="<?php echo cTYPE::gstr($row["alias"])?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["gender"])?>: </td>
                    	 <td style="white-space:nowrap;">
                           	<?php
								$gender_array = array();
								$gender_array[0]["id"] 		= "Male";
								$gender_array[0]["title"] 	= "Male";
								$gender_array[1]["id"] 		= "Female";
								$gender_array[1]["title"] 	= "Female";
								echo cHTML::radio("gender", $gender_array, $row["gender"]);
							?>
                        	<span class="required">*</span>
                         </td>
                    	 <td class="title"><?php echo cTYPE::gstr($words["identify number"])?>: </td>
                    	 <td style="white-space:nowrap;">
                                <input class="form-input" style="width:100px;" id="identify_no" name="identify_no" value="<?php echo cTYPE::gstr($row["identify_no"])?>" />
                         </td>
                    </tr>

                	<tr>
                         <td class="title"><?php echo cTYPE::gstr($words["age range"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<select id="age_range" style="text-align:center;" name="age_range">
                            	<option value=""></option>
								<?php
									$result_age = $db->query("SELECT * FROM puti_members_age order by id");
									while( $row_age = $db->fetch($result_age) ) {
										echo '<option value="' . $row_age["id"] . '" ' . ($row_age["id"]==$row["age"]?'selected':'') . '>' . $row_age["title"] . '</option>';
									}
								?>
                            </select> <?php echo cTYPE::gstr($words["years old"])?>
                        	<span class="required">*</span>
                         </td>
                         <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["birth date"]?>: </td>
                         <td style="white-space:nowrap;">
                                <input class="form-input" style="width:40px; text-align:center;" id="birth_yy" name="birth_yy" maxlength="4" value="<?php echo $row["birth_yy"]<=0?"":$row["birth_yy"];?>" />
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="birth_mm" name="birth_mm">
                                    <option value="0"><?php echo $words["month"]?></option>
                                    <?php
                                        for($i=1;$i<=12;$i++) {
                                            echo '<option value="' . $i . '" ' . ($row["birth_mm"]==$i?"selected":"") . '>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="birth_dd" name="birth_dd">
                                    <option value="0"><?php echo $words["bday"]?></option>
                                    <?php
                                        for($i=1;$i<=31;$i++) {
                                            echo '<option value="' . $i . '" ' . ($row["birth_dd"]==$i?"selected":""). '>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                
                          </td>
                    </tr>
<?php } else { ?>

                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["first name"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" id="first_name" name="first_name" value="<?php echo cTYPE::gstr($row["first_name"])?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["last name"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<input class="form-input" id="last_name" name="last_name" value="<?php echo cTYPE::gstr($row["last_name"])?>" />
                            <span class="required">*</span>	
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["gender"])?>: </td>
                    	 <td style="white-space:nowrap;">
                           	<?php
								$gender_array = array();
								$gender_array[0]["id"] 		= "Male";
								$gender_array[0]["title"] 	= "Male";
								$gender_array[1]["id"] 		= "Female";
								$gender_array[1]["title"] 	= "Female";
								echo cHTML::radio("gender", $gender_array, $row["gender"]);
							?>
                        	<span class="required">*</span>
                         </td>
                    </tr>

                	<tr>
                         <td class="title"><?php echo cTYPE::gstr($words["age range"])?>: </td>
                    	 <td style="white-space:nowrap;">
                         	<select id="age_range" style="text-align:center;" name="age_range">
                            	<option value=""></option>
								<?php
									$result_age = $db->query("SELECT * FROM puti_members_age order by id");
									while( $row_age = $db->fetch($result_age) ) {
										echo '<option value="' . $row_age["id"] . '" ' . ($row_age["id"]==$row["age"]?'selected':'') . '>' . $row_age["title"] . '</option>';
									}
								?>
                            </select> <?php echo cTYPE::gstr($words["years old"])?>
                        	<span class="required">*</span>
                         </td>
                    </tr>
                    <tr> 

                         <td class="title" width="30" style="white-space:nowrap;"><?php echo $words["birth date"]?>: </td>
                         <td style="white-space:nowrap;">
                                <input class="form-input" style="width:40px; text-align:center;" id="birth_yy" name="birth_yy" maxlength="4" value="<?php echo $row["birth_yy"]<=0?"":$row["birth_yy"];?>" />
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="birth_mm" name="birth_mm">
                                    <option value="0"><?php echo $words["month"]?></option>
                                    <?php
                                        for($i=1;$i<=12;$i++) {
                                            echo '<option value="' . $i . '" ' . ($row["birth_mm"]==$i?"selected":"") . '>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>
                                <span style="font-size:16px;font-weight:bold;">-</span>
                                <select style="text-align:center;" id="birth_dd" name="birth_dd">
                                    <option value="0"><?php echo $words["bday"]?></option>
                                    <?php
                                        for($i=1;$i<=31;$i++) {
                                            echo '<option value="' . $i . '" ' . ($row["birth_dd"]==$i?"selected":""). '>' . $i . '</option>';
                                        }
                                    ?>    
                                </select>&nbsp;<span style="color:red;">optional</span>
                                
                          </td>
                      </tr>
<?php } ?>
					<tr>
                    	<td colspan="4" class="line"><b><?php echo $words["language ability"]?>:</b></td>
                    </tr>
					<tr>
 	                  	<td class="title"><?php echo $words["preferred language"]?> <span class="required">*</span>: </td>
                    	<td colspan="3" align="left">
								<?php 
                                    echo iHTML::radio($Glang, $db, "vw_vol_language", "member_lang", "", 99, 0, 0);
                                ?>
                                <input class="form-input" style="width:80px;" id="lang_main" name="lang_main" value="" /> 
                        </td>
                    </tr>
					<tr>
 	                  	<td class="title"><?php echo $words["language ability"]?>: </td>
                    	<td colspan="3" align="left">
								<?php 
                                    echo iHTML::checkbox($Glang, $db, "vw_vol_language", "languages", "", 99, 0, 0);
                                ?>
                                <input class="form-input" style="width:80px;" id="lang_able" name="lang_able" value="" /> 
                        </td>
                    </tr>

					<tr>
                    	<td colspan="4" class="line"><b><?php echo cTYPE::gstr($words["address information"])?>:</b></td>
                    </tr>
                    <tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["address"])?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="address" name="address" value="<?php echo cTYPE::gstr($row["address"])?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["city"])?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="city" name="city" value="<?php echo cTYPE::gstr($row["city"])?>" />
                         </td>
                    </tr>
                	<tr>

                    	 <td class="title"><?php echo cTYPE::gstr($words["state"])?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="state" name="state" value="<?php echo cTYPE::gstr($row["state"])?>" />
                         </td>
                    </tr>
                	<!--
                    <tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["country"])?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="country" name="country" value="Canada" />
                         </td>
                    </tr>
                    -->
                	<tr>
                    	 <td class="title"><?php echo cTYPE::gstr($words["postal code"])?>: </td>
                    	 <td colspan="3">
                         	<input class="form-input" id="postal" name="postal" value="<?php echo cTYPE::gstr($row["postal"])?>" />
                         </td>
                    </tr>
                </table>
            </td>
            <td valign="top" width="50%" style="border:1px solid #cccccc;">
            	<table cellpadding="2" cellspacing="0" width="100%">
					<tr>
                    	<td colspan="2"><b><?php echo cTYPE::gstr($words["contact information"])?>:</b> <span class="required">*</span></td>
                        <td rowspan="10" align="center" valign="top" style="border-left:1px dotted #cccccc;">
                            <span style="color:red;font-size:12px;"><?php echo cTYPE::gstr($words["upload your photo"])?></span>
                            <br />
                            <div class="lwhZoom">
                                <img id="member_photo" src="<?php echo $CFG["http"] . $CFG["web_domain"] . "/ajax/lwhUpload_image.php?ts=" . time() . "&size=tiny&img_id=" . $member_id ?>" width="110" height="152" maxwidth="2048" />
                            </div>
						</td>
                    </tr>
                	<tr>
                    	 <td class="title" style="white-space:nowrap;"><?php echo cTYPE::gstr($words["phone"])?>: </td>
                    	 <td>
                         	<input class="form-input" style="width:150px;" id="phone" name="phone" value="<?php echo cTYPE::gstr($row["phone"])?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td class="title" style="white-space:nowrap;"><?php echo cTYPE::gstr($words["cell"])?>: </td>
                    	 <td>
                         	<input class="form-input" style="width:150px;" id="cell" name="cell" value="<?php echo cTYPE::gstr($row["cell"])?>" />
                         </td>
                    </tr>
                	<tr>
                    	 <td colspan="2" align="left">
                         	<table>
                            	<tr>
                                	<td>
                         				<?php echo cTYPE::gstr($words["preferred method of contact"])?>: 
                            		</td>
                                    <td>
										<?php
                                            $contact_array = array();
                                            $contact_array[0]["id"] 	= "Phone";
                                            $contact_array[0]["title"] 	= "Phone";
                                            $contact_array[1]["id"] 	= "Email";
                                            $contact_array[1]["title"] 	= "Email";
											$contact_val = explode(",", $row["contact_method"]);
                                            echo cHTML::checkbox("contact_method", $contact_array, $contact_val);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                         </td>
                    </tr>

					<tr>
                    	<td colspan="2"><br /></td>
                    </tr>

					<tr>
                    	<td colspan="2" class="line"><b><?php echo cTYPE::gstr($words["emergency contact name and relationship"])?>:</b></td>
                    </tr>
					<tr>
                    	<td><?php echo cTYPE::gstr($words["contact name"])?>: </td>
                        <td>
                        	<input class="form-input" style="width:150px;" id="emergency_name" name="emergency_name" value="<?php echo cTYPE::gstr($row_others["emergency_name"])?>" />
                            <span class="required">*</span>
                        </td>
                    </tr>
					<tr>
                    	<td><?php echo cTYPE::gstr($words["contact phone"])?>: </td>
                        <td>
                        	<input class="form-input" style="width:150px;" id="emergency_phone" name="emergency_phone" value="<?php echo cTYPE::gstr($row_others["emergency_phone"])?>" />
                            <span class="required">*</span>
                        </td>
                    </tr>
					<tr>
                    	<td><?php echo cTYPE::gstr($words["relationship"])?>: </td>
                        <td>
                        	<input class="form-input" style="width:150px;" id="emergency_ship" name="emergency_ship" value="<?php echo cTYPE::gstr($row_others["emergency_ship"])?>" />
                        	<span class="required">*</span>
                        </td>
                    </tr>

					<tr>
                    	<td colspan="2"><br /></td>
                    </tr>


					<tr>
                    	<td colspan="3" class="line"><b><?php echo cTYPE::gstr($words["how did you hear about us?"])?> <span class="required">*</span></b></td>
                    </tr>
					<tr>
                    	<td colspan="3" align="left">
                         	<?php 
								$result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
								$rows_hearfrom = $db->rows($result_hearfrom);
								echo ($Glang=="en"?cHTML::checkbox('hear_about',$rows_hearfrom,6,$hears):cHTML::checkbox('hear_about',$rows_hearfrom,8,$hears));
							?>
                        </td>
                    </tr>

					<tr>
                    	<td colspan="3"><br /></td>
                    </tr>

					<tr>
                    	<td colspan="3" class="line"><b><?php echo cTYPE::gstr($words["ailment & symptom"])?></b></td>
                    </tr>
					<tr>
                    	<td colspan="3" align="left">
                         	<?php 
								$result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
								$rows_symptom = $db->rows($result_symptom);
								echo ($Glang=="en"?cHTML::checkbox('symptom',$rows_symptom,4,$symptoms):cHTML::checkbox('symptom',$rows_symptom,7,$symptoms));
								//echo cHTML::checkbox('symptom',$rows_symptom,20);
							?><br />
                            <span><?php echo cTYPE::gstr($words["specify"])?>: <input type="text" id="other_symptom" name="other_sympton" style="width:200px;" value="<?php echo cTYPE::gstr($row_others["other_symptom"])?>" /></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="line"><b><?php echo $words["please write down any other medical concerns or history"]?> : </b></td>
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
        	<td class="line" colspan="2" align="center" style="padding-top:20px; padding-bottom:20px;">
            	<input type="button" id="btn_submit" name="btn_submit" value="<?php echo cTYPE::gstr($words["submit"])?>" style="font-size:14px; font-weight:bold;"  />
            	<input type="button" id="btn_cancel" name="btn_cancel" value="<?php echo cTYPE::gstr($words["menu_logout"])?>" style="font-size:14px; font-weight:bold;"  />
            </td>
        </tr>
    </table>
    </form>
<?php 
include("public_footer_html_nohead.php");
?>
<br /><br /><br /><br />
<br /><br /><br /><br />
<br /><br /><br /><br />

<div id="diaglog" class="lwhDiag">
	<div class="lwhDiag-content lwhDiag-no-border">
	</div>
</div>

<form name="welcomeform" action="<?php echo $CFG["http"] . $CFG["web_domain"]?>/welcome.php" method="get">
	<input type="hidden" id="welcome_event_id" 	name="event_id" value="<?php echo $_REQUEST["personalform_event_id"]?>" />
	<input type="hidden" id="publicSession" name="publicSession" value="<?php echo $publicSession?>" />
</form>

</body>
</html>