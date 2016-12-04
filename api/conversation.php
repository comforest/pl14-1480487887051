<?php 
	session_start();

    $workspace_id = 'a5f9a706-0017-4aae-9ae3-f5c7e1f7d9c0';
    $userName = "c22a9e67-2f46-4dda-9286-b21862276e30";
    $userPwd = "05MXPpfi8Gs2";


    //Parameter for conversation API
    if(!isset($_SESSION["dialog"])) $context = array("system"=>array("dialog_stack"=>array("root")));
    else $context = $_SESSION["dialog"];
    $data = array("input" => array("text"=>"$_POST[input]"), "context"=>$context);
    $data_string = json_encode($data);


    //curl for conversation API
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id/message?version=2016-09-20"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_USERPWD, "$userName:$userPwd");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );
       
    //conversation result
    $out = curl_exec($ch);
    $arr = json_decode($out,true);

    curl_close($ch);

    $_SESSION["dialog"] = $arr["context"];


    //RIOT API File
    include $_SERVER["DOCUMENT_ROOT"]."/api/riot.php";
    //Document conversion API file
    include $_SERVER["DOCUMENT_ROOT"]."/api/doc_con.php";


    if(isset($arr["output"]["data"])){ //conversation API에서 특정 요청이 있는 경우
        $data = $arr["output"]["data"];
    }else{
        echo $arr["output"]["text"][0]; //conversation API에서 정해진 응답을 그대로 출력하는 경우
        return;
    }

	switch ($data) {
	 	case 'getRotationChampion': //로테이션 챔피언 검색
	 		$result = rotation_champ();
            echo 'The Rotation Champions are ';
            echo "<table>";
            $i = 0;
            foreach ($result as $key => $value) {
                if($i == 0) echo "<tr>";
                echo "<td style='width:calc(100vw/12)'>";
                echo "$value<br>";
                echo "<img src = 'http://ddragon.leagueoflegends.com/cdn/6.23.1/img/champion/$value.png ' height='70px'><br>";
                echo "</td>";
                ++$i;
                if($i == 11){
                     echo "</tr>";
                     $i = 0;
                };
            }
            echo "</table>";
	 		break;
	 	case 'includeRotationChampion': //해당 쳄피언 로테이션 챔피언 인지 확인
            $name = $arr["output"]["param"][0];
            if(b_rotation_champ($name))
                echo "Yes";
            else
                echo "No";
	 		break;
        case 'rateSummoner': // 플레이어의 승률
            $param = $arr["output"]["param"];
            if($param[0] == "win"){
                echo "$param[1]'s win rate is ".win_rate($param[1]).'%';
            }
            break;
	 	case 'summoner':
            $param = $arr["output"]["param"][0];
            $name = $arr["output"]["param"][1];
            if($param == "tier"){  // 플레이어의 랭크게임 티어(등급)
                if($tier == null){ //플레이어가 없을때
                    echo "Summoner does not exist.";
                    return;
                }
                echo "$name's Tier is $tier<br>";
                echo "<img src = '../images/tier/".$tier.".png'>";
            }else if($param == "gamenumber"){  // 플레이어가 플레이 한 랭크게임수
                $num = gameNum($name);
                if($num == null){
                    echo "Can't find summoner whose name is $name.";
                    return;
                } 
                echo "$name played total $num games";
            }
	 		break;
        case 'skin': //챔피언 스킨
            $param = $arr["output"]["param"][0];
            $result = championSkin($param);
            echo "The Champion's skins are<br>";
            echo "<table>";
            $i = 0;
            foreach ($result as $key => $value) {
                if($i == 0) echo "<tr>";
                echo "<td>";
                echo "$key<br>";
                echo "<img src = '$value' height='250px'><br>";
                echo "</td>";
                ++$i;
                if($i == 7){
                     echo "</tr>";
                     $i = 0;
                };
            }
            echo "</table>";
            break;
        case 'skill': //챔피언 스킬
            $param = $arr["output"]["param"][0];
            echo $param;
            $name = championSkillName($param);
            $img = championSkillImage($param);
            echo "The Champion's skills are<br>";
            foreach ($name as $key => $value) {
                echo "<img src = '$img[$key]' height='30px'> ";
                echo "$key - $value<br>";
                echo "<br>";
            }
            echo "If Do you want more information about skill, write Q,W,E,R or Passive";
            break;
        case 'skillInfo': //챔피언 스킬 상세 목록
            $param = $arr["output"]["param"][0];
            $skill = $arr["output"]["param"][1];
            $desc = championSkillDescrip($param);
            $name = championSkillName($param);
            $img = championSkillImage($param);
            echo "The Champion's $skill skill is<br>";
            
            echo "<img src = '$img[$skill]' height='30px'> ";
            echo "$skill - $name[$skill]<br>";
            echo "<br>";
            echo "$desc[$skill]";
            
            break;
        case 'championList': //전체 챔피언 목록
            $result = getChampionList();
            echo 'The Champions are ';
            echo "<table>";
            $i = 0;
            foreach ($result as $key => $value) {
                if($i == 0) echo "<tr>";
                echo "<td style='width:calc(100vw/12)'>";
                echo "$value<br>";
                echo "<img src = 'http://ddragon.leagueoflegends.com/cdn/6.23.1/img/champion/$value.png ' height='70px'><br>";
                echo "</td>";
                ++$i;
                if($i == 11){
                     echo "</tr>";
                     $i = 0;
                };
            }
            echo "</table>";
            break;
        case 'lol': //LoL에 대해서
            echo whatLoL();
            break;
        case 'rankingTop': //1위 플레이어 구하기
            echo "The best player is ". rankingTop();
            break;
        case 'itemList': //아이템 목록
            $arr = getItemList();
            echo "Item's list is";
            echo "<table>";
            $i = 0;
            foreach ($arr as $key => $value) {
                if($i == 0) echo "<tr>";
                echo "<td style='calc(100vw/10)'>";
                echo "$value<br>";
                echo "<img src = 'http://ddragon.leagueoflegends.com/cdn/6.23.1/img/item/$key.png ' height='70px'><br>";
                echo "</td>";
                ++$i;
                if($i == 9){
                     echo "</tr>";
                     $i = 0;
                };
            }
            echo "</table>";


            break;
        case 'itemInfo': //특정 아이템 상세정보
            $param = $arr["output"]["param"][0];
            $arr = itemInfo($param);
            echo $param." Information<br>";
            echo "<img src = 'http://ddragon.leagueoflegends.com/cdn/6.23.1/img/item/$arr[id].png ' height='70px'><br>";
            echo $arr["description"];
            break;
        case 'map': //맵에 관한 설명
            $param = $arr["output"]["param"][0];
            $id;
            switch ($param) {
                case 'Crystal Scar':
                    $id = CrystalScar;
                    break;
                case 'Howling Abyss':
                    $id = HowlingAbyss;
                    break;
                case "Summoner's Rift":
                    $id = SummonersRift;
                    break;
                case 'Twisted Treeline':
                    $id = TwistedTreeline;
                    break;
            }
            echo Map($id);
            break;
	 };
?>
</body>
</html>