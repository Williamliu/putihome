<?php
/***********************************************************************************************/
/*																							   */
/***********************************************************************************************/
class iHTML {
    //  iHTML::checkbox( "en", $db, "vw_vol_professional", "education", "", 3, 1, 1); 
    /*
        $ccc = array();
        $ccc["table"] = "pt_volunteer_degree";
        $ccc["output"] = "degree_id";
        $ccc["criteria"]["volunteer_id"] = 10;
        iHTML::checkbox( "en", $db, "vw_vol_professional", "education", $ccc, 3, 1, 1); 
    */

	static public function checkbox() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
        $lang       = $params[0]?$params[0]:"en";
        $dbHTML     = $params[1];   
		$table_name = $params[2];  		
		$el_name    = $params[3];        
        $values     = $params[4];        
        $ccnt       = $params[5]?$params[5]:1;
        $isTable    = $params[6]?$params[6]:0;   
        $numberable = $params[7]?$params[7]:0;
          
        $vals = array();        
        if(is_array($values)) {
            if($values["table"]!="") {
                $vals = $dbHTML->attrs($dbHTML->select($values["table"], $values["output"], $values["criteria"]) , $values["output"]);
            } else {
                $vals = $values;
            }
        } else {
            $vals = explode(",", $values);
        }

        $query_html     = "SELECT * FROM $table_name WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, created_time ASC";
        $result_html    = $dbHTML->query($query_html); 
        $rows = $dbHTML->rows($result_html);
        
        if($isTable) 
            $html = '<table border="0" cellpadding="0" cellspacing="0">';
        else 
            $html = '';

        $cnt=0;
		$cno=0;

		foreach( $rows as $row ) {
			$cno++;
			if($cnt <= 0) {
				if($isTable) 
                    $html .= '<tr>';
                else
                    $html .= '<span style="vertical-align:middle; margin-left:0px;">';
			}
			$cnt++;
			
            $hdesc  = $lang=="en"?$row["desc_en"]:cTYPE::gstr($row["desc_cn"]);
            $htitle = $lang=="en"?$row["title_en"]:cTYPE::gstr($row["title_cn"]);

            if($isTable) $html .= '<td>';
			$html .= '<input type="checkbox" style="vertical-align:middle; margin-left:10px;" id="' . $el_name . '_' . $row["id"] . '" name="' . $el_name . '" ' . ( in_array($row["id"], $vals)?'checked="checked"':'').  ' class="' . $el_name . '"  value="' . $row["id"] . '">';
            $html .= '<label for="' . $el_name . '_' . $row["id"] . '" title="' . $hdesc . '">' . ($numberable?$cno . '. ':'') .  $htitle . '</label>';
            if($isTable) $html .= '</td>';

			if($cnt >= $ccnt) {
				$cnt = 0;
				if($isTable) 
                    $html .= '</tr>';
                else
                    $html .= '</span><br>';
			}
		}
        if($isTable) {
            if($cnt > 0 && $cnt < $ccnt) $html .= '</tr>';
            $html .= '</table>';
        }

