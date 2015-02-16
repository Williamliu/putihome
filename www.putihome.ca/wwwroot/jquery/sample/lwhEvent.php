<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>JQUERY Event</title>
	
	<script type="text/javascript" 	src="../code/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="../min/jquery-ui-1.8.5.custom.min.js"></script>
	<link type="text/css" 			href="../themes/base/jquery.ui.all.css" rel="stylesheet" />
    
	<script language="javascript" type="text/javascript">
		/*
			-------------------------------------------------------------------------------
			bind , live 都是事件叠加的。 根据定义的先后顺序执行。 无所谓命名空间。
			bind and unbind
			// this will destory  window all resize event,  including resize.xxxx, resize
			$(window).unbind("resize");
			
			// this will only destory  window all resize.test.
			$(window).unbind("resize.test");
	
			--------------------------------------------------------------------------------
			bind, live , onclick 的执行顺序是 ，  先 bind, onclick , live 最后。
			
			--------------------------------------------------------------------------------
			bind , live 的冒泡事件处理。同一元素的情况。
			1) $("#ttt").trigger("click",[10, 20 , 30]);
			先执行 bind , 然后执行 onclick, 最后执行 live 的。
			
			2) $("#ttt").trigger("click!",[10, 20 , 30]);  带感叹号 ! 
			先执行所有 bind 不带命名空间的， 再执行onclick， 最后执行live的。
			
			3) $("#ttt").trigger("click.bok",[10, 20 , 30]);
			先只执行所有 bind.xxxx 匹配命名空间的事件， 不在执行 onclick , 最后执行所有 live 包括所有命名空间的。 
			
			4) $("#ttt").trigger("click.uok",[10, 20 , 30]);
			虽然在 bind 不存在 click.uok, 而且也不执行。  而live 存在 click.uok , 但是仍然执行所有 live 的事件。
			
			5) $("#ttt").triggerHandler("click");
			只阻止冒泡 live 事件， 不阻止 bing, onclick 事件

			6) $("#ttt").triggerHandler("click.bok");
			阻止冒泡 所有live 事件， 只执行所有 bind 的click.bok事件, 不执行onclick 事件。
			
			7) 	bind 所绑定的任何事件里使用如下代码， 都不能阻止往后的事件， 也就是说对所有 bind 的事件都不起作用，
				但是 bind 任何事件定义以下代码， 都将阻止所有 live 事件。 
				ev.preventDefault();
				ev.stopPropagation();
				return false;

			8) 如果 live 事件上有, 则按顺序执行到此为止。
				ev.stopPropagation();
				return false;

			---------------------------------------------------------------------------------
			bind , live 的冒泡事件处理。祖先与后代元素的情况。
			1) 先执行子 onclick -> bind, 然后执行祖先的 onclick -> bind事件， 所有bind 执行完后， 执行子的 live, 再往上执行祖先的live. 所有执行完以后， 再执行 document.bind
			2) 在 bind 事件上执行阻止冒泡， 则执行完所有同层的bind事件后， 即可停止向上传递， 也不执行其祖先的 bind 事件。
				ev.stopPropagation();
			   	return false; 
			3） 在live 事件上执行阻止冒泡， 则是到此为止。 然后如果定义了 document.bind ，则还要执行document.bind. 
			
			4) $("#a2").triggerHandler("click.ok"); 只执行 #a 上的 click.ok 事件， 然后执行 document 上的所有 click 事件

			
			6) 先子 bind,  后祖先bind, 然后子 live, 再祖先的live, 最后 document 的bind, (没有live)
			7) 顺序如下 
				tta 111
			  	myfunc 的顺序逻辑
				tta 222
				
				如果没有 return false;  
				则是 
				tta 111 
				myfunc 的祖孙顺序逻辑
				tta 的祖孙 click 事件逻辑。
				
			$("#tta").bind("click", function() {
				$("#msg").append("tta 1111");
				$(this).trigger("myfunc",[100, 200, 300]);
				return false;
			})

			$("#tta").bind("click",function(ev) {
				cnt++;
				$("#msg").append("tta 2222");
			});		
			
			
			
			
			--------------------------------------------------------------------------------
			bind, live  可以有两类数据： ev.data  and function(event, a, b)  a , b parameter.
			$("#ttt").bind("myfunc", ["hello", "world", 2012] , myfunc_content);
			function myfunc_content( ev, a, b ) {
				alert("ev:" + ev.data[0] + "-" + ev.data[1] + "-" + ev.data[2] + "-" + ev.data[3] + "  a=" + a + " b=" + b );
			}
			$("#ttt").trigger("myfunc");
			ev:hello-world-2012-undefined a=undefined b=undefined;
			
			$("#ttt").trigger("myfunc", [100, 200, 300, 400]);
			output:  ev:hello-world-2012-undefined a=100 b=200
			
			comment:  
			1) make clear of different: ev.data  and function parameter.
			2) $("#ttt").myfunc(100, 200);   get error of myfunc not define, you must use trigger to call.
			3) $("#ttt").click(100,200); - get error    			$("#ttt").click(); - ok  but no parameter transfer to function.
			4) this sample will be ok for click event to transfer data and parameter: 
			$("#ttt").bind("click", ["aa", "bb", "cc"], liveClick_111).trigger("click", [100, 200, 300, 400, 500]);	
			function liveClick_111(ev, a, b, c) {
				cnt++;
				var tt = ev.target || ev.srcElement;
				$("#msg").append("live append click: " + cnt + " - #ttt liveClick_111(" + ev.data[0] +  "," + a + "," + b + "," + c + ")"  + "<br>");
			}
		
			
			--------------------------------------------------------------------------------
			die 
			1) die 只能解除由 live 注册的事件， 不能解除 onclick="" , bind 的事件
					$("#ttt").die("click");
					$("#ttt").die("click");

		
		
			---------------------------------------------------------------------------------
			clone(false), clone(true) 
			clone(true) - 将保留bind的事件一起， 当然live事件也肯定存在。 包括子孙元素在内
			clone(false) - 将丢失所有bind的事件， live事件仍然存在。 包括子孙元素在内
		
			
			remove() - 将元素移除，包括所有事件。 当使用变量再添加回文档时， 所有bind事件丢失。 
			detach() - 将元素卸载，当添加回文档时， 所有bind事件仍然保留。 
			*/


		var cnt = 0;
		$(function(){
			// bind  支持重复叠加 
			$(window).bind("resize.test", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize.test 111" + "<br>");
			});
			$(window).bind("resize", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize 111" + "<br>");
			});

			$(window).bind("resize.test", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize.test 222" + "<br>");
			});
			
			$(window).bind("resize", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize 222" + "<br>");
			});

			
			// below only bind once, no duplicate event accumulate. 
			$(window).unbind("resize.onlyone").bind("resize.onlyone", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize.onlyone 1111" + "<br>");
			});

			$(window).unbind("resize.onlyone").bind("resize.onlyone", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize.onlyone 2222" + "<br>");
			});

			$(window).unbind("resize.onlyone").bind("resize.onlyone", function() {
				cnt++;
				$("#msg").append("window resize: " + cnt + " - " + "resize.onlyone 3333" + "<br>");
			});

			
			// live 支持重复叠加
			$("#ttt").live("click",function(ev) {
				cnt++;
				$("#msg").append("live append click: " + cnt + " - #ttt 1111" + "<br>");
				//ev.stopPropagation();
				//return false;
			});		

			
			$("#ttt").bind("click",function(ev) {
				cnt++;
				$("#msg").append("bind append click: " + cnt + " - #ttt 1111" + "<br>");
				//ev.stopPropagation();
				//return false;
			});		

			$("#ttt").live("myfunc.test222",function(ev) {
				cnt++;
				$("#msg").append("myfunc.test222: " + cnt + " - #ttt 2222"  + "<br>");
				//ev.preventDefault();
				//ev.stopPropagation();
				//return false;
			});		
			
		
			$("#ttt").bind("myfunc", ["hello", "world", 2012] , myfunc_content);
									  
			$("#ttt").live("myfunc.uok", ["aa", "bb", "cc"], liveClick_333);		
			

			
			$("#ttt").bind("myfunc.ok", ["aa", "bb", "cc"], liveClick_111);		

			$("#ttt").bind("myfunc.bok", function(ev){
				cnt++;
				$("#msg").append("myfunc.bok: " + cnt + " - #ttt 4444"  + "<br>");
				//ev.stopPropagation();
			});		
			
			$("#ttt").bind("myfunc.bok", ["dd"], liveClick_111);		

			$("#ttt").bind("myfunc", function(ev){
				cnt++;
				$("#msg").append("myfunc bind: " + cnt + " - #ttt 3333"  + "<br>");
			});		

			$("#tta").live("myfunc",function(ev) {
				cnt++;
				$("#msg").append("myfunc live: " + cnt + " - #tta 1111"  + "<br>");
				
				//preventDefautl to prevent  href="" when it have onclick event
				//ev.preventDefault();
				
				//当一个事件处理函数用 .live() 绑定后，要停止执行其他的事件处理函数，那么这个函数必须返回 false。 仅仅调用 .stopPropagation() 无法实现这个目的。
				//ev.stopPropagation();
				//return false;
			});		

			$("#tta").bind("myfunc",function(ev) {
				cnt++;
				$("#msg").append("#tta myfunc  bind: " + cnt + " - #tta 1111"  + "<br>");
			});		


			$("#tta").live("myfunc.kkk",function() {
				cnt++;
				$("#msg").append("myfunc.kkk live: " + cnt + " - #tta 2222"  + "<br>");
			});		

			$("#tta").live("click", function() {
				cnt++;
				$("#msg").append("tta live click: " + cnt + " - #tta 2222"  + "<br>");
				//return false;
			})
			
			$("#tta").bind("click", function() {
				cnt++;
				$("#msg").append("tta bind click: " + cnt + " - #tta 2222"  + "<br>");
				$(this).trigger("myfunc",[100, 200, 300]);
				return false;
			})

			$("#tta").bind("click",function(ev) {
				cnt++;
				$("#msg").append("#tta click  bind: " + cnt + " - #tta 1111"  + "<br>");
			});		

		
		
		
			$("#a2").live("click", function(ev) {
				$("#msg").append("a2 live - click1111");
				$("#msg").append("<br>");
				//ev.stopPropagation();
				//return false;
			});

			$("#a2").live("click.ok", function(ev) {
				$("#msg").append("a2 live - click.ok");
				$("#msg").append("<br>");
			});

			$("#a2").live("click", function(ev) {
				$("#msg").append("a2 live - click222");
				$("#msg").append("<br>");
			});

			$("#a2").bind("click", function(ev) {
				$("#msg").append("a2 bind - click1111");
				$("#msg").append("<br>");
			});

			$("#a2").bind("click.ok", function(ev) {
				$("#msg").append("a2 bind - click.ok");
				$("#msg").append("<br>");
			});

			$("#a2").bind("click.ok", function(ev) {
				$("#msg").append("a2 bind - click222");
				$("#msg").append("<br>");
			});

			$("#a1").live("click", function(ev) {
				$("#msg").append("a1 live - click1111");
				$("#msg").append("<br>");
			});

			$("#a1").live("click.ok", function(ev) {
				$("#msg").append("a1 live - click.ok");
				$("#msg").append("<br>");
			});

			$("#a1").live("click", function(ev) {
				$("#msg").append("a1 live - click222");
				$("#msg").append("<br>");
			});

			$("#a1").bind("click", function(ev) {
				$("#msg").append("a1 bind - click1111");
				$("#msg").append("<br>");
			});

			$("#a1").bind("click.ok", function(ev) {
				$("#msg").append("a1 bind - click.ok");
				$("#msg").append("<br>");
			});

			$("#a1").bind("click", function(ev) {
				$("#msg").append("a1 bind - click222");
				$("#msg").append("<br>");
			});

			
			$("#a3").live("click", function(ev) {
				$("#msg").append("a3 live - click1111");
				$("#msg").append("<br>");
			});

			$("#a3").live("click.ok", function(ev) {
				$("#msg").append("a3 live - click.ok");
				$("#msg").append("<br>");
			});

			$("#a3").live("click", function(ev) {
				$("#msg").append("a3 live - click222");
				$("#msg").append("<br>");
			});

			$("#a3").bind("click", function(ev) {
				$("#msg").append("a3 bind - click1111");
				$("#msg").append("<br>");
			});

			$("#a3").bind("click.ok", function(ev) {
				$("#msg").append("a3 bind - click.ok");
				$("#msg").append("<br>");
			});

			$("#a3").bind("click", function(ev) {
				$("#msg").append("a3 bind - click222");
				$("#msg").append("<br>");
			});


			$(document).live("click", function(ev) {
				$("#msg").append("document live - click111");
				$("#msg").append("<br>");
			});

			$(document).bind("click.ok", function(ev) {
				$("#msg").append("document bind - click.ok");
				$("#msg").append("<br>");
			});
			
			$(document).bind("click", function(ev) {
				$("#msg").append("document bind - click1111");
				$("#msg").append("<br>");
			});


			
			

		});
		
		function win_resize_unbind_all() {
			// this will destory  window all resize event,  including resize.xxxx, resize
			$(window).unbind("resize");
		}

		function win_resize_unbind_test() {
			// this will only destory  window all resize.test.
			$(window).unbind("resize.test");
		}


		var left = 0;
		function live_element_append() {
			left =left + 130;
			$("body").append('<div id="ttt" style="position:absolute; background-color:#eeeeee; display:block; top:10px; left:' + (200+left) + 'px; width:120px; height:200px; z-index:999; border:1px solid red;">' + 
							 '<a id="tta" href="javascript:alert(\'ttt a\');">A Tag(' + left + ')</a>' +
							 '</div>');
		}

		function liveClick_111(ev, a, b, c) {
				cnt++;
				var tt = ev.target || ev.srcElement;
				$("#msg").append("myfunc.ok: " + cnt + " - liveClick_111(" + ev.data[0] +  "," + a + "," + b + "," + c + ")"  + "<br>");
		}

		function liveClick_222(ev) {
				cnt++;
				var tt = ev.target || ev.srcElement;
				$("#msg").append("onclick: " + cnt + " - liveClick_222(" + $(tt).attr("id") +  ")"  + "<br>");
		}

		function liveClick_333(ev) {
				cnt++;
				var tt = ev.target || ev.srcElement;
				$("#msg").append("myfunc.uok: " + cnt + " - liveClick_333(" + $(tt).attr("id") +  ")"  + "<br>");
		}

		function mutiple_id_trigger() {
			// only trigger first element with #ttt.  
			$("#ttt").triggerHandler("myfunc.bok",[10, 20 , 30]);
		}

		function live_elment_die() {
			// die 只能解除由 live 注册的事件， 不能解除 onclick="" , bind 的事件
			$("#ttt").die("click");
		}

		function clear_msg() {
			$("#msg").empty();
			cnt = 0;
		}
		
		function myfunc_content( ev, a, b ) {
			var str = "ev:" + ev.data[0] + "-" + ev.data[1] + "-" + ev.data[2] + "-" + ev.data[3] + "  a=" + a + " b=" + b + "<br>";
			//alert("ev:" + ev.data[0] + "-" + ev.data[1] + "-" + ev.data[2] + "-" + ev.data[3] + "  a=" + a + " b=" + b );
			$("#msg").append(str);
		}
		var rem = null;
		function bubble_stop() {
			//$("#a2").die("click");
			//$("#a2").trigger("click");
			rem = $("#a2").detach();
		}

		function el_append() {
			$("#a4").append(rem);
			//$("#a2").clone(false).appendTo("#a4");
		}
		</script>
