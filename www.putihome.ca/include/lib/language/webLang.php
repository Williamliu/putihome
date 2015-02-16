<?php
function getLang() {
	global $CFG;
	global $Glang;
	$pnum 	= func_num_args();
	$params	= func_get_args();
	$Glang = $params[0];
	if( $Glang == "" ) $Glang = "en";
	
	$db_lang = new cMYSQL($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"], $CFG["mysql"]["database"]);
	
	$result_words = $db_lang->query("SELECT keyword, $Glang, en as def_lang FROM website_language_word WHERE deleted <> 1");
	$array_words = array();
	while($row_words = $db_lang->fetch($result_words) ) {
		$word_key 	= $row_words["keyword"];
		$word_value = $row_words[$Glang]==''?cTYPE::gstr($row_words["def_lang"]):cTYPE::gstr($row_words[$Glang]);
		$word_value = str_replace(array("\n","\r"), array("",""), $word_value);
		$array_words[$word_key] = $word_value; 
	}
	return $array_words;
}
?>