/************************************************************************************/
/*  JQuery Plugin Main Menu - 														*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-3-15      															*/
/*  Files: 	jquery.lwh.mmenu.js ;  jquery.lwh.mmenu.css								*/
/************************************************************************************/

$.fn.extend({
			lwhMMenu: function(opts) {
				var def_settings = {};
				$.extend(def_settings, opts);
				return this.each(function(idx, el) { 
						$("li.lwhMMenu-li", el).has("div.lwhMMenu-div").addClass("lwhMMenu-menu").children("a").addClass("lwhMMenu-menu");
						$("li.lwhMMenu-li:first", el).css("margin-left", 15);
						
						if($.browser.msie && $.browser.version < 8 ) { 
							var menu_id = $(el).attr("id")?$(el).attr("id"):$("a.lwhDrop").length;
							
							$("li.lwhMMenu-li", el).each(function(idx1, el1) {
								$(this).attr("menuid", idx + "-" + idx1);
								$("div.lwhMMenu-div", this).attr("menuid", idx + "-" + idx1).appendTo( $("body") );
							});

							$("div.lwhMMenu-div[menuid]").die("mouseover").live("mouseover", function(ev){
								var li_el 	= $("li.lwhMMenu-li[menuid='" + $(this).attr("menuid") + "']"); 
								var li_pos 	= li_el.offset();
								li_el.addClass("lwhMMenu-liHover lwhMMenu-menuliHover");
								$(this).stop(true,true).css({
									left: 	li_pos.left,
									top:	li_pos.top + 30
								}).show(); 
							});

							$("div.lwhMMenu-div[menuid]").die("mouseout").live("mouseout", function(ev){
								var li_el 	= $("li.lwhMMenu-li[menuid]"); 
								$(this).stop(true, true).delay(500).hide(200, function(){
									li_el.removeClass("lwhMMenu-liHover lwhMMenu-menuliHover");
								}); 
							});
							
							
							$("li.lwhMMenu-li", el).die("mouseover").live("mouseover", function(ev) {
								var _self = $(this);
								
								$("li.lwhMMenu-li[menuid]").removeClass("lwhMMenu-liHover lwhMMenu-menuliHover");
								$("div.lwhMMenu-div[menuid]").stop(true,true).hide();
								
								var div_el = $("div.lwhMMenu-div[menuid='" + $(this).attr("menuid") + "']");
								var div_pos = $(this).offset(); 
								_self.addClass("lwhMMenu-liHover");
								div_el.stop(true, true).css({
									left: 	div_pos.left, 
									top: 	div_pos.top + 30
								}).show(0, function(){
									_self.addClass("lwhMMenu-menuliHover");
								});
								
							});
				
							$("li.lwhMMenu-li", el).die("mouseout").live("mouseout", function(ev) {
								var _self = $(this);
								if( $("div.lwhMMenu-div[menuid='" + $(this).attr("menuid") + "']").length > 0 ) {
									$("div.lwhMMenu-div[menuid='" + $(this).attr("menuid") + "']").stop(true,true).delay(500).hide(200, function(){
										_self.removeClass("lwhMMenu-liHover lwhMMenu-menuliHover");
									});
								} else {
									_self.removeClass("lwhMMenu-liHover lwhMMenu-menuliHover");
								}
							});
							
							$("li.lwhMMenu-item").die("mouseover").live("mouseover", function(ev) {
								$(this).addClass("lwhMMenu-itemHover");
							});
							$("li.lwhMMenu-item").die("mouseout").live("mouseout", function(ev) {
								$(this).removeClass("lwhMMenu-itemHover");
							});
							
							
						} // end of if($.browser)
				});
			}
});