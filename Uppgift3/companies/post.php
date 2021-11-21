<?php
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$contentType = $_SERVER["CONTENT_TYPE"];

// om det inte är i JSON, ge felmeddelande.
if ($contentType !== "application/json") {
    send(
        ["message" => "Bad request."],
        400
    );
}

// om metoden inte är POST, ge felmeddelande.
if ($requestMethod !== "POST") {
    send(
        ["message" => "Method not allowed."],
        405
    );
}

$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if ($requestMethod === "POST") {
    // skapa variabler
    $id = $requestData["id"];
    $companyName = $requestData["company_name"];
    $country = $requestData["country"];
    $address = $requestData["address"];
    $number = $requestData["phone_number"];
    
    if(!isset($companyName, $country, $address, $number)) {
        send(
            ["message" => "Bad request. There are missing keys."],
            400
        );
    }    
    
    if (isset($id)) {
        send(
            ["message" => "The company 'id' is not allowed. Try again by removing it."],
            400
        );
    }

    if (strlen($companyName) < 3) {
        send(
            ["Message" => "The company name needs to include 3 or more characters."],
            400
        );
    }

    if (strlen($country) < 4) {
        send(
            ["Message" => "Write the full name of the country. The key requires at least 4 letters."],
            400
        );
    }

    $companyData = loadJson("companies.json");


}

?>