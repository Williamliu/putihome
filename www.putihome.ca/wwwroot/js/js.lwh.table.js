LWH.cTABLE = function(opts) {
	this.tabData = {};
	this.general = {
		me:			"",
		container:	"",
		recoTotal:	0,
		pageTotal:	0,
		pageNo:		0,
		pageSize:	0
	};
	this.condition = {};
	this.settings = {
		admin_sess: 	"",
		admin_menu:		"",
		admin_oper:		"",
		
		url:			"",
		condition: 		{},   // search condition + pageSize + orderBY + orderSQ
		headers:		[],   // {title:"SN", 			col:"first_name", 	sq:"ASC", 	width:20},
					
		container:		"",   // table container
		me:				"",   // self js object
		pageNo:			1,
		pageSize:		20,
		orderBY:		"created_time",
		orderSQ:		"DESC",
		
		cache:			false,
		expire:			3600 ,  // seconds
		headRows:		null,
		pageRows:   	null,
		pageDONE:		null,
		ajaxDONE:		null,		
		button:		true,
		view:		true,
		output:		true,
		remove:		true
	};

	$.extend(this.settings, opts);
	var conObj = {};
	for(var key in this.settings.condition) {
		conObj[key] = $(this.settings.condition[key]).val();
	}
	
	this.tabData.pageNo				= this.settings.pageNo;  // pageNo not use for condition compare

	this.tabData.condition 			= this.clone(conObj);
	// add below three fields to condition compare.
	this.tabData.condition.orderBY 	= this.settings.orderBY;
	this.tabData.condition.orderSQ 	= this.settings.orderSQ;
	this.tabData.condition.pageSize	= this.settings.pageSize;
	
	this.condition					= this.clone(conObj);
	this.condition.orderBY 			= this.settings.orderBY;
	this.condition.orderSQ 			= this.settings.orderSQ;
	this.condition.pageSize			= this.settings.pageSize;
	
	var dt = new Date();
	this.tabData.timestamp  = dt.getTime();
	this.tabData.pages 		= [];
	
	this.general.container	= this.settings.container;
	this.general.me 		= this.settings.me;
	this.general.cache 		= this.settings.cache;
	this.general.expire 	= this.settings.expire;
	this.general.pageNo		= this.settings.pageNo;
	this.general.pageSize	= this.settings.pageSize;
	
	this.others				= null;
	
	var _self = this;
	var _constructor = function() {
		$("tr[rid]", _self.settings.container ).live("mouseover", function(ev) {
			//alert("hover");
			$("tr[rid]", _self.settings.container).removeClass("tr-highlight");
			$(this).addClass("tr-highlight");
		});
		//alert( showObj( _self.settings.condition ) );
		//alert("con:" + _self.settings.condition.length + " orders:" + _self.settings.orders.length + "  fields:" + _self.settings.fields.first_name); 
		//_self.getData();
		
	};
	_constructor();
}

