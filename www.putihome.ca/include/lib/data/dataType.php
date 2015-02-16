<?php
class CERROR {
	public $errorCode 		= 0;
	public $errorMessage	= "";
	public $errorFields		= array();
	public $hasError		= false;
	private $retByID		= array();
	private $retByKey		= array();
	public $colArray		= array();
	
	public function CERROR() {
		//do nothing for constructor
	}
	
	public function addFromCol($aCol) {
		array_push($this->colArray, $aCol);
	}

	public function addFromArray($colArray) {
		if(is_array($colArray)) {	
			foreach($colArray as $key=>$aCol) {
				array_push($this->colArray, $aCol);
			}
		}
	}
	
	public function addFromCols($cols) {
		if(is_array($cols)) {	
			$newCol = array();
			foreach($cols as $key=>$aCol) {
				$newCol[$key] = $aCol;
				$this->addFromCol($newCol[$key]);
			}
		}
	}

	public function addFromRow($aRow) {
		$this->addFromCols($aRow["cols"]);
	}

	public function addFromRows ( $rows ) {
		if(is_array($rows)) {	
			foreach($rows as $aRow) {
				$this->addFromRow($aRow);
			}
		}
	}
	
	
	public function clear() {
		$this->errorCode	= 0;
		$this->errorMessage	= "";
		$this->errorFields	= array();
		$this->retByID		= array();
		$this->retByKey		= array();
		$this->colArray		= array();
		$this->hasError		= false;
	}
	
	public function setError() {
		$args = func_get_args();
		$this->errorCode 	= $args[0];
		if($args[1]) $this->errorMessage = $args[1];
		if($args[1]) $this->errorFields	 = $args[2];
		$this->hasError		= intval($this->errorCode)>0?true:false;
		//echo "<br>code:" . $this->errorCode . "<br>Message:" . $this->errorMessage . "<br>Fields:" . $this->errorFields . "<br>hasError:" . $this->hasError . "<br>";
	}
	
	public function pushError( $obj ) {
		array_push($this->errorFields, $obj);
	}
	
	public function getError() {
		$errObj = array();
		$errObj["hasError"] 	= $this->hasError;
		$errObj["errorCode"]	= $this->errorCode;
		$errObj["errorMessage"]	= $this->errorMessage;
		$errObj["errorFields"]	= $this->errorFields;
		return $errObj;
	}
	
	public function validate() {
		foreach($this->colArray as $colName=>$colVal) {
				if( is_array($colVal) ) {		  
						$ff = array();
						$ff["dataID"]	= $colVal["dataID"];
						$ff["pageID"]	= $colVal["pageID"];
						$ff["rowID"]	= $colVal["rowID"];
						$ff["eid"] 		= $colVal["eid"];
						$ff["eid"] 		= $colVal["eid"];
						$ff["etype"] 	= $colVal["etype"];
						$ff["elength"] 	= $colVal["elength"];
						$ff["enull"] 	= $colVal["enull"];
						$ff["ename"] 	= $colVal["ename"];
						$ff["etitle"] 	= $colVal["etitle"];
						$ff["evalue"] 	= trim($colVal["evalue"]);
						$ff["ovalue"] 	= trim($colVal["ovalue"]);
						$ff["emem1"] 	= $colVal["emem1"];

						$ff["errno"] 	= 0;
						$ff["errmsg"] 	= "";
						
						if( $ff["evalue"] == "" ) {
							// not null
							if($ff["enull"]) {
								$this->setError(1001);
								$ff["errno"] += 1;
							}
						} else {
							// maxlength
							if($ff["elength"] > 0) {
								if( strlen($ff["evalue"]) > $ff["elength"] ) {
									$this->setError(1001);
									$ff["errno"] += 2;
								}
							}
							if($ff["etype"] != "") {
									if( $this->dataType[$ff["etype"]] != "" ) {
										  if( !preg_match($this->dataType[$ff["etype"]],$ff["evalue"]) ) {
											  $this->setError(1001);
											  $ff["errno"] += 4;
										  }
									} else {
											  $this->setError(1001);
											  $ff["errno"] += 8;
									}
							}
						}
					  
						if( $ff["errno"] > 0 ) {$this->pushError($ff); }
						$this->retByID[$ff["eid"]][$colName]["evalue"] = trim($colVal["evalue"]);
						$this->retByID[$ff["eid"]][$colName]["ovalue"] = trim($colVal["ovalue"]);
						$this->retByKey[$colName]["evalue"] = trim($colVal["evalue"]);
						$this->retByKey[$colName]["ovalue"] = trim($colVal["ovalue"]);
				}  else {
						$this->retByID[$colName] 	= $colVal;
						$this->retByKey[$colName] 	= $colVal;
				}// end of is_array
			}
	}
	
	public function getDataByID() {
		return $this->retByID;
	}

	public function getDataByKey() {
		return $this->retByKey;
	}
	
