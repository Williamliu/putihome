<?php
include_once($CFG["include_path"] . "/lib/utf8/trans.php");
$tran = new transClass();
/***********************************************************************************************/
/*																							   */
/***********************************************************************************************/
interface iSQL {
	public function open();
	public function select_db($db);	
	public function close();

	public function query($query);
	public function fetch($rs);			
	public function rows($rs);
	public function cols($rs);


	public function row_nums($rs);
	public function col_nums($rs);
	public function cols_info($rs);
	
	public function exists($sql);
	
	public function insert();
	public function update();
	public function delete();
	public function detach();
	
	public function quote($val);
}

/***********************************************************************************************/
/*																							   */
/***********************************************************************************************/
class cMYSQL implements iSQL {
	public 	$error 	= null;

	private $link 	= null;
	
	public function __construct() {
		// initialize error object;
		$this->error = new cERR();

		//call connection open if parameters exists
		$pnum 	= func_num_args();
		$params	= func_get_args();
		switch($pnum) {
			case 3:
				$this->__open($params[0], $params[1], $params[2]);
				break;
			case 4:
				$this->__open($params[0], $params[1], $params[2]);
				$this->select_db($params[3]);
				break;
			default:
				break;
		}
	}
	
	public function __destruct() {
		$this->close();
	}
	
