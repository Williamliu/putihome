<?php 
session_start();
ini_set("display_errors", 1);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once($CFG["include_path"] . "/lib/html/html.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages = $db->rows($result_age);

    $result_langs = $db->query("SELECT * FROM puti_info_language WHERE status = 1 AND deleted <> 1 order by sn DESC");
	$rows_langs = $db->rows($result_langs);

	$query = "SELECT * 
				FROM puti_members 
				LEFT JOIN puti_members_others b ON ( puti_members.id = b.member_id ) 				
				WHERE deleted <> 1 AND id = '" . $_REQUEST["member_id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);

	$width_one 	= 'height="25" style="with:40px;font-size:16px;white-space:nowrap;"';
	$width_two 	= 'align="left" height="25" width="100%"';
	$width_colspan = 'width="270"';
	
	$line_top = 'style="border-top:1px dotted #666666;"';

	
	$html = '<br><center>';
	$html .= $photo_html;
	$html .= '<table border="1" cellpadding="0" cellspacing="0" style="font-size:16px;" width="650">';
	$html.= '<tr>
				<td colspan="2" style="font-size:20px; font-weight:bold; height:50px;" valign="middle" align="center"><b>' . $words["puti student form"] . '</span></td>
			 </tr>';

	$html.= '<tr>';
	$html.= '<td valign="top" width="50%">';

		$html.= '<table border="0" cellpadding="1" style="font-size:16px;" width="100%">';
		$html.= '<tr>
                   	<td colspan="2"><span style="font-size:14px;font-weight:bold;">' . $words["personal information"] . '</span></td>
                 </tr>';
		if( $admin_user["lang"] != "en" ) {
			// non english version
			$html.= '<tr><td ' . $width_one . '>' . $words["last name"] . '*</td>
						 <td ' . $width_two . '>' . cTYPE::gstr($row["last_name"]) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . '>' . $words["first name"] . '*</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["first_name"]) .'</td>
					</tr>';

			$html.= '<tr><td ' . $width_one . '>' . $words["legal last"] . '</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["legal_last"]) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . '>' . $words["legal first"] . '</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["legal_first"]) .'</td>
					</tr>';
	
			$html.= '<tr><td ' . $width_one . '>' . $words["dharma name"] . '</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["dharma_name"]) . ($row["dharma_pinyin"]!=""?" ":"") . $row["dharma_pinyin"] . '</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . '>' . $words["alias"] . ' </td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["alias"]) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . '>' . $words["identify number"] . '</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["identify_no"]) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . '>' . $words["gender"] . ' *</td>
						 <td ' . $width_two . '>
								  <input type="radio" id="gender_male" name="gender" value="Male" '. ($row["gender"]=="Male"?"checked":"") .' />'. $words["male"] .'  
								  <input type="radio" id="gender_female" name="gender" style="margin-left:10px;" value="Female" '. ($row["gender"]=="Female"?"checked":"") .' />' . $words["female"] . '
						 </td>
					</tr>';
			$html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["age range"]) . '*</td>
						 <td ' . $width_two . '>' . cHTML::radio('age_range',$ages, $row["age"], 3) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["birth date"]) . '</td>
						 <td ' . $width_two . '>'. cTYPE::toDate($row["birth_yy"],$row["birth_mm"],$row["birth_dd"]) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["religion"]) . '</td>
						 <td ' . $width_two . '>' .  $db->getTitle($admin_user["lang"], "vw_vol_religion",$row["religion"]) .'</td>
					</tr>';
		} else {
			// english version		
			$html.= '<tr><td ' . $width_one . '>' . $words["first name"] . '*</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["first_name"]) .'</td>
					</tr>';
			$html.= '<tr><td ' . $width_one . '>' . $words["last name"] . '*</td>
						 <td ' . $width_two . '>' . cTYPE::gstr($row["last_name"]) .'</td>
					</tr>';
	
			$html.= '<tr><td ' . $width_one . '>' . $words["dharma name"] . '</td>
						 <td ' . $width_two . '>' .  cTYPE::gstr($row["dharma_name"]) .'</td>
					</tr>';

			$html.= '<tr><td ' . $width_one . '>' . $words["gender"] . '*</td>
						 <td ' . $width_two . '>
								  <input type="radio" id="gender_male" name="gender" value="Male" '. ($row["gender"]=="Male"?"checked":"") .' />'. $words["male"] .'  
								  <input type="radio" id="gender_female" name="gender" style="margin-left:10px;" value="Female" '. ($row["gender"]=="Female"?"checked":"") .' />' . $words["female"] . '
						 </td>
					</tr>';
			$html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["age range"]) . '*</td>
						 <td ' . $width_two . '>' . cHTML::radio('age_range',$ages, $row["age"], 3) .'</td>
					</tr>';

			$html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["birth date"]) . '</td>
						 <td ' . $width_two . '>'. cTYPE::toDate($row["birth_yy"],$row["birth_mm"],$row["birth_dd"]) .'</td>
					</tr>';

			$html.= '<tr><td ' . $width_one . ' valign="top">' . cTYPE::gstr($words["religion"]) . '</td>
						 <td ' . $width_two . '>' .  $db->getTitle($admin_user["lang"], "vw_vol_religion",$row["religion"]) .'</td>
					</tr>';
		}

        // Language Ability
        $query 	= "SELECT * FROM puti_members_lang WHERE member_id = '" . $_REQUEST["member_id"] . "'";
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
								                iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "member_lang", $row["language"],4 )
                                        . '</td>
                                    </tr>
					                <tr>
 	                  	                <td ' . $width_one . ' valign="top">' . $words["language ability"] . '</td>
                    	                <td ' . $width_two . ' align="left">' .
								                iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "languages", $db->attrs($result_lang, "language_id"),4 )
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
								                iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "member_lang", $row["language"],3)
                                        . '</td>
                                    </tr>
					                <tr>
 	                  	                <td ' . $width_one . ' valign="top">' . $words["language ability"] . '</td>
                    	                <td ' . $width_two . ' align="left">' .
								                iHTML::checkbox($admin_user["lang"], $db, "vw_vol_language", "languages", $db->attrs($result_lang, "language_id") ,3)
                                        . '</td>
                                </tr>
                            </table>
                            </td>
                        </tr>';
        }        

		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. '><span style="font-size:14px;font-weight:bold;">' . $words["address information"] . '</span></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["address"] . ' </td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["address"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["city"] . ' *</td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["city"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["state"] . ' </td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["state"]) .'</td>
				</tr>';
		/*
		$html.= '<tr><td ' . $width_one . '>' . $words["country"] . ' </td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["country"]) .'</td>
				</tr>';
		*/
		$html.= '<tr><td ' . $width_one . '>' . $words["postal code"] . '</td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["postal"]) .'</td>
				</tr>';
		$html.= '</table>';
	
	$html.= '</td>';
	$html.= '<td valign="top" ' . $width_colspan . '  width="50%">';
		$html.= '<table border="0" cellpadding="1" style="font-size:16px;" width="100%">';

		$html.= '<tr>
                   	<td colspan="2"><span style="font-size:14px;font-weight:bold;">' . $words["member.select_option"] . '</span></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["member.degree"] . '</td>
					 <td ' . $width_two . '>' .  $db->getTitle($admin_user["lang"], "vw_vol_degree",$row["degree"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["past_position"] . '</td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["past_position"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["current_position"] . '</td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["current_position"]) .'</td>
				</tr>';


		$html.= '<tr>
                   	<td colspan="2"' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["contact information"] . '</span></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["email"] . '*</td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["email"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["phone"] . ' *</td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["phone"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>' . $words["cell"] . ' </td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($row["cell"]) .'</td>
				</tr>';

				$contact_array = array();
				$contact_array[0]["id"] 	= "Phone";
				$contact_array[0]["title"] 	= "Phone";
				$contact_array[1]["id"] 	= "Email";
				$contact_array[1]["title"] 	= "Email";


		$html.= '<tr><td ' . $width_one . '>' . $words["contact by"] . '</td>
					 <td ' . $width_two . '>'.
						cHTML::checkbox("contact_method", $contact_array, 10, explode(",",$row["contact_method"]) )
					 .
					'</td>
				</tr>';


		$query_ans = "SELECT * FROM puti_members_others WHERE member_id = '" . $_REQUEST["member_id"] . "'";
		$result_ans = $db->query( $query_ans );
		$row_ans	= $db->fetch( $result_ans);
		
		$html.= '<tr>
                   	<td colspan="2"' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["emergency contact name and relationship"] . '</span></td>
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
            /*
		$html.= '<tr>
                   	<td colspan="2"><br></td>
                 </tr>';
        */
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["how did you hear about us?"] . '</span>*</td>
                 </tr>';
		
		$result_hearfrom = $db->query("SELECT * FROM puti_info_hearfrom Order BY id");
		$rows_hearfrom = $db->rows($result_hearfrom);
		
		$result_h111 = $db->query("SELECT hearfrom_id FROM puti_members_hearfrom WHERE member_id = '" . $_REQUEST["member_id"] . "'"); 
		$hear_array = array();
		while($row_h111 = $db->fetch($result_h111) ) {
			$hear_array[] = $row_h111["hearfrom_id"]; 
		}
		
		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . ' valign="top">' .
						( $admin_user["lang"]=="en"?
						cHTML::checkbox('hear_about',$rows_hearfrom, 4 , $hear_array):
						cHTML::checkbox('hear_about',$rows_hearfrom, 5 , $hear_array) ) . 
					'</td>
                 </tr>';

        /*
		$html.= '<tr>
                   	<td colspan="2"><br></td>
                 </tr>';
        */

		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. ' ' . $width_colspan . '><span style="font-size:14px;font-weight:bold;">' . $words["ailment & symptom"] . '</span></td>
                 </tr>';
		
		$result_symptom = $db->query("SELECT * FROM puti_info_symptom Order BY id");
		$rows_symptom = $db->rows($result_symptom);
		
		$result_h111 = $db->query("SELECT symptom_id FROM puti_members_symptom WHERE member_id = '" . $_REQUEST["member_id"] . "'"); 
		$symptom_array = array();
		while($row_h111 = $db->fetch($result_h111) ) {
			$symptom_array[] = $row_h111["symptom_id"]; 
		}
		
		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . ' valign="top">' .
						($admin_user["lang"]=="en"?cHTML::checkbox('symptom',$rows_symptom, 3 , $symptom_array):cHTML::checkbox('symptom',$rows_symptom, 4 , $symptom_array))
					. 
					'<br><span>' . $words["specify"] . ': <input type="text" id="other_symptom" name="other_sympton" style="width:200px; border:0px; border-bottom:1px solid black;" value="' . cTYPE::gstr($row_ans["other_symptom"]) . '" /></span>
					</td>
                 </tr>';
	$html.= '</table>';
	
	$html.= '</td>';
	$html.= '</tr>';
    
    /*
	$html.= '<tr>
				<td colspan="2" valign="top" style="border-top:0px; border-bottom:0px;">
                    <center><span style="font-weight:bold;">' . cTYPE::gstr($words["email subscription"]) . '</span></center>
                </td>
			 </tr>';
    */
	$html.= '<tr>
				<td colspan="2"  style="border-top:0px; border-bottom:0px;">
                    <span style="font-weight:bold;">' . cTYPE::gstr($words["email subscription"]) . ':</span>
                    <span style="font-size:14px;">' . cTYPE::gstr($words["email subscription agreement"]) . '</span><br>
                    <center>
                	    <input type="checkbox" id="irefuse" ' . ($row["email_flag"]==0?"":"") . ' 	name="email_flag" value="0" /><label for="irefuse"><b>' . $words["i dont agree"] . '</b></label>
                        <input type="checkbox" id="iagree" ' . ($row["email_flag"]==1?"checked":"") . '	name="email_flag" value="1" style="margin-left:50px;" /><b>' . $words["i agree"] . '</b></label>
              	    </center><br>
                </td>
			 </tr>';

	$html.= '<tr>
				<td colspan="2"  style="border-top:0px; border-bottom:0px;"><b>' . $words["please write down any other medical concerns or history"] . ':</span></td>
			 </tr>';
	$html.= '<tr>
				<td colspan="2" valign="top" style="border-top:0px; border-bottom:0px;">' . $row_ans["medical_concern"] . '&nbsp;</td>
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
			$ccaa = array();
			$ccaa["event_id"] 	= $_REQUEST["event_id"];
			$ccaa["member_id"] 	= $_REQUEST["member_id"];
			$group = $db->getVal("event_calendar_enroll", "group_no", $ccaa);
			$group = $group?$group:"";
			
			$names						= array();
			$names["first_name"] 		= $row["first_name"];
			$names["last_name"] 		= $row["last_name"];
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
	
	//header("Content-Type: application/vnd.ms-excel; name='excel'");
	//header("Content-disposition:  attachment; filename=puti_enrollment_" . $_REQUEST["member_id"] . ".xls");
	//echo $html;
	
	echo json_encode($response);
	exit();	
	/*
	//header("Content-Type: application/pdf; name='pdf'; charset=utf-8");
	//header("Content-disposition:  attachment; filename=event_member_signature.pdf");

	$pdf = new HTML2FPDF();
	//$pdf->DisplayPreferences('HideWindowUI');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->WriteHTML($html);
	$pdf->Output();
	*/
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
