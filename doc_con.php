<!DOCTYPE html>
<html>
<head>
	<title>Document Conversion</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<?php
	$url = "https://gateway.watsonplatform.net/document-conversion/api/v1/convert_document?version=2015-12-15";
    $userName = "8108ea35-b6da-41f9-b866-9256512e3b5c";
    $userPwd = "3d1TCQCzeNke";

    $data = array("input" => array("text"=>"Ashe is the most popular champion?"));

    $json_data = json_encode($data);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data))
    );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_USERPWD, "$userName:$userPwd");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $output = curl_exec($ch);
    echo $output. '<br>';

    curl_close($ch);
?>
</body>
</html>