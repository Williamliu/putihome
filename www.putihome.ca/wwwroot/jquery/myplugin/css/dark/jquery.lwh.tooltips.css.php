/************************************************************************************/
/*  JQuery Plugin Tooltips									                       	*/
/*  Author:	William Liu                                                            	*/
/*  Date: 	2012-5-29      															*/
/*  Files: 	jquery.lwh.tooltips.js ;  jquery.lwh.tooltips.css						*/
/************************************************************************************/

/* Tooltips  Dive ayout CSS */
.lwhTooltip {
		display:			none;
		padding:			0px;

		position:			absolute;
		left:				-2000; 
		top:				-2000; 
		width:				200px; 

		border:				1px solid #333333;
		background-color:	#999999; 
		
		overflow:			hidden;
		text-align:			center;
		vertical-align:		middle;
		z-index:			100;
}

.lwhTooltip_message {
	display:				block;
	color:					#222222;
	font-size:				16px;
	font-weight:			bold;
	height:					60px;
	vertical-align:			middle;
}

.lwhTooltip_message s {
	display:				inline-block;
	width:					1px;
	height:					100%;
	vertical-align:			middle;
}

