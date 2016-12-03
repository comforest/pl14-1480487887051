<!DOCTYPE html>
<html>
<head>
	<title>RIOT API</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<?php 
  $region = "kr";

  function callAPI($url){
	$api_key = "RGAPI-040244e7-c5c4-4f93-910b-07b367bf78ca";
	$base_url = "https://kr.api.pvp.net";
	$ch1 = curl_init(); 

	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch1, CURLOPT_URL, $base_url.$url."?api_key=$api_key");
   
	$output = curl_exec($ch1);
    curl_close($ch1);

	return json_decode($output, true);
  }

  function rotation_champ() {
  	global $region;
  	$url = "/api/lol/$region/v1.2/champion";

  	$arr = callAPI($url);

	$result = array();
	foreach($arr["champions"] as $key => $value) {
		if($value["freeToPlay"] === true) {
			$result[] = getChampionName($value['id']);
		}
	}
	return $result;
  }

  function getSummonerID($name){
  	$id = '';
  	return $id;
  }

  function getChampionName($id){
  	$name = $id;
  	return $name;
  }


?>
</body>
</html>