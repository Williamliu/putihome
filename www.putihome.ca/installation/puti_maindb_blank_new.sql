/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.1.72-community : Database - puti_maindb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `event_calendar` */

DROP TABLE IF EXISTS `event_calendar`;

CREATE TABLE `event_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` int(11) DEFAULT NULL,
  `branch` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `place` int(11) DEFAULT NULL,
  `agreement` int(11) DEFAULT NULL,
  `start_date` bigint(20) DEFAULT NULL,
  `end_date` bigint(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `event_calendar` */

/*Table structure for table `event_calendar_attend` */

DROP TABLE IF EXISTS `event_calendar_attend`;

CREATE TABLE `event_calendar_attend` (
  `enroll_id` int(11) NOT NULL DEFAULT '0',
  `event_date_id` int(11) NOT NULL DEFAULT '0',
  `sn` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`enroll_id`,`event_date_id`,`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `event_calendar_attend` */

/*Table structure for table `event_calendar_date` */

DROP TABLE IF EXISTS `event_calendar_date`;

CREATE TABLE `event_calendar_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `class_date_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `day_no` tinyint(4) DEFAULT NULL,
  `yy` int(4) DEFAULT NULL,
  `mm` int(2) DEFAULT NULL,
  `dd` int(2) DEFAULT NULL,
  `start_time` varchar(10) DEFAULT NULL,
  `end_time` varchar(10) DEFAULT NULL,
  `event_date` bigint(20) DEFAULT NULL,
  `checkin` tinyint(4) DEFAULT NULL,
  `meal` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `event_calendar_date` */

/*Table structure for table `event_calendar_enroll` */

DROP TABLE IF EXISTS `event_calendar_enroll`;

CREATE TABLE `event_calendar_enroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `group_no` int(11) DEFAULT '0',
  `leader` tinyint(4) DEFAULT '0',
  `volunteer` tinyint(4) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  `online` tinyint(4) DEFAULT '0',
  `signin` tinyint(4) DEFAULT '0',
  `new_flag` tinyint(1) DEFAULT '0',
  `graduate` tinyint(4) DEFAULT '0',
  `cert` tinyint(4) DEFAULT '0',
  `attend` decimal(11,2) DEFAULT '0.00',
  `paid` tinyint(4) DEFAULT '0',
  `amt` decimal(11,2) DEFAULT '0.00',
  `invoice` varchar(31) DEFAULT NULL,
  `paid_date` bigint(20) DEFAULT '0',
  `unauth` tinyint(4) DEFAULT '0',
  `trial` tinyint(4) DEFAULT '0',
  `trial_date` bigint(20) DEFAULT '0',
  `onsite` tinyint(4) DEFAULT '0',
  `cert_no` varchar(255) DEFAULT NULL,
  `shelf` int(11) DEFAULT '0',
  `doc_no` varchar(31) DEFAULT NULL,
  `link` varchar(4096) DEFAULT NULL,
  `sess` varchar(4096) DEFAULT NULL,
  `confirm` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `event_calendar_enroll` */

/*Table structure for table `pt_department` */

DROP TABLE IF EXISTS `pt_department`;

CREATE TABLE `pt_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) DEFAULT '0',
  `lang_key` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_cn` varchar(255) DEFAULT NULL,
  `desc_en` varchar(255) DEFAULT NULL,
  `desc_cn` varchar(255) DEFAULT NULL,
  `sn` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `deleted` tinyint(1) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

/*Data for the table `pt_department` */

insert  into `pt_department`(`id`,`parent`,`lang_key`,`title_en`,`title_cn`,`desc_en`,`desc_cn`,`sn`,`status`,`deleted`,`created_time`,`last_updated`) values (1,0,'','人力资源部','人力资源部','','人力资源部',99,1,0,1423110453,1423110519),(2,0,'','文宣部','文宣部','','',88,1,0,1423110481,1423110525),(3,0,'','教学部','教学部','','',66,1,0,1423110511,1423110555),(4,0,'','财务部','财务部','','',77,1,0,1423110544,1423110559),(5,3,'','中文禅修课程组','中文禅修课程组','','',99,1,0,1423110589,1423110672),(6,3,'','英文禅修课程组','英文禅修课程组','','',88,1,0,1423110694,0),(7,3,'','八卦课程组','八卦课程组','','',77,1,0,1423110725,0),(8,3,'','共修组','共修组','','',66,1,0,1423110752,0),(9,3,'','教务组','教务组','','',55,1,0,1423110776,0),(10,3,'','师资培训组','师资培训组','','',50,1,0,1423110823,0),(11,3,'','青年精英组','青年精英组','','',45,1,0,1423110860,0),(12,3,'','音响组','音响组','','',40,1,0,1423110881,1423110886),(13,3,'','内场组','内场组','','',35,1,0,1423110905,0),(14,0,'','生活服务部','生活服务部','','',55,1,0,1423110933,1423110945),(15,14,'','接送服务组','接送服务组','','',99,1,0,1423110970,0),(16,14,'','翻译服务组','翻译服务组','','',88,1,0,1423110989,0),(17,14,'','入籍考试组','入籍考试组','','',77,1,0,1423111011,0),(18,14,'','儿童看护组','儿童看护组','','',66,1,0,1423111029,0),(19,14,'','清洁组','清洁组','','',55,1,0,1423111053,0),(20,14,'','餐饮组','餐饮组','','',44,1,0,1423111076,0),(21,14,'','紧急救助组','紧急救助组','','',33,1,0,1423111099,0),(22,0,'','爱心关怀部','爱心关怀部','','',44,1,0,1423111137,0),(23,22,'','关怀组','关怀组','','',99,1,0,1423111164,0),(24,22,'','祈福组','祈福组','','',88,1,0,1423111186,0),(25,22,'','祝寿组','祝寿组','','',77,1,0,1423111206,0),(26,22,'','殡仪组','殡仪组','','',66,1,0,1423111229,0),(27,22,'','常青会','常青会','','',55,1,0,1423111258,0),(28,22,'','家庭共修组','家庭共修组','','',44,1,0,1423111278,0),(29,0,'','咨询调理部','咨询调理部','','',33,1,0,1423111314,0),(30,29,'','调理组','调理组','','',99,1,0,1423111337,0),(31,29,'','解签组','解签组','','',88,1,0,1423111371,0),(32,29,'','供灯组','供灯组','','',77,1,0,1423111394,1423111399),(33,29,'','杂志发放组','杂志发放组','','',66,1,0,1423111421,0),(34,29,'','前台咨询组','前台咨询组','','',55,1,0,1423111442,0),(35,1,'','招募组','招募组','','',99,1,0,1423111483,0),(36,1,'','义工组','义工组','','',88,1,0,1423111497,0),(37,2,'','摄影组','摄影组','','',99,1,0,1423111513,0),(38,2,'','编辑组','编辑组','','',88,1,0,1423111527,1423111555),(39,2,'','采访组','采访组','','',77,1,0,1423111542,0),(40,4,'','出纳组','出纳组','','',88,1,0,1423111572,1423111696),(41,4,'','账务组','账务组','','',99,1,0,1423111603,1423111702),(42,0,'','行政部','行政部','','',22,1,0,1423111775,0),(43,42,'','行政组','行政组','','',99,1,0,1423111800,0),(44,42,'','公共关系组','公共关系组','','',88,1,0,1423111813,0),(45,42,'','安保组','安保组','','',77,1,0,1423111829,0),(46,42,'','信息安全组','信息安全组','','',66,1,0,1423111846,0),(47,42,'','文档管理组','文档管理组','','',55,1,0,1423111866,0),(48,42,'','日常采购组','日常采购组','','',44,1,0,1423111884,0),(49,42,'','IT支持组','IT支持组','','',40,1,0,1423111907,0),(50,42,'','物业管理组','物业管理组','','',35,1,0,1423111924,0),(51,42,'','车辆管理组','车辆管理组','','',30,1,0,1423111942,0),(52,42,'','医护组','医护组','','',25,1,0,1423111965,0),(53,42,'','赶集组(会展)','赶集组(会展)','','',20,1,0,1423111984,0),(54,0,'','工程部','工程部','','',18,1,0,1423112046,0),(55,54,'','物业维护组','物业维护组','','',99,1,0,1423112067,0),(56,54,'','营造组','营造组','','',88,1,0,1423112081,0),(57,0,'','法物部','法物部','','',16,1,0,1423112108,0),(58,57,'','法物征订组','法物征订组','','',99,1,0,1423112131,0),(59,57,'','报关货运组','报关货运组','','',88,1,0,1423112147,0),(60,57,'','仓库管理组','仓库管理组','','',77,1,0,1423112169,0),(61,57,'','法物销售组','法物销售组','','',66,1,0,1423112189,0);

/*Table structure for table `pt_site_attribute` */

DROP TABLE IF EXISTS `pt_site_attribute`;

CREATE TABLE `pt_site_attribute` (
  `site_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  PRIMARY KEY (`site_id`,`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_site_attribute` */

/*Table structure for table `pt_site_department` */

DROP TABLE IF EXISTS `pt_site_department`;

CREATE TABLE `pt_site_department` (
  `site_id` int(11) NOT NULL,
  `depart_id` int(11) NOT NULL,
  PRIMARY KEY (`site_id`,`depart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_site_department` */

/*Table structure for table `pt_volunteer` */

DROP TABLE IF EXISTS `pt_volunteer`;

CREATE TABLE `pt_volunteer` (
  `member_id` int(11) NOT NULL,
  `resume` text,
  `memo` text,
  `vol_type` int(11) DEFAULT '0',
  `email_flag` tinyint(4) DEFAULT '0',
  `status` tinyint(4) DEFAULT '1',
  `deleted` tinyint(4) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer` */

/*Table structure for table `pt_volunteer_degree` */

DROP TABLE IF EXISTS `pt_volunteer_degree`;

CREATE TABLE `pt_volunteer_degree` (
  `volunteer_id` int(11) NOT NULL,
  `degree_id` int(11) NOT NULL,
  PRIMARY KEY (`volunteer_id`,`degree_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_degree` */

/*Table structure for table `pt_volunteer_depart_current` */

DROP TABLE IF EXISTS `pt_volunteer_depart_current`;

CREATE TABLE `pt_volunteer_depart_current` (
  `member_id` int(11) NOT NULL,
  `depart_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`depart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_depart_current` */

/*Table structure for table `pt_volunteer_depart_will` */

DROP TABLE IF EXISTS `pt_volunteer_depart_will`;

CREATE TABLE `pt_volunteer_depart_will` (
  `member_id` int(11) NOT NULL,
  `depart_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`depart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_depart_will` */

/*Table structure for table `pt_volunteer_health` */

DROP TABLE IF EXISTS `pt_volunteer_health`;

CREATE TABLE `pt_volunteer_health` (
  `member_id` int(11) NOT NULL,
  `health_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`health_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_health` */

/*Table structure for table `pt_volunteer_others` */

DROP TABLE IF EXISTS `pt_volunteer_others`;

CREATE TABLE `pt_volunteer_others` (
  `member_id` int(11) NOT NULL,
  `professional_other` varchar(255) DEFAULT NULL,
  `health_other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_others` */

/*Table structure for table `pt_volunteer_professional` */

DROP TABLE IF EXISTS `pt_volunteer_professional`;

CREATE TABLE `pt_volunteer_professional` (
  `member_id` int(11) NOT NULL,
  `professional_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`professional_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_professional` */

/*Table structure for table `pt_volunteer_schedule` */

DROP TABLE IF EXISTS `pt_volunteer_schedule`;

CREATE TABLE `pt_volunteer_schedule` (
  `member_id` int(11) DEFAULT NULL,
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_type` tinyint(1) DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT '00:00:00',
  `end_time` time DEFAULT '00:00:00',
  `status` tinyint(1) DEFAULT '1',
  `deleted` tinyint(1) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_schedule` */

/*Table structure for table `pt_volunteer_schedule_day` */

DROP TABLE IF EXISTS `pt_volunteer_schedule_day`;

CREATE TABLE `pt_volunteer_schedule_day` (
  `schedule_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY (`schedule_id`,`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pt_volunteer_schedule_day` */

/*Table structure for table `puti_agreement` */

DROP TABLE IF EXISTS `puti_agreement`;

CREATE TABLE `puti_agreement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `puti_agreement` */

insert  into `puti_agreement`(`id`,`status`,`deleted`,`created_time`,`last_updated`,`subject`) values (1,1,0,1387480777,1418022269,'八卦班 Agreement'),(2,1,0,1394911291,1415128597,'二级班条款'),(3,1,0,1399331222,1402528115,'塑身美容班 Bodhi Meditation and Beauty Retreat'),(4,1,0,1399657658,1407527859,'一级班条款'),(5,1,0,1402526239,1402526343,'青少年夏令营 Agreement'),(6,1,0,1404573678,1421197765,'1st Level Agreement'),(7,1,1,1405222622,1405222632,'2nd Level Agreement'),(8,1,0,1405222803,1418192866,'3nd Level Agreement'),(9,1,0,1415128641,1418192885,'三级班条款'),(10,1,0,1415390741,1418192874,'2nd Level Agreement');

/*Table structure for table `puti_agreement_lang` */

DROP TABLE IF EXISTS `puti_agreement_lang`;

CREATE TABLE `puti_agreement_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agreement_id` int(11) DEFAULT NULL,
  `lang` varchar(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `puti_agreement_lang` */

insert  into `puti_agreement_lang`(`id`,`agreement_id`,`lang`,`title`,`description`) values (1,1,'cn','潜在伤害风险免责与个人肖像授权声明','本人明白任何活动都有预料不到伤及身体的可能风险，这些风险也许是由于课堂活动学员自身或参加禅修班其他学员的行为造成的；万一发生这种现象，如前所述，学员同意完全承担这些风险以及因此而可能造成的损失、费用及责任。本人在此承认，授予并许可菩提禅修协会能在活动期间以记录本人名字或相似性质的纪录或声音、影像、图片摄影。并授权或无异议菩提禅修协会用于广告、宣传、展示和公开播放其拍摄全部或部分的图片、影音素材于各媒介、设备与流程。同时也授予菩提禅修协会，在不得违反或侵犯任何第三方权利之条件下有权修改、调整、改变、重制、配音或结合他人所记录的资料素材和声音制成合适的宣传品。'),(2,1,'en','Individual and Risk Release','I assume all risks of damage and\ninjuries that may occur to me while participating in the Bodhi Meditation\ncourse and while on the premises at which the classes are held. I am aware that\nsome courses may involve yoga, mindful stretching and mental exercises. I\nhereby release and discharge the Canada Bodhi Dharma Society and its agents and\nrepresentatives from all claims or injuries resulting from my participation in\nthe program. I hereby grant permission to the Canada Bodhi Dharma Society,\nIncluding its successors and assignees to record and use my name, image and\nvoice, for use in its promotional and informational productions. I further\ngrant the Canada Bodhi Dharma Society permission to edit and modify these\nrecordings in the making of productions as long as no third party\'s rights are\ninfringed by their use. Lastly, I release any and all legal claims against the\nCanada Bodhi Dharma Association for using, distributing or broadcasting any\nproductions. <br><p class=\"MsoNormal\" style=\"margin-bottom: 0.0001pt;\"><span style=\"font-size:12.0pt;font-family:&quot;Times New Roman&quot;,&quot;serif&quot;;\nmso-fareast-font-family:&quot;Times New Roman&quot;\">\nI have read, understood, and I guarantee that all the information I have\nprovide above is true and correct to the best of my knowledge. I agree to the\nabove release.<o:p></o:p></span></p>\n\n<div><span style=\"font-family: \'Times New Roman\', serif; font-size: 12pt;\">By signing this form, you also give\nthe&nbsp;Canada Bodhi Dharma Society permission to keep you informed about this\nsociety through various media.</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>'),(3,1,'tw','潜在伤害风险免责与个人肖像授权声明','<p class=\"MsoNormal\" align=\"center\" style=\"text-align: left; margin-right: -62.5pt;\"><font face=\"Arial, Verdana\" size=\"2\"><span style=\"line-height: 18.66666603088379px;\">本人明白任何活动都有预料不到伤及身体的可能风险，这些风险也许是由于课堂活动学员自身或参加禅修班其他学员的行为造成的﹔万一发生这种现象，如前所述，学员同意完全承担这些风险以及因此而可能造成的损失、费用及责任。本人在此承认，授予并许可菩提禅修协会能在活动期间以记录本人名字或相似性质的纪录或声音、影像、图片摄影。并授权或无异议菩提禅修协会用于广告、宣传、展示和公开播放其拍摄全部或部分的图片、影音素材于各媒介、设备与流程。同时也授予菩提禅修协会，在不得违反或侵犯任何第三方权利之条件下有权修改、调整、改变、重制、配音或结合他人所记录的资料素材和声音制成合适的宣传品。</span></font></p>'),(4,2,'cn','二级班报名条件','1）有初级健身班结业证<br>2）填写并签署“二级班学员申请表”<br>3）是弟子，或有意愿成为弟子并提交《拜师申请表》<br>4）报名费$100加元 + 5% GST<b><br></b><div align=\"center\"><b>申请菩提禅修班说明及协议</b><br></div>1、由加拿大菩提法门协会（以下简称协会）举办的菩提禅修二级班为期12天。<br><br>2、协会一旦决定接纳申请者为二级班的学员后，将会通过电话、传真或电子信件等向申请者发出接收的通知后才能成为二级班的学生。<br><br>3、学员如果对所使用的场所以及场所里的设施造成损坏，要按价赔偿。<br><br>4、 学员应该明白任何活动都有预料不到的伤害及风险, 这些风险也许是由于学员自身或参加班的其他人的行为造成的; 万一发生这种现象，如前所述, 学员同意完全承担这些风险以及由此而可能造成的损失、费用及责任。<br><br>5、 办班期间，协会的老师、工作人员和代理人所教授的方法和动作，对人的身体和精神都是有益的。 但是学员有权根据自己的身体和精神状况对这些方法和动作做出取捨，学员在办班期间的一切行为都是自愿的，协会、协会的老师、工作人员和代理人不强迫学员使 用任何方法和动作。因此，学员在参加班期间，无论任何自身原因－身体、心理或精神上出现各种非正常状况，包括病情加重，赴医院急救，乃至死亡等；或者对其 他人造成身体、心理或精神伤害，概由自己负责；学员豁免协会、 协会的老师、工作人员和代理人承担任何与之相关的经济或法律责任。<br><br>6、 根据本协议中所涉及的责任豁免, 学员同意，即使学员本人、或由任何人代理学员来起诉协会、协会的老师、 工作人员和代理人，也要全面免除协会、协会的老师、工作人员和代理人承担任何与起诉相关的诉讼费、律师费及可能由起诉引起的任何其它责任与费用。<br>7、为了保障教学秩序及禅修效果，请学员自觉遵守以下条例:<br><br>&nbsp;&nbsp;&nbsp; 报名时不得隐瞒自身疾病状况；所填写申请资料须与个人证件身份相符；<br><br>&nbsp;&nbsp;&nbsp; 协会不接受精神病患者、传染病患者、重大疾病患者或生活无法自理者的入学申请。如&nbsp;&nbsp;&nbsp; 发现对自身疾病状况隐瞒者，协会有权在任何时间要求该学员退出学习班。<br><br>&nbsp;&nbsp;&nbsp; 服从管理，不得有无理要求；<br><br>&nbsp;&nbsp;&nbsp; 不能有任何形式的歧视（包括宗教信仰、种族、国籍、性别等）；<br><br>&nbsp;&nbsp;&nbsp; 尊重老师，不得有任何不礼貌的言行；<br><br>&nbsp;&nbsp;&nbsp; 禅修时必须统一着装（本协会备有专用服装，方便禅修，费用自付）；<br><br>&nbsp;&nbsp;&nbsp; 遵守上课和禅修时间，未经允许，不得迟到、早退和无故旷课；<br><br>&nbsp;&nbsp;&nbsp; 不得大声喧哗、干扰正常的上课、作息秩序；不得以任何形式妨碍、干扰他人；<br><br>&nbsp;&nbsp;&nbsp; 不得传播非本协会教授的任何健身方法、修行方法及宗教内容；<br><br>&nbsp;&nbsp;&nbsp; 未经许可严禁照相、录像及录音；<br><br>&nbsp;&nbsp;&nbsp; 严禁抽烟、饮酒和使用毒品;<br><br>&nbsp;&nbsp;&nbsp; 不能在上课和禅修时吃东西；<br><br>&nbsp;&nbsp;&nbsp; 上课和禅修时关闭手机，不得携带妨碍他人静修的任何物品；<br><br>8、办班期间，如果学员违反以上任何一款条例者，协会有权在任何时间要求该学员退出二级班。<br><br>9、本协会不承担任何与教学课程无关的事务和责任.<br><br>10、以上条款本人都已明白，自愿接受所有要求。本协议经双方签字后立即生效。<br><br>11、本协议受辖于卑诗省法律。一旦发生纠纷双方同意在诉诸法律之前通过仲裁解决纠纷.<br><br>12、本协议同意双方的后代, 私人代表, 继承人, 委托人为合约或法律责任所约束。<br><br>13、学员明白为了方便中英文学员阅读，此申请表特设中英文两种语言，两种语言的涵义均一致。读懂任何一种语言后签署该协议都是有效的。<br>14、以上这些条例仅限用本期禅修班。<br><br><div align=\"center\"><h3><b>个人资料授权书</b></h3></div>被授权人名称：加拿大菩提法门协会（以下简称「被授权人」）<br><br>被授权人地址：7740 Alderbridge Way,Richmond,B.C.<br><br>本人，即本授权书的签署人(以下简称「授权人」)，基于有效的约因，在此同意授予被授权人以下权利：被授权人可以不受限地记录和使用授权人的姓名和/或肖像、声音、形象、履歷资料(以下统称为「个人资料」) ，被授权人可以为广告、宣传、展览及开发等目的，在其创作的有关菩提禅修的书面、影视、摄影、印刷品、电子和网络出版物(以下简称「项目」)中，以目前已有或将来可能出现的任何方式、媒介、设备、工艺和技术，在全球范围内永久地全部或部分地使用个人资料。<br><br>授权人同意被授权人对个人资料进行编辑、改编、修改、重新排序、复制，或与其他作品或资料混合编辑使用。<br><br>被授权人行使本授权书赋予之权利时，不得违反或侵犯任何第三方权利或权益。授权人知悉并理解被授权人已基于本授权书开始实施项目的制作、发佈和开发等工作。<br>授权人同意豁免被授权人、其继受人和受让人、被许可人因行使本授权书项下权利而引发的任何诉讼或索赔请求，包括但不限于任何针对侵犯隐私权、侵犯公开权、诽谤、错误披露隐私及侵犯其他人身和/或财产权的诉讼或索赔请求。授权人就个人资料放弃任何道义上的权利。被授权人无需通知或经授权人同意，即可将此授权书赋予的权利转让给任何第三方。'),(5,2,'en','2nd Level Agreement','<p class=\"MsoNormal\" style=\"margin-bottom: 0.0001pt;\"><span style=\"font-family: Arial, sans-serif; font-size: 10pt;\">1. The applicant must\nhave graduated from the Bodhi Meditation &amp; Fitness Retreat Level 1. Shortly\nafter The Society has chosen applicants for the Program, Notice of admittance\nand request for payment of tuition will be made by phone, fax, or e-mail.\nApplicants will not be admitted as students until tuition has been paid in\nfull.&nbsp;</span></p><p class=\"MsoNormal\" style=\"margin-bottom: 0.0001pt;\"><span style=\"font-family: Arial, sans-serif; font-size: 10pt;\">2. The prospective student acknowledges that any activity or exercise involves\nthe risk and danger of bodily injury, and that such risks and dangers: a) may\nbe caused by the student\'s own actions, or inactions, or the actions, or\ninactions of others participating in the Program. b) may not be known or\nreadily foreseeable at this time. Notwithstanding the aforementioned, the\nstudent agrees to FULLY ACCEPT AND ASSUME ALL SUCH RISKS AND DANGERS, AND ALL\nRESPONSIBILITY FOR LOSSES, COSTS AND DAMAGES which may be suffered by the\nstudent, as a result of the student participation in the Program.&nbsp;</span></p><p class=\"MsoNormal\" style=\"margin-bottom: 0.0001pt;\"><span style=\"font-size: 10pt; font-family: Arial, sans-serif;\">\n3. During this session of the Program, all methods and activities taught by the\nSociety and the Society\'s instructors, employees, and agents are considered to\nbe beneficial; however, the students, at all times, have the right and\nresponsibility to choose for themselves whether to participate or not to\nparticipate in any of the methods and activities being taught, based on their\nown physical, psychological and spiritual condition. During participation in\nthe Program, every action the student takes is of his/her own free will. The\nSociety, and the Society\'s instructors, employees, and agents will not force or\ncoerce any student to perform any methods or activities. Thus, during the\nstudent\'s participation in the Program, the student accepts sole responsibility\nfor any physical, psychological or spiritual dilemmas the student experiences\nor causes to any other individual, irrespective of cause, including, but not\nlimited to, the worsening of a health condition, hospitalization, and even\ndeath. Thus, the student HEREBY RELEASES and DISCHARGES the Society, and the\nSociety\'s instructors, employees, and agents from all liability, claims,\ndemands, losses or damage perceived to have been incurred during or because of\nparticipation in the Program.&nbsp;<br>\n4. In light of the release, waiver of liability and assumption of risk\ncontained in this Agreement, the student agrees that if the student, or anyone\non behalf of the student, makes a claim against the Society, or the Society\'s\ninstructors, employees, and agents, the student WILL INDEMNIFY, SAVE AND HOLD\nHARMLESS the Society, and the Society\'s instructors, employees, and agents from\nany litigation expenses, lawyer\'s fees, loss liability, damage, or cost which\nany may incur as a result of such a claim, to the fullest extent permitted by\nlaw.&nbsp;<br>\n5. While participating in the Program, should the student break any of the\nabove-mentioned regulations, the Society holds the right to dismiss the student\nfrom the Program at any time.&nbsp;<br>\n6.The Society is not responsible for any extra-curricular situations or\nconcerns that arise.&nbsp;<br>\n7. In order to guarantee the proper instruction of the Program\'s methods, and\nto guarantee its effectiveness, students must agree to abide by the following\nconditions: Upon registration, do not conceal matters of personal health; the\ninformation filled in on each student\'s application form must match the\ninformation on their documents of official identification; The Society does not\naccept applicants having mental health issues, infectious disease,\nlife-threatening illnesses, or incapable of performing Activities of Daily\nliving. If an applicant is discovered withholding any information on his/her\nmedical conditions, The Society holds the right to request the student to\nwithdraw from the Program at any time.&nbsp;<br>\n8. I have fully understood all of the above mentioned conditions of this\nAgreement, and freely agree to abide by them. This Agreement shall be effective\nand binding on the parties upon signing by both parties.&nbsp;<br>\n9. This Agreement shall bind and insure to the benefit of the respective heirs,\npersonal representatives, successors, and assignees of the parties.&nbsp;<br>\n10. The stipulations and conditions written above apply exclusively to the\nfore-mentioned session of the Program. INDIVIDUAL RELEASE Produce\'s full\ncorporate name: The Canada Bodhi Dharma Society(The \"Producer\") This\nletter confirms that the undersigned person ( the \"Releaser\"), for\ngood and valuable consideration, the receipt and sufficiency of which is hereby\nacknowledged, hereby grants unrestricted permission to the Producer to record\nand use Releaser\'s name and /or likeness, voice, image, biographical material\nmotion picture, photography, printed materials, electronic and web publications\ncreated by the Producer in connection with advertising, publicizing, exhibiting\nand exploiting the Program, in whole or in part, by any and all means, media,\ndevices, processes and technology now or hereafter known of devised in perpetuity\nthroughout the universe. The Releaser hereby grants to Producer the right to\nedit, adapt, alter, rearrange, dub, interpolate or combine with others the\nRecordings of the Releaser. The Releaser represents to Producer that Producer\'s\nexercise of the rights granted herein shall not violate or infringe any rights\nof any third party. The Releaser understands that Producer has been induced to\nproceed with production, distribution and exploitation of the Program in\nreliance upon this Agreement. The Releaser hereby releases the Producer, its\nsuccessors, assignees and licensees from any and all claims and demands arising\nout of or in connection with such use, including, without limitation, any and\nall claims for invasion of privacy, infringement of Releaser\'s right of\npublicity, defamation ( including libel and slander), false light, and any\nother person and /or property rights. The Releaser hereby waives any moral\nrights that Releaser may have in the Recordings. The Producer may assign this\nAgreement to any party without the consent of a notice to the releaser.</span></p><p class=\"MsoNormal\" style=\"margin-bottom: 0.0001pt;\"><span style=\"font-size: 10pt; font-family: Arial, sans-serif;\">Consent I am the parent or guardian of the minor named above and have the legal\nauthority to execute the above release. I approve and waive any rights in the\nrelease. By signing this form, you also give the Canada Bodhi Dharma Society\npermission to keep you informed about this society through various media. &nbsp;</span><span style=\"font-size: 10pt;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></p>'),(6,2,'tw','潜在伤害风险免责与个人肖像授权声明','本人明白任何活动都有预料不到伤及身体的可能风险，这些风险也许是由于课堂活动学员自身或参加禅修班其他学员的行为造成的；万一发生这种现象，如前所述，学员同意完全承担这些风险以及因此而可能造成的损失、费用及责任。本人在此承认，授予并许可菩提禅修协会能在活动期间以记录本人名字或相似性质的纪录或声音、影像、图片摄影。并授权或无异议菩提禅修协会用于广告、宣传、展示和公开播放其拍摄全部或部分的图片、影音素材于各媒介、设备与流程。同时也授予菩提禅修协会，在不得违反或侵犯任何第三方权利之条件下有权修改、调整、改变、重制、配音或结合他人所记录的资料素材和声音制成合适的宣传品。'),(7,3,'tw','菩提禅修课程报名手续説明及协议 Bodhi Meditation Program Application Procedures and Agreement','<div style=\"text-align: center;\"><div style=\"font-family: Arial, Verdana; font-style: normal; font-variant: normal; line-height: normal; text-align: left;\"><div style=\"text-align: center;\"><b>菩提禅修课程报名手续説明及协议</b></div><div style=\"text-align: center;\"><b>Bodhi Meditation Program</b></div><div style=\"text-align: center;\"><b>Application Procedures and Agreement</b></div><br>1.   由美国国际菩提法门协会 (以下简称本协会) 举办的菩提禅修塑身美容班	(以下简称禅修班)  为期九天。毎天上午 9:30 -  下午 6:20<br>Bodhi Meditation &amp; Beauty Retreat (hereafter referred to as “The Program”) is held by American&nbsp;Int’l Puti (Bodhi) Dharma Society (hereafter referred to as “The Society”), is 9 days in length. &nbsp;Daily 9:30 AM – 6:20 PM<br><br>2.   申请者需上满七天课程才能领取结业证书，请正确填写表格。<br>Participants need to complete 7 full days for certificate, and must correctly fill out the registration form.<br><br>        3.   费用：押金$360（全程9天，至少上满7天。每天押金＄40，一共＄360）<br>       禅修课程费用全免+免费素食午餐。上满七天课程者，押金全数退还。上课少于七天者，旷课半天                     者，功德捐款$20；旷课一天，功德捐款$40,以此类推。迟到早退三次, 按旷课一天来计算。                    课程结束凭收据领回押金。<br>Tuition Policy: Deposit is $360 ($40 per day).  All Class Fees waived, and Free Lunch.<br>Participants who complete the 7 full days out of the 9-day retreat will receive a full refund. If else, half day absence of $20 and whole day absence of $40 will be collected automatically as donation for participant attend less than 7 full days. Tardiness and leaving early three times will automatically donate $40. Please present deposit receipt for refund at the end of the class. <br><br>4. 凡患有严重心脏病、癫痫、精神病、传染病、危重病者或生活不能自理者，谢绝参加禅修课程或 集体活动，因为您不适合参加这种集体禅修课程。如有不便，敬请原谅。<br>Persons suffering from severe heart disease, epilepsy, certain mental disorders, infectious diseases; or those who require constant assistance and/or critical care are NOT permitted to participate in any meditation programs or group activities.  Such persons are not suitable for group meditation programs/activities.    We apologize for any inconvenience.<br><br>5.   开课期间,  学员如对所使用的场所及场所内的设施造成损坏,  需按价赔偿。<br>During the course of the Program, student is responsible for any damage the student causes to any equipment or to the facility.<br><br>6.   由学员自身造成的行为风险事故、财物损失, 应由其本人自行承负经济损失和法律责任。如与他 人共同行为所造成的风险后果财物损失,  也应自行协调处理相关责任和损失,  与本会无关。<br>The student agrees to FULLY ACCEPT AND ASSUME ALL SUCH RISKS AND DANGERS, AND ALL RESPONSIBILITY FOR LOSSES, COST AND DAMANGES which may be suffered by the student, as a result of the student’s participation in The Program or in participation with other individual. The Society does not assume any responsibility in this matter.<br> <br>7.   开课期间, 协会的授课人员教授的健身方法,  对人的身体和精神都是有益无害的。 但是学员有权 根据自己的身体和精神状况对这些方法和动作做出取。学员在开课期间的一切行为都是自愿的, 协会的授课老师和工作人员不强迫学员使用任何方法和动作。学员在参加禅修期间,  无论任何自 身的原因:  身体、心理、或精神上出现各种非正常状况,  包括病情加重、赴医院急救、乃至死亡 等;  或对 (被)  其他人造成身体、心理、或精神伤害时,  后果应由当事人负则承担经济责任和法律 责任。此类事件若有发生,  学员同意全面豁免协会、协会老师、工作人员、和代理人的经济责任 和法律责任。<br>During this session of The Program, all methods and activities taught by the Society’s instructors, and agents are considered to be beneficial; however, the student, at all times, has the right and responsibility to choose for themselves whether to participate or not in any of the methods and activities being taught, based on their own physical, psychological and spiritual condition. During the participation in The Program, every action the student takes is of his/her own free will. The Society, and the Society’s instructors and agents will not force or coerce any student to perform any method or activity. Thus, during the student’s participation in The Program, the student accepts sole responsibility for any physical, psychological or spiritual dilemmas the student experiences or causes to any other individual, irrespective of cause, including, but not limited to, the worsening of a health condition, hospitalization, and even death. Thus, the student HEREBY RELEASES and DISCHARGES The Society, and the Society’s instructors, employees, and agents from all liability, claims, demands, losses or damage perceived to have been incurred during or because of participation in The Program.<br><br>8.   学员同意, 协会提供给学员的所有资料, 其版权永久归属协会所有。未经本会事先书面许可,  学 员不得以任何方式私自复制传播这些资料。<br>The student agrees that any and all materials, which The Society provides to the student and the copyright of such material are the property of The Society. The student shall not use, release, reproduce, disclose, or otherwise disseminate in any way, such materials without the prior written permission of The Society.<br><br>9.   在参加禅修班期间, 学员将得到协会专有的保密信息。学员同意未经协会事先书面许可,  不得复 制、传播这些信息。学员同意合理保护协会专有保密信息。<br>The student acknowledges that during The Program, the student will have access to confidential and proprietary information of The Society. The student agrees not to use, release, reproduce, disclose, or otherwise disseminate in any way The Society’s confidential and proprietary information without The Society’s prior written permission. The student agrees to use a reasonable standard of care, in safeguarding The Society’s confidential proprietary information.<br></div><div style=\"font-family: Arial, Verdana; font-style: normal; font-variant: normal; line-height: normal; text-align: left;\"><br> <br></div><div style=\"font-family: Arial, Verdana; font-style: normal; font-variant: normal; line-height: normal; text-align: left;\"><div style=\"text-align: center;\"><b>INDIVIDUAL RELEASE</b></div><br>Producer’s full corporate name:              American Int’l Puti (Bodhi) Dharma Society (the “Producer”)<br>Street Address: 			          20657 Golden Springs Drive, Suite 111<br>City, State, ZIP Code:		          Diamond Bar, CA 91789<br><br>The undersigned person (“Releaser”), for goods and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, hereby grants permission and all necessary rights to Producer to record Releaser and use Releaser’s name and/or likeness and/or voice as same may appear in motion picture photography created by Producer in connection with the program currently entitled “Bodhi Meditation” (“Program”) and in connection with promotion, publicizing, exhibiting and exploiting the Program and others, in whole or in part, by any and all means, media, devices, processes and technology now or hereafter known or devised in perpetuity throughout the universe. In connection with such, the Releaser grants to the Producer the absolute and royalty-free right, on a fully paid-up basis, and unrestricted permission in respect of photographic portraits, pictures, audios, videos, articles that the Producer have taken or written regarding Releaser, to copy, distribute, modify, make derivative works of, sell, license, or otherwise exploit such. <br><br>The Releaser hereby holds harmless and release and forever discharge Producer from all claims and grants to Producer the right to edit, adapt, alter, rearrange, dub, interpolate or combine with others the recorded images, audio, name and/or likeness and/or voice of Releaser.<br><br>The Releaser represents to Producer that Producer’s exercise of the rights granted herein shall not violate or infringe any rights of any third party. Releaser understands that Producer has been induced to proceed with the production, distribution and exploitation of the Program in reliance upon this Agreement.   <br><br>Releaser hereby releases Producer, its successors, assignees and licensees from any and all claims and demands arising out of or in connection with such use, including, without limitation, any and all claims for invasion of privacy, infringement of Releaser’s right of publicity, defamation (including libel and slander), false light, and any other personal and/or property rights. The Releaser hereby waives any moral rights that Releaser may have in the recording. Producer may assign this Agreement to any party without the consent of or notice to the Releaser.<br><br>Please contact Bodhi Meditation Los Angeles Office at (909) 612-9232 if you have any questions.<br></div><div style=\"font-family: Arial, Verdana; font-style: normal; font-variant: normal; font-weight: normal; line-height: normal; text-align: left;\"><br></div><div style=\"font-family: Arial, Verdana; font-style: normal; font-variant: normal; line-height: normal; text-align: left;\"><div style=\"text-align: center;\"><b>授 权 书</b></div><br>    此签字人在参加美国国际菩提法门协会举办的禅修班，法会以及各项活动期间，被採录的所有声音、照片、影像等资料，自愿授予美国国际菩提法门协会及全球国际菩提法门机构,为推广菩提禅修所有宣传事宜等方面的永久拥有权和永久使用权（被採录的所有声音、照片、影像等）。签字人并授权菩提机构对被採录的所有声音、照片、影像等资料进行编辑之用(包括删改，配音等等)。鑑于菩提机构是国际非营利慈善团体，签字人将不会被支付任何费用和版税。<br><br><br>如有任何问题，请洽询洛杉矶禅堂办公室:<br>(626)457-5316 / (909)612-9636。<br></div></div>'),(8,4,'cn','潜在伤害风险免责与个人肖像授权声明','本人明白任何活动都有预料不到伤及身体的可能风险，这些风险也许是由于课堂活动学员自身或参加禅修班其他学员的行为造成的；万一发生这种现象，如前所述，学员同意完全承担这些风险以及因此而可能造成的损失、费用及责任。本人在此承认，授予并许可菩提禅修协会能在活动期间以记录本人名字或相似性质的纪录或声音、影像、图片摄影。并授权或无异议菩提禅修协会用于广告、宣传、展示和公开播放其拍摄全部或部分的图片、影音素材于各媒介、设备与流程。同时也授予菩提禅修协会，在不得违反或侵犯任何第三方权利之条件下有权修改、调整、改变、重制、配音或结合他人所记录的资料素材和声音制成合适的宣传品。'),(9,4,'en','一级班条款','<br>'),(10,4,'tw','潜在伤害风险免责与个人肖像授权声明','本人明白任何活动都有预料不到伤及身体的可能风险，这些风险也许是由于课堂活动学员自身或参加禅修班其他学员的行为造成的；万一发生这种现象，如前所述，\n学员同意完全承担这些风险以及因此而可能造成的损失、费用及责任。本人在此承认，授予并许可菩提禅修协会能在活动期间以记录本人名字或相似性质的纪录或声\n音、影像、图片摄影。并授权或无异议菩提禅修协会用于广告、宣传、展示和公开播放其拍摄全部或部分的图片、影音素材于各媒介、设备与流程。同时也授予菩提\n禅修协会，在不得违反或侵犯任何第三方权利之条件下有权修改、调整、改变、重制、配音或结合他人所记录的资料素材和声音制成合适的宣传品。'),(11,5,'tw','菩提禅修课程报名手续説明及协议 Bodhi Meditation Program Application Procedures and Agreement','<div style=\"text-align: center;\"><span style=\"font-size: 10pt;\"><b>菩提禅修课程报名手续説明及协议</b></span></div><div style=\"text-align: center;\"><span style=\"font-size: 10pt;\"><b>Bodhi Meditation Program&nbsp;</b></span></div><div style=\"text-align: center;\"><span style=\"font-size: 10pt;\"><b>Application Procedures and Agreement</b></span></div><br>1.	由美国国际菩提法门协会 (以下简称本会) 举办的菩提禅修<b>青少年夏令营</b> (以下简称夏令营) 为期三天。  	 毎天上午9:30 - 下午6:00 <br>The Bodhi Meditation Youth Summer Camp (hereafter referred to as “The Program”) held by American Int’l Puti (Bodhi) Dharma Society (hereafter referred to as “The Society”) is 3 days in length.  	Daily 9:30 AM – 6:00 PM<br><br>2.	学费: 全免 	Tuition: Waived <br><br>3.	本人同意，开课期间, 学员如对所使用的场所或场所内的设施造成损坏, 需按价赔偿。 由学员自身造成的行为风险事故、财物损失, 应由本人自行承负经济损失和法律责任，与本会、其职员或义工等无关。本协议同意双方的眷属、私人代表、继承人、委託人受合约或法律责任所约束.<br>In consideration of The Society furnishing facilities, supervisors, equipments or expenses, I agree to HEREBY RELEASE, INDEMNIFY, AND HOLD HARMLESS The Society, its affiliates, officers, employees, volunteers and agents, on behalf of myself, my child(ren), my heirs, assignees, administrators and executors, any and ALL RIGHTS AND CLAIMS OR INSURIES to property and/or person which undersigned or participant may sustain or incur as a result of, use of, or participation in the activities, events, or property by The Society.<br><br>4.	开课期间，协会透过照相、摄影等方式将所有学员和活动过程记录下来。本人了解并同意授权于协会，将学员姓名和肖像可以使用在协会的非商业出版刊物中，用于宣传和推广协会课程。<br>All participants in the Society’s programs, activities, and/or events are subject to being photographed and/or videotaped.  I hereby acknowledged and grant permission to the Society to record and use Releaser’s name and/or likeness and/or voice in the Society’s publications in connection with advertising, publicizing, exhibiting and exploiting the Program, in whole or in part, by any and all means, media, devices, processes and technology now or hereafter known. <br><br>5.	以上条款本人都已明白, 自愿接受所有要求. 本协议经本人签字后立即生效. <br>I have fully understood all of the above mentioned conditions of this Agreement, and willfully agree to abide by them. This Agreement shall be effective and binding on the parties upon my signature. <br><br>6.	以上这些条例仅限用本期夏令营. <br>The stipulations and conditions written above apply exclusively to the fore-mentioned session of The Program.&nbsp;<br><div><br></div><div>_____________________________________________________________</div><div><br></div><div><div style=\"text-align: center;\"><span style=\"font-size: 10pt;\"><b>INDIVIDUAL RELEASE</b></span></div><br>Producer’s full corporate name:              American Int’l Puti (Bodhi) Dharma Society (the “Producer”)<br>Street Address: 			          20657 Golden Springs Drive, Suite 111<br>City, State, ZIP Code:		          Diamond Bar, CA 91789<br><br>The undersigned person (“Releaser”), for goods and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, hereby grants permission and all necessary rights to Producer to record Releaser and use Releaser’s name and/or likeness and/or voice as same may appear in motion picture photography created by Producer in connection with the program currently entitled “Bodhi Meditation” (“Program”) and in connection with promotion, publicizing, exhibiting and exploiting the Program and others, in whole or in part, by any and all means, media, devices, processes and technology now or hereafter known or devised in perpetuity throughout the universe. In connection with such, the Releaser grants to the Producer the absolute and royalty-free right, on a fully paid-up basis, and unrestricted permission in respect of photographic portraits, pictures, audios, videos, articles that the Producer have taken or written regarding Releaser, to copy, distribute, modify, make derivative works of, sell, license, or otherwise exploit such. <br><br>The Releaser hereby holds harmless and release and forever discharge Producer from all claims and grants to Producer the right to edit, adapt, alter, rearrange, dub, interpolate or combine with others the recorded images, audio, name and/or likeness and/or voice of Releaser.<br><br>The Releaser represents to Producer that Producer’s exercise of the rights granted herein shall not violate or infringe any rights of any third party. Releaser understands that Producer has been induced to proceed with the production, distribution and exploitation of the Program in reliance upon this Agreement.   <br><br>Releaser hereby releases Producer, its successors, assignees and licensees from any and all claims and demands arising out of or in connection with such use, including, without limitation, any and all claims for invasion of privacy, infringement of Releaser’s right of publicity, defamation (including libel and slander), false light, and any other personal and/or property rights. The Releaser hereby waives any moral rights that Releaser may have in the recording. Producer may assign this Agreement to any party without the consent of or notice to the Releaser.<br><br>Please contact Bodhi Meditation Los Angeles Office at (909) 612-9232 if you have any questions.<br><br> <br><div style=\"text-align: center;\"><span style=\"font-size: 10pt;\"><b>授 权 书</b></span></div><br>    此签字人在参加美国国际菩提法门协会举办的禅修班，法会以及各项活动期间，被採录的所有声音、照片、影像等资料，自愿授予美国国际菩提法门协会及全球国际菩提法门机构,为推广菩提禅修所有宣传事宜等方面的永久拥有权和永久使用权（被採录的所有声音、照片、影像等）。签字人并授权菩提机构对被採录的所有声音、照片、影像等资料进行编辑之用(包括删改，配音等等)。鑑于菩提机构是国际非营利慈善团体，签字人将不会被支付任何费用和版税。<br><br><br>如有任何问题，请洽询洛杉矶禅堂办公室:<br>(626)457-5316 / (909)612-9636。<br></div>'),(12,6,'en','Individual and Risk Release','8.5 day health and happiness retreat &nbsp;Jan 24 - Feb 1, 2015<div>I assume all risks of damage and injuries that may occur to me while participating in the Bodhi Meditation course and while on the premises at which the classes are held. I am aware that some courses may involve yoga, mindful stretching and mental exercises. I hereby release and discharge the Canada Bodhi Dharma Society and its agents and representatives from all claims or injuries resulting from my participation in the program. I hereby grant permission to the Canada Bodhi Dharma Society, Including its successors and assignees to record and use my name, image and voice, for use in its promotional and informational productions. I further grant the Canada Bodhi Dharma Society permission to edit and modify these recordings in the making of productions as long as no third party\'s rights are infringed by their use. Lastly, I release any and all legal claims against the Canada Bodhi Dharma Association for using, distributing or broadcasting any productions.<br>I understand that I must not take part in any class offered in Canada Bodhi Dharma Society if I have either one of the followings: Serious heart conditions, Psychiatric Illnesses, contagious diseases and stage 4+ cancer.<br>I have read, understood, and I guarantee that all the information I have provided above is true and correct to the best of my knowledge. I agree to the above release. By signing this form, I also give the Canada Bodhi Dharma Society permission to keep me informed about this society through various media.</div>'),(13,7,'en','APPLICATION PROCEDURES AND AGREEMENT','1. The applicant must have graduated from the Bodhi Meditation &amp; Fitness Retreat Level 1. Shortly after The Society has chosen applicants for the Program, Notice of admittance and request for payment of tuition will be made by phone, fax, or e-mail. Applicants will not be admitted as students until tuition has been paid in full.<br>2. The prospective student acknowledges that any activity or exercise involves the risk and danger of bodily injury, and that such risks and dangers: a) may be caused by the student\'s own actions, or inactions, or the actions, or inactions of others participating in the Program. b) may not be known or readily foreseeable at this time. Notwithstanding the aforementioned, the student agrees to FULLY ACCEPT AND ASSUME ALL SUCH RISKS AND DANGERS, AND ALL RESPONSIBILITY FOR LOSSES, COSTS AND DAMAGES which may be suffered by the student, as a result of the student participation in the Program. <br>\n3. During this session of the Program, all methods and activities taught by the Society and the Society\'s instructors, employees, and agents are considered to be beneficial; however, the students, at all times, have the right and responsibility to choose for themselves whether to participate or not to participate in any of the methods and activities being taught, based on their own physical, psychological and spiritual condition. During participation in the Program, every action the student takes is of his/her own free will. The Society, and the Society\'s instructors, employees, and agents will not force or coerce any student to perform any methods or activities. Thus, during the student\'s participation in the Program, the student accepts sole responsibility for any physical, psychological or spiritual dilemmas the student experiences or causes to any other individual, irrespective of cause, including, but not limited to, the worsening of a health condition, hospitalization, and even death. Thus, the student HEREBY RELEASES and DISCHARGES the Society, and the Society\'s instructors, employees, and agents from all liability, claims, demands, losses or damage perceived to have been incurred during or because of participation in the Program. <br>\n4. In light of the release, waiver of liability and assumption of risk contained in this Agreement, the student agrees that if the student, or anyone on behalf of the student, makes a claim against the Society, or the Society\'s instructors, employees, and agents, the student WILL INDEMNIFY, SAVE AND HOLD HARMLESS the Society, and the Society\'s instructors, employees, and agents from any litigation expenses, lawyer\'s fees, loss liability, damage, or cost which any may incur as a result of such a claim, to the fullest extent permitted by law. <br>\n5. While participating in the Program, should the student break any of the above-mentioned regulations, the Society holds the right to dismiss the student from the Program at any time. <br>\n6.The Society is not responsible for any extra-curricular situations or concerns that arise. <br>\n7. In order to guarantee the proper instruction of the Program\'s methods, and to guarantee its effectiveness, students must agree to abide by the following conditions: Upon registration, do not conceal matters of personal health; the information filled in on each student\'s application form must match the information on their documents of official identification; The Society does not accept applicants having mental health issues, infectious disease, life-threatening illnesses, or incapable of performing Activities of Daily living. If an applicant is discovered withholding any information on his/her medical conditions, The Society holds the right to request the student to withdraw from the Program at any time. \n<br>8. I have fully understood all of the above mentioned conditions of this Agreement, and freely agree to abide by them. This Agreement shall be effective and binding on the parties upon signing by both parties. <br>\n9. This Agreement shall bind and insure to the benefit of the respective heirs, personal representatives, successors, and assignees of the parties. <br>\n10. The stipulations and conditions written above apply exclusively to the fore-mentioned session of the Program. INDIVIDUAL RELEASE Produce\'s full corporate name: The Canada Bodhi Dharma Society(The \"Producer\") This letter confirms that the undersigned person ( the \"Releaser\"), for good and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, hereby grants unrestricted permission to the Producer to record and use Releaser\'s name and /or likeness, voice, image, biographical material motion picture, photography, printed materials, electronic and web publications created by the Producer in connection with advertising, publicizing, exhibiting and exploiting the Program, in whole or in part, by any and all means, media, devices, processes and technology now or hereafter known of devised in perpetuity throughout the universe. The Releaser hereby grants to Producer the right to edit, adapt, alter, rearrange, dub, interpolate or combine with others the Recordings of the Releaser. The Releaser represents to Producer that Producer\'s exercise of the rights granted herein shall not violate or infringe any rights of any third party. The Releaser understands that Producer has been induced to proceed with production, distribution and exploitation of the Program in reliance upon this Agreement. The Releaser hereby releases the Producer, its successors, assignees and licensees from any and all claims and demands arising out of or in connection with such use, including, without limitation, any and all claims for invasion of privacy, infringement of Releaser\'s right of publicity, defamation ( including libel and slander), false light, and any other person and /or property rights. The Releaser hereby waives any moral rights that Releaser may have in the Recordings. The Producer may assign this Agreement to any party without the consent of a notice to the releaser.\n<br>Consent I am the parent or guardian of the minor named above and have the legal authority to execute the above release. I approve and waive any rights in the release. By signing this form, you also give the Canada Bodhi Dharma Society permission to keep you informed about this society through various media.'),(14,7,'en','2nd Level Agreement','1. The applicant must have graduated from the Bodhi Meditation &amp; Fitness Retreat Level 1. Shortly after The Society has chosen applicants for the Program, Notice of admittance and request for payment of tuition will be made by phone, fax, or e-mail. Applicants will not be admitted as students until tuition has been paid in full.<br>2. The prospective student acknowledges that any activity or exercise involves the risk and danger of bodily injury, and that such risks and dangers: a) may be caused by the student\'s own actions, or inactions, or the actions, or inactions of others participating in the Program. b) may not be known or readily foreseeable at this time. Notwithstanding the aforementioned, the student agrees to FULLY ACCEPT AND ASSUME ALL SUCH RISKS AND DANGERS, AND ALL RESPONSIBILITY FOR LOSSES, COSTS AND DAMAGES which may be suffered by the student, as a result of the student participation in the Program. <br>\n3. During this session of the Program, all methods and activities taught by the Society and the Society\'s instructors, employees, and agents are considered to be beneficial; however, the students, at all times, have the right and responsibility to choose for themselves whether to participate or not to participate in any of the methods and activities being taught, based on their own physical, psychological and spiritual condition. During participation in the Program, every action the student takes is of his/her own free will. The Society, and the Society\'s instructors, employees, and agents will not force or coerce any student to perform any methods or activities. Thus, during the student\'s participation in the Program, the student accepts sole responsibility for any physical, psychological or spiritual dilemmas the student experiences or causes to any other individual, irrespective of cause, including, but not limited to, the worsening of a health condition, hospitalization, and even death. Thus, the student HEREBY RELEASES and DISCHARGES the Society, and the Society\'s instructors, employees, and agents from all liability, claims, demands, losses or damage perceived to have been incurred during or because of participation in the Program. <br>\n4. In light of the release, waiver of liability and assumption of risk contained in this Agreement, the student agrees that if the student, or anyone on behalf of the student, makes a claim against the Society, or the Society\'s instructors, employees, and agents, the student WILL INDEMNIFY, SAVE AND HOLD HARMLESS the Society, and the Society\'s instructors, employees, and agents from any litigation expenses, lawyer\'s fees, loss liability, damage, or cost which any may incur as a result of such a claim, to the fullest extent permitted by law. <br>\n5. While participating in the Program, should the student break any of the above-mentioned regulations, the Society holds the right to dismiss the student from the Program at any time. <br>\n6.The Society is not responsible for any extra-curricular situations or concerns that arise. <br>\n7. In order to guarantee the proper instruction of the Program\'s methods, and to guarantee its effectiveness, students must agree to abide by the following conditions: Upon registration, do not conceal matters of personal health; the information filled in on each student\'s application form must match the information on their documents of official identification; The Society does not accept applicants having mental health issues, infectious disease, life-threatening illnesses, or incapable of performing Activities of Daily living. If an applicant is discovered withholding any information on his/her medical conditions, The Society holds the right to request the student to withdraw from the Program at any time. \n<br>8. I have fully understood all of the above mentioned conditions of this Agreement, and freely agree to abide by them. This Agreement shall be effective and binding on the parties upon signing by both parties. <br>\n9. This Agreement shall bind and insure to the benefit of the respective heirs, personal representatives, successors, and assignees of the parties. <br>\n10. The stipulations and conditions written above apply exclusively to the fore-mentioned session of the Program. INDIVIDUAL RELEASE Produce\'s full corporate name: The Canada Bodhi Dharma Society(The \"Producer\") This letter confirms that the undersigned person ( the \"Releaser\"), for good and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, hereby grants unrestricted permission to the Producer to record and use Releaser\'s name and /or likeness, voice, image, biographical material motion picture, photography, printed materials, electronic and web publications created by the Producer in connection with advertising, publicizing, exhibiting and exploiting the Program, in whole or in part, by any and all means, media, devices, processes and technology now or hereafter known of devised in perpetuity throughout the universe. The Releaser hereby grants to Producer the right to edit, adapt, alter, rearrange, dub, interpolate or combine with others the Recordings of the Releaser. The Releaser represents to Producer that Producer\'s exercise of the rights granted herein shall not violate or infringe any rights of any third party. The Releaser understands that Producer has been induced to proceed with production, distribution and exploitation of the Program in reliance upon this Agreement. The Releaser hereby releases the Producer, its successors, assignees and licensees from any and all claims and demands arising out of or in connection with such use, including, without limitation, any and all claims for invasion of privacy, infringement of Releaser\'s right of publicity, defamation ( including libel and slander), false light, and any other person and /or property rights. The Releaser hereby waives any moral rights that Releaser may have in the Recordings. The Producer may assign this Agreement to any party without the consent of a notice to the releaser.\n<br>Consent I am the parent or guardian of the minor named above and have the legal authority to execute the above release. I approve and waive any rights in the release. By signing this form, you also give the Canada Bodhi Dharma Society permission to keep you informed about this society through various media.'),(15,8,'en','Individual and Risk Release','<font size=\"2\">1. The applicant must have graduated from the Bodhi Meditation &amp; \nFitness Retreat Level 2. Shortly after The Society has chosen applicants\n for the Program, Notice of admittance and request for payment of \ntuition will be made by phone, fax, or e-mail. Applicants will not be \nadmitted as students until tuition has been paid in full.<br>2. The \nprospective student acknowledges that any activity or exercise involves \nthe risk and danger of bodily injury, and that such risks and dangers: \na) may be caused by the student\'s own actions, or inactions, or the \nactions, or inactions of others participating in the Program. b) may not\n be known or readily foreseeable at this time. Notwithstanding the \naforementioned, the student agrees to FULLY ACCEPT AND ASSUME ALL SUCH \nRISKS AND DANGERS, AND ALL RESPONSIBILITY FOR LOSSES, COSTS AND DAMAGES \nwhich may be suffered by the student, as a result of the student \nparticipation in the Program. <br>\n3. During this session of the Program, all methods and activities taught\n by the Society and the Society\'s instructors, employees, and agents are\n considered to be beneficial; however, the students, at all times, have \nthe right and responsibility to choose for themselves whether to \nparticipate or not to participate in any of the methods and activities \nbeing taught, based on their own physical, psychological and spiritual \ncondition. During participation in the Program, every action the student\n takes is of his/her own free will. The Society, and the Society\'s \ninstructors, employees, and agents will not force or coerce any student \nto perform any methods or activities. Thus, during the student\'s \nparticipation in the Program, the student accepts sole responsibility \nfor any physical, psychological or spiritual dilemmas the student \nexperiences or causes to any other individual, irrespective of cause, \nincluding, but not limited to, the worsening of a health condition, \nhospitalization, and even death. Thus, the student HEREBY RELEASES and \nDISCHARGES the Society, and the Society\'s instructors, employees, and \nagents from all liability, claims, demands, losses or damage perceived \nto have been incurred during or because of participation in the Program.\n <br>\n4. In light of the release, waiver of liability and assumption of risk \ncontained in this Agreement, the student agrees that if the student, or \nanyone on behalf of the student, makes a claim against the Society, or \nthe Society\'s instructors, employees, and agents, the student WILL \nINDEMNIFY, SAVE AND HOLD HARMLESS the Society, and the Society\'s \ninstructors, employees, and agents from any litigation expenses, \nlawyer\'s fees, loss liability, damage, or cost which any may incur as a \nresult of such a claim, to the fullest extent permitted by law. <br>\n5. While participating in the Program, should the student break any of \nthe above-mentioned regulations, the Society holds the right to dismiss \nthe student from the Program at any time. <br>\n6.The Society is not responsible for any extra-curricular situations or concerns that arise. <br>\n7. In order to guarantee the proper instruction of the Program\'s \nmethods, and to guarantee its effectiveness, students must agree to \nabide by the following conditions: Upon registration, do not conceal \nmatters of personal health; the information filled in on each student\'s \napplication form must match the information on their documents of \nofficial identification; The Society does not accept applicants having \nmental health issues, infectious diseases, life-threatening illnesses, serious heart conditions, psychiatric illnesses, contagious diseases, stage 4+ cancer or\n incapable of performing Activities of Daily living. If an applicant is \ndiscovered withholding any information on his/her medical conditions, \nThe Society holds the right to request the student to withdraw from the \nProgram at any time. \n<br>8. I have fully understood all of the above mentioned conditions of \nthis Agreement, and freely agree to abide by them. This Agreement shall \nbe effective and binding on the parties upon signing by both parties. <br>\n9. This Agreement shall bind and insure to the benefit of the respective\n heirs, personal representatives, successors, and assignees of the \nparties. <br>\n10. The stipulations and conditions written above apply exclusively to \nthe fore-mentioned session of the Program. INDIVIDUAL RELEASE Produce\'s \nfull corporate name: The Canada Bodhi Dharma Society(The \"Producer\") \nThis letter confirms that the undersigned person ( the \"Releaser\"), for \ngood and valuable consideration, the receipt and sufficiency of which is\n hereby acknowledged, hereby grants unrestricted permission to the \nProducer to record and use Releaser\'s name and /or likeness, voice, \nimage, biographical material motion picture, photography, printed \nmaterials, electronic and web publications created by the Producer in \nconnection with advertising, publicizing, exhibiting and exploiting the \nProgram, in whole or in part, by any and all means, media, devices, \nprocesses and technology now or hereafter known of devised in perpetuity\n throughout the universe. The Releaser hereby grants to Producer the \nright to edit, adapt, alter, rearrange, dub, interpolate or combine with\n others the Recordings of the Releaser. The Releaser represents to \nProducer that Producer\'s exercise of the rights granted herein shall not\n violate or infringe any rights of any third party. The Releaser \nunderstands that Producer has been induced to proceed with production, \ndistribution and exploitation of the Program in reliance upon this \nAgreement. The Releaser hereby releases the Producer, its successors, \nassignees and licensees from any and all claims and demands arising out \nof or in connection with such use, including, without limitation, any \nand all claims for invasion of privacy, infringement of Releaser\'s right\n of publicity, defamation ( including libel and slander), false light, \nand any other person and /or property rights. The Releaser hereby waives\n any moral rights that Releaser may have in the Recordings. The Producer\n may assign this Agreement to any party without the consent of a notice \nto the releaser.\n<br>Consent I am the parent or guardian of the minor named above and \nhave the legal authority to execute the above release. I approve and \nwaive any rights in the release. By signing this form, I also give the\n Canada Bodhi Dharma Society permission to keep me informed about this \nsociety through various media.</font>'),(16,9,'en','Individual and Risk Release','<font size=\"2\">1. The applicant must have graduated from the Bodhi Meditation &amp; \nFitness Retreat Level 2. Shortly after The Society has chosen applicants\n for the Program, Notice of admittance and request for payment of \ntuition will be made by phone, fax, or e-mail. Applicants will not be \nadmitted as students until tuition has been paid in full.<br>2. The \nprospective student acknowledges that any activity or exercise involves \nthe risk and danger of bodily injury, and that such risks and dangers: \na) may be caused by the student\'s own actions, or inactions, or the \nactions, or inactions of others participating in the Program. b) may not\n be known or readily foreseeable at this time. Notwithstanding the \naforementioned, the student agrees to FULLY ACCEPT AND ASSUME ALL SUCH \nRISKS AND DANGERS, AND ALL RESPONSIBILITY FOR LOSSES, COSTS AND DAMAGES \nwhich may be suffered by the student, as a result of the student \nparticipation in the Program. <br>\n3. During this session of the Program, all methods and activities taught\n by the Society and the Society\'s instructors, employees, and agents are\n considered to be beneficial; however, the students, at all times, have \nthe right and responsibility to choose for themselves whether to \nparticipate or not to participate in any of the methods and activities \nbeing taught, based on their own physical, psychological and spiritual \ncondition. During participation in the Program, every action the student\n takes is of his/her own free will. The Society, and the Society\'s \ninstructors, employees, and agents will not force or coerce any student \nto perform any methods or activities. Thus, during the student\'s \nparticipation in the Program, the student accepts sole responsibility \nfor any physical, psychological or spiritual dilemmas the student \nexperiences or causes to any other individual, irrespective of cause, \nincluding, but not limited to, the worsening of a health condition, \nhospitalization, and even death. Thus, the student HEREBY RELEASES and \nDISCHARGES the Society, and the Society\'s instructors, employees, and \nagents from all liability, claims, demands, losses or damage perceived \nto have been incurred during or because of participation in the Program.\n <br>\n4. In light of the release, waiver of liability and assumption of risk \ncontained in this Agreement, the student agrees that if the student, or \nanyone on behalf of the student, makes a claim against the Society, or \nthe Society\'s instructors, employees, and agents, the student WILL \nINDEMNIFY, SAVE AND HOLD HARMLESS the Society, and the Society\'s \ninstructors, employees, and agents from any litigation expenses, \nlawyer\'s fees, loss liability, damage, or cost which any may incur as a \nresult of such a claim, to the fullest extent permitted by law. <br>\n5. While participating in the Program, should the student break any of \nthe above-mentioned regulations, the Society holds the right to dismiss \nthe student from the Program at any time. <br>\n6.The Society is not responsible for any extra-curricular situations or concerns that arise. <br>\n7. In order to guarantee the proper instruction of the Program\'s \nmethods, and to guarantee its effectiveness, students must agree to \nabide by the following conditions: Upon registration, do not conceal \nmatters of personal health; the information filled in on each student\'s \napplication form must match the information on their documents of \nofficial identification; The Society does not accept applicants having \nmental health issues, infectious diseases, life-threatening illnesses, \nserious heart conditions, psychiatric illnesses, contagious diseases, \nstage 4+ cancer or\n incapable of performing Activities of Daily living. If an applicant is \ndiscovered withholding any information on his/her medical conditions, \nThe Society holds the right to request the student to withdraw from the \nProgram at any time. \n<br>8. I have fully understood all of the above mentioned conditions of \nthis Agreement, and freely agree to abide by them. This Agreement shall \nbe effective and binding on the parties upon signing by both parties. <br>\n9. This Agreement shall bind and insure to the benefit of the respective\n heirs, personal representatives, successors, and assignees of the \nparties. <br>\n10. The stipulations and conditions written above apply exclusively to \nthe fore-mentioned session of the Program. INDIVIDUAL RELEASE Produce\'s \nfull corporate name: The Canada Bodhi Dharma Society(The \"Producer\") \nThis letter confirms that the undersigned person ( the \"Releaser\"), for \ngood and valuable consideration, the receipt and sufficiency of which is\n hereby acknowledged, hereby grants unrestricted permission to the \nProducer to record and use Releaser\'s name and /or likeness, voice, \nimage, biographical material motion picture, photography, printed \nmaterials, electronic and web publications created by the Producer in \nconnection with advertising, publicizing, exhibiting and exploiting the \nProgram, in whole or in part, by any and all means, media, devices, \nprocesses and technology now or hereafter known of devised in perpetuity\n throughout the universe. The Releaser hereby grants to Producer the \nright to edit, adapt, alter, rearrange, dub, interpolate or combine with\n others the Recordings of the Releaser. The Releaser represents to \nProducer that Producer\'s exercise of the rights granted herein shall not\n violate or infringe any rights of any third party. The Releaser \nunderstands that Producer has been induced to proceed with production, \ndistribution and exploitation of the Program in reliance upon this \nAgreement. The Releaser hereby releases the Producer, its successors, \nassignees and licensees from any and all claims and demands arising out \nof or in connection with such use, including, without limitation, any \nand all claims for invasion of privacy, infringement of Releaser\'s right\n of publicity, defamation ( including libel and slander), false light, \nand any other person and /or property rights. The Releaser hereby waives\n any moral rights that Releaser may have in the Recordings. The Producer\n may assign this Agreement to any party without the consent of a notice \nto the releaser.\n<br>Consent I am the parent or guardian of the minor named above and \nhave the legal authority to execute the above release. I approve and \nwaive any rights in the release. By signing this form, I also give the\n Canada Bodhi Dharma Society permission to keep me informed about this \nsociety through various media.</font>'),(17,9,'cn','三级班报名条件','1）有初级健身班结业证,以及二级班的毕业证书.<br>2）填写并签署“三级班学员申请表”<br>3）是弟子，或有意愿成为弟子并提交《拜师申请表》<br>4）报名费$100加元 + 5% GST<b><br></b><div align=\"center\"><b>申请菩提禅修班说明及协议</b><br></div>1、由加拿大菩提法门协会（以下简称协会）举办的菩提禅修三级班为期12天。<br><br>2、协会一旦决定接纳申请者为三级班的学员后，将会通过电话、传真或电子信件等向申请者发出接收的通知后才能成为三级班的学生。<br><br>3、学员如果对所使用的场所以及场所里的设施造成损坏，要按价赔偿。<br><br>4、 学员应该明白任何活动都有预料不到的伤害及风险, 这些风险也许是由于学员自身或参加班的其他人的行为造成的; 万一发生这种现象，如前所述, 学员同意完全承担这些风险以及由此而可能造成的损失、费用及责任。<br><br>5、\n 办班期间，协会的老师、工作人员和代理人所教授的方法和动作，对人的身体和精神都是有益的。 \n但是学员有权根据自己的身体和精神状况对这些方法和动作做出取捨，学员在办班期间的一切行为都是自愿的，协会、协会的老师、工作人员和代理人不强迫学员使\n \n用任何方法和动作。因此，学员在参加班期间，无论任何自身原因－身体、心理或精神上出现各种非正常状况，包括病情加重，赴医院急救，乃至死亡等；或者对其\n 他人造成身体、心理或精神伤害，概由自己负责；学员豁免协会、 协会的老师、工作人员和代理人承担任何与之相关的经济或法律责任。<br><br>6、 根据本协议中所涉及的责任豁免, 学员同意，即使学员本人、或由任何人代理学员来起诉协会、协会的老师、 工作人员和代理人，也要全面免除协会、协会的老师、工作人员和代理人承担任何与起诉相关的诉讼费、律师费及可能由起诉引起的任何其它责任与费用。<br>7、为了保障教学秩序及禅修效果，请学员自觉遵守以下条例:<br><br>&nbsp;&nbsp;&nbsp; 报名时不得隐瞒自身疾病状况；所填写申请资料须与个人证件身份相符；<br><br>&nbsp;&nbsp;&nbsp; 协会不接受精神病患者、传染病患者、重大疾病患者或生活无法自理者的入学申请。如&nbsp;&nbsp;&nbsp; 发现对自身疾病状况隐瞒者，协会有权在任何时间要求该学员退出学习班。<br><br>&nbsp;&nbsp;&nbsp; 服从管理，不得有无理要求；<br><br>&nbsp;&nbsp;&nbsp; 不能有任何形式的歧视（包括宗教信仰、种族、国籍、性别等）；<br><br>&nbsp;&nbsp;&nbsp; 尊重老师，不得有任何不礼貌的言行；<br><br>&nbsp;&nbsp;&nbsp; 禅修时必须统一着装（本协会备有专用服装，方便禅修，费用自付）；<br><br>&nbsp;&nbsp;&nbsp; 遵守上课和禅修时间，未经允许，不得迟到、早退和无故旷课；<br><br>&nbsp;&nbsp;&nbsp; 不得大声喧哗、干扰正常的上课、作息秩序；不得以任何形式妨碍、干扰他人；<br><br>&nbsp;&nbsp;&nbsp; 不得传播非本协会教授的任何健身方法、修行方法及宗教内容；<br><br>&nbsp;&nbsp;&nbsp; 未经许可严禁照相、录像及录音；<br><br>&nbsp;&nbsp;&nbsp; 严禁抽烟、饮酒和使用毒品;<br><br>&nbsp;&nbsp;&nbsp; 不能在上课和禅修时吃东西；<br><br>&nbsp;&nbsp;&nbsp; 上课和禅修时关闭手机，不得携带妨碍他人静修的任何物品；<br><br>8、办班期间，如果学员违反以上任何一款条例者，协会有权在任何时间要求该学员退出三级班。<br><br>9、本协会不承担任何与教学课程无关的事务和责任.<br><br>10、以上条款本人都已明白，自愿接受所有要求。本协议经双方签字后立即生效。<br><br>11、本协议受辖于卑诗省法律。一旦发生纠纷双方同意在诉诸法律之前通过仲裁解决纠纷.<br><br>12、本协议同意双方的后代, 私人代表, 继承人, 委托人为合约或法律责任所约束。<br><br>13、学员明白为了方便中英文学员阅读，此申请表特设中英文两种语言，两种语言的涵义均一致。读懂任何一种语言后签署该协议都是有效的。<br>14、以上这些条例仅限用本期禅修班。<br><br><div align=\"center\"><h3><b>个人资料授权书</b></h3></div>被授权人名称：加拿大菩提法门协会（以下简称「被授权人」）<br><br>被授权人地址：7740 Alderbridge Way,Richmond,B.C.<br><br>本\n人，即本授权书的签署人(以下简称「授权人」)，基于有效的约因，在此同意授予被授权人以下权利：被授权人可以不受限地记录和使用授权人的姓名和/或肖\n像、声音、形象、履歷资料(以下统称为「个人资料」) \n，被授权人可以为广告、宣传、展览及开发等目的，在其创作的有关菩提禅修的书面、影视、摄影、印刷品、电子和网络出版物(以下简称「项目」)中，以目前已\n有或将来可能出现的任何方式、媒介、设备、工艺和技术，在全球范围内永久地全部或部分地使用个人资料。<br><br>授权人同意被授权人对个人资料进行编辑、改编、修改、重新排序、复制，或与其他作品或资料混合编辑使用。<br><br>被授权人行使本授权书赋予之权利时，不得违反或侵犯任何第三方权利或权益。授权人知悉并理解被授权人已基于本授权书开始实施项目的制作、发佈和开发等工作。<br>授\n权人同意豁免被授权人、其继受人和受让人、被许可人因行使本授权书项下权利而引发的任何诉讼或索赔请求，包括但不限于任何针对侵犯隐私权、侵犯公开权、诽\n谤、错误披露隐私及侵犯其他人身和/或财产权的诉讼或索赔请求。授权人就个人资料放弃任何道义上的权利。被授权人无需通知或经授权人同意，即可将此授权书\n赋予的权利转让给任何第三方。'),(18,10,'en','Individual and Risk Release','<font size=\"2\">1. The applicant must have graduated from the Bodhi Meditation &amp; \nFitness Retreat Level 1. Shortly after The Society has chosen applicants\n for the Program, Notice of admittance and request for payment of \ntuition will be made by phone, fax, or e-mail. Applicants will not be \nadmitted as students until tuition has been paid in full.<br>2. The \nprospective student acknowledges that any activity or exercise involves \nthe risk and danger of bodily injury, and that such risks and dangers: \na) may be caused by the student\'s own actions, or inactions, or the \nactions, or inactions of others participating in the Program. b) may not\n be known or readily foreseeable at this time. Notwithstanding the \naforementioned, the student agrees to FULLY ACCEPT AND ASSUME ALL SUCH \nRISKS AND DANGERS, AND ALL RESPONSIBILITY FOR LOSSES, COSTS AND DAMAGES \nwhich may be suffered by the student, as a result of the student \nparticipation in the Program. <br>\n3. During this session of the Program, all methods and activities taught\n by the Society and the Society\'s instructors, employees, and agents are\n considered to be beneficial; however, the students, at all times, have \nthe right and responsibility to choose for themselves whether to \nparticipate or not to participate in any of the methods and activities \nbeing taught, based on their own physical, psychological and spiritual \ncondition. During participation in the Program, every action the student\n takes is of his/her own free will. The Society, and the Society\'s \ninstructors, employees, and agents will not force or coerce any student \nto perform any methods or activities. Thus, during the student\'s \nparticipation in the Program, the student accepts sole responsibility \nfor any physical, psychological or spiritual dilemmas the student \nexperiences or causes to any other individual, irrespective of cause, \nincluding, but not limited to, the worsening of a health condition, \nhospitalization, and even death. Thus, the student HEREBY RELEASES and \nDISCHARGES the Society, and the Society\'s instructors, employees, and \nagents from all liability, claims, demands, losses or damage perceived \nto have been incurred during or because of participation in the Program.\n <br>\n4. In light of the release, waiver of liability and assumption of risk \ncontained in this Agreement, the student agrees that if the student, or \nanyone on behalf of the student, makes a claim against the Society, or \nthe Society\'s instructors, employees, and agents, the student WILL \nINDEMNIFY, SAVE AND HOLD HARMLESS the Society, and the Society\'s \ninstructors, employees, and agents from any litigation expenses, \nlawyer\'s fees, loss liability, damage, or cost which any may incur as a \nresult of such a claim, to the fullest extent permitted by law. <br>\n5. While participating in the Program, should the student break any of \nthe above-mentioned regulations, the Society holds the right to dismiss \nthe student from the Program at any time. <br>\n6.The Society is not responsible for any extra-curricular situations or concerns that arise. <br>\n7. In order to guarantee the proper instruction of the Program\'s \nmethods, and to guarantee its effectiveness, students must agree to \nabide by the following conditions: Upon registration, do not conceal \nmatters of personal health; the information filled in on each student\'s \napplication form must match the information on their documents of \nofficial identification; The Society does not accept applicants having \nmental health issues, infectious diseases, life-threatening illnesses, \nserious heart conditions, psychiatric illnesses, contagious diseases, \nstage 4+ cancer or\n incapable of performing Activities of Daily living. If an applicant is \ndiscovered withholding any information on his/her medical conditions, \nThe Society holds the right to request the student to withdraw from the \nProgram at any time. \n<br>8. I have fully understood all of the above mentioned conditions of \nthis Agreement, and freely agree to abide by them. This Agreement shall \nbe effective and binding on the parties upon signing by both parties. <br>\n9. This Agreement shall bind and insure to the benefit of the respective\n heirs, personal representatives, successors, and assignees of the \nparties. <br>\n10. The stipulations and conditions written above apply exclusively to \nthe fore-mentioned session of the Program. INDIVIDUAL RELEASE Produce\'s \nfull corporate name: The Canada Bodhi Dharma Society(The \"Producer\") \nThis letter confirms that the undersigned person ( the \"Releaser\"), for \ngood and valuable consideration, the receipt and sufficiency of which is\n hereby acknowledged, hereby grants unrestricted permission to the \nProducer to record and use Releaser\'s name and /or likeness, voice, \nimage, biographical material motion picture, photography, printed \nmaterials, electronic and web publications created by the Producer in \nconnection with advertising, publicizing, exhibiting and exploiting the \nProgram, in whole or in part, by any and all means, media, devices, \nprocesses and technology now or hereafter known of devised in perpetuity\n throughout the universe. The Releaser hereby grants to Producer the \nright to edit, adapt, alter, rearrange, dub, interpolate or combine with\n others the Recordings of the Releaser. The Releaser represents to \nProducer that Producer\'s exercise of the rights granted herein shall not\n violate or infringe any rights of any third party. The Releaser \nunderstands that Producer has been induced to proceed with production, \ndistribution and exploitation of the Program in reliance upon this \nAgreement. The Releaser hereby releases the Producer, its successors, \nassignees and licensees from any and all claims and demands arising out \nof or in connection with such use, including, without limitation, any \nand all claims for invasion of privacy, infringement of Releaser\'s right\n of publicity, defamation ( including libel and slander), false light, \nand any other person and /or property rights. The Releaser hereby waives\n any moral rights that Releaser may have in the Recordings. The Producer\n may assign this Agreement to any party without the consent of a notice \nto the releaser.\n<br>Consent I am the parent or guardian of the minor named above and \nhave the legal authority to execute the above release. I approve and \nwaive any rights in the release. By signing this form, I also give the\n Canada Bodhi Dharma Society permission to keep me informed about this \nsociety through various media.</font>'),(19,10,'cn','二级班报名条件','1）有初级健身班结业证<br>2）填写并签署“二级班学员申请表”<br>3）是弟子，或有意愿成为弟子并提交《拜师申请表》<br>4）报名费$100加元 + 5% GST<b><br></b><div align=\"center\"><b>申请菩提禅修班说明及协议</b><br></div>1、由加拿大菩提法门协会（以下简称协会）举办的菩提禅修二级班为期12天。<br><br>2、协会一旦决定接纳申请者为二级班的学员后，将会通过电话、传真或电子信件等向申请者发出接收的通知后才能成为二级班的学生。<br><br>3、学员如果对所使用的场所以及场所里的设施造成损坏，要按价赔偿。<br><br>4、 学员应该明白任何活动都有预料不到的伤害及风险, 这些风险也许是由于学员自身或参加班的其他人的行为造成的; 万一发生这种现象，如前所述, 学员同意完全承担这些风险以及由此而可能造成的损失、费用及责任。<br><br>5、\n 办班期间，协会的老师、工作人员和代理人所教授的方法和动作，对人的身体和精神都是有益的。 \n但是学员有权根据自己的身体和精神状况对这些方法和动作做出取捨，学员在办班期间的一切行为都是自愿的，协会、协会的老师、工作人员和代理人不强迫学员使\n \n用任何方法和动作。因此，学员在参加班期间，无论任何自身原因－身体、心理或精神上出现各种非正常状况，包括病情加重，赴医院急救，乃至死亡等；或者对其\n 他人造成身体、心理或精神伤害，概由自己负责；学员豁免协会、 协会的老师、工作人员和代理人承担任何与之相关的经济或法律责任。<br><br>6、 根据本协议中所涉及的责任豁免, 学员同意，即使学员本人、或由任何人代理学员来起诉协会、协会的老师、 工作人员和代理人，也要全面免除协会、协会的老师、工作人员和代理人承担任何与起诉相关的诉讼费、律师费及可能由起诉引起的任何其它责任与费用。<br>7、为了保障教学秩序及禅修效果，请学员自觉遵守以下条例:<br><br>&nbsp;&nbsp;&nbsp; 报名时不得隐瞒自身疾病状况；所填写申请资料须与个人证件身份相符；<br><br>&nbsp;&nbsp;&nbsp; 协会不接受精神病患者、传染病患者、重大疾病患者或生活无法自理者的入学申请。如&nbsp;&nbsp;&nbsp; 发现对自身疾病状况隐瞒者，协会有权在任何时间要求该学员退出学习班。<br><br>&nbsp;&nbsp;&nbsp; 服从管理，不得有无理要求；<br><br>&nbsp;&nbsp;&nbsp; 不能有任何形式的歧视（包括宗教信仰、种族、国籍、性别等）；<br><br>&nbsp;&nbsp;&nbsp; 尊重老师，不得有任何不礼貌的言行；<br><br>&nbsp;&nbsp;&nbsp; 禅修时必须统一着装（本协会备有专用服装，方便禅修，费用自付）；<br><br>&nbsp;&nbsp;&nbsp; 遵守上课和禅修时间，未经允许，不得迟到、早退和无故旷课；<br><br>&nbsp;&nbsp;&nbsp; 不得大声喧哗、干扰正常的上课、作息秩序；不得以任何形式妨碍、干扰他人；<br><br>&nbsp;&nbsp;&nbsp; 不得传播非本协会教授的任何健身方法、修行方法及宗教内容；<br><br>&nbsp;&nbsp;&nbsp; 未经许可严禁照相、录像及录音；<br><br>&nbsp;&nbsp;&nbsp; 严禁抽烟、饮酒和使用毒品;<br><br>&nbsp;&nbsp;&nbsp; 不能在上课和禅修时吃东西；<br><br>&nbsp;&nbsp;&nbsp; 上课和禅修时关闭手机，不得携带妨碍他人静修的任何物品；<br><br>8、办班期间，如果学员违反以上任何一款条例者，协会有权在任何时间要求该学员退出二级班。<br><br>9、本协会不承担任何与教学课程无关的事务和责任.<br><br>10、以上条款本人都已明白，自愿接受所有要求。本协议经双方签字后立即生效。<br><br>11、本协议受辖于卑诗省法律。一旦发生纠纷双方同意在诉诸法律之前通过仲裁解决纠纷.<br><br>12、本协议同意双方的后代, 私人代表, 继承人, 委托人为合约或法律责任所约束。<br><br>13、学员明白为了方便中英文学员阅读，此申请表特设中英文两种语言，两种语言的涵义均一致。读懂任何一种语言后签署该协议都是有效的。<br>14、以上这些条例仅限用本期禅修班。<br><br><div align=\"center\"><h3><b>个人资料授权书</b></h3></div>被授权人名称：加拿大菩提法门协会（以下简称「被授权人」）<br><br>被授权人地址：7740 Alderbridge Way,Richmond,B.C.<br><br>本\n人，即本授权书的签署人(以下简称「授权人」)，基于有效的约因，在此同意授予被授权人以下权利：被授权人可以不受限地记录和使用授权人的姓名和/或肖\n像、声音、形象、履歷资料(以下统称为「个人资料」) \n，被授权人可以为广告、宣传、展览及开发等目的，在其创作的有关菩提禅修的书面、影视、摄影、印刷品、电子和网络出版物(以下简称「项目」)中，以目前已\n有或将来可能出现的任何方式、媒介、设备、工艺和技术，在全球范围内永久地全部或部分地使用个人资料。<br><br>授权人同意被授权人对个人资料进行编辑、改编、修改、重新排序、复制，或与其他作品或资料混合编辑使用。<br><br>被授权人行使本授权书赋予之权利时，不得违反或侵犯任何第三方权利或权益。授权人知悉并理解被授权人已基于本授权书开始实施项目的制作、发佈和开发等工作。<br>授\n权人同意豁免被授权人、其继受人和受让人、被许可人因行使本授权书项下权利而引发的任何诉讼或索赔请求，包括但不限于任何针对侵犯隐私权、侵犯公开权、诽\n谤、错误披露隐私及侵犯其他人身和/或财产权的诉讼或索赔请求。授权人就个人资料放弃任何道义上的权利。被授权人无需通知或经授权人同意，即可将此授权书\n赋予的权利转让给任何第三方。'),(20,8,'cn','三级班报名条件','1）有初级健身班结业证<br>2）填写并签署“三级班学员申请表”<br>3）是弟子，或有意愿成为弟子并提交《拜师申请表》<br>4）报名费$100加元 + 5% GST<b><br></b><div align=\"center\"><b>申请菩提禅修班说明及协议</b><br></div>1、由加拿大菩提法门协会（以下简称协会）举办的菩提禅修三级班为期12天。<br><br>2、协会一旦决定接纳申请者为三级班的学员后，将会通过电话、传真或电子信件等向申请者发出接收的通知后才能成为三级班的学生。<br><br>3、学员如果对所使用的场所以及场所里的设施造成损坏，要按价赔偿。<br><br>4、 学员应该明白任何活动都有预料不到的伤害及风险, 这些风险也许是由于学员自身或参加班的其他人的行为造成的; 万一发生这种现象，如前所述, 学员同意完全承担这些风险以及由此而可能造成的损失、费用及责任。<br><br>5、\n 办班期间，协会的老师、工作人员和代理人所教授的方法和动作，对人的身体和精神都是有益的。 \n但是学员有权根据自己的身体和精神状况对这些方法和动作做出取捨，学员在办班期间的一切行为都是自愿的，协会、协会的老师、工作人员和代理人不强迫学员使\n \n用任何方法和动作。因此，学员在参加班期间，无论任何自身原因－身体、心理或精神上出现各种非正常状况，包括病情加重，赴医院急救，乃至死亡等；或者对其\n 他人造成身体、心理或精神伤害，概由自己负责；学员豁免协会、 协会的老师、工作人员和代理人承担任何与之相关的经济或法律责任。<br><br>6、 根据本协议中所涉及的责任豁免, 学员同意，即使学员本人、或由任何人代理学员来起诉协会、协会的老师、 工作人员和代理人，也要全面免除协会、协会的老师、工作人员和代理人承担任何与起诉相关的诉讼费、律师费及可能由起诉引起的任何其它责任与费用。<br>7、为了保障教学秩序及禅修效果，请学员自觉遵守以下条例:<br><br>&nbsp;&nbsp;&nbsp; 报名时不得隐瞒自身疾病状况；所填写申请资料须与个人证件身份相符；<br><br>&nbsp;&nbsp;&nbsp; 协会不接受精神病患者、传染病患者、重大疾病患者或生活无法自理者的入学申请。如&nbsp;&nbsp;&nbsp; 发现对自身疾病状况隐瞒者，协会有权在任何时间要求该学员退出学习班。<br><br>&nbsp;&nbsp;&nbsp; 服从管理，不得有无理要求；<br><br>&nbsp;&nbsp;&nbsp; 不能有任何形式的歧视（包括宗教信仰、种族、国籍、性别等）；<br><br>&nbsp;&nbsp;&nbsp; 尊重老师，不得有任何不礼貌的言行；<br><br>&nbsp;&nbsp;&nbsp; 禅修时必须统一着装（本协会备有专用服装，方便禅修，费用自付）；<br><br>&nbsp;&nbsp;&nbsp; 遵守上课和禅修时间，未经允许，不得迟到、早退和无故旷课；<br><br>&nbsp;&nbsp;&nbsp; 不得大声喧哗、干扰正常的上课、作息秩序；不得以任何形式妨碍、干扰他人；<br><br>&nbsp;&nbsp;&nbsp; 不得传播非本协会教授的任何健身方法、修行方法及宗教内容；<br><br>&nbsp;&nbsp;&nbsp; 未经许可严禁照相、录像及录音；<br><br>&nbsp;&nbsp;&nbsp; 严禁抽烟、饮酒和使用毒品;<br><br>&nbsp;&nbsp;&nbsp; 不能在上课和禅修时吃东西；<br><br>&nbsp;&nbsp;&nbsp; 上课和禅修时关闭手机，不得携带妨碍他人静修的任何物品；<br><br>8、办班期间，如果学员违反以上任何一款条例者，协会有权在任何时间要求该学员退出三级班。<br><br>9、本协会不承担任何与教学课程无关的事务和责任.<br><br>10、以上条款本人都已明白，自愿接受所有要求。本协议经双方签字后立即生效。<br><br>11、本协议受辖于卑诗省法律。一旦发生纠纷双方同意在诉诸法律之前通过仲裁解决纠纷.<br><br>12、本协议同意双方的后代, 私人代表, 继承人, 委托人为合约或法律责任所约束。<br><br>13、学员明白为了方便中英文学员阅读，此申请表特设中英文两种语言，两种语言的涵义均一致。读懂任何一种语言后签署该协议都是有效的。<br>14、以上这些条例仅限用本期禅修班。<br><br><div align=\"center\"><h3><b>个人资料授权书</b></h3></div>被授权人名称：加拿大菩提法门协会（以下简称「被授权人」）<br><br>被授权人地址：7740 Alderbridge Way,Richmond,B.C.<br><br>本\n人，即本授权书的签署人(以下简称「授权人」)，基于有效的约因，在此同意授予被授权人以下权利：被授权人可以不受限地记录和使用授权人的姓名和/或肖\n像、声音、形象、履歷资料(以下统称为「个人资料」) \n，被授权人可以为广告、宣传、展览及开发等目的，在其创作的有关菩提禅修的书面、影视、摄影、印刷品、电子和网络出版物(以下简称「项目」)中，以目前已\n有或将来可能出现的任何方式、媒介、设备、工艺和技术，在全球范围内永久地全部或部分地使用个人资料。<br><br>授权人同意被授权人对个人资料进行编辑、改编、修改、重新排序、复制，或与其他作品或资料混合编辑使用。<br><br>被授权人行使本授权书赋予之权利时，不得违反或侵犯任何第三方权利或权益。授权人知悉并理解被授权人已基于本授权书开始实施项目的制作、发佈和开发等工作。<br>授\n权人同意豁免被授权人、其继受人和受让人、被许可人因行使本授权书项下权利而引发的任何诉讼或索赔请求，包括但不限于任何针对侵犯隐私权、侵犯公开权、诽\n谤、错误披露隐私及侵犯其他人身和/或财产权的诉讼或索赔请求。授权人就个人资料放弃任何道义上的权利。被授权人无需通知或经授权人同意，即可将此授权书\n赋予的权利转让给任何第三方。');

/*Table structure for table `puti_answers` */

DROP TABLE IF EXISTS `puti_answers`;

CREATE TABLE `puti_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `answer1` tinytext,
  `answer2` tinytext,
  `answer3` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_answers` */

/*Table structure for table `puti_attend` */

DROP TABLE IF EXISTS `puti_attend`;

CREATE TABLE `puti_attend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `idd` varchar(15) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `purpose` varchar(15) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_id` (`ref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_attend` */

/*Table structure for table `puti_branchs` */

DROP TABLE IF EXISTS `puti_branchs`;

CREATE TABLE `puti_branchs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1024) DEFAULT NULL,
  `internal` tinyint(1) DEFAULT '0',
  `sn` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `puti_branchs` */

insert  into `puti_branchs`(`id`,`title`,`internal`,`sn`) values (1,'English Teaching',0,33),(2,'Mandarin Teaching',0,22),(3,'Cantonese Teaching',0,44),(4,'Internal Teaching',1,77),(5,'Bagua Teaching',0,55),(6,'Other Teaching',0,66),(7,'Secret Teaching',1,88),(8,'Bodhi Dharma Name',1,99),(9,'mix teaching',0,11);

/*Table structure for table `puti_class` */

DROP TABLE IF EXISTS `puti_class`;

CREATE TABLE `puti_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` int(11) DEFAULT '0',
  `branch` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `agreement` int(11) DEFAULT '0',
  `date_length` int(11) DEFAULT '0',
  `checkin` tinyint(4) DEFAULT '0',
  `attend` int(11) DEFAULT '0',
  `meal` varchar(255) DEFAULT '0',
  `cert` tinyint(4) DEFAULT '0',
  `cert_prefix` varchar(31) DEFAULT NULL,
  `photo` tinyint(1) DEFAULT '0',
  `payfree` tinyint(1) DEFAULT '0',
  `payonce` tinyint(1) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  `logform` int(11) DEFAULT '0',
  `deleted` tinyint(4) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  `sn` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_class` */

/*Table structure for table `puti_class_checkin` */

DROP TABLE IF EXISTS `puti_class_checkin`;

CREATE TABLE `puti_class_checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `sn` int(11) DEFAULT NULL,
  `from_hh` varchar(2) DEFAULT NULL,
  `from_mm` varchar(2) DEFAULT NULL,
  `to_hh` varchar(2) DEFAULT NULL,
  `to_mm` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_class_checkin` */

/*Table structure for table `puti_class_date` */

DROP TABLE IF EXISTS `puti_class_date`;

CREATE TABLE `puti_class_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `day_no` tinyint(4) DEFAULT NULL,
  `start_time` varchar(10) DEFAULT NULL,
  `end_time` varchar(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `checkin` tinyint(4) DEFAULT NULL,
  `meal` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_class_date` */

/*Table structure for table `puti_department` */

DROP TABLE IF EXISTS `puti_department`;

CREATE TABLE `puti_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `en_title` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(4) DEFAULT NULL,
  `sn` int(11) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_department` */

/*Table structure for table `puti_department_job` */

DROP TABLE IF EXISTS `puti_department_job`;

CREATE TABLE `puti_department_job` (
  `department_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`department_id`,`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_department_job` */

/*Table structure for table `puti_department_volunteer` */

DROP TABLE IF EXISTS `puti_department_volunteer`;

CREATE TABLE `puti_department_volunteer` (
  `site` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `volunteer_id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`site`,`department_id`,`volunteer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_department_volunteer` */

/*Table structure for table `puti_device_record` */

DROP TABLE IF EXISTS `puti_device_record`;

CREATE TABLE `puti_device_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` int(11) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `idd` varchar(15) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_device_record` */

/*Table structure for table `puti_devices` */

DROP TABLE IF EXISTS `puti_devices`;

CREATE TABLE `puti_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(255) NOT NULL,
  `site` int(11) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `ip_address` varchar(31) DEFAULT NULL,
  `device_no` int(11) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_devices` */

/*Table structure for table `puti_dharma` */

DROP TABLE IF EXISTS `puti_dharma`;

CREATE TABLE `puti_dharma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dharma_prefix` varchar(12) DEFAULT NULL,
  `dharma_date` bigint(20) DEFAULT NULL,
  `dharma_site` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

/*Data for the table `puti_dharma` */

insert  into `puti_dharma`(`id`,`dharma_prefix`,`dharma_date`,`dharma_site`) values (1,'悟',1083999600,1),(2,'觉',1091948400,1),(3,'观',1102492800,1),(4,'悟',1105171200,1),(5,'慧',1107849600,1),(6,'月',1147071600,1),(7,'弥',1244444400,1),(8,'尚',1168243200,1),(9,'宝',1181286000,1),(10,'圆',1199779200,1),(11,'成',1204963200,1),(12,'信',1356336000,1),(13,'达',1368860400,1),(14,'琨',1381215600,1),(15,'生',1220857200,9),(16,'妙',1278572400,3),(17,'世',1155884400,1),(18,'空',1243494000,2),(19,'义',1372402800,2),(20,'愿',1348815600,6),(21,'顿',1392192000,3),(22,'典',1387699200,3),(23,'腾',1396940400,1),(24,'如',1304233200,6),(25,'备',1387872000,6),(26,'玛',1368860400,0),(27,'容',1348815600,6),(28,'弘',1254380400,6),(29,'灵',1398495600,1),(30,'供',1398495600,1),(31,'平',1412492400,8),(32,'宾',1414220400,1);

/*Table structure for table `puti_email` */

DROP TABLE IF EXISTS `puti_email`;

CREATE TABLE `puti_email` (
  `admin_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`admin_id`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_email` */

/*Table structure for table `puti_forms` */

DROP TABLE IF EXISTS `puti_forms`;

CREATE TABLE `puti_forms` (
  `id` int(11) NOT NULL,
  `template` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_forms` */

insert  into `puti_forms`(`id`,`template`,`title`) values (1,'level1_form.php','level1 form'),(2,'level2_form.php','level2 form'),(3,'level3_form.php','level3 form');

/*Table structure for table `puti_idd` */

DROP TABLE IF EXISTS `puti_idd`;

CREATE TABLE `puti_idd` (
  `member_id` int(11) NOT NULL,
  `idd` varchar(15) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_idd` */

/*Table structure for table `puti_info_carpool` */

DROP TABLE IF EXISTS `puti_info_carpool`;

CREATE TABLE `puti_info_carpool` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_info_carpool` */

insert  into `puti_info_carpool`(`id`,`title`,`description`) values (10,'By Walk',''),(20,'Public Transportation',''),(30,'I need carpool',''),(40,'I drive','');

/*Table structure for table `puti_info_hearfrom` */

DROP TABLE IF EXISTS `puti_info_hearfrom`;

CREATE TABLE `puti_info_hearfrom` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_info_hearfrom` */

insert  into `puti_info_hearfrom`(`id`,`title`,`description`) values (10,'Friend',NULL),(20,'Poster',NULL),(30,'Magazine',NULL),(40,'TV',NULL),(50,'Internet',NULL),(60,'Other',NULL),(5,'Family',NULL),(15,'Professional',NULL),(35,'newspaper',NULL),(55,'radio',NULL);

/*Table structure for table `puti_info_language` */

DROP TABLE IF EXISTS `puti_info_language`;

CREATE TABLE `puti_info_language` (
  `id` int(11) NOT NULL,
  `title` varchar(31) DEFAULT NULL,
  `description` varchar(63) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `sn` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_info_language` */

insert  into `puti_info_language`(`id`,`title`,`description`,`status`,`deleted`,`sn`) values (1,'lang.mandarin','Mandarin',1,0,99),(2,'lang.cantonese','Cantonese',1,0,88),(3,'lang.english','English',1,0,77),(4,'lang.spanish','Spanish',0,0,66),(5,'lang.vietnamese','Vietnamese',0,0,55),(6,'lang.malay','Malay',0,0,44),(99,'lang.others','Others',1,0,5);

/*Table structure for table `puti_info_symptom` */

DROP TABLE IF EXISTS `puti_info_symptom`;

CREATE TABLE `puti_info_symptom` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_info_symptom` */

insert  into `puti_info_symptom`(`id`,`title`,`description`) values (10,'Heart','Heart'),(20,'Back pain','Back pain'),(30,'High blood pressure','High Blood Pressure'),(40,'Diabetes','Diabetes'),(50,'Stroke','Stroke'),(60,'Cholesterol','Cholesterol'),(70,'Anxiety','Anxiety'),(80,'Rheumatism','Rheumatism'),(90,'Insomnia','Insomnia'),(100,'Digestive','Digestive'),(110,'Depression','Depression'),(120,'Weight issues','Weight issues'),(130,'Migraine','Migraine'),(140,'Anger','Anger'),(150,'Fatigue','Fatigue'),(900,'Other','Other');

/*Table structure for table `puti_info_title` */

DROP TABLE IF EXISTS `puti_info_title`;

CREATE TABLE `puti_info_title` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_info_title` */

insert  into `puti_info_title`(`id`,`title`,`description`) values (3,'未准金刚法师',NULL),(5,'准金剛法師',NULL),(10,'金刚法师',NULL),(20,'二级法师',NULL),(30,'三級法師',NULL),(40,'四級法師',NULL),(50,'五級法師',NULL),(60,'六級法師',NULL),(70,'七級法師',NULL),(80,'八級禪師',NULL),(90,'九級禪師',NULL);

/*Table structure for table `puti_members` */

DROP TABLE IF EXISTS `puti_members`;

CREATE TABLE `puti_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) DEFAULT '1',
  `deleted` tinyint(4) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  `last_login` bigint(20) DEFAULT '0',
  `hits` int(11) DEFAULT '0',
  `legal_first` varchar(255) DEFAULT NULL,
  `legal_last` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `dharma_name` varchar(63) DEFAULT NULL,
  `dharma_pinyin` varchar(63) DEFAULT NULL,
  `temp_dharma_name` varchar(63) DEFAULT NULL,
  `temp_dharma_pinyin` varchar(63) DEFAULT NULL,
  `degree` int(11) DEFAULT '0',
  `past_position` varchar(255) DEFAULT NULL,
  `current_position` varchar(255) DEFAULT NULL,
  `religion` int(11) DEFAULT '0',
  `apply_date` bigint(20) DEFAULT '0',
  `dharma_yy` int(11) DEFAULT '0',
  `dharma_mm` int(11) DEFAULT '0',
  `dharma_dd` int(11) DEFAULT '0',
  `dharma_date` bigint(20) DEFAULT '0',
  `alias` varchar(255) DEFAULT NULL,
  `gender` varchar(11) DEFAULT NULL,
  `birth_yy` int(11) DEFAULT '0',
  `birth_mm` int(11) DEFAULT '0',
  `birth_dd` int(11) DEFAULT '0',
  `birth_date` bigint(20) DEFAULT '0',
  `age` tinyint(4) DEFAULT '0',
  `language` int(11) DEFAULT '0',
  `email` varchar(1023) DEFAULT NULL,
  `email_flag` tinyint(1) DEFAULT '0',
  `phone` varchar(31) DEFAULT NULL,
  `cell` varchar(31) DEFAULT NULL,
  `contact_method` varchar(15) DEFAULT NULL,
  `address` varchar(1023) DEFAULT NULL,
  `city` varchar(127) DEFAULT NULL,
  `state` varchar(127) DEFAULT NULL,
  `country` varchar(127) DEFAULT NULL,
  `level` tinyint(4) DEFAULT '0',
  `identify_no` varchar(31) DEFAULT NULL,
  `idd` varchar(31) DEFAULT NULL,
  `postal` varchar(15) DEFAULT NULL,
  `site` int(11) DEFAULT '0',
  `online` tinyint(4) DEFAULT '0',
  `operator` int(11) DEFAULT '0',
  `sess_id` varchar(63) DEFAULT NULL,
  `sess_exp` bigint(20) DEFAULT '0',
  `password_link` varchar(63) DEFAULT NULL,
  `password_exp` bigint(20) DEFAULT '0',
  `password` varchar(15) DEFAULT NULL,
  `password_hits` int(11) DEFAULT '0',
  `member_yy` int(4) DEFAULT '0',
  `member_mm` int(2) DEFAULT '0',
  `member_dd` int(2) DEFAULT '0',
  `memo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_members` */

/*Table structure for table `puti_members_age` */

DROP TABLE IF EXISTS `puti_members_age`;

CREATE TABLE `puti_members_age` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `puti_members_age` */

insert  into `puti_members_age`(`id`,`title`,`description`) values (1,'0~25',''),(2,'26~40',''),(3,'41~55',''),(4,'56~65',''),(5,'66~up','');

/*Table structure for table `puti_members_hearfrom` */

DROP TABLE IF EXISTS `puti_members_hearfrom`;

CREATE TABLE `puti_members_hearfrom` (
  `member_id` int(11) NOT NULL,
  `hearfrom_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`hearfrom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_members_hearfrom` */

/*Table structure for table `puti_members_lang` */

DROP TABLE IF EXISTS `puti_members_lang`;

CREATE TABLE `puti_members_lang` (
  `member_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_members_lang` */

/*Table structure for table `puti_members_others` */

DROP TABLE IF EXISTS `puti_members_others`;

CREATE TABLE `puti_members_others` (
  `member_id` int(11) NOT NULL,
  `emergency_name` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(255) DEFAULT NULL,
  `emergency_ship` varchar(255) DEFAULT NULL,
  `therapy` tinyint(1) unsigned zerofill DEFAULT '0',
  `therapy_content` varchar(1023) DEFAULT NULL,
  `medical_concern` varchar(1023) DEFAULT NULL,
  `other_symptom` varchar(1023) DEFAULT NULL,
  `transportation` int(11) DEFAULT '0',
  `plate_no` varchar(31) DEFAULT NULL,
  `offer_carpool` tinyint(4) DEFAULT '0',
  `lang_main` varchar(255) DEFAULT NULL,
  `lang_able` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_members_others` */

/*Table structure for table `puti_members_symptom` */

DROP TABLE IF EXISTS `puti_members_symptom`;

CREATE TABLE `puti_members_symptom` (
  `member_id` int(11) NOT NULL,
  `symptom_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`symptom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_members_symptom` */

/*Table structure for table `puti_places` */

DROP TABLE IF EXISTS `puti_places`;

CREATE TABLE `puti_places` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_places` */

insert  into `puti_places`(`id`,`title`) values (0,'place unknown'),(1,'yaoshifo'),(2,'guanyin'),(3,'other yaoshifo'),(4,'other guanyin'),(5,'other'),(6,'yangyuantang');

/*Table structure for table `puti_questions` */

DROP TABLE IF EXISTS `puti_questions`;

CREATE TABLE `puti_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `filter` varchar(31) DEFAULT NULL,
  `question` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `puti_questions` */

insert  into `puti_questions`(`id`,`status`,`deleted`,`filter`,`question`) values (1,1,0,'member','Emergency contact name and relationship:'),(2,1,0,'member','How did you hear about us?'),(3,1,0,'member','Are you currently receiving therapy of some kind?'),(4,1,0,'member','If yes, please provide details regarding the nature of the therapy/treatment:'),(5,1,0,'member','Please write down any other medical concerns or history:'),(6,1,0,'member','<table border=\"0\" width=\"100%\"><tbody><tr>\r\n<td colspan=\"2\" align=\"center\"><span style=\"FONT-WEIGHT: bold; FONT-SIZE: 16px\">Individual and Risk Release</span> \r\n</td></tr>\r\n<tr>\r\n<td colspan=\"2\" align=\"left\"><span style=\"FONT-SIZE: 12px\">I assume all risks of \r\ndamage and injuries that may occur to me while participating in the Bodhi \r\nMeditation course and while on the premises at which the classes are held. I am \r\naware that some courses may involve yoga, mindful stretching and mental \r\nexercises. I hereby release and discharge the Canada Bodhi Dharma Society and \r\nits agents and representatives from all claims or injuries resulting from my \r\nparticipation in the program.<br><br>I hereby grant permission to the Canada \r\nBodhi Dharma Society, Including its successors and assignees to record and use \r\nmy name, image and voice, for use in its promotional and informational \r\nproductions. I further grant the Canada Bodhi Dharma Society permission to edit \r\nand modify these recordings in the making of productions as long as no third \r\nparty\'s rights are infringeed by their use. Lastly, I release any and all legal \r\nclaims against the Canada Bodhi Dharma Association for using, distributing or \r\nbroadcasting any productions.<br><br>I have read, understood, and I guarantee \r\nthat all the information I have provide above is true and correct to the best of \r\nmy knowledge. I agree to the above release. </span></td></tr></tbody></table>');

/*Table structure for table `puti_sites` */

DROP TABLE IF EXISTS `puti_sites`;

CREATE TABLE `puti_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `address` varchar(1023) DEFAULT NULL,
  `tel` varchar(1023) DEFAULT NULL,
  `email` varchar(1023) DEFAULT NULL,
  `cert_prefix` varchar(31) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `sn` int(11) DEFAULT NULL,
  `city` varchar(127) DEFAULT NULL,
  `state` varchar(127) DEFAULT NULL,
  `country` varchar(127) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `site_name_cn` varchar(255) DEFAULT NULL,
  `phone_cn` varchar(31) DEFAULT NULL,
  `site_name_en` varchar(255) DEFAULT NULL,
  `phone_en` varchar(31) DEFAULT NULL,
  `school_cn` varchar(255) DEFAULT NULL,
  `school_en` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `puti_sites` */

insert  into `puti_sites`(`id`,`title`,`address`,`tel`,`email`,`cert_prefix`,`status`,`sn`,`city`,`state`,`country`,`timezone`,`site_name_cn`,`phone_cn`,`site_name_en`,`phone_en`,`school_cn`,`school_en`) values (1,'Vancouver','150-7740 Alderbridge Way, Richmond, BC, V6X 2A3','English : 604-537-2268<br>中文 : 604-276-2885','englishinfo@putimeditation.ca','CVAN',1,11,'Vancouver','British Columbia','Canada','America/Los_Angeles','加拿大菩提法门协会','604-276-2885','The Canada Bodhi Dharma Society','604-537-2268','加拿大温哥华药师禅院','加拿大溫哥華藥師禪院'),(2,'Toronto','Unit 21 - 22, 4500 Sheppard Ave. East, Scarborough, ON M1S 1V2','(416)939-4325,  (647)388-5798','putitor@gmail.com','CTOT',1,22,'Toronto','Ontario','Canada','America/Toronto','','','','','',''),(3,'Hongkong','九龙荔枝角永康街10号中太工业大厦6/F','(+852) 2959-3238','','CNHK',1,77,'Hongkong','Hongkong','China','Asia/Shanghai','','','','','',''),(4,'Taibei','台北市中正区衡阳路51号13楼（东侧电梯）','(+886) 2-2313-1177','software@dharma.org.tw','CTBE',1,66,'Taiwan','Taiwan','China','Asia/Taipei','','','','','',''),(0,'Unknown','Unknown','Unknown','','Unknow',0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'Los Angeles','20657 Golden Spring Drive #111, Diamond Bar, CA 91789','Tel：(626) 457-5316 or  (909) 612-9636','putila@gmail.com','USLA',1,44,NULL,NULL,NULL,'America/Los_Angeles','洛杉矶菩提禅修','626-457-5316','L.A. Bodhi Meditation','626-457-5316','',''),(7,'New York','131-27 Fowler Ave., 2nd floor, Flushing, NY 11355','English: (516) 320-0860  or  Mandarin  (718) 886-1122','nypt886@gmail.com','USNY',1,55,NULL,NULL,NULL,'America/New_York','','','','','',''),(8,'Bailian & San Francisco','919 Hanson Court, Milpitas CA 95035','English: (408)856-8252  or Mandarin: (408)956-8662','putisfo@gmail.com','USBL',1,33,NULL,NULL,NULL,'America/Los_Angeles','','','','','',''),(9,'Malaysia','D-37-03 Dataran 3 Two, No.2, Jalan 19/1, 46300 Petaling Jaya, Selangor Darul Ehsan.','+603-7960-8066','putimsia@gmail.com','MLSEL',1,88,NULL,NULL,NULL,'Asia/Singapore','马来西亚菩提法门协会','+603-7960-8066','The Malaysia Bodhi Dharma Society','+603-7960-8066','',''),(10,'San Francisco','','','','SF',1,11,NULL,NULL,NULL,'America/Los_Angeles','','','','','',''),(11,'Alhambra','240 South Garfield Avenue, Alhambra, CA 91801, United States','','','USLH',1,22,NULL,NULL,NULL,'Asia/Taipei','罗汉禅堂','','','','罗汉禅堂','');

/*Table structure for table `puti_sites_branchs` */

DROP TABLE IF EXISTS `puti_sites_branchs`;

CREATE TABLE `puti_sites_branchs` (
  `site_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`site_id`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `puti_sites_branchs` */

/*Table structure for table `puti_volunteer` */

DROP TABLE IF EXISTS `puti_volunteer`;

CREATE TABLE `puti_volunteer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` int(11) DEFAULT NULL,
  `cname` varchar(255) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `en_name` varchar(255) DEFAULT NULL,
  `dharma_name` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(1023) DEFAULT NULL,
  `phone` varchar(31) DEFAULT NULL,
  `cell` varchar(31) DEFAULT NULL,
  `city` varchar(127) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_volunteer` */

/*Table structure for table `puti_volunteer_hours` */

DROP TABLE IF EXISTS `puti_volunteer_hours`;

CREATE TABLE `puti_volunteer_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT '0',
  `purpose` varchar(255) DEFAULT NULL,
  `work_date` bigint(20) DEFAULT NULL,
  `work_hour` decimal(11,1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `puti_volunteer_hours` */

/*Table structure for table `website_admins` */

DROP TABLE IF EXISTS `website_admins`;

CREATE TABLE `website_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `dharma_name` varchar(255) DEFAULT NULL,
  `city` varchar(127) DEFAULT NULL,
  `phone` varchar(31) DEFAULT NULL,
  `cell` varchar(31) DEFAULT NULL,
  `user_name` varchar(15) DEFAULT NULL,
  `email` varchar(1023) DEFAULT NULL,
  `password` varchar(15) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `group_id` int(11) DEFAULT '0',
  `department` varchar(1023) DEFAULT NULL,
  `site` int(11) DEFAULT '0',
  `branch` int(11) DEFAULT '0',
  `sites` varchar(2048) DEFAULT NULL,
  `branchs` varchar(2048) DEFAULT NULL,
  `lang` varchar(11) DEFAULT NULL,
  `login_count` tinyint(1) DEFAULT '0',
  `deleted` tinyint(4) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  `last_login` bigint(20) DEFAULT '0',
  `hits` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;

/*Data for the table `website_admins` */

insert  into `website_admins`(`id`,`first_name`,`last_name`,`dharma_name`,`city`,`phone`,`cell`,`user_name`,`email`,`password`,`status`,`group_id`,`department`,`site`,`branch`,`sites`,`branchs`,`lang`,`login_count`,`deleted`,`created_time`,`last_updated`,`last_login`,`hits`) values (2,'System','Admin','','Richmond','','','admin','admin@van.putiyea.com','9182736455',0,17,'7',1,2,'1','','tw',0,0,1367125073,1419130295,1418971684,151);

/*Table structure for table `website_basic` */

DROP TABLE IF EXISTS `website_basic`;

CREATE TABLE `website_basic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_key` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_cn` varchar(255) DEFAULT NULL,
  `desc_en` varchar(255) DEFAULT NULL,
  `desc_cn` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `sn` int(11) DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `website_basic` */

insert  into `website_basic`(`id`,`lang_key`,`title_en`,`title_cn`,`desc_en`,`desc_cn`,`table_name`,`sn`,`deleted`) values (1,'','Professional','专业特长','用于义工专业特长',NULL,'volunteer.professional',99,1),(2,'','dfads','Fasdf','Asdfasdf',NULL,'asdfasd',0,1),(3,'','dfaf','Sdfas','Dfasdfa',NULL,'sdfsadf',99,1),(4,'','dfasd','Fasdfasdf','Asdfaf',NULL,'asdfasd',122,1),(5,'','Professional','专业特长','Volunteer Professional','义工专业特长','volunteer.professional',99,0),(6,'','sdfas','Dsfasdf','Dasf',NULL,'asdfas',0,1),(7,'','Heath Status','健康状况','Health Statue','健康状况','health.status',88,0),(8,'','Degree','学历','Education Degree','教育背景','member.education',0,0),(9,'','Religion','宗教信仰','Religion','宗教信仰','member.religion',0,0),(10,'','Language','语言','Language','语言','member.language',0,0),(11,'','Volunteer Type','义工类型','Volunteer Type','义工类型','volunteer.type',0,0);

/*Table structure for table `website_basic_table` */

DROP TABLE IF EXISTS `website_basic_table`;

CREATE TABLE `website_basic_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filter` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_cn` varchar(255) DEFAULT NULL,
  `desc_en` varchar(1023) DEFAULT NULL,
  `desc_cn` varchar(1023) DEFAULT NULL,
  `lang_key` varchar(255) DEFAULT NULL,
  `number1` int(11) DEFAULT '0',
  `string1` varchar(255) DEFAULT NULL,
  `float1` decimal(11,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '1',
  `deleted` tinyint(1) DEFAULT '0',
  `created_time` bigint(20) DEFAULT '0',
  `last_updated` bigint(20) DEFAULT '0',
  `sn` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

/*Data for the table `website_basic_table` */

insert  into `website_basic_table`(`id`,`filter`,`title_en`,`title_cn`,`desc_en`,`desc_cn`,`lang_key`,`number1`,`string1`,`float1`,`status`,`deleted`,`created_time`,`last_updated`,`sn`) values (1,'volunteer.professional','Computer','电脑','Computer Programming','电脑编程， 硬件',NULL,0,NULL,'0.00',1,1,1422303651,0,99),(2,'volunteer.professional','Advertising','广告设计','Advertising, Editor','广告设计， 策划',NULL,0,NULL,'0.00',1,1,1422303878,1422489176,66),(3,'volunteer.professional','Computer','电脑','Computer Software, hardware','电脑编程， 硬件，软件',NULL,0,NULL,'0.00',1,1,1422303884,1422309798,88),(4,'health.status','Pneumonia','肺结核','Pneumonia','肺结核',NULL,0,NULL,'0.00',1,1,1422304029,1422489237,77),(5,'health.status','Skin Virus','皮肤病','Skin Virus','皮肤传染病',NULL,0,NULL,'0.00',1,1,1422304712,0,88),(6,'member.education','PHD','博士','Posted Degree','博士生',NULL,0,NULL,'0.00',1,0,1422478093,1422667336,55),(7,'member.education','Master','研究生','Master Degree','研究生畢業',NULL,0,NULL,'0.00',1,0,1422478132,1422667320,66),(8,'member.education','Bachelor','本科生','Bachelor','本科生',NULL,0,NULL,'0.00',1,0,1422478173,1422667314,77),(9,'member.religion','Bodhi','佛教','Bidhi Meditation','菩提禪修',NULL,0,NULL,'0.00',1,0,1422478903,1422670386,99),(10,'member.education','College','大專','College','大專生',NULL,0,NULL,'0.00',1,0,1422495250,1422667302,88),(11,'member.education','Middle School','中学','Middle School','中学',NULL,0,NULL,'0.00',1,0,1422495377,1422736513,99),(12,'member.language','English','英文','English','英文',NULL,0,NULL,'0.00',1,0,1422668169,1422668250,77),(13,'member.language','Mandarin','國語','Mandarin','國語',NULL,0,NULL,'0.00',1,0,1422668203,1422668243,99),(14,'member.language','Cantonese','粵語','Cantonese','粵語',NULL,0,NULL,'0.00',1,0,1422668227,1422668247,88),(15,'member.language','Others, please specify','其他','Other Language','其他語言',NULL,0,NULL,'0.00',1,0,1422668277,1423601242,0),(16,'member.language','French','法语','French','法语',NULL,0,NULL,'0.00',1,0,1422669089,1423601273,66),(17,'member.religion','Christian','基督教','Christianism','基督教',NULL,0,NULL,'0.00',1,0,1422670443,0,77),(18,'member.religion','Islam','回教','Islam','回教',NULL,0,NULL,'0.00',1,0,1422670558,0,0),(19,'member.education','Other','其他','Other','其他',NULL,0,NULL,'0.00',1,0,1422736536,0,44),(20,'volunteer.professional','x','烹饪','','',NULL,0,NULL,'0.00',1,0,1422744120,1423082234,24),(21,'volunteer.professional','x','财务','','',NULL,0,NULL,'0.00',1,0,1423082068,1423082222,23),(22,'volunteer.professional','g','水电维修','','',NULL,0,NULL,'0.00',1,0,1423082174,0,22),(23,'volunteer.professional','r','法律咨询','','',NULL,0,NULL,'0.00',1,0,1423082285,0,21),(24,'volunteer.professional','r','行政管理','','',NULL,0,NULL,'0.00',1,0,1423082323,0,20),(25,'volunteer.professional','t','建筑/设计','','',NULL,0,NULL,'0.00',1,0,1423082416,0,19),(26,'volunteer.professional','g','文物鉴定','','',NULL,0,NULL,'0.00',1,0,1423082455,0,18),(27,'volunteer.professional','w','歌唱','','',NULL,0,NULL,'0.00',1,0,1423082537,0,17),(28,'volunteer.professional','G','IT软体','','',NULL,0,NULL,'0.00',1,0,1423082624,0,16),(29,'volunteer.professional','e','教学','','',NULL,0,NULL,'0.00',1,0,1423082660,0,15),(30,'volunteer.professional','f','绘画','','',NULL,0,NULL,'0.00',1,0,1423082685,0,14),(31,'volunteer.professional','f','舞蹈','','',NULL,0,NULL,'0.00',1,0,1423082716,0,13),(32,'volunteer.professional','f','IT网络','','',NULL,0,NULL,'0.00',1,0,1423082771,0,12),(33,'volunteer.professional','r','翻译','','',NULL,0,NULL,'0.00',1,0,1423082806,0,11),(34,'volunteer.professional','e','幼儿教育','','',NULL,0,NULL,'0.00',1,0,1423082839,0,10),(35,'volunteer.professional','d','安保','','',NULL,0,NULL,'0.00',1,0,1423082863,0,9),(36,'volunteer.professional','u','摄影/录影','','',NULL,0,NULL,'0.00',1,0,1423082897,0,8),(37,'volunteer.professional','e','写作采访','','',NULL,0,NULL,'0.00',1,0,1423082930,0,7),(38,'volunteer.professional','y','公关','','',NULL,0,NULL,'0.00',1,0,1423082952,0,6),(39,'volunteer.professional','g','平面设计','','',NULL,0,NULL,'0.00',1,0,1423082977,0,5),(40,'volunteer.professional','r','音响/视频','','',NULL,0,NULL,'0.00',1,0,1423083006,0,4),(41,'volunteer.professional','e','医护','','',NULL,0,NULL,'0.00',1,0,1423083026,0,3),(42,'volunteer.professional','r','采购','','',NULL,0,NULL,'0.00',1,0,1423083052,0,2),(43,'volunteer.professional','S','调理','','',NULL,0,NULL,'0.00',1,0,1423083077,0,1),(44,'health.status','Sever heart disease','嚴重心髒病','','',NULL,0,NULL,'0.00',1,0,1423083232,1423600906,9),(45,'health.status','Mental disease(Pls. Clarify)','精神類疾病（請說明）','','',NULL,0,NULL,'0.00',1,0,1423083377,1423600943,8),(46,'health.status','Hepatitis B','乙肝','','',NULL,0,NULL,'0.00',1,0,1423083446,1423600952,7),(47,'health.status','Tuberculosis(TB)','結核病','','',NULL,0,NULL,'0.00',1,0,1423083480,1423600963,6),(48,'health.status','HIV','艾滋病','','',NULL,0,NULL,'0.00',1,0,1423083503,1423600970,5),(49,'health.status','Epilepsy','癲癇','','',NULL,0,NULL,'0.00',1,0,1423083529,1423600979,4),(50,'health.status','Cancer','癌症','','',NULL,0,NULL,'0.00',1,0,1423083555,1423600986,3),(51,'health.status','Contagious skin disease','皮膚類傳染病','','',NULL,0,NULL,'0.00',1,0,1423083588,1423601163,2),(52,'health.status','Details and other diseases to specify','其他需說明的疾病','','',NULL,0,NULL,'0.00',1,0,1423083621,1423601031,1),(53,'volunteer.type','全职义工','全职义工','全职义工','全职义工',NULL,0,NULL,'0.00',1,0,1423962508,0,99),(54,'volunteer.type','长期义工','长期义工','长期义工','长期义工',NULL,0,NULL,'0.00',1,0,1423962519,0,88),(55,'volunteer.type','中期义工','中期义工','中期义工','中期义工',NULL,0,NULL,'0.00',1,0,1423962533,0,77),(56,'volunteer.type','短期义工','短期义工','短期义工','短期义工',NULL,0,NULL,'0.00',1,0,1423962545,0,66);

/*Table structure for table `website_document` */

DROP TABLE IF EXISTS `website_document`;

CREATE TABLE `website_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_id` int(11) DEFAULT NULL,
  `filter` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(1023) DEFAULT NULL,
  `file_url` varchar(1023) DEFAULT NULL,
  `file_content` longblob,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `website_document` */

/*Table structure for table `website_groups` */

DROP TABLE IF EXISTS `website_groups`;

CREATE TABLE `website_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(1023) DEFAULT NULL,
  `group_right` text,
  `status` tinyint(4) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `website_groups` */

insert  into `website_groups`(`id`,`name`,`description`,`group_right`,`status`,`level`,`deleted`,`created_time`,`last_updated`) values (1,'禅堂账号管理员','拥有所有权限','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"71\":{\"view\":1},\"72\":{\"view\":1,\"save\":1,\"delete\":1},\"73\":{\"view\":1,\"save\":1,\"print\":1,\"detail\":1},\"74\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":0},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":0,\"email\":0},\"12\":{\"view\":1,\"print\":1},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":1,\"print\":1}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":1},\"32\":{\"view\":1,\"save\":1},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":1},\"70\":{\"view\":1},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":1}}},\"900\":{\"view\":1}}}',1,8,0,1365566823,1416698394),(2,'ä¸€èˆ¬ç®¡ç†å‘˜','ä¸èƒ½æ›´æ”¹å…¶ä»–ç®¡ç†å‘˜çš„å¸å·ä¿¡æ¯ï¼Œå…¶ä»–æƒé™éƒ½æœ‰','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":1,\"save\":0},\"30\":{\"view\":1,\"save\":0,\"delete\":0},\"40\":{\"view\":1,\"save\":0},\"45\":{\"view\":1},\"50\":{\"view\":1,\"save\":0},\"60\":{\"view\":1,\"save\":0,\"delete\":0},\"70\":{\"view\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":0},\"90\":{\"view\":1,\"save\":0,\"delete\":0,\"email\":0},\"100\":{\"view\":1,\"save\":0,\"delete\":0},\"110\":{\"view\":1,\"save\":0},\"120\":{\"view\":1,\"save\":0},\"130\":{\"view\":1,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":1,\"save\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":0},\"30\":{\"view\":1,\"save\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":0},\"30\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":0,\"email\":0},\"20\":{\"view\":1,\"print\":0},\"25\":{\"view\":1},\"30\":{\"view\":1,\"save\":0},\"40\":{\"view\":1,\"print\":0},\"50\":{\"view\":1,\"print\":0},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":0},\"70\":{\"view\":1,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":0},\"90\":{\"view\":1,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":0},\"20\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0}}},\"900\":{\"view\":1}}}',1,NULL,1,1365566901,1383803115),(3,'管理员-教务部门','只对教学有操作权限','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":0},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":0},\"70\":{\"view\":1},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"12\":{\"view\":1,\"print\":1},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":1,\"print\":1}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":1},\"70\":{\"view\":0},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,4,0,1365566994,1418283397),(4,'ç®¡ç†å‘˜-å­¦å‘˜ä¿¡æ¯','åªå¯¹å­¦å‘˜ä¿¡æ¯æœ‰æ“ä½œæƒé™','{\"right\":{\"0\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"40\":{\"view\":0,\"save\":0},\"45\":{\"view\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"30\":{\"view\":0,\"save\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0}}},\"900\":{\"view\":1}}}',1,NULL,1,1365567023,1383803128),(5,'æŸ¥è¯¢ç»„','åªèƒ½æŸ¥çœ‹ï¼Œä¸èƒ½ä¿®æ”¹.','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":1,\"save\":0},\"30\":{\"view\":1,\"save\":0,\"delete\":0},\"40\":{\"view\":1,\"save\":0},\"45\":{\"view\":1},\"50\":{\"view\":1,\"save\":0},\"60\":{\"view\":1,\"save\":0,\"delete\":0},\"70\":{\"view\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":0},\"90\":{\"view\":1,\"save\":0,\"delete\":0,\"email\":0},\"100\":{\"view\":1,\"save\":0,\"delete\":0},\"110\":{\"view\":1,\"save\":0},\"120\":{\"view\":1,\"save\":0},\"130\":{\"view\":1,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":1,\"save\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":0},\"30\":{\"view\":1,\"save\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":0},\"30\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":0,\"email\":0},\"20\":{\"view\":1,\"print\":0},\"25\":{\"view\":1},\"30\":{\"view\":1,\"save\":0},\"40\":{\"view\":1,\"print\":0},\"50\":{\"view\":1,\"print\":0},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":0},\"70\":{\"view\":1,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":0},\"90\":{\"view\":1,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0}}},\"900\":{\"view\":1}}}',1,NULL,1,1365723645,1383803135),(6,'é¦™ç§¯ç»„','ç‚¹é‡‘ä¹æœ¯åº·æ‹‰å¾·','{\"right\":{\"0\":{\"view\":0,\"right\":[{\"view\":0,\"save\":0},{\"view\":0,\"save\":0,\"delete\":0},{\"view\":0},{\"view\":0,\"save\":0},{\"view\":0,\"save\":0,\"delete\":0},{\"view\":0}]},\"1\":{\"view\":0,\"right\":[{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},{\"view\":0,\"save\":0},{\"view\":0,\"save\":0}]},\"2\":{\"view\":0,\"right\":[{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},{\"view\":0,\"save\":0},{\"view\":0,\"save\":0}]},\"8\":{\"view\":0,\"right\":[{\"view\":0,\"save\":0},{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},{\"view\":0,\"add\":0,\"save\":0,\"delete\":0}]},\"9\":{\"view\":0}}}',1,NULL,1,1369526582,0),(7,'义工组-人力部门','','{\"right\":{\"0\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"113\":{\"view\":0,\"save\":0,\"print\":0},\"116\":{\"view\":0,\"save\":0,\"print\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"50\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"save\":0},\"40\":{\"view\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"60\":{\"view\":0},\"70\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":1,\"print\":1}}},\"800\":{\"view\":0,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"33\":{\"view\":0,\"save\":0,\"delete\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,5,0,1369690564,1423604121),(8,'ä¹‰å·¥ç»„-è‹±æ–‡ç»„Peggy','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1,\"save\":1},\"1\":{\"view\":1,\"save\":1,\"delete\":1},\"2\":{\"view\":1},\"3\":{\"view\":1,\"save\":1},\"4\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"5\":{\"view\":1,\"save\":1,\"delete\":1},\"6\":{\"view\":1,\"save\":1},\"7\":{\"view\":1,\"save\":1},\"8\":{\"view\":1,\"save\":1,\"delete\":1},\"9\":{\"view\":1,\"save\":1},\"10\":{\"view\":1,\"save\":1},\"15\":{\"view\":1,\"print\":1},\"16\":{\"view\":1,\"print\":1}}},\"1\":{\"view\":1,\"right\":[{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},{\"view\":1,\"save\":1},{\"view\":1,\"save\":1}]},\"2\":{\"view\":1,\"right\":{\"0\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"1\":{\"view\":0,\"save\":0},\"2\":{\"view\":0,\"save\":0,\"delete\":0},\"3\":{\"view\":0,\"save\":0},\"4\":{\"view\":0,\"print\":0,\"email\":0},\"5\":{\"view\":0,\"print\":0},\"8\":{\"view\":1,\"save\":1},\"9\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"3\":{\"view\":1,\"right\":[{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},{\"view\":0,\"save\":0},{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},{\"view\":1,\"print\":1},{\"view\":1,\"print\":1},{\"view\":1,\"print\":1},{\"view\":1,\"print\":1},{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},{\"view\":1,\"print\":1},{\"view\":1,\"print\":1},{\"view\":1,\"print\":1}]},\"4\":{\"view\":1,\"right\":[{\"view\":1,\"delete\":1,\"email\":1}]},\"8\":{\"view\":1,\"right\":[{\"view\":1,\"save\":1},{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},{\"view\":0,\"add\":0,\"save\":0,\"delete\":0}]},\"9\":{\"view\":1}}}',1,NULL,1,1370129456,1381376557),(9,'义工组-其他部门','只负责本部门的义工工时录入和调整','{\"right\":{\"0\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1370656014,1403924742),(10,'è‹±æ–‡æ•™åŠ¡','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1},\"40\":{\"view\":1,\"save\":1},\"45\":{\"view\":1},\"50\":{\"view\":1,\"save\":1},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"120\":{\"view\":1,\"save\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"140\":{\"view\":1,\"save\":1}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":1},\"40\":{\"view\":1,\"delete\":1,\"email\":1}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"30\":{\"view\":1,\"save\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0}}},\"900\":{\"view\":1}}}',1,NULL,1,1373468044,1384047919),(11,'收款组','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1387648854,1402353155),(12,'操作员-教务部门','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":0,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":0,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":0},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1388548014,1416699886),(13,'文宣组','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":1},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":1},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":1},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,1,1388622266,1398362399),(14,'Operator-Administration','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":0,\"email\":0},\"12\":{\"view\":1,\"print\":1},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":1},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":1},\"70\":{\"view\":0},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,4,0,1389117715,1416698546),(15,'前台登记-前台','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1389118728,1402282231),(16,'内部管理-Dharma','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":1},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":1},\"155\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"160\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":0,\"email\":1},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":1,\"delete\":1,\"email\":1}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,9,1,1390335159,1402280681),(17,'系统管理员','系统管理拥有更改权限','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":1,\"save\":1},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"71\":{\"view\":1},\"72\":{\"view\":1,\"save\":1,\"delete\":1},\"73\":{\"view\":1,\"save\":1,\"print\":1,\"detail\":1},\"74\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":1},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":1},\"155\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"160\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":1},\"12\":{\"view\":1,\"print\":1},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":1},\"38\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},\"40\":{\"view\":1,\"delete\":1,\"email\":1}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":1},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"50\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"30\":{\"view\":1,\"save\":1},\"40\":{\"view\":1},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"60\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":1,\"print\":1}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"30\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"31\":{\"view\":1},\"32\":{\"view\":1,\"save\":1},\"33\":{\"view\":1,\"save\":1,\"delete\":1},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},\"50\":{\"view\":1},\"60\":{\"view\":1},\"70\":{\"view\":1},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":1}}},\"900\":{\"view\":1}}}',1,9,0,1394155215,1423957979),(18,'主管-教务部门','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"71\":{\"view\":1},\"72\":{\"view\":1,\"save\":0,\"delete\":0},\"73\":{\"view\":1,\"save\":1,\"print\":1,\"detail\":1},\"74\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":1},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":1},\"155\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":0},\"160\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":0,\"email\":0},\"12\":{\"view\":1,\"print\":1},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":1},\"70\":{\"view\":0},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,6,0,1396401963,1416698520),(19,'教务报表-教学部门','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":1},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":1},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"113\":{\"view\":0,\"save\":0,\"print\":0},\"116\":{\"view\":0,\"save\":0,\"print\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1398202081,1416699922),(20,'机密临时帐号组','只用于处理机密拜师时使用。\n\n此组唯一能做的事情是， 可以处理拜师法名。','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":1},\"155\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":0},\"160\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0,\"email\":1},\"35\":{\"view\":0},\"40\":{\"view\":1,\"save\":0,\"delete\":0,\"print\":0,\"email\":1},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":1,\"print\":0,\"email\":1},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,9,0,1402280888,1410072316),(21,'Supervisor-Administrator','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"71\":{\"view\":1},\"72\":{\"view\":1,\"save\":0,\"delete\":0},\"73\":{\"view\":1,\"save\":1,\"print\":1,\"detail\":1},\"74\":{\"view\":1,\"print\":1},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":1,\"save\":1,\"delete\":1,\"email\":0},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"50\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"save\":0},\"40\":{\"view\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"60\":{\"view\":0},\"70\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":1},\"60\":{\"view\":1,\"print\":1},\"65\":{\"view\":1,\"print\":1},\"70\":{\"view\":1,\"print\":1},\"75\":{\"view\":0},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":1,\"print\":1}}},\"800\":{\"view\":0,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"33\":{\"view\":0,\"save\":0,\"delete\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":1},\"70\":{\"view\":0},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,6,0,1402776627,1423167552),(22,'主管-八卦教学','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"add\":1,\"save\":1,\"delete\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1,\"delete\":1},\"45\":{\"view\":1},\"48\":{\"view\":1,\"save\":1},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":1,\"save\":1,\"delete\":1},\"70\":{\"view\":1},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1,\"email\":0},\"100\":{\"view\":1,\"save\":1,\"delete\":1},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":1,\"save\":1,\"print\":1},\"116\":{\"view\":1,\"save\":1,\"print\":1},\"120\":{\"view\":1,\"save\":1,\"print\":1},\"125\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"127\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":1,\"save\":1,\"print\":1},\"145\":{\"view\":1,\"save\":1,\"detail\":1,\"print\":1},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":1,\"print\":1},\"13\":{\"view\":1,\"save\":1,\"delete\":1,\"detail\":1,\"print\":1},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":1,\"save\":1},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":1},\"20\":{\"view\":1,\"save\":1},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":0},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"print\":1,\"email\":0},\"15\":{\"view\":1,\"print\":1},\"20\":{\"view\":1,\"print\":1},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":1,\"print\":1},\"50\":{\"view\":1,\"print\":1},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":1,\"print\":1},\"90\":{\"view\":1,\"print\":1}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,6,0,1404435299,1416698492),(23,'系统安装','系统安装组。\n只能用于系统的初始化安装。','{\"right\":{\"0\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":1},\"70\":{\"view\":1},\"80\":{\"view\":1},\"82\":{\"view\":1},\"83\":{\"view\":0},\"95\":{\"view\":1}}},\"900\":{\"view\":1}}}',1,1,0,1411410953,1411411125),(24,'test','test','{\"right\":{\"0\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":0}}}',1,1,1,1413344487,NULL),(25,'邮件群发','邮件群发，可以管理自己的邮件清单， 以及群发。','{\"right\":{\"0\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":0,\"save\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":1},\"38\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1},\"40\":{\"view\":1,\"delete\":1,\"email\":1}}},\"10\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"50\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0}}},\"700\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":0},\"27\":{\"view\":0,\"print\":0},\"30\":{\"view\":0,\"print\":0},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":0,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1414278522,1414278546),(26,'内场主管','','{\"right\":{\"0\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0},\"45\":{\"view\":0},\"48\":{\"view\":0,\"save\":0},\"50\":{\"view\":0,\"save\":0},\"60\":{\"view\":0,\"save\":0,\"delete\":0},\"70\":{\"view\":0},\"71\":{\"view\":0},\"72\":{\"view\":0,\"save\":0,\"delete\":0},\"73\":{\"view\":0,\"save\":0,\"print\":0,\"detail\":0},\"74\":{\"view\":0,\"print\":0},\"75\":{\"view\":1},\"80\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"85\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"90\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"100\":{\"view\":0,\"save\":0,\"delete\":0},\"110\":{\"view\":1,\"save\":1},\"113\":{\"view\":0,\"save\":0,\"print\":0},\"116\":{\"view\":0,\"save\":0,\"print\":0},\"120\":{\"view\":0,\"save\":0,\"print\":0},\"125\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"127\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"130\":{\"view\":0,\"save\":0,\"delete\":0,\"email\":0},\"140\":{\"view\":0,\"save\":0,\"print\":0},\"145\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0},\"150\":{\"view\":0},\"155\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"160\":{\"view\":0,\"save\":0,\"detail\":0,\"print\":0}}},\"5\":{\"view\":0,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0,\"email\":0},\"12\":{\"view\":0,\"print\":0},\"13\":{\"view\":0,\"save\":0,\"delete\":0,\"detail\":0,\"print\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"38\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"40\":{\"view\":0,\"delete\":0,\"email\":0}}},\"10\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"15\":{\"view\":0},\"20\":{\"view\":0,\"save\":0},\"30\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0,\"email\":0},\"35\":{\"view\":1},\"40\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1,\"email\":1},\"50\":{\"view\":1,\"save\":1,\"delete\":1,\"print\":1}}},\"700\":{\"view\":1,\"right\":{\"0\":{\"view\":0},\"10\":{\"view\":0,\"print\":0,\"email\":0},\"15\":{\"view\":0,\"print\":0},\"20\":{\"view\":0,\"print\":0},\"25\":{\"view\":1},\"27\":{\"view\":1,\"print\":1},\"30\":{\"view\":1,\"print\":1},\"40\":{\"view\":0,\"print\":0},\"50\":{\"view\":0,\"print\":0},\"55\":{\"view\":0},\"60\":{\"view\":0,\"print\":0},\"65\":{\"view\":0,\"print\":0},\"70\":{\"view\":0,\"print\":0},\"75\":{\"view\":0},\"80\":{\"view\":0,\"print\":0},\"90\":{\"view\":0,\"print\":0}}},\"800\":{\"view\":1,\"right\":{\"0\":{\"view\":1},\"10\":{\"view\":1,\"save\":1},\"20\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"30\":{\"view\":0,\"add\":0,\"save\":0,\"delete\":0},\"31\":{\"view\":0},\"32\":{\"view\":0,\"save\":0},\"35\":{\"view\":0},\"40\":{\"view\":0,\"save\":0,\"delete\":0,\"print\":0},\"50\":{\"view\":0},\"60\":{\"view\":0},\"70\":{\"view\":0},\"80\":{\"view\":0},\"82\":{\"view\":0},\"83\":{\"view\":0},\"95\":{\"view\":0}}},\"900\":{\"view\":1}}}',1,1,0,1414304477,1416699844);

