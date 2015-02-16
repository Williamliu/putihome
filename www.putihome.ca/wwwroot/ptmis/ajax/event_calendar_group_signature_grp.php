<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include($CFG["web_path"] . "/source/php_pdf/html2fpdf.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages = $db->rows($result_age);

    $result_langs = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
	$rows_langs = $db->rows($result_langs);


	$order_str = "ORDER BY a.group_no, a.leader DESC, a.volunteer DESC,  b.first_name, b.last_name";
	if($_REQUEST["orderBY"] != "" &&  $_REQUEST["orderSQ"] != "") {	
		$orderBY = $_REQUEST["orderBY"]=="aname"?"first_name":$_REQUEST["orderBY"];
		$orderBY = $orderBY=="created_time"?"a.created_time":$orderBY;
		$orderSQ = $_REQUEST["orderSQ"];
		$order_str 	= " ORDER BY $orderBY $orderSQ";
	} 

	$crrr = "";
	$grp_id = trim($_REQUEST["group_id"]);
	if( $grp_id != "" ) {
		$crrr .= ($crrr==""?"":" AND ") . "a.group_no = '" . $grp_id . "'"; 
	}

	$enr_id = trim($_REQUEST["enroll_id"]);
	if( $enr_id != "" ) {
		$crrr .= ($crrr==""?"":" AND ") . "a.id = '" . $enr_id . "'"; 
	}

	/*******************************************/
	$sch_name = trim($_REQUEST["sch_name"]);
	if($sch_name != "") {
		$crrr .= ($crrr==""?"":" AND ") . 
						"( 	first_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							last_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							legal_first like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							legal_last like '%" .	cTYPE::trans_trim($sch_name) . "%' OR 
							dharma_name like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR 
							alias like '%" . cTYPE::trans_trim($sch_name) . "%' OR
							concat(first_name, last_name) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(last_name,  first_name) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(legal_first, legal_last) like '%" . 	cTYPE::trans_trim($sch_name) . "%' OR
							concat(legal_last, legal_first) like '%" . 	cTYPE::trans_trim($sch_name) . "%'
						)";
	}

	$sch_phone = trim($_REQUEST["sch_phone"]);
	if($sch_phone != "") {
		$crrr .= ($crrr==""?"":" AND ") . "( replace(replace(replace(phone,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' OR replace(replace(replace(cell,' ',''),'-',''),'.','') like '%" . str_replace(array(" ","-","."), array("","",""), $sch_phone) . "%' )";
	}

	$sch_email = trim($_REQUEST["sch_email"]);
	if($sch_email != "") {
		$crrr .= ($crrr==""?"":" AND ") . "email like '%" . $sch_email . "%'";
	}

	$sch_gender = trim($_REQUEST["sch_gender"]);
	if($sch_gender != "") {
		$crrr .= ($crrr==""?"":" AND ") . "gender = '" . $sch_gender . "'";
	}

	$sch_online = trim($_REQUEST["sch_online"]);
	if($sch_online != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.online = '" . $sch_online . "'";
	}

	$sch_new_flag = trim($_REQUEST["sch_new_flag"]);
	if($sch_new_flag != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.new_flag = '" . $sch_new_flag . "'";
	}

	$sch_attend = trim($_REQUEST["sch_attend"]);
	if($sch_attend != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.attend >= '" . ($sch_attend/100) . "'";
	}

	$sch_level = trim($con["sch_level"]);
	if($sch_level != "") {
		$crrr .= ($criteria==""?"":" AND ") . "b.level = '" . $sch_level . "'";
	}

	$sch_onsite = trim($_REQUEST["sch_onsite"]);
	if($sch_onsite != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.onsite = '" . $sch_onsite . "'";
	}

	$sch_trial = trim($_REQUEST["sch_trial"]);
	if($sch_trial != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.trial = '" . $sch_trial . "'";
	}

	$sch_lang = trim($_REQUEST["sch_lang"]);
	if($sch_lang != "") {
		$crrr .= ($crrr==""?"":" AND ") . "b.language = '" . $sch_lang . "'";
	}

	$sch_group = trim($_REQUEST["sch_group"]);
	if($sch_group != "") {
		$crrr .= ($crrr==""?"":" AND ") . "a.group_no = '" . cTYPE::trans($sch_group) . "'";
	}

	$sch_date = trim($_REQUEST["sch_date"]);
	if($sch_date != "") {
		$sd = cTYPE::datetoint($sch_date);
		$ed = $sd + ( 3600 * 24 - 1);
		$crrr .= ($crrr==""?"":" AND ") . "a.created_time BETWEEN '" . $sd . "' AND '" . $ed . "'";
	}

	$sch_city = trim($_REQUEST["sch_city"]);
	if($sch_city != "") {
		$crrr .= ($crrr==""?"":" AND ") . "city like '%" . cTYPE::trans($sch_city) . "%'";
	}


	$sch_idd = trim($_REQUEST["sch_idd"]);
	if( $sch_idd != "" ) {
		$mem_id = $db->getVal("puti_idd", "member_id", array("idd"=>$sch_idd) );
		if( $mem_id != "" ) {
				$crrr .= ($crrr==""?"":" AND ") . "b.id = '" . $mem_id . "'";
		} else {
				$crrr .= ($crrr==""?"":" AND ") . "b.id = '-1'";
		}
	}
	/*****************************************************************/
	$crrr = ($crrr==""?"":" AND ") . $crrr; 


	$query_idd = "SELECT aaa0.member_id, aaa0.idd  
					FROM puti_idd aaa0 
					INNER JOIN (SELECT member_id, max(idd) as idd FROM puti_idd GROUP BY member_id) aaa1 
					ON (aaa0.member_id = aaa1.member_id AND aaa0.idd = aaa1.idd)";

	$query_base = "SELECT a.id as enroll_id, a.leader, a.volunteer, a.confirm,  a.group_no,
                         b.id as member_id, b.*,
					     c.title, c.start_date, c.end_date 
						FROM event_calendar_enroll a 
						INNER JOIN puti_members b ON (a.member_id = b.id)  
						LEFT JOIN ($query_idd) aa0 ON (b.id = aa0.member_id)
						INNER JOIN event_calendar c ON (a.event_id = c.id) 
						WHERE  a.deleted <> 1 AND 
						b.deleted <> 1 AND 
						c.deleted <> 1 AND 
						a.event_id = '" . $_REQUEST["event_id"] . "' 
						$crrr  
						$order_str";
			
	$result_base = $db->query($query_base);
	$html = '';
	$first = true;
	while( $row_base = $db->fetch($result_base) ) {

/*********************  single student form ************************************/

	        $width_one 	= 'height="25" style="with:40px;font-size:16px;white-space:nowrap;"';
	        $width_two 	= 'align="left" height="25" width="100%"';
	        $width_colspan = 'width="270"';
			  
			$line_top = 'style="border-top:1px dotted #666666;"';
		  	
            if( $first ) {
				$first = false;
				$html .= '<div style="display:block;height:30px;"></div>';
			} else {
				$html .= '<div style="display:block;height:30px;page-break-before:always;"></div>';
			}
			  
			$html .= '<center>';
			$html .= $photo_html; 
			$html .= '<table border="1" cellpadding="0" cellspacing="0" style="font-size:16px; position:aboslute;" width="650">';
			  $html.= '<tr>
						  <td colspan="2" style="font-size:20px; font-weight:bold; height:50px;" valign="middle" align="center"><b>'
						   . $words["puti student form"] . ($_REQUEST["sch_group"]>0?' - <span style="font-size:16px;">' .$words["group"].': '.$_REQUEST["sch_group"].'</span>':'') . 
						'</span></td>
					   </tr>';
		  
			  $html.= '<tr>';

              //// Table left hand side 
			  $html.= '<td valign="top" width="50%">';
				  $html.= '<table border="0" cellpadding="1" style="font-size:16px;" width="100%">';
				  $html.= '<tr>
							  <td colspan="2"><span style="font-size:14px;font-weight:bold;">' . $words["personal information"] . '</span></td>
						   </tr>';
                  
                    /////////////////////////////// Personal Information
		            if( $admin_user["lang"] != "en" ) {
			            // non english version
			            $html.= '<tr><td ' . $width_one . '>' . $words["last name"] . '*</td>
						             <td ' . $width_two . '>' . cTYPE::gstr($row_base["last_name"]) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . '>' . $words["first name"] . '*</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["first_name"]) .'</td>
					            </tr>';

			            $html.= '<tr><td ' . $width_one . '>' . $words["legal last"] . '</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["legal_last"]) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . '>' . $words["legal first"] . '</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["legal_first"]) .'</td>
					            </tr>';
	
			            $html.= '<tr><td ' . $width_one . '>' . $words["dharma name"] . '</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["dharma_name"]) . ($row_base["dharma_pinyin"]!=""?" ":"") . $row_base["dharma_pinyin"] . '</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . '>' . $words["alias"] . ' </td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["alias"]) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . '>' . $words["identify number"] . '</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["identify_no"]) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . '>' . $words["gender"] . ' *</td>
						             <td ' . $width_two . '>
								              <input type="radio" id="gender_male" name="gender_' .  $row_base["member_id"] . '" value="Male" '. ($row_base["gender"]=="Male"?"checked":"") .' />'. $words["male"] .'  
								              <input type="radio" id="gender_female" name="gender' .  $row_base["member_id"] . '" style="margin-left:10px;" value="Female" '. ($row_base["gender"]=="Female"?"checked":"") .' />' . $words["female"] . '
						             </td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["age range"]) . '*</td>
						             <td ' . $width_two . '>' . cHTML::radio('age_range_' . $row_base["member_id"], $ages, $row_base["age"], 3) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["birth date"]) . '</td>
						             <td ' . $width_two . '>'. cTYPE::toDate($row_base["birth_yy"],$row_base["birth_mm"],$row_base["birth_dd"]) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["religion"]) . '</td>
						             <td ' . $width_two . '>' .  $db->getTitle($admin_user["lang"], "vw_vol_religion", $row_base["religion"]) .'</td>
					            </tr>';
		            } else {
			            // english version		
			            $html.= '<tr><td ' . $width_one . '>' . $words["first name"] . '*</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["first_name"]) .'</td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . '>' . $words["last name"] . '*</td>
						             <td ' . $width_two . '>' . cTYPE::gstr($row_base["last_name"]) .'</td>
					            </tr>';
	
			            $html.= '<tr><td ' . $width_one . '>' . $words["dharma name"] . '</td>
						             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["dharma_name"]) .'</td>
					            </tr>';

			            $html.= '<tr><td ' . $width_one . '>' . $words["gender"] . '*</td>
						             <td ' . $width_two . '>
								              <input type="radio" id="gender_male" name="gender_' . $row_base["member_id"] . '" value="Male" '. ($row_base["gender"]=="Male"?"checked":"") .' />'. $words["male"] .'  
								              <input type="radio" id="gender_female" name="gender_' . $row_base["member_id"] . '" style="margin-left:10px;" value="Female" '. ($row_base["gender"]=="Female"?"checked":"") .' />' . $words["female"] . '
						             </td>
					            </tr>';
			            $html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["age range"]) . '*</td>
						             <td ' . $width_two . '>' . cHTML::radio('age_range_' . $row_base["member_id"],  $ages, $row_base["age"], 3) .'</td>
					            </tr>';

			            $html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["birth date"]) . '</td>
						             <td ' . $width_two . '>'. cTYPE::toDate($row_base["birth_yy"],$row_base["birth_mm"],$row_base["birth_dd"]) .'</td>
					            </tr>';

			            $html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["religion"]) . '</td>
						             <td ' . $width_two . '>' .  $db->getTitle($admin_user["lang"], "vw_vol_religion",$row_base["religion"]) .'</td>
					            </tr>';
		            }
                    /// End of Personal Information


                    // Language Ability
                    $query 	= "SELECT * FROM puti_members_lang WHERE member_id = '" . $row_base["member_id"] . "'";
                    $result_lang = $db->query($query);

 		            if( $admin_user["lang"] != "en" ) {
		                $html.= '<tr>
                   	                <td colspan="2" ' . $line_top. '><span style="font-size:14px;font-weight:bold;">' . $words["language ability"] . '</span></td>
                                    </tr>
				                    <tr>
                                        <td colspan="2">
                                        <table> 
                                            <tr>
                  	                            <td  ' . $width_one . ' valign="top">' . $words["preferred language"] . '*</td>
                    	                            <td ' . $width_two . ' align="left">' .
								                            iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "member_lang_" . $row_base["member_id"] , $row_base["language"],4 )
                                                    . '</td>
                                                </tr>
					                            <tr>
 	                  	                            <td ' . $width_one . ' valign="top">' . $words["language ability"] . '</td>
                    	                            <td ' . $width_two . ' align="left">' .
								                            iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "languages_" . $row_base["member_id"], $db->attrs($result_lang, "language_id"),4 )
                                                    . '</td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>';
                    } else {
		                $html.= '<tr>
                   	                <td colspan="2" ' . $line_top. '><span style="font-size:14px;font-weight:bold;">' . $words["language ability"] . '</span></td>
                                    </tr>
				                    <tr>
                                        <td colspan="2">
                                        <table> 
                                            <tr>
                  	                            <td  ' . $width_one . ' valign="top">' . $words["preferred language"] . '*</td>
                    	                            <td ' . $width_two . ' align="left">' .
								                            iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "member_lang_" . $row_base["member_id"], $row_base["language"],3)
                                                    . '</td>
                                                </tr>
					                            <tr>
 	                  	                            <td ' . $width_one . ' valign="top">' . $words["language ability"] . '</td>
                    	                            <td ' . $width_two . ' align="left">' .
								                            iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "languages_" . $row_base["member_id"], $db->attrs($result_lang, "language_id") ,3)
                                                    . '</td>
                                            </tr>
                                        </table>
                                        </td>
                                    </tr>';
                    }        
		            /// end of language ability
		            
				// address information  
                  $html.= '<tr>
							  <td colspan="2" ' . $line_top. '><span style="font-size:14px;font-weight:bold;">' . $words["address information"] . '</span></td>
						   </tr>';
				  $html.= '<tr><td ' . $width_one . '>' . $words["address"] . '</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_base["address"]) .'</td>
						  </tr>';
				  $html.= '<tr><td ' . $width_one . '>' . $words["city"] . '*</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_base["city"]) .'</td>
						  </tr>';
				  $html.= '<tr><td ' . $width_one . '>' . $words["state"] . '</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_base["state"]) .'</td>
						  </tr>';
				  /*
				  $html.= '<tr><td ' . $width_one . '>' . $words["country"] . ' </td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row["country"]) .'</td>
						  </tr>';
				  */
				  $html.= '<tr><td ' . $width_one . '>' . $words["postal code"] . '</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_base["postal"]) .'</td>
						  </tr>';
				  $html.= '</table>';
			     // address information
			  $html.= '</td>';
              //// End of  Table left hand side 


              //////  Table right hand side 
			  $html.= '<td valign="top" ' . $width_colspan . '  width="50%">';
				  $html.= '<table border="0" cellpadding="1" style="font-size:16px;" width="100%">';

		            $html.= '<tr>
                   	            <td colspan="2"><span style="font-size:14px;font-weight:bold;">' . $words["member.select_option"] . '</span></td>
                             </tr>';
		            $html.= '<tr><td ' . $width_one . '>' . $words["member.degree"] . '</td>
					             <td ' . $width_two . '>' .  $db->getTitle($admin_user["lang"], "vw_vol_degree",$row_base["degree"]) .'</td>
				            </tr>';
		            $html.= '<tr><td ' . $width_one . '>' . $words["past_position"] . '</td>
					             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["past_position"]) .'</td>
				            </tr>';
		            $html.= '<tr><td ' . $width_one . '>' . $words["current_position"] . '</td>
					             <td ' . $width_two . '>' .  cTYPE::gstr($row_base["current_position"]) .'</td>
				            </tr>';


					$html.= '<tr>
                               	<td colspan="2"' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["contact information"] . '</span></td>
							 </tr>';
					$html.= '<tr><td ' . $width_one . '>' . $words["email"] . '*</td>
								 <td ' . $width_two . '>' .  cTYPE::gstr($row_base["email"]) .'</td>
							</tr>';
					$html.= '<tr><td ' . $width_one . '>' . $words["phone"] . ' *</td>
								 <td ' . $width_two . '>' .  cTYPE::gstr($row_base["phone"]) .'</td>
							</tr>';
					$html.= '<tr><td ' . $width_one . '>' . $words["cell"] . ' </td>
								 <td ' . $width_two . '>' .  cTYPE::gstr($row_base["cell"]) .'</td>
							</tr>';
			
							$contact_array = array();
							$contact_array[0]["id"] 		= "Phone";
							$contact_array[0]["title"]  	= "Phone";
							$contact_array[1]["id"] 		= "Email";
							$contact_array[1]["title"] 	    = "Email";
			
			
					$html.= '<tr><td ' . $width_one . '>' . $words["contact by"] . '</td>
								 <td ' . $width_two . '>'.
									cHTML::checkbox(("contact_method".$row_base["member_id"]), $contact_array, 10, explode(",",$row_base["contact_method"]) )
								 .
								'</td>
							</tr>';
		  
				  $query_ans = "SELECT * FROM puti_members_others WHERE member_id = '" . $row_base["member_id"] . "'";
				  $result_ans = $db->query( $query_ans );
				  $row_ans	= $db->fetch( $result_ans);
				  
				  $html.= '<tr>
							  <td colspan="2" ' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["emergency contact name and relationship"] . '</span></td>
						   </tr>';
				  $html.= '<tr><td ' . $width_one . '>' . $words["contact name"] . '*</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_ans["emergency_name"]) .'</td>
						  </tr>';
				  $html.= '<tr><td ' . $width_one . '>' . $words["contact phone"] . ' *</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_ans["emergency_phone"]) .'</td>
						  </tr>';
				  $html.= '<tr><td ' . $width_one . '>' . $words["relationship"] . ' *</td>
							   <td ' . $width_two . '>' .  cTYPE::gstr($row_ans["emergency_ship"]) .'</td>
						  </tr>';

		  
				  $html.= '<tr>
							  <td colspan="2" ' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["how did you hear about us?"] . '</span> *</td>
						   </tr>';
				  
				  $result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
				  $rows_hearfrom = $db->rows($result_hearfrom);
				  
				  $result_h111 = $db->query("SELECT hearfrom_id FROM puti_members_hearfrom WHERE member_id = '" . $row_base["member_id"] . "'"); 
				  $hear_array = array();
				  while($row_h111 = $db->fetch($result_h111) ) {
					  $hear_array[] = $row_h111["hearfrom_id"]; 
				  }
				  
				  $html.= '<tr>
							  <td colspan="2" ' . $width_colspan . ' valign="top">' .
								  ( $admin_user["lang"]=="en"?
								  cHTML::checkbox( ('hear_about'.$row_base["member_id"]) ,$rows_hearfrom, 4 , $hear_array):
								  cHTML::checkbox( ('hear_about'.$row_base["member_id"]) ,$rows_hearfrom, 5 , $hear_array) ) . 
							  '</td>
						   </tr>';
		  
		  
				  $html.= '<tr>
							  <td colspan="2" ' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["ailment & symptom"] . '</span></td>
						   </tr>';
				  
				  $result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
				  $rows_symptom = $db->rows($result_symptom);
				  
				  $result_h111 = $db->query("SELECT symptom_id FROM puti_members_symptom WHERE member_id = '" . $row_base["member_id"] . "'"); 
				  $symptom_array = array();
				  while($row_h111 = $db->fetch($result_h111) ) {
					  $symptom_array[] = $row_h111["symptom_id"]; 
				  }
				  
				  $html.= '<tr>
							  <td colspan="2" ' . $width_colspan . ' valign="top">' .
									($admin_user["lang"]=="en"?cHTML::checkbox( ('symptom'.$row_base["member_id"]) ,$rows_symptom, 3 , $symptom_array):cHTML::checkbox( ('symptom'.$row_base["member_id"]) ,$rows_symptom, 4 , $symptom_array))
							  . 
							  '<br><span>' . $words["specify"] . ': <input type="text" id="other_symptom" name="other_sympton" style="width:200px; border:0px; border-bottom:1px solid black;" value="' . cTYPE::gstr($row_ans["other_symptom"]) . '" /></span>
							  </td>
						   </tr>';
		  
				  $html.= '</table>';
			  $html.= '</td>';
              //// End of  Table right hand side 
              $html.= '</tr>';


	
			  $html.= '<tr>
						  <td colspan="2"  style="border-top:0px; border-bottom:0px;">
							  <span style="font-weight:bold;">' . cTYPE::gstr($words["email subscription"]) . ' : </span>
							  <span style="font-size:14px;">' . cTYPE::gstr($words["email subscription agreement"]) . '</span><br>
							  <center>
								  <input type="checkbox" id="irefuse" ' . ($row_base["email_flag"]?"":"") . ' 	name="email_flag' . $row_base["member_id"] . '" value="0" /><b>' . $words["i dont agree"] . '</b>
								  <input type="checkbox" id="iagree" ' . ($row_base["email_flag"]==1?"checked":"") . '	name="email_flag' . $row_base["member_id"] . '" value="1" style="margin-left:50px;" /><b>' . $words["i agree"] . '</b>
							  </center><br>
						  </td>
					   </tr>';


		  

			  $html.= '<tr>
						  <td colspan="2"  style="border-top:0px; border-bottom:0px;"><b>' . $words["please write down any other medical concerns or history"] . ':</span></td>
					   </tr>';
			  $html.= '<tr>
						  <td colspan="2" valign="top"  style="border-top:0px; border-bottom:0px;">' . $row_ans["medical_concern"] . '&nbsp;</td>
					   </tr>';
		

			  $html.= '<tr>';
				$html.= '<td colspan="2" align="left"  style="border-top:0px;">';
					$html.= '<br><br>';
					$html.= '<center><b>' . $words["signature"] . ':_______________________</b>';
					$html.= '<b>   ' . $words["date"] . ':______________________</b></center><br>';
				 $html.= '</td>';
			  $html.= '</tr>';
			$html.= '</table></center>';

    $html .= '<div style="display:block;height:30px;page-break-before:always;"></div>';

	$html .= '<center>';
	$html .= $photo_html;
	$html .= '<table cellpadding="0" cellspacing="0" style="font-size:16px; border:1px dotted #333333;" width="650">';
	$query_evt = "SELECT a.title as etitle, a.start_date, a.end_date, b.id, b.title, b.description FROM event_calendar a INNER JOIN (SELECT * FROM puti_agreement_lang WHERE lang = '" . $admin_user["lang"] . "') b ON (a.agreement = b.agreement_id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
	$result_evt = $db->query( $query_evt );
	$row_evt	= $db->fetch( $result_evt);
	
	
	if( $row_evt["id"]>0 ) {
		    $etitle = cTYPE::gstr($row_evt["etitle"]);
		    $edate  = $row_evt["start_date"]>0?date("Y-m-d",$row_evt["start_date"]):'';
		    $edate .= $row_evt["end_date"]>0 && $row_evt["end_date"]!=$row_evt["start_date"]?" ~ " . date("Y-m-d",$row_evt["end_date"]):'';
			$group = $row_base["group_no"];
			$group = $group?$group:"";
			
			$names						= array();
			$names["first_name"] 		= $row_base["first_name"];
			$names["last_name"] 		= $row_base["last_name"];
			$lfname = cTYPE::gstr(cTYPE::lfname($names));

			
		  $html.= '<tr>';
			$html.= '<td colspan="2" align="left" style="padding:5px;">';
				$html.= '<span style="font-size:16px;">' . cTYPE::gstr($words["form.event"]) . " : " . $etitle . '</span>';
				$html.= '<span style="font-size:16px; float:right">' . cTYPE::gstr($words["group"]) . " : " . ($group!=""?$group:'<span style="display:inline-block;width:100px;"></span>') . '</span>';
				$html.= '<br>';
				$html.= '<span style="font-size:16px;">' . cTYPE::gstr($words["date"]) . " : " . $edate . '</span>';
				$html.= '<span style="font-size:16px; float:right">' . cTYPE::gstr($words["name"]) . " : " . ($lfname!=""?$lfname:'<span style="display:inline-block;width:100px;"></span>') . '</span>';
				$html.= '<br><br>';
				$html.= '<center><span style="font-size:16px; font-weight:bold">' . cTYPE::gstr($row_evt["title"]) . '</span></center>';
				$html.= '<div style="font-size:14px; text-align:justify; text-justify:inter-ideograph;">';
				$html.= cTYPE::gstr($row_evt["description"]);
				$html.= '</div><br><br>';
				$html.= '<center><b>' . $words["signature"] . ':_______________________</b>';
				$html.= '<b>   ' . $words["date"] . ':______________________</b></center><br>';
			 $html.= '</td>';
		  $html.= '</tr>';
	}
	$html.= '</table></center>';
	
	$response["data"] = $html;
/*********************  single student form ************************************/				
	}
	
	$response["data"] = $html;
	echo json_encode($response);
	exit();	

} catch(cERR $e) {
	echo "<pre>";
	print_r($e->detail());
	echo "</pre>";	
} catch(Exception $e ) {
	$response["errorCode"] 		= $e->code();
	$response["errorMessage"] 	= $e->getMessage();
	$response["errorLine"] 		= sprintf("File[file:%s, line:%s]", $e->getFile(), $e->getLine());
	$response["errorField"]		= "";
	echo "<pre>";
	print_r($response);
	echo "</pre>";	
}


?>
