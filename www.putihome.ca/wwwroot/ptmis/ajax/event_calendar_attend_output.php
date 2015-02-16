<?php 
session_start();
ini_set("display_errors", 0);
include_once("../../../include/config/config.php");
include_once($CFG["include_path"] . "/lib/database/database.php");

$response = array();
try {
	$type["event_id"] 	= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Event ID", 		"nullable":0}';
	$type["member_id"] 	= '{"type":"NUMBER", 	"length":11, 	"id": "group_id", 		"name":"Student ID", 	"nullable":0}';
	cTYPE::validate($type, $_REQUEST);
	cTYPE::check();

	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-disposition:  attachment; filename=puti_enrollment_" . $_REQUEST["member_id"] . ".xls");
	
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
					 <td ' . $width_two . '>' . $row["first_name"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Last Name: </td>
					 <td ' . $width_two . '>' . $row["last_name"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Dharma Name: </td>
					 <td ' . $width_two . '>' . $row["dharma_name"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Alias: </td>
					 <td ' . $width_two . '>' . $row["alias"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Gender: </td>
					 <td ' . $width_two . '>' . $row["gender"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Age Range: </td>
					 <td ' . $width_two . '>' . $ages[$row["age"]] .'</td>
				</tr>';
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
					 <td ' . $width_two . '>' . $row["contact_method"] .'</td>
				</tr>';
		$html.= '<tr>
                   	<td colspan="2"><br></td>
                 </tr>';
		$html.= '<tr>
                   	<td colspan="2" ' . $line_top. '><b>Address Information:</b></td>
                 </tr>';
		$html.= '<tr><td ' . $width_one . '>Address: </td>
					 <td ' . $width_two . '>' . $row["address"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>City: </td>
					 <td ' . $width_two . '>' . $row["city"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>State: </td>
					 <td ' . $width_two . '>' . $row["state"] .'</td>
				</tr>';
		$html.= '<tr><td ' . $width_one . '>Country: </td>
					 <td ' . $width_two . '>' . $row["country"] .'</td>
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

	$query_ans = "SELECT b.title, b.description FROM event_calendar a INNER JOIN puti_agreement b ON (a.agreement = b.id) WHERE a.id = '" . $_REQUEST["event_id"] . "'";
	$result_ans = $db->query( $query_ans );
	$row_ans	= $db->fetch( $result_ans);

	$html.= '<tr>';
	  $html.= '<td colspan="2" align="left">';
	  $html.= '<center><span style="font-size:14px; font-weight:bold">' . stripslashes($row_ans["title"]) . '</span></center><br>';
	  $html.= '<span style="font-size:12px;">';
	  $html.= stripslashes($row_ans["description"]);
	  $html.= '</span><br><br>';
	  $html.= '<center><b>Signature:_______________________</b>';
	  $html.= '<b>   Date:______________________</b></center><br>';
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