/*Table structure for table `website_images` */

DROP TABLE IF EXISTS `website_images`;

CREATE TABLE `website_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filter` varchar(255) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(1023) DEFAULT NULL,
  `file_url` varchar(1023) DEFAULT NULL,
  `small` longblob,
  `medium` longblob,
  `large` longblob,
  `status` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `website_images` */

/*Table structure for table `website_language_word` */

DROP TABLE IF EXISTS `website_language_word`;

CREATE TABLE `website_language_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project` varchar(127) DEFAULT NULL,
  `filter` varchar(127) DEFAULT NULL,
  `keyword` varchar(127) DEFAULT NULL,
  `en` text,
  `cn` text,
  `tw` text,
  `deleted` tinyint(4) DEFAULT NULL,
  `created_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=856 DEFAULT CHARSET=utf8;

/*Data for the table `website_language_word` */

insert  into `website_language_word`(`id`,`project`,`filter`,`keyword`,`en`,`cn`,`tw`,`deleted`,`created_time`) values (1,'van.puti.ca','common','menu_class','Class','课程定义','課程定義',0,1388966367),(2,'van.puti.ca','common','menu_agreement','Agreement','法律条款','法律條款',0,1388966375),(3,'van.puti.ca','common','menu_event','Event','教学管理','教學管理',0,1388966380),(4,'van.puti.ca','common','menu_new_class','New Class','课程新增','課程新增',0,1389119535),(5,'van.puti.ca','common','menu_edit_class','Edit Class','课程修改','課程修改',0,1389119536),(6,'van.puti.ca','common','menu_chinese','简体中文','简体中文','簡體中文',0,1389119537),(7,'van.puti.ca','common','menu_volunteer','Volunteer','义工管理','義工管理',0,1389119538),(8,'van.puti.ca','common','menu_add_to_cal','Add to Calender','开班日历','開班日曆',0,1389119539),(9,'van.puti.ca','common','menu_calendar','Calendar','课程日历','課程日曆',0,1389119540),(10,'van.puti.ca','common','menu_onetime','New one-time Event','添加偶然事件','添加偶然事情',0,1389119540),(13,'van.puti.ca','common','menu_course','Course','班级上课','班級上課',0,1389119541),(11,'van.puti.ca','common','menu_cal_evtlist','Calendar Event List','课程日历','課程日曆',0,1389119542),(12,'van.puti.ca','common','menu_cal_evtall','ALL Event List','课程列表','課程列表',0,1389119543),(14,'van.puti.ca','common','menu_enroll','Student Enroll','学生报名','學生報名',0,1389119544),(15,'van.puti.ca','common','menu_group','Student Group','学生分组','學生分組',0,1389119545),(16,'van.puti.ca','common','menu_checkin','Attend CheckIn','考勤签到','考勤簽到',0,1389119601),(17,'van.puti.ca','common','menu_adjust','Graduate Approval','毕业核准','畢業核准',0,1391564971),(18,'van.puti.ca','common','menu_attend_cal','Attend Calculate','计算出勤','計算出勤',0,1400380878),(19,'van.puti.ca','common','menu_creturn','ID Card Return','ID卡回收','ID卡回收',0,1389119604),(20,'van.puti.ca','common','menu_payment','Payment','学费收款','學費收款',0,1389119605),(21,'van.puti.ca','common','menu_member','Member','学员管理','學員管理',0,1389119606),(22,'van.puti.ca','common','menu_all_member','All Members','所有学员','所有學員',0,1389119607),(23,'van.puti.ca','common','menu_member_list','Member List','学员清单','學員清單',0,1389119608),(24,'van.puti.ca','common','menu_register','Registration','登记注册','登記註冊',0,1389119609),(25,'van.puti.ca','common','menu_freg','Full Registration','学员完整信息登记','學員完整信息',0,1389119609),(26,'van.puti.ca','common','menu_qreg','Quick Registration','学员信息快速登记','學員信息快速登記',0,1389119896),(27,'van.puti.ca','common','menu_email_sent','Email Notification','邮件通知','郵件通知',0,1389119897),(28,'van.puti.ca','common','menu_email','Email','邮件功能','郵件功能',0,1389119897),(29,'van.puti.ca','common','menu_dep','Department','部门','部門',0,1389119898),(30,'van.puti.ca','common','menu_dep_def','Department Define','部门定义','部門定義',0,1389119898),(31,'van.puti.ca','common','menu_vol','Volunteer','义工','義工',0,1389119899),(32,'van.puti.ca','common','menu_vol_add','New Volunteer','新增义工','新增義工',0,1389119900),(33,'van.puti.ca','common','menu_vol_all','ALL Volunteer','所有义工','所有義工',0,1389119901),(34,'van.puti.ca','common','menu_vol_hours','Work Hours','工时','工時',0,1389119902),(35,'van.puti.ca','common','menu_vol_entry','Hours Entry','工时录入','工時錄入',0,1389119902),(36,'van.puti.ca','common','menu_vol_adjust','Hours Adjust','调整校对','調整校對',0,1389119903),(37,'van.puti.ca','common','menu_report','Report','报表功能','報表功能',0,1389119893),(38,'van.puti.ca','common','menu_rclass','Class','课程统计','課程統計',0,1389119892),(39,'van.puti.ca','common','menu_crep','Class Report','班级学员报告','班級學員',0,1389119892),(40,'van.puti.ca','common','menu_csum','Class Summary','班级历史汇总','班級歷史匯總',0,1389119891),(41,'van.puti.ca','common','menu_rcourse','Course','上课统计','上課統計',0,1389119887),(42,'van.puti.ca','common','menu_rattend','Attendance Report','上课考勤统计','上課考勤統計',0,1389119887),(43,'van.puti.ca','common','menu_cccrr','Course Report','上课情况报告','上課情況報告',0,1389119886),(44,'van.puti.ca','common','menu_cccss','Course Summary','历史上课汇总','歷史上課匯總',0,1389119886),(45,'van.puti.ca','common','menu_vol_rep','Volunteer','义工报表','義工報表',0,1389119885),(46,'van.puti.ca','common','menu_1111','Dep.-Vol.-Detail','部门 - 义工 - 明细','部門－義工－明細',0,1389119884),(47,'van.puti.ca','common','menu_2222','Vol.-Dep.-Detail','义工 - 部门 - 明细','義工－部門－明細',0,1389119884),(48,'van.puti.ca','common','menu_3333','Annual Report','义工年度报表','義工年度報表',0,1389119883),(49,'van.puti.ca','common','menu_4444','Dep.-( Head|Time)-Hours','部门 - ( 人数 | 次数 ) - 时数','部門－（人數|次數)-時數',0,1389119881),(50,'van.puti.ca','common','menu_5555','Vol.-(Dep.|Time)-Hours','义工 - ( 部门 | 次数 ) - 时数','義工－（部門|次數）－時數',0,1389119880),(51,'van.puti.ca','common','menu_logout','Logout','退出','退出',0,1389119879),(52,'van.puti.ca','common','menu_admin','Administrator','帐号管理','帳號管理',0,1389120134),(53,'van.puti.ca','common','menu_myacc','My Account','我的帐号','我的帳號',0,1389120135),(54,'van.puti.ca','common','menu_acclist','Account List','所有帐号','所有帳號',0,1389120135),(55,'van.puti.ca','common','menu_right','Roles','操作权限','權限操作',0,1389120135),(56,'van.puti.ca','common','menu_lang','Language','网站语言','網站語言',0,1389120136),(57,'van.puti.ca','common','menu_acct','Account','帐号','帳號',0,1389120137),(58,'van.puti.ca','common','menu_other','Other','其他','其他',0,1389120138),(59,'van.puti.ca','common','menu_select','Select','选择语言','選擇語言',0,1389120139),(60,'van.puti.ca','common','project','Project','项目','項目',0,1389120140),(61,'van.puti.ca','common','personal information','Personal Information','个人信息','個人信息',0,1389120119),(62,'van.puti.ca','common','first name','First Name','名字','名字',0,1389120120),(63,'van.puti.ca','common','last name','Last Name','姓氏','姓氏',0,1389120120),(64,'van.puti.ca','common','dharma name','Dharma Name','法名','法名',0,1388712978),(65,'van.puti.ca','common','alias','Alias','别名','別名',0,1388860899),(66,'van.puti.ca','common','gender','Gender','性别','性別',0,1389120121),(67,'van.puti.ca','common','male','Male','男','男',0,1389120122),(68,'van.puti.ca','common','female','Female','女','女',0,1389120123),(69,'van.puti.ca','common','age range','Age Range','年龄段','年齡段',0,1389120123),(70,'van.puti.ca','common','years old','Years Old','岁','歲',0,1389120124),(71,'van.puti.ca','common','contact information','Contact Information','联系信息','聯係信息',0,1389120125),(72,'van.puti.ca','common','email','Email','电子邮件','電子郵件',0,1389120128),(73,'van.puti.ca','common','phone','Phone','电话','電話',0,1389120129),(74,'van.puti.ca','common','cell','Cell','手机','手機',0,1389120130),(75,'van.puti.ca','common','preferred method of contact','Preferred method of contact','首选联系方式','首選聯係方式',0,1389120130),(76,'van.puti.ca','common','by phone','By Phone','通过电话','通過電話',0,1389120131),(77,'van.puti.ca','common','by email','By Email','通过电邮','通過郵件',0,1389120109),(78,'van.puti.ca','common','address information','Address Information','地址信息','地址信息',0,1389120108),(79,'van.puti.ca','common','address','Address','地址','地址',0,1389120144),(80,'van.puti.ca','common','city','City','城市','城市',0,1389120144),(81,'van.puti.ca','common','state','State','省份','省份',0,1389120103),(82,'van.puti.ca','common','country','Country','国家','國家',0,1389120146),(83,'van.puti.ca','common','postal code','Postal','邮编','郵編',0,1422911217),(84,'van.puti.ca','common','emergency contact name and relationship','Emergency contact','紧急联系','緊急聯係',0,1388860928),(517,'van.puti.ca','common','newspaper','Newspaper','报纸','報紙',0,1388860917),(85,'van.puti.ca','common','contact name','Contact Name','联系人','聯係人',0,1389120101),(86,'van.puti.ca','common','contact phone','Contact Phone','联系电话','聯係電話',0,1389120100),(87,'van.puti.ca','common','relationship','Relationship','关系','關係',0,1389120099),(88,'van.puti.ca','common','how did you hear about us?','How did you hear about us','信息來源','信息來源',0,1388860902),(89,'van.puti.ca','common','are you currently receiving therapy of some kind?','Are you currently receiving any therapy','最近有没有接受治疗，物理治疗','最近有沒有接受治療',0,1388860900),(90,'van.puti.ca','common','if yes, please provide details regarding the nature of the therapy/treatment','If yes, please provide details regarding the nature of the therapy/treatment','如果有，请提供治疗，理疗的情况','如果有，請提供治療，理療的情況',0,1389120097),(91,'van.puti.ca','common','please write down any other medical concerns or history','Health Condition','请填写过往医疗情况以及医疗方面的关注','請填寫過往醫療情況以及醫療方面的關注',0,1397696986),(92,'van.puti.ca','common','individual and risk release','Individual and Risk Release','个人风险与免责声明','個人風險与免責聲明',0,1389120254),(93,'van.puti.ca','common','i dont agree','I don\'t agree','我不同意','我不同意',0,1389120253),(94,'van.puti.ca','common','i agree','I agree','我同意','我同意',0,1389120253),(95,'van.puti.ca','common','submit','Submit','提交','提交',0,1389120252),(96,'van.puti.ca','common','read before submit','Please read our \'Individual and Risk Release\' before submit.','在提交之前，请阅读我们的 \'个人风险与免责声明\'','在提交前，請閱讀我們的‘個人風險与免責聲明’',0,1389120251),(97,'van.puti.ca','common','you dont agree submit','You don\'t agree our \'Individual and Risk Release\'','你不同意 \'个人风险与免责声明\'','你不同意‘個人風險与免責聲明’',0,1389120250),(98,'van.puti.ca','common','agreement statement','I assume all risks of damage and injuries that may occur to me while participating in the Bodhi Meditation course and while on the premises at which the classes are held. I am aware that some courses may involve yoga, mindful stretching\nand mental exercises. I hereby release and discharge the Canada Bodhi Dharma Society and its agents and representatives\nfrom all claims or injuries resulting from my participation in the program.<br><br>\nI hereby grant permission to the Canada Bodhi Dharma Society, Including its successors and assignees to record and use my name, image and voice, for use in its promotional and informational productions. I further grant the Canada Bodhi Dharma Society permission to edit and modify these recordings in the making of productions as long as no third party\'s rights are infringeed by their use. Lastly, I release any and all legal claims against the Canada Bodhi Dharma Association for using, distributing or broadcasting any productions.<br /><br />\nI have read, understood, and I guarantee that all the information I have provide above is true and correct to the best of my knowledge. I agree to the above release.','<span style=\"font-size:16px\">我明白在菩提禅修过程中可能会出现身体受伤的情况. 我知道一些类似的运动如瑜伽, 冥想, 观想法等， 会存在由于自身的身体状况以及对教授方法的领悟差别而导致身体受伤.<br><br>\n我在此同意接受菩提禅修所教授的方法，并且本人会根据自己情况适当练习， 并且自己根据自己的身体状况选择性参与. 在练习过程中本人愿意承当身体受到伤害的风险.本人愿意合法的使用菩提禅修提供的场所， 器械以及宣传资料等物品.<br><br>\n我已阅读以上的条款, 并且我保证所提供的资料是真实合法的, 本人同意以上的条款.</span>\n<br>',NULL,1,1387008897),(108,'van.puti.ca','common','register form','Bodhi Meditation Student Registration Form','菩提禅修学员登记表','菩提禪修學員登記表',0,1389120408),(109,'van.puti.ca','common','new volunteer','Add Volunteer','新增义工','新增義工',0,1389120409),(99,'van.puti.ca','common','i have read','I have read above content','我已经阅读以上条款','我已經閱讀以上條款',0,1389120409),(100,'van.puti.ca','common','yes','Yes','是','是',0,1389120410),(101,'van.puti.ca','common','no','No','不是','不是',0,1389120411),(102,'van.puti.ca','common','friend','Friend','朋友','朋友',0,1389120411),(103,'van.puti.ca','common','tv','TV','电视','電視',0,1389120412),(104,'van.puti.ca','common','magazine','Magazine','杂志','雜誌',0,1389120412),(105,'van.puti.ca','common','poster','Poster','海报','海報',0,1389120413),(106,'van.puti.ca','common','internet','Internet','互联网','互聯網',0,1389120415),(107,'van.puti.ca','common','other','Other','其他','其他',0,1398364800),(110,'van.puti.ca','common','chinese name','Chinese Name','中文名','中文名',0,1389120417),(111,'van.puti.ca','common','pinyin','Pinyin','拼音名','拼音名',0,1389120420),(112,'van.puti.ca','common','english name','English Name','英文名','英文名',0,1389116227),(113,'van.puti.ca','common','button save','Save','保存','保存',0,1389120420),(114,'van.puti.ca','common','belong department','Department','所属部门','所屬部門',0,1389120421),(115,'van.puti.ca','common','volunteer records','Records','义工记录','義工記錄',0,1389120994),(116,'van.puti.ca','common','status','Status','状态','狀態',0,1389120422),(117,'van.puti.ca','common','inactive','Inactive','未激活','未激活',0,1389120423),(118,'van.puti.ca','common','active','Active','激活','激活',0,1389120424),(119,'van.puti.ca','common','total volunteer hours','Total Volunteer Hours','历史工时汇总','歷史工時匯總',0,1389120424),(120,'van.puti.ca','common','counts','Counts','服务次数','服務次數',0,1389120995),(121,'van.puti.ca','common','overwrite','Overwrite','覆盖','覆蓋',0,1389120995),(122,'van.puti.ca','common','create new','Create New','新建','新建',0,1389120996),(123,'van.puti.ca','common','search filter','Search Filter','查询条件','查詢條件',0,1389120997),(124,'van.puti.ca','common','name','Name','姓名','姓名',0,1388712915),(125,'van.puti.ca','common','search','Search','查询','查詢',0,1389120998),(126,'van.puti.ca','common','output excel','Output Excel','输出表格','輸出表格',0,1389120998),(127,'van.puti.ca','common','email pool','Email Pool','添加到邮件池','添加到郵件池',0,1389120999),(128,'van.puti.ca','common','sn','SN','序号','序號',0,1389120999),(129,'van.puti.ca','common','department','Department','部门','部門',0,1389121001),(130,'van.puti.ca','common','work for','Work for','服务内容','服務部門',0,1389121001),(131,'van.puti.ca','common','work date','Work Date','工作日期','工作時間',0,1389121002),(132,'van.puti.ca','common','hours','Hours','工时','工時',0,1389121003),(133,'van.puti.ca','common','del.','Del.','删除','刪除',0,1389121005),(134,'van.puti.ca','common','c.name','C.Name','中文名','中文名',0,1389121005),(135,'van.puti.ca','common','e.name','E.Name','英文名','英文名',0,1389121007),(136,'van.puti.ca','common','dharma','Dharma','法名','法名',0,1389121008),(137,'van.puti.ca','common','r.date','R.Date','申请日','申請日',0,1389121009),(138,'van.puti.ca','common','volunteer information','Volunteer Information','义工详细信息','義工詳細信息',0,1389121009),(139,'van.puti.ca','common','add email success','Add to Email Pool Successful','成功添加到电子邮件池','成功添加到電子郵件池',0,1389120985),(140,'van.puti.ca','common','volunteer search','Volunteer Search','义工搜索','義工搜索',0,1389120986),(141,'van.puti.ca','common','mark','Mark','标注','標註',0,1389120987),(142,'van.puti.ca','common','save all','Save All','保存所有','保存所有',0,1389120987),(143,'van.puti.ca','common','output sign','Output Signature','输出签到表','輸出簽到表',0,1389120988),(144,'van.puti.ca','common','remove marks','Remove All Marks','删除所有标注','刪除所有標註',0,1389120981),(145,'van.puti.ca','common','copy content','Copy Content','复制服务内容','複制服務內容',0,1389120980),(146,'van.puti.ca','common','copy work date','Copy work date','复制服务日期','複制服務日期',0,1389120990),(147,'van.puti.ca','common','selected department','Selected Department','所选部门','所選部門',0,1389120991),(148,'van.puti.ca','common','please input','Please input your name, email, phone or cell to below box','请输入你的名字，电子邮件，或者电话','請輸入你的名字，電子郵件， 或者電話',0,1389120977),(149,'van.puti.ca','common','name|email|phone|cell','Name|Email|Phone|Cell','姓名|电邮|电话|手提','姓名|電郵|電話|手提',0,1389120744),(150,'van.puti.ca','common','select department','Select Department','选择部门','選擇部門',0,1389120974),(151,'van.puti.ca','common','action','Action','操作','操作',0,1389120975),(152,'van.puti.ca','common','button save all','Save All','保存所有','保存所有',0,1389120975),(153,'van.puti.ca','common','button add','Add','添加','添加',0,1389120976),(154,'van.puti.ca','common','find','Find','查找','查找',0,1389120971),(155,'van.puti.ca','common','total','Total','汇总','匯總',0,1389120970),(156,'van.puti.ca','common','get history','Get History','获取历史数据','獲取歷史數據',0,1389120969),(157,'van.puti.ca','common','member enroll success','Member Enroll Success','学员报名成功','學員報名成功',0,1389120967),(158,'van.puti.ca','common','member enrollment','Member Enrollment','学员报名','學員報名',0,1389120967),(159,'van.puti.ca','common','select event','Select Event','选择课程','選擇課程',0,1389120966),(160,'van.puti.ca','common','date','Date','日期','日期',0,1389120965),(161,'van.puti.ca','common','register','Register','报名','報名',0,1389120962),(162,'van.puti.ca','common','send email','Send Email','发送电邮','發送電郵',0,1389120962),(163,'van.puti.ca','common','are you sure send email','Are you sure send email？','你确定发邮件吗？','你確定髮出郵件嗎？',0,1389120961),(164,'van.puti.ca','common','label print','Label Print','学生卡片打印','學生卡片打印',0,1389120960),(165,'van.puti.ca','common','group matrix','Group Matrix','小组列表','小組列表',0,1389120960),(166,'van.puti.ca','common','group list','Group List','按姓列表','按姓列表',0,1389120959),(167,'van.puti.ca','common','blank label','Blank Label','空白学生卡片','空白學生卡片',0,1389120953),(168,'van.puti.ca','common','ungroup label print','Ungroup Print','未分组卡片','未分組卡片',1,1389120952),(169,'van.puti.ca','common','print signature','Print Signature','打印签名表','打印簽名表',0,1389120951),(170,'van.puti.ca','common','view details','View Details','查看详细信息','查看詳細信息',0,1389120951),(171,'van.puti.ca','common','save id card','Save ID Card','保存ID卡','保存ID卡',0,1389120950),(172,'van.puti.ca','common','enroll','Enroll','报名','報名',0,1389120949),(173,'van.puti.ca','common','remove','Remove','移除','移除',0,1388973515),(174,'van.puti.ca','common','group member confirm','Group Member Confirm','学员信息确认表','學員信息確認表',0,1388973501),(175,'van.puti.ca','common','group label print','Group Label Print','小组学生卡片打印','小組學生卡片打印',0,1388973482),(176,'van.puti.ca','common','group attandance','Group Attandance','小组考勤表格','小組考勤表格',0,1388973459),(177,'van.puti.ca','common','people','People','人','人',0,1388973438),(178,'van.puti.ca','common','group','Group','小组','小組',0,1388713116),(179,'van.puti.ca','common','opened event','Opened Event','已经公开的课程','已經公開的課程',0,1388973432),(180,'van.puti.ca','common','event groups','Event Groups','课程分组','課程分組',0,1388973368),(181,'van.puti.ca','common','event students','Event Students','课程所有学员','課程所有學員',0,1388973408),(182,'van.puti.ca','common','students - cancel','Students - Cancel','未批准学员','未批準學員',0,1388973388),(183,'van.puti.ca','common','id number','ID Card','ID卡号','ID卡號',0,1423072432),(184,'van.puti.ca','common','save all idd','Save All','保存所有ID号','保存所有ID號',0,1388973342),(185,'van.puti.ca','common','age','Age','年龄','年齡',0,1388973321),(186,'van.puti.ca','common','id card','ID Card','ID卡号','ID卡號',0,1388973307),(187,'van.puti.ca','common','p.date','P.Date','付款日','付款日',0,1388973295),(188,'van.puti.ca','common','paid','Paid','付款','付款',0,1388958093),(189,'van.puti.ca','common','web','Web','网上','网上',0,1388973285),(190,'van.puti.ca','common','page','Page','页','頁',0,1388973273),(191,'van.puti.ca','common','page of','of','总页','總頁',0,1388973267),(192,'van.puti.ca','common','page records total','Total Records','总共查找到','總共查找到',0,1388973255),(193,'van.puti.ca','common','page size','Page Size','每页','每頁',0,1388973240),(194,'van.puti.ca','common','event title','Event Title','课程名称','課程名稱',0,1388973228),(195,'van.puti.ca','common','sign?','Sign?','签字?','簽字',0,1388973213),(196,'van.puti.ca','common','attd.','Attd.','出勤率','出勤率',0,1388973192),(197,'van.puti.ca','common','grad.?','Grad.?','毕业?','畢業？',0,1388973181),(198,'van.puti.ca','common','cert.?','Cert.?','证书?','證書？',0,1388973172),(199,'van.puti.ca','common','amt.','Amt.','金额','金額',0,1388973162),(200,'van.puti.ca','common','p.information','Information','个人信息','個人信息',0,1388973064),(201,'van.puti.ca','common','emergency','Emergency','紧急联系','緊急聯系',0,1388973155),(202,'van.puti.ca','common','q & a','Q & A','状况问答','狀況問答',0,1388973145),(203,'van.puti.ca','common','records','Records','历史记录','歷史記錄',0,1388973075),(204,'van.puti.ca','common','created time','Created Time','创建时间','創建時間',0,1388973131),(205,'van.puti.ca','common','last updated','Last Updated','最近修改','最近修改',0,1388973119),(206,'van.puti.ca','common','last login','Last Login','最近登录','最近登錄',0,1388973105),(207,'van.puti.ca','common','login count','Login Count','登录次数','登錄次數',0,1388973091),(208,'van.puti.ca','common','p.address','Address','家庭住址','家庭住址',0,1388973052),(209,'van.puti.ca','common','member details','Member Details','学员详细资料','學員詳細信息',0,1388973035),(210,'van.puti.ca','common','print details','Print Details','打印详细信息','打印詳細信息',0,1388973012),(211,'van.puti.ca','common','delete record','Delete Record','删除记录','刪除記錄',0,1388972997),(212,'van.puti.ca','common','print empty signature','Print Empty Student Form','打印空白学员登记表','打印空白學員登記表',0,1388972985),(213,'van.puti.ca','common','subject','Subject','题目','題目',0,1388972957),(214,'van.puti.ca','common','identity','Identity','版本识别','版本識別',0,1388972948),(215,'van.puti.ca','common','please specify email version. for example: a, b, c','please specify email version. for example: A, B, C','请填写电子邮件的版本号，如 A, B , C','請填寫電子郵件的版本號。如A.B.C',0,1388972933),(216,'van.puti.ca','common','content','Content','内容','內容',0,1388972861),(217,'van.puti.ca','common','to be confirmed','To be confirmed','未确定','未確定',0,1388972849),(218,'van.puti.ca','common','are you sure to clear email pool','Are you sure to clear Email Pool？','你确定要清空电子邮件池吗？','你確定要清空電子郵件池嗎',0,1388972480),(219,'van.puti.ca','common','clear pool','Clear Poll','清空','清空',0,1388972837),(220,'van.puti.ca','common','please select the event','Please Select The Event','请选择课程','請選擇課程',0,1388972827),(221,'van.puti.ca','common','error: please select the event from event list','Error: Please select the event from event list','错误： 考勤打卡必须选择一个课程','錯誤：考勤打卡必須選擇一個課程',0,1388972811),(222,'van.puti.ca','common','time','Time','时间','時間',0,1388972762),(223,'van.puti.ca','common','scan id card here','Scan ID Card','扫描你的ID卡','掃描你的ID卡',0,1388709618),(224,'van.puti.ca','common','to check in','to Check In','记录考勤','記錄考勤',0,1388972748),(225,'van.puti.ca','common','please select event','Select Event','请选择课程','請選擇課程',0,1393653516),(226,'van.puti.ca','common','grand total','Grand Total','总计','總計',0,1388972689),(227,'van.puti.ca','common','punch','Punch','刷卡','刷卡',0,1394593948),(228,'van.puti.ca','common','student','Student','学员','學員',0,1388972675),(229,'van.puti.ca','common','check in list','Check In List','考勤清单','考勤清單',0,1388972667),(230,'van.puti.ca','common','welcome','Welcome','欢迎','歡迎',0,1388972655),(231,'van.puti.ca','common','not registered yet please try again','not registered yet !','此卡没有注册','此卡沒有註冊',0,1388972643),(232,'van.puti.ca','common','vol.','Vol.','义工','义工',0,1418509335),(233,'van.puti.ca','common','checklist','Checklist','清单','清單',0,1388972610),(234,'van.puti.ca','common','a.all','ALL','所有学员','所有學員',0,1388972601),(235,'van.puti.ca','common','a.check','Check','义工','義工',0,1388972587),(236,'van.puti.ca','common','a.uncheck','Uncheck','非义工','非義工',0,1388972580),(237,'van.puti.ca','common','button print','Print','打印','打印',0,1388972568),(238,'van.puti.ca','common','to return the card','to return the card','返还ID卡','返還ID卡',0,1388972561),(239,'van.puti.ca','common','holder','Holder','持卡人','持卡人',0,1388972539),(240,'van.puti.ca','common','return success see you next time','return success. see you next tim','卡回收成功， 欢迎下次再参加菩提禅修','卡回收成功，歡迎下次再參加菩提禪修',0,1388972529),(241,'van.puti.ca','common','email pool clear successful','email pool clear successful','邮件池成功清空','郵件池成功清空',0,1388972436),(242,'van.puti.ca','common','a.sign','Sign','签字','簽字',0,1388972407),(243,'van.puti.ca','common','grad.','Grad.','毕业','畢業',0,1388972397),(244,'van.puti.ca','common','cert.','Cert.','证书','證書',0,1388972391),(245,'van.puti.ca','common','event attendance','Event Attendance','课程出勤率','課程出勤率',0,1388972368),(246,'van.puti.ca','common','day','Day','第','第',0,1388972349),(247,'van.puti.ca','common','day1','&nbsp;','天','天',0,1389120946),(248,'van.puti.ca','common','event list','Event List','课程清单','課程清單',0,1388972322),(249,'van.puti.ca','common','grp','Grp','组','組',0,1388972309),(250,'van.puti.ca','common','paid date','paid date','付款日','付款日',0,1388972302),(251,'van.puti.ca','common','amount','Amount','金额','金額',0,1388972291),(252,'van.puti.ca','common','student list','Student List','学生清单','學生清單',0,1388972283),(253,'van.puti.ca','common','unpay','Unpay','未付款','未付款',0,1388972251),(254,'van.puti.ca','common','attend calculate info','Attendance Calculate will recalculate and overwrite the current attendance records.<br><br>Only this period records will be affected','计算考勤会把所选日期的考勤结果重新计算和覆盖已存在的结果.\n<br><br>这个期间的记录','計算考勤會把所選日期的考勤結果重新計算和覆蓋已存在的結果.<br><br>這個期間的記錄',0,1388710882),(255,'van.puti.ca','common','checkin time range','CheckIn Time Range','考勤时间段','考勤時間段',0,1388972236),(256,'van.puti.ca','common','get data','Get Data','获取数据','獲取數據',0,1388972216),(257,'van.puti.ca','common','calculate attendance','Calculate Attendance','计算考勤','計算考勤',0,1388972201),(258,'van.puti.ca','common','all records','All Records','所有数据','所有數據',0,1388972188),(259,'van.puti.ca','common','matched records','Matched Records','匹配数据','匹配數據',0,1388972175),(260,'van.puti.ca','common','from','From','从','從',0,1388972158),(261,'van.puti.ca','common','to','To','到','到',0,1388972151),(262,'van.puti.ca','common','are you sure delete this event?','Are you sure delete this event?','你确定要删除这个课程吗?','你確定要刪除這個課程？',0,1388972143),(263,'van.puti.ca','common','site','Site','地点','地點',0,1388972106),(264,'van.puti.ca','common','vancouver','Vancouver','温哥华','溫哥華',0,1388972095),(265,'van.puti.ca','common','english teaching','Meditation Classes in English','菩提禅修英文课程','菩提禪修英文課程',0,1398214337),(266,'van.puti.ca','common','mandarin teaching','Meditation Classes in Mandarin','菩提禅修中文课程','菩提禪修中文課程',0,1398214339),(267,'van.puti.ca','common','cantonese teaching','Meditation Classes in Cantonese','菩提禅修粤语课程','菩提禪修粵語課程',1,1389116460),(268,'van.puti.ca','common','closed','Closed','已结束','已結束',0,1388972087),(269,'van.puti.ca','common','open','Open','开放','開放',0,1388972073),(270,'van.puti.ca','common','button delete','Delele','删除','刪除',0,1388972065),(271,'van.puti.ca','common','description','Description','描述','描述',0,1388972057),(272,'van.puti.ca','common','event detail','Event Detail','课程信息','課程信息',0,1388972047),(273,'van.puti.ca','common','date range','Date Range','日期段','日期段',0,1388972035),(274,'van.puti.ca','common','breakfast','Breakfast','早餐','早餐',0,1388972022),(275,'van.puti.ca','common','lunch','Lunch','午餐','午餐',0,1388972012),(276,'van.puti.ca','common','dinner','Dinner','晚餐','晚餐',0,1388972004),(277,'van.puti.ca','common','date length','Date Length','天数','天數',0,1388971995),(278,'van.puti.ca','common','meal','Meal','供餐','供餐',0,1388971988),(279,'van.puti.ca','common','certification','Certification','颁发证书','頒發證書',0,1388971978),(280,'van.puti.ca','common','quick form','Quick Form','简要表格','簡要表格',0,1388971963),(281,'van.puti.ca','common','full form','Full Form','详细表格','詳細表格',0,1388971950),(282,'van.puti.ca','common','checkin times','CheckIn Times','考勤次数','考勤次數',0,1388971934),(283,'van.puti.ca','common','attend percent','Attend Percent','毕业出勤率','畢業出勤率',0,1388971919),(284,'van.puti.ca','common','register form way','Register Form','报名表格','報名表格',0,1388971896),(285,'van.puti.ca','common','button clear','Clear','清除','清除',0,1388971883),(286,'van.puti.ca','common','agreement','Agreement','同意书','同意書',0,1388971871),(287,'van.puti.ca','common','groups','Groups','组','組',0,1388971860),(288,'van.puti.ca','common','classes','Classes','课程','課程',0,1388971853),(289,'van.puti.ca','common','toronto','Toronto','多伦多','多倫多',0,1388971846),(290,'van.puti.ca','common','submit success','Submit Success','提交成功','提交成功',0,1388971838),(291,'van.puti.ca','common','add date','Add Date','添加日期','添加日期',0,1388971820),(292,'van.puti.ca','common','start date','Start Date','开始日期','開始日期',0,1388971721),(293,'van.puti.ca','common','class list','Class List','课程列表','課程列表',0,1388971709),(294,'van.puti.ca','common','class details','Class Details','课程信息','課程信息',0,1388971691),(295,'van.puti.ca','common','agreement list','Agreement List','同意书清单','同意書清單',0,1388971678),(296,'van.puti.ca','common','agreement details','Agreement Details','同意书信息','同意書信息',0,1388971662),(297,'van.puti.ca','common','title','Title','题目','題目',0,1388971645),(298,'van.puti.ca','common','event attendance report','Event Attendance Report','课程考勤报告','課程考勤報告',0,1388971638),(299,'van.puti.ca','common','c.male','M','男','男',0,1388971620),(300,'van.puti.ca','common','c.female','F','女','女',0,1388971613),(301,'van.puti.ca','common','c.total','Total','合计','合計',0,1388971607),(302,'van.puti.ca','common','sign','Sign','签名','簽名',0,1388971586),(303,'van.puti.ca','common','graduate','Graduate','毕业','畢業',0,1388971470),(304,'van.puti.ca','common','end date','End Date','结束日期','結束日期',0,1388971461),(305,'van.puti.ca','common','class','Class','课程','課程',0,1388971446),(306,'van.puti.ca','common','c.alias','Alias','别名','別名',0,1388971436),(307,'van.puti.ca','common','att.pp','Att.PP','出席','出席',0,1388971303),(308,'van.puti.ca','common','punch.tm','Punch.Tm','刷卡次数','刷卡次數',0,1394593971),(309,'van.puti.ca','common','punch.pp','Punch.PP','刷卡人数','刷卡人數',0,1394593972),(310,'van.puti.ca','common','att.rate','Att.Rate','出勤率','出勤率',0,1388971392),(311,'van.puti.ca','common','day no.','Day No.','第几天','第幾天',0,1388971381),(312,'van.puti.ca','common','class subject','Class Subject','课程题目','課程題目',0,1388971371),(313,'van.puti.ca','common','g.report','Report','生成报表','生成報表',0,1388971358),(314,'van.puti.ca','common','search event','Search Event','查找课程','查找課程',0,1388971341),(315,'van.puti.ca','common','pun.tm','Pun.Tm','卡次','卡次',0,1388971317),(316,'van.puti.ca','common','pun.pp','Pun.PP','人数','人數',0,1388971327),(317,'van.puti.ca','common','puti student form','Bodhi Meditation Student Registration Form','菩提禅修学员登记表','菩提禪修學員登記表',0,1388970491),(318,'van.puti.ca','common','contact by','Contact By','联系方式','聯繫方式',0,1388970604),(319,'van.puti.ca','common','signature','Signature','签字','簽字',0,1388970499),(320,'van.puti.ca','common','puti student form1','Bodhi Meditation Student Registration Form','è©æç¦…ä¿®å­¦å‘˜ç™»è®°è¡¨',NULL,1,1384143589),(321,'van.puti.ca','common','t.yes','Yes','有','有',0,1388970467),(322,'van.puti.ca','common','t.no','No','没有','沒有',0,1388970460),(323,'van.puti.ca','common','button cancel','Cancel','取消','取消',0,1388970451),(324,'van.puti.ca','common','legal first','Legal First','护照名','護照名',0,1422905012),(325,'van.puti.ca','common','legal last','Legal Last','护照姓','護照姓',0,1422905013),(326,'van.puti.ca','common','you have signed in successful.','You have signed in successful.','您已经成功报名了.','您已經成功報名了',0,1388970381),(327,'van.puti.ca','common','issue id card','ID Card','IDå¡',NULL,1,1384842998),(328,'van.puti.ca','common','id card list','ID Card List','ID卡清单','ID卡清單',0,1388970356),(329,'van.puti.ca','common','issue date','Issue Date','发卡日期','發卡日期',0,1388970275),(330,'van.puti.ca','common','roles','Roles','权限','權限',0,1388970261),(331,'van.puti.ca','common','dep.s','Dep.s','部门','部門',0,1388970242),(332,'van.puti.ca','common','r.groups','Groups','教学组','教學組',0,1388970205),(333,'van.puti.ca','common','sites','Sites','禅堂','禪堂',0,1388970235),(334,'van.puti.ca','common','admin user details','Admin User Details','系统用户信息','係統用戶信息',0,1388970224),(335,'van.puti.ca','common','login name','Login Name','登录名','登錄名',0,1388970171),(336,'van.puti.ca','common','r.teaching','Teaching','教学部门','教學部門',0,1388970148),(337,'van.puti.ca','common','r.site','Site','所属禅堂','所屬禪堂',0,1388970134),(338,'van.puti.ca','common','r.sites','Sites','跨点访问','跨點訪問',0,1388970098),(339,'van.puti.ca','common','role','Role','权限','權限',0,1388970075),(340,'van.puti.ca','common','rr.groups','Groups','跨组访问','跨組訪問',0,1388970062),(341,'van.puti.ca','common','users','Users','用户','用戶',0,1388970042),(342,'van.puti.ca','common','login info tips','Login with either \"Login Name\" or \"Email\".','请使用 \"登录名\" 或者 \"电子邮件\" 来登录系统.','請使用\"登陸名\"或者\"電子郵件\"來登陸係統',0,1388710449),(343,'van.puti.ca','common','set password','Set Password','设置密码','設置密碼',0,1388710492),(344,'van.puti.ca','common','confirm password','Confirm','确认密码','確認密碼',0,1388710491),(345,'van.puti.ca','common','password length tips','Password must be 6+ digi, it can be number or letter.','密码必须是 6 位以上数字或者字符.','密碼必須是 6 位以上數字或者字符',0,1410070594),(346,'van.puti.ca','common','password','Password','密码','密碼',0,1388710493),(347,'van.puti.ca','common','my account','My Account','我的帐号信息','我的帳號',0,1388970027),(348,'van.puti.ca','common','group right','Group Right','组权限','組權限',0,1388969955),(349,'van.puti.ca','common','group name','Group Name','组名称','組名稱',0,1388969942),(350,'van.puti.ca','common','details','Details','详细信息','詳細信息',0,1388969928),(351,'van.puti.ca','common','view','view','查看','查看',0,1388969914),(352,'van.puti.ca','common','delete','delete','删除','刪除',0,1388969905),(353,'van.puti.ca','common','save','save','保存','保存',0,1388969883),(354,'van.puti.ca','common','print','print','打印','打印',0,1388969876),(355,'van.puti.ca','common','add','add','新增','新增',0,1388969862),(356,'van.puti.ca','common','welcome page content','Welcome to website management cpanel.','欢迎您使用后台管理系统','歡迎您使用後臺管理係統',0,1389120923),(357,'van.puti.ca','common','beta','Test Database','测试数据库','測試數據',0,1388969812),(358,'van.puti.ca','common','are you sure to delete this record?','are you sure to delete this record?','你确定要删除吗?','你確定要刪除嗎？',0,1388969798),(359,'van.puti.ca','common','report level','Report Level','报表层次','報表層次',0,1388969746),(360,'van.puti.ca','common','by times','By Times','按服务次数','按服務次數',0,1388969722),(361,'van.puti.ca','common','by head','By People','按义工人数','按義工人數',0,1388968643),(362,'van.puti.ca','common','select all','Select All','选择全部','選擇全部',0,1388968624),(363,'van.puti.ca','common','unselect all','Unselect All','不选全部','不選全部',0,1388968196),(364,'van.puti.ca','common','summary by dep','Summary by Department','只有部门汇总','只有部門匯總',0,1388968180),(365,'van.puti.ca','common','summary by dep, vol','Summary by Department, Volunteer','包括部门汇总, 义工汇总','包括部門彙總，義工彙總',0,1388968165),(366,'van.puti.ca','common','summary by dep, vol, det','Summary by Department, Volunteer, Details','包括部门汇总, 义工汇总, 服务明细','包括部門彙總，義工彙總，服務明細',0,1388968122),(367,'van.puti.ca','common','summary by vol','Summary by Volunteer','只有义工汇总','只有義工彙總',0,1388968059),(368,'van.puti.ca','common','summary by vol, dep','Summary by Volunteer, Department','包括义工汇总, 所在部门汇总','包括義工匯總，所在部門彙總',0,1388968039),(369,'van.puti.ca','common','summary by vol, dep, det','Summary by Volunteer, Department, Details','包括义工汇总, 所在部门汇总, 服务工时明细','包括義工匯總，所在部門匯總，服務工時明細',0,1389120922),(370,'van.puti.ca','common','by deps','By Department','部门数','部門數',0,1388967967),(371,'van.puti.ca','common','event summary report','Event Summary Report','课程汇总报告','課程匯總報告',0,1388967957),(372,'van.puti.ca','common','bodhi meditation classes','Bodhi Meditation Classes','菩提禅修课程清单','菩提禪修課程清單',0,1388967937),(373,'van.puti.ca','common','filter','Filter','过滤','過濾',0,1388967912),(374,'van.puti.ca','common','please input your name, email, phone or cell to below box','Please input your Name, Email, Phone or Cell to below box','请输入你的名字，电子邮件，或者电话，手提.','請輸入你的名字，電子郵件或電話',0,1388967900),(375,'van.puti.ca','common','bodhi meditation upcoming classes','Bodhi Meditation Upcoming Classes','菩提禅修公开课程','菩提禪修公開課程',0,1388959880),(376,'van.puti.ca','common','please click sign in','Please click on \"Sign In\". if you didn\'t register your information, please fill up the registration form.','请点击 \"报名\". 如果你没有登记过你的信息， 请完成登记表格.','請點擊“報名”，如果你沒有登記過你的信息',0,1388960237),(377,'van.puti.ca','common','event calendar','Event Calendar','课程日历','課程日歷',0,1388967857),(378,'van.puti.ca','common','name | email | phone | cell','Name | Email | Phone | Cell','名字 | 邮件 | 电话 | 手提','名字|郵件|電話|手提',0,1388967844),(379,'van.puti.ca','common','event - sign in','Remind: Select Proper Event','注意：请选择正确的课程','注意：選擇正確的課程',0,1389296352),(380,'van.puti.ca','common','button sign in','Sign In','报名','報名',0,1388967786),(381,'van.puti.ca','common','tel','Tel','电话','電話',0,1388967778),(382,'van.puti.ca','common','matched members','Matched Members','找到你的信息','找到你的信息',0,1388967767),(383,'van.puti.ca','common','registration form','Registration Form','学员登记表格','學員登記表格',0,1388967750),(384,'van.puti.ca','common','event agreement','Event Agreement','课程免责声明','課程免責聲明',0,1388967732),(385,'van.puti.ca','common','select & sign in','Select & Sign In','选择并报名','選擇并報名',0,1388967664),(386,'van.puti.ca','common','check before read','Please read this agreement first, then check \"I have read\".','请先阅读免责声明，勾选我已经阅读，以确定你是否接受条款.','請先閱讀免責聲明，勾選我已經閱讀，',0,1388967645),(387,'van.puti.ca','common','match sign in success','The below members have signed in successful.','以下学员报名成功.','以下學員報名成功',0,1388967452),(388,'van.puti.ca','common','sel.','Sel.','选择','選擇',0,1388967278),(389,'van.puti.ca','common','we found below matched members','We found below matched members, please select the proper member.','我们找到你曾经登记过的信息， 请选择需要报名的学员.','我們找到你曾經登記過的信息，請選擇',0,1388967268),(390,'van.puti.ca','common','error message','Error Message','错误信息','錯誤信息',0,1388967198),(391,'van.puti.ca','common','register success','Register Successful','登记成功','登記成功',0,1388967169),(392,'van.puti.ca','common','please select location','Location','选择地点','選擇地點',0,1398210465),(393,'van.puti.ca','common','not ready for enrollment','not ready for enrollment','课程还没有开放报名,请等一段时间.','課程還沒有開放報名，請等一段時間',0,1388960290),(394,'van.puti.ca','common','open for enrollment','open for enrollment','课程可以报名了','課程可以報名了',0,1388966744),(395,'van.puti.ca','common','click here to register','click here to register','点击这里登记报名','點擊這裏登記報名',0,1388966723),(396,'van.puti.ca','common','telephone','Telephone','电话','電話',0,1388966701),(397,'van.puti.ca','common','location','Location','地点','地點',0,1388960521),(398,'van.puti.ca','common','submit error','We can not process your submit for below error','我们不能保存你的资料，因为以下原因','我們不等保存你的資料，因為以下原因',0,1388960505),(399,'van.puti.ca','common','read agreement before submit','Please read our \'Individual and Risk Release\' before submit.','请先阅读我们的免责声明，然后勾选我已阅读，以决定你是否接受条款.','請先閱讀我們的免責聲明，然後勾選我們',0,1388960452),(400,'van.puti.ca','common','you dont read agreement','You don\'t agree our \'Individual and Risk Release\'.','你并没有同意我们的免责条款，我們不接受你的申請。','你并沒有同意我們的免責條款，我們不接受你的申請。',0,1394912441),(401,'van.puti.ca','common','retrieve password successful.','Retrieve Password Successful.','密码获取成功.','密碼獲取成功',0,1388710520),(402,'van.puti.ca','common','retrieve password','Retrieve Password','取回密码','取回密碼',0,1388710526),(403,'van.puti.ca','common','register as administrator','Register as Administrator','登记成为用户','登記成為用戶',0,1388960154),(404,'van.puti.ca','common','user name','User Name','用户名','用戶名',0,1388710461),(405,'van.puti.ca','common','platform','PlatForm','数据库','數據庫',0,1388710599),(406,'van.puti.ca','common','production','Production','正式数据库','正式數據庫',0,1388710622),(407,'van.puti.ca','common','test','Test','测试数据库','測試數據庫',0,1388710633),(408,'van.puti.ca','common','button login','Login','登录','登陸',0,1388710646),(409,'van.puti.ca','common','login system','Login','登录管理平台','登錄管理平臺',0,1388710393),(410,'van.puti.ca','common','forget password?','If you forgot password, please click to retrieve your password','如果忘记密码，点击这里取回密码','如果忘記密碼，點擊這裏取回密碼',0,1388710548),(411,'van.puti.ca','common','password sent to you','Please specify your email, Password will be sent to your email.','请提供你注册登记的电子邮件，密码将会发送到你登记的电子邮件.','請提供你的註冊的電子郵件，密碼將會發送到你登記的電子郵件',0,1388710582),(412,'van.puti.ca','common','welcome to our meditation class.','Welcome to our meditation class.','欢迎您报名参加菩提禅修课程.','歡迎您參加菩提禪修課程',0,1388960133),(413,'van.puti.ca','common','your registration details as below','Enrolled Successful. your registration details as below','已经成功报名,你的报名登记的详细信息如下','已經成功報名，你的報名登記詳細信息如',0,1388959952),(414,'van.puti.ca','common','register failure','Your registration for our meditation class has been fail, please try again.','你注册我们的禅修课程失败，请重新再试一次，或者联系我们.','你註冊我們的禪修課程失敗，請重新再試',0,1388966606),(415,'van.puti.ca','common','remind','Remind','提醒','提醒',0,1388960085),(416,'van.puti.ca','common','please remember class start date and your group no.','Please remember class start date and your Group No.','请记住上课的时间和你的小组号码.','請記住上課時間和你的小組號',0,1388960077),(417,'van.puti.ca','common','class name','Class Name','课程名称','課程名稱',0,1388960024),(418,'van.puti.ca','common','the link in your email has been expired.','The link in your email has been expired.','你电子邮件里的确认链接已经过期或者失效. 谢谢你的回复.','你的電子郵件里的確認連接已經過期，請重新更',0,1388966396),(419,'van.puti.ca','common','thank you for your confirmation','Thank you for your confirmation','谢谢你的确认回复','謝謝你的確認回复',0,1388960007),(420,'van.puti.ca','common','g.site','Site','禅堂','禪堂',0,1388959853),(421,'van.puti.ca','common','m.alias','Alias','别名','別名',0,1388959841),(422,'van.puti.ca','common','student id','Student ID','学生证','學生證',0,1388959833),(423,'van.puti.ca','common','g.leader','L.','正','正',0,1418509813),(424,'van.puti.ca','common','g.volunteer','V.','副','副',0,1418509737),(425,'van.puti.ca','common','tag.volunteer','V.Leader','副组长','副组长',0,1418509522),(426,'van.puti.ca','common','tag.leader','G.Leader','组长','組長',0,1390673596),(427,'van.puti.ca','common','tag.title','Title','职务','職務',0,1390673598),(428,'van.puti.ca','common','c.id','ID','标识号','標識號',0,1388959821),(429,'van.puti.ca','common','button merge','Merge','合并','合併',0,1388959817),(430,'van.puti.ca','common','please assign this record to other id','Please provide other ID to take over this record','请指定其他标识号来继承此历史记录','請指定其他標識號來繼續此歷史記錄',0,1388959728),(431,'van.puti.ca','common','volunteer - merge','Volunteer - Merge','重复义工合并','重復義工合併',0,1388959819),(432,'van.puti.ca','common','button close','Close','关闭','關閉',0,1388959686),(433,'van.puti.ca','common','if merge successful, it will delete this record. are you sure?','If merge successful, it will delete this record. are you sure?','如果合并成功，会把资料转移，并把这条记录删除，你确定要合并吗？','如果合並成功，會把資料',0,1388959677),(440,'van.puti.ca','common','keyword','Keyword','关键字','關鍵字',0,1388959627),(441,'van.puti.ca','common','lang.en','English','英文','英文',0,1388959618),(442,'van.puti.ca','common','lang.cn','Chinese','中文','中文',0,1388959611),(443,'van.puti.ca','common','ailment & symptom','Ailment & Symptom','身体症状','身體症狀',0,1388959599),(444,'van.puti.ca','common','heart','Heart','心脏','心臟',0,1388959584),(445,'van.puti.ca','common','rachis','Backbone','脊椎','脊椎',0,1388959392),(446,'van.puti.ca','common','headache','Headache','头痛','頭痛',0,1388959575),(447,'van.puti.ca','common','insomnia','Insomnia','失眠','失眠',0,1388959562),(448,'van.puti.ca','common','other symptom','Other Symptom','其他症状','其他症狀',0,1388959549),(449,'van.puti.ca','common','specify','Specify','指明','指明',0,1388959537),(450,'van.puti.ca','common','statistics','Statistics','统计','統計',0,1388959523),(451,'van.puti.ca','common','unknown','Unknown','未知','未知',0,1416177416),(452,'van.puti.ca','common','count','Count','计数','計數',0,1388959476),(453,'van.puti.ca','common','student number','Student Number','学员人数','學員人數',0,1388959467),(454,'van.puti.ca','common','hear from','Hear From','认识渠道','認識渠道',0,1388959453),(455,'van.puti.ca','common','back pain','Back pain','背痛','背痛',0,1389656796),(456,'van.puti.ca','common','hypochondria','Hypochondria','忧郁症','憂鬱症',0,1388959415),(457,'van.puti.ca','common','arthrosis','Arthrosis','关节痛','關節痛',0,1388959403),(458,'van.puti.ca','common','rheumatism','Rheumatism','风湿症','風濕症',0,1388959369),(459,'van.puti.ca','common','stomachache','Stomachache','胃痛','胃痛',0,1388959357),(460,'van.puti.ca','common','kidney','Kidney','肾','腎',0,1388959348),(461,'van.puti.ca','common','menu_member_stat','Member Statistics','学员统计','學員統計',0,1388958806),(462,'van.puti.ca','common','all members statistics','All Members Statistics','所有学员统计','所有學員統計',0,1388958796),(463,'van.puti.ca','common','add job','Add Job','添加工作','添加工作',0,1388958780),(464,'van.puti.ca','common','job content','Job Content','工作内容','工作內容',0,1388958764),(465,'van.puti.ca','common','include job content','Include Job Content','包括工作内容','包括工作內容',0,1388958743),(466,'van.puti.ca','common','menu_1155','Dep.-Job-Detail','部门 - 工作内容 － 明细','部門－工作內容－明細',0,1388958725),(467,'van.puti.ca','common','summary by dep, job','Summary by Department, Job','包括部门， 工作内容','包括部門工作內容',0,1388958661),(468,'van.puti.ca','common','summary by dep, job, vol','Summary by Dep., Job, Vol.','包括部门，工作内容，义工汇总','包括內容，工作內容，義工彙總',0,1388958697),(469,'van.puti.ca','common','summary by dep, job, vol, det','Summary by Dep., Job, Vol.,Detail','包括部门，工作内容，义工，明细','包括部門，工作內容，義工，明細',0,1388958635),(470,'van.puti.ca','common','department en','English Name','英文名称','英文名稱',0,1388958577),(471,'van.puti.ca','common','hypertension','Hypertension','高血压','高血壓',0,1388958563),(472,'van.puti.ca','common','hyperglycemia','Hyperglycemia','高血糖','高血糖',0,1388958557),(473,'van.puti.ca','common','diabetes','Diabetes','糖尿病','糖尿病',0,1388958548),(474,'van.puti.ca','common','hyperlipidemia','Hyperlipidemia','高血脂','高血脂',0,1388958534),(475,'van.puti.ca','common','digestive','Digestive','消化系统','消化係統',0,1389657208),(476,'van.puti.ca','common','obesity','Obesity','肥胖症','肥胖症',0,1388958525),(477,'van.puti.ca','common','cardio-cerebrovascular','Cardio-Cerebrovascular','心脑血管','心腦血管',0,1388958496),(478,'van.puti.ca','common','emerg.contact','Emerg.Contact','紧急联系人','緊急聯系人',0,1388958481),(479,'van.puti.ca','common','emerg.phone','Emerg.Phone','紧急联系电话','緊急聯係電話',0,1388958469),(480,'van.puti.ca','common','emerg.relative','Emerg.Relative','关系','關係',0,1388958452),(481,'van.puti.ca','common','therapy?','Therapy','理疗过','理療過',0,1388860901),(482,'van.puti.ca','common','therapy kind','Therapy Kind','理疗的内容','理療的內容',0,1388958310),(483,'van.puti.ca','common','medical concern','Medical Concern','医疗上的关注','醫療上的關注',0,1388958297),(484,'van.puti.ca','common','menu_table','Output Table','导出表格','導出表格',0,1388958256),(485,'van.puti.ca','common','table name','Table Name','数据表名称','數據表名稱',0,1388958228),(486,'van.puti.ca','common','leader','Leader','组长','組長',0,1388958162),(487,'van.puti.ca','common','volunteer','Volunteer','义工','義工',0,1388958154),(488,'van.puti.ca','common','participate','Participate','参加次数','參加次數',0,1388958145),(489,'van.puti.ca','common','head count','Head','人数','人數',0,1388958129),(490,'van.puti.ca','common','family','Family','家人','家人',0,1388958121),(491,'van.puti.ca','common','professional','Professional','专家推荐','專家推薦',0,1388958114),(492,'van.puti.ca','common','dep.no','Dep.No','部门数','部門數',0,1388958083),(493,'van.puti.ca','common','email confirm','Email Confirm','邮件阅读','郵件閱讀',0,1388958070),(494,'van.puti.ca','common','confirm','Confirm','签字确认','簽字確認',0,1388958054),(495,'van.puti.ca','common','transportation','Transportation','交通工具','交通工具',0,1388958040),(496,'van.puti.ca','common','plate no','Plate No.','车牌号','車牌號',0,1388958025),(497,'van.puti.ca','common','by walk','By Walk','步行','步行',0,1388958012),(498,'van.puti.ca','common','offer carpool','Offer Carpool','我愿意提供搭车','我願意提供搭車',0,1388957999),(499,'van.puti.ca','common','i drive','I Drive','我开车','我開車',0,1388957970),(500,'van.puti.ca','common','i need carpool','I Need Carpool','我需要搭车','我需要搭車',0,1388957852),(501,'van.puti.ca','common','i_drive','I Drive','开车','開車',0,1388957827),(502,'van.puti.ca','common','offer_carpool','Offer Carpool','提供搭车','提供搭車',0,1388957818),(503,'van.puti.ca','common','need_carpool','Need Carpool','需要搭车','需要搭車',0,1388957806),(504,'van.puti.ca','common','if driving, please help','If driving, please help','如果您开车，请提供帮助','如果您開車，請提供幫助',0,1388957785),(505,'van.puti.ca','common','public transportation','Public Transit','公共交通','公共交通',0,1388957743),(506,'van.puti.ca','common','tbc','Unknown','未分组','未分組',0,1388957719),(507,'van.puti.ca','common','please select an event','Please Select an Event','请先选择一个课程','請選擇一個課程',0,1388861020),(508,'van.puti.ca','common','trial','Trial','试听','試聽',0,1388860996),(509,'van.puti.ca','common','trial time','Trial Time','试听时间','試聽時間',0,1388860990),(510,'van.puti.ca','common','legal name','Legal Name','护照名','護照名',0,1388860976),(511,'van.puti.ca','common','none.di','Group','第','第',0,1394819047),(512,'van.puti.ca','common','none.zu','&nbsp;','组','組',0,1394819093),(513,'van.puti.ca','common','other.zu','L','组长','組長',0,1388860963),(514,'van.puti.ca','common','other.yi','V','副','副',0,1418509391),(515,'van.puti.ca','common','purpose','Purpose','主题','主題',0,1388860948),(516,'van.puti.ca','common','button refresh','Refresh','刷新','刷新',0,1388860940),(518,'van.puti.ca','common','identify number','ID Number','证件号','證件號',0,1388860900),(519,'van.puti.ca','common','save success','Save Success','保存成功','保存成功',0,1388860898),(520,'van.puti.ca','common','invoice','Invoice','发票号','發票號',0,1388860897),(521,'van.puti.ca','common','onsite registration','Onsite Registration','现场报名','現場報名',0,1388860897),(522,'van.puti.ca','common','read card error','ID Number is invalid, please try again!','ID卡号码无效，请再试!','ID卡號碼無效，請再試!',0,1393021866),(523,'van.puti.ca','common','student signature','Signature Form','学生签字表','學生簽字表',0,1388860894),(524,'van.puti.ca','common','has','Yes','有','有',0,1388860667),(525,'van.puti.ca','common','hasnt','No','没有','沒有',0,1388860660),(526,'van.puti.ca','common','member.title','Title','称号','稱號',0,1388860643),(527,'van.puti.ca','common','cancel enroll','Cancel Enroll','取消报名','取消報名',0,1388860636),(528,'van.puti.ca','common','enroll all','Enroll All','全部报名','全部報名',0,1388860624),(529,'van.puti.ca','common','enroll remove','Enroll Remove','全部取消','全部取消',0,1388860610),(530,'van.puti.ca','common','menu_crep1','Class Student','班级学员统计','班級學員統計',0,1388860597),(531,'van.puti.ca','common','include details','Include Details','包括课程明细','包括課程明細',0,1388860557),(532,'van.puti.ca','common','menu_certificate','Certification','证书打印','證書打印',0,1388860550),(533,'van.puti.ca','common','cert_no','Cert.No','证书号','證書號',0,1388860542),(534,'van.puti.ca','common','cert_no_prefix','Certificate Prefix','证书号前缀','証書',0,1388860301),(535,'van.puti.ca','common','button save cert','Generate Cert.','生成证书号','生成證書號',0,1388711342),(536,'van.puti.ca','common','print certificate','Print Cert.','打印证书','打印證書',0,1388711313),(537,'van.puti.ca','common','cert.temp','Cert.Template','证书模板','証書模板',0,1388860300),(538,'van.puti.ca','common','cert.level1','Level One','一级证书','一級證書',0,1389922256),(539,'van.puti.ca','common','cert.level2','Level Two','二级证书','二級證書',0,1388711286),(540,'van.puti.ca','common','please select cert. template','Please select a certificate template','请选择证书模板','請選擇證書模板',0,1388711280),(541,'van.puti.ca','common','photo download','Photo Download','照片下载','照片下載',0,1388709673),(542,'van.puti.ca','common','photo original download','Original Size Photo','原始照片下载','原始照片下載',0,1388709667),(543,'van.puti.ca','common','photo large download','Large Size Photo','2048X2048 照片下载','2048X2048 照片下載',0,1388709651),(544,'van.puti.ca','common','photo medium download','Medium Size Photo','800X800 照片下载','800X800 照片下載',0,1388709639),(545,'van.puti.ca','common','photo small  download','Small Size Photo','120X160 照片下载','120X160 照片下載',0,1388709639),(546,'van.puti.ca','common','cert.level3','Level Three','三级证书','三級證書',0,1388709620),(547,'van.puti.ca','common','photo checkin','Require Photo','需要照片','需要照片',0,1388867690),(548,'van.puti.ca','common','back to main','Main Menu','回到主菜单','回到主菜單',0,1388709618),(549,'van.puti.ca','common','menu_site','Puti Center','禅堂','禪堂',0,1388709617),(550,'van.puti.ca','common','menu_site_info','Puti Center','禅堂信息','禪堂信息',0,1388709616),(551,'van.puti.ca','common','site name','Site Name','禅堂名称','禪堂名稱',0,1388709616),(552,'van.puti.ca','common','site description','Site Desc.','禅堂描述','禪堂描述',0,1388709615),(553,'van.puti.ca','common','hongkong','Hongkong','香港','香港',0,1388709614),(554,'van.puti.ca','common','taibei','Taipei','台北','臺北',0,1398318083),(555,'van.puti.ca','common','pay once','Pay Once for retrance','一次学费多次重修','一次學費多次重修',0,1388860298),(556,'van.puti.ca','common','menu_cntw','繁体中文','繁体中文','繁體中文',0,1388860298),(557,'van.puti.ca','common','lang.tw','CHS.TW','繁体中文','繁體中文',0,1388860299),(558,'van.puti.ca','common','quick register','Quick Enroll','快速登记表格','快速登記表格',0,1388947456),(559,'van.puti.ca','common','full register','Full Enroll','完整登记表格','完整登記表格',0,1388947651),(560,'van.puti.ca','common','go back','Go Back','返回','返回',0,1388948713),(561,'van.puti.ca','common','detail','Detail','明细','詳細',0,1388952898),(562,'van.puti.ca','common','puti_enroll_desk','Front Desk','前台报名','前台報名',0,1388960671),(563,'van.puti.ca','common','member - merge','Member Merge','学员信息合并','學員信息合并',0,1389130779),(564,'van.puti.ca','common','are you sure to merge this record?','Are you sure to merge this record?','你确定要合并该记录吗？','你確定要合并此記錄嗎？',0,1389132974),(565,'van.puti.ca','common','cantonese teaching','Meditation Classes in Cantonese','菩提禅修粤语课程','菩提禪修粵語課程',0,1398214340),(566,'van.puti.ca','common','internal teaching','Internal Education','菩提内部培训课程','菩提內部培訓課程',0,1389312061),(567,'van.puti.ca','common','reg.date','Reg.Date','报名日期','報名日期',0,1389380166),(568,'van.puti.ca','common','c.photo','PH','照片','照片',0,1389418155),(569,'van.puti.ca','common','stroke','Stroke','中风','中風',0,1389656906),(570,'van.puti.ca','common','high blood pressure','High blood pressure','高血压','高血壓',0,1389656993),(571,'van.puti.ca','common','cholesterol','Cholesterol','高胆固醇','高膽固醇',0,1389657078),(572,'van.puti.ca','common','anxiety','Anxiety','忧郁','憂鬱',0,1389657152),(573,'van.puti.ca','common','depression','Depression','沮丧','沮喪',0,1389657273),(574,'van.puti.ca','common','weight issues','weight issues','体重','體重',0,1389657320),(575,'van.puti.ca','common','migraine','Migraine','偏头痛','偏頭痛',0,1389657363),(576,'van.puti.ca','common','anger','Anger','暴躁','暴躁',0,1389657459),(577,'van.puti.ca','common','fatigue','Fatigue','易疲劳','易疲勞',0,1389657504),(578,'van.puti.ca','common','menu_certificate_other','Other Certificate','其他证书','其他证书',0,1389918184),(579,'van.puti.ca','common','cert.bagua','Bagua Nametag','八卦学生证','八卦學生證',0,1389919349),(580,'van.puti.ca','common','cert.baishi','Dharma Name','菩提法名卡','菩提法名卡',0,1389919420),(581,'van.puti.ca','common','jinputi','金菩提上師親賜','金菩提上师亲赐','金菩提上師親賜',0,1389940465),(582,'van.puti.ca','common','yy','-','年','年',0,1389942211),(583,'van.puti.ca','common','mm','-','月','月',0,1389942223),(584,'van.puti.ca','common','dd','&nbsp;','日','日',0,1389942244),(585,'van.puti.ca','common','loc.vancouver','加拿大溫哥華藥師禪院','加拿大温哥华药师禅院','加拿大溫哥華藥師禪院',0,1389942578),(586,'van.puti.ca','common','loc.hongkong','菩提禪修香港藥師禪院','菩提禅修香港药师禅院','菩提禪修香港藥師禪院',0,1389981441),(587,'van.puti.ca','common','loc.toronto','加拿大多倫多藥師禪院','加拿大多伦多药师禅院','加拿大多倫多藥師禪院',0,1389981512),(588,'van.puti.ca','common','loc.taibei','菩提禪修台北藥師禪院','菩提禅修台北药师禅院','菩提禪修台北藥師禪院',0,1398318081),(589,'van.puti.ca','common','loc','Loc.','地点','地點',0,1389981792),(590,'van.puti.ca','common','cert.bagua1','Bagua Level1','八卦一级证书','八卦一级证书',0,1390353447),(591,'van.puti.ca','common','nametag template select','Select Student Card Template','选择学生卡模板','選擇學生卡模板',0,1390675661),(592,'van.puti.ca','common','nametag.temp','Card Template','学生卡模板','學生卡模板',0,1390675691),(593,'van.puti.ca','common','nametag.bigsize','Large Size Card','大尺寸学生卡','大尺寸學生卡',0,1390675718),(594,'van.puti.ca','common','nametag.cardsize','Name Card Size','名片尺寸学生卡','名片尺寸學生卡',0,1390675746),(595,'van.puti.ca','common','event calculation','Attendance Calculation','学生出勤计算','學生出勤計算',0,1391293795),(596,'van.puti.ca','common','nametag.vipsize','VIP Mini Size','贵宾小卡片','貴賓小卡片',0,1391640156),(597,'van.puti.ca','common','nametag.vipbsize','VIP Big Size','贵宾大卡片','貴賓大卡片',0,1391724967),(598,'van.puti.ca','common','cert.idcover','ID Cover','ID卡封面','ID卡封面',0,1391741602),(599,'van.puti.ca','common','member id','Member ID','内部标识号','內部標識號',0,1391900716),(600,'van.puti.ca','common','bagua teaching','Bagua Walking Classes','菩提禅修八卦课程','菩提禪修八卦課程',0,1392360003),(601,'van.puti.ca','common','radio','Radio','电台','電台',0,1392336952),(602,'van.puti.ca','common','id reader list','ID Card Reader List','ID读卡设备清单','ID讀卡設備列表',0,1392962434),(603,'van.puti.ca','common','site_desc','Bodhi Center','禅堂','禪堂',0,1392962502),(604,'van.puti.ca','common','place_desc','Place','殿堂','殿堂',0,1392962589),(605,'van.puti.ca','common','device_no','Device SN','读卡器号码','讀卡器號碼',0,1392962657),(606,'van.puti.ca','common','device_id','Device ID','读卡器编码','讀卡器編碼',0,1392962689),(607,'van.puti.ca','common','ip_address','IP Address','IP地址','IP地址',0,1392962723),(608,'van.puti.ca','common','last_updated','Last Updated','更新時間','更新時間',0,1392962780),(609,'van.puti.ca','common','select','Select','选择','選擇',0,1392963201),(610,'van.puti.ca','common','yaoshifo','YaoShiFo','药师佛殿','藥師佛殿',0,1416174941),(611,'van.puti.ca','common','guanyin','GuanYin','观音殿','觀音殿',0,1416173466),(612,'van.puti.ca','common','search id reader','Search ID Reader','搜索读卡器','搜索讀卡器',0,1393178714),(613,'van.puti.ca','common','button hide','Hide','隐藏','隱藏',0,1393012812),(614,'van.puti.ca','common','button show','Show','显示','顯示',0,1393013356),(615,'van.puti.ca','common','menu_device','ID Device','网络设备','網絡設備',0,1393212676),(616,'van.puti.ca','common','menu_id_reader','ID Reader','网络读卡器','網絡讀卡器',0,1393213027),(617,'van.puti.ca','common','menu_id_data','ID DATA','刷卡数据','刷卡數據',0,1394598430),(618,'van.puti.ca','common','time range','Time Range','时间段','時間段',0,1393219394),(619,'van.puti.ca','common','kaoqin.site','Reader','读卡器','讀卡器',0,1393290794),(620,'van.puti.ca','common','kaoqin.place','Place','殿堂','殿堂',0,1393290809),(621,'van.puti.ca','common','kaoqin.time','DateTime','刷卡時間','刷卡時間',0,1393225024),(622,'van.puti.ca','common','kaoqin.cishu','Punch Times','刷卡次数','刷卡次數',0,1393226549),(623,'van.puti.ca','common','kaoqin.renshu','People Count','学员人数','學員人數',0,1393226577),(624,'van.puti.ca','common','event_place','Event Place','上课殿堂','上課殿堂',0,1393287749),(625,'van.puti.ca','common','idreader records','ID Reader','网络考勤','網絡考勤',0,1393289607),(626,'van.puti.ca','common','unauth','Unauth','未批准','未批准',0,1410249954),(627,'van.puti.ca','common','menu_id_report','ID Report','刷卡报表','刷卡報表',0,1393368024),(628,'van.puti.ca','common','cancel success','Cancel Success.','取消报名成功.','取消報名成功',0,1393439945),(629,'van.puti.ca','common','menu_apps','Application','应用程序','應用程式',0,1393543480),(630,'van.puti.ca','common','menu_collector','ID Collector','网络ID数据采集','网络ID数据采集',0,1409876638),(631,'van.puti.ca','common','menu_monitor','ID Monitor','网络ID考勤监控','网络ID考勤監視',0,1409876686),(632,'van.puti.ca','common','reply reader','Reply Reader','答复读卡器','回複讀卡器',0,1393656095),(633,'van.puti.ca','common','menu_baishi','Dharma Management','拜师管理','拜師管理',0,1394063464),(634,'van.puti.ca','common','menu_dharma','Dharma Name','拜师法名','拜師法名',0,1394063646),(635,'van.puti.ca','common','print dharma name','Print Dharma Card','打印法名卡','打印法名卡',0,1394067339),(636,'van.puti.ca','common','apply.date','Apply Date','申请日期','申請日期',0,1394068062),(637,'van.puti.ca','common','button close all','Close Event','已完成','已完成',0,1394144303),(638,'van.puti.ca','common','are you sure to publish dharma name?','are you sure to publish Dharma Name?','你确定要公布学员的法名吗?','你確定要公布學員的法名嗎?',0,1394150448),(639,'van.puti.ca','common','dharma date','Dharma Date','拜师日期','拜師日期',0,1394228338),(640,'van.puti.ca','common','los angeles','Los Angeles','洛杉矶','洛杉磯',0,1394427668),(641,'van.puti.ca','common','loc.los angeles','Los Angeles','美國洛杉磯藥師禪院','美國洛杉磯藥師禪院',0,1394428662),(642,'van.puti.ca','common','new york','New York','纽约','紐約',0,1394428607),(643,'van.puti.ca','common','loc.new york','New York','美國鈕約藥師禪院','美國鈕約藥師禪院',0,1394428681),(644,'van.puti.ca','common','san francisco','San Francisco','三藩市','三藩市',0,1394428715),(645,'van.puti.ca','common','loc.san francisco','San Francisco','美國三藩市藥師禪院','美國三藩市藥師禪院',0,1394428754),(646,'van.puti.ca','common','timezone','Timezone','时区','時區',0,1394734445),(647,'van.puti.ca','common','menu_gattend','Group Attendance','小组出勤人数','小組出勤人數',0,1394768286),(648,'van.puti.ca','common','menu_webapps','App Code Download','最新程序代码下载','最新程序代碼下載',0,1410801989),(649,'van.puti.ca','common','button next','Next Step','下一步','下一步',0,1394911952),(650,'van.puti.ca','common','please login with your email','One account. All of Bodhi Meditation.','一个帐号，所有课程','一個帳號，所有課程',0,1395804276),(651,'van.puti.ca','common','forgot password','Forgot Password?','忘记密码？','忘記密碼？',0,1395001820),(652,'van.puti.ca','common','create new account','Create new account？','创建新帐号？','創建新帳號？',0,1395002167),(653,'van.puti.ca','common','login email password error','Email and Password are required.','电子邮件和密码不能为空。','電子郵件和密碼不能為空。',0,1395005520),(654,'van.puti.ca','common','login wrong password','Login with wrong password. if forgot password, please click on \"forgot password\" to reset your password.','密码不正确。如果忘记密码请点击 “忘记密码” 重新设置密码。','密碼不正確。如果忘記密碼，請點擊“忘記密碼”重新設置密碼。',0,1395026245),(655,'van.puti.ca','common','login wrong email','This email has not been registered as member account. please click on \"create new account\".','此电子邮件还没有注册， 请点击下面“创建新帐号”。','此電子郵件還沒有註冊， 請點擊下面“創建新帳號”。',0,1395026282),(656,'van.puti.ca','common','reset password info','We will send you a reset password link to your email','我们会发送重置密码的链接到你的电子邮箱。','我們會發送重置密碼的鏈接到你的電子郵箱。',0,1395026314),(657,'van.puti.ca','common','reset password link has been sent to you','Email has been sent.','密码链接已发到你邮箱','密碼鏈接已發到你郵箱',0,1395026347),(658,'van.puti.ca','common','login successful','Login Successful.','登陆成功','登陸成功',0,1395026380),(659,'van.puti.ca','common','level1 form','Level One Form','一级班报名表','一級班報名表',0,1395026413),(660,'van.puti.ca','common','level2 form','Level Two Form','二级班报名表','二級班報名表',0,1395026444),(661,'van.puti.ca','common','level3 form','Level Three Form','三级班报名表','三級班報名表',0,1395026468),(662,'van.puti.ca','common','email has been used','Email has been used. if you forgot your password, please reset your password.','电子邮件已经被注册过， 如果忘记密码请重置密码。','電子郵件已經被使用，如果忘記密碼請重置密碼。',0,1395032080),(663,'van.puti.ca','common','important account','<span style=\"color:red;\">Important: </span>\nLogin Account','<span style=\"color:red;\">重要: </span>\n登陆帐号','<span style=\"color:red;\">重要: </span>\n登陸帳號',0,1395033552),(664,'van.puti.ca','common','password not match','Password not match with confirm password.','密码和确认密码不相同。','密碼和確認密碼不相同',0,1395034872),(665,'van.puti.ca','common','email has not been registered','Email has not been registered. Please create account first.','电子邮件没有注册过，请创建新帐号。','電子郵件沒有註冊，請創建新帳號。',0,1395716315),(666,'van.puti.ca','common','reset password email body','Dear {0}<br><br>\nPlease use below link to reset your password.<br><br>\n<a href=\"{1}\" style=\"color:blue;font-size:14px;\">Click here to reset your password.<a><br><br><br><br>\nPlease keep this password safe.<br><br>\nThank you and best regards<br><br>\nBodhi Meditation Website Service','亲爱的{0}<br><br>\n请点击以下链接重新设置你的密码.<br><br>\n<a href=\"{1}\" style=\"color:blue;font-size:14px;\">点击这里重新设置密码。<a><br><br><br><br>\n请妥善保管好你的密码<br><br>\n谢谢使用我们的网站<br><br>\n菩提禅修网站管理员','亲爱的{0}<br><br>\n请点击以下链接重新设置你的密码.<br><br>\n<a href=\"{1}\" style=\"color:blue;font-size:14px;\">点击这里重新设置密码。<a><br><br><br><br>\n请妥善保管好你的密码<br><br>\n谢谢使用我们的网站<br><br>\n菩提禅修网站管理员',0,1395716660),(667,'van.puti.ca','common','reset password','Reset Password','重新设置密码','重新設置密碼',0,1395717500),(668,'van.puti.ca','common','reset password link expired','Reset password link has expired!','设置密码的链接已经过期， 请重新申请。','設置密碼的鏈接已經過期， 請重新申請。',0,1395719316),(669,'van.puti.ca','common','password change successful','Password has been changed successful.','密码修改成功','密碼修改成功',0,1395720625),(670,'van.puti.ca','common','upload your photo','Click icon to upload photo<br>Resize photo by mouse scroll & move','点击上传照片图标<br>调整尺寸使用鼠标滚动和移动','點擊上傳照片圖標<br>調整尺寸使用鼠標滾動，按下移動',0,1395864723),(671,'van.puti.ca','common','bailian & san francisco','Bailian','白莲市','白蓮市',0,1419127688),(672,'van.puti.ca','common','camera photo','Take Photo','照相','照相',0,1395958164),(673,'van.puti.ca','common','menu_camera','Camera Photo','照片报名程序','照片報名程式',0,1395974608),(674,'van.puti.ca','common','birth date','Birth Date','生日','生日',0,1396206723),(675,'van.puti.ca','common','month','Month','月','月',0,1396207183),(676,'van.puti.ca','common','bday','Day','日','日',0,1396207229),(677,'van.puti.ca','common','postal','Postal','邮编','郵編',0,1396219140),(678,'van.puti.ca','common','menu_enroll_all','Cross Sites Enroll','跨禅堂报名','跨禅堂报名',0,1396401254),(679,'van.puti.ca','common','other yaoshifo','Yaoshifo-Other','药师殿-其他用途','藥師殿-其他用途',0,1398621332),(680,'van.puti.ca','common','other guanyin','Guanyin-Other','观音殿-其他用途','觀音殿-其他用途',0,1398621306),(681,'van.puti.ca','common','please provide either phone number or cell phone number','Please provide either phone number or cell phone number','请提供电话号码或者手机号码','請提供電話號碼或者手機號碼',0,1396559356),(682,'van.puti.ca','common','shoes.shelf','Shoes','鞋柜','鞋櫃',0,1396575393),(683,'van.puti.ca','common','button shoes','Shoes Shelf','分配鞋柜号码','分配鞋柜號碼',0,1396581641),(684,'van.puti.ca','common','shoes shelf number success','Shoes shelf number has generated successful.','分配鞋柜号码完成','分配鞋柜號碼完成',0,1396582777),(685,'van.puti.ca','common','print shoes shelf number','Print Shoes Shelf Number','打印鞋柜号码','打印鞋櫃號碼',0,1396583096),(686,'van.puti.ca','common','unpaid','Unpaid','未付款','未付款',0,1396640934),(687,'van.puti.ca','common','right class','Right Class','权限等级','權限等級',0,1396921927),(688,'van.puti.ca','common','nametag.vcardsize','Name Card V.Size','学生卡竖直','學生卡豎直',0,1397244865),(689,'van.puti.ca','common','menu_dharma_list','Dharma List','法名辈分','法名辈分',0,1397420429),(690,'van.puti.ca','common','dharma prefix','Dharma Prefix','法名辈分','法名辈分',0,1397421176),(691,'van.puti.ca','common','baishi.site','Location','拜师地点','拜师地点',0,1397421115),(692,'van.puti.ca','common','cert.level31','Level 3 No.','三级证书－无证书号','三級證書－無證書號',0,1397780796),(693,'van.puti.ca','common','site name cn','Site Name','禅堂名称.中文','禪堂名稱.中文',0,1397833929),(694,'van.puti.ca','common','site name en','Site Name(EN)','禅堂名称.英文','禪堂名稱.英文',0,1397833976),(695,'van.puti.ca','common','phone cn','Phone','电话.中文','電話.中文',0,1397834024),(696,'van.puti.ca','common','phone en','Phone(EN)','电话.英文','电话.英文',0,1397834061),(697,'van.puti.ca','common','doc no','Doc No.','档案号','檔案號',0,1397925990),(698,'van.puti.ca','common','menu_archive','Archive','存档管理','存檔管理',0,1397927735),(699,'van.puti.ca','common','print name','Print Name','打印姓名','打印姓名',0,1397956697),(700,'van.puti.ca','common','people attend rate','People Rate','人数出席率','人數出席率',0,1398025025),(701,'van.puti.ca','common','cert.site.en.vancouver','The Canada Bodhi Dharma Society','The Canada Bodhi Dharma Society','The Canada Bodhi Dharma Society',0,1398127228),(702,'van.puti.ca','common','cert.site.cn.vancouver','加拿大菩提法门协会','加拿大菩提法门协会','加拿大菩提法门协会',0,1398127268),(703,'van.puti.ca','common','cert.site.en.taibei','The Taipei Bodhi Dharma Society','The Taipei Bodhi Dharma Society','The Taipei Bodhi Dharma Society',0,1398318079),(704,'van.puti.ca','common','cert.site.cn.taibei','台北菩提法门协会','台北菩提法门协会','台北菩提法门协会',0,1398318080),(705,'van.puti.ca','common','cert.level11','Level One-No Signature','一级证书-无签名','一級證書-無簽名',0,1398128549),(706,'van.puti.ca','common','bodhi all class','Bodhi Meditation Classes','菩提禅修所有课程','菩提禪修所有課程',0,1398386723),(707,'van.puti.ca','common','main menu','MAIN MENU','主菜单','主菜單',0,1398211737),(708,'van.puti.ca','common','malaysia','Malaysia','马来西亚','馬來西亞',0,1398222589),(709,'van.puti.ca','common','loc.malaysia','馬來西亞藥師禪院','馬來西亞藥師禪院','馬來西亞藥師禪院',0,1398222742),(710,'van.puti.ca','common','cert.site.en.malaysia','The Malaysia Bodhi Dharma Society','The Malaysia Bodhi Dharma Society','The Malaysia Bodhi Dharma Society',0,1398223229),(711,'van.puti.ca','common','cert.site.cn.malaysia','馬來西亞菩提法门协会','馬來西亞菩提法门协会','馬來西亞菩提法门协会',0,1398223265),(712,'van.puti.ca','common','cert.site.cn.los angeles','洛杉机菩提法门协会','洛杉机菩提法门协会','洛杉机菩提法门协会',0,1398362852),(713,'van.puti.ca','common','cert.site.en.los angeles','American Int\'l Puti(Bodhi) Dharma Society','American Int\'l Puti(Bodhi) Dharma Society','American Int\'l Puti(Bodhi) Dharma Society',0,1398363310),(714,'van.puti.ca','common','member enter date','Member Date','入会日期','入会日期',0,1398365625),(715,'van.puti.ca','common','memo notes','Notes','备注','備註',0,1398365802),(716,'van.puti.ca','common','dharma pinyin','Pinyin','拼音','拼音',0,1398497419),(717,'van.puti.ca','common','other certificate id cover','发菩提心 行菩萨道','发菩提心 行菩萨道','發菩提心 行菩薩道',0,1398736375),(718,'van.puti.ca','common','cert.level12','Retreat Certificate','闭关班证书','閉關班證書',0,1400375612),(719,'van.puti.ca','common','nametag.bigsize1','NameTag-Only Dharma','大尺寸学生法名卡','大尺寸学生法名卡',0,1400978401),(720,'van.puti.ca','common','other teaching','Bodhi Meditation Other Classes','菩提禅修其他课程','菩提禅修其他课程',0,1410070979),(721,'van.puti.ca','common','cert.site.en.bailian & san francisco','American Int’l Puti(Bodhi) Dharma Society – San Francisco','American Int’l Puti(Bodhi) Dharma Society – San Francisco','American Int’l Puti(Bodhi) Dharma Society – San Francisco',0,1401644131),(722,'van.puti.ca','common','cert.site.cn.bailian & san francisco','美國國際菩提法門協會 - 白蓮禪堂','美國國際菩提法門協會 - 白蓮禪堂','美國國際菩提法門協會 - 白蓮禪堂',0,1401644159),(723,'van.puti.ca','common','empty search error','Please enter a search keyword.','请输入搜索关键字。','请输入搜索關键字。',0,1402801558),(724,'van.puti.ca','common','nametag.cardsize1','Name Card Size-Dharma','名片尺寸法名卡','名片尺寸法名卡',0,1405221725),(725,'van.puti.ca','common','secret teaching','Secret Teaching','高级密法課程','高级密法課程',0,1410071048),(726,'van.puti.ca','common','pay free','Free Class','免费课程','免費課程',0,1409805600),(727,'van.puti.ca','common','menu_service','ID Servie','服务器WCF服务','服务器WCF服务',0,1409876792),(728,'van.puti.ca','common','internal group','Internal','内部','内部',0,1409984083),(729,'van.puti.ca','common','bodhi dharma name','Bodhi Dharma Name','菩提法门拜师','菩提法門拜師',0,1410071371),(730,'van.puti.ca','common','trial_exp','Trial Expired','试听过期','試聽過期',0,1410245550),(731,'van.puti.ca','common','invalid member','Invalid','无效学员','無效學員',0,1410245602),(732,'van.puti.ca','common','unenroll','Unenroll','未报名','未报名',0,1410250183),(733,'van.puti.ca','common','checkin_state','State','状态','状态',0,1410669815),(734,'van.puti.ca','common','history summary','Summary','历史统计','歷史統計',0,1412917138),(735,'van.puti.ca','common','mix teaching','Bodhi Meditation','菩提禅修课程','菩提禅修课程',0,1413344856),(736,'van.puti.ca','common','preferred language','Main','母语','母語',0,1422907310),(737,'van.puti.ca','common','lang.mandarin','Mandarin','国语','国語',0,1414198379),(738,'van.puti.ca','common','lang.cantonese','Cantonese','粵語','粵語',0,1414198461),(739,'van.puti.ca','common','lang.english','English','英語','英語',0,1414198512),(740,'van.puti.ca','common','short.lang','Lang.','母语','母語',0,1414198550),(741,'van.puti.ca','common','lang.spanish','Spanish','西班牙语','西班牙語',0,1414198624),(742,'van.puti.ca','common','lang.vietnamese','Vietnamese','越南语','越南语',0,1414198726),(743,'van.puti.ca','common','lang.others','Others','其他','其他',0,1414198798),(744,'van.puti.ca','common','email subscription agreement','I hereby agree to receive Bodhi Meditation Email about any events.','我同意接收由菩提禅修发出的关于菩提禅修课程和各种信息的电子邮件.\n\n在此点击同意，即表示接受此条款。','我同意接收由菩提禅修发出的关于菩提禅修课程和各种信息的电子邮件.\n\n在此点击同意，即表示接受此条款。',0,1414198834),(745,'van.puti.ca','common','email.subscribe','Subscribe','订阅','订阅',0,1414198867),(746,'van.puti.ca','common','email.unsubscribe','UnSubscribe','不订阅','不订阅',0,1414198900),(747,'van.puti.ca','common','email subscription','Email.Sub','邮件订阅','邮件订阅',0,1423072836),(748,'van.puti.ca','common','language ability','Ability','会说','会说',0,1422907928),(749,'van.puti.ca','common','form.event','Event','课程','课程',0,1414198989),(750,'van.puti.ca','common','lang.ability','Lang.Ability','语言能力','语言能力',0,1414199015),(751,'van.puti.ca','common','menu_email_list','Email List','邮件清单','邮件清单',0,1414260611),(752,'van.puti.ca','common','remove from email list','remove from email list','从邮件列表移除','從郵件列表移除',0,1414262303),(753,'van.puti.ca','common','add to email list','add to email list','添加到邮件列表','添加到郵件列表',0,1414262330),(754,'van.puti.ca','common','add to email list success','Add to email list success.','添加到邮件列表成功','添加到郵件列表成功',0,1414264724),(755,'van.puti.ca','common','remove from email list success','Remove from email list sucess','从邮件列表移除成功','從郵件列表移除成功',0,1414264979),(756,'van.puti.ca','common','lang.malay','Malay','马来语','馬來語',0,1414274209),(757,'van.puti.ca','common','button delete all','Delete All','删除所有','删除所有',0,1414795895),(758,'van.puti.ca','common','site name for certification','Site Name for Cetification','禅堂名称-证书使用','禅堂名称-证书使用',0,1414795926),(759,'van.puti.ca','common','site name for dharma','Site Name for Dharma Card','禅堂名称-法名卡使用','禅堂名称-法名卡使用',0,1414795957),(760,'van.puti.ca','common','last name first name','Subname First','姓氏在前','姓氏在前',0,1414978092),(761,'van.puti.ca','common','event dashboard','All Activities','所有活动','所有活动',0,1415487737),(762,'van.puti.ca','common','cert.photo','Print Photo','照片打印','照片打印',0,1415753261),(763,'van.puti.ca','common','yangyuantang','YYT','养元堂','養元堂',0,1416174959),(765,'van.puti.ca','common','menu_absent','Attend Adjust','考勤补漏','考勤補漏',0,1416379664),(764,'van.puti.ca','common','place unknown','Place Unknown','地点待定','地點待定',0,1416177405),(766,'van.puti.ca','common','menu_leave','Apply Leave','学员请假','學員請假',0,1416379092),(767,'van.puti.ca','common','total checkin','Require','全勤','全勤',0,1416379366),(768,'van.puti.ca','common','total leave','Leave','请假','請假',0,1416379365),(769,'van.puti.ca','common','total attend','Attend','出席','出席',0,1416379407),(770,'van.puti.ca','common','new people','New','新人','新人',0,1416460647),(771,'van.puti.ca','common','class date','Class Date','课程日期','課程日期',0,1416465360),(772,'van.puti.ca','common','nametag.bigsize2','Name Card 10cm X 7cm','学生证10cm X 7cm','學生證10cm X 7cm',0,1416702256),(773,'van.puti.ca','common','nametag.vipbsize1','VIP Big Staff','贵宾大工作证','貴賓大工作證',0,1416716735),(774,'van.puti.ca','common','nametag.vipsize1','VIP Mini Staff','贵宾小工作證','貴賓小工作證',0,1416716798),(775,'van.puti.ca','common','staff nametag','STAFF','工作证','工作證',0,1416717285),(776,'van.puti.ca','common','nametag.bigsize11','NameTag-Dharma Staff','大尺寸法名工作证','大尺寸法名工作证',0,1418974701),(777,'van.puti.ca','common','nametag.bigsize22','Staff Card 10cm X 7cm','工作证10cm X 7cm','工作证10cm X 7cm',0,1418974816),(778,'van.puti.ca','common','ungroup label print','Student Card Print','未分组学生证打印','未分組學生證打印',0,1419051070),(779,'van.puti.ca','common','alhambra','Alhambra','罗汉','罗汉',0,1419715312),(780,'van.puti.ca','common','menu_basic','Basic Info','基本信息','基本信息',0,1421886063),(781,'van.puti.ca','common','basic.lang_key','Keyword','语言关键字','语言关键字',0,1421886561),(782,'van.puti.ca','common','basic.name_cn','Title.CN','中文说明','中文说明',0,1421886701),(783,'van.puti.ca','common','basic.name_en','Title.EN','英文说明','英文说明',0,1421886702),(784,'van.puti.ca','common','basic.table_name','Table Name','表格名称','表格名称',0,1421886728),(785,'van.puti.ca','common','basic.basic information','Basic Information','基本信息','基本信息',0,1421887169),(786,'van.puti.ca','common','basic.description','Description','用途说明','用途说明',0,1421950043),(787,'van.puti.ca','common','menu_sevice','Volunteer','义工服务','義工服務',0,1422081424),(788,'van.puti.ca','common','menu_service_basic','Basic','基本信息','基本信息',0,1422081544),(789,'van.puti.ca','common','menu_service_depart','Organization','组织架构','组织架构',0,1422422979),(790,'van.puti.ca','common','menu_service_table','Attribute','基本属性','基本屬性',0,1422082193),(791,'van.puti.ca','common','menu_service_volunteer','Volunteer','义工信息','義工信息',0,1422082347),(792,'van.puti.ca','common','menu_service_depvol','Department','部门义工','部門義工',0,1422082488),(793,'van.puti.ca','common','delete success','Delete Success','删除成功','删除成功',0,1422084820),(794,'van.puti.ca','common','basic.basic category','Category Table','信息类别','信息类别',0,1422298551),(795,'van.puti.ca','common','basic.desc_en','Desc.EN','英文描述','英文描述',0,1422299164),(796,'van.puti.ca','common','basic.desc_cn','Desc.CN','中文描述','中文描述',0,1422299195),(797,'van.puti.ca','common','basic.show','show','显示','显示',0,1422300214),(798,'van.puti.ca','common','basic.subject_en','Subject.EN','英文主题','英文主题',0,1422300907),(799,'van.puti.ca','common','basic.subject_cn','Subject.CN','中文主题','中文主题',0,1422300936),(800,'van.puti.ca','common','puti.organization','Organization','部门架构','部门架构',0,1422314816),(801,'van.puti.ca','common','puti.organization structure','Puti Organization Structure','菩提部门结构','菩提部门结构',0,1422318392),(802,'van.puti.ca','common','puti.department.detail','Department Detail','部门信息','部门信息',0,1422337201),(803,'van.puti.ca','common','puti.depart.title_en','Name.English','英文名称','英文名称',0,1422339055),(804,'van.puti.ca','common','puti.depart.title_cn','Name.Chinese','中文名称','中文名称',0,1422339122),(805,'van.puti.ca','common','puti.depart.desc_en','Desc.EN','英文说明','英文说明',0,1422339197),(806,'van.puti.ca','common','puti.depart.desc_cn','Desc.CN','中文说明','中文说明',0,1422339266),(807,'van.puti.ca','common','puti.depart.lang_key','Lang.Keyword','语言关键字','语言关键字',0,1422414865),(808,'van.puti.ca','common','the record can not be deleted for children','This record can not be deleted, please delete child item first.','此记录有子项目不能删除,请先删除子项目','此记录有子项目不能删除,请先删除子项目',0,1422422745),(809,'van.puti.ca','common','click to add new record','Click to add new record','点击新增记录','點擊新增記錄',0,1422425970),(810,'van.puti.ca','common','menu_site_depart','Site Department','禅堂部门','禪堂部門',0,1422426454),(811,'van.puti.ca','common','religion','Religion','宗教','宗教',0,1422664661),(812,'van.puti.ca','common','view volunteer details','View Volunteer Details','查看义工明细','查看義工明細',0,1422598084),(813,'van.puti.ca','common','view member details','View Member Details','查看学员信息','查看学员信息',0,1422598117),(814,'van.puti.ca','common','print volunteer form','Print Volunteer Form','打印义工申请表','打印義工申請表',0,1422598161),(815,'van.puti.ca','common','volunteer department','Volunteer Department','义工部门','義工部门',0,1422647408),(816,'van.puti.ca','common','p.other_info','Others','其他信息','其他信息',0,1422663077),(817,'van.puti.ca','common','member.professional','Professional','技术专长','技術專長',0,1422688386),(818,'van.puti.ca','common','current_position','Cur.Position','目前职业','目前职业',0,1422667056),(819,'van.puti.ca','common','past_position','Past.Position','过往职业','过往职业',0,1422667090),(820,'van.puti.ca','common','member.degree','Degree','学历','学历',0,1422667268),(821,'van.puti.ca','common','member.professional_other','Other','其他专长','其他專長',0,1422688948),(822,'van.puti.ca','common','member.health','Health Condition','健康狀況','健康狀況',0,1422689445),(823,'van.puti.ca','common','member.health_other','Other','其他疾病','其他疾病',0,1422689495),(824,'van.puti.ca','common','member.resume','Resume','个人简历','個人簡歷',0,1422689694),(825,'van.puti.ca','common','member.will_depart','Expected Department','部门意向','部门意向',0,1422690287),(826,'van.puti.ca','common','member.current_depart','Approval Department','批准部门','批准部门',0,1422690348),(827,'van.puti.ca','common','member.edit','Edit','修改','修改',0,1422690757),(828,'van.puti.ca','common','member.select_option','Other Option','其他选项','其他选项',0,1422691290),(829,'van.puti.ca','common','member.memo','Notes','备注','備註',0,1422853232),(830,'van.puti.ca','common','volunteer.type','Vol.Type','义工类型','义工类型',0,1422901673),(831,'van.puti.ca','common','volunteer.available_time','Available Time','服务时段','服务时段',0,1422920783),(832,'van.puti.ca','common','volunteer.schedule.type','Type','类型','类型',0,1422922765),(833,'van.puti.ca','common','volunteer.schedule.type.daily','Daily','每天','每天',0,1422921893),(834,'van.puti.ca','common','volunteer.schedule.type.weekly','Weekly','每周','每周',0,1422921913),(835,'van.puti.ca','common','volunteer.schedule.type.monthly','Monthly','每月','每月',0,1422921934),(836,'van.puti.ca','common','volunteer.schedule.date','Date','日期','服务日期',0,1422922740),(837,'van.puti.ca','common','volunteer.schedule.time','Time','时间','服务时间',0,1422922741),(838,'van.puti.ca','common','start time','Start Time','开始时间','开始时间',0,1422999621),(839,'van.puti.ca','common','end time','End Time','结束时间','结束时间',0,1422999646),(840,'van.puti.ca','common','weekday.mon','Mon','周一','周一',0,1423001286),(841,'van.puti.ca','common','weekday.tue','Tue','周二','周二',0,1423001285),(842,'van.puti.ca','common','weekday.wed','Wed','周三','周三',0,1423001284),(843,'van.puti.ca','common','weekday.thur','Thur','周四','周四',0,1423001283),(844,'van.puti.ca','common','weekday.fri','Fri','周五','周五',0,1423001283),(845,'van.puti.ca','common','weekday.sat','Sat','周六','周六',0,1423001282),(846,'van.puti.ca','common','weekday.sun','Sun','周日','周日',0,1423001281),(847,'van.puti.ca','common','add success','Add Success','新增成功','新增成功',0,1423008799),(848,'van.puti.ca','common','menu_volunteer_search','Search','义工查询','义工查询',0,1423072369),(849,'van.puti.ca','common','member.position','Position','职业','职业',0,1423072792),(850,'van.puti.ca','common','service date','Sevice Date','服务日期','服务日期',0,1423075581),(851,'van.puti.ca','common','member.regdate','Reg.Date','学员登记','学员登记',0,1423078061),(852,'van.puti.ca','common','volunteer.select.date','Service Date','所选日期','所选日期',0,1423079515),(853,'van.puti.ca','common','everyday','Every Day','每天','每天',0,1423086618),(854,'van.puti.ca','common','volunteer.regdate','Vol.Date','义工登记','义工登记',0,1423092516),(855,'van.puti.ca','common','button.add.subitem','Add Sub-item','添加子項目','添加子項目',0,1423109406);

/*Table structure for table `website_session` */

DROP TABLE IF EXISTS `website_session`;

CREATE TABLE `website_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `session_id` varchar(63) DEFAULT NULL,
  `ip_address` varchar(31) DEFAULT NULL,
  `login_time` bigint(20) DEFAULT NULL,
  `last_updated` bigint(20) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `website_session` */

