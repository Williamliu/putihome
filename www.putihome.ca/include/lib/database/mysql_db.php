<?php
// oracle9i_db.php - main module for Oracle access functions
function sql_open() {
	global $mysql_link;
	global $CFG;
	$mysql_link = mysql_connect($CFG["mysql"]["host"], $CFG["mysql"]["user"], $CFG["mysql"]["pwd"]); 
	if($mysql_link) {
		mysql_select_db( $CFG["mysql"]["database"], $mysql_link );
	} else {
		echo "Error:" .  mysql_errno($mysql_link);
		return false;
	}	
	//echo "<br>sql open:" . $mysql_link . "<br>";
	return $mysql_link;
}

function sql_close() {
	global $mysql_link;
	if (isset($mysql_link)) mysql_close($mysql_link);
	//echo "<br>sql close:" . $mysql_link . "<br>";
}


function sql_query($query) {
	global $mysql_link;
	$result = mysql_query($query, $mysql_link);
	if(!$result) {
		echo "\nError: " .  mysql_errno($mysql_link) . " -- \nMessage: " . mysql_error($mysql_link) . " -- \nQuery: [[[" . $query . "]]]";
		return false; 
	}
	return $result;
}

function sql_exec($query, $close_connection = true) {
	global $mysql_link;
	sql_open();
	$result = mysql_query($query, $mysql_link);
	if(!$result) {
		echo "\nError: " .  mysql_errno($mysql_link) . " -- \nMessage: " . mysql_error($mysql_link) . " -- \nQuery: [[[" . $query . "]]]";
		if ($close_connection === true)
			sql_close();
		return false; 
	} else {
		sql_close();
		return $result;
	}
}

function fetch_row($result) {
	return mysql_fetch_assoc($result);
}

function row_count($result) {
	return mysql_num_rows($result);
}

function col_count($result) {
	return mysql_num_fields($result);
}

function fetch_fields( $result ) {
	$fields = array();
	$i = 0;
	while ( $i < col_count($result) ) {
		$finfo = mysql_fetch_field($result, $i);
		$field = array();
        $field["name"] =  $finfo->name;
        $field["table"] =  $finfo->table;
        $field["len"] =  $finfo->length;
        $field["flag"] =  $finfo->flags;
        $field["type"] =  $finfo->type;
		$fields[] = $field;
		$i++;	
	}
	return $fields;
}

function fetch_cols( $result ) {
	$fields = array();
	$i = 0;
	while ( $i < col_count($result) ) {
		$finfo = mysql_fetch_field($result, $i);
		$fields[] = $finfo->name;
		$i++;	
	}
	return $fields;
}


function is_exist($sql) {
	$result = sql_exec($sql);
	if( row_count($result) > 0 ) { 
		return true;
	} else { 
		return false;
	}
}


//Table  manipulation
function table_insert($table, $field_array) {
	global $mysql_link;
	$fields = "";
	$values = "";
	foreach($field_array as $key=>$val) {
		$fields .= ($fields==""?$key: ", " . $key); 
		$val = smart_quote($val);
		$values .= ($values==""?"'" . $val . "'" : ", '" . $val . "'"); 
	}
	$query = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
	//echo "\nquery:" . $query;
	sql_open();
	sql_query($query,false);
	$insert_id = mysql_insert_id($mysql_link);
	sql_close();
	return $insert_id;
}

function table_oinsert($table, $field_array) {
	global $mysql_link;
	$fields = "";
	$values = "";
	foreach($field_array as $key=>$val) {
		$fields .= ($fields==""?$key: ", " . $key); 
		$val = smart_quote($val);
		$values .= ($values==""?"'" . $val . "'" : ", '" . $val . "'"); 
	}
	$query = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
	//echo "\nquery:" . $query;
	sql_query($query);
	$insert_id = mysql_insert_id($mysql_link);

	return $insert_id;
}

function table_insert1($table, $field_array) {
	global $mysql_link;
	$fields = "";
	$values = "";
	foreach($field_array as $key=>$val) {
		$fields .= ($fields==""?$key: ", " . $key); 
		$val = smart_quote($val);
		$values .= ($values==""?"'" . $val . "'" : ", '" . $val . "'"); 
	}
	$query = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
	//echo "\nquery:" . $query;
	sql_query($query);
	$insert_id = $mysql_link->insert_id;

	return $insert_id;
}


function table_update($table, $id , $field_array) {
	$fields_update = "";
	foreach($field_array as $key=>$val) {
			$val = smart_quote($val);
			$fields_update .= ($fields_update==""?$key . " = '" . $val . "'" : ", " . $key . " = '" . $val . "'");
	}	
	$query = "UPDATE " . $table . " SET " . $fields_update . " WHERE id = '" . $id . "'";
	sql_exec($query);
}

