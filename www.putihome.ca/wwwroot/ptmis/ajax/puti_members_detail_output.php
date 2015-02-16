<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");
include_once("website_admin_ajax_auth.php");

$response = array();
try {
	$type["member_id"] = '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Group ID", 		"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-disposition:  attachment; filename=puti_member_" . $_REQUEST["member_id"] . ".xls");
	
	$db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);

	$result_age = $db->query("SELECT * FROM puti_members_age order by id");
	$ages =array();
	while($row_age = $db->fetch($result_age)) {
		$ages[$row_age["id"]] = $row_age["title"];
	}

	$query = "SELECT * FROM puti_members WHERE deleted <> 1 AND id = '" . $_REQUEST["member_id"] . "'";
	$result = $db->query( $query );
	$row = $db->fetch($result);

	$width_one 	= 'width="20%" style="font-style:italic; font-size:12px; width:20%;"';
	$width_two 	= 'width="80%" style="text-align:left; width:80%;"';
	$width_colspan = 'width="100%" style="width:400px;"';
	
	$line_top		= 'style="border-top:1px dotted #666666;"';
	
	$html = '<table border="1" cellpadding="2" style="font-size:14px;">';
	$html.= '<tr>
				<td colspan="2" style="font-size:14px; font-weight:bold; height:50px;" valign="middle" align="center"><b>Bodhi Meditation Student Registration Form</b></td>
			 </tr>';

	$html.= '<tr>';
	$html.= '<td valign="top">';

		$html.= '<table border="0" cellpadding="2" style="font-size:14px;">';
		$html.= '<tr>
                   	<td colspan="2"><b>Personal Information:</b></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>First Name: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["first_name"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Last Name: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["last_name"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Dharma Name: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["dharma_name"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Alias: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["alias"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Gender: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["gender"]) .'</td>
				</tr>';

		$html.= '<tr><td ' . $width_one . '>Age Range: </td>
					 <td ' . $width_two . '>' .  cTYPE::gstr($ages[$row["age"]])  .'</td>
				</tr>';
		/*
		$html.= '<tr><td ' . $width_one . '>Age Range: </td>
					 <td ' . $width_two . '>' . ($row["birth_date"]>0?date("M j,Y",$row["birth_date"]):"") .'</td>
				</tr>';
		*/

		$html.= '<tr>
                   	<td colspan="2"><br></td>
                 </tr>';
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. '><b>Contact Information:</b></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>Email: </td>
					 <td ' . $width_two . '>' . $row["email"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Phone: </td>
					 <td ' . $width_two . '>' . $row["phone"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Cell: </td>
					 <td ' . $width_two . '>' . $row["cell"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Contact By: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["contact_method"]) .'</td>
				</tr>';
		$html.= '<tr>
                   	<td colspan="2"><br></td>
                 </tr>';
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. '><b>Address Information:</b></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>Address: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["address"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>City: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["city"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>State: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["state"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Country: </td>
					 <td ' . $width_two . '>' . cTYPE::gstr($row["country"]) .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Postal: </td>
					 <td ' . $width_two . '>' . $row["postal"] .'</td>
				</tr>';
		$html.= '</table>';
	
	$html.= '</td>';
	$html.= '<td valign="top" ' . $width_colspan . '>';
		$html.= '<table border="0" cellpadding="2" style="font-size:14px; width:100%;" width="100%">';

		$query_ans = "SELECT answer1, answer2, answer3 FROM puti_answers WHERE deleted <> 1 AND status = 1 AND question_id = '1' AND member_id = '" . $row["id"] . "'";
		$result_ans = $db->query( $query_ans );
		$row_ans	= $db->fetch( $result_ans);
		
		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '><b>Emergency contact name and relationship:</b></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>Contact Name: </td>
					 <td ' . $width_two . '>' . $row_ans["answer1"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Contact Phone: </td>
					 <td ' . $width_two . '>' . $row_ans["answer2"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Relationship: </td>
					 <td ' . $width_two . '>' . $row_ans["answer3"] .'</td>
				</tr>';

		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '><br></td>
                 </tr>';
		$query_ans = "SELECT answer1, answer2, answer3 FROM puti_answers WHERE deleted <> 1 AND status = 1 AND question_id = '2' AND member_id = '" . $row["id"] . "'";
		$result_ans = $db->query( $query_ans );
		$row_ans	= $db->fetch( $result_ans);
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. ' ' . $width_colspan . '><b>How did you hear about us?</b></td>
                 </tr>';
		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '>' . $row_ans["answer1"] . '</td>
                 </tr>';

		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '><br></td>
                 </tr>';
		$query_ans = "SELECT answer1, answer2, answer3 FROM puti_answers WHERE deleted <> 1 AND status = 1 AND question_id = '3' AND member_id = '" . $row["id"] . "'";
		$result_ans = $db->query( $query_ans );
		$row_ans	= $db->fetch( $result_ans);
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. ' ' . $width_colspan . '><b>Are you currently receiving therapy of some kind? ' . $row_ans["answer1"] . '</b></td>
                 </tr>';

		$query_ans = "SELECT answer1, answer2, answer3 FROM puti_answers WHERE deleted <> 1 AND status = 1 AND question_id = '4' AND member_id = '" . $row["id"] . "'";
		$result_ans = $db->query( $query_ans );
		$row_ans	= $db->fetch( $result_ans);

		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '><b>If yes, please provide details:</b></td>
				</tr>';
		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '>' . $row_ans["answer1"] . '</td>
                 </tr>';

		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '><br></td>
                 </tr>';
		$query_ans = "SELECT answer1, answer2, answer3 FROM puti_answers WHERE deleted <> 1 AND status = 1 AND question_id = '5' AND member_id = '" . $row["id"] . "'";
		$result_ans = $db->query( $query_ans );
		$row_ans	= $db->fetch( $result_ans);
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. ' ' . $width_colspan . '><b>Please write down any other medical concerns or history:</b></td>
                 </tr>';
		$html.= '<tr>
                   	<td colspan="2" ' . $width_colspan . '>' . $row_ans["answer1"] . '</td>
                 </tr>';

		$html.= '</table>';
	
	$html.= '</td>';
	$html.= '</tr>';

	$query_ans = "SELECT answer1, answer2, answer3 FROM puti_answers WHERE deleted <> 1 AND status = 1 AND question_id = '6' AND member_id = '" . $row["id"] . "'";
	$result_ans = $db->query( $query_ans );
	$row_ans	= $db->fetch( $result_ans);

	$html.= '<tr>';
	  $html.= '<td colspan="2" align="left">';
	  $html.= '<center><span style="font-size:14px; font-weight:bold">Individual and Risk Release</span></center><br>';
	  $html.= '<span style="font-size:12px;">';
	  $html.= 'I assume all risks of damage and injuries that may occur to me while participating 
	  in the Bodhi Meditation course and while on the premises at which the classes are held. 
	  I am aware that some courses may involve yoga, mindful stretching and mental exercises. 
	  I hereby release and discharge the Canada Bodhi Dharma Society and its agents and 
	  representatives from all claims or injuries resulting from my participation in the program.<br>
	  <br>
	  I hereby grant permission to the Canada Bodhi Dharma Society, Including its successors and 
	  assignees to record and use my name, image and voice, for use in its promotional and informational productions. 
	  I further grant the Canada Bodhi Dharma Society permission to edit and modify these recordings in the making 
	  of productions as long as no third party\'s rights are infringeed by their use. Lastly, 
	  I release any and all legal claims against the Canada Bodhi Dharma Association for using, 
	  distributing or broadcasting any productions.<br>
	  <br>
	  I have read, understood, and I guarantee that all the information I have provide above is true and correct to the best 
	  of my knowledge. I agree to the above release.';
	  $html.= '</span><br><br>';
	  $html.= '<center><b>' . ($row_ans["answer1"]?$row_ans["answer1"]:'<br>') . '</b></center>';
	  $html.= '<center><b>' . ($row_ans["answer2"]?$row_ans["answer2"]:'<br>') . '</b></center><br>';
	  $html.= '</td>';
	$html.= '</tr>';
	$html.= '</table>';
	echo $html;
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