/* Procedure structure for procedure `sp_vol_pressional` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_vol_pressional` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_vol_pressional`(in tname  varchar(255) )
BEGIN
	SELECT * FROM website_basic_table where filter = tname;
    END */$$
DELIMITER ;

/*Table structure for table `vw_bas_degree` */

DROP TABLE IF EXISTS `vw_bas_degree`;

/*!50001 DROP VIEW IF EXISTS `vw_bas_degree` */;
/*!50001 DROP TABLE IF EXISTS `vw_bas_degree` */;

/*!50001 CREATE TABLE  `vw_bas_degree`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_bas_health` */

DROP TABLE IF EXISTS `vw_bas_health`;

/*!50001 DROP VIEW IF EXISTS `vw_bas_health` */;
/*!50001 DROP TABLE IF EXISTS `vw_bas_health` */;

/*!50001 CREATE TABLE  `vw_bas_health`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_bas_language` */

DROP TABLE IF EXISTS `vw_bas_language`;

/*!50001 DROP VIEW IF EXISTS `vw_bas_language` */;
/*!50001 DROP TABLE IF EXISTS `vw_bas_language` */;

/*!50001 CREATE TABLE  `vw_bas_language`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_bas_professional` */

DROP TABLE IF EXISTS `vw_bas_professional`;

