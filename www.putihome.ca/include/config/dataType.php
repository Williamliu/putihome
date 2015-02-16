<?php
$dataType = array(
"ANYTHING"		=> "(.*)", // all chars
//"CHAR"			=> "^[^<>]*$", // not allow to use "<>"  prevent from broke web page .  like "<html>" or any tags.
"CHAR"			=> "(.*)", // not allow to use "<>"  prevent from broke web page .  like "<html>" or any tags.
"EMAIL"			=> "^[a-z0-9]+([-_.]{0,1}[a-z0-9]+)*@[a-z0-9]+([-.]{0,1}[a-z0-9]+)*(\.)([a-z]{2,7})$", //Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca
"DATE"			=> "^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$",  //2007-10-31  or 97-12-31
"TIME"			=> "^([0-9]{1,2}(:[0-5]{1}[0-9]{1}(:[0-5]{1}[0-9]{1})?)?[ ]*(am|pm)?)$", // 2pm , 13:01:56AM , 13:25pm 
"DATETIME"		=> "^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2}[ ]+[0-9]{1,2}(:[0-5]{1}[0-9]{1}(:[0-5]{1}[0-9]{1})?)?[ ]*(am|pm)?)$", //1997-04-25 13pm    2007-4-25 10:00am
"NUMBER"		=> "^[-0-9.]*$",  // 3.1414, -34,  -3.143
"LETTER"			=> "^[a-z0-9]+$", //Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca
);

$default_length = array(	
"ANYTHING"		=> 16, 
"CHAR"			=> 16, 
"EMAIL"			=> 255, 
"DATE"			=> 10, 
"TIME"			=> 11, 
"DATETIME"		=> 22, 
"NUMBER"		=> 11,
"LETTER"		=> 6,
);

$err_char = array(
"ANYTHING"		=> "All charater was allowed.", // all chars
"CHAR"			=> "Not allow these characters : '<' , '>' ", // not allow to use "<>"  prevent from broke web page .  like "<html>" or any tags.
"EMAIL"			=> "Email format must be like xxxx@xxx.xxx , xxxx@xxx.xxx.xxx ", //Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$",  //2007-10-31  or 97-12-31
"TIME"			=> "Format as 2:00, 2pm, 9:01:56am, 13:25pm", // 2pm , 9:01:56AM , 13:25pm 
"DATE"			=> "Format as 1997-04-25, yyyy-mm-dd", //1997-04-25 13pm    2007-4-25 10:00am
"DATETIME"		=> "Format as 1997-04-25 13pm, 2007-4-25 10:00am", //1997-04-25 13pm    2007-4-25 10:00am
"NUMBER"		=> "Just number charater allowed.",  // 3.1414, -34,  -3.143
"LETTER"		=> "Letters and numbers only.",  // 3.1414, -34,  -3.143
);

function verify_fields($fields) {
	global $default_length, $dataType, $err_char;	
	if(0) {echo "<br>Array from Posted<pre>"; print_r($_REQUEST); echo "</pre>";}
	$return_info[0] = 0;
	$return_info[1] = "";
	$return_info[2] = "";
	
	foreach($fields as $key=>$val) {
		$field_name = $key;
		$tmp = explode("|", $val);
		$field_type = strpos($tmp[0],"(")?substr($tmp[0], 0 , strpos($tmp[0],"(")):$tmp[0];
		$field_size = strpos($tmp[0],"(")?substr($tmp[0], strpos($tmp[0],"(") + 1, strpos($tmp[0],")") - strpos($tmp[0],"(") -1):$default_length[$field_type];
		$field_element = $tmp[1];
		$field_name_ui = $tmp[2];
		$field_null = $tmp[3];
		
		$value = trim($_REQUEST[$field_name]);
		 
		if(strtoupper($field_null) == "NOT NULL") {
			if(strlen($value) == 0) {
				$return_info[0] = 1;
				$return_info[1] .= "The '" . $field_name_ui . "' field is required.\n";
				$return_info[2] = $return_info[2]==""?$field_element:$return_info[2];
			}
		}

		if( $field_size != 0 ) {
			if(strlen($value) > $field_size) {
					$return_info[0] = 1;
					$return_info[1] .= "Your input for the '" . $field_name_ui . "' field exceeds the maximum allowed length (" . $field_size . " chars).\n";
					$return_info[2] = $return_info[2]==""?$field_element:$return_info[2];
			}
		}

//		if(!eregi($dataType[$field_type], $value) && $value != "") {
		if(!preg_match("/" . $dataType[$field_type] . "/i", $value) && $value != "") {
				$return_info[0] = 1;
				$return_info[1] .= "Your input for the '" . $field_name_ui . "' field contains invalid characters or an invalid format.\n";
				$return_info[1] .= "   ( Reference: " . $err_char[$field_type] . " )\n";
				$return_info[2] = $return_info[2]==""?$field_element:$return_info[2];
		}
		//echo "field_name:" . $field_name . "   field_type:" . $field_type . "   field_size:" . $field_size . "   element:" . $field_element .  "   null:" . $field_null . "  ";
	}
	if($return_info[1] != "") $return_info[1] = "Sorry, we are unable to process your input for the following reasons:\n\n" . $return_info[1];
	return $return_info;
}


