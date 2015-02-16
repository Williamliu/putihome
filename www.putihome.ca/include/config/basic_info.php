<?php
$emailArr = array();
$emailArr[0]["en"]["subject"] = "Puti Mediation - {event_title}";
$emailArr[0]["en"]["content"] = "Dear {first_name} {last_name}<br>
<br>
Please click here to confirm: <a href='{link}'>{link}</a>
<br><br>

Welcome to our meditation class,  please confirm your information showed as below:<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>First Name:</td><td>{first_name}</td></tr>
	<tr><td style='white-space:nowrap;'>Last Name:</td><td>{last_name}</td></tr>
	<tr><td style='white-space:nowrap;'>Dharma Name</td><td>{dharma_name}</td></tr>
	<tr><td style='white-space:nowrap;'>Email</td><td>{email}</td></tr>
	<tr><td style='white-space:nowrap;'>phone</td><td>{phone}</td></tr>
	<tr><td style='white-space:nowrap;'>cell</td><td>{cell}</td></tr>
</table>	
<br>
You have signed in our class.<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>Class Name:</td><td>{event_title}</td></tr>
	<tr><td style='white-space:nowrap;'><b>Group</b></td><td align='center'><span style='color:red;font-weight:bold;font-size:2em;'>{group}</span></td></tr>
</table>	
<br><br>
Class Detail:<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>Class Name:</td><td>{event_title}</td></tr>
	<tr><td style='white-space:nowrap;'>Start Date:</td><td>{start_date}</td></tr>
	<tr><td style='white-space:nowrap;'>End Date:</td><td>{end_date}</td></tr>
	<tr><td style='white-space:nowrap;'>Class Content:</td><td>{event_desc}</td></tr>
</table>
<br><br>
If you have any problem, please call our front desk service:<br>
Tel: (778) 551-1068 (English)  (604) 276-2885(Mandarin)<br>
Email: englishinfo@putimeditation.ca<br>
Address:150-7740 Alderbridge Way, Richmond, BC, V6X 2A3<br> 	
<br><br>
Thank you and have a good day.<br>
Vancouver Puti Meditaion.
<br>
";

$emailArr[1]["en"]["subject"] = "Puti Mediation";
$emailArr[1]["en"]["content"] = "Dear {first_name} {last_name}<br>
<br>

<br><br>
If you have any problem, please call our front desk service:<br>
Tel: (778) 551-1068 (English)  (604) 276-2885(Mandarin)<br>
Email: englishinfo@putimeditation.ca<br>
Address:150-7740 Alderbridge Way, Richmond, BC, V6X 2A3<br> 	
<br>
Best wishes to you.<br>
Vancouver Puti Meditaion.
<br>
";


$emailArr[0]["cn"]["subject"] = "菩提禅修 - {event_title}";
$emailArr[0]["cn"]["content"] = "亲爱的 {first_name} {last_name}<br>
<br>
请点击链接，告诉我们您已经知道了以下内容: <a href='{link}'>{link}</a>
<br><br>

欢迎您报名参加我们的菩提禅修课程，以下是你的确认信息:<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>名字:</td><td>{first_name}</td></tr>
	<tr><td style='white-space:nowrap;'>姓氏:</td><td>{last_name}</td></tr>
	<tr><td style='white-space:nowrap;'>法名</td><td>{dharma_name}</td></tr>
	<tr><td style='white-space:nowrap;'>别名</td><td>{alias}</td></tr>
	<tr><td style='white-space:nowrap;'>电子邮件</td><td>{email}</td></tr>
	<tr><td style='white-space:nowrap;'>电话</td><td>{phone}</td></tr>
	<tr><td style='white-space:nowrap;'>手提</td><td>{cell}</td></tr>
</table>	
<br>
您报名参加我们的菩提禅修班的信息如下：<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>课程名称:</td><td>{event_title}</td></tr>
	<tr><td style='white-space:nowrap;'><b>所在小组</b></td><td align='center'><span style='color:red;font-weight:bold;font-size:2em;'>{group}</span></td></tr>
