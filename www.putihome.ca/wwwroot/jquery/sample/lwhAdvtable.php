<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY MyPlugIn Window</title>
	
	<script type="text/javascript" 	src="../min/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.21.custom.min.js"></script>
	<script type="text/javascript" 	src="../myplugin/jquery.lwh.advtable.js"></script>
	<script type="text/javascript" 	src="/js/js.lwh.common.js"></script>
	<link type="text/css" 			href="../theme/dark/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
    <link 	type="text/css" 		href="../myplugin/css/dark/jquery.lwh.advtable.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		// new fu
		var old_ww = 0;
		var mycon = '';
		$(function(){
			var a = [];
			a["1"] = 1000;
			mycon = $("#mycon").html();
			//alert("a=" + a[1] + " len:" + a.length);
			$("#mytab").lwhTable({
				memHeight:	true,
				initWidth: [100,200],
				hideCols: [],
				snhCols:[1,0,0,0],
				wrapCols:[],
				resizeCols:[1,1,1,1],
				rowResize:	true,
				init: function(t) {
					//alert("table id:" + $(t).attr("id"));
				},
				col_show: function(t,c,s) {
					//alert("show table id:" + $(t).attr("id") + " sn:" + s);
				},
				col_hide: function(t,c,s) {
					//alert("hide table id:" + $(t).attr("id") + " sn:" + s);
				},
				col_end: function(t,c,s) {
				},
				row_end: function(t,c,s) {
				}
			});

			$("#youtab").lwhTable({
				rowResize:	true,
				memHeight:	false,
				initWidth: [100,200],
				hideCols: [],
				snhCols:[1,0,0,0],
				wrapCols:[1,1,1,1,1,1,1],
				resizeCols:[1,0,0,0,1],
				init: function(t) {
					//alert("table id:" + $(t).attr("id"));
				},
				col_show: function(t,c,s) {
					//alert("show table id:" + $(t).attr("id") + " sn:" + s);
				},
				col_hide: function(t,c,s) {
					//alert("hide table id:" + $(t).attr("id") + " sn:" + s);
				},
				col_end: function(t,c,s) {
				},
				row_end: function(t,c,s) {
				},
				menu_open: function(t, c, s) {
					//alert("menu open table id:" + $(t).attr("id") + " sn:" + s + " col:" + c.text());
				},
				col_sort: function(t, s) {
					//alert("col sort field:" + t + " sq:" + s );
				}
			});

			/*
			$(window).resize(function(){
				if( $("#mytab").width() != old_ww ) {
					old_ww = $("#mytab").width();
					$.lwhTable_syncBorders($("#mytab"));
					$("#msg").append("table resize");
				}
			});
			var str = 'table:' + $("#mytab").css("padding") + "<br>";
			$("td", $("tr:nth-child(2)") ).each(function(idx0, el0) {
					str += "column: " + idx0 + "<br>";
					str += "Left: " + Math.ceil($(el0).position().left) + "<br>";
					str += "width: " + $(el0).width() + "<br>";
					str += "Outer: " + $(el0).outerWidth() + "<br><br>";
			});
			$("#msg").append(str);
			*/
		});
		
		function hhh() {
			$("#mytab").lwhTable_hide(0);
		}

		function sss() {
			$("#mytab").lwhTable_show(0);
		}
		
		function ddd() {
			$("table").lwhTable_destory();
			$("#mycon").empty();
			$("#mycon").html(mycon);
		}
		function rrr() {
			$("table").lwhTable({
				rowResize:	true
			});
		}
	</script>
