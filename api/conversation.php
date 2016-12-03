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

    print_r($arr);

    if(count($arr["intents"]) != 1){
        echo "Sorry, I don't know...";
        return;
    }

    echo($arr["output"]["text"][0]);

	switch ($arr["intents"][0]["intent"]) {
	 	case 'popular':
	 		
	 		break;
	 	case 'win_rate':

	 		break;
	 	case 'ban_rate':
	 		
	 		break;
	 	case 'price':
	 		
	 		break;

	 };

 //    $result["intent"] = $arr2;
    $arr2 = [];
    foreach ($arr["entities"] as $k => $v) {
        $result[$v["entity"]][] = $v["value"];
    }
?>
</body>
</html>
