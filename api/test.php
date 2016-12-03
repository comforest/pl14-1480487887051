<!DOCTYPE html>
<html>
<head>
	<title>PHP Starter Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<h1>a</h1>
<?php 

    // create curl resource 
    $ch = curl_init();

    // set url 
    curl_setopt($ch, CURLOPT_URL, "http://gameinfo.na.leagueoflegends.com/en/game-info/get-started/what-is-lol/"); 

    //return the transfer as a string 

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // $output contains the output string 
    $out = curl_exec($ch);
    
    echo curl_errno($ch);
    curl_close($ch);      

    echo $out;
?>
</body>
</html>
