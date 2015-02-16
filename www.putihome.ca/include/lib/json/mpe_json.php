<?php
function jEncode( $arr ) {
	// require to convert HTML character , use  htmlentities() or htmlspecialchars( str , ENT_QUOTES | ENT_NOQUOTES | ENT_COMPAT);
	/*
	ENT_COMPAT   
	'&' (ampersand) becomes '&amp;'  
	'<' (less than) becomes '&lt;' 
	'>' (greater than) becomes '&gt;' 
	'"' (double quote) becomes '&quot;'

	ENT_NOQUOTES:
	'"" (double quote) still  "
	"'" (single quote) still  '

	ENT_QUOTES:
	'"' (double quote) becomes '&quot;'
	"'" (single quote) becomes '&#039;'
	*/	
	
	// json_encode convert  " to \" ; ' is still ' ;   \  to \\;  /  to \/ 
	return json_encode( $arr );
}

function jRow( $aRow ) {
	return json_encode( $aRow );
}

function jTable ( $aResult ) {
	$data = '[';
	while( $aRow = fetch_row($aResult) ) {
		$data .= ($data=='['?'':',') . jRow($aRow);
	}
	$data .= ']';
	return $data;
}

function jData ( $arr ) {
	$html ='{';
	foreach( $arr as $key=>$val ) {
		$html .= ($html=='{'?'':',') . '"' . $key . '":' . $val;
	}
	return $html;
}
?>