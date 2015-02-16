<?php
if( $_REQUEST["publicSession"] != "" ) $_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] = $_REQUEST["publicSession"];

if( $_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] == "" ) {
	$_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] = -1;
} else {
	$sess_db = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	$sess_id = $_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"];
	
	$query = "SELECT id FROM puti_members WHERE deleted <> 1 AND status =1 AND sess_id = '" . $sess_id . "' AND sess_exp > '" . time() . "'";
	if( $sess_db->exists($query) ) {
		$query = "UPDATE puti_members SET sess_exp = '" . (time() + 3600 * 2) . "' WHERE deleted <> 1 AND status = 1 AND sess_id = '" . $sess_id . "'";
		$sess_db->query( $query );
		$sess_pass = true;
	} else {
		$sess_pass = false;
		$_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"] = -1;
	}
}

if( $sess_pass ) {
	$next_url = $CFG["http"] . $CFG["web_domain"] . '/' .$logform . '?personalform_event_id=' . $_REQUEST["loginform_event_id"] . '&publicSession=' . $_SESSION[$_SERVER['HTTP_HOST'] . ".pubSessID"];
	header("Location: " . $next_url);
}
?>
