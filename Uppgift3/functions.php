<?php

// skickar JSON.
function send($data, $statusCode = 200) {
    header("Content-Type:application/json");
    http_response_code($statusCode);

    $json = json_encode($data);
    echo $json;
    exit();
}

// laddar JSON till associative array.
function loadJson($filename) {
    $json = file_get_contents($filename);
    return json_decode($json, true);
}

// sparar associative php till JSON + gör det fint.
function saveJson($filename, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json);
}

?>