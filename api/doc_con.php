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

    $data['config'] = "{\"conversion_target\":\"answer_units\"}";
    $data['file'] = "@". dirname(__FILE__). "/sample.pdf";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERPWD, "$userName:$userPwd");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_URL, $url);

    $output = curl_exec($ch);
    echo $output. '<br>';
    echo curl_error($ch);

    curl_close($ch);
?>
</body>
</html>