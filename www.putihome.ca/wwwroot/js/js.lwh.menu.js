LWH.CMENU = function(str_menu , str_right, str_user) {
	this.menu = null;
	this.right = null;
	this.user = null;
	var _self = this;
	
	var _constructor = function(smenu, sright, suser) {
		try {
			_self.menu  = $.parseJSON(smenu);
		} catch(e) {
			_self.menu 	= null;
		}
		try {
			_self.right  = $.parseJSON(sright);
		} catch(e) {
			_self.right 	= null;
		}
		try {
			_self.user  = $.parseJSON(suser);
		} catch(e) {
			_self.user 	= null;
		}
		_self.setRight(_self.user);

	};
	_constructor(str_menu, str_right, str_user);
}

LWH.CMENU.prototype = {
	setMenu: function(smenu) {
		try {
			this.menu  = $.parseJSON(smenu);
		} catch(e) {
			this.menu 	= null;
		}
	},
	setRight: function(sright) {
		try {
			this.right  = $.parseJSON(sright);
		} catch(e) {
			this.right 	= null;
		}
	},
	toHTML: function() {
		var html = '';
		if(this.menu) {
			  html += '<ul id="website_menu_right" class="lwhTree" style="padding:0px; margin:0px;">';
			  for(var key0 in this.menu.menu) {
				  if(this.menu.menu[key0].menu)  {
					  var menu0 = this.menu.menu[key0]; 
					  html += '<li class="nodes nodes-close">';
					  html += '<s class="node-line"></s><s class="node-img"></s>';
					  html += menu0.name;
					  //////// menu right ///////////////////////////////////////
					  html += '<span class="span-menu-right" mid="' + key0 + '">';
					  for(var rkey0 in this.right.right[key0]) {
						  if(rkey0 != "right") {
							  var rgt_check = this.right.right[key0][rkey0]?"checked":"";
							  html += '<input type="checkbox" class="ck-right" mid="' + key0 + '" right="' + rkey0 + '" ' + rgt_check + ' value="1" />';
							  html += words[rkey0];
						  }
					  }
					  html += '</span>';
					  ///////////////////////////////////////////////////////////
					  html += '<ul class="lwhTree">';
					  for(var key1 in menu0.menu) {
						  html += '<li class="node"><s class="node-line"></s><s class="node-img"></s>';
						  if( menu0.menu[key1].title == "category" )
						  	html += '<span style="color:#CD6868; font-weight:bold;">' + menu0.menu[key1].name + '</span>';
						  else 
						  	html += menu0.menu[key1].name;
						  
						  /////// submenu right /////////// 
						  html += '<span class="span-menu-right" mid="' + key0 + '" sid="' + key1 + '">';
						  for(var rkey0 in this.right.right[key0].right[key1]) {
							  if(rkey0 != "right") {
								  var rgt_check = this.right.right[key0].right[key1][rkey0]?"checked":"";
								  html += '<input type="checkbox" class="ck-right" mid="' + key0 + '" sid="' + key1 + '" right="' + rkey0 + '" ' + rgt_check + ' value="1" />';
								  html += words[rkey0];
							  }
						  }
						  html += '</span>';
						  //////////////////////////////// 
						  html += '</li>';
					  }
					  html += '</ul>';
					  html += '</li>';
				  } else {
					  var menu0 = this.menu.menu[key0]; 
					  html += '<li class="node">';
					  html += '<s class="node-line"></s><s class="node-img"></s>';
					  html += menu0.name;
					  ////////// menu right /////////////////////
					  html += '<span class="span-menu-right" mid="' + key0 + '">';
					  for(var rkey0 in this.right.right[key0]) {
						  if(rkey0 != "right") {
							  var rgt_check = this.right.right[key0][rkey0]?"checked":"";
							  html += '<input type="checkbox" class="ck-right" mid="' + key0 + '" right="' + rkey0 + '" ' + rgt_check + ' value="1" />';
							  html += words[rkey0];
						  }
					  }
					  html += '</span>';
					  //////////////////////////////////////////
					  html += '</li>';
				  }
			  } 
			  html += '</ul>';
		}
		return html;
	},
	getRight: function() {
		var _self = this;
		$("input:checkbox[mid]").not("[sid]").each(function(idx0,el0) {
				_self.right.right[$(el0).attr("mid")][$(el0).attr("right")] = $(el0).is(":checked")?1:0; 
		});
		$("input:checkbox[mid]").filter("[sid]").each(function(idx0,el0) {
				_self.right.right[$(el0).attr("mid")].right[$(el0).attr("sid")][$(el0).attr("right")] = $(el0).is(":checked")?1:0; 
		});
		return _self.right;
	},
	
	setRight: function(juser) {
		var _self = this;
		if($.isPlainObject(juser)) {
			_self.user = juser;
		} else {
			try {
				_self.user  = $.parseJSON(juser);
			} catch(e) {
				_self.user 	= null;
			}
		}
		// here set right 
		for(var key0 in _self.right.right) {
			 for(var key1 in _self.right.right[key0]) {
				if(key1 != "right") {
					if(_self.user && _self.user.right && _self.user.right[key0] && _self.user.right[key0][key1] ) 
						_self.right.right[key0][key1] = _self.user.right[key0][key1];
					else 
						_self.right.right[key0][key1] = 0;
				}
			 }
			 if( _self.right.right[key0].right ) {			 
				 for(var key2 in _self.right.right[key0].right) {
					 for(var key3 in _self.right.right[key0].right[key2]) {
						if(_self.user && _self.user.right && _self.user.right[key0] && _self.user.right[key0].right && _self.user.right[key0].right[key2] && _self.user.right[key0].right[key2][key3]) 
							_self.right.right[key0].right[key2][key3] = _self.user.right[key0].right[key2][key3]?1:0;	 
					 	else 
					 		_self.right.right[key0].right[key2][key3] = 0;
					 }
				 }
			 }
		}
		// end of set right
	}
}