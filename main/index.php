<!DOCTYPE html>
<html>
<head>
	<title>LOL Searcher</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="mainpage.css" />
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
		function size() {
			$('#wrapper').css('width', $(window).width()-10);
			$('#wrapper').css('height', $(window).height()-10);
			$('#conversation').css('height', $(window).height()-100);
			$('#sidebar').css('height', $(window).height()-60);
			$('#sidebar').css('width', $('#sidebar').height()*0.4);
			$('#conversation').css('margin-right', $('#sidebar').width());
			$('#textinput').css('margin-right', $('#sidebar').width());
			$('#content').css('margin-right', -($('#sidebar').width()+50));
			$('#message').css('width', $('#textinput').width()-100);
		}
		$(document).ready(function() {
			size();
			$(window).resize(function() {size();});
			focus_message();
		});

		function sendmessage() {
			document.getElementById('send_btn').disabled = true;
			var conversation = document.getElementById('conversation');
			var message = document.getElementById('message');
			if(message.value.replace(/ /g,"") == "") {
				focus_message();
				document.getElementById('send_btn').disabled = false;
				return;
			}

			var str = "<div class='bubblewrap'><div class='bubble'><p class='label'>";
		    	str += message.value;
		    	str += "</p></div></div>";
			conversation.innerHTML += str;
			conversation.scrollTop = conversation.scrollHeight;
			message.value = "";
			focus_message();
			//TODO Ajax 처리

			$.ajax({
				url:"../api/conversation.php",
				type:"post",
				data:{input:message.value},
				success: function(data){
					var str = "<div class='bubblewrap watson'><div class='bubble'><p class='label'>";
					    str += data;
					    str += "</p></div></div>";
					conversation.innerHTML += str;
					conversation.scrollTop = conversation.scrollHeight;
					focus_message();
				}
			}).done(function() {
				document.getElementById('send_btn').disabled = false
			});
		}

		function clearall() {
			var conversation = document.getElementById('conversation');
			conversation.innerHTML = "";
			focus_message();
		}

		function enter() {
			if(event.keyCode == 13 && 
			!document.getElementById('send_btn').disabled)
				sendmessage();
		}

		function focus_message() {
			document.getElementById('message').focus();
		}
	</script>
</head>
<body>
	<div id="wrapper">
		<div id="header"><h1>LOL Searcher</h1></div>
		<div id="container">
			<div id="content">
				<div id="conversation"></div>		
				<div id="textinput">
					<input type="text" id="message" onKeyDown="enter();">
					<input type="button" id="send_btn" onclick="sendmessage();" value="전송" style="width: 40px">
					<input type="button" id="clear_btn" onclick="clearall();" value="삭제" style="width: 40px">
				</div>
			</div>
			<div id="sidebar"></div>
		</div>
	</div>
</body>
</html>