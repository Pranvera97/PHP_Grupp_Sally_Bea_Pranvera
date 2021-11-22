<?php
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$contentType = $_SERVER["CONTENT_TYPE"];

// om det inte är i JSON, ge felmeddelande.
if ($contentType !== "application/json") {
    send(
        ["message" => "Bad request. You're missing 'Content-Type'."],
        400
    );
}

// om metoden inte är POST, ge felmeddelande.
if ($requestMethod !== "POST") {
    send(
        ["message" => "Method not allowed. You can only use 'POST'."],
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
    
    // olika ändpunkter om det inte skulle fungera.
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
            ["Message" => "The company name needs to include 3 or more letters."],
            400
        );
    }

    if (strlen($country) < 4) {
        send(
            ["Message" => "Write the full name of the country. The key requires at least 4 letters."],
            400
        );
    }

    if (strlen($country) > 25) {
        send(
            ["Message" => "The country name only allows the maximum of 25 letters."],
            400
        );
    }

    if (!is_numeric($number)) {
        send(
            ["message" => "The phone number must only consist of numbers."],
            400
        );
    }

    if (strlen($number) != 10) {
        send(
            ["message" => "The phone number needs to have 10 digits."],
            400
        );
    }

    // när det fungerar.
    $companyData = loadJson("companies.json");

    $highestID = 0;

    foreach ($companyData as $index => $companyID) {
        if ($companyID["id"] > $highestID) {
            $highestID = $companyID["id"];
        }
    }

    // nytt företag
    $newCompany = [
        "id" => $highestID + 1,
        "company_name" => $companyName,
        "country" => $country,
        "address" => $address,
        "phone_number" => $number
    ];

    // lägga till i companies.json
    array_push($companyData, $newCompany);

    // spara det nya innehållet
    saveJson("companies.json", $companyData);
    send($newCompany, 201);
}

?>