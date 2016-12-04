<!DOCTYPE html>
<html>
<head>
   <meta charset = "utf-8">
   <title>Welcome!</title>
   <link rel="stylesheet" type="text/css" href="style.css">
   <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script type="text/javascript">
      function size() {
         $('#wrapper').css('width', $(window).width()-10);
         $('#wrapper').css('height', $(window).height()-10);
      }
      $(document).ready(function() {
         size();
         $(window).resize(function() {size();});
      });
   </script>
</head>
<body>
   <div id="wrapper">
      <div id="container">
         <h1>LOL Searcher</h1>
         <p>LOL Searcher는 최고의 롤 백과사전입니다.</p>
         <p>귀여운 티모에게 뭐든지 물어보세요!</p>
         <br>
         <a href="main" id="" class="loginBtn">시작하기</a>
      </div>
   </div>
</body>
</html>