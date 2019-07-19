<?php
    require_once "server.php";
    $server = new Server("");
    $query_str = $_SERVER['QUERY_STRING'];
    parse_str($query_str, $params);
    $url = $params["path"];
    $server->download($url);