/*!50001 DROP VIEW IF EXISTS `vw_bas_professional` */;
/*!50001 DROP TABLE IF EXISTS `vw_bas_professional` */;

/*!50001 CREATE TABLE  `vw_bas_professional`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_bas_religion` */

DROP TABLE IF EXISTS `vw_bas_religion`;

/*!50001 DROP VIEW IF EXISTS `vw_bas_religion` */;
/*!50001 DROP TABLE IF EXISTS `vw_bas_religion` */;

/*!50001 CREATE TABLE  `vw_bas_religion`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_bas_type` */

DROP TABLE IF EXISTS `vw_bas_type`;

/*!50001 DROP VIEW IF EXISTS `vw_bas_type` */;
/*!50001 DROP TABLE IF EXISTS `vw_bas_type` */;

/*!50001 CREATE TABLE  `vw_bas_type`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_vol_degree` */

DROP TABLE IF EXISTS `vw_vol_degree`;

/*!50001 DROP VIEW IF EXISTS `vw_vol_degree` */;
/*!50001 DROP TABLE IF EXISTS `vw_vol_degree` */;

/*!50001 CREATE TABLE  `vw_vol_degree`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_vol_health` */

DROP TABLE IF EXISTS `vw_vol_health`;

/*!50001 DROP VIEW IF EXISTS `vw_vol_health` */;
/*!50001 DROP TABLE IF EXISTS `vw_vol_health` */;