		return $html;
	}

	static public function radio() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
        $lang       = $params[0]?$params[0]:"en";
        $dbHTML     = $params[1];   
		$table_name = $params[2];  		
		$el_name    = $params[3];        
        $values     = $params[4];        
        $ccnt       = $params[5]?$params[5]:1;
        $isTable    = $params[6]?$params[6]:0;   
        $numberable = $params[7]?$params[7]:0;
          
        $val = '';        
        if(is_array($values)) {
            $val = $dbHTML->getVal($values["table"], $values["output"], $values["criteria"]);
        } else {
            $val = $values;
        }

        $query_html     = "SELECT * FROM $table_name WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, created_time ASC";
        $result_html    = $dbHTML->query($query_html); 
        $rows = $dbHTML->rows($result_html);
        
        if($isTable) 
            $html = '<table border="0" cellpadding="0" cellspacing="0">';
        else 
            $html = '';

        $cnt=0;
		$cno=0;

		foreach( $rows as $row ) {
			$cno++;
			if($cnt <= 0) {
				if($isTable) 
                    $html .= '<tr>';
                else
                    $html .= '<span style="vertical-align:middle; margin-left:0px;">';
			}
			$cnt++;
			
            $hdesc  = $lang=="en"?$row["desc_en"]:cTYPE::gstr($row["desc_cn"]);
            $htitle = $lang=="en"?$row["title_en"]:cTYPE::gstr($row["title_cn"]);

            if($isTable) $html .= '<td>';
			$html .= '<input type="radio" style="vertical-align:middle; margin-left:10px;" id="' . $el_name . '_' . $row["id"] . '" name="' . $el_name . '" ' . ($row["id"]==$val?'checked="checked"':'') .  ' class="' . $el_name . '"  value="' . $row["id"] . '">';
            $html .= '<label for="' . $el_name . '_' . $row["id"] . '" title="' . $hdesc . '">' . ($numberable?$cno . '. ':'') .  $htitle . '</label>';
            if($isTable) $html .= '</td>';

			if($cnt >= $ccnt) {
				$cnt = 0;
				if($isTable) 
                    $html .= '</tr>';
                else
                    $html .= '</span><br>';
			}
		}
        if($isTable) {
            if($cnt > 0 && $cnt < $ccnt) $html .= '</tr>';
            $html .= '</table>';
        }

		return $html;
    }

	static public function select() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
        $lang       = $params[0]?$params[0]:"en";
        $dbHTML     = $params[1];   
		$table_name = $params[2];  		
		$el_name    = $params[3];        
        $val         = $params[4];        
        $numberable = $params[5]?$params[5]:0;

        $query_html     = "SELECT * FROM $table_name WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, created_time ASC";
        $result_html    = $dbHTML->query($query_html); 
        $rows = $dbHTML->rows($result_html);
        
        $html = '<select id="' . $el_name . '" name="' . $el_name . '">';
        $html .= '<option value="0"></option>';

        $cnt = 0;
		foreach( $rows as $row ) {
            $cnt++;
            $hdesc  = $lang=="en"?$row["desc_en"]:cTYPE::gstr($row["desc_cn"]);
            $htitle = $lang=="en"?$row["title_en"]:cTYPE::gstr($row["title_cn"]);
            $htitle = ($numberable?$cnt . '. ':'') . $htitle;
            $html .= '<option value="' . $row["id"] . '" title="' . $hdesc . '"' . ($row["id"]==$val?' selected':'') . '>' . $htitle . '</option>';
        }
        $html .= '<select>';
		return $html;
    }

	static public function select1() {
		$pnum 	= func_num_args();
		$params	= func_get_args();
		
        $lang       = $params[0]?$params[0]:"en";
        $dbHTML     = $params[1];   
		$table_name = $params[2];  		
		$el_name    = $params[3];        
        $val         = $params[4];        
        $numberable = $params[5]?$params[5]:0;

        $query_html     = "SELECT * FROM $table_name WHERE deleted <> 1 AND status = 1 ORDER BY sn DESC, created_time ASC";
        $result_html    = $dbHTML->query($query_html); 
        $rows = $dbHTML->rows($result_html);
        
        $html = '<select id="' . $el_name . '" name="' . $el_name . '">';
        $html .= '<option value=""></option>';

        $cnt = 0;
		foreach( $rows as $row ) {
            $cnt++;
            $hdesc  = $lang=="en"?$row["desc_en"]:cTYPE::gstr($row["desc_cn"]);
            $htitle = $lang=="en"?$row["title_en"]:cTYPE::gstr($row["title_cn"]);
            $htitle = ($numberable?$cnt . '. ':'') . $htitle;
            $html .= '<option value="' . $row["id"] . '" title="' . $hdesc . '"' . ($row["id"]==$val?' selected':'') . '>' . $htitle . '</option>';
        }
        $html .= '<select>';
		return $html;
    }

}
?>