	public function open() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		switch($pnum) {
			case 3:
				$this->__open($params[0], $params[1], $params[2]);
				break;
			case 4:
				$this->__open($params[0], $params[1], $params[2]);
				$this->select_db($params[3]);
				break;
			default:
				break;
		}
	}
	
	public function select_db($db) {
		if($this->link) {
			$db_selected = mysql_select_db($db, $this->link);
			if (!$db_selected) {
				  $this->error->set(3003, mysql_error());
				  throw $this->error;
			}
		} else {
			$this->error->set(3001, "connection is not available before select database");
			throw $this->error;
		}
	}

	public function close() {
		if ($this->link) {
			mysql_close($this->link);
			$this->link = null;
		}
	}
	
	public function query($query) {
		if($this->link) {
			$rs = mysql_query($query, $this->link);
			if(!$rs) {
				$err_msg = "NO:[" . mysql_errno($this->link) . "] Message:[" . mysql_error($this->link) . "] Query:[" . $query . "]";
				$this->error->set(3002, $err_msg);
				throw $this->error; 
			}
			return $rs;
		} else {
			  $this->error->set(3001, "connection is not available before excute query");
			  throw $this->error;
		}
	}
	
	public function row_nums($rs) {
		if($rs) {
			return mysql_num_rows($rs);
		} else {
			$this->error->set(3004, "rowset is not available for row_nums");
			throw $this->error;
		}
	}
	
	public function col_nums($rs) {
		if($rs) {
			return mysql_num_fields($rs);
		} else {
			$this->error->set(3004, "rowset is not available for col_nums");
			throw $this->error;
		}
	}

	public function cols_info($rs) {
		if($rs) {
			$fields = array();
			$i = 0;
			while ( $i < $this->col_nums($rs) ) {
				$finfo 			= mysql_fetch_field($rs, $i);
				$field 			= array();
				$field["name"] 	=  $finfo->name;
				$field["table"] =  $finfo->table;
				$field["length"]=  $finfo->length;
				$field["flag"] 	=  $finfo->flags;
				$field["type"] 	=  $finfo->type;
				$fields[] = $field;
				$i++;	
			}
			return $fields;
		} else {
			$this->error->set(3004, "rowset is not available for cols_info");
			throw $this->error;
		}
	}
	

		
	public function fetch($rs) {
		if($rs) {
			$row = mysql_fetch_assoc($rs);
			return $row;
		} else {
			$this->error->set(3004, "rowset is not available for fetch");
			throw $this->error;
		}
	}

    
	public function rows($rs) {
		if($rs) {
			$rows 	= array();
			$cnt 	= 0;
			$fields	= $this->cols($rs);		
			while( $row = $this->fetch($rs) ) {
				foreach( $fields as $field ) {
					$rows[$cnt][$field] =  $row[$field];
				}
				$cnt++;
			}
			mysql_data_seek($rs,0);
			return $rows;
		} else {
			$this->error->set(3004, "rowset is not available for rows");
			throw $this->error;
		}
	}
	

	public function attrs($rs, $field) {
		$field_name 	= $field;
		if($rs) {
		    $rows 	= array();
            if($field_name!="") {
			    $cnt 	= 0;
			    while( $row = $this->fetch($rs) ) {
				    $rows[$cnt] =  $row[$field_name];
				    $cnt++;
			    }
            } else {
			    $cnt 	= 0;
			    $fields	= $this->cols($rs);		
			    while( $row = $this->fetch($rs) ) {
				    foreach( $fields as $field ) {
					    $rows[$cnt][$field] =  $row[$field];
				    }
				    $cnt++;
			    }
            }
			mysql_data_seek($rs,0);
			return $rows;
		} else {
			$this->error->set(3004, "rowset is not available for rows");
			throw $this->error;
		}
	}
	
	public function astr() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
   		switch($pnum) {
			case 1:
                $arr = $params[0];
                $sp  = ",";
                break;
            case 2:
                $arr = $params[0];
                $sp  = $params[1];
                break;
            case 3:
                $rs = $params[0];
                $ff = $params[1];
                $sp  = $params[2];
                $arr = $this->attrs($rs, $ff);
                break;
        }

		$arr_str = "";
		foreach($arr as $val) {
			$arr_str .= ($arr_str==""?"":$sp) . $val;
		}
		return $arr_str;
	}

	public function astrs() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
   		switch($pnum) {
            case 2:
                $all_arr = $params[0];
                $key_arr = $params[1];
                $sp  = ", ";
                break;
            case 3:
                $all_arr = $params[0];
                $key_arr = $params[1];
                $sp  = $params[2];
                break;
        }

		$arr_str = "";
		foreach($all_arr as $key=>$val) {
			if(in_array($key, $key_arr)) $arr_str .= ($arr_str==""?"":$sp) . $val;
		}
		return $arr_str;
	}

	public function getTitle() {  // language, table, criteria => auto output  "title_$languange"
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$lang = $params[0];
		$table = $params[1];
		$criteria = "";
		if(is_array($params[2])) {
			foreach($params[2] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[2]) . "'";
		}

		$col_title = strtolower("title_" . $lang);
		if($lang != "en") $col_title = strtolower("title_cn");

		$query = "SELECT $col_title FROM $table WHERE $criteria";	
		$result = $this->query($query);
		$row = $this->fetch($result);
		return $row[$col_title]?cTYPE::gstr($row[$col_title]):"";
	}


	public function getTitles() {   // language, table, criteria
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$lang = $params[0];
		$table = $params[1];
		$criteria = "";
		if(is_array($params[2])) {
			foreach($params[2] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
		    $criteria = "1=1";
		} 

		$col_title = strtolower("title_" . $lang);
		if($lang != "en") $col_title = strtolower("title_cn");

		$query = "SELECT id, $col_title FROM $table WHERE $criteria";	
		$result = $this->query($query);
		
        	$titles = array();
	        while($row = $this->fetch($result)) {
            		$titles[$row["id"]] = $row[$col_title]?cTYPE::gstr($row[$col_title]):"";
		}
		return $titles;
	}
	
	public function cols($rs) {
		if($rs) {
			$fields = array();
			$i = 0;
			while ( $i < $this->col_nums($rs) ) {
				$finfo = mysql_fetch_field($rs, $i);
				$fields[] = $finfo->name;
				$i++;	
			}
			return $fields;
		} else {
			$this->error->set(3004, "rowset is not available for cols");
			throw $this->error;
		}
	}
	
	
	public function exists($sql) {
		$rs = $this->query($sql);
		if( $this->row_nums($rs) > 0 ) { 
			return true;
		} else { 
			return false;
		}
	}

	public function hasRow() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$table = $params[0];
		$criteria = "";
		if(is_array($params[1])) {
			foreach($params[1] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[1]) . "'";
		}
		
		$query = "SELECT 0 FROM $table WHERE $criteria";	
		return $this->exists($query);
	}

	
	public function insert() {
		$pnum 			= func_num_args();
		$params			= func_get_args();
		$table 			= $params[0];
		$field_array 	= $params[1];
		
		$fields = "";
		$values = "";
		foreach($field_array as $key=>$val) {
			$fields .= ($fields==""?$key: ", " . $key); 
			$val = $this->quote($val);
			$values .= ($values==""?"":", ") . "'" . $val . "'"; 
		}
		$query = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
		//echo "\nquery:" . $query;
		$this->query($query);
		$insert_id = mysql_insert_id($this->link);
		return $insert_id;
	}

	public function insert_raw() {
		$pnum 			= func_num_args();
		$params			= func_get_args();
		$table 			= $params[0];
		$field_array 	= $params[1];
		
		$fields = "";
		$values = "";
		foreach($field_array as $key=>$val) {
			$fields .= ($fields==""?$key: ", " . $key); 
			//$val = $val;
			$values .= ($values==""?"":", ") . "'" . $val . "'"; 
		}
		$query = "INSERT INTO " . $table . " (" . $fields . ") VALUES (" . $values . ")";
		//echo "\nquery:" . $query;
		$this->query($query);
		$insert_id = mysql_insert_id($this->link);
		return $insert_id;
	}
	
	public function getID() {
		$insert_id = mysql_insert_id($this->link);
		return $insert_id;
	}

	public function getVal() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$table = $params[0];
		$criteria = "";
		if(is_array($params[2])) {
			foreach($params[2] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[2]) . "'";
		}
		$col = $params[1];
		$query = "SELECT $col FROM $table WHERE $criteria";	
		$result = $this->query($query);
		$row = $this->fetch($result);
		return $row[$col];
	}

	public function select() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$table = $params[0];
		$col = $params[1];

		$criteria = "";
		if(is_array($params[2])) {
			foreach($params[2] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[2]) . "'";
		}
		$query = "SELECT $col FROM $table WHERE $criteria";	
		$result = $this->query($query);
		return $result;
	}

	public function rselect() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$pt = $params[0];
		$rt = $params[1];

        $ptable = $pt["table"];
        $pkeys  = $pt["keys"];
        $pcols  = $pt["cols"];
        $pcons  = $pt["where"];

        $rtable = $rt["table"];
        $rkeys  = $rt["keys"];
        $rcols  = $rt["cols"];
        $rcons  = $rt["where"];

        $output = "";
        foreach($pcols as $pcol) {
            $output .= ($output==""?"":",") . ($ptable . "." . $pcol); 
        }

        foreach($rcols as $rcol) {
            $output .= ($output==""?"":",") . ($rtable . "." . $rcol); 
        }

        $from = "";
        foreach($pkeys as $idx=>$pkey) {
            $from .= ($from==""?"":" AND ") . ($ptable . "." . $pkey) . " = " . ($rtable . "." . $rkeys[$idx]);
        }       
        $from = "$ptable INNER JOIN $rtable ON (" . $from . ")";



		$criteria = "";
		foreach($pcons as $col=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . ($ptable . "." . $col) . " = '" . $this->quote(trim($val)) . "'";
		}

		foreach($rcons as $col=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . ($rtable . "." . $col) . " = '" . $this->quote(trim($val)) . "'";
		}

        $criteria = $criteria==""?"1=1":$criteria;

		$query = "SELECT $output FROM $from WHERE $criteria";	
		$result = $this->query($query);
		return $result;
	}



	public function append() {  // table, critera, fieldset  => No Ouput,  Update or Insert
		$pnum 	= func_num_args();
		$params	= func_get_args();

		$table 			= $params[0];
		$field_array 	= $params[2];
		
		$criteria = "";
		///1111111111111111111111111//
		if(is_array($params[1])) {
			foreach($params[1] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[1]) . "'";
		}
		///1111111111111111111111111//
		if($this->exists("SELECT * FROM $table WHERE $criteria")) {
			$fields_update = "";
			foreach($field_array as $key=>$val) {
				$val = $this->quote($val);
				$fields_update .= ($fields_update==""?"":", ") . $key . " = '" . $val . "'";
			}	
			$query = "UPDATE " . $table . " SET " . $fields_update . " WHERE " . $criteria . ";";
			$this->query($query);
		} else {
			foreach($params[1] as $key=>$val) {
				$field_array[$key] = $this->quote($val); 
			}
			$this->insert($table, $field_array);
		}
	}

	public function update() {
		$pnum 	= func_num_args();
		$params	= func_get_args();

		$table 			= $params[0];
		$field_array 	= $params[2];
		
		$criteria = "";
		if(is_array($params[1])) {
			foreach($params[1] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[1]) . "'";
		}
		
		$fields_update = "";
		foreach($field_array as $key=>$val) {
				$val = $this->quote($val);
				$fields_update .= ($fields_update==""?"":", ") . $key . " = '" . $val . "'";
		}	
		$query = "UPDATE " . $table . " SET " . $fields_update . " WHERE " . $criteria . ";";
		//echo "\nquery:" . $query . "\n";
		$this->query($query);
	}

	public function rupdate() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$pfield_con = "1=0";
		$pfield_arr = array();
		switch($pnum) {
			case 4:
				$table 			= $params[0];
				$pfields		= $params[1];

				$criteria = "";
				if(is_array($pfields)) {
					foreach($pfields as $key=>$val) {
						$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . $this->quote(trim($val)) . "'";
					}
					$pfield_con = $criteria==""?"1=0":$criteria;
					$pfield_arr = $pfields; 
				} 
				
				$rfield			= $params[2];
				$rfield_val		= $params[3];
				break;
			case 5:
				$table 			= $params[0];
				$pfield_name	= $params[1];
				$pfield_val		= $params[2];
				if( $pfield_name != "" ) {
					$criteria = "$pfield_name = '" . $this->quote(trim($pfield_val)) . "'";
					$pfield_con = $criteria==""?"1=0":$criteria;
					$pfield_arr[$pfield_name] = $pfield_val; 
				} else {
					$pfield_con = "1=0";
				}
				
				$rfield			= $params[3];
				$rfield_val		= $params[4];
				break;
		}
		//echo "Count: " . count($pfield_arr) . "  Condition:" . $pfield_con . "<br>\n";
		
		if( count($pfield_arr) > 0 ) {
			$this->query("DELETE FROM $table WHERE $pfield_con");
			if( is_array($rfield_val) ) {
				$rfield_arr = $rfield_val;
			} else {
				$rfield_arr = explode(",",$rfield_val); 
			}
			
			foreach($rfield_arr as $key=>$val) {
				$val = $this->quote($val);
				if($val>0) {
					$fields = array();
					$fields = $pfield_arr;
					$fields[$rfield] = $val;
					$this->insert($table, $fields);
				}	
			}
		}
	}
	
	public function delete() {
		$pnum 	= func_num_args();
		$params	= func_get_args();

		$table 			= $params[0];
		$criteria = "";
		if(is_array($params[1])) {
			foreach($params[1] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[1]) . "'";
		}
		$query = "DELETE FROM " . $table . " WHERE " . $criteria . ";";
		//echo "\nquery:" . $query . "\n";
		$this->query($query);
	}

	public function detach() {
		$pnum 	= func_num_args();
		$params	= func_get_args();

		$table 			= $params[0];
		$criteria = "";
		if(is_array($params[1])) {
			foreach($params[1] as $key=>$val) {
				$criteria .= ($criteria==""?"":" AND ") . $key . " = '" . trim($val) . "'";
			}
		} else {
			$criteria = "id = '" . trim($params[1]) . "'";
		}
		$query = "UPDATE " . $table . " SET deleted = 1 WHERE " . $criteria . ";";
		//echo "\nquery:" . $query . "\n";
		$this->query($query);
	}
	
	public function quote($val) {
		if($this->link) {
			$new_val = mysql_real_escape_string(trim($val), $this->link);
			return $new_val;
		} else {
			$this->error->set(3001, "connection is not available before excute quote");
			throw $this->error;
		}
	}
	
	//private method to support
	private function __open($host, $user, $pwd) {
		$this->link = mysql_connect($host, $user, $pwd); 
		if(!$this->link)  {
			  $this->error->set(3001, mysql_error());
			  throw $this->error;
		} else {
			//mysql_query('SET NAMES utf8', $this->link);
		}
	}
}