/*!50001 CREATE TABLE  `vw_vol_health`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_vol_language` */

DROP TABLE IF EXISTS `vw_vol_language`;

/*!50001 DROP VIEW IF EXISTS `vw_vol_language` */;
/*!50001 DROP TABLE IF EXISTS `vw_vol_language` */;

/*!50001 CREATE TABLE  `vw_vol_language`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_vol_professional` */

DROP TABLE IF EXISTS `vw_vol_professional`;

/*!50001 DROP VIEW IF EXISTS `vw_vol_professional` */;
/*!50001 DROP TABLE IF EXISTS `vw_vol_professional` */;

/*!50001 CREATE TABLE  `vw_vol_professional`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_vol_religion` */

DROP TABLE IF EXISTS `vw_vol_religion`;

/*!50001 DROP VIEW IF EXISTS `vw_vol_religion` */;
/*!50001 DROP TABLE IF EXISTS `vw_vol_religion` */;

/*!50001 CREATE TABLE  `vw_vol_religion`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*Table structure for table `vw_vol_type` */

DROP TABLE IF EXISTS `vw_vol_type`;

/*!50001 DROP VIEW IF EXISTS `vw_vol_type` */;
/*!50001 DROP TABLE IF EXISTS `vw_vol_type` */;

/*!50001 CREATE TABLE  `vw_vol_type`(
 `id` int(11) NOT NULL  default '0' ,
 `filter` varchar(255) NULL ,
 `title_en` varchar(255) NULL ,
 `title_cn` varchar(255) NULL ,
 `desc_en` varchar(1023) NULL ,
 `desc_cn` varchar(1023) NULL ,
 `lang_key` varchar(255) NULL ,
 `number1` int(11) NULL  default '0' ,
 `string1` varchar(255) NULL ,
 `float1` decimal(11,2) NULL  default '0.00' ,
 `status` tinyint(1) NULL  default '1' ,
 `deleted` tinyint(1) NULL  default '0' ,
 `created_time` bigint(20) NULL  default '0' ,
 `last_updated` bigint(20) NULL  default '0' ,
 `sn` int(11) NULL  default '0' 
)*/;

