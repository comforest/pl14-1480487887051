<?php
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_POST['inputURL']);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);

    //open file
    $fp = fopen(dirname(__FILE__). '/temp.html', 'w');
    //lock file
    flock($fp, LOCK_EX);
    //write and unlock file
    fwrite($fp, $output);
    flock($fp, LOCK_UN);
    fclose($fp);
?>