</table>	
<br><br>
课程详细信息如下:<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>课程名称:</td><td>{event_title}</td></tr>
	<tr><td style='white-space:nowrap;'>开始日期:</td><td>{start_date}</td></tr>
	<tr><td style='white-space:nowrap;'>结束日期:</td><td>{end_date}</td></tr>
	<tr><td style='white-space:nowrap;'>课程内容:</td><td>{event_desc}</td></tr>
</table>
<br><br>
如果你有任何疑问， 请致电我们前台咨询:<br>
电话: (778) 551-1068 (英语)  (604) 276-2885(中文)<br>
Email: englishinfo@putimeditation.ca<br>
地址:150-7740 Alderbridge Way, Richmond, BC, V6X 2A3<br> 	
<br><br>
祝你身体健康，吉祥如意.<br>
温哥华菩提禅修.
<br>
";


$emailArr[0]["tw"]["subject"] = "菩提禅修 - {event_title}";
$emailArr[0]["tw"]["content"] = "亲爱的 {first_name} {last_name}<br>
<br>
请点击链接，告诉我们您已经知道了以下内容: <a href='{link}'>{link}</a>
<br><br>

欢迎您报名参加我们的菩提禅修课程，以下是你的确认信息:<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>名字:</td><td>{first_name}</td></tr>
	<tr><td style='white-space:nowrap;'>姓氏:</td><td>{last_name}</td></tr>
	<tr><td style='white-space:nowrap;'>法名</td><td>{dharma_name}</td></tr>
	<tr><td style='white-space:nowrap;'>别名</td><td>{alias}</td></tr>
	<tr><td style='white-space:nowrap;'>电子邮件</td><td>{email}</td></tr>
	<tr><td style='white-space:nowrap;'>电话</td><td>{phone}</td></tr>
	<tr><td style='white-space:nowrap;'>手提</td><td>{cell}</td></tr>
</table>	
<br>
您报名参加我们的菩提禅修班的信息如下：<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>课程名称:</td><td>{event_title}</td></tr>
	<tr><td style='white-space:nowrap;'><b>所在小组</b></td><td align='center'><span style='color:red;font-weight:bold;font-size:2em;'>{group}</span></td></tr>
</table>	
<br><br>
课程详细信息如下:<br>
<table border='1' cellpadding='2' cellspacing='0'>
	<tr><td style='white-space:nowrap;'>课程名称:</td><td>{event_title}</td></tr>
	<tr><td style='white-space:nowrap;'>开始日期:</td><td>{start_date}</td></tr>
	<tr><td style='white-space:nowrap;'>结束日期:</td><td>{end_date}</td></tr>
	<tr><td style='white-space:nowrap;'>课程内容:</td><td>{event_desc}</td></tr>
</table>
<br><br>
如果你有任何疑问， 请致电我们前台咨询:<br>
电话: (778) 551-1068 (英语)  (604) 276-2885(中文)<br>
Email: englishinfo@putimeditation.ca<br>
地址:150-7740 Alderbridge Way, Richmond, BC, V6X 2A3<br> 	
<br><br>
祝你身体健康，吉祥如意.<br>
温哥华菩提禅修.
<br>
";


$emailArr[1]["cn"]["subject"] = "菩提禅修";
$emailArr[1]["cn"]["content"] = "亲爱的 {first_name} {last_name}<br>
<br>

<br><br>
如果你有任何疑问， 请致电我们前台咨询:<br>
电话: (778) 551-1068 (英语)  (604) 276-2885(中文)<br>
Email: englishinfo@putimeditation.ca<br>
地址:150-7740 Alderbridge Way, Richmond, BC, V6X 2A3<br> 	
<br><br>
祝你身体健康，吉祥如意.<br>
温哥华菩提禅修.
<br>
";

$emailArr[1]["tw"]["subject"] = "菩提禅修";
$emailArr[1]["tw"]["content"] = "亲爱的 {first_name} {last_name}<br>
<br>

<br><br>
如果你有任何疑问， 请致电我们前台咨询:<br>
电话: (778) 551-1068 (英语)  (604) 276-2885(中文)<br>
Email: englishinfo@putimeditation.ca<br>
地址:150-7740 Alderbridge Way, Richmond, BC, V6X 2A3<br> 	
<br><br>
祝你身体健康，吉祥如意.<br>
温哥华菩提禅修.
<br>
";

?>