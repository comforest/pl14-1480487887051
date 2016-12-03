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
	curl_setopt($ch1, CURLOPT_URL, $url."$api_key");
   
	$output = curl_exec($ch1);
    curl_close($ch1);

	return json_decode($output, true);
  }

  function rotation_champ() {
  	global $region;
  	$url = "https://kr.api.pvp.net/api/lol/$region/v1.2/champion?api_key=";

  	$arr = callAPI($url);

	$result = array();
	foreach($arr["champions"] as $key => $value) {
		if($value["freeToPlay"] === true) {
			$result[] = $value['id'];
		}
	}
	return getChampionName($result);
  }

  function b_rotation_champ($name) {
  	global $region;

  	$id = getChampionID($name);
  	$url = "https://kr.api.pvp.net/api/lol/kr/v1.2/champion/22?api_key=";

  	$arr = callAPI($url);

	return $arr["freeToPlay"];
  }

	function getSummonerID($name){
		$tmp = $name;
		$name2 = preg_replace("/\s+/","",$tmp);

		global $region; 
		$url = "https://$region.api.pvp.net/api/lol/$region/v1.4/summoner/by-name/$name2";
		$summonerInfo = callAPI($url);
		$id = $summonerInfo[$name2]['id'];
		return $id; 
	}

  function getChampionName($list){
  	global $region;

  	$url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion?api_key=";

  	$arr = callAPI($url);

  	$result = array();
  	foreach ($list as $k => $v) {
		foreach ($arr["data"] as $k1 => $v1) {
			if($v == $v1["id"]){
				$result[] = $k1;
				break;
			}
		}
	}

  	return $result;
  }
  function getChampionID($name){
  	global $region;

  	$url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion?api_key=";

  	$arr = callAPI($url);

  	$result = array();
	foreach ($arr["data"] as $k => $v) {
		if($k == $name){
			return $k;
		}
	}
	return null;
  }

?>
</body>
</html>