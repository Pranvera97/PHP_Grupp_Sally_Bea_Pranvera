<?php
error_reporting(-1);


// Skicka ut JSON till en anvĂ¤ndare
function send($data, $statusCode = 200) {
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $json = json_encode($data);
    echo $json;
    exit();
}

function loadJson($filename) {
    $json = file_get_contents($filename);
    return json_decode($json, true);
}

function saveJson($filename, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json);
}

function timeLog($message, $level = "[INFO]")
{
    $date = date('[ m/d/Y h:i:s ]', time());
    $helaMeddelandet = $date . $level . $message;
    file_put_contents("historik.log", $helaMeddelandet, FILE_APPEND);
}

?>