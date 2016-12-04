<?php
    function conversion($url){
        downloadHTML($url);
        $url = "https://gateway.watsonplatform.net/document-conversion/api/v1/convert_document?version=2015-12-15";
        $userName = "8108ea35-b6da-41f9-b866-9256512e3b5c";
        $userPwd = "3d1TCQCzeNke";

        $data['config'] = "{\"conversion_target\":\"answer_units\"}";
        $data['file'] = "@". dirname(__FILE__). "/temp.html";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERPWD, "$userName:$userPwd");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);

        $output = curl_exec($ch);

        curl_close($ch);
        unlink('temp.html');
        return $output;
    }

    function downloadHTML($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
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
        
    }

    function whatLoL() {
        $data = json_decode(conversion("http://gameinfo.na.leagueoflegends.com/en/game-info/get-started/what-is-lol/"), true);

        return $data["answer_units"][2]["content"][0]["text"];
    }
?>