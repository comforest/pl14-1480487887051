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

  print_r(rotation_champ());
  print(win_rate("hide on bush"));
  
  function callAPI($url){
	global $region;
	
	$api_key = "RGAPI-040244e7-c5c4-4f93-910b-07b367bf78ca";
	$ch1 = curl_init(); 

	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch1, CURLOPT_URL, "$url"."$api_key");
   
	$output = curl_exec($ch1);
    curl_close($ch1);

	return json_decode($output, true);
  }

  function win_rate($name) {
	global $region;
	$id = getSummonerID($name);
	$url = "https://$region.api.pvp.net/api/lol/$region/v1.3/stats/by-summoner/$id/summary?season=SEASON2016&api_key=";
	
	$arr = callAPI($url);
	$win;
	$lose;
	foreach($arr["playerStatSummaries"] as $key => $value) {
		if($value["playerStatSummaryType"] === "RankedSolo5x5") {
			$win = $value["wins"];
			$lose = $value["losses"];
			break;
		}
	}
	$rate = $win * 100 / ($win + $lose); 
	return $rate;
  }
  function rotation_champ() {
  	global $region;
  	$url = "https://$region.api.pvp.net/api/lol/$region/v1.2/champion/?api_key=";

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
	$tmp = $name;
	$name2 = preg_replace("/\s+/","",$tmp);
	
	global $region; 
	$url = "https://$region.api.pvp.net/api/lol/$region/v1.4/summoner/by-name/$name2?api_key=";
	$summonerInfo = callAPI($url);
	$id = $summonerInfo[$name2]['id'];
  	return $id; 
  }

  function getChampionName($id){
  	return $name;
  }
?>
</body>
</html>