/***********************************************************************************************/
/*																							   */
/***********************************************************************************************/
class cTABLE {
	public 	$error 	= null;
	private $rowset = null;
	public 	$row	= null;

	public $selectCommand = '';
	public $updateCommand = '';
	public $insertCommand = '';
	public $deleteCommand = '';


	
	function __construct() {
		// initialize error object;
		$this->error = new cERR();

		$pnum 	= func_num_args();
		$params	= func_get_args();
		switch($pnum) {
			case 1:
				if($params[0]) {
					$this->rowset = $params[0];
				} else {
					$this->error->set(3006, "table object is invalid due to parameter rowset invalid");
					throw $this->error;
				}
				break;
			default:
				break;
		}
		
	}
	function __destruct() {
	}
}

/***********************************************************************************************/
/*																							   */
/***********************************************************************************************/
class cTYPE {
	static private $error 		= null;
	static private $err_code	= 0;
	static private $err_msg 	= '';
	static private $err_field	= array();
	static private $err_cnt		= 0;
	static private $type = array(
			"ALL"		=> "/^(?:.|\s)*$/i", 						// all chars
			"EMAIL"		=> "/^(?:\w+-?\.?)*\w+@(?:\w+\.)+\w+$/i", 	//Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca
			"EMAILS"	=> "/^(?:(?:\w+-?\.?)*\w+@(?:\w+\.)+\w+(\s*,\s*)?)+$/i",  		// a@a.com, b@b.com, c@c.com
			"CHAR"		=> "/^.*$/i", 								// all chars except \n\r 
			"LETTER"	=> "/^[a-zA-Z'\",\._ ]*$/i",
			"NUMBER"	=> "/^[+-]?[0-9]*(?:(?:\.)[0-9]+)?(,)?$/i",
			"DATE"		=> "/^(?:19|20)[0-9]{2}(?:-|\/)(?:1[0-2]|0?[1-9])(?:-|\/)(?:3[01]|[0-2]?[0-9])$/i",
			"TIME"		=> "/^((2[0-3]|[01]?[0-9])(:[0-5][0-9](:[0-5][0-9])?)?[ ]*(am|pm)?)$/i",
			"DATETIME"	=> "/^(?:19|20)[0-9]{2}(?:-|\/)(?:1[0-2]|0?[1-9])(?:-|\/)(?:3[01]|[0-2]?[0-9])[ ]+((2[0-3]|[01]?[0-9])(:[0-5][0-9](:[0-5][0-9])?)?[ ]*(am|pm)?)$/i"
	);
	