	private $dataType = array(
			"EMAIL"		=> "/^(?:\w+\.?)*\w+@(?:\w+\.)+\w+$/i", 	//Email :  abd_dkkd.dkfd-dkd@hotmail.adk-dkdk.gc.ca
			"EMAILS"	=> "/^(?:(?:\w+\.?)*\w+@(?:\w+\.)+\w+(\s*,\s*)?)+$/i",  		// a@a.com, b@b.com, c@c.com
			"ALL"		=> "/^(?:.|\s)*$/i", 						// all chars
			"CHAR"		=> "/^.*$/i", 								// all chars except \n\r 
			"LETTER"	=> "/^[a-zA-Z'\",\._ ]*$/i",
			"NUMBER"	=> "/^[+-]?[0-9]*(?:(?:\.)[0-9]+)?(,)?$/i",
			"DATE"		=> "/^(?:19|20)[0-9]{2}(?:-|\/)(?:1[0-2]|0?[1-9])(?:-|\/)(?:3[01]|[0-2]?[0-9])$/i",
			"TIME"		=> "/^((2[0-3]|[01]?[0-9])(:[0-5][0-9](:[0-5][0-9])?)?[ ]*(am|pm)?)$/i",
			"DATETIME"	=> "/^(?:19|20)[0-9]{2}(?:-|\/)(?:1[0-2]|0?[1-9])(?:-|\/)(?:3[01]|[0-2]?[0-9])[ ]+((2[0-3]|[01]?[0-9])(:[0-5][0-9](:[0-5][0-9])?)?[ ]*(am|pm)?)$/i"
	);
}

class CROW {
	public $rows 	= array();
	public $heads 	= array();
	private $rowID	= -1;
	public function pushHead( $hArr ) {
		if( is_array($hArr) ) {
				$head				= array();
				// inmport for three items
				$head["ename"] 		= isset($hArr["ename"])		?$hArr["ename"]:"";
				$head["etitle"] 	= isset($hArr["etitle"])	?$hArr["etitle"]:"";
				$head["etype"] 		= isset($hArr["etype"])		?$hArr["etype"]:"ALL";
				
				$head["elen"] 		= isset($hArr["elen"])		?$hArr["elen"]:0;
				$head["enull"] 		= isset($hArr["enull"])		?$hArr["enull"]:0;
		
				$head["width"] 		= isset($hArr["width"])		?$hArr["width"]:120;
				$head["showhide"] 	= isset($hArr["showhide"])	?$hArr["showhide"]:0;
				$head["hide"] 		= isset($hArr["hide"])		?$hArr["hide"]:0;
				$head["nowrap"] 	= isset($hArr["nowrap"])	?$hArr["nowrap"]:1;
				$head["resizable"] 	= isset($hArr["resizable"])	?$hArr["resizable"]:1;
				$head["sortable"] 	= isset($hArr["sortable"])	?$hArr["sortable"]:1;
				$head["defsq"] 		= isset($hArr["defsq"])		?$hArr["defsq"]:"ASC";
				
				$this->heads[$head["ename"]] = $head;
		}
	}
	
	public function pushRow( $rArr ) {
		if( is_array($rArr) ) {
				$rowArr = array();
				$rowArr["dataID"]				= isset($rArr["dataID"])	?$rArr["dataID"]:0;
				$rowArr["pageID"]				= isset($rArr["pageID"])	?$rArr["pageID"]:0;
				$rowArr["rowID"] 				= isset($rArr["rowID"])		?$rArr["rowID"]:count($this->rows);
				$rowArr["recordID"] 			= isset($rArr["recordID"])	?$rArr["recordID"]:-1;
				$rowArr["flag"] 				= isset($rArr["flag"])		?$rArr["flag"]:0;
				
				$this->rowID					= $rowArr["rowID"];
				$this->rows[$rowArr["rowID"]]	= $rowArr;
		}
	}
	
	public function pushCol( $cArr ) {
		if( is_array($cArr) ) {
				$colArr				= array();
				// must have: rowID , ename, evalue, ovalue
				$rowID 				= isset($cArr["rowID"])?$cArr["rowID"]:$this->rowID;
				$colArr["ename"]	= isset($cArr["ename"])?$cArr["ename"]:"";
				$colArr["evalue"] 	= isset($cArr["evalue"])?$cArr["evalue"]:"";
				$colArr["ovalue"] 	= isset($cArr["ovalue"])?$cArr["ovalue"]:"";;
				$colArr["emem1"]  	= isset($cArr["emem1"])?$cArr["emem1"]:"";
		
				// get value from row
				$colArr["dataID"]	= $this->rows[$rowID]["dataID"];
				$colArr["pageID"]	= $this->rows[$rowID]["pageID"];
				$colArr["rowID"] 	= $rowID;
				$colArr["eid"] 		= $this->rows[$rowID]["recordID"];
				
				// get value from header
				$head				= $this->heads[$colArr["ename"]];
				$colArr["etitle"]	= $head["etitle"];
				$colArr["etype"] 	= $head["etype"];
				$colArr["elen"] 	= $head["elen"];
				$colArr["enull"] 	= $head["enull"];
				
				$this->rows[$rowID]["cols"][$colArr["ename"]] = $colArr;
		}
	}
	
	public function getHeads() {
		return $this->heads;
	}
	
	public function getRows() {
		return $this->rows;
	}

	public function getRow($row_id) {
		return $this->rows[$row_id];
	}
	
}
?>
