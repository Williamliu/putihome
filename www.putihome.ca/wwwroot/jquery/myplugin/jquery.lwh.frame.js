/************************************************************************************/
/*  JQuery Plugin Main Menu - 														*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-3-15      															*/
/*  Files: 	jquery.lwh.mmenu.js ;  jquery.lwh.mmenu.css								*/
/************************************************************************************/

$.fn.extend({
			lwhFrame: function(opts) {
				var def_settings = {};
				$.extend(def_settings, opts);
				return this.each(function(idx, el) { 
						$(el).append(
									  '<s class="lt"></s>' +
									  '<s class="rt"></s>' +
									  '<s class="lb"></s>' +
									  '<s class="rb"></s>' +
									  '<s class="tt"></s>' +
									  '<s class="bb"></s>' +
									  '<s class="ll"></s>' +
									  '<s class="rr"></s>'
									  );
						$(el).width($(el).width()).height($(el).height());
				});
			}
});