/*View structure for view vw_bas_degree */

/*!50001 DROP TABLE IF EXISTS `vw_bas_degree` */;
/*!50001 DROP VIEW IF EXISTS `vw_bas_degree` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_bas_degree` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where (`website_basic_table`.`filter` = 'member.education') order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_bas_health */

/*!50001 DROP TABLE IF EXISTS `vw_bas_health` */;
/*!50001 DROP VIEW IF EXISTS `vw_bas_health` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_bas_health` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where (`website_basic_table`.`filter` = 'health.status') order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_bas_language */

/*!50001 DROP TABLE IF EXISTS `vw_bas_language` */;
/*!50001 DROP VIEW IF EXISTS `vw_bas_language` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_bas_language` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where (`website_basic_table`.`filter` = 'member.language') order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_bas_professional */

/*!50001 DROP TABLE IF EXISTS `vw_bas_professional` */;
/*!50001 DROP VIEW IF EXISTS `vw_bas_professional` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_bas_professional` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where (`website_basic_table`.`filter` = 'volunteer.professional') order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_bas_religion */

/*!50001 DROP TABLE IF EXISTS `vw_bas_religion` */;
/*!50001 DROP VIEW IF EXISTS `vw_bas_religion` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_bas_religion` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where (`website_basic_table`.`filter` = 'member.religion') order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_bas_type */

