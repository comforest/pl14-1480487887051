<!DOCTYPE html>
<html>
<head>
	<title>PHP Starter Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<h1><a href="hj/mainpage.php">mainpage</a></h1>
<h1><a href="hj/doc_con.php">document conversion</a></h1>
<?php 

    $workspace_id = 'a5f9a706-0017-4aae-9ae3-f5c7e1f7d9c0';
    $userName = "c22a9e67-2f46-4dda-9286-b21862276e30";
    $userPwd = "05MXPpfi8Gs2";

    $data = array("input" => array("text"=>"Ashe is the most popular champion?"));
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

    print_r($arr);

    echo "<br>";
    print_r($arr["intents"]);
    echo "<br>";
    print_r($arr["entities"]);

    curl_close($ch);      


?>
</body>
</html>
