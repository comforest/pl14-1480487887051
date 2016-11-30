<!DOCTYPE html>
<html>
<head>
	<title>LOL Searcher</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="mainpage.css" />
	<script type="text/javascript">
		window.onload = function() {
			focus_message();
		}

		function sendmessage() {
			var conversation = document.getElementById('conversation');
			var message = document.getElementById('message');
			if(message.value == "") {
				focus_message();
				return;
			}

			var str = "<div class='bubblewrap'><div class='bubble'><p class='label'>";
			    str += message.value;
			    str += "</p></div></div>";

			message.value = "";
			conversation.innerHTML += str;

			conversation.scrollTop = conversation.scrollHeight;
			focus_message();
		}

		function clearall() {
			var conversation = document.getElementById('conversation');
			conversation.innerHTML = "";
			focus_message();
		}

		function enter() {
			if(event.keyCode == 13) sendmessage();
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
					<input type="text" id="message" onKeyDown="enter();" style="width: 695px">
					<input type="button" id="send_btn" onclick="sendmessage();" value="전송" style="width: 40px">
					<input type="button" id="clear_btn" onclick="clearall();" value="삭제" style="width: 40px">
				</div>
			</div>
			<div id="sidebar">
				
			</div>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>