LWH.cTABLE.prototype = {
	getData: function() {
				  var _self = this;
				  if( _self.compareCondition() ) {
						//condition true
						//alert("Condition true");
						if(_self.general.cache ) {
							  var ts = new Date();
							  if( Math.round((ts.getTime() - _self.tabData.timestamp)/1000) > _self.general.expire ) {
								  _self.resetTabData();
							  } else { // not expire
								  var pgNo 	= _self.tabData.pageNo;
								  if(_self.tabData.pages[pgNo]) {
									  switch(_self.tabData.pages[pgNo].status) {
										  case 0:
											  	break;
										  case 1:
											  	return;
										  case 2:
										  		_self.general.pageNo 	= pgNo;
												_self.headerHTML();
												_self.pageHTML(_self.tabData.pages[pgNo]);
												return;
									  }							
								  } else {
									  _self.resetPageData(pgNo);
								  }
							  }
						} else {  // no cache
							  var pgNo 	= _self.tabData.pageNo;
							  _self.tabData.pages = [];
							  _self.resetPageData(pgNo);
						}
						// end condition true
				  } else {
					  	// condition false
						//alert("Condition false");
						_self.resetTabData();
				  }
				   
				  _self.tabData.pages[_self.tabData.pageNo].status = 1;
				  if( $("#wait").length > 0 && $("#wait").is(":hidden") ) $("#wait").loadShow(); 
				  $.ajax({
					  data: {
						  admin_sess: 	_self.settings.admin_sess,
						  admin_menu:	_self.settings.admin_menu,
						  admin_oper:	_self.settings.admin_oper,

						  pageNo:		_self.tabData.pageNo,
						  pageSize:		_self.tabData.condition.pageSize,
						  orderBY:		_self.tabData.condition.orderBY,
						  orderSQ:		_self.tabData.condition.orderSQ,
						  condition:	_self.tabData.condition
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  if( $("#wait").length > 0 && $("#wait").is(":visible") ) $("#wait").loadHide(); 
						  alert("Error (" + _self.settings.url + "): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
						  if( $("#wait").length > 0 && $("#wait").is(":visible") ) $("#wait").loadHide(); 
						  if( req.errorCode > 0 ) { 
							  errObj.set(req.errorCode, req.errorMessage, req.errorField);
							  return false;
						  } else {
							  // after getData , it do synchorzie data.
							  var pgNo = req.data.general.pageNo;
							  $.extend(_self.general,req.data.general);
							  $.extend(_self.tabData.condition,req.data.condition);
							  _self.condition = _self.clone(_self.tabData.condition);
							  //alert(showObj(_self.condition));

							  // using general data to build page navigator.
							  _self.tabData.pages[pgNo].status = 2;
							  _self.tabData.pages[pgNo].rows = req.data.rows;
							  
							  if(req.data.others) {
							  		_self.others = req.data.others;
							    	_self.headerHTML(_self.others);
							  } else { 
							  		_self.others = null;
							    	_self.headerHTML();
							  }
							 
							  _self.pageHTML(_self.tabData.pages[pgNo]);
							 
							  if(_self.settings.pageDONE && $.isFunction(_self.settings.pageDONE)) {
								  _self.settings.pageDONE(_self.tabData.pages[pgNo]);
							  }

							  if(_self.settings.ajaxDONE && $.isFunction(_self.settings.ajaxDONE)) {
								  _self.settings.ajaxDONE(req);
							  }
							  
						  }
					  },
					  type: "post",
					  url: _self.settings.url
				  });
	},

	compareCondition: function() {
		for(var key in this.settings.condition) {
			this.tabData.condition[key] = $(this.settings.condition[key]).val();
		}

		var flag = true;
		for(var key in this.tabData.condition) {
			if(this.tabData.condition[key] != this.condition[key]) {
				flag = false;
				break;
			}
		}
		return flag;
	},

	
	goPage: function(pgNo) {
		this.tabData.pageNo	= pgNo;
		this.getData();
	},
	
	sortBy: function() {
		var sort_col 	= arguments[0];
		var def_sq 	= "ASC";
		if(arguments[1]) { 
			def_sq = arguments[1].toUpperCase();
		}
		
		if( this.tabData.condition.orderBY == sort_col ) {
			if( this.tabData.condition.orderSQ == "ASC" ) {
				this.tabData.condition.orderSQ = "DESC";
			} else {
				this.tabData.condition.orderSQ = "ASC";
			}
		} else {
			this.tabData.condition.orderBY = sort_col;
			this.tabData.condition.orderSQ = def_sq.toUpperCase();
		}
		this.getData();
	},
	setCondition: function(con) {
		$.extend(this.tabData.condition, con);
		this.resetTabData();
		this.getData();
	},
	
	start: function() {
		this.resetTabData();
		this.getData();
	},
	
	fresh: function() {
		  this.general.recoTotal = 0;
		  this.general.pageTotal = 0;
		  this.general.pageNo	 = this.tabData.pageNo;
		  this.general.pageSize  = this.tabData.condition.pageSize;

		  this.tabData.pages = [];
		  var dt = new Date();
		  this.tabData.timestamp  = dt.getTime();

		  this.getData();
	},
	resizePage: function(pSize) {
		var pgSize = parseInt(pSize);
		this.tabData.condition.pageSize = pgSize;
		
		this.getData();
	},
	
	resetPageData: function(pgNo) {
		  this.tabData.pages[pgNo] = null;
		  this.tabData.pages[pgNo] = {};
		  this.tabData.pages[pgNo].status = 0;
		  this.tabData.pages[pgNo].pageNo = pgNo;
		  this.tabData.pages[pgNo].rows = [];
	},

	resetTabData: function() {
		  this.tabData.pageNo 	 = 1;

		  this.general.recoTotal = 0;
		  this.general.pageTotal = 0;
		  this.general.pageNo	 = this.tabData.pageNo;
		  this.general.pageSize  = this.tabData.condition.pageSize;

		  this.tabData.pages = [];
		  var dt = new Date();
		  this.tabData.timestamp  = dt.getTime();
		  this.resetPageData(1);
	},
	
	headerHTML: function(others) {
   		  var _self = this;
		  
		  var oGen = this.general;
		  var html_nav = '';
		  html_nav += '<div class="tabQuery-dbnav-background">';
		  html_nav += '<span style="float:left;">';
		  html_nav += '<span style="color: black; font-size:11px; font-weight:bold; vertical-align:middle; margin-left:10px;">' + words["page"] + ': </span>'; 
		  html_nav += '<select style="vertical-align:middle; height:22px; text-align:center; z-index:99999;" onchange="' + oGen.me + '.goPage(this.value);">';
		  for(var i = 1; i <= oGen.pageTotal; i++) {
			  html_nav += '<option value="' + i + '" ' + (parseInt(oGen.pageNo)==i?" selected":"") + '>' + i + '</option>';
		  }
		  html_nav += '</select>';
		  html_nav += '<span style="color:black; font-size:11px; font-weight:bold;"> ' + words["page of"] + ' ' + oGen.pageTotal + '</span> ';
		  html_nav += '<span style="color:black; font-size:1.1em; font-weight:bold; margin-left:5px; margin-right:5px;">|</span>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-first" title="First"></a>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-prev" 	title="Previous"></a>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-next" 	title="Next"></a>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-last" 	title="Last"></a>';
		  html_nav += '<span style="color:black; font-size:1.1em; font-weight:bold; margin-left:5px; margin-right:2px;">|</span>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-loading  tabQuery-dbnav-loading-na" title="LOADING..."></a>';
		  html_nav += '<span style="color:black; font-size:14px;">' + words["page records total"] + ': <span class="tabQuery_recoTotal">' + oGen.recoTotal + '</span></span>';
		  html_nav += '</span>';
		  html_nav += '<span style="color: black; font-size:11px; font-weight:bold; vertical-align:middle; float:right; margin-right:10px;">' + words["page size"] + ': '; 
		  html_nav += '<input type="text" id="tabQuery_pageSize" style="vertical-align:middle; text-align:center; width:30px;" value="' + oGen.pageSize + '" />';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-go" title="Go"></a></span>'; 
		  html_nav += '</div>';
		  $(oGen.container).empty().prepend(html_nav);		  
		  // verify navi icon status
		  if( oGen.pageTotal >= 1 ) {
			  if( oGen.pageNo==1 ) {
				  $(".tabQuery-dbnav-first", oGen.container).addClass("tabQuery-dbnav-first-na");
				  $(".tabQuery-dbnav-prev", oGen.container).addClass("tabQuery-dbnav-prev-na");
			  } 
			  if( oGen.pageNo  >= oGen.pageTotal ) {
				  $(".tabQuery-dbnav-next", oGen.container).addClass("tabQuery-dbnav-next-na");
				  $(".tabQuery-dbnav-last", oGen.container).addClass("tabQuery-dbnav-last-na");
			  }
		  }else {
			  $(".tabQuery-dbnav-first", oGen.container).addClass("tabQuery-dbnav-first-na");
			  $(".tabQuery-dbnav-prev", oGen.container).addClass("tabQuery-dbnav-prev-na");
			  $(".tabQuery-dbnav-next", oGen.container).addClass("tabQuery-dbnav-next-na");
			  $(".tabQuery-dbnav-last", oGen.container).addClass("tabQuery-dbnav-last-na");
			  $(".tabQuery-dbnav-go", oGen.container).addClass("tabQuery-dbnav-go-na");
		  }
		  // end of verify
		  
		  // setup click event for navi icons
		  $(".tabQuery-dbnav-first", oGen.container).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-first-na") ) {
				  var pgNO = 1;
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	  
		  $(".tabQuery-dbnav-prev", oGen.container).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-prev-na") ) {
				  var pgNO = parseInt(oGen.pageNo) - 1;
				  if( pgNO < 1 ) {
					  pgNO = 1;
				  } 
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	  
		  $(".tabQuery-dbnav-next", oGen.container).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-next-na") ) {
				  var pgNO = parseInt(oGen.pageNo) + 1;
				  if( pgNO > oGen.pageTotal ) {
					  pgNO = oGen.pageTotal;
				  } 
				  
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	  
		  $(".tabQuery-dbnav-last", oGen.container).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-last-na") ) {
				  var pgNO = oGen.pageTotal;
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });


		  $(".tabQuery-dbnav-go", oGen.container).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-go-na") ) {
				  var pgSize = $("#tabQuery_pageSize", $(this).parent()).val();
				  eval(oGen.me + ".resizePage(pgSize)");
				  ev.preventDefault();
				  return false;
			  }
		  });
		
		// table below
		var html_header = '<table class="tabQuery-table" border="1" cellpadding="1" cellspacing="0" orderby="' + this.tabData.condition.orderBY + '" ordersq="' + this.tabData.condition.orderSQ + '">';
		// only add TD
		if(this.settings.headRows && $.isFunction(this.settings.headRows)) {
				if( _self.others ) 
					html_header += this.settings.headRows(this.settings.headers, _self.others);
				else 
					html_header += this.settings.headRows(this.settings.headers);
		} else {
				html_header += '<tr class="tabQuery-headers" rid="header">';
				for(var key in this.settings.headers) {
						var ff = this.settings.headers[key];
						if(ff.width && ff.width>0) 
							html_header += '<td class="tabQuery-table-header" width="' + ff.width + '">';
						else 
							html_header += '<td class="tabQuery-table-header">';
		
						html_header += ff.title;
						if(ff.sq && ff.sq!="") {
							var order_css = '';
							if(ff.col == this.tabData.condition.orderBY) {
								order_css = ' tabQuery-sort-' + this.tabData.condition.orderSQ.toLowerCase();
							}
							html_header += ' <a class="tabQuery-sort' + order_css + '" orderby="' + ff.col + '" defsq="' + (ff.sq!=""?ff.sq:"ASC") + '"></a>';
						}
						html_header += '</td>';
				}
				html_header += '</tr>';
		} // end of if headRows

		html_header += '</table>';
		$(oGen.container).append(html_header);		
		$(".tabQuery-sort", oGen.container).unbind("click").bind("click", function(ev) {
			_self.sortBy($(this).attr("orderby"), $(this).attr("defsq"));
			ev.preventDefault();
			return false;
		});

	},
	
	pageHTML: function(pgData) {
		var html = '';
		if(this.settings.pageRows && $.isFunction(this.settings.pageRows)) {
			  html = this.settings.pageRows(pgData);
		} else {
			  html = this.defaultPageHTML(pgData);
		}
		$("table.tabQuery-table", this.general.container).append(html);
	},
	
	defaultPageHTML: function(pgData) {
		var html = '';
		for(var idx in pgData.rows) {
			var tr_css = idx%2==0?'tr-even':'tr-odd';
			html += '<tr class="' + tr_css + '" rowno="' + idx + '" rid="' + (pgData.rows[idx]["id"]?pgData.rows[idx]["id"]:-1) + '">';
			for(var key in this.settings.headers) {
				var ff = this.settings.headers[key];
				if(ff.col && ff.col!="hidden")	{
					if(ff.col == "rowno") {
						html += '<td align="center">';
						html += parseInt(idx) + 1;
						html += '</td>';
					} else {
						var tmp = '';
						if(ff.align && ff.align!="") 	tmp = ' align="' + ff.align + '"'; 
						if(ff.valign && ff.valign!="") 	tmp += ' valign="' + ff.valign + '"'; 
						html += '<td class="' + ff.col + '" '+ tmp +'>';
						html += pgData.rows[idx][ff.col];
						html += '&nbsp;</td>';
					}
				}
			}

			if( this.settings.button) {
				html += '<td width="auto" style="white-space:nowrap;" align="center">';
				if( this.settings.view) 	html += '<a class="tabQuery-button tabQuery-button-view" 	oper="view" 	right="view" 	pid="' + this.tabData.pageNo + '" rsn="' + idx + '"	rid="' + pgData.rows[idx]["id"] + '" title="' + words["view details"] + '"></a>';
				if( this.settings.output)	html += '<a class="tabQuery-button tabQuery-button-output" 	oper="print" 	right="print" 	pid="' + this.tabData.pageNo + '" rsn="' + idx + '"	rid="' + pgData.rows[idx]["id"] + '" title="' + words["print details"] + '"></a>';
				if( this.settings.remove)	html += '<a class="tabQuery-button tabQuery-button-delete" 	oper="delete" 	right="delete" 	pid="' + this.tabData.pageNo + '" rsn="' + idx + '"	rid="' + pgData.rows[idx]["id"] + '" title="' + words["delete record"] + '" style="margin-left:3px;"></a>';
				html += '</td>';
			}
			html += '</tr>';
		}
		return html;
	},
	
	clone: function( obj ) {
		var newObj = {};
		for(var key in obj) {
			eval( "newObj." + key + " = obj[key]");
		}
		return newObj;
	}
}