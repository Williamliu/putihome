/************************************************************************************/
/*  JQuery Plugin:  LOADING                                                       	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-3-30      															*/
/*  Files: 	jquery.lwh.loading.js ;  jquery.lwh.loading.css							*/
/************************************************************************************/
$.fn.extend({
	lwhLoading:function( opts ){
		var def_settings = {
								container: 	"",
								loadMsg:	"LOADING...",
								zIndex:		90000
						 	};
		$.extend(def_settings, opts);
		
		var mask_zidx	= def_settings.zIndex;
		var mask_ifrm	= "#lwhLoading_mask_ifrm";
		var mask_div	= "#lwhLoading_mask_div";
		if($(mask_ifrm).length <= 0 )	{
			$(document.body).append('<iframe id="lwhLoading_mask_ifrm" class="lwhLoading-mask-ifrm" style="z-index:' + mask_zidx  + ';"></iframe>');
		} 

		if($(mask_div).length <= 0 ) 	{
			$(document.body).append('<div id="lwhLoading_mask_div" class="lwhLoading-mask-div" style="z-index:' + (mask_zidx + 1) + ';"></div>');
		}
		def_settings.zIndex = mask_zidx + 2;
		
		return this.each( function(idx, el) { 
			$(el).data("default_settings", def_settings);
			$(el).append('<div class="lwhLoading-msgText">' + def_settings.loadMsg + '</div><div class="lwhLoading-loadingImage"></div>');
			$(el).css("zIndex", def_settings.zIndex);
			def_settings.zIndex++;

		});
	},
	
	loadShow:function(opts){
		return this.each( function(idx, el) {
				var mask_ifrm	= "#lwhLoading_mask_ifrm";
				var mask_div 	= "#lwhLoading_mask_div";
				
				var def_settings = $(el).data("default_settings");
				$.extend(def_settings, opts);
				if(opts && opts.loadMsg && opts.loadMsg != "") {
					$(".lwhLoading-msgText", el).html(opts.loadMsg);
				} 

				var cont = $.lwhLoading_getLTWH("");
				$(mask_ifrm).stop(true, true).css({
						width: 	cont.width,
						height: cont.height,
						left: 	cont.left,
						top: 	cont.top
				}).show();
				

				$(mask_div).stop(true, true).css({
						width: 	cont.width,
						height: cont.height,
						left: 	cont.left,
						top: 	cont.top
				}).show();
			
				var el_pos 	= $.lwhLoading_getELPos(el);
				$(el).stop(true, true).css({
						left: el_pos.left,
						top:  el_pos.top
				}).show();
				
				$(window).unbind("scroll.lwhLoading").bind("scroll.lwhLoading", function(){ 
					$.lwhLoading_window_event();
				});
	
				$(window).unbind("resize.lwhLoading").bind("resize.lwhLoading", function(){ 
					$.lwhLoading_window_event();
				});
		});
	},
	
	loadHide:function(){
		return this.each( function(idx, el) {
				var mask_ifrm	= "#lwhLoading_mask_ifrm";
				var mask_div 	= "#lwhLoading_mask_div";
				var def_settings = $(el).data("default_settings");
				
				$(el).stop(true, true).hide().css({
						left: 	-2000,
						top: 	-2000
				});
				
				if( $(".lwhLoading:visible").length <= 0 ) {
						$(mask_ifrm).stop(true, true).hide().css({
								left:	-2000,
								top: 	-2000
						});
						$(mask_div).stop(true, true).hide().css({
								left: 	-2000,
								top: 	-2000
						});
						$(window).unbind("scroll.lwhLoading");
						$(window).unbind("resize.lwhLoading");
				}
		});
	}
});

$.extend({
	// get left, top, width , height for container
	lwhLoading_getLTWH: function( container ) {
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
	lwhLoading_getELPos: function( el ) {
				var def_settings = $(el).data("default_settings");
				var el_pos 		= {};
				el_pos.left 	= 0;
				el_pos.top 		= 0;
				
				var cont		= $.lwhLoading_getLTWH("");
				var el_width 	= $(el).width();
				var el_height 	= $(el).height();
				el_pos.top = cont.top + (cont.height - el_height) / 2;
				el_pos.left = cont.left + (cont.width - el_width) / 2;
				
				// think about  out of boundary;
				if( el_pos.left	<= 0 ) 	el_pos.left = 5;
				if( el_pos.top	<= 0 ) 	el_pos.top	= 5;
				return el_pos;
	},
	
	lwhLoading_window_event: function() {
				var mask_ifrm	= "#lwhLoading_mask_ifrm";
				var mask_div 	= "#lwhLoading_mask_div";
				
				if( $(mask_div).is(":visible") ) {
					if( $(".lwhLoading:visible").length <= 0 ) {
							$(mask_ifrm).hide().css({
									left:	-2000,
									top: 	-2000
							});
							$(mask_div).hide().css({
									left: 	-2000,
									top: 	-2000
							});
					} else {
							var cont = $.lwhLoading_getLTWH("");
							$(mask_ifrm).stop(true, true).delay(200).animate({
														left: 	cont.left,
														top: 	cont.top,
														width:	cont.width,
														height:	cont.height
													  }, 50 ) ;					
							$(mask_div).stop(true, true).delay(200).animate({
														left: 	cont.left,
														top: 	cont.top,
														width:	cont.width,
														height:	cont.height
													  }, 50 ) ;					
							
							$(".lwhLoading:visible").each(function(idx0, el0) {
									var el_pos 	= $.lwhLoading_getELPos(el0);
									$(el0).stop(true, true).delay(500).animate({
															left: 	el_pos.left,
															top: 	el_pos.top
														},	50);	
							});
					}
				}
	}
});

