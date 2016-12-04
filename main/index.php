<!DOCTYPE html>
<html>
<head>
	<title>LOL Searcher</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="mainpage.css" />
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
		//resize divs by using jquery
		function size() {
			$('#wrapper').css('width', $(window).width()-10);
			$('#wrapper').css('height', $(window).height()-10);
			$('#conversation').css('height', $(window).height()-100);
			$('#sidebar').css('height', $(window).height()-70);
			$('#sidebar').css('width', $('#sidebar').height()*0.4);
			$('#conversation').css('margin-left', $('#sidebar').width()+5);
			$('#textinput').css('margin-left', $('#sidebar').width()+5);
			$('#content').css('margin-left', -($('#sidebar').width()+5));
			$('#message').css('width', $('#textinput').width()-100);
		}
		$(document).ready(function() {
			document.getElementById('conversation').innerHTML += "<div class='bubblewrap watson'><div class='bubble'><p class='label'>Hello! I'm teemo. Ask anything to me! kkk</p></div></div>";
			size();
			$(window).resize(function() {size();});
			focus_message();
		});

		//disable message input text and send button
		//to prevent message collapse
		function message_disable(bool) {
			document.getElementById('send_btn').disabled = bool;
			document.getElementById('message').disabled = bool;
			if(bool)
				$('#loading').css('display', 'block');
			else
				$('#loading').css('display', 'none');
		}

		//send message function
		//send message to watson and receive message
		function sendmessage() {
			message_disable(true);

			var conversation = document.getElementById('conversation');
			var message = document.getElementById('message');

			//if message empty
			if(message.value.replace(/ /g,"") == "") {
				message_disable(false);
				focus_message();
				return;
			}

			//message bubble
			var str = "<div class='bubblewrap'><div class='bubble'><p class='label'>";
		    	str += message.value;
		    	str += "</p></div></div>";
			conversation.innerHTML += str;
			conversation.scrollTop = conversation.scrollHeight;

			//data to send
			var val = message.value;
			message.value = "";
			focus_message();

			//TODO Ajax 처리
			$.ajax({
				url:"../api/conversation.php",
				type:"post",
				data:{input:val},
				success: function(data){
					//message from watson
					var str = "<div class='bubblewrap watson'><div class='bubble'><p class='label'>";
					    str += data;
					    str += "</p></div></div>";
					conversation.innerHTML += str;
					conversation.scrollTop = conversation.scrollHeight;
				}
			}).done(function() {
				//enable message input
				message_disable(false);
				focus_message();
			});
		}

		//clear conversation window
		function clearall() {
			var conversation = document.getElementById('conversation');
			conversation.innerHTML = "";
			focus_message();
		}

		//enter key to send message
		function enter() {
			if(event.keyCode == 13 && 
			!document.getElementById('send_btn').disabled)
				sendmessage();
		}

		//focus message
		function focus_message() {
			document.getElementById('message').focus();
		}
	</script>
</head>
<body>
	<div id="wrapper">
		<div id="header"><div id="title">LOL Searcher</div><div id="loading">loading...</div></div>
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