LWH.cTABLE = function(opts) {
	$.extend(this.settings, opts);
	
	this.tabData.condition 			= this.clone(this.settings.condition);
	this.tabData.condition.orderBY 	= this.settings.orderBY;
	this.tabData.condition.orderSQ 	= this.settings.orderSQ;
	this.tabData.condition.pageSize	= this.settings.pageSize;
	this.tabData.pageNo				= this.settings.pageNo;  // pageNo not use for condition compare
	
	this.condition					= this.clone(this.settings.condition);
	this.condition.orderBY 			= this.settings.orderBY;
	this.condition.orderSQ 			= this.settings.orderSQ;
	this.condition.pageSize			= this.settings.pageSize;
	
	var dt = new Date();
	this.tabData.timestamp  = dt.getTime();
	this.tabData.pages 		= [];
	
	this.general.me 		= this.settings.me;
	this.general.navi 		= this.settings.navi;
	this.general.tab 		= this.settings.tab;
	this.general.cache 		= this.settings.cache;
	this.general.expire 	= this.settings.expire;
	this.general.pageNo		= this.settings.pageNo;
	this.general.pageSize	= this.settings.pageSize;
	
	
	var _self = this;
	var _constructor = function() {
		//alert( showObj( _self.settings.condition ) );
		//alert("con:" + _self.settings.condition.length + " orders:" + _self.settings.orders.length + "  fields:" + _self.settings.fields.first_name); 
		_self.getData();
		
	};
	_constructor();
}

