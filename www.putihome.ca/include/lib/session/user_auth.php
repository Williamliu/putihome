<?php
function get_user_agent() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$ua['browser']  = '';
	$ua['version']  = 0;
	if (preg_match('/(firefox|opera|applewebkit)(?: \(|\/|[^\/]*\/| )v?([0-9.]*)/i', $user_agent, $m)) {
		$ua['browser']  = strtolower($m[1]);
		$ua['version']  = $m[2];
	}	else if (preg_match('/MSIE ?([0-9.]*)/i', $user_agent, $v) && !preg_match('/(bot|(?<!mytotal)search|seeker)/i', $user_agent)) {
		$ua['browser']  = 'ie';
		$ua['version']  = $v[1];
		if( preg_match('/Trident\/?([0-9.]*)/i', $user_agent, $v) ) {
			$ua['version']  = intval($v[1]) + 4;
		}
	}
	return $ua;
}

function mpe_user_login( $user, $pwd, $lang="" ) {
	global $CFG;
	$mpe_user = array();
	$data = '<request>
				 <platform>DTW</platform>
				 <product>PlayMPE Player</product>
				 <version>5.0.0.0</version>
				 <ipopen>'. $_SERVER['REMOTE_ADDR'] .'</ipopen>
				 <username><![CDATA[' . $user . ']]></username>
				 <password><![CDATA[' . $pwd . ']]></password>
				 <language>' . $lang . '</language>
				 <useragent><![CDATA[' . $_SERVER['HTTP_USER_AGENT'] . ']]></useragent>
				 <http_host><![CDATA[' . $_SERVER['HTTP_HOST'] . ']]></http_host>
				 <document_root><![CDATA[' .$_SERVER['DOCUMENT_ROOT'] . ']]></document_root>
				 <referrer><![CDATA[' . $_SERVER['SCRIPT_URI'] . ']]></referrer>
			</request>';
	
	$query = $CFG["php_path"] . "player5_login_common.php " . base64_encode($data) ;
	
	$xyz = exec_php($query);
	$res_arr = XMLparser(base64_decode($xyz));
	$xmlObj = new xmlObject($res_arr);
	
	$mpe_user["err_code"] 		= $xmlObj->nodeValue("response=>errorcode"); 
	$mpe_user["err_message"] 	= $xmlObj->nodeValue("response=>errordesc"); 
	$mpe_user["sID"]			= $xmlObj->nodeValue("response=>sessionid");
	$mpe_user["sPass"]			= $xmlObj->nodeValue("response=>sessionpass");

	if( 1 == 0 ) {
		echo "<br>Login Data:" . $data . "<br>";
		echo "Array <pre>"; print_r($res_arr); echo "</pre>";
		echo "Mpe User <pre>"; print_r($mpe_user); echo "</pre>";
	}
	return $mpe_user;
}

function mpe_user_logout($sid, $spass) {
	global $CFG;
	$mpe_user = array();
	$data = '<?xml version="1.0" encoding="utf-8" ?>
		<request>
		 <sessionid>' . $sid . '</sessionid>
		 <sessionpass>' . $spass . '</sessionpass>
		</request>';
	
	$query = $CFG["php_path"] . "player5_logoff_common.php " . base64_encode($data) ;
	$xyz = exec_php($query);
	$res_arr = XMLparser(base64_decode($xyz));
	$xmlObj = new xmlObject($res_arr);

	$mpe_user["errorCode"]	 	= $xmlObj->nodeValue("response=>errorcode");
	$mpe_user["errorMessage"] 	= $xmlObj->nodeValue("response=>errordesc");

	if( 1 == 0 ) {
		echo "<br>Logout Data:" . $data . "<br>";
		echo "Array <pre>"; print_r($res_arr); echo "</pre>";
		echo "Mpe User <pre>"; print_r($mpe_user); echo "</pre>";
	}
	return $mpe_user;
}

function get_mpe_user($sess_id , $sess_pass, $lang="") {
	global $CFG;
	$mpe_user = array();
	$data = "
		<request>
			<sessionid>" . $sess_id . "</sessionid>
			<sessionpass>" . $sess_pass . "</sessionpass>
			<useragent>" . $_SERVER['HTTP_USER_AGENT'] . "</useragent>
			<http_host><![CDATA[" . $_SERVER['HTTP_HOST'] . "]]></http_host>
			<document_root><![CDATA[" .$_SERVER['DOCUMENT_ROOT'] . "]]></document_root>
			<language>" . $lang . "</language>
			<ipopen>" . $_SERVER['REMOTE_ADDR'] . "</ipopen>
		</request>";
	
	
		$script = $CFG["php_path"] . "player5_getsessioninfo_common.php " . base64_encode( $data );
		$res_user = exec_php( $script );
		$res_oarr = XMLparser( base64_decode($res_user) );
		$userObj = new xmlObject( $res_oarr );
		$mpe_user["err_code"] 		= $userObj->nodeValue("response=>errorcode"); 
		$mpe_user["err_message"] 	= $userObj->nodeValue("response=>errordesc"); 
		$mpe_user["sess_id"] 		= $sess_id;
		
		$mpe_user["user_id"] 		= $userObj->nodeValue("response=>output=>userid");
		$mpe_user["username"] 		= $userObj->nodeValue("response=>output=>username");
		$mpe_user["video_type"] 	= $userObj->nodeValue("response=>output=>videotype");
		
		$mpe_user["default_tab"]	= $userObj->nodeValue("response=>output=>defaultpage");
		$mpe_user["country"]		= $userObj->nodeValue("response=>output=>country");
		
		// debug information for user info return.
		if( 1==0 ) { echo "auth<pre>";	print_r($res_oarr); echo "</pre>"; echo "<br>auth mpeuser<pre>";	print_r($mpe_user); echo "</pre>"; }
		
		return $mpe_user;
}
?>
