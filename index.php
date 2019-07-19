<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>抓包</title>
    <style>
        .content {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        button {
            width: 100px;
            height: 50px;
            background: #ff4081;
            box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
            border: none;
            margin: 0 5px;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="content">
        <?php
            require_once "server.php";
            $query_str = $_SERVER['QUERY_STRING'];
            parse_str($query_str, $params);
            $url = $params["path"];
            if (trim($url) == "") {
                $url = 'http://www.89file.com/file/QVNaMTQzNDk1.html';
            }
            $server = new Server($url);
            $fileData = $server->getWhatYouWant();
        ?>
        <button onclick="download(1)">VIP主力下载</>
        <button onclick="download(2)">VIP电信下载</>
        <button onclick="download(3)">VIP联通下载</>
    </div>

    <script>
        function download(tag) {
            switch (tag) {
                case 1:
                    window.location.href="<?php echo "/download.php?path=".$fileData[0] ?>"
                    break
                case 2:
                    window.location.href="<?php echo "/download.php?path=".$fileData[1] ?>"
                    break
                case 3:
                    window.location.href="<?php echo "/download.php?path=".$fileData[2] ?>"
                    break
            }
        }
    </script>
</body>
</html>