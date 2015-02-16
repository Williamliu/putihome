/************************************************************************************/
/*  JQuery Plugin context Menu - right click , left click                       	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-2-15      															*/
/*  Files: 	jquery.lwh.menu.js ;  jquery.lwh.menu.css								*/
/************************************************************************************/

/* Menu window layout CSS */
.lwhMenu {
	display:			none;
	position:			absolute;
	width:				auto;
	
	margin:				0px;
	padding:	    	15px 2px 15px 2px;

	list-style:			none;
	
	border:				1px solid #999999;
	background-color:	#CCCCCC;
	
	
	left:				-2000px;
	top:				-2000px;
}

.lwhMenu-corner {
	display:			inline-block;
	position:			absolute;
	width:				40px;
	height:				40px;
	background-image:	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhMenu-arrow-corner.png" , "png");?>);
	background-repeat:	no-repeat;
}

.lwhMenu-corner-lb {
	display:				none;
	top:					100%;
	left:					0px;
	
	margin-left: 			-25px;
	margin-top:	 			-14px;
	background-position: 	0px -40px;
}

.lwhMenu-corner-rb {
	display:				none;
	top:					100%;
	left:					100%;
	
	margin-left: 			-14px;
	margin-top:	 			-15px;
	background-position: 	-40px -40px;
}

.lwhMenu-corner-lt {
	display:				none;
	top:					0px;
	left:					0px;
	
	margin-left: 			-26px;
	margin-top:	 			-25px;
	background-position: 	0px 0px;
}

.lwhMenu-corner-rt {
	display:				none;
	top:					0px;
	left:					100%;
	
	margin-left: 			-15px;
	margin-top:	 			-26px;
	background-position: 	-40px 0px;
}

.lwhMenu-middle {
	display:			inline-block;
	position:			absolute;
	width:				20px;
	height:				20px;
	background-image:	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhMenu-arrow-middle.png" , "png");?>);
	background-repeat:	no-repeat;
}

.lwhMenu-middle-top {
	display:				none;
	top:					0px;
	left:					50%;
	
	margin-top:	 			-20px;
	margin-left:			-10px;
	background-position: 	0px 0px;
}

.lwhMenu-middle-bottom {
	display:				none;
	top:					100%;
	left:					50%;
	
	margin-top:	 			0px;
	margin-left:			-10px;
	background-position: 	0px -20px;
}

.lwhMenu-arrow-select {
	display:			block;
}

/* Menu Items CSS */
.lwhMenu li {
	cursor:				pointer;
	list-style:			none;
	
	margin:				0px;
	padding-left:       18px;
	padding-right:      10px;
	padding-top:        2px;
	padding-bottom:		2px;
	
	color:				#3333CC;
	font-size:			13px;
	text-decoration:	none;
	white-space:		nowrap;
}

.lwhMenu li:hover {
	color:				#FFFFFF;
	background-color: 	#3333CC;
	
}

.lwhMenu li.separator {
	margin-top: 	5px;
	padding-top: 	4px;
	border-top: 	1px solid #999999;
}

.lwhMenu li.title {
	cursor:			default;
	color: 			#666666;
    padding-left: 	2px;
    font-size:		12px;
    font-weight:	bold;

}

.lwhMenu li.title:hover {
  	background-color: 	#cccccc;
}

.lwhMenu li.na {
	cursor:				default;
	color: 				#666666;
}

.lwhMenu li.na:hover {
  	background-color: 	#cccccc;
}

.lwhMenu li.nv {
	display:			none;
}

/*** Other Flexiable CSS ****/
.lwhMenu li.radio-selected {
	color: 			#ffffff;
    font-size:		12px;
    font-weight: 	bold;
	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhMenu-item-dotted.png" , "png");?>) 6px center no-repeat;
}