	static private $err_char = array(
	"ALL"			=> "All charater was allowed.", // all chars
	"EMAIL"			=> "Email format must be like xxxx@xxx.xxx , xxxx@xxx.xxx.xxx ", //Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$",  //2007-10-31  or 97-12-31
	"EMAILS"		=> "Email format must be like xxxx@xxx.xxx , xxxx@xxx.xxx.xxx ", //Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$",  //2007-10-31  or 97-12-31
	"CHAR"			=> "Not allow these characters : '<' , '>' ", // not allow to use "<>"  prevent from broke web page .  like "<html>" or any tags.
	"LETTER"		=> "Letters and numbers only.",  // 3.1414, -34,  -3.143
	"NUMBER"		=> "Just number charater allowed.",  // 3.1414, -34,  -3.143
	"DATE"			=> "Format as 1997-04-25, yyyy-mm-dd", //1997-04-25 13pm    2007-4-25 10:00am
	"TIME"			=> "Format as 2:00, 2pm, 9:01:56am, 13:25pm", // 2pm , 9:01:56AM , 13:25pm 
	"DATETIME"		=> "Format as 1997-04-25 13pm, 2007-4-25 10:00am" //1997-04-25 13pm    2007-4-25 10:00am
	);
	
	static public function validate($pa, $vv) {
		foreach( $pa as $key=>$val ) {
			$tobj = json_decode($val);
			$err_flag = false;
			if( in_array( strtoupper($tobj->type), array_keys(self::$type)) ) {
				$vv[$key] = trim($vv[$key]);
				if(!$tobj->nullable  && strlen($vv[$key]) <= 0) {
					$err_flag = true;
					self::$err_code	= 1;
					self::$err_msg .= "'" . $tobj->name . "' is required field.\n";  
				}
				
				if($tobj->length > 0 && strlen($vv[$key]) > $tobj->length)	{
					$err_flag = true;
					self::$err_code	= 1;
					self::$err_msg .= "'" . $tobj->name . "' {" . strlen($vv[$key]) . " chars} exceed the maximium length {" . $tobj->length . " chars}.\n";  			
				}
				
				if(strlen($vv[$key]) > 0 && !preg_match(self::$type[strtoupper($tobj->type)], $vv[$key]) )  {
					$err_flag = true;
					self::$err_code	= 1;
					self::$err_msg .= "'" . $tobj->name . "' contains invalid characters or an invalid format.\n";  			
					self::$err_msg .= "'" . $tobj->name . "' Reference: " . self::$err_char[strtoupper($tobj->type)] . ".\n";  			
				}
				
				if($err_flag) {
					self::$err_field[self::$err_cnt]["id"] 		= $tobj->id;
					self::$err_field[self::$err_cnt]["name"] 	= $tobj->name;
				}
				self::$err_cnt++;
			} else {
				self::$err_code	= 1;
				self::$err_msg .= "'" . $tobj->name . "' data type " . $tobj->type . " doesn't exists.\n";  
			}
		}
	}
	
	static public function clear() {
		self::$err_code		= 0;
		self::$err_msg		= '';
		self::$err_field 	= array();
		self::$err_cnt 		= 0;
	}
	
	static public function check() {
		if(self::$err_code) {
			if( !self::$error ) self::$error = new cERR();
			self::$error->set(4001, self::$err_msg, self::$err_field);
			throw self::$error;
		}
	}
	
	static public function nltobr( $str ) {
		return str_replace(array("\n", "\r", " "), array("<br>", "<br>", "&nbsp;"), $str);
	}

	static public function brtonl( $str ) {
		return str_replace(array("<br>", "<br>", "&nbsp;"), array("\n", "\r", " "),  $str);
	}
	