LWH.cTABLE.prototype = {
	tabData:	{},
	general: 	{
		me:			"",
		navi:		"",
		recoTotal:	0,
		pageTotal:	0,
		pageNo:		0,
		pageSize:	0
	},
	condition: {},
	settings: {
		admin_sess: "",
		admin_menu:	"",
		admin_oper:	"",
		
		url:		"",
		condition: 	{},   // search condition + pageSize + orderBY + orderSQ
		fields:		{},   // { id: "hidden", first_name: "First Name"} hidden not for showing column 
		orders:		{},   // use for local js to create table sortable column
					
		me:			"",   // self js object
		navi:		"",   // navi container
		tab:		"",   // table container
		pageNo:		1,
		pageSize:	20,
		orderBY:	"created_time",
		orderSQ:	"DESC",
		
		butt:		true,
		view:		true,
		prin:		true,
		dele:		true,
		
		cache:		true,
		expire:		3600 ,  // seconds
		
		pageHTML:   null
	},
	
	getData: function() {
				  var _self = this;
				  if( _self.compareCondition() ) {
						//condition true
						//alert("Condition true");
						if(_self.general.cache ) {
							  var ts = new Date();
							  if( Math.round((ts.getTime() - _self.tabData.timestamp)/1000) > _self.general.expire ) {
								  _self.resetTabDate();
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
										   		_self.buildNavi(_self.general);
												if(_self.settings.pageHTML && $.isFunction(_self.settings.pageHTML)) {
													_self.settings.pageHTML(_self.tabData.pages[pgNo]);
												} else {											  	
													_self.pageDataToHTML(_self.tabData.pages[pgNo]);
												}
												return;
									  }							
								  } else {
									  _self.resetPageDate(pgNo);
								  }
							  }
						} else {  // no cache
							  var pgNo 	= _self.tabData.pageNo;
							  _self.tabData.pages = [];
							  _self.resetPageDate(pgNo);
						}
						// end condition true
				  } else {
					  	// condition false
						//alert("Condition false");
						_self.resetTabDate();
				  }
				   
				  _self.tabData.pages[_self.tabData.pageNo].status = 1;
				  $.ajax({
					  data: {
						  admin_sess: 	_self.settings.admin_sess,
						  admin_menu:	_self.settings.admin_menu,
						  admin_oper:	_self.settings.admin_oper,

						  pageNo:		_self.tabData.pageNo,
						  condition: 	_self.tabData.condition,
						  fields: 		_self.settings.fields
					  },
					  dataType: "json",  
					  error: function(xhr, tStatus, errorTh ) {
						  alert("Error (" + _self.settings.url + "): " + xhr.responseText + "\nStatus: " + tStatus);
					  },
					  success: function(req, tStatus) {
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
							  _self.buildNavi(_self.general);
							  _self.tabData.pages[pgNo].status = 2;
							  _self.tabData.pages[pgNo].rows = req.data.rows;
							  if(_self.settings.pageHTML && $.isFunction(_self.settings.pageHTML)) {
							  		_self.settings.pageHTML(_self.tabData.pages[pgNo]);
							  } else {
									_self.pageDataToHTML(_self.tabData.pages[pgNo]);
							  }
						  }
					  },
					  type: "post",
					  url: _self.settings.url
				  });
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
		this.resetTabDate();
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
	
	compareCondition: function() {
		var flag = true;
		for(var key in this.tabData.condition) {
			if(this.tabData.condition[key] != this.condition[key]) {
				flag = false;
				break;
			}
		}
		return flag;
	},
	
	buildNavi: function(oGen) {
		  var html_nav = '';
		  html_nav += '<div class="tabQuery-dbnav-background">';
		  html_nav += '<span style="float:left;">';
		  html_nav += '<span style="color: black; font-size:11px; font-weight:bold; vertical-align:middle; margin-left:10px;">Page: </span>'; 
		  html_nav += '<select style="vertical-align:middle; height:22px; text-align:center; z-index:99999;" onchange="' + oGen.me + '.goPage(this.value);">';
		  for(var i = 1; i <= oGen.pageTotal; i++) {
			  html_nav += '<option value="' + i + '" ' + (parseInt(oGen.pageNo)==i?" selected":"") + '>' + i + '</option>';
		  }
		  html_nav += '</select>';
		  html_nav += '<span style="color:black; font-size:11px; font-weight:bold;"> of ' + oGen.pageTotal + '</span> ';
		  html_nav += '<span style="color:black; font-size:1.1em; font-weight:bold; margin-left:5px; margin-right:5px;">|</span>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-first" title="First"></a>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-prev" 	title="Previous"></a>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-next" 	title="Next"></a>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-last" 	title="Last"></a>';
		  html_nav += '<span style="color:black; font-size:1.1em; font-weight:bold; margin-left:5px; margin-right:2px;">|</span>';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-loading  tabQuery-dbnav-loading-na" title="LOADING..."></a>';
		  html_nav += '<span style="color:black; font-size:14px;">Searched records total: <span class="tabQuery_recoTotal">' + oGen.recoTotal + '</span></span>';
		  html_nav += '</span>';
		  html_nav += '<span style="color: black; font-size:11px; font-weight:bold; vertical-align:middle; float:right; margin-right:10px;">Page Size: '; 
		  html_nav += '<input type="text" id="tabQuery_pageSize" style="vertical-align:middle; text-align:center; width:30px;" value="' + oGen.pageSize + '" />';
		  html_nav += '<a class="tabQuery-dbnav tabQuery-dbnav-go" title="Go"></a></span>'; 
		  html_nav += '</div>';
		  $(oGen.navi).html(html_nav);
		  
		  // verify navi icon status
		  if( oGen.pageTotal >= 1 ) {
			  if( oGen.pageNo==1 ) {
				  $(".tabQuery-dbnav-first", oGen.navi).addClass("tabQuery-dbnav-first-na");
				  $(".tabQuery-dbnav-prev", oGen.navi).addClass("tabQuery-dbnav-prev-na");
			  } 
			  if( oGen.pageNo  >= oGen.pageTotal ) {
				  $(".tabQuery-dbnav-next", oGen.navi).addClass("tabQuery-dbnav-next-na");
				  $(".tabQuery-dbnav-last", oGen.navi).addClass("tabQuery-dbnav-last-na");
			  }
		  }else {
			  $(".tabQuery-dbnav-first", oGen.navi).addClass("tabQuery-dbnav-first-na");
			  $(".tabQuery-dbnav-prev", oGen.navi).addClass("tabQuery-dbnav-prev-na");
			  $(".tabQuery-dbnav-next", oGen.navi).addClass("tabQuery-dbnav-next-na");
			  $(".tabQuery-dbnav-last", oGen.navi).addClass("tabQuery-dbnav-last-na");
			  $(".tabQuery-dbnav-go", oGen.navi).addClass("tabQuery-dbnav-go-na");
		  }
		  // end of verify
		  
		  // setup click event for navi icons
		  $(".tabQuery-dbnav-first", oGen.navi).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-first-na") ) {
				  var pgNO = 1;
				  //$(".tabQuery-dbnav-first", oGen.navi).addClass("tabQuery-dbnav-first-na");
				  //$(".tabQuery-dbnav-prev", oGen.navi).addClass("tabQuery-dbnav-prev-na");
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	  
		  $(".tabQuery-dbnav-prev", oGen.navi).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-prev-na") ) {
				  var pgNO = parseInt(oGen.pageNo) - 1;
				  if( pgNO < 1 ) {
					  pgNO = 1;
					  //$(".tabQuery-dbnav-first", oGen.navi).addClass("tabQuery-dbnav-first-na");
					  //$(".tabQuery-dbnav-prev", oGen.navi).addClass("tabQuery-dbnav-prev-na");
				  } 
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	  
		  $(".tabQuery-dbnav-next", oGen.navi).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-next-na") ) {
				  var pgNO = parseInt(oGen.pageNo) + 1;
				  if( pgNO > oGen.pageTotal ) {
					  pgNO = oGen.pageTotal;
					  //$(".tabQuery-dbnav-last", oGen.navi).addClass("tabQuery-dbnav-last-na");
					  //$(".tabQuery-dbnav-next", oGen.navi).addClass("tabQuery-dbnav-next-na");
				  } 
				  
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	  
		  $(".tabQuery-dbnav-last", oGen.navi).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-last-na") ) {
				  var pgNO = oGen.pageTotal;
				  //$(".tabQuery-dbnav-last", oGen.navi).addClass("tabQuery-dbnav-last-na");
				  //$(".tabQuery-dbnav-next", oGen.navi).addClass("tabQuery-dbnav-next-na");
				  eval( oGen.me + ".goPage(pgNO)");
				  ev.preventDefault();
				  return false;
			  }
		  });


		  $(".tabQuery-dbnav-go", oGen.navi).unbind("click").bind("click", function(ev) {
			  if( !$(this).hasClass("tabQuery-dbnav-go-na") ) {
				  var pgSize = $("#tabQuery_pageSize", $(this).parent()).val();
				  eval(oGen.me + ".resizePage(pgSize)");
				  ev.preventDefault();
				  return false;
			  }
		  });
	},
	
	resetPageDate: function(pgNo) {
		  this.tabData.pages[pgNo] = null;
		  this.tabData.pages[pgNo] = {};
		  this.tabData.pages[pgNo].status = 0;
		  this.tabData.pages[pgNo].pageNo = pgNo;
		  this.tabData.pages[pgNo].rows = [];
	},

	resetTabDate: function() {
		  this.tabData.pageNo 	 = 1;

		  this.general.recoTotal = 0;
		  this.general.pageTotal = 0;
		  this.general.pageNo	 = this.tabData.pageNo;
		  this.general.pageSize  = this.tabData.condition.pageSize;

		  this.tabData.pages = [];
		  var dt = new Date();
		  this.tabData.timestamp  = dt.getTime();
		  this.resetPageDate(1);
	},
	
	pageDataToHTML: function(pgData ) {
		var html = '<table id="mytab"  class="tabQuery-table" border="1" cellpadding="2" cellspacing="0" orderby="' + this.tabData.condition.orderBY + '" ordersq="' + this.tabData.condition.orderSQ + '">';
		html += '<tr>';
		html += '<td width="20" class="tabQuery-table-header">';
		html += '</td>';
		
		for(var ffname in this.settings.fields) {
				if(this.settings.fields[ffname] == "hidden" ) continue;

				html += '<td class="tabQuery-table-header">';
				html += this.settings.fields[ffname];
				if(this.settings.orders[ffname]) {
					var order_css = '';
					if(ffname == this.tabData.condition.orderBY) {
						order_css = ' tabQuery-sort-' + this.tabData.condition.orderSQ.toLowerCase();
					}
					html += ' <a class="tabQuery-sort' + order_css + '" orderby="' + ffname + '" defsq="' + this.settings.orders[ffname] + '"></a>';
				}
				html += '</td>';
		}
		
		if( this.settings.butt) html += '<td width="80" align="center" class="tabQuery-table-header"></td>';
		html += '</tr>';
		
		
		for(var idx in pgData.rows) {
			html += '<tr>';

			html += '<td width="20" align="center">';
			html += parseInt(idx) + 1;
			html += '</td>';

			for(var ffname in this.settings.fields) {
				if(this.settings.fields[ffname] == "hidden" ) continue;

				html += '<td>';
				html +=  pgData.rows[idx][ffname];
				html += '</td>'
			}
			
			if( this.settings.butt) {
				html += '<td width="auto" style="white-space:nowrap;" align="center">';
				if( this.settings.view) html += '<a class="tabQuery-button tabQuery-button-view" 	oper="view" right="view" rid="' + pgData.rows[idx]["id"] + '" title="查看详细信息"></a>';
				if( this.settings.prin)	html += '<a class="tabQuery-button tabQuery-button-output" 	oper="print" right="print" 	rid="' + pgData.rows[idx]["id"] + '" title="打印详细信息"></a>';
				if( this.settings.dele)	html += '<a class="tabQuery-button tabQuery-button-delete" 	oper="delete" right="delete" 	rid="' + pgData.rows[idx]["id"] + '" title="删除记录" style="margin-left:3px;"></a>';
				html += '</td>';
			}
			html += '</tr>';
		}
		html += '</table>';
		
		$(this.general.tab).html(html);

		var _self = this;
		$(".tabQuery-sort", this.settings.tab).unbind("click").bind("click", function(ev) {
			_self.sortBy($(this).attr("orderby"), $(this).attr("defsq"));
			
			ev.preventDefault();
			return false;
		});
	},
	
	clone: function( obj ) {
		var newObj = {};
		for(var key in obj) {
			eval( "newObj." + key + " = obj[key]");
		}
		return newObj;
	}
}