function verify_fields1($fields) {
	global $default_length, $dataType, $err_char;	
	$return_info[0] = 0;
	$return_info[1] = "";
	$return_info[2] = "";
	
	foreach($fields as $key=>$val) {
		$field_name = $val["name"];
		$field_element = $field_name;
		$field_type = $val["type"];
		$field_size = $val["len"];
		$field_name_ui = $val["msg"];
		$field_null = $val["nullable"];
		
		$value = $val["value"];
		 
		if( $field_null=="true" ) {
			if(strlen($value) == 0) {
				$return_info[0] = 1;
				$return_info[1] .= "The '" . $field_name_ui . "' field is required.\n";
				$return_info[2] = $return_info[2]==""?$field_element:$return_info[2];
			}
		}

		if(strlen($value) > $field_size) {
				$return_info[0] = 1;
				$return_info[1] .= "Your input for the '" . $field_name_ui . "' field exceeds the maximum allowed length (" . $field_size . " chars).\n";
				$return_info[2] = $return_info[2]==""?$field_element:$return_info[2];
		}
		
		if(!preg_match("/" . $dataType[$field_type] . "/i", $value) && $value != "") {
				$return_info[0] = 1;
				$return_info[1] .= "Your input for the '" . $field_name_ui . "' field contains invalid characters or an invalid format.\n";
				$return_info[1] .= "   ( Reference: " . $err_char[$field_type] . " )\n";
				$return_info[2] = $return_info[2]==""?$field_element:$return_info[2];
		}
		//echo "field_name:" . $field_name . "   field_type:" . $field_type . "   field_size:" . $field_size . "   element:" . $field_element .  "   null:" . $field_null . "  ";
	}
	if($return_info[1] != "") $return_info[1] = "Sorry, we are unable to process your input for the following reasons:\n\n" . $return_info[1];
	return $return_info;
}



function datetoint($date) {
	if(strpos($date, ":") != false) {
		$d_t = explode(" ", $date);
		$date = $d_t[0];
		$time = $d_t[1];
		$APM = $d_t[2];
	} else {
		$time = "";
	}
	if(strpos($date, "/") != false) {
		$sp = "/";
		$count = substr_count($date,"/");
	} elseif(strpos($date, "-") != false) {
		$sp = "-";
		$count = substr_count($date, "-");
	} else {
		return "";
	}
	$a = explode($sp, $date);
	$b = explode(":", $time);
	if(strtoupper($APM) == "PM") $b[0] += 12;
	//return  "Year:" . $a[0] . "  month:" . $a[1] . " day:" . $a[2] . " hour:" . $b[0] . " minutes:" . $b[1] ;
	switch (count($a)){
		case 0:
			return "";
			break;
		case 1:
			if(count($b) == 1) {return mktime($b[0],$b[1],0,0,0,$a[0]);}
			else {return mktime(0,0,0,0,0,$a[0]);}
			break;
		case 2:
			if(count($b) == 2) {return mktime($b[0],$b[1],0,$a[1],0,$a[0]);}
			else {return mktime(0,0,0,$a[1],0,$a[0]);}
			break;
		case 3:
			if(count($b) == 3) {return mktime($b[0],$b[1],0,$a[1],$a[2],$a[0]);}
			else {return mktime(0,0,0,$a[1],$a[2],$a[0]);}
			break;
		default:
			return "";
	}
}