function table_oupdate($table, $id_name, $id_value, $field_array) {
	$fields_update = "";
	foreach($field_array as $key=>$val) {
			$val = smart_quote($val);
			$fields_update .= ($fields_update==""?$key . " = '" . $val . "'" : ", " . $key . " = '" . $val . "'");
	}	
	$query = "UPDATE " . $table . " SET " . $fields_update . " WHERE $id_name = '" . $id_value . "'";
	//echo "\nquery:" . $query . "\n";
	sql_query($query);
}

function table_update1($table, $id , $field_array) {
	$fields_update = "";
	foreach($field_array as $key=>$val) {
			$val = smart_quote($val);
			$fields_update .= ($fields_update==""?$key . " = '" . $val . "'" : ", " . $key . " = '" . $val . "'");
	}	
	$query = "UPDATE " . $table . " SET " . $fields_update . " WHERE id = '" . $id . "'";
	sql_query($query);
}

function table_update2($table, $criteria , $field_array) {
	$fields_update = "";
	foreach($field_array as $key=>$val) {
			$val = smart_quote($val);
			$fields_update .= ($fields_update==""?$key . " = '" . $val . "'" : ", " . $key . " = '" . $val . "'");
	}	
	$query = "UPDATE " . $table . " SET " . $fields_update . " WHERE $criteria";
	sql_query($query);
}

function table_delete($table, $id) {
	$query = "DELETE FROM " . $table . " WHERE id = '" . $id . "'";
	sql_exec($query);
}

function table_odelete($table, $id) {
	$query = "DELETE FROM " . $table . " WHERE id = '" . $id . "'";
	sql_query($query);
}


/************************************************************************************/
function table_rows_update($table, $id_name, &$rows) {
	if(is_array($rows)) {
		foreach( $rows as $aRow) {
			$id_val 	= $aRow["recordID"];
			$cols 		= $aRow["cols"];
			$fields		= array();
			foreach($cols as $aCol) {
				if( $aCol["evalue"] !== $aCol["ovalue"] ) {
					$fields[$aCol["ename"]] = $aCol["evalue"];
				}
			}
			if(count($fields) > 0) {
				$fields["last_updated"] = time();
				table_oupdate($table, $id_name, $id_val, $fields);
			}
		}
	}
}

function table_rows_add($table, &$rows) {
	if(is_array($rows)) {
		foreach( $rows as $key=>$aRow) {
			$cols 		= $aRow["cols"];
			$fields		= array();
			foreach($cols as $aCol) {
				$fields[$aCol["ename"]] = $aCol["evalue"];
			}
			$fields["created_date"] = time();
			$fields["last_updated"] = time();
			$fields["status"] 		= 1;
			$fields["deleted"] 		= 0;
			$id_val = table_oinsert($table, $fields);
			$rows[$key]["recordID"] = $id_val;	
		}
	}
}

function table_rows_delete($table, $id_name, &$rows) {
	if(is_array($rows)) {
		foreach( $rows as $key=>$aRow) {
			$id_val 	= $aRow["recordID"];
			$fields		= array();
			$fields["deleted"] = 1;
			$fields["last_updated"] = time();
			$id_val = table_oupdate($table, $id_name, $id_val, $fields);
		}
	}
}
/********************************************************************************/
function nltobr( $str ) {
	return str_replace(array("\n", "\r", " "), array("<br>", "<br>", "&nbsp;"), $str);
}

function brtonl( $str ) {
	return str_replace(array("<br>", "<br>", "&nbsp;"), array("\n", "\r", " "),  $str);
}

/*************************************************************************/
// quote 
// Smart Quote
function smart_quote($val) {
	global $mysql_link;
	sql_open();
	$new_val = mysql_real_escape_string( $val, $mysql_link );
	sql_close();
	return $new_val; 
}

function smart_oquote($val) {
	global $mysql_link;
	$new_val = mysql_real_escape_string( $val, $mysql_link );
	return $new_val;
}


function sd_quote( $val ) {
 	return str_replace(array('"', "'" ), array('\"', "\'"), $val);
}

function no_dquote( $val ) {
 	return str_replace(array("\"", '\"' , '"'), array("'", "'", "'"), $val);
}

function un_quote($val) {
 	return str_replace(array("\'", '\"'), array("'", '"'), $val);
}

function dquote_input($val) {
 	//return str_replace('"', '\"', $val);
 	return str_replace(array("\'", '"'), array("'", "'"), $val);
}

function un_quoteHTML($val) {
 	return str_replace(array("\'", '\"', " ", "\n", "\r"), array("'", '"', "&nbsp;&nbsp;", "<br />" , "<br />"), $val);
}
?>