</head>
<body>
<span style="font-size:18px; color:blue;">unbind & bind  event:</span><br />
<input type="button" onclick="win_resize_unbind_all();" value="window resize unbind all" /><br />
<input type="button" onclick="win_resize_unbind_test();" value="window resize unbind resize.test" /><br /><br />
<br />
<span style="font-size:18px; color:blue;">live & die:</span><br />
<input type="button" onclick="live_element_append()" value="live element append" /><br />
<input type="button" onclick="mutiple_id_trigger()" value="mutiple_id_trigger" /><br />
<input type="button" onclick="live_elment_die()" value="live element die" /><br />
<br />
<input type="button" onclick="bubble_stop()" value="bubble_stop" /><br />
<br />
<input type="button" onclick="el_append()" value="element append" /><br />


<div style="position:absolute; left:800px; top:10px; background-color:#eeeeee;">
	<input type="button" onclick="clear_msg();" value="message clear" /><br />
	<div id="msg" style="color:red; background-color:#eeeeee; border:1px solid yellow; padding:10px; display:block; width:400px; height:600px; overflow:auto;"></div>
</div>

<div id="ttt" onclick="liveClick_222(event);" style="position:absolute; background-color:#eeeeee; display:block; top:10px; left:200px; width:120px; height:200px; z-index:999; border:1px solid red;">
	<a id="tta" href="javascript:alert('ttt a');">A Tag(111)</a>
</div>


<div id="a1" onclick="$('#msg').append('a1 onclick directly<br>');" style="border: 1px solid black; display: block; width:400px; height:400px; background-color:#060;" align="center">
	<span style="display:inline-block; width:1px; height:100%; vertical-align:middle; position:relative;"></span>
	<div onclick="$('#msg').append('a2 onclick directly<br>');" id="a2" style="display:block; border:1px solid pink; width:200px; height:200px; background-color:#99F; position:relative; vertical-align:middle;">
		<a id="a3" onclick="$('#msg').append('a3 onclick directly<br>');">Good Morning</a>
    </div>
</div>

<div id="a4" style="border:1px solid blue; width:400px; height:400px; left:300px; position:relative; background-color:#cccccc;">
</div>
</body>
</html>