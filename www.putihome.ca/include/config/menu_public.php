<?php 
$menu_public = '{
"menu":
	{
		"m10": {	
				"name": "hello", 
				"title": "world", 
				"tpl": "", 
				"url": "", 
				"type": 1	
		},
		"m20": {	
				"name": "", 
				"title": "", 
				"tpl": "", 
				"url": "", 
				"type": 0,
				"menu": {
						  "m201": {
								  "name": "hello", 
								  "title": "world", 
								  "tpl": "", 
								  "url": "", 
								  "type": 1	
						  },
						  "m202": {
								  "name": "hello", 
								  "title": "world", 
								  "tpl": "", 
								  "url": "", 
								  "type": 1	
						  },
						  "m203": {
								  "name": "hello", 
								  "title": "world", 
								  "tpl": "", 
								  "url": "", 
								  "type": 1	
						  },
						  "m204": {
								  "name": "hello", 
								  "title": "world", 
								  "tpl": "", 
								  "url": "", 
								  "type": 1	
						  }
				}
		},
		"m30": {	
				"name": "", "title": "", 
				"tpl": "", "url": "", 
				"type":	0
		},
		"m40": {	
				"name": "", "title": "", 
				"tpl": "", "url": "", 
				"type": 1	
		},
		"m50": {	
				"name": "", "title": "", 
				"tpl": "", "url": "", 
				"type": 1	
		},
		"m60": {	
				"name": "", "title": "", 
				"tpl": "", "url": "", 
				"type": 1	
		}
	}
}';

$menu_public = str_replace(array(chr(10),chr(13),"\n","\r","\t"), array("","","","",""), $menu_public); 
?>
