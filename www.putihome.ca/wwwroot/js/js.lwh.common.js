var LWH = {};

/***  prototype common function ***/
String.prototype.replaceAll = function(s1, s2) {
	return this.replace(new RegExp(s1, "gm"), s2);
}

String.prototype.trim = function() {
			return this.replace(/^\s+(.*)\s+$/gi,"$1");
}

String.prototype.right = function(n) {
			if( n >= this.length ) {
				return this;
			} else {
				return this.substr(this.length - n);
			}
}

String.prototype.printr = function() {
	var str = this.toString();
	for(var i = 0; i < arguments.length; i++ ) {
		var reg = new RegExp("\\{" + i + "\\}", "gim");
		str = str.replace(reg, arguments[i]);
	}
	return str;
}

String.prototype.nl2br = function() {
	var str = this.toString();
	str = str.replace(/\n|\r/gi, "<br>");
	str = str.replace(/ /gi, "&nbsp;");
	return str;
}

String.prototype.br2nl = function() {
	var str = this.toString();
	str = str.replace(/<br>|<br \/>/gi, "\n");
	str = str.replace(/&nbsp;/gi, " ");
	return str;
}

String.prototype.toDate = function() {
	if(isNaN(this)) {
		return "";
	} else {
		if( parseInt(this) > 0 ) {
			var ts = parseInt(this);
			var dt = new Date(ts);
			var ds = "{0}-{1}-{2} {3}:{4}:{5}";
			return ds.printr(dt.getFullYear(), ("0" + (dt.getMonth() + 1)).right(2) , ("0" + dt.getDate()).right(2), dt.getHours(), ("0" + dt.getMinutes()).toString().right(2), ("0" + dt.getSeconds()).toString().right(2) );
		} else {
			return "";
		}
	}
}

String.prototype.toJSON = function() {
	return $.parseJSON(this.toString());
}

