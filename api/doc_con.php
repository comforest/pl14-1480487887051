<?php
    //conversion from html(from downloadHTML($url))
    function conversion($url){
        //download html file
        downloadHTML($url);

        //required informations
        $url = "https://gateway.watsonplatform.net/document-conversion/api/v1/convert_document?version=2015-12-15";
        $userName = "8108ea35-b6da-41f9-b866-9256512e3b5c";
        $userPwd = "3d1TCQCzeNke";

        //conversion temp.html(from downloadHTML($url))
        $data['config'] = "{\"conversion_target\":\"answer_units\"}";
        $data['file'] = "@". dirname(__FILE__). "/temp.html";

        //start curl
        $ch = curl_init();
        //set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERPWD, "$userName:$userPwd");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);

        //execute output
        $output = curl_exec($ch);
        curl_close($ch);
        unlink('temp.html');

        return $output;
    }

    //download HTML from url
    function downloadHTML($url){
        //start curl
        $ch = curl_init();
        //set options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //execute output
        $output = curl_exec($ch);
        curl_close($ch);

        //open file
        $fp = fopen(dirname(__FILE__). '/temp.html', 'w');
        //lock file
        flock($fp, LOCK_EX);
        //write and unlock file
        fwrite($fp, $output);
        flock($fp, LOCK_UN);
        //file close
        fclose($fp);
    }

    //print conversioned data (not use)
    function printconv($url) {
        //decode json data
        $temp = json_decode(conversion($url), true);

        //print data
        for($i=0; $i<count($temp["answer_units"]); $i++) {
            echo $i. '<br>';
            echo $temp["answer_units"][$i]["title"]. '<br>';
            echo $temp["answer_units"][$i]["content"][0]["text"]. '<br><br>';
        }
    }

    //function for question "What is lol?"
    function whatLoL() {
        //decode json data
        $data = json_decode(conversion("http://gameinfo.na.leagueoflegends.com/en/game-info/get-started/what-is-lol/"), true);
        //return text
        return $data["answer_units"][2]["content"][0]["text"];
    }

    //define
    define("SummonersRift", 4);
    define("TwistedTreeline", 5);
    define("HowlingAbyss", 6);
    define("CrystalScar", 7);
    //function for question "What types of map are in lol?"
    function Map($id) {
        //decode json data
        $data = json_decode(conversion("https://en.wikipedia.org/wiki/League_of_Legends"), true);
        //return text
        return $data["answer_units"][$id]["content"][0]["text"];
    }
?>