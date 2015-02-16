<?php
function getLang( $lang = "" ) {
	if( $lang == "" && $_REQUEST["lng"] != "" ) $lang = $_REQUEST["lng"];
	$result_lang = sql_exec("SELECT id, lang_code, main FROM mpe_language WHERE deleted <> 1 AND lang_code = '" . smart_quote(strtolower($lang)) . "'");

	$result_main = sql_exec("SELECT id, lang_code, main FROM mpe_language WHERE deleted <> 1 AND main = 1");
	$row_main = fetch_row($result_main);	
	$lang_main = $row_main["lang_code"];
	
	if( row_count($result_lang) > 0 ) {
		$row_lang = fetch_row($result_lang);	
		$lang_code = $row_lang["lang_code"];

	} else {
		$lang_code = $row_main["lang_code"];
	}
	
	$result_words = sql_exec("SELECT keyword, $lang_code, $lang_main as lang_default FROM mpe_language_word WHERE deleted <> 1 AND status = 1");
	$array_words = array();
	while($row_words = fetch_row($result_words) ) {
		$word_key = strtolower($row_words["keyword"]);
		$word_value = $row_words[$lang_code]==''?'<font color="red">' . $row_words["lang_default"] . '</font>':$row_words[$lang_code];
		$array_words[$word_key] = $word_value; 
	}
	return $array_words;
}

function listLang($sel="") {
	if( $sel == "" && $_REQUEST["lng"] != "" ) $sel = $_REQUEST["lng"];
	$result_lang = sql_exec("SELECT id, lang_code, lang_desc, main FROM mpe_language WHERE deleted <> 1 AND status = 1 ORDER BY main DESC, lang_code ASC");
	$lang_select = '<select title="Language" id="language_list" name="language_list" onchange="window.location.href=\'' . $_SERVER["SCRIPT_URI"] . '?lng=\' + this.value;">';
	while($row_lang = fetch_row($result_lang) ) {
		$lang_select .= '<option value="' . $row_lang["lang_code"] . '" ' . ($row_lang["lang_code"]==strtolower($sel)?'selected':'') . '>' . $row_lang["lang_desc"] . '</option>';
	}
	$lang_select .= '</select>';
	return $lang_select;
}
?>