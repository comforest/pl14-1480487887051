<?php 
  $region = "kr";
  $language= "na";
  
  //curl을 통해 연동시키고 API key를 입력하는 부분을 함수로 제작
  function callAPI($url){
   global $region;
   
   $api_key = "RGAPI-040244e7-c5c4-4f93-910b-07b367bf78ca";
   $ch1 = curl_init(); 

   curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
   curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($ch1, CURLOPT_URL, $url."$api_key");
   $output = curl_exec($ch1);
   curl_close($ch1);

   //받아온 값을 json형식으로 반환
   return json_decode($output, true);
  }

  //승률 반환 함수
  function win_rate($name) {
   global $region;
   $id = getSummonerID($name); //각 유저에게 부여된 고유 ID
   //등록되지 않은 유저라면 null 반환
   if($id == null) {
      return null;
   }
   $url = "https://$region.api.pvp.net/api/lol/$region/v1.3/stats/by-summoner/$id/summary?season=SEASON2016&api_key=";
   
   $arr = callAPI($url);   
   $win;
   $lose;
   //승리 횟수와 패배 횟수를 받아와 전체 승률 계산
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
   //제대로 계산되지 못했다면 null 반환
   return null;
  }
  
  //총 게임 횟수 반환
  function gameNum($name) {
   global $region;
   $id = getSummonerID($name);
   if($id == null) {
      return null;
   }
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
	   //전체 게임 횟수 = 승리 횟수 + 패배 횟수
	   $total = $win + $lose;
	   return $total;
	 }
   }   
   return null;
 }
 
  //매주 바뀌는 무료로 플레이 가능한 캐릭터들 반환
  function rotation_champ() {
     global $region;
     $url = "https://$region.api.pvp.net/api/lol/$region/v1.2/champion?api_key=";

     $arr = callAPI($url);

   $result = array();
   //API에서 freeToPlay값이 참이라면 무료 플레이 가능한 챔피언이므로 배열에 추가
   foreach($arr["champions"] as $key => $value) {
      if($value["freeToPlay"] === true) {
         $result[] = $value['id'];
      }
   }
   //챔피언들의 이름 반환
   return getChampionName($result);
  }
  
  //모든 챔피언 목록 반환
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
  
  //특정 챔피언이 무료로 플레이 가능한지 여부 반환
  function b_rotation_champ($name) {
     global $region;

    $id = getChampionID($name);
     $url = "https://$region.api.pvp.net/api/lol/$region/v1.2/champion/$id?api_key=";

     $arr = callAPI($url);

   return $arr["freeToPlay"];
  }

  //유저의 닉네임을 입력하면 고유 ID를 반환시키는 함수
  function getSummonerID($name){
   $tmp = $name;
   $name2 = preg_replace("/\s+/","",$tmp);
   
   global $region; 
   $url = "https://$region.api.pvp.net/api/lol/$region/v1.4/summoner/by-name/$name2?api_key=";
   $summonerInfo = callAPI($url);
   if(isset($summonerInfo["status"]["status_code"]) && $summonerInfo["status"]["status_code"] == 404) {
   //유저가 등록되어있지 않다면 API의 status_code에서 404를 반환하는데, 이를 이용
      return null;
   }
   else {
     $id = $summonerInfo[$name2]['id'];
     return $id; 
   }
  }
  
   //유저의 실력에 따라 티어를 나누는데, 티어를 반환해주는 함수
   function getSummonerTier($name){
      global $region;
      $id = getSummonerID($name);
     
     if($id == null) {
        return null;
     }
      $url = "https://$region.api.pvp.net/api/lol/$region/v2.5/league/by-summoner/$id?api_key=";
      $data = callAPI($url);
      if(isset($data[$id]))
        return strtolower($data[$id][0]["tier"]);
      return "provisional"; 
   }

  //리스트에서 챔피언의 이름을 반환
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

  //모든 챔피언의 리스트를 반환시킴
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

  //챔피언의 이름을 입력받아 그 챔피언 고유의 ID를 반환
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

  //특정 챔피언이 어떤 종류의 스킨을 가지는지 여부를 반환
  function championSkin($name) {
    global $language;
    $id = getChampionID($name);
   
    if($id == null) {
      return null;
    }
    $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=skins&api_key=";
    $arr = callAPI($url);
   
    $result = array();
    foreach ($arr["skins"] as $k => $v) {
	  //링크를 주소창에 입력하면 챔피언 스킨을 볼 수 있으므로 이를 반환
      $result[$v["name"]] = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/"."$name"."_$v[num].jpg";
    }
    return $result;
  }
  
  //챔피언 스킨의 이미지를 반환
  function championSkinImage($name) {
   global $region;
   $name2 = $name;
   $id = getChampionID($name);
   
   if($id == null) {
    return null;
   }
   $url = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/champion/$id?champData=skins&api_key=";
   $arr = callAPI($url);
   
   $result = array();
   foreach ($arr["skins"] as $k => $v) {
      $tmp = $v["num"];
      $result[] = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/"."$name2"."_$tmp.jpg";
   }
   return $result;
  }
  
  //아이템의 이름을 입력하면 고유 ID를 반환
  function getItemID($name) {
     global $language;
     $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/item?api_key=";
     $arr = callAPI($url);
   
     foreach($arr["data"] as $k => $v) {
        if(isset($v["name"]) && $v["name"] == $name)
          return $v["id"];
     }
    return null;
  }
  
  //랭킹 1~200위만이 들어갈 수 있는 티어를 분석해 랭킹1위를 반환
  function rankingTop() {
   global $region;
   $url = "https://$region.api.pvp.net/api/lol/$region/v2.5/league/challenger?type=RANKED_SOLO_5x5&api_key=";
   $arr = callAPI($url);
   
   //불규칙적으로 배열되어 있는 플레이어 목록에서 leaguePoints가 가장 높은 사람을 찾는 코드
   $no1 = $arr["entries"][0]["leaguePoints"];
   $no1Name = $arr["entries"][0]["playerOrTeamName"]; 
   
   foreach($arr["entries"] as $k => $v) {
	  //더 높은 leaguePoints를 가진 사람이 있다면 그 사람을 새로운 $no1로 설정
      if($v["leaguePoints"] >= $no1) {
         $no1 = $v["leaguePoints"];
         $no1Name = $v["playerOrTeamName"];
      }
   }
   return $no1Name;
  }
  
  //아이템의 이름을 입력하면 아이템 정보 반환
  function itemInfo($name) {
   global $language;
   $id = getItemID($name);
   if($id == null) {
      return null;
   }
   $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/item/$id?itemListData=sanitizedDescription&api_key=";
   
   $arr = callAPI($url);
   
   return $arr;   
  }
  
  //챔피언의 이름을 입력하면 챔피언 스킬 이름을 반환
  function championSkillName($name) {
     global $language;
     $id = getChampionID($name);
    if($id == null) {
       return null;
    }
     //spell url
     $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=spells&api_key=";
     //passive url
     $url2 = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=passive&api_key=";
     
     $arr = callAPI($url);
     $arr2 = callAPI($url2);
     
	 //챔피언 스킬은 passive, q, w, e, r 5가지가 있다.
     $skill = array("passive"=>null,"q"=>null,"w"=>null,"e"=>null,"r"=>null);
     $skill["passive"] = $arr2["passive"]["name"];
     $skill["q"] = $arr["spells"][0]["name"];
     $skill["w"] = $arr["spells"][1]["name"];
     $skill["e"] = $arr["spells"][2]["name"];
     $skill["r"] = $arr["spells"][3]["name"];
          
     return $skill;
  }
  
  //챔피언의 이름을 입력하면 상세설명 반환
  function championSkillDescrip($name) {
    global $language;
    $id = getChampionID($name);
    if($id == null) {
       return null;
    }
    //spell url
     $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=spells&api_key=";
     //passive url
     $url2 = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=passive&api_key=";
    
     $arr = callAPI($url);
     $arr2 = callAPI($url2);   
    
     $skill = array("passive"=>null,"q"=>null,"w"=>null,"e"=>null,"r"=>null);   
    $skill["passive"] = $arr2["passive"]["description"];
    $skill["q"] = $arr["spells"][0]["description"];
    $skill["w"] = $arr["spells"][1]["description"];
    $skill["e"] = $arr["spells"][2]["description"];
     $skill["r"] = $arr["spells"][3]["description"];
    
    return $skill;
  }
  
  //챔피언의 이름을 입력하면 스킬 이미지 URL을 반환
  function championSkillImage($name) {
     global $language;
     $id = getChampionID($name);
    if($id == null) {
       return null;
    }
     //spell url
     $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=spells&api_key=";
     //passive url
     $url2 = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/champion/$id?champData=passive&api_key=";
     
     $arr = callAPI($url);
     $arr2 = callAPI($url2);
     $skillImage = array("passive"=>null,"q"=>null,"w"=>null,"e"=>null,"r"=>null);
     $skillImage["passive"] = $arr2["passive"]["image"]["full"];
     $skillImage["q"] = $arr["spells"][0]["image"]["full"];
     $skillImage["w"] = $arr["spells"][1]["image"]["full"];
     $skillImage["e"] = $arr["spells"][2]["image"]["full"];
     $skillImage["r"] = $arr["spells"][3]["image"]["full"];
     
     foreach($skillImage as $k => $v) {
        if($k == "passive") {
           $skillImage[$k] = "http://ddragon.leagueoflegends.com/cdn/6.23.1/img/passive/$v";
        }
        else {
           $skillImage[$k] = "http://ddragon.leagueoflegends.com/cdn/6.23.1/img/spell/$v";
        }
     }
     return $skillImage;
   }
   
   //모든 아이템들의 list를 반환
  function getItemList() {
    global $language;
   $url = "https://global.api.pvp.net/api/lol/static-data/$language/v1.2/item?itemListData=hideFromAll&api_key=";
   $arr = callAPI($url);
   $result = array();
    foreach ($arr["data"] as $key => $value) {
      if(isset($value["name"]))
        $result[$key] = $value["name"];
    }
   return $result;   
  }
?>