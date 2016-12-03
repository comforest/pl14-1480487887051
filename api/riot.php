<!DOCTYPE html>
<html>
<head>
	<title>RIOT API</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<?php 
  function rot_champ() {
	$url1 = "https://kr.api.pvp.net/api/lol/kr/v1.2/champion?api_key=RGAPI-040244e7-c5c4-4f93-910b-07b367bf78ca";
	$region = "kr";
	$ch1 = curl_init(); 

	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch1, CURLOPT_URL, $url1);
   
	$output = curl_exec($ch1);
	$decode = json_decode($output, true);
    curl_close($ch1);
	echo $arr;
	
	$result = array();
	$arr2 = $decode['champions'];
	foreach($arr2 as $key => $value) {
		if($value["freeToPlay"] === true) {
			$result[] = $value['id'];
		}
	}
	print_r($result);
  }
  
  rot_champ();
?>
</body>
</html>