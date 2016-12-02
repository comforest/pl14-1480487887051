<!DOCTYPE html>

<html>
   <head>
      <meta charset = "utf-8">
      <title>Welcome!</title>
      <style type = "text/css">
         body {
            background: url('loginimg.png') no-repeat center fixed;
            background-size: 1600px 1000px
         }
         .loginForm {
            position:relative;
            width:1000px
         }
         .loginForm p { font-size: 12px; }
         .loginForm .box { width:326px }
         .loginForm .box .iText1 {
            width: 355px; margin: 3px 0;
            margin-top: 360px;
            margin-left: 285px;
            padding:10px 12px;
            border:1px solid #e1e1e1
         }
         .loginForm .box .iText2 {
            width: 355px; margin: 3px 0;
            margin-top: 20px;
            margin-left: 285px;
            padding:10px 12px;
            border:1px solid #e1e1e1
         }
         .loginForm .box p {
            margin-top:1em
         }
         .loginForm .box p span,
         .loginForm .box p input {
            height: 18px; font-size: 11px;
            color: #737373; line-height:18px;
            vertical-align:middle
         }
         .loginForm .loginBtn {
            background: #d9aa34;
            background-image: -webkit-linear-gradient(top, #d9aa34, #b8482c);
            background-image: -moz-linear-gradient(top, #d9aa34, #b8482c);
            background-image: -ms-linear-gradient(top, #d9aa34, #b8482c);
            background-image: -o-linear-gradient(top, #d9aa34, #b8482c);
            background-image: linear-gradient(to bottom, #d9aa34, #b8482c);
            -webkit-border-radius: 6;
            -moz-border-radius: 6;
            border-radius: 6px;
            font-family: Arial;
            color: #ffffff;
            font-size: 18px;
            padding: 10px 30px 9px 30px;
            margin-left: 550px;
            text-decoration: none;
         }
         .loginForm .loginBtn:hover {
            background: #fabb3c;
            background-image: -webkit-linear-gradient(top, #fabb3c, #d97b34);
            background-image: -moz-linear-gradient(top, #fabb3c, #d97b34);
            background-image: -ms-linear-gradient(top, #fabb3c, #d97b34);
            background-image: -o-linear-gradient(top, #fabb3c, #d97b34);
            background-image: linear-gradient(to bottom, #fabb3c, #d97b34);
            text-decoration: none;
         }
      </style>
   </head>
   
   <body>
       <div class="loginForm">
        <form method="post" name="" action="">
           <div class="box">
            <input type="text" class="iText1">
            <br><br>
            <input type="password" name="" id="" class="iText2">
            <br><br>
          </div>
          <a href="main" id="" class="loginBtn">로그인</a>
        </form>
      </div>
   </body>
</html>