function dateinrange($start_time, $end_time) {
	$ok = true;
	if($start_time != "" && $start_time !=0) {
		if( $start_time <= time() ) {
			$ok = true;
		} else {
			$ok = false;
		}
	}
	
	if($ok) {
		if($end_time != "" && $end_time !=0) {
			if( $end_time >= time() ) {
				$ok = true;
			} else {
				$ok = false;
			}
		}
	}
	return $ok;
}

function datediff($end_time, $start_time) {
	$time_dd = floor(($end_time - $start_time )/(3600 * 24));
	$time_hh = floor( fmod( $end_time - $start_time , 3600 * 24 ) / 3600 );
	$time_mm = ceil(fmod($end_time - $start_time, 3600) / 60);
	
    $left_dd =  $time_dd > 0? $time_dd	. ' days ':'';
	$left_hh =  $left_dd==""?( $time_hh > 0?$time_hh . ' hrs ':''):$left_dd . $time_hh . ' hrs ';
	$left_mm =  $left_hh==""?( $time_mm > 0?$time_mm  . ' mins':''):$left_hh . $time_mm  . ' mins';
	
	return $left_mm;
}

function inttodate($dint, $format="Y-m-d G:i:s T") {
	if($dint > 0) return date($format, $dint); else return "";
}

function inttodate1($dint, $format="Y-m-d") {
	if($dint > 0) return date($format, $dint); else return "";
}

function inttodate2($dint, $format="F j, Y") {
	if($dint > 0) return date($format, $dint); else return "";
}

function inttodate3($dint, $format="M jS, Y") {
	if($dint > 0) return date($format, $dint); else return "";
}

function inttodate4($dint, $format="Y-m-d H:i:s") {
	if($dint > 0) return date($format, $dint); else return "";
}

function get_url($url) {
	if($url == "") {
		return "javascript:void(0);";
	} else {
		if(preg_match("/https:\/\//i", $url) ) return $url;
		if(preg_match("/http:\/\//i", $url) ) return $url; else return "http://" . $url;
	}
}

function fsize( $b ) {
	$byte = 1024;
	$mega = 1024 * 1024;
	$giga = 1024 * 1024 * 1024;
	if( $b <= 0 ) return '';
	$val = ($b/$byte)>1? round($b/$byte, 2).'KB': $b ."Bytes"; 
	$val = ($b/$mega)>1? round($b/$mega, 2).'MB': round($b/$byte,2) ."KB"; 
	$val = ($b/$giga)>1? round($b/$giga, 2).'GB': round($b/$mega,2) ."MB";
	
	return $val;
}

