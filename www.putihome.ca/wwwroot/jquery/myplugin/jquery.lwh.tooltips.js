/************************************************************************************/
/*  JQuery Plugin  Tooltips		                                                 	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-2-5      															*/
/*  Files: 	jquery.lwh.tooltips.js ;  jquery.lwh.tooltips.css						*/
/************************************************************************************/
$.fn.extend({
	lwhTooltip:function( opts ){
			var def_settings = {
									container: 	"",
									hAlign:		"center",   //"left", "center", "right"
									vAlign:		"middle",  //"top", 	"middle", "bottom"
									
									radius:		20,
									offset: 	{left: 20, right:20, top:5, bottom:30} 
							};
			$.extend(def_settings, opts);
			return this.each( function(idx, el) { 
				$(el).data("default_settings", def_settings);
				$(el).css({"border":"1px solid #999999", "border-radius": def_settings.radius});
			});
	},

	TShow: function( msg ) {
		return this.each( function(idx, el) {
			var def_settings = $(el).data("default_settings");

			$(".lwhTooltip_message", el).html(msg + '<s></s>');

			var el_pos = $.lwhTooltip_getELPos(el);
			$(el).css({
					display:"block",
					left: 	el_pos.left,
					top: 	el_pos.top
			});
			
			$(el).show();
		});
	},
	
	THide: function() {
		$(el).fadeOut(1000);
	},
	
	autoTShow: function( msg ) {
		return this.each( function(idx, el) {
			var def_settings = $(el).data("default_settings");
			
			$(".lwhTooltip_message", el).html(msg + '<s></s>');
			
			var el_pos = $.lwhTooltip_getELPos(el);
			$(el).css({
					display:"block",
					left: 	el_pos.left,
					top: 	el_pos.top
			});
			
			$(el).stop(true, true).fadeIn(10).delay(2000).fadeOut(1000);
		});
	}
});


$.extend({
	// get left, top, width , height for container
	lwhTooltip_getLTWH: function( container ) {
				var layout	= {};
				if(container == "") {
						container				= window;
						layout.left				= $(container).scrollLeft();
						layout.top				= $(container).scrollTop();
						layout.width			= $(container).width()	- 4;
						layout.height			= $(container).height()	- 4;
			   } else {
						layout.left		= $(container).offset().left;
						layout.top		= $(container).offset().top;
						layout.width	= $(container).outerWidth();
						layout.height	= $(container).outerHeight();
				} 
				return layout;
	},
	// get left, top, position for element
	lwhTooltip_getELPos: function( el ) {
				var def_settings = $(el).data("default_settings");
				var el_pos 		= {};
				el_pos.left 	= 0;
				el_pos.top 		= 0;
				
				var cont	= $.lwhTooltip_getLTWH(def_settings.container);
				var el_width 	= $(el).outerWidth();
				var el_height 	= $(el).outerHeight();

				el_pos.left 	= cont.left  + ( cont.width 	- el_width );
				el_pos.top 		= cont.top	 + ( cont.height 	- el_height);
								
				switch(def_settings.hAlign) {
					case "left":
									el_pos.left	= cont.left + def_settings.offset.left;
									break;
					case "center":
									el_pos.left	= cont.left + ( cont.width - el_width ) / 2;
									break;
					case "right":
									el_pos.left	= cont.left + ( cont.width - el_width ) - def_settings.offset.right;
									break;
					default:
									el_pos.left	= cont.left + ( cont.width - el_width ) - def_settings.offset.right;
									break;
				}
				switch(def_settings.vAlign) {
					case "top":
									el_pos.top = cont.top + def_settings.offset.top;
									break;
					case "middle":
									el_pos.top = cont.top + ( cont.height - el_height) / 2;
									break;
					case "bottom":
									el_pos.top = cont.top	 + ( cont.height- el_height) - def_settings.offset.bottom;
									break;
					default:
									el_pos.top = cont.top	 + ( cont.height- el_height) - def_settings.offset.bottom;
									break;
				}
				

				// think about  out of boundary;
				if( el_pos.left	<= 0 ) 	el_pos.left = 5;
				if( el_pos.top	<= 0 ) 	el_pos.top	= 5;
				return el_pos;
	}

});