	static public function datetoint($dt) {
		$dt 			= trim($dt);
		$dt_arr 		= explode(" ",$dt);
		$date_part 	= $dt_arr[0];
		$time_part	= $dt_arr[1];
		$ampm_part	= $dt_arr[2];
		
		if(strpos($date_part, "/") != false) {
			$sp = "/";
		} elseif(strpos($date_part, "-") != false) {
			$sp = "-";
		} else {
			//$sp = " ";
			return 0;
		}
		// deal with date part
		$ddd = explode($sp, $date_part);
		$yy_digit = intval($ddd[0]);
		$nn_digit = intval($ddd[1])>1?intval($ddd[1]):1;
		$dd_digit = intval($ddd[2])>1?intval($ddd[2]):1;
		// deal with time part
		$ttt = explode(":", $time_part);
		$mm_digit = intval($ttt[1]);
		$hh_digit = intval($ttt[0]);
		$ss_digit = floatval($ttt[2]);
		// deal with time AM/PM situation
		if( 
			( 
				strpos(strtoupper($ttt[0]),"PM") != false 
				|| 
				strpos(strtoupper($ttt[1]),"PM") != false 
				|| 	
				strpos(strtoupper($ttt[2]),"PM") != false 
				|| 
				strtoupper($ampm_part) == "PM"				
			) 
			&& $hh_digit <= 12 
		)  $hh_digit += 12;
		
		$mk_tt = mktime($hh_digit, $mm_digit, $ss_digit, $nn_digit, $dd_digit, $yy_digit);
		return $mk_tt;
	}

