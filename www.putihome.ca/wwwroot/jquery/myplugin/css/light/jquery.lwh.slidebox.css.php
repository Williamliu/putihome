/************************************************************************************/
/*  JQuery Plugin  Custom Slidebox                                                 	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-4-2      															*/
/*  Files: 	jquery.lwh.slidebox.js ;  jquery.lwh.slidebox.css						*/
/************************************************************************************/
.lwhSlidebox {
	cursor:		default;
	position:	absolute;
	display:	none;
	top:		-2000px;
	left:		-2000px;
	border:		1px solid #777777;
	
	padding:			25px 5px 15px 5px !important;
	background-color:	#dddddd;
	
	overflow:			hidden !important;
}
/* mask iframe and div */

.lwhSlidebox-bgColor1 {
	background-color:	#ffffff;
}

.lwhSlidebox-bgColor2 {
	background-color:	#ffffff;
}


.lwhSlidebox-header {
	cursor:				default;
	position:			absolute;
	left:				0px;
	top:				0px;
	display:			block;
	width:				100%;
	height:				25px;
	line-height:		25px;
	text-align:			center;
	
	background-color:	#dddddd;
}

.lwhSlidebox-header-title {
	display:		inline-block;
	color:			#666666;
	
	position:		absolute;
	left:			10px;
	top:			0px;
	width:			100%;
	height:			25px;
	line-height:	25px;
	font-size:		14px;
	font-weight:	bold;
	
	overflow:hidden; 
	text-decoration:none;
	text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	white-space:nowrap;
}

.lwhSlidebox-header-close {
	cursor:			pointer;
	display: 		inline-block;
	
	position: 		absolute;
	width:			16px;
	height:			16px;
	
	left:			100%;
	top:			2px;
	margin-left:	-18px;
	vertical-align:	middle;

	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhSlidebox-btn-close.png" , "png");?>)  center center no-repeat;
}

.lwhSlidebox-footer {
	cursor:				default;
	position:			absolute;
	left:				0px;
	top:				100%;

	display:			block;
	width:				100%;
	height:				12px;
	line-height:		12px;
	text-align:			center;
	margin-top:			-12px;
	
	background-color:	#999999;
}

.lwhSlidebox-footer-close {
	cursor:			pointer;
	display: 		inline-block;
	
	position: 		relative;
	width:			40px;
	height:			12px;
    
	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhSlidebox-btn-slideup.png" , "png");?>)  center center no-repeat;
}

.lwhSlidebox-content {
	overflow:			hidden;
	padding:			5px;
}

.lwhSlidebox-scroll {
	overflow: 				auto;
}

.lwhSlidebox-hscroll {
	overflow-x: 			auto;
	overflow-y: 			none;
}

.lwhSlidebox-vscroll {
	overflow-x: 			none;
	overflow-y: 			auto;
}
