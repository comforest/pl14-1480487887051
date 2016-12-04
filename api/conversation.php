<?php 
	session_start();

    $workspace_id = 'a5f9a706-0017-4aae-9ae3-f5c7e1f7d9c0';
    $userName = "c22a9e67-2f46-4dda-9286-b21862276e30";
    $userPwd = "05MXPpfi8Gs2";

    if(!isset($_SESSION["dialog"])) $context = array("system"=>array("dialog_stack"=>array("root")));
    else $context = $_SESSION["dialog"];
    $data = array("input" => array("text"=>"$_POST[input]"), "context"=>$context);
    $data_string = json_encode($data);

    // create curl resource 
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/$workspace_id/message?version=2016-09-20"); 

    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_USERPWD, "$userName:$userPwd");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );
       

    // $output contains the output string 
    $out = curl_exec($ch);
    
    $arr = json_decode($out,true);

    curl_close($ch);

    $_SESSION["dialog"] = $arr["context"];


    include $_SERVER["DOCUMENT_ROOT"]."/api/riot.php";
    include $_SERVER["DOCUMENT_ROOT"]."/api/doc_con.php";

    if(isset($arr["output"]["data"])){
        $data = $arr["output"]["data"];
    }else{
        echo $arr["output"]["text"][0];
        return;
    }

	switch ($data) {
	 	case 'getRotationChampion':
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
	 	case 'includeRotationChampion':
            $name = $arr["output"]["param"][0];
            if(b_rotation_champ($name))
                echo "Yes";
            else
                echo "No";
	 		break;
        case 'rateSummoner':
            $param = $arr["output"]["param"];
            if($param[0] == "win"){
                echo "$param[1]'s win rate is ".win_rate($param[1]).'%';
            }
            break;
	 	case 'summoner':
            $param = $arr["output"]["param"][0];
            if($param == "tier"){
                $name = $arr["output"]["param"][1];
                $tier = getSummonerTier($name);
                echo "$name's Tier is $tier<br>";
                echo "<img src = '/images/tier/".$tier.".png'>";
            }
	 		break;
        case 'skin':
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
        case 'skill':
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
            break;
        case 'championList':
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
        case 'lol':
            echo whatLoL();
            break;
        case 'rankingTop':
            echo "The best player is ".rankingTop();
            break;
        case 'itemList':
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
        case 'itemInfo':
            $param = $arr["output"]["param"][0];
            $arr = itemInfo($param);
            echo $param." Information<br>";
            echo "<img src = 'http://ddragon.leagueoflegends.com/cdn/6.23.1/img/item/$arr[id].png ' height='70px'><br>";
            echo $arr["description"];
            break;
	 };
?>
</body>
</html>