	static public function inttodate() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		if( $params[0] > 0 ) {
			$dateff = "Y-m-d H:i:s";
			if($pnum > 1) $dateff = $params[1];
			return date($dateff, $params[0]);
		} else {
			return "";
		}
	}
	
	static public function dhms() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$t = intval($params[0]);
		if($t > 0) {
			$dd = floor($t/(3600 *24));
			$dd_str = $dd<=0?"":$dd . ($dd<2?"day ":"days ");
			$hh = floor( ($t % (3600 * 24)) / 3600 );
			$hh_str = $hh>0?substr("0".$hh, -2).":":"";
			$mm = floor(($t % 3600) / 60);
			$mm_str = $mm>0?substr("0".$mm, -2).":":"00:";
			$ss = $t % 60;
			$ss_str = $ss>0?substr("0".$ss, -2):"00";
			return $dd_str . $hh_str. $mm_str. $ss_str;
		} else {
			if($pnum > 1 && $params[1]) 
				return ""; 
			else  
				return "00:00";
			
		}
	}
	
	static public function cname($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["first_name"] . '' . $name_array["last_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["first_name"] . ' ' . $name_array["last_name"];
				
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])   . " " . substr($name_array["last_name"],0,1) . ".";
				} 
			}
		}
		$name_str = $info["name"];
		
		$info["name"] 			= $name_array["legal_last"] . '' . $name_array["legal_first"];
		if ( cTYPE::iifcn($name_array["legal_last"]) || cTYPE::iifcn($name_array["legal_first"]) ) {
			if($name_array["legal_last"] == $name_array["legal_first"] ) 
				$info["name"] = $name_array["legal_first"];
			else 
				$info["name"] = $name_array["legal_last"] . '' . $name_array["legal_first"];
			//echo "name:" . $info["name"] . "\n"; 
		} else {
			if($name_array["legal_last"] == $name_array["legal_first"] ) { 
				$info["name"] = $name_array["legal_first"];
			} else {
				$info["name"] = $name_array["legal_first"] . ' ' . $name_array["legal_last"];
			}
		}
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$name_array["first_name"]?$name_array["dharma_name"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		$info["name"] = $name_array["alias"]!=""?$name_array["alias"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		return stripslashes($name_str);		
	}

	static public function fullfirst($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["first_name"] . '' . $name_array["last_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["first_name"] . ' ' . $name_array["last_name"];
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = $name_array["first_name"] . ' ' . substr($name_array["last_name"],0,1) . '.'; 
					//$info["name"] = (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])   . " " . substr($name_array["last_name"],0,1) . ".";
				} 
			}
		}
		$name_str = $info["name"];
		
		
		$info["name"] 			= $name_array["legal_last"] . '' . $name_array["legal_first"];
		if ( cTYPE::iifcn($name_array["legal_last"]) || cTYPE::iifcn($name_array["legal_first"]) ) {
			if($name_array["legal_last"] == $name_array["legal_first"] ) 
				$info["name"] = $name_array["legal_first"];
			else 
				$info["name"] = $name_array["legal_last"] . '' . $name_array["legal_first"];
			//echo "name:" . $info["name"] . "\n"; 
		} else {
			if($name_array["legal_last"] == $name_array["legal_first"] ) { 
				$info["name"] = $name_array["legal_first"];
			} else {
				$info["name"] = $name_array["legal_first"] . ' ' . $name_array["legal_last"];
			}
		}
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$name_array["first_name"]?$name_array["dharma_name"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		$info["name"] = $name_array["alias"]!=""?$name_array["alias"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		return stripslashes($name_str);		
	}

	static public function tname($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["first_name"] . '' . $name_array["last_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["first_name"] . ' ' . $name_array["last_name"];
				
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])   . " " . substr($name_array["last_name"],0,1) . ".";
				} 
			}
		}
		$name_str = $info["name"];
		
		$info["name"] 			= $name_array["legal_last"] . '' . $name_array["legal_first"];
		if ( cTYPE::iifcn($name_array["legal_last"]) || cTYPE::iifcn($name_array["legal_first"])) {
			if($name_array["legal_last"] == $name_array["legal_first"] ) 
				$info["name"] = $name_array["legal_first"];
			else 
				$info["name"] = $name_array["legal_last"] . '' . $name_array["legal_first"];
			//echo "name:" . $info["name"] . "\n"; 
		} else {
			if($name_array["legal_last"] == $name_array["legal_first"] ) { 
				$info["name"] = $name_array["legal_first"];
			} else {
				$info["name"] = $name_array["legal_first"] . ' ' . $name_array["legal_last"];
			}
		}
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$name_array["first_name"]?$name_array["dharma_name"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		$info["name"] = $name_array["alias"]!=""?$name_array["alias"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		return cTYPE::tobig5(stripslashes($name_str));		
	}

	static public function lfname($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["last_name"] . ($name_array["last_name"]!="" && $name_array["first_name"]!=""?', ':'') . $name_array["first_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["last_name"] . ($name_array["last_name"]!="" && $name_array["first_name"]!=""?', ':'') . $name_array["first_name"];
				
				if($name_count > 0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = substr($name_array["last_name"],0,1) . "."  . " " . (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])  ;
				} 
			}
		}
		$name_str = $info["name"];
		
		$info["name"] 			= $name_array["legal_last"] . '' . $name_array["legal_first"];
		if ( cTYPE::iifcn($name_array["legal_last"]) || cTYPE::iifcn($name_array["legal_first"])) {
			if($name_array["legal_last"] == $name_array["legal_first"] ) 
				$info["name"] = $name_array["legal_first"];
			else 
				$info["name"] = $name_array["legal_last"] . '' . $name_array["legal_first"];
			//echo "name:" . $info["name"] . "\n"; 
		} else {
			if($name_array["legal_last"] == $name_array["legal_first"] ) { 
				$info["name"] = $name_array["legal_first"];
			} else {
				$info["name"] = $name_array["legal_last"] . ($name_array["legal_last"]!="" && $name_array["legal_first"]!=""?', ':'') . $name_array["legal_first"];
			}
		}
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$name_array["first_name"]?$name_array["dharma_name"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		$info["name"] = $name_array["alias"]!=""?$name_array["alias"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		return cTYPE::tobig5(stripslashes($name_str));		
	}


	static public function lfname1($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["last_name"] . ' ' . $name_array["first_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["last_name"] . ' ' . $name_array["first_name"];
				
				if($name_count > 0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = substr($name_array["last_name"],0,1) . "."  . " " . (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])  ;
				} 
			}
		}
		$name_str = $info["name"];
		
		$info["name"] 			= $name_array["legal_last"] . '' . $name_array["legal_first"];
		if ( cTYPE::iifcn($name_array["legal_last"]) || cTYPE::iifcn($name_array["legal_first"])) {
			if($name_array["legal_last"] == $name_array["legal_first"] ) 
				$info["name"] = $name_array["legal_first"];
			else 
				$info["name"] = $name_array["legal_last"] . '' . $name_array["legal_first"];
			//echo "name:" . $info["name"] . "\n"; 
		} else {
			if($name_array["legal_last"] == $name_array["legal_first"] ) { 
				$info["name"] = $name_array["legal_first"];
			} else {
				$info["name"] = $name_array["legal_last"] . ' ' . $name_array["legal_first"];
			}
		}
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$name_array["first_name"]?$name_array["dharma_name"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		$info["name"] = $name_array["alias"]!=""?$name_array["alias"]:""; 
		$name_str .= ($name_str!=""&&$info["name"]!=""?" - ":"") . $info["name"];

		return cTYPE::tobig5(stripslashes($name_str));		
	}


	static public function cert_cname($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["first_name"] . '' . $name_array["last_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["first_name"] . ' ' . $name_array["last_name"];
				
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])   . " " . substr($name_array["last_name"],0,1) . ".";
				} 
			}
		}
		$name_str = $info["name"];
		
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$info["name"]?$name_array["dharma_name"]. '&nbsp;&nbsp;&nbsp;' . $info["name"]:$info["name"];
		$info["name"] = $info["name"]!=""?$info["name"]:$name_array["alias"];
		return cTYPE::trans(stripslashes($info["name"]));		
	}

	static public function cert_lname($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["last_name"] . ' ' . $name_array["first_name"];
				
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = substr($name_array["last_name"],0,1) . "." . " " . (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"]);
				} 
			}
		}
		$name_str = $info["name"];
		
	
		$info["name"] = $name_array["dharma_name"]!=""&&$name_array["dharma_name"]!=$info["name"]?$name_array["dharma_name"]. '&nbsp;&nbsp;&nbsp;' . $info["name"]:$info["name"];
		$info["name"] = $info["name"]!=""?$info["name"]:$name_array["alias"];
		return cTYPE::trans(stripslashes($info["name"]));		
	}
	

	static public function cert_fullname($name_array) {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		$name_count = $params[1];
		
		$name_str = ''; 	
		$info = array();	
		$info["name"] 			= $name_array["last_name"] . '' . $name_array["first_name"];
		if ( cTYPE::iifcn($name_array["last_name"]) || cTYPE::iifcn($name_array["first_name"])) {
			if($name_array["last_name"] == $name_array["first_name"] ) 
				$info["name"] = $name_array["first_name"];
			else 
				if( !cTYPE::iifcn($name_array["first_name"]) )
					$info["name"] = $name_array["first_name"] . '' . $name_array["last_name"];
				else 
					$info["name"] = $name_array["last_name"] . '' . $name_array["first_name"];
		} else {
			if($name_array["last_name"] == $name_array["first_name"] ) { 
				$info["name"] = $name_array["first_name"];
			} else {
				$info["name"] = $name_array["first_name"] . ' ' . $name_array["last_name"];
				
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = (strlen($name_array["first_name"])>($name_count-3)?substr($name_array["first_name"],0,($name_count-3) ) . "." : $name_array["first_name"])   . " " . substr($name_array["last_name"],0,1) . ".";
				} 
			}
		}
		$name_str = $info["name"];


		$info 			= array();	
		$info["name"] 	= $name_array["legal_last"] . '' . $name_array["legal_first"];
		if ( cTYPE::iifcn($name_array["legal_last"]) || cTYPE::iifcn($name_array["legal_first"])) {
			if($name_array["legal_last"] == $name_array["legal_first"] ) 
				$info["name"] = $name_array["legal_first"];
			else 
				if( !cTYPE::iifcn($name_array["legal_first"]) )
					$info["name"] = $name_array["legal_first"] . '' . $name_array["legal_last"];
				else 
					$info["name"] = $name_array["legal_last"] . '' . $name_array["legal_first"];
		} else {
			if($name_array["legal_last"] == $name_array["legal_first"] ) { 
				$info["name"] = $name_array["legal_first"];
			} else {
				$info["name"] = $name_array["legal_first"] . ' ' . $name_array["legal_last"];
				
				if($name_count>0 && strlen( $info["name"] ) > $name_count)  {
					$info["name"] = (strlen($name_array["legal_first"])>($name_count-3)?substr($name_array["legal_first"],0,($name_count-3) ) . "." : $name_array["legal_first"])   . " " . substr($name_array["legal_last"],0,1) . ".";
				} 
			}
		}
		$legal_str = $info["name"];		
		
		$ret_name = $legal_str;
		if( $ret_name == "" ) $ret_name = $name_str;
		if( $ret_name == "" ) $ret_name = $name_array["alias"];
		
		return cTYPE::trans(stripslashes($ret_name));		
	}

	static public function cert_dharma($name_array) {
		$name_str = ''; 	
		$name_str = $name_array["dharma_name"];
		if($name_array["dharma_pinyin"]!="" && $name_array["dharma_name"]!=$name_array["dharma_pinyin"]) $name_str .= ' ' . $name_array["dharma_pinyin"];
		return $name_str;		
	}


	static public function tobig5($str) {
		global $tran; 
		return $tran->c2t((trim($str)));
	}

	static public function iifcn($str) {
		if( !preg_match("/^\w*[\x7f-\xff]+\w*$/", $str) )
			return false;
		else 
			return true; 
	}


	static public function ufirst($str) {
		global $tran; 
		if( cTYPE::iifcn($str) ) 
			return $tran->t2c(trim($str));
		else 	
			return $tran->t2c(ucfirst(strtolower(trim($str))));
	}

	static public function uword($str) {
		global $tran; 
		if( cTYPE::iifcn($str) ) 
			return $tran->t2c(trim($str));
		else 	
			return $tran->t2c(ucwords(strtolower(trim($str))));
	}
	
	static public function trans($str) {
		global $tran; 
		return $tran->t2c($str);
	}

	static public function trans_trim($str) {
		global $tran; 
		return str_replace(array(" ", ","), array("%","%"), $tran->t2c(trim($str)));
	}

	static public function trans_trim1($str) {
		global $tran; 
		return str_replace(array(" ", ",", "-", "."), array("", "", "", ""), $tran->t2c(trim($str)));
	}

	static public function utrans($str) {
		global $tran; 
		return $tran->t2c(trim($str));
	}

	
	static public function phone($str) {
		$tp = str_replace(array(" ", "-", ",", "(", ")","."), array("","","","","",""), trim($str));
		if( strlen($tp) == 10 ) {
			return substr($tp,0,3) . "-" . substr($tp,3,3) . "-" . substr($tp,6,4);
		} elseif( strlen($tp) == 11 ){
			return substr($tp,0,1) . "-" . substr($tp,1,3) . "-" . substr($tp,4,3) . "-" . substr($tp,7,4);
		} else {
		    return $str;
		}
	}

	static public function gstr($str) {
		global $Glang;
		switch($Glang) {
			case "cn":
				return stripslashes(cTYPE::trans($str));
			case "tw":
				return cTYPE::tobig5($str);
			default:
				return stripslashes($str);
		}
		//return $tran->t2c(trim($str));
	}
	
	static public function toDate($yy, $mm, $dd) {
		if( ($yy==0 && $mm==0 && $dd==0) || ($yy=="" && $mm=="" && $dd=="") ) {
			return "";
		} else {
			if( $yy=="" || $yy<=0 ) $yy = "xxxx";
			if( $mm=="" || $mm<=0 ) $mm = "xx";
			if( $dd=="" || $dd<=0 ) $dd = "xx";
			return $yy . "-" . substr( "0".$mm, -2) . "-" . substr("0".$dd, -2);	
		}
	}

	static public function ageRange($yy, $cur_range) {
		$man_range = 0;
		if( $yy > 0 ) {
			
			$ages = array();
			$ages[0]["min"] = 0;
			$ages[0]["max"] = 0;
	
			$ages[1]["min"] = 0;
			$ages[1]["max"] = 25;
	
			$ages[2]["min"] = 26;
			$ages[2]["max"] = 40;
	
			$ages[3]["min"] = 41;
			$ages[3]["max"] = 55;
	
			$ages[4]["min"] = 56;
			$ages[4]["max"] = 65;
	
			$ages[5]["min"] = 66;
			$ages[5]["max"] = 120;
			
			$man_age = date("Y") - intval($yy);
			
			foreach( $ages as $key=>$val) {
				if( $man_age >= $val["min"] && $man_age <= $val["max"] ) {
					$man_range = $key;
					break;
				}
			}

		} else {
			$man_range = $cur_range;
		}
		return $man_range;
		
	}

    function shelfSN($current, $maxcap) 
    {
        $caplen     = strlen($maxcap);
        $current    = intval($current);
        $maxcap     = intval($maxcap);
        $hih_num = floor($current / $maxcap);
    
        if( $current > $maxcap) {
            $low_num = $current - $hih_num * $maxcap;
            $low_num = $low_num==0?$maxcap:$low_num;
        } else {
            $low_num = $current;
        }

        $new_val = $low_num>0?str_pad($low_num, $caplen, "0", STR_PAD_LEFT):"";
        return $new_val;
    }

}