/*!50001 DROP TABLE IF EXISTS `vw_bas_type` */;
/*!50001 DROP VIEW IF EXISTS `vw_bas_type` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_bas_type` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where (`website_basic_table`.`filter` = 'volunteer.type') order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_vol_degree */

/*!50001 DROP TABLE IF EXISTS `vw_vol_degree` */;
/*!50001 DROP VIEW IF EXISTS `vw_vol_degree` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vol_degree` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where ((`website_basic_table`.`deleted` <> 1) and (`website_basic_table`.`status` = 1) and (`website_basic_table`.`filter` = 'member.education')) order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_vol_health */

/*!50001 DROP TABLE IF EXISTS `vw_vol_health` */;
/*!50001 DROP VIEW IF EXISTS `vw_vol_health` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vol_health` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where ((`website_basic_table`.`deleted` <> 1) and (`website_basic_table`.`status` = 1) and (`website_basic_table`.`filter` = 'health.status')) order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_vol_language */

/*!50001 DROP TABLE IF EXISTS `vw_vol_language` */;
/*!50001 DROP VIEW IF EXISTS `vw_vol_language` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vol_language` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where ((`website_basic_table`.`deleted` <> 1) and (`website_basic_table`.`status` = 1) and (`website_basic_table`.`filter` = 'member.language')) order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_vol_professional */

