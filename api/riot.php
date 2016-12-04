<?php 
  $region = "kr";
  $language= "na";


  function callAPI($url){
   global $region;
   
   $api_key = "RGAPI-040244e7-c5c4-4f93-910b-07b367bf78ca";
   $ch1 = curl_init(); 

   curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
   curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($ch1, CURLOPT_URL, $url."$api_key");
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
       if($win === 0 && $lose === 0) {
          return 0;
       }
         $rate = $win * 100 / ($win + $lose); 
      return round($rate,2);
      }
   }
   return null;
  }
  
  function rotation_champ() {
     global $region;
     $url = "https://$region.api.pvp.net/api/lol/$region/v1.2/champion?api_key=";

     $arr = callAPI($url);

   $result = array();
   foreach($arr["champions"] as $key => $value) {
      if($value["freeToPlay"] === true) {
         $result[] = $value['id'];
      }
   }
   return getChampionName($result);
  }
  
  function all_champ() {
   global $region;
    $url = "https://$region.api.pvp.net/api/lol/$region/v1.2/champion?api_key=";

    $arr = callAPI($url);

    $result = array();
    foreach($arr["champions"] as $key => $value) {
         $result[] = $value['id'];
    }
   return getChampionName($result);
  }
  
  function b_rotation_champ($name) {
     global $region;

    $id = getChampionID($name);
     $url = "https://$region.api.pvp.net/api/lol/$region/v1.2/champion/$id?api_key=";

     $arr = callAPI($url);

   return $arr["freeToPlay"];
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
  
   function getSummonerTier($name){
      global $region;
      $id = getSummonerID($name);
      $url = "https://$region.api.pvp.net/api/lol/$region/v2.5/league/by-summoner/$id?api_key=";
      $data = callAPI($url);
      if(isset($data[$id]))
        return strtolower($data[$id][0]["tier"]);
      return "provisional"; 
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

  function getChampionList(){
     global $region;

     $url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion?api_key=";

     $arr = callAPI($url);

     $result = array();
   foreach ($arr["data"] as $k1 => $v1) {
      $result[] = $k1;
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
         return $v["id"];
      }
   }
   return null;
  }


  function championSkin($name) {
   $region = 'NA';
   $id = getChampionID($name);
   $url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion/$id?champData=skins&api_key=";
   $arr = callAPI($url);
   
   $result = array();
   foreach ($arr["skins"] as $k => $v) {
      $result[$v["name"]] = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/"."$name"."_$v[num].jpg";
   }
   return $result;
  }
  
  function championSkinImage($name) {
   global $region;
   $name2 = $name;
   $id = getChampionID($name);
   $url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion/$id?champData=skins&api_key=";
   $arr = callAPI($url);
   
   $result = array();
   foreach ($arr["skins"] as $k => $v) {
      $tmp = $v["num"];
      $result[] = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/"."$name2"."_$tmp.jpg";
   }
   return $result;
  }
  
  function getItemID($name) {
  	global $language;
   $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/item?api_key=";
   $arr = callAPI($url);
   
   foreach($arr["data"] as $k => $v) {
      if($v["name"] == $name) {
         return $v["id"];
      }
   }   
  }
  function rankingTop() {
   global $region;
   $url = "https://$region.api.pvp.net/api/lol/$region/v2.5/league/challenger?type=RANKED_SOLO_5x5&api_key=";
   $arr = callAPI($url);
   
   $no1 = $arr["entries"][0]["leaguePoints"];
   $no1Name = $arr["entries"][0]["playerOrTeamName"]; 
   
   foreach($arr["entries"] as $k => $v) {
      if($v["leaguePoints"] >= $no1) {
         $no1 = $v["leaguePoints"];
         $no1Name = $v["playerOrTeamName"];
      }
   }
   return $no1Name;

  }
  function itemInfo($name) {
  global $language;
   $id = getItemID($name);
   $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/item/$id?itemListData=sanitizedDescription&api_key=";
   
   $arr = callAPI($url);
   
   return $arr;   
  }
?>