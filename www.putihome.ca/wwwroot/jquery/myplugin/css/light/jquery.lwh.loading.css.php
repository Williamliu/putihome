/************************************************************************************/
/*  JQuery Plugin:  LOADING                                                       	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-3-30      															*/
/*  Files: 	jquery.lwh.loading.js ;  jquery.lwh.loading.css							*/
/************************************************************************************/
.lwhLoading {
	display:				none;
	width:					180px;
	height:					100px;
	
	position:				absolute;
	top:					-2000px;
	left:					-2000px;
	text-align:				center;

	background: 	url(<?php echo base64_raw_image($theme_image_folder . "/icon/lwhLoading-frame.png" , "png");?>)  center center no-repeat;
}

/* mask iframe and div */
.lwhLoading-mask-ifrm {
	display:	none;
	position:	absolute;
	border:		1px solid #eeeeee; 
	width:		0px; 
	height:		0px; 
	left:		-2000px; 
	top:		-2000px;

	filter:				alpha(opacity:20);
	opacity:			0.2;
	background-color:	#000000;
}

.lwhLoading-mask-div {
	display:	none;
	position:	absolute;
	border:1px solid #eeeeee; 
	width:0px;
	height:0px; 
	left:-2000px; 
	top:-2000px;

	filter:				alpha(opacity:20);
	opacity:			0.2;
	background-color:	#000000;
}



.lwhLoading-msgText {
    color: 					#666666;
    font-size: 				16px;
    font-weight: 			bold;
	
	position:				relative;
	top:					20px;
	padding-left:			10px;
	text-align:				center;
}

.lwhLoading-loadingImage {
    display: 				inline-block;
	width:					32px;
	height:					32px;

	position: 				relative;
    top: 					30px;

	background-image: 		url(../image/icon/lwhLoading-loading.gif);
	background-position:	center center;
	background-repeat:		no-repeat;
}

