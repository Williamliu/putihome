<?php
include_once($CFG["include_path"] . "/config/admin_language.php");

/****  Menus *******************************************************************************************************/
$menu = array();
$menu["menu"][0] = array("name"=>$words["menu_event"], "tpl"=>"", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"] 	= array();

$menu["menu"][0]["menu"][0] = array("name"=>$words["menu_class"],		"title"=>"category");
$menu["menu"][0]["menu"][10]= array("name"=>$words["menu_agreement"], 	"tpl"=>"agreement.php",			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][20] = array("name"=>$words["menu_new_class"],  "tpl"=>"class_add.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][30] = array("name"=>$words["menu_edit_class"],	"tpl"=>"class_edit.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][45] = array("name"=>$words["menu_calendar"],	"title"=>"category");
$menu["menu"][0]["menu"][48] = array("name"=>$words["menu_add_to_cal"], "tpl"=>"class_calendar.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][50] = array("name"=>$words["menu_onetime"],	"tpl"=>"event_calendar_add.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][60] = array("name"=>$words["menu_cal_evtlist"],"tpl"=>"event_calendar.php", 	 "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][70] = array("name"=>$words["menu_cal_evtall"],	"tpl"=>"event_calendar_list.php","url"=>"", "title"=>"", "desc"=>"");

$menu["menu"][0]["menu"][71] = array("name"=>$words["menu_device"],		"title"=>"category");
$menu["menu"][0]["menu"][72] = array("name"=>$words["menu_id_reader"],  "tpl"=>"idcard_reader.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][73] = array("name"=>$words["menu_id_data"],	"tpl"=>"idcard_data.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][74] = array("name"=>$words["menu_id_report"],	"tpl"=>"idcard_report.php", 	"url"=>"", "title"=>"", "desc"=>"");

$menu["menu"][0]["menu"][75] = array("name"=>$words["menu_course"],	"title"=>"category");
$menu["menu"][0]["menu"][80] = array("name"=>$words["menu_enroll"],	"tpl"=>"event_calendar_enroll.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][85] = array("name"=>$words["menu_enroll_all"],	"tpl"=>"event_calendar_enroll_all.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][90] = array("name"=>$words["menu_group"], 	"tpl"=>"event_calendar_group.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][100]= array("name"=>$words["menu_checkin"],"tpl"=>"event_calendar_checkin.php","url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][110]= array("name"=>$words["menu_attend_cal"],"tpl"=>"event_calendar_calculation.php","url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][113]= array("name"=>$words["menu_absent"], "tpl"=>"event_calendar_absent.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][116]= array("name"=>$words["menu_leave"], "tpl"=>"event_calendar_leave.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][120]= array("name"=>$words["menu_adjust"], "tpl"=>"event_calendar_attend.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][125]= array("name"=>$words["menu_certificate"], "tpl"=>"event_certificate.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][127]= array("name"=>$words["menu_certificate_other"], "tpl"=>"other_certificate.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][130]= array("name"=>$words["menu_creturn"],"tpl"=>"event_calendar_idcard.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][140]= array("name"=>$words["menu_payment"],"tpl"=>"event_calendar_payment.php","url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][145]= array("name"=>$words["menu_archive"],"tpl"=>"event_archive.php","url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][150]= array("name"=>$words["menu_baishi"],	"title"=>"category");
$menu["menu"][0]["menu"][155]= array("name"=>$words["menu_dharma_list"], "tpl"=>"puti_dharma.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][0]["menu"][160]= array("name"=>$words["menu_dharma"],	"tpl"=>"dharma_name.php", 			"url"=>"", "title"=>"", "desc"=>"");

$menu["menu"][5] = array("name"=>$words["menu_member"], "url"=>"", "tpl"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"] 		= array();
$menu["menu"][5]["menu"][0] 	= array("name"=>$words["menu_all_member"],	"title"=>"category");
$menu["menu"][5]["menu"][10] 	= array("name"=>$words["menu_member_list"],	"tpl"=>"puti_members.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"][12] 	= array("name"=>$words["menu_member_stat"],	"tpl"=>"puti_members_stat.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"][13] 	= array("name"=>$words["puti_enroll_desk"],	"tpl"=>"puti_enroll_desk.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"][15] 	= array("name"=>$words["menu_register"],	"title"=>"category");
$menu["menu"][5]["menu"][20] 	= array("name"=>$words["menu_freg"],		"tpl"=>"puti_registration.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"][30] 	= array("name"=>$words["menu_qreg"],		"tpl"=>"puti_qform.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"][35] 	= array("name"=>$words["menu_email"],		"title"=>"category");
$menu["menu"][5]["menu"][38] 	= array("name"=>$words["menu_email_list"], 	"tpl"=>"puti_email_list.php",	 "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][5]["menu"][40] 	= array("name"=>$words["menu_email_sent"], 	"tpl"=>"puti_email_pool.php",	 "url"=>"", "title"=>"", "desc"=>"");

$menu["menu"][10] = array("name"=>$words["menu_volunteer"], "url"=>"", "tpl"=>"", "title"=>"", "desc"=>"");
$menu["menu"][10]["menu"] 	= array();
$menu["menu"][10]["menu"][0] = array("name"=>$words["menu_dep"], 		"title"=>"category");
$menu["menu"][10]["menu"][10]= array("name"=>$words["menu_dep_def"],	"tpl"=>"puti_department.php",			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][10]["menu"][15]= array("name"=>$words["menu_vol"],		"title"=>"category");
$menu["menu"][10]["menu"][20]= array("name"=>$words["menu_vol_add"],	"tpl"=>"puti_volunteer_add.php", 			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][10]["menu"][30]= array("name"=>$words["menu_vol_all"],	"tpl"=>"puti_volunteer_list.php", 			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][10]["menu"][35]= array("name"=>$words["menu_vol_hours"],	"title"=>"category");
$menu["menu"][10]["menu"][40]= array("name"=>$words["menu_vol_entry"], 	"tpl"=>"puti_volunteer_hours.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][10]["menu"][50]= array("name"=>$words["menu_vol_adjust"],	"tpl"=>"puti_volunteer_adjust.php", 	"url"=>"", "title"=>"", "desc"=>"");


$menu["menu"][50] = array("name"=>$words["menu_sevice"], "url"=>"", "tpl"=>"", "title"=>"", "desc"=>"");
$menu["menu"][50]["menu"] 	= array();
$menu["menu"][50]["menu"][0] = array("name"=>$words["menu_service_basic"], 		"title"=>"category");
$menu["menu"][50]["menu"][10]= array("name"=>$words["menu_service_table"],	"tpl"=>"website_basic_table.php",		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][50]["menu"][20]= array("name"=>$words["menu_service_depart"],	"tpl"=>"pt_department.php", 			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][50]["menu"][30]= array("name"=>$words["menu_site_depart"],	"tpl"=>"pt_site_depart.php", 			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][50]["menu"][40]= array("name"=>$words["menu_volunteer"],		"title"=>"category");
$menu["menu"][50]["menu"][50]= array("name"=>$words["menu_volunteer"],	    "tpl"=>"pt_volunteer.php", 			    "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][50]["menu"][60]= array("name"=>$words["menu_volunteer_search"],"tpl"=>"pt_volunteer1.php", 			 "url"=>"", "title"=>"", "desc"=>"");



$menu["menu"][700] = array("name"=>$words["menu_report"], "url"=>"", "tpl"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"] 		= array();
$menu["menu"][700]["menu"][0] 	= array("name"=>$words["menu_rclass"],	"title"=>"category");
$menu["menu"][700]["menu"][10] 	= array("name"=>$words["menu_crep"], 	"tpl"=>"class_report.php", 					"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][15] 	= array("name"=>$words["menu_crep1"], 	"tpl"=>"class_report1.php", 				"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][20] 	= array("name"=>$words["menu_csum"], 	"tpl"=>"class_summary.php", 				"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][25] 	= array("name"=>$words["menu_rcourse"],	"title"=>"category");
$menu["menu"][700]["menu"][27] 	= array("name"=>$words["menu_gattend"],	"tpl"=>"event_group_attend.php", 			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][30] 	= array("name"=>$words["menu_rattend"],	"tpl"=>"event_calendar_attend_report.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][40] 	= array("name"=>$words["menu_cccrr"],	"tpl"=>"event_calendar_report.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][50] 	= array("name"=>$words["menu_cccss"],	"tpl"=>"event_calendar_summary.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][55]= array("name"=>$words["menu_vol_rep"],	"title"=>"category");
$menu["menu"][700]["menu"][60]= array("name"=>$words["menu_1111"],		"tpl"=>"puti_volunteer_bydep.php",			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][65]= array("name"=>$words["menu_1155"],		"tpl"=>"puti_volunteer_byjob.php",			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][70]= array("name"=>$words["menu_2222"],		"tpl"=>"puti_volunteer_byvol.php", 			"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][75]= array("name"=>$words["menu_3333"],		"title"=>"category");
$menu["menu"][700]["menu"][80]= array("name"=>$words["menu_4444"],		"tpl"=>"puti_volunteer_depyear.php","url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][700]["menu"][90]= array("name"=>$words["menu_5555"],		"tpl"=>"puti_volunteer_volyear.php","url"=>"", "title"=>"", "desc"=>"");



$menu["menu"][800] = array("name"=>$words["menu_admin"], 	"tpl"=>"", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"] 	= array();
$menu["menu"][800]["menu"][0]  	= array("name"=>$words["menu_acct"],		"title"=>"category");
$menu["menu"][800]["menu"][10] 	= array("name"=>$words["menu_myacc"],		"tpl"=>"website_myaccount.php", "url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][20] 	= array("name"=>$words["menu_acclist"], 	"tpl"=>"website_admins.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][30] 	= array("name"=>$words["menu_right"],		"tpl"=>"website_groups.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][31]  = array("name"=>$words["menu_site"],		"title"=>"category");
$menu["menu"][800]["menu"][32] 	= array("name"=>$words["menu_site_info"], 	"tpl"=>"puti_sites.php", 		"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][33] 	= array("name"=>$words["menu_basic"], 	    "tpl"=>"website_basic.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][35]  = array("name"=>$words["menu_other"],		"title"=>"category");
$menu["menu"][800]["menu"][40] 	= array("name"=>$words["menu_lang"], 		"tpl"=>"website_language.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][50] 	= array("name"=>$words["menu_table"], 		"tpl"=>"website_table.php", 	"url"=>"", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][60]  = array("name"=>$words["menu_apps"],		"title"=>"category");
$menu["menu"][800]["menu"][70] 	= array("name"=>$words["menu_collector"], 	"tpl"=>"", "url"=>$CFG["http"] . $CFG["web_domain"] . "/apps/collector/index.html", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][80] 	= array("name"=>$words["menu_monitor"], 	"tpl"=>"", "url"=>$CFG["http"] . $CFG["web_domain"] . "/apps/monitor/index.html", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][82] 	= array("name"=>$words["menu_camera"], 		"tpl"=>"", "url"=>$CFG["http"] . $CFG["web_domain"] . "/apps/camera/index.html", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][83] 	= array("name"=>$words["menu_service"], 	"tpl"=>"", "url"=>$CFG["http"] . $CFG["web_domain"] . "/apps/service/index.html", "title"=>"", "desc"=>"");
$menu["menu"][800]["menu"][95] 	= array("name"=>$words["menu_webapps"], 	"tpl"=>"", "url"=>$CFG["http"] . $CFG["web_domain"] . "/apps/download.php", "title"=>"", "desc"=>"");

$menu["menu"][900] = array("name"=>$words["menu_logout"], "tpl"=>"index.php", "url"=>"", "title"=>"", "desc"=>"");

/***** RIGHTS ********************************************************************************************************************/
$right["right"] = array();
$right["right"][0] = array("view"=>1);
$right["right"][0]["right"] 	= array();
$right["right"][0]["right"][0] 	= array("view"=>1);
$right["right"][0]["right"][10] = array("view"=>1, "add"=>1, 	"save"=>1, "delete"=>1);
$right["right"][0]["right"][20] = array("view"=>1, "save"=>1);
$right["right"][0]["right"][30] = array("view"=>1, "save"=>1, 	"delete"=>1);

$right["right"][0]["right"][45] = array("view"=>1);
$right["right"][0]["right"][48] = array("view"=>1, "save"=>1);
$right["right"][0]["right"][50] = array("view"=>1, "save"=>1);
$right["right"][0]["right"][60] = array("view"=>1, "save"=>1, 	"delete"=>1);
$right["right"][0]["right"][70] = array("view"=>1);

$right["right"][0]["right"][71] = array("view"=>1);
$right["right"][0]["right"][72] = array("view"=>1, "save"=>1, "delete"=>1);
$right["right"][0]["right"][73] = array("view"=>1, "save"=>1, "print"=>1, "detail"=>1);
$right["right"][0]["right"][74] = array("view"=>1, "print"=>1);

$right["right"][0]["right"][75] = array("view"=>1);
$right["right"][0]["right"][80] = array("view"=>1, 	"save"=>1, 	"delete"=>1, "detail"=>1, "print"=>1);
$right["right"][0]["right"][85] = array("view"=>1, 	"save"=>1, 	"delete"=>1, "detail"=>1, "print"=>1);
$right["right"][0]["right"][90] = array("view"=>1, 	"save"=>1, 	"delete"=>1, "detail"=>1, "print"=>1, "email"=>1);
$right["right"][0]["right"][100]= array("view"=>1,  "save"=>1, 	"delete"=>1);
$right["right"][0]["right"][110] = array("view"=>1, "save"=>1);
$right["right"][0]["right"][113] = array("view"=>1, "save"=>1, 	"print"=>1);
$right["right"][0]["right"][116] = array("view"=>1, "save"=>1, 	"print"=>1);
$right["right"][0]["right"][120] = array("view"=>1, "save"=>1, 	"print"=>1);
$right["right"][0]["right"][125] = array("view"=>1, "save"=>1, 	"detail"=>1, "print"=>1);
$right["right"][0]["right"][127] = array("view"=>1, "save"=>1, 	"detail"=>1, "print"=>1);
$right["right"][0]["right"][130] = array("view"=>1, "save"=>1, 	"delete"=>1, "email"=>1);
$right["right"][0]["right"][140] = array("view"=>1, "save"=>1, "print"=>1);
$right["right"][0]["right"][145] = array("view"=>1, "save"=>1, 	"detail"=>1, "print"=>1);
$right["right"][0]["right"][150] = array("view"=>1);
$right["right"][0]["right"][155] = array("view"=>1, "add"=>1, 	"save"=>1, "delete"=>1);
$right["right"][0]["right"][160] = array("view"=>1, "save"=>1, "detail"=>1, "print"=>1);

$right["right"][5] 				= array("view"=>1);
$right["right"][5]["right"]    	= array();
$right["right"][5]["right"][0] 	= array("view"=>1);
$right["right"][5]["right"][10]	= array("view"=>1, "save"=>1, "delete"=>1, "detail"=>1, "print"=>1, "email"=>1);
$right["right"][5]["right"][12]	= array("view"=>1, "print"=>1);
$right["right"][5]["right"][13] = array("view"=>1, 	"save"=>1, 	"delete"=>1, "detail"=>1, "print"=>1);
$right["right"][5]["right"][15] = array("view"=>1);
$right["right"][5]["right"][20] = array("view"=>1, "save"=>1);
$right["right"][5]["right"][30] = array("view"=>1, "save"=>1);
$right["right"][5]["right"][35] = array("view"=>1);
$right["right"][5]["right"][38] = array("view"=>1, "save"=>1, 	"delete"=>1, "print"=>1);
$right["right"][5]["right"][40] = array("view"=>1, "delete"=>1, "email"=>1);

$right["right"][10] = array("view"=>1);
$right["right"][10]["right"] 	= array();
$right["right"][10]["right"][0] = array("view"=>1);
$right["right"][10]["right"][10]= array("view"=>1, "add"=>1, "save"=>1, "delete"=>1);
$right["right"][10]["right"][15] = array("view"=>1);
$right["right"][10]["right"][20] = array("view"=>1, "save"=>1);
$right["right"][10]["right"][30] = array("view"=>1, "save"=>1, "delete"=>1, "print"=>1, "email"=>1);
$right["right"][10]["right"][35] = array("view"=>1);
$right["right"][10]["right"][40] = array("view"=>1, "save"=>1, "delete"=>1, "print"=>1, "email"=>1);
$right["right"][10]["right"][50] = array("view"=>1, "save"=>1, "delete"=>1, "print"=>1);

$right["right"][50] = array("view"=>1);
$right["right"][50]["right"] 	= array();
$right["right"][50]["right"][0] = array("view"=>1);
$right["right"][50]["right"][10]= array("view"=>1, "add"=>1, "save"=>1, "delete"=>1);
$right["right"][50]["right"][20] = array("view"=>1, "add"=>1, "save"=>1, "delete"=>1);
$right["right"][50]["right"][30] = array("view"=>1, "save"=>1);
$right["right"][50]["right"][40] = array("view"=>1);
$right["right"][50]["right"][50] = array("view"=>1, "save"=>1, "delete"=>1, "detail"=>1, "print"=>1);
$right["right"][50]["right"][60] = array("view"=>1, "save"=>1, "delete"=>1, "detail"=>1, "print"=>1);


$right["right"][700] = array("view"=>1);
$right["right"][700]["right"] 		= array();
$right["right"][700]["right"][0] 	= array("view"=>1);
$right["right"][700]["right"][10] 	= array("view"=>1, "print"=>1, "email"=>1);
$right["right"][700]["right"][15] 	= array("view"=>1, "print"=>1);
$right["right"][700]["right"][20] 	= array("view"=>1, "print"=>1);
$right["right"][700]["right"][25] 	= array("view"=>1);
$right["right"][700]["right"][27]	= array("view"=>1, "print"=>1);
$right["right"][700]["right"][30] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][40] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][50] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][55] = array("view"=>1);
$right["right"][700]["right"][60] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][65] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][70] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][75] = array("view"=>1);
$right["right"][700]["right"][80] = array("view"=>1, "print"=>1);
$right["right"][700]["right"][90] = array("view"=>1, "print"=>1);


$right["right"][800] = array("view"=>1);
$right["right"][800]["right"] 	= array();
$right["right"][800]["right"][0] = array("view"=>1);
$right["right"][800]["right"][10] = array("view"=>1, "save"=>1);
$right["right"][800]["right"][20] = array("view"=>1, "add"=>1, "save"=>1, "delete"=>1);
$right["right"][800]["right"][30] = array("view"=>1, "add"=>1, "save"=>1, "delete"=>1);
$right["right"][800]["right"][31] = array("view"=>1);
$right["right"][800]["right"][32] = array("view"=>1, "save"=>1);
$right["right"][800]["right"][33] = array("view"=>1, "save"=>1, "delete"=>1);
$right["right"][800]["right"][35] = array("view"=>1);
$right["right"][800]["right"][40] = array("view"=>1, "save"=>1, "delete"=>1, "print"=>1);
$right["right"][800]["right"][50] = array("view"=>1);
$right["right"][800]["right"][60] = array("view"=>1);
$right["right"][800]["right"][70] = array("view"=>1);
$right["right"][800]["right"][80] = array("view"=>1);
$right["right"][800]["right"][82] = array("view"=>1);
$right["right"][800]["right"][83] = array("view"=>1);
$right["right"][800]["right"][95] = array("view"=>1);

$right["right"][900] = array("view"=>1);
?>