$array_user_data = array();
function create_filter($table_name, $field_name , $field_value) {
	global $array_user_data;
	
	$criteria = "";
	if($field_value != "") {
		  if($field_name != "") {
			  $field_tmp = explode("|" , $field_name);
			  if(is_array($field_tmp)) {
				  switch($field_tmp[1]) {
					  case "=":
						  $criteria = " AND " . $field_tmp[0] . " = '" . $field_value . "'";
						  break;
					  case ">=":
						  $criteria = " AND " . $field_tmp[0] . " >= '" . $field_value . "'";
						  break;
					  case ">":
						  $criteria = " AND " . $field_tmp[0] . " > '" . $field_value . "'";
						  break;
					  case "<=":
						  $criteria = " AND " . $field_tmp[0] . " <= '" . $field_value . "'";
						  break;
					  case "<":
						  $criteria = " AND " . $field_tmp[0] . " < '" . $field_value . "'";
						  break;

					  case "day":
						  $criteria = " AND " . $field_tmp[0] . " >= " . time() . " - " . ( intval($field_value) * 3600 * 24 ) ;
						  break;

					   case "ck":
						  $criteria = " AND " . $field_tmp[0] . " LIKE '%[" . $field_value . "]%'";
						  break;

					  case "inck":
						  global $tabinfo;
						  $array_user_data[0] = $field_value;
						  $array_user_data[1] = "";
						  array_walk($tabinfo[$table_name][$field_tmp[0]], "el_walk", $array_user_data);
						  
						  /*
						  echo "user data:<pre>";
						  print_r($array_user_data);
						  echo "--</pre>";
						  */
						  
						  if($array_user_data[1] == "") { 
							  $criteria = " AND 1 = 0"; 
						  } else {
							  $tmp_arr = explode("," , $array_user_data[1]);
							  $tmp_str = "";
							  foreach($tmp_arr as $val) {
							  	$tmp_str .= ($tmp_str==""?"":" OR ") . $field_tmp[0] . " LIKE '%[" . $val . "]%'"; 
							  }
							  	$criteria = " AND (" . $tmp_str . ")"; 
						  }
						  break;


					  case "in":
						  global $tabinfo;
						  $array_user_data[0] = $field_value;
						  $array_user_data[1] = "";
						  array_walk($tabinfo[$table_name][$field_tmp[0]], "el_walk", $array_user_data);
						  if($array_user_data[1] == "") { 
							  $criteria = " AND 1 = 0"; 
						  } else {
							  $criteria = " AND " . $field_tmp[0] . " IN (" . $array_user_data[1] . ")"; 
						  }
						  break;



					  
					  case "date":
						  $criteria = " AND" . $field_tmp[0] . " BETWEEN '" . datetoint($field_value) . "' AND '" . (datetoint($field_value) + (24 * 3600 - 1)) . "'";
						  break;
					  default:
						  if($field_tmp[1] == "") {
							  $criteria = " AND " . $field_tmp[0] . " LIKE '%" . $field_value . "%'";
						  } else {
							  $criteria = " AND (" . $field_tmp[0] . " LIKE '%" . $field_value . "%' OR " . $field_tmp[1] . " LIKE '%" . $field_value . "%')";
				  		  }
						  break;
						  
				  }
			  } else {
					  $criteria = " AND " . $field_name . " LIKE '%" . $field_value . "%'";
			  }
		  }
	}
	return $criteria;
}

function el_walk($el, $key, $array_user_data) {
	global $array_user_data;
	//echo "\n" . "look:" . $array_user_data[0] . "   el:" . $el ; 
//	if( eregi($array_user_data[0], $el) ) {
	if( preg_match("/" . $array_user_data[0] . "/i", $el) ) {
		$array_user_data[1] .= ($array_user_data[1]==""?"":",") . $key;
	}
}

function build_criteria($field_array, $value_array) {
	$criteria = "";
	foreach($value_array as $key=>$value) {
		$value = trim($value);
		if($value != "") {
			$tmp = explode("|",$field_array[$key]);
			//$field = (strpos($tmp[0],".")===false?"`" . $tmp[0] . "`":$tmp[0]);
			$field = $tmp[0];

			switch($tmp[1]) {
				case "date": // date between day_begin and day_end
					$criteria .= ($criteria==""? $field . " BETWEEN '" . datetoint($value) . "' AND '" . (datetoint($value) + (24 * 3600 - 1)) . "'" : " AND " . $field . " BETWEEN '" . datetoint($value) . "' AND '" . (datetoint($value) + (24 * 3600 - 1)) . "'");
					break;
				case "equal": // field = value;
					$criteria .= ($criteria==""? $field . " = '" . $value . "'" : " AND " . $field . " = '" . $value . "'");
					break;
				case "equalor": // field = value1|value2|value3   (field = value1 OR field = value2 OR field =value3) 
					if($value != "") {
						$tmp = explode("|", $value);
						$criteria_tmp = "";
						foreach($tmp as $val) {
							$criteria_tmp .= ($criteria_tmp==""? $field . " = '" . $val . "'":" OR " . $field . " = '" . $val . "'");
						}
						$criteria_tmp = ($criteria_tmp==""?"":"(" . $criteria_tmp . ")");
						$criteria .= ($criteria==""?$criteria_tmp:" AND " . $criteria_tmp);
					}
					break;
				case "likeand": // field like value1|value2|value3   (field LIKE "%value1%" AND field LIKE "%value2%" AND field LIKE "%value3%")
					if($value != "") {
						$tmp = explode("|", $value);
						$criteria_tmp = "";
						foreach($tmp as $val) {
							$criteria_tmp .= ($criteria_tmp==""? $field . " LIKE '%" . $val . "%'":" AND " . $field . " LIKE '%" . $val . "%'");
						}
						$criteria_tmp = ($criteria_tmp==""?"":"(" . $criteria_tmp . ")");
						$criteria .= ($criteria==""?$criteria_tmp:" AND " . $criteria_tmp);
					}
					break;
				case "likeor": // field like value1|value2|value3   (field LIKE "%value1%" OR field LIKE "%value2%" OR field LIKE "%value3%")
					if($value != "") {
						$tmp = explode("|", $value);
						$criteria_tmp = "";
						foreach($tmp as $val) {
							$criteria_tmp .= ($criteria_tmp==""?$field . " LIKE '%" . $val . "%'":" OR " . $field . " LIKE '%" . $val . "%'");
						}
						$criteria_tmp = ($criteria_tmp==""?"":"(" . $criteria_tmp . ")");
						$criteria .= ($criteria==""?$criteria_tmp:" AND " . $criteria_tmp);
					}
					break;

				case "or": //   (field1 LIKE "%value1%" OR field2 LIKE "%value2%" OR field3 LIKE "%value3%")
					$criteria .= ($criteria==""? $field . " LIKE '%" . $value . "%'" : " OR " . $field . " LIKE '%" . $value . "%'");
					break;

				default:
					$criteria .= ($criteria==""? $field . " LIKE '%" . $value . "%'" : " AND " . $field . " LIKE '%" . $value . "%'");
					break;
			} 
		}
	}
	return $criteria;
}