/*** sample as below  ***/
/*
	$a["NAME"] 	= '{"type":"EMAIL", "length":3, "id": "good", "name":"first name", "nullable":0}';
	$a["AGE"] 	= '{"type":"letter", "length":11, "id": "el_age", "name":"Birth Date", "nullable":0}';
	$v["NAME"] = "abc@abc";
	cTYPE::validate($a, $v);
	cTYPE::check();
*/
/*** end of sample ***/



/***********************************************************************************************/
/*																							   */
/***********************************************************************************************/
class cERR extends Exception {
	private $errMsg = array (
		3001=>"Database Connection: %s.",
		3002=>"Database Query: %s.",
		3003=>"Database Select: %s.",
		3004=>"Database Rowset: %s.",
		3005=>"Database Row: %s.",
		3006=>"Table Object: %s.",
		
		4001=>"Sorry, we are unable to process your input for the following reasons:\n\n%s",
		9001=>"Session has expired, please login again.",
		9002=>"You don't have right to access our database."
	);
	
	private $errFields = array();
	function __construct() {    // params[0] = errorCode,  params[1] = errorMessage
		$pnum 	= func_num_args();
		$params	= func_get_args();
		switch($pnum) {
			case 1:
				parent::__construct("", $params[0]);
				break;
			case 2:
				parent::__construct($params[1], $params[0]);
				break;
		}
	}
	
