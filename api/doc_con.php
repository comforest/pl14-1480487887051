<?php

    $arr = conversion("http://gameinfo.na.leagueoflegends.com/en/game-info/get-started/what-is-lol/");

    print_r($arr);


    function converstion($url){
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Document Conversion</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript">
        function getconversion(inputURL) {
            var arr = new Array(2);
            $.ajax({
                type: 'POST',
                url: 'html_download.php',
                dataType: 'html',
                data: {input: inputURL},
                async: false,
                success:function() {
                    $.ajax({
                        type: 'POST',
                        url: 'doc_conv.php',
                        dataType: 'json',
                        async: false,
                        success: function(data) {
                            console.log(data);
                            var len = data.answer_units.length;
                            arr.title = new Array(len);
                            arr.text = new Array(len);
                            for(var i=0; i<len; i++) {
                                arr.title[i] = data.answer_units[i].title;
                                arr.text[i] = data.answer_units[i].content[0].text;
                            }
                        },
                        error: function() {
                            alert(inputURL + ': conversion failed');
                        }
                    });
                },
                error: function() {
                    alert(inputURL + ': cannot download a html file')
                }
            });
            return arr;
        }
        function printAll(url) {
            var arr = getconversion(url);
            $(document).ajaxStop(function() {
                for(var i=0; i<arr.title.length; i++) {
                    document.write(arr.title[i]+'<br>');
                    document.write(arr.text[i]+'<br><br>');
                }
            });
        }

        function Question(url, num) {
            var arr = getconversion(url);
            $('#content').append(arr.text[num]+'<br>');
        }
        function WhatisLOL() {
            Question("http://gameinfo.na.leagueoflegends.com/en/game-info/get-started/what-is-lol/", 2);
        }
        function Naver() {
            Question("www.naver.com", 4);
        }
    </script>
</head>
<body>
<div id="content"></div>
<script>
    WhatisLOL();
    Naver();
</script>
</body>
</html>