</head>
<body style="padding:0px; margin:0px;">
<input type="button" onclick="hhh();" value="hide 222" />
<input type="button" onclick="sss();" value="show 222" />
<input type="button" onclick="ddd();" value="Destroy" />
<input type="button" onclick="rrr();" value="Rebuild" />
<br />
<div id="mycon" style="position:relative; left:20px; top:20px; width:500px; overflow:auto; padding-bottom:40px;">
<table id="mytab"  class="lwhTable" border="1" cellpadding="2" cellspacing="0" sort="ttff222" sq="desc">
    	<tr>
        	<td width="180" class="nowrap" sort="ttff111" defsq="desc">Column <span style="color:red;">One One</span></td>
        	<td width="200" class="nowrap resizable">Col-111 resizable width</td>
        	<td width="220" class="hidden">Column Three 222</td>
        	<td width="160" class="show-hide"  sort="ttff222" defsq="asc">Column Four 333</td>
        	<td width="120" class="">Column Four 4444</td>
        	<td width="120" class="">Column Five 5555</td>
    	</tr>
    	<tr>
        	<td width="180">
            	content one onle http://dev.usanacity.com/jquery/sample/lwhAdvtable.php http://dev.usanacity.com/jquery/sample/lwhAdvtable.php
            </td>
        	<td width="200" valign="top">
            	content two two, content text can be wrap automaticly.<br />
               content two too  dskfjadskljf kasdjfk adsfjkdsl adskjfkads jdf adskjfklads f<br />
			   asdkjfadsklj fladsjfkl adsklfjadskljfadskljf ladsjfladskjfladsj fadskljf ads<br />
			   asdfjadskljfkldsjaflk adsklfjadsklj fadskljfadskl kljasefkljadsl kfdsa<br />
               adsjflasdjflkj adsfkljads lfsadkljfladsk flads fsa<br />
               asdfjladskjfads
            </td>
        	<td width="30">693 adsjkfajdsl askdjf asjadsk sadfjdska askdjfkads askdjfkslad</td>
        	<td width="60">
    			        Descr iption Of Jobs djfadsljf asdkfjadsk sdkfjkasd sadjfkads
            </td>
        	<td width="20">
            	one two three four
            </td>
        	<td width="20">
            	one two three four
            </td>
        </tr>
    	<tr>
        	<td width="80">
            	<textarea style="width:100%; resize:none; height:98%; border:1px solid green;">content one onle with nowrap</textarea>
            </td>
        	<td width="200">
            	content two two, content text can be wrap automaticly.
            </td>
        	<td width="30">dsdfasdfasd</td>
        	<td width="60">
    			        Descr iption Of Jobs
            </td>
        	<td width="20">
            	one two three four
            </td>
        	<td width="20">
            	one two three four
            </td>
        </tr>
    	<tr>
        	<td width="80">
            	content one onle with nowrap
            </td>
        	<td width="200">
            	content two two, content text can be wrap automaticly.
            </td>
        	<td width="30">dsfasdfdas</td>
        	<td width="60">
    			        Descr iption Of Jobs
            </td>
        	<td width="20">
            	one two three four
            </td>
        	<td width="20">
            	one two three four
            </td>
        </tr>
    	<tr>
        	<td width="80">
            	content one onle with nowrap
            </td>
        	<td width="200"  class="nowrap">
            	content two two, content text can be wrap automaticly.
            </td>
        	<td width="30">dfasd</td>
        	<td width="60">
    			        Descr iption Of Jobs
            </td>
        	<td width="20">
            	one two three four
            </td>
        	<td width="20">
            	one two three four
            </td>
        </tr>
</table>
</div>
<br /><br /><br /><br />
<div>
<table id="youtab" border="1" cellpadding="2" cellspacing="0" class="lwhTable"  sort="f1" sq="desc">
	<tr>
		<td width="200" class="nowarp show-hide" sort="f1" defsq="desc">
			COLUMN 11111 CLUMNE 1111
		</td>
		<td width="150" class="nowrap show-hide resizable" sort="f2" defsq="asc">
			COLUMN 2222 CLUMNE 22222
		</td>
		<td width="120" class="nowrap resizable" sort="f3" defsq="asc">
			COLUMN 33333 CLUMNE 33333
		</td>
		<td width="160" class="nowrap" sort="f4" defsq="asc">
			COLUMN 44444 CLUMNE 44444
		</td>
		<td width="100">
			COLUMN 55555 CLUMNE 5555
		</td>
	</tr>
	<tr>
		<td>
			<input class="fullwidth" value="hello sdkjfklsadj sakdjfklasdjf ksaldfjklasd jflksdajfasdf jaslkdfjsl aslkdfjlkasd" />
		</td>
		<td>
			<textarea class="fullsize">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<div class="fullwidth" style="border:1px solid red;">kdsjfkasdjkl asdkjflkasdjflkasj kjasdf  kdjfkasd kasjdfkjasl dfasdkjasdlkfasdkj ksdjfkldsa</div>
        </td>
		<td>
			<div class="resize" style="display:block; width:50px; height:40px; border:1px solid red;">kdsjfkasdjkl asdkjflkasdjflkasj kjasdfkjasdlkfasdkj ksdjfkldsa</div>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<div class="fullheight" style="border:1px solid red; width:100px; white-space:normal;">kdsjfkasdjkl asdkjflkasdjflkasj kjasdf  kdjfkasd kasjdfkjasl dfasdkjasdlkfasdkj ksdjfkldsa</div>
		</td>
		<td>
			<input class="fullheight" style="width:100px;" value="hello sdkjfklsadj sakdjfklasdjf ksaldfjklasd jflksdajfasdf jaslkdfjsl aslkdfjlkasd" />
		</td>
		<td>
			<textarea class="fullwidth" style="height:50px;">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea class="fullheight" style="width:120px;">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea class="fullsize">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea class="fullsize" style="width:150px; height:80px;">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea class="fullsize">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea class="fullsize">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea class="fullsize">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea class="fullsize">hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
	<tr>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
		<td>
			<textarea>hello world this is a good djfkds ksdjfk askjasdfkjads asdkfjadsk aksjdfkadsjfkasdjflas
			</textarea>
		</td>
	</tr>
</table>
</div>			
</body>
</html>