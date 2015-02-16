/************************************************************************************/
/*  JQuery Plugin Tab		 - 														*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-8-15      															*/
/*  Files: 	jquery.lwh.tab.js ;  jquery.lwh.tab.css									*/
/************************************************************************************/

$.fn.extend({
	lwhTab: function(opts) {
		var def_settings = {
			trigger:	"click",
			tabsn:		0,
			height:		0
		};
		$.extend(def_settings, opts);
		return this.each(function(idx, el) { 
				$(">ul>li", el).each(function(idx1, el1) {
					$(el1).attr("tabsn", idx1);
				});

				$(">div", el).each(function(idx1, el1) {
					if(def_settings.height > 0 ) $(el1).height(def_settings.height); 
					$(el1).attr("tabsn", idx1);
				});
				
				$(">ul>li[tabsn='" + def_settings.tabsn + "']", el).addClass("selected");
				$(">div[tabsn='" + def_settings.tabsn + "']", el).addClass("lwhTab-content-selected");
				
				$(">ul>li", el).unbind("mouseover").bind("mouseover", function(ev){
					$(">ul>li", el).not(".selected").removeClass("liHover");
					$(this).addClass("liHover");
				});

				$(">ul>li", el).unbind("mouseout").bind("mouseout", function(ev){
					$(this).removeClass("liHover");
				});

				$(">ul>li", el).unbind(def_settings.trigger).bind(def_settings.trigger, function(ev){
					$(">ul>li", el).removeClass("selected");
					$(">div",el).removeClass("lwhTab-content-selected");
					$(this).addClass("selected");
					$(">div[tabsn='" + $(this).attr("tabsn") + "']",el).addClass("lwhTab-content-selected");
					
				});
		});
	}
});