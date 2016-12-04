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