function ctr_to_br($content) {
	$search = array("\n", "\r", "  " , chr(10), chr(13));
	$replace = array("<br>", "", " &nbsp;", "<br>" , "");
	return str_replace($search, $replace, $content);
}

function br_to_ctr($content) {
	$search = array("<br>","<br>", "&nbsp;");
	$replace = array("\n", "\r", " ");
	return str_replace($search, $replace, $content);
}

function tomoney($num) {
	if( $num > 0 ) {
		return "US $" . number_format($num, 2, ".", ",");
	} else {
		return "";
	}
}

function viewed_add($user_id, $release_id, $action, $artist, $title ) {
	sql_open();
	$result = sql_query("SELECT id FROM recent_viewed_release WHERE Recipient_ID = '" . $user_id . "' AND Releases_ID = '" . $release_id . "' AND Action = '" . $action ."'");
	$fields = array();
	if( row_count($result) > 0 ) {
		$row = fetch_row( $result ) ;
		$fields["CreatedTime"] = time();
		table_update1("recent_viewed_release", $row["id"], $fields);
	} else {
		$fields["Recipient_ID"] = $user_id;
		$fields["Releases_ID"] = $release_id;
		$fields["artist"] = smart_quote($artist);
		$fields["title"] = smart_quote($title);
		$fields["Action"] = smart_quote($action);
		$fields["CreatedTime"] = time();
		table_insert1("recent_viewed_release", $fields);
	}
	sql_close();
}

function trim_time( $time ) {
	$new_time = '';
	if( strpos( $time , ":" ) )  {
		if(   intval( substr( $time, 0, strpos($time,":")  )  ) >= 1 ) {
			$new_time = $time;
		} else {
			$new_time = substr( $time, strpos( $time,":")+1 );
		}
	}
	return $new_time;
}

function image_right( $name, $right , $padding ) {
	$html ='';
	if( $right == "" || $right == 0 || $right == "No") {
		$html = '';
	}
	
	if( $right >= 1 || $right == "Yes" ) {
		
		$html = '<span style="color:#333333;">' . $name . '</span>' . ( $right>=1?'(' . $right . ')':'') . $padding;
	}
	return $html;
}

function drm_right( $right , $css) {
	$html ='';
	if( $right == "" || $right == 0 || $right == "No") {
		$html = $css;
	}
	
	if( $right >= 1 || $right == "Yes" ) {
		$html = '';
	}
	return $html;
}

function htmldecode( $str ) {
	return  urldecode( htmlspecialchars_decode( $str ) );
}
?>