/*!50001 DROP TABLE IF EXISTS `vw_vol_professional` */;
/*!50001 DROP VIEW IF EXISTS `vw_vol_professional` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vol_professional` AS select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where ((`website_basic_table`.`filter` = 'volunteer.professional') and (`website_basic_table`.`status` = 1) and (`website_basic_table`.`deleted` <> 1)) order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time` */;

/*View structure for view vw_vol_religion */

/*!50001 DROP TABLE IF EXISTS `vw_vol_religion` */;
/*!50001 DROP VIEW IF EXISTS `vw_vol_religion` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vol_religion` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where ((`website_basic_table`.`deleted` <> 1) and (`website_basic_table`.`status` = 1) and (`website_basic_table`.`filter` = 'member.religion')) order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*View structure for view vw_vol_type */

/*!50001 DROP TABLE IF EXISTS `vw_vol_type` */;
/*!50001 DROP VIEW IF EXISTS `vw_vol_type` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_vol_type` AS (select `website_basic_table`.`id` AS `id`,`website_basic_table`.`filter` AS `filter`,`website_basic_table`.`title_en` AS `title_en`,`website_basic_table`.`title_cn` AS `title_cn`,`website_basic_table`.`desc_en` AS `desc_en`,`website_basic_table`.`desc_cn` AS `desc_cn`,`website_basic_table`.`lang_key` AS `lang_key`,`website_basic_table`.`number1` AS `number1`,`website_basic_table`.`string1` AS `string1`,`website_basic_table`.`float1` AS `float1`,`website_basic_table`.`status` AS `status`,`website_basic_table`.`deleted` AS `deleted`,`website_basic_table`.`created_time` AS `created_time`,`website_basic_table`.`last_updated` AS `last_updated`,`website_basic_table`.`sn` AS `sn` from `website_basic_table` where ((`website_basic_table`.`deleted` <> 1) and (`website_basic_table`.`status` = 1) and (`website_basic_table`.`filter` = 'volunteer.type')) order by `website_basic_table`.`sn` desc,`website_basic_table`.`created_time`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
