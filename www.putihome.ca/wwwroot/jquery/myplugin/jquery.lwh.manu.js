/************************************************************************************/
/*  JQuery Plugin Context Manu - Corner,  Left/right click    		                */
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-3-15      															*/
/*  Files: 	jquery.lwh.menu.js ;  jquery.lwh.menu.css								*/
/************************************************************************************/
$.fn.extend({
	lwhManu : function( opts ) {
		var def_settings = {};
		$.extend(def_settings, opts);
		return this.each(function(idx, el) { 
				$("a", el).die("mouseover").live("mouseover", function(ev) {
					var mid = $(this).attr("mid");
					$(".lwhSMenu").stop(true, true).hide();
					$(".lwhSMenu[pmid='" + mid + "']").css({left:$(this).offset().left, top:$(this).offset().top + $(this).outerHeight()}).show();
					
					var ww = 16;
					var hh = 10;
					var half_ww = $(this).outerWidth() / 2;
					var half_ss = ww / 2;
					var l = half_ww - half_ss; 
					var t = 0;
					var ml = 0;
					var mt = -1 * hh;
					$("s", $(".lwhSMenu[pmid='" + mid + "']")).removeClass("toper lefter").addClass("toper").css({
							left: 			l,
							marginLeft: 	ml,
							top:			t,
							marginTop:		mt,
							width:			ww,
							height:			hh
					});
				});
	
	
				$("a", el).die("mouseout").live("mouseout", function(ev) {
					$(".lwhSMenu").stop(true, true).delay(1500).hide();
				});
				
	
				$(".lwhSMenu li").die("mouseover").live("mouseover", function(ev) {
					var mid = $(this).attr("mid");
					
					parent_smenu_show($(this).parent());
					clear_same_level(this);
					
					$(".lwhSMenu[pmid='" + mid + "']").stop(true, true).css({left:$(this).offset().left + $(this).outerWidth(), top:$(this).offset().top}).show();
	
					var ww = 10;
					var hh = 16;
					var l = 0;  
					var half_hh = $(this).outerHeight() / 2;
					var half_ss = hh / 2; 
					var t = half_hh - half_ss  - 1;
					var ml = -1 * ww;
					var mt = 0;
					$("s", $(".lwhSMenu[pmid='" + mid + "']")).removeClass("toper lefter").addClass("lefter").css({
							left: 			l,
							marginLeft: 	ml,
							top:			t,
							marginTop:		mt,
							width:			ww,
							height:			hh
					});
				});
	
				$(".lwhSMenu").die("mouseout").live("mouseout", function(ev) {
					child_smenu_hide(this);
					var _self = this;
					$(this).stop(true,true).delay(1500).hide(1, function(){
						parent_smenu_hide(_self);
					});
				});
	
				$(".lwhSMenu").die("mouseover").live("mouseover", function(ev) {
					$(this).stop(true,true).show();
				});
		});
	}
});


function parent_smenu_show(el) {
	var pmid = $(el).attr("pmid");
	var tmp_el = $(".lwhSMenu li[mid='" + pmid + "']").parent();
	tmp_el.stop(true, true).show(0, function(){
			parent_smenu_show(tmp_el);
	});
}


function parent_smenu_hide(el) {
	var pmid = $(el).attr("pmid");
	var tmp_el = $(".lwhSMenu li[mid='" + pmid + "']").parent();
	tmp_el.stop(true, true).delay(300).hide(1, function(){
		parent_smenu_hide(tmp_el);
	});
}


function child_smenu_hide(el) {
	$("li", $(el)).each(function(idx1, el1) {
		 var mid = $(el1).attr("mid");
		 $(".lwhSMenu[pmid='" + mid + "']").stop(true,true).hide();
	});
}

function clear_same_level(el) {
	$("li", $(el).parent()).each(function(idx1, el1){
		var tmid = $(el1).attr("mid");
		$(".lwhSMenu[pmid='" + tmid + "']").stop(true, true).hide();	
	});
}
