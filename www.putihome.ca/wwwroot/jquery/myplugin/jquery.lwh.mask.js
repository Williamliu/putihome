/************************************************************************************/
/*  JQuery Plugin Maskable Div			                     		                */
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-1-18      															*/
/*  Files: 	jquery.lwh.advtable.js ;  jquery.lwh.advtable.css						*/
/************************************************************************************/

/******************************************************************************************************/
/* 
	$("#wait").maskable();
	$("#report").maskable({width:500, height:300});
	
	$("#report").Show();
	$("#wait").Hide();
	
	<div id="wait">
		<center>
		<span style="font-size:1.3em;">Please wait...</span><br><br>
		<div style="background-image:url(image/icon/progress_bar.gif); background-repeat:repeat-x; height:10px; width:98%;"></div>
		</center>
	</div> 
	
	<div id="report">
		<input type="image" src="image/icon/btn_close.png" onClick="report_close();" style="float:right; height:20px; margin-right:2px;" />
		<center>
			<span style="font-size:16px; font-weight:bold;">Please click the link to download report</span><br>
			<span style="font-size:12px;color:red;">(Maximum 1000 files in a report)</span><br>
		</center>
		<div id="file_list" style="width:98%; height:250px; overflow:auto; border:1px solid #999999;">
			<ul>
			</ul>
		</div>
	</div> 
	
	
*/
/******************************************************************************************************/
$.fn.extend({
	maskable:function( opts ){
		return this.each( function(idx, el) { 
			if($("#mask_ifrm").length <= 0 ) {
				$(document.body).append('<iframe id="mask_ifrm" style="position:absolute; border:1px solid #eeeeee; width:0px; height:0px; left:-2000px; top:-2000px; z-index:990; display:none;"></iframe>');
			}
			if($("#mask_div").length <= 0 ) {
				$(document.body).append('<div id="mask_div" style="position:absolute; border:1px solid #eeeeee; width:0px height:0px; left:-2000px; top:-2000px; z-index:991; display:none;"></div>');

				$("#mask_div").live("click", function() {
											$("#mask_div").fadeOut(200);
											$("#mask_ifrm").fadeOut(200);
											$(el).fadeOut(200);
											
											$("#mask_ifrm").css({
												display: "none",
												width: 0,
												height: 0,
												left: -2000,
												top: -2000
											});
								
											$("#mask_div").css({
												display: "none",
												width: 0,
												height: 0,
												left: -2000,
												top: -2000
											});
											
											$(el).css({
												display: "none",
												left: -2000,
												top: -2000
											});
											
											$(".recent-activity").removeClass("recent-activity-selected");
																							
				});
			
			}
			var default_setting = {
										border:"1px solid #333333", 
										backgroundColor:"#eeeeee", 
										position:"absolute", 
										padding:8, 
										left:-2000, 
										top:-2000, 
										display:"none", 
										width:300, 
										height:70, 
										"z-index":999,
										verticalAlign: "top"
								  };

			$.extend(default_setting, opts);
			$(el).css(default_setting);
			$(document.body).append( $(el) );
			
					
			$(window).scroll(function(){ 
				if( !$(el).is(":hidden") ) {
					  $("#mask_ifrm").stop(true, true).delay(500).animate({		
											  		top:$(window).scrollTop(), 
												  	left:$(window).scrollLeft(),
												  	width:$(window).width() - 2, 
												  	height: $(window).height() - 2
											  }, 50 );					
					  
					  $("#mask_div").stop(true, true).delay(500).animate({	
											 		top:$(window).scrollTop(), 
												  	left:$(window).scrollLeft(), 
												  	width:$(window).width() - 2, 
												  	height: $(window).height() - 2
											  }, 50 );
			  
		  			if($(el).css("verticalAlign") == "middle" ) {
					 		$(el).stop(true, true).delay(500).animate({
										top: $(window).scrollTop() + Math.round( ( $(window).height() - $(el).height() ) / 2 ) - 40, 
									  	left:$(window).scrollLeft() +  Math.round( ( $(window).width() - $(el).width() ) / 2 )
									}, 50);
					}
				}
				
			});
			// end of scroll
			
			$(window).resize(function(){ 
				if( !$(el).is(":hidden") ) {
					  $("#mask_ifrm").stop(true, true).delay(500).animate({
											  	left: $(window).scrollLeft(),
												top: $(window).scrollTop(),
											  	//width: "100%",
												//height: "100%"
												width:$(window).width() - 4, 
											  	height: $(window).height() - 4 
											  }, 50 ) ;					
					  $("#mask_div").stop(true, true).delay(500).animate({
											  	left: $(window).scrollLeft(),
												top: $(window).scrollTop(),
											  	//width: "100%",
												//height: "100%"
												width:$(window).width() - 4, 
												height: $(window).height() - 4 
											  }, 50 ) ;					
		  			if($(el).css("verticalAlign") == "middle" ) {
							  $(el).stop(true, true).delay(500).animate({
												left:$(window).scrollLeft() +  Math.round( ( $(window).width() - $(el).width() ) / 2 ),
												//top: $(window).scrollTop() + 5
												top: $(window).scrollTop() + Math.round( ( $(window).height() - $(el).height() ) / 2 )
								  }, 50 );					
					} else {
							  $(el).stop(true, true).delay(500).animate({
												left:$(window).scrollLeft() +  Math.round( ( $(window).width() - $(el).width() ) / 2 ),
												top: $(window).scrollTop() + 5
												//top: $(window).scrollTop() + Math.round( ( $(window).height() - $(el).height() ) / 2 )
								  }, 50 );					
					}
				}
			});
			// end of resize
		});
	},

	
	Show:function(){
		return this.each( function(idx, el) {
			$("#mask_ifrm").css({
				backgroundColor:"#000000",
				opacity: 0.3,
				//width: "100%",
				//height: "100%",
				width: $(window).width() - 4,
				height: $(window).height() - 4,
				left: $(window).scrollLeft(),
				top: $(window).scrollTop()
			});

			$("#mask_div").css({
				backgroundColor:"#000000",
				opacity: 0.3,
				//width: "100%",
				//height: "100%",
				width: $(window).width() - 4,
				height: $(window).height() - 4,
				left: $(window).scrollLeft(),
				top: $(window).scrollTop()
			});
			$("#mask_div").stop(true, true).fadeIn(100);
			$("#mask_ifrm").stop(true, true).fadeIn(100);
			
  			if($(el).css("verticalAlign") == "middle" ) {
				$(el).css({
					left: $(window).scrollLeft() + Math.round( ( $(window).width() - $(el).width() ) / 2 ),
					//top: $(window).scrollTop() + 5
					top: $(window).scrollTop() + Math.round( ( $(window).height() - $(el).height() ) / 2 )
				});
			} else {
				$(el).css({
					left: $(window).scrollLeft() + Math.round( ( $(window).width() - $(el).width() ) / 2 ),
					top: $(window).scrollTop() + 5
					//top: $(window).scrollTop() + Math.round( ( $(window).height() - $(el).height() ) / 2 )
				});
			}
			//alert( $(el).html());
			$(el).stop(true, true).fadeIn(100);
		});
	},
	
	Hide:function(){
		return this.each( function(idx, el) {
			$("#mask_div").stop(true, true).fadeOut(200);
			$("#mask_ifrm").stop(true, true).fadeOut(200);
			$(el).stop(true, true).fadeOut(200);
			
			$("#mask_ifrm").css({
				display: "none",
				width: 0,
				height: 0,
				left: -2000,
				top: -2000
			});

			$("#mask_div").css({
				display: "none",
				width: 0,
				height: 0,
				left: -2000,
				top: -2000
			});
			
			$(el).css({
				display: "none",
				left: -2000,
				top: -2000
			});
			
		});
	}
});
