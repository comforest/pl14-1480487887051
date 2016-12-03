<!DOCTYPE html>
<html>
<head>
	<title>RIOT API</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<?php
  $apiKey = "RGAPI-040244e7-c5c4-4f93-910b-07b367bf78ca";
  $region = "kr";

  $url = "https://kr.api.pvp.net/api/lol/$region/v1.2/champion?api_key=$apiKey";

  $ch = curl_init(); 

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_URL, $url);
   
  $output = curl_exec($ch);
  echo $output;
?>
</body>
</html>