	// used to format error message;
	public function getMsg() {
		$msg = '';
		switch($this->getCode()) {
			case 3001:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 3002:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 3003:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 3004:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 3005:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 3006:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 4001:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 9001:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			case 9002:
				$msg = sprintf($this->errMsg[$this->code], $this->getMessage());
				break;
			default:
				$msg = sprintf("%s", $this->getMessage());
				break;
		}
		return $msg;
	}
	
	public function set() {
		$pnum 	= func_num_args();
		$params	= func_get_args();

		$this->code 	= $params[0];
		$this->message	= $params[1];
		
		if($params[2])  $this->errFields = $params[2];
	}
	
	public function detail() {
		$msg = array();
		$msg["errorCode"] 		= $this->getCode();
		$msg["errorMessage"] 	= $this->getMsg();
		$msg["errorLine"] 		= sprintf("File[file:%s, line:%s]", $this->getFile(), $this->getLine());
		$msg["errorField"]		= $this->errFields;
		/*
		echo "<pre>";
		print_r($msg);
		echo "</pre>";
		*/
		return $msg;
	}
}

class cHTML {
	static public function checkbox() {
		global $words;
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$name = "";  		// p0
		$rows = array(); 	// p1
		$ccnt = 3;   		// p2
		$vals = array();	// p3
		switch($pnum) {
			case 2:
				$name = $params[0];
				$rows = $params[1];
				break;
			case 3:
				$name = $params[0];
				$rows = $params[1];
				$ccnt = $params[2];
				break;
			
			case 4:
				$name = $params[0];
				$rows = $params[1];
				$ccnt = $params[2];
				$vals = $params[3];
				break;

		}

		//$html = '<table>';
		$cnt=0;
		$cno=0;
		foreach( $rows as $row ) {
			$cno++;
			/*
			if($cnt <= 0) {
				$html .= '<tr>';
			}
			*/

			if($cnt <= 0) {
				$html .= '<span style="vertical-align:middle; margin-left:0px;">';
			}

			$cnt++;
			
			$hdesc = $row["description"]&&$row["description"]!=""?$row["description"]:"";
			//$html .= '<td>';
			$html .= ' <input type="checkbox" style="vertical-align:middle;" id="' . $name . '_' . $row["id"] . '" name="' . $name . '" ' . ( in_array($row["id"], $vals)?'checked="checked"':'').  ' class="' . $name . '"  value="' . $row["id"] . '"><label for="' . $name . '_' . $row["id"] . '" title="' . $hdesc . '">' .  ($words[strtolower($row["title"])]!=""?$words[strtolower($row["title"])]:$row["title"]) . '</label>';
			//$html .= '</td>';

			/*
			if($cnt >= $ccnt) {
				$cnt = 0;
				$html .= '</tr>';
			}
			*/
			if($cnt >= $ccnt) {
				$cnt = 0;
				$html .= '</span><br>';
			}

		}
		//if($cnt > 0 && $cnt < $ccnt) $html .= '</tr>';
		//$html .= '</table>';
		return $html;
	}
	
	static public function radio() {
		global $words;
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
		$name = "";  		// p0
		$rows = array(); 	// p1
		$cval = '';	// p3
		$ccnt = 0;
		switch($pnum) {
			case 2:
				$name = $params[0];
				$rows = $params[1];
				break;
			case 3:
				$name = $params[0];
				$rows = $params[1];
				$cval = $params[2];
				break;
			case 4:
				$name = $params[0];
				$rows = $params[1];
				$cval = $params[2];
				$ccnt = $params[3];
				break;
		}

		$html = '<span>';
		$cno=0;
		foreach( $rows as $row ) {
			$cno++;
			$html .= '<span style="margin-left:0px;">';
			
			$hdesc = $row["description"]&&$row["description"]!=""?$row["description"]:"";
			$html .= ' <input type="radio" id="' . $name . '_' . $row["id"] . '" name="' . $name . '" ' . ($row["id"]==$cval?'checked="checked"':'').  ' class="' . $name . '"  value="' . $row["id"] . '"><label for="' . $name . '_' . $row["id"] . '" title="' . $hdesc . '">' .  ($words[strtolower($row["title"])]!=""?$words[strtolower($row["title"])]:$row["title"]) . '</label>';
			$html .= '</span>';
			if($ccnt > 0 && $cno >= $ccnt) {
				$html .= '<br />';
				$cno = 0;
			}
		}
		$html .= '</span>';
		return $html;
	}
	
}
?>
