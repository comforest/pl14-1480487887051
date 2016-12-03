<!DOCTYPE html>
<html>
<head>
    <title>Document Conversion</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#doc_conv').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'html_download.php',
                    dataType: 'html',
                    data: {inputURL: $('#inputURL').val()},
                    success: function(data) {
                        $('#html').html(data);
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: 'doc_conv.php',
                    dataType: 'json',
                    success: function(data) {
                        $('#content').html(data.timestamp);
                    },
                    error: function() {
                        alert('error');
                    }
                });
            });
        });
    </script>
</head>
<body>
<input type="text" id="inputURL" value="http://gameinfo.na.leagueoflegends.com/en/game-info/get-started/what-is-lol/" style="width: 500px"> <button id="doc_conv">문서 분석</button><br>
<div id="html"></div>
<div id="content"></div>
</body>
</html>