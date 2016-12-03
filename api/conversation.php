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
            foreach ($result as $key => $value) {
                if(count($result)-1 == $key) echo "and $value";
                else echo "$value, ";
            }
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
            // if($param == "tier"){

            // }else($param == "winrate"){

            // }
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
        case 'championList':
            $result = getChampionList();
            echo 'The Champions are ';
            foreach ($result as $key => $value) {
                if(count($result)-1 == $key) echo "and $value";
                else echo "$value, ";
            }
            break;

	 };
?>
</body>
<img src="">
</html>
