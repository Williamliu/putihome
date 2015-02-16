<?php
function call_perl( $perl_script ) {
	global $CFG;
	$query = "env REMOTE_ADDR=" . $_SERVER['REMOTE_ADDR'] . " /usr/bin/perl -x" . $CFG["perl_path"] . " " . $perl_script;
	error_log("\n[query]:" . $perl_script . "\n");
	$output = shell_exec($query);
	$output = substr($output, strpos($output,"<opt>") );
	return $output;
}
?>