String.prototype.htmlEnt = function() {
	return  String(this).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

String.prototype.toHMS = function() {
	if( isNaN(this) || parseFloat(this) <= 0 ) {
		return "00:00";
	} else {
		var tsec 	= Math.ceil( parseFloat(this) );
		var tddd	= Math.floor(tsec / (3600 * 24));
		var thhh	= Math.floor( (tsec % (3600 * 24)) / 3600 );
		var tmmm	= Math.floor( (tsec % 3600) / 60 );
		var tsss	= Math.floor((tsec % 60));
		tddd		= tddd<=0?"":(tddd<2?tddd.toString()+"day ":tddd.toString()+"days ");
		thhh		= thhh==0?(tddd<=0?"":"00:"):(thhh.toString() + ":");
		tmmm		= "0" + tmmm.toString();
		tsss		= "0" + tsss.toString();		
		tmmm		= tmmm.right(2);
		tsss		= ":" + tsss.right(2);
		var new_str = tddd + thhh + tmmm + tsss;
		return new_str;
	}
}

String.prototype.toSize = function() {
	if(isNaN(this)) {
		return "";
	} else {
		if( parseInt(this) > 0 ) {

			/* main function here */
			var bytes = parseInt(this);
			var i = -1;                                    
			do {
				bytes = bytes / 1024;
				i++;  
			} while (bytes > 99);
			/* end of main function here */

			return Math.max(bytes, 0.1).toFixed(2) + ['KB', 'MB', 'GB', 'TB', 'PB', 'EB'][i];          

		} else {
			return "";
		}
	}
}


Number.prototype.toDate = function() {
	if( parseInt(this) > 0 ) {
		var ts = parseInt(this);
		var dt = new Date(ts);
		var ds = "{0}-{1}-{2} {3}:{4}:{5}";
		return ds.printr(dt.getFullYear(), ("0" + (dt.getMonth() + 1)).right(2) , ("0" + dt.getDate()).right(2), dt.getHours(), ("0" + dt.getMinutes()).toString().right(2), ("0" + dt.getSeconds()).toString().right(2) );
	} else {
		return "";
	}
}

Number.prototype.toHMS = function() {
	if( isNaN(this) || parseFloat(this) <= 0 ) {
		return "00:00";
	} else {
		var tsec 	= Math.ceil( parseFloat(this) );
		var tddd	= Math.floor(tsec / (3600 * 24));
		var thhh	= Math.floor( (tsec % (3600 * 24)) / 3600 );
		var tmmm	= Math.floor( (tsec % 3600) / 60 );
		var tsss	= Math.floor((tsec % 60));
		tddd		= tddd<=0?"":(tddd<2?tddd.toString()+"day ":tddd.toString()+"days ");
		thhh		= thhh==0?(tddd<=0?"":"00:"):(thhh.toString() + ":");
		tmmm		= "0" + tmmm.toString();
		tsss		= "0" + tsss.toString();		
		tmmm		= tmmm.right(2);
		tsss		= ":" + tsss.right(2);
		var new_str = tddd + thhh + tmmm + tsss;
		return new_str;
	}
}

Number.prototype.toSize = function() {
		if( isNaN(this) || parseFloat(this) <= 0  ) {
			return "";
		} else {
			/* main function here */
			var bytes = parseInt(this);
			var i = -1;                                    
			do {
				bytes = bytes / 1024;
				i++;  
			} while (bytes > 99);
			/* end of main function here */

			return Math.max(bytes, 0.1).toFixed(2) + ['KB', 'MB', 'GB', 'TB', 'PB', 'EB'][i];          
		}
}

Date.prototype.diff = function(d2) {
   		var t2 = this.getTime();
        var t1 = d2.getTime();
        return parseInt((t2-t1)/(24*3600*1000));
}
/*** End of prototype common function ***/



/*** Normal JS function ***/
function jsonStr(jsonObj) {
	return JSON.stringify(jsonObj);
}

function showObj(obj) {
	var str = "-------------------------------------------\n";
	str += 'obj:[' + obj + "] *type:" + typeof(obj) + "\n";
	for(var key in obj) {
		//if( key.indexOf("on") >= 0 )
		if($.isArray(obj[key]) || $.isPlainObject(obj[key]) ) 
			str += showObj(obj[key]);
		else 
			str += "key:"  + key + "  value:" + obj[key] +  " type:" + typeof(obj[key]) + "\n";
	}
	return str;
}
/*** End of normal JS function ***/



/*** JQuery Common Function ***/
$.extend({
	element_pro: function(el) {
				var pro	= {};
				if($(el).length <= 0) {
						el					= window;
						pro.left			= $(el).scrollLeft();
						pro.top				= $(el).scrollTop();
						pro.width			= $(el).width()	 - 4;
						pro.height			= $(el).height() - 4;
			   } else {
						pro.left			= $(el).offset().left;
						pro.top				= $(el).offset().top;
						pro.width			= $(el).outerWidth();
						pro.height			= $(el).outerHeight();
				} 
				return pro;
	},
	
	element_pos: function(el) {
				var def_settings = $(el).data("default_settings");
				var el_pos 		= {};
				el_pos.left 	= 0;
				el_pos.top 		= 0;
				
				var cont		= $.element_pro(def_settings.container);
				var el_width 	= $(el).outerWidth();
				var el_height 	= $(el).outerHeight();
				
				if( isNaN(def_settings.top)  ) {
						switch(def_settings.top) {
							case "top":
								el_pos.top = cont.top + 5;
								break;
							case "middle":
								el_pos.top = cont.top + (cont.height - el_height) / 2;
								break;
							case "bottom":
								el_pos.top = cont.top + cont.height - el_height - 5;
								break;
							default:
								el_pos.top = cont.top;
								break;
						}
				} else {
						el_pos.top 	= cont.top + parseInt(def_settings.top);
				}

				if( isNaN(def_settings.left) ) {
						switch(def_settings.left) {
							case "left":
								el_pos.left =cont.left + 5;
								break;
							case "center":
								el_pos.left = cont.left + (cont.width - el_width) / 2;
								break;
							case "right":
								el_pos.left = cont.left + cont.width - el_width - 5;
								break;
							default:
								el_pos.left = cont.left;
								break;
						}
				} else {
						el_pos.left = cont.left + parseInt(def_settings.left);
				}

				if( $(def_settings.offsetTo).length > 0) {
					var rel_pos		= {};
					rel_pos.left 	= 0;
					rel_pos.top		= 0;

					rel_pos 	= $.element_pro(def_settings.offsetTo);	
					if(isNaN(def_settings.top)) el_pos.top 	= rel_pos.top; else el_pos.top = rel_pos.top + parseInt(def_settings.top);
					if(isNaN(def_settings.left)) el_pos.left = rel_pos.left; else el_pos.left = rel_pos.left + parseInt(def_settings.left);
				}
				
				// think about  out of boundary;
				if( el_pos.left	<= 0 ) 	el_pos.left = 5;
				if( el_pos.top	<= 0 ) 	el_pos.top	= 5;
				return el_pos;
	}
});
/*** End of JQuery Common Funcion ***/

//error handle
LWH.cERR = function(opts) {
	$.extend(this.settings, opts);
}
LWH.cERR.prototype = {
	settings: {
		diag: null
	},
	setDiag: function(dd) {
		this.settings.diag = dd;
	},
	set: function(code, msg, ff) {
		msg = msg?msg:"";
		switch(code) {
			case 1:
			case 3001:
			case 3002:
			case 3003:
			case 3004:
			case 3005:
			case 4001:
			case 9002:
					if( $(this.settings.diag).length > 0 ) {
						$(".lwhDiag-content", this.settings.diag).html(msg.nl2br());
						$(this.settings.diag).diagShow(); 
					} else {
						alert("Error Code:" + code + "\nError Message:" + msg);
					}
					break;
			case 9001:
					if( $(this.settings.diag).length > 0 ) {
						$(".lwhDiag-content", this.settings.diag).html(msg.nl2br());
						$(this.settings.diag).diagShow({
															diag_close:  function() {
																window.location.href = ff;
															}
														}); 
					} else {
						alert("Error Code:" + code + "\nError Message:" + msg);
					}
					break;
			default:
					alert("Error Code:" + code + "\nError Message:" + msg);
					break;
		}
	}
}

/***************** Cookies *****************************************/
function setCookie(name, value) {
    var argv = setCookie.arguments;
    var argc = setCookie.arguments.length;
    var expires = (argc > 2) ? argv[2] : 365;
    if(expires!=null) {
        var LargeExpDate = new Date ();
        LargeExpDate.setTime(LargeExpDate.getTime() + (expires*1000*3600*24));        
    }
    document.cookie = name + "=" + escape (value)+((expires == null) ? "" : ("; expires=" +LargeExpDate.toGMTString()));
}

function getCookie(Name) {
    var search = Name + "="
    if(document.cookie.length > 0) {
        offset = document.cookie.indexOf(search)
        if(offset != -1) {
            offset += search.length
            end = document.cookie.indexOf(";", offset)
            if(end == -1) end = document.cookie.length
            return unescape(document.cookie.substring(offset, end))
        }
        else return ""
    }
}

function deleteCookie(name) {
    var expdate = new Date();
    expdate.setTime(expdate.getTime() - (86400 * 1000 * 1));
    setCookie(name, "", expdate);
} 

LWH.cHTML = function() {}
LWH.cHTML.prototype = {
    checkbox_get: function (name) {
        var ret_val = '';
        ret_val = $("input:checkbox[name='" + name + "']:checked").map(function () { return $(this).val(); }).get().join(",");
        return ret_val;
    },
    checkbox_get1: function (name, attr_name) {
        var ret_val = '';
        ret_val = $("input:checkbox[name='" + name + "']:checked").map(function () { return $(this).attr(attr_name); }).get().join(",");
        return ret_val;
    },
    checkbox_title1: function (name, class_name, attr_name) {
        var ret_val = '';
        $("input:checkbox[name='" + name + "']:checked").each(function (idx, el) {
            var rid = $(el).attr(attr_name);
            var el_ccc = class_name + "[" + attr_name + "='" + rid + "']";
            ret_val += (ret_val == "" ? "" : " ") + (idx + 1) + "." + $(el_ccc).html();
        });
        return ret_val;
    },
    checkbox_set: function (name, vals) {
        $("input:checkbox[name='" + name + "']").attr("checked", false);
        if (vals && vals != "") {
            $.map(vals.split(","), function (n) {
                $("input:checkbox[name='" + name + "'][value='" + n + "']").attr("checked", true);
            });
        }
    },
    checkbox_set1: function (name, attr_name, vals) {
        $("input:checkbox[name='" + name + "']").attr("checked", false);
        if (vals && vals != "") {
            $.map(vals.split(","), function (n) {
                $("input:checkbox[name='" + name + "'][" + attr_name + "='" + n + "']").attr("checked", true);
            });
        }
    },
    checkbox_clear: function (name) {
        $("input:checkbox[name='" + name + "']").attr("checked", false);
    },
    checkbox_all: function (name) {
        $("input:checkbox[name='" + name + "']").attr("checked", true);
    },

    radio_get: function (name) {
        var ret_val = '0';
        ret_val = $("input:radio[name='" + name + "']:checked").val();
        return ret_val;
    },
    radio_set: function (name, val) {
         $("input:radio[name='" + name + "']").attr("checked", false);
        $("input:radio[name='" + name + "'][value='" + val + "']").attr("checked", true);
    },
    radio_clear: function (name) {
        $("input:radio[name='" + name + "']").attr("checked", false);
    }

}