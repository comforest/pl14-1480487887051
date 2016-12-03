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
	$ch1 = curl_init(); 

	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch1, CURLOPT_URL, $url."?api_key=$api_key");
   
	$output = curl_exec($ch1);
    curl_close($ch1);

	return json_decode($output, true);
  }

  function rotation_champ() {
  	global $region;
  	$url = "https://kr.api.pvp.net/api/lol/$region/v1.2/champion";

  	$arr = callAPI($url);

	// $url = "/api/lol/static-data/$region/v1.2/champion/";
	// $championList = callAPI($url);

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
  	global $region;

  	$url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion/$id";

  	$arr = callAPI($url);
  	
  	return $arr["key"];
  }
  rotation_champ();

?>
</body>
</html>