<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../code/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.5.custom.min.js"></script>
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.dbQuery.js"></script>
	<script type="text/javascript" 	src="/js/js.lwh.common.js"></script>
	<script type="text/javascript" 	src="/js/js.lwh.validate.js"></script>
	
    <link type="text/css" 			href="../themes/base/jquery.ui.all.css" rel="stylesheet" />
    
<script language="javascript" type="text/javascript">
	function getData() {
			var obj = {
						fname: 	$("#first_name").getValue(),
						lname:	$("#last_name").getValue(),
						email:	$("#email").getValue(),
						horbby:	$(":checkbox[ename='horbby'],:checkbox[name='horbby']").getValue(),
						sex: 	$(":radio[name='gender']").getValue(),
						notes:	$(".comment").getValue(),
						date:	$("#datetime").getValue()
			}
			return obj;
	}
	
	function verify_date() {
		gError.clear();
		$("#first_name").validate();
		$("#last_name").validate();
		$("#email").validate();
		$(":checkbox[ename='horbby'],:checkbox[name='horbby']").validate();
		$(":radio[name='gender']").validate();
		$(".comment").validate();
		$("#datetime").validate();
	}
	
	function call_ajax() {
					$.ajax({
						url:"/ajax/validate.php",
						data: {
							data: getData()
						},
						type: "post",
						dataType: "json",
						beforeSend: function( xhr ) {
							verify_date();
							if(gError.hasError) {
								gError.show();
								return false;
							}
						},
						error: function(xhr, tStatus, errorTh ) {
						},
						success: function( req, status ) {
							gError.ajaxError(req.error);
							if(gError.hasError) {
								gError.show();
							} else {
								alert("ok ok");
							}
						}
				});

	}
</script>
</head>
<body style="padding:0px; margin:0px;">
Personal Information:<br />
<table border="0" cellspacing="2" cellpadding="2">
	<tr>
    	<td align="right" valign="top">First Name</td>
        <td>
        	<input type="text" id="first_name" name="Family Name"  etype="CHAR" enull="1" value="" />
    	</td>
    </tr>
	<tr>
    	<td align="right"  valign="top">Last Name</td>
        <td>
        	<input type="text" id="last_name"  name="name" etype="CHAR" value="" />
    	</td>
    </tr>
	<tr>
    	<td align="right"  valign="top">Email Address</td>
        <td>
        	<input type="text" id="email" eid="email" etitle="Email Address" ename="email" etype="EMAILS" elength="15" value="" />
    	</td>
    </tr>
	<tr>
    	<td align="right"  valign="top">Horbit</td>
        <td>
        	<input type="checkbox"  etitle="Horbby" ename="horbby" etype="LETTER"  value="ski"  enull="1" />SKI<BR />
        	<input type="checkbox"  name="horbby" value="reading" />Reading<BR />
        	<input type="checkbox"  name="horbby" value="swim" />Swim<BR />
        	<input type="checkbox"  name="horbby" value="hocky" />Hocky<BR />
        	<input type="checkbox"  name="horbby" value="golf" />Golf<BR />
    	</td>
    </tr>
	<tr>
    	<td align="right"  valign="top">Gender</td>
        <td>
        	<input type="radio" name="gender" eid="male" etitle="Gender" 	ename="gender" etype="NUMBER" enull="1"  value="1" />Male<BR />
        	<input type="radio" name="gender" value="2" />Female<BR />
    	</td>
    </tr>
	<tr>
    	<td align="right"  valign="top">Comment</td>
        <td>
        	<textarea class="comment" name="comments" etype="ALL" enull="1"></textarea>
    	</td>
    </tr>
	<tr>
    	<td align="right"  valign="top">DateTime</td>
        <td>
        	<input type="text" id="datetime" name="Birth Date" etype="DATETIME" elength="25" value="" />
    	</td>
    </tr>
</table>
<input type="button" value="submit"  onclick="call_ajax();" />
<div id="msg" style="width:400px; top:10px; left:500px; position:absolute; height:500px; border:1px solid black;